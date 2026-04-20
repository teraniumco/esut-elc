@extends('layouts.app')
@section('title', $event->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-gray-400 mb-6">
        <a href="{{ route('events.index') }}" class="hover:text-crimson">Events</a>
        <span>›</span>
        <span class="text-gray-600">{{ Str::limit($event->title, 60) }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- Main --}}
        <div class="lg:col-span-2">
            <div class="flex items-center gap-3 mb-4">
                @if($event->event_date->isPast())
                    <span class="bg-gray-100 text-gray-500 text-xs font-semibold px-3 py-1 rounded-full">Past Event</span>
                @else
                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">Upcoming</span>
                @endif
            </div>

            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-crimson mb-5">{{ $event->title }}</h1>

            <div class="flex flex-wrap gap-5 mb-8 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $event->event_date->format('l, d F Y') }}
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $event->event_date->format('g:i A') }}
                    @if($event->event_end_date) – {{ $event->event_end_date->format('g:i A') }} @endif
                </div>
                @if($event->location)
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    {{ $event->location }}
                </div>
                @endif
            </div>

            <div class="prose-legal text-gray-700">
                {!! $event->description !!}
            </div>
        </div>

        {{-- Sidebar: Registration --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

                @if($event->event_date->isPast())
                    <div class="p-6 text-center text-gray-500">
                        <p class="text-3xl mb-2">📌</p>
                        <p class="font-medium">This event has passed.</p>
                    </div>

                @elseif(!$event->requires_registration)
                    <div class="bg-crimson p-5">
                        <p class="text-white font-semibold">Free Entry</p>
                        <p class="text-gray-300 text-sm mt-1">No registration required. Just show up!</p>
                    </div>

                @elseif($event->isFull())
                    <div class="bg-red-50 border-b border-red-100 p-5">
                        <p class="text-red-700 font-semibold">Event Full</p>
                        <p class="text-red-600 text-sm mt-1">All spots have been taken for this event.</p>
                    </div>

                @else
                    <div class="bg-crimson p-5 border-b border-crimson/20">
                        <p class="text-white font-semibold">Register for this Event</p>
                        @if($event->spotsLeft() !== null)
                        <p class="text-gold text-sm mt-0.5">{{ $event->spotsLeft() }} spot{{ $event->spotsLeft() !== 1 ? 's' : '' }} remaining</p>
                        @endif
                    </div>

                    @if(session('success'))
                    <div class="bg-green-50 p-5 text-green-800 text-sm font-medium flex gap-2">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('success') }}
                    </div>
                    @endif

                    <form action="{{ route('events.register', $event) }}" method="POST" class="p-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-crimson/30 @error('name') border-red-400 @enderror">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-crimson/30 @error('email') border-red-400 @enderror">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Phone <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-crimson/30">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Affiliation <span class="text-gray-400 font-normal">(optional)</span></label>
                            <select name="affiliation" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-crimson/30">
                                <option value="">— Select —</option>
                                <option value="ESUT Student">ESUT Student</option>
                                <option value="ESUT Staff">ESUT Staff</option>
                                <option value="General Public">General Public</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-crimson text-white font-semibold py-3 rounded-xl hover:bg-gold hover:text-crimson transition-colors text-sm">
                            Register Now
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
