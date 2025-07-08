<style type="text/css" >
    /* .VueTables__table tbody {
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
    /* }
    .tcrono thead tr th{
        text-align: center;
    }
    table th {
        
        text-align: center;
        vertical-align: middle;
    }
    table tbody tr td {
        
        text-align: center;
        vertical-align: middle !important;
    }
    table thead tr th {
        
        text-align: center;
        vertical-align: middle;
    }

    .dropdown-item {
        cursor: pointer !important;
    } */ 
    /* th:nth-child(n+2),
    td:nth-child(n+2) {
    text-align: right;
    } */
</style>
<template>
  <div class="" >
       <div class="card card-primary card-outline" >
            <div class="card-header">
                <h3 class="card-title" style="font-size: 24px !important;"> <i class="fas fa-users mr-2" ></i> RELACION DE CREDITOS </h3>
                    <div class="card-tools">

                        <select class="form-control" v-model="anio" @change="selperiodo($event)" >SELCCIONE AÑO
                            <option v-for="peri in periodos" :key="peri.año" >
                                {{ peri.año }}
                            </option>
                        </select>
                         <!-- <button @click="exportExcel('testTable', 'W3C Example Table')" class="btn btn-primary pull-right mr-2">
                            Exportar a Excel
                        </button>
                        <button @click="exportPDF()" class="btn btn-danger">
                            PDF
                        </button> -->
                        <button class="btn btn-outline-primary mx-1 " @click="nuevocre" v-if="roles.includes('Agente') || roles.includes('Asesor')"> Registrar Crédito <i class="fas fa-user-plus fa-fw"></i></button>
                        <!-- <button  @click="imprimirRonald">Impirmir voucher ronald</button> -->
                    </div>
            </div>
            <div class="card-body" ref="contento" >
                        <v-client-table :data="creditos" :columns="columna1" :options="options1"  id="#ttrabajador">
                                    <div slot="dni" slot-scope="{row}">
                                      {{row.persona.dni   }}
                                     </div>
                                    <div slot="nombres" slot-scope="{row}" >
                                      {{row.persona.paterno+' '+row.persona.materno +' '+row.persona.nombres }}
                                    </div> 
                                    <div slot="empresa" slot-scope="{row}">
                                      {{row.persona.empresa.razonsocial   }}
                                     </div>
                                    <div slot="monto_credito" slot-scope="{row}">
                                      {{row.monto_credito | currency  }}
                                     </div>
                                    <div slot="situacion_apro" slot-scope="{row}" >
                                        <div v-if = "row.situacion_apro == 'P'">
                                            <span class="badge badge-pill badge-warning">Por Aprobar</span>
                                        </div>
                                        <div v-else>
                                            <span  class="badge badge-pill badge-success">Aprobado</span>
                                        </div>
                                    </div>
                                    <div slot="estado_cred" slot-scope="{row}" >
                                      <div v-if = "row.estado_cred == 'A'">
                                          <span class="badge badge-pill badge-success">En Proceso</span>
                                      </div>
                                      <div v-else>
                                          <span  class="badge badge-pill badge-danger">Cancelado</span>
                                      </div>
                                    </div>
                                    <div slot="estado" slot-scope="{row}" >
                                      <div v-if = "row.estado == 1">
                                          <span class="badge badge-pill badge-success">Activo</span>
                                      </div>
                                      <div v-else>
                                          <span  class="badge badge-pill badge-danger">Anulado</span>
                                      </div>
                                    </div>

                                   <div slot="actions" slot-scope="{ row }">
                                      <div class="btn-group">
                                        
                                        <button type="button" class="btn btn-warning btn-sm" v-if="row.responsable == 'N' && row.situacion_apro == 'P'"  @click="editCre(row)">
                                            <i class="fa fa-edit"></i></button>

                                        <button type="button" class="btn btn-success btn-sm" v-if="row.situacion_apro == 'A'"  @click="printCronograma(row.id)"><i class="fas fa-eye"></i></button>
                                        
                                        <template >
                                            <button type="button" v-if = "row.situacion_apro == 'P'" class="btn btn-warning btn-sm"  @click="AprobarCredito(row)" ><i class="fa fa-edit"></i></button>
                                        </template> 
                                        <!-- <template v-if="row.responsable == 'S' && row.monto_credito < 10001">
                                            <button type="button" v-if = "row.situacion_apro == 'P'" class="btn btn-warning btn-sm"  @click="AprobarCredito(row)" ><i class="fa fa-edit"></i></button>
                                        </template>  -->
                                        <button type="button" v-if = "row.situacion_apro == 'P'" class="btn btn-success btn-sm"  @click="viewAntes(row)"><i class="fas fa-eye"></i></button>
                                        <!-- <button type="button" class="btn btn-danger btn-sm" v-if="row.responsable == 'N'"  @click="deleteCre(row.id)" ><i class="fas fa-trash-alt"></i></button> -->
                                        <button type="button" class="btn btn-danger btn-sm"  @click="deleteCre(row.id)" ><i class="fas fa-trash-alt"></i></button>

                                        <el-dropdown size="small">
                                            <el-button type="info" class="form-control" style="border-radius:0% !important;">
                                            <i class="fas fa-print"></i> Imprimir <i class="el-icon-arrow-down el-icon--right"></i>
                                            </el-button>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item @click.native="printCronograma(row.id)">Cronograma</el-dropdown-item>
                                                <el-dropdown-item @click.native="printFormatos( 'solicitud', row.id )">Solicituds Credito</el-dropdown-item>
                                                <el-dropdown-item @click.native="printFormatos( 'CartaAutorizacion', row.id )">Carta Autorizacion</el-dropdown-item>
                                                <el-dropdown-item @click.native="printFormatos( 'ContratoMutuoDinerato', row.id )">Contrato Mutuo Dinerato</el-dropdown-item>
                                                <el-dropdown-item @click.native="printFormatos( 'DeclaracionJurada', row.id )">Declaracion Jurada</el-dropdown-item>
                                                <el-dropdown-item @click.native="printFormatos( 'HojaInstrucciones', row.id )">Hoja de Instrucciones</el-dropdown-item>
                                                <el-dropdown-item @click.native="printFormatos( 'HojaInstruccionesSinCompra', row.id )">Hoja de Instrucciones sin Compra</el-dropdown-item>
                                                <el-dropdown-item @click.native="printFormatos( 'HojaResumen', row.id )">Hoja de Resumen</el-dropdown-item>
                                                <el-dropdown-item @click.native="printFormatos( 'Pagare', row.id )">Pagaré</el-dropdown-item>
                                                <el-dropdown-item @click.native="printFormatos( 'checklist', row.id )">Check List</el-dropdown-item>
                                                <el-dropdown-item @click.native="printFormatos( 'informe', row.id )">Informe Desembolso</el-dropdown-item>
                                                <el-dropdown-item @click.native="printFormatos( 'calculoprestamo', row.id )">Calculo de Credito</el-dropdown-item>
                                            </el-dropdown-menu>
                                        </el-dropdown>
                                      </div>               
                                    
                                    
                                    
                                    </div>
                      </v-client-table>
                </div>
        </div>
     <div class="modal bd-example-modal-xl " id="nuevacre" tabindex="-1" role="dialog" >
            <div class="modal-dialog modal-xl" role="document" style="max-width:90% !important;" >
                <div class="modal-content">
                     
                    <div class="card-header">
                            <h5 class="card-title" v-show="!editmode" id="addNewLabel">Nuevo Credito</h5>
                            <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar Credito</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <form @submit.prevent="editmode ? actuaCre() : creaCre()" id="myForm">
                    <div class="modal-body">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-pills nav-fill" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" type="button"  data-bs-toggle="tab" data-bs-target="#custom-tabs-four-home" role="tab" 
                                        aria-controls="custom-tabs-four-home" aria-selected="false">CRONOGRAMA</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" type="button"  data-bs-toggle="tab" data-bs-target="#custom-tabs-four-profile" role="tab" 
                                        aria-controls="custom-tabs-four-profile" aria-selected="false">CALCULO DEL  MONTO, PLAZO Y CUOTA A PRESTAR</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body pt-0">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                        <div class="form-row mt-2">
                                              <div class="form-group col-2">
                                                  <label  >DNI <span style="color: red; font-weight: bold;">*</span></label>
                                                  <div class="input-group ">
                                                      <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-id-card"></i>
                                                        </div>
                                                      </div>
                                                      <input v-model="formCliente.dni" type="text" class="form-control"  :class="{ 'is-invalid': error.dni  }"  maxlength="8" >
                                                      <div class="input-group-append">
                                                          <button class="btn btn-outline-success" v-on:click.prevent="buscarcli" ><i class="fas fa-search"></i></button>
                                                      </div>
                                                   </div>
                                                    <span v-if="error.dni" class="error" >{{error.dni[0]}}</span>
                                              </div>
                                              <div class="col-12 col-sm-10">
                                                    <div class="info-box bg-light">
                                                        <div class="info-box-content">
                                                            <span class="info-box-number   text-dark mb-0">Apellidos y Nombres</span>
                                                            <span class="info-box-text  text-muted"> {{ formCliente.nombres }} {{formCliente.paterno}} {{formCliente.materno}}</span>
                                                            <span class="info-box-number  text-dark mb-0">Direccion</span>
                                                            <span class="info-box-text  text-muted">{{formCliente.direccion}}</span>
                                                        </div>

                                                        <div class="info-box-content">
                                                            <span class="info-box-number  text-dark mb-0">Estado Civil</span>
                                                            <span class="info-box-text  text-muted text-center">{{formCliente.estadocivil.nomdelista}}</span>
                                                            <span class="info-box-number  text-dark mb-0">Edad Cliente</span>
                                                            <span class="info-box-text  text-muted text-center">{{ formCliente.edad }}</span>
                                                        </div>
                                                        <div class="info-box-content">
                                                            <span class="info-box-number  text-dark mb-0">Ocupacion</span>
                                                            <span class="info-box-text  text-muted">{{formCliente.ocupacion}}</span>
                                                            <span class="info-box-number  text-dark mb-0">Empresa</span>
                                                            <span class="info-box-text  text-muted">{{formCliente.empresa.razonsocial}}</span>
                                                        </div>
                                                        <div class="info-box-content">
                                                            <span class="info-box-number  text-dark mb-0">Tipo Entidad</span>
                                                            <span class="info-box-text  text-muted text-center">{{formCliente.tentidad.nomdelista}}</span>
                                                            <span class="info-box-number  text-dark mb-0">Tipo Modalidad</span>
                                                            <span class="info-box-text  text-muted text-center">{{formCliente.tmodalidad.nomdelista}}</span>
                                                        </div>
                                                        <div class="info-box-content">
                                                            <span class="info-box-number  text-dark text-center mb-0">Ingreso Bruto</span>
                                                            <span class="info-box-text  text-primary text-center text-lg ">S/. {{formCredito.ingre_bru | decimal}}</span>
                                                        </div>
                                                    </div>
                                              </div>
                                        </div>
                                        <div class="row mt-1" >
                                                <div class="form-group col-md-2 mb-0">
                                                        <label class="">Monto financiado <span style="color: red; font-weight: bold;">*</span></label>
                                                        <div class="input-group mb-0">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text form-control-sm">S/.</div>
                                                            </div>
                                                            <vue-autonumeric :options="{ maximumValue : valormaximo }" v-model="formCronograma.mon_financiado" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                        </div>         
                                                </div>                                      
                                                <div class="form-group col-md-1 mb-0">
                                                     <label class="">Num. Cuotas <span style="color: red; font-weight: bold;">*</span></label>
                                                     <div class="input-group mb-0">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text form-control-sm">Nº</div>
                                                            </div>
                                                            <vue-autonumeric :options="'integer'" v-model="formCronograma.num_cuotas" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                     </div>
                                                </div>
                                                
                                                <div class="form-group col-md-2 mb-0">
                                                    <label class="">TEA<span style="color: red; font-weight: bold;">*</span></label>
                                                    <vue-autonumeric :options="{decimalPlaces:2}" v-model="formCronograma.tea" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                </div>

                                                <div class="form-group col-md-2 mb-0">
                                                    <label class="">SEG. DESGRA.<span style="color: red; font-weight: bold;">*</span></label>
                                                    <vue-autonumeric :options="{decimalPlaces:3}"  v-model="formCronograma.desgravament" class="form-control form-control-sm text-right"  ></vue-autonumeric>                                        
                                                </div>

                                                <div class="form-group col-md-2 mb-0">
                                                    <label class="">COM. DESC.<span style="color: red; font-weight: bold;">*</span></label>
                                                    <vue-autonumeric :options="{decimalPlaces:2}"  v-model="formCronograma.com_desc_auto" class="form-control form-control-sm text-right"  ></vue-autonumeric>                                        
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
                                                <div class="form-group col-md-2 text-center mt-1 mb-0">
                                                    <button type="button" class="btn btn-app bg-info btn-block" @click="renganche(true)" v-if="formCredito.reenganche == false">
                                                        <i class="fas fa-calendar-day"></i>Es Reenganche
                                                    </button>
                                                    <button type="button" class="btn btn-app bg-secondary btn-block" @click="renganche(false)" v-else>
                                                        <i class="fas fa-calendar-day"></i>No es Reenganche
                                                    </button>
                                                </div>
                                                <div class="form-group col-md-2 text-center mt-1 mb-0">
                                                    <button type="button" class="btn btn-app bg-success btn-block" @click="generaFecha">
                                                        <i class="fas fa-calendar-day"></i>Calcular Cronograma
                                                    </button>
                                                </div>
                                                <div class="form-group col-md-2 text-center mt-1 mb-0"  v-if="this.fechas.length > 0">
                                                    <button type="button" class="btn btn-app bg-warning btn-block" @click="printCronograma(formCredito.id)">
                                                        <i class="fas fa-print"></i> Imprimir Cronograma 
                                                    </button>
                                                </div>                                        
                                        </div>
                                        
                                        <div class="cronograma"  id="#imprimircrono" v-if="this.fechas.length > 0">
                                               <div class="col-md-12 mt-3">
                                                   <table class="w-100">
                                                        <tr>
                                                            <td class="title">Cliente</td>
                                                            <td class="dos-puntos">:</td>
                                                            <td> {{ formCliente.nombres }} {{formCliente.paterno}} {{formCliente.materno}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="title">Dirección</td>
                                                            <td class="dos-puntos">:</td>
                                                            <td>{{formCliente.direccion | upText}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="title">Credito N°</td>
                                                            <td class="dos-puntos">:</td>
                                                            <td v-if="formCredito.reenganche == true">
                                                                 <v-select label="numero"  :options="numeroReenganche" 
                                                                 :reduce="numeroReenganche => numeroReenganche.numero" v-model="formCredito.numero"  style="width:20% !important;"></v-select>
                                                            </td>
                                                            <td v-else> <input type="text"   class="form-control form-control-sm" style="width:10% !important;" placeholder="000" v-model="formCredito.numero"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="title">Tipo</td>
                                                            <td class="dos-puntos">:</td>
                                                            <td> {{ tipocredi | upText}} </td>
                                                        </tr>
                                                    </table>
                                               </div>
                                               <div class="col-md-12 mt-3">
                                                       <table class="w-100">
                                                                <tr>
                                                                    <td class="title">Tasa Mensual Seguro de Desgravamen</td>
                                                                    <td class="dos-puntos">:</td>
                                                                    <td class="contenido">{{ formCronograma.desgravament * 100  | localmoney }} %</td>

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
                                                                    <td class="title">F. venc. última cuSota</td>
                                                                    <td class="dos-puntos">:</td>
                                                                    <td class="contenido">{{ formCronograma.f_ultima_cuota }}</td>

                                                                    <td class="title">Comisión por Desembolso</td>
                                                                    <td class="dos-puntos">:</td>
                                                                    <td class="contenido">{{ formCronograma.com_desembolso  | localmoney}} %</td>

                                                                    <td class="title">Monto Neto a Desembolsar</td>
                                                                    <td class="dos-puntos">:</td>
                                                                    <td class="contenido">S/. {{ formCronograma.mon_financiado | localmoney}}</td>
                                                                </tr>
                                                       </table>
                                               </div>
                                               <v-client-table class="mt-2" :data="fechas" :columns="columnacronograma" :options="optionscronograma" v-if="this.fechas.length > 0">
                                                                <div slot="fecha_vencimiento" slot-scope="{row}" class="text-center">
                                                                    <span>{{row.fecha_vencimiento | myDate}}</span>
                                                                </div>
                                                                <div slot="saldo_capital" slot-scope="{row}" class="text-center">
                                                                    <span>S/. {{row.saldo_capital | localmoney}}</span>
                                                                </div>
                                                                <div slot="capital_amortizado" slot-scope="{row}" class="text-center">
                                                                    <span>S/. {{row.capital_amortizado | localmoney}}</span>
                                                                </div>
                                                                <div slot="interes" slot-scope="{row}" class="text-center">
                                                                    <span>S/. {{row.interes | localmoney}}</span>
                                                                </div>
                                                                <div slot="com_desc_automatico" slot-scope="{row}" class="text-center">
                                                                    <span>S/. {{row.com_desc_automatico | localmoney}}</span>
                                                                </div>
                                                                <div slot="seguro_degrav" slot-scope="{row}" class="text-center">
                                                                    <span>S/. {{row.seguro_degrav | localmoney}}</span>
                                                                </div>
                                                                <div slot="cuota" slot-scope="{row}" class="text-center">
                                                                    <span>S/. {{row.cuota | localmoney}}</span>
                                                                </div>
                                               </v-client-table>                                    
                                        </div>                                      
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                        
                                        <div class="row"  >
                                                
                                                <div class="card card-info col-md-3">
                                                        <div class="card-header p-1">
                                                            <div class="card-title text-center" style="float: none;">
                                                                <h5 class="mb-0">Datos Basicos</h5>
                                                                    
                                                            </div>
                                                        </div>
                                                        <div class="car-body">
                                                            <div class="m-2">
                                                                <div class="form-group row mb-0 ">
                                                                            <label class="col-sm-8 col-form-label" >Antiguedad Laboral <span style="color: red; font-weight: bold;">*</span></label>
                                                                            <div class="col-sm-4">
                                                                                <vue-autonumeric :options="''" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                                
                                                                            </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-8 col-form-label" >Meses que Falatan Contrato<span style="color: red; font-weight: bold;">*</span></label>
                                                                            <div class="col-sm-4">
                                                                                <vue-autonumeric :options="'integer'" v-model="formCredito.meses_fal_cont" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                                
                                                                            </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
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
                                                        <div class="m-2">
                                                                <div class="form-group row mb-0 ">
                                                                            <label class="col-sm-9 col-form-label" >Ingreso Bruto<span style="color: red; font-weight: bold;">*</span></label>
                                                                        <div class="col-sm-3">
                                                                                <vue-autonumeric :options="''" v-model="formCredito.ingre_bru" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                            </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-9 col-form-label" >Descuentos ley <span style="color: red; font-weight: bold;">*</span></label>
                                                                        <div class="col-sm-3">

                                                                                <vue-autonumeric :options="''" v-model="formCredito.des_ley" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                            </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-9 col-form-label" >Ingresos Netos <span style="color: red; font-weight: bold;">*</span></label>
                                                                        <div class="col-sm-3">
                                                                                <vue-autonumeric :options="''" v-model="formCredito.ing_neto" :value="calcIngresoNeto" class="form-control form-control-sm text-right" disabled></vue-autonumeric>
                                                                            </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-9 col-form-label" >RCI <span style="color: red; font-weight: bold;">*</span></label>
                                                                        <div class="col-sm-3">
                                                                                <vue-autonumeric :options="{ suffixText: '%' }" v-model="formCredito.idrci" class="form-control form-control-sm text-right"></vue-autonumeric>
                                                                            </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-6 col-form-label" >Ingreso Neto Disponible <span style="color: red; font-weight: bold;">*</span></label>
                                                                        <div class="col-sm-3">
                                                                            <label >Saldos</label>

                                                                            </div>
                                                                        <div class="col-sm-3">
                                                                                <vue-autonumeric :options="''" v-model="formCredito.ing_netodiscred" :value="calcing_netodiscred" class="form-control form-control-sm text-right" disabled></vue-autonumeric>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-6 col-form-label" >Deuda Consumo <span style="color: red; font-weight: bold;">*</span></label>
                                                                        <div class="col-sm-3">
                                                                                <!-- <vue-autonumeric :options="''" v-model="formCredito.sal_deuda_sc" class="form-control form-control-sm  text-right"  ></vue-autonumeric> -->
                                                                            </div>
                                                                        <div class="col-sm-3">
                                                                            <vue-autonumeric :options="''" v-model="formCredito.deuda_sc" :value="deuda_sc" class="form-control form-control-sm form-control-sm text-right"></vue-autonumeric>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-6 col-form-label" >Cuotas Fijas <span style="color: red; font-weight: bold;">*</span></label>
                                                                        <div class="col-sm-3">

                                                                                <vue-autonumeric :options="''" v-model="formCredito.sal_deuda_cc" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                            </div>
                                                                        <div class="col-sm-3">

                                                                                <vue-autonumeric :options="''"  class="form-control form-control-sm text-right" v-model="formCredito.deuda_cc"  ></vue-autonumeric>
                                                                            </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-6 col-form-label" >Deuda cuota Hipotecaria <span style="color: red; font-weight: bold;">*</span></label>
                                                                        <div class="col-sm-3">

                                                                                <vue-autonumeric :options="''" v-model="formCredito.sal_hipo" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                            </div>
                                                                        <div class="col-sm-3">

                                                                                <vue-autonumeric :options="''" v-model="formCredito.deuda_hipo" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                            </div>
                                                                </div>

                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-9 col-form-label" >Cuota Maxima para prestamo <span style="color: red; font-weight: bold;">*</span></label>

                                                                        <div class="col-sm-3">

                                                                                <vue-autonumeric :options="''" v-model="formCredito.cuota_max_pres" :value = "calcCuotaMaxima" class="form-control form-control-sm text-right"  disabled></vue-autonumeric>
                                                                            </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-9 col-form-label" >Plazo Maximo por Modalidad <span style="color: red; font-weight: bold;">*</span></label>

                                                                        <div class="col-sm-3">

                                                                                <vue-autonumeric :options="''" v-model="formCredito.idplazo" class="form-control form-control-sm text-right" ></vue-autonumeric>
                                                                            </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-9 col-form-label" >Plazo maximo de la Operacion <span style="color: red; font-weight: bold;">*</span></label>

                                                                        <div class="col-sm-3">

                                                                                <vue-autonumeric :options="''" :value="calcPlaz_Max_Oper" v-model="formCredito.plazo_mas_ope" class="form-control form-control-sm text-right"></vue-autonumeric>
                                                                            </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-6 col-form-label" >Primer Pago <span style="color: red; font-weight: bold;">*</span></label>
                                                                        <div class="col-sm-3">

                                                                            </div>
                                                                        <div class="col-sm-3">

                                                                                <vue-autonumeric :options="''" v-model="formCredito.primer_pago" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                            </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
                                                                            <label class="col-sm-6 col-form-label" >Tea/ Tem <span style="color: red; font-weight: bold;">*</span></label>
                                                                        <div class="col-sm-3">

                                                                                <vue-autonumeric :options="{ suffixText: '%' }" v-model="formCredito.idtasa_int" class="form-control form-control-sm text-right"></vue-autonumeric>
                                                                            </div>
                                                                        <div class="col-sm-3">
                                                                                <vue-autonumeric :options="{decimalPlaces:13}" v-model="formCredito.tem_rci" :value="calctem_rci" class="form-control form-control-sm text-right" style="display:none" disabled></vue-autonumeric>
                                                                                <vue-autonumeric :options="'percentageEU2decPos'"  :value="calctem_rci" class="form-control form-control-sm text-right" disabled></vue-autonumeric>
                                                                                
                                                                        </div>
                                                                </div>
                                                                <div class="form-group row mb-0">
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
                                                        <div class="m-2">
                                                                    <div class="form-group row mb-0">
                                                                                <label class="col-sm-9 col-form-label" >Leverage Maximo segun modalidad <span style="color: red; font-weight: bold;">*</span></label>
                                                                            <div class="col-sm-3">

                                                                                    <vue-autonumeric :options="''" v-model="formCredito.idrdi" class="form-control form-control-sm text-right" ></vue-autonumeric>
                                                                                </div>
                                                                    </div>
                                                                    <div class="form-group row mb-0">
                                                                                <label class="col-sm-9 col-form-label" >Maximo Endeudamiento <span style="color: red; font-weight: bold;">*</span></label>
                                                                            <div class="col-sm-3">

                                                                                    <vue-autonumeric :options="''" v-model="formCredito.max_ende"  :value="calcmax_ende" class="form-control form-control-sm text-right"  disabled></vue-autonumeric>
                                                                                </div>
                                                                    </div>
                                                                    <div class="form-group row mb-0">
                                                                                <label class="col-sm-9 col-form-label" >Deuda Consumo SBS<span style="color: red; font-weight: bold;">*</span></label>
                                                                            <div class="col-sm-3">

                                                                                    <vue-autonumeric :options="''" v-model="formCredito.deuda_consu" :value="calcdeuda_consu" class="form-control form-control-sm text-right"  disabled></vue-autonumeric>
                                                                                </div>
                                                                    </div>
                                                                    <div class="form-group row mb-0">
                                                                                <label class="col-sm-9 col-form-label" >Otras deudas <span style="color: red; font-weight: bold;">*</span></label>
                                                                                <div class="col-sm-3">

                                                                                    <vue-autonumeric :options="''" v-model="formCredito.otras_deudas" class="form-control form-control-sm text-right"  ></vue-autonumeric>
                                                                                </div>
                                                                    </div>
                                                                    <div class="form-group row mb-0">
                                                                                <label class="col-sm-9 col-form-label" >Maximo Monto a Prestar por RDI <span style="color: red; font-weight: bold;">*</span></label>
                                                                                <div class="col-sm-3">
                                                                                    <vue-autonumeric :options="''" v-model="formCredito.monto_max_rdi" :value="calcmonto_max_rdi" class="form-control form-control-sm text-right" disabled></vue-autonumeric>
                                                                                </div>
                                                                    </div>
                                                                    <div class="form-group row mb-0">
                                                                                <label class="col-sm-9 col-form-label text-blue" >Monto Maximo a Aprobar <span style="color: red; font-weight: bold;">*</span></label>
                                                                            <div class="col-sm-3">

                                                                                    <vue-autonumeric :options="''" v-model="formCredito.monto_max_apro"  :value="calcmonto_max_apro" class="form-control form-control-sm text-right text-bold"  disabled></vue-autonumeric>
                                                                                </div>
                                                                    </div>
                                                                    <div class="form-group row mb-0">
                                                                                <label class="col-sm-9 col-form-label text-blue" >Plazo Maximo a Aprobar<span style="color: red; font-weight: bold;">*</span></label>
                                                                            <div class="col-sm-3">

                                                                                    <vue-autonumeric :options="''" v-model="formCredito.plazo_max_apro" class="form-control form-control-sm text-right text-bold"  disabled></vue-autonumeric>
                                                                                </div>
                                                                    </div>
                                                                    <div class="form-group row mb-0">
                                                                                <label class="col-sm-9 col-form-label text-blue" >TEM <span style="color: red; font-weight: bold;">*</span></label>
                                                                            <div class="col-sm-3">
                                                                                    <vue-autonumeric :options="{ suffixText: '%' }" v-model="formCredito.tem" class="form-control form-control-sm text-right text-bold" disabled></vue-autonumeric>


                                                                                </div>
                                                                    </div>
                                                                    <div class="form-group row mb-0">
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
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>

                        <template v-if="!view">
                            <button v-show="editmode" type="submit" class="btn btn-success">Actualizar</button>
                            <button v-show="!editmode" type="submit" class="btn btn-success">Registrar</button>
                        </template>

                    </div>
                  </form>
                </div>
            </div>
      </div>


  </div>
</template>

<script>
    import VueAutonumeric from 'vue-autonumeric'
    import VueHtmlToPaper from 'vue-html-to-paper';
    import moment from 'moment';
    var Finance = require('financejs'); // or ES6 import
    const { irr } = require('node-irr');
    export default {
        props: ['ruta', 'usuario', 'listPermisos','listRoles'],
        data() {
            return {
                view:false,
                periodos:[],
                reenganche:false,
                roles:[],
                valormaximo:'1000000',
                tabName:'',
                tipocredi:'Convenio',
                isActive:false,
                iscrono : false,
                editmode: false,
                anio:moment().format('YYYY'),
                numeroCorre:'',
                numeroReenganche:[],
                error:{},
                delista:[],
                creditos : [],
                fechas:[],
                loading_modal:false,
                empresa : [],
                columna1:['numero','dni','nombres','empresa','monto_credito','plazo_credito','situacion_apro','estado_cred','estado','actions'],
                options1: {
                          headings: {
                                
                                dni: 'DNI',nombres:'Apellidos y Nombres',empresa:'Entidad',monto_credito:'Credito',plazo_credito:'Nro de Cuotas',situacion_apro:'Situacion',estado_cred:'Estado de Credito',estado:'Estado'
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm table-hover',
                          filterAlgorithm: {
                               full_name(row, query) {
                          return (row.persona.paterno + ' ' + row.persona.materno ).includes(query.toUpperCase());
                             }
                          },
                         filterable: ['numero','full_name','nombres'],
                         
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
                          perPage:60,
                          perPageValues:[25,50,100],
                          skin:'table table-sm table-hover',
                          filterable: false,
                        texts: {
                                count: "Mostrando {from} a {to} de {count} registros|{count} Cuotas|Una Cuota",
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
                        reenganche:false,
                        numero:'',
                        idpersona:'',
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
                        tem:0,
                        fec_apro:0,
                        situacion:0,
                        credito:[],
                        estado:0,
                        tea:0,
                        desgravament:'',
                }),

                // formCronograma: new Form({}),
                logo:'/img/logocasa2.png',
                formCronograma: new Form({
                    iduser :1,
                    idcliente :'',
                    estado :'PENDIENTE',
                    idcredito :'',
                    tasa_men_desgra :0,
                    com_desc_auto :5,
                    tasa_efe_anu :20,
                    t_interes :'',
                    num_cuotas :'',
                    periocidad :'MENSUAL',
                    f_ultima_cuota :'',
                    com_desembolso :5,
                    mon_ne_desem :'',
                    f_desembolso : moment().format('YYYY-MM-DD'),
                    mon_financiado:'',
                    f_primeracuota:'',
                    tea:'',
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
                let result =  this.formCredito.ing_netodiscred - this.formCredito.deuda_sc - this.formCredito.deuda_cc - this.formCredito.deuda_hipo;
                this.formCredito.cuota_max_pres = result;
                return result;
            },

            // calcPlaz_Max_Oper(){
            //     let result = this.formCredito.meses_fal_cont;
            //     this.formCredito.plazo_mas_ope = this.formCredito.meses_fal_cont;
            //     return result;
            // },
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
                console.log(this.formCredito.tem_rci);
                let result = ((1-(Math.pow((1+this.formCredito.tem_rci),- this.formCredito.plazo_mas_ope)))*this.formCredito.cuota_max_pres)/this.formCredito.tem_rci;
                this.formCredito.monto_max_rci = result;
                return result;
            },

            calctem_rci(){
                let result = Math.pow((1+(this.formCredito.idtasa_int/100)), (1/12))-1;
                this.formCredito.tem_rci = result;
                // console.log('asdasdasd', result);
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
            calcdeuda_consu(){
           
                 let result = parseFloat(this.formCredito.sal_deuda_sc) + parseFloat(this.formCredito.sal_deuda_cc);
                 this.formCredito.deuda_consu = result;
                 return result;
             },
            // calcdeuda_consu(){
            //     if (!this.formCredito.plazo_max_apro) {
            //         this.formCredito.plazo_max_apro = 0;
            //         console.log('esta entrando');
            //     }
            //     console.log(this.formCredito.sal_deuda_sc, this.formCredito.plazo_mas_apro);
            //     let result = parseFloat(this.formCredito.sal_deuda_sc) + parseFloat(this.formCredito.plazo_max_apro);
            //     this.formCredito.deuda_consu = result;
            //     return result;
            // },

            calcmonto_max_rdi(){
                let result =  parseFloat(this.formCredito.max_ende)- parseFloat(this.formCredito.deuda_consu)- parseFloat(this.formCredito.otras_deudas);
                this.formCredito.monto_max_rdi = result;
                return result;
            },

            calcmonto_max_apro(){
                let result = Math.min(this.formCredito.monto_max_rdi,this.formCredito.monto_max_rci);
                this.formCredito.monto_max_apro = result;
                return result;
            }


        },
        methods: {

            renganche(valor){
                if (valor == true) {
                    this.formCredito.reenganche = true;
                }else {
                    this.formCredito.reenganche = false;
                }
            },

            viewAntes(data){
                // this.formCredito.fill(data);
                this.view = true;
                this.formCredito.idpersona=data.idpersona,
                this.formCredito.ingre_bru=data.persona.ingre_bru;
                console.log(data);
                this.formCredito.meses_fal_cont=data.meses_fal_cont;
                this.formCredito.patrimonio=data.patrimonio;
                this.formCredito.des_ley=data.des_ley;
                this.formCredito.ing_neto=data.ing_neto;
                this.formCredito.idrci=data.idrci;
                this.formCredito.ing_netodiscred=data.ing_netodiscred;
                this.formCredito.sal_deuda_sc=data.sal_deuda_sc;
                this.formCredito.sal_deuda_cc=data.sal_deuda_cc;
                this.formCredito.deuda_sc=data.deuda_sc;
                this.formCredito.deuda_cc=data.deuda_cc;
                this.formCredito.sal_hipo=data.sal_hipo;
                this.formCredito.deuda_hipo=data.deuda_hipo;
                this.formCredito.cuota_max_pres=data.cuota_max_pres;
                this.formCredito.idplazo=data.idplazo;
                this.formCredito.plazo_mas_ope=data.plazo_mas_ope;
                this.formCredito.primer_pago=data.primer_pago;
                this.formCredito.idtasa_int=data.idtasa_int;
                this.formCredito.tem_rci=data.tem_rci;
                this.formCredito.monto_max_rci=data.monto_max_rci;
                this.formCredito.idrdi=data.idrdi;
                this.formCredito.max_ende=data.max_ende;
                this.formCredito.deuda_consu=data.deuda_consu;
                this.formCredito.otras_deudas=data.otras_deudas;
                this.formCredito.monto_max_rdi=data.monto_max_rdi;
                this.formCredito.monto_max_apro=data.monto_max_apro;
                this.formCredito.tem=data.tem;
                this.formCredito.fec_apro=data.fec_apro;
                this.formCredito.situacion=data.situacion_apro;
                this.formCredito.credito=data.credito;
                this.formCredito.estado=data.estado;
                this.formCredito.id=data.id;
                this.formCliente.fill(data.persona);


                axios.get('/api/viewcrono/'+data.id)
                .then((result) => {
                    // console.log(result.data);
                    let ob = result.data;
                    this.fechas = ob.cuotas.filter(x => x.num_cuota != 0);
                    this.formCredito.desgravament=ob.tasa_men_desgra;
                    this.formCronograma.id = ob.id;
                    this.formCredito.tea=ob.tasa_efe_anu;
                    this.formCronograma.mon_financiado = ob.mon_financiado;
                    this.formCronograma.num_cuotas = ob.num_cuotas;
                    this.formCronograma.desgravament = ob.tasa_men_desgra;
                    this.formCronograma.f_desembolso = ob.f_desembolso;
                    this.formCronograma.f_primeracuota = ob.cuotas[0].fecha_ven;
                    this.formCronograma.tea = ob.tasa_efe_anu;
                    this.formCronograma.t_interes = ob.t_interes;
                    this.formCronograma.f_ultima_cuota = ob.f_ultima_cuota;
                    this.formCronograma.f_c_final = ob.f_ultima_cuota;
                    this.formCronograma.tasa_men_desgra = ob.tasa_men_desgra;
                    this.formCronograma.mon_ne_desem = ob.mon_financiado;
                }).catch((err) => {
                    
                });
                
                // this.generaFecha();
                // this.formCronograma.reset();
                 $('#nuevacre').modal('show');
            },

            selperiodo(){
                axios.get("api/creditos",{params:{'anio':this.anio}})
                .then(({data}) => {
                    this.creditos = data
                }).catch(error => { this.error = error.response });
            },
            printCronograma(id){
                // axios.get('api/creditos/'+id)
                // .then(({data}) => {
                //     console.log(data);
                // }).catch((err) => {
                //     console.log(err);
                // });
                let routeData = this.$router.resolve({path:'/cronograma/'+id});
                window.open(routeData.href, '_blank');
            },
            printFormatos(text,id){
                if (text == 'solicitud') {
                    let routeData = this.$router.resolve({path:'/formatos/solicitudcredito/'+id});
                    window.open(routeData.href, '_blank');
                };
                if (text== 'CartaAutorizacion'){
                    let routeData = this.$router.resolve({path:'/formatos/carta-autorizacion/'+id});
                    window.open(routeData.href, '_blank');
                };
                if (text== 'ContratoMutuoDinerato'){
                    let routeData = this.$router.resolve({path:'/formatos/contrato-mutuo-dinerario/'+id});
                    window.open(routeData.href, '_blank');
                };
                if (text== 'DeclaracionJurada'){
                    let routeData = this.$router.resolve({path:'/formatos/declaracion-jurada/'+id});
                    window.open(routeData.href, '_blank');
                };
                if (text== 'HojaInstrucciones'){
                    let routeData = this.$router.resolve({path:'/formatos/hoja-instrucciones/'+id});
                    window.open(routeData.href, '_blank');
                };
                if (text== 'HojaInstruccionesSinCompra'){
                    let routeData = this.$router.resolve({path:'/formatos/hoja-instrucciones-sin-compra/'+id});
                    window.open(routeData.href, '_blank');
                };
                
                if (text== 'HojaResumen'){
                    let routeData = this.$router.resolve({path:'/formatos/hoja-resumen/'+id});
                    window.open(routeData.href, '_blank');
                };
                if (text== 'Pagare'){
                    let routeData = this.$router.resolve({path:'/formatos/pagare/'+id});
                    window.open(routeData.href, '_blank');
                };
                if (text== 'checklist'){
                    let routeData = this.$router.resolve({path:'/formatos/checklist/'+id});
                    window.open(routeData.href, '_blank');
                };
                if (text== 'informe'){
                    let routeData = this.$router.resolve({path:'/formatos/informe/'+id});
                    window.open(routeData.href, '_blank');
                };
                if (text== 'calculoprestamo'){
                    let routeData = this.$router.resolve({path:'/formatos/calculoprestamo/'+id});
                    window.open(routeData.href, '_blank');
                };
                
            },
            actuaCre(){
                console.log('esta entrando aqui');

                // this.formCronograma.f_ultima_cuota = this.formCronograma.f_c_final;

                axios.post('api/actualizarcredito/'+this.formCredito.id,{'crono': this.formCronograma,'credi': this.formCredito, 'detalle':this.fechas})
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevacre').modal('hide');
                   
                    Toast.fire({
                        type: 'success',
                        title: 'Contrato Creado'
                        })
                })
                .catch((error)=>{
                     this.error = error.response.data.errors;
                })
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
                this.fechas=[];
                const loading = this.$loading({
                    lock: true,
                    text: 'Cargando',
                    spinner: 'el-icon-loading',
                    background: 'white'
                });
                this.loading_modal = true;
                
                let data = {
                    rows:parseInt(this.formCronograma.num_cuotas),
                    fecha:this.formCronograma.f_desembolso,
                    montofinanciado: parseFloat(this.formCronograma.mon_financiado),
                    fecha_cuota:this.formCronograma.f_primeracuota,
                    tea: parseFloat(this.formCronograma.tea),
                    desgravament: parseFloat(this.formCronograma.desgravament)/100,
                    comision_descuento: parseFloat(this.formCronograma.com_desc_auto),
                }
                axios.post('api/calctcea', data)
                .then(
                    data => {
                        loading.close();
                        this.loading_modal = false;
                        this.fechas = data.data.cuotas.filter(x => x.num_cuota != 0);
                        console.log(data);
                        this.formCronograma.t_interes = data.data.suma_intereses;
                        this.formCronograma.f_ultima_cuota = data.data.ultima_fecha;
                        this.formCronograma.f_c_final = data.data.f_cuota_final;
                        this.formCronograma.tasa_men_desgra = data.data.seguro;
                        this.formCronograma.mon_ne_desem = this.formCronograma.mon_financiado;
                        axios.get('/api/correlativo')
                            .then(({data}) => {
                                console.log(data[0]);
                                this.numeroCorre = data[0];
                                this.formCredito.numero = data[0].numero + 1;
                            }).catch((err) => {
                                console.log(err);
                            });
                        this.$message({
                            showClose: true,
                            message: 'Se generó el cronograma satisfactoriamente.',
                            type: 'success'
                        });
                    }
                ).catch((err)=>{
                    loading.close();
                    console.log(err)
                })

            },

            buscarcli(){
               // console.log(this.formCronograma);
                // reset
                this.formCronograma.reset();
                //
                if(this.formCliente.dni == ''){
                    Swal.fire('DNI',
                            'Ingrese el DNI',
                            'error'
                            )
                }else{
                    axios.get("api/buscarcli",{params:{'dni':this.formCliente.dni}})
                    .then( ({data})  =>{
                        console.log(data);
                        this.formCliente.fill(data.cliente);
                        this.formCredito.fill(data.credi);
                        this.formCredito.reenganche = false;
                        this.formCronograma.mon_financiado = this.formCredito.monto_max_apro;
                        this.formCronograma.num_cuotas = this.formCredito.plazo_max_apro;
                        this.formCronograma.f_desembolso = moment().add(5, 'days').format('YYYY-MM-DD');
                        this.formCronograma.f_primeracuota = moment().add(35, 'days').format('YYYY-MM-DD');
                        this.formCronograma.tea = data.credi.tea;
                        this.formCronograma.desgravament = this.formCredito.desgravament;
                        this.generaFecha();
                        }).catch(error => { this.error = error.response });
                //axios({url: 'https://api.reniec.cloud/dni/'+this.form.dni, method: 'get', headers: {'Content-type': 'text/html; charset=UTF-8'}}).then(({ data }) => (console.log(data) )).catch(error => { this.error = error.response });
                //const instance = axios.create({
                    //      baseURL: 'https://api.reniec.cloud/dni',
                    //    headers: {'Access-Control-Allow-Origin': '*'}
                    // });
                    //  instance.get('/40679669').then(res => console.log(res));
                //   axios.get('https://api.reniec.cloud/dni/40679669').then(function (response) {                          console.log(response.data);             });
                }

            },
            nuevocre(){
                this.editmode = false;
                this.formCredito.reset();
                this.formCliente.reset();
                this.formCronograma.reset();
                this.fechas=[];
                this.view = false;
                $('#nuevacre').modal('show');
            },
            deleteCre(id){
                Swal.fire({
                    title: 'Estas Seguro',
                    icon: 'warning',
                    text: "Si se elimina no se podra revertir",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminar!'
                }).then((result) => {

                    // Send request to the server
                    if (result.value) {
                        this.formCredito.delete('api/creditos/' + id).then(() => {
                            Swal.fire(
                                'Eliminado!',
                                'El Credito fue eliminado.',
                                'success'
                            )
                            this.loadUsers();
                        }).catch(() => {
                            Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                        });
                    }
                })

            },
            activaTra(){
            },
            editCre(data){
                this.editmode = true;
                this.view = false;
                // this.formCredito.fill(data);
                this.formCredito.idpersona=data.idpersona,
                this.formCredito.ingre_bru=data.persona.ingre_bru;
                console.log(data);
                this.formCredito.meses_fal_cont=data.meses_fal_cont;
                this.formCredito.patrimonio=data.patrimonio;
                this.formCredito.des_ley=data.des_ley;
                this.formCredito.ing_neto=data.ing_neto;
                this.formCredito.idrci=data.idrci;
                this.formCredito.ing_netodiscred=data.ing_netodiscred;
                this.formCredito.sal_deuda_sc=data.sal_deuda_sc;
                this.formCredito.sal_deuda_cc=data.sal_deuda_cc;
                this.formCredito.deuda_sc=data.deuda_sc;
                this.formCredito.deuda_cc=data.deuda_cc;
                this.formCredito.sal_hipo=data.sal_hipo;
                this.formCredito.deuda_hipo=data.deuda_hipo;
                this.formCredito.cuota_max_pres=data.cuota_max_pres;
                this.formCredito.idplazo=data.idplazo;
                this.formCredito.plazo_mas_ope=data.plazo_mas_ope;
                this.formCredito.primer_pago=data.primer_pago;
                this.formCredito.idtasa_int=data.idtasa_int;
                this.formCredito.tem_rci=data.tem_rci;
                this.formCredito.monto_max_rci=data.monto_max_rci;
                this.formCredito.idrdi=data.idrdi;
                this.formCredito.max_ende=data.max_ende;
                this.formCredito.deuda_consu=data.deuda_consu;
                this.formCredito.otras_deudas=data.otras_deudas;
                this.formCredito.monto_max_rdi=data.monto_max_rdi;
                this.formCredito.monto_max_apro=data.monto_max_apro;
                this.formCredito.tem=data.tem;
                this.formCredito.fec_apro=data.fec_apro;
                this.formCredito.situacion=data.situacion_apro;
                this.formCredito.credito=data.credito;
                this.formCredito.estado=data.estado;
                this.formCredito.id=data.id;
                this.formCliente.fill(data.persona);


                axios.get('/api/viewcrono/'+data.id)
                .then((result) => {
                    // console.log(result.data);
                    let ob = result.data;
                    this.fechas = ob.cuotas.filter(x => x.num_cuota != 0);
                    this.formCredito.desgravament=ob.tasa_men_desgra;
                    this.formCronograma.id = ob.id;
                    this.formCredito.tea=ob.tasa_efe_anu;
                    this.formCronograma.mon_financiado = ob.mon_financiado;
                    this.formCronograma.num_cuotas = ob.num_cuotas;
                    this.formCronograma.desgravament = ob.tasa_men_desgra;
                    this.formCronograma.f_desembolso = ob.f_desembolso;
                    this.formCronograma.f_primeracuota = ob.cuotas[0].fecha_ven;
                    this.formCronograma.tea = ob.tasa_efe_anu;
                    this.formCronograma.t_interes = ob.t_interes;
                    this.formCronograma.f_ultima_cuota = ob.f_ultima_cuota;
                    this.formCronograma.f_c_final = ob.f_ultima_cuota;
                    this.formCronograma.tasa_men_desgra = ob.tasa_men_desgra;
                    this.formCronograma.mon_ne_desem = ob.mon_financiado;
                }).catch((err) => {
                    
                });
                
                // this.generaFecha();
                // this.formCronograma.reset();
                 $('#nuevacre').modal('show');
            },

            getCreditos(){
                axios.get("api/creditos",{params:{'anio':this.anio}})
                .then(({data}) => {
                    this.creditos = data
                }).catch(error => { this.error = error.response });
            },
            loadUsers(){
                axios.get("api/periodos").then(({ data }) => (this.periodos = data)).catch(error => { this.error = error.response });
                
                axios.get("api/creditos",{params:{'anio':this.anio}})
                .then(({data}) => {
                    this.creditos = data

                    }).catch(error => { this.error = error.response });
                //axios.get("api/cLientes").then(({ data }) => (this.relaciondi = data)).catch(error => { this.error = error.response });
                //axios.get("api/delista").then(({ data }) => (this.delista = data)).catch(error => { this.error = error.response });
                //axios.get("api/empresas").then(({ data }) => (this.empresa = data)).catch(error => { this.error = error.response });

            },
            getNumeroReenganche(){
                axios.get("/api/getnumreenganche")
                .then(({data}) => {
                    console.log(data);
                    this.numeroReenganche = data;
                }).catch((err) => {
                    console.log(err);
                });
                
            },
            creaCre(){
                this.formCronograma.f_ultima_cuota = this.formCronograma.f_c_final;

                axios.post('api/creditos',{'crono': this.formCronograma,'credi': this.formCredito, 'detalle':this.fechas})
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevacre').modal('hide');
                   
                    Toast.fire({
                        type: 'success',
                        title: 'Contrato Creado'
                        })
                })
                .catch((error)=>{
                     this.error = error.response.data.errors;
                })

            },

            sumarmeses(fecha, cant_meses){
                // var fecha = new Date('2020-12-31');
                fecha.setMonth(fecha.getMonth() + cant_meses);
                fecha.setDate(fecha.getDate());
                fecha = moment(fecha, 'YYYY-MM-DD').format('YYYY-MM-DD');
                return fecha;
            },

            numerofechaexcel(fecha){
                let date = new Date(fecha);
                date.setDate(date.getDate()+2);
                date.setFullYear(date.getFullYear()+70);
                let numeroObtenido= Date.parse(date);
                numeroObtenido = parseInt((((numeroObtenido/24)/60)/60)/1000);
                // console.log(fecha, numeroObtenido);
                return numeroObtenido;
            },

            createCredit(){
                this.formCredito.estado = 1;
                this.formCredito.post('api/creditos')
                .then((data)=>{
                    console.log(data);
                    this.crearCronograma(data.data.id);
                    // this.generarCrono();
                })
                .catch((err)=>{
                    console.log(err);
                })
            },

            handleTabChange(tabIndex, newTab, oldTab){
                //your code here
                // console.log(newTab.title);
                // console.log(oldTab.title);
            },

            changeTab(){
                this.tabName = 'Cronograma';
                console.log('cambiando');
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
                                    alignment: 'center',
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
                                                text:'Cliente :', style:'cronoheadertext',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text: this.formCliente.paterno+' '+this.formCliente.materno+' '+this.formCliente.nombres , style:'cronoheadertext',
                                            },
                                        ],
                                        [
                                            {
                                                text:'Direccion :', style:'cronoheadertext',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text: this.formCliente.direccion , style:'cronoheadertext',
                                            },
                                        ],
                                        [
                                            {
                                                text:'Credito N° : ', style:'cronoheadertext',
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
                                                text:'Tipo : ', style:'cronoheadertext',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text:'Convenio', style:'cronoheadertext',
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
                                                text: `${ this.formCronograma.desgravament }`, style:'cronoheadertext',
                                            },
                                            // columna
                                            {
                                                text:'Comisión Mensual Dscto Automático', style:'cronoheadertitle',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text:`${ this.formCronograma.com_desc_auto }`, style:'cronoheadertext',
                                            },

                                            // columna

                                            {
                                                text:'Tasa efectiva anual', style:'cronoheadertitle',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text:` ${this.formCronograma.tea}`, style:'cronoheadertext',
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
                                                text:`${this.formCronograma.t_interes}`, style:'cronoheadertext',
                                            },
                                            // columna
                                            {
                                                text:'  Num Cuotas', style:'cronoheadertitle',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text:`${this.formCronograma.num_cuotas}`, style:'cronoheadertext',
                                            },

                                            // columna

                                            {
                                                text:'  Periocidad', style:'cronoheadertitle',
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
                                                text:`${this.formCronograma.f_ultima_cuota}`, style:'cronoheadertext',
                                            },
                                            // columna
                                            {
                                                text:'Comisión por Desembolso', style:'cronoheadertitle',
                                            },
                                            {
                                                text:':', style:'dospuntos',
                                            },
                                            {
                                                text:`${this.formCronograma.com_desembolso}`, style:'cronoheadertext',
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
                                                    text: moment(p.fecha_vencimiento, 'DD-MM-YYYY').format('DD-MM-YYYY'), style:'fecha'
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


            buscarCronograma(){
                if (this.formCredito.credito.length > 0) {
                    axios.get('api/cronograma/'+this.formCredito.credito[0].id)
                    .then(
                        (data)=> {
                            // console.log(data);
                            if (data.data != '') {
                                this.formCronograma.fill(data.data);
                                this.tipocredi = this.formCredito.credito[0].tipo;
                                // console.log('no generó cronograma');
                                this.buscarcuotas(data.data.id)
                            } else {
                                console.log('si generó cronograma');
                                // this.generarCrono();
                                this.crearCronograma(this.formCredito.credito[0].id);
                            }
                        }
                    ).catch(
                        (err)=> {console.log(err)}
                    )
                } else {
                    Swal.fire({
                        icon:'warning',
                        title:'No tiene créditos pendientes',
                        text: '¿Desea crear el crédito?',
                        showCancelButton: true,
                        confirmButtonText: `Si, crear crédito`,
                        cancelButtonText: 'No, cancelar!',
                    }).then((result) => {
                            console.log(result);
                            if (result.value == true) {
                                console.log('confirmado');
                                this.createCredit();
                            } else {
                                // console.log('esta cancelado y evitado');
                            }
                        })
                }
            },
            AprobarCredito(row){
                Swal.fire({
                        icon:'warning',
                        title:'Aprobar Credito x '+ new Intl.NumberFormat("es-PE", {style: "currency", currency: "PEN"}).format(row.monto_credito),
                        text: '¿Desea Aprobar el crédito?',
                        showCancelButton: true,
                        confirmButtonText: `Si, Aprobar Crédito`,
                        cancelButtonText: 'No, cancelar!',
                    }).then((result) => {
                            console.log(result);
                            if (result.value == true) {
                                console.log('confirmado');
                                axios.get("api/aprocredito/"+row.id).then(({ data }) => {
                                      this.loadUsers();
                                      EventBus.$emit('actunoti',data);
                                      //Fire.$emit('AfterCreate');
                                     }).catch(error => { this.error = error.response });
                                

                              
                            } else {
                                // console.log('esta cancelado y evitado');
                            }
                        })

            },
            crearCronograma(idcredito){

                this.formCronograma.idcredito = idcredito;
                this.formCronograma.idcliente = this.formCliente.id;

                axios.post('api/cronograma', this.formCronograma)
                .then(
                    data=> {
                        this.tabName = 'Cronograma';
                        console.log(this.tabName);
                    }
                ).catch(
                    err=> console.log(err)
                )
            },

            buscarcuotas(idcrono){
                axios.get('api/cuota/'+this.formCredito.credito[0].id)
                .then(
                    (data)=> {
                        console.log(data);
                        if (data.data.length > 0) {
                            this.formCronograma.cuotas = data.data;
                            this.tabName = 'Cronograma';
                            this.fechas = this.formCronograma.cuotas;
                            console.log('cuotas', this.fechas);
                        } else {
                            this.tabName = 'Cronograma';
                            console.log('nohay');
                        }
                    }
                ).catch(
                    (err)=> {console.log(err)}
                )
            },

            getRoles(){
                axios.get("/api/roleuser").then(({ data }) => {
                    this.roles=data;
                    console.log(data);
                }).catch();
            }

        },


        created() {
           this.loadUsers();
           this.getRoles();
           this.getNumeroReenganche();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
           });

           setInterval(() => this.getCreditos(), 10000);
        //    setInterval(() => this.loadUsers(), 3000);
        },
    }
</script>

