<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Resource; // N'oubliez pas d'importer le modèle
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // --- GESTION DES UTILISATEURS ---

    /*public function activateUser(Request $request, $id)
    *{
    *    // Ancienne méthode, remplacée par toggleUserStatus pour plus de simplicité
    *    $user = User::findOrFail($id);
    *    $user->is_active = true;
    *    $user->save();
    *    return back()->with('success', 'Compte activé.');
    *}
    */
    // AJOUT : Fonction pour activer/désactiver (Switch)
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Empêcher l'admin de se désactiver lui-même
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->is_active = !$user->is_active; // On inverse la valeur
        $user->save();

        $status = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Le compte de {$user->name} a été $status.");
    }

    // --- GESTION DES RESSOURCES ---

    // AJOUT : Permet à l'admin d'ajouter une ressource directement
    public function storeResource(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'category' => 'required|string', // Assurez-vous que les catégories correspondent au menu (ex: "Machine Virtuelle")
            'location' => 'required|string',
            //'description' => 'nullable|string',
            'manager_id' => 'required|exists:users,id', // On valide que le manager existe
        ]);

        Resource::create([
            'label' => $request->label,
            'category' => $request->category,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'available',
            'manager_id' => $request->manager_id,
        ]);

        //return redirect()->route('admin.dashboard')->with('success', 'Ressource ajoutée au catalogue.');
        return back()->with('success', 'Pack créé et assigné au manager.');
    }

    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Sécurité : Un admin ne peut pas changer son propre rôle (pour éviter de se bloquer)
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $request->validate([
            'role' => 'required|in:user,manager,admin',
        ]);

        $user->role = $request->role;
        $user->save();

        return back()->with('success', "Le rôle de {$user->name} est maintenant : {$user->role}.");
    }
}