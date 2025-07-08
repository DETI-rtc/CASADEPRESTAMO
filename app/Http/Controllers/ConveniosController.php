<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Convenio;

class ConveniosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empre=Convenio::with('empresa')->get();
        // foreach ($empre as $item) {
        //     // DD($empre->id_deta_list);
        //     $sector = Delista::where('id',$item->id_deta_list)->get()->first();
        //     $item->sector=$sector->nomdelista;
        // }
        // DD($empre);
        return $empre;
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
            'fecha_ini.required' => 'La fecha de Inicio es requerido',
            'fecha_fin.required' => 'La fecha de Final es requerido',
            'idempresa.required' =>'La empresa es requerido',
            'nro.requerid' =>'El numero es requerido'
            
            
        ];
        $this->validate($request,[
            'fecha_ini' => 'required',
            'fecha_fin' => 'required',
            'idempresa' => 'required',
            'nro' => 'required|unique:convenio'
           
            
        ],$messages);
        
        $empre = new Convenio;
        $empre->idempresa=$request->get('idempresa');
        $empre->entidad=$request->get('entidad');
        $empre->nro=$request->get('nro');
        $empre->fecha_ini=$request->get('fecha_ini');
        $empre->fecha_fin=$request->get('fecha_fin');
        $empre->iduser=auth('sanctum')->user()->id;
        $empre->save();
     
        return $empre;
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
        $empre = Convenio::findOrFail($id);

        $this->validate($request,[
            'nro' => 'required|unique:convenio,idempresa,'.$empre->id            
        ]);
        $empre->idempresa=$request->get('idempresa');
        $empre->empresa=$request->get('empresa');
        $empre->nro=$request->get('nro');
        $empre->fecha_ini=$request->get('fecha_ini');
        $empre->fecha_fin=$request->get('fecha_fin');
        $empre->iduser=auth('sanctum')->user()->id;
        $empre->save();
        //DD($request);
       // $empre->update($request->all());
        
        return $empre;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empre = Convenio::findOrFail($id);
        // deletplazo the user
        $empre->estado=0;
        $empre->save();
    }
}
