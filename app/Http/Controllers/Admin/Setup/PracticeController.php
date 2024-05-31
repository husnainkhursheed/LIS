<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\Surgery;
use Illuminate\Http\Request;
use App\Models\Practice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PracticeController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Practice Access', ['only' => ['index','store','edit','update','destroy']]);
    }

    public function index(Request $request)
    {
        $query = Practice::query();

        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($query) use ($searchTerm) {
                $query->where('v_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('address', 'like', '%' . $searchTerm . '%')
                      ->orWhere('town', 'like', '%' . $searchTerm . '%')
                      ->orWhere('zip', 'like', '%' . $searchTerm . '%')
                      ->orWhere('country', 'like', '%' . $searchTerm . '%')
                      ->orWhere('telephone', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Handle sorting
        if ($request->has('sort_by')) {
            $sortOrder = $request->input('sort_order') ?? 'asc'; // Default to ascending if not specified
            $query->orderBy($request->input('sort_by'), $sortOrder);
        }

        $practices = $query->paginate(10);

        return view('setup.practice',compact('practices'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'v_name' => 'required',
            'address' => 'required',
            'town' => 'required',
            'zip' => 'required',
            'country' => 'required',
            'telephone' => 'required',
            'email' => 'required',
       ]);

        $practice = new Practice();
        $practice->v_name  = $request->input('v_name');
        $practice->address  = $request->input('address');
        $practice->town  = $request->input('town');
        $practice->zip  = $request->input('zip');
        $practice->country  = $request->input('country');
        $practice->telephone  = $request->input('telephone');
        $practice->email  = $request->input('email');
        $practice->zip  = $request->input('zip');
        $practice->is_active  = $request->has('is_active') ? 1 : 0;
        $practice->inserted_by = Auth::id();
        $practice->save();

        Session::flash('message', 'Created successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $practice = Practice::find($id);
        return response()->json([
            'SetupPractice' => $practice,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'v_name' => 'required',
            'address' => 'required',
            'town' => 'required',
            'zip' => 'required',
            'country' => 'required',
            'telephone' => 'required',
            'email' => 'required',
       ]);

        $practice = Practice::find($id);
        $practice->v_name  = $request->input('v_name');
        $practice->address  = $request->input('address');
        $practice->town  = $request->input('town');
        $practice->zip  = $request->input('zip');
        $practice->country  = $request->input('country');
        $practice->telephone  = $request->input('telephone');
        $practice->email  = $request->input('email');
        $practice->zip  = $request->input('zip');
        $practice->is_active  = $request->has('is_active') ? 1 : 0;
        $practice->updated_by = Auth::id();
        $practice->update();


        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();

    }

    public function destroy($id)
    {
        $user = Practice::find($id);
        $user->delete();
        Session::flash('message', 'Deleted successfully!');
        Session::flash('alert-class', 'alert-success');
    }
}
