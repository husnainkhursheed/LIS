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
    {{-- @component('components.breadcrumb')
        @slot('li_1')
            Assets
        @endslot
        @slot('title')
            Doctors
        @endslot
    @endcomponent --}}

    <div class="row">

        @include('layouts.notification')

        <div class="card px-5 py-3 bg-white">
            <div class="card-header d-flex justify-content-between mb-4 py-2">
                <h3 class="text-dark">List of Test Report</h3>
            </div>
            <form class="mb-4" action="{{ route('test-reports.index') }}" method="GET">
                <div class="row d-flex align-items-end">
                    <div class="col-3">
                        <label for="test_number">Test Number</label>
                        <input type="text" name="test_number" id="test_number" class="form-control">
                    </div>
                    <div class="col-3">
                        <label for="access_number">Access Number</label>
                        <input type="text" name="access_number" id="access_number" class="form-control">
                    </div>
                    <div class="col-4">
                        <label for="patient_name">Patient Name</label>
                        <input type="text" name="patient_name" id="patient_name" class="form-control">
                    </div>
                    <div class="col-2">

                        <button type="submit" class="btn search-btn">Search</button>

                    </div>
                </div>


            </form>


            <div class="col-lg-12">

                {{-- <div class="card "> --}}
                    <div class="col">
                        <div class="">
                            @if(isset($testReports))
                            <table id="" class="table table-striped display table-responsive rounded">
                                <thead>
                                    <tr>
                                        <th>Test #</th>
                                        <th>Access #</th>
                                        <th>Patient Name</th>
                                        <th>Date Received</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($testReports as $testReport)
                                        <tr>
                                            <td>{{ $testReport->test_number }}</td>
                                            <td>{{ $testReport->access_number }}</td>
                                            <td>{{ $testReport->patient->surname }}, {{ $testReport->patient->first_name }}</td>
                                            <td>{{ $testReport->received_date }}</td>
                                            <td>
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                        <a class="edit-item-btn" data-id="{{ $testReport->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                                class="ri-pencil-fill align-bottom text-muted"></i></a>
                                                    </li>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                        <a class="remove-item-btn" data-id="{{ $testReport->id }}"  data-bs-toggle="modal"
                                                            href="#deleteRecordModal">
                                                            <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                                {{-- <a href="{{ route('test-reports.edit', $testReport->id) }}" class="btn btn-warning">Edit</a>
                                                <a href="{{ route('test-reports.show', $testReport->id) }}" class="btn btn-info">View</a>
                                                <form action="{{ route('test-reports.destroy', $testReport->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                            <ul class="pagination justify-content-center">
                                @if ($testReports->previousPageUrl())
                                    <li class="page-item previousPageUrl">
                                        <a class="page-link" href="{{ $testReports->previousPageUrl() }}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item previousPageUrl disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                                    </li>
                                @endif

                                @for ($page = 1; $page <= $testReports->lastPage(); $page++)
                                    <li class="page-item {{ $testReports->currentPage() == $page ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ $testReports->url($page) }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                                    </li>
                                @endfor

                                @if ($testReports->nextPageUrl())
                                    <li class="page-item nextPageUrl">
                                        <a class="page-link" href="{{ $testReports->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item nextPageUrl disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&raquo;</a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                    </div>
                    {{-- <div class="card-body">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Telephone</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testReports as $doctor)
                                    <tr>
                                        <td>{{ $doctor->name }}</td>
                                        <td>{{ $doctor->contact_number }}</td>
                                        <td>{{ $doctor->address }}</td>
                                        <td>{{ $doctor->is_active == 1 ? 'Active' : 'InActive' }}</td>

                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                    data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                    <a class="edit-item-btn" data-id="{{ $doctor->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                            class="ri-pencil-fill align-bottom text-muted"></i></a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                    data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                    <a class="remove-item-btn" data-id="{{ $doctor->id }}"  data-bs-toggle="modal"
                                                        href="#deleteRecordModal">
                                                        <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                        </table>
                    </div> --}}
                {{-- </div> --}}
            </div>
     </div>
    </div>

    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0">
                {{-- <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div> --}}
                <form class="tablelist-form" id="leadtype_form" action="{{ url("/reports/test-reports") }}" method="post" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row">
                            <div class="col-lg-12">
                                <div>
                                    <label for="test_charges" class="form-label">Select charge items </label>
                                    <select class="form-control" name="test_charges" id="test_charges" required>
                                        <option value="">Select charge items </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <div>
                                    <label for="report_type" class="form-label">Select Report Type</label>
                                    <select class="form-control" name="report_type" id="report_type" required>
                                        <option value="">Select Report Type</option>
                                        <option value="1">Biochemistry / Haematology</option>
                                        <option value="2">Cytology / Gynecology</option>
                                        <option value="3">Urinalysis / Microbiology</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Edit Report</button>
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
                        <h4 class="fs-semibold">You are about to delete a Doctor ?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your Doctor will
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
            var url = '{{ url("/reports/test-reports") }}' + '/' + itemId + '/edit';
            // $('#leadtype_form').attr('action', url);
            $.ajax({
                    url: url, // Adjust the route as needed
                    type: 'GET',
                    success: function(response) {
                        // Assuming the response has a 'leadType' key
                        var sample = response.sample;
                        var tests = response.sample.tests;
                        // console.log("my practices ",sample);
                        var testChargesSelect = $('#test_charges');
                        testChargesSelect.empty(); // Clear existing options
                        testChargesSelect.append('<option value="">Select Test Charges</option>'); // Add default option

                        tests.forEach(function(test) {
                            var option = $('<option></option>')
                                .attr('value', test.id) // Adjust the value if needed
                                .text(test.name); // Adjust the text if needed
                            testChargesSelect.append(option);
                        });

                        // Update modal title
                        // $('#exampleModalLabel').html("Edit Doctor");

                        // Display the modal footer
                        $('#showModal .modal-footer').css('display', 'block');

                        // Change the button text
                        // $('#add-btn').html("Update");
                        var form = $('#leadtype_form');
                        var url = '{{ url("/reports/test-reports") }}' + '/' + itemId ;
                        $('#leadtype_form').attr('action', url);

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                        // Handle errors if needed
                    }
                });

        });



        function resetModal() {
            // Reset modal titleq
            $('#exampleModalLabel').html("Add Doctor");

            // Display the modal footer
            $('#showModal .modal-footer').css('display', 'block');

            // Change the button text
            $('#add-btn').html("Add");
            $('#leadtype_form').attr('action', '{{ url("/doctor") }}');
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
        $('#showModal').on('hidden.bs.modal', function () {
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
