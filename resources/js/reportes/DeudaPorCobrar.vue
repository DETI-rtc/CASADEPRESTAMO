<template> 
  <div class="clase">
       <div class="card card-info">
            <div class="card-header no-print">
                <h3 class="card-title " style="font-size: 24px !important;"> <i class="fas fa-users mr-2" >
                  </i> Reporte De Deuda Restante</h3>
                    <div class="card-tools">
                      <div class="btn-group" role="group" aria-label="Basic example">
                          <button class="btn btn-warning" @click="print"><i class="fa fa-print"></i> Imprimir</button>
                          <!-- <el-select v-model="anio" filterable placeholder="Seleccione" @input="get">
                            <el-option
                              v-for="item in periodos"
                              :key="item.año"
                              :label="item.año"
                              :value="item.año">
                            </el-option>
                          </el-select> -->
                          
                          <!-- <el-select v-model="mes" filterable placeholder="Seleccione" @input="get">
                            <el-option
                              v-for="item in meses"
                              :key="item.value"
                              :label="item.label"
                              :value="item.value"
                            >
                            </el-option>
                          </el-select> -->
                      </div>
                        <!-- <select v-model="anio" @change="selperiodo($event)" >SELCCIONE AÑO
                            <option v-for="peri in periodos" :key="peri.año" >
                                {{ peri.año }}
                            </option>
                        </select> -->
                        <!-- <button @click="openModalAnalista()" class="btn btn-success">Ver Calificación de Analistas</button> -->
                    </div>
            </div>
            
        </div>
        <div  ref="contento" >
              <div class="text-center">
                <!-- <input type="date" class="form-control" v-model="fecha" @input="getReport"> -->
                <div class="input-group mb-3 col-4 no-print">
                  <input type="date" class="form-control" placeholder="Especifique" aria-describedby="button-addon2" v-model="fecha">
                  <button class="btn btn-outline-secondary" type="button" id="button-addon2" @click="getReport">Buscar</button>
                  <button class="btn btn-outline-primary" type="button" id="button-addon2" @click="getReportsave">Guardar</button>
                </div>
                
              </div>

              <div class="text-center">
                <h3>REPORTES DE ESTADO DE CRÉDITO A LA FECHA {{ fecha }}</h3>
              </div>
              
                <table class="table table-bordered" >
                <thead>
                  <tr class="text-center text-sm">
                    <th class="cabe" rowspan="2"width="3%">N°</th>
                    <th class="cabe" rowspan="2"width="20%">Nombres y Apellidos</th>
                    <th class="cabe" rowspan="2">Prestamo Solicitado</th>
                    <th class="cabe" rowspan="2">Total Prestamo Otorgado</th>
                    <th class="cabe" rowspan="2">Cantidad de Cuotas</th>
                    <th class="cabe" rowspan="2">Monto de la Cuota</th>
                    <th class="cabe" colspan="6">PAGADO AL</th>
                    <th class="cabe" rowspan="2">Total Pagado</th>
                    <th class="cabe" rowspan="2">Total Prestamo X Cobrar</th>
                    <th class="cabe" colspan="3">Informacion Adicional</th>
                    <!-- <th class="cabe" rowspan="2">Observaciones</th> -->
                  </tr>
                  <tr>
                    <th class="cabe text-center">N° Cuotas Pagadas</th>
                    <th class="cabe text-center">Monto Pagado Prestamo</th>
                    <th class="cabe text-center">Amortizaciones</th>
                    <th class="cabe text-center">Pagos a cuenta </th>
                    <th class="cabe text-center">Cancelaciones de Prestamos</th>
                    
                    <th class="cabe text-center">Redondeo</th>
                    <th class="cabe text-center">Interés captado x cuotas</th>
                    <th class="cabe text-center">Interes X Cancelacion de prestamos</th>
                    <th class="cabe text-center">No Cobrado x Cancelacion</th>
                    
                   
                  </tr>

                    <!-- <tr class="text-center text-sm">
                    <th class="cabe" width="3%">N°</th>
                    <th class="cabe" width="20%">Nombres y Apellidos</th>
                     <th class="cabe">N° Credito</th> 
                    <th class="cabe">Prestamo Solicitado</th>
                    <th class="cabe">Monto de la Cuota</th>
                    <th class="cabe">Cantidad de Cuotas</th>
                    <th class="cabe">Total Prestamo Otorgado</th>
                    <th class="cabe">N° Cuotas Pagadas</th>
                    <th class="cabe">Interés captado x cuotas</th>
                    <th class="cabe">Monto Pagado Prestamo</th>
                    <th class="cabe">Adelanto de Cuotas</th>
                    <th class="cabe">No Cobrado x Cancelacion</th>
                    <th class="cabe">Interes X Cancelacion de prestamos</th> 
                    <th class="cabe">Cancelaciones de Prestamos</th>
                    <th class="cabe">Redondeo</th>
                    <th class="cabe">Total Pagado</th>
                    <th class="cabe">Total Prestamo X Cobrar</th>
                   <th class="cabe">Sal/Cap X Cobrar</th>
                    <th class="cabe">Interes X Cobrar</th>
                    <th class="cabe">Seguro X Cobrar</th>
                    <th class="cabe">Comision X Cobrar</th>
                    <th class="cabe">Cant. Cuotas x Cobrar</th> 
                  </tr> -->
                </thead>
                <tbody v-if="data.length > 0">
                  <tr v-for="(i,index) in data" :key="i.id">
                    <td class="text-center" > {{ index + 1 }} </td>
                    <td title="CLIENTES"> {{i.nombres}} </td>
                    <!-- <td class="text-center " title="NRO DE CREDITO"> {{i.n_credito}} </td> -->
                    <td class="text-right" title="MONTO FINANCIADO"> {{ i.monto_financiado | decimal1}} </td>
                    <td class="text-right" title="DEUDA PRESTAMO"> {{ i.total_deuda | decimal1}} </td>
                    <td class="text-center" title="TOTAL CUOTAS"> {{i.total_cuotas}} </td>
                    <td class="text-right" title="CUOTA PRESTAMO"> {{ i.cuota | decimal1}} </td>
                    <td class="text-center " title="NRO DE CUTAS PAGADAS"> {{i.n_cuotas_pagado}} </td>                   
                    <td class="text-right " title="MONTO PAGADO "> {{ i.total_pagado | decimal1}} </td>
                    <td class="text-right " title="MONTO AMORTIZADO "> {{ i.amortiza | decimal1}} </td>
                    <td class="text-right " title="ADELANTO DE CUOTA "> {{ i.adelanto | decimal1}} </td>
                    <td class="text-right " title="CANCELACION"> {{ i.monto_cancelado | decimal1}} </td>
                    
                    <!-- <td class="text-right " title="REDONDEO" v-if="i.redondeo === null"> 0 </td> -->
                    <td class="text-right " title="REDONDEO" > {{ i.redo | decimal1 }} </td>
                    <td class="text-right " title="TOTAL PAGADO"> {{ i.total | decimal1 }} </td>
                    <td class="text-right " title="PRESTAMO X COBRAR" v-if="i.monto_cancelado > 0"> 0.00 </td>
                    <td class="text-right " title="PRESTAMO POR COBRAR" v-else> {{ i.monto_res | decimal1}} </td>
                    <td class="text-right " title="INTERES PAGADO"> {{ i.interes_pagado | decimal1}} </td>
                    <td class="text-right " title="INTERES POR CANCELACION"> {{ i.interes_cancelado | decimal1}} </td>
                    <td class="text-right " title="NO COBRADO POR CANCELACION"> {{ i.nocobro | decimal1}} </td>
                    
                    <td></td>
                    <!-- <td class="text-right " title="SALDO RESTANTE"> {{ i.sal_res | decimal1 }} </td>
                    <td class="text-right " title="INTERES RESTANTE"> {{ i.int_res | decimal1 }} </td>
                    <td class="text-right " title="SEGURO RESTANTE"> {{ i.seg_res | decimal1 }} </td>
                    <td class="text-right " title="COMISION RESTANTE"> {{ i.com_res | decimal1 }} </td>
                    <td class="text-center " title="CUOTA RESTANTE" v-if="i.monto_cancelado > 0"> 0 </td>
                    <td class="text-center " title="CUOTA RESTANTE" v-else> {{i.cant_cuotas_res}} </td> -->
                  </tr>
                  <tr style="font-weight: bold;">
                    <td colspan="2" class="text-center">TOTAL :</td>
                    <!-- <td class="text-right"></td> -->
                    <td class="text-right">{{ montofi | decimal1 }}</td>
                    <td class="text-right">{{total_deuda_total | decimal1}}</td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    
                    <td></td>
                   
                    <td class="text-right">{{total_pagado | decimal1}}</td>   
                    <td class="text-right">{{totalamor| decimal1}}</td>
                    <td class="text-right">{{totalade | decimal1}}</td>                   
                    <td class="text-right"> {{total_cancelado | decimal1}} </td>
                   
                    <td class="text-right">  </td>
                    <td class="text-right"> {{total_total | decimal1}} </td>
                    <td class="text-right">{{total_deuda | decimal1}}</td>
                    <td class="text-right">{{total_interes | decimal1}}</td>
                    <td class="text-right">{{ t_int_pago | decimal1}}</td>
                    <td class="text-right" >{{total_nopagado | decimal1}}</td>
                    
                    
                   
                    
                   
                    <td></td>
                    <!-- <td class="text-right">{{salres | decimal1}}</td>
                    <td class="text-right">{{intres | decimal1}}</td>
                    <td class="text-right">{{segres | decimal1}}</td>
                    <td class="text-right">{{comres | decimal1}}</td> 
                    <td></td> -->
                    
                  </tr>
                </tbody>
                <!-- <tfoot>
                </tfoot> -->
              </table>
             
              
                        
            </div>
  </div>
