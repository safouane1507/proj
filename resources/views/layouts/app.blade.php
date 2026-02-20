<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicone.ico') }}">
    <title>DataCenter Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- VARIABLES DE COULEURS --- */
        :root {
            /* ☀️ Light Mode */
            --bg-background: #F6F9F9;
            --bg-surface: #FFFFFF;
            --primary: #0FA3A3;
            --secondary: #5FC9C4;
            --text-primary: #1C2F30;
            --text-muted: #647F80;
            --border: #DCECEC;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --radius: 12px;
            /* Couleurs pour les tables et lea inputs en Light Mode */
            --table-header: #f8f9fa;
            --input-bg: #ffffff;
        }

        /* 🌙 Dark Mode */
        body.dark {
            --bg-background: #081B1C;
            --bg-surface: #102C2D;
            --primary: #4DB6AC;
            --secondary: #80CBC4;
            --text-primary: #E0F2F1;
            --text-muted: #B2DFDB;
            --border: #294C4C;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
            /* Couleurs spécifiques pour Dark Mode */
            --table-header: #153536; /* Plus sombre que bg-surface */
            --input-bg: #081B1C;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-background);
            color: var(--text-primary);
            margin: 0;
            transition: all 0.3s ease;
        }

        /* --- CORRECTION GLOBALE DES FORMULAIRES (Inputs, Selects) --- */
        input, select, textarea {
            background-color: var(--input-bg) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--border) !important;
            border-radius: 6px;
        }
        /* Correction spécifique pour les options des menus déroulants */
        select option {
            background-color: var(--bg-surface);
            color: var(--text-primary);
        }
        /* --- CORRECTION GLOBALE DES TABLEAUX (Admin/Manager) --- */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        /* En-tête de tableau (La bande blanche qui posait problème) */
        thead tr, th {
            background-color: var(--table-header) !important;
            color: var(--text-primary) !important;
        }

        /* Lignes du tableau */
        tbody tr {
            background-color: var(--bg-surface) !important;
            color: var(--text-primary) !important;
            border-bottom: 1px solid var(--border);
        }

        /* --- STYLES GENERAUX --- */
        header {
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border);
            padding: 0 5%;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow);
        }

        .logo { 
            font-size: 1.5rem; 
            font-weight: 800; 
            color: var(--primary); 
            text-decoration: none;
        }
        .logo span { color: var(--text-primary); }

        nav { display: flex; align-items: center; gap: 15px; }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
            cursor: pointer;
        }
        .nav-link:hover { color: var(--primary); }

        /* --- MENU DEROULANT --- */
        .dropdown-container { position: relative; height: 70px; display: flex; align-items: center; }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 65px;
            left: -20px;
            width: 240px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 10px;
            z-index: 1001;
            content: "";
        }

        .dropdown-menu::before {
            content: "";
            position: absolute;
            top: -15px; /* Comble le vide de 15px au-dessus */
            left: 0;
            width: 100%;
            height: 15px;
            background: transparent; /* Invisible mais détecte la souris */
        }

        .dropdown-container:hover .dropdown-menu { display: block; animation: fadeIn 0.2s ease-out; }
        
        .dropdown-item {
            display: flex; align-items: center; padding: 10px 15px;
            color: var(--text-muted); text-decoration: none; border-radius: 8px;
        }
        .dropdown-item:hover { background: var(--bg-background); color: var(--primary); }

        /* --- BOUTONS AND CARDS    THE PROBLEM IS HNA --- */
        .btn { padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; cursor: pointer; border: none; }
        .btn-outline { border: 1px solid var(--border); color: var(--text-primary); background: transparent; }
        .btn-primary { background: var(--primary) !important; color: #fff !important; border: none; }
        .card { background: var(--bg-surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; }

        /* --- SALUTATION --- */
        .user-greeting {
            display: flex; align-items: center; gap: 10px;
            padding: 5px 12px; border-radius: 30px;
            background: var(--bg-background); border: 1px solid var(--border);
        }
        .user-avatar {
            width: 32px; height: 32px; background: var(--secondary); color: white;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: bold;
        }

        /* --- TOGGLE SWITCH --- */
        #theme-toggle {
            background: var(--bg-background);
            border: 1px solid var(--border);
            color: var(--text-primary);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="light"> <header>
    <a href="/" class="logo">Data<span>Center</span></a>

    <nav>
        <div class="nav-links" style="display: flex; align-items: center; gap: 20px;">
            <div class="dropdown-container">
                <a href="{{ route('resources.all') }}" class="nav-link">Ressources ▾</a>
                <div class="dropdown-menu">
                    <div style="padding: 0 15px 5px; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Filtrer par</div>
                    <a href="{{ route('resources.all', ['cat' => 'Serveur Physique']) }}" class="dropdown-item">🖥️ Serveurs</a>
                    <a href="{{ route('resources.all', ['cat' => 'Machine Virtuelle']) }}" class="dropdown-item">☁️ Virtuel (VM)</a>
                    <a href="{{ route('resources.all', ['cat' => 'Stockage']) }}" class="dropdown-item">💾 Stockage</a>
                    <a href="{{ route('resources.all', ['cat' => 'Réseau']) }}" class="dropdown-item">🌐 Réseau</a>
                </div>
            </div>

            @if(request()->routeIs('home'))
                <a href="#features" class="nav-link" style="color: var(--text-muted); text-decoration: none; font-weight: 500; transition: color 0.3s;">
                    Features
                </a>
                <a href="#definitions" class="nav-link" style="color: var(--text-muted); text-decoration: none; font-weight: 500; transition: color 0.3s;">
                    Définitions
                </a>
                <a href="#contact" class="nav-link" style="color: var(--text-muted); text-decoration: none; font-weight: 500; transition: color 0.3s;">
                    Contact Us
                </a>
            @endif

        </div>

        <button id="theme-toggle" title="Changer de mode">
            <span id="theme-toggle-icon">🌙</span>
        </button>

        @guest
            <a href="{{ route('login') }}" class="btn btn-outline">Connexion</a>
            <a href="{{ route('register.request') }}" class="btn btn-primary">Inscription</a>
        @else
            @php
                $roleTitle = "M.";
                if(Auth::user()->role === 'manager') $roleTitle = "Ing.";
                if(Auth::user()->role === 'admin') $roleTitle = "Admin";
            @endphp
            
            <div class="user-greeting">
                <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div style="font-size: 0.85rem; line-height: 1.2;">
                    <span style="color: var(--text-muted); font-size: 0.8em;">Bonjour,</span><br>
                    <strong style="color: var(--text-primary);">{{ $roleTitle }} {{ Auth::user()->name }}</strong>
                </div>
            </div>

            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : (Auth::user()->role === 'manager' ? route('manager.dashboard') : route('user.dashboard')) }}" class="nav-link">
                Dashboard
            </a>

            {{-- here  IS OTHER PTOBLEM BUTTON OF THE SETTINGS --}}
            <a href="{{ route('profile.settings') }}" class="nav-link" style="margin-right: 15px;">⚙️ Paramètres</a> 
            {{-- here --}}

            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="nav-link" style="background:none; border:none; font-size:1.2rem;" title="Déconnexion">⏻</button>
            </form>
        @endguest
    </nav>
