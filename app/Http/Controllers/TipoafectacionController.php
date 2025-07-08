<?php

namespace App\Http\Controllers;

use App\Models\Tipoafectacion;
use Illuminate\Http\Request;

class TipoafectacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $tipoafectacion = Tipoafectacion::all();
       return $tipoafectacion;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipoafectacion = new Tipoafectacion;
        $tipoafectacion->descripcion = $request->get('descripcion');
        $tipoafectacion->letra = $request->get('letra');
        $tipoafectacion->codigo = $request->get('codigo');
        $tipoafectacion->nombre = $request->get('nombre');
        $tipoafectacion->tipo = $request->get('tipo');
        $tipoafectacion->save();  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tipoafectacion  $tipoafectacion
     * @return \Illuminate\Http\Response
     */
    public function show(Tipoafectacion $tipoafectacion)
    {
        $tipoafectacion = Tipoafectacion::findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tipoafectacion  $tipoafectacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Tipoafectacion $tipoafectacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tipoafectacion  $tipoafectacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipoafectacion $tipoafectacion)
    {
        $tipoafectacion = Tipoafectacion::findOrFail($id);        
        $tipoafectacion->update($request->all());        
        return $tipoafectacion;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tipoafectacion  $tipoafectacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tipoafectacion $tipoafectacion)
    {
        $tipoafectacion = Tipoafectacion::findOrFail($id);
        $tipoafectacion->estado=0;
        $tipoafectacion->save();

        return $tipoafectacion;
    }
}
