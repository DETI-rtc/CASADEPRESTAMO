<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Cronograma;
use App\Models\Cuota;
use App\Models\Empresa;
use App\Models\ListaDetalle;
use App\Models\Credito;
use App\Models\Detacronograma;
use App\Models\Lista;

use \NumberFormatter;




use Carbon\Carbon;
use DB;


class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perso=Persona::with('empresa')->get();
        return $perso;
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
            $messages = [
                'ingre_bru.required' => 'El Ingreso Bruto es requerido',
                'idempresa.required' => 'La Empresa es requerido',            
                'tipomodalidad.required' =>'El Tipo de Modalidad es requerido',
                'dni.unique' =>'El DNI ya esta registrado con el periodo'
    
    
            ];
            $this->validate($request,[
                'ingre_bru' => 'required',
                'idempresa' => 'required',            
                'tipomodalidad' => 'required|integer',
                'dni' => 'required|unique:persona',
    
    
            ],$messages);
           // 'dni','paterno','materno','nombres','sexo','fec_nac', 'idestadocivil','email',
           // 'direccion','localidad','ocupacion','celular','referencia','ingre_bru',
           // 'idempresa','tipoentidad','tipomodalidad','idanalista','estado'
            $perso = new Persona;
            $perso->dni=$request->get('dni');
            $perso->nombres=$request->get('nombres');
            $perso->paterno=$request->get('paterno');
            $perso->materno=$request->get('materno');
            $perso->fec_nac=Carbon::parse($request->get('fec_nac'))->format('Y-m-d');
            $perso->sexo=$request->get('sexo');
            $perso->idestadocivil=$request->get('idestadocivil');
            $perso->celular=$request->get('celular');
            $perso->email=$request->get('email');
            $perso->direccion=$request->get('direccion');
            $perso->referencia=$request->get('referencia');
            $perso->ocupacion=$request->get('ocupacion');
            $perso->tipoentidad=Empresa::where('id',$request->idempresa)->first()->id_tipo;
    
            $perso->idempresa=$request->get('idempresa');        
            $perso->sector=$request->get('sector');
            $perso->tipomodalidad=$request->get('tipomodalidad');
            $perso->ingre_bru=$request->get('ingre_bru');
            $perso->escliente = 1 ;
            $perso->idanalista= auth('sanctum')->user()->id;
            $perso->estado=1;
    
            $perso->empresa= Empresa::where('id',$request->idempresa)->first()->razonsocial;        
            $perso->n_domicilio = $request->n_domicilio;
            $perso->tipo_vivienda = $request->tipo_vivienda;
            $perso->nivel_instruccion = $request->nivel_instruccion;
            $perso->tipo_ocupacion = $request->tipo_ocupacion;
            $perso->f_ingreso_actividades = $request->f_ingreso_actividades;
            $perso->f_fin_actividades = $request->f_fin_actividades;
            $perso->modalidad_contratacion = ListaDetalle::where('id',$request->tipomodalidad)->first()->nomdelista;
            $perso->profesion = $request->profesion;
            $perso->cargo = $request->cargo;
            $perso->cargo = $request->area;
            $perso->cci = $request->cci;
            $perso->save();
            // DD($perso);
            DB::commit();
            return $perso;
        } catch (\Throwable $th) {
            //throw $th;
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
        // DD($request, $id);
        $perso=Persona::findOrFail($id);
        $this->validate($request,[
            'dni' => 'required|unique:persona,dni,'.$perso->id
            ],[
            'dni.unique' => 'El DNI ya esta registrado']);
            $perso->update($request->all());

            return $perso;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $perso = Persona::findOrFail($id);
        // delete the user
        $perso->estado=0;
        $perso->save();

        return $perso;
    }
    public function buscardni(Request $request){
        $token='b99fbb01645d16a3247a13913ae6ac3c23c2c4c38c5d1071ba5db05f784357e0';
        $client = new \GuzzleHttp\Client([
           // 'headers' => ['content-type' => 'application/json',  'charset' => 'utf-8']
           'headers' => ['Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ]
            ]);
        $request = $client->get('https://apiperu.dev/api/dni/'.$request->dni,['verify' => false]);
        //$request = $client->get('http://api.reniec.cloud/dni/'.$request->dni,['verify' => false]);
        $response = $request->getBody();
        //$response = $request->getBody();
        //$response = Http::get('https://api.reniec.cloud/dni/40679669');
        return  $response;
    }

    public function burcarpordni($request){
        // $client = Persona::where('dni', $request)->get();
        $persona = Persona::with('empresa')->where('dni',$request)-> first();
        DD($persona);
        $cronograma = Cronograma::where('idcliente', $persona->id)->where('estado','pendiente')->get()->first();
        $cuotas = Cuota::where('idcronograma', $cronograma->id)->get();
        $persona['cronogramas_pendientes']=$cronograma;
        $persona['cuotas']=$cuotas;
        return $persona;
    }

    public function getFormatos($id){
        // $id = id de credito
        Carbon::setLocale('es'); 

        $persona = Credito::with('persona')->where('id',$id)->first();
        $persona->persona['estadocivil'] = ListaDetalle::where('id',$persona->persona->idestadocivil)->first()->nomdelista;
        $persona->empresa = Empresa::where('id',$persona->persona->idempresa)->first();
        $persona->cronograma = Cronograma::where('idcredito', $id)->first();
        $persona->cuota = Detacronograma::where('idcrono',$persona->cronograma['id'])->where('cuotanro',1)->first();
        $persona->ultima_cuota = Detacronograma::where('idcrono',$persona->cronograma['id'])->where('cuotanro',$persona->cronograma['num_cuotas'])->first();
        $persona->fecha_primera_couta = date('d-m-Y',strtotime( Detacronograma::where('idcrono',$persona->cronograma['id'])->where('cuotanro',1)->first()->fecha_ven));
        $persona->dia_pago =  date('d',strtotime($persona->cuota['fecha_ven']));

        if ($persona->fec_aproba_dos == null) {
            $persona->fec_aproba_dos = date('Y-m-d');
        }
        $persona->dia = date('d',strtotime($persona->fec_aproba_dos));
        $persona->mes = date('m',strtotime($persona->fec_aproba_dos));
        $persona->anio = date('Y',strtotime($persona->fec_aproba_dos));
        $persona->fecha_d = date('d-m-Y',strtotime($persona->fec_aproba_dos));
        $persona->monto_multiplicado = number_format($persona->cuota['cuota'] * $persona->cronograma['num_cuotas'], 2, '.', ',');
        $persona->tipo_contrato = ListaDetalle::where('id',$persona->persona->tipomodalidad)->first()->nomdelista;
        $persona->persona['edad'] = Carbon::parse($persona->persona['fec_nac'])->age;
        $persona->persona['tiempo_trabajo'] = Carbon::parse($persona->persona['f_ingreso_actividades'])->age;


        $formater= new NumberFormatter("es",NumberFormatter::SPELLOUT);
        $numero = str_replace(',', '', $persona->monto_credito);
        $number = intval($numero);
        $entero = strtoupper($formater->format($number));

        $persona->total_letras = $entero . ' CON 00/100';
        
        // $persona->persona['estadocivil'] = ListaDetalle::where('id',$persona->persona->idestadocivil)->first()->nomdelista;

        // DD($persona->persona['id']);
        // $id = $persona->persona['id'];
        // $persona->ff = \Carbon\Carbon::parse($persona->fec_aproba_dos);

        $fecha = Carbon::parse($persona->fec_aproba_dos);
        $date = $fecha->locale(); //con esto revise que el lenguaje fuera es 
        // dd($fecha->monthName);

        $cantidadCreditos = DB::select("select count(*) as creditos
        from persona p 
        inner join credito c on p.id = c.idpersona
        where idpersona =  $id");

        // DD($cantidadCreditos);

        // dd($cantidadCreditos[0]->creditos );
        if ($cantidadCreditos[0]->creditos == 1) {
            $persona->tipo_cliente = 'CLIENTE NUEVO';
            // DD('cliente nuevo');
        }
        if ($cantidadCreditos[0]->creditos > 1) {
            $persona->tipo_cliente = 'REENGANCHE';
            // DD('reenganche');
        }
        
        $persona->mes = $fecha->monthName;
        // DD($persona->mes);
        // $persona = Persona::with('empresa')->where('id',$id)-> first();
        return $persona;

    }
}
