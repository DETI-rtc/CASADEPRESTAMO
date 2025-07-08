<template>
    <div class="">
       <div class="card card-outline card-primary" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title blue" style="font-size: 24px;"> <i class="fa fa-suitcase nav-icon"></i> Seguimiento de Pagos SBH </h3>
            
                <div class="card-tools">
                    <div class="input-group input-group-sm">
                    <select class="form-control" v-model="fdato.ano" @change="selanopago($event)"> SELECIONE EL AÑO
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                  </select>
                          <select class="form-control" v-model="fdato.feca" @change="selmespago($event)" > Mes
                    <option value="Enero">Enero</option>
                    <option value="Febrero">Febrero</option>
                    <option value="Marzo">Marzo</option>
                    <option value="Abril">Abril</option>
                    <option value="Mayo">Mayo</option>
                    <option value="Junio">Junio</option>
                    <option value="Julio">Julio</option>
                    <option value="Agosto">Agosto</option>
                    <option value="Setiembre">Setiembre</option>
                    <option value="Octubre">Octubre</option>
                    <option value="Noviembre">Noviembre</option>
                    <option value="Diciembre">Diciembre</option>
                  </select>
              </div>
                      </div>
            
        </div>
        <div class="card-body" >
             <v-client-table :data="pago" :columns="columna" :options="options" >                       
                    <div slot="actions" slot-scope="{ row }">
                        
                        <button type="button" class="btn btn-outline-success btn-sm" @click="pagarsbh(row.dni)" ><i class="fa fa-edit"> </i> </button>
                        
                      </div>
            </v-client-table>
            
           
        </div>
       </div>
       <div v-else>
            <not-found></not-found>
       </div>

       <div class="modal bd-example-modal-xl " id="nuevalista" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-xl" role="document" style="max-width:90% !important;" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Importar documento excel</h4>
                </div>
                <div class="modal-body"> 
                     <div class="form-group row noinfe ">
                        <label class="col-sm-3 col-form-label" >Número de cuota <span style="color: red; font-weight: bold;">*</span></label>
                        <div class="col-sm-4">
                            <select class="form-control"  id="" >
                                <option>Cuota </option>
                             </select>
                        </div>
                     </div>
                </div>
                <div class="modal-footer"> 

                </div>


            </div>
        </div>
        
       </div> 
       <div class="modal bd-example-modal-xl " id="regPagosbh" tabindex="-1" role="dialog" >
                <div class="modal-dialog modal-md" role="document"  >
                    <div class="modal-content" :class="!editmode ? 'card card-outline card-primary' : 'card card-outline card-success'">
                        <div class="card-header">
                        <h5 class="card-title" v-show="!editmode" id="addNewLabel">Nuevo Pago</h5>
                        <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar Pago</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-bs-dismiss="modal"><i
                                    class="fas fa-times"></i></button>
                        </div>

                    </div>
                         <form @submit.prevent="editmode ? ActuaPago() : creaPago()">
                        <div class="modal-body">
                            <div class="form-group m-0 row " >
                                <div class="col-7">
                                    <label >Fecha de Pago</label>
                                </div>
                                <div class="col-5">
                                  <input type="date"  class="form-control form-control-sm" placeholder="Fecha" v-model="formPago.fecha_pago"> 
                                </div>
                            </div>
                            
                           <div class="form-group">
                                       
                                </div>
                                <div class="form-group m-0 row " >
                                    <div class="col-3">
                                        <label>DNI <span style="color: red; font-weight: bold;">*</span></label>
                                    </div>
                                    <div class="col-9">
                                         <label>{{formPago.dni}}</label>
                                    </div>
                                </div>

                                <div class="form-group row  m-0">
                                    <label class="col-sm-3 col-form-label" >Entidad <span style="color: red; font-weight: bold;">*</span></label>
                                    <div class="col-sm-9">
                                        <label>{{formPago.empresa}}</label>
                                    </div>
                                </div>

                                <div class="form-group row  m-0">
                                    <label class="col-sm-7 col-form-label" >Número de cuota <span style="color: red; font-weight: bold;">*</span></label>
                                    <div class="col-sm-5">
                                         <v-select  :options="cuotas" :reduce="cuotas => cuotas.cuotanro"  v-model="formPago.cuotanro" label="cuotanro" @input="mostrarcuota">
                                             <template slot="option" slot-scope="option">
                                                    <span class="fa" :class="option.icon"></span>
                                                    {{ option.cuotanro }} - {{ option.fecha_ven }}
                                                </template>
                                         </v-select>
                                    </div>
                                </div>
                                <div class="form-group row  m-0">
                                    <label class="col-sm-6 col-form-label"> Total de Cuota <span style="color: red; font-weight: bold;">*</span></label>
                                    <div class="col-sm-6">
                                        <label class="text.bold text-lg mt-2 mb-0 float-right" >{{cuotames | currency}} </label>
                                    </div>
                                </div>

                                <div class="form-group row  m-0">
                                    <label class="col-sm-6 col-form-label"> Saldo de Cuota</label>
                                    <div class="col-sm-6">
                                        <vue-autonumeric :options="''" v-model="formPago.saldo" :value="calsaldo" class="form-control form-control-sm text-right" disabled></vue-autonumeric>
                                        
                                    </div>
                                </div>

                                <div class="form-group row  m-0">
                                    <label class="col-sm-6 col-form-label" >Monto a Pagar S/.<span style="color: red; font-weight: bold;">*</span></label>
                                    <div class="col-sm-6">
                                        <vue-autonumeric :options="''" v-model="formPago.monto" class="form-control form-control-sm text-right text-lg text-bold"  ></vue-autonumeric>
                                         
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" >Registrar pagos</button>
                        </div>
                    </form>


                        
                    </div>
                </div>
        </div>

