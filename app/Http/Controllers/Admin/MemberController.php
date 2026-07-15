<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->with('activeSubscription');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->status === 'active') {
            $query->whereHas('subscriptions', fn($q) => $q->where('status', 'active')->where('end_date', '>=', now()->toDateString()));
        } elseif ($request->status === 'inactive') {
            $query->whereDoesntHave('subscriptions', fn($q) => $q->where('status', 'active')->where('end_date', '>=', now()->toDateString()));
        }

        $members = $query->latest()->paginate(15);
        return view('admin.members.index', compact('members'));
    }

    public function show(User $user)
    {
        $user->load(['subscriptions.membership', 'attendanceLogs.subscription.membership']);
        $activeSubscription = $user->activeSubscription()->with('membership')->first();
        return view('admin.members.show', compact('user', 'activeSubscription'));
    }
}
