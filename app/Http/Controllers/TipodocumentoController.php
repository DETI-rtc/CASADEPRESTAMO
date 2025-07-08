<?php

namespace App\Http\Controllers;

use App\Models\Tipodocumento;
use Illuminate\Http\Request;


class TipodocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $tipodocumento = Tipodocumento::all();
       return $tipodocumento;
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
        $tipodocumento = new Tipodocumento;
        $tipodocumento->descripcion=$request->get('name');
        $tipodocumento->save();  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tipodocumento  $tipodocumento
     * @return \Illuminate\Http\Response
     */
    public function show(Tipodocumento $tipodocumento)
    {
        $tipodocumento = Tipodocumento::findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tipodocumento  $tipodocumento
     * @return \Illuminate\Http\Response
     */
    public function edit(Tipodocumento $tipodocumento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tipodocumento  $tipodocumento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipodocumento $tipodocumento)
    {
        $tipodocumento = Tipodocumento::findOrFail($id);        
        $tipodocumento->update($request->all());        
        return $tipodocumento;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tipodocumento  $tipodocumento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tipodocumento $tipodocumento)
    {
        $tipodocumento = Tipodocumento::findOrFail($id);
        $tipodocumento->estado=0;
        $tipodocumento->save();

        return $tipodocumento;
    }
}
