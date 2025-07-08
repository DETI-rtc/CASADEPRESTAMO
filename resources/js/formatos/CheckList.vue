<template>
<div class="card">
    <div class="card-header no-print">
    <div class="col-md-6 pt-5">
    <button class="btn btn-success btn-sm no-print" @click="imprimir">Imprimir</button>
    </div>
</div>

<div class="card-body"  id="printMe">
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
    <!-- <h4 class="text-center"><strong>PAGARÉ</strong></h4> -->
    <table style="width:100%" border="1">
        <tr>
            <td colspan="6" class="text-center" style="background-color:#7777">CHECK LIST PARA EL CREDITO CONVENIO/PRENDA</td>
        </tr>
        <tr>
            <td colspan="5" class="text-center" style="background-color:#7777">DOCUMENTOS PARA EVALUACIÓN</td>
            <td style="background-color:#7777">FECHA: </td>
        </tr>
        <tr class="text-center">
            <td>Formatos de la Casa de Prestamos Social de la SBH</td>
            <td>Rev. por Jefe Comercial</td>
            <td>Rev. por An. Créditos</td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td class="p-2">Solicitud de credito por Convenio / Prenda </td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Firma del titular, cónyuge(solo casados), avales y Ejecutivo Convenio</span>
                <br>
                <span>Firma del titular y del Ejecutivo Prenda</span>
            </td>
        </tr>

        <tr>
            <td class="p-2">Contrato de credito por Convenio/Prenda </td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Firma del titular, cónyuge(solo casados), avales y Ejecutivo Convenio</span>
            </td>
        </tr>

        <tr>
            <td class="p-2">Hoja de resumen credito por Convenio/Prenda </td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Firma Titular</span>
            </td>
        </tr>

        <tr>
            <td class="p-2">Pagaré </td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Firma del titular, cónyuge(solo casados), avales y Ejecutivo Convenio</span>
            </td>
        </tr>

        <tr>
            <td class="p-2">Carta de automatización de descuento para planilla para C. Convenio </td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Firma del titular y representante de la Empresa/Institucion Empleador</span>
            </td>
        </tr>

        <tr>
            <td class="p-2">Certificado de Seguro Desgravament para C. Convenio </td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Firma Titular</span>
            </td>
        </tr>

        <tr>
            <td class="p-2">Hoja de Instruccion para desembolso para C. Convenio/C. Prenda </td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Firma Titular</span>
            </td>
        </tr>

        <tr>
            <td class="text-center" style="background-color:#7777">Documento del Cliente</td>
            <td colspan="5" class="text-center" style="background-color:#7777"></td>
        </tr>

        <tr>
            <td class="p-2">Copia de documento de Identidad del titular, conyugue, avales </td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Copia con fedateo del Ejecutivo de Convenio/Prenda</span>
            </td>
        </tr>

        <tr>
            <td class="p-2">Copia de Boletas de Pago </td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Copia con fedateo del Ejecutivo de Convenio</span>
            </td>
        </tr>


        <tr>
            <td class="p-2">Copia Sustento de Pago</td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Copia con fedateo del Ejecutivo de Convenio</span>
            </td>
        </tr>
        <tr>
            <td class="p-2">Copia Estado de cuenta de ahorros (haberes) </td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Copia con fedateo del Ejecutivo de Convenio</span>
            </td>
        </tr>
        <tr>
            <td class="p-2">Copia de Recibo de servicios Domicilio </td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Copia con fedateo del Ejecutivo de Convenio</span>
            </td>
        </tr>
        <tr>
            <td class="p-2">Copia de Cronogramas de pago de otros Créditos </td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Copia con fedateo del Ejecutivo de Convenio</span>
            </td>
        </tr>
        <tr>
            <td class="p-2">Otros</td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
                <span>Copia con fedateo del Ejecutivo de Convenio</span>
            </td>
        </tr>

        <tr>
            <td colspan="6" class="text-center" style="background-color:#7777">EVALUACION DEL ANALISTA DE CREDITO</td>
        </tr>

        <tr>
            <td class="p-2">Precalificación Central de Riesgo Titular, Esposa y Avales C. Convenio</td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
            </td>
        </tr>

        <tr>
            <td class="p-2">Precalificación Central de Riesgo Solo Titular C. Prenda</td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
            </td>
        </tr>

        <tr>
            <td class="p-2">Print de Reniec de los intervenientes C. Convenio y C. Prenda</td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
            </td>
        </tr>
        <tr>
            <td class="p-2">Verificación Laboral Teléfonica para Crédito Convenio</td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
            </td>
        </tr>
        <tr>
            <td class="p-2">Consulta Essalud de Titular para Crédito Convenio</td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
            </td>
        </tr>
        <tr>
            <td class="p-2">Paginas blancas para Créditos Convenio</td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
            </td>
        </tr>

        <tr>
            <td class="p-2">Print de RUC de la Empresa/Institución para Crédito Convenio</td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
            </td>
        </tr>

        <tr>
            <td class="p-2">Precalificación Central de Riesgo de la Empresa/Institución</td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
            </td>
        </tr>

        <tr>
            <td class="p-2">Cálculo del Monto del Crédito a Aprobar Crédito Convenio</td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
            </td>
        </tr>

        <tr>
            <td class="p-2">Cálculo del Monto del Crédito a Aprobar Crédito Prenda</td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td class=""><div style="border:1px solid black; width:25px;height:25px; margin: auto !important;"></div></td>
            <td colspan="3" class="p-2"> 
            </td>
        </tr>

        <tr>
            <td colspan="6" class="text-center" style="background-color:#7777">DETALLES DE LA APROBACIÓN</td>
        </tr>

        <tr>
            <td class="p-2">Monto de Aprobación :</td>
            <td></td>
            <td class="p-2" colspan="4" style="background-color:#7777"> <u>Garantía de Joya de Oro</u> </td>
        </tr>
        <tr>
            <td class="p-2">Plazo de Aprobación :</td>
            <td></td>
            <td class="p-2" colspan="4"> Tipo de Joya de Oro :</td>
        </tr>
        <tr>
            <td class="p-2">Primer Vencimiento :</td>
            <td></td>
            <td class="p-2" colspan="4"> Pureza del Oro : </td>
        </tr>
        <tr>
            <td class="p-2">AVAL :</td>
            <td></td>
            <td class="p-2" colspan="4"> Peso de la Joya :</td>
        </tr>

        <tr>
            <td class="p-2"></td>
            <td></td>
            <td class="p-2" colspan="4"> Código de la Garantía :</td>
        </tr>

        <tr>
            <td colspan="6" class="text-center" style="background-color:#7777">COMENTARIOS ANALISTA DE CREDITO</td>
        </tr>

        <tr>
            <td colspan="6">-</td>
        </tr>

        <tr>
            <td colspan="6">-</td>
        </tr>

        <tr>
            <td colspan="6">-</td>
        </tr>

        <tr>
            <td colspan="6">
                <div class="row">
                    <div class="col-4" style="padding:1rem;">
                        <div class="text-center"  style="border:1px solid black; padding-top:100px; height:150px">
                            <span>Firma y Sello Ejecutivo</span>
                        </div>
                    </div>
                    <div class="col-4" style="padding:1rem;">
                        <div class="text-center"  style="border:1px solid black; padding-top:100px; height:150px">
                            <span>Firma y Sello Analista Creditos y Cobranzas</span>
                        </div>
                    </div>
                    <div class="col-4" style="padding:1rem;">
                        <div class="text-center"  style="border:1px solid black; padding-top:100px; height:150px">
                            <span>Firma y Sello Jefe de Creditos y Cobranzas</span>
                        </div>
                    </div>
                    <div class="col-6" style="padding:1rem;">
                        <div class="text-center"  style="border:1px solid black; padding-top:100px; height:150px">
                            <span>Firma y Sello Jefe Comercial de Creditos</span>
                        </div>
                    </div>
                    <div class="col-6" style="padding:1rem;">
                        <div class="text-center"  style="border:1px solid black; padding-top:100px; height:150px">
                            <span>Firma y Sello Sub Gerente de la Casa de Préstamos </span>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="6" class="text-center" style="background-color:#7777">DESEMBOLSO</td>
        </tr>

        <tr>
            <td colspan="6" style="padding: 10px 400px;">
                <div class="text-center"  style="border:1px solid black; padding-top:100px; width:400px !important; height:150px">
                    <span>Firma y Sello Tesorero Casa de Préstamo </span>
                </div>
            </td>
        </tr>


    </table>
    </div>
    
