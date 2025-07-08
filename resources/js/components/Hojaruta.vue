<template>
    <div class="">
       <div class="card card-teal" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 24px;"> <i class="fa fa-calendar nav-icon"></i> Hojas de Ruta Diarios</h3>
            
                <div class="card-tools">
                          <button type="button" class="btn btn-primary" @click="nuevoperio" >Registrar Ruta <i class="fas fa-user-plus fa-fw"></i></button>
                      </div>
            
        </div>
        <div class="card-body" >
            <v-client-table :data="hoja" :columns="columna" :options="options" >
                                              
                         
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
                        <button type="button" class="btn btn-success btn-sm" @click="editHoja(row)" ><i class="fa fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" @click="deleteHoja(row.id)"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-warning btn-sm" @click="verHoja(row.id)"><i class="fa fa-print"></i></button>
                        
                      </div>
                          
                        </div>
                      </v-client-table>
            
           
        </div>
    </div>
    <div v-else>
            <not-found></not-found>
    </div>


    <div class="modal fade" id="nuevoperiodo" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Registrar Hoja de Ruta</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar Hoja de Ruta</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? actuaHoja() : creaHoja()">
                    <div class="modal-body">
                        <div class="row">
                                <div class="form-group col-2" >
                                        <label>Fecha </label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            <input class="form-control" type="date" v-model="form.fecha" name="fecven" :class="{ 'is-invalid': form.errors.has('fecven') }" >
                                            <has-error :form="form" field="fecven"></has-error>
                                        </div>
                                </div>
                                <div class="form-group col-2" >
                                            <label>Hora de Inicio</label>
                                            <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text"><i class="fas fa-user-clock"></i></div>
                                                        </div>
                                                    <input class="form-control"  type="time"  v-model="hora_ini"  >
                                                    
                                            </div>
                                </div>
                                <div class="form-group col-2" >
                                        <label>Hora final</label>
                                        <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-history"></i></div>
                                                    </div>
                                                    <input class="form-control"  type="time" id="" v-model="hora_fin"  >
                                                    
                                        </div>
                                </div>
                                <div class="form-group col-2" >
                                        <label>DNI Cliente</label>
                                        <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-id-card-alt"></i></div>
                                                </div>
                                                <input class="form-control" type="number" v-model="dni" >
                                                
                                        </div>
                                </div>
                                <div class="form-group col-4" >
                                        <label>Nombre del Cliente</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-user-edit"></i></div>
                                            </div>
                                            <input class="form-control" type="text" v-model="cliente"  >
                                            
                                        </div>
                                </div>
                                <div class="form-group col-10" >
                                        <label>Actividad a Realizada</label>
                                        <textarea cols="50" rows="2" class="form-control" v-model="actividad" >Área de texto!</textarea>                                        
                                </div>
                                
                                <div class="col-2 mx-auto my-auto text-center">
                                        
                                            <button type="button" @click="agregarDetalle()" class="btn btn-app bg-success"><i class="fas fa-tasks"></i> Agregar Actividad</button>
                                        
                                </div>
                        



                        </div>
                            <div class="row">
                                    
                                    <div class="table-responsive col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Opciones</th>
                                                    <th>Hora Inicio</th>
                                                    <th>Hora Final</th>
                                                    <th>DNI</th>
                                                    <th>Cliente</th>
                                                    <th>Actividades Realizadas</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="form.array_detalle.length">
                                                <tr v-for="(detalle,index) in form.array_detalle" :key="detalle.id">
                                                    <td>
                                                        <button @click="eliminarDetalle(index,detalle.id)" type="button" class="btn btn-danger btn-sm">
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                    <td >
                                                        <input v-model="detalle.hora_ini" type="time" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input v-model="detalle.hora_fin" type="time"  class="form-control">
                                                    </td>
                                                    <td>
                                                        <input v-model="detalle.dni" type="text"  class="form-control">
                                                    </td>
                                                    <td>
                                                        <input v-model="detalle.cliente" type="text"  class="form-control">
                                                    </td>
                                                    <td>
                                                       <input v-model="detalle.actividad" type="text"  class="form-control">
                                                    </td>
                                                </tr>
                                            </tbody>  
                                            <tbody v-else>
                                                <tr>
                                                    <td colspan="6">
                                                        No hay Actividades agregadas
                                                    </td>
                                                </tr>
                                            </tbody>                                  
                                        </table>
                                    </div> 
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
    import moment from 'moment';
    export default {
        data() {
            return {
                editmode: false,
                error:{},
                hora_fin:'',
                hora_ini:'',
                dni:'',
                cliente:'',
                actividad:'',
                hoja : [],
                columna:['id','fecha','actions'],  
                options: {
                          headings: {
                                id: 'ID',fecha:'Fecha de Actividad',estado:'Estado',
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm',
                          
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
                    fecha : moment().format('YYYY-MM-DD'),
                    array_detalle:[{
                        idhoja:'',
                        hora_ini:'',
                        hora_fin:'',
                        dni:'',
                        cliente:'',
                        actividad:'',
                    }],
                    
                })
            }
        },
        methods: {
            eliminarDetalle(index,id){
                if(this.editmode == true){
                        Swal.fire({
                        title: 'Estas Seguro',
                        text: "Si se elimina no se podra revertir",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Eliminar!'
                    }).then((result) => {

                        // Send request to the server
                        if (result.value) {
                            axios.get('api/deldetallehoja',{params:{id:id}}).then(() => {
                                this.form.array_detalle.splice(index, 1);
                                Swal.fire(
                                    'Eliminado!',
                                    'El producto fue eliminado',
                                    'success'
                                )                               
                            }).catch(() => {
                                Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                            });
                        }
                    })
                }else{
                    this.form.array_detalle.splice(index, 1);
                    Swal.fire(
                                    'Eliminado!',
                                    'El producto fue eliminado',
                                    'success'
                                );
                }
               
                
            },
            verHoja(periodo){

                 this.$router.push({ path: '/hojaruta/'+periodo });
               
            },
            agregarDetalle(){
                let me=this;
                if(me.hora_fin === '' || me.hora_ini === '' || me.dni === '' || me.cliente === '' || me.actividad === '' ){
                    Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Debe Ingresar todos los Datos',
                        });
                }else{
                    
                    me.form.array_detalle.push({
                    hora_ini: me.hora_ini,
                    hora_fin: me.hora_fin,
                    dni: me.dni,
                    cliente: me.cliente,
                    actividad: me.actividad,
                    });
                    me.hora_ini='';
                    me.hora_fin='';
                    me.dni='';
                    me.cliente='';
                    me.actividad='';
                    }
                   
                
                
            },
            actuaHoja(){
               // this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/hojaruta/'+this.form.id)
                .then(() => {
                    // success
                    $('#nuevoperiodo').modal('hide');
                     Swal.fire(
                        'Actualizado',
                        'La Actualizacion los Datos',
                        'success'
                        )
                        this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch((error) => {
                   //console.log(error.response.data.errors);
                    this.error = error.response.data.errors;
                    this.$Progress.fail();
                });

            },

            nuevoperio(){
                this.editmode = false;
                this.form.reset();
                this.form.array_detalle=[];
                $('#nuevoperiodo').modal('show');
            },
            editHoja(periodo){
                this.editmode = true;
                this.error='';
                this.form.reset();
                this.form.clear();
                this.form.fill(periodo);
                $('#nuevoperiodo').modal('show');
                this.form.fill(periodo);
               
               
            },
            deleteHoja(id){
                Swal.fire({
                    icon: 'warning',
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
                                this.form.delete('api/hojaruta/'+id).then(()=>{
                                        Swal.fire(
                                        'Eliminado',
                                        'La Hoja de Ruta fue Eliminado',
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
                
                axios.get("api/hojaruta").then(({ data }) => (this.hoja = data)).catch(error => { this.error = error.response });  
            },
            creaHoja(){
               // this.$Progress.start();

                this.form.post('api/hojaruta')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevoperiodo').modal('hide');

                    Toast.fire({
                        type: 'success',
                        title: 'Hoja de Ruta Creado'
                        })
                    this.$Progress.finish();

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
        //    setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>
