<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }
    
    public function submit(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ]);
        
        // Process the data (send email, save to database, etc.)
        // For example, send an email:
        Mail::to('info@petcentre.com')->send(new ContactFormMail($validated));
        
        // Redirect back with success message
        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}