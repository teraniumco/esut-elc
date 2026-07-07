<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('invitedBy')->latest();

        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->filled('status')) $query->where('is_active', $request->status === 'active');
        if ($request->filled('search')) $query->where(fn($q) =>
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%")
        );

        $users = $query->paginate(20)->withQueryString();

        $counts = [
            'total'       => User::count(),
            'admin'       => User::admins()->count(),
            'supervisor'  => User::supervisors()->count(),
            'advisor'     => User::advisors()->count(),
            'inactive'    => User::where('is_active', false)->count(),
        ];

        return view('portal.admin.users.index', compact('users', 'counts'));
    }

    public function create()
    {
        return view('portal.admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'role'       => ['required', 'in:admin,supervisor,advisor'],
            'department' => ['nullable', 'string', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            ...$data,
            'invited_by' => auth()->id(),
            'is_active'  => true,
        ]);

        $token = $user->generateInviteToken();

        // Send invite email
        try {
            \Mail::to($user->email)->send(new \App\Mail\Portal\UserInviteMail($user, $token));
        } catch (\Throwable $e) {
            logger()->error('Invite mail failed', ['error' => $e->getMessage()]);
        }

        ActivityLog::record('user.invited', $user, ['role' => $user->role]);

        return redirect()->route('portal.admin.users.index')
            ->with('success', "Invitation sent to {$user->email}.");
    }

    public function show(User $user)
    {
        $user->load('invitedBy');
        $activity     = ActivityLog::where('user_id', $user->id)->latest('created_at')->take(10)->get();
        $enquiries    = \App\Models\Enquiry::forAdvisor($user->id)->with('currentResponse')->latest()->take(10)->get();
        return view('portal.admin.users.show', compact('user', 'activity', 'enquiries'));
    }

    public function edit(User $user)
    {
        return view('portal.admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email,' . $user->id],
            'role'       => ['required', 'in:admin,supervisor,advisor'],
            'department' => ['nullable', 'string', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'bio'        => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update($data);
        ActivityLog::record('user.updated', $user);

        return redirect()->route('portal.admin.users.show', $user)
            ->with('success', "{$user->name}'s details have been updated.");
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $action = $user->is_active ? 'user.reactivated' : 'user.deactivated';
        ActivityLog::record($action, $user);

        $label = $user->is_active ? 'reactivated' : 'deactivated';
        return back()->with('success', "{$user->name} has been {$label}.");
    }

    public function resendInvite(User $user)
    {
        if ($user->hasAcceptedInvite()) {
            return back()->with('error', 'This user has already accepted their invite.');
        }

        $token = $user->generateInviteToken();

        try {
            \Mail::to($user->email)->send(new \App\Mail\Portal\UserInviteMail($user, $token));
        } catch (\Throwable $e) {
            logger()->error('Resend invite mail failed', ['error' => $e->getMessage()]);
        }

        return back()->with('success', "Invite re-sent to {$user->email}.");
    }
}
