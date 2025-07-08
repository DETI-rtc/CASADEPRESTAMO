<template>

    <div class="">
         
        
        <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title" style="font-size: 24px !important;"> <i class="fas fa-users mr-2" ></i> RELACION DE PAGOS </h3>
                            <div class="card-tools">                         
                       
                                <div class="btn-group" role="group">
                                     <el-select v-model="anio" filterable placeholder="Seleccione" @input="getCuotasAnio" class="mt-2">
                                        <el-option
                                        v-for="peri in periodos"
                                        :key="peri.id"
                                        :label="peri.año"
                                        :value="peri.año"></el-option>
                                    </el-select>
                                    <el-select v-model="mes" filterable placeholder="Seleccione" @input="getmes" class="mt-2">
                                        <el-option
                                            v-for="item in meses"
                                            :key="item.value"
                                            :label="item.label"
                                            :value="item.value"
                                        ></el-option>
                                    </el-select>
                            
                                    <button class="btn btn-warning btm-sm lh-1"  @click="openModalPago" v-show="!roles.includes('temporal')"> Registrar Pago <i class="fas fa-plus"></i></button>
                                    <button class="btn btn-success btm-sm lh-1"  @click="openModalN" v-show="!roles.includes('temporal')"> Registrar Pago N <i class="fas fa-plus"></i></button>
                                    <button class="btn btn-info btm-sm lh-1"  @click="openModalAmortizacion" v-show="!roles.includes('temporal')"> Amortizacion <i class="fas fa-plus"></i></button>
                                </div>
                                 <!-- <button class="btn btn-warning"  @click="imprimirVoucher()"> Cancelar Pago <i class="fas fa-plus"></i></button> -->
                            </div>
                    </div>

                    <div class="card-body" ref="contento" >
                            <template v-if="loading==true">
                                <div class="d-flex justify-content-center" >
                                    <div class="spinner-border text-success" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                    <div class="mt-2 w-100 text-center text-success">
                                        <span>Cargando información</span>
                                    </div>
                            </template>
                            <v-client-table :data="pagos" :columns="columna" :options="options"  id="#pagos" v-else>
                                    
                                    <div slot="cliente" slot-scope="{row}" >
                                      {{row.persona.paterno}}
                                    </div>
                                    <div slot="cuopago" slot-scope="{row}">
                                      {{row.cuopago | currency  }}
                                     </div>
                                     <div slot="mon_pag" slot-scope="{row}">
                                      {{row.mon_pag | currency  }}
                                     </div>
                                     <div slot="cappago" slot-scope="{row}">
                                      {{row.cappago | currency  }}
                                     </div>
                                     <div slot="intpago" slot-scope="{row}">
                                      {{row.intpago | currency  }}
                                     </div>
                                     <div slot="segpago" slot-scope="{row}">
                                      {{row.segpago | currency  }}
                                     </div>
                                     <div slot="compago" slot-scope="{row}">
                                      {{row.compago | currency  }}
                                     </div>
                                     <div slot="ultimopago" slot-scope="{row}">
                                      {{row.ultimopago | myDate  }}
                                     </div>
                                     <div slot="comprobante" slot-scope="{row}" class="text-primary">
                                      {{row.comprobante}}
                                     </div>
                                    
                                    <div slot="acciones" slot-scope="{ row }">
                                        <div class="btn-group">
                                            <!-- <i class="fa fa-eye" @click="verPago(row.id)"></i>                                           -->
                                            <button type="button" class="btn btn-info btn-sm" @click="verPago(row.id)"><i class="fa fa-eye"></i></button>
                                            <template v-if="row.comprobante == null">
                                                
                                                <button type="button" class="btn btn-warning btn-sm" @click="openEditPago(row)" v-show="!roles.includes('temporal')"><i class="fas fa-edit"></i></button>
                                                <!-- <button type="button" class="btn btn-success btn-sm" @click="PagoCompro(row)" v-show="!roles.includes('temporal')"><i class="fas fa-ticket-alt"></i></button> -->
                                            </template>
                                            
                                        </div>
                                    </div>
                                </v-client-table>
                    </div>
                <!-- </div> -->
        </div>
        <!---///nuevo-->   
        <!-- modal de agregar lista -->
        <div class="modal bd-example-modal-xl " id="regPago" tabindex="-1" role="dialog" >
                <div class="modal-dialog modal-xl" role="document"  >
                    <div class="modal-content" :class="!editmode ? 'card card-outline card-primary' : 'card card-outline card-success'">
                        <div class="card-header">
                        <h5 class="card-title" v-show="!editmode" id="addNewLabel">Pagar Cuota</h5>
                        <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar Cuota</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-bs-dismiss="modal"><i
                                    class="fas fa-times"></i></button>
                        </div>

                    </div>
                        <div class="row">
                            <div class="col-6">
                                <form @submit.prevent="editmode ? ActuaPago() : creaPago()">
                                <div class="modal-body">
                                    <div class="form-group m-0 row " >
                                        <div class="col-7">
                                            <label >Fecha de Pago</label>
                                        </div>
                                        <div class="col-5">
                                        <input type="date"  class="form-control form-control-sm" placeholder="Fecha" v-model="formPago.fecha_pago"> 
                                        </div>
                                    </div>
                                    <div class="form-group col-12" v-show="!editmode">
                                        <label> Seleccione Cliente</label>
                                        <v-select  label="pendientes" :options="creditospen" :reduce="pendientes => pendientes.id"  v-model="formPago.idcredito"  @input="buscarcredito"></v-select>
                                    </div>
                                <div class="form-group">
                                        <!-- <div class="row">
                                            <div class="col-3">
                                                <label>DNI <span style="color: red; font-weight: bold;">*</span></label>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-group ">
                                                  <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                                                    </div> 
                                                    <input type="text" class="form-control" maxlength="8" v-model="inputdni">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-success" @click="buscarPorDni"><i class="fas fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                                        </div>
                                        <div class="form-group m-0 row " >
                                            <div class="col-3">
                                                <label>DNI <span style="color: red; font-weight: bold;">*</span></label>
                                            </div>
                                            <div class="col-9">
                                                <label>{{formPago.dni}}</label>
                                            </div>
                                        </div>

                                        <div class="form-group row  m-0">
                                            <label class="col-sm-3 col-form-label" >Entidad <span style="color: red; font-weight: bold;">*</span></label>
                                            <div class="col-sm-9">
                                                <label>{{formPago.empresa}}</label>
                                            </div>
                                        </div>

                                        <div class="form-group row  m-0">
                                            <label class="col-sm-7 col-form-label" >Número de cuota <span style="color: red; font-weight: bold;">*</span></label>
                                            <div class="col-sm-5">
                                                <v-select  :options="cuotas" :reduce="cuotas => cuotas.cuotanro"  v-model="formPago.cuotanro" label="cuotanro" @input="mostrarcuota">
                                                    <template slot="option" slot-scope="option">
                                                            <span class="fa" :class="option.icon"></span>
                                                            {{ option.cuotanro }} - {{ option.fecha_ven }}
                                                        </template>
                                                </v-select>
                                            </div>
                                        </div>
                                        <div class="form-group row  m-0">
                                            <label class="col-sm-6 col-form-label"> Total de Cuota <span style="color: red; font-weight: bold;">*</span></label>
                                            <div class="col-sm-6">
                                                <label class="text.bold text-lg mt-2 mb-0 float-right" >{{formPago.cuotames | currency}} </label>
                                            </div>
                                        </div>

                                        
                                        <!-- <div class="form-group row  m-0">
                                            <label class="col-sm-6 col-form-label"> Nro de C/P</label>
                                            <div class="col-sm-6">
                                                <vue-autonumeric :options="'integer'" v-model="formPago.com_pago"  class="form-control form-control-sm text-right" ></vue-autonumeric>
                                                
                                            </div>
                                        </div> -->

                                        <div class="form-group row  m-0">
                                            <label class="col-sm-6 col-form-label" >Monto a Pagar (Descuento Planilla) S/.<span style="color: red; font-weight: bold;">*</span></label>
                                            <div class="col-sm-6">
                                                <vue-autonumeric :options="''" v-model="formPago.monto_desc" class="form-control form-control-sm text-right text-lg text-bold"  ></vue-autonumeric>
                                            </div>
                                        </div>
                                        <div class="form-group row  m-0">
                                            <label class="col-sm-6 col-form-label"> Saldo de Cuota</label>
                                            <div class="col-sm-3">
                                                <vue-autonumeric :options="''" v-model="formPago.saldo" :value="calsaldo" class="form-control form-control-sm text-lg text-right" disabled></vue-autonumeric>
                                                
                                            </div>
                                            <div class="form-check text-center col-sm-3 mt-2">
                                            <input class="form-check-input" type="checkbox" v-model="formPago.adelanto" true-value="S" false-value="N" id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                  Adelanto
                                            </label>
                                        </div>
                                        </div>
                                        <!-- <div class="form-group row  m-0">
                                            <label class="col-sm-6 col-form-label" >Monto a Pagar (Efectivo) S/.</label>
                                            <div class="col-sm-6">
                                                <vue-autonumeric :options="''" v-model="formPago.monto_efectivo" class="form-control form-control-sm text-right text-lg text-bold"  ></vue-autonumeric>
                                            </div>
                                        </div>

                                        <div class="form-group row  m-0">
                                            <label class="col-sm-6 col-form-label" >Fecha Pago Efectivo</label>
                                            <div class="col-sm-6">
                                                <input type="date" class="form-control" v-model="formPago.fec_pago_efec">
                                            </div>
                                        </div> -->
                                        
                                        

                                        <div class="form-group row  m-0">
                                            <label class="col-sm-6 col-form-label" >Total Monto a Pagar S/.<span style="color: red; font-weight: bold;">*</span></label>
                                            <div class="col-sm-6">
                                                 <label class="text.bold text-lg mt-2 mb-0 float-right" >{{formPago.monto = formPago.monto_desc}} </label>
                                                <!-- <vue-autonumeric :options="''" v-model="formPago.monto" class="form-control form-control-sm text-right text-lg text-bold" disabled ></vue-autonumeric> -->
                                            </div>
                                        </div>

                                        <div class="form-group row  m-0">
                                            <label class="col-sm-6 col-form-label" >Referencia <span style="color: red; font-weight: bold;">*</span></label>
                                            <div class="col-sm-6">
                                                <!-- <input type="text" class="form-control" v-model="formPago.comentario"> -->
                                                <textarea cols="30" rows="4" class="form-control" v-model="formPago.comentario"></textarea>
                                                <!-- <vue-autonumeric :options="''" v-model="formPago.comentario" class="form-control form-control-sm text-right text-lg text-bold"  ></vue-autonumeric> -->
                                            </div>
                                        </div>

                                        <div class="form-group row m-0" >
                                            <label class="col-sm-6 col-form-label">Fecha de Recibo</label>
                                            <div class="col-5">
                                                <input type="date"  class="form-control form-control-sm" placeholder="Fecha" v-model="formPago.fecha_recibo"> 
                                            </div>
                                        </div>

                                        <div class="form-group row m-0" >
                                            <label class="col-sm-6 col-form-label">Nro de Recibo</label>
                                            <div class="col-5">
                                                <input type="number"  class="form-control form-control-sm" placeholder="Fecha" v-model="formPago.nro_recibo"> 
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" ><i class="fas fa-save"></i> Registrar pagos</button>
                                </div>
                        </form>
                            </div>
                            <div class="col-6 p-3" style="height:600px; overflow-y: scroll;">
                                <table class="table table-striped table-sm">
                                            <thead class="bg-success">
                                                <tr>
                                                    <th class=" text-center">N° Cuota</th>
                                                    <th class=" text-center">F. Venc</th>
                                                    <th class=" text-center">Saldo Cap</th>
                                                    <th class=" text-center">Cap. Amort</th>
                                                    <th class=" text-center">Interes</th>
                                                    <th class=" text-center">Com. Desc</th>
                                                    <th class=" text-center">Seguro Desg</th>
                                                    <th class=" text-center">Cuota</th> 
                                                    <th class=" text-center">Saldo Adelanto</th>  
                                                    <th class=" text-center">Situacion</th>                                 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template v-if="pagos_reg.length > 0 ">
                                                    <tr v-for="i in pagos_reg" :key="i.id">
                                                        <td class=" text-center align-middle font-weight-bolder" style="font-size: 14px;">  {{ i.cuotanro}} </td>
                                                        <td class=" text-center align-middle"> {{ i.fecha_ven |myDate}} </td>
                                                        <td class=" text-right align-middle" > {{ parseFloat(i.sal_cap).toFixed(2)}} </td>
                                                        <td class=" text-right align-middle"> {{ parseFloat(i.cap_amor).toFixed(2)}} </td>
                                                        <td class=" text-right align-middle"> {{ parseFloat(i.interes).toFixed(2)}} </td>
                                                        <td class=" text-right align-middle"> {{ parseFloat(i.com_des).toFixed(2)}} </td>
                                                        <td class=" text-right align-middle"> {{ parseFloat(i.seg_des).toFixed(2)}} </td>
                                                        <td class=" text-right text-primary align-middle font-weight-bold" style="font-size: 14px;"> {{ parseFloat(i.cuota).toFixed(2)}} </td>
                                                        <td class=" text-right align-middle" v-if="i.saldo_ade > 0"> {{ parseFloat(i.saldo_ade).toFixed(2)}} </td>
                                                        <td class=" text-right align-middle" v-else> 0.00 </td>

                                                        <td class=" text-center align-middle"><span class="badge badge-pill badge-info" v-if="i.situacion == 'P'">Pendiente</span>
                                                            <span class="badge badge-pill badge-danger" v-if="i.situacion == 'C'">Cancelado</span>
                                                                
                                                            </td>
                                                        

                                                    </tr>
                                                </template>
                                                
                                            </tbody>
                                        </table>
                            </div>
                        </div>


                        
                    </div>
                </div>
        </div>
        <!-- MODAL DE REGISTRO DE PAGOS -->
        <div class="modal fade" id="pagosModal" tabindex="-1" role="dialog" aria-labelledby="pagosModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pagosModalLabel">Registrar Pagos</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Entidad : </label>
                            <br>
                            <el-select v-model="formModalPago.entidad" filterable placeholder="Seleccione" @input="getCuotasPago">
                                <el-option
                                v-for="item in empresas"
                                :key="item.id"
                                :label="item.razonsocial"
                                :value="item.id">
                                </el-option>
                            </el-select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Año : </label>
                            <br>
                            <el-select v-model="formModalPago.anio" filterable placeholder="Seleccione" @input="getCuotasPago">
                                <el-option
                                v-for="peri in periodos"
                                :key="peri.id"
                                :label="peri.año"
                                :value="peri.año">
                                </el-option>
                            </el-select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>Mes : </label>
                            <br>
                            <el-select v-model="formModalPago.mes" filterable placeholder="Seleccione" @input="getCuotasPago">
                                <el-option
                                v-for="item in meses"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                                </el-option>
                            </el-select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>Fecha </label>
                            <br>
                            <input type="date" class="form-control" v-model="formModalPago.fecha_pago">
                        </div>
                        <div class="form-group col-md-2">
                            <label>TOTAL: </label> 
                            <br> 
                            <h4 style="text-align: right;" class="text-primary"><strong>{{calcTotalPa}}</strong></h4> 
                            <!-- <input type="date" class="form-control" v-model="formModalPago.fecha_pago"> -->
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            
                            <tr>
                                <th>Nro Crédito</th>
                                <th>Cliente</th>
                                <th>Nro Cuota</th>
                                <th>Fecha Venc.</th>
                                <th>Fecha Pago</th>
                                <th>Monto Cuota</th>
                                <th>Monto Pendiente</th>
                                <th>Monto a Pagar (Desc)</th>
                                <!-- <th>Monto a Pagar (Efectivo)</th> -->
                                <th>Monto Total a Pagar</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(i,key) in formModalPago.detalle" :key="key">
                                <td> C000{{ i.numero }} </td>
                                <td class="text-left"> {{ i.cliente }} </td>
                                <td > {{ i.cuotanro }} </td>
                                <td > {{ i.fecha_ven }} </td>
                                <td > <input type="date" class="form-control" v-model="i.fecha_pago"> </td>
                                <td class="text-right"> {{ i.cuota }} </td>
                                <td class="text-right"> {{ i.pendiente }} </td>
                                <td>  <vue-autonumeric :options="{decimalPlaces:2}" v-model="i.montopagar" class="form-control form-control-sm text-right"  ></vue-autonumeric> </td>
                                <!-- <td>  <vue-autonumeric :options="{decimalPlaces:2}" v-model="i.montopagarefec" class="form-control form-control-sm text-right"  ></vue-autonumeric> </td> -->
                                <td>  {{ i.monto = i.montopagar + i.montopagarefec }} </td>
                                <td> <button class="btn btn-danger btn-sm" @click="quitClientModalPago(i.id)"><i class="fas fa-trash-alt"></i></button> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" @click="modalVerifi">Registrar</button>
                </div>
                </div>
            </div>
        </div>
        <!-- MODAL AMORTIZACION -->
        <div class="modal fade" id="amortizacionModal" tabindex="-1" role="dialog" aria-labelledby="amortizacionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="amortizacionModalLabel">Registrar amortización</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Cliente : </label>
                                <br>
                                <el-select v-model="idcredito" filterable placeholder="Seleccione" style="width:100%;" @input="getCuotasPendientes">
                                    <el-option
                                    v-for="item in clientes"
                                    :key="item.idcredito"
                                    :label="item.cliente"
                                    :value="item.idcredito">
                                    </el-option>
                                </el-select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Opcion : </label>
                                <br>
                                <el-select v-model="tipoAmortiza" filterable placeholder="Seleccione" style="width:100%;" @input="resetCalculo">
                                    <el-option
                                    v-for="item in tipoAmortizacion"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                            </div>

                            
                            <div class="form-group col-md-3">
                                <label>Fecha Desembolso : </label>
                                <br>
                                <input type="date" class="form-control" v-model="fecha_desembolso">
                            </div>

                            <div class="form-group col-md-3">
                                <label>Fecha Primera Cuota : </label>
                                <br>
                                <input type="date" class="form-control" v-model="fecha_primera_cuota">
                            </div>
                            

                            <div class="form-group col-md-3">
                                <label>Monto Amortizar : </label>
                                <br>
                                <vue-autonumeric :options="''" v-model="montoAmortizar" class="form-control form-control text-right text-lg text-bold" @input="resetCalculo"></vue-autonumeric>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Cant Cuotas : </label>
                                <br>
                                <vue-autonumeric :options="''" v-model="cantCuotas" class="form-control form-control text-right text-lg text-bold" @input="resetCalculo"></vue-autonumeric>
                            </div>

                            <!-- <div class="form-group col-md-3">
                                <label>Fecha Pago : </label>
                                <br>
                                <input type="date" class="form-control" v-model="fecha_pago">
                            </div>

                            <div class="form-group col-md-3">
                                <label>Descripción : </label>
                                <br>
                                 <textarea class="form-control" rows="3" v-model="justificacion"></textarea>
                            </div> -->

                            
                            
                            <div class="form-group col-md-3">
                                <label></label>
                                <br>
                                <button class="btn btn-success mt-2" @click="amortizar">Calcular</button>
                                <!-- <vue-autonumeric :options="''" v-model="montoAmortizar" class="form-control form-control text-right text-lg text-bold"></vue-autonumeric> -->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 p-1">
                                <div class="text-center">
                                    <h5><strong>Cuotas Pendientes</strong></h5>
                                </div>
                                <table class="table table-bordered">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>N° Cuota</th>
                                            <th>F. Venc</th>
                                            <th>Saldo Cap</th>
                                            <th>Cap. Amort</th>
                                            <th>Interes</th>
                                            <th>Com. Desc</th>
                                            <th>Cuota</th>  
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="cuotaspendientes.length > 0 && loadAmoGetDeuda == false">
                                            <tr v-for="i in cuotaspendientes">
                                                <td> {{ i.cuotanro }} </td>
                                                <td> {{ i.fecha_venci }} </td>
                                                <td> {{ i.sal_cap }} </td>
                                                <td> {{ i.cap_amor }} </td>
                                                <td> {{ i.interes }} </td>
                                                <td> {{ i.com_des }} </td>
                                                <td> {{ i.cuota }} </td>
                                            </tr>
                                        </template>
                                        <tr v-else-if="cuotaspendientes.length < 1 && loadAmoGetDeuda == false">
                                            <td colspan="7"> No se encontraron datos </td>
                                        </tr>
                                        <tr v-else-if="cuotaspendientes.length < 1 && loadAmoGetDeuda == true">
                                            <td colspan="7"> Cargando... </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6 p-1">
                                <div class="text-center">
                                    <h5><strong>Calculo de Amortización</strong></h5>
                                </div>
                                <table class="table table-bordered">
                                    <thead class="bg-success">
                                        <tr>
                                            <th>N° Cuota</th>
                                            <th>F. Venc</th>
                                            <th>Saldo Cap</th>
                                            <th>Cap. Amort</th>
                                            <th>Interes</th>
                                            <th>Com. Desc</th>
                                            <th>Cuota</th>                                   
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="nuevoCrono.cuotas.length > 0 && loadAmoGetCalc == false">
                                            <tr v-for="(i, index) in nuevoCrono.cuotas" :key="index">
                                                <!-- <td> {{ i.cuotanro}} </td>
                                                <td> {{ i.fecha_venci}} </td>
                                                <td> {{ parseFloat(i.sal_cap).toFixed(2)}} </td>
                                                <td> {{ parseFloat(i.cap_amor).toFixed(2)}} </td>
                                                <td> {{ parseFloat(i.interes).toFixed(2)}} </td>
                                                <td> {{ parseFloat(i.com_des).toFixed(2)}} </td>
                                                <td> {{ parseFloat(i.cuota).toFixed(2)}} </td> -->

                                                <td> {{ i.num_cuota}} </td>
                                                <td> {{ i.fecha_vencimiento}} </td>
                                                <td> {{ parseFloat(i.saldo_capital).toFixed(2)}} </td>
                                                <td> {{ parseFloat(i.capital_amortizado).toFixed(2)}} </td>
                                                <td> {{ parseFloat(i.interes).toFixed(2)}} </td>
                                                <td> {{ parseFloat(i.com_desc_automatico).toFixed(2)}} </td>
                                                <td> {{ parseFloat(i.cuota).toFixed(2)}} </td>
                                            </tr>
                                        </template>
                                        <tr v-else-if="nuevoCrono.cuotas.length < 1 && loadAmoGetCalc == false">
                                            <td colspan="7"> No se encontraron datos </td>
                                        </tr>
                                        <tr v-else-if="nuevoCrono.cuotas.length < 1 && loadAmoGetCalc == true">
                                            <td colspan="7"> Cargando... </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="guardarAmortizacion(nuevoCrono.cuotas)">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>
        <!--mostrar Pagos-->
        <div class="modal fade" id="ModalPago" tabindex="-1" role="dialog" aria-labelledby="ModalPagoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalPagoLabel">Registro de Pagos</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Cliente </label>
                                <input type="text" class="form-control form-control-border" id="exampleInputBorder" v-model="vcliente">
                            </div> 
                            <div class="form-group col-md-2" >
                                <label for="exampleInputBorder">Total Cancelado</label>
                                <input type="text" class="form-control form-control-border" id="exampleInputBorder" v-model="pagado">
                                                    
                            </div>
                            <div class="form-group col-md-2" >
                                <label for="exampleInputBorder">Deuda</label>
                                <input type="text" class="form-control form-control-border" id="exampleInputBorder" v-model="pendiente">
                                                    
                            </div>
                            <div class="form-group col-md-2" >
                                <label for="exampleInputBorder">C. Canceladas</label>
                                <input type="text" class="form-control form-control-border" id="exampleInputBorder" v-model="cuotacan">
                                                    
                            </div>
                            <div class="form-group col-md-2" >
                                <label for="exampleInputBorder">C. Pendientes</label>
                                <input type="text" class="form-control form-control-border" id="exampleInputBorder" v-model="cuotapen">
                                                    
                            </div>
                            <div class="form-group col-md-2" >
                                <label for="exampleInputBorder">Amortizaciones</label>
                                <input type="text" class="form-control form-control-border" id="exampleInputBorder" v-model="amorti">
                                                    
                            </div>
                        </div>
                        <div class="row">
                                    <table class="table table-bordered table-sm">
                                            <thead class="bg-success">
                                                <tr>
                                                    <th class=" text-center">N° Cuota</th>
                                                    <th class=" text-center">F. Venc</th>
                                                    <th class=" text-center">Saldo Cap</th>
                                                    <th class=" text-center">Cap. Amort</th>
                                                    <th class=" text-center">Interes</th>
                                                    <th class=" text-center">Com. Desc</th>
                                                    <th class=" text-center">Cuota</th>   
                                                    <th class=" text-center">Situacion</th>                                 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template v-if="regpagos.length > 0 ">
                                                    <tr v-for="i in regpagos">
                                                        <td class=" text-center align-middle"> {{ i.cuotanro}} </td>
                                                        <td class=" text-center align-middle"> {{ i.fecha_ven |myDate}} </td>
                                                        <td class=" text-right align-middle" > {{ parseFloat(i.sal_cap).toFixed(2)}} </td>
                                                        <td class=" text-right align-middle"> {{ parseFloat(i.cap_amor).toFixed(2)}} </td>
                                                        <td class=" text-right align-middle"> {{ parseFloat(i.interes).toFixed(2)}} </td>
                                                        <td class=" text-right align-middle"> {{ parseFloat(i.com_des).toFixed(2)}} </td>
                                                        <td class=" text-right align-middle"> {{ parseFloat(i.cuota).toFixed(2)}} </td>
                                                        <td class=" text-center align-middle"><span class="badge badge-pill badge-info" v-if="i.situacion == 'P'">Pendiente</span>
                                                            <span class="badge badge-pill badge-danger" v-if="i.situacion == 'C'">Cancelado</span>
                                                                
                                                            </td>
                                                        

                                                    </tr>
                                                </template>
                                                
                                            </tbody>
                                        </table>                    
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="editarPagoModal" tabindex="-1" role="dialog" aria-labelledby="editarPagoModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarPagoModalLabel">Editar Pago</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Fecha Pago</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control"  v-model="formModalEditPago.ultimopago">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Cliente</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control-plaintext" readonly  v-model="formModalEditPago.clientes">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">DNI</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control-plaintext" readonly  v-model="formModalEditPago.dni">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Empresa</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control-plaintext" readonly  v-model="formModalEditPago.empresa">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">N° de Cuota</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control-plaintext" readonly  v-model="formModalEditPago.cuopago">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Monto Pagado</label>
                            <div class="col-sm-10">
                                <vue-autonumeric :options="{decimalPlaces:2}" v-model="formModalEditPago.mon_pag" class="form-control text-right"  ></vue-autonumeric>
                                <!-- <input type="text" class="form-control-plaintext" v-model="formModalEditPago.mon_pag"> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Comentario</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" v-model="formModalEditPago.comentario"  rows="3"></textarea>
                                <!-- <input type="text" class="form-control" readonly  v-model="formModalEditPago.comentario"> -->
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nro Comprobante</label>
                            <div class="col-sm-10">
                                <!-- <textarea class="form-control" v-model="formModalEditPago.nro_recibo"  rows="3"></textarea> -->
                                <input type="text" class="form-control"  v-model="formModalEditPago.nro_recibo">
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Fecha Comprobente</label>
                            <div class="col-sm-10">
                                <!-- <textarea class="form-control" v-model="formModalEditPago.fecha_recibo"  rows="3"></textarea> -->
                                <input type="date" class="form-control"   v-model="formModalEditPago.fecha_recibo">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" @click="editarPago"><i class="fas fa-save"></i> Guardar</button>
                </div>
                </div>
            </div>
        </div>

        
    </div>
