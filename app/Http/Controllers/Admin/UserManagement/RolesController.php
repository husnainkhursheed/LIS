<?php

namespace App\Http\Controllers\Admin\UserManagement;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:UserManagement access', ['only' => ['index','store','edit','update','destroy']]);
    }
    public function index(Request $request)
    {
        $query = Role::query();

        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');

            });
        }

        // Handle sorting
        if ($request->has('sort_by')) {
            $sortOrder = $request->input('sort_order') ?? 'asc'; // Default to ascending if not specified
            $query->orderBy($request->input('sort_by'), $sortOrder);
        }


       $roles = $query->latest()->paginate(10);

       $permissions = Permission::latest()->get();
       return view('usermanagement.roles' ,['permissions'=>$permissions, 'roles'=>$roles]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name'=>'required',
            'permission_ids' => 'required',
        ]);

        $role = Role::create(['name'=>$request->name]);
        $permissionIds = array_map('intval', $request->permission_ids);
        $role->syncPermissions($permissionIds);
        Session::flash('message', 'Role created successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();

        // return  redirect('usermanagement.roles')->withSuccess('Role created !!!');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        // $permission = Permission::get();
        $role->permissions;
        return response()->json([
            'role' => $role,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required',
            // 'permission_ids' => 'required',
        ]);
        // dd($request->permission_ids);
        $role = Role::find($id);
        $role->update(['name'=>$request->name]);
        if (is_array($request->permission_ids)) {
            // If permission_ids is an array, map its elements to integers
            $permissionIds = array_map('intval', $request->permission_ids);
            $role->syncPermissions($permissionIds);
        } else {
            // If permission_ids is not an array, assume it's a single ID
            $role->syncPermissions([$request->permission_ids]);
        }
        // dd($permissionIds);


        // $role->syncPermissions($permissionIds);
        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function destroy($id)
    {
        // $role = Role::find($id);
        $role = Role::destroy($id);
        // $role->delete();
        Session::flash('message', 'Deleted successfully!');
        Session::flash('alert-class', 'alert-success');
        // return redirect()->back();
    }

}
