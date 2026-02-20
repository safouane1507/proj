@extends('layouts.app')

@section('content')
<div style="max-width: 400px; margin: 50px auto; padding: 30px;" class="card">
    <h2 style="text-align: center; color: var(--primary); margin-bottom: 30px;">Connexion</h2>

    @if(session('success'))
        <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; border: 1px solid #c8e6c9; margin-bottom: 20px; font-size: 0.9rem;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 8px; border: 1px solid #ffcdd2; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label>Email</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; margin-top: 5px;">
        </div>
        <div style="margin-bottom: 25px;">
            <label>Mot de passe</label>
            <input type="password" name="password" required style="width: 100%; padding: 10px; margin-top: 5px;">
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Se connecter</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px; font-size: 0.9rem;">
        Pas encore de compte ? <a href="{{ route('register.request') }}" style="color: var(--primary);">S'inscrire</a>
    </p>
</div>
@endsection