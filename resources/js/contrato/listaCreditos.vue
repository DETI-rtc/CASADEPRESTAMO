<template>
    <div>
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Créditos</h3>
                <div class="card-tools">
                    <button class="btn btn-primary" @click="newModal">Nuevo Crédito<i
                            class="fas fa-user-plus fa-fw"></i></button>
                </div>
            </div>
            <div class="card-body p-3">
                <v-client-table :data="creditos" :columns="columna" :options="options">
                    <div slot="cliente" slot-scope="{row}">
                        <span>{{row.contrato.nombre +' '+ row.contrato.apellido_pat +' '+ row.contrato.apellido_mat}}</span>
                    </div>
                    <div slot="nrocontrato" slot-scope="{row}">
                        <span>{{row.contrato.nro}}</span>
                    </div>
                    <div slot="fecha_inicio" slot-scope="{row}">
                        <span>{{row.fecha_inicio | myDate}}</span>
                    </div>
                    <div slot="fecha_fin" slot-scope="{row}">
                        <span>{{row.fecha_fin | myDate}}</span>
                    </div>
                    <div slot="monto_pagare" slot-scope="{row}">
                        <span>{{row.monto_pagare | moneda}}</span>
                    </div>
                    <div slot="cuota_inicial" slot-scope="{row}">
                        <span>{{row.cuota_inicial | moneda}}</span>
                    </div>
                    <div slot="monto_contratado" slot-scope="{row}">
                        <span>{{row.monto_contratado | moneda}}</span>
                    </div>
                    <div slot="estado" slot-scope="{row}">
                        <span class="badge badge-info" v-if="row.estado == 1">
                            Por Pagar
                        </span>
                        <span class="badge badge-success" v-else-if="row.estado == 2">
                            Pagado
                        </span>
                        <span class="badge badge-danger" v-else>
                            Anulado
                        </span>
                    </div>
                    <div slot="acciones" class="btn-group" role="group" slot-scope="{row}">
                        <el-button-group class="group">
                            <el-button size="mini" type="primary" @click="abrirCronogramaModal(row)" ><i class="fas fa-eye"></i></el-button>
                            <el-button v-show="row.estado == 1" size="mini" type="success" @click="editModal(row)" ><i class="fas fa-edit"></i></el-button>
                            <el-button v-show="row.estado == 1" size="mini" type="danger" @click="deleteCredito(row.id)" ><i class="fas fa-trash"></i></el-button>
                            <el-button size="mini" type="warning" @click="printCronograma(row)" ><i class="fas fa-print"></i></el-button>
                            <!-- <el-button size="mini" type="warning" @click="print(row.id)"><i class="fas fa-print"></i></el-button> -->
                        </el-button-group>
                    </div>
                </v-client-table>
            </div>
        </div>

        <!-- EDITAR CREDITO -->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="addNew" tabindex="-1" role="dialog"
            aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content" :class="!editmode ? 'card card-primary' : 'card card-success'">
                    <div class="card-header">
                        <h5 class="card-title" v-show="!editmode" id="addNewLabel">Nuevo Crédito</h5>
                        <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar Crédito</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-bs-dismiss="modal"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label>Contratos</label>
                                <el-select v-model="form.idcontrato" @change="seleccionarContrato" filterable popper-class="el-select-provinces" dusk="province_id">
                                    <el-option v-for="option in contratos" :key="option.id" :value="option.id" :label="option.nro"></el-option>
                                </el-select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Cliente</label>
                                <input disabled v-model="form.cliente" type="text" class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Monto Contratado</label>
                                <input disabled v-model="form.monto_contratado" type="text" class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Cuota Inicial</label>
                                <input v-model="form.cuota_inicial" type="number" class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Monto a pagar</label>
                                <input disabled v-model="form.monto_pagare = CalcularPagare" type="number" class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Fecha de Inicio</label>
                                <input v-model="form.fecha_inicio" type="date" class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Fecha de Término</label>
                                <input disabled v-model="form.fecha_fin = calcularFechaFin" type="date" class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-md-3">
                                <label>N° de Cuotas</label>
                                <input v-model="form.nrocuotas" type="number" class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Monto mensual</label>
                                <input disabled v-model="form.monto_mensual = CalcularMensual" type="number" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" @click="cerrarModal">Cerrar</button>
                        <button @click="updateCredito" v-show="editmode" type="button" class="btn btn-success">Actualizar</button>
                        <button :disabled="disablebtn" @click="createCredito" v-show="!editmode" type="button" class="btn btn-primary">Crear</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- CRONOGRAMA -->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="cronogramaModal" tabindex="-1" role="dialog"
            aria-labelledby="cronogramaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content card card-primary">
                    <div class="card-header">
                        <h5 class="card-title" id="cronogramaModalLabel">Cronograma</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-bs-dismiss="modal"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N° Cuota</th>
                                        <th>Deuda</th>
                                        <th>Fec. Vencimiento</th>
                                        <th>Pagado</th>
                                        <!-- <th>Fec. Pago.</th> -->
                                        <th>Saldo</th>
                                        <th>Situación</th>
                                        <!-- <th>Comprobantes</th> -->
                                        <th>Pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="credi in cliente.arraydetalle">
                                        <td>{{credi.nro_cuota}}</td>
                                        <td>{{credi.monto_deuda | moneda}}</td>
                                        <td>{{credi.fecha_vencimiento | myDate}}</td>
                                        <td>{{credi.monto_pagado | moneda}}</td>
                                        <!-- <td>{{credi.fecha_pagado | myDate}}</td> -->
                                        <td>{{credi.saldo | moneda}}</td>
                                        <td>
                                            <span class="badge badge-info" v-if="credi.estado == 1">
                                                Por Pagar
                                            </span>
                                            <span class="badge badge-success" v-else-if="credi.estado == 2">
                                                Pagado
                                            </span>
                                            <span class="badge badge-danger" v-else>
                                                Anulado
                                            </span>
                                        </td>
                                        <td>
                                            <button @click="verComprobantes(credi)" v-show="credi.comprobantes.length > 0" type="button" class="btn btn-warning"><i class="fas fa-eye"></i></button>
                                            <button @click="irPagar()" v-show="credi.saldo > 0" type="button" class="btn btn-primary"><i class="fas fa-receipt"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- VER COMPROBANTES -->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="comprobantesModal" tabindex="-1" role="dialog"
            aria-labelledby="comprobantesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content card card-primary">
                    <div class="card-header">
                        <h5 class="card-title" id="comprobantesModalLabel">Comprobantes Emitidos</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-bs-dismiss="modal"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N° Comprobante</th>
                                        <th>Fecha Emisión</th>
                                        <th>Monto</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="compro in detallecredito.comprobantes">
                                        <td>{{compro.serie + '-'+ compro.correlativo}}</td>
                                        <td>{{compro.fecha_emision | myDate}}</td>
                                        <td>{{compro.total | moneda}}</td>
                                        <td>
                                            <el-button-group class="group">
                                                <el-button type="warning" @click="printComprobante(compro, 'ticket')" :loading="disablebtn"><i class="fas fa-print"></i>Ticket</el-button>
                                                <el-button type="warning" @click="printComprobante(compro, 'a4')" :loading="disablebtn"><i class="fas fa-print"></i>A4</el-button>
                                            </el-button-group>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                loading: true,
                editmode: false,
                disablebtn:false,
                creditos: [],
                clientes:[],
                contratos:[],
                cliente:{},
                detallecredito:{},
                columna: ['nrocredito','nrocontrato','cliente','monto_contratado','nrocuotas','fecha_inicio','fecha_fin','cuota_inicial','monto_pagare','estado','acciones'],
                options: {
                    headings: {
                    },
                    perPage: 15,
                    perPageValues: [15, 50, 100],
                    skin: 'table table-striped table-hover',
                    filterable: ['nrocredito','contrato.nro','cliente'],
                    texts: {
                        count: 'Mostrando {from} a {to} de {count} registros|{count} registros|Un registro',
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
                form: new Form({
                    id: '',
                    fecha_inicio: momento().format('yyyy-MM-DD'),
                    idcliente: '',
                    cliente:'',
                    idcontrato: '',
                    nrocuotas: 0,
                    cuota_inicial: 0,
                    monto_pagare: 0,
                    fecha_fin: momento().add(momento().daysInMonth(),'d').format('YYYY-MM-DD'),
                    monto_contratado: 0,
                    monto_mensual:0,
                }),
            }
        },
        computed:{
            CalcularPagare: function () {
                let total = 0;
                if (this.form.idcontrato && this.form.cuota_inicial) {
                    total = parseFloat(this.form.monto_contratado) - parseFloat(this.form.cuota_inicial)
                }
                return total;
            },
            CalcularMensual: function () {
                let total = 0;
                if (this.form.idcontrato && this.form.nrocuotas) {
                    total = parseFloat(this.form.monto_pagare) / parseFloat(this.form.nrocuotas);
                }
                return  total;
            },
            calcularFechaFin: function (){
                let fecha = '';
                if (this.form.fecha_inicio) {
                    fecha = momento(this.form.fecha_inicio).add(this.form.nrocuotas,'M').format('YYYY-MM-DD');
                }
                return fecha
            }
        },
        methods: {
            loadCreditos() {
                let dato = {};
                axios.get("/api/creditos", dato)
                    .then(({data}) => {
                        this.creditos = data;
                        // this.creditos.map(i => {
                        //     i.arraydetalle.map(j => {
                        //         let nrocomprobantes ='';
                        //         if (j.comprobantes.length > 0) {
                        //             // nrocomprobantes = j.comprobantes[0].serie+'-'+j.comprobantes[0].correlativo;
                        //             if (j.comprobantes.length > 1) {
                        //                 j.comprobantes.map(k => {
                        //                     nrocomprobantes += `${k.serie}-${k.correlativo}, `;
                        //                 })
                        //             }
                        //         }
                        //         j.nrocomprobantes = nrocomprobantes;
                        //     })
                        // })
                    })
                    .catch(error => {
                        Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                    });
            },
            createCredito() {
                this.disablebtn = true;
                this.form.post('/api/creditos')
                    .then(() => {
                        this.loadCreditos();
                        $('#addNew').modal('hide')
                        Swal.fire(
                            'Correcto',
                            'Crédito creado satisfactoriamente',
                            'success',
                        )
                        this.form.reset();
                        this.form.clear();
                        this.disablebtn = false;
                    })
                    .catch(() => {
                        this.disablebtn = false;
                        Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                    })
            },
            updateCredito() {
                this.form.put('/api/creditos/' + this.form.id)
                    .then(() => {
                        $('#addNew').modal('hide');
                        Swal.fire('Ingresado','El documento se actualizó','success')
                        this.loadCreditos();
                    })
                    .catch(() => {
                        Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                    });
            },
            deleteCredito(id) {
                Swal.fire({
                    title: 'Estas Seguro',
                    text: "Si se elimina no se podra revertir",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminar!'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/api/creditos/' + id).then(() => {
                            Swal.fire(
                                'Eliminado!',
                                'El documento fue eliminado.',
                                'success'
                            )
                            this.loadCreditos();
                        }).catch(() => {
                            Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                        });
                    }
                })
            },
            editModal(row) {
                this.editmode = true;
                this.form.clear();
                this.form.reset();
                this.form.fill(row);
                $('#addNew').modal('show');
            },
            newModal() {
                this.editmode = false;
                $('#addNew').modal('show');
            },
            cerrarModal(){
                this.form.reset();
                $('#addNew').modal('hide');
            },
            abrirCronogramaModal(row){
                this.cliente = row;
                $('#cronogramaModal').modal('show');
            },
            loadContratos(){
                axios.get('/api/contrato/contratosactivos').then( ({data}) => {
                    this.contratos = data;
                })
            },
            seleccionarContrato(){
                this.cliente = this.contratos.find(i => i.id == this.form.idcontrato);
                this.form.idcliente = this.cliente.idcliente
                this.form.monto_contratado = this.cliente.monto
                this.form.cliente = this.cliente.nombre + ' ' + this.cliente.apellido_pat +' '+this.cliente.apellido_mat;
            },
            irPagar(){
                window.open(`/regcomprobante`, 'blank');
            },
            verComprobantes(row){
                this.detallecredito = row;
                $('#comprobantesModal').modal('show');
            },
            printComprobante(row, tipo){
                this.disablebtn = true;
                row.tipo_impresion = tipo;
                axios.post('/api/comprobante/crearPDF',row).then( (data) => {
                    window.open(`${window.location.origin}/docs/${tipo}/${row.tipo_comprobante_id}-${row.serie}-${row.correlativo}.pdf`)
                    this.disablebtn = false;
                }).catch(() => {
                    this.disablebtn = false;
                })
            },
            printCronograma(row){
                window.open('/print/cronograma/'+row.id, 'blank');
            },
        },
        created() {
            this.loadContratos();
            this.loadCreditos();
            document.title = 'Créditos - SBH';
        }

    }

</script>
