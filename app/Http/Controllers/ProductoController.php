<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Tipoafectacion;
use App\Models\Almacen;
use App\Models\Unidad;
use App\Models\Moneda;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $producto = Producto::all();
       return $producto;
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
    public function store(Request $request){
        //DD($request);
        $id = $request->input('id');
        $producto = producto::firstOrNew(['id' => $id]);
        $producto->nombre = $request->get('nombre');
        $producto->idmoneda = $request->get('idmoneda');
        $producto->idcategoria= $request->get('idcategoria');
        $cat =Categoria::where('id',$request->get('idcategoria'))->first('nombre');
        $producto->categoria=$cat->nombre;        
        $producto->idtipoafectacion= $request->get('idtipoafectacion');
        $tipo =Tipoafectacion::where('id','=',$request->get('idtipoafectacion'))->first('descripcion');
        //DD($tipo);
        $producto->afectacion= $tipo->descripcion;
        $producto->valor_unitario = $request->get('valor_unitario');
        $producto->unidad_id = $request->get('unidad_id');
        $producto->unidad = $request->get('unidad');
        $producto->idalmacen = 1;
        $producto->almacen = 'CEMENTERIO GENERAL';
        $producto->codigo_sunat = $request->get('codigo_sunat');
        $producto->save();  

        $producto->update();

        return [
            'success' => true,
            'message' => ($id)?'Producto editado con éxito':'Producto registrado con éxito',
            'id' => $producto->id
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        $producto = Producto::findOrFail($id);
        
        return response()->json($producto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $producto = Producto::findOrFail($id);        
        $producto->update($request->all());        
        return $producto;   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $producto = Producto::findOrFail($id);
        $producto->estado=0;
        $producto->save();

        return $producto;
    }
    public function tablas()
    {
        $items = Producto::all();
        $unidades = Unidad::all();
        $tiposafectacion = TipoAfectacion::all();
        $almacenes = Almacen::all();
        $monedas = Moneda::all();
        // $categorias = [];
        $categorias = Categoria::all();
       

        return compact('unidades','items', 'tiposafectacion', 'almacenes', 'monedas','categorias');
    }

    public function searchItems(Request $request)
    {

        $items = Producto::where('nombre', 'like', "%{$request->input}%" )
                    ->orderBy('nombre')
                    ->get();

        return [
            'success' => true,
            'data' => array('items' => $items)
        ];
    }
    
}
