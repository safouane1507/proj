

<?php $__env->startSection('content'); ?>
<div style="max-width: 640px; margin: 40px auto; padding: 0 16px;">
    
    <div class="card" style="padding: 32px; border-radius: 18px; border: 1px solid var(--border); background: var(--bg-surface);">
        <h2 style="color: var(--primary); margin: 0 0 6px;">📅 Nouvelle Réservation</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0 0 24px;">
            Confirmez les dates pour bloquer votre ressource.
        </p>

        
        <?php if($errors->any()): ?>
            <div style="background:#fee2e2; border:1px solid #ef4444; color:#b91c1c; padding:14px 18px; border-radius:10px; margin-bottom:20px;">
                <ul style="margin:0; padding-left:18px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        
        <div id="js-error-alert" style="display:none; background:#fee2e2; border:1px solid #ef4444; color:#b91c1c; padding:14px 18px; border-radius:10px; margin-bottom:20px; font-weight:600;">
            <span id="js-error-message"></span>
        </div>

        
        <div id="conflict-alert" style="display:none; background:#fff3cd; border:1px solid #f59e0b; color:#92400e; padding:14px 18px; border-radius:10px; margin-bottom:20px;">
            <strong>⛔ Créneau non disponible</strong>
            <div id="conflict-detail" style="margin-top:4px; font-size:0.88rem;"></div>
        </div>

        <form action="<?php echo e(route('reservations.store')); ?>" method="POST" id="reservationForm">
            <?php echo csrf_field(); ?>
            
            
            <div style="margin-bottom:20px;">
                <label style="display:block; font-weight:700; font-size:0.85rem; margin-bottom:6px;">Ressource sélectionnée</label>
                <?php if(isset($selectedResource)): ?>
                    <div style="background:var(--bg-background); padding:12px 16px; border:1px solid var(--border); border-radius:10px; display:flex; align-items:center; justify-content:space-between;">
                        <span style="font-weight:700; color:var(--text-primary);">
                            📦 <?php echo e($selectedResource->label); ?>

                            <span style="font-weight:400; font-size:0.8rem; color:var(--text-muted);">(<?php echo e($selectedResource->category); ?>)</span>
                        </span>
                        <span style="color:#2ecc71; font-weight:700; font-size:0.85rem;">● Disponible</span>
                    </div>
                    <input type="hidden" name="resource_id" value="<?php echo e($selectedResource->id); ?>">
                <?php else: ?>
                    <select name="resource_id" required
                            style="width:100%; padding:12px; border-radius:10px; border:1px solid var(--border); background:var(--bg-background); color:var(--text-primary); font-size:0.95rem;">
                        <option value="">-- Choisir une ressource --</option>
                        <?php $__currentLoopData = $resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($resource->id); ?>"><?php echo e($resource->category); ?> : <?php echo e($resource->label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                <?php endif; ?>
            </div>

            
            <?php if(isset($selectedResource) && isset($bookedSlots) && $bookedSlots->isNotEmpty()): ?>
                <div style="margin-bottom:20px; padding:14px; background:rgba(239,68,68,0.06); border:1px solid rgba(239,68,68,0.25); border-radius:10px;">
                    <div style="font-size:0.78rem; font-weight:800; color:#dc2626; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:10px;">
                        🚫 Créneaux déjà réservés — choisissez une date en dehors de ces périodes
                    </div>
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <?php $__currentLoopData = $bookedSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $slotColors = [
                                    'pending'  => ['bg'=>'rgba(245,158,11,0.12)', 'color'=>'#b45309', 'label'=>'⏳ En attente'],
                                    'approved' => ['bg'=>'rgba(239,68,68,0.12)',  'color'=>'#dc2626', 'label'=>'✅ Approuvée'],
                                    'active'   => ['bg'=>'rgba(239,68,68,0.12)',  'color'=>'#dc2626', 'label'=>'🔵 Active'],
                                ];
                                $sc = $slotColors[$slot->status] ?? ['bg'=>'#eee','color'=>'#666','label'=>$slot->status];
                            ?>
                            <div style="display:flex; align-items:center; gap:10px; padding:8px 12px; background:<?php echo e($sc['bg']); ?>; border-radius:8px;">
                                <span style="font-size:0.78rem; font-weight:700; color:<?php echo e($sc['color']); ?>; min-width:90px;"><?php echo e($sc['label']); ?></span>
                                <span style="font-size:0.85rem; color:var(--text-primary);">
                                    <?php echo e(\Carbon\Carbon::parse($slot->start_date)->format('d/m/Y H:i')); ?>

                                    →
                                    <?php echo e(\Carbon\Carbon::parse($slot->end_date)->format('d/m/Y H:i')); ?>

                                </span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
                <div>
                    <label style="display:block; font-weight:700; font-size:0.85rem; margin-bottom:6px;">Date de début</label>
                    <input type="datetime-local" id="start_date" name="start_date" required
                           style="width:100%; padding:12px; border-radius:10px; border:1px solid var(--border); background:var(--bg-background); color:var(--text-primary); <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-color:#ef4444; <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('start_date')); ?>">
                    <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span style="color:#ef4444; font-size:0.82rem;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label style="display:block; font-weight:700; font-size:0.85rem; margin-bottom:6px;">Date de fin</label>
                    <input type="datetime-local" id="end_date" name="end_date" required
                           style="width:100%; padding:12px; border-radius:10px; border:1px solid var(--border); background:var(--bg-background); color:var(--text-primary); <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-color:#ef4444; <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('end_date')); ?>">
                    <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span style="color:#ef4444; font-size:0.82rem;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div style="margin-bottom:24px;">
                <label style="display:block; font-weight:700; font-size:0.85rem; margin-bottom:6px;">Motif de la réservation</label>
                <textarea name="justification" rows="3" placeholder="Pour quel projet ou usage ?"
                          style="width:100%; padding:12px; border-radius:10px; border:1px solid var(--border); background:var(--bg-background); color:var(--text-primary); resize:vertical; font-size:0.9rem; <?php $__errorArgs = ['justification'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-color:#ef4444; <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('justification')); ?></textarea>
                <?php $__errorArgs = ['justification'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span style="color:#ef4444; font-size:0.82rem;"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" id="submitBtn"
                    style="background:var(--primary); color:white; width:100%; padding:14px; font-weight:700; font-size:1rem; border:none; border-radius:10px; cursor:pointer; transition:opacity 0.2s;">
                ✅ Confirmer la réservation
            </button>
        </form>
    </div>
    
    <div style="text-align:center; margin-top:16px;">
        <a href="<?php echo e(route('user.dashboard')); ?>" style="color:var(--text-muted); text-decoration:none; font-size:0.9rem;">← Retour au tableau de bord</a>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Booked slots passed from server (ISO strings)
const bookedSlots = <?php echo json_encode(isset($bookedSlots) ? $bookedSlots->map(fn($s) => ['start' => $s->start_date, 'end' => $s->end_date]) : [], 512) ?>;

const startInput   = document.getElementById('start_date');
const endInput     = document.getElementById('end_date');
const errAlert     = document.getElementById('js-error-alert');
const errMsg       = document.getElementById('js-error-message');
const conflictBox  = document.getElementById('conflict-alert');
const conflictDetail = document.getElementById('conflict-detail');
const submitBtn    = document.getElementById('submitBtn');

function showErr(msg) {
    errMsg.textContent = msg;
    errAlert.style.display = 'block';
    errAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
function hideErr() { errAlert.style.display = 'none'; }

function fmt(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('fr-FR') + ' ' + d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
}

function checkConflicts() {
    hideErr();
    conflictBox.style.display = 'none';
    submitBtn.disabled = false;
    submitBtn.style.opacity = '1';

    const startStr = startInput.value;
    const endStr   = endInput.value;
    if (!startStr || !endStr) return;

    const now   = new Date();
    const start = new Date(startStr);
    const end   = new Date(endStr);

    // Basic date validation
    if (start < new Date(now.getTime() - 60000)) {
        showErr('⚠️ La date de début ne peut pas être dans le passé.');
        startInput.value = '';
        return;
    }
    if (end <= start) {
        showErr('⚠️ La date de fin doit être après la date de début.');
        endInput.value = '';
        return;
    }

    // Check against known booked slots
    const conflict = bookedSlots.find(s => {
        const sStart = new Date(s.start);
        const sEnd   = new Date(s.end);
        return start < sEnd && end > sStart;
    });

    if (conflict) {
        conflictDetail.textContent = `Ce créneau chevauche une réservation existante : ${fmt(conflict.start)} → ${fmt(conflict.end)}`;
        conflictBox.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.45';
        conflictBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

if (startInput) startInput.addEventListener('change', checkConflicts);
if (endInput)   endInput.addEventListener('change', checkConflicts);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\PROJET DEV\Project Dev\resources\views/user/reservations/create.blade.php ENDPATH**/ ?>