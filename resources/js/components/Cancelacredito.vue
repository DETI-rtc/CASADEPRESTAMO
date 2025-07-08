<template>

    <div class="">
         
        <!-- <div class="justify-content-center"> -->
        <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title" style="font-size: 24px !important;"> <i class="fas fa-users mr-2" ></i> RELACION DE CREDITOS CANCELADOS </h3>
                            <div class="card-tools">
                                <button class="btn btn-success"  @click="nuevopago"> Registrar Pago <i class="fas fa-plus"></i></button>
                                <!--<button class="btn btn-warning"  @click="cancelpago"> Cancelar Pago <i class="fas fa-plus"></i></button>-->
                            </div>
                    </div>

                    <div class="card-body" ref="contento" >
                            <v-client-table :data="pagos" :columns="columna" :options="options"  id="#pagos">
                                    <div slot="fecha_reg" slot-scope="{row}" >
                                      {{row.fecha_reg | myDate }}
                                    </div>
                                    <div slot="fec_ven" slot-scope="{row}" >
                                      {{row.fec_ven | myDate }}
                                    </div>
                                    
                                    
                                    <div slot="monto_can" slot-scope="{row}">
                                      {{row.monto_can | currency  }}
                                     </div>
                                     <div slot="int_can" slot-scope="{row}">
                                      {{row.int_can | currency  }}
                                     </div>
                                     <div slot="total_can" slot-scope="{row}">
                                      {{row.total_can | currency  }}
                                     </div>
                                    <div slot="estado" slot-scope="{row}" >
                                        <div v-if = "row.estado == 0">
                                            <span class="badge badge-pill badge-danger">Pendiente</span>
                                        </div>
                                        <div v-else>
                                            <span  class="badge badge-pill badge-success">Aprobado</span>
                                        </div>
                                    </div>
                                    <div slot="actions" slot-scope="{ row }">
                                    <div class="btn-group">
                                       
                                        <button type="button" class="btn btn-success btn-sm"  @click="editCance(row)" ><i class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-warning btn-sm"  @click="regPago(row)" ><i class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" @click="delCance(row.id)"><i class="fa fa-trash"></i></button>
                                        
                                    </div>

                                    </div>
                                </v-client-table>
                    </div>
                <!-- </div> -->
        </div>
       
    
        <!---///nuevo-->      

        <!-- modal de agregar lista -->
        <div class="modal bd-example-modal-xl " id="regPago" tabindex="-1" role="dialog" >
                <div class="modal-dialog " :class="!editmode ? 'modal-md' : 'modal-lg'" role="document"  >
                    <div class="modal-content" :class="!editmode ? 'card card-outline card-primary' : 'card card-outline card-success'">
                        <div class="card-header">
                            <h5 class="card-title" v-show="!editmode" id="addNewLabel">Cancelar Credito</h5>
                            <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar </h5>
                                <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                                </div>
                        </div>
                         <form @submit.prevent="editmode ? ActuaPago() : creaPago()">
                        <div class="modal-body">

                            <div class="row">
                                <div :class="!editmode ? 'col-md-12' : 'col-md-6'" >
                                        <div class="form-group m-0 row " v-show ="!editmode">
                                            <div class="col-7">
                                                <label >Fecha de Registro</label>
                                            </div>
                                            <div class="col-5">
                                              <input type="date"  class="form-control form-control-sm" placeholder="Fecha" v-model="formCancelar.fecha_reg"> 
                                            </div>
                                        </div>
                                        <div class="form-group m-0 row " v-show ="editmode" >
                                            <div class="col-7">
                                                <label >Fecha de Vencimiento</label>
                                            </div>
                                            <div class="col-5">
                                              <input type="date"  class="form-control form-control-sm" placeholder="Fecha" v-model="formCancelar.fec_ven"> 
                                            </div>
                                        </div>
                                         <div class="form-group m-0 row " v-show ="editmode" >
                                            <div class="col-7">
                                                <label >Fecha de Pago del Vaucher</label>
                                            </div>
                                            <div class="col-5">
                                              <input type="date"  class="form-control form-control-sm" placeholder="Fecha" v-model="formCancelar.fec_vaucher"> 
                                            </div>
                                        </div>
                                        <div class="form-group col-12" v-show="!editmode">
                                            <label> Seleccione Cliente</label>
                                            <v-select  label="pendientes" :options="creditospen" :reduce="pendientes => pendientes.id"  v-model="formCancelar.idcredito"  @input="buscarcredito"></v-select>
                                        </div>
                                        <div class="form-group row  m-0" v-show="editmode" >
                                             <label class="col-sm-3 col-form-label" >Cliente <span style="color: red; font-weight: bold;">*</span></label>
                                               <div class="col-sm-9">
                                                    <label class="mt-2 mb-0">{{formCancelar.cliente}}</label>
                                                </div>
                                        </div>
                                        <div class="form-group row m-0 " >
                                              <label class="col-sm-3 col-form-label">DNI <span style="color: red; font-weight: bold;">*</span></label>
                                              <div class="col-md-9">
                                                     <label class="mt-2 mb-0">{{formCancelar.dni}}</label>
                                              </div>
                                        </div>
                                        <div class="form-group row  m-0" v-show="!editmode">
                                             <label class="col-sm-3 col-form-label" >Entidad <span style="color: red; font-weight: bold;">*</span></label>
                                               <div class="col-sm-9">
                                                    <label class="mt-2 mb-0">{{formCancelar.empresa}}</label>
                                                </div>
                                        </div>
                                        <div class="form-group row  m-0">
                                              <label class="col-sm-6 col-form-label"> Monto a Cancelar <span style="color: red; font-weight: bold;">*</span></label>
                                                <div class="col-sm-6">
                                                    <label class="text.bold text-lg mt-2 mb-0 float-right" >{{formCancelar.monto_can | currency}} </label>
                                                </div>
                                        </div>
                                        
                                        <div class="form-group row  m-0">
                                              <label class="col-sm-6 col-form-label"> Interes a Cancelar</label>
                                              <div class="col-sm-6">
                                                    <vue-autonumeric :options="''" v-model="formCancelar.int_can" class="form-control form-control-sm text-right" ></vue-autonumeric>
                                              </div>
                                        </div>                           
                                        <div class="form-group row  m-0">
                                                <label class="col-sm-6 col-form-label" >Total a Cancelar<span style="color: red; font-weight: bold;">*</span></label>
                                                <div class="col-sm-6">
                                                    <vue-autonumeric :options="''" v-model="formCancelar.total_can" :value="caltotal" class="form-control form-control-sm text-right text-lg text-bold" disabled ></vue-autonumeric>
                                                </div>
                                        </div>
                                         

                                </div>
                                <div class="col-md-6" v-show="editmode" >
                                        <div >
                                             <img class="img_vaucher" :src="getProfilePhoto()" alt="User profile picture">
                                        </div>
                                       
                                         <div class="form-group col-md-12">
                                                  <label for="avatar" class=" control-label">Vaucher</label>
                                                  <div class="col-md-12">
                                                      <input type="file" @change="updateProfile" id="vaucher" class="form-input">
                                                  </div>
                                        </div>
                                </div>


                            </div>




                            
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" >Registrar Cancelar</button>
                        </div>
                    </form>


                        
                    </div>
                </div>
        </div>
        

        
    </div>
