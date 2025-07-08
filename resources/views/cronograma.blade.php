@extends('layouts.print')
@section('estilo')
<style>

    table {
        border-collapse: collapse;
        font-weight: 100 !important;
    }

    .page-header, .page-header-space {
        height: 253px;
        /* height: 274px; */
        /* z-index: 101; */
    }
  
    .page-footer, .page-footer-space {
        z-index: 101;
        height: 50px;
    
    }
    
    .page-footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        background: white; /* for demo */
    }
    
    .page-header {
        position: fixed;
        /* margin-right: 10px !important; */
        top: 0;
        width: 100%;
        /* border-bottom: 1px solid black; */
        background: white; /* for demo */
    }
    
    .page {
        page-break-after: always;
    }
    
    @page {
        margin: 20mm
    }
    
    @media print {
        thead {display: table-header-group;} 
        tfoot {display: table-footer-group;}
        
        button {display: none;}
        
        body {margin: 0;}
        .marca-agua {
            visibility: visible !important;
        }
    }

    .floot {
        position: fixed;
        z-index: 100;
        margin-top: -15px;
        visibility: hidden;
        /* display: none; */
    }

    .firma{
        position: fixed;
        z-index: 102;
        bottom: 60px;
    }

    .sello{
        position: fixed;
        z-index: 102;
        bottom: 75px;
    }

    .marca-agua {
        position: fixed;
        left: 30%;
        margin-top: 15%;
        z-index: 0;
        /* visibility: hidden; */
    }

    .btnprn {
        background-color: rgb(221, 221, 221);
        border:none;
        padding: 0.5%;
        transition: 0.2s;
        border-radius: 10%;
        /* margin-left: -26.5%; */
    }
    .btnprn:hover {
        cursor: pointer;
        background-color: rgb(179, 179, 179);
        transition: 0.2s;
    }


    .table-cuotas{
        width: 100%
    }
</style>


