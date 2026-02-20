@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="margin: 0; font-size: 2.5rem; font-weight: 800; color: var(--text-primary);">Catalogue</h1>
            <p style="color: var(--text-muted); margin-top: 5px;">
                @if(request('cat')) Filtré par : <strong>{{ request('cat') }}</strong> @else Toutes les ressources @endif
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('resources.all') }}" class="btn {{ !request('cat') ? 'btn-primary' : 'btn-outline' }}">Tout</a>
            <a href="{{ route('resources.all', ['cat' => 'Serveur Physique']) }}" class="btn {{ request('cat') == 'Serveur Physique' ? 'btn-primary' : 'btn-outline' }}">Serveurs</a>
            <a href="{{ route('resources.all', ['cat' => 'Machine Virtuelle']) }}" class="btn {{ request('cat') == 'Machine Virtuelle' ? 'btn-primary' : 'btn-outline' }}">VMs</a>
            <a href="{{ route('resources.all', ['cat' => 'Stockage']) }}" class="btn {{ request('cat') == 'Stockage' ? 'btn-primary' : 'btn-outline' }}">Stockage</a>
            <a href="{{ route('resources.all', ['cat' => 'Réseau']) }}" class="btn {{ request('cat') == 'Réseau' ? 'btn-primary' : 'btn-outline' }}">Réseau</a>
        </div>
    </div>

    @if($resources->isEmpty())
        <div style="text-align: center; padding: 80px; background: var(--bg-surface); border-radius: 16px; border: 2px dashed var(--border);">
            <h3 style="color: var(--text-primary);">Aucune ressource trouvée.</h3>
            <a href="{{ route('resources.all') }}" class="btn btn-primary">Voir tout</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
            @foreach($resources as $resource)
                @php
                    $statusMap = [
                        'available'   => ['label' => '● Disponible',     'color' => '#2ecc71', 'bg' => 'rgba(46,204,113,0.1)'],
                        'maintenance' => ['label' => '🔧 En Maintenance', 'color' => '#e67e22', 'bg' => 'rgba(255,165,0,0.1)'],
                        'occupied'    => ['label' => '🔒 Occupée',        'color' => '#3498db', 'bg' => 'rgba(52,152,219,0.1)'],
                        'inactive'    => ['label' => '⛔ Hors Service',   'color' => '#e74c3c', 'bg' => 'rgba(231,76,60,0.1)'],
                    ];
                    $s = $statusMap[$resource->status] ?? $statusMap['inactive'];

                    // Encode all data needed for the modal as JSON
                    $specs = $resource->specifications ? collect($resource->specifications)->map(fn($v,$k) => ['k'=>$k,'v'=>$v])->values()->toJson() : '[]';
                    // Manual unavailability periods
                    $unavailPeriods = $resource->unavailabilityPeriods->map(fn($p) => [
                        'type'   => $p->type,
                        'reason' => $p->reason,
                        'start'  => $p->start_date->format('d/m/Y H:i'),
                        'end'    => $p->end_date->format('d/m/Y H:i'),
                        'status' => $p->start_date > now() ? 'planned' : ($p->end_date >= now() ? 'active' : 'past'),
                    ]);

                    // Active/pending/approved reservations also count as occupied periods
                    $resvPeriods = $resource->reservations
                        ->whereIn('status', ['pending', 'approved', 'active'])
                        ->map(fn($r) => [
                            'type'   => 'réservation',
                            'reason' => match($r->status) {
                                'pending'  => '⏳ Réservation en attente de validation',
                                'approved' => '✅ Réservation approuvée',
                                'active'   => '🔵 Réservation en cours',
                                default    => 'Réservation',
                            },
                            'start'  => \Carbon\Carbon::parse($r->start_date)->format('d/m/Y H:i'),
                            'end'    => \Carbon\Carbon::parse($r->end_date)->format('d/m/Y H:i'),
                            'status' => \Carbon\Carbon::parse($r->start_date) > now() ? 'planned'
                                      : (\Carbon\Carbon::parse($r->end_date) >= now() ? 'active' : 'past'),
                        ]);

                    $periods = collect(array_merge($unavailPeriods->toArray(), $resvPeriods->toArray()))
                        ->sortByDesc('start')
                        ->values()
                        ->toJson();

                    $history = $resource->reservations->whereIn('status',['approved','active','completed'])->map(fn($r) => [
                        'start'  => \Carbon\Carbon::parse($r->start_date)->format('d/m/Y H:i'),
                        'end'    => \Carbon\Carbon::parse($r->end_date)->format('d/m/Y H:i'),
                        'status' => $r->status,
                    ])->values()->toJson();
                    $notes = (Auth::check() && in_array(Auth::user()->role,['manager','admin'])) ? $resource->internal_notes : null;
                @endphp

                {{-- Resource Card --}}
                <div class="card" style="display: flex; flex-direction: column; transition: transform 0.2s; border: 1px solid var(--border); background: var(--bg-surface); border-radius: 12px; padding: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <span style="font-size: 0.75rem; font-weight: 800; color: var(--primary); text-transform: uppercase;">{{ $resource->category }}</span>
                        <span style="font-size: 0.75rem; color: {{ $s['color'] }}; font-weight: bold; background: {{ $s['bg'] }}; padding: 2px 8px; border-radius: 10px;">{{ $s['label'] }}</span>
                    </div>
                    
                    <h3 style="margin: 0 0 10px 0; font-size: 1.3rem; color: var(--text-primary);">{{ $resource->label }}</h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); flex-grow: 1; margin-bottom: 20px;">
                        {{ Str::limit($resource->description ?? 'Aucune description', 80) }}
                    </p>
                    
                    <div style="padding-top: 15px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; gap: 8px; flex-wrap:wrap;">
                        <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">📍 {{ $resource->location }}</span>
                        
                        <div style="display:flex; gap:8px; align-items:center;">
                            {{-- Details trigger button --}}
                            <button class="btn-details"
                                    onclick="openModal(this)"
                                    data-id="{{ $resource->id }}"
                                    data-label="{{ e($resource->label) }}"
                                    data-category="{{ e($resource->category) }}"
                                    data-location="{{ e($resource->location) }}"
                                    data-description="{{ e($resource->description) }}"
                                    data-status="{{ $resource->status }}"
                                    data-status-label="{{ $s['label'] }}"
                                    data-status-color="{{ $s['color'] }}"
                                    data-specs='{{ $specs }}'
                                    data-periods='{{ $periods }}'
                                    data-history='{{ $history }}'
                                    data-reserveurl="{{ $resource->status == 'available' ? route('reservations.create', ['resource_id' => $resource->id]) : '' }}"
                                    data-canreserve="{{ (!Auth::check() || Auth::user()->role === 'user') && $resource->status === 'available' ? '1' : '0' }}">
                                🔍 Détails
                            </button>

                            @if(!Auth::check() || Auth::user()->role === 'user')
                                @if($resource->status == 'available')
                                    <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" class="btn btn-primary" style="padding: 6px 14px; font-size: 0.9rem;">Réserver</a>
                                @else
                                    <button disabled style="padding: 6px 14px; font-size: 0.85rem; background: #f8f9fa; color: #636e72; border: 1px solid #dfe6e9; border-radius: 8px; cursor: not-allowed; font-weight: bold;">
                                        {{ $resource->status == 'maintenance' ? '🔧 Maintenance' : '⛔ Hors Service' }}
                                    </button>
                                @endif
                            @else
                                <span style="padding: 6px 12px; font-size: 0.8rem; background: var(--bg-background); color: var(--text-muted); border: 1px solid var(--border); border-radius: 8px;">
                                    Utilisateurs uniquement
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ============================================================
     MODAL OVERLAY
     ============================================================ --}}
