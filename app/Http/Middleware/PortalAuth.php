<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PortalAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('portal.login')
                ->with('error', 'Please log in to access the portal.');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('portal.login')
                ->with('error', 'Your account has been deactivated. Contact an administrator.');
        }

        if (!auth()->user()->hasAcceptedInvite()) {
            auth()->logout();
            return redirect()->route('portal.login')
                ->with('error', 'Please complete your account setup using the invite link sent to your email.');
        }

        return $next($request);
    }
}
