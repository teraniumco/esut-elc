<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRegistrationRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Mail\EventRegistrationMail;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
{
    public function index()
    {
        $upcoming = Event::published()->upcoming()->get();
        $past     = Event::published()->past()->take(6)->get();

        return view('events.index', compact('upcoming', 'past'));
    }

    public function show(Event $event)
    {
        abort_if(!$event->is_published, 404);
        return view('events.show', compact('event'));
    }

    public function register(EventRegistrationRequest $request, Event $event)
    {
        abort_if(!$event->is_published, 404);
        abort_if(!$event->requires_registration, 400);
        abort_if($event->isFull(), 422, 'This event is fully booked.');

        // Prevent duplicate registration
        $alreadyRegistered = EventRegistration::where('event_id', $event->id)
            ->where('email', $request->email)
            ->exists();

        if ($alreadyRegistered) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'This email address is already registered for this event.']);
        }

        $registration = EventRegistration::create([
            'event_id'    => $event->id,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'affiliation' => $request->affiliation,
        ]);

        Mail::to($registration->email)->send(new EventRegistrationMail($registration));

        return back()->with('success', "You have been successfully registered for \"{$event->title}\". A confirmation has been sent to {$registration->email}.");
    }
}
