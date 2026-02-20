@extends('layouts.app')

@section('content')
<script src="{{ asset('js/chart.min.js') }}"></script>

<div style="max-width: 1200px; margin: 0 auto; padding-bottom: 50px;">
    <h1 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px; margin-bottom: 30px;">Panneau d'Administration</h1>

    @if(session('success')) <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px;">{{ session('success') }}</div> @endif
    @if(session('error')) <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 8px; margin-bottom: 20px;">{{ session('error') }}</div> @endif

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div class="card" style="text-align: center; border-top: 4px solid var(--accent);">
            <h3 style="margin: 0; font-size: 2rem;">{{ $stats['users_count'] }}</h3><span>Utilisateurs</span>
        </div>
        <div class="card" style="text-align: center; border-top: 4px solid var(--success);">
            <h3 style="margin: 0; font-size: 2rem;">{{ $stats['resources_count'] }}</h3><span>Ressources</span>
        </div>
        <div class="card" style="text-align: center; border-top: 4px solid orange;">
            <h3 style="margin: 0; font-size: 2rem;">{{ $stats['pending_reservations'] }}</h3><span>En attente</span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px; margin-bottom: 40px;">
        <div class="card" style="padding: 20px;"><h3 style="text-align: center;">📊 État Réservations</h3><div style="height:250px;"><canvas id="statusChart"></canvas></div></div>
        <div class="card" style="padding: 20px;"><h3 style="text-align: center;">🖥️ Types Ressources</h3><div style="height:250px;"><canvas id="resourceChart"></canvas></div></div>
    </div>

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid #d63031;">
        <h2 style="color: #d63031;">🚨 Incidents Signalés</h2>
        @if($incidents->isEmpty()) <p style="color: var(--text-muted);">R.A.S. Aucun incident.</p> @else
        <table style="width: 100%;">
            <tbody id="list-incidents">
            @foreach($incidents as $inc)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;"><b>{{ $inc->subject }}</b><br><small>{{ $inc->user->name }}</small></td>
                <td style="padding: 10px;">{{ Str::limit($inc->message, 50) }}</td>
                <td style="padding: 10px;">
                    <form action="{{ route('admin.incidents.resolve', $inc->id) }}" method="POST">@csrf <button type="submit" style="background:#2ecc71; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Résoudre</button></form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <div id="toggle-incidents" style="margin-top:10px;"></div>
        @endif
    </div>

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid #8e44ad;">
        <h2 style="color: #8e44ad;">📩 Messages d'Aide</h2>
        @if($helpMessages->isEmpty())
            <p style="color: var(--text-muted);">Aucun message.</p>
        @else
            <div id="list-messages" style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($helpMessages as $msg)
                <div style="border: 1px solid var(--border); border-radius: 8px; overflow: hidden;">
                    <div onclick="document.getElementById('msg-content-{{ $msg->id }}').style.display = document.getElementById('msg-content-{{ $msg->id }}').style.display === 'none' ? 'block' : 'none'"
                         style="padding: 15px; background: {{ $msg->is_read ? 'var(--bg-surface)' : 'rgba(255, 193, 7, 0.15)' }}; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border-left: 4px solid {{ $msg->is_read ? 'transparent' : '#f1c40f' }};">
                        <div>
                            <strong style="color: var(--text-primary);">Message aide from {{ $msg->email }}</strong>
                            <span style="font-size: 0.85rem; color: var(--text-muted); margin-left: 10px;">{{ $msg->created_at->format('d/m H:i') }}</span>
                        </div>
                        <div>
                            <span style="padding: 4px 8px; border-radius: 4px; background: {{ $msg->is_read ? 'var(--bg-background)' : '#ffecb3' }}; color: {{ $msg->is_read ? 'var(--text-muted)' : '#b45309' }}; font-size: 0.8rem; font-weight: bold; border: 1px solid {{ $msg->is_read ? 'var(--border)' : 'transparent' }};">
                                {{ $msg->is_read ? 'Lu' : 'Non Lu' }}
                            </span>
                        </div>
                    </div>
                    <div id="msg-content-{{ $msg->id }}" style="display: none; padding: 15px; border-top: 1px solid var(--border); background: var(--bg-background);">
                        <p style="white-space: pre-wrap; margin-bottom: 15px; color: var(--text-primary);">{{ $msg->message }}</p>
                        @if(!$msg->is_read)
                            <form action="{{ route('admin.messages.read', $msg->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Marquer comme lu</button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div id="toggle-messages" style="margin-top:10px;"></div>
        @endif
    </div>

    <div class="card" style="margin-bottom: 30px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h2>📜 Historique</h2>
            <form action="{{ route('admin.dashboard') }}" method="GET" style="display:flex; gap:10px;">
                <input type="date" name="date_start" value="{{ request('date_start') }}" style="padding:5px; border:1px solid #ddd; border-radius:4px;">
                <button type="submit" class="btn btn-primary" style="padding:5px 15px;">Filtrer</button>
            </form>
        </div>
        <table style="width:100%;">
            <thead style="background:var(--bg-background);"><tr><th style="padding:10px; text-align:left;">Date</th><th style="padding:10px; text-align:left;">User</th><th style="padding:10px; text-align:left;">Ressource</th><th style="padding:10px;">Statut</th></tr></thead>
            <tbody id="list-history">
            @foreach($history as $h)
            <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:10px;">{{ $h->created_at->format('d/m/Y') }}</td>
                <td style="padding:10px;">{{ $h->user->name }}</td>
                <td style="padding:10px;">{{ $h->resource->label }}</td>
                <td style="padding:10px; text-align:center;"><span style="padding:2px 8px; border-radius:4px; background:{{ $h->status=='approved'?'#e8f5e9':($h->status=='rejected'?'#ffebee':'#fff3e0') }}; color:{{ $h->status=='approved'?'green':($h->status=='rejected'?'red':'orange') }};">{{ ucfirst($h->status) }}</span></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <div id="toggle-history" style="margin-top:10px;"></div>
    </div>

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid var(--primary);">
        <h2 style="color: var(--primary);">✨ Demandes Spéciales</h2>
        @if($customRequests->isEmpty()) <p style="color: #777;">Aucune demande.</p> @else
        <table style="width: 100%;">
            @foreach($customRequests as $req)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;"><b>{{ $req->name }}</b><br><small>{{ $req->type }}</small></td>
                <td style="padding: 10px;">CPU: {{ $req->cpu }} | RAM: {{ $req->ram }}</td>
                <td style="padding: 10px;">
                    <div style="display: flex; gap: 5px; flex-wrap:wrap;">
                        <form action="{{ route('admin.custom.approve', $req->id) }}" method="POST">@csrf <button type="submit" style="background: #2ecc71; color: white; border: none; padding: 5px 10px; border-radius: 6px; cursor: pointer;">✔</button></form>
                        <div>
                            <button type="button" onclick="toggleRefuseCustom('a{{ $req->id }}')" style="background: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 6px; cursor: pointer;">✘</button>
                            <div id="refuse-custom-a{{ $req->id }}" style="display:none; margin-top:6px; background:var(--bg-background); border:1px solid #e74c3c44; border-radius:8px; padding:10px; min-width:220px;">
                                <form action="{{ route('admin.custom.reject', $req->id) }}" method="POST" onsubmit="return validateRefuseCustom('a{{ $req->id }}')">
                                    @csrf
                                    <label style="display:block; font-size:0.75rem; font-weight:700; color:#e74c3c; margin-bottom:4px; text-transform:uppercase;">Motif <span style="color:red">*</span></label>
                                    <textarea id="custom-motif-a{{ $req->id }}" name="manager_feedback" rows="2" placeholder="Raison du refus..." style="width:100%; padding:6px; border-radius:6px; border:1px solid var(--border); background:var(--bg-surface); color:var(--text-primary); font-size:0.82rem; resize:vertical;"></textarea>
                                    <div style="display:flex; gap:5px; margin-top:6px; justify-content:flex-end;">
                                        <button type="button" onclick="toggleRefuseCustom('a{{ $req->id }}')" style="padding:4px 8px; border:1px solid var(--border); border-radius:5px; background:transparent; color:var(--text-muted); cursor:pointer; font-size:0.8rem;">Annuler</button>
                                        <button type="submit" style="padding:4px 10px; background:#e74c3c; color:white; border:none; border-radius:5px; cursor:pointer; font-weight:700; font-size:0.8rem;">Confirmer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
        @endif
    </div>

    <div class="card" style="margin-bottom: 30px;">
        <h2>👥 Utilisateurs</h2>
        <table style="width: 100%;">
            <tr style="background: var(--bg-background);"><th style="padding: 10px; text-align: left;">Nom</th><th style="padding: 10px; text-align: left;">Rôle</th><th style="padding: 10px; text-align: left;">Action</th></tr>
            @foreach($allUsers as $user)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">{{ $user->name }}<br><small>{{ $user->email }}</small></td>
                <td style="padding: 10px;">
                    <form action="{{ route('admin.users.role', $user->id) }}" method="POST">
                        @csrf <select name="role" onchange="this.form.submit()" style="padding: 5px; border: 1px solid #ddd; border-radius: 4px;"><option value="user" {{ $user->role=='user'?'selected':'' }}>User</option><option value="manager" {{ $user->role=='manager'?'selected':'' }}>Manager</option><option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option></select>
                    </form>
                </td>
                <td style="padding: 10px;">
                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                        @csrf <button type="submit" style="background: {{ $user->is_active ? '#e74c3c' : '#2ecc71' }}; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">{{ $user->is_active ? 'Désactiver' : 'Activer' }}</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="card">
        <h2>➕ Ajouter Pack</h2>
        <form action="{{ route('admin.resources.store') }}" method="POST" style="display: grid; gap: 10px; grid-template-columns: 1fr 1fr;">
            @csrf
            <input type="text" name="label" placeholder="Nom" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <select name="category" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="Serveur Physique">Serveur Physique</option><option value="Machine Virtuelle">Machine Virtuelle</option><option value="Stockage">Stockage</option><option value="Réseau">Réseau</option>
            </select>
            <select name="manager_id" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="" disabled selected>Responsable...</option>
                @foreach($managers as $manager) <option value="{{ $manager->id }}">{{ $manager->name }}</option> @endforeach
            </select>
            <input type="text" name="location" placeholder="Localisation" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <input type="text" name="description" placeholder="Description" style="grid-column: span 2; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <button type="submit" style="grid-column: span 2; background: var(--primary); color: white; border: none; padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer;">Ajouter</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusData = {!! json_encode($chartData['status']) !!};
        const resourceData = {!! json_encode($chartData['resources']) !!};

        new Chart(document.getElementById('statusChart').getContext('2d'), {
            type: 'doughnut',
            data: { labels: Object.keys(statusData), datasets: [{ data: Object.values(statusData), backgroundColor: ['#f1c40f', '#2ecc71', '#e74c3c'] }] },
            options: { responsive: true, maintainAspectRatio: false }
        });

        new Chart(document.getElementById('resourceChart').getContext('2d'), {
            type: 'bar',
            data: { labels: Object.keys(resourceData), datasets: [{ label: 'Quantité', data: Object.values(resourceData), backgroundColor: '#3498db' }] },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });
