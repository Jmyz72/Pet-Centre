<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MerchantBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'limit' => ['nullable','integer','min:1','max:100'],
            'status' => ['nullable','string','in:pending,confirmed,completed,cancelled'],
            'booking_type' => ['nullable','string','in:service,package,adoption'],
            'date_from' => ['nullable','date'],
            'date_to' => ['nullable','date','after_or_equal:date_from'],
            'upcoming_only' => ['nullable','boolean'],
        ];
    }
}