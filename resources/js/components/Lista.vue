<template>
    <div class="">
       <div class="card card-primary" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title">Relacion de Listas</h3>
            
                <div class="card-tools">
                          
                      </div>
            
        </div>
        <div class="card-body">
            
            <div class="form-group col-6" >
                 <label for="staticEmail" class="col-form-label">LISTAS</label>
                 
                 <div class="input-group ">
                    <el-select v-model="form1.idlista" filterable placeholder="Select" @change="cambiolista">
                         <el-option  v-for="item in listas1" :key="item.id" :label="item.nomlista" :value="item.id" ></el-option>
                    </el-select>
                 <div class="input-group-append">
                        <button class="btn btn-outline-success" @click="nuevalis">Nuevo Lista <i class="fas fa-user-plus fa-fw"></i></button>
                       <button class="btn btn-outline-primary" @click="editlis">Editar Lista <i class="fas fa-user-plus fa-fw"></i></button>
                    </div>
                  </div>  
            </div>
                
             <div class="col-md-12">
                <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detalle de Lista</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-outline-success" @click="nuevode" ><i class="fas fa-user-plus fa-fw"></i>Agregar Detalle
                    </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="col-4">
                        <v-client-table :data="detalle" :columns="columna" :options="options" >
                           
                            <div slot="nomdelista" style="text-align: center;" slot-scope="{row}" @click="editCon(row)">
                                  
                                {{row.nomdelista}}
                            </div>
                            <div slot="actions" slot-scope="{ row }" style=" width:15%;">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-xs" @click="editdelis(row)" ><i class="fa fa-edit"></i></button>
                                    <button type="button" class="btn btn-danger btn-xs" @click="deledelis(row.id)"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </v-client-table>
                    </div>
                        
                <!-- <el-table :data="detalle.filter(data => !search || data.nomdelista.toLowerCase().includes(search.toLowerCase()))" style="width: 100%">
                   <el-table-column width="180" > 
                       <template slot="header" slot-scope="scope">
                            <el-input v-model="search" size="mini" placeholder="Type to search"/>
                       </template>
                       <template slot-scope="scope">
                            <el-button size="mini" @click="editdelis( scope.row)">Edit</el-button>
                            <el-button type="danger" icon="el-icon-delete" circle size="mini"  @click="deledelis( scope.row.id)">Delete</el-button>
                       </template>
                   </el-table-column>
                   <el-table-column label="Nombre" prop="nomdelista"> </el-table-column>
                   
                   
                </el-table>  -->  
                </div>
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
             </div> 
            
        </div>
    </div>
    <div v-else>
             <not-found></not-found>
            </div>


    <div class="modal fade" id="nuevalist" tabindex="-1" lista="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" lista="document">
                <div class="modal-content">
                <div class="card-header">
                    <h5 class="card-title" v-show="!editmode" id="addNewLabel">Nueva Lista</h5>
                    <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar LIsta</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? actuaLis() : creaLis()">
                <div class="modal-body">
                     <div class="form-group">
                         <label >Nombre de Lista</label>
                        <input v-model="form.nomlista" type="text" 
                            placeholder="Lista"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('nomlista') }">
                        <has-error :form="form" field="nomlista"></has-error>
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
     <div class="modal fade" id="nuevodetalle" tabindex="-1" lista="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" lista="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode1" id="addNewLabel">Nueva Detalle</h5>
                    <h5 class="modal-title" v-show="editmode1" id="addNewLabel">Actualizar Detalle</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode1 ? actuadelis() : creadeLis()">
                <div class="modal-body">
                     <div class="form-group">
                         <label >Nombre de Detalle</label>
                        <input v-model="form1.nomdelista" type="text" 
                            placeholder="Detalle"
                            class="form-control" :class="{ 'is-invalid': form1.errors.has('nomdelista') }">
                        <has-error :form="form1" field="nomdelista"></has-error>
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
                editmode1: false,
                error:{}, 
                value:'',  
                 search: '',             
                listas : [],
                listas1 : [],
                listas2 : [],
                detalle:[],
                delista:[],
                idlista:'',
                columna:['actions','nomdelista'],  
                options: {
                          headings: {
                                actions: '',nomdelista:'Detalle',
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm',
                          filterable: ['nomdelista'],
                          editableColumns:['nomdelista'],
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
                    nomdelista : '',
                    idlista : '',
                }),
                form: new Form({
                    id:'',
                    nomlista : '',
                })
            }
        },
        methods: {
            
            actuaLis(){
                this.form.put('api/listas/'+this.form.id).then(() => {
                    // success
                    $('#nuevalist').modal('hide');
                     Swal.fire(
                        'Actualizado',
                        'Se Actualizo el Rol',
                        'success'
                        )
                        Fire.$emit('AfterCreate');
                })
                .catch(() => { this.$Progress.fail();});
            },
            nuevalis(){
                this.editmode = false;
                this.form.reset();
                this.form.clear();
                this.form.errors.errors=[];
                $('#nuevalist').modal('show');
            },
            editlis(lista){
                this.editmode = true;
                this.form.errors.errors=[];
                $('#nuevalist').modal('show');
            },
            creaLis(){
                this.form.post('api/listas')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevalist').modal('hide');
                    Toast.fire({
                        type: 'success',
                        title: 'User Created in successfully'
                        })
                })
                .catch(()=>{ })
            },
            delelis(id){
                Swal.fire({
                    title: 'Estas Seguro?',
                    text: "Una ves Eliminado no se puede Revertir",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminarlo'
                    }).then((result) => {
                         if (result.value) {
                                this.form.delete('api/listas/'+id).then(()=>{
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
            cambiolista(value){
                
                //console.log(this.$emit("input", val));
                var inge = this.listas.find( nombre => nombre.id === value );
               this.form.id = inge.id;
               this.form.nomlista = inge.nomlista;
                //this.detalle = this.listas[0];
                
               axios.get("api/delista/"+value).then(({ data }) => (this.detalle=data)).catch(error => { this.error = error.response });
               $('th:eq(0)').css('width', '10');

            },
            actuadelis(){
                this.form1.put('api/delista/'+this.form1.id).then(({data}) => {
                    // success
                    this.detalle=data;
                    $('#nuevodetalle').modal('hide');
                     Swal.fire(
                        'Actualizado',
                        'Se Actualizo el Detalle',
                        'success')
                        Fire.$emit('AfterCreate');
                })
                .catch(() => { this.$Progress.fail();});
            },
            nuevode(){
                if(this.form1.idlista==''){
                    Swal.fire(
                        'Atencion',
                        'Selecione una Lista',
                        'warning')
                }else{
                    this.editmode1 = false; 
                    this.form1.errors.errors=[];  
                    this.form1.nomdelista='';             
                    $('#nuevodetalle').modal('show');
                }
                
            },
            editdelis(lista){
                this.editmode1 = true;
                this.form1.errors.errors=[];              
                this.form1.fill(lista);
                $('#nuevodetalle').modal('show');
            },
            creadeLis(){
                this.form1.post('api/delista')
                .then(({data})=>{
                    this.detalle = data;
                    Fire.$emit('AfterCreate');
                    $('#nuevodetalle').modal('hide');
                    Toast.fire({
                        type: 'success',
                        title: 'Detalle Creado'
                        })
                })
                .catch(()=>{ })
            },
            deledelis(id){
                Swal.fire({
                    title: 'Estas Seguro?',
                    text: "Una ves Eliminado no se puede Revertir",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminarlo'
                    }).then((result) => {
                         if (result.value) {
                                this.form.delete('api/listas/'+id).then(()=>{
                                        Swal.fire(
                                        'Eliminado',
                                        'El Detalle fue Eliminado',
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
                axios.get("api/listas").then(({ data }) => (this.listas = data,this.listas1=data)).catch(error => { this.error = error.response });
                axios.get("api/delista").then(({ data }) => (this.listas2 = data)).catch(error => { this.error = error.response });
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
<style  type="text/css">
.vs--searchable{
 /* width: 60%;*/
  
}
.vs__search{
 line-height: 1.9 !important;
}
.vuetables th#_actions {
     width: 200px;
}
.VueTables__search-field {
    display: inline-flex;
  }

</style>