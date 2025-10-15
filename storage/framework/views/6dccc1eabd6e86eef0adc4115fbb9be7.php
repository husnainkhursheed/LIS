
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.settings'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            
            <div class="overlay-content">
                <div class="text-end p-3">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!--end col-->
        <div class="col-xxl-12">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i>
                                Change Password
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#socialconfig" role="tab">
                                <i class="far fa-envelope"></i>
                                 Configurations
                            </a>
                        </li>
                        
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content">
                        <?php if(Session::has('message')): ?>
                        <div class="alert <?php echo e(Session::get('alert-class', 'alert-info')); ?>" id="alert-message">
                            <?php echo e(Session::get('message')); ?>

                        </div>

                        <script>
                            // Add a timer to automatically dismiss the alert after 5 seconds (adjust as needed)
                            setTimeout(function() {
                                document.getElementById('alert-message').style.display = 'none';
                            }, 5000); // 5000 milliseconds = 5 seconds
                        </script>
                    <?php endif; ?>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                        <div class="alert alert-danger" id="alert-message">
                            <?php echo e($message); ?>

                        </div>

                        <script>
                            // Add a timer to automatically dismiss the alert after 5 seconds (adjust as needed)
                            setTimeout(function() {
                                document.getElementById('alert-message').style.display = 'none';
                            }, 5000); // 5000 milliseconds = 5 seconds
                        </script>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <!--end tab-pane-->
                        <div class="tab-pane active" id="changePassword" role="tabpanel">
                            <form action="<?php echo e(url('/change_password')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="oldpasswordInput" class="form-label">Old
                                                Password*</label>
                                            <input type="password" class="form-control"  name="current_password" id="oldpasswordInput"
                                                placeholder="Enter current password" value="<?php echo e(old('current_password')); ?>" required>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="newpasswordInput" class="form-label">New
                                                Password*</label>
                                            <input type="password" class="form-control"  name="password" id="newpasswordInput"
                                                placeholder="Enter new password" value="<?php echo e(old('password')); ?>" required>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput" class="form-label">Confirm
                                                Password*</label>
                                            <input type="password" class="form-control"  name="password_confirmation" id="confirmpasswordInput"
                                                placeholder="Confirm password" value="<?php echo e(old('password_confirmation')); ?>" required>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Change
                                                Password</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>

                        </div>
                        <div class="tab-pane" id="socialconfig" role="tabpanel">
                            <form action="<?php echo e(url('/social_config')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <input class="form-control" placeholder="" name="lab_director_nameid" value="<?php echo e($name ? $name->id : ''); ?>"  hidden="">
                                <div class="row g-2">
                                    <div class="col-lg-12">
                                        <div>
                                            <label for="lab_director_name" class="form-label">Lab director name </label>
                                            <input type="text" class="form-control"  name="lab_director_name" id="lab_director_name"
                                                placeholder="" value="<?php echo e($name->lab_director_name ?? ''); ?>" required>
                                        </div>
                                    </div>


                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/two0/public_html/resources/views/profile/settings.blade.php ENDPATH**/ ?>