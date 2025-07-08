<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Persona;
use App\Models\Cuota;
use App\Models\Detacronograma;
use App\Models\Cronograma;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use DB;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        if (is_null($request->anio)) {
            $anio=intval(date('YYYY'));
        }else{
            $anio=$request->anio;
        }
       // DD($anio);
        $user = auth('sanctum')->user();
        $role = $user->getRoleNames();


        if ($role->first() == "Agente") {
          //  select pa.*, CONCAT(p.paterno,' ',p.materno,' ',p.nombres) as clientes from pago as pa INNER JOIN credito as c on pa.idcredito = c.id INNER JOIN persona as p on c.idpersona = p.id where c.idanalista = 6
          $pago = DB::table('pago as pa')->join('credito as c','pa.idcredito','=','c.id')->join('persona as  p','c.idpersona','=','p.id')
          ->select(DB::raw(' pa.*,c.numero, CONCAT(p.paterno," ",p.materno," ",p.nombres) as clientes'))
          ->where('c.idanalista',$user->id)->get();
        }

        if ($role->first() == "Supervisor"){
            $pago = DB::table('pago as pa')->join('credito as c','pa.idcredito','=','c.id')
            ->join('persona as  p','c.idpersona','=','p.id')
            ->select(DB::raw(' pa.*,c.numero, CONCAT(p.paterno," ",p.materno," ",p.nombres) as clientes'))
            ->where('c.idsupervisor',$user->id)->get();
        }
