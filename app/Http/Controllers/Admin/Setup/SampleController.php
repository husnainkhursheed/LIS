<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\Test;
use App\Models\Doctor;
use App\Models\Sample;
use App\Models\Patient;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        return view('setup.sample.create' ,compact('doctors', 'institutions', 'patients','tests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'access_number' => 'required',
            'collected_date' => 'required',
            'received_date' => 'required',
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'institution_id' => 'required',
            'test_requested' => 'required',
            'bill_to' => 'required',
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
