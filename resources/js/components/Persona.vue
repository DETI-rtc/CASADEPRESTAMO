<template>
  <div class="" >
       <div class="card card-info card-outline" v-if="error.status != 403">
            <div class="card-header">
                <h3 class="card-title" style="font-size: 24px !important;"> <i class="fas fa-users mr-2" ></i> RELACION DE CLIENTES </h3>
                    <div class="card-tools">
                        <button class="btn btn-success" @click="nuevotrab"> Agregar Cliente<i class="fas fa-user-plus fa-fw"></i></button>
                        <!-- <button @click="exportExcel('testTable', 'W3C Example Table')" class="btn btn-primary pull-right">Exportar a Excel</button> -->
                        <!-- <button @click="exportPDF()" class="btn btn-danger">PDF</button> -->
                    </div>
            </div>
            <div class="card-body" ref="contento" >
                                          
                        <v-client-table :data="persona" :columns="columna" :options="options"  id="#ttrabajador">
                       
                        <div slot="nombres" slot-scope="{row}" @click="editTra(row)">
                          {{row.paterno+' '+row.materno +' '+row.nombres }}
                        </div>
                        <div slot="dni" slot-scope="{row}" @click="editTra(row)">
                          {{row.dni }}
                         </div>
                         
                         <div slot="celularo" slot-scope="{row}" @click="editTra(row)">
                          {{row.celular }}
                         </div>
                        <div slot="fec_nac" slot-scope="{row}" @click="editTra(row)">
                          {{row.fecnac|myDate }}
                         </div>
                                                  
                        <div slot="estado" slot-scope="{row}" >
                          <div v-if = "row.estado == 1">
                              <span class="badge badge-pill badge-success">Activo</span>
                          </div>
                          <div v-else>
                              <span  class="badge badge-pill badge-danger">Inactivo</span>
                          </div>
                        </div>
                       <div slot="actions" slot-scope="{ row }">
                          <div class="btn-group">
                            <button type="button" class="btn btn-success btn-sm"  @click="editTra(row)" ><i class="fa fa-edit"></i></button>
                            <button v-if="row.estado==1" type="button" class="btn btn-danger btn-sm" @click="deleteTra(row.idtrabajador)"><i class="fa fa-trash"></i></button>
                            <button v-if="row.estado==0" type="button" class="btn btn-primary btn-sm" @click="activaTra(row.idtrabajador)"><i class="fa fa-trash"></i></button>
                          </div>
                          
                        </div>
                      </v-client-table>
            </div>
        </div>
        <div v-else>
                <not-found></not-found>
        </div>
     <div class="modal bd-example-modal-xl" id="nuevacli" tabindex="-1" role="dialog" >
            <div class="modal-dialog modal-xl" role="document" style="max-width:90% !important;" >
                <div class="modal-content">
                  <form @submit.prevent="editmode ? actuaCli() : creaCli()" id="myForm">
                    <div class="modal-header blue">
                        <h4 class="modal-title blue" v-show="!editmode" id="addNewLabel">Registrar Nuevo Cliente</h4>
                        <h4 class="modal-title" v-show="editmode" id="addNewLabel">{{form.paterno}} {{form.materno}} {{form.nombres}}</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                      <!--<div v-if="error" class="row">
                            <div class="alert alert-danger alert-dismissible col-md-3" v-for="(erro , key) in error">
                                            {{erro[0]}}
                          </div>
                        </div>-->
                        <div class="row">
                                  <div class="form-group col-md-2 mb-0">
                                      <label>DNI <span style="color: red; font-weight: bold;">*</span></label>
                                      <div class="input-group ">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                                          </div>
                                          <input v-model="form.dni" type="text" class="form-control"  :class="{ 'is-invalid': error.dni  }"  maxlength="8" minlength="8" >
                                          <div class="input-group-append">
                                            <button class="btn btn-outline-success" v-on:click.prevent="buscardni" ><i class="fas fa-search"></i></button>
                                          </div>                                                                     
                                      
                                        </div>
                                        <span v-if="error.dni" class="error" >{{error.dni[0]}}</span>
                                  </div>
                                  <div class="form-group col-md-2 mb-0">
                                      <label>Apellido Paterno <span style="color: red; font-weight: bold;">*</span></label>
                                      <input v-model="form.paterno" type="text" placeholder="paterno" class="form-control" :class="{ 'is-invalid': error.paterno }">
                                      <span v-if="error.paterno" class="error" >{{error.paterno[0]}}</span>
                                  </div>
                                  <div class="form-group col-md-2 mb-0">
                                      <label>Apellido Materno <span style="color: red; font-weight: bold;">*</span></label>
                                      <input v-model="form.materno" type="text" placeholder="Materno" class="form-control" :class="{ 'is-invalid': error.materno }">
                                    <span v-if="error.materno" class="error" >{{error.materno[0]}}</span>
                                  </div>
                                  <div class="form-group col-md-2 mb-0">
                                      <label>Nombres <span style="color: red; font-weight: bold;">*</span></label>
                                      <input v-model="form.nombres" type="text" placeholder="Nombres" class="form-control" :class="{ 'is-invalid': error.nombres }">
                                      <span v-if="error.nombres" class="error" >{{error.nombres[0]}}</span>
                                  </div>
                                  <div class="form-group col-md-2 mb-0">
                                      <label>Fecha de Nacimiento <span style="color: red; font-weight: bold;">*</span></label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-birthday-cake"></i></div>
                                          </div>
                                          <input v-model="form.fec_nac" type="date" class="form-control" >
                                        
                                        </div>
                                  </div>

                                  <div class="form-group col-md-2 mb-0">
                                      <label>Distrito <span style="color: red; font-weight: bold;">*</span></label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-birthday-cake"></i></div>
                                          </div>
                                          <input v-model="form.distrito" type="text" class="form-control" >
                                        
                                        </div>
                                  </div>

                                  <div class="form-group col-md-2 mb-0">
                                      <label>Provincia <span style="color: red; font-weight: bold;">*</span></label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-birthday-cake"></i></div>
                                          </div>
                                          <input v-model="form.provincia" type="text" class="form-control" >
                                        
                                        </div>
                                  </div>

                                  <div class="form-group col-md-2 mb-0">
                                      <label>Departamento <span style="color: red; font-weight: bold;">*</span></label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-birthday-cake"></i></div>
                                          </div>
                                          <input v-model="form.departamento" type="text" class="form-control" >
                                        
                                        </div>
                                  </div>
                                  <div class="form-group col-md-2 mb-0">
                                      <label>Sexo</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-venus-mars"></i></div>
                                          </div>
                                          <select v-model="form.sexo" class="form-control">
                                              <option value = "F">Femenino</option>
                                              <option value = "M" >Masculino</option>
                                            </select>
                                            
                                        </div>
                                  </div>
                                  <div class="form-group col-md-2 mb-0">
                                      <label>Estado Civil</label>
                                      <div class="input-group mb-2">
                                          <!-- <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-diagnoses"></i></div>
                                          </div> -->
                                          <el-select v-model="form.idestadocivil" filterable placeholder="Select" class="w-100">
                                                <el-option  v-for="item in delista" :key="item.id" :label="item.nomdelista" :value="item.id" v-if="item.idlista == 4" name="idestadocivil" id="idestadocivil" ></el-option>
                                            </el-select>
                                      
                                        </div>
                                  </div>
                                  
                                  <div class="form-group col-md-2 mb-0">
                                      <label>Telefono</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-phone-volume"></i></div>
                                          </div>
                                          <input v-model="form.celular" type="text" name="telefono" placeholder="999999999" class="form-control"  maxlength="9">
                                        
                                      </div>
                                  </div>
                                  <div class="form-group col-md-2 mb-0">
                                      <label>Email</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-envelope-open-text"></i></div>
                                          </div>
                                          <input v-model="form.email" type="text" name="email"
                                          placeholder="nombre@compani.com"
                                          class="form-control"  style="font-size: small;">
                                          
                                        </div>
                                                                  
                                  </div>
                                 
                                  <div class="form-group col-md-2 mb-0">
                                      <label>Direccion</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-road"></i></div>
                                          </div>
                                          <input v-model="form.direccion" type="text" name="direc" class="form-control"  style="font-size: small;">
                                        
                                      </div>   
                                  </div>

                                   <div class="form-group col-md-2">
                                      <label>N° Domicilio <span style="color: red; font-weight: bold;">*</span></label>
                                      <input type="text" class="form-control" v-model="form.n_domicilio">
                                  </div>
                                 
                                  
                                  <div class="form-group col-md-2 mb-0">
                                      <label>Referencia</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-street-view"></i></div>
                                          </div>
                                          <input type="text" v-model="form.referencia" class="form-control">
                                        
                                        </div>
                                  </div>
                                  

                                  

                                   <div class="form-group col-md-2">
                                      <label>Tipo Vivienda <span style="color: red; font-weight: bold;">*</span></label>
                                      <!-- <input type="text" class="form-control" v-model="form.tipo_vivienda"> -->
                                      
                                      <el-select v-model="form.tipo_vivienda" filterable placeholder="Select" class="w-100">
                                        <el-option  v-for="item in tipo_vivienda" :key="item.label" :label="item.label" :value="item.label" id="label" ></el-option>
                                      </el-select>
                                  </div>

                                   <div class="form-group col-md-2">
                                      <label>Nivel Instruccion <span style="color: red; font-weight: bold;">*</span></label>
                                      <!-- <input type="text" class="form-control" v-model="form.nivel_instruccion"> -->

                                      <el-select v-model="form.nivel_instruccion" filterable placeholder="Select" class="w-100">
                                        <el-option  v-for="item in nivel_instruccion" :key="item.label" :label="item.label" :value="item.label" id="label" ></el-option>
                                      </el-select>
                                  </div>

                                  <div class="form-group col-md-2">
                                      <label>Profesion <span style="color: red; font-weight: bold;">*</span></label>
                                      <input type="text" class="form-control" v-model="form.ocupacion">
                                  </div>

                                  

                                   <div class="form-group col-md-2">
                                      <label>Tipo Ocupacion <span style="color: red; font-weight: bold;">*</span></label>
                                      <!-- <input type="text" class="form-control" v-model="form.tipo_ocupacion"> -->
                                      
                                      <el-select v-model="form.tipo_ocupacion" filterable placeholder="Select" class="w-100">
                                        <el-option  v-for="item in tipo_ocupacion" :key="item.label" :label="item.label" :value="item.label" id="label" ></el-option>
                                      </el-select>
                                  </div>
                                   <div class="form-group col-md-2">
                                      <label>Fecha Ingreso Actividades <span style="color: red; font-weight: bold;">*</span></label>
                                      <!-- <input type="text" class="form-control" v-model="form.f_ingreso_actividades"> -->
                                      <el-date-picker type="date" placeholder="Seleccione la fecha" v-model="form.f_ingreso_actividades" 
                                        style="width: 100%;"  value-format="yyyy-MM-dd"  format="dd/MM/yyyy" ></el-date-picker>
                                  </div>

                                   <div class="form-group col-md-2">
                                      <label>Fecha Fin Actividades <span style="color: red; font-weight: bold;">*</span></label>
                                      <!-- <input type="text" class="form-control" v-model="form.f_fin_actividades"> -->
                                      <el-date-picker type="date" placeholder="Seleccione la fecha" v-model="form.f_fin_actividades" 
                                        style="width: 100%;"  value-format="yyyy-MM-dd"  format="dd/MM/yyyy" ></el-date-picker>
                                  </div>

                                  <div class="form-group col-md-2">
                                      <label>Cuenta de Banco <span style="color: red; font-weight: bold;">*</span></label>
                                      <input type="text" class="form-control" v-model="form.cuenta_scotiabank">
                                  </div>

                                  <div class="form-group col-md-2">
                                      <label>Banco <span style="color: red; font-weight: bold;">*</span></label>
                                      <!-- <input type="text" class="form-control" v-model="form.tipo_vivienda"> -->
                                      
                                      <el-select v-model="form.cuenta_banco" filterable placeholder="Select" class="w-100">
                                        <el-option  v-for="item in bancos" :key="item.label" :label="item.label" :value="item.label" id="label" ></el-option>
                                      </el-select>
                                  </div>


                                  <div class="form-group col-md-2">
                                      <label>Cuenta CCI <span style="color: red; font-weight: bold;">*</span></label>
                                      <input type="text" class="form-control" v-model="form.cci">
                                  </div>

                                  <div class="form-group col-md-2">
                                      <label>Banco CCI<span style="color: red; font-weight: bold;">*</span></label>
                                      <!-- <input type="text" class="form-control" v-model="form.tipo_vivienda"> -->
                                      
                                      <el-select v-model="form.cci_banco" filterable placeholder="Select" class="w-100">
                                        <el-option  v-for="item in bancos" :key="item.label" :label="item.label" :value="item.label" id="label" ></el-option>
                                      </el-select>
                                  </div>

                                   <!-- <div class="form-group col-md-2">
                                      <label>Profesion <span style="color: red; font-weight: bold;">*</span></label>
                                      <input type="text" class="form-control" v-model="form.profesion">
                                  </div> -->
                                  
                            </div>
                            
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <div class="card-title">
                                            Datos de Empresa
                                        </div>
                                    </div>
                                    <div class="car-body">
                                        <div class="row m-2" >
                                             <div class="form-group col-md-4">
                                              <label>Empresa</label>
                                               <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                                <v-select label="razonsocial"  :options="empresa" :reduce="razonsocial => razonsocial.id" v-model="form.idempresa" @input="setSelected($event)" class="w-75"></v-select>
                                                  <div class="input-group-append">
                                                        <button class="btn btn-outline-success" v-on:click.prevent="loadUsers" ><i class="fas fa-sync-alt"></i> Recargar</button>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3" >
                                              <label>Sector de la Empresa </label>
                                              <input type="text" v-model="form.sector" class="form-control text-center" disabled="true">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Cargo Actual <span style="color: red; font-weight: bold;">*</span></label>
                                                <input type="text" class="form-control" v-model="form.cargo">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Area Actual <span style="color: red; font-weight: bold;">*</span></label>
                                                <input type="text" class="form-control" v-model="form.area">
                                            </div>
                                            <div class="form-group col-md-3">
                                              <label>Modalidad del Trabajador <span style="color: red; font-weight: bold;">*</span></label>
                                              <div class="input-group ">
                                                <el-select v-model="form.tipomodalidad" filterable placeholder="Select" class="w-95">
                                                  <el-option  v-for="item in delista" :key="item.id" :label="item.nomdelista" :value="item.id" name="tipomodalidad" id="tipomodalidad" ></el-option>
                                                </el-select>
                                              </div>
                                            </div>

                                            <!-- <div class="form-group col-md-2">
                                                <label>CCI <span style="color: red; font-weight: bold;">*</span></label>
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control" v-model="form.cci">
                                                    <span v-if="error.cci" class="error" >{{error.cci[0]}}</span>
                                                </div>
                                            </div>  -->
                                            
                                            <div class="form-group col-md-2">
                                                <label>Ingreso Bruto <span style="color: red; font-weight: bold;">*</span></label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-money-bill-alt"></i></div>
                                                    </div>
                                                    <vue-autonumeric :options="''" v-model="form.ingre_bru" class="form-control text-right"  :class="{ 'is-invalid': error.ingre_bru }" ></vue-autonumeric>
                                                    <span v-if="error.ingre_bru" class="error" >{{error.ingre_bru[0]}}</span>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>

                                </div>
                                               
                            
                    </div> 
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
                      <template v-if="!loadingModal">
                        <button v-show="editmode" type="submit" class="btn btn-success" :disabled="loadingModal">Actualizar</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary" :disabled="loadingModal">Registrar</button>
                      </template>
                      <button class="btn btn-primary" :class="{ 'btn-success': editmode  }"  disabled v-else>
                        <div class="spinner-border spinner-border-sm" role="status"></div> Cargando
                      </button>
                    </div>
                  </form>
                </div>
            </div>
      </div>

      <div class="modal fade bd-example-modal-xl" id="nuevaemp" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content" :class="!editmode1 ? 'card card-primary' : 'card card-success'">
                <div class="card-header">
                    <h5 class="card-title" v-show="!editmode1" id="addNewLabel">Nueva Empresa</h5>
                    <h5 class="card-title" v-show="editmode1" id="addNewLabel">Actualizar Empresa</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode1 ? actuaEmp() : creaEmp()" autocomplete="off">
                <div class="modal-body">
                  
                    <div class="row">
                                   
                                  <div class="form-group col-4">
                                      <label>RUC <span style="color: red; font-weight: bold;">*</span></label>
                                      <div class="input-group ">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                                          </div>
                                          <input v-model="form1.ruc" type="text" class="form-control"  :class="{ 'is-invalid': error.ruc  }"  maxlength="11" >
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-success" v-on:click.prevent="buscarruc" ><i class="fas fa-search"></i></button>
                                          </div>                                                                     
                                      
                                        </div>
                                        
                                  </div>
                                  <loading :active.sync="isLoading" 
                                    :can-cancel = "true" 
                                    :is-full-page ="fullPage"
                                    :loader = "dots"
                                    :color= "color">                      
                                    </loading>
                                  <div class="form-group col-8">
                                      <label>Razon Social <span style="color: red; font-weight: bold;">*</span></label>
                                      <input v-model="form1.razonsocial" type="text" placeholder="Empresa" class="form-control" :class="{ 'is-invalid': error.razonsocial }">
                                      <span v-if="error.razonsocial" class="error" >{{error.razonsocial[0]}}</span>
                                  </div>
                                  <div class="form-group col-8">
                                      <label>Direccion</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-road"></i></div>
                                          </div>
                                          <input v-model="form1.direccion" type="text" name="direc" class="form-control"  >
                                        
                                      </div>   
                                  </div>
                                  <div class="form-group col-4">
                                      <label>Telefono</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-phone-volume"></i></div>
                                          </div>
                                          <input v-model="form1.telefono" type="text" name="telefono" placeholder="999999999" class="form-control"  maxlength="9">
                                        
                                      </div>
                                  </div>
                                 
                                  <div class="form-group col-6">
                                      <label>Contacto</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-road"></i></div>
                                          </div>
                                          <input v-model="form1.contacto" type="text" name="direc" class="form-control"  >
                                        
                                      </div>   
                                  </div>
                                  <div class="form-group col-3">
                                      <label>Telefono  Contacto</label>
                                      <div class="input-group mb-2">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-phone-volume"></i></div>
                                          </div>
                                          <input v-model="form1.telefo_cont" type="text" name="telefono" placeholder="999999999" class="form-control"  maxlength="9">
                                        
                                      </div>
                                  </div>
                                  

                                  <div class="form-group col-3">
                                            <label>Sector de la Empresa</label>
                                            <div class="input-group ">
                                                    <el-select v-model="form1.id_tipo" filterable placeholder="Select" width="100%" @change="cambiotipo">
                                                        <el-option  v-for="item in delista" :key="item.id" :label="item.nomdelista" :value="item.id" v-if="item.idlista == 1" name="idlista" id="idlista" ></el-option>
                                                    </el-select>
                                            </div>
                                </div>

                        
                    </div>        
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button v-show="editmode1" type="button" @click="actuaEmp" class="btn btn-success">Actualizar</button>
                        <button v-show="!editmode1" type="button" @click="creaEmp" class="btn btn-primary">Crear</button>
                </div>

                </form>

                </div>
            </div>
      </div>
 
  </div>
