<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Border Life - LIS</title>
    <style>
        @page {
            margin: 10mm 10mm 30mm 10mm;
            box-sizing: border-box;
        }
        body {
            font-family: 'Cambria', sans-serif;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
            box-sizing: border-box;
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
        th, td {
            padding: 4px;
            font-size: 14px;
            line-height: 1.1;
            box-sizing: border-box;
        }
        .order-details h2 {
            margin-top: 0;
            margin-bottom: 8px;
            border-bottom: 2px solid #3d90ca;
            padding-bottom: 3px;
        }
        .text-start {
            text-align: left;
        }
        .text-end {
            text-align: right;
        }
        .company-data span {
            display: inline-block;
            font-size: 14px;
            font-weight: 400;
        }
        .bg-blue {
            border: 1px solid #3d90ca;
            /* background-color: #3d90ca; */
            color: #3d90ca;
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
        .footer .left, .footer .center, .footer .right {
            display: inline-block;
            width: 35%;
        }

         .request-section {
            /* margin: 2px 0; */
            padding: 4px 8px;
            background-color: #f9f9f9;
            border-left: 4px solid #3d90ca;
        }


        .request-section strong {
            color: #3d90ca;
            /* font-size: 12px; */
        }
        .specimen-type {
            background-color: #e8f5e8;
            padding: 3px 8px;
            margin: 3px 0;
            border-left: 3px solid #28a745;
            font-style: italic;
            color: #155724;
            font-size: 11px;
        }
        .heading {
            background-color: #3d90ca;
            color: white;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
        .micro-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .micro-table th, .micro-table td {
            /* border: 1px solid #3d90ca; */
            padding: 6px;
            text-align: left;
            font-size: 12px;
        }
        .micro-table .bg-blue th {
            background-color: #3d90ca;
            color: white;
            font-size: 11px;
            text-align: center;
        }
        .flag-normal { color: #40bb82; background-color: #d4edda; padding: 2px 6px; border-radius: 10px; font-size: 0.8em; }
        .flag-high { color: red; background-color: #f8d7da; padding: 2px 6px; border-radius: 10px; font-size: 0.8em; }
        .flag-low { color: orange; background-color: #fff3cd; padding: 2px 6px; border-radius: 10px; font-size: 0.8em; }
        .sensitivity-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .sensitivity-table th, .sensitivity-table td {
            border: 1px solid #3d90ca;
            padding: 6px;
            text-align: center;
            font-size: 12px;
        }
        .sensitivity-table .bg-blue th {
            background-color: #3d90ca;
            color: white;
            font-size: 11px;
        }
        .sensitivity-result {
            font-weight: bold;
            text-transform: uppercase;
        }
        .procedure-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .procedure-table th, .procedure-table td {
            border: 1px solid #3d90ca;
            padding: 6px;
            text-align: left;
            font-size: 12px;
            vertical-align: top;
        }
        .procedure-table .bg-blue th {
            background-color: #3d90ca;
            color: white;
            font-size: 11px;
            text-align: center;
        }
        .review-section {
            border: 1px solid #3d90ca;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<table class="order-details">
    <thead>
        <tr>
            <th width="50%" style="vertical-align: top;">
                <img src="{{ public_path('build/images/logo-lis.png') }}" alt="Logo" style="height: 70px;"><br>
                <span style="display: block; font-weight:normal;, font-size: 15px; margin-top: 1px;"><small>ISO:15189 Accredited</small></span>
                <p style="margin-top: 8px;"><small>71 Eastern Main Road Barataria, San Juan Trinidad and Tobago</small></p>
            </th>
            <th width="50%"  class="text-end company-data">
                <img height="50" src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code"><br><br>
                <span style="display: inline-block; text-align: left; width: 100%;"><strong>TEL: </strong>(868) 229-8643 or 316-1383</span><br>
                <span style="display: inline-block; text-align: left; width: 100%;"><strong>Mail: </strong>borderlifemedlab@gmail.com</span><br>
            </th>
        </tr>
        <tr>
            <th width="50%" style="vertical-align: top;">
                <h2>Patient Information</h2>
                <table>
                    <tr>
                        <td style="font-weight: normal"><strong>Name:</strong> {{ $sample->patient->first_name ?? '' }} {{ $sample->patient->surname ?? '' }}</td>
                        <td style="font-weight: normal"><strong>Sex:</strong> {{ $sample->patient->sex ?? '' }}</td>
                    </tr>
                    @php
                        $dob = \Carbon\Carbon::parse($sample->patient->dob);
                        $age = $dob->age;
                    @endphp
                    <tr>
                        <td style="font-weight: normal"><strong>DOB:</strong> {{ \Carbon\Carbon::parse($sample->patient->dob)->format('d-M-Y') }}</td>
                        <td style="font-weight: normal"><strong>Age:</strong> {{ $age }} yrs</td>
                    </tr>
                    <tr>
                        <td style="font-weight: normal"><strong>Ordering Dr:</strong> {{ $sample->doctor->name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: normal"><strong>Institution:</strong> {{ $sample->institution->name }}</td>
                    </tr>
                </table>
            </th>
            <th width="50%" class="company-data" style="vertical-align: top;">
                <h2>Report Information</h2>
                <table>
                    <tr>
                        <td style="font-weight: normal"><strong>Collection Date:</strong> {{ \Carbon\Carbon::parse($sample->collected_date)->format('d-M-Y') }}</td>
                        <td style="font-weight: normal"><strong>Lab Ref:</strong> {{ $sample->access_number ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: normal"><strong>Received Date:</strong> {{ \Carbon\Carbon::parse($sample->received_date)->format('d-M-Y') }}</td>
                        <td style="font-weight: normal"><strong>Sample ID:</strong> {{ $sample->access_number ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: normal"><strong>Report Date:</strong> {{ \Carbon\Carbon::parse($sample->created_at)->format('d-M-Y') }}</td>
                    </tr>
                </table>
            </th>
        </tr>
        <tr>
            <td colspan="4">
                <hr style="border: 1px solid #3d90ca; margin: 10px 0;">
            </td>
        </tr>
        <tr>
            <th colspan="4">
                @php
                    // Assuming $sample->tests is a collection or array of test objects
                    $testNames = $tests->pluck('name')->implode(', ');
                    $individualtests = $sample->tests()->where('department', $reporttype)->pluck('name')->implode(', ');
                    // $sampleprofiles = $sample->testProfiles()->pluck('name')->implode(', ');
                    $sampleprofiles = $sample->testProfiles()->whereHas('departments', function($query) use ($reporttype) {
                                        $query->where('department', $reporttype);
                                    })->with('tests')->pluck('name')->implode(', ');

                @endphp

                <span class="request-section">
                    <strong>Request: {{ $sampleprofiles  . ', ' . $individualtests  }}</strong>
                </span>
            </th>
        </tr>
        <tr>
            @php
                $urinalysisStatus = $sample->departmentStatus('3');
            @endphp
            <td colspan="4" >
                <span style="white-space: nowrap;"><strong>Comments: </strong>{{$urinalysisStatus->note ?? ''}} </span>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <hr style="border: 1px solid #3d90ca; margin: 10px 0;">
            </td>
        </tr>
    </thead>
    <tbody>
        @php
            $hasUrineCS = false;
            if ($sample->testProfiles()->count() > 0) {
                $hasUrineCS = $sample->testProfiles()->get()->contains(function ($test) {
                    return stripos($test->name, 'urine c/s') !== false || stripos($test->name, 'culture and sensitivity') !== false;
                });
            }
        @endphp

        @if (!empty($hasUrineCS) && $hasUrineCS)
            <tr>
                <td colspan="4" class="heading">URINALYSIS</td>
            </tr>
            <tr>
                <td  style="vertical-align: top; padding: 0;">
                    <table class="micro-table">
                        <tr>
                            <th colspan="4" class="heading">CHEMICAL ANALYSIS</th>
                        </tr>
                        <tr class="bg-blue">
                            <th width="50%">Test</th>
                            <th width="25%">Results</th>
                            {{-- <th width="10%">Flag</th> --}}
                            <th width="25%">Normal Range</th>
                        </tr>
                        @foreach ($categorizedTests as $profileId => $profileData)
                            @if(!empty($profileData['tests']))
                                <tr>
                                    <td colspan="4"><strong>* {{ $profileData['name'] }}</strong></td>
                                </tr>
                                @php
                                    $microscopyTests = collect();
                                    $chemicalAnalysisTests = collect();
                                    foreach ($profileData['tests'] as $test) {
                                        if ($test->urin_test_type === '2') {
                                            $microscopyTests->push($test);
                                        } elseif ($test->urin_test_type === '1') {
                                            $chemicalAnalysisTests->push($test);
                                        }
                                    }
                                @endphp
                                @foreach ($chemicalAnalysisTests as $test)
                                    @php
                                        $testReport = $testReports
                                            ->where('test_id', $test->id)
                                            ->where('sample_id', $sample->id)
                                            ->first();
                                        $urinalysisMicrobiologyResults = $testReport
                                            ? $testReport->urinalysisMicrobiologyResults->first()
                                            : [];
                                        $flag = $urinalysisMicrobiologyResults->flag ?? '';
                                        $flagClass = '';
                                        if ($flag == 'Normal') {
                                            $flagClass = 'flag-normal';
                                        } elseif ($flag == 'High') {
                                            $flagClass = 'flag-high';
                                        } elseif ($flag == 'Low') {
                                            $flagClass = 'flag-low';
                                        }

                                        $referenceRange = '';

                                        if ($test->reference_range == 'basic_ref') {
                                            $referenceRange =
                                                ($test->basic_low_value_ref_range ?? '') .
                                                '-' .
                                                ($test->basic_high_value_ref_range ?? '') .
                                                ' ' .
                                                ($test->basic_unit_value_ref_range ?? '');
                                        } elseif ($test->reference_range == 'optional_ref') {
                                            $referenceRange =
                                                'Male: ' .
                                                ($test->male_low_value_ref_range ?? '') .
                                                '-' .
                                                ($test->male_high_value_ref_range ?? '') .
                                                ' ' .
                                                ($test->male_unit_value_ref_range ?? '') .
                                                '<br>Female: ' .
                                                ($test->female_low_value_ref_range ?? '') .
                                                '-' .
                                                ($test->female_high_value_ref_range ?? '') .
                                                ' ' .
                                                ($test->female_unit_value_ref_range ?? '');
                                        } elseif ($test->reference_range == 'no_manual_tag') {
                                            $referenceRange = $test->nomanualvalues_ref_range ?? '';
                                        }
                                    @endphp
                                    <tr>
                                        <td><small>{{ $urinalysisMicrobiologyResults->description ?? $test->name }}</small></td>
                                        <td><small>{{ $urinalysisMicrobiologyResults->test_results ?? '' }}</small></td>
                                        {{-- <td>
                                            @if($flag)
                                                <span class="{{ $flagClass }}"><small>{{ $flag }}</small></span>
                                            @endif
                                        </td> --}}
                                        <td><small>{!! $referenceRange !!}</small></td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </table>
                </td>
                <td  style="vertical-align: top; padding: 0;">
                    <table class="micro-table">
                        <tr>
                            <th colspan="4" class="heading">MICROSCOPY</th>
                        </tr>
                        <tr class="bg-blue">
                            <th width="50%">Test</th>
                            <th width="25%">Results</th>
                            {{-- <th width="10%">Flag</th> --}}
                            <th width="25%">Normal Range</th>
                        </tr>
                        @foreach ($categorizedTests as $profileId => $profileData)
                            @if(!empty($profileData['tests']))
                                <tr>
                                    <td colspan="4"><strong>* {{ $profileData['name'] }}</strong></td>
                                </tr>
                                @php
                                    $microscopyTests = collect();
                                    $chemicalAnalysisTests = collect();
                                    foreach ($profileData['tests'] as $test) {
                                        if ($test->urin_test_type === '2') {
                                            $microscopyTests->push($test);
                                        } elseif ($test->urin_test_type === '1') {
                                            $chemicalAnalysisTests->push($test);
                                        }
                                    }
                                @endphp
                                @foreach ($microscopyTests as $test)
                                    @php
                                        $testReport = $testReports
                                            ->where('test_id', $test->id)
                                            ->where('sample_id', $sample->id)
                                            ->first();
                                        $urinalysisMicrobiologyResults = $testReport
                                            ? $testReport->urinalysisMicrobiologyResults->first()
                                            : [];
                                        $flag = $urinalysisMicrobiologyResults->flag ?? '';
                                        $flagClass = '';
                                        if ($flag == 'Normal') {
                                            $flagClass = 'flag-normal';
                                        } elseif ($flag == 'High') {
                                            $flagClass = 'flag-high';
                                        } elseif ($flag == 'Low') {
                                            $flagClass = 'flag-low';
                                        }

                                        $referenceRange = '';

                                        if ($test->reference_range == 'basic_ref') {
                                            $referenceRange =
                                                ($test->basic_low_value_ref_range ?? '') .
                                                '-' .
                                                ($test->basic_high_value_ref_range ?? '') .
                                                ' ' .
                                                ($test->basic_unit_value_ref_range ?? '');
                                        } elseif ($test->reference_range == 'optional_ref') {
                                            $referenceRange =
                                                'Male: ' .
                                                ($test->male_low_value_ref_range ?? '') .
                                                '-' .
                                                ($test->male_high_value_ref_range ?? '') .
                                                ' ' .
                                                ($test->male_unit_value_ref_range ?? '') .
                                                '<br>Female: ' .
                                                ($test->female_low_value_ref_range ?? '') .
                                                '-' .
                                                ($test->female_high_value_ref_range ?? '') .
                                                ' ' .
                                                ($test->female_unit_value_ref_range ?? '');
                                        } elseif ($test->reference_range == 'no_manual_tag') {
                                            $referenceRange = $test->nomanualvalues_ref_range ?? '';
                                        }
                                    @endphp
                                    <tr>
                                        <td style="vertical-align: top;"><small>{{ $urinalysisMicrobiologyResults->description ?? $test->name }}</small></td>
                                        <td style="vertical-align: top;"><small>{{ $urinalysisMicrobiologyResults->test_results ?? '' }}</small></td>
                                        {{-- <td style="vertical-align: top;">
                                            @if($flag)
                                                <span class="{{ $flagClass }}"><small>{{ $flag }}</small></span>
                                            @endif
                                        </td> --}}
                                        <td style="vertical-align: top;"><small>{!! $referenceRange !!}</small></td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </table>
                </td>
            </tr>
        @endif

        @php
            $procedureResults = $sample ? $sample->procedureResults : [];
            $filteredResults = collect($procedureResults)->filter(function ($item) {
                return !empty($item->specimen_note);
            });
        @endphp

        @if ($filteredResults->isNotEmpty())
            <tr>
                <td colspan="4">
                    <hr style="border: 1px solid #3d90ca; margin: 10px 0;">
                </td>
            </tr>
            <tr>
                <td colspan="4" class="heading">MICROBIOLOGY</td>
            </tr>
            <tr>
                <td colspan="4">
                    <table class="procedure-table">
                        <tr class="bg-blue">
                            <th width="30%">PROCEDURE</th>
                            <th width="70%">RESULTS</th>
                        </tr>
                        @foreach ($procedureResults as $value)
                            <tr>
                                <td><strong>{{ $value->procedure ?? '' }}</strong></td>
                                <td>{!! nl2br(e($value->specimen_note ?? '')) !!}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        @endif

        @php
            $sensitivityResult = $sample->sensitivityResults->first();
            $data = $sensitivityResult ? json_decode($sensitivityResult->sensitivity, true) : [];
        @endphp

        @if ($data)
            <tr>
                <td colspan="4">
                    <hr style="border: 1px solid #3d90ca; margin: 10px 0;">
                </td>
            </tr>
            <tr>
                <td colspan="4" class="heading">SENSITIVITY</td>
            </tr>
            <tr>
                <td colspan="4">
                    <table class="sensitivity-table">
                        @foreach ($data as $i)
                            <tr class="bg-blue">
                                <th width="25%">MICROORGANISM ISOLATED</th>
                                <th width="20%">ANTIBIOTICS</th>
                                <th width="10%">{{ getSensitivityUnitByMicroorganism($i['microorganism']) }}</th>
                                <th width="10%">SENSITIVE</th>
                                <th width="10%">RESISTANT</th>
                                <th width="10%">INTERMEDIATE</th>
                                <th width="15%">RESULT</th>
                            </tr>
                            @foreach ($i['items'] as $item)
                                <tr>
                                    <td>{{  $loop->first ? $i['microorganism'] : '' }}</td>
                                    <td>{{ $item['antibiotic'] }}</td>
                                    <td>{{ $item['mic'] }}</td>
                                    <td>{{ $item['sensitivity'] === 'sensitive' ? 'o' : '' }}</td>
                                    <td>{{ $item['sensitivity'] === 'resistant' ? 'o' : '' }}</td>
                                    <td>{{ $item['sensitivity'] === 'intermediate' ? 'o' : '' }}</td>
                                    <td class="sensitivity-result">{{ strtoupper($item['sensitivity']) }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </table>
                </td>
            </tr>
        @endif

        <tr>
            <td colspan="4">
                <hr style="border: 1px solid #3d90ca; margin: 10px 0;">
            </td>
        </tr>
        @if (isset($sensitivityResult->review))
            <tr>
                <td colspan="4" class="heading">REVIEW AND RECOMMENDATIONS</td>
            </tr>
            <tr>
                <td colspan="4" class="review-section">{!! nl2br(e($sensitivityResult->review ?? '')) !!}</td>
            </tr>
        @endif
        <br><br><br>
        <tr>
            <td colspan="4">
                <strong>Validated by: </strong>
                {{ $validated_by }}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>This material has been reviewed and the report completed and electronically signed by: </strong>
                {{ $signed_by }}
            </td>
        </tr>
    </tbody>
</table>

<script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            if ($PAGE_COUNT > 0) {
                $font = $fontMetrics->get_font("Cambria, serif", "normal");
                $size = 9;
                // Centered text calculation
                $accreditText = "THIS LABORATORY IS ACCREDITED FOR THE TESTS AND PROFILES MARKED *.";
                $directorText = "Lab Director: Dr. Christina Pierre";
                $width = $pdf->get_width();
                $accreditWidth = $fontMetrics->get_text_width($accreditText, $font, $size);
                $docRef = "(DOC-PPA-#13-V1)";
                $docRefWidth = $fontMetrics->get_text_width($docRef, $font, $size);
                $directorWidth = $fontMetrics->get_text_width($directorText, $font, $size);
                $pdf->text(($width - $accreditWidth) / 2, 786, $accreditText, $font, $size);
                // Add document reference below accreditation text
                $pdf->text(($width - $docRefWidth) / 2, 796, $docRef, $font, $size);
                $pdf->line(40, 810, $width - 40, 810, [0, 112/255, 192/255], 0.5);
                $pdf->text(270, 815, "Page $PAGE_NUM of $PAGE_COUNT", $font, $size);
                // Director text below the line and page count
                $pdf->text(($width - $directorWidth) / 2, 828, $directorText, $font, $size);
            }
        ');
    }
</script>
</body>
</html>
