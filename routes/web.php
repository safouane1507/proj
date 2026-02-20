<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResourceManagerController;

//--- PAGE D'ACCUEIL ---
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Nouvelle page Ressources (Catalogue officiel)
Route::get('/resources', [DashboardController::class, 'guestIndex'])->name('resources.all');

// --- ACCÈS PUBLIC ---
Route::get('/catalogue', [DashboardController::class, 'guestIndex'])->name('guest.index');
Route::get('/resources/{id}', [DashboardController::class, 'resourceDetail'])->name('resource.show');
Route::post('/contact-support', [App\Http\Controllers\IncidentController::class, 'sendContactEmail'])->name('contact.send');

// --- AUTHENTIFICATION ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- INSCRIPTION ---
Route::get('/register-request', [DashboardController::class, 'showRegisterForm'])->name('register.request');
Route::post('/register-request', [AuthController::class, 'register']);

// --- ESPACES SÉCURISÉS ---
Route::middleware(['auth'])->group(function () {

    // Notifications — accessible by all authenticated users
    Route::post('/notifications/mark-read', [DashboardController::class, 'markNotificationsRead'])->name('notifications.markRead');

    // User
    Route::middleware(['role:user'])->prefix('user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
        Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
        
        // Routes pour demandes personnalisées (Custom Requests)
        Route::get('/custom-request', [ReservationController::class, 'createCustom'])->name('user.custom.create');
        Route::post('/custom-request', [ReservationController::class, 'storeCustom'])->name('user.custom.store');
        Route::post('/incidents', [App\Http\Controllers\IncidentController::class, 'store'])->name('incidents.store');
    });

    // Manager
    Route::middleware(['role:manager'])->prefix('manager')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
        Route::post('/reservations/{id}/handle', [ReservationController::class, 'handleRequest'])->name('manager.reservations.handle');
        
        // Gestion complète des ressources (CRUD)
        Route::resource('resources', ResourceManagerController::class)->names('manager.resources');
        
        // Anciennes routes de maintenance (toujours compatibles)
        Route::get('/resources/{id}/edit', [DashboardController::class, 'editResource'])->name('manager.resources.edit');
        Route::post('/resources/{id}', [DashboardController::class, 'updateResource'])->name('manager.resources.update');

        // Routes pour gérer les demandes sur mesure (Custom Requests)
        Route::post('/custom-requests/{id}/approve', [App\Http\Controllers\ResourceManagerController::class, 'approveCustom'])->name('manager.custom.approve');
        Route::post('/custom-requests/{id}/reject', [App\Http\Controllers\ResourceManagerController::class, 'rejectCustom'])->name('manager.custom.reject');
        Route::post('/incidents/{id}/resolve', [App\Http\Controllers\IncidentController::class, 'resolve'])->name('incidents.resolve');
        // Route pour marquer les messages d'aide comme lus
        Route::post('/contact-messages/{id}/read', [App\Http\Controllers\IncidentController::class, 'markMessageRead'])->name('manager.messages.read');

        // Gestion des périodes d'indisponibilité
        Route::post('/resources/{id}/unavailability', [ResourceManagerController::class, 'addUnavailability'])->name('manager.unavailability.add');
        Route::delete('/unavailability/{id}', [ResourceManagerController::class, 'removeUnavailability'])->name('manager.unavailability.remove');
    });

    
    // Admin
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::post('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');
        Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
        Route::post('/resources', [AdminController::class, 'storeResource'])->name('admin.resources.store');

        // --- AJOUTS : Routes pour gérer les demandes et réservations en tant qu'Admin ---
        
        // 1. Gérer les Réservations (Accepter/Refuser)
        Route::post('/reservations/{id}/handle', [ReservationController::class, 'handleRequest'])->name('admin.reservations.handle');

        // 2. Gérer les Demandes sur Mesure (Custom Requests)
        Route::post('/custom-requests/{id}/approve', [ResourceManagerController::class, 'approveCustom'])->name('admin.custom.approve');
        Route::post('/custom-requests/{id}/reject', [ResourceManagerController::class, 'rejectCustom'])->name('admin.custom.reject');
        Route::post('/incidents/{id}/resolve', [App\Http\Controllers\IncidentController::class, 'resolve'])->name('admin.incidents.resolve');
        // Route pour marquer les messages d'aide comme lus
        Route::post('/contact-messages/{id}/read', [App\Http\Controllers\IncidentController::class, 'markMessageRead'])->name('admin.messages.read');
    });

    // Paramètres du profil
    Route::get('/settings', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.settings');
    Route::post('/settings', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});