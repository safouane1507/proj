<?php $__env->startSection('content'); ?>

<style>
    /* Style CSS pour le défilement fluide */
    html { scroll-behavior: smooth; }

    /* Cartes Interactives */
    .hover-card {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 30px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .hover-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(15, 163, 163, 0.15);
        border-color: var(--primary);
    }

    /* Icônes Carrées */
    .icon-box {
        width: 60px;
        height: 60px;
        background: rgba(15, 163, 163, 0.1);
        color: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 20px;
        transition: transform 0.3s ease;
    }

    .hover-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
        background: var(--primary);
        color: white;
    }

    /* Boutons animés */
    .btn-anim { transition: all 0.3s ease; }
    .btn-anim:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

    /* Cartes Étapes */
    .step-card { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; }
    .step-card:hover { background: rgba(255,255,255,0.2); transform: translateY(-5px); }
    .step-card .icon-box { background: rgba(255,255,255,0.2); color: white; }
    .step-card:hover .icon-box { background: white; color: var(--primary); transform: scale(1.1); }
</style>

<section style="text-align: center; padding: 120px 20px 100px; background: linear-gradient(180deg, var(--bg-surface) 0%, var(--bg-background) 100%); border-bottom: 1px solid var(--border);">
    <div style="max-width: 900px; margin: 0 auto;">
        <h1 style="font-size: 3.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 24px; color: var(--text-primary); letter-spacing: -1px;">
            L'Infrastructure pour les <br> <span style="color: var(--primary);">Bâtisseurs de Demain</span>
        </h1>
        <p style="font-size: 1.25rem; color: var(--text-muted); max-width: 700px; margin: 0 auto 40px auto; line-height: 1.6;">
            Gérez, réservez et déployez vos ressources internes. Des machines virtuelles aux serveurs dédiés, la puissance à portée de clic.
        </p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="<?php echo e(route('resources.all')); ?>" class="btn btn-primary btn-anim" style="padding: 16px 36px; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(15, 163, 163, 0.3);">Explorer l'Inventaire</a>
            <?php if(auth()->guard()->guest()): ?> <a href="<?php echo e(route('login')); ?>" class="btn btn-outline btn-anim" style="padding: 16px 36px; font-size: 1.1rem;">Se Connecter</a> <?php endif; ?>
        </div>
    </div>
</section>

<section style="padding: 40px 20px; text-align: center; background: var(--bg-background); border-bottom: 1px solid var(--border);">
    <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.2px; color: var(--text-muted); font-weight: 700; margin-bottom: 25px; display: block;">Utilisé par les équipes</span>
    <div style="display: flex; justify-content: center; gap: 50px; flex-wrap: wrap; opacity: 0.6; filter: grayscale(100%);">
        <span style="font-weight: 900; font-size: 1.5rem; color: var(--text-primary);">DEV-OPS</span>
        <span style="font-weight: 900; font-size: 1.5rem; color: var(--text-primary);">DATA-LAB</span>
        <span style="font-weight: 900; font-size: 1.5rem; color: var(--text-primary);">R&D</span>
        <span style="font-weight: 900; font-size: 1.5rem; color: var(--text-primary);">DEV-WEB</span>
    </div>
</section>

<section id="features" style="padding: 100px 20px; background: var(--bg-surface); border-bottom: 1px solid var(--border);">
    <div style="max-width: 1100px; margin: 0 auto;">
        <div style="margin-bottom: 60px;">
            <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 15px; color: var(--text-primary);">Pourquoi cette plateforme ?</h2>
            <p style="font-size: 1.1rem; color: var(--text-muted);">Centralisez la gestion de votre infrastructure pour réduire les frictions.</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
            <div class="hover-card"><div class="icon-box">⚡</div><h3 style="margin-bottom: 15px; color: var(--text-primary);">Disponibilité Instantanée</h3><p style="color: var(--text-muted);">Vérifiez l'état en temps réel des ressources.</p></div>
            <div class="hover-card"><div class="icon-box">🛡️</div><h3 style="margin-bottom: 15px; color: var(--text-primary);">Accès Sécurisé</h3><p style="color: var(--text-muted);">Environnement cloisonné et validé par rôles.</p></div>
            <div class="hover-card"><div class="icon-box">📊</div><h3 style="margin-bottom: 15px; color: var(--text-primary);">Pilotage Centralisé</h3><p style="color: var(--text-muted);">Suivez réservations et historique sur un écran.</p></div>
        </div>
    </div>
</section>

