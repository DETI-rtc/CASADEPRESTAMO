<template>
    <div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="card-title fs-3 my-0">Listado de {{ title }}</h3>
                <div class="card-tools">
                     <!-- <button type="button" class="btn btn-warning btn-sm  mt-2 mr-2" @click.prevent="clickExport()"><i class="fa fa-download"></i> Exportar</button>
                <button type="button" class="btn btn-secondary btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-upload"></i> Importar</button> -->
                <button type="button" class="btn btn-primary btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                    
                 </div>
            </div>
            <div class="card-body">
                <v-client-table class="tableuser" :data="categorias" :columns="columna" :options="options">
                    
                   <div slot="estado" slot-scope="{row}" >
                          <div v-if = "row.estado === 0">
                              <span class="badge bg-danger text-xs">Inactivo</span>
                          </div>
                          <div v-else>
                              <span class="badge bg-warning text-xs">Activo</span>
                          </div>
                   </div>
                    <div slot="acciones" slot-scope="{ row }">
                        
                                 <button type="button" class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="clickCreate(row.id)" ><i class="far fa-edit"></i> Editar</button>
                                 <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)"><i class="fas fa-trash-alt"></i> Eliminar</button>

                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDisable(row.id)" v-if="row.enabled">Inhabilitar</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickEnable(row.id)" v-else>Habilitar</button>

                    </div>
                </v-client-table>
            </div>

            <categorias-form :showDialog.sync="showDialog"
                          :type="type"
                          :recordId="recordId"
                          ></categorias-form>

           

        </div>
    </div>
</template>

<script>

    import CategoriasForm from './form.vue'
    // import PersonsImport from './importar.vue'
    // import PersonsExport from './partes/exportar.vue'
    

    export default {
        
        
        components: {CategoriasForm },
        data() {
            return {
                title: null,
                type:'categoria',
                showDialog: false,
                showImportDialog: false,
                showExportDialog: false,
                resource: 'categoria',
                categorias:[],
                recordId: null,
                columna: [ 'id','nombre', 'estado','acciones'],
                options: {
                    headings: {
                        id: 'ID',
                        nombre: 'Categorias',
                        estado: 'Estado',
                    },
                    perPage: 15,
                    perPageValues: [15, 50, 100],
                    skin: 'table table-striped table-hover ',
                    filterable: ['descripcion'],
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
            }
        },
        created() {
            this.title = 'Categorias'
            axios.get(`/api/categoria`).then((response) => {
            this.categorias = response.data;
        });
        this.$eventHub.$on('reloadCateindex', ()=>{
                this.reloadTables()
            })
        },
        methods: {
            reloadTables(){
            console.log('entra aqui index');
             axios.get(`/api/categoria`).then((response) => {
                this.categorias = response.data;
            });
            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickImport() {
                this.showImportDialog = true
            },
            clickExport() {
                this.showExportDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
            clickDisable(id){
                this.disable(`/${this.resource}/enabled/${0}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
            clickEnable(id){
                this.enable(`/${this.resource}/enabled/${1}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
        }
    }
</script>
