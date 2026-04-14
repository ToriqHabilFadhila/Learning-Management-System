<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use App\Mail\VerifyEmailMail;

class AuthServices extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // is_active gatekeeper for all logins
        $user = User::active()->where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau password salah, atau akun tidak aktif.'
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $user->last_login = now();
        $user->save();
        return redirect()
            ->route('dashboard')
            ->with('success', 'Selamat datang, ' . $user->nama . '! Login berhasil.')
            ->with('redirect_delay', true);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'nama'        => $validated['nama'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'role'        => 'siswa',
            'is_active'   => false,
            'is_verified' => false,
            'last_login'  => null,
        ]);

        // Notify admin about new user registration
        \App\Services\AdminNotificationService::notifyUserRegistered($user);

        // Send verification email
        try {
            $verifyUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addHours(24),
                ['id' => $user->id_user, 'hash' => sha1($user->email)]
            );
            Mail::to($user->email)->send(new VerifyEmailMail($user, $verifyUrl));
        } catch (\Exception $e) {
            Log::error('Verification email failed: ' . $e->getMessage());
        }

        return redirect()->route('login')
            ->with('success', 'Akun dibuat! Silakan cek email untuk verifikasi dan login.')
            ->with('redirect_delay', true);
    }

    public function verificationNotice()
    {
        return view('auth.verify-email-sent');
    }

    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if ($user->is_active && $user->is_verified) {
            return back()->withErrors(['email' => 'Email sudah terverifikasi.']);
        }

        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addHours(24),
            ['id' => $user->id_user, 'hash' => sha1($user->email)]
        );

        Mail::to($user->email)->send(new VerifyEmailMail($user, $verifyUrl));

        return back()->with('success', 'Email verifikasi baru dikirim!');
    }

    public function verifyEmail(Request $request, string $id, string $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->email))) {
            return redirect()->route('login')
                ->with('error', 'Link verifikasi tidak valid.');
        }

        $user->email_verified_at = now();
        $user->is_active = true;
        $user->is_verified = true;
        $user->last_login = now();
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Email terverifikasi! Selamat datang ' . $user->nama . '!');
    }

    /**
     * Kirim email reset password
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            // Cari user berdasarkan email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()->withErrors([
                    'email' => 'Email "' . $request->email . '" tidak ditemukan dalam sistem kami. Pastikan email yang Anda masukkan benar.'
                ])->with('warning', 'Email tidak terdaftar.');
            }

            // Generate token menggunakan Laravel Password Broker
            $token = Password::createToken($user);

            // Kirim email (urutan parameter: token, email)
            Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email));

            // Log untuk debugging
            Log::info('Reset password email sent', [
                'email' => $request->email,
                'token' => $token,
            ]);

            return back()->with('success', 'Email reset password telah dikirim ke ' . $request->email . '. Silakan periksa inbox atau folder spam Anda.')
                ->with('redirect_delay', false);

        } catch (\Exception $e) {
            // Log error
            Log::error('Failed to send reset password email', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors([
                'email' => 'Gagal mengirim email reset password. Silakan periksa koneksi internet Anda dan coba lagi nanti.'
            ])->with('warning', 'Terjadi gangguan sistem.');
        }
    }

    /**
     * Tampilkan form reset password (dari link email)
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.forgot-password', [ // sama Blade
            'mode'  => 'reset', // switch ke form reset password
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Proses reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.')
                ->with('redirect_delay', true)
            : back()->withErrors(['email' => 'Reset password gagal. ' . __($status)])->with('warning', 'Terjadi kesalahan saat reset password.');
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user && str_ends_with($user->email, '@guest.local')) {
            $user->delete();
            // Reset sequence supaya guest berikutnya pakai ID berturut-turut
            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('users', 'id_user'),
                    COALESCE((SELECT MAX(id_user) FROM users), 0) + 1,
                    false
                )
            ");
        }

        return redirect()
            ->route('login')
            ->with('success', 'Logout berhasil. Terima kasih telah menggunakan platform kami!')
            ->with('redirect_delay', true);
    }
}

