@extends('layouts.app')

@section('content')
<div style="max-width: 500px; margin: 50px auto;">
    
    <div class="card" style="padding: 40px; border-radius: 16px; background: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow);">
        
        <h2 style="text-align: center; color: var(--primary); margin-bottom: 30px; font-weight: 800;">Inscription</h2>

        @if ($errors->any())
            <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffcdd2;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.request') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background-color: var(--bg-background) !important; color: var(--text-primary) !important;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Adresse Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background-color: var(--bg-background) !important; color: var(--text-primary) !important;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Mot de passe</label>
                <input type="password" name="password" required 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background-color: var(--bg-background) !important; color: var(--text-primary) !important;">
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background-color: var(--bg-background) !important; color: var(--text-primary) !important;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-weight: bold; font-size: 1rem; border-radius: 8px;">
                Créer mon compte
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 25px; font-size: 0.95rem; color: var(--text-muted);">
            Déjà inscrit ? <a href="{{ route('login') }}" style="color: var(--primary); font-weight: bold; text-decoration: none;">Se connecter</a>
        </p>
    </div>
</div>
@endsection