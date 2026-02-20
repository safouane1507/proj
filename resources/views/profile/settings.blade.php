@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 50px auto;">
    <div class="card">
        <h2 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px; margin-bottom: 20px;">⚙️ Paramètres du compte</h2>

        @if(session('success'))
            <div style="background: #e8f5e9; color: green; padding: 10px; border-radius: 5px; margin-bottom: 15px;">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nom complet</label>
                <input type="text" name="name" value="{{ Auth::user()->name }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Adresse Email</label>
                <input type="email" name="email" value="{{ Auth::user()->email }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">
            
            <h3 style="font-size: 1.1rem; color: #666;">Changer le mot de passe (optionnel)</h3>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Nouveau mot de passe</label>
                <input type="password" name="password" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <button type="submit" style="background: var(--primary); color: white; border: none; padding: 12px 20px; width: 100%; border-radius: 5px; cursor: pointer; font-size: 1rem;">Enregistrer les modifications</button>
        </form>
    </div>
</div>
@endsection