<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuota;
use App\Models\Cronograma;
use Illuminate\Support\Arr;
Use \Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use \PhpOffice\PhpSpreadsheet\Shared\Date;
use \PhpOffice\PhpSpreadsheet\Calculation\Financial;
use \PhpOffice\PhpSpreadsheet\Style\NumberFormat;
// use \PhpOffice\PhpSpreadsheet\Calculation;

use Illuminate\Support\Collection;
use DB;


class CuotaController extends Controller
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
        $cuota=Cuota::where('idcronograma',$id)->get();
        // DD($cuota);
        return $cuota;
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
        $cuota=Cuota::findOrFail($id);
        $cuota->update($request->all());
        return $cuota;
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


    public function updateforidcredito(Request $request, $idcredito){


        $cuota= Cuota::where('id',$request->dni)->get()->first();
        $cuota=Cuota::findOrFail($id);
        $cuota->update($request->all());
        return $cuota;
    }
    public function irr($flow) {
        // DD($flow);
        $tir =Financial::IRR($flow);
        $tcea = pow(1+ $tir, 12)-1;
        return $tcea;

    }

    public function prueba(){
       return 'entrando a prueba';
    }

    public function calcularNuevoCrono($request){
        // return $request;
        // public function calctcea( $fechaPrincipal = '2021-03-05', $monto_financiado=1500, $numero_cuotas=18){
        $fecha_cuota_uno = Carbon::parse($request->fecha_cuota)->toDateString();
        
        // $segu = DB::table('seguro_desg')->where('estado',1)->first();
        // $seguro = $segu->seg_des;
        $com_desc_automatico = $request->comision_descuento;
        
        $seguro = $request->desgravament;
        $teanueva = $request->tea/100; 
        $fechaPrincipal = $request->fecha;
        $monto_financiado=$request->montofinanciado;
        $numero_cuotas=$request->rows;
        $fecha=Carbon::parse($fechaPrincipal)->toDateString();
        $array = array();
        $arrayTCEA = array();
        array_push($array, (object)[
            "num_cuota"=>0,
            "fecha_vencimiento"=>$fecha,
            "saldo_capital"=>$monto_financiado,
            "capital_amortizado"=>0,
            "interes"=>0,
            "subtotal_cuota"=>0,
            "com_desc_automatico"=>$com_desc_automatico,
            "seguro_degrav"=>0,
            "TCEA"=>-$monto_financiado,
            ]);
        $interes = pow((1+$teanueva), (1/12))-1;

        for ($i=1; $i <= $numero_cuotas; $i++) {
            if ($i < 2) {
                # code...
                $fecha_cuota_uno = $fecha_cuota_uno;
            } else {
                $fecha_cuota_uno = date("Y-m-d",strtotime($fecha_cuota_uno."+ 1 month"));
            }
            array_push($array, (object)[
                "num_cuota"=>$i,
                "fecha_vencimiento"=>$fecha_cuota_uno,
                "saldo_capital"=>0,
                "capital_amortizado"=>0,
                "interes"=>0,
                "subtotal_cuota"=>($monto_financiado*($interes))/(1-(pow((1+$interes),-$numero_cuotas))),
                "com_desc_automatico"=>$com_desc_automatico,
                "seguro_degrav"=>0,
                "TCEA"=>0,
                ]);
                $array[$i]->saldo_capital=$array[$i-1]->saldo_capital - $array[$i-1]->capital_amortizado;
                $array[$i]->seguro_degrav=$array[$i]->saldo_capital * $seguro;
                $fechavencimientoanterior = Date::PHPToExcel($array[$i-1]->fecha_vencimiento);
                $fechavencimientoactual = Date::PHPToExcel($array[$i]->fecha_vencimiento);
                $array[$i]->interes=$array[$i]->saldo_capital*(pow(1+$teanueva,($fechavencimientoactual - $fechavencimientoanterior)/360) -1);
                $array[$i]->capital_amortizado=$array[$i]->subtotal_cuota - $array[$i]->interes;
                $array[$i]->TCEA = $array[$i]->seguro_degrav + $array[$i]->com_desc_automatico + $array[$i]->subtotal_cuota;
        };

        foreach ($array as $item) {
            array_push($arrayTCEA, $item->TCEA);
        };
        $tcea = $this->irr($arrayTCEA);
        $result = $this->crono_intermedio($fechaPrincipal, $monto_financiado, $numero_cuotas,$tcea, $request->fecha_cuota,$teanueva, $seguro, $com_desc_automatico);
        return json_encode($result);
    }
    public function calctceanohttp( $request){

        // public function calctcea( $fechaPrincipal = '2021-03-05', $monto_financiado=1500, $numero_cuotas=18){
        $fecha_cuota_uno = Carbon::parse($request->fecha_cuota)->toDateString();
        
        // $segu = DB::table('seguro_desg')->where('estado',1)->first();
        // $seguro = $segu->seg_des;
        $com_desc_automatico = $request->comision_descuento;
        
        $seguro = $request->desgravament;
        $teanueva = $request->tea/100; 
        $fechaPrincipal = $request->fecha;
        $monto_financiado=$request->montofinanciado;
        $numero_cuotas=$request->rows;
        $fecha=Carbon::parse($fechaPrincipal)->toDateString();
        $array = array();
        $arrayTCEA = array();
        array_push($array, (object)[
            "num_cuota"=>0,
            "fecha_vencimiento"=>$fecha,
            "saldo_capital"=>$monto_financiado,
            "capital_amortizado"=>0,
            "interes"=>0,
            "subtotal_cuota"=>0,
            "com_desc_automatico"=>$com_desc_automatico,
            "seguro_degrav"=>0,
            "TCEA"=>-$monto_financiado,
            ]);
        $interes = pow((1+$teanueva), (1/12))-1;

        for ($i=1; $i <= $numero_cuotas; $i++) {
            if ($i < 2) {
                # code...
                $fecha_cuota_uno = $fecha_cuota_uno;
            } else {
                $fecha_cuota_uno = date("Y-m-d",strtotime($fecha_cuota_uno."+ 1 month"));
            }
            array_push($array, (object)[
                "num_cuota"=>$i,
                "fecha_vencimiento"=>$fecha_cuota_uno,
                "saldo_capital"=>0,
                "capital_amortizado"=>0,
                "interes"=>0,
                "subtotal_cuota"=>($monto_financiado*($interes))/(1-(pow((1+$interes),-$numero_cuotas))),
                "com_desc_automatico"=>$com_desc_automatico,
                "seguro_degrav"=>0,
                "TCEA"=>0,
                ]);
                $array[$i]->saldo_capital=$array[$i-1]->saldo_capital - $array[$i-1]->capital_amortizado;
                $array[$i]->seguro_degrav=$array[$i]->saldo_capital * $seguro;
                $fechavencimientoanterior = Date::PHPToExcel($array[$i-1]->fecha_vencimiento);
                $fechavencimientoactual = Date::PHPToExcel($array[$i]->fecha_vencimiento);
                $array[$i]->interes=$array[$i]->saldo_capital*(pow(1+$teanueva,($fechavencimientoactual - $fechavencimientoanterior)/360) -1);
                $array[$i]->capital_amortizado=$array[$i]->subtotal_cuota - $array[$i]->interes;
                $array[$i]->TCEA = $array[$i]->seguro_degrav + $array[$i]->com_desc_automatico + $array[$i]->subtotal_cuota;
        };

        foreach ($array as $item) {
            array_push($arrayTCEA, $item->TCEA);
        };
        $tcea = $this->irr($arrayTCEA);
        $result = $this->crono_intermedio($fechaPrincipal, $monto_financiado, $numero_cuotas,$tcea, $request->fecha_cuota,$teanueva, $seguro, $com_desc_automatico);
        return json_encode($result);
    }

    public function calctcea( Request $request){

        // public function calctcea( $fechaPrincipal = '2021-03-05', $monto_financiado=1500, $numero_cuotas=18){
        $fecha_cuota_uno = Carbon::parse($request->fecha_cuota)->toDateString();
        
        // $segu = DB::table('seguro_desg')->where('estado',1)->first();
        // $seguro = $segu->seg_des;
        $com_desc_automatico = $request->comision_descuento;
        
        $seguro = $request->desgravament;
        $teanueva = $request->tea/100; 
        $fechaPrincipal = $request->fecha;
        $monto_financiado=$request->montofinanciado;
        $numero_cuotas=$request->rows;
        $fecha=Carbon::parse($fechaPrincipal)->toDateString();
        $array = array();
        $arrayTCEA = array();
        array_push($array, (object)[
            "num_cuota"=>0,
            "fecha_vencimiento"=>$fecha,
            "saldo_capital"=>$monto_financiado,
            "capital_amortizado"=>0,
            "interes"=>0,
            "subtotal_cuota"=>0,
            "com_desc_automatico"=>$com_desc_automatico,
            "seguro_degrav"=>0,
            "TCEA"=>-$monto_financiado,
            ]);
        $interes = pow((1+$teanueva), (1/12))-1;

        for ($i=1; $i <= $numero_cuotas; $i++) {
            if ($i < 2) {
                # code...
                $fecha_cuota_uno = $fecha_cuota_uno;
            } else {
                $fecha_cuota_uno = date("Y-m-d",strtotime($fecha_cuota_uno."+ 1 month"));
            }
            array_push($array, (object)[
                "num_cuota"=>$i,
                "fecha_vencimiento"=>$fecha_cuota_uno,
                "saldo_capital"=>0,
                "capital_amortizado"=>0,
                "interes"=>0,
                "subtotal_cuota"=>($monto_financiado*($interes))/(1-(pow((1+$interes),-$numero_cuotas))),
                "com_desc_automatico"=>$com_desc_automatico,
                "seguro_degrav"=>0,
                "TCEA"=>0,
                ]);
                $array[$i]->saldo_capital=$array[$i-1]->saldo_capital - $array[$i-1]->capital_amortizado;
                $array[$i]->seguro_degrav=$array[$i]->saldo_capital * $seguro;
                $fechavencimientoanterior = Date::PHPToExcel($array[$i-1]->fecha_vencimiento);
                $fechavencimientoactual = Date::PHPToExcel($array[$i]->fecha_vencimiento);
                $array[$i]->interes=$array[$i]->saldo_capital*(pow(1+$teanueva,($fechavencimientoactual - $fechavencimientoanterior)/360) -1);
                $array[$i]->capital_amortizado=$array[$i]->subtotal_cuota - $array[$i]->interes;
                $array[$i]->TCEA = $array[$i]->seguro_degrav + $array[$i]->com_desc_automatico + $array[$i]->subtotal_cuota;
        };

        foreach ($array as $item) {
            array_push($arrayTCEA, $item->TCEA);
        };
        $tcea = $this->irr($arrayTCEA);
        $result = $this->crono_intermedio($fechaPrincipal, $monto_financiado, $numero_cuotas,$tcea, $request->fecha_cuota,$teanueva, $seguro, $com_desc_automatico);
        return json_encode($result);
    }



    public function crono_intermedio($fechaPrincipal, $monto_financiado, $numero_cuotas, $tcea, $fecha_cuota,$teanueva, $seguro, $com_desc_automatico){
        $fecha_cuota_uno = Carbon::parse($fecha_cuota)->toDateString();
        $fecha = $fechaPrincipal;
        $array_crono_intermedio = array();
        $arraycuota = array();
        $teanueva1 = $teanueva;
        $seguro1 = $seguro;
        array_push($array_crono_intermedio, (object)[
            "num_cuota"=>0,
            "fecha_vencimiento"=>$fecha,
            "saldo_capital"=>$monto_financiado,
            "capital_amortizado"=>0,
            "interes"=>0,
            "com_desc_automatico"=>$com_desc_automatico,
            "seguro_degrav"=>0,
            "cuota"=>-$monto_financiado,
        ]);
        $interes = pow((1+$tcea), (1/12))-1;
        for ($i=1; $i <= $numero_cuotas; $i++) {
            if ($i < 2) {
                # code...
                $fecha_cuota_uno = $fecha_cuota_uno;
            } else {
                $fecha_cuota_uno = date("Y-m-d",strtotime($fecha_cuota_uno."+ 1 month"));
            }
            // $fecha = date("Y-m-d",strtotime($fecha."+ 1 month"));
            array_push($array_crono_intermedio, (object)[
                "num_cuota"=>$i,
                "fecha_vencimiento"=>$fecha_cuota_uno,
                "saldo_capital"=>0,
                "capital_amortizado"=>0,
                "interes"=>0,
                "com_desc_automatico"=>$com_desc_automatico,
                "seguro_degrav"=>0,
                "cuota"=>0,
                ]);
                $array_crono_intermedio[$i]->cuota = ($monto_financiado * $interes)/(1- pow(1+$interes, -$numero_cuotas));
                $array_crono_intermedio[$i]->saldo_capital=$array_crono_intermedio[$i-1]->saldo_capital - $array_crono_intermedio[$i-1]->capital_amortizado;
                $array_crono_intermedio[$i]->seguro_degrav=$array_crono_intermedio[$i]->saldo_capital * $seguro1;
                $fechavencimientoanterior = Date::PHPToExcel($array_crono_intermedio[$i-1]->fecha_vencimiento);
                $fechavencimientoactual = Date::PHPToExcel($array_crono_intermedio[$i]->fecha_vencimiento);
                $array_crono_intermedio[$i]->interes=$array_crono_intermedio[$i]->saldo_capital*(pow(1+$teanueva1,($fechavencimientoactual - $fechavencimientoanterior)/360) -1);
                $array_crono_intermedio[$i]->capital_amortizado=$array_crono_intermedio[$i]->cuota - $array_crono_intermedio[$i]->seguro_degrav - $array_crono_intermedio[$i]->com_desc_automatico - $array_crono_intermedio[$i]->interes;

                if ($numero_cuotas == $i) {
                    $array_crono_intermedio[$i]->capital_amortizado=$array_crono_intermedio[$i]->saldo_capital;
                    $array_crono_intermedio[$i]->cuota = $array_crono_intermedio[$i]->capital_amortizado + $array_crono_intermedio[$i]->interes + $array_crono_intermedio[$i]->com_desc_automatico + $array_crono_intermedio[$i]->seguro_degrav;
                }
        };
        foreach ($array_crono_intermedio as $item) {
            array_push($arraycuota, $item->cuota);
        }


        $tir =Financial::IRR($arraycuota);
        $toto =  $this->crono_client($fechaPrincipal, $monto_financiado, $numero_cuotas,$tir, $fecha_cuota,$teanueva1, $seguro1, $com_desc_automatico);
        return $toto;
    }

    public function crono_client ($fecha, $monto_financiado, $numero_cuotas,$tir, $fecha_cuota ,$teanueva1, $seguro1, $com_desc_automatico){
        $fecha_cuota_uno = Carbon::parse($fecha_cuota)->toDateString();
        $sumaintereses = 0;
        $ultimafechacuota;
        $array_crono_intermedio = array();
        $arraycuota = array();
        $teanueva2 = $teanueva1;
        $seguro2 = $seguro1;
        array_push($array_crono_intermedio, (object)[
            "num_cuota"=>0,
            "fecha_vencimiento"=>$fecha,
            "saldo_capital"=>$monto_financiado,
            "capital_amortizado"=>0,
            "interes"=>0,
            "com_desc_automatico"=>$com_desc_automatico,
            "seguro_degrav"=>0,
            "cuota"=>-$monto_financiado,
        ]);
        $interes = $tir;
        for ($i=1; $i <= $numero_cuotas; $i++) {
            if ($i < 2) {
                # code...
                $fecha_cuota_uno = $fecha_cuota_uno;
            } else {
                $fecha_cuota_uno = date("Y-m-d",strtotime($fecha_cuota_uno."+ 1 month"));
            }
            // $fecha = date("Y-m-d",strtotime($fecha."+ 1 month"));
            array_push($array_crono_intermedio, (object)[
                "num_cuota"=>$i,
                "fecha_vencimiento"=>$fecha_cuota_uno,
                "saldo_capital"=>0,
                "capital_amortizado"=>0,
                "interes"=>0,
                "com_desc_automatico"=>$com_desc_automatico,
                "seguro_degrav"=>0,
                "cuota"=>0,
                ]);
                $array_crono_intermedio[$i]->cuota = ($monto_financiado * $interes)/(1- pow(1+$interes, -$numero_cuotas));
                $array_crono_intermedio[$i]->saldo_capital=$array_crono_intermedio[$i-1]->saldo_capital - $array_crono_intermedio[$i-1]->capital_amortizado;
                $array_crono_intermedio[$i]->seguro_degrav=$array_crono_intermedio[$i]->saldo_capital * $seguro2;
                $fechavencimientoanterior = Date::PHPToExcel($array_crono_intermedio[$i-1]->fecha_vencimiento);
                $fechavencimientoactual = Date::PHPToExcel($array_crono_intermedio[$i]->fecha_vencimiento);
                $array_crono_intermedio[$i]->interes=$array_crono_intermedio[$i]->saldo_capital*(pow(1+$teanueva2,($fechavencimientoactual - $fechavencimientoanterior)/360) -1);
                $array_crono_intermedio[$i]->capital_amortizado=$array_crono_intermedio[$i]->cuota - $array_crono_intermedio[$i]->seguro_degrav - $array_crono_intermedio[$i]->com_desc_automatico - $array_crono_intermedio[$i]->interes;
                if ($numero_cuotas == $i) {
                    $array_crono_intermedio[$i]->capital_amortizado=$array_crono_intermedio[$i]->saldo_capital;
                    $ultimafechacuota = $array_crono_intermedio[$i]->fecha_vencimiento;
                    // $array_crono_intermedio[$i]->cuota = round($array_crono_intermedio[$i]->capital_amortizado + $array_crono_intermedio[$i]->interes + $array_crono_intermedio[$i]->com_desc_automatico + $array_crono_intermedio[$i]->seguro_degrav,2);
                }
            };
        foreach ($array_crono_intermedio as $item) {
            $sumaintereses = $sumaintereses + $item->interes;
            array_push($arraycuota, $item->cuota);
        };

        $ultimafechacuota = date("d-m-Y",strtotime($ultimafechacuota));
        $f_cuota_final = date("Y-m-d",strtotime($ultimafechacuota));
        $response = (object)[
            "ultima_fecha" => $ultimafechacuota,
            "f_cuota_final" => $f_cuota_final,
            "suma_intereses" => round($sumaintereses,2),
            "cuotas"=>$array_crono_intermedio,
            "seguro"=>$seguro2,
        ];


        return $response;
    }


    public function getcuotasvencidas(Request $request){
        // DD($request);
        return DB::select("select d.*,c2.idpersona as idcliente, c2.numero as numero_credito, CONCAT(p.nombres,' ', p.paterno, ' ' ,p.materno) as nom_ape,
        (select concat(com.serie,'-',com.correlativo) from comprobante com where com.id_detallecrono = d.id) as tiene_comprobante
        from detallecrono d
        inner join cronograma c on d.idcrono = c.id 
        inner join credito c2 on c.idcredito = c2.id
        inner join persona p on c2.idpersona = p.id
        where year(fecha_ven) = $request->anio
        and month(fecha_ven) = $request->mes
        and (situacion = 'P' OR  situacion = 'C')
        and c2.situacion_apro = 'A' AND c2.estado = 1  and (estado_cred <> 'CA' or d.mon_pag > 0) order by fecha_ven ");
    }
}
