<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\RoleOrPermission;
// use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:UserManagement access', ['only' => ['index','store','edit','update','destroy']]);
    }
    public function index()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        // $users= User::all();
        $roles = Role::where('name', '!=', 'admin')->get();
        // $roles= Role::all();
        // dd($users);
        return view('usermanagement.users',compact('users','roles'));
    }


    public function store(Request $request)
    {
        // dd($request->file('userimage'));
        $request->validate([
            'name'=>'required',
            'email' => 'required|email|unique:users',
            'password'=>'required',
            'userimage' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imageName = null;
        if ($request->userimage) {
            $image = $request->userimage;
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
        }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'avatar' => $imageName ? $imageName : null,
            // 'roleid'=>2,
            'email_verified_at' => now(),
            'password'=> bcrypt($request->user_password),
        ]);
        if (is_array($request->role_ids)) {
            // If permission_ids is an array, map its elements to integers
            $permissionIds = array_map('intval', $request->role_ids);
            $user->syncRoles($permissionIds);
        } else {
            // If permission_ids is not an array, assume it's a single ID
            $user->syncRoles([$request->role_ids]);
        }
        // $permissionIds = array_map('intval', $request->role_ids);
        // $user->syncRoles($permissionIds);
        Session::flash('message', 'Created successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
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
            'name'=>'required',
            'email' => 'required|email|unique:users,email,'.$user->id.',id',
            'userimage' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($request->password != null){
            $request->validate([
                'password' => 'required'
            ]);
            $validated['password'] = bcrypt($request->password);
        }
        // if ($request->hasFile('user_image') && $request->file('user_image')->isValid()) {
        //     $image = $request->file('user_image');
        //     $imageName = time() . '.' . $image->getClientOriginalExtension();
        //     $image->move(public_path('uploads'), $imageName);
        //     $validated['avatar'] = $imageName;
        // }
        $imageName = null;
        if ($request->hasFile('userimage') && $request->file('userimage')->isValid()) {
            if ($user->avatar != null && file_exists(public_path('uploads') . '/' . $user->avatar)) {
                 $file_old = public_path('uploads') . '/' . $user->avatar;
                 unlink($file_old);
             }
             $image = $request->file('userimage');
             // var_dump( $image );
             $imageName = time() . '.' . $image->getClientOriginalExtension();
             $image->move(public_path('uploads'), $imageName);

             // Save the image file name to the database
             $validated['avatar'] = $imageName ? $imageName : null;
         }

        $user->update($validated);
        if (is_array($request->role_ids)) {
            // If permission_ids is an array, map its elements to integers
            $permissionIds = array_map('intval', $request->role_ids);
            $user->syncRoles($permissionIds);
        } else {
            // If permission_ids is not an array, assume it's a single ID
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

}
