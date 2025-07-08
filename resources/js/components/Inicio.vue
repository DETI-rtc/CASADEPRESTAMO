<template>
    <div class="">
        <section class="content">
      <div class="container-fluid">
          <div class="row">


            <div class="card">
              <div class="card-body">
                 <div class="row">
                      <div class="row col-4 align-items-center">
              <div class="col-auto">
                <label for="inputPassword6" class="col-form-label">Seleccione Año</label>
              </div>
              <div class="col-auto">
                <select class="form-control" v-model="ano" @change="selingreso($event)" >SELCCIONE AÑO
                    <option v-for="(peri, index) in periodos" v-bind:value="peri.periodo" :name="peri.periodo" v-bind:key ="index">
                        {{ peri.periodo }}
                      </option>
                  </select>
              </div>
              
                </div>
                <div class="row col-4 align-items-center">
                  <div class="col-auto">
                    <label for="inputPassword6" class="col-form-label">Seleccione Mes</label>
                  </div>
                  <div class="col-auto">
                    <select class="form-control" v-model="feca3" @change="selingredia($event)" > SELECIONE MES
                        <option value="01">Enero</option>
                        <option value="02">Febrero</option>
                        <option value="03">Marzo</option>
                        <option value="04">Abril</option>
                        <option value="05">Mayo</option>
                        <option value="06">Junio</option>
                        <option value="07">Julio</option>
                        <option value="08">Agosto</option>
                        <option value="09">Setiembre</option>
                        <option value="10" >Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                      </select>
                  </div>
                  
                </div>
                 </div>
              </div>
            </div>
            


            
          </div>
     
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-6 connectedSortable">
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title" style="font-size: 20px !important;">
                 <i class="fas fa-chart-line"></i>
                 Ingresos Anuales 
                </h3>
                
              </div><!-- /.card-header -->
              <div class="card-body">
                <bar-chart :chart-data="datahistoria" :height="100" :options="{responsive:true,maintainAspectRation:false,tooltips: { callbacks: { label: function(t, d) {
                    var xLabel = d.datasets[t.datasetIndex].label;
                    var yLabel = t.yLabel >= 10000 ? 'S/ ' + t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') : 'S/ ' + t.yLabel;
                    return xLabel + ': ' + yLabel; }}}, scales: {yAxes: [{
                        ticks: {
                              beginAtZero: true, 
                              callback: function(value, index, values) {
                                  if (parseInt(value) >= 5000) {
                                    return 'S/ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                  } else {
                                    return 'S/ ' + value;
                                  }
                              }
                            }
                      }]
                    }}">
                </bar-chart>
               
              </div>
            </div>           
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title" style="font-size: 20px !important;padding-top: 7px;">
                  <i class="fas fa-chart-bar"></i>
                 Ingresos x Categorias
                </h3>
                
              </div><!-- /.card-header -->
              <div class="card-body">
                <bar-chart :chart-data="datacollection"  :height="100" :options="{responsive:true,maintainAspectRation:false,tooltips: { callbacks: { label: function(t, d) {
                      var xLabel = d.datasets[t.datasetIndex].label;
                      var yLabel = t.yLabel >= 1000 ? ' S/ ' + t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') : ' S/ ' + t.yLabel;
                      return xLabel + ': ' + yLabel; }} }, scales: {
                      yAxes: [{
                        ticks: {
                                    //min: 0,
                              //stepSize: 200000,
                              beginAtZero: true, 
                              callback: function(value, index, values) {
                                  if (parseInt(value) >= 500) {
                                    return 'S/ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                  } else {
                                    return 'S/ ' + value;
                                  }
                              }
                            }
                      }]}}">
                </bar-chart>               
              </div>
            </div>
            <div class="card card-indigo">
              <div class="card-header border-0">
                <h3 class="card-title" style="font-size: 20px;padding-top: 7px;">
                  <i class="fas fa-cash-register"></i>Ingreso Diario x Mes 
                </h3>
                               
              </div>
              <div class="card-body">
                  <line-chart :chart-data="datacollection3" :height="100"  :options="{responsive:true,maintainAspectRation:false,tooltips: { callbacks: { label: function(t, d) {
                    var xLabel = d.datasets[t.datasetIndex].label;
                    var yLabel = t.yLabel >= 500 ? 'S/ ' + t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') : 'S/ ' + t.yLabel;
                    return xLabel + ': ' + yLabel;
                    }} }, scales: {
                    yAxes: [{
                      ticks: {
                            //     min: 0,
                            //stepSize: 200000,
                            beginAtZero: true, 
                            callback: function(value, index, values) {
                                if (parseInt(value) >= 500) {
                                  return 'S/ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                } else {
                                  return 'S/ ' + value;
                                }
                            }
                          }
                    }]}}">
                  </line-chart>
              </div>  
            </div>

              <div class="card card-success col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="card-header border-0">
                <h2 class="card-title" style="font-size: 22px;" >
                  <i class="fab fa-buromobelexperte"></i> Reporte de Pabellones
                </h2>
                <div class="card-tools">
                </div>
                <!-- /. tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body pt-2">
                  <v-client-table id="tablapabe" :data="pabellones" :columns="columna" :options="options" ></v-client-table>
              </div>
              <!-- /.card-body -->
            </div>
            <div class="card card-warning col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="card-header border-0">
                  <h3 class="card-title" style="font-size: 22px;">
                      <i class="fas fa-church"></i> Reporte x Tipo de Nichos
                  </h3>
                  <div class="card-tools">
                  </div>
                
              </div>
              <div class="card-body pt-0">
                  <v-client-table :data="tipopabellon" :columns="columna3" :options="options3" >
                   <div slot="nombre" slot-scope="{row}" >
                          <div v-if = "row.nombre == 'TOTAL'" style="text-align: right;font-size: 14px;">
                              <b>{{row.nombre}}</b>
                          </div>
                          <div v-else>
                             <b> {{row.nombre}}</b>
                          </div>
                        </div>
                        <div slot="vacio" slot-scope="{row}" >
                          <div v-if = "row.nombre == 'TOTAL'" style="text-align: right;font-size: 14px;">
                              <b>{{row.vacio}}</b>
                          </div>
                          <div v-else style="text-align: right;">
                              {{row.vacio}}
                          </div>
                        </div>
                        <div slot="ocupado" slot-scope="{row}" >
                          <div v-if = "row.nombre == 'TOTAL'" style="text-align: right;font-size: 14px;">
                              <b>{{row.ocupado}}</b>
                          </div>
                          <div v-else style="text-align: right;">
                              {{row.ocupado}}
                          </div>
                        </div>
                        <div slot="reservado" slot-scope="{row}" >
                          <div v-if = "row.nombre == 'TOTAL'" style="text-align: right;font-size: 14px;">
                              <b>{{row.reservado}}</b>
                          </div>
                          <div v-else style="text-align: right;">
                              {{row.reservado}}
                          </div>
                        </div>
                        <div slot="vacio10a" slot-scope="{row}" >
                          <div v-if = "row.nombre == 'TOTAL'" style="text-align: right;font-size: 14px;">
                              <b>{{row.vacio10a}}</b>
                          </div>
                          <div v-else style="text-align: right;">
                              {{row.vacio10a}}
                          </div>
                        </div>
                        <div slot="vacio15a" slot-scope="{row}" >
                          <div v-if = "row.nombre == 'TOTAL'" style="text-align: right;font-size: 14px;">
                              <b>{{row.vacio15a}}</b>
                          </div>
                          <div v-else style="text-align: right;">
                              {{row.vacio15a}}
                          </div>
                        </div>
                        <div slot="vacio16a" slot-scope="{row}" >
                          <div v-if = "row.nombre == 'TOTAL'" style="text-align: right;font-size: 14px;">
                              <b>{{row.vacio16a}}</b>
                          </div>
                          <div v-else style="text-align: right;">
                              {{row.vacio16a}}
                          </div>
                        </div>
                        <div slot="total" slot-scope="{row}" >
                          <div v-if = "row.nombre == 'TOTAL'" style="text-align: right;font-size: 14px;">
                              <b>{{row.total}}</b>
                          </div>
                          <div v-else style="text-align: right;">
                              {{row.total}}
                          </div>
                        </div>
                  </v-client-table>
              </div>
            </div>
            
          </section>
         <section class="col-lg-6 connectedSortable">

             <div class="card card-primary col-12">
              <div class="card-header">
                <h3 class="card-title" style="font-size: 20px !important;padding-top: 7px;">
                  <i class="fa fa-chart-bar"></i> Ingresos de Servicios y Productos
                </h3>
              </div>
              <div class="card-body">
                <bar-chart :chart-data="datacollectionps" :height="100" :options="{responsive:true,maintainAspectRation:false,tooltips: {
                             callbacks: {
                                label: function(t, d) {
                                   var xLabel = d.datasets[t.datasetIndex].label;
                                   var yLabel = t.yLabel >= 1000 ? 'S/ ' + t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') : 'S/ ' + t.yLabel;
                                   return xLabel + ': ' + yLabel;
                                }
                             }
                          }, scales: {
                          yAxes: [{
                            ticks: {
                                        //min: 0,
                                   //stepSize: 200000,
                                   beginAtZero: true, 
                                  
                                   callback: function(value, index, values) {
                                      if (parseInt(value) >= 500) {
                                         return 'S/ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                      } else {
                                         return 'S/ ' + value;
                                      }
                                   }
                                }
                          }]
                        }}">
                </bar-chart>
              </div>
            </div>
           
             <div class="card card-success col-12">
              <div class="card-header">
                <h3 class="card-title" style="font-size: 20px !important;padding-top: 7px;"><i class="fas fa-user-clock"></i> Ingreso x Sericios</h3>
              </div>
             <div class="card-body">
                <div class="row">
                      <div class="col-8">
                           <bar-chart :chart-data="dataservicios" :height="180"  :options="{responsive:true,maintainAspectRation:false,tooltips: {
                         callbacks: {
                            label: function(t, d) {
                               var yLabel = d.datasets[t.datasetIndex].label;
                               var xLabel = t.xLabel >= 1000 ? 'S/ ' + t.xLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') : 'S/ ' + t.xLabel;
                               return yLabel + ': ' + xLabel;
                                    }
                                 }
                              }, scales: {
                              yAxes: [{
                                ticks: {
                                       fontSize: 8,
                                       beginAtZero: true, 
                                       callback: function(value, index, values) {
                                          if (parseFloat(value) >= 500) {
                                             return 'S/ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                          } else {
                                             return 'S/ ' + value;
                                          }
                                       }
                                    }
                              }],xAxes: [{
                                ticks: {
                                       fontSize: 10,
                                       
                                    }
                              }]
                            
                            }}"  >
                            </bar-chart>
                      </div>
                      <div class="col-4">
                              <table class="table table-sm">
                                    <thead>
                                      <tr>
                                        <th style="width: 10px">Nro</th>
                                        <th>Servicio</th>
                                        <th>Cantidad</th>
                                        <th style="width: 40px">Total</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr v-for="(nic, index) in nroservmes" v-bind:key="index" style="font-size: 11px;" >
                                        <td >{{index+1}}</td>
                                        <td >{{nic.producto | upText }}</td>
                                        <td>{{nic.nro}}</td>
                                        <td style="text-align: right;">{{nic.total| Money }}</td>
                                      </tr>
                                      
                                      <tr style="font-weight: bold;">
                                        
                                        <td colspan="2" style="text-align: center;">TOTAL</td>
                                        <td>{{totalNum}}</td>
                                        <td>{{totalsermes | toCurrency}}</td>
                                      </tr>
                                      
                                    </tbody>
                                  </table>
                      </div>
                  </div>
              </div>
            </div>
            <div class="card card-info col-12">
              <div class="card-header">
                <h3 class="card-title" style="font-size: 20px;padding-top: 7px;">
                  <i class="fas fa-id-card-alt"></i>Ingresos x Productos
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                <div class="col-5">
                      <bar-chart1 :chart-data="datacollectionp" :height="600"  :options="{responsive:false,maintainAspectRation:false,tooltips: {
                         callbacks: {
                            label: function(t, d) {
                               var yLabel = d.datasets[t.datasetIndex].label;
                               var xLabel = t.xLabel >= 2000 ? 'S/ ' + t.xLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') : 'S/ ' + t.xLabel;
                               return yLabel + ': ' + xLabel;
                                    }
                                 }
                              }, scales: {
                              xAxes: [{
                                ticks: {
                                        min: 0,
                                       stepSize: 2000,
                                       beginAtZero: true, 
                                       callback: function(value, index, values) {
                                          if (parseFloat(value) >= 1000) {
                                             return 'S/ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                          } else {
                                             return 'S/ ' + value;
                                          }
                                       }
                                    }
                              }],
                              yAxes: [{
                                ticks: {
                                        fontSize: 10,
                                        font: function(context) {
                                          var width = context.chart.width;
                                          var size = Math.round(width / 32);

                                          return {
                                              weight: 'bold',
                                              size: size
                                          };
                                      }
                                    }
                              }]
                            }}">
                      </bar-chart1>
                </div>
                <div class="col-7">
                  <table class="table table-sm">
                  <thead>
                    <tr>
                      <th style="width: 10px">Nro</th>
                      <th>Producto</th>
                      <th>Cantidad</th>
                      <th style="width: 50px">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(nic, index) in nroprodmes" v-bind:key="index" style="font-size: 11px;" >
                      <td >{{index+1}}</td>
                      <td >{{nic.producto}}</td>
                      <td>
                        
                          {{nic.nro}}
                        
                      </td>
                      <td style="text-align: right;">{{nic.total| Money }}</td>
                    </tr>
                    
                    <tr style="font-weight: bold;font-size: 11px;">
                      
                      <td colspan="2" style="text-align: center;">TOTAL</td>
                      <td>{{ totalnumpro }}</td>
                      <td>{{totalmespro | toCurrency}}</td>
                      
                    </tr>
                    
                  </tbody>
                </table>

                </div>

              </div>

              </div>
            
              
            </div>
            <div class="card card-info">
              <div class="card-header border-0">
                <h3 class="card-title" style="font-size: 20px;">
                  <i class="fas fa-bible"></i>
                  ESTADOS DE NICHOS
                </h3>

                <div class="card-tools">
                  
                </div>
              </div>
              <div class="card-body">
                <pie-chart :chart-data="datacollection4" :height="200" :options="{plugins: {
      datalabels: {
        formatter: (value) => {
          return value + '%dddddd';
        }
      }
                }}"></pie-chart>
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-white p-2">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th width="10%">Nro</th>
                      <th width="40%">Estado</th>
                      <th width="15%" style="text-align: center;">Cantidad</th>
                      <th width="35%" style="text-align: right;">%</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(nic, index) in nicho"  v-bind:key="index" >
                      <td >{{index+1}}</td>
                      <td >{{nic.nombre}}</td>
                      <td style="text-align: right;">{{nic.nro}}</td>
                      <td style="text-align: right;"><span class="badge" :style="{background:nic.color}" style="color: #fff!important;" >{{nic.porce }}%</span></td>
                    </tr>  
                  </tbody>
                </table>  
              </div>
            </div> 
          </section>          
        </div>        
      </div>
    </section>
    </div>
