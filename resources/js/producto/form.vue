<template>
    <el-dialog width="65%" :title="titleDialog" :visible="showDialog" :close-on-click-modal="false" @close="close" @open="create" append-to-body top="7vh">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.nombre}">
                            <label class="control-label">Nombre<span class="text-danger">*</span></label>
                            <el-input v-model="form.nombre" dusk="description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.nombre[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.unidad_id}">
                            <label class="control-label">Unidad</label>
                            <el-select v-model="form.unidad_id" dusk="unidad_id">
                                <el-option v-for="option in unidades" :key="option.id" :value="option.id" :label="option.descripcion"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.unidad_id" v-text="errors.unidad_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.idmoneda}">
                            <label class="control-label">Moneda</label>
                            <el-select v-model="form.idmoneda" >
                                <el-option v-for="option in monedas" :key="option.id" :value="option.id" :label="option.descripcion"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.idmoneda" v-text="errors.idmoneda[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.valor_unitario}">
                            <label class="control-label">Precio Unitario (Venta) <span class="text-danger">*</span></label>
                            <el-input v-model="form.valor_unitario" dusk="valor_unitario" ></el-input>
                            <small class="form-control-feedback" v-if="errors.valor_unitario" v-text="errors.valor_unitario[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.idtipoafectacion}">
                            <label class="control-label">Tipo de afectación (Venta)</label>
                            <el-select v-model="form.idtipoafectacion" @change="changeAffectationIgvType">
                                <el-option v-for="option in tiposafectacion" :key="option.id" :value="option.id" :label="option.descripcion"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.idtipoafectacion" v-text="errors.idtipoafectacion[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.idcategoria}">
                            <label class="control-label">
                                Categoría
                            </label>

                            <a href="#" v-if="form_categoria.add == false" class="control-label font-weight-bold text-info" @click="form_categoria.add = true"> [ + Nuevo]</a>
                            <a href="#" v-if="form_categoria.add == true" class="control-label font-weight-bold text-info" @click="saveCategory()"> [ + Guardar]</a>
                            <a href="#" v-if="form_categoria.add == true" class="control-label font-weight-bold text-danger" @click="form_categoria.add = false"> [ Cancelar]</a>
                            <el-input   v-if="form_categoria.add == true" v-model="form_categoria.nombre" dusk="item_code" style="margin-bottom:1.5%;"></el-input>

                            <el-select v-if="form_categoria.add == false" v-model="form.idcategoria" filterable clearable>
                                <el-option v-for="option in categorias" :key="option.id" :value="option.id" :label="option.nombre"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.idcategoria" v-text="errors.idcategoria[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.codigo_sunat}">
                            <label class="control-label">Código Sunat
                                <el-tooltip class="item" effect="dark" content="Código proporcionado por SUNAT, campo obligatorio para exportaciones" placement="top">
                                    <i class="fa fa-info-circle"></i>
                                </el-tooltip>
                            </label>
                            <el-input v-model="form.codigo_sunat" dusk="codigo_sunat"></el-input>
                            <small class="form-control-feedback" v-if="errors.codigo_sunat" v-text="errors.codigo_sunat[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3" v-show="recordId==null && form.unit_type_id !='ZZ'">
                        <div class="form-group" :class="{'has-danger': errors.stock_ini}">
                            <label class="control-label">Stock Inicial</label>
                            <el-input v-model="form.stock_ini" ></el-input> 
                            <small class="form-control-feedback" v-if="errors.stock_ini" v-text="errors.stock_ini[0]"></small>
                        </div>
                    </div>
                    <div  class="col-md-3" v-show="recordId==null" v-if="form.unit_type_id !='ZZ'">
                        <div class="form-group" :class="{'has-danger': errors.idalmacen}">
                            <label class="control-label">
                                Almacén
                                <el-tooltip class="item" effect="dark" content="Si no selecciona almacén, se asignará por defecto el relacionado al establecimiento" placement="top">
                                    <i class="fa fa-info-circle"></i>
                                </el-tooltip>
                            </label>
                            <el-select v-model="form.idalmacen" filterable >
                                <el-option v-for="option in almacenes" :key="option.id" :value="option.id" :label="option.descripcion"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.idalmacen" v-text="errors.idalmacen[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3" v-show="form.unit_type_id !='ZZ'">
                        <div class="form-group" :class="{'has-danger': errors.fec_venc}">
                            <label class="control-label">Fec. Vencimiento</label>
                            <el-date-picker v-model="form.fec_venc" type="date" value-format="yyyy-MM-dd" :clearable="true"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.fec_venc" v-text="errors.fec_venc[0]"></small>
                        </div>
                    </div>

                   
                    <div class="col-md-3" >
                        <div class="form-group" :class="{'has-danger': errors.cod_barra}">
                            <label class="control-label">Código de barra</label>
                            <el-input v-model="form.cod_barra" ></el-input>
                            <small class="form-control-feedback" v-if="errors.cod_barra" v-text="errors.cod_barra[0]"></small>
                        </div>
                    </div>

                                      
                    <div class="row col-md-12">
                        <div class="col-md-3">
                            <div class="form-group" >
                                <label class="control-label">Imágen <span class="text-danger"></span></label>
                                <el-upload class="avatar-uploader"
                                        :data="{'type': 'items'}"
                                        
                                        :action="`/${resource}/upload`"
                                        :show-file-list="false"
                                        :on-success="onSuccess">
                                    <img v-if="form.image_url" :src="form.image_url" class="avatar">
                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                </el-upload>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
        

    </el-dialog>
</template>

<script>
    // import PercentagePerception from './partials/percentage_perception.vue'
    //import LotsForm from './partes/lotes.vue'

    export default {
        props: ['showDialog', 'recordId', 'external'],
        //components: {LotsForm},

        data() {
            return {
                showDialogLots:false,
                form_categoria:{ add: false, nombre: null, id: null },                
                almacenes: [],
                loading_submit: false,
                titleDialog: null,
                resource: 'producto',
                errors: {},
                
                form: {},
                configuration: {},
                unidades: [],
                monedas: [],                
                tiposafectacion: [],
                categorias: [],
                
               
            }
        },
        async created() {
            await this.initForm()
            await axios.get(`/api/${this.resource}/tablas`)
                .then(response => {
                    this.unidades = response.data.unidades
                    this.tiposafectacion = response.data.tiposafectacion
                    this.almacenes = response.data.almacenes
                    this.categorias = response.data.categorias   
                    this.monedas = response.data.monedas          

                    
                })

            this.$eventHub.$on('reloadTables', ()=>{
                this.reloadTables()
            })

            

        },

        methods: {
           
            
            async reloadTables(){

                await axios.get(`/api/${this.resource}/tablas`)
                    .then(response => {
                        console.log('reload');
                        this.unidades = response.data.unidades
                        this.tiposafectacion = response.data.tiposafectacion
                        this.almacenes = response.data.almacenes
                        this.categorias = response.data.categorias 
                        this.monedas = response.data.monedas 
                 })
            },
            
            initForm() {
                this.loading_submit = false,
                this.errors = {}
                this.form = {
                    id: null,
                    nombre: null,
                    idmoneda: 'PEN',
                    valor_unitario: 0,
                    stock_ini: 0,
                    idtipoafectacion: "10",
                    img_pro: null,
                    image_url: null,
                    temp_path: null,
                    unidad_id:null,
                    codigo_sunat:'',
                    idcategoria:null,
                    idalmacen:null,
                    fec_venc:'',
                    cod_barra:'',
                }               
            },
            onSuccess(response, file, fileList) {
                if (response.success) {
                    this.form.image = response.data.filename
                    this.form.image_url = response.data.temp_image
                    this.form.temp_path = response.data.temp_path
                } else {
                    this.$message.error(response.message)
                }
            },
            changeAffectationIgvType(){

                // let affectation_igv_type_exonerated = [20,21,30,31,32,33,34,35,36,37]
                // let is_exonerated = affectation_igv_type_exonerated.includes((parseInt(this.form.sale_affectation_igv_type_id)));

                // if(is_exonerated){
                //     this.show_has_igv = false
                //     this.form.has_igv = true
                // }else{
                //     this.show_has_igv = true
                // }

            },
            
            resetForm() {
                this.initForm()
               // this.form.sale_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                //this.form.purchase_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
               
            },
            create() {

                this.titleDialog = (this.recordId)? 'Editar Producto':'Nuevo Producto'
                if (this.recordId) {
                    axios.get(`/api/${this.resource}s/${this.recordId}`)
                        .then(response => {
                            this.form = response.data
                            
                            this.changeAffectationIgvType()
                            
                        })
                }

            },
            loadRecord(){
                if (this.recordId) {
                    axios.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
                            this.changeAffectationIgvType()
                            
                        })
                }
            },
            
            async submit() {

               // if(this.validateItemUnitTypes() > 0) return this.$message.error('El campo factor no puede ser menor a 0.0001');

                //if(this.form.has_perception && !this.form.percentage_perception) return this.$message.error('Ingrese un porcentaje');
                // if(!this.has_percentage_perception) this.form.percentage_perception = null

                /*if(!this.recordId && this.form.lots_enabled){

                    if(this.form.lots.length > this.form.stock)
                        return this.$message.error('La cantidad de series registradas es superior al stock');

                    if(!this.form.lot_code)
                        return this.$message.error('Código de lote es requerido');

                    if(this.form.lots.length != this.form.stock)
                        return this.$message.error('La cantidad de series registradas son diferentes al stock');

                }*/

                if(!this.recordId && this.form.series_enabled)
                {

                    // if(this.form.lots.length > this.form.stock)
                    //     return this.$message.error('La cantidad de series registradas es superior al stock');

                    // if(this.form.lots.length != this.form.stock)
                    //     return this.$message.error('La cantidad de series registradas son diferentes al stock');
                }

                this.loading_submit = true
                await axios.post(`/api/${this.resource}s`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            if (this.external) {
                                this.$eventHub.$emit('reloadDataItems', response.data.id)
                            } else {
                                this.$eventHub.$emit('reloadData')
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
                this.$emit('update:showDialog', false)
                this.resetForm()
            },
            
            saveCategory()
            {
                this.form_categoria.add = false

                axios.post(`/api/categoria`,  this.form_categoria)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        this.categorias.push(response.data.data)
                        this.form_categoria.nombre = null
                    } else {
                        this.$message.error('No se guardaron los cambios')
                    }
                })
                .catch(error => {

                })
            },
            
        }
    }
</script>
