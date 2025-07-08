<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calificacion;
use App\Models\DetalleCalificacion;
use Carbon\Carbon;

use DB;




class CalificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $i = DB::select("select p.*,year(p.created_at) as anio, 
        concat(u.nombres,' ' ,u.apellidos) as analista,
        u.avatar as photo
        from pre_calificacion_analista p
        inner join users u 
        on p.idanalista = u.id");
        return $i;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            $dateentero = strtotime($request->fecha);
            $anio = date("Y", $dateentero);
            $fecha = Carbon::parse($request["fecha"]);
            $date = $fecha->locale();
            $mes_letras = $fecha->monthName;
            $calificacion;
            $v_existe = Calificacion::where('idanalista',$request->idanalista)->where('mes',$mes_letras)->whereYear('created_at',$anio)->count();
    
            if ($v_existe > 0) {
                $calificacion = Calificacion::where('idanalista',$request->idanalista)->where('mes',$mes_letras)->first();
                // DD($cant_calificaciones);
                $de = new DetalleCalificacion();
                $de->idcalificacion = $calificacion->id;
                $de->idcliente = $request->idcliente;
                $de->puntaje = $request->calificacion;
                $de->fecha = $request->fecha;
                $de->idcredito = $request->idcredito;
                $de->save();
                $cant_calificaciones = DetalleCalificacion::where('idcalificacion',$calificacion->id)->count();
                $sum_puntajes= DetalleCalificacion::where('idcalificacion',$calificacion->id)->sum('puntaje');
                $promedio = floatval($sum_puntajes)/floatval($cant_calificaciones);
                $up = Calificacion::where('id',$calificacion->id)->update(['promedio'=>$promedio]);
            } else {
                $calificacion = new Calificacion();
                $calificacion->idanalista = $request->idanalista;
                $calificacion->mes = $mes_letras;
                $calificacion->save();
    
                $de = new DetalleCalificacion();
                $de->idcalificacion = $calificacion->id;
                $de->idcliente = $request->idcliente;
                $de->puntaje = $request->calificacion;
                $de->fecha = $request->fecha;
                $de->idcredito = $request->idcredito;
                $de->save();
    
                $sum_puntajes= DetalleCalificacion::where('idcalificacion',$calificacion->id)->sum('puntaje');
                $up = Calificacion::where('id',$calificacion->id)->update(['promedio'=>$sum_puntajes]);
    
            }
            // DD($calificacion);
            DB::commit();
            return $calificacion;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        // dd($fecha->monthName);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
