<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }
    
    public function submit(Request $request)
    {
        // Validate the form data with phone number validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(\+?601[0-46-9]|01[0-46-9])[ -]?\d{3,4}[ -]?\d{4}$/'
            ],

            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ], [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.regex' => 'Please enter a valid Malaysian phone number (e.g., +60123456789, 012-3456789).',
            'phone.max' => 'Phone number must not exceed 20 characters.',
            'subject.required' => 'Please select a subject.',
            'message.required' => 'Please enter your message.',
            'message.min' => 'Your message must be at least 10 characters long.',
        ]);

        // Use transaction to ensure data consistency
        return \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            try {
                // Save to database
                $contactMessage = ContactMessage::create($validated);
                
                // Send email notification
                Mail::to('info@petcentre.com')->send(new ContactFormMail($validated));
                
                // Clear the form
                return redirect()->back()
                    ->with('success', 'Thank you for your message! We will get back to you soon.')
                    ->withInput(['clear' => true]);

            } catch (\Exception $e) {
                // Log the error
                \Log::error('Contact form submission failed: ' . $e->getMessage());
                
                // The transaction will automatically roll back if any error occurs
                return redirect()->back()
                    ->with('error', 'Sorry, there was an error sending your message. Please try again later.')
                    ->withInput(); // Keep user input
            }
        });
    }
}