</template>

<script>
import pdf from 'vue-pdf';
import XLSX from 'xlsx';
import moment from 'moment';
import Loading from 'vue-loading-overlay'
//import Vuetable from 'vuetable-2'

    export default {
        components:{
            Loading
        },
        props: ['ruta', 'usuario', 'listPermisos','listRoles'],
        data(){
            return {
                pagos_reg:[],
                cantCuotas:'',
                dataAmortizacion:'',
                vcliente:'',
                anio:moment().format('YYYY'),
                editmode: false,
                loading:false,
                loadAmoGetDeuda:false,
                loadAmoGetCalc:false,
                creditospen:[],
                cuotaspendientes:[],
                roles:[],
                data_table:[],
                inputdni:'',
                cliente :'',
                idcredito:'',
                montoAmortizar:0,
                clientes:[],
                nempresa:'-',
                empresas : [],
                cuotas:[],
                //cuotames:'',
                pagos:[],
                periodos:[],
                monto_an_pagar:'',
                idEmpresaImportExcel:'',
                nuevoCrono: new Form({
                    cuotas:[],
                }),
                creditoSelect:'',
                meses:[
                    {value:1,label:'ENERO'},
                    {value:2,label:'FEBRERO'},
                    {value:3,label:'MARZO'},
                    {value:4,label:'ABRIL'},
                    {value:5,label:'MAYO'},
                    {value:6,label:'JUNIO'},
                    {value:7,label:'JULIO'},
                    {value:8,label:'AGOSTO'},
                    {value:9,label:'SETIEMBRE'},
                    {value:10,label:'OCTUBRE'},
                    {value:11,label:'NOVIEMBRE'},
                    {value:12,label:'DICIEMBRE'},
                ],
                mes:'',
                tipoAmortiza:'p',
                fecha_desembolso:'',
                fecha_primera_cuota:'',
                tipoAmortizacion:[
                    {value:'p',label:'Disminución de Plazo'},
                    // {value:'c',label:'Disminución de valor de cuota'},
                ],
                formPago : new Form ({
                    idcredito:'',
                    dni:'',
                    empresa:'',
                    idcliente:'',
                    fecha_pago:moment().format('YYYY-MM-DD'),
                    // fec_pago_efec:moment().format('YYYY-MM-DD'),
                    fec_pago_efec:'',
                    fecha_ven:'',
                    monto:0,
                    cuotames:0,
                    cuotanro:'',
                    saldo:0,
                    idcrono:'',
                    com_pago:0,
                    cap_amor:0,
                    interes:0,
                    com_des:0,
                    seg_des:0,
                    comentario:'',
                    monto_efectivo:0,
                    monto_desc:0,
                    fecha_recibo:'',
                    nro_recibo:'',
                    adelanto:'N',
                }),
                justificacion :'',
                fecha_pago :'',
                regpagos:[],
                total:0,
                pagado:0,
                pendiente:0,
                cuotacan:0,
                cuotapen:0,
                amorti:0,                
                formFile: new Form({
                    excel:'',
                }),
                columna:['dni','clientes','cuopago','mon_pag','cappago','intpago','segpago','compago','ultimopago','comprobante','acciones'],  
                options: {
                          headings: {
                               dni:'DNI', clientes:'Cliente',cuopago:'Nro Cuota',mon_pag:'Monto Pagado',cappago:'Capital Pag.',segpago:'Seguro Pag',compago:'Comision Pag', ultimopago:'Fecha Pago',
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm',
                          filterable: ['dni','clientes','mon_pag'],
                         
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
                columnap:['cuotanro','fecha_ven','sal_cap','cap_amor','interes','com_des','seg_des','cuota','situacion'],  
                optionsp: {
                          headings: {
                               cuotanro:'Nro de Cuota', fecha_ven:'Fecha de Venc.',sal_cap:'Csaldo Capital.',interes:'Intereses',com_des:'Comision',seg_des:'Seguro Desgrava',cuota:'Cuota', situacion:'estado',
                              },
                          perPage:100,
                         pagination: false,
                          skin:'table table-sm',
                          filterable: false,                         
                          texts: {
                                count: "Mostrando {from} a {to} de {count} Cuotas|{count} Cuotas|Un registro",
                                first: 'Primero',
                                last: 'Ultimo',
                                filter: "Filtrar:",
                                filterPlaceholder: "Buscar",
                                limit: "Cuotas:",
                                page: "Pagina:",
                                noResults: "No se encontro registros",
                                filterBy: "Filtrar {column}",
                                loading: 'Cargando...',
                                defaultOption: 'Seleccionar {column}',
                                columns: 'Columna',
                                resizableColumns: true,                  
                                },
                }, 

                columnastabladescuentos:['numero','dni','monto', 'actions'],
                opcionestabladescuentos:{
                    editableColumns:['dni','monto'],
                    headings: {numero:'N°', dni: 'DNI',monto:'Monto S/.', actions:'Acciones'},
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm table-hover',
                          filterable: false,
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


                formModalPago:new Form({
                    entidad:'',
                    mes:parseInt(moment().format('M')),
                    anio:moment().format('YYYY'),
                    fecha_pago:'',
                    detalle:[]
                }),

                formModalEditPago:new Form({
                    cappago: 0,
                    clientes: "AIME ORIHUELA SHIRLEY PAMELA",
                    compago: 0,
                    cuopago: 0,
                    dni: '',
                    empresa: '',
                    id: 0,
                    intpago: 0,
                    mon_pag: 0,
                    segpago: 0,
                    ultimopago: '',
                    id_pago:'',
                    comentario:'',
                    idcliente:'',
                    ncredito:'',
                    fecha_recibo:'',
                    nro_recibo:'',
                }),


            }
        },

        components:{
            pdf
        },
        mounted() {
            // console.log('Component mounted.')
            
        },

        methods:{
            getmes(){
                if (this.mes == null || this.mes == '') {
                    return;
                }
                axios.get("api/pago",{params:{anio:this.anio, mes: this.mes}}).then(({ data }) => (this.pagos = data)).catch(error => { this.error = error.response });
            },
            PagoCompro(row){

                Swal.fire({
                        icon:'warning',
                        title:'Confirmación',
                        text: '¿Está seguro de generar el Comprobante?',
                        showCancelButton: true,
                        confirmButtonText: `Si, estoy seguro`,
                        cancelButtonText: 'No, cancelar!',
                }).then((result) => {
                    console.log(result);
                    if (result.value == true) {
                        this.loading = true;
                            this.formModalEditPago.fill(row);
                            this.formModalEditPago.post('api/pagacomprobante')
                            .then(({data}) => {
                                this.loading = false;
                                this.$notify({
                                    title: 'Satisfactorio',
                                    message: 'Se registro el Comrobante',
                                    type: 'success'
                                });
                                this.loadUsers();
                                
                            }).catch((err) => {
                                this.loading = false;
                                this.$notify({
                                    title: 'Error!',
                                    message: 'Error de Servidor.',
                                    type: 'error'
                                });
                                console.log(err);
                            });

                    } else {
                        // console.log('esta cancelado y evitado');
                    }
                })

                
            },
            openEditPago(row){
                console.log(row);
                this.formModalEditPago.fill(row);
                $('#editarPagoModal').modal('show');
            },
            editarPago(){
                this.loading = true;
                this.formModalEditPago.put('api/pago/'+this.formModalEditPago.id_pago)
                .then(({data}) => {
                    this.loading = false;
                    $('#editarPagoModal').modal('hide');
                    this.$notify({
                        title: 'Satisfactorio',
                        message: 'Pago actualizado correctamente.',
                        type: 'success'
                    });
                    this.loadUsers();
                    console.log(data);
                }).catch((err) => {
                    this.loading = false;
                    this.$notify({
                        title: 'Error!',
                        message: 'Error de Servidor.',
                        type: 'error'
                    });
                    console.log(err);
                });
            },

            openModalN(){
                this.formPago.reset();
                this.cuotas = [];
                this.pagos_reg = [];
                $('#regPago').modal('show');
            },
            verPago(id){
                //this.regpago=[],
                axios.get("/api/mostrarpagos",{params:{id:id}} ).then(({ data }) => (this.regpagos = data.pago,
                this.pagado = data.pagado,
                this.pendiente = data.pendiente,
                this.cuotacan = data.cuotacan,
                this.cuotapen = data.cuotapen,
                this.amorti = data.amorti,
                this.total = data.total
                
                )).catch(error => { this.error = error.response });
                $('#ModalPago').modal('show');
            },
            getCuotasAnio(){
                axios.get("api/pago",{params:{anio:this.anio}} ).then(({ data }) => (this.pagos = data)).catch(error => { this.error = error.response });
            },

            getCuotasPago(){
                if (!this.formModalPago.entidad) {
                    return;
                }
                if (!this.formModalPago.mes) {
                    return;
                }
                axios.get('/api/getcuotaspagos', {params:{entidad:this.formModalPago.entidad , mes:this.formModalPago.mes,anio:this.formModalPago.anio}})
                .then(({data}) => {
                    console.log(data);
                    this.formModalPago.detalle = data;
                }).catch((err) => {
                    console.log(err);
                });
            },
            openModalPago(){
                this.formModalPago.reset();
                $('#pagosModal').modal('show');
            },
            //AMORTIZACION
            resetCalculo(){
                this.nuevoCrono.cuotas = [];
            },
            openModalAmortizacion(){
                this.cuotaspendientes = [];
                this.nuevoCrono.cuotas =[];
                // this.tipoAmortiza='p';
                this.idcredito='';
                this.montoAmortizar='';
                // console.log('entrando a la amortizacion');
                axios.get('/api/getclientesdeudores')
                .then(({data}) => {
                    console.log(data);
                    this.clientes = data;
                }).catch((err) => {
                    console.log(err);
                });
                $('#amortizacionModal').modal('show');
            },

            getCuotasPendientes(){
                this.resetCalculo();
                this.loadAmoGetDeuda = true;

                axios.get('/api/getcuotasforcredito/'+this.idcredito)
                .then(({data}) => {
                    console.log(data);
                    this.loadAmoGetDeuda = false;
                    this.cuotaspendientes = data;
                }).catch((err) => {
                    this.loadAmoGetDeuda = false;
                    console.log(err);
                });
            },

            amortizar(){
                this.montoAmortizar;
                this.idcredito;
                this.loadAmoGetCalc = true;
                axios.post('/api/amortizar',{ cantcuotas:this.cantCuotas, montoAmortizar : this.montoAmortizar, 
                idcredito:this.idcredito, cuotaspendientes:this.cuotaspendientes, tipo_amortizacion:this.tipoAmortiza,
                fecha_desembolso:this.fecha_desembolso, fecha_primera_cuota:this.fecha_primera_cuota})
                .then(({data}) => {
                    console.log(data);
                    this.loadAmoGetCalc = false;
                    this.nuevoCrono.cuotas=data.calc.cuotas;
                    this.dataAmortizacion= data;
                }).catch((err) => {
                    this.loadAmoGetCalc = false;
                    console.log(err);
                });
                
            },

            guardarAmortizacion(cuotas){
                axios.post('/api/guardaramortizacion',{cuotas,cuotaspendientes : this.cuotaspendientes, justificacion:this.justificacion, 
                fecha_pago: this.fecha_pago, data:this.dataAmortizacion,  idcredito:this.idcredito, idcrono:this.dataAmortizacion.idcrono, montoamortizar : this.montoAmortizar})
                .then(({data}) => {
                    console.log(data);
                     $('#amortizacionModal').modal('hide');
                    Swal.fire({
                        icon:'success',
                        title:'Satisfactorio',
                        text: 'Se generaron la amortizacion correctamente',
                        showCancelButton: false,
                        confirmButtonText: `Aceptar`
                    });
                    this.cuotaspendientes = [];
                    this.nuevoCrono.cuotas =[];
                    this.idcredito='';
                    this.montoAmortizar='';
                }).catch((err) => {
                    console.log(err);
                });
            },        //END AMORTIZACION
            quitClientModalPago(id){
                this.formModalPago.detalle = this.formModalPago.detalle.filter(i=> i.id != id);
            },

            modalVerifi(){
                Swal.fire({
                        icon:'warning',
                        title:'Confirmación',
                        text: '¿Está seguro de generar los pagos?',
                        showCancelButton: true,
                        confirmButtonText: `Si, estoy seguro`,
                        cancelButtonText: 'No, cancelar!',
                }).then((result) => {
                    console.log(result);
                    if (result.value == true) {
                        console.log('confirmado');
                        this.registrarPagoModal();
                    } else {
                        // console.log('esta cancelado y evitado');
                    }
                })
            },
            registrarPagoModal(){
                let MontoMayorACuota = this.formModalPago.detalle.some(i=> i.monto > i.cuota || !i.fecha_pago);
                if (MontoMayorACuota != true) {
                    console.log(MontoMayorACuota);
                    this.formModalPago.post('/api/createpagosmasivos')
                    .then(({data}) => {
                        console.log(data);
                        $('#pagosModal').modal('hide');
                        this.formModalPago.reset();
                        Swal.fire({
                            icon:'success',
                            title:'Satisfactorio',
                            text: 'Se generaron los pagos correctamente',
                            showCancelButton: false,
                            confirmButtonText: `Aceptar`
                        })
                    }).catch((err) => {
                        console.log(err);
                    });
                }
            },

            nuevopago(){
                this.editmode = false;
                this.formPago.reset();
                //this.cuotames='';

                $('#regPago').modal('show');
            },

            editPago(row){
                console.log(row);
            },
            buscarcredito(id){
                console.log(id);
                let credi =  this.creditospen.filter(function(creditospen) {
                    return creditospen.id == id;
                });
                axios.post('api/detallecrono',{idcredito: id} ).then(({ data }) => {this.cuotas = data, console.log(data);}).catch(error => { this.error = error.response });

                axios.get("/api/mostrarpagos",{params:{id:id}} ).then(({data}) => {
                    console.log(data);
                    this.pagos_reg = data.pago;
                }).catch((err) => {
                    console.log(err);
                });
                this.formPago.idcredito = credi[0].id;
                this.formPago.empresa = credi[0].empresa;
                this.formPago.idcliente = credi[0].idcliente;
                this.formPago.dni = credi[0].dni;
                //console.log(digitalGoods);

            },          

            mostrarcuota(id){
                let cou =  this.cuotas.filter(function(cuotas) {
                    return cuotas.cuotanro == id;
                });
                
                if (cou[0].saldo_ade == null || cou[0].saldo_ade === 0) {
                    this.formPago.monto = cou[0].cuota;
                    this.formPago.monto_desc = cou[0].cuota;
                    this.formPago.cuotames = cou[0].cuota;
                    console.log(cou[0].saldo_ade, 'nulo');
                }else{
                    this.formPago.monto = cou[0].saldo_ade;
                    this.formPago.monto_desc = cou[0].saldo_ade;
                    this.formPago.cuotames = cou[0].saldo_ade;
                   console.log(cou[0].saldo_ade,'nonulo');
                   
                }
               
                
                
                this.formPago.fecha_ven = cou[0].fecha_ven;
                this.formPago.idcrono = cou[0].idcrono;
                this.formPago.cap_amor = cou[0].cap_amor;
                this.formPago.interes = cou[0].interes;
                this.formPago.com_des = cou[0].com_des;
                this.formPago.seg_des = cou[0].seg_des;
            },
            actualizarmonto(tipofunction, value){
                this.data_table.forEach(element => {
                    if (element.numero == value.numero) {
                        if (tipofunction === 'monto') {
                            element.monto = parseFloat(value.monto);
                        } else {
                            console.log('esta entrando a dni');
                            element.dni = parseFloat(value.dni);
                        }
                    }
                });
            },

            eliminaregistro(id){
                let temparray = this.data_table.filter( item => item.numero != id);
                this.data_table = temparray;
            },
            setSelected(data){
                console.log('data');
                this.idEmpresaImportExcel = data;
                if (data != null) {
                    return true;
                } else {
                    return true;
                }
            },
            openModal(){
                this.getEmpresas();
                $('#nuevalista').modal('show');
            },
            importExcel($event){
                let f = $event.target.files[0];
                console.log('asdasd', f);
                if (f == undefined) {
                    console.log('es indefinded');
                } else {
                    console.log('cuando selecciona archivo');
                    let reader = new FileReader();
                    let obtenidos;
                    this.data_table = [];
                    reader.onload = (e) => {
                        let data = e.target.result;
                        data = new Uint8Array(data);
                        var workbook = XLSX.read(data, {type: "array"});
                        
                        /* DO SOMETHING WITH workbook HERE */
                        var first_sheet_name = workbook.SheetNames[0];
                        /* Get worksheet */
                        var worksheet = workbook.Sheets[first_sheet_name];
                        //It will prints with header and contents ex) Name, Home...
                        var json = XLSX.utils.sheet_to_json(worksheet, {
                            header: 2
                        });
                        obtenidos = json;
                    }
                    reader.readAsArrayBuffer(f);
                    // console.log(object);
    
                    reader.addEventListener("loadend", () => {
                        this.data_table = obtenidos;
                    }, false);
                }
            },
            registrarPagoLista(){
                this.data_table.forEach(item => {
                    let usuarioLista = {
                        dni:item.dni,
                        monto:item.monto,
                        iduser:1,
                        idempresa:this.idEmpresaImportExcel,
                    }
                    axios.post('api/pagarlista', usuarioLista)
                    .then(
                        data=>console.log(data)
                    ).catch ( erro => console.log(erro))
                });
            },
            getEmpresas(){
                // console.log('se monto el function');
                axios.get('api/empresas')
                .then(
                    data=>{
                        // datarecolec = data.data;
                        this.empresas = data.data;
                        
                    }
                ).catch(
                    err => {
                        console.log(err);
                    }
                )
            },
            creaPago(){
                Swal.fire({
                        icon:'warning',
                        title:'Confirmación',
                        text: '¿Está seguro de Realizar el Pago?',
                        showCancelButton: true,
                        confirmButtonText: `Si, estoy seguro`,
                        cancelButtonText: 'No, cancelar!',
                }).then((result) => {
                   
                    if (result.value == true) {
                        let date = new Date(this.formPago.fecha_pago);
                        let anio = date.getFullYear() 
                        if (anio < 2021) {
                            return;
                        };
                        this.formPago.post('api/pago')
                        .then((data)=>{
                                console.log('data');
                                this.formPago.reset();
                                this.$notify({
                                    title: 'Satisfactorio',
                                    message: 'Pago creado satisfactoriamente.',
                                    type: 'success'
                                });
                                $('#regPago').modal('hide');
                                this.loadUsers();
                            }
                        ).catch( err=>{
                                console.log(err);
                                }
                            )
                    } else {
                        // console.log('esta cancelado y evitado');
                    }
                })





                
                    
            },
            imprimirVoucher(lncliente, ltcuota, ltpagado) {
                let datavoucher = {
                    ncliente:lncliente,
                    tcuota:ltcuota,
                    tpagado:ltpagado
                };
                console.log(this.formPago);
                this.formPago.post('api/voucherronald',datavoucher)
                .then(
                    data=>{
                        console.log(data);
                    }
                ) .catch(
                    err => {
                        console.log(err);
                    }
                )
            },

            buscarPorDni(){
                axios.get('api/burcarpordni/'+ this.inputdni)
                .then(
                    data=>{
                        this.cliente = data.data;
                        this.formPago.idempresa = this.cliente.empresa.id;
                        this.formPago.idcliente = this.cliente.id;
                        this.formPago.ncliente = data.data.nombres+ ' ' + data.data.paterno +' '+ data.data.materno;
                        this.formPago.ncliente = data.data.nombres+ ' ' + data.data.paterno +' '+ data.data.materno;
                        this.nempresa = this.cliente.empresa.razonsocial;
                        this.cuotas = this.cliente = data.data.cuotas;
                    }
                )
                .catch(
                    err =>{
                        console.log(err);
                    }
                )
            },

            exportar(){
                axios.get('api/exportarexcel').then(
                    data=>console.log(data)
                ).catch( err=>console.log(err))
            },
            loadUsers(){      
                this.loading = true; 
                axios.get("api/periodos").then(({ data }) => (this.periodos = data)).catch(error => { this.error = error.response }); 
                axios.get("api/empresas").then(({ data }) => (this.empresas = data)).catch(error => { 
                //   this.error = error.response;
                  console.log(error);
                  });                
                axios.get("api/pago",{params:{anio:this.anio}}).then(({ data }) => (this.pagos = data,this.loading=false)).catch(error => { this.error = error.response });
                axios.get("api/creditospendientes").then(({ data }) => (this.creditospen = data)).catch(error => { this.error = error.response });
               
            },
            getRoles(){
                axios.get("/api/roleuser").then(({ data }) => {
                    this.roles=data;
                    console.log(data);
                }).catch();
            }
        },

        computed:{

            calcTotalPa(){
                let t = 0;
                if (this.formModalPago.detalle.length > 0) {
                    this.formModalPago.detalle.map(i=>{
                        t += parseFloat(i.montopagar)
                    })
                }

                return t.toFixed(2);
            },
            totalCuota(){
                if (this.formPago.idcuota != '') {
                    let lacuota = this.cuotas.filter(item => item.id == this.formPago.idcuota);
                    this.formPago.tcuota = lacuota[0].cuota;
                    this.formPago.cuota_restante = lacuota[0].cuota_restante;
                    this.formPago.monto_pagado = lacuota[0].monto_pagado;
                    console.log(this.formPago);
                    return lacuota[0].cuota;
                }

            },
            calsaldo(){
                let saldo = this.formPago.cuotames - this.formPago.monto;
                this.formPago.saldo=saldo;
                return saldo;


            }
        },
        created() {
            document.title = 'Pagos - SBH';
            this.loadUsers();
            this.getRoles();
            Fire.$on('AfterCreate',() => {
                this.loadUsers();
            });
        //    setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>
