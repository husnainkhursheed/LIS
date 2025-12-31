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
@php
use \Carbon\Carbon;
@endphp

<style>
    .test-reports-dropdown{
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
                <h3 class="text-dark">List of Audit Trails</h3>
                {{-- <a href=""  data-bs-toggle="modal" data-bs-target="#auditModal" class="btn btn-primary"> Audit Trail modal </a> --}}
            </div>

            <div class="col-lg-12">

                {{-- <div class="card "> --}}
                    <div class="col">
                        <div class="">
                            {{-- @if(isset($testReports)) --}}
                            <table id="" class="table table-striped display table-responsive rounded">
                                <thead>
                                    <tr>
                                        <th>Test #</th>
                                        <th>User Name</th>
                                        <th>Date &Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($records))
                                    @foreach ($records as $record)
                                    <tr>
                                        {{-- {{dd($record->id)}} --}}
                                        @if ($reporttype == 1)
                                            <td>{{ $record->biochemResult?->testReport?->test?->name }}</td>

                                        @elseif ($reporttype == 2)
                                            <td>{{ $record->cytologyResult?->testReport?->test?->name }}</td>

                                        @elseif ($reporttype == 3)
                                            <td>{{ $record->urinalysisResult?->testReport?->test?->name }}</td>
                                        @endif
                                        {{-- <td>{{$record->testReport->test->name}}</td> --}}
                                        <td>{{$record->user->first_name ." ". $record->user->surname}}</td>
                                        <td>{{$record->changed_at}}</td>
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                    data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                    <a class="edit-item-btn" data-id="{{$record->test_report_id}}" data-changedat="{{$record->changed_at}}"  href="#showModal" data-bs-target="#auditModal" data-bs-toggle="modal"><i
                                                            class="ri-pencil-fill align-bottom text-muted"></i></a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            {{-- @endif --}}
                            {{-- <ul class="pagination justify-content-center">
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
                            </ul> --}}
                        </div>

                    </div>

                 </div>
            </div>
     </div>
    </div>

    {{-- Audit Traits modal  --}}
    <div class="modal right fade" id="auditModal" tabindex="-1" aria-labelledby="auditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="auditModalLabel">All Changes List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="changestable" class="table table-striped display table-responsive rounded">
                        <thead>
                            <tr>
                                <th>Field name </th>
                                <th>From value</th>
                                <th>To value</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    {{-- <div id="notes-container">
                    </div> --}}
                </div>
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
        $(document).ready(function() {


            $('.generate-pdf-link').click(function(e) {
                e.preventDefault();
                var testReportId = $(this).data('test-report-id');
                var reportType = $('#report_type').val(); // Assuming you have a dropdown with id='report_type'

                // Construct the URL dynamically
                var url = "{{ url('generate-pdf') }}/" + testReportId + "/" + reportType;

                // Set the href attribute of the anchor tag to the constructed URL
                $(this).attr('href', url);

                // Optional: Open the link in a new tab/window
                window.open(url, '_blank'); // This will open the URL in a new tab
            });




            var currentUser = "{{ Auth::user()->getRoleNames()->first() }}"; // Get the current user's ID from the server-side

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
            var url = '{{ url("/reports/test-reports") }}' + '/' + itemId ;
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
        $('.edit-item-btn').on('click', function () {

            var itemId    = $(this).data('id');
            var changedat = $(this).data('changedat');

            var url = '{{ url('reports/changes') }}/' + itemId;

            $.ajax({
                url: url,
                type: 'GET',
                data: { changedat: changedat },

                success: function (response) {

                    $('#changestable tbody').empty();

                    response.changes_made.forEach(function (change) {

                        var field_name = change.field_name ?? '';

                        var from_value = formatAuditValue(change.from_value, field_name);
                        var to_value   = formatAuditValue(change.to_value, field_name);

                        var row = `
                            <tr>
                                <td class="fw-bold">${formatFieldName(field_name)}</td>
                                <td>${from_value}</td>
                                <td>${to_value}</td>
                            </tr>
                        `;

                        $('#changestable tbody').append(row);
                    });
                },

                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        });

        /**
         * Format field names to be more readable
         */
        function formatFieldName(fieldName) {
            const fieldLabels = {
                'sensitivity': 'Sensitivity',
                'sensitivity_profiles': 'Sensitivity Profiles',
                'review': 'Review',
                'procedures': 'Procedures'
            };

            return fieldLabels[fieldName] || fieldName.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        /**
         * Format audit value
         * - Detect JSON
         * - Format procedures, arrays, objects
         * - Handle special cases for sensitivity and procedures
         * - Fallback to plain text
         */
        function formatAuditValue(value, fieldName) {

            if (!value || value === 'null' || value === '[]' || value === '{}') {
                return '<em class="text-muted">—</em>';
            }

            // Try JSON decode
            try {
                let parsed = JSON.parse(value);

                // ==========================
                // PROCEDURES (Array format)
                // ==========================
                if (fieldName === 'procedures' && Array.isArray(parsed)) {
                    if (parsed.length === 0) {
                        return '<em class="text-muted">No procedures</em>';
                    }

                    let html = '<ul class="list-unstyled mb-0">';

                    parsed.forEach((item, index) => {
                        html += '<li class="mb-2 p-2 border rounded bg-light">';
                        html += `<div class="mb-1"><span class="badge bg-secondary">#${index + 1}</span></div>`;

                        if (item.procedure) {
                            html += `<div><strong>Procedure:</strong> ${escapeHtml(item.procedure)}</div>`;
                        }

                        if (item.specimen_note) {
                            html += `<div><strong>Note:</strong> ${escapeHtml(item.specimen_note)}</div>`;
                        }

                        html += '</li>';
                    });

                    html += '</ul>';
                    return html;
                }

                // ==========================
                // SENSITIVITY / SENSITIVITY PROFILES
                // ==========================
                if (fieldName === 'sensitivity' || fieldName === 'sensitivity_profiles') {

                    // If it's an array, process each item
                    if (Array.isArray(parsed)) {
                        if (parsed.length === 0) {
                            return '<em class="text-muted">None</em>';
                        }

                        let html = '<div class="mb-0">';

                        parsed.forEach((item, index) => {
                            // Each item should be an object with microorganism and items
                            if (typeof item === 'object' && item !== null) {

                                if (index > 0) {
                                    html += '<hr class="my-2">';
                                }

                                html += '<div class="p-2 border rounded bg-light mb-2">';

                                // Display microorganism
                                if (item.microorganism) {
                                    html += `<div class="mb-2"><strong class="text-primary">Microorganism:</strong> ${escapeHtml(item.microorganism)}</div>`;
                                }

                                // Display items (antibiotics)
                                if (item.items && Array.isArray(item.items)) {
                                    html += '<div><strong>Antibiotics:</strong></div>';
                                    html += '<table class="table table-sm table-bordered mt-1 mb-0">';
                                    html += '<thead class="table-light"><tr><th>Antibiotic</th><th>MIC</th><th>Sensitivity</th></tr></thead>';
                                    html += '<tbody>';

                                    item.items.forEach(antibiotic => {
                                        if (typeof antibiotic === 'object') {
                                            let sensitivityClass = '';
                                            let sensitivity = antibiotic.sensitivity || '';

                                            if (sensitivity.toLowerCase() === 'sensitive') {
                                                sensitivityClass = 'text-success';
                                            } else if (sensitivity.toLowerCase() === 'resistant') {
                                                sensitivityClass = 'text-danger';
                                            } else if (sensitivity.toLowerCase() === 'intermediate') {
                                                sensitivityClass = 'text-warning';
                                            }

                                            html += '<tr>';
                                            html += `<td>${escapeHtml(antibiotic.antibiotic || '')}</td>`;
                                            html += `<td>${escapeHtml(antibiotic.mic || '')}</td>`;
                                            html += `<td class="${sensitivityClass} fw-bold">${escapeHtml(sensitivity)}</td>`;
                                            html += '</tr>';
                                        }
                                    });

                                    html += '</tbody></table>';
                                }

                                html += '</div>';
                            }
                        });

                        html += '</div>';
                        return html;
                    }
                }

                // ==========================
                // GENERIC ARRAY
                // ==========================
                if (Array.isArray(parsed)) {
                    if (parsed.length === 0) {
                        return '<em class="text-muted">Empty</em>';
                    }

                    let html = '<ul class="list-unstyled mb-0">';

                    parsed.forEach((item, index) => {
                        if (typeof item === 'object') {
                            html += '<li class="mb-2 p-2 border rounded bg-light">';

                            Object.entries(item).forEach(([key, val]) => {
                                html += `<div><strong>${escapeHtml(key.replace(/_/g, ' '))}:</strong> ${escapeHtml(val ?? '')}</div>`;
                            });

                            html += '</li>';
                        } else {
                            html += `<li class="p-1">${escapeHtml(item)}</li>`;
                        }
                    });

                    html += '</ul>';
                    return html;
                }

                // ==========================
                // GENERIC OBJECT
                // ==========================
                if (typeof parsed === 'object' && parsed !== null) {
                    let html = '<div class="p-2 border rounded bg-light">';

                    Object.entries(parsed).forEach(([key, val]) => {
                        let formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

                        if (Array.isArray(val)) {
                            html += `<div class="mb-2"><strong>${escapeHtml(formattedKey)}:</strong></div>`;
                            html += '<ul class="ms-3">';
                            val.forEach(v => {
                                if (typeof v === 'object') {
                                    html += '<li>';
                                    Object.entries(v).forEach(([k, value]) => {
                                        html += `<span class="me-2"><strong>${escapeHtml(k)}:</strong> ${escapeHtml(value ?? '')}</span>`;
                                    });
                                    html += '</li>';
                                } else {
                                    html += `<li>${escapeHtml(v ?? '')}</li>`;
                                }
                            });
                            html += '</ul>';
                        } else if (typeof val === 'object' && val !== null) {
                            html += `<div class="mb-2"><strong>${escapeHtml(formattedKey)}:</strong></div>`;
                            html += '<div class="ms-3">';
                            Object.entries(val).forEach(([k, v]) => {
                                html += `<div><strong>${escapeHtml(k)}:</strong> ${escapeHtml(v ?? '')}</div>`;
                            });
                            html += '</div>';
                        } else {
                            html += `<div><strong>${escapeHtml(formattedKey)}:</strong> ${escapeHtml(val ?? '')}</div>`;
                        }
                    });

                    html += '</div>';
                    return html;
                }

                // ==========================
                // PRIMITIVE (string, number, boolean)
                // ==========================
                return escapeHtml(String(parsed));

            } catch (e) {
                // Not JSON → plain text
                console.error('JSON parse error:', e, 'Value:', value);
                return escapeHtml(value);
            }
        }

        /**
         * Escape HTML (safety)
         */
        function escapeHtml(text) {
            if (text === null || text === undefined) {
                return '';
            }
            return $('<div>').text(String(text)).html();
        }



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
