<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class GoogleController extends Controller
{
    /**
     * Redirect ke Google + paksa pilih akun
     */
    public function redirect(Request $request): RedirectResponse
    {
        $mode = $request->query('mode', 'login');
        session(['google_auth_mode' => $mode]);

        /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
        $driver = Socialite::driver('google');

        return $driver
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Download dan simpan avatar Google ke local storage
                $profilePicture = $this->downloadAndSaveAvatar($googleUser->getAvatar(), $googleUser->getEmail());
                
                // Auto-register Google user (trusted)
                $user = User::create([
                    'nama' => $googleUser->getName() ?? 'User Google',
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(32)),
                    'role' => 'siswa',
                    'is_active' => true,
                    'is_verified' => true,
                    'email_verified_at' => now(),
                    'last_login' => now(),
                    'profile_picture' => $profilePicture,
                ]);
            } else {
                $user->update([
                    'last_login' => now(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
            }

            Auth::login($user);
            $request = request();
            $request->session()->regenerate();

            return redirect()->route('dashboard')
                ->with('success', 'Selamat datang, ' . $user->nama . '!');


        } catch (\Exception $e) {
            Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->withErrors(['email' => 'Terjadi kesalahan saat menghubungkan dengan Google.']);
        }
    }

    /**
     * Download avatar dari Google dan simpan ke local storage
     */
    private function downloadAndSaveAvatar($avatarUrl, $email)
    {
        try {
            if (!$avatarUrl) {
                return null;
            }

            // Download image dari Google
            $response = Http::get($avatarUrl);
            if (!$response->successful()) {
                Log::warning('Failed to download Google avatar for ' . $email);
                return null;
            }

            // Buat folder jika belum ada
            $avatarDir = storage_path('app/public/avatars');
            if (!is_dir($avatarDir)) {
                mkdir($avatarDir, 0755, true);
            }

            // Simpan dengan nama unik
            $filename = 'google_' . time() . '_' . md5($email) . '.jpg';
            $filePath = $avatarDir . '/' . $filename;
            file_put_contents($filePath, $response->body());

            Log::info('Google avatar saved for ' . $email . ': ' . $filename);
            return $filename;

        } catch (\Exception $e) {
            Log::error('Error downloading Google avatar: ' . $e->getMessage());
            return null;
        }
    }

}
