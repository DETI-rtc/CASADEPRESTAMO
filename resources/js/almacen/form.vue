<template>
    <el-dialog :title="titleDialog" :visible="showDialog" width="80%" @close="close" @open="create" @opened="opened" :close-on-click-modal="false" append-to-body>
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <el-card class="box-card" >
                  <div slot="header" class="clearfix">                   
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" :class="{'text-danger': errors.errors.descripcion}">
                            <label class="control-label">Nombre de Almacen<span class="text-danger">*</span></label>
                             <el-input v-model="form.descripcion" ></el-input>
                            <small class="text-danger" v-if="errors.errors.descripcion" v-text="errors.errors.descripcion[0]"></small>
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
                detdifunto:false,
                showDialogNewDifu:false,
                loading_submit: false,
                titleDialog: null,
                resource: 'almacen',
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
                this.form = {
                    id: null,
                    descripcion:'',
                }
            },
            async opened() {
                if(this.external ) {    }
            },            
            create() {               
                if(this.external) {  }
                this.titleDialog = (this.recordId)? 'Editar Almacen':'Nuevo Almacen'
                if (this.recordId) {
                    this.detdifunto=true;
                    axios.get(`/api/${this.resource}/${this.recordId}`)
                        .then(response => {
                            this.form = response.data })
                }
            },            
            async submit() {
                this.loading_submit = true

                await axios.post(`api/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            if (this.external) {
                                this.$eventHub.$emit('reloadDataAlmacenes', response.data.id)
                            } else {
                                console.log('entra reloadaTables');
                                this.$eventHub.$emit('reloadDifuindex')
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
