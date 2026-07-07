<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $address = SiteSetting::get('contact_address', "Faculty of Law, ESUT,\nAgbani Road, Enugu State");
        $email   = SiteSetting::get('contact_email', 'elc@esut.edu.ng');
        $hours   = SiteSetting::get('contact_hours', "Monday – Friday\n9:00 AM – 5:00 PM");

        return view('contact.index', compact('address', 'email', 'hours'));
    }

    public function store(ContactFormRequest $request)
    {
        $message = ContactMessage::create([
            ...$request->validated(),
            'ip_address' => $request->ip(),
        ]);

        // Notify clinic admin
        Mail::to(SiteSetting::get('contact_email', config('clinic.admin_email', 'clinic@esut.edu.ng')))
            ->send(new ContactMessageMail($message));

        return back()->with('success', 'Your message has been sent. We will get back to you within 2 business days.');
    }
}
