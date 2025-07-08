<template> 
  <div class="clase">
       <div class="card card-info">
            <div class="card-header no-print">
                <h3 class="card-title " style="font-size: 24px !important;"> <i class="fas fa-users mr-2" >
                  </i> Calculo de Comisiones</h3>
                    <div class="card-tools">
                      <button class="btn btn-warning" @click="print">Imprimir</button>
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
                                          
                      <!-- <v-client-table :data="resumen" :columns="columna" :options="options"  id="#ttrabajador">
                        <div slot="analista" slot-scope="{row}">
                          <span>{{row.nombres}} {{row.apellidos}}</span>
                        </div>
                        <div slot="cum_calidad_porc_total" slot-scope="{row}">
                          <span>{{row.cum_calidad_porc_total}} % </span>
                        </div>
                        <div slot="cum_meta_porc_total" slot-scope="{row}">
                          <span>{{row.cum_meta_porc_total}} %</span>
                        </div>
                        <div slot="cum_riesgo_porc_total" slot-scope="{row}">
                          <span>{{row.cum_riesgo_porc_total}} % </span>
                        </div>
                        <div slot="rentabilidad" slot-scope="{row}">
                          <span>{{row.dataCom.met_ren}} % </span>
                        </div>

                        <div slot="total" slot-scope="{row}">
                          <span>{{row.totalporcentaje }} % </span>
                        </div>
                      </v-client-table> -->

                      <div class="">
                        <h4><strong>CUMPLIMIENTO DE METAS :</strong></h4>
                        <table class="table table-bordered" border="1">
                          <tr style="font-weight:bold !important;" class="text-center">
                            <td>PERSONAL</td>
                            <td>CARGO</td>
                            <td>META COMERCIAL</td>
                            <td>META DE RIESGO</td>
                            <td>META DE RENTABILIDAD</td>
                            <td>CALIDAD DE PROCESO</td>
                            <td>TOTAL</td>
                          </tr>
                          <tr v-for="row in resumen">
                            <td><span>{{row.nombres}} {{row.apellidos}}</span></td>
                            <td class="text-center"> {{row.rolename}} </td>
                            <td class="text-center">{{row.cum_meta_porc_total}} %</td>
                            <td class="text-center">{{row.cum_riesgo_porc_total}} %</td>
                            <td class="text-center">{{row.dataCom.met_ren }} %</td>
                            <td class="text-center">{{row.cum_calidad_porc_total}} %</td>
                            <td class="text-center">{{row.totalporcentaje}} %</td>
                          </tr>
                        </table>
                      </div>

                      <div class="mt-5">
                        <h4><strong>PAGO DE COMISIONES:</strong></h4>
                        <table class="table table-bordered" border="1">
                          <tr style="font-weight:bold !important;" class="text-center">
                            <td>PERSONAL</td>
                            <td>SUELDO VARIABLE</td>
                            <td>META COMERCIAL</td>
                            <td>META DE RIESGO</td>
                            <td>META DE RENTABILIDAD</td>
                            <td>CALIDAD DE PROCESO</td>
                            <td>TOTAL</td>
                          </tr>
                          <tr v-for="row in resumen">
                            <td><span>{{row.nombres}} {{row.apellidos}}</span></td>
                            <td class="text-right">{{row.dataCom.sueldo_variable}}</td>
                            <td class="text-right">{{row.pago_meta_comercial}}</td>
                            <td class="text-right">{{row.pago_riesgo_comercial}}</td>
                            <td class="text-right">{{row.pago_rentabilidad_comercial }}</td>
                            <td class="text-right">{{row.pago_calidad_comercial}}</td>
                            <td class="text-right">{{row.pago_total}}</td>
                          </tr>
                        </table>
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
              columna:['analista','cum_meta_porc_total','cum_riesgo_porc_total','rentabilidad', 'cum_calidad_porc_total', 'total'],  
              options: {
                        headings: {
                          cum_calidad_porc_total:'Calidad de Proceso',
                          cum_meta_porc_total:'Meta Comercial',
                          cum_riesgo_porc_total:'Meta de Riesgo',
                          rentabilidad:'Meta de Rentabilidad',
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
          loadUsers(){
            axios.get("/api/periodos")
              .then(({ data }) => (this.periodos = data)).catch(error => { this.error = error.response });
          },

          get(){
            if (this.anio == null || this.anio == '') {
              return;
            }
            if (this.mes == null || this.mes == '') {
              return;
            }
            axios.get('/api/calcularcomisiones',{params:{anio:this.anio, mes:this.mes}})
            .then(({data}) => {
              console.log(data);
              
              data.map(i=>{
                if (!i.cum_calidad_porc_total) {
                  i.cum_calidad_porc_total = 10;
                } 
                if (!i.cum_meta_porc_total) {
                  i.cum_meta_porc_total = 0;
                } 
                if (!i.cum_riesgo_porc_total) {
                  i.cum_riesgo_porc_total = 0;
                }

                i.totalporcentaje = (parseFloat(i.cum_calidad_porc_total) + parseFloat(i.cum_meta_porc_total) + parseFloat(i.cum_riesgo_porc_total) + parseFloat(i.dataCom.met_ren)).toFixed(2);

                i.pago_meta_comercial = ((parseFloat(i.cum_meta_porc_total)/100) * parseFloat(i.dataCom.sueldo_variable)).toFixed(2);
                console.log(i.cum_meta_porc_total, i.dataCom.sueldo_variable);
                i.pago_riesgo_comercial = ((parseFloat(i.cum_riesgo_porc_total)/100) * parseFloat(i.dataCom.sueldo_variable)).toFixed(2);
                i.pago_calidad_comercial = ((parseFloat(i.cum_calidad_porc_total)/100) * parseFloat(i.dataCom.sueldo_variable)).toFixed(2);
                i.pago_rentabilidad_comercial = ((parseFloat(i.dataCom.met_ren)/100) * parseFloat(i.dataCom.sueldo_variable)).toFixed(2);

                i.pago_total = (((parseFloat(i.cum_meta_porc_total)/100) * parseFloat(i.dataCom.sueldo_variable)) + ((parseFloat(i.cum_riesgo_porc_total)/100) * parseFloat(i.dataCom.sueldo_variable)) + ((parseFloat(i.cum_calidad_porc_total)/100) * parseFloat(i.dataCom.sueldo_variable)) +  ((parseFloat(i.dataCom.met_ren)/100) * parseFloat(i.dataCom.sueldo_variable))).toFixed(2);
              })
              this.resumen = data;
            }).catch((err) => {
              console.log(err);
            });
          },
          print(){
            let footer = document.getElementsByTagName('footer')[0]
                footer.style = 'display:none';
                var css = "@page { size: A4; font-family: '8pin-matrix', arial; size: Carta;  margin: 2rem 1rem 2rem 0rem !important; padding: 0 0 0 0 !important} .table{width:100% !important} .tds{padding:0 0.1rem 0 0.1rem !important; font-size:11px !important}",
                    head = document.head || document.getElementsByTagName('head')[0],
                    style = document.createElement('style');
                style.type = 'text/css';
                style.media = 'print';
                if (style.styleSheet) {
                    style.styleSheet.cssText = css;
                } else {
                    style.appendChild(document.createTextNode(css));
                }

                head.appendChild(style);

                window.print();
                footer.style = 'display:block';
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
