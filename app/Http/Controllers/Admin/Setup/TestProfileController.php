<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\Test;
use App\Models\TestProfile;
use Illuminate\Http\Request;
use App\Models\ProfileFormula;
use App\Models\ProfileDepartment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class TestProfileController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Notes access', ['only' => ['index','store','edit','update','destroy']]);
    }

    public function index(Request $request)
    {
        $query = TestProfile::query();
        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('cost', 'like', '%' . $searchTerm . '%');

            });
        }

        // Handle sorting
        if ($request->has('sort_by')) {
            $sortOrder = $request->input('sort_order') ?? 'asc'; // Default to ascending if not specified
            $query->orderBy($request->input('sort_by'), $sortOrder);
        }
        $tests = Test::where('is_active', true)->get();
        $specimens = \App\Models\SpecimenType::all();
        $profiles = \App\Models\TestProfile::all();
        // dd($tests);

        $notes = $query->paginate(10);
        return view('setup.testProfiles',compact('notes','tests','profiles', 'specimens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'code' => 'required',
            'name' => 'required',
            'cost' => 'required',
            'department' => 'required',
       ]);
        //    dd($request->input('tests'));

        $testprofile = new TestProfile();
        // $testprofile->code  = $request->input('code');
        $testprofile->name  = $request->input('name');
        $testprofile->cost  = $request->input('cost');
        $testprofile->specimentype_id  = $request->input('specimen_type') ?? null;
        // $testprofile->save();
        if($testprofile->save()) {
            foreach($request->department as $department) {
                ProfileDepartment::create([
                    'test_profile_id' => $testprofile->id,
                    'department' => $department,
                ]);
            }

            // Attach sub-profiles
            $testprofile->subProfiles()->detach();
            foreach ($request->sub_profiles as $subprofile) {
                $testprofile->subProfiles()->attach($subprofile);
            }
        }

        // Attach tests with order preservation
        // $order = 1;
        // $testOrder = [];
        // foreach ($request->tests as $testId) {
        //     $testOrder[$testId] = ['order' => $order++];
        // }

        // dd($testOrder);
        // $orderedTests = explode(',', $request->input('ordered_tests'));
        // dd($orderedTests);
        if ($request->has('tests')) {
            foreach ($request->tests as $testId) {
                $testprofile->tests()->attach($testId); // maintains your order
            }
        }


        if ($request->has('formulas') && !empty($request->formulas)) {
            $formulas = json_decode($request->formulas, true);

            foreach ($formulas as $formulaData) {
                ProfileFormula::create([
                    'profile_id' => $testprofile->id,
                    'calculated_test_id' => $formulaData['calculated_test_id'],
                    'formula' => $formulaData['formula'],
                    'calculation_order' => $formulaData['calculation_order'] ?? 1,
                ]);
            }
        }
        // dd($testprofile->tests);

        Session::flash('message', 'Created successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $note = TestProfile::find($id);
        $profiledepartment = ProfileDepartment::where('test_profile_id', $note->id)->get();
        $profiletests = $note->tests()->get();
        // dd($profiletests);

        $formulas = $note->profileFormulas->map(function($formula) {
            return [
                'calculated_test_id' => $formula->calculated_test_id,
                'formula' => $formula->formula,
                'calculation_order' => $formula->calculation_order,
            ];
        });


        return response()->json([
            'note' => array_merge($note->toArray(), [
                'sub_profiles' => $note->subProfiles()->pluck('test_profiles.id')->toArray(),
            ]),
            'formulas' => $formulas,
            'profiledepartment' => $profiledepartment,
            'profiletests' => $profiletests,
        ]);
    }

    public function fetchTestsFromProfiles(Request $request)
    {
        $profileIds = $request->input('profile_ids', []);
        $testIds = \App\Models\TestProfile::whereIn('id', $profileIds)
            ->with('tests')
            ->get()
            ->flatMap(function($profile) {
                return $profile->tests->pluck('id');
            })
            ->unique()
            ->values();

        return response()->json(['test_ids' => $testIds]);
    }

    /**
     * Fetch tests that belong to the given specimen type(s).
     * Accepts specimen_type (single id) or specimen_type[] array in POST.
     */
    public function fetchTestsBySpecimen(Request $request)
    {
        $specimenIds = $request->input('specimen_type');

        // If no specimen selected, return all active tests
        if (empty($specimenIds)) {
            $tests = \App\Models\Test::where('is_active', true)
                ->get(['id', 'name']);

            return response()->json(['tests' => $tests]);
        }

        // if (is_null($specimenIds)) {
        //     return response()->json(['tests' => []]);
        // }

        // Normalize to array
        if (!is_array($specimenIds)) {
            $specimenIds = [$specimenIds];
        }

        // Query tests that have specimen_type in the provided ids and are active
        $tests = \App\Models\Test::whereIn('specimen_type', $specimenIds)
            ->where('is_active', true)
            ->get(['id', 'name']);

        return response()->json(['tests' => $tests]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->input('tests'));
        $request->validate([
            // 'code' => 'required',
            'name' => 'required',
            'cost' => 'required',
       ]);


        $testprofile = TestProfile::find($id);
        // $testprofile->code  = $request->input('code');
        $testprofile->name  = $request->input('name');
        $testprofile->cost  = $request->input('cost');
        $testprofile->specimentype_id  = $request->input('specimen_type') ?? null;

        // $note->update();
        // dd($request->department);
        if($testprofile->update()) {
            ProfileDepartment::where('test_profile_id', $testprofile->id)->delete();
            foreach($request->department as $department) {
                ProfileDepartment::create([
                    'test_profile_id' => $testprofile->id,
                    'department' => $department,
                ]);
            }
            $testprofile->subProfiles()->detach();
            foreach ($request->sub_profiles as $subprofile) {
                $testprofile->subProfiles()->attach($subprofile);
            }
            // $testprofile->subProfiles()->sync($request->input('sub_profiles', []));

        }

        $testprofile->tests()->detach();
        if ($request->has('tests')) {
            foreach ($request->tests as $testId) {
                $testprofile->tests()->attach($testId); // maintains your order
            }
        }

        ProfileFormula::where('profile_id', $id)->delete();

        if ($request->has('formulas') && !empty($request->formulas)) {
            $formulas = json_decode($request->formulas, true);

            foreach ($formulas as $formulaData) {
                ProfileFormula::create([
                    'profile_id' => $id,
                    'calculated_test_id' => $formulaData['calculated_test_id'],
                    'formula' => $formulaData['formula'],
                    'calculation_order' => $formulaData['calculation_order'] ?? 1,
                ]);
            }
        }
        // dd($testprofile->tests);
        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();

    }

    public function destroy($id)
    {
        $note = TestProfile::find($id);
        $note->delete();
        Session::flash('message', 'Deleted successfully!');
        Session::flash('alert-class', 'alert-success');
    }
}
