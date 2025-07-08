<template>
    <div class="">
        <!-- <div class="justify-content-center"> -->
        <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title" style="font-size: 24px !important;"> <i class="fas fa-users mr-2" ></i> RELACION DE PAGOS </h3>
                            <div class="card-tools">
                                <button class="btn btn-success" @click="nuevocre"> Agregar lista <i class="fas fa-plus"></i></button>
                            </div>
                    </div>

                    <div class="card-body" ref="contento" >
                            <div class="form-group row">
                                <button class="btn btn-primary pull-right mr-2">
                                    Exportar a Excel
                                </button>
                                <button class="btn btn-danger">
                                    PDF
                                </button>
                            </div>

                                <v-client-table :data="creditos" :columns="columna" :options="options"  id="#ttrabajador">
                                    <div slot="estado" slot-scope="{row}" >
                                    <div v-if = "row.estado == 0">
                                        <span class="badge badge-pill badge-danger">Pendiente</span>
                                    </div>
                                    <div v-else>
                                        <span  class="badge badge-pill badge-success">Aprobado</span>
                                    </div>
                                    </div>
                                    <div slot="actions" slot-scope="{ row }">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-sm"  @click="editCre(row)" ><i class="fa fa-edit"></i></button>
                                        <button v-if="row.situacion==1" type="button" class="btn btn-danger btn-sm" @click="deleteCre(row.idtrabajador)"><i class="fa fa-trash"></i></button>
                                        <button v-if="row.situacion==0" type="button" class="btn btn-primary btn-sm" @click="activaTra(row.idtrabajador)"><i class="fa fa-trash"></i></button>
                                    </div>

                                    </div>
                                </v-client-table>
                    </div>
                <!-- </div> -->
        </div>
        <div class="modal bd-example-modal-xl" id="nuevacre" tabindex="-1" role="dialog" >
            <div class="modal-dialog modal-xl" role="document" style="max-width:90% !important;" >
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h5>Registrar nueva lista</h5>
                    </div>
                    <div class="modal-body">
                        <div class="text-right">
                            <button type="button" class="btn btn-success btn-sm">Subir Excel</button>
                            <div class="formulario-manual">
                                <form action="">
                                <div class="form-group">
                                        <div class="row">
                                            <div class="col-3">
                                                <label>DNI <span style="color: red; font-weight: bold;">*</span></label>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-group ">
                                                    <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                                                    </div>
                                                    <input type="text" class="form-control" maxlength="8" >
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-success"><i class="fas fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="form-group mb-3 row noinfe">
                                            <div class="col-3">
                                                <label>Nombre <span style="color: red; font-weight: bold;">*</span></label>
                                            </div>
                                            <div class="col-4">
                                                Aquí irá el nombre del cliente
                                            </div>
                                        </div>

                                        <div class="form-group row noinfe ">
                                            <label class="col-sm-3 col-form-label" >Entidad <span style="color: red; font-weight: bold;">*</span></label>
                                            <div class="col-sm-4">
                                                <select class="form-control" name="" id="">
                                                    <option>Entidad 01</option>
                                                </select>
                                            </div>
                                        </div>



                                        <div class="form-group row noinfe mt-3">
                                            <label class="col-sm-3 col-form-label" >Monto S/.<span style="color: red; font-weight: bold;">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="number" class="form-control">
                                            </div>
                                        </div>
                                    </form>

                                    <div class="text-right">
                                        <button type="button" class="btn btn-success">Agregar a lista</button>
                                    </div>

                                    <!-- LISTA -->
                                    <v-client-table :data="creditos" :columns="columna" :options="options"  id="#ttrabajador">
                                        <div slot="estado" slot-scope="{row}" >
                                        <div v-if = "row.estado == 0">
                                            <span class="badge badge-pill badge-danger">Pendiente</span>
                                        </div>
                                        <div v-else>
                                            <span  class="badge badge-pill badge-success">Aprobado</span>
                                        </div>
                                        </div>
                                        <div slot="actions" slot-scope="{ row }">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success btn-sm"  @click="editCre(row)" ><i class="fa fa-edit"></i></button>
                                            <button v-if="row.situacion==1" type="button" class="btn btn-danger btn-sm" @click="deleteCre(row.idtrabajador)"><i class="fa fa-trash"></i></button>
                                            <button v-if="row.situacion==0" type="button" class="btn btn-primary btn-sm" @click="activaTra(row.idtrabajador)"><i class="fa fa-trash"></i></button>
                                        </div>

                                        </div>
                                    </v-client-table>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm">Subir lista</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                columna:['dni','nombres','direccion','celular','empresa','estado','actions'],
                options: {
                          headings: {
                                dni: 'DNI',nombre:'nombres',direccion:'Direccion',celular:'Telefono',empresa:'Empresa',estado:'Estado',
                              },
                          perPage:25,
                          perPageValues:[25,50,100],
                          skin:'table table-sm table-hover',
                          filterAlgorithm: {
                               full_name(row, query) {
                          return (row.paterno + ' ' + row.materno).includes(query.toUpperCase());
                             }
                          },
                          filterable: false,
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
        methods:{
            nuevocre(){
                $('#nuevacre').modal('show');
            },
        }
    }
</script>
