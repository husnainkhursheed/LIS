<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\Test;
use App\Models\Sample;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TestController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:TestCharges access', ['only' => ['index','store','edit','update','destroy']]);
    }
    public function index(Request $request)
    {
        $query = Test::query();

        $currentUser = Auth::user();
        if ($currentUser->hasRole('Lab')) {
            // Filter tests by the current user's departments
            $departmentIds = $currentUser->departments;
            $query->whereIn('department', $departmentIds);
        }

        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('department', 'like', '%' . $searchTerm . '%')
                      ->orWhere('specimen_type', 'like', '%' . $searchTerm . '%')
                      ->orWhere('cost', 'like', '%' . $searchTerm . '%')
                      ->orWhere('reference_range', 'like', '%' . $searchTerm . '%');

            });
        }

        // Handle sorting
        if ($request->has('sort_by')) {
            $sortOrder = $request->input('sort_order') ?? 'asc'; // Default to ascending if not specified
            $query->orderBy($request->input('sort_by'), $sortOrder);
        }

        $tests = $query->paginate(10);

        return view('setup.tests',compact('tests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'department' => 'required',
            'specimen_type' => 'required',
            'cost' => 'required',
            'reference_range' => 'required',
       ]);
       $reference_range = $request->input('reference_range');
        $test = new Test();
        $test->name  = $request->input('name');
        $test->department  = $request->input('department');
        $test->specimen_type  = $request->input('specimen_type');
        $test->cost  = $request->input('cost');
        $test->reference_range  = $request->input('reference_range');
        if($reference_range == 'basic_ref'){
            $test->basic_low_value_ref_range  = $request->input('basic_low_value_ref_range');
            $test->basic_high_value_ref_range  = $request->input('basic_high_value_ref_range');
            $test->male_low_value_ref_range  = null;
            $test->male_high_value_ref_range  = null;
            $test->female_low_value_ref_range  = null;
            $test->female_high_value_ref_range  = null;
        }else if($reference_range == 'optional_ref'){
            $test->male_low_value_ref_range  = $request->input('male_low_value_ref_range');
            $test->male_high_value_ref_range  = $request->input('male_high_value_ref_range');
            $test->female_low_value_ref_range  = $request->input('female_low_value_ref_range');
            $test->female_high_value_ref_range  = $request->input('female_high_value_ref_range');
            $test->basic_low_value_ref_range  = null;
            $test->basic_high_value_ref_range  = null;
        }
        $test->is_active  = $request->has('is_active') ? 1 : 0;
        $test->save();

        if ($request->ajax()) {
            $sample = Sample::find($request->sample_id);
            $sample->tests()->attach($test);

            return response()->json(['success' => true, 'test' => $test]);
        }

        Session::flash('message', 'Created successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $tests = Test::find($id);
        return response()->json([
            'tests' => $tests,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'department' => 'required',
            'specimen_type' => 'required',
            'cost' => 'required',
            'reference_range' => 'required',
       ]);
        $reference_range = $request->input('reference_range');

        $test = Test::find($id);
        $test->name  = $request->input('name');
        $test->department  = $request->input('department');
        $test->specimen_type  = $request->input('specimen_type');
        $test->cost  = $request->input('cost');
        $test->reference_range  = $request->input('reference_range');
        if($reference_range == 'basic_ref'){
            $test->basic_low_value_ref_range  = $request->input('basic_low_value_ref_range');
            $test->basic_high_value_ref_range  = $request->input('basic_high_value_ref_range');
            $test->male_low_value_ref_range  = null;
            $test->male_high_value_ref_range  = null;
            $test->female_low_value_ref_range  = null;
            $test->female_high_value_ref_range  = null;
        }else if($reference_range == 'optional_ref'){
            $test->male_low_value_ref_range  = $request->input('male_low_value_ref_range');
            $test->male_high_value_ref_range  = $request->input('male_high_value_ref_range');
            $test->female_low_value_ref_range  = $request->input('female_low_value_ref_range');
            $test->female_high_value_ref_range  = $request->input('female_high_value_ref_range');
            $test->basic_low_value_ref_range  = null;
            $test->basic_high_value_ref_range  = null;
        }
        $test->is_active  = $request->has('is_active') ? 1 : 0;
        $test->update();


        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();

    }

    public function destroy($id)
    {
        $test = Test::find($id);
        $test->delete();
        Session::flash('message', 'Deleted successfully!');
        Session::flash('alert-class', 'alert-success');
    }
}
