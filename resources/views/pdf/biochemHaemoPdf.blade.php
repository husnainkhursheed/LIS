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
        th, td {
            padding: 4px;
            font-size: 14px;
            line-height: 1.1;
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
    </style>
</head>
<body>

<table class="order-details">
    <thead>
        <tr>
            <th width="50%" colspan="2" style="vertical-align: top;">
                <img src="{{ public_path('build/images/logo-lis.png') }}" alt="Logo" style="height: 70px;"><br>
                <span style="display: block; font-weight:normal;, font-size: 15px; margin-top: 1px;"><small>ISO:15189 Accredited</small></span>
                <p style="margin-top: 8px;"><small>71 Eastern Main Road Barataria, San Juan Trinidad and Tobago</small></p>
            </th>
            <th width="50%" colspan="2" class="text-end company-data">
                <img height="50" src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code"><br><br>
                <span style="display: inline-block; text-align: left; width: 100%;"><strong>TEL: </strong>(868) 229-8643 or 316-1383</span><br>
                <span style="display: inline-block; text-align: left; width: 100%;"><strong>Mail: </strong>borderlifemedlab@gmail.com</span><br>
            </th>
        </tr>
        <tr>
            <th width="45%" style="vertical-align: top;">
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
                {{-- <span style="font-weight: normal"><strong>Name:</strong>
                    {{ $sample->patient->first_name ?? '' }} {{ $sample->patient->surname ?? '' }}
                </span><span style="font-weight: normal"><strong>Sex:</strong> {{ $sample->patient->sex ?? '' }}</span><br><br>

                <span style="font-weight: normal"><strong> DOB:</strong> {{ \Carbon\Carbon::parse($sample->patient->dob)->format('d-M-Y') }}</span>@php
                    $dob = \Carbon\Carbon::parse($sample->patient->dob);
                    $age = $dob->age;
                @endphp
                <span style="font-weight: normal">
                <strong>Age:</strong> {{ $age }} yrs</span> <br><br>
                <span style="font-weight: normal">
                <strong>Ordering Dr:</strong> {{ $sample->doctor->name }}</span> <br><br>
                <span style="font-weight: normal">
                <strong>Institution:</strong> {{ $sample->institution->name }}</span> <br> --}}

            </th>
            <th width="60%" colspan="3" class="company-data" style="vertical-align: top;">
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

            <span style="white-space: nowrap;"><strong>Request: {{ $sampleprofiles  . ', ' . $individualtests  }}</strong></span>
            </th>
        </tr>
        <tr>
            @php
                $hematologyStatus = $sample->departmentStatus('1')
            @endphp
            <td colspan="4" >
                <span style="white-space: nowrap;"><strong>Comments: </strong>{{$hematologyStatus->note ?? ''}} </span>
            </td>
        </tr>


        <tr class="bg-blue">
            <th>NAME OF TEST</th>
            <th>RESULTS</th>
            <th>FLAG</th>
            <th>REFERENCE RANGE</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categorizedTests as $profileId => $profileData)
            <tr id="{{ $profileId }}">
                <td colspan="4"><strong>{{ $profileData['name'] }}</strong></td>
                {{-- <small>{{ $profileData['test_note'] }}</small> --}}
            </tr>
            @foreach ($profileData['tests'] as $index => $test)
                @php
                    $testReport = $testReports
                        ->where('test_id', $test->id)
                        ->where('sample_id', $sample->id)
                        ->first();
                    // $biochemHaemoResults = $testReport ? $testReport->biochemHaemoResults->first() : [];
                    $biochemHaemoResults = $testReport ? $testReport->biochemHaemoResults->first() : [];
                    $description = $biochemHaemoResults->description ?? $test->name;
                    $testResults = $biochemHaemoResults->test_results ?? '';
                    $testNote = $biochemHaemoResults->test_notes ?? '';
                    $methodology = $test->methodology ?? '';
                    $flag = $biochemHaemoResults->flag ?? '';
                    $background = '';

                    if ($flag == 'Normal') {
                        $background = 'color:#40bb82';
                    } elseif ($flag == 'High') {
                        $background = 'color:red';
                    } elseif ($flag == 'Low') {
                        $background = 'color:red';
                    }

                    $referenceRange = '';

                    if ($test->reference_range == 'basic_ref') {
                        $referenceRange = ($test->basic_low_value_ref_range ?? '') . '-' . ($test->basic_high_value_ref_range ?? '') . ' ' . ($test->basic_unit_value_ref_range ?? '');
                    } elseif ($test->reference_range == 'optional_ref') {
                        $referenceRange = 'Male: ' . ($test->male_low_value_ref_range ?? '') . '-' . ($test->male_high_value_ref_range ?? '') . ' '. ($test->male_unit_value_ref_range ?? '') . '<br>Female: ' . ($test->female_low_value_ref_range ?? '') . '-' . ($test->female_high_value_ref_range ?? ''). ' '. ($test->female_unit_value_ref_range ?? '');
                    } elseif ($test->reference_range == 'no_manual_tag') {
                        $referenceRange = ($test->nomanualvalues_ref_range ?? '');
                    }
                @endphp

                {{-- @if ($testResults) --}}
                    <tr>
                        <td>{{ $description }}
                            @if ($testNote)
                                <br><small>({{ $testNote }})</small>
                            @endif
                        </td>

                        <td >{{ $testResults }}</td>
                        <td>
                            <span class="badge badge-pill flag-badge" style="{{ $background }}" data-key="t-hot">{{ $flag }}</span>
                        </td>
                        <td style="text-align: left">{!! $referenceRange !!}
                            @if ($methodology)
                                <br><small>({{ $methodology }})</small>
                            @endif
                        </td>

                    </tr>
                {{-- @endif --}}
            @endforeach
            <tr>
                <td colspan="4">
                    <hr style="border: 0.5px solid #caced1; margin: 10px 0;">
                </td>
            </tr>

        @endforeach
        <br><br><br>
        <tr>
            <td>
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
                $directorWidth = $fontMetrics->get_text_width($directorText, $font, $size);
                $pdf->text(($width - $accreditWidth) / 2, 786, $accreditText, $font, $size);
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
