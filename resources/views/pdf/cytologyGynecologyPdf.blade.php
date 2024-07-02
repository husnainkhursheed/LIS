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
                        {{ $sample->patient->first_name ?? 'null' }}</span>
                    <span style="margin-right:15px; "><strong>Sex:</strong> {{ $sample->patient->sex ?? 'null' }}</span>
                    <span><strong>DOB:</strong>{{ $sample->patient->dob ?? 'null' }}</span> <br><br>
                    <span><strong>Sample ID:</strong> {{ $sample->test_number ?? 'null' }}</span>
                </th>
                <th width="50%" colspan="2" class="company-data" style="vertical-align: top; font-size:12px;">
                    <h2>Report Information</h2>
                    <span style="margin-right:50px; "><strong>Lab Ref:</strong>
                        {{ $sample->access_number ?? 'null' }}</span>
                    <span><strong>Company:</strong> PRIVATE</span>
                    <span><strong>Collection Date:</strong> {{ $sample->collected_date ?? 'null' }}</span><br>
                    <span><strong>Received Date:</strong> {{ $sample->received_date ?? 'null' }}</span><br>
                    <span><strong>Report Date:</strong>
                        {{ $sample->created_at ? $sample->created_at->format('Y-m-d') : 'null' }}</span>
                </th>
            </tr>
            <tr>
                <th colspan="4">
                    @php
                        $testNames = $tests->pluck('name')->implode(', ');
                    @endphp
                    <span style="white-space: nowrap;"><strong>Request: {{ $testNames ?? 'null' }}</strong></span>
                </th>
            </tr>
            <tr class="bg-blue">
                <th width="50%" colspan="4">CLINICAL HISTORY</th>
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
                $cytologyGynecologyResults = $testReport ? $testReport->cytologyGynecologyResults->first() : [];
                // dd($cytologyGynecologyResults);
            @endphp
            <tr>
                <td colspan="2"> <span> <strong> LAST PERIOD:   </strong></span> {{$cytologyGynecologyResults->last_period ?? 'null'}}</td>
                <td colspan="2"> <span> <strong> CONTRACEPTIVE: </strong></span> {{$cytologyGynecologyResults->contraceptive ?? 'null'}}</td>
            </tr>
            <tr>
                <td colspan="2"> <span> <strong> PREVIOUS PAP:  </strong></span> {{$cytologyGynecologyResults->previous_pap ?? 'null'}}</td>
                <td colspan="2"> <span> <strong> RESULT: </strong></span> {{$cytologyGynecologyResults->result ?? 'null'}}</td>
            </tr>
            <tr>
                <td colspan="2"> <span> <strong> CERVIX EXAMINATION:   </strong></span> {{$cytologyGynecologyResults->cervix_examination ?? 'null'}}</td>
                <td colspan="2"> <span> <strong> HISTORY: </strong></span> {{$cytologyGynecologyResults->history ?? 'null'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <thead>
            <tr class="bg-blue" colspan="4">
                <th width="50%" colspan="4">SPECIMEN ADEQUACY:</th>
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
                $cytologyGynecologyResults = $testReport ? $testReport->cytologyGynecologyResults->first() : [];
                // dd($cytologyGynecologyResults);
            @endphp
            <tr>
                <td colspan="4">{{$cytologyGynecologyResults->specimen_adequacy}}</td>
            </tr>
            @endforeach


        </tbody>
    </table>

    <table>
        <thead>
            <tr class="bg-blue" colspan="4">
                <th width="50%" colspan="4">DIAGNOSTIC INTERPRETATION:</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tests as $index => $test)
            @php
                $testReport = $testReports
                    ->where('test_id', $test->id)
                    ->where('sample_id', $sample->id)
                    ->first();

                $cytologyGynecologyResults = $testReport ? $testReport->cytologyGynecologyResults->first() : [];

            @endphp
            <tr>
                <td colspan="4">{{$cytologyGynecologyResults->diagnostic_interpretation}}</td>
            </tr>
            @endforeach


        </tbody>
    </table>

    <table>
        <thead>
            <tr class="bg-blue" colspan="4">
                <th width="50%" colspan="4">RECOMMENDATION:</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tests as $index => $test)
            @php
                $testReport = $testReports
                    ->where('test_id', $test->id)
                    ->where('sample_id', $sample->id)
                    ->first();

                $cytologyGynecologyResults = $testReport ? $testReport->cytologyGynecologyResults->first() : [];

            @endphp
            <tr>
                <td colspan="4">{{$cytologyGynecologyResults->recommend}}</td>
            </tr>
            @endforeach


        </tbody>
    </table>


</body>
</html>
