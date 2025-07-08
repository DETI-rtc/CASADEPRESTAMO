<?php

namespace App\Http\Controllers;

use App\Models\Detalle;
use Illuminate\Http\Request;

class DetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $detalle = Detalle::all();
       return $detalle;
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
        $detalle = new Detalle;
        $detalle->comprobante_id = $request->get('comprobante_id');
        $detalle->item = $request->get('item');
        $detalle->producto_id = $request->get('producto_id');
        $detalle->cantidad = $request->get('cantidad');
        $detalle->valor_unitario = $request->get('valor_unitario');
        $detalle->precio_unitario = $request->get('precio_unitario');
        $detalle->igv = $request->get('igv');
        $detalle->porcentaje_igv = $request->get('porcentaje_igv');
        $detalle->valor_total = $request->get('valor_total');
        $detalle->importe_total = $request->get('importe_total');
        $detalle->save();  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Detalle  $detalle
     * @return \Illuminate\Http\Response
     */
    public function show(Detalle $detalle)
    {
        $detalle = Detalle::findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Detalle  $detalle
     * @return \Illuminate\Http\Response
     */
    public function edit(Detalle $detalle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Detalle  $detalle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Detalle $detalle)
    {
        $detalle = Detalle::findOrFail($id);        
        $detalle->update($request->all());        
        return $detalle;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Detalle  $detalle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Detalle $detalle)
    {
        $detalle = Detalle::findOrFail($id);
        $detalle->estado=0;
        $detalle->save();

        return $detalle;
    }
}
