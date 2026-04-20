@extends('portal.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name)

@section('content')
@php $user = auth()->user(); @endphp

{{-- ═══ STAT CARDS ═══════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @if($user->isAdmin())
        @foreach([
            ['Unassigned', $stats['unassigned'], 'bg-red-50 text-red-700', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
            ['Awaiting Review', $stats['awaiting_approval'], 'bg-orange-50 text-orange-700', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['In Progress', $stats['in_progress'], 'bg-indigo-50 text-indigo-700', 'M13 10V3L4 14h7v7l9-11h-7z'],
            ['Total Enquiries', $stats['total'], 'bg-gray-50 text-gray-700', 'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2'],
        ] as [$label,$val,$color,$icon])
        <div class="portal-card">
            <div class="flex items-start justify-between mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider" style="color:var(--text-light)">{{ $label }}</span>
                <span class="w-7 h-7 rounded-lg flex items-center justify-center {{ $color }}" style="background-opacity:0.1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/></svg>
                </span>
            </div>
            <div class="text-3xl font-bold" style="font-family:'DM Serif Display',serif;color:var(--text)">{{ $val }}</div>
        </div>
        @endforeach

    @elseif($user->isSupervisor())
        @foreach([
            ['Awaiting My Review', $stats['awaiting_approval'], 'text-orange-700'],
            ['Pending Responses', $stats['pending_responses'], 'text-amber-700'],
            ['Total Responded', $stats['responded'], 'text-green-700'],
            ['Total Cases', $stats['total'], 'text-gray-700'],
        ] as [$label,$val,$color])
        <div class="portal-card">
            <span class="text-xs font-semibold uppercase tracking-wider" style="color:var(--text-light)">{{ $label }}</span>
            <div class="text-3xl font-bold mt-2 {{ $color }}" style="font-family:'DM Serif Display',serif">{{ $val }}</div>
        </div>
        @endforeach

    @else {{-- Advisor --}}
        @foreach([
            ['Assigned to Me', $stats['assigned'], 'text-crimson'],
            ['In Progress', $stats['in_progress'], 'text-indigo-700'],
            ['Awaiting Approval', $stats['awaiting_approval'], 'text-orange-700'],
            ['Completed', $stats['responded'], 'text-green-700'],
        ] as [$label,$val,$color])
        <div class="portal-card">
            <span class="text-xs font-semibold uppercase tracking-wider" style="color:var(--text-light)">{{ $label }}</span>
            <div class="text-3xl font-bold mt-2 {{ $color }}" style="font-family:'DM Serif Display',serif;color:{{ $val > 0 && str_contains($color,'crimson') ? 'var(--crimson)' : '' }}">{{ $val }}</div>
        </div>
        @endforeach
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ═══ LEFT: Main table ═══════════════════════════════════════════ --}}
    <div class="lg:col-span-2 space-y-6">

        @if($user->isAdmin() && isset($pendingAssignment) && $pendingAssignment->isNotEmpty())
        <div class="portal-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-sm" style="color:var(--text)">⚠ Unassigned Enquiries</h3>
                <a href="{{ route('portal.enquiries.index', ['status'=>'received']) }}" class="text-xs" style="color:var(--crimson)">View all →</a>
            </div>
            <div class="space-y-2">
                @foreach($pendingAssignment as $enq)
                <a href="{{ route('portal.enquiries.show', $enq) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <div>
                        <span class="text-xs font-mono font-bold" style="color:var(--crimson)">{{ $enq->reference_code }}</span>
                        <span class="text-xs ml-2 px-2 py-0.5 rounded-full {{ $enq->urgency === 'urgent' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }} font-medium">{{ ucfirst($enq->urgency) }}</span>
                        <div class="text-xs mt-0.5" style="color:var(--text-mid)">{{ $enq->category_label }}</div>
                    </div>
                    <div class="text-xs text-right" style="color:var(--text-light)">{{ $enq->created_at->diffForHumans() }}</div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($user->isSupervisor() || ($user->isAdmin() && isset($awaitingReview) && $awaitingReview->isNotEmpty()))
        <div class="portal-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-sm" style="color:var(--text)">📋 Review Queue</h3>
                <a href="{{ route('portal.enquiries.index', ['status'=>'awaiting_approval']) }}" class="text-xs" style="color:var(--crimson)">View all →</a>
            </div>
            @php $queue = $reviewQueue ?? $awaitingReview ?? collect(); @endphp
            @forelse($queue as $enq)
            <a href="{{ route('portal.enquiries.show', $enq) }}" class="flex items-start justify-between p-3 rounded-xl hover:bg-gray-50 transition-colors border-b last:border-0" style="border-color:var(--border)">
                <div>
                    <span class="text-xs font-mono font-bold" style="color:var(--crimson)">{{ $enq->reference_code }}</span>
                    <div class="text-xs mt-0.5" style="color:var(--text-mid)">{{ $enq->category_label }}</div>
                    @if($enq->activeAssignment)
                    <div class="text-xs mt-0.5" style="color:var(--text-light)">Advisor: {{ $enq->activeAssignment->advisor->name }}</div>
                    @endif
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-orange-50 text-orange-700 whitespace-nowrap">Needs Review</span>
            </a>
            @empty
            <p class="text-sm text-center py-4" style="color:var(--text-light)">No responses pending review.</p>
            @endforelse
        </div>
        @endif

        @if($user->isAdvisor() && isset($myEnquiries))
        <div class="portal-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-sm" style="color:var(--text)">My Enquiries</h3>
                <a href="{{ route('portal.enquiries.index') }}" class="text-xs" style="color:var(--crimson)">View all →</a>
            </div>
            @forelse($myEnquiries->take(6) as $enq)
            <a href="{{ route('portal.enquiries.show', $enq) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-colors border-b last:border-0" style="border-color:var(--border)">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-mono font-bold" style="color:var(--crimson)">{{ $enq->reference_code }}</span>
                        @if($enq->urgency === 'urgent') <span class="text-xs px-1.5 py-0.5 rounded-full bg-red-100 text-red-600 font-semibold">Urgent</span> @endif
                    </div>
                    <div class="text-xs mt-0.5" style="color:var(--text-mid)">{{ $enq->category_label }}</div>
                </div>
                <span class="status-badge status-{{ $enq->status }}">{{ $enq->status_label }}</span>
            </a>
            @empty
            <p class="text-sm text-center py-6" style="color:var(--text-light)">No enquiries assigned yet.</p>
            @endforelse
        </div>
        @endif

        @if($user->isAdmin() && isset($recentEnquiries))
        <div class="portal-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-sm" style="color:var(--text)">Recent Enquiries</h3>
                <a href="{{ route('portal.enquiries.index') }}" class="text-xs" style="color:var(--crimson)">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b" style="border-color:var(--border)">
                        <th class="text-left pb-2 text-xs font-semibold" style="color:var(--text-light)">Ref</th>
                        <th class="text-left pb-2 text-xs font-semibold" style="color:var(--text-light)">Category</th>
                        <th class="text-left pb-2 text-xs font-semibold" style="color:var(--text-light)">Advisor</th>
                        <th class="text-left pb-2 text-xs font-semibold" style="color:var(--text-light)">Status</th>
                    </tr></thead>
                    <tbody>
                    @foreach($recentEnquiries as $enq)
                    <tr class="border-b hover:bg-gray-50" style="border-color:var(--border)">
                        <td class="py-2.5 pr-3"><a href="{{ route('portal.enquiries.show', $enq) }}" class="font-mono text-xs font-bold" style="color:var(--crimson)">{{ $enq->reference_code }}</a></td>
                        <td class="py-2.5 pr-3 text-xs" style="color:var(--text-mid)">{{ $enq->category_label }}</td>
                        <td class="py-2.5 pr-3 text-xs" style="color:var(--text-mid)">{{ $enq->activeAssignment?->advisor->name ?? '—' }}</td>
                        <td class="py-2.5"><span class="status-badge status-{{ $enq->status }}">{{ $enq->status_label }}</span></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- ═══ RIGHT: Activity feed ══════════════════════════════════════ --}}
    <div class="space-y-6">
        @if(isset($recentActivity) && $recentActivity->isNotEmpty())
        <div class="portal-card">
            <h3 class="font-semibold text-sm mb-4" style="color:var(--text)">Recent Activity</h3>
            <div class="space-y-3">
                @foreach($recentActivity as $log)
                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center mt-0.5" style="background:rgba(113,21,0,0.08)">
                        <div class="w-1.5 h-1.5 rounded-full" style="background:var(--crimson)"></div>
                    </div>
                    <div>
                        <p class="text-xs font-medium" style="color:var(--text)">{{ $log->action_label }}</p>
                        @if($log->user)<p class="text-xs" style="color:var(--text-light)">by {{ $log->user->name }}</p>@endif
                        <p class="text-xs" style="color:var(--text-light)">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($user->isAdmin())
        <div class="portal-card">
            <h3 class="font-semibold text-sm mb-4" style="color:var(--text)">Quick Stats</h3>
            <div class="space-y-3">
                @foreach([['Urgent Open Cases', $stats['urgent'] ?? 0, 'text-red-600'],['Active Advisors', $stats['total_advisors'] ?? 0, ''],['Supervisors', $stats['total_supervisors'] ?? 0, ''],['Responded (all time)', $stats['responded'] ?? 0, 'text-green-600']] as [$l,$v,$c])
                <div class="flex items-center justify-between text-sm">
                    <span style="color:var(--text-mid)">{{ $l }}</span>
                    <span class="font-bold {{ $c }}" style="{{ !$c ? 'color:var(--text)' : '' }}">{{ $v }}</span>
                </div>
                @endforeach
            </div>
            <div class="mt-4 pt-4 border-t" style="border-color:var(--border)">
                <a href="{{ route('portal.admin.reports') }}" class="btn-ghost btn-sm w-full justify-center text-xs">View Full Report →</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
