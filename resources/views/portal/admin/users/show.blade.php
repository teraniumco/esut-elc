@extends('portal.layout')
@section('title', $user->name)
@section('page-title', $user->name)
@section('page-subtitle', $user->role_label . ($user->department ? ' · ' . $user->department : ''))

@section('content')

<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('portal.admin.users.index') }}" class="btn-ghost btn-sm">← Users</a>
    <a href="{{ route('portal.admin.users.edit', $user) }}" class="btn-crimson btn-sm">Edit Details</a>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ═══ LEFT: Profile card ═══════════════════════════════════════════ --}}
    <div class="space-y-5">

        {{-- Identity --}}
        <div class="portal-card text-center">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                 class="w-20 h-20 rounded-full mx-auto mb-4">

            <h2 class="text-lg font-semibold mb-0.5" style="color:var(--text)">{{ $user->name }}</h2>
            <p class="text-sm mb-3" style="color:var(--text-light)">{{ $user->email }}</p>

            {{-- Role badge --}}
            <span class="inline-block text-xs font-bold px-3 py-1.5 rounded-full mb-4"
                  style="{{ $user->role === 'admin' ? 'background:var(--crimson-light);color:var(--crimson)' : ($user->role === 'supervisor' ? 'background:#fefce8;color:#854d0e' : 'background:#f0fdf4;color:#15803d') }}">
                {{ $user->role_label }}
            </span>

            {{-- Status --}}
            <div class="flex justify-center mb-5">
                @if(!$user->hasAcceptedInvite())
                    <span class="text-xs px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold">Invite Pending</span>
                @elseif($user->is_active)
                    <span class="text-xs px-3 py-1 rounded-full bg-green-50 text-green-700 font-semibold">● Active</span>
                @else
                    <span class="text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-500 font-semibold">● Inactive</span>
                @endif
            </div>

            {{-- Action buttons --}}
            <div class="flex flex-col gap-2">
                @if(!$user->hasAcceptedInvite())
                <form method="POST" action="{{ route('portal.admin.users.resend-invite', $user) }}">
                    @csrf
                    <button type="submit" class="btn-ghost btn-sm w-full justify-center">Resend Invite</button>
                </form>
                @endif
                @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('portal.admin.users.toggle', $user) }}">
                    @csrf
                    <button type="submit"
                            class="{{ $user->is_active ? 'btn-danger' : 'btn-ghost' }} btn-sm w-full justify-center">
                        {{ $user->is_active ? 'Deactivate Account' : 'Reactivate Account' }}
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Details --}}
        <div class="portal-card">
            <h3 class="text-sm font-semibold mb-4" style="color:var(--text)">Account Details</h3>
            <div class="space-y-3 text-sm">
                @foreach([
                    ['Department',   $user->department  ?? '—'],
                    ['Phone',        $user->phone       ?? '—'],
                    ['Invited by',   $user->invitedBy?->name ?? '—'],
                    ['Invite accepted', $user->invite_accepted_at ? $user->invite_accepted_at->format('d M Y') : 'Not yet'],
                    ['Last login',   $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never'],
                    ['Member since', $user->created_at->format('d M Y')],
                ] as [$label, $val])
                <div class="flex items-start justify-between gap-4">
                    <span class="text-xs font-semibold flex-shrink-0" style="color:var(--text-light)">{{ $label }}</span>
                    <span class="text-xs text-right" style="color:var(--text)">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Activity log --}}
        <div class="portal-card">
            <h3 class="text-sm font-semibold mb-4" style="color:var(--text)">Recent Activity</h3>
            @forelse($activity as $log)
            <div class="flex gap-2.5 mb-3">
                <div class="w-1.5 h-1.5 rounded-full mt-1.5 flex-shrink-0" style="background:var(--crimson)"></div>
                <div>
                    <p class="text-xs font-medium" style="color:var(--text)">{{ $log->action_label }}</p>
                    <p class="text-xs" style="color:var(--text-light)">{{ $log->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-xs" style="color:var(--text-light)">No activity recorded yet.</p>
            @endforelse
        </div>

    </div>

    {{-- ═══ RIGHT: Enquiries ══════════════════════════════════════════════ --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Stats row --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
                $total      = $enquiries->count();
                $inProgress = $enquiries->whereIn('status', ['under_review','in_progress'])->count();
                $pending    = $enquiries->where('status', 'awaiting_approval')->count();
                $done       = $enquiries->where('status', 'responded')->count();
            @endphp
            @foreach([
                ['Assigned',        $total,      ''],
                ['In Progress',     $inProgress, 'text-indigo-600'],
                ['Awaiting Review', $pending,    'text-orange-600'],
                ['Responded',       $done,       'text-green-600'],
            ] as [$label, $val, $color])
            <div class="portal-card text-center">
                <div class="text-2xl font-bold mb-1 {{ $color }}"
                     style="{{ !$color ? 'color:var(--crimson)' : '' }};font-family:'DM Serif Display',serif">{{ $val }}</div>
                <div class="text-xs" style="color:var(--text-light)">{{ $label }}</div>
            </div>
            @endforeach
        </div>

        {{-- Enquiries table --}}
        <div class="portal-card overflow-hidden p-0">
            <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color:var(--border)">
                <h3 class="text-sm font-semibold" style="color:var(--text)">Assigned Enquiries</h3>
                <a href="{{ route('portal.enquiries.index', ['advisor' => $user->id]) }}"
                   class="text-xs" style="color:var(--crimson)">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b" style="border-color:var(--border);background:var(--off-white)">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold" style="color:var(--text-light)">Reference</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold hidden md:table-cell" style="color:var(--text-light)">Category</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold" style="color:var(--text-light)">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold hidden sm:table-cell" style="color:var(--text-light)">Response</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold hidden lg:table-cell" style="color:var(--text-light)">Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($enquiries as $enq)
                    <tr class="border-b hover:bg-gray-50 cursor-pointer"
                        style="border-color:var(--border)"
                        onclick="window.location='{{ route('portal.enquiries.show', $enq) }}'">
                        <td class="px-5 py-3">
                            <a href="{{ route('portal.enquiries.show', $enq) }}"
                               class="font-mono text-xs font-bold hover:underline"
                               style="color:var(--crimson)">{{ $enq->reference_code }}</a>
                        </td>
                        <td class="px-4 py-3 text-xs hidden md:table-cell" style="color:var(--text-mid)">{{ $enq->category_label }}</td>
                        <td class="px-4 py-3">
                            <span class="status-badge status-{{ $enq->status }}">{{ $enq->status_label }}</span>
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            @if($enq->currentResponse)
                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                      style="background:{{ $enq->currentResponse->review_status === 'approved' ? '#f0fdf4' : ($enq->currentResponse->review_status === 'rejected' ? '#fef2f2' : '#fff7ed') }};color:{{ $enq->currentResponse->review_status === 'approved' ? '#15803d' : ($enq->currentResponse->review_status === 'rejected' ? '#dc2626' : '#c2410c') }}">
                                    {{ $enq->currentResponse->status_label }}
                                </span>
                            @else
                                <span class="text-xs" style="color:var(--text-light)">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs hidden lg:table-cell" style="color:var(--text-light)">
                            {{ $enq->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-sm" style="color:var(--text-light)">
                            No enquiries assigned to this user yet.
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