</template>

<script>
    import BarChart from './BarChart.js'
    import BarChart1 from './HorisontalBarChart.js'
    import LineChart from './LineChart.js'
    import PieChart from './PieChart.js'
    import moment from 'moment'
    
    export default {
        components: {
            
            BarChart,
            BarChart1,
            LineChart,
            PieChart
           
          },
          data(){
            return {
              datacollection: [],
              datahistoria: [],
              periodos:[],
              datacollectionps: [],
              dataservicios: [],
              datacollectionp: [],
              datacollection3: [],
              datacollection4: [],
              nroservmes:[],
              nroprodmes:[],
              nicho: [],
              pabellones:[],
              tipopabellon:[],
              mestipocomp:[],
              tipocomp:{
                bole:'',
              },
              dtraba:{},
              feca:'',
              feca1:'',
              ano:'',
              feca2:'',
              feca3:'',
              feca4:'',
              gradient:null,
              columna:['nombre','vacio','ocupado','reservado','vacio10a','vacio15a','vacio16a','total'],  
              options: {
                          headings: {
                                nombre: 'PABELLONES',
                              },
                          perPage:10,
                          perPageValues:[25,50,100],
                          skin:'table display compact hover',
                          filterable: ['nombre'],
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
                columna3:['nombre','vacio','ocupado','reservado','vacio10a','vacio15a','vacio16a','total'],  
              options3: {
                          headings: {
                                nombre: 'NICHO',
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table display compact hover',
                          filterable: false,
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
                      
            }
          },
        mounted() {
            this.loadUsers()
        },
        methods: {

            loadUsers(){ 
                         this.ano=moment().format("YYYY");
                         this.feca=moment().format("MM");
                         this.feca1=moment().format("MM");
                         this.feca2=moment().format("MM");
                         this.feca3=moment().format("MM");
                        axios.get("api/cemeningre",{params:{'fecha':this.ano}}).then(({ data }) => (this.datacollection = data)).catch(error => { this.error = error.response });
                        axios.get("api/cemeningreps",{params:{'fecha':this.ano}}).then(({ data }) => ( this.datacollectionps = data)).catch(error => { this.error = error.response });
                        axios.get("api/cemeningredia").then(({ data }) => (this.datacollection3 = data.dia,this.dataservicios = data.servicio, this.nroservmes=data.nroservi,this.datacollectionp = data.producto, this.nroprodmes=data.nroprod)).catch(error => { this.error = error.response });
                        axios.get("api/cemennichos").then(({ data }) => (this.datacollection4 = data)).catch(error => { this.error = error.response });
                        axios.get("api/alpabellones").then(({ data }) => (this.pabellones = data)).catch(error => { this.error = error.response });
                        axios.get("api/tipopabellon").then(({ data }) => (this.tipopabellon = data)).catch(error => { this.error = error.response });
                        
                        // axios.get("api/alnicho").then(({ data }) => (this.nicho = data)).catch(error => { this.error = error.response });
                        // axios.get("api/tipocomp").then(({ data }) => (this.tipocomp = data)).catch(error => { this.error = error.response });
                        axios.get("api/mestipocomp").then(({ data }) => (this.mestipocomp = data)).catch(error => { this.error = error.response });
                        axios.get("api/cemehistoria").then(({ data }) => (this.datahistoria = data)).catch(error => { this.error = error.response });
                        axios.get("api/periodos").then(({ data }) => (this.periodos = data)).catch(error => { this.error = error.response });
                        //axios.get("api/dtrabajadores").then(({ data }) => (this.dtraba = data)).catch(error => { this.error = error.response });

            },
            selingreso(event){
                axios.get("api/cemeningre",{params:{'fecha':event.target.value}}).then(({ data }) => (this.datacollection = data.mes, this.datacollectionps = data.prodserv)).catch(error => { this.error = error.response });
                //  axios.get("api/tipocomp",{params:{'fecha':event.target.value}}).then(({ data }) => (this.tipocomp = data)).catch(error => { this.error = error.response });
            },
            selmestipo(event){
                axios.get("api/mestipocomp",{params:{'fecha':event.target.value}}).then(({ data }) => (this.mestipocomp = data)).catch(error => { this.error = error.response });
                
            },
            selingmes(event){
                axios.get("api/cemeningreser",{params:{'fecha':event.target.value}}).then(({ data }) => (this.datacollection1 = data)).catch(error => { this.error = error.response });
                axios.get("api/nroservmes",{params:{'fecha':event.target.value}}).then(({ data }) => (this.nroservmes = data)).catch(error => { this.error = error.response });
                
            },
            selinprogmes(event){
                axios.get("api/cemeningrepro",{params:{'fecha':event.target.value}}).then(({ data }) => (this.datacollection2 = data)).catch(error => { this.error = error.response });
                axios.get("api/nroprodmes",{params:{'fecha':event.target.value}}).then(({ data }) => (this.nroprodmes = data)).catch(error => { this.error = error.response });
            },
            selingredia(event){
                axios.get("api/cemeningredia",{params:{'fecha':event.target.value}}).then(({ data }) => (this.datacollection3 = data.dia,this.dataservicios = data.servicio, this.nroservmes=data.nroservi,this.datacollectionp = data.producto, this.nroprodmes=data.nroprod)).catch(error => { this.error = error.response });
                
            }
    },
    computed: {
                totalsermes: function () {
                    return this.nroservmes.reduce(function (total, value) {
                        return total + Number(value.total);
                    }, 0);
                    
                },
                
                totalNum: function(){
                  return this.nroservmes.reduce(function(total, item){
                      return total + item.nro; 
                  },0);
                },

                totalmespro: function(){
                  return this.nroprodmes.reduce(function(total, item){
                      return total + Number(item.total); 
                  },0);
                },
                totalnumpro: function(){
                  return this.nroprodmes.reduce(function(total, item){
                      return total + item.nro; 
                  },0);
                },
            },
        created(){
          document.title = `CASA DE PRESTAMOS`;
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
.VueTables__search {
    float: left !important;
  }
.VueTables__limit{
  float: right !important;
}
.error{
  width: 100%;
    margin-top: .25rem;
    font-size: 80%;
    color: #dc3545;
}
</style>
