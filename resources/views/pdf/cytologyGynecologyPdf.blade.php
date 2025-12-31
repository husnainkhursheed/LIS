<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Border Life - LIS</title>

    <style>
        @page {
            margin: 2mm 10mm 30mm 10mm;
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

        th,
        td {
            padding: 4px;
            font-size: 14px;
            line-height: 1.2;
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
                        </tr>
                        @php
                            $dob = \Carbon\Carbon::parse($sample->patient->dob);
                            $age = $dob->age;
                        @endphp
                        <tr>
                            <td style="font-weight: normal;">
                                <strong>DOB:</strong> {{ \Carbon\Carbon::parse($sample->patient->dob)->format('d-M-Y') }}&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>Age:</strong> {{ $age }} yrs &nbsp;
                                <strong>Sex:</strong> {{ $sample->patient->sex === 'male' ? 'M' : 'F' }}
                            </td>
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
                    <hr style="border: 1px solid #3d90ca; margin: 5px 0;">
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
                <td colspan="4">
                    <hr style="border: 1px solid #3d90ca; margin: 5px 0;">
                </td>
            </tr>
            <tr class="bg-blue">
                <th width="50%" colspan="4">CLINICAL INFORMATION</th>
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
                <td colspan="2"> <span> <strong> LAST PERIOD: </strong></span> {{$cytologyGynecologyResults->last_period
                    ?? ''}}</td>
                <td colspan="2"> <span> <strong> CONTRACEPTIVE: </strong></span>
                    {{$cytologyGynecologyResults->contraceptive ?? ''}}</td>
            </tr>
            <tr>
                <td colspan="2"> <span> <strong> PREVIOUS PAP: </strong></span>
                    {{$cytologyGynecologyResults->previous_pap ?? ''}}</td>
                <td colspan="2"> <span> <strong> RESULT: </strong></span> {{$cytologyGynecologyResults->result ?? ''}}
                </td>
            </tr>
            <tr>
                <td colspan="4"> <span> <strong> CERVIX EXAMINATION: </strong></span>
                    {{$cytologyGynecologyResults->cervix_examination ?? ''}}</td>
            </tr>
            <tr>
                <td colspan="4"> <span> <strong> HISTORY: </strong></span> {{$cytologyGynecologyResults->history ?? ''}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- loop for $cytologyGynecologyResults->specimen_adequacy / SPECIMEN ADEQUACY --}}

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
    {{-- @if ($cytologyGynecologyResults->specimen_adequacy) --}}

    <table>
        <thead>
            <tr class="bg-blue" colspan="4">
                <th width="50%" colspan="4">SPECIMEN ADEQUACY:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4">{!! nl2br(e($cytologyGynecologyResults->specimen_adequacy ?? '')) !!}</td>
            </tr>
        </tbody>
    </table>
    {{-- @endif --}}
    @endforeach
    <br>

    {{-- loop for $cytologyGynecologyResults->diagnostic_interpretation / DIAGNOSTIC INTERPRETATION --}}

    @foreach ($tests as $index => $test)
    @php
    $testReport = $testReports
    ->where('test_id', $test->id)
    ->where('sample_id', $sample->id)
    ->first();

    $cytologyGynecologyResults = $testReport ? $testReport->cytologyGynecologyResults->first() : [];

    @endphp
    {{-- @if ($cytologyGynecologyResults->diagnostic_interpretation) --}}

    <table>
        <thead>
            <tr class="bg-blue" colspan="4">
                <th width="50%" colspan="4">DIAGNOSTIC INTERPRETATION:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4">{!! nl2br(e($cytologyGynecologyResults->diagnostic_interpretation ?? '')) !!}</td>
            </tr>
        </tbody>
    </table>
    {{-- @endif --}}
    @endforeach
    <br>

    {{-- loop for $cytologyGynecologyResults->recommend / RECOMMENDATION --}}

    @foreach ($tests as $index => $test)
    @php
    $testReport = $testReports
    ->where('test_id', $test->id)
    ->where('sample_id', $sample->id)
    ->first();

    $cytologyGynecologyResults = $testReport ? $testReport->cytologyGynecologyResults->first() : [];

    @endphp
    {{-- @if ($cytologyGynecologyResults->recommend) --}}

    <table>
        <thead>
            <tr class="bg-blue" colspan="4">
                <th width="50%" colspan="4">RECOMMENDATION *2019 ASCCP Updated Consensus Guidelines www.asccp.org:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4">{!! nl2br(e($cytologyGynecologyResults->recommend ?? '')) !!}</td>
            </tr>
        </tbody>
    </table>
    {{-- @endif --}}
    @endforeach
    <table style="margin-top: 40px ">
        <thead>
            {{-- <tr class="bg-blue" colspan="4">
                <th width="50%" colspan="4">RECOMMENDATION:</th>
            </tr> --}}
        </thead>
        <tbody>
            {{-- <tr>
                <td colspan="4">{!! nl2br(e($cytologyGynecologyResults->recommend ?? '')) !!}</td>
            </tr> --}}

            {{-- <tr>
                <td>
                    <strong>Validated by: </strong>
                    {{ $validated_by }}
                </td>
            </tr> --}}
            <tr>
                <td colspan="4">
                    <strong>This material has been reviewed and the report completed and electronically signed by: </strong>
                    {{ $validated_by }}
                </td>
            </tr>

        </tbody>
    </table>

<script type="text/php">
    $pdf->page_script('
        if ($PAGE_COUNT > 0) {
            $font = $fontMetrics->get_font("Cambria, serif", "normal");
            $size = 9;
            $width = $pdf->get_width();

            // -------------------
            // HEADER (unchanged)
            // -------------------

            // Centered PATHOLOGIST
            $pathologistText = "PATHOLOGIST: MELANIE JOHNCILLA MD, FCAP";
            $pathologistWidth = $fontMetrics->get_text_width($pathologistText, $font, $size);
            $pdf->text(($width - $pathologistWidth) / 2, 750, $pathologistText, $font, $size);

            // Centered DOC-PPA reference below PATHOLOGIST
            $docRef = "(DOC-PPA-#13-V1)";
            $docRefWidth = $fontMetrics->get_text_width($docRef, $font, $size);

            // Line below DOC-PPA
            $pdf->line(40, 765, $width - 40, 765, [61/255, 144/255, 202/255], 0.5);

            // -------------------
            // FOOTER (UPDATED)
            // -------------------

            // LEFT: Lab Director
            $directorText = "Lab Director: Dr. Christina Pierre";
            $directorWidth = $fontMetrics->get_text_width($directorText, $font, $size);
            $pdf->text(40, 770, $directorText, $font, $size);

            // CENTER: Page number
            $pageText = "Page $PAGE_NUM of $PAGE_COUNT";
            $pageWidth = $fontMetrics->get_text_width($pageText, $font, $size);
            $pdf->text(($width - $pageWidth) / 2, 770, $pageText, $font, $size);

            // RIGHT: DOC-PPA number
            $pdf->text($width - $docRefWidth - 40, 770, $docRef, $font, $size);
        }
    ');
</script>

</body>

</html>
