<template>
    <div>
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Contratos</h3>
                <div class="card-tools">
                    <button class="btn btn-primary" @click="newModal">Nuevo Contrato<i
                            class="fas fa-user-plus fa-fw"></i></button>
                </div>
            </div>
            <div class="card-body p-3">
                <v-client-table :data="contratos" :columns="columna" :options="options">
                    <div slot="cliente" slot-scope="{row}">
                        <span>{{row.nombre +' '+ row.apellido_pat +' '+ row.apellido_mat}}</span>
                    </div>
                    <div slot="fecha" slot-scope="{row}">
                        {{row.fecha | myDate}}
                    </div>
                    <div slot="estado" slot-scope="{row}">
                        <span class="badge badge-success" v-if="row.estado == 1">
                            Registrado
                        </span>
                        <span class="badge badge-danger" v-else>
                            Anulado
                        </span>
                    </div>
                    <div slot="acciones" class="btn-group" role="group" slot-scope="{row}">
                        <el-button-group class="group">
                            <el-button v-show="row.estado == 1" size="mini" type="success" @click="editModal(row)" ><i class="fas fa-edit"></i></el-button>
                            <el-button v-show="row.estado == 1" size="mini" type="danger" @click="deleteContrato(row.id)" ><i class="fas fa-trash"></i></el-button>
                            <el-button size="mini" type="warning" @click="print(row.id)"><i class="fas fa-print"></i></el-button>
                        </el-button-group>
                    </div>
                </v-client-table>
            </div>
        </div>
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="addNew" tabindex="-1" role="dialog"
            aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content" :class="!editmode ? 'card card-primary' : 'card card-success'">
                    <div class="card-header">
                        <h5 class="card-title" v-show="!editmode" id="addNewLabel">Nuevo Contrato</h5>
                        <h5 class="card-title" v-show="editmode" id="addNewLabel">Actualizar Contrato</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-bs-dismiss="modal"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-contrato-tab" data-bs-toggle="pill" href="#pills-contrato" role="tab" aria-controls="pills-contrato" aria-selected="true">Datos del Contrato</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-titular-tab" data-bs-toggle="pill" href="#pills-titular" role="tab" aria-controls="pills-titular" aria-selected="true">Datos del Titular</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-conyuge-tab" data-bs-toggle="pill" href="#pills-conyuge" role="tab" aria-controls="pills-conyuge" aria-selected="false">Datos del Cónyuge</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-autorizada-tab" data-bs-toggle="pill" href="#pills-autorizada" role="tab" aria-controls="pills-autorizada" aria-selected="false">Datos de la Persona Autorizada</a>
                        </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-contrato" role="tabpanel" aria-labelledby="pills-contrato-tab">
                                <div class="row">
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Número</label>
                                        <input disabled v-model="form.nro" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <label class="pb-0 mb-0">Tipo</label>
                                        <el-select v-model="form.tipo" filterable popper-class="el-select-provinces" dusk="province_id">
                                            <el-option v-for="option in tipos_contrato" :key="option.id" :value="option.id" :label="option.descripcion"></el-option>
                                        </el-select>
                                        <!-- <input v-model="form.tipo" type="text" class="form-control form-control-sm"> -->
                                    </div>
                                    <div class="form-group col-md-3 mb-2">
                                        <label class="pb-0 mb-0">Fecha</label>
                                        <input v-model="form.fecha" type="date" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-3 mb-2">
                                        <label class="pb-0 mb-0">Monto</label>
                                        <el-input-number v-model="form.monto" :precision="2" :step="0.1" :min="0"></el-input-number>
                                        <!-- <input v-model="form.monto" type="text" class="form-control form-control-sm"> -->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-titular" role="tabpanel" aria-labelledby="pills-titular-tab">
                                <div class="row">
                                    <div class="form-group col-md-4 mb-2">
                                        <label class="pb-0 mb-0">Nombre/s</label>
                                        <div class="input-group mb-3">
                                            <input v-model="form.nombre" type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button @click="abrirSwalCliente" class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <label class="pb-0 mb-0">Apellido Paterno</label>
                                        <input v-model="form.apellido_pat" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <label class="pb-0 mb-0">Apellido Materno</label>
                                        <input v-model="form.apellido_mat" type="text" class="form-control form-control-sm">
                                    </div>

                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">DNI/Documento</label>
                                        <input v-model="form.dni" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-3 mb-2">
                                        <label class="pb-0 mb-0">RUC</label>
                                        <input v-model="form.ruc" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-3 mb-2">
                                        <label class="pb-0 mb-0">Fecha de Nacimiento</label>
                                        <input v-model="form.fecha_nac" type="date" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Estado Civil</label>
                                        <input v-model="form.estado_civil" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Nacionalidad</label>
                                        <input v-model="form.nacionalidad" type="text" class="form-control form-control-sm">
                                    </div>

                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Teléfono</label>
                                        <input v-model="form.telefono" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <label class="pb-0 mb-0">Dirección (Av./Jr./Calle/Mz.)</label>
                                        <input v-model="form.direccion" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Dpto/Int</label>
                                        <input v-model="form.dpto" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Teléfono Celular</label>
                                        <input v-model="form.celular" type="text" class="form-control form-control-sm">
                                    </div>

                                    <div class="form-group col-md-6 mb-2">
                                        <label class="pb-0 mb-0">Urbanización/Etapa/Sector</label>
                                        <input v-model="form.urb_etapa_sector" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Departamento</label>
                                        <input v-model="form.departamento" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Credo</label>
                                        <input v-model="form.credo" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Sexo</label>
                                        <el-select v-model="form.sexo" filterable popper-class="el-select-provinces" dusk="province_id">
                                            <el-option v-for="option in sexos" :key="option.id" :value="option.id" :label="option.descripcion"></el-option>
                                        </el-select>
                                        <!-- <input v-model="form.sexo" type="text" class="form-control form-control-sm"> -->
                                    </div>

                                    <div class="form-group col-md-6 mb-2">
                                        <label class="pb-0 mb-0">Ocupación/Profesión/Cargo Titular/Nombre Referencia</label>
                                        <input v-model="form.ocupacion" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <label class="pb-0 mb-0">Centro de Trabajo Titular/Relación con Referencia</label>
                                        <input v-model="form.centro_trabajo" type="text" class="form-control form-control-sm">
                                    </div>

                                    <div class="form-group col-md-6 mb-2">
                                        <label class="pb-0 mb-0">Dirección de Centro de Trabajo Titular o Referencia</label>
                                        <input v-model="form.direccion_trabajo" type="text" class="form-control form-control-sm">
                                    </div>
                                    <!-- <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Regiones</label>
                                        <el-select v-model="form.region" filterable popper-class="el-select-provinces" dusk="province_id">
                                            <el-option v-for="option in departamentos" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                        </el-select>
                                    </div> -->
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Provincia</label>
                                        <el-select v-model="form.provincia" filterable popper-class="el-select-provinces" dusk="province_id">
                                            <el-option v-for="option in provincias" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                        </el-select>
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Distrito</label>
                                        <el-select v-model="form.distrito" filterable popper-class="el-select-provinces" dusk="province_id">
                                            <el-option v-for="option in distritos" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                        </el-select>
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Teléfono Trabajo</label>
                                        <input v-model="form.telefono_trabajo" type="text" class="form-control form-control-sm">
                                    </div>

                                    <div class="form-group col-md-6 mb-2">
                                        <label class="pb-0 mb-0">Dirección de Recaudación</label>
                                        <el-radio-group v-model="form.tipo_direccion_recaudacion">
                                        <el-radio label="Particular">Particular</el-radio>
                                        <el-radio label="Trabajo">Trabajo</el-radio>
                                        <el-radio label="Otro">Otro</el-radio>
                                        </el-radio-group>
                                        <input v-model="form.direccion_recaudacion" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <label class="pb-0 mb-0">Dirección de Correspondencia</label>
                                        <el-radio-group v-model="form.tipo_direccion_correspondencia">
                                        <el-radio label="Particular">Particular</el-radio>
                                        <el-radio label="Trabajo">Trabajo</el-radio>
                                        <el-radio label="Otro">Otro</el-radio>
                                        </el-radio-group>
                                        <input v-model="form.direccion_correspondencia" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <label class="pb-0 mb-0">Email Particular</label>
                                        <input v-model="form.email_particular" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <label class="pb-0 mb-0">Email Trabajo</label>
                                        <input v-model="form.email_trabajo" type="text" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-conyuge" role="tabpanel" aria-labelledby="pills-conyuge-tab">
                                <div class="row">
                                    <div class="form-group col-md-4 mb-2">
                                        <label class="pb-0 mb-0">Nombre/s del/a Cónyuge</label>
                                        <input v-model="form.nombre_conyuge" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <label class="pb-0 mb-0">Apellido Paterno del/a Cónyuge</label>
                                        <input v-model="form.apellido_pat_conyuge" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <label class="pb-0 mb-0">Apellido Materno del/a Cónyuge</label>
                                        <input v-model="form.apellido_mat_conyuge" type="text" class="form-control form-control-sm">
                                    </div>

                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">DNI/Documento</label>
                                        <input v-model="form.numerodoc_conyuge" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">F. de Nacimiento</label>
                                        <input v-model="form.fecha_nac_conyuge" type="date" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Nacionalidad</label>
                                        <input v-model="form.nacionalidad_conyuge" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Teléfono</label>
                                        <input v-model="form.telefono_conyuge" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Celular</label>
                                        <input v-model="form.celular_conyuge" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Sexo</label>
                                        <el-select v-model="form.sexo_conyuge" filterable popper-class="el-select-provinces" dusk="province_id">
                                            <el-option v-for="option in sexos" :key="option.id" :value="option.id" :label="option.descripcion"></el-option>
                                        </el-select>
                                        <!-- <input v-model="form.sexo_conyuge" type="text" class="form-control form-control-sm"> -->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-autorizada" role="tabpanel" aria-labelledby="pills-autorizada-tab">
                                <div class="row">
                                    <div class="form-group col-md-4 mb-2">
                                        <label class="pb-0 mb-0">Nombre/s</label>
                                        <input v-model="form.nombre_autorizado" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <label class="pb-0 mb-0">Apellido Paterno</label>
                                        <input v-model="form.apellido_pat_autorizado" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <label class="pb-0 mb-0">Apellido Materno</label>
                                        <input v-model="form.apellido_mat_autorizado" type="text" class="form-control form-control-sm">
                                    </div>

                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">DNI/Documento</label>
                                        <input v-model="form.numerodoc_autorizado" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">F. de Nacimiento</label>
                                        <input v-model="form.fecha_nac_autorizado" type="date" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Nacionalidad</label>
                                        <input v-model="form.nacionalidad_autorizado" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Teléfono</label>
                                        <input v-model="form.telefono_autorizado" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Celular</label>
                                        <input v-model="form.celular_autorizado" type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <label class="pb-0 mb-0">Sexo</label>
                                        <el-select v-model="form.sexo_autorizado" filterable popper-class="el-select-provinces" dusk="province_id">
                                            <el-option v-for="option in sexos" :key="option.id" :value="option.id" :label="option.descripcion"></el-option>
                                        </el-select>
                                        <!-- <input v-model="form.sexo_autorizado" type="text" class="form-control form-control-sm"> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <el-button type="danger" @click="cerrarModal">Cerrar</el-button>
                        <el-button v-show="editmode" :disabled="disablebtn" type="success" @click="updateContrato" >Actualizar</el-button>
                        <el-button v-show="!editmode" :disabled="disablebtn" type="primary" @click="createContrato" >Crear</el-button>

                        <!-- <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        <button :disabled="disablebtn" @click="updateContrato" v-show="editmode" type="button" class="btn btn-success">Actualizar</button>
                        <button :disabled="disablebtn" @click="createContrato" v-show="!editmode" type="button" class="btn btn-primary">Crear</button> -->
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
                contratos: [],
                clientes:[],
                departamentos:[],
                provincias:[],
                distritos:[],
                tipos_contrato:[],
                sexos:[{id:'F',descripcion:'Femenino'}, {id:'M',descripcion:'Masculino'}],
                cliente:{},
                columna: ['nro','cliente','dni','monto','fecha','tipo','estado','acciones'],
                options: {
                    headings: {
                    },
                    perPage: 15,
                    perPageValues: [15, 50, 100],
                    skin: 'table table-striped table-hover',
                    filterable: ['id_nro_documento','TIPO_DOCUMENTO','nro_documento','remitente','fec_recepcion'],
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
                    nro: '',
                    fecha: momento().format('yyyy-MM-DD'),
                    monto: '',
                    tipo: '',
                    idcliente: '',
                    nombre: '',
                    apellido_pat: '',
                    apellido_mat: '',
                    dni: '',
                    ruc: '',
                    fecha_nac: '',
                    estado_civil: '',
                    nacionalidad: '',
                    telefono: '',
                    direccion: '',
                    dpto: '',
                    celular: '',
                    urb_etapa_sector: '',
                    departamento: '',
                    credo: '',
                    sexo: '',
                    ocupacion: '',
                    centro_trabajo: '',
                    direccion_trabajo: '',
                    pronvicia: '',
                    distrito: '',
                    telefono_trabajo: '',
                    email_particular: '',
                    email_trabajo: '',
                    nombre_conyuge: '',
                    apellido_pat_conyuge: '',
                    apellido_mat_conyuge: '',
                    numerodoc_conyuge: '',
                    fecha_nac_conyuge: '',
                    nacionalidad_conyuge: '',
                    telefono_conyuge: '',
                    celular_conyuge: '',
                    sexo_conyuge: '',
                    nombre_autorizado: '',
                    apellido_pat_autorizado: '',
                    apellido_mat_autorizado: '',
                    numerodoc_autorizado: '',
                    fecha_nac_autorizado: '',
                    nacionalidad_autorizado: '',
                    telefono_autorizado: '',
                    celular_autorizado: '',
                    sexo_autorizado: '',
                    tipo_sepultura: '',
                    capacidad_unidad: '',
                    capacidad_contratada: '',
                    escogida: '',
                    plataforma: '',
                    codigo_sepultura: '',
                    tipo_necesidad: '',
                    periodo_carencia: '',
                    fecha_termino_periodo_carencia: '',
                    costo_carencia: '',
                    minimo_inhumar: '',
                    precio_lista: '',
                    descuento: '',
                    precio_total: '',
                    fonto_mantencion: '',
                    vencimiento_fondo_mantencion: '',
                    contado: '',
                    pago_inicial: '',
                    pagare: '',
                    letra: '',
                    seguro: '',
                    monto_cuota: '',
                    monto_letra: '',
                    id_user: '',
                    estado: '',
                    direccion_recaudacion: '',
                    direccion_correspondencia: '',
                    tipo_direccion_recaudacion: '',
                    tipo_direccion_correspondencia: '',
                }),
            }
        },
        computed:{
            
        },
        methods: {
            loadContratos() {
                let dato = {};
                axios.get("/api/contratos", dato)
                    .then(({data}) => {
                        this.contratos = data;
                    })
                    .catch(error => {
                        Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                    });
            },
            createContrato() {
                this.disablebtn = true;
                this.form.post('/api/contratos')
                    .then(() => {
                        this.loadContratos();
                        $('#addNew').modal('hide')
                        Swal.fire(
                            'Correcto',
                            'Contrato creado satisfactoriamente',
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
            updateContrato() {
                this.disablebtn = true;
                this.form.put('/api/contratos/' + this.form.id)
                    .then(() => {
                        $('#addNew').modal('hide');
                        Swal.fire('Actualizado','El contrato se actualizó','success')
                        this.loadContratos();
                        this.disablebtn = false;
                    })
                    .catch(() => {
                        this.disablebtn = false;
                        Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                    });
            },
            deleteContrato(id) {
                Swal.fire({
                    title: '¿Estás Seguro?',
                    text: "Si se elimina no se podra revertir",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminar!'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/api/contratos/' + id).then(() => {
                            Swal.fire(
                                'Eliminado!',
                                'El contrato fue eliminado.',
                                'success'
                            )
                            this.loadContratos();
                        }).catch(() => {
                            Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                        });
                    }
                })
            },
            editModal(row) {
                this.editmode = true;
                this.form.reset();
                this.form.fill(row);
                $('#addNew').modal('show');
            },
            newModal() {
                this.loadNroContrato();
                this.editmode = false;
                $('#addNew').modal('show');
            },
            cerrarModal(){
                this.form.reset();
                $('#addNew').modal('hide');
            },
            loadLocacion(){
                axios.get("/api/contrato/locaciones").then( ({data}) => {
                    this.departamentos = data.departments
                    this.distritos = data.districts
                    this.provincias = data.provinces
                })
            },
            loadTiposdeContrato(){
                axios.get('/api/contrato/tiposcontrato').then( ({data}) => {
                    this.tipos_contrato = data;
                })
            },
            loadNroContrato(){
                axios.get('/api/contrato/nrocontrato').then( ({data}) => {
                    console.log(data);
                    this.form.nro = data;
                })
            },
            loadCliente(){
                axios.get('s').then( ({data}) => {
                    this.clientes = data;
                    let clientebd = {};
                    this.clientes.map((i,k) => {
                        for (const j in i) {
                            if (Object.hasOwnProperty.call(i, j)) {
                                const element = i[j];
                                if (j == 'nombre') {
                                    clientebd[i.id] = element;
                                }
                            }
                        }
                    })
                    this.clientes = clientebd;
                    // console.log(data);
                    // let clientebd = data;
                    // clientebd.map(i => {
                    //     this.clientes.p
                    // })
                    // this.clientes = data[0]
                })
            },
            async abrirSwalCliente(){
                const { value: fruit } = await Swal.fire({
                    title: 'Buscar Cliente',
                    input: 'select',
                    inputOptions: this.clientes,
                    inputPlaceholder: 'Escriba o seleccione...',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            resolve()
                        })
                    }
                })

                this.form.idcliente = fruit;
                this.buscarCliente();
            },
            buscarCliente(){
                axios.get('/api/clientes/'+ this.form.idcliente).then( ({data}) => {
                    this.cliente = data;
                    this.form.dni = this.cliente.numerodoc;
                    this.form.direccion = this.cliente.direccion
                    this.form.celular = this.cliente.telefono
                    this.form.email_particular = this.cliente.email
                    this.form.provincia = this.cliente.province_id
                    this.form.region = this.cliente.department_id
                    this.form.distrito = this.cliente.district_id
                })
            },
            print(id){
                window.open('/print/contrato/'+id, '_blank');
            },
        },
        created() {
            this.loadLocacion();
            this.loadContratos();
            this.loadCliente();
            this.loadTiposdeContrato();
            // this.loadNroContrato();
            document.title = 'Contratos - SBH';
        }

    }

</script>
