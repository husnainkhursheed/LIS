<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Models\Test;
use App\Models\Sample;
use App\Models\TestReport;
use Illuminate\Http\Request;
use App\Models\CustomDropdown;
use App\Models\BiochemHaemoResults;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\CytologyGynecologyResults;
use App\Models\Note;

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
       $currentUser = Auth::user();
        if ($currentUser->hasRole('Lab')) {
            // Filter samples by the current user's departments through the related tests
            $departmentIds = $currentUser->departments;
            $query->whereHas('tests', function($testQuery) use ($departmentIds) {
                $testQuery->whereIn('department', $departmentIds);
            });
        }
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
        $testReports->getCollection()->transform(function ($sample) {
            $sample->unique_departments = $sample->tests->pluck('department')->unique();
            return $sample;
        });
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
        $reporttype = $request->report_type;
        $sample = Sample::findOrFail($id);
        $sample->tests;

        // $test = Test::findOrFail($request->test_charges);
        $tests = $sample->tests()->where('department', $reporttype)->get();
        // dd($tests);
        // Find or create a test report for the selected test and sample
        // Collect test reports with their related results
        $testReports = collect(); // Initialize as a collection
        foreach ($tests as $test) {
            $testReport = TestReport::with('cytologyGynecologyResults')
                ->where('sample_id', $sample->id)
                ->where('test_id', $test->id)
                ->first();

            $testReports->push($testReport); // Add to the collection
        }
        // dd($testReports);
        // dd($testReport->BiochemHaemoResults);

        // $sample = Sample::find($id);
        // $reporttype = $request->report_type;
        $contraceptivedropdown = CustomDropdown::where('dropdown_name', 'Contraceptive')->get();
        $bilirubinropdown = CustomDropdown::where('dropdown_name', 'Bilirubin')->get();
        // $contraceptivedropdown = CustomDropdown::where('dropdown_name', 'Bilirubin')->get();
        $blooddropdown = CustomDropdown::where('dropdown_name', 'Blood')->get();
        $leucocytesdropdown = CustomDropdown::where('dropdown_name', 'Leucocytes')->get();
        $glucosedropdown = CustomDropdown::where('dropdown_name', 'Glucose')->get();
        $nitritedropdown = CustomDropdown::where('dropdown_name', 'Nitrite')->get();
        $ketonesdropdown = CustomDropdown::where('dropdown_name', 'Ketones')->get();
        $urobilinogendropdown = CustomDropdown::where('dropdown_name', 'Urobilinogen')->get();
        $proteinsdropdown = CustomDropdown::where('dropdown_name', 'Proteins')->get();
        $bacteriadropdown = CustomDropdown::where('dropdown_name', 'Bacteria')->get();

        return view('reports/test-reports.edit', compact('sample','reporttype','tests','testReports','contraceptivedropdown','bilirubinropdown','blooddropdown','leucocytesdropdown','glucosedropdown','nitritedropdown','ketonesdropdown','urobilinogendropdown','proteinsdropdown','bacteriadropdown'));
    }

    public function saveReports(Request $request)
    {
        // Gather data from the request
        $data = $request->all();
        // dd($data);
        // Check if the report type is 1
        if ($data['reporttype'] == 1) {
            foreach ($data['testsData'] as $testId => $testData) {
                // Find or create the test report
                $testReport = TestReport::firstOrCreate(
                    [
                        'sample_id' => $data['sampleid'],
                        'test_id' => $testId
                    ]
                    // [
                    //     'is_completed' => false,
                    //     'is_signed' => false
                    // ]
                );

                // Save the data into BiochemHaemoResults table
                BiochemHaemoResults::updateOrCreate(
                    ['test_report_id' => $testReport->id], // Condition to check
                    [
                        'reference' => $data['reference'] ?? null,
                        'note' => $data['note'] ?? null,
                        'description' => $testData['description'] ?? null,
                        'test_results' => $testData['test_results'] ?? null,
                        'flag' => $testData['flag'] ?? null,
                        'reference_range' => $testData['reference_range'] ?? null,
                        'test_notes' => $testData['test_notes'] ?? null
                    ]
                );
            }
        }

        if ($data['reporttype'] == 2) {
            $test_ids = explode(',', $data['testIds']);
            // dd($test_ids);
            foreach ($test_ids as $testId ) {
                // Find or create the test report
                $testReport = TestReport::firstOrCreate(
                    [
                        'sample_id' => $data['sampleid'],
                        'test_id' => $testId
                    ]
                    // [
                    //     'is_completed' => false,
                    //     'is_signed' => false
                    // ]
                );
                // dd($testReport);

                // Save the data into BiochemHaemoResults table
                CytologyGynecologyResults::updateOrCreate(
                    ['test_report_id' => $testReport->id], // Condition to check
                    [ // Data to update or create
                        'history' => $data['history'] ?? null,
                        'last_period' => $data['last_period'] ?? null,
                        'contraceptive' => $data['contraceptive'] ?? null,
                        'result' => $data['result'] ?? null,
                        'previous_pap' => $data['previous_pap'] ?? null,
                        'cervix_examination' => $data['cervix_examination'] ?? null,
                        'specimen_adequacy' => $data['specimen_adequacy'] ?? null,
                        'diagnostic_interpretation' => $data['diagnostic_interpretation'] ?? null,
                        'recommend' => $data['recommend'] ?? null
                    ]
                );
                // CytologyGynecologyResults::updateOrCreate(
                //     ['test_report_id' => $data['testReport']], // Condition to check
                //     [ // Data to update or create
                //         'history' => $data['history'],
                //         'last_period' => $data['last_period'],
                //         'contraceptive' => $data['contraceptive'],
                //         'result' => $data['result'],
                //         'previous_pap' => $data['previous_pap'],
                //         'cervix_examination' => $data['cervix_examination'],
                //         'specimen_adequacy' => $data['specimen_adequacy'],
                //         'diagnostic_interpretation' => $data['diagnostic_interpretation'],
                //         'recommend' => $data['recommend']
                //     ]
                // );
            }
            // Save the data into BiochemHaemoResults table
            // CytologyGynecologyResults::updateOrCreate(
            //     ['test_report_id' => $data['testReport']], // Condition to check
            //     [ // Data to update or create
            //         'history' => $data['history'],
            //         'last_period' => $data['last_period'],
            //         'contraceptive' => $data['contraceptive'],
            //         'result' => $data['result'],
            //         'previous_pap' => $data['previous_pap'],
            //         'cervix_examination' => $data['cervix_examination'],
            //         'specimen_adequacy' => $data['specimen_adequacy'],
            //         'diagnostic_interpretation' => $data['diagnostic_interpretation'],
            //         'recommend' => $data['recommend']
            //     ]
            // );
        }

        return response()->json([
            'success' => true,
            'message' => 'Saved successfully!',
            'alert-class' => 'alert-success',
        ]);
    }


    public function signReport(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check if the provided username matches the logged-in user's username
        $user = Auth::user();
        if ($user->email !== $request->email) {
            return response()->json(['error' => 'Email does not match the logged-in user.'], 401);
        }

        // Check if the provided password matches the logged-in user's password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Password is incorrect.'], 401);
        }

        // Find the test report
        $testReport = TestReport::find($request->test_report_id);

        // Check if the report is already signed
        if ($testReport->signed_by) {
            // Fetch the user who signed the report
            $signedByUser = TestReport::find($testReport->signed_by);
            return response()->json(['error' => 'Report already signed by ' . $signedByUser->name . ' on ' . $testReport->updated_at->format('d-m-Y')], 400);
        }

        // Update the test-reports table with the user ID in the signed_by column
        $testReport->signed_by = $user->id;
        $testReport->save();

        return response()->json(['success' => 'Report signed successfully.']);
    }
    public function fetchNotesCytology()
    {
        //Cytology / Gynecology
        $notesCytology = Note::where('department',2)->pluck('note_code'); // Adjust this query to match your data structure

        return response()->json($notesCytology);
    }
    public function fetchNotesUrinalysis()
    {
        //Urinalysis
        $notesUrinalysis = Note::where('department',3)->pluck('note_code'); // Adjust this query to match your data structure

        return response()->json($notesUrinalysis);
    }


    public function delinktest(Request $request, $id){
        $sample_id = $request->sample_id;
        $sample = Sample::find($sample_id);
        $sample->tests()->detach($id);

        $testReport = TestReport::where([
            'sample_id' => $sample_id,
            'test_id' => $id
        ])->first();
        if($testReport) {
            $testReport->delete();
        }




        return response()->json([
            'success' => true,
            'message' => 'Detached successfully!',
            'alert-class' => 'alert-success',
        ]);
    }

}
