<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class DoctorController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Doctor access', ['only' => ['index','store','edit','update','destroy']]);
    }

    public function index(Request $request)
    {
        $query = Doctor::query();

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

        $doctors = $query->paginate(10);

        return view('setup.doctor',compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'contact_number' => 'required',
            // 'street_name' => 'required',
            // 'address_line_2' => 'required',
            // 'area' => 'required',
            // 'email' => 'required',
       ]);

        $doctor = new Doctor();
        $doctor->name  = $request->input('name');
        $doctor->contact_number  = $request->input('contact_number');
        $doctor->street_name  = $request->input('street_name');
        $doctor->address_line_2  = $request->input('address_line_2');
        $doctor->area  = $request->input('area');
        $doctor->email  = $request->input('email');
        $doctor->is_active  = $request->has('is_active') ? 1 : 0;
        $doctor->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'doctor' => $doctor]);
        }

        Session::flash('message', 'Created successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $doctor = Doctor::find($id);
        return response()->json([
            'doctor' => $doctor,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            // 'contact_number' => 'required',
            // 'street_name' => 'required',
            // 'address_line_2' => 'required',
            // 'area' => 'required',
            // 'email' => 'required',
       ]);

        $doctor = Doctor::find($id);
        $doctor->name  = $request->input('name');
        $doctor->contact_number  = $request->input('contact_number');
        $doctor->street_name  = $request->input('street_name');
        $doctor->address_line_2  = $request->input('address_line_2');
        $doctor->area  = $request->input('area');
        $doctor->email  = $request->input('email');
        $doctor->is_active  = $request->has('is_active') ? 1 : 0;
        $doctor->update();




        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();

    }

    public function destroy($id)
    {
        $doctor = Doctor::find($id);
        $doctor->delete();
        Session::flash('message', 'Deleted successfully!');
        Session::flash('alert-class', 'alert-success');
    }
}
