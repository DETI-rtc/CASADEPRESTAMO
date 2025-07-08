<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Models\DetalleHojaruta;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Database\Eloquent\Collection;
//use GuzzleHttp\Client;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function datosinicioteso(Request $request){         
        
        $dato = Credito::where('estado',1)->whereYear('created_at',$request->ano)->get();
        $nrocredito = $dato->count();
        $mtocredito = $dato->sum('monto_credito'); 
        $pago =  Pago::all();      
        $nrocliente = $pago->sum('monto');
        $nroactividad = $dato->where('desembolso','S')->count();
        $nrocredcan = $dato->where('estado_cred','C')->count();
        // DD($dato->sum('monto_credito'));
        return ['nrocredito'=> $nrocredito,'mtocredito' => $mtocredito , 'nrocliente'=>$nrocliente,'nroactividad'=>$nroactividad,'nrocredcan'=>$nrocredcan];
        // $dato1 = Creditos::where('idanalista',auth->('sanctum')->user->id())->first();
        // $dato1 = Creditos::where('idanalista',auth->('sanctum')->user->id())->first();
        //$dato1 = Creditos::where('idanalista',auth->('sanctum')->user->id())->first();
    }
    public function datosinicio(Request $request){           
        $dato = Credito::where('idanalista',auth('sanctum')->user()->id)->whereYear('created_at',$request->ano)->where('estado',1)->get();
        $nrocredito = $dato->count();
        $mtocredito = $dato->sum('monto_credito');
        $nrocliente = $dato->count('idpersona');
        $mtodese = $dato->where('desembolso','S')->sum('monto_credito');  
        $nrodese = $dato->where('desembolso','S')->count('monto_credito');  
        $nroactividad = DetalleHojaruta::where('idanalista',auth('sanctum')->user()->id)->count();
        // DD($dato->sum('monto_credito'));
        return ['nrocredito'=> $nrocredito,'mtocredito' => $mtocredito , 'nrocliente'=>$nrocliente,'nroactividad'=>$nroactividad,'mtodese'=>$mtodese,'nrodese'=>$nrodese];
        // $dato1 = Creditos::where('idanalista',auth->('sanctum')->user->id())->first();
        // $dato1 = Creditos::where('idanalista',auth->('sanctum')->user->id())->first();
        //$dato1 = Creditos::where('idanalista',auth->('sanctum')->user->id())->first();
    }
    public function datosinicioger(Request $request){           
        $dato = Credito::where('estado',1)->whereYear('created_at',$request->ano)->get();
        $nrocredito = $dato->count();
        $mtocredito = $dato->sum('monto_credito');
        $user = DB::table('users')->select('users.id','users.name','users.nombres','users.apellidos','users.dni','users.avatar','users.email','roles.name as rol')
       ->join('model_has_roles','users.id','=','model_id')
       ->join('roles','role_id','=','roles.id')       
       ->get();

        $nrosuper = $user->where('rol','Supervisor')->count();
        $nroagen = $user->where('rol','Agente')->count();
        $mtopago = Pago::where('estado',1)->sum('monto');
       // DD($dato->sum('monto_credito'));
         return ['nrocredito'=> $nrocredito,'mtocredito' => $mtocredito, 'nrosuper' => $nrosuper,'nroagen' => $nroagen, 'mtopago' => $mtopago ];
       // $dato1 = Creditos::where('idanalista',auth->('sanctum')->user->id())->first();
       // $dato1 = Creditos::where('idanalista',auth->('sanctum')->user->id())->first();
        //$dato1 = Creditos::where('idanalista',auth->('sanctum')->user->id())->first();
    }
     public function datosiniciosuper(Request $request){           
        $dato = Credito::where('estado',1)->where('idsupervisor',auth('sanctum')->user()->id)->whereYear('created_at',$request->ano)->get();
        $nrocredito = $dato->count();
        $mtocredito = $dato->sum('monto_credito');
        $user = DB::table('users')->select('users.id','users.name','users.nombres','users.apellidos','users.dni','users.avatar','users.email','roles.name as rol')
       ->join('model_has_roles','users.id','=','model_id')
       ->join('roles','role_id','=','roles.id')       
       ->get();
        $agen = DB::table('nivel1_has_nivel2')->where('idnivel2',auth('sanctum')->user()->id)->get();
        $nroactividad = DetalleHojaruta::where('idanalista',auth('sanctum')->user()->id)->count();
        $nrosuper = 0;
        $nroagen = $agen->count();
        $mtopago = Pago::where('estado',1)->sum('monto');
        $monto = DB::table('credito as c')->join('pago as p','c.id','=','p.idcredito')->whereYear('fecha_pago', '=', 2021)->where('c.idsupervisor',auth('sanctum')->user()->id)->sum('monto');
       // DD($dato->sum('monto_credito'));
         return ['nrocredito'=> $nrocredito,'mtocredito' => $mtocredito, 'nrosuper' => $nrosuper,'nroagen' => $nroagen, 'mtopago' => $monto,'nroactividad'=>$nroactividad ];
       // $dato1 = Creditos::where('idanalista',auth->('sanctum')->user->id())->first();
       // $dato1 = Creditos::where('idanalista',auth->('sanctum')->user->id())->first();
        //$dato1 = Creditos::where('idanalista',auth->('sanctum')->user->id())->first();
    }
    public function creditomes(Request $request){


        // $client = new Client(['base_uri' => 'http://192.168.1.102/planti81/public/']);
        // $response = $client->request('GET', 'prueba');

        // dd($response);


        $ingreso = DB::table('credito')->select(DB::raw('ELT(MONTH(created_at), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto_credito) as total,MONTH(created_at) as mese'))->where('estado',1)->where('idanalista',auth('sanctum')->user()->id)->whereYear('created_at', '=', $request->ano)->groupby('mes','mese')->orderby('mese')->get();
        
        
        $pago = DB::table('credito as c')->join('pago as p','c.id','=','p.idcredito')
        ->select(DB::raw('ELT(MONTH(fecha_pago), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto) as total,MONTH(fecha_pago) as mese'))
        ->whereYear('fecha_pago', '=', $request->ano)->where('c.idanalista',auth('sanctum')->user()->id)->groupby('mes','mese')->orderby('mese')->get();
        
        
        $cancelaciones = DB::table('credito as c')->join('credito_cancel as cc','c.id','=','cc.idcredito')
        ->select(DB::raw('ELT(MONTH(cc.fec_vaucher), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(cc.total_can) as total, MONTH(cc.fec_vaucher) as mese'))
        ->whereYear('fec_vaucher', '=', $request->ano)->where('c.idanalista',auth('sanctum')->user()->id)->groupby('mes','mese')->orderby('mese')->get();

        // DB::select('select ELT(MONTH(cc.fec_vaucher), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(cc.total_can) as total, MONTH(cc.fec_vaucher) as mese
        // from credito c
        // left join  credito_cancel cc 
        // on c.id = cc.idcredito 
        // where year(fec_vaucher)=2021 and c.idanalista=' . auth('sanctum')->user()->id . '
        // group by  mes, mese');
        // DD($cancelaciones);
       $meses=[];
       $meses1=[];
       $meses_pagos_can=[];
       for ($i=1;$i<13 ; $i++) { 
            $mes = $ingreso->where('mese',$i)->first();
            $mes1 = $pago->where('mese',$i)->first();
            $mes_pagos_can = $cancelaciones->where('mese',$i)->first();


           if (is_null($mes)) {
            array_push($meses,0);
           }else{
            $monto = $ingreso->where('mese',$i)->first();
            //DD($monto);
            array_push($meses,$monto->total);
           }


           if (is_null($mes1)) {
            array_push($meses1,0);
           }else{
            $monto = $pago->where('mese',$i)->first();
            //DD($monto);
            array_push($meses1,$monto->total);
           }


           if (is_null($mes_pagos_can)) {
            array_push($meses_pagos_can,0);
           }else{
            $monto = $cancelaciones->where('mese',$i)->first();
            //DD($monto);
            array_push($meses_pagos_can,$monto->total);
           }
           
       }
        // DD($meses);
        $labe=$ingreso->pluck('mes');
       return [
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => array([
            'label' => 'Creditos',
             'borderColor' => '#FC2525',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#ff000087',
            'data' => $meses,
        ],[
            'label' => 'Pagos',
            'borderColor' => '#05CBE1',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#4ca3dda3',
            'data' => $meses1,
        ],[
            'label' => 'Créditos cancelados',
            'borderColor' => '#3AA600',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#3AA600',
            'data' => $meses_pagos_can,
        ])
    
     ];
   }
   public function creditomesteso(Request $request){
        $ingreso = DB::table('credito')->select(DB::raw('ELT(MONTH(created_at), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto_credito) as total,MONTH(created_at) as mese'))->where('estado',1)->where('desembolso','S')->whereYear('created_at', '=', $request->ano)->groupby('mes','mese')->orderby('mese')->get();
        $pago = DB::table('credito as c')->join('pago as p','c.id','=','p.idcredito')->select(DB::raw('ELT(MONTH(fecha_pago), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto) as total,MONTH(fecha_pago) as mese'))->whereYear('fecha_pago', '=', $request->ano)->groupby('mes','mese')->orderby('mese')->get();
        //DD($ingreso);
       $meses=[];
       $meses1=[];
       for ($i=1;$i<13 ; $i++) { 
            $mes = $ingreso->where('mese',$i)->first();
            $mes1 = $pago->where('mese',$i)->first();
           if (is_null($mes)) {
            array_push($meses,0);
           }else{
            $monto = $ingreso->where('mese',$i)->first();
            //DD($monto);
            array_push($meses,$monto->total);
           }
           if (is_null($mes1)) {
            array_push($meses1,0);
           }else{
            $monto = $pago->where('mese',$i)->first();
            //DD($monto);
            array_push($meses1,$monto->total);
           }
           
       }
        // DD($meses);
        $labe=$ingreso->pluck('mes');
       return [
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => array([
            'label' => 'Creditos',
             'borderColor' => '#FC2525',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#ff000087',
            'data' => $meses,
        ],[
            'label' => 'Pagos',
            'borderColor' => '#05CBE1',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#4ca3dda3',
            'data' => $meses1,
        ] )
    
     ];
   }
   public function creditomesger(Request $request){
        $ingreso = DB::table('credito')->select(DB::raw('ELT(MONTH(created_at), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto_credito) as total,MONTH(created_at) as mese'))->where('estado',1)->whereYear('created_at', '=', $request->ano)->groupby('mes','mese')->orderby('mese')->get();

        $pago = DB::table('credito as c')->join('pago as p','c.id','=','p.idcredito')->select(DB::raw('ELT(MONTH(fecha_pago), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto) as total,MONTH(fecha_pago) as mese'))->where('c.estado',1)->whereYear('fecha_pago', '=', $request->ano)->groupby('mes','mese')->orderby('mese')->get();
        //DD($pago);
       $meses=[];
       for ($i=1;$i<13 ; $i++) { 
            $mes = $ingreso->where('mese',$i)->first();
           if (is_null($mes)) {
            array_push($meses,0);
           }else{
            $monto = $ingreso->where('mese',$i)->first();
            //DD($monto);
            array_push($meses,$monto->total);
           }
           
       }
       $pagos=[];
       for ($i=1;$i<13 ; $i++) { 
            $mes = $pago->where('mese',$i)->first();
           if (is_null($mes)) {
            array_push($pagos,0);
           }else{
            $monto = $pago->where('mese',$i)->first();
            //DD($monto);
            array_push($pagos,round($monto->total,2));
           }
           
       }
       $labelg = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
     //DD($pagos);
        //$labe=$ingreso->pluck('mes');
       return [
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => array([
            'label' => 'Creditos',
            'borderColor' => '#FC2525',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#ff000087', 
                        
            'data' => $pagos,
        ],[
            'label' => 'Pagos ',
            'borderColor' => '#05CBE1',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#4ca3dda3',
            
            'data' => $meses,
            
        ] )
    
     ];
   }
    public function creditomessuper(Request $request){
        $ingreso = DB::table('credito')->select(DB::raw('ELT(MONTH(created_at), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto_credito) as total,MONTH(created_at) as mese'))->where('estado',1)->where('idsupervisor',auth('sanctum')->user()->id)->whereYear('created_at', '=', $request->ano)->groupby('mes','mese')->orderby('mese')->get();

        $pago = DB::table('credito as c')->join('pago as p','c.id','=','p.idcredito')->select(DB::raw('ELT(MONTH(fecha_pago), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto) as total,MONTH(fecha_pago) as mese'))->where('c.estado',1)->where('idsupervisor',auth('sanctum')->user()->id)->whereYear('fecha_pago', '=', $request->ano)->groupby('mes','mese')->orderby('mese')->get();
        //DD($pago);
       $meses=[];
       for ($i=1;$i<13 ; $i++) { 
            $mes = $ingreso->where('mese',$i)->first();
           if (is_null($mes)) {
            array_push($meses,0);
           }else{
            $monto = $ingreso->where('mese',$i)->first();
            //DD($monto);
            array_push($meses,$monto->total);
           }
           
       }
       $pagos=[];
       for ($i=1;$i<13 ; $i++) { 
            $mes = $pago->where('mese',$i)->first();
           if (is_null($mes)) {
            array_push($pagos,0);
           }else{
            $monto = $pago->where('mese',$i)->first();
            //DD($monto);
            array_push($pagos,round($monto->total,2));
           }
           
       }
       $labelg = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
     //DD($pagos);
        //$labe=$ingreso->pluck('mes');
       return [
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => array([
            'label' => 'Creditos',
            'borderColor' => '#FC2525',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#ff000087', 
                        
            'data' => $meses,
        ],[
            'label' => 'Pagos ',
            'borderColor' => '#05CBE1',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#4ca3dda3',
            
            'data' => $pago,
            
        ] )
    
     ];
   }
   public function creditomesanalista(Request $request){

        $analistas = DB::table('credito as c')->join('users as u', 'c.idanalista','=','u.id')->select(DB::raw('distinct(idanalista ) as idanalista,u.apellidos'))->whereYear('c.created_at',$request->ano)->get();
        $nuevo =[];
        foreach ($analistas as $key=>$ana) {
             $ingreso = DB::table('credito')->select(DB::raw('ELT(MONTH(created_at), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto_credito) as total,MONTH(created_at) as mese'))->where('idanalista',$ana->idanalista)->whereYear('created_at', '=', $request->ano)->groupby('mes','mese')->orderby('mese')->get();
             $meses=[];
                for ($i=1;$i<13 ; $i++) { 
                       $mes = $ingreso->where('mese',$i)->first();
                    if (is_null($mes)) {
                        array_push($meses,0);
                    }else{
                        $monto = $ingreso->where('mese',$i)->first();
                        //DD($monto);
                        array_push($meses,$monto->total);
                    }
               
                }

                switch ( $key ) {
                    case 0:
                        $color1='#FC2525';
                        $color2='#ff000087';
                        break;
                    
                    case 1:
                        $color1='#05CBE1';
                        $color2='#4ca3dda3';
                        break;
                    
                    case 2:
                        $color1='#f0bf75';
                        $color2='#ff9900a3';
                        break;
                    case 3:
                        $color1='#4dff4d';
                        $color2='#00cc00';
                        break;
                    case 4:
                        $color1='#cc99ff';
                        $color2='#bf80ff';
                        break;
                    case 5:
                        $color1='#85e0e0';
                        $color2='#33cccc';
                        break;
                    case 6:
                        $color1='#ffff99';
                        $color2='#ffff00';
                        break;
                    case 7:
                        $color1='#ffb399';
                        $color2='#ff4000';
                        break;
                    default:
                        # code...
                        break;
                }
                $datos = [
                            'label' => $ana->apellidos,
                            'borderColor' => $color1,
                            'pointBackgroundColor' => 'white',
                            'borderWidth' => 1,
                            'pointBorderColor' => 'white',
                            'backgroundColor' => $color2,                         
                            'data' => $meses,
                         ];
               // $nuevo = $nuevo.$datos.',';
                array_push($nuevo,$datos); 
                
        }
       // DD($nuevo);
         

        $total=  [
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => $nuevo
    ] ;
       // DD($total); 

       //$labelg = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
     //DD($pagos);
        //$labe=$ingreso->pluck('mes');
       return $total;
   }
   public function creditomesanalistasuper(Request $request){

        $analistas = DB::table('credito as c')->join('users as u', 'c.idanalista','=','u.id')->select(DB::raw('distinct(idanalista ) as idanalista,u.apellidos'))->where('idsupervisor',auth('sanctum')->user()->id)->whereYear('c.created_at',$request->ano)->get();
        $nuevo =[];
        foreach ($analistas as $key=>$ana) {
             $ingreso = DB::table('credito')->select(DB::raw('ELT(MONTH(created_at), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto_credito) as total,MONTH(created_at) as mese'))->where('idanalista',$ana->idanalista)->whereYear('created_at', '=', $request->ano)->groupby('mes','mese')->orderby('mese')->get();
             $meses=[];
                for ($i=1;$i<13 ; $i++) { 
                       $mes = $ingreso->where('mese',$i)->first();
                    if (is_null($mes)) {
                        array_push($meses,0);
                    }else{
                        $monto = $ingreso->where('mese',$i)->first();
                        //DD($monto);
                        array_push($meses,$monto->total);
                    }
               
                }

                switch ( $key ) {
                    case 0:
                        $color1='#FC2525';
                        $color2='#ff000087';
                        $color3='#05CBE1';
                        $color4='#4ca3dda3';
                        break;
                    
                    case 1:
                        $color1='#f0bf75';
                        $color2='#ff9900a3';
                        $color3='#4dff4d';
                        $color4='#00cc00';
                        break;
                    
                    case 2:
                        $color1='#cc99ff';
                        $color2='#bf80ff';
                        $color3='#85e0e0';
                        $color4='#33cccc';
                        break;
                    case 3:
                        $color1='#ffff99';
                        $color2='#ffff00';
                        $color3='#ffb399';
                        $color4='#ff4000';
                    case 4:
                        $color1='#ffff99';
                        $color2='#ffff00';
                        $color3='#ffb399';
                        $color4='#ff4000';
                        break;
                    case 5:
                        $color1='#85e0e0';
                        $color2='#33cccc';
                        $color3='#ffff99';
                        $color4='#ffff00';
                        break;
                    case 6:
                        $color1='#ffff99';
                        $color2='#ffff00';
                        break;
                    case 7:
                        $color1='#ffb399';
                        $color2='#ff4000';
                        break;
                    default:
                        # code...
                        break;
                }
                $datos = [
                            'label' => 'Creditos - '.$ana->apellidos,
                            'borderColor' => $color1,
                            'pointBackgroundColor' => 'white',
                            'borderWidth' => 1,
                            'pointBorderColor' => 'white',
                            'backgroundColor' => $color2,                         
                            'data' => $meses,
                         ];
               // $nuevo = $nuevo.$datos.',';
                array_push($nuevo,$datos); 
                $metames=[];
               $meta = DB::table('meta_usu_mes')->where('idpersona', $ana->idanalista )->where('periodo',$request->ano)->first();
               for ($i=1; $i <13 ; $i++) { 
                    array_push($metames,$meta->{'m'.$i});
                }
                $datos1 = [
                            'label' => 'Meta - '.$ana->apellidos,
                            'borderColor' => $color3,
                            'pointBackgroundColor' => 'white',
                            'borderWidth' => 1,
                            'pointBorderColor' => 'white',
                            'backgroundColor' => $color4,                         
                            'data' => $metames,
                         ];
                array_push($nuevo,$datos1);
                
        }
       // DD($nuevo);
         

        $total=  [
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => $nuevo
    ] ;
       // DD($total); 

       //$labelg = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
     //DD($pagos);
        //$labe=$ingreso->pluck('mes');
       return $total;
   }
   
   public function creditoporcenanalista(Request $request){
        //select idanalista, u.apellidos, sum(monto_credito) as total , from credito as c inner join users  as u on c.idanalista = u.id where estado=1 and year(c.created_at)=2021  GROUP BY idanalista 
        $analistas = DB::table('credito as c')->join('users as u', 'c.idanalista','=','u.id')
        ->select(DB::raw('idanalista,u.apellidos, sum(monto_credito) as total'))
        ->whereYear('c.created_at',$request->ano)
        ->groupby('idanalista')->orderby('total','desc')->get();

         $analistas->map(function($analistas){
                    $analistas->porcent = 1;
                });
                /////
        $porcentaje = new Collection();
        foreach($analistas as $key => $ana){
                switch ( $key ) {
                    case 0:
                        $color1='#FC2525';
                        $color2='#ff000087';
                        break;
                    
                    case 1:
                        $color1='#05CBE1';
                        $color2='#4ca3dda3';
                        break;
                    
                    case 2:
                        $color1='#f0bf75';
                        $color2='#ff9900a3';
                        break;
                    case 3:
                        $color1='#4dff4d';
                        $color2='#00cc00';
                        break;
                    case 4:
                        $color1='#cc99ff';
                        $color2='#bf80ff';
                        break;
                    case 5:
                        $color1='#85e0e0';
                        $color2='#33cccc';
                        break;
                    case 6:
                        $color1='#ffff99';
                        $color2='#ffff00';
                        break;
                    case 7:
                        $color1='#ffb399';
                        $color2='#ff4000';
                        break;
                    default:
                        # code...
                        break;
                }
                $porcentaje->push((object)['nro'=>$key,'apellidos'=>$ana->apellidos,'color'=> $color2,'monto'=>$ana->total,'porcen'=>round(($ana->total*100)/$analistas->sum('total'),2)]);
        }
       //; DD($porcentaje)}
        $datos1=[
        'labels' => $porcentaje->pluck('apellidos'),
        'datasets' => array([
            //'label' => 'Ingresos 2021',
             //'backgroundColor' => ["#F5B7B1", "#9B59B6", "#2980B9", "#1ABC9C", "#F1C40F","#27AE60","#5DADE2","#BDC3C7","#34495E","#7DCEA0","#E59866","#F5B7B1"],
            'backgroundColor' =>  $porcentaje->pluck('color'), 
            'data' => $porcentaje->pluck('porcen'),
        ] )
    
      ];
        
       return ['grafico' =>  $datos1, 'valores'=> $porcentaje];
   }
    public function creditoporcenanalistasuper(Request $request){
        //select idanalista, u.apellidos, sum(monto_credito) as total , from credito as c inner join users  as u on c.idanalista = u.id where estado=1 and year(c.created_at)=2021  GROUP BY idanalista 
        $analistas = DB::table('credito as c')->join('users as u', 'c.idanalista','=','u.id')->select(DB::raw('idanalista,u.apellidos, sum(monto_credito) as total'))->whereYear('c.created_at',$request->ano)->where('idsupervisor',auth('sanctum')->user()->id)-> groupby('idanalista')->orderby('total','desc')->get();

         $analistas->map(function($analistas){
                    $analistas->porcent = 1;
                });
                /////
        $porcentaje = new Collection();
        foreach($analistas as $key => $ana){
                switch ( $key ) {
                    case 0:
                        $color1='#FC2525';
                        $color2='#ff000087';
                        break;
                    
                    case 1:
                        $color1='#05CBE1';
                        $color2='#4ca3dda3';
                        break;
                    
                    case 2:
                        $color1='#f0bf75';
                        $color2='#ff9900a3';
                        break;
                    case 3:
                        $color1='#4dff4d';
                        $color2='#00cc00';
                        break;
                    case 4:
                        $color1='#cc99ff';
                        $color2='#bf80ff';
                        break;
                    case 5:
                        $color1='#85e0e0';
                        $color2='#33cccc';
                        break;
                    case 6:
                        $color1='#ffff99';
                        $color2='#ffff00';
                        break;
                    case 7:
                        $color1='#ffb399';
                        $color2='#ff4000';
                        break;
                    default:
                        # code...
                        break;
                }
                $porcentaje->push((object)['nro'=>$key,'apellidos'=>$ana->apellidos,'color'=> $color2,'monto'=>$ana->total,'porcen'=>round(($ana->total*100)/$analistas->sum('total'),2)]);
        }
       //; DD($porcentaje)}
        $datos1=[
        'labels' => $porcentaje->pluck('apellidos'),
        'datasets' => array([
            //'label' => 'Ingresos 2021',
             //'backgroundColor' => ["#F5B7B1", "#9B59B6", "#2980B9", "#1ABC9C", "#F1C40F","#27AE60","#5DADE2","#BDC3C7","#34495E","#7DCEA0","#E59866","#F5B7B1"],
            'backgroundColor' =>  $porcentaje->pluck('color'), 
            'data' => $porcentaje->pluck('porcen'),
        ] )
    
      ];
        
       return ['grafico' =>  $datos1, 'valores'=> $porcentaje];
   }
   
   
   public function metacreditomess(Request $request){
        $año = date('Y');
        $ingreso = DB::table('credito')->select(DB::raw('ELT(MONTH(created_at), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto_credito) as total,MONTH(created_at) as mese'))->where('idanalista',auth('sanctum')->user()->id)->where('estado',1)->whereYear('created_at', '=', $request->ano)->groupby('mes','mese')->orderby('mese')->get();
        //$ingreso = DB::table('credito as c')->join('pago as p','c.id','=','p.idcredito')->select(DB::raw('ELT(MONTH(fecha_pago), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto) as total,MONTH(fecha_pago) as mese'))->whereYear('fecha_pago', '=', 2021)->where('c.idanalista',auth('sanctum')->user()->id)->groupby('mes','mese')->orderby('mese')->get();
        $meses=[];
       for ($i=1;$i<13 ; $i++) { 
            $mes = $ingreso->where('mese',$i)->first();
           if (is_null($mes)) {
            array_push($meses,0);
           }else{
            $monto = $ingreso->where('mese',$i)->first();
            //DD($monto);
            array_push($meses,$monto->total);
           }
           
       }
         $meta = DB::table('meta_usu_mes')->where('idpersona', auth('sanctum')->user()->id )->where('periodo',$request->ano)->first();
         $metames = [];
         for ($i=1; $i <13 ; $i++) { 
              array_push($metames,$meta->{'m'.$i});
         }
        
         $mesmeta=[
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => array([
            'label' => 'Meta Asignada',
            'borderColor' => '#FC2525',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#ff000087', 
                        
            'data' => $metames,
        ],[
            'label' => 'Avance ',
            'borderColor' => '#05CBE1',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#4ca3dda3',
            
            'data' => $meses,
            
        ] )
    
     ];

         
        $dato1 =[
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => array([
            'label' => 'Pago de Creditos',
            'backgroundColor' => '#F5B7B1',
            'data' => $meses,
        ] )
    
     ];


     $labelg = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
     
        //$object = (object) $metames;

        $datoos =[
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => array([
            'label' => 'Meta Asignada',
            'borderColor' => '#FC2525',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#ff000087', 
                        
            'data' => $metames,
        ],[
            'label' => 'Avance ',
            'borderColor' => '#05CBE1',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#4ca3dda3',
            
            'data' => $meses,
            
        ] )
    
     ];


        //DD($ingreso);
       
        // DD($meses);
        $labe=$ingreso->pluck('mes');
       return ['valor1'=>$metames,'valor2'=>$meses];
   }
   public function pagocreditomessuper(Request $request){
        $año = date('Y');
        $ingreso = DB::table('credito as c')->join('pago as p','c.id','=','p.idcredito')->select(DB::raw('ELT(MONTH(fecha_pago), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto) as total,MONTH(fecha_pago) as mese'))->whereYear('fecha_pago', '=', $request->ano)->where('c.estado',1)->groupby('mes','mese')->orderby('mese')->get();
        $meses=[];
       for ($i=1;$i<13 ; $i++) { 
            $mes = $ingreso->where('mese',$i)->first();
           if (is_null($mes)) {
            array_push($meses,0);
           }else{
            $monto = $ingreso->where('mese',$i)->first();
            //DD($monto);
            array_push($meses,$monto->total);
           }
           
       }
         $meta = DB::table('meta_usu_mes')->where('idsupervisor', auth('sanctum')->user()->id )->where('periodo',$request->ano)->get();
         $metames = [];
         for ($i=1; $i <13 ; $i++) { 
              array_push($metames,$meta->sum('m'.$i));
         }
        
         $mesmeta=[
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => array([
            'label' => 'Meta Asignada',
            'borderColor' => '#FC2525',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#ff000087', 
                        
            'data' => $metames,
        ],[
            'label' => 'Avance ',
            'borderColor' => '#05CBE1',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#4ca3dda3',
            
            'data' => $meses,
            
        ] )
    
     ];
        $dato1 =[
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => array([
            'label' => 'Pago de Creditos',
            'backgroundColor' => '#F5B7B1',
            'data' => $meses,
        ] )
    
     ];

     $labelg = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        //DD($ingreso);
        // DD($meses);
        $labe=$ingreso->pluck('mes');
       return ['dato1'=>$dato1, 'labelg'=>$labelg, 'grafico1'=>$metames ,'grafico2'=>$meses];
   }
   public function pagocreditomesger(Request $request){
        $año = date('Y');
        $ingreso = DB::table('credito as c')->join('pago as p','c.id','=','p.idcredito')->select(DB::raw('ELT(MONTH(fecha_pago), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") as mes, sum(monto) as total,MONTH(fecha_pago) as mese'))->whereYear('fecha_pago', '=', $request->ano)->where('c.estado',1)->groupby('mes','mese')->orderby('mese')->get();
        $meses=[];
       for ($i=1;$i<13 ; $i++) { 
            $mes = $ingreso->where('mese',$i)->first();
           if (is_null($mes)) {
            array_push($meses,0);
           }else{
            $monto = $ingreso->where('mese',$i)->first();
            //DD($monto);
            array_push($meses,$monto->total);
           }
           
       }
         
         $mesmeta=[
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => array([
            'label' => 'Avance de Pagos ',
            'borderColor' => '#05CBE1',
            'pointBackgroundColor' => 'white',
            'borderWidth' => 1,
            'pointBorderColor' => 'white',
            'backgroundColor' => '#4ca3dda3',
            
            'data' => $meses,
            
        ] )
    
     ];
        $dato1 =[
        'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        'datasets' => array([
            'label' => 'Pago de Creditos',
            'backgroundColor' => '#F5B7B1',
            'data' => $meses,
        ] )
    
     ];

     $labelg = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        //DD($ingreso);
        // DD($meses);
        $labe=$ingreso->pluck('mes');
       return ['dato1'=>$dato1, 'labelg'=>$labelg, 'grafico2'=>$meses];
   }
   public function metames(Request $request){

       // $año = date('Y');
    //    DD($request->mes, $request->ano);
        //$nombre = date('M');
       // $fecha = DateTime::createFromFormat('!m', intval($request->mes));
        $nombre = date('F', mktime(0, 0, 0, $request->mes, 10));
        $mes = intval($request->mes);
        // DD($mes);
        $meta = DB::table('meta_usu_mes')->where('idpersona', auth('sanctum')->user()->id )->where('periodo',$request->ano)->first();      
        $logro = DB::table('credito as c')->join('pago as p','c.id','=','p.idcredito')->select(DB::raw('sum(monto) as total'))->whereYear('fecha_pago', '=', $request->ano)->where('c.idanalista',auth('sanctum')->user()->id)->whereMonth('fecha_pago','=',$mes)->first();
        // $mm= 'm'.$mes;
        // DD($meta);
        // DD($meta['$mm']);
         $meses=[];
         $avance = ($logro->total*100)/$meta->{'m'.$mes};
         $pen = (($meta->{'m'.$mes}-$logro->total)*100)/$meta->{'m'.$mes};
         //array_push($meses,$meta->{'m'.$mes});
         array_push($meses,number_format($avance, 2, '.', ','));
         array_push($meses,number_format($pen, 2, '.', ','));
        //$meses1 = new Collection();
        
        $meses1=['color'=>'#28a745','nro'=>number_format($avance, 2, '.', ','),'nombre'=>'Avance','monto'=>number_format($logro->total, 2, '.', ','),'meta'=>$meta->{'m'.$mes}];
        $meses2=['color'=>'#dc3545','nro'=>number_format($pen, 2, '.', ','),'nombre'=>'Pendiente','monto'=>number_format($meta->{'m'.$mes}-$logro->total, 2, '.', ','),'meta'=>$meta->{'m'.$mes}];
        
           
        

       $labe=["Avance","Pendienete"];

       $pie = [
        'labels' =>$labe,
        'datasets' => array([
            'label' => 'Progreso Mes'.$nombre,
             //'backgroundColor' => ["#F5B7B1", "#9B59B6", "#2980B9", "#1ABC9C", "#F1C40F","#27AE60","#5DADE2","#BDC3C7","#34495E","#7DCEA0","#E59866","#F5B7B1"],
            'backgroundColor' =>['#28a745','#dc3545'], 
            'data' => [number_format($avance, 2, '.', ','),number_format($pen, 2, '.', ',')],
        ] )
    
      ];

       return ['pie'=> $pie,'cuadro'=>$meses1,'cuadro1'=>$meses2];
    }
    public function metamesteso(Request $request){

        $año = date('Y');
        $nombre = date('M');
        $mes = intval(date('n'));
        //DD($mes);
        $pago = DB::table('pago')->select(DB::raw('sum(cap_amor) as capital,sum(interes) as interes, sum(com_des) as comision, sum(seg_des) as seguro'))->whereYear('fecha_pago', '=', $año)->whereMonth('fecha_pago','=',1)->first();
        //$meta = DB::table('meta_usu_mes')->where('idpersona', auth('sanctum')->user()->id )->where('periodo',$año)->first();
      
        //$logro = DB::table('credito as c')->join('pago as p','c.id','=','p.idcredito')->select(DB::raw('sum(monto) as total'))->whereYear('fecha_pago', '=', $año)->where('c.idanalista',auth('sanctum')->user()->id)->whereMonth('fecha_pago','=',$mes)->first();
         //DD($meta->{'m'.$mes});
         $meses=[];
        // $avance = ($logro->total*100)/$meta->{'m'.$mes};
        // $pen = (($meta->{'m'.$mes}-$logro->total)*100)/$meta->{'m'.$mes};
         //array_push($meses,$meta->{'m'.$mes});
         //array_push($meses,number_format($avance, 2, '.', ','));
         //array_push($meses,number_format($pen, 2, '.', ','));
        //$meses1 = new Collection();
        
        $meses1= 1;//['color'=>'#28a745','nro'=>number_format($avance, 2, '.', ','),'nombre'=>'Avance','monto'=>number_format($logro->total, 2, '.', ','),'meta'=>$meta->{'m'.$mes}];
        $meses2=2 ;//['color'=>'#dc3545','nro'=>number_format($pen, 2, '.', ','),'nombre'=>'Pendiente','monto'=>number_format($meta->{'m'.$mes}-$logro->total, 2, '.', ','),'meta'=>$meta->{'m'.$mes}];
        
           
        

       $labe=["Capital","Interes","Comision","Seguro"];

       $pie = [
        'labels' =>$labe,
        'datasets' => array([
            'label' => 'Progreso Mes '.$nombre,
             //'backgroundColor' => ["#F5B7B1", "#9B59B6", "#2980B9", "#1ABC9C", "#F1C40F","#27AE60","#5DADE2","#BDC3C7","#34495E","#7DCEA0","#E59866","#F5B7B1"],
            'backgroundColor' =>["#28a745","#dc3545","#2980B9", "#1ABC9C"], 
            'data' => [$pago->capital,$pago->interes,$pago->comision,$pago->seguro],
        ] )
    
      ];

       return ['pie'=> $pie,'cuadro'=>$meses1,'cuadro1'=>$meses2];
    }
    public function periodos (Request $request){
        $periodo = DB::table('periodo')->get();

        return  $periodo;
    }
}
