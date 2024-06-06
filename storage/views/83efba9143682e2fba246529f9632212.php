
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.password-reset'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<style>
    .input-group-text {
        cursor: pointer;
        z-index: 10;
    }
    .input-group .form-control {
        position: relative;
        z-index: 10;
    }
    .input-group .input-group-append {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
    }
    .input-group .input-group-append .input-group-text {
        border: none;
        background: none;
    }
    .invalid-feedback {
        position: absolute;
        width: 100%;
        top: 100%;
        left: 0;
    }
    </style>

    <div class="auth-page-wrapper pt-5">

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 text-white-20">
                            
                            <p class="mt-3 fs-3 fw-bold mb-0">Border Life - LIS</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4 card-bg-fill">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Create Password?</h5>
                                    

                                    <lord-icon src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop"
                                        colors="primary:#0ab39c" class="avatar-xl">
                                    </lord-icon>

                                </div>

                                
                                <div class="p-2">
                                    <form class="form-horizontal" method="POST" action="<?php echo e(route('password.update')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="token" value="<?php echo e($token); ?>">
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">Email</label>
                                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="useremail" name="email" placeholder="Enter email" value="<?php echo e($email ?? old('email')); ?>" id="email" readonly>
                                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo e($message); ?></strong>
                                            </span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label for="userpassword">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" id="userpassword" placeholder="Enter password" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-transparent border-0 cursor-pointer" id="toggle-password">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo e($message); ?></strong>
                                            </span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <div id="passwordHelpBlock" class="form-text">
                                                Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.
                                            </div>
                                        </div>

                                        <div class="mb-3 position-relative">
                                            <label for="password-confirm">Confirm Password</label>
                                            <div class="input-group">
                                                <input id="password-confirm" type="password" name="password_confirmation" class="form-control" placeholder="Enter confirm password" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-transparent border-0 cursor-pointer" id="toggle-confirm-password">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback" id="confirm-password-error">
                                                Passwords do not match.
                                            </div>
                                        </div>



                                        

                                        <div class="text-end">
                                            <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Create </button>
                                        </div>

                                    </form><!-- end form -->
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            Privacy Statement
                            Legal Notices
                            
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/particles.js/particles.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/particles.app.js')); ?>"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const password = document.getElementById('userpassword');
            const confirmPassword = document.getElementById('password-confirm');
            const passwordHelpBlock = document.getElementById('passwordHelpBlock');
            const confirmPasswordError = document.getElementById('confirm-password-error');
            const form = password.closest('form');

            const togglePassword = document.getElementById('toggle-password').querySelector('i');
            const toggleConfirmPassword = document.getElementById('toggle-confirm-password').querySelector('i');

            // Password validation regex
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/;

            // Function to check password strength
            function validatePassword() {
                if (passwordRegex.test(password.value)) {
                    password.classList.remove('is-invalid');
                    passwordHelpBlock.style.display = 'none';
                } else {
                    password.classList.add('is-invalid');
                    passwordHelpBlock.style.display = 'block';
                }
            }

            // Function to check if passwords match
            function validateConfirmPassword() {
                if (password.value === confirmPassword.value) {
                    confirmPassword.classList.remove('is-invalid');
                    confirmPasswordError.style.display = 'none';
                } else {
                    confirmPassword.classList.add('is-invalid');
                    confirmPasswordError.style.display = 'block';
                }
            }

            // Toggle password visibility
            function togglePasswordVisibility(input, icon) {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }

            // Event listeners for validation
            password.addEventListener('input', validatePassword);
            confirmPassword.addEventListener('input', validateConfirmPassword);

            // Event listener for toggling password visibility
            togglePassword.addEventListener('click', function() {
                togglePasswordVisibility(password, togglePassword);
            });

            toggleConfirmPassword.addEventListener('click', function() {
                togglePasswordVisibility(confirmPassword, toggleConfirmPassword);
            });

            // Prevent form submission if validations fail
            form.addEventListener('submit', function(event) {
                validatePassword();
                validateConfirmPassword();

                if (password.classList.contains('is-invalid') || confirmPassword.classList.contains('is-invalid')) {
                    event.preventDefault();
                }
            });
        });
    </script>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\LIS\LIS\resources\views/auth/passwords/reset.blade.php ENDPATH**/ ?>