<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span><?php echo app('translator')->get('translation.menu'); ?></span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link d-flex gap-3 align-middle" href="/">
                        <span class="">
                            <img src="<?php echo e(URL::asset('build/icons/dashboard.png')); ?>" alt="" height="18">
                        </span>
                        
                         <span class="pt-1">Dashboards</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link d-flex gap-3 align-middle" href="<?php echo e(url('/sample/create')); ?>">
                        <span class="">
                            <img src="<?php echo e(URL::asset('build/icons/sample.png')); ?>" alt="" height="18">
                        </span>
                        
                         <span class="pt-1">New Sample</span>
                    </a>
                </li>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('UserManagement access')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link d-flex gap-3 align-middle" href="#sidebarAuth" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuth">
                        <span class="">
                            <img src="<?php echo e(URL::asset('build/icons/users.png')); ?>" alt="" height="18">
                        </span>
                        
                         <span class="pt-1">Users</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAuth">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo e(url('/permissions')); ?>" class="nav-link">Permissions</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(url('/roles')); ?>" class="nav-link">Roles</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(url('/users')); ?>" class="nav-link">Users</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                
                    
                


                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['Practice Access'])): ?>
                    <li class="nav-item">
                        <a class="nav-link menu-link d-flex gap-3 align-middle" href="#sidebarSetup" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSetup">
                            <span class="">
                                <img src="<?php echo e(URL::asset('build/icons/assets.png')); ?>" alt="" height="18">
                            </span>
                            
                            <span class="pt-1">Assets</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSetup">
                            <ul class="nav nav-sm flex-column">
                                
                                <li class="nav-item">
                                    <a href="<?php echo e(url('/institution')); ?>" class="nav-link px-0 py-2">Institutions</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('/doctor')); ?>" class="nav-link px-0 py-2">Doctors</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('/patient')); ?>" class="nav-link px-0 py-2">Patients</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('/test')); ?>" class="nav-link px-0 py-2">Charges and Reference Ranges</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('/note')); ?>" class="nav-link px-0 py-2">Notes</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link menu-link d-flex gap-3 align-middle" href="#sidebarAuth" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuth">
                        <span class="">
                            <img src="<?php echo e(URL::asset('build/icons/report.png')); ?>" alt="" height="18">
                        </span>
                        
                         <span class="pt-1">Reports</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAuth">
                        <ul class="nav nav-sm flex-column">
                            
                        </ul>
                    </div>
                </li>

                
            </ul>
        </div>
        <div class="fixed-bottom mb-4">
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item ">
                    <a class="nav-link menu-link d-flex gap-2 align-middle" href="javascript:void();"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="">
                            <img src="<?php echo e(URL::asset('build/icons/md-log-out.png')); ?>" alt="" height="18">
                        </span>
                         <span class="text-white pt-1"> Logout</span>
                    </a>

                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>

<script>
    $(document).ready(function () {
        // Get the current path or URL
        var currentPath = window.location.pathname;

        // Specify the default active link
        var defaultActiveLink = '/';

        // Add the "active" class to the default menu item
        $('.nav-item .nav-link').each(function () {
            var linkPath = $(this).attr('href');

            // Check if the current path is the default active link
            if (currentPath === defaultActiveLink) {
                $('.nav-item .nav-link[href="/"]').addClass('active');
                $('.nav-item .nav-link[href="/"]').parents('.nav-item').addClass('active');
            }

            // Check if the current path includes the linkPath or vice versa
            else if (currentPath.includes(linkPath) || linkPath.includes(currentPath)) {
                $(this).addClass('active');

                // Handle the parent elements based on the structure
                $(this).parents('.collapse').addClass('show');
                $(this).parents('.nav-item').addClass('active');
            }
        });
    });
</script>

<?php /**PATH C:\laragon\www\LIS\LIS\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>