</template>

<script>
  //import jsPDF from 'jspdf'
  //import 'jspdf-autotable'
  //import html2canvas from "html2canvas"
  import VueAutonumeric from 'vue-autonumeric'
  //import { saveAs } from 'file-saver'
  import Loading from 'vue-loading-overlay'
  import moment from 'moment'
  //import Multiselect from 'vue-multiselect'
  //import { ModelListSelect } from 'vue-search-select'
    export default {
        components:{
            Loading
        },
        data() {
            return {
              loadingModal :false,
              bancos :[
                {label: 'Scotiabank'},
                {label: 'BCP'},
                {label: 'BBVA'},
                {label: 'Interbank'},
              ],
              tipo_vivienda :[
                {label: 'Propia'},
                {label: 'Propia Financiada'},
                {label: 'Alquilada'},
                {label: 'Familiar'},
              ],
              nivel_instruccion :[
                {label: 'Primaria'},
                {label: 'Secundaria'},
                {label: 'Técnica'},
                {label: 'Superior'},
                {label: 'Post Grado'},
              ],
              tipo_ocupacion :[
                {label: 'Dependiente'},
                {label: 'Jubilado'},
                {label: 'Comerciante Independiente'},
                {label: 'Rentista'},
                {label: 'Profesional Independiente'},
              ],
                isLoading: false,
                fullPage: true,
                dots:'dots',
                color:'#dc3545',
                rotarmode: false,
                modalidad:false,
                editmode: false,
                editmode1: false,
                altamode:false,
                contratado:true,
                cafae:true,
                miembro:true,
                error:{},
                item:{
                  idocuopacion:'',
                  descripcion:'',
                },
                delista:[],
                columexcel:{},
                descuentos:[],
                descuentos1:[],
                descuentos2:[],
                descuentos3:[],
                ingresos:[],
                laboral:[],
                banco:[],
                cese:[],
                escalafon:[],
                formativa:[],
                menpago:[],
                modpago:[],
                vdni:false,
                fhoy: moment(new Date()).local().format('YYYY-MM-DD'),
                vmaterno:false,
                vnombres:false,
                vingre_bru:false,
                nivedu:[],
                ocupacion:[],
                situatra:[],
                tipotra:[],
                tipovia:[],
                ubigeo:[],
                zona:[],
                persona : [],
                pension : [],
                oficina : [],
                contrato : [],
                cargo : [],
                comitipo : {},
                compa : {},
                empresa : [],
                motivo:'',
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
                          filterable: ['full_name','nombres','empresa'],
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
                        templates: {
                                empresa(h, row){
                                return row.empresa.razonsocial;
                                },
                               
                          },
                }, 
                form1: new Form({
                        id:'',
                        ruc:'',
                        razonsocial:'',
                        direccion:'',
                        telefono:'',
                        contacto:'',
                        telefo_cont:'',
                        id_tipo:'',
                        sector:''
                }),           
                form: new Form({
                    id:'',
                    dni:'',
                    cci:'',
                    cci_banco:'',
                    cuenta_scotiabank:'',
                    cuenta_banco:'',
                    nombres:'',
                    paterno:'',
                    materno:'',
                    fec_nac:'',
                    sexo:'',
                    idestadocivil:'',
                    celular:'',
                    direccion:'',                    
                    email:'',
                    referencia:'',
                    ocupacion:'',
                    idempresa:'',
                    empresa:'',
                    sector:'',
                    tipomodalidad:'',
                    ingre_bru:'',
                    n_domicilio : '',
                    tipo_vivienda : '',
                    nivel_instruccion : '',
                    tipo_ocupacion : '',
                    f_ingreso_actividades : '',
                    f_fin_actividades : '',
                    modalidad_contratacion : '',
                    profesion : '',
                    cargo : '',
                    area:'',
                    distrito:'',
                    provincia:'',
                    departamento:'',

                }),
                fbaja: new Form({
                  fecha:moment(new Date()).local().format('YYYY-MM-DD'),
                  motivo:'',
                  idtra:'',
                  tipo:'B'
                })
            }
        },
       
        methods: {
            actuaEmp(){

            },
            cambiotipo(value){
              var inge = this.delista.find( nombre => nombre.id === value );
              this.form1.sector = inge.nomdelista;
              //console.log(inge);
            },
            creaEmp(){
              this.form1.post('api/empresas')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevaemp').modal('hide');

                    Swal.fire(
                        'Creado',
                        'Se Creo la Empresa',
                        'success'
                        )
                    

                })
                .catch((error)=>{
                     this.error = error.response.data.errors;
                })

            },
            nuevaemp(){
                this.editmode1 = false;
                this.form1.reset(); 
                $('#nuevaemp').modal('show');
            },
            buscardni(){
              if(this.form.dni == ''){
                Swal.fire('DNI',
                          'Ingrese el DNI',
                          'error'
                          )
              }else{
                this.isLoading = true;
                const loading = this.$loading({
                  lock: true,
                  text: 'Buscando información',
                  spinner: 'el-icon-loading',
                  background: 'rgba(255, 255, 255,0.8)'
                });
                axios.get("api/buscardni",{params:{'dni':this.form.dni}})
                .then( ({data})  => {
                  this.form.nombres = data.data.nombres;
                  this.form.paterno = data.data.apellido_paterno;
                  this.form.materno = data.data.apellido_materno;
                  this.form.fec_nac= data.data.fecha_nacimiento;
                  this.isLoading = false;
                  loading.close();

                })
                .catch(error => { 
                  this.error = error.response;
                  this.isLoading = false;
                  loading.close();
                });
               //axios({url: 'https://api.reniec.cloud/dni/'+this.form.dni, method: 'get', headers: {'Content-type': 'text/html; charset=UTF-8'}}).then(({ data }) => (console.log(data) )).catch(error => { this.error = error.response });
               //const instance = axios.create({
                //      baseURL: 'https://api.reniec.cloud/dni',
                  //    headers: {'Access-Control-Allow-Origin': '*'}                    
                   // });
                  //  instance.get('/40679669').then(res => console.log(res));
               //   axios.get('https://api.reniec.cloud/dni/40679669').then(function (response) {                          console.log(response.data);             });
              }

            },
            setSelected(data){
              var inge = this.empresa.find( nombre => nombre.id === data );
              this.form.sector = inge.sector;
              this.form.empresa = inge.razonsocial;

              axios.get('api/listarsector/'+data)
              .then(
                ({data})=>{
                  this.delista=data;
                }
                
              ).catch(err=>console.log(err));
              // axios.get('api/delista/'+ data)
              // .then(
              //   data=> { console.log(data);}
              // ).catch(
              //   err=>console.log(err)
              // )
            },
           nuevotrab(){
                this.editmode = false;
                this.form.reset();
               
                $('#nuevacli').modal('show');
           },
            deleteTra(){

            },
            activaTra(){

            },
            editTra(cliente){
                this.editmode = true;
                this.form.reset();
                this.form.fill(cliente);
                $('#nuevacli').modal('show');
            },
             loadUsers(){
                 axios.get("api/clientes").then(({ data }) => (this.persona = data)).catch(error => { this.error = error.response });
                //axios.get("api/cLientes").then(({ data }) => (this.relaciondi = data)).catch(error => { this.error = error.response });
                axios.get("api/delista").then(({ data }) => (this.delista = data)).catch(error => { this.error = error.response });
                axios.get("api/empresas").then(({ data }) => (this.empresa = data)).catch(error => { 
                  this.error = error.response;
                  console.log(error);
                  });

            },
            creaCli(){
                this.form.post('api/clientes')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#nuevacli').modal('hide');

                    Toast.fire({
                        type: 'success',
                        title: 'Creado Satisfactoriamente'
                        })
                    

                })
                .catch((error)=>{
                     this.error = error.response.data.errors;
                })

            },
            actuaCli(){
              this.loadingModal = true;
              this.form.put('api/clientes/'+this.form.id)
              .then(({data}) => {
                this.loadingModal = false;
                console.log(data);
                // Fire.$emit('AfterCreate');
                $('#nuevacli').modal('hide');
                this.$message({
                    showClose: true,
                    message: 'Actualizado correctamente.',
                    type: 'success'
                });

                    
              }).catch((err) => {
                this.loadingModal = false;
                console.log(err);
              });
            }         
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
<style type="text/css">
  .VueTables__table tbody {
  font-size: 12px;
}
  .VueTables__search-field {
    display: inline-flex;
  }
.VueTables__limit-field{
  display: inline-flex;
}
.error{
  width: 100%;
    margin-top: .25rem;
    font-size: 80%;
    color: #dc3545;
}
.table-hover tbody tr:hover {
  
  background-color: rgb(212 222 63 / 52%);
}
.el-select {
    /*width: 100%;*/
}
</style>
