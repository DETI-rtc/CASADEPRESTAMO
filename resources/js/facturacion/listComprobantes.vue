<template>
    <div>
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Comprobantes emitidos</h3>
                <div class="card-tools">
                    <!-- <v-select @input="loadComprobantes" :reduce="anio => anio.anio" :options="anios" v-model="anio" label="anio">
                    </v-select> -->
                    <div class="row">
                        <!-- <div class="col-md-3"></div> -->
                        <div class="col-md-5">
                            <el-select v-model="anio" @input="loadComprobantes" class="border-left rounded-left">
                                <el-option v-for="option in anios" :key="option.anio" :value="option.anio" :label="option.anio"></el-option>
                            </el-select>
                        </div>
                        <div class="col-md-7">
                            <button class="btn btn-primary" @click="nuevoComprobante">Emitir comprobante<i
                                    class="fas fa-user-plus fa-fw"></i></button>
                            <!-- <button class="btn btn-primary" @click="emitirtodo">Envio<i
                                    class="fas fa-user-plus fa-fw"></i></button> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <v-client-table :data="comprobantes" :columns="columna" :options="options">
                    <div slot="estado_comprobante" slot-scope="{row}">
                        <span class="badge badge-success" v-if="row.codigo_sunat == '0'">
                            Aceptado
                        </span>
                        <span class="badge badge-danger" v-else>
                            Rechazado
                        </span>
                    </div>
                    <div slot="serie" slot-scope="{row}">
                        {{row.serie}}-{{row.correlativo}}
                    </div>
                    <div slot="fecha_emision" slot-scope="{row}">
                        {{row.fecha_emision|myDate}}
                    </div>
                    <div slot="fecha_vencimiento" slot-scope="{row}">
                        {{row.fecha_vencimiento| myDate}}
                    </div>
                    <div class="btn-group" slot="acciones" slot-scope="{row}">
                        <el-button type="warning" @click="printComprobante(row, 'ticket')" :loading="disablebtn"><i class="fas fa-print"></i>Ticket</el-button>
                        <el-button type="warning" @click="printComprobante(row, 'a4')" :loading="disablebtn"><i class="fas fa-print"></i>A4</el-button>
                        <!-- <button :loading="disablebtn" @click="printComprobante(row, 'ticket')" type="button" class="btn btn-warning"><i class="fas fa-print"></i>TICKET</button>
                        <button :loading="disablebtn" @click="printComprobante(row, 'a4')" type="button" class="btn btn-warning"><i class="fas fa-print"></i>A4</button> -->
                        <!-- <router-link :to="'/tramite/documentos/print/hojarecepcion/'+row.id_documento">
                            <button type="button" class="btn btn-warning" ><i class="fas fa-print"></i></button>
                        </router-link> -->
                    </div>
                </v-client-table>
            </div>
        </div>
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="addNew" tabindex="-1" role="dialog"
            aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content" :class="!editmode ? 'card card-primary' : 'card card-success'">
                    <div class="card-header">
                        <h5 class="card-title" v-show="!editmode" id="addNewLabel">Nuevo Documento</h5>
                        <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar Documento</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-bs-dismiss="modal"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Expediente</label>
                                <input disabled type="text" class="form-control form-control-sm">
                            </div>
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
                comprobantes: [],
                anios:[{anio:2018},{anio:2019},{anio:2021},{anio:2022}],
                anio:moment().format('yyyy'),
                columna: ['serie','forma_pago','fecha_emision','fecha_vencimiento','cliente','numerodoc','estado_comprobante','acciones'],
                options: {
                    headings: {
                    },
                    perPage: 15,
                    perPageValues: [15, 50, 100],
                    skin: 'table table-striped table-hover',
                    filterable: ['serie','correlativo','fecha_emision','cliente','numerodoc'],
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
                }),
            }
        },
        computed:{
            
        },
        methods: {
            loadComprobantes() {
                let dato = {anio:this.anio};
                axios.post("/api/comprobantebyparams",dato)
                    .then(({data}) => {
                        this.comprobantes = data;
                    })
                    .catch(error => {
                        console.log(error);
                        Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                    });
            },
            deleteDocumento(id) {
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
                        axios.delete('/api/tramite/documentos/delete/recepcionados/' + id).then(() => {
                            Swal.fire(
                                'Eliminado!',
                                'El documento fue eliminado.',
                                'success'
                            )
                            this.loadComprobantes();
                        }).catch(() => {
                            Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                        });
                    }
                })
            },
            newModal() {
                $('#addNew').modal('show');
            },
            nuevoComprobante(){
                this.$router.push('/regcomprobante');
            },
            printComprobante(row, tipo){
                this.disablebtn = true;
                row.tipo_impresion = tipo;
                axios.post('/api/comprobante/crearPDF',row).then( (data) => {
                    window.open(`${window.location.origin}/docs/${tipo}/${row.tipo_comprobante_id}-${row.serie}-${row.correlativo}.pdf`)
                    this.disablebtn = false;
                })
            },
            loadAnio(){
                axios.get('/api/anio').then( ({data}) => {
                    this.anios = data;
                })
            },
            emitirtodo(){
                axios.get('/api/comprobanteenvio').then();
            },
        },
        created() {
            // this.loadAnio();
            this.loadComprobantes();
            document.title = 'Comprobantes - SBH';
        }

    }

</script>