</header>

{{-- ============================================================
     NOTIFICATION TOAST — shown once per login session
     ============================================================ --}}
@auth
@php
    $unreadNotifs = \App\Models\Notification::where('user_id', Auth::id())
        ->where('is_read', false)
        ->latest()
        ->get();
@endphp
@if($unreadNotifs->isNotEmpty())
<div id="notif-toast" style="
    position: fixed; top: 80px; right: 20px; z-index: 99999;
    width: 360px; max-width: 95vw;
    background: var(--bg-surface);
    border: 1px solid var(--border);
    border-left: 5px solid var(--primary);
    border-radius: 14px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.18);
    animation: notifSlideIn 0.4s cubic-bezier(.17,.67,.35,1.1);
    overflow: hidden;
">
    {{-- Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center;
                padding: 14px 18px; border-bottom: 1px solid var(--border);
                background: var(--bg-background);">
        <div style="display:flex; align-items:center; gap:10px;">
            <span style="font-size:1.3rem;">🔔</span>
            <div>
                <div style="font-weight:800; font-size:0.95rem; color:var(--text-primary);">
                    Nouveaux messages
                </div>
                <div style="font-size:0.75rem; color:var(--text-muted);">
                    {{ $unreadNotifs->count() }} notification{{ $unreadNotifs->count() > 1 ? 's' : '' }} non lue{{ $unreadNotifs->count() > 1 ? 's' : '' }}
                </div>
            </div>
        </div>
        <button onclick="dismissNotifToast()" title="Fermer" style="
            background: transparent; border: none; cursor: pointer;
            font-size: 1.2rem; color: var(--text-muted); padding: 4px;
            border-radius: 50%; transition: background 0.2s;
        ">✕</button>
    </div>

    {{-- List --}}
    <div style="max-height: 300px; overflow-y: auto;">
        @foreach($unreadNotifs as $notif)
        <div style="padding: 12px 18px; border-bottom: 1px solid var(--border); display:flex; gap:10px; align-items:flex-start;">
            <span style="font-size:1.1rem; margin-top:2px;">
                @if(str_contains($notif->message, '✅')) ✅
                @elseif(str_contains($notif->message, '❌')) ❌
                @elseif(str_contains($notif->message, '⏳')) ⏳
                @elseif(str_contains($notif->message, '🔵')) 🔵
                @elseif(str_contains($notif->message, '📩')) 📩
                @elseif(str_contains($notif->message, '🔧')) 🔧
                @else 💬
                @endif
            </span>
            <div style="flex:1; min-width:0;">
                <div style="font-size:0.85rem; color:var(--text-primary); line-height:1.4;">
                    {{ $notif->message }}
                </div>
                <div style="font-size:0.72rem; color:var(--text-muted); margin-top:3px;">
                    {{ $notif->created_at->diffForHumans() }}
                </div>
            </div>
            @if($notif->link)
            <a href="{{ $notif->link }}" style="font-size:0.75rem; color:var(--primary); white-space:nowrap; font-weight:600; text-decoration:none; margin-top:3px;">Voir →</a>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Footer --}}
    <div style="padding: 10px 18px; display:flex; justify-content:space-between; align-items:center; background:var(--bg-background);">
        <span id="notif-timer" style="font-size:0.75rem; color:var(--text-muted);">Fermeture auto dans <b id="notif-countdown">8</b>s</span>
        <button onclick="dismissNotifToast()" style="
            background: var(--primary); color: white; border: none;
            padding: 6px 16px; border-radius: 8px; cursor: pointer;
            font-weight: 700; font-size: 0.82rem;
        ">Marquer comme lu ✓</button>
    </div>