<section id="definitions" style="padding: 100px 20px; background: var(--bg-background); border-bottom: 1px solid var(--border);">
    <div style="max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 60px; align-items: center;">
        <div>
            <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 20px; color: var(--text-primary);">Comprendre nos Ressources</h2>
            <p style="font-size: 1.1rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 35px;">Guide rapide des technologies disponibles.</p>
            <a href="<?php echo e(route('resources.all')); ?>" class="btn btn-primary btn-anim" style="padding: 16px 36px; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(15, 163, 163, 0.3);">Catalogue &rarr;</a>
        </div>
        <div style="display: grid; gap: 20px;">
            <div class="hover-card" style="flex-direction: row; align-items: center; padding: 20px; gap: 20px;">
                <div class="icon-box" style="margin-bottom: 0; min-width: 60px;">☁️</div><div><h4 style="font-weight: 700; color: var(--text-primary); margin-bottom: 5px;">Machine Virtuelle</h4><p style="color: var(--text-muted); margin: 0;">Pour dev et tests. Déploiement rapide.</p></div>
            </div>
            <div class="hover-card" style="flex-direction: row; align-items: center; padding: 20px; gap: 20px;">
                <div class="icon-box" style="margin-bottom: 0; min-width: 60px;">🖥️</div><div><h4 style="font-weight: 700; color: var(--text-primary); margin-bottom: 5px;">Serveur Physique</h4><p style="color: var(--text-muted); margin: 0;">Puissance brute pour calculs intensifs.</p></div>
            </div>
            <div class="hover-card" style="flex-direction: row; align-items: center; padding: 20px; gap: 20px;">
                <div class="icon-box" style="margin-bottom: 0; min-width: 60px;">💾</div><div><h4 style="font-weight: 700; color: var(--text-primary); margin-bottom: 5px;">Stockage Bloc</h4><p style="color: var(--text-muted); margin: 0;">Données persistantes haute performance.</p></div>
            </div>
            <div class="hover-card" style="flex-direction: row; align-items: center; padding: 20px; gap: 20px;">
                <div class="icon-box" style="margin-bottom: 0; min-width: 60px;">🌐</div><div><h4 style="font-weight: 700; color: var(--text-primary); margin-bottom: 5px;">Réseau</h4><p style="color: var(--text-muted); margin: 0;">Connectivité haute performance et SDN.</p></div>
            </div>
        </div>
    </div>
</section>

<section style="padding: 100px 20px; background: var(--primary); color: white; text-align: center;">
    <div style="max-width: 1100px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 15px; color: white;">Comment ça marche ?</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; text-align: left; margin-top: 50px;">
            <div class="hover-card step-card"><div class="icon-box">01</div><h3 style="color: white;">Inscription</h3><p style="opacity: 0.9;">Créez un compte et parcourez le catalogue.</p></div>
            <div class="hover-card step-card"><div class="icon-box">02</div><h3 style="color: white;">Demande</h3><p style="opacity: 0.9;">Réservez vos dates. Validé par un Manager.</p></div>
            <div class="hover-card step-card"><div class="icon-box">03</div><h3 style="color: white;">Accès</h3><p style="opacity: 0.9;">Recevez vos accès et commencez à travailler.</p></div>
        </div>
    </div>
</section>

<section id="contact" style="padding: 120px 20px; text-align: center; background: var(--bg-surface);">
    <div class="hover-card" style="max-width: 700px; margin: 0 auto; padding: 60px; text-align: center; align-items: center;">
        <div class="icon-box">✉️</div>
        <h2 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 15px; color: var(--text-primary);">Besoin d'aide ?</h2>
        <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 40px;">Une question ? Notre équipe vous répond.</p>
        
        <?php if(session('success')): ?> <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 25px; width: 100%;"><?php echo e(session('success')); ?></div> <?php endif; ?>
        <?php if(session('error')): ?> <div style="background: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 25px; width: 100%;"><?php echo e(session('error')); ?></div> <?php endif; ?>

        <form action="<?php echo e(route('contact.send')); ?>" method="POST" style="width: 100%; text-align: left; display: grid; gap: 20px;">
            <?php echo csrf_field(); ?>
            <div><label style="font-weight: 600; color: var(--text-primary);">Email</label><input type="email" name="email" required style="width: 100%; padding: 15px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background); color: var(--text-primary);"></div>
            <div><label style="font-weight: 600; color: var(--text-primary);">Message</label><textarea name="message" rows="5" required style="width: 100%; padding: 15px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background); color: var(--text-primary);"></textarea></div>
            <button type="submit" class="btn btn-primary btn-anim" style="padding: 16px 40px; border-radius: 8px; width: 100%;">Envoyer le message ✉️</button>
        </form>
    </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\PROJET DEV\Project Dev\resources\views/welcome.blade.php ENDPATH**/ ?>