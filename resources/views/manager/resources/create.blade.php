@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 40px auto;">
    <div class="card">
        <h2 style="color: var(--primary);">➕ Ajouter une ressource</h2>
        <form action="{{ route('manager.resources.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Nom de l'équipement</label>
                <input type="text" name="label" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Catégorie</label>
                <select name="category" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                    <option value="Serveur Physique">Serveur Physique</option>
                    <option value="Machine Virtuelle">Machine Virtuelle</option>
                    <option value="Stockage">Stockage</option>
                    <option value="Réseau">Réseau</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Emplacement</label>
                <input type="text" name="location" placeholder="Ex: Baie A, Rack 4" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Description technique</label>
                <textarea name="description" rows="3" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">Enregistrer la ressource</button>
        </form>
    </div>
</div>
@endsection