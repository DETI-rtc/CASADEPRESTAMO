<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group( function () {
     Route::apiResources([
         'user' => UsuarioController::class,
         
     ]);
    // Route::apiResources(['user' => 'UsuarioController']);
    // Route::get('rol', 'UsuarioController@roles');
    // // //roles
    Route::apiResources(['roles' => 'RoleController']);
    Route::get('permi', 'RoleController@permi');
    Route::apiResources(['permisos' => 'PermisosController']);
    Route::get('permisouser','PermisosController@permisosrol');
    Route::get('roleuser','RoleController@permisos');
   
    Route::get('producto/search-items', 'ProductoController@searchItems');

    //Route::get('permi', 'RoleController@permisos');
    Route::get('perfil', 'UsuarioController@profile');
    Route::put('profile', 'UsuarioController@updateProfile');

    Route::get('agensupervisados/{id}','UsuarioController@veragentes');
    Route::get('veragentes', 'UsuarioController@agenteslibres');
    Route::post('regagente', 'UsuarioController@registraagente');
    Route::get('verusuario', 'UsuarioController@verdatosusuario');
    Route::get('notificaciones', 'UsuarioController@notificaciones');

    Route::get('buscarcuotasvencidas', 'CronogramaController@buscarVencidas');
    // //periodos
    //Route::apiResources(['producto' => 'ProductoController']);
    Route::apiResources(['categoria' => 'CategoriaController']);

    // CLIENTE
    Route::apiResources(['clientes' => 'ClienteController']);
    Route::get('cliente/tablas', 'ClienteController@tablas');
    Route::get('documents/search/customers', 'ClienteController@searchCustomers');
    //comprobante
    Route::apiResources(['comprobante' => 'ComprobanteController']);
    Route::post('comprobante/buscar/tipocomprobante', 'ComprobanteController@loadComprobantesByTipo');
    Route::post('comprobante/crearPDF', 'ComprobanteController@crearPdf');
    Route::post('comprobantebyparams', 'ComprobanteController@getComprobantesByParams');
    Route::post('comprobantediario', 'ComprobanteController@getComprobantesDiario');
    Route::post('comprobantemensual', 'ComprobanteController@getComprobantesMensual');
    Route::get('comprobanteenvio', 'ComprobanteController@enviarSunat');
    Route::post('pagacomprobante', 'ComprobanteController@pagocompro');
    Route::post('cuotasvencom', 'ComprobanteController@cuotasVencidasCompro');

    
    

    Route::apiResources(['cuota' => 'CuotaController']);
    Route::apiResources(['emisor' => 'EmisorController']);
    Route::apiResources(['moneda' => 'MonedaController']);
    //Producto
    Route::apiResources(['productos' => 'ProductoController']);
    Route::get('producto/tablas', 'ProductoController@tablas');
    Route::get('producto/mostrar', 'ProductoController@mostrar');

    Route::apiResources(['serie' => 'SerieController']);
    Route::apiResources(['tablapara' => 'TablaparametricaController']);
    Route::apiResources(['tipoafecta' => 'TipoafectacionController']);
    Route::apiResources(['tipocomprobante' => 'TipocomprobanteController']);
    Route::get('tipodocumento/motivocreditodebito', 'TipocomprobanteController@motivoNotaCredito');
    Route::apiResources(['tipodocumento' => 'TipodocumentoController']);
    Route::apiResources(['unidad' => 'UnidadController']);
    //Almacem
    Route::apiResources(['almacen' => 'AlmacenController']);
    Route::get('almacen/tablas', 'AlmacenController@tablas');
    Route::get('almacen/mostrar', 'AlmacenController@mostrar');
    //categoria
    Route::apiResources(['categoria' => 'CategoriaController']);
    Route::get('categoria/tablas', 'CategoriaController@tablas');
    Route::get('categoria/mostrar', 'CategoriaController@mostrar');
    //Otros
    Route::apiResources(['tipopabe' => 'TipopabellonController']);
    Route::apiResources(['tipodocu' => 'TipodocumentoController']);
    Route::apiResources(['tipocompro' => 'TipocomprobanteController']);
    Route::apiResources(['tipoafecta' => 'TipoafectacionController']);
    Route::apiResources(['tiponocredito' => 'TiponotacreditoController']);
    Route::apiResources(['tiponodebito' => 'TiponotadebitoController']);
    Route::apiResources(['unidad' => 'UnidadController']);
    Route::apiResources(['serie' => 'SerieController']);

    Route::get('buscarruc/{numerodoc}', 'ClienteController@buscarruc');

    Route::apiResources(['listas' => 'ListaController']);
    //delista
    Route::apiResources(['delista' => 'DelistaController']);
    //tasa interes
    Route::apiResources(['tasaint' => 'TasainteresController']);
    //RCI
    Route::apiResources(['relaci' => 'RelacionCIController']);
    //RDI
    Route::apiResources(['reladi' => 'RelacionDIController']);
    //Plasos
    Route::apiResources(['plasos' => 'PlazosController']);
    //Clientes
    Route::apiResources(['clientes' => 'PersonaController']);
    route::get('buscardni','PersonaController@buscardni');
    //Empresa
    Route::apiResources(['empresas' => 'EmpresasController']);
    route::get('buscarruc','EmpresasController@buscarruc');
    route::get('listarsector/{id}','EmpresasController@getbyidandsector');
    route::get('esinconvenio','EmpresasController@empsinconvenio');
    //Creditos
    Route::apiResources(['creditos' => 'CreditosController']);
    route::get('buscarcli','CreditosController@buscarcliente');
    route::get('creditospendientes','CreditosController@creditospen');
    route::get('buscarcredito/{id}','CreditosController@buscarcredito');
    route::get('aprocredito/{id}','CreditosController@aprobarcredito');
    route::post('actualizarcredito/{id}','CreditosController@actualizarcredito');
    route::get('getnumreenganche','CreditosController@getNumReenganche');
    Route::apiResources(['correlativo' => 'CorrelativoController']);

    //AMORTIZACION
    route::get('getclientesdeudores','CreditosController@getClientesDeudores');
    route::get('getcuotasforcredito/{id}','CreditosController@getCuotasPendientesForIdCredito');
    route::post('amortizar','CreditosController@amortizar');
    route::post('guardaramortizacion','CreditosController@guardarAmortizacion');
    
    // Cronograma
    Route::apiResources(['cronograma' => 'CronogramaController']);
    route::get('buscarcronograma/{id}','CronogramaController@getbyidcliente');
    route::post('detallecrono','CronogramaController@detallecronograma');
    route::post('detallecancela','CronogramaController@detallecancela');
    route::get('viewcrono/{id}','CronogramaController@viewcrono');
    route::get('getcuotaspagos','CronogramaController@getCuotas');

   // PAGOS
   Route::apiResources(['pago' => 'PagoController']);
   route::get('burcarpordni/{id}','PersonaController@burcarpordni');
   route::post('imprimirvoucher','PagoController@print');
   route::post('pagarlista','PagoController@payfordni');
   route::post('voucherronald','PagoController@printronald');
   route::post('pagosbh','PagoController@buscarenplani');   
   route::post('createpagosmasivos','PagoController@storeMasivo');
   route::post('validarpagos','PagoController@validarPago');

    //FORMATOS

    route::get('getformato/{id}','PersonaController@getFormatos');    

    // CRONOGRAMAS
    Route::apiResources(['cuota' => 'CuotaController']);
    Route::post('calctcea', 'CuotaController@calctcea');

    Route::apiResources(['asignameta' => 'AsignametaController']);
    Route::apiResources(['hojaruta' => 'HojarutaController']);
    route::get('deldetallehoja','HojarutaController@eliminardetalle');

    Route::apiResources(['convenio' => 'ConveniosController']);
    // INICIO
    Route::get('parainicio','HomeController@datosinicio');
    Route::get('creditomes','HomeController@creditomes');    
    Route::get('metacreditos','HomeController@metacreditomess');   
    Route::get('metausu','HomeController@metames');  

    //Gerente Inicio
    Route::get('parainicioger','HomeController@datosinicioger');
    Route::get('creditomesger','HomeController@creditomesger');  
    Route::get('creditomesana','HomeController@creditomesanalista');   
    Route::get('pagoscreditoger','HomeController@pagocreditomesger'); 
    Route::get('creditoporcentaje','HomeController@creditoporcenanalista');

    Route::get('parainiciosupe','HomeController@datosiniciosuper');
    Route::get('creditomessupe','HomeController@creditomessuper');  
    Route::get('creditomesanasupe','HomeController@creditomesanalistasuper');   
    Route::get('pagoscreditosupe','HomeController@pagocreditomessuper'); 
    Route::get('creditoporsupe','HomeController@creditoporcenanalistasuper');
     //Inicio de Tesorero
    Route::get('parainicioteso','HomeController@datosinicioteso');
    Route::get('creditomesteso','HomeController@creditomesteso');    
    Route::get('metamesteso','HomeController@metamesteso');   
    Route::get('reportecuotano','CreditosController@cuotasnopagas');

    // segururodes
    Route::apiResources(['segurodes' => 'SegurodesController']);
    Route::apiResources(['cancelacred' => 'CancelacredController']);    
    Route::post('subirvoucher', 'CancelacredController@subirvoucher');
    Route::post('actuacancel', 'CancelacredController@actualizar');


    //TESORERIA
    Route::get('getcredcancelteso','CancelacredController@getcancelteso'); 
    Route::post('validarpagoteso','CancelacredController@validarPago'); 
    route::post('calcularcredito','CreditosController@calculomonto');
    route::post('addcp','CreditosController@addcp');
    Route::get('periodos','HomeController@periodos'); 
    // PRESUPUESTO
    route::get('credwithanalista','CreditosController@credwithanalista');
    Route::apiResources(['calificacion' => 'CalificacionController']);

    //COMISIONES
    route::get('comcoutaatrasada','ComisionesController@getCuotasAtrasadasByDate');
    route::get('comcumplimientometa','ComisionesController@getCumplimientoMeta');
    route::get('porcentajesatisfaccion','ComisionesController@getSatisfaccion');
    route::get('calcularcomisiones','ComisionesController@calcularComisiones');

    //REPORTES
    route::get('controlventascreditos','CreditosController@controlVentasCredito');
    route::get('reportreenganche','CreditoController@reportReenganches');
    route::get('mostrarpagos','PagoController@verpagos');

    route::get('reportemensual','PagoController@getReportMensual');
    route::get('reportemensualamo','PagoController@getReportMensualamo');
    route::get('reportedetallado','PagoController@getReportDetallado');
    route::get('reportdeudarestante','PagoController@getReportDeudaRestante');



    //CUOTAS VENCIDAS
    

    route::post('getcuotasvencidas','CuotaController@getcuotasvencidas');
    route::post('guardar','CreditosController@guardarobserva');
    route::post('savereportdeudarestante','CreditosController@guardarreporte');
    

    

});