</div>
</div>
</template>

<script>
import VueHtmlToPaper from 'vue-html-to-paper';
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
            }),

            empresa : new Form({
                id : '',
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
                monto_multiplicado:'',
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
                dia:'',
                mes:'',
                anio:'',
            }),

            cronograma : new Form({
                id :'',
                iduser :'',
                idcliente :'',
                idcredito :'',
                tasa_men_desgra :'',
                com_desc_auto :'',
                tasa_efe_anu :'',
                t_interes :'',
                num_cuotas :'',
                periocidad :'',
                f_ultima_cuota :'',
                com_desembolso :'',
                mon_ne_desem :'',
                f_desembolso :'',
                mon_financiado :'',
                estado :'',
            }),

            cuota : new Form({
                id :'',
                idcrono :'',
                cuotanro :'',
                fecha_ven :'',
                sal_cap :'',
                cap_amor :'',
                interes :'',
                com_des :'',
                seg_des :'',
                cuota :'',
                diasnopago :'',
                mora :'',
                situacion :'',
                mon_pag :'',
                sal_pen :'',
                estado :'',
                iduser :'',
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
                this.empresa.fill(data.empresa);
                this.cronograma.fill(data.cronograma);
                this.credito.fill(data);
                this.cuota.fill(data.cuota);
                
            }).catch((err) => {
                console.log(err);
            });
        },
        async imprimir() {
            this.$htmlToPaper('printMe');
            return;
            // VueHtmlToPaper('#imprimircrono');
            this.$htmlToPaper('printMe');
            return;
            // let secret = await this.getOrden();
            let footer = document.getElementsByTagName('footer')[0]
            footer.style = 'display:none';

            

            // var css = '.thismedio{font-size:11pt;}*{ font-size:11pt;line-height:11pt !important; letter-spacing: 4pt !important;}@media print{  @page { font:10pt "Courier New";margin: 152px 0 0 0; }}',
            var css = '@page { size: A4 portrait;@bottom-left {content: counter(page) "of " counter(pages);}} *{font-size:17px !important;}; @page:right{@bottom-right {content: counter(page);}}; @page:left{  @bottom-left {    content: counter(page);  }}',
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
@media print {
    .card-body{
        overflow: visible !important;
        /* margin-top:-50px !important; */
        /*top right bottom left */
        text-align: justify;
        padding: 50px 100px 0px 100px !important;
        font-size: 15px;
    }
}

</style>