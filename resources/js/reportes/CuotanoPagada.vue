<template> 
    <div class="clase">
        
         <div class="card card-info">
              <div class="card-header no-print">
                  <h3 class="card-title " style="font-size: 24px !important;"> <i class="fas fa-users mr-2" >
                    </i> Reporte Cuotas No Canceladas</h3>
                      <div class="card-tools">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button class="btn btn-warning" @click="print">Imprimir</button>
                            <button class="btn btn-primary" @click="Guardaobserva">Guardar</button>
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
                  <h3>RELACION DE CUOTAS NO PAGADAS AL <template v-if="mes"> {{mes.label | upText}} </template> DEL {{ anio }}</h3>
                </div>
                <table class="table table-bordered">
                  <thead>
                    
                    <tr class="text-center">
                      <th width="3%">Item</th>
                      <th width="25%">Cliente</th>
                      <th width="25%">Convenio/Entidad</th>
                      <th width="6%" >Nro de Cuota</th>
                      <th width="10%">Fecha Vencimiento</th>
                      <th width="9%">Monto Cuota</th>
                      <th width="9%">Pago a Cuenta</th>
                      <th width="9%">Pendiente de Pago</th>
                      <th width="9%">Observacion</th>
                                           
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="(i,index) in data" :key="i.id">
                      <td class="text-center"> {{ index + 1 }} </td>
                      <td class="text-left"> {{i.nom_comp}} </td>
                       <td class="text-center">{{i.empresa}} </td>
                       <td class="text-center">{{i.cuotanro}} </td>
                       <td class="text-center  font-weight-bold"> {{i.fecha_ven | myDate}} </td>
                       <td class="text-right"> {{ i.cuota | decimal}} </td>
                      <td class="text-right text-primary font-weight-bold"> {{ i.mon_pag | decimal}} </td>
                      <td class="text-right"> {{i.saldo_ade | decimal}} </td>
                      <td class="text-right"> <textarea name="obs"  cols="30" rows="2" v-model="i.observa"></textarea> </td>
                      
                    </tr>
                  </tbody>
                  <tfoot v-if="data.length > 0">
                    <tr>
                      <td colspan="5" class="text-center">TOTAL :</td>
                      <td class="text-right font-weight-bold">{{totalc}}</td>
                      <td class="text-right font-weight-bold">{{totala}}</td>
                      <td class="text-right font-weight-bold">{{totals}}</td>
                                            
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
                totald:0,
                totali:0,
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
                
  
              }
          },
         
          methods: {
            loadUsers(){
              axios.get("/api/periodos")
                .then(({ data }) => (this.periodos = data)).catch(error => { this.error = error.response });
            },
            Guardaobserva(){
              axios.post('/api/guardar',{params:{datos:this.data}}).then(({data}) => {
                 console.log(data);
                
  
              }).catch((err) => {
                console.log(err);
              });

            },
  
            get(){
              if (this.anio == null || this.anio == '') {
                return;
              }
              axios.get('/api/reportecuotano',{params:{anio:this.anio, mes:this.mes.value}})
              .then(({data}) => {
                this.data = data;
                let total=0;
                let ade=0;
                let sald=0;
                 this.data.map(i=>{
                  total += i.cuota;
                  ade += i.mon_pag;
                  if (i.saldo_ade > 0) {
                    
                  }else{
                    i.saldo_ade = i.cuota;
                  }
                  sald += i.saldo_ade;
                })
                
                this.totalc = new Intl.NumberFormat('es-MX').format(total.toFixed(2)),
                this.totala = new Intl.NumberFormat('es-MX').format(ade.toFixed(2)),
                this.totals = new Intl.NumberFormat('es-MX').format(sald.toFixed(2))
  
              }).catch((err) => {
                console.log(err);
              });
            },
           
            print(){
              let footer = document.getElementsByTagName('footer')[0]
                  footer.style = 'display:none';
                  var css = "@page { size: A4 ; font-family: '8pin-matrix', arial;  margin: 2rem 1rem 2rem 0rem !important; padding: 0 0 0 0 !important} .table{width:100% !important} .tds{padding:0 0.1rem 0 0.1rem !important; }",
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
  
  