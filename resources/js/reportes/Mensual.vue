<template> 
  <div class="clase">
       <div class="card card-info">
            <div class="card-header no-print">
                <h3 class="card-title " style="font-size: 24px !important;"> <i class="fas fa-users mr-2" >
                  </i> Reporte Mensual</h3>
                    <div class="card-tools">
                      <div class="btn-group" role="group" aria-label="Basic example">
                        <button class="btn btn-outline-primary" type="button" id="button-addon2" @click="getReportsave">Guardar</button>
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
                <h3>RESUMEN DE COBROS DE CUOTAS Y CANCELACIONES DE <template v-if="mes"> {{mes.label | upText}} </template> DEL {{ anio }}</h3>
              </div>
              <table class="table table-bordered">
                <thead>
                  <tr class="text-center">
                    <th>N°</th>
                    <th>Mes</th>                    
                    <th>Cancelacion</th>
                    <th>Interes X Cancelacion</th>
                    <th>Redondeo Cancelacion</th>
                    <th>Cap. Amortizado Cuotas</th>
                    <th>Amortizacion x Cancelacion</th>
                    <th>Interés</th>
                    <th>Comisión</th>
                    <th>Seguro</th>
                    <th>Redondeo</th>
                    <th>Monto</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(i,index) in data" :key="i.id">
                    <td> {{ index + 1 }} </td>
                    <td> {{i.mes}} </td>                     
                     <td class="text-right text-primary font-weight-bold"> {{i.cancelado | localperu}} </td>
                     <td class="text-right text-primary font-weight-bold"> {{i.intexcancela| localperu}} </td>
                     <td class="text-right text-primary font-weight-bold"> {{i.credondeo | localperu}} </td>
                     <td class="text-right"> {{ i.cap_amor | localperu}} </td>
                     <td class="text-right">{{i.amorti | localperu}} </td>
                    <td class="text-right"> {{ i.interes | localperu}} </td>
                    <td class="text-right"> {{i.comision | localperu}} </td>
                    <td class="text-right"> {{i.seguro | localperu}} </td>
                    <td class="text-right"> {{i.redondeo | localperu}} </td>
                    <td class="text-right text-primary font-weight-bold"> {{i.monto | localperu}} </td>
                     <td class="text-right font-weight-bold"> {{i.total | localperu}} </td>
                  </tr>
                </tbody>
                <tfoot v-if="data.length > 0">
                  <tr>
                    <td colspan="2" class="text-center">TOTAL - {{ anio }} :</td>
                    <td class="text-right">{{cance}}</td>
                    <td class="text-right">{{intecance}}</td>
                    <td class="text-right"></td>
                    <td class="text-right">{{cap}}</td>
                    <td class="text-right">{{tamorti}}</td>
                    <td class="text-right">{{inte}}</td>
                    <td class="text-right">{{comi}}</td>
                    <td class="text-right">{{segu}}</td>
                    <td class="text-right"></td>
                    <td class="text-right"> {{totalmonto}} </td>
                    <td class="text-right">{{calcTot}}</td>
                  </tr>
                  <tr>
                    <td colspan="2" class="text-center">TOTAL - General :</td>
                    <td class="text-right">{{cance1}}</td>
                    <td class="text-right">{{intecance1}}</td>
                    <td class="text-right"></td>
                    <td class="text-right">{{cap1}}</td>
                    <td class="text-right">{{tamorti1}}</td>
                    <td class="text-right">{{inte1}}</td>
                    <td class="text-right">{{comi1}}</td>
                    <td class="text-right">{{segu1}}</td>
                    <td class="text-right"></td>
                    <td class="text-right"> {{totalmonto1}} </td>
                    <td class="text-right">{{calcTot1}}</td>
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
              tamorti:0,
              tamorti1:0,
              comi:0,
              segu:0,
              inte:0,
              cance:0,
              intecance:0,
              cap:0,
              totalmonto:0,
              calcTot1:0,
              comi1:0,
              segu1:0,
              inte1:0,
              cance1:0,
              intecance1:0,
              cap1:0,
              totalmonto1:0,
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
          getReportsave(){
            // if (this.data.length > 0) {
            //   axios.post('/api/savereportdeudarestante',{fecha:this.fecha,datos:this.data})
            //   .then(({data}) => {
                Swal.fire(
                        'Guardado',
                        'Se Guardo el Reporte',
                        'success'
                        );
            //   }).catch((err) => {
            //     console.log(err);
            //   });
            // }else{

            // }
            
          },
          get(){
            if (this.anio == null || this.anio == '') {
              return;
            }
            axios.get('/api/reportemensual',{params:{anio:this.anio, mes:this.mes.value}})
            .then(({data}) => {
              console.log(data);
              this.data = data;
                 let calcTot = 0;
                 let inte = 0;
                 let cance = 0;
                 let intecance = 0;
                 let segu = 0;
                 let comi = 0;
                 let cap = 0;
                 let totalmonto=0;
                 let calcTot1 = 0;
                 let inte1 = 0;
                 let cance1 = 0;
                 let intecance1 = 0;
                 let segu1 = 0;
                 let comi1 = 0;
                 let cap1 = 0;
                 let amor = 0;
                 let amor1 = 0;
                 let totalmonto1=0;
               this.data.map(i=>{
                if (i.nmes < 13) {
                  calcTot +=  parseFloat(i.total);
                  amor += parseFloat(i.amorti);
                inte +=  parseFloat(i.interes);
                
                if (i.cancelado===null) {
                  cance += 0;
                  
                }else{
                  cance +=  parseFloat(i.cancelado);
                }
                if (i.intexcancela===null) {
                  intecance1 +=0;
                  }else{
                    intecance +=  parseFloat(i.intexcancela);
                  }
                  

               
                comi +=  parseFloat(i.comision);
                segu +=  parseFloat(i.seguro);
                cap +=  parseFloat(i.cap_amor);
                totalmonto += parseFloat(i.monto); 
                }else{
                  amor1 += i.amorti;
                  calcTot1 +=  i.total;
                  inte1 +=  i.interes;
                  if (i.cancelado===null) {
                  cance += 0;
                  }else{
                    cance1 +=  parseFloat(i.cancelado);
                  }
                  if (i.intexcancela===null) {
                    intecance1 +=0;
                    
                  }else{
                    intecance1 +=  parseFloat(i.intexcancela);
                  }
                  
                 
                  comi1 +=  i.comision;
                  segu1 +=  i.seguro;
                  cap1 +=  i.cap_amor;
                  totalmonto1 += i.monto;

                }
                
              })
              this.calcTot = new Intl.NumberFormat('es-MX').format(calcTot);
              this.inte = new Intl.NumberFormat('es-MX').format(inte);
              this.cance = new Intl.NumberFormat('es-MX').format(cance);
              this.intecance = new Intl.NumberFormat('es-MX').format(intecance);
              this.segu = new Intl.NumberFormat('es-MX').format(segu);
              this.comi = new Intl.NumberFormat('es-MX').format(comi.toFixed(2))
              this.cap = new Intl.NumberFormat('es-MX').format(cap.toFixed(2))
              this.totalmonto = new Intl.NumberFormat('es-MX').format(totalmonto)
              this.tamorti = new Intl.NumberFormat('es-MX').format(amor)

              this.calcTot1 = new Intl.NumberFormat('es-MX').format(calcTot+calcTot1);
              this.inte1 = new Intl.NumberFormat('es-MX').format(inte+inte1);
              this.cance1 = new Intl.NumberFormat('es-MX').format(cance+cance1);
              this.intecance1 = new Intl.NumberFormat('es-MX').format(intecance+intecance1)
              this.segu1 = new Intl.NumberFormat('es-MX').format(segu+segu1);
              this.comi1 = new Intl.NumberFormat('es-MX').format(comi+comi1);
              this.cap1 = new Intl.NumberFormat('es-MX').format(cap+cap1);
              this.totalmonto1 =Intl.NumberFormat('es-MX').format(totalmonto+totalmonto1);
              this.tamorti1 = new Intl.NumberFormat('es-MX').format(amor+amor1);

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
