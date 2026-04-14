<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FCMTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = Auth::user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'FCM token berhasil disimpan',
        ]);
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->fcm_token = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'FCM token berhasil dihapus',
        ]);
    }
}
