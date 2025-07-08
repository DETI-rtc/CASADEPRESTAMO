<template>
    <div>
        
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="card-title fs-3 my-0">Listado de Productos</h3>
                <div class="card-tools">
                    <template >
                    <div class="btn-group flex-wrap">
                        <button
                            type="button"
                            class="btn btn-warning btn-sm mt-2 mr-2 dropdown-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="fa fa-download"></i> Exportar
                            <span class="caret"></span>
                        </button>
                        <div
                            class="dropdown-menu"
                            role="menu"
                            x-placement="bottom-start"
                            style="
                                position: absolute;
                                will-change: transform;
                                top: 0px;
                                left: 0px;
                                transform: translate3d(0px, 42px, 0px);
                            "
                        >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickExport()"
                                >Listado</a
                            >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickExportWp()"
                                >Woocommerce</a
                            >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickExportBarcode()"
                                >Etiquetas</a
                            >
                        </div>
                    </div>
                    <div class="btn-group flex-wrap">
                        <button
                            type="button"
                            class="btn btn-secondary btn-sm mt-2 mr-2 dropdown-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="fa fa-upload"></i> Importar
                            <span class="caret"></span>
                        </button>
                        <div
                            class="dropdown-menu"
                            role="menu"
                            x-placement="bottom-start"
                            style="
                                position: absolute;
                                will-change: transform;
                                top: 0px;
                                left: 0px;
                                transform: translate3d(0px, 42px, 0px);
                            "
                        >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickImport()"
                                >Productos</a
                            >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickImportListPrice()"
                                >L. Precios</a
                            >
                        </div>
                    </div>
                    <button
                        type="button"
                        class="btn btn-primary btn-sm mt-2 mr-2"
                        @click.prevent="clickCreate()"
                    >
                        <i class="fa fa-plus-circle"></i> Nuevo
                    </button>
                </template>
                </div>
            </div>

            <div class="card-body">
                <v-client-table class="tableuser" :data="productos" :columns="columna" :options="options">
                    
                    <div slot="created_at" slot-scope="{row}">
                        {{row.created_at | myDate}}
                    </div>
                    <div slot="acciones" slot-scope="{ row }">
                        
                                <button
                                    type="button"
                                    class="btn waves-effect waves-light btn-xs btn btn-success"
                                    @click.prevent="clickCreate(row.id)"
                                ><i class="fa fa-edit"></i>
                                    Editar
                                </button>
                                <button
                                    type="button"
                                    class="btn waves-effect waves-light btn-xs btn btn-danger"
                                    @click.prevent="clickDelete(row.id)"
                                ><i class="fa fa-trash"></i>
                                    Eliminar
                                </button>
                                <button
                                    type="button"
                                    class="btn waves-effect waves-light btn-xs btn btn-danger"
                                    @click.prevent="clickDisable(row.id)"
                                    v-if="row.active"
                                >
                                    Inhabilitar
                                </button>
                                <button
                                    type="button"
                                    class="btn waves-effect waves-light btn-xs btn btn-primary"
                                    @click.prevent="clickEnable(row.id)"
                                    v-else
                                >
                                    Habilitar
                                </button>

                                <button
                                    type="button"
                                    class="btn waves-effect waves-light btn-xs btn-warning"
                                    @click.prevent="clickBarcode(row)"
                                >
                                    Cod. Barras
                                </button>

                    </div>
                </v-client-table>
            </div>

            <items-form
                :showDialog.sync="showDialog"
                :recordId="recordId"
            ></items-form>

            <items-import :showDialog.sync="showImportDialog"></items-import>
            <items-export :showDialog.sync="showExportDialog"></items-export>
            <items-export-wp
                :showDialog.sync="showExportWpDialog"
            ></items-export-wp>
            <items-export-barcode
                :showDialog.sync="showExportBarcodeDialog"
            ></items-export-barcode>

            <warehouses-detail
                :showDialog.sync="showWarehousesDetail"
                :warehouses="warehousesDetail"
                :item_unit_types="item_unit_types"
            >
            </warehouses-detail>

            <items-import-list-price
                :showDialog.sync="showImportListPriceDialog"
            ></items-import-list-price>
        </div>
    </div>