<div id="resourceModal" class="modal-overlay" onclick="if(event.target===this) closeModal()">
    <div class="modal-box">
        {{-- Header --}}
        <div class="modal-header">
            <div>
                <span id="mCategory" class="modal-cat"></span>
                <h2 id="mLabel" class="modal-title"></h2>
                <p id="mLocation" class="modal-loc"></p>
            </div>
            <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                <span id="mStatusBadge" class="modal-badge"></span>
            </div>
        </div>
        <p id="mDescription" class="modal-desc"></p>
        <button class="modal-close" onclick="closeModal()">✕</button>

        {{-- Tabs --}}
        <div class="modal-tabs">
            <button class="tab-btn active" onclick="switchTab('specs', this)">🔧 Caractéristiques</button>
            <button class="tab-btn" onclick="switchTab('avail', this)">📊 Disponibilité</button>
            <button class="tab-btn" onclick="switchTab('periods', this)">🚫 Indisponibilités</button>
            @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'manager']))
            <button class="tab-btn" onclick="switchTab('history', this)">📜 Historique</button>
            @endif
        </div>

        {{-- Tab: Specs --}}
        <div id="tab-specs" class="tab-content active">
            <div id="mSpecs" class="specs-grid"></div>
        </div>

        {{-- Tab: Availability --}}
        <div id="tab-avail" class="tab-content">
            <div class="specs-grid">
                <div class="spec-card"><div class="spec-key">Statut</div><div id="mAvailStatus" class="spec-val"></div></div>
                <div class="spec-card"><div class="spec-key">Période bloquée</div><div id="mAvailBlocked" class="spec-val"></div></div>
            </div>
        </div>

        {{-- Tab: Periods --}}
        <div id="tab-periods" class="tab-content">
            <div id="mPeriods"></div>
        </div>

        {{-- Tab: History (admin & manager only) --}}
        @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'manager']))
        <div id="tab-history" class="tab-content">
            <div id="mHistory"></div>
        </div>
        @endif

        {{-- Footer --}}
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal()">Fermer</button>
            <a id="mReserveBtn" href="#" class="btn btn-primary" style="display:none;">📅 Réserver</a>
        </div>
    </div>
