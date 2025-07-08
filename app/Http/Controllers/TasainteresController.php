<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Hash;
use App\Models\Tasainteres;

class TasainteresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasa=Tasainteres::with('entidad','modalidad')->get();
        return $tasa;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'tea.required' => 'La TEA es requerido',
            'identidad.required' => 'El Tipo Entidad es requerido',
            'idmodalidad.required' =>'El Tipo de Modalidad es requerido',
            'idmodalidad.unique' =>'El Tipo de Modalidad ya esta registrado con el periodo'
            
            
        ];
        $this->validate($request,[
            'tea' => 'required',
            'identidad' => 'required|integer',
            'idmodalidad' => 'required|unique:tasa_int,idmodalidad,NULL,id,identidad,'.$request->identidad 
           
            
        ],$messages);
        
        $tasa = new Tasainteres;
        $tasa->identidad=$request->get('identidad');
        $tasa->idmodalidad=$request->get('idmodalidad');
        $tasa->tea=$request->get('tea');
        $tasa->save();
     
        return $tasa;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $tasa=Tasainteres::findOrFail($id);
        $this->validate($request,[
            'idmodalidad' => 'required|unique:tasa_int,idmodalidad,'.$tasa->id.',id,identidad,'.$request->identidad
            ],[
            'idmodalidad.unique' => 'La modalidad ya esta registrado con la entidad']);  
            $tasa->update($request->only('identidad','idmodalidad','tea'));

            return $tasa;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tasa = Tasainteres::findOrFail($id);
        // delete the user
        $tasa->estado=0;
        $tasa->save();

        return $tasa;
    }
}
