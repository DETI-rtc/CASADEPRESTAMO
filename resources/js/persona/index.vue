<template>
    <div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="card-title fs-3 my-0">Listado de {{ title }}</h3>
                <div class="card-tools">
                     <button type="button" class="btn btn-warning btn-sm  mt-2 mr-2" @click.prevent="clickExport()"><i class="fa fa-download"></i> Exportar</button>
                <button type="button" class="btn btn-secondary btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-upload"></i> Importar</button>
                <button type="button" class="btn btn-primary btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                    
                 </div>
            </div>
            <div class="card-body">
                <v-client-table class="tableuser" :data="clientes" :columns="columna" :options="options">
                    
                    <div slot="estado" slot-scope="{row}">
                        {{row.estado | myDate}}
                    </div>
                    <div slot="acciones" slot-scope="{ row }">
                        
                                 <button type="button" class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="clickCreate(row.id)" ><i class="far fa-edit"> </i> Editar</button>
                                 <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)"><i class="fas fa-trash-alt"></i> Eliminar</button>

                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDisable(row.id)" v-if="row.enabled">Inhabilitar</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickEnable(row.id)" v-else>Habilitar</button>

                    </div>
                </v-client-table>
            </div>

            <persons-form :showDialog.sync="showDialog"
                          :type="type"
                          :recordId="recordId"
                          ></persons-form>

           

        </div>
    </div>
</template>

<script>

    import PersonsForm from './form.vue'
    import PersonsImport from './importar.vue'
    import PersonsExport from './partes/exportar.vue'
   

    export default {
        
        
        components: {PersonsForm, PersonsImport, PersonsExport },
        data() {
            return {
                title: null,
                type:'clientes',
                showDialog: false,
                showImportDialog: false,
                showExportDialog: false,
                resource: 'cliente',
                clientes:[],
                recordId: null,
                columna: [ 'numerodoc','nombre', 'direccion', 'telefono','email','acciones'],
                options: {
                    headings: {
                        numerodoc: 'DNI',
                        nombre: 'Cliente',
                        direccion: 'Direccion',
                        Telefono: 'Telefono',
                        email:'Correo',
                        
                        
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
            }
        },
        created() {
            this.title = 'Clientes'
            axios.get(`/api/clientes`).then((response) => {
            this.clientes = response.data;
        });
        this.$eventHub.$on('reloadData', ()=>{
                this.reloadTables()
            })
        },
        methods: {
            reloadTables(){
            console.log('entra');
            axios.get(`/api/clientes`).then((response) => {
                this.clientes = response.data;
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
