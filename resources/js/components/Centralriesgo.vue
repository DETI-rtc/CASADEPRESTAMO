<template>
    <div class="">
       <div class="card card-teal" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 24px;"> <i class="fa fa-calendar nav-icon"></i> Registro de Consultas Central de Riesgo</h3>
            
                <div class="card-tools">
                          <button class="btn btn-primary" @click="nuevoperio" >Registrar Consultas <i class="fas fa-user-plus fa-fw"></i></button>
                      </div>
            
        </div>
        <div class="card-body" >
            <v-client-table :data="periodo" :columns="columna" :options="options" >
                        <div slot="idperiodo" slot-scope="{row}" @click="editPeri(row)">
                         {{row.idperiodo }}
                         </div>
                         <div slot="periodo" slot-scope="{row}" @click="editPeri(row)">
                         {{row.ano}}-{{row.mes}}
                         </div>
                       <div slot="fecven" slot-scope="{row}" @click="editPeri(row)">
                         {{row.fecven | myDate}}
                         </div>
                        <div slot="dias_calen" slot-scope="{row}" @click="editPeri(row)">
                          {{row.dias_calen}}
                        </div>
                        <div slot="dias_util" slot-scope="{row}" @click="editPeri(row)">
                          {{row.dias_util}}
                        </div>
                        <div slot="dias_feri" slot-scope="{row}" @click="editPeri(row)">
                          {{row.dias_feri}}
                        </div>
                        <div slot="estado" slot-scope="{row}" @click="editPeri(row)">
                          <div v-if = "row.estado == 1">
                              <span class="badge badge-pill badge-success">Activo</span>
                          </div>
                          <div v-else>
                              <span  class="badge badge-pill badge-danger">Inactivo</span>
                          </div>
                        </div>
                       <div slot="actions" slot-scope="{ row }">
                          <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-xs" @click="editPeri(row)" ><i class="fa fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-xs" @click="deletePeri(row.idperiodo)"><i class="fa fa-trash"></i></button>
                        
                      </div>
                          
                        </div>
                      </v-client-table>
            
           
        </div>
    </div>
    <div v-else>
            <not-found></not-found>
    </div>


    <div class="modal fade" id="nuevoperiodo" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Nuevo Periodo</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar Periodo</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? actuaPeri() : creaPeri()">
                <div class="modal-body">

                   <div class="row">
                          <div class="form-group col-6">
                        <label for="nombre">Mes*</label>
                          <select class="form-control" id="mes" name="mes" v-model="form.mes" :class="{ 'is-invalid': form.errors.has('mes') }">
                              <option value="Enero">Enero</option>
                              <option value="Febrero">Febrero</option>
                              <option value="Marzo">Marzo</option>
                              <option value="Abril">Abril</option>
                              <option value="Mayo">Mayo</option>
                              <option value="Junio">Junio</option>
                              <option value="Julio">Julio</option>
                              <option value="Agosto">Agosto</option>
                              <option value="Setiembre">Setiembre</option>
                              <option value="Octubre">Octubre</option>
                              <option value="Noviembre">Noviembre</option>
                              <option value="Diciembre">Diciembre</option>
                          </select> 
                          <has-error :form="form" field="mes"></has-error>
                          <span v-if="error.mes" class="error" >{{error.mes[0]}}</span>
                        </div>
                    <div class="form-group col-6">
                         <label for="apellido">Año Fiscal</label>
                         <select class="form-control" id="ano" name="ano" v-model="form.ano" :class="{ 'is-invalid': form.errors.has('ano') }">
                              <option value="2016">2016</option>
                              <option value="2017">2017</option>
                              <option value="2018">2018</option>
                              <option value="2019">2019</option>
                              <option value="2020">2020</option>
                               <option value="2021">2021</option>
                        </select> 
                        <has-error :form="form" field="ano"></has-error>
                    </div>
                    <div class="form-group col-6" >
                        <label>Dias Calendario</label>
                          <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                           </div>
                          <input class="form-control"  type="number"  v-model="form.dias_calen" :class="{ 'is-invalid': form.errors.has('dias_calen') }" min="20" max="31" maxlength="2">
                          <has-error :form="form" field="dias_calen"></has-error>
                        </div>
                      </div>
                      <div class="form-group col-6" >
                        <label>Dias Utiles</label>
                      <div class="input-group mb-2">
                          <div class="input-group-prepend">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                           </div>
                          <input class="form-control"  type="number" id="" v-model="form.dias_util" name="dias_util" :class="{ 'is-invalid': form.errors.has('dias_util') }" >
                          <has-error :form="form" field="dias_util"></has-error>
                        </div>
                      </div>

                      <div class="form-group col-6" >
                        <label>Dias Feriados</label>
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                           </div>
                          <input class="form-control" type="number" v-model="form.dias_feri" name="dias_feri" :class="{ 'is-invalid': form.errors.has('dias_feri') }" >
                          <has-error :form="form" field="dias_feri"></has-error>
                        </div>
                      </div>
                       <div class="form-group col-6" >
                        <label>Fecha de Vencimiento</label>
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                           </div>
                          <input class="form-control" type="date" v-model="form.fecven" name="fecven" :class="{ 'is-invalid': form.errors.has('fecven') }" >
                          <has-error :form="form" field="fecven"></has-error>
                        </div>
                        
                        <!-- /.input group -->
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
    export default {
        data() {
            return {
                editmode: false,
                error:{},
                periodo : [],
                columna:['idperiodo','mes','ano','fecven','dias_util','dias_calen','dias_feri','estado','actions'],  
                options: {
                          headings: {
                                idperiodo: 'ID',nombre:'Nombres',observa:'Descripcion',tipo_pla:'Tipo de Planilla',fijo:'Tipo de Descuento',
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm',
                          filterable: ['mes','observa'],
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
                    idperiodo:'',
                    ano : '2020',
                    mes : 'Enero',
                    dias_calen:'30',
                    dias_feri:'0',
                    dias_util:'20',
                    fecven : '',
                    
                })
            }
        },
        methods: {
            
            actuaPeri(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/periodo/'+this.form.idperiodo)
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

                $('#nuevoperiodo').modal('show');
            },
            editPeri(periodo){
                this.editmode = true;
                this.error='';
                this.form.reset();
                this.form.clear();
                $('#nuevoperiodo').modal('show');
                this.form.fill(periodo);
               
            },
            deletePeri(id){
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
                                this.form.delete('api/periodo/'+id).then(()=>{
                                        Swal.fire(
                                        'Eliminado',
                                        'El periodo fue Eliminado',
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
                
                axios.get("api/alperiodo").then(({ data }) => (this.periodo = data)).catch(error => { this.error = error.response });  
            },
            creaPeri(){
                this.$Progress.start();

                this.form.post('api/periodo')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevoperiodo').modal('hide');

                    Toast.fire({
                        type: 'success',
                        title: 'Periodo Creado'
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
