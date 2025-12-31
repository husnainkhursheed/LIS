<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sample;
use App\Models\TestReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\UrinalysisReferenceRanges;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PDFController extends Controller
{

    public function generateQRCode($data)
    {
        $qr = QrCode::size(300)->generate($data);

        return $qr;
    }

    public function generatePDF(Request $request, $sample_id, $reporttype)
    {
        try {
            // Fetch sample and related tests
            $sample = Sample::findOrFail($sample_id);
            $individualTests = $sample->tests()->where('department', $reporttype)->get();

            // Get all test profiles associated with the sample, regardless of department
            $profiles = $sample->testProfiles()->with('tests', 'subProfiles.tests', 'departments')->get();
            // dd($profiles);

            $profileTests = collect();

            foreach ($profiles as $profile) {
                // If profile has subprofiles, ignore department check
                $hasSubProfiles = $profile->subProfiles && $profile->subProfiles->isNotEmpty();

                if ($hasSubProfiles ) {

                    // Add profile's tests
                    $profileTests = $profileTests->merge($profile->tests);
                    //  dd($profileTests);

                    // Add all subprofile tests recursively
                    $allSubProfiles = getSubProfilesRecursive($profile);
                    foreach ($allSubProfiles as $subProfile) {
                        if ($subProfile->departments->contains('department', $reporttype)) {
                            $profileTests = $profileTests->merge($subProfile->tests);
                            //  dd($profileTests);
                        }
                    }
                }else{
                    // If no subprofiles, check if the profile's departments match the report type
                    if ($profile->departments->contains('department', $reporttype)) {
                        // Add profile's tests directly
                        $profileTests = $profileTests->merge($profile->tests);
                    }
                }
            }

            // Combine individual tests and profile tests
            $tests = $individualTests->merge($profileTests);
            // dd($tests->count());

            // Collect test reports with their related results
            $testReports = collect(); // Initialize as a collection

            $allTestsCompleted = true; // Flag to check if all tests are completed
            $completedat = null;
            $completedBy = null;
            $signed_by = null;

            foreach ($tests as $test) {
                // Fetch the related TestReport with its results based on the report type
                $testReport = TestReport::with([
                    'biochemHaemoResults',
                    'cytologyGynecologyResults',
                    'urinalysisMicrobiologyResults'
                ])
                ->where('sample_id', $sample->id)
                ->where('test_id', $test->id)
                ->first();
                if(empty($testReport)){
                    continue;
                }

                if ($testReport) {
                    $testReports->push($testReport); // Store the TestReport for further use if needed

                    // Determine the completed status based on the report type
                    switch ($reporttype) {
                        case 1: // Biochemistry/Haematology Results
                            $hematologyStatus = $sample->departmentStatus('1');
                            $signed_by = $hematologyStatus->signed_by ?? null;
                            foreach ($testReport->biochemHaemoResults as $result) {
                                $completedBy = $result->completed_by ?? null;
                                $completedat = $result->completed_at ?? null;
                                if (!$result->is_completed) {
                                    $allTestsCompleted = false; // Set flag to false if any result is not completed
                                }
                            }
                            break;

                        case 2: // Cytology/Gynecology Results
                            $cytologyStatus = $sample->departmentStatus('2');
                            $signed_by = $cytologyStatus->signed_by ?? null;
                            foreach ($testReport->cytologyGynecologyResults as $result) {
                                $completedBy = $result->completed_by ?? null;
                                $completedat = $result->completed_at ?? null;
                                if (!$result->is_completed) {
                                    $allTestsCompleted = false; // Set flag to false if any result is not completed
                                }
                            }
                            break;

                        case 3: // Urinalysis/Microbiology Results
                            $urinalysisStatus = $sample->departmentStatus('3');
                            $signed_by = $urinalysisStatus->signed_by ?? null;
                            foreach ($testReport->urinalysisMicrobiologyResults as $result) {
                                $completedBy = $result->completed_by ?? null;
                                $completedat = $result->completed_at ?? null;
                                if (!$result->is_completed) {
                                    $allTestsCompleted = false; // Set flag to false if any result is not completed
                                }
                            }
                            break;

                        default:
                            // Handle other report types if necessary
                            $allTestsCompleted = false; // Set flag to false if the report type is not recognized
                            break;
                    }
                } else {
                    $allTestsCompleted = false; // Set flag to false if no TestReport is found
                }
            }


            // dd($allTestsCompleted);

            // If the department is 1, categorize the tests by their profiles
            $categorizedTests = [];
            $sampleProfiles = $sample->testProfiles->pluck('id')->toArray(); // Get profile IDs assigned to the sample

            if ($reporttype == '1' || $reporttype == '3') {
                // Get all sample profiles with subprofiles
                // dd($sampleProfiles);
                foreach ($profiles as $mainProfile) {
                    $mainProfileId = $mainProfile->id;
                    $mainProfileName = $mainProfile->name;

                    // Add main profile heading
                    $categorizedTests[$mainProfileId] = [
                        'name' => $mainProfileName,
                        'subprofiles' => [],
                        'tests' => [],
                    ];

                    // Get tests directly under main profile
                    foreach ($tests as $test) {

                        // dd($test->testProfiles);
                        if ($test->testProfiles->contains('id', $mainProfileId)) {
                            $categorizedTests[$mainProfileId]['tests'][] = $test;
                        }
                    }

                    // Handle subprofiles
                    $allSubProfiles = getSubProfilesRecursive($mainProfile);
                    // dd($allSubProfiles);
                    foreach ($allSubProfiles as $subProfile) {
                        if ($subProfile->departments->contains('department', $reporttype)) {

                            $subProfileId = $subProfile->id;
                            $subProfileName = $subProfile->name;
                            $categorizedTests[$mainProfileId]['subprofiles'][$subProfileId] = [
                                'name' => $subProfileName,
                                'specimen_type' => $subProfile->specimen_type?->name ?? null,
                                'tests' => [],
                            ];

                            foreach ($tests as $test) {
                                if ($test->testProfiles->contains('id', $subProfileId)) {
                                    $categorizedTests[$mainProfileId]['subprofiles'][$subProfileId]['tests'][] = $test;
                                }
                            }
                        }
                    }

                }

                // Handle tests with no profile
                foreach ($individualTests as $test) {
                    // if ($test->testProfiles->isEmpty()) {
                        $categorizedTests['no-profile']['name'] = 'Individual Tests';
                        $categorizedTests['no-profile']['subprofiles'] = [];
                        $categorizedTests['no-profile']['tests'][] = $test;
                    // }
                }
            }

            // dd($categorizedTests);



            // Calculate pagination
            $perPage = 20;
            $totalPages = ceil($tests->count() / $perPage);
            $currentPage = $request->input('page', 1);

            $referenceRanges = UrinalysisReferenceRanges::all()->keyBy('analyte');
            $signedUser = User::find($signed_by);
            $validatedUser = User::find($completedBy);

            $signedName = $signedUser
                ? trim(($signedUser->first_name ?? '') . ' ' . ($signedUser->surname ?? $signedUser->last_name ?? ''))
                : null;

            $validatedName = $validatedUser
                ? trim(($validatedUser->first_name ?? '') . ' ' . ($validatedUser->surname ?? $validatedUser->last_name ?? ''))
                : null;
            // Data for PDF view
            $data = [
                'title' => 'Border Life - LIS',
                'date' => date('m/d/Y'),
                'sample' => $sample,
                'testReports' => $testReports,
                'referenceRanges' => $referenceRanges,
                'tests' => $tests,
                'categorizedTests' => $categorizedTests,
                'totalPages' => $totalPages,
                'currentPage' => $currentPage,
                'reporttype' => $reporttype,
                'signed_by' => $signedName, // Replace with actual data
                'validated_by' => $validatedName, // Replace with actual data
            ];

            // Load the view based on $type
            $viewMapping = [
                '1' => 'pdf.biochemHaemoPdf', // Biochemistry / Haematology
                '2' => 'pdf.cytologyGynecologyPdf', // Cytology / Gynecology
                '3' => 'pdf.urinalysisMicrobiologyPdf', // Urinalysis / Microbiology
            ];

            if (!array_key_exists($reporttype, $viewMapping)) {
                return response()->json(['error' => 'Invalid report type'], 400);
            }

            $view = $viewMapping[$reporttype];
            $qrCode = $this->generateQRCode('https://borderlifemedlab.com/');
            $data['qrCode'] = $qrCode;
            // Generate PDF using Dompdf
            $pdf = PDF::loadView($view, $data);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            // (Optional) Set paper size and orientation
            $pdf->setPaper('Letter', 'portrait');

            // Stream the generated PDF file to the browser
            return $pdf->stream('Report.pdf');

        } catch (\Exception $e) {
            return response()->json(['error' => 'PDF generation failed: ' . $e->getMessage()], 500);
        }
    }


}
