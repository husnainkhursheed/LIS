<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\Test;
use App\Models\Doctor;
use App\Models\Sample;
use App\Models\Patient;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomDropdown;
use Illuminate\Support\Facades\Session;

class SampleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $doctors = Doctor::all();
        $institutions = Institution::all();
        $patients = Patient::all();
        $tests = Test::all();
        // $custom = CustomDropdown::all();
        $custom = CustomDropdown::where('dropdown_name', 'Bilirubin')->get();

        return view('setup.sample.create' ,compact('doctors', 'institutions', 'patients','tests','custom'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            // 'test_number' => 'required|unique:samples,test_number|max:6',
            'access_number' => 'required|unique:samples,access_number',
            'collected_date' => 'required|date',
            'received_date' => 'required|date',
            'received_time' => 'required|time',
            'patient_id' => 'required|exists:patients,id',
            'institution_id' => 'nullable|exists:institutions,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'bill_to' => 'required|in:Patient,Doctor,Other',
            'test_requested' => 'required',

       ]);
    //    dd($request->all());


       $sample = new Sample();
       $sample->test_number = strtoupper(substr(md5(time()), 0, 6)); // Example of generating a unique 6-character string
       $sample->access_number = $request->access_number;
       $sample->collected_date = $request->collected_date;
       $sample->received_date = $request->received_date;
       $sample->received_time = now()->format('H:i:s'); // Store the current system time
       $sample->patient_id = $request->patient_id;
       $sample->doctor_id = $request->doctor_id;
       $sample->institution_id = $request->institution_id;
       $sample->bill_to = $request->bill_to;
       $sample->save();
       // Attach the tests to the sample
       $sample->tests()->attach($request->test_requested);

        Session::flash('message', 'Created successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $doctors = Doctor::all();
        $institutions = Institution::all();
        $patients = Patient::all();
        $tests = Test::all();

        $sample = Sample::find($id);

        return view('setup.sample.edit' ,compact('doctors', 'institutions', 'patients','tests','sample'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            // 'test_number' => 'required|unique:samples,test_number|max:6',
            'access_number' => 'required|unique:samples,access_number',
            'collected_date' => 'required|date',
            'received_date' => 'required|date',
            'received_time' => 'required|time',
            'patient_id' => 'required|exists:patients,id',
            'institution_id' => 'nullable|exists:institutions,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'bill_to' => 'required|in:Patient,Doctor,Other',
            'test_requested' => 'required',
        ]);

        $sample = Sample::findOrFail($id);

        $sample->access_number = $request->access_number;
        $sample->collected_date = $request->collected_date;
        $sample->received_date = $request->received_date;
        $sample->received_time = now()->format('H:i:s'); // Store the current system time
        $sample->patient_id = $request->patient_id;
        $sample->doctor_id = $request->doctor_id;
        $sample->institution_id = $request->institution_id;
        $sample->bill_to = $request->bill_to;

        // Detach the existing tests from the sample
        $sample->tests()->detach();

        // Attach the updated tests to the sample
        $sample->tests()->attach($request->test_requested);

        $sample->save();

        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
