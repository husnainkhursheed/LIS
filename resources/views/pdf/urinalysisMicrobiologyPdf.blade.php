<!DOCTYPE html>
<html>
<head>
    <title>Border Life - LIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
        }
        .sub-header {
            color: #3d90ca;
            text-align: center;
            margin-bottom: 20px;
        }
        .sub-header img {
            width: 250px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 10px;
            border: 1px solid #3d90ca;
        }
        .info-table h2 {
            margin-top: 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #3d90ca;
            padding-bottom: 5px;
        }
        .info-table p {
            font-size: 13px;
            font-weight: normal;
            margin: 5px 0;
            letter-spacing: 0.7px;
        }


        .main-table {
            width: 100%;
            margin-bottom: 20px;
            border: 0px;
        }
        .main-table td img{
            width: 230px;
        }
        .main-table td {
            /* padding: 10px; */
            border: 0px;
        }
        .main-table h3 {
            margin-top: 0;
            /* margin-bottom: 10px; */
            padding-bottom: 5px;
        }
        .main-table p {
            font-size: 13px;
            margin: 5px 0;
        }



        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #3d90ca;
        }
        .table-bordered thead {
            background-color: #3d90ca;
            color: #fff;
        }
        thead th {
            font-size: 14px
        }
        th, td {
            padding: 8px;
            text-align: left;
            font-size: 12px;

        }
        .table-bordered td{
            font-size: 14px;
            /* font-weight: 100; */
            line-height: 25px;
        }
    </style>
