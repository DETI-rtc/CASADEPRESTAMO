<?php

namespace App\Http\Controllers;


use App\Events\CreditoStatusChangedEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Credito;
use App\Models\Persona;
use App\Models\Tasainteres;
use App\Models\Relacionci;
use App\Models\Relaciondi;
use App\Models\Plazo;
use App\Models\Delista;
use App\Models\Empresa;

use App\Models\Detacronograma;
use Carbon\Carbon ;

use App\Models\Cuota;
use App\Models\Cronograma;
use Illuminate\Support\Arr;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use \PhpOffice\PhpSpreadsheet\Shared\Date;
use \PhpOffice\PhpSpreadsheet\Calculation\Financial;
use \PhpOffice\PhpSpreadsheet\Style\NumberFormat;
// use \PhpOffice\PhpSpreadsheet\Calculation;




use Illuminate\Database\Eloquent\Collection;
use DB;

class FueraController extends Controller
{
     public function irr($flow) {
        // DD($flow);
        $tir =Financial::IRR($flow);
        $tcea = pow(1+ $tir, 12)-1;
        return $tcea;

    }
    public function crono_intermedio($fechaPrincipal, $monto_financiado, $numero_cuotas, $tcea, $fecha_cuota,$teanueva, $seguro){
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
            "com_desc_automatico"=>0,
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
                "com_desc_automatico"=>0,
                "seguro_degrav"=>0,
                "cuota"=>0,
                ]);
                $array_crono_intermedio[$i]->cuota = ($monto_financiado * $interes)/(1- pow(1+$interes, -$numero_cuotas));
                $array_crono_intermedio[$i]->saldo_capital=round($array_crono_intermedio[$i-1]->saldo_capital - $array_crono_intermedio[$i-1]->capital_amortizado,2);
                $array_crono_intermedio[$i]->seguro_degrav=$array_crono_intermedio[$i]->saldo_capital * $seguro1;
                $fechavencimientoanterior = Date::PHPToExcel($array_crono_intermedio[$i-1]->fecha_vencimiento);
                $fechavencimientoactual = Date::PHPToExcel($array_crono_intermedio[$i]->fecha_vencimiento);
                $array_crono_intermedio[$i]->interes=$array_crono_intermedio[$i]->saldo_capital*(pow(1+$teanueva1,($fechavencimientoactual - $fechavencimientoanterior)/360) -1);
                $array_crono_intermedio[$i]->capital_amortizado=round($array_crono_intermedio[$i]->cuota - $array_crono_intermedio[$i]->seguro_degrav - $array_crono_intermedio[$i]->com_desc_automatico - $array_crono_intermedio[$i]->interes, 2);

                if ($numero_cuotas == $i) {
                    $array_crono_intermedio[$i]->capital_amortizado=$array_crono_intermedio[$i]->saldo_capital;
                    $array_crono_intermedio[$i]->cuota = $array_crono_intermedio[$i]->capital_amortizado + $array_crono_intermedio[$i]->interes + $array_crono_intermedio[$i]->com_desc_automatico + $array_crono_intermedio[$i]->seguro_degrav;
                }
        };
        foreach ($array_crono_intermedio as $item) {
            array_push($arraycuota, $item->cuota);
        }


        $tir =Financial::IRR($arraycuota);
        $toto =  $this->crono_client($fechaPrincipal, $monto_financiado, $numero_cuotas,$tir, $fecha_cuota,$teanueva1, $seguro1);
        return $toto;
    }

    	public function crono_client ($fecha, $monto_financiado, $numero_cuotas,$tir, $fecha_cuota ,$teanueva1, $seguro1){
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
            "com_desc_automatico"=>0,
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
                "com_desc_automatico"=>0,
                "seguro_degrav"=>0,
                "cuota"=>0,
                ]);
                $array_crono_intermedio[$i]->cuota = round(($monto_financiado * $interes)/(1- pow(1+$interes, -$numero_cuotas)),2);
                $array_crono_intermedio[$i]->saldo_capital=round($array_crono_intermedio[$i-1]->saldo_capital - $array_crono_intermedio[$i-1]->capital_amortizado,2);
                $array_crono_intermedio[$i]->seguro_degrav=round($array_crono_intermedio[$i]->saldo_capital * $seguro2,2);
                $fechavencimientoanterior = Date::PHPToExcel($array_crono_intermedio[$i-1]->fecha_vencimiento);
                $fechavencimientoactual = Date::PHPToExcel($array_crono_intermedio[$i]->fecha_vencimiento);
                $array_crono_intermedio[$i]->interes=round($array_crono_intermedio[$i]->saldo_capital*(pow(1+$teanueva2,($fechavencimientoactual - $fechavencimientoanterior)/360) -1),2);
                $array_crono_intermedio[$i]->capital_amortizado=round($array_crono_intermedio[$i]->cuota - $array_crono_intermedio[$i]->seguro_degrav - $array_crono_intermedio[$i]->com_desc_automatico - $array_crono_intermedio[$i]->interes, 2);
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
    public function calculomonto1 (Request $request){

        
        //$persona = Persona::with('tmodalidad', 'tentidad', 'empresa', 'estadocivil')->where('id',1)-> first();
        // $persona->with('empresa')->get();
        //$credi=[];
//return $request;
        $teafija = Tasainteres::where('identidad', $request->idsector)->where('idmodalidad',$request->idmodalidad)->where('estado',1)->first();
        $rci = Relacionci::where('identidad', $request->idsector)->where('idmodalidad',$request->idmodalidad)->get();
        $rdi = Relaciondi::where('identidad', $request->idsector)->where('idmodalidad',$request->idmodalidad)->get();
        $plazo = Plazo::where('identidad', $request->idsector)->where('idmodalidad',$request->idmodalidad)->get();
        $inte = Tasainteres::where('identidad', $request->idsector)->where('idmodalidad',$request->idmodalidad)->get();
        $credi['ingre_bru']=$request->ingre_bru;
        // DD($empresa);
        $credi['meses_fal_cont']= $request->meses_faltantes;
        $credi['patrimonio']='casa propia';
        $credi['des_ley'] = $request->descuento_ley;
        $credi['ing_neto']= floatval($request->ingre_bru)-floatval($credi['des_ley']);
        $credi['idrci']= $rci[0]->rci_max;
        $credi['ing_netodiscred']= floatval($credi['ing_neto']) * floatval($credi['idrci']/100);
        $credi['sal_deuda_sc']=0;
        $credi['sal_deuda_cc']=0;
        $credi['sal_hipo']=0;
        $credi['deuda_sc']=$credi['sal_deuda_sc']/24;
        $credi['deuda_cc']=0;
        $credi['deuda_hipo']=0;
        $credi['cuota_max_pres']=$credi['ing_netodiscred']-$credi['deuda_sc']-$credi['deuda_cc']-$credi['deuda_hipo'];
        $credi['idplazo']=$plazo[0]->pla_max;
        
        $ideplazo = $plazo[0]->id;
        // DD($ideplazo);


        if ($ideplazo == 3 || $ideplazo == 8 || $ideplazo == 1 || $ideplazo == 6) {
            $credi['plazo_mas_ope']=48;
        } else{
            if ($request->meses_faltantes == 0) {
                $credi['plazo_mas_ope']=$plazo[0]->pla_min;
            }else{
                $credi['plazo_mas_ope']=min($credi['idplazo'],$request->meses_faltantes);
            }
            
        };
        // DD($credi['plazo_mas_ope']);

        $credi['primer_pago']=30;
        $credi['idtasa_int']=$inte[0]->tea;
        $credi['tem_rci']=pow((1+($credi['idtasa_int']/100)), (1/12))-1;
        $credi['monto_max_rci']= ((1-(pow((1+$credi['tem_rci']),-floatval($credi['plazo_mas_ope']))))*$credi['cuota_max_pres'])/$credi['tem_rci'];
        $credi['idrdi']=$rdi[0]->rdi_max;
        $credi['max_ende']=$credi['ing_neto']*$credi['idrdi'];
        $credi['deuda_consu']=$credi['sal_deuda_sc']+$credi['sal_deuda_cc'];
        $credi['otras_deudas']=0;
        $credi['monto_max_rdi']=floatval($credi['max_ende'])-floatval($credi['deuda_consu'])-floatval($credi['otras_deudas']);
        $credi['monto_max_apro']= number_format(min($credi['monto_max_rdi'],$credi['monto_max_rci']), 2, '.',''); 
        $credi['plazo_max_apro']=$credi['plazo_mas_ope'];
        $credi['tem']=$credi['tem_rci']*100;
        $credi['tea']=$teafija->tea;
        $segu = DB::table('seguro_desg')->where('estado',1)->first();
        $credi['desgravament'] = $segu->seg_des;
        return json_encode( $credi, true);
    }
    public function delista1()
    {
        $delista = Delista::all();
        return response()->json($delista);
    }
    public function calctcea1( Request $request){
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
            "com_desc_automatico"=>0,
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
                $array[$i]->saldo_capital=round($array[$i-1]->saldo_capital - $array[$i-1]->capital_amortizado,2);
                $array[$i]->seguro_degrav=$array[$i]->saldo_capital * $seguro;
                $fechavencimientoanterior = Date::PHPToExcel($array[$i-1]->fecha_vencimiento);
                $fechavencimientoactual = Date::PHPToExcel($array[$i]->fecha_vencimiento);
                $array[$i]->interes=$array[$i]->saldo_capital*(pow(1+$teanueva,($fechavencimientoactual - $fechavencimientoanterior)/360) -1);
                $array[$i]->capital_amortizado=round($array[$i]->subtotal_cuota - $array[$i]->interes, 2);
                $array[$i]->TCEA = $array[$i]->seguro_degrav + $array[$i]->com_desc_automatico + $array[$i]->subtotal_cuota;
        };

        foreach ($array as $item) {
            array_push($arrayTCEA, $item->TCEA);
        };

        $tcea = $this->irr($arrayTCEA);
        $result = $this->crono_intermedio($fechaPrincipal, $monto_financiado, $numero_cuotas,$tcea, $request->fecha_cuota,$teanueva, $seguro);
        // DD($array);
        // return response($result, 200);
        return json_encode($result);
        // return $result;

    }

}
