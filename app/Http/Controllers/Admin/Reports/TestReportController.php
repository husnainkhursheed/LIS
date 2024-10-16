<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Models\Note;
use App\Models\Test;
use App\Models\Sample;
use App\Models\AuditTrail;
use App\Models\TestReport;
use App\Models\TestProfile;
use Illuminate\Http\Request;
use App\Models\CustomDropdown;
use App\Models\ProcedureResults;
use App\Models\BiochemHaemoResults;
use App\Models\SensitivityProfiles;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\CytologyGynecologyResults;
use App\Models\UrinalysisReferenceRanges;
use App\Models\UrinalysisMicrobiologyResults;

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
                        $isCompleted = CytologyGynecologyResults::whereIn('test_report_id', $departmentTestsReports->pluck('id'))
                            ->where('is_completed', true)
                            ->count() == $departmentTests->count();
                        break;
                    case '1':
                        $isCompleted = BiochemHaemoResults::whereIn('test_report_id', $departmentTestsReports->pluck('id'))
                            ->where('is_completed', true)
                            ->count() == $departmentTests->count();
                        break;
                    case '3':
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
                    ],
                ];
            });

            // Store unique department statuses
            $sample->unique_departments = $allDepartments;
            $sample->unique_departments_status = $departmentsStatus;

            return $sample;
        });


        return view('reports/test-reports.index', compact('testReports', 'testNumber', 'accessNumber', 'patientName'));
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


        $individualTests = $sample->tests()->where('department', $reporttype)->get();
        // dd($individualTests);

        // Get the profiles associated with the sample
        $profiles = $sample->testProfiles()->whereHas('departments', function($query) use ($reporttype) {
            $query->where('department', $reporttype);
        })->with('tests')->get();
        // dd($profiles);

        // Collect all the tests from the profiles that match the department
        $profileTests = collect();
        foreach ($profiles as $profile) {
            // Add tests from each profile that match the department
            // dd($profile->tests->where('department', $reporttype));
            $profileTests = $profileTests->merge(
                $profile->tests,
            );
        }
        // dd($profileTests);

        // Combine individual tests and profile tests
        $tests = $individualTests->merge($profileTests);

        // dd($tests);

        // Collect test reports with their related results
        $testReports = collect(); // Initialize as a collection

        $allTestsCompleted = true; // Flag to check if all tests are completed
        $completedat = null;



        // foreach ($tests as $test) {
        //     // Fetch the related TestReport with its results based on the report type
        //     $testReport = TestReport::with([
        //         'biochemHaemoResults',
        //         'cytologyGynecologyResults',
        //         'urinalysisMicrobiologyResults'
        //     ])
        //     ->where('sample_id', $sample->id)
        //     ->where('test_id', $test->id)
        //     ->first();
            // dd($testReport);
            // if(empty($testReport)){
            //     continue;
            // }
            // if (!empty($testReport) && empty($testReport->urinalysisMicrobiologyResults->first())) {
            //     continue;
            // }

            $testReports = TestReport::where('sample_id', $sample->id)
            ->whereIn('test_id', $tests->pluck('id'))
            ->get();

            // if ($testReport) {
                 // Store the TestReport for further use if needed

                // Determine the completed status based on the report type
                switch ($reporttype) {
                    case 1: // Biochemistry/Haematology Results
                        // $testReports->push($departmentTestsReports);
                        $completedat = BiochemHaemoResults::whereIn('test_report_id', $testReports->pluck('id'))
                            ->where('is_completed', true)
                            ->first();
                            $completedat = $completedat ? $completedat->completed_at : null;

                        $allTestsCompleted = BiochemHaemoResults::whereIn('test_report_id', $testReports->pluck('id'))
                            ->where('is_completed', true)
                            ->count() == $tests->count();
                        break;

                    case 2: // Cytology/Gynecology Results
                        // $testReports->push($departmentTestsReports);
                        $completedat = CytologyGynecologyResults::whereIn('test_report_id', $testReports->pluck('id'))
                        ->where('is_completed', true)
                        ->first();
                        $completedat = $completedat ? $completedat->completed_at : null;

                        $allTestsCompleted = CytologyGynecologyResults::whereIn('test_report_id', $testReports->pluck('id'))
                            ->where('is_completed', true)
                            ->count() == $tests->count();
                        break;

                    case 3: // Urinalysis/Microbiology Results
                        // dd($testReport->urinalysisMicrobiologyResults->first());
                        // $testReports->push($departmentTestsReports);
                        $testscount = $tests->filter(function ($test) {
                            return $test->urin_test_type !== null;
                        });
                        $completedat = UrinalysisMicrobiologyResults::whereIn('test_report_id', $testReports->pluck('id'))
                        ->where('is_completed', true)
                        ->first();
                        // dd(UrinalysisMicrobiologyResults::whereIn('test_report_id', $testReports->pluck('id'))
                        // ->where('is_completed', true)
                        // ->count());
                        // dd($testscount);
                        $completedat = $completedat ? $completedat->completed_at : null;
                        $allTestsCompleted = UrinalysisMicrobiologyResults::whereIn('test_report_id', $testReports->pluck('id'))
                            ->where('is_completed', true)
                            ->count() == $testscount->count();
                        break;

                    default:
                        // $testReports->push($departmentTestsReports);
                        // Handle other report types if necessary
                        $allTestsCompleted = false; // Set flag to false if the report type is not recognized
                        break;
                }

            // } else {
            //     $allTestsCompleted = false; // Set flag to false if no TestReport is found
            // }
        // }


        // dd($testReports);

        // If the department is 1, categorize the tests by their profiles
        $categorizedTests = [];

        $sampleProfiles = $sample->testProfiles->pluck('id')->toArray(); // Get profile IDs assigned to the sample

        if ($reporttype == '1' || $reporttype == '3') {
            foreach ($tests as $test) {
                // Check if there are any test profiles assigned to the test
                if ($test->testProfiles->isNotEmpty()) {
                    // Loop through the test profiles and filter them based on the sample's profiles
                    foreach ($test->testProfiles as $profile) {
                        if (in_array($profile->id, $sampleProfiles)) {
                            $profileId = $profile->id;
                            $profileName = $profile->name;
                            $categorizedTests[$profileId]['name'] = $profileName;
                            $categorizedTests[$profileId]['tests'][] = $test;
                        }
                    }
                } else {
                    // Handle the case where there is no profile assigned to the test
                    $profileId = 'no-profile';
                    $profileName = 'Individual Tests';
                    $categorizedTests[$profileId]['name'] = $profileName;
                    $categorizedTests[$profileId]['tests'][] = $test;
                }
            }
        }

        // dd($categorizedTestss);


        $test_profiles = TestProfile::all();

        $contraceptivedropdown = CustomDropdown::where('dropdown_name', 'Contraceptive')->get();

        $senstivityprofiles = SensitivityProfiles::with('sensitivityValues')->get();

        $referenceRanges = UrinalysisReferenceRanges::all()->keyBy('analyte');

        return view('reports/test-reports.edit', compact('test_profiles','categorizedTests','completedat','allTestsCompleted','sample','reporttype','tests','testReports','contraceptivedropdown','senstivityprofiles','referenceRanges'));
    }

    public function getsensitivityitems(Request $request)
    {
        $profileIds = $request->input('profile_ids');
        $profiles = SensitivityProfiles::with('sensitivityValues')
            ->whereIn('id', $profileIds)
            ->get();

        return response()->json($profiles);
    }

    public function getProcedurePartial($procedure)
    {
        $procedureResult = new ProcedureResults();
        $procedureResult->procedure = $procedure;
        $procedureResult->specimen_note = '';
        $procedureResult->sensitivity_profiles = '';

        $senstivityprofiles = SensitivityProfiles::with('sensitivityValues')->get();
        return view('partials.procedures', compact('procedureResult','senstivityprofiles'))->render();
    }


    public function saveReports(Request $request)
    {
        // Gather data from the request
        $data = $request->all();
        // dd($data);
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
                // dd($result->id);
                foreach ($result->getChanges() as $field => $newValue) {
                    if (array_key_exists($field, $originalValues)) {
                        $changes[$field] = [
                            'from' => $originalValues[$field],
                            'to' => $newValue,
                        ];
                    }
                }
                // dd($changes);
                $this->addAuditTrail($result->id, $user, $changes);

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
            foreach ($data['testsData'] as $testId => $testData) {
                // Find or create the test report
                $testReport = TestReport::firstOrCreate(
                    [
                        'sample_id' => $data['sampleid'],
                        'test_id' => $testId
                    ]
                );

                // Save the data into BiochemHaemoResults table
                $result =  UrinalysisMicrobiologyResults::updateOrCreate(
                    ['test_report_id' => $testReport->id]
                );

                $originalValues = $result->getOriginal();
                // Condition to check
                // Capture original values
                // $originalValues = $result->getOriginal();

                // Update or create the result
                $result->sensitivity = $data['sensitivity'] ?? null;
                $result->sensitivity_profiles =  $data['sensitivity_profiles'] ?? null;
                $result->description = $testData['description'] ?? $result->description;
                $result->test_results = $testData['test_results'] ?? $result->test_results;
                $result->flag = $testData['flag'] ?? $result->flag;
                $result->reference_range = $testData['reference_range'] ?? $result->reference_range;
                $result->test_notes = $testData['test_notes'] ?? $result->test_notes;
                $result->save();
                // );
                if (isset($data['procedure']) && isset($data['specimen_note'])) {
                    $existingProcedureResults = ProcedureResults::where('urinalysis_microbiology_result_id', $result->id)->get();
                    $existingProcedureIds = $existingProcedureResults->pluck('id')->toArray();

                    $procedures = $data['procedure'];
                    // dd($procedures);
                    $specimen_notes = $data['specimen_note'];
                    // dd($specimen_notes);
                    $newProcedureIds = [];

                    foreach ($procedures as $index => $procedure) {
                        if (!empty($procedure)) {
                            $procedureNote = $specimen_notes[$index] ?? null;
                            // dd($result->id);
                            $procedureResult = ProcedureResults::updateOrCreate(
                                [
                                    'urinalysis_microbiology_result_id' => $result->id,
                                    'procedure' => $procedure,
                                ],
                                [

                                    'specimen_note' => $procedureNote
                                ]
                            );

                            $newProcedureIds[] = $procedureResult->id;
                        }
                    }

                    // Determine which procedures to delete
                    $procedureIdsToDelete = array_diff($existingProcedureIds, $newProcedureIds);

                    // Delete procedures that are not present in the request
                    ProcedureResults::whereIn('id', $procedureIdsToDelete)->delete();
                }
                $changes = [];
                foreach ($result->getChanges() as $field => $newValue) {
                    if (array_key_exists($field, $originalValues)) {
                        $changes[$field] = [
                            'from' => $originalValues[$field],
                            'to' => $newValue,
                        ];
                    }
                }

                $this->addAuditTrail($result->id, $user, $changes);

            }
            // $test_ids = explode(',', $data['testIds']);
            // foreach ($test_ids as $testId ) {
            //     // Find or create the test report
            //     $testReport = TestReport::firstOrCreate(
            //         [
            //             'sample_id' => $data['sampleid'],
            //             'test_id' => $testId
            //         ]
            //     );
            //     // Save the data into BiochemHaemoResults table
            //     $urinalysisMicrobiologyResult = UrinalysisMicrobiologyResults::updateOrCreate(
            //         ['test_report_id' => $testReport->id], // Condition to check
            //         [
            //             'history' => $data['history'] ?? null,
            //             's_gravity'=> $data['s_gravity'] ?? null,
            //             'ph'=> $data['ph'] ?? null,
            //             'bilirubin'=> $data['bilirubin'] ?? null,
            //             'blood'=> $data['blood'] ?? null,
            //             'leucocytes'=> $data['leucocytes'] ?? null,
            //             'glucose'=> $data['glucose'] ?? null,
            //             'nitrite'=> $data['nitrite'] ?? null,
            //             'ketones'=> $data['ketones'] ?? null,
            //             'urobilinogen'=> $data['urobilinogen'] ?? null,
            //             'proteins'=> $data['proteins'] ?? null,
            //             'colour'=> $data['colour'] ?? null,
            //             'appearance'=> $data['appearance'] ?? null,
            //             'epith_cells'=> $data['epith_cells'] ?? null,
            //             'bacteria'=> $data['bacteria'] ?? null,
            //             'white_cells'=> $data['white_cells'] ?? null,
            //             'yeast'=> $data['yeast'] ?? null,
            //             'red_cells'=> $data['red_cells'] ?? null,
            //             'trichomonas'=> $data['trichomonas'] ?? null,
            //             'casts'=> $data['casts'] ?? null,
            //             'crystals'=> $data['crystals'] ?? null,
            //             'sensitivity'=> $data['sensitivity'] ?? null,
            //             'sensitivity_profiles'=> $data['sensitivity_profiles'] ?? null,
            //         ]
            //     );

            //     // Now save the procedure results
            //     if (isset($data['procedure']) && isset($data['specimen_note'])) {
            //         $existingProcedureResults = ProcedureResults::where('urinalysis_microbiology_result_id', $urinalysisMicrobiologyResult->id)->get();
            //         $existingProcedureIds = $existingProcedureResults->pluck('id')->toArray();

            //         $procedures = $data['procedure'];
            //         // dd($procedures);
            //         $specimen_notes = $data['specimen_note'];
            //         // dd($specimen_notes);
            //         $newProcedureIds = [];

            //         foreach ($procedures as $index => $procedure) {
            //             if (!empty($procedure)) {
            //                 $procedureNote = $specimen_notes[$index] ?? null;
            //                 // dd($urinalysisMicrobiologyResult->id);
            //                 $procedureResult = ProcedureResults::updateOrCreate(
            //                     [
            //                         'urinalysis_microbiology_result_id' => $urinalysisMicrobiologyResult->id,
            //                         'procedure' => $procedure,
            //                     ],
            //                     [

            //                         'specimen_note' => $procedureNote
            //                     ]
            //                 );

            //                 $newProcedureIds[] = $procedureResult->id;
            //             }
            //         }

            //         // Determine which procedures to delete
            //         $procedureIdsToDelete = array_diff($existingProcedureIds, $newProcedureIds);

            //         // Delete procedures that are not present in the request
            //         ProcedureResults::whereIn('id', $procedureIdsToDelete)->delete();
            //     }
            // }
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
    protected function addAuditTrail($testReport, $user, array $changes)
    {
        // dd($testReport);
        foreach ($changes as $field => $values) {
            AuditTrail::create([
                'test_report_id' => $testReport,
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

        // Find the test report
        $sample = Sample::findOrFail($request->report_sample_id);
        $reporttypeis = $request->reporttypeis;
        // $tests = $sample->tests()->where('department', $reporttypeis)->pluck('id');
        $individualTests = $sample->tests()->where('department', $reporttypeis)->get();

        // Get the profiles associated with the sample
        $profiles = $sample->testProfiles()->whereHas('departments', function($query) use ($reporttypeis) {
            $query->where('department', $reporttypeis);
        })->with('tests')->get();

        // Collect all the tests from the profiles that match the department
        $profileTests = collect();
        foreach ($profiles as $profile) {
            // Add tests from each profile that match the department
            $profileTests = $profileTests->merge(
                $profile->tests()->get()
            );
        }

        // Combine individual tests and profile tests
        $tests = $individualTests->merge($profileTests);
        foreach ($tests as $testId ) {
            // Find or create the test report

            $testReport = TestReport::where('sample_id',$sample->id)->where('test_id',$testId->id)->first();
            if(empty($testReport)){
                continue;
            }

            switch ($reporttypeis) {
                case 1: // Biochemistry/Haematology Results
                    BiochemHaemoResults::where('test_report_id', $testReport->id)
                        ->update([
                            'is_signed' => true,
                            'signed_by' => $user->id,
                            'signed_at' => now(),
                        ]);
                    break;

                case 2: // Cytology/Gynecology Results
                    CytologyGynecologyResults::where('test_report_id', $testReport->id)
                        ->update([
                            'is_signed' => true,
                            'signed_by' => $user->id,
                            'signed_at' => now(),
                        ]);
                    break;

                case 3: // Urinalysis/Microbiology Results
                    UrinalysisMicrobiologyResults::where('test_report_id', $testReport->id)
                        ->update([
                            'is_signed' => true,
                            'signed_by' => $user->id,
                            'signed_at' => now(),
                        ]);
                    break;

                default:
                    // Handle other cases if needed
                    break;
            }
        }

        // Update the test-reports table with the user ID in the signed_by column
        // foreach ($testReports as $testReport) {
        //     // Check if the report is already signed
        //     // if ($testReport->is_signed) {
        //     //     return response()->json(['error' => 'One or more reports are already signed.'], 400);
        //     // }

        //     // Update the test report with the signed information
        //     $testReport->is_signed = true;
        //     $testReport->signed_by = $user->id;
        //     $testReport->signed_at = now();
        //     $testReport->save();
        // }

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
        $sample = Sample::find($request->sample_id);
        $reporttypeis = $request->reporttypeis;

        // $tests = $sample->tests()->where('department', $reporttypeis)->get();

        $individualTests = $sample->tests()->where('department', $reporttypeis)->get();

        // Get the profiles associated with the sample
        $profiles = $sample->testProfiles()->whereHas('departments', function($query) use ($reporttypeis) {
            $query->where('department', $reporttypeis);
        })->with('tests')->get();

        // dd($profiles);

        // Collect all the tests from the profiles that match the department
        $profileTests = collect();
        foreach ($profiles as $profile) {
            // Add tests from each profile that match the department
            $profileTests = $profileTests->merge(
                $profile->tests()->get()
            );
        }

        // Combine individual tests and profile tests
        $tests = $individualTests->merge($profileTests);
        // dd($tests);

        // $test_ids = $sample->tests()->where('department', $reporttypeis)->pluck('tests.id');
        // $testReport =  TestReport::where('sample_id', $sample->id)->whereIn('test_id', $test_ids)->get();
        // dd($testReport);


            // dd($tests);
        foreach ($tests as $testId ) {
            // Find or create the test report

            $testReport = TestReport::where('sample_id',$sample->id)->where('test_id',$testId->id)->first();
            if (empty($testReport)) {
                // session()->flash('alert', 'Test report for Test ID: ' . $testId->name . ' is empty . First Save the Report');
                session()->flash('alert', 'Some changes done on your report. Please save the Report first');
                continue;
            }
            //     [
            //         'sample_id' => $sample->id,
            //         'test_id' => $testId->id
            //     ],
            //     [
            //         'is_completed' => true,
            //         'completed_by' => $user->id,
            //         'completed_at' => now(),
            //     ]
            // );
            // Update the status in the appropriate results table based on reporttypeis
            switch ($reporttypeis) {
                case 1: // Biochemistry/Haematology Results
                    if (empty($testReport->biochemHaemoResults->first())) {
                        session()->flash('alert', 'Some changes done on your report. Please save the Report first');
                        break;
                    }
                    BiochemHaemoResults::where('test_report_id', $testReport->id)
                        ->update([
                            'is_completed' => true,
                            'completed_by' => $user->id,
                            'completed_at' => now(),
                        ]);
                    break;

                case 2: // Cytology/Gynecology Results
                    if (empty($testReport->cytologyGynecologyResults->first())) { 
                        session()->flash('alert', 'Some changes done on your report. Please save the Report first');
                        break;
                    }
                    CytologyGynecologyResults::where('test_report_id', $testReport->id)
                        ->update([
                            'is_completed' => true,
                            'completed_by' => $user->id,
                            'completed_at' => now(),
                        ]);
                    break;

                case 3: // Urinalysis/Microbiology Results
                    if (empty($testReport->urinalysisMicrobiologyResults->first())) {
                        session()->flash('alert', 'Some changes done on your report. Please save the Report first');
                        break;
                    }
                    UrinalysisMicrobiologyResults::where('test_report_id', $testReport->id)
                        ->update([
                            'is_completed' => true,
                            'completed_by' => $user->id,
                            'completed_at' => now(),
                        ]);
                    break;

                default:
                    // Handle other cases if needed
                    break;
            }
        }

        return response()->json([
            'success' => 'Report Completed successfully.',
            'sample' => $testReport,
        ]);
    }

    public function uncompletetest(Request $request){
        $user = Auth::user();
        $sample = Sample::find($request->sample_id);
        $reporttypeis = $request->reporttypeis;

        $individualTests = $sample->tests()->where('department', $reporttypeis)->get();

        // Get the profiles associated with the sample
        $profiles = $sample->testProfiles()->whereHas('departments', function($query) use ($reporttypeis) {
            $query->where('department', $reporttypeis);
        })->with('tests')->get();

        // dd($profiles);

        // Collect all the tests from the profiles that match the department
        $profileTests = collect();
        foreach ($profiles as $profile) {
            // Add tests from each profile that match the department
            $profileTests = $profileTests->merge(
                $profile->tests()->get()
            );
        }

        // Combine individual tests and profile tests
        $tests = $individualTests->merge($profileTests);
            // dd($test_ids);
        foreach ($tests as $testId ) {
            // Find or create the test report
            $testReport = TestReport::where('sample_id',$sample->id)->where('test_id',$testId->id)->first();
            if(empty($testReport)){
                continue;
            }

            //     [
            //         'sample_id' => $sample->id,
            //         'test_id' => $testId->id
            //     ],
            //     [
            //         'is_completed' => false,
            //         'completed_by' => null,
            //         'completed_at' => null,
            //         'is_signed' => false,
            //         'signed_by' => null,
            //         'signed_at' => null,
            //     ]
            // );
            switch ($reporttypeis) {
                case 1: // Biochemistry/Haematology Results
                    BiochemHaemoResults::where('test_report_id', $testReport->id)
                        ->update([
                            'is_completed' => false,
                            'completed_by' => null,
                            'completed_at' => null,
                            'is_signed' => false,
                            'signed_by' => null,
                            'signed_at' => null,
                        ]);
                    break;

                case 2: // Cytology/Gynecology Results
                    CytologyGynecologyResults::where('test_report_id', $testReport->id)
                        ->update([
                            'is_completed' => false,
                            'completed_by' => null,
                            'completed_at' => null,
                            'is_signed' => false,
                            'signed_by' => null,
                            'signed_at' => null,
                        ]);
                    break;

                case 3: // Urinalysis/Microbiology Results
                    UrinalysisMicrobiologyResults::where('test_report_id', $testReport->id)
                        ->update([
                            'is_completed' => false,
                            'completed_by' => null,
                            'completed_at' => null,
                            'is_signed' => false,
                            'signed_by' => null,
                            'signed_at' => null,
                        ]);
                    break;

                default:
                    // Handle other cases if needed
                    break;
            }
        }

        // Update the test-reports table with the user ID in the signed_by column
        // $testReport->is_completed = false;
        // $testReport->completed_by = null;
        // $testReport->completed_at = null;
        // $testReport->is_signed = false;
        // $testReport->signed_by = null;
        // $testReport->signed_at = null;
        // $testReport->save();

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
