<?php

namespace App\Http\Controllers;

use App\Models\Envioresumen;
use Illuminate\Http\Request;

class EnvioresumenController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $envioresumen = Envioresumen::all();
       return $envioresumen;
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
        $envioresumen = new Envioresumen;
        $envioresumen->fecha_envio = $request->get('fecha_envio');
        $envioresumen->fecha_referencia = $request->get('fecha_referencia');
        $envioresumen->correlativo = $request->get('correlativo');
        $envioresumen->resumen = $request->get('resumen');
        $envioresumen->baja = $request->get('baja');
        $envioresumen->nombrexml = $request->get('nombrexml');
        $envioresumen->mensaje_sunat = $request->get('mensaje_sunat');
        $envioresumen->codigo_sunat = $request->get('codigo_sunat');
        $envioresumen->ticket = $request->get('ticket');
        $envioresumen->estado = $request->get('estado');
        $envioresumen->save();  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Envioresumen  $envioresumen
     * @return \Illuminate\Http\Response
     */
    public function show(Envioresumen $envioresumen)
    {
        $envioresumen = Envioresumen::findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Envioresumen  $envioresumen
     * @return \Illuminate\Http\Response
     */
    public function edit(Envioresumen $envioresumen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Envioresumen  $envioresumen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Envioresumen $envioresumen)
    {
        $envioresumen = Envioresumen::findOrFail($id);        
        $envioresumen->update($request->all());        
        return $envioresumen;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Envioresumen  $envioresumen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Envioresumen $envioresumen)
    {
        $envioresumen = Envioresumen::findOrFail($id);
        $envioresumen->estado=0;
        $envioresumen->save();

        return $envioresumen;
    }
}
