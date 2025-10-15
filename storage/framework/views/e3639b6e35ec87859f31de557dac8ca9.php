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
                    <img src="<?php echo e(public_path('build/images/logo-lis.png')); ?>" alt="Logo" style="height: 70px;"><br>
                    <span style="display: block; font-weight:normal;, font-size: 15px; margin-top: 1px;"><small>ISO:15189 Accredited</small></span>
                    <p style="margin-top: 8px;"><small>71 Eastern Main Road Barataria, San Juan Trinidad and Tobago</small></p>
                </th>
                <th width="50%" colspan="2" class="text-end company-data">
                    <img height="50" src="data:image/png;base64,<?php echo e(base64_encode($qrCode)); ?>" alt="QR Code"><br><br>
                    <span style="display: inline-block; text-align: left; width: 100%;"><strong>TEL: </strong>(868) 229-8643 or 316-1383</span><br>
                    <span style="display: inline-block; text-align: left; width: 100%;"><strong>Mail: </strong>borderlifemedlab@gmail.com</span><br>
                </th>
            </tr>
            <tr>
                <th width="45%" style="vertical-align: top;">
                    <h2>Patient Information</h2>
                    <table>
                        <tr>
                            <td style="font-weight: normal"><strong>Name:</strong> <?php echo e($sample->patient->first_name ?? ''); ?> <?php echo e($sample->patient->surname ?? ''); ?></td>
                            <td style="font-weight: normal"><strong>Sex:</strong> <?php echo e($sample->patient->sex ?? ''); ?></td>
                        </tr>
                        <?php
                            $dob = \Carbon\Carbon::parse($sample->patient->dob);
                            $age = $dob->age;
                        ?>
                        <tr>
                            <td style="font-weight: normal"><strong>DOB:</strong> <?php echo e(\Carbon\Carbon::parse($sample->patient->dob)->format('d-M-Y')); ?></td>
                            <td style="font-weight: normal"><strong>Age:</strong> <?php echo e($age); ?> yrs</td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal"><strong>Ordering Dr:</strong> <?php echo e($sample->doctor->name); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal"><strong>Institution:</strong> <?php echo e($sample->institution->name); ?></td>
                        </tr>
                    </table>
                    

                </th>
                <th width="60%" colspan="3" class="company-data" style="vertical-align: top;">
                    <h2>Report Information</h2>
                    <table>
                        <tr>
                            <td style="font-weight: normal"><strong>Collection Date:</strong> <?php echo e(\Carbon\Carbon::parse($sample->collected_date)->format('d-M-Y')); ?></td>
                            <td style="font-weight: normal"><strong>Lab Ref:</strong> <?php echo e($sample->access_number ?? ''); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal"><strong>Received Date:</strong> <?php echo e(\Carbon\Carbon::parse($sample->received_date)->format('d-M-Y')); ?></td>
                            <td style="font-weight: normal"><strong>Sample ID:</strong> <?php echo e($sample->access_number ?? ''); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal"><strong>Report Date:</strong> <?php echo e(\Carbon\Carbon::parse($sample->created_at)->format('d-M-Y')); ?></td>
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
                    <?php
                    // Assuming $sample->tests is a collection or array of test objects
                    $testNames = $tests->pluck('name')->implode(', ');
                    $individualtests = $sample->tests()->where('department', $reporttype)->pluck('name')->implode(', ');
                    // $sampleprofiles = $sample->testProfiles()->pluck('name')->implode(', ');
                    $sampleprofiles = $sample->testProfiles()->whereHas('departments', function($query) use ($reporttype) {
                            $query->where('department', $reporttype);
                        })->with('tests')->pluck('name')->implode(', ');

                ?>
                <span style="white-space: nowrap;"><strong>Request: <?php echo e($sampleprofiles  . ', ' . $individualtests); ?></strong></span>
                </th>
            </tr>
            <tr class="bg-blue">
                <th width="50%" colspan="4">CLINICAL INFORMATION</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $testReport = $testReports
            ->where('test_id', $test->id)
            ->where('sample_id', $sample->id)
            ->first();
            // dd($testReport);
            $cytologyGynecologyResults = $testReport ? $testReport->cytologyGynecologyResults->first() : [];
            // dd($cytologyGynecologyResults);
            ?>
            <tr>
                <td colspan="2"> <span> <strong> LAST PERIOD: </strong></span> <?php echo e($cytologyGynecologyResults->last_period
                    ?? ''); ?></td>
                <td colspan="2"> <span> <strong> CONTRACEPTIVE: </strong></span>
                    <?php echo e($cytologyGynecologyResults->contraceptive ?? ''); ?></td>
            </tr>
            <tr>
                <td colspan="2"> <span> <strong> PREVIOUS PAP: </strong></span>
                    <?php echo e($cytologyGynecologyResults->previous_pap ?? ''); ?></td>
                <td colspan="2"> <span> <strong> RESULT: </strong></span> <?php echo e($cytologyGynecologyResults->result ?? ''); ?>

                </td>
            </tr>
            <tr>
                <td colspan="4"> <span> <strong> HISTORY: </strong></span> <?php echo e($cytologyGynecologyResults->history ?? ''); ?>

                </td>
            </tr>
            <tr>
                <td colspan="4"> <span> <strong> CERVIX EXAMINATION: </strong></span>
                    <?php echo e($cytologyGynecologyResults->cervix_examination ?? ''); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    

    <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
    $testReport = $testReports
    ->where('test_id', $test->id)
    ->where('sample_id', $sample->id)
    ->first();
    // dd($testReport);
    $cytologyGynecologyResults = $testReport ? $testReport->cytologyGynecologyResults->first() : [];
    // dd($cytologyGynecologyResults);
    ?>
    

    <table>
        <thead>
            <tr class="bg-blue" colspan="4">
                <th width="50%" colspan="4">SPECIMEN ADEQUACY:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4"><?php echo nl2br(e($cytologyGynecologyResults->specimen_adequacy ?? '')); ?></td>
            </tr>
        </tbody>
    </table>
    
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    

    <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
    $testReport = $testReports
    ->where('test_id', $test->id)
    ->where('sample_id', $sample->id)
    ->first();

    $cytologyGynecologyResults = $testReport ? $testReport->cytologyGynecologyResults->first() : [];

    ?>
    

    <table>
        <thead>
            <tr class="bg-blue" colspan="4">
                <th width="50%" colspan="4">DIAGNOSTIC INTERPRETATION:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4"><?php echo nl2br(e($cytologyGynecologyResults->diagnostic_interpretation ?? '')); ?></td>
            </tr>
        </tbody>
    </table>
    
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    

    <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
    $testReport = $testReports
    ->where('test_id', $test->id)
    ->where('sample_id', $sample->id)
    ->first();

    $cytologyGynecologyResults = $testReport ? $testReport->cytologyGynecologyResults->first() : [];

    ?>
    

    <table>
        <thead>
            <tr class="bg-blue" colspan="4">
                <th width="50%" colspan="4">RECOMMENDATION:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4"><?php echo nl2br(e($cytologyGynecologyResults->recommend ?? '')); ?></td>
            </tr>
        </tbody>
    </table>
    
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <script type="text/php">
        if ( isset($pdf) ) {
            $signedBy = '<?php echo e($signed_by); ?>';
            $validatedBy = '<?php echo e($validated_by); ?>';
            $pdf->page_script('
                if ($PAGE_COUNT > 0) {
                    $font = $fontMetrics->get_font("Cambria", "normal");
                    $size = 9;
                    // Left aligned
                    $pdf->text(45, 786, "CYTOTECHNOLOGIST:  PETAL JULIEN, BSc. MLT", $font, $size);
                    // Right aligned (move left to fit page)
                    $pdf->text(350, 786, "PATHOLOGIST: MELANIE JOHNCILLA, MD", $font, $size);
                    // Line
                    $pdf->text(30, 795, "___________________________________________________________________________________________________________", $font, $size, array(61/255, 144/255, 202/255));
                    // Page number (centered)
                    $pdf->text(150, 810, "Page $PAGE_NUM of $PAGE_COUNT", $font, $size);
                    // Lab Director (right)
                    $pdf->text(350, 810, "Lab Director: Dr. Christina Pierre", $font, $size);
                }
            ');
        }
    </script>
</body>

</html>
<?php /**PATH /home/two0/public_html/resources/views/pdf/cytologyGynecologyPdf.blade.php ENDPATH**/ ?>