</div>

<style>
@keyframes notifSlideIn {
    from { opacity:0; transform: translateY(-20px) scale(0.95); }
    to   { opacity:1; transform: translateY(0) scale(1); }
}
@keyframes notifSlideOut {
    from { opacity:1; transform: translateY(0); }
    to   { opacity:0; transform: translateY(-20px); }
}
</style>

<script>
(function() {
    let countdown = 8;
    const countdownEl = document.getElementById('notif-countdown');
    const toast = document.getElementById('notif-toast');

    // Countdown timer
    const timer = setInterval(() => {
        countdown--;
        if (countdownEl) countdownEl.textContent = countdown;
        if (countdown <= 0) {
            clearInterval(timer);
            dismissNotifToast();
        }
    }, 1000);

    window.dismissNotifToast = function() {
        clearInterval(timer);
        if (!toast) return;
        toast.style.animation = 'notifSlideOut 0.3s ease forwards';
        setTimeout(() => { toast.remove(); }, 320);

        // Mark all as read via AJAX
        fetch('{{ route('notifications.markRead') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
    };
})();
</script>
@endif
@endauth

<main class="container">
    @yield('content')
</main>

<footer style="padding: 50px 20px; text-align: center; background: var(--bg-background); border-top: 1px solid var(--border);">
    
   @if(request()->routeIs('home'))
        <div style="margin-bottom: 40px; width: 100%; text-align: center;">
            <img src="{{ asset('images/fst.png') }}" alt="Logo FSTT" 
                 style="width: 100%; max-width: 1100px; height: auto; margin-bottom: 20px; display: block; margin-left: auto; margin-right: auto;">
        
            <div style="font-weight: 800; color: var(--text-primary); letter-spacing: 1.5px; font-size: 1.1rem; text-transform: uppercase;">
                FSTT - DÉPARTEMENT D'INFORMATIQUE - IDAI 2025-2026
            </div>
        </div>
    @endif

    <div style="color: var(--text-muted); font-size: 0.85rem;">
        &copy; {{ date('Y') }} DataCenter Management System. Tous droits réservés.
    </div>
</footer>

<script>
    const btn = document.getElementById('theme-toggle');
    const icon = document.getElementById('theme-toggle-icon');
    const body = document.body;

    // Charger le thème sauvegardé
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        body.classList.add('dark');
        icon.textContent = '☀️';
    }

    btn.addEventListener('click', () => {
        body.classList.toggle('dark');
        
        if (body.classList.contains('dark')) {
            localStorage.setItem('theme', 'dark');
            icon.textContent = '☀️';
        } else {
            localStorage.setItem('theme', 'light');
            icon.textContent = '🌙';
        }
    });
</script>
@yield('scripts')
</body>
</html>