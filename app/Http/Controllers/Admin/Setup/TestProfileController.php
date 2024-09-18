<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\TestProfiles;
use Illuminate\Http\Request;
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
        $query = TestProfiles::query();
        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($query) use ($searchTerm) {
                $query->where('code', 'like', '%' . $searchTerm . '%')
                      ->orWhere('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('specimen_type', 'like', '%' . $searchTerm . '%');

            });
        }

        // Handle sorting
        if ($request->has('sort_by')) {
            $sortOrder = $request->input('sort_order') ?? 'asc'; // Default to ascending if not specified
            $query->orderBy($request->input('sort_by'), $sortOrder);
        }

        $notes = $query->paginate(10);
        // $notes = TestProfiles::all();
        // $practices = Practice::paginate(10);
        return view('setup.testProfiles',compact('notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'specimen_type' => 'required',
       ]);

        $note = new TestProfiles();
        $note->code  = $request->input('code');
        $note->name  = $request->input('name');
        $note->specimen_type  = $request->input('specimen_type');
        $note->save();

        Session::flash('message', 'Created successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $note = TestProfiles::find($id);
        return response()->json([
            'note' => $note,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'specimen_type' => 'required',
       ]);


        $note = TestProfiles::find($id);
        $note->code  = $request->input('code');
        $note->name  = $request->input('name');
        $note->specimen_type  = $request->input('specimen_type');
        $note->update();


        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();

    }

    public function destroy($id)
    {
        $note = TestProfiles::find($id);
        $note->delete();
        Session::flash('message', 'Deleted successfully!');
        Session::flash('alert-class', 'alert-success');
    }
}
