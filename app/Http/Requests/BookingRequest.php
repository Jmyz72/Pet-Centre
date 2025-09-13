<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // allow all authenticated users
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $type = $this->input('booking_type');
        $method = $this->input('payment_method');

        $rulesCommon = [
            'merchant_id' => 'required|exists:merchant_profiles,id',
            'start_at'    => [
                'required', 'date',
                function ($attr, $value, $fail) {
                    $start = Carbon::parse($value);
                    if ($start->lt(now()->addMinutes(120))) {
                        $fail('Please book at least 2 hours in advance.');
                    }
                    if ($start->gt(now()->addDays(28)->endOfDay())) {
                        $fail('Bookings cannot be made more than 28 days in advance.');
                    }
                }
            ],
            'payment_method' => 'required|in:fpx,card',
            'bank'           => 'nullable|string',
            'card_name'      => 'nullable|string',
            'card_number'    => 'nullable|string',
            'card_expiry'    => 'nullable|string',
            'card_ccv'       => 'nullable|string',
        ];

        $rulesByType = match ($type) {
            'service' => [
                'service_id'      => 'required|exists:services,id',
                'customer_pet_id' => 'required|exists:customer_pets,id',
                'staff_id'        => 'required|exists:staff,id',
            ],
            'package' => [
                'package_id'      => 'required|exists:packages,id',
                'customer_pet_id' => 'required|exists:customer_pets,id',
                'staff_id'        => 'required|exists:staff,id',
                'pet_type_id'     => 'nullable|integer',
                'size_id'         => 'nullable|integer',
                'breed_id'        => 'nullable|integer',
            ],
            'adoption' => [
                'pet_id' => [
                    'required',
                    'exists:pets,id',
                    function ($attribute, $value, $fail) {
                        $fee = \App\Models\Pet::where('id', $value)->value('adoption_fee');
                        if (is_null($fee) || $fee <= 0) {
                            $fail('Selected pet does not have a valid adoption fee.');
                        }
                    },
                ],
            ],
            default => [],
        };

        if ($method === 'card') {
            $rulesCommon = array_merge($rulesCommon, [
                'card_name'   => 'required|string',
                'card_number' => 'required|string',
                'card_expiry' => 'required|string',
                'card_ccv'    => 'required|string',
            ]);
        }

        return $rulesCommon + $rulesByType;
    }
}
