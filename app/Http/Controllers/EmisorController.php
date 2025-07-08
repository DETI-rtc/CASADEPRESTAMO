<?php

namespace App\Http\Controllers;

use App\Models\Emisor;
use Illuminate\Http\Request;

class EmisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $emisor = Emisor::all();
       return $emisor;
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
        $emisor = new Emisor;
        $emisor->tipodoc = $request->get('tipodoc');
        $emisor->ruc = $request->get('ruc');
        $emisor->razon_social = $request->get('razon_social');
        $emisor->nombre_comercial = $request->get('nombre_comercial');
        $emisor->direccion = $request->get('direccion');
        $emisor->pais = $request->get('pais');
        $emisor->departamento = $request->get('departamento');
        $emisor->provincia = $request->get('provincia');
        $emisor->distrito = $request->get('distrito');
        $emisor->ubigeo = $request->get('ubigeo');
        $emisor->usurio_sol = $request->get('usurio_sol');
        $emisor->clave_sol = $request->get('clave_sol');
        $emisor->save();  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Emisor  $emisor
     * @return \Illuminate\Http\Response
     */
    public function show(Emisor $emisor)
    {
        $emisor = Emisor::findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Emisor  $emisor
     * @return \Illuminate\Http\Response
     */
    public function edit(Emisor $emisor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Emisor  $emisor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Emisor $emisor)
    {
        $emisor = Emisor::findOrFail($id);        
        $emisor->update($request->all());        
        return $emisor;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Emisor  $emisor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Emisor $emisor)
    {
        $emisor = Emisor::findOrFail($id);
        $emisor->estado=0;
        $emisor->save();

        return $emisor;
    }
}
