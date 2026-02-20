@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 40px auto;">
    <div class="card" style="padding: 30px; border-radius: 16px; border: 1px solid var(--border); background: var(--bg-surface);">
        <h2 style="color: var(--primary); margin-bottom: 10px;">✨ Configuration sur Mesure</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 25px;">
            Décrivez précisément l'infrastructure dont vous avez besoin.
        </p>

        <form action="{{ route('user.custom.store') }}" method="POST">
            @csrf

        {{-- Client-Side Error Container --}}
        <div id="js-error-alert" style="display:none; background-color: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <span id="js-error-message"></span>
        </div>

        {{-- Display Global Validation Errors --}}
        @if ($errors->any())
            <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Type d'équipement</label>
                <select name="type" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background); color: var(--text-primary);">
                    <option value="Serveur Physique">Serveur Physique Dédié</option>
                    <option value="VM Haute Performance">Machine Virtuelle (VM)</option>
                    <option value="Stockage Cloud">Baie de Stockage</option>
                    <option value="Équipement Réseau">Équipement Réseau (Switch/Routeur/Firewall)</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Date de début</label>
                    <input type="datetime-local" id="start_date" name="start_date" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background); color: var(--text-primary); @error('start_date') border-color: red; @enderror" value="{{ old('start_date') }}">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Date de fin</label>
                    <input type="datetime-local" id="end_date" name="end_date" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background); color: var(--text-primary); @error('end_date') border-color: red; @enderror" value="{{ old('end_date') }}">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Processeur (CPU)</label>
                    <select name="cpu" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background);">
                        <option value="8 Cores">8 Cores</option>
                        <option value="16 Cores">16 Cores</option>
                        <option value="32 Cores">32 Cores</option>
                        <option value="64 Cores">64 Cores</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Mémoire (RAM)</label>
                    <select name="ram" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background);">
                        <option value="16 GB">16 GB</option>
                        <option value="32 GB">32 GB</option>
                        <option value="64 GB">64 GB</option>
                        <option value="128 GB">128 GB</option>
                        <option value="256 GB">256 GB</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Espace Disque (Storage)</label>
                <select name="storage" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background);">
                    <option value="256 GB SSD">256 GB SSD</option>
                    <option value="512 GB SSD">512 GB SSD</option>
                    <option value="1 TB NVMe">1 TB NVMe</option>
                    <option value="2 TB NVMe">2 TB NVMe</option>
                    <option value="4 TB RAID">4 TB RAID</option>
                    <option value="8 TB RAID">8 TB RAID</option>
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Justification technique</label>
                <textarea name="justification" rows="4" placeholder="Pourquoi avez-vous besoin de cette configuration ?" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background); color: var(--text-primary);"></textarea>
            </div>

            <button type="submit" style="background: var(--primary); color: white; width: 100%; padding: 14px; font-weight: 700; font-size: 1rem; border: none; border-radius: 8px; cursor: pointer;">
                Envoyer ma demande
            </button>
        </form>
    </div>
    
    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('user.dashboard') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">Annuler et retour</a>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const errorAlert = document.getElementById('js-error-alert');
        const errorMessage = document.getElementById('js-error-message');

        function showError(msg) {
            errorMessage.textContent = msg;
            errorAlert.style.display = 'block';
            errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function hideError() {
            errorAlert.style.display = 'none';
        }

        function checkDates() {
            hideError(); 
            
            const now = new Date();
            const startStr = startDateInput.value;
            const endStr = endDateInput.value;

            if (!startStr) return; 

            const start = new Date(startStr);

            if (start < new Date(now.getTime() - 60000)) {
                showError("⚠️ La date de début ne peut pas être dans le passé.");
                startDateInput.value = ''; 
                return;
            }

            if (!endStr) return; 

            const end = new Date(endStr);

            if (end <= start) {
                showError("⚠️ La date de fin doit être strictement après la date de début.");
                endDateInput.value = ''; 
                return;
            }
        }

        if(startDateInput) {
            startDateInput.addEventListener('change', checkDates);
        }
        
        if(endDateInput) {
            endDateInput.addEventListener('change', checkDates);
        }
    });
</script>
@endsection