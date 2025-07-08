<template>
  <div class="clase">
       <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title" style="font-size: 24px !important;"> <i class="fas fa-users mr-2" ></i> RELACION DE CLIENTES CON CRÉDITOS</h3>
                    <div class="card-tools">
                        <select v-model="anio" @change="selperiodo($event)" >SELCCIONE AÑO
                            <option v-for="peri in periodos" :key="peri.año" >
                                {{ peri.año }}
                            </option>
                        </select>
                        <button @click="openModalAnalista()" class="btn btn-success">Ver Calificación de Analistas</button>
                    </div>
            </div>
            <div class="card-body" ref="contento" >
                                          
                      <v-client-table :data="creditos" :columns="columna" :options="options"  id="#ttrabajador">
                        <div slot="actions" slot-scope="{row}">
                          <button class="btn btn-primary btn-sm" @click="openModalCalificacion(row)" v-if="row.calificado == null"><i class="fas fa-star"></i> Calificar</button>
                          <span v-else style="color:#D2AD05;">{{row.calificado}} <i class="fas fa-star"></i></span>
                        </div>
                        <div slot="porcentaje" slot-scope="{row}">
                          <span v-if="parseFloat(row.calificado)>0">{{ row.porcentaje = 100*(parseFloat(row.calificado)/10) }} %</span>
                          <span v-else>0 %</span>
                        </div>
                      </v-client-table>
            </div>
        </div>
        

        <div class="modal fade" id="CalificacionModal" tabindex="-1" aria-labelledby="CalificacionModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="CalificacionModalLabel">{{formCalificacion.cliente}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <el-form ref="form" label-width="120px">
                  <el-form-item label="Puntaje">
                    <!-- <el-input v-model="form.name"></el-input> -->
                    <el-input-number v-model="formCalificacion.calificacion" :precision="2" :step="1" :min="1" :max="10"></el-input-number>
                  </el-form-item>
                </el-form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" @click="upCalificacion">Calificar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="AnalistaModal" tabindex="-1" aria-labelledby="AnalistaModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="AnalistaModalLabel">Calificacion de Analistas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <v-client-table :data="calificaciones" :columns="columna1" :options="options1" >
                    <!-- <div slot="s" slot-scope="{row}">
                          <img :src="`/storage/${row.photo}`" style="width:10rem;">
                      </div> -->
                      <div slot="promedio" slot-scope="{row}">
                        {{row.promedio}} <i class="fas fa-star"></i>
                      </div>
                  </v-client-table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
 
  </div>
</template>

<script>
  import VueAutonumeric from 'vue-autonumeric'
  import Loading from 'vue-loading-overlay'
  import moment from 'moment'
    export default {
        components:{
            Loading
        },
        data() {
            return {
              isLoading: false,
              fullPage: true,
              dots:'dots',
              color:'#dc3545',
              creditos :[],
              periodos:[],
              anio:moment().format('YYYY'),
              calificaciones:[],
              columna:['analista','cliente','celular','anio','miempresa','porcentaje','actions'],  
              options: {
                        headings: {
                          actions:'Calificacion',
                          miempresa:'Empresa'
                        },
                        perPage:25,
                        perPageValues:[25,50,100],
                        skin:'table table-sm table-hover',
                        filterAlgorithm: {
                              full_name(row, query) {
                        return (row.paterno + ' ' + row.materno).includes(query.toUpperCase());
                            }
                        },
                        filterable: ['analista','cliente','celular','anio','miempresa'],
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
                              empresa(h, row){
                              return row.empresa.razonsocial;
                              },
                              
                        },
              },

              columna1:['analista','mes','anio','promedio'],  
              options1: {
                headings: {
                  s:'',
                  anio:'Año'
                },
                perPage:25,
                perPageValues:[25,50,100],
                skin:'table table-sm table-hover',
                filterAlgorithm: {
                      full_name(row, query) {
                return (row.paterno + ' ' + row.materno).includes(query.toUpperCase());
                    }
                },
                filterable: ['analista','mes','anio'],
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
                      empresa(h, row){
                      return row.empresa.razonsocial;
                      },
                      
                },
              },




              formCalificacion: new Form({
                cliente:'',
                idcliente:'',
                idanalista:'',
                idcredito:'',
                calificacion:'',
                fecha:moment().format('YYYY-MM-DD'),
              }),
            }
        },
       
        methods: {
          selperiodo(){
            axios.get('/api/credwithanalista',{params:{'anio':this.anio}})
            .then(({data}) => {
              console.log(data);
              this.creditos = data;
            }).catch((err) => {
              console.log(err);
            });
          },
          openModalAnalista(){
            $('#AnalistaModal').modal('show')
            axios.get('/api/calificacion')
            .then(({data}) => {
              console.log(data);
              this.calificaciones = data;
            }).catch((err) => {
              console.log(err);
            });
          },
          openModalCalificacion(i){
            this.formCalificacion.reset();
            $('#CalificacionModal').modal('show')
            console.log(i);
            this.formCalificacion.cliente =i.cliente;
            this.formCalificacion.idcliente =i.idpersona;
            this.formCalificacion.idanalista =i.idanalista;
            this.formCalificacion.idcredito =i.id;
          },

          upCalificacion(){
            this.formCalificacion.post('/api/calificacion')
            .then(({data}) => {
              console.log(data);
              $('#CalificacionModal').modal('hide');
              this.$notify({
                  title: 'Satisfactorio',
                  message: 'La calificacion ha sido enviada con éxito.',
                  type: 'success'
              });
              this.loadUsers();

            }).catch((err) => {
              console.log(err);
            });
          },

          loadUsers(){
            axios.get("/api/periodos").then(({ data }) => (this.periodos = data)).catch(error => { this.error = error.response });
            axios.get('/api/credwithanalista',{params:{'anio':this.anio}})
            .then(({data}) => {
              console.log(data);
              this.creditos = data;
            }).catch((err) => {
              console.log(err);
            });
          },
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
<style type="text/css">
  .VueTables__table tbody {
  font-size: 12px;
}
  .VueTables__search-field {
    display: inline-flex;
  }
.VueTables__limit-field{
  display: inline-flex;
}
.error{
  width: 100%;
    margin-top: .25rem;
    font-size: 80%;
    color: #dc3545;
}
.table-hover tbody tr:hover {
  
  background-color: rgb(212 222 63 / 52%);
}
.el-select {
    /*width: 100%;*/
}
</style>
