<template> 
  <div class="clase">
       <div class="card card-info">
            <div class="card-header no-print">
                <h3 class="card-title " style="font-size: 24px !important;"> <i class="fas fa-users mr-2" >
                  </i> Reporte Mensual</h3>
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
            <div class="card-body" ref="contento">

              <div class="text-center">
                <h3>RESUMEN DE RETORNO DE CAPITAL <template v-if="mes"> {{mes.label | upText}} </template> DEL {{ anio }}</h3>
              </div>
              <table class="table table-bordered">
                <thead>
                  <tr class="text-center">
                    <th>N°</th>
                    <th>Mes</th> 
                    <th>Desembolso</th>                   
                    <th>Acumulado</th>
                    <th>Cancelacion</th>
                    <th>Cap. Amortizado</th>
                    <th>Total Amortizado</th>
                    <th>Saldo Cap. Adeudo</th>
                    </tr>
                </thead>
                <tbody>
                  <tr v-for="(i,index) in data" :key="i.id">
                    <td> {{ index + 1 }} </td>
                    <td> {{i.mes}} </td>                     
                     <td class="text-right text-primary font-weight-bold"> {{i.desem | localperu}} </td>
                     <td class="text-right"> {{ i.acumulado | localperu}} </td>
                     <td class="text-right">{{i.cancelado | localperu}} </td>
                    <td class="text-right"> {{ i.amorti | localperu}} </td>
                    <td class="text-right"> {{i.total | localperu}} </td>
                    <td class="text-right"> {{i.deuda | localperu}} </td>
                   
                  </tr>
                </tbody>
                <tfoot v-if="data.length > 0">
                  <tr>
                    <td colspan="2" class="text-center">TOTAL - {{ anio }} :</td>
                    <td class="text-right">{{tdesem}}</td>
                    <td class="text-right">{{tacumo}}</td>
                    <td class="text-right">{{tcancelado}}</td>
                    <td class="text-right">{{tamorti}}</td>
                    <td class="text-right">{{ttotal}}</td>
                    <td class="text-right">{{tdeuda}}</td>
                  
                  </tr>
                  <tr>
                    <td colspan="2" class="text-center">TOTAL - General :</td>
                    <td class="text-right"></td>
                    <td class="text-right">{{ttacomu}}</td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right">{{tttotal}}</td>
                    <td class="text-right">{{ttdeuda}}</td>
                    
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
              tacomu:0,
              ttacomu:0,
              tdesem:0,
              tcancelado:0,
              ttotal:0,
              tdeuda:0,
              tttotal:0,
              ttdeuda:0,

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

          get(){
            if (this.anio == null || this.anio == '') {
              return;
            }
            axios.get('/api/reportemensualamo',{params:{anio:this.anio, mes:this.mes.value}})
            .then(({data}) => {
              this.data = data;
              let tdesem =0;
                 let tacumo = 0;
                 let tamorti = 0;
                 let tcancelado = 0;
                 let ttotal = 0;
                 let tdeuda = 0;
                 
                 let tttoal= 0;
                 let ttdeuda = 0;
                 
               this.data.map(i=>{
                if (i.nmes < 13) {
                  tacomu +=  parseFloat(i.acomulado);
                  tdesem += parseFloat(i.desem);
                tamorti +=  parseFloat(i.amorti);
                tcancelado +=  parseFloat(i.cancelado);
                ttotal +=  parseFloat(i.total);
                tdeuda +=  parseFloat(i.deuda);
                
                }else{
                  ttacomu += i.acumulado;
                  ttotal +=  i.total;
                  ttdeuda +=  i.deuda;
                  

                }
                
              })
              this.tdesem = new Intl.NumberFormat('es-MX').format(tdesem);
              this.tacumu = new Intl.NumberFormat('es-MX').format(tacomu);
              this.tcancelacion = new Intl.NumberFormat('es-MX').format(tcancelado);
              this.tamorti = new Intl.NumberFormat('es-MX').format(tamorti);
              this.ttotal = new Intl.NumberFormat('es-MX').format(ttotal.toFixed(2))
              this.tdeuda = new Intl.NumberFormat('es-MX').format(tdeuda.toFixed(2))
              

              this.ttacomu = new Intl.NumberFormat('es-MX').format(tacumo+ttacomu);
              this.tttoal = new Intl.NumberFormat('es-MX').format(ttotal+tttoal);
              this.ttdeuda = new Intl.NumberFormat('es-MX').format(tdeuda+ttdeuda);
              

            }).catch((err) => {
              console.log(err);
            });
          },
          sumar(){

          },
          print(){
            let footer = document.getElementsByTagName('footer')[0]
                footer.style = 'display:none';
                var css = "@page { size: A4 landscape; font-family: '8pin-matrix', arial; size: Carta;  margin: 2rem 1rem 2rem 0rem !important; padding: 0 0 0 0 !important} .table{width:100% !important} .tds{padding:0 0.1rem 0 0.1rem !important; font-size:13px !important}",
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
           this.get();
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
