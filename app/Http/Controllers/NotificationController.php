<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Notification::where('id_notification', $id)
            ->where('id_user', Auth::user()->id_user)
            ->firstOrFail();
        $notification->update(['is_read' => true]);
        
        // Redirect ke halaman terkait jika ada
        if ($notification->related_id) {
            if ($notification->type === 'new_assignment') {
                return redirect()->route('siswa.assignments.show', $notification->related_id);
            } elseif ($notification->type === 'grade') {
                $submission = \App\Models\Submission::find($notification->related_id);
                if ($submission) {
                    return redirect()->route('siswa.submissions.show', $submission->id_submission);
                }
            }
        }
        
        return redirect()->back();
    }

    public function sendBrowserNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'url' => 'nullable|string'
        ]);
        return response()->json(['success' => true]);
    }
}
