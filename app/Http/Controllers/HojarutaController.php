<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hojaruta;
use App\Models\DetalleHojaruta;
use Carbon\Carbon;
use DB;

class HojarutaController extends Controller
{
    public function index()
    {
        $ruta = Hojaruta::with("arrayDetalle")->where('idanalista',auth('sanctum')->user()->id)->get();
        return $ruta;
        //response()->json(['role'=>Role::all()]);
    }

    public function store(Request $request)
    {
        
        try{
            DB::beginTransaction();
            //$numero = DB::table('glob_corre')->where('tipodoc','PB')->where('anio',$anio)->first();
            $hoja = new Hojaruta();
            $hoja->fecha = $request->fecha;
            $hoja->idanalista = auth('sanctum')->user()->id;
            $hoja->estado = 1;
            $hoja->save();
 
            $detalles = $request->array_detalle;//Array de detalles
            //Recorro todos los elementos
 //DD($detalles);
             $super = DB::table('nivel1_has_nivel2')->where('idnivel1',auth('sanctum')->user()->id)->first();
            foreach($detalles as $det)
            {
                $detalle = new DetalleHojaruta();
                $detalle->idhoja = $hoja->id;
                $detalle->hora_ini = $det['hora_ini'];
                $detalle->hora_fin = $det['hora_fin'];
                $detalle->dni = $det['dni'];
                $detalle->cliente = $det['cliente'];
                $detalle->actividad = $det['actividad'];
                $hora = Carbon::parse($request->get('hora_ini'))->format('H');
                    if ($hora<12) {
                     $detalle->horario = 'M';
                        # code...
                    }else{
                        $detalle->horario = 'T';
                    }
                $detalle->idanalista = auth('sanctum')->user()->id;
                $detalle->idsupervisor = $super->idnivel2;
                $detalle->save();                
            }          
            //$nuemor = DB::table('glob_corre')->where('tipodoc', 'PB')->update(['nume' => 1 + $numero->nume]);
            //DB::update('update glob_corre set nume = ? where tipodoc = "PB"', $numero->nume + 1);
            DB::commit();
            return $hoja;
        } catch (Exception $e){
            DB::rollBack();
        }
       
    }

    
    public function show($id)
    {
       //DD($id);
        $nomb = db::table('users as u')->join('hoja_ruta as h','u.id','=','h.idanalista')->select(db::raw('concat(u.apellidos," ",u.nombres) as nombre'))->where('h.id',$id)->first();
       $deta = Detallehojaruta::where('idhoja',$id)->get();
       $man = $deta->where('horario','M');
       // $man1 = $deta->where('horario','M')->count('id');
       $tar = $deta->where('horario','T');
       //$tar1 = $deta->where('horario','T')->count('id');
       return  ['deta' => $nomb, 'maña' => $man,'tarde' => $tar ,'nroma'=>count($man), 'nrota'=>count($tar)];
    }
   
    public function update(Request $request, $id)
    {
       // DD($id);
        try{
            DB::beginTransaction();
           
            $hoja = Hojaruta::findOrFail(intval($id));
            $hoja->fecha = $request->fecha;
            $hoja->idanalista = auth('sanctum')->user()->id;
            $hoja->estado = 1;
            $hoja->save();
 
            $detalles = $request->array_detalle;//Array de detalles
            //Recorro todos los elementos

           
            foreach($detalles as $ep=>$det)
            {
                
                if (isset($det['id'])){
                    $detalle =  DetalleHojaruta::findOrFail($det['id']);
                    $detalle->idhoja = $hoja->id;
                    $detalle->hora_ini = $det['hora_ini'];
                    $detalle->hora_fin = $det['hora_fin'];
                    $detalle->dni = $det['dni'];
                    $detalle->cliente = $det['cliente'];
                    $detalle->actividad = $det['actividad'];
                    $hora = Carbon::parse($det['hora_ini'])->format('H');
                    
                    if (intval($hora) < 12) {
                     $detalle->horario = 'M';
                        # code...
                    }else{
                        $detalle->horario = 'T';
                    }
                    $detalle->save();          
                    
                }else{
                    $detalle = new DetalleHojaruta();
                    $detalle->idhoja = $hoja->id;
                    $detalle->hora_ini = $det['hora_ini'];
                    $detalle->hora_fin = $det['hora_fin'];
                    $detalle->dni = $det['dni'];
                    $detalle->cliente = $det['cliente'];
                    $detalle->actividad = $det['actividad'];
                    $hora = Carbon::parse($det['hora_ini'])->format('H');
                    if (intval($hora) < 12) {
                     $detalle->horario = 'AM';
                        # code...
                    }else{
                        $detalle->horario = 'PM';
                    }
                    $detalle->save();            
                }
                               
            }          
           
            //DB::update('update glob_corre set nume = ? where tipodoc = "PB"', $numero->nume + 1);
            DB::commit();
            return $hoja;
        } catch (Exception $e){
            DB::rollBack();
        }
        

    }

    public function destroy($id)
    {
        $hoja = Hojaruta::find($id)->delete();
        return $role;
    }
    public function eliminardetalle(Request $request)
    {

      $detalle  = Detallehojaruta::find($request->id)->delete(); 
      return $detalle;

    }
    
}
