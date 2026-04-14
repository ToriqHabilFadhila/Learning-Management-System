<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\AuthServices;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\FCMTokenController;

/*
|--------------------------------------------------------------------------
| PUBLIC PAGES
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('landing-page'));

// Firebase Service Worker - served dynamically to inject env config
Route::get('/firebase-messaging-sw.js', function () {
    return response(view('firebase-sw'), 200, [
        'Content-Type' => 'application/javascript',
        'Service-Worker-Allowed' => '/',
    ]);
});
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::get('/forgot-password', [PageController::class, 'forgot'])->name('password.request');


/*
|--------------------------------------------------------------------------
| AUTH ACTIONS (WITH RATE LIMITING)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthServices::class, 'login'])
    ->middleware('throttle:5,15')
    ->name('login.submit');

Route::post('/register', [AuthServices::class, 'register'])
    ->middleware('throttle:3,60')
    ->name('register.submit');

Route::post('/logout', [AuthServices::class, 'logout'])->name('logout');

// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.auth');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// Email verification routes
Route::get('/email/verify', [AuthServices::class, 'verificationNotice'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthServices::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');
Route::post('/email/verify/resend', [AuthServices::class, 'resendVerification'])
    ->middleware('throttle:3,60')
    ->name('verification.resend');

/*
|--------------------------------------------------------------------------
| PASSWORD RESET (WITH RATE LIMITING)
|--------------------------------------------------------------------------
*/
Route::post('/forgot-password', [AuthServices::class, 'sendResetLink'])
    ->middleware('throttle:3,60')
    ->name('password.email');

Route::get('/reset-password/{token}', [AuthServices::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [AuthServices::class, 'resetPassword'])
    ->middleware('throttle:3,60')
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| DASHBOARD (ROLE BASED)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE & SETTINGS
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/browser', [NotificationController::class, 'sendBrowserNotification'])->name('notifications.browser');
    
    // FCM Token
    Route::post('/api/fcm-token', [FCMTokenController::class, 'store'])->name('fcm.token.store');
    Route::delete('/api/fcm-token', [FCMTokenController::class, 'destroy'])->name('fcm.token.destroy');
});

/*
|--------------------------------------------------------------------------
| PROTECTED AREA (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'getUsers'])
            ->name('admin.users');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])
            ->name('admin.users.store');
        Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])
            ->name('admin.users.update');
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])
            ->name('admin.users.delete');
        Route::get('/admin/classes', [AdminController::class, 'getClasses'])
            ->name('admin.classes');
        Route::delete('/admin/classes/{id}', [AdminController::class, 'deleteClass'])
            ->name('admin.classes.delete');
        Route::get('/admin/monitoring', [AdminController::class, 'getMonitoring'])
            ->name('admin.monitoring');
    });

    /*
    |--------------------------------------------------------------------------
    | GURU
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:guru')->group(function () {
        Route::post('/guru/classes', [GuruController::class, 'storeClass'])
            ->name('guru.classes.store');
        Route::post('/guru/materials', [GuruController::class, 'storeMaterial'])
            ->name('guru.materials.store');
        Route::post('/guru/assignments', [GuruController::class, 'storeAssignment'])
            ->name('guru.assignments.store');
        Route::get('/guru/assignments/{id}/questions', [GuruController::class, 'showQuestions'])
            ->name('guru.assignments.questions');
        Route::post('/guru/assignments/{id}/questions', [GuruController::class, 'storeQuestion'])
            ->name('guru.questions.store');
        Route::post('/guru/assignments/{id}/questions/generate', [GuruController::class, 'generateQuestions'])
            ->name('guru.questions.generate');
        Route::put('/guru/assignments/{id}/deadline', [GuruController::class, 'updateAssignmentDeadline'])
            ->name('guru.assignments.deadline');
        Route::delete('/guru/assignments/{id}', [GuruController::class, 'deleteAssignment'])
            ->name('guru.assignments.delete');
        Route::get('/guru/assignments/{id}/submissions', [GuruController::class, 'showSubmissions'])
            ->name('guru.assignments.submissions');
        Route::put('/guru/submissions/{id}/grade', [GuruController::class, 'gradeSubmission'])
            ->name('guru.submissions.grade');
        Route::get('/guru/classes/{id}', [GuruController::class, 'showClass'])
            ->name('guru.classes.show');
        Route::get('/guru/classes/{id}/students', [GuruController::class, 'getStudents'])
            ->name('guru.classes.students');
        Route::put('/guru/questions/{id}', [GuruController::class, 'updateQuestion'])
            ->name('guru.questions.update');
        Route::delete('/guru/questions/{id}', [GuruController::class, 'deleteQuestion'])
            ->name('guru.questions.delete');
        Route::delete('/guru/assignments/{id}/questions/bulk-delete', [GuruController::class, 'bulkDeleteQuestions'])
            ->name('guru.questions.bulkDelete');
        Route::post('/guru/assignments/{id}/publish', [GuruController::class, 'publishAssignment'])
            ->name('guru.assignments.publish');
        Route::get('/guru/ai/analyze/{userId}/{classId}', [AIController::class, 'analyzeProgress'])
            ->name('guru.ai.analyze');
        Route::post('/guru/ai/grade', [AIController::class, 'autoGrade'])
            ->name('guru.ai.grade');
        Route::post('/guru/classes/{id}/regenerate-token', [GuruController::class, 'regenerateToken'])
            ->name('guru.classes.regenerateToken');
        Route::get('/guru/classes/{id}/progress', [GuruController::class, 'getClassProgress'])
            ->name('guru.classes.progress');
    });

    /*
    |--------------------------------------------------------------------------
    | SISWA
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:siswa')->group(function () {
        Route::get('/siswa/kelas', [SiswaController::class, 'index'])
            ->name('siswa.kelas');
        Route::post('/siswa/join-kelas', [SiswaController::class, 'join'])
            ->name('siswa.join');
        Route::get('/siswa/classes/{id}', [SiswaController::class, 'showClass'])
            ->name('siswa.classes.show');
        Route::get('/siswa/assignments/{id}', [SiswaController::class, 'showAssignment'])
            ->name('siswa.assignments.show');
        Route::post('/siswa/assignments/{id}/submit', [SiswaController::class, 'submitAssignment'])
            ->name('siswa.assignments.submit');
        Route::get('/siswa/submissions/{id}', [SiswaController::class, 'showSubmission'])
            ->name('siswa.submissions.show');
        Route::get('/siswa/materials', [SiswaController::class, 'materials'])
            ->name('siswa.materials');
        Route::get('/siswa/recommendations', fn() => view('siswa.recommendations'))
            ->name('siswa.recommendations');
        Route::post('/siswa/ai/feedback', [AIController::class, 'getFeedback'])
            ->name('siswa.ai.feedback');
        Route::get('/siswa/ai/recommendations', [AIController::class, 'getRecommendations'])
            ->name('ai.recommendations');
        Route::get('/siswa/token/{token}/info', [SiswaController::class, 'getTokenInfo'])
            ->name('siswa.token.info');
        Route::get('/siswa/progress', [SiswaController::class, 'getProgress'])
            ->name('siswa.progress');
    });
});
