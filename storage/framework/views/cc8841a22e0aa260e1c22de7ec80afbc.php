<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('images/favicone.ico')); ?>">
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
                <a href="<?php echo e(route('resources.all')); ?>" class="nav-link">Ressources ▾</a>
                <div class="dropdown-menu">
                    <div style="padding: 0 15px 5px; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Filtrer par</div>
                    <a href="<?php echo e(route('resources.all', ['cat' => 'Serveur Physique'])); ?>" class="dropdown-item">🖥️ Serveurs</a>
                    <a href="<?php echo e(route('resources.all', ['cat' => 'Machine Virtuelle'])); ?>" class="dropdown-item">☁️ Virtuel (VM)</a>
                    <a href="<?php echo e(route('resources.all', ['cat' => 'Stockage'])); ?>" class="dropdown-item">💾 Stockage</a>
                    <a href="<?php echo e(route('resources.all', ['cat' => 'Réseau'])); ?>" class="dropdown-item">🌐 Réseau</a>
                </div>
            </div>

            <?php if(request()->routeIs('home')): ?>
                <a href="#features" class="nav-link" style="color: var(--text-muted); text-decoration: none; font-weight: 500; transition: color 0.3s;">
                    Features
                </a>
                <a href="#definitions" class="nav-link" style="color: var(--text-muted); text-decoration: none; font-weight: 500; transition: color 0.3s;">
                    Définitions
                </a>
                <a href="#contact" class="nav-link" style="color: var(--text-muted); text-decoration: none; font-weight: 500; transition: color 0.3s;">
                    Contact Us
                </a>
            <?php endif; ?>

        </div>

        <button id="theme-toggle" title="Changer de mode">
            <span id="theme-toggle-icon">🌙</span>
        </button>

        <?php if(auth()->guard()->guest()): ?>
            <a href="<?php echo e(route('login')); ?>" class="btn btn-outline">Connexion</a>
            <a href="<?php echo e(route('register.request')); ?>" class="btn btn-primary">Inscription</a>
        <?php else: ?>
            <?php
                $roleTitle = "M.";
                if(Auth::user()->role === 'manager') $roleTitle = "Ing.";
                if(Auth::user()->role === 'admin') $roleTitle = "Admin";
            ?>
            
            <div class="user-greeting">
                <div class="user-avatar"><?php echo e(substr(Auth::user()->name, 0, 1)); ?></div>
                <div style="font-size: 0.85rem; line-height: 1.2;">
                    <span style="color: var(--text-muted); font-size: 0.8em;">Bonjour,</span><br>
                    <strong style="color: var(--text-primary);"><?php echo e($roleTitle); ?> <?php echo e(Auth::user()->name); ?></strong>
                </div>
            </div>

            <a href="<?php echo e(Auth::user()->role === 'admin' ? route('admin.dashboard') : (Auth::user()->role === 'manager' ? route('manager.dashboard') : route('user.dashboard'))); ?>" class="nav-link">
                Dashboard
            </a>

            
            <a href="<?php echo e(route('profile.settings')); ?>" class="nav-link" style="margin-right: 15px;">⚙️ Paramètres</a> 
            

            <form action="<?php echo e(route('logout')); ?>" method="POST" style="display:inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-link" style="background:none; border:none; font-size:1.2rem;" title="Déconnexion">⏻</button>
            </form>
        <?php endif; ?>
    </nav>
</header>


<?php if(auth()->guard()->check()): ?>
<?php
    $unreadNotifs = \App\Models\Notification::where('user_id', Auth::id())
        ->where('is_read', false)
        ->latest()
        ->get();
?>
<?php if($unreadNotifs->isNotEmpty()): ?>
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
                    <?php echo e($unreadNotifs->count()); ?> notification<?php echo e($unreadNotifs->count() > 1 ? 's' : ''); ?> non lue<?php echo e($unreadNotifs->count() > 1 ? 's' : ''); ?>

                </div>
            </div>
        </div>
        <button onclick="dismissNotifToast()" title="Fermer" style="
            background: transparent; border: none; cursor: pointer;
            font-size: 1.2rem; color: var(--text-muted); padding: 4px;
            border-radius: 50%; transition: background 0.2s;
        ">✕</button>
    </div>

    
    <div style="max-height: 300px; overflow-y: auto;">
        <?php $__currentLoopData = $unreadNotifs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="padding: 12px 18px; border-bottom: 1px solid var(--border); display:flex; gap:10px; align-items:flex-start;">
            <span style="font-size:1.1rem; margin-top:2px;">
                <?php if(str_contains($notif->message, '✅')): ?> ✅
                <?php elseif(str_contains($notif->message, '❌')): ?> ❌
                <?php elseif(str_contains($notif->message, '⏳')): ?> ⏳
                <?php elseif(str_contains($notif->message, '🔵')): ?> 🔵
                <?php elseif(str_contains($notif->message, '📩')): ?> 📩
                <?php elseif(str_contains($notif->message, '🔧')): ?> 🔧
                <?php else: ?> 💬
                <?php endif; ?>
            </span>
            <div style="flex:1; min-width:0;">
                <div style="font-size:0.85rem; color:var(--text-primary); line-height:1.4;">
                    <?php echo e($notif->message); ?>

                </div>
                <div style="font-size:0.72rem; color:var(--text-muted); margin-top:3px;">
                    <?php echo e($notif->created_at->diffForHumans()); ?>

                </div>
            </div>
            <?php if($notif->link): ?>
            <a href="<?php echo e($notif->link); ?>" style="font-size:0.75rem; color:var(--primary); white-space:nowrap; font-weight:600; text-decoration:none; margin-top:3px;">Voir →</a>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
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
        fetch('<?php echo e(route('notifications.markRead')); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json'
            }
        });
    };
})();
</script>
<?php endif; ?>
<?php endif; ?>

<main class="container">
    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer style="padding: 50px 20px; text-align: center; background: var(--bg-background); border-top: 1px solid var(--border);">
    
   <?php if(request()->routeIs('home')): ?>
        <div style="margin-bottom: 40px; width: 100%; text-align: center;">
            <img src="<?php echo e(asset('images/fst.png')); ?>" alt="Logo FSTT" 
                 style="width: 100%; max-width: 1100px; height: auto; margin-bottom: 20px; display: block; margin-left: auto; margin-right: auto;">
        
            <div style="font-weight: 800; color: var(--text-primary); letter-spacing: 1.5px; font-size: 1.1rem; text-transform: uppercase;">
                FSTT - DÉPARTEMENT D'INFORMATIQUE - IDAI 2025-2026
            </div>
        </div>
    <?php endif; ?>

    <div style="color: var(--text-muted); font-size: 0.85rem;">
        &copy; <?php echo e(date('Y')); ?> DataCenter Management System. Tous droits réservés.
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
<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\PROJET DEV\Project Dev\resources\views/layouts/app.blade.php ENDPATH**/ ?>