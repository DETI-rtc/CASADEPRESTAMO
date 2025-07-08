<?php

namespace App\Http\Controllers;

use App\Events\CreditoStatusChangedEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CuotaController;



use Illuminate\Http\Request;
// $carbon = new Carbon();
use Carbon;
use App\Models\Credito;
use App\Models\Pago;
use App\Models\Persona;
use App\Models\Tasainteres;
use App\Models\Relacionci;
use App\Models\Relaciondi;
use App\Models\Plazo;
use App\Models\Delista;
use App\Models\Empresa;
use App\Models\Cronograma;
use App\Models\Detacronograma;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Database\Eloquent\Collection;
use DB;
use \stdClass;



use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use \PhpOffice\PhpSpreadsheet\Shared\Date;
use \PhpOffice\PhpSpreadsheet\Calculation\Financial;
use \PhpOffice\PhpSpreadsheet\Style\NumberFormat;
// use \PhpOffice\PhpSpreadsheet\Calculation;

class CreditosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {

        // DD('SS');
        //DD($request->anio);
        $user = auth('sanctum')->user();
        $role = $user->getRoleNames();


        if ($role->first() == "Asesor") {
            $credi=Credito::where('idanalista',$user->id)->where('estado',1)->whereYear('created_at',$request->anio)->orderBy('numero', 'desc')->get();
            // $credi=DB::select("
            // select c.*, concat(p.nombres, ' ' ,p.paterno,' ', p.materno) as nombres
            // from credito c
            // inner join persona p on c.idpersona =p.id
            // where c.estado=1 and year(c.created_at) = $request->anio
            // order by numero");
            foreach ($credi as $c) {
                $c->persona = Persona::with('tmodalidad', 'tentidad', 'empresa', 'estadocivil')->where('id',$c->idpersona)->first();
                $c->responsable = 'N';
            }
            //  $credi->map(function($credi){
            //     $credi->responsable = 'N';
            // });
        }

        if ($role->first() == "Agente") {
            $credi=Credito::where('idanalista',$user->id)->orWhere('idsupervisor',$user->id)->where('estado',1)->whereYear('created_at',$request->anio)->orderBy('numero', 'desc')->get();
            // $credi=DB::select("
            // select c.*, concat(p.nombres, ' ' ,p.paterno,' ', p.materno) as nombres
            // from credito c
            // inner join persona p on c.idpersona =p.id
            // where c.estado=1 and year(c.created_at) = $request->anio or c.idsupervisor = $user->id
            // order by numero");
            foreach ($credi as $c) {
                $c->persona = Persona::with('tmodalidad', 'tentidad', 'empresa', 'estadocivil')->where('id',$c->idpersona)->first();
                // $c->nombres = $c->persona['paterno'] . ' ' . $c->persona['materno'] .' ' . $c->persona['nombres'];
                $c->nombres = $c->persona['nombres'] . ' ' . $c->persona['paterno'] .' ' . $c->persona['materno'];
                $c->responsable = 'S';
            }
            //  $credi->map(function($credi){
            //     $credi->responsable = 'N';
            // });
        }
        if ($role->first() == "Supervisor") {
        //    $credi=Credito::where('idsupervisor',$user->id)->where('estado',1)->whereYear('created_at',$request->anio)->orderBy('numero', 'desc')->get();
           $credi=Credito::where('estado',1)->whereYear('created_at',$request->anio)->orderBy('numero', 'desc')->get();
        //    $credi=DB::select("
        //     select c.*, concat(p.nombres, ' ' ,p.paterno,' ', p.materno) as nombres
        //     from credito c
        //     inner join persona p on c.idpersona =p.id
        //     where c.estado=1 and year(c.created_at) = $request->anio and c.idsupervisor = $user->id
        //     order by numero");
           
           foreach ($credi as $c) {
                $c->persona = Persona::with('tmodalidad', 'tentidad', 'empresa', 'estadocivil')->where('id',$c->idpersona)->first();
                //DD($c);
                if ($c->nivel_aproba == 2) {
                    $c->responsable = 'A';      
                }else{
                    $c->responsable = 'B';  
                } 
            }
        //    $credi->map(function($credi){
        //        if ($credi->nivel_aproba == 2) {
        //           $credi->responsable = 'A';      
        //         }else{
        //           $credi->responsable = 'B';  
        //         } 
        //     });
        }
        if ($role->first() == "Gerente" ) {
            $credi=Credito::where('estado',1)->whereYear('created_at',$request->anio)->orderBy('numero', 'desc')->get(); 
            // $credi=DB::select("
            // select c.*, concat(p.nombres, ' ' ,p.paterno,' ', p.materno) as nombres
            // from credito c
            // inner join persona p on c.idpersona =p.id
            // where c.estado=1 and year(c.created_at) = $request->anio
            // order by numero");
            foreach ($credi as $c) {
                $c->persona = Persona::with('tmodalidad', 'tentidad', 'empresa', 'estadocivil')->where('id',$c->idpersona)->first();
                if ($c->nivel_aproba == 3) {
                    $c->responsable = 'A';
                }else{
                    $c->responsable = 'B';
                }
            }
        //    $credi->map(function($credi){
        //         if ($credi->nivel_aproba == 3) {
        //           $credi->responsable = 'A';
        //         }else{
        //           $credi->responsable = 'B';
        //         }
        //    });  
        }
        if ( $role->first() == "Administrador"  ) {
           $credi=Credito::where('estado',1)->whereYear('created_at',$request->anio)->get(); 
        //    $credi=DB::select("
        //     select c.*, concat(p.nombres, ' ' ,p.paterno,' ', p.materno) as nombres
        //     from credito c
        //     inner join persona p on c.idpersona =p.id
        //     where c.estado=1 and year(c.created_at) = $request->anio
        //     order by numero");
           foreach ($credi as $c) {
                $c->persona = Persona::with('tmodalidad', 'tentidad', 'empresa', 'estadocivil')->where('id',$c->idpersona)->first();
                // if ($c->nivel_aproba == 4) {
                //     $c->responsable = 'A';
                // } else{
                //     $c->responsable = 'B';
                // }
            }
        }

