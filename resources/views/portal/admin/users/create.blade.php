@extends('portal.layout')
@section('title', 'Invite User')
@section('page-title', 'Invite New User')
@section('page-subtitle', 'Create a portal account and send an invite email')

@section('content')
<div class="max-w-xl">
    <div class="portal-card">
        <form method="POST" action="{{ route('portal.admin.users.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input @error('name') border-red-400 @enderror" required>
                    @error('name') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input @error('email') border-red-400 @enderror" required>
                    @error('email') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Portal Role</label>
                <select name="role" class="form-input @error('role') border-red-400 @enderror" required>
                    <option value="">— Select role —</option>
                    <option value="advisor" {{ old('role')==='advisor'?'selected':'' }}>Student Advisor — drafts and submits responses</option>
                    <option value="supervisor" {{ old('role')==='supervisor'?'selected':'' }}>Faculty Supervisor — reviews and approves responses</option>
                    <option value="admin" {{ old('role')==='admin'?'selected':'' }}>Administrator — full access including user management</option>
                </select>
                @error('role') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Department / Faculty <span class="font-normal" style="color:var(--text-light)">(optional)</span></label>
                    <input type="text" name="department" value="{{ old('department') }}" class="form-input" placeholder="e.g. Faculty of Law">
                </div>
                <div>
                    <label class="form-label">Phone Number <span class="font-normal" style="color:var(--text-light)">(optional)</span></label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" class="form-input">
                </div>
            </div>

            <div class="pt-2 border-t" style="border-color:var(--border)">
                <div class="flex items-start gap-3 mb-4 p-3 rounded-xl" style="background:rgba(201,168,76,0.08);border:1px solid rgba(201,168,76,0.2)">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:var(--gold)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs leading-relaxed" style="color:var(--text-mid)">An invite email will be sent immediately. The user will have <strong>7 days</strong> to accept and set their password. You can resend the invite at any time.</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-crimson">Send Invitation</button>
                    <a href="{{ route('portal.admin.users.index') }}" class="btn-ghost">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
