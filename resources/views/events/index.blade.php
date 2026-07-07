@extends('layouts.app')
@section('title', 'Events & Outreach')

@section('content')
<div class="bg-crimson py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-3">Events & Outreach</h1>
        <p class="text-gray-300">Legal awareness seminars, free consultation days, and community outreach programs.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    {{-- Upcoming --}}
    @if($upcoming->isNotEmpty())
    <section class="mb-16">
        <h2 class="font-serif text-2xl font-bold text-navy mb-6">Upcoming Events</h2>
        <div class="space-y-5">
            @foreach($upcoming as $event)
            <a href="{{ route('events.show', $event->slug) }}"
               class="group flex flex-col sm:flex-row bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-md transition-all">
                {{-- Date block --}}
                <div class="bg-crimson sm:w-32 flex-shrink-0 flex sm:flex-col items-center justify-center p-5 gap-3 sm:gap-0">
                    <p class="font-serif text-3xl font-bold text-gold">{{ $event->event_date->format('d') }}</p>
                    <p class="text-white/60 text-sm uppercase tracking-wide">{{ $event->event_date->format('M Y') }}</p>
                </div>
                {{-- Content --}}
                <div class="flex-1 p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-navy group-hover:text-gold transition-colors text-lg">{{ $event->title }}</h3>
                        <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-gray-400">
                            <span>🕐 {{ $event->event_date->format('g:i A') }}</span>
                            @if($event->location) <span>📍 {{ $event->location }}</span> @endif
                            @if($event->requires_registration)
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">Registration Open</span>
                            @endif
                        </div>
                        <p class="text-gray-500 text-sm mt-2 line-clamp-2">{{ Str::limit(strip_tags($event->description), 140) }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center gap-1 text-sm text-navy font-semibold group-hover:text-gold transition-colors">
                            Details
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @else
    <div class="text-center py-16 bg-gray-50 rounded-2xl mb-14">
        <span class="text-4xl block mb-4">📅</span>
        <p class="text-gray-500 font-medium">No upcoming events at this time.</p>
        <p class="text-gray-400 text-sm mt-1">Check back soon or <a href="{{ route('contact.index') }}" class="text-navy hover:underline">contact us</a> to suggest an event.</p>
    </div>
    @endif

    {{-- Past events --}}
    @if($past->isNotEmpty())
    <section>
        <h2 class="font-serif text-xl font-bold text-navy mb-5 text-gray-600">Past Events</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($past as $event)
            <div class="bg-gray-50 border border-gray-100 rounded-xl p-5 opacity-75">
                <p class="text-xs text-gray-400 mb-2">{{ $event->event_date->format('d M Y') }}</p>
                <h3 class="font-medium text-gray-700 text-sm">{{ $event->title }}</h3>
                @if($event->location) <p class="text-xs text-gray-400 mt-1">📍 {{ $event->location }}</p> @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
