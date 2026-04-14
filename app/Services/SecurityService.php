<?php

namespace App\Services;

use App\Models\User;
use App\Models\Classes;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;

class SecurityService
{
    /**
     * Check if user is guru
     */
    public static function isGuru(User $user): bool
    {
        return $user->role === 'guru' && $user->is_active;
    }

    /**
     * Check if user is siswa
     */
    public static function isSiswa(User $user): bool
    {
        return $user->role === 'siswa' && $user->is_active;
    }

    /**
     * Check if user owns the class
     */
    public static function ownsClass(User $user, Classes $class): bool
    {
        return $class->created_by === $user->id_user && self::isGuru($user);
    }

    /**
     * Check if user is enrolled in class
     */
    public static function isEnrolledInClass(User $user, Classes $class): bool
    {
        return $class->enrollments()
            ->where('id_user', $user->id_user)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Check if user owns the assignment
     */
    public static function ownsAssignment(User $user, Assignment $assignment): bool
    {
        return $assignment->created_by === $user->id_user && self::isGuru($user);
    }

    /**
     * Check if user can access assignment
     */
    public static function canAccessAssignment(User $user, Assignment $assignment): bool
    {
        // Guru can access their own assignments
        if (self::ownsAssignment($user, $assignment)) {
            return true;
        }

        // Siswa can access published assignments in their classes
        if ($assignment->is_published && self::isSiswa($user)) {
            return self::isEnrolledInClass($user, $assignment->class);
        }

        return false;
    }

    /**
     * Check if user owns the submission
     */
    public static function ownsSubmission(User $user, Submission $submission): bool
    {
        return $submission->id_user === $user->id_user;
    }

    /**
     * Check if user can grade submission
     */
    public static function canGradeSubmission(User $user, Submission $submission): bool
    {
        return self::ownsAssignment($user, $submission->assignment);
    }

    /**
     * Authorize class access
     */
    public static function authorizeClassAccess(User $user, Classes $class): void
    {
        if (!self::ownsClass($user, $class) && !self::isEnrolledInClass($user, $class)) {
            self::logUnauthorizedAccess($user, 'class', $class->id_class);
            throw new AuthorizationException('Anda tidak memiliki akses ke kelas ini');
        }
    }

    /**
     * Authorize assignment access
     */
    public static function authorizeAssignmentAccess(User $user, Assignment $assignment): void
    {
        if (!self::canAccessAssignment($user, $assignment)) {
            self::logUnauthorizedAccess($user, 'assignment', $assignment->id_assignment);
            throw new AuthorizationException('Anda tidak memiliki akses ke tugas ini');
        }
    }

    /**
     * Authorize submission access
     */
    public static function authorizeSubmissionAccess(User $user, Submission $submission): void
    {
        if (!self::ownsSubmission($user, $submission) && !self::canGradeSubmission($user, $submission)) {
            self::logUnauthorizedAccess($user, 'submission', $submission->id_submission);
            throw new AuthorizationException('Anda tidak memiliki akses ke submission ini');
        }
    }

    /**
     * Authorize class management
     */
    public static function authorizeClassManagement(User $user, Classes $class): void
    {
        if (!self::ownsClass($user, $class)) {
            self::logUnauthorizedAccess($user, 'class_management', $class->id_class);
            throw new AuthorizationException('Anda tidak memiliki izin untuk mengelola kelas ini');
        }
    }

    /**
     * Authorize assignment management
     */
    public static function authorizeAssignmentManagement(User $user, Assignment $assignment): void
    {
        if (!self::ownsAssignment($user, $assignment)) {
            self::logUnauthorizedAccess($user, 'assignment_management', $assignment->id_assignment);
            throw new AuthorizationException('Anda tidak memiliki izin untuk mengelola tugas ini');
        }
    }

    /**
     * Authorize grading
     */
    public static function authorizeGrading(User $user, Submission $submission): void
    {
        if (!self::canGradeSubmission($user, $submission)) {
            self::logUnauthorizedAccess($user, 'grading', $submission->id_submission);
            throw new AuthorizationException('Anda tidak memiliki izin untuk menilai submission ini');
        }
    }

    /**
     * Check if assignment deadline has passed
     */
    public static function isDeadlinePassed(Assignment $assignment): bool
    {
        return now()->isAfter($assignment->deadline);
    }

    /**
     * Check if submission is late
     */
    public static function isSubmissionLate(Submission $submission): bool
    {
        return $submission->submitted_at->isAfter($submission->assignment->deadline);
    }

    /**
     * Validate file upload security
     */
    public static function validateFileUpload($file, array $allowedMimes, int $maxSizeKB): bool
    {
        // Check file size
        if ($file->getSize() > ($maxSizeKB * 1024)) {
            return false;
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $allowedMimes)) {
            return false;
        }

        return true;
    }

    /**
     * Sanitize user input
     */
    public static function sanitizeInput(string $input): string
    {
        // Remove potentially dangerous characters
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return trim($input);
    }

    /**
     * Log unauthorized access attempt
     */
    private static function logUnauthorizedAccess(User $user, string $resource, $resourceId): void
    {
        Log::warning('Unauthorized access attempt', [
            'user_id' => $user->id_user,
            'user_email' => $user->email,
            'resource' => $resource,
            'resource_id' => $resourceId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
    }

    /**
     * Check rate limiting for sensitive operations
     */
    public static function checkRateLimit(User $user, string $action, int $maxAttempts = 10, int $decayMinutes = 60): bool
    {
        $key = "rate_limit:{$user->id_user}:{$action}";
        $attempts = cache()->get($key, 0);

        if ($attempts >= $maxAttempts) {
            Log::warning('Rate limit exceeded', [
                'user_id' => $user->id_user,
                'action' => $action,
                'attempts' => $attempts,
            ]);
            return false;
        }

        cache()->put($key, $attempts + 1, now()->addMinutes($decayMinutes));
        return true;
    }

    /**
     * Verify CSRF token
     */
    public static function verifyCsrfToken(string $token): bool
    {
        return hash_equals(csrf_token(), $token);
    }
}
