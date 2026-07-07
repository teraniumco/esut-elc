@extends('portal.layout')
@section('title', 'Edit ' . $user->name)
@section('page-title', 'Edit User')
@section('page-subtitle', $user->name . ' · ' . $user->role_label)

@section('content')

<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('portal.admin.users.show', $user) }}" class="btn-ghost btn-sm">← Back to profile</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── Form ── --}}
    <div class="lg:col-span-2">
        <div class="portal-card">
            <h3 class="text-sm font-semibold mb-6" style="color:var(--text)">Account Details</h3>

            <form method="POST" action="{{ route('portal.admin.users.update', $user) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="form-input @error('name') border-red-400 @enderror" required>
                        @error('name') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="form-input @error('email') border-red-400 @enderror" required>
                        @error('email') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Portal Role</label>
                    <select name="role" class="form-input @error('role') border-red-400 @enderror" required
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <option value="advisor"    {{ old('role', $user->role) === 'advisor'    ? 'selected' : '' }}>Student Advisor — drafts and submits responses</option>
                        <option value="supervisor" {{ old('role', $user->role) === 'supervisor' ? 'selected' : '' }}>Faculty Supervisor — reviews and approves responses</option>
                        <option value="admin"      {{ old('role', $user->role) === 'admin'      ? 'selected' : '' }}>Administrator — full access including user management</option>
                    </select>
                    @if($user->id === auth()->id())
                        {{-- Re-submit the role value since disabled fields aren't submitted --}}
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <p class="form-hint">You cannot change your own role.</p>
                    @endif
                    @error('role') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Department / Faculty <span class="font-normal" style="color:var(--text-light)">(optional)</span></label>
                        <input type="text" name="department" value="{{ old('department', $user->department) }}"
                               class="form-input" placeholder="e.g. Faculty of Law">
                    </div>
                    <div>
                        <label class="form-label">Phone Number <span class="font-normal" style="color:var(--text-light)">(optional)</span></label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Bio <span class="font-normal" style="color:var(--text-light)">(optional — shown on about page)</span></label>
                    <textarea name="bio" rows="4" class="form-input"
                              placeholder="Brief professional bio…">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2 border-t flex gap-3" style="border-color:var(--border)">
                    <button type="submit" class="btn-crimson">Save Changes</button>
                    <a href="{{ route('portal.admin.users.show', $user) }}" class="btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Sidebar: current summary ── --}}
    <div class="space-y-5">
        <div class="portal-card text-center">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                 class="w-16 h-16 rounded-full mx-auto mb-3">
            <div class="text-sm font-semibold mb-0.5" style="color:var(--text)">{{ $user->name }}</div>
            <div class="text-xs mb-3" style="color:var(--text-light)">{{ $user->email }}</div>
            <span class="inline-block text-xs font-bold px-3 py-1 rounded-full"
                  style="{{ $user->role === 'admin' ? 'background:var(--crimson-light);color:var(--crimson)' : ($user->role === 'supervisor' ? 'background:#fefce8;color:#854d0e' : 'background:#f0fdf4;color:#15803d') }}">
                {{ $user->role_label }}
            </span>
        </div>

        <div class="portal-card">
            <h4 class="text-xs font-bold uppercase tracking-wider mb-3" style="color:var(--text-light)">Account Status</h4>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between">
                    <span style="color:var(--text-mid)">Status</span>
                    @if(!$user->hasAcceptedInvite())
                        <span class="text-blue-600 font-semibold">Invite Pending</span>
                    @elseif($user->is_active)
                        <span class="text-green-600 font-semibold">Active</span>
                    @else
                        <span class="text-gray-500 font-semibold">Inactive</span>
                    @endif
                </div>
                <div class="flex justify-between">
                    <span style="color:var(--text-mid)">Member since</span>
                    <span style="color:var(--text)">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span style="color:var(--text-mid)">Last login</span>
                    <span style="color:var(--text)">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
                </div>
            </div>
        </div>

        <div class="portal-card" style="background:#fff8e1;border-color:#fde68a">
            <p class="text-xs leading-relaxed" style="color:#78350f">
                <strong>Note:</strong> Changing a user's role takes effect immediately. The user will have the new permissions on their next page load without needing to log out.
            </p>
        </div>
    </div>

</div>
@endsection
