@extends('portal.layout')
@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'Overview of clinic performance')

@section('content')

{{-- Period selector --}}
<div class="flex items-center gap-3 mb-6">
    @foreach([['7','Last 7 days'],['30','Last 30 days'],['90','Last 90 days'],['365','Last 12 months']] as [$val,$label])
    <a href="{{ route('portal.admin.reports', ['period'=>$val]) }}"
       class="text-xs px-3 py-1.5 rounded-full font-semibold border transition-colors {{ $period == $val ? 'text-white' : '' }}"
       style="{{ $period == $val ? 'background:var(--crimson);border-color:var(--crimson)' : 'background:#fff;border-color:var(--border);color:var(--text-mid)' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Summary cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['All-time Enquiries', $totals['all_time_enquiries'], ''],
        ['Period Enquiries', $totals['period_enquiries'], 'text-blue-600'],
        ['Period Responded', $totals['period_responded'], 'text-green-600'],
        ['Avg. Response Time', $totals['avg_response_hours'] ?? '—', 'text-amber-600'],
    ] as [$label,$val,$color])
    <div class="portal-card">
        <div class="text-xs font-semibold uppercase tracking-wider mb-2" style="color:var(--text-light)">{{ $label }}</div>
        <div class="text-3xl font-bold {{ $color }}" style="{{ !$color ? 'color:var(--text)' : '' }};font-family:'DM Serif Display',serif">{{ $val }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- By category --}}
    <div class="portal-card">
        <h3 class="font-semibold text-sm mb-4" style="color:var(--text)">Enquiries by Category <span class="font-normal text-xs ml-1" style="color:var(--text-light)">(last {{ $period }} days)</span></h3>
        @forelse($byCategory as $cat)
        @php $max = $byCategory->max('count'); $pct = $max > 0 ? round($cat['count'] / $max * 100) : 0; @endphp
        <div class="mb-3">
            <div class="flex items-center justify-between text-xs mb-1">
                <span style="color:var(--text-mid)">{{ $cat['label'] }}</span>
                <span class="font-bold" style="color:var(--text)">{{ $cat['count'] }}</span>
            </div>
            <div class="h-1.5 rounded-full overflow-hidden" style="background:var(--border)">
                <div class="h-full rounded-full" style="width:{{ $pct }}%;background:var(--crimson)"></div>
            </div>
        </div>
        @empty
        <p class="text-xs" style="color:var(--text-light)">No data for this period.</p>
        @endforelse
    </div>

    {{-- By status --}}
    <div class="portal-card">
        <h3 class="font-semibold text-sm mb-4" style="color:var(--text)">All-time Status Breakdown</h3>
        <div class="space-y-2">
            @foreach($byStatus as $s)
            <div class="flex items-center justify-between py-2 border-b last:border-0" style="border-color:var(--border)">
                <div class="flex items-center gap-2">
                    <span class="status-badge status-{{ $s['status'] }}">{{ $s['label'] }}</span>
                </div>
                <span class="text-sm font-bold" style="color:var(--text)">{{ $s['count'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Advisor performance --}}
<div class="portal-card">
    <h3 class="font-semibold text-sm mb-4" style="color:var(--text)">Advisor Performance</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b" style="border-color:var(--border)">
                <tr>
                    <th class="text-left pb-2 text-xs font-semibold" style="color:var(--text-light)">Advisor</th>
                    <th class="text-center pb-2 text-xs font-semibold" style="color:var(--text-light)">Assigned</th>
                    <th class="text-center pb-2 text-xs font-semibold" style="color:var(--text-light)">Responses</th>
                    <th class="text-center pb-2 text-xs font-semibold" style="color:var(--text-light)">Approved</th>
                    <th class="text-center pb-2 text-xs font-semibold" style="color:var(--text-light)">Approval Rate</th>
                </tr>
            </thead>
            <tbody>
            @forelse($advisorStats as $adv)
            <tr class="border-b hover:bg-gray-50" style="border-color:var(--border)">
                <td class="py-3">
                    <div class="flex items-center gap-2">
                        <img src="{{ $adv->avatar_url }}" class="w-7 h-7 rounded-full">
                        <span class="font-medium text-sm" style="color:var(--text)">{{ $adv->name }}</span>
                    </div>
                </td>
                <td class="py-3 text-center font-bold" style="color:var(--text)">{{ $adv->total_assigned }}</td>
                <td class="py-3 text-center" style="color:var(--text-mid)">{{ $adv->total_responses }}</td>
                <td class="py-3 text-center text-green-600 font-semibold">{{ $adv->approved_responses }}</td>
                <td class="py-3 text-center">
                    @php $rate = $adv->total_responses > 0 ? round($adv->approved_responses / $adv->total_responses * 100) : 0; @endphp
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $rate >= 80 ? 'bg-green-50 text-green-700' : ($rate >= 50 ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700') }}">
                        {{ $rate }}%
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-8 text-center text-sm" style="color:var(--text-light)">No advisor data available.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
