<template>
    <div class="">
       <div class="card card-success" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.7rem; margin: auto;" >Permisos </h3> 
            
                <div class="card-tools">
                          <button class="btn btn-primary" @click="nuevopermi">Agregar Permiso <i class="fas fa-user-plus fa-fw"></i></button>
                      </div>
            
        </div>
        <div class="card-body" >
             <v-client-table :data="permi" :columns="columna" :options="options" >
                
                <div slot="acciones" slot-scope="{ row }">
                    <button type="button" class="btn btn-success btn-xs" @click="editPer(row)" ><i class="fa fa-edit"></i></button>
                    <button type="button" class="btn btn-danger btn-xs" @click="deletePer(row.idbanco)"><i class="fa fa-trash"></i></button>
                </div>
            </v-client-table>       
            
           
        </div>
    </div>
    <div v-else>
            <not-found></not-found>
    </div>


    <div class="modal fade" id="nuevoper" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nuevo Permiso</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar Permiso</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? actuaPer() : creaPer()">
                <div class="modal-body">
                     <div class="form-group">
                        <label> PERMISO</label>       
                        <input v-model="form.name" type="text" name="name"
                            placeholder="Name"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                 </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
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
                permi : [],
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
                    
                    
                })
            }
        },
        methods: {
            
            actuaPer(){
                this.form.put('api/permisos/'+this.form.id)
                .then(() => {
                    // success
                    $('#nuevoper').modal('hide');
                     Swal.fire(
                        'Actualizado',
                        'La Actualizacion los Datos',
                        'success'
                        )
                        
                         Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            nuevopermi(){
                this.editmode = false;
                this.form.reset();

                $('#nuevoper').modal('show');
            },
            editPer(permi){
                this.editmode = true;
                this.form.reset();
                $('#nuevoper').modal('show');
                this.form.fill(permi);   
            },
            deletePer(id){
                Swal.fire({
                    title: 'Estas Seguro?',
                    text: "Una ves Eliminado no se puede Revertir",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminarlo'
                    }).then((result) => {// Send request to the server
                         if (result.value) {
                                this.form.delete('api/permisos/'+id).then(()=>{
                                        Swal.fire(
                                        'Eliminado',
                                        'El permiso fue Eliminado',
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
                axios.get('api/permisos').then(({ data }) => (this.permi = data)).catch(error => { this.error = error.response });
            },
            creaPer(){
                this.form.post('api/permisos')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevoper').modal('hide');

                    Swal.fire(
                        'success',
                        'Permiso Creado'
                    )  
                })
                .catch(()=>{   })
            } 
        },
        created() {
            document.title = 'Permisos - SBH'
           this.loadUsers();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
           });
        }
    }
</script>
