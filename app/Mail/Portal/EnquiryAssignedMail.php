<?php

namespace App\Mail\Portal;

use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnquiryAssignedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Enquiry $enquiry,
        public readonly User $advisor,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "New Enquiry Assigned — {$this->enquiry->reference_code}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.portal.enquiry-assigned');
    }
}
