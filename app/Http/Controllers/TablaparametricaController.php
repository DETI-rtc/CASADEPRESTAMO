<?php

namespace App\Http\Controllers;

use App\Models\Tablaparametrica;
use Illuminate\Http\Request;

class TablaparametricaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $tablaparametrica = Tablaparametrica::all();
       return $tablaparametrica;
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
        $tablaparametrica = new Tablaparametrica;
        $tablaparametrica->tipo=$request->get('tipo');
        $tablaparametrica->codigo=$request->get('codigo');
        $tablaparametrica->descripcion=$request->get('descripcion');
        $tablaparametrica->save();  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tablaparametrica  $tablaparametrica
     * @return \Illuminate\Http\Response
     */
    public function show(Tablaparametrica $tablaparametrica)
    {
        $tablaparametrica = Tablaparametrica::findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tablaparametrica  $tablaparametrica
     * @return \Illuminate\Http\Response
     */
    public function edit(Tablaparametrica $tablaparametrica)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tablaparametrica  $tablaparametrica
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tablaparametrica $tablaparametrica)
    {
        $tablaparametrica = Tablaparametrica::findOrFail($id);        
        $tablaparametrica->update($request->all());        
        return $tablaparametrica;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tablaparametrica  $tablaparametrica
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tablaparametrica $tablaparametrica)
    {
        $tablaparametrica = Tablaparametrica::findOrFail($id);
        $tablaparametrica->estado=0;
        $tablaparametrica->save();

        return $tablaparametrica;
    }
}
