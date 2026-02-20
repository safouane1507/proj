

<?php $__env->startSection('content'); ?>
<div style="max-width: 1200px; margin: 0 auto; padding-bottom: 50px;">
    <h1 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px; margin-bottom: 30px;">Espace Responsable Technique</h1>

    <?php if(session('success')): ?>
        <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c8e6c9;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid var(--primary);">
        <h2 style="color: var(--primary);">✨ Nouvelles demandes sur mesure (<?php echo e($customRequests->count()); ?>)</h2>
        
        <?php if($customRequests->isEmpty()): ?>
            <p style="color: #777; font-style: italic;">Aucune demande de configuration personnalisée.</p>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead style="background: var(--bg-background);">
                    <tr>
                        <th style="padding: 12px; text-align: left;">Utilisateur</th>
                        <th style="padding: 12px; text-align: left;">Config. Demandée</th>
                        <th style="padding: 12px; text-align: left;">Justification</th>
                        <th style="padding: 12px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="list-custom">
                    <?php $__currentLoopData = $customRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px;">
                                <strong><?php echo e($req->name); ?></strong><br>
                                <small><?php echo e($req->email); ?></small>
                            </td>
                            <td style="padding: 12px; font-size: 0.85rem;">
                                <b>Type:</b> <?php echo e($req->type); ?><br>
                                <b>CPU:</b> <?php echo e($req->cpu); ?> | <b>RAM:</b> <?php echo e($req->ram); ?><br>
                                <b>Disk:</b> <?php echo e($req->storage); ?>

                            </td>
                            <td style="padding: 12px; font-style: italic; font-size: 0.85rem;">"<?php echo e(Str::limit($req->justification, 30)); ?>"</td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center; flex-wrap:wrap;">
                                    <form action="<?php echo e(route('manager.custom.approve', $req->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" style="background: #2ecc71; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-weight: bold;">✔ Accepter</button>
                                    </form>

                                    <div>
                                        <button type="button" onclick="toggleRefuseCustom(<?php echo e($req->id); ?>)" style="background: #e74c3c; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-weight: bold;">✘ Rejeter</button>
                                        <div id="refuse-custom-<?php echo e($req->id); ?>" style="display:none; margin-top:8px; background:var(--bg-background); border:1px solid #e74c3c44; border-radius:8px; padding:12px; min-width:240px;">
                                            <form action="<?php echo e(route('manager.custom.reject', $req->id)); ?>" method="POST" onsubmit="return validateRefuseCustom(<?php echo e($req->id); ?>)">
                                                <?php echo csrf_field(); ?>
                                                <label style="display:block; font-size:0.78rem; font-weight:700; color:#e74c3c; margin-bottom:4px; text-transform:uppercase;">Motif du refus <span style="color:red">*</span></label>
                                                <textarea id="custom-motif-<?php echo e($req->id); ?>" name="manager_feedback" rows="2" placeholder="Expliquez la raison du refus..." style="width:100%; padding:8px; border-radius:6px; border:1px solid var(--border); background:var(--bg-surface); color:var(--text-primary); font-size:0.85rem; resize:vertical;"></textarea>
                                                <div style="display:flex; gap:6px; margin-top:8px; justify-content:flex-end;">
                                                    <button type="button" onclick="toggleRefuseCustom(<?php echo e($req->id); ?>)" style="padding:5px 10px; border:1px solid var(--border); border-radius:6px; background:transparent; color:var(--text-muted); cursor:pointer; font-size:0.82rem;">Annuler</button>
                                                    <button type="submit" style="padding:5px 12px; background:#e74c3c; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:700; font-size:0.82rem;">Confirmer le refus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div id="toggle-custom" style="margin-top:10px;"></div>
        <?php endif; ?>
    </div>

    
    <div class="card" style="margin-bottom: 30px; border-left: 5px solid #8e44ad;">
        <h2 style="color: #8e44ad;">📩 Messages d'Aide</h2>
        <?php if($helpMessages->isEmpty()): ?> 
            <p style="color: var(--text-muted);">Aucun message.</p> 
        <?php else: ?>
            <div id="list-mgr-messages" style="display: flex; flex-direction: column; gap: 10px;">
                <?php $__currentLoopData = $helpMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="border: 1px solid var(--border); border-radius: 8px; overflow: hidden; margin-bottom: 10px;">
                    <div onclick="document.getElementById('mgr-msg-content-<?php echo e($msg->id); ?>').style.display = document.getElementById('mgr-msg-content-<?php echo e($msg->id); ?>').style.display === 'none' ? 'block' : 'none'" 
                         style="padding: 15px; background: <?php echo e($msg->is_read ? 'var(--bg-surface)' : 'rgba(255, 193, 7, 0.15)'); ?>; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border-left: 4px solid <?php echo e($msg->is_read ? 'transparent' : '#f1c40f'); ?>;">
                        <div>
                            <strong style="color: var(--text-primary);">Message aide from <?php echo e($msg->email); ?></strong>
                            <span style="font-size: 0.85rem; color: var(--text-muted); margin-left: 10px;"><?php echo e($msg->created_at->format('d/m H:i')); ?></span>
                        </div>
                        <div>
                            <span style="padding: 4px 8px; border-radius: 4px; background: <?php echo e($msg->is_read ? 'var(--bg-background)' : '#ffecb3'); ?>; color: <?php echo e($msg->is_read ? 'var(--text-muted)' : '#b45309'); ?>; font-size: 0.8rem; font-weight: bold; border: 1px solid <?php echo e($msg->is_read ? 'var(--border)' : 'transparent'); ?>;">
                                <?php echo e($msg->is_read ? 'Lu' : 'Non Lu'); ?>

                            </span>
                        </div>
                    </div>
                    <div id="mgr-msg-content-<?php echo e($msg->id); ?>" style="display: none; padding: 15px; border-top: 1px solid var(--border); background: var(--bg-background);">
                        <p style="white-space: pre-wrap; margin-bottom: 15px; color: var(--text-primary);"><?php echo e($msg->message); ?></p>
                        <?php if(!$msg->is_read): ?>
                            <form action="<?php echo e(route('manager.messages.read', $msg->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                                    Marquer comme lu
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div id="toggle-mgr-messages" style="margin-top:10px;"></div>
        <?php endif; ?>
    </div>

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid orange;">
        <h2 style="color: #d35400;">🔔 Réservations en attente (<?php echo e($pendingReservations->count()); ?>)</h2>
        <?php if($pendingReservations->isEmpty()): ?>
            <p style="color: #777; font-style: italic;">Aucune réservation à traiter.</p>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead style="background: var(--bg-background);">
                    <tr>
                        <th style="padding: 12px; text-align: left;">Utilisateur</th>
                        <th style="padding: 12px; text-align: left;">Ressource</th>
                        <th style="padding: 12px; text-align: left;">Dates</th>
                        <th style="padding: 12px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="list-pending">
                    <?php $__currentLoopData = $pendingReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px;"><strong><?php echo e($reservation->user->name); ?></strong></td>
                            <td style="padding: 12px; color: var(--primary);"><?php echo e($reservation->resource->label); ?></td>
                            <td style="padding: 12px; font-size: 0.85rem;">
                                Du <?php echo e(\Carbon\Carbon::parse($reservation->start_date)->format('d/m H:i')); ?><br>
                                au <?php echo e(\Carbon\Carbon::parse($reservation->end_date)->format('d/m H:i')); ?>

                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                    <form action="<?php echo e(route('manager.reservations.handle', $reservation->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" style="background: #2ecc71; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-weight: bold;">✔ Accepter</button>
                                    </form>

                                    
                                    <div>
                                        <button type="button"
                                                onclick="toggleRefuse(<?php echo e($reservation->id); ?>)"
                                                style="background: #e74c3c; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-weight: bold;">
                                            ✘ Refuser
                                        </button>
                                        <div id="refuse-form-<?php echo e($reservation->id); ?>" style="display:none; margin-top:8px; background:var(--bg-background); border:1px solid #e74c3c44; border-radius:8px; padding:12px; min-width:240px;">
                                            <form action="<?php echo e(route('manager.reservations.handle', $reservation->id)); ?>" method="POST"
                                                  onsubmit="return validateRefuse(<?php echo e($reservation->id); ?>)">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="action" value="reject">
                                                <label style="display:block; font-size:0.78rem; font-weight:700; color:#e74c3c; margin-bottom:4px; text-transform:uppercase;">Motif du refus <span style="color:red">*</span></label>
                                                <textarea id="motif-<?php echo e($reservation->id); ?>" name="manager_feedback"
                                                          rows="2" placeholder="Expliquez la raison du refus..."
                                                          style="width:100%; padding:8px; border-radius:6px; border:1px solid var(--border); background:var(--bg-surface); color:var(--text-primary); font-size:0.85rem; resize:vertical;"></textarea>
                                                <div style="display:flex; gap:6px; margin-top:8px; justify-content:flex-end;">
                                                    <button type="button" onclick="toggleRefuse(<?php echo e($reservation->id); ?>)" style="padding:5px 10px; border:1px solid var(--border); border-radius:6px; background:transparent; color:var(--text-muted); cursor:pointer; font-size:0.82rem;">Annuler</button>
                                                    <button type="submit" style="padding:5px 12px; background:#e74c3c; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:700; font-size:0.82rem;">Confirmer le refus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div id="toggle-pending" style="margin-top:10px;"></div>
        <?php endif; ?>
    <div style="display: flex; justify-content: center; align-items: center; gap: 20px; margin: 40px 0 30px 0;">
        <h2 style="margin: 0; color: var(--text-primary);">🛠 Gestion du Catalogue (<?php echo e($managedResources->count()); ?>)</h2>
        <a href="<?php echo e(route('manager.resources.create')); ?>" style="background: var(--primary); color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: bold;">+ Ajouter</a>
    </div>
    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px;">
            <?php $__currentLoopData = $managedResources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card" style="padding: 15px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg-surface);">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-size: 0.7rem; font-weight: 800; color: var(--primary); text-transform: uppercase;"><?php echo e($resource->category); ?></span>
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: <?php echo e($resource->status == 'available' ? '#00b894' : ($resource->status == 'maintenance' ? 'orange' : '#d63031')); ?>;"></div>
                    </div>
                    <h3 style="font-size: 1.1rem; margin: 10px 0;"><?php echo e($resource->label); ?></h3>
                    
                    <div style="margin-top: 15px; display: flex; gap: 10px;">
                        <a href="<?php echo e(route('manager.resources.edit', $resource->id)); ?>" style="flex: 1; text-align: center; font-size: 0.8rem; padding: 6px; background: var(--bg-background); border: 1px solid var(--border); border-radius: 6px; text-decoration: none; color: var(--text-primary); font-weight: 600;">⚙ Gérer</a>
                        
                        <form action="<?php echo e(route('manager.resources.destroy', $resource->id)); ?>" method="POST" style="flex: 1;" onsubmit="return confirm('Supprimer définitivement cette ressource ?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" style="width: 100%; font-size: 0.8rem; padding: 6px; background: rgba(214, 48, 49, 0.1); color: #d63031; border: 1px solid rgba(214, 48, 49, 0.2); border-radius: 6px; cursor: pointer; font-weight: 600;">Supprimer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<script>
