<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation for every public enquiry form.
 *
 * The same rules apply to the popup, the contact page and the home page so a
 * valid submission is always accepted by the same code path.
 */
class StoreEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:140'],
            'phone' => ['required', 'string', 'min:7', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'email' => ['nullable', 'email', 'max:180'],
            'service' => ['required', 'string', 'max:140'],
            'event_type' => ['required', 'string', 'max:120'],
            'event_date' => ['required', 'date'],
            'event_time' => ['required', 'date_format:H:i'],
            'event_address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:140'],
            'budget' => ['nullable', 'string', 'max:80'],
            'message' => ['nullable', 'string', 'max:2000'],
            'source_page' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'Please enter a valid phone number (digits, spaces, +, - and brackets only).',
            'event_date.date' => 'Please choose a valid event date.',
            'event_time.date_format' => 'Please choose a valid event time.',
        ];
    }
}
