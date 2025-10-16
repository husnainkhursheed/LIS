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
            // 'contact_number' => 'required',
            // 'dob' => 'required',
            'sex' => 'required',
       ]);

        $patient = new Patient();
        $patient->first_name  = $request->input('first_name');
        $patient->surname  = $request->input('surname');
        $patient->contact_number  = $request->input('contact_number');
        $patient->dob  = $request->input('dob');
        $patient->sex  = $request->input('sex');
        $patient->is_active  = $request->has('is_active') ? 1 : 0;
        $patient->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'patient' => $patient]);
        }

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
            // 'contact_number' => 'required',
            // 'dob' => 'required',
            'sex' => 'required',
       ]);

        $patient = Patient::find($id);
        $patient->first_name  = $request->input('first_name');
        $patient->surname  = $request->input('surname');
        $patient->contact_number  = $request->input('contact_number');
        $patient->dob  = $request->input('dob');
        $patient->sex  = $request->input('sex');
        $patient->is_active  = $request->has('is_active') ? 1 : 0;
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

    public function getSuggestions(Request $request)
    {
        $field = $request->has('first_name') ? 'first_name' : 'surname';
        $value = $request->input($field);

        if (strlen($value) < 2) {
            return response()->json([]);
        }

        $query = Patient::query();

        if ($field === 'first_name') {
            $query->where('first_name', 'like', $value . '%')
                  ->orderBy('first_name')
                  ->limit(10);
        } else {
            $query->where('surname', 'like', $value . '%')
                  ->orderBy('surname')
                  ->limit(10);
        }

        return response()->json($query->get(['id', 'first_name', 'surname', 'dob']));
    }

    public function checkDuplicates(Request $request)
    {
        $firstName = $request->input('first_name');
        $surname = $request->input('surname');
        $dob = $request->input('dob');

        if (strlen($firstName) < 2 || strlen($surname) < 2) {
            return response()->json([]);
        }

        $duplicates = $this->findPotentialDuplicates($firstName, $surname, $dob);

        return response()->json($duplicates);
    }

    private function findPotentialDuplicates($firstName, $surname, $dob = null)
    {
        $query = Patient::where('first_name', 'like', $firstName . '%')
                       ->where('surname', 'like', $surname . '%');

        if ($dob) {
            $query->where('dob', $dob);
        }

        return $query->limit(5)
                     ->get(['id', 'first_name', 'surname', 'dob', 'contact_number']);
    }
}
