<template>
  <div class="clase">
       <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title" style="font-size: 24px !important;"> <i class="fas fa-users mr-2" >
                  </i> Meta Comercial</h3>
                    <div class="card-tools">
                        <el-select v-model="anio" filterable placeholder="Seleccione" @input="get">
                          <el-option
                            v-for="item in periodos"
                            :key="item.año"
                            :label="item.año"
                            :value="item.año">
                          </el-option>
                        </el-select>
                        <el-select v-model="mes" filterable placeholder="Seleccione" @input="get">
                          <el-option
                            v-for="item in meses"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                          >
                          </el-option>
                        </el-select>
                        <!-- <select v-model="anio" @change="selperiodo($event)" >SELCCIONE AÑO
                            <option v-for="peri in periodos" :key="peri.año" >
                                {{ peri.año }}
                            </option>
                        </select> -->
                        <!-- <button @click="openModalAnalista()" class="btn btn-success">Ver Calificación de Analistas</button> -->
                    </div>
            </div>
            <div class="card-body" ref="contento" >
                      <!-- <div class="form-group">
                        <label for="exampleInputEmail1">Mes</label>
                        <el-select v-model="mes" filterable placeholder="Seleccione">
                          <el-option
                            v-for="item in meses"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                            @input="get()"
                          >
                          </el-option>
                        </el-select>
                      </div> -->
                                          
                      <v-client-table :data="resumen" :columns="columna" :options="options"  id="#ttrabajador">
                        <div slot="porcentaje" slot-scope="{row}">
                          <span v-if="row.meta != 0">{{ row.porcentaje = (parseFloat(row.desembolso) / parseFloat(row.meta))* 100 }}</span>
                          <span v-else>{{ row.porcentaje = 0 }}</span>
                        </div>
                      </v-client-table>
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
              resumen:[],
              periodos:[],
              meses:[
                {
                  label:'Enero',
                  value:1
                },
                {
                  label:'Febrero',
                  value:2
                },
                {
                  label:'Marzo',
                  value:3
                },
                {
                  label:'Abril',
                  value:4
                },
                {
                  label:'Mayo',
                  value:5
                },
                {
                  label:'Junio',
                  value:6
                },
                {
                  label:'Julio',
                  value:7
                },
                {
                  label:'Agosto',
                  value:8
                },
                {
                  label:'Setiembre',
                  value:9
                },
                {
                  label:'Octubre',
                  value:10
                },
                {
                  label:'Noviembre',
                  value:11
                },
                {
                  label:'Diciembre',
                  value:12
                },
              ],
              mes:'',
              anio:moment().format('YYYY'),
              calificaciones:[],
              columna:['nombre_analista','meta','desembolso', 'porcentaje'],  
              options: {
                        headings: {
                          nombre_analista:'Analista',
                          desembolso:'Desembolsado'
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
          },

          get(){
            if (this.anio == null || this.anio == '') {
              return;
            }
            if (this.mes == null || this.mes == '') {
              return;
            }
            axios.get('/api/comcumplimientometa',{params:{anio:this.anio, mes:this.mes}})
            .then(({data}) => {
              console.log(data);
              this.resumen = data;
            }).catch((err) => {
              console.log(err);
            });
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