function toggleRefuse(id) {
    const el = document.getElementById('refuse-form-' + id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
    if (el.style.display === 'block') {
        el.querySelector('textarea').focus();
    }
}
function validateRefuse(id) {
    const txt = document.getElementById('motif-' + id);
    if (!txt.value.trim()) {
        txt.style.borderColor = '#e74c3c';
        txt.placeholder = '⚠️ Le motif est obligatoire !';
        txt.focus();
        return false;
    }
    return true;
}
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
        txt.placeholder = '⚠️ Le motif est obligatoire !';
        txt.focus();
        return false;
    }
    return true;
}
</script>

<script>
// Expandable lists: show 5 rows by default, toggle more/less
document.addEventListener('DOMContentLoaded', function () {
    const LIMIT = 5;
    const BTN_STYLE = 'background:transparent; border:1px solid var(--border); padding:6px 18px; border-radius:20px; cursor:pointer; font-size:0.83rem; font-weight:600; color:var(--primary); transition:all 0.2s; margin-top:4px;';

    // Table-based lists (tbody rows)
    [['list-custom','toggle-custom'],['list-pending','toggle-pending']].forEach(([listId, btnId]) => {
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

    // Flex/div-based lists (child divs)
    [['list-mgr-messages','toggle-mgr-messages']].forEach(([listId, btnId]) => {
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\PROJET DEV\Project Dev\resources\views/manager/dashboard.blade.php ENDPATH**/ ?>