<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Relacionci;

class RelacionCIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reci=Relacionci::with('entidad','modalidad')->get();
        return $reci;
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
            'rci_max.required' => 'La rci_max es requerido',
            'identidad.required' => 'El Tipo Entidad es requerido',
            'idmodalidad.required' =>'El Tipo de Modalidad es requerido',
            'idmodalidad.unique' =>'El Tipo de Modalidad ya esta registrado con el periodo'
            
            
        ];
        $this->validate($request,[
            'rci_max' => 'required',
            'identidad' => 'required|integer',
            'idmodalidad' => 'required|unique:pre_rci,idmodalidad,NULL,id,identidad,'.$request->identidad 
           
            
        ],$messages);
        
        $reci = new Relacionci;
        $reci->identidad=$request->get('identidad');
        $reci->idmodalidad=$request->get('idmodalidad');
        $reci->rci_max=$request->get('rci_max');
        $reci->save();
     
        return $reci;
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
        $reci=Relacionci::findOrFail($id);
        $this->validate($request,[
            'idmodalidad' => 'required|unique:reci_int,idmodalidad,'.$reci->id.',id,identidad,'.$request->identidad
            ],[
            'idmodalidad.unique' => 'La modalidad ya esta registrado con la entidad']);  
            $reci->update($request->only('identidad','idmodalidad','rci_max'));

            return $reci;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reci = Relacionci::findOrFail($id);
        // delete the user
        $reci->estado=0;
        $reci->save();

        return $reci;
    }
}