</div>

<style>
/* Cards */
.card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); border-color: var(--primary); }
.btn-details {
    font-size: 0.82rem; font-weight: 600; color: var(--primary);
    background: transparent; border: 1px solid var(--primary);
    border-radius: 8px; padding: 6px 12px; cursor: pointer;
    transition: all 0.2s;
}
.btn-details:hover { background: var(--primary); color: white; }

/* Modal Overlay */
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.55); backdrop-filter: blur(4px);
    z-index: 9999; align-items: center; justify-content: center;
    padding: 20px;
}
.modal-overlay.open { display: flex; }

/* Modal Box */
.modal-box {
    background: var(--bg-surface); border-radius: 20px;
    width: 100%; max-width: 740px; max-height: 88vh;
    overflow-y: auto; position: relative;
    box-shadow: 0 24px 60px rgba(0,0,0,0.3);
    animation: modalIn 0.25s ease;
}
@keyframes modalIn {
    from { opacity: 0; transform: translateY(24px) scale(0.97); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* Modal Header */
.modal-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 28px 28px 0; gap: 16px; flex-wrap: wrap;
}
.modal-cat { font-size: 0.72rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; }
.modal-title { margin: 6px 0 4px; font-size: 1.5rem; font-weight: 800; color: var(--text-primary); }
.modal-loc { margin: 0; font-size: 0.85rem; color: var(--text-muted); }
.modal-desc { margin: 14px 28px 0; font-size: 0.9rem; color: var(--text-muted); line-height: 1.6; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
.modal-badge { font-size: 0.78rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; }
.modal-badge-outline { font-size: 0.78rem; font-weight: 700; padding: 3px 12px; border-radius: 20px; border: 1px solid currentColor; }
.modal-close {
    position: absolute; top: 16px; right: 16px;
    background: var(--bg-background); border: 1px solid var(--border);
    border-radius: 50%; width: 34px; height: 34px; font-size: 1rem;
    cursor: pointer; color: var(--text-muted); transition: all 0.2s;
}
.modal-close:hover { background: #e74c3c; color: white; border-color: #e74c3c; }

/* Tabs */
.modal-tabs {
    display: flex; gap: 4px; padding: 16px 28px 0; overflow-x: auto;
    border-bottom: 1px solid var(--border); margin-bottom: 0;
}
.tab-btn {
    background: none; border: none; border-bottom: 3px solid transparent;
    padding: 8px 14px 12px; font-size: 0.85rem; font-weight: 600;
    color: var(--text-muted); cursor: pointer; white-space: nowrap;
    transition: all 0.2s;
}
.tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
.tab-btn:hover { color: var(--primary); }

/* Tab Content */
.tab-content { display: none; padding: 20px 28px; }
.tab-content.active { display: block; }

/* Specs Grid */
.specs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; }
.spec-card { background: var(--bg-background); border-radius: 10px; padding: 14px; }
.spec-key { font-size: 0.7rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.spec-val { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); }

/* Period / History table */
.mini-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
.mini-table th { padding: 8px 10px; text-align: left; color: var(--text-muted); font-weight: 700; font-size: 0.75rem; text-transform: uppercase; border-bottom: 2px solid var(--border); }
.mini-table td { padding: 9px 10px; border-bottom: 1px solid var(--border); color: var(--text-primary); }
.empty-msg { color: var(--text-muted); font-style: italic; padding: 8px 0; }

/* Footer */
.modal-footer { padding: 16px 28px 24px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border); }
</style>

<script>
function openModal(btn) {
    const d = btn.dataset;
    const modal = document.getElementById('resourceModal');

    // Header
    document.getElementById('mCategory').textContent    = d.category;
    document.getElementById('mLabel').textContent       = d.label;
    document.getElementById('mLocation').textContent    = '📍 ' + d.location;
    document.getElementById('mDescription').textContent = d.description || '';

    // Badges
    const sb = document.getElementById('mStatusBadge');
    sb.textContent = d.statusLabel;
    sb.style.background = d.statusColor + '22';
    sb.style.color = d.statusColor;

    // Specs tab
    const specs = JSON.parse(d.specs || '[]');
    const specsEl = document.getElementById('mSpecs');
    if (specs.length === 0) {
        specsEl.innerHTML = '<p class="empty-msg">Aucune spécification.</p>';
    } else {
        specsEl.innerHTML = specs.map(s =>
            `<div class="spec-card"><div class="spec-key">${s.k}</div><div class="spec-val">${s.v}</div></div>`
        ).join('');
    }

    // Availability tab
    document.getElementById('mAvailStatus').innerHTML = `<span style="color:${d.statusColor};font-weight:700;">${d.statusLabel}</span>`;

    const periods = JSON.parse(d.periods || '[]');
    const isBlocked = periods.some(p => p.status === 'active');
    document.getElementById('mAvailBlocked').innerHTML = isBlocked
        ? '<span style="color:#e74c3c;font-weight:700;">Oui</span>'
        : '<span style="color:#2ecc71;font-weight:700;">Non</span>';

    // Periods tab
    const periodsEl = document.getElementById('mPeriods');
    const typeIcons = { maintenance: '🔧', panne: '⚡', 'réservé': '📌', autre: '📋' };
    const pStatusMap = {
        planned: { label: '⏳ Planifiée', color: '#3498db' },
        active:  { label: '🔴 En cours',  color: '#e74c3c' },
        past:    { label: '✅ Terminée',  color: '#2ecc71' },
    };
    if (periods.length === 0) {
        periodsEl.innerHTML = '<p class="empty-msg">Aucune période d\'indisponibilité enregistrée.</p>';
    } else {
        periodsEl.innerHTML = `<table class="mini-table">
            <thead><tr><th>Type</th><th>Motif</th><th>Début</th><th>Fin</th><th>Statut</th></tr></thead>
            <tbody>
            ${periods.map(p => {
                const ps = pStatusMap[p.status] || { label: p.status, color: '#aaa' };
                return `<tr>
                    <td>${typeIcons[p.type] || '📋'} ${p.type}</td>
                    <td style="max-width:180px;word-break:break-word;">${p.reason}</td>
                    <td style="white-space:nowrap;">${p.start}</td>
                    <td style="white-space:nowrap;">${p.end}</td>
                    <td><span style="color:${ps.color};font-weight:700;font-size:0.82rem;">${ps.label}</span></td>
                </tr>`;
            }).join('')}
            </tbody></table>`;
    }

    // History tab (admin & manager only)
    const histEl = document.getElementById('mHistory');
    if (histEl) {
        const history = JSON.parse(d.history || '[]');
        const rStatusMap = {
            approved:  { label: '✅ Approuvée', color: '#2ecc71' },
            active:    { label: '🔵 Active',    color: '#3498db' },
            completed: { label: '✔ Terminée',  color: '#95a5a6' },
        };
        if (history.length === 0) {
            histEl.innerHTML = '<p class="empty-msg">Aucune réservation enregistrée.</p>';
        } else {
            histEl.innerHTML = `<table class="mini-table">
                <thead><tr><th>Début</th><th>Fin</th><th>Statut</th></tr></thead>
                <tbody>
                ${history.map(r => {
                    const rs = rStatusMap[r.status] || { label: r.status, color: '#aaa' };
                    return `<tr>
                        <td>${r.start}</td><td>${r.end}</td>
                        <td><span style="color:${rs.color};font-weight:700;font-size:0.82rem;">${rs.label}</span></td>
                    </tr>`;
                }).join('')}
                </tbody></table>`;
        }
    }

    // Reserve button
    const reserveBtn = document.getElementById('mReserveBtn');
    if (d.canreserve === '1' && d.reserveurl) {
        reserveBtn.href = d.reserveurl;
        reserveBtn.style.display = 'inline-block';
    } else {
        reserveBtn.style.display = 'none';
    }

    // Reset to first tab
    switchTab('specs', document.querySelector('.tab-btn'));

    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('resourceModal').classList.remove('open');
    document.body.style.overflow = '';
}

function switchTab(name, btn) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}

// Close on Escape
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endsection