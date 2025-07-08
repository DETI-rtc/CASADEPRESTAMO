<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" @opened="opened" :close-on-click-modal="false" append-to-body>
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.tipo_documento_id}">
                            <label class="control-label">Tipo Doc. Identidad <span class="text-danger">*</span></label>
                            <el-select v-model="form.tipo_documento_id" filterable  popper-class="el-select-identity_document_type" dusk="tipo_documento_id" @change="changeIdentityDocType">
                                <el-option v-for="option in tiposdedocumentos" :key="option.id" :value="option.id" :label="option.nombre"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.tipo_documento_id" v-text="errors.tipo_documento_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.numerodoc}">
                            <label class="control-label">Número <span class="text-danger">*</span></label>

                           
                                <el-input v-model="form.numerodoc" >
                                    <template v-if="form.tipo_documento_id === 6 || form.tipo_documento_id === 1">
                                        <el-button type="primary" slot="append" :loading="loading_search" icon="el-icon-search" @click="searchCustomer">
                                            <template v-if="form.tipo_documento_id === 6">
                                                SUNAT
                                            </template>
                                            <template v-if="form.tipo_documento_id === 1">
                                                RENIEC
                                            </template>
                                        </el-button>
                                    </template>
                                </el-input>
                            

                            <small class="form-control-feedback" v-if="errors.numerodoc" v-text="errors.numerodoc[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.nombre}">
                            <label class="control-label"><template v-if="form.tipo_documento_id === 6">
                                                Razon Social
                                            </template>
                                            <template v-else>
                                                Nombre Completo
                                            </template>
                                             <span class="text-danger">*</span></label>
                            <el-input v-model="form.nombre" dusk="name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.nombre[0]"></small>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.razon_social}">
                            <label class="control-label"><template v-if="form.tipo_documento_id === 6">
                                                Razon Social
                                            </template>
                                            <template v-else>
                                                Nombre Completo
                                            </template>
                                            
                                            </label>
                            <el-input v-model="form.razon_social" dusk="razon_social"></el-input>
                            <small class="form-control-feedback" v-if="errors.razon_social" v-text="errors.razon_social[0]"></small>
                        </div>
                    </div> -->
                </div>

                <!-- <div class="row" v-if="type === 'customers'">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.person_type_id}">
                            <label class="control-label">Tipo de cliente</label>
                            <el-select v-model="form.person_type_id" filterable  >
                                <el-option v-for="option in person_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.person_type_id" v-text="errors.person_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group"  >
                            <label class="control-label">Comentario</label>
                            <el-input v-model="form.comment"></el-input>
                        </div>
                    </div>
                </div> -->

                <div class="row">
                    
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.country_id}">
                            <label class="control-label">País</label>
                            <el-select v-model="form.country_id" filterable dusk="country_id">
                                <el-option v-for="option in countries" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.country_id" v-text="errors.country_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.department_id}">
                            <label class="control-label">Departamento</label>
                            <el-select v-model="form.department_id" filterable @change="filterProvince" popper-class="el-select-departments" dusk="department_id">
                                <el-option v-for="option in all_departments" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.department_id" v-text="errors.department_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.province_id}">
                            <label class="control-label">Provincia</label>
                            <el-select v-model="form.province_id" filterable @change="filterDistrict" popper-class="el-select-provinces" dusk="province_id">
                                <el-option v-for="option in provinces" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.province_id" v-text="errors.province_id[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.province_id}">
                            <label class="control-label">Distrito</label>
                            <el-select v-model="form.district_id" filterable popper-class="el-select-districts" dusk="district_id">
                                <el-option v-for="option in districts" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.district_id" v-text="errors.district_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.direccion}">
                            <label class="control-label">Dirección</label>
                            <el-input v-model="form.direccion" dusk="direccion"></el-input>
                            <small class="form-control-feedback" v-if="errors.direccion" v-text="errors.direccion[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.telefono}">
                            <label class="control-label">Teléfono</label>
                            <el-input v-model="form.telefono" dusk="telefono"></el-input>
                            <small class="form-control-feedback" v-if="errors.telefono" v-text="errors.telefono[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.email}">
                            <label class="control-label">Correo electrónico</label>
                            <el-input v-model="form.email" dusk="email"></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>
                    
                </div>
                <!-- <div class="row">
                    <div class="col-md-6" v-if="form.state">
                        <div class="form-group" >
                            <label class="control-label">Estado del Contribuyente</label>
                            <template v-if="form.state == 'ACTIVO'">
                                <el-alert   :title="`${form.state}`"  type="success"   show-icon :closable="false"></el-alert>
                            </template>
                            <template v-else>
                                <el-alert   :title="`${form.state}`"  type="error"   show-icon :closable="false"></el-alert>
                            </template>
                        </div>

                    </div>
                    <div class="col-md-6" v-if="form.condition">
                        <div class="form-group" >
                            <label class="control-label">Condición del Contribuyente</label>
                            <template v-if="form.condition == 'HABIDO'">
                                <el-alert   :title="`${form.condition}`"  type="success"   show-icon :closable="false"></el-alert>
                            </template>
                            <template v-else>
                                <el-alert   :title="`${form.condition}`"  type="error"   show-icon :closable="false"></el-alert>
                            </template>
                        </div>

                    </div>
                </div> -->
                <!-- <div class="row border-top mt-2">
                    <div class="col-12">
                        <h4>Contacto</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.contact}">
                            <label class="control-label">Nombre y Apellido</label>
                            <el-input v-model="form.contact"></el-input>
                            <small class="form-control-feedback" v-if="errors.contact" v-text="errors.contact[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.contact}">
                            <label class="control-label">Teléfono</label>
                            <el-input v-model="form.telefono_cont"></el-input>
                            <small class="form-control-feedback" v-if="errors.contact" v-text="errors.contact[0]"></small>
                        </div>
                    </div>
                </div> -->
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
        props: ['showDialog', 'type', 'recordId', 'external', 'document_type_id','input_person'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'cliente',
                errors: {},
                api_service_token:false,
                form: {},
                countries: [],
                all_departments: [],
                all_provinces: [],
                all_districts: [],
                provinces: [],
                districts: [],
                locations: [],
                person_types: [],
                tiposdedocumentos: []
            }
        },
        async created() {
            await this.initForm()
            await axios.get(`/api/${this.resource}/tablas`)
                .then(response => {
                    this.api_service_token = response.data.api_service_token
                    // console.log(this.api_service_token)

                    this.countries = response.data.countries
                    this.all_departments = response.data.departments;
                    this.all_provinces = response.data.provinces;
                    this.all_districts = response.data.districts;
                    this.tiposdedocumentos = response.data.tipodocumento;
                    this.locations = response.data.locations;
                    this.person_types = response.data.person_types;
                })
             

        },
        computed: {
            maxLength: function () {
                if (this.form.tipo_documento_id === 6) {
                    return 11
                }
                if (this.form.tipo_documento_id === 1) {
                    return 8
                }
            }
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    tipo: this.type,
                    tipo_documento_id: 6,
                    nombre: '',
                    razon_social: null,
                    trade_name: null,
                    country_id: 'PERU',
                    department_id: null,
                    province_id: null,
                    district_id: null,
                    direccion: null,
                    telefono: null,
                    email: null,
                    nombre_cont: '',
                    telefono_cont: '',
                }
            },
            async opened() {

                if(this.external ) {
                    if(this.form.numerodoc.length === 8 || this.form.numerodoc.length === 11){
                        // if(this.api_service_token == false){
                        //     this.searchCustomer()
                        // }else{
                        //     await this.$eventHub.$emit('enableClickSearch')
                            
                        // }
                    }
                }

            },
            create() {
                // console.log(this.input_person)
                if(this.external) {
                    if(this.document_type_id === '01') {
                        this.form.tipo_documento_id = '6'
                    }
                    if(this.document_type_id === '03') {
                        this.form.tipo_documento_id = '1'
                    }

                    if(this.input_person) {
                        //this.form.tipo_documento_id = (this.input_person.tipo_documento_id) ? this.input_person.tipo_documento_id: this.form.tipo_documento_id
                        //this.form.numerodoc = (this.input_person.numerodoc) ? this.input_person.numerodoc:''
                    }
                }
                
                this.titleDialog = (this.recordId)? 'Editar Cliente':'Nuevo Cliente'
                
                // if(this.type === 'suppliers') {
                //     this.titleDialog = (this.recordId)? 'Editar Proveedor':'Nuevo Proveedor'
                // }
                if (this.recordId) {
                    axios.get(`/api/${this.resource}s/${this.recordId}`)
                        .then(response => {
                            this.form = response.data
                            if (response.data.contact == null) {
                                this.form.contact = ''
                            }
                            this.filterProvinces()
                            this.filterDistricts()
                        })
                }
            },
           
            validateDigits(){

                const pattern_number = new RegExp('^[0-9]+$', 'i');

                if (this.form.tipo_documento_id === '6') {

                    if(this.form.numerodoc.length !== 11){
                        return {
                            success: false,
                            message: `El campo número debe tener 11 dígitos.`
                        }
                    }

                    if(!pattern_number.test(this.form.numerodoc)){
                        return {
                            success: false,
                            message: `El campo número debe contener solo números`
                        }
                    }

                }


                if (this.form.tipo_documento_id === '1') {

                    if(this.form.numerodoc.length !== 8){
                        return {
                            success: false,
                            message: `El campo número debe tener 8 dígitos.`
                        }
                    }

                    if(!pattern_number.test(this.form.numerodoc)){
                        return {
                            success: false,
                            message: `El campo número debe contener solo números`
                        }
                    }
                }


                if(['4', '7', '0'].includes(this.form.tipo_documento_id)){

                    const pattern = new RegExp('^[A-Z0-9\-]+$', 'i');

                    if(!pattern.test(this.form.numerodoc)){
                        return {
                            success: false,
                            message: `El campo número no cumple con el formato establecido`
                        }
                    }

                }


                return {
                    success: true
                }
            },
            async submit() {

                let val_digits = await this.validateDigits()
                if(!val_digits.success){
                    return this.$message.error(val_digits.message)
                }

                this.loading_submit = true

                await axios.post(`api/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            if (this.external) {
                                this.$eventHub.$emit('reloadDataPersons', response.data.id)
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
            changeIdentityDocType(){
                (this.recordId == null) ? this.setDataDefaultCustomer() : null
            },
            setDataDefaultCustomer(){

                if(this.form.tipo_documento_id === 0){
                    this.form.numerodoc = '99999999'
                    this.form.nombre = "Clientes - Varios"
                }else{
                    this.form.numerodoc = ''
                    this.form.nombre = null
                }

            },
            close() {
                //this.$eventHub.$emit('initInputPerson')
                 this.$emit('update:showDialog', false)
                this.initForm()
            },
            async searchCustomer() {
                if(this.form.numerodoc === '') {
                this.$message.error('Ingresar el número a buscar')
                return
                }
                let identity_document_type_name = ''
                if (this.form.tipo_documento_id === '6') {
                    identity_document_type_name = 'ruc'
                }
                if (this.form.tipo_documento_id === '1') {
                    identity_document_type_name = 'dni'
                }
                this.loading_search = true
                let response = await axios.get(`/api/buscarruc/${this.form.numerodoc}`)
                //console.log(response.data);
                if(response.data.success) {
                    console.log(response.data.nombre);
                    this.form.direccion = response.data.direccion
                    this.form.nombre = response.data.nombre
                   // console.log(response.data.data);
                    

                    //this.filterProvinces()
                    //this.filterDistricts()

                } else {
                    this.$message.error(response.data.message)
                }
                this.loading_search = false
            },
            searchNumber(data) {
                this.form.name = (this.form.tipo_documento_id === '1')?data.nombre_completo:data.nombre_o_razon_social;
                this.form.trade_name = (this.form.tipo_documento_id === '6')?data.nombre_o_razon_social:'';
                this.form.location_id = data.ubigeo;
                this.form.address = data.direccion;
                this.form.department_id = (data.ubigeo) ? (data.ubigeo[0] != '-' ? data.ubigeo[0] : null) : null;
                this.form.province_id = (data.ubigeo) ? (data.ubigeo[1] != '-' ? data.ubigeo[1] : null) : null;
                this.form.district_id = (data.ubigeo) ? (data.ubigeo[2] != '-' ? data.ubigeo[2] : null) : null;
                this.form.condition = data.condicion;
                this.form.state = data.estado;

                this.filterProvinces()
                this.filterDistricts()
//                this.form.addresses[0].telephone = data.telefono;
           },
           clickRemoveAddress(index)
           {
                this.form.addresses.splice(index, 1);
           }
        }
    }
</script>
