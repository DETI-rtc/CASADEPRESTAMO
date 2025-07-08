<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Relaciondi;

class RelacionDIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasa=Relaciondi::with('entidad','modalidad')->get();
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
            'rdi_max.required' => 'La rdi_max es requerido',
            'identidad.required' => 'El Tipo Entidad es requerido',
            'idmodalidad.required' =>'El Tipo de Modalidad es requerido',
            'idmodalidad.unique' =>'El Tipo de Modalidad ya esta registrado con el periodo'
            
            
        ];
        $this->validate($request,[
            'rdi_max' => 'required',
            'identidad' => 'required|integer',
            'idmodalidad' => 'required|unique:pre_rdi,idmodalidad,NULL,id,identidad,'.$request->identidad 
           
            
        ],$messages);
        
        $tasa = new Relaciondi;
        $tasa->identidad=$request->get('identidad');
        $tasa->idmodalidad=$request->get('idmodalidad');
        $tasa->rdi_max=$request->get('rdi_max');
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
        $tasa=Relaciondi::findOrFail($id);
        $this->validate($request,[
            'idmodalidad' => 'required|unique:pre_rdi,idmodalidad,'.$tasa->id.',id,identidad,'.$request->identidad
            ],[
            'idmodalidad.unique' => 'La modalidad ya esta registrado con la entidad']);  
            $tasa->update($request->only('identidad','idmodalidad','rdi_max'));

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
        $tasa = Relaciondi::findOrFail($id);
        // delete the user
        $tasa->estado=0;
        $tasa->save();

        return $tasa;
    }
}
