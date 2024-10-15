<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Border Life - LIS</title>

    <style>
        @page {
            margin: 10mm 10mm 30mm 10mm;
        }

        body {
            font-family: 'Cambria', sans-serif;
            margin: 0;
            padding: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        span,
        label {
            font-family: 'Cambria', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        thead {
            display: table-header-group;
        }

        tbody {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid;
        }

        table thead th {
            text-align: left;
            font-size: 14px;
        }


        th,
        td {
            padding: 8px;
            font-size: 14px;
        }
        .table-wrapper {
           page-break-inside: avoid;
        }
        .order-details h2 {
            margin-top: 0;
            margin-bottom: 10px;
            border-bottom: 2px solid #3d90ca;
            padding-bottom: 5px;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }

        .company-data span {
            display: inline-block;
            font-size: 14px;
            font-weight: 400;
        }

        .no-border {
            border: 1px solid #3d90ca !important;
        }

        .bg-blue {
            border: 1px solid #3d90ca;
            /* background-color: #3d90ca; */
            color: #3d90ca;
        }

        tr.microorganism-row {
            page-break-inside: avoid;
        }

        .page-break {
            page-break-before: always;
        }

        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
            bottom: -20mm;
            left: 0;
            right: 0;
            font-size: 12px;

        }

        .footer .left,
        .footer .center,
        .footer .right {
            display: inline-block;
            width: 35%;
        }
    </style>
</head>

<body>

    <table class="order-details">
        <thead>
            <tr>
                <th width="50%" colspan="2">
                    <img src="{{ public_path('build/images/logo-lis.png') }}" alt="Logo" class="logo">
                    <p><strong>71 Eastern Main Road Barataria, San Juan Trinidad and Tobago</strong></p>
                    {{-- <h2 class="text-start">Funda Ecommerce</h2> --}}
                </th>
                <th width="50%" colspan="5" class="text-end company-data">
                    <img height="50" src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code"><br><br>
                    <span><strong>TEL: </strong>(868) 229-8643 or 316-1383</span> <br>
                    <span><strong>Mail: </strong>borderlifemedlab@gmail.com</span> <br>
                </th>
            </tr>
            <tr>
                <th width="40%" colspan="" style="vertical-align: top;  ">
                    <h2>Patient Information</h2><br>
                    <span><strong>Name:</strong>
                        {{ $sample->patient->first_name ?? '' }} {{ $sample->patient->surname ?? '' }}
                    </span><br><br>
                    <span><strong>Sex:</strong> {{ $sample->patient->sex ?? '' }}</span>
                    <span><strong> &nbsp;&nbsp;&nbsp;&nbsp; DOB:</strong>{{ $sample->patient->dob ?? '' }}</span>
                    <br><br>
                </th>
                <th width="60%" colspan="6" class="company-data" style="vertical-align: top; ">
                    <h2>Report Information</h2>
                    <table>
                        <tr>
                            <td><strong>Collection Date:</strong>
                                {{ \Carbon\Carbon::parse($sample->collected_date)->format('d-M-Y') }}</td>
                            <td><strong>Lab Ref:</strong> {{ $sample->access_number ?? '' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Received Date:</strong>
                                {{ \Carbon\Carbon::parse($sample->received_date)->format('d-M-Y') }}</td>
                            <td><strong>Company:</strong> PRIVATE</td>
                        </tr>
                        <tr>
                            <td><strong>Report Date:</strong>
                                {{ \Carbon\Carbon::parse($sample->created_at)->format('d-M-Y') }}</td>
                            <td><strong>Sample ID:</strong> {{ $sample->test_number ?? '' }}</td>
                        </tr>
                    </table>
                </th>

            </tr>
            <tr>
                <th colspan="7" style="">
                    @php
                        // Assuming $sample->tests is a collection or array of test objects
                        $testNames = $tests->pluck('name')->implode(', ');
                        $individualtests = $sample->tests()->where('department', $reporttype)->pluck('name')->implode(', ');
                        // $sampleprofiles = $sample->testProfiles()->pluck('name')->implode(', ');
                        $sampleprofiles = $sample->testProfiles()->whereHas('departments', function($query) use ($reporttype) {
                                $query->where('department', $reporttype);
                            })->with('tests')->pluck('name')->implode(', ');

                    @endphp
                    <span style="white-space: nowrap;"><strong>Request: {{ $sampleprofiles  . ', ' . $individualtests  }}</strong></span>
                </th>
            </tr>
        </thead>
        <tbody>
            {{-- {{dd($tests)}} --}}
            @foreach ($tests as $index => $test)
            @php
            $testReport = $testReports
            ->where('test_id', $test->id)
            ->where('sample_id', $sample->id)
            ->first();
            if (empty($testReport)) {
                continue;
            }
            if (!empty($testReport) && empty($testReport->urinalysisMicrobiologyResults->first())) {
                continue;
            }
            $urinalysisMicrobiologyResults = $testReport
            ? $testReport->urinalysisMicrobiologyResults->first()
            : [];

            $procedureResults = $urinalysisMicrobiologyResults
            ? $urinalysisMicrobiologyResults->procedureResults
            : [];



            @endphp
            @endforeach
            {{-- {{dd($urinalysisMicrobiologyResults)}} --}}
            @if (!empty($urinalysisMicrobiologyResults))
                <tr style="margin:0px;">
                    <tr>
                        <th class="text-start heading" colspan="7" style="border-top: 2px solid #3d90ca; ">
                            URINALYSIS
                        </th>
                    </tr>
                    <td colspan="2" style="vertical-align: top;  border:1px solid #3d90ca;">
                        <table class="chemical-analysis">
                            <!-- Add rows for chemical analysis data -->
                            <thead>

                                <tr>
                                    <th class="heading" colspan="3" style="text-align: center"> CHEMICAL ANALYSIS</th>
                                </tr>
                                <tr class="bg-blue">
                                    <th width="30%">Test</th>
                                    <th>Results</th>
                                    <th>Flag</th>
                                    <th>Normal Range</th>
                                </tr>
                            </thead>
                            <tbody>
                                        @foreach ($categorizedTests as $profileId => $profileData)
                                            <tr id="{{ $profileId }}">
                                                <td colspan="7"><strong>{{ $profileData['name'] }}</strong></td>
                                            </tr>
                                            @php
                                                $microscopyTests = collect();
                                                $chemicalAnalysisTests = collect();
                                                // dd($profileData['tests']);
                                                foreach ($profileData['tests'] as $test) {
                                                    // dd($profileData['tests']);
                                                    if ($test->urin_test_type === '2') {
                                                        $microscopyTests->push($test);
                                                    } elseif ($test->urin_test_type === '1') {
                                                        $chemicalAnalysisTests->push($test);
                                                    }
                                                }
                                                // dd($microscopyTests);
                                            @endphp
                                            @foreach ($chemicalAnalysisTests as $index => $test)
                                                @php
                                                    $testReport = $testReports
                                                        ->where('test_id', $test->id)
                                                        ->where('sample_id', $sample->id)
                                                        ->first();
                                                    $urinalysisMicrobiologyResults = $testReport ? $testReport->urinalysisMicrobiologyResults->first() : [];
                                                    $flag = $urinalysisMicrobiologyResults->flag ?? '';
                                                    $background = '';

                                                    if ($flag == 'Normal') {
                                                        $background = 'color:#40bb82';
                                                    } elseif ($flag == 'High') {
                                                        $background = 'color:red';
                                                    } elseif ($flag == 'Low') {
                                                        $background = 'color:#ffca5b';
                                                    }

                                                    $referenceRange = '';

                                                    if ($test->reference_range == 'basic_ref') {
                                                        $referenceRange = ($test->basic_low_value_ref_range ?? '') . '-' . ($test->basic_high_value_ref_range ?? '');
                                                    } elseif ($test->reference_range == 'optional_ref') {
                                                        $referenceRange = 'Male: ' . ($test->male_low_value_ref_range ?? '') . '-' . ($test->male_high_value_ref_range ?? '') . '<br>Female: ' . ($test->female_low_value_ref_range ?? '') . '-' . ($test->female_high_value_ref_range ?? '');
                                                    } elseif ($test->reference_range == 'no_manual_tag') {
                                                        $referenceRange = ($test->nomanualvalues_ref_range ?? '');
                                                    }
                                                @endphp
                                                    <tr>
                                                        <td><strong> {{ $urinalysisMicrobiologyResults->description ?? '' }} </strong> </td>
                                                        <td> {{ $urinalysisMicrobiologyResults->test_results ?? '' }}</td>
                                                        <td>
                                                            <span class="badge badge-pill flag-badge" style="{{ $background }}" data-key="t-hot">{{ $flag }}</span>
                                                        </td>
                                                        <td>{!! $referenceRange !!}</td>
                                                    </tr>
                                            @endforeach
                                        @endforeach
                                {{-- @if (!$urinalysisMicrobiologyResults->s_gravity && !$urinalysisMicrobiologyResults->ph &&
                                !$urinalysisMicrobiologyResults->leucocytes && !$urinalysisMicrobiologyResults->nitrite && !$urinalysisMicrobiologyResults->glucose &&
                                !$urinalysisMicrobiologyResults->ketones && !$urinalysisMicrobiologyResults->proteins && !$urinalysisMicrobiologyResults->urobilinogen
                                && !$urinalysisMicrobiologyResults->bilirubin && !$urinalysisMicrobiologyResults->blood && !$urinalysisMicrobiologyResults->colour && !$urinalysisMicrobiologyResults->appearance
                                ) --}}
                                {{-- <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endif --}}



                            </tbody>
                        </table>
                    </td>
                    <td colspan="5" style="vertical-align: top;  border:1px solid #3d90ca;">
                        <table class="microscopy-analysis">
                            <thead>
                                <tr>
                                    <th class="heading" colspan="3" style="text-align: center">MICROSCOPY</th>
                                </tr>
                                <tr class="bg-blue" >
                                    <th width="30%">Test</th>
                                    <th>Results</th>
                                    <th>Flag</th>
                                    <th>Normal Range</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @if (!$urinalysisMicrobiologyResults->white_cells && !$urinalysisMicrobiologyResults->epith_cells &&
                                !$urinalysisMicrobiologyResults->red_cells && !$urinalysisMicrobiologyResults->casts && !$urinalysisMicrobiologyResults->crystals &&
                                !$urinalysisMicrobiologyResults->bacteria && !$urinalysisMicrobiologyResults->yeast && !$urinalysisMicrobiologyResults->trichomonas
                                )
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endif --}}
                                @foreach ($categorizedTests as $profileId => $profileData)
                                    <tr id="{{ $profileId }}">
                                        <td colspan="7"><strong>{{ $profileData['name'] }}</strong></td>
                                    </tr>
                                    @php
                                        $microscopyTests = collect();
                                        $chemicalAnalysisTests = collect();
                                        // dd($profileData['tests']);
                                        foreach ($profileData['tests'] as $test) {
                                            // dd($profileData['tests']);
                                            if ($test->urin_test_type === '2') {
                                                $microscopyTests->push($test);
                                            } elseif ($test->urin_test_type === '1') {
                                                $chemicalAnalysisTests->push($test);
                                            }
                                        }
                                        // dd($microscopyTests);
                                    @endphp
                                    @foreach ($microscopyTests as $index => $test)
                                        @php
                                            $testReport = $testReports
                                                ->where('test_id', $test->id)
                                                ->where('sample_id', $sample->id)
                                                ->first();
                                            $urinalysisMicrobiologyResults = $testReport ? $testReport->urinalysisMicrobiologyResults->first() : [];
                                            $flag = $urinalysisMicrobiologyResults->flag ?? '';
                                            $background = '';

                                            if ($flag == 'Normal') {
                                                $background = 'color:#40bb82';
                                            } elseif ($flag == 'High') {
                                                $background = 'color:red';
                                            } elseif ($flag == 'Low') {
                                                $background = 'color:#ffca5b';
                                            }

                                            $referenceRange = '';

                                            if ($test->reference_range == 'basic_ref') {
                                                $referenceRange = ($test->basic_low_value_ref_range ?? '') . '-' . ($test->basic_high_value_ref_range ?? '');
                                            } elseif ($test->reference_range == 'optional_ref') {
                                                $referenceRange = 'Male: ' . ($test->male_low_value_ref_range ?? '') . '-' . ($test->male_high_value_ref_range ?? '') . '<br>Female: ' . ($test->female_low_value_ref_range ?? '') . '-' . ($test->female_high_value_ref_range ?? '');
                                            } elseif ($test->reference_range == 'no_manual_tag') {
                                                $referenceRange = ($test->nomanualvalues_ref_range ?? '');
                                            }
                                        @endphp
                                            <tr>
                                                <td><strong> {{ $urinalysisMicrobiologyResults->description ?? '' }} </strong> </td>
                                                <td> {{ $urinalysisMicrobiologyResults->test_results ?? '' }}</td>
                                                <td>
                                                    <span class="badge badge-pill flag-badge" style="{{ $background }}" data-key="t-hot">{{ $flag }}</span>
                                                </td>
                                                <td>{!! $referenceRange !!}</td>
                                            </tr>
                                    @endforeach
                                @endforeach

                            </tbody>
                        </table>
                    </td>
                </tr>
            @endif

        </tbody>
    </table>

    @php
    // dd($procedureResults);
    $filteredResults = collect($procedureResults)->filter(function ($item) {
    return !empty($item->specimen_note);
    });
    @endphp

    @if ($filteredResults->isNotEmpty())
    <table class="table-wrapper">
        <thead>
            <tr>
                <th class="text-start heading" colspan="2">
                    MICROBIOLOGY
                </th>
            </tr>
            <tr class="bg-blue">
                <th width="30%">PROCEDURE</th>
                <th>RESULTS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($procedureResults as $key => $value)
            <tr>
                <td width="10%"><strong>{{ $value->procedure ?? '' }}</strong></td>
                <td>
                    {{ $value->specimen_note ?? '' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- <div class="page-break"></div> --}}
    @endif

    @php
    $data = json_decode($urinalysisMicrobiologyResults->sensitivity ?? '[]', true);
    @endphp
    @if ($data)

    <table class="table-wrapper">
        <thead>
            <tr>
                <th class=" text-start heading" colspan="2">
                    SENSITIVITY
                </th>
            </tr>
            <tr class="bg-blue">
                <th width="40%">MICROORGANISM &nbsp; ISOLATED</th>
                <th>ANTIBIOTICS </th>
                <th>MIC(ug/mL)</th>
                <th>SENSITIVE</th>
                <th>RESISTANT</th>
                <th>INTERMEDIATE</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $i)
            @foreach ($i['items'] as $index => $item)
            <tr style="{{ $index === 0 ? '' : '' }}">
                <td>{{ $index === 0 ? $i['microorganism'] : '' }}</td>
                <td>{{ $item['antibiotic'] }}</td>
                <td class="text-center">{{ $item['mic'] }}</td>
                <td class="text-center"><input type="radio"
                        name="{{ $i['microorganism'] }}-{{ $item['antibiotic'] }}-sensitivity" {{
                        $item['sensitivity']==='sensitive' ? 'checked' : '' }}></td>
                <td class="text-center"><input type="radio"
                        name="{{ $i['microorganism'] }}-{{ $item['antibiotic'] }}-sensitivity" {{
                        $item['sensitivity']==='resistant' ? 'checked' : '' }}></td>
                <td class="text-center"><input type="radio"
                        name="{{ $i['microorganism'] }}-{{ $item['antibiotic'] }}-sensitivity" {{
                        $item['sensitivity']==='intermediate' ? 'checked' : '' }}></td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
    @endif

        <script type="text/php">
            if ( isset($pdf) ) {
                $pdf->page_script('
                    if ($PAGE_COUNT > 0) {
                        $font = $fontMetrics->get_font("Cambria, serif", "normal");
                        $size = 9;
                        $pdf->text(45, 786, "Signed by: {{$signed_by}}", $font, $size);
                        $pdf->text(440, 786, "Validated by: {{$validated_by}}", $font, $size);
                        $pdf->text(30, 795, "______________________________________________________________________________________________________________________", $font, $size,array(61/255, 144/255, 202/255));
                        $pdf->text(270, 815, "Page $PAGE_NUM of $PAGE_COUNT", $font, $size);
                    }
                ');
            }
        </script>



</body>

</html>
