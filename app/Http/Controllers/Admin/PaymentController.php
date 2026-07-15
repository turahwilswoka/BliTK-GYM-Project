<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = MemberSubscription::with(['user', 'membership'])
            ->orderByRaw("CASE status
                WHEN 'pending'   THEN 1
                WHEN 'active'    THEN 2
                WHEN 'expired'   THEN 3
                WHEN 'cancelled' THEN 4
                ELSE 5 END")
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function confirm(MemberSubscription $subscription)
    {
        if ($subscription->status !== 'pending') {
            return back()->with('error', 'Hanya subscription pending yang bisa dikonfirmasi.');
        }

        $membership = $subscription->membership;
        $startDate = now()->toDateString();
        $endDate = now()->addDays($membership->duration_days)->toDateString();

        $subscription->update([
            'status' => 'active',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'confirmed_at' => now(),
            'confirmed_by' => Auth::id(),
        ]);

        return back()->with('success', "Membership {$subscription->user->name} berhasil diaktifkan hingga {$endDate}.");
    }

    public function reject(Request $request, MemberSubscription $subscription)
    {
        if ($subscription->status !== 'pending') {
            return back()->with('error', 'Hanya subscription pending yang bisa ditolak.');
        }

        $subscription->update(['status' => 'cancelled']);

        return back()->with('success', "Pembayaran {$subscription->user->name} berhasil ditolak.");
    }
}
