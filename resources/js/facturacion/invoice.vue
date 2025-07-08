<template>
    <div class="card mb-0 pt-md-0">
        <div class="tab-content" v-if="loading_form">
            <div class="invoice p-3">
                <header class="clearfix">
                    <div class="row">
                        <h3 class="title mb-3">NUEVO COMPROBANTE ELECTRÓNICO</h3>
                    </div>
                </header>
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row">
                            <div :class="form.document_type_id <= 2 ? 'col-lg-4' : 'col-lg-3'">
                                <div class="form-group" :class="{'has-danger': errors.document_type_id}">
                                    <label class="control-label font-weight-bold">Tipo comprobante</label>
                                    <el-select v-model="form.document_type_id" @input="changeDocumentType" class="border-left rounded-left">
                                        <el-option v-for="option in tipocom" :key="option.id" :value="option.id" :label="option.descripcion"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.document_type_id" v-text="errors.document_type_id[0]"></small>
                                </div>
                            </div>
                            <div v-show="form.document_type_id > 2" class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.tipo_comprobante_ref_id}">
                                    <label class="control-label font-weight-bold">Comp. emitidos</label>
                                    <el-select v-model="form.tipo_comprobante_ref_id" @change="selectComprobanteEmitido" class="border-left rounded-left">
                                        <el-option v-for="option in compemitidos" :key="option.id" :value="option.id" :label="option.serietotal"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.tipo_comprobante_ref_id" v-text="errors.tipo_comprobante_ref_id[0]"></small>
                                </div>
                            </div>
                            <div v-show="form.document_type_id > 2" class="col-lg-3">
                                <div class="form-group" :class="{'has-danger': errors.codmotivo}">
                                    <label class="control-label font-weight-bold">Motivo Nota</label>
                                    <el-select v-model="form.codmotivo" class="border-left rounded-left">
                                        <el-option v-for="option in motivosnota" :key="option.id" :value="option.id" :label="option.descripcion"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.codmotivo" v-text="errors.codmotivo[0]"></small>
                                </div>
                            </div>
                            <div :class="form.document_type_id <= 2 ? 'col-lg-2' : 'col-lg-1'">
                                <div class="form-group" :class="{'has-danger': errors.series_id}">
                                    <label class="control-label">Serie</label>
                                    <el-select @change="selectCorrelativo" v-model="form.series_id">
                                        <el-option v-for="option in series" :key="option.id" :value="option.id" :label="option.serie"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.series_id" v-text="errors.series_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group" :class="{'has-danger': errors.serie_correlativo}">
                                    <label class="control-label">Correlativo</label>
                                    <el-input disabled v-model="form.serie_correlativo"></el-input>
                                    <small class="form-control-feedback" v-if="errors.serie_correlativo" v-text="errors.serie_correlativo[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                                    <label class="control-label">Moneda</label>
                                    <el-select v-model="form.currency_type_id">
                                        <el-option v-for="option in monedas" :key="option.id" :value="option.id" :label="option.descripcion"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.currency_type_id" v-text="errors.currency_type_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                    <label class="control-label">Fec. Emisión</label>
                                    <el-date-picker v-model="form.date_of_issue" type="date" format="yyyy-MM-dd" value-format="yyyy-MM-dd"  :clearable="false" @change="changeDateOfIssue" :picker-options="datEmision" :readonly="readonly_date_of_due"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_of_due}">
                                    <label class="control-label">Fec. Vencimiento</label>
                                    <el-date-picker v-model="form.date_of_due" type="date" format="yyyy-MM-dd" value-format="yyyy-MM-dd" :clearable="false"  :readonly="readonly_date_of_due"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_of_due" v-text="errors.date_of_due[0]"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-lg-3 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.customer_id}">
                                    <label class="control-label font-weight-bold">
                                        Cliente
                                        <a href="#" @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                                    </label>
                                    <el-select v-model="form.customer_id" filterable remote class="border-left rounded-left" popper-class="el-select-customers"
                                        dusk="customer_id"
                                        placeholder="Escriba el nombre o número de documento del cliente"
                                        :remote-method="searchRemoteCustomers"
                                        @keyup.enter.native="keyupCustomer"
                                        :loading="loading_search"
                                        @change="changeCustomer">
                                        <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.nombres"></el-option>

                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.customer_id" v-text="errors.customer_id[0]"></small>
                                </div>
                            </div>
                            <div  class="col-lg-1" v-if="creditos.length > 0">
                                <div class="form-group">
                                    <label class="control-label">N° Credito</label>
                                    <el-select v-model="form.idcredito" @change="changeCredito()">
                                        <el-option v-for="option in creditos" :key="option.id" :value="option.id" :label="option.nrocredito"></el-option>
                                    </el-select>
                                </div>
                            </div>
                            <div  class="col-lg-2" v-if="creditos.length > 0">
                                <div class="form-group">
                                    <label class="control-label">N° Cuota</label>
                                    <el-select v-model="form.idcuota" @change="changeCuota()">
                                        <el-option v-for="option in credito.arraydetalle" :key="option.id" :value="option.id" :label="option.nro_cuota + ' - ' + option.fecha_nueva"></el-option>
                                    </el-select>
                                </div>
                            </div>
                            <div  class="col-lg-1">
                                <div class="form-group">
                                    <label class="control-label">Forma de pago</label>
                                    <el-select v-model="form.payment_method_type_id" @change="changePaymentMethodType()">
                                        <el-option v-for="option in payment_method_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                </div>
                            </div>
                            <div v-show="form.payment_method_type_id == 2" class="col-lg-2">
                                <div class="form-group">
                                    <label class="control-label">Tipo de contado</label>
                                    <el-select v-model="form.idpago_tipo_contado">
                                        <el-option v-for="option in tipos_contado" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Difunto</label>
                                    <el-select v-model="form.iddifunto" filterable :clearable="true" remote class="border-left rounded-left" popper-class="el-select-customers"
                                        dusk="customer_id"
                                        placeholder="Escriba el nombre o número de documento del cliente"
                                        :remote-method="searchRemoteDifuntos"
                                        :loading="loading_difunto"
                                        @change="changeDifunto">
                                        <el-option v-for="option in difuntos" :key="option.id"  :value="option.id" :label="option.nombre_dif"></el-option>
                                    </el-select>
                                </div>
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-lg-8" v-if="!is_receivable">
                                    <table>
                                        <thead>
                                            <tr width="100%">
                                                <template v-if="enabled_payments">
                                                    <th v-if="form.payments.length>0" class="pb-2">N° Couta</th>
                                                    <th v-if="form.payments.length>0" class="pb-2">Monto</th>
                                                    <th v-if="form.payments.length>0" class="pb-2">Fec. Vencimiento</th>
                                                    <th v-if="form.payments.length>0" width="15%"><a href="#" @click.prevent="clickAddPayment" class="text-center font-weight-bold text-info">[+ Agregar]</a></th>
                                                </template>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row, index) in form.payments" :key="index">
                                                <template v-if="enabled_payments">
                                                    <td>
                                                        <div class="form-group mb-2 mr-2"  >
                                                            <el-input disabled v-model="row.description"></el-input>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group mb-2 mr-2" >
                                                            <el-input v-model="row.payment"></el-input>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <el-date-picker v-model="row.date_of_payment" type="date" format="yyyy-MM-dd" value-format="yyyy-MM-dd" :clearable="false" ></el-date-picker>
                                                    </td>
                                                    <td class="series-table-actions text-center">
                                                        <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancel(index)">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </template>

                                                <br>
                                            </tr>
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                        <div class="row mt-2" v-if="form.iddifunto">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label style="font-size:1rem; color:#0dcaf1;"><i
                                                class="fas fa-info"></i> Datos de Difunto </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Pabellón</label>
                                    <input type="text" class="form-control form-control-sm" disabled v-model="difunto.pabellon"></input>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Cara</label>
                                    <input type="text" class="form-control form-control-sm" disabled v-model="difunto.cara"></input>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Fila</label>
                                    <input type="text" class="form-control form-control-sm" disabled v-model="difunto.fila"></input>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Columna</label>
                                    <input type="text" class="form-control form-control-sm" disabled v-model="difunto.columna"></input>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="30%" class="font-weight-bold">Producto</th>
                                                <th width="20%" class="font-weight-bold">Descripcion</th>
                                                <th class="text-center font-weight-bold">Unidad</th>
                                                <th class="text-right font-weight-bold">Cantidad</th>
                                                <th class="text-right font-weight-bold">Valor Unitario</th>
                                                <th class="text-right font-weight-bold">Precio Unitario</th>
                                                <th class="text-right font-weight-bold">Subtotal</th>
                                                <!--<th class="text-right font-weight-bold">Cargo</th>-->
                                                <th class="text-right font-weight-bold">Total</th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="form.items.length > 0">
                                            <tr v-for="(row, index) in form.items" v-bind:key="index">
                                                <td>{{index + 1}}</td>
                                                <!-- <td>{{row.item.description}} {{row.item.presentation.hasOwnProperty('description') ? row.item.presentation.description : ''}}<br/><small>{{row.affectation_igv_type.description}}</small></td> -->
                                                <td><el-input v-model="row.item.nombre"></el-input></td>
                                                <td class="text-center"><el-input v-model="row.item.descripcion"></el-input></td>

                                                <td class="text-right">{{row.item.unidad}}</td>
                                                <td class="text-right">{{row.quantity}}</td>
                                                <!--<td class="text-right" v-else ><el-input-number :min="0.01" v-model="row.quantity"></el-input-number> </td> -->

                                                <td class="text-right">{{currency_type.symbol}} {{getFormatUnitPriceRow(row.unit_value)}}</td>
                                                <td v-if="row.tipo_credito" class="text-right"><input @keyup="actualizarMontos(row)" type="text" class="form-control form-control-sm" v-model="row.unit_price"></td>
                                                <td v-if="!row.tipo_credito" class="text-right">{{currency_type.symbol}} {{getFormatUnitPriceRow(row.unit_price)}}</td>
                                                <!--<td class="text-right" v-else ><el-input-number :min="0.01" v-model="row.unit_price"></el-input-number> </td> -->


                                                <td class="text-right">{{currency_type.symbol}} {{row.total_value}}</td>
                                                <!--<td class="text-right">{{ currency_type.symbol }} {{ row.total_charge }}</td>-->
                                                <td class="text-right">{{currency_type.symbol}} {{row.total}}</td>
                                                <td class="text-right">
                                                    <button :disabled="row.tipo_credito" type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveItem(index)">x</button>
                                                    <button :disabled="row.tipo_credito" type="button" class="btn waves-effect waves-light btn-xs btn-info" @click="ediItem(row, index)" ><span style='font-size:10px;'>&#9998;</span> </button>

                                                </td>
                                            </tr>
                                            <tr><td colspan="9"></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6 d-flex align-items-end">
                                <div class="form-group">
                                    <button :disabled="this.form.idcuota!= null" type="button" class="btn waves-effect waves-light btn-primary" @click.prevent="clickAddItemInvoice">+ Agregar Producto</button>
                                </div>
                            </div>
                            <div class="col-md-12" style="display: flex; flex-direction: column; align-items: flex-end;">
                                <table>
                                    <tr v-if="form.total_taxed > 0 && enabled_discount_global">
                                        <td>
                                            DESCUENTO
                                            <template v-if="is_amount"> MONTO</template>
                                            <template v-else> %</template>
                                            <el-checkbox class="ml-1 mr-1" v-model="is_amount" @change="changeTypeDiscount"></el-checkbox>

                                        </td>
                                        <td>:</td>
                                        <td class="text-right">
                                            <el-input class="input-custom" v-model="total_global_discount" @input="calculateTotal"></el-input>
                                        </td>
                                    </tr>

                                    <tr v-if="form.detraction.amount > 0">
                                        <td>M. DETRACCIÓN</td>
                                        <td>:</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ form.detraction.amount }}</td>
                                    </tr>

                                    <tr v-if="form.total_exportation > 0">
                                        <td>OP.EXPORTACIÓN</td>
                                        <td>:</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ form.total_exportation }}</td>
                                    </tr>
                                    <tr v-if="form.total_free > 0">
                                        <td>OP.GRATUITAS</td>
                                        <td>:</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ form.total_free }}</td>
                                    </tr>
                                    <tr v-if="form.total_unaffected > 0">
                                        <td>OP.INAFECTAS</td>
                                        <td>:</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ form.total_unaffected }}</td>
                                    </tr>
                                    <tr v-if="form.total_exonerated > 0">
                                        <td>OP.EXONERADAS</td>
                                        <td>:</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ form.total_exonerated }}</td>
                                    </tr>
                                    <tr v-if="form.total_taxed > 0">
                                        <td>OP.GRAVADA</td>
                                        <td>:</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ form.total_taxed }}</td>
                                    </tr>
                                    <tr v-if="form.total_prepayment > 0">
                                        <td>ANTICIPOS</td>
                                        <td>:</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ form.total_prepayment }}</td>
                                    </tr>
                                    <tr v-if="form.total_igv > 0">
                                        <td>IGV</td>
                                        <td>:</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ form.total_igv }}</td>
                                    </tr>
                                    <tr v-if="form.total_plastic_bag_taxes > 0">
                                        <td>ICBPER</td>
                                        <td>:</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ form.total_plastic_bag_taxes }}</td>
                                    </tr>

                                </table>

                                <template v-if="form.total > 0">
                                    <h3 class="text-right" v-if="form.total > 0"><b>TOTAL A PAGAR: </b>{{ currency_type.symbol }} {{ form.total }}</h3>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions text-right mt-4">
                        <el-button @click.prevent="close()">Cancelar</el-button>
                        <el-button class="submit" type="primary" native-type="submit" :loading="loading_submit" v-if="form.items.length > 0 && this.dateValid">Generar</el-button>
                    </div>
                </form>
            </div>

        <document-form-item :showDialog.sync="showDialogAddItem"
                           :recordItem="recordItem"
                           :isEditItemNote="false"
                           :operation-type-id="form.operation_type_id"
                           :currency-type-id-active="form.currency_type_id"
                           :exchange-rate-sale="form.exchange_rate_sale"
                           :typeUser="'admin'"
                           :editNameProduct=1
                           @add="addRow"></document-form-item>

        <person-form :showDialog.sync="showDialogNewPerson"
                       type="customers"
                       :external="true"
                       :input_person="input_person"
                       :tipo_documento_id = "form.tipo_documento_id"></person-form>

    </div>
    </div>
