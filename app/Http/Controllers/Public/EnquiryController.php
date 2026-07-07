<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitEnquiryRequest;
use App\Models\Enquiry;
use App\Mail\EnquiryConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EnquiryController extends Controller
{
    /**
     * Show the enquiry submission form.
     */
    public function create()
    {
        $categories = Enquiry::MATTER_CATEGORIES;
        return view('enquiry.create', compact('categories'));
    }

    /**
     * Store a new enquiry.
     */
    public function store(SubmitEnquiryRequest $request)
    {
        $data = $request->validated();

        // Handle file upload
        $attachmentPath = null;
        $attachmentName = null;
        if ($request->hasFile('attachment')) {
            $file           = $request->file('attachment');
            $attachmentPath = $file->store('enquiry-attachments', 'private');
            $attachmentName = $file->getClientOriginalName();
        }

        $enquiry = Enquiry::create([
            'reference_code'  => Enquiry::generateReferenceCode(),
            'full_name'       => $data['is_anonymous'] ?? false ? null : ($data['full_name'] ?? null),
            'email'           => $data['email'] ?? null,
            'phone'           => $data['phone'] ?? null,
            'is_anonymous'    => $data['is_anonymous'] ?? false,
            'matter_category' => $data['matter_category'],
            'description'     => $data['description'],
            'urgency'         => $data['urgency'],
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'status'          => 'received',
            'ip_address'      => $request->ip(),
        ]);

        // Send confirmation email if we have an email address
        if ($enquiry->email) {
            Mail::to($enquiry->email)->send(new EnquiryConfirmationMail($enquiry));
        }

        return redirect()
            ->route('enquiry.confirmation', ['ref' => $enquiry->reference_code])
            ->with('success', 'Your enquiry has been submitted successfully.');
    }

    /**
     * Show the submission confirmation page with reference code.
     */
    public function confirmation(Request $request)
    {
        $ref     = $request->get('ref');
        $enquiry = Enquiry::where('reference_code', $ref)->firstOrFail();

        return view('enquiry.confirmation', compact('enquiry'));
    }

    /**
     * Show the tracking page (no login required).
     */
    public function track()
    {
        return view('enquiry.track');
    }

    /**
     * Look up an enquiry by reference code.
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'reference_code' => ['required', 'string', 'regex:/^ELC-\d{4}-\d{5}$/'],
        ], [
            'reference_code.regex' => 'Please enter a valid reference code in the format ELC-YYYY-NNNNN.',
        ]);

        $enquiry = Enquiry::where('reference_code', strtoupper($request->reference_code))->first();

        if (!$enquiry) {
            return back()
                ->withInput()
                ->withErrors(['reference_code' => 'No enquiry found with that reference code. Please check and try again.']);
        }

        return view('enquiry.status', compact('enquiry'));
    }
}
