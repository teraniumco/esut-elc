<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'matter_category' => ['required', 'string', 'in:' . implode(',', array_keys(\App\Models\Enquiry::MATTER_CATEGORIES))],
            'description'     => ['required', 'string', 'min:30', 'max:3000'],
            'urgency'         => ['required', 'in:normal,urgent'],
            'is_anonymous'    => ['nullable', 'boolean'],
            'attachment'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'], // 5MB
        ];

        // If not anonymous, require contact details
        if (!$this->boolean('is_anonymous')) {
            $rules['full_name'] = ['required', 'string', 'min:2', 'max:100'];
            $rules['email']     = ['required_without:phone', 'nullable', 'email', 'max:150'];
            $rules['phone']     = ['required_without:email', 'nullable', 'string', 'max:20'];
        } else {
            // Anonymous: only email required for delivering response
            $rules['email'] = ['required', 'email', 'max:150'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'description.min'          => 'Please describe your issue in at least 30 characters so we can help you properly.',
            'email.required_without'   => 'Please provide either an email address or phone number.',
            'phone.required_without'   => 'Please provide either a phone number or email address.',
            'attachment.mimes'         => 'Attachments must be a PDF, Word document, or image (JPG/PNG).',
            'attachment.max'           => 'Attachment size must not exceed 5MB.',
        ];
    }
}
