<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DB;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $this->authorize('isAdmin');
       $user = DB::table('users')->select('users.id','users.name','users.nombres','users.apellidos','users.dni','users.avatar','users.email','roles.name as rol')
       ->join('model_has_roles','users.id','=','model_id')
       ->join('roles','role_id','=','roles.id')       
       ->get();
       //DD($user);
        return $user;
  

    }
    public function store(Request $request)
    {
       
        $this->validate($request,[
            'name' => 'required|string|max:191|unique:users',
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'dni' => 'required|string|max:8|min:8',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6'
        ]);
        try{
            DB::beginTransaction();
            $usuario = new User;
            $usuario->name=$request->get('name');
            $usuario->nombres=$request->get('nombres');
            $usuario->apellidos=$request->get('apellidos');       
            $usuario->dni=$request->get('dni');
            $usuario->email=$request->get('email');
            $usuario->password=bcrypt($request->get('password'));
            $usuario->save();        
        
            $usuario->assignRole($request->rol);
            DB::commit();
            return $usuario;
        } catch (Exception $e){
            DB::rollBack();
        }

        

    }

    public function show($id)
    {
        $usu = User::findOrFail($id);
        $user = DB::table('users')->select('users.id','users.name','users.nombres','users.apellidos','users.dni','users.avatar','users.email','roles.name as rol','roles.slug as idrol')->join('role_user','users.id','=','role_user.user_id')->join('roles','role_user.role_id','=','roles.id')->where('users.id',$id)->first();
        return response()->json($user);
    }

    
    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);
        //$idrol = Role::find($request->roles[0]['name']);
      //DD($idrol->slug);
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'dni' => 'required|string|max:8|min:8',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6'
        ]);
         $request['password'] = bcrypt($request->get('password'));
        $user->update($request->all());
        $user->syncRoles($request->rol);
        return ['message' => 'Se Actualizo Informacion del Usuario'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $user = User::findOrFail($id);
        // delete the user
        $user->delete();

        return ['message' => 'User Eliminado'];
    }

    public function search(){

        if ($search = \Request::get('q')) {
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users = User::latest()->paginate(5);
        }

        return $users;

    }

    public function roles()
    {
        $roles = Role::paginate();
        return response()->json(['role'=>Role::all()]);
    }

    public function profile()
    {
        $usu = auth('sanctum')->user();
        $userRole = $usu->roles->pluck('model_id','name');
        //$rol = BD::table('model_has_roles')->where('id_model')
        //$usua = DB::table('users as u')->join('model_has_roles as hr','u.id','=','hr.model_id')->select('u.id','u.name','u.nombres','u.apellidos','u.dni','u.avatar')->where('u.id','=',$usu->id)->first();
                //DB::table('users')->select('users.*','roles.name')->join('role_user','users.id','=','role_user.user_id')->join('roles','role_user.role_id','=','roles.id')->where('users.id',$usu->id)->first();
       //$usua = auth('api')->roles->pluck('name')->toArray();
       //DB::table('users')->select('users.id','users.name','users.nombres','users.apellidos','users.dni','users.avatar','users.email')->join('model_has_roles as hr','users.id','=','hr.model_id')->get();
      // $usua = User::find($usu->id);

       
        return $usu;
    }

    public function updateProfile(Request $request)
    {
        $user = auth('sanctum')->user();


        $this->validate($request,[
            'name' => 'required|string|max:191',
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'dni' => 'required|string|max:8|min:8',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);
        $currentPhoto = $user->avatar;
        if($request->avatar != $currentPhoto){
            $name = time().'.' . explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];

            \Image::make($request->avatar)->save(public_path('/storage/').$name);
            $request->merge(['avatar' => $name]);

            $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }
        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }        
        $user->update($request->all());
        //$idrol=$request->roles[0]->name;
       
        //auth()->user()->assignRoles($idrol);
        return $user;
    }
     public function selperiodo(Request $request){

        $periodo = DB::table('periodo')->get();

        return  $periodo;
    }
    public function veragentes($id){
        $agen = DB::table('users as u')->join('nivel1_has_nivel2 as n','u.id','=','n.idnivel1')->select('u.id','n.id as idnivel','nombres','apellidos','dni')->WHERE('idnivel2',$id)->where('estado',1)->get();
        return $agen;
    }
    public function agenteslibres(){
        //SELECT u.id,u.name,u.nombres,u.apellidos,u.dni,u.avatar,u.email,r.name as rol FROM users AS u INNER JOIN model_has_roles AS m ON u.id =  m.model_id inner JOIN roles AS r ON m.role_id = r.id  WHERE NOT EXISTS (SELECT NULL FROM nivel1_has_nivel2 t2 WHERE t2.idnivel1 = u.id) AND r.name = 'Agente';
        //SELECT u.id,u.name,u.nombres,u.apellidos,u.dni,u.avatar,u.email,r.name as rol FROM users AS u INNER JOIN model_has_roles AS m ON u.id =  m.model_id inner JOIN roles AS r ON m.role_id = r.id  LEFT JOIN nivel1_has_nivel2 t2 ON t2.idnivel1 = u.id WHERE t2.idnivel1 IS NULL AND  r.name = 'Agente';
        //SELECT u.id,u.name,u.nombres,u.apellidos,u.dni,u.avatar,u.email,r.name as rol FROM users AS u INNER JOIN model_has_roles AS m ON u.id =  m.model_id inner JOIN roles AS r ON m.role_id = r.id WHERE u.id NOT IN (SELECT idnivel1 FROM nivel1_has_nivel2) AND r.name = 'Agente';
        $agentes = DB::table('users as u')->select('u.id','u.name','u.nombres','u.apellidos','u.dni','u.avatar','u.email','r.name as rol')->join('model_has_roles as m','u.id','=','m.model_id')->join('roles as r','m.role_id','=','r.id')->whereRaw("u.id NOT IN (SELECT idnivel1 FROM nivel1_has_nivel2) AND r.name = 'Agente'")->get();
        return $agentes;
    }
    public function registraagente(Request $request){

       // DD($request->all());
        $agente = DB::table('nivel1_has_nivel2')->insert($request->all());
        
        //$agentes = DB::table('users as u')->select('u.id','u.name','u.nombres','u.apellidos','u.dni','u.avatar','u.email','r.name as rol')->join('model_has_roles as m','u.id','=','m.model_id')->join('roles as r','m.role_id','=','r.id')->whereRaw("u.id NOT IN (SELECT idnivel1 FROM nivel1_has_nivel2) AND r.name = 'Agente'")->get();
        return $agente;
    }
    public function verdatosusuario(Request $request){

        $user = auth('sanctum')->user();
        // DD($user->getRoleNames());
        //$user->hasRole('Supervisor');
        // dd($user);
        if ($user->hasRole(['Supervisor'])) {
            // DD($user);
            $usuarios = DB::table('users as u')
            ->join('nivel1_has_nivel2 as n','u.id','=','n.idnivel1')
            ->select(db::raw("u.id,u.nombres,concat(u.apellidos,' ',u.nombres) as usuarios,u.apellidos"))
            ->WHERE('idnivel2',auth('sanctum')->user()->id)->get();
            // ->WHERE('idnivel2',auth('sanctum')->user()->id)->whereRaw("u.id IN (SELECT idpersona FROM meta_usu_mes where estado=1)")->get();
            //$usuarios = DB::table('users as u')->select(db::raw("u.id,u.nombres,concat(u.apellidos,' ',u.nombres) as usuarios,u.apellidos,r.name as rol"))->join('model_has_roles as m','u.id','=','m.model_id')->join('roles as r','m.role_id','=','r.id')->whereRaw("u.id NOT IN (SELECT idpersona FROM meta_usu_mes) AND u.id <> ".auth('sanctum')->user()->id." and r.name <> 'Supervisor' " )->get();
        } elseif ($user->hasRole(['Gerente'])) {
            $usuarios = DB::table('users as u')->select(db::raw("u.id,u.nombres,concat(u.apellidos,' ',u.nombres) as usuarios,u.apellidos"))
            ->join('model_has_roles as m','u.id','=','m.model_id')->join('roles as r','m.role_id','=','r.id')
            ->whereRaw("u.id IN (SELECT idnivel1 FROM nivel1_has_nivel2) AND r.name = 'Supervisor'")->get();
        }elseif ($user->hasRole(['Administrador'])) {
            // DD('asd');
            $usuarios = DB::table('users as u')
            ->join('nivel1_has_nivel2 as n','u.id','=','n.idnivel1')
            ->select(db::raw("u.id,u.nombres,concat(u.apellidos,' ',u.nombres) as usuarios,u.apellidos"))
            ->whereRaw("u.id IN (SELECT idpersona FROM meta_usu_mes where estado=1)")->get();
        } else {
            $usuarios='';
        }
        // $usu1 = $user->hasRole(['Gerente', 'Administrador']);
        // if ($usu1 ) {
        //     DD('este es GERENTE?');
        //     $usuarios = DB::table('users as u')->select(db::raw("u.id,u.nombres,concat(u.apellidos,' ',u.nombres) as usuarios,u.apellidos"))
        //     ->join('model_has_roles as m','u.id','=','m.model_id')->join('roles as r','m.role_id','=','r.id')
        //     ->whereRaw("u.id IN (SELECT idnivel1 FROM nivel1_has_nivel2) AND r.name = 'Supervisor'")->get();
        // }
         //
        // DD($usuarios);
         //$agente = DB::table('nivel1_has_nivel2')->insert($request->all());
         //$agentes = DB::table('users as u')->select('u.id','u.name','u.nombres','u.apellidos','u.dni','u.avatar','u.email','r.name as rol')->join('model_has_roles as m','u.id','=','m.model_id')->join('roles as r','m.role_id','=','r.id')->whereRaw("u.id NOT IN (SELECT idnivel1 FROM nivel1_has_nivel2) AND r.name = 'Agente'")->get();
         return $usuarios;
     }
     public function notificaciones(){

        $datos = auth('sanctum')->user()->unreadNotifications;
        return $datos->pluck('data');
     }
}
