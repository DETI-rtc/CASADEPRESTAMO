<style type="text/css" >
    /* .VueTables__table tbody {
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
    .marcard{
        margin: 15px;
    }
    .noinfe {
        margin-bottom: 0;
        /*width: 100%;*/
    /* }
    .tcrono thead tr th{
        text-align: center;
    }
    table th {
        
        text-align: center;
        vertical-align: middle;
    }
    table tbody tr td {
        
        text-align: center;
        vertical-align: middle !important;
    }
    table thead tr th {
        
        text-align: center;
        vertical-align: middle;
    }

    .dropdown-item {
        cursor: pointer !important;
    } */ 
    /* th:nth-child(n+2),
    td:nth-child(n+2) {
    text-align: right;
    } */
</style>
<template>
  <div class="" >
       <div class="card card-primary card-outline" >
            <div class="card-header">
                <h3 class="card-title" style="font-size: 24px !important;"> <i class="fas fa-users mr-2" ></i> RELACION DE CREDITOS </h3>
                    <div class="card-tools">

                        <select class="form-control" v-model="anio" @change="selperiodo($event)" >SELCCIONE AÑO
                            <option v-for="peri in periodos" :key="peri.año" >
                                {{ peri.año }}
                            </option>
                        </select>
                         
                    </div>
            </div>
            <div class="card-body" ref="contento" >
                   

                        <v-client-table :data="creditos" :columns="columna" :options="options"  id="#ttrabajador">
                                    <div slot="dni" slot-scope="{row}">
                                      {{row.persona.dni   }}
                                     </div>

                                     
                                    <div slot="nombres" slot-scope="{row}" >
                                      {{row.persona.paterno+' '+row.persona.materno +' '+row.persona.nombres }}
                                    </div>
                                    <div slot="empresa" slot-scope="{row}">
                                      {{row.persona.empresa.razonsocial   }}
                                     </div>
                                    <div slot="monto_credito" slot-scope="{row}">
                                      {{row.monto_credito | currency  }}
                                     </div>
                                    <div slot="situacion_apro" slot-scope="{row}" >
                                        <div v-if = "row.situacion_apro == 'P'">
                                            <span class="badge badge-pill badge-warning">Por Aprobar</span>
                                        </div>
                                        <div v-else>
                                            <span  class="badge badge-pill badge-success">Aprobado</span>
                                        </div>
                                    </div>
                                    <div slot="estado_cred" slot-scope="{row}" >
                                      <div v-if = "row.estado_cred == 'A'">
                                          <span class="badge badge-pill badge-success">En Proceso</span>
                                      </div>
                                      <div v-else>
                                          <span  class="badge badge-pill badge-danger">Cancelado</span>
                                      </div>
                                    </div>
                                    <div slot="estado" slot-scope="{row}" >
                                      <div v-if = "row.estado == 1">
                                          <span class="badge badge-pill badge-success">Activo</span>
                                      </div>
                                      <div v-else>
                                          <span  class="badge badge-pill badge-danger">Anulado</span>
                                      </div>
                                    </div>

                                   <div slot="actions" slot-scope="{ row }">
                                      <div class="btn-group">
                                        
                                        <button type="button" class="btn btn-success btn-sm"  @click="printCronograma(row.id)"><i class="fas fa-eye"></i></button>
                                        
                                        <button type="button" class="btn btn-warning btn-sm" v-if="row.fecha_des == null"  @click="showAddCP(row.id)"> <i class="fas fa-plus"></i> Comprobante</button>
                                        
                                      </div>               
                                    
                                    
                                    
                                    </div>
                      </v-client-table>
                </div>
        </div>
     
     <!-- Modal -->
    <div class="modal fade" id="addCPModal" tabindex="-1" aria-labelledby="addCPModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCPModalLabel">AÑADIR CP</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nro Comprobante</label>
                        <input type="number" class="form-control" v-model="form.nrocp">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Fecha</label>
                        <input type="date" class="form-control" v-model="form.fecha">
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" @click="addCP()">Añadir Comprobante</button>
            </div>
            </div>
        </div>
    </div>


  </div>
