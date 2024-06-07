<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Models\Test;
use App\Models\Sample;
use App\Models\TestReport;
use Illuminate\Http\Request;
use App\Models\BiochemHaemoResults;
use App\Http\Controllers\Controller;
use App\Models\CytologyGynecologyResults;

class TestReportController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Manage TestReports', ['only' => ['index','edit','getreportforedit','saveReports']]);
    }
    public function index(Request $request)
    {
        $testNumber = $request->input('test_number');
        $accessNumber = $request->input('access_number');
        $patientName = $request->input('patient_name');
       $query = Sample::query()->orderBy('received_date', 'asc');


        if ($request->filled('test_number')) {
            $query->where('test_number', $request->test_number);
        }

        if ($request->filled('access_number')) {
            $query->where('access_number', $request->access_number);
        }

        if ($request->filled('patient_name')) {
            $searchTerm = $request->input('patient_name');
            $query->whereHas('patient', function ($query) use ($searchTerm) {
                $query->where('surname', 'like', '%' . $searchTerm . '%')
                      ->orWhere('first_name', 'like', '%' . $searchTerm . '%');
            });
        }

        $testReports = $query->paginate(10);
        return view('reports/test-reports.index', compact('testReports','testNumber', 'accessNumber', 'patientName'));
    }

    public function edit($id)
    {
        $sample = Sample::with('tests')->findOrFail($id);
        // dd($sample);
        return response()->json([
            'sample' => $sample,
        ]);
    }

    public function getreportforedit(Request $request, $id){
        $sample = Sample::findOrFail($id);
        $test = Test::findOrFail($request->test_charges);
        // dd($test);
        // Find or create a test report for the selected test and sample
        $testReport = TestReport::firstOrCreate(
            ['sample_id' => $sample->id,'test_id' => $test->id]
            // ['is_completed' => false, 'is_signed' => false]
        );

        // dd($testReport->BiochemHaemoResults);

        // $sample = Sample::find($id);
        $reporttype = $request->report_type;
        // $contraceptivedropdown = CustomDropdown::where('dropdown_name', 'Contraceptive')->get();

        return view('reports/test-reports.edit', compact('sample','reporttype','test','testReport'));
    }

    public function saveReports(Request $request)
    {
        // Gather data from the request
        $data = $request->all();
        // dd($data);
        // Check if the report type is 1
        if ($data['reporttype'] == 1) {
            // Save the data into BiochemHaemoResults table
            BiochemHaemoResults::updateOrCreate(
                ['test_report_id' => $data['testReport']], // Condition to check
                [ // Data to update or create
                    'reference' => $data['reference'],
                    'note' => $data['note'],
                    'description' => $data['description'],
                    'test_results' => $data['test_results'],
                    'flag' => $data['flag'],
                    'reference_range' => $data['reference_range'],
                    'test_notes' => $data['test_notes']
                ]
            );
        }
        if ($data['reporttype'] == 2) {
            // Save the data into BiochemHaemoResults table
            CytologyGynecologyResults::updateOrCreate(
                ['test_report_id' => $data['testReport']], // Condition to check
                [ // Data to update or create
                    'history' => $data['history'],
                    'last_period' => $data['last_period'],
                    'contraceptive' => $data['contraceptive'],
                    'result' => $data['result'],
                    'previous_pap' => $data['previous_pap'],
                    'cervix_examination' => $data['cervix_examination'],
                    'specimen_adequacy' => $data['specimen_adequacy'],
                    'diagnostic_interpretation' => $data['diagnostic_interpretation'],
                    'recommend' => $data['recommend']
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Saved successfully!',
            'alert-class' => 'alert-success',
        ]);
    }
}
