<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirection selon le rôle
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Accès refusé : Votre compte est désactivé ou a été banni par l\'administrateur.']);
            }

            $request->session()->regenerate();

            // Redirection selon le rôle
            if ($user->role === 'admin') return redirect()->route('admin.dashboard');
            if ($user->role === 'manager') return redirect()->route('manager.dashboard');
            return redirect()->route('user.dashboard');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ]);
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_active' => false, // Doit être activé par l'admin
        ]);

        return redirect()->route('login')->with('success', 'Compte créé avec succès ! Un administrateur validera votre demande sous 24h.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}