<!DOCTYPE html>
<html>
<head><title>Nouveau Message</title></head>
<body>
    <h2>Nouveau message de : <?php echo e($data['email']); ?></h2>
    <p><strong>Sujet :</strong> Support Technique / Contact</p>
    <hr>
    <p><?php echo e($data['message']); ?></p>
</body>
</html><?php /**PATH C:\xampp\htdocs\PROJET DEV\Project Dev\resources\views/emails/contact.blade.php ENDPATH**/ ?>