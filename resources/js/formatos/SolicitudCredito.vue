<template>
<div class="card">
    <div class="card-header no-print">
        <div class="col-md-6 pt-5">
        <button class="btn btn-success btn-sm no-print" @click="imprimir">Imprimir</button>
        </div>
    </div>
<div class="card-body" id="printMe">
    <div class="general">
         <h4 class="first"></h4>
        <div class="insert"></div>
        <table style="width:100%;">
        <tr>
            <td style="text-align: left;"><img :src="'/storage/logo.png'" alt="SBH" style="width:100px;"></td>
            <td></td>
            <td style="text-align: right;"><img :src="'/storage/sbhlogo.png'" alt="SBH" style="width:100px;"></td>
        </tr>
    </table>
    <div class="text-center">
        <!-- <button class="btn btn-success btn-sm no-print" @click="imprimir">Imprimir</button> -->
        <h3 class="text-center" style="margin-bottom:-5px;">SOLICITUD CREDITO</h3>
        <!-- <span style="margin-top:-20px;">LLENAR SOLICITUD CON LETRA IMPRENTA Y LEGIBLE</span> -->
        <small>LLENAR SOLICITUD CON LETRA IMPRENTA Y LEGIBLE</small>
    </div>

    <table class="w-100 mt-2 tbl-primero">
        <tr>
            <td class="label-inpt" style="width:20%;">Oficina</td>
            <td class="label-inpt" style="width:20%;">Ejecutivo</td>
            <td class="label-inpt" style="width:20%;">Tipo Producto</td>
            <td class="label-inpt" style="width:20%;">Tipo Cliente</td>
            <td class="label-inpt" style="width:20%;">Fecha</td>
        </tr>
        <tr>
            <td style="padding-bottom:10px;"><input type="text" disabled class="w-100"></td>
            <td style="padding-bottom:10px;"><input type="text" disabled class="w-100"></td>
            <td style="padding-bottom:10px;"><input type="text" disabled class="w-100" v-model="credito.tipo"></td>
            <td style="padding-bottom:10px;"><input type="text" class="w-100" v-model="credito.tipo_cliente"></td>
            <td style="padding-bottom:10px;"><input type="text" disabled class="w-100" v-model="credito.fecha_d"></td>
        </tr>
        <!-- <tr>
            <td colspan="5" class="line">Tipo de Cliente: <strong>"{{ "tipo de client "}}"</strong> </td>
        </tr> -->
        <tr>
            <td class="separator" colspan="5" style="background-color:#E7E7E7 !important; "><strong>DATOS DEL CLIENTE</strong></td>
        </tr>
        <tr>
            <td class="line label-inpt">Tipo Documento: <strong>{{ "DNI" }}</strong></td>
            <td class="line label-inpt">Fecha Nacimiento</td>
            <td class="line label-inpt">Sexo</td>
            <td class="line label-inpt"></td>
            <td class="line label-inpt">Estado Civil</td>                
        </tr>
            <tr>
            <td><input type="text" disabled class="w-100" v-model="persona.dni"></td>
            <td><input type="text" disabled class="w-100" v-model="persona.fec_nac"></td>
            <td><input type="text" disabled class="w-100" v-model="persona.sexo"></td>
            <td></td>
            <td><input type="text" disabled class="w-100" v-model="persona.estadocivil"></td>
        </tr>
        <tr>
            <td class="line label-inpt">Ap. Paterno</td>
            <td class="line label-inpt">Ap. Materno</td>
            <td class="line label-inpt" colspan="2">Nombres</td>
            <td class="line label-inpt">Correo Electronico</td>                
        </tr>
        <tr>
            <td><input type="text" disabled class="w-100" v-model="persona.paterno"></td>
            <td><input type="text" disabled class="w-100" v-model="persona.materno"></td>
            <td colspan="2"><input type="text" disabled class="w-100" v-model="persona.nombres"></td>
            <td><input type="text" disabled class="w-100" v-model="persona.email"></td>
        </tr>
        <tr>
            <td class="line label-inpt">Domicilio : Nombre de Ca/Av/Jr</td>
            <td class="line label-inpt">N°/Mz</td>
            <td class="line label-inpt">L1/Edificio Int/Dpto</td>
            <td class="line label-inpt" colspan="2">Nombre de Urbanizacion/Residencial/Unidad Vecina</td>                
        </tr>
        <tr>
            <td><input type="text" class="w-100" v-model="persona.direccion"></td>
            <td><input type="text" class="w-100" v-model="persona.n_domicilio"></td>
            <td><input type="text" class="w-50"><input type="text" class="w-50"></td>
            <td colspan="2"><input type="text" class="w-100"></td>
        </tr>
        <tr>
            <td class="line label-inpt">Distrito</td>
            <td class="line label-inpt">Provincia</td>
            <td class="line label-inpt">Region</td>
            <td class="line label-inpt">Cod. Ciudad/Telefono Casa</td>                
            <td class="line label-inpt">Telefono Celular</td>                
        </tr>
        <tr>
            <td><input type="text" disabled class="w-100" v-model="persona.distrito"></td>
            <td><input type="text" disabled class="w-100" v-model="persona.provincia"></td>
            <td><input type="text" disabled class="w-100" v-model="persona.departamento"></td>
            <td><input type="text" disabled class="w-100" ></td>
            <td><input type="text" disabled class="w-100" v-model="persona.celular"></td>
        </tr>
        <tr>
            <td class="line label-inpt">Tipo de Vivienda</td>
            <td class="line label-inpt">Años</td>
            <td class="line label-inpt">Meses</td>
            <td class="line label-inpt">Nivel de instruccion</td>     
            <td></td>           
        </tr>
        <tr>
            <td style="padding-bottom:10px;"><input type="text"  class="w-100" v-model="persona.tipo_vivienda"></td>
            <td style="padding-bottom:10px;"><input type="text" class="w-100"></td>
            <td style="padding-bottom:10px;"><input type="text" class="w-100"></td>
            <td style="padding-bottom:10px;"><input type="text" disabled class="w-100" v-model="persona.nivel_instruccion"></td>
            <td style="padding-bottom:10px;"></td>
        </tr>

        <tr>
            <td class="separator" colspan="5" style="background-color:#E7E7E7;"><strong>DATOS LABORALES</strong></td>
        </tr>
        <tr>
            <td class="line label-inpt">Ocupación</td>
            <td class="line label-inpt">Razon Social</td>
            <td class="line label-inpt">RUC</td>
            <td class="line label-inpt">Giro o Actividad</td>                
            <td class="line label-inpt">F. Ingreso/Inicio Actividad</td>                
        </tr>
        <tr>
            <td><input type="text"  class="w-100" v-model="persona.tipo_ocupacion"></td>
            <td><input type="text"  class="w-100" v-model="empresa.razonsocial"></td>
            <td><input type="text"  class="w-100" v-model="empresa.ruc"></td>
            <td><input type="text"  class="w-100" v-model="empresa.sector"></td>
            <td><input type="text"  class="w-100" v-model="persona.f_ingreso_actividades"></td>
        </tr>
        <tr>
            <td class="line label-inpt">Fin de Contrato</td>
            <td class="line label-inpt">Profesión</td>
            <td class="line label-inpt">Cargo Actual</td>
            <td class="line label-inpt">Modalidad de contratacion</td>                
            <td></td>                
        </tr>
        <tr>
            <td><input type="text" disabled class="w-100" v-model="persona.f_fin_actividades"></td>
            <td><input type="text" disabled class="w-100" v-model="persona.ocupacion"></td>
            <td><input type="text" disabled class="w-100" v-model="persona.cargo"></td>
            <td><input type="text" disabled class="w-100" v-model="credito.tipo_contrato"></td>
            <td></td>
        </tr>
        <tr>
            <td class="line label-inpt" colspan="2">Dirección</td>
            <td class="line label-inpt">Distrito</td>
            <td class="line label-inpt">Provincia</td>
            <td class="line label-inpt">Departamento</td>                
        </tr>
        <tr>
            <td colspan="2"><input type="text" class="w-100" v-model="empresa.direccion"></td>
            <td><input type="text"  class="w-100" v-model="persona.localidad"></td>
            <td><input type="text"  class="w-100"></td>
            <td><input type="text"  class="w-100" ></td>
        </tr>
        <tr>
            <td class="line label-inpt">Telefono </td>
            <td class="line label-inpt">Tipo Ingreso</td>
            <td class="line label-inpt">Ingreso Bruto Mes S/.</td>
            <td class="line label-inpt">Otros Ingresos</td>                
            <td class="line label-inpt">Correo</td>                
        </tr>
        <tr>
            <td style="padding-bottom:10px;"><input type="text" class="w-100" v-model="persona.telefono"></td>
            <td style="padding-bottom:10px;"><input type="text" disabled class="w-100" value="FIJO"></td>
            <td style="padding-bottom:10px;"><input type="text" disabled class="w-100" v-model="persona.ingre_bru"></td>
            <td style="padding-bottom:10px;"><input type="text" disabled class="w-100" v-model="persona.ingre_1"></td>
            <td style="padding-bottom:10px;"><input type="text" disabled class="w-100"></td>
        </tr>

        <tr>
            <td class="separator" colspan="5" style="background-color:#E7E7E7;"><strong>DATOS DEL CONYUGUE</strong></td>
        </tr>

        <tr>
            <td class="line label-inpt">Tipo documento</td>
            <td class="line label-inpt">Número</td>
            <td class="line label-inpt">Fecha Nacimiento</td>
            <td class="line label-inpt">Telefono</td>                
            <td class="line label-inpt"></td>                
        </tr>
        <tr>
            <td><input type="text" class="w-100"></td>
            <td><input type="text" class="w-100"></td>
            <td><input type="text" class="w-100"></td>
            <td><input type="text" class="w-100"></td>
            <td></td>
        </tr>

        <tr>
            <td class="line label-inpt">Ap. Paterno</td>
            <td class="line label-inpt">Ap. Materno</td>
            <td class="line label-inpt">Nombres</td>
            <td class="line label-inpt">Email</td>                
            <td class="line label-inpt"></td>                
        </tr>
        <tr>
            <td><input type="text" class="w-100"></td>
            <td><input type="text" class="w-100"></td>
            <td><input type="text" class="w-100"></td>
            <td><input type="text" class="w-100"></td>
            <td></td>
        </tr>

        <tr>
            <td class="separator" colspan="5" style="background-color:#E7E7E7;"><strong>DATOS DEL CREDITO</strong></td>
        </tr>

        <tr>
            <td class="line label-inpt">Monto a Financiar</td>
            <td class="line label-inpt">Plazo</td>
            <td class="line label-inpt">Fecha de primer Vencimiento</td>
            <td class="line label-inpt">Nro Cuenta</td>                
            <td class="line label-inpt"></td>                
        </tr>
        <tr>
            <td><input type="text" disabled class="w-100" v-model="cronograma.mon_financiado"></td>
            <td><input type="text" disabled class="w-100" v-model="cronograma.num_cuotas"></td>
            <td><input type="date" disabled class="w-100" v-model="cuota.fecha_ven"></td>
            <td><input type="text" disabled class="w-100"></td>
            <td></td>
        </tr>


        <tr>
            <td class="separator" colspan="5" style="background-color:#E7E7E7;"><strong>DATOS DEL AVAL O FIADOR</strong></td>
        </tr>

        <tr>
            <td class="line label-inpt">DNI ó CE</td>
            <td class="line label-inpt">Fecha Nacimiento</td>
            <td class="line label-inpt">Telefono</td>
            <td class="line label-inpt">Ap. Paterno Conyugue/Refe</td>
            <td></td>             
        </tr>
        
        <tr>
            <td><input type="text" disabled class="w-100"></td>
            <td><input type="text" disabled class="w-100"></td>
            <td><input type="text" disabled class="w-100"></td>
            <td><input type="text" disabled class="w-100"></td>
            <td></td>
        </tr>

        <tr>
            <td class="line label-inpt">Ap. Materno Conyugue/Refe</td>                
            <td class="line label-inpt">Nombres Conyugue/Refe</td>             
            <td class="line label-inpt">E-mail</td>             
        </tr>
        <tr>
            <td><input type="text" disabled class="w-100"></td>
            <td><input type="text" disabled class="w-100"></td>
            <td><input type="text" disabled class="w-100"></td>
        </tr>

        <tr>
            <td class="separator" colspan="5" style="background-color:#E7E7E7;"><strong>DECLARACIÓN JURADA</strong></td>
        </tr>
        <tr>
            <td colspan="5" class="text-justify" style="font-size:11px;">
                <p style="margin-bottom: 0;">(i) Declaro bajo juramento NO TENER PROCESOS JUDICIALES VIGENTES que afecten mis ingresos mensuales.</p>
                <p style="margin-bottom: 0;">(ii) Declaro haber sido informado sobre los términos y condiciones del contrato y tarifario, habiendo absuelto el ejecutivo las dudas que se me presentaron.
                    Asimismo, manifiesto mi voluntad para recibir un ejemplar de la documentación contractual siguiente: Contrato de Crédito, en la dirección de opreo
                    electrónico consignado en la presente solicitud. Finalmente, La Casa no tendrá responsabilidad por la imposibilidad de envio y/o recepción de l
                    documentos contractuales en caso de inexistencia, invalidez o inactividad del correo electrónico consignado</p>
            </td>
        </tr>
        <tr>
            <td class="separator" colspan="5" style="background-color:#E7E7E7;"><strong>TRATAMIENTOS DE DATOS PERSONALES</strong></td>
        </tr>
        <tr>
            <td colspan="5" class="text-justify" style="font-size:11px;">
                <p style="margin-bottom: 0;">El cliente otorga a la Casa de Préstamos (en adelante la Casa), su consentimiento libre, previo, informado, expreso e inequivoco para:</p>
                <p style="margin-bottom: 0;">(i) Recolectar los datos personales necesarios para formalizar la relación contractual con el cliente para el procesamiento de datos, elaboración de pe
                de compra, evaluaciones crediticias, gestión de los productos y/o servicios contratados y evaluación de calidad del servicio prestado y cualquier otro fia
                que sea necesario para efecto de la ejecución contractual y efrecer productos y/o servicios yo ofertas, y/o publicidad, y/o beneficios, entre otros; de la da
                y/o de terceros, a través de cualquier medio escrito, verbal, electrónico y/o informativo.</p>
                <p style="margin-bottom: 0;">(ii) Informar al Cliente, que pueden ejercer sus derechos (Acceso, actualización, inclusión, rectificación, supresión y oposición), presentando su soliciti
                nuestra oficina principal o a nuestro correo electrónico datoscasadeprestamos@sociedaddebeneficenciahuancayo.org</p>
            </td>
        </tr>

        <tr>
            <td colspan="5">
                <div class="row">
                    <div class="col-4 text-center" style="padding-top:250px;">
                        <span>_____________________________________</span>
                        <br>
                        <span style="font-size:11px;"><strong>FIRMA DEL SOLICITANTE</strong></span>
                        <br>
                        
                    </div>
                    <div class="col-4 text-center" style="padding-top:250px;">
                        <span>_____________________________________</span>
                        <br>
                        <span style="font-size:11px;"><strong>FIRMA DEL CONYUGE DEL SOLICITANTE</strong></span>
                        <br>
                        
                    </div>
                    <div class="col-4 text-center" style="padding-top:250px;">
                        <span>_____________________________________</span>
                        <br>
                        <span style="font-size:11px;"><strong>VB SELLO EJECUTIVO</strong></span>
                        <br>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="5">
                <div class="row">
                    <div class="col-4 text-center" style="padding-top:250px;">
                        <span>_____________________________________</span>
                        <br>
                        <span style="font-size:11px;"><strong>FIRMA DEL AVAL O FIADOR</strong></span>
                        
                    </div>
                    <div class="col-4 text-center" style="padding-top:250px;">
                        <span>_____________________________________</span>
                        <br>
                        <span style="font-size:11px;"><strong>FIRMA DEL CONYUGUE DEL AVAL O FIADOR</strong></span>
                        
                    </div>
                    
                </div>
            </td>
        </tr>
        


    </table>
    </div>
    
