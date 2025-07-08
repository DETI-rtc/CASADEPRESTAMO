<?php

namespace App\Http\Controllers;

use App\Models\Tipocomprobante;
use Illuminate\Http\Request;
use DB;

class TipocomprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $tipocomprobante = Tipocomprobante::where('estado',1)->get();
       return $tipocomprobante;
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
        $tipocomprobante = new Tipocomprobante;
        $tipocomprobante->descripcion=$request->get('descripcion');
        $tipocomprobante->save();  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tipocomprobante  $tipocomprobante
     * @return \Illuminate\Http\Response
     */
    public function show(Tipocomprobante $tipocomprobante)
    {
        $tipocomprobante = Tipocomprobante::findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tipocomprobante  $tipocomprobante
     * @return \Illuminate\Http\Response
     */
    public function edit(Tipocomprobante $tipocomprobante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tipocomprobante  $tipocomprobante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipocomprobante $tipocomprobante)
    {
        $tipocomprobante = Tipocomprobante::findOrFail($id);        
        $tipocomprobante->update($request->all());        
        return $tipocomprobante;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tipocomprobante  $tipocomprobante
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tipocomprobante $tipocomprobante)
    {
        $tipocomprobante = Tipocomprobante::findOrFail($id);
        $tipocomprobante->estado=0;
        $tipocomprobante->save();

        return $tipocomprobante;
    }

    public function motivoNotaCredito() {
        $motivocreditos = DB::Table('tipo_notacredito')->where('estado',1)->get();
        $motivodebitos = DB::Table('tipo_notadebito')->where('estado',1)->get();
        return [$motivocreditos, $motivodebitos];
    }
}
