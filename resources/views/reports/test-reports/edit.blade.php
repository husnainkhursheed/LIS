@extends('layouts.master')
@section('title')
    Report
@endsection
@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@php
    use Carbon\Carbon;
@endphp
@section('content')
    <style>
        #reportStickyNav {
            top: 86px;
            background-color: #22416b;
        }

        .border-nav {
            font-size: .9375rem;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            border: 2px solid #3AAFE2;
            background-color: #3AAFE2;
        }

        .border-nav:hover {
            background-color: #22416b;
            transition: 0.3s;
        }


    </style>
    {{-- //start  --}}
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg sticky-top navbar-light rounded" id="reportStickyNav">
            <div class="container-fluid">
                {{-- <a class="navbar-brand text-white" href="#">Navbar</a> --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center " id="navbarNav">

                    <ul class="navbar-nav gap-5">
                        <li class="nav-item border-nav px-5 rounded ">
                            <a class="nav-link active" aria-current="page"
                                href="{{ url('/reports/test-reports') }}">Find</a>
                        </li>
                        <li class="nav-item border-nav px-5 rounded ">
                            <button class="nav-link" id="SaveReport">Save</button>
                        </li>
                        <li class="nav-item border-nav px-5 rounded ">
                            <a class="nav-link" href="#">Delete</a>
                        </li>
                        <li class="nav-item border-nav px-5 rounded ">
                            <a class="nav-link" href="#" id="sign-link">Sign</a>
                        </li>
                        <li class="nav-item border-nav px-5 rounded ">
                            <a class="nav-link" href="#">Complete</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="card px-5 py-3 bg-white">
            <div class="card-header py-1">
                <h4 class="text-dark">Patient information</h4>
            </div>
            <div class="row pt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="access_number" class="form-label">Patient name</label>
                        <input type="text" id="access_number" name="access_number" class="form-control"
                            value="{{ $sample->patient->surname }} {{ $sample->patient->first_name }}" disabled />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Test Number</label>
                        <input type="text" id="test_number" name="test_number" class="form-control form-control-sm"
                            value="{{ $sample->test_number }}" disabled />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="access_number" class="form-label">Access Number</label>
                        <input type="text" id="access_number" name="access_number" class="form-control"
                            value="{{ $sample->access_number }}" disabled />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="collected_date" class="form-label">Collected date</label>
                        <input type="text" id="collected_date" name="collected_date" class="form-control"
                            value="{{ Carbon::parse($sample->collected_date)->format('d-m-Y') }}" disabled />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="received_date" class="form-label">Received date</label>
                        <input type="text" id="received_date" name="received_date" class="form-control"
                            value="{{ Carbon::parse($sample->received_date)->format('d-m-Y') }}" disabled />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="received_time" class="form-label">Time</label>
                        <input type="text" id="received_time" name="received_time" class="form-control"
                            value="{{ $sample->received_time }}" disabled />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Bill to (Doctor, Patient, Other)</label>
                        <input type="text" id="test_number" name="test_number" class="form-control"
                            value="{{ $sample->bill_to }}" disabled />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="doctorname" class="form-label">Doctor Name</label>
                        <input type="text" id="doctorname" name="doctorname" class="form-control"
                            value="{{ $sample->doctor->name }}" disabled />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="test_number" class="form-label">Tests Requested</label>
                        @php
                            // Assuming $sample->tests is a collection or array of test objects
                            $testNames = $sample->tests->pluck('name')->implode(', ');
                        @endphp
                        <input type="text" id="test_number" name="test_number" class="form-control"
                            value="{{ $testNames }}" disabled />
                    </div>
                </div>
            </div>
            <input type="hidden" id="reporttypeis" name="reporttypeis" value="{{ $reporttype }}">
            <input type="hidden" id="sampleid" name="sampleid" value="{{ $sample->id }}">
            {{-- <input type="hidden" id="testReport" name="testReport" value="{{$testReport}}"> --}}
            {{-- BioChemistry / Haematology Test Results --}}
            @if ($reporttype == 1)
                {{-- {{dd($testReports[0]->BiochemHaemoResults[0]->reference)}} --}}
                <div class="card-header py-1">
                    <h4 class="text-dark">BioChemistry / Haematology Test Results </h4>
                </div>
                <div class="row pt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reference" class="form-label">Reference</label>
                            {{-- <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly /> --}}
                            <textarea name="reference" id="reference" cols="30" rows="5" class="form-control">{{ $testReports[0]->BiochemHaemoResults[0]->reference ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="note" class="form-label">Note</label>
                            <textarea name="note" id="note" cols="30" rows="5" class="form-control">{{ $testReports[0]->BiochemHaemoResults[0]->note ?? '' }}</textarea>
                            {{-- <input type="text" id="test_number" name="test_number" class="form-control form-control-sm" value="ABC123" readonly /> --}}
                        </div>
                    </div>
                </div>
                <div class="card-header d-flex justify-content-between">
                    <h3 class="text-dark">List of tests</h3>
                    <button type="button" class="btn btn-primary add-btn align-item-end ms-auto" data-bs-toggle="modal"
                        id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1 "></i> Add
                        Test</button>
                </div>
                <table id="" class="table table-striped display table-responsive rounded">
                    <thead>
                        <tr>
                            <th class="rounded-start-3 ">Description</th>
                            <th>Test Results </th>
                            <th>Flag </th>
                            <th>Reference Range </th>
                            <th class="rounded-end-3 ">Test Notes </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($tests as $test)
                                <tr>
                                    <td>
                                        <input type="text" id="testid" name="testid" class="form-control" value="{{$test->id}}"  disabled/>
                                        <input type="text" id="description" name="description" class="form-control" value="{{$test->name}}"  disabled/>
                                    </td>
                                    <td>
                                    <input type="text" id="test_results" name="test_results" class="form-control" value="{{$testReport->BiochemHaemoResults[0]->test_results ?? ''}}"  />
                                    </td>
                                    <td>
                                    <input type="text" id="flag" name="flag" class="form-control" value="{{$testReport->BiochemHaemoResults[0]->flag  ?? ''}}"  />
                                    </td>
                                    <td>
                                    <input type="text" id="reference_range" name="reference_range" class="form-control" value="{{$test->reference_range}}"  disabled/>
                                    </td>
                                    <td>
                                    <textarea  id="test_notes" name="test_notes" class="form-control"   >{{$testReport->BiochemHaemoResults[0]->test_notes  ?? ''}}</textarea>
                                    </td>
                                </tr>
                            @endforeach --}}
                        @foreach ($tests as $test)
                            @php
                                $testReport = $testReports
                                    ->where('test_id', $test->id)
                                    ->where('sample_id', $sample->id)
                                    ->first();
                                // dd($testReport);
                                $biochemHaemoResults = $testReport ? $testReport->biochemHaemoResults->first() : [];
                                // dd($biochemHaemoResults);
                            @endphp
                            <tr>
                                <td>
                                    <input type="text" data-test-id="{{ $test->id }}"
                                        name="tests[{{ $test->id }}][id]" class="form-control"
                                        value="{{ $test->id }}" hidden disabled />
                                    <input type="text" data-test-id="{{ $test->id }}"
                                        name="tests[{{ $test->id }}][description]" class="form-control"
                                        value="{{ $test->name }}" disabled />
                                </td>
                                <td>
                                    <input type="text" data-test-id="{{ $test->id }}"
                                        name="tests[{{ $test->id }}][test_results]" class="form-control"
                                        value="{{ $biochemHaemoResults->test_results ?? '' }}" />
                                </td>
                                <td>
                                    <input type="text" data-test-id="{{ $test->id }}"
                                        name="tests[{{ $test->id }}][flag]" class="form-control"
                                        value="{{ $biochemHaemoResults->flag ?? '' }}" />
                                </td>
                                <td>
                                    <textarea data-test-id="{{ $test->id }}" name="tests[{{ $test->id }}][reference_range]"
                                        class="form-control" disabled>
                                            @if ($test->reference_range == 'basic_ref')
{{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}
@else
Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}
@endif
                                            </textarea>
                                </td>
                                <td>
                                    <textarea data-test-id="{{ $test->id }}" name="tests[{{ $test->id }}][test_notes]" class="form-control">{{ $biochemHaemoResults->test_notes ?? '' }}</textarea>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            @endif

            {{-- Cytology / Gynecology Test Results  --}}
            @if ($reporttype == 2)
                @foreach ($tests as $test)
                    @php
                        $testReport = $testReports
                            ->where('test_id', $test->id)
                            ->where('sample_id', $sample->id)
                            ->first();
                        // dd($testReport);
                        $cytologyGynecologyResults = $testReport ? $testReport->cytologyGynecologyResults->first() : [];
                        // dd($biochemHaemoResults);

                        $testIds = $tests->pluck('id')->implode(',');

                    @endphp
                @endforeach
                <input type="hidden" id="test_id" name="test_id[]" value="{{ $testIds }}" hidden>
                <div class="card-header py-1">
                    <h4 class="text-dark">Cytology / Gynecology Test Results </h4>
                </div>
                <div class="row pt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="history" class="form-label">History</label>
                            {{-- <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly /> --}}
                            <textarea name="history" id="history" cols="30" rows="" class="form-control" value="">{{ $cytologyGynecologyResults->history ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="last_period" class="form-label">Last Period </label>
                            <input type="date" id="last_period" name="last_period"
                                class="form-control form-control-sm"
                                value="{{ $cytologyGynecologyResults->last_period ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="custom" class="form-label">Contraceptive<a href=""
                                    class="customDropdownEdit" data-bs-toggle="modal" data-id="Contraceptive"
                                    data-bs-target="#showModalDropdown"> <span class="badge bg-info text-white"> Add
                                        New</span> </a></label>
                            <select class="js-example-basic-multiple" name="contraceptive" id="Contraceptive">
                                {{-- {{ dd($testReports->contraceptive)}} --}}
                                @foreach ($contraceptivedropdown as $test)
                                    <option value="{{ $test->value }}"
                                        {{ isset($cytologyGynecologyResults) && $cytologyGynecologyResults->contraceptive === $test->value ? 'selected' : '' }}>
                                        {{ $test->value }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="previous_pap" class="form-label">Previous Pap </label>
                            <input type="date" id="previous_pap" name="previous_pap" class="form-control"
                                value="{{ $cytologyGynecologyResults->previous_pap ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="result" class="form-label">Result </label>
                            <input type="text" id="result1" name="result" class="form-control"
                                value="{{ $cytologyGynecologyResults->result ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cervix_examination" class="form-label">Cervix Examination </label>
                            <textarea type="text" id="cervix_examination" name="cervix_examination" class="form-control" value="">{{ $cytologyGynecologyResults->cervix_examination ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-header py-1">
                    <h4 class="text-dark">Notes </h4>
                </div>
                <style>
                    .modal.right .modal-dialog {
                        position: fixed;
                        right: 0;
                        margin: auto;
                        width: 30% !important;
                        height: 100%;
                    }
                    .modal.right .modal-content {
                        height: 100%;
                        overflow-y: auto;
                    }
                    .modal.right .modal-body {
                        padding: 15px 15px 80px;
                    }
                </style>
                <div class="row pt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="specimen_adequacy" class="form-label">Specimen Adequacy
                                <span class="badge bg-info text-white add-note" data-target="#specimen_adequacy"> Add Note</span>
                            </label>
                            <textarea name="specimen_adequacy" id="specimen_adequacy" cols="30" rows="5" class="form-control">{{ $cytologyGynecologyResults->specimen_adequacy ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="diagnostic_interpretation" class="form-label">Diagnostic Interpretation
                                <span class="badge bg-info text-white add-note" data-target="#diagnostic_interpretation"> Add Note</span>
                            </label>
                            <textarea name="diagnostic_interpretation" id="diagnostic_interpretation" cols="30" rows="5" class="form-control">{{ $cytologyGynecologyResults->diagnostic_interpretation ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="recommend" class="form-label">Recommend
                                <span class="badge bg-info text-white add-note" data-target="#recommend"> Add Note</span>
                            </label>
                            <textarea name="recommend" id="recommend" cols="30" rows="5" class="form-control">{{ $cytologyGynecologyResults->recommend ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            @endif
            {{-- Urinalysis / Microbiology Test Results  --}}
            @if ($reporttype == 3)
                <div class="card-header py-1">
                    <h4 class="text-dark">Urinalysis / Microbiology Test Results </h4>
                </div>
                <div class="">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link waves-effect waves-light active" data-bs-toggle="tab"
                                href="#pill-justified-home-1" role="tab" aria-selected="false" tabindex="-1">
                                Chemical Analysis
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link waves-effect waves-light" data-bs-toggle="tab"
                                href="#pill-justified-profile-1" role="tab" aria-selected="true">
                                Microscopy
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link waves-effect waves-light" data-bs-toggle="tab"
                                href="#pill-justified-messages-1" role="tab" aria-selected="false" tabindex="-1">
                                Specimen
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content text-muted">
                        <div class="tab-pane active show" id="pill-justified-home-1" role="tabpanel">
                            <div class="d-flex">

                                <div class="flex-grow-1 ms-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="s_gravity" class="form-label">S. Gravity</label>
                                                <input type="number" id="s_gravity" name="s_gravity"
                                                    class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ph" class="form-label">PH</label>
                                                <input type="text" id="ph" name="ph" class="form-control"
                                                    value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="bilirubin" class="form-label">Bilirubin<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Bilirubin" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a></label>
                                                <select class="js-example-basic-multiple" name="bilirubin"
                                                    id="Bilirubin">
                                                    @foreach ($bilirubinropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ isset($cytologyGynecologyResults) && $cytologyGynecologyResults->bilirubin === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="blood" class="form-label">Blood <a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal" data-id="Blood"
                                                        data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a></label>
                                                <select class="js-example-basic-multiple" name="blood" id="Blood">
                                                    @foreach ($blooddropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ isset($cytologyGynecologyResults) && $cytologyGynecologyResults->bilirubin === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="leucocytes" class="form-label">Leucocytes<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Leucocytes" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a></label>
                                                <select class="js-example-basic-multiple" name="leucocytes"
                                                    id="Leucocytes">
                                                    @foreach ($leucocytesdropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ isset($cytologyGynecologyResults) && $cytologyGynecologyResults->bilirubin === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="glucose" class="form-label">Glucose<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Glucose" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a></label>
                                                <select class="js-example-basic-multiple" name="glucose" id="Glucose">
                                                    @foreach ($glucosedropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ isset($cytologyGynecologyResults) && $cytologyGynecologyResults->bilirubin === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nitrite" class="form-label">Nitrite<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Nitrite" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a></label>
                                                <select class="js-example-basic-multiple" name="nitrite" id="Nitrite">
                                                    @foreach ($nitritedropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ isset($cytologyGynecologyResults) && $cytologyGynecologyResults->bilirubin === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ketones" class="form-label">Ketones<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Ketones" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a></label>
                                                <select class="js-example-basic-multiple" name="ketones" id="Ketones">
                                                    @foreach ($ketonesdropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ isset($cytologyGynecologyResults) && $cytologyGynecologyResults->bilirubin === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="urobilinogen" class="form-label">Urobilinogen<a
                                                        href="" class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Urobilinogen" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a></label>
                                                <select class="js-example-basic-multiple" name="urobilinogen"
                                                    id="Urobilinogen">
                                                    @foreach ($urobilinogendropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ isset($cytologyGynecologyResults) && $cytologyGynecologyResults->bilirubin === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="proteins" class="form-label">Proteins<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Proteins" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a></label>
                                                <select class="js-example-basic-multiple" name="proteins" id="Proteins">
                                                    @foreach ($proteinsdropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ isset($cytologyGynecologyResults) && $cytologyGynecologyResults->bilirubin === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="colour" class="form-label">Colour</label>
                                                <input type="text" id="colour" name="colour" class="form-control"
                                                    value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="appearance" class="form-label">Appearance</label>
                                                <input type="text" id="appearance" name="appearance"
                                                    class="form-control" value="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="pill-justified-profile-1" role="tabpanel">
                            <div class="d-flex">

                                <div class="flex-grow-1 ms-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="epith" class="form-label">Epith</label>
                                                <input type="text" id="epith" name="epith" class="form-control"
                                                    value="" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="bacteria " class="form-label">Bacteria<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Bacteria" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a></label>
                                                <select class="js-example-basic-multiple" name="bacteria "
                                                    id="Bacteria">
                                                    @foreach ($bacteriadropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ isset($cytologyGynecologyResults) && $cytologyGynecologyResults->bilirubin === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="white_cells" class="form-label">White cells </label>
                                                <input type="text" id="white_cells" name="white_cells "
                                                    class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="yeast" class="form-label">Yeast </label>
                                                <input type="text" id="yeast " name="yeast" class="form-control"
                                                    value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="red_cells" class="form-label">Red Cells</label>
                                                <input type="text" id="red_cells" name="red_cells"
                                                    class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="trichomonas" class="form-label">Trichomonas </label>
                                                <input type="text" id="trichomonas " name="trichomonas"
                                                    class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="casts" class="form-label">Casts </label>
                                                <input type="text" id="casts" name="casts" class="form-control"
                                                    value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="crystals" class="form-label">Crystals </label>
                                                <input type="text" id="crystals" name="crystals"
                                                    class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="specimen" class="form-label">Specimen</label>
                                                <textarea id="specimen" name="specimen" class="form-control" value=""></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="pill-justified-messages-1" role="tabpanel">
                            <div class="d-flex">
                                {{-- <div class="flex-shrink-0">
                                    <i class="ri-checkbox-circle-fill text-success"></i>
                                </div> --}}
                                <div class="flex-grow-1 ms-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="procedure" class="form-label">Procedure </label>
                                                <select class="js-example-basic-multiple" name="procedure"
                                                    id="procedure">
                                                    <option value="wet_prep">Wet Prep</option>
                                                    <option value="gram_stain">Gram Stain</option>
                                                    <option value="culture">Culture</option>
                                                    <option value="stool">Stool</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="specimen_note" class="form-label">Note</label>
                                                <textarea type="text" id="specimen_note" name="specimen_note" rows="5" class="form-control"
                                                    value=""></textarea>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="test_number" class="form-label">Tests Requested</label>
                                                <input type="text" id="test_number" name="test_number" class="form-control" value=""/>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end card-body -->
            @endif
        </div>
    </div>
    <div class="modal right fade" id="notesModal" tabindex="-1" aria-labelledby="notesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-scrollable">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="notesModalLabel">All Notes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="notes-container">
                        <!-- Notes will be loaded here via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.add-note').on('click', function() {
                var targetTextarea = $($(this).data('target'));
                $('#notesModal').modal('show');

                $.ajax({
                    type: 'GET',
                    url: '{{ route("fetch-notes-cytology") }}',
                    success: function(notes) {
                        var notesContainer = $('#notes-container');
                        notesContainer.empty();

                        if (notes.length > 0) {
                            notes.forEach(function(note) {
                                notesContainer.append('<div class="note-item">' + note + '</div>');
                            });

                            // Add click event to each note-item
                            $('.note-item').on('click', function() {
                                var selectedNote = $(this).text();
                                var currentText = targetTextarea.val();
                                targetTextarea.val(currentText + (currentText ? '\n' : '') + selectedNote);
                                $('#notesModal').modal('hide'); // Optional: Hide modal after selecting a note
                            });
                        } else {
                            notesContainer.append('<p>No notes available.</p>');
                        }
                    },
                    error: function() {
                        var notesContainer = $('#notes-container');
                        notesContainer.empty();
                        notesContainer.append('<p>Error fetching notes.</p>');
                    }
                });
            });
        });
    </script>
    <!-- Dropdown Modal -->
    <div class="modal fade" id="showModalDropdown" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-end py-1">
                        <input type="text" name="" id="dropdownName" hidden>
                        <button type="button" id="addRowBtn" class="btn btn-secondary px-5">Add</button>
                    </div>
                    <form id="dropdownForm">
                        @csrf
                        <input type="hidden" name="dropdown_name" id="dropdown_name">
                        <input type="hidden" name="deleted_ids" id="deleted_ids" value="">
                        <table class="table table-responsive rounded">
                            <thead>
                                <tr>
                                    <th class="rounded-start-3">Name</th>
                                    <th>Values</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="valuesTable">
                                <!-- Rows will be added dynamically -->
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary float-end px-4">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Test</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="leadtype_form" action="{{ url('/test') }}" method="Post"
                    autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div>
                                    <label for="companyname-field" class="form-label">Name of charge item</label>
                                    <input type="text" id="name" name="name" class="form-control"
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

                                    <input type="radio" id="basic_ref" name="reference_range" required
                                        value="basic_ref" checked />
                                    <label for="basic_ref" class="form-label">Basic Reference range</label>
                                    <input type="radio" id="optional_ref" class="ms-4" name="reference_range"
                                        required value="optional_ref" />
                                    <label for="optional_ref" class="form-label">Reference range with optional sex</label>
                                </div>
                            </div>
                            <div class="row" id="basicValues">
                                {{-- <label for="" class="form-label">High value with optional sex</label> --}}
                                {{-- <div> --}}
                                <div class="col-lg-6">
                                    <div>
                                        <label for="basic_low_value_ref_range" class="form-label">Low Value</label>
                                        <input type="text" id="basic_low_value_ref_range" class="form-control"
                                            name="basic_low_value_ref_range" placeholder="Enter Low Value" required />
                                    </div>
                                </div>
                                {{-- <label for="male" class="form-label">High Value</label> --}}
                                <div class="col-lg-6">
                                    <div>
                                        <label for="basic_high_value_ref_range" class="form-label">High Value</label>
                                        <input type="text" id="basic_high_value_ref_range" class="form-control"
                                            name="basic_high_value_ref_range" placeholder="Enter High Value" required />
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
                                        <input type="text" id="male_low_value_ref_range" class="form-control"
                                            name="male_low_value_ref_range" placeholder="Enter Low Value" />
                                    </div>
                                </div>
                                {{-- <label for="male" class="form-label">High Value</label> --}}
                                <div class="col-lg-6">
                                    <div>
                                        <label for="male_high_value_ref_range" class="form-label">High Value</label>
                                        <input type="text" id="male_high_value_ref_range" class="form-control"
                                            name="male_high_value_ref_range" placeholder="Enter High Value" />
                                    </div>
                                </div>
                                <h5 for="" class="form-label text-black fw-bolder mt-2">Female </h5>
                                <div class="col-lg-6">
                                    <div>
                                        <label for="female_low_value_ref_range" class="form-label">Low Value</label>
                                        <input type="text" id="female_low_value_ref_range" class="form-control"
                                            name="female_low_value_ref_range" placeholder="Enter Low Value" />
                                    </div>
                                </div>
                                {{-- <label for="female" class="form-label">High Value</label> --}}
                                <div class="col-lg-6">
                                    <div>
                                        <label for="female_high_value_ref_range" class="form-label">High Value</label>
                                        <input type="text" id="female_high_value_ref_range" class="form-control"
                                            name="female_high_value_ref_range" placeholder="Enter High Value" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-check form-check-dark mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                        checked>
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
                            <button type="submit" class="btn btn-success" id="add-btn">Add Test</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- sign modal  --}}
    <!-- Modal -->
    <div class="modal fade" id="signModal" tabindex="-1" aria-labelledby="signModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="signModalLabel">Sign</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($tests as $test)
                        <div id="sign-form">
                            <p class="text-dark fw-semibold fs-6">Please indicate that you agree with all that is in this
                                report by signing:</p>
                            <form class="tablelist-form" id="leadtype_form"
                                action="{{ route('test-reports.signReport') }}" method="POST" autocomplete="off">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <input type="hidden" id="test_report_id" name="test_report_id"
                                    value="{{ $test->id ?? '' }}">
                                <div id="success-message" class="text-success" style="display: none;"></div>
                                <div id="error-message" class="text-danger" style="display: none;"></div>
                            </form>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="sign-button">Sign</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- end sign modal  --}}
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
                var url = '{{ url('/reports/test-reports') }}' + '/' + itemId;
                $('#leadtype_form').attr('action', url);
                // $.ajax({
                //         url: url, // Adjust the route as needed
                //         type: 'GET',
                //         success: function(response) {
                //             // Assuming the response has a 'leadType' key
                //             var doctor = response.doctor;
                //             console.log("my practices ",doctor);

                //             // Now you can use the leadType data to populate your modal fields
                //             $('#id-field').val(doctor.id);
                //             $('#name').val(doctor.name);
                //             // $('#phone').val(doctor.phone);
                //             $('#contact_number').val(doctor.contact_number);
                //             $('#street_name').val(doctor.street_name);
                //             $('#address_line_2').val(doctor.address_line_2);
                //             $('#area').val(doctor.area);
                //             $('#email').val(doctor.email);

                //             // var surgeries = SetupPractice.surgeries.map(function(surgery) {
                //             //         return surgery.id;
                //             //     });

                //             // $('#surgeries').val(surgeries).trigger('change');


                //             // Set the checkbox town for is_active
                //             // $('#is_active').prop('checked', SetupPractice.is_active);

                //             // Update modal title
                //             $('#exampleModalLabel').html("Edit Doctor");

                //             // Display the modal footer
                //             $('#showModal .modal-footer').css('display', 'block');

                //             // Change the button text
                //             $('#add-btn').html("Update");
                //             var form = $('#leadtype_form');

                //             // Update the form action (assuming the form has an ID of 'your-form-id')




                //             // $('#showModal').modal('show');

                //         },
                //         error: function(xhr, status, error) {
                //             console.error(xhr, status, error);
                //             // Handle errors if needed
                //         }
                //     });

            });



            function resetModal() {
                // Reset modal titleq
                $('#exampleModalLabel').html("Add Doctor");

                // Display the modal footer
                $('#showModal .modal-footer').css('display', 'block');

                // Change the button text
                $('#add-btn').html("Add");
                $('#leadtype_form').attr('action', '{{ url('/doctor') }}');
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
            $('#showModal').on('hidden.bs.modal', function() {
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

        $(document).ready(function() {
            // Set CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#SaveReport').on('click', function(event) {
                console.log('clicked')
                event.preventDefault(); // Prevent the default link behavior
                var reporttypeis = $('#reporttypeis').val();
                var data = {};

                var testsData = {};

                // Collect data from each row


                // Gather data from the form based on report type
                if (reporttypeis == 1) {
                    $('input[data-test-id], textarea[data-test-id]').each(function() {
                        var testId = $(this).data('test-id');
                        var fieldName = $(this).attr('name').split('[')[2].slice(0, -
                            1); // Extract the field name

                        if (!testsData[testId]) {
                            testsData[testId] = {};
                        }

                        testsData[testId][fieldName] = $(this).val();
                    });
                    data = {
                        sampleid: $('#sampleid').val(),
                        // testReport: $('#testReport').val(),
                        reporttype: reporttypeis,
                        reference: $('#reference').val(),
                        note: $('#note').val(),
                        testsData: testsData,
                    };
                } else if (reporttypeis == 2) {
                    data = {
                        sampleid: $('#sampleid').val(),
                        testIds: $('#test_id').val(),
                        reporttype: reporttypeis,
                        history: $('#history').val(),
                        last_period: $('#last_period').val(),
                        contraceptive: $('#Contraceptive').val(),
                        result: $('#result1').val(),
                        previous_pap: $('#previous_pap').val(),
                        cervix_examination: $('#cervix_examination').val(),
                        specimen_adequacy: $('#specimen_adequacy').val(),
                        diagnostic_interpretation: $('#diagnostic_interpretation').val(),
                        recommend: $('#recommend').val()
                    };
                }

                $.ajax({
                    url: '/reports/save-reports',
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        // Handle the success response
                        console.log('Success:', response);
                        if (response.success) {
                            Toastify({
                                text: response.message,
                                gravity: 'top',
                                position: 'center',
                                duration: 5000,
                                close: true,
                                backgroundColor: '#40bb82',
                            }).showToast();
                        } else {
                            var errors = response.message;
                            var errorMessage = errors.join('\n');
                            Toastify({
                                text: errors,
                                duration: 5000,
                                gravity: 'top',
                                position: 'left',
                                backgroundColor: '#ff4444',
                            }).showToast();
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr, status, error);

                    }
                });
            });
        });

        // $('#customDropdownEdit').on('click', function() {
        //     // Get the ID from the data attribute

        //     var itemId = $(this).data('id');
        //     var url = '{{ url('/custom-dropdown/getvalues') }}' + '/' + itemId + '/edit';

        //     $.ajax({
        //             url: url, // Adjust the route as needed
        //             type: 'GET',
        //             success: function(response) {



        //             },
        //             error: function(xhr, status, error) {
        //                 console.error(xhr, status, error);
        //                 // Handle errors if needed
        //             }
        //         });

        // });
        // Edit button click event
        $(document).on('click', '.customDropdownEdit', function() {
            var dropdownName = $(this).data('id');
            $('#dropdown_name').val(dropdownName); // Set the dropdown name
            var url = '{{ url('/custom-dropdown/getvalues') }}' + '/' + dropdownName + '/edit';

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#valuesTable').empty(); // Clear existing rows
                    response.customdropdownvalues.forEach(function(item) {
                        var newRow = `
                            <tr>
                                <td>
                                    <input type="hidden" name="id[]" value="${item.id}">
                                    <input type="text" name="dropdown_name[]" class="form-control" value="${item.dropdown_name}" readonly>
                                </td>
                                <td><input type="text" name="value[]" class="form-control" value="${item.value}"></td>
                                <td>
                                    <ul class="list-inline hstack gap-2 mb-0">
                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                            <a class="remove-item-btn" href="javascript:void(0);" onclick="removeRow(this, ${item.id});">
                                                <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>`;
                        $('#valuesTable').append(newRow);
                    });
                    $('#dropdownName').val(dropdownName);
                    $('#exampleModalLabel').html(dropdownName);
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                    // Handle errors if needed
                }
            });
        });

        $('#addRowBtn').click(function() {
            var dropdownName = $('#dropdownName').val();
            var newRow = `
                <tr>
                    <td>
                        <input type="hidden" name="id[]" value="">
                        <input type="text" name="dropdown_name[]" class="form-control" value="${dropdownName}" readonly>
                    </td>
                    <td><input type="text" name="value[]" class="form-control" value=""></td>
                    <td>
                        <ul class="list-inline hstack gap-2 mb-0">
                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                <a class="remove-item-btn" href="javascript:void(0);" onclick="removeRow(this, null);">
                                    <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>`;
            $('#valuesTable').append(newRow);
        });

        // Remove row and track deleted IDs
        window.removeRow = function(el, id) {
            if (id) {
                var deletedIds = $('#deleted_ids').val();
                if (deletedIds) {
                    deletedIds += ',' + id;
                } else {
                    deletedIds = id;
                }
                $('#deleted_ids').val(deletedIds);
            }
            $(el).closest('tr').remove();
        };

        $('#dropdownForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('custom-dropdown.store') }}',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function(response) {
                    alert('Values added/updated/deleted successfully!');

                    // Update dropdown
                    var dropdown_name = $('#dropdown_name').val();
                    console.log(dropdown_name);
                    updateDropdown(dropdown_name);

                    // Optionally, you can clear the form or close the modal
                    $('#showModalDropdown').modal('hide');
                },
                error: function(xhr) {
                    alert('An error occurred. Please try again.');
                    console.log(xhr.responseText); // Log the error response
                }
            });
        });

        function updateDropdown(dropdownName) {
            var url = '{{ url('custom-dropdown/names') }}' + '/' + dropdownName;
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    var dropdown = $(`#${dropdownName}`);
                    dropdown.empty(); // Clear the existing options
                    data.forEach(function(item) {
                        dropdown.append($('<option>', {
                            value: item.value,
                            text: item.value
                        }));
                    });

                    // Re-initialize the select2 plugin if used
                    $('.js-example-basic-multiple').select2();
                },
                error: function(xhr) {
                    console.log('An error occurred while updating the dropdown.');
                }
            });
        }

        // updateDropdown('Contraceptive');

        // sign report working
        $('#sign-link').click(function(event) {
            event.preventDefault();
            $('#signModal').modal('show');
        });

        $('#sign-button').click(function(event) {
            event.preventDefault();

            var formData = {
                email: $('#email').val(),
                password: $('#password').val(),
                test_report_id: $('#test_report_id').val(),
                _token: $('input[name="_token"]').val()
            };

            $.ajax({
                type: 'POST',
                url: '{{ route('test-reports.signReport') }}',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#success-message').text(response.success).show();
                        $('#error-message').hide();
                        // Hide the modal after a short delay to let the user read the success message
                        setTimeout(function() {
                            $('#signModal').modal('hide');
                        }, 2000);
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON;
                    if (errors.error) {
                        $('#error-message').text(errors.error).show();
                        $('#success-message').hide();
                    }
                }
            });

            $('#signModal').on('hidden.bs.modal', function() {
                // Reset form fields
                $('#email').val('');
                $('#password').val('');
                $('#success-message').hide();
                $('#error-message').hide();
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
@endsection