</template>
<style scoped>
.input-custom{
    width: 50% !important;
}

.el-textarea__inner {
    height: 65px !important;
    min-height: 65px !important;
}
</style>
<script>
    import DocumentFormItem from './partes/item.vue'
    import PersonForm from './../persona/form.vue'
    import {functions, exchangeRate} from './../mixins/functions'
    import moment from 'moment'

    export default {
        // props: ['typeUser', 'configuration'],
        components: {DocumentFormItem, PersonForm },
        mixins: [functions, exchangeRate],
        data() {
            return {
                datEmision: {
                  disabledDate(time) {
                    return time.getTime() > moment();
                  }
                },
                // datVencimiento:{
                //     disabledDate(time) {
                //     return time.getTime() < moment();
                //   }
                // },
                dateValid:true,
                input_person:{},
                showDialogDocumentDetraction:false,
                has_data_detraction:false,
                showDialogFormHotel:false,
                showDialogFormTransport:false,
                is_client:false,
                recordItem: null,
                resource: 'documents',
                showDialogAddItem: false,
                showDialogNewPerson: false,
                showDialogOptions: false,
                loading_submit: false,
                loading_form: false,
                errors: {},
                form: {},
                form_payment: {},
                document_types: [],
                currency_types: [],
                discount_types: [],
                charges_types: [],
                all_customers: [],
                business_turns: [],
                form_payment: {},
                document_types_guide: [],
                customers: [],
                sellers: [],
                company: null,
                document_type_03_filter: null,
                operation_types: [],
                establishments: [],
                payment_method_types: [{id:1,description:'Credito'},{id:2,description:'Contado'}],
                tipos_contado:[{id:1, description:'EFECTIVO'},{id:2,description:'TARJETA'}  ],
                establishment: null,
                all_series: [],
                series: [],
                tipodocumen:[],
                tipocom:[],
                monedas:[],
                productos:[],
                clientes:[],
                compemitidos:[],
                prepayment_documents: [],
                motivosnota: [],
                motivostodo:[],
                difuntos:[],
                difuntostotal:[],
                creditos:[],
                credito:{},
                currency_type: {},
                difunto:{},
                documentNewId: null,
                prepayment_deduction:false,
                activePanel: 0,
                total_global_discount:0,
                loading_search:false,
                loading_difunto:false,
                is_amount:true,
                enabled_discount_global:false,
                user: null,
                is_receivable:false,
                is_contingency: false,
                cat_payment_method_types: [],
                select_first_document_type_03:false,
                detraction_types: [],
                all_detraction_types: [],
                customer_addresses:  [],
                payment_destinations:  [],
                form_cash_document: {},
                enabled_payments: true,
                readonly_date_of_due: false,
                seller_class: 'col-lg-5 pb-2',
            }
        },
        async created() {
            await this.loadMotivoCreditoDebito();
            await this.loadDifuntos();
            await this.loadUsers();
            await this.initForm()
            this.loading_form = true
        },
        methods: {
            changePaymentMethodType(){
                this.form.forma_pago = this.payment_method_types.find(i => i.id == this.form.payment_method_type_id).description;
                if (this.form.payment_method_type_id == 2) {
                    this.form.payments = [];
                    this.form.idpago_tipo_contado = 1;
                    this.form.forma_pago = 'Contado'
                } else {
                    this.form.idpago_tipo_contado = null;
                    this.form.forma_pago = 'Contado'
                    this.clickAddPayment();
                }

                // let payment_method_type = _.find(this.payment_method_types, {'id':this.form.payments[index].payment_method_type_id})

                // if(payment_method_type.number_days){

                //     this.form.date_of_due =  moment().add(payment_method_type.number_days,'days').format('dd-MM-yyyy')
                //     // this.form.payments = []
                //     this.enabled_payments = false
                //     this.readonly_date_of_due = true
                //     this.form.payment_method_type_id = payment_method_type.id

                // }else if(payment_method_type.id == '09'){

                //     this.form.payment_method_type_id = payment_method_type.id
                //     this.form.date_of_due = this.form.date_of_issue
                //     // this.form.payments = []
                //     this.enabled_payments = false

                // }else{

                //     this.form.date_of_due = this.form.date_of_issue
                //     this.readonly_date_of_due = false
                //     this.form.payment_method_type_id = null
                //     this.enabled_payments = true

                // }

            },

            keyupCustomer(){

                if(this.input_person.number){

                    if(!isNaN(parseInt(this.input_person.number))){

                        switch (this.input_person.number.length) {
                            case 8:
                                this.input_person.identity_document_type_id = '1'
                                this.showDialogNewPerson = true
                                break;

                            case 11:
                                this.input_person.identity_document_type_id = '6'
                                this.showDialogNewPerson = true
                                break;
                            default:
                                this.input_person.identity_document_type_id = '6'
                                this.showDialogNewPerson = true
                                break;
                        }
                    }
                }
            },

            clickAddItemInvoice(){
                this.recordItem = null
                this.showDialogAddItem = true
            },

            getFormatUnitPriceRow(unit_price){
                return _.round(unit_price, 6)
                // return unit_price.toFixed(6)
            },

            discountGlobalPrepayment(){

                let global_discount = 0
                this.form.prepayments.forEach((item)=>{
                    global_discount += parseFloat(item.amount)
                })

                // let base = (this.form.affectation_type_prepayment == 10) ? parseFloat(this.form.total_taxed):parseFloat(this.form.total_exonerated)
                let base = 0

                switch (this.form.affectation_type_prepayment) {
                    case 10:
                        base = parseFloat(this.form.total_taxed)
                        break;
                    case 20:
                        base = parseFloat(this.form.total_exonerated)
                        break;
                    case 30:
                        base = parseFloat(this.form.total_unaffected)
                        break;
                }

                let amount = _.round(parseFloat(global_discount), 2)
                let factor = _.round(amount/base, 4)

                this.form.total_prepayment = _.round(global_discount, 2)

                if(this.form.affectation_type_prepayment == 10){


                    let discount = _.find(this.form.discounts,{'discount_type_id':'04'})

                    if(global_discount>0 && !discount){
                        // console.log("gl 0")

                        this.form.total_discount =  _.round(amount,2)
                        this.form.total_taxed =  _.round(base - amount,2)
                        this.form.total_value =  _.round(base - amount,2)
                        this.form.total_igv =  _.round(this.form.total_value * 0.18,2)
                        this.form.total_taxes =  _.round(this.form.total_igv,2)
                        this.form.total =  _.round(this.form.total_value + this.form.total_taxes,2)

                        this.form.discounts.push({
                                discount_type_id: "04",
                                description: "Descuentos globales por anticipos gravados que afectan la base imponible del IGV/IVAP",
                                factor: factor,
                                amount: amount,
                                base: base
                            })

                    }else{

                        let pos = this.form.discounts.indexOf(discount);

                        if(pos > -1){

                            this.form.total_discount =  _.round(amount,2)
                            this.form.total_taxed =  _.round(base - amount,2)
                            this.form.total_value =  _.round(base - amount,2)
                            this.form.total_igv =  _.round(this.form.total_value * 0.18,2)
                            this.form.total_taxes =  _.round(this.form.total_igv,2)
                            this.form.total =  _.round(this.form.total_value + this.form.total_taxes,2)

                            this.form.discounts[pos].base = base
                            this.form.discounts[pos].amount = amount
                            this.form.discounts[pos].factor = factor

                        }

                    }

                }else if(this.form.affectation_type_prepayment == 20){

                    let exonerated_discount = _.find(this.form.discounts,{'discount_type_id':'05'})


                    this.form.total_discount =  _.round(amount,2)
                    this.form.total_exonerated =  _.round(base - amount,2)
                    this.form.total_value =  this.form.total_exonerated
                    this.form.total =  this.form.total_value

                    if(global_discount>0 && !exonerated_discount){

                        // console.log("gl 0")
                        this.form.discounts.push({
                                discount_type_id: '05',
                                description: 'Descuentos globales por anticipos exonerados',
                                factor: factor,
                                amount: amount,
                                base: base
                            })

                    }else{

                        let position = this.form.discounts.indexOf(exonerated_discount);

                        if(position > -1){

                            this.form.discounts[position].base = base
                            this.form.discounts[position].amount = amount
                            this.form.discounts[position].factor = factor

                        }

                    }

                }else if(this.form.affectation_type_prepayment == 30){

                    let unaffected_discount = _.find(this.form.discounts,{'discount_type_id':'06'})

                    this.form.total_discount =  _.round(amount,2)
                    this.form.total_unaffected =  _.round(base - amount,2)
                    this.form.total_value =  this.form.total_unaffected
                    this.form.total =  this.form.total_value

                    if(global_discount>0 && !unaffected_discount){

                        // console.log("gl 0")
                        this.form.discounts.push({
                                discount_type_id: '06',
                                description: 'Descuentos globales por anticipos inafectos',
                                factor: factor,
                                amount: amount,
                                base: base
                            })

                    }else{

                        let position = this.form.discounts.indexOf(unaffected_discount);

                        if(position > -1){

                            this.form.discounts[position].base = base
                            this.form.discounts[position].amount = amount
                            this.form.discounts[position].factor = factor

                        }

                    }
                }

            },

            setPendingAmount(){
                this.form.pending_amount_prepayment = this.form.has_prepayment ? this.form.total:0
            },

            clickAddPayment() {
                let total = 0;
                if (this.form.items.length > 0) {
                    this.form.items.map(i => total += i.total);
                }
                this.form.payments.push({
                    id: null,
                    document_id: null,
                    date_of_payment:  moment().add(30,'d').format('YYYY-MM-DD'),
                    payment_method_type_id: 1,
                    reference: null,
                    description: `${this.form.payments.length +1}`,
                   // payment_destination_id: this.getPaymentDestinationId(),
                    payment: total,
                });
                this.form.forma_pago = 'Credito';
                this.form.payment_method_type_id = 1;

            },

            clickCancel(index) {
                this.form.payments.splice(index, 1);
            },
            ediItem(row, index)
            {
                row.indexi = index
                this.recordItem = row
                this.showDialogAddItem = true
            },

            searchRemoteCustomers(input) {

                if (input.length > 2) {
                // if (input!="") {
                    // console.log("a")
                    this.loading_search = true
                    let parameters = `input=${input}&document_type_id=${this.form.document_type_id}&operation_type_id=${this.form.operation_type_id}`

                    axios.get(`/api/${this.resource}/search/customers?${parameters}`)
                            .then((response) => {
                                this.customers = response.data
                                this.loading_search = false
                                this.input_person.number = null

                                if(this.customers.length == 0){
                                    // console.log("b")
                                    this.filterCustomers()  
                                    this.input_person.number = input
                                }
                            })
                } else {
                    // this.customers = []
                    this.filterCustomers()
                    this.input_person.number = null
                }

            },

            initForm() {
                this.errors = {}
                this.form = {
                    establishment_id: null,
                    document_type_id: null,
                    series_id: null,
                    seller_id: null,
                    number: '#',
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: moment().format('HH:mm:ss'),
                    customer_id: null,
                    currency_type_id: 'PEN',
                    codmotivo:null,
                    purchase_order: null,
                    exchange_rate_sale: 0,
                    total_prepayment: 0,
                    total_charge: 0,
                    total_discount: 0,
                    total_exportation: 0,
                    total_free: 0,
                    total_taxed: 0,
                    total_unaffected: 0,
                    total_exonerated: 0,
                    total_igv: 0,
                    total_base_isc: 0,
                    total_isc: 0,
                    total_base_other_taxes: 0,
                    total_other_taxes: 0,
                    total_plastic_bag_taxes: 0,
                    total_taxes: 0,
                    total_value: 0,
                    total: 0,
                    operation_type_id: null,
                    date_of_due: moment().format('YYYY-MM-DD'),
                    items: [],
                    charges: [],
                    discounts: [],
                    attributes: [],
                    guides: [],
                    payments: [],
                    prepayments: [],
                    legends: [],
                    detraction: {},
                    iddifunto: null,
                    additional_information:null,
                    plate_number:null,
                    has_prepayment:false,
                    affectation_type_prepayment:null,
                    actions: {
                        format_pdf:'a4',
                    },
                    serie_correlativo:'',
                    hotel: {},
                    transport: {},
                    customer_address_id:null,
                    pending_amount_prepayment:0,
                    payment_method_type_id:null,
                    show_terms_condition: true,
                    terms_condition: '',
                    tipo_comprobante_ref_id:null,
                }

                this.form_cash_document = {
                    document_id: null,
                    sale_note_id: null
                }

                this.clickAddPayment()
                this.is_receivable = false
                this.total_global_discount = 0
                this.is_amount = true
                this.prepayment_deduction = false
                this.imageDetraction = {}
               // this.$eventHub.$emit('eventInitForm')


                // if(!this.configuration.restrict_receipt_date){
                //   this.datEmision = {}
                // }

                this.enabled_payments = true
                this.readonly_date_of_due = false
            },

            resetForm() {
                this.activePanel = 0
                this.initForm()
                this.form.establishment_id = (this.establishments.length > 0)?this.establishments[0].id:null
                this.form.operation_type_id = (this.operation_types.length > 0)?this.operation_types[0].id:null
                this.form.seller_id = (this.sellers.length > 0)?this.sellers[0].id:null;


            },
            
            async changeDetractionType(){
                // let detraction_type = await _.find(this.detraction_types, {'id':this.form.detraction.detraction_type_id})

                if(this.form.detraction){

                    this.form.detraction.amount = (this.form.currency_type_id == 'PEN') ? _.round(parseFloat(this.form.total) * (parseFloat(this.form.detraction.percentage)/100),2) : _.round((parseFloat(this.form.total) * this.form.exchange_rate_sale) * (parseFloat(this.form.detraction.percentage)/100),2)

                    // this.form.detraction.amount = _.round(parseFloat(this.form.total) * (parseFloat(this.form.detraction.percentage)/100),2)
                    // console.log(this.form.detraction.amount)
                }
            },
            
            changeDocumentType() {
                // this.form.payments = [];
                this.form.items = [];
                this.form.codmotivo = '';
                this.form.date_of_issue = moment().format('YYYY-MM-DD');
                this.form.date_of_due = moment().format('YYYY-MM-DD');
                this.form.tipo_comprobante_ref_id = null;
                this.filterSeries();
                if (this.form.document_type_id > 2) {
                    let dato = {document_type_id: this.form.document_type_id};
                    axios.post('/api/comprobante/buscar/tipocomprobante', dato).then( ({data}) => {
                        this.compemitidos = data;
                        this.compemitidos.map(i => i.serietotal = i.serie + ' - '+i.correlativo );
                    })
                    this.filterMotivos();
                } else {
                    this.form.payments = [];
                    this.form.items = [];
                    this.clickAddPayment();
                    this.form.total_unaffected = 0;
                    this.form.total_exonerated = 0;
                    this.form.total_taxed = 0;
                    this.form.total_igv = 0;
                    this.form.total = 0;
                }
            },
            filterMotivos(){
                if (this.form.document_type_id == 4 || this.form.document_type_id == 3) {
                    this.motivosnota = this.motivostodo[0]
                } else {
                    this.motivosnota = this.motivostodo[1]
                }
            },

            changeDateOfIssue() {
              let minDate = moment().subtract(7, 'days')
              if(moment(this.form.date_of_issue) < minDate ) {
                this.$message.error('No puede seleccionar una fecha menor a 6 días.');
                this.dateValid=false
              } else { this.dateValid = true }
                // this.form.date_of_due = this.form.date_of_issue
                // this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                //     this.form.exchange_rate_sale = response
                // })
            },
            
            filterSeries() {
                this.form.series_id = null
                // this.series = _.find(i => i.tipo_comprobante_id == this.form.id)
                this.series = _.filter(this.all_series, {'tipo_comprobante_id': this.form.document_type_id,});
                this.form.series_id = (this.series.length > 0)?this.series[0].id:null
                this.selectCorrelativo();
            },

            filterCustomers() {
                // this.form.customer_id = null
                // if(this.form.operation_type_id === '0101' || this.form.operation_type_id === '1001') {

                if (['0101', '1001', '1004'].includes(this.form.operation_type_id)) {

                    if(this.form.document_type_id === '01') {
                        this.customers = _.filter(this.all_customers, {'identity_document_type_id': '6'})
                    } else {
                        if(this.document_type_03_filter) {
                            this.customers = _.filter(this.all_customers, (c) => { return c.identity_document_type_id !== '6' })
                        } else {
                            this.customers = this.all_customers
                        }
                    }

                } else {
                    this.customers = this.all_customers
                }
            },

            addRow(row) {
                if(this.recordItem)
                {
                    //this.form.items.$set(this.recordItem.indexi, row)
                    this.form.items[this.recordItem.indexi] = row
                    this.recordItem = null
                }
                else{
                    this.form.items.push(JSON.parse(JSON.stringify(row)));
                }
                this.form.items.map(i => i.item.descripcion = '');
                this.calculateTotal();
            },

            clickRemoveItem(index) {
                this.form.items.splice(index, 1)
                this.calculateTotal()
            },

            calculateTotal() {
                let total_discount = 0
                let total_charge = 0
                let total_exportation = 0
                let total_taxed = 0
                let total_exonerated = 0
                let total_unaffected = 0
                let total_free = 0
                let total_igv = 0
                let total_value = 0
                let total = 0
                let total_plastic_bag_taxes = 0
                this.form.items.forEach((row) => {
                    total_discount += parseFloat(row.total_discount)
                    total_charge += parseFloat(row.total_charge)

                    if (row.affectation_igv_type_id === '10') {
                        total_taxed += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '20') {
                        total_exonerated += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '30') {
                        total_unaffected += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '40') {
                        total_exportation += parseFloat(row.total_value)
                    }
                    if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) < 0) {
                        total_free += parseFloat(row.total_value)
                    }
                    if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) > -1) {
                        total_igv += parseFloat(row.total_igv)
                        total += parseFloat(row.total)
                    }
                    total_value += parseFloat(row.total_value)
                    total_plastic_bag_taxes += parseFloat(row.total_plastic_bag_taxes)

                    if (['13', '14', '15'].includes(row.affectation_igv_type_id)) {

                        let unit_value = (row.total_value/row.quantity) / (1 + row.percentage_igv / 100)
                        let total_value_partial = unit_value * row.quantity
                        row.total_taxes = row.total_value - total_value_partial
                        row.total_igv = row.total_value - total_value_partial
                        row.total_base_igv = total_value_partial
                        total_value -= row.total_value

                    }

                });

                this.form.total_exportation = _.round(total_exportation, 2)
                this.form.total_taxed = _.round(total_taxed, 2)
                this.form.total_exonerated = _.round(total_exonerated, 2)
                this.form.total_unaffected = _.round(total_unaffected, 2)
                this.form.total_free = _.round(total_free, 2)
                this.form.total_igv = _.round(total_igv, 2)
                this.form.total_value = _.round(total_value, 2)
                this.form.total_taxes = _.round(total_igv, 2)
                this.form.total_plastic_bag_taxes = _.round(total_plastic_bag_taxes, 2)
                // this.form.total = _.round(total, 2)
                this.form.total = _.round(total + this.form.total_plastic_bag_taxes, 2)

                if(this.enabled_discount_global)
                    this.discountGlobal()

                if(this.prepayment_deduction)
                    this.discountGlobalPrepayment()

                if(['1001', '1004'].includes(this.form.operation_type_id))
                    this.changeDetractionType()

                this.setTotalDefaultPayment()
                this.setPendingAmount()

            },

            setTotalDefaultPayment(){

                if(this.form.payments.length > 0){

                    this.form.payments[0].payment = this.form.total
                }
            },

            changeTypeDiscount(){
                this.calculateTotal()
            },

            discountGlobal(){

                let base = this.form.total_taxed

                let amount = (this.is_amount) ? parseFloat(this.total_global_discount) : parseFloat(this.total_global_discount)/100 * base
                let factor = (this.is_amount) ? _.round(amount/base,5) : _.round(parseFloat(this.total_global_discount)/100,5)

                if(this.total_global_discount>0 && this.form.discounts.length == 0){

                    this.form.discounts.push({
                            discount_type_id: "02",
                            description: "Descuento Global afecta a la base imponible",
                            factor: 0,
                            amount: 0,
                            base: 0
                        })

                }


                if(this.form.discounts.length){

                    this.form.total_discount =  _.round(amount,2)
                    this.form.total_value =  _.round(base - amount,2)
                    this.form.total_igv =  _.round(this.form.total_value * 0.18,2)
                    this.form.total_taxes =  _.round(this.form.total_igv,2)
                    this.form.total =  _.round(this.form.total_value + this.form.total_taxes,2)

                    this.form.total_taxed =  this.form.total_value

                    this.form.discounts[0].base = base
                    this.form.discounts[0].amount = _.round(amount,2)
                    this.form.discounts[0].factor = factor
                }


                // console.log(this.form.discounts)
            },

            async submit() {
                let validate = await this.validate_payments()
                if(validate.acum_total > parseFloat(this.form.total) || validate.error_by_item > 0) {
                    return this.$message.error('Los montos ingresados superan al monto a pagar o son incorrectos');
                }

                if (this.form.codmotivo) {
                    this.form.descripcion_motivo = this.motivosnota.find(i => i.id == this.form.codmotivo).descripcion
                }
                if (this.form.tipo_comprobante_ref_id) {
                    let comprobante = this.compemitidos.find(i => i.id == this.form.tipo_comprobante_ref_id);
                    this.form.tipo_comprobante_ref_id = comprobante.tipo_comprobante_id;
                    this.form.serie_ref = comprobante.serie;
                    this.form.correlativo_ref = comprobante.correlativo;
                }
                this.loading_submit = true
                await axios.post(`/api/comprobante`, this.form).then(response => {
                    //this.$eventHub.$emit('reloadDataItems', null)
                    this.resetForm();
                    this.loadUsers();
                    this.documentNewId = response.data.data.id;
                    this.showDialogOptions = true;

                    this.form_cash_document.document_id = response.data.data.id;
                    this.form.payments = [];
                    this.form.items = [];
                    // this.clickAddPayment();
                    this.form.total_unaffected = 0;
                    this.form.total_exonerated = 0;
                    this.form.total_taxed = 0;
                    this.form.total_igv = 0;
                    this.form.total = 0;
                    this.form.payments = [];
                    this.form.items = [];
                    this.form.codmotivo = '';
                    this.form.date_of_issue = moment().format('YYYY-MM-DD');
                    this.form.date_of_due = moment().format('YYYY-MM-DD');
                    this.form.tipo_comprobante_ref_id = null;
                    this.form.document_type_id = null;
                    this.loading_submit = false;
                }).catch(error => {
                    this.loading_submit = false;
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    }
                    else {
                        this.$message.error(error.response.data.message);
                    }
                })
            },

            validate_payments(){

                //eliminando items de pagos
                for (let index = 0; index < this.form.payments.length; index++) {
                    if(parseFloat(this.form.payments[index].payment) === 0)
                        this.form.payments.splice(index, 1)
                }

                let error_by_item = 0
                let acum_total = 0

                this.form.payments.forEach((item)=>{
                    acum_total += parseFloat(item.payment)
                    if(item.payment <= 0 || item.payment == null) error_by_item++;
                })

                return  {
                    error_by_item : error_by_item,
                    acum_total : acum_total
                }

            },

            close() {
                window.history.back();
            },

            selectComprobanteEmitido(){
                this.form.payments = [];
                this.form.items = [];
                let comprobante = this.compemitidos.find(i => i.id == this.form.tipo_comprobante_ref_id);
                this.form.currency_type_id = comprobante.moneda_id;
                this.form.date_of_issue = comprobante.fecha_emision;
                this.form.date_of_due = comprobante.fecha_vencimiento;
                this.form.customer_id = comprobante.cliente_id;
                this.form.payment_method_type_id = comprobante.forma_pago = 'Credito' ? 1 : 2;
                this.form.total_unaffected = comprobante.op_inafectas;
                this.form.total_exonerated = comprobante.op_exoneradas;
                this.form.total_taxed = comprobante.op_gravadas;
                this.form.total_igv = comprobante.igv;
                this.form.total = comprobante.total;
                comprobante.cuota.map(i => {
                    let cuota = {
                        date_of_payment:i.fecha_vencimiento,
                        description:i.numero,
                        document_id:null,
                        id:i.id,
                        payment:i.importe,
                        // payment_method_type_id:,
                        // reference:null,
                    }
                    this.form.payments.push(cuota);
                });
                comprobante.detalle.map(i => {
                    let item = {
                        affectation_igv_type_id:"10",
                        // attributes:Array[0],
                        // charges:Array[0],
                        currency_type_id:comprobante.moneda_id,
                        // discounts:Array[0],
                        document_item_id:i.id,
                        input_unit_price_value:i.precio_unitario,
                        item:{                        
                            afectacion:i.producto_detalle.afectacion,
                            almacen:i.producto_detalle.almacen,
                            categoria:i.producto_detalle.categoria,
                            cod_barra:null,
                            codigo_sunat:i.producto_detalle.codigo_sunat,
                            fec_venc:i.producto_detalle.fec_venc,
                            id:i.producto_detalle.id,
                            idalmacen:i.producto_detalle.idalmacen,
                            idcategoria:i.producto_detalle.idcategoria,
                            idmoneda:i.producto_detalle.idmoneda,
                            idtipoafectacion:i.producto_detalle.idtipoafectacion,
                            img_pro:null,
                            moneda:comprobante.moneda_id,
                            nombre:i.producto,
                            // presentation:Object (empty),
                            // stock_ini:i.stock,
                            unidad:i.producto_detalle.unidad,
                            unidad_id:i.producto_detalle.unidad_id,
                            unit_price:i.precio_unitario,
                            valor_unitario:i.valor_unitario,
                            descripcion:i.descripcion,
                        },
                        item_id:1,
                        percentage_igv:18,
                        percentage_isc:0,
                        percentage_other_taxes:0,
                        price_type_id:"01",
                        quantity:1,
                        system_isc_type_id:null,
                        total:5,
                        total_base_igv:4.24,
                        total_base_isc:0,
                        total_base_other_taxes:0,
                        total_charge:0,
                        total_discount:0,
                        total_igv:0.76,
                        total_isc:0,
                        total_other_taxes:0,
                        total_plastic_bag_taxes:0,
                        total_taxes:0.76,
                        total_value:4.24,
                        unit_price:5,
                        unit_value:4.237288135593221,
                        warehouse_id:null,
                    }
                    this.form.items.push(item)
                });
            },

            changeCustomer() {
                this.form.idcredito = null;
                this.form.idcuota = null;
                this.form.items = [];
                this.loadCreditos(this.form.customer_id);
                // this.customer_addresses = [];
                // let customer = _.find(this.customers, {'id': this.form.customer_id});
                // this.customer_addresses = customer.addresses;
                // if(customer.address)
                // {
                //     this.customer_addresses.unshift({
                //         id:null,
                //         address: customer.address
                //     })
                // }


                /*if(this.customer_addresses.length > 0) {
                    let address = _.find(this.customer_addresses, {'main' : 1});
                    this.form.customer_address_id = address.id;
                }*/
                this.calculateTotal();
            },

            loadUsers(){
                axios.get("/api/moneda").then(({ data }) => (this.monedas = data)).catch(error => { this.error = error.response });
                axios.get("/api/tipocomprobante").then(({ data }) => (this.tipocom = data)).catch(error => { this.error = error.response });
                axios.get("/api/tipodocumento").then(({ data }) => (this.tipodocumen = data));
                axios.get("/api/serie").then(({ data }) => (this.all_series = data,this.series = data));
                // axios.get("/api/producto").then(({ data }) => (this.productos = data)).catch(error => { this.error = error.response });
                axios.get("/api/clientes").then(({ data }) => (this.customers = data)).catch(error => { this.error = error.response });
            },

            selectCorrelativo(){
                let serie = this.series.find(i => i.id == this.form.series_id);
                this.form.serie = serie.serie;
                this.form.serie_correlativo = serie.correlativo;
            },

            loadMotivoCreditoDebito(){
                axios.get('/api/tipodocumento/motivocreditodebito').then( ({data}) => {
                    this.motivostodo = data;
                })
            },

            loadDifuntos(){
                axios.get('/api/difunto').then( ({data}) => {
                    this.difuntos = data;
                    this.difuntostotal = data;
                })
            },

            loadCreditos(idcliente){
                axios.get('/api/credito/todobyidcliente/' + idcliente).then( ({data}) => {
                    this.creditos = data;
                    this.creditos.map(i => {
                        i.arraydetalle = i.arraydetalle.filter(i => {
                            if (i.estado == 1) {
                                return true;
                            }
                        })
                    })
                })
            },

            changeCredito(){
                this.form.items = [];
                this.form.idcuota = null;
                this.credito = this.creditos.find(i => i.id == this.form.idcredito);
                this.credito.arraydetalle.map(i  => {
                    i.fecha_nueva = momento(i.fecha_vencimiento).format('DD/MM/YYYY');
                })
                this.calculateTotal();
            },

            changeCuota(){
                this.forma_pago = 'Contado';
                this.form.items = [];
                this.form.payment_method_type_id = 2;
                this.form.payments = [];
                this.form.idpago_tipo_contado = 1;
                this.form.forma_pago = 'Contado';
                let cuota = this.credito.arraydetalle.find(i  => i.id == this.form.idcuota);
                let subtotal = (parseFloat(cuota.saldo).toFixed(2) / 1.18).toFixed(2);
                let igv = (cuota.saldo - parseFloat(subtotal).toFixed(2)).toFixed(2);
                let precioigv =  (parseFloat(cuota.saldo).toFixed(6) / 1.18).toFixed(6);
                this.form.items.push({
                    affectation_igv_type_id:"10",
                    attributes:[],
                    charges:[],
                    currency_type_id:"PEN",
                    discounts:[],
                    document_item_id:119,
                    input_unit_price_value:cuota.saldo,
                    item:{
                        afectacion:"OP. GRAVADAS",
                        almacen:"CEMENTERIO GENERAL",
                        categoria:"OTROS",
                        cod_barra:null,
                        codigo_sunat:null,
                        created_at:"2022-06-15T21:52:17.000000Z",
                        descripcion:"",
                        estado:1,
                        fec_venc:null,
                        id:119,
                        idalmacen:1,
                        idcategoria:2,
                        idmoneda:"PEN",
                        idtipoafectacion:"10",
                        img_pro:null,
                        nombre:`PAGO POR CRÉDITO N° ${this.credito.nrocredito} CORRESPONDIENTE A LA CUOTA N° ${cuota.nro_cuota}`,
                        presentation:{},
                        stock_ini:null,
                        tipo:"P",
                        unidad:"UNIDAD",
                        unidad_id:"NIU",
                        unit_price:cuota.saldo,
                        updated_at:"2022-07-14T15:12:45.000000Z",
                        valor_unitario:cuota.saldo,
                    },
                    item_id:1,
                    percentage_igv:18,
                    percentage_isc:0,
                    percentage_other_taxes:0,
                    price_type_id:"01",
                    quantity:1,
                    system_isc_type_id:null,
                    total:cuota.saldo,
                    total_base_igv:subtotal,
                    total_base_isc:0,
                    total_base_other_taxes:0,
                    total_charge:0,
                    total_discount:0,
                    total_igv:igv,
                    total_isc:0,
                    total_other_taxes:0,
                    total_plastic_bag_taxes:0,
                    total_taxes:igv,
                    total_value:subtotal,
                    unit_price:cuota.saldo,
                    unit_value:precioigv,
                    warehouse_id:null,
                    tipo_credito:true,
                });
                this.calculateTotal();
            },

            changeDifunto(){
                if (this.form.iddifunto) {
                    this.difunto = this.difuntostotal.find(i => i.id == this.form.iddifunto);
                } else {
                    this.difunto = {};
                }
            },

            searchRemoteDifuntos(input){
                if (input.length > 2) {
                // if (input!="") {
                    // console.log("a")
                    this.loading_difunto = true
                    let parameters = `input=${input}`

                    axios.get(`/api/${this.resource}/search/difuntos?${parameters}`)
                            .then((response) => {
                                this.difuntos = response.data
                                this.loading_difunto = false
                            })
                } else {
                    this.difuntos = this.difuntostotal
                    // this.customers = []
                    // this.loadDifuntos();
                }
            },
            actualizarMontos(row){
                let subtotal = (parseFloat(row.unit_price).toFixed(2) / 1.18).toFixed(2);
                let igv = (row.unit_price - parseFloat(subtotal).toFixed(2)).toFixed(2);
                let precioigv =  (parseFloat(row.unit_price).toFixed(6) / 1.18).toFixed(6);

                row.input_unit_price_value= row.unit_price;
                row.item.unit_price= row.unit_price;
                row.item.valor_unitario= row.unit_price;
                row.total= row.unit_price;
                row.total_base_igv= subtotal;
                row.total_taxes= igv;
                row.total_value= subtotal;
                row.unit_price= row.unit_price;
                row.unit_value= precioigv;
                row.tipo_credito= true;
                this.calculateTotal();
            },
        }
    }
</script>
