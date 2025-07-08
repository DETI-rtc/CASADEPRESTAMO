<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Database\Eloquent\Collection;

class ComisionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        //
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

    public function getCuotasAtrasadasByDate(Request $request){
        $anio = $request->anio;
        if ($request->mes == '12') {
            $mesAntes = 1;
            $anioA = $anio + 1;
            $mesFin = $anioA.'-'. $mesAntes .'-20';
            $mesInicio = $anio.'-'. $request->mes .'-19';
        } else {
            $mesAntes = floatval($request->mes) + 1;
            $mesInicio = $anio.'-'. $request->mes .'-19';
            $mesFin = $anio.'-'. $mesAntes .'-20';
        }
        $s =  DB::select("call obtener_resumen_retrasocuotas('$mesInicio','$mesFin')");
        return $s;
    }

    public function getCumplimientoMeta(Request $request){
        // DD($request->mes,$request->anio);
        $data = DB::select("CALL com_cumplimiento_meta($request->mes,$request->anio)");
        return $data;
    }

    public function getSatisfaccion(Request $request){
        DD($request);
        $data = DB::select("select ca.promedio,c.idanalista ,c.*, concat(u.nombres,' ', u.apellidos) as nombre_analista 
        from pre_calificacion_analista ca
        inner join pre_det_calificacion d on ca.id = d.idcalificacion 
        inner join credito c on d.idcredito = c.id
        inner join users u on c.idanalista = u.id
        inner join model_has_roles m on u.id = m.model_id 
        inner join comisiones com on m.role_id = com.idrol
        where EXTRACT(MONTH from c.fecha_des)=$request->mes
        and year(c.fecha_des)=$request->anio  group by c.idanalista");
        // if (count($data)<1) {
        //     # code...
            
        // }
        return $data;
    }

    public function calcularComisiones(Request $request){

        $i = new Collection();

        $anio = $request->anio;
        if ($request->mes == '12') {
            $mesAntes = 1;
            $anioA = $anio + 1;
            $mesFin = $anioA.'-'. $mesAntes .'-20';
            $mesInicio = $anio.'-'. $request->mes .'-19';
        } else {
            $mesAntes = floatval($request->mes) + 1;
            $mesInicio = $anio.'-'. $request->mes .'-19';
            $mesFin = $anio.'-'. $mesAntes .'-20';
        }
        $metariesgo = DB::select("call obtener_resumen_retrasocuotas('$mesInicio','$mesFin')");

        $cumplimientoMeta = DB::select("CALL com_cumplimiento_meta($request->mes,$request->anio)");

        $satisfaccion = DB::select("select ca.promedio,c.idanalista ,c.*, concat(u.nombres,' ', u.apellidos) as nombre_analista 
        from pre_calificacion_analista ca
        inner join pre_det_calificacion d on ca.id = d.idcalificacion 
        inner join credito c on d.idcredito = c.id
        inner join users u on c.idanalista = u.id
        inner join model_has_roles m on u.id = m.model_id 
        inner join comisiones com on m.role_id = com.idrol
        where EXTRACT(MONTH from c.fecha_des)=$request->mes
        and year(c.fecha_des)=$request->anio  group by c.idanalista");

        $usuarios = DB::select("select r.*, u.* , r.id as roleid, r.name rolename
        from users u
        inner join model_has_roles m
        on u.id = m.model_id 
        inner join roles r 
        on m.role_id = r.id
        where (r.id = 7 or r.id = 4)");

        foreach ($usuarios  as $u) {
            $comi_general = DB::table('comisiones')->where('idrol', $u->roleid)->first();
            $u->dataCom = $comi_general;
            foreach ($cumplimientoMeta as $cm) {
                if ($u->id == $cm->idanalista) {
                    $u->cumplimientometa = $cm; 
                    $u->meta = $cm->meta;
                    $u->desembolsado = $cm->desembolso;
                    $u->cum_meta_porc = (floatval($cm->desembolso) / floatval($cm->meta))*100;
                    $u->cum_meta_porc_total = (((floatval($cm->desembolso) / floatval($cm->meta))*100) / 100) * floatval($comi_general->met_com);
                } else {
                    $u->meta = 0;
                    $u->desembolsado = 0;
                    $u->cum_meta_porc = 0;
                }
            }
            foreach ($metariesgo as $mc) {
                if ($u->id == $mc->idanalista) {
                    $u->metariesgo = $mc;
                    $u->montomensual = $mc->MontoMensual;
                    $u->montonopagado = $mc->MontoNoPagado;
                    $u->montopagado = $mc->MontoPagado;

                    $u->cum_riesgo_porc = (floatval($mc->MontoPagado) / floatval($mc->MontoMensual))*100;
                    $u->cum_riesgo_porc_total = (((floatval($mc->MontoPagado) / floatval($mc->MontoMensual))*100) / 100) * floatval($comi_general->met_ries);

                } else {
                    $u->montomensual = 0;
                    $u->montonopagado = 0;
                    $u->montopagado = 0;
                }
            }

            foreach ($satisfaccion as $s) {
                if ($u->id == $s->idanalista) {
                    $u->satisfaccion = $s;
                    $u->promedio = $s->promedio;
                    $u->cum_calidad_porc = (floatval($s->promedio) / 10)*100;
                    $u->cum_calidad_porc_total = (((floatval($s->promedio) / 10)*100) / 100) * floatval($comi_general->cal_proc);
                }else{
                    $u->promedio = $s->promedio;
                }
            }

            $i->push($u);
        }

        $supervisor = DB::select("select r.*, u.*, r.id as roleid, r.name rolename
        from users u
        inner join model_has_roles m
        on u.id = m.model_id 
        inner join roles r 
        on m.role_id = r.id
        where r.id = 3");

        foreach ($supervisor as $s) {
            $comi_general = DB::table('comisiones')->where('idrol', $s->roleid)->first();
            $s->dataCom = $comi_general;
            $meta=0;
            $desembolsado=0;

            //META DE MORA
            $montomensual = 0;
            $montonopagado = 0;
            $montopagado = 0;

            //SATISFACCION
            $promedios = 0;

            
            foreach ($usuarios as $uu) {
                if (isset($uu->cumplimientometa) && isset($uu->metariesgo) && isset($uu->satisfaccion)) {
                    $meta = $meta + floatval($uu->cumplimientometa->meta);
                    $desembolsado = floatval($desembolsado) +  floatval($uu->cumplimientometa->desembolso); 
                    //MORA
                    $montomensual =  $montomensual + floatval($uu->montomensual);    
                    $montonopagado = $montonopagado + floatval($uu->montonopagado);    
                    $montopagado =   $montopagado + floatval($uu->montopagado);
                    
                    //SATISFACCION
                    $promedios = $promedios  + floatval($uu->promedio);
                    // DD(count($satisfaccion));
                    
                }
                
            }
            
            
            
            $s->meta = $meta;
            $s->desembolsado = $desembolsado;
            $s->montomensual = $montomensual;
            $s->montonopagado = $montonopagado;
            $s->montopagado = $montopagado;

            if($meta > 0){

                $s->cum_meta_porc = (floatval($desembolsado) / floatval($meta))*100;
                $s->cum_meta_porc_total = (((floatval($desembolsado) / floatval($meta))*100) / 100) * floatval($comi_general->met_com);
            } else {
                $s->cum_meta_porc =0; 
                $s->cum_meta_porc_total =0;
            }
            
            if($montomensual > 0){

                $s->cum_riesgo_porc = (floatval($montomensual) / floatval($montomensual))*100;
                $s->cum_riesgo_porc_total = (((floatval($mc->MontoPagado) / floatval($montomensual))*100) / 100) * floatval($comi_general->met_ries);
            }else{
                $s->cum_riesgo_porc =0; 
                $s->cum_riesgo_porc_total =0;
            }


            $cant = count($satisfaccion);
            if ($cant == 0) {
                $s->promedio = 0;
            }else {
                $s->promedio = $promedios / $cant;
            }

            $s->cum_calidad_porc = (floatval($s->promedio) / 10)*100;
            $s->cum_calidad_porc_total = (((floatval($s->promedio) / 10)*100) / 100) * floatval($comi_general->cal_proc);

            $i->push($s);
        }

         
        return $i;
        }
    
}