</template>
<script>
import ItemsForm from "./form.vue";
import WarehousesDetail from "./partes/almacenes.vue";
import ItemsImport from "./importar.vue";
import ItemsImportListPrice from "./partes/import_lista_precio.vue";
import ItemsExport from "./partes/exportar.vue";
import ItemsExportWp from "./partes/exportar_wp.vue";
import ItemsExportBarcode from "./partes/exportar_codbarra.vue";
// import DataTable from "../../../components/DataTable.vue";
//import { deletable } from "../../../mixins/deletable";

export default {
    props: ["typeUser"],
    
    components: {
        ItemsForm,
        ItemsImport,
        ItemsExport,
        ItemsExportWp,
        ItemsExportBarcode,
       
        WarehousesDetail,
        ItemsImportListPrice,
    },
    data() {
        return {
            showDialog: false,
            showImportDialog: false,
            showExportDialog: false,
            showExportWpDialog: false,
            showExportBarcodeDialog: false,
            showImportListPriceDialog: false,
            showWarehousesDetail: false,
            resource: "items",
            recordId: null,
            productos:[],
            warehousesDetail: [],
            config: {},
            columna: [ 'nombre','valor_unitario', 'stock_ini','afectacion', 'unidad_id','categoria','acciones'],
                options: {
                    headings: {
                        nombre: 'Producto',
                        valor_unitario: 'Precio',
                        stock_ini: 'STOCK',
                        afectacion: 'Afectacion',
                        unidad_id:'Unidad Med',
                        categoria:'Categoria',
                        
                    },
                    perPage: 15,
                    perPageValues: [15, 50, 100],
                    skin: 'table table-striped table-hover ',
                    filterable: ['nombre','stock_ini'],
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
            columns: {
                description: {
                    title: 'Descripción',
                    visible: false
                },
                item_code: {
                    title: 'Cód. SUNAT',
                    visible: false
                },
                purchase_unit_price: {
                    title: 'P.Unitario (Compra)',
                    visible: false
                },
                purchase_has_igv_description: {
                    title: 'Tiene Igv (Compra)',
                    visible: false
                },

            },
            item_unit_types: [],
        };
    },
    created() {
        axios.get(`/api/productos`).then((response) => {
            this.productos = response.data;
        });
        this.$eventHub.$on('reloadData', ()=>{
                this.reloadTables()
            })
    },
    methods: {
        reloadTables(){
            console.log('entra');
            axios.get(`/api/productos`).then((response) => {
            this.productos = response.data;
        });
            },
        duplicate(id) {
            axios
                .post(`${this.resource}/duplicate`, { id })
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(
                            "Se guardaron los cambios correctamente."
                        );
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error("No se guardaron los cambios");
                    }
                })
                .catch((error) => {});
            this.$eventHub.$emit("reloadData");
        },
        clickWarehouseDetail(warehouses, item_unit_types) {
            this.warehousesDetail = warehouses;
            this.item_unit_types = item_unit_types
            this.showWarehousesDetail = true;
        },
        clickCreate(recordId = null) {
            this.recordId = recordId;
            this.showDialog = true;
        },
        clickImport() {
            this.showImportDialog = true;
        },
        clickExport() {
            this.showExportDialog = true;
        },
        clickExportWp() {
            this.showExportWpDialog = true;
        },
        clickExportBarcode() {
            this.showExportBarcodeDialog = true;
        },
        clickImportListPrice() {
            this.showImportListPriceDialog = true;
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickDisable(id) {
            this.disable(`/${this.resource}/disable/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickEnable(id) {
            this.enable(`/${this.resource}/enable/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickBarcode(row) {
            if (!row.barcode) {
                return this.$message.error(
                    "Para generar el código de barras debe registrar el código de barras."
                );
            }

            window.open(`/${this.resource}/barcode/${row.id}`);
        },
        clickPrintBarcode(row) {
            if (!row.barcode) {
                return this.$message.error(
                    "Para generar el código de barras debe registrar el código de barras."
                );
            }

            window.open(`/${this.resource}/export/barcode/print?id=${row.id}`);
        },
    },
};
</script>
