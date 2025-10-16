<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\Test;
use App\Models\TestProfile;
use Illuminate\Http\Request;
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
                $query->where('code', 'like', '%' . $searchTerm . '%')
                      ->orWhere('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('cost', 'like', '%' . $searchTerm . '%');

            });
        }

        // Handle sorting
        if ($request->has('sort_by')) {
            $sortOrder = $request->input('sort_order') ?? 'asc'; // Default to ascending if not specified
            $query->orderBy($request->input('sort_by'), $sortOrder);
        }
        $tests = Test::where('is_active', true)->get();
        $profiles = \App\Models\TestProfile::all();
        // dd($tests);

        $notes = $query->paginate(10);
        return view('setup.testProfiles',compact('notes','tests','profiles'));
    }

    public function store(Request $request)
    {
        // dd($request->tests);
        // dd($request->all());
        $request->validate([
            // 'code' => 'required',
            'name' => 'required',
            'cost' => 'required',
            'department' => 'required',
       ]);

        $testprofile = new TestProfile();
        // $testprofile->code  = $request->input('code');
        $testprofile->name  = $request->input('name');
        $testprofile->cost  = $request->input('cost');
        // $testprofile->save();
        if($testprofile->save()) {
            foreach($request->department as $department) {
                ProfileDepartment::create([
                    'test_profile_id' => $testprofile->id,
                    'department' => $department,
                ]);
            }

            // Attach sub-profiles
            if ($request->has('sub_profiles')) {
                $testprofile->subProfiles()->sync($request->input('sub_profiles'));
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
            $testprofile->tests()->attach($request->input('tests'));
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
        $profiletests = $note->tests;
        // dd($profiletests);


        return response()->json([
            'note' => array_merge($note->toArray(), [
                'sub_profiles' => $note->subProfiles->pluck('id')->toArray()
            ]),
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

            $testprofile->subProfiles()->sync($request->input('sub_profiles', []));
        }

        $testprofile->tests()->detach();
        if ($request->has('tests')) {
            $testprofile->tests()->attach($request->input('tests'));
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
