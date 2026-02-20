<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Incident;

class DashboardController extends Controller
{
    // --- NOTIFICATIONS ---
    public function markNotificationsRead()
    {
        \App\Models\Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    // --- PARTIE PUBLIQUE ---
    public function guestIndex(Request $request) {
        $query = Resource::with(['unavailabilityPeriods', 'reservations.user', 'manager']);

        // Filtrage dynamique
        if ($request->has('cat') && !empty($request->cat)) {
            $query->where('category', $request->cat);
        }

        $resources = $query->get();
        
        return view('resources', compact('resources'));
    }

    public function resourceDetail($id) {
        $resource = Resource::with([
            'unavailabilityPeriods.creator',
            'reservations.user',
            'manager',
        ])->findOrFail($id);
        return view('guest.resource_detail', compact('resource'));
    }

    public function showRegisterForm() {
        return view('auth.register');
    }

    // --- PARTIE UTILISATEUR ---
    public function userDashboard() {
        $user = Auth::user();
        if (!$user) { return redirect()->route('login'); }
        
        $myReservations = $user->reservations()->latest()->get();
        $myCustomRequests = DB::table('custom_requests')->where('user_id', $user->id)->latest()->get();
        $myIncidents = Incident::where('user_id', $user->id)->latest()->get();

        return view('user.dashboard', compact('myReservations', 'myCustomRequests', 'myIncidents'));
    }

    // --- PARTIE MANAGER ---
    public function managerDashboard() {
        $managerId = Auth::id();
        $managedResources = Resource::where('manager_id', $managerId)->get();
        $resourceIds = $managedResources->pluck('id');
        
        $pendingReservations = Reservation::whereIn('resource_id', $resourceIds)
            ->where('status', 'pending')
            ->with(['user', 'resource'])
            ->orderBy('created_at', 'asc')
            ->get();

        $customRequests = DB::table('custom_requests')
            ->join('users', 'custom_requests.user_id', '=', 'users.id')
            ->select('custom_requests.*', 'users.name', 'users.email')
            ->where('custom_requests.status', 'pending')
            ->get();

            // Récupérer les incidents non résolus
            $incidents = Incident::where('status', 'open')->with('user')->get();

            // Récupérer les messages d'aide
            $helpMessages = \App\Models\ContactMessage::orderBy('is_read', 'asc')->orderBy('created_at', 'desc')->get();
        
        return view('manager.dashboard', compact('managedResources', 'pendingReservations', 'customRequests', 'incidents', 'helpMessages'));
    }

    // --- PARTIE ADMIN (CORRIGÉE ET RESTAURÉE)(AVEC STATS GRAPHIQUES & HISTORIQUE FILTRÉ) ---
    public function adminDashboard(Request $request) {
        // 1. RESTAURATION DES STATISTIQUES
        $stats = [
            'users_count' => User::count(),
            'resources_count' => Resource::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
        ];

        // 2. Données pour les GRAPHIQUES (Chart.js)
        $chartData = [
            'status' => Reservation::select('status', DB::raw('count(*) as total')) ->groupBy('status')->pluck('total', 'status')->toArray(),
            'resources' => Resource::select('category', DB::raw('count(*) as total')) ->groupBy('category')->pluck('total', 'category')->toArray(),
        ];
        // 3. HISTORIQUE FILTRÉ
        $historyQuery = Reservation::with(['user', 'resource'])->latest();

        // Filtre par Date
        if ($request->filled('date_start')) {
            $historyQuery->whereDate('created_at', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $historyQuery->whereDate('created_at', '<=', $request->date_end);
        }
        // Filtre par État
        if ($request->filled('filter_status')) {
            $historyQuery->where('status', $request->filter_status);
        }

        $history = $historyQuery->take(50)->get(); // On limite aux 50 derniers résultats

        // Gestion des utilisateurs (Tout le monde sauf soi-même)
        $allUsers = User::where('id', '!=', Auth::id())->get();
        
        //  Liste des managers (pour l'ajout de ressource)
        $managers = User::where('role', 'manager')->orWhere('role', 'admin')->get();
        
        //  RESTAURATION DES RÉSERVATIONS EN ATTENTE
        $pendingReservations = Reservation::where('status', 'pending')
            ->with(['user', 'resource'])
            ->orderBy('created_at', 'asc')
            ->get();

        // 5. Demandes sur mesure
        $customRequests = DB::table('custom_requests')
            ->join('users', 'custom_requests.user_id', '=', 'users.id')
            ->select('custom_requests.*', 'users.name', 'users.email')
            ->where('custom_requests.status', 'pending')
            ->get();

    
        $incidents = Incident::where('status', 'open')->with('user')->get();    

        // Récupérer les messages d'aide
        $helpMessages = \App\Models\ContactMessage::orderBy('is_read', 'asc')->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('stats', 'chartData','history', 'allUsers', 'managers', 'pendingReservations', 'customRequests', 'incidents', 'helpMessages'));
    }

    // --- GESTION RESSOURCES (Admin & Manager) ---
    public function editResource($id) {
        $resource = Resource::findOrFail($id);
        if ($resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') { abort(403); }
        return view('manager.resources.edit', compact('resource'));
    }

    public function updateResource(Request $request, $id) {
        $resource = Resource::findOrFail($id);

        // Vérification : Seul le manager assigné ou un Admin peut modifier
        if ($resource->manager_id != Auth::id() && Auth::user()->role !== 'admin') { 
            abort(403, "Vous n'avez pas le droit de gérer cette ressource."); 
        }

        $request->validate([ 
            'status' => 'required', 
            'description' => 'nullable' 
        ]);

        $resource->update([ 
            'status' => $request->status, 
            'description' => $request->description 
        ]);

        $route = Auth::user()->role === 'admin' ? 'admin.dashboard' : 'manager.dashboard';
        return redirect()->route($route)->with('success', 'Ressource mise à jour.');
    }
}