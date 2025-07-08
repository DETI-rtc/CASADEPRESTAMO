<template>
    <div class="">
       <div class="card card-primary" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title">Asignacion de Supervisores</h3>
            
                <div class="card-tools">
                          
                      </div>
            
        </div>
        <div class="card-body">
            
            <div class="form-group col-6" >
                 <label for="staticEmail" class="col-form-label">Supervisores</label>
                 <div class="input-group " >
                    <el-select v-model="form.idnivel2" filterable placeholder="Selecciona Supervisor" @change="cambiolista">
                         <el-option  v-for="item in listas1" :key="item.id" :label="item.apellidos+' '+item.nombres" v-if="item.rol == 'Supervisor'" :value="item.id" ></el-option>
                    </el-select>
                  </div>  
            </div>
                
             <div class="col-md-12">
                <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Relacion de Agentes a Supervisar</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-outline-success" @click="nuevode" ><i class="fas fa-user-plus fa-fw"></i>Agregar Agente 
                    </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="col-4">
                        <v-client-table :data="detalle" :columns="columna" :options="options" >
                            <div slot="actions" slot-scope="{ row }" style=" width:15%;">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-danger btn-xs" @click="deledelis(row.id)"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </v-client-table>
                    </div>
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


    <div class="modal fade" id="nuevodetalle" tabindex="-1" lista="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" lista="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode1" id="addNewLabel">Nueva Detalle</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="asignaAgen()">
                <div class="modal-body">
                     <div class="form-group">
                         <label >Seleccione Agente</label>
                        <el-select  v-model="form.idnivel1" filterable placeholder="Selecciona Supervisor" @change="selagente" >
                            <el-option  v-for="item in listas2" :key="item.id" :label="item.apellidos+' '+item.nombres" :value="item.id" ></el-option>
                        </el-select>
                        
                    </div>
                        
                 </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Asignar</button>
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
                value:1,  
                 search: '',             
                listas : [],
                listas1 : [],
                listas2 : [],
                detalle:[],
                delista:[],
                idlista:'',
                columna:['actions','nombres','apellidos','dni'],  
                options: {
                          headings: {
                                actions: '',nombres:'Nombres', apellidos:'Apellidos',dni:'DNI',
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
                form: new Form({
                    id:'',
                    idnivel1 : '',
                    nombrenivel1:'',
                    idnivel2 : '',
                    nombrenivel2:'',
                }),
                
            }
        },
        methods: {
            
            
            cambiolista(value){
                
                //console.log(this.$emit("input", val));
                var inge = this.listas.find( nombre => nombre.id === value );
               //console.log(inge);
               this.form.idnivel2 = inge.id;
               this.form.nombrenivel2 = inge.rol
              // this.form.nomlista = inge.nomlista;
                //this.detalle = this.listas[0];
                
               axios.get("api/agensupervisados/"+value).then(({ data }) => (this.detalle=data)).catch(error => { this.error = error.response });
               $('th:eq(0)').css('width', '10');

            },
            selagente(value){
                
                var inge = this.listas2.find( nombre => nombre.id === value );
                this.form.idnivel1 = inge.id;
                this.form.nombrenivel1 = inge.rol
            },
            
            nuevode(){
                if(this.form.idnivel2==''){
                    Swal.fire(
                        'Atencion',
                        'Selecione una Lista',
                        'warning')
                }else{
                    
                    $('#nuevodetalle').modal('show');
                }
                
            },
            
            asignaAgen(){
                this.form.post('api/regagente').then(({data})=>{
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
                axios.get("api/user").then(({ data }) => (this.listas = data,this.listas1=data)).catch(error => { this.error = error.response });
                axios.get("api/veragentes").then(({ data }) => (this.listas2 = data)).catch(error => { this.error = error.response });
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