<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Models\Sample;
use App\Models\TestReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestReportController extends Controller
{
    public function index()
    {
        $testReports = Sample::orderBy('received_date', 'asc')->get();
        return view('reports/test-reports.index', compact('testReports'));
    }

    public function search(Request $request)
    {
        $query = Sample::query();

        if ($request->filled('test_number')) {
            $query->where('test_number', $request->test_number);
        }

        if ($request->filled('access_number')) {
            $query->where('access_number', $request->access_number);
        }

        if ($request->filled('patient_name')) {
            $nameParts = explode(' ', $request->patient_name);
            $query->whereHas('patient', function ($q) use ($nameParts) {
                $q->where('surname', $nameParts[0])
                  ->where('first_name', $nameParts[1] ?? '');
            });
        }

        $testReports = $query->get();
        // dd($samples);

        return view('reports/test-reports.index', compact('testReports'));
    }

    public function getreportforedit(Request $request, $id){
        $sample = Sample::findOrFail($id);

        // Find or create a test report for the selected test and sample
        $testReport = TestReport::firstOrCreate(
            ['sample_id' => $sample->id]
            // ['is_completed' => false, 'is_signed' => false]
        );

        // $sample = Sample::find($id);
        $reporttype = $request->report_type;
        // dd($reporttypeid);
        return view('reports/test-reports.edit', compact('sample','reporttype'));
    }
}