</template>

<script>
    import VueAutonumeric from 'vue-autonumeric'
    import VueHtmlToPaper from 'vue-html-to-paper';
    import moment from 'moment';
    var Finance = require('financejs'); // or ES6 import
    const { irr } = require('node-irr');
    export default {

        data() {
            return {
                periodos:[],
                valormaximo:'1000000',
                tabName:'',
                tipocredi:'Convenio',
                isActive:false,
                iscrono : false,
                editmode: false,
                anio:moment().format('YYYY'),
                error:{},
                delista:[],
                creditos : [],
                fechas:[],
                loading_modal:false,
                empresa : [],
                idCredito:'',
                columna:['id','cp_desembolso','dni','nombres','empresa','monto_credito','plazo_credito','situacion_apro','estado_cred','estado','actions'],
                options: {
                          headings: {
                                id:'Nro Credito', cp_desembolso:'C/P',dni: 'DNI',nombre:'Apellidos y Nombres',empresa:'Entidad',monto_credito:'Credito',plazo_credito:'Nro de Cuotas',situacion_apro:'Situacion',estado_cred:'Estado de Credito',estado:'Estado'
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm table-hover',
                          filterAlgorithm: {
                               full_name(row, query) {
                          return (row.paterno + ' ' + row.materno +' '+ row.nombres).includes(query.toUpperCase());
                             }
                          },
                          filterable: ['full_name','nombres'],
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

                // formCronograma: new Form({}),
                logo:'/img/logocasa2.png',

                form : new Form ({
                    nrocp:'',
                    fecha:'',
                }),
                
            }
        },

        computed: {
            calcIngresoNeto(){
                let result = this.formCredito.ingre_bru - this.formCredito.des_ley;
                this.formCredito.ing_neto = result;
                return result;
            },
            calcing_netodiscred(){
                // if (this.formCredito.ing_neto.length > 0) {
                    let result = this.formCredito.ing_neto * (this.formCredito.idrci/100);
                    this.formCredito.ing_netodiscred = result;
                    return result;
                // } else {
                //     this.formCredito.ing_neto === 0;
                // }
            },

            calcdeuda_sc(){
                let result = this.formCredito.sal_deuda_sc/this.formCredito.meses_fal_cont;
                console.log(result);
                if (this.formCredito.sal_deuda_sc > 0) {
                    this.formCredito.deuda_sc = result;
                }
                return result;
            },

            calcCuotaMaxima(){
                let result =  this.formCredito.ing_netodiscred - this.formCredito.deuda_sc - this.formCredito.deuda_cc - this.formCredito.deuda_hipo;
                this.formCredito.cuota_max_pres = result;
                return result;
            },

            // calcPlaz_Max_Oper(){
            //     let result = this.formCredito.meses_fal_cont;
            //     this.formCredito.plazo_mas_ope = this.formCredito.meses_fal_cont;
            //     return result;
            // },
            calcPlaz_Max_Oper(){
                let result = this.formCredito.meses_fal_cont;
                console.log(result);
                if (result == 0) {
                    this.formCredito.plazo_mas_ope = 3;
                    this.formCredito.plazo_max_apro =3;
                    result=3;
                }else{
                    this.formCredito.plazo_mas_ope = Math.min(this.formCredito.meses_fal_cont,this.formCredito.idplazo);
                    this.formCredito.plazo_max_apro = Math.min(this.formCredito.meses_fal_cont,this.formCredito.idplazo);
                    result=Math.min(this.formCredito.meses_fal_cont,this.formCredito.idplazo);
                }

                //this.formCredito.plazo_mas_ope = this.formCredito.meses_fal_cont;
                return result;
            },


            calcmonto_max_rci(){
                let result = ((1-(Math.pow((1+this.formCredito.tem_rci),-24)))*this.formCredito.cuota_max_pres)/this.formCredito.tem_rci;
                this.formCredito.monto_max_rci = result;
                return result;
            },

            calctem_rci(){
                let result = Math.pow((1+(this.formCredito.idtasa_int/100)), (1/12))-1;
                this.formCredito.tem_rci = result;
                return result;
            },

            calcmax_ende(){
                let result = this.formCredito.ing_neto*this.formCredito.idrdi;
                this.formCredito.max_ende = result;
                return result;
            },
            deuda_sc(){
                let result = this.formCredito.sal_deuda_sc/24;
                this.formCredito.deuda_sc =  result;
                return result;
            },
            calcdeuda_consu(){
           
                 let result = parseFloat(this.formCredito.sal_deuda_sc) + parseFloat(this.formCredito.sal_deuda_cc);
                 this.formCredito.deuda_consu = result;
                 return result;
             },
            // calcdeuda_consu(){
            //     if (!this.formCredito.plazo_max_apro) {
            //         this.formCredito.plazo_max_apro = 0;
            //         console.log('esta entrando');
            //     }
            //     console.log(this.formCredito.sal_deuda_sc, this.formCredito.plazo_mas_apro);
            //     let result = parseFloat(this.formCredito.sal_deuda_sc) + parseFloat(this.formCredito.plazo_max_apro);
            //     this.formCredito.deuda_consu = result;
            //     return result;
            // },

            calcmonto_max_rdi(){
                let result =  parseFloat(this.formCredito.max_ende)- parseFloat(this.formCredito.deuda_consu)- parseFloat(this.formCredito.otras_deudas);
                this.formCredito.monto_max_rdi = result;
                return result;
            },

            calcmonto_max_apro(){
                let result = Math.min(this.formCredito.monto_max_rdi,this.formCredito.monto_max_rci);
                this.formCredito.monto_max_apro = result;
                return result;
            }


        },
        methods: {

            showAddCP(id){
                console.log(id);
                $('#addCPModal').modal('show');
                this.idCredito = id;

            },

            addCP(){
                let enviar = {
                    idcredito:this.idCredito,
                    nrocp:this.form.nrocp,
                    fecha:this.form.fecha,
                }
                axios.post('/api/addcp',enviar)
                .then(({data}) => {
                    $('#addCPModal').modal('hide');
                    this.getCreditos();
                    this.$message({
                        type: 'success',
                        message: 'Completado Satisfactoriamente'
                    });
                    
                }).catch((err) => {
                    console.log(err);
                });
            },


            selperiodo(){
                axios.get("/api/creditos",{params:{'anio':this.anio}})
                .then(({data}) => {
                    this.creditos = data
                }).catch(error => { this.error = error.response });
            },
            printCronograma(id){
                let routeData = this.$router.resolve({path:'/cronograma/'+id});
                window.open(routeData.href, '_blank');
            },


            
            getCreditos(){
                axios.get("/api/creditos",{params:{'anio':this.anio}})
                .then(({data}) => {
                    this.creditos = data
                }).catch(error => { this.error = error.response });
            },
            loadUsers(){
                //axios.get("api/periodos").then(({ data }) => {console.log(data); this.periodos = data}).catch(error => { this.error = error.response });

                axios.get("/api/periodos")
                .then(({data}) => {
                    console.log(data);
                    this.periodos = data;
                }).catch((err) => {
                    console.log(err);
                     this.error = error.response 
                });


                axios.get("/api/creditos",{params:{'anio':this.anio}})
                .then(({data}) => {
                    this.creditos = data

                    }).catch(error => { this.error = error.response });
                //axios.get("api/cLientes").then(({ data }) => (this.relaciondi = data)).catch(error => { this.error = error.response });
                //axios.get("api/delista").then(({ data }) => (this.delista = data)).catch(error => { this.error = error.response });
                //axios.get("api/empresas").then(({ data }) => (this.empresa = data)).catch(error => { this.error = error.response });

            },
            

        },


        created() {
           this.loadUsers();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
           });

           setInterval(() => this.getCreditos(), 10000);
        //    setInterval(() => this.loadUsers(), 3000);
        },
    }
</script>

