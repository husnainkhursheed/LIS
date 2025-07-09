@extends('layouts.master')
@section('title')
    Add Sample
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
        .time {
            font-size: 18px;
            font-weight: bold;
            margin: 20px;
            color: #22416b;
            text-align: center;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #eff2f7;
        }
    </style>
    {{-- @component('components.breadcrumb')
        @slot('li_1')
            Dashboard
        @endslot
        @slot('title')
            Add Sample
        @endslot
    @endcomponent --}}
    @include('layouts.notification')
    {{-- <div class="row">
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

    </div> --}}
    {{-- start here  --}}

    <div class="container m-auto">
        <div class="form-wrap">
            <div class="position-absolute top-0 pt-4 end-0 translate-middle-y me-3 float-end time" >
                Time : <span id="liveTime"></span>
            </div>
            <header class="header">
                <h1 id="title" class="text-center">New Sample
                </h1>
            </header>
            {{-- <div id="liveTime"></div> --}}
            <form class="" id="leadtype_form" action="{{ url('/sample') }}" method="Post" autocomplete="off" >
            @csrf

                <div class="row">
                    {{-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="test_number" class="form-label">Test Number</label>
                                <input type="text" id="test_number" name="test_number" value="{{$test_number}}" class="form-control"
                                hidden required />
                                <input type="text" id="" name="" value="{{$test_number}}" class="form-control"
                                disabled  />
                        </div>
                    </div> --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="access_number" class="form-label">Access Number</label>
                            <input type="text" id="access_number" name="access_number"  value="{{$access_number}}" hi class="form-control"
                               hidden required />
                            <input type="text" id="" name="" value="{{$access_number}}" class="form-control"
                            disabled  />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                         <div class="form-group">
                            <label for="collected_date" class="form-label">Collected Date</label>
                            <input type="date" id="collected_date" name="collected_date" class="form-control"
                            value="{{ old('collected_date') }}" required />
                        </div>
                  </div>
                    <div class="col-md-6">
                         <div class="form-group">
                            <label for="received_date" class="form-label">Received Date </label>
                            <input type="date" class="form-control" id="received_date" name="received_date" value="{{ old('received_date') }}"/>
                        </div>
                     </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="doctor_id" class="form-label">Patient Name<a href=""
                                data-bs-toggle="modal" data-bs-target="#showModalPatient"
                                > <span class="badge bg-info text-white"> Add New</span> </a></label>
                                        <select class="js-example-basic-multiple form-control" name="patient_id" id="patient_id">
                                            <option value=""></option>
                                            @foreach ($patients as $patient)
                                                @php
                                                    $dateOfBirth = \Carbon\Carbon::parse($patient->dob)->format('d/m/Y');
                                                @endphp
                                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                                    {{ $patient->first_name .' '. $patient->surname .' '. $dateOfBirth }}</option>
                                            @endforeach
                                        </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="doctor_id" class="form-label">Doctor Name  <a href=""
                                data-bs-toggle="modal" data-bs-target="#showModalDoctor"
                                > <span class="badge bg-info text-white"> Add New</span> </a></label>
                            <select class="js-example-basic-multiple form-control" name="doctor_id" id="doctor_id">
                                <option value=""></option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="institution_id" class="form-label">Institution <a href=""
                                data-bs-toggle="modal" data-bs-target="#showModalInstitution"
                                > <span class="badge bg-info text-white"> Add New</span> </a></label>
                            <select class="js-example-basic-multiple form-control" name="institution_id" id="institution_id">
                                <option value=""></option>
                                @foreach ($institutions as $institution)
                                <option value="{{ $institution->id }}" {{ old('institution_id') == $institution->id ? 'selected' : '' }}>
                                    {{ $institution->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="institution" class="form-label">Bill</label>
                            <select class="js-example-basic-multiple form-control" name="bill_to" id="bill_to">
                                {{-- <option selected>Choose Institution</option> --}}
                                <option value="Patient" {{ old('bill_to') == 'Patient' ? 'selected' : '' }}>Patient</option>
                                <option value="Doctor" {{ old('bill_to') == 'Doctor' ? 'selected' : '' }}>Doctor</option>
                                <option value="Other" {{ old('bill_to') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" name="notes" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="test_profiles" class="form-label">Test Profiles</label>
                            <select  class="js-example-basic-multiple"   name="test_profiles[]" id="test_profiles" onchange="checkTestProfiles()" multiple="multiple">
                                @foreach ($test_profiles as $item)
                                            <option value="{{$item->id}}" data-cost="{{ $item->cost }}" {{ (collect(old('test_profiles'))->contains($item->id)) ? 'selected' : '' }}>{{$item->name.' '. $item->cost}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="total_cost" class="form-label">Total Cost</label>
                            <input type="text" class="form-control" name="total_cost_profile" id="total_cost_profile" >
                        </div>
                    </div>
                    <div class="x">
                        <div class="form-group">
                            <label for="test_requested" class="form-label">Individual Tests</label>
                            <select class="js-example-basic-multiple" name="test_requested[]" id="test_requested" multiple="multiple">
                                {{-- {{dd($tests->where('department'!= null))}} --}}
                                @foreach ($tests as $test)
                                    <option value="{{ $test->id }}" data-cost="{{ $test->cost }}" {{ (collect(old('test_requested'))->contains($test->id)) ? 'selected' : '' }}>
                                        {{ $test->name .' '. $test->specimen_type .' '. $test->cost }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="total_cost" class="form-label">Total Cost</label>
                            <input type="text" class="form-control" name="total_cost" id="total_cost" >
                        </div>
                    </div>
                </div>
                <!-- Row for Grand Total -->
                <div class="row align-items-center p-0" style="text-align: right">
                    <div class="col-md-10 form-group mt-2">
                        <label for="grand_total" class="form-label">Grand Total:</label>
                    </div>
                    <div class="col-md-2 p-0">
                        <div class="form-group">
                            <input type="text" class="form-control" name="grand_total" id="grand_total" >
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 d-flex justify-content-center">
                        <button type="submit" id="submit" class="btn btn-primary btn-sm btn-block submit-btn">Submit New Sample</button>
                    </div>
                </div>

            </form>
        </div>
    </div>


    {{-- end  --}}

    <!--patient-modal-->
    <div class="modal fade" id="showModalPatient" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="patient_form" action="{{ url("/patient") }}" method="Post" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div>
                                    <label for="companyname-field"
                                        class="form-label">First Name</label>
                                    <input type="text" id="first_name" name="first_name"
                                        class="form-control"
                                        placeholder="Enter First Name" required />
                                </div>
                                {{-- @error('v_name')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror --}}
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="surname" class="form-label">Surname</label>
                                    <input type="text" id="surname" name="surname" class="form-control"
                                    placeholder="Enter surname" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" id="contact_number" class="form-control" name="contact_number"
                                        placeholder="Enter Contact Number" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="dob" class="form-label">DOB</label>
                                    <input type="date" id="dob" name="dob" class="form-control"
                                        placeholder="Enter Dob" required />
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
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add Patient</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end Modal -->

    {{-- DOCTOR MODEL --}}
    <div class="modal fade" id="showModalDoctor" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="doctor_form" action="{{ url("/doctor") }}" method="Post" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div>
                                    <label for="companyname-field"
                                        class="form-label">Doctor’s Name</label>
                                    <input type="text" id="name" name="name"
                                        class="form-control"
                                        placeholder="Enter Doctor’s Name" required />
                                </div>
                                {{-- @error('v_name')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror --}}
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="Enter Email" required />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="address" class="form-label">Contact Number</label>
                                    <input type="text" id="contact_number" name="contact_number" class="form-control"
                                    placeholder="Enter Contact Number" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="town" class="form-label">Street name</label>
                                    <input type="text" id="street_name" class="form-control" name="street_name"
                                        placeholder="Enter Town" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="zip" class="form-label">Second line in address</label>
                                    <input type="text" id="address_line_2" name="address_line_2" class="form-control"
                                        placeholder="Enter Address" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="country" class="form-label">Area</label>
                                    <input type="text" id="area" name="area" class="form-control"
                                        placeholder="Enter Area" required />
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
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add Doctor</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end model  --}}

    {{-- insitution model  --}}
    <div class="modal fade" id="showModalInstitution" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Institution</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="institution_form" action="{{ url("/institution") }}" method="Post" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div>
                                    <label for="companyname-field"
                                        class="form-label">Institution Name</label>
                                    <input type="text" id="name" name="name"
                                        class="form-control"
                                        placeholder="Enter Institution Name" required />
                                </div>
                                {{-- @error('v_name')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror --}}
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="Enter Email" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="address" class="form-label">Contact Number</label>
                                    <input type="text" id="contact_number" name="contact_number" class="form-control"
                                    placeholder="Enter Contact Number" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="town" class="form-label">Street name</label>
                                    <input type="text" id="street_name" class="form-control" name="street_name"
                                        placeholder="Enter Town" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="zip" class="form-label">Second line in address</label>
                                    <input type="text" id="address_line_2" name="address_line_2" class="form-control"
                                        placeholder="Enter Address" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="country" class="form-label">Area</label>
                                    <input type="text" id="area" name="area" class="form-control"
                                        placeholder="Enter Area" required />
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
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add Institution</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end model  --}}

    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="reviewModalLabel">Review Sample Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="reviewContent">
                        <!-- Review content will be filled by JS -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Edit</button>
                    <button type="button" class="btn btn-success" id="confirmSubmit">Confirm & Submit</button>
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

            // On confirm, submit the form
            // $('#confirmSubmit').on('click', function() {
            //     document.forms['leadtype_form'].submit();
            // });
        // Set max for received_date to today
        var today = new Date().toISOString().split('T')[0];
        $('#received_date').attr('max', today);

        function validateDates(showAlert = true) {
            var collected = $('#collected_date').val();
            var received = $('#received_date').val();

            if (collected && received) {
                if (collected > received) {
                    if (showAlert) alert('Collected date cannot be later than received date.');
                    $('#collected_date').val('');
                    return false;
                }
            }
            if (received && received > today) {
                if (showAlert) alert('Received date cannot be in the future.');
                $('#received_date').val('');
                return false;
            }
            return true;
        }

        $('#collected_date, #received_date').on('change', function() {
            validateDates(true);
        });

        // $('#leadtype_form').on('submit', function(e) {
        //     if (!validateDates(true)) {
        //         e.preventDefault();
        //         return false;
        //     }
        // });

        function checkTestProfiles() {
            $('#test_requested').val('').trigger('change');
            // Get selected profile IDs
            let selectedProfiles = $('#test_profiles').val();
            if (selectedProfiles === null || selectedProfiles.length === 0) {
                console.log('No profiles selected, enabling all options');
                // Re-enable all options if no profiles are selected
                $('#test_profiles option').each(function() {
                    $(this).prop('disabled', false);
                });
                $('#test_requested option').each(function() {
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
                        $('#test_profiles option').each(function() {
                            // Disable the profiles that are in the profilesToHide array
                            if (response.profilesToHide.includes(parseInt($(this).val()))) {
                                $(this).prop('disabled', true);  // Disable the option
                            } else {
                                $(this).prop('disabled', false); // Enable the option
                            }
                        });
                        // Refresh the Select2 options
                        $('#test_profiles').select2();
                        $('#test_requested option').each(function() {
                            // Disable the profiles that are in the profilesToHide array
                            if (response.testIdsInSelectedProfiles.includes(parseInt($(this).val()))) {
                                $(this).prop('disabled', true);  // Disable the option
                            } else {
                                $(this).prop('disabled', false); // Enable the option
                            }
                        });
                        // Refresh the Select2 options
                        $('#test_requested').select2();
                    }
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }
       $(document).ready(function() {

            $('#submit').on('click', function(e) {
                e.preventDefault();

                // Gather form data for review
                let patient = $('#patient_id option:selected').text();
                let doctor = $('#doctor_id option:selected').text();
                let institution = $('#institution_id option:selected').text();
                let billTo = $('#bill_to').val();
                let collected = $('#collected_date').val();
                let received = $('#received_date').val();
                // let notes = $('#notes').val();
                let profiles = $('#test_profiles option:selected').map(function(){return $(this).text();}).get().join(', ');
                let tests = $('#test_requested option:selected').map(function(){return $(this).text();}).get().join(', ');
                let totalProfile = $('#total_cost_profile').val();
                let totalTest = $('#total_cost').val();
                let grandTotal = $('#grand_total').val();

                let html = `
                    <strong>Patient:</strong> ${patient}<br>
                    <strong>Doctor:</strong> ${doctor}<br>
                    <strong>Institution:</strong> ${institution}<br>
                    <strong>Bill To:</strong> ${billTo}<br>
                    <strong>Collected Date:</strong> ${collected}<br>
                    <strong>Received Date:</strong> ${received}<br>
                    <strong>Test Profiles:</strong> ${profiles}<br>
                    <strong>Individual Tests:</strong> ${tests}<br>
                    <strong>Total Profile Cost:</strong> ${totalProfile}<br>
                    <strong>Total Test Cost:</strong> ${totalTest}<br>
                    <strong>Grand Total:</strong> ${grandTotal}<br>
                `;
                $('#reviewContent').html(html);
                $('#reviewModal').modal('show');
            });
            document.getElementById('confirmSubmit').addEventListener('click', function () {
                console.log(document.getElementById('leadtype_form'));
                document.getElementById('leadtype_form').requestSubmit();
            });

            // Intercept submit button

            function calculateGrandTotal() {
                let totalCost = parseFloat($('#total_cost').val()) || 0;
                let totalProfileCost = parseFloat($('#total_cost_profile').val()) || 0;
                let grandTotal = totalCost + totalProfileCost;

                $('#grand_total').val(grandTotal.toFixed(2));
            }
            calculateGrandTotal();
            $('#test_requested').on('change', function() {
                let totalCost = 0;

                // Iterate through each selected option
                $(this).find('option:selected').each(function() {
                    totalCost += parseFloat($(this).data('cost'));
                });

                // Update the total_cost input field
                $('#total_cost').val(totalCost.toFixed(2));
                calculateGrandTotal();
            });
            $('#test_profiles').on('change', function() {
                let totalCost = 0;

                // Iterate through each selected option
                $(this).find('option:selected').each(function() {
                    totalCost += parseFloat($(this).data('cost'));
                });

                // Update the total_cost input field
                $('#total_cost_profile').val(totalCost.toFixed(2));
                calculateGrandTotal();
            });
            // Initialize Select2
            // $('#doctor_id').select2();

            // // Select the last option
            // $('#doctor_id option:last').attr('selected', 'selected');
            // $('#doctor_id').trigger('change'); // Notify any JS listeners of the change
            // $('#institution_id option:last').attr('selected', 'selected');
            // $('#institution_id').trigger('change'); // Notify any JS listeners of the change
            // $('#patient_id option:last').attr('selected', 'selected');
            // $('#patient_id').trigger('change'); // Notify any JS listeners of the change


            $(document).ready(function() {
                // Initialize Select2
                $('#patient_id').select2();

                // Handle form submission via AJAX
                $('#patient_form').on('submit', function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: '{{ url("/patient") }}',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.success) {
                                // Append the new patient to the dropdown
                                var newOption = new Option(response.patient.first_name, response.patient.id, true, true);
                                $('#patient_id').append(newOption).trigger('change');

                                // Close the modal
                                $('#showModalPatient').modal('hide');

                                // Optionally, clear the form inputs
                                $('#patient_form')[0].reset();
                            } else {
                                alert('An error occurred while adding the patient.');
                            }
                        },
                        error: function(response) {
                            alert('An error occurred. Please check the input data.');
                        }
                    });
                });
                $('#doctor_form').on('submit', function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: '{{ url("/doctor") }}',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.success) {
                                // Append the new patient to the dropdown
                                var newOption = new Option(response.doctor.name, response.doctor.id, true, true);
                                $('#doctor_id').append(newOption).trigger('change');

                                // Close the modal
                                $('#showModalDoctor').modal('hide');

                                // Optionally, clear the form inputs
                                $('#doctor_form')[0].reset();
                            } else {
                                alert('An error occurred while adding the patient.');
                            }
                        },
                        error: function(response) {
                            alert('An error occurred. Please check the input data.');
                        }
                    });
                });
                $('#institution_form').on('submit', function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: '{{ url("/institution") }}',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.success) {
                                // Append the new patient to the dropdown
                                var newOption = new Option(response.institution.name, response.institution.id, true, true);
                                $('#institution_id').append(newOption).trigger('change');

                                // Close the modal
                                $('#showModalInstitution').modal('hide');

                                // Optionally, clear the form inputs
                                $('#institution_form')[0].reset();
                            } else {
                                alert('An error occurred while adding the patient.');
                            }
                        },
                        error: function(response) {
                            alert('An error occurred. Please check the input data.');
                        }
                    });
                });
            });
        });
        // document.querySelector("#lead-image-input").addEventListener("change", function() {
        //     var preview = document.querySelector("#lead-img");
        //     var file = document.querySelector("#lead-image-input").files[0];
        //     console.log(file);
        //     var reader = new FileReader();
        //     reader.addEventListener("load", function() {
        //         preview.src = reader.result;
        //     }, false);
        //     if (file) {
        //         reader.readAsDataURL(file);
        //     }
        // });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function updateTime() {
                var now = new Date();
                var hours = now.getHours().toString().padStart(2, '0');
                var minutes = now.getMinutes().toString().padStart(2, '0');
                var seconds = now.getSeconds().toString().padStart(2, '0');
                var timeString = hours + ':' + minutes + ':' + seconds;
                document.getElementById('liveTime').textContent = timeString;
            }

            // Update the time immediately and then every second
            updateTime();
            setInterval(updateTime, 1000);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
@endsection
