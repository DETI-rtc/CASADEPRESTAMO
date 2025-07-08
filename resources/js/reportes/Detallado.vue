<template> 
  <div class="clase">
       <div class="card card-info">
            <div class="card-header no-print">
                <h3 class="card-title " style="font-size: 24px !important;"> <i class="fas fa-users mr-2" >
                  </i> Reporte Detallado</h3>
                    <div class="card-tools">
                      <div class="btn-group" role="group" aria-label="Basic example">
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
                              :value="item"
                            >
                            </el-option>
                          </el-select>
                      </div>
                        <!-- <select v-model="anio" @change="selperiodo($event)" >SELCCIONE AÑO
                            <option v-for="peri in periodos" :key="peri.año" >
                                {{ peri.año }}
                            </option>
                        </select> -->
                        <!-- <button @click="openModalAnalista()" class="btn btn-success">Ver Calificación de Analistas</button> -->
                    </div>
            </div>
            <div class="card-body" ref="contento" >
              <div class="text-center">
                <h3 v-if="mes != ''">REPORTES DE COBROS DE CUOTAS Y CANCELACIONES DEL MES DE {{  mes.label.toUpperCase() }} CORRESPONDIENTE AL {{ anio }}</h3>
              </div>
              <table class="table table-bordered">
                <thead>
                  <tr class="text-center">
                    <th>N°</th>
                    <th>Fecha Pago</th>
                    <th>Cliente</th>
                    <th>Nro Cuota</th>
                    <th>Interés</th>
                    <th>Comisión</th>
                    <th>Seguro</th>
                    <th>Monto Cuota</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(i,index) in data" :key="i.id" v-bind:class="{ revisado: i.activo == 1 }" @click="revisado(i, index)">
                    <td class="text-center" > {{ index + 1 }} </td>
                    <td class="text-center"> {{i.fecha_pago}} </td>
                    <td> {{ i.nom_cliente}} </td>
                    <td class="text-center"> {{i.nro_cuota}} </td>
                    <td class="text-right"> {{ i.interes | money}} </td>
                    <td class="text-right"> {{ i.com_des | money}} </td>
                    <td class="text-right"> {{ i.seg_des | money}} </td>
                    <td class="text-right"> {{i.monto}} </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="7">TOTAL :</td>
                    <td class="text-right">{{calcTot}}</td>
                  </tr>
                </tfoot>
              </table>
                        
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
              calcTot:0,
              dots:'dots',
              color:'#dc3545',
              creditos :[],
              data:[],
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

          revisado(row,index){
            if (this.data[index].activo == 1) {
              this.data[index].activo = 0;
            } else {
              this.data[index].activo = 1;
            }
            
            // this.data.map(i=>{
            //   if (row.id == i.id) {
            //     if (i.activo == 1) {
            //       i.activo = 0;
            //     } else {
            //       i.activo = 1;
            //     }
            //   }
            // })
          },

          get(){
            if (this.anio == null || this.anio == '') {
              return;
            }
            if (this.mes == null || this.mes == '') {
              return;
            }
            axios.get('/api/reportedetallado',{params:{anio:this.anio, mes:this.mes.value}})
            .then(({data}) => {
              this.data = data;
              this.calcTot = 0;
              this.data.map(i=>{
                this.calcTot = this.calcTot + parseFloat(i.monto_value)
              });
              this.calcTot = this.calcTot.toFixed(2)
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

.revisado {
  background-color: rgba(0, 128, 0, 0.308) !important;
}
</style>
