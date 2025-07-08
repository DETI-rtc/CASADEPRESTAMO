<template> 
  <div class="clase">
       <div class="card card-info">
            <div class="card-header no-print">
                <h3 class="card-title " style="font-size: 24px !important;"> <i class="fas fa-users mr-2" >
                  </i> Reporte Total de Reenganches</h3>
                    <div class="card-tools">
                      <div class="btn-group" role="group" aria-label="Basic example">
                          <button class="btn btn-warning" @click="print"><i class="fa fa-print"></i> Imprimir</button>
                      </div>
                    </div>
            </div>
            
        </div>
        <div  ref="contento">
              <div class="text-center">
                <h3>TOTAL DE REENGANCHES A LA FECHA</h3>
              </div>
              
                <table class="table table-bordered" >
                <thead>
                  <tr class="text-center text-sm">
                    <th class="cabe">Item</th>
                    <th class="cabe">Nro Credito</th>
                    <th class="cabe">Cliente</th>
                    <th class="cabe">Total de Reenganches</th>
                    <th class="cabe">Total de Creditos</th>
                  </tr>
                </thead>
                <!-- <tbody v-if="data.length > 0">
                  <tr v-for="(i,index) in data" :key="i.id">
                    <td class="text-center"> {{ index + 1 }} </td>
                    <td class="text-center"> {{ i.numero }} </td>
                    <td class="text-center"> {{ i.cliente }} </td>
                    <td class="text-center"> {{ i.reenganche }} </td>
                    <td class="text-center"> {{ i.total_creditos }} </td>
                  </tr>
                </tbody> -->
                <tbody>
                  <template v-for="(i, index) in data">
                    <tr>
                      <td class="text-center"> {{ index + 1 }} </td>
                      <td class="text-center"> {{ i.numero }} </td>
                      <td class="text-center"> {{ i.cliente }} </td>
                      <td class="text-center"> {{ i.reenganche }} </td>
                      <td class="text-center"> {{ i.total_creditos }} </td>
                    </tr>
                    <tr style="font-weight:bold; font-size:0.7rem;" v-if="i.reenganches && i.reenganches.length > 0">
                      <td class="text-center" colspan="2"></td>
                      <td class="text-center">Fecha</td>
                      <td class="text-center">Monto</td>
                      <td class="text-center">Estado</td>
                    </tr>
                    <tr v-for="r in i.reenganches" style="font-size:0.7rem;">
                      <td class="text-center" colspan="2"></td>
                      <td class="text-center"> {{r.cronograma[0].f_desembolso}} </td>
                      <td class="text-right">S/. {{r.monto_credito}}</td>
                      <td class="text-center">
                        <span v-if="r.estado_cred == 'CA'">CANCELADO</span>
                        <span v-else-if="r.estado_cred == 'C'">CANCELADO</span>
                        <span v-else-if="r.estado_cred == 'A'">PENDIENTE</span>
                        <span v-else>{{r.estado_cred}}</span>
                      </td>
                    </tr>
                  </template>
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
              data:[],
            }
        },
       
        methods: {
          getReport(){
            axios.get('/api/reportreenganche')
            .then(({data}) => {
              this.data = data;
              console.log(data);
            }).catch((err) => {
              console.log(err);
            });
          },
          
          print(){
            let footer = document.getElementsByTagName('footer')[0]
                footer.style = 'display:none';
                var css = "@page {size: A4 landscape;   margin: 1rem 1rem 1rem 1rem !important; padding: 0 0 0 0 !important} .table{width:100% !important; border-color: #000000 !important; } th{padding:0 !important; font-size:8px !important;border: solid 2px; border-color: #000000;} td{padding:0 !important; font-size:8px !important;border: solid 2px; border-color: #000000 !important; color:#000000 !important;vertical-align:middle !important;} ",
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
          this.getReport();
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
