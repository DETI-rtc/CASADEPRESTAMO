<?php

namespace App\Http\Controllers;

use App\Models\Tiponotacredito;
use Illuminate\Http\Request;


class TiponotacreditoController extends Controller
{
    
    public function index()
    {
       $tiponotacredito = tiponotacredito::all();
       return $tiponotacredito;
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $tiponotacredito = new tiponotacredito;
        $tiponotacredito->descripcion=$request->get('name');
        $tiponotacredito->save();  
    }
    public function show($id)
    {
        $tipo = tiponotacredito::findOrFail($id);
        
        return response()->json($tipo);
    }
    public function edit(tiponotacredito $tiponotacredito)
    {
        //
    }
    public function update(Request $request, tiponotacredito $tiponotacredito)
    {
        $tiponotacredito = tiponotacredito::findOrFail($id);        
        $tiponotacredito->update($request->all());        
        return $tiponotacredito;   
    }
    public function destroy(tiponotacredito $tiponotacredito)
    {
        $tiponotacredito = tiponotacredito::findOrFail($id);
        $tiponotacredito->estado=0;
        $tiponotacredito->save();

        return $tiponotacredito;
    }
}
