<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Border Life - LIS</title>

    <style>
        @page {
            margin: 10mm 10mm 30mm 10mm;
            box-sizing: border-box
        }



        body {
            font-family: 'Cambria', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
            width: 100%;
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
            page-break-inside: auto;
            box-sizing: border-box;
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
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .table-wrapper {
            page-break-inside: avoid;
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

        .text-center {
            text-align: center;
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
    <?php
        $procedureResults = $sample ? $sample->procedureResults : [];
    ?>

    <table class="order-details">
        <thead>
            <tr>
                <th width="50%" style="vertical-align: top;">
                    <img src="<?php echo e(public_path('build/images/logo-lis.png')); ?>" alt="Logo" style="height: 70px;"><br>
                    <span style="display: block; font-weight:normal;, font-size: 15px; margin-top: 1px;"><small>ISO:15189
                            Accredited</small></span>
                    <span style="margin-top: 8px; display: inline-block;"><small>71 Eastern Main Road Barataria, San
                            Juan Trinidad and Tobago</small></span>
                </th>
                <th width="50%" class="text-end company-data">
                    <img height="50" src="data:image/png;base64,<?php echo e(base64_encode($qrCode)); ?>" alt="QR Code"><br><br>
                    <span style="display: inline-block; text-align: left; width: 100%;"><strong>TEL: </strong>(868)
                        229-8643 or 316-1383</span><br>
                    <span style="display: inline-block; text-align: left; width: 100%;"><strong>Mail:
                        </strong>borderlifemedlab@gmail.com</span><br>
                </th>
            </tr>
            <tr>
                <th width="40%" style="vertical-align: top;">
                    <h2>Patient Information</h2>
                    <table>
                        <tr>
                            <td style="font-weight: normal"><strong>Name:</strong>
                                <?php echo e($sample->patient->first_name ?? ''); ?> <?php echo e($sample->patient->surname ?? ''); ?></td>
                            <td style="font-weight: normal"><strong>Sex:</strong> <?php echo e($sample->patient->sex ?? ''); ?></td>
                        </tr>
                        <?php
                            $dob = \Carbon\Carbon::parse($sample->patient->dob);
                            $age = $dob->age;
                        ?>
                        <tr>
                            <td style="font-weight: normal"><strong>DOB:</strong>
                                <?php echo e(\Carbon\Carbon::parse($sample->patient->dob)->format('d-M-Y')); ?></td>
                            <td style="font-weight: normal"><strong>Age:</strong> <?php echo e($age); ?> </td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal"><strong>Ordering Dr:</strong> <?php echo e($sample->doctor->name); ?>

                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal"><strong>Institution:</strong>
                                <?php echo e($sample->institution->name); ?></td>
                        </tr>
                    </table>
                    

                </th>
                <th width="60%" colspan="6" class="company-data" style="vertical-align: top;">
                    <h2>Report Information</h2>
                    <table>
                        <tr>
                            <td style="font-weight: normal"><strong>Collection Date:</strong>
                                <?php echo e(\Carbon\Carbon::parse($sample->collected_date)->format('d-M-Y')); ?></td>
                            <td style="font-weight: normal"><strong>Lab Ref:</strong>
                                <?php echo e($sample->access_number ?? ''); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal"><strong>Received Date:</strong>
                                <?php echo e(\Carbon\Carbon::parse($sample->received_date)->format('d-M-Y')); ?></td>
                            <td style="font-weight: normal"><strong>Sample ID:</strong>
                                <?php echo e($sample->access_number ?? ''); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal"><strong>Report Date:</strong>
                                <?php echo e(\Carbon\Carbon::parse($sample->created_at)->format('d-M-Y')); ?></td>
                        </tr>
                    </table>
                </th>

            </tr>
            <tr>
                <td colspan="7">
                    <hr style="border: 1px solid #3d90ca; margin: 10px 0;">
                </td>
            </tr>
            <tr>
                <th colspan="7" style="">
                    <?php
                        // Assuming $sample->tests is a collection or array of test objects
                        $testNames = $tests->pluck('name')->implode(', ');
                        $individualtests = $sample
                            ->tests()
                            ->where('department', $reporttype)
                            ->pluck('name')
                            ->implode(', ');
                        // $sampleprofiles = $sample->testProfiles()->pluck('name')->implode(', ');
                        $sampleprofiles = $sample
                            ->testProfiles()
                            ->whereHas('departments', function ($query) use ($reporttype) {
                                $query->where('department', $reporttype);
                            })
                            ->with('tests')
                            ->pluck('name')
                            ->implode(', ');

                    ?>
                    <span style="white-space: nowrap;"><strong>Request:
                            <?php echo e($sampleprofiles . ', ' . $individualtests); ?></strong></span>
                </th>
            </tr>
            <tr>
                <?php
                    $urinalysisStatus = $sample->departmentStatus('3');
                ?>
                <td colspan="4">
                    <span style="white-space: nowrap;"><strong>Comments: </strong> <?php echo e($urinalysisStatus->note ?? ''); ?>

                    </span>
                </td>
            </tr>
        </thead>
        <tbody>
            
            <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $testReport = $testReports->where('test_id', $test->id)->where('sample_id', $sample->id)->first();
                    if (empty($testReport)) {
                        continue;
                    }
                    if (!empty($testReport) && empty($testReport->urinalysisMicrobiologyResults->first())) {
                        continue;
                    }
                    $urinalysisMicrobiologyResults = $testReport
                        ? $testReport->urinalysisMicrobiologyResults->first()
                        : [];

                    // $procedureResults = $sample->sensitivityResults
                    // ? $sample->sensitivityResults[0]->sensitivity_profiles
                    // : [];

                ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <?php if(!empty($urinalysisMicrobiologyResults)): ?>
                <tr style="margin:0px;" width="100%">
                <tr>
                    <th class="text-start heading" colspan="7" style="border-top: 2px solid #3d90ca; ">
                        URINALYSIS
                    </th>
                </tr>
                <td colspan="1"  style="vertical-align: top;  border:1px solid #3d90ca; width: 50%;padding:0px;border-collapse: collapse;">
                    <table class="chemical-analysis" style="border-collapse: collapse;margin:0px;padding:0px;">
                        <!-- Add rows for chemical analysis data -->
                        <thead>

                            <tr>
                                <th class="heading" colspan="7" style="text-align: center"> CHEMICAL ANALYSIS</th>
                            </tr>
                            <tr class="bg-blue" colspan="">
                                <th width="30%">Test</th>
                                <th width="10%">Results</th>
                                <th width="10%">Flag</th>
                                <th width="40%">Normal Range</th>
                            </tr>
                        </thead>
                        <tbody >
                            <?php $__currentLoopData = $categorizedTests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profileId => $profileData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr id="<?php echo e($profileId); ?>">
                                    <td colspan="7"><strong>* <?php echo e($profileData['name']); ?></strong></td>
                                </tr>
                                <?php
                                    $microscopyTests = collect();
                                    $chemicalAnalysisTests = collect();
                                    // dd($profileData['tests']);
                                    foreach ($profileData['tests'] as $test) {
                                        // dd($profileData['tests']);
                                        if ($test->urin_test_type === '2') {
                                            $microscopyTests->push($test);
                                        } elseif ($test->urin_test_type === '1') {
                                            $chemicalAnalysisTests->push($test);
                                        }
                                    }
                                    // dd($microscopyTests);
                                ?>
                                <?php $__currentLoopData = $chemicalAnalysisTests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $testReport = $testReports
                                            ->where('test_id', $test->id)
                                            ->where('sample_id', $sample->id)
                                            ->first();
                                        $urinalysisMicrobiologyResults = $testReport
                                            ? $testReport->urinalysisMicrobiologyResults->first()
                                            : [];
                                        $flag = $urinalysisMicrobiologyResults->flag ?? '';
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
                                            $referenceRange =
                                                ($test->basic_low_value_ref_range ?? '') .
                                                '-' .
                                                ($test->basic_high_value_ref_range ?? '');
                                        } elseif ($test->reference_range == 'optional_ref') {
                                            $referenceRange =
                                                'Male: ' .
                                                ($test->male_low_value_ref_range ?? '') .
                                                '-' .
                                                ($test->male_high_value_ref_range ?? '') .
                                                '<br>Female: ' .
                                                ($test->female_low_value_ref_range ?? '') .
                                                '-' .
                                                ($test->female_high_value_ref_range ?? '');
                                        } elseif ($test->reference_range == 'no_manual_tag') {
                                            $referenceRange = $test->nomanualvalues_ref_range ?? '';
                                        }
                                    ?>
                                    <tr>
                                        <td> <small><?php echo e($urinalysisMicrobiologyResults->description ?? $test->name); ?> </small></td>
                                        <td> <small><?php echo e($urinalysisMicrobiologyResults->test_results ?? ''); ?></small></td>
                                        <td>
                                            <span class="badge badge-pill flag-badge" style="<?php echo e($background); ?>"
                                                data-key="t-hot"><small><?php echo e($flag); ?></small></span>
                                        </td>
                                        <td ><small><?php echo $referenceRange; ?></small></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            



                        </tbody>
                    </table>
                </td>
                <td colspan="6" style="vertical-align: top;  border:1px solid #3d90ca; width: 50%;padding:0px;border-collapse: collapse;">
                    <table class="microscopy-analysis" style="border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th class="heading" colspan="7" style="text-align: center">MICROSCOPY</th>
                            </tr>
                            <tr class="bg-blue">
                                <th width="30%">Test</th>
                                <th width="15%">Results</th>
                                <th width="15%">Flag</th>
                                <th width="40%">Normal Range</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php $__currentLoopData = $categorizedTests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profileId => $profileData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr id="<?php echo e($profileId); ?>">
                                    <td colspan="7"><strong>* <?php echo e($profileData['name']); ?></strong></td>
                                </tr>
                                <?php
                                    $microscopyTests = collect();
                                    $chemicalAnalysisTests = collect();
                                    // dd($profileData['tests']);
                                    foreach ($profileData['tests'] as $test) {
                                        // dd($profileData['tests']);
                                        if ($test->urin_test_type === '2') {
                                            $microscopyTests->push($test);
                                        } elseif ($test->urin_test_type === '1') {
                                            $chemicalAnalysisTests->push($test);
                                        }
                                    }
                                    // dd($microscopyTests);
                                ?>
                                <?php $__currentLoopData = $microscopyTests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $testReport = $testReports
                                            ->where('test_id', $test->id)
                                            ->where('sample_id', $sample->id)
                                            ->first();
                                        $urinalysisMicrobiologyResults = $testReport
                                            ? $testReport->urinalysisMicrobiologyResults->first()
                                            : [];
                                        $flag = $urinalysisMicrobiologyResults->flag ?? '';
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
                                            $referenceRange =
                                                ($test->basic_low_value_ref_range ?? '') .
                                                '-' .
                                                ($test->basic_high_value_ref_range ?? '') .
                                                ' ' .
                                                ($test->basic_unit_value_ref_range ?? '');
                                        } elseif ($test->reference_range == 'optional_ref') {
                                            $referenceRange =
                                                'Male: ' .
                                                ($test->male_low_value_ref_range ?? '') .
                                                '-' .
                                                ($test->male_high_value_ref_range ?? '') .
                                                ' ' .
                                                ($test->male_unit_value_ref_range ?? '') .
                                                '<br>Female: ' .
                                                ($test->female_low_value_ref_range ?? '') .
                                                '-' .
                                                ($test->female_high_value_ref_range ?? '') .
                                                ' ' .
                                                ($test->female_unit_value_ref_range ?? '');
                                        } elseif ($test->reference_range == 'no_manual_tag') {
                                            $referenceRange = $test->nomanualvalues_ref_range ?? '';
                                        }
                                    ?>
                                    <tr>
                                        <td style="vertical-align: top;">
                                             <small><?php echo e($urinalysisMicrobiologyResults->description ?? $test->name); ?> </small> </td>
                                        <td style="vertical-align: top;">
                                             <small><?php echo e($urinalysisMicrobiologyResults->test_results ?? ''); ?> </small></td>
                                        <td style="vertical-align: top;">
                                            <span class="badge badge-pill flag-badge" style="<?php echo e($background); ?>"
                                                data-key="t-hot"> <small><?php echo e($flag); ?> </small></span>
                                        </td>
                                        <td style="vertical-align: top;"> <small><?php echo $referenceRange; ?> </small></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>
                </td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>

    <?php
        // dd($procedureResults);
        $filteredResults = collect($procedureResults)->filter(function ($item) {
            return !empty($item->specimen_note);
        });
    ?>

    <?php if($filteredResults->isNotEmpty()): ?>
        <br>
        <br>
        <table class="table-wrapper">
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
                <?php $__currentLoopData = $procedureResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td width="10%" style="vertical-align: top;"><strong><?php echo e($value->procedure ?? ''); ?></strong>
                        </td>
                        <td style="vertical-align: top;">
                            <?php echo nl2br(e($value->specimen_note ?? '')); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        
    <?php endif; ?>


    <?php
        $sensitivityResult = $sample->sensitivityResults->first();
        $data = $sensitivityResult ? json_decode($sensitivityResult->sensitivity, true) : [];
    ?>
    <?php if($data): ?>
        <br>
        <br>
        <table class="table-wrapper">
            <thead>
                <tr>
                    <th class=" text-start heading" colspan="2">
                        SENSITIVITY
                    </th>
                </tr>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="bg-blue">
                        <th width="40%">MICROORGANISM &nbsp; ISOLATED</th>
                        <th>ANTIBIOTICS </th>
                        <th><?php echo e(getSensitivityUnitByMicroorganism($i['microorganism'])); ?></th>
                        <th>SENSITIVE</th>
                        <th>RESISTANT</th>
                        <th>INTERMEDIATE</th>
                    </tr>
            </thead>

            <tbody>

                <?php $__currentLoopData = $i['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="<?php echo e($index === 0 ? '' : ''); ?>">
                        <td><?php echo e($index === 0 ? $i['microorganism'] : ''); ?></td>
                        <td><?php echo e($item['antibiotic']); ?></td>
                        <td class="text-center"><?php echo e($item['mic']); ?></td>
                        <td class="text-center"><input type="radio"
                                name="<?php echo e($i['microorganism']); ?>-<?php echo e($item['antibiotic']); ?>-sensitivity"
                                <?php echo e($item['sensitivity'] === 'sensitive' ? 'checked' : ''); ?>></td>
                        <td class="text-center"><input type="radio"
                                name="<?php echo e($i['microorganism']); ?>-<?php echo e($item['antibiotic']); ?>-sensitivity"
                                <?php echo e($item['sensitivity'] === 'resistant' ? 'checked' : ''); ?>></td>
                        <td class="text-center"><input type="radio"
                                name="<?php echo e($i['microorganism']); ?>-<?php echo e($item['antibiotic']); ?>-sensitivity"
                                <?php echo e($item['sensitivity'] === 'intermediate' ? 'checked' : ''); ?>></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
    <br>
    <br>
    <?php endif; ?>
    <table class="table-wrapper">
        <thead>
            <tr>
                <th class="text-start heading" colspan="2">
                    REVIEW AND RECOMMENDATIONS
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo nl2br(e($sensitivityResult->review ?? '')); ?></td>
            </tr>
            <br><br><br>
            <tr>
                <td>
                    <strong>Validated by: </strong>
                    <?php echo e($validated_by); ?>

                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>Electronically signed by: </strong>
                    <?php echo e($signed_by); ?>

                </td>
            </tr>
        </tbody>
    </table>

    <script type="text/php">
            if ( isset($pdf) ) {
                $pdf->page_script('
                    if ($PAGE_COUNT > 0) {
                        $font = $fontMetrics->get_font("Cambria, serif", "normal");
                        $size = 9;
                        // Centered text calculation
                        $accreditText = "THIS LABORATORY IS ACCREDITED FOR THE TESTS AND PROFILES MARKED *.";
                        $directorText = "Lab Director: Dr. Christina Pierre";
                        $width = $pdf->get_width();
                        $accreditWidth = $fontMetrics->get_text_width($accreditText, $font, $size);
                        $directorWidth = $fontMetrics->get_text_width($directorText, $font, $size);
                        $pdf->text(($width - $accreditWidth) / 2, 786, $accreditText, $font, $size);
                        $pdf->line(40, 810, $width - 40, 810, [0, 112/255, 192/255], 0.5);
                        $pdf->text(270, 815, "Page $PAGE_NUM of $PAGE_COUNT", $font, $size);
                        // Director text below the line and page count
                        $pdf->text(($width - $directorWidth) / 2, 828, $directorText, $font, $size);
                    }
                ');
            }
        </script>



</body>

</html>
<?php /**PATH /home/two0/public_html/resources/views/pdf/urinalysisMicrobiologyPdf.blade.php ENDPATH**/ ?>