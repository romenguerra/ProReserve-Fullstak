<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Enviar el correo al administrador (puedes cambiar este email por el tuyo)
        Mail::to('proreserve.soporte@gmail.com')->send(new ContactMessage($validated));

        return back()->with('success', 'Tu mensaje ha sido enviado correctamente. Nos pondremos en contacto contigo pronto.');
    }
}
