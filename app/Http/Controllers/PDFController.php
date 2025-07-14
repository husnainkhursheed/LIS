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

            // Get the profiles associated with the sample
            $profiles = $sample->testProfiles()->whereHas('departments', function($query) use ($reporttype) {
                $query->where('department', $reporttype);
            })->with('tests')->get();

            // Collect all the tests from the profiles that match the department
            $profileTests = collect();
            foreach ($profiles as $profile) {
                // Add tests from each profile that match the department
                $profileTests = $profileTests->merge(
                    $profile->tests()->get()
                );
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
                foreach ($tests as $test) {
                    // Check if there are any test profiles assigned to the test
                    if ($test->testProfiles->isNotEmpty()) {
                        // Loop through the test profiles and filter them based on the sample's profiles
                        foreach ($test->testProfiles as $profile) {
                            if (in_array($profile->id, $sampleProfiles)) {
                                $profileId = $profile->id;
                                $profileName = $profile->name;
                                $categorizedTests[$profileId]['name'] = $profileName;
                                $categorizedTests[$profileId]['tests'][] = $test;
                            }
                        }
                    } else {
                        // Handle the case where there is no profile assigned to the test
                        $profileId = 'no-profile';
                        $profileName = 'Individual Tests';
                        $categorizedTests[$profileId]['name'] = $profileName;
                        $categorizedTests[$profileId]['tests'][] = $test;
                    }
                }
            }



            // Calculate pagination
            $perPage = 20;
            $totalPages = ceil($tests->count() / $perPage);
            $currentPage = $request->input('page', 1);

            $referenceRanges = UrinalysisReferenceRanges::all()->keyBy('analyte');
            // $signed_by = $sample::with('signedBy','validateBy')->get();
            // foreach ($signed_by as $sample) {
            //     // Access the signed user's name
            //     $signedUserName = $sample->signedBy->first_name ?? 'No Signer';
            //     $validateUserName = $sample->validateBy->first_name ?? 'No Validator';
            //     // dd($signedUserName);
            // }
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
                'signed_by' => optional(User::find($signed_by))->first_name, // Replace with actual data
                'validated_by' => optional(User::find($completedBy))->first_name, // Replace with actual data
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
            $qrCode = $this->generateQRCode('https://20.dev.webberz.com/');
            $data['qrCode'] = $qrCode;
            // Generate PDF using Dompdf
            $pdf = PDF::loadView($view, $data);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            // (Optional) Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');

            // Stream the generated PDF file to the browser
            return $pdf->stream('Report.pdf');

        } catch (\Exception $e) {
            return response()->json(['error' => 'PDF generation failed: ' . $e->getMessage()], 500);
        }
    }


}
