<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\MemberSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription()->with('membership')->first();

        if (!$subscription) {
            return redirect()->route('customer.membership')
                ->with('error', 'Anda tidak memiliki membership aktif.');
        }

        return view('customer.qrcode', compact('user', 'subscription'));
    }

    public function generate($token)
    {
        $subscription = MemberSubscription::where('qr_token', $token)->first();

        if (!$subscription) {
            abort(404);
        }

        // Generate QR code as SVG
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate(route('qrcode.validate', ['token' => $token]));

        return response($qrCode, 200)->header('Content-Type', 'image/svg+xml');
    }

    public function validate(Request $request, $token = null)
    {
        $tokenVal = $token ?? $request->get('token');
        $subscription = MemberSubscription::where('qr_token', $tokenVal)
            ->with(['user', 'membership'])
            ->first();

        if (!$subscription) {
            return response()->json([
                'valid' => false,
                'status' => 'INVALID',
                'message' => 'QR Code tidak valid atau tidak ditemukan.',
            ]);
        }

        if (!$subscription->isActive()) {
            return response()->json([
                'valid' => false,
                'status' => 'EXPIRED',
                'message' => 'Membership sudah kadaluarsa.',
                'member' => [
                    'name' => $subscription->user->name,
                    'membership' => $subscription->membership->name,
                    'end_date' => $subscription->end_date?->format('d/m/Y'),
                ],
            ]);
        }

        // Log attendance jika scan dari admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            AttendanceLog::create([
                'user_id' => $subscription->user_id,
                'subscription_id' => $subscription->id,
                'scanned_by' => Auth::id(),
                'scanned_at' => now(),
            ]);
        }

        return response()->json([
            'valid' => true,
            'status' => 'VALID',
            'message' => 'Member valid! Selamat datang di BliTK Gym.',
            'member' => [
                'name' => $subscription->user->name,
                'email' => $subscription->user->email,
                'phone' => $subscription->user->phone,
                'membership' => $subscription->membership->name,
                'badge_color' => $subscription->membership->badge_color,
                'start_date' => $subscription->start_date?->format('d/m/Y'),
                'end_date' => $subscription->end_date?->format('d/m/Y'),
                'days_remaining' => $subscription->days_remaining,
                'photo_url' => $subscription->user->photo_url,
            ],
        ]);
    }
}
