<?php

namespace App\Mail;

use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly EventRegistration $registration) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Event Registration Confirmed – {$this->registration->event->title}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.event-registration');
    }
}
