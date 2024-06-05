@extends('layouts.master')
@section('title')
    Doctors
@endsection
@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
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
    {{-- //start  --}}
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg sticky-top navbar-light rounded" id="reportStickyNav">
            <div class="container-fluid">
                {{-- <a class="navbar-brand text-white" href="#">Navbar</a> --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center " id="navbarNav">

                    <ul class="navbar-nav gap-5">
                        <li class="nav-item border-nav px-5 rounded ">
                            <a class="nav-link active" aria-current="page" href="#">Find</a>
                        </li>
                        <li class="nav-item border-nav px-5 rounded ">
                            <a class="nav-link" href="#">Save</a>
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
                        <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Test Number</label>
                        <input type="text" id="test_number" name="test_number" class="form-control form-control-sm" value="ABC123" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="access_number" class="form-label">Access Number</label>
                        <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Collected date</label>
                        <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="access_number" class="form-label">Received date</label>
                        <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Time</label>
                        <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Bill to (Doctor, Patient, Other)</label>
                        <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="access_number" class="form-label">Doctor Name</label>
                        <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Tests Requested</label>
                        <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                    </div>
                </div>
            </div>
            {{-- BioChemistry / Haematology Test Results --}}
            <div class="card-header py-1">
                <h4 class="text-dark">BioChemistry / Haematology Test Results </h4>
            </div>
            <div class="row pt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="access_number" class="form-label">Reference</label>
                        {{-- <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly /> --}}
                        <textarea name="" id="" cols="30" rows="" class="form-control" value="ABC123" readonly></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Note</label>
                        <textarea name="" id="" cols="30" rows="" class="form-control" value="ABC123" readonly></textarea>
                        {{-- <input type="text" id="test_number" name="test_number" class="form-control form-control-sm" value="ABC123" readonly /> --}}
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
                                <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123"  />
                            </td>
                            <td>
                              <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123"  />
                            </td>
                            <td>
                               <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123"  />
                            </td>
                            <td>
                             <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123"  />
                            </td>
                            <td>
                             <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123"  />
                            </td>
                            {{-- <td>
                                    <ul class="list-inline hstack gap-2 mb-0">
                                        <li class="list-inline-item" data-bs-toggle="tooltip"
                                            data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                            <a class="edit-item-btn" data-id="{{ $sample->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                    class="ri-pencil-fill align-bottom text-muted"></i></a>
                                        </li>
                                        <li class="list-inline-item" data-bs-toggle="tooltip"
                                            data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                            <a class="remove-item-btn" data-id="{{ $sample->id }}"  data-bs-toggle="modal"
                                                href="#deleteRecordModal">
                                                <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td> --}}
                        </tr>
                </tbody>
            </table>
              {{-- Cytology / Gynecology Test Results  --}}
              <div class="card-header py-1">
                <h4 class="text-dark">Cytology / Gynecology Test Results </h4>
            </div>
            <div class="row pt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="access_number" class="form-label">History</label>
                        {{-- <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly /> --}}
                        <textarea name="" id="" cols="30" rows="" class="form-control" value="ABC123" readonly></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Last Period </label>
                        <input type="date" id="test_number" name="test_number" class="form-control form-control-sm" value="ABC123" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="access_number" class="form-label">Contraceptive</label>
                        <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Previous Pap </label>
                        <input type="date" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="access_number" class="form-label">Result </label>
                        <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Time</label>
                        <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                    </div>
                </div>
            </div>
            <div class="card-header py-1">
                <h4 class="text-dark">Notes </h4>
            </div>
            <div class="row pt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="access_number" class="form-label">Specimen Adequacy</label>
                        {{-- <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly /> --}}
                        <textarea name="" id="" cols="30" rows="" class="form-control" value="ABC123" readonly></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Diagnostic Interpretation</label>
                        <textarea name="" id="" cols="30" rows="" class="form-control" value="ABC123" readonly></textarea>
                        {{-- <input type="text" id="test_number" name="test_number" class="form-control form-control-sm" value="ABC123" readonly /> --}}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Recommend</label>
                        <textarea name="" id="" cols="30" rows="" class="form-control" value="ABC123" readonly></textarea>
                        {{-- <input type="text" id="test_number" name="test_number" class="form-control form-control-sm" value="ABC123" readonly /> --}}
                    </div>
                </div>
            </div>
             {{-- Urinalysis / Microbiology Test Results  --}}
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
                        <div class="d-flex">

                            <div class="flex-grow-1 ms-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="test_number" class="form-label">Bill to (Doctor, Patient, Other)</label>
                                            <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="access_number" class="form-label">Doctor Name</label>
                                            <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="test_number" class="form-label">Tests Requested</label>
                                            <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane" id="pill-justified-profile-1" role="tabpanel">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="test_number" class="form-label">Collected date</label>
                                            <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="access_number" class="form-label">Received date</label>
                                            <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="test_number" class="form-label">Time</label>
                                            <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="test_number" class="form-label">Bill to (Doctor, Patient, Other)</label>
                                            <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="access_number" class="form-label">Doctor Name</label>
                                            <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="test_number" class="form-label">Tests Requested</label>
                                            <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="pill-justified-messages-1" role="tabpanel">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="test_number" class="form-label">Bill to (Doctor, Patient, Other)</label>
                                            <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="access_number" class="form-label">Doctor Name</label>
                                            <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="test_number" class="form-label">Tests Requested</label>
                                            <input type="text" id="test_number" name="test_number" class="form-control" value="ABC123" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end card-body -->
        </div>
    </div>
@endsection
@section('script')
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

    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        jQuery(document).ready(function($) {
            // When the document is ready, attach a click event to the "Edit" button
            $('.edit-item-btn').on('click', function() {
                // Get the ID from the data attribute

                var itemId = $(this).data('id');
                var url = '{{ url('/reports/test-reports') }}' + '/' + itemId;
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
                $('#leadtype_form').attr('action', '{{ url('/doctor') }}');
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
@endsection
