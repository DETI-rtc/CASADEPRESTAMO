<template>
    <div class="">
       <div class="card card-teal" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 24px;"> <i class="fa fa-calendar nav-icon"></i> Cuotas Vencidas</h3>
            
                <div class="card-tools">
                    <!-- <button type="button" class="btn btn-primary" @click="nuevoperio">Registrar Ruta <i class="fas fa-user-plus fa-fw"></i></button> -->
                    <!-- <select v-model="anio" >SELCCIONE AÑO
                        <option v-for="peri in periodos" :key="peri.año" >
                            {{ peri.año }}
                        </option>
                    </select> -->

                    <el-select v-model="anio" placeholder="Seleccione el año">
                        <el-option
                        v-for="item in periodos"
                        :key="item.año"
                        :label="item.año"
                        :value="item.año"
                        :disabled="item.disabled">
                        </el-option>
                    </el-select>
                </div>
            
        </div>
        <div class="card-body" >
            <div style="padding-top:2rem;padding-bottom:2rem; ">
                <label style="padding-right:2rem;">Seleccione el Mes </label>
                <el-select v-model="mes" placeholder="Seleccione el mes" @input="buscar">
                    <el-option
                    v-for="item in meses"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                    :disabled="item.disabled">
                    </el-option>
                </el-select>
            </div>
            <v-client-table :data="creditos" :columns="columna" :options="options" >
            </v-client-table>
            
           
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
                creditos:[],
                anio:moment().format('YYYY'),
                periodos:[],
                columna:[ 'cuotanro' , 'fecha_ven', 'idcredito', 'cliente'],  
                options: {
                    headings: {
                       fecha_ven:'Fecha de Vencimiento',
                       cuotanro:'N° Couta',
                       idcredito:'N° Credito'
                    },
                    perPage:25,
                    perPageValues:[25,50,100],
                    skin:'table table-sm',
                    filterable: ['cuotanro' , 'fecha_ven', 'idcredito', 'cliente'],
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
                mes:'',
                meses:[
                    {value:1,label:'ENERO'},
                    {value:2,label:'FEBRERO'},
                    {value:3,label:'MARZO'},
                    {value:4,label:'ABRIL'},
                    {value:5,label:'MAYO'},
                    {value:6,label:'JUNIO'},
                    {value:7,label:'JULIO'},
                    {value:8,label:'AGOSTO'},
                    {value:9,label:'SETIEMBRE'},
                    {value:10,label:'OCTUBRE'},
                    {value:11,label:'NOVIEMBRE'},
                    {value:12,label:'DICIEMBRE'},
                ],
            }
        },
        methods: {
            loadUsers(){
                // axios.get("api/hojaruta").then(({ data }) => (this.hoja = data)).catch(error => { this.error = error.response });  
                axios.get("/api/periodos").then(({ data }) => (this.periodos = data)).catch(error => { this.error = error.response });
            },
            
            buscar(){
                axios.get('/api/buscarcuotasvencidas',{params:{'mes':this.mes, 'anio':this.anio}})
                .then(({data}) => {
                    console.log(data);
                    this.creditos = data;
                }).catch((err) => {
                    console.log(err);
                });
            },
        },
        created() {
           this.loadUsers();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>
