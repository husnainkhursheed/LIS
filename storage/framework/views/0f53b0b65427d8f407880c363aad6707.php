<?php $__env->startSection('title'); ?>
        Roles
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    
    <div class="row">
        <?php $__errorArgs = ['email'];
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
        <?php $__errorArgs = ['name'];
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
                }, 5000); //
            </script>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="col-lg-12">

            
                

                <div class="col">
                    <div class="card p-3 bg-white">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="text-dark">List of Users</h3>
                            <button type="button" class="btn btn-primary add-btn align-item-end ms-auto" data-bs-toggle="modal"
                                id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1 "></i> Add
                                User</button>
                        </div>

                        <div class="col my-2">
                            <nav class="navbar">
                                <div class="container-fluid p-0">
                                    <form class="d-flex" method="GET" action="<?php echo e(route('users.index')); ?>">
                                        <input class="form-control me-2 main-search" type="search" placeholder="Search"
                                            aria-label="Search" name="search" value="<?php echo e(request('search')); ?>">
                                        <button class="btn search-btn" type="submit">Search</button>
                                    </form>
                                    <form class="d-flex" method="GET" action="<?php echo e(route('users.index')); ?>">
                                        <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
                                        <select class="form-select sort-dropdown" aria-label="Default select example"
                                            name="sort_by" onchange="this.form.submit()">
                                            <option selected disabled>Sort By</option>
                                            <option value="name"
                                                <?php echo e(request('sort_by') == 'name' ? 'selected' : ''); ?>>Name</option>
                                            <option value="email"
                                                <?php echo e(request('sort_by') == 'email' ? 'selected' : ''); ?>>Email
                                            </option>
                                        </select>
                                    </form>
                                </div>
                            </nav>

                        </div>
                        <table id="" class="table table-striped display table-responsive rounded">
                            <thead>
                                <tr>
                                    <th class="rounded-start-3 ">Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th class="rounded-end-3 ">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($user->name); ?></td>
                                        <td><?php echo e($user->email); ?></td>
                                    <td>
                                        <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-primary-subtle text-white"><?php echo e($role->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>

                                        
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                    data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                    <a class="edit-item-btn" data-id="<?php echo e($user->id); ?>"  href="#showModal" data-bs-toggle="modal"><i
                                                            class="ri-pencil-fill align-bottom text-muted"></i></a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                    data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                    <a class="remove-item-btn" data-id="<?php echo e($user->id); ?>"  data-bs-toggle="modal"
                                                        href="#deleteRecordModal">
                                                        <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <ul class="pagination justify-content-center">
                            <?php if($users->previousPageUrl()): ?>
                                <li class="page-item previousPageUrl">
                                    <a class="page-link" href="<?php echo e($users->previousPageUrl()); ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item previousPageUrl disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                                </li>
                            <?php endif; ?>

                            <?php for($page = 1; $page <= $users->lastPage(); $page++): ?>
                                <li class="page-item <?php echo e($users->currentPage() == $page ? 'active' : ''); ?>">
                                    <a class="page-link"
                                        href="<?php echo e($users->url($page)); ?>"><?php echo e(str_pad($page, 2, '0', STR_PAD_LEFT)); ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if($users->nextPageUrl()): ?>
                                <li class="page-item nextPageUrl">
                                    <a class="page-link" href="<?php echo e($users->nextPageUrl()); ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item nextPageUrl disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&raquo;</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                
            
        </div>
    </div>



    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="leadtype_form" action="<?php echo e(url('/users')); ?>"
                    method="Post" autocomplete="off" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="modal-body">
                        <input type="hidden" id="id-field"/>
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div class="text-center">
                                    <div class="position-relative d-inline-block">
                                        <div class="position-absolute bottom-0 end-0">
                                            <label for="lead-image-input" class="mb-0" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                                <div class="avatar-xs cursor-pointer">
                                                    <div class="avatar-title bg-light border rounded-circle text-muted">
                                                        <i class="ri-image-fill"></i>
                                                    </div>
                                                </div>
                                            </label>
                                            <input class="form-control d-none"  id="lead-image-input" type="file"
                                                accept="image/png, image/gif, image/jpeg" name="userimage">
                                        </div>
                                        <div class="avatar-lg p-1">
                                            <div class="avatar-title bg-light rounded-circle">
                                                <img src="<?php echo e(URL::asset('build/images/users/user-dummy-img.jpg')); ?>"
                                        alt="" id="lead-img" class="avatar-md rounded-circle object-fit-cover" >
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="fs-13 mt-3">Profile</h5>
                                </div>

                            </div>

                            <!--end col-->
                            <div class="col-lg-12">
                                <div>
                                    <label for="user_password" class="form-label">
                                        Name</label>
                                    <input type="text" id="user_name" name="name"
                                        class="form-control"  required />
                                </div>
                                <br>
                                <div>
                                    <label for="user_email" class="form-label">
                                        Email</label>
                                    <input type="text" id="user_email" name="email"
                                        class="form-control"  required />

                                </div>
                                <br>
                                
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <label for="role_select" class="form-label">
                                    User Type</label>
                                <select class="js-example-basic-multiple" name="role_ids[]" id="role_ids" multiple="multiple">
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rolevalue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($rolevalue->id); ?>">
                                                <?php echo e($rolevalue->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add </button>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--end modal-->

<!-- Modal -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1"
        aria-labelledby="deleteRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                    </lord-icon>
                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold">You are about to delete a lead ?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your lead will
                            remove all of your information from our database.</p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button
                                class="btn btn-link link-success fw-medium text-decoration-none shadow-none"
                                data-bs-dismiss="modal" id="deleteRecord-close"><i
                                    class="ri-close-line me-1 align-middle"></i>
                                Close</button>
                            <button class="btn btn-danger" id="delete-record">Yes,
                                Delete It!!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>


    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="<?php echo e(URL::asset('build/js/pages/datatables.init.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script>
        jQuery(document).ready(function($) {
        // When the document is ready, attach a click event to the "Edit" button
        $('.edit-item-btn').on('click', function() {
            // Get the ID from the data attribute

            var itemId = $(this).data('id');
            var url = '<?php echo e(url("/users")); ?>' + '/' + itemId + '/edit';


            $.ajax({
                    url: url, // Adjust the route as needed
                    type: 'GET',
                    success: function(response) {
                        // Assuming the response has a 'leadType' key
                        var leadType = response.user;

                        // Now you can use the leadType data to populate your modal fields
                        $('#id-field').val(leadType.id);
                        $('#user_name').val(leadType.name);
                        $('#user_email').val(leadType.email);
                        $('#user_password').val(leadType.password);
                        var imagePath = leadType.avatar
                        ? '<?php echo e(URL::asset("uploads/")); ?>/' + leadType.avatar
                        : '<?php echo e(URL::asset("build/images/users/user-dummy-img.jpg")); ?>';
                        $('#lead-img').attr('src', imagePath);

                        // var roleIds = leadType.roles.map(function(role) {
                        //     return role.id;
                        // });

                        var roleIds = leadType.roles.map(function(role) {
                            return role.id;
                        });

                        $('#role_ids').val(roleIds).trigger('change');
                        // Set the checkbox state for is_active
                        $('#is_active').prop('checked', leadType.is_active);

                        // Update modal title
                        $('#exampleModalLabel').html("Edit User");

                        // Display the modal footer
                        $('#showModal .modal-footer').css('display', 'block');

                        // Change the button text
                        $('#add-btn').html("Update");
                        var form = $('#leadtype_form');

                        // Update the form action (assuming the form has an ID of 'your-form-id')
                        $('#leadtype_form').attr('action', '<?php echo e(url("/users")); ?>/' + itemId);

                        // $('#leadtype_form').attr('method', 'POST');
                        // if (!form.find('input[name="_method"]').length) {
                        //     // Add a hidden input field for the method
                        //     form.append('<input type="hidden" name="_method" id="patch" value="PATCH">');
                        // }
                        // $('#showModal').modal('show');

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                        // Handle errors if needed
                    }
                });

            // Populate the hidden field in the modal with the ID




            // Open the modal
        });

        function resetModal() {
            // Reset modal title
            $('#exampleModalLabel').html("Add User");

            // Display the modal footer
            $('#showModal .modal-footer').css('display', 'block');

            // Change the button text
            $('#add-btn').html("Add");
            $('#leadtype_form').attr('action', '<?php echo e(url("/users")); ?>');
            // if ( $('#patch').length) {
            //     $('#patch').remove();
            // }

            // Reset form fields
            $('#id-field').val('');
            $('#user_name').val('');
            $('#user_email').val('');
            $('#user_password').val('');
            $('#role_ids').val('');
            $('#is_active').prop('checked', true);
            $('#lead-img').attr('src', '<?php echo e(URL::asset("build/images/users/user-dummy-img.jpg")); ?>');
        }

        // Event listener for modal close event
        $('#showModal').on('hidden.bs.modal', function () {
            resetModal();
        });

        $('.remove-item-btn').on('click', function() {
            var itemId = $(this).data('id');
            $('#delete-record').attr('data-id', itemId);
        });

        $('#delete-record').on('click', function() {
            var itemId = $(this).data('id');
            var url = '/users/' + itemId;

            $.ajax({
                url: url,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Handle success, e.g., remove the deleted item from the UI
                    console.log(response);
                    $('#deleteRecordModal').modal('hide');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr, status, error);
                }
            });
        });


        // Function to reset modal when clicking the "Close" button
        $('#close-modal').on('click', function() {
            resetModal();
        });
            // setTimeout(function() {
            //     document.getElementById('alert-message').style.display = 'none';
            // }, 5000); // 5000 milliseconds = 5 seconds
    });

    document.querySelector("#lead-image-input").addEventListener("change", function () {
        var preview = document.querySelector("#lead-img");
        var file = document.querySelector("#lead-image-input").files[0];
        console.log(file);
        var reader = new FileReader();
        reader.addEventListener("load",function () {
            preview.src = reader.result;
        },false);
        if (file) {
            reader.readAsDataURL(file);
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/select2.init.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\LIS\LIS\resources\views/usermanagement/users.blade.php ENDPATH**/ ?>