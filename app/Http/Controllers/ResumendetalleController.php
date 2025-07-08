<?php

namespace App\Http\Controllers;

use App\Models\Resumendetalle;
use Illuminate\Http\Request;

class ResumendetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $resumendetalle = Resumendetalle::all();
       return $resumendetalle;
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
        $resumendetalle = new Resumendetalle;
        $resumendetalle->envio_id = $request->get('envio_id');
        $resumendetalle->comprobante_id = $request->get('comprobante_id');
        $resumendetalle->condicion = $request->get('condicion');
        $resumendetalle->direccion = $request->get('direccion');
        $resumendetalle->save();  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resumendetalle  $resumendetalle
     * @return \Illuminate\Http\Response
     */
    public function show(Resumendetalle $resumendetalle)
    {
        $resumendetalle = Resumendetalle::findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resumendetalle  $resumendetalle
     * @return \Illuminate\Http\Response
     */
    public function edit(Resumendetalle $resumendetalle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resumendetalle  $resumendetalle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resumendetalle $resumendetalle)
    {
        $resumendetalle = Resumendetalle::findOrFail($id);        
        $resumendetalle->update($request->all());        
        return $resumendetalle;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resumendetalle  $resumendetalle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resumendetalle $resumendetalle)
    {
        $resumendetalle = Resumendetalle::findOrFail($id);
        $resumendetalle->estado=0;
        $resumendetalle->save();

        return $resumendetalle;
    }
}
