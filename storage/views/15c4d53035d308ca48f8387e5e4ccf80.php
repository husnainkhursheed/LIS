
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.dashboards'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php
use \Carbon\Carbon;
?>
    
    <div class="row">
        <?php echo $__env->make('layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="col">
            <div class="card p-3 bg-white">
                <h3 class="text-dark">List of samples Being Processed</h3>
                <div class="col my-2">
                    <nav class="navbar">
                        <div class="container-fluid p-0">
                            <form class="d-flex" method="GET" action="<?php echo e(route('root')); ?>">
                                <input class="form-control me-2 main-search" type="search" placeholder="Search" aria-label="Search" name="search" value="<?php echo e(request('search')); ?>">
                                <button class="btn search-btn" type="submit">Search</button>
                            </form>
                            <form class="d-flex" method="GET" action="<?php echo e(route('root')); ?>">
                                <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
                                <select class="form-select sort-dropdown" aria-label="Default select example" name="sort_by" onchange="this.form.submit()">
                                    <option selected disabled>Sort By</option>
                                    <option value="test_number" <?php echo e(request('sort_by') == 'test_number' ? 'selected' : ''); ?>>Test Number</option>
                                    <option value="access_number" <?php echo e(request('sort_by') == 'access_number' ? 'selected' : ''); ?>>Access Number</option>
                                    <option value="received_date" <?php echo e(request('sort_by') == 'received_date' ? 'selected' : ''); ?>>Received date</option>
                                </select>

                                
                            </form>
                        </div>
                    </nav>

                </div>
                <table id="" class="table table-striped display table-responsive rounded">
                    <thead>
                        <tr>
                            <th class="rounded-start-3 ">Test #</th>
                            <th>Access #</th>
                            <th>Patient Name</th>
                            <th>Date Received</th>
                            <th class="rounded-end-3 ">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $samples; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sample): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($sample->test_number); ?></td>
                                <td><?php echo e($sample->access_number); ?></td>
                                <td><?php echo e("{$sample->patient->first_name} {$sample->patient->surname}"); ?></td>
                                <td><?php echo e(Carbon::parse($sample->received_date)->format('d-m-Y')); ?></td>



                                <td>
                                    <a href="#showModal" data-bs-toggle="modal">
                                        <span class="logo-sm">
                                            <img src="<?php echo e(URL::asset('build/images/report.png')); ?>" alt=""
                                                height="20">
                                        </span>
                                    </a>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Sample edit')): ?>
                                        <li class="list-inline-item" data-bs-toggle="tooltip"
                                            data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                            <a  id="edit-btn" class="edit-item-btn fs-5" data-id="<?php echo e($sample->id); ?>"  href="#showModal" data-bs-toggle="modal"><img src="<?php echo e(URL::asset('build/images/Vector.png')); ?>" alt=""
                                                height="20"></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Sample delete')): ?>
                                        <li class="list-inline-item" data-bs-toggle="tooltip"
                                            data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                            <a class="remove-item-btn" data-id="<?php echo e($sample->id); ?>"  data-bs-toggle="modal"
                                                href="#deleteRecordModal">
                                                <img src="<?php echo e(URL::asset('build/images/delete.png')); ?>" alt=""
                                                    height="20">
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                </td>
                                
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <ul class="pagination justify-content-center">
                    <?php if($samples->previousPageUrl()): ?>
                        <li class="page-item previousPageUrl">
                            <a class="page-link" href="<?php echo e($samples->previousPageUrl()); ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item previousPageUrl disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                        </li>
                    <?php endif; ?>

                    <?php for($page = 1; $page <= $samples->lastPage(); $page++): ?>
                        <li class="page-item <?php echo e($samples->currentPage() == $page ? 'active' : ''); ?>">
                            <a class="page-link" href="<?php echo e($samples->url($page)); ?>"><?php echo e(str_pad($page, 2, '0', STR_PAD_LEFT)); ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if($samples->nextPageUrl()): ?>
                        <li class="page-item nextPageUrl">
                            <a class="page-link" href="<?php echo e($samples->nextPageUrl()); ?>" aria-label="Next">
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
                        <h4 class="fs-semibold">You are about to delete a Sample ?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your Sample will
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
    <!-- apexcharts -->
    <script src="<?php echo e(URL::asset('build/libs/apexcharts/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/jsvectormap/maps/world-merc.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.js')); ?>"></script>
    <!-- dashboard init -->
    <script src="<?php echo e(URL::asset('build/js/pages/dashboard-ecommerce.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>

    <script>
        $(document).ready(function(){
        $('.edit-item-btn').click(function(){
            // Redirect to another page
            var itemId = $(this).data('id');
            // var itemId = $('#edit-btn').attr('data-id');
            console.log(itemId);
            var urlValue = '/sample/' + itemId + '/edit';
            // alert(urlValue);
            window.location.href = urlValue;
        });
    });

    jQuery(document).ready(function($) {
        $('.remove-item-btn').on('click', function() {
            var itemId = $(this).data('id');
            $('#delete-record').attr('data-id', itemId);
        });

        $('#delete-record').on('click', function() {
            var itemId = $(this).data('id');
            var url = '/sample/' + itemId;

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
    });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\LIS\LIS\resources\views/index.blade.php ENDPATH**/ ?>