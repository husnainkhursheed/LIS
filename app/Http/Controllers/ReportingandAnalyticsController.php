<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Test;
use App\Models\Sample;
use App\Models\AuditTrail;
use App\Models\TestReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BiochemHaemoResults;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TimeTakenReportExport;
use App\Models\CytologyGynecologyResults;
use App\Models\UrinalysisMicrobiologyResults;

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
            $departmentSummary = [];

            $samples->transform(function ($sample) {
                // Fetch individual tests with direct department association
                $individualTests = $sample->tests()->get();

                // Get departments for individual tests directly from the 'tests' table
                $individualTestDepartments = $individualTests->pluck('department')->unique();

                // Initialize collection for profile-related tests
                $profileTests = collect();
                $profileDepartments = collect();

                // Fetch profile tests and their departments
                foreach ($sample->testProfiles as $profile) {
                    $profileTests = $profileTests->merge($profile->tests()->get());

                    // Fetch profile departments from the relationship (ensure profile->departments exists)
                    if ($profile->departments) {
                        $profileDepartments = $profileDepartments->merge(
                            $profile->departments->pluck('department')
                        );
                    }
                }

                // Merge individual and profile-related departments
                $allDepartments = $individualTestDepartments->merge($profileDepartments)->unique();



                // Merge individual and profile tests
                $tests = $individualTests->merge($profileTests);
                // Group tests by department and check if all tests in each department are completed
                $departmentsStatus = $allDepartments->mapWithKeys(function ($department) use ($tests, $sample) {
                    // Filter tests for the current department
                    $departmentTests = $tests->filter(function ($test) use ($department) {
                        // Ensure the test and its profile's departments are properly checked
                        return $test->department === $department ||
                            $test->testProfiles->contains(function ($testProfile) use ($department) {
                                return $testProfile->departments->contains('department', $department);
                            });
                    });
                    $departmentTestsReports = TestReport::where('sample_id', $sample->id)
                    ->whereIn('test_id', $departmentTests->pluck('id'))
                    ->get();
                    // Determine completion status for each department by looking at the appropriate result table
                    $isCompleted = false;
                    switch ($department) {
                        case '2':
                            $completedat = CytologyGynecologyResults::whereIn('test_report_id', $departmentTestsReports->pluck('id'))
                            ->where('is_completed', true)
                            ->first();
                            $completedat = $completedat ? $completedat->completed_at : null;
                            $isCompleted = CytologyGynecologyResults::whereIn('test_report_id', $departmentTestsReports->pluck('id'))
                                ->where('is_completed', true)
                                ->count() == $departmentTests->count();
                            break;
                        case '1':
                            $completedat = BiochemHaemoResults::whereIn('test_report_id', $departmentTestsReports->pluck('id'))
                            ->where('is_completed', true)
                            ->first();
                            $completedat = $completedat ? $completedat->completed_at : null;

                            $isCompleted = BiochemHaemoResults::whereIn('test_report_id', $departmentTestsReports->pluck('id'))
                                ->where('is_completed', true)
                                ->count() == $departmentTests->count();
                            break;
                        case '3':
                            $completedat = UrinalysisMicrobiologyResults::whereIn('test_report_id', $departmentTestsReports->pluck('id'))
                            ->where('is_completed', true)
                            ->first();
                            $completedat = $completedat ? $completedat->completed_at : null;
                            $testscount = $departmentTests->filter(function ($test) {
                                return $test->urin_test_type !== null;
                            });
                            $isCompleted = UrinalysisMicrobiologyResults::whereIn('test_report_id', $departmentTestsReports->pluck('id'))
                                ->where('is_completed', true)
                                ->count() == $testscount->count();
                            break;
                    }

                    return [
                        $department => [
                            'is_completed' => $isCompleted,
                            'completedat' => $completedat,
                        ],
                    ];
                });

                // Store unique department statuses
                $sample->unique_departments = $allDepartments;
                $sample->unique_departments_status = $departmentsStatus;

                return $sample;
            });

            // dd($samples);
            foreach ($samples as $sample) {
                foreach ($sample->unique_departments as $department) {
                    // Initialize the summary for this department if not already done
                    if (!isset($departmentSummary[$department])) {
                        $departmentSummary[$department] = [
                            'total_completed' => 0,
                            'total_pending' => 0,
                            'total_outliers' => 0,
                            'outliers_percentage' => 0,
                            'avg_days' => 0,
                            'total_days' => 0,
                        ];
                    }


                }
                foreach ($sample->unique_departments_status as $index => $sampl){
                    // dd($sampl['completedat']);
                    $submittedDateTime = Carbon::parse($sample->received_date . ' ' . $sample->received_time);
                    $completedDateTime = $sampl['completedat'] ? Carbon::parse($sampl['completedat']) : null;

                    $timeTaken = $submittedDateTime->diff($completedDateTime);
                    // dd($timeTaken->days);
                    $daysTaken = $timeTaken->days;
                    $totalDays += $daysTaken;
                    $outlier = $daysTaken > 10; // Define outlier condition as required
                    if ($outlier) {
                        $departmentSummary[$index]['total_outliers']++;
                    }
                    $departmentSummary[$index]['total_days'] = $totalDays;

                    // $departmentSummary[$index]['total_tests']++;
                    if ($sampl['is_completed']) {
                        $departmentSummary[$index]['total_completed']++;
                    } else {
                        $departmentSummary[$index]['total_pending']++;
                    }


                    $reportData[] = [
                        'Department' => $index,
                        'Test ID' => $sample->test_number,
                        'Access # ' => $sample->access_number,
                        'Date Received' => $sample->received_date,
                        'Date Completed' => $sampl['is_completed'] ? $sampl['completedat'] : 'N/A',
                        'In Progress' => !$sampl['is_completed'] ? 'TRUE' : 'FALSE',
                        'Number of Days' => $daysTaken
                    ];
                }
                foreach ($sample->unique_departments as $department) {
                    // Initialize the summary for this department if not already done
                    $departmentSummary[$department]['avg_days'] = $departmentSummary[$department]['total_completed'] ? $departmentSummary[$department]['total_days']  / $departmentSummary[$department]['total_completed'] : 0;
                    $departmentSummary[$department]['outliers_percentage'] = $departmentSummary[$department]['total_completed'] ? ($departmentSummary[$department]['total_outliers'] / $departmentSummary[$department]['total_completed']) * 100 : 0;



                }
            }
            $grouped = collect($reportData)->groupBy('Department');
            // dd($grouped->toArray() , $departmentSummary);

            // // Display the grouped data
            // dd($grouped->toArray());


            // foreach ($samples as $sample) {






            //     $test = Test::find($sample->test_id); // Assuming sample has a test_id foreign key to tests table
            //     $submittedDateTime = Carbon::parse($sample->received_date . ' ' . $sample->received_time);
            //     $completedDateTime = $sample->completed_at ? Carbon::parse($sample->completed_at) : null;

            //     if ($completedDateTime) {
            //         $timeTaken = $submittedDateTime->diff($completedDateTime);
            //         $daysTaken = $timeTaken->days;
            //         $totalDays += $daysTaken;
            //         $completedCount++;
            //         $outlier = $daysTaken > 10; // Define outlier condition as required
            //         if ($outlier) {
            //             $outliersCount++;
            //         }

            //         $reportData[] = [
            //             // 'department' => $test->department,
            //             'test_number' => $sample->test_number,
            //             'access_number' => $sample->access_number,
            //             // 'test_name' => $test->name,
            //             'received_date' => $sample->received_date,
            //             'completed_date' => $sample->completed_at,
            //             'in_progress' => 'FALSE',
            //             'number_of_days' => $daysTaken
            //         ];
            //     } else {
            //         $pendingCount++;

            //         $reportData[] = [
            //             // 'department' => $test->department,
            //             'test_number' => $sample->test_number,
            //             'access_number' => $sample->access_number,
            //             // 'test_name' => $test->name,
            //             'received_date' => $sample->received_date,
            //             'completed_date' => 'N/A',
            //             'in_progress' => 'TRUE',
            //             'number_of_days' => 'N/A'
            //         ];
            //     }
            // }

            // $avgDays = $completedCount ? $totalDays / $completedCount : 0;
            // $outliersPercentage = $completedCount ? ($outliersCount / $completedCount) * 100 : 0;

            $summary = [
                'total_days' => $totalDays,
                'avg_days' => 0,
                'total_outliers' => $outliersCount,
                'outliers_percentage' => 0,
                'total_completed' => $completedCount,
                'total_pending' => $pendingCount,
            ];
            $processedSamples = $reportData;

            if ($request->has('export') && $request->input('export') == 'excel') {
                return Excel::download(new TimeTakenReportExport($grouped->toArray(), $startDate, $endDate, $departmentSummary), 'time_taken_report.xlsx');
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
        // dd($sample);
        // $sample->tests;

        $individualTests = $sample->tests()->get();

        // Initialize collection for profile-related tests
        $profileTests = collect();

        // Fetch profile tests and their departments
        foreach ($sample->testProfiles as $profile) {
            $profileTests = $profileTests->merge($profile->tests()->get());
        }

        // Merge individual and profile tests
        $tests = $individualTests->merge($profileTests);

        $departmentTests = $tests->filter(function ($test) use ($reporttype) {
            // Ensure the test and its profile's departments are properly checked
            return $test->department === $reporttype ||
                $test->testProfiles->contains(function ($testProfile) use ($reporttype) {
                    return $testProfile->departments->contains('department', $reporttype);
                });
        });

        // dd($departmentTests);

        // $test = Test::findOrFail($request->test_charges);
        // $tests = $sample->tests()->where('department', $reporttype)->get();
        // dd($tests);

        $testReports = collect();
        foreach ($departmentTests as $test) {
            $testReport = TestReport::where('sample_id', $sample->id)
                ->where('test_id', $test->id)
                ->first();
            $testReports->push($testReport->id);
        }
        // dd($testReports);

        switch ($reporttype) {
            case '1':
                $entriesIds = BiochemHaemoResults::whereIn('test_report_id', $testReports)->pluck('id');
                break;
            case '2':
                $entriesIds = CytologyGynecologyResults::whereIn('test_report_id', $testReports)->pluck('id');
                break;
            case '3':
                $entriesIds = UrinalysisMicrobiologyResults::whereIn('test_report_id', $testReports)->pluck('id');
                break;

            default:
                # code...
                break;
        }
        // dd($entriesIds);
        $auditTrailEntries = AuditTrail::whereIn('test_report_id', $entriesIds)->get();
        // dd($records);
        // Filter and keep only unique entries based on changed_at timestamp
        $records = $auditTrailEntries->unique('test_report_id');
        // dd($records);
        return view('reports/test-reports.auditTraits', compact('records','sample'));
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
