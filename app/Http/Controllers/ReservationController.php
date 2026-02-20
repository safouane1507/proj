<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Notification;
use App\Models\User;

class ReservationController extends Controller
{
    // --- ESPACE UTILISATEUR ---

    /**
     * Affiche le formulaire de réservation standard
     */
    public function create(Request $request)
    {
        $selectedResource = null;
        $bookedSlots = collect();

        if ($request->has('resource_id')) {
            $selectedResource = Resource::find($request->resource_id);

            if ($selectedResource) {
                // Load all active/pending reservations for this resource to display on the form
                $bookedSlots = Reservation::where('resource_id', $selectedResource->id)
                    ->whereIn('status', ['pending', 'approved', 'active'])
                    ->where('end_date', '>', now())
                    ->orderBy('start_date')
                    ->get(['start_date', 'end_date', 'status']);
            }
        }

        $resources = Resource::where('status', 'available')->get();
        return view('user.reservations.create', compact('resources', 'selectedResource', 'bookedSlots'));
    }

    /**
     * Enregistre une demande de réservation standard (avec gestion des conflits)
     */
    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'justification' => 'required|string|max:500',
        ], [
            'start_date.after' => 'La date de début doit être ultérieure à maintenant.',
            'end_date.after' => 'La date de fin doit être après la date de début.',
            'resource_id.required' => 'Veuillez sélectionner une ressource.',
            'justification.required' => 'Le motif est obligatoire.',
        ]);

        // Vérification des conflits — bloque pending + approved + active
        $conflit = Reservation::where('resource_id', $request->resource_id)
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->where(function ($query) use ($request) {
                $query->where('start_date', '<', $request->end_date)
                      ->where('end_date', '>', $request->start_date);
            })
            ->first();

        if ($conflit) {
            $statusLabels = ['pending' => 'en attente de validation', 'approved' => 'approuvée', 'active' => 'en cours'];
            $label        = $statusLabels[$conflit->status] ?? $conflit->status;
            $start        = \Carbon\Carbon::parse($conflit->start_date)->format('d/m/Y H:i');
            $end          = \Carbon\Carbon::parse($conflit->end_date)->format('d/m/Y H:i');
            return back()
                ->withInput()
                ->withErrors(['dates' => "⛔ Ce créneau est déjà occupé par une réservation {$label} du {$start} au {$end}."]);
        }

        Reservation::create([
            'user_id' => Auth::id(),
            'resource_id' => $request->resource_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'justification' => $request->justification,
            'status' => 'pending',
        ]);

        // Notification au Manager
        $resource = Resource::find($request->resource_id);
        if ($resource && $resource->manager_id) {
            Notification::create([
                'user_id' => $resource->manager_id,
                'message' => "Nouvelle demande de " . Auth::user()->name . " pour " . $resource->label,
                'link' => route('manager.dashboard'),
            ]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Votre demande de réservation a été envoyée avec succès !');
    }

    // --- CONFIGURATION SUR MESURE (CUSTOM REQUESTS) ---

    /**
     * Affiche le formulaire de configuration personnalisée
     * Résout l'erreur : Call to undefined method createCustom()
     */
    public function createCustom()
    {
        return view('user.custom_create');
    }

    /**
     * Enregistre la demande personnalisée dans la table custom_requests
     */
    public function storeCustom(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'cpu' => 'required|string',
            'ram' => 'required|string',
            'storage' => 'required|string',
            'justification' => 'required|string|max:1000',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
        ], [
            'start_date.after' => 'La date de début doit être ultérieure à maintenant.',
            'end_date.after' => 'La date de fin doit être après la date de début.',
            'justification.required' => 'La justification est obligatoire.',
        ]);

        // Insertion en base de données
        DB::table('custom_requests')->insert([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'cpu' => $request->cpu,
            'ram' => $request->ram,
            'storage' => $request->storage,
            'justification' => $request->justification,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Notification à l'Admin (Optionnel)
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => Auth::user()->name . " a demandé une configuration sur mesure.",
                'link' => route('admin.dashboard'),
            ]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Votre demande de configuration personnalisée a été envoyée à l\'administrateur.');
    }

    // --- ESPACE RESPONSABLE (MANAGER) ---

    /**
     * Traite (Approuve ou Refuse) une réservation standard
     */
    public function handleRequest(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Sécurité : Seul le manager de la ressource peut décider
       if ($reservation->resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, "Action non autorisée sur cette ressource.");
        }

        // Validation du motif de refus si action = reject
        if ($request->action === 'reject') {
            $request->validate([
                'manager_feedback' => 'required|string|min:5|max:500',
            ], [
                'manager_feedback.required' => 'Le motif du refus est obligatoire.',
                'manager_feedback.min'      => 'Le motif doit comporter au moins 5 caractères.',
            ]);
        }

        $messageUser = "";

        if ($request->action === 'approve') {
            $reservation->update(['status' => 'approved']);
            $messageUser = "✅ Votre réservation pour " . $reservation->resource->label . " a été acceptée !";
            
        } elseif ($request->action === 'reject') {
            $reservation->update([
                'status'           => 'rejected',
                'manager_feedback' => $request->manager_feedback,
            ]);
            $messageUser = "❌ Votre réservation pour " . $reservation->resource->label . " a été refusée. Motif : " . $request->manager_feedback;
        }

        // Notification à l'utilisateur
        if ($messageUser) {
            Notification::create([
                'user_id' => $reservation->user_id,
                'message' => $messageUser,
                'link' => route('user.dashboard'),
            ]);
        }

        return back()->with('success', 'La demande a été traitée.');
    }
}