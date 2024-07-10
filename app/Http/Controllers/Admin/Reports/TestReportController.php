<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Models\Test;
use App\Models\Sample;
use App\Models\TestReport;
use Illuminate\Http\Request;
use App\Models\CustomDropdown;
use App\Models\BiochemHaemoResults;
use App\Models\SensitivityProfiles;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Note;
use App\Models\CytologyGynecologyResults;
use App\Models\UrinalysisMicrobiologyResults;
use App\Models\AuditTrail;

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
            $testReport = TestReport::with('biochemHaemoResults')
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

        $senstivityprofiles = SensitivityProfiles::with('sensitivityValues')->get();

        return view('reports/test-reports.edit', compact('sample','reporttype','tests','testReports','contraceptivedropdown','bilirubinropdown','blooddropdown','leucocytesdropdown','glucosedropdown','nitritedropdown','ketonesdropdown','urobilinogendropdown','proteinsdropdown','bacteriadropdown','senstivityprofiles'));
    }

    public function getsensitivityitems(Request $request)
    {
        $profileIds = $request->input('profile_ids');
        $profiles = SensitivityProfiles::with('sensitivityValues')
            ->whereIn('id', $profileIds)
            ->get();

        return response()->json($profiles);
    }


    public function saveReports(Request $request)
    {
        // Gather data from the request
        $data = $request->all();
        $user = Auth::user();

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
                $result =  BiochemHaemoResults::updateOrCreate(
                    ['test_report_id' => $testReport->id]
                );
                // Condition to check
                    // Capture original values
                    $originalValues = $result->getOriginal();

                   // Update or create the result
                    $result->reference = $data['reference'] ?? $result->reference;
                    $result->note = $data['note'] ?? $result->note;
                    $result->description = $testData['description'] ?? $result->description;
                    $result->test_results = $testData['test_results'] ?? $result->test_results;
                    $result->flag = $testData['flag'] ?? $result->flag;
                    $result->reference_range = $testData['reference_range'] ?? $result->reference_range;
                    $result->test_notes = $testData['test_notes'] ?? $result->test_notes;
                    $result->save();
                // );
                $changes = [];
                foreach ($result->getChanges() as $field => $newValue) {
                    if (array_key_exists($field, $originalValues)) {
                        $changes[$field] = [
                            'from' => $originalValues[$field],
                            'to' => $newValue,
                        ];
                    }
                }
                // dd($changes);
                $this->addAuditTrail($testReport, $user, $changes);

            }
            // dd($changes);
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

                );
                // dd($testReport);

                $result =  CytologyGynecologyResults::updateOrCreate(
                    ['test_report_id' => $testReport->id],
                );
                $originalValues = $result->getOriginal();

                $result->history = $data['history'] ?? null;
                $result->last_period = $data['last_period'] ?? null;
                $result->contraceptive = $data['contraceptive'] ?? null;
                $result->result = $data['result'] ?? null;
                $result->previous_pap = $data['previous_pap'] ?? null;
                $result->cervix_examination = $data['cervix_examination'] ?? null;
                $result->specimen_adequacy = $data['specimen_adequacy'] ?? null;
                $result->diagnostic_interpretation = $data['diagnostic_interpretation'] ?? null;
                $result->recommend = $data['recommend'] ?? null;
                $result->save();

                $changes = [];
                foreach ($result->getChanges() as $field => $newValue) {
                    if (array_key_exists($field, $originalValues)) {
                        $changes[$field] = [
                            'from' => $originalValues[$field],
                            'to' => $newValue,
                        ];
                    }
                }
                // dd($changes);
                $this->addAuditTrail($testReport, $user, $changes);
            }
        }

        if ($data['reporttype'] == 3) {
            $test_ids = explode(',', $data['testIds']);
            // dd($data['sensitivity_profiles']);
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
                UrinalysisMicrobiologyResults::updateOrCreate(
                    ['test_report_id' => $testReport->id], // Condition to check
                    [
                        'history' => $data['history'] ?? null,
                        's_gravity'=> $data['s_gravity'] ?? null,
                        'ph'=> $data['ph'] ?? null,
                        'bilirubin'=> $data['bilirubin'] ?? null,
                        'blood'=> $data['blood'] ?? null,
                        'leucocytes'=> $data['leucocytes'] ?? null,
                        'glucose'=> $data['glucose'] ?? null,
                        'nitrite'=> $data['nitrite'] ?? null,
                        'ketones'=> $data['ketones'] ?? null,
                        'urobilinogen'=> $data['urobilinogen'] ?? null,
                        'proteins'=> $data['proteins'] ?? null,
                        'colour'=> $data['colour'] ?? null,
                        'appearance'=> $data['appearance'] ?? null,
                        'epith_cells'=> $data['epith_cells'] ?? null,
                        'bacteria'=> $data['bacteria'] ?? null,
                        'white_cells'=> $data['white_cells'] ?? null,
                        'yeast'=> $data['yeast'] ?? null,
                        'red_cells'=> $data['red_cells'] ?? null,
                        'trichomonas'=> $data['trichomonas'] ?? null,
                        'casts'=> $data['casts'] ?? null,
                        'crystals'=> $data['crystals'] ?? null,
                        'specimen'=> $data['specimen'] ?? null,
                        'procedure'=> $data['procedure'] ?? null,
                        'sensitivity'=> $data['sensitivity'] ?? null,
                        'specimen_note'=> $data['specimen_note'] ?? null,
                        'sensitivity_profiles'=> $data['sensitivity_profiles'] ?? null,

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

    /**
     * Add an entry to the audit trail.
     *
     * @param TestReport $testReport
     * @param User $user
     * @param array $changes
     * @return void
     */
    protected function addAuditTrail(TestReport $testReport, $user, array $changes)
    {
        foreach ($changes as $field => $values) {
            AuditTrail::create([
                'test_report_id' => $testReport->id,
                'user_id' => $user->id,
                'changed_at' => now(),
                'field_name' => $field,
                'from_value' => $values['from'],
                'to_value' => $values['to'],
            ]);
        }
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
        // dd($request->report_sample_id);

        // Find the test report
        $testReport = Sample::find($request->report_sample_id);

        // Check if the report is already signed
        if ($testReport->signed_by) {
            // Fetch the user who signed the report
            $signedByUser = Sample::find($testReport->signed_by);
            return response()->json(['error' => 'Report already signed by ' . $signedByUser->first_name . ' on ' . $testReport->signed_at], 400);
        }

        // Update the test-reports table with the user ID in the signed_by column
        $testReport->is_signed = true;
        $testReport->signed_by = $user->id;
        $testReport->signed_at = now();
        $testReport->save();

        // AuditTrail::create([
        //     'test_report_id' => $testReport->id,
        //     'user_id' => $user->id,
        //     'changed_at' => now(),
        //     'field_name' => $field,
        //     'from_value' => $values['from'],
        //     'to_value' => $values['to'],
        // ]);

        return response()->json([
            'success' => 'Report signed successfully.',
            'user' => $user,
            'sample' => $testReport,
        ]);
    }

    public function fetchNotesCytology()
    {
        //Cytology / Gynecology
        $notesCytology = Note::where('department',2)->get();
        return response()->json($notesCytology);
    }
    public function fetchNotesUrinalysis()
    {
        //Urinalysis
        $notesUrinalysis = Note::where('department',3)->get();

        return response()->json($notesUrinalysis);
    }

    public function completetest(Request $request){
        $user = Auth::user();
        $testReport = Sample::find($request->sample_id);

        // Check if the report is already signed
        // if ($testReport->signed_by) {
        //     // Fetch the user who signed the report
        //     $signedByUser = Sample::find($testReport->signed_by);
        //     return response()->json(['error' => 'Report already signed by ' . $signedByUser->first_name . ' on ' . $testReport->signed_at], 400);
        // }

        // Update the test-reports table with the user ID in the signed_by column
        $testReport->is_completed = true;
        $testReport->completed_by = $user->id;
        $testReport->completed_at = now();
        $testReport->save();

        return response()->json([
            'success' => 'Report Completed successfully.',
            'sample' => $testReport,
        ]);
    }



    // audit traits
    public function auditTraits(Request $request)
    {

        return view('reports/test-reports.auditTraits');
    }
}