</div>
    
</template>

<script>
    import VueAutonumeric from 'vue-autonumeric'
     import moment from 'moment'
    export default {
        data() {
            return {
                editmode: false,
                error:{},
                feca:'',
                errors:{},
                delista:[],
                cuotames:'',
                cuotas:[],
                credito:[],
                pago : [],
                columna:['idtrabajador','dni','apenom','cod_inte','desc_l06','casa_sbh','actions'],  
                options: {
                          headings: {
                                idtrabajador: 'ID',dni: 'DNI',apenom:'Apellidos y Nombres',cod_inte :'Codigo',desc_l06 :'Planilla Contratado',casa_sbh :'Planilla CAFAE',
                              },
                          perPage:15,
                          perPageValues:[15,25,50,100],
                          skin:'table table-sm',
                          filterable: ['apenom','dni'],
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
                                }
                          
                },
                formPago : new Form ({
                    idcredito:'',
                    dni:'',
                    empresa:'',
                    idcliente:'',
                    fecha_pago: '',
                    fecha_ven:'',
                    monto:'',
                    cuotanro:'',
                    saldo:0,
                    idcrono:'',
                    
                }),
                fdato: new Form({
                        ano:'',
                        feca:'',
                }),
                form: new Form({
                        id:'',
                        identidad:'',
                        idmodalidad:'',
                        tea:'', 
                                      
                    
                })
            }
        },
        methods: {
            creaPago(){
                if(this.formPago.cuotanro === undefined){
                    Toast.fire({
                        type: 'warning',
                        title: 'Ingrese el Nro de la cuota a Pagar'
                        })
                }else{
                    this.formPago.post('api/pago').then((data)=>{
                    console.log('data');
                    $('#regPagosbh').modal('hide');
                    this.loadUsers();
                }).catch( err=>{
                                console.log(err);
                            }
                        )
                }
                

                    
            },
            pagarsbh(persona){
               // console.log(persona);
                axios.get('api/buscarcredito/'+persona).then( ({data})  =>  
                {
                    console.log(data.creditos.length);
                    if ( data.creditos.length === 0){
                        Swal.fire('La Persona',
                          'No tiene Credito o no esta registrado',
                          'error'
                          )
                    }else{
                        this.formPago.reset();
                        this.formPago.fill(data.creditos);
                        this.cuotas = data.cuotas;
                        this.formPago.fecha_pago = moment().format('YYYY-MM-DD'); 
                        this.editmode = false;
                        $('#regPagosbh').modal('show');
                        
                    }
                    


                }).catch(error => { this.error = error.response });
                
            },
            mostrarcuota(id){
                let cou =  this.cuotas.filter(function(cuotas) {
                    return cuotas.cuotanro == id;
                });
                this.cuotames = cou[0].cuota;
                this.formPago.monto = cou[0].cuota;
                this.formPago.fecha_ven = cou[0].fecha_ven;
                this.formPago.idcrono = cou[0].idcrono;
            },






            selmespago(event){
                this.fdato.feca = event.target.value
                this.fdato.post("api/pagosbh").then(({ data }) => (this.pago = data)).catch(error => { this.error = error.response });              
            }, 
            selanopago(event){
                this.fdato.ano = event.target.value
                this.fdato.post("api/pagosbh").then(({ data }) => (this.pago = data)).catch(error => { this.error = error.response });              
            }, 
            loadUsers(){
                this.fdato.ano=moment().format("YYYY");

                this.fdato.feca=moment().locale('es-es').format("MMMM");
                
                this.fdato.post("api/pagosbh").then(({ data }) => (this.pago = data)).catch(error => { this.error = error.response });
                //axios.get("api/delista").then(({ data }) => (this.delista = data)).catch(error => { this.error = error.response });
                


            },
            

            
        },
        computed:{
           
            calsaldo(){
                let saldo = this.cuotames - this.formPago.monto;
                this.formPago.saldo=saldo;
                return saldo;


            }
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
<style >
.VueTables__search{
       display: inline;
    }
    
.VueTables__search-field {
    display: inline-flex !important; 
}
.error-form{
    display: block;
        width: 100%;
    margin-top: 0.25rem;
    font-size: 80%;
    color: #dc3545;
}
</style>