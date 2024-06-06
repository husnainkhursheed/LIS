<div class="top-bar bg-white">
    <nav class="navbar navbar-light">
        <div class="container-fluid align-items-center d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <a class="navbar-brand ms-5" href="#">
                    <img src="{{ URL::asset('build/images/logo-lis.png') }}" alt="" height="50">
                </a>
                <span class="label-text ms-5">
                    Laboratory Information System
                </span>
            </div>
            <div class="dropdown ms-sm-3 header-item topbar-user">
                <button type="button" class="btn d-flex align-items-center" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-flex align-items-center">
                        <span class="text-end me-2">
                            <span class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text">{{ Auth::user()->first_name }}</span>
                            <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">ID Number</span>
                        </span>
                        <i class="bx bx-chevron-down font-size-16 align-middle"></i>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-power-off font-size-16 align-middle me-1"></i> <span key="t-logout">@lang('translation.logout')</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>
</div>



<header id="page-topbar" class="bg-transparent">
    <div class="layout-width">
        <div class="">
            <div class="d-flex border-0">

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                            <img src="{{ URL::asset('build/images/left-arrow.png') }}" alt="" height="26">
                    </span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- removeNotificationModal -->
<div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
