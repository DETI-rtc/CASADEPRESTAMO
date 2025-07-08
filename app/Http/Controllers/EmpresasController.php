<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Delista;
use App\Models\Lista;
use DB;

class EmpresasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empre=Empresa::with('convenio')->get();
        // foreach ($empre as $item) {
        //     // DD($empre->id_deta_list);
        //     $sector = Delista::where('id',$item->id_deta_list)->get()->first();
        //     $item->sector=$sector->nomdelista;
        // }
        // DD($empre);
        return $empre;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            //'telefono.required' => 'El Telefono es requerido',
            'direccion.required' => 'La Direccion es requerido',
            'razonsocial.required' =>'La Razon Social es requerido',
            'ruc.unique' =>'El RUC ya esta Registrado'
            
            
        ];
        $this->validate($request,[
            //'telefono' => 'required',
            'direccion' => 'required',
            'razonsocial' => 'required',
            'ruc' => 'required|unique:empresa'
           
            
        ],$messages);
        
        $empre = new Empresa;
        $empre->ruc=$request->get('ruc');
        $empre->razonsocial=$request->get('razonsocial');
        $empre->direccion=$request->get('direccion');
        $empre->telefono=$request->get('telefono');
        $empre->contacto=$request->get('contacto');
        $empre->telefo_cont=$request->get('telefo_cont');
       // $empre->convenio=$request->get('convenio');
        $empre->id_tipo=$request->get('id_tipo');
        $empre->sector=$request->get('sector');
        $empre->save();
     
        return $empre;
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
        $empre = Empresa::findOrFail($id);

        $this->validate($request,[
            'ruc' => 'required|unique:empresa,ruc,'.$empre->id            
        ]);
        $empre->ruc=$request->get('ruc');
        $empre->razonsocial=$request->get('razonsocial');
        $empre->direccion=$request->get('direccion');
        $empre->telefono=$request->get('telefono');
        $empre->contacto=$request->get('contacto');
        $empre->telefo_cont=$request->get('telefo_cont');
        $empre->convenio=$request->get('convenio');
        $empre->id_tipo=$request->get('id_tipo');
        $empre->sector=$request->get('sector');
        $empre->iduser=auth('sanctum')->user()->id;
        $empre->save();
        //DD($request);
       // $empre->update($request->all());
        
        return $empre;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empre = Empresa::findOrFail($id);
        // deletplazo the user
        $empre->estado=0;
        $empre->save();

        return $empre;
    }
    public function buscarruc(Request $request){
        $token='b99fbb01645d16a3247a13913ae6ac3c23c2c4c38c5d1071ba5db05f784357e0';
        $client = new \GuzzleHttp\Client([
             'headers' => ['content-type' => 'application/json',  'charset' => 'utf-8']
           // 'headers' => ['Authorization' => 'Bearer ' . $token,        
             //    'Accept'        => 'application/json',
           //  ]
             ]);
        //$client = new \GuzzleHttp\Client([
           // 'headers' => ['content-type' => 'application/json',  'charset' => 'utf-8']
        //   'headers' => ['Authorization' => 'Bearer ' . $token,        
        //        'Accept'        => 'application/json',
        //    ]
        //    ]);
        $request = $client->get('https://api.sunat.cloud/ruc/'.$request->ruc,['verify' => false]);
        $response = $request->getBody();
        return  $response;
     }
     public function getbyidandsector($id){

         $empresa=Empresa::with('tipoempresa')->where('id',$id)->first();
         $delista= DB::table('listas as l')->join('lista_deta as d','l.id','=','d.idlista')->where('nomlista',$empresa->sector)->get();

         //lista::with('detalle')->where('nomlista',$empresa->sector)->get();
          //DD($delista);
         return $delista;
        // $empresa['sector']
    }
    public function empsinconvenio(){
        $empresas = DB::table('empresa as e')->whereRaw("e.id NOT IN (select idempresa from convenio)")->get();
        //SELECT * FROM `empresa` where id not in (select idempresa from convenio)
        return $empresas;

    }
}
