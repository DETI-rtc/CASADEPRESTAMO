<template>

    <div class="">
         
        <!-- <div class="justify-content-center"> -->
        <!-- <loading :active.sync="loading" 
                                :can-cancel = "false" 
                                :is-full-page ="true"
        >                      
                                </loading> -->
        <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title" style="font-size: 24px !important;"> <i class="fas fa-users mr-2" ></i> RELACION DE PAGOS </h3>
                            <div class="card-tools">
                                <!-- <button class="btn btn-success"  @click="openModalPago"> Registrar Pago <i class="fas fa-plus"></i></button> -->
                                 <!-- <button class="btn btn-warning"  @click="imprimirVoucher()"> Cancelar Pago <i class="fas fa-plus"></i></button> -->
                            </div>
                    </div>

                    <div class="card-body" ref="contento" >
                            <v-client-table :data="pagos" :columns="columna" :options="options"  id="#pagos">
                                    <div slot="fecha_pago" slot-scope="{row}" >
                                      {{row.fecha_pago | myDate }}
                                    </div>
                                    <div slot="cliente" slot-scope="{row}" >
                                      {{row.persona.paterno+' '+row.persona.materno }}
                                    </div>
                                    <div slot="monto" slot-scope="{row}">
                                      {{row.monto | currency  }}
                                     </div>
                                    <div slot="estado" slot-scope="{row}" >
                                        <div v-if="row.situacion=='P'">
                                            <span class="badge badge-pill badge-warning">Pendiente</span>
                                        </div>
                                        <div v-else-if="row.situacion == 'A'">
                                            <span class="badge badge-pill badge-success">Aprobado</span>
                                        </div>
                                        <div v-else>
                                            <span  class="badge badge-pill badge-danger">Rechazado</span>
                                        </div>
                                    </div>
                                    <div slot="acciones" slot-scope="{ row }">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success btn-sm"  @click="aceptarPago(row)" >Aceptar</button>
                                            <button type="button" class="btn btn-danger btn-sm" @click="rechazarPago(row)">Rechazar</button>
                                        </div>
                                    </div>
                                </v-client-table>
                    </div>
                <!-- </div> -->
        </div>
       
    
        <!---///nuevo-->      

        <!-- modal de agregar lista -->
        <div class="modal bd-example-modal-xl " id="regPago" tabindex="-1" role="dialog" >
                <div class="modal-dialog modal-md" role="document"  >
                    <div class="modal-content" :class="!editmode ? 'card card-outline card-primary' : 'card card-outline card-success'">
                        <div class="card-header">
                        <h5 class="card-title" v-show="!editmode" id="addNewLabel">Pagar Cuota</h5>
                        <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar Cuota</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-bs-dismiss="modal"><i
                                    class="fas fa-times"></i></button>
                        </div>

                    </div>
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
                                            <!-- <div class="input-group-prepend">
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
                                        <label class="text.bold text-lg mt-2 mb-0 float-right" >{{cuotames | currency}} </label>
                                    </div>
                                </div>

                                <div class="form-group row  m-0">
                                    <label class="col-sm-6 col-form-label"> Saldo de Cuota</label>
                                    <div class="col-sm-6">
                                        <vue-autonumeric :options="''" v-model="formPago.saldo" :value="calsaldo" class="form-control form-control-sm text-right" disabled></vue-autonumeric>
                                        
                                    </div>
                                </div>
                                <div class="form-group row  m-0">
                                    <label class="col-sm-6 col-form-label"> Nro de C/P</label>
                                    <div class="col-sm-6">
                                        <vue-autonumeric :options="'integer'" v-model="formPago.com_pago"  class="form-control form-control-sm text-right" ></vue-autonumeric>
                                        
                                    </div>
                                </div>

                                <div class="form-group row  m-0">
                                    <label class="col-sm-6 col-form-label" >Monto a Pagar S/.<span style="color: red; font-weight: bold;">*</span></label>
                                    <div class="col-sm-6">
                                        <vue-autonumeric :options="''" v-model="formPago.monto" class="form-control form-control-sm text-right text-lg text-bold"  ></vue-autonumeric>
                                         
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" >Registrar pagos</button>
                        </div>
                    </form>


                        
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

                        <div class="form-group col-md-4">
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

                        <div class="form-group col-md-4">
                            <label>Fecha </label>
                            <br>
                            <input type="date" class="form-control" v-model="formModalPago.fecha_pago">
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nro Crédito</th>
                                <th>Cliente</th>
                                <th>Nro Cuota</th>
                                <th>Monto Cuota</th>
                                <th>Monto Pendiente</th>
                                <th>Monto a Pagar (Desc)</th>
                                <th>Monto a Pagar (Efectivo)</th>
                                <th>Monto Total a Pagar</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(i,key) in formModalPago.detalle" :key="key">
                                <td> C000{{ i.numero }} </td>
                                <td class="text-left"> {{ i.cliente }} </td>
                                <td > {{ i.cuotanro }} </td>
                                <td class="text-right"> {{ i.cuota }} </td>
                                <td class="text-right"> {{ i.pendiente }} </td>
                                <td>  <vue-autonumeric :options="{decimalPlaces:2}" v-model="i.montopagar" class="form-control form-control-sm text-right"  ></vue-autonumeric> </td>
                                <td>  <vue-autonumeric :options="{decimalPlaces:2}" v-model="i.montopagarefec" class="form-control form-control-sm text-right"  ></vue-autonumeric> </td>
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
        data(){
            return {
                editmode: false,
                loading:false,
                creditospen:[],
                roles:[],
                data_table:[],
                inputdni:'',
                cliente :'',
                nempresa:'-',
                empresas : [],
                cuotas:[],
                cuotames:'',
                pagos:[],
                monto_an_pagar:'',
                idEmpresaImportExcel:'',
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
                formPago : new Form ({
                    idcredito:'',
                    dni:'',
                    empresa:'',
                    idcliente:'',
                    fecha_pago:moment().format('YYYY-MM-DD'),
                    fecha_ven:'',
                    monto:'',
                    cuotanro:'',
                    saldo:0,
                    idcrono:'',
                    com_pago:0,
                    cap_amor:0,
                    interes:0,
                    com_des:0,
                    seg_des:0,
                    
                }),
                
                formFile: new Form({
                    excel:'',
                }),
                columna:['fecha_pago','dni','clientes','nro_cuota','numero','monto','mora','estado', 'acciones'],  
                options: {
                          headings: {
                               fecha_pago:'Fecha de Pago', clientes:'Cliente',nro_cuota:'Nro de Cuota',numero:'Credito',monto:'Cuota',mora:'Mora',estado:'Estado',
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm',
                          filterable: ['full_name'],
                         
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
                    mes:'',
                    fecha_pago:'',
                    detalle:[]
                })
            }
        },

        components:{
            pdf
        },
        mounted() {
            // console.log('Component mounted.')
            
        },

        methods:{

            aceptarPago(row){
                row.aceptar = true;
                axios.post('/api/validarpagos', row)
                .then(({data}) => {
                    console.log(data);
                }).catch((err) => {
                    console.log(err);
                });
            },

            rechazarPago(row){
                row.aceptar = false;
                axios.post('/api/validarpagos', row)
                .then(({data}) => {
                    console.log(data);
                }).catch((err) => {
                    console.log(err);
                });
            },


            getCuotasPago(){
                if (!this.formModalPago.entidad) {
                    return;
                }
                if (!this.formModalPago.mes) {
                    return;
                }
                axios.get('/api/getcuotaspagos', {params:{entidad:this.formModalPago.entidad , mes:this.formModalPago.mes}})
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

            quitClientModalPago(id){
                // console.log(id);
                this.formModalPago.detalle = this.formModalPago.detalle.filter(i=> i.id != id);
                // console.log(this.formModalPago.detalle);
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
                let MontoMayorACuota = this.formModalPago.detalle.some(i=> i.monto > i.cuota);
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
                this.cuotames='';

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
                axios.post('/api/detallecrono',{idcredito: id} ).then(({ data }) => (this.cuotas = data)).catch(error => { this.error = error.response });
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
                this.cuotames = cou[0].cuota;
                this.formPago.monto = cou[0].cuota;
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
                    axios.post('/api/pagarlista', usuarioLista)
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
                
                this.formPago.post('/api/pago').then((data)=>{
                    console.log('data');
                    Fire.$emit('AfterCreate');
                    $('#regPago').modal('hide');
                }).catch( err=>{
                                console.log(err);
                            }
                        )

                    
            },

            imprimirVoucher(lncliente, ltcuota, ltpagado) {
                let datavoucher = {
                    ncliente:lncliente,
                    tcuota:ltcuota,
                    tpagado:ltpagado
                };
                console.log(this.formPago);
                this.formPago.post('/api/voucherronald',datavoucher)
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
                axios.get('/api/exportarexcel').then(
                    data=>console.log(data)
                ).catch( err=>console.log(err))
            },
            loadUsers(){        
                axios.get("/api/empresas").then(({ data }) => (this.empresas = data)).catch(error => { 
                //   this.error = error.response;
                  console.log(error);
                  });                
                axios.get("/api/pago").then(({ data }) => (this.pagos = data)).catch(error => { this.error = error.response });
                // axios.get("api/creditospendientes").then(({ data }) => (this.creditospen = data)).catch(error => { this.error = error.response });
               
            },
            getRoles(){
                axios.get("/api/roleuser").then(({ data }) => {
                    this.roles=data;
                    console.log(data);
                }).catch();
            }
        },

        computed:{
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
                let saldo = this.cuotames - this.formPago.monto;
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
