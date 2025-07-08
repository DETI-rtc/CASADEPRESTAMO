<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestDelista;
use Illuminate\Http\Request;
use App\Models\Delista;

class DelistaController extends Controller
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
        $delista = Delista::all();
        return response()->json($delista);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestDelista $request)
    {   
        $delista = new Delista;
        $delista->nomdelista = $request->nomdelista;
        $delista->idlista = $request->idlista;
        $delista->estado=1;
        $delista->created_by =  auth('sanctum')->user()->id;
        $delista->updated_by = auth('sanctum')->user()->id; 
        $delista->save();
        $detalle = Delista::where('idlista','=',$request->idlista)->get();
        return $detalle;
    }
    public function edit($id)
    {
        $delista = Delista::findOrFail($id);
        return  $delista;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $delista = Delista::where('idlista',$id)->get();
        //DD($delista);
        return $delista;
        // $delista =Delista::where('id' === $id)->get();
        // DD($id);
        // return  $delista;   
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
       
        $delista = Delista::find($id);
        $delista->updated_by =  auth('sanctum')->user()->id;
        $delista->update($request->all());
        $delista1 = Delista::where('idlista','=',$request->idlista)->get();

        return $delista1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delista = Delista::find($request->id);
        $delista->estado = 0;
        $delista->updated_by =  auth('sanctum')->user()->id();
        $delista->save();
        return $delista;
    }
    
}
