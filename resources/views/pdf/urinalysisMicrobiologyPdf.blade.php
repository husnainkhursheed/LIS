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

        th,
        td {
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
                <th width="50%" colspan="4" class="text-end company-data">
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
                    <span><strong> &nbsp;&nbsp;&nbsp;&nbsp; DOB:</strong>{{ $sample->patient->dob ?? '' }}</span>
                    <br><br>
                </th>
                <th width="60%" colspan="5" class="company-data" style="vertical-align: top;">
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
                <th colspan="3">
                    @php
                        $testNames = $tests->pluck('name')->implode(', ');
                    @endphp
                    <span style="white-space: nowrap;"><strong>Request: {{ $testNames ?? '' }}</strong></span>
                </th>
            </tr>

            <tr class="bg-blue">
                <th width="50%" colspan="3">CHEMICAL ANALYSIS</th>
                <th width="50%" colspan="3">MICROSCOPY</th>
            </tr>
            <tr class="">
                <th width="">Tests
                </th>
                <th width="">
                    Results</th>
                <th width="">
                    Normal Range</th>
                <th width="">Tests
                </th>
                <th width="">
                    Results</th>
                <th width="">
                    Normal Range</th>
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

                    $procedureResults = $urinalysisMicrobiologyResults
                        ? $urinalysisMicrobiologyResults->procedureResults
                        : [];

                    // dd($urinalysisMicrobiologyResults);

                @endphp
                <tr>
                    @if ($urinalysisMicrobiologyResults->s_gravity)
                        <td><strong>S. Gravity: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->s_gravity ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['s_gravity']))
                                {{-- {{ $referenceRanges['s_gravity']->low . '-' . $referenceRanges['s_gravity']->high}} --}}
                                @if ($referenceRanges['s_gravity']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['s_gravity']->low . '-' . $referenceRanges['s_gravity']->high }}
                                @elseif ($referenceRanges['s_gravity']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['s_gravity']->male_low . '-' . $referenceRanges['s_gravity']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['s_gravity']->female_low . '-' . $referenceRanges['s_gravity']->female_high }}
                                @elseif ($referenceRanges['s_gravity']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['s_gravity']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                    {{-- MICROSCOPY --}}
                    @if ($urinalysisMicrobiologyResults->epith_cells)
                        <td><strong>Epith. Cells: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->epith_cells ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['epith_cells']))
                                {{-- {{ $referenceRanges['epith_cells']->low . '-' . $referenceRanges['epith_cells']->high}} --}}
                                @if ($referenceRanges['epith_cells']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['epith_cells']->low . '-' . $referenceRanges['epith_cells']->high }}
                                @elseif ($referenceRanges['epith_cells']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['epith_cells']->male_low . '-' . $referenceRanges['epith_cells']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['epith_cells']->female_low . '-' . $referenceRanges['epith_cells']->female_high }}
                                @elseif ($referenceRanges['epith_cells']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['epith_cells']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                </tr>
                <tr>
                    @if ($urinalysisMicrobiologyResults->leucocytes)
                        <td><strong>Leucocytes: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->leucocytes ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['leucocytes']))
                                {{-- {{ $referenceRanges['leucocytes']->low . '-' . $referenceRanges['leucocytes']->high}} --}}
                                @if ($referenceRanges['leucocytes']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['leucocytes']->low . '-' . $referenceRanges['leucocytes']->high }}
                                @elseif ($referenceRanges['leucocytes']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['leucocytes']->male_low . '-' . $referenceRanges['leucocytes']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['leucocytes']->female_low . '-' . $referenceRanges['leucocytes']->female_high }}
                                @elseif ($referenceRanges['leucocytes']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['leucocytes']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif
                    {{-- MICROSCOPY --}}
                    @if ($urinalysisMicrobiologyResults->white_cells)
                        <td><strong>White Cells: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->white_cells ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['white_cells']))
                                {{-- {{ $referenceRanges['white_cells']->low . '-' . $referenceRanges['white_cells']->high}} --}}
                                @if ($referenceRanges['white_cells']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['white_cells']->low . '-' . $referenceRanges['white_cells']->high }}
                                @elseif ($referenceRanges['white_cells']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['white_cells']->male_low . '-' . $referenceRanges['white_cells']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['white_cells']->female_low . '-' . $referenceRanges['white_cells']->female_high }}
                                @elseif ($referenceRanges['white_cells']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['white_cells']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                </tr>
                <tr>
                    @if ($urinalysisMicrobiologyResults->nitrite)
                        <td><strong>Nitrite: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->nitrite ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['nitrite']))
                                {{-- {{ $referenceRanges['nitrite']->low . '-' . $referenceRanges['nitrite']->high}} --}}
                                @if ($referenceRanges['nitrite']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['nitrite']->low . '-' . $referenceRanges['nitrite']->high }}
                                @elseif ($referenceRanges['nitrite']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['nitrite']->male_low . '-' . $referenceRanges['nitrite']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['nitrite']->female_low . '-' . $referenceRanges['nitrite']->female_high }}
                                @elseif ($referenceRanges['nitrite']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['nitrite']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif
                    {{-- MICROSCOPY --}}
                    @if ($urinalysisMicrobiologyResults->red_cells)
                        <td><strong>Red Cells:</strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->red_cells ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['red_cells']))
                                {{-- {{ $referenceRanges['red_cells']->low . '-' . $referenceRanges['red_cells']->high}} --}}
                                @if ($referenceRanges['red_cells']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['red_cells']->low . '-' . $referenceRanges['red_cells']->high }}
                                @elseif ($referenceRanges['red_cells']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['red_cells']->male_low . '-' . $referenceRanges['red_cells']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['red_cells']->female_low . '-' . $referenceRanges['red_cells']->female_high }}
                                @elseif ($referenceRanges['red_cells']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['red_cells']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                </tr>
                <tr>
                    @if ($urinalysisMicrobiologyResults->glucose)
                        <td><strong>Glucose: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->glucose ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['glucose']))
                                {{-- {{ $referenceRanges['glucose']->low . '-' . $referenceRanges['glucose']->high}} --}}
                                @if ($referenceRanges['glucose']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['glucose']->low . '-' . $referenceRanges['glucose']->high }}
                                @elseif ($referenceRanges['glucose']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['glucose']->male_low . '-' . $referenceRanges['glucose']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['glucose']->female_low . '-' . $referenceRanges['glucose']->female_high }}
                                @elseif ($referenceRanges['glucose']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['glucose']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                    {{-- MICROSCOPY --}}
                    @if ($urinalysisMicrobiologyResults->casts)
                        <td><strong>Casts:</strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->casts ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['casts']))
                                {{-- {{ $referenceRanges['casts']->low . '-' . $referenceRanges['casts']->high}} --}}
                                @if ($referenceRanges['casts']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['casts']->low . '-' . $referenceRanges['casts']->high }}
                                @elseif ($referenceRanges['casts']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['casts']->male_low . '-' . $referenceRanges['casts']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['casts']->female_low . '-' . $referenceRanges['casts']->female_high }}
                                @elseif ($referenceRanges['casts']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['casts']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                </tr>
                <tr>
                    @if ($urinalysisMicrobiologyResults->ketones)
                        <td><strong>Ketones: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->ketones ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['ketones']))
                                {{-- {{ $referenceRanges['ketones']->low . '-' . $referenceRanges['ketones']->high}} --}}
                                @if ($referenceRanges['ketones']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['ketones']->low . '-' . $referenceRanges['ketones']->high }}
                                @elseif ($referenceRanges['ketones']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['ketones']->male_low . '-' . $referenceRanges['ketones']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['ketones']->female_low . '-' . $referenceRanges['ketones']->female_high }}
                                @elseif ($referenceRanges['ketones']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['ketones']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                    {{-- MICROSCOPY --}}
                    @if ($urinalysisMicrobiologyResults->crystals)
                        <td><strong>Crystals: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->crystals ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['crystals']))
                                {{-- {{ $referenceRanges['crystals']->low . '-' . $referenceRanges['crystals']->high}} --}}
                                @if ($referenceRanges['crystals']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['crystals']->low . '-' . $referenceRanges['crystals']->high }}
                                @elseif ($referenceRanges['crystals']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['crystals']->male_low . '-' . $referenceRanges['crystals']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['crystals']->female_low . '-' . $referenceRanges['crystals']->female_high }}
                                @elseif ($referenceRanges['crystals']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['crystals']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                </tr>
                <tr>
                    @if ($urinalysisMicrobiologyResults->proteins)
                        <td><strong>Proteins: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->proteins ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['proteins']))
                                {{-- {{ $referenceRanges['proteins']->low . '-' . $referenceRanges['proteins']->high}} --}}
                                @if ($referenceRanges['proteins']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['proteins']->low . '-' . $referenceRanges['proteins']->high }}
                                @elseif ($referenceRanges['proteins']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['proteins']->male_low . '-' . $referenceRanges['proteins']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['proteins']->female_low . '-' . $referenceRanges['proteins']->female_high }}
                                @elseif ($referenceRanges['proteins']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['proteins']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                    {{-- MICROSCOPY --}}
                    @if ($urinalysisMicrobiologyResults->bacteria)
                        <td><strong>Bacteria:</strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->bacteria ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['bacteria']))
                                {{-- {{ $referenceRanges['bacteria']->low . '-' . $referenceRanges['bacteria']->high}} --}}
                                @if ($referenceRanges['bacteria']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['bacteria']->low . '-' . $referenceRanges['bacteria']->high }}
                                @elseif ($referenceRanges['bacteria']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['bacteria']->male_low . '-' . $referenceRanges['bacteria']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['bacteria']->female_low . '-' . $referenceRanges['bacteria']->female_high }}
                                @elseif ($referenceRanges['bacteria']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['bacteria']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                </tr>
                <tr>
                    @if ($urinalysisMicrobiologyResults->urobilinogen)
                        <td><strong>Urobilinogen: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->urobilinogen ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['urobilinogen']))
                                {{-- {{ $referenceRanges['urobilinogen']->low . '-' . $referenceRanges['urobilinogen']->high}} --}}
                                @if ($referenceRanges['urobilinogen']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['urobilinogen']->low . '-' . $referenceRanges['urobilinogen']->high }}
                                @elseif ($referenceRanges['urobilinogen']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['urobilinogen']->male_low . '-' . $referenceRanges['urobilinogen']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['urobilinogen']->female_low . '-' . $referenceRanges['urobilinogen']->female_high }}
                                @elseif ($referenceRanges['urobilinogen']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['urobilinogen']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                    {{-- MICROSCOPY --}}
                    @if ($urinalysisMicrobiologyResults->yeast)
                        <td><strong>Yeast:</strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->yeast ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['yeast']))
                                {{-- {{ $referenceRanges['yeast']->low . '-' . $referenceRanges['yeast']->high}} --}}
                                @if ($referenceRanges['yeast']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['yeast']->low . '-' . $referenceRanges['yeast']->high }}
                                @elseif ($referenceRanges['yeast']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['yeast']->male_low . '-' . $referenceRanges['yeast']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['yeast']->female_low . '-' . $referenceRanges['yeast']->female_high }}
                                @elseif ($referenceRanges['yeast']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['yeast']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                </tr>
                <tr>
                    @if ($urinalysisMicrobiologyResults->bilirubin)
                        <td><strong>Bilirubin: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->bilirubin ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['bilirubin']))
                                {{-- {{ $referenceRanges['bilirubin']->low . '-' . $referenceRanges['bilirubin']->high}} --}}
                                @if ($referenceRanges['bilirubin']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['bilirubin']->low . '-' . $referenceRanges['bilirubin']->high }}
                                @elseif ($referenceRanges['bilirubin']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['bilirubin']->male_low . '-' . $referenceRanges['bilirubin']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['bilirubin']->female_low . '-' . $referenceRanges['bilirubin']->female_high }}
                                @elseif ($referenceRanges['bilirubin']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['bilirubin']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                    {{-- MICROSCOPY --}}
                    @if ($urinalysisMicrobiologyResults->trichomonas)
                        <td><strong>Trichomonas: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->trichomonas ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['trichomonas']))
                                {{-- {{ $referenceRanges['trichomonas']->low . '-' . $referenceRanges['trichomonas']->high}} --}}
                                @if ($referenceRanges['trichomonas']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['trichomonas']->low . '-' . $referenceRanges['trichomonas']->high }}
                                @elseif ($referenceRanges['trichomonas']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['trichomonas']->male_low . '-' . $referenceRanges['trichomonas']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['trichomonas']->female_low . '-' . $referenceRanges['trichomonas']->female_high }}
                                @elseif ($referenceRanges['trichomonas']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['trichomonas']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                </tr>
                <tr>
                    @if ($urinalysisMicrobiologyResults->blood)
                        <td><strong>Blood: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->blood ?? '' }}</td>
                        <td>
                            @if (isset($referenceRanges['blood']))
                                {{-- {{ $referenceRanges['blood']->low . '-' . $referenceRanges['blood']->high}} --}}
                                @if ($referenceRanges['blood']->urireference_range == 'uri_basic_ref')
                                    {{ $referenceRanges['blood']->low . '-' . $referenceRanges['blood']->high }}
                                @elseif ($referenceRanges['blood']->urireference_range == 'uri_optional_ref')
                                    Male:
                                    {{ $referenceRanges['blood']->male_low . '-' . $referenceRanges['blood']->male_high }}
                                    <br>
                                    Female:
                                    {{ $referenceRanges['blood']->female_low . '-' . $referenceRanges['blood']->female_high }}
                                @elseif ($referenceRanges['blood']->urireference_range == 'uri_no_manual_tag')
                                    {{ $referenceRanges['blood']->nomanualvalues_ref_range }}
                                @endif
                            @endif
                        </td>
                    @endif

                </tr>
                <tr>
                    @if ($urinalysisMicrobiologyResults->colour)
                        <td><strong>Colour: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->colour ?? '' }}</td>
                    @endif

                </tr>
                <tr>
                    @if ($urinalysisMicrobiologyResults->appearance)
                        <td><strong>Appearance: </strong> </td>
                        <td> {{ $urinalysisMicrobiologyResults->appearance ?? '' }}</td>
                    @endif

                </tr>
                @endforeach
        </tbody>
    </table>

    @php
        $filteredResults = collect($procedureResults)->filter(function ($item) {
            return !empty($item->specimen_note);
        });
    @endphp

    @if ($filteredResults->isNotEmpty())
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
        <div class="page-break"></div>
    @endif

    @php
    $data = json_decode($urinalysisMicrobiologyResults->sensitivity ?? '[]', true);
    @endphp
    @if ($data)

    <table>
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
                                name="{{ $i['microorganism'] }}-{{ $item['antibiotic'] }}-sensitivity"
                                {{ $item['sensitivity'] === 'sensitive' ? 'checked' : '' }}></td>
                        <td class="text-center"><input type="radio"
                                name="{{ $i['microorganism'] }}-{{ $item['antibiotic'] }}-sensitivity"
                                {{ $item['sensitivity'] === 'resistant' ? 'checked' : '' }}></td>
                        <td class="text-center"><input type="radio"
                                name="{{ $i['microorganism'] }}-{{ $item['antibiotic'] }}-sensitivity"
                                {{ $item['sensitivity'] === 'intermediate' ? 'checked' : '' }}></td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    @endif


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
