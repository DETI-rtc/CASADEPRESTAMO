<template>
    <div class="">
       <div class="card card-teal card-outline" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 24px;"> <i class="fa fa-calendar nav-icon"></i> Relacion de Metas Asignadas</h3>
            
                <div class="card-tools">
                          <button class="btn btn-primary" @click="nuevoperio" >Asignar Meta <i class="fas fa-user-plus fa-fw"></i></button>
                      </div>
            
        </div>
        <div class="card-body" >
            <v-client-table :data="asignameta" :columns="columna" :options="options" >
                       <div slot="nombres" slot-scope="{row}" >
                          {{row.persona.apellidos+' '+row.persona.nombres }}
                        </div>
                        <div slot="m1" slot-scope="{row}">
                          {{row.m1 | currency  }}
                         </div>
                         <div slot="m2" slot-scope="{row}">
                          {{row.m2 | currency  }}
                         </div>
                         <div slot="m3" slot-scope="{row}">
                          {{row.m3 | currency  }}
                         </div>
                         <div slot="m4" slot-scope="{row}">
                          {{row.m4 | currency  }}
                         </div>
                         <div slot="m5" slot-scope="{row}">
                          {{row.m5 | currency  }}
                         </div>
                         <div slot="m6" slot-scope="{row}">
                          {{row.m6 | currency  }}
                         </div>
                         <div slot="m7" slot-scope="{row}">
                          {{row.m7 | currency  }}
                         </div>
                         <div slot="m8" slot-scope="{row}">
                          {{row.m8 | currency  }}
                         </div>
                         <div slot="m9" slot-scope="{row}">
                          {{row.m9 | currency  }}
                         </div>
                         <div slot="m10" slot-scope="{row}">
                          {{row.m10 | currency  }}
                         </div>
                         <div slot="m11" slot-scope="{row}">
                          {{row.m11 | currency  }}
                         </div>
                         <div slot="m12" slot-scope="{row}">
                          {{row.m12 | currency  }}
                         </div>
                         <div slot="anual" slot-scope="{row}">
                          {{row.anual | currency  }}
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
                        <button type="button" class="btn btn-success btn-sm" @click="editAsig(row)" ><i class="fa fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" @click="deleteAsig(row.id)"><i class="fa fa-trash"></i></button>
                        
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
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Registro de Meta para el Periodo : {{ form.periodo }}</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel"> {{form.persona.apellidos}} {{form.persona.nombres}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? actuAsig() : creaAsig()">
                <div class="modal-body">

                   <div class="row col-12">
                      <div class="form-group col-12" v-show="!editmode">
                          <label> Seleccione Analista</label>
                          <v-select  :options="persona" :reduce="persona => persona.id"  v-model="form.idpersona" label="usuarios" ></v-select>
                      </div>
                      <div class="form-group row col-12 mb-0 text-center" >
                        <label class="col-6 mt-2">Enero</label>
                        <div class="col-6">
                          <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.m1" class="form-control text-right" ></vue-autonumeric> 
                        </div>
                          
                      </div>
                      <div class="form-group row col-12 mb-0 text-center" >
                        <label class="col-6 mt-2">Febrero</label>
                        <div class="col-6">
                        <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.m2" class="form-control text-right" ></vue-autonumeric>   
                      </div>
                      </div>
                      <div class="form-group row col-12 mb-0 text-center" >
                        <label class="col-6 mt-2">Marzo</label>
                        <div class="col-6">
                        <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.m3" class="form-control text-right" ></vue-autonumeric>   
                      </div>
                      </div>
                      <div class="form-group row col-12 mb-0 text-center" >
                        <label class="col-6 mt-2">Abril</label>
                        <div class="col-6">
                        <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.m4" class="form-control text-right" ></vue-autonumeric>   
                      </div>
                      </div>
                      <div class="form-group row col-12 mb-0 text-center" >
                        <label class="col-6 mt-2">Mayo</label>
                        <div class="col-6">
                        <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.m5" class="form-control text-right" ></vue-autonumeric>   
                      </div>
                      </div>
                      <div class="form-group row col-12 mb-0 text-center" >
                        <label class="col-6 mt-2">Junio</label>
                        <div class="col-6">
                        <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.m6" class="form-control text-right" ></vue-autonumeric>   
                      </div>
                      </div>
                      <div class="form-group row col-12 mb-0 text-center" >
                        <label class="col-6 mt-2">Julio</label>
                        <div class="col-6">
                        <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.m7" class="form-control text-right" ></vue-autonumeric>   
                      </div>
                      </div>
                      <div class="form-group row col-12 mb-0 text-center" >
                        <label class="col-6 mt-2">Agosto</label>
                        <div class="col-6">
                        <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.m8" class="form-control text-right" ></vue-autonumeric>   
                      </div>
                      </div>
                      <div class="form-group row col-12 mb-0 text-center" >
                        <label class="col-6 mt-2">Setiembre</label>
                        <div class="col-6">
                        <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.m9" class="form-control text-right" ></vue-autonumeric>   
                      </div>
                      </div>
                      <div class="form-group row col-12 mb-0 text-center" >
                        <label class="col-6 mt-2">Octubre</label>
                        <div class="col-6">
                        <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.m10" class="form-control text-right" ></vue-autonumeric>   
                      </div>
                      </div>
                      <div class="form-group row col-12 mb-0 text-center" >
                        <label class="col-6 mt-2">Noviembre</label>
                        <div class="col-6">
                        <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.m11" class="form-control text-right" ></vue-autonumeric>   
                      </div>
                      </div>
                      <div class="form-group row col-12 mb-0 text-center" >
                        <label class="col-6 mt-2">Diciembre</label>
                        <div class="col-6">
                        <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.m12" class="form-control text-right" ></vue-autonumeric>   
                      </div>
                      </div>
                      <div class="form-group row col-12 mb-0 text-right" >
                        <label class="col-6 mt-2">Total</label>
                        <div class="col-6">
                        <vue-autonumeric :options="{digitGroupSeparator: ',', decimalCharacter: '.', decimalCharacterAlternative: '.'}" v-model="form.anual" class="form-control text-right" :value="calcTotal"  disabled></vue-autonumeric>   
                      </div>
                      </div>    <!-- /.input group -->
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
                persona : [],
                asignameta:[],
                columna:['periodo','nombres','m1','m2','m3','m4','m5','m6','m7','m8','m9','m10','m11','m12','anual','estado','actions'],  
                options: {
                          headings: {
                               nombres:'Apellidos y Nombres', m1:'Enero',m2:'Febrero',m3:'Marzo',m4:'Abril',m5:'Mayo',m6:'Junio',m7:'Julio',m8:'Agosto',m9:'Setiembre',m10:'Octubre',m11:'Noviembre',m12:'Diciembre',anual:'Total',
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm',
                           filterAlgorithm: {
                               full_name(row, query) {
                          return (row.persona.apellidos + ' ' + row.persona.nombres).includes(query.toUpperCase());
                             }
                          },
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
                form: new Form({
                    periodo:moment().format('YYYY'),
                    id:'',
                    idpersona:'',
                    m1:0,
                    m2:0,
                    m3:0,
                    m4:0,
                    m5:0,
                    m6:0,
                    m7:0,
                    m8:0,
                    m9:0,
                    m10:0,
                    m11:0,
                    m12:0,
                    anual:'',
                    persona:[],
                    
                    
                })
            }
        },
        computed: {
          calcTotal(){
                let result = this.form.m1 + this.form.m2 + this.form.m3 + this.form.m4 + this.form.m5 + this.form.m6 + this.form.m7 + this.form.m8 + this.form.m9 + this.form.m10 + this.form.m11 + this.form.m12;
                this.form.anual=result;
                return result;
            },
        },
        methods: {
            
            actuAsig(){
                //this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/asignameta/'+this.form.id)
                .then(() => {
                    // success
                    $('#nuevoperiodo').modal('hide');
                     Swal.fire(
                        'Actualizado',
                        'La Actualizacion los Datos',
                        'success'
                        )
                       // this.$Progress.finish();
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
            editAsig(periodo){
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
                
                axios.get("api/asignameta").then(({ data }) => (this.asignameta = data)).catch(error => { this.error = error.response });
                axios.get("api/verusuario").then(({ data }) => (this.persona = data)).catch(error => { this.error = error.response }); 
                
            },
            creaAsig(){
                //this.$Progress.start();
                this.form.post('api/asignameta')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevoperiodo').modal('hide');

                    Toast.fire({
                        type: 'success',
                        title: 'Periodo Creado'
                        })
                    //this.$Progress.finish();

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
