<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RoleOrPermission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;

// use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:UserManagement access', ['only' => ['index','store','edit','update','destroy']]);
    }
    public function index(Request $request)
    {
        $query = User::whereDoesntHave('roles', function ($query) {
            $query->where('first_name', 'managment');
        });

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($query) use ($searchTerm) {
                $query->where('first_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('surname', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('sort_by')) {
            $sortBy = $request->input('sort_by');
            $sortOrder = $request->input('sort_order') ?? 'asc';
            $query->orderBy($sortBy, $sortOrder);
        }

        $users = $query->paginate(10);

        $roles = Role::all();

        // Return the view with the users and roles data
        return view('usermanagement.users', compact('users', 'roles'));
    }


    public function store(Request $request)
    {
        // dd($request->file('userimage'));
        $request->validate([
            'first_name'=>'required',
            'surname'=>'required',
            'email' => 'required|email|unique:users',
            // 'userimage' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $user_password = Hash::make('12345678');
        $token = Str::random(60);
        // $imageName = null;
        // if ($request->userimage) {
        //     $image = $request->userimage;
        //     $imageName = time() . '.' . $image->getClientOriginalExtension();
        //     $image->move(public_path('uploads'), $imageName);
        // }


        $user = User::create([
            'first_name'=>$request->first_name,
            'surname'=>$request->surname,
            'email'=>$request->email,
            'remember_token'=>$token,
            // 'avatar' => $imageName ? $imageName : null,
            'departments' => $request->departments ?? [],
            'email_verified_at' => now(),
            'password'=> bcrypt($user_password),
        ]);

        if (is_array($request->role_ids)) {
            // If permission_ids is an array, map its elements to integers
            $permissionIds = array_map('intval', $request->role_ids);
            $user->syncRoles($permissionIds);
        } else {
            // If permission_ids is not an array, assume it's a single ID
            $user->syncRoles([$request->role_ids]);
        }

        $data = [
            'token' => $token,
            'first_name' => $request->first_name,
            'surname' => $request->surname,
        ];

        Mail::send('emails.verify', $data, function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Your Borderlife LIS Account');
        });

        return redirect()->back()->with('success', 'User created and verification email sent.');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $user->roles;
        return response()->json([
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $validated = $request->validate([
            'first_name'=>'required',
            'surname'=>'required',
            // 'email' => 'required|email|unique:users,email,'.$user->id.',id',
            // 'userimage' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // if($request->password != null){
        //     $request->validate([
        //         'password' => 'required'
        //     ]);
        //     $validated['password'] = bcrypt($request->password);
        // }
        // if ($request->hasFile('user_image') && $request->file('user_image')->isValid()) {
        //     $image = $request->file('user_image');
        //     $imageName = time() . '.' . $image->getClientOriginalExtension();
        //     $image->move(public_path('uploads'), $imageName);
        //     $validated['avatar'] = $imageName;
        // }
        // $imageName = null;
        // if ($request->hasFile('userimage') && $request->file('userimage')->isValid()) {
        //     if ($user->avatar != null && file_exists(public_path('uploads') . '/' . $user->avatar)) {
        //          $file_old = public_path('uploads') . '/' . $user->avatar;
        //          unlink($file_old);
        //      }
        //      $image = $request->file('userimage');
        //      // var_dump( $image );
        //      $imageName = time() . '.' . $image->getClientOriginalExtension();
        //      $image->move(public_path('uploads'), $imageName);

        //      // Save the image file name to the database
        //      $validated['avatar'] = $imageName ? $imageName : null;
        // }

        // Find the user by ID
        $user = User::findOrFail($id);
        // dd($user);
        // Collect the data to be updated
        $updateData = [
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'email' => $request->email,
            'departments' => $request->departments ?? [],
        ];

        // Only add the password to the update data if it is provided
        if ($request->password != null) {
            $updateData['password'] = bcrypt($request->password);
        }

        // Update the user with the collected data
        $user->update($updateData);

        // Update user's roles
        if (is_array($request->role_ids)) {
            $roleIds = array_map('intval', $request->role_ids);
            $user->syncRoles($roleIds);
        } else {
            $user->syncRoles([$request->role_ids]);
        }
        // $permissionIds = array_map('intval', $request->role_ids);
        // $user->syncRoles($permissionIds);
        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        Session::flash('message', 'Deleted successfully!');
        Session::flash('alert-class', 'alert-success');
        // return redirect()->back();
    }






    public function verify($token)
    {
        $user = User::where('remember_token', $token)->firstOrFail();
        return view('auth.set_password', ['user' => $user]);
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('root')->with('success', 'Password set successfully.');
    }

}
