<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerPetRequest extends FormRequest
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
            'name' => ['required','string','max:120'],
            'pet_type_id' => ['required','exists:pet_types,id'],
            'pet_breed_id' => [
                'nullable',
                Rule::exists('pet_breeds', 'id')->where(fn($q) =>
                    $q->where('pet_type_id', $this->input('pet_type_id'))
                ),
            ],
            'size_id' => ['prohibited'],
            'sex' => ['required','in:male,female,unknown'],
            'birthdate' => ['nullable','date','before:tomorrow'],
            'weight_kg' => ['nullable','numeric','between:0,200'],
            'photo' => ['nullable','image','max:2048'],
            'description' => ['nullable','string','max:2000'],
        ];
    }
}
