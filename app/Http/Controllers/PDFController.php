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
                'signed_by' => 'Dr. John Doe', // Replace with actual data
                'validated_by' => 'Admin User', // Replace with actual data
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

            // (Optional) Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');

            // Stream the generated PDF file to the browser
            return $pdf->stream('Report.pdf');

        } catch (\Exception $e) {
            return response()->json(['error' => 'PDF generation failed: ' . $e->getMessage()], 500);
        }
    }


}
