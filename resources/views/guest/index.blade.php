@extends('layouts.app')

@section('content')

<section style="
    text-align: center; 
    padding: 100px 20px; 
    background: linear-gradient(180deg, var(--bg-surface) 0%, var(--bg-background) 100%);
    border-bottom: 1px solid var(--border);">
    
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 style="font-size: 3.5rem; margin-bottom: 20px; color: var(--text-primary); line-height: 1.1; font-weight: 800;">
            Infrastructure <br>
            <span style="color: var(--primary);">Management Platform</span>
        </h1>
        <p style="font-size: 1.2rem; color: var(--text-muted); max-width: 600px; margin: 0 auto;">
            @if($resources->isEmpty())
                Veuillez sélectionner une catégorie dans le menu <b>Ressources</b> ci-dessus.
            @else
                Explorez nos équipements de type <b style="color: var(--primary);">{{ request('cat') }}</b>.
            @endif
        </p>
    </div>
</section>

@if($resources->isNotEmpty())
<div class="container" id="catalogue" style="margin-top: 40px; padding-bottom: 80px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="font-size: 1.6rem; font-weight: 700; color: var(--text-primary);">Résultats : {{ request('cat') }}</h2>
        <a href="{{ route('guest.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem; background: var(--bg-surface); padding: 5px 15px; border-radius: 20px; border: 1px solid var(--border);">✖ Effacer le filtre</a>
    </div>

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 75px 25px;">
        @foreach($resources as $resource)
            <div class="card" style="
                position: relative;
                display: flex; 
                flex-direction: column; 
                justify-content: space-between; 
                min-height: 260px;
                padding: 25px;
                border: 1px solid var(--border);
                background: var(--bg-surface);
                border-radius: 16px;">
                
                <div style="
                    position: absolute; top: 20px; right: 20px; width: 10px; height: 10px; border-radius: 50%; 
                    background: {{ $resource->status == 'available' ? '#00b894' : '#d63031' }};
                    box-shadow: 0 0 8px {{ $resource->status == 'available' ? '#00b894' : '#d63031' }};">
                </div>

                <div>
                    <span style="
                        display: inline-block; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px; 
                        color: var(--primary); font-weight: 800; margin-bottom: 12px;
                        background: rgba(15, 163, 163, 0.08); padding: 4px 12px; border-radius: 20px;">
                        {{ $resource->category }}
                    </span>

                    <h3 style="margin: 0 0 10px 0; font-size: 1.25rem; color: var(--text-primary); font-weight: 700;">
                        {{ $resource->label }}
                    </h3>
                    
                    <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; margin-bottom: 20px;">
                        {{ Str::limit($resource->description, 90) }}
                    </p>
                </div>

                <div style="padding-top: 15px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 0.8rem;">
                        <span style="display: block; color: var(--text-muted); font-size: 0.7rem; margin-bottom: 2px;">Localisation</span>
                        <strong style="color: var(--text-primary);">{{ $resource->location }}</strong>
                    </div>

                    @auth
                        @if(Auth::user()->role === 'user')
                            @if($resource->status == 'available')
                                <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" 
                                   class="btn-action" 
                                   style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 5px;">
                                   Réserver <span>→</span>
                                </a>
                            @else
                                <span style="color: #d63031; font-size: 0.8rem; font-weight: 700;">Indisponible</span>
                            @endif
                        @else
                            <span style="color: var(--text-muted); font-size: 0.75rem; font-style: italic;">Mode Gestion</span>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-action" style="color: var(--primary); text-decoration: none; font-weight: 700;">Se connecter →</a>
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<style>
    .card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); border-color: var(--primary); }
    .btn-action:hover { text-decoration: underline; color: var(--secondary); }
</style>

@endsection