<template>
    <div class="">
       <div class="card card-outline card-primary" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title blue" style="font-size: 24px;"> <i class="fa fa-suitcase nav-icon"></i> Relacion Cuota Ingreso </h3>
            
                <div class="card-tools">
                          <button class="btn btn-outline-primary "  @click="nuevorci">Agregar RCI <i class="fas fa-user-plus fa-fw"></i></button>
                      </div>
            
        </div>
        <div class="card-body" >
             <v-client-table :data="relacionci" :columns="columna" :options="options" >
                        
                         <div slot="rci_max" slot-scope="{row}" >
                          {{row.rci_max+'%' }}
                        </div>
                        <div slot="estado" slot-scope="{row}" >
                          <div v-if = "row.estado == 1">
                              <span class="badge badge-pill badge-success">Activo</span>
                          </div>
                          <div v-else>
                              <span  class="badge badge-pill badge-danger">Inactivo</span>
                          </div>
                        </div>
                       <div slot="actions" slot-scope="{ row }">
                        <div class="btn-group">
                        <button type="button" class="btn btn-outline-success " @click="editRci(row)" ><i class="fa fa-edit"> </i> </button>
                        <button type="button" class="btn btn-outline-danger " @click="deleteRci(row.id)"><i class="fa fa-trash"></i> </button>
                        
                      </div>
                      
                          
                        </div>
            </v-client-table>
            
           
        </div>
    </div>
    <div v-else>
            <not-found></not-found>
    </div>


    <div class="modal fade" id="nuevarci" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" :class="!editmode ? 'card card-primary' : 'card card-success'">
                <div class="card-header">
                    <h5 class="card-title" v-show="!editmode" id="addNewLabel">Nuevo Tasa</h5>
                    <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar Tasa</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? actuaRci() : creaRci()" autocomplete="off">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-6" :class="{ 'has-error': form.errors.has('identidad') }">
                            <label for="staticEmail" class="col-form-label">Tipo Entidad</label>
                            <el-select v-model="form.identidad" filterable placeholder="Select">
                                <el-option  v-for="item in delista" :key="item.id" :label="item.nomdelista" :value="item.id" v-if="item.idlista == 1" name="identidad" id="identidad" ></el-option>
                            </el-select>
                            <has-error :form="form" field="identidad"></has-error>
                        </div>
                        <div class="form-group col-6" :class="{ 'has-error': form.errors.has('idmodalidad') }" >
                            
                            <label for="staticEmail" class="col-form-label">Tipo Modalidad</label>
                            <el-select v-model="form.idmodalidad" filterable placeholder="Select">
                                <el-option  v-for="item in delista" :key="item.id" :label="item.nomdelista" :value="item.id" v-if="item.idlista == 2" :class="{ 'is-invalid': form.errors.has('idmodalidad') }" name="idmodalidad" id="idmodalidad"></el-option>
                            </el-select>
                            
                           <div v-if="error.idmodalidad" class=" invalid-feedback" style="display:block" >{{error.idmodalidad[0]}}</div>
                            
                        </div>
                        
                        <div class="form-group row col-12">
                            <label for="statictea" class="col-sm-7 col-form-label">Tasa Compesatoria</label>
                            <div class="col-sm-5">
                                <div class="input-group mb-3">
                                    <vue-autonumeric :options="['integer',{minimumValue:'1',maximumValue:'100'}]" v-model="form.rci_max" class="form-control text-right"  :class="{ 'is-invalid': form.errors.has('rci_max') }"  name="rci_max" id="rci_max"></vue-autonumeric>
                                   
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                      <has-error :form="form" field="rci_max"></has-error>
                                </div>
                              
                            </div>
                            
                            
                                               
                                                 
                                   
                                
                           
                        </div>
                    </div>
                                 

                        
                        
                 </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button v-show="editmode" type="submit" class="btn btn-success">Actualizar</button>
                    <button v-show="!editmode" type="submit" class="btn btn-primary">Crear</button>
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
                relacionci : [],
                columna:['id','tipoEntidad','tipoModalidad','rci_max','estado','actions'],  
                options: {
                          headings: {
                                idrelacionci: 'ID',tipoModalidad:'Tipo Modaliad',tipoEntidad:'Tipo Entidad',rci_max:'rci_max',
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
                          templates: {
                                tipoEntidad(h, row){
                                return row.entidad.nomdelista;
                                },
                                tipoModalidad(h, row){
                                return row.modalidad.nomdelista;
                                },
                          },
                },
                form: new Form({
                        id:'',
                        identidad:'',
                        idmodalidad:'',
                        rci_max:'', 
                                      
                    
                })
            }
        },
        methods: {
            
            actuaRci(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/relaci/'+this.form.id)
                .then(() => {
                    // success
                    $('#nuevarci').modal('hide');
                     Swal.fire(
                        'Actualizado',
                        'La Actualizacion los Datos',
                        'success'
                        )
                        this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch((error) => {
                    this.error = error.response.data.errors;
                    this.$Progress.fail();
                });
             console.log(message);
            },

            nuevorci(){
                this.editmode = false;
                this.form.reset();
                

                $('#nuevarci').modal('show');
            },
            editRci(tasa){
                this.editmode = true;
                this.form.reset();
                $('#nuevarci').modal('show');
                this.form.fill(tasa);
               
            },
            deleteRci(id){
                Swal.fire({
                    title: 'Estas Seguro?',
                    text: "Una ves Eliminado no se puede Revertir",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminarlo'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.form.delete('api/relaci/'+id).then(()=>{
                                        Swal.fire(
                                        'Eliminado',
                                        'El relacionci fue Eliminado',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    Swal.fire("Falla", "Sucedio un Error", "warning");
                                });
                         }
                    })
            },
            loadUsers(){
        
                
                axios.get("api/relaci").then(({ data }) => (this.relacionci = data)).catch(error => { this.error = error.response });
                axios.get("api/delista").then(({ data }) => (this.delista = data)).catch(error => { this.error = error.response });
                


            },
            creaRci(){
                
                this.form.post('api/relaci')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevarci').modal('hide');

                    Swal.fire(
                        'Actualizado',
                        'La Actualizacion los Datos',
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