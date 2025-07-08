<template>
    <div class="">
       <div class="card card-outline card-primary" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title blue" style="font-size: 24px;"> <i class="fa fa-suitcase nav-icon"></i> Empresas Con Convenio </h3>
            
                <div class="card-tools">
                          <button class="btn btn-outline-primary "  @click="nuevoempre">Agregar Empresa <i class="fas fa-user-plus fa-fw"></i></button>
                      </div>
            
        </div>
        <div class="card-body" >
             <v-client-table :data="empresa" :columns="columna" :options="options" >
                        
                       
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
                        <button type="button" class="btn btn-outline-success " @click="editEmp(row)" ><i class="fa fa-edit"> </i> </button>
                        <button type="button" class="btn btn-outline-danger " @click="deleteEmp(row.id)"><i class="fa fa-trash"></i> </button>
                        
                      </div>
                      
                          
                        </div>
            </v-client-table>
            
           
        </div>
    </div>
    <div v-else>
            <not-found></not-found>
    </div>


    <div class="modal fade bd-example-modal-xl" id="nuevaemp" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content" :class="!editmode ? 'card card-primary' : 'card card-success'">
                <div class="card-header">
                    <h5 class="card-title" v-show="!editmode" id="addNewLabel">Nueva Empresa</h5>
                    <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar Empresa</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? actuaEmp() : creaEmp()" autocomplete="off">
                <div class="modal-body">
                    <div class="row">
                                  <div class="form-group col-4">
                                      <label>RUC <span style="color: red; font-weight: bold;">*</span></label>
                                      <div class="input-group ">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                                          </div>
                                          <input v-model="form.ruc" type="text" class="form-control"  :class="{ 'is-invalid': error.ruc  }"  maxlength="11" >
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-success" v-on:click.prevent="buscarruc" ><i class="fas fa-search"></i></button>
                                          </div>                                                                     
                                      
                                        </div>
                                        
                                  </div>
                                  <div class="form-group col-8">
                                      <label>Razon Social <span style="color: red; font-weight: bold;">*</span></label>
                                      <input v-model="form.razonsocial" type="text" placeholder="Empresa" class="form-control" :class="{ 'is-invalid': error.razonsocial }">
                                      <span v-if="error.razonsocial" class="error" >{{error.razonsocial[0]}}</span>
                                  </div>
                                  <div class="form-group col-8">
                                      <label>Direccion</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-road"></i></div>
                                          </div>
                                          <input v-model="form.direccion" type="text" name="direc" class="form-control"  >
                                        
                                      </div>   
                                  </div>
                                  <div class="form-group col-4">
                                      <label>Telefono</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-phone-volume"></i></div>
                                          </div>
                                          <input v-model="form.telefono" type="text" name="telefono" placeholder="999999999" class="form-control"  maxlength="9">
                                        
                                      </div>
                                  </div>
                                 
                                  <div class="form-group col-3">
                                      <label>Contacto</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-road"></i></div>
                                          </div>
                                          <input v-model="form.contacto" type="text" name="direc" class="form-control"  >
                                        
                                      </div>   
                                  </div>
                                  <div class="form-group col-3">
                                      <label>Telefono  Contacto</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-phone-volume"></i></div>
                                          </div>
                                          <input v-model="form.telefo_cont" type="text" name="telefono" placeholder="999999999" class="form-control"  maxlength="9">
                                        
                                      </div>
                                  </div>
                                  <div class="form-group col-3">
                                      <label>Convenio</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-road"></i></div>
                                          </div>
                                          <input v-model="form.convenio" type="text" name="direc" class="form-control"  >
                                        
                                      </div>   
                                  </div>

                                  <div class="form-group col-3">
                                            <label>Sector de la Empresa</label>
                                            <div class="input-group ">
                                                    <el-select v-model="form.id_tipo" filterable placeholder="Select" width="100%" @change="cambiotipo">
                                                        <el-option  v-for="item in delista" :key="item.id" :label="item.nomdelista" :value="item.id" v-if="item.idlista == 1" name="idlista" id="idlista" ></el-option>
                                                    </el-select>
                                            </div>
                                </div>

                        
                    </div>        
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button v-show="editmode" type="button" @click="actuaEmp" class="btn btn-success">Actualizar</button>
                        <button v-show="!editmode" type="button" @click="creaEmp" class="btn btn-primary">Crear</button>
                </div>

                </form>

                </div>
            </div>
            </div>
            
        
    </div>
