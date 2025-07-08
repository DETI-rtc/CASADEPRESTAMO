<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use App\Models\Comprobante;
use App\Models\Detalle;
use App\Models\Cuota;
use App\Models\Credito;
use App\Models\CreditoDetalle;
use Illuminate\Http\Request;
use DB;
use QrCode;
use Carbon\Carbon;
use Greenter\XMLSecLibs\Sunat\SignedXml;
use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\XMLSecLibs\Certificate\X509ContentType;
use ZipArchive;
use DOMDocument;
use Barryvdh\DomPDF\Facade\Pdf;

use Luecano\NumeroALetras\NumeroALetras;

class ComprobanteController extends Controller
{

    public function index(){
        $comprobante = Comprobante::with('detalle','cuota','serieRelacion')->where('estado',1)->whereYear('fecha_emision',2022)->orderBy('id','desc')->get();
        return $comprobante;
    }

    public function create(){
        //
    }

    public function store(Request $request){
        //DD($request);
        try {
            $customer = DB::table('persona')->where('id', $request->customer_id)->get()->first();
            $tipo_comprobante = DB::table('tipo_comprobante')->where('id',$request->document_type_id)->get()->first();

            DB::beginTransaction();
            $comprobante = new Comprobante;
           // DD($request);
            $comprobante->emisor_id = 1;
            $comprobante->tipo_comprobante_id = $tipo_comprobante->tipo_serie;
            $comprobante->serie_id = $request->series_id;
            $comprobante->serie = $request->serie;
            $comprobante->correlativo = $request->serie_correlativo;
            $comprobante->forma_pago = $request->forma_pago;
            $comprobante->fecha_emision = $request->date_of_issue;
            $comprobante->hora_emision = $request->time_of_issue;
            $comprobante->fecha_vencimiento = $request->date_of_due;
            $comprobante->moneda_id = $request->currency_type_id;
            $comprobante->op_gravadas = $request->total_taxed;
            $comprobante->op_exoneradas = $request->total_exonerated;
            $comprobante->op_inafectas = $request->total_unaffected;
            $comprobante->igv = $request->total_igv;
            $comprobante->total = $request->total;
            $comprobante->cliente_id = $request->customer_id;
            $comprobante->tipo_comprobante_ref_id = $request->tipo_comprobante_ref_id;
            $comprobante->serie_ref = $request->serie_ref;
            $comprobante->correlativo_ref = $request->correlativo_ref;
            $comprobante->codmotivo = $request->codmotivo;
            $comprobante->iddifunto = $request->iddifunto;
            $comprobante->id_tipodoccliente = 1;
            $comprobante->cliente = $customer->nom_comp;
            $comprobante->numerodoc = $customer->dni;
            $comprobante->direccion = $customer->direccion;
            $comprobante->idcuota = $request->idcuota;
          //   $comprobante->nombrexml = ;
          //   $comprobante->xmlbase64 = ;
          //   $comprobante->hash = ;
          //   $comprobante->cdrbase64 = ;
          //   $comprobante->codigo_sunat = ;
          //   $comprobante->mensaje_sunat = ;
          //   $comprobante->estado_comprobante = ;
            
          //   $comprobante->emisor_id = 1;
          //   $comprobante->tipo_comprobante=$request->get('tipo_comprobante');
          //   $comprobante->serie_id=$request->get('serie_id');
          //   $comprobante->serie=$request->get('serie');
          //   $comprobante->correlativo=$request->get('correlativo');
          //   $comprobante->forma_pago=$request->get('forma_pago');
          //   $comprobante->fecha_emision=$request->get('fecha_emision');
          //   $comprobante->fecha_vencimiento=$request->get('fecha_vencimiento');
          //   $comprobante->moneda_id=$request->get('moneda_id');
          //   $comprobante->op_gravadas=$request->get('op_gravadas');
          //   $comprobante->op_exoneradas=$request->get('op_exoneradas');
          //   $comprobante->op_inafectas=$request->get('op_inafectas');
          //   $comprobante->igv=$request->get('igv');
          //   $comprobante->total=$request->get('total');
          //   $comprobante->cliente_id=$request->get('cliente_id');
          //   $comprobante->tipo_comprobante=$request->get('tipo_comprobante');
          //   $comprobante->serie_ref=$request->get('serie_ref');
          //   $comprobante->correlativo_ref=$request->get('correlativo_ref');
          //   $comprobante->codmotivo=$request->get('codmotivo');
            // $comprobante->nombrexml=$request->get('nombrexml');
            // $comprobante->xmlbase64=$request->get('xmlbase64');
            // $comprobante->codigo_sunat=$request->get('codigo_sunat');
            // $comprobante->mensaje_sunat=$request->get('mensaje_sunat');
            // $comprobante->estado_comprobante=$request->get('estado_comprobante');
            $comprobante->save();  
    
            $detalle = $request->items;
            foreach ($detalle as $key => $deta) {
                
                $det = new Detalle;
                $det->comprobante_id = $comprobante->id;
                $det->item = $key+1;
                $det->producto_id = $deta['item_id'];
                $det->cantidad = $deta['quantity'];
                $det->valor_unitario = $deta['unit_value'];
                $det->precio_unitario = $deta['unit_price'];
                $det->igv = $deta['total_igv'];
                $det->porcentaje_igv = $deta['percentage_igv'];
                $det->valor_total = $deta['total_value'];
                $det->importe_total = $deta['total'];
                $det->producto = $deta['item']['nombre'];
                $det->descripcion = $deta['item']['descripcion'];
    
                // $det = new Detalle;
                // $det->comprobante_id = $comprobante->id;
                // $det->item = $key+1;
                // $det->producto_id = $deta['producto_id'];
                // $det->cantidad = $deta['cantidad'];
                // $det->valor_unitario = $deta['valor_unitario'];
                // $det->precio_unitario = $deta['precio_unitario'];
                // $det->igv = $deta['igv'];
                // $det->porcentaje_igv = $deta['porcentaje_igv'];
                // $det->valor_total = $deta['valor_total'];
                // $det->importe_total = $deta['importe_total'];
                $det->save();
            }
            foreach ($request->payments as $key => $deta) {
                $det = new Cuota;
                $det->comprobante_id = $comprobante->id;
                $det->numero = $deta['description'];
                $det->importe = $deta['payment'];
                $det->fecha_vencimiento = $deta['date_of_payment'];
                $det->estado = 'N';
                $det->save();
            }
            DB::table('serie')->where('id',$request->series_id)->update(['correlativo'=> $request->serie_correlativo+1]);

            // $credito = CreditoDetalle::find($request->idcuota);
            // $saldo = $credito->monto_deuda - ($request->total + $credito->monto_pagado);
            // $estado = 1;
            // if ($saldo > 0) {
            //     $estado = 1;
            // } else {
            //     $estado = 2;
            // }
            // $credito->update(['fecha_pagado'=> $request->date_of_issue, 'monto_pagado' => $request->total + $credito->monto_pagado, 'saldo' => $saldo, 'estado' => $estado]);
            // $count = CreditoDetalle::where('idcredito', $request->idcredito)->where('estado',1)->count();
            // if ($count == 0) {
            //     Credito::find($request->idcredito)->update(['estado'=> 2]);
            // }
            // DB::commit();
            // return $comprobante;

            $this->crearQr('20133670191', $tipo_comprobante->tipo_serie, $request->serie,$request->serie_correlativo,$request->total_igv,$request->total_value,$request->total,$request->date_of_issue,$customer->dni);

            $motivonota= null;
            $documentoref = null;
            if ($tipo_comprobante->tipo_serie == '07') {
                $motivonota = DB::table('tipo_notacredito')->where('id',$request->codmotivo)->get()->first();
            } else if($tipo_comprobante->tipo_serie == '08') {
                $motivonota = DB::table('tipo_notadebito')->where('id',$request->codmotivo)->get()->first();
            }
            if ($request->tipo_comprobante_ref_id != null) {                
                $documentoref = DB::table('comprobante')->where('serie',$request->serie_ref)->where('correlativo',$request->correlativo_ref)->first();
              // DD($documentoref);
                $fecha_ref = date("d/m/Y",strtotime($documentoref->fecha_emision));
            }


            $emision = date("d/m/Y",strtotime($request->date_of_issue));
            $documento;
            $customer->tipo_documento_id=1;
            switch ($customer->tipo_documento_id) {
                case 1:
                    $documento = 'DNI';
                    break;
                case 4:
                    $documento = 'CARNET DE EXTRANJERÍA';
                    break;
                case 6:
                    $documento = 'RUC';
                    break;
                case 7:
                    $documento = 'PASAPORTE';
                    break;
                default:
                    $documento = 'SIN DOCUMENTO';
                    break;
            }
            $total = number_format($request->total, 2, '.', ',');
            $subtotal = number_format($request->total_value, 2, '.', ',');
            $igv = number_format($request->total_igv, 2, '.', ',');
            $formatter = new NumeroALetras();

            $total_texto = $formatter->toInvoice($request->total, 2,'SOLES');

            // if ($request->iddifunto != null) {
            //     $difunto = DB::table('cem_difunto')->where('id', $request->iddifunto)->get()->first();
            //     $fallecimiento = date("d/m/Y",strtotime($difunto->fecfalle));
            //     $entierro = date("d/m/Y h:i:s A",strtotime($difunto->fechorafalle));
            //     $pabellon = DB::table('cem_pabellon')->where('id', $difunto->codpabe)->get()->first();
            //     $cara = substr($difunto->codnicho,5,2);
            //     $fila = substr($difunto->codnicho,7,1);
            //     $columna = substr($difunto->codnicho,8,3);
            // }

            // CREAR PDF
            $html = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='utf-8'>
                <style>
                    body {
                      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                    }
                    .subtitulo, .titulo {
                        font-weight: bold;
                        font-size: 18px;
                    }
                    .cabecera1 {
                        display: block;
                        text-align: center;
                    }
                    .cabecera2 {
                        text-align: center;
                        border: solid 1px #000;
                        border-radius: 10px;
                        font-weight: bold;
                    }
                    .direccion, .direccion2 {
                        font-size: 12px;
                    }
                </style>
            </head>
            <body>
                <table>
                    <tbody>
                        <tr>
                            <td width='10%'>
                                <img src='img/casa.png' width='15%'>
                            </td>
                            <td width='60%'>
                                <div class='cabecera1'>
                                <!---<span class='titulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>                                    
                                    <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br>-->
                                    <span class='titulo'>CASA DE PRESTAMOS HUANCAYO</span> <br>
                                    <span class='subtitulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>
                                    <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br>
                                    <span class='direccion2'>Jr. Junín 253 - Huancayo - Junín</span><br>                                     
                                </div>
                            </td>
                            <td width='30%'>
                                <div class='cabecera2' style='padding: 0.3rem'>
                                    <span>R.U.C. 20133670191<span><br>
                                    <span>$tipo_comprobante->nombre</span><br>
                                    <span>$request->serie-$request->serie_correlativo</span><br>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table style='width: 100%; font-size:12px; padding: 0.3rem;'>
                        <thead>
                            <th colspan='2' width='50%'></th>
                            <th colspan='2' width='50%'></th>   
                        </thead>
                        <tbody>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Fecha de emisión</td>
                                <td width='40%'>: $emision</td>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Tipo de Pago</td>
                                <td width='20%'>: $request->forma_pago</td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold;'>$documento</td>
                                <td width='40%'>: $customer->dni</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($tipo_comprobante->tipo_serie == '08' || $tipo_comprobante->tipo_serie == '07') {
                                    $html.="Motivo";
                                }
                            $html.="
                            </td>
                                <td width='20%'>";
                                if ($tipo_comprobante->tipo_serie == '08' || $tipo_comprobante->tipo_serie == '07') {
                                    $html.=": $motivonota->descripcion";
                                }
                            $html.="
                            </td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Señor(es)</td>
                                <td width='40%'>: $customer->nom_comp</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.="Doc. Afectación";
                                }
                            $html.="
                                </td>
                                <td width='20%'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.=": $request->serie_ref-$request->correlativo_ref";
                                }
                            $html.="
                                </td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Dirección</td>
                                <td width='40%'>: $customer->direccion</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.="Fecha Documento";
                                }
                            $html.="
                                </td>
                                <td width='20%'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.=": $fecha_ref";
                                }
                            $html.="
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>";
               
                $html.="
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table width='100%' style='font-size:12px; padding: 0.3rem;'>
                        <thead>
                            <th width='10%'>Cant.</th>
                            <th width='60%'>Descripción</th>
                            <th width='15%'>P. Unit</th>
                            <th width='15%'>Importe</th>
                        </thead>
                        <tbody>
                            <tr><td style='border-top: 2px solid #000; padding-bottom: 0.3rem;' colspan='10'></td></tr>";

            foreach ($request->items as $i) {
                $producto = $i['item']['nombre'];   
                $quantity = number_format($i['quantity'], 2, '.', ',');
                $unit_price = number_format($i['unit_price'], 2, '.', ',');
                $total_unit = number_format($i['total'], 2, '.', ',');
                $html.="<tr>
                            <td style='text-align: center;'>$quantity</td>
                            <td>$producto</td>
                            <td style='text-align: right;'>$unit_price</td>
                            <td style='text-align: right;'>$total_unit</td>
                        </tr>";
            }
            $html.="<tr><td style='border-bottom: 2px solid #000; padding-top: 0.3rem;' colspan='10'></td></tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>SUB TOTAL</td>
                            <td style='text-align: right;'>$subtotal</td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>I.G.V. 18%</td>
                            <td style='text-align: right;'>$igv</td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;font-weight: bold;'>IMPORTE TOTAL</td>
                            <td style='text-align: right;'>$total</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                <table width='100%' style='font-size:12px; padding: 0.3rem;'>
                    <thead>
                        <th width='50%'></th>
                        <th width='50%'></th>
                    </thead>    
                    <tbody>
                        <tr>
                            <td style='text-align:left; vertical-align: top;'><span style='font-weight: bold;'>IMPORTE EN LETRAS</span>: $total_texto</td>
                            <td style='text-align: right;'>
                                <img src='img/qr/$tipo_comprobante->tipo_serie-$request->serie-$request->serie_correlativo.png' width='30%'>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style='text-align: center; margin-top:0.2rem'><small style='font-size:8px;'>Representación impresa de la factura electrónica, podrá ser consultada en: <a href='https://ww3.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm' target='_blank' rel='noopener noreferrer'>http://sbhuancayo.website/consulta-comprobante</a></small></div>
            </body>
            </html>";


            
           

            PDF::loadHTML($html)->setPaper('A4')->save(public_path("docs/a4/$tipo_comprobante->tipo_serie-$request->serie-$request->serie_correlativo.pdf"));
            //PDF::loadHTML($htmlticket)->setPaper(array(0,0,230,800))->save(public_path("docs/ticket/$tipo_comprobante->tipo_serie-$request->serie-$request->serie_correlativo.pdf"));
            // // FIN DE CREAR PDF

            // // DD('creado', number_format($request->total, 2, '.', ','));
            // // ENVIAR FACTURA
            // $emisor = array(
            //     "tipodoc" => "6",
            //     "ruc" => "20133670191",
            //     "razon_social" => "SOCIEDAD DE BENEFICENCIA DE HUANCAYO",
            //     "nombre_comercial" => "SOCIEDAD DE BENEFICENCIA DE HUANCAYO",
            //     "pais" => "PE",
            //     "departamento" => "JUNIN",
            //     "provincia" => "HUANCAYO",
            //     "distrito" => "HUANCAYO",
            //     "direccion" => "JR. CUZCO  NRO 1576 JUNIN HUANCAYO",
            //     "ubigeo" => "120101",
            //     "usuario_emisor" => "GALA1024",
            //     "clave_emisor" => "HQJZsOol"
            //     );
            
            // $cliente = array(
            //             "tipo_documento" => $customer->tipo_documento_id,
            //             "ruc" => $customer->dni,
            //             "razon_social" => $customer->nom_comp,
            //             "direccion" => $customer->direccion,
            //             "pais" => 'PE',
            //             );
            // $total = 0;
            // $cuotas = array();

            // foreach ($request->payments as $i) {
            //     $total += $i['payment'];
            //     $cuotas[] = array(
            //         "numero" => "00".$i['description'],
            //         "vencimiento" => $i['date_of_payment'],
            //         "importe" => $i['payment'],
            //     );
            // }
            // // $letras = $formatter->toInvoice($request->total, 2);
            // // DD($letras, $letrasgirado);
            // $serie_correlativo=null;
            // if ($request->tipo_comprobante_ref_id != null) {
            //     $serie_correlativo = $request->serie_ref.'-'.$request->correlativo_ref;
            // }
            // $cabecera = array(
            //             "tipo_operacion"  => "0101",
            //             "tipo_comprobante" => $tipo_comprobante->tipo_serie,
            //             "moneda"          => $request->currency_type_id,
            //             "serie"           => $request->serie,
            //             "correlativo"     => $request->serie_correlativo,
            //             "serieycorrelativo" => $serie_correlativo,
            //             "total_op_gravadas" => $request->total_taxed,
            //             "igv"          => $request->total_igv,
            //             "icbper"       => 0,
            //             "total_op_exoneradas"=> $request->total_exonerated,
            //             "total_op_inafectas" => $request->total_unaffected,
            //             "total_antes_impuestos" => $request->total_value,
            //             "total_impuestos"    => $request->total_igv,
            //             "total_despues_impuestos"=> $request->total,
            //             "total_a_pagar"      =>$request->total,
            //             "fecha_emision"      =>$request->date_of_issue,
            //             "hora_emision"    =>$request->time_of_issue,
            //             "fecha_vencimiento" =>$request->date_of_due,
            //             "forma_pago"      => $request->forma_pago,
            //             "monto_credito"   => $total,
            //             "anexo_sucursal"  => "0000",
            //             "total_texto" => $total_texto,
            //             "serie_ref" => $request->serie_ref,
            //             "correlativo_ref" => $request->correlativo_ref,
            //             "codmotivo" => $request->codmotivo,
            //             "descripcion"=> $request->descripcion_motivo,
            //             "tipo_comprobante_ref_id" => $request->tipo_comprobante_ref_id,
            //             );
            
            // // $cuotas = array();
            
            // // $cuotas[] = array(
            // //             "numero"    => "001",
            // //             "importe"   => 150.00,
            // //             "vencimiento"=> "2021-10-30"
            // //         );
            
            // // $cuotas[] = array(
            // //             "numero"    => "002",
            // //             "importe"   => 50.00,
            // //             "vencimiento"=> "2021-11-30"
            // //         );
            
            // $cabecera['cuotas'] = $cuotas;
            // $items = array();
            // foreach ($request->items as $i) {
            //     if ($i['affectation_igv_type_id'] == 10) {
            //         $codigos = array("S",$i['affectation_igv_type_id'], '1000','IGV','VAT');
            //     } else if ($i['affectation_igv_type_id'] == 30) {
            //         $codigos = array("S",$i['affectation_igv_type_id'], '9998','INA','FRE');
            //     } else {
            //         $codigos = array("S",$i['affectation_igv_type_id'], '9997','EXO','VAT');
            //     }
            //     $items[] = array(
            //         "item" =>  $i['item_id'],
            //         "cantidad" =>  $i['quantity'],
            //         "unidad" =>  $i['item']['unidad_id'],
            //         "nombre" =>  $i['item']['nombre'],
            //         "valor_unitario" =>  round($i['unit_value'],2),
            //         "precio_lista" =>  round($i['total'],2),
            //         "valor_total" =>  round($i['total_value'],2),
            //         "igv"  =>  round($i['total_igv'],2),
            //         "icbper"  =>  0.00,
            //         "factor_icbper"  =>  0.00,
            //         "total_antes_impuestos" =>  round($i['total_value'],2),
            //         "total_impuestos" =>  round($i['total_igv'],2),
            //         "porcentaje_igv" =>  round($i['percentage_igv']),
            //         "codigos" =>  $codigos,
            //         "tipo_precio" => "01",
            //     );
            // }
            // // $items =$request->items;

            // $nombrexml = $emisor['ruc']."-".$cabecera['tipo_comprobante']."-".$cabecera['serie']."-".$cabecera['correlativo'];
            
            // $doc = new DOMDocument();
            // $doc->formatOutput = FALSE;
            // $doc->preserveWhiteSpace = TRUE;
            // $doc->encoding = 'utf-8';
            // if ($cabecera['tipo_comprobante'] == "03" || $cabecera['tipo_comprobante'] == "01") {
            //     $this->CrearXMLFactura($nombrexml,$emisor, $cliente, $cabecera, $items);
            // } else if($cabecera['tipo_comprobante'] == "07") {
            //     $this->CrearXMLNotaCredito($nombrexml, $emisor, $cliente, $cabecera, $items);
            // } else if($cabecera['tipo_comprobante'] == "08") {
            //     $this->CrearXMLNotaDebito($nombrexml, $emisor, $cliente, $cabecera, $items);
            // }
            
            // $flg_firma = "0";
            // $ruta = public_path('storage/sinfirmar/'.$nombrexml.'.xml');
            // $ruta2 = public_path('storage/firmado/'.$nombrexml.'.xml');
            // $ruta3 = public_path('storage/firmado/'.$nombrexml);
            // $ruta4 = public_path('storage/cdr/');
            // $rutaerror = public_path('storage/sunatRpta/');
                
            // $ruta_firma = public_path('storage/Beneficencia2022.pem');
            // $pass_firma = "wadsp8.323dZx&";
                
    
            // $signer = new SignedXml();
            // $signer->setCertificateFromFile($ruta_firma);
            
            // $xmlSigned = $signer->signFromFile($ruta);
            
            // file_put_contents($ruta2, $xmlSigned);
    
            // $zip = new ZipArchive();
            // $nombrezip = $nombrexml.".ZIP";
            // $rutazip = $ruta3.".ZIP";
    
            // if($zip->open($rutazip,ZIPARCHIVE::CREATE)===true){
            //     $zip->addFile($ruta2, $nombrexml.'.xml');
            //     $zip->close();
            // }
            // //PASO 04
            // //PREPARAR EL ENVÍO DEL XML
            // $contenido_del_zip = base64_encode(file_get_contents($rutazip));
            // $xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
            //         xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" 
            //         xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            //         <soapenv:Header>
            //             <wsse:Security>
            //                 <wsse:UsernameToken>
            //                     <wsse:Username>'.$emisor['ruc'].$emisor['usuario_emisor'].'</wsse:Username>
            //     <wsse:Password>'.$emisor['clave_emisor'].'</wsse:Password>
            //                 </wsse:UsernameToken>
            //             </wsse:Security>
            //     </soapenv:Header>
            //     <soapenv:Body>
            //     <ser:sendBill>
            //         <fileName>'.$nombrezip.'</fileName>
            //         <contentFile>'.$contenido_del_zip.'</contentFile>
            //     </ser:sendBill>
            //     </soapenv:Body>
            // </soapenv:Envelope>';
    
            //     //PASO 05
            // //ENVÍO DEL CPE A WS DE SUNAT
            // //https://www.sunat.gob.pe/ol-ti-itcpfegem/billService
            // //https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService

            // $user = auth('sanctum')->user();
            // $role = $user->getRoleNames();
            // // DD($role);
            // $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            // // $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            // $header = array(
            //             "Content-type: text/xml; charset=\"utf-8\"",
            //             "Accept: text/xml",
            //             "Cache-Control: no-cache",
            //             "Pragma: no-cache",
            //             "SOAPAction: ",
            //             "Content-lenght: ".strlen($xml_envio)
            //         );
            // $ch = curl_init();
            // curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            // curl_setopt($ch,CURLOPT_URL,$ws);
            // curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            // curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
            // curl_setopt($ch,CURLOPT_TIMEOUT,30);
            // curl_setopt($ch,CURLOPT_POST,true);
            // curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
            // curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
            // curl_setopt($ch, CURLOPT_CAINFO, public_path('storage/cacert.pem'));
            // $response = curl_exec($ch);
            // //PASO 06 
            // // OBTENEMOS RESPUESTA (CDR)
            // $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            // $responseCode='';
            // $mensaje ='';
            // // DD($response,$httpcode);
            // //DD($httpcode);
            // if($httpcode == 200){
            //     $doc = new DOMDocument();
            //     $doc->loadXML($response);
            //     // DD($responde);
            //     if(isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)){
            //         $cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue;
            //         $cdr = base64_decode($cdr);
            //         // DD($cdr);
            //         file_put_contents($ruta4."R-".$nombrezip, $cdr);
            //         $zip = new ZipArchive;
            //         if($zip->open($ruta4."R-".$nombrezip)===true){
            //             $zip->extractTo($ruta4.'R-'.$nombrexml);
            //             $zip->close();
            //         }
            //         $docCDR = new DOMDocument();
            //         $cdrContent = file_get_contents($ruta4.'R-'.$nombrexml.'/'.'R-'.$nombrexml.'.XML');
            //         $docCDR->loadXML($cdrContent);
            
            //         $responseCode = $docCDR->getElementsByTagName("ResponseCode")->item(0)->nodeValue;
            //         if($responseCode==0){
            //             $resultado = "FACTURA APROBADA";   
            //         }else{
            //             $resultado = "FACTURA RECHAZADA CON CODIGO DE ERROR:".$responseCode;
            //         }
            //         $descri= $docCDR->getElementsByTagName("Description")->item(0)->nodeValue;
            //         $codigo='';
            //         $mensaje='';
            //     }else{
            //         $descri=''; 
            //         $resultado='';
            //        // file_put_contents($rutaerror."R-".$nombrezip, $doc);
            //         $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
            //         $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
            //         DD($codigo,$mensaje);
            //     }
            // } else {
            //     DD(curl_error($ch));
            //     // echo "Problema de conexión";
            // }
           // DB::table('comprobante')->where('id',$comprobante->id)->update(['nombrexml'=>$nombrexml,'codigo_sunat'=>$responseCode,'mensaje_sunat'=>$mensaje,'estado_comprobante'=>1]);
           // curl_close($ch);
            // DD($codigo.": ".$mensaje."  "."d ".$descri." f ".$resultado); 
            // FIN ENVIAR DE FACTURA

            DB::commit();
            return $comprobante;
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function enviarSunat(Request $request){
        $comprobantes = Comprobante::with('detalle','cuota')->where('codigo_sunat', '!=','0')->get();
        // DD($comprobantes);
        foreach ($comprobantes as $com) {
            // $customer = DB::table('cliente')->where('id', $com->cliente_id)->get()->first();
            $tipo_comprobante = DB::table('tipo_comprobante')->where('id',$com->tipo_comprobante_id)->get()->first();
    
            // DB::table('serie')->where('id',$request->series_id)->update(['correlativo'=> $request->serie_correlativo+1]);
    
            $total_value = $com->op_gravadas + $com->op_exoneradas + $com->op_inafectas;
            $total_value = round($total_value,2);
            $this->crearQr('20133670191', $tipo_comprobante->tipo_serie, $com->serie,$com->correlativo,$com->igv,$total_value,$com->total,$com->fecha_emision,$com->numerodoc);
    
            $motivonota= null;
            $documentoref = null;
            if ($tipo_comprobante->tipo_serie == '07') {
                $motivonota = DB::table('tipo_notacredito')->where('id',$com->codmotivo)->get()->first();
            } else if($tipo_comprobante->tipo_serie == '08') {
                $motivonota = DB::table('tipo_notadebito')->where('id',$com->codmotivo)->get()->first();
            }
            if ($com->tipo_comprobante_ref_id != null) {                
                $documentoref = DB::table('comprobante')->where('serie',$com->serie_ref)->where('correlativo',$com->correlativo_ref);
                $fecha_ref = date("d/m/Y",strtotime($documentoref->fecha_emision));
            }
    
    
            $emision = date("d/m/Y",strtotime($com->fecha_emision));
            $documento;
            switch ($com->id_tipodoccliente) {
                case 1:
                    $documento = 'DNI';
                    break;
                case 4:
                    $documento = 'CARNET DE EXTRANJERÍA';
                    break;
                case 6:
                    $documento = 'RUC';
                    break;
                case 7:
                    $documento = 'PASAPORTE';
                    break;
                default:
                    $documento = 'SIN DOCUMENTO';
                    break;
            }
            $total = number_format($com->total, 2, '.', ',');
            $subtotal = number_format($total_value, 2, '.', ',');
            $igv = number_format($com->igv, 2, '.', ',');
            $formatter = new NumeroALetras();
    
            $total_texto = $formatter->toInvoice($com->total, 2,'SOLES');
    
            if ($com->iddifunto != null) {
                $difunto = DB::table('cem_difunto')->where('id', $com->iddifunto)->get()->first();
                $fallecimiento = date("d/m/Y",strtotime($difunto->fecfalle));
                $entierro = date("d/m/Y h:i:s A",strtotime($difunto->fechorafalle));
                $pabellon = DB::table('cem_pabellon')->where('id', $difunto->codpabe)->get()->first();
                $cara = substr($difunto->codnicho,5,2);
                $fila = substr($difunto->codnicho,7,1);
                $columna = substr($difunto->codnicho,8,3);
            }
    
            // CREAR PDF
            $html = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='utf-8'>
                <style>
                    body {
                        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                    }
                    .subtitulo, .titulo {
                        font-weight: bold;
                        font-size: 18px;
                    }
                    .cabecera1 {
                        display: block;
                        text-align: center;
                    }
                    .cabecera2 {
                        text-align: center;
                        border: solid 1px #000;
                        border-radius: 10px;
                        font-weight: bold;
                    }
                    .direccion, .direccion2 {
                        font-size: 12px;
                    }
                </style>
            </head>
            <body>
                <table>
                    <tbody>
                        <tr>
                            <td width='10%'>
                                <img src='img/logofinal.png' width='100%'>
                            </td>
                            <td width='60%'>
                                <div class='cabecera1'>
                                    <span class='titulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>                                    
                                    <span class='direccion'>Domicilio fiscal: Jr. Cusco N° 1576</span><br>                                    
                                </div>
                            </td>
                            <td width='30%'>
                                <div class='cabecera2' style='padding: 0.3rem'>
                                    <span>R.U.C. 20133670191<span><br>
                                    <span>$tipo_comprobante->nombre</span><br>
                                    <span>$com->serie-$com->correlativo</span><br>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table style='width: 100%; font-size:12px; padding: 0.3rem;'>
                        <thead>
                            <th colspan='2' width='50%'></th>
                            <th colspan='2' width='50%'></th>   
                        </thead>
                        <tbody>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Fecha de emisión</td>
                                <td width='40%'>: $emision</td>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Tipo de Pago</td>
                                <td width='20%'>: $com->forma_pago</td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold;'>$documento</td>
                                <td width='40%'>: $com->numerodoc</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($tipo_comprobante->tipo_serie == '08' || $tipo_comprobante->tipo_serie == '07') {
                                    $html.="Motivo";
                                }
                            $html.="
                            </td>
                                <td width='20%'>";
                                if ($tipo_comprobante->tipo_serie == '08' || $tipo_comprobante->tipo_serie == '07') {
                                    $html.=": $motivonota->descripcion";
                                }
                            $html.="
                            </td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Señor(es)</td>
                                <td width='40%'>: $com->cliente</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($com->tipo_comprobante_ref_id != null) {
                                    $html.="Doc. Afectación";
                                }
                            $html.="
                                </td>
                                <td width='20%'>";
                                if ($com->tipo_comprobante_ref_id != null) {
                                    $html.=": $com->serie_ref-$com->correlativo_ref";
                                }
                            $html.="
                                </td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Dirección</td>
                                <td width='40%'>: $com->direccion</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($com->tipo_comprobante_ref_id != null) {
                                    $html.="Fecha Documento";
                                }
                            $html.="
                                </td>
                                <td width='20%'>";
                                if ($com->tipo_comprobante_ref_id != null) {
                                    $html.=": $fecha_ref";
                                }
                            $html.="
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>";
                if ($com->iddifunto != null) {
                    $html.="
                    <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                        <div>
                            <table style='width: 100%;font-size:12px; padding: 0.3rem;'>
                                <thead>
                                    <th colspan='2' style='text-decoration: underline'>DATOS DEL DIFUNTO</th>
                                    <th colspan='2' style='text-decoration: underline'>REGISTRO CIVIL</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width='20%' style='text-align: left; font-weight: bold;'>Fecha de entierro</td>
                                        <td width='40%'>: $entierro</td>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> Distrito</td>
                                        <td width='20%'>: Huancayo</td>
                                    </tr>
                                    <tr>
                                        <td width='20%' style='text-align: left; font-weight: bold;vertical-align:top;'>Difunto</td>
                                        <td width='40%'>: $difunto->nombre_dif</td>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> Provincia</td>
                                        <td width='20%'>: Huancayo</td>
                                    </tr>
                                    <tr>
                                        <td width='20%' style='text-align: left; font-weight: bold;'>Pabellón</td>
                                        <td width='40%'>: $pabellon->nombre</td>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> Departamento</td>
                                        <td width='20%'>: Junín</td>
                                    </tr>
                                    <tr>
                                        <td width='20%' style='text-align: left; font-weight: bold;'>Cara</td>
                                        <td width='40%'>: $cara</td>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> Libro</td>
                                        <td width='20%'>: </td>
                                    </tr>
                                    <tr>
                                        <td width='20%' style='text-align: left; font-weight: bold;'>Fila</td>
                                        <td width='40%'>: $fila</td>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> Folio</td>
                                        <td width='20%'>: </td>
                                    </tr>
                                    <tr>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> Columna</td>
                                        <td width='40%'>: $columna</td>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> F. de Fallecimiento</td>
                                        <td width='20%'>: $fallecimiento</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>";
                }
                $html.="
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table width='100%' style='font-size:12px; padding: 0.3rem;'>
                        <thead>
                            <th width='10%'>Cant.</th>
                            <th width='60%'>Descripción</th>
                            <th width='15%'>P. Unit</th>
                            <th width='15%'>Importe</th>
                        </thead>
                        <tbody>
                            <tr><td style='border-top: 2px solid #000; padding-bottom: 0.3rem;' colspan='10'></td></tr>";
    
            foreach ($com->detalle as $i) {
                $producto = $i['producto'];   
                $quantity = number_format($i['cantidad'], 2, '.', ',');
                $unit_price = number_format($i['precio_unitario'], 2, '.', ',');
                $total_unit = number_format($i['importe_total'], 2, '.', ',');
                $html.="<tr>
                            <td style='text-align: center;'>$quantity</td>
                            <td>$producto</td>
                            <td style='text-align: right;'>$unit_price</td>
                            <td style='text-align: right;'>$total_unit</td>
                        </tr>";
            }
            $html.="<tr><td style='border-bottom: 2px solid #000; padding-top: 0.3rem;' colspan='10'></td></tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>SUB TOTAL</td>
                            <td style='text-align: right;'>$subtotal</td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>I.G.V. 18%</td>
                            <td style='text-align: right;'>$igv</td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;font-weight: bold;'>IMPORTE TOTAL</td>
                            <td style='text-align: right;'>$total</td>
                        </tr>
                    </tbody>
                </table>
            </div>
    
            <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                <table width='100%' style='font-size:12px; padding: 0.3rem;'>
                    <thead>
                        <th width='50%'></th>
                        <th width='50%'></th>
                    </thead>    
                    <tbody>
                        <tr>
                            <td style='text-align:left; vertical-align: top;'><span style='font-weight: bold;'>IMPORTE EN LETRAS</span>: $total_texto</td>
                            <td style='text-align: right;'>
                                <img src='img/qr/$tipo_comprobante->tipo_serie-$com->serie-$com->correlativo.png' width='30%'>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style='text-align: center; margin-top:0.2rem'><small style='font-size:8px;'>Representación impresa de la factura electrónica, podrá ser consultada en: <a href='https://ww3.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm' target='_blank' rel='noopener noreferrer'>http://sbhuancayo.website/consulta-comprobante</a></small></div>
            </body>
            </html>";
    
    
            
            // FORMATO TICKET
            $htmlticket = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='utf-8'>
                <style>
                    @page{
                        margin: 1rem;
                        padding:1rem;
                    }
                    body {
                        font-size:10px;
                        font-family: 'Arial Narrow', Arial, sans-serif;
                    }
                    .subtitulo, .titulo {
                        font-weight: bold;
                        font-size: 10px;
                    }
                    .cabecera1 {
                        display: block;
                        text-align: center;
                        font-size:10px;
                    }
                    .cabecera2 {
                        text-align: center;
                        font-weight: bold;
                        font-size: 15px !important;
                    }
                    .direccion, .direccion2 {
                        font-size: 10px;
                </style>
            </head>
            <body style='width:100%; padding:0 !important; margin:0 !important'>
                <table style='padding:0 !important; margin:0 !important'>
                    <tbody>
                        <tr>
                            <td style='text-align:center' width='100%'>
                                <img src='img/logofinal.png' width='15%'>
                            </td>
                        </tr>
                        <tr>
                            <td width='100%'>
                                <div class='cabecera1'>
                                    <small class='titulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</small> <br>
                                    <!-- <span class='subtitulo'>CEMENTERIO GENERAL</span> <br> -->
                                    <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 </span><br>
                                    <!-- <span class='direccion'>Domicilio fiscal: Jr. Junin N° 255 Huancayo - Huancayo - Junín</span><br> -->
                                    <!-- <span class='direccion2'>Jr. San Martin N° 350 Teléfono. 221468 - Huancayo</span><br> -->
                                    <span class='direccion2'>Suc. Jr. Trujillo N° 285 El Tambo - Huancayo</span><br>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width='100%'>
                                <div class='cabecera2' style='padding: 0.3rem'>
                                    <span>R.U.C. 20133670191<span><br>
                                    <span>$tipo_comprobante->nombre</span><br>
                                    <span>$com->serie-$com->correlativo</span><br>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div style='margin-top:0.2rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table style='width: 100%; font-size:10px; padding: 0.3rem;'>
                        <tbody>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>Forma de Pago</td>
                                <td width='70%'>: $com->forma_pago</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>Fec de Emisión</td>
                                <td width='70%'>: $emision</td>
                                
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>$documento</td>
                                <td width='70%'>: $com->numerodoc</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold; vertical-align:top'>Señor(es)</td>
                                <td width='70%' style='font-size:8px !important'>: $com->cliente</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold; vertical-align:top'>Dirección</td>
                                <td width='70%' style='font-size:8px !important'>: $com->direccion</td>
                            </tr>
                        </tbody>
                    </table>
                </div>";
                $htmlticket.="
                <div style='margin-top:0.2rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table width='100%' style='font-size:10px; padding: 0.3rem;'>
                        <thead>
                            <th width='10%'>Cant.</th>
                            <th width='60%'>Descripción</th>
                            <th width='15%'>P. Unit</th>
                            <th width='15%'>Importe</th>
                        </thead>
                        <tbody>
                            <tr><td style='border-top: 2px solid #000; padding-bottom: 0.3rem;' colspan='10'></td></tr>";
    
            foreach ($com->detalle as $i) {
                $producto = $i['producto'];   
                $quantity = number_format($i['cantidad'], 2, '.', ',');
                $unit_price = number_format($i['precio_unitario'], 2, '.', ',');
                $total_unit = number_format($i['importe_total'], 2, '.', ',');
                $htmlticket.="<tr>
                            <td style='text-align: center;'>$quantity</td>
                            <td>$producto</td>
                            <td style='text-align: right;'>$unit_price</td>
                            <td style='text-align: right;'>$total_unit</td>
                        </tr>";
            }
            $htmlticket.="<tr><td style='border-bottom: 2px solid #000; padding-top: 0.3rem;' colspan='10'></td></tr>
                        <tr>
                            <td colspan='2' style='text-align: right;'>SUB TOTAL</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$subtotal</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align: right;'>I.G.V. 18%</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$igv</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align: right;font-weight: bold;'>IMPORTE TOTAL</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$total</td>
                        </tr>
                    </tbody>
                </table>    
            </div>
    
            <div style='margin-top:0.2rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                <table width='100%' style='font-size:10px;'>   
                    <tbody>
                        <tr>
                            <td style='text-align:left; vertical-align: top;'><span style='font-weight: bold;'>SON</span>: $total_texto</td>
                        </tr>
                        <tr>
                            <td style='text-align: center;'>
                                <img src='img/qr/$tipo_comprobante->tipo_serie-$com->serie-$com->correlativo.png' width='20%'>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <div style='text-align: center; margin-top:0.2rem'><small style='font-size:9px;'>Representación impresa de la factura electrónica, podrá ser consultada en: <a href='https://ww3.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm' target='_blank' rel='noopener noreferrer'>http://sbhuancayo.website/consulta-comprobante</a></small></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </body>
            </html>";
    
    
            PDF::loadHTML($html)->setPaper('A4')->save(public_path("docs/a4/$tipo_comprobante->tipo_serie-$com->serie-$com->correlativo.pdf"));
            PDF::loadHTML($htmlticket)->setPaper(array(0,0,230,800))->save(public_path("docs/ticket/$tipo_comprobante->tipo_serie-$com->serie-$com->correlativo.pdf"));
            // FIN DE CREAR PDF
    
            // DD('creado', number_format($com->total, 2, '.', ','));
            // ENVIAR FACTURA
            $emisor = array(
                "tipodoc" => "6",
                "ruc" => "20133670191",
                "razon_social" => "SOCIEDAD DE BENEFICENCIA DE HUANCAYO",
                "nombre_comercial" => "SOCIEDAD DE BENEFICENCIA DE HUANCAYO",
                "pais" => "PE",
                "departamento" => "JUNIN",
                "provincia" => "HUANCAYO",
                "distrito" => "HUANCAYO",
                "direccion" => "JR. CUZCO  NRO 1576 JUNIN HUANCAYO",
                "ubigeo" => "120101",
                "usuario_emisor" => "GALA1024",
                "clave_emisor" => "HQJZsOol"
                );
            
            $cliente = array(
                        "tipo_documento" => $com->id_tipodoccliente,
                        "ruc" => $com->numerodoc,
                        "razon_social" => $com->cliente,
                        "direccion" => $com->direccion,
                        "pais" => 'PE',
                        );
            $total = 0;
            $cuotas = array();
    
            foreach ($com->cuota as $i) {
                $total += $i['payment'];
                $cuotas[] = array(
                    "numero" => "00".$i['numero'],
                    "vencimiento" => $i['fecha_vencimiento'],
                    "importe" => $i['importe'],
                );
            }
            // $letras = $formatter->toInvoice($com->total, 2);
            // DD($letras, $letrasgirado);
            $serie_correlativo=null;
            if ($com->tipo_comprobante_ref_id != null) {
                $serie_correlativo = $com->serie_ref.'-'.$com->correlativo_ref;
            }
            $cabecera = array(
                        "tipo_operacion"  => "0101",
                        "tipo_comprobante" => $tipo_comprobante->tipo_serie,
                        "moneda"          => $com->moneda_id,
                        "serie"           => $com->serie,
                        "correlativo"     => $com->correlativo,
                        "serieycorrelativo" => $serie_correlativo,
                        "total_op_gravadas" => $com->op_gravadas,
                        "igv"          => $com->igv,
                        "icbper"       => 0,
                        "total_op_exoneradas"=> $com->op_exoneradas,
                        "total_op_inafectas" => $com->op_inafectas,
                        "total_antes_impuestos" => $total_value,
                        "total_impuestos"    => $com->igv,
                        "total_despues_impuestos"=> $com->total,
                        "total_a_pagar"      =>$com->total,
                        "fecha_emision"      =>$com->fecha_emision,
                        "hora_emision"    =>$com->hora_emision,
                        "fecha_vencimiento" =>$com->fecha_vencimiento,
                        "forma_pago"      => $com->forma_pago,
                        "monto_credito"   => $total,
                        "anexo_sucursal"  => "0000",
                        "total_texto" => $total_texto,
                        "serie_ref" => $com->serie_ref,
                        "correlativo_ref" => $com->correlativo_ref,
                        "codmotivo" => $com->codmotivo,
                        "descripcion"=> $com->descripcion_motivo,
                        "tipo_comprobante_ref_id" => $com->tipo_comprobante_ref_id,
                        );
            
            // $cuotas = array();
            
            // $cuotas[] = array(
            //             "numero"    => "001",
            //             "importe"   => 150.00,
            //             "vencimiento"=> "2021-10-30"
            //         );
            
            // $cuotas[] = array(
            //             "numero"    => "002",
            //             "importe"   => 50.00,
            //             "vencimiento"=> "2021-11-30"
            //         );
            
            $cabecera['cuotas'] = $cuotas;
            $items = array();
            foreach ($com->detalle as $k => $i) {
                $producto = DB::table('producto')->where('id', $i['producto_id'])->get()->first();
                $i['affectation_igv_type_id'] = $producto->idtipoafectacion;
                // $i['affectation_igv_type_id'] = DB::table('producto')->where('id', $i['producto_id'])->get()->first();
                if ($i['affectation_igv_type_id'] == 10) {
                    $codigos = array("S",$i['affectation_igv_type_id'], '1000','IGV','VAT');
                } else if ($i['affectation_igv_type_id'] == 30) {
                    $codigos = array("S",$i['affectation_igv_type_id'], '9998','INA','FRE');
                } else {
                    $codigos = array("S",$i['affectation_igv_type_id'], '9997','EXO','VAT');
                }
                $items[] = array(
                    "item" =>  $k+1,
                    "cantidad" =>  $i['cantidad'],
                    "unidad" =>  $producto->unidad_id,
                    "nombre" =>  $i['producto'],
                    "valor_unitario" =>  round($i['valor_unitario'],2),
                    "precio_lista" =>  round($i['precio_unitario'],2),
                    "valor_total" =>  round($i['valor_total'],2),
                    "igv"  =>  round($i['igv'],2),
                    "icbper"  =>  0.00,
                    "factor_icbper"  =>  0.00,
                    "total_antes_impuestos" =>  round($i['valor_total'],2),
                    "total_impuestos" =>  round($i['igv'],2),
                    "porcentaje_igv" =>  round($i['porcentage_igv']),
                    "codigos" =>  $codigos,
                    "tipo_precio" => "01",
                );
            }
            // $items =$com->items;
    
            $nombrexml = $emisor['ruc']."-".$cabecera['tipo_comprobante']."-".$cabecera['serie']."-".$cabecera['correlativo'];
            
            $doc = new DOMDocument();
            $doc->formatOutput = FALSE;
            $doc->preserveWhiteSpace = TRUE;
            $doc->encoding = 'utf-8';
            if ($cabecera['tipo_comprobante'] == "03" || $cabecera['tipo_comprobante'] == "01") {
                $this->CrearXMLFactura($nombrexml,$emisor, $cliente, $cabecera, $items);
            } else if($cabecera['tipo_comprobante'] == "07") {
                $this->CrearXMLNotaCredito($nombrexml, $emisor, $cliente, $cabecera, $items);
            } else if($cabecera['tipo_comprobante'] == "08") {
                $this->CrearXMLNotaDebito($nombrexml, $emisor, $cliente, $cabecera, $items);
            }
            
            $flg_firma = "0";
            $ruta = public_path('storage/sinfirmar/'.$nombrexml.'.xml');
            $ruta2 = public_path('storage/firmado/'.$nombrexml.'.xml');
            $ruta3 = public_path('storage/firmado/'.$nombrexml);
            $ruta4 = public_path('storage/cdr/');
                
            $ruta_firma = public_path('storage/Beneficencia2022.pem');
            $pass_firma = "wadsp8.323dZx&";
                
    
            $signer = new SignedXml();
            $signer->setCertificateFromFile($ruta_firma);
            
            $xmlSigned = $signer->signFromFile($ruta);
            
            file_put_contents($ruta2, $xmlSigned);
    
            $zip = new ZipArchive();
            $nombrezip = $nombrexml.".ZIP";
            $rutazip = $ruta3.".ZIP";
    
            if($zip->open($rutazip,ZIPARCHIVE::CREATE)===true){
                $zip->addFile($ruta2, $nombrexml.'.xml');
                $zip->close();
            }
            //PASO 04
            //PREPARAR EL ENVÍO DEL XML
            $contenido_del_zip = base64_encode(file_get_contents($rutazip));
            $xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" 
                    xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                    <soapenv:Header>
                        <wsse:Security>
                            <wsse:UsernameToken>
                                <wsse:Username>'.$emisor['ruc'].$emisor['usuario_emisor'].'</wsse:Username>
                <wsse:Password>'.$emisor['clave_emisor'].'</wsse:Password>
                            </wsse:UsernameToken>
                        </wsse:Security>
                </soapenv:Header>
                <soapenv:Body>
                <ser:sendBill>
                    <fileName>'.$nombrezip.'</fileName>
                    <contentFile>'.$contenido_del_zip.'</contentFile>
                </ser:sendBill>
                </soapenv:Body>
            </soapenv:Envelope>';
    
                //PASO 05
            //ENVÍO DEL CPE A WS DE SUNAT
            //https://www.sunat.gob.pe/ol-ti-itcpfegem/billService
            //https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService
    
    
            // EVALUAR QUE TIPO DE USUARIO ES PARA ENVIAR A SUNAT

            $user = auth('sanctum')->user();
            $role = $user->getRoleNames();
            $ws = '';
            if ($role[0] == 'admin') {
                $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            } else {
                // $ws = "https://www.sunat.gob.pe/ol-ti-itcpfegem/billService";
            }
            $header = array(
                        "Content-type: text/xml; charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: ",
                        "Content-lenght: ".strlen($xml_envio)
                    );
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_URL,$ws);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
            curl_setopt($ch,CURLOPT_TIMEOUT,30);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
            curl_setopt($ch, CURLOPT_CAINFO, public_path('storage/cacert.pem'));
            $response = curl_exec($ch);
            // DD($response);
            //PASO 06 
            // OBTENEMOS RESPUESTA (CDR)
            $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            $responseCode='';
            $mensaje ='';
            // DD($httpcode);
            if($httpcode == 200){
                $doc = new DOMDocument();
                $doc->loadXML($response);
                if(isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)){
                    $cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue;
                    $cdr = base64_decode($cdr);         
                    file_put_contents($ruta4."R-".$nombrezip, $cdr);
                    $zip = new ZipArchive;
                    if($zip->open($ruta4."R-".$nombrezip)===true){
                        $zip->extractTo($ruta4.'R-'.$nombrexml);
                        $zip->close();
                    }
                    $docCDR = new DOMDocument();
                    $cdrContent = file_get_contents($ruta4.'R-'.$nombrexml.'/'.'R-'.$nombrexml.'.XML');
                    $docCDR->loadXML($cdrContent);
            
                    $responseCode = $docCDR->getElementsByTagName("ResponseCode")->item(0)->nodeValue;
            
                    if($responseCode==0){
                        $resultado = "FACTURA APROBADA";   
                    }else{
                        $resultado = "FACTURA RECHAZADA CON CODIGO DE ERROR:".$responseCode;
                    }
            
                    
                    $descri= $docCDR->getElementsByTagName("Description")->item(0)->nodeValue;
                    $codigo='';
                    $mensaje='';
                }else{   
                    $descri=''; 
                    $resultado='';    
                    $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
                    $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
                    
                }
            } else {
                curl_error($ch);
                DB::table('comprobante')->where('id',$com->id)->update(['nombrexml'=>$nombrexml,'codigo_sunat'=>$httpcode,'mensaje_sunat'=>'Problema de conexión','estado_comprobante'=>1]);
                echo "Problema de conexión";
            }
            DB::table('comprobante')->where('id',$com->id)->update(['nombrexml'=>$nombrexml,'codigo_sunat'=>$responseCode,'mensaje_sunat'=>$mensaje,'estado_comprobante'=>1]);
            curl_close($ch);
        }
    }

    public function show(Comprobante $comprobante){
        $comprobante = Comprobante::findOrFail($id);
        
        return response()->json($comprobante);
    }

    public function edit(Comprobante $comprobante){
        //
    }

    public function update(Request $request, Comprobante $comprobante){
        $comprobante = Comprobante::findOrFail($id);        
        $comprobante->update($request->all());        
        return $comprobante;   
    }

    public function destroy(Comprobante $comprobante){
        $comprobante = Comprobante::findOrFail($id);
        $comprobante->estado=0;
        $comprobante->save();

        return $comprobante;
    }

    public function getComprobantesByParams(Request $request){
        $comprobante = Comprobante::with('detalle','cuota','serieRelacion')->where('estado',1)->whereYear('fecha_emision',$request->anio)->orderBy('id','desc')->get();
        return response()->json($comprobante);
    }

    public function CrearXMLFactura($nombrexml, $emisor, $cliente, $cabecera, $items){

		$doc = new DOMDocument();
		$doc->formatOutput = FALSE;
		$doc->preserveWhiteSpace = TRUE;
		$doc->encoding = 'utf-8';

	    $xml = '<?xml version="1.0" encoding="utf-8"?>
        <Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
        <ext:UBLExtensions>
            <ext:UBLExtension>
                <ext:ExtensionContent/>
            </ext:UBLExtension>
        </ext:UBLExtensions>
        <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
        <cbc:CustomizationID schemeAgencyName="PE:SUNAT">2.0</cbc:CustomizationID>
        <cbc:ProfileID schemeName="Tipo de Operacion" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo17">'.$cabecera['tipo_operacion'].'</cbc:ProfileID>
        <cbc:ID>'.$cabecera['serie'].'-'.$cabecera['correlativo'].'</cbc:ID>
        <cbc:IssueDate>'.$cabecera['fecha_emision'].'</cbc:IssueDate>
        <cbc:IssueTime>'.$cabecera['hora_emision'].'</cbc:IssueTime>
        <cbc:DueDate>'.$cabecera['fecha_vencimiento'].'</cbc:DueDate>
        <cbc:InvoiceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01" listID="0101" name="Tipo de Operacion">'.$cabecera['tipo_comprobante'].'</cbc:InvoiceTypeCode>
        <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">'.$cabecera['moneda'].'</cbc:DocumentCurrencyCode>
                    <cbc:LineCountNumeric>'.count($items).'</cbc:LineCountNumeric>
            <cac:Signature>
            <cbc:ID>'.$cabecera['serie'].'-'.$cabecera['correlativo'].'</cbc:ID>
            <cac:SignatoryParty>
                <cac:PartyIdentification>
                    <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name><![CDATA['.$emisor['razon_social'].']]></cbc:Name>
                </cac:PartyName>
            </cac:SignatoryParty>
            <cac:DigitalSignatureAttachment>
                <cac:ExternalReference>
                    <cbc:URI>#SignatureSP</cbc:URI>
                </cac:ExternalReference>
            </cac:DigitalSignatureAttachment>
        </cac:Signature>
        <cac:AccountingSupplierParty>
            <cac:Party>
                <cac:PartyIdentification>
                    <cbc:ID schemeID="'.$emisor['tipodoc'].'" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$emisor['ruc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name><![CDATA['.$emisor['razon_social'].']]></cbc:Name>
                </cac:PartyName>
                <cac:PartyTaxScheme>
                    <cbc:RegistrationName><![CDATA['.$emisor['razon_social'].']]></cbc:RegistrationName>
                    <cbc:CompanyID schemeID="'.$emisor['tipodoc'].'" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$emisor['ruc'].'</cbc:CompanyID>
                    <cac:TaxScheme>
                    <cbc:ID schemeID="'.$emisor['tipodoc'].'" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$emisor['ruc'].'</cbc:ID>
                    </cac:TaxScheme>
                </cac:PartyTaxScheme>
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA['.$emisor['razon_social'].']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                    <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">'.$emisor['ubigeo'].'</cbc:ID>
                    <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0000</cbc:AddressTypeCode>
                    <cbc:CityName><![CDATA['.$emisor['provincia'].']]></cbc:CityName>
                    <cbc:CountrySubentity><![CDATA['.$emisor['departamento'].']]></cbc:CountrySubentity>
                    <cbc:District><![CDATA['.$emisor['distrito'].']]></cbc:District>
                    <cac:AddressLine>
                        <cbc:Line><![CDATA['.$emisor['direccion'].']]></cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PE</cbc:IdentificationCode>
                    </cac:Country>
                    </cac:RegistrationAddress>
                </cac:PartyLegalEntity>
                <cac:Contact>
                    <cbc:Name><![CDATA[]]></cbc:Name>
                </cac:Contact>
            </cac:Party>
        </cac:AccountingSupplierParty>
        <cac:AccountingCustomerParty>
            <cac:Party>
                <cac:PartyIdentification>
                    <cbc:ID schemeID="'.$cliente['tipo_documento'].'" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$cliente['ruc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name><![CDATA['.$cliente['razon_social'].']]></cbc:Name>
                </cac:PartyName>
                <cac:PartyTaxScheme>
                    <cbc:RegistrationName><![CDATA['.$cliente['razon_social'].']]></cbc:RegistrationName>
                    <cbc:CompanyID schemeID="'.$cliente['tipo_documento'].'" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$cliente['ruc'].'</cbc:CompanyID>
                    <cac:TaxScheme>
                    <cbc:ID schemeID="'.$cliente['tipo_documento'].'" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$cliente['ruc'].'</cbc:ID>
                    </cac:TaxScheme>
                </cac:PartyTaxScheme>
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA['.$cliente['razon_social'].']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                    <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI"/>
                    <cbc:CityName><![CDATA[]]></cbc:CityName>
                    <cbc:CountrySubentity><![CDATA[]]></cbc:CountrySubentity>
                    <cbc:District><![CDATA[]]></cbc:District>
                    <cac:AddressLine>
                        <cbc:Line><![CDATA['.$cliente['direccion'].']]></cbc:Line>
                    </cac:AddressLine>                                        
                    <cac:Country>
                        <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country"/>
                    </cac:Country>
                    </cac:RegistrationAddress>
                </cac:PartyLegalEntity>
            </cac:Party>
        </cac:AccountingCustomerParty>';

        $xml.='
        <cac:PaymentTerms>
                <cbc:ID>FormaPago</cbc:ID>
                <cbc:PaymentMeansID>'.$cabecera['forma_pago'].'</cbc:PaymentMeansID>
                <cbc:Amount currencyID="'.$cabecera['moneda'].'">'.$cabecera['monto_credito'].'</cbc:Amount>
        </cac:PaymentTerms>';

        foreach ($cabecera['cuotas'] as $p => $q) {
            $xml.='<cac:PaymentTerms>
                    <cbc:ID>FormaPago</cbc:ID>
                    <cbc:PaymentMeansID>Cuota'.$q['numero'].'</cbc:PaymentMeansID>
                    <cbc:Amount currencyID="PEN">'.$q['importe'].'</cbc:Amount>
                    <cbc:PaymentDueDate>'.$q['vencimiento'].'</cbc:PaymentDueDate>
                </cac:PaymentTerms>';
        }
        $xml.= '<cac:TaxTotal>
        <cbc:TaxAmount currencyID="'.$cabecera['moneda'].'">'.$cabecera['total_impuestos'].'</cbc:TaxAmount>';
        if ($cabecera['total_op_gravadas']>0) {
            $xml.='<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="'.$cabecera['moneda'].'">'.$cabecera['total_op_gravadas'].'</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="'.$cabecera['moneda'].'">'.$cabecera['igv'].'</cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
                        <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
                        <cbc:Name>IGV</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>';
        }
        if($cabecera['total_op_exoneradas']>0){
        $xml.='<cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="'.$cabecera['moneda'].'">'.$cabecera['total_op_exoneradas'].'</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="'.$cabecera['moneda'].'">0.00</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                    <cac:TaxScheme>
                    <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                    <cbc:Name>EXO</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>';
        }
        if($cabecera['total_op_inafectas']>0){
        $xml.='<cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="'.$cabecera['moneda'].'">'.$cabecera['total_op_inafectas'].'</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="'.$cabecera['moneda'].'">0.00</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">O</cbc:ID>
                    <cac:TaxScheme>
                    <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                    <cbc:Name>INA</cbc:Name>
                    <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>';          
        }
        if($cabecera['icbper']>0){
        $xml.='<cac:TaxSubtotal>
                <cbc:TaxAmount currencyID="'.$cabecera['moneda'].'">'.$cabecera['igv'].'</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cac:TaxScheme>
                    <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">7152</cbc:ID>
                    <cbc:Name>ICBPER</cbc:Name>
                    <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>';          
        }
        $xml.='</cac:TaxTotal>
        <cac:LegalMonetaryTotal>
            <cbc:LineExtensionAmount currencyID="'.$cabecera['moneda'].'">'.$cabecera['total_antes_impuestos'].'</cbc:LineExtensionAmount>
            <cbc:TaxInclusiveAmount currencyID="'.$cabecera['moneda'].'">'.$cabecera['total_despues_impuestos'].'</cbc:TaxInclusiveAmount>
            <cbc:PayableAmount currencyID="'.$cabecera['moneda'].'">'.$cabecera['total_a_pagar'].'</cbc:PayableAmount>
        </cac:LegalMonetaryTotal>';

        foreach ($items as $k=>$v){
        $xml.='<cac:InvoiceLine>
            <cbc:ID>'.$v['item'].'</cbc:ID>
            <cbc:InvoicedQuantity unitCode="'.$v['unidad'].'" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission for Europe">'.$v['cantidad'].'</cbc:InvoicedQuantity>
            <cbc:LineExtensionAmount currencyID="'.$cabecera['moneda'].'">'.$v['total_antes_impuestos'].'</cbc:LineExtensionAmount>
            <cac:PricingReference>
                <cac:AlternativeConditionPrice>
                    <cbc:PriceAmount currencyID="'.$cabecera['moneda'].'">'.$v['precio_lista'].'</cbc:PriceAmount>
                    <cbc:PriceTypeCode listName="Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>
                </cac:AlternativeConditionPrice>
            </cac:PricingReference>
            <cac:TaxTotal>
                <cbc:TaxAmount currencyID="'.$cabecera['moneda'].'">'.$v['total_impuestos'].'</cbc:TaxAmount>
                <cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="'.$cabecera['moneda'].'">'.$v['valor_total'].'</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="'.$cabecera['moneda'].'">'.$v['igv'].'</cbc:TaxAmount>
                    <cac:TaxCategory>
                    <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">'.$v['codigos'][0].'</cbc:ID>
                    <cbc:Percent>'.$v['porcentaje_igv'].'</cbc:Percent>
                    <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">'.$v['codigos'][1].'</cbc:TaxExemptionReasonCode>
                    <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT">'.$v['codigos'][2].'</cbc:ID>
                        <cbc:Name>'.$v['codigos'][3].'</cbc:Name>
                        <cbc:TaxTypeCode>'.$v['codigos'][4].'</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>';

                if($v['icbper']>0){
                    $xml.='<cac:TaxSubtotal>
                        <cbc:TaxAmount currencyID="'.$cabecera['moneda'].'">'.$v['icbper'].'</cbc:TaxAmount>
                        <cbc:BaseUnitMeasure unitCode="'.$v['unidad'].'">'.$v['cantidad'].'</cbc:BaseUnitMeasure>            
                        <cac:TaxCategory>
                                <cbc:PerUnitAmount currencyID="'.$cabecera['moneda'].'">'.$v['factor_icbper'].'</cbc:PerUnitAmount>
                                <cac:TaxScheme>
                                        <cbc:ID>7152</cbc:ID>
                                        <cbc:Name>ICBPER</cbc:Name>
                                        <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                                </cac:TaxScheme>
                        </cac:TaxCategory>
                    </cac:TaxSubtotal>';          
                }

            $xml.='</cac:TaxTotal>
            <cac:Item>
                <cbc:Description><![CDATA['.$v['nombre'].']]></cbc:Description>
                <cac:SellersItemIdentification>
                    <cbc:ID><![CDATA[195]]></cbc:ID>
                </cac:SellersItemIdentification>
                <cac:CommodityClassification>
                    <cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification">10191509</cbc:ItemClassificationCode>
                </cac:CommodityClassification>
            </cac:Item>
            <cac:Price>
                <cbc:PriceAmount currencyID="'.$cabecera['moneda'].'">'.$v['valor_unitario'].'</cbc:PriceAmount>
            </cac:Price>
        </cac:InvoiceLine>';   
        }
        $xml.='</Invoice>';

	    $doc->loadXML($xml);
	    $doc->save('storage/sinfirmar/'.$nombrexml.'.xml');
	}

    public function CrearXMLNotaCredito($nombrexml, $emisor, $cliente, $comprobante, $detalle){ 

        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';      
  
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
            <ext:UBLExtensions>
                <ext:UBLExtension>
                <ext:ExtensionContent />
                </ext:UBLExtension>
            </ext:UBLExtensions>
            <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
            <cbc:CustomizationID>2.0</cbc:CustomizationID>
            <cbc:ID>'.$comprobante['serie'].'-'.$comprobante['correlativo'].'</cbc:ID>
            <cbc:IssueDate>'.$comprobante['fecha_emision'].'</cbc:IssueDate>
            <cbc:IssueTime>00:00:01</cbc:IssueTime>
            <cbc:Note languageLocaleID="1000"><![CDATA['.$comprobante['total_texto'].']]></cbc:Note>
            <cbc:DocumentCurrencyCode>'.$comprobante['moneda'].'</cbc:DocumentCurrencyCode>
            <cac:DiscrepancyResponse>
                <cbc:ReferenceID>'.$comprobante['serieycorrelativo'].'</cbc:ReferenceID>
                <cbc:ResponseCode>'.$comprobante['codmotivo'].'</cbc:ResponseCode>
                <cbc:Description>'.$comprobante['descripcion'].'</cbc:Description>
            </cac:DiscrepancyResponse>
            <cac:BillingReference>
                <cac:InvoiceDocumentReference>
                <cbc:ID>'.$comprobante['serieycorrelativo'].'</cbc:ID>
                <cbc:DocumentTypeCode>'.$comprobante['tipo_comprobante_ref_id'].'</cbc:DocumentTypeCode>
                </cac:InvoiceDocumentReference>
            </cac:BillingReference>
            <cac:Signature>
                <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
                <cbc:Note><![CDATA['.$emisor['nombre_comercial'].']]></cbc:Note>
                <cac:SignatoryParty>
                <cac:PartyIdentification>
                    <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name><![CDATA['.$emisor['razon_social'].']]></cbc:Name>
                </cac:PartyName>
                </cac:SignatoryParty>
                <cac:DigitalSignatureAttachment>
                <cac:ExternalReference>
                    <cbc:URI>#SIGN-EMPRESA</cbc:URI>
                </cac:ExternalReference>
                </cac:DigitalSignatureAttachment>
            </cac:Signature>
            <cac:AccountingSupplierParty>
                <cac:Party>
                <cac:PartyIdentification>
                    <cbc:ID schemeID="'.$emisor['tipodoc'].'">'.$emisor['ruc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name><![CDATA['.$emisor['nombre_comercial'].']]></cbc:Name>
                </cac:PartyName>
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA['.$emisor['razon_social'].']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                        <cbc:ID>'.$emisor['ubigeo'].'</cbc:ID>
                        <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                        <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                        <cbc:CityName>'.$emisor['provincia'].'</cbc:CityName>
                        <cbc:CountrySubentity>'.$emisor['departamento'].'</cbc:CountrySubentity>
                        <cbc:District>'.$emisor['distrito'].'</cbc:District>
                        <cac:AddressLine>
                            <cbc:Line><![CDATA['.$emisor['direccion'].']]></cbc:Line>
                        </cac:AddressLine>
                        <cac:Country>
                            <cbc:IdentificationCode>'.$emisor['pais'].'</cbc:IdentificationCode>
                        </cac:Country>
                    </cac:RegistrationAddress>
                </cac:PartyLegalEntity>
                </cac:Party>
            </cac:AccountingSupplierParty>
            <cac:AccountingCustomerParty>
                <cac:Party>
                <cac:PartyIdentification>
                    <cbc:ID schemeID="'.$cliente['tipo_documento'].'">'.$cliente['ruc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA['.$cliente['razon_social'].']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                        <cac:AddressLine>
                            <cbc:Line><![CDATA['.$cliente['direccion'].']]></cbc:Line>
                        </cac:AddressLine>
                        <cac:Country>
                            <cbc:IdentificationCode>'.$cliente['pais'].'</cbc:IdentificationCode>
                        </cac:Country>
                    </cac:RegistrationAddress>
                </cac:PartyLegalEntity>
                </cac:Party>
            </cac:AccountingCustomerParty>
            <cac:TaxTotal>
                <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['igv'].'</cbc:TaxAmount>';
            if ($comprobante['total_op_gravadas'] > 0) {
                $xml.=' <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total_op_gravadas'].'</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['igv'].'</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cac:TaxScheme>
                        <cbc:ID>1000</cbc:ID>
                        <cbc:Name>IGV</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
                </cac:TaxSubtotal>';
            }
        if($comprobante['total_op_exoneradas']>0){
           $xml.='<cac:TaxSubtotal>
              <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total_op_exoneradas'].'</cbc:TaxableAmount>
              <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">0.00</cbc:TaxAmount>
              <cac:TaxCategory>
                 <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                 <cac:TaxScheme>
                    <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                    <cbc:Name>EXO</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                 </cac:TaxScheme>
              </cac:TaxCategory>
           </cac:TaxSubtotal>';
        }
  
        if($comprobante['total_op_inafectas']>0){
           $xml.='<cac:TaxSubtotal>
              <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total_op_inafectas'].'</cbc:TaxableAmount>
              <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">0.00</cbc:TaxAmount>
              <cac:TaxCategory>
                 <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                 <cac:TaxScheme>
                    <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                    <cbc:Name>INA</cbc:Name>
                    <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                 </cac:TaxScheme>
              </cac:TaxCategory>
           </cac:TaxSubtotal>';
        }
  
        $xml.='</cac:TaxTotal>
        <cac:LegalMonetaryTotal>
            <cbc:PayableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total_a_pagar'].'</cbc:PayableAmount>
        </cac:LegalMonetaryTotal>';
        
        foreach($detalle as $k=>$v){
            $xml.='<cac:CreditNoteLine>
            <cbc:ID>'.$v['item'].'</cbc:ID>
            <cbc:CreditedQuantity unitCode="'.$v['unidad'].'">'.$v['cantidad'].'</cbc:CreditedQuantity>
            <cbc:LineExtensionAmount currencyID="'.$comprobante['moneda'].'">'.$v['total_antes_impuestos'].'</cbc:LineExtensionAmount>
            <cac:PricingReference>
                <cac:AlternativeConditionPrice>
                    <cbc:PriceAmount currencyID="'.$comprobante['moneda'].'">'.$v['precio_lista'].'</cbc:PriceAmount>
                    <cbc:PriceTypeCode>'.$v['tipo_precio'].'</cbc:PriceTypeCode>
                </cac:AlternativeConditionPrice>
            </cac:PricingReference>
            <cac:TaxTotal>
                <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$v['igv'].'</cbc:TaxAmount>
                <cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$v['valor_total'].'</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$v['igv'].'</cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">'.$v['codigos'][0].'</cbc:ID>
                        <cbc:Percent>'.$v['porcentaje_igv'].'</cbc:Percent>
                        <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">'.$v['codigos'][1].'</cbc:TaxExemptionReasonCode>
                        <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT">'.$v['codigos'][2].'</cbc:ID>
                        <cbc:Name>'.$v['codigos'][3].'</cbc:Name>
                        <cbc:TaxTypeCode>'.$v['codigos'][4].'</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>
            </cac:TaxTotal>
            <cac:Item>
                <cbc:Description><![CDATA['.$v['nombre'].']]></cbc:Description>
                <cac:SellersItemIdentification>
                    <cbc:ID><![CDATA[195]]></cbc:ID>
                </cac:SellersItemIdentification>
            </cac:Item>
            <cac:Price>
                <cbc:PriceAmount currencyID="'.$comprobante['moneda'].'">'.$v['valor_unitario'].'</cbc:PriceAmount>
            </cac:Price>
            </cac:CreditNoteLine>';
        }
        $xml.='</CreditNote>';
        
        $doc->loadXML($xml);
        $doc->save('storage/sinfirmar/'.$nombrexml.'.XML'); 
    }

    public function CrearXMLNotaDebito($nombrexml, $emisor, $cliente, $comprobante, $detalle){

        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
            <ext:UBLExtensions>
                <ext:UBLExtension>
                <ext:ExtensionContent />
                </ext:UBLExtension>
            </ext:UBLExtensions>
            <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
            <cbc:CustomizationID>2.0</cbc:CustomizationID>
            <cbc:ID>'.$comprobante['serie'].'-'.$comprobante['correlativo'].'</cbc:ID>
            <cbc:IssueDate>'.$comprobante['fecha_emision'].'</cbc:IssueDate>
            <cbc:IssueTime>00:00:03</cbc:IssueTime>
            <cbc:Note languageLocaleID="1000"><![CDATA['.$comprobante['total_texto'].']]></cbc:Note>
            <cbc:DocumentCurrencyCode>'.$comprobante['moneda'].'</cbc:DocumentCurrencyCode>
            <cac:DiscrepancyResponse>
                <cbc:ReferenceID>'.$comprobante['serieycorrelativo'].'</cbc:ReferenceID>
                <cbc:ResponseCode>'.$comprobante['codmotivo'].'</cbc:ResponseCode>
                <cbc:Description>'.$comprobante['descripcion'].'</cbc:Description>
            </cac:DiscrepancyResponse>
            <cac:BillingReference>
                <cac:InvoiceDocumentReference>
                <cbc:ID>'.$comprobante['serieycorrelativo'].'</cbc:ID>
                <cbc:DocumentTypeCode>'.$comprobante['tipo_comprobante_ref_id'].'</cbc:DocumentTypeCode>
                </cac:InvoiceDocumentReference>
            </cac:BillingReference>
            <cac:Signature>
                <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
                <cbc:Note><![CDATA['.$emisor['nombre_comercial'].']]></cbc:Note>
                <cac:SignatoryParty>
                <cac:PartyIdentification>
                    <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name><![CDATA['.$emisor['razon_social'].']]></cbc:Name>
                </cac:PartyName>
                </cac:SignatoryParty>
                <cac:DigitalSignatureAttachment>
                <cac:ExternalReference>
                    <cbc:URI>#SIGN-EMPRESA</cbc:URI>
                </cac:ExternalReference>
                </cac:DigitalSignatureAttachment>
            </cac:Signature>
            <cac:AccountingSupplierParty>
                <cac:Party>
                <cac:PartyIdentification>
                    <cbc:ID schemeID="'.$emisor['tipodoc'].'">'.$emisor['ruc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name><![CDATA['.$emisor['nombre_comercial'].']]></cbc:Name>
                </cac:PartyName>
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA['.$emisor['razon_social'].']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                        <cbc:ID>'.$emisor['ubigeo'].'</cbc:ID>
                        <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                        <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                        <cbc:CityName>'.$emisor['provincia'].'</cbc:CityName>
                        <cbc:CountrySubentity>'.$emisor['departamento'].'</cbc:CountrySubentity>
                        <cbc:District>'.$emisor['distrito'].'</cbc:District>
                        <cac:AddressLine>
                            <cbc:Line><![CDATA['.$emisor['direccion'].']]></cbc:Line>
                        </cac:AddressLine>
                        <cac:Country>
                            <cbc:IdentificationCode>'.$emisor['pais'].'</cbc:IdentificationCode>
                        </cac:Country>
                    </cac:RegistrationAddress>
                </cac:PartyLegalEntity>
                </cac:Party>
            </cac:AccountingSupplierParty>
                <cac:AccountingCustomerParty>
                <cac:Party>
                <cac:PartyIdentification>
                    <cbc:ID schemeID="'.$cliente['tipo_documento'].'">'.$cliente['ruc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA['.$cliente['razon_social'].']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                        <cac:AddressLine>
                            <cbc:Line><![CDATA['.$cliente['direccion'].']]></cbc:Line>
                        </cac:AddressLine>
                        <cac:Country>
                            <cbc:IdentificationCode>'.$cliente['pais'].'</cbc:IdentificationCode>
                        </cac:Country>
                    </cac:RegistrationAddress>
                </cac:PartyLegalEntity>
                </cac:Party>
            </cac:AccountingCustomerParty>
            <cac:TaxTotal>
                <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['igv'].'</cbc:TaxAmount>';
                if ($comprobante['total_op_gravadas'] > 0) {
                    $xml.='
                    <cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total_op_gravadas'].'</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['igv'].'</cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cac:TaxScheme>
                            <cbc:ID>1000</cbc:ID>
                            <cbc:Name>IGV</cbc:Name>
                            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                    </cac:TaxSubtotal>';
                }
                if($comprobante['total_op_exoneradas']>0){
                $xml.='<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total_op_exoneradas'].'</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                        <cac:TaxScheme>
                            <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                            <cbc:Name>EXO</cbc:Name>
                            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>';
                }
        
                if($comprobante['total_op_inafectas']>0){
                $xml.='<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total_op_inafectas'].'</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                        <cac:TaxScheme>
                            <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                            <cbc:Name>INA</cbc:Name>
                            <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>';
                }
        
        $xml.='</cac:TaxTotal>
        <cac:RequestedMonetaryTotal>
            <cbc:PayableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total_a_pagar'].'</cbc:PayableAmount>
        </cac:RequestedMonetaryTotal>';
     
        foreach($detalle as $k=>$v){
    
            $xml.='<cac:DebitNoteLine>
            <cbc:ID>'.$v['item'].'</cbc:ID>
            <cbc:DebitedQuantity unitCode="'.$v['unidad'].'">'.$v['cantidad'].'</cbc:DebitedQuantity>
            <cbc:LineExtensionAmount currencyID="'.$comprobante['moneda'].'">'.$v['total_antes_impuestos'].'</cbc:LineExtensionAmount>
            <cac:PricingReference>
                <cac:AlternativeConditionPrice>
                    <cbc:PriceAmount currencyID="'.$comprobante['moneda'].'">'.$v['precio_lista'].'</cbc:PriceAmount>
                    <cbc:PriceTypeCode>'.$v['tipo_precio'].'</cbc:PriceTypeCode>
                </cac:AlternativeConditionPrice>
            </cac:PricingReference>
            <cac:TaxTotal>
                <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$v['igv'].'</cbc:TaxAmount>
                <cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$v['valor_total'].'</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$v['igv'].'</cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">'.$v['codigos'][0].'</cbc:ID>
                        <cbc:Percent>'.$v['porcentaje_igv'].'</cbc:Percent>
                        <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">'.$v['codigos'][1].'</cbc:TaxExemptionReasonCode>
                        <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT">'.$v['codigos'][2].'</cbc:ID>
                        <cbc:Name>'.$v['codigos'][3].'</cbc:Name>
                        <cbc:TaxTypeCode>'.$v['codigos'][4].'</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>
            </cac:TaxTotal>
            <cac:Item>
                <cbc:Description><![CDATA['.$v['nombre'].']]></cbc:Description>
                <cac:SellersItemIdentification>
                    <cbc:ID><![CDATA[195]]></cbc:ID>
                </cac:SellersItemIdentification>
            </cac:Item>
            <cac:Price>
                <cbc:PriceAmount currencyID="'.$comprobante['moneda'].'">'.$v['valor_unitario'].'</cbc:PriceAmount>
            </cac:Price>
            </cac:DebitNoteLine>';
     
        }
  
        $xml.='</DebitNote>';
  
        $doc->loadXML($xml);
        $doc->save('storage/sinfirmar/'.$nombrexml.'.XML'); 
    }

    public function loadComprobantesByTipo(Request $request){
        $id = 0;
        $tipo_comprobante = DB::table('tipo_comprobante')->where('id', $request->document_type_id)->get()->first();
        //DD($tipo_comprobante);
        if ($request->document_type_id == 3 || $request->document_type_id == 5) {
            $id = 01;
        } else if($request->document_type_id == 4 ||$request->document_type_id == 6) {
            $id = 03;
        }
        $data = Comprobante::where('tipo_comprobante_id', $id)->with('detalle','cuota')->where('estado_comprobante',1)->orderBy('correlativo','desc')->get();
        $dato = new Collection($data);
        $dato->map(function ($i) {
            $deta = new Collection($i['detalle']);
            // DD($deta);
            $deta->map(function ($d){
                // DD($d);
                $d->producto_detalle = DB::table('producto')->where('id',$d['producto_id'])->get()->first();
            });
        });

        return $data;
    }

    public function crearQr($ruc, $tipo_comprobante, $serie,$correlativo,$igv,$subtotal,$total,$fecha,$dni){
        QrCode::size(300)
            ->format('png')
            ->generate("$ruc|$tipo_comprobante|$serie|$correlativo|$subtotal|$igv|$total|$fecha|1|$dni", public_path("img/qr/$tipo_comprobante-$serie-$correlativo.png"));
    }

    public function crearPdf(Request $request){ 
       // DD($request);
        $detallecrono = DB::table('detallecrono')->where('id', $request->id_detallecrono)->first();
        if (isset($detallecrono)) {
            $crono = DB::table('cronograma')->where('id', $detallecrono->idcrono)->first();
            $credito = Credito::where('id', $crono->idcredito)->first();
        }
        
        
        $tipo_comprobante = DB::table('tipo_comprobante')->where('id',intval($request->serie_relacion['tipo_comprobante_id']))->get()->first();
        $existe = file_exists(public_path("docs/$request->tipo_impresion/$tipo_comprobante->tipo_serie-$request->serie-$request->correlativo.pdf"));
        if ($existe == true) {
            return 'ok';
        } else {
            // DD($request);
            // $customer = DB::table('cliente')->where('id', $request->cliente_id)->get()->first();
            $total_value = $request->op_gravadas + $request->op_exoneradas + $request->op_inafectas;
            $total_value = round($total_value,2);
            $this->crearQr('20133670191', $tipo_comprobante->tipo_serie, $request->serie,$request->correlativo,$request->igv,$total_value,$request->total,$request->fecha_emision,$request->numerodoc);
    
            $motivonota= null;
            $documentoref = null;
            
            if ($tipo_comprobante->tipo_serie == '07') {
                $motivonota = DB::table('tipo_notacredito')->where('id',$request->codmotivo)->get()->first();
            } else if($tipo_comprobante->tipo_serie == '08') {
                $motivonota = DB::table('tipo_notadebito')->where('id',$request->codmotivo)->get()->first();
            }
            if ($request->tipo_comprobante_ref_id != null) {             
               // DD($request->correlativo_ref,$request->serie_ref);   
                $documentoref = DB::table('comprobante')->where('serie',$request->serie_ref)->where('correlativo',$request->correlativo_ref)->first();
               // DD($documentoref);
                $fecha_ref = date("d/m/Y",strtotime($documentoref->fecha_emision));
            }
    
    
            $emision = date("d/m/Y",strtotime($request->fecha_emision));
            $documento;
            switch ($request->id_tipodoccliente) {
                case 1:
                    $documento = 'DNI';
                    break;
                case 4:
                    $documento = 'CARNET DE EXTRANJERÍA';
                    break;
                case 6:
                    $documento = 'RUC';
                    break;
                case 7:
                    $documento = 'PASAPORTE';
                    break;
                default:
                    $documento = 'SIN DOCUMENTO';
                    break;
            }
            $total = number_format($request->total, 2, '.', ',');
            $subtotal = number_format($total_value, 2, '.', ',');
            $igv = number_format($request->igv, 2, '.', ',');
            $formatter = new NumeroALetras();
    
            $total_texto = $formatter->toInvoice($request->total, 2,'SOLES');
    
            if ($request->iddifunto != null) {
                $difunto = DB::table('cem_difunto')->where('id', $request->iddifunto)->get()->first();
                $fallecimiento = date("d/m/Y",strtotime($difunto->fecfalle));
                $entierro = date("d/m/Y h:i:s A",strtotime($difunto->fechorafalle));
                $pabellon = DB::table('cem_pabellon')->where('id', $difunto->codpabe)->get()->first();
                $cara = substr($difunto->codnicho,5,2);
                $fila = substr($difunto->codnicho,7,1);
                $columna = substr($difunto->codnicho,8,3);
            }
    
            // CREAR PDF
            $html = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='utf-8'>
                <style>
                    body {
                        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                    }
                    .subtitulo, .titulo {
                        font-weight: bold;
                        font-size: 18px;
                    }
                    .cabecera1 {
                        display: block;
                        text-align: center;
                    }
                    .cabecera2 {
                        text-align: center;
                        border: solid 1px #000;
                        border-radius: 10px;
                        font-weight: bold;
                    }
                    .direccion, .direccion2 {
                        font-size: 12px;
                    }
                </style>
            </head>
            <body>
                <table>
                    <tbody>
                        <tr>
                            <td width='10%'>
                                <img src='img/logofinal.png' width='100%'>
                            </td>
                            <td width='60%'>
                                <div class='cabecera1'>
                                    <span class='titulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>                                    
                                    <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br>
                                   
                                </div>
                            </td>
                            <td width='30%'>
                                <div class='cabecera2' style='padding: 0.3rem'>
                                    <span>R.U.C. 20133670191<span><br>
                                    <span>$tipo_comprobante->nombre</span><br>
                                    <span>$request->serie-$request->correlativo</span><br>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table style='width: 100%; font-size:12px; padding: 0.3rem;'>
                        <thead>
                            <th colspan='2' width='50%'></th>
                            <th colspan='2' width='50%'></th>   
                        </thead>
                        <tbody>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Fecha de emisión</td>
                                <td width='40%'>: $emision</td>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Tipo de Pago</td>
                                <td width='20%'>: $request->forma_pago</td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold;'>$documento</td>
                                <td width='40%'>: $request->numerodoc</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Nro Credito</td>";
                                if (isset($credito)){
                                    $html.=" <td width='20%'>: $credito->numero </td>";
                                }else{
                                    $html.="<td width='20%'></td>";
                                }
                                $html.="    
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Señor(es)</td>
                                <td width='40%'>: $request->cliente</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.="Doc. Afectación";
                                }
                            $html.="
                                </td>
                                <td width='20%'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.=": $request->serie_ref-$request->correlativo_ref";
                                }
                            $html.="
                                </td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Dirección</td>
                                <td width='40%'>: $request->direccion</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.="Fecha Documento";
                                }
                            $html.="
                                </td>
                                <td width='20%'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.=": $fecha_ref";
                                }
                            $html.="
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>";
                if ($request->iddifunto != null) {
                    $html.="
                    <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                        <div>
                            <table style='width: 100%;font-size:12px; padding: 0.3rem;'>
                                <thead>
                                    <th colspan='2' style='text-decoration: underline'>DATOS DEL DIFUNTO</th>
                                    <th colspan='2' style='text-decoration: underline'>REGISTRO CIVIL</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width='20%' style='text-align: left; font-weight: bold;'>Fecha de entierro</td>
                                        <td width='40%'>: $entierro</td>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> Distrito</td>
                                        <td width='20%'>: Huancayo</td>
                                    </tr>
                                    <tr>
                                        <td width='20%' style='text-align: left; font-weight: bold;vertical-align:top;'>Difunto</td>
                                        <td width='40%'>: $difunto->nombre_dif</td>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> Provincia</td>
                                        <td width='20%'>: Huancayo</td>
                                    </tr>
                                    <tr>
                                        <td width='20%' style='text-align: left; font-weight: bold;'>Pabellón</td>
                                        <td width='40%'>: $pabellon->nombre</td>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> Departamento</td>
                                        <td width='20%'>: Junín</td>
                                    </tr>
                                    <tr>
                                        <td width='20%' style='text-align: left; font-weight: bold;'>Cara</td>
                                        <td width='40%'>: $cara</td>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> Libro</td>
                                        <td width='20%'>: </td>
                                    </tr>
                                    <tr>
                                        <td width='20%' style='text-align: left; font-weight: bold;'>Fila</td>
                                        <td width='40%'>: $fila</td>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> Folio</td>
                                        <td width='20%'>: </td>
                                    </tr>
                                    <tr>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> Columna</td>
                                        <td width='40%'>: $columna</td>
                                        <td width='20%' style='text-align: left; font-weight: bold;'> F. de Fallecimiento</td>
                                        <td width='20%'>: $fallecimiento</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>";
                }
                $html.="
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table width='100%' style='font-size:12px; padding: 0.3rem;'>
                        <thead>
                            <th width='10%'>Cant.</th>
                            <th width='60%'>Descripción</th>
                            <th width='15%'>P. Unit</th>
                            <th width='15%'>Importe</th>
                        </thead>
                        <tbody>
                            <tr><td style='border-top: 2px solid #000; padding-bottom: 0.3rem;' colspan='10'></td></tr>";
    
            foreach ($request->detalle as $i) {
                $producto = $i['producto'];   
                $quantity = number_format($i['cantidad'], 2, '.', ',');
                $unit_price = number_format($i['precio_unitario'], 2, '.', ',');
                $total_unit = number_format($i['importe_total'], 2, '.', ',');
                $html.="<tr>
                            <td style='text-align: center;'>$quantity</td>
                            <td>$producto</td>
                            <td style='text-align: right;'>$unit_price</td>
                            <td style='text-align: right;'>$total_unit</td>
                        </tr>";
            }
            $html.="<tr><td style='border-bottom: 2px solid #000; padding-top: 0.3rem;' colspan='10'></td></tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>SUB TOTAL</td>
                            <td style='text-align: right;'>$subtotal</td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>I.G.V. 18%</td>
                            <td style='text-align: right;'>$igv</td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;font-weight: bold;'>IMPORTE TOTAL</td>
                            <td style='text-align: right;'>$total</td>
                        </tr>
                    </tbody>
                </table>
            </div>
    
            <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                <table width='100%' style='font-size:12px; padding: 0.3rem;'>
                    <thead>
                        <th width='50%'></th>
                        <th width='50%'></th>
                    </thead>    
                    <tbody>
                        <tr>
                            <td style='text-align:left; vertical-align: top;'><span style='font-weight: bold;'>IMPORTE EN LETRAS</span>: $total_texto</td>
                            <td style='text-align: right;'>
                                <img src='img/qr/$tipo_comprobante->tipo_serie-$request->serie-$request->correlativo.png' width='30%'>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style='text-align: center; margin-top:0.2rem'><small style='font-size:8px;'>Representación impresa de la factura electrónica, podrá ser consultada en: <a href='https://ww3.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm' target='_blank' rel='noopener noreferrer'>http://sbhuancayo.website/consulta-comprobante</a></small></div>
            </body>
            </html>";
    
    
            
            // FORMATO TICKET
            $htmlticket = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='utf-8'>
                <style>
                    @page{
                        margin: 1rem;
                        padding:1rem;
                    }
                    body {
                        font-size:10px;
                        font-family: 'Arial Narrow', Arial, sans-serif;
                    }
                    .subtitulo, .titulo {
                        font-weight: bold;
                        font-size: 10px;
                    }
                    .cabecera1 {
                        display: block;
                        text-align: center;
                        font-size:10px;
                    }
                    .cabecera2 {
                        text-align: center;
                        font-weight: bold;
                        font-size: 15px !important;
                    }
                    .direccion, .direccion2 {
                        font-size: 10px;
                </style>
            </head>
            <body style='width:100%; padding:0 !important; margin:0 !important'>
                <table style='padding:0 !important; margin:0 !important'>
                    <tbody>
                        <tr>
                            <td style='text-align:center' width='100%'>
                                <img src='img/casa.png' width='15%'>
                            </td>
                        </tr>
                        <tr>
                            <td width='100%'>
                            <div class='cabecera1'>
                            <!---<span class='titulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>                                   
                            <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br>-->
                            <span class='titulo'>CASA DE PRESTAMOS HUANCAYO</span> <br>
                            <span class='subtitulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>
                            <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br>
                            <span class='direccion2'>Jr. Junín 253 - Huancayo - Junín</span><br>
                        </div>
                            </td>


                           
                        </tr>
                        <tr>
                            <td width='100%'>
                                <div class='cabecera2' style='padding: 0.3rem'>
                                    <span>R.U.C. 20133670191<span><br>
                                    <span>$tipo_comprobante->nombre</span><br>
                                    <span>$request->serie-$request->correlativo</span><br>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div style='margin-top:0.2rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table style='width: 100%; font-size:10px; padding: 0.3rem;'>
                        <tbody>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>Forma de Pago</td>
                                <td width='70%'>: $request->forma_pago</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>Fecha de emisión</td>
                                <td width='70%'>: $emision</td>
                                
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>$documento</td>
                                <td width='70%'>: $request->numerodoc</td>
                            </tr>";
                                if (isset($credito)){
                                    $htmlticket.="
                                    <tr>
                                        <td width='30%' style='text-align: left; font-weight: bold;'>Nro Credito</td> <td width='20%'>: $credito->numero </td> </tr>";
                                }else{
                                    $htmlticket.="<td width='20%'></td>
                                    ";
                                }
                                $htmlticket.=" 
                                
                            
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold; vertical-align:top'>Señor(es)</td>
                                <td width='70%'>: $request->cliente</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold; vertical-align:top'>Dirección</td>
                                <td width='70%'>: $request->direccion</td>
                            </tr>";
                            if ($tipo_comprobante->tipo_serie == '07'){
                                $htmlticket.= " <tr>
                                <td width='30%' style='text-align: left; font-weight: bold; vertical-align:top'>Doc. Referencia</td>
                                <td width='70%'>: $request->serie_ref - $request->correlativo_ref</td>
                            </tr>";
                            }
                            $htmlticket.=" 

                            
                        </tbody>
                    </table>
                </div>";
                $htmlticket.="
                <div style='margin-top:0.2rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table width='100%' style='font-size:10px; padding: 0.3rem;'>
                        <thead>
                            <th width='10%'>Cant.</th>
                            <th width='60%'>Descripción</th>
                            <th width='15%'>P. Unit</th>
                            <th width='15%'>Importe</th>
                        </thead>
                        <tbody>
                            <tr><td style='border-top: 2px solid #000; padding-bottom: 0.3rem;' colspan='10'></td></tr>";
    
            foreach ($request->detalle as $i) {
                $producto = $i['producto'];   
                $quantity = number_format($i['cantidad'], 2, '.', ',');
                $unit_price = number_format($i['precio_unitario'], 2, '.', ',');
                $total_unit = number_format($i['importe_total'], 2, '.', ',');
                $htmlticket.="<tr>
                            <td style='text-align: center;'>$quantity</td>
                            <td>$producto</td>
                            <td style='text-align: right;'>$unit_price</td>
                            <td style='text-align: right;'>$total_unit</td>
                        </tr>";
            }
            $htmlticket.="<tr><td style='border-bottom: 2px solid #000; padding-top: 0.3rem;' colspan='10'></td></tr>
                        <tr>
                            <td colspan='2' style='text-align: right;'>SUB TOTAL</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$subtotal</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align: right;'>I.G.V. 18%</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$igv</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align: right;font-weight: bold;'>IMPORTE TOTAL</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$total</td>
                        </tr>
                    </tbody>
                </table>    
            </div>
    
            <div style='margin-top:0.2rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                <table width='100%' style='font-size:10px;'>   
                    <tbody>
                        <tr>
                            <td style='text-align:left; vertical-align: top;'><span style='font-weight: bold;'>SON</span>: $total_texto</td>
                        </tr>
                        <tr>
                            <td style='text-align: center;'>
                                <img src='img/qr/$tipo_comprobante->tipo_serie-$request->serie-$request->correlativo.png' width='20%'>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <div style='text-align: center; margin-top:0.2rem'><small style='font-size:9px;'>Representación impresa de la factura electrónica, podrá ser consultada en: <a href='https://ww3.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm' target='_blank' rel='noopener noreferrer'>http://sbhuancayo.website/consulta-comprobante</a></small></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </body>
            </html>";
    
    
            PDF::loadHTML($html)->setPaper('A4')->save(public_path("docs/a4/$tipo_comprobante->tipo_serie-$request->serie-$request->correlativo.pdf"));
            PDF::loadHTML($htmlticket)->setPaper(array(0,0,230,800))->save(public_path("docs/ticket/$tipo_comprobante->tipo_serie-$request->serie-$request->correlativo.pdf"));
            return 'ok';
        }
    }

    public function getComprobantesDiario(Request $request){

        if ($request->mes) {
            # code...
        }
        if ($request->tipo == 1) {
            $comprobante = Comprobante::with('detalle','cuota','serieRelacion')->where('estado',1)->whereYear('fecha_emision',$request->anio)->whereMonth('fecha_emision',$request->mes)->orderByRaw('correlativo')->get();
        }else{
            $comprobante = Comprobante::with('detalle','cuota','serieRelacion')->where('estado',1)->whereYear('fecha_emision',$request->anio)->whereMonth('fecha_emision',$request->mes)->orderByRaw('cliente')->get();
        }

        $datos=$comprobante->pluck('correlativo');
        
        $minimo =  min($datos->toArray());
       
        $maximo = max($datos->toArray());
        $total = count($datos);
        
        
        return ['comprobante' => $comprobante,'minimo' => $minimo, 'maximo' => $maximo];
    }
    public function getComprobantesMensual(Request $request){
        $data = DB::select("select
        fecha_emision,
        sum(op_gravadas) as gravada,
        sum(IF(op_inafectas > 0,op_inafectas,0)) as inafecta,
        sum(IF(op_inafectas > 0,op_exoneradas,0)) as exonerada,
        sum(igv) as igv,
        sum(total) as facturado,
        SUM(IF(forma_pago = 'Credito' and tipo_comprobante_id != '07',total,0)) as credito,
        SUM(IF(forma_pago = 'Contado' and tipo_comprobante_id != '07',total,0)) as contado,
        SUM(IF(tipo_comprobante_id = '07',total,0)) as nota_credito,
        (SUM(IF(tipo_comprobante_id != '07',total,0)) - SUM(IF(tipo_comprobante_id = '07',total,0))) as total
        from comprobante
        where estado = 1 and fecha_emision >= '$request->fechainicio' and fecha_emision <= '$request->fechafin'
        GROUP BY fecha_emision with ROLLUP");
        return $data;
    }
    public function pagocompro(Request $request){

        try {
            
            $customer = DB::table('persona')->where('id', $request->idcliente)->get()->first();
            $tipo_comprobante = DB::table('tipo_comprobante')->where('id',2)->get()->first();
            $serie = DB::table('serie')->where('id',2)->get()->first();
            DB::beginTransaction();
            $comprobante = new Comprobante;
           
            $comprobante->emisor_id = 1;
            $comprobante->tipo_comprobante_id = '03';
            $comprobante->serie_id = $serie->id;
            $comprobante->serie = $serie->serie;
            $comprobante->correlativo = $serie->correlativo;
            $comprobante->forma_pago = 'Contado';
            $comprobante->fecha_emision = date('Y-m-d');
            $comprobante->hora_emision = date('H:i:s');
            $comprobante->fecha_vencimiento = date('Y-m-d');
            $comprobante->moneda_id = 'PEN';
            $comprobante->op_gravadas = $request->intpago/1.18;
            $comprobante->op_exoneradas = 0;
            $comprobante->op_inafectas = 0;
            $comprobante->igv = ($request->intpago/1.18)*0.18;
            $comprobante->total = $request->intpago;
            $comprobante->cliente_id = $request->idcliente;
            $comprobante->tipo_comprobante_ref_id = '';
            $comprobante->serie_ref = '';
            $comprobante->correlativo_ref = 0;
            $comprobante->codmotivo = '';
            $comprobante->iddifunto = 0;
            $comprobante->id_tipodoccliente = 1;
            $comprobante->cliente = $request->clientes;
            $comprobante->numerodoc = $customer->dni;
            $comprobante->direccion = $customer->direccion;
            $comprobante->iduser=auth('sanctum')->user()->id;
            $comprobante->idcuota = 0;
            $comprobante->save();  
    
            
                
                $det = new Detalle;
                $det->comprobante_id = $comprobante->id;
                $det->item = 1;
                $det->producto_id = 1;
                $det->cantidad = 1;
                $det->valor_unitario = $request->intpago/1.18;
                $det->precio_unitario = $request->intpago;
                $det->igv = ($request->intpago/1.18)*0.18;
                $det->porcentaje_igv = 18;
                $det->valor_total = $request->intpago/1.18;
                $det->importe_total = $request->intpago;
                $det->producto = 'PAGO POR EL CRÉDITO N° '.$request->ncredito.' CORRESPONDIENTE A LA CUOTA N° '.$request->cuopago;
                $det->descripcion = '';    
                $det->save();
          
           
                           
            DB::table('serie')->where('id',$serie->id)->update(['correlativo'=> $serie->correlativo+1]);
            $total = number_format($request->intpago, 2, '.', ',');
            $subtotal = number_format($request->intpago/1.18, 2, '.', ',');
            $igv = number_format(($request->intpago/1.18)*0.18, 2, '.', ',');

           // DD($serie);
            $this->crearQr('20133670191', $tipo_comprobante->tipo_serie, $serie->serie,$serie->correlativo,$igv,$subtotal,$request->intpago,date('Y-m-d'),$customer->dni);

            $motivonota= null;
            $documentoref = null;
            


            $emision = date("d/m/Y");
            $documento = 'DNI';
            
            // $total = number_format($request->intpago, 2, '.', ',');
            // $subtotal = number_format($request->intpago/1.18, 2, '.', ',');
            // $igv = number_format(($request->intpago/1.18)*0.18, 2, '.', ',');
            $formatter = new NumeroALetras();

            $total_texto = $formatter->toInvoice($request->intpago, 2,'SOLES');
            // CREAR PDF
            $html = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='utf-8'>
                <style>
                    body {
                      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                    }
                    .subtitulo, .titulo {
                        font-weight: bold;
                        font-size: 18px;
                    }
                    .cabecera1 {
                        display: block;
                        text-align: center;
                    }
                    .cabecera2 {
                        text-align: center;
                        border: solid 1px #000;
                        border-radius: 10px;
                        font-weight: bold;
                    }
                    .direccion, .direccion2 {
                        font-size: 12px;
                    }
                </style>
            </head>
            <body>
                <table>
                    <tbody>
                        <tr>
                            <td width='10%'>
                                <img src='img/logofinal.png' width='100%'>
                            </td>
                            <td width='60%'>
                                <div class='cabecera1'>
                                    <span class='titulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>                                   
                                    <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br>                                     
                                </div>
                            </td>
                            <td width='30%'>
                                <div class='cabecera2' style='padding: 0.3rem'>
                                    <span>R.U.C. 20133670191<span><br>
                                    <span>$tipo_comprobante->nombre</span><br>
                                    <span>$serie->serie-$serie->correlativo</span><br>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table style='width: 100%; font-size:12px; padding: 0.3rem;'>
                        <thead>
                            <th colspan='2' width='50%'></th>
                            <th colspan='2' width='50%'></th>   
                        </thead>
                        <tbody>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Fecha de emisión</td>
                                <td width='40%'>: $emision</td>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Tipo de Pago</td>
                                <td width='20%'>: Contado</td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold;'>$documento</td>
                                <td width='40%'>: $customer->dni</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($tipo_comprobante->tipo_serie == '08' || $tipo_comprobante->tipo_serie == '07') {
                                    $html.="Motivo";
                                }
                            $html.="
                            </td>
                                <td width='20%'>";
                                if ($tipo_comprobante->tipo_serie == '08' || $tipo_comprobante->tipo_serie == '07') {
                                    $html.=": $motivonota->descripcion";
                                }
                            $html.="
                            </td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Señor(es)</td>
                                <td width='40%'>: $customer->paterno $customer->paterno $customer->nombres </td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.="Doc. Afectación";
                                }
                            $html.="
                                </td>
                                <td width='20%'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.=": $request->serie_ref-$request->correlativo_ref";
                                }
                            $html.="
                                </td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Dirección</td>
                                <td width='40%'>: $customer->direccion</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.="Fecha Documento";
                                }
                            $html.="
                                </td>
                                <td width='20%'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.=": $fecha_ref";
                                }
                            $html.="
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>";
                
                $html.="
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table width='100%' style='font-size:12px; padding: 0.3rem;'>
                        <thead>
                            <th width='10%'>Cant.</th>
                            <th width='60%'>Descripción</th>
                            <th width='15%'>P. Unit</th>
                            <th width='15%'>Importe</th>
                        </thead>
                        <tbody>
                            <tr><td style='border-top: 2px solid #000; padding-bottom: 0.3rem;' colspan='10'></td></tr>";

         
                  
                $quantity = number_format(1, 2, '.', ',');
                $unit_price = number_format($det->valor_unitario, 2, '.', ',');
                $total_unit = number_format($det->importe_total, 2, '.', ',');
                $html.="<tr>
                            <td style='text-align: center;'>$quantity</td>
                            <td>$det->producto</td>
                            <td style='text-align: right;'>$unit_price</td>
                            <td style='text-align: right;'>$total_unit</td>
                        </tr>";
           
            $html.="<tr><td style='border-bottom: 2px solid #000; padding-top: 0.3rem;' colspan='10'></td></tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>SUB TOTAL</td>
                            <td style='text-align: right;'>$subtotal</td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>I.G.V. 18%</td>
                            <td style='text-align: right;'>$igv</td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;font-weight: bold;'>IMPORTE TOTAL</td>
                            <td style='text-align: right;'>$total</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                <table width='100%' style='font-size:12px; padding: 0.3rem;'>
                    <thead>
                        <th width='50%'></th>
                        <th width='50%'></th>
                    </thead>    
                    <tbody>
                        <tr>
                            <td style='text-align:left; vertical-align: top;'><span style='font-weight: bold;'>IMPORTE EN LETRAS</span>: $total_texto</td>
                            <td style='text-align: right;'>
                                <img src='img/qr/$tipo_comprobante->tipo_serie-$serie->serie-$serie->correlativo.png' width='30%'>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style='text-align: center; margin-top:0.2rem'><small style='font-size:8px;'>Representación impresa de la factura electrónica, podrá ser consultada en: <a href='https://ww3.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm' target='_blank' rel='noopener noreferrer'>http://sbhuancayo.website/consulta-comprobante</a></small></div>
            </body>
            </html>";


            
            // FORMATO TICKET
            $htmlticket = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='utf-8'>
                <style>
                    @page{
                        margin: 0;
                        padding:0;
                    }
                    body {
                        font-size:8px;
                        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                    }
                    .subtitulo, .titulo {
                        font-weight: bold;
                        font-size: 8px;
                    }
                    .cabecera1 {
                        display: block;
                        text-align: center;
                        font-size:8px;
                    }
                    .cabecera2 {
                        text-align: center;
                        font-weight: bold;
                        font-size:10px !important;
                    }
                    .direccion, .direccion2 {
                        font-size: 8px;
                    }
                </style>
            </head>
            <body style='width:100%; padding:0 !important; margin:0 !important'>
                <table style='padding:0 !important; margin:0 !important'>
                    <tbody>
                        <tr>
                            <td style='text-align:center' width='100%'>
                                <img src='img/logofinal.png' width='30%'>
                            </td>
                        </tr>
                        <tr>
                            <td width='100%'>
                                <div class='cabecera1'>
                                    <small class='titulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</small> <br>
                                    <!-- <span class='subtitulo'>CEMENTERIO GENERAL</span> <br> -->
                                    <!-- <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br> -->
                                    <span class='direccion'>Domicilio fiscal: Jr. Junin N° 255 Huancayo - Huancayo - Junín</span><br>
                                    <!-- <span class='direccion2'>Jr. San Martin N° 350 Teléfono. 221468 - Huancayo</span><br> -->
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width='100%'>
                                <div class='cabecera2' style='padding: 0.3rem'>
                                    <span>R.U.C. 20133670191<span><br>
                                    <span>$tipo_comprobante->nombre</span><br>
                                    <span>$serie->serie-$serie->correlativo</span><br>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table style='width: 100%; font-size:8px; padding: 0.3rem;'>
                        <tbody>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>Tipo de Pago</td>
                                <td width='70%'>: Contado</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>Fecha de emisión</td>
                                <td width='70%'>: $emision</td>
                                
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>$documento</td>
                                <td width='70%'>: $customer->dni</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold; vertical-align:top'>Señor(es)</td>
                                <td width='70%'>: $customer->paterno  $customer->paterno $customer->nombres</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold; vertical-align:top'>Dirección</td>
                                <td width='70%'>: $customer->direccion</td>
                            </tr>
                        </tbody>
                    </table>
                </div>";
                $htmlticket.="
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table width='100%' style='font-size:8px; padding: 0.3rem;'>
                        <thead>
                            <th width='10%'>Cant.</th>
                            <th width='60%'>Descripción</th>
                            <th width='15%'>P. Unit</th>
                            <th width='15%'>Importe</th>
                        </thead>
                        <tbody>
                            <tr><td style='border-top: 2px solid #000; padding-bottom: 0.3rem;' colspan='10'></td></tr>";
                             
                $quantity = number_format(1, 2, '.', ',');
                $unit_price = number_format($det->valor_unitario, 2, '.', ',');
                $total_unit = number_format($det->precio_unitario, 2, '.', ',');
                $htmlticket.="<tr>
                            <td style='text-align: center;'>$quantity</td>
                            <td>$det->producto</td>
                            <td style='text-align: right;'>$unit_price</td>
                            <td style='text-align: right;'>$total_unit</td>
                        </tr>";
            
            $htmlticket.="<tr><td style='border-bottom: 2px solid #000; padding-top: 0.3rem;' colspan='10'></td></tr>
                        <tr>
                            <td colspan='2' style='text-align: right;'>SUB TOTAL</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$subtotal</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align: right;'>I.G.V. 18%</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$igv</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align: right;font-weight: bold;'>IMPORTE TOTAL</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$total</td>
                        </tr>
                    </tbody>
                </table>    
            </div>

            <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                <table width='100%' style='font-size:8px;'>
                    <thead>
                        <th width='50%'></th>
                        <th width='50%'></th>
                    </thead>    
                    <tbody>
                        <tr>
                            <td style='text-align:left; vertical-align: top;'><span style='font-weight: bold;'>IMPORTE EN LETRAS</span>: $total_texto</td>
                        </tr>
                        <tr>
                            <td style='text-align: center;'>
                                <img src='img/qr/$tipo_comprobante->tipo_serie-$serie->serie-$serie->correlativo.png' width='30%'>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <div style='text-align: center; margin-top:0.2rem'><small style='font-size:8px;'>Representación impresa de la factura electrónica, podrá ser consultada en: <a href='https://ww3.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm' target='_blank' rel='noopener noreferrer'>http://sbhuancayo.website/consulta-comprobante</a></small></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </body>
            </html>";


            PDF::loadHTML($html)->setPaper('A4')->save(public_path("docs/a4/$tipo_comprobante->tipo_serie-$serie->serie-$serie->correlativo.pdf"));
            PDF::loadHTML($htmlticket)->setPaper(array(0,0,230,800))->save(public_path("docs/ticket/$tipo_comprobante->tipo_serie-$serie->serie-$serie->correlativo.pdf"));
            // FIN DE CREAR PDF

            // DD('creado', number_format($request->total, 2, '.', ','));
            // ENVIAR FACTURA
            $emisor = array(
                "tipodoc" => "6",
                "ruc" => "20133670191",
                "razon_social" => "SOCIEDAD DE BENEFICENCIA DE HUANCAYO",
                "nombre_comercial" => "SOCIEDAD DE BENEFICENCIA DE HUANCAYO",
                "pais" => "PE",
                "departamento" => "JUNIN",
                "provincia" => "HUANCAYO",
                "distrito" => "HUANCAYO",
                "direccion" => "JR. CUZCO  NRO 1576 JUNIN HUANCAYO",
                "ubigeo" => "120101",
                "usuario_emisor" => "GALA1024",
                "clave_emisor" => "HQJZsOol"
                );
            
            $cliente = array(
                        "tipo_documento" => 1,
                        "ruc" => $customer->dni,
                        "razon_social" => $customer->paterno.' '.$customer->materno.' '.$customer->nombres ,
                        "direccion" => $customer->direccion,
                        "pais" => 'PE',
                        );
            $total = 0;
            $cuotas = array();

            // foreach ($request->payments as $i) {
            //     $total += $i['payment'];
            //     $cuotas[] = array(
            //         "numero" => "00".$i['description'],
            //         "vencimiento" => $i['date_of_payment'],
            //         "importe" => $i['payment'],
            //     );
            // }

            // $letras = $formatter->toInvoice($request->total, 2);
            // DD($letras, $letrasgirado);
            //$serie_correlativo=null;
            if ($request->tipo_comprobante_ref_id != null) {
                $serie_correlativo = $request->serie_ref.'-'.$request->correlativo_ref;
            }else{
                $serie_correlativo ='';
            }
            
            $cabecera = array(
                        "tipo_operacion"  => "0101",
                        "tipo_comprobante" => $tipo_comprobante->tipo_serie,
                        "moneda"          => 'PEN',
                        "serie"           => $serie->serie,
                        "correlativo"     => $serie->correlativo,
                        "serieycorrelativo" => $serie_correlativo,
                        "total_op_gravadas" => number_format($request->intpago/1.18,2),
                        "igv"          => number_format(($request->intpago/1.18)*0.18,2),
                        "icbper"       => 0,
                        "total_op_exoneradas"=> 0,
                        "total_op_inafectas" => 0,
                        "total_antes_impuestos" => number_format($request->intpago/1.18,2),
                        "total_impuestos"    => number_format(($request->intpago/1.18)*0.18,2),
                        "total_despues_impuestos"=> number_format($request->intpago,2),
                        "total_a_pagar"      => number_format($request->intpago,2),
                        "fecha_emision"      =>date('Y-m-d'),
                        "hora_emision"    => date('H:i:s'),
                        "fecha_vencimiento" =>date('Y-m-d'),
                        "forma_pago"      => 'Contado',
                        "monto_credito"   => $total,
                        "anexo_sucursal"  => "0000",
                        "total_texto" => $total_texto,
                        "serie_ref" => '',
                        "correlativo_ref" => '',
                        "codmotivo" => '',
                        "descripcion"=> '',
                        "tipo_comprobante_ref_id" => '',
                        );
            
            $cabecera['cuotas'] = $cuotas;
            $items = array();
           
               
                    $codigos = array("S",'10', '1000','IGV','VAT');
                
                $items[] = array(
                    "item" => 1,
                    "cantidad" =>  1,
                    "unidad" => 'NIU',
                    "nombre" =>  'PAGO POR CRÉDITO N° '.$request->ncredito.' CORRESPONDIENTE A LA CUOTA N° '.$request->cuopago,
                    "valor_unitario" =>  number_format($request->intpago/1.18,2),
                    "precio_lista" =>  number_format($request->intpago,2),
                    "valor_total" =>  number_format($request->intpago/1.18,2),
                    "igv"  =>  number_format(($request->intpago/1.18)*0.18,2),
                    "icbper"  =>  0.00,
                    "factor_icbper"  =>  0.00,
                    "total_antes_impuestos" =>  number_format($request->intpago/1.18,2),
                    "total_impuestos" =>  number_format(($request->intpago/1.18)*0.18,2),
                    "porcentaje_igv" =>  18,
                    "codigos" =>  $codigos,
                    "tipo_precio" => "01",
                );
            
            // $items =$request->items;

            $nombrexml = $emisor['ruc']."-".$cabecera['tipo_comprobante']."-".$cabecera['serie']."-".$cabecera['correlativo'];
            
            $doc = new DOMDocument();
            $doc->formatOutput = FALSE;
            $doc->preserveWhiteSpace = TRUE;
            $doc->encoding = 'utf-8';
            if ($cabecera['tipo_comprobante'] == "03" || $cabecera['tipo_comprobante'] == "01") {
                $this->CrearXMLFactura($nombrexml,$emisor, $cliente, $cabecera, $items);
            } else if($cabecera['tipo_comprobante'] == "07") {
                $this->CrearXMLNotaCredito($nombrexml, $emisor, $cliente, $cabecera, $items);
            } else if($cabecera['tipo_comprobante'] == "08") {
                $this->CrearXMLNotaDebito($nombrexml, $emisor, $cliente, $cabecera, $items);
            }
            
            $flg_firma = "0";
            $ruta = public_path('storage/sinfirmar/'.$nombrexml.'.xml');
            $ruta2 = public_path('storage/firmado/'.$nombrexml.'.xml');
            $ruta3 = public_path('storage/firmado/'.$nombrexml);
            $ruta4 = public_path('storage/cdr/');
            $rutaerror = public_path('storage/sunatRpta/');
                
            $ruta_firma = public_path('storage/Beneficencia2022.pem');
            $pass_firma = "wadsp8.323dZx&";
                
    
            $signer = new SignedXml();
            $signer->setCertificateFromFile($ruta_firma);
            
            $xmlSigned = $signer->signFromFile($ruta);
            
            file_put_contents($ruta2, $xmlSigned);
    
            $zip = new ZipArchive();
            $nombrezip = $nombrexml.".ZIP";
            $rutazip = $ruta3.".ZIP";
    
            if($zip->open($rutazip,ZIPARCHIVE::CREATE)===true){
                $zip->addFile($ruta2, $nombrexml.'.xml');
                $zip->close();
            }
            //PASO 04
            //PREPARAR EL ENVÍO DEL XML
            $contenido_del_zip = base64_encode(file_get_contents($rutazip));
            $xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" 
                    xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                    <soapenv:Header>
                        <wsse:Security>
                            <wsse:UsernameToken>
                                <wsse:Username>'.$emisor['ruc'].$emisor['usuario_emisor'].'</wsse:Username>
                <wsse:Password>'.$emisor['clave_emisor'].'</wsse:Password>
                            </wsse:UsernameToken>
                        </wsse:Security>
                </soapenv:Header>
                <soapenv:Body>
                <ser:sendBill>
                    <fileName>'.$nombrezip.'</fileName>
                    <contentFile>'.$contenido_del_zip.'</contentFile>
                </ser:sendBill>
                </soapenv:Body>
            </soapenv:Envelope>';
    
                //PASO 05
            //ENVÍO DEL CPE A WS DE SUNAT
            //https://www.sunat.gob.pe/ol-ti-itcpfegem/billService
            //https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService

            $user = auth('sanctum')->user();
            $role = $user->getRoleNames();
            // DD($role);
            $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            // $ws = "https://www.sunat.gob.pe/ol-ti-itcpfegem/billService";
            $header = array(
                        "Content-type: text/xml; charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: ",
                        "Content-lenght: ".strlen($xml_envio)
                    );
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_URL,$ws);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
            curl_setopt($ch,CURLOPT_TIMEOUT,30);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
            curl_setopt($ch, CURLOPT_CAINFO, public_path('storage/cacert.pem'));
            $response = curl_exec($ch);
            //PASO 06 
            // OBTENEMOS RESPUESTA (CDR)
            $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            $responseCode='';
            $mensaje ='';
             //DD($response,$httpcode);
            //DD($httpcode);
            if($httpcode == 200){
                $doc = new DOMDocument();
                $doc->loadXML($response);
                 //DD($doc);
                if(isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)){
                    $cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue;
                    $cdr = base64_decode($cdr);
                    // DD($cdr);
                    file_put_contents($ruta4."R-".$nombrezip, $cdr);
                    $zip = new ZipArchive;
                    if($zip->open($ruta4."R-".$nombrezip)===true){
                        $zip->extractTo($ruta4.'R-'.$nombrexml);
                        $zip->close();
                    }
                    $docCDR = new DOMDocument();
                    $cdrContent = file_get_contents($ruta4.'R-'.$nombrexml.'/'.'R-'.$nombrexml.'.XML');
                    $docCDR->loadXML($cdrContent);
            
                    $responseCode = $docCDR->getElementsByTagName("ResponseCode")->item(0)->nodeValue;
                    if($responseCode==0){
                        $resultado = "FACTURA APROBADA";   
                    }else{
                        $resultado = "FACTURA RECHAZADA CON CODIGO DE ERROR:".$responseCode;
                    }
                    $descri= $docCDR->getElementsByTagName("Description")->item(0)->nodeValue;
                    $codigo='';
                    $mensaje='';
                }else{
                    $descri=''; 
                    $resultado='';
                    file_put_contents($rutaerror."R-".$nombrexml, $doc);
                    $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
                    $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
                   // DD($mensaje);
                }
            } else {
                DD(curl_error($ch));
                // echo "Problema de conexión";
            }
            DB::table('comprobante')->where('id',$comprobante->id)->update(['nombrexml'=>$nombrexml,'codigo_sunat'=>$responseCode,'mensaje_sunat'=>$mensaje,'estado_comprobante'=>1]);
            DB::table('pago')->where('id',$request->id_pago)->update(['idcomprobante'=>$comprobante->id,'comprobante'=>$cabecera['tipo_comprobante']."-".$cabecera['serie']."-".$cabecera['correlativo']]);

            curl_close($ch);
            // DD($codigo.": ".$mensaje."  "."d ".$descri." f ".$resultado); 
            // FIN ENVIAR DE FACTURA

            DB::commit();
            
            return $comprobante;
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function cuotasVencidasCompro(Request $request){

        try {
            
            $customer = DB::table('persona')->where('id', $request->idcliente)->get()->first();
            $tipo_comprobante = DB::table('tipo_comprobante')->where('id',2)->get()->first();
            $serie = DB::table('serie')->where('id',2)->get()->first();
            $fechas = Carbon::now()->locale('es');
            DB::beginTransaction();
            $comprobante = new Comprobante;
           //DD($request);
            $comprobante->emisor_id = 1;
            $comprobante->tipo_comprobante_id = '03';
            $comprobante->serie_id = $serie->id;
            $comprobante->serie = $serie->serie;
            $comprobante->correlativo = $serie->correlativo;
            $comprobante->forma_pago = 'Contado';
            $comprobante->fecha_emision = date('Y-m-d');
            $comprobante->hora_emision = date('H:i:s');
            $comprobante->fecha_vencimiento = date('Y-m-d');
            $comprobante->moneda_id = 'PEN';
            $comprobante->op_gravadas = $request->interes/1.18;
            $comprobante->op_exoneradas = 0;
            $comprobante->op_inafectas = 0;
            $comprobante->igv = ($request->interes/1.18)*0.18;
            $comprobante->total = $request->interes;
            $comprobante->cliente_id = $request->idcliente;
            $comprobante->tipo_comprobante_ref_id = '';
            $comprobante->serie_ref = '';
            $comprobante->correlativo_ref = 0;
            $comprobante->codmotivo = '';
            $comprobante->iddifunto = 0;
            $comprobante->id_tipodoccliente = 1;
            $comprobante->cliente = $request->nom_ape;
            $comprobante->numerodoc = $customer->dni;
            $comprobante->direccion = $customer->direccion;
            $comprobante->iduser=auth('sanctum')->user()->id;
            $comprobante->idcuota = 0;
            $comprobante->id_detallecrono = $request->id;
            $comprobante->save();  

                $det = new Detalle;
                $det->comprobante_id = $comprobante->id;
                $det->item = 1;
                $det->producto_id = 1;
                $det->cantidad = 1;
                $det->valor_unitario = $request->interes/1.18;
                $det->precio_unitario = $request->interes;
                $det->igv = ($request->interes/1.18)*0.18;
                $det->porcentaje_igv = 18;
                $det->valor_total = $request->interes/1.18;
                $det->importe_total = $request->interes;
                //INTERES POR EL PRESTMO OTORGADO, CORRESPONDIENTE AL MES DE SETIEMBRE -2022
                //PAGO POR EL INTERÉS CORRESPONDIENTE AL MES DE 
                $det->producto = 'INTERES POR EL PRESTAMO OTORGADO, CORRESPONDIENTE AL MES DE '.strtoupper($fechas->monthName).' - '.date('Y');   //. //$request->cuotanro . '.';
                $det->descripcion = '';    
                $det->save();
                           
            DB::table('serie')->where('id',$serie->id)->update(['correlativo'=> $serie->correlativo+1]);
            $total = number_format($request->interes, 2, '.', ',');
            $subtotal = number_format($request->interes/1.18, 2, '.', ',');
            $igv = number_format(($request->interes/1.18)*0.18, 2, '.', ',');

           // DD($serie);
            $this->crearQr('20133670191', $tipo_comprobante->tipo_serie, $serie->serie,$serie->correlativo,$igv,$subtotal,$request->interes,date('Y-m-d'),$customer->dni);

            $motivonota= null;
            $documentoref = null;          


            $emision = date("d/m/Y");
            $documento = 'DNI';
            $nrocredi = str_pad($request->numero_credito,6,"0", STR_PAD_LEFT);
            //$serie->correlativo
            $nroserie = str_pad($serie->correlativo,6,"0", STR_PAD_LEFT);
           
            $formatter = new NumeroALetras();

            $total_texto = $formatter->toInvoice($request->interes, 2,'SOLES');
            // CREAR PDF
            $html = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='utf-8'>
                <style>
                    body {
                      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                    }
                    .subtitulo, .titulo {
                        font-weight: bold;
                        font-size: 18px;
                    }
                    .cabecera1 {
                        display: block;
                        text-align: center;
                    }
                    .cabecera2 {
                        text-align: center;
                        border: solid 1px #000;
                        border-radius: 10px;
                        font-weight: bold;
                    }
                    .direccion, .direccion2 {
                        font-size: 12px;
                    }
                </style>
            </head>
            <body>
                <table>
                    <tbody>
                        <tr>
                            <td width='10%'>
                                <img src='img/casa.png' width='100%'>
                            </td>
                            <td width='60%'>
                                <div class='cabecera1'>
                                    <!---<span class='titulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>                                   
                                    <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br>-->
                                    <span class='titulo'>CASA DE PRESTAMOS HUANCAYO</span> <br>
                                    <span class='subtitulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>
                                    <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br>
                                    <span class='direccion2'>Jr. Junín 253 - Huancayo - Junín</span><br>
                                </div>
                            </td>
                            <td width='30%'>
                                <div class='cabecera2' style='padding: 0.3rem'>
                                    <span>R.U.C. 20133670191<span><br>
                                    <span>$tipo_comprobante->nombre</span><br>
                                    <span>$serie->serie-$nroserie</span><br>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table style='width: 100%; font-size:12px; padding: 0.3rem;'>
                        <thead>
                            <th colspan='2' width='50%'></th>
                            <th colspan='2' width='50%'></th>   
                        </thead>
                        <tbody>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Fecha de emisión</td>
                                <td width='40%'>: $emision</td>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Tipo de Pago</td>
                                <td width='20%'>: Contado</td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold;'>$documento</td>
                                <td width='40%'>: $customer->dni</td>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Nro Crédito</td>
                                <td width='20%'>: $nrocredi</td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Señor(es)</td>
                                <td width='40%'>: $customer->paterno $customer->materno $customer->nombres </td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.="Doc. Afectación";
                                }
                            $html.="
                                </td>
                                <td width='20%'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.=": $request->serie_ref-$request->correlativo_ref";
                                }
                            $html.="
                                </td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Dirección</td>
                                <td width='40%'>: $customer->direccion</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.="Fecha Documento";
                                }
                            $html.="
                                </td>
                                <td width='20%'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.=": $fecha_ref";
                                }
                            $html.="
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>";
                
                $html.="
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table width='100%' style='font-size:12px; padding: 0.3rem;'>
                        <thead>
                            <th width='10%'>Cant.</th>
                            <th width='60%'>Descripción</th>
                            <th width='15%'>P. Unit</th>
                            <th width='15%'>Importe</th>
                        </thead>
                        <tbody>
                            <tr><td style='border-top: 2px solid #000; padding-bottom: 0.3rem;' colspan='10'></td></tr>";

         
                  
                $quantity = number_format(1, 2, '.', ',');
                $unit_price = number_format($det->valor_unitario, 2, '.', ',');
                $total_unit = number_format($det->importe_total, 2, '.', ',');
                $html.="<tr>
                            <td style='text-align: center;'>$quantity</td>
                            <td>$det->producto</td>
                            <td style='text-align: right;'>$unit_price</td>
                            <td style='text-align: right;'>$total_unit</td>
                        </tr>";
           
            $html.="<tr><td style='border-bottom: 2px solid #000; padding-top: 0.3rem;' colspan='10'></td></tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>SUB TOTAL</td>
                            <td style='text-align: right;'>$subtotal</td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>I.G.V. 18%</td>
                            <td style='text-align: right;'>$igv</td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;font-weight: bold;'>IMPORTE TOTAL</td>
                            <td style='text-align: right;'>$total</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                <table width='100%' style='font-size:12px; padding: 0.3rem;'>
                    <thead>
                        <th width='50%'></th>
                        <th width='50%'></th>
                    </thead>    
                    <tbody>
                        <tr>
                            <td style='text-align:left; vertical-align: top;'><span style='font-weight: bold;'>IMPORTE EN LETRAS</span>: $total_texto</td>
                            <td style='text-align: left;'>
                                <img src='img/qr/$tipo_comprobante->tipo_serie-$serie->serie-$serie->correlativo.png' width='30%'>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style='text-align: center; margin-top:0.2rem'><small style='font-size:8px;'>Representación impresa de la factura electrónica, podrá ser consultada en: <a href='https://ww3.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm' target='_blank' rel='noopener noreferrer'>http://sbhuancayo.website/consulta-comprobante</a></small></div>
            </body>
            </html>";


            
            // FORMATO TICKET
            $htmlticket = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='utf-8'>
                <style>
                    @page{
                        margin: 0;
                        padding:0;
                    }
                    @font-face {
                        font-family: 'Arial Narrow';
                        src: url('../fonts/arial-narrow.ttf');
                    }

                    @font-face {
                        font-family: 'HelveticaNarrow';
                        src: url('../fonts/HelveticaNarrow.ttf');
                    }

                    
                    body {
                        font-size:12px;
                        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                    }
                    .subtitulo, .titulo {
                        font-weight: bold;
                        font-size: 11px;
                    }
                    .cabecera1 {
                        display: block;
                        text-align: center;
                        font-size:12px;
                    }
                    .cabecera2 {
                        text-align: center;
                        font-weight: bold;
                        font-size:12px !important;
                    }
                    .direccion, .direccion2 {
                        font-size: 10px;
                    }
                </style>
            </head>
            <body style='width:100%; padding:0 !important; margin:0 !important'>
                <table style='padding:0 !important; margin:0 !important'>
                    <tbody>
                        <tr>
                            <td style='text-align:center' width='100%'>
                                <img src='img/logocasa2.png' width='30%'>
                            </td>
                        </tr>
                        <tr>
                            <td width='100%'>
                                <div class='cabecera1'>
                                    <small class='titulo'>CASA DE PRESTAMOS HUANCAYO</small> <br>
                                    <span class='subtitulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>
                                    <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br>
                                    <!--<span class='direccion'>Domicilio fiscal: Jr. Junin N° 255 Huancayo - Huancayo - Junín</span><br>-->
                                    <span class='direccion2'>Jr. Junín 253 - Huancayo - Junín</span><br>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width='100%'>
                                <div class='cabecera2' style='padding: 0.3rem'>
                                    <span>R.U.C. 20133670191<span><br>
                                    <span>$tipo_comprobante->nombre</span><br>
                                    <span>$serie->serie-$nroserie</span><br>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div style='margin-top:0.5rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table style='width: 100%; font-size:12px; padding: 0.7rem; font-family: 'HelveticaNarrow' !important;'>
                        <tbody>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>Tipo de Pago</td>
                                <td width='70%'>: Contado</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>Nro Credito</td>
                                <td width='70%'>: $nrocredi</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>Fecha de emisión</td>
                                <td width='70%'>: $emision</td>
                                
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>$documento</td>
                                <td width='70%'>: $customer->dni</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold; vertical-align:top'>Señor(es)</td>
                                <td width='70%'>: $customer->paterno  $customer->materno $customer->nombres</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold; vertical-align:top'>Dirección</td>
                                <td width='70%'>: $customer->direccion</td>
                            </tr>
                        </tbody>
                    </table>
                </div>";
                $htmlticket.="
                <div style='margin-top:0.1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table width='100%' style='font-size:9px; padding: 1.3rem;'>
                        <thead>
                            <th width='10%'>Cant.</th>
                            <th width='60%'>Descripción</th>
                            <th width='15%'>P. Unit</th>
                            <th width='15%'>Importe</th>
                        </thead>
                        <tbody>
                            <tr><td style='border-top: 2px solid #000; padding-bottom: 0.3rem;' colspan='10'></td></tr>";
                             
                $quantity = number_format(1, 2, '.', ',');
                $unit_price = number_format($det->valor_unitario, 2, '.', ',');
                $total_unit = number_format($det->precio_unitario, 2, '.', ',');
                $htmlticket.="<tr>
                            <td style='text-align: center;'>$quantity</td>
                            <td>$det->producto</td>
                            <td style='text-align: right;'>$unit_price</td>
                            <td style='text-align: right;'>$total_unit</td>
                        </tr>";
            
            $htmlticket.="<tr><td style='border-bottom: 2px solid #000; padding-top: 0.3rem;' colspan='10'></td></tr>
                        <tr>
                            <td colspan='2' style='text-align: right;'>SUB TOTAL</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$subtotal</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align: right;'>I.G.V. 18%</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$igv</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align: right;font-weight: bold;'>IMPORTE TOTAL</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$total</td>
                        </tr>
                    </tbody>
                </table>    
            </div>

            <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                <table width='100%' style='font-size:8px;'>
                    <thead>
                        <th width='100%'></th>
                        
                    </thead>    
                    <tbody>
                        <tr>
                            <td style='text-align:left; vertical-align: top; padding: 1 rem'><span style='font-weight: bold;'>IMPORTE EN LETRAS</span>: $total_texto</td>
                        </tr>
                        <tr>
                            <td style='text-align: center;'>
                                <img src='img/qr/$tipo_comprobante->tipo_serie-$serie->serie-$serie->correlativo.png' width='30%'>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <div style='text-align: center; margin-top:0.2rem'><small style='font-size:8px;'>Representación impresa de la factura electrónica, podrá ser consultada en: <a href='https://ww3.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm' target='_blank' rel='noopener noreferrer'>http://sbhuancayo.website/consulta-comprobante</a></small></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </body>
            </html>";


            PDF::loadHTML($html)->setPaper('A4')->save(public_path("docs/a4/$tipo_comprobante->tipo_serie-$serie->serie-$serie->correlativo.pdf"));
            PDF::loadHTML($htmlticket)->setPaper(array(0,0,230,800))->save(public_path("docs/ticket/$tipo_comprobante->tipo_serie-$serie->serie-$serie->correlativo.pdf"));
            // FIN DE CREAR PDF

            // DD('creado', number_format($request->total, 2, '.', ','));
            // ENVIAR FACTURA
            $emisor = array(
                "tipodoc" => "6",
                "ruc" => "20133670191",
                "razon_social" => "SOCIEDAD DE BENEFICENCIA DE HUANCAYO",
                "nombre_comercial" => "SOCIEDAD DE BENEFICENCIA DE HUANCAYO",
                "pais" => "PE",
                "departamento" => "JUNIN",
                "provincia" => "HUANCAYO",
                "distrito" => "HUANCAYO",
                "direccion" => "JR. CUZCO  NRO 1576 JUNIN HUANCAYO",
                "ubigeo" => "120101",
                "usuario_emisor" => "GALA1024",
                "clave_emisor" => "HQJZsOol"
                );
            
            $cliente = array(
                        "tipo_documento" => 1,
                        "ruc" => $customer->dni,
                        "razon_social" => $customer->paterno.' '.$customer->materno.' '.$customer->nombres ,
                        "direccion" => $customer->direccion,
                        "pais" => 'PE',
                        );
            $total = 0;
            $cuotas = array();

            if ($request->tipo_comprobante_ref_id != null) {
                $serie_correlativo = $request->serie_ref.'-'.$request->correlativo_ref;
            }else{
                $serie_correlativo ='';
            }
            
            $cabecera = array(
                        "tipo_operacion"  => "0101",
                        "tipo_comprobante" => $tipo_comprobante->tipo_serie,
                        "moneda"          => 'PEN',
                        "serie"           => $serie->serie,
                        "correlativo"     => $serie->correlativo,
                        "serieycorrelativo" => $serie_correlativo,
                        "total_op_gravadas" => number_format($request->interes/1.18,2),
                        "igv"          => number_format(($request->interes/1.18)*0.18,2),
                        "icbper"       => 0,
                        "total_op_exoneradas"=> 0,
                        "total_op_inafectas" => 0,
                        "total_antes_impuestos" => number_format($request->interes/1.18,2),
                        "total_impuestos"    => number_format(($request->interes/1.18)*0.18,2),
                        "total_despues_impuestos"=> number_format($request->interes,2),
                        "total_a_pagar"      => $request->interes,
                        "fecha_emision"      =>date('Y-m-d'),
                        "hora_emision"    => date('H:i:s'),
                        "fecha_vencimiento" =>date('Y-m-d'),
                        "forma_pago"      => 'Contado',
                        "monto_credito"   => $total,
                        "anexo_sucursal"  => "0000",
                        "total_texto" => $total_texto,
                        "serie_ref" => '',
                        "correlativo_ref" => '',
                        "codmotivo" => '',
                        "descripcion"=> '',
                        "tipo_comprobante_ref_id" => '',
                        );
            
            $cabecera['cuotas'] = $cuotas;
            $items = array();
           
               
                    $codigos = array("S",'10', '1000','IGV','VAT');
                
                $items[] = array(
                    "item" => 1,
                    "cantidad" =>  1,
                    "unidad" => 'NIU',
                    //'INTERES POR EL PRESTMO OTORGADO, CORRESPONDIENTE AL MES DE '.strtoupper($fechas->monthName).' - ' date('Y');
                    "nombre" =>  'INTERES POR EL PRESTAMO OTORGADO, CORRESPONDIENTE AL MES DE '. strtoupper($fechas->monthName).' - '.date('Y'), // CRÉDITO N° '.$request->numero_credito.' CORRESPONDIENTE AL '.$fechas->monthName,//A CUOTA N° '.$request->cuotanro,
                    "valor_unitario" =>  number_format($request->interes/1.18,2),
                    "precio_lista" =>  $request->interes,
                    "valor_total" =>  number_format($request->interes/1.18,2),
                    "igv"  =>  number_format(($request->interes/1.18)*0.18,2),
                    "icbper"  =>  0.00,
                    "factor_icbper"  =>  0.00,
                    "total_antes_impuestos" =>  number_format($request->interes/1.18,2),
                    "total_impuestos" =>  number_format(($request->interes/1.18)*0.18,2),
                    "porcentaje_igv" =>  18,
                    "codigos" =>  $codigos,
                    "tipo_precio" => "01",
                );
            
            // $items =$request->items;

            $nombrexml = $emisor['ruc']."-".$cabecera['tipo_comprobante']."-".$cabecera['serie']."-".$cabecera['correlativo'];
            
            $doc = new DOMDocument();
            $doc->formatOutput = FALSE;
            $doc->preserveWhiteSpace = TRUE;
            $doc->encoding = 'utf-8';
            if ($cabecera['tipo_comprobante'] == "03" || $cabecera['tipo_comprobante'] == "01") {
                $this->CrearXMLFactura($nombrexml,$emisor, $cliente, $cabecera, $items);
            } else if($cabecera['tipo_comprobante'] == "07") {
                $this->CrearXMLNotaCredito($nombrexml, $emisor, $cliente, $cabecera, $items);
            } else if($cabecera['tipo_comprobante'] == "08") {
                $this->CrearXMLNotaDebito($nombrexml, $emisor, $cliente, $cabecera, $items);
            }
            
            $flg_firma = "0";
            $ruta = public_path('storage/sinfirmar/'.$nombrexml.'.xml');
            $ruta2 = public_path('storage/firmado/'.$nombrexml.'.xml');
            $ruta3 = public_path('storage/firmado/'.$nombrexml);
            $ruta4 = public_path('storage/cdr/');
            $rutaerror = public_path('storage/sunatRpta/');
                
            $ruta_firma = public_path('storage/Beneficencia2022.pem');
            $pass_firma = "wadsp8.323dZx&";
                
    
            $signer = new SignedXml();
            $signer->setCertificateFromFile($ruta_firma);            
            $xmlSigned = $signer->signFromFile($ruta);            
            file_put_contents($ruta2, $xmlSigned);    
            $zip = new ZipArchive();
            $nombrezip = $nombrexml.".ZIP";
            $rutazip = $ruta3.".ZIP";
    
            if($zip->open($rutazip,ZIPARCHIVE::CREATE)===true){
                $zip->addFile($ruta2, $nombrexml.'.xml');
                $zip->close();
            }
            //PASO 04
            //PREPARAR EL ENVÍO DEL XML
            $contenido_del_zip = base64_encode(file_get_contents($rutazip));
            $xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" 
                    xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                    <soapenv:Header>
                        <wsse:Security>
                            <wsse:UsernameToken>
                                <wsse:Username>'.$emisor['ruc'].$emisor['usuario_emisor'].'</wsse:Username>
                <wsse:Password>'.$emisor['clave_emisor'].'</wsse:Password>
                            </wsse:UsernameToken>
                        </wsse:Security>
                </soapenv:Header>
                <soapenv:Body>
                <ser:sendBill>
                    <fileName>'.$nombrezip.'</fileName>
                    <contentFile>'.$contenido_del_zip.'</contentFile>
                </ser:sendBill>
                </soapenv:Body>
            </soapenv:Envelope>';
    
                //PASO 05
            //ENVÍO DEL CPE A WS DE SUNAT
            //https://www.sunat.gob.pe/ol-ti-itcpfegem/billService
            //https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService

            $user = auth('sanctum')->user();
            $role = $user->getRoleNames();
            // DD($role);
            // $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            $header = array(
                        "Content-type: text/xml; charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: ",
                        "Content-lenght: ".strlen($xml_envio)
                    );
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_URL,$ws);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
            curl_setopt($ch,CURLOPT_TIMEOUT,30);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
            curl_setopt($ch, CURLOPT_CAINFO, public_path('storage/cacert.pem'));
            $response = curl_exec($ch);
            //PASO 06 
            // OBTENEMOS RESPUESTA (CDR)
            $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            $responseCode='';
            $mensaje ='';
            //DD($response,$httpcode);
            //DD($httpcode);
            if($httpcode == 200){
                $doc = new DOMDocument();
                $doc->loadXML($response);
                 //DD($doc);
                if(isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)){
                    $cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue;
                    $cdr = base64_decode($cdr);
                    // DD($cdr);
                    file_put_contents($ruta4."R-".$nombrezip, $cdr);
                    $zip = new ZipArchive;
                    if($zip->open($ruta4."R-".$nombrezip)===true){
                        $zip->extractTo($ruta4.'R-'.$nombrexml);
                        $zip->close();
                    }
                    $docCDR = new DOMDocument();
                    $cdrContent = file_get_contents($ruta4.'R-'.$nombrexml.'/'.'R-'.$nombrexml.'.XML');
                    $docCDR->loadXML($cdrContent);
            
                    $responseCode = $docCDR->getElementsByTagName("ResponseCode")->item(0)->nodeValue;
                    if($responseCode==0){
                        $resultado = "FACTURA APROBADA";   
                    }else{
                        $resultado = "FACTURA RECHAZADA CON CODIGO DE ERROR:".$responseCode;
                    }
                    $descri= $docCDR->getElementsByTagName("Description")->item(0)->nodeValue;
                    $codigo='';
                    $mensaje='';
                }else{
                    $descri=''; 
                    $resultado='';
                    file_put_contents($rutaerror."R-".$nombrexml, $doc);
                    $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
                    $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
                   // DD($mensaje);
                }
            } else {
                DD(curl_error($ch));
                // echo "Problema de conexión";
            }
            DB::table('comprobante')->where('id',$comprobante->id)->update(['nombrexml'=>$nombrexml,'codigo_sunat'=>$responseCode,'mensaje_sunat'=>$mensaje,'estado_comprobante'=>1]);
            DB::table('pago')->where('id',$request->id_pago)->update(['idcomprobante'=>$comprobante->id,'comprobante'=>$cabecera['tipo_comprobante']."-".$cabecera['serie']."-".$cabecera['correlativo']]);

            curl_close($ch);
            // DD($codigo.": ".$mensaje."  "."d ".$descri." f ".$resultado); 
            // FIN ENVIAR DE FACTURA

            DB::commit();
            
            return $comprobante;
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }
    public function cuotasVencidasCompro1(Request $request){

        try {
            
            $customer = DB::table('persona')->where('id', 301)->get()->first();
            $tipo_comprobante = DB::table('tipo_comprobante')->where('id',2)->get()->first();
            $serie = DB::table('serie')->where('id',2)->get()->first();
            $fechas = Carbon::now()->locale('es');
            DB::beginTransaction();
            $comprobante = new Comprobante;
           
            $comprobante->emisor_id = 1;
            $comprobante->tipo_comprobante_id = '03';
            $comprobante->serie_id = $serie->id;
            $comprobante->serie = $serie->serie;
            $comprobante->correlativo = $serie->correlativo;
            $comprobante->forma_pago = 'Contado';
            $comprobante->fecha_emision = date('Y-m-d');
            $comprobante->hora_emision = date('H:i:s');
            $comprobante->fecha_vencimiento = date('Y-m-d');
            $comprobante->moneda_id = 'PEN';
            $comprobante->op_gravadas = 335.76/1.18;
            $comprobante->op_exoneradas = 0;
            $comprobante->op_inafectas = 0;
            $comprobante->igv = (335.76/1.18)*0.18;
            $comprobante->total = 335.76;
            $comprobante->cliente_id = $customer->id;
            $comprobante->tipo_comprobante_ref_id = '';
            $comprobante->serie_ref = '';
            $comprobante->correlativo_ref = 0;
            $comprobante->codmotivo = '';
            $comprobante->iddifunto = 0;
            $comprobante->id_tipodoccliente = 1;
            $comprobante->cliente = $customer->nom_comp;
            $comprobante->numerodoc = $customer->dni;
            $comprobante->direccion = $customer->direccion;
            $comprobante->iduser=auth('sanctum')->user()->id;
            $comprobante->idcuota = 0;
            $comprobante->id_detallecrono = $request->id;
            $comprobante->save();    
            
                
                $det = new Detalle;
                $det->comprobante_id = $comprobante->id;
                $det->item = 1;
                $det->producto_id = 1;
                $det->cantidad = 1;
                $det->valor_unitario = 335.76/1.18;
                $det->precio_unitario = 335.76;
                $det->igv = (335.76/1.18)*0.18;
                $det->porcentaje_igv = 18;
                $det->valor_total = 335.76/1.18;
                $det->importe_total = 335.76;
                //INTERES POR EL PRESTMO OTORGADO, CORRESPONDIENTE AL MES DE SETIEMBRE -2022
                //PAGO POR EL INTERÉS CORRESPONDIENTE AL MES DE 
                $det->producto = 'INTERES POR LA CANCELACION DE CREDITO';   //. //$request->cuotanro . '.';
                $det->descripcion = '';    
                $det->save();
          
           
                           
            DB::table('serie')->where('id',$serie->id)->update(['correlativo'=> $serie->correlativo+1]);
            $total = number_format(335.76, 2, '.', ',');
            $subtotal = number_format(335.76/1.18, 2, '.', ',');
            $igv = number_format((335.76/1.18)*0.18, 2, '.', ',');

           // DD($serie);
            $this->crearQr('20133670191', $tipo_comprobante->tipo_serie, $serie->serie,$serie->correlativo,$igv,$subtotal,$request->interes,date('Y-m-d'),$customer->dni);

            $motivonota= null;
            $documentoref = null;
            


            $emision = date("d/m/Y");
            $documento = 'DNI';
            $nrocredi = str_pad(159,6,"0", STR_PAD_LEFT);
            //$serie->correlativo
            $nroserie = str_pad($serie->correlativo,6,"0", STR_PAD_LEFT);
            // $total = number_format($request->interes, 2, '.', ',');
            // $subtotal = number_format($request->interes/1.18, 2, '.', ',');
            // $igv = number_format(($request->interes/1.18)*0.18, 2, '.', ',');
            $formatter = new NumeroALetras();

            $total_texto = $formatter->toInvoice(335.76, 2,'SOLES');
            // CREAR PDF
            $html = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='utf-8'>
                <style>
                    body {
                      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                    }
                    .subtitulo, .titulo {
                        font-weight: bold;
                        font-size: 18px;
                    }
                    .cabecera1 {
                        display: block;
                        text-align: center;
                    }
                    .cabecera2 {
                        text-align: center;
                        border: solid 1px #000;
                        border-radius: 10px;
                        font-weight: bold;
                    }
                    .direccion, .direccion2 {
                        font-size: 12px;
                    }
                </style>
            </head>
            <body>
                <table>
                    <tbody>
                        <tr>
                            <td width='10%'>
                                <img src='img/casa.png' width='100%'>
                            </td>
                            <td width='60%'>
                                <div class='cabecera1'>
                                    <!---<span class='titulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>                                   
                                    <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br>-->
                                    <span class='titulo'>CASA DE PRESTAMOS HUANCAYO</span> <br>
                                    <span class='subtitulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>
                                    <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br>
                                    <span class='direccion2'>Jr. Junín 253 - Huancayo - Junín</span><br>
                                </div>
                            </td>
                            <td width='30%'>
                                <div class='cabecera2' style='padding: 0.3rem'>
                                    <span>R.U.C. 20133670191<span><br>
                                    <span>$tipo_comprobante->nombre</span><br>
                                    <span>$serie->serie-$nroserie</span><br>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table style='width: 100%; font-size:12px; padding: 0.3rem;'>
                        <thead>
                            <th colspan='2' width='50%'></th>
                            <th colspan='2' width='50%'></th>   
                        </thead>
                        <tbody>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Fecha de emisión</td>
                                <td width='40%'>: $emision</td>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Tipo de Pago</td>
                                <td width='20%'>: Contado</td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold;'>$documento</td>
                                <td width='40%'>: $customer->dni</td>
                                <td width='20%' style='text-align: left; font-weight: bold;'>Nro Crédito</td>
                                <td width='20%'>: $nrocredi</td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Señor(es)</td>
                                <td width='40%'>: $customer->paterno $customer->materno $customer->nombres </td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.="Doc. Afectación";
                                }
                            $html.="
                                </td>
                                <td width='20%'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.=": $request->serie_ref-$request->correlativo_ref";
                                }
                            $html.="
                                </td>
                            </tr>
                            <tr>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>Dirección</td>
                                <td width='40%'>: $customer->direccion</td>
                                <td width='20%' style='text-align: left; font-weight: bold; vertical-align:top'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.="Fecha Documento";
                                }
                            $html.="
                                </td>
                                <td width='20%'>";
                                if ($request->tipo_comprobante_ref_id != null) {
                                    $html.=": $fecha_ref";
                                }
                            $html.="
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>";
                
                $html.="
                <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table width='100%' style='font-size:12px; padding: 0.3rem;'>
                        <thead>
                            <th width='10%'>Cant.</th>
                            <th width='60%'>Descripción</th>
                            <th width='15%'>P. Unit</th>
                            <th width='15%'>Importe</th>
                        </thead>
                        <tbody>
                            <tr><td style='border-top: 2px solid #000; padding-bottom: 0.3rem;' colspan='10'></td></tr>";

         
                  
                $quantity = number_format(1, 2, '.', ',');
                $unit_price = number_format($det->valor_unitario, 2, '.', ',');
                $total_unit = number_format($det->importe_total, 2, '.', ',');
                $html.="<tr>
                            <td style='text-align: center;'>$quantity</td>
                            <td>$det->producto</td>
                            <td style='text-align: right;'>$unit_price</td>
                            <td style='text-align: right;'>$total_unit</td>
                        </tr>";
           
            $html.="<tr><td style='border-bottom: 2px solid #000; padding-top: 0.3rem;' colspan='10'></td></tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>SUB TOTAL</td>
                            <td style='text-align: right;'>$subtotal</td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>I.G.V. 18%</td>
                            <td style='text-align: right;'>$igv</td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;font-weight: bold;'>IMPORTE TOTAL</td>
                            <td style='text-align: right;'>$total</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                <table width='100%' style='font-size:12px; padding: 0.3rem;'>
                    <thead>
                        <th width='50%'></th>
                        <th width='50%'></th>
                    </thead>    
                    <tbody>
                        <tr>
                            <td style='text-align:left; vertical-align: top;'><span style='font-weight: bold;'>IMPORTE EN LETRAS</span>: $total_texto</td>
                            <td style='text-align: left;'>
                                <img src='img/qr/$tipo_comprobante->tipo_serie-$serie->serie-$serie->correlativo.png' width='30%'>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style='text-align: center; margin-top:0.2rem'><small style='font-size:8px;'>Representación impresa de la factura electrónica, podrá ser consultada en: <a href='https://ww3.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm' target='_blank' rel='noopener noreferrer'>http://sbhuancayo.website/consulta-comprobante</a></small></div>
            </body>
            </html>";


            
            // FORMATO TICKET
            $htmlticket = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='utf-8'>
                <style>
                    @page{
                        margin: 0;
                        padding:0;
                    }
                    @font-face {
                        font-family: 'Arial Narrow';
                        src: url('../fonts/arial-narrow.ttf');
                    }

                    @font-face {
                        font-family: 'HelveticaNarrow';
                        src: url('../fonts/HelveticaNarrow.ttf');
                    }

                    
                    body {
                        font-size:12px;
                        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                    }
                    .subtitulo, .titulo {
                        font-weight: bold;
                        font-size: 11px;
                    }
                    .cabecera1 {
                        display: block;
                        text-align: center;
                        font-size:12px;
                    }
                    .cabecera2 {
                        text-align: center;
                        font-weight: bold;
                        font-size:12px !important;
                    }
                    .direccion, .direccion2 {
                        font-size: 10px;
                    }
                </style>
            </head>
            <body style='width:100%; padding:0 !important; margin:0 !important'>
                <table style='padding:0 !important; margin:0 !important'>
                    <tbody>
                        <tr>
                            <td style='text-align:center' width='100%'>
                                <img src='img/logocasa2.png' width='30%'>
                            </td>
                        </tr>
                        <tr>
                            <td width='100%'>
                                <div class='cabecera1'>
                                    <small class='titulo'>CASA DE PRESTAMOS HUANCAYO</small> <br>
                                    <span class='subtitulo'>SOCIEDAD DE BENEFICENCIA DE HUANCAYO</span> <br>
                                    <span class='direccion'>Domicilio fiscal: Jr. Cusco 1576 Hyo Teléfono. 217868</span><br>
                                    <!--<span class='direccion'>Domicilio fiscal: Jr. Junin N° 255 Huancayo - Huancayo - Junín</span><br>-->
                                    <span class='direccion2'>Jr. Junín 253 - Huancayo - Junín</span><br>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width='100%'>
                                <div class='cabecera2' style='padding: 0.3rem'>
                                    <span>R.U.C. 20133670191<span><br>
                                    <span>$tipo_comprobante->nombre</span><br>
                                    <span>$serie->serie-$nroserie</span><br>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div style='margin-top:0.5rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table style='width: 100%; font-size:12px; padding: 0.7rem; font-family: 'HelveticaNarrow' !important;'>
                        <tbody>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>Tipo de Pago</td>
                                <td width='70%'>: Contado</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>Nro Credito</td>
                                <td width='70%'>: $nrocredi</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>Fecha de emisión</td>
                                <td width='70%'>: $emision</td>
                                
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold;'>$documento</td>
                                <td width='70%'>: $customer->dni</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold; vertical-align:top'>Señor(es)</td>
                                <td width='70%'>: $customer->paterno  $customer->materno $customer->nombres</td>
                            </tr>
                            <tr>
                                <td width='30%' style='text-align: left; font-weight: bold; vertical-align:top'>Dirección</td>
                                <td width='70%'>: $customer->direccion</td>
                            </tr>
                        </tbody>
                    </table>
                </div>";
                $htmlticket.="
                <div style='margin-top:0.1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                    <table width='100%' style='font-size:9px; padding: 1.3rem;'>
                        <thead>
                            <th width='10%'>Cant.</th>
                            <th width='60%'>Descripción</th>
                            <th width='15%'>P. Unit</th>
                            <th width='15%'>Importe</th>
                        </thead>
                        <tbody>
                            <tr><td style='border-top: 2px solid #000; padding-bottom: 0.3rem;' colspan='10'></td></tr>";
                             
                $quantity = number_format(1, 2, '.', ',');
                $unit_price = number_format($det->valor_unitario, 2, '.', ',');
                $total_unit = number_format($det->precio_unitario, 2, '.', ',');
                $htmlticket.="<tr>
                            <td style='text-align: center;'>$quantity</td>
                            <td>$det->producto</td>
                            <td style='text-align: right;'>$unit_price</td>
                            <td style='text-align: right;'>$total_unit</td>
                        </tr>";
            
            $htmlticket.="<tr><td style='border-bottom: 2px solid #000; padding-top: 0.3rem;' colspan='10'></td></tr>
                        <tr>
                            <td colspan='2' style='text-align: right;'>SUB TOTAL</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$subtotal</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align: right;'>I.G.V. 18%</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$igv</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align: right;font-weight: bold;'>IMPORTE TOTAL</td>
                            <td style='text-align:left'> S/.</td>
                            <td style='text-align: right;'>$total</td>
                        </tr>
                    </tbody>
                </table>    
            </div>

            <div style='margin-top:1rem; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.515);'>
                <table width='100%' style='font-size:8px;'>
                    <thead>
                        <th width='100%'></th>
                        
                    </thead>    
                    <tbody>
                        <tr>
                            <td style='text-align:left; vertical-align: top; padding: 1 rem'><span style='font-weight: bold;'>IMPORTE EN LETRAS</span>: $total_texto</td>
                        </tr>
                        <tr>
                            <td style='text-align: center;'>
                                <img src='img/qr/$tipo_comprobante->tipo_serie-$serie->serie-$serie->correlativo.png' width='30%'>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <div style='text-align: center; margin-top:0.2rem'><small style='font-size:8px;'>Representación impresa de la factura electrónica, podrá ser consultada en: <a href='https://ww3.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm' target='_blank' rel='noopener noreferrer'>http://sbhuancayo.website/consulta-comprobante</a></small></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </body>
            </html>";


            PDF::loadHTML($html)->setPaper('A4')->save(public_path("docs/a4/$tipo_comprobante->tipo_serie-$serie->serie-$serie->correlativo.pdf"));
            PDF::loadHTML($htmlticket)->setPaper(array(0,0,230,800))->save(public_path("docs/ticket/$tipo_comprobante->tipo_serie-$serie->serie-$serie->correlativo.pdf"));
            // FIN DE CREAR PDF

            // DD('creado', number_format($request->total, 2, '.', ','));
            // ENVIAR FACTURA
            $emisor = array(
                "tipodoc" => "6",
                "ruc" => "20133670191",
                "razon_social" => "SOCIEDAD DE BENEFICENCIA DE HUANCAYO",
                "nombre_comercial" => "SOCIEDAD DE BENEFICENCIA DE HUANCAYO",
                "pais" => "PE",
                "departamento" => "JUNIN",
                "provincia" => "HUANCAYO",
                "distrito" => "HUANCAYO",
                "direccion" => "JR. CUZCO  NRO 1576 JUNIN HUANCAYO",
                "ubigeo" => "120101",
                "usuario_emisor" => "GALA1024",
                "clave_emisor" => "HQJZsOol"
                );
            
            $cliente = array(
                        "tipo_documento" => 1,
                        "ruc" => $customer->dni,
                        "razon_social" => $customer->paterno.' '.$customer->materno.' '.$customer->nombres ,
                        "direccion" => $customer->direccion,
                        "pais" => 'PE',
                        );
            $total = 0;
            $cuotas = array();

            // foreach ($request->payments as $i) {
            //     $total += $i['payment'];
            //     $cuotas[] = array(
            //         "numero" => "00".$i['description'],
            //         "vencimiento" => $i['date_of_payment'],
            //         "importe" => $i['payment'],
            //     );
            // }

            // $letras = $formatter->toInvoice($request->total, 2);
            // DD($letras, $letrasgirado);
            //$serie_correlativo=null;
            if ($request->tipo_comprobante_ref_id != null) {
                $serie_correlativo = $request->serie_ref.'-'.$request->correlativo_ref;
            }else{
                $serie_correlativo ='';
            }
            
            $cabecera = array(
                        "tipo_operacion"  => "0101",
                        "tipo_comprobante" => $tipo_comprobante->tipo_serie,
                        "moneda"          => 'PEN',
                        "serie"           => $serie->serie,
                        "correlativo"     => $serie->correlativo,
                        "serieycorrelativo" => $serie_correlativo,
                        "total_op_gravadas" => number_format(335.76/1.18,2),
                        "igv"          => number_format((335.76/1.18)*0.18,2),
                        "icbper"       => 0,
                        "total_op_exoneradas"=> 0,
                        "total_op_inafectas" => 0,
                        "total_antes_impuestos" => number_format(335.76/1.18,2),
                        "total_impuestos"    => number_format((335.76/1.18)*0.18,2),
                        "total_despues_impuestos"=> number_format(335.76,2),
                        "total_a_pagar"      => number_format(335.76,2),
                        "fecha_emision"      =>date('Y-m-d'),
                        "hora_emision"    => date('H:i:s'),
                        "fecha_vencimiento" =>date('Y-m-d'),
                        "forma_pago"      => 'Contado',
                        "monto_credito"   => $total,
                        "anexo_sucursal"  => "0000",
                        "total_texto" => $total_texto,
                        "serie_ref" => '',
                        "correlativo_ref" => '',
                        "codmotivo" => '',
                        "descripcion"=> '',
                        "tipo_comprobante_ref_id" => '',
                        );
            
            $cabecera['cuotas'] = $cuotas;
            $items = array();
           
               
                    $codigos = array("S",'10', '1000','IGV','VAT');
                
                $items[] = array(
                    "item" => 1,
                    "cantidad" =>  1,
                    "unidad" => 'NIU',
                    //'INTERES POR EL PRESTMO OTORGADO, CORRESPONDIENTE AL MES DE '.strtoupper($fechas->monthName).' - ' date('Y');
                    "nombre" =>  'INTERES POR LA CANCELACION DE CREDITO', // CRÉDITO N° '.$request->numero_credito.' CORRESPONDIENTE AL '.$fechas->monthName,//A CUOTA N° '.$request->cuotanro,
                    "valor_unitario" =>  number_format(335.76/1.18,2),
                    "precio_lista" =>  number_format(335.76,2),
                    "valor_total" =>  number_format(335.76/1.18,2),
                    "igv"  =>  number_format((335.76/1.18)*0.18,2),
                    "icbper"  =>  0.00,
                    "factor_icbper"  =>  0.00,
                    "total_antes_impuestos" =>  number_format(335.76/1.18,2),
                    "total_impuestos" =>  number_format((335.76/1.18)*0.18,2),
                    "porcentaje_igv" =>  18,
                    "codigos" =>  $codigos,
                    "tipo_precio" => "01",
                );
            
            // $items =$request->items;

            $nombrexml = $emisor['ruc']."-".$cabecera['tipo_comprobante']."-".$cabecera['serie']."-".$cabecera['correlativo'];
            
            $doc = new DOMDocument();
            $doc->formatOutput = FALSE;
            $doc->preserveWhiteSpace = TRUE;
            $doc->encoding = 'utf-8';
            if ($cabecera['tipo_comprobante'] == "03" || $cabecera['tipo_comprobante'] == "01") {
                $this->CrearXMLFactura($nombrexml,$emisor, $cliente, $cabecera, $items);
            } else if($cabecera['tipo_comprobante'] == "07") {
                $this->CrearXMLNotaCredito($nombrexml, $emisor, $cliente, $cabecera, $items);
            } else if($cabecera['tipo_comprobante'] == "08") {
                $this->CrearXMLNotaDebito($nombrexml, $emisor, $cliente, $cabecera, $items);
            }
            
            $flg_firma = "0";
            $ruta = public_path('storage/sinfirmar/'.$nombrexml.'.xml');
            $ruta2 = public_path('storage/firmado/'.$nombrexml.'.xml');
            $ruta3 = public_path('storage/firmado/'.$nombrexml);
            $ruta4 = public_path('storage/cdr/');
            $rutaerror = public_path('storage/sunatRpta/');
                
            $ruta_firma = public_path('storage/Beneficencia2022.pem');
            $pass_firma = "wadsp8.323dZx&";
                
    
            $signer = new SignedXml();
            $signer->setCertificateFromFile($ruta_firma);
            
            $xmlSigned = $signer->signFromFile($ruta);
            
            file_put_contents($ruta2, $xmlSigned);
    
            $zip = new ZipArchive();
            $nombrezip = $nombrexml.".ZIP";
            $rutazip = $ruta3.".ZIP";
    
            if($zip->open($rutazip,ZIPARCHIVE::CREATE)===true){
                $zip->addFile($ruta2, $nombrexml.'.xml');
                $zip->close();
            }
            //PASO 04
            //PREPARAR EL ENVÍO DEL XML
            $contenido_del_zip = base64_encode(file_get_contents($rutazip));
            $xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" 
                    xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                    <soapenv:Header>
                        <wsse:Security>
                            <wsse:UsernameToken>
                                <wsse:Username>'.$emisor['ruc'].$emisor['usuario_emisor'].'</wsse:Username>
                <wsse:Password>'.$emisor['clave_emisor'].'</wsse:Password>
                            </wsse:UsernameToken>
                        </wsse:Security>
                </soapenv:Header>
                <soapenv:Body>
                <ser:sendBill>
                    <fileName>'.$nombrezip.'</fileName>
                    <contentFile>'.$contenido_del_zip.'</contentFile>
                </ser:sendBill>
                </soapenv:Body>
            </soapenv:Envelope>';
    
                //PASO 05
            //ENVÍO DEL CPE A WS DE SUNAT
            //https://www.sunat.gob.pe/ol-ti-itcpfegem/billService
            //https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService

            $user = auth('sanctum')->user();
            $role = $user->getRoleNames();
            // DD($role);
            // $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            $header = array(
                        "Content-type: text/xml; charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: ",
                        "Content-lenght: ".strlen($xml_envio)
                    );
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_URL,$ws);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
            curl_setopt($ch,CURLOPT_TIMEOUT,30);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
            curl_setopt($ch, CURLOPT_CAINFO, public_path('storage/cacert.pem'));
            $response = curl_exec($ch);
            //PASO 06 
            // OBTENEMOS RESPUESTA (CDR)
            $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            $responseCode='';
            $mensaje ='';
             //DD($response,$httpcode);
            //DD($httpcode);
            if($httpcode == 200){
                $doc = new DOMDocument();
                $doc->loadXML($response);
                 //DD($doc);
                if(isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)){
                    $cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue;
                    $cdr = base64_decode($cdr);
                    // DD($cdr);
                    file_put_contents($ruta4."R-".$nombrezip, $cdr);
                    $zip = new ZipArchive;
                    if($zip->open($ruta4."R-".$nombrezip)===true){
                        $zip->extractTo($ruta4.'R-'.$nombrexml);
                        $zip->close();
                    }
                    $docCDR = new DOMDocument();
                    $cdrContent = file_get_contents($ruta4.'R-'.$nombrexml.'/'.'R-'.$nombrexml.'.XML');
                    $docCDR->loadXML($cdrContent);
            
                    $responseCode = $docCDR->getElementsByTagName("ResponseCode")->item(0)->nodeValue;
                    if($responseCode==0){
                        $resultado = "FACTURA APROBADA";   
                    }else{
                        $resultado = "FACTURA RECHAZADA CON CODIGO DE ERROR:".$responseCode;
                    }
                    $descri= $docCDR->getElementsByTagName("Description")->item(0)->nodeValue;
                    $codigo='';
                    $mensaje='';
                }else{
                    $descri=''; 
                    $resultado='';
                    file_put_contents($rutaerror."R-".$nombrexml, $doc);
                    $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
                    $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
                   // DD($mensaje);
                }
            } else {
                DD(curl_error($ch));
                // echo "Problema de conexión";
            }
            DB::table('comprobante')->where('id',$comprobante->id)->update(['nombrexml'=>$nombrexml,'codigo_sunat'=>$responseCode,'mensaje_sunat'=>$mensaje,'estado_comprobante'=>1]);
            DB::table('pago')->where('id',$request->id_pago)->update(['idcomprobante'=>$comprobante->id,'comprobante'=>$cabecera['tipo_comprobante']."-".$cabecera['serie']."-".$cabecera['correlativo']]);

            curl_close($ch);
            // DD($codigo.": ".$mensaje."  "."d ".$descri." f ".$resultado); 
            // FIN ENVIAR DE FACTURA

            DB::commit();
            
            return $comprobante;
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

}
