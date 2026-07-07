@extends('portal.layout')
@section('title', 'Events')
@section('page-title', 'Events')
@section('page-subtitle', 'Manage events and outreach programmes shown on the public site')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    {{-- Filter tabs --}}
    <div class="flex gap-1 flex-wrap">
        @foreach([
            [null,        'All',       $counts['all']],
            ['upcoming',  'Upcoming',  $counts['upcoming']],
            ['past',      'Past',      $counts['past']],
            ['draft',     'Drafts',    $counts['draft']],
        ] as [$key, $label, $count])
        <a href="{{ route('portal.admin.events.index', $key ? ['filter' => $key] : []) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors"
           style="{{ request('filter') == $key ? 'background:var(--crimson);color:#fff;border-color:var(--crimson)' : 'background:#fff;color:var(--text-mid);border-color:var(--border)' }}">
            {{ $label }} <span class="opacity-70">({{ $count }})</span>
        </a>
        @endforeach
    </div>
    <a href="{{ route('portal.admin.events.create') }}" class="btn-crimson">+ New Event</a>
</div>

@if($events->isEmpty())
<div class="portal-card text-center py-16">
    <div class="text-4xl mb-4">📅</div>
    <h3 class="text-lg font-semibold mb-2" style="color:var(--text)">No events found</h3>
    <p class="text-sm mb-5" style="color:var(--text-light)">
        {{ request('filter') ? 'No events match this filter.' : 'Create your first event to get started.' }}
    </p>
    @if(!request('filter'))
    <a href="{{ route('portal.admin.events.create') }}" class="btn-crimson">Create First Event</a>
    @endif
</div>
@else
<div class="portal-card overflow-hidden p-0">
    <table class="w-full text-sm">
        <thead class="border-b" style="border-color:var(--border);background:var(--off-white)">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold" style="color:var(--text-light)">Event</th>
                <th class="text-left px-4 py-3 text-xs font-semibold hidden md:table-cell" style="color:var(--text-light)">Date</th>
                <th class="text-left px-4 py-3 text-xs font-semibold hidden lg:table-cell" style="color:var(--text-light)">Location</th>
                <th class="text-center px-4 py-3 text-xs font-semibold hidden sm:table-cell" style="color:var(--text-light)">Reg.</th>
                <th class="text-center px-4 py-3 text-xs font-semibold" style="color:var(--text-light)">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
        @foreach($events as $event)
        <tr class="border-b hover:bg-gray-50" style="border-color:var(--border)">
            <td class="px-5 py-3">
                <div class="font-semibold" style="color:var(--text)">{{ $event->title }}</div>
                <div class="text-xs mt-0.5" style="color:var(--text-light)">{{ Str::limit(strip_tags($event->description), 60) }}</div>
            </td>
            <td class="px-4 py-3 hidden md:table-cell">
                <div class="text-sm font-semibold" style="color:var(--text)">{{ $event->event_date->format('d M Y') }}</div>
                <div class="text-xs" style="color:var(--text-light)">{{ $event->event_date->format('g:i A') }}</div>
            </td>
            <td class="px-4 py-3 text-sm hidden lg:table-cell" style="color:var(--text-mid)">
                {{ $event->location ?? '—' }}
            </td>
            <td class="px-4 py-3 text-center hidden sm:table-cell">
                @if($event->requires_registration)
                <div class="text-sm font-semibold" style="color:var(--text)">{{ $event->registrations_count }}</div>
                @if($event->max_attendees)
                <div class="text-xs" style="color:var(--text-light)">/ {{ $event->max_attendees }}</div>
                @endif
                @else
                <span class="text-xs" style="color:var(--text-light)">Open</span>
                @endif
            </td>
            <td class="px-4 py-3 text-center">
                @php
                    $isPast = $event->event_date->isPast();
                @endphp
                @if($isPast)
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold" style="background:#f1f5f9;color:#64748b">Past</span>
                @elseif($event->is_published)
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold" style="background:#f0fdf4;color:#15803d">Published</span>
                @else
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold" style="background:#fff7ed;color:#c2410c">Draft</span>
                @endif
            </td>
            <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-2">
                    {{-- Quick publish toggle --}}
                    @if(!$event->event_date->isPast())
                    <form method="POST" action="{{ route('portal.admin.events.toggle', $event) }}">
                        @csrf
                        <button type="submit" class="btn-ghost btn-sm">
                            {{ $event->is_published ? 'Unpublish' : 'Publish' }}
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('portal.admin.events.edit', $event) }}" class="btn-ghost btn-sm">Edit</a>
                    <form method="POST" action="{{ route('portal.admin.events.destroy', $event) }}"
                          onsubmit="return confirm('Delete this event and all its registrations?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $events->links() }}</div>
@endif

@endsection