</template>

<script>
    import VueAutonumeric from 'vue-autonumeric'
    export default {
        data() {
            return {
                editmode: false,
                error:{},
                errors:{},
                delista:[],
                empresa : [],
                columna:['sector','ruc','razonsocial','direccion','telefono','contacto','telefo_cont','convenio','estado','acciones'],  
                options: {
                          headings: {
                                sector:'SECTOR',ruc:'RUC',razonsocial:'Entidad',direccion:'Direccion',telefono:'Telefono',contacto:'Contacto',telefo_cont:'Tel. Contacto',convenio:'Convenio',
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
                form: new Form({
                        id:'',
                        ruc:'',
                        razonsocial:'',
                        direccion:'',
                        telefono:'',
                        contacto:'',
                        telefo_cont:'',
                        convenio:'', 
                        id_tipo:'',
                        sector:''
                })
            }
        },
        methods: {
            buscarruc(){
              if(this.form.dni == ''){
                Swal.fire('RUC',
                          'Ingrese el RUC',
                          'success'
                          )
              }else{
               // axios.get("api/buscarruc",{params:{'ruc':this.form.ruc}}).then( ({data})  => {
               //     this.form.razonsocial = data.data.nombre_o_razon_social;
                 //   this.form.direccion = data.data.direccion_completa
                  //   }).catch(error => { this.errors = error.response });
                axios.get("api/buscarruc",{params:{'ruc':this.form.ruc}}).then( ({data})  => {
                    this.form.razonsocial = data.razon_social;
                    this.form.direccion = data.domicilio_fiscal
                     }).catch(error => { this.errors = error.response });
               //axios({url: 'https://api.sunat.cloud/ruc/20568033354', method: 'get', headers: {'Content-type': 'text/html; charset=UTF-8'}}).then(({ data }) => (console.log(data) )).catch(error => { this.error = error.response });
               //axios({url: 'https://api.reniec.cloud/dni/'+this.form.dni, method: 'get', headers: {'Content-type': 'text/html; charset=UTF-8'}}).then(({ data }) => (console.log(data) )).catch(error => { this.error = error.response });
               //const instance = axios.create({
                //      baseURL: 'https://api.reniec.cloud/dni',
                  //    headers: {'Access-Control-Allow-Origin': '*'}                    
                   // });
                  //  instance.get('/40679669').then(res => console.log(res));
               //   axios.get('https://api.reniec.cloud/dni/40679669').then(function (response) {                          console.log(response.data);             });
              }

            },
            
            actuaEmp(){
                console.log('Editing data');
                //this.$Progress.start();
                // axios.put('api/empresa/'+this.form.id,this.form)
                this.form.put('api/empresas/'+this.form.id)
                .then(() => {
                    // success
                    $('#nuevaemp').modal('hide');
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

            nuevoempre(){
                this.editmode = false;
                this.form.reset();
                

                $('#nuevaemp').modal('show');
            },
            editEmp(tasa){
                this.editmode = true;
                this.form.reset();
                $('#nuevaemp').modal('show');
                this.form.fill(tasa);
               
            },
            deleteEmp(id){
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
               this.form.sector = inge.nomdelista;
              //console.log(inge);
            },
            loadUsers(){
                axios.get("api/empresas")
                .then(({ data }) => {
                    this.empresa = data
                    }).catch(error => { this.error = error.response });
                axios.get("api/delista").then(({ data }) => {
                    (this.delista = data);
                    }).catch(error => { this.error = error.response });
            },
            creaEmp(){
                
                this.form.post('api/empresas')
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