<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asignameta;

class AsignametaController extends Controller
{
    //
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
       $user = auth('sanctum')->user();
       if ($user->hasRole(['Administrador'])) {
           $asig = Asignameta::with('persona')->get();
       }else {
           $asig = Asignameta::with('persona')->where('idsupervisor',auth('sanctum')->user()->id)->get();
       }
       //DD($user);
        return $asig;
  

    }
    public function store(Request $request)
    {
        $anio = date("Y");
        // $request['periodo']= $anio;
        
        $messages = [
            'periodo.unique' => 'Este usuario ya esta registrado con ese periodo',
            
        ];

        $this->validate($request,[
            'periodo' => 'required|unique:meta_usu_mes,periodo,NULL,id,idpersona,'.$request->idpersona
        ], $messages);
        $asigna = new Asignameta;
        $asigna->idpersona=$request->get('idpersona');
        $asigna->m1=$request->get('m1');
        $asigna->m2=$request->get('m2');
        $asigna->m3=$request->get('m3');
        $asigna->m4=$request->get('m4');
        $asigna->m5=$request->get('m5');
        $asigna->m6=$request->get('m6');
        $asigna->m7=$request->get('m7');
        $asigna->m8=$request->get('m8');
        $asigna->m9=$request->get('m9');
        $asigna->m10=$request->get('m10');
        $asigna->m11=$request->get('m11');
        $asigna->m12=$request->get('m12');            
        $asigna->idsupervisor=auth('sanctum')->user()->id;
        $asigna->anual=$request->get('anual');
        $asigna->periodo = $anio;
        $asigna->estado=1;
        $asigna->save();      

    }

    public function show($id)
    {
        $asig = Asignameta::findOrFail($id);
        
        return $asig;
    }

    
    public function update(Request $request, $id)
    {

        $asig = Asignameta::findOrFail($id);
        //$idrol = Role::find($request->roles[0]['name']);
      //DD($idrol->slug);
        
        // $request['password'] = bcrypt($request->get('password'));
        $asig->update($request->all());
        
        return $asig;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $asig = Asignameta::findOrFail($id);
        // delete the asig
        $asig->delete();

        return $asig;
    }

   

    
    

    
}
