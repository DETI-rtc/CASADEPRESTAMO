<template>
    <div class="">
        <!-- <div class="justify-content-center"> -->
        <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title" style="font-size: 24px !important;"> <i class="fas fa-users mr-2" ></i> DESEMBOLSOS</h3>
                            <div class="card-tools">
                                <!-- <button class="btn btn-success" @click="nuevocre"> Agregar lista <i class="fas fa-plus"></i></button> -->
                                <button class="btn btn-primary"  @click="openCancelDeudaModal"> <i class="fas fa-plus"></i> Cancelar Deuda </button>
                            </div>
                    </div>

                    <div class="card-body" ref="contento" >
                            <!-- <div class="form-group row">
                                <button class="btn btn-primary pull-right mr-2">
                                    Exportar a Excel
                                </button>
                                <button class="btn btn-danger">
                                    PDF
                                </button>
                            </div> -->

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

                            <v-client-table :data="pagosDeudas" :columns="columna" :options="options"  id="#ttrabajador" v-else>
                                <!-- <div slot="estado" slot-scope="{row}" >
                                <div v-if = "row.estado == 0">
                                    <span class="badge badge-pill badge-danger">Pendiente</span>
                                </div>
                                <div v-else>
                                    <span  class="badge badge-pill badge-success">Aprobado</span>
                                </div>
                                </div> -->
                                <div slot="situacion" slot-scope="{row}">

                                    
                                    <span class="badge badge-pill badge-secondary" v-if="row.situacion == 'R'">Registrado</span>
                                    <span class="badge badge-pill badge-info" v-if="row.situacion == 'P'">Pendiente</span>

                                </div>
                                <div slot="actions" slot-scope="{ row }">
                                    <div class="btn-group">

                                        <button type="button" class="btn btn-success btn-sm"  @click="editCancelPago(row,'view')" v-if="row.situacion == 'P'"><i class="fas fa-eye"></i></button>
                                        <button type="button" class="btn btn-warning btn-sm"  @click="editCancelPago(row, 'edit')" v-if="row.situacion == 'R'"><i class="fas fa-upload"></i> Voucher</button>
                                        <button v-if="row.situacion==1" type="button" class="btn btn-danger btn-sm" @click="deleteCre(row.idtrabajador)"><i class="fa fa-trash"></i></button>
                                        <button v-if="row.situacion==0" type="button" class="btn btn-primary btn-sm" @click="activaTra(row.idtrabajador)"><i class="fa fa-trash"></i></button>
                                    </div>

                                </div>
                            </v-client-table>
                    </div>
                <!-- </div> -->
        </div>
        <div class="modal bd-example-modal-xl" id="nuevacre" tabindex="-1" role="dialog" >
            <div class="modal-dialog modal-xl" role="document" style="max-width:90% !important;" >
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h5>Registrar nueva lista</h5>
                    </div>
                    <div class="modal-body">
                        <div class="text-right">
                            <button type="button" class="btn btn-success btn-sm">Subir Excel</button>
                            <div class="formulario-manual">
                                <form action="">
                                <div class="form-group">
                                        <div class="row">
                                            <div class="col-3">
                                                <label>DNI <span style="color: red; font-weight: bold;">*</span></label>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-group ">
                                                    <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                                                    </div>
                                                    <input type="text" class="form-control" maxlength="8" >
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-success"><i class="fas fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="form-group mb-3 row noinfe">
                                            <div class="col-3">
                                                <label>Nombre <span style="color: red; font-weight: bold;">*</span></label>
                                            </div>
                                            <div class="col-4">
                                                Aquí irá el nombre del cliente
                                            </div>
                                        </div>

                                        <div class="form-group row noinfe ">
                                            <label class="col-sm-3 col-form-label" >Entidad <span style="color: red; font-weight: bold;">*</span></label>
                                            <div class="col-sm-4">
                                                <select class="form-control" name="" id="">
                                                    <option>Entidad 01</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row noinfe mt-3">
                                            <label class="col-sm-3 col-form-label" >Monto S/.<span style="color: red; font-weight: bold;">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="number" class="form-control">
                                            </div>
                                        </div>
                                    </form>

                                    <div class="text-right">
                                        <button type="button" class="btn btn-success">Agregar a lista</button>
                                    </div>

                                    <!-- LISTA -->
                                    <v-client-table :data="creditos" :columns="columna" :options="options"  id="#ttrabajador">
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
                                            <button type="button" class="btn btn-success btn-sm"  @click="editCre(row)" ><i class="fa fa-edit"></i></button>
                                            <button v-if="row.situacion==1" type="button" class="btn btn-danger btn-sm" @click="deleteCre(row.idtrabajador)"><i class="fa fa-trash"></i></button>
                                            <button v-if="row.situacion==0" type="button" class="btn btn-primary btn-sm" @click="activaTra(row.idtrabajador)"><i class="fa fa-trash"></i></button>
                                        </div>

                                        </div>
                                    </v-client-table>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm">Subir lista</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal nueva cancelacion -->
        <div class="modal bd-example-modal-xl " id="canPago" tabindex="-1" role="dialog" >
                <div class="modal-dialog modal-md" role="document"  >
                    <!-- <div class="modal-content" :class="!editmode ? 'card card-primary' : 'card card-success'"> -->
                    <div class="modal-content" v-bind:class="classModalHeader">
                        <div class="card-header">
                        <h5 class="card-title" v-if="!editmode" id="addNewLabel">Cancelar Deuda</h5>
                        <h5 class="card-title" v-if="editmode == true &&  funcionModalCancelDeuda=='edit'" id="addNewLabel"><i class="fas fa-upload"></i> Subir Voucher</h5>
                        <h5 class="card-title" v-if="editmode == true &&  funcionModalCancelDeuda=='view'"id="addNewLabel"> <span style="font-size:15px !important;"><i class="fas fa-eye"></i> <b>{{ formCPago.cliente }}</b></span></h5>
                        
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-bs-dismiss="modal"><i
                                    class="fas fa-times"></i></button>
                        </div>

                    </div>
                         <!-- <form @submit.prevent="cancelDeuda()"> -->
                        <div class="modal-body">

                            <table class="table table-bordered">
                                <tr>
                                    <td class="w-25"><b>Fecha de Pago</b></td>
                                    <td>
                                        <!-- <input type="date"  class="form-control form-control-sm" placeholder="Fecha" v-model="formCPago.fecha_pago" :disabled="editmode"> -->
                                        <el-date-picker type="date" placeholder="Seleccione la fecha" v-model="formCPago.fecha_pago" style="width: 100%;"  value-format="yyyy-MM-dd"  format="dd/MM/yyyy" :disabled="editmode"></el-date-picker>
                                    </td>
                                </tr>
                                <tr v-if="!editmode">
                                    <td><b>Seleccione Cliente</b></td>
                                    <td>
                                        <!-- <v-select  label="pendientes" :options="creditospen" :reduce="pendientes => pendientes.id"  v-model="formCPago.idcredito"  @input="buscarcredito1"></v-select> -->
                                        <el-select v-model="formCPago.idcredito" placeholder="Seleccione cliente" class="w-100" :value="creditospen" @change="buscarcredito1">
                                            <el-option
                                                v-for="item in creditospen"
                                                :key="item.id"
                                                :label="item.pendientes"
                                                :value="item.id"
                                                >
                                            </el-option>
                                        </el-select>
                                    </td>
                                </tr>
                                <tr v-if="editmode && funcionModalCancelDeuda=='edit'">
                                    <td><b>CLIENTE</b></td>
                                    <td>{{formCPago.cliente}}</td>
                                </tr>
                                <tr>
                                    <td><b>DNI</b></td>
                                    <td>{{formCPago.dni}}</td>
                                </tr>
                                <tr>
                                    <td><b>Entidad</b></td>
                                    <td>{{formCPago.empresa}}</td>
                                </tr>
                                <tr>
                                    <td><b>Vencimiento</b></td>
                                    <td>{{formCPago.fecha_ven | myDate}}</td>
                                </tr>

                                <tr>
                                    <td><b>Cuotas faltantes</b></td>
                                    <td>{{formCPago.cuotas_fal}}</td>
                                </tr>

                                <tr>
                                    <td><b>Monto a Pagar S/.</b></td>
                                    <td>{{formCPago.monto}}</td>
                                </tr>

                                <tr v-if="editmode">
                                    <td><b>Foto Voucher</b></td>
                                    <td>
                                        
                                        <template>
                                            <input type="file" accept="image/*"  @change="uploadImage($event)" name="vaucher" v-if="funcionModalCancelDeuda=='edit'">
                                            <template v-if="formCPago.vaucher != '' &&  formCPago.vaucher != null">
                                                <img class="w-50" 
                                                    :src="getProfilePhoto()" 
                                                    alt="User profile picture">
                                            </template>
                                        </template>
                                        
                                        <!-- <el-image
                                        style="width: 100px; height: 100px"
                                        :src="url"
                                        :fit="fit"></el-image> -->
                                    </td>
                                </tr>
                                <tr v-if="editmode">
                                    <td><b>Fecha Voucher</b></td>
                                    <td>
                                        <el-date-picker type="date" placeholder="Seleccione la fecha" v-model="formCPago.fec_vaucher" value-format="yyyy-MM-dd"   format="dd/MM/yyyy" style="width: 100%;" :disabled="funcionModalCancelDeuda=='view'"></el-date-picker>
                                    </td>
                                </tr>
                            </table>

                    
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" v-if="editmode == false " @click="cancelDeuda" :disabled="disabledButton">Pagar crédito</button>
                            <button type="submit" class="btn btn-success" v-else-if="editmode == true && funcionModalCancelDeuda=='edit'" @click="subirVoucher" :disabled="disabledButton"><i class="fas fa-cloud-upload-alt"></i> Enviar</button>
                        </div>
                    </form>


                        
                    </div>
                </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import pdf from 'vue-pdf';
import XLSX from 'xlsx';
    export default {
        data(){
            return {

                classModalHeader :{
                    'card card-primary':false,
                    'card card-success':false,
                    'card card-warning':false,
                },

                //imagen
                imageUrl: '',
                funcionModalCancelDeuda:'',
                //

                //LOADING
                disabledButton:false,
                //
                fotoVoucher:'',
                editmode:false,
                loading:false,
                pagosDeudas:[],
                creditos:[],
                creditospen:[],
                form:'',
                formPago : new Form(),
                formCPago : new Form ({
                    id:'',
                    idcredito:'',
                    cliente:'',
                    dni:'',
                    empresa:'',
                    idcliente:'',
                    nombre_cliente:'',
                    fecha_pago:moment().format('YYYY-MM-DD'),
                    fecha_ven:'',
                    fec_vaucher:'',
                    monto:'',
                    cuotanro:'',
                    saldo:0,
                    idcrono:'',
                    com_pago:0,
                    saldocap:0,
                    saldoint:0,  
                    cuotas_fal:0, 
                    vaucher:''                
                    
                }),
                columna:['dni','cliente','fec_ven','celular','empresa','situacion','actions'],
                options: {
                    headings: {
                        dni: 'DNI',cliente:'nombres',fec_ven:'Fech. Ven.',celular:'Telefono',empresa:'Empresa',situacion:'Estado',actions:'Acciones'
                        },
                    perPage:25,
                    perPageValues:[25,50,100],
                    skin:'table table-sm table-hover',
                    filterAlgorithm: {
                        full_name(row, query) {
                    return (row.paterno + ' ' + row.materno).includes(query.toUpperCase());
                        }
                    },
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
            }
        },
        methods:{

            //


            buscarcredito1(idcredito){
                // this.loading = true;
                let credi = this.creditospen.filter(i=> i.id == idcredito);
                console.log(credi[0]);
                axios.post('api/detallecancela', {idcredito} )
                .then(({data}) => {
                    // this.loading = false;
                    this.formCPago.monto = data.sal_cap;
                    this.formCPago.cuotas_fal = data.cuotas_fal;
                    this.formCPago.fecha_ven = data.fecha_ven;
                })
                .catch(error => { this.loading = false; this.error = error.response });
                this.formCPago.idcredito = credi[0].id;
                this.formCPago.empresa = credi[0].empresa;
                this.formCPago.dni = credi[0].dni;
                this.formCPago.idcliente = credi[0].idcliente;
                this.formCPago.nombre_cliente = credi[0].cliente;

            },
            nuevocre(){
                $('#nuevacre').modal('show');
            },

            openCancelDeudaModal(){
                console.log('asd');
                this.classModalHeader ={
                    'card card-primary':true,   
                };
                this.editmode = false;
                this.formCPago.reset();
                 $('#canPago').modal('show');

                
                //this.editmode = false;
                //this.formPago.reset();
                //this.cuotames='';

                //$('#regPago').modal('show');
            },

            cancelDeuda(){
                console.log(this.formCPago);
                this.disabledButton = true;
                this.formCPago.post('/api/cancelacred')
                .then(({data}) => {
                    console.log(data);
                    this.disabledButton = false;
                    this.getPagosDeudas();
                    $('#canPago').modal('hide');
                }).catch((err) => {
                    this.disabledButton = false;
                    console.log(err);
                });
            },

            loadUsers(){        
                // axios.get("api/pago").then(({ data }) => (this.pagos = data)).catch(error => { this.error = error.response });
                axios.get("api/creditospendientes").then(({ data }) => (this.creditospen = data)).catch(error => { this.error = error.response });
               
            },

            getPagosDeudas(){
                this.loading = true;
                this.pagosDeudas =[];
                axios.get('/api/cancelacred')
                .then(({data}) => {
                    this.loading = false;
                    this.pagosDeudas = data;
                    console.log(data);
                }).catch((err) => {
                    this.loading = false;
                    console.log(err);
                });
            },

            editCancelPago(row, funcion){
                if (funcion == 'edit') {
                    this.classModalHeader ={
                        'card card-warning':true,   
                    }
                }
                if (funcion == 'view') {
                    this.classModalHeader ={
                        'card card-success':true,   
                    }
                }
                this.funcionModalCancelDeuda = funcion;
                this.formCPago.reset();
                this.formCPago.clear();
                this.editmode= true;
                console.log(row);
                this.formCPago.fill(row);
                $('#canPago').modal('show');
            },

            uploadImage(e){
                
                let file = e.target.files[0];
                let reader = new FileReader();

                if(file['size'] < 2111775){
                    reader.onloadend = (file) => {
                        console.log(e.target.files[0]);
                        this.fotoVoucher = e.target.files[0];
                        this.formCPago.vaucher=reader.result;
                    }
                    reader.readAsDataURL(file);

                }else{
                    
                    
                this.$message({
                    message: 'Error, la imagen no puede exceder los 2MB.',
                    type: 'error'
                });


                }
                 
            },

            subirVoucher(){
                console.log(this.fotoVoucher);

                var form = new FormData();
                form.append('vaucher', this.fotoVoucher);
                form.append('fec_vaucher', this.formCPago.fec_vaucher);
                form.append('id', this.formCPago.id);
                

                axios.post('api/subirvoucher', form,{
                    headers: {
                        'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
                    }
                })
                .then(({data}) => {
                    console.log(data);
                    this.getPagosDeudas();
                    $('#canPago').modal('hide');
                    this.$message({
                        message: 'Satisfactorio, se subió el voucher con éxito.',
                        type: 'success'
                    });
                }).catch((err) => {
                    console.log(err);
                });

                
                
            },

            updateProfile(e){
                let file = e.target.files[0];
                let reader = new FileReader();

                if(file['size'] < 2111775){
                     reader.onloadend = (file) => {
                    this.formCPago.vaucher=reader.result;
                    }
                reader.readAsDataURL(file);

                }else{
                    
                    
                this.$message({
                    message: 'Satisfactorio, se subió el voucher con éxito.',
                    type: 'success'
                });


                }
            },

            getProfilePhoto(){
                let row = this.formCPago;
                let avatar = (row.vaucher.length > 200) ? row.vaucher : "/storage/"+ row.vaucher ;
                return avatar;
            },
        },

        created() {
            document.title = 'Cancelar Pagos - SBH';
            this.getPagosDeudas();
            this.loadUsers();
            Fire.$on('AfterCreate',() => {
                this.loadUsers();
            });
        //    setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>

<style scoped>
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
</style>
