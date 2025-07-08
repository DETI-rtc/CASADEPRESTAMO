<template>
    <div class="">
       <div class="card card-teal" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 24px;"> <i class="fa fa-calendar nav-icon"></i> Seguro de Desgravament</h3>
        </div>
        <div class="card-body" >
            <div class="form-group">
                <label>Seguro desgravamen</label>
                <input type="text" class="form-control">
            </div>
            <div class="form-group">
                <label>Email address</label>
                <input type="text" class="form-control">
            </div>
            <div class="form-group">
                <label>Email address</label>
                <input type="text" class="form-control">
            </div>
        </div>
        </div>
        <div v-else>
                <not-found></not-found>
        </div>           
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        data() {
            return {
                editmode: false,
                error:{},
                hora_fin:'',
                hora_ini:'',
                dni:'',
                cliente:'',
                actividad:'',
                hoja : [],
                columna:['id','fecha','actions'],  
                options: {
                          headings: {
                                id: 'ID',fecha:'Fecha de Actividad',estado:'Estado',
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm',
                          
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
                form: new Form({
                    id:'',
                    fecha : moment().format('YYYY-MM-DD'),
                    array_detalle:[{
                        idhoja:'',
                        hora_ini:'',
                        hora_fin:'',
                        dni:'',
                        cliente:'',
                        actividad:'',
                    }],
                    
                })
            }
        },
        methods: {
        },
        created() {
        }
    }
</script>
