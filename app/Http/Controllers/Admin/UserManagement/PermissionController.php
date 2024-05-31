<?php

namespace App\Http\Controllers\Admin\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:UserManagement access', ['only' => ['index']]);
    }
    public function index(Request $request)
    {
       $permissions = Permission::latest()->get();
       return view('usermanagement.permissions' ,['permissions'=>$permissions]);
    }
}
