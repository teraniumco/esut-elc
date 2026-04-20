<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    /** Show the set-password form */
    public function show(Request $request, string $token)
    {
        $user = User::where('invite_token', $token)->first();

        if (!$user || !$user->hasValidInviteToken($token)) {
            return view('auth.invite-invalid');
        }

        return view('auth.accept-invite', compact('user', 'token'));
    }

    /** Accept the invite and set password */
    public function accept(Request $request, string $token)
    {
        $user = User::where('invite_token', $token)->first();

        if (!$user || !$user->hasValidInviteToken($token)) {
            return redirect()->route('portal.login')
                ->with('error', 'This invite link is invalid or has expired.');
        }

        $request->validate([
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'The passwords do not match.',
            'password.min'       => 'Password must be at least 8 characters.',
        ]);

        $user->acceptInvite($request->password);
        ActivityLog::record('user.invite_accepted', $user, [], $user);

        \Illuminate\Support\Facades\Auth::login($user);
        $user->update(['last_login_at' => now()]);

        return redirect()->route('portal.dashboard')
            ->with('success', 'Welcome to the ESUT Law Clinic portal, ' . $user->name . '!');
    }
}
