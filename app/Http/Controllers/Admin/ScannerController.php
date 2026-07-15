<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\MemberSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScannerController extends Controller
{
    public function index()
    {
        $todayLogs = AttendanceLog::with(['user', 'subscription.membership'])
            ->whereDate('scanned_at', today())
            ->latest('scanned_at')
            ->get();

        return view('admin.scanner', compact('todayLogs'));
    }

    public function scan(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        $token = $request->token;

        $subscription = MemberSubscription::where('qr_token', $token)
            ->with(['user', 'membership'])
            ->first();

        if (!$subscription) {
            return response()->json([
                'valid' => false,
                'status' => 'INVALID',
                'message' => 'QR Code tidak valid.',
            ]);
        }

        if (!$subscription->isActive()) {
            return response()->json([
                'valid' => false,
                'status' => $subscription->status === 'cancelled' ? 'CANCELLED' : 'EXPIRED',
                'message' => 'Membership sudah tidak aktif.',
                'member' => [
                    'name' => $subscription->user->name,
                    'membership' => $subscription->membership->name,
                    'end_date' => $subscription->end_date?->format('d/m/Y'),
                ],
            ]);
        }

        // Log attendance
        $log = AttendanceLog::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'scanned_by' => Auth::id(),
            'scanned_at' => now(),
        ]);

        return response()->json([
            'valid' => true,
            'status' => 'VALID',
            'message' => 'Selamat datang di BliTK Gym! ✅',
            'member' => [
                'name' => $subscription->user->name,
                'email' => $subscription->user->email,
                'phone' => $subscription->user->phone,
                'membership' => $subscription->membership->name,
                'badge_color' => $subscription->membership->badge_color,
                'end_date' => $subscription->end_date?->format('d/m/Y'),
                'days_remaining' => $subscription->days_remaining,
                'photo_url' => $subscription->user->photo_url,
            ],
        ]);
    }

    public function attendance()
    {
        $logs = AttendanceLog::with(['user', 'subscription.membership', 'scannedBy'])
            ->latest('scanned_at')
            ->paginate(20);

        return view('admin.attendance', compact('logs'));
    }
}
