<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestList;
use Illuminate\Http\Request;
use App\Models\Lista;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ListaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = Lista::with('detalle')->get();
        return $lista;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestList $request)
    {   
       
        DD(auth('api')->user());
        $lista = new Lista;
        $lista->nomlista = $request->nomlista;
        $lista->estado=1;
        $lista->created_by = auth('api')->user()->id; 
        $lista->updated_by = auth('api')->user()->id; 
        $lista->save();
        return $lista;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $lista = Lista::find($id);
        $lista->updated_by =  auth('api')->user()->id;
        $lista->update($request->all());
        return $lista;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lista = Lista::find($request->id);
        $lista->estado = 0;
        $lista->updated_by =  auth('api')->user()->id();
        $lista->save();
        return $lista;
    }
}
