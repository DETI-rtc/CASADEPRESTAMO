<template>
   
          <div class="col-12">
            <!-- Default box -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title mb-0"> <i class="fas fa-shopping-cart"></i> Registra Venta</h3>
              </div>
              <div class="card-body">
                    <div class="col-12">
                        <div class="row"> 
                            <div class="form-group col-2">
                                <label>Fecha</label>
                                 <!-- <Datepicker v-model="form.fecha_emision"/> -->
                                 <VueDatePicker v-model="form.fecha_emision" fullscreen-mobile />
                                <!-- <input class="form-control" type="date" v-model="form.fecha_emision" > -->
                            </div>
                            <div class="form-group col-3">
                                <label>Tipo Comp.</label>
                                <select class="custom-select form-control-border border-width-2" v-model="form.tipo_comprobante"  >
                                    <option v-for="tipoco in tipocom" :value="tipoco.id" v-bind:key="tipoco.id" >{{tipoco.descripcion}}</option>
                                </select>
                            </div>
                            <div class="form-group col-1">
                                <label>Moneda</label>
                                <select class="custom-select form-control-border border-width-2" type="date" name="moneda" id="moneda">
                                    <option v-for="moneda in monedas" :value="moneda.id" v-bind:key="moneda.descripcion" >{{moneda.descripcion}}</option>
                                </select>
                            </div>
                            <div class="form-group col-2">
                                <label>Forma Pago</label>
                                <select class="custom-select form-control-border border-width-2" v-model="forma_pago" >
                                    <option value="Contado">Contado</option>
                                    <option value="Credito">Credito</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-2">
                                <label>Serie</label>
                                <select class="custom-select form-control-border border-width-2" type="text" v-model="form.idserie" id="idserie" :onchange="ConsultarCorrelativo">
                                    <option v-for="serie in series" :value="serie.id" v-bind:key="serie.id" >{{serie.serie}}</option>
                                </select>
                            </div>
                            <div class="form-group col-1">
                                <label>Correlativo</label>
                                <input class="form-control" type="number" v-model="form.correlativo" >
                            </div>
                            <div class="form-group col-2">
                                <label>Tipo Doc.</label>
                                <select class="custom-select form-control-border border-width-2" v-model="form.tipo_comprobante_id" >
                                    <option v-for="tipodocu in tipodocumen" :value="tipodocu.id" v-bind:key="tipodocu.id" >{{tipodocu.descripcion}}</option>
                                </select>
                            </div>
                            <div class="form-group col-2">
                                <label>Nro. Doc</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="nrodoc" id="nrodoc">
                                    <div class="input-group-addon">
                                    <button type="button" class="btn btn-default" onclick="ObtenerDatosEmpresa()"><li class="fa fa-search"></li></button> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-3">
                                <label>Nombre/Raz. Social</label>
                                <input class="form-control" type="text" name="razon_social" id="razon_social">
                            </div>
                            <div class="form-group col-3">
                                <label>Dirección</label>
                                <input class="form-control" type="text" name="direccion" id="direccion">
                            </div>

                            <div class="input-group col-6">
                                <!-- <input class="form-control" type="text" name="producto" id="producto" placeholder="producto..." /> -->
                                <select class="custom-select form-control-border border-width-2" v-model="idproducto" >
                                    <option v-for="produ in productos" :value="produ.id" v-bind:key="produ.id" >{{produ.nombre}}</option>
                                </select>
                                <div class="input-group-addon">
                                    <button type="button" class="btn btn-success" @click="BuscarProducto()"> Agregar</button>  
                                </div>
                            </div>
                            <div class="col-12">
                                <table class="table table-hover">
                                    <thead>
                                        <th width="5%"></th>
                                        <th width="50%">Nombre</th>
                                        <th width="15%">P.U.</th>
                                        <th width="15%">Cantidad</th>
                                        <th width="15%">Total</th>
                                    <!-- <button type="button" class="btn btn-info">  +</button> </th> -->
                                    </thead>

                                        <tbody v-if="form.detalle.length">
                                        <tr v-for="(detalle,index) in form.detalle" :key="detalle.id">
                                           <td style="text-align: center;">
                                              <button @click="eliminarDetalle(index,detalle.id)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash-alt"></i></button>
                                           </td>
                                           <td v-text="detalle.nombre" style="vertical-align: middle;"></td>
                                           <td v-text="detalle.precio_unitario" style="vertical-align: middle;"></td>
                                           <td ><input type="text" class="form-control form-control-sm" v-model="detalle.cantidad"></td>
                                           <td style="text-align: right; vertical-align: middle;">{{ detalle.precio_unitario*detalle.cantidad | moneda}}</td>
                                           
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold">Sub total</td>
                                            <td align="right" style="font-weight: bold;">{{form.op_gravadas=calcularGravada.toFixed(2) | moneda}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold">Exonerado</td>
                                            <td align="right" style="font-weight: bold;">{{form.op_exoneradas=calcularExone.toFixed(2) | moneda}}</td>
                                        </tr>
                                        <!-- <tr>
                                            <td align="right" style="font-weight: bold;">{{form.op_inafectas=calcularIna.toFixed(2) | moneda}}</td>
                                        </tr> -->
                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold">I.G.V.</td>
                                            <td align="right" style="font-weight: bold;">{{form.igv=calcularIgv.toFixed(2) | moneda}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold">Total</td>
                                            <td align="right" style="font-weight: bold;">{{form.total=calcularTotal.toFixed(2) | moneda}}</td>
                                        </tr>
                                    </tbody>  
                                    <tbody v-else>
                                        <tr>
                                           <td colspan="7"><has-error :form="form" field="arraydetalle"></has-error> No hay articulos agregados</td>
                                        </tr>
                                    </tbody>        
                                  
                                </table>
                            </div>
                            <div class="col-12" id="div_carrito">
                            </div> 
                            <div class="form-group col-2">
                                <label>Vencimiento</label>
                                <VueDatePicker v-model="form.fecha_vencimiento" no-header  />
                                <!-- <input class="form-control form-control-border.border-width-2" type="date" name="fecha_vencimiento" id="fecha_vencimiento" > -->
                                    
                            </div>                   
                        </div>
                    </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                    <button type="button" class="btn btn-primary" onclick="GuardarComprobante()"><i class="fa fa-save"></i> Guardar</button> 
                    <button type="button" class="btn btn-danger" onclick="CancelarComprobante();"> <i class="far fa-trash-alt"></i> Cancelar</button>
              </div>
              <!-- /.card-footer-->
            </div>
            <!-- /.card -->
          </div>
        
</template>

<script>
import moment from 'moment'
    export default {
        data() {
            return {
                monedas:[{
                    id:'',
                    descripcion:'',
                }],
                tipodocumen:[],
                series:[],
                tipocom:[],
                productos:[],
                idproducto:'',
                form:new Form({
                    emisor_id:'',
                    tipo_comprobante_id:'',
                    serie_id:'',
                    serie:'',
                    correlativo:'',
                    forma_pago:'',
                    fecha_emision:moment().format('YYYY-MM-DD'),                   
                    moneda_id:'',
                    op_gravadas:'',
                    op_exoneradas:'',
                    op_inafectas:'',
                    igv:'',
                    total:'',
                    cliente_id:'',
                    detalle:[],
                   
                   
                })
            }
        },
        computed:{
            calcularTotal: function(){
                var resultado=0.0;
                for(var i=0;i<this.form.detalle.length;i++){
                    resultado=resultado+(this.form.detalle[i].precio_unitario*this.form.detalle[i].cantidad)
                }
                return resultado;
            },
             calcularGravada: function(){
                var resultado=0.0;
                for(var i=0;i<this.form.detalle.length;i++){
                    if(this.form.detalle[i].tipo_afectacion_id==10){
                         resultado=resultado+(this.form.detalle[i].cantidad*this.form.detalle[i].valor_unitario)
                    }
                   
                }
                return resultado;
            },
             calcularExone: function(){
                var resultado=0.0;
                for(var i=0;i<this.form.detalle.length;i++){
                    if(this.form.detalle[i].tipo_afectacion_id==20){
                         resultado=resultado+(this.form.detalle[i].cantidad*this.form.detalle[i].valor_unitario)
                    }
                }
                return resultado;
            },
             calcularIgv: function(){
                var resultado=0.0;
                for(var i=0;i<this.form.detalle.length;i++){
                    resultado=resultado+(this.form.detalle[i].igv)
                }
                return resultado;
            },
        },
        methods: { 
            encuentra(id){
                var sw=0;
                for(var i=0;i< this.form.detalle.length; i++){
                    if(this.form.detalle[i].producto_id==id){
                        sw=true;
                    }
                }
                return sw;
            },
            ConsultarCorrelativo(){
            },
            BuscarProducto(){              
                if(this.encuentra(this.idproducto)){
                    Swal.fire({
                        type: 'error',
                        icon: 'error',
                        title: 'Error...',
                        text: 'Este Producto ya se encuentra agregado!',
                    })
                }else{
                    let ofs = this.productos.filter(i=> i.id == this.idproducto) ;
                    console.log(ofs);
                    if(ofs[0].tipo_afectacion_id==10){
                        this.form.detalle.push({
                    producto_id: ofs[0].producto_id,
                    nombre:ofs[0].nombre,
                    cantidad: 1,
                    igv:parseFloat(ofs[0].valor_unitario*0.18),
                    valor_unitario: ofs[0].valor_unitario,
                    precio_unitario: parseFloat(ofs[0].valor_unitario)+parseFloat(ofs[0].valor_unitario*0.18),
                    tipo_afectacion_id:ofs[0].tipo_afectacion_id,
                        });
                    }
                    if(ofs[0].tipo_afectacion_id==20){
                        this.form.detalle.push({
                    producto_id: ofs[0].producto_id,
                    nombre:ofs[0].nombre,
                    cantidad: 1,
                    igv:0,
                    valor_unitario: ofs[0].valor_unitario,
                    precio_unitario: ofs[0].valor_unitario,
                    tipo_afectacion_id:ofs[0].tipo_afectacion_id,
                        });
                    }
                    //let pro = $.trim($('#producto').text());                                    
                }
            },
            loadUsers(){
                axios.get("api/moneda").then(({ data }) => (this.monedas = data, console.log(data))).catch(error => { this.error = error.response });
                axios.get("api/tipocomprobante").then(({ data }) => (this.tipocom = data)).catch(error => { this.error = error.response });
                axios.get("api/tipodocumento").then(({ data }) => (this.tipodocumen = data));
                axios.get("api/serie").then(({ data }) => (this.series = data));
                axios.get("api/producto").then(({ data }) => (this.productos = data)).catch(error => { this.error = error.response });
            },
        },
         created() {
            this.loadUsers();
            // Fire.$on('AfterCreate',() => {
            //     this.loadUsers();
            // });
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
