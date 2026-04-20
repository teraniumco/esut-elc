<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('portal.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && !$user->is_active) {
            return back()->withErrors(['email' => 'This account has been deactivated.'])->withInput();
        }

        if ($user && !$user->hasAcceptedInvite()) {
            return back()->withErrors(['email' => 'Your account setup is incomplete. Please use the invite link sent to your email.'])->withInput();
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput();
        }

        $request->session()->regenerate();
        Auth::user()->update(['last_login_at' => now()]);
        ActivityLog::record('user.login', Auth::user());

        return redirect()->route('portal.dashboard');
    }

    public function logout(Request $request)
    {
        ActivityLog::record('user.logout', Auth::user());
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('portal.login')->with('success', 'You have been logged out.');
    }
}
