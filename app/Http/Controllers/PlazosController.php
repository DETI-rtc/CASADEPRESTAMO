<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\Plazo;
class PlazosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plazo=Plazo::with('entidad','modalidad')->get();
        return $plazo;
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
            'mont_min.required' => 'La Monto Minimo es requerido',
            'mont_max.required' => 'La Monto Maximo es requerido',
            'pla_min.required' => 'La Plazo Minimo es requerido',
            'pla_max.required' => 'La Plazo maximo es requerido',
            'identidad.required' => 'El Tipo Entidad es requerido',
            'idmodalidad.required' =>'El Tipo de Modalidad es requerido',
            'idmodalidad.unique' =>'El Tipo de Modalidad ya esta registrado con el periodo'
            
            
        ];
        $this->validate($request,[
            'mont_min' => 'required',
            'mont_max' => 'required',
            'pla_min' => 'required',
            'pla_max' => 'required',
            'identidad' => 'required|integer',
            'idmodalidad' => 'required|unique:plazo_max,idmodalidad,NULL,id,identidad,'.$request->identidad 
           
            
        ],$messages);
        
        $plazo = new Plazo;
        $plazo->identidad=$request->get('identidad');
        $plazo->idmodalidad=$request->get('idmodalidad');
        $plazo->mont_min=$request->get('mont_min');
        $plazo->mont_max=$request->get('mont_max');
        $plazo->pla_min=$request->get('pla_min');
        $plazo->pla_max=$request->get('pla_max');
        $plazo->save();
     
        return $plazo;
    }


     /* Display the specified resource.
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
        $plazo=Plazo::findOrFail($id);
        $this->validate($request,[
            'idmodalidad' => 'required|unique:plazo_max,idmodalidad,'.$plazo->id.',id,identidad,'.$request->identidad
            ],[
            'idmodalidad.unique' => 'La modalidad ya esta registrado con la entidad']); 
         
        $plazo->update($request->only('identidad','idmodalidad','mont_min','mont_max','pla_min','pla_max'));

        return $plazo;
    }
     /* Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plazo = Plazo::findOrFail($id);
        // deletplazo the user
        $plazo->estado=0;
        $plazo->save();

        return $plazo;
    }
}