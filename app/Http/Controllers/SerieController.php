<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $serie = Serie::all();
       return$serie;
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
       $serie = new Serie;
       $serie->tipo_comprobante_id = $request->get('tipo_comprobante_id');
       $serie->serie = $request->get('serie');
       $serie->correlativo = $request->get('correlativo');
       $serie->save();  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Serie $serie
     * @return \Illuminate\Http\Response
     */
    public function show(Serie$serie)
    {
       $serie = Serie::findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Serie $serie
     * @return \Illuminate\Http\Response
     */
    public function edit(Serie$serie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Serie $serie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Serie$serie)
    {
       $serie = Serie::findOrFail($id);        
       $serie->update($request->all());        
        return$serie;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Serie $serie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Serie$serie)
    {
       $serie = Serie::findOrFail($id);
       $serie->estado=0;
       $serie->save();

        return$serie;
    }
}
