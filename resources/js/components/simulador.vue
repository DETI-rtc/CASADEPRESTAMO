<style type="text/css" scoped>
    .VueTables__table tbody {
        font-size: 12px;
    }
    .VueTables__search-field {
        display: inline-flex;
    }
    .VueTables__limit-field{
    display: inline-flex;
    }
    .error{
    width: 100%;
        margin-top: .25rem;
        font-size: 80%;
        color: #dc3545;
    }
    .table-hover tbody tr:hover {

    background-color: rgb(212 222 63 / 52%);
    }
    .marcard{
        margin: 15px;
    }
    .noinfe {
        margin-bottom: 0;
        /*width: 100%;*/
    }
    table th {
        font-size: 5px;
    }
    /* th:nth-child(n+2),
    td:nth-child(n+2) {
    text-align: right;
    } */
</style>
<template>
  <div class="" >
       <div class="card card-info card-outline" >
            <div class="card-header">
                <h3 class="card-title" style="font-size: 24px !important;"> <i class="fas fa-users mr-2" ></i> SIMULADOR DE CRÉDITOS ACTUALIZADO </h3>
                <!-- <button @click="imprimirCronograma">aceptar</button> -->
            </div>
            <div class="card-body" ref="contento">
                <div class="row">
                        <div class="form-group col-md-2">
                            <label class="" >Sector Empresarial <span style="color: red; font-weight: bold;">*</span></label>
                            <div class="col">
                                <el-select v-model="forminicialdata.id_sector" placeholder="Seleccione Sector" class="w-95">
                                    <el-option
                                    v-for="item in sectores"
                                    :key="item.id"
                                    :label="item.nomdelista"
                                    :value="item.id"
                                    v-if="item.idlista == 1"
                                    >
                                    </el-option>
                                </el-select>

                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="" >Modalidad <span style="color: red; font-weight: bold;">*</span></label>
                            <div class="col-sm-12">
                                <el-select v-model="forminicialdata.id_modalidad" placeholder="Seleccione Modalidad" class="w-95" :value="listarmodalidad" @change="cambiomodalidad">
                                    <el-option
                                    v-for="item in sectores"
                                    :key="item.id"
                                    :label="item.nomdelista"
                                    :value="item.id"
                                    v-if="item.idlista == idlista"
                                    >
                                    </el-option>
                                </el-select>
                            </div>
                        </div>
                        <div class="form-group col-md-1" >
                                        <label>Meses restantes <span style="color: red; font-weight: bold;">*</span></label>
                                        <div class="input-group mb-0">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Nº</div>
                                                </div>
                                                <vue-autonumeric :options="'integer'" v-model="forminicialdata.meses_faltantes" :disabled="sinlimite" class="form-control text-right"  ></vue-autonumeric>
                                        </div>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="" >Ingreso bruto <span style="color: red; font-weight: bold;">*</span></label>
                            <div class="input-group mb-0">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">S/.</div>
                                </div>
                                 <vue-autonumeric :options="''" v-model="forminicialdata.ingreso_bruto"  class="form-control  text-right"  ></vue-autonumeric>
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="" >Descuento por ley <span style="color: red; font-weight: bold;">*</span></label>
                            <div class="input-group mb-0">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">S/.</div>
                                </div>
                                <vue-autonumeric :options="''" v-model="forminicialdata.descuento_ley"  class="form-control  text-right"  ></vue-autonumeric>
                                
                            </div>
                        </div>
                        <div class="form-group col-md-3 text-center mb-0 mt-2 ">
                            <button type="button" class="btn btn-app bg-primary btn-block" @click="evaluar" v-loading.fullscreen.lock="loading"><i class="fas fa-bolt"></i> Evaluar</button>                            
                        </div>
                </div>
                <div class="row">
                   <div class="col-md-12 ">
                        <div class="card card-primary card-outline card-tabs">
                          <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-pills nav-justified" id="custom-tabs-three-tab" role="tablist">
                              <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-three-home-tab" type="button"  data-bs-toggle="tab" data-bs-target="#custom-tabs-three-home"  role="tab" 
                                aria-controls="custom-tabs-three-home" aria-selected="true">Cronograma</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-profile-tab" type="button" data-bs-toggle="tab" data-bs-target="#custom-tabs-three-profile" role="tab" 
                                aria-controls="custom-tabs-three-profile" aria-selected="false">Calculo Monto y plazo</a>
                              </li>
                             
                            </ul>
                          </div>
                          <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                              <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                    <div class="row mt-1" >
                                        
                                        <div class="form-group col-md-2 mb-0">
                                                <label class="">Monto financiado <span style="color: red; font-weight: bold;">*</span></label>
                                                <div class="input-group mb-0">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">S/.</div>
                                                    </div>
                                                    <vue-autonumeric :options="{ maximumValue : valormaximo }" v-model="formCronograma.mon_financiado" class="form-control form-control-sm text-right"  ></vue-autonumeric>                       
                                               
                                                </div>         
                                        </div>                                      
                                        <div class="form-group col-md-1 mb-0">
                                             <label class="">Num. Cuotas <span style="color: red; font-weight: bold;">*</span></label>
                                             <div class="input-group mb-0">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">Nº</div>
                                                    </div>
                                                    <vue-autonumeric :options="'integer'" v-model="formCronograma.num_cuotas" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                             </div>
                                        </div>
                                        <div class="form-group col-md-2 mb-0">
                                             <label class="">TEA<span style="color: red; font-weight: bold;">*</span></label>
                                              <vue-autonumeric v-model="formCronograma.tea" class="form-control form-control-sm text-right"  ></vue-autonumeric>                                        
                                         </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <label class="">SEG. DESGRA.<span style="color: red; font-weight: bold;">*</span></label>
                                            <vue-autonumeric :options="{decimalPlaces:5}"  v-model="formCronograma.desgravament" class="form-control form-control-sm text-right"  ></vue-autonumeric>                                        
                                        </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <label class="">COM. DESC.<span style="color: red; font-weight: bold;">*</span></label>
                                            <vue-autonumeric :options="'integer'"  v-model="formCronograma.com_desc_auto" class="form-control form-control-sm text-right"  ></vue-autonumeric>                                        
                                        </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <label class="">COM. DESEM.<span style="color: red; font-weight: bold;">*</span></label>
                                            <vue-autonumeric :options="'integer'"  v-model="formCronograma.com_desembolso" class="form-control form-control-sm text-right"  ></vue-autonumeric>                                        
                                        </div>
                                         

                                         
                                        
                                        <div class="form-group col-md-2 mb-0">
                                             <label class="">F. Desembolso <span style="color: red; font-weight: bold;">*</span></label>
                                             <input type="date"  class="form-control form-control-sm" placeholder="Fecha" v-model="formCronograma.f_desembolso">                                        
                                         </div>
                                         <div class="form-group col-md-2 mb-0">
                                             <label class="">F. 1ra cuota <span style="color: red; font-weight: bold;">*</span></label>
                                             <input type="date"  class="form-control form-control-sm" placeholder="Fecha" v-model="formCronograma.f_primeracuota">                                        
                                         </div>
                                        <div class="form-group col-md-3 text-center mt-1 mb-0">
                                            <button type="button" class="btn btn-app bg-success btn-block" @click="generaFecha"><i class="fas fa-calendar-day"></i>Generar Cronograma</button>
                                        </div>
                                        <div class="form-group col-md-4 text-center mt-1 mb-0"  v-if="this.fechas.length > 0">
                                            <button type="button" class="btn btn-app bg-warning btn-block" @click="imprimirCronograma"><i class="fas fa-print"></i> Imprimir Cronograma </button>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="cronograma"  id="#imprimircrono"  v-if="this.fechas.length > 0">
                                        <div class="formluario-cronograma row">

                                        <div class="col-md-12 mt-3">
                                            <table class="w-100">

                                                <tr>
                                                    <td class="title">Tasa Mensual Seguro de Desgravamen</td>
                                                    <td class="dos-puntos">:</td>
                                                    <td class="contenido">{{ formCronograma.tasa_men_desgra * 100  }} %</td>

                                                    <td class="title">Comisión Mensual Dscto Automático</td>
                                                    <td class="dos-puntos">:</td>
                                                    <td class="contenido">S/. {{ formCronograma.com_desc_auto | localmoney }}</td>

                                                    <td class="title">Tasa efectiva anual</td>
                                                    <td class="dos-puntos">:</td>
                                                    <td class="contenido">{{ formCronograma.tea | localmoney }} %</td>
                                                </tr>
                                                <tr>
                                                    <td class="title">Total interés</td>
                                                    <td class="dos-puntos">:</td>
                                                    <td class="contenido">S/. {{ formCronograma.t_interes | localmoney }}</td>

                                                    <td class="title">Num Cuotas</td>
                                                    <td class="dos-puntos">:</td>
                                                    <td class="contenido">{{ formCronograma.num_cuotas | localmoney }}</td>

                                                    <td class="title">Periocidad</td>
                                                    <td class="dos-puntos">:</td>
                                                    <td class="contenido">{{ formCronograma.periocidad  | upText}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="title">F. venc. última cuota</td>
                                                    <td class="dos-puntos">:</td>
                                                    <td class="contenido">{{ formCronograma.f_ultima_cuota }}</td>

                                                    <td class="title">Comisión por Desembolso</td>
                                                    <td class="dos-puntos">:</td>
                                                    <td class="contenido">{{ formCronograma.com_desembolso  | localmoney}} %</td>

                                                    <!-- <td class="title">Monto Neto a Desembolsar</td>
                                                    <td class="dos-puntos">:</td>
                                                    <td class="contenido">S/. {{ formCronograma.mon_ne_desem | localmoney}}</td> -->
                                                </tr>
                                            </table>
                                        </div>

                                        </div>

                                        <v-client-table class="mt-2" :data="fechas" :columns="columnacronograma" :options="optionscronograma" v-if="this.fechas.length > 0">
                                            <div slot="fecha_vencimiento" slot-scope="{row}" class="text-center">
                                                <span>{{row.fecha_vencimiento | myDate}}</span>
                                            </div>
                                            <div slot="saldo_capital" slot-scope="{row}" class="text-right">
                                                <span>S/. {{row.saldo_capital | localmoney}}</span>
                                            </div>
                                            <div slot="capital_amortizado" slot-scope="{row}" class="text-right">
                                                <span>S/. {{row.capital_amortizado | localmoney}}</span>
                                            </div>
                                            <div slot="interes" slot-scope="{row}" class="text-right">
                                                <span>S/. {{row.interes | localmoney}}</span>
                                            </div>
                                            <div slot="com_desc_automatico" slot-scope="{row}" class="text-right">
                                                <span>S/. {{row.com_desc_automatico | localmoney}}</span>
                                            </div>
                                            <div slot="seguro_degrav" slot-scope="{row}" class="text-right">
                                                <span>S/. {{row.seguro_degrav | localmoney}}</span>
                                            </div>
                                            <div slot="cuota" slot-scope="{row}" class="text-right">
                                                <span>S/. {{row.cuota | localmoney}}</span>
                                            </div>
                                        </v-client-table>
                                    </div>

                                        
                              </div>
                              <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                                    <div class="row">
                                            <div class="card card-info col-md-3">
                                                <div class="card-header p-1">
                                                    <div class="card-title text-center" style="float: none;">
                                                        <h5 class="mb-0">Datos Basicos</h5>
                                                            
                                                    </div>
                                                </div>
                                                <div class="car-body">
                                                    <div class="marcard">
                                                        <div class="form-group row noinfe ">
                                                                    <label class="col-sm-8 col-form-label" >Antiguedad Laboral <span style="color: red; font-weight: bold;">*</span></label>
                                                                    <div class="col-sm-4">
                                                                        <vue-autonumeric :options="''" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                        
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-8 col-form-label" >Meses que Falatan Contrato<span style="color: red; font-weight: bold;">*</span></label>
                                                                    <div class="col-sm-4">
                                                                        <vue-autonumeric :options="'integer'" v-model="formCredito.meses_fal_cont" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                        
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-8 col-form-label" >Patrimonio <span style="color: red; font-weight: bold;">*</span></label>
                                                                <div class="col-sm-4">
                                                                    <vue-autonumeric :options="''" v-model="formCredito.patrimonio" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                        
                                                                    </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="card card-warning col-md-5">
                                            <div class="card-header p-1">
                                                <div class="card-title text-center" style="float:none;">
                                                    <h5 class="mb-0">Relacion Couta ingreso</h5>
                                                        
                                                </div>
                                               
                                            </div>
                                            <div class="car-body">
                                                <div class="marcard">
                                                        <div class="form-group row noinfe ">
                                                                    <label class="col-sm-9 col-form-label" >Ingreso Bruto<span style="color: red; font-weight: bold;">*</span></label>
                                                                <div class="col-sm-3">
                                                                        <vue-autonumeric :options="''" v-model="formCredito.ingre_bru" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-9 col-form-label" >Descuentos ley <span style="color: red; font-weight: bold;">*</span></label>
                                                                <div class="col-sm-3">

                                                                        <vue-autonumeric :options="''" v-model="formCredito.des_ley" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-9 col-form-label" >Ingresos Netos <span style="color: red; font-weight: bold;">*</span></label>
                                                                <div class="col-sm-3">
                                                                        <vue-autonumeric :options="''" v-model="formCredito.ing_neto" :value="calcIngresoNeto" class="form-control form-control-sm text-right" disabled></vue-autonumeric>
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-9 col-form-label" >RCI <span style="color: red; font-weight: bold;">*</span></label>
                                                                <div class="col-sm-3">
                                                                        <vue-autonumeric :options="{ suffixText: '%' }" v-model="formCredito.idrci" class="form-control form-control-sm text-right"  disabled></vue-autonumeric>
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-6 col-form-label" >Ingreso Neto Disponible <span style="color: red; font-weight: bold;">*</span></label>
                                                                <div class="col-sm-3">
                                                                    <label >Saldos</label>

                                                                    </div>
                                                                <div class="col-sm-3">
                                                                        <vue-autonumeric :options="''" v-model="formCredito.ing_netodiscred" :value="calcing_netodiscred" class="form-control form-control-sm text-right" disabled></vue-autonumeric>
                                                                </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-6 col-form-label" >Deuda Consumo <span style="color: red; font-weight: bold;">*</span></label>
                                                                <div class="col-sm-3">
                                                                        <vue-autonumeric :options="''" v-model="formCredito.sal_deuda_sc" class="form-control form-control-sm  text-right"  ></vue-autonumeric>
                                                                    </div>
                                                                <div class="col-sm-3">

                                                                        <vue-autonumeric :options="''" v-model="formCredito.deuda_sc" :value="deuda_sc"class="form-control form-control-sm form-control-sm text-right" disabled></vue-autonumeric>
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-6 col-form-label" >Cuotas Fijas <span style="color: red; font-weight: bold;">*</span></label>
                                                                <div class="col-sm-3">

                                                                        <vue-autonumeric :options="''" v-model="formCredito.sal_deuda_cc" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                    </div>
                                                                <div class="col-sm-3">

                                                                        <vue-autonumeric :options="''"  class="form-control form-control-sm text-right" v-model="formCredito.deuda_cc"  ></vue-autonumeric>
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-6 col-form-label" >Deuda cuota Hipotecaria <span style="color: red; font-weight: bold;">*</span></label>
                                                                <div class="col-sm-3">

                                                                        <vue-autonumeric :options="''" v-model="formCredito.sal_hipo" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                    </div>
                                                                <div class="col-sm-3">

                                                                        <vue-autonumeric :options="''" v-model="formCredito.deuda_hipo" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                    </div>
                                                        </div>

                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-9 col-form-label" >Cuota Maxima para prestamo <span style="color: red; font-weight: bold;">*</span></label>

                                                                <div class="col-sm-3">

                                                                        <vue-autonumeric :options="''" v-model="formCredito.cuota_max_pres" :value = "calcCuotaMaxima" class="form-control form-control-sm text-right"  disabled></vue-autonumeric>
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-9 col-form-label" >Plazo Maximo por Modalidad <span style="color: red; font-weight: bold;">*</span></label>

                                                                <div class="col-sm-3">

                                                                        <vue-autonumeric :options="''" v-model="formCredito.idplazo" class="form-control form-control-sm text-right"  disabled></vue-autonumeric>
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-9 col-form-label" >Plazo maximo de la Operacion <span style="color: red; font-weight: bold;">*</span></label>

                                                                <div class="col-sm-3">

                                                                        <vue-autonumeric :options="''" :value="calcPlaz_Max_Oper" v-model="formCredito.plazo_mas_ope" class="form-control form-control-sm text-right" disabled></vue-autonumeric>
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-6 col-form-label" >Primer Pago <span style="color: red; font-weight: bold;">*</span></label>
                                                                <div class="col-sm-3">

                                                                    </div>
                                                                <div class="col-sm-3">

                                                                        <vue-autonumeric :options="''" v-model="formCredito.primer_pago" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-6 col-form-label" >Tea/ Tem <span style="color: red; font-weight: bold;">*</span></label>
                                                                <div class="col-sm-3">

                                                                        <vue-autonumeric :options="{ suffixText: '%' }" v-model="formCredito.idtasa_int" class="form-control form-control-sm text-right"></vue-autonumeric>
                                                                    </div>
                                                                <div class="col-sm-3">

                                                                        <vue-autonumeric :options="'percentageEU2dec'" v-model="formCredito.tem_rci" :value="calctem_rci" class="form-control form-control-sm text-right" disabled></vue-autonumeric>
                                                                    </div>
                                                        </div>
                                                        <div class="form-group row noinfe">
                                                                    <label class="col-sm-6 col-form-label" >Monto Maximo a prestar por RCI <span style="color: red; font-weight: bold;">*</span></label>
                                                                <div class="col-sm-3">

                                                                    </div>
                                                                <div class="col-sm-3">
                                                                        <vue-autonumeric :options="''" v-model="formCredito.monto_max_rci" :value="calcmonto_max_rci" class="form-control form-control-sm text-right" disabled></vue-autonumeric>
                                                                    </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-dark col-md-4">
                                            <div class="card-header p-1">

                                                <div class="card-title text-center " style="float:none;">
                                                         <h5 class="mb-0">Relacion Deuda Ingreso</h5>
                                                </div>
                                            </div>
                                            <div class="car-body">
                                                <div class="marcard">
                                                            <div class="form-group row noinfe">
                                                                        <label class="col-sm-9 col-form-label" >Leverage Maximo segun modalidad <span style="color: red; font-weight: bold;">*</span></label>
                                                                    <div class="col-sm-3">

                                                                            <vue-autonumeric :options="''" v-model="formCredito.idrdi" class="form-control form-control-sm text-right" disabled ></vue-autonumeric>
                                                                        </div>
                                                            </div>
                                                            <div class="form-group row noinfe">
                                                                        <label class="col-sm-9 col-form-label" >Maximo Endeudamiento <span style="color: red; font-weight: bold;">*</span></label>
                                                                    <div class="col-sm-3">

                                                                            <vue-autonumeric :options="''" v-model="formCredito.max_ende"  :value="calcmax_ende" class="form-control form-control-sm text-right"  disabled></vue-autonumeric>
                                                                        </div>
                                                            </div>
                                                            <div class="form-group row noinfe">
                                                                        <label class="col-sm-9 col-form-label" >Deuda Consumo SBS<span style="color: red; font-weight: bold;">*</span></label>
                                                                    <div class="col-sm-3">

                                                                            <vue-autonumeric :options="''" v-model="formCredito.deuda_consu" :value="calcdeuda_consu" class="form-control form-control-sm text-right"  disabled></vue-autonumeric>
                                                                        </div>
                                                            </div>
                                                            <div class="form-group row noinfe">
                                                                        <label class="col-sm-9 col-form-label" >Otras deudas <span style="color: red; font-weight: bold;">*</span></label>
                                                                        <div class="col-sm-3">

                                                                            <vue-autonumeric :options="''" v-model="formCredito.otras_deudas" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                        </div>
                                                            </div>
                                                            <div class="form-group row noinfe">
                                                                        <label class="col-sm-9 col-form-label" >Maximo Monto a Prestar por RDI <span style="color: red; font-weight: bold;">*</span></label>
                                                                        <div class="col-sm-3">
                                                                            <vue-autonumeric :options="''" v-model="formCredito.monto_max_rdi" :value="calcmonto_max_rdi" class="form-control form-control-sm text-right" disabled></vue-autonumeric>
                                                                        </div>
                                                            </div>
                                                            <div class="form-group row noinfe">
                                                                        <label class="col-sm-9 col-form-label text-blue" >Monto Maximo a Aprobar <span style="color: red; font-weight: bold;">*</span></label>
                                                                    <div class="col-sm-3">

                                                                            <vue-autonumeric :options="''" v-model="formCredito.monto_max_apro"  :value="calcmonto_max_apro" class="form-control form-control-sm text-right text-bold"  disabled></vue-autonumeric>
                                                                        </div>
                                                            </div>
                                                            <div class="form-group row noinfe">
                                                                        <label class="col-sm-9 col-form-label text-blue" >Plazo Maximo a Aprobar<span style="color: red; font-weight: bold;">*</span></label>
                                                                    <div class="col-sm-3">

                                                                            <vue-autonumeric :options="''" v-model="formCredito.plazo_max_apro" class="form-control form-control-sm text-right text-bold"  disabled></vue-autonumeric>
                                                                        </div>
                                                            </div>
                                                            <div class="form-group row noinfe">
                                                                        <label class="col-sm-9 col-form-label text-blue" >TEM <span style="color: red; font-weight: bold;">*</span></label>
                                                                    <div class="col-sm-3">
                                                                            <vue-autonumeric :options="{ suffixText: '%' }" v-model="formCredito.tem" class="form-control form-control-sm text-right text-bold" disabled></vue-autonumeric>


                                                                        </div>
                                                            </div>
                                                            <div class="form-group row noinfe">
                                                                        <label class="col-sm-9 col-form-label text-blue" >Primer Pago<span style="color: red; font-weight: bold;">*</span></label>
                                                                    <div class="col-sm-3">
                                                                            <input type="text" class="form-control text-right text-bold" v-model="formCredito.primer_pago" disabled>
                                                                        </div>
                                                            </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    
                              </div>
                             
                            </div>
                          </div>
                          <!-- /.card -->
                        </div>
                      </div>
                </div>  




                

            </div>
        </div>


        <!-- MODAL -->

   

  </div>
</template>

<script>
  import VueAutonumeric from 'vue-autonumeric'
//   import VueHtmlToPaper from 'vue-html-to-paper';
  import moment from 'moment';
  var Finance = require('financejs'); // or ES6 import
  const { irr } = require('node-irr');
//   const laimage = require('../public/img/logosbh.png');

    export default {

        data() {
            return {
                valormaximo:'1000000',
                tabName:'',
                tipocredi:'',
                isActive:false,
                iscrono : false,
                editmode: false,
                error:{},
                delista:[],
                creditos : [],
                fechas:[],
                empresa : [],
                sectores:[],
                loading:false,
                loading_modal:false,
                sinlimite:false,
                idlista:'',
                imageLogo:[
                    'https://www.beneficenciadehuancayo.org/file_sbh/logos/logo.png'
                ],
                columna:['dni','nombres','direccion','celular','empresa','estado','actions'],
                options: {
                          headings: {
                                dni: 'DNI',nombre:'nombres',direccion:'Direccion',celular:'Telefono',empresa:'Empresa',estado:'Estado',
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm table-hover',
                          filterAlgorithm: {
                               full_name(row, query) {
                          return (row.paterno + ' ' + row.materno).includes(query.toUpperCase());
                             }
                          },
                          filterable: ['full_name','nombres'],
                          texts: {
                                count: "Mostrando {from} a {to} de {count} registros|{count} registros|Un registro",
                                first: 'Primero',
                                last: 'Ultimo',
                                filter: "Filtrar:",
                                filterPlaceholder: "Buscar",
                                limit: "Registros:",
                                page: "Pagina:",
                                noResults: "No se encontro registros",
                                filterBy: "Filtrar {column}",
                                loading: 'Cargando...',
                                defaultOption: 'Seleccionar {column}',
                                columns: 'Columna',
                                resizableColumns: true,
                                },
                },

//
                columnacronograma:['num_cuota','fecha_vencimiento','saldo_capital','capital_amortizado','interes','com_desc_automatico','seguro_degrav', 'cuota'],
                optionscronograma:{
                    headings: {
                                num_cuota: 'NUM',fecha_vencimiento:'FECHA VENC',saldo_capital:'SALDO CAPITAL',capital_amortizado:'CAPITAL AMORTIZADO',interes:'INTERES',com_desc_automatico:'COM DESC AUTOMÁTICO',
                                seguro_degrav:'SEGURO DESGRAV', cuota:'CUOTA'
                              },
                          perPage:100,
                          perPageValues:[100],
                          skin:'table table-sm table-hover',
                          filterable: false,
                        texts: {
                                count: '',
                                first: 'Primero',
                                last: 'Ultimo',
                                filter: "Filtrar:",
                                filterPlaceholder: "Buscar",
                                limit: "Registros:",
                                page: "Pagina:",
                                noResults: "No se encontro registros",
                                loading: 'Cargando...',
                                defaultOption: 'Seleccionar {column}',
                                columns: 'Columna',
                                resizableColumns: true,
                                },
                },


//
                formCliente : new Form ({
                    celular: '',
                    created_at: '',
                    direccion: '',
                    dni: '',
                    email: '',
                    edad: '',
                    estado: '',
                    fec_nac: '',
                    id: '',
                    idanalista: '',
                    idempresa: '',
                    idestadocivil: '',
                    ingre_bru: '',
                    localidad: '',
                    materno: '',
                    nombres: '',
                    ocupacion: '',
                    paterno: '',
                    referencia: '',
                    sexo: '',
                    tipoentidad: '',
                    tipomodalidad: '',
                    updated_at: '',
                    tentidad:{
                        mondelista:''
                    },
                    tmodalidad:{
                        nomdelista:''
                    },
                    empresa:{
                        razonsocial:''
                    },
                    estadocivil:{
                        nomdelista:''
                    }
                }),
                formCredito: new Form({
                        ingre_bru:0,
                        meses_fal_cont:0,
                        patrimonio:0,
                        des_ley:0,
                        ing_neto:0,
                        idrci:0,
                        ing_netodiscred:0,
                        sal_deuda_sc:0,
                        sal_deuda_cc:0,
                        deuda_sc:0,
                        deuda_cc:0,
                        sal_hipo:0,
                        deuda_hipo:0,
                        cuota_max_pres:0,
                        idplazo:0,
                        plazo_mas_ope:0,
                        primer_pago:0,
                        idtasa_int:0,
                        tem_rci:0,
                        monto_max_rci:0,
                        idrdi:0,
                        max_ende:0,
                        deuda_consu:0,
                        otras_deudas:0,
                        monto_max_rdi:0,
                        monto_max_apro:0,
                        plazo_max_apro:0,
                        tem:0,
                        fec_apro:0,
                        situacion:0,
                        estado:0,
                        tea:0,
                        desgravament:0,
                }),

                forminicialdata: new Form({
                    id_sector:'',
                    id_modalidad:'',
                    ingreso_bruto:'',
                    descuento_ley:'',
                    meses_faltantes:0,
                }),
                // formCronograma: new Form({}),
                logo:'/img/logocasa2.png',
                formCronograma: new Form({
                    iduser :1,
                    estado :'PENDIENTE',
                    tasa_men_desgra :'',
                    tasa_efe_anu :20,
                    t_interes :0,
                    num_cuotas :0,
                    periocidad :'MENSUAL',
                    f_ultima_cuota :'-',
                    com_desc_auto :5,
                    com_desembolso :0,
                    mon_ne_desem :0,
                    f_desembolso :'',
                    mon_financiado:'',
                    f_primeracuota:'',
                    tea:'',
                    desgravament:'',
                }),
            }
        },

        computed: {
            calcIngresoNeto(){
                let result = this.formCredito.ingre_bru - this.formCredito.des_ley;
                this.formCredito.ing_neto = result;
                return result;
            },

            calcing_netodiscred(){
                // if (this.formCredito.ing_neto.length > 0) {
                    let result = this.formCredito.ing_neto * (this.formCredito.idrci/100);
                    this.formCredito.ing_netodiscred = result;
                    return result;
                // } else {
                //     this.formCredito.ing_neto === 0;
                // }
            },

            calcdeuda_sc(){
                let result = this.formCredito.sal_deuda_sc/this.formCredito.meses_fal_cont;
                console.log(result);
                if (this.formCredito.sal_deuda_sc > 0) {
                    this.formCredito.deuda_sc = result;
                }
                return result;
            },

            calcCuotaMaxima(){
                let result =  this.formCredito.ing_netodiscred - (this.formCredito.deuda_sc + this.formCredito.deuda_cc + this.formCredito.deuda_hipo);
                this.formCredito.cuota_max_pres = result;
                return result;
            },

            calcPlaz_Max_Oper(){
                let result = this.formCredito.meses_fal_cont;
                console.log(result);
                if (result == 0) {
                    this.formCredito.plazo_mas_ope = 3;
                    this.formCredito.plazo_max_apro =3;
                    result=3;
                }else{
                    this.formCredito.plazo_mas_ope = Math.min(this.formCredito.meses_fal_cont,this.formCredito.idplazo);
                    this.formCredito.plazo_max_apro = Math.min(this.formCredito.meses_fal_cont,this.formCredito.idplazo);
                    result=Math.min(this.formCredito.meses_fal_cont,this.formCredito.idplazo);
                }

                //this.formCredito.plazo_mas_ope = this.formCredito.meses_fal_cont;
                return result;
            },


            calcmonto_max_rci(){
                let result = ((1-(Math.pow((1+this.formCredito.tem_rci),-this.formCredito.plazo_mas_ope)))*this.formCredito.cuota_max_pres)/this.formCredito.tem_rci;
                this.formCredito.monto_max_rci = result;
                return result;
            },

            calctem_rci(){
                let result = Math.pow((1+(this.formCredito.idtasa_int/100)), (1/12))-1;
                this.formCredito.tem_rci = result;
                return result;
            },

            calcmax_ende(){
                let result = this.formCredito.ing_neto*this.formCredito.idrdi;
                this.formCredito.max_ende = result;
                return result;
            },
            deuda_sc(){
                let result = this.formCredito.sal_deuda_sc/24;
                this.formCredito.deuda_sc =  result;
                return result;
            },
            // deuda_cc(){
            //     let result = this.formCredito.sal_deuda_cc/24;
            //     this.formCredito.deuda_cc =  result;
            //     return result;
            // },
            calcdeuda_consu(){
            //     if (!this.formCredito.plazo_max_apro) {
            //         this.formCredito.plazo_max_apro = 0;
            //         console.log('esta entrando');
            //     }
            //     console.log(this.formCredito.sal_deuda_sc, this.formCredito.plazo_mas_apro);
                 let result = parseFloat(this.formCredito.sal_deuda_sc) + parseFloat(this.formCredito.sal_deuda_cc);
                 this.formCredito.deuda_consu = result;
                 return result;
             },

            calcmonto_max_rdi(){
                let result =  parseFloat(this.formCredito.max_ende)- (parseFloat(this.formCredito.deuda_consu)+ parseFloat(this.formCredito.otras_deudas));
                this.formCredito.monto_max_rdi = result;
                return result;
            },

            calcmonto_max_apro(){
                let result = Math.min(this.formCredito.monto_max_rdi,this.formCredito.monto_max_rci);
                this.formCredito.monto_max_apro = result;
                return result;
            },

            listarmodalidad(){
                this.forminicialdata.id_modalidad = '';
                this.idlista = 0;
                if (this.forminicialdata.id_sector == 1) {
                    // PUBLICO
                    this.idlista = 8;
                } else if (this.forminicialdata.id_sector == 2) {
                    // PRIVADO
                    this.idlista = 7;
                }

                return this.idlista;
            },

            fechacuotauno(){
                this.forminicialdata.f_primeracuota = '2021/05/05';
                console.log(this.forminicialdata.f_primeracuota);
                return 1;
            }

        },
        methods: {

            evaluar(){
                if (this.forminicialdata.id_sector =='' || this.forminicialdata.id_modalidad =='' || this.forminicialdata.ingreso_bruto =='') {
                    this.$notify.error({
                                title: 'Error',
                                message: 'Debe Ingresar Valores Para la Simulacion'
                            });
                }else{
                    this.loading = true;
                    let infoevaluacion={
                        idsector:this.forminicialdata.id_sector,
                        idmodalidad:this.forminicialdata.id_modalidad,
                        meses_faltantes:this.forminicialdata.meses_faltantes,
                        descuento_ley:this.forminicialdata.descuento_ley,
                        ingre_bru : this.forminicialdata.ingreso_bruto
                    }
                    axios.post('api/calcularcredito', infoevaluacion)
                    .then(
                        data=>{
                            this.formCredito.fill(data.data.credi);
                            this.formCronograma.mon_financiado = this.formCredito.monto_max_apro;
                            this.formCronograma.num_cuotas = this.formCredito.plazo_max_apro;
                            this.formCronograma.f_desembolso = moment().add(5, 'days').format('YYYY-MM-DD');
                            this.formCronograma.tea = this.formCredito.tea;
                            console.log(data.data.desgravament);
                            this.formCronograma.desgravament = this.formCredito.desgravament;
                            this.generaFecha();
                            $('#custom-tabs-three-tab li:first-child a').tab('show');
                            //this.nuevocre();
                            this.loading = false;
                            }
                    ).catch(
                        err=>{
                            console.log(err);
                            this.loading = false;
                            this.$notify.error({
                                title: 'Error',
                                message: 'Verifica los datos indroducidos.'
                            });
                            }
                    )
                }
                
            },
            actuaCre(){

            },
            imprimirRonald(){
                // axios.post('api/voucherronald').then(
                //     data=>{
                //         console.log(data);
                //     }
                // ).catch(ert => console.log(ert))

                axios.get('api/calctcea').then(
                    data=>{
                        console.log(data);
                    }
                ).catch(ert => console.log(ert))

            },

            generaFecha(){
                this.loading_modal = true;

                //this.formCronograma.f_primeracuota = this.obtenercuotainicial(this.formCronograma.f_desembolso);
               // console.log(this.formCronograma.f_primeracuota);

                if (this.formCronograma.mon_financiado > this.formCredito.monto_max_apro) {
                    //console.log('no puede ser mayor');
                    this.$notify.error({
                        title: 'Error',
                        message: 'El monto financiado no puede ser mayor al monto máximo a aprobar.'
                    });
                    this.loading_modal = false;
                }else {
                    let data = {
                        rows:parseInt(this.formCronograma.num_cuotas),
                        fecha:this.formCronograma.f_desembolso,
                        fecha_cuota:this.formCronograma.f_primeracuota,
                        montofinanciado: parseFloat(this.formCronograma.mon_financiado),
                        tea: parseFloat(this.formCronograma.tea),
                        desgravament: parseFloat(this.formCronograma.desgravament),
                        comision_descuento: parseFloat(this.formCronograma.com_desc_auto),
                    }
                    axios.post('api/calctcea', data)
                    .then(
                        data=> {
                            // console.log(data);
                            this.fechas = data.data.cuotas.filter(x => x.num_cuota != 0);
                            this.formCronograma.t_interes = data.data.suma_intereses;
                            this.formCronograma.f_ultima_cuota = data.data.ultima_fecha;
                            this.formCronograma.tasa_men_desgra = data.data.seguro;
                            
                            this.loading_modal = false;
                            this.$notify({
                                title: 'Satisfactorio',
                                message: 'Cronograma creado con éxito.',
                                type: 'success'
                            });
                        }
                    ).catch(
                        err=> {
                            console.log(err);
                            this.loading_modal = false;
                            this.$notify.error({
                                title: 'Error',
                                message: 'Verifica los datos indroducidos.'
                            });
                            }
                    )
                }


            },
            cambiomodalidad(value){
                
                //console.log(this.$emit("input", val));
               var inge = this.sectores.find( nombre => nombre.id === value );
               if (inge.nomdelista == "PLANILLA NOMBRADO"){
                       //forminicialdata.meses_faltantes
                        this.forminicialdata.meses_faltantes == 0;
                        this.sinlimite=true;
               }else{
                        this.forminicialdata.meses_faltantes == 0;
                    this.sinlimite=false;
               }
               console.log(inge);
               //this.form.id = inge.id;
               //this.form.nomlista = inge.nomlista;
                

            },

            nuevocre(){
                $('#nuevacre').modal('show');
            },
            deleteCre(){

            },
            activaTra(){

            },
            editCre(){

            },
            loadUsers(){
                axios.get("api/delista").then(({ data }) => {
                    (this.sectores = data);
                    }).catch(error => { this.error = error.response });
            },
            creaCre(){
                    $('#nuevacre').modal('hide');
            },

            handleTabChange(tabIndex, newTab, oldTab){
                //your code here
                // console.log(newTab.title);
                // console.log(oldTab.title);
            },

            changeTab(){
            },

            async imprimirCronograma(){
                    // var ficha  = document.getElementById("#imprimircrono");
                    // var ventimp = window.open(' ', 'popimpr');
                    // ventimp.document.write( ficha.innerHTML );
                    // ventimp.document.close();
                    // ventimp.print( );
                    // ventimp.close();

                    // this.$htmlToPaper('#imprimircrono');

                    let docDefinition = {
                        pageSize: 'A4',
                        info: {
                            title: 'Cronograma de Pagos',
                            author: 'Casa de Prestamos Huancayo',
                            },
                            styles: {
                                headertable:{
                                    fontSize:9,
                                    bold: true,
                                    alignment: 'center',
                                    background: '#F7F7F7',
                                },
                                dinero: {
                                    fontSize:8,
                                    bold: false,
                                    alignment: 'right',
                                },
                                fecha: {
                                    fontSize: 8,
                                    bold: false,
                                    alignment: 'center',
                                },
                                num:{
                                    fontSize: 8,
                                    bold: false,
                                    alignment: 'center',
                                },
                                cronoheadertitle : {
                                    fontSize: 8,
                                    bold: false,
                                    alignment: 'right',
                                    margin: [ 0,1,0,1 ],
                                },
                                cronoheadertext:{
                                    fontSize: 8,
                                    bold: false,
                                    alignment: 'left',
                                    margin: [ 0,1,0,1 ],
                                },
                                dospuntos:{
                                    fontSize: 8,
                                    bold: false,
                                    alignment: 'center',
                                    margin: [ 0,1,0,1 ],
                                }
                            },

                        content: [
                            // Previous configuration

                            // {
                            //      image: await this.getBase64ImageFromURL("http://planti81.test:82/img/logocasa2.png"),
                            //      width: 100
                            // },
                            {
                            text: 'Reporte de Cronograma  de Cuotas',
                            fontSize: 13,
                            alignment: 'center',
                            color: '#424242',
                            bold: true,
                            margin: [ 0,-30, 0, 10]
                            },
                            {
                            text: 'Fecha : ' + moment(new Date(), 'DD-MM-YYYY').format('DD/MM/YYYY'),
                            fontSize: 9,
                            alignment: 'right',
                            bold: false,
                            },

                            {
                                margin: [ 0,10,0,10 ],
                                layout: 'noBorders',
                                table: {
                                     body: [
                                        [
                                            {
                                                text:'Credito N° :', style:'cronoheadertext',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text:'C00001', style:'cronoheadertext',
                                            },
                                        ],
                                        [
                                            {
                                                text:'Cliente :', style:'cronoheadertext',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text:'Nombre del Cliente', style:'cronoheadertext',
                                            },
                                        ],
                                     ]
                                }
                            },

                            {
                                margin: [ 0,10,0,10 ],
                                layout: 'noBorders',
                                table:{
                                    widths: [90,5,80,100,5,30,90,5,40 ],
                                    body: [
                                        [
                                            {
                                                text:'Tasa Mensual Seguro de Desgravamen', style:'cronoheadertext',

                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text: `${this.formCronograma.desgravament * 100} %`, style:'cronoheadertext',
                                            },
                                            // columna
                                            {
                                                text:'Comisión Mensual Dscto Automático', style:'cronoheadertitle',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text:`${parseFloat(this.formCronograma.com_desc_auto).toFixed(2)}`, style:'cronoheadertext',
                                            },

                                            // columna

                                            {
                                                text:'Tasa efectiva anual', style:'cronoheadertitle',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text: `${parseFloat(this.formCronograma.tea).toFixed(2)} %`, style:'cronoheadertext',
                                            }
                                        ],
                                        [
                                            {
                                                text:'Total interés', style:'cronoheadertext',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text:`S/. ${this.formCronograma.t_interes}`, style:'cronoheadertext',
                                            },
                                            // columna
                                            {
                                                text:'	Num Cuotas', style:'cronoheadertitle',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text: `${this.formCronograma.num_cuotas}`, style:'cronoheadertext',
                                            },

                                            // columna

                                            {
                                                text:'	Periocidad', style:'cronoheadertitle',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text:'MENSUAL', style:'cronoheadertext',
                                            }
                                        ],
                                        [
                                            {
                                                text:'F. venc. última cuota', style:'cronoheadertext',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                
                                                text: `${this.formCronograma.f_ultima_cuota}`, style:'cronoheadertext',
                                            },
                                            // columna
                                            {
                                                text:'Comisión por Desembolso', style:'cronoheadertitle',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text:`${parseFloat(this.formCronograma.com_desembolso).toFixed(2)} %`, style:'cronoheadertext',
                                            },

                                            // columna

                                            {
                                                text:'', style:'cronoheadertitle',
                                            },
                                            {
                                                text:'', style:'dospuntos',
                                            },
                                            {
                                                text:'', style:'cronoheadertext',
                                            }
                                        ]
                                    ]
                                }
                            },


                            {
                                layout: 'noBorders',
                                margin: [ 5,20,5,5 ],
                                    table: {
                                        // headers are automatically repeated if the table spans over multiple pages
                                        // you can declare how many rows should be treated as headers
                                        headerRows: 1,
                                        widths: [ 20,60,80,80,50,50,50,60 ],

                                        body: [
                                        [
                                            {
                                                text:'N°', style:'headertable',
                                            } ,
                                            {
                                                text:'Fecha Venc.', style:'headertable',
                                            } ,
                                            {
                                                text:'Saldo Capital', style:'headertable',
                                            } ,
                                            {
                                                text:'Capital Amortizado', style:'headertable',
                                            } ,
                                            {
                                                text:'Interes', style:'headertable',
                                            } ,
                                            {
                                                text:'Com. Desc. Automático', style:'headertable',
                                            } ,
                                            {
                                                text:'Seguro Desgrav', style:'headertable',
                                            } ,
                                            {
                                                text:'Cuota', style:'headertable',
                                            } ,
                                        ],
                                        ...this.fechas.map(p=>(
                                            [
                                                {
                                                    text: p.num_cuota, style:'num'
                                                },
                                                {
                                                    text: moment(p.fecha_vencimiento, 'YYYY-MM-DD').format('DD-MM-YYYY'), style:'fecha'
                                                },
                                                {
                                                    text: 'S/. '+ Number(p.saldo_capital).toFixed(2), style:'dinero'
                                                },
                                                {
                                                    text: 'S/. '+ Number(p.capital_amortizado).toFixed(2), style:'dinero'
                                                },
                                                {
                                                    text: 'S/. '+ Number(p.interes).toFixed(2), style:'dinero'
                                                },
                                                {
                                                    text: 'S/. '+ Number(p.com_desc_automatico).toFixed(2), style:'dinero'
                                                },
                                                {
                                                    text: 'S/. '+ Number(p.seguro_degrav).toFixed(2), style:'dinero'
                                                },
                                                {
                                                    text: 'S/. '+ Number(p.cuota).toFixed(2), style:'dinero'
                                                }
                                            ]
                                        ))
                                        ]
                                    }
                            }



                        ],
                    }
                    pdfMake.createPdf(docDefinition).print();

                    // window.print();
                    // elem.style.display = 'block';
            },
            getBase64ImageFromURL(url) {
                return new Promise((resolve, reject) => {
                  var img = new Image();
                  img.setAttribute("crossOrigin", "anonymous");
                  img.onload = () => {
                    var canvas = document.createElement("canvas");
                    canvas.width = img.width;
                    canvas.height = img.height;
                    var ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0);
                    var dataURL = canvas.toDataURL("image/png");
                    resolve(dataURL);
                  };
                  img.onerror = error => {
                    reject(error);
                  };
                  img.src = url;
                });
            },

            

            obtenercuotainicial(fechadesembolsoform){
                let fechacuota = '';
                let fechadesembolso = new Date(fechadesembolsoform);
                let dia = fechadesembolso.getDate();
                if (dia > 10) {
                    fechacuota = fechadesembolso.setMonth(fechadesembolso.getMonth() + 2);
                    fechacuota = fechadesembolso.setDate(10);
                    fechacuota = moment(fechadesembolso, 'DD-MM-YYYY').format('DD-MM-YYYY');
                    // console.log('2 meses',fechacuota);
                    return fechacuota;
                } else {
                    fechacuota = fechadesembolso.setMonth(fechadesembolso.getMonth() + 1);
                    fechacuota = fechadesembolso.setDate(10);
                    fechacuota = moment(fechadesembolso, 'DD-MM-YYYY').format('DD-MM-YYYY');
                    // console.log('1 mes',fechacuota);
                    return fechacuota;
                }
            },
            convertirbase64(){
                var canvas = document.createElement('CANVAS');
                img = document.createElement('img'),
                img.src = '/assets/img/logosbh.png';
                img.onload = function()
                {
                    canvas.height = img.height;
                    canvas.width = img.width;
                    var dataURL = canvas.toDataURL('image/png');
                    console.log(dataURL);
                    canvas = null;
                };
            },
        },


        created() {
           this.loadUsers();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
           });
        //    this.convertirbase64();
        //    setInterval(() => this.loadUsers(), 3000);
        },
    }
</script>

