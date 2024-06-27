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

        .main-table td img {
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

        table,
        th,
        td {
            border: 1px solid #3d90ca;
        }

        .table-bordered thead {
            background-color: #3d90ca;
            color: #fff;
        }

        thead th {
            font-size: 14px
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            font-size: 12px;

        }

        .table-bordered td {
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
                <p><strong>71 Eastern Main Road Barataria, San Juan Trinidad and Tobago</strong></p>
            </td>
            <td style="width: 35%; vertical-align: middle;">
                <p><strong>TEL: </strong> (868) 229-8643 or 316-1383</p>
                <p><strong>Mail: </strong> borderlifemedlab@gmail.com</p>


            </td>

        </tr>
    </table>

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
                    <p><strong>Report Date:</strong>
                        {{ $sample->created_at ? $sample->created_at->format('Y-m-d') : 'null' }}</p>
                </th>
            </tr>
        </thead>
    </table>
    {{-- <table class="info-table">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <p><strong>Request: </strong><br>
                    @php
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
                <p><strong>Specimen: </strong><br> Serum (Urine for CT/NG)</p>
            </td>
        </tr>
    </table> --}}

    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="text-align: center; background:white; color:black">CHEMICAL ANALYSIS</th>
                <th style="text-align: center; background:white; color:black">MICROSCOPY </th>
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
                    $urinalysisMicrobiologyResults = $testReport
                        ? $testReport->urinalysisMicrobiologyResults->first()
                        : [];
                    // dd($urinalysisMicrobiologyResults);
                @endphp
                <tr>
                    <td>
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>Tests</th>
                                    <th>Results</th>
                                    {{-- <th>Normal Range</th> --}}
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
                                        $urinalysisMicrobiologyResults = $testReport
                                            ? $testReport->urinalysisMicrobiologyResults->first()
                                            : [];
                                        // dd($urinalysisMicrobiologyResults);
                                    @endphp
                                    <tr>
                                        <td><strong>S. Gravity: </strong> </td>
                                        <td> {{ $urinalysisMicrobiologyResults->s_gravity }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Leucocytes: </strong> </td>
                                        <td> {{ $urinalysisMicrobiologyResults->leucocytes }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nitrite: </strong> </td>
                                        <td> {{ $urinalysisMicrobiologyResults->nitrite }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Glucose: </strong> </td>
                                        <td> {{ $urinalysisMicrobiologyResults->glucose }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ketones: </strong> </td>
                                        <td> {{ $urinalysisMicrobiologyResults->ketones }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Proteins: </strong> </td>
                                        <td> {{ $urinalysisMicrobiologyResults->proteins }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Urobilinogen: </strong> </td>
                                        <td> {{ $urinalysisMicrobiologyResults->urobilinogen }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Bilirubin: </strong> </td>
                                        <td> {{ $urinalysisMicrobiologyResults->bilirubin }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Blood: </strong> </td>
                                        <td> {{ $urinalysisMicrobiologyResults->blood }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Colour: </strong> </td>
                                        <td> {{ $urinalysisMicrobiologyResults->colour }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Appearance: </strong> </td>
                                        <td> {{ $urinalysisMicrobiologyResults->appearance }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tests</th>
                                    <th>Results</th>
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
                                        $urinalysisMicrobiologyResults = $testReport
                                            ? $testReport->urinalysisMicrobiologyResults->first()
                                            : [];
                                        // dd($urinalysisMicrobiologyResults);
                                    @endphp
                                  <tr>
                                    <td><strong>Epith. Cells: </strong> </td>
                                    <td> {{ $urinalysisMicrobiologyResults->epith_cells }}</td>
                                </tr>
                                <tr>
                                    <td><strong>White Cells: </strong> </td>
                                    <td> {{ $urinalysisMicrobiologyResults->white_cells }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Red Cells:</strong> </td>
                                    <td> {{ $urinalysisMicrobiologyResults->red_cells }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Casts:</strong> </td>
                                    <td> {{ $urinalysisMicrobiologyResults->casts }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Crystals: </strong> </td>
                                    <td> {{ $urinalysisMicrobiologyResults->crystals }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bacteria:</strong> </td>
                                    <td> {{ $urinalysisMicrobiologyResults->bacteria }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Yeast:</strong> </td>
                                    <td> {{ $urinalysisMicrobiologyResults->yeast }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Trichomonas: </strong> </td>
                                    <td> {{ $urinalysisMicrobiologyResults->trichomonas }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
