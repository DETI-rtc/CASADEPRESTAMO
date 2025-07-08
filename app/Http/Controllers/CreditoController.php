<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credito;
use App\Models\CreditoDetalle;
use Illuminate\Support\Collection;
use DB;

class CreditoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('sanctum')->user();
        if ($user->roles[0]->name == 'ADMINISTRADOR' || $user->roles[0]->name == 'SUB GERENTE') {
            return Credito::with('contrato','contrato.tipo_contrato','arraydetalle','arraydetalle.comprobantes','arraydetalle.comprobantes.serieRelacion', 'arraydetalle.comprobantes.detalle')->orderBy('nrocredito','desc')->get();
        } else {
            return Credito::with('contrato','contrato.tipo_contrato','arraydetalle','arraydetalle.comprobantes','arraydetalle.comprobantes.serieRelacion', 'arraydetalle.comprobantes.detalle')->where('id_user', auth('sanctum')->user()->id)->orderBy('nrocredito','desc')->get();
        }
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
            $request['nrocredito'] = Credito::orderBy('nrocredito','desc')->get()->first()->nrocredito +1;
            $request['id_user'] = auth('sanctum')->user()->id;
            $data = Credito::create($request->all());
            $fecha = strtotime($request['fecha_inicio']);
            for ($i=0; $i < $request['nrocuotas']; $i++) {
                $fecha = date("Y-m-d", strtotime("+1 month", $fecha));
                $detalle = [
                    'idcredito' => $data->id,
                    'nro_cuota'=> $i+1,
                    'fecha_vencimiento' => $fecha,
                    'monto_deuda' => $request['monto_mensual'],
                    'saldo' => $request['monto_mensual'],
                ];
                CreditoDetalle::create($detalle);
                $fecha = strtotime($fecha);
            }
            DB::commit();
            return $data;
        } catch (Exception $e) {
            DB::rollback();
            return $e;
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
        return Credito::where('id',$id)->with('contrato','contrato.tipo_contrato','arraydetalle')->get()->first();
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
        $update = Credito::find($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Credito::find($id)->update(['estado'=>0]);
        CreditoDetalle::where('idcredito', $id)->update(['saldo'=> 0,'estado' => 0]);
        return $id;
    }
    public function getCreditosByIdCliente($id){
        $data = Credito::with('arraydetalle','contrato')->where('idcliente',$id)->where('estado',1)->get();
        return $data;
    }

    public function reportReenganches(){
        $totalCred = DB::select("select CONCAT(p.nombres,' ',p.paterno,' ',p.materno) as cliente, count(c.numero) -1 as reenganche, count(c.numero) as total_creditos, sum(monto_credito) as total_prestado, c.numero, sum(monto_can) as total_cancelaciones 
        from credito c
        inner join persona p on c.idpersona = p.id
        left join credito_cancel cc on c.id = cc.idcredito
        where c.estado != 0
        group by numero
        order by c.numero");
        $data = new Collection();
        foreach ($totalCred as $k => $v) {
            // if ($v->reenganche > 0) {
                // dd($k, $v);
                $v->reenganches = Credito::with('persona','cronograma')->where('numero', $v->numero)->get();
                $data->push($v);
            // }
        }
        return $totalCred;

    }
}
