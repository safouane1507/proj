<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactSupport;

class IncidentController extends Controller
{
    // Enregistrer un incident (User)
    public function store(Request $request) {
        $request->validate(['subject' => 'required', 'message' => 'required']);
        
        Incident::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Incident signalé. Nous traitons votre demande.');
    }

    // Marquer comme résolu (Admin/Manager)
    public function resolve($id) {
        $incident = Incident::findOrFail($id);
        $incident->update(['status' => 'resolved']);
        return back()->with('success', 'Incident marqué comme résolu.');
    }

    // Envoyer un message d'aide (Database)
    public function sendContactEmail(Request $request) {
        $request->validate(['email' => 'required|email', 'message' => 'required']);

        \App\Models\ContactMessage::create([
            'email' => $request->email,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return back()->with('success', 'Votre message a bien été envoyé à notre équipe !');
    }

    // Marquer un message d'aide comme lu
    public function markMessageRead($id) {
        $msg = \App\Models\ContactMessage::findOrFail($id);
        $msg->update(['is_read' => true]);
        return back()->with('success', 'Message marqué comme lu.');
    }
}