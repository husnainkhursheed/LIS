<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sample;
use App\Models\TestReport;
use Illuminate\Http\Request;
use App\Models\BiochemHaemoResults;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\CytologyGynecologyResults;
use App\Models\UrinalysisMicrobiologyResults;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (view()->exists($request->path())) {
            return view($request->path());
        }
        return abort(404);
    }

    public function root(Request $request)
    {
        // $samples = Sample::paginate(10);
        $query = Sample::with('patient');

        // // Check if the current user has the "Lab" role
        // $currentUser = Auth::user();
        // if ($currentUser->hasRole('Lab')) {
        //     // Filter samples by the current user's departments through the related tests
        //     $departmentIds = $currentUser->departments;
        //     $query->whereHas('tests', function($testQuery) use ($departmentIds) {
        //         $testQuery->whereIn('department', $departmentIds);
        //     });
        // }

        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($query) use ($searchTerm) {
                $query->where('test_number', 'like', '%' . $searchTerm . '%')
                      ->orWhere('access_number', 'like', '%' . $searchTerm . '%')
                      ->orWhere('received_date', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('patient', function($patientQuery) use ($searchTerm) {
                          $patientQuery->where('first_name', 'like', '%' . $searchTerm . '%')
                                       ->orWhere('surname', 'like', '%' . $searchTerm . '%');
                      });
            });
        }

        // Handle entries shown filter
        if ($request->has('entries_shown')) {
            $entriesShown = $request->input('entries_shown');
            $now = now();

            if ($entriesShown == 'last_20_days') {
                $query->where('received_date', '>=', $now->subDays(20));
            } elseif ($entriesShown == 'last_3_years') {
                $query->where('received_date', '>=', $now->subYears(3));
            }
        }


        // Handle sorting
        if ($request->has('sort_by')) {
            $sortBy = $request->input('sort_by');
            $sortOrder = $request->input('sort_order') ?? 'asc'; // Default to ascending if not specified

            // Handle sorting by patient columns
            if (in_array($sortBy, ['first_name', 'surname'])) {
                $query->join('patients', 'samples.patient_id', '=', 'patients.id')
                    ->orderBy('patients.' . $sortBy, $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        $samples = $query->paginate(15);





        $samples->getCollection()->transform(function ($sample) {
            // Fetch individual tests with direct department association
            $individualTests = $sample->tests()->get();

            // Get departments for individual tests directly from the 'tests' table
            $individualTestDepartments = $individualTests->pluck('department')->unique();

            // Initialize collection for profile-related tests
            $profileTests = collect();
            $profileDepartments = collect();

            // dd($sample->testProfiles[0]->tests);

            // Fetch profile tests and their departments
            foreach ($sample->testProfiles as $profile) {
                $profileTests = $profileTests->merge($profile->tests()->get());

                // Fetch profile departments from the relationship (ensure profile->departments exists)
                if ($profile->departments) {
                    $profileDepartments = $profileDepartments->merge(
                        $profile->departments->pluck('department')
                    );
                }
            }

            // Merge individual and profile-related departments
            $allDepartments = $individualTestDepartments->merge($profileDepartments)->unique();
            // dd($allDepartments);

            // Merge individual and profile tests
            $tests = $individualTests->merge($profileTests);

            // Group tests by department and check if all tests in each department are completed
            $departmentsStatus = $allDepartments->mapWithKeys(function ($department) use ($tests, $sample) {
                // Filter tests for the current department
                $departmentTests = $tests->filter(function ($test) use ($department) {
                    // Ensure the test and its profile's departments are properly checked
                    return $test->department === $department ||
                        $test->testProfiles->contains(function ($testProfile) use ($department) {
                            return $testProfile->departments->contains('department', $department);
                        });
                });

                $departmentTestsReports = TestReport::where('sample_id', $sample->id)
                    ->whereIn('test_id', $departmentTests->pluck('id'))
                    ->get();

                $isCompleted = false;
                switch ($department) {
                    case '2':
                        // $departmentTests = $tests->filter(function ($test) use ($department) {
                        //         return $test->department === $department;
                        //     });
                        $isCompleted = CytologyGynecologyResults::whereIn('test_report_id', $departmentTestsReports->pluck('id'))
                            ->where('is_completed', true)
                            ->count() == $departmentTests->count();
                        break;
                    case '1':
                        $isCompleted = BiochemHaemoResults::whereIn('test_report_id', $departmentTestsReports->pluck('id'))
                            ->where('is_completed', true)
                            ->count() == $departmentTests->count();
                        break;
                    case '3':
                        $testscount = $departmentTests->filter(function ($test) {
                            return $test->urin_test_type !== null;
                        });
                        $isCompleted = UrinalysisMicrobiologyResults::whereIn('test_report_id', $departmentTestsReports->pluck('id'))
                            ->where('is_completed', true)
                            ->count() == $testscount->count();
                        break;
                }

                return [
                    $department => [
                        'is_completed' => $isCompleted,
                    ],
                ];
            });

            // Check if all departments are completed
            $allDepartmentsCompleted = $departmentsStatus->every(function ($status) {
                return $status['is_completed'];
            });
            // dd($allDepartmentsCompleted);

            // Add the 'all_departments_completed' attribute to the sample object
            $sample->all_departments_completed = $allDepartmentsCompleted;

            // Store unique department statuses
            $sample->unique_departments = $allDepartments;
            $sample->unique_departments_status = $departmentsStatus;

            return $sample;
        });

        return view('index' , compact('samples'));
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
            $user->avatar =  $avatarName;
        }

        $user->update();
        if ($user) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            // return response()->json([
            //     'isSuccess' => true,
            //     'Message' => "User Details Updated successfully!"
            // ], 200); // Status code here
            return redirect()->back();
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            // return response()->json([
            //     'isSuccess' => true,
            //     'Message' => "Something went wrong!"
            // ], 200); // Status code here
            return redirect()->back();

        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
            ], 200); // Status code
        } else {
            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {
                Session::flash('message', 'Password updated successfully!');
                Session::flash('alert-class', 'alert-success');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Password updated successfully!"
                ], 200); // Status code here
            } else {
                Session::flash('message', 'Something went wrong!');
                Session::flash('alert-class', 'alert-danger');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Something went wrong!"
                ], 200); // Status code here
            }
        }
    }
}
