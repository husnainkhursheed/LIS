<?php $__env->startSection('title'); ?>
    Institution
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    
    <div class="row">

        <?php echo $__env->make('layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="col-lg-12">

            

            

            <div class="col">
                <div class="card p-3 bg-white">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="text-dark">List of Institutions</h3>
                        <button type="button" class="btn btn-primary add-btn align-item-end ms-auto" data-bs-toggle="modal"
                            id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1 "></i> Add
                            Institution</button>
                    </div>

                    <div class="col my-2">
                        <nav class="navbar">
                            <div class="container-fluid p-0">
                                <form class="d-flex" method="GET" action="<?php echo e(route('institution.index')); ?>">
                                    <input class="form-control me-2 main-search" type="search" placeholder="Search"
                                        aria-label="Search" name="search" value="<?php echo e(request('search')); ?>">
                                    <button class="btn search-btn" type="submit">Search</button>
                                </form>
                                <form class="d-flex" method="GET" action="<?php echo e(route('institution.index')); ?>">
                                    <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
                                    <select class="form-select sort-dropdown" aria-label="Default select example"
                                        name="sort_by" onchange="this.form.submit()">
                                        <option selected disabled>Sort By</option>
                                        <option value="name"
                                            <?php echo e(request('sort_by') == 'name' ? 'selected' : ''); ?>>Name</option>
                                        <option value="contact_number"
                                            <?php echo e(request('sort_by') == 'contact_number' ? 'selected' : ''); ?>>Contact Number
                                        </option>
                                        <option value="address_line_2"
                                            <?php echo e(request('sort_by') == 'address_line_2' ? 'selected' : ''); ?>>Address
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
                                <th>Telephone</th>
                                <th>Address</th>
                                <th class="rounded-end-3 ">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($institution->name); ?></td>
                                    <td><?php echo e($institution->contact_number); ?></td>
                                    <td><?php echo e($institution->address_line_2); ?></td>



                                    <td>
                                        <a href="#showModal" data-bs-toggle="modal">
                                            <span class="logo-sm">
                                                <img src="<?php echo e(URL::asset('build/images/report.png')); ?>" alt=""
                                                    height="20">
                                            </span>
                                        </a>
                                        <a href="">
                                            <span class="logo-sm">
                                                <img src="<?php echo e(URL::asset('build/images/Vector.png')); ?>" alt=""
                                                    height="20">
                                            </span>
                                        </a>
                                        <a href="">
                                            <span class="logo-sm">
                                                <img src="<?php echo e(URL::asset('build/images/delete.png')); ?>" alt=""
                                                    height="20">
                                            </span>
                                        </a>
                                    </td>
                                    
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <ul class="pagination justify-content-center">
                        <?php if($institutions->previousPageUrl()): ?>
                            <li class="page-item previousPageUrl">
                                <a class="page-link" href="<?php echo e($institutions->previousPageUrl()); ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="page-item previousPageUrl disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                            </li>
                        <?php endif; ?>

                        <?php for($page = 1; $page <= $institutions->lastPage(); $page++): ?>
                            <li class="page-item <?php echo e($institutions->currentPage() == $page ? 'active' : ''); ?>">
                                <a class="page-link"
                                    href="<?php echo e($institutions->url($page)); ?>"><?php echo e(str_pad($page, 2, '0', STR_PAD_LEFT)); ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if($institutions->nextPageUrl()): ?>
                            <li class="page-item nextPageUrl">
                                <a class="page-link" href="<?php echo e($institutions->nextPageUrl()); ?>" aria-label="Next">
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

    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Institution</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="leadtype_form" action="<?php echo e(url('/institution')); ?>" method="Post"
                    autocomplete="off">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div>
                                    <label for="companyname-field" class="form-label">Institution Name</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Enter Institution Name" required />
                                </div>
                                
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="Enter Email" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="address" class="form-label">Contact Number</label>
                                    <input type="text" id="contact_number" name="contact_number" class="form-control"
                                        placeholder="Enter Contact Number" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="town" class="form-label">Street name</label>
                                    <input type="text" id="street_name" class="form-control" name="street_name"
                                        placeholder="Enter Town" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="zip" class="form-label">Second line in address</label>
                                    <input type="text" id="address_line_2" name="address_line_2" class="form-control"
                                        placeholder="Enter Address" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="country" class="form-label">Area</label>
                                    <input type="text" id="area" name="area" class="form-control"
                                        placeholder="Enter Area" required />
                                </div>
                            </div>

                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add Institution</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!--end modal-->

    <!-- Modal -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordLabel"
        aria-hidden="true">
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
                        <h4 class="fs-semibold">You are about to delete a Institution ?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your Institution will
                            remove all of your information from our database.</p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none"
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
                var url = '<?php echo e(url('/institution')); ?>' + '/' + itemId + '/edit';


                $.ajax({
                    url: url, // Adjust the route as needed
                    type: 'GET',
                    success: function(response) {
                        // Assuming the response has a 'leadType' key
                        var institution = response.institution;
                        console.log("my practices ", institution);

                        // Now you can use the leadType data to populate your modal fields
                        $('#id-field').val(institution.id);
                        $('#name').val(institution.name);
                        // $('#phone').val(institution.phone);
                        $('#contact_number').val(institution.contact_number);
                        $('#street_name').val(institution.street_name);
                        $('#address_line_2').val(institution.address_line_2);
                        $('#area').val(institution.area);
                        $('#email').val(institution.email);

                        // var surgeries = SetupPractice.surgeries.map(function(surgery) {
                        //         return surgery.id;
                        //     });

                        // $('#surgeries').val(surgeries).trigger('change');


                        // Set the checkbox town for is_active
                        // $('#is_active').prop('checked', SetupPractice.is_active);

                        // Update modal title
                        $('#exampleModalLabel').html("Edit Institution");

                        // Display the modal footer
                        $('#showModal .modal-footer').css('display', 'block');

                        // Change the button text
                        $('#add-btn').html("Update");
                        var form = $('#leadtype_form');

                        // Update the form action (assuming the form has an ID of 'your-form-id')
                        $('#leadtype_form').attr('action', '<?php echo e(url('/institution')); ?>/' +
                            itemId);



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
                // Reset modal titleq
                $('#exampleModalLabel').html("Add Institution");

                // Display the modal footer
                $('#showModal .modal-footer').css('display', 'block');

                // Change the button text
                $('#add-btn').html("Add");
                $('#leadtype_form').attr('action', '<?php echo e(url('/institution')); ?>');
                // if ( $('#patch').length) {
                //     $('#patch').remove();
                // }
                $('#id-field').val('');
                $('#name').val('');
                // $('#phone').val('');
                $('#contact_number').val('');
                $('#street_name').val('');
                $('#address_line_2').val('');
                $('#area').val('');
                $('#email').val('');
                // $('#surgeries').val("");
                // $('#surgeries').val("").trigger('change');

            }

            // Event listener for modal close event
            $('#showModal').on('hidden.bs.modal', function() {
                resetModal();
            });

            $('.remove-item-btn').on('click', function() {
                var itemId = $(this).data('id');
                $('#delete-record').attr('data-id', itemId);
            });

            $('#delete-record').on('click', function() {
                var itemId = $(this).data('id');
                var url = '/institution/' + itemId;

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
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/select2.init.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\LIS\LIS\resources\views/setup/institution.blade.php ENDPATH**/ ?>