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

            $tests = $sample->tests()->where('department', $type)->get();

            $testReports = collect();
            // Define the relationship mappings
            $relationMapping = [
                '1' => 'biochemHaemoResults', // Biochemistry / Haematology
                '2' => 'cytologyGynecologyResults', // Cytology / Gynecology
                '3' => 'urinalysisMicrobiologyResults', // Urinalysis / Microbiology
            ];

            if (!array_key_exists($type, $relationMapping)) {
                return response()->json(['error' => 'Invalid report type'], 400);
            }

            $relationship = $relationMapping[$type];

            foreach ($tests as $test) {
                $testReport = TestReport::with($relationship)
                    ->where('sample_id', $sample->id)
                    ->where('test_id', $test->id)
                    ->first();

                if ($testReport) {
                    $testReports->push($testReport);
                }
            }

            $data = [
                'title' => 'Border Life - LIS',
                'date' => date('m/d/Y'),
                'sample' => $sample,
                'testReports' => $testReports,
                'tests' => $tests,
            ];
            $viewMapping = [
                '1' => 'pdf.biochemHaemoPdf', // Biochemistry / Haematology
                '2' => 'pdf.cytologyGynecologyPdf', // Cytology / Gynecology
                '3' => 'pdf.urinalysisMicrobiologyPdf', // Urinalysis / Microbiology
            ];

            if (!array_key_exists($type, $viewMapping)) {
                return response()->json(['error' => 'Invalid report type'], 400);
            }

            $view = $viewMapping[$type];
            $pdf = PDF::loadView($view, $data);

            return $pdf->stream('Report.pdf');

        } catch (\Exception $e) {
            return response()->json(['error' => 'PDF generation failed: ' . $e->getMessage()], 500);
        }
    }


}
