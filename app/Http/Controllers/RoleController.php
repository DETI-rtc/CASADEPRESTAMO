<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
     
    }
    public function index()
    {
        $roles = Role::with("permissions")->get();
        return $roles;
        //response()->json(['role'=>Role::all()]);
    }

    public function store(Request $request)
    {
        
        $role = Role::create(['name' => $request->get('name'),'guard_name'=>$request['guard_name']]);
       $role->permissions()->sync($request->get('permissions'));
        return $role;
    }

    
    public function show($id)
    {
        //
    }
   
    public function update(Request $request, $id)
    {

      // DD($request);
        $role = Role::find($id);
        $role->name = $request->get('name');
        $role->save();
        $role->permissions()->sync($request->get('permissions'));

        return $role;
    }

    public function destroy($id)
    {
        $role = Role::find($id)->delete();
        return $role;
    }

    public function permi()
    {
        $permissions = Permission::all();      
        return $permissions;
    }
    public function permisos()
    {
        $user = auth('sanctum')->user(); //Italo Morales
        $role = $user->getRoleNames();
        
        return $role;
    }
}
