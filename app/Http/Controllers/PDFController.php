<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sample;
use App\Models\TestReport;
use Illuminate\Http\Request;
use App\Exports\ReportsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UrinalysisReferenceRanges;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PDFController extends Controller
{

    public function generateQRCode($data)
    {
        $qr = QrCode::size(300)->generate($data);

        return $qr;
    }

    public function generatePDF(Request $request, $sample_id, $type)
    {
        try {
            // Fetch sample and related tests
            $sample = Sample::findOrFail($sample_id);
            $sample->load('tests');

            $tests = $sample->tests()->where('department', $type)->get();

            // Load test reports
            $testReports = collect();
            foreach ($tests as $test) {
                $testReport = TestReport::with('biochemHaemoResults')
                    ->where('sample_id', $sample->id)
                    ->where('test_id', $test->id)
                    ->first();

                if ($testReport) {
                    $testReports->push($testReport);
                }
            }

            // Calculate pagination
            $perPage = 20;
            $totalPages = ceil($tests->count() / $perPage);
            $currentPage = $request->input('page', 1);

            $referenceRanges = UrinalysisReferenceRanges::all()->keyBy('analyte');
            $signed_by = $sample::with('signedBy','validateBy')->get();
            foreach ($signed_by as $sample) {
                // Access the signed user's name
                $signedUserName = $sample->signedBy->first_name ?? 'No Signer';
                $validateUserName = $sample->validateBy->first_name ?? 'No Validator';
                // dd($signedUserName);
            }
                        // Data for PDF view
            $data = [
                'title' => 'Border Life - LIS',
                'date' => date('m/d/Y'),
                'sample' => $sample,
                'testReports' => $testReports,
                'referenceRanges' => $referenceRanges,
                'tests' => $tests,
                'totalPages' => $totalPages,
                'currentPage' => $currentPage,
                'signed_by' => $signedUserName, // Replace with actual data
                'validated_by' => $validateUserName, // Replace with actual data
            ];

            // Load the view based on $type
            $viewMapping = [
                '1' => 'pdf.biochemHaemoPdf', // Biochemistry / Haematology
                '2' => 'pdf.cytologyGynecologyPdf', // Cytology / Gynecology
                '3' => 'pdf.urinalysisMicrobiologyPdf', // Urinalysis / Microbiology
            ];

            if (!array_key_exists($type, $viewMapping)) {
                return response()->json(['error' => 'Invalid report type'], 400);
            }

            $view = $viewMapping[$type];
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


    public function export()
    {
        $data = [
            ['','5E81C1', 'BR-1235', 'Blood Work', '02-Jul-2024', '07-Jul-2024', '', 5],
            ['','52D5C0', 'AR-254', 'Chemistry', '02-Jul-2024', '', 'TRUE', 7],
            ['','62E562', 'BR-5685', 'Blood Work', '05-Jul-2024', '12-Jul-2024', '', 7],
            ['','5D96E2', 'CL-587', 'Fractional', '07-Jul-2024', '10-Jul-2024', '', 3],
            ['','68R1Y8', 'CL-125', 'Analysis', '08-Jul-2024', '11-Jul-2024', '', 3],
            ['','75D26E', 'AR-658', 'Fractional', '10-Jul-2024', '15-Jul-2024', '', 5],
            ['','12GR28', 'CL-658', 'Diarlivel', '11-Jul-2024', '', 'TRUE', 5],
            ['Total # of Days:','','','','','','','23'],
            ['Avg # of Days:','','','','','','','7.67'],
            ['Total Outliers:','','','','','','','2'],
            ['Outliers (%):','','','','','','','40'],
            ['Total Tests: Completed:','','','','','','','6'],
            ['Total Tests: Pending:','','','','','','','2'],
        ];

        return Excel::download(new ReportsExport($data), 'reports.xlsx');
    }

}
