@extends('portal.layout')
@section('title', 'Users')
@section('page-title', 'Portal Users')
@section('page-subtitle', 'Manage staff access and roles')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div></div>
    <a href="{{ route('portal.admin.users.create') }}" class="btn-crimson">+ Invite New User</a>
</div>

{{-- Count chips --}}
<div class="flex flex-wrap gap-3 mb-5">
    @foreach([['All', $counts['total'],''],['Admins',$counts['admin'],'admin'],['Supervisors',$counts['supervisor'],'supervisor'],['Advisors',$counts['advisor'],'advisor'],['Inactive',$counts['inactive'],'inactive']] as [$label,$count,$val])
    <a href="{{ route('portal.admin.users.index', $val ? ['role'=> $val==='inactive' ? null : $val, 'status'=> $val==='inactive' ? 'inactive' : null] : []) }}"
       class="text-xs px-3 py-1.5 rounded-full font-semibold border transition-colors"
       style="background:#fff;border-color:var(--border);color:var(--text-mid)">
        {{ $label }} <span class="font-bold ml-1" style="color:var(--crimson)">{{ $count }}</span>
    </a>
    @endforeach
</div>

{{-- Filters --}}
<div class="portal-card mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="form-label text-xs">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email…" class="form-input" style="width:200px">
        </div>
        <div>
            <label class="form-label text-xs">Role</label>
            <select name="role" class="form-input" style="width:150px">
                <option value="">All roles</option>
                <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Admin</option>
                <option value="supervisor" {{ request('role')==='supervisor'?'selected':'' }}>Supervisor</option>
                <option value="advisor" {{ request('role')==='advisor'?'selected':'' }}>Advisor</option>
            </select>
        </div>
        <div>
            <label class="form-label text-xs">Status</label>
            <select name="status" class="form-input" style="width:130px">
                <option value="">All</option>
                <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-crimson btn-sm">Filter</button>
            <a href="{{ route('portal.admin.users.index') }}" class="btn-ghost btn-sm">Clear</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="portal-card overflow-hidden p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b" style="border-color:var(--border);background:var(--off-white)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold" style="color:var(--text-light)">User</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold" style="color:var(--text-light)">Role</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold hidden md:table-cell" style="color:var(--text-light)">Status</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold hidden lg:table-cell" style="color:var(--text-light)">Invited By</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold hidden lg:table-cell" style="color:var(--text-light)">Last Login</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold" style="color:var(--text-light)">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $u)
            <tr class="border-b hover:bg-gray-50" style="border-color:var(--border)">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <img src="{{ $u->avatar_url }}" class="w-8 h-8 rounded-full flex-shrink-0">
                        <div>
                            <a href="{{ route('portal.admin.users.show', $u) }}" class="text-sm font-semibold hover:underline" style="color:var(--text)">{{ $u->name }}</a>
                            <div class="text-xs" style="color:var(--text-light)">{{ $u->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3.5">
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold"
                        style="{{ $u->role === 'admin' ? 'background:var(--crimson-light);color:var(--crimson)' : ($u->role === 'supervisor' ? 'background:#fefce8;color:#854d0e' : 'background:#f0fdf4;color:#15803d') }}">
                        {{ $u->role_label }}
                    </span>
                </td>
                <td class="px-4 py-3.5 hidden md:table-cell">
                    @if(!$u->hasAcceptedInvite())
                        <span class="text-xs px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold">Invite Pending</span>
                    @elseif($u->is_active)
                        <span class="text-xs px-2.5 py-1 rounded-full bg-green-50 text-green-700 font-semibold">Active</span>
                    @else
                        <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 font-semibold">Inactive</span>
                    @endif
                </td>
                <td class="px-4 py-3.5 text-xs hidden lg:table-cell" style="color:var(--text-light)">{{ $u->invitedBy?->name ?? '—' }}</td>
                <td class="px-4 py-3.5 text-xs hidden lg:table-cell" style="color:var(--text-light)">{{ $u->last_login_at ? $u->last_login_at->diffForHumans() : 'Never' }}</td>
                <td class="px-4 py-3.5">
                    <div class="flex items-center justify-end gap-2">
                        @if(!$u->hasAcceptedInvite())
                        <form method="POST" action="{{ route('portal.admin.users.resend-invite', $u) }}">@csrf
                            <button type="submit" class="btn-ghost btn-sm text-xs">Resend Invite</button>
                        </form>
                        @endif
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('portal.admin.users.toggle', $u) }}">@csrf
                            <button type="submit" class="{{ $u->is_active ? 'btn-danger' : 'btn-ghost' }} btn-sm text-xs">
                                {{ $u->is_active ? 'Deactivate' : 'Reactivate' }}
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-12 text-center text-sm" style="color:var(--text-light)">No users found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-5 py-4 border-t" style="border-color:var(--border)">{{ $users->links() }}</div>
    @endif
</div>
@endsection
