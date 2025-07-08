
<template v-loading="loading" element-loading-text="Loading..." element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)">

    <div>


        <div class="card card-outline card-primary" v-if="error.status != 403">
            <div class="card-header">
                <h3 class="card-title mb-0 mt-1">Usuarios</h3>
                <div class="card-tools">
                    <button class="btn btn-primary" @click="newModal" >Nuevo Usuario<i
                            class="fas fa-user-plus fa-fw"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">
                <v-client-table class="tableuser" :data="users" :columns="columna" :options="options">
                    
                    <div slot="created_at" slot-scope="{row}">
                        {{row.created_at | myDate}}
                    </div>
                    <div slot="acciones" slot-scope="{ row }">
                        <button type="button" class="btn btn-success" @click="editModal(row)"><i
                                class="fa fa-edit"></i></button>
                        <button type="button" class="btn btn-danger" @click="deleteUser(row.id)"><i
                                class="fa fa-trash"></i></button>
                    </div>
                </v-client-table>

            </div>
            <!-- /.card-body -->
            <div class="card-footer">

            </div>
        </div>
        <div v-else>
            <not-found></not-found>
        </div>
        <!-- /.card -->





        <!-- Modal -->
        <div class="modal fade" id="addNew" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" :class="!editmode ? 'card card-primary' : 'card card-success'">
                    <div class="card-header">
                        <h5 class="card-title mb-0 mt-1" v-show="!editmode" id="addNewLabel">Nuevo Usuario</h5>
                        <h5 class="card-title mb-0 mt-1" v-show="editmode" id="addNewLabel">Actualizar Usuario</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-bs-dismiss="modal"><i
                                    class="fas fa-times"></i></button>
                        </div>

                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-6">
                                    <label>idUsuario</label>
                                    <input v-model="form.name" type="text" name="name" placeholder="Nombres"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                                <div class="form-group col-6">
                                    <label>Nombres</label>
                                    <input v-model="form.nombres" type="text" name="name" placeholder="Nombres"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('nombres') }">
                                    <has-error :form="form" field="nombres"></has-error>
                                </div>
                                
                                <div class="form-group col-6">
                                    <label>Apellidos</label>
                                    <input v-model="form.apellidos" type="text" name="apellidos" placeholder="Apellidos"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('apellidos') }">
                                    <has-error :form="form" field="apellidos"></has-error>
                                </div>
                                <div class="form-group col-6">
                                    <label>DNI</label>
                                    <input v-model="form.dni" type="text" name="dni" placeholder="00000000"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('dni') }">
                                    <has-error :form="form" field="dni"></has-error>
                                </div>
                                <div class="form-group col-6">
                                    <label>Email</label>
                                    <input v-model="form.email" type="email" name="email" placeholder="Email Address"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                    <has-error :form="form" field="email"></has-error>
                                </div>
                                
                                <div class="form-group col-6">
                                    <label>Rol</label>
                                    <select v-model="form.rol" class="form-control" name="rol">
                                        <option v-for="rol in roles" v-bind:value="rol.name" v-bind:key="rol.id"> {{ rol.name }}
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group col-6">
                                    <label>Password</label>
                                    <input v-model="form.password" type="password" name="password" id="password"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                    <has-error :form="form" field="password"></has-error>
                                </div>


                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Actualizar</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Crear</button>
                        </div>

                    </form>

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
                roles: [],                
                rol: [{
                    name: ''
                }],
                error: {},
                editmode: false,
                users: [],
                columna: ['avatar', 'name','nombres', 'apellidos', 'email','rol','created_at','acciones',],
                options: {
                    headings: {
                        name: 'Usuario',
                        nombres: 'Nombres',
                        apellidos: 'Apellidos',
                        created_at: 'Fecha de registro',
                        'rol': 'Rol',
                    },
                    perPage: 10,
                    perPageValues: [15, 50, 100],
                    skin: 'table table-striped table-hover ',
                    filterable: ['name'],
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
                    id: '',
                    name: '',
                    nombres:'',
                    apellidos:'',
                    avatar:'default.png',
                    lastname: '',
                    dni: '',
                    email: '',                    
                    rol: '',
                    password: ''
                })
            }
        },
        methods: {

            updateUser() {

                this.form.put('/api/user/' + this.form.id)
                    .then(() => {
                        // success
                        $('#addNew').modal('hide');
                        Swal.fire(
                            'Actualizado',
                            'El Usuario se actualizÓ',
                            'success'
                        )

                        this.loadUsers();
                    })
                    .catch(() => {
                        Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                    });

            },
            editModal(id) {
                this.editmode = true;

                this.form.clear();
                this.form.fill(id);
                $('#addNew').modal('show');

            },
            newModal() {
                this.editmode = false;
                this.form.reset();
                this.form.clear();
                $('#addNew').modal('show');
            },
            deleteUser(id) {
                Swal.fire({
                    title: 'Estas Seguro',
                    text: "Si se elimina no se podra revertir",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminar!'
                }).then((result) => {

                    // Send request to the server
                    if (result.value) {
                        this.form.delete('/api/user/' + id).then(() => {
                            Swal.fire(
                                'Eliminado!',
                                'El Usuario fue eliminado.',
                                'success'
                            )
                            this.loadUsers();
                        }).catch(() => {
                            Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                        });
                    }
                })
            },
            loadUsers() {
                this.fullscreenLoading = true;
                axios.get("/api/user").then(
                ({data}) => {
                    (this.users = data);
                })
                .catch(error => {
                    Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                });
                axios.get('/api/roles').then(response => (this.roles = response.data));
            },

            

            createUser() {
                this.fullscreenLoading = true;
                this.form.post('/api/user')
                    .then(() => {
                        this.loadUsers();
                        $('#addNew').modal('hide')

                        Swal.fire(
                            'Creado',
                            'Usuario creado satisfactoriamente',
                            'success',
                        )

                        this.fullscreenLoading = false;

                    })
                    .catch(() => {
                        Swal.fire("Fallido!", "Ocurrio un error.", "warning");
                    })
            }
        },
        created() {

            this.loadUsers();            
            document.title = 'Usuarios - SBH'
        }

    }

</script>
