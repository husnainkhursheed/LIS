<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Border Life - LIS</title>


    <style>
        html,
        body {
            margin: 10px;
            padding: 10px;
            font-family: sans-serif;
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
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }

        table thead th {
            height: 2px;
            text-align: left;
            font-size: 14px;
            font-family: sans-serif;
        }

        table,
        th,
        td {
            /* border: 1px solid #ddd; */
            padding: 8px;
            font-size: 14px;
        }

        .heading {
            font-size: 18px;
            margin-top: 12px;
            margin-bottom: 12px;
            font-family: sans-serif;
        }

        .small-heading {
            font-size: 18px;
            font-family: sans-serif;
        }

        .total-heading {
            font-size: 18px;
            font-weight: 700;
            font-family: sans-serif;
        }

        .order-details tbody tr td:nth-child(1) {
            width: 20%;
        }

        .order-details tbody tr td:nth-child(3) {
            width: 20%;
        }

        .order-details h2 {
            margin-top: 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #3d90ca;
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
            margin-bottom: 4px;
            display: inline-block;
            font-family: sans-serif;
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
                <th width="50%" colspan="2" class="text-end company-data">
                    <span><strong>TEL: </strong>(868) 229-8643 or 316-1383</span> <br>
                    <span><strong>Mail: </strong>borderlifemedlab@gmail.com</span> <br>
                </th>
            </tr>
            <tr>
                <th width="50%" colspan="2" style="vertical-align: top; font-size:12px;">
                    <h2>Patient Information</h2>
                    <span style="margin-right:15px; "><strong>Name:</strong>
                        {{ $sample->patient->first_name ?? '' }}</span>
                    <span style="margin-right:15px; "><strong>Sex:</strong> {{ $sample->patient->sex ?? '' }}</span>
                    <span><strong>DOB:</strong>{{ $sample->patient->dob ?? '' }}</span> <br><br>
                    <span><strong>Sample ID:</strong> {{ $sample->test_number ?? '' }}</span>
                </th>
                <th width="50%" colspan="2" class="company-data" style="vertical-align: top; font-size:12px;">
                    <h2>Report Information</h2>
                    <span style="margin-right:50px; "><strong>Lab Ref:</strong>
                        {{ $sample->access_number ?? '' }}</span>
                    <span><strong>Company:</strong> PRIVATE</span>
                    <span><strong>Collection Date:</strong> {{ $sample->collected_date ?? '' }}</span><br>
                    <span><strong>Received Date:</strong> {{ $sample->received_date ?? '' }}</span><br>
                    <span><strong>Report Date:</strong>
                        {{ $sample->created_at ? $sample->created_at->format('Y-m-d') : '' }}</span>
                </th>
            </tr>
            <tr>
                <th colspan="4">
                    @php
                        $testNames = $tests->pluck('name')->implode(', ');
                    @endphp
                    Request: {{ $testNames ?? '' }}
                </th>
            </tr>
            <tr class="bg-blue">
                <th> NAME OF TEST</th>
                <th>RESULTS</th>
                <th>FLAG</th>
                <th>REFERENCE RANGE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tests as $index => $test)
                @php
                    $testReport = $testReports
                        ->where('test_id', $test->id)
                        ->where('sample_id', $sample->id)
                        ->first();
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
                        $referenceRange = ($test->basic_low_value_ref_range ?? '') . '-' . ($test->basic_high_value_ref_range ?? '');
                    } elseif ($test->reference_range == 'optional_ref') {
                        $referenceRange = 'Male: ' . ($test->male_low_value_ref_range ?? '') . '-' . ($test->male_high_value_ref_range ?? '') . '<br>Female: ' . ($test->female_low_value_ref_range ?? '') . '-' . ($test->female_high_value_ref_range ?? '');
                    } elseif ($test->reference_range == 'no_manual_tag') {
                        $referenceRange = ($test->nomanualvalues_ref_range ?? '');
                    }

                @endphp
                    <tr>
                        <td>{{ $description }}</td>
                        <td>{{ $testResults }}</td>
                        <td>
                            <span class="badge badge-pill flag-badge" style="{{ $background }}" data-key="t-hot">{{ $flag }}</span>
                        </td>
                        <td>{!! $referenceRange !!}</td>
                    </tr>
            @endforeach
        </tbody>

    </table>

</body>
</html>
