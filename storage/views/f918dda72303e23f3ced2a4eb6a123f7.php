<?php echo $__env->yieldContent('css'); ?>
<!-- Layout config Js -->
<script src="<?php echo e(URL::asset('build/js/layout.js')); ?>"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap Css -->
<link href="<?php echo e(URL::asset('build/css/bootstrap.min.css')); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="<?php echo e(URL::asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="<?php echo e(URL::asset('build/css/app.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="<?php echo e(URL::asset('build/css/custom.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<?php /**PATH C:\laragon\www\LIS\LIS\resources\views/layouts/head-css.blade.php ENDPATH**/ ?>