<template>
    <div class="">
       <div class="card card-info" v-if="error.status != 403">
        <div class="card-header">
            <h3 class="card-title">Seguro de Desgravamen  </h3>            
                <div class="card-tools">
                     
                </div>            
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                    <th width="10px">ID</th>
                    <th>Monto Seguro Desgravamen</th>
                    <th >Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="segu in seguro" :key="segu.id">
                          <td>{{segu.id}}</td>
                          <td>{{segu.seg_des}}</td>
                          <td><a href="#" @click="editRol(segu)"><i class="fa fa-edit blue"></i></a></td>
                        </tr>                    
                </tbody>
            </table>
        </div>
    </div>
    <div v-else>
             <not-found></not-found>
    </div>
    <div class="modal fade" id="nuevorol" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewLabel">Actualizar Seguro Desgravamen</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="actuaRol() ">
                    <div class="modal-body">
                        <div class="form-group">
                            <input v-model="form.seg_des" type="text" name="name"
                                placeholder="Name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        <button  type="submit" class="btn btn-success">Actualizar</button>
                        
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
                editmode: false,
                error:{},
                permi:[],
                seguro : {},
                form: new Form({
                    id:'',
                    seg_des : '',
                })
            }
        },
        methods: {
            
            actuaRol(){
               // this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/segurodes/'+this.form.id)
                .then(() => {
                    // success
                    $('#nuevorol').modal('hide');
                     Swal.fire(
                        'Actualizado',
                        'Se Actualizo el Seguro',
                        'success'
                        )
                        //this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch(() => {
                   // this.$Progress.fail();
                });

            },
            
            editRol(seguro){
                this.editmode = true;
                this.form.reset();
                this.form.fill(seguro);               
                $('#nuevorol').modal('show');
            },
            
            loadUsers(){
                
                    axios.get("api/segurodes").then(({ data }) => (this.seguro = data)).catch(error => { this.error = error.response });
                    
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