</head>
<body>
    <table class="main-table">

        <tr>
            <td style="width: 65%; vertical-align: top;">
                <img src="{{ public_path('build/images/logo-lis.png') }}" alt="Logo" class="logo">
            <p><strong>71 Eastern Main Road   Barataria, San Juan  Trinidad and Tobago</strong></p>
            </td>
            <td style="width: 35%; vertical-align: middle;">
                <p><strong>TEL: </strong>   (868) 229-8643  or 316-1383</p>
                <p><strong>Mail: </strong> borderlifemedlab@gmail.com</p>


            </td>

        </tr>
    </table>
    {{-- <div class="content">
        <h1>{{ $title }}</h1>
        <p><strong>Date:</strong> {{ $date }}</p>

        <div class="section">
            <h2>Patient Information</h2>
            <p class="field"><strong>Name:</strong> {{ $sample->patient->first_name ?? 'null' }} {{ $sample->patient->surname ?? 'null' }}</p>
            <p class="field"><strong>Contact Number:</strong> {{ $sample->patient->contact_number ?? 'null' }}</p>
            <p class="field"><strong>DOB:</strong> {{ $sample->patient->dob ?? 'null' }}</p>
            <p class="field"><strong>Sex:</strong> {{ $sample->patient->sex ?? 'null' }}</p>
        </div>

        <div class="section">
            <h2>Sample Information</h2>
            <p class="field"><strong>Sample ID:</strong> {{ $sample->test_number ?? 'null' }}</p>
            <p class="field"><strong>Access Number:</strong> {{ $sample->access_number ?? 'null' }}</p>
            <p class="field"><strong>Collected Date:</strong> {{ $sample->collected_date ?? 'null' }}</p>
            <p class="field"><strong>Received Date:</strong> {{ $sample->received_date ?? 'null' }}</p>
            <p class="field"><strong>Received Time:</strong> {{ $sample->received_time ?? 'null' }}</p>
        </div>

        <div class="section">
            <h2>Institution Information</h2>
            <p class="field"><strong>Institution Name:</strong> {{ $sample->institution->name ?? 'null' }}</p>
            <p class="field"><strong>Contact Number:</strong> {{ $sample->institution->contact_number ?? 'null' }}</p>
            <p class="field"><strong>Address:</strong> {{ $sample->institution->street_name ?? 'null' }}, {{ $sample->institution->address_line_2 ?? 'null' }}, {{ $sample->institution->area ?? 'null' }}</p>
            <p class="field"><strong>Email:</strong> {{ $sample->institution->email ?? 'null' }}</p>
        </div>

        <div class="section">
            <h2>Doctor Information</h2>
            <p class="field"><strong>Doctor Name:</strong> {{ $sample->doctor->name ?? 'null' }}</p>
            <p class="field"><strong>Contact Number:</strong> {{ $sample->doctor->contact_number ?? 'null' }}</p>
            <p class="field"><strong>Address:</strong> {{ $sample->doctor->street_name ?? 'null' }}, {{ $sample->doctor->address_line_2 ?? 'null' }}, {{ $sample->doctor->area ?? 'null' }}</p>
            <p class="field"><strong>Email:</strong> {{ $sample->doctor->email ?? 'null' }}</p>
        </div>

        <div class="section">
            <h2>Tests Information</h2>
            @foreach($sample->tests as $test)
                <p class="field"><strong>Test Name:</strong> {{ $test->name }}</p>
                <p class="field"><strong>Department:</strong> {{ $test->department }}</p>
                <p class="field"><strong>Specimen Type:</strong> {{ $test->specimen_type }}</p>
                <p class="field"><strong>Cost:</strong> {{ $test->cost }}</p>
                <p class="field"><strong>Reference Range:</strong> {{ $test->reference_range }}</p>
                <p class="field"><strong>Basic Low Value Ref Range:</strong> {{ $test->basic_low_value_ref_range }}</p>
                <p class="field"><strong>Basic High Value Ref Range:</strong> {{ $test->basic_high_value_ref_range }}</p>
                <p class="field"><strong>Male Low Value Ref Range:</strong> {{ $test->male_low_value_ref_range ?? 'null' }}</p>
                <p class="field"><strong>Male High Value Ref Range:</strong> {{ $test->male_high_value_ref_range ?? 'null' }}</p>
                <p class="field"><strong>Female Low Value Ref Range:</strong> {{ $test->female_low_value_ref_range ?? 'null' }}</p>
                <p class="field"><strong>Female High Value Ref Range:</strong> {{ $test->female_high_value_ref_range ?? 'null' }}</p>
                <hr>
            @endforeach
        </div>

        <div class="section">
            <h2>Test Reports Information</h2>
            @foreach($sample->testReports as $report)
                <p class="field"><strong>Results:</strong> {{ $report->results ?? 'null' }}</p>
                <p class="field"><strong>Notes:</strong> {{ $report->notes ?? 'null' }}</p>
                <p class="field"><strong>Is Completed:</strong> {{ $report->is_completed ? 'Yes' : 'No' }}</p>
                <p class="field"><strong>Is Signed:</strong> {{ $report->is_signed ? 'Yes' : 'No' }}</p>
                <hr>
            @endforeach
        </div>

        <div class="section">
            <h2>Signed By</h2>
            <p class="field"><strong>Signed By:</strong> {{ $sample->signedBy->first_name ?? 'null' }} {{ $sample->signedBy->surname ?? 'null' }}</p>
            <p class="field"><strong>Signed At:</strong> {{ $sample->signed_at ?? 'null' }}</p>
        </div>
    </div> --}}
    <table class="info-table">
        <thead>
        <tr>
            <th style="width: 50%; vertical-align: top;">
                <h2>Patient Information</h2>
                <p><strong>Name:</strong> {{ $sample->patient->first_name ?? 'null' }}</p>
                {{-- <p><strong>Age:</strong> 35</p> --}}
                <p><strong>Sex:</strong> {{ $sample->patient->sex ?? 'null' }}</p>
                <p><strong>DOB:</strong>{{ $sample->patient->dob ?? 'null' }}</p>
                <p><strong>Sample ID:</strong> {{ $sample->test_number ?? 'null' }}</p>
            </th>
            <th style="width: 50%; vertical-align: top;">
                <h2>Sample Collected At</h2>
                <p><strong>Tobago, 71 Eastern Main Road Barataria</strong></p><br><br>
                <p><strong>Ref. By:</strong> DR Yousaf</p>

            </th>
            <th style="width: 50%; vertical-align: top;">
                <h2>Report Information</h2>
                <p><strong>Lab Ref:</strong> {{ $sample->access_number ?? 'null' }}</p>
                <p><strong>Company:</strong> PRIVATE</p>
                <p><strong>Collection Date:</strong> {{ $sample->collected_date ?? 'null' }}</p>
                <p><strong>Received Date:</strong> {{ $sample->received_date ?? 'null' }}</p>
                <p><strong>Report Date:</strong> {{ $sample->created_at ? $sample->created_at->format('Y-m-d') : 'null' }}</p>
            </th>
        </tr>
    </thead>
    </table>
    <table class="info-table">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <p><strong>Request: </strong><br>
                    @php
                    // Assuming $sample->tests is a collection or array of test objects
                    $testNames = $tests->pluck('name')->implode(', ');
                     @endphp
                {{ $testNames }}
                </p>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <p><strong>Comments: </strong><br> SPECIMEN:
                    SATISFACTORY</p>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <p><strong>Specimen: </strong><br>  Serum (Urine for CT/NG)</p>
            </td>
        </tr>
    </table>
    {{-- <div style=" border-bottom: 1px solid #3d90ca;">
        <p><b>Whole Blood </b></p>
    </div> --}}
    <table class="table table-bordered">
        <thead>
            <tr>
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
                // dd($testReport);
                $urinalysisMicrobiologyResults = $testReport ? $testReport->urinalysisMicrobiologyResults->first() : [];
                // dd($urinalysisMicrobiologyResults);
            @endphp
            <tr>
                <td> {{$urinalysisMicrobiologyResults->description}}</td>
                <td style="text-align:center;">{{$urinalysisMicrobiologyResults->test_results}}</td>
                {{-- <td style="color: blue; text-align:center;">{{$urinalysisMicrobiologyResults->flag}}</td> --}}
                @php
                $background = '';
                if (!empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->flag == 'Normal') {
                    $background = 'color:#40bb82';
                } elseif (
                    !empty($urinalysisMicrobiologyResults) &&
                    $urinalysisMicrobiologyResults->flag == 'High'
                ) {
                    $background = 'color:red';
                } elseif (!empty($urinalysisMicrobiologyResults) && $urinalysisMicrobiologyResults->flag == 'Low') {
                    $background = 'color:##ffca5b';
                }
            @endphp
            <td>
            <span class="badge badge-pill flag-badge" style="{{ $background }}"
                data-key="t-hot">{{ $urinalysisMicrobiologyResults->flag ?? 'Normal' }}</span>
            </td>
                <td>  @if ($test->reference_range == 'basic_ref')
                    {{ $test->basic_low_value_ref_range . '-' . $test->basic_high_value_ref_range }}
                @else
                    Male:
                    {{ $test->male_low_value_ref_range . '-' . $test->male_high_value_ref_range }}
                    <br>
                    Female:
                    {{ $test->female_low_value_ref_range . '-' . $test->female_high_value_ref_range }}
                @endif</td>
            </tr>
            @endforeach
            {{-- <tr>
                <td> Lymphocytes #</td>
                <td style="text-align:center;"> 2.1 </td>
                <td></td>
                <td>0.8 - 4.9 10^3/ul</td>
            </tr>
            <tr>
                <td> MID #</td>
                <td style="text-align:center;">1.5</td>
                <td></td>
                <td> 0.3 - 2.4 10^3/uL</td>
            </tr>
            <tr>
                <td> Granulocytes #</td>
                <td style="text-align:center;">5.5</td>
                <td></td>
                <td> 1.4 - 6.5 10^3/ul</td>
            </tr>
            <tr>
                <td> Lymphocytes %</td>
                <td style="text-align:center;">10%</td>
                <td style="color: blue; text-align:center;">Low</td>
                <td> 20 - 45 %</td>
            </tr>
            <tr>
                <td> MID %</td>
                <td style="text-align:center;">15.2%</td>
                <td style="color: red; text-align:center;">High</td>
                <td> 2.0 - 13.3 %</td>
            </tr>
            <tr>
                <td> RBC (Red Blood Cell)</td>
                <td style="text-align:center;">5.5</td>
                <td></td>
                <td>  Male: 4.5 - 6.5 10^6/ul <br>
                    Female: 3.9 - 5.6 10^6/ul
                </td>
            </tr>
            <tr>
                <td>  HGB (Hemoglobin)</td>
                <td style="text-align:center;">16</td>
                <td></td>
                <td>  Males 13 - 18 g/dL <br>
                    Females 11.6 - 16.5 g/dL
                </td>
            </tr>
            <tr>
                <td>HCT (Hematocrit)</td>
                <td style="text-align:center;">30%</td>
                <td></td>
                <td>  Males 40 - 54% <br>
                    Females 36 - 47%
                </td>
            </tr> --}}

        </tbody>
    </table>
</body>
</html>



{{-- <table>
    <thead>
        <tr>
            <th>Test</th>
            <th>Result</th>
            <th>Reference Range</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Hemoglobin</td>
            <td>13.5 g/dL</td>
            <td>13.0 - 17.0 g/dL</td>
        </tr>
        <tr>
            <td>White Blood Cells</td>
            <td>6,000 /µL</td>
            <td>4,000 - 11,000 /µL</td>
        </tr>
        <tr>
            <td>Platelets</td>
            <td>250,000 /µL</td>
            <td>150,000 - 450,000 /µL</td>
        </tr>
        <tr>
            <td>Red Blood Cells</td>
            <td>4.5 million/µL</td>
            <td>4.0 - 5.5 million/µL</td>
        </tr>
    </tbody>
</table>

<div class="footer">
    <p>Border Life - LIS</p>
</div> --}}
    {{-- <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        thead {
            background-color: #f2f2f2;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

    <h1>{{ $title }}</h1>
    <p>{{ $date }}</p>
    <br/>
    <br/>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->surname }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html> --}}
