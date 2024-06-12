<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\Institution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class InstitutionController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Institution access', ['only' => ['index','store','edit','update','destroy']]);
    }
    //
    public function index(Request $request)
    {
        $query = Institution::query();

        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('contact_number', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('address_line_2', 'like', '%' . $searchTerm . '%');

            });
        }

        // Handle sorting
        if ($request->has('sort_by')) {
            $sortOrder = $request->input('sort_order') ?? 'asc'; // Default to ascending if not specified
            $query->orderBy($request->input('sort_by'), $sortOrder);
        }

        $institutions = $query->paginate(10);
        return view('setup.institution',compact('institutions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'contact_number' => 'required',
            'street_name' => 'required',
            // 'address_line_2' => 'required',
            'area' => 'required',
            'email' => 'required',
       ]);

        $institution = new Institution();
        $institution->name  = $request->input('name');
        $institution->contact_number  = $request->input('contact_number');
        $institution->street_name  = $request->input('street_name');
        $institution->address_line_2  = $request->input('address_line_2');
        $institution->area  = $request->input('area');
        $institution->email  = $request->input('email');
        $institution->is_active  = $request->has('is_active') ? 1 : 0;
        $institution->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'institution' => $institution]);
        }

        Session::flash('message', 'Created successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $institution = Institution::find($id);
        return response()->json([
            'institution' => $institution,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'contact_number' => 'required',
            'street_name' => 'required',
            // 'address_line_2' => 'required',
            'area' => 'required',
            'email' => 'required',
       ]);

        $institution = Institution::find($id);
        $institution->name  = $request->input('name');
        $institution->contact_number  = $request->input('contact_number');
        $institution->street_name  = $request->input('street_name');
        $institution->address_line_2  = $request->input('address_line_2');
        $institution->area  = $request->input('area');
        $institution->email  = $request->input('email');
        $institution->is_active  = $request->has('is_active') ? 1 : 0;
        $institution->update();


        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();

    }

    public function destroy($id)
    {
        $institution = Institution::find($id);
        $institution->delete();
        Session::flash('message', 'Deleted successfully!');
        Session::flash('alert-class', 'alert-success');
    }
}
