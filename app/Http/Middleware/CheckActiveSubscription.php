<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Admin tidak perlu membership
        if ($user->isAdmin()) {
            return $next($request);
        }

        $activeSubscription = $user->activeSubscription()->first();
        if (!$activeSubscription) {
            return redirect()->route('customer.membership')
                ->with('warning', 'Akses ditolak. Silakan beli atau perpanjang membership Anda.');
        }

        return $next($request);
    }
}
