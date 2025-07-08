<template>
    <el-dialog :title="titleDialog" :visible="showDialog" width="80%" @close="close" @open="create" @opened="opened" :close-on-click-modal="false" append-to-body>
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <el-card class="box-card" >
                  <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" :class="{'text-danger': errors.errors.descripcion}">
                                <label class="control-label">Tipo Comprobante<span class="text-danger">*</span></label>
                                <el-input v-model="form.descripcion" ></el-input>
                                <small class="text-danger" v-if="errors.errors.descripcion" v-text="errors.errors.descripcion[0]"></small>
                            </div>
                        </div>  
                        <div class="col-md-6">
                            <div class="form-group" :class="{'text-danger': errors.errors.tipo_serie}">
                                <label class="control-label">Serie<span class="text-danger">*</span></label>
                                <el-input v-model="form.tipo_serie" ></el-input>
                                <small class="text-danger" v-if="errors.errors.tipo_serie" v-text="errors.errors.tipo_serie[0]"></small>
                            </div>
                        </div> 
                  </div>
                </el-card>                
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
           
    </el-dialog>
</template>

<script>

    import {serviceNumber} from '../mixins/functions'
   
    export default {
        mixins: [serviceNumber],
       
        props: ['showDialog', 'type', 'recordId', 'external'],
        data() {
            return {
                
                showDialogNewDifu:false,
                loading_submit: false,
                titleDialog: null,
                resource: 'tipocompro',
                errors: {
                  errors:{}
                },
                form: {},
            }
        },
        async created() {
            await this.initForm()
            
             

        },
        
        methods: {
            initForm() {
               // this.errors = {}
                
                this.form = {
                    id: null,
                    tipo: this.type,
                    descripcion: '',                    
                    tipo_serie:'',
                }
            },
            async opened() {

                if(this.external ) {
                    
                }

            },
            
            create() {
                // console.log(this.input_person)
                if(this.external) {
                    
                    
                }
                
                this.titleDialog = (this.recordId)? 'Editar Tipo Comprobante':'Nuevo Tipo Comprobante'
                
                if (this.recordId) {
                   
                    axios.get(`/api/${this.resource}/${this.recordId}`)
                        .then(response => {
                            this.form = response.data
                            
                        })
                }
            },
            
            async submit() {
                this.loading_submit = true
                await axios.post(`api/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            if (this.external) {
                                this.$eventHub.$emit('reloadDataTipocompro', response.data.id)
                            } else {
                                
                                this.$eventHub.$emit('reloadTipoindex')
                            }
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
           
            close() {
                //this.$eventHub.$emit('initInputPerson')
                 this.$emit('update:showDialog', false)
                this.initForm()
            },           
           
        }
    }
</script>
