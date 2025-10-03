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
        // dd($tests);

        $notes = $query->paginate(10);
        return view('setup.testProfiles',compact('notes','tests'));
    }

    public function store(Request $request)
    {
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
        }
        $testprofile->tests()->attach($request->tests);

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
            'note' => $note,
            'profiledepartment' => $profiledepartment,
            'profiletests' => $profiletests,
        ]);
    }

    public function update(Request $request, $id)
    {
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
        }

        $testprofile->tests()->detach();
        $testprofile->tests()->attach($request->tests);

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
