<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        $test = new Test();
        $test->name  = $request->input('name');
        $test->department  = $request->input('department');
        $test->specimen_type  = $request->input('specimen_type');
        $test->cost  = $request->input('cost');
        $test->reference_range  = $request->input('reference_range');
        $test->save();

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

        $test = Test::find($id);
        $test->name  = $request->input('name');
        $test->department  = $request->input('department');
        $test->specimen_type  = $request->input('specimen_type');
        $test->cost  = $request->input('cost');
        $test->reference_range  = $request->input('reference_range');
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
