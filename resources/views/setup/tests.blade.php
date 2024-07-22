@extends('layouts.master')
@section('title')
        Test Charges
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
            Test Charges
        @endslot
    @endcomponent --}}
    <div class="row">

        @include('layouts.notification')

        <div class="col-lg-12">

            {{-- <div class="card "> --}}
                <div class="col">
                    <div class="card p-3 bg-white">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="text-dark">List of tests</h3>
                            <button type="button" class="btn btn-primary add-btn align-item-end ms-auto" data-bs-toggle="modal"
                                id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1 "></i> Add
                                Test</button>
                        </div>

                        <div class="col my-2">
                            <nav class="navbar">
                                <div class="container-fluid p-0">
                                    <form class="d-flex" method="GET" action="{{ route('test.index') }}">
                                        <input class="form-control me-2 main-search" type="search" placeholder="Search"
                                            aria-label="Search" name="search" value="{{ request('search') }}">
                                        <button class="btn search-btn" type="submit">Search</button>
                                    </form>
                                    <form class="d-flex" method="GET" action="{{ route('test.index') }}">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <select class="form-select sort-dropdown" aria-label="Default select example"
                                            name="sort_by" onchange="this.form.submit()">
                                            <option selected disabled>Sort By</option>
                                            <option value="name"
                                                {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                                            <option value="department"
                                                {{ request('sort_by') == 'department' ? 'selected' : '' }}>Department
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
                                    <th class="rounded-start-3 ">Name</th>
                                    <th>Department</th>
                                    <th>Cost</th>
                                    <th>Status</th>
                                    <th class="rounded-end-3 ">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tests as $test)
                                    <tr>
                                        <td>{{ $test->name }}</td>

                                        <td>
                                            @if($test->department == 1)
                                                Biochemistry / Haematology
                                            @elseif($test->department == 2)
                                                Cytology / Gynecology
                                            @elseif($test->department == 3)
                                                Urinalysis / Microbiology
                                            @else
                                                Unknown Department
                                            @endif
                                        </td>
                                        <td>{{ $test->cost  }}</td>
                                        <td>{{ $test->is_active == 1 ? 'Active' : 'InActive' }}</td>


                                        {{-- <td>
                                            <a href="#showModal" data-bs-toggle="modal">
                                                <span class="logo-sm">
                                                    <img src="{{ URL::asset('build/images/report.png') }}" alt=""
                                                        height="20">
                                                </span>
                                            </a>
                                            <a href="">
                                                <span class="logo-sm">
                                                    <img src="{{ URL::asset('build/images/Vector.png') }}" alt=""
                                                        height="20">
                                                </span>
                                            </a>
                                            <a href="">
                                                <span class="logo-sm">
                                                    <img src="{{ URL::asset('build/images/delete.png') }}" alt=""
                                                        height="20">
                                                </span>
                                            </a>
                                        </td> --}}
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                    data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                    <a class="edit-item-btn" data-id="{{ $test->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                            class="ri-pencil-fill align-bottom text-muted"></i></a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                    data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                    <a class="remove-item-btn" data-id="{{ $test->id }}"  data-bs-toggle="modal"
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
                            @if ($tests->previousPageUrl())
                                <li class="page-item previousPageUrl">
                                    <a class="page-link" href="{{ $tests->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item previousPageUrl disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                                </li>
                            @endif

                            @for ($page = 1; $page <= $tests->lastPage(); $page++)
                                <li class="page-item {{ $tests->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="{{ $tests->url($page) }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                                </li>
                            @endfor

                            @if ($tests->nextPageUrl())
                                <li class="page-item nextPageUrl">
                                    <a class="page-link" href="{{ $tests->nextPageUrl() }}" aria-label="Next">
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
                                <th>Department</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tests as $test)
                                <tr>
                                    <td>{{ $test->name }}</td>
                                    <td>{{ $test->department }}</td>
                                    <td>{{ $test->cost  }}</td>
                                    <td>{{ $test->is_active == 1 ? 'Active' : 'InActive' }}</td>

                                    <td>
                                        <ul class="list-inline hstack gap-2 mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                <a class="edit-item-btn" data-id="{{ $test->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                        class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                <a class="remove-item-btn" data-id="{{ $test->id }}"  data-bs-toggle="modal"
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

    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Test</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="leadtype_form" action="{{ url("/test") }}" method="Post" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div>
                                    <label for="companyname-field"
                                        class="form-label">Name of charge item</label>
                                    <input type="text" id="name" name="name"
                                        class="form-control"
                                        placeholder="Enter Name" required />
                                </div>
                                {{-- @error('v_name')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror --}}
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="department" class="form-label">Department</label>
                                    <select class="form-control" name="department" id="department">
                                        <option value="">Select Department</option>
                                        <option value="1">Biochemistry / Haematology</option>
                                        <option value="2">Cytology / Gynecology</option>
                                        <option value="3">Urinalysis / Microbiology</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="specimen_type" class="form-label">Specimen Type</label>
                                    <input type="text" id="specimen_type" class="form-control" name="specimen_type"
                                        placeholder="Enter Specimen Type" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="cost" class="form-label">Cost</label>
                                    <input type="number" id="cost" name="cost" class="form-control"
                                        placeholder="Enter Cost" required />
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label for="reference_range" class="form-label">Reference range</label>
                                <div>

                                    <input type="radio" id="basic_ref" name="reference_range"
                                         required  value="basic_ref" checked/>
                                        <label for="basic_ref" class="form-label">Basic Reference range</label>
                                    <input type="radio" id="optional_ref" class="ms-4" name="reference_range"
                                         required value="optional_ref" />
                                    <label for="optional_ref" class="form-label">Reference range with optional sex</label>
                                    <input type="radio" id="no_manual_tag" class="ms-4" name="reference_range"
                                         required value="no_manual_tag" />
                                    <label for="no_manual_tag" class="form-label">No / Manual Tag</label>
                                </div>
                            </div>
                            <div class="row" id="basicValues">
                                {{-- <label for="" class="form-label">High value with optional sex</label> --}}
                                {{-- <div> --}}
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="basic_low_value_ref_range" class="form-label">Low Value</label>
                                            <input type="text" id="basic_low_value_ref_range" class="form-control" name="basic_low_value_ref_range"
                                                placeholder="Enter Low Value" required />
                                        </div>
                                    </div>
                                        {{-- <label for="male" class="form-label">High Value</label> --}}
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="basic_high_value_ref_range" class="form-label">High Value</label>
                                            <input type="text" id="basic_high_value_ref_range" class="form-control" name="basic_high_value_ref_range"
                                                placeholder="Enter High Value" required />
                                        </div>
                                    </div>
                                    {{-- <label for="female" class="form-label">Low value</label> --}}
                                {{-- </div> --}}
                            </div>
                            <div class="row" id="optionalValues">
                                <h5 for="" class="form-label text-black fw-bolder">Male </h5>
                                <div class="col-lg-6">
                                    <div>
                                        <label for="male_low_value_ref_range" class="form-label">Low Value</label>
                                        <input type="text" id="male_low_value_ref_range" class="form-control" name="male_low_value_ref_range"
                                            placeholder="Enter Low Value"  />
                                    </div>
                                </div>
                                    {{-- <label for="male" class="form-label">High Value</label> --}}
                                <div class="col-lg-6">
                                    <div>
                                        <label for="male_high_value_ref_range" class="form-label">High Value</label>
                                        <input type="text" id="male_high_value_ref_range" class="form-control" name="male_high_value_ref_range"
                                            placeholder="Enter High Value"  />
                                    </div>
                                </div>
                                <h5 for="" class="form-label text-black fw-bolder mt-2">Female </h5>
                                <div class="col-lg-6">
                                    <div>
                                        <label for="female_low_value_ref_range" class="form-label">Low Value</label>
                                        <input type="text" id="female_low_value_ref_range" class="form-control" name="female_low_value_ref_range"
                                            placeholder="Enter Low Value"  />
                                    </div>
                                </div>
                                    {{-- <label for="female" class="form-label">High Value</label> --}}
                                <div class="col-lg-6">
                                    <div>
                                        <label for="female_high_value_ref_range" class="form-label">High Value</label>
                                        <input type="text" id="female_high_value_ref_range" class="form-control" name="female_high_value_ref_range"
                                            placeholder="Enter High Value"  />
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="noManualValues">
                                <textarea name="nomanualvalues_ref_range" id="nomanualvalues_ref_range" cols="30" rows="10"></textarea>
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
                            <button type="submit" class="btn btn-success" id="add-btn">Add Test</button>
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
                        <h4 class="fs-semibold">You are about to delete a test ?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your test will
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
    {{-- {{dd(Auth::user()->departments)}} --}}
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
            // Hide optionalValues by default
            $('#optionalValues').hide();
            $('#noManualValues').hide();
            $('#basicValues').show();

            // Show/hide fields based on selected reference range
            $('input[name="reference_range"]').on('change', function() {
                if (this.value === 'basic_ref') {
                    $('#basicValues').show();
                    $('#optionalValues').hide();
                    $('#noManualValues').hide();
                    // Make fields required
                    $('#basic_low_value_ref_range').prop('required', true);
                    $('#basic_high_value_ref_range').prop('required', true);
                    $('#male_low_value_ref_range').prop('required', false);
                    $('#male_high_value_ref_range').prop('required', false);
                    $('#female_low_value_ref_range').prop('required', false);
                    $('#female_high_value_ref_range').prop('required', false);
                } else if (this.value === 'optional_ref') {
                    $('#basicValues').hide();
                    $('#optionalValues').show();
                    $('#noManualValues').hide();
                    // Make fields required
                    $('#basic_low_value_ref_range').prop('required', false);
                    $('#basic_high_value_ref_range').prop('required', false);
                    $('#male_low_value_ref_range').prop('required', true);
                    $('#male_high_value_ref_range').prop('required', true);
                    $('#female_low_value_ref_range').prop('required', true);
                    $('#female_high_value_ref_range').prop('required', true);
                }else if (this.value === 'no_manual_tag') {
                    $('#basicValues').hide();
                    $('#optionalValues').hide();
                    $('#noManualValues').show();
                    // Make fields required
                    $('#basic_low_value_ref_range').prop('required', false);
                    $('#basic_high_value_ref_range').prop('required', false);
                    $('#male_low_value_ref_range').prop('required', false);
                    $('#male_high_value_ref_range').prop('required', false);
                    $('#female_low_value_ref_range').prop('required', false);
                    $('#female_high_value_ref_range').prop('required', false);
                }
            });
        });
        // $(document).ready(function() {
        //     var currentUser = "{{ Auth::user()->getRoleNames()->first() }}"; // Get the current user's ID from the server-side

        //     // Check if the current user is in the "Lab" role
        //     if (currentUser === 'Lab') {
        //         console.log('clicked');
        //         var labDepartments = {!! json_encode(Auth::user()->departments) !!}; // Get the department IDs associated with the user

        //         // Loop through each option in the select element
        //         $('#department option').each(function() {
        //             var departmentId = $(this).val(); // Get the value of the option

        //             // Check if the department ID is not in the user's associated departments
        //             if (!labDepartments.includes(departmentId)) {
        //                 $(this).hide(); // Hide the option
        //             }
        //         });
        //     }
        // });

        jQuery(document).ready(function($) {
        // When the document is ready, attach a click event to the "Edit" button
        $('.edit-item-btn').on('click', function() {
            // Get the ID from the data attribute

            var itemId = $(this).data('id');
            var url = '{{ url("/test") }}' + '/' + itemId + '/edit';

            $.ajax({
                    url: url, // Adjust the route as needed
                    type: 'GET',
                    success: function(response) {
                        // Assuming the response has a 'leadType' key
                        var test = response.tests;
                        // console.log("my practices ",doctor);

                        // Now you can use the leadType data to populate your modal fields
                        $('#id-field').val(test.id);
                        $('#name').val(test.name);
                        $('#department').val(test.department);
                        $('#specimen_type').val(test.specimen_type);
                        $('#cost').val(test.cost);
                        $('#reference_range').val(test.reference_range);

                        if (test.reference_range === 'basic_ref') {
                            $('#basic_ref').prop('checked', true);
                            $('#optional_ref').prop('checked', false);
                            $('#no_manual_tag').prop('checked', false);
                            $('#basicValues').show();
                            $('#optionalValues').hide();
                            $('#noManualValues').hide();
                            // Make fields required
                            $('#basic_low_value_ref_range').prop('required', true);
                            $('#basic_high_value_ref_range').prop('required', true);
                            $('#male_low_value_ref_range').prop('required', false);
                            $('#male_high_value_ref_range').prop('required', false);
                            $('#female_low_value_ref_range').prop('required', false);
                            $('#female_high_value_ref_range').prop('required', false);
                            $('#basic_low_value_ref_range').val(test.basic_low_value_ref_range);
                            $('#basic_high_value_ref_range').val(test.basic_high_value_ref_range);
                            $('#male_low_value_ref_range').val(test.male_low_value_ref_range);
                            $('#male_high_value_ref_range').val(test.male_high_value_ref_range);
                            $('#female_low_value_ref_range').val(test.female_low_value_ref_range);
                            $('#female_high_value_ref_range').val(test.female_high_value_ref_range);
                        } else if (test.reference_range === 'optional_ref') {
                            $('#basic_ref').prop('checked', false);
                            $('#optional_ref').prop('checked', true);
                            $('#no_manual_tag').prop('checked', false);
                            $('#basicValues').hide();
                            $('#optionalValues').show();
                            $('#noManualValues').hide();
                            // Make fields required
                            $('#basic_low_value_ref_range').prop('required', false);
                            $('#basic_high_value_ref_range').prop('required', false);
                            $('#male_low_value_ref_range').prop('required', true);
                            $('#male_high_value_ref_range').prop('required', true);
                            $('#female_low_value_ref_range').prop('required', true);
                            $('#female_high_value_ref_range').prop('required', true);
                            $('#basic_low_value_ref_range').val(test.basic_low_value_ref_range);
                            $('#basic_high_value_ref_range').val(test.basic_high_value_ref_range);
                            $('#male_low_value_ref_range').val(test.male_low_value_ref_range);
                            $('#male_high_value_ref_range').val(test.male_high_value_ref_range);
                            $('#female_low_value_ref_range').val(test.female_low_value_ref_range);
                            $('#female_high_value_ref_range').val(test.female_high_value_ref_range);
                        }else if (test.reference_range === 'no_manual_tag') {
                            $('#basic_ref').prop('checked', false);
                            $('#optional_ref').prop('checked', false);
                            $('#no_manual_tag').prop('checked', true);
                            $('#basicValues').hide();
                            $('#optionalValues').hide();
                            $('#noManualValues').show();
                            $('#basic_low_value_ref_range').prop('required', false);
                            $('#basic_high_value_ref_range').prop('required', false);
                            $('#male_low_value_ref_range').prop('required', false);
                            $('#male_high_value_ref_range').prop('required', false);
                            $('#female_low_value_ref_range').prop('required', false);
                            $('#female_high_value_ref_range').prop('required', false);
                            $('#nomanualvalues_ref_range').val(test.nomanualvalues_ref_range);
                        }
                        // $('#area').val(test.area);
                        // $('#email').val(test.email);

                        // var surgeries = SetupPractice.surgeries.map(function(surgery) {
                        //         return surgery.id;
                        //     });

                        // $('#surgeries').val(surgeries).trigger('change');


                        // Set the checkbox town for is_active
                        $('#is_active').prop('checked', test.is_active);

                        // Update modal title
                        $('#exampleModalLabel').html("Edit test");

                        // Display the modal footer
                        $('#showModal .modal-footer').css('display', 'block');

                        // Change the button text
                        $('#add-btn').html("Update");
                        var form = $('#leadtype_form');

                        // Update the form action (assuming the form has an ID of 'your-form-id')
                        $('#leadtype_form').attr('action', '{{ url("/test") }}/' + itemId);



                        // $('#showModal').modal('show');

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                        // Handle errors if needed
                    }
                });

        });

        function resetModal() {
            $('#exampleModalLabel').html("Add Test");
            $('#showModal .modal-footer').css('display', 'block');
            $('#add-btn').html("Add");
            $('#leadtype_form').attr('action', '{{ url("/test") }}');
            $('#name').val('');
            $('#department').val('');
            $('#specimen_type ').val('');
            $('#cost').val('');
            $('#basic_ref').prop('checked', true);
            $('#basicValues').show();
            $('#optionalValues').hide();
            $('#noManualValues').hide();
            $('#basic_low_value_ref_range').prop('required', true);
            $('#basic_high_value_ref_range').prop('required', true);
            $('#male_low_value_ref_range').prop('required', false);
            $('#male_high_value_ref_range').prop('required', false);
            $('#female_low_value_ref_range').prop('required', false);
            $('#female_high_value_ref_range').prop('required', false);
            $('#basic_low_value_ref_range').val('');
            $('#basic_high_value_ref_range').val('');
            $('#male_low_value_ref_range').val('');
            $('#male_high_value_ref_range').val('');
            $('#female_low_value_ref_range').val('');
            $('#female_high_value_ref_range').val('');
            $('#nomanualvalues_ref_range').val('');
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
            var url = '/test/' + itemId;

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
