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
                <v-client-table class="tableuser" :data="tipos" :columns="columna" :options="options">
                    
                   
                    <div slot="acciones" slot-scope="{ row }">
                        
                                 <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)" >Editar</button>
                                 <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>

                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDisable(row.id)" v-if="row.enabled">Inhabilitar</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickEnable(row.id)" v-else>Habilitar</button>

                    </div>
                </v-client-table>
            </div>

            <tipos-form :showDialog.sync="showDialog"
                          :type="type"
                          :recordId="recordId"
                          ></tipos-form>

           

        </div>
    </div>
</template>

<script>

    import TiposForm from './form.vue'
    // import PersonsImport from './importar.vue'
    // import PersonsExport from './partes/exportar.vue'
    

    export default {
        
        
        components: {TiposForm },
        data() {
            return {
                title: null,
                type:'tipodocu',
                showDialog: false,
                showImportDialog: false,
                showExportDialog: false,
                resource: 'tipodocu',
                tipos:[],
                recordId: null,
                columna: [ 'id','nombre', 'estado','acciones'],
                options: {
                    headings: {
                        id: 'ID',
                        nombre: 'Tipo Documento',
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
            this.title = 'Tipos Documentos'
            axios.get(`/api/tipodocu`).then((response) => {
            this.tipos = response.data;
        });
        this.$eventHub.$on('reloadTipoindex', ()=>{
                this.reloadTables()
            })
        },
        methods: {
            reloadTables(){
            console.log('entra aqui index');
             axios.get(`/api/tipodocu`).then((response) => {
                this.tipos = response.data;
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
