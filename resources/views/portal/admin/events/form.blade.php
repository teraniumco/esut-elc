@extends('portal.layout')
@section('title', $event ? 'Edit Event' : 'New Event')
@section('page-title', $event ? 'Edit Event' : 'New Event')
@section('page-subtitle', $event ? $event->title : 'Add a new event to the public events page')

@section('content')

<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('portal.admin.events.index') }}" class="btn-ghost btn-sm">← Events</a>
    @if($event)
    <a href="{{ route('events.show', $event->slug) }}" target="_blank" class="btn-ghost btn-sm">View Public ↗</a>
    @endif
</div>

<form method="POST"
      action="{{ $event ? route('portal.admin.events.update', $event) : route('portal.admin.events.store') }}"
      id="event-form">
    @csrf
    @if($event) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Main content ── --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="portal-card space-y-5">
                <div>
                    <label class="form-label">Event Title</label>
                    <input type="text" name="title" class="form-input @error('title') border-red-400 @enderror" required
                           value="{{ old('title', $event->title ?? '') }}"
                           placeholder="e.g. Know Your Rights: A Free Legal Awareness Seminar">
                    @error('title') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Location <span class="font-normal" style="color:var(--text-light)">(optional)</span></label>
                    <input type="text" name="location" class="form-input"
                           value="{{ old('location', $event->location ?? '') }}"
                           placeholder="e.g. ESUT Faculty of Law Moot Court, Agbani">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Start Date & Time</label>
                        <input type="datetime-local" name="event_date" class="form-input @error('event_date') border-red-400 @enderror" required
                               value="{{ old('event_date', $event ? $event->event_date->format('Y-m-d\TH:i') : '') }}">
                        @error('event_date') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">End Date & Time <span class="font-normal" style="color:var(--text-light)">(optional)</span></label>
                        <input type="datetime-local" name="event_end_date" class="form-input"
                               value="{{ old('event_end_date', $event && $event->event_end_date ? $event->event_end_date->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>

                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input @error('description') border-red-400 @enderror"
                              rows="6" required
                              placeholder="Describe the event — agenda, speakers, what attendees will learn...">{{ old('description', $event ? strip_tags($event->description, '<p><strong><em><ul><ol><li><h2><h3><a><br>') : '') }}</textarea>
                    <p class="form-hint">HTML is supported for rich formatting. Use &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;/&lt;li&gt;, &lt;h2&gt;, &lt;h3&gt;.</p>
                    @error('description') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ── Sidebar ── --}}
        <div class="space-y-5">

            <div class="portal-card">
                <h3 class="text-sm font-semibold mb-4" style="color:var(--text)">Publish</h3>
                <label class="flex items-center gap-2 cursor-pointer mb-4">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1"
                           class="w-4 h-4 rounded accent-crimson"
                           {{ old('is_published', $event->is_published ?? false) ? 'checked' : '' }}>
                    <div>
                        <span class="text-sm font-medium" style="color:var(--text)">Published</span>
                        <p class="text-xs" style="color:var(--text-light)">Visible to the public when checked</p>
                    </div>
                </label>
                <div class="flex gap-2">
                    <button type="submit" class="btn-crimson flex-1 justify-center">
                        {{ $event ? 'Save Changes' : 'Create Event' }}
                    </button>
                    <a href="{{ route('portal.admin.events.index') }}" class="btn-ghost">Cancel</a>
                </div>
            </div>

            <div class="portal-card">
                <h3 class="text-sm font-semibold mb-4" style="color:var(--text)">Registration</h3>
                <div class="space-y-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="requires_registration" value="0">
                        <input type="checkbox" name="requires_registration" value="1" id="reg-toggle"
                               class="w-4 h-4 rounded accent-crimson"
                               {{ old('requires_registration', $event->requires_registration ?? true) ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium" style="color:var(--text)">Requires registration</span>
                            <p class="text-xs" style="color:var(--text-light)">Show a registration form on the event page</p>
                        </div>
                    </label>
                    <div id="max-attendees-wrap">
                        <label class="form-label text-xs">Max attendees <span class="font-normal" style="color:var(--text-light)">(blank = unlimited)</span></label>
                        <input type="number" name="max_attendees" class="form-input" min="1"
                               value="{{ old('max_attendees', $event->max_attendees ?? '') }}"
                               placeholder="e.g. 200">
                    </div>
                </div>
            </div>

            @if($event && $event->requires_registration && $event->registrations_count > 0)
            <div class="portal-card">
                <h3 class="text-sm font-semibold mb-3" style="color:var(--text)">Registrations</h3>
                <div class="text-3xl font-bold mb-1" style="font-family:'DM Serif Display',serif;color:var(--crimson)">
                    {{ $event->registrations_count }}
                    @if($event->max_attendees)<span class="text-lg text-gray-400">/ {{ $event->max_attendees }}</span>@endif
                </div>
                <p class="text-xs" style="color:var(--text-light)">people registered</p>
                @if($event->isFull())
                <div class="mt-2 text-xs px-2 py-1 rounded-lg font-semibold text-center" style="background:#fef2f2;color:#dc2626">Event Full</div>
                @endif
            </div>
            @endif

        </div>
    </div>
</form>

@push('scripts')
<script>
// Toggle max attendees field based on registration checkbox
const regToggle = document.getElementById('reg-toggle');
const maxWrap   = document.getElementById('max-attendees-wrap');
function updateMaxVis() { maxWrap.style.display = regToggle.checked ? '' : 'none'; }
regToggle.addEventListener('change', updateMaxVis);
updateMaxVis();
</script>
@endpush

@endsection
