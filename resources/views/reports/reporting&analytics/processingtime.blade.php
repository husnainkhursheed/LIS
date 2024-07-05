@extends('layouts.master')
@section('title')
    Reports
@endsection
@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    {{-- @component('components.breadcrumb')
        @slot('li_1')
            Assets
        @endslot
        @slot('title')
            Doctors
        @endslot
    @endcomponent --}}
    <style>
        .test-reports-dropdown {
            width: 50%;
            height: 33px;
            border-color: #ced4da;
            border-radius: 4px;
        }
    </style>
    <div class="row">

        @include('layouts.notification')

        <div class="card py-3 bg-white">
            <div class="card-header d-flex justify-content-between mb-4 py-2">
                <h3 class="text-dark">List of Reports Processing Time</h3>
            </div>
            <form class="mb-4" action="{{ route('processingtime.index') }}" method="GET">
                <div class="row d-flex align-items-end">
                    <div class="col-3">
                        <label for="date_from">Date From</label>
                        <input type="date" name="date_from" id="date_from" value="{{ $date_from }}"
                            class="form-control">
                    </div>
                    <div class="col-3">
                        <label for="date_to">Date To</label>
                        <input type="date" name="date_to" id="date_to" value="{{ $date_to }}"
                            class="form-control">
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn search-btn">Get</button>
                    </div>
                </div>
            </form>

            <div class="card-body">
                <table id="processing-time-table" class="display table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Test Number</th>
                            <th>Submitted at</th>
                            <th>Results entered at</th>
                            <th>Time taken (hours)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($processedSamples as $sample)
                            <tr>
                                <td>{{ $sample['test_number'] }}</td>
                                <td>{{ $sample['submitted_at'] }}</td>
                                <td>{{ $sample['results_entered_at'] ?? 'N/A' }}</td>
                                <td>{{ $sample['time_taken'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                        <h4 class="fs-semibold">You are about to delete a Doctor ?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your Doctor will
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
        $(document).ready(function() {


            $('.generate-pdf-link').click(function(e) {
                e.preventDefault();
                var testReportId = $(this).data('test-report-id');
                // var reportType = $('#report_type').val(); // Assuming you have a dropdown with id='report_type'
                var reportType = $(this).closest('tr').find('.test-reports-dropdown')
                    .val(); // Get the report type from the closest dropdown

                // Construct the URL dynamically
                var url = "{{ url('generate-pdf') }}/" + testReportId + "/" + reportType;

                // Set the href attribute of the anchor tag to the constructed URL
                $(this).attr('href', url);

                // Optional: Open the link in a new tab/window
                window.open(url, '_blank'); // This will open the URL in a new tab
            });




            var currentUser =
                "{{ Auth::user()->getRoleNames()->first() }}"; // Get the current user's ID from the server-side

            // Check if the current user is in the "Lab" role
            if (currentUser === 'Lab') {
                console.log('clicked');
                var labDepartments = {!! json_encode(Auth::user()->departments) !!}; // Get the department IDs associated with the user

                // Loop through each option in the select element
                $('#report_type option').each(function() {
                    var departmentId = $(this).val(); // Get the value of the option

                    // Check if the department ID is not in the user's associated departments
                    if (!labDepartments.includes(departmentId)) {
                        $(this).hide(); // Hide the option
                    }
                });
            }
        });
        jQuery(document).ready(function($) {
            $('#SaveReport').on('click', function(event) {
                event.preventDefault();
                var itemId = $(this).data('id');
                var url = '{{ url('/reports/test-reports') }}' + '/' + itemId;
                // Prevent the default link behavior
                var reporttypeis = $('#report_type').val();
                data = {
                    report_type: reporttypeis,
                };


                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        // Handle the success response
                        console.log('Success:', response);
                        // if (response.success) {
                        //     Toastify({
                        //         text: response.message,
                        //         gravity: 'top',
                        //         position: 'center',
                        //         duration: 5000,
                        //         close: true,
                        //         backgroundColor: '#40bb82',
                        //     }).showToast();
                        // } else {
                        //     var errors = response.message;
                        //     var errorMessage = errors.join('\n');
                        //     Toastify({
                        //         text: errors,
                        //         duration: 5000,
                        //         gravity: 'top',
                        //         position: 'left',
                        //         backgroundColor: '#ff4444',
                        //     }).showToast();
                        // }

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr, status, error);
                    }
                });
            });
            // When the document is ready, attach a click event to the "Edit" button
            $('.edit-item-btn').on('click', function() {

                // Get the ID from the data attribute
                event.preventDefault();
                var itemId = $(this).data('id');
                // var url = '{{ url('/reports/test-reports') }}' + '/' + itemId ;
                // Prevent the default link behavior
                $('#edittestreport' + itemId).submit();

                // var reporttypeis = $('#report_type').val();
                // data = {
                //     report_type: reporttypeis,
                // };
                // $.ajaxSetup({
                //     headers: {
                //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //     }
                // });

                // $.ajax({
                //     url: url,
                //     type: 'POST',
                //     data: data,
                //     success: function(response) {
                //         // Handle the success response
                //         console.log('Success:', response);
                //         // if (response.success) {
                //         //     Toastify({
                //         //         text: response.message,
                //         //         gravity: 'top',
                //         //         position: 'center',
                //         //         duration: 5000,
                //         //         close: true,
                //         //         backgroundColor: '#40bb82',
                //         //     }).showToast();
                //         // } else {
                //         //     var errors = response.message;
                //         //     var errorMessage = errors.join('\n');
                //         //     Toastify({
                //         //         text: errors,
                //         //         duration: 5000,
                //         //         gravity: 'top',
                //         //         position: 'left',
                //         //         backgroundColor: '#ff4444',
                //         //     }).showToast();
                //         // }

                //     },
                //     error: function(xhr, status, error) {
                //         console.error('Error:', xhr, status, error);

                //     }
                // });

                // var itemId = $(this).data('id');
                // var url = '{{ url('/reports/test-reports') }}';
                // // $('#leadtype_form').attr('action', url);
                // $.ajax({
                //         url: url, // Adjust the route as needed
                //         type: 'Post',
                //         success: function(response) {
                //             // Assuming the response has a 'leadType' key
                //             var sample = response.sample;
                //             var tests = response.sample.tests;
                //             // console.log("my practices ",sample);
                //             var testChargesSelect = $('#test_charges');
                //             testChargesSelect.empty(); // Clear existing options
                //             testChargesSelect.append('<option value="">Select Test Charges</option>'); // Add default option

                //             tests.forEach(function(test) {
                //                 var option = $('<option></option>')
                //                     .attr('value', test.id) // Adjust the value if needed
                //                     .text(test.name); // Adjust the text if needed
                //                 testChargesSelect.append(option);
                //             });

                //             // Update modal title
                //             // $('#exampleModalLabel').html("Edit Doctor");

                //             // Display the modal footer
                //             $('#showModal .modal-footer').css('display', 'block');

                //             // Change the button text
                //             // $('#add-btn').html("Update");
                //             var form = $('#leadtype_form');
                //             var url = '{{ url('/reports/test-reports') }}' + '/' + itemId ;
                //             $('#leadtype_form').attr('action', url);

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
