@extends('layouts.master')
@section('title')
    Test Report
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
                font-size: .85rem;
                font-weight: 600;
                font-family: 'Montserrat', sans-serif;
                border: 2px solid #3AAFE2;
                background-color: #3AAFE2;
            }

            .border-nav:hover {
                background-color: #22416b;
                transition: 0.3s;
            }

            .select2-container--default .select2-results__option[aria-selected=true] {
                background-color: #eff2f7;
            }
            .navbar-nav .nav-item {
                    flex: 1;
                    text-align: center;
                }
                .navbar-nav .nav-link {
                    display: block;
                    width: 100%;
                }
                .navbar-nav .nav-item .sub-link {
                    padding: 0.4rem 0rem;
                }
                @media (max-width: 992px) {
                    .navbar-nav .nav-item {
                        flex: none;
                        width: 100%;
                    }

                }
                @media (max-width: 1350px) {
                    .navbar-nav .nav-link {
                        font-size: 0.59rem; /* Adjust font size for smaller screens */
                    }
                }
        </style>

    {{-- //start  --}}
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg sticky-top navbar-light rounded" id="reportStickyNav">
            <div class="container-fluid">
                {{-- <a class="navbar-brand text-white" href="#">Navbar</a> --}}
                <button class="navbar-toggler bg-white mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav d-flex gap-1 justify-content-center w-100">
                        <li class="nav-item border-nav rounded flex-grow-1">
                            <a class="nav-link sub-link active" aria-current="page" href="{{ url('/reports/test-reports') }}">Find</a>
                        </li>
                        @if (!$sample->is_completed)
                            <li class="nav-item border-nav rounded flex-grow-1" id="SaveReport">
                                <button class="nav-link sub-link">Save</button>
                            </li>
                        @endif
                        <li class="nav-item border-nav rounded flex-grow-1">
                            <a class="nav-link sub-link sub-link" href="#">Delete</a>
                        </li>
                        @if ($sample->signed_by)
                            <li class="nav-item border-nav rounded flex-grow-1" id="allreadyassign">
                                <a class="nav-link sub-link " href="#">Signed</a>
                            </li>
                        @else
                            <li class="nav-item border-nav rounded flex-grow-1" id="assign">
                                <a class="nav-link sub-link" href="#" id="sign-link">Sign</a>
                            </li>
                        @endif
                        @if (!$sample->is_completed)
                            <li class="nav-item border-nav rounded flex-grow-1" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-placement="top" title="Complete">
                                <a class="nav-link sub-link complete-report-btn" data-id="{{ $sample->id }}" data-bs-toggle="modal"
                                    href="#completeRecordModal">Complete</a>
                            </li>
                        @else
                            <li class="nav-item border-nav rounded flex-grow-1" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-placement="top" title="Complete">
                                <a class="nav-link sub-link uncomplete-report-btn" data-id="{{ $sample->id }}" data-bs-toggle="modal"
                                    href="#UncompleteRecordModal">Un Complete</a>
                            </li>
                        @endif
                        <li class="nav-item border-nav rounded flex-grow-1">
                            <a class="nav-link sub-link" aria-current="page" id="pdfbtn"
                                href="{{ url('generate-pdf/'.$sample->id.'/'.$reporttype) }}" target="_blank">Generate Pdf Report</a>
                        </li>
                        <li class="nav-item border-nav rounded flex-grow-1">
                            <a class="nav-link sub-link" aria-current="page" id=""
                                href="{{ url('reports/audit-trails/'.$sample->id.'/'.$reporttype) }}">Audit Trail</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


            <div class="row pt-3" >
                @if ($sample->signed_by)
                    <div class="col-md-6" id="report_signed_input">
                        <div class="form-group">
                            <label for="access_number" class="form-label">Report Signed By</label>
                            <input type="text" class="form-control" id="report_signed"
                                value="{{ 'Report signed by ' . $sample->signedBy->first_name . ' on ' . $sample->signed_at }}"
                                disabled />
                        </div>
                    </div>
                @endif
                @if ($sample->is_completed)
                    <div class="col-md-6" id="report_signed_input">
                        <div class="form-group">
                            <label for="access_number" class="form-label">Report Completed On</label>
                            <input type="text" class="form-control" id="report_signed"
                                value="{{ 'Report Completed on ' . $sample->completed_at }}"
                                disabled />
                        </div>
                    </div>
                @endif
            </div>


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
                            $testNames = $tests->pluck('name')->implode(', ');
                        @endphp
                        <input type="text" id="test_number" name="test_number" class="form-control"
                            value="{{ $testNames }}" disabled />
                    </div>
                </div>
            </div>

            <input type="hidden" id="gender" name="gender" value="{{ $sample->patient->sex }}">
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
                    @if (!$sample->is_completed)
                    <button type="button" class="btn btn-primary add-btn align-item-end ms-auto" data-bs-toggle="modal"
                        id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1 "></i> Add
                        Test</button>
                    @endif
                </div>
                <table id="tests-table" class="table table-striped display table-responsive rounded">
                    <thead>
                        <tr>
                            <th class="rounded-start-3 ">Description</th>
                            <th>Test Results </th>
                            <th>Flag </th>
                            <th>Reference Range </th>
                            <th>Test Notes </th>
                            <th></th>
                            <th class="rounded-end-3">Calc</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($tests as $index => $test)
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
                                    <input type="text"  data-test-id="{{ $test->id }}"
                                        name="tests[{{ $test->id }}][test_results]" class="form-control test-result"
                                        value="{{ $biochemHaemoResults->test_results ?? '' }}"
                                        data-basic-low="{{ $test->basic_low_value_ref_range }}"
                                        data-basic-high="{{ $test->basic_high_value_ref_range }}"
                                        data-male-low="{{ $test->male_low_value_ref_range }}"
                                        data-male-high="{{ $test->male_high_value_ref_range }}"
                                        data-female-low="{{ $test->female_low_value_ref_range }}"
                                        data-female-high="{{ $test->female_high_value_ref_range }}"
                                        data-nomanual-set="{{ $test->nomanualvalues_ref_range }}" />
                                </td>
                                <td>
                                    <input type="text"  data-test-id="{{ $test->id }}"
                                        name="tests[{{ $test->id }}][flag]" class="form-control flag-input"
                                        value="{{ $biochemHaemoResults->flag ?? '' }}" style="width: 80px;"/>
                                    @php
                                        $background = '';
                                        if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                            $background = 'bg-success';
                                        } elseif (
                                            !empty($biochemHaemoResults) &&
                                            $biochemHaemoResults->flag == 'High'
                                        ) {
                                            $background = 'bg-danger';
                                        } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                            $background = 'bg-warning';
                                        }
                                    @endphp
                                    <span class="badge badge-pill flag-badge {{ $background }} d-none"
                                        data-key="t-hot">{{ $biochemHaemoResults->flag ?? '' }}</span>

                                </td>

                                <td>
                                    <p class="reference-range">
                                        @if ($test->reference_range == 'basic_ref')
                                            {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}
                                        @elseif ($test->reference_range == 'optional_ref')
                                            Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                            <br>
                                            Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}
                                        @elseif ($test->reference_range == 'no_manual_tag')
                                            {{ $test->nomanualvalues_ref_range }}
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    <textarea data-test-id="{{ $test->id }}" name="tests[{{ $test->id }}][test_notes]" class="form-control">{{ $biochemHaemoResults->test_notes ?? '' }}</textarea>
                                </td>
                                <td>
                                    @if ($index > 0 && !$sample->is_completed)
                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                            data-bs-placement="top" title="Delete">
                                            <a class="remove-item-btn" data-id="{{ $test->id }}"
                                                data-sampleid="{{ $sample->id }}" data-bs-toggle="modal"
                                                href="#deleteRecordModal">
                                                <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                            </a>
                                        </li>
                                    @endif
                                </td>
                                <td>
                                    @if ($test->calculation_explanation)
                                        <a href="" class="getcalc" data-bs-toggle="modal"
                                        data-id="{{ $test->id }}" data-bs-target="#showModalcalc"> <span
                                                        class="badge bg-info text-white">show</span> </a>
                                    @endif
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
                            <label for="custom" class="form-label">Contraceptive
                                @if (!$sample->is_completed)
                                <a href=""
                                    class="customDropdownEdit" data-bs-toggle="modal" data-id="Contraceptive"
                                    data-bs-target="#showModalDropdown"> <span class="badge bg-info text-white"> Add
                                        New</span> </a>
                                @endif
                                    </label>
                            <select class="js-example-basic-multiple" name="contraceptive" id="Contraceptive">
                                {{-- {{ dd($testReports->contraceptive)}} --}}
                                @foreach ($contraceptivedropdown as $test)
                                    <option value="{{ $test->value }}"
                                        {{ !empty($cytologyGynecologyResults) && $cytologyGynecologyResults->contraceptive === $test->value ? 'selected' : '' }}>
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

                    .note-item {
                        cursor: pointer;
                        background-color: #f2fafc;
                        padding: 10px;
                        margin-bottom: 10px;
                        border-radius: 5px;
                        transition: background-color 0.3s;
                        font-weight: 700;
                    }

                    .note-item:hover {
                        background-color: #e9ecef;
                        /* Hover background color */
                    }
                </style>
                <div class="row pt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="specimen_adequacy" class="form-label">Specimen Adequacy
                                @if (!$sample->is_completed)
                                <span class="badge bg-info text-white add-note" data-target="#specimen_adequacy"> Add
                                    Note</span>
                                @endif
                            </label>
                            {{-- <input type="text" id="access_number" name="access_number" class="form-control" value="ABC123" readonly /> --}}
                            <textarea name="specimen_adequacy" id="specimen_adequacy" cols="" rows="5" class="form-control"
                                value="">{{ $cytologyGynecologyResults->specimen_adequacy ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="diagnostic_interpretation" class="form-label">Diagnostic Interpretation
                                @if (!$sample->is_completed)
                                <span class="badge bg-info text-white add-note" data-target="#diagnostic_interpretation">
                                    Add Note</span>
                                @endif
                            </label>
                            <textarea name="diagnostic_interpretation" id="diagnostic_interpretation" cols="30" rows="5"
                                class="form-control">{{ $cytologyGynecologyResults->diagnostic_interpretation ?? '' }}</textarea>
                            {{-- <input type="text" id="test_number" name="test_number" class="form-control form-control-sm" value="ABC123" readonly /> --}}

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="recommend" class="form-label">Recommend
                                @if (!$sample->is_completed)
                                <span class="badge bg-info text-white add-note" data-target="#recommend"> Add Note</span>
                                @endif
                            </label>
                            <textarea name="recommend" id="recommend" cols="30" rows="5" class="form-control">{{ $cytologyGynecologyResults->recommend ?? '' }}</textarea>
                            {{-- <input type="text" id="test_number" name="test_number" class="form-control form-control-sm" value="ABC123" readonly /> --}}
                        </div>
                    </div>
                </div>
            @endif
            {{-- Urinalysis / Microbiology Test Results  --}}
            @if ($reporttype == 3)
                @foreach ($tests as $test)
                    @php
                        $testReport = $testReports
                            ->where('test_id', $test->id)
                            ->where('sample_id', $sample->id)
                            ->first();
                        // dd($testReport);
                        $urinalysisMicrobiologyResults = $testReport
                            ? $testReport->urinalysisMicrobiologyResults->first()
                            : [];
                            // dd($urinalysisMicrobiologyResults->procedureResults);
                        $procedureResults = $urinalysisMicrobiologyResults ? $urinalysisMicrobiologyResults->procedureResults : [];


                        $testIds = $tests->pluck('id')->implode(',');

                    @endphp
                @endforeach
                <input type="hidden" id="urinalysis_test_id" name="urinalysis_test_id[]" value="{{ $testIds }}"
                    hidden>
                <div class="card-header py-1">
                    <h4 class="text-dark">Urinalysis / Microbiology Test Results </h4>
                </div>
                <div class="">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified mb-3" role="tablist">

                        <li class="nav-item1" role="presentation">
                            <a class="nav-link waves-effect waves-light active" data-bs-toggle="tab" href="#pill-justified-home-1" role="tab" aria-selected="false" tabindex="-1">

                                Chemical Analysis
                            </a>
                        </li>
                        <li class="nav-item1" role="presentation">

                            <a class="nav-link waves-effect waves-light" data-bs-toggle="tab" href="#pill-justified-profile-1" role="tab" aria-selected="true">

                                Microscopy
                            </a>
                        </li>
                        <li class="nav-item1" role="presentation">

                            <a class="nav-link waves-effect waves-light" data-bs-toggle="tab" href="#pill-justified-messages-1" role="tab" aria-selected="false" tabindex="-1">

                                Specimen
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content text-muted">
                        <div class="tab-pane active show" id="pill-justified-home-1" role="tabpanel">
                            <table id="tests-table" class="table table-striped display table-responsive rounded">
                                <thead>
                                    <tr>
                                        <th class="rounded-start-3 ">Test</th>
                                        <th>Test Results </th>
                                        {{-- <th>Flag </th> --}}
                                        <th>Normal Range </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                        {{------------------- s gravity ------------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="s_gravity" class="form-control"
                                                    value="S. Gravity" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="s_gravity_result" class="form-control test-result" id="s_gravity_result"
                                                    value="{{ $urinalysisMicrobiologyResults->s_gravity ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td> --}}
                                                {{-- <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span> --}}

                                            {{-- </td> --}}

                                            <td>
                                                <p class="uri-reference-range-s_gravity">
                                                    @if(isset($referenceRanges['s_gravity']))
                                                        {{-- {{ $referenceRanges['s_gravity']->low . '-' . $referenceRanges['s_gravity']->high}} --}}
                                                        @if ($referenceRanges['s_gravity']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['s_gravity']->low . '-' . $referenceRanges['s_gravity']->high }}
                                                        @elseif ($referenceRanges['s_gravity']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['s_gravity']->male_low . '-' . $referenceRanges['s_gravity']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['s_gravity']->female_low . '-' .$referenceRanges['s_gravity']->female_high }}
                                                        @elseif ($referenceRanges['s_gravity']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['s_gravity']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="s_gravity" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{--------------------- PH --------------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="bilirubin" class="form-control"
                                                    value="Bilirubin" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="bilirubin_result" class="form-control test-result" id="bilirubin_result"
                                                    value="{{ $urinalysisMicrobiologyResults->bilirubin ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @bilirubinp
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endbilirubinp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-bilirubin">
                                                    @if(isset($referenceRanges['bilirubin']))
                                                        {{-- {{ $referenceRanges['bilirubin']->low . '-' . $referenceRanges['bilirubin']->high}} --}}
                                                        @if ($referenceRanges['bilirubin']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['bilirubin']->low . '-' . $referenceRanges['bilirubin']->high }}
                                                        @elseif ($referenceRanges['bilirubin']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['bilirubin']->male_low . '-' . $referenceRanges['bilirubin']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['bilirubin']->female_low . '-' .$referenceRanges['bilirubin']->female_high }}
                                                        @elseif ($referenceRanges['bilirubin']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['bilirubin']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="bilirubin" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{--------------------- PH --------------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="ph" class="form-control"
                                                    value="PH" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="ph_result" class="form-control test-result" id="ph_result"
                                                    value="{{ $urinalysisMicrobiologyResults->ph ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-ph">
                                                    @if(isset($referenceRanges['ph']))
                                                        {{-- {{ $referenceRanges['ph']->low . '-' . $referenceRanges['ph']->high}} --}}
                                                        @if ($referenceRanges['ph']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['ph']->low . '-' . $referenceRanges['ph']->high }}
                                                        @elseif ($referenceRanges['ph']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['ph']->male_low . '-' . $referenceRanges['ph']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['ph']->female_low . '-' .$referenceRanges['ph']->female_high }}
                                                        @elseif ($referenceRanges['ph']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['ph']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="ph" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{------------------- blood ---------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="blood" class="form-control"
                                                    value="Blood" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="blood_result" class="form-control test-result" id="blood_result"
                                                    value="{{ $urinalysisMicrobiologyResults->blood ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-blood">
                                                    @if(isset($referenceRanges['blood']))
                                                        {{-- {{ $referenceRanges['blood']->low . '-' . $referenceRanges['blood']->high}} --}}
                                                        @if ($referenceRanges['blood']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['blood']->low . '-' . $referenceRanges['blood']->high }}
                                                        @elseif ($referenceRanges['blood']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['blood']->male_low . '-' . $referenceRanges['blood']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['blood']->female_low . '-' .$referenceRanges['blood']->female_high }}
                                                        @elseif ($referenceRanges['blood']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['blood']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="blood" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                         {{------------------- Leucocytes ---------------}}
                                         <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="leucocytes" class="form-control"
                                                    value="Leucocytes" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="leucocytes_result" class="form-control test-result" id="leucocytes_result"
                                                    value="{{ $urinalysisMicrobiologyResults->leucocytes ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-leucocytes">
                                                    @if(isset($referenceRanges['leucocytes']))
                                                        {{-- {{ $referenceRanges['leucocytes']->low . '-' . $referenceRanges['leucocytes']->high}} --}}
                                                        @if ($referenceRanges['leucocytes']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['leucocytes']->low . '-' . $referenceRanges['leucocytes']->high }}
                                                        @elseif ($referenceRanges['leucocytes']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['leucocytes']->male_low . '-' . $referenceRanges['leucocytes']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['leucocytes']->female_low . '-' .$referenceRanges['leucocytes']->female_high }}
                                                        @elseif ($referenceRanges['leucocytes']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['leucocytes']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="leucocytes" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{------------------- Glucose ---------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="glucose" class="form-control"
                                                    value="Glucose" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="glucose_result" class="form-control test-result" id="glucose_result"
                                                    value="{{ $urinalysisMicrobiologyResults->glucose ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-glucose">
                                                    @if(isset($referenceRanges['glucose']))
                                                        {{-- {{ $referenceRanges['glucose']->low . '-' . $referenceRanges['glucose']->high}} --}}
                                                        @if ($referenceRanges['glucose']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['glucose']->low . '-' . $referenceRanges['glucose']->high }}
                                                        @elseif ($referenceRanges['glucose']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['glucose']->male_low . '-' . $referenceRanges['glucose']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['glucose']->female_low . '-' .$referenceRanges['glucose']->female_high }}
                                                        @elseif ($referenceRanges['glucose']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['glucose']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="glucose" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{------------------- nitrite ---------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="nitrite" class="form-control"
                                                    value="Nitrite" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="nitrite_result" class="form-control test-result" id="nitrite_result"
                                                    value="{{ $urinalysisMicrobiologyResults->nitrite ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-nitrite">
                                                    @if(isset($referenceRanges['nitrite']))
                                                        {{-- {{ $referenceRanges['nitrite']->low . '-' . $referenceRanges['nitrite']->high}} --}}
                                                        @if ($referenceRanges['nitrite']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['nitrite']->low . '-' . $referenceRanges['nitrite']->high }}
                                                        @elseif ($referenceRanges['nitrite']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['nitrite']->male_low . '-' . $referenceRanges['nitrite']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['nitrite']->female_low . '-' .$referenceRanges['nitrite']->female_high }}
                                                        @elseif ($referenceRanges['nitrite']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['nitrite']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="nitrite" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{------------------- ketones ---------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="ketones" class="form-control"
                                                    value="Ketones" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="ketones_result" class="form-control test-result" id="ketones_result"
                                                    value="{{ $urinalysisMicrobiologyResults->ketones ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-ketones">
                                                    @if(isset($referenceRanges['ketones']))
                                                        {{-- {{ $referenceRanges['ketones']->low . '-' . $referenceRanges['ketones']->high}} --}}
                                                        @if ($referenceRanges['ketones']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['ketones']->low . '-' . $referenceRanges['ketones']->high }}
                                                        @elseif ($referenceRanges['ketones']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['ketones']->male_low . '-' . $referenceRanges['ketones']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['ketones']->female_low . '-' .$referenceRanges['ketones']->female_high }}
                                                        @elseif ($referenceRanges['ketones']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['ketones']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="ketones" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{------------------- urobilinogen ---------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="urobilinogen" class="form-control"
                                                    value="Urobilinogen" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="urobilinogen_result" class="form-control test-result" id="urobilinogen_result"
                                                    value="{{ $urinalysisMicrobiologyResults->urobilinogen ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-urobilinogen">
                                                    @if(isset($referenceRanges['urobilinogen']))
                                                        {{-- {{ $referenceRanges['urobilinogen']->low . '-' . $referenceRanges['urobilinogen']->high}} --}}
                                                        @if ($referenceRanges['urobilinogen']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['urobilinogen']->low . '-' . $referenceRanges['urobilinogen']->high }}
                                                        @elseif ($referenceRanges['urobilinogen']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['urobilinogen']->male_low . '-' . $referenceRanges['urobilinogen']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['urobilinogen']->female_low . '-' .$referenceRanges['urobilinogen']->female_high }}
                                                        @elseif ($referenceRanges['urobilinogen']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['urobilinogen']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="urobilinogen" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{------------------- Proteins ---------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="proteins" class="form-control"
                                                    value="Proteins" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="proteins_result" class="form-control test-result" id="proteins_result"
                                                    value="{{ $urinalysisMicrobiologyResults->proteins ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-proteins">
                                                    @if(isset($referenceRanges['proteins']))
                                                        {{-- {{ $referenceRanges['proteins']->low . '-' . $referenceRanges['proteins']->high}} --}}
                                                        @if ($referenceRanges['proteins']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['proteins']->low . '-' . $referenceRanges['proteins']->high }}
                                                        @elseif ($referenceRanges['proteins']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['proteins']->male_low . '-' . $referenceRanges['proteins']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['proteins']->female_low . '-' .$referenceRanges['proteins']->female_high }}
                                                        @elseif ($referenceRanges['proteins']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['proteins']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="proteins" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>


                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="colour" class="form-label">Colour</label>
                                        <input type="text" id="colour" name="colour" class="form-control"
                                            value="{{ $urinalysisMicrobiologyResults->colour ?? '' }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="appearance" class="form-label">Appearance</label>
                                        <input type="text" id="appearance" name="appearance"
                                            class="form-control"
                                            value="{{ $urinalysisMicrobiologyResults->appearance ?? '' }}" />
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="d-flex">

                                <div class="flex-grow-1 ms-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="s_gravity" class="form-label">S. Gravity</label>
                                                <input type="number" id="s_gravity" name="s_gravity"
                                                    class="form-control"
                                                    value="{{ $urinalysisMicrobiologyResults->s_gravity ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ph" class="form-label">PH</label>
                                                <input type="text" id="ph" name="ph" class="form-control"
                                                    value="{{ $urinalysisMicrobiologyResults->ph ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="bilirubin" class="form-label">Bilirubin
                                                    @if (!$sample->is_completed)
                                                    <a href="" class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Bilirubin" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a>
                                                    @endif
                                                        </label>
                                                <select class="js-example-basic-multiple" name="bilirubin"
                                                    id="Bilirubin">
                                                    @foreach ($bilirubinropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->bilirubin === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="blood" class="form-label">Blood
                                                    @if (!$sample->is_completed) <a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal" data-id="Blood"
                                                        data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a>@endif</label>
                                                <select class="js-example-basic-multiple" name="blood" id="Blood">
                                                    @foreach ($blooddropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->blood === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="leucocytes" class="form-label">Leucocytes @if (!$sample->is_completed)<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Leucocytes" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a>@endif</label>
                                                <select class="js-example-basic-multiple" name="leucocytes"
                                                    id="Leucocytes">
                                                    @foreach ($leucocytesdropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->leucocytes === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="glucose" class="form-label">Glucose @if (!$sample->is_completed)<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Glucose" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a> @endif</label>
                                                <select class="js-example-basic-multiple" name="glucose" id="Glucose">
                                                    @foreach ($glucosedropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->glucose === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nitrite" class="form-label">Nitrite @if (!$sample->is_completed)<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Nitrite" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a> @endif</label>
                                                <select class="js-example-basic-multiple" name="nitrite" id="Nitrite">
                                                    @foreach ($nitritedropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->nitrite === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ketones" class="form-label">Ketones @if (!$sample->is_completed)<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Ketones" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a> @endif</label>
                                                <select class="js-example-basic-multiple" name="ketones" id="Ketones">
                                                    @foreach ($ketonesdropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->ketones === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="urobilinogen" class="form-label">Urobilinogen @if (!$sample->is_completed)<a
                                                        href="" class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Urobilinogen" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a> @endif</label>
                                                <select class="js-example-basic-multiple" name="urobilinogen"
                                                    id="Urobilinogen">
                                                    @foreach ($urobilinogendropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->urobilinogen === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="proteins" class="form-label">Proteins @if (!$sample->is_completed)<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Proteins" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a> @endif</label>
                                                <select class="js-example-basic-multiple" name="proteins" id="Proteins">
                                                    @foreach ($proteinsdropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->proteins === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="colour" class="form-label">Colour</label>
                                                <input type="text" id="colour" name="colour" class="form-control"
                                                    value="{{ $urinalysisMicrobiologyResults->colour ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="appearance" class="form-label">Appearance</label>
                                                <input type="text" id="appearance" name="appearance"
                                                    class="form-control"
                                                    value="{{ $urinalysisMicrobiologyResults->appearance ?? '' }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                        </div>
                        <div class="tab-pane" id="pill-justified-profile-1" role="tabpanel">
                            <table id="tests-table" class="table table-striped display table-responsive rounded">
                                <thead>
                                    <tr>
                                        <th class="rounded-start-3 ">Test</th>
                                        <th>Test Results </th>
                                        {{-- <th>Flag </th> --}}
                                        <th>Normal Range </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                        {{------------------- epith_cells ------------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="epith_cells" class="form-control"
                                                    value="Epith. Cells" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="epith_cells_result" class="form-control test-result" id="epith_cells_result"
                                                    value="{{ $urinalysisMicrobiologyResults->epith_cells ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}

                                            <td>
                                                <p class="uri-reference-range-epith_cells">
                                                    @if(isset($referenceRanges['epith_cells']))
                                                        {{-- {{ $referenceRanges['epith_cells']->low . '-' . $referenceRanges['epith_cells']->high}} --}}
                                                        @if ($referenceRanges['epith_cells']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['epith_cells']->low . '-' . $referenceRanges['epith_cells']->high }}
                                                        @elseif ($referenceRanges['epith_cells']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['epith_cells']->male_low . '-' . $referenceRanges['epith_cells']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['epith_cells']->female_low . '-' .$referenceRanges['epith_cells']->female_high }}
                                                        @elseif ($referenceRanges['epith_cells']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['epith_cells']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="epith_cells" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{--------------------- bacteria --------------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="bacteria" class="form-control"
                                                    value="Bacteria" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="bacteria_result" class="form-control test-result" id="bacteria_result"
                                                    value="{{ $urinalysisMicrobiologyResults->bacteria ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @bacteriap
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endbacteriap --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-bacteria">
                                                    @if(isset($referenceRanges['bacteria']))
                                                        {{-- {{ $referenceRanges['bacteria']->low . '-' . $referenceRanges['bacteria']->high}} --}}
                                                        @if ($referenceRanges['bacteria']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['bacteria']->low . '-' . $referenceRanges['bacteria']->high }}
                                                        @elseif ($referenceRanges['bacteria']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['bacteria']->male_low . '-' . $referenceRanges['bacteria']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['bacteria']->female_low . '-' .$referenceRanges['bacteria']->female_high }}
                                                        @elseif ($referenceRanges['bacteria']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['bacteria']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="bacteria" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{------------------- white_cells ---------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="white_cells" class="form-control"
                                                    value="White Cells" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="white_cells_result" class="form-control test-result" id="white_cells_result"
                                                    value="{{ $urinalysisMicrobiologyResults->white_cells ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-white_cells">
                                                    @if(isset($referenceRanges['white_cells']))
                                                        {{-- {{ $referenceRanges['white_cells']->low . '-' . $referenceRanges['white_cells']->high}} --}}
                                                        @if ($referenceRanges['white_cells']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['white_cells']->low . '-' . $referenceRanges['white_cells']->high }}
                                                        @elseif ($referenceRanges['white_cells']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['white_cells']->male_low . '-' . $referenceRanges['white_cells']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['white_cells']->female_low . '-' .$referenceRanges['white_cells']->female_high }}
                                                        @elseif ($referenceRanges['white_cells']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['white_cells']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="white_cells" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                         {{------------------- yeast ---------------}}
                                         <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="yeast" class="form-control"
                                                    value="Yeast" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="yeast_result" class="form-control test-result" id="yeast_result"
                                                    value="{{ $urinalysisMicrobiologyResults->yeast ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-yeast">
                                                    @if(isset($referenceRanges['yeast']))
                                                        {{-- {{ $referenceRanges['yeast']->low . '-' . $referenceRanges['yeast']->high}} --}}
                                                        @if ($referenceRanges['yeast']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['yeast']->low . '-' . $referenceRanges['yeast']->high }}
                                                        @elseif ($referenceRanges['yeast']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['yeast']->male_low . '-' . $referenceRanges['yeast']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['yeast']->female_low . '-' .$referenceRanges['yeast']->female_high }}
                                                        @elseif ($referenceRanges['yeast']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['yeast']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="yeast" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{------------------- red_cells ---------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="red_cells" class="form-control"
                                                    value="Red Cells" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="red_cells_result" class="form-control test-result" id="red_cells_result"
                                                    value="{{ $urinalysisMicrobiologyResults->red_cells ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-red_cells">
                                                    @if(isset($referenceRanges['red_cells']))
                                                        {{-- {{ $referenceRanges['red_cells']->low . '-' . $referenceRanges['red_cells']->high}} --}}
                                                        @if ($referenceRanges['red_cells']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['red_cells']->low . '-' . $referenceRanges['red_cells']->high }}
                                                        @elseif ($referenceRanges['red_cells']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['red_cells']->male_low . '-' . $referenceRanges['red_cells']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['red_cells']->female_low . '-' .$referenceRanges['red_cells']->female_high }}
                                                        @elseif ($referenceRanges['red_cells']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['red_cells']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="red_cells" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{------------------- trichomonas ---------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="trichomonas" class="form-control"
                                                    value="Trichomonas" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="trichomonas_result" class="form-control test-result" id="trichomonas_result"
                                                    value="{{ $urinalysisMicrobiologyResults->trichomonas ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-trichomonas">
                                                    @if(isset($referenceRanges['trichomonas']))
                                                        {{-- {{ $referenceRanges['trichomonas']->low . '-' . $referenceRanges['trichomonas']->high}} --}}
                                                        @if ($referenceRanges['trichomonas']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['trichomonas']->low . '-' . $referenceRanges['trichomonas']->high }}
                                                        @elseif ($referenceRanges['trichomonas']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['trichomonas']->male_low . '-' . $referenceRanges['trichomonas']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['trichomonas']->female_low . '-' .$referenceRanges['trichomonas']->female_high }}
                                                        @elseif ($referenceRanges['trichomonas']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['trichomonas']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="trichomonas" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>
                                        {{------------------- Casts ---------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="casts" class="form-control"
                                                    value="Casts" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="casts_result" class="form-control test-result" id="casts_result"
                                                    value="{{ $urinalysisMicrobiologyResults->casts ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-casts">
                                                    @if(isset($referenceRanges['casts']))
                                                        {{-- {{ $referenceRanges['casts']->low . '-' . $referenceRanges['casts']->high}} --}}
                                                        @if ($referenceRanges['casts']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['casts']->low . '-' . $referenceRanges['casts']->high }}
                                                        @elseif ($referenceRanges['casts']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['casts']->male_low . '-' . $referenceRanges['casts']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['casts']->female_low . '-' .$referenceRanges['casts']->female_high }}
                                                        @elseif ($referenceRanges['casts']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['casts']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="casts" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>

                                        {{------------------- crystals ---------------}}
                                        <tr>
                                            <td>
                                                <input type="text" data-test-id=""
                                                    name="crystals" class="form-control"
                                                    value="Crystals" disabled />
                                            </td>
                                            <td>
                                                <input type="text"  data-test-id=""
                                                    name="crystals_result" class="form-control test-result" id="crystals_result"
                                                    value="{{ $urinalysisMicrobiologyResults->crystals ?? '' }}"
                                                    data-basic-low=""
                                                    data-basic-high=""
                                                    data-male-low=""
                                                    data-male-high=""
                                                    data-female-low=""
                                                    data-female-high=""
                                                    data-nomanual-set="" />
                                            </td>
                                            {{-- <td>
                                                <input type="text"  data-test-id=""
                                                    name="flag" class="form-control flag-input"
                                                    value="" style="width: 80px;"/> --}}
                                                {{-- @php
                                                    $background = '';
                                                    if (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Normal') {
                                                        $background = 'bg-success';
                                                    } elseif (
                                                        !empty($biochemHaemoResults) &&
                                                        $biochemHaemoResults->flag == 'High'
                                                    ) {
                                                        $background = 'bg-danger';
                                                    } elseif (!empty($biochemHaemoResults) && $biochemHaemoResults->flag == 'Low') {
                                                        $background = 'bg-warning';
                                                    }
                                                @endphp --}}
                                                {{-- <span class="badge badge-pill flag-badge d-none"
                                                    data-key="t-hot"></span>

                                            </td> --}}
                                            <td>
                                                <p class="uri-reference-range-crystals">
                                                    @if(isset($referenceRanges['crystals']))
                                                        {{-- {{ $referenceRanges['crystals']->low . '-' . $referenceRanges['crystals']->high}} --}}
                                                        @if ($referenceRanges['crystals']->urireference_range == 'uri_basic_ref')
                                                            {{ $referenceRanges['crystals']->low . '-' . $referenceRanges['crystals']->high }}
                                                        @elseif ($referenceRanges['crystals']->urireference_range == 'uri_optional_ref')
                                                            Male: {{ $referenceRanges['crystals']->male_low . '-' . $referenceRanges['crystals']->male_high }}
                                                            <br>
                                                            Female: {{ $referenceRanges['crystals']->female_low . '-' .$referenceRanges['crystals']->female_high }}
                                                        @elseif ($referenceRanges['crystals']->urireference_range == 'uri_no_manual_tag')
                                                            {{ $referenceRanges['crystals']->nomanualvalues_ref_range }}
                                                        @endif
                                                    @endif

                                                    {{--
                                                        {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}

                                                        Male: {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                                                        <br>
                                                        Female: {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}

                                                        {{ $test->nomanualvalues_ref_range }}
                                                    --}}
                                                </p>
                                            </td>
                                            <td>
                                                <a href="" class="uriRefRangesEdit" data-bs-toggle="modal"
                                                data-id="crystals" data-bs-target="#showModalRefferenceranges"> <span
                                                    class="badge bg-info text-white"> Set Range</span> </a>
                                            </td>
                                        </tr>


                                </tbody>
                            </table>
                            {{-- <div class="d-flex">

                                <div class="flex-grow-1 ms-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="epith" class="form-label">Epith</label>
                                                <input type="text" id="epith_cells" name="epith_cells"
                                                    class="form-control"
                                                    value="{{ $urinalysisMicrobiologyResults->epith_cells ?? '' }}" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="bacteria" class="form-label">Bacteria @if (!$sample->is_completed)<a href=""
                                                        class="customDropdownEdit" data-bs-toggle="modal"
                                                        data-id="Bacteria" data-bs-target="#showModalDropdown"> <span
                                                            class="badge bg-info text-white"> Add New</span> </a> @endif</label>
                                                <select class="js-example-basic-multiple" name="bacteria "
                                                    id="Bacteria">
                                                    @foreach ($bacteriadropdown as $test)
                                                        <option value="{{ $test->value }}"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->bacteria === $test->value ? 'selected' : '' }}>
                                                            {{ $test->value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="white_cells" class="form-label">White cells </label>
                                                <input type="text" id="white_cells" name="white_cells "
                                                    class="form-control"
                                                    value="{{ $urinalysisMicrobiologyResults->white_cells ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="yeast" class="form-label">Yeast </label>
                                                <input type="text" id="yeast" name="yeast" class="form-control"
                                                    value="{{ $urinalysisMicrobiologyResults->yeast ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="red_cells" class="form-label">Red Cells</label>
                                                <input type="text" id="red_cells" name="red_cells"
                                                    class="form-control"
                                                    value="{{ $urinalysisMicrobiologyResults->red_cells ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="trichomonas" class="form-label">Trichomonas </label>
                                                <input type="text" id="trichomonas" name="trichomonas"
                                                    class="form-control"
                                                    value="{{ $urinalysisMicrobiologyResults->trichomonas ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="casts" class="form-label">Casts </label>
                                                <input type="text" id="casts" name="casts" class="form-control"
                                                    value="{{ $urinalysisMicrobiologyResults->casts ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="crystals" class="form-label">Crystals </label>
                                                <input type="text" id="crystals" name="crystals"
                                                    class="form-control"
                                                    value="{{ $urinalysisMicrobiologyResults->crystals ?? '' }}" />
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="tab-pane" id="pill-justified-messages-1" role="tabpanel">
                            <div class="d-flex">
                                {{-- <div class="flex-shrink-0">
                                    <i class="ri-checkbox-circle-fill text-success"></i>
                                </div> --}}
                                <div class="flex-grow-1 ms-2">
                                    <div class="row">
                                        <h3 class="text-black">Type of Specimen :</h3>
                                        <div>
                                            {{-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="procedure" class="form-label">Procedure </label>
                                                    <select class="js-example-basic-multiple" name="procedure"
                                                        id="procedure" >
                                                        <option value="wet_prep"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->procedure === 'wet_prep' ? 'selected' : '' }}>
                                                            Wet Prep</option>
                                                        <option value="gram_stain"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->procedure === 'gram_stain' ? 'selected' : '' }}>
                                                            Gram Stain</option>
                                                        <option value="culture"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->procedure === 'culture' ? 'selected' : '' }}>
                                                            Culture</option>
                                                        <option value="stool"
                                                            {{ !empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->procedure === 'stool' ? 'selected' : '' }}>
                                                            Stool</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="specimen_note" class="form-label">Note</label>
                                                    <textarea type="text" id="specimen_note" name="specimen_note" rows="5" class="form-control"
                                                        value="">{{ $urinalysisMicrobiologyResults->specimen_note ?? '' }}</textarea>
                                                </div>
                                            </div> --}}
                                            {{-- {{dd($urinalysisMicrobiologyResults->procedureResults())}} --}}
                                            {{-- @foreach ($urinalysisMicrobiologyResults->procedureResults as $procedures) --}}
                                                {{-- <div id="procedures-container">
                                                    <div class="procedure-group">
                                                        <div class="form-group">
                                                            <label for="procedure" class="form-label">Procedure</label>
                                                            <select class="js-example-basic-multiple procedure" name="procedure[]" id="procedure" >
                                                                <option value="wet_prep">Wet Prep</option>
                                                                <option value="gram_stain">Gram Stain</option>
                                                                <option value="culture">Culture</option>
                                                                <option value="stool">Stool</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="specimen_note" class="form-label">Note</label>
                                                            <textarea type="text" id="specimen_note" name="specimen_note[]" rows="5" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" id="add-procedure" class="btn btn-primary">Add Procedure</button> --}}
                                            {{-- @endforeasch --}}

                                            {{-- <button type="button" id="add-procedure" class="btn btn-primary">Add Procedure</button> --}}
                                            <div id="procedures-container">
                                                <button type="button" class="btn btn-primary add-btn float-end mb-1"  id="add-procedure" ><i class="ri-add-line align-bottom me-1 "></i> Add
                                                    Procedure</button>
                                                @foreach ($procedureResults as $index => $procedure)
                                                    <div class="procedure-group">
                                                        <div class="form-group">
                                                            <label for="procedure" class="form-label">Procedure</label>
                                                            <select class="js-example-basic-multiple procedure" name="procedure[]">
                                                                <option value="wet_prep" {{ $procedure->procedure == 'wet_prep' ? 'selected' : '' }}>Wet Prep</option>
                                                                <option value="gram_stain" {{ $procedure->procedure == 'gram_stain' ? 'selected' : '' }}>Gram Stain</option>
                                                                <option value="culture" {{ $procedure->procedure == 'culture' ? 'selected' : '' }}>Culture</option>
                                                                <option value="stool" {{ $procedure->procedure == 'stool' ? 'selected' : '' }}>Stool</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="specimen_note" class="form-label">Note</label>
                                                            <textarea type="text" name="specimen_note[]" rows="5" class="form-control">{{ $procedure->specimen_note }}</textarea>
                                                        </div>
                                                        @if ($index > 0)
                                                            <button type="button" class="remove-procedure btn btn-danger float-end mt-1">Remove</button>
                                                        @endif
                                                    </div>

                                                @endforeach
                                            </div>




                                            {{-- <button type="submit" class="btn btn-success">Submit</button> --}}

                                        </div>


                                        <h3 class="text-black">Sensitivity :</h3>
                                        @if (!$sample->is_completed)
                                            <form id="profileForm" class="d-flex align-items-center">
                                                @csrf
                                                <div class="form-group mb-0 mr-2 col-6">
                                                    <label for="profiles" class="mr-2">Select Profiles: <a
                                                            href="{{ route('profile.index') }}" target="blank"> <span
                                                                class="badge bg-info text-white"> Add New</span> </a></label>
                                                    <select name="profiles[]" id="profiles"
                                                        class="js-example-basic-multiple form-control" multiple>
                                                        @php
                                                            $sensitivityProfilesArray = !empty(
                                                                $urinalysisMicrobiologyResults->sensitivity_profiles
                                                            )
                                                                ? json_decode(
                                                                    $urinalysisMicrobiologyResults->sensitivity_profiles,
                                                                    true,
                                                                )
                                                                : [];
                                                        @endphp
                                                        @foreach ($senstivityprofiles as $profile)
                                                            <option value="{{ $profile->id }}"
                                                                {{ in_array($profile->id, $sensitivityProfilesArray) ? 'selected' : '' }}>
                                                                {{ $profile->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <button type="button" id="createReportButton"
                                                    class="btn btn-primary ms-3 mt-4">Get Sensitivity Items</button>
                                            </form>
                                        @endif

                                        <div id="reportContainer" class="mt-3">
                                            @php
                                                $sensitivityData = !empty($urinalysisMicrobiologyResults->sensitivity)
                                                    ? json_decode($urinalysisMicrobiologyResults->sensitivity)
                                                    : [];
                                            @endphp

                                            @if (!empty($sensitivityData))
                                                @foreach ($sensitivityData as $profile)
                                                    <div class="form-group">
                                                        <label for="microorganism">Microorganism:</label>
                                                        <input type="text" name="microorganism" class="form-control"
                                                            value="{{ $profile->microorganism }}">
                                                    </div>

                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Antibiotics</th>
                                                                <th>MIC (ug/mL)</th>
                                                                <th>Sensitive</th>
                                                                <th>Resistant</th>
                                                                <th>Intermediate</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($profile->items as $item)
                                                                <tr>
                                                                    <td>{{ $item->antibiotic }}</td>
                                                                    <td><input type="text" name="mic"
                                                                            class="form-control"
                                                                            value="{{ $item->mic }}"></td>
                                                                    <td>
                                                                        <input type="radio"
                                                                            name="sensitivity_{{ $profile->microorganism }}_{{ $item->antibiotic }}"
                                                                            value="sensitive"
                                                                            {{ $item->sensitivity === 'sensitive' ? 'checked' : '' }}>
                                                                    </td>
                                                                    <td>
                                                                        <input type="radio"
                                                                            name="sensitivity_{{ $profile->microorganism }}_{{ $item->antibiotic }}"
                                                                            value="resistant"
                                                                            {{ $item->sensitivity === 'resistant' ? 'checked' : '' }}>
                                                                    </td>
                                                                    <td>
                                                                        <input type="radio"
                                                                            name="sensitivity_{{ $profile->microorganism }}_{{ $item->antibiotic }}"
                                                                            value="intermediate"
                                                                            {{ $item->sensitivity === 'intermediate' ? 'checked' : '' }}>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @endforeach
                                            @endif
                                        </div>
                                        {{-- <button type="button" id="saveReportButton" class="btn btn-success">Save Report</button> --}}

                                        {{-- <div class="col-md-4">pp
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
                    <input type="text" id="note-search" class="form-control mb-3" placeholder="Search notes...">
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
                    url: '{{ route('fetch-notes-cytology') }}',
                    success: function(notes) {
                        var notesContainer = $('#notes-container');
                        notesContainer.empty();

                        if (notes.length > 0) {
                            notes.forEach(function(note) {
                                notesContainer.append('<div class="note-item">' + note.note_code + '<br>' + note.comment + '</div>');
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

    <div class="modal fade" id="showModalcalc" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Calculation Explanation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                    <div class="modal-body">
                        <p class="calc-container"></p>
                    </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showModalRefferenceranges" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary-subtle p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Set Normal Range</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" id="refRanges_form" action="{{ url("/uriRefRanges") }}" method="Post" autocomplete="off">
                    @csrf
                    <input type="hidden" id="analyte" name="analyte">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <label for="reference_range" class="form-label">Reference range</label>
                            <div>

                                <input type="radio" id="uri_basic_ref" name="urireference_range"
                                     required  value="uri_basic_ref" checked/>
                                    <label for="uri_basic_ref" class="form-label">Basic Reference range</label>
                                <input type="radio" id="uri_optional_ref" class="ms-4" name="urireference_range"
                                     required value="uri_optional_ref" />
                                <label for="uri_optional_ref" class="form-label">Reference range with optional sex</label>
                                <input type="radio" id="uri_no_manual_tag" class="ms-4" name="urireference_range"
                                     required value="uri_no_manual_tag" />
                                <label for="uri_no_manual_tag" class="form-label">No / Manual Tag</label>
                            </div>
                        </div>
                        <div class="row" id="uribasicValues">
                            {{-- <label for="" class="form-label">High value with optional sex</label> --}}
                            {{-- <div> --}}
                                <div class="col-lg-6">
                                    <div>
                                        <label for="basic_low_value" class="form-label">Low Value</label>
                                        <input type="text" id="basic_low_value" class="form-control" name="basic_low_value"
                                            placeholder="Enter Low Value" required />
                                    </div>
                                </div>
                                    {{-- <label for="male" class="form-label">High Value</label> --}}
                                <div class="col-lg-6">
                                    <div>
                                        <label for="basic_high_value" class="form-label">High Value</label>
                                        <input type="text" id="basic_high_value" class="form-control" name="basic_high_value"
                                            placeholder="Enter High Value" required />
                                    </div>
                                </div>
                                {{-- <label for="female" class="form-label">Low value</label> --}}
                            {{-- </div> --}}
                        </div>
                        <div class="row" id="urioptionalValues">
                            <h5 for="" class="form-label text-black fw-bolder">Male </h5>
                            <div class="col-lg-6">
                                <div>
                                    <label for="male_low_value" class="form-label">Low Value</label>
                                    <input type="text" id="male_low_value" class="form-control" name="male_low_value"
                                        placeholder="Enter Low Value"  />
                                </div>
                            </div>
                                {{-- <label for="male" class="form-label">High Value</label> --}}
                            <div class="col-lg-6">
                                <div>
                                    <label for="male_high_value" class="form-label">High Value</label>
                                    <input type="text" id="male_high_value" class="form-control" name="male_high_value"
                                        placeholder="Enter High Value"  />
                                </div>
                            </div>
                            <h5 for="" class="form-label text-black fw-bolder mt-2">Female </h5>
                            <div class="col-lg-6">
                                <div>
                                    <label for="female_low_value" class="form-label">Low Value</label>
                                    <input type="text" id="female_low_value" class="form-control" name="female_low_value"
                                        placeholder="Enter Low Value"  />
                                </div>
                            </div>
                                {{-- <label for="female" class="form-label">High Value</label> --}}
                            <div class="col-lg-6">
                                <div>
                                    <label for="female_high_value" class="form-label">High Value</label>
                                    <input type="text" id="female_high_value" class="form-control" name="female_high_value"
                                        placeholder="Enter High Value"  />
                                </div>
                            </div>
                        </div>
                        <div class="row" id="urinoManualValues">
                            <textarea name="nomanualvalues" id="nomanualvalues" cols="30" rows="10"></textarea>
                        </div>
                        <div class="modal-footer">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" id="add-btn">Set Range</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                        <input type="hidden" id="sample_id" name="sample_id" value="{{ $sample->id }}">
                        <div class="row g-3">
                            <div class="col-lg-6">
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
                                <div>
                                    <label for="Calculation-Explanation" class="form-label">Calculation Explanation</label>
                                    <textarea name="calculation_explanation" id="calculation_explanation" class="form-control" cols="30" rows="3"></textarea>
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
                                <textarea name="nomanualvalues_ref_range" id="nomanualvalues_ref_range" class="form-control" cols="30" rows="4"></textarea>
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

    <!--delete Modal -->
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
                        <h4 class="fs-semibold">You are about to delete a test ?</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your test will
                            remove all of your information from our database.</p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none"
                                data-bs-dismiss="modal" id="deleteRecord-close"><i
                                    class="ri-close-line me-1 align-middle"></i>
                                Close</button>
                            <input type="text" id="delete-record-id" hidden>
                            <button class="btn btn-danger" id="delete-record">Yes,
                                Delete It!!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--UnCOMPLETE  Modal -->
    <div class="modal fade zoomIn" id="UncompleteRecordModal" tabindex="-1" aria-labelledby="UncompleteRecordModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    {{-- <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                    </lord-icon> --}}
                    <lord-icon
                        src="https://cdn.lordicon.com/guqkthkk.json"
                        trigger="loop"
                        colors="primary:#110a5c"
                        style="width:90px;height:90px">
                    </lord-icon>
                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold text-black">Are you sure want to Incomplete this Report?</h4>
                        {{-- <p class="text-muted fs-14 mb-4 pt-1">Deleting your test will
                            remove all of your information from our database.</p> --}}
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none"
                                data-bs-dismiss="modal" id="deleteRecord-close"><i
                                    class="ri-close-line me-1 align-middle"></i>
                                Close</button>
                            <input type="text" id="complete-record-id" hidden>
                            <button class="btn btn-primary" id="Uncomplete-record">Yes,
                                InComplete It !!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--COMPLETE  Modal -->
    <div class="modal fade zoomIn" id="completeRecordModal" tabindex="-1" aria-labelledby="completeRecordModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    {{-- <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                    </lord-icon> --}}
                    <lord-icon
                        src="https://cdn.lordicon.com/guqkthkk.json"
                        trigger="loop"
                        colors="primary:#110a5c"
                        style="width:90px;height:90px">
                    </lord-icon>
                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold text-black">Are you sure want to complete this Report?</h4>
                        {{-- <p class="text-muted fs-14 mb-4 pt-1">Deleting your test will
                            remove all of your information from our database.</p> --}}
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none"
                                data-bs-dismiss="modal" id="deleteRecord-close"><i
                                    class="ri-close-line me-1 align-middle"></i>
                                Close</button>
                            <input type="text" id="complete-record-id" hidden>
                            <button class="btn btn-primary" id="complete-record">Yes,
                                Complete It!!</button>
                        </div>
                    </div>
                </div>
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
                        @php
                            $testReport = $testReports
                                ->where('test_id', $test->id)
                                ->where('sample_id', $sample->id)
                                ->first();
                            // dd($testReport);
                            $cytologyGynecologyResults = $testReport
                                ? $testReport->cytologyGynecologyResults->first()
                                : [];
                            // dd($biochemHaemoResults);

                            $testIds = $tests->pluck('id')->implode(',');

                        @endphp
                    @endforeach
                    <div id="sign-form">
                        <p class="text-dark fw-semibold fs-6">Please indicate that you agree with all that is in this
                            report by signing:</p>
                        <form class="tablelist-form" id="leadtype_form" action="{{ route('test-reports.signReport') }}"
                            method="POST" autocomplete="off">
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
                                value="{{ $testIds }}">
                            <input type="hidden" id="report_sample_id" name="report_sample_id"
                                value="{{ $sample->id }}">
                            <div id="success-message" class="text-success" style="display: none;"></div>
                            <div id="error-message" class="text-danger" style="display: none;"></div>
                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="sign-button">Sign</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- end sign modal  --}}

    {{-- report notes modal  --}}
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
                    url: '{{ route('fetch-notes-cytology') }}',
                    success: function(notes) {
                        var notesContainer = $('#notes-container');
                        notesContainer.empty();

                        if (notes.length > 0) {
                            notes.forEach(function(note) {
                                // Create the note item with comment initially hidden
                                var noteItem = $('<div class="note-item">' + note.note_code + '</div>');
                                var noteComment = $('<div class="note-comment" style="display:none;">' + note.comment + '</div>');

                                // Append note item and comment to the container
                                notesContainer.append(noteItem);
                                notesContainer.append(noteComment);

                                // Toggle the comment on note item click
                                noteItem.on('click', function() {
                                    noteComment.toggle();
                                });

                                // Append the comment to the textarea on comment click
                                noteComment.on('click', function() {
                                    var selectedComment = $(this).text();
                                    var currentText = targetTextarea.val();
                                    targetTextarea.val(currentText + (currentText ? '\n' : '') + selectedComment);
                                    $('#notesModal').modal('hide'); // Optional: Hide modal after selecting a comment
                                });
                                 // Filter notes based on search input
                                $('#note-search').on('input', function() {
                                    var searchTerm = $(this).val().toLowerCase();
                                    $('.note-item').each(function() {
                                        var noteText = $(this).text().toLowerCase();
                                        if (noteText.includes(searchTerm)) {
                                            $(this).show();
                                        } else {
                                            $(this).hide();
                                        }
                                    });
                                });
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
        document.addEventListener('DOMContentLoaded', function() {
            const testResultInputs = document.querySelectorAll('.test-result');
            testResultInputs.forEach(input => {
                input.addEventListener('input', function() {
                    // console.log(input.value);
                    const testId = this.dataset.testId;
                    const testValue = parseFloat(this.value);

                    const flagInput = document.querySelector(
                    `input[name="tests[${testId}][flag]"]`);
                    const flagBadge = this.closest('tr').querySelector('.flag-badge');
                    const referenceRange = this.closest('tr').querySelector('.reference-range')
                        .innerText;

                    let low, high;
                    let sex = document.getElementById('gender').value
                    // console.log(sex);
                    if (referenceRange.includes('Male') && referenceRange.includes('Female')) {
                        // Assuming gender is available
                        const gender = sex; // Replace with actual gender logic
                        if (gender === 'Male') {
                            low = parseFloat(this.dataset.maleLow);
                            high = parseFloat(this.dataset.maleHigh);
                        } else {
                            low = parseFloat(this.dataset.femaleLow);
                            high = parseFloat(this.dataset.femaleHigh);
                        }
                    } else {
                        low = parseFloat(this.dataset.basicLow);
                        high = parseFloat(this.dataset.basicHigh);
                    }
                    // data-nomanual-set
                    let noManualSet = this.dataset.nomanualSet;
                    // console.log(noManualSet);


                    let flag = '';
                    if (!noManualSet) {
                        // flag = '';
                        if (testValue < low) {
                            flag = 'Low';
                        } else if (testValue > high) {
                            flag = 'High';
                        }else if(testValue >= low && testValue <= high){
                            flag = 'Normal';
                        }
                    }


                    flagInput.value = flag;
                    flagBadge.innerText = flag;
                    flagBadge.classList.remove('bg-danger', 'bg-warning', 'bg-success');
                    // if (flag === 'Low') {
                    //     flagBadge.classList.add('bg-warning');
                    // } else if (flag === 'High') {
                    //     flagBadge.classList.add('bg-danger');
                    // } else {
                    //     flagBadge.classList.add('bg-success');
                    // }
                    // if (flag === 'Low') {
                    //     flagInput.style.color = '#c2c22c';
                    // } else if (flag === 'High') {
                    //     flagInput.style.color = 'red';
                    // } else {
                    //     flagInput.style.color = 'green';
                    // }
                });
            });
        });

    </script>
    <script>
        jQuery(document).ready(function($) {
            $('.js-example-basic-multiple').select2();

            $('#add-procedure').click(function() {
                let newProcedureGroup = `
                    <div class="procedure-group">
                        <div class="form-group">
                            <label for="procedure" class="form-label">Procedure</label>
                            <select class="js-example-basic-multiple procedure" name="procedure[]">
                                <option value="wet_prep">Wet Prep</option>
                                <option value="gram_stain">Gram Stain</option>
                                <option value="culture">Culture</option>
                                <option value="stool">Stool</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="specimen_note" class="form-label">Note</label>
                            <textarea type="text" name="specimen_note[]" rows="5" class="form-control"></textarea>
                        </div>
                        <button type="button" class="remove-procedure btn btn-danger float-end">Remove</button>
                    </div>
                `;
                $('#procedures-container').append(newProcedureGroup);
                $('.js-example-basic-multiple').select2();
            });

            $(document).on('click', '.remove-procedure', function() {
                $(this).closest('.procedure-group').remove();
            });

            // generate pdf
            $('.generate-pdf-link').click(function(e) {
                e.preventDefault();
                var testReportId = $(this).data('test-report-id');
                // var reportType = $('#report_type').val(); // Assuming you have a dropdown with id='report_type'
                var reportType = $(this).closest('tr').find('.test-reports-dropdown').val(); // Get the report type from the closest dropdown

                // Construct the URL dynamically
                var url = "{{ url('generate-pdf') }}/" + testReportId + "/" + reportType;

                // Set the href attribute of the anchor tag to the constructed URL
                $(this).attr('href', url);

                // Optional: Open the link in a new tab/window
                window.open(url, '_blank'); // This will open the URL in a new tab
            });


            // $('#allreadyassign').hide();
            $('#optionalValues').hide();
            $('#noManualValues').hide();
            $('#basicValues').show();

            $('#urioptionalValues').hide();
            $('#urinoManualValues').hide();
            $('#uribasicValues').show();

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

            $('input[name="urireference_range"]').on('change', function() {
                if (this.value === 'uri_basic_ref') {
                    $('#uribasicValues').show();
                    $('#urioptionalValues').hide();
                    $('#urinoManualValues').hide();
                    // Make fields required
                    $('#basic_low_value').prop('required', true);
                    $('#basic_high_value').prop('required', true);
                    $('#male_low_value').prop('required', false);
                    $('#male_high_value').prop('required', false);
                    $('#female_low_value').prop('required', false);
                    $('#female_high_value').prop('required', false);
                } else if (this.value === 'uri_optional_ref') {
                    $('#uribasicValues').hide();
                    $('#urioptionalValues').show();
                    $('#urinoManualValues').hide();
                    // Make fields required
                    $('#basic_low_value').prop('required', false);
                    $('#basic_high_value').prop('required', false);
                    $('#male_low_value').prop('required', true);
                    $('#male_high_value').prop('required', true);
                    $('#female_low_value').prop('required', true);
                    $('#female_high_value').prop('required', true);
                }else if (this.value === 'uri_no_manual_tag') {
                    $('#uribasicValues').hide();
                    $('#urioptionalValues').hide();
                    $('#urinoManualValues').show();
                    // Make fields required
                    $('#basic_low_value').prop('required', false);
                    $('#basic_high_value').prop('required', false);
                    $('#male_low_value').prop('required', false);
                    $('#male_high_value').prop('required', false);
                    $('#female_low_value').prop('required', false);
                    $('#female_high_value').prop('required', false);
                }
            });
            // When the document is ready, attach a click event to the "Edit" button
            $('.edit-item-btn').on('click', function() {
                // Get the ID from the data attribute

                // var itemId = $(this).data('id');
                // var url = '{{ url('/reports/test-reports') }}' + '/' + itemId;
                // $('#leadtype_form').attr('action', url);
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
                // console.log("Resetting modal")
                // Reset modal titleq
                // $('#exampleModalLabel').html("Add Doctor");

                // Display the modal footer
                $('#showModal .modal-footer').css('display', 'block');

                // Change the button text
                // $('#add-btn').html("Add");
                // $('#leadtype_form').attr('action', '{{ url('/doctor') }}');
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


                $('#uri_basic_ref').prop('checked', true);
                $('#uribasicValues').show();
                $('#urioptionalValues').hide();
                $('#urinoManualValues').hide();
                $('#basic_low_value').prop('required', true);
                $('#basic_high_value').prop('required', true);
                $('#male_low_value').prop('required', false);
                $('#male_high_value').prop('required', false);
                $('#female_low_value').prop('required', false);
                $('#female_high_value').prop('required', false);
                $('#basic_low_value').val('');
                $('#basic_high_value').val('');
                $('#male_low_value').val('');
                $('#male_high_value').val('');
                $('#female_low_value').val('');
                $('#female_high_value').val('');
                $('#nomanualvalues').val('');
                // $('#surgeries').val("");
                // $('#surgeries').val("").trigger('change');

            }

            // Event listener for modal close event
            $('#showModal').on('hidden.bs.modal', function() {
                resetModal();
            });

            $('#showModalRefferenceranges').on('hidden.bs.modal', function() {
                resetModal();
            });

            $(document).on('click', '.remove-item-btn', function() {
                // event.preventDefault();
                // $('.remove-item-btn').on('click', function() {
                var itemId = $(this).data('id');
                var sampleid = $(this).data('sampleid');
                $('#delete-record').attr('data-id', itemId);
                $('#delete-record-id').val(itemId);
                $('#delete-record').attr('data-sampleid', sampleid);
            });

            $(document).on('click', '#delete-record', function(event) {
                // $('#delete-record').on('click', function() {
                event.preventDefault();
                // var itemId = $('#delete-record').data('id');
                var sampleid = $('#sampleid').val();
                var deleterecordid = $('#delete-record-id').val();
                var url = '{{ url('/reports/delink-test') }}' + '/' + deleterecordid;
                // Prevent the default link behavior
                // var reporttypeis = $('#report_type').val();
                data = {
                    sample_id: sampleid,
                };


                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        // Handle the success response
                        console.log('Success:', response);

                        if (response.success) {
                            // Remove the corresponding row from the table
                            $('tr').has('a[data-id="' + deleterecordid + '"]').remove();
                            // alert(response.message); // Optionally, show a success message
                            $('#deleteRecordModal').modal('hide');
                        }


                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr, status, error);
                    }
                });



            });


            $(document).on('click', '.complete-report-btn', function() {
                // event.preventDefault();
                // $('.remove-item-btn').on('click', function() {
                // var itemId = $(this).data('id');
                // var sampleid = $(this).data('sampleid');
                // $('#delete-record').attr('data-id', itemId);
                // $('#delete-record-id').val(itemId);
                // $('#delete-record').attr('data-sampleid', sampleid);
            });

            $(document).on('click', '#complete-record', function(event) {
                // $('#delete-record').on('click', function() {
                event.preventDefault();
                // var itemId = $('#delete-record').data('id');
                var sampleid = $('#sampleid').val();
                // var deleterecordid = $('#delete-record-id').val();
                var url = '{{ url('/reports/complete-test') }}';
                // Prevent the default link behavior
                // var reporttypeis = $('#report_type').val();
                data = {
                    sample_id: sampleid,
                };

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        // Handle the success response
                        console.log('Success:', response);

                        if (response.success) {
                            window.location.reload();
                            $('#completeRecordModal').modal('hide');
                        }


                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr, status, error);
                    }
                });

            });

            $(document).on('click', '#Uncomplete-record', function(event) {
                // $('#delete-record').on('click', function() {
                event.preventDefault();
                // var itemId = $('#delete-record').data('id');
                var sampleid = $('#sampleid').val();
                // var deleterecordid = $('#delete-record-id').val();
                var url = '{{ url('/reports/uncomplete-test') }}';
                // Prevent the default link behavior
                // var reporttypeis = $('#report_type').val();
                data = {
                    sample_id: sampleid,
                };

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        // Handle the success response
                        console.log('Success:', response);

                        if (response.success) {
                            window.location.reload();
                            $('#UncompleteRecordModal').modal('hide');
                        }


                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr, status, error);
                    }
                });

            });


            // Function to reset modal when clicking the "Close" button
            $('#close-modal').on('click', function() {
                resetModal();
            });
        });

        $(document).ready(function() {

            var isDirty = false;
            // console.log(isDirty);
            $('#pdfbtn').click(function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                if (isDirty) {
                    alert('You have unsaved changes. Are you sure you want to leave?');
                    window.open(url, '_blank');
                }else{
                    window.open(url, '_blank');
                }
            });

            // Example: Monitor changes in input fields
            $('textarea').on('input', function() {
                // console.log('tested');
                isDirty = true; // Set flag to true when changes are detected
                // console.log(isDirty);
            });
            // $('input[type="text"]').on('change', function() {
            //     console.log('tested input');
            //     isDirty = true; // Set flag to true when changes are detected
            //     // console.log(isDirty);
            // });

            $('.test-result, .flag-input, #s_gravity_result, #ph_result, #colour, #appearance, #epith_cells_result, #white_cells_result, #yeast_result, #red_cells_result, #trichomonas_result, #casts_result, #crystals_result, #result1').on('keyup', function() {
                // console.log('test');
                isDirty = true; // Set flag to true when changes are detected
                // console.log(isDirty);
            });

            // Example: Monitor changes in contenteditable elements
            // $('[contenteditable]').on('input', function() {
            //     isDirty = true; // Set flag to true when changes are detected
            // });

            // // Example: Monitor changes in checkboxes or radio buttons
            $('input[type="checkbox"], input[type="radio"]').on('change', function() {
                // console.log('test');
                isDirty = true; // Set flag to true when changes are detected
            });
            $('select').on('select2:select', function() {
                // console.log('test');
                isDirty = true; // Set flag to true when changes are detected via Select2
            });
            $(window).on('beforeunload', function() {
                // console.log(isDirty);
                if (isDirty === true) {
                    return 'You have unsaved changes. Are you sure you want to leave?';
                }
            });

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

                var procedures = [];
                var specimenNotes = [];
                console.log(procedures);
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
                } else if (reporttypeis == 3) {
                    var reportData = [];
                    $('#reportContainer .form-group').each(function() {

                        var microorganism = $(this).find('input[type="text"]').val();
                        console.log(microorganism);
                        // var profileId = $(this).find('input[type="text"]').attr('name').match(/\d+/)[0];
                        var items = [];

                        $(this).next('table').find('tbody tr').each(function() {
                            // var itemId = $(this).find('input[type="text"]').attr('name').match(/\d+$/)[0];
                            var antibiotic = $(this).find('td:first').text();
                            var mic = $(this).find('input[type="text"]').val();
                            var sensitivity = $(this).find('input[type="radio"]:checked')
                                .val();

                            items.push({
                                antibiotic: antibiotic,
                                mic: mic,
                                sensitivity: sensitivity
                            });
                        });

                        reportData.push({
                            // profile_id: profileId,
                            microorganism: microorganism,
                            items: items
                        });
                    });

                    $('.procedure-group').each(function() {
                        var procedure = $(this).find('.procedure').val();
                        var note = $(this).find('textarea').val();

                        procedures.push(procedure);
                        specimenNotes.push(note);
                    });

                    console.log(reportData);
                    data = {
                        sampleid: $('#sampleid').val(),
                        testIds: $('#urinalysis_test_id').val(),
                        reporttype: reporttypeis,
                        s_gravity: $('#s_gravity_result').val(),
                        ph: $('#ph_result').val(),
                        bilirubin: $('#bilirubin_result').val(),
                        blood: $('#blood_result').val(),
                        leucocytes: $('#leucocytes_result').val(),
                        glucose: $('#glucose_result').val(),
                        nitrite: $('#nitrite_result').val(),
                        ketones: $('#ketones_result').val(),
                        urobilinogen: $('#urobilinogen_result').val(),
                        proteins: $('#proteins_result').val(),
                        colour: $('#colour').val(),
                        appearance: $('#appearance').val(),
                        epith_cells: $('#epith_cells_result').val(),
                        bacteria: $('#bacteria_result').val(),
                        white_cells: $('#white_cells_result').val(),
                        yeast: $('#yeast_result').val(),
                        red_cells: $('#red_cells_result').val(),
                        trichomonas: $('#trichomonas_result').val(),
                        casts: $('#casts_result').val(),
                        crystals: $('#crystals_result').val(),
                        specimen: $('#specimen').val(),
                        procedure: procedures,
                        specimen_note: specimenNotes,
                        sensitivity_profiles: $('#profiles').val(),
                        sensitivity: JSON.stringify(reportData)
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
                            isDirty = false;
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


            $('#leadtype_form').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            var sampleid = $('#sampleid').val();
                            // Append the new test to the table
                            var newRow = '<tr>' +
                                '<td><input data-test-id="' + response.test.id +
                                '" type="text" name="tests[' + response.test.id +
                                '][description]" class="form-control" value="' + response.test
                                .name + '" disabled /><input type="text" name="tests[' +
                                response.test.id + '][id]" class="form-control" value="' +
                                response.test.id + '" hidden disabled /></td>' +
                                '<td><input data-test-id="' + response.test.id +
                                '" type="text" step="any" name="tests[' + response.test.id +
                                '][test_results]" class="form-control test-result" value="" data-basic-low="' +
                                response.test.basic_low_value_ref_range +
                                '" data-basic-high="' + response.test
                                .basic_high_value_ref_range + '" data-male-low="' + response
                                .test.male_low_value_ref_range + '" data-male-high="' + response
                                .test.male_high_value_ref_range + '" data-female-low="' +
                                response.test.female_low_value_ref_range +
                                '" data-female-high="' + response.test
                                .female_high_value_ref_range + '" data-nomanual-set="' +
                                response.test.nomanualvalues_ref_range +
                                '" /></td>' +
                                '<td><input data-test-id="' + response.test.id +
                                '" type="text"  name="tests[' + response.test.id +
                                '][flag]" class="form-control flag-input" value="" style="width: 80px;" /><span class="badge badge-pill flag-badge d-none" data-key="t-hot"></span></td>' +
                                '<td><p class="reference-range">' +
                                (response.test.reference_range === 'basic_ref' ? response.test
                                    .basic_low_value_ref_range + '-' + response.test
                                    .basic_high_value_ref_range :
                                    response.test.reference_range === 'optional_ref' ? 'Male: ' + response.test.male_low_value_ref_range + '-' +
                                    response.test.male_high_value_ref_range + '<br>Female: ' +
                                    response.test.female_low_value_ref_range + '-' + response
                                    .test.female_high_value_ref_range : response.test.nomanualvalues_ref_range) +
                                '</p></td>' +
                                '<td><textarea data-test-id="' + response.test.id +
                                '" name="tests[' + response.test.id +
                                '][test_notes]" class="form-control"></textarea></td>' +
                                '<td><li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete"><a class="remove-item-btn" data-id="' +
                                response.test.id + '" data-sampleid="' + sampleid +
                                '" data-bs-toggle="modal" href="#deleteRecordModal"><i class="ri-delete-bin-fill align-bottom text-muted"></i></a></li></td>' +
                                '</tr>';
                            if (response.test.department === '1') {
                                $('#tests-table tbody').append(newRow);
                            }

                            bindTestResultChangeEvent();

                            // Close the modal
                            $('#showModal').modal('hide');

                            // Show a success message (optional)
                            $('#leadtype_form')[0].reset();
                        } else {
                            // Show an error message (optional)
                            alert('An error occurred');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle the error
                        alert('An error occurred: ' + error);
                    }
                });
            });

            function bindTestResultChangeEvent() {
                $('.test-result').off('input').on('input', function() {
                    const testId = $(this).data('test-id');
                    const testValue = parseFloat($(this).val());
                    const flagInput = $(`input[name="tests[${testId}][flag]"]`);
                    const flagBadge = $(this).closest('tr').find('.flag-badge');
                    const referenceRange = $(this).closest('tr').find('.reference-range').text();

                    let low, high;
                    if (referenceRange.includes('Male') && referenceRange.includes('Female')) {
                        // Assuming gender is available

                        const gender = $('#gender').val(); // Replace with actual gender logic
                        if (gender === 'Male') {
                            low = parseFloat($(this).data('male-low'));
                            high = parseFloat($(this).data('male-high'));
                        } else {
                            low = parseFloat($(this).data('female-low'));
                            high = parseFloat($(this).data('female-high'));
                        }
                    } else {
                        low = parseFloat($(this).data('basic-low'));
                        high = parseFloat($(this).data('basic-high'));
                    }
                    let noManualSet = $(this).data('nomanual-set');
                    // console.log(noManualSet);


                    let flag = '';
                    if (!noManualSet) {
                        // flag = 'Normal';
                        if (testValue < low) {
                            flag = 'Low';
                        } else if (testValue > high) {
                            flag = 'High';
                        }else if(testValue >= low && testValue <= high){
                            flag = 'Normal';
                        }
                    }




                    flagInput.val(flag);
                    flagBadge.text(flag);
                    flagBadge.removeClass('bg-danger bg-warning bg-success');
                    // if (flag === 'Low') {
                    //     flagBadge.style('bg-warning');
                    // } else if (flag === 'High') {
                    //     flagBadge.addClass('bg-danger');
                    // } else {
                    //     flagBadge.addClass('bg-success');
                    // }
                    // if (flag === 'Low') {
                        // $(flagInput).css('color', '#c2c22c');
                    // } else if (flag === 'High') {
                        // $(flagInput).css('color', 'red');
                    // } else {
                        // $(flagInput).css('color', 'green');
                    // }
                });
            }

            // Initial binding for existing elements
            bindTestResultChangeEvent();
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

        $(document).on('click', '.uriRefRangesEdit', function() {
            var dropdownName = $(this).data('id');
            $('#analyte').val(dropdownName); // Set the dropdown name
            var url = '{{ url('/uriRefRanges/getvalues') }}' + '/' + dropdownName + '/edit';

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                        var test = response.success;
                        console.log(response);
                        if (test.urireference_range === 'uri_basic_ref') {
                            $('#uri_basic_ref').prop('checked', true);
                            $('#uri_optional_ref').prop('checked', false);
                            $('#uri_no_manual_tag').prop('checked', false);
                            $('#uribasicValues').show();
                            $('#urioptionalValues').hide();
                            $('#urinoManualValues').hide();
                            // Make fields required
                            $('#basic_low_value').prop('required', true);
                            $('#basic_high_value').prop('required', true);
                            $('#male_low_value').prop('required', false);
                            $('#male_high_value').prop('required', false);
                            $('#female_low_value').prop('required', false);
                            $('#female_high_value').prop('required', false);
                            $('#basic_low_value').val(test.low);
                            $('#basic_high_value').val(test.high);
                            $('#male_low_value').val(test.male_low);
                            $('#male_high_value').val(test.male_high);
                            $('#female_low_value').val(test.female_low);
                            $('#female_high_value').val(test.female_high);
                        } else if (test.urireference_range === 'uri_optional_ref') {
                            $('#uri_basic_ref').prop('checked', false);
                            $('#uri_optional_ref').prop('checked', true);
                            $('#uri_no_manual_tag').prop('checked', false);
                            $('#uribasicValues').hide();
                            $('#urioptionalValues').show();
                            $('#urinoManualValues').hide();
                            // Make fields required
                            $('#basic_low_value').prop('required', false);
                            $('#basic_high_value').prop('required', false);
                            $('#male_low_value').prop('required', true);
                            $('#male_high_value').prop('required', true);
                            $('#female_low_value').prop('required', true);
                            $('#female_high_value').prop('required', true);
                            $('#basic_low_value').val(test.low);
                            $('#basic_high_value').val(test.high);
                            $('#male_low_value').val(test.male_low);
                            $('#male_high_value').val(test.male_high);
                            $('#female_low_value').val(test.female_low);
                            $('#female_high_value').val(test.female_high);
                        }else if (test.urireference_range === 'uri_no_manual_tag') {
                            $('#uri_basic_ref').prop('checked', false);
                            $('#uri_optional_ref').prop('checked', false);
                            $('#uri_no_manual_tag').prop('checked', true);
                            $('#uribasicValues').hide();
                            $('#urioptionalValues').hide();
                            $('#urinoManualValues').show();
                            $('#basic_low_value').prop('required', false);
                            $('#basic_high_value').prop('required', false);
                            $('#male_low_value').prop('required', false);
                            $('#male_high_value').prop('required', false);
                            $('#female_low_value').prop('required', false);
                            $('#female_high_value').prop('required', false);
                            $('#nomanualvalues').val(test.nomanualvalues_ref_range);
                        }
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                    // Handle errors if needed
                }
            });
        });

        $(document).on('click', '.getcalc', function() {
            var test_id = $(this).data('id');
            // $('#analyte').val(dropdownName); // Set the dropdown name
            var url = '{{ url('getcalc') }}' + '/' + test_id + '/edit';

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                        var test = response.test;
                        // console.log(response);
                        $('.calc-container').text(test.calculation_explanation);

                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                    // Handle errors if needed
                }
            });
        });

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

        $('#refRanges_form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ url('uriRefRanges/store') }}',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function(response) {

                    let referenceRangeText = '';

                    if (response.test.urireference_range === 'uri_basic_ref') {
                        referenceRangeText = response.test.low + '-' + response.test.high;
                    } else if (response.test.urireference_range === 'uri_optional_ref') {
                        referenceRangeText = 'Male: ' + response.test.male_low + '-' + response.test.male_high + '<br>Female: ' + response.test.female_low + '-' + response.test.female_high;
                    } else {
                        referenceRangeText = response.test.nomanualvalues_ref_range;
                    }

                    $('.uri-reference-range-' + response.test.analyte).html(referenceRangeText);

                    $('#showModalRefferenceranges').modal('hide');
                },
                error: function(xhr) {
                    alert('An error occurred. Please try again.');
                    console.log(xhr.responseText); // Log the error response
                }
            });
        });

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
                    // alert('Values added/updated/deleted successfully!');

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
                report_sample_id: $('#report_sample_id').val(),
                _token: $('input[name="_token"]').val()
            };

            $.ajax({
                type: 'POST',
                url: '{{ route('test-reports.signReport') }}',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#allreadyassign').show();
                        $('#report_signed_input').show();
                        $('#report_signed').val("Report signed by " + response.user.first_name +
                            " on " + response.sample.signed_at);
                        $('#assign').hide();
                        $('#success-message').text(response.success).show();
                        $('#error-message').hide();
                        window.location.reload();
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
        });


        $(document).on('click', '#createReportButton', function() {

            console.log('clicked');
            var selectedProfiles = $('#profiles').val();
            if (selectedProfiles.length > 0) {
                $.ajax({
                    url: '{{ route('test-reports.getsensitivityitems') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: {
                        profile_ids: selectedProfiles
                    },
                    success: function(data) {
                        var reportHtml = '';
                        data.forEach(function(profile) {
                            reportHtml += `
                            <div class="form-group">
                                <label for="microorganism_${profile.id}">Microorganism:</label>
                                <input type="text" name="microorganism[${profile.id}]" class="form-control" value="${profile.name}">
                            </div>
                        `;
                            reportHtml += `
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Antibiotics</th>
                                        <th>MIC (ug/mL)</th>
                                        <th>Sensitive</th>
                                        <th>Resistant</th>
                                        <th>Intermediate</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;
                            profile.sensitivity_values.forEach(function(item) {
                                reportHtml += `
                                <tr>
                                    <td>${item.antibiotic}</td>
                                    <td><input type="text" name="mic[${item.id}]" class="form-control"></td>
                                    <td><input type="radio" name="sensitivity[${item.id}]" value="sensitive"></td>
                                    <td><input type="radio" name="sensitivity[${item.id}]" value="resistant"></td>
                                    <td><input type="radio" name="sensitivity[${item.id}]" value="intermediate"></td>
                                </tr>
                            `;
                            });
                            reportHtml += `
                                </tbody>
                            </table>
                        `;
                        });
                        $('#reportContainer').html(reportHtml);
                    }
                });
            } else {
                alert('Please select at least one profile.');
            }
        });

        $(document).on('click', '#saveReportButton', function() {
            // $('#saveReportButton').click(function() {
            var reportData = [];
            $('#reportContainer .form-group').each(function() {

                var microorganism = $(this).find('input[type="text"]').val();
                console.log(microorganism);
                // var profileId = $(this).find('input[type="text"]').attr('name').match(/\d+/)[0];
                var items = [];

                $(this).next('table').find('tbody tr').each(function() {
                    // var itemId = $(this).find('input[type="text"]').attr('name').match(/\d+$/)[0];
                    var antibiotic = $(this).find('td:first').text();
                    var mic = $(this).find('input[type="text"]').val();
                    var sensitivity = $(this).find('input[type="radio"]:checked').val();

                    items.push({
                        antibiotic: antibiotic,
                        mic: mic,
                        sensitivity: sensitivity
                    });
                });

                reportData.push({
                    // profile_id: profileId,
                    microorganism: microorganism,
                    items: items
                });
            });

            console.log(reportData);

            // $.ajax({
            //     url: $('#saveReportForm').attr('action'),
            //     method: 'POST',
            //     data: {
            //         _token: $('input[name="_token"]').val(),
            //         reportData: JSON.stringify(reportData)
            //     },
            //     success: function(response) {
            //         alert('Report saved successfully');
            //     }
            // });
        });


        $('#signModal').on('hidden.bs.modal', function() {
            // Reset form fields
            $('#email').val('');
            $('#password').val('');
            $('#success-message').hide();
            $('#error-message').hide();
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
@endsection