        if ( $role->first() == "Tesorero"  ) {
            $credi=Credito::where('estado',1)->whereYear('created_at',$request->anio)->where('situacion_apro','A')->orderBy('numero', 'desc')->get(); 
            // $credi=DB::select("
            // select c.*, concat(p.nombres, ' ' ,p.paterno,' ', p.materno) as nombres
            // from credito c
            // inner join persona p on c.idpersona =p.id
            // where c.estado=1 and year(c.created_at) = $request->anio and situacion_apro='A'
            // order by numero");
            foreach ($credi as $c) {
                 $c->persona = Persona::with('tmodalidad', 'tentidad', 'empresa', 'estadocivil')->where('id',$c->idpersona)->first();
                 if ($c->nivel_aproba == 4) {
                     $c->responsable = 'A';
                 } else{
                     $c->responsable = 'B';
                 }
             }
         }
        
        return $credi;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Credito $credito)
    {
       //DD($request->detalle);
       // $messages = [
       //     'idpersona.required' => 'El necesita elegir al cliente',
      //      'idplazo.required' => 'el plazo es requerido',
       //     'idtaza_int.required' => 'La tasa es requerido',
       //     'idrdi.required' =>'El RDI es requerido',
       //     'idrci.required' =>'El RCI es requerido'

       // ];
       // $this->validate($request,[

       //     'idpersona' => 'required',
        //    'idplazo' => 'required',
        //    'idtasa_int' => 'required|integer',
        //    'idrdi' => 'required',
         //   'idrci' => 'required',


        //],$messages);
       //  'idpersona','meses_fal_cont','patrimonio','des_ley','ing_neto','idrci','ing_netodiscred',
       //  'sal_deuda_sc','sal_deuda_cc','deuda_sc','deuda_cc','sal_hipo','deuda_hipo','cuota_max_pres',
       //  'idplazo','plazo_mas_ope','primer_pago','idtasa_int','tem_rci','monto_max_rci','idrdi','max_ende',
       //  'deuda_consu','otras_deudas','monto_max_rdi','monto_max_apro','plazo_mas_apro','tem','fec_apro','situacion',
        try {
            DB::beginTransaction();

            $numero;
            if ($request->credi['reenganche'] == true) {
                $numero = $request->credi['numero'];
            } else {
                $corre = DB::table('correlativo')->where('abrev','C')->first();
                $numero = $corre->numero + 1;
                DB::table('correlativo')->where('abrev','C')->update(['numero' => $numero]);
            }

            // DD($corre->numero);
            $credi = new Credito;
            $credi->idpersona=$request->credi['idpersona'];
            $credi->meses_fal_cont=$request->credi['meses_fal_cont'];
            $credi->patrimonio=$request->credi['patrimonio'];
            $credi->des_ley=$request->credi['des_ley'];
            $credi->ing_neto=$request->credi['ing_neto'];
            $credi->idrci=$request->credi['idrci'];
            $credi->ing_netodiscred=$request->credi['ing_netodiscred'];
            $credi->sal_deuda_sc=$request->credi['sal_deuda_sc'];
            $credi->sal_deuda_cc=$request->credi['sal_deuda_cc'];
            $credi->deuda_cc=$request->credi['deuda_cc'];
            $credi->deuda_sc=$request->credi['deuda_sc'];
            $credi->sal_hipo=$request->credi['sal_hipo'];
            $credi->deuda_hipo=$request->credi['deuda_hipo'];
            $credi->cuota_max_pres=$request->credi['cuota_max_pres'];
            $credi->idplazo=$request->credi['idplazo'];
            $credi->plazo_mas_ope=$request->credi['plazo_mas_ope'];
            $credi->primer_pago=$request->credi['primer_pago'];
            $credi->idtasa_int=$request->credi['idtasa_int'];
            $credi->tem_rci=$request->credi['tem_rci'];
            $credi->tem=$request->credi['tem'];
            $credi->monto_max_rci=$request->credi['monto_max_rci'];
            $credi->idrdi=$request->credi['idrdi'];
            $credi->max_ende=$request->credi['max_ende'];
            $credi->deuda_consu=$request->credi['deuda_consu'];
            $credi->otras_deudas=$request->credi['otras_deudas'];
            $credi->monto_max_rdi=$request->credi['monto_max_rdi'];
            $credi->monto_max_apro=$request->credi['monto_max_apro'];
            $credi->estado = 1;
            $credi->tipo='CONVENIO';
            $credi->monto_credito = $request->crono['mon_ne_desem'];
            $credi->plazo_max_apro=$request->credi['plazo_max_apro'];
            $credi->plazo_credito = $request->crono['num_cuotas'];
            $credi->numero = $numero;
            $credi->repre_legal = 'Dr. Nuñez Solis Gumercindo Albino';
            $credi->dni_repre = 20012280;
            $credi->idanalista=auth('sanctum')->user()->id;
            $super = DB::table('nivel1_has_nivel2')->where('idnivel1',auth('sanctum')->user()->id)->where('estado',1)->first();
            $credi->idsupervisor=$super->idnivel2;
        
            if ($credi->monto_credito < 10001) {
                $credi->nivel_aproba = 1 ;
                $credi->situacion_apro='P';
            }
            // if (($credi->monto_credito > 5000) && ($credi->monto_credito < 10001)) {
            //     $credi->nivel_aproba = 2 ;
            //     $credi->situacion_apro='P';

            // }
            if (($credi->monto_credito > 10000) && ($credi->monto_credito < 20001) ) {
                $credi->nivel_aproba = 2 ;
                $credi->situacion_apro='P';
            }
            if ($credi->monto_credito > 20000) {
                $credi->nivel_aproba = 3 ;
                $credi->situacion_apro='P';
            }

            $credi->save();

            $crono = new Cronograma;
            $crono->iduser=$request->crono['iduser']; 
            $crono->idcliente=$request->credi['idpersona'];
            $crono->estado=$request->crono['estado'];
            $crono->idcredito=$credi->id;
            $crono->tasa_men_desgra=$request->crono['tasa_men_desgra'];
            $crono->com_desc_auto=$request->crono['com_desc_auto'];
            $crono->tasa_efe_anu=$request->crono['tea'];
            $crono->t_interes=$request->crono['t_interes'];
            $crono->num_cuotas=$request->crono['num_cuotas'];
            $crono->periocidad=$request->crono['periocidad'];
            $crono->f_ultima_cuota=$request->crono['f_ultima_cuota'];
            $crono->com_desembolso=$request->crono['com_desembolso'];
            $crono->mon_ne_desem=$request->crono['mon_ne_desem'];
            $crono->f_desembolso=$request->crono['f_desembolso'];
            $crono->mon_financiado=$request->crono['mon_financiado'];
            $crono->save();

            $detalles = $request->detalle;
            foreach ($detalles as $deta) {
                $detallecrono = new Detacronograma;
                $detallecrono->idcrono =$crono->id;
                $detallecrono->cuotanro = $deta['num_cuota'];
                $detallecrono->fecha_ven = $deta['fecha_vencimiento'];
                $detallecrono->sal_cap = $deta['saldo_capital'];
                $detallecrono->cap_amor = $deta['capital_amortizado'];
                $detallecrono->interes = $deta['interes'];
                $detallecrono->com_des = $deta['com_desc_automatico'];
                $detallecrono->seg_des = $deta['seguro_degrav'];
                $detallecrono->cuota = $deta['cuota'];
                //$detallecrono->diasnopago
                //$detallecrono->mora
                //$detallecrono->mon_pago
                //$detallecrono->sal_pen
                $detallecrono->estado = 0;
                $detallecrono->iduser = auth('sanctum')->user()->id;
                $detallecrono->save();
            }
            if ($credi->nivel_aproba > 1) {
                event(new CreditoStatusChangedEvent($credi));
            }
            //DD('SUCCESS');
            db::commit();
            return $credi;
        } catch (Exception $e) {
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
        $credito = Credito::find($id);
        $credito->fecha = date('d-m-Y');
        $credito->cronograma = Cronograma::where('idcredito',$id)->with('cuotas',function($query){
            $query->where('amortizado','0');
        })->first();
       
        $credito->cliente = Persona::where('id',$credito->cronograma['idcliente'])->first();

        // DD(json_decode($credito));
        // return $credito;
        // DD(json_decode($credito));
        return view('cronograma',['credito'=>$credito]);
        // DB::select("select dc.*
        // from detallecrono dc 
        // inner join cronograma c 
        // on dc.idcrono = c.id
        // inner join credito cr
        // on c.idcredito = cr.id
        // where cr.id = $id");
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
        $credi=Credito::findOrFail($id);

            $credi->update($request->all());

            return $credi;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $credi = Credito::findOrFail($id);
        // delete the user
        $credi->estado=0;
        $credi->save();

        return $credi;
    }
    public function buscarcliente (Request $request){
        $persona = Persona::with('tmodalidad', 'tentidad', 'empresa', 'estadocivil')->where('dni',$request->dni)->first();
        // $persona->with('empresa')->get();
        //$credi=[];
        // DD($persona);
        $edad = CarbonCarbon::parse($persona->fec_nac)->age;
        //DD($edad);
        $teafija = Tasainteres::where('identidad', $persona->tipoentidad)->where('idmodalidad',$persona->tipomodalidad)->where('estado',1)->first();
        $rci= Relacionci::where('identidad',$persona->tipoentidad)->where('idmodalidad',$persona->tipomodalidad)->get();

        // DD($rci, $persona->tipoentidad, $persona->tipomodalidad);
        // DD($persona->tipoentidad ,$persona->tipomodalidad );
        //DD($rci);
        $rdi= Relaciondi::where('identidad',$persona->tipoentidad)->where('idmodalidad',$persona->tipomodalidad)->get();
        $plazo = Plazo::where('identidad',$persona->tipoentidad)->where('idmodalidad',$persona->tipomodalidad)->get();
        $inte=Tasainteres::where('identidad',$persona->tipoentidad)->where('idmodalidad',$persona->tipomodalidad)->get();
        $credito=Credito::where('idpersona',$persona->id)->where('estado',1)->get();
        
        // $modalidad= $persona->tmodalidad->nomdelista;
        // $tipoentidad= $persona->tentidad->nomdelista;
        $estadocivil= Delista::where('id',$persona->idestadocivil)->get('nomdelista')->first();
        $empresa= Empresa::where('id',$persona->idempresa)->get('razonsocial')->first()->razonsocial;
        //DD($rci);
        $credi['idpersona']= $persona->id;
        $credi['credito']= $credito;
        $credi['cronograma']= [];
        $credi['ingre_bru']=$persona->ingre_bru;
        
        $credi['meses_fal_cont']= 0;
        $credi['patrimonio']='';
        $credi['des_ley'] = 0;
        $credi['ing_neto']= $persona->ingre_bru-$credi['des_ley'];
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
        $credi['plazo_mas_ope']=24;
        $credi['primer_pago']=30;
        $credi['idtasa_int']=$inte[0]->tea;
        $credi['tem_rci']=pow((1+($credi['idtasa_int']/100)), (1/12))-1;
        $credi['monto_max_rci']= ((1-(pow((1+$credi['tem_rci']),-24)))*$credi['cuota_max_pres'])/$credi['tem_rci'];
        $credi['idrdi']=$rdi[0]->rdi_max;
        $credi['max_ende']=$credi['ing_neto']*$credi['idrdi'];
        $credi['deuda_consu']=$credi['sal_deuda_sc']+$credi['sal_deuda_cc'];
        $credi['otras_deudas']=0;
        $credi['monto_max_rdi']=floatval($credi['max_ende'])-floatval($credi['deuda_consu'])-floatval($credi['otras_deudas']);
        $credi['monto_max_apro']=min($credi['monto_max_rdi'],$credi['monto_max_rci']);
        $credi['plazo_max_apro']=$credi['idplazo'];
        $credi['tea']=$teafija->tea;
        $persona['edad']=$edad;
        $credi['tem']=$credi['tem_rci']*100;
        $credi['desgravament'] = DB::table('seguro_desg')->where('estado',1)->first()->seg_des;
        
        
         
        return ['credi'=>$credi,'cliente'=>$persona];
    }
    
    public function calculomonto (Request $request){


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
        $credi['monto_max_apro']=min($credi['monto_max_rdi'],$credi['monto_max_rci']);
        $credi['plazo_max_apro']=$credi['plazo_mas_ope'];
        $credi['tem']=$credi['tem_rci']*100;
        $credi['tea']=$teafija->tea;
        $segu = DB::table('seguro_desg')->where('estado',1)->first();
        $credi['desgravament'] = $segu->seg_des;
        return ['credi'=>$credi];
    }
    public function creditospen(Request $request){
       // $this->authorize('isAdmin');
       $credi = DB::table('credito as c')
                ->join('persona as p','c.idpersona','=','p.id')
                ->join('cronograma as cr','c.id','cr.idcredito')
                ->select(db::raw("c.id, concat(p.dni,' - ', p.paterno ,' ', p.materno,' ', nombres,' - ',(select cuota from detallecrono where idcrono=cr.id limit 1 )) as pendientes,concat(p.paterno ,' ', p.materno,' ', nombres) as cliente, p.dni , p.id as idcliente, p.empresa"))
                ->where('estado_cred','A')
                ->where('situacion_apro','A')
                ->where('c.estado',1)
                ->get();
        return $credi; 

    }
    public function buscarcredito($id)
    {
       $credito = db::table('credito as c')->join('persona as p','c.idpersona','=','p.id')->select(db::raw("c.id as idcredito, concat(p.dni,' - ', p.paterno ,' ', p.materno,' ', nombres) as pendientes, p.dni , p.id as idcliente, p.empresa"))->where('p.dni',$id)->where('estado_cred','A')->where('c.estado',1)->first();
       //DD($credito);
       if (!empty($credito)) {
        $cuotas = DB::table('cronograma as c')->join('detallecrono as d','c.id','=','d.idcrono')->select(DB::raw("d.id as idcrono,d.fecha_ven,cuotanro,cuota, concat(d.fecha_ven,' - ',cuotanro)as cuotas"))->where('d.estado',0)->where('idcredito',$credito->idcredito)->get();
        // DD($cuotas);
       }else{
        $cuotas='';
        $credito='';
       }       
      return ['creditos'=>$credito,'cuotas'=>$cuotas];
  

    }
    public function aprobarcredito($id){

        $user = auth('sanctum')->user();
        $role = $user->getRoleNames();
        $credito = Credito::findOrFail($id);
        if ($role->first() == "Supervisor") {
            $credito->id_apro_dos = auth('sanctum')->user()->id;
        }
        if ($role->first() == "Gerente") {
            $credito->id_apro_tres = auth('sanctum')->user()->id;
        }
        $credito->fec_aproba_dos = date("Y-m-d");
        $credito->situacion_apro = 'A';
        $credito->save();
        foreach ($user->unreadNotifications as $notification) {
                
                if ($notification->data['credito'] == $credito->id){
                    $notification->markAsRead();
                }
                
        }
        return $credito;
    }

    public function actualizarcredito(Request $request, $id){
        // DD($req, $id);
        // DD($request->credi['idpersona']);
        try {
            DB::beginTransaction();
            $res=Credito::where('id',$id)->delete();
            $credi = new Credito;
            $credi->idpersona       = $request->credi['idpersona'];
            $credi->des_ley         = $request->credi['des_ley'];
            $credi->ing_neto        = $request->credi['ing_neto'];
            $credi->idrci           = $request->credi['idrci'];
            $credi->ing_netodiscred = $request->credi['ing_netodiscred'];
            $credi->sal_deuda_sc    = $request->credi['sal_deuda_sc'];
            $credi->sal_deuda_cc    = $request->credi['sal_deuda_cc'];
            $credi->deuda_cc        = $request->credi['deuda_cc'];
            $credi->deuda_sc        = $request->credi['deuda_sc'];
            $credi->sal_hipo        = $request->credi['sal_hipo'];
            $credi->deuda_hipo      = $request->credi['deuda_hipo'];
            $credi->cuota_max_pres  = $request->credi['cuota_max_pres'];
            $credi->idplazo         = $request->credi['idplazo'];
            $credi->plazo_mas_ope   = $request->credi['plazo_mas_ope'];
            $credi->primer_pago     = $request->credi['primer_pago'];
            $credi->idtasa_int      = $request->credi['idtasa_int'];
            $credi->tem_rci         = $request->credi['tem_rci'];
            $credi->tem             = $request->credi['tem'];
            $credi->monto_max_rci   = $request->credi['monto_max_rci'];
            $credi->idrdi           = $request->credi['idrdi'];
            $credi->max_ende        = $request->credi['max_ende'];
            $credi->deuda_consu     = $request->credi['deuda_consu'];
            $credi->otras_deudas    = $request->credi['otras_deudas'];
            $credi->monto_max_rdi   = $request->credi['monto_max_rdi'];
            $credi->monto_max_apro  = $request->credi['monto_max_apro'];
            $credi->estado          =  1;
            $credi->tipo            = 'CONVENIO';
            $credi->plazo_max_apro  = $request->credi['plazo_max_apro'];
            $credi->idanalista      = auth('sanctum')->user()->id;
            $super                  =  DB::table('nivel1_has_nivel2')->where('idnivel1',auth('sanctum')->user()->id)->where('estado',1)->first();
            $credi->idsupervisor    = $super->idnivel2;
            $credi->monto_credito   =  $request->crono['mon_ne_desem'];
            $credi->plazo_credito   =  $request->crono['num_cuotas'];
            $credi->meses_fal_cont  = $request->credi['meses_fal_cont'];
            $credi->patrimonio      = $request->credi['patrimonio'];

            
        
            if ($credi->monto_credito < 5001) {
                $credi->nivel_aproba = 1 ;
                $credi->situacion_apro='A';
            }
            if (($credi->monto_credito > 5000) && ($credi->monto_credito < 10001)) {
                $credi->nivel_aproba = 2 ;
                $credi->situacion_apro='P';

            }
            if (($credi->monto_credito > 10000) && ($credi->monto_credito < 20001) ) {
                $credi->nivel_aproba = 3 ;
                $credi->situacion_apro='P';
            }
            if ($credi->monto_credito > 20000) {
                $credi->nivel_aproba = 4 ;
                $credi->situacion_apro='P';
            }

            $credi->save();

            $res=Cronograma::where('id',$request->crono['id'])->delete();
            $crono = new Cronograma;
            $crono->iduser          = $request->crono['iduser']; 
            $crono->idcliente       = $request->credi['idpersona'];
            $crono->estado          = $request->crono['estado'];
            $crono->idcredito       = $credi->id;
            $crono->tasa_men_desgra = $request->crono['tasa_men_desgra'];
            $crono->com_desc_auto   = $request->crono['com_desc_auto'];
            $crono->tasa_efe_anu    = $request->crono['tea'];
            $crono->t_interes       = $request->crono['t_interes'];
            $crono->num_cuotas      = $request->crono['num_cuotas'];
            $crono->periocidad      = $request->crono['periocidad'];
            $crono->f_ultima_cuota  = date("Y-m-d", strtotime($request->crono['f_ultima_cuota']));
            $crono->com_desembolso  = $request->crono['com_desembolso'];
            $crono->mon_ne_desem    = $request->crono['mon_ne_desem'];
            $crono->f_desembolso    = $request->crono['f_desembolso'];
            $crono->mon_financiado  = $request->crono['mon_financiado'];
            $crono->save();


            $res=Detacronograma::where('idcrono',$request->crono['id'])->delete();
            $detalles = $request->detalle;
            foreach ($detalles as $deta) {
                $detallecrono = new Detacronograma;
                $detallecrono->idcrono      =$crono->id;
                $detallecrono->cuotanro     = $deta['num_cuota'];
                $detallecrono->fecha_ven    = $deta['fecha_vencimiento'];
                $detallecrono->sal_cap      = $deta['saldo_capital'];
                $detallecrono->cap_amor     = $deta['capital_amortizado'];
                $detallecrono->interes      = $deta['interes'];
                $detallecrono->com_des      = $deta['com_desc_automatico'];
                $detallecrono->seg_des      = $deta['seguro_degrav'];
                $detallecrono->cuota        = $deta['cuota'];
                $detallecrono->estado       = 0;
                $detallecrono->iduser       = auth('sanctum')->user()->id;
                $detallecrono->save();
            }
            if ($credi->nivel_aproba > 1) {
                event(new CreditoStatusChangedEvent($credi));
            }
            // DD('SUCCESS');
            db::commit();
            return $credi;
        } catch (Exception $e) {
            DB::rollback();
        }
    }
    

    public function addcp( Request $request){

        $nuemor = DB::table('credito')->where('id', $request->idcredito)->update(['cp_desembolso' => $request->nrocp]);
        $nuemor1 = DB::table('credito')->where('id', $request->idcredito)->update(['fecha_des' => $request->fecha]);

        return $nuemor1;
    }

    public function credwithanalista(Request $request){
        // DD('esta entrando');
        // $anio = date('Y');
        // $credi=Credito::where('estado',1)->whereYear('created_at',$request->anio)->get();
        $credi = DB::select("select concat(p.nombres,' ', p.paterno,' ' ,p.materno) as cliente, 
        (select min(puntaje) from pre_det_calificacion where idcredito = c.id) as calificado,
        concat(u.nombres,' ',u.apellidos) as analista,
        e.razonsocial as miempresa,
        p.celular,
        c.*,
        year(c.created_at) as anio
        from credito c 
        inner join persona p 
        on c.idpersona = p.id
        inner join  users u 
        on c.idanalista =u.id
        inner join empresa e 
        on p.idempresa = e.id  
        where year(c.created_at)=$request->anio
        and fecha_des IS NOT NULL
        ");
        return $credi;
    }

    public function controlVentasCredito(Request $request){
        $collect = new Collection();
        $data = DB::select("CALL report_control_ventas_credito($request->mes,$request->anio)");
        $collect = $data;
        $cant_total_aprobadas = 0;
        $cant_total_desembolsadas = 0;
        $cant_total_pendientes = 0;
        $total_aprobadas = 0;
        $total_desembolsadas = 0;
        $total_pendientes = 0;

        foreach ($data as $i) {
            $cant_total_aprobadas = $cant_total_aprobadas + $i->cant_total_aprobadas;
            $cant_total_desembolsadas = $cant_total_desembolsadas + $i->cant_total_desembolsadas;
            $cant_total_pendientes = $cant_total_pendientes + $i->cant_total_pendientes;
            $total_aprobadas = $total_aprobadas + $i->total_aprobadas;
            $total_desembolsadas = $total_desembolsadas + $i->total_desembolsadas;
            $total_pendientes = $total_pendientes + $i->total_pendientes;

        }
        $item['cant_total_aprobadas'] = $cant_total_aprobadas;
        // DD($cant_total_aprobadas);
        $item['cant_total_desembolsadas'] = $cant_total_desembolsadas;
        $item['cant_total_pendientes'] = $cant_total_pendientes;
        $item['total_aprobadas'] = $total_aprobadas;
        $item['total_desembolsadas'] = $total_desembolsadas;
        $item['total_pendientes'] = $total_pendientes;

        $query = "select concat(u.nombres,' ',u.apellidos) as nombres, r.name, m.m$request->mes as meta
        from meta_usu_mes m
        inner join users u on m.idpersona = u.id 
        inner join model_has_roles mo on u.id = mo.model_id
        inner join roles r on mo.role_id = r.id
        where idpersona = 3
        and periodo=$request->anio";

        
        $superv = DB::select($query);

        $item['meta'] = $superv[0]->meta;
        $item['name'] = $superv[0]->name;
        $item['nombres'] = $superv[0]->nombres;
        

        $respon['analistas'] = $data;
        $respon['supervisor'] = $item;

        // $collect = $data;

        return $respon;
    }

    public function getNumReenganche(){
        $data = DB::select('select numero from credito where numero is not null group by numero');
        return $data;
    }


    public function getClientesDeudores(){
        $data =DB::select("select c.id as idcredito, p.*, CONCAT(p.nombres,' ', p.paterno, ' ', p.materno ) as cliente 
        from credito c
        inner join persona p on c.idpersona = p.id
        where situacion_apro = 'A' and estado_cred = 'A'");

        return $data;
    }

    public function getCuotasPendientesForIdCredito($id){
        // DD($id);
        $data = DB::select("
            select c.id as idcredito, cr.id as idcronograma, d.*, DATE_FORMAT(d.fecha_ven, '%d-%m-%Y') as fecha_venci
            from credito c
            inner join cronograma cr on c.id = cr.idcredito
            inner join detallecrono d on cr.id = d.idcrono 
            where situacion_apro = 'A' and estado_cred = 'A' 
            and d.situacion ='P' 
            and idcredito =$id
        ");

        return $data;
    }

    public function amortizar(Request $request){
        if ($request->tipo_amortizacion == 'p') {
            // DD($request->tipo_amortizacion);
            $data  = $this->amortizarplazo($request);
            return $data;
        } else {
            $data = $this->amortizarCuota($request);
            return $data;
        }
    }

    public function amortizarplazo($request){

        $primera_couta = $request->cuotaspendientes[0];
        $monto_restante_amortizado =  floatval(
            $primera_couta['sal_cap'] + 
            $primera_couta['interes'] + 
            $primera_couta['com_des'] +
            $primera_couta['seg_des'] ) - floatval($request->montoAmortizar);
        $cuota_prox;
        foreach ($request->cuotaspendientes as $c) {
            $resta = $monto_restante_amortizado - floatval($c['sal_cap']);
            if ($resta > 0) {
                $cuota_prox = $c;
                break;
            }
        }
        // DD($cuota_prox);
        $crono = Cronograma::where('idcredito', $request->idcredito)->firstOrFail();
        if ($request->cantcuotas == null || $request->cantcuotas == '' || $request->cantcuotas == 0) {
            $cuotas_restantes = $crono->num_cuotas - $cuota_prox['cuotanro'];
        } else {
            $cuotas_restantes = $request->cantcuotas;
        }
        
        // DD($monto_restante_amortizado, $cuota_prox);
        $send = new stdClass();
        $send->comision_descuento= floatval($crono->com_desc_auto);
        $send->desgravament= floatval($crono->tasa_men_desgra);
        $send->fecha= $request->fecha_desembolso;
        $send->fecha_cuota= $request->fecha_primera_cuota;
        $send->montofinanciado= $monto_restante_amortizado;
        $send->rows= $cuotas_restantes;
        $send->tea= floatval($crono->tasa_efe_anu);
        // DD($send);
        
        $cuota = new CuotaController();
        $resp = $cuota->calctceanohttp($send);
        // json_decode($resp);
        $retur = new stdClass();
        $retur->calc = json_decode($resp);
        $retur->idcrono = $crono->id;
        return $retur;
        // DD($resp);

        // $cuotas = new Collection();

        // $primera_couta = $request->cuotaspendientes[0];
        // $fecha_operacion = '12-07-2021';
        // $credito = Credito::where('id', $request->idcredito)->firstOrFail();
        // $cronograma = Cronograma::where('idcredito', $credito->id)->firstOrFail();
        // $TND = ((pow((1+($cronograma->tasa_efe_anu/100)),(1/12) ) -1)*12*365/360)/365;
        // $cant_coutas_pendientes = count($request->cuotaspendientes);
        // $ultima_cuota;

        // $monto_restante_amortizado =  $primera_couta['sal_cap'] - $request->montoAmortizar;
        // $calc['couta']=$primera_couta['cuota']; 
        // $calc['interes']=$monto_restante_amortizado*$TND*( Date::PHPToExcel($primera_couta['fecha_venci']) - Date::PHPToExcel($fecha_operacion)); 
        // $calc['amortizacion']=$calc['couta'] - $calc['interes']; 
        // $calc['saldo']=$monto_restante_amortizado - $calc['amortizacion']; 

        // $e['idcredito'] =  0;
        // $e['id'] =  0;
        // $e['idcronograma'] = 0;
        // $e['idcrono'] =  0;
        // $e['cuotanro'] =  0;
        // $e['fecha_ven'] =  $fecha_operacion;
        // $e['sal_cap'] =  $monto_restante_amortizado;
        // $e['cap_amor'] =  0;
        // $e['interes'] =  0;
        // $e['com_des'] =  0;
        // $e['seg_des'] =  0;
        // $e['cuota'] =  0;
        // $e['diasnopago'] =  null;
        // $e['mora'] =  null;
        // $e['situacion'] =  "P";
        // $e['mon_pag'] =  null;
        // $e['sal_pen'] =  null;
        // $e['estado'] =  0;
        // $e['iduser'] =  6;
        // $e['atrasado'] =  "0";
        // $e['fecha_venci'] =  $fecha_operacion;

        // $cuotas->push($e);

        // for ($i=0; $i < $cant_coutas_pendientes; $i++) { 
        //     $create['id'] = $request->cuotaspendientes[$i]['id'];
        //     $create['idcredito'] = $request->cuotaspendientes[$i]['idcredito'];
        //     $create['idcronograma'] = $request->cuotaspendientes[$i]['idcronograma'];
        //     $create['idcrono'] = $request->cuotaspendientes[$i]['idcrono'];
        //     $create['cuotanro'] = $request->cuotaspendientes[$i]['cuotanro'];
        //     $create['fecha_ven'] = $request->cuotaspendientes[$i]['fecha_ven'];
        //     $create['interes'] = $cuotas[$i]['sal_cap']* $TND *( Date::PHPToExcel($request->cuotaspendientes[$i]['fecha_venci']) - Date::PHPToExcel($cuotas[$i]['fecha_venci']));
        //     $create['cuota'] = $request->cuotaspendientes[$i]['cuota'];
        //     $create['cap_amor'] = $create['cuota'] - $create['interes'];
        //     $create['sal_cap'] = $cuotas[$i]['sal_cap'] - $create['cap_amor'];
        //     $create['com_des'] = $request->cuotaspendientes[$i]['com_des'];
        //     $create['seg_des'] = $create['sal_cap'] * $cronograma->tasa_men_desgra;
        //     $create['diasnopago'] = $request->cuotaspendientes[$i]['diasnopago'];
        //     $create['mora'] = $request->cuotaspendientes[$i]['mora'];
        //     $create['situacion'] = $request->cuotaspendientes[$i]['situacion'];
        //     $create['mon_pag'] = $request->cuotaspendientes[$i]['mon_pag'];
        //     $create['sal_pen'] = $request->cuotaspendientes[$i]['sal_pen'];
        //     $create['estado'] = $request->cuotaspendientes[$i]['estado'];
        //     $create['iduser'] = $request->cuotaspendientes[$i]['iduser'];
        //     $create['atrasado'] = $request->cuotaspendientes[$i]['cuotanro'];
        //     $create['fecha_venci'] = $request->cuotaspendientes[$i]['fecha_venci'];


        //     if ($create['sal_cap']>0) {
        //         $cuotas->push($create);
        //     } else {
        //         $ultima_cuota = $create['cuotanro'];
        //         $ultima_c['id'] = $request->cuotaspendientes[$i]['id'];
        //         $ultima_c['idcredito'] = $request->cuotaspendientes[$i]['idcredito'];
        //         $ultima_c['idcronograma'] = $request->cuotaspendientes[$i]['idcronograma'];
        //         $ultima_c['idcrono'] = $request->cuotaspendientes[$i]['idcrono'];
        //         $ultima_c['cuotanro'] = $request->cuotaspendientes[$i]['cuotanro'];
        //         $ultima_c['fecha_ven'] = $request->cuotaspendientes[$i]['fecha_ven'];
        //         $ultima_c['interes'] = $cuotas[$i]['sal_cap']* $TND *( Date::PHPToExcel($request->cuotaspendientes[$i]['fecha_venci']) - Date::PHPToExcel($cuotas[$i]['fecha_venci']));
        //         $ultima_c['cap_amor'] = $cuotas[$i]['sal_cap'];
        //         $ultima_c['sal_cap'] = 0;
        //         $ultima_c['com_des'] = $request->cuotaspendientes[$i]['com_des'];
        //         $ultima_c['seg_des'] = $ultima_c['sal_cap'] * $cronograma->tasa_men_desgra;
        //         $ultima_c['cuota'] = $cuotas[$i]['sal_cap'] + $ultima_c['interes'];
        //         $ultima_c['diasnopago'] = $request->cuotaspendientes[$i]['diasnopago'];
        //         $ultima_c['mora'] = $request->cuotaspendientes[$i]['mora'];
        //         $ultima_c['situacion'] = $request->cuotaspendientes[$i]['situacion'];
        //         $ultima_c['mon_pag'] = $request->cuotaspendientes[$i]['mon_pag'];
        //         $ultima_c['sal_pen'] = $request->cuotaspendientes[$i]['sal_pen'];
        //         $ultima_c['estado'] = $request->cuotaspendientes[$i]['estado'];
        //         $ultima_c['iduser'] = $request->cuotaspendientes[$i]['iduser'];
        //         $ultima_c['atrasado'] = $request->cuotaspendientes[$i]['cuotanro'];
        //         $ultima_c['fecha_venci'] = $request->cuotaspendientes[$i]['fecha_venci'];
        //         $cuotas->push($ultima_c);
        //         break;
        //     }
        // }

        // return $cuotas;
    }

    public function amortizarCuota($request){
        $credito = Credito::findOrFail($request->idcredito);
        $cronograma = Cronograma::where('id', $request->cuotaspendientes[0]['idcronograma'])->first();
        $fecha_operacion = '2021-07-12';
        $monto_restante_amortizado =  $request->cuotaspendientes[0]['sal_cap'] - $request->montoAmortizar;
        $cant_coutas_pendientes = count($request->cuotaspendientes);
        // dd($cronograma);
        $cuotaController = new CuotaController();
        $i = new \stdClass();
        $i->comision_descuento = $cronograma->com_desc_auto;
        $i->desgravament = $cronograma->tasa_men_desgra;
        $i->fecha = $fecha_operacion;
        $i->fecha_cuota =$request->cuotaspendientes[0]['fecha_ven'];
        $i->montofinanciado = $monto_restante_amortizado;
        $i->rows = $cant_coutas_pendientes;
        $i->tea = $cronograma->tasa_efe_anu;
        $cuotas = new Collection();
        // DD($i);

        // $crono= new \stdClass();
        $crono = $cuotaController->calcularNuevoCrono($i);
        $crono = json_decode($crono);
        foreach ($crono->cuotas as $c ) {
            $c->idcredito =  $request->idcredito;
            $c->idcronograma = $request->cuotaspendientes[0]['idcronograma'];
            $c->idcrono =  $request->cuotaspendientes[0]['idcronograma'];
            $c->cuotanro =  $c->num_cuota;
            $c->fecha_ven =  $c->fecha_vencimiento;
            $c->sal_cap =  $c->saldo_capital;
            $c->cap_amor =  $c->capital_amortizado;
            $c->interes =  $c->interes;
            $c->com_des =  $c->com_desc_automatico;
            $c->seg_des =  $c->seguro_degrav;
            $c->cuota =  $c->cuota;
            $c->diasnopago =  null;
            $c->mora =  null;
            $c->situacion =  "P";
            $c->mon_pag =  null;
            $c->sal_pen =  null;
            $c->estado =  0;
            $c->iduser =  6;
            $c->atrasado =  "0";
            $c->fecha_venci =  $c->fecha_vencimiento;


            // capital_amortizado: 0
            // com_desc_automatico: 5
            // cuota: -2995.08
            // fecha_vencimiento: "2021-07-12"
            // interes: 0
            // num_cuota: 0
            // saldo_capital: 2995.08
            // seguro_degrav: 0
        }
        return $crono->cuotas;
    }

//GUARDAR
    public function guardarAmortizacion(Request $request){
        // DD($request->cuotaspendientes[0]['sal_cap']);
        try {
            DB::beginTransaction();
            Detacronograma::where('idcrono', $request->idcrono)->where('estado',0)->delete();
            $cuotas = [];
            foreach ($request->cuotas as $c) {
                if ($c['num_cuota'] == 0) {
                    // $cuota['idcrono'] = $c['idcrono'];
                    // $cuota['cuotanro'] = $c['cuotanro'];
                    // $cuota['fecha_ven'] = $c['fecha_ven'];
                    // $cuota['interes'] = $c['interes'];
                    // $cuota['cap_amor'] = $c['cap_amor'];
                    // $cuota['sal_cap'] = $c['sal_cap'];
                    // $cuota['com_des'] = $c['com_des'];
                    // $cuota['seg_des'] = $c['seg_des'];
                    // $cuota['cuota'] = $c['cuota'];
                    // $cuota['diasnopago'] = $c['diasnopago'];
                    // $cuota['mora'] = $c['mora'];
                    // $cuota['situacion'] = $c['situacion'];
                    // $cuota['mon_pag'] = $c['mon_pag'];
                    // $cuota['sal_pen'] = $c['sal_pen'];
                    // $cuota['estado'] = $c['estado'];
                    // $cuota['iduser'] = $c['iduser'];
                    // $cuota['atrasado'] = $c['atrasado'];

                    $cuota['idcrono'] = $request->idcrono;
                    $cuota['cuotanro'] = 0;
                    $cuota['fecha_ven'] = $c['fecha_vencimiento'];
                    $cuota['interes'] = 0;
                    $cuota['cap_amor'] = $request->montoamortizar;
                    $cuota['sal_cap'] =  $request->cuotaspendientes[0]['sal_cap'];
                    $cuota['com_des'] =  0;
                    $cuota['seg_des'] =  0;
                    $cuota['cuota'] =    $request->montoamortizar;
                    $cuota['situacion'] = 'P';
                    $cuota['amortizado'] = 0;
                    $cuota['estado'] = 0;
                    $cuota['iduser'] = auth('sanctum')->user()->id;
                    $cuota['atrasado'] = 0;
                    $cuotas[] = $cuota;


                    
                } else {
                    $cuota['idcrono'] = $request->idcrono;
                    $cuota['cuotanro'] = $c['num_cuota'];
                    $cuota['fecha_ven'] = $c['fecha_vencimiento'];
                    $cuota['interes'] = $c['interes'];
                    $cuota['cap_amor'] = $c['capital_amortizado'];
                    $cuota['sal_cap'] = $c['saldo_capital'];
                    $cuota['com_des'] = $c['com_desc_automatico'];
                    $cuota['seg_des'] = $c['seguro_degrav'];
                    $cuota['cuota'] = $c['cuota'];

                    $cuota['situacion'] = 'P';
                    $cuota['estado'] = 0;
                    $cuota['iduser'] = auth('sanctum')->user()->id;
                    $cuota['atrasado'] = 0;
                    $cuotas[] = $cuota;
                }
            }

            // $credito = Credito::where('id', $request->idcredito)->first();
            // $cliente = Persona::find($credito->idpersona);

            // $pago = new Pago;
            // $pago->idcliente=$cliente->id;
            // $pago->dni=$cliente->dni;
            // $cliente1 = DB::table('persona')->select(db::raw('concat(paterno," ",materno," ",nombres) as nombre'))->where('id',$credito->idpersona)->first();
            // $pago->nom_cliente =  $cliente1->nombre;
            // $pago->fecha_pago=$request->fecha_pago;
            // $pago->nro_cuota=0;
            // $pago->idcredito=$request->idcredito;
            // $pago->monto=$request->montoamortizar;
            // $pago->monto_desc=$request->montoamortizar;
            // $pago->monto_efectivo=0;
            // $pago->fec_pago_efec=$request->fecha_pago;
            // $pago->saldo=0;
            // $pago->com_pago=0;
            // $pago->dias_pos=0;
            // $pago->cap_amor=0;
            // $pago->interes=0;
            // $pago->com_des=0;
            // $pago->seg_des=0;
            // $pago->comentario=$request->justificacion;
            // $pago->iduser=auth('sanctum')->user()->id;
            // $pago->save();
            Detacronograma::insert($cuotas);
            // dd('SATISFACTORIO');
            db::commit();
            return 'Satisfactorio';
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
        }

    }
    public function cuotasnopagas(Request $request){
        if ($request->anio=='') {
            $anio=date("Y");
        }else{
            $anio=$request->anio;
        }

        if ($request->mes == '') {
            $mes=1;
        }else{
            switch ($request->mes) {
                case 1:
                    $fecha= $anio.'-01-31';
                    break;
                case 2:
                    $fecha= $anio.'-02-29';
                    break;
                case 3:
                    $fecha= $anio.'-03-31';
                    break;
                case 4:
                    $fecha= $anio.'-04-30';
                    break;
                case 5:
                    $fecha= $anio.'-05-31';
                    break;
                case 6:
                    $fecha= $anio.'-06-30';
                    break;
                case 7:
                    $fecha= $anio.'-07-31';
                    break;
                case 8:
                    $fecha= $anio.'-08-31';
                    break;
                case 9:
                    $fecha= $anio.'-09-30';
                    break;
                case 10:
                    $fecha= $anio.'-10-31';
                    break;
                case 11:
                    $fecha= $anio.'-11-30';
                    break;
                case 12:
                    $fecha= $anio.'-12-31';
                    break;
                default:
                    # code...
                    break;
            }
            
        }
        //DD($fecha);
        //$cuotas =DB::select("select c.numero, CONCAT(p.paterno,' ',p.materno,' ',p.nombres) as cliente ,p.empresa, d.fecha_ven,d.cuotanro, d.cuota, d.interes,d.com_des,d.seg_des,c.monto_credito from persona as p inner join credito as c on p.id=c.idpersona INNER JOIN cronograma as cro on c.id=cro.idcredito INNER JOIN  detallecrono as d on cro.id=d.idcrono where year(fecha_ven) = $request->anio and MONTH(fecha_ven) <= $mes and  situacion='P' and c.estado=1");
        $cuotas =DB::select("SELECT d.id as iddetalle, cre.id,p.nom_comp,empresa,d.cuotanro, fecha_ven,cuota,mon_pag,saldo_ade, d.observa from detallecrono as d inner join cronograma as c on d.idcrono=c.id inner join credito as cre on c.idcredito=cre.id inner join persona as p on cre.idpersona=p.id where fecha_ven <= '".$fecha."' and situacion ='P' and cre.estado = 1 ORDER BY nom_comp,fecha_ven");
        //DD($cuotas);
        return $cuotas ;
    }
    public function guardarobserva(Request $request){
       // DD($request->params['datos']);
        foreach ($request->params['datos'] as $c) {
            DB::table('detallecrono')->where('id', $c['iddetalle'])->update(['observa' => $c['observa']]);
         //update
        }

        return $request->params['datos'];

    }
    
    public function guardarreporte(Request $request){
       // DD($request);
       $nro=count($request->datos);
       $de = DB::table('reporte_save')->insert([                
        'fecha' => $request->fecha,
        'nro' => $nro]);
        $userId = DB::getPdo()->lastInsertId();
        //DD($userId);
         foreach ($request->datos as $c) {
          
          //update
            DB::table('reporte_fecha')->insert([                
                'adelanto' => $c['adelanto'],
                'amor' => $c['amor'],
                'amortiza' => $c['amortiza'],
                'cant_cuotas_res' => $c['cant_cuotas_res'],
                'com_res' => $c['com_res'],
                'cuota' => $c['cuota'],
                'int_res' => $c['int_res'],
                'interes_cancelado' => $c['interes_cancelado'],
                'interes_pagado' => $c['interes_pagado'],
                'monto_cancelado' => $c['monto_cancelado'],
                'monto_financiado' => $c['monto_financiado'],
                'monto_res' => $c['monto_res'],
                'n_credito' => $c['n_credito'],
                'n_cuotas_pagado' => $c['n_cuotas_pagado'],
                'nocobro' => $c['nocobro'],
                'nombres' => $c['nombres'],
                'nreal' => $c['nreal'],
                'por_cobrar' => $c['por_cobrar'],
                'redo' => $c['redo'],
                'redondeo' => $c['redondeo'],
                'sal_res' => $c['sal_res'],
                'seg_res' => $c['seg_res'],
                'total' => $c['total'],
                'total_cuotas' => $c['total_cuotas'],
                'total_deuda' => $c['total_deuda'],
                'total_pagado' => $c['total_pagado'],
                'idrepor' => $userId,
                'fecha_repo' => $request->fecha
            ]);
         }
 
         return $request->datos;
 
     }
     
}
