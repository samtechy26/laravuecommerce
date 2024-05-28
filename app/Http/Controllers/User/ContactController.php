<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'visitor_message' => 'required|string'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'visitor_message' => $request->visitor_message
        ];

        Mail::to('akansundaysam@gmail.com')->send(new ContactFormMail($data['name'], $data['email'], $data['visitor_message']));

        return back()->with('success', 'Email successfully sent');
    }
}
