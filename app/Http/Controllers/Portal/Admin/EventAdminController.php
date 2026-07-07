<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::withCount('registrations');

        if ($request->filled('filter')) {
            match ($request->filter) {
                'upcoming'   => $query->upcoming(),
                'past'       => $query->past(),
                'draft'      => $query->where('is_published', false),
                'published'  => $query->where('is_published', true),
                default      => null,
            };
        } else {
            $query->orderByDesc('event_date');
        }

        $events = $query->paginate(20)->withQueryString();

        $counts = [
            'all'       => Event::count(),
            'upcoming'  => Event::upcoming()->count(),
            'past'      => Event::past()->count(),
            'draft'     => Event::where('is_published', false)->count(),
        ];

        return view('portal.admin.events.index', compact('events', 'counts'));
    }

    public function create()
    {
        return view('portal.admin.events.form', ['event' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validateEvent($request);

        $base = Str::slug($data['title']);
        $slug = $base;
        $n = 1;
        while (Event::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }
        $data['slug']         = $slug;
        $data['is_published'] = $request->boolean('is_published');

        $event = Event::create($data);
        ActivityLog::record('event.created', $event);

        return redirect()->route('portal.admin.events.index')
            ->with('success', "Event \"{$event->title}\" created.");
    }

    public function edit(Event $event)
    {
        return view('portal.admin.events.form', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $this->validateEvent($request, $event);

        // Regenerate slug only if title changed
        if ($data['title'] !== $event->title) {
            $base = Str::slug($data['title']);
            $slug = $base;
            $n = 1;
            while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = $base . '-' . $n++;
            }
            $data['slug'] = $slug;
        }

        $data['is_published'] = $request->boolean('is_published');
        $event->update($data);
        ActivityLog::record('event.updated', $event);

        return redirect()->route('portal.admin.events.index')
            ->with('success', "Event \"{$event->title}\" updated.");
    }

    public function destroy(Event $event)
    {
        $title = $event->title;
        $event->registrations()->delete();
        $event->delete();
        ActivityLog::record('event.deleted');

        return redirect()->route('portal.admin.events.index')
            ->with('success', "Event \"{$title}\" deleted.");
    }

    public function togglePublish(Event $event)
    {
        $event->update(['is_published' => !$event->is_published]);
        $label = $event->is_published ? 'published' : 'unpublished';
        ActivityLog::record('event.toggled', $event);

        return back()->with('success', "Event {$label}.");
    }

    private function validateEvent(Request $request, ?Event $event = null): array
    {
        return $request->validate([
            'title'                 => ['required', 'string', 'max:255'],
            'description'           => ['required', 'string'],
            'location'              => ['nullable', 'string', 'max:255'],
            'event_date'            => ['required', 'date'],
            'event_end_date'        => ['nullable', 'date', 'after_or_equal:event_date'],
            'requires_registration' => ['nullable', 'boolean'],
            'max_attendees'         => ['nullable', 'integer', 'min:1'],
        ]);
    }
}
