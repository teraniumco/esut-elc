<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'min:2', 'max:100'],
            'email'       => ['required', 'email', 'max:150'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'affiliation' => ['nullable', 'string', 'max:100'],
        ];
    }
}
