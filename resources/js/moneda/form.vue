<template>
    <el-dialog :title="titleDialog" :visible="showDialog" width="80%" @close="close" @open="create" @opened="opened" :close-on-click-modal="false" append-to-body>
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <el-card class="box-card" v-show="!detdifunto">
                  <div slot="header" class="clearfix">
                    <span>Seleccionar Nicho</span>
                  </div>
                  <div  class="row">
                      <div class="row mb-3">
                                  <div class="col-md-8 offset-md-2">
                                    <el-select v-model="form.codpabe" filterable @change="mostrarpabe" popper-class="el-select-provinces" >
                                      <el-option v-for="option in pabellones" :key="option.id" :value="option.id" :label="option.nombre"></el-option>
                                  </el-select>
                                      <!-- <v-select  label="ctitpabe" :options="pabellones" :reduce="ctitpabe => ctitpabe.ccodpabe" v-model="codpabe" :value="selected"   @input="mostrarpabe"></v-select> -->
                                  </div>
                                  <div class="col-12 text-center mt-3">
                                      <button type="button" @click="mostrar(e)" v-for="e in ncaras" :key="e" class="btn btn-primary">CARA 0{{e}}</button>
                                  </div>
                          </div>
                          <div class="row" id="paramostrar">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
                                <div class="text-center"><h2 v-text="nombrepabe"></h2></div>
                                  <div class="table-responsive">                           
                                      <table class="table">
                                          <thead>
                                          <tr>
                                              <th scope="col">#</th>
                                              <th scope="col" class="text-center" v-for="i in numcol" :key="i">{{i}} </th>                            
                                          </tr>
                                          </thead>
                                          <tbody>
                                          <tr v-for="p in filas " :key="p.numero">
                                              <th scope="row" class="tdifu text-center">{{p.letra}}</th>
                                              <td class="tdifu text-center" v-for="i in numcol" :key="i" >
                                                      <template v-if ="columna1[p.letra][i] == 1" >  
                                                          <a class="btn btn-app1 btn-block bg-primary">                   
                                                          <i class="fas fa-user"></i> 
                                                          </a>
                                                      </template>
                                                      <template v-if ="columna1[p.letra][i] == 0" >   
                                                              <a class="btn btn-app1 btn-block bg-secondary " :id="p.letra+i" @click="seldifunto(p.letra,i,0)">        
                                                              <i class="fas fa-inbox"></i> 
                                                              </a>
                                                      </template>
                                                      <template v-if ="columna1[p.letra][i] == 5" >   
                                                              <a class="btn btn-app1 btn-block bg-info" >        
                                                              <i class="fas fa-inbox"></i> 
                                                              </a>
                                                      </template>
                                                      <template v-if ="columna1[p.letra][i] == 4" >   
                                                              <a class="btn btn-app1 btn-block bg-warning" >        
                                                              <i class="fas fa-inbox"></i> 
                                                              </a>
                                                      </template>
                                                      <template v-if ="columna1[p.letra][i] == 2" >   
                                                              <a class="btn btn-app1 btn-block bg-success" >        
                                                              <i class="fas fa-inbox"></i> 
                                                              </a>
                                                      </template>
                                                      <template v-if ="columna1[p.letra][i] == 3" >   
                                                              <a class="btn btn-app1 btn-block bg-orange" >        
                                                              <i class="fas fa-inbox"></i> 
                                                              </a>
                                                      </template>
                                              </td>
                                          </tr>    
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                              <div class="col-12 mt-1 text-center">
                                  <span class="btn btn-secondary btn-sm">
                                    <i class="fas fa-inbox"></i> Vacios
                                  </span>
                                  <a class="btn btn-primary btn-sm">
                                    <i class="fas fa-users"></i> Ocupados
                                  </a>
                                  <a class="btn btn-success btn-sm" style="font-size:13px !important">
                                    <i class="fas fa-users"></i> Reservados
                                  </a>
                                  <a class="btn btn-danger btn-sm" style="font-size:8px !important">
                                  <i class="fas fa-inbox"></i> Vacios 2da <br> menor 10 años
                                  </a>
                                  <a class="btn btn-warning btn-sm" style="font-size:8px !important">
                                    <i class="fas fa-inbox"> </i>Vacios 2da<br> menor 15 años
                                  </a>
                                  <a class="btn btn-info btn-sm" style="font-size:8px !important">
                                  <i class="fas fa-inbox"></i>Vacios 2da <br>mayor 16 años
                                  </a>
                              </div>
                          </div>
                  </div>
                </el-card>

                <el-card class="box-card" v-show="detdifunto">
                  <div slot="header" class="clearfix">
                    <span class="text-primary"><strong>  Codigo de Nicho: </strong>{{form.codnicho}}</span>
                    <el-button style="float: right; padding: 3px 0" type="primary" icon="el-icon-caret-left" @click.prevent="volver" round>Cambiar Nicho</el-button>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" :class="{'text-danger': errors.errors.tipo_documento_id}">
                            <label class="control-label">Tipo Doc. Identidad <span class="text-danger">*</span></label>
                            <el-select v-model="form.tipo_documento_id" filterable  popper-class="el-select-identity_document_type" dusk="tipo_documento_id" @change="changeIdentityDocType">
                                <el-option v-for="option in tiposdedocumentos" :key="option.id" :value="option.id" :label="option.descripcion"></el-option>
                            </el-select>
                            <small class="text-danger" v-if="errors.errors.tipo_documento_id" v-text="errors.errors.tipo_documento_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'text-danger': errors.errors.numerodoc}">
                            <label class="control-label">Número <span class="text-danger">*</span></label>

                           
                                <el-input v-model="form.numerodoc"  >
                                    <template v-if="form.tipo_documento_id === 1">
                                        <el-button type="primary" slot="append" :loading="loading_search" icon="el-icon-search" @click.prevent="searchCustomer">
                                           
                                            <template v-if="form.tipo_documento_id === 1">
                                                RENIEC
                                            </template>
                                        </el-button>
                                    </template>
                                </el-input>
                            

                            <small class="text-danger" v-if="errors.errors.numerodoc" v-text="errors.errors.numerodoc[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'text-danger': errors.errors.nombre_dif}">
                            <label class="control-label">                                           
                                                Nombre Completo
                                          
                                             <span class="text-danger">*</span></label>
                            <el-input v-model="form.nombre_dif" dusk="name"></el-input>
                            <small class="text-danger" v-if="errors.errors.nombre_dif" v-text="errors.errors.nombre_dif[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" :class="{'text-danger': errors.errors.gendifu}">
                            <label class="control-label">Genero</label>
                             <el-select v-model="form.gendifu" filterable >
                                <el-option v-for="option in sexo" :key="option.value" :value="option.value" :label="option.label"></el-option>
                            </el-select>
                            <small class="text-danger" v-if="errors.errors.gendifu" v-text="errors.errors.gendifu[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group" :class="{'text-danger': errors.errors.diredifu}">
                            <label class="control-label">Dirección</label>
                            <el-input v-model="form.diredifu" dusk="diredifu"></el-input>
                            <small class="text-danger" v-if="errors.errors.diredifu" v-text="errors.errors.diredifu[0]"></small>
                        </div>
                    </div>
                     
                     <div class="col-md-3" >
                        <div class="form-group" :class="{'text-danger': errors.errors.fecnac}">
                            <label class="control-label">Fecha Nacimiento</label>
                            <el-date-picker v-model="form.fecnac" type="date" format="dd/MM/yyyy" :clearable="true"></el-date-picker>
                            <small class="text-danger" v-if="errors.errors.fecnac" v-text="errors.errors.fecnac[0]"></small>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group" :class="{'text-danger': errors.country_id_difu}">
                            <label class="control-label">País</label>
                            <el-select v-model="form.country_id_difu" filterable dusk="country_id_difu">
                                <el-option v-for="option in countries" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="text-danger" v-if="errors.errors.country_id_difu" v-text="errors.errors.country_id_difu[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'text-danger': errors.errorsdepartment_id_difu}">
                            <label class="control-label">Departamento</label>
                            <el-select v-model="form.department_id_difu" filterable @change="filterProvince1" popper-class="el-select-departments" >
                                <el-option v-for="option in all_departments" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="text-danger" v-if="errors.errors.department_id_difu" v-text="errors.errors.department_id_difu[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'text-danger': errors.province_id_difu}">
                            <label class="control-label">Provincia</label>
                            <el-select v-model="form.province_id_difu" filterable @change="filterDistrict1" popper-class="el-select-provinces" dusk="province_id_difu">
                                <el-option v-for="option in provinces" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="text-danger" v-if="errors.errors.province_id_difu" v-text="errors.errors.province_id_difu[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'text-danger': errors.province_id_difu}">
                            <label class="control-label">Distrito</label>
                            <el-select v-model="form.district_id_difu" filterable popper-class="el-select-districts" dusk="district_id_difu">
                                <el-option v-for="option in districts" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="text-danger" v-if="errors.errors.district_id_difu" v-text="errors.errors.district_id_difu[0]"></small>
                        </div>
                    </div>
                    <div  class="row">
                           <div class="col-md-3" >
                              <div class="form-group" :class="{'text-danger': errors.errors.fecfalle}">
                                  <label class="control-label">Fecha Fallecimiento</label>
                                  <el-date-picker v-model="form.fecfalle" type="datetime" format="dd/MM/yyyy HH:mm:ss" :clearable="true" default-time="12:00:00"></el-date-picker>
                                  <small class="text-danger" v-if="errors.errors.fecfalle" v-text="errors.errors.fecfalle[0]"></small>
                              </div>
                          </div>
                          <div class="col-md-3" >
                            <div class="form-group" :class="{'text-danger': errors.errors.acta}">
                                <label class="control-label">Acta</label>
                               <el-input v-model="form.acta" dusk="acta"></el-input>
                                <small class="text-danger" v-if="errors.errors.acta" v-text="errors.errors.acta[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2" >
                            <div class="form-group" :class="{'text-danger': errors.errors.folio}">
                                <label class="control-label">Folio</label>
                                <el-input v-model="form.folio" dusk="folio"></el-input>
                                <small class="text-danger" v-if="errors.errors.folio" v-text="errors.errors.folio[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2" >
                            <div class="form-group" :class="{'text-danger': errors.errors.libro}">
                                <label class="control-label">Libro</label>
                                <el-input v-model="form.libro" dusk="libro"></el-input>
                                <small class="text-danger" v-if="errors.errors.libro" v-text="errors.errors.libro[0]"></small>
                            </div>
                        </div>
                          <div class="col-md-2" >
                            <div class="form-group" :class="{'text-danger': errors.errors.edad}">
                                <label class="control-label">Edad</label>
                                <el-input v-model="form.edad" dusk="edad"></el-input>
                                <small class="text-danger" v-if="errors.errors.edad" v-text="errors.errors.edad[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2" >
                            <div class="form-group" :class="{'text-danger': errors.errors.estatura}">
                                <label class="control-label">Estatura</label>
                                <el-input v-model="form.estatura" dusk="estatura"></el-input>
                                <small class="text-danger" v-if="errors.errors.estatura" v-text="errors.errors.estatura[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2" >
                            <div class="form-group" :class="{'text-danger': errors.errors.color}">
                                <label class="control-label">color</label>
                                <el-input v-model="form.color" dusk="otros"></el-input>
                                <small class="text-danger" v-if="errors.errors.color" v-text="errors.errors.color[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-8" >
                            <div class="form-group" :class="{'text-danger': errors.otros}">
                                <label class="control-label">Descripcion</label>
                                <el-input v-model="form.otros" type="textarea" :rows="1"></el-input>
                                <small class="text-danger" v-if="errors.errors.otros" v-text="errors.errors.otros[0]"></small>
                            </div>
                        </div>
                          
                          <div class="col-md-3">
                              <div class="form-group" :class="{'text-danger': errors.errors.country_id_fa}">
                                  <label class="control-label">País</label>
                                  <el-select v-model="form.country_id_fa" filterable dusk="country_id_fa">
                                      <el-option v-for="option in countries" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                  </el-select>
                                  <small class="text-danger" v-if="errors.errors.country_id_fa" v-text="errors.errors.country_id_fa[0]"></small>
                              </div>
                          </div>
                          <div class="col-md-3">
                              <div class="form-group" :class="{'text-danger': errors.errors.department_id_fa}">
                                  <label class="control-label">Departamento</label>
                                  <el-select v-model="form.department_id_fa" filterable @change="filterProvince2" popper-class="el-select-departments" dusk="department_id_fa">
                                      <el-option v-for="option1 in all_departments" :key="option1.id" :value="option1.id" :label="option1.description"></el-option>
                                  </el-select>
                                  <small class="text-danger" v-if="errors.errors.department_id_fa" v-text="errors.errors.department_id_fa[0]"></small>
                              </div>
                          </div>
                          <div class="col-md-3">
                              <div class="form-group" :class="{'text-danger': errors.province_id_fa}">
                                  <label class="control-label">Provincia</label>
                                  <el-select v-model="form.province_id_fa" filterable @change="filterDistrict2" popper-class="el-select-provinces" dusk="province_id_fa">
                                      <el-option v-for="option in provinces1" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                  </el-select>
                                  <small class="text-danger" v-if="errors.errors.province_id_fa" v-text="errors.errors.province_id_fa[0]"></small>
                              </div>
                          </div> 
                          <div class="col-md-3">
                              <div class="form-group" :class="{'text-danger': errors.province_id_fa}">
                                  <label class="control-label">Distrito</label>
                                  <el-select v-model="form.district_id_fa" filterable popper-class="el-select-districts" dusk="district_id_fa">
                                      <el-option v-for="option in districts1" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                  </el-select>
                                  <small class="text-danger" v-if="errors.errors.district_id_fa" v-text="errors.errors.district_id_fa[0]"></small>
                              </div>
                          </div>
                          <div class="col-md-3">
                            <el-checkbox v-model="form.tipofinal" false-label="E" true-label="C" label="Cremacion" @change="cambiocre" border></el-checkbox>

                          </div>
                          <div class="col-md-3" v-if="form.tipofinal=='C'" >
                              <div class="form-group" :class="{'text-danger': errors.errors.feccrema}">
                                  <label class="control-label">Fecha y Hora de Cremacion</label>
                                  <el-date-picker v-model="form.feccrema" type="datetime" format="dd/MM/yyyy HH:mm:ss" :clearable="true" default-time="12:00:00"></el-date-picker>
                                  <small class="text-danger" v-if="errors.errors.feccrema" v-text="errors.errors.feccrema[0]"></small>
                              </div>
                          </div>
                           <div class="col-md-2" v-if="form.tipofinal=='C'">
                            <div class="form-group" :class="{'text-danger': errors.errors.peso}">
                                <label class="control-label">peso</label>
                                <el-input v-model="form.peso" dusk="otros"></el-input>
                                <small class="text-danger" v-if="errors.errors.peso" v-text="errors.errors.peso[0]"></small>
                            </div>
                        </div>
                        
                  </div>
                </div>
               
                </el-card>
                <!-- <el-card class="box-card">
                  <div slot="header" class="clearfix">
                    <span>Datos del Fallecimiento</span>
                    <el-button style="float: right; padding: 3px 0" type="text">Operation button</el-button>
                  </div>
                  
                </el-card> -->
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
                detdifunto:false,
                showDialogNewDifu:false,
                loading_submit: false,
                titleDialog: null,
                resource: 'difunto',
                errors: {
                  errors:{}
                },
                api_service_token:false,
                form: {},
                cara:'',
                ncaras:null,
                countries: [],
                all_departments: [],
                all_provinces: [],
                all_districts: [],
                provinces: [],
                districts: [],
                provinces1: [],
                nombrepabe:null,
                districts1: [],
                locations: [],
                pabellones:[],
                person_types: [],
                tipopabe:null,
                filas:[],
                nichocodigo:'',
                numcol:'',
                columnas1:[],
                sexo: [{
                    value: 'M',
                    label: 'Masculino'
                  }, {
                    value: 'F',
                    label: 'Femenino'
                  }],
                tiposdedocumentos: []
            }
        },
        async created() {
            await this.initForm()
            await axios.get(`/api/${this.resource}/tablas`)
                .then(response => {
                    this.api_service_token = response.data.api_service_token
                    // console.log(this.api_service_token)

                    this.countries= response.data.countries
                    this.all_departments = response.data.departments;
                    this.all_provinces = response.data.provinces;
                    this.all_districts = response.data.districts;
                    this.tiposdedocumentos = response.data.tipodocumento;
                    this.pabellones = response.data.pabellones;
                   // this.person_types = response.data.person_types;
                })
             

        },
        computed: {
            maxLength: function () {
                
                if (this.form.tipo_documento_id === 1) {
                    return 8
                }
            }
        },
        methods: {
            initForm() {
               // this.errors = {}
                this.ncaras= '';
                this.nombrepabe='';
                this.filas=[],
                this.numcol='',
                this.form = {
                    id: null,
                    tipo: this.type,
                    tipo_documento_id: 1,
                    numerodocu:'',
                    nombre_dif: '',
                    fecnac:'',
                    gendifu:'',
                    diredifu:'',
                    country_id_difu: 'PE',
                    department_id_difu: null,
                    province_id_difu: null,
                    district_id_difu: null,
                    fecfalle:'',
                    acta:'',
                    folio:'',
                    libro:'',
                    codigo:'',
                    codclie:'',
                    fechorafalle:'',
                    country_id_fa:'PE',
                    department_id_fa:null,
                    province_id_fa:null,
                    district_id_fa:null,
                    edad:'',
                    estatura:'',
                    cabello:'',
                    ojos:'',
                    color:'',
                    otros:'',
                    codpabe:null,
                    codtipopab:'',
                    codnicho:null,
                    estado:'',
                    tipofinal:'E',
                    feccrema:'',
                    peso:'',
                }
            },
            async opened() {

                if(this.external ) {
                    if(this.form.numerodoc.length === 8 ){
                        // if(this.api_service_token == false){
                        //     this.searchCustomer()
                        // }else{
                        //     await this.$eventHub.$emit('enableClickSearch')
                            
                        // }
                    }
                }

            },
            filterProvince1() {
            this.form.province_id_difu = null
            this.form.district_id_difu = null
            this.filterProvinces1()
            },
            filterProvinces1() {
            this.provinces = this.all_provinces.filter(f => {
                    return f.department_id === this.form.department_id_difu
                })
            },
            filterDistrict1() {
            this.form.district_id = null
            this.filterDistricts1()
            },
            filterDistricts1() {
                this.districts = this.all_districts.filter(f => {
                    return f.province_id === this.form.province_id_difu
                })
            },
            filterProvince2() {
            this.form.province_id_fa = null
            this.form.district_id_fa = null
            this.filterProvinces2()
            },
            filterProvinces2() {
            this.provinces1 = this.all_provinces.filter(f => {
                    return f.department_id === this.form.department_id_fa
                })
            },
            filterDistrict2() {
            this.form.district_id_fa = null
            this.filterDistricts2()
            },
            filterDistricts2() {
                this.districts1 = this.all_districts.filter(f => {
                    return f.province_id === this.form.province_id_fa
                })
            },
            cambiocre(){

            },
            
            mostrarpabe(id){
              //console.log(id);


              var inge = this.pabellones.find( nombre => nombre.id === id );
              this.ncaras= inge.caras;
              this.tipopabe = inge.tipopab;
              this.cara=1;
              this.nombrepabe=inge.nombre;
              axios.get("/api/nichosv",{params:{'id':this.form.codpabe, 'cara':'01'}}).then(({ data }) => ( this.filas=data.fila,this.numcol=data.columna,this.columna1=data.mostrar )).catch(error => { this.error = error.response });
           },
           mostrar(id){
               this.cara = id;
               axios.get("/api/nichosv",{params:{'id':this.form.codpabe, 'cara':id}}).then(({ data }) => ( this.filas=data.fila,this.numcol=data.columna,this.columna1=data.mostrar )).catch(error => { this.error = error.response });
           },
            create() {
                // console.log(this.input_person)
                if(this.external) {
                    
                    if(this.document_type_id === '03') {
                        this.form.tipo_documento_id = '1'
                    }

                    if(this.input_person) {
                        //this.form.tipo_documento_id = (this.input_person.tipo_documento_id) ? this.input_person.tipo_documento_id: this.form.tipo_documento_id
                        //this.form.numerodoc = (this.input_person.numerodoc) ? this.input_person.numerodoc:''
                    }
                }
                
                this.titleDialog = (this.recordId)? 'Editar Difunto':'Nuevo Difunto'
                
                // if(this.type === 'suppliers') {
                //     this.titleDialog = (this.recordId)? 'Editar Proveedor':'Nuevo Proveedor'
                // }
                if (this.recordId) {
                    this.detdifunto=true;
                    axios.get(`/api/${this.resource}/${this.recordId}`)
                        .then(response => {
                            this.form = response.data
                            if (response.data.contact == null) {
                                this.form.contact = ''
                            }
                            this.filterProvinces1()
                            this.filterDistricts1()
                            this.filterProvinces2()
                            this.filterDistricts2()
                            this.mostrarpabe(this.form.codpabe)
                        })
                }
            },
            seldifunto(letra,numero,estado){

               // $('#'+letra+numero ).removeClass("g-secondary");
                 let nomero=null;
                 nomero = ('00' + this.form.codpabe).slice(-3)+this.tipopabe+'0'+this.cara+letra+('00' + numero).slice(-3);
                this.nichocodigo = nomero;
                this.form.codnicho=nomero;              
               this.detdifunto=true;
               this.recordItem = null
                this.showDialogNewDifu = true
               //console.log(letra,numero);
           },
           volver(){
              this.detdifunto=false;
              this.nichocodigo = '';
           },
           codigonicho(letra,  numero){
             console.log(letra);
             this.form.codnicho='';
             let nomero = ('00' + this.form.codpabe).slice(-3)+'0'+this.cara+letra+('00' + numero).slice(-3);
             this.form.codnicho = nomero;
           },
            validateDigits(){

                const pattern_number = new RegExp('^[0-9]+$', 'i');

                


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
                                this.$eventHub.$emit('reloadDataDifuntos', response.data.id)
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
            changeIdentityDocType(){
                (this.recordId == null) ? this.setDataDefaultCustomer() : null
            },
            setDataDefaultCustomer(){

                

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
                
                if (this.form.tipo_documento_id === '1') {
                    identity_document_type_name = 'dni'
                }
                this.loading_search = true
                let response = await axios.get(`/api/buscarruc/${this.form.numerodoc}`)
                //console.log(response.data);
                if(response.data.success) {
                    console.log(response.data.nombre);
                    this.form.direccion = response.data.direccion
                    this.form.nombre_dif = response.data.nombre
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
                this.form.department_id_difu = (data.ubigeo) ? (data.ubigeo[0] != '-' ? data.ubigeo[0] : null) : null;
                this.form.province_id_difu = (data.ubigeo) ? (data.ubigeo[1] != '-' ? data.ubigeo[1] : null) : null;
                this.form.district_id_difu = (data.ubigeo) ? (data.ubigeo[2] != '-' ? data.ubigeo[2] : null) : null;
                this.form.condition = data.condicion;
                this.form.state = data.estado;

                this.filterProvinces()
                this.filterDistricts()
//                this.form.addresses[0].telephone = data.telefono;
           }
           
        }
    }
</script>