//where year(fecha_pago) <= $anio 
        else{
            if ($request->mes=='') {
                $pago = DB::select("select c.id, p.nro_recibo, p.fecha_recibo, pe.dni,pe.id as idcliente, CONCAT(pe.paterno,' ',pe.materno,' ',pe.nombres) as clientes,p.nro_cuota as cuopago,p.comentario,
                empresa, sum(monto)as mon_pag,sum(cap_amor) as cappago,sum(interes)as intpago,p.id as id_pago,
                sum(seg_des)as segpago,sum(com_des) as compago,p.fecha_pago as ultimopago,
                (select  fecha_pago from pago where idcredito = c.id 
                order by fecha_pago desc LIMIT 1) as ultimopago1 ,idcomprobante, comprobante, c.numero as ncredito
                from pago as p 
                inner join credito as c on p.idcredito=c.id 
                inner join persona as pe on c.idpersona=pe.id                 
                GROUP BY p.id 
                ORDER BY paterno");
            }else{
            $pago = DB::select("select c.id, pe.dni,pe.id as idcliente, CONCAT(pe.paterno,' ',pe.materno,' ',pe.nombres) as clientes,p.nro_cuota as cuopago,p.comentario,
            empresa, sum(monto)as mon_pag,sum(cap_amor) as cappago,sum(interes)as intpago,p.id as id_pago,
            sum(seg_des)as segpago,sum(com_des) as compago,p.fecha_pago as ultimopago,
            (select  fecha_pago from pago where idcredito = c.id 
            order by fecha_pago desc LIMIT 1) as ultimopago1 ,idcomprobante, comprobante, c.numero as ncredito
            from pago as p 
            inner join credito as c on p.idcredito=c.id 
            inner join persona as pe on c.idpersona=pe.id 
            where year(fecha_pago)=$anio and month(fecha_pago)=$request->mes
            GROUP BY p.id 
            ORDER BY paterno");
            }
            //DB::table('pago as pa')->join('credito as c','pa.idcredito','=','c.id')->join('persona as  p','c.idpersona','=','p.id')->select(DB::raw('pe.dni, CONCAT(p.paterno," ",p.materno," ",p.nombres empresa,count(pe.id) as cuopago, sum(monto)as mon_pag,sum(cap_amor) as cappago,sum(interes)as intpago,sum(seg_des)as segpago,sum(com_des) as compago) as clientes '))->whereraw('year(fecha_pago)=2021')->GROUPBY pe.id ORDER BY paterno->get();
           // DB::select("select pe.id, pe.dni, nombres , paterno, materno, empresa,count(pe.id) as cuopago, sum(monto)as mon_pag,sum(cap_amor) as cappago,sum(interes)as intpago,sum(seg_des)as segpago,sum(com_des) as compago from pago as p inner join credito as c on p.idcredito=c.id inner join persona as pe on c.idpersona=pe.id where year(fecha_pago) = $anio  GROUP BY pe.id ORDER BY paterno");
        }

       // $pago = Pago::with('persona')->get();
       //DD($user);
        return $pago;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
     //DD($request);

       // DD($request->get('cuotanro'));

        try {
            DB::beginTransaction();
            $formatted_dt1=Carbon::parse($request->fecha_pago);
            $formatted_dt2=Carbon::parse($request->fecha_ven);
            $dias=$formatted_dt1->diffInDays($formatted_dt2);

            $pago = new Pago;
            $pago->idcliente=$request->get('idcliente');
            $pago->dni=$request->get('dni');
            $cliente = DB::table('persona')->select(db::raw('concat(paterno," ",materno," ",nombres) as nombre'))->where('dni',$request->get('dni'))->first();
            $pago->nom_cliente =  $cliente->nombre;
            $pago->fecha_pago=$request->get('fecha_pago');
            $pago->nro_cuota=$request->get('cuotanro');
            $pago->idcredito=$request->get('idcredito');
            $pago->monto=$request->get('monto');
            $pago->monto_desc=$request->get('monto_desc');
            $pago->monto_efectivo=$request->get('monto_efectivo');
            $pago->fec_pago_efec=$request->get('fec_pago_efec');
            
            $pago->com_pago=$request->get('com_pago');
            $pago->dias_pos=$dias;
            if ( $request->get('adelanto')=='S'){
                $pago->cap_amor= ($request->get('cap_amor')*$request->get('monto'))/$request->get('cuotames');
                $pago->interes= ($request->get('interes')*$request->get('monto'))/$request->get('cuotames');
                $pago->com_des= ($request->get('com_des')*$request->get('monto'))/$request->get('cuotames');
                $pago->seg_des= ($request->get('seg_des')*$request->get('monto'))/$request->get('cuotames');
                $pago->adelanto =$request->get('saldo');
                $pago->saldo = 0;
            }else {

                $pago->cap_amor=$request->get('cap_amor');
                $pago->interes=$request->get('interes');
                $pago->com_des=$request->get('com_des');
                $pago->seg_des=$request->get('seg_des');
                $pago->saldo=$request->get('saldo');     
            }
            
            $pago->comentario=$request->get('comentario');
            $pago->nro_recibo=$request->get('nro_recibo');
            $pago->fecha_recibo=$request->get('fecha_recibo');
            $pago->iduser=auth('sanctum')->user()->id;
            $pago->save();

            $detalle =  Detacronograma::where('id',$request->idcrono)->first();
            //DB::table('detallecrono')->where('idcredito', 1)->update(['votes' => 1]);
            //DD($detalle,$detalle->cuota , $request->get('monto'));
            $detalle->diasnopago = $dias;
            if ( $request->get('adelanto')=='N'){
                $detalle->situacion = 'C';
                $detalle->estado = 1;
                $detalle->mon_pag = $request->get('monto')+$detalle->mon_pag;
                $detalle->sal_pen = $detalle->cuota-$detalle->mon_pag;
                $detalle->saldo_ade = 0;
                $detalle->adelanto = 0;
               
                
            }elseif($detalle->cuota >= $request->get('monto')){
                $detalle->adelanto = 1;
                $detalle->estado = 0;
                $detalle->situacion = 'P';
                //$detalle->estado = 1;
                $detalle->mon_pag = $request->get('monto');
                $detalle->saldo_ade = $request->get('saldo');;
            }
            $detalle->save();
            $ultima = DB::table('cronograma')->where('idcredito',$request->idcredito)->first();
            if ($ultima->num_cuotas ==  $request->get('cuotanro')) {
                DB::table('credito')->where('id', $request->get('idcredito'))->update(['estado_cred' => 'C']);
            }
            db::commit();
            return $pago;
        } catch (\Throwable $th) {
            DB::rollback();
        }
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
        // DD($id,$request);
        $p = Pago::where('id',$id)->update(['monto'=>$request->mon_pag, 'fecha_pago'=>$request->ultimopago, 'comentario'=>$request->comentario]);
        return $p;

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

    public function print( Request $request)
    {
        // $nombreImpresora = "PORTPROMPT";
        // $connector = new NetworkPrintConnector("192.168.1.249", 9100);
        $connector = new WindowsPrintConnector('TM20II');
        $impresora = new Printer($connector);

        // PERSONALIZACION
        $impresora->text("CASA DE PRESTAMOS HUANCAYO\n");
        $impresora->text("\n"."Direccion: Jr. Junín 495" . "\n");
        $impresora->text("Tel: 957421025" . "\n");
        #La fecha también
        date_default_timezone_set("America/Mexico_City");
        // $impresora->text(date("Y-m-d H:i:s") . "\n");
        $impresora->text(date("d-m-Y H:i:s") . "\n");
        $impresora->text("-----------------------------" . "\n");
        $impresora->setJustification(Printer::JUSTIFY_LEFT);

        $impresora->text("CLIENTE"."\n");
        $impresora->text($request->ncliente . "\n");

        $impresora->text("TOTAL DE CUOTA"."\n");
        $impresora->text($request->tcuota ."\n");

        $impresora->text("TOTAL PAGADO"."\n");
        $impresora->text($request->tpagado ."\n");


        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->text("RECIBO ESTE VOUCHER EN CONFORMIDAD CON OPERACIÓN");

        /*Alimentamos el papel 3 veces*/
        $impresora->feed(3);

        /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
        */
        $impresora->cut();

        /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
        */
        $impresora->pulse();

        /*
            Para imprimir realmente, tenemos que "cerrar"
            la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
        */
        $impresora->close();
        return response('OK', 200)
                  ->header('Content-Type', 'text/JSON');
    }

    public function payfordni( Request $request){
        $pago = new Pago;
        $persona= Persona::where('dni',$request->dni)->get()->first();

        $pago['idcliente'] = $persona->id;
        $pago['iduser'] = $request->iduser;
        $pago['idempresa'] = $request->idempresa;
        $pago['monto'] = $request->monto;

        // DD($pago);
        $pago->save();
        // return $cuota;
        return $pago;
    }

    public function printronald( Request $request)
    {
        // $nombreImpresora = "PORTPROMPT";
        //$use   = system("net use lpt1: \\192.168.1.45\epson",$result);
        //$print = system(" print nube.txt ");
     // $connector = new WindowsPrintConnector("FX-890II");
       //  WindowsPrintConnector("LPT1")
      $connector = new FilePrintConnector("USB002");
        //smb://computer/printer
       //$connector = new WindowsPrintConnector("smb://192.168.1.64/epson");
        //system('net use "\\192.168.1.9\epson" /user:workgroup\INFBER-PC INFORMATICA2019');
       //$connector = new WindowsPrintConnector("smb://INFBER:INFORMATICA2019@INFBER-PC/workgroup/epson");
       //$connector = new WindowsPrintConnector("smb://INFBER:INFORMATICA2019@INFBER-PC/epson");
       // $connector = new WindowsPrintConnector("epson");
        $impresora = new Printer($connector);

        // PERSONALIZACION
        $impresora->setPrintLeftMargin(8);
        $impresora->setPrintWidth(8);
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->setTextSize(2,1);
        $impresora->text("GRUPO EMPRESARIAL WORKING FAMILY S.A.C.\n");
        $impresora->setJustification(Printer::JUSTIFY_RIGHT);
        $impresora->text("01/09/2021\n\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->setTextSize(4,8);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->text("IMPORTE QUESE GUIRA PARA EFECTUAR EL PAGO DE LA O/C NRO 534,532 POR CONCEPTO DE CARNES EN GENERAL PARA LA ALIMENTACION DE RESIDENTES DEL CAR NIÑOS, NIÑAS,ADULTO MAYOR, MADRES ADOLECENTES Y COMER SBH, CORRESPONDIENTE DEL 29/05 AL 0416/2021, SEGUN CONTRATO N| 10-20210-2021-SBH,S/PN 22-NUT,ACTADE RECEPCION DEL NUTRICIONISTA DE LA SBH Y FACTURA NROE001-58
            DEPOSITODETRACCION S/.329.00 CONSTANCIA N12452653, 2157855454
            VIGENCIA DE CONTRATO DEL 08/04/2021\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->setTextSize(1,1);
        $impresora->text("MINDES                    IF 2021              PROVEEDOR              7096.20\n");
        $impresora->text("                                               DETRACCION             7096.20\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("95       82365.22         97                  86255.20\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("95       82365.22         97                  86255.20\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora->text("\n");


        #La fecha también
        // $impresora->text(date("Y-m-d H:i:s") . "\n");
       // $impresora->setJustification(Printer::JUSTIFY_LEFT);

        //$impresora->setJustification(Printer::JUSTIFY_CENTER);
        // $impresora->text("RECIBO ESTE VOUCHER EN CONFORMIDAD CON OPERACIÓN");


        // $impresora->text("RECIBO ESTE VOUCHER EN CONFORMIDAD CON OPERACIÓN");
        // $impresora->text("RECIBO ESTE VOUCHER EN CONFORMIDAD CON OPERACIÓN");
        // $impresora->text("RECIBO ESTE VOUCHER EN CONFORMIDAD CON OPERACIÓN");

        /*Alimentamos el papel 3 veces*/
       // $impresora->feed(3);

        /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
        */
        //$impresora->cut();

        /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
        */
       // $impresora->pulse();

        /*
            Para imprimir realmente, tenemos que "cerrar"
            la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
        */
        $impresora->close();
        return response('OK', 200)
                  ->header('Content-Type', 'text/JSON');
    }
    public function buscarenplani(Request $request){

        //DD(intval($request->ano));
        $periodo = DB::connection('mysql2')->table('periodo')->where('mes',ucfirst($request->feca))->where('ano',intval($request->ano))->first();
        //DD($periodo);

        $pagos = DB::connection('mysql2')->table('trabajador as t')->join('detplanilla as d','t.idtrabajador','=','d.idtrabajador')->select('d.idtrabajador','t.dni','apenom','d.cod_inte','d.casa_sbh','d.desc_l06')->where('idperiodo',$periodo->idperiodo)->where(function ($query) { $query->where('d.desc_l06','>',0)->orWhere('d.casa_sbh','>',0);})->get();

        return $pagos;
    }
    public function mespagosbh(Request $request){
        $periodo = DB::connection('mysql2')->table('periodo')->where('mes',ucwords($request->feca))->where('ano',$request->ano)->first();

        $pagos = DB::connection('mysql2')->table('detplanilla')->join('detplanilla as d','t.idtrabajador','=','d.idtrabajador')->select('d.idtrabajador','t.dni','apenom','d.cod_inte','d.casa_sbh','d.desc_l06')->where('idperiodo',$periodo->idperiodo)->where(function ($query) { $query->where('d.desc_l06','>',0)->orWhere('d.casa_sbh','>',0);})->get();

        return $pagos;
    }


    public function storeMasivo(Request $request){
        // DD($request);

        try {
            DB::beginTransaction();
            foreach ($request->detalle as $i) {
                $pago = new Pago();
                // $pago->fecha_pago = $request->fecha_pago;
                $pago->fecha_pago = $i['fecha_pago'];
                $pago->dni = $i['dni'];
                $pago->idcliente = $i['idcliente'];
                $pago->nom_cliente = $i['cliente'];
                $pago->idcredito = $i['idcredito'];
                $pago->nro_cuota = $i['numero'];
                $pago->monto = $i['monto'];
                $pago->cap_amor = $i['cap_amor'];
                $pago->interes = $i['interes'];
                $pago->seg_des = $i['seg_des'];
                $pago->com_des = $i['com_des'];
                $pago->saldo = floatval($i['pendiente']) - floatval($i['monto']);
                // $pago->mora = $i[''];
                // $pago->estado = $i[''];
                // $pago->dias_pos = $i[''];
                $pago->com_pago = 0;
                $pago->iduser = auth('sanctum')->user()->id;
                $pago->monto_desc = $i['montopagar'];
                $pago->idcuota = $i['idcuota'];
                $pago->monto_efectivo = $i['montopagarefec'];
                $pago->save();
    
    
                // $detalle =  Detacronograma::where('id',$i['idcuota'])->first();
                // if ($detalle->sal_pen == null) {
                //     $detalle->pendiente = $detalle->cuota;
                // }else {
                //     $detalle->pendiente = $detalle->sal_pen;
                // }
                // //DB::table('detallecrono')->where('idcredito', 1)->update(['votes' => 1]);
        
                // // $detalle->diasnopago = $dias;
                // if (floatval($detalle->pendiente) == floatval($i['monto'])){
                //     Detacronograma::where('id',$i['idcuota'])->update([
                //         'situacion' => 'C',
                //         'estado' => 1,
                //         'mon_pag' => $i['monto'],
                //         'sal_pen' => 0
                //     ]);
        
                // }elseif(floatval($detalle->pendiente) > floatval($i['monto'])){

                //     Detacronograma::where('id',$i['idcuota'])->update([
                //         'situacion' => 'P',
                //         'estado' => '0',
                //         'mon_pag' => $i['monto'],
                //         'sal_pen' => floatval($detalle->pendiente) - floatval($i['monto'])
                //     ]);
                    
                // }
    
                // $cronograma = DB::table('cronograma')->where('idcredito', $i['idcredito'])->first();
                // if ($cronograma->num_cuotas ==  $i['numero']) {
                //     DB::table('credito')->where('id', $i['idcredito'])->update(['estado_cred' => 'C']);
                // };
            }
            
            // DD('SATISFACTORIO');
            DB::commit();
            return 'PAGOS CREADOS';
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
        }

        
    }

    public function validarPago(Request $request){

        if ($request->aceptar == true) {
            $detalle =  Detacronograma::where('id', $request->idcuota)->first();
            if ($detalle->sal_pen == null) {
                $detalle->pendiente = $detalle->cuota;
            }else {
                $detalle->pendiente = $detalle->sal_pen;
            }
            //DB::table('detallecrono')->where('idcredito', 1)->update(['votes' => 1]);
    
            // $detalle->diasnopago = $dias;
            if (floatval($detalle->pendiente) == floatval($request->monto)){
                Detacronograma::where('id',$request->idcuota)->update([
                    'situacion' => 'C',
                    'estado' => 1,
                    'mon_pag' => $request->monto,
                    'sal_pen' => 0
                ]);
    
            }elseif(floatval($detalle->pendiente) > floatval($request->monto)){

                Detacronograma::where('id',$request->idcuota)->update([
                    'situacion' => 'P',
                    'estado' => '0',
                    'mon_pag' => $request->monto,
                    'sal_pen' => floatval($detalle->pendiente) - floatval($request->monto)
                ]);
                
            }

            $cronograma = DB::table('cronograma')->where('idcredito', $i['idcredito'])->first();
            if ($cronograma->num_cuotas ==  $request->nro_cuota) {
                DB::table('credito')->where('id', $request->idcredito)->update(['estado_cred' => 'C']);
            };

            $up = Pago::where('id', $request->id)->update(['situacion'=>'A']);

            return 'ACEPTADO';
            // DD('ACEPTADO');
        } else {
            // DD('RECHAZADO');
            $up = Pago::where('id', $request->id)->update(['situacion'=>'C']);
            return 'RECHAZADO';
        }
        // DD($request);
    }
    public function verpagos(Request $request){

      //  DD($request);
        $pago = DB::select("select de.* FROM cronograma as c  inner join detallecrono as de on c.id=de.idcrono WHERE c.idcredito = $request->id");

        $data = new collection($pago);
       // Pago::Where('idcredito',$request->id)->get();
        $pagado = $data->where('situacion','C')->sum('cuota');
        $pendiente = $data->where('situacion','P')->sum('cuota');
        $cuotacan = $data->where('situacion','C')->count();
        $cuotapen = $data->where('situacion','P')->count();
        $amorti = $data->sum('amortizado');
        $total = $pagado+$pendiente;

        $amortizaciones='';
        return compact('pago','pagado','pendiente','cuotacan','cuotapen','amorti','total');
    }
    //REPORTES
    public function getReportMensual(Request $request){
        // DD($request);
        // $data =  DB::select("select ELT(MONTH(fecha_pago), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes, 
        // sum(monto) as monto,sum(interes) as interes,  sum(seg_des) as seguro, sum(com_des) as comision, MONTH(fecha_pago) as mese from pago  where year(fecha_pago) =$request->anio 
        // group by mes ,mese order by mese;");



        // $data2 = DB::Select("select ELT(MONTH(fecha_reg), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes,
        // sum(total_can) as monto, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio group by mes ,mese order by mese");
        //$res = New Collection();
        //DD($data2);
        $res = [];
        //$ani =db::Select("select year(created_at) as anio FROM credito where year(created_at) < $request->anio group by anio");
        $ani =db::Select("select year(fecha_pago) as anio FROM pago where year(fecha_pago) < $request->anio group by anio");
        
        for ($a=0;$a<count($ani);$a++){
            $res[$a]['mes']="SALDO - ".$ani[$a]->anio;
            $va=$ani[$a]->anio;
            $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $va");
               if( count($amor) < 1 ){
                $res[$a]['amorti'] = 0;
               }else{
                $res[$a]['amorti'] = $amor[0]->monto;
               }
            $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto ,sum(redondeo) as credondeo,sum(int_can) as intexcancela from credito_cancel  where year(fecha_reg) = $va;");
            // DD($cancelado);
           if( count($cancelado) < 1 ){
            $res[$a]['cancelado'] = 0;
            $res[$a]['intexcancela'] = 0;
            $res[$a]['credondeo'] = 0;
            }else{
        //     if ($ani[$a]->anio==2022) {
                 $res[$a]['cancelado'] = floatval($cancelado[0]->monto);
                 $res[$a]['intexcancela'] = floatval($cancelado[0]->intexcancela);
                 $res[$a]['credondeo'] = floatval($cancelado[0]->credondeo);
        //     }else {
        //         $res[$a]['cancelado'] = floatval($cancelado[0]->monto)+floatval($cancelado[0]->redo);
            }         
            
                                      
        //    }
           $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision, sum(saldo) as redondeo from pago  where year(fecha_pago) = $va;");
           if( count($pago) < 1 ){
               $res[$a]['cap_amor'] = 0;
               $res[$a]['monto'] = 0;
               $res[$a]['comision'] = 0;
               $res[$a]['interes'] = 0;
               $res[$a]['redondeo'] = 0;
               $res[$a]['seguro'] = 0;
               $res[$a]['total'] = 0;
               
               
            }else{
                $res[$a]['cap_amor'] = $pago[0]->cap_amor;
                $res[$a]['monto'] = $pago[0]->monto;
                $res[$a]['comision'] = $pago[0]->comision;
                $res[$a]['interes'] = $pago[0]->interes;
                $res[$a]['seguro'] = $pago[0]->seguro;
                $res[$a]['redondeo'] = $pago[0]->redondeo;
            }
            $res[$a]['total'] = $res[$a]['monto']+ $res[$a]['cancelado']+$res[$a]['intexcancela'] ;

        }

       // DD($res);


        $i=count($ani)-1;


        for ($m=0; $m <= $request->mes  ; $m++) {

            // if ($m == $request->mes) {
            //     return response()->json($res);
            //     break;
            // }

            // if ($m === 0) {
            //     // DD($m);
            //     $res[$m]['mes']="SALDOS ANTERIORES";

            //     $cancelado = DB::Select("select sum(total_can) as monto from credito_cancel  where year(fecha_reg) < $request->anio;");
            //     // DD($cancelado);
            //    if( count($cancelado) < 1 ){
            //     $res[$m]['cancelado'] = 0.00;

            //    }else{
            //     $res[$m]['cancelado'] = $cancelado[0]->monto,2 
                
                                          
            //    }
            //    $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision from pago  where year(fecha_pago) < $request->anio;");
            //    if( count($pago) < 1 ){
            //        $res[$m]['cap_amor'] = 0;
            //        $res[$m]['monto'] = 0;
            //        $res[$m]['comision'] = 0;
            //        $res[$m]['interes'] = 0;
            //        $res[$m]['seguro'] = 0;
            //        $res[$m]['total'] = 0;
                   
                   
            //     }else{
            //         $res[$m]['cap_amor'] = $pago[0]->cap_amor;
            //         $res[$m]['monto'] = $pago[0]->monto;
            //         $res[$m]['comision'] = $pago[0]->comision;
            //         $res[$m]['interes'] = $pago[0]->interes;
            //         $res[$m]['seguro'] = $pago[0]->seguro;
            //         $res[$m]['total'] = $pago[0]->monto + $cancelado[0]->monto;
            //     }
            //     //    DD($res);
            // }


            if($m ==  1){
               $res[$m+$i]['mes']="Enero";
               $res[$m+$i]['nmes']=1;
               $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $request->anio  and  MONTH(fecha_ven) = 1 group by mese");
               if( count($amor) < 1 ){
                $res[$m+$i]['amorti'] = 0;
               }else{
                $res[$m+$i]['amorti'] = $amor[0]->monto;
               }
              

               $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto,sum(redondeo) as credondeo, sum(int_can) as intexcancela, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 1 group by mese;");
                // DD($cancelado);
               if( count($cancelado) < 1 ){
                $res[$m+$i]['cancelado'] = 0;
                $res[$m+$i]['credondeo'] = 0;
                $res[$m+$i]['intexcancela'] = 0;

               }else{
                $res[$m+$i]['cancelado'] = $cancelado[0]->monto;    
                $res[$m+$i]['credondeo'] = $cancelado[0]->credondeo;  
                $res[$m+$i]['intexcancela'] = $cancelado[0]->intexcancela;          
                                          
               }
               $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision, MONTH(fecha_pago) as mese, sum(saldo) as redondeo from pago  where year(fecha_pago) =$request->anio and MONTH(fecha_pago) = 1 group by mese;");
               if( count($pago) < 1 ){
                $res[$m+$i]['cap_amor'] = 0;
                $res[$m+$i]['monto'] = 0;
                $res[$m+$i]['comision'] = 0;
                $res[$m+$i]['interes'] = 0;
                $res[$m+$i]['seguro'] = 0;
                $res[$m+$i]['total'] = 0;
                $res[$m+$i]['redondeo'] = 0;

               }else{
                $res[$m+$i]['cap_amor'] = $pago[0]->cap_amor;
                $res[$m+$i]['monto'] = $pago[0]->monto;
                $res[$m+$i]['comision'] = $pago[0]->comision;
                $res[$m+$i]['interes'] = $pago[0]->interes;
                $res[$m+$i]['seguro'] = $pago[0]->seguro;
                $res[$m+$i]['redondeo'] = $pago[0]->redondeo;
               // $res[$m+$i]['total'] = $pago[0]->monto+$cancelado[0]->monto;
               }
             // DD( floatval(str_replace(",","",$res[$m+$i]['monto']))+floatval(str_replace(",","",$res[$m+$i]['cancelado'])),;
             $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado']+$res[$m+$i]['intexcancela'];
               
            }
            if($m == 2){
                $res[$m+$i]['mes']="Febrero";
                $res[$m+$i]['nmes']=2;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $request->anio  and  MONTH(fecha_ven) = 2 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto,sum(redondeo) as credondeo, sum(int_can) as intexcancela, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 2 group by mese");
                if( count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                    $res[$m+$i]['credondeo'] = 0;
                    $res[$m+$i]['intexcancela'] = 0;
    
                   }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;    
                    $res[$m+$i]['credondeo'] = $cancelado[0]->credondeo;  
                    $res[$m+$i]['intexcancela'] = $cancelado[0]->intexcancela;          
                                              
                   }
                $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision, MONTH(fecha_pago) as mese, sum(saldo) as redondeo from pago  where year(fecha_pago) =$request->anio and MONTH(fecha_pago) = 2 group by mese;");
                if(count($pago) < 1){
                 $res[$m+$i]['monto'] = 0;
                 $res[$m+$i]['comision'] = 0;
                 $res[$m+$i]['interes'] = 0;
                 $res[$m+$i]['seguro'] = 0;
                 $res[$m+$i]['cap_amor'] = 0;
                 $res[$m+$i]['redondeo'] = 0;
                }else{
                 $res[$m+$i]['cap_amor'] = $pago[0]->cap_amor;
                 $res[$m+$i]['monto'] = $pago[0]->monto;
                 $res[$m+$i]['comision'] = $pago[0]->comision;
                 $res[$m+$i]['interes'] = $pago[0]->interes;
                 $res[$m+$i]['seguro'] = $pago[0]->seguro;
                 $res[$m+$i]['redondeo'] = $pago[0]->redondeo;
                }
              // DD($res[$m+$i]['monto'],floatval($res[$m+$i]['cancelado']));
              $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado']+$res[$m+$i]['intexcancela'];
            }
            if($m== 3){
                $res[$m+$i]['mes']="Marzo";
                $res[$m+$i]['nmes']=3;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $request->anio  and  MONTH(fecha_ven) = 3 group by mese");
                if( count($amor) < 1 ){
                $res[$m+$i]['amorti'] = 0;
                }else{
                $res[$m+$i]['amorti'] = $amor[0]->monto; 
                }
                $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto,sum(redondeo) as credondeo, sum(int_can) as intexcancela, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 3 group by mese");
                if( count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                    $res[$m+$i]['credondeo'] = 0;
                    $res[$m+$i]['intexcancela'] = 0;
    
                   }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;    
                    $res[$m+$i]['credondeo'] = $cancelado[0]->credondeo;  
                    $res[$m+$i]['intexcancela'] = $cancelado[0]->intexcancela;          
                                              
                   }
                $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  
                sum(seg_des) as seguro, sum(com_des) as comision, MONTH(fecha_pago) as mese, sum(saldo) as redondeo from pago  where year(fecha_pago) =$request->anio and MONTH(fecha_pago) = 3 group by mese;");
                if(count($pago)<1) {
                    $res[$m+$i]['monto'] = 0;
                    $res[$m+$i]['comision'] = 0;
                    $res[$m+$i]['interes'] = 0;
                    $res[$m+$i]['seguro'] = 0;
                    $res[$m+$i]['cap_amor'] = 0;
                    $res[$m+$i]['redondeo'] = 0;

                }else{
                    $res[$m+$i]['cap_amor'] = $pago[0]->cap_amor;
                    $res[$m+$i]['monto'] = $pago[0]->monto;
                    $res[$m+$i]['comision'] = $pago[0]->comision;
                    $res[$m+$i]['interes'] = $pago[0]->interes;
                    $res[$m+$i]['seguro'] = $pago[0]->seguro;
                    $res[$m+$i]['redondeo'] = $pago[0]->redondeo;
                }
                $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado']+$res[$m+$i]['intexcancela'];
            }
            if($m  == 4){
                $res[$m+$i]['mes']="Abril";
                $res[$m+$i]['nmes']=4;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $request->anio  and  MONTH(fecha_ven) = 4 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                    $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto,sum(redondeo) as credondeo, sum(int_can) as intexcancela, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 4 group by mese");
                if( count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                    $res[$m+$i]['credondeo'] = 0;
                    $res[$m+$i]['intexcancela'] = 0;
    
                   }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;    
                    $res[$m+$i]['credondeo'] = $cancelado[0]->credondeo;  
                    $res[$m+$i]['intexcancela'] = $cancelado[0]->intexcancela;          
                                              
                   }
                $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision, MONTH(fecha_pago) as mese , sum(saldo) as redondeo from pago  where year(fecha_pago) =$request->anio and MONTH(fecha_pago) = 4 group by mese;");
                if(count($pago)<1) {
                    $res[$m+$i]['monto'] = 0;
                    $res[$m+$i]['comision'] = 0;
                    $res[$m+$i]['interes'] = 0;
                    $res[$m+$i]['seguro'] = 0;
                    $res[$m+$i]['cap_amor'] = 0;
                    $res[$m+$i]['redondeo'] = 0;

                }else{
                    $res[$m+$i]['cap_amor'] = $pago[0]->cap_amor;
                    $res[$m+$i]['monto'] = $pago[0]->monto;
                    $res[$m+$i]['comision'] = $pago[0]->comision;
                    $res[$m+$i]['interes'] = $pago[0]->interes;
                    $res[$m+$i]['seguro'] = $pago[0]->seguro;
                    $res[$m+$i]['redondeo'] = $pago[0]->redondeo;
                }
                $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado']+$res[$m+$i]['intexcancela'];
            }
            if($m == 5){
                $res[$m+$i]['mes']="Mayo";
                $res[$m+$i]['nmes']=5;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $request->anio  and  MONTH(fecha_ven) = 5 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto,sum(redondeo) as credondeo, sum(int_can) as intexcancela, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 5 group by mese ");
                if( count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                    $res[$m+$i]['credondeo'] = 0;
                    $res[$m+$i]['intexcancela'] = 0;
    
                   }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;    
                    $res[$m+$i]['credondeo'] = $cancelado[0]->credondeo;  
                    $res[$m+$i]['intexcancela'] = $cancelado[0]->intexcancela;          
                                              
                   }
                $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision, 
                MONTH(fecha_pago) as mese, sum(saldo) as redondeo from pago  
                where year(fecha_pago) =$request->anio 
                and MONTH(fecha_pago) = 5 group by mese;");

                // DD($cancelado);
                if(count($pago)<1){
                    $res[$m+$i]['monto'] = 0;
                    $res[$m+$i]['comision'] = 0;
                    $res[$m+$i]['interes'] = 0;
                    $res[$m+$i]['seguro'] = 0;
                    $res[$m+$i]['cap_amor'] = 0;
                    $res[$m+$i]['redondeo'] = 0;

                }else{
                    $res[$m+$i]['cap_amor'] = $pago[0]->cap_amor;
                    $res[$m+$i]['monto'] = $pago[0]->monto;
                    $res[$m+$i]['comision'] = $pago[0]->comision;
                    $res[$m+$i]['interes'] = $pago[0]->interes;
                    $res[$m+$i]['seguro'] = $pago[0]->seguro;
                    $res[$m+$i]['redondeo'] = $pago[0]->redondeo;
                }
                $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado']+$res[$m+$i]['intexcancela'];
            }
            if($m == 6){
                $res[$m+$i]['mes']="Junio";
                $res[$m+$i]['nmes']=6;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $request->anio  and  MONTH(fecha_ven) = 6 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                    $res[$m+$i]['amorti'] = $amor[0]->monto;
                   }
                $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto,sum(redondeo) as credondeo, sum(int_can) as intexcancela, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 6 group by mese");
                if( count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                    $res[$m+$i]['credondeo'] = 0;
                    $res[$m+$i]['intexcancela'] = 0;
    
                   }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;    
                    $res[$m+$i]['credondeo'] = $cancelado[0]->credondeo;  
                    $res[$m+$i]['intexcancela'] = $cancelado[0]->intexcancela;          
                                              
                   }
                $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision, 
                MONTH(fecha_pago) as mese, sum(saldo) as redondeo from pago  where year(fecha_pago) =$request->anio and MONTH(fecha_pago) = 6 group by mese;");
                if(count($pago)<1) {
                    $res[$m+$i]['monto'] = 0;
                    $res[$m+$i]['comision'] = 0;
                    $res[$m+$i]['interes'] = 0;
                    $res[$m+$i]['seguro'] = 0;
                    $res[$m+$i]['cap_amor'] = 0;
                    $res[$m+$i]['redondeo'] = 0;

                }else{
                    $res[$m+$i]['cap_amor'] = $pago[0]->cap_amor;
                    $res[$m+$i]['monto'] = $pago[0]->monto;
                    $res[$m+$i]['comision'] = $pago[0]->comision;
                    $res[$m+$i]['interes'] = $pago[0]->interes;
                    $res[$m+$i]['seguro'] = $pago[0]->seguro;
                    $res[$m+$i]['redondeo'] = $pago[0]->redondeo;
                }
                $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado']+$res[$m+$i]['intexcancela'];
            }
            if($m == 7){
                $res[$m+$i]['mes']="Julio";
                $res[$m+$i]['nmes']=7;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $request->anio  and  MONTH(fecha_ven) = 7 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto,sum(redondeo) as credondeo, sum(int_can) as intexcancela, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 7 group by mese");
                // (count($cancelado) < 1);
                if( count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                    $res[$m+$i]['credondeo'] = 0;
                    $res[$m+$i]['intexcancela'] = 0;
    
                   }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;    
                    $res[$m+$i]['credondeo'] = $cancelado[0]->credondeo;  
                    $res[$m+$i]['intexcancela'] = $cancelado[0]->intexcancela;          
                                              
                   }
                $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision, MONTH(fecha_pago) as mese, sum(saldo) as redondeo from pago  where year(fecha_pago) =$request->anio and MONTH(fecha_pago) = 7 group by mese;");
                if(count($pago)<1) {
                    $res[$m+$i]['monto'] = 0;
                    $res[$m+$i]['comision'] = 0;
                    $res[$m+$i]['interes'] = 0;
                    $res[$m+$i]['seguro'] = 0;
                    $res[$m+$i]['cap_amor'] = 0;
                    $res[$m+$i]['redondeo'] = 0;

                }else{
                    $res[$m+$i]['cap_amor'] = $pago[0]->cap_amor;
                    $res[$m+$i]['monto'] = $pago[0]->monto;
                    $res[$m+$i]['comision'] = $pago[0]->comision;
                    $res[$m+$i]['interes'] = $pago[0]->interes;
                    $res[$m+$i]['seguro'] = $pago[0]->seguro;
                    $res[$m+$i]['redondeo'] = $pago[0]->redondeo;
                }
                $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado']+$res[$m+$i]['intexcancela'];
            }
            if($m == 8){
                $res[$m+$i]['mes']="Agosto";
                $res[$m+$i]['nmes']=8;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $request->anio  and  MONTH(fecha_ven) = 8 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto,sum(redondeo) as credondeo, sum(int_can) as intexcancela, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 8 group by mese");
                if( count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                    $res[$m+$i]['credondeo'] = 0;
                    $res[$m+$i]['intexcancela'] = 0;
    
                   }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;    
                    $res[$m+$i]['credondeo'] = $cancelado[0]->credondeo;  
                    $res[$m+$i]['intexcancela'] = floatval($cancelado[0]->intexcancela);          
                                              
                   }
                $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision, MONTH(fecha_pago) as mese, sum(saldo) as redondeo from pago  where year(fecha_pago) =$request->anio and MONTH(fecha_pago) = 8 group by mese;");
                if(count($pago)<1){
                    $res[$m+$i]['monto'] = 0;
                    $res[$m+$i]['comision'] = 0;
                    $res[$m+$i]['interes'] = 0;
                    $res[$m+$i]['seguro'] = 0;
                    $res[$m+$i]['cap_amor'] = 0;                
                    $res[$m+$i]['redondeo'] = 0;
                }else{
                    $res[$m+$i]['cap_amor'] = $pago[0]->cap_amor;
                    $res[$m+$i]['monto'] = $pago[0]->monto;
                    $res[$m+$i]['comision'] = $pago[0]->comision;
                    $res[$m+$i]['interes'] = $pago[0]->interes;
                    $res[$m+$i]['seguro'] = $pago[0]->seguro;
                    $res[$m+$i]['redondeo'] = $pago[0]->redondeo;
                }
                $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado']+$res[$m+$i]['intexcancela'];
            }
            if($m == 9){
                $res[$m+$i]['mes']="Setiembre";
                $res[$m+$i]['nmes']=9;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $request->anio  and  MONTH(fecha_ven) = 9 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto,sum(redondeo) as credondeo, sum(int_can) as intexcancela, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 9 group by mese");
                if( count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                    $res[$m+$i]['credondeo'] = 0;
                    $res[$m+$i]['intexcancela'] = 0;
    
                   }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;    
                    $res[$m+$i]['credondeo'] = $cancelado[0]->credondeo;  
                    $res[$m+$i]['intexcancela'] = $cancelado[0]->intexcancela;          
                                              
                   }
                $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision, MONTH(fecha_pago) as mese, sum(saldo) as redondeo from pago where year(fecha_pago) =$request->anio and MONTH(fecha_pago) = 9 group by mese;");
                // DD($pago);
                if(count($pago) < 1 ){
                    $res[$m+$i]['monto'] = 0;
                    $res[$m+$i]['comision'] = 0;
                    $res[$m+$i]['interes'] = 0;
                    $res[$m+$i]['seguro'] = 0;
                    $res[$m+$i]['cap_amor'] = 0;
                    $res[$m+$i]['total'] = 0;
                    $res[$m+$i]['redondeo'] = 0;
                }else{
                    $res[$m+$i]['cap_amor'] = $pago[0]->cap_amor;
                    $res[$m+$i]['monto'] = $pago[0]->monto;
                    $res[$m+$i]['comision'] = $pago[0]->comision;
                    $res[$m+$i]['interes'] = $pago[0]->interes;
                    $res[$m+$i]['seguro'] = $pago[0]->seguro;
                    $res[$m+$i]['redondeo'] = $pago[0]->redondeo;
                    // dd($pago, $cancelado);
                    // if (count($cancelado) < 1) {
                    //     $res[$m+$i]['total'] = $pago[0]->monto;
                    // }else {
                    //     $res[$m+$i]['total'] = $pago[0]->monto+$cancelado[0]->monto;
                    // }
                    
                }
                $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado']+$res[$m+$i]['intexcancela'];
            }
            if($m == 10){
                $res[$m+$i]['mes']="Octubre";
                $res[$m+$i]['nmes']=10;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $request->anio  and  MONTH(fecha_ven) = 10 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto,sum(redondeo) as credondeo, sum(int_can) as intexcancela, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 10 group by mese");
                if( count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                    $res[$m+$i]['credondeo'] = 0;
                    $res[$m+$i]['intexcancela'] = 0;
    
                   }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;    
                    $res[$m+$i]['credondeo'] = $cancelado[0]->credondeo;  
                    $res[$m+$i]['intexcancela'] = floatval($cancelado[0]->intexcancela);          
                                              
                   }
                $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision, MONTH(fecha_pago) as mese, sum(saldo) as redondeo from pago  where year(fecha_pago) =$request->anio and MONTH(fecha_pago) = 10 group by mese;");
                if(count($pago) < 1 ){
                    $res[$m+$i]['monto'] = 0;
                    $res[$m+$i]['comision'] = 0;
                    $res[$m+$i]['interes'] = 0;
                    $res[$m+$i]['seguro'] = 0;
                    $res[$m+$i]['cap_amor'] = 0;
                    $res[$m+$i]['total'] = 0;
                    $res[$m+$i]['redondeo'] = 0;

                }else{
                    $res[$m+$i]['cap_amor'] = $pago[0]->cap_amor;
                    $res[$m+$i]['monto'] = $pago[0]->monto;
                    $res[$m+$i]['comision'] = $pago[0]->comision;
                    $res[$m+$i]['interes'] = $pago[0]->interes;
                    $res[$m+$i]['seguro'] = $pago[0]->seguro;
                    $res[$m+$i]['redondeo'] = $pago[0]->redondeo;
                    // if (count($cancelado) < 1) {
                    //     $res[$m+$i]['total'] = $pago[0]->monto;
                    // }else {
                    //     $res[$m+$i]['total'] = $pago[0]->monto+$cancelado[0]->monto;
                    // }
                    // $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado'];
                }
                $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado']+$res[$m+$i]['intexcancela'];
            }
            if($m == 11){
                $res[$m+$i]['mes']="Noviembre";
                $res[$m+$i]['nmes']=11;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $request->anio  and  MONTH(fecha_ven) = 11 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto,sum(redondeo) as credondeo, sum(int_can) as intexcancela, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 11 group by mese");
                if( count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                    $res[$m+$i]['credondeo'] = 0;
                    $res[$m+$i]['intexcancela'] = 0;
    
                   }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;    
                    $res[$m+$i]['credondeo'] = $cancelado[0]->credondeo;  
                    $res[$m+$i]['intexcancela'] = $cancelado[0]->intexcancela;          
                                              
                   }
                $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision, MONTH(fecha_pago) as mese, sum(saldo) as redondeo from pago  where year(fecha_pago) =$request->anio and MONTH(fecha_pago) = 11 group by mese;");
                if(count($pago) < 1){
                    $res[$m+$i]['monto'] = 0;
                    $res[$m+$i]['comision'] = 0;
                    $res[$m+$i]['interes'] = 0;
                    $res[$m+$i]['seguro'] = 0;
                    $res[$m+$i]['cap_amor'] = 0;
                    $res[$m+$i]['total'] = 0;
                    $res[$m+$i]['redondeo'] = 0;

                }else{
                    $res[$m+$i]['cap_amor'] = $pago[0]->cap_amor;
                    $res[$m+$i]['monto'] = $pago[0]->monto;
                    $res[$m+$i]['comision'] = $pago[0]->comision;
                    $res[$m+$i]['interes'] = $pago[0]->interes;
                    $res[$m+$i]['seguro'] = $pago[0]->seguro;
                    $res[$m+$i]['redondeo'] = $pago[0]->redondeo;
                //  $res[$m+$i]['total'] = $res[$m+$i]['monto']+$res[$m+$i]['cancelado'];
                   // $res[$m+$i]['total'] = $pago[0]->monto+$cancelado[0]->monto;
                }
                $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado']+$res[$m+$i]['intexcancela'];
            }
            if($m == 12){
                $res[$m+$i]['mes']="Diciembre";
                $res[$m+$i]['nmes']=12;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_ven) as mese from detallecrono  where cuotanro = 0 and year(fecha_ven) = $request->anio  and  MONTH(fecha_ven) = 12 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select (sum(total_can)-sum(int_can)) as monto,sum(redondeo) as credondeo, sum(int_can) as intexcancela, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 12 group by mese");
                if( count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                    $res[$m+$i]['credondeo'] = 0;
                    $res[$m+$i]['intexcancela'] = 0;
    
                   }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;    
                    $res[$m+$i]['credondeo'] = $cancelado[0]->credondeo;  
                    $res[$m+$i]['intexcancela'] = $cancelado[0]->intexcancela;          
                                              
                   }
                $pago = DB::select("select sum(monto) as monto,sum(interes) as interes,sum(cap_amor)as cap_amor,  sum(seg_des) as seguro, sum(com_des) as comision, MONTH(fecha_pago) as mese, sum(saldo) as redondeo from pago  where year(fecha_pago) = $request->anio and MONTH(fecha_pago) = 12 group by mese;");
                if(count($pago) < 1 ){
                    $res[$m+$i]['monto'] = 0;
                    $res[$m+$i]['comision'] = 0;
                    $res[$m+$i]['interes'] = 0;
                    $res[$m+$i]['seguro'] = 0;
                    $res[$m+$i]['cap_amor'] = 0;
                    $res[$m+$i]['total'] = 0;
                    $res[$m+$i]['redondeo'] = 0;

                }else{
                    $res[$m+$i]['cap_amor'] = $pago[0]->cap_amor;
                    $res[$m+$i]['monto'] = $pago[0]->monto;
                    $res[$m+$i]['comision'] = $pago[0]->comision;
                    $res[$m+$i]['interes'] = $pago[0]->interes;
                    $res[$m+$i]['seguro'] = $pago[0]->seguro;
                    $res[$m+$i]['redondeo'] = $pago[0]->redondeo;
                //  $res[$m+$i]['total'] = $res[$m+$i]['monto']+$res[$m+$i]['cancelado'];
                // $res[$m+$i]['total'] = $pago[0]->monto+$res[$m+$i]['cancelado'];
                // if (count($cancelado) < 1) {
                //     $res[$m+$i]['total'] = $pago[0]->monto;
                // } else {
                //     $res[$m+$i]['total'] = $pago[0]->monto+$cancelado[0]->monto;
                // }
                }
                $res[$m+$i]['total'] =  $res[$m+$i]['monto']+$res[$m+$i]['cancelado']+$res[$m+$i]['intexcancela'];
            }
            
        }


       // DD($res);

        // foreach ($data as $i) {



        //     $i->monto_value = $i->monto;
        //     $i->monto = number_format($i->monto,2);
        //     $i->comision = number_format($i->comision,2);
        //     $i->interes = number_format($i->interes,2);
        //     $i->seguro = number_format($i->seguro,2);       // }

        return response()->json($res);
    }

    public function getReportDetallado(Request $request){
        // DD($request->anio , $request->mes);
        $data = DB::select("select *, false as activo from pago   where year(fecha_pago)=$request->anio AND MONTH(fecha_pago)=$request->mes  ORDER BY  nom_cliente ASC ,nro_cuota ASC");

        foreach ($data as $i) {
            $i->monto_value = $i->monto;
            $i->monto = number_format($i->monto,2);
        }

        return $data;
    }

    public function getReportDeudaRestante (Request $request){
        // DD($request->anio , $request->mes);
        // select ((c.num_cuotas - count(pago.monto)) * d.cuota) 
        //     from pago inner join credito_cancel on pago.idcredito = credito_cancel.idcredito 
        //     where credito_cancel.fecha_reg <= '$request->fecha' 
        //     and pago.idcredito = cre.id and fecha_pago <= '$request->fecha' 
        // (select ((c.num_cuotas - count(monto))) from pago where idcredito = cre.id and fecha_pago <= '$request->fecha' ) as cant_cuotas_res,

        // select (d.cuota * (select (c.num_cuotas -(select count(id) from pago where pago.idcredito = cre.id  and pago.fecha_pago <= '$request->fecha') ) as s from cronograma c where c.idcredito = cre.id)) as s
        //     from cronograma c
        //     inner join detallecrono d on c.id = d.idcrono 
        //     where c.idcredito = cre.id
        //     and d.fecha_ven >= '$request->fecha'
        //     order by d.fecha_ven desc
        //     limit 1  (select count(monto) from pago where idcredito = cre.id and fecha_pago <= '$request->fecha' ) as n_cuotas_pagado,
        //(select sum(if(d.situacion = 'C', 1,0))) as n_cuotas_pagado,
        //dsdfs
        // select concat( p.paterno, ' ', p.materno,' ', p.nombres) as nombres , cre.id,count(d.id) as nreal,
        // cre.numero as n_credito, c.mon_financiado  as monto_financiado, c.num_cuotas as total_cuotas, 
        // sum(d.cuota) as total_deuda,
        // (select if(SUM(d.interes+seg_des+com_des)  is null ,0 ,SUM(d.interes+seg_des+com_des))
        //     from cronograma c
        //     inner join detallecrono d on c.id = d.idcrono 
        //     where c.idcredito =cre.id
		// 				and d.situacion ='A'
        //                 and d.fecha_ven >= '$request->fecha'
        //     order by d.fecha_ven desc           
        // ) as nocobro,
        // (select sum(monto) from pago where nro_cuota = 0 and idcredito = cre.id and fecha_pago <= '$request->fecha' ) as amortiza,
        // (select  sum(saldo) from pago where idcredito = cre.id and fecha_pago <= '$request->fecha' ) + (select  sum(redondeo) from credito_cancel where idcredito = cre.id and fecha_reg <= '$request->fecha' ) as redondeo,
        // (select sum(if(d.adelanto = 1, d.mon_pag,0))) as adelanto,
        // (select if(sum(monto) is null, 0, sum(monto)) from pago where idcredito = cre.id and fecha_pago <= '$request->fecha' ) as total_pagado,
        // (select if(sum(monto) is null, 0, sum(monto)) from pago where idcredito = cre.id and fecha_pago <= '$request->fecha' ) as por_cobrar,
        // (select count(DISTINCT nro_cuota) from pago  where idcredito=cre.id and fecha_pago <= '$request->fecha' and nro_cuota > 0 and adelanto is NULL) as n_cuotas_pagado,               
        // (select if(sum(interes) is null, 0, sum(interes)) from pago where idcredito = cre.id and fecha_pago <= '$request->fecha' ) as interes_pagado,
        // d.cuota as cuota,
        //  ((c.num_cuotas - (select count(id) from pago where pago.idcredito = cre.id and pago.fecha_pago <= '$request->fechaan') )*d.cuota) as monto_res,
        //  (SELECT count(d.id) from  cronograma c inner join detallecrono d on c.id = d.idcrono where c.idcredito =cre.id and cuotanro=0 and fecha_ven <='$request->fecha') as amor,
        // (select  (c.num_cuotas - (select count(id) from pago where pago.idcredito = cre.id and pago.fecha_pago <= '$request->fecha') ) as er
        //     from cronograma c
        //     where c.idcredito = cre.id
        // ) as cant_cuotas_res,
		// (select if(int_can is null, 0, int_can ) from credito_cancel  where idcredito = cre.id and fecha_reg <= '$request->fecha') as interes_cancelado,
        // (select if(sum(total_can) is null, 0, sum(total_can) ) from credito_cancel  where idcredito = cre.id and fecha_reg <= '$request->fecha') as monto_cancelado
        // from detallecrono d
        // inner join cronograma c on d.idcrono = c.id
        // inner join credito cre on c.idcredito = cre.id
        // inner join persona p on cre.idpersona = p.id
        // where c.f_desembolso <= '$request->fecha'
        // and cre.estado =  1
        // group by c.id
        // order by p.paterno
        // ");((select count(DISTINCT d.id) from pago as p INNER JOIN cronograma as c on p.idcredito=c.idcredito INNER JOIN detallecrono as d on c.id=d.idcrono where p.idcredito=cre.id and fecha_pago <= '$request->fecha' and d.situacion ='C' and nro_cuota > 0 and d.cuotanro>0 and p.adelanto is NULL) *  d.cuota ) as total_pagado,

        $data = DB::select("
        select concat( p.paterno, ' ', p.materno,' ', p.nombres) as nombres , cre.id,count(d.id) as nreal,
        cre.numero as n_credito, c.mon_financiado  as monto_financiado, c.num_cuotas as total_cuotas, 
        sum(d.cuota) as total_deuda,
        (select if(SUM(d.interes+seg_des+com_des)  is null ,0 ,SUM(d.interes+seg_des+com_des))
            from cronograma c
            inner join detallecrono d on c.id = d.idcrono 
            where c.idcredito =cre.id
						and d.situacion ='A'
                        and d.fecha_ven >= '$request->fecha'
            order by d.fecha_ven desc           
        ) as nocobro,
        (select sum(monto) from pago where nro_cuota = 0 and idcredito = cre.id and fecha_pago <= '$request->fecha' ) as amortiza,
        (select  sum(saldo) from pago where idcredito = cre.id and fecha_pago <= '$request->fecha' ) + (select  sum(redondeo) from credito_cancel where idcredito = cre.id and fecha_reg <= '$request->fecha' ) as redondeo,
        (select sum(if(d.adelanto = 1, d.mon_pag,0))) as adelanto,
        (select if(sum(monto) is null, 0, sum(monto)) from pago where idcredito = cre.id and fecha_pago <= '$request->fecha' ) as total_pagado,
        (select if(sum(monto) is null, 0, sum(monto)) from pago where idcredito = cre.id and fecha_pago <= '$request->fecha' ) as por_cobrar,
        (select count(DISTINCT p.id) from pago as p INNER JOIN cronograma as c on p.idcredito=c.idcredito INNER JOIN detallecrono as d on c.id=d.idcrono where p.idcredito=cre.id and fecha_pago <= '$request->fecha'and d.situacion ='C' and nro_cuota > 0 and d.cuotanro>0 and p.adelanto is NULL) as n_cuotas_pagado,              
        (select if(sum(interes) is null, 0, sum(interes)) from pago where idcredito = cre.id and fecha_pago <= '$request->fecha' ) as interes_pagado,
        d.cuota as cuota,
         ((c.num_cuotas - (select count(DISTINCT d.id) from pago as p INNER JOIN cronograma as c on p.idcredito=c.idcredito INNER JOIN detallecrono as d on c.id=d.idcrono where p.idcredito=cre.id and fecha_pago <= '$request->fecha'and d.situacion='C' and nro_cuota > 0 and d.cuotanro>0 and p.adelanto is NULL) )*d.cuota) as monto_res,
         (SELECT count(d.id) from  cronograma c inner join detallecrono d on c.id = d.idcrono where c.idcredito =cre.id and cuotanro=0 and fecha_ven <='$request->fecha') as amor,
        (select  (c.num_cuotas - (select count(id) from pago where pago.idcredito = cre.id and pago.fecha_pago <= '$request->fecha') ) as er
            from cronograma c
            where c.idcredito = cre.id
        ) as cant_cuotas_res,
		(select if(int_can is null, 0, int_can ) from credito_cancel  where idcredito = cre.id and fecha_reg <= '$request->fecha') as interes_cancelado,
        (select if(sum(total_can) is null, 0, sum(total_can) ) from credito_cancel  where idcredito = cre.id and fecha_reg <= '$request->fecha') as monto_cancelado
        from detallecrono d
        inner join cronograma c on d.idcrono = c.id
        inner join credito cre on c.idcredito = cre.id
        inner join persona p on cre.idpersona = p.id
        where c.f_desembolso <= '$request->fecha'
        and cre.estado =  1
        group by c.id
        order by p.paterno
        ");
    
        foreach ($data as $i) {
            $i->redo=$i->redondeo;
            if ($i->monto_cancelado === "0.00"  ){
                if ($i->n_cuotas_pagado == $i->total_cuotas) {
                        $i->monto_res=0;
                        $i->sal_res=0;
                        $i->int_res=0;
                        $i->seg_res=0;
                        $i->com_res=0;
                }else{
                        if ($i->amor == 0) {
                            $nue = $i->total_cuotas-$i->n_cuotas_pagado;
                            if ($nue < 0) {
                                $nue=1;
                            }
                            $dato= DB::select("select de.* from cronograma  as c inner JOIN detallecrono as de on c.id=de.idcrono WHERE idcredito =$i->id order by de.id desc limit $nue");
                            // $sumveri=DB::select("select SUM(COALESCE(d.cuota, 0)) AS nro,t_interes from  cronograma c inner join detallecrono d on c.id = d.idcrono where c.idcredito = $i->id and d.situacion = 'P' 
                            // and fecha_ven <= '$request->fecha'");
                            $dato1 = new collection($dato);
                            $i->sal_res =  $dato1->sum('cuota')-($dato1->sum('interes')+$dato1->sum('seg_des')+$dato1->sum('com_des'));
                            $i->cuota= $dato1[0]->cuota;
                            $i->int_res=$dato1->sum('interes');
                            $i->seg_res=$dato1->sum('seg_des');
                            $i->com_res=$dato1->sum('com_des');
                            $i->monto_res=$dato1->sum('cuota');
                            if (empty($sumveri)) {
                            } else {
                                // if ($sumveri[0]->nro === null && $sumveri[0]->t_interes === null) {
                                    
                                // } else {
                                //     $i->monto_res=$dato1->sum('cuota') + $sumveri[0]->nro;
                                // }
                            }
                        }else{
                            $veri=DB::select("select count(d.id) as nro,t_interes from  cronograma c inner join detallecrono d on c.id = d.idcrono where c.idcredito = $i->id and cuotanro = 0 and fecha_ven >= '$request->fecha'");
                                if($veri[0]->nro > 0){
                                    $i->total_deuda=$i->monto_res;
                                    $i->sal_res = $i->monto_res-$veri[0]->t_interes;
                                    $i->int_res=$veri[0]->t_interes;
                                    $i->seg_res=0;
                                    $i->com_res=0;
                                }else{
                                    $sumveri=DB::select("select SUM(COALESCE(d.cuota, 0)) AS nro,t_interes from  cronograma c inner join detallecrono d on c.id = d.idcrono where c.idcredito = $i->id and d.situacion = 'P' 
                                    and fecha_ven <= '$request->fecha'");

                                    
                                    $dato= DB::select("select *, sum(cuota) as cuo,(sum(cuota)-SUM(seg_des+com_des+interes) )  as sal, sum(interes) as inte, sum(seg_des) as seg ,sum(com_des) as com
                                    from cronograma c
                                    inner join detallecrono d on c.id = d.idcrono 
                                    where c.idcredito = $i->id  and fecha_ven > '$request->fecha'");
                                    // DD($dato);
                                $i->sal_res=$dato[0]->sal -$i->redondeo;
                                $i->int_res=$dato[0]->inte;
                                $i->seg_res=$dato[0]->seg;
                                $i->com_res=$dato[0]->com;
                                if ($i->redondeo == 0 )  {
                                    $i->monto_res=$dato[0]->cuo;
                                }
                                if (isset($i->redondeo)) {
                                    $i->monto_res=$dato[0]->cuo;
                                }else{
                                    $i->monto_res=$dato[0]->cuo;
                                }

                                if (empty($sumveri)) {
                                    // $i->monto_res=$dato1->sum('cuota');
                                } else {
                                    if ($sumveri[0]->nro === null && $sumveri[0]->t_interes === null) {
                                        
                                    } else {
                                        // if ($i->id == 36) {
                                        //     DD($dato, $i, $dato[0]->cuo);
                                        // }
                                        if ($i->id == 464) {
                                            $i->monto_res=$i->monto_res + $sumveri[0]->nro;
                                        }
                                    }
                                }
                                // if ($i->id == 36) {
                                //     DD($dato, $i, $dato[0]->cuo);
                                // }
                            }
                        }
                }
            }else{
                $i->monto_res=0;
                $i->sal_res=0;
                $i->int_res=0;
                $i->seg_res=0;
                $i->com_res=0;
                
            }
            
         
       
            
        }
        return $data;
    }
    public function getReportMensualamo(Request $request){
        
        $res = [];
        $ani =db::Select("select year(f_desembolso) as anio FROM cronograma where year(f_desembolso) < $request->anio group by anio");
        for ($a=0;$a<count($ani);$a++){
            $res[$a]['mes']="SALDO - ".$ani[$a]->anio;
            $va=$ani[$a]->anio;
            $amor = DB::select("select sum(cap_amor) as monto  from pago  where  year(fecha_pago) = $va  ");
               if( count($amor) < 1 ){
                $res[$a]['tot_amor'] = 0;
               }else{
                $res[$a]['tot_amor'] = $amor[0]->monto;
               }
            $cancelado = DB::Select("select sum(monto_can) as monto from credito_cancel  where year(fecha_reg) = $va");
            // DD($cancelado);
           if( count($cancelado) < 1 ){
            $res[$a]['cancelado'] = 0;

           }else{
            $res[$a]['cancelado'] = floatval($cancelado[0]->monto);
            
                                      
           }
           $pago = DB::select("select SUM(mon_ne_desem) as monto from cronograma  where year(f_desembolso) = $va");
           if( count($pago) < 1 ){
            $res[$a]['desem'] = 0;
               $res[$a]['acumulado'] = 0;
               
            }else{
                $res[$a]['desem'] = 0;
                $res[$a]['acumulado'] = $pago[0]->monto;
                
               
            }
            $res[$a]['total'] = $res[$a]['tot_amor']+ $res[$a]['cancelado'] ;
            $res[$a]['deuda'] =  $res[$a]['acumulado']-$res[$a]['total']  ;

        }
        $i=count($ani)-1;
        for ($m=0; $m <= $request->mes  ; $m++) {
            if($m ==  1){
               $res[$m+$i]['mes']="Enero";
               $res[$m+$i]['nmes']=1;
               $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_pago) as mese  from pago  where  year(fecha_pago) = $request->anio  and  MONTH(fecha_pago) = 1 group by mese");
              // DD($amor);
               if( count($amor) < 1 ){
                $res[$m+$i]['amorti'] = 0;
               }else{
                $res[$m+$i]['amorti'] = $amor[0]->monto;
               }
               $cancelado = DB::Select("select sum(monto_can) as monto ,MONTH(fecha_reg) as mese  from credito_cancel  where year(fecha_reg)  = $request->anio and  MONTH(fecha_reg) = 1 group by mese;");
               if( count($cancelado) < 1 ){
                $res[$m+$i]['cancelado'] = 0;
               }else{
                $res[$m+$i]['cancelado'] = $cancelado[0]->monto;   
               }
               $pago = DB::select("select SUM(mon_ne_desem) as monto,MONTH(f_desembolso) as mese from cronograma  where year(f_desembolso) = $request->anio and MONTH(f_desembolso) = 1 group by mese;");
               if( count($pago) < 1 ){
                $res[$m+$i]['desem'] = 0;
                $res[$m+$i]['acumulado'] = 0+$res[$i]['acumulado'];
               }else{
                $res[$m+$i]['desem'] = $pago[0]->monto;
                $res[$m+$i]['acumulado'] = $pago[0]->monto+$res[$i]['acumulado'];
               }
             $res[$m+$i]['total'] =  $res[$m+$i]['amorti']+$res[$m+$i]['cancelado']+$res[$i]['total'] ;
             $res[$m+$i]['deuda'] =  $res[$m+$i]['acumulado']-$res[$m+$i]['total'];
               
            }
            if($m == 2){
                $res[$m+$i]['mes']="Febrero";
                $res[$m+$i]['nmes']=2;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_pago) as mese  from pago  where  year(fecha_pago) = $request->anio  and  MONTH(fecha_pago) = 2 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select sum(monto_can) as monto,MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg)  = $request->anio and  MONTH(fecha_reg) = 2 group by mese");
                if(count($cancelado) < 1){
                   
                 $res[$m+$i]['cancelado'] = 0;
                }else{
                   $res[$m+$i]['cancelado'] = $cancelado[0]->monto;
                }
                $pago = DB::select(" select SUM(mon_ne_desem) as monto,MONTH(f_desembolso) as mese from cronograma  where year(f_desembolso) = $request->anio and MONTH(f_desembolso) = 2 group by mese;");
                if(count($pago) < 1){
                    $res[$m+$i]['desem'] = 0;
                    $res[$m+$i]['acumulado'] = 0+$res[$i]['acumulado'];
                   }else{
                    $res[$m+$i]['desem'] = $pago[0]->monto;
                    $res[$m+$i]['acumulado'] = $pago[0]->monto+$res[$m+$i-1]['acumulado'];
                   }
                 $res[$m+$i]['total'] =  $res[$m+$i]['amorti']+$res[$m+$i]['cancelado']+$res[$m+$i-1]['total'] ;
                 $res[$m+$i]['deuda'] =  $res[$m+$i]['acumulado']-$res[$m+$i]['total'];
            }
            if($m== 3){
            $res[$m+$i]['mes']="Marzo";
            $res[$m+$i]['nmes']=3;
            $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_pago) as mese from pago  where  year(fecha_pago) = $request->anio  and  MONTH(fecha_pago) = 3 group by mese");
            if( count($amor) < 1 ){
             $res[$m+$i]['amorti'] = 0;
            }else{
             $res[$m+$i]['amorti'] = $amor[0]->monto;
            }
            $cancelado = DB::Select("select sum(monto_can) as monto,MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg)  = $request->anio and  MONTH(fecha_reg) = 3 group by mese");
            if(count($cancelado) < 1) {
                $res[$m+$i]['cancelado'] = 0;
            }else{
                $res[$m+$i]['cancelado'] = $cancelado[0]->monto;
                            
            }
            $pago = DB::select("select SUM(mon_ne_desem) as monto,MONTH(f_desembolso) as mese from cronograma  where year(f_desembolso) =$request->anio and MONTH(f_desembolso) = 3 group by mese;");
            if(count($pago)<1) {
                $res[$m+$i]['desem'] = 0;
                $res[$m+$i]['acumulado'] = 0+$res[$i]['acumulado'];
               }else{
                $res[$m+$i]['desem'] = $pago[0]->monto;
                $res[$m+$i]['acumulado'] = $pago[0]->monto+$res[$m+$i-1]['acumulado'];
               }
             $res[$m+$i]['total'] =  $res[$m+$i]['amorti']+$res[$m+$i]['cancelado']+$res[$m+$i-1]['total'];
             $res[$m+$i]['deuda'] =  $res[$m+$i]['acumulado']-$res[$m+$i]['total'];
            }
            if($m  == 4){
                $res[$m+$i]['mes']="Abril";
                $res[$m+$i]['nmes']=4;
                $amor = DB::select("select sum(cap_amor) as monto,MONTH(fecha_pago) as mese  from pago  where  year(fecha_pago) = $request->anio  and  MONTH(fecha_pago) = 4 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select sum(monto_can) as monto,MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg)  =  $request->anio and  MONTH(fecha_reg) = 4 group by mese");
                if(count($cancelado) < 1) {
                    $res[$m+$i]['cancelado'] = 0;
                }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;
                                
                }
                $pago = DB::select("select SUM(mon_ne_desem) as monto,MONTH(f_desembolso) as mese from cronograma  where year(f_desembolso) =$request->anio and MONTH(f_desembolso) = 4 group by mese;");
                if(count($pago)<1) {
                    $res[$m+$i]['desem'] = 0;
                    $res[$m+$i]['acumulado'] = 0+$res[$i]['acumulado'];
                   }else{
                    $res[$m+$i]['desem'] = $pago[0]->monto;
                    $res[$m+$i]['acumulado'] = $pago[0]->monto+$res[$m+$i-1]['acumulado'];
                   }
                 $res[$m+$i]['total'] =  $res[$m+$i]['amorti']+$res[$m+$i]['cancelado']+$res[$m+$i-1]['total'];
                 $res[$m+$i]['deuda'] =  $res[$m+$i]['acumulado']-$res[$m+$i]['total'];
            }
            if($m == 5){
                $res[$m+$i]['mes']="Mayo";
                $res[$m+$i]['nmes']=5;
                $amor = DB::select("select sum(cap_amor) as monto,MONTH(fecha_pago) as mese  from pago  where  year(fecha_pago) =  $request->anio  and  MONTH(fecha_pago) = 5 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select sum(monto_can) as monto,MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg)  = $request->anio and  MONTH(fecha_reg) = 5 group by mese ");
                if(count($cancelado) < 1) {
                    $res[$m+$i]['cancelado'] = 0;
                }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;                                
                }
                $pago = DB::select("select SUM(mon_ne_desem) as monto,MONTH(f_desembolso) as mese from cronograma  where year(f_desembolso) = $request->anio and MONTH(f_desembolso) = 5 group by mese;");
                if(count($pago)<1){
                    $res[$m+$i]['desem'] = 0;
                    $res[$m+$i]['acumulado'] = 0+$res[$i]['acumulado'];
                   }else{
                    $res[$m+$i]['desem'] = $pago[0]->monto;
                    $res[$m+$i]['acumulado'] = $pago[0]->monto+$res[$m+$i-1]['acumulado'];
                   }
                 $res[$m+$i]['total'] =  $res[$m+$i]['amorti']+$res[$m+$i]['cancelado']+$res[$m+$i-1]['total'];
                 $res[$m+$i]['deuda'] =  $res[$m+$i]['acumulado']-$res[$m+$i]['total'];
            }
            if($m == 6){
                $res[$m+$i]['mes']="Junio";
                $res[$m+$i]['nmes']=6;
                $amor = DB::select("select sum(cap_amor) as monto,MONTH(fecha_pago) as mese   from pago  where  year(fecha_pago) = $request->anio  and  MONTH(fecha_pago) = 6 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select sum(monto_can) as monto,MONTH(fecha_reg) as mese  from credito_cancel  where year(fecha_reg)  = $request->anio and  MONTH(fecha_reg) = 6 group by mese");
                if(count($cancelado) < 1){
                    $res[$m+$i]['cancelado'] = 0;
                }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;
                                
                }
                $pago = DB::select("select SUM(mon_ne_desem) as monto, MONTH(f_desembolso) as mese from cronograma  where year(f_desembolso) = $request->anio and MONTH(f_desembolso) = 6 group by mese;");
                if(count($pago)<1) {
                    $res[$m+$i]['desem'] = 0;
                    $res[$m+$i]['acumulado'] = 0+$res[$i]['acumulado'];
                   }else{
                    $res[$m+$i]['desem'] = $pago[0]->monto;
                    $res[$m+$i]['acumulado'] = $pago[0]->monto+$res[$m+$i-1]['acumulado'];
                   }
                 $res[$m+$i]['total'] =  $res[$m+$i]['amorti']+$res[$m+$i]['cancelado']+$res[$m+$i-1]['total'];
                 $res[$m+$i]['deuda'] =  $res[$m+$i]['acumulado']-$res[$m+$i]['total'];
            }
            if($m == 7){
                $res[$m+$i]['mes']="Julio";
                $res[$m+$i]['nmes']=7;
                $amor = DB::select("select sum(cap_amor) as monto,MONTH(fecha_pago) as mese   from pago  where  year(fecha_pago) = $request->anio  and  MONTH(fecha_pago) = 7 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select sum(monto_can) as monto,MONTH(fecha_reg) as mese  from credito_cancel  where year(fecha_reg)  = $request->anio and  MONTH(fecha_reg) = 7 group by mese");
                // (count($cancelado) < 1);
                if(count($cancelado) < 1){
                    $res[$m+$i]['cancelado'] = 0;
                }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;
                                
                }
                $pago = DB::select("select SUM(mon_ne_desem) as monto, MONTH(f_desembolso) as mese from cronograma  where year(f_desembolso) = $request->anio and MONTH(f_desembolso) = 7 group by mese;");
                if(count($pago)<1) {
                    $res[$m+$i]['desem'] = 0;
                    $res[$m+$i]['acumulado'] = 0+$res[$i]['acumulado'];
                   }else{
                    $res[$m+$i]['desem'] = $pago[0]->monto;
                    $res[$m+$i]['acumulado'] = $pago[0]->monto+$res[$m+$i-1]['acumulado'];
                   }
                 $res[$m+$i]['total'] =  $res[$m+$i]['amorti']+$res[$m+$i]['cancelado']+$res[$m+$i-1]['total'];
                 $res[$m+$i]['deuda'] =  $res[$m+$i]['acumulado']-$res[$m+$i]['total'];
            }
            if($m == 8){
                $res[$m+$i]['mes']="Agosto";
                $res[$m+$i]['nmes']=8;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_pago) as mese from pago  where  year(fecha_pago) = $request->anio  and  MONTH(fecha_pago) = 8 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select sum(monto_can) as monto, MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 8 group by mese");
                if(count($cancelado) < 1){
                    $res[$m+$i]['cancelado'] = 0;
                }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;
                                
                }
                $pago = DB::select("select SUM(mon_ne_desem) as monto,MONTH(f_desembolso) as mese from cronograma  where year(f_desembolso) = $request->anio and MONTH(f_desembolso) = 8 group by mese;");
                if(count($pago)<1){
                    $res[$m+$i]['desem'] = 0;
                    $res[$m+$i]['acumulado'] = 0+$res[$i]['acumulado'];
                   }else{
                    $res[$m+$i]['desem'] = $pago[0]->monto;
                    $res[$m+$i]['acumulado'] = $pago[0]->monto+$res[$m+$i-1]['acumulado'];
                   }
                 $res[$m+$i]['total'] =  $res[$m+$i]['amorti']+$res[$m+$i]['cancelado']+$res[$m+$i-1]['total'];
                 $res[$m+$i]['deuda'] =  $res[$m+$i]['acumulado']-$res[$m+$i]['total'];
            }
            if($m == 9){
                $res[$m+$i]['mes']="Setiembre";
                $res[$m+$i]['nmes']=9;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_pago) as mese  from pago  where  year(fecha_pago) = $request->anio  and  MONTH(fecha_pago) = 9 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select sum(monto_can) as monto,MONTH(fecha_reg) as mese  from credito_cancel  where year(fecha_reg)  = $request->anio and  MONTH(fecha_reg) = 9 group by mese");
                if(count($cancelado) < 1){
                    $res[$m+$i]['cancelado'] = 0;
                }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;      
                }
                $pago = DB::select("select SUM(mon_ne_desem) as monto,MONTH(f_desembolso) as mese from cronograma  where year(f_desembolso) = $request->anio and MONTH(f_desembolso) = 9 group by mese;");
                // DD($pago);
                if(count($pago) < 1 ){
                    $res[$m+$i]['desem'] = 0;
                    $res[$m+$i]['acumulado'] = 0+$res[$i]['acumulado'];
                   }else{
                    $res[$m+$i]['desem'] = $pago[0]->monto;
                    $res[$m+$i]['acumulado'] = $pago[0]->monto+$res[$m+$i-1]['acumulado'];
                   }
                 $res[$m+$i]['total'] =  $res[$m+$i]['amorti']+$res[$m+$i]['cancelado']+$res[$m+$i-1]['total'];
                 $res[$m+$i]['deuda'] =  $res[$m+$i]['acumulado']-$res[$m+$i]['total'];
            }
            if($m == 10){
                $res[$m+$i]['mes']="Octubre";
                $res[$m+$i]['nmes']=10;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_pago) as mese   from pago  where  year(fecha_pago) = $request->anio  and  MONTH(fecha_pago) = 10 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select sum(monto_can) as monto,MONTH(fecha_reg) as mese   from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 10 group by mese");
                if(count($cancelado) < 1){
                    $res[$m+$i]['cancelado'] = 0;
                }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;
                                
                }
                $pago = DB::select("select SUM(mon_ne_desem) as monto,MONTH(f_desembolso) as mese from cronograma  where year(f_desembolso) =$request->anio and MONTH(f_desembolso) = 10 group by mese;");
                if(count($pago) < 1 ){
                    $res[$m+$i]['desem'] = 0;
                    $res[$m+$i]['acumulado'] = 0+$res[$i]['acumulado'];
                   }else{
                    $res[$m+$i]['desem'] = $pago[0]->monto;
                    $res[$m+$i]['acumulado'] = $pago[0]->monto+$res[$m+$i-1]['acumulado'];
                   }
                 $res[$m+$i]['total'] =  $res[$m+$i]['amorti']+$res[$m+$i]['cancelado']+$res[$m+$i-1]['total'];
                 $res[$m+$i]['deuda'] =  $res[$m+$i]['acumulado']-$res[$m+$i]['total'];
            }
            if($m == 11){
                $res[$m+$i]['mes']="Noviembre";
                $res[$m+$i]['nmes']=11;
                $amor = DB::select("select sum(cap_amor) as monto, MONTH(fecha_pago) as mese    from pago  where  year(fecha_pago) = $request->anio  and  MONTH(fecha_pago) = 11 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select sum(monto_can) as monto,MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) =$request->anio and  MONTH(fecha_reg) = 11 group by mese");
                if(count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;
                                
                }
                $pago = DB::select("select SUM(mon_ne_desem) as monto,MONTH(f_desembolso) as mese from cronograma  where year(f_desembolso) = $request->anio and MONTH(f_desembolso) = 11 group by mese;");
                if(count($pago) < 1){
                    $res[$m+$i]['desem'] = 0;
                    $res[$m+$i]['acumulado'] = 0+$res[$i]['acumulado'];
                   }else{
                    $res[$m+$i]['desem'] = $pago[0]->monto;
                    $res[$m+$i]['acumulado'] = $pago[0]->monto+$res[$m+$i-1]['acumulado'];
                   }
                 $res[$m+$i]['total'] =  $res[$m+$i]['amorti']+$res[$m+$i]['cancelado']+$res[$m+$i-1]['total'];
                 $res[$m+$i]['deuda'] =  $res[$m+$i]['acumulado']-$res[$m+$i]['total'];
            }
            if($m == 12){
                $res[$m+$i]['mes']="Diciembre";
                $res[$m+$i]['nmes']=12;
                $amor = DB::select("select sum(cap_amor) as monto,MONTH(fecha_pago) as mese  from pago  where  year(fecha_pago) = $request->anio  and  MONTH(fecha_pago) = 12 group by mese");
                if( count($amor) < 1 ){
                 $res[$m+$i]['amorti'] = 0;
                }else{
                 $res[$m+$i]['amorti'] = $amor[0]->monto;
                }
                $cancelado = DB::Select("select sum(monto_can) as monto,MONTH(fecha_reg) as mese from credito_cancel  where year(fecha_reg) = $request->anio and  MONTH(fecha_reg) = 12 group by mese");
                if(count($cancelado) < 1 ){
                    $res[$m+$i]['cancelado'] = 0;
                }else{
                    $res[$m+$i]['cancelado'] = $cancelado[0]->monto;
                                
                }
                $pago = DB::select("select SUM(mon_ne_desem) as monto,MONTH(f_desembolso) as mese from cronograma  where year(f_desembolso) = $request->anio and MONTH(f_desembolso) = 12 group by mese;");
                if(count($pago) < 1 ){
                    $res[$m+$i]['desem'] = 0;
                    $res[$m+$i]['acumulado'] = 0+$res[$i]['acumulado'];
                   }else{
                    $res[$m+$i]['desem'] = $pago[0]->monto;
                    $res[$m+$i]['acumulado'] = $pago[0]->monto+$res[$m+$i-1]['acumulado'];
                   }
                 $res[$m+$i]['total'] =  $res[$m+$i]['amorti']+$res[$m+$i]['cancelado']+$res[$m+$i-1]['total'];
                 $res[$m+$i]['deuda'] =  $res[$m+$i]['acumulado']-$res[$m+$i]['total'];
            }
            
        }



        return response()->json($res);
    }
}
