@extends('layouts.master')
@section('title')
    Sensitivity Area Profiles
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
                        <h3 class="text-dark">List of Profiles</h3>
                        <button type="button" class="btn btn-primary add-btn align-item-end ms-auto" data-bs-toggle="modal"
                            id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1 "></i> Add
                            Profile</button>
                    </div>

                    <div class="col my-2">
                        <nav class="navbar">
                            <div class="container-fluid p-0">
                                <form class="d-flex" method="GET" action="{{ route('profile.index') }}">
                                    <input class="form-control me-2 main-search" type="search" placeholder="Search"
                                        aria-label="Search" name="search" value="{{ request('search') }}">
                                    <button class="btn search-btn" type="submit">Search</button>
                                </form>
                                <form class="d-flex" method="GET" action="{{ route('profile.index') }}">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    <select class="form-select sort-dropdown" aria-label="Default select example"
                                        name="sort_by" onchange="this.form.submit()">
                                        <option selected disabled>Sort By</option>
                                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name
                                        </option>
                                        {{-- <option value="contact_number"
                                                {{ request('sort_by') == 'contact_number' ? 'selected' : '' }}>Contact Number
                                            </option> --}}
                                        {{-- <option value="sex"
                                                {{ request('sort_by') == 'sex' ? 'selected' : '' }}>Sex
                                            </option> --}}
                                    </select>
                                </form>
                            </div>
                        </nav>

                    </div>
                    <table id="" class="table table-striped display table-responsive rounded">
                        <thead>
                            <tr>
                                <th class="rounded-start-3 ">Name</th>
                                {{-- <th>Telephone</th>
                                    <th>Sex</th>
                                    <th>Status</th> --}}
                                <th class="rounded-end-3 ">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($profiles as $profile)
                                <tr>
                                    <td>{{ $profile->name }}</td>

                                    <td>
                                        <ul class="list-inline hstack gap-2 mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                data-bs-placement="top" title="Edit">
                                                <a class="edit-item-btn" data-id="{{ $profile->id }}" href="#showModal"
                                                    data-bs-toggle="modal"><i
                                                        class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                data-bs-placement="top" title="Delete">
                                                <a class="remove-item-btn" data-id="{{ $profile->id }}"
                                                    data-bs-toggle="modal" href="#deleteRecordModal">
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
                        @if ($profiles->previousPageUrl())
                            <li class="page-item previousPageUrl">
                                <a class="page-link" href="{{ $profiles->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                        @else
                            <li class="page-item previousPageUrl disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                            </li>
                        @endif

                        @for ($page = 1; $page <= $profiles->lastPage(); $page++)
                            <li class="page-item {{ $profiles->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ $profiles->url($page) }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                            </li>
                        @endfor

                        @if ($profiles->nextPageUrl())
                            <li class="page-item nextPageUrl">
                                <a class="page-link" href="{{ $profiles->nextPageUrl() }}" aria-label="Next">
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
                            @foreach ($profiles as $patient)
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
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="leadtype_form" action="{{ url('/profile') }}" method="Post"
                    autocomplete="off">

                    @csrf



                    <div class="modal-body">
                        <input type="hidden" id="id-field" />

                        <div class="row g-3">

                            <!-- Row 1 -->

                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div>
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" id="name" name="name"
                                                class="form-control" required />
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Row 2 -->

                            <div class="col-lg-12">

                                <div class="row">

                                    <div class="col-lg-12 mt-2">

                                        <div id="attribute-container">

                                            <div class="row input-group">

                                                <div class="col-md-12"><label for="antibiotic"
                                                        class="form-label">Antibiotic</label></div>

                                            </div>

                                            <div id="attribute-item">

                                                <div class="row input-group">

                                                    <div class="col-md-12">

                                                        <input type="text" class="form-control antibiotic-value"
                                                            id="antibiotic" style=""
                                                            name="antibiotic[]" required />

                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-lg-12 mt-2">
                                        <a id="add-item" class="btn btn-soft-primary fw-medium text-white"><i
                                                class="ri-add-fill me-1 align-bottom "></i> Add Item</a>
                                    </div>

                                </div>



                            </div>

                        </div>

                        <!--end row-->

                    </div>

                    <div class="modal-footer">

                        <div class="hstack gap-2 justify-content-end">

                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>

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
                        <h4 class="fs-semibold">You are about to delete a profile ?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your profile will
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

            // Function to add a new input field and delete button

            function newInput() {

                var newItem =

                    // '<label for="attribute_values" class="form-label">Attribute Value</label>' +

                    '<div class="row input-group">' +

                        '<div class="col-md-10">' +

                        '<input type="hidden" class="form-control mt-1 " style="" id="senstivityItems_ids"  name="senstivityItems_ids[]" required />' +

                        '<input type="text" class="form-control antibiotic-value mt-1 " style="" id="antibiotic"  name="antibiotic[]" required />' +

                        '</div>'+

                        '<div class="col-md-2 mt-3 text-left">' +

                        '<span type="button" class="delete-item fs-5 "  style="margin-left: 4px;"><i class="ri-delete-bin-fill align-bottom text-danger"></i></span>' +

                        '</div>' ;

                    '</div>';

                $('#attribute-item').append(newItem);

            }

            // Function to remove the parent element (item) when delete button is clicked

            $('#attribute-item').on('click', '.delete-item', function() {

                $(this).closest('.input-group').remove();

            });

            // Event handler for the "Add Item" link

            $('#add-item').on('click', function() {
                newInput();
            });

        });
        jQuery(document).ready(function($) {
            // When the document is ready, attach a click event to the "Edit" button
            $('.edit-item-btn').on('click', function() {

                var itemId = $(this).data('id');
                var url = '{{ url('/profile') }}' + '/' + itemId + '/edit';

                $.ajax({
                    url: url, // Adjust the route as needed
                    type: 'GET',
                    success: function(response) {
                        var profile = response.profile;
                        console.log(profile);

                        $('#id-field').val(profile.id);
                        $('#name').val(profile.name);

                        $('#is_active').prop('checked', profile.is_active);

                        // Update modal title
                        $('#exampleModalLabel').html("Edit Profile");

                        // Display the modal footer
                        $('#showModal .modal-footer').css('display', 'block');

                        // Change the button text
                        $('#add-btn').html("Update");
                        var form = $('#leadtype_form');

                        // Update the form action (assuming the form has an ID of 'your-form-id')
                        $('#leadtype_form').attr('action', '{{ url('/profile') }}/' + itemId);

                        $('#attribute-item').empty();

                        profile.sensitivity_values.forEach((element, index) => {

                            var newItem =

                            '<div class="row input-group">' +

                            '<div class="col-md-10">' +

                            '<input type="hidden" class="form-control mt-1" id="senstivityItems_ids" name="senstivityItems_ids[]" value="' +
                            element.id + '" />' +

                            '<input type="text" class="form-control antibiotic-value mt-1" id="antibiotic" style="" name="antibiotic[]" value="' +
                            element.antibiotic + '" required />' +

                            '</div>' +

                            '<div class="col-md-2 mt-3 text-left">' +

                            // Add the delete button only if the index is greater than 0

                            (index > 0 ?
                                '<span type="button" class="delete-item mt-1 fs-5" style="margin-left: 4px;">  <i class="ri-delete-bin-fill align-bottom text-danger"></i></span>' :
                                '') +

                            '</div>' +

                            '</div>';



                        $('#attribute-item').append(newItem);

                        });



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
                $('#exampleModalLabel').html("Add Profile");

                // Display the modal footer
                $('#showModal .modal-footer').css('display', 'block');

                // Change the button text
                $('#add-btn').html("Add");
                $('#leadtype_form').attr('action', '{{ url('/profile') }}');
                // if ( $('#patch').length) {
                //     $('#patch').remove();
                // }
                $('#id-field').val('');
                $('#name').val('');
                $('#attribute-item').empty();

                var newItem =

                    // '<label for="attribute_values" class="form-label">Attribute Value</label>' +

                    '<div class="row input-group">' +

                    '<div class="col-md-12">' +

                    '<input type="hidden" class="form-control mt-1 " style="" id="senstivityItems_ids"  name="senstivityItems_ids[]" required />' +

                    '<input type="text" class="form-control antibiotic-value mt-1 " style="" id="antibiotic"  name="antibiotic[]" required />' +

                    '</div>' ;

                // '</div>';

                $('#attribute-item').append(newItem);

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
                var url = '/profile/' + itemId;

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
