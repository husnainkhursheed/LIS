<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sample;
use App\Models\AuditTrail;
use App\Models\TestReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportingandAnalyticsController extends Controller
{
    //


    public function index(Request $request) {
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');

        $samplesQuery = Sample::query();

        if ($date_from && $date_to) {
            $samplesQuery->whereBetween('received_date', [$date_from, $date_to]);
        }

        $samples = $samplesQuery->get();

        // Calculate time taken and format it for display
        $processedSamples = $samples->map(function ($sample) {
            $submitted_at = Carbon::parse($sample->received_date . ' ' . $sample->received_time);
            $results_entered_at = $sample->completed_at ? Carbon::parse($sample->completed_at) : null;

            $time_taken_days = $results_entered_at ? $submitted_at->diffInDays($results_entered_at) : null;
            $time_taken_hours = $results_entered_at ? $submitted_at->diffInHours($results_entered_at) : null;

            return [
                'test_number' => $sample->test_number,
                'submitted_at' => $submitted_at->format('Y-m-d H:i:s'),
                'results_entered_at' => $results_entered_at ? $results_entered_at->format('Y-m-d H:i:s') : null,
                'time_taken_days' => $time_taken_days,
                'time_taken_hours' => $time_taken_hours,
            ];
        });

        return view('reports.reporting&analytics.processingtime', compact('processedSamples', 'date_from', 'date_to'));
    }

    public function auditTrails($id , $reporttype){
        //
        // dd($reporttype);
        $reporttype = $reporttype;
        $sample = Sample::findOrFail($id);
        // $sample->tests;

        // $test = Test::findOrFail($request->test_charges);
        $tests = $sample->tests()->where('department', $reporttype)->get();
        // dd($tests);

        $testReports = collect();
        foreach ($tests as $test) {
            $testReport = TestReport::where('sample_id', $sample->id)
                ->where('test_id', $test->id)
                ->first();
            $testReports->push($testReport->id);
        }
        // dd($testReports);

        $auditTrailEntries = AuditTrail::whereIn('test_report_id', $testReports)->get();
        // dd($auditTrailEntries);
        // Filter and keep only unique entries based on changed_at timestamp
        $records = $auditTrailEntries->unique('changed_at');
        // dd($records);
        return view('reports/test-reports.auditTraits', compact('records'));
    }

    public function trailchanges(Request $request , $id){
        // dd($request->changedat);
        $changes_made = AuditTrail::where('test_report_id', $id)->where('changed_at',$request->changedat)->get();
        // dd($changes_made);

        return response()->json([
            'changes_made' => $changes_made,
        ]);

    }

}
