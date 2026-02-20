<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\UnavailabilityPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class ResourceManagerController extends Controller
{
    public function create()
    {
        return view('manager.resources.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'category' => 'required|string',
            'location' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Resource::create([
            'label' => $request->label,
            'category' => $request->category,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'available',
            'manager_id' => Auth::id(),
        ]);

        return redirect()->route('manager.dashboard')->with('success', 'Équipement ajouté au catalogue.');
    }

    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);
        if ($resource->manager_id !== Auth::id()) {
            abort(403);
        }
        $resource->delete();
        return back()->with('success', 'Ressource supprimée.');
    }

    // Approuver la demande
    public function approveCustom($id) {
        $request = DB::table('custom_requests')->where('id', $id)->first();
    
        DB::table('custom_requests')->where('id', $id)->update(['status' => 'approved']);

        // Notification à l'utilisateur
        Notification::create([
            'user_id' => $request->user_id,
            'message' => "✅ Votre demande de configuration sur mesure ({$request->type}) a été approuvée !",
            'link' => route('user.dashboard'),
        ]);

        return back()->with('success', 'La demande a été approuvée.');
    }

    // Rejeter la demande
    public function rejectCustom(Request $request, $id) {
        $request->validate([
            'manager_feedback' => 'required|string|min:5|max:500',
        ], [
            'manager_feedback.required' => 'Le motif du refus est obligatoire.',
            'manager_feedback.min'      => 'Le motif doit comporter au moins 5 caractères.',
        ]);

        $custom = DB::table('custom_requests')->where('id', $id)->first();

        DB::table('custom_requests')->where('id', $id)->update([
            'status'           => 'rejected',
            'manager_feedback' => $request->manager_feedback,
        ]);

        Notification::create([
            'user_id' => $custom->user_id,
            'message' => "❌ Votre demande de configuration pour {$custom->type} a été refusée. Motif : " . $request->manager_feedback,
            'link' => route('user.dashboard'),
        ]);

        return back()->with('success', 'La demande a été rejetée.');
    }

    // Afficher le formulaire d'édition
    public function edit($id)
    {
        $resource = Resource::findOrFail($id);
        
        // Sécurité : On vérifie si c'est le manager propriétaire OU un admin
        if ($resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, "Vous n'avez pas la permission de modifier cette ressource.");
        }

        return view('manager.resources.edit', compact('resource'));
    }

    // Enregistrer les modifications
    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        if ($resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:available,maintenance,inactive,occupied',
            'description' => 'nullable|string',
        ]);

        $resource->update([
            'status' => $request->status,
            'description' => $request->description,
        ]);

        // Redirection intelligente selon le rôle
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Ressource mise à jour.');
        }
        return redirect()->route('manager.dashboard')->with('success', 'Ressource mise à jour.');
    }

    // Ajouter une période d'indisponibilité
    public function addUnavailability(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        if ($resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'reason'     => 'required|string|max:500',
            'type'       => 'required|in:maintenance,panne,réservé,autre',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        UnavailabilityPeriod::create([
            'resource_id' => $resource->id,
            'created_by'  => Auth::id(),
            'reason'      => $request->reason,
            'type'        => $request->type,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
        ]);

        return back()->with('success', 'Période d\'indisponibilité ajoutée.');
    }

    // Supprimer une période d'indisponibilité
    public function removeUnavailability($id)
    {
        $period = UnavailabilityPeriod::findOrFail($id);
        $resource = $period->resource;

        if ($resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $period->delete();
        return back()->with('success', 'Période supprimée.');
    }

}