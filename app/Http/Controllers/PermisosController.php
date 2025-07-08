<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DB;

class PermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
     
    }


    public function index()
    {
        $permi = Permission::all();
        return $permi;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $permiso = Permission::create($request->all());
        return $permiso;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //SELECT pe.id FROM permissions as pe INNER JOIN permission_role as pr on pe.id = permission_id inner join roles as ro on pr.role_id=ro.id WHERE role_id = 3
        $permi = db::table('permissions')->select('permissions.id as id')->join('permission_role','permissions.id','=','permission_role.permission_id')->join('roles','permission_role.role_id','=','roles.id')->where('role_id','=',$id )->get(); 
        //::with("permissions")->where('id','=',$id)->first();

        

        return response()->json($permi->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permi = Permission::find($id);
        
        $permi->update($request->all());

        return $permi;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permiso = Permission::findOrFail($id);
        // delete the user
        $permiso->delete();

        return $permiso;
    }
    public function permisosrol(Request $request)
    {
        $user = auth('sanctum')->user(); //Italo Morales
        $role = $user->getPermissionsViaRoles();
        $role = $role->pluck('name')->unique();
        // DD($role);
        // DD($role);
      
        // $permisos = $role->getPermissionNames();
        // $rol = User::where('id',$user)->first();
        
        return $role;
    }
}
