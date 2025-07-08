<template>
  <div class="clase">
       <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title" style="font-size: 24px !important;"> <i class="fas fa-users mr-2" >
                  </i> Meta de Riesgo</h3>
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
                        <!-- <button @click="openModalAnalista()" class="btn btn-success">Ver Calificación de Analistas</button> -->
                    </div>
            </div>
            <div class="card-body" ref="contento" >

                <!-- <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Fecha de Inicio</label>
                        <el-date-picker @input="get" 
                          type="date" 
                          placeholder="Seleccione la fecha" 
                          v-model="fechaInicio" 
                          style="width: 100%;"  
                          value-format="yyyy-MM-dd"  
                          format="dd/MM/yyyy" 
                          :disabled="editmode">
                        </el-date-picker>

                      </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Fecha Fin</label>
                        <el-date-picker  @input="get" 
                          type="date" 
                          placeholder="Seleccione la fecha" 
                          v-model="fechaFin" 
                          style="width: 100%;"  
                          value-format="yyyy-MM-dd"  
                          format="dd/MM/yyyy" 
                          :disabled="editmode">
                        </el-date-picker>
                      </div>
                  </div>
                </div> -->
                                          
                      <v-client-table :data="analistas" :columns="columna" :options="options"  id="#ttrabajador">
                        <div slot="porcentaje" slot-scope="{row}">
                          {{row.porcentaje = 100 - ((parseFloat(row.meta) /parseFloat(row.MontoNoPagado))*100)}} %
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
              analistas :[],
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
              periodos:[],
              anio:moment().format('YYYY'),
              fechaInicio:'',
              fechaFin:'',
              calificaciones:[],
              columna:['nombre_analista','meta','MontoNoPagado', 'porcentaje'],  
              options: {
                        headings: {
                          nombre_analista:'Analista',
                          meta:'Monto a cobrar',
                          MontoNoPagado:'Monto no pagado',
                        },
                        perPage:25,
                        perPageValues:[25,50,100],
                        skin:'table table-sm table-hover',
                        filterAlgorithm: {
                              full_name(row, query) {
                        return (row.paterno + ' ' + row.materno).includes(query.toUpperCase());
                            }
                        },
                        filterable: ['nombre_analista','MontoMensual','MontoNoPagado'],
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
        

          loadUsers(){
            axios.get("/api/periodos").then(({ data }) => (this.periodos = data)).catch(error => { this.error = error.response });
          },

          get(){
            if (this.mes == null || this.mes == '' ) {
              return;
            }
            if (this.anio == null || this.anio == '' ) {
              return;
            }
            axios.get('/api/comcoutaatrasada',{params:{mes:this.mes, anio:this.anio}})
            .then(({data}) => {
              console.log(data);
              this.analistas = data;
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
