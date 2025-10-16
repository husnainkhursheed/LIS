@extends('layouts.master')
@section('title')
        Patient
@endsection
@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <style>
        .autocomplete-suggestions {
            position: absolute;
            z-index: 1000;
            background: white;
            border: 1px solid #ddd;
            max-height: 200px;
            overflow-y: auto;
            width: calc(100% - 2px); /* Match input width */
        }

        .autocomplete-suggestions div {
            padding: 8px;
            cursor: pointer;
        }

        .autocomplete-suggestions div:hover {
            background-color: #f5f5f5;
        }
    </style>
@endsection
@section('content')
    {{-- @component('components.breadcrumb')
        @slot('li_1')
            Assets
        @endslot
        @slot('title')
            Patient
        @endslot
    @endcomponent --}}
    <div class="row">

        @include('layouts.notification')

        <div class="col-lg-12">

            {{-- <div class="card "> --}}

                <div class="col">
                    <div class="card p-3 bg-white">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="text-dark">List of patients</h3>
                            <button type="button" class="btn btn-primary add-btn align-item-end ms-auto" data-bs-toggle="modal"
                                id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1 "></i> Add
                                Patient</button>
                        </div>

                        <div class="col my-2">
                            <nav class="navbar">
                                <div class="container-fluid p-0">
                                    <form class="d-flex" method="GET" action="{{ route('patient.index') }}">
                                        <input class="form-control me-2 main-search" type="search" placeholder="Search"
                                            aria-label="Search" name="search" value="{{ request('search') }}">
                                        <button class="btn search-btn" type="submit">Search</button>
                                    </form>
                                    <form class="d-flex" method="GET" action="{{ route('patient.index') }}">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <select class="form-select sort-dropdown" aria-label="Default select example"
                                            name="sort_by" onchange="this.form.submit()">
                                            <option selected disabled>Sort By</option>
                                            <option value="first_name"
                                                {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>Name</option>
                                            <option value="contact_number"
                                                {{ request('sort_by') == 'contact_number' ? 'selected' : '' }}>Contact Number
                                            </option>
                                            <option value="sex"
                                                {{ request('sort_by') == 'sex' ? 'selected' : '' }}>Sex
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
                                    <th>Sex</th>
                                    <th>Status</th>
                                    <th class="rounded-end-3 ">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($patients as $patient)
                                    <tr>
                                        <td>{{ $patient->first_name.' '.$patient->surname }}</td>
                                        <td>{{ $patient->contact_number }}</td>
                                        <td>{{ $patient->sex }}</td>
                                        <td>{{ $patient->is_active == 1 ? 'Active' : 'InActive' }}</td>


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
                                                    <a class="edit-item-btn" data-id="{{ $patient->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                            class="ri-pencil-fill align-bottom text-muted"></i></a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                    data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                    <a class="remove-item-btn" data-id="{{ $patient->id }}"  data-bs-toggle="modal"
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
                            @if ($patients->previousPageUrl())
                                <li class="page-item previousPageUrl">
                                    <a class="page-link" href="{{ $patients->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item previousPageUrl disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                                </li>
                            @endif

                            @for ($page = 1; $page <= $patients->lastPage(); $page++)
                                <li class="page-item {{ $patients->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="{{ $patients->url($page) }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                                </li>
                            @endfor

                            @if ($patients->nextPageUrl())
                                <li class="page-item nextPageUrl">
                                    <a class="page-link" href="{{ $patients->nextPageUrl() }}" aria-label="Next">
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
                                <th>Sex</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patients as $patient)
                                <tr>
                                    <td>{{ $patient->first_name }}</td>
                                    <td>{{ $patient->contact_number }}</td>
                                    <td>{{ $patient->sex }}</td>
                                    <td>{{ $patient->is_active == 1 ? 'Active' : 'InActive' }}</td>

                                    <td>
                                        <ul class="list-inline hstack gap-2 mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                <a class="edit-item-btn" data-id="{{ $patient->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                        class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                <a class="remove-item-btn" data-id="{{ $patient->id }}"  data-bs-toggle="modal"
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

    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary-subtle p-3">
                <h5 class="modal-title" id="exampleModalLabel">Add Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <form class="tablelist-form" id="leadtype_form" action="{{ url("/patient") }}" method="Post" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="id-field" />
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <div>
                                <label for="companyname-field" class="form-label">First Name</label>
                                <input type="text" id="first_name" name="first_name" class="form-control"
                                    placeholder="Enter First Name" required autocomplete="off" />
                                <div id="first_name_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label for="surname" class="form-label">Surname</label>
                                <input type="text" id="surname" name="surname" class="form-control"
                                placeholder="Enter surname" required autocomplete="off" />
                                <div id="surname_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" id="contact_number" class="form-control" name="contact_number"
                                    placeholder="Enter Contact Number" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label for="dob" class="form-label">DOB</label>
                                <input type="date" id="dob" name="dob" class="form-control"
                                    placeholder="Enter Dob" autocomplete="off" />
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
                        <div id="duplicate_warning" class="alert alert-warning d-none">
                            <p>Potential duplicate patients found:</p>
                            <ul id="duplicate_list"></ul>
                            <p>Do you want to proceed with creating a new patient?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="add-btn">Add Patient</button>
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
                        <h4 class="fs-semibold">You are about to delete a Patient ?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your Patient will
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
            var url = '{{ url("/patient") }}' + '/' + itemId + '/edit';

            $.ajax({
                    url: url, // Adjust the route as needed
                    type: 'GET',
                    success: function(response) {
                        // Assuming the response has a 'leadType' key
                        var patient = response.patient;
                        // console.log("my practices ",doctor);

                        // Now you can use the leadType data to populate your modal fields
                        $('#id-field').val(patient.id);
                        $('#first_name').val(patient.first_name);
                        // $('#phone').val(patient.phone);
                        $('#surname').val(patient.surname);
                        $('#contact_number').val(patient.contact_number);
                        $('#dob').val(patient.dob);
                        // $('#area').val(patient.area);
                        // $('#email').val(patient.email);

                        // var surgeries = SetupPractice.surgeries.map(function(surgery) {
                        //         return surgery.id;
                        //     });

                        // $('#surgeries').val(surgeries).trigger('change');

                        $('#is_active').prop('checked', patient.is_active);


                        // Set the checkbox town for is_active
                        if (patient.sex === 'Male') {
                            $('#male').prop('checked', true);
                        } else if (patient.sex === 'Female') {
                            $('#female').prop('checked', true);
                        }

                        // Update modal title
                        $('#exampleModalLabel').html("Edit Patient");

                        // Display the modal footer
                        $('#showModal .modal-footer').css('display', 'block');

                        // Change the button text
                        $('#add-btn').html("Update");
                        var form = $('#leadtype_form');

                        // Update the form action (assuming the form has an ID of 'your-form-id')
                        $('#leadtype_form').attr('action', '{{ url("/patient") }}/' + itemId);



                        // $('#showModal').modal('show');

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                        // Handle errors if needed
                    }
                });

        });

        function resetModal() {
            // Reset modal titleq
            $('#exampleModalLabel').html("Add Patient");

            // Display the modal footer
            $('#showModal .modal-footer').css('display', 'block');

            // Change the button text
            $('#add-btn').html("Add");
            $('#leadtype_form').attr('action', '{{ url("/patient") }}');
            // if ( $('#patch').length) {
            //     $('#patch').remove();
            // }
            $('#id-field').val('');
            $('#first_name').val('');
            // $('#phone').val(patient.phone);
            $('#surname').val('');
            $('#contact_number').val('');
            $('#dob').val('');
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
            var url = '/patient/' + itemId;

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


    document.addEventListener('DOMContentLoaded', function() {
        const firstNameInput = document.getElementById('first_name');
        const surnameInput = document.getElementById('surname');
        const firstNameSuggestions = document.getElementById('first_name_suggestions');
        const surnameSuggestions = document.getElementById('surname_suggestions');
        const dobInput = document.getElementById('dob');
        const duplicateWarning = document.getElementById('duplicate_warning');
        const duplicateList = document.getElementById('duplicate_list');
        const form = document.getElementById('leadtype_form');

        // Debounce function to limit API calls
        function debounce(func, timeout = 300) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => { func.apply(this, args); }, timeout);
            };
        }

        // Fetch suggestions
        async function fetchSuggestions(field, value, suggestionElement) {
            if (value.length < 2) {
                suggestionElement.innerHTML = '';
                return;
            }

            try {
                const response = await fetch(`/patient/suggestions?${field}=${encodeURIComponent(value)}`);
                const data = await response.json();

                suggestionElement.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(patient => {
                        const div = document.createElement('div');
                        div.textContent = `${patient.first_name} ${patient.surname} (${patient.dob || 'No DOB'})`;
                        div.addEventListener('click', () => {
                            firstNameInput.value = patient.first_name;
                            surnameInput.value = patient.surname;
                            if (patient.dob) dobInput.value = patient.dob.split(' ')[0];
                            suggestionElement.innerHTML = '';
                            checkForDuplicates();
                        });
                        suggestionElement.appendChild(div);
                    });
                }
            } catch (error) {
                console.error('Error fetching suggestions:', error);
            }
        }

        // Check for duplicate patients
        async function checkForDuplicates() {
            const firstName = firstNameInput.value.trim();
            const surname = surnameInput.value.trim();
            const dob = dobInput.value;

            if (firstName.length < 2 || surname.length < 2) {
                duplicateWarning.classList.add('d-none');
                return;
            }

            try {
                let url = `/patient/check-duplicates?first_name=${encodeURIComponent(firstName)}&surname=${encodeURIComponent(surname)}`;
                if (dob) url += `&dob=${encodeURIComponent(dob)}`;

                const response = await fetch(url);
                const duplicates = await response.json();

                if (duplicates.length > 0) {
                    duplicateList.innerHTML = '';
                    duplicates.forEach(patient => {
                        const li = document.createElement('li');
                        li.textContent = `${patient.first_name} ${patient.surname} (DOB: ${patient.dob || 'N/A'}, Contact: ${patient.contact_number || 'N/A'})`;
                        duplicateList.appendChild(li);
                    });
                    duplicateWarning.classList.remove('d-none');
                } else {
                    duplicateWarning.classList.add('d-none');
                }
            } catch (error) {
                console.error('Error checking for duplicates:', error);
            }
        }

        // Event listeners with debounce
        firstNameInput.addEventListener('input', debounce(() => {
            fetchSuggestions('first_name', firstNameInput.value.trim(), firstNameSuggestions);
            checkForDuplicates();
        }));

        surnameInput.addEventListener('input', debounce(() => {
            fetchSuggestions('surname', surnameInput.value.trim(), surnameSuggestions);
            checkForDuplicates();
        }));

        dobInput.addEventListener('change', debounce(checkForDuplicates));

        // Hide suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target !== firstNameInput && e.target !== surnameInput) {
                firstNameSuggestions.innerHTML = '';
                surnameSuggestions.innerHTML = '';
            }
        });

        // Form submission handler
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const firstName = firstNameInput.value.trim();
            const surname = surnameInput.value.trim();
            const dob = dobInput.value;

            // Final duplicate check before submission
            try {
                let url = `/patient/check-duplicates?first_name=${encodeURIComponent(firstName)}&surname=${encodeURIComponent(surname)}`;
                if (dob) url += `&dob=${encodeURIComponent(dob)}`;

                const response = await fetch(url);
                const duplicates = await response.json();

                if (duplicates.length > 0) {
                    if (!confirm('Potential duplicates found. Are you sure you want to create a new patient?')) {
                        return;
                    }
                }

                // Proceed with form submission
                form.submit();
            } catch (error) {
                console.error('Error during final duplicate check:', error);
                form.submit();
            }
        });
    });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
@endsection
