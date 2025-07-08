<template>
    <div class="">
        <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-2 col-md-6">
            <!-- small box -->
            <div class="small-box bg-blue">
              <div class="inner">
                <h3 class="mb-0 ">{{nrocredito  }}</h3>

                <p class="mb-0 ">Nro de Creditos</p>
              </div>
              <div class="icon">
                <i class="fa fa-users"></i>
              </div>
              <router-link to="/trabajadores" class="small-box-footer">Mas Informacion <i class="fas fa-arrow-circle-right"></i></router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-md-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 class="mb-0 text-center">{{mtocredito | currency}}<sup style="font-size: 20px"></sup></h3>

                <p class="mb-0 ">Monto de Creditos</p>
              </div>
              <div class="icon">
                <i class="fa fa-money-bill-alt"></i>
              </div>
              <router-link to="/creditos" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>              
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-md-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 class="mb-0 " >{{ nrodesembolso }}</h3>

                <p class="mb-0 ">Nro de Desembolsos</p>
              </div>
              <div class="icon">
                <i class="fa fa-female" aria-hidden="true"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-md-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 class="mb-0 ">{{nrocliente | currency}}</h3>

                <p class="mb-0 ">Monto Desembolsado</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-2 col-md-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 class="mb-0 " >{{nrocredcan}}</h3>

                <p class="mb-0 ">Nro de Creditos Cancelados</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-edit"></i>
                
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-8 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">
                   <i class="fas fa-chart-area"></i>
                 
                  Historial de Creditos y Pagos Mensual
                </h3>
                <div class="card-tools">
                  <select class="form-control"> Año
                    <option>2021</option>
                    
                  </select>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                
                <area-chart :chart-data="datacollection" :height="130" :options="{responsive:true,maintainAspectRation:true}"></area-chart>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- DIRECT CHAT -->
            <div class="card card-outline card-dark">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> Historial Meta vs Credito Mensual</h3>                
              </div>
              
              <div class="card-body">              
                  
              </div>              
            </div>           
          </section>          
          <section class="col-lg-4 connectedSortable">
            <!-- Map card -->
            <div class="card card-primary">
              <div class="card-header border-0">
                <h3 class="card-title">
                   <i class="fas fa-chart-pie mr-1"></i>
                  Avance de Meta X Mes
                </h3>
                <!-- card tools -->
                <div class="card-tools">
                  <div class="card-tools">
                  <select class="form-control"> Mes
                    <option>Enero</option>
                    <option>Febrero</option>
                    <option>Marzo</option>
                    <option>Abril</option>
                    <option>Mayo</option>
                    <option>Junio</option>
                    <option>Julio</option>
                    <option>Agosto</option>
                    <option>Setiembre</option>
                    <option>Octubre</option>
                    <option>Noviembre</option>
                    <option>Diciembre</option>
                  </select>
                </div>
                  
                </div>
                <!-- /.card-tools -->
              </div>
              <div class="card-body">
                  <pie-chart :chart-data="datacollection2" :height="200" :options="{responsive:true,maintainAspectRation:true}"></pie-chart>

              </div>
               <div class="card-footer bg-white p-2">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th width="35%"class="text-center">Meta Mes</th>
                      <th width="35%" class="text-center">Avance</th>
                      <th width="30%" class="text-center">Diferencia</th>
                    </tr>
                  </thead>
                  <tbody>
                    

                    <tr>

                      <td class="text-center">{{cuadro.meta | currency}}</td>
                      <td class="text-center">{{cuadro.monto}}</td>
                      <td class="text-center">{{cuadro1.monto}}</td>
                    </tr>
                    <tr>
                      <td><span class="badge"> 100 %</span></td>
                        <template>
                          
                        </template>
                       <td><span class="badge"  :style="{background:cuadro.color}"style="color: #fff!important;;" >{{cuadro.nro}}%</span></td>
                       <td><span class="badge"  :style="{background:cuadro1.color}" style="color: #fff!important;;" >{{cuadro1.nro}}%</span></td>
                       

                    </tr>
                   
                    
                    
                   
                    
                  </tbody>
                </table>
                
              </div>
              <!-- /.card-body-->
              
            </div>
            <!-- /.card -->
              <div class="card card-success">
              <div class="card-header border-0">

                <h3 class="card-title">
                  <i class="far fa-calendar-alt"></i>
                  Calendar
                </h3>
                <!-- tools card -->
                </div>
              <!-- /.card-header -->
              <div class="card-body pt-0">
                <!--The calendar -->
                <calendario></calendario>
              </div>
              <!-- /.card-body -->
            
          </div>
         
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    </div>
</template>
<script>

import LineChart from './LineChart.js'
import BarChart from './BarChart.js'
import PieChart from './PieChart.js'
import AreaChart from './AreaChart.js'
import AreaChart1 from './Area2dChart.js'
export default {
  components: {
    BarChart, LineChart,  PieChart, AreaChart, AreaChart1
  },
  data(){
    return {
      datacollection: [],
      datacollection1: [],
      datacollection2: [],
      datacollection3: [],
      cuadro:[],
      cuadro1:[],
      nrocliente:'',
      nrocredito:'',
      nrodesembolso:'',
      mtocredito:'',
      nrocredcan:'',
      labelg:[],
      label1:[],
      label2:[],
      medatos1:[],
      medatos2:[],
    }
  },
  
  methods: {

    loadUsers(){
                axios.get("api/parainicioteso").then(({ data }) => {
                    this.nrocredito = data.nrocredito;
                    this.mtocredito = data.mtocredito;
                    this.nrocliente = data.nrocliente;
                    this.nrodesembolso = data.nroactividad;
                    this.nrocredcan = data.nrocredcan;

                    }).catch(error => { this.error = error.response });
                axios.get("api/creditomesteso").then(({ data }) => {
                 (this.datacollection = data);
                 }).catch(error => { this.error = error.response });
                // axios.get("api/pagoscredito").then(({ data }) => {
                //    (this.datacollection1 = data.dato1,this.labelg = data.labelg, this.medatos1=data.grafico1, this.medatos2=data.grafico2 );
                //   }).catch(error => { this.error = error.response });
                axios.get("api/metamesteso").then(({ data }) => {
                  this.datacollection2 = data.pie; this.cuadro=data.cuadro;this.cuadro1=data.cuadro1;
                 }).catch(error => { this.error = error.response });
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