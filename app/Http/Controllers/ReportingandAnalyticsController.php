<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sample;
use Carbon\Carbon;

class ReportingandAnalyticsController extends Controller
{
    //


public function index(Request $request) {
    $date_from = $request->input('date_from');
    $date_to = $request->input('date_to');

    $samplesQuery = Sample::query();

    if ($date_from && $date_to) {
        $samplesQuery->whereBetween('collected_date', [$date_from, $date_to]);
    }

    $samples = $samplesQuery->get();

    // Calculate time taken and format it for display
    $processedSamples = $samples->map(function ($sample) {
        $submitted_at = Carbon::parse($sample->collected_date . ' ' . $sample->received_time);
        $results_entered_at = $sample->completed_at ? Carbon::parse($sample->completed_at) : null;

        $time_taken = $results_entered_at ? $submitted_at->diffInHours($results_entered_at) : null;

        return [
            'test_number' => $sample->test_number,
            'submitted_at' => $submitted_at->format('Y-m-d H:i:s'),
            'results_entered_at' => $results_entered_at ? $results_entered_at->format('Y-m-d H:i:s') : null,
            'time_taken' => $time_taken,
        ];
    });

    return view('reports.reporting&analytics.processingtime', compact('processedSamples', 'date_from', 'date_to'));
}

}