</template>

<script>
import pdf from 'vue-pdf';
import XLSX from 'xlsx';
import moment from 'moment';
//import Vuetable from 'vuetable-2'

    export default {

        data(){
            return {
                 editmode: false,
                 creditospen:[],
                data_table:[],
                inputdni:'',
                cliente :'',
                nempresa:'-',
                empresas : [],
                cuotacan:{},
                cuotames:'',
                pagos:[],
                monto_an_pagar:'',
                idEmpresaImportExcel:'',
                formCancelar : new Form ({
                    id:'',
                    idcredito:'',
                    dni:'',
                    empresa:'',
                    idcliente:'',
                    cliente:'',
                    fecha_reg:moment().format('YYYY-MM-DD'),
                    fec_ven:'',
                    monto_can:'',
                    int_can:0,
                    total_can:0,
                    cuotas_pen_can:0,
                    cuotas_can:0,
                    monto_cuo_can:0,
                    idcrono:0,
                    fec_vaucher:'',
                    vaucher:''
                    
                }),
                
                formFile: new Form({
                    excel:'',
                }),
                columna:['dni','cliente','idcredito','monto_can','int_can','total_can','fecha_reg','fec_ven','estado','actions'],  
                options: {
                          headings: {
                               fecha_reg:'Fecha de Registro', cliente:'Cliente',dni:'Nro de DNI',idcredito:'Nro de Credito',monto_can:'Saldo Cancelado',int_can:'Interes',total_can:'Monto Cancelado',fecha_reg:'Fecha de Registro',fec_ven:'Fecha de Vencimiento',estado:'Estado',
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm',
                          filterable: ['cliente','dni','idcredito'],
                         
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
                }                
                
            }
        },

        components:{
            pdf
        },
        mounted() {
            // console.log('Component mounted.')
            
        },

        methods:{
            // progress(event,progress,stepValue){
            //     console.log(stepValue);
            // },
             getProfilePhoto(){
                let vaucher = (this.formCancelar.vaucher.length > 200) ? this.formCancelar.vaucher : "/storage/"+ this.formCancelar.vaucher ;
                return vaucher;
                 },
             editCance(row){

                this.editmode = true;
                this.formCancelar.reset();
                this.formCancelar.fill(row);
                $("#vaucher").val(null);
                this.getProfilePhoto();
                $('#regPago').modal('show');
               
                //this.editmode = false;
                //this.formCancelar.reset();
                //this.cuotames='';

                //$('#regPago').modal('show');
            },
            nuevopago(){
                this.editmode = false;
                this.formCancelar.reset();
                this.cuotames='';

                $('#regPago').modal('show');
            },
            buscarcredito(id){
                console.log(id);
                let credi =  this.creditospen.filter(function(creditospen) {
                    return creditospen.id == id;
                });
                axios.post('api/detallecancela',{idcredito: id} ).then(({ data }) => {
                    this.formCancelar.monto_can = data.sal_cap;
                    this.formCancelar.cuotas_pen_can = data.cuotas_fal;
                    this.formCancelar.idcredito = credi[0].id;
                    this.formCancelar.empresa = credi[0].empresa;
                    this.formCancelar.idcliente = credi[0].idcliente;
                    this.formCancelar.cliente = credi[0].cliente;
                    this.formCancelar.dni = credi[0].dni;
                    this.formCancelar.idcrono = data.idcrono;                    
                }).catch(error => { this.error = error.response });
                

                
                
                //console.log(digitalGoods);

            },
            
            mostrarcuota(id){
                let cou =  this.cuotas.filter(function(cuotas) {
                    return cuotas.cuotanro == id;
                });
                this.cuotames = cou[0].cuota;
                this.formCancelar.monto = cou[0].cuota;
                this.formCancelar.fecha_ven = cou[0].fecha_ven;
                this.formCancelar.idcrono = cou[0].idcrono;
                this.formCancelar.cap_amor = cou[0].cap_amor;
                this.formCancelar.interes = cou[0].interes;
                this.formCancelar.com_des = cou[0].com_des;
                this.formCancelar.seg_des = cou[0].seg_des;
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
            updateProfile(e){
                let file = e.target.files[0];
                let reader = new FileReader();

                if(file['size'] < 2111775){
                     reader.onloadend = (file) => {
                    this.formCancelar.vaucher = reader.result;
                    }
                reader.readAsDataURL(file);

                }else{
                    Swal.Fire({
                        type:'error',
                        title:'Lo siento',
                        text:'El archivo exede el tamaño',
                    })


                }

               
                
            },

            creaPago(){
                
                this.formCancelar.post('api/cancelacred').then((data)=>{
                    //console.log('data');
                    Fire.$emit('AfterCreate');
                    $('#regPago').modal('hide');
                }).catch( err=>{
                                console.log(err);
                            }
                        )

                    
            },
            ActuaPago(){
                this.formCancelar.put('api/cancelacred/' + this.formCancelar.id)
                    .then(() => {
                        // success
                        $('#regPago').modal('hide');
                        Swal.fire(
                            'Actualizado',
                            'Cancelacion actualizado',
                            'success'
                        )
                        this.loadUsers();
                    })
                    .catch(() => {
                        Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                    });
            },

            imprimirVoucher(lncliente, ltcuota, ltpagado) {
                let datavoucher = {
                    ncliente:lncliente,
                    tcuota:ltcuota,
                    tpagado:ltpagado
                };
                console.log(this.formCancelar);
                this.formCancelar.post('api/imprimirvoucher',datavoucher)
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
                        this.formCancelar.idempresa = this.cliente.empresa.id;
                        this.formCancelar.idcliente = this.cliente.id;
                        this.formCancelar.ncliente = data.data.nombres+ ' ' + data.data.paterno +' '+ data.data.materno;
                        this.formCancelar.ncliente = data.data.nombres+ ' ' + data.data.paterno +' '+ data.data.materno;
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

                
                axios.get("api/cancelacred").then(({ data }) => (this.pagos = data)).catch(error => { this.error = error.response });
                axios.get("api/creditospendientes").then(({ data }) => (this.creditospen = data)).catch(error => { this.error = error.response });
               
            },
        },

        computed:{
            totalCuota(){
                if (this.formCancelar.idcuota != '') {
                    let lacuota = this.cuotas.filter(item => item.id == this.formCancelar.idcuota);
                    this.formCancelar.tcuota = lacuota[0].cuota;
                    this.formCancelar.cuota_restante = lacuota[0].cuota_restante;
                    this.formCancelar.monto_pagado = lacuota[0].monto_pagado;
                    console.log(this.formCancelar);
                    return lacuota[0].cuota;
                }

            },
            caltotal(){
                let saldo = parseFloat(this.formCancelar.int_can) + parseFloat(this.formCancelar.monto_can);
                this.formCancelar.total_can = saldo;
                return saldo;


            }
        },
        created() {
            
           this.loadUsers();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>
