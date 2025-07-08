<?php

namespace App\Http\Controllers;

use App\Models\Canceladredi;
use Illuminate\Http\Request;
use App\Models\Cancelacredi;
use App\Models\Persona;
use App\Models\Cronograma;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use DB;
class CancelacredController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $user = auth('sanctum')->user();
        $role = $user->getRoleNames();


        if ($role->first() == "Agente") {
          //  select pa.*, CONCAT(p.paterno,' ',p.materno,' ',p.nombres) as clientes from pago as pa INNER JOIN credito as c on pa.idcredito = c.id INNER JOIN persona as p on c.idpersona = p.id where c.idanalista = 6
          $cred = Cancelacredi::where('iduser',$user->id)->get();
          foreach ($cred as $cre) {
            $cre->fecha_pago = $cre->fec_ven;
            $cre->cuotas_fal = $cre->cuotas_can;
            $cre->monto= $cre->total_can;
            
            // $cre->fecha = $cre->fecha->format('d/m/Y');
            $cre->empresa = Persona::where('id',$cre->idcliente)->first()->empresa;
            $cre->celular = Persona::where('id',$cre->idcliente)->first()->celular;
          }
  //DD($cred);
          //DB::table('pago as pa')->join('credito as c','pa.idcredito','=','c.id')->join('persona as  p','c.idpersona','=','p.id')->select(DB::raw(' pa.*, CONCAT(p.paterno," ",p.materno," ",p.nombres) as clientes'))->where('c.idanalista',$user->id)->get();
        }else{
            $cred = Cancelacredi::all();
            foreach ($cred as $cre) {
                $cre->fecha_pago = $cre->fec_ven;
                $cre->cuotas_fal = $cre->cuotas_can;
                $cre->monto= $cre->total_can;
                // $ultima_c = DB::select("select d.*
                // from cronograma c 
                // inner join detallecrono d on c.id = d.idcrono 
                // where c.idcredito = $cre->idcredito 
                // and d.situacion = 'C'
                // order by id desc LIMIT 1;");
                // if (count($ultima_c) > 0) {
                //     if ($ultima_c[0]->sal_cap >= $cre->monto) {
                //         $cre->interes = 0;
                //         $cre->com_des = 0;
                //         $cre->seg_des = 0;
                //     } else {
                //         $cre->interes = $ultima_c[0]->interes;
                //         $cre->com_des = $ultima_c[0]->com_des;
                //         $cre->seg_des = $ultima_c[0]->seg_des;
                //     }
                //     $cre->sal_cap = $ultima_c[0]->sal_cap;
                // }
                $cre->sal_cap = $cre->monto_can;
                $cre->interes = $cre->int_can;
                $cre->com_des = $cre->com_des;
                $cre->seg_des = $cre->seg_can;    
                $cre->fec_ven = Carbon::parse($cre->fec_ven )->format('d-m-Y');
                $cre->empresa = Persona::where('id',$cre->idcliente)->first()->empresa;
                $cre->celular = Persona::where('id',$cre->idcliente)->first()->celular;

                // $cre->fec_ven = date($cre->fec_ven)->format('d/m/Y');
                // DD($cre);
            }
        }
        //DD($cred[64]);
       // $cred = cred::with('persona')->get();
        // DD('ASD');
        return $cred;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //DD($request->sal_cap);
        // if ($request->primero >= $request->cuotanro) {
            $dif = $request->cuotanro-$request->primero;
      
        try {
            DB::beginTransaction();
            // $cancelado = DB::table('detallecrono')->where('idcrono', $request->idcrono)->get();
            $cancelado =  Cronograma::with('detalle')->where('idcredito',$request->idcredito)->first();
            // DD($cancelado->detalle->where('situacion','C')->count('cuota'));
            $cliente = Persona::find($request->idcliente);
            $credi = new Cancelacredi;
            $credi->idcredito=$request->idcredito;
            $credi->idcliente=$request->idcliente;
            $credi->cliente=$request->nombre_cliente;
            $credi->dni=$request->dni;
            $credi->cuotas_can=$request->cuotas_fal;//$cancelado->detalle->where('situacion','C')->count('cuota');


            $credi->monto_cuo_can=$cancelado->detalle->where('situacion','<>','C')->sum('cuota');
            // $credi->fec_ven=Carbon::now()->addday(2)->format('Y-m-d');
            $credi->monto_can=$request->sal_cap;
            $credi->int_can=$request->interes;
            $credi->seg_can=$request->seg_des;

            $credi->fec_ven=$request->fecha_ven; 
            
            $credi->situacion = 'R';
            $credi->estado = 1;
            $credi->referencia= $request->referencia;

            $credi->iduser=auth('sanctum')->user()->id;
            $super = DB::table('nivel1_has_nivel2')->where('idnivel1',auth('sanctum')->user()->id)->where('estado',1)->first();
            // dd($super);
            // $credi->idsupervisor=$super->idnivel2;
            $credi->idsupervisor=auth('sanctum')->user()->id;
            // $credi->int_can=$request->int_can;

            //$credi->monto_can=0;
            $credi->redondeo=$request->redondeo;
            $credi->total_can=$request->monto;
            $credi->cuotas_pen_can=$request->cuotas_pen_can;
            // $credi->fecha_reg=Carbon::now()->format('Y-m-d');
            $credi->fecha_reg=$request->fecha_reg;
            $credi->save();
            
            $crono = DB::table('detallecrono')->where('idcrono',$cancelado->id)->where('situacion','P')->where('cuotanro','>=',$request->cuotanro)->update(['situacion'=>'A','estado'=>1]);
            if ($dif = 0) {
                $credito = DB::table('credito')->where('id',$request->idcredito)->update(['estado_cred'=>'CA']);
            }

            
            

            // dd('correcto');
            DB::commit();
            return $credi;
            
            
        } catch (Exception $e) {
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Canceladredi  $canceladredi
     * @return \Illuminate\Http\Response
     */
    public function show(Canceladredi $canceladredi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Canceladredi  $canceladredi
     * @return \Illuminate\Http\Response
     */
    public function edit(Canceladredi $canceladredi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Canceladredi  $canceladredi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // DD($request->file);
        $extension = $request->file('vaucher')->getClientOriginalExtension();
        //DD($extension);
        $credi = Cancelacredi::findOrFail($id);
        $currentPhoto = $credi->vaucher;
        if($request->vaucher != $currentPhoto){
            $name = time().'.' . explode('/', explode(':', substr($request->vaucher, 0, strpos($request->vaucher, ';')))[1])[1];

            \Image::make($request->vaucher)->save(public_path('/storage/').$name);
            $request->merge(['vaucher' => $name]);

            $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }
        $credi->situacion = 'P';
        $credi->update($request->all());
        return $credi;
    }
    public function actualizar(Request $request)
    {
        
        //$extension = $request->file('vaucher')->getClientOriginalExtension();
       
        $credi = Cancelacredi::findOrFail($request->id);
        //DD($request->monto);
        $credi->monto_can = $request->sal_cap;
        $credi->int_can = $request->interes;
        $credi->seg_can=$request->seg_des;
        $credi->total_can=$request->monto;        
        $credi->redondeo=$request->redondeo;
        $credi->referencia=$request->referencia;
        //DD($credi);
        $credi->save();
        return $credi;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Canceladredi  $canceladredi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Canceladredi $canceladredi)
    {
        //
    }

    public function subirvoucher(Request $request){
        // DD($request->fec_vaucher);
        try {
            DB::beginTransaction();
            $extension = $request->file('vaucher')->getClientOriginalExtension();
            $credi = Cancelacredi::findOrFail($request->id);
            $currentPhoto = $credi->vaucher;
            $imageName = '0000'.$request->id.'.'.$extension;
            $credi->vaucher = $imageName; 
            $request->vaucher->move(public_path('storage'), $imageName);
            $credi->situacion = 'P';
            $credi->update(['situacion'=>'P']);
            $credi->update(['vaucher'=>$imageName]);
            $credi->update(['fec_vaucher'=>$request->fec_vaucher]);
            DB::commit();
            return $credi;
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }


    public function getcancelteso(){

        try {
            DB::beginTransaction();
            $cred = Cancelacredi::where('situacion','P')->orWhere('situacion','V')->orWhere('situacion','C')->get();
            foreach ($cred as $cre) {
                $cre->fecha_pago = $cre->fec_ven;
                $cre->cuotas_fal = $cre->cuotas_can;
                $cre->monto= $cre->total_can;

                $cre->fec_ven = Carbon::parse($cre->fec_ven )->format('d-m-Y');
                $cre->empresa = Persona::where('id',$cre->idcliente)->first()->empresa;
                $cre->celular = Persona::where('id',$cre->idcliente)->first()->celular;
                // $cre->fec_ven = date($cre->fec_ven)->format('d/m/Y');
                // DD($cre);
            }

            return $cred;

        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }

    public function validarPago(Request $request){
        try {
            DB::beginTransaction();
            if ($request->funcion == 'valido') {
                $cred = Cancelacredi::where('id',$request->id)->update(['situacion'=> 'V']);
            }
            if ($request->funcion == 'rechazar') {
                $cred = Cancelacredi::where('id',$request->id)->update(['situacion'=> 'C']);
            }
            // DD('ACABO');
            DB::commit();

            return $cred;
            //code...
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }
}
