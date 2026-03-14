<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Rules\Turnstile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'service' => 'nullable|string|max:100',
            'message' => 'required|string',
            'cf_turnstile_response' => ['required', new Turnstile()],
        ], [
            'cf_turnstile_response.required' => 'Please complete the CAPTCHA verification.',
        ]);

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'service' => $validated['service'],
            'message' => $validated['message'],
        ]);

        return back()->with('message', 'Thank you for your message. We will get back to you shortly!');
    }

    public function index()
    {
        $messages = ContactMessage::latest()->get();
        return Inertia::render('Admin/Contacts/Index', [
            'messages' => $messages
        ]);
    }
}
