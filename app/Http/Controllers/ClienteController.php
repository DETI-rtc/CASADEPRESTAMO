<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use DB;
use App\Models\Tipodocumento;
use App\Models\Country;
use App\Models\Department;
use App\Models\District;
use App\Models\Province;
use Peru\Sunat\RucFactory;
use Peru\Jne\DniFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $cliente = Cliente::with('creditos')->where('estado',1)->get();
       return $cliente;
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
        //DD($request);
        // $cliente = new Cliente;
        // $cliente->tipo_documento_id=$request->get('tipo_documento_id');
        // $cliente->nrodoc=$request->get('nrodoc');
        // $cliente->razon_social=$request->get('razon_social');
        // $cliente->direccion=$request->get('direccion');
        // $cliente->save();  
        $id = $request->input('id');
        $request['razon_social'] = $request['nombre'];
        $request['country_id'] = 'PE';
        $cliente = Cliente::firstOrNew(['id' => $id]);
        $cliente->fill($request->all());
        $cliente->save();        

        return [
            'success' => true,
            'message' => ($id)?'Cliente editado con éxito':'Cliente registrado con éxito',
            'id' => $cliente->id
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        
        return response()->json($cliente);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        $cliente = Cliente::findOrFail($id);        
        $cliente->update($request->all());        
        return $cliente;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->estado=0;
        $cliente->save();

        return $cliente;
    }
    public function tablas()
    {
        $countries = Country::all();
        $departments = Department::all();
        $provinces = Province::all();
        $districts = District::all();
        
        $tipodocumento = Tipodocumento::all();
        //$tipopersona = Tipopersona::get();
        //$ubicacion = $this->getLocationCascade();
      
        // 'paises', 'departamentos', 'provinces', 'distrito', $api_service_token = $configuration->token_apiruc == 'false' ? config('configuration.api_service_token') : $configuration->token_apiruc;

        return compact('tipodocumento','countries','departments','provinces','districts');
    }
    public function getLocationCascade(){
        $locations = [];
        $departamentos = Departamento::where('active', true)->get();
        foreach ($departamentos as $departamento)
        {
            $provincia = [];
            foreach ($departamento->provincias as $provincia)
            {
                $distritos = [];
                foreach ($provincia->distritos as $distrito)
                {
                    $distritos[] = [
                        'value' => $distrito->id,
                        'label' => $distrito->description
                    ];
                }
                $provincia[] = [
                    'value' => $provincia->id,
                    'label' => $provincia->description,
                    'children' => $distritos
                ];
            }
            $locations[] = [
                'value' => $departamento->id,
                'label' => $departamento->description,
                'children' => $provincia
            ];
        }

        return $locations;
    }

    public function searchCustomers(Request $request)
    {
        //DD($request);
        //tru de boletas en env esta en true filtra a los con dni   , false a todos
        // $identity_document_type_id = $this->getIdentityDocumentTypeId($request->document_type_id, $request->operation_type_id);
        // $operation_type_id_id = $this->getIdentityDocumentTypeId($request->operation_type_id);

        $customers = DB::table('persona')->where('dni','like', "%{$request->input}%")
                            ->orWhere('nom_comp','like', "%{$request->input}%")
                            // ->orWhere('nombre','like', "%{$request->input}%")
                            ->orderBy('nom_comp')
                            // ->whereIn('identity_document_type_id',$identity_document_type_id)
                            // ->whereIsEnabled()
                            ->get();
                            // ->transform(function($row) {
                            //     return [
                            //         'id' => $row->id,
                            //         'descripcion' => $row->dni.' - '.$row->nom_comp,
                            //         'razon_social' => $row->nom_comp,
                            //         'nrodoc' => $row->dni,
                            //         // 'identity_document_type_id' => $row->identity_document_type_id,
                            //         // 'identity_document_type_code' => $row->identity_document_type->code,
                            //         // 'addresses' => $row->addresses,
                            //         'direccion' =>  $row->direccion
                            //     ];
                            // });
        return $customers;
    }
    public function buscarruc(Request $request,$id){
      // DD(strlen($id));
       
        if (strlen($id) === 8){
            $factory = new DniFactory();
            $cs = $factory->create();
            $tipo ='DNI';
            $company = $cs->get($id);
            
            if (!$company) {
                
                return [
                    'success' => false,
                    'message' => 'El número de '.$tipo.' ingresado no existe. Detalles: '
                ];
            }else{
                $nombre = $company->nombres.' '.$company->apellidoPaterno.' '.$company->apellidoMaterno;
                $direccion = '';
                return [
                    
                    'success' => true,
                    'nombre'    => $nombre,
                    'direccion'    => $direccion,
                ];
    
            }
        }
        
        if(strlen($id) === 11){
            $factory = new RucFactory();
            $cs = $factory->create();
            $tipo ='RUC';
            $rol = new collection($cs->get($id));
            //$company=new collection($rol);
            $company =  $rol->toArray();
            //DD($nuevo['razonSocial']);
            //$company = json_encode($rol);
           
           

            if (!$company) {
               
                return [
                    'success' => false,
                    'message' => 'El número de '.$tipo.' ingresado no existe. Detalles: '
                ];
            }else{
                $nombre = $company['razonSocial'];
                $direccion = $company['direccion'];
                return [
                   
                    'success' => true,
                    'nombre'    => $nombre,
                    'direccion'    => $direccion,
                ];
    
            }
        }   
    }
}
