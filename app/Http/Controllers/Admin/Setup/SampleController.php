<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\Test;
use App\Models\Doctor;
use App\Models\Sample;
use App\Models\Patient;
use App\Models\TestReport;
use App\Models\Institution;
use App\Models\TestProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;


class SampleController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role_or_permission:Course access|Course create|Course edit|Course delete', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:Sample create', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Sample edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:Sample delete', ['only' => ['destroy']]);
    }


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
        $doctors = Doctor::where('is_active', 1)->get();
        $institutions = Institution::where('is_active', 1)->get();
        $patients = Patient::where('is_active', 1)->get();
        $tests = Test::where('is_active', 1)->get();
        $test_profiles = TestProfile::all();
        $access_number = strtoupper(substr(md5(time()), 0, 6));

        return view('setup.sample.create' ,compact('test_profiles','doctors', 'institutions', 'patients','tests','access_number'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            // 'test_number' => 'required',
            'access_number' => 'required|unique:samples,access_number,except,id',
            'collected_date' => 'required',
            'received_date' => 'required',
            // 'received_time' => 'required',
            'patient_id' => 'required',
            'institution_id' => 'required',
            'doctor_id' => 'required',
            'bill_to' => 'required',
            // 'test_requested' => 'required',
            // 'test_profiles' => 'required',
       ]);
        //    dd($request->all());


       $sample = new Sample();
       // $sample->test_number =$request->test_number;
       $sample->access_number = $request->access_number;
       $sample->collected_date = $request->collected_date;
       $sample->received_date = $request->received_date;
       $sample->received_time = now()->format('H:i:s'); // Store the current system time
       $sample->patient_id = $request->patient_id;
       $sample->doctor_id = $request->doctor_id;
       $sample->institution_id = $request->institution_id;
       $sample->bill_to = $request->bill_to;
    //    $sample->notes = $request->notes;
       $sample->save();
       // Attach the tests to the sample
       $sample->tests()->attach($request->test_requested);
       $sample->testProfiles()->attach($request->test_profiles);

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
        $doctors = Doctor::where('is_active', 1)->get();
        $institutions = Institution::where('is_active', 1)->get();
        $patients = Patient::where('is_active', 1)->get();
        $tests = Test::where('is_active', 1)->get();
        $test_profiles = TestProfile::all();
        $sample = Sample::find($id);

        return view('setup.sample.edit' , compact('test_profiles','doctors', 'institutions', 'patients','tests','sample'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            // 'test_number' => 'required',
            // 'access_number' => 'required',
            'collected_date' => 'required',
            'received_date' => 'required',
            'patient_id' => 'required',
            'institution_id' => 'required',
            'doctor_id' => 'required',
            'bill_to' => 'required',
            // 'test_requested' => 'required',
       ]);

        $sample = Sample::findOrFail($id);
        // $sample->test_number =$request->test_number;
        // $sample->access_number = $request->access_number;
        $sample->collected_date = $request->collected_date;
        $sample->received_date = $request->received_date;
        $sample->received_time = now()->format('H:i:s'); // Store the current system time
        $sample->patient_id = $request->patient_id;
        $sample->doctor_id = $request->doctor_id;
        $sample->institution_id = $request->institution_id;
        $sample->bill_to = $request->bill_to;
        // $sample->notes = $request->notes;
        $sample->save();

         // Get the list of test IDs from the request
         $newTestIds = $request->test_requested ?? [];
         $newProfileIds = $request->test_profiles ?? [];

         // Find the current test IDs attached to the sample
         $currentTestIds = $sample->tests()->pluck('tests.id')->toArray();
         $currentProfileIds = $sample->testProfiles()->pluck('test_profiles.id')->toArray();

         // Identify the test IDs that are being removed
         $removedTestIds = array_diff($currentTestIds, $newTestIds);
         $removedProfileIds = array_diff($currentProfileIds, $newProfileIds);
        //  dd($removedProfileIds);

        //  dd($getProfileTestsforremove[0]->tests());
        //  dd($getProfileTestsforremove);

         // Delete the `test_reports` associated with the removed tests
         if (!empty($removedTestIds)) {
             TestReport::where('sample_id', $sample->id)
                 ->whereIn('test_id', $removedTestIds)
                 ->delete();
         }
         if (!empty($removedProfileIds)) {
            foreach ($removedProfileIds as $key => $value) {
                $getProfileTestsforremove = TestProfile::find($value);
                // dd($getProfileTestsforremove);
                $getProfileTestsforremove = $getProfileTestsforremove->tests()->pluck('tests.id')->toArray();

                 TestReport::where('sample_id', $sample->id)
                     ->whereIn('test_id', $getProfileTestsforremove)
                     ->delete();
            }

         }

         // Detach the existing tests and profiles from the sample
         $sample->tests()->detach();
         $sample->testProfiles()->detach();

         // Attach the updated tests to the sample
         $sample->tests()->attach($request->test_requested);
         $sample->testProfiles()->attach($request->test_profiles);

        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sample = Sample::find($id);
        $sample->delete();
        Session::flash('message', 'Deleted successfully!');
        Session::flash('alert-class', 'alert-success');
    }

    public function checkTestsInProfiles(Request $request)
    {
        // Get the selected profile IDs from the request
        $selectedProfileIds = $request->input('profiles');

        // Retrieve all the tests that belong to the selected profiles
        $selectedProfiles = TestProfile::with('tests')->whereIn('id', $selectedProfileIds)->get();
        $testIdsInSelectedProfiles = $selectedProfiles->pluck('tests.*.id')->flatten()->unique();

        // Find other profiles that also contain these tests
        $otherProfilesWithSameTests = TestProfile::whereHas('tests', function($query) use ($testIdsInSelectedProfiles) {
            $query->whereIn('tests.id', $testIdsInSelectedProfiles); // Fully qualify the 'id' here
        })->whereNotIn('test_profiles.id', $selectedProfileIds)->get();

        // Return the IDs of the profiles to hide
        $profilesToHide = $otherProfilesWithSameTests->pluck('id')->toArray();

        return response()->json(['profilesToHide' => $profilesToHide ,'testIdsInSelectedProfiles' => $testIdsInSelectedProfiles]);
    }

}
