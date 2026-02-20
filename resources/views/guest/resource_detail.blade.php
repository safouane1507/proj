@extends('layouts.app')

@section('content')
<div style="max-width: 960px; margin: 0 auto; padding: 40px 20px;">

    {{-- Breadcrumb --}}
    <a href="{{ route('resources.all') }}" style="display:inline-flex; align-items:center; gap:6px; color:var(--primary); font-weight:600; text-decoration:none; margin-bottom:28px;">
        ← Retour au catalogue
    </a>

    {{-- Flash Message --}}
    @if(session('success'))
        <div style="background:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:10px; padding:12px 20px; margin-bottom:24px;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- ===== HEADER ===== --}}
    <div style="background:var(--bg-surface); border:1px solid var(--border); border-radius:16px; padding:32px; margin-bottom:24px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
            <div>
                <span style="font-size:0.75rem; font-weight:800; color:var(--primary); text-transform:uppercase; letter-spacing:1px;">
                    {{ $resource->category }}
                </span>
                <h1 style="margin:8px 0 6px; font-size:2rem; font-weight:800; color:var(--text-primary);">
                    {{ $resource->label }}
                </h1>
                <p style="color:var(--text-muted); margin:0; font-size:0.95rem;">
                    📍 {{ $resource->location }}
                    @if($resource->manager)
                        &nbsp;·&nbsp; 👤 {{ $resource->manager->name }}
                    @endif
                </p>
            </div>

            {{-- Status + Condition badges --}}
            <div style="display:flex; flex-direction:column; align-items:flex-end; gap:8px;">
                @php
                    $statusMap = [
                        'available'   => ['label' => '● Disponible',      'bg' => 'rgba(46,204,113,0.1)',  'color' => '#2ecc71'],
                        'maintenance' => ['label' => '🔧 En Maintenance',  'bg' => 'rgba(255,165,0,0.1)',   'color' => '#e67e22'],
                        'occupied'    => ['label' => '🔒 Occupée',         'bg' => 'rgba(52,152,219,0.1)',  'color' => '#3498db'],
                        'inactive'    => ['label' => '⛔ Hors Service',    'bg' => 'rgba(231,76,60,0.1)',   'color' => '#e74c3c'],
                    ];
                    $s = $statusMap[$resource->status] ?? $statusMap['inactive'];
                    $cond = $resource->conditionLabel();
                @endphp
                <span style="font-size:0.8rem; font-weight:700; background:{{ $s['bg'] }}; color:{{ $s['color'] }}; padding:4px 14px; border-radius:20px;">
                    {{ $s['label'] }}
                </span>
                <span style="font-size:0.8rem; font-weight:700; color:{{ $cond['color'] }}; background:rgba(0,0,0,0.04); padding:4px 14px; border-radius:20px; border:1px solid {{ $cond['color'] }}44;">
                    {{ $cond['label'] }}
                </span>
            </div>
        </div>

        {{-- Description --}}
        @if($resource->description)
            <p style="margin-top:20px; color:var(--text-muted); font-size:1rem; line-height:1.6; border-top:1px solid var(--border); padding-top:16px;">
                {{ $resource->description }}
            </p>
        @endif

        {{-- Reserve button --}}
        @if(!Auth::check() || Auth::user()->role === 'user')
            <div style="margin-top:20px;">
                @if($resource->status == 'available' && !$resource->isCurrentlyUnavailable())
                    <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}"
                       class="btn btn-primary">
                        📅 Réserver cette ressource
                    </a>
                @else
                    <button disabled style="padding:10px 24px; background:#f0f2f5; color:#999; border:1px solid #ddd; border-radius:8px; font-weight:700; cursor:not-allowed;">
                        Non disponible à la réservation
                    </button>
                @endif
            </div>
        @endif
    </div>

    {{-- ===== SECTION 1: CARACTÉRISTIQUES TECHNIQUES ===== --}}
    <div style="background:var(--bg-surface); border:1px solid var(--border); border-radius:16px; padding:28px; margin-bottom:24px;">
        <h2 style="margin:0 0 20px; font-size:1.1rem; font-weight:700; color:var(--text-primary); display:flex; align-items:center; gap:8px;">
            🔧 Caractéristiques Techniques
        </h2>
        @if($resource->specifications && count($resource->specifications) > 0)
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:14px;">
                @foreach($resource->specifications as $key => $value)
                    <div style="background:var(--bg-background); border-radius:10px; padding:14px 18px;">
                        <div style="font-size:0.72rem; font-weight:700; color:var(--primary); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">
                            {{ $key }}
                        </div>
                        <div style="font-size:1rem; font-weight:700; color:var(--text-primary);">
                            {{ $value }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color:var(--text-muted);">Aucune spécification renseignée.</p>
        @endif
    </div>

    {{-- ===== SECTION 2: DISPONIBILITÉ ===== --}}
    <div style="background:var(--bg-surface); border:1px solid var(--border); border-radius:16px; padding:28px; margin-bottom:24px;">
        <h2 style="margin:0 0 20px; font-size:1.1rem; font-weight:700; color:var(--text-primary); display:flex; align-items:center; gap:8px;">
            📊 Disponibilité
        </h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:14px;">
            <div style="background:var(--bg-background); border-radius:10px; padding:14px 18px;">
                <div style="font-size:0.72rem; font-weight:700; color:var(--primary); text-transform:uppercase; margin-bottom:4px;">Statut</div>
                <div style="font-size:0.95rem; font-weight:700; color:{{ $s['color'] }};">{{ $s['label'] }}</div>
            </div>
            <div style="background:var(--bg-background); border-radius:10px; padding:14px 18px;">
                <div style="font-size:0.72rem; font-weight:700; color:var(--primary); text-transform:uppercase; margin-bottom:4px;">État</div>
                <div style="font-size:0.95rem; font-weight:700; color:{{ $cond['color'] }};">{{ $cond['label'] }}</div>
            </div>
            <div style="background:var(--bg-background); border-radius:10px; padding:14px 18px;">
                <div style="font-size:0.72rem; font-weight:700; color:var(--primary); text-transform:uppercase; margin-bottom:4px;">Réservations actives</div>
                <div style="font-size:0.95rem; font-weight:700; color:var(--text-primary);">
                    {{ $resource->reservations->whereIn('status', ['approved','active'])->count() }}
                </div>
            </div>
            <div style="background:var(--bg-background); border-radius:10px; padding:14px 18px;">
                <div style="font-size:0.72rem; font-weight:700; color:var(--primary); text-transform:uppercase; margin-bottom:4px;">Période bloquée</div>
                <div style="font-size:0.95rem; font-weight:700; color:{{ $resource->isCurrentlyUnavailable() ? '#e74c3c' : '#2ecc71' }};">
                    {{ $resource->isCurrentlyUnavailable() ? 'Oui' : 'Non' }}
                </div>
            </div>
        </div>

        {{-- Internal notes for managers/admins only --}}
        @if(Auth::check() && in_array(Auth::user()->role, ['manager', 'admin']) && $resource->internal_notes)
            <div style="margin-top:18px; background:rgba(255,193,7,0.08); border:1px solid rgba(255,193,7,0.3); border-radius:10px; padding:14px 18px;">
                <div style="font-size:0.72rem; font-weight:700; color:#e67e22; text-transform:uppercase; margin-bottom:4px;">🔒 Notes internes</div>
                <div style="color:var(--text-primary); font-size:0.9rem;">{{ $resource->internal_notes }}</div>
            </div>
        @endif
    </div>

    {{-- ===== SECTION 3: PÉRIODES D'INDISPONIBILITÉ ===== --}}
    <div style="background:var(--bg-surface); border:1px solid var(--border); border-radius:16px; padding:28px; margin-bottom:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
            <h2 style="margin:0; font-size:1.1rem; font-weight:700; color:var(--text-primary);">
                🚫 Périodes d'Indisponibilité
            </h2>
            {{-- Manager: form to add a new period --}}
            @if(Auth::check() && in_array(Auth::user()->role, ['manager', 'admin']))
                <button onclick="document.getElementById('addPeriodForm').classList.toggle('hidden')"
                        class="btn btn-outline" style="font-size:0.85rem; padding:6px 14px;">
                    + Ajouter une période
                </button>
            @endif
        </div>

        {{-- Add Period Form (hidden by default) --}}
        @if(Auth::check() && in_array(Auth::user()->role, ['manager', 'admin']))
            <div id="addPeriodForm" class="hidden" style="background:var(--bg-background); border-radius:12px; padding:20px; margin-bottom:20px; border:1px solid var(--border);">
                <h3 style="margin:0 0 16px; font-size:0.95rem; font-weight:700; color:var(--text-primary);">Nouvelle période d'indisponibilité</h3>
                <form action="{{ route('manager.unavailability.add', $resource->id) }}" method="POST">
                    @csrf
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                        <div>
                            <label style="font-size:0.8rem; font-weight:700; color:var(--text-muted); display:block; margin-bottom:4px;">Date début *</label>
                            <input type="datetime-local" name="start_date" required
                                   style="width:100%; padding:8px 12px; border:1px solid var(--border); border-radius:8px; background:var(--bg-surface); color:var(--text-primary);">
                        </div>
                        <div>
                            <label style="font-size:0.8rem; font-weight:700; color:var(--text-muted); display:block; margin-bottom:4px;">Date fin *</label>
                            <input type="datetime-local" name="end_date" required
                                   style="width:100%; padding:8px 12px; border:1px solid var(--border); border-radius:8px; background:var(--bg-surface); color:var(--text-primary);">
                        </div>
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="font-size:0.8rem; font-weight:700; color:var(--text-muted); display:block; margin-bottom:4px;">Type *</label>
                        <select name="type" required style="width:100%; padding:8px 12px; border:1px solid var(--border); border-radius:8px; background:var(--bg-surface); color:var(--text-primary);">
                            <option value="maintenance">🔧 Maintenance</option>
                            <option value="panne">⚡ Panne</option>
                            <option value="réservé">📌 Réservé (usage exclusif)</option>
                            <option value="autre">📋 Autre</option>
                        </select>
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="font-size:0.8rem; font-weight:700; color:var(--text-muted); display:block; margin-bottom:4px;">Motif *</label>
                        <textarea name="reason" required rows="2"
                                  style="width:100%; padding:8px 12px; border:1px solid var(--border); border-radius:8px; background:var(--bg-surface); color:var(--text-primary); resize:vertical;"
                                  placeholder="Décrivez la raison de l'indisponibilité..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="font-size:0.85rem;">Enregistrer</button>
                </form>
            </div>
        @endif

        {{-- Table of periods --}}
        @if($resource->unavailabilityPeriods->isEmpty())
            <p style="color:var(--text-muted); font-style:italic;">Aucune période d'indisponibilité enregistrée.</p>
        @else
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
                    <thead>
                        <tr style="border-bottom:2px solid var(--border);">
                            <th style="padding:8px 12px; text-align:left; color:var(--text-muted); font-weight:700; font-size:0.8rem; text-transform:uppercase;">Type</th>
                            <th style="padding:8px 12px; text-align:left; color:var(--text-muted); font-weight:700; font-size:0.8rem; text-transform:uppercase;">Motif</th>
                            <th style="padding:8px 12px; text-align:left; color:var(--text-muted); font-weight:700; font-size:0.8rem; text-transform:uppercase;">Début</th>
                            <th style="padding:8px 12px; text-align:left; color:var(--text-muted); font-weight:700; font-size:0.8rem; text-transform:uppercase;">Fin</th>
                            <th style="padding:8px 12px; text-align:left; color:var(--text-muted); font-weight:700; font-size:0.8rem; text-transform:uppercase;">Statut</th>
                            @if(Auth::check() && in_array(Auth::user()->role, ['manager', 'admin']))
                                <th style="padding:8px 12px;"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resource->unavailabilityPeriods as $period)
                            @php
                                $now = now();
                                if ($period->start_date > $now) {
                                    $pStatus = ['label' => '⏳ Planifiée', 'color' => '#3498db'];
                                } elseif ($period->end_date >= $now) {
                                    $pStatus = ['label' => '🔴 En cours',  'color' => '#e74c3c'];
                                } else {
                                    $pStatus = ['label' => '✅ Terminée',  'color' => '#2ecc71'];
                                }
                                $typeIcons = ['maintenance' => '🔧', 'panne' => '⚡', 'réservé' => '📌', 'autre' => '📋'];
                            @endphp
                            <tr style="border-bottom:1px solid var(--border);">
                                <td style="padding:10px 12px; color:var(--text-primary);">
                                    {{ $typeIcons[$period->type] ?? '📋' }} {{ ucfirst($period->type) }}
                                </td>
                                <td style="padding:10px 12px; color:var(--text-muted);">{{ $period->reason }}</td>
                                <td style="padding:10px 12px; color:var(--text-primary);">{{ $period->start_date->format('d/m/Y H:i') }}</td>
                                <td style="padding:10px 12px; color:var(--text-primary);">{{ $period->end_date->format('d/m/Y H:i') }}</td>
                                <td style="padding:10px 12px;">
                                    <span style="font-size:0.8rem; font-weight:700; color:{{ $pStatus['color'] }};">{{ $pStatus['label'] }}</span>
                                </td>
                                @if(Auth::check() && in_array(Auth::user()->role, ['manager', 'admin']))
                                    <td style="padding:10px 12px; text-align:right;">
                                        <form action="{{ route('manager.unavailability.remove', $period->id) }}" method="POST"
                                              onsubmit="return confirm('Supprimer cette période ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background:none; border:none; color:#e74c3c; cursor:pointer; font-size:0.85rem; font-weight:700;">
                                                🗑 Supprimer
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ===== SECTION 4: HISTORIQUE D'UTILISATION ===== --}}
    <div style="background:var(--bg-surface); border:1px solid var(--border); border-radius:16px; padding:28px; margin-bottom:24px;">
        <h2 style="margin:0 0 20px; font-size:1.1rem; font-weight:700; color:var(--text-primary);">
            📜 Historique d'Utilisation
        </h2>
        @php
            $historyReservations = $resource->reservations
                ->whereIn('status', ['approved', 'active', 'completed'])
                ->sortByDesc('start_date');
        @endphp
        @if($historyReservations->isEmpty())
            <p style="color:var(--text-muted); font-style:italic;">Aucune réservation enregistrée pour cette ressource.</p>
        @else
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
                    <thead>
                        <tr style="border-bottom:2px solid var(--border);">
                            @if(Auth::check() && in_array(Auth::user()->role, ['manager', 'admin']))
                                <th style="padding:8px 12px; text-align:left; color:var(--text-muted); font-weight:700; font-size:0.8rem; text-transform:uppercase;">Utilisateur</th>
                            @endif
                            <th style="padding:8px 12px; text-align:left; color:var(--text-muted); font-weight:700; font-size:0.8rem; text-transform:uppercase;">Début</th>
                            <th style="padding:8px 12px; text-align:left; color:var(--text-muted); font-weight:700; font-size:0.8rem; text-transform:uppercase;">Fin</th>
                            <th style="padding:8px 12px; text-align:left; color:var(--text-muted); font-weight:700; font-size:0.8rem; text-transform:uppercase;">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historyReservations as $res)
                            @php
                                $rStatusMap = [
                                    'approved'  => ['label' => '✅ Approuvée', 'color' => '#2ecc71'],
                                    'active'    => ['label' => '🔵 Active',    'color' => '#3498db'],
                                    'completed' => ['label' => '✔ Terminée',  'color' => '#95a5a6'],
                                ];
                                $rs = $rStatusMap[$res->status] ?? ['label' => ucfirst($res->status), 'color' => '#aaa'];
                            @endphp
                            <tr style="border-bottom:1px solid var(--border);">
                                @if(Auth::check() && in_array(Auth::user()->role, ['manager', 'admin']))
                                    <td style="padding:10px 12px; color:var(--text-primary);">
                                        {{ $res->user->name ?? '—' }}
                                    </td>
                                @endif
                                <td style="padding:10px 12px; color:var(--text-primary);">{{ \Carbon\Carbon::parse($res->start_date)->format('d/m/Y H:i') }}</td>
                                <td style="padding:10px 12px; color:var(--text-primary);">{{ \Carbon\Carbon::parse($res->end_date)->format('d/m/Y H:i') }}</td>
                                <td style="padding:10px 12px;">
                                    <span style="font-size:0.8rem; font-weight:700; color:{{ $rs['color'] }};">{{ $rs['label'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

<style>
.hidden { display: none !important; }
</style>
@endsection