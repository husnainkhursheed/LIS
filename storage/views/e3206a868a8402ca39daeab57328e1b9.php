<?php $__env->startSection('title'); ?>
    Doctors
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php
use \Carbon\Carbon;
?>
<?php $__env->startSection('content'); ?>
    <style>
        #reportStickyNav {
            top: 86px;
            background-color: #22416b;
        }

        .border-nav {
            font-size: .9375rem;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            border: 2px solid #3AAFE2;
            background-color: #3AAFE2;
        }

        .border-nav:hover {
            background-color: #22416b;
            transition: 0.3s;
        }
    </style>
    
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg sticky-top navbar-light rounded" id="reportStickyNav">
            <div class="container-fluid">
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center " id="navbarNav">

                    <ul class="navbar-nav gap-5">
                        <li class="nav-item border-nav px-5 rounded ">
                            <a class="nav-link active" aria-current="page" href="<?php echo e(url('/reports/test-reports')); ?>">Find</a>
                        </li>
                        <li class="nav-item border-nav px-5 rounded ">
                            <button class="nav-link" id="SaveReport">Save</button>
                        </li>
                        <li class="nav-item border-nav px-5 rounded ">
                            <a class="nav-link" href="#">Delete</a>
                        </li>
                        <li class="nav-item border-nav px-5 rounded ">
                            <a class="nav-link" href="#">Sign</a>
                        </li>
                        <li class="nav-item border-nav px-5 rounded ">
                            <a class="nav-link" href="#">Complete</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="card px-5 py-3 bg-white">
            <div class="card-header py-1">
                <h4 class="text-dark">Patient information</h4>
            </div>
            <div class="row pt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="access_number" class="form-label">Patient name</label>
                        <input type="text" id="access_number" name="access_number" class="form-control" value="<?php echo e($sample->patient->surname); ?> <?php echo e($sample->patient->first_name); ?>" disabled />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Test Number</label>
                        <input type="text" id="test_number" name="test_number" class="form-control form-control-sm" value="<?php echo e($sample->test_number); ?>" disabled />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="access_number" class="form-label">Access Number</label>
                        <input type="text" id="access_number" name="access_number" class="form-control" value="<?php echo e($sample->access_number); ?>" disabled />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="collected_date" class="form-label">Collected date</label>
                        <input type="text" id="collected_date" name="collected_date" class="form-control" value="<?php echo e(Carbon::parse($sample->collected_date)->format('d-m-Y')); ?>" disabled />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="received_date" class="form-label">Received date</label>
                        <input type="text" id="received_date" name="received_date" class="form-control" value="<?php echo e(Carbon::parse($sample->received_date)->format('d-m-Y')); ?>" disabled />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="received_time" class="form-label">Time</label>
                        <input type="text" id="received_time" name="received_time" class="form-control" value="<?php echo e($sample->received_time); ?>" disabled />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Bill to (Doctor, Patient, Other)</label>
                        <input type="text" id="test_number" name="test_number" class="form-control" value="<?php echo e($sample->bill_to); ?>" disabled />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="doctorname" class="form-label">Doctor Name</label>
                        <input type="text" id="doctorname" name="doctorname" class="form-control" value="<?php echo e($sample->doctor->name); ?>" disabled />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Tests Requested</label>
                        <?php
                        // Assuming $sample->tests is a collection or array of test objects
                            $testNames = $sample->tests->pluck('name')->implode(', ');
                        ?>
                        <input type="text" id="test_number" name="test_number" class="form-control" value="<?php echo e($testNames); ?>" disabled />
                    </div>
                </div>
            </div>
            <input type="hidden" id="reporttypeis" name="reporttypeis" value="<?php echo e($reporttype); ?>">
            <input type="hidden" id="testReport" name="testReport" value="<?php echo e($testReport->id); ?>">
            
            <?php if($reporttype == 1): ?>
                <div class="card-header py-1">
                    <h4 class="text-dark">BioChemistry / Haematology Test Results </h4>
                </div>
                <div class="row pt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reference" class="form-label">Reference</label>
                            
                            <textarea name="reference" id="reference" cols="30" rows="5" class="form-control" ><?php echo e($testReport->BiochemHaemoResults[0]->reference  ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="note" class="form-label">Note</label>
                            <textarea name="note" id="note" cols="30" rows="5" class="form-control"  ><?php echo e($testReport->BiochemHaemoResults[0]->note  ?? ''); ?></textarea>
                            
                        </div>
                    </div>
                </div>
                <table id="" class="table table-striped display table-responsive rounded">
                    <thead>
                        <tr>
                            <th class="rounded-start-3 ">Description</th>
                            <th>Test Results </th>
                            <th>Flag </th>
                            <th>Reference Range </th>
                            <th class="rounded-end-3 ">Test Notes </th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td>
                                    <input type="text" id="description" name="description" class="form-control" value="<?php echo e($test->name); ?>"  disabled/>
                                </td>
                                <td>
                                <input type="text" id="test_results" name="test_results" class="form-control" value="<?php echo e($testReport->BiochemHaemoResults[0]->test_results ?? ''); ?>"  />
                                </td>
                                <td>
                                <input type="text" id="flag" name="flag" class="form-control" value="<?php echo e($testReport->BiochemHaemoResults[0]->flag  ?? ''); ?>"  />
                                </td>
                                <td>
                                <input type="text" id="reference_range" name="reference_range" class="form-control" value="<?php echo e($test->reference_range); ?>"  disabled/>
                                </td>
                                <td>
                                <textarea  id="test_notes" name="test_notes" class="form-control"   ><?php echo e($testReport->BiochemHaemoResults[0]->test_notes  ?? ''); ?></textarea>
                                </td>
                                
                            </tr>
                    </tbody>
                </table>
            <?php endif; ?>

              
            <?php if($reporttype == 2): ?>
                <div class="card-header py-1">
                    <h4 class="text-dark">Cytology / Gynecology Test Results </h4>
                </div>
                <div class="row pt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="history" class="form-label">History</label>
                            
                            <textarea name="history" id="history" cols="30" rows="" class="form-control" value="" ><?php echo e($testReport->cytologyGynecologyResults[0]->history  ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="last_period" class="form-label">Last Period </label>
                            <input type="date" id="last_period" name="last_period" class="form-control form-control-sm" value="<?php echo e($testReport->cytologyGynecologyResults[0]->last_period  ?? ''); ?>"  />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contraceptive" class="form-label">Contraceptive <a href=""
                                data-bs-toggle="modal" data-bs-target="#showModalInstitution"
                                > <span class="badge bg-info text-white"> Add New</span> </a>
                            </label>
                            <select class="js-example-basic-multiple form-control" name="contraceptive" id="contraceptive">
                                
                            </select>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="previous_pap" class="form-label">Previous Pap </label>
                            <input type="date" id="previous_pap" name="previous_pap" class="form-control" value="<?php echo e($testReport->cytologyGynecologyResults[0]->previous_pap  ?? ''); ?>"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="result" class="form-label">Result </label>
                            <input type="text" id="result1" name="result" class="form-control" value="<?php echo e($testReport->cytologyGynecologyResults[0]->result  ?? ''); ?>"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cervix_examination" class="form-label">Cervix Examination </label>
                            <textarea type="text" id="cervix_examination" name="cervix_examination" class="form-control" value="" ><?php echo e($testReport->cytologyGynecologyResults[0]->cervix_examination  ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-header py-1">
                    <h4 class="text-dark">Notes </h4>
                </div>
                <div class="row pt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="specimen_adequacy" class="form-label">Specimen Adequacy</label>
                            
                            <textarea name="specimen_adequacy" id="specimen_adequacy" cols="" rows="5" class="form-control" value="" ><?php echo e($testReport->cytologyGynecologyResults[0]->specimen_adequacy  ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="diagnostic_interpretation" class="form-label">Diagnostic Interpretation</label>
                            <textarea name="diagnostic_interpretation" id="diagnostic_interpretation" cols="30" rows="5" class="form-control"><?php echo e($testReport->cytologyGynecologyResults[0]->diagnostic_interpretation  ?? ''); ?></textarea>
                            
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="recommend" class="form-label">Recommend</label>
                            <textarea name="recommend" id="recommend" cols="30" rows="5" class="form-control"><?php echo e($testReport->cytologyGynecologyResults[0]->recommend  ?? ''); ?></textarea>
                            
                        </div>
                    </div>
                </div>
            <?php endif; ?>
             
            <?php if($reporttype == 3): ?>
                <div class="card-header py-1">
                    <h4 class="text-dark">Urinalysis / Microbiology Test Results </h4>
                </div>
                <div class="">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link waves-effect waves-light" data-bs-toggle="tab" href="#pill-justified-home-1" role="tab" aria-selected="false" tabindex="-1">
                                Chemical Analysis
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link waves-effect waves-light active" data-bs-toggle="tab" href="#pill-justified-profile-1" role="tab" aria-selected="true">
                                Microscopy
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link waves-effect waves-light" data-bs-toggle="tab" href="#pill-justified-messages-1" role="tab" aria-selected="false" tabindex="-1">
                                Specimen
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content text-muted">
                        <div class="tab-pane active show" id="pill-justified-home-1" role="tabpanel">
                            

                        </div>
                        <div class="tab-pane" id="pill-justified-profile-1" role="tabpanel">
                            
                        </div>
                        <div class="tab-pane" id="pill-justified-messages-1" role="tabpanel">
                            
                        </div>
                    </div>
                </div><!-- end card-body -->
            <?php endif; ?>
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
                var url = '<?php echo e(url('/reports/test-reports')); ?>' + '/' + itemId;
                $('#leadtype_form').attr('action', url);
                // $.ajax({
                //         url: url, // Adjust the route as needed
                //         type: 'GET',
                //         success: function(response) {
                //             // Assuming the response has a 'leadType' key
                //             var doctor = response.doctor;
                //             console.log("my practices ",doctor);

                //             // Now you can use the leadType data to populate your modal fields
                //             $('#id-field').val(doctor.id);
                //             $('#name').val(doctor.name);
                //             // $('#phone').val(doctor.phone);
                //             $('#contact_number').val(doctor.contact_number);
                //             $('#street_name').val(doctor.street_name);
                //             $('#address_line_2').val(doctor.address_line_2);
                //             $('#area').val(doctor.area);
                //             $('#email').val(doctor.email);

                //             // var surgeries = SetupPractice.surgeries.map(function(surgery) {
                //             //         return surgery.id;
                //             //     });

                //             // $('#surgeries').val(surgeries).trigger('change');


                //             // Set the checkbox town for is_active
                //             // $('#is_active').prop('checked', SetupPractice.is_active);

                //             // Update modal title
                //             $('#exampleModalLabel').html("Edit Doctor");

                //             // Display the modal footer
                //             $('#showModal .modal-footer').css('display', 'block');

                //             // Change the button text
                //             $('#add-btn').html("Update");
                //             var form = $('#leadtype_form');

                //             // Update the form action (assuming the form has an ID of 'your-form-id')




                //             // $('#showModal').modal('show');

                //         },
                //         error: function(xhr, status, error) {
                //             console.error(xhr, status, error);
                //             // Handle errors if needed
                //         }
                //     });

            });



            function resetModal() {
                // Reset modal titleq
                $('#exampleModalLabel').html("Add Doctor");

                // Display the modal footer
                $('#showModal .modal-footer').css('display', 'block');

                // Change the button text
                $('#add-btn').html("Add");
                $('#leadtype_form').attr('action', '<?php echo e(url('/doctor')); ?>');
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
                var url = '/doctor/' + itemId;

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

        $(document).ready(function() {
            // Set CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#SaveReport').on('click', function(event) {
                console.log('clicked')
                event.preventDefault(); // Prevent the default link behavior
                var reporttypeis = $('#reporttypeis').val();
                var data = {};

                // Gather data from the form based on report type
                if (reporttypeis == 1) {
                    data = {
                        testReport: $('#testReport').val(),
                        reporttype: reporttypeis,
                        reference: $('#reference').val(),
                        note: $('#note').val(),
                        description: $('#description').val(),
                        test_results: $('#test_results').val(),
                        flag: $('#flag').val(),
                        reference_range: $('#reference_range').val(),
                        test_notes: $('#test_notes').val()
                    };
                } else if (reporttypeis == 2) {
                    data = {
                        testReport: $('#testReport').val(),
                        reporttype: reporttypeis,
                        history: $('#history').val(),
                        last_period: $('#last_period').val(),
                        contraceptive: $('#contraceptive').val(),
                        result: $('#result1').val(),
                        previous_pap: $('#previous_pap').val(),
                        cervix_examination: $('#cervix_examination').val(),
                        specimen_adequacy: $('#specimen_adequacy').val(),
                        diagnostic_interpretation: $('#diagnostic_interpretation').val(),
                        recommend: $('#recommend').val()
                    };
                }

                $.ajax({
                    url: '/reports/save-reports',
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        // Handle the success response
                        console.log('Success:', response);
                        if (response.success) {
                            Toastify({
                                text: response.message,
                                gravity: 'top',
                                position: 'center',
                                duration: 5000,
                                close: true,
                                backgroundColor: '#40bb82',
                            }).showToast();
                        } else {
                            var errors = response.message;
                            var errorMessage = errors.join('\n');
                            Toastify({
                                text: errors,
                                duration: 5000,
                                gravity: 'top',
                                position: 'left',
                                backgroundColor: '#ff4444',
                            }).showToast();
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr, status, error);

                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/select2.init.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\LIS\LIS\resources\views/reports/test-reports/edit.blade.php ENDPATH**/ ?>