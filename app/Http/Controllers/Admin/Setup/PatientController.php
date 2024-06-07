<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class PatientController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Patient access', ['only' => ['index','store','edit','update','destroy']]);
    }

    public function index(Request $request)
    {
        $query = Patient::query();

        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($query) use ($searchTerm) {
                $query->where('first_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('surname', 'like', '%' . $searchTerm . '%')
                      ->orWhere('contact_number', 'like', '%' . $searchTerm . '%')
                      ->orWhere('dob', 'like', '%' . $searchTerm . '%')
                      ->orWhere('sex', 'like', '%' . $searchTerm . '%');

            });
        }

        // Handle sorting
        if ($request->has('sort_by')) {
            $sortOrder = $request->input('sort_order') ?? 'asc'; // Default to ascending if not specified
            $query->orderBy($request->input('sort_by'), $sortOrder);
        }

        $patients = $query->paginate(10);

        return view('setup.patient',compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'surname' => 'required',
            'contact_number' => 'required',
            'dob' => 'required',
            'sex' => 'required',
       ]);

        $patient = new Patient();
        $patient->first_name  = $request->input('first_name');
        $patient->surname  = $request->input('surname');
        $patient->contact_number  = $request->input('contact_number');
        $patient->dob  = $request->input('dob');
        $patient->sex  = $request->input('sex');
        $patient->save();

        Session::flash('message', 'Created successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $patient = Patient::find($id);
        return response()->json([
            'patient' => $patient,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required',
            'surname' => 'required',
            'contact_number' => 'required',
            'dob' => 'required',
            'sex' => 'required',
       ]);

        $patient = Patient::find($id);
        $patient->first_name  = $request->input('first_name');
        $patient->surname  = $request->input('surname');
        $patient->contact_number  = $request->input('contact_number');
        $patient->dob  = $request->input('dob');
        $patient->sex  = $request->input('sex');
        $patient->update();


        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();

    }

    public function destroy($id)
    {
        $patient = Patient::find($id);
        $patient->delete();
        Session::flash('message', 'Deleted successfully!');
        Session::flash('alert-class', 'alert-success');
    }
}