</div>
</div>
</template>

<script>
export default {

    data() {
        return {
            persona : new Form ({
                id :'',
                dni :'',
                nombres :'',
                paterno :'',
                materno :'',
                sexo :'',
                fec_nac :'',
                idestadocivil :'',
                estadocivil :'',
                email :'',
                direccion :'',
                localidad :'',
                ocupacion :'',
                celular :'',
                referencia :'',
                ingre_1 :'',
                ingre_2 :'',
                ingre_bru :'',
                idempresa :'',
                empresa :'',
                tipoentidad :'',
                sector :'',
                tipomodalidad :'',
                idanalista :'',
                estado :'',
                created_at :'',
                updated_at :'',
                escliente :'',
                n_domicilio :'',
                tipo_vivienda :'',
                nivel_instruccion :'',
                tipo_ocupacion :'',
                f_ingreso_actividades :'',
                f_fin_actividades :'',
                modalidad_contratacion :'',
                profesion :'',
                cargo :'',
                provincia:'',
                departamento:'',
                distrito:''
            }),

            empresa : new Form({
                id : '',
                tipo_contrato:'',
                razonsocial : '',
                ruc : '',
                direccion : '',
                telefono : '',
                contacto : '',
                telefo_cont : '',
                idconvenio : '',
                convenio : '',
                id_tipo : '',
                sector : '',
                iduser : '',
                estado : '',
            }),

            credito : new Form({
                tipo_cliente:'',
                updated_at:'',
                created_at:'',
                renganche:'',
                cp_desembolso:'',
                desembolso:'',
                id_apro_cuatro:'',
                fec_aproba_cuatro:'',
                fec_aproba_tres:'',
                id_apro_tres:'',
                fec_aproba_dos:'',
                id_apro_dos:'',
                fec_aproba_uno:'',
                id_apro_uno:'',
                nivel_aproba:'',
                estado_cred:'',
                estado:'',
                plazo_credito:'',
                monto_credito:'',
                max_ende:'',
                tipo:'',
                idanalista:'',
                idsupervisor:'',
                fec_apro:'',
                aprobacion:'',
                situacion_apro:'',
                plazo_max_apro:'',
                monto_max_apro:'',
                monto_max_rdi:'',
                otras_deudas:'',
                deuda_consu:'',
                monto_max_rci:'',
                idtasa_int:'',
                primer_pago:'',
                plazo_mas_ope:'',
                cuota_max_pres:'',
                deuda_hipo:'',
                sal_hipo:'',
                deuda_cc:'',
                deuda_sc:'',
                sal_deuda_cc:'',
                sal_deuda_sc:'',
                ing_netodiscred:'',
                ing_neto:'',
                des_ley:'',
                id:'',
                idpersona:'',
                meses_fal_cont:'',
                patrimonio:'',
                fecha_d:'',
                
            }),

            cronograma: new Form({
                mon_financiado : '',
                num_cuotas : '',
            }),
            cuota: new Form({
                fecha_ven:'',
            }),
        }
    },

    methods: {

        get(){
            // console.log();
            let id= this.$route.params.id;
            axios.get('/api/getformato/'+id)
            .then(({data}) => {
                console.log(data);
                this.persona.fill(data.persona);
                this.cronograma.fill(data.cronograma);
                this.cuota.fill(data.cuota);
                this.empresa.fill(data.empresa);
                this.credito.fill(data);
                
            }).catch((err) => {
                console.log(err);
            });
        },

        async imprimir() {
            // await  this.$htmlToPaper('printMe');
            let footer = document.getElementsByTagName('footer')[0]
            footer.style = 'display:none';
            var css = '@page { size: A4 portrait;} .table{padding: 0.55mm; font-size:12px !important;} .table th , td{padding:0.15rem !important} .blanco td{padding:0.55rem !important;} .espacio{padding:2rem !important;}',
                head = document.head || document.getElementsByTagName('head')[0],
                style = document.createElement('style');
            style.type = 'text/css';
            style.media = 'print';
            if (style.styleSheet) {
                style.styleSheet.cssText = css;
            } else {
                style.appendChild(document.createTextNode(css));
            }

            head.appendChild(style);
            setTimeout(() => {
                window.print();
                // window.close();
            }, 100);
                
        },
    },
    created() {
        this.get();
        
    },
    
}
</script>

<style scoped>

.label-inpt {
    font-size: 11px;
}

.tbl-primero tr td{
    padding-left: 10px;
    
}

.line {
    padding-top: 0.5%;
}

.separator {
    padding-top: 0.5%;
    padding-bottom: 0.5%;

}
</style>