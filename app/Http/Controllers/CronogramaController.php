<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cronograma;
use App\Models\detacronograma;
use App\Models\Empresa;
use App\Models\Credito;
use App\Models\Persona;
use DB;


class CronogramaController extends Controller
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
        $crono = new Cronograma;
        $crono->iduser=$request->get('iduser');
        $crono->idcliente=$request->get('idcliente');
        $crono->estado=$request->get('estado');
        $crono->idcredito=$request->get('idcredito');
        $crono->tasa_men_desgra=$request->get('tasa_men_desgra');
        $crono->com_desc_auto=$request->get('com_desc_auto');
        $crono->tasa_efe_anu=$request->get('tasa_efe_anu');
        $crono->t_interes=$request->get('t_interes');
        $crono->num_cuotas=$request->get('num_cuotas');
        $crono->periocidad=$request->get('periocidad');
        $crono->f_ultima_cuota=$request->get('f_ultima_cuota');
        $crono->com_desembolso=$request->get('com_desembolso');
        $crono->mon_ne_desem=$request->get('mon_ne_desem');
        $crono->f_desembolso=$request->get('f_desembolso');
        $crono->mon_financiado=$request->get('mon_financiado');


        $crono->save();

        return $crono;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $credito = Credito::where('id',$id)
        $cronograma=Cronograma::where('idcredito', $id)->get()->first();
        // DD($cronograma);
        return $cronograma;
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

    public function getbyidcliente($id){
        $cronograma = Cronograma::where('idcliente',$id)->get()->first();
        return $cronograma;
    }
    public function detallecronograma(Request $request)
    {

           // DD($request);
            $detalle = DB::table('cronograma as c')->join('detallecrono as d','c.id','=','d.idcrono')
            ->select(DB::raw("d.id as idcrono,d.fecha_ven,cuotanro,saldo_ade,cuota,cap_amor,sal_cap,interes,com_des,seg_des, concat(d.fecha_ven,' - ',cuotanro)as cuotas"))
            ->where('d.estado',0)->where('idcredito',$request->idcredito)->get();
            return $detalle;

    }
    public function detallecancela(Request $request)
    {
            $detalle = DB::table('cronograma as c')
            ->join('detallecrono as d','c.id','=','d.idcrono')
            ->select(DB::raw("d.seg_des, d.cap_amor ,d.interes, d.com_des ,d.idcrono,d.fecha_ven,sal_cap,num_cuotas-cuotanro+1 as cuotas_fal"))
            ->where('d.estado',0)
            ->where('idcredito',$request->idcredito)
            ->first();
            // $credito = Credito::find($request->idcredito);
            // $persona = Persona::find($credito->idpersona);
            // $empresa = Empresa::find($persona->idempresa);
            // $detalle->empresa = $empresa->razonsocial;
            // $detalle->dni = $persona->dni;
            return response()->json($detalle);

    }

    public function viewcrono($id){
        $data = Cronograma::where('idcredito',$id)
        ->with('cuotas',function($query){
            $query->where('amortizado','0');
        })->first();
        foreach ($data->cuotas as $c) {
            $c->num_cuota = $c->cuotanro;
            $c->fecha_vencimiento = $c->fecha_ven;
            $c->saldo_capital = $c->sal_cap;
            $c->capital_amortizado = $c->cap_amor;
            // $c->interes = $c->interes;
            $c->com_desc_automatico = $c->com_des;
            $c->seguro_degrav = $c->seg_des;
        }
        return $data;
    }

    public function buscarVencidas(Request $request){
        $anio = date('Y');
        $anio = $request->anio;
        if ($request->mes == '1') {
            $mesAntes = 12;
            $anioA = $anio - 1;
            $mesInicio = $anioA.'-'. $mesAntes .'-19';
            $mesFin = $anio.'-'. $request->mes .'-20';
        } else {
            $mesAntes = floatval($request->mes) - 1;
            $mesInicio = $anio.'-'. $mesAntes .'-19';
            $mesFin = $anio.'-'. $request->mes .'-20';
        }

        $data = DB::select("select date_format(fecha_ven, '%d-%m-%Y') as fecha_ven, 
        cr.id as idcredito, 
        concat(p.nombres,' ',p.paterno, ' ' ,p.materno) as cliente, 
        d.cuotanro 
        from detallecrono d
        inner join cronograma c
        on d.idcrono = c.id
        inner join credito cr
        on c.idcredito = cr.id
        inner join persona p 
        on cr.idpersona = p.id
        where d.fecha_ven > '$mesInicio' and d.fecha_ven < '$mesFin'
        and d.atrasado = 1
        order by fecha_ven ");
        return $data;
    }

    public function getCuotas(Request $request){
        // DD('mes',$request->mes,'entidad', $request->entidad);
        //DD($request);and year(d.fecha_ven) = year(now())
        $data = DB::select("select IF(d.sal_pen is null , d.cuota , d.sal_pen) as pendiente,d.id as idcuota, p.id as idcliente, p.dni,c.id as idcredito, IF(d.sal_pen is null , d.cuota , d.sal_pen)  as montopagar, 0 as montopagarefec ,c.numero, concat(p.nombres, ' ' , p.paterno, ' ', p.materno) as cliente , d.*
        from detallecrono d 
        inner join cronograma cr on d.idcrono = cr.id 
        inner join credito c on cr.idcredito = c.id
        inner join persona p on c.idpersona = p.id
        where year(d.fecha_ven) = $request->anio
        and month(d.fecha_ven) = $request->mes
        and p.idempresa = $request->entidad
        and c.estado_cred !='CA'
        and c.situacion_apro='A'
        and situacion = 'P'        
        order by p.nombres");

        return $data;
    }
}