</script>

<script>
function toggleRefuseCustom(id) {
    const el = document.getElementById('refuse-custom-' + id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
    if (el.style.display === 'block') el.querySelector('textarea').focus();
}
function validateRefuseCustom(id) {
    const txt = document.getElementById('custom-motif-' + id);
    if (!txt.value.trim()) {
        txt.style.borderColor = '#e74c3c';
        txt.placeholder = '⚠️ Motif obligatoire !';
        txt.focus();
        return false;
    }
    return true;
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const LIMIT = 5;
    const BTN_STYLE = 'background:transparent; border:1px solid var(--border); padding:6px 18px; border-radius:20px; cursor:pointer; font-size:0.83rem; font-weight:600; color:var(--primary); transition:all 0.2s; margin-top:4px;';

    // Table tbody lists
    [['list-incidents','toggle-incidents'],['list-history','toggle-history']].forEach(([listId, btnId]) => {
        const tbody = document.getElementById(listId);
        const wrap  = document.getElementById(btnId);
        if (!tbody || !wrap) return;
        const rows = Array.from(tbody.querySelectorAll('tr'));
        if (rows.length <= LIMIT) return;
        rows.slice(LIMIT).forEach(r => r.style.display = 'none');
        let expanded = false;
        const btn = document.createElement('button');
        btn.style.cssText = BTN_STYLE;
        btn.textContent = '⬇ Voir plus (' + (rows.length - LIMIT) + ' de plus)';
        btn.addEventListener('click', () => {
            expanded = !expanded;
            rows.slice(LIMIT).forEach(r => r.style.display = expanded ? '' : 'none');
            btn.textContent = expanded ? '⬆ Voir moins' : '⬇ Voir plus (' + (rows.length - LIMIT) + ' de plus)';
        });
        wrap.appendChild(btn);
    });

    // Flex/div messages list
    [['list-messages','toggle-messages']].forEach(([listId, btnId]) => {
        const container = document.getElementById(listId);
        const wrap      = document.getElementById(btnId);
        if (!container || !wrap) return;
        const items = Array.from(container.children);
        if (items.length <= LIMIT) return;
        items.slice(LIMIT).forEach(i => i.style.display = 'none');
        let expanded = false;
        const btn = document.createElement('button');
        btn.style.cssText = BTN_STYLE;
        btn.textContent = '⬇ Voir plus (' + (items.length - LIMIT) + ' de plus)';
        btn.addEventListener('click', () => {
            expanded = !expanded;
            items.slice(LIMIT).forEach(i => i.style.display = expanded ? '' : 'none');
            btn.textContent = expanded ? '⬆ Voir moins' : '⬇ Voir plus (' + (items.length - LIMIT) + ' de plus)';
        });
        wrap.appendChild(btn);
    });
});
</script>
@endsection