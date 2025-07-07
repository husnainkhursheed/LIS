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
            padding: 8px;
            font-size: 14px;
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
        <th width="50%" colspan="2">
            <img src="{{ public_path('build/images/logo-lis.png') }}" alt="Logo">
            <p><strong>71 Eastern Main Road Barataria, San Juan Trinidad and Tobago</strong></p>
        </th>
        <th width="50%" colspan="2" class="text-end company-data">
            <img height="50" src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code"><br><br>
            <span><strong>TEL: </strong>(868) 229-8643 or 316-1383</span> <br>
            <span><strong>Mail: </strong>borderlifemedlab@gmail.com</span> <br>
        </th>
    </tr>
    <tr>
        <th width="40%" style="vertical-align: top;">
            <h2>Patient Information</h2><br>
            <span><strong>Name:</strong>
                {{ $sample->patient->first_name ?? '' }} {{ $sample->patient->surname ?? '' }}
            </span><br><br>
            <span><strong>Sex:</strong> {{ $sample->patient->sex ?? '' }}</span>
            <span><strong> &nbsp;&nbsp;&nbsp;&nbsp; DOB:</strong>{{ $sample->patient->dob ?? '' }}</span> <br><br>
        </th>
        <th width="60%" colspan="3" class="company-data" style="vertical-align: top;">
            <h2>Report Information</h2>
            <table>
                <tr>
                    <td><strong>Collection Date:</strong> {{ \Carbon\Carbon::parse($sample->collected_date)->format('d-M-Y') }}</td>
                    <td><strong>Lab Ref:</strong> {{ $sample->access_number ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Received Date:</strong> {{ \Carbon\Carbon::parse($sample->received_date)->format('d-M-Y') }}</td>
                    <td><strong>Company:</strong> PRIVATE</td>
                </tr>
                <tr>
                    <td><strong>Report Date:</strong> {{ \Carbon\Carbon::parse($sample->created_at)->format('d-M-Y') }}</td>
                    <td><strong>Sample ID:</strong> {{ $sample->test_number ?? '' }}</td>
                </tr>
            </table>
        </th>
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
            </tr>
            @foreach ($profileData['tests'] as $index => $test)
                @php
                    $testReport = $testReports
                        ->where('test_id', $test->id)
                        ->where('sample_id', $sample->id)
                        ->first();
                    // $biochemHaemoResults = $testReport ? $testReport->biochemHaemoResults->first() : [];
                    $biochemHaemoResults = $testReport ? $testReport->biochemHaemoResults->first() : [];
                    $description = $biochemHaemoResults->description ?? '';
                    $testResults = $biochemHaemoResults->test_results ?? '';
                    $flag = $biochemHaemoResults->flag ?? '';
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
                        $referenceRange = ($test->basic_low_value_ref_range ?? '') . '-' . ($test->basic_high_value_ref_range ?? '') . ' ' . ($test->basic_unit_value_ref_range ?? '');
                    } elseif ($test->reference_range == 'optional_ref') {
                        $referenceRange = 'Male: ' . ($test->male_low_value_ref_range ?? '') . '-' . ($test->male_high_value_ref_range ?? '') . ' '. ($test->male_unit_value_ref_range ?? '') . '<br>Female: ' . ($test->female_low_value_ref_range ?? '') . '-' . ($test->female_high_value_ref_range ?? ''). ' '. ($test->female_unit_value_ref_range ?? '');
                    } elseif ($test->reference_range == 'no_manual_tag') {
                        $referenceRange = ($test->nomanualvalues_ref_range ?? '');
                    }
                @endphp

                @if ($testResults)
                    <tr>
                        <td>{{ $description }}</td>
                        <td >{{ $testResults }}</td>
                        <td>
                            <span class="badge badge-pill flag-badge" style="{{ $background }}" data-key="t-hot">{{ $flag }}</span>
                        </td>
                        <td style="text-align: center">{!! $referenceRange !!}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
</table>

<script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            if ($PAGE_COUNT > 0) {
                $font = $fontMetrics->get_font("Cambria, serif", "normal");
                $size = 9;
                $pdf->text(45, 786, "Signed by: {{$signed_by}}", $font, $size);
                $pdf->text(448, 786, "Validated by: {{$validated_by}}", $font, $size);
                $pdf->text(30, 795, "__________________________________________________________________________________________________________", $font, $size,array(61/255, 144/255, 202/255));
                $pdf->text(270, 815, "Page $PAGE_NUM of $PAGE_COUNT", $font, $size);
            }
        ');
    }
</script>

</body>
</html>
