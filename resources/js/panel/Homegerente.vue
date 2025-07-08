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
                <h3 class="mb-0 ">{{nrocredito}}</h3>

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
          <div class="col-lg-3 col-md-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3 class="mb-0 text-center">{{mtopago | currency}}<sup style="font-size: 20px"></sup></h3>

                <p class="mb-0 ">Monto de Pagos</p>
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
                <h3 class="mb-0 " >{{nrosuper}}</h3>

                <p class="mb-0 ">Nro de Supervisores</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-secret" aria-hidden="true"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-md-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 class="mb-0 ">{{nroagen }}</h3>

                <p class="mb-0 ">Nro Agentes</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-tie " aria-hidden="true"></i>
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
          <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-area"></i>
                  
                  Historial de Creditos y Pagos
                </h3>
                <div class="card-tools">
                  <!-- <select class="form-control"> Año
                    <option>2019</option>
                    <option>2020</option>
                  </select> -->
                  <select v-model="anio" @change="loadUsers()" >SELCCIONE AÑO
                      <option v-for="peri in periodos" :key="peri.año" >
                          {{ peri.año }}
                      </option>
                  </select>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <area-chart :chart-data="datacollection3" :height="180" :options="{responsive:true,maintainAspectRation:true}"></area-chart>
                
                
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
              <div class="card card-outline card-dark">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-line"></i>
                  Historial de Creditos por Analistas
                </h3>

                <div class="card-tools">
                  <ul class="pagination pagination-sm">
                    <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                  </ul>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <area-chart :chart-data="creditoanalistas" :height="150" :options="{responsive:true,maintainAspectRation:true}"></area-chart>

              </div>
              <!-- /.card-body -->
              
            </div>
            <!-- DIRECT CHAT -->
            <div class="card card-outline card-warning">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar"></i> Historial de Pagos Mensual</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- Conversations are loaded here -->
                <bar-chart :chart-data="datacollection1" :height="100" :options="{responsive:true,maintainAspectRation:true}"></bar-chart>
                <!-- /.direct-chat-pane -->
              </div>
              <!-- /.card-body -->
             
              <!-- /.card-footer-->
            </div>
            <!--/.direct-chat -->

            <!-- TO DO List -->
            
            <!-- /.card -->
          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-5 connectedSortable">

            <!-- Map card -->
            <div class="card card-primary">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                 Porcenteje de Creditos por Analistas
                </h3>
                <!-- card tools -->
                <div class="card-tools">
                  <button type="button"
                          class="btn btn-primary btn-sm daterange"
                          data-toggle="tooltip"
                          title="Date range">
                    <i class="far fa-calendar-alt"></i>
                  </button>
                  <button type="button"
                          class="btn btn-primary btn-sm"
                          data-card-widget="collapse"
                          data-toggle="tooltip"
                          title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
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
                      <th>N°</th>
                      <th width="35%"class="text-center">Analista</th>
                      <th width="35%" class="text-center">Monto</th>
                      <th width="30%" class="text-center">Porcentaje</th>
                    </tr>
                  </thead>
                  <tbody>   
                    <tr v-for="(nic, index) in valores"  >
                      <td >{{index+1}}</td>
                      <td >{{nic.apellidos}}</td>
                      <td style="text-align: right;">{{nic.monto | currency}}</td>
                      <td style="text-align: right;" ><span class="badge text-md" :style="{background:nic.color}" style="color: #fff!important;" >{{nic.porcen }}%</span></td>
                    </tr>
                    <tr>
                      <td colspan="4"><span class="badge"> 100 %</span></td>
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
                <div class="card-tools">
                  <!-- button with a dropdown -->
                </div>
                <!-- /. tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body pt-0">
                <!--The calendar -->
                <calendario></calendario>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- solid sales graph -->
            <div class="card card-info">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Sales Graph
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <bar-chart :chart-data="datacollection" :height="100" :options="{responsive:true,maintainAspectRation:true}"></bar-chart>
              </div>
              <!-- /.card-body -->
              
              
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->

            <!-- Calendar -->
            
            <!-- /.card -->
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
import moment from 'moment'


export default {
  components: {
    BarChart, LineChart,  PieChart, AreaChart 
  
  },
  data(){
    return {
      datacollection: [],
      datacollection1: [],
      datacollection2: [],
      datacollection3: [],
      cuadro:[],
      cuadro1:[],
      dtraba:{},
      respuesta:[],
      nrocredito:'',
      mtocredito:'',
      mtopago:'',
      nrosuper:'',
      nroagen:'',
      labelg:[],
      label1:[],
      label2:[],
      medato1:[],
      medato2:[],
      creditoanalistas:[],
      valores:[],
      anio:moment().format('YYYY'),
      periodos:[],
      
    }
  },
  
  methods: {

    loadUsers(){
      axios.get("api/periodos").then(({ data }) => (this.periodos = data)).catch(error => { this.error = error.response });
      axios.get("api/parainicioger",{params:{'ano':this.anio}}).then(({ data }) => {
          this.nrocredito = data.nrocredito;
          this.mtocredito = data.mtocredito;
          this.nrosuper = data.nrosuper;
          this.nroagen = data.nroagen;
          this.mtopago = data.mtopago;

          }).catch(error => { this.error = error.response });
      axios.get("api/creditomesger",{params:{'ano':this.anio}} ).then(({ data }) => {(this.datacollection3 = data); }).catch(error => { this.error = error.response });
      axios.get("api/pagoscreditoger",{params:{'ano':this.anio}} ).then(({ data }) => {(this.datacollection1 = data.dato1);}).catch(error => { this.error = error.response });
      axios.get("api/creditomesana",{params:{'ano':this.anio}} ).then(({ data }) => {(this.creditoanalistas = data);}).catch(error => { this.error = error.response });
      axios.get("api/creditoporcentaje",{params:{'ano':this.anio}} ).then(({ data }) => {this.datacollection2 = data.grafico; this.valores=data.valores }).catch(error => { this.error = error.response });
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