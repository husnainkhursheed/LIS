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
            <form class="tablelist-form" id="leadtype_form" action="{{ url('/sample') }}" method="Post" autocomplete="off" >
            @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="test_number" class="form-label">Test Number</label>
                                <input type="text" id="test_number" name="test_number" value="{{$test_number}}" class="form-control"
                                hidden required />
                                <input type="text" id="" name="" value="{{$test_number}}" class="form-control"
                                disabled  />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="access_number" class="form-label">Access Number</label>
                            <input type="text" id="access_number" name="access_number"  class="form-control"
                                required />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                         <div class="form-group">
                            <label for="collected_date" class="form-label">Collected Date</label>
                            <input type="date" id="collected_date" name="collected_date" class="form-control"
                                required />
                        </div>
                  </div>
                    <div class="col-md-6">
                         <div class="form-group">
                            <label for="received_date" class="form-label">Received Date </label>
                            <input type="date" class="form-control" id="received_date" name="received_date"/>
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
                                            @foreach ($patients as $patient)
                                                <option value="{{ $patient->id }}">
                                                    {{ $patient->first_name }}</option>
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
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
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
                                @foreach ($institutions as $institution)
                                <option value="{{ $institution->id }}">
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
                                <option value="Patient">Patient</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="test_requested" class="form-label">Test Requested</label>
                            <select class="js-example-basic-multiple" name="test_requested[]" id="test_requested" multiple="multiple">
                                @foreach ($tests as $test)
                                    <option value="{{ $test->id }}">
                                        {{ $test->name }}</option>
                                @endforeach
                            </select>
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
                <form class="tablelist-form" id="leadtype_form" action="{{ url("/patient") }}" method="Post" autocomplete="off">
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
                            {{-- <div class="col-lg-12">
                                <div class="form-check form-check-dark mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                        id="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div> --}}
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
                <form class="tablelist-form" id="leadtype_form" action="{{ url("/doctor") }}" method="Post" autocomplete="off">
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
                            {{-- <div class="col-lg-12">
                                <div class="form-check form-check-dark mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                        id="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div> --}}
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
                <form class="tablelist-form" id="leadtype_form" action="{{ url("/institution") }}" method="Post" autocomplete="off">
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

                            {{-- <div class="col-lg-12">
                                <div class="form-check form-check-dark mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                        id="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div> --}}
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
        document.querySelector("#lead-image-input").addEventListener("change", function() {
            var preview = document.querySelector("#lead-img");
            var file = document.querySelector("#lead-image-input").files[0];
            console.log(file);
            var reader = new FileReader();
            reader.addEventListener("load", function() {
                preview.src = reader.result;
            }, false);
            if (file) {
                reader.readAsDataURL(file);
            }
        });
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
