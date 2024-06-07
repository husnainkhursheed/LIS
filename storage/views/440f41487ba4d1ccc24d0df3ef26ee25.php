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
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('Sample create')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link d-flex gap-3 align-middle" href="<?php echo e(url('/sample/create')); ?>">
                        <span class="">
                            <img src="<?php echo e(URL::asset('build/icons/sample.png')); ?>" alt="" height="18">
                        </span>
                        
                         <span class="pt-1">New Sample</span>
                    </a>
                </li>
                <?php endif; ?>

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
                                <a href="<?php echo e(url('/roles')); ?>" class="nav-link px-0 py-2">Roles</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(url('/users')); ?>" class="nav-link px-0 py-2">Users</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                
                    
                


                
                
                    <li class="nav-item">
                        <a class="nav-link menu-link d-flex gap-3 align-middle" href="#sidebarSetup" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSetup">
                            <span class="">
                                <img src="<?php echo e(URL::asset('build/icons/assets.png')); ?>" alt="" height="18">
                            </span>
                            
                            <span class="pt-1">Assets</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSetup">
                            <ul class="nav nav-sm flex-column">
                                
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('Institution access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('/institution')); ?>" class="nav-link px-0 py-2">Institutions</a>
                                </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('Doctor access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('/doctor')); ?>" class="nav-link px-0 py-2">Doctors</a>
                                </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('Patient access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('/patient')); ?>" class="nav-link px-0 py-2">Patients</a>
                                </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('TestCharges access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('/test')); ?>" class="nav-link px-0 py-2">Charges and Reference Ranges</a>
                                </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('Notes access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('/note')); ?>" class="nav-link px-0 py-2">Notes</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </li>
                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('Manage TestReports')): ?>
                <li class="nav-item">

                    <a class="nav-link menu-link d-flex gap-3 align-middle" href="#sidebarreport" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarreport">

                        <span class="">
                            <img src="<?php echo e(URL::asset('build/icons/report.png')); ?>" alt="" height="18">
                        </span>
                        
                         <span class="pt-1">Reports</span>
                    </a>

                    <div class="collapse menu-dropdown" id="sidebarreport">
                        <ul class="nav nav-sm flex-column">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('Manage TestReports')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(url('/reports/test-reports')); ?>" class="nav-link">Test Reports</a>
                            </li>
                            <?php endif; ?>
                            

                        </ul>
                    </div>
                </li>

                
                <?php endif; ?>
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

    // Function to add the active class to the correct menu item
    function activateLink(linkPath) {
        // Remove active class from all nav-links
        $('.nav-item .nav-link').removeClass('active');
        // Remove active class from all nav-items
        $('.nav-item').removeClass('active');

        // Add active class to the current nav-link
        $('.nav-item .nav-link[href="' + linkPath + '"]').addClass('active');

        // Handle the parent elements based on the structure
        $('.nav-item .nav-link[href="' + linkPath + '"]').parents('.collapse').addClass('show');
        $('.nav-item .nav-link[href="' + linkPath + '"]').parents('.nav-item').addClass('active');
    }

    // Check if the current path is the default active link
    if (currentPath === defaultActiveLink) {
        activateLink(defaultActiveLink);
    } else {
        // Iterate through all nav-links to find the best match
        $('.nav-item .nav-link').each(function () {
            var linkPath = $(this).attr('href');

            // Ensure we check against the full URL without query strings or hashes
            var fullPath = new URL(linkPath, window.location.origin).pathname;

            // Check if the current path exactly matches the linkPath
            if (currentPath === fullPath) {
                activateLink(linkPath);
                return false; // Break the loop once a match is found
            }
        });

        // If no exact match is found, find the closest parent path match
        $('.nav-item .nav-link').each(function () {
            var linkPath = $(this).attr('href');
            var fullPath = new URL(linkPath, window.location.origin).pathname;

            if (currentPath.startsWith(fullPath) && fullPath !== defaultActiveLink) {
                activateLink(linkPath);
                return false; // Break the loop once a match is found
            }
        });
    }
});

</script>

<?php /**PATH C:\laragon\www\LIS\LIS\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>