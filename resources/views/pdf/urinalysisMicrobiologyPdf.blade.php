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
            margin-bottom: 20px;
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
            border-bottom: 1px solid #3d90ca;
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
                    <img src="{{ public_path('build/images/logo-lis.png') }}" alt="Logo" class="logo">
                    <p><strong>71 Eastern Main Road Barataria, San Juan Trinidad and Tobago</strong></p>
                    {{-- <h2 class="text-start">Funda Ecommerce</h2> --}}
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
                            <td><strong>Collection Date:</strong> {{ \Carbon\Carbon::parse($sample->collected_date)->format('d/m/Y') }}</td>
                            <td><strong>Lab Ref:</strong> {{ $sample->access_number ?? '' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Received Date:</strong> {{ \Carbon\Carbon::parse($sample->received_date)->format('d/m/Y') }}</td>
                            <td><strong>Company:</strong> PRIVATE</td>
                        </tr>
                        <tr>
                            <td><strong>Report Date:</strong> {{ \Carbon\Carbon::parse($sample->created_at)->format('d/m/Y') }}</td>
                            <td><strong>Sample ID:</strong> {{ $sample->test_number ?? '' }}</td>
                        </tr>
                    </table>
                </th>
                {{-- <th width="50%" colspan="3" class="company-data" style="vertical-align: top; font-size:12px;">
                    <h2>Report Information</h2>
                    <span style="margin-right:50px; "><strong>Lab Ref:</strong>
                        {{ $sample->access_number ?? '' }}</span>
                    <span><strong>Company:</strong> PRIVATE</span>
                    <span><strong>Collection Date:</strong> {{ $sample->collected_date ?? '' }}</span><br>
                    <span><strong>Received Date:</strong> {{ $sample->received_date ?? '' }}</span><br>
                    <span><strong>Report Date:</strong>
                        {{ $sample->created_at ? $sample->created_at->format('Y-m-d') : '' }}</span>
                </th> --}}
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


    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                if ($PAGE_COUNT ) {
                    $font = $fontMetrics->get_font("Cambria, serif", "normal");
                    $size = 10;
                    $pdf->text(45, 810, "Signed by: Dr. John Doe", $font, $size);
                    $pdf->text(245, 810, "Page $PAGE_NUM of $PAGE_COUNT", $font, $size);
                    $pdf->text(435, 810, "Validated by: Admin User", $font, $size);
                }
            ');
        }
    </script>


</body>
</html>
