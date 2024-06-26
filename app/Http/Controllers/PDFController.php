<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sample;
use App\Models\TestReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function generatePDF1(Request $request, $sample_id)
    {
        $sample = Sample::where('id',$sample_id)->with('patient','tests','institution','doctor','testReports','signedBy')->firstOrFail();
        $data = [
            'title' => 'Border Life - LIS',
            'date' => date('m/d/Y'),
            'sample' => $sample
        ];
        // dd($sample);

        $pdf = PDF::loadView('pdf.usersPdf', $data);
        return $pdf->stream('users-list.pdf');
    }
    public function generatePDF(Request $request, $sample_id, $type)
    {
        try {
            $sample = Sample::findOrFail($sample_id);
            $sample->tests;

            // $test = Test::findOrFail($request->test_charges);
            $tests = $sample->tests()->where('department', $type)->get();
            // dd($tests);
            // Find or create a test report for the selected test and sample
            // Collect test reports with their related results
            $testReports = collect(); // Initialize as a collection
            foreach ($tests as $test) {
                $testReport = TestReport::with('biochemHaemoResults')
                    ->where('sample_id', $sample->id)
                    ->where('test_id', $test->id)
                    ->first();

                $testReports->push($testReport); // Add to the collection
            }

            // $sample = Sample::where('id', $sample_id)
            //     ->with('patient', 'tests', 'institution', 'doctor', 'testReports.biochemHaemoResults', 'signedBy')
            //     ->firstOrFail();
            $data = [
                'title' => 'Border Life - LIS',
                'date' => date('m/d/Y'),
                'sample' => $sample,
                'testReports' => $testReports,
                'tests' => $tests,
            ];
// dd($data);
            switch ($type) {
                case "3": // Urinalysis / Microbiology
                    $pdf = PDF::loadView('pdf.urinalysisMicrobiologyPdf', $data);

                    break;
                case "2": // Cytology / Gynecology
                    $pdf = PDF::loadView('pdf.cytologyGynecologyPdf', $data);
                    break;
                case "1": // Biochemistry / Haematology
                    $pdf = PDF::loadView('pdf.biochemHaemoPdf', $data);
                    break;
                default:
                    return response()->json(['error' => 'Invalid report type'], 400);
            }

            // Generate the PDF and return base64-encoded content
            return $pdf->stream('Report.pdf');

        } catch (\Exception $e) {
            return response()->json(['error' => 'PDF generation failed: ' . $e->getMessage()], 500);
        }
    }

}
