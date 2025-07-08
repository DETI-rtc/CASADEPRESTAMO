<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $almacen = almacen::all();
       return $almacen;
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
        $id = $request->input('id');
        $almacen = almacen::firstOrNew(['id' => $id]);
       // $almacen = new almacen;
        $almacen->fill($request->all());
        $almacen->save();  
        return [
            'success' => true,
            'message' => ($id)?'Categoría editada con éxito':'Categoría registrada con éxito',
            'data' => $almacen

        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\almacen  $almacen
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        $almacen = almacen::findOrFail($id);
        
        return response()->json($almacen);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\almacen  $almacen
     * @return \Illuminate\Http\Response
     */
    public function edit(almacen $almacen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\almacen  $almacen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, almacen $almacen)
    {
        $almacen = almacen::findOrFail($id);        
        $almacen->update($request->all());        
        return $almacen;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\almacen  $almacen
     * @return \Illuminate\Http\Response
     */
    public function destroy(almacen $almacen)
    {
        $almacen = almacen::findOrFail($id);
        $almacen->estado=0;
        $almacen->save();

        return $almacen;
    }
}
