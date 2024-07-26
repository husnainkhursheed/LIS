<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Test;
use App\Models\Sample;
use App\Models\AuditTrail;
use App\Models\TestReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TimeTakenReportExport;

class ReportingandAnalyticsController extends Controller
{
    //


    public function index(Request $request) {
        $startDate = $request->input('date_from');
        $endDate = $request->input('date_to');

        // Validate the date range
        // $request->validate([
        //     'start_date' => 'required|date',
        //     'end_date' => 'required|date|after_or_equal:start_date',
        // ]);

        // Fetch the samples within the date range
            $samples = Sample::whereBetween('received_date', [$startDate, $endDate])->get();

            $reportData = [];
            $totalDays = 0;
            $completedCount = 0;
            $outliersCount = 0;
            $pendingCount = 0;

            foreach ($samples as $sample) {
                $test = Test::find($sample->test_id); // Assuming sample has a test_id foreign key to tests table
                $submittedDateTime = Carbon::parse($sample->received_date . ' ' . $sample->received_time);
                $completedDateTime = $sample->completed_at ? Carbon::parse($sample->completed_at) : null;

                if ($completedDateTime) {
                    $timeTaken = $submittedDateTime->diff($completedDateTime);
                    $daysTaken = $timeTaken->days;
                    $totalDays += $daysTaken;
                    $completedCount++;
                    $outlier = $daysTaken > 10; // Define outlier condition as required
                    if ($outlier) {
                        $outliersCount++;
                    }

                    $reportData[] = [
                        // 'department' => $test->department,
                        'test_number' => $sample->test_number,
                        'access_number' => $sample->access_number,
                        // 'test_name' => $test->name,
                        'received_date' => $sample->received_date,
                        'completed_date' => $sample->completed_at,
                        'in_progress' => 'FALSE',
                        'number_of_days' => $daysTaken
                    ];
                } else {
                    $pendingCount++;

                    $reportData[] = [
                        // 'department' => $test->department,
                        'test_number' => $sample->test_number,
                        'access_number' => $sample->access_number,
                        // 'test_name' => $test->name,
                        'received_date' => $sample->received_date,
                        'completed_date' => 'N/A',
                        'in_progress' => 'TRUE',
                        'number_of_days' => 'N/A'
                    ];
                }
            }

            $avgDays = $completedCount ? $totalDays / $completedCount : 0;
            $outliersPercentage = $completedCount ? ($outliersCount / $completedCount) * 100 : 0;

            $summary = [
                'total_days' => $totalDays,
                'avg_days' => $avgDays,
                'total_outliers' => $outliersCount,
                'outliers_percentage' => $outliersPercentage,
                'total_completed' => $completedCount,
                'total_pending' => $pendingCount,
            ];
            $processedSamples = $reportData;

            if ($request->has('export') && $request->input('export') == 'excel') {
                return Excel::download(new TimeTakenReportExport($reportData, $startDate, $endDate, $summary), 'time_taken_report.xlsx');
            }

            return view('reports.reporting&analytics.processingtime', compact('processedSamples','reportData', 'startDate', 'endDate', 'summary'));

        // $samplesQuery = Sample::query();

        // if ($date_from && $date_to) {
        //     $samplesQuery->whereBetween('received_date', [$date_from, $date_to]);
        // }

        // $samples = $samplesQuery->get();

        // // Calculate time taken and format it for display
        // $processedSamples = $samples->map(function ($sample) {
        //     $submitted_at = Carbon::parse($sample->received_date . ' ' . $sample->received_time);
        //     $results_entered_at = $sample->completed_at ? Carbon::parse($sample->completed_at) : null;

        //     $time_taken_days = $results_entered_at ? $submitted_at->diffInDays($results_entered_at) : null;
        //     $time_taken_hours = $results_entered_at ? $submitted_at->diffInHours($results_entered_at) : null;

        //     return [
        //         'test_number' => $sample->test_number,
        //         'submitted_at' => $submitted_at->format('Y-m-d H:i:s'),
        //         'results_entered_at' => $results_entered_at ? $results_entered_at->format('Y-m-d H:i:s') : null,
        //         'time_taken_days' => $time_taken_days,
        //         'time_taken_hours' => $time_taken_hours,
        //     ];
        // });

        // return view('reports.reporting&analytics.processingtime', compact('processedSamples', 'date_from', 'date_to'));
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
