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

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|max:20',
            'project' => 'nullable|max:255',
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);

        // Ici vous pourriez envoyer un email avec les informations du formulaire
        // Mail::to('info.cameroungrandsudbury@gmail.com')->send(new ContactFormMail($validated));

        return redirect()->route('contact')->with('success', 'Votre message a été envoyé avec succès! Nous vous contacterons bientôt.');
    }
}
