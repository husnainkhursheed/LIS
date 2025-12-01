@extends('layouts.master')
@section('title')
        Test Profiles
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
            Notes
        @endslot
    @endcomponent --}}
    <div class="row">

        @include('layouts.notification')

        <div class="col-lg-12">

            {{-- <div class="card "> --}}

                <div class="col">
                    <div class="card p-3 bg-white">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="text-dark">List of test profiles</h3>
                            <button type="button" class="btn btn-primary add-btn align-item-end ms-auto" data-bs-toggle="modal"
                                id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1 "></i> Add
                                profile</button>
                        </div>

                        <div class="col my-2">
                            <nav class="navbar">
                                <div class="container-fluid p-0">
                                    <form class="d-flex" method="GET" action="{{ route('TestProfile.index') }}">
                                        <input class="form-control me-2 main-search" type="search" placeholder="Search"
                                            aria-label="Search" name="search" value="{{ request('search') }}">
                                        <button class="btn search-btn" type="submit">Search</button>
                                    </form>
                                    <form class="d-flex" method="GET" action="{{ route('TestProfile.index') }}">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <select class="form-select sort-dropdown" aria-label="Default select example"
                                            name="sort_by" onchange="this.form.submit()">
                                            <option selected disabled>Sort By</option>
                                            {{-- <option value="code"
                                                {{ request('sort_by') == 'code' ? 'selected' : '' }}>Code</option> --}}
                                            <option value="name"
                                                {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name
                                            </option>
                                            <option value="cost"
                                                {{ request('sort_by') == 'cost' ? 'selected' : '' }}>Cost
                                            </option>
                                        </select>
                                    </form>
                                </div>
                            </nav>

                        </div>
                        <table id="" class="table table-striped display table-responsive rounded">
                            <thead>
                                <tr>
                                    {{-- <th class="rounded-start-3 ">Code</th> --}}
                                    <th class="rounded-start-3">Name</th>
                                    <th>Cost</th>
                                    <th class="rounded-end-3 ">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notes as $note)
                                    <tr>
                                        {{-- <td>{{ $note->code }}</td> --}}
                                        <td>
                                            {{ $note->name }}
                                        </td>
                                        <td>{{ $note->cost  }}</td>
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                    data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                    <a class="edit-item-btn" data-id="{{ $note->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                            class="ri-pencil-fill align-bottom text-muted"></i></a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                    data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                    <a class="remove-item-btn" data-id="{{ $note->id }}"  data-bs-toggle="modal"
                                                        href="#deleteRecordModal">
                                                        <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <ul class="pagination justify-content-center">
                            @if ($notes->previousPageUrl())
                                <li class="page-item previousPageUrl">
                                    <a class="page-link" href="{{ $notes->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item previousPageUrl disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                                </li>
                            @endif

                            @for ($page = 1; $page <= $notes->lastPage(); $page++)
                                <li class="page-item {{ $notes->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="{{ $notes->url($page) }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                                </li>
                            @endfor

                            @if ($notes->nextPageUrl())
                                <li class="page-item nextPageUrl">
                                    <a class="page-link" href="{{ $notes->nextPageUrl() }}" aria-label="Next">
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
        </div>
    </div>

    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="leadtype_form" action="{{ url("/TestProfile") }}" method="Post" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row g-3">

                            <div class="col-lg-12">
                                <div>
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" id="name" name="name"
                                    class="form-control"
                                    placeholder="EnterName" required />
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div>
                                    <label for="cost" class="form-label">Cost</label>
                                    <input type="number" id="cost" name="cost" class="form-control"
                                        placeholder="Enter Cost" required />
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label for="test_requested" class="form-label">Departments</label>
                                <select class="js-example-basic-multiple" name="department[]" id="department" multiple="multiple">
                                    {{-- <option value="">Select Department</option> --}}
                                    <option value="1">Biochemistry / Haematology</option>
                                    <option value="2">Cytology / Gynecology</option>
                                    <option value="3">Urinalysis / Microbiology</option>
                                </select>
                            </div>

                            <div class="col-lg-12">
                                <label for="specimen_type" class="form-label">Specimens Type</label><small class="text-warning"> (note: Specimens apply only to profiles that do not have subprofiles)</small>
                                <select class="js-example-basic-multiple" name="specimen_type" id="specimen_type">
                                    <option value="">Choose Specimen</option>
                                    @foreach ($specimens as $specimen)
                                        <option value="{{ $specimen->id }}">{{ $specimen->name }}</option>
                                    @endforeach
                                </select>
                            </div>




                            <div class="col-lg-12">
                                    <label for="sub_profiles" class="form-label">Include Sub-Profiles</label><small class="text-warning"> (note: Profiles that contain subprofiles do not have formulas )</small>
                                    <select class="js-example-basic-multiple" name="sub_profiles[]" id="sub_profiles" multiple="multiple">
                                        @foreach($profiles as $profile)
                                            {{-- Prevent selecting self as sub-profile --}}
                                            <option value="{{ $profile->id }}">{{ $profile->name }}</option>
                                        @endforeach
                                    </select>
                            </div>

                            <div class="col-lg-12">
                                <label for="tests" class="form-label">Tests</label>
                                <select class="js-example-basic-multiple" name="tests[]" id="tests" multiple="multiple">
                                    {{-- <option value="">Select Department</option> --}}
                                    @foreach ($tests as $test)
                                        <option value="{{$test->id}}"> {{$test->name}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="ordered_tests" id="ordered_tests" />
                            </div>
                            <label for="" class="form-label d-none">Arranged selected tests</label>
                            <div id="ordered-tests" class="d-flex flex-wrap gap-2 mb-3 d-none">
                                {{-- This will be populated with selected tests --}}

                                <span class="badge bg-primary-subtle text-white "></span>
                            </div>
                            <div class="col-lg-12">
                                <hr class="my-4">
                                <h5 class="mb-3">Calculation Formulas</h5>
                                <p class="text-muted small">Define automatic calculations for tests in this profile</p>

                                <div id="formulas-container">
                                    <!-- Dynamic formula rows will be added here -->
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-formula-btn">
                                    <i class="ri-add-line"></i> Add Formula
                                </button>

                                <!-- Hidden input to store formulas as JSON -->
                                <input type="hidden" name="formulas" id="formulas-data" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add Profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>4
    </div>

    <!-- Formula Row Template (hidden) -->
    <template id="formula-row-template">
        <div class="formula-row card mb-3 p-3 border">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Calculated Test</label>
                    <select class="form-select calculated-test-select" required>
                        <option value="">Select Test</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Formula</label>
                    <div class="input-group">
                        <input type="text" class="form-control formula-input"
                            placeholder="e.g., TRIGLYCERIDES/5" required />
                        <button type="button" class="btn btn-outline-info formula-helper-btn"
                                title="Formula Helper">
                            <i class="ri-question-line"></i>
                        </button>
                    </div>
                    <small class="text-muted">Use test names in UPPERCASE. Operators: +, -, *, /, ()</small>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Order</label>
                    <input type="number" class="form-control calculation-order"
                        min="1" value="1" required />
                </div>

                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-formula-btn">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>

            <!-- Formula Preview -->
            <div class="mt-2">
                <small class="text-info formula-preview"></small>
            </div>
        </div>
    </template>

    <!-- Formula Helper Modal -->
    <div class="modal fade" id="formulaHelperModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Formula Builder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Tests to Include</label>
                        <div id="available-tests-list" class="list-group mb-3">
                            <!-- Will be populated with available tests -->
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Formula Preview</label>
                        <input type="text" class="form-control" id="formula-builder-preview" readonly />
                    </div>

                    <div class="btn-group mb-3" role="group">
                        <button type="button" class="btn btn-outline-secondary operator-btn" data-op="+">+</button>
                        <button type="button" class="btn btn-outline-secondary operator-btn" data-op="-">-</button>
                        <button type="button" class="btn btn-outline-secondary operator-btn" data-op="*">×</button>
                        <button type="button" class="btn btn-outline-secondary operator-btn" data-op="/">/</button>
                        <button type="button" class="btn btn-outline-secondary operator-btn" data-op="(">(</button>
                        <button type="button" class="btn btn-outline-secondary operator-btn" data-op=")">)</button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Constant Value</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="constant-value" step="0.001" />
                            <button type="button" class="btn btn-primary" id="add-constant-btn">Add to Formula</button>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <strong>Examples:</strong>
                        <ul class="mb-0 mt-2">
                            <li><code>TRIGLYCERIDES/5</code> - Simple division</li>
                            <li><code>(CHOLESTEROL-HDL)-VLDL</code> - Multiple operations</li>
                            <li><code>CHOLESTEROL/HDL</code> - Ratio</li>
                            <li><code>(MICROALBUMIN/CREATININE)*1000</code> - With constant</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="apply-formula-btn">Apply Formula</button>
                </div>
            </div>
        </div>
    </div>

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
                        <h4 class="fs-semibold">You are about to delete a Profile ?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your Profile will
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
        function checkTestProfiles() {
            $('#tests').val('').trigger('change');
            // Get selected profile IDs
            let selectedProfiles = $('#sub_profiles').val();
            if (selectedProfiles === null || selectedProfiles.length === 0) {
                console.log('No profiles selected, enabling all options');
                // Re-enable all options if no profiles are selected
                $('#sub_profiles option').each(function() {
                    $(this).prop('disabled', false);
                });
                $('#tests option').each(function() {
                    $(this).prop('disabled', false);
                });
            }

            // Send AJAX request to the server
            $.ajax({
                url: '{{ route("checkTestsInProfiles") }}', // Your route here
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    profiles: selectedProfiles
                },
                success: function(response) {
                    // Assuming the response contains the IDs of test profiles to hide
                    if (response.profilesToHide) {
                        $('#sub_profiles option').each(function() {
                            // Disable the profiles that are in the profilesToHide array
                            if (response.profilesToHide.includes(parseInt($(this).val()))) {
                                $(this).prop('disabled', true);  // Disable the option
                            } else {
                                $(this).prop('disabled', false); // Enable the option
                            }
                        });
                        // Refresh the Select2 options
                        $('#sub_profiles').select2();
                        $('#tests option').each(function() {
                            // Disable the profiles that are in the profilesToHide array
                            if (response.testIdsInSelectedProfiles.includes(parseInt($(this).val()))) {
                                $(this).prop('disabled', true);  // Disable the option
                            } else {
                                $(this).prop('disabled', false); // Enable the option
                            }
                        });
                        // Refresh the Select2 options
                        $('#tests').select2();
                    }
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }


        jQuery(document).ready(function($) {

            let selectedTestsOrder = [];

            $('#tests').select2({
                closeOnSelect: false
            });

            // Helper to get current ordered_tests as array (handles empty string)
            function getOrderedTestsArray() {
                const val = $('#ordered_tests').val();
                if (!val) return [];
                return val.split(',').filter(Boolean);
            }
            $('#sub_profiles').select2({
                closeOnSelect: false
            });

            // Function to enable/disable sub_profiles based on specimen selection
            function updateSubProfilesDisabledState() {
                // Get current value(s) of specimen_type. Works for single or multiple selects.
                let val = $('#specimen_type').val();

                // Normalize to an array for easier checks
                if (val === null) val = [];
                if (!Array.isArray(val)) val = [val];

                // If there's any non-empty selection, disable sub_profiles
                const hasSpecimenSelected = val.some(function(v) { return v !== null && v !== '' && v !== undefined; });

                if (hasSpecimenSelected) {
                    // disable underlying select and refresh select2
                    $('#sub_profiles').prop('disabled', true);
                    $('#sub_profiles').val(null).trigger('change');
                    // visually update select2 by triggering change (select2 respects underlying disabled prop)
                    // $('#sub_profiles').trigger('change.select2');
                } else {
                    $('#sub_profiles').prop('disabled', false);
                    // $('#sub_profiles').trigger('change.select2');
                }
            }

            // Watch specimen_type changes, update sub_profiles state and fetch tests for selected specimen(s)
            $('#specimen_type').on('change', function() {
                // When specimen is changed, clear selected sub_profiles (if any) and update state
                // Optionally we could keep selections, but requirement says disable Include Sub-Profiles when specimen selected
                updateSubProfilesDisabledState();

                // Fetch related tests via AJAX
                let selectedSpecimens = $(this).val();

                // Normalize to array or null
                // if (!selectedSpecimens || selectedSpecimens.length === 0) {
                //     // If no specimen selected, reset tests list to original options
                //     $('#tests option').each(function() { $(this).prop('disabled', false); });
                //     $('#tests').val('').trigger('change');
                //     selectedTestsOrder = [];
                //     // updateOrderedTestsDisplay();
                //     return;
                // }

                $.ajax({
                    url: '{{ route("TestProfile.fetchTestsBySpecimen") }}',
                    type: 'POST',
                    data: {
                        specimen_type: selectedSpecimens,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (!response.tests) return;

                        let $select = $('#tests');

                        // Remove current options and rebuild from returned tests
                        // But to preserve other tests not related to specimen, we'll clear selection and replace options with returned set
                        $select.empty();

                        response.tests.forEach(function(test) {
                            $select.append(new Option(test.name, test.id));
                        });

                        // Refresh select2
                        $select.trigger('change');

                        // Clear ordered tests since new set loaded
                        selectedTestsOrder = [];
                        $('#ordered_tests').val('');
                        // updateOrderedTestsDisplay();
                    },
                    error: function(xhr) {
                        console.error('Error fetching tests by specimen', xhr);
                    }
                });
            });

            // Ensure the state is correct on initial load
            updateSubProfilesDisabledState();

            // $('#mySelect').on('select2:select', function (e) {
            //     var selectedId = e.params.data.id;
            //     var $option = $(this).find('option[value="' + selectedId + '"]');
            //     $option.detach();
            //     $(this).append($option);
            //     $(this).trigger('change.select2');
            // });

            $('#sub_profiles').on('select2:select', function(e) {
                const selectedValue = e.params.data.id;

                var $option = $(this).find('option[value="' + selectedValue + '"]');
                $option.detach();
                $(this).append($option);
                $(this).trigger('change.select2');

            });

            $('#tests').on('select2:select', function(e) {
                const selectedValue = e.params.data.id;

                var $option = $(this).find('option[value="' + selectedValue + '"]');
                $option.detach();
                $(this).append($option);
                $(this).trigger('change.select2');
                //
                selectedTestsOrder = getOrderedTestsArray();
                // Add to order array if not already present
                if (!selectedTestsOrder.includes(selectedValue)) {
                    selectedTestsOrder.push(selectedValue);
                }

                // Update hidden input and badges
                updateOrderedTestsDisplay();
            });

            $('#tests').on('select2:unselect', function(e) {
                const unselectedValue = e.params.data.id;

                selectedTestsOrder = getOrderedTestsArray();
                // Remove from order array
                selectedTestsOrder = selectedTestsOrder.filter(id => id !== unselectedValue);

                // Update hidden input and badges
                updateOrderedTestsDisplay();
            });

            function updateFormulasStateBasedOnSubProfiles() {
                const selected = $('#sub_profiles').val();
                const hasValue = Array.isArray(selected) ? selected.length > 0 : !!selected;

                if (hasValue) {
                    // Clear formulas data and UI
                    $('#formulas-container').empty();
                    $('#formulas-data').val('');
                    formulas = [];

                    // Disable the add button and any leftover formula controls
                    $('#add-formula-btn').prop('disabled', true).addClass('disabled');
                    $('#formulas-container').find('input, select, button').prop('disabled', true);

                    // Optional: show a small notice (uncomment if desired)
                    // if ($('#formulas-disabled-note').length === 0) {
                    //     $('<div id="formulas-disabled-note" class="text-muted small mt-2">Formulas disabled when Include Sub-Profiles is selected.</div>').insertBefore('#formulas-container');
                    // }
                } else {
                    // Re-enable add button; actual formula rows will be added by user
                    $('#add-formula-btn').prop('disabled', false).removeClass('disabled');
                    $('#formulas-container').find('input, select, button').prop('disabled', false);
                    $('#formulas-disabled-note').remove();
                }
            }

            $('#sub_profiles').on('change', function() {
                // also call your existing check function if needed
                checkTestProfiles();
                updateFormulasStateBasedOnSubProfiles();
            });

            function updateOrderedTestsDisplay() {
                // Update hidden input with ordered values
                $('#ordered_tests').val(selectedTestsOrder.join(','));

                // Clear and rebuild badges in order
                $('#ordered-tests').empty();

                selectedTestsOrder.forEach(function(testId) {
                    const testName = $('#tests option[value="' + testId + '"]').text();
                    if (testName) {
                        $('#ordered-tests').append(
                            `<span class="badge bg-primary-subtle text-white" data-test-id="${testId}">
                                ${testName}
                                <button type="button" class="btn-close btn-close-white ms-1 remove-test" data-test-id="${testId}" style="font-size: 0.7em;"></button>
                            </span>`
                        );
                    }
                });
            }

            // Remove test when clicking the badge close button
            $('#ordered-tests').on('click', '.remove-test', function() {
                const testId = $(this).data('test-id').toString();
                // Remove from selectedTestsOrder
                selectedTestsOrder = selectedTestsOrder.filter(id => id !== testId);
                // Unselect from select2
                let selected = $('#tests').val() || [];
                selected = selected.filter(id => id !== testId);
                $('#tests').val(selected).trigger('change');
                // Update display
                updateOrderedTestsDisplay();
            });



            $('#fetch-profile-tests').on('click', function() {
                var profileIds = $('#load_profiles').val();
                if (!profileIds || profileIds.length === 0) return;

                $.ajax({
                    url: '/TestProfile/fetch-tests-from-profiles',
                    type: 'POST',
                    data: {
                        profile_ids: profileIds,
                        _token: $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        let $select = $('#tests');
                        response.test_ids.forEach(function(id) {
                            let $option = $select.find('option[value="' + id + '"]');
                            $option.detach();
                            $select.append($option);
                        });

                        // Set Select2 selected values in order
                        $select.val(response.test_ids).trigger('change');
                        // $('#tests').val(response.test_ids).trigger('change');
                        $('#ordered_tests').val(response.test_ids.join(','));
                        selectedTestsOrder = getOrderedTestsArray();
                        updateOrderedTestsDisplay();
                    },
                    error: function(xhr) {
                        alert('Could not load tests for the selected profiles.');
                    }
                });
            });

            $('#toggle-load-profiles').on('click', function() {
                $('#load-profiles-group').slideToggle(150);
                $('#arrow-icon').toggleClass('rotated');
            });

            // Optional: rotate arrow when open
            $('<style>.rotated { transform: rotate(180deg); }</style>').appendTo('head');

            // When the document is ready, attach a click event to the "Edit" button
            $('.edit-item-btn').on('click', function() {
                // Get the ID from the data attribute

                var itemId = $(this).data('id');
                var url = '{{ url("/TestProfile") }}' + '/' + itemId + '/edit';

                $.ajax({
                        url: url, // Adjust the route as needed
                        type: 'GET',
                        success: function(response) {
                            // Assuming the response has a 'leadType' key
                            var note = response.note;
                            // console.log("my practices ",doctor);

                            // Now you can use the leadType data to populate your modal fields
                            $('#id-field').val(note.id);
                            $('#code').val(note.code);
                            $('#name').val(note.name);
                            $('#cost').val(note.cost);



                            // Ensure sub_profiles enabled/disabled state matches any specimen selection (edit response may have populated specimen_type)
                            updateSubProfilesDisabledState();


                            var profiledepartment = response.profiledepartment.map(function(surgery) {
                                return surgery.department;
                            });
                            $('#department').val(profiledepartment).trigger('change');

                            if (response.note && response.note.sub_profiles) {
                                // $('#sub_profiles').val(response.note.sub_profiles.map(String)).trigger('change');
                                let $sub_profiles_select = $('#sub_profiles');
                                response.note.sub_profiles.forEach(function(id) {
                                    let $option = $sub_profiles_select.find('option[value="' + id + '"]');
                                    $option.detach();
                                    $sub_profiles_select.append($option);
                                });

                                // Set Select2 selected values in order
                                $sub_profiles_select.val(response.note.sub_profiles).trigger('change');
                            }

                            if (response.note && response.note.specimentype_id) {
                                $('#specimen_type').val(response.note.specimentype_id).trigger('change');
                            }


                            var profiletests = response.profiletests; // Array of {id, name} in correct order
                            let testIds = profiletests.map(function(test) { return test.id.toString(); });

                            // Move options in DOM to match saved order
                            let $select = $('#tests');
                            testIds.forEach(function(id) {
                                let $option = $select.find('option[value="' + id + '"]');
                                $option.detach();
                                $select.append($option);
                            });
                            setTimeout(() => {
                                // Set Select2 selected values in order
                                $select.val(testIds).trigger('change');
                                // Load formulas
                                if (response.formulas) {
                                    loadFormulas(response.formulas);
                                }
                            }, 4000); // delay of 10 sec

                            // Set the order array and update badges/hidden input
                            selectedTestsOrder = testIds;
                            updateOrderedTestsDisplay();

                            $('#ordered_tests').val(testIds.join(','));

                            // Update modal title, button, etc...
                            $('#exampleModalLabel').html("Edit Profile");
                            $('#showModal .modal-footer').css('display', 'block');
                            $('#add-btn').html("Update");
                            $('#leadtype_form').attr('action', '{{ url("/TestProfile") }}/' + note.id);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr, status, error);
                            // Handle errors if needed
                        }
                    });

            });

            function resetModal() {
                // Reset modal titleq
                $('#exampleModalLabel').html("Add Profile");

                // Display the modal footer
                $('#showModal .modal-footer').css('display', 'block');

                // Change the button text
                $('#add-btn').html("Add");
                $('#leadtype_form').attr('action', '{{ url("/TestProfile") }}');
                // if ( $('#patch').length) {
                //     $('#patch').remove();
                // }
                $('#code').val('');
                $('#name').val('');
                $('#cost ').val('');
                $('#tests ').val('');
                // $('#surgeries').val("");
                $('#surgeries').val("").trigger('change');
                $('#tests').val("").trigger('change');
                $('#department').val("").trigger('change');
                $('#tests').val('').trigger('change');
                $('#sub_profiles').val('').trigger('change');
                $('#specimen_type').val('').trigger('change');
                $('#ordered-tests').empty();
                $('#ordered_tests').val('');
                $('#sub_profiles option').each(function() {
                    $(this).prop('disabled', false);
                });
                $('#tests option').each(function() {
                    $(this).prop('disabled', false);
                });
                $('#formulas-container').empty();
                $('#formulas-data').val('');
                formulas = [];

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
                var url = '/TestProfile/' + itemId;

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

        // Formula Management System
        let formulas = [];
        let currentFormulaRow = null;
        let availableTests = [];

        // Update available tests when tests selection changes
        $('#tests').on('change', function() {
            availableTests = [];
            $('#tests option:selected').each(function() {
                availableTests.push({
                    id: $(this).val(),
                    name: $(this).text()
                });
            });

            updateCalculatedTestOptions();
            updateFormulaHelperTests();
        });

        // Add new formula row
        $('#add-formula-btn').on('click', function() {
            if (availableTests.length === 0) {
                alert('Please select tests first before adding formulas');
                return;
            }
            addFormulaRow();
        });

        function addFormulaRow(data = null) {
            const template = document.getElementById('formula-row-template');
            const clone = template.content.cloneNode(true);
            const container = document.getElementById('formulas-container');

            // Add to container
            container.appendChild(clone);

            const row = container.lastElementChild;

            // Populate calculated test dropdown
            const select = row.querySelector('.calculated-test-select');
            availableTests.forEach(test => {
                const option = new Option(test.name, test.id);
                select.add(option);
            });

            // If editing, populate data
            if (data) {
                const targetValue = String(data.calculated_test_id);

                // 🕐 Make sure options exist first
                setTimeout(() => {
                    // Find the select again to be safe
                    const select = row.querySelector('.calculated-test-select');

                    // Try direct match first
                    select.value = targetValue;

                    // Fallback: manually find and select the option
                    if (select.value !== targetValue) {
                        const match = Array.from(select.options).find(opt => String(opt.value) === targetValue);
                        if (match) match.selected = true;
                    }

                    // Update other fields
                    const formulaInput = row.querySelector('.formula-input');
                    if (formulaInput) formulaInput.value = data.formula || '';

                    const orderInput = row.querySelector('.calculation-order');
                    if (orderInput) orderInput.value = data.calculation_order || 1;

                    // ✅ Trigger preview update
                    updateFormulaPreview(row);
                }, 50); // small delay ensures options are rendered
            }

            // Attach event listeners
            attachFormulaRowListeners(row);
        }

        function attachFormulaRowListeners(row) {
            // Remove formula
            row.querySelector('.remove-formula-btn').addEventListener('click', function() {
                row.remove();
                updateFormulasData();
            });

            // Formula helper
            row.querySelector('.formula-helper-btn').addEventListener('click', function() {
                currentFormulaRow = row;
                const currentFormula = row.querySelector('.formula-input').value;
                $('#formula-builder-preview').val(currentFormula);
                $('#formulaHelperModal').modal('show');
            });

            // Update preview on input
            row.querySelector('.formula-input').addEventListener('input', function() {
                updateFormulaPreview(row);
                updateFormulasData();
            });

            row.querySelector('.calculated-test-select').addEventListener('change', function() {
                updateFormulaPreview(row);
                updateFormulasData();
            });

            row.querySelector('.calculation-order').addEventListener('change', function() {
                updateFormulasData();
            });
        }

        function updateFormulaPreview(row) {
            const select = row.querySelector('.calculated-test-select');
            const testId = select.value;
            const formula = row.querySelector('.formula-input').value;
            const preview = row.querySelector('.formula-preview');

            if (testId && formula) {
                const testName = select.options[select.selectedIndex]?.text || '';
                preview.innerHTML = `<strong>${testName}</strong> = ${formula}`;

                // Validate formula
                if (validateFormula(formula)) {
                    preview.classList.remove('text-danger');
                    preview.classList.add('text-info');
                } else {
                    preview.classList.remove('text-info');
                    preview.classList.add('text-danger');
                    preview.innerHTML += ' <i class="ri-error-warning-line"></i> Invalid formula';
                }
            } else {
                preview.innerHTML = '';
            }
        }

        function validateFormulaWithDetails(formula) {
            // Basic validation
            if (!formula || formula.trim() === '') {
                return { valid: false, error: 'Formula is empty' };
            }

            // Allow letters, numbers, spaces, operators, parentheses, and special characters
            const validPattern = /^[A-Za-z0-9\s\+\-\*\/\(\)\.\#\%\@\&\$\!\~\[\]\_\-\:\,]+$/;
            if (!validPattern.test(formula)) {
                return { valid: false, error: 'Contains invalid characters' };
            }

            // Check balanced parentheses
            let openCount = (formula.match(/\(/g) || []).length;
            let closeCount = (formula.match(/\)/g) || []).length;
            if (openCount !== closeCount) {
                return { valid: false, error: 'Unbalanced parentheses' };
            }

            // Extract referenced test names from formula
            const referencedTests = [];
            availableTests.forEach(test => {
                const upperTestName = test.name.toUpperCase().trim();
                if (formula.toUpperCase().includes(upperTestName)) {
                    referencedTests.push(test.name);
                }
            });

            return { valid: true, referencedTests };
        }

        function validateFormula(formula) {
            // Basic validation: check for test names and operators
            if (!formula || formula.trim() === '') return false;

            // Allow letters, numbers, spaces, operators, parentheses, and special characters (*,#,%,etc)
            const validPattern = /^[A-Za-z0-9\s\+\-\*\/\(\)\.\#\%\@\&\$\!\~\[\]\_\-\:\,]+$/;
            if (!validPattern.test(formula)) return false;

            // Check balanced parentheses
            let openCount = (formula.match(/\(/g) || []).length;
            let closeCount = (formula.match(/\)/g) || []).length;
            if (openCount !== closeCount) return false;

            return true;
        }

        function updateCalculatedTestOptions() {
            // Update all calculated test dropdowns
            $('.calculated-test-select').each(function() {
                const currentValue = $(this).val();
                $(this).empty().append('<option value="">Select Test</option>');

                availableTests.forEach(test => {
                    const option = new Option(test.name, test.id);
                    $(this).append(option);
                });

                // Restore previous selection if still valid
                if (currentValue) {
                    $(this).val(currentValue);
                }
            });
        }

        function updateFormulasData() {
            formulas = [];

            $('.formula-row').each(function() {
                const testId = $(this).find('.calculated-test-select').val();
                const formula = $(this).find('.formula-input').val();
                const order = $(this).find('.calculation-order').val();

                if (testId && formula) {
                    formulas.push({
                        calculated_test_id: testId,
                        formula: formula,
                        calculation_order: parseInt(order) || 1
                    });
                }
            });

            // Update hidden input
            $('#formulas-data').val(JSON.stringify(formulas));
        }

        // Formula Helper Modal Functions
        function updateFormulaHelperTests() {
            const container = $('#available-tests-list');
            container.empty();

            availableTests.forEach(test => {
                // Display original test name with special characters
                const displayName = test.name;
                const upperName = test.name.toUpperCase().trim();

                const item = $(`
                    <button type="button" class="list-group-item list-group-item-action test-name-btn"
                            data-test-name="${upperName}">
                        <span class="badge bg-primary me-2">${test.id}</span>
                        ${displayName}
                        ${displayName.includes('*') || displayName.includes('#') || displayName.includes('%') ?
                            '<span class="badge bg-warning ms-2">Special chars</span>' : ''}
                    </button>
                `);
                container.append(item);
            });

            // Attach click handlers
            $('.test-name-btn').on('click', function() {
                const testName = $(this).data('test-name');
                const current = $('#formula-builder-preview').val();

                // Add space before test name if needed (after operator or opening parenthesis)
                const lastChar = current.slice(-1);
                const needsSpace = current.length > 0 && !['+', '-', '*', '/', '(', ' '].includes(lastChar);
                const prefix = needsSpace ? ' ' : '';

                $('#formula-builder-preview').val(current + prefix + testName);
            });
        }

        // Operator buttons
        $('.operator-btn').on('click', function() {
            const operator = $(this).data('op');
            const current = $('#formula-builder-preview').val();
            $('#formula-builder-preview').val(current + operator);
        });

        // Add constant
        $('#add-constant-btn').on('click', function() {
            const constant = $('#constant-value').val();
            if (constant) {
                const current = $('#formula-builder-preview').val();
                $('#formula-builder-preview').val(current + constant);
                $('#constant-value').val('');
            }
        });

        // Apply formula from helper
        $('#apply-formula-btn').on('click', function() {
            if (currentFormulaRow) {
                const formula = $('#formula-builder-preview').val();
                $(currentFormulaRow).find('.formula-input').val(formula);
                updateFormulaPreview(currentFormulaRow);
                updateFormulasData();
            }
            $('#formulaHelperModal').modal('hide');
        });

        // Load formulas when editing
        function loadFormulas(formulasData) {
            $('#formulas-container').empty();

            if (formulasData && formulasData.length > 0) {
                formulasData.forEach(formula => {
                    addFormulaRow(formula);
                });
            }
        }

        // Form submission - ensure formulas are included
        $('#leadtype_form').on('submit', function() {
            updateFormulasData();

            // Validate all formulas
            let isValid = true;
            $('.formula-input').each(function() {
                const formula = $(this).val();
                if (formula && !validateFormula(formula)) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                alert('Please fix invalid formulas before submitting');
                return false;
            }

            return true;
        });


    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
@endsection
