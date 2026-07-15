<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\MemberSubscription;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMembers = User::where('role', 'customer')->count();
        $activeMembers = MemberSubscription::where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->distinct()
            ->count('user_id');
        $pendingPayments = MemberSubscription::where('status', 'pending')->count();
        $todayAttendance = AttendanceLog::whereDate('scanned_at', today())->count();
        $monthlyRevenue = MemberSubscription::where('status', 'active')
            ->whereMonth('confirmed_at', now()->month)
            ->sum('amount_paid');

        $recentPayments = MemberSubscription::where('status', 'pending')
            ->with(['user', 'membership'])
            ->latest()
            ->take(5)
            ->get();

        $recentAttendance = AttendanceLog::with(['user', 'subscription.membership'])
            ->latest('scanned_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalMembers', 'activeMembers', 'pendingPayments',
            'todayAttendance', 'monthlyRevenue', 'recentPayments', 'recentAttendance'
        ));
    }
}
