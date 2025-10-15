<?php $__env->startSection('title'); ?>
    Edit Sample
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
<style>
    /* #liveTime {
        font-size: 24px;
        font-weight: bold;
        margin: 20px;
        color: #333;
        text-align: center;
    } */
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #eff2f7;
    }
</style>
    
    <?php echo $__env->make('layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row">


    </div>
    

    <div class="container m-auto">
        <div class="form-wrap">
            <header class="header">
                <h1 id="title" class="text-center">Edit Sample</h1>
            </header>
            <form class="tablelist-form" id="leadtype_form" action="<?php echo e(url('/sample/' . $sample->id)); ?>" method="Post" autocomplete="off" >
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="access_number" class="form-label">Access Number</label>
                            <input type="text" id="access_number" name="access_number" disabled  class="form-control"
                                required value="<?php echo e($sample->access_number); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                         <div class="form-group">
                            <label for="collected_date" class="form-label">Collected Date</label>
                            <input type="date" id="collected_date" name="collected_date" class="form-control"
                                required value="<?php echo e($sample->collected_date); ?>" />
                        </div>
                  </div>
                    <div class="col-md-6">
                         <div class="form-group">
                            <label for="received_date" class="form-label">Received Date </label>
                            <input type="date" class="form-control" id="received_date" name="received_date" value="<?php echo e($sample->received_date); ?>"/>
                        </div>
                     </div>
                    <div class="col-md-6">
                         <div class="form-group">
                            <label for="received_date" class="form-label">Time </label>
                            <input type="text" class="form-control"  value="<?php echo e($sample->received_time); ?>"/>
                        </div>
                     </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="doctor_id" class="form-label">Patient Name<a href=""
                                data-bs-toggle="modal" data-bs-target="#showModalPatient"
                                > <span class="badge bg-info text-white"> Add New</span> </a></label>
                                        <select class="js-example-basic-multiple form-control" name="patient_id" id="patient_id">
                                            <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $dateOfBirth = \Carbon\Carbon::parse($patient->dob)->format('d/m/Y');
                                            ?>
                                                <option value="<?php echo e($patient->id); ?>" <?php echo e($sample->patient_id == $patient->id ? 'selected' : ''); ?>>
                                                    <?php echo e($patient->first_name .' '. $patient->surname .' '. $dateOfBirth); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="doctor_id" class="form-label">Doctor Name  <a href=""
                                data-bs-toggle="modal" data-bs-target="#showModalDoctor"
                                > <span class="badge bg-info text-white"> Add New</span> </a></label>
                            <select class="js-example-basic-multiple form-control" name="doctor_id" id="doctor_id">
                                <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($doctor->id); ?>" <?php echo e($sample->doctor_id == $doctor->id ? 'selected' : ''); ?>>
                                        <?php echo e($doctor->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="institution_id" class="form-label">Institution <a href=""
                                data-bs-toggle="modal" data-bs-target="#showModalInstitution"
                                > <span class="badge bg-info text-white"> Add New</span> </a></label>
                            <select class="js-example-basic-multiple form-control" name="institution_id" id="institution_id">
                                <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($institution->id); ?>" <?php echo e($sample->institution_id == $institution->id ? 'selected' : ''); ?>>
                                    <?php echo e($institution->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="institution" class="form-label">Bill</label>
                            <select class="js-example-basic-multiple form-control" name="bill_to" id="bill_to">
                                
                                <option value="Patient" <?php echo e($sample->bill_to == 'Patient' ? 'selected' : ''); ?>>Patient</option>
                                <option value="Doctor"  <?php echo e($sample->bill_to == 'Doctor' ? 'selected' : ''); ?>>Doctor</option>
                                <option value="Other"   <?php echo e($sample->bill_to == 'Other' ? 'selected' : ''); ?>>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                

                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="test_profiles" class="form-label">Test Profiles</label>
                            <select class="js-example-basic-multiple" name="test_profiles[]" id="test_profiles" onchange="checkTestProfiles()"  multiple="multiple">
                                <?php $__currentLoopData = $test_profiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($test->id); ?>"  <?php $__currentLoopData = $sample->testProfiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($stest->id == $test->id ? 'selected' : ''); ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> data-cost="<?php echo e($test->cost); ?>">
                                        <?php echo e($test->name .' '. $test->cost); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="total_cost_profile" class="form-label">Total Cost</label>
                            <input type="text" class="form-control" name="total_cost_profile" id="total_cost_profile" value="<?php echo e($sample->profiles_total_cost); ?>" >
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="test_requested" class="form-label">Test Requested</label>
                            <select class="js-example-basic-multiple" name="test_requested[]" id="test_requested"  multiple="multiple">
                                <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($test->id); ?>"  <?php $__currentLoopData = $sample->tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($stest->id == $test->id ? 'selected' : ''); ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> data-cost="<?php echo e($test->cost); ?>">
                                        <?php echo e($test->name .' '. $test->specimen_type .' '. $test->cost); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="total_cost" class="form-label">Total Cost</label>
                            <input type="text" class="form-control" name="total_cost" id="total_cost" value="<?php echo e($sample->indvidualtests_total_cost); ?>" >
                        </div>
                    </div>
                </div>

                <!-- Row for Grand Total -->
                <div class="row align-items-center p-0" style="text-align: right">
                    <div class="col-md-10 form-group mt-2">
                        <label for="grand_total" class="form-label">Grand Total:</label>
                    </div>
                    <div class="col-md-2 p-0">
                        <div class="form-group">
                            <input type="text" class="form-control" name="grand_total" id="grand_total" value="<?php echo e($sample->grand_total_cost); ?>" >
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 d-flex justify-content-center">
                        <button type="submit" id="submit" class="btn btn-primary btn-sm btn-block submit-btn">Update Sample</button>
                    </div>
                </div>

            </form>
        </div>
    </div>


    

    <!--patient-modal-->
    <div class="modal fade" id="showModalPatient" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="leadtype_form" action="<?php echo e(url("/patient")); ?>" method="Post" autocomplete="off">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div>
                                    <label for="companyname-field"
                                        class="form-label">First Name</label>
                                    <input type="text" id="first_name" name="first_name"
                                        class="form-control"
                                        placeholder="Enter First Name" required />
                                </div>
                                
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="surname" class="form-label">Surname</label>
                                    <input type="text" id="surname" name="surname" class="form-control"
                                    placeholder="Enter surname" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" id="contact_number" class="form-control" name="contact_number"
                                        placeholder="Enter Contact Number" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="dob" class="form-label">DOB</label>
                                    <input type="date" id="dob" name="dob" class="form-control"
                                        placeholder="Enter Dob" required />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="Sex" class="form-label">Sex</label>
                                <div class="pt-2">
                                    <input type="radio" id="male" name="sex"
                                        placeholder="Enter Email" required  value="male"/>
                                        <label for="male" class="form-label">Male</label>
                                    <input type="radio" id="female" name="sex"
                                        placeholder="Enter Email" required value="female" />
                                    <label for="female" class="form-label">Female</label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-check form-check-dark mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                        id="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add Patient</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end Modal -->

    
    <div class="modal fade" id="showModalDoctor" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="leadtype_form" action="<?php echo e(url("/doctor")); ?>" method="Post" autocomplete="off">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div>
                                    <label for="companyname-field"
                                        class="form-label">Doctor’s Name</label>
                                    <input type="text" id="name" name="name"
                                        class="form-control"
                                        placeholder="Enter Doctor’s Name" required />
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
                            <div class="col-lg-12">
                                <div class="form-check form-check-dark mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                        id="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add Doctor</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    
    <div class="modal fade" id="showModalInstitution" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Institution</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="leadtype_form" action="<?php echo e(url("/institution")); ?>" method="Post" autocomplete="off">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div>
                                    <label for="companyname-field"
                                        class="form-label">Institution Name</label>
                                    <input type="text" id="name" name="name"
                                        class="form-control"
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

                            <div class="col-lg-12">
                                <div class="form-check form-check-dark mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                        id="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add Institution</button>
                        </div>
                    </div>
                </form>
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
        function checkTestProfiles() {
            // $('#test_requested').val('').trigger('change');
            // Get selected profile IDs
            let selectedProfiles = $('#test_profiles').val();
            if (selectedProfiles === null || selectedProfiles.length === 0) {
                console.log('No profiles selected, enabling all options');
                // Re-enable all options if no profiles are selected
                $('#test_profiles option').each(function() {
                    $(this).prop('disabled', false);
                });
                $('#test_requested option').each(function() {
                    $(this).prop('disabled', false);
                });
            }

            // Send AJAX request to the server
            $.ajax({
                url: '<?php echo e(route("checkTestsInProfiles")); ?>', // Your route here
                type: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>',
                    profiles: selectedProfiles
                },
                success: function(response) {
                    // Assuming the response contains the IDs of test profiles to hide
                    if (response.profilesToHide) {
                        $('#test_profiles option').each(function() {
                            // Disable the profiles that are in the profilesToHide array
                            if (response.profilesToHide.includes(parseInt($(this).val()))) {
                                $(this).prop('disabled', true);  // Disable the option
                            } else {
                                $(this).prop('disabled', false); // Enable the option
                            }
                        });
                        // Refresh the Select2 options
                        // $('#test_profiles').select2();
                        $('#test_requested option').each(function() {
                            // Disable the profiles that are in the profilesToHide array
                            if (response.testIdsInSelectedProfiles.includes(parseInt($(this).val()))) {
                                $(this).prop('disabled', true);  // Disable the option
                            } else {
                                $(this).prop('disabled', false); // Enable the option
                            }
                        });
                        // Refresh the Select2 options
                        // $('#test_requested').select2();
                    }
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }
        checkTestProfiles();
        $(document).ready(function() {
            $('#test_profiles').on('change', function() {
                $('#test_requested').val('').trigger('change');
            });

            let totalCost = 0;
            let total_cost_profile = 0;
            $('#test_requested').find('option:selected').each(function() {
                totalCost += parseFloat($(this).data('cost'));
            });
            $('#test_profiles').find('option:selected').each(function() {
                total_cost_profile += parseFloat($(this).data('cost'));
            });

            // Update the total_cost input field
            // $('#total_cost').val(totalCost.toFixed(2));

            function calculateGrandTotal() {
                let totalCost = parseFloat($('#total_cost').val()) || 0;
                let totalProfileCost = parseFloat($('#total_cost_profile').val()) || 0;
                let grandTotal = totalCost + totalProfileCost;

                $('#grand_total').val(grandTotal.toFixed(2));
            }

            // $('#total_cost_profile').val(total_cost_profile.toFixed(2));
            $('#test_requested').on('change', function() {
                let totalCost = 0;

                // Iterate through each selected option
                $(this).find('option:selected').each(function() {
                    totalCost += parseFloat($(this).data('cost'));
                });

                // Update the total_cost input field
                $('#total_cost').val(totalCost.toFixed(2));
                calculateGrandTotal();
            });
            $('#test_profiles').on('change', function() {
                let totalCost = 0;

                // Iterate through each selected option
                $(this).find('option:selected').each(function() {
                    totalCost += parseFloat($(this).data('cost'));
                });

                // Update the total_cost input field
                $('#total_cost_profile').val(totalCost.toFixed(2));
                calculateGrandTotal();
            });
            // calculateGrandTotal();
        });
        document.addEventListener("DOMContentLoaded", function() {
            let doctorSelect = document.getElementById('doctor_id');
            if (doctorSelect) {
                let lastOption = doctorSelect.options[doctorSelect.options.length - 1];
                if (!Array.from(doctorSelect.options).some(option => option.selected)) {
                    lastOption.selected = true;
                }
            }
        });
        // document.querySelector("#lead-image-input").addEventListener("change", function() {
        //     var preview = document.querySelector("#lead-img");
        //     var file = document.querySelector("#lead-image-input").files[0];
        //     console.log(file);
        //     var reader = new FileReader();
        //     reader.addEventListener("load", function() {
        //         preview.src = reader.result;
        //     }, false);
        //     if (file) {
        //         reader.readAsDataURL(file);
        //     }
        // });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/select2.init.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/two0/public_html/resources/views/setup/sample/edit.blade.php ENDPATH**/ ?>