<template>
   <div >
  <div class="col-12">

                        <!-- Main content -->
                        <div class="invoice p-3 mb-3">
                          <!-- title row -->
                          <div class="row" >
                            <div class="col-md-12">
                              <h4>
                                <img src="/img/logocasa2.png" width="145px">

                                <small class="float-right">CASA DE PRESTAMOS HUANCAYO </br>Fecha: {{fecha}}</small>
                              </h4>
                            </div>
                            <!-- /.col -->
                          </div>
                          <!-- Table row -->
                          <div class="row">
                            <div class="card-body table-responsive p-0">
                            </br>
                              <table class="nuevo">
                                <tr>
                                  <th colspan="7">HOJA DE RUTA DIARIA DE EJECUTIVO</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td colspan="2" class=" text-bold" >NOMBRE ANALISTA</td>
                                  <td colspan="3">{{ detalle.nombre }}</td>
                                  <td class=" text-bold">FECHA</td>
                                  <td>{{fecha}}</td>
                                </tr>
                                <tr>  
                                    <td colspan="7" class="separador">  </td>
                                </tr>
                                <tr>
                                  <td :rowspan="nman+1" class=" text-bold">MAÑANAS</td>
                                  <td class=" text-bold">HORA INICIO</td>
                                  <td class=" text-bold">HORA FIN</td>
                                  <td class=" text-bold">DNI CLIENTE</td>
                                  <td class=" text-bold">CLIENTE NOMBRES APELLIDOS</td>
                                  <td class=" text-bold">ACTIVIDAD REALIZADA</td>
                                  <td class=" text-bold">ESTATUS</td>
                                  
                                </tr>
                                <tr v-for="man in maña">
                                  <td>{{ man.hora_ini }}</td>
                                  <td>{{ man.hora_fin }}</td>
                                  <td>{{ man.dni }}</td>
                                  <td>{{ man.cliente }}</td>
                                  <td>{{ man.actividad }}</td>
                                  <td></td>
                                  
                                </tr>
                                
                                <tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td colspan="7" class=" text-bold">ALMUERZO</td>
                                </tr>
                                <tr>
                                  <td class=" text-bold" :rowspan="ntar+1">TARDES</td>
                                  <td class=" text-bold">HORA INICIO</td>
                                  <td class=" text-bold">HORA FIN</td>
                                  <td class=" text-bold">DNI CLIENTE</td>
                                  <td class=" text-bold">CLIENTE NOMBRES APELLIDOS</td>
                                  <td class=" text-bold">ACTIVIDAD REALIZADA</td>
                                  <td class=" text-bold">ESTATUS</td>
                                </tr>
                                <tr v-for="man in tarde">
                                  <td>{{ man.hora_ini }}</td>
                                  <td>{{ man.hora_fin }}</td>
                                  <td>{{ man.dni }}</td>
                                  <td>{{ man.cliente }}</td>
                                  <td>{{ man.actividad }}</td>
                                  <td></td>
                                  
                                </tr>
                                <tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                </tr>
                              </tbody>
                              </table>
                              
                            
                             
                              
                                
                               
                              
                            </div>
                            <!-- /.col -->
                          </div>
                          <div class="row"> </div>

                              <table class=" table">    
                                    <tr>  </tr>
                                    <tr>  </tr>
                                    <tr>  </tr>
                                    <tr>  
                                        
                                        <td class="text-center">FIRMA Y SELLO DEL ANALISTA   </td>
                                        <td class="text-center">FIRMA Y SELLO DEL SUPERVISOR  </td>
                                    </tr>
                                
                              </table>
                              <div class="row col-md-12"> 
                                   <div class="col-md-4 text-center pt-5"> FIRMA Y SELLO DEL ANALISTA  </div>
                             <div class="col-md-2">  </div>
                             <div class="col-md-4 text-center pt-5">FIRMA Y SELLO DEL SUPERVISOR  </div>


                              </div>
                          <!-- /.row -->

                         
                          <!-- /.row -->

                          <!-- this row will not appear when printing -->
                          <div class="row no-print">
                            <div class="col-12">

                              <a href="" @click.prevent="printme" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Inprimir</a>
                              

                            </div>
                          </div>

                        </div>
                        <!-- /.invoice -->
                      </div>
         </div>
</template>
<script>
  import moment from 'moment';
    export default {
        data() {
            return {
               fecha:moment(new Date()).local().format('DD/MM/YYYY'),
               detalle:'',
               maña:[],
               tarde:[],
               nman:'',
               ntar:'',
                                   
                }
            },
        
        methods: {
            
            printme() {
              var css = '@page { size: A4 ; margin-top: 1.5cm;margin-left: 1cm; margin-right: 1cm; margin-bottom: 1.5cm; } .saltopagina {page-break-before: always; }',
              head = document.head || document.getElementsByTagName('head')[0],
              style = document.createElement('style');
              style.type = 'text/css';
              style.media = 'print';
              if (style.styleSheet){
                  style.styleSheet.cssText = css;
              } else {
                style.appendChild(document.createTextNode(css));
              }

              head.appendChild(style);

        window.print();
           },
           loadUsers(){
            axios.get("/api/hojaruta/"+this.$route.params.id).then(({ data }) => {this.detalle = data.deta; this.maña=data.maña;this.tarde=data.tarde;this.nman=data.nroma;this.ntar=data.nrota}).catch(error => { this.error = error.response });
        },
      },
      created() {
            
           this.loadUsers();
           Fire.$on('AfterCreate',() => {
               this.regargar();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>
<style  scoped>
  
  table.nuevo {
   width: 100%;
   border: 1px solid #000;
   font-size: 14px !important;
   /*font-weight: bold;*/
   line-height: 1.2;
}

 table.nuevo th, table.nuevo td {
   text-align: center;
   vertical-align: top;
  
   border-collapse: collapse;
   padding: 0.3em;
   caption-side: bottom;
   border-width: 1px;
   border-style: solid;
   vertical-align: middle !important;
}
.centrar {
  vertical-align: middle !important;

}
.derecha {
  text-align: right !important;

}
.izquierda {
  text-align: left !important;

}
.bordes {
      border-bottom: 1px solid #fff !important;
    border-top: 1px solid #fff !important;
    /* border-left: 1px solid #fff; */
    border-right: 1px solid #fff !important;
    text-align: left !important;
}
.bordes1 {
      border-bottom: 1px solid #fff !important;
    border-right: 1px solid #000 !important;
}
.separador {
  border-right: #ffffff 1px solid !important;
    border-left: white 1px solid !important;
}
</style>


 