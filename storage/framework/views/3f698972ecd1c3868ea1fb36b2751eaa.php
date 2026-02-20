

<?php $__env->startSection('content'); ?>
<div style="max-width: 600px; margin: 40px auto;">
    <a href="<?php echo e(route('manager.dashboard')); ?>" style="text-decoration: none; color: #666;">&larr; Retour au tableau de bord</a>
    
    <div class="card" style="margin-top: 15px;">
        <h2 style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Gérer : <?php echo e($resource->label); ?></h2>
        
        <form action="<?php echo e(route('manager.resources.update', $resource->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">État de la ressource</label>
                <select name="status" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ccc;">
                    <option value="available" <?php echo e($resource->status == 'available' ? 'selected' : ''); ?>>✅ Disponible</option>
                    <option value="maintenance" <?php echo e($resource->status == 'maintenance' ? 'selected' : ''); ?>>🔧 En Maintenance</option>
                    <option value="inactive" <?php echo e($resource->status == 'inactive' ? 'selected' : ''); ?>>⛔ Désactivé (Hors service)</option>
                    </select>
                <small style="color: #777;">Si vous mettez "Maintenance" ou "Désactivé", la ressource ne sera plus visible pour les réservations.</small>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">Note technique / Description</label>
                <textarea name="description" rows="5" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ccc;"><?php echo e($resource->description); ?></textarea>
            </div>

            <button type="submit" style="background: var(--primary); color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; width: 100%;">
                Sauvegarder les modifications
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\PROJET DEV\Project Dev\resources\views/manager/resources/edit.blade.php ENDPATH**/ ?>