@endsection
@section('content')
    <div class="page-header" style=" width:196.1mm; margin-left:16px !important;">
        <button class="btnprn btn" onclick="empri()"> Imprimir</button>
        <h2 style="text-align: center">Reporte de Cronograma de Cuotas</h2>
        <table border="0" style="width: 100%; font-size: 11px">
            <tr>
                <td colspan="6" style="text-align: right"><strong>FECHA: </strong>  {{ date_format(date_create( $credito->fecha ),"d/m/Y")  }}</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: left"><strong>CREDITO N°: </strong> C000{{ $credito->numero}}</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: left"><strong>CLIENTE: </strong> {{ $credito->cliente['paterno'] ." ". $credito->cliente['materno'] ."  ". $credito->cliente['nombres'] }}</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: left"><strong>DNI: </strong> {{ $credito->cliente['dni'] }}</td>
            </tr>
            <tr>
                <td style="width: 20%; padding-top: 0.5%;"><strong>Tasa Mensual Seguro de Desgravamen : </strong></td>
                <td style="width: 20%; padding-top: 0.5%;">{{ floatval($credito->cronograma['tasa_men_desgra'] )*100}} %</td>
                <td style="width: 15%; padding-top: 0.5%;"><strong>Comisión Mensual Dscto Automático : </strong></td>
                <td style="width: 20%; padding-top: 0.5%;">{{ number_format($credito->cronograma['com_desc_auto'], 2, '.', ',') }}</td>
                <td style="width: 10%; padding-top: 0.5%;"><strong>Tasa efectiva anual : </strong></td>
                <td style="width: 20%; padding-top: 0.5%;">{{ number_format( $credito->cronograma['tasa_efe_anu'], 2, '.', ',') }} %</td>
            </tr>

            <tr>
                <td style="padding-top: 1%;"><strong>Total interés : </strong></td>
                <td style="padding-top: 1%;">S/. {{ number_format($credito->cronograma['t_interes'], 2, '.', ',')  }}</td>
                <td style="padding-top: 1%;"><strong>Num Cuotas : </strong></td>
                <td style="padding-top: 1%;">{{ $credito->cronograma['num_cuotas'] }}</td>
                <td style="padding-top: 1%;"><strong>Periocidad : </strong></td>
                <td style="padding-top: 1%;">{{ $credito->cronograma['periocidad'] }}</td>
            </tr>

            <tr>
                <td style="padding-top: 1%; "><strong>F. venc. última cuota : </strong></td>
                <td style="padding-top: 1%;">{{ date_format(date_create(  $credito->cronograma['f_ultima_cuota'] ),"d/m/Y") }}</td>
                <td style="padding-top: 1%;"><strong>Comisión por Desembolso  : </strong></td>
                <td style="padding-top: 1%;">{{ number_format( $credito->cronograma['com_desembolso'], 2, '.', ',')  }}</td>

                <td style="padding-top: 1%;"><strong>Fecha de Desembolso  : </strong></td>
                <td style="padding-top: 1%;">{{ date_format(date_create( $credito->cronograma['f_desembolso'] ),"d/m/Y")}}</td>
                
            </tr>
        </table>

        <table class="table-cuotas" border="1" style="font-size: 12px;">
            <tr style="text-align: center; font-weight: bold;">
                <td style="width: 3%">N°</td>
                <td style="width: 10%; ">Fecha Venc.</td>
                <td style="width: 10%; ">Saldo Capital</td>
                <td style="width: 10%; ">Capital Amortizado</td>
                <td style="width: 10%; ">Interes</td>
                <td style="width: 10%; ">Com. Desc. Automático</td>
                <td style="width: 10%; ">Seguro Desgrav</td>
                <td style="width: 10%; ">Cuota</td>
            </tr>
        </table>

    </div>

    <div class="page-footer">
    </div>

    <table style="width:196.1mm;!important; margin-left:15px !important;">
      <thead>
        <tr>
          <td>
            <div class="page-header-space"></div>
          </td>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>
            <div class="marca-agua" style="width:196.1mm;">
                <img src="{{ URL::asset('storage/logo.png') }}" alt="SBH" style="width:300px; opacity: 0.2;">
                {{-- <img src="/img/logo.png" alt="SBH" style="width:300px; opacity: 0.2;"> --}}
            </div>
            {{-- <div class="floot" style="width:196.1mm;">
                <hr size="690" style="border: 0.2px solid grey; width: 0px; float: left;">
                <hr size="690" style="border: 0.2px solid transparent; width: 104px; float: left;">
                <hr size="690" style="border: 0.2px solid grey; width: 0px; float: left;">
                <hr size="690" style="border: 0.2px solid transparent; width: 62px; float: left;">
                <hr size="690" style="border: 0.2px solid grey; width: 0px; float: left;">
                <hr size="690" style="border: 0.2px solid transparent; width: 72px; float: left;">
                <hr size="690" style="border: 0.2px solid grey; width: 0px; float: left;">
                <hr size="690" style="border: 0.2px solid transparent; width: 271px; float: left;">
                <hr size="690" style="border: 0.2px solid grey; width: 0px; float: left;">
                <hr size="690" style="border: 0.2px solid transparent; width: 104px; float: left;">
                <hr size="690" style="border: 0.2px solid grey; width: 0px; float: left;">
                <hr size="690" style="border: 0.2px solid transparent; width: 101px; float: left;">
                <hr size="690" style="border: 0.2px solid grey; width: 0px; float: left;">
            </div> --}}
                
            <div class="page" style="width:196.1mm; z-index: 101;">
                {{-- <div class="vertical-line" style="height: 45px;"></div> --}}
                
                
                <table class="table-cuotas" border="1" style=" font-size: 12px"">
                    {{-- <tr style="text-align: center; font-weight: bold;">
                        <td style="width: 3%; ">N°</td>
                        <td style="width: 10%; ">Fecha Venc.</td>
                        <td style="width: 10%; ">Saldo Capital</td>
                        <td style="width: 10%; ">Capital Amortizado</td>
                        <td style="width: 10%; ">Interes</td>
                        <td style="width: 10%; ">Com. Desc. Automático</td>
                        <td style="width: 10%; ">Seguro Desgrav</td>
                        <td style="width: 10%; ">Cuota</td>
                    </tr> --}}

                    @foreach ($credito->cronograma['cuotas'] as $item)
                        <tr>
                            <td style="width: 3%; text-align:center;">{{ $item['cuotanro'] }}</td>
                            <td style="width: 10%; text-align:center;">{{ date_format(date_create($item['fecha_ven']),"d/m/Y")  }}</td>
                            <td style="width: 10%; padding-right: 1%; text-align:right;">{{  number_format($item['sal_cap'], 2, '.', ',')  }}</td>
                            <td style="width: 10%; padding-right: 1%; text-align:right;">{{  number_format($item['cap_amor'], 2, '.', ',')  }}</td>
                            <td style="width: 10%; padding-right: 1%; text-align:right;">{{  number_format($item['interes'], 2, '.', ',')  }}</td>
                            <td style="width: 10%; padding-right: 1%; text-align:right;">{{  number_format($item['com_des'], 2, '.', ',')  }}</td>
                            <td style="width: 10%; padding-right: 1%; text-align:right;">{{  number_format($item['seg_des'], 2, '.', ',')  }}</td>
                            <td style="width: 10%; padding-right: 1%; text-align:right;">{{  number_format($item['cuota'], 2, '.', ',')  }}</td>
                        </tr>
                    @endforeach

                </table>
                {{-- <table style="width:100%; margin-top: 6px; font-family: courier new;" border="1">
                    @foreach ($orden->detallespropuestaganadora as $prod)
                        <tr style="font-size: 10px;">
                            @if ($prod['prod'] != null)
                                <td style="width:106px; text-align: center; border: 1px solid white;"> {{$prod['prod']->codigo}} </td>
                            @else
                                <td style="width:106px;  text-align: center; border: 1px solid white; color: white;"></td>
                            @endif

                            @if ($prod['cant_producto'] > 0)
                                
                                <td style="width:70px; text-align: center; border: 1px solid white;"> {{$prod['cant_producto']}} </td>
                            @else
                                <td style="width:70px; text-align: center; border: 1px solid white;"></td>
                            @endif
                            <td style="width:72px; text-align: center; border: 1px solid white;"> {{$prod['unidad']}} </td>
                            <td style="width:280px; text-align: left; border: 1px solid white; padding-left:20px;"> {{$prod['nombre_producto']}} </td>
                            @if ($prod['precio_unitario_producto'] > 0)
                                <td style="width:104px; text-align: right; border: 1px solid white; padding-right:10px"> {{  $prod['precio_unitario_producto']}} </td>
                            @else
                                <td style="width:104px; text-align: right; border: 1px solid white; padding-right:10px"> </td>
                            @endif

                            @if ($prod['importe'] > 0)
                                <td style="width:103px; text-align: right; border: 1px solid white; padding-right:10px"> {{ $prod['importe']}} </td>
                            @else
                                <td style="width:103px; text-align: right; border: 1px solid white; padding-right:10px">  </td>
                            @endif
                        </tr>
                    @endforeach
                </table> --}}
            </div>
          </td>
        </tr>
      </tbody>

      <tfoot>
        <tr>
          <td>
            <div class="page-footer-space"></div>
          </td>
        </tr>
      </tfoot>
    </table>
@endsection