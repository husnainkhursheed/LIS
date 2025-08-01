@extends('layouts.master')
@section('title')
    Practice
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
            Setup
        @endslot
        @slot('title')
            Practice
        @endslot
    @endcomponent --}}
    <style>

    </style>
    <div class="row">
        @include('layouts.notification')

        <div class="col">
            <div class="card p-3 bg-white">
                <h3 class="text-dark">List of samples Being Processed</h3>
                <div class="col my-2">
                    <nav class="navbar">
                        <div class="container-fluid p-0">
                            <form class="d-flex" method="GET" action="{{ route('practice.index') }}">
                                <input class="form-control me-2 main-search" type="search" placeholder="Search" aria-label="Search" name="search" value="{{ request('search') }}">
                                <button class="btn search-btn" type="submit">Search</button>
                            </form>
                            <form class="d-flex" method="GET" action="{{ route('practice.index') }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <select class="form-select sort-dropdown" aria-label="Default select example" name="sort_by" onchange="this.form.submit()">
                                    <option selected disabled>Sort By</option>
                                    <option value="v_name" {{ request('sort_by') == 'v_name' ? 'selected' : '' }}>Name</option>
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                                    <option value="town" {{ request('sort_by') == 'town' ? 'selected' : '' }}>Town</option>
                                    <option value="country" {{ request('sort_by') == 'country' ? 'selected' : '' }}>Country</option>
                                </select>

                                {{-- <select class="form-select sort-order-dropdown" aria-label="Default select example" name="sort_order" onchange="this.form.submit()">
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                                </select> --}}
                            </form>
                        </div>
                    </nav>

                </div>
                <table id="" class="table table-striped display table-responsive rounded">
                    <thead>
                        <tr>
                            <th class="rounded-start-3 ">Test #</th>
                            <th>Access #</th>
                            <th>Patient Name</th>
                            <th>Date Received</th>
                            <th class="rounded-end-3 ">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($practices as $practice)
                            <tr>
                                <td>{{ $practice->v_name }}</td>
                                <td>{{ $practice->telephone }}</td>
                                <td>{{ $practice->address }}</td>
                                <td>{{ $practice->is_active == 1 ? 'Active' : 'InActive' }}</td>

                                <td>
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
                                </td>
                                {{-- <td>
                                        <ul class="list-inline hstack gap-2 mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                <a class="edit-item-btn" data-id="{{ $practice->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                        class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                <a class="remove-item-btn" data-id="{{ $practice->id }}"  data-bs-toggle="modal"
                                                    href="#deleteRecordModal">
                                                    <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <ul class="pagination justify-content-center">
                    @if ($practices->previousPageUrl())
                        <li class="page-item previousPageUrl">
                            <a class="page-link" href="{{ $practices->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                    @else
                        <li class="page-item previousPageUrl disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                        </li>
                    @endif

                    @for ($page = 1; $page <= $practices->lastPage(); $page++)
                        <li class="page-item {{ $practices->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $practices->url($page) }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                        </li>
                    @endfor

                    @if ($practices->nextPageUrl())
                        <li class="page-item nextPageUrl">
                            <a class="page-link" href="{{ $practices->nextPageUrl() }}" aria-label="Next">
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

    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Practice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="leadtype_form" action="{{ url('/practice') }}" method="Post"
                    autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="v_name" class="form-label">Name</label>
                                    <input type="text" id="v_name" name="v_name" class="form-control" placeholder="Enter Practice name" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter Email" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" id="address" name="address" class="form-control" placeholder="Enter Address" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="town" class="form-label">Town</label>
                                    <input type="text" id="town" class="form-control" name="town" placeholder="Enter Town" required />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="zip" class="form-label">Zip</label>
                                    <input type="text" id="zip" name="zip" class="form-control" placeholder="Enter Zip" required />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" id="country" name="country" class="form-control" placeholder="Enter Country" required />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Telephone</label>
                                    <input type="tel" id="telephone" name="telephone" class="form-control" placeholder="Enter Telephone" required />
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add Practice</button>
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
                        <h4 class="fs-semibold">You are about to delete a lead ?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your lead will
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
    {{-- <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> --}}

    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        jQuery(document).ready(function($) {
            // When the document is ready, attach a click event to the "Edit" button
            $('.edit-item-btn').on('click', function() {
                // Get the ID from the data attribute

                var itemId = $(this).data('id');
                var url = '{{ url('/practice') }}' + '/' + itemId + '/edit';


                $.ajax({
                    url: url, // Adjust the route as needed
                    type: 'GET',
                    success: function(response) {
                        // Assuming the response has a 'leadType' key
                        var SetupPractice = response.SetupPractice;
                        console.log("my practices ", SetupPractice);

                        // Now you can use the leadType data to populate your modal fields
                        $('#id-field').val(SetupPractice.id);
                        $('#v_name').val(SetupPractice.v_name);
                        // $('#phone').val(SetupPractice.phone);
                        $('#address').val(SetupPractice.address);
                        $('#town').val(SetupPractice.town);
                        $('#zip').val(SetupPractice.zip);
                        $('#country').val(SetupPractice.country);
                        $('#telephone').val(SetupPractice.telephone);
                        $('#email').val(SetupPractice.email);

                        // var surgeries = SetupPractice.surgeries.map(function(surgery) {
                        //         return surgery.id;
                        //     });

                        // $('#surgeries').val(surgeries).trigger('change');


                        // Set the checkbox town for is_active
                        $('#is_active').prop('checked', SetupPractice.is_active);

                        // Update modal title
                        $('#exampleModalLabel').html("Edit Company");

                        // Display the modal footer
                        $('#showModal .modal-footer').css('display', 'block');

                        // Change the button text
                        $('#add-btn').html("Update");
                        var form = $('#leadtype_form');

                        // Update the form action (assuming the form has an ID of 'your-form-id')
                        $('#leadtype_form').attr('action', '{{ url('/practice') }}/' + itemId);



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
                $('#exampleModalLabel').html("Add Practice");

                // Display the modal footer
                $('#showModal .modal-footer').css('display', 'block');

                // Change the button text
                $('#add-btn').html("Add");
                $('#leadtype_form').attr('action', '{{ url('/practice') }}');
                // if ( $('#patch').length) {
                //     $('#patch').remove();
                // }
                $('#id-field').val('');
                $('#v_name').val('');
                // $('#phone').val('');
                $('#address').val('');
                $('#town').val('');
                $('#zip').val('');
                $('#country').val('');
                $('#telephone').val('');
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

            // $('#delete-record').on('click', function() {
            //     var itemId = $(this).data('id');
            //     var url = '/practice/' + itemId;

            //     $.ajax({
            //         url: url,
            //         type: 'DELETE',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             // Handle success, e.g., remove the deleted item from the UI
            //             console.log(response);
            //             $('#deleteRecordModal').modal('hide');
            //             location.reload();
            //         },
            //         error: function(xhr, status, error) {
            //             // Handle error
            //             console.error(xhr, status, error);
            //         }
            //     });
            // });


            // Function to reset modal when clicking the "Close" button
            $('#close-modal').on('click', function() {
                resetModal();
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
@endsection