</template>

<script>
  import VueAutonumeric from 'vue-autonumeric'
  import Loading from 'vue-loading-overlay'
  import moment from 'moment'
import M from 'minimatch'
    export default {
        components:{
            Loading
        },
        data() {
            return {

              //totales
              redondeo:0,
              totalredon:0,
              total_deuda:0,
              total_deuda_total:0,
              total_pagado:0,
              total_nopagado:0,
              total_cancelado :0,
              total_total:0,
              total_interes:0,
              t_int_pago:0,
              intres:0,
              segres:0,
              comres:0,
              salres:0,
              montofi:0,
              totalade:0,
              totalamor:0,
              

              //
              isLoading: false,
              fullPage: true,
              calcTot:0,
              comi:0,
              segu:0,
              inte:0,
              cance:0,
              cap:0,

              totalmonto:0,
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
              fecha:'',
              anio:moment().format('YYYY'),
              calificaciones:[],
              columna:['analista','cum_meta_porc_total','nocobro','cum_riesgo_porc_total','rentabilidad', 'cum_calidad_porc_total', 'total'],  
              options: {
                        headings: {
                          cum_calidad_porc_total:'Calidad de Proceso',
                          cum_meta_porc_total:'Meta Comercial',
                          cum_riesgo_porc_total:'Meta de Riesgo',
                          rentabilidad:'Meta de Rentabilidad',
                          nocobro:'no cobrado'
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
            axios.get('/api/reportemensual',{params:{anio:this.anio}})
            .then(({data}) => {
              console.log('entra');
              this.data = data;
                 let calcTot = 0;
                 let inte = 0;
                 let cance = 0;
                 let segu = 0;
                 let comi = 0;
                 let cap = 0;
                 let totalmonto=0;
                 let redondeo =0;
                 let ade =0;
                 let amor =0;
                 

                 
               this.data.map(i=>{
                
                calcTot +=  parseFloat(i.total.replace(',', ''));
                inte +=  parseFloat(i.interes.replace(',', ''));
                cance +=  parseFloat(i.cancelado.replace(',', ''));
                comi +=  parseFloat(i.comision.replace(',', ''));
                segu +=  parseFloat(i.seguro.replace(',', ''));
                cap +=  parseFloat(i.cap_amor.replace(',', ''));
                redondeo +=  parseFloat(i.redondeo.replace(',', ''));
                ade += parseFloat(i.adelanto.replace(',', ''));
                amor += parseFloat(i.amortiza.replace(',', ''));
                // }else{
                //   redondeo +=  parseFloat(i.redondeo.replace(',', ''))+parseFloat(i.credondeo.replace(',', ''));
                // }
                totalmonto += parseFloat(i.monto.replace(',', ''));
              })
              this.calcTot = new Intl.NumberFormat('es-MX').format(calcTot);
              this.inte = new Intl.NumberFormat('es-MX').format(inte);
              this.cance = new Intl.NumberFormat('es-MX').format(cance);
              this.segu = new Intl.NumberFormat('es-MX').format(segu);
              this.comi = new Intl.NumberFormat('es-MX').format(comi.toFixed(2))
              this.cap = new Intl.NumberFormat('es-MX').format(cap.toFixed(2))
              this.totalmonto = new Intl.NumberFormat('es-MX').format(totalmonto.toFixed(2))
              this.totalredon = new Intl.NumberFormat('es-MX').format(redondeo.toFixed(2))
              this.totalade = new Intl.NumberFormat('es-MX').format(ade.toFixed(2))
              this.totalamor = new Intl.NumberFormat('es-MX').format(amor.toFixed(2))

            }).catch((err) => {
              console.log(err);
            });
          },
          getReportsave(){
            if (this.data.length > 0) {
              axios.post('/api/savereportdeudarestante',{fecha:this.fecha,datos:this.data})
              .then(({data}) => {
                Swal.fire(
                        'Guardado',
                        'Se Guardo el Reporte',
                        'success'
                        );
              }).catch((err) => {
                console.log(err);
              });
            }else{

            }
            
          },
          getReport(){
            axios.get('/api/reportdeudarestante',{params:{fecha:this.fecha}})
            .then(({data}) => {
              console.log(data);
              this.total_deuda_total=0;
              this.total_pagado=0;
              this.total_nopagado=0;
              this.total_deuda=0;
              this.total_cancelado=0;
              this.total_total = 0;
              this.total_interes=0;
              this.t_int_pago=0;
              this.intres=0;
              this.segres=0;
              this.comres=0;
              this.salres=0;
              this.montofi=0;
              this.totalredon=0;
              this.totalade=0;
              this.totalamor = 0;
              data.map(i=>{
                this.total_deuda_total += i.total_deuda;
                
                //this.total_pagado += i.total_pagado;
                this.total_interes += i.interes_pagado;
                this.intres += i.int_res;
                this.segres += i.seg_res;
                this.comres += i.com_res;
                
                this.montofi += i.monto_financiado;
                this.totalade += i.adelanto;
                //i.sal_res = i.sal_res-i.adelanto;
                if(i.adelanto > 0){
                  i.total_pagado = i.total_pagado-i.adelanto ;
                }
               
                i.monto_res=i.monto_res-i.adelanto;
                if (i.amortiza > 0) {
                  this.totalamor += parseFloat(i.amortiza);
                  i.total_pagado = i.por_cobrar-i.amortiza-i.adelanto;
                }
                  if(i.redondeo === null){

                    i.total = parseFloat(i.total_pagado) + parseFloat(i.monto_cancelado) + i.adelanto + i.amortiza;
                  }else{
                    if (i.redo > 0) {
                      i.total = parseFloat(i.total_pagado)  + parseFloat(i.monto_cancelado) + i.adelanto + i.amortiza ;
                    }else{
                      i.total = parseFloat(i.total_pagado) + parseFloat(i.monto_cancelado) + i.adelanto + i.amortiza;
                    }
                    
                  }
                //i.total = i.total + i.adelanto;
                if (i.monto_cancelado > 0) {
                  this.total_deuda += 0;
                  
                  if (i.amortiza > 0) {
                    i.nocobro = parseFloat(i.total_deuda) - (parseFloat(i.total_pagado)+parseFloat(i.monto_cancelado)+parseFloat(i.amortiza));
                  }else{
                    i.nocobro = parseFloat(i.total_deuda) - (parseFloat(i.total_pagado)+parseFloat(i.monto_cancelado)+i.adelanto);
                  }
                  //i.redo=0;
                } else {
                  if (i.amortiza > 0) {
                    i.monto_res = parseFloat(i.total_deuda)-i.total;
                  }
                  i.nocobro = 0;

                  this.total_deuda += i.monto_res;
                }
                if (i.id == 36) {
                  // i.monto_res =i.cuota*i.cant_cuotas_res;
                  // this.total_deuda += 1490.98;
                } 
                if (i.interes_cancelado > 0) {
                  this.t_int_pago += parseFloat(i.interes_cancelado);
                }else{
                  this.t_int_pago += 0;
                }
                this.salres += i.sal_res;
                this.total_pagado += i.total_pagado;
                this.totalredon += i.redo;
                this.total_nopagado += i.nocobro;
                this.total_total += parseFloat(i.total_pagado) + parseFloat(i.monto_cancelado) + i.adelanto + i.amortiza;
                this.total_cancelado += parseFloat(i.monto_cancelado);
              
                
              });
              this.data = data;
            }).catch((err) => {
              console.log(err);
            });
          },
          sumar(){

          },
          print(){
            let footer = document.getElementsByTagName('footer')[0]
                footer.style = 'display:none';
                var css = "@page {size: A4 landscape;   margin: 2rem 1rem 1.5rem 1rem !important; padding: 0 0 0 0 !important} .table{width:100% !important; border-color: #000000 !important; } th{padding:0 !important; font-size:8px !important;border: solid 2px; border-color: #000000;} td{padding:0 !important; font-size:8px !important;border: solid 2px; border-color: #000000 !important; color:#000000 !important;vertical-align:middle !important;} ",
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
          //  this.getReport();
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
.cabe {
  background: white !important;
}
thead tr th { 
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #ffffff;
        }
    
        .table-responsive { 
            height:100%;
            overflow:scroll;
        }
</style>
