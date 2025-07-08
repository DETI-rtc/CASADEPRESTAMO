<?php

namespace App\Http\Controllers;

use App\Models\Tiponotadebito;
use Illuminate\Http\Request;


class TiponotadebitoController extends Controller
{
    public function index()
    {
       $tiponotadebito = tiponotadebito::all();
       return $tiponotadebito;
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $tiponotadebito = new tiponotadebito;
        $tiponotadebito->descripcion=$request->get('name');
        $tiponotadebito->save();  
    }
    public function show($id)
    {
        $tipo = tiponotadebito::findOrFail($id);
        
        return response()->json($tipo);
    }
    public function edit(tiponotadebito $tiponotadebito)
    {
        //
    }
    public function update(Request $request, tiponotadebito $tiponotadebito)
    {
        $tiponotadebito = tiponotadebito::findOrFail($id);        
        $tiponotadebito->update($request->all());        
        return $tiponotadebito;   
    }
    public function destroy(tiponotadebito $tiponotadebito)
    {
        $tiponotadebito = tiponotadebito::findOrFail($id);
        $tiponotadebito->estado=0;
        $tiponotadebito->save();

        return $tiponotadebito;
    }
}
