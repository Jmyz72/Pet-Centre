<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true; // 公开接口，所有人都可以访问
    }

    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255|in:adoption,veterinary,grooming,general,other',
            'message' => 'required|string|min:10|max:2000',
        ];
    }

    
    public function messages(): array
    {
        return [
            'name.required' => ,
            'email.required' => ,
            'email.email' => ,
            'subject.required' => ,
            'subject.in' => ,
            'message.required' => ,
            'message.min' => ,
        ];
    }
}