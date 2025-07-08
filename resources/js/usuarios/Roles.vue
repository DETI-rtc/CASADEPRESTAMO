<template>
    <div class="">
       <div class="card card-outline card-primary" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title">ROLES </h3>
                  <div class="card-tools">
                          <button class="btn btn-primary" @click="nuevorol">Agregar Rol <i class="fas fa-user-plus fa-fw"></i></button>
                  </div>            
        </div>
        <div class="card-body table-responsive p-3">
            <v-client-table :data="roles" :columns="columna" :options="options" >
                
                <div slot="acciones" slot-scope="{ row }">
                    <button type="button" class="btn btn-success " @click="editRol(row)" ><i class="fa fa-edit"></i></button>
                    <button type="button" class="btn btn-danger" @click="deleteRol(row.id)"><i class="fa fa-trash"></i></button>
                </div>
            </v-client-table>          
        </div>
    </div>
    <div v-else>
             <not-found></not-found>
    </div>
    <div class="modal fade" id="nuevorol" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" :class="!editmode ? 'card card-primary' : 'card card-success'">
                <div class="card-header">
                    <h5 class="card-title" v-show="!editmode" id="addNewLabel">Nuevo Rol</h5>
                    <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar Rol Info</h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                    </div>
                    
                </div>
                <form @submit.prevent="editmode ? actuaRol() : creaRol()">
                <div class="modal-body">
                     <div class="form-group">
                         <label >Nombre</label>
                        <input v-model="form.name" type="text" name="name"
                            placeholder="Nombre"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                   <!-- <div class="form-group">
                        <input v-model="form.slug" type="text" name="slug"
                            placeholder="Slug"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('slug') }">
                        <has-error :form="form" field="slug"></has-error>
                    </div>

                    <div class="form-group">
                        <input v-model="form.description" type="text" name="description"
                            placeholder="Descripcion"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                        <has-error :form="form" field="description"></has-error>
                    </div>
                        <hr>
                        <h5>Permiso especial</h5>
                        <div >
                            <div class="form-group" >
                            <div class="custom-control custom-radio custom-control-inline">
                              <input class="custom-control-input" type="radio"  v-model="form.special" name="special" id="exampleRadios1" value="all-access" >
                              <label class="custom-control-label" for="exampleRadios1">
                                Acceso Total
                              </label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input class="custom-control-input" type="radio"  v-model="form.special" name="especial" id="exampleRadios2" value="no-access">
                              <label class="custom-control-label" for="exampleRadios2">
                                Ningun Acceso
                              </label>
                            </div>
                        </div>

                        </div>-->
                        
                        <hr>
                        <h5>Lista de permisos</h5>
                        <div class="custom-control custom-checkbox custom-control-inline" v-for="permisos in permi" >
                          <input class="custom-control-input" type="checkbox" :id="permisos.id" :value="permisos.id" name="permissions"  v-model="form.permissions" >
                          <label class="custom-control-label" :for="permisos.id">{{ permisos.name }}</label>
                        </div>
                 </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
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
    export default {
        data() {
            return {
                editmode: false,
                error:{},
                permi:[],
                roles : [],
                columna:['id','name','acciones'],  
                options: {
                          headings: {
                                id: 'ID',name:'Nombre',
                              },
                          perPage:15,
                          perPageValues:[25,50,100],
                          skin:'table table-striped table-hover ',
                          filterable: ['name'],
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
                    name : '',
                    guard_name : 'api',
                    permissions:[]
                    
                    

                    
                })
            }
        },
        methods: {
            
            actuaRol(){
                
                this.form.put('api/roles/'+this.form.id)
                .then(() => {
                    // success
                    Fire.$emit('AfterCreate');
                    $('#nuevorol').modal('hide');
                     Swal.fire(
                        'Actualizado',
                        'Se Actualizo el Rol',
                        'success'
                        )
                        
                         
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            nuevorol(){
                this.editmode = false;
                this.form.reset();

                $('#nuevorol').modal('show');
            },
            editRol(roles){
                this.editmode = true;
                this.form.reset();
                this.form.fill(roles);               
                this.form.permissions=[];
                roles.permissions.forEach( element => {
                  this.form.permissions.push(element.id)
                });
                $('#nuevorol').modal('show');
            },
            deleteRol(id){
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
                                this.form.delete('api/roles/'+id).then(()=>{
                                        Swal.fire(
                                        'Eliminado',
                                        'El Rol fue Eliminado',
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
                
                    axios.get("api/roles").then(({ data }) => (this.roles = data)).catch(error => { this.error = error.response });
                    axios.get('api/permi').then(({ data }) => (this.permi = data));
            },
            creaRol(){
                

                this.form.post('api/roles')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevorol').modal('hide');

                    Swal.fire(
                        'success',
                        'User Created in successfully'
                    )
                   

                })
                .catch(()=>{

                })
            }

            
        },
        created() {
            
           this.loadUsers();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
           });
            document.title = 'Roles - SBH'
        }
    }
</script>
