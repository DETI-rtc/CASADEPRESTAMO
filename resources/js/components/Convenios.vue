<template>
    <div class="">
       <div class="card card-outline card-primary" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title blue" style="font-size: 24px;"> <i class="fa fa-suitcase nav-icon"></i>Relacion de Convenios Con Entidades</h3>
            
                <div class="card-tools">
                          <button class="btn btn-outline-primary "  @click="nuevoconv">Nuevo Convenio <i class="fas fa-user-plus fa-fw"></i></button>
                      </div>
            
        </div>
        <div class="card-body" >
             <v-client-table :data="convenios" :columns="columna" :options="options" >
                        
                        <div slot="fecha_ini" slot-scope="{row}" >
                                      {{row.fecha_ini | myDate}}
                        </div>
                        <div slot="fecha_fin" slot-scope="{row}" >
                                      {{row.fecha_fin | myDate }}
                        </div>
                        <div slot="contacto" slot-scope="{row}" >
                                      {{row.empresa.contacto }}
                        </div>
                        <div slot="telefo_cont" slot-scope="{row}" >
                                      {{row.empresa.telefo_cont }}
                        </div>
                       
                        <div slot="estado" slot-scope="{row}" >
                          <div v-if = "row.estado == 1">
                              <span class="badge badge-pill badge-success">Activo</span>
                          </div>
                          <div v-else>
                              <span  class="badge badge-pill badge-danger">Inactivo</span>
                          </div>
                        </div>
                       <div slot="acciones" slot-scope="{ row }">
                        <div class="btn-group">
                        <button type="button" class="btn btn-outline-success " @click="editConv(row)" ><i class="fa fa-edit"> </i> </button>
                        <button type="button" class="btn btn-outline-danger " @click="deleteConv(row.id)"><i class="fa fa-trash"></i> </button>
                        
                      </div>
                      
                          
                        </div>
            </v-client-table>
            
           
        </div>
    </div>
    <div v-else>
            <not-found></not-found>
    </div>


    <div class="modal fade bd-example-modal-xl" id="nuevoconv" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
                <div class="modal-content" :class="!editmode ? 'card card-primary' : 'card card-success'">
                <div class="card-header">
                    <h5 class="card-title" v-show="!editmode" id="addNewLabel">Nuevo Convenio</h5>
                    <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar Convenio</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? actuaConv() : creaConv()" autocomplete="off">
                <div class="modal-body">
                    <div class="row">
                                  <div class="form-group col-md-8">
                                        <label>Empres o Entidad <span style="color: red; font-weight: bold;">*</span></label>
                                        <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                            <v-select class="w-80 text-sm" :options="empresa" :reduce="persona => persona.id"  v-model="form.idempresa"  label="razonsocial" @input="cambioemp"></v-select>
                                            <div class="input-group-append">
                                                  <button class="btn btn-outline-success" v-on:click.prevent="nuevaemp" ><i class="far fa-building"></i> Nuevo</button>
                                            </div>
                                        </div>
                                  </div>
                                  <div class="form-group col-md-4">
                                      <label>Nro de Convenio <span style="color: red; font-weight: bold;">*</span></label>
                                      <input v-model="form.nro" type="text" placeholder="Nro" class="form-control text-sm" :class="{ 'is-invalid': error.razonsocial }">
                                      <span v-if="error.nro" class="error" >{{error.nro[0]}}</span>
                                  </div>
                                  <div class="form-group col-md-4">
                                      <label>Fecha de Inicio</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                          </div>
                                          <input v-model="form.fecha_ini" type="date" name="direc" class="form-control">
                                        
                                      </div>   
                                  </div>
                                  <div class="form-group col-md-4">
                                      <label>Fecha Fin</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                          </div>
                                          <input v-model="form.fecha_fin" type="date" class="form-control"  >
                                        
                                      </div>
                                  </div>
                                 

                        
                    </div>        
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button v-show="editmode" type="button" @click="actuaConv" class="btn btn-success">Actualizar</button>
                        <button v-show="!editmode" type="button" @click="creaConv" class="btn btn-primary">Crear</button>
                </div>

                </form>

                </div>
            </div>
            </div>

    <div class="modal fade bd-example-modal-xl" id="nuevaemp" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" :class="!editmode1 ? 'card card-primary' : 'card card-success'">
        <div class="card-header">
            <h5 class="card-title" v-show="!editmode1" id="addNewLabel">Nueva Empresa</h5>
            <h5 class="card-title" v-show="editmode1" id="addNewLabel">Actualizar Empresa</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form @submit.prevent="editmode1 ? actuaEmp() : creaEmp()" autocomplete="off">
        <div class="modal-body">
            
            <div class="row">
                            
                            <div class="form-group col-4">
                                <label>RUC <span style="color: red; font-weight: bold;">*</span></label>
                                <div class="input-group ">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                                    </div>
                                    <input v-model="form1.ruc" type="text" class="form-control"  :class="{ 'is-invalid': error.ruc  }"  maxlength="11" >
                                <div class="input-group-append">
                                    <button class="btn btn-outline-success" v-on:click.prevent="buscarruc" ><i class="fas fa-search"></i></button>
                                    </div>                                                                     
                                
                                </div>
                                
                            </div>
                            <loading :active.sync="isLoading" 
                            :can-cancel = "true" 
                            :is-full-page ="fullPage"
                            :loader = "dots"
                            :color= "color">                      
                            </loading>
                            <div class="form-group col-8">
                                <label>Razon Social <span style="color: red; font-weight: bold;">*</span></label>
                                <input v-model="form1.razonsocial" type="text" placeholder="Empresa" class="form-control" :class="{ 'is-invalid': error.razonsocial }">
                                <span v-if="error.razonsocial" class="error" >{{error.razonsocial[0]}}</span>
                            </div>
                            <div class="form-group col-8">
                                <label>Direccion</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-road"></i></div>
                                    </div>
                                    <input v-model="form1.direccion" type="text" name="direc" class="form-control"  >
                                
                                </div>   
                            </div>
                            <div class="form-group col-4">
                                <label>Telefono</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-phone-volume"></i></div>
                                    </div>
                                    <input v-model="form1.telefono" type="text" name="telefono" placeholder="999999999" class="form-control"  maxlength="9">
                                
                                </div>
                            </div>
                            
                            <div class="form-group col-6">
                                <label>Contacto</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-road"></i></div>
                                    </div>
                                    <input v-model="form1.contacto" type="text" name="direc" class="form-control"  >
                                
                                </div>   
                            </div>
                            <div class="form-group col-3">
                                <label>Telefono  Contacto</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-phone-volume"></i></div>
                                    </div>
                                    <input v-model="form1.telefo_cont" type="text" name="telefono" placeholder="999999999" class="form-control"  maxlength="9">
                                
                                </div>
                            </div>
                            

                            <div class="form-group col-3">
                                    <label>Sector de la Empresa</label>
                                    <div class="input-group ">
                                            <el-select v-model="form1.id_tipo" filterable placeholder="Select" width="100%" @change="cambiotipo">
                                                <el-option  v-for="item in delista" :key="item.id" :label="item.nomdelista" :value="item.id" v-if="item.idlista == 1" name="idlista" id="idlista" ></el-option>
                                            </el-select>
                                    </div>
                        </div>

                
            </div>        
        </div>
        <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button v-show="editmode1" type="button" @click="actuaEmp" class="btn btn-success">Actualizar</button>
                <button v-show="!editmode1" type="button" @click="creaEmp" class="btn btn-primary">Crear</button>
        </div>

        </form>

        </div>
    </div>
    </div>
            
        
    </div>
            
        
    </div>
</template>

<script>
    import VueAutonumeric from 'vue-autonumeric'
    import Loading from 'vue-loading-overlay'
    export default {
       components:{
        Loading
    },
        data() {
            return {
                editmode: false,
                editmode1: false,
                error:{},
                errors:{},
                convenios:[],
                empresa : [],
                delista:[],
                isLoading: false,
                fullPage: true,
                dots:'dots',
                color:'#dc3545',
                columna:['entidad','nro','fecha_ini','fecha_fin','contacto','telefo_cont','estado','acciones'],  
                options: {
                          headings: {
                                entidad:'Razon Social',nro:'Nro de Convenio',fecha_ini:'Fecha de Inicio',fecha_fin:'Fecha de Termino',contacto:'Contacto',telefo_cont:'Tel. Contacto',
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm',
                          filterable: ['nombre','observa'],
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
                form1: new Form({
                        id:'',
                        ruc:'',
                        razonsocial:'',
                        direccion:'',
                        telefono:'',
                        contacto:'',
                        telefo_cont:'',
                        id_tipo:'',
                        sector:''
                }),
                form: new Form({
                        id:'',
                        idempresa:'',
                        entidad:'',
                        fecha_ini:'',
                        fecha_fin:'',
                        nro:'',
                        
                })
            }
        },
        methods: {
            buscarruc(){
              if(this.form1.ruc == ''){
                Swal.fire('RUC',
                          'Ingrese el RUC',
                          'success'
                          )
              }else{

                this.isLoading = true;
                // let env = {
                //     token: "a2ee2e4a-2282-41cb-9b62-5bd54f05da2b-3ed83f1f-57ca-46b9-8970-b872b097269c",
                //     ruc: this.formOrden_Compra.proveedor.r_u_c
                // }
                // axios.get(`https://apiperu.dev/api/ruc/${this.formOrden_Compra.proveedor.r_u_c}`, {
                //                 headers: {
                //                 'Content-Type': 'application/json',
                //                 'Authorization': 'Bearer a8f7ad23b2b39ac73f53d4009bc7023012cecc01088d6c86125b16df1878989a',
                //                 }
                //             }
                // axios.post('https://ruc.com.pe/api/v1/consultas', env, {
                //     headers:{
                //         'Content-Type': 'application/json',
                //     }
                // })
                axios.get(`https://dniruc.apisperu.com/api/v1/ruc/${this.form1.ruc}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InJ0ZW5pY2VsYUBnbWFpbC5jb20ifQ.LgmpzMSbrlA4RDAblZRNUcE2i2Ux8_J2LOO2mko_h6U`)
                .then(({data})=>{
                    this.isLoading = false; 
                    this.form1.razonsocial = data.razonSocial;
                    this.form1.direccion = data.direccion
                    //return (data.estado);
                    }
                ).catch(err=>{
                    this.isLoading = false;
                    console.log(err);
                    //this.proveedorV = 'NO REGISTRADO'; 
                    //return 'NO REGISTRADO';
                    })
            }






               // axios.get("api/buscarruc",{params:{'ruc':this.form.ruc}}).then( ({data})  => {
               //     this.form.razonsocial = data.data.nombre_o_razon_social;
                 //   this.form.direccion = data.data.direccion_completa
                  //   }).catch(error => { this.errors = error.response });
                ///axios.get("api/buscarruc",{params:{'ruc':this.form1.ruc}}).then( ({data})  => {
                    //this.form.razonsocial = data.razon_social;
                   // this.form.direccion = data.domicilio_fiscal
                   //  }).catch(error => { this.errors = error.response });
               //axios({url: 'https://api.sunat.cloud/ruc/20568033354', method: 'get', headers: {'Content-type': 'text/html; charset=UTF-8'}}).then(({ data }) => (console.log(data) )).catch(error => { this.error = error.response });
               //axios({url: 'https://api.reniec.cloud/dni/'+this.form.dni, method: 'get', headers: {'Content-type': 'text/html; charset=UTF-8'}}).then(({ data }) => (console.log(data) )).catch(error => { this.error = error.response });
               //const instance = axios.create({
                //      baseURL: 'https://api.reniec.cloud/dni',
                  //    headers: {'Access-Control-Allow-Origin': '*'}                    
                   // });
                  //  instance.get('/40679669').then(res => console.log(res));
               //   axios.get('https://api.reniec.cloud/dni/40679669').then(function (response) {                          console.log(response.data);             });
              //}

            },
            cambioemp(id){
              var inge = this.empresa.find( nombre => nombre.id === id );
              this.form.entidad = inge.razonsocial;
            },
            actuaEmp(){

            },
            creaEmp(){
              this.form1.post('api/empresas')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevaemp').modal('hide');

                    Swal.fire(
                        'Creado',
                        'Se Creo la Empresa',
                        'success'
                        )
                    

                })
                .catch((error)=>{
                     this.error = error.response.data.errors;
                })

            },
            actuaConv(){
                console.log('Editing data');
                //this.$Progress.start();
                // axios.put('api/empresa/'+this.form.id,this.form)
                this.form.put('api/empresas/'+this.form.id)
                .then(() => {
                    // success
                    $('#nuevoconv').modal('hide');
                     Swal.fire(
                        'Actualizado',
                        'La Actualizacion los Datos',
                        'success'
                        )
                        //this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch((error) => {
                    this.error = error.response.data.errors;
                    this.$Progress.fail();
                });
             //console.log(message);
            },
            nuevoconv(){
                this.editmode = false;
                this.form.reset(); 
                $('#nuevoconv').modal('show');
            },

            nuevaemp(){
                this.editmode1 = false;
                this.form1.reset(); 
                $('#nuevaemp').modal('show');
            },
            editConv(tasa){
                this.editmode = true;
                this.form.reset();
                $('#nuevoconv').modal('show');
                this.form.fill(tasa);
               
            },
            deleteConv(id){
                Swal.fire({
                    title: 'Estas Seguro?',
                    text: "Una ves Eliminado no se puede Revertir",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminarlo'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.form.delete('api/empresas/'+id).then(()=>{
                                        Swal.fire(
                                        'Eliminado',
                                        'El empresa fue Eliminado',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    Swal.fire("Falla", "Sucedio un Error", "warning");
                                });
                         }
                    })
            },
            cambiotipo(value){
              var inge = this.delista.find( nombre => nombre.id === value );
              this.form1.sector = inge.nomdelista;
              //console.log(inge);
            },
            loadUsers(){
                axios.get("api/esinconvenio")
                .then(({ data }) => {
                    this.empresa = data
                    }).catch(error => { this.error = error.response });
                axios.get("api/convenio").then(({ data }) => {
                    (this.convenios = data);
                    }).catch(error => { this.error = error.response });
                 axios.get("api/delista").then(({ data }) => {
                    (this.delista = data);
                    }).catch(error => { this.error = error.response });
            },
            creaConv(){
                
                this.form.post('api/convenio')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevoconv').modal('hide');

                    Swal.fire(
                        'Creado',
                        'Se Creo la Empresa',
                        'success'
                        )
                    

                })
                .catch((error)=>{
                     this.error = error.response.data.errors;
                })
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
<style scoped>
.error-form{
    display: block;
        width: 100%;
    margin-top: 0.25rem;
    font-size: 80%;
    color: #dc3545;
}

</style>