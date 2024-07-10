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
        tr.microorganism-row {
        page-break-inside: avoid;
    }
    .page-break {
            page-break-before: always;
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
                    <span style="white-space: nowrap;"><strong>Request: {{ $testNames ?? '' }}</strong></span>
                </th>
            </tr>

            <tr class="bg-blue">
                <th width="50%" colspan="2">CHEMICAL ANALYSIS</th>
                <th width="50%" colspan="2">MICROSCOPY</th>
            </tr>
            <tr class="">
                <th width="">Tests
                </th>
                <th width="">
                    Results</th>
                <th width="">Tests
                </th>
                <th width="">
                    Results</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tests as $index => $test)
                @php
                    $testReport = $testReports
                        ->where('test_id', $test->id)
                        ->where('sample_id', $sample->id)
                        ->first();
                    $urinalysisMicrobiologyResults = $testReport
                        ? $testReport->urinalysisMicrobiologyResults->first()
                        : [];
                @endphp
                <tr>
                    <td><strong>S. Gravity: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->s_gravity ?? '' }}</td>
                    {{-- MICROSCOPY --}}
                    <td><strong>Epith. Cells: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->epith_cells ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Leucocytes: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->leucocytes ?? '' }}</td>
                    {{-- MICROSCOPY --}}
                    <td><strong>White Cells: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->white_cells ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Nitrite: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->nitrite ?? '' }}</td>
                    {{-- MICROSCOPY --}}
                    <td><strong>Red Cells:</strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->red_cells ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Glucose: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->glucose ?? '' }}</td>
                    {{-- MICROSCOPY --}}
                    <td><strong>Casts:</strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->casts ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Ketones: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->ketones ?? '' }}</td>
                    {{-- MICROSCOPY --}}
                    <td><strong>Crystals: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->crystals ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Proteins: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->proteins ?? '' }}</td>
                    {{-- MICROSCOPY --}}
                    <td><strong>Bacteria:</strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->bacteria ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Urobilinogen: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->urobilinogen ?? '' }}</td>
                    {{-- MICROSCOPY --}}
                    <td><strong>Yeast:</strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->yeast ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Bilirubin: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->bilirubin ?? '' }}</td>
                    {{-- MICROSCOPY --}}
                    <td><strong>Trichomonas: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->trichomonas ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Blood: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->blood ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Colour: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->colour ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Appearance: </strong> </td>
                    <td> {{ $urinalysisMicrobiologyResults->appearance ?? '' }}</td>
                </tr>

        </tbody>
    </table>

    <table>
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
            <tr>
                <td width="10%"><strong>{{ $urinalysisMicrobiologyResults->procedure ?? '' }}:</strong></td>
                <td>
                    {{ $urinalysisMicrobiologyResults->specimen_note ?? '' }}
                </td>

            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>
    <table >
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
            @php
              $data = json_decode($urinalysisMicrobiologyResults->sensitivity ?? '[]', true);
            @endphp
            @foreach ($data as $i )
                @foreach ($i['items'] as $index => $item )

                <tr style="{{ $index === 0 ? '' : '' }}">
                    <td>{{ $index === 0 ? $i['microorganism'] : '' }}</td>
                    <td>{{ $item['antibiotic'] }}</td>
                    <td class="text-center">{{ $item['mic'] }}</td>
                    <td class="text-center"><input type="radio" name="{{ $i['microorganism'] }}-{{ $item['antibiotic'] }}-sensitivity" {{ $item['sensitivity'] === 'sensitive' ? 'checked' : '' }}></td>
                    <td class="text-center"><input type="radio" name="{{ $i['microorganism'] }}-{{ $item['antibiotic'] }}-sensitivity" {{ $item['sensitivity'] === 'resistant' ? 'checked' : '' }}></td>
                    <td class="text-center"><input type="radio" name="{{ $i['microorganism'] }}-{{ $item['antibiotic'] }}-sensitivity" {{ $item['sensitivity'] === 'intermediate' ? 'checked' : '' }}></td>
                </tr>
                @endforeach

            @endforeach

            @endforeach
        </tbody>
    </table>







</body>
</html>
