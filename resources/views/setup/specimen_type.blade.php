@extends('layouts.master')
@section('title')
    Specimen Types
@endsection
@section('content')
    <div class="row">
        @include('layouts.notification')

        <div class="col-lg-12">
            <div class="card p-3 bg-white">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="text-dark">Manage Specimen Types</h3>
                    <button type="button" class="btn btn-primary add-btn align-item-end ms-auto" data-bs-toggle="modal"
                        id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1"></i> Add
                        Specimen Type</button>
                </div>

                <div class="col my-2">
                    <table class="table table-striped display table-responsive rounded">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Priority</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($specimenTypes as $specimenType)
                                <tr>
                                    <td>{{ $specimenType->name }}</td>
                                    <td>{{ $specimenType->priority }}</td>
                                    <td>
                                        <ul class="list-inline hstack gap-2 mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                <a class="edit-item-btn" data-id="{{ $specimenType->id }}" href="#showModal" data-bs-toggle="modal"><i class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                <a class="remove-item-btn" data-id="{{ $specimenType->id }}" data-bs-toggle="modal" href="#deleteRecordModal">
                                                    <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Specimen Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="specimen_type_form" action="{{ url('/specimen-types') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div>
                                    <label for="name" class="form-label">Specimen Type Name</label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter Specimen Type Name" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="priority" class="form-label">Priority</label>
                                    <input type="number" id="priority" name="priority" class="form-control" placeholder="Enter Priority" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add Specimen Type</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold">You are about to delete a Specimen Type?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting this specimen type will remove all of your information from our database.</p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none" data-bs-dismiss="modal" id="deleteRecord-close"><i class="ri-close-line me-1 align-middle"></i> Close</button>
                            <button class="btn btn-danger" id="delete-record">Yes, Delete It!!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        jQuery(document).ready(function($) {
            $('.edit-item-btn').on('click', function() {
                var itemId = $(this).data('id');
                var url = '{{ url('/specimen-types') }}' + '/' + itemId + '/edit';

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        var specimenType = response.specimenType;
                        $('#id-field').val(specimenType.id);
                        $('#name').val(specimenType.name);
                        $('#priority').val(specimenType.priority);
                        $('#exampleModalLabel').html("Edit Specimen Type");
                        $('#add-btn').html("Update");
                        $('#specimen_type_form').attr('action', '{{ url('/specimen-types') }}/' + itemId);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                    }
                });
            });

            $('.remove-item-btn').on('click', function() {
                var itemId = $(this).data('id');
                $('#delete-record').attr('data-id', itemId);
            });

            $('#delete-record').on('click', function() {
                var itemId = $(this).data('id');
                var url = '/specimen-types/' + itemId;

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#deleteRecordModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                    }
                });
            });

            function resetModal() {
                // Reset modal titleq
                $('#exampleModalLabel').html("Add Specimen Type");

                // Display the modal footer
                $('#showModal .modal-footer').css('display', 'block');

                // Change the button text
                $('#add-btn').html("Add");
                $('#specimen_type_form').attr('action', '{{ url('/specimen-types') }}');
                // if ( $('#patch').length) {
                //     $('#patch').remove();
                // }
                $('#name').val('');
                $('#priority').val('');

            }

            // Event listener for modal close event
            $('#showModal').on('hidden.bs.modal', function() {
                resetModal();
            });
        });
    </script>
@endsection
