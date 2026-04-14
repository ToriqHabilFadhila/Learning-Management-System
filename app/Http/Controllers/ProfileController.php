<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile()
    {
        return view('profile.index', [
            'user' => Auth::user()
        ]);
    }

    public function settings()
    {
        return view('profile.settings', [
            'user' => Auth::user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($request->hasFile('avatar')) {
            $avatarDir = storage_path('app/public/avatars');
            if (!is_dir($avatarDir)) {
                mkdir($avatarDir, 0755, true);
            }
            
            if ($user->profile_picture) {
                $oldPath = $avatarDir . '/' . $user->profile_picture;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            $file = $request->file('avatar');
            $filename = time() . '_' . $user->id_user . '.' . $file->getClientOriginalExtension();
            $file->move($avatarDir, $filename);
            $validated['profile_picture'] = $filename;
            unset($validated['avatar']);
        }
        
        $user->fill($validated)->save();
        return back()->with('success', 'Profil Anda berhasil diperbarui! Perubahan telah disimpan.')
            ->with('redirect_delay', false);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        /** @var User $user */
        $user = Auth::user();
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama yang Anda masukkan tidak sesuai. Silakan periksa kembali.'])
                ->with('warning', 'Password lama salah.');
        }
        $user->password = Hash::make($validated['password']);
        $user->save();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru Anda.');
    }
}
