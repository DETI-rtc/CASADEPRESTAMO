<template>
    <div class="">
        <div class="form-row" v-if="error.status != 403">
            <div class="col-3">
            <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                          <img class="profile-user-img img-fluid img-circle" :src="getProfilePhoto()" alt="User profile picture">
                        </div>
                        <h3 class="profile-username text-center">{{this.form.name}}</h3>
                            <p class="text-muted text-center"></p>                        
                        <a href="#" class="btn btn-primary btn-block"><b>{{this.form.roles[0].name}}</b></a>
                      </div>
                      <!-- /.card-body -->
                </div>
           </div>
           <div class="col-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills nav-fill">
                  <li class="nav-item"><a class="nav-link active" href="#activity" data-bs-toggle="tab">Datos Personales</a></li>
                  
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                        <form class="form-horizontal">
                                <div class="row">
                                          <div class="form-group col-6">
                                              <label for="inputName" class="col-sm-2 control-label">Usuario</label>

                                              <div class="col-sm-12">
                                              <input type="" v-model="form.name" class="form-control" id="inputName" placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                              <has-error :form="form" field="name"></has-error>
                                              </div>
                                          </div>
                                          
                                          <div class="form-group col-6">
                                              <label for="inputName" class="col-sm-2 control-label">Nombres</label>

                                              <div class="col-sm-12">
                                              <input type="" v-model="form.nombres" class="form-control" id="nombres" placeholder="Nombres" :class="{ 'is-invalid': form.errors.has('nombres') }">
                                              <has-error :form="form" field="nombres"></has-error>
                                              </div>
                                          </div>
                                          <div class="form-group  col-6">
                                              <label for="inputName" class="col-sm-2 control-label">Apellidos</label>

                                              <div class="col-sm-12">
                                              <input type="" v-model="form.apellidos" class="form-control" id="apellidos" placeholder="Apellidos" :class="{ 'is-invalid': form.errors.has('apellidos') }">
                                              <has-error :form="form" field="apellidos"></has-error>
                                              </div>
                                          </div>
                                          <div class="form-group  col-6">
                                              <label for="inputName" class="col-sm-2 control-label">DNI</label>

                                              <div class="col-sm-12">
                                              <input type="" v-model="form.dni" class="form-control" id="dni" placeholder="DNI" :class="{ 'is-invalid': form.errors.has('dni') }">
                                              <has-error :form="form" field="dni"></has-error>
                                              </div>
                                          </div>
                                          <div class="form-group  col-6">
                                              <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                              <div class="col-sm-12">
                                              <input type="email" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email"  :class="{ 'is-invalid': form.errors.has('email') }">
                                              <has-error :form="form" field="email"></has-error>
                                              </div>
                                          </div>
                                          <div class="form-group  col-6">
                                              <label for="avatar" class="col-sm-2 control-label">Avatar</label>
                                              <div class="col-sm-12">
                                                  <input type="file" @change="updateProfile" name="avatar" class="form-input">
                                              </div>

                                          </div>
                                          <div class="form-group col-6">
                                              <label for="password" class="col-sm-12 control-label">Password (Si deja en blanco no se cambiar el password)</label>

                                              <div class="col-sm-12">
                                              <input type="password"
                                                  v-model="form.password"
                                                  class="form-control"
                                                  id="password"
                                                  placeholder="Passport"
                                                  :class="{ 'is-invalid': form.errors.has('password') }"
                                              >
                                              <has-error :form="form" field="password"></has-error>
                                              </div>
                                          </div>

                                          <div class="form-group  col-6">
                                              <div class="col-sm-offset-2 col-sm-12">
                                              <button @click.prevent="updateInfo" type="submit" class="btn btn-success">Actualizar</button>
                                              </div>
                                          </div>


                                </div>
                        </form>
                    
                    <!-- /.post -->
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="timeline">
                    
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="settings">
                    
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>


        </div>
        <div v-else>
             <not-found></not-found>
            </div>


    </div>
</template>

<script>
    export default {
        data() {
            return {

                 rol:[],
                error:{},
                form: new Form({
                    id:'',
                    name : '',
                    nombres : '',
                    apellidos : '',
                    dni : '',
                    email: '',                    
                    roles: [{
                        name:''
                    }],
                    password: '',
                    avatar: ''
                })
            }
        },
       
        methods:{

                getProfilePhoto(){
                let avatar = (this.form.avatar.length > 200) ? this.form.avatar : "/storage/"+ this.form.avatar ;
                return avatar;
                 },
                updateInfo(){
                this.form.put('/api/profile')
                .then( data => {
                    Swal.fire('Correcto','El usuario ha sido actualizado','success')
                     EventBus.$emit('actuausuario',data.data);
                     Fire.$emit('AfterCreate');
                    
                })
                .catch(() => {
                    
                });
            },

               updateProfile(e){
                let file = e.target.files[0];
                let reader = new FileReader();

                if(file['size'] < 2111775){
                     reader.onloadend = (file) => {
                    this.form.avatar=reader.result;
                    }
                reader.readAsDataURL(file);

                }else{
                    Swal.Fire({
                        type:'error',
                        title:'Lo siento',
                        text:'El archivo exede el tamaño',
                    })


                }

               
                
            }
        },
        created() {
            axios.get("/api/perfil").then(({ data }) => (this.form.fill(data))).catch(error => { this.error = error.response });
            document.title = 'Perfil - SBH'
        }
    }
</script>
