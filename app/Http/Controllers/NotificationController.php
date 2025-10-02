<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AuditNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(15);
        return view('pages.notifikasi', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return redirect()->back()->with('success', 'Notifikasi ditandai sebagai dibaca');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
        return redirect()->back()->with('success', 'Semua notifikasi ditandai sebagai dibaca');
    }

    public function sendAuditReminder(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'audit_id' => 'required|exists:audits,id',
            'message' => 'required|string'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->notify(new AuditNotification($request->message, $request->audit_id));

        return redirect()->back()->with('success', 'Pengingat berhasil dikirim');
    }
}
