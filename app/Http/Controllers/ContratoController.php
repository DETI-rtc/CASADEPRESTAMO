<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrato;
use App\Models\Country;
use App\Models\Department;
use App\Models\District;
use App\Models\Province;
use DB;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Contrato::where('id_user', auth('sanctum')->user()->id)->orderBy('nro','desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nro = Contrato::where('estado',1)->orderBy('nro','desc')->get()->first()->nro;
        $request['nro'] = $nro+1;
        $request['estado'] = 1;
        $request['id_user'] = auth('sanctum')->user()->id;
        $data = Contrato::create($request->all());
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Contrato::with('tipo_contrato','provincias','distritos')->find($id);
        return $data;
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
        $contratoBefore = Contrato::find($id);
        $contratoBefore->update($request->all());
        return $contratoBefore;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Contrato::find($id)->update(['estado' => 0]);
        return $id;
    }

    public function getLocation(){
        // DD('sadas');
        // return 'ok';
        $countries = Country::all();
        $departments = Department::all();
        $provinces = Province::all();
        $districts = District::all();
        return compact('countries','departments','provinces','districts');
    }
    public function getTiposContrato(){
        $data = DB::table('tipo_contratos')->where('estado',1)->get();
        return $data;
    }
    public function getNroContrato(){
        $nro = Contrato::orderBy('nro','desc')->get()->first()->nro;
        return $nro+1;
    }
    public function getContratosActivos(){
        $data = Contrato::where('estado',1)->get();
        return $data;
    }
}
