<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    /**
     * Everyone is allowed; authentication/authorization can be layered separately.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validate the eligible-staff query parameters.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item'     => ['required','in:service,package'],
            'item_id'  => ['required','integer'],
            'start_at' => ['nullable','date'],
            'minutes'  => ['nullable','integer','min:1','max:1440'],
        ];
    }

    /**
     * Optionally normalize incoming values.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'item_id' => $this->integer('item_id'),
            'minutes' => $this->filled('minutes') ? (int) $this->minutes : null,
        ]);
    }
}
