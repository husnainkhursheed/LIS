<?php if(Session::has('message')): ?>
    <div class="alert <?php echo e(Session::get('alert-class', 'alert-info')); ?>" id="alert-message">
        <?php echo e(Session::get('message')); ?>

    </div>
    <script>
        setTimeout(function() {
            document.getElementById('alert-message').style.display = 'none';
        }, 5000);
    </script>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger" id="validation-errors">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('validation-errors').style.display = 'none';
        }, 6000);
    </script>
<?php endif; ?>

<?php if(Session::has('error')): ?>
    <div class="alert alert-danger" id="error-message">
        <?php echo e(Session::get('error')); ?>

    </div>
    <script>
        setTimeout(function() {
            document.getElementById('error-message').style.display = 'none';
        }, 5000); 
    </script>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\velzon_with_Multiauth_laravel\resources\views/layouts/notification.blade.php ENDPATH**/ ?>