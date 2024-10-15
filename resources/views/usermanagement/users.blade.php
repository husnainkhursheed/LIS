@extends('layouts.master')
@section('title')
        Users
@endsection
@section('css')
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <style>
         .error-message {
            color: red;
            font-size: 0.9em;
            display: none;
        }
        /* .time {
            font-size: 18px;
            font-weight: bold;
            margin: 20px;
            color: #22416b;
            text-align: center;
        } */
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #eff2f7;
        }
    </style>
    {{-- @component('components.breadcrumb')
        @slot('li_1')
            Pages
        @endslot
        @slot('title')
            Users
        @endslot
    @endcomponent --}}
    <div class="row">
        @error('email')
            <div class="alert alert-danger" id="alert-message">
                {{ $message }}
            </div>

            <script>
                // Add a timer to automatically dismiss the alert after 5 seconds (adjust as needed)
                setTimeout(function() {
                    document.getElementById('alert-message').style.display = 'none';
                }, 5000); // 5000 milliseconds = 5 seconds
            </script>
        @enderror
        @if (Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                {{ Session::get('message') }}
            </div>

            <script>
                // Add a timer to automatically dismiss the alert after 5 seconds (adjust as needed)
                setTimeout(function() {
                    document.getElementById('alert-message').style.display = 'none';
                }, 5000); // 5000 milliseconds = 5 seconds
            </script>
        @endif
        @error('name')
            <div class="alert alert-danger" id="alert-message">
                {{ $message }}
            </div>

            <script>
                // Add a timer to automatically dismiss the alert after 5 seconds (adjust as needed)
                setTimeout(function() {
                    document.getElementById('alert-message').style.display = 'none';
                }, 5000); //
            </script>
        @enderror
        <div class="col-lg-12">

            {{-- <div class="card "> --}}
                {{-- <div class="card-header d-flex justify-content-between">
                    <button type="button" class="btn btn-primary add-btn align-item-end ms-auto" data-bs-toggle="modal"
                    id="create-btn" data-bs-target="#showModal"><i
                        class="ri-add-line align-bottom me-1 "></i> Add
                    User</button>
                    <h5 class="card-title mb-0">Buttons Datatables</h5>
                </div> --}}

                <div class="col">
                    <div class="card p-3 bg-white">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="text-dark">List of Users</h3>
                            <button type="button" class="btn btn-primary add-btn align-item-end ms-auto" data-bs-toggle="modal"
                                id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1 "></i> Add
                                User</button>
                        </div>

                        <div class="col my-2">
                            <nav class="navbar">
                                <div class="container-fluid p-0">
                                    <form class="d-flex" method="GET" action="{{ route('users.index') }}">
                                        <input class="form-control me-2 main-search" type="search" placeholder="Search"
                                            aria-label="Search" name="search" value="{{ request('search') }}">
                                        <button class="btn search-btn" type="submit">Search</button>
                                    </form>
                                    <form class="d-flex" method="GET" action="{{ route('users.index') }}">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <select class="form-select sort-dropdown" aria-label="Default select example"
                                            name="sort_by" onchange="this.form.submit()">
                                            <option selected disabled>Sort By</option>
                                            <option value="first_name"
                                                {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>Name</option>
                                            <option value="email"
                                                {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email
                                            </option>
                                        </select>
                                    </form>
                                </div>
                            </nav>

                        </div>
                        <table id="" class="table table-striped display table-responsive rounded">
                            <thead>
                                <tr>
                                    <th class="rounded-start-3 ">Full Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th class="rounded-end-3 ">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->first_name }} {{ $user->surname }}</td>
                                        <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge bg-primary-subtle text-white">{{ $role->name }}</span>
                                        @endforeach
                                    </td>

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
                                                    <a class="edit-item-btn" data-id="{{ $user->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                            class="ri-pencil-fill align-bottom text-muted"></i></a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                    data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                    <a class="remove-item-btn" data-id="{{ $user->id }}"  data-bs-toggle="modal"
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
                            @if ($users->previousPageUrl())
                                <li class="page-item previousPageUrl">
                                    <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item previousPageUrl disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                                </li>
                            @endif

                            @for ($page = 1; $page <= $users->lastPage(); $page++)
                                <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="{{ $users->url($page) }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                                </li>
                            @endfor

                            @if ($users->nextPageUrl())
                                <li class="page-item nextPageUrl">
                                    <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Next">
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
                                <th>Roles</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge bg-primary-subtle text-primary">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <ul class="list-inline hstack gap-2 mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                <a class="edit-item-btn" data-id="{{ $user->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                        class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                <a class="remove-item-btn" data-id="{{ $user->id }}"  data-bs-toggle="modal"
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="leadtype_form" action="{{ url('/users') }}"
                    method="Post" autocomplete="off" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" id="id-field"/>
                        <div class="row g-3">
                            {{-- <div class="col-lg-12">
                                <div class="text-center">
                                    <div class="position-relative d-inline-block">
                                        <div class="position-absolute bottom-0 end-0">
                                            <label for="lead-image-input" class="mb-0" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                                <div class="avatar-xs cursor-pointer">
                                                    <div class="avatar-title bg-light border rounded-circle text-muted">
                                                        <i class="ri-image-fill"></i>
                                                    </div>
                                                </div>
                                            </label>
                                            <input class="form-control d-none"  id="lead-image-input" type="file"
                                                accept="image/png, image/gif, image/jpeg" name="userimage">
                                        </div>
                                        <div class="avatar-lg p-1">
                                            <div class="avatar-title bg-light rounded-circle">
                                                <img src="{{ URL::asset('build/images/users/user-dummy-img.jpg') }}"
                                        alt="" id="lead-img" class="avatar-md rounded-circle object-fit-cover" >
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="fs-13 mt-3">Profile</h5>
                                </div>

                            </div> --}}

                            <!--end col-->
                            <div class="col-lg-12">
                                <div>
                                    <label for="" class="form-label">
                                        First Name</label>
                                    <input type="text" id="first_name" name="first_name"
                                        class="form-control"  required />
                                </div>
                                <br>
                                <div>
                                    <label for="" class="form-label">
                                    Surname</label>
                                    <input type="text" id="surname" name="surname"
                                        class="form-control"  required />
                                </div>
                                <br>
                                <div>
                                    <label for="user_email" class="form-label">
                                        Email</label>
                                    <input type="text" id="user_email" name="email"
                                        class="form-control"  required />

                                </div>
                                <br>
                                @php
                                $user = Auth::user();
                                $roleName = $user->getRoleNames()->first();
                              @endphp

                            @if($roleName === 'Management')
                                <div>
                                    <label for="user_password" class="form-label">Password</label>
                                    <input type="password" id="user_password" name="password" class="form-control" />
                                    <span id="passwordHelpBlock" class="form-text">
                                        Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.
                                    </span>
                                    <span id="passwordError" class="error-message">Invalid password format.</span>
                                </div>
                            @endif
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <label for="role_select" class="form-label">
                                    User Type</label>
                                <select class="js-example-basic-multiple" name="role_ids[]" id="role_ids">
                                    @foreach ($roles as $rolevalue)
                                            <option value="{{ $rolevalue->id }}">
                                                {{ $rolevalue->name }}</option>
                                        @endforeach
                                </select>
                            </div>

                            {{-- <div class="col-lg-12" id="department_area">
                                <div>
                                    <label for="departments" class="form-label">Departments</label>
                                    <select class="form-control js-example-basic-multiple" name="departments[]" id="departments" multiple="multiple">
                                        <option value="1">Biochemistry / Haematology</option>
                                        <option value="2">Cytology / Gynecology</option>
                                        <option value="3">Urinalysis / Microbiology</option>
                                    </select>
                                </div>
                            </div> --}}
                            {{-- <div class="col-lg-12">
                                <div>
                                    <label for="leads_score-field" class="form-label">Roles</label>
                                    <select class="form-select mb-3" name="role_ids[]"
                                        id="role_ids" aria-label=".form-select-lg example" multiple
                                        required>
                                        @foreach ($roles as $rolevalue)
                                            <option value="{{ $rolevalue->id }}">
                                                {{ $rolevalue->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            {{-- <div class="col-lg-12">
                                <div class="form-check form-check-dark mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                        id="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div> --}}
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add </button>
                            {{-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> --}}
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
                        <h4 class="fs-semibold">You are about to delete a lead ?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your lead will
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


    {{-- <script src="{{ URL::asset('build/js/app.js') }}"></script> --}}
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
            var url = '{{ url("/users") }}' + '/' + itemId + '/edit';


            $.ajax({
                    url: url, // Adjust the route as needed
                    type: 'GET',
                    success: function(response) {
                        // Assuming the response has a 'leadType' key
                        var leadType = response.user;

                        // Now you can use the leadType data to populate your modal fields
                        $('#id-field').val(leadType.id);
                        $('#first_name').val(leadType.first_name);
                        $('#surname').val(leadType.surname);
                        $('#user_email').val(leadType.email);
                        $('#user_password').val(leadType.password);
                        var imagePath = leadType.avatar
                        ? '{{ URL::asset("uploads/") }}/' + leadType.avatar
                        : '{{ URL::asset("build/images/users/user-dummy-img.jpg") }}';
                        $('#lead-img').attr('src', imagePath);

                        // var roleIds = leadType.roles.map(function(role) {
                        //     return role.id;
                        // });

                        var roleIds = leadType.roles.map(function(role) {
                            return role.id;
                        });
                        var department_ids = leadType.departments.map(function(department) {
                            return department;
                        });

                        $('#role_ids').val(roleIds).trigger('change');
                        $('#departments').val(department_ids).trigger('change');
                        // Set the checkbox state for is_active
                        $('#is_active').prop('checked', leadType.is_active);

                        // Update modal title
                        $('#exampleModalLabel').html("Edit User");

                        // Display the modal footer
                        $('#showModal .modal-footer').css('display', 'block');

                        // Change the button text
                        // $('#add-btn').html("Update");
                        $('#add-btn').html("Update").addClass("validate-class");
                        var form = $('#leadtype_form');

                        // Update the form action (assuming the form has an ID of 'your-form-id')
                        $('#leadtype_form').attr('action', '{{ url("/users") }}/' + itemId);

                        // $('#leadtype_form').attr('method', 'POST');
                        // if (!form.find('input[name="_method"]').length) {
                        //     // Add a hidden input field for the method
                        //     form.append('<input type="hidden" name="_method" id="patch" value="PATCH">');
                        // }
                        // $('#showModal').modal('show');

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                        // Handle errors if needed
                    }
                });

            // Populate the hidden field in the modal with the ID




            // Open the modal
        });

        function resetModal() {
            // Reset modal title
            $('#exampleModalLabel').html("Add User");

            // Display the modal footer
            $('#showModal .modal-footer').css('display', 'block');

            // Change the button text
            $('#add-btn').html("Add");
            $('#leadtype_form').attr('action', '{{ url("/users") }}');
            // if ( $('#patch').length) {
            //     $('#patch').remove();
            // }

            // Reset form fields
            $('#id-field').val('');
            $('#first_name').val('');
            $('#user_email').val('');
            $('#surname').val('');
            $('#user_password').val('');
            $('#role_ids').val('').trigger('change');
            $('#department').val('').trigger('change');
            $('#is_active').prop('checked', true);
            $('#lead-img').attr('src', '{{ URL::asset("build/images/users/user-dummy-img.jpg") }}');
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
            var url = '/users/' + itemId;

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
            // setTimeout(function() {
            //     document.getElementById('alert-message').style.display = 'none';
            // }, 5000); // 5000 milliseconds = 5 seconds
    });


    </script>
    <script>
    //    document.addEventListener('DOMContentLoaded', function() {
    //         // Initialize select2
    //         $('.js-example-basic-multiple').select2();

    //         $('#role_ids').on('change', function() {
    //             var selectedRoles = $(this).val();
    //             var showDepartment = false;

    //             // Check if any of the selected roles is "Lab"
    //             $('#role_ids option:selected').each(function() {
    //                 if ($(this).text().trim().toLowerCase() === 'lab') {
    //                     showDepartment = true;
    //                 }
    //             });

    //             // Show or hide the department area based on the selected roles
    //             if (showDepartment) {
    //                 $('#department_area').show();
    //             } else {
    //                 $('#department_area').hide();
    //                 $('#departments').val('').trigger('change');
    //             }
    //         });

    //         // Trigger change event to set initial visibility of department area
    //         $('#role_ids').trigger('change');
    //     });


// password ERROR
    document.getElementById('user_password').addEventListener('input', function() {
                const password = this.value;
                const passwordError = document.getElementById('passwordError');
                const submitBtn = document.getElementById('add-btn');
                // console.log(submitBtn);
                const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

                if (password === "") {
                    passwordError.style.display = 'none';
                    submitBtn.disabled = false; // Enable the submit button if the password is empty
                } else if (pattern.test(password)) {
                    passwordError.style.display = 'none';
                    submitBtn.disabled = false; // Enable the submit button if the password is valid
                } else {
                    passwordError.style.display = 'block';
                    submitBtn.disabled = true; // Disable the submit button if the password is invalid
                }
            });
// validate update btn
        // document.addEventListener('DOMContentLoaded', function() {
        //     const password = document.getElementById('user_password').value;
        //     const submitBtn = document.getElementsByClassName('validate-class');
        //     console.log(submitBtn[0]);
        //     const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        //     if (password === "" || !pattern.test(password)) {
        //         submitBtn.disabled = true; // Disable the submit button initially if the password is invalid or empty
        //     }
        // });
        </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
@endsection
