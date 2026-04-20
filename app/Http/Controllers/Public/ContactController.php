<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Models\ContactMessage;
use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(ContactFormRequest $request)
    {
        $message = ContactMessage::create([
            ...$request->validated(),
            'ip_address' => $request->ip(),
        ]);

        // Notify clinic admin
        Mail::to(config('clinic.admin_email', 'clinic@esut.edu.ng'))
            ->queue(new ContactMessageMail($message));

        return back()->with('success', 'Your message has been sent. We will get back to you within 2 business days.');
    }
}
