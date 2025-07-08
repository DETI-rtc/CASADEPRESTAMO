<template>
<div class="card-body">
    <table style="width:100%;">
        <tr>
            <td style="text-align: left;"><img :src="'/storage/logo.png'" alt="SBH" style="width:100px;"></td>
            <td></td>
            <td style="text-align: right;"><img :src="'/storage/sbhlogo.png'" alt="SBH" style="width:100px;"></td>
        </tr>
    </table>
    <button class="btn btn-success btn-sm no-print" @click="imprimir">Imprimir</button>
    <!-- <h4 class="text-center"><strong>INFORME N°    -2022-SBH/GNE-DECDP</strong></h4> -->
    <table class="w-100 nuestra-tabla" border="1">
        <tr>
            <td colspan="7" style="border:1px solid grey; background-color: #E2E2E2;" class="text-center"><h4><strong>CALCULO DEL  MONTO, PLAZO Y CUOTA A PRESTAR</strong></h4></td>
        </tr>
        <tr>
            <td colspan="2" style="border:1px solid grey;"><strong>DNI:</strong> {{ persona.dni}} </td>
            <td colspan="5" style="border:1px solid grey;"><strong>NOMBRE:</strong> {{ persona.nombres + ' ' + persona.paterno + ' ' + persona.materno}} </td>
        </tr>
        <tr>
            <td class="text-center" colspan="2" style="border:1px solid grey; background-color: #E2E2E2;"> <strong>DATOS BÁSICOS</strong> </td>
            <td class="text-center" colspan="3" style="border:1px solid grey; background-color: #E2E2E2;"> <strong>RELACIÓN CUOTA INGRESO - RCI</strong> </td>
            <td class="text-center" colspan="2" style="border:1px solid grey; background-color: #E2E2E2;"> <strong>RELACIÓN DEUDA INGRESO - RDI</strong> </td>
        </tr>
        <tr class="body">
            <td>EDAD (AÑOS)</td>
            <td> {{persona.edad}} </td>
            <td>INGRESO BRUTO</td>
            <td>  </td>
            <td class="text-right">{{persona.ingre_bru}}</td>
            <td>LEVERAGE MÁXIMO SEGÚN MODALIDAD (N° de Ingresos)</td>
            <td class="text-right"> {{ credito.idrdi }} </td>
        </tr>

        <tr class="body">
            <td>ANTIGÜEDAD LABORAL MISMA EMPRESA (AÑOS)</td>
            <td></td>
            <td>DESCUENTOS LEY</td>
            <td> </td>
            <td class="text-right"> {{credito.des_ley}}  </td>
            <td>MAXIMO ENDEUDAMIENTO SEGÚN MODALIDAD</td>
            <td class="text-right"> {{credito.max_ende}} </td>
        </tr>

        <tr class="body">
            <td>TIPO DE ENTIDAD</td>
            <td > {{empresa.sector}} </td>
            <td>INGRESOS NETOS</td>
            <td></td>
            <td class="text-right"> {{credito.ing_neto}} </td>
            <td>DEUDA CONSUMO SBS</td>
            <td class="text-right"> {{ credito.deuda_consu }} </td>
        </tr>

        <tr class="body">
            <td>MODALIDAD</td>
            <td> {{credito.tipo_contrato}} </td>
            <td>RCI (Porcentaje del Ingreso Neto para Créditos) según Modalidad</td>
            <td class="text-right"> {{ credito.idrci }} </td>
            <td></td>
            <td>OTRAS DEUDAS</td>
            <td class="text-right"> {{ credito.otras_deudas }} </td>
        </tr>

        <tr class="body">
            <td>MESES QUE FALTAN CONTRATO</td>
            <td> NO APLICA </td>
            <td>Ingreso Neto Disponible para cubrir créditos</td>
            <td class="text-center" style="background-color: #E2E2E2;">Saldos</td>
            <td class="text-right"> {{ credito.ing_netodiscred }} </td>
            <td>MAXIMO MONTO A PRESTAR POR RDI</td>
            <td class="text-right"> {{ credito.monto_max_rdi }} </td>
        </tr>

        <tr class="body">
            <td></td>
            <td></td>
            <td>DEUDA CONSUMO ULTIMO MES SBS (SIN CRONOGRAMA)</td>
            <td class="text-right"> {{credito.deuda_sc}} </td>
            <td class="text-right"> {{credito.deuda_sc / 24}} </td>
            <td></td>
            <td></td>
        </tr>

        <tr class="body">
            <td>BANCARIZADO</td>
            <td></td>
            <td>CUOTAS FIJAS DEUDA CONSUMO CON CRONOGRAMAS</td>
            <td class="text-right"> {{ credito.sal_deuda_cc }} </td>
            <td class="text-right"> {{ credito.deuda_cc }} </td>
            <td></td>
            <td></td>
        </tr>

        <tr class="body">
            <td>CALIFICACIÓN SBS ULTIMO REPORTE</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr class="body">
            <td>CALIFICACIÓN SBS PENÚLTIMO REPORTE</td>
            <td></td>
            <td>DEUDA Y CUOTA HIPOTECARIA CON CRONOGRAMA</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>


        <tr class="body">
            <td>CALIFICACIÓN SBS ANTEPENÚLTIMO REPORTE</td>
            <td></td>
            <td>CUOTAS OTRAS DEUDAS</td>
            <td></td>
            <td></td>
            <td colspan="2" class="text-center" style="background-color: #E2E2E2;"><strong>CÁLCULO FINAL</strong></td>
        </tr>

        <tr class="body">
            <td>ESTADO CIVIL</td>
            <td> {{persona.estadocivil}} </td>
            <td>CUOTA MÁXIMA PARA UN PRESTAMO DE LA CASA </td>
            <td>  </td>
            <td class="text-right"> {{ credito.cuota_max_pres | localmoney}}</td>
            <td>MONTO MAXIMO A APROBAR</td>
            <td class="text-right"> {{credito.monto_max_apro | localmoney}} </td>
        </tr>

        <tr class="body">
            <td>PATRIMONIO</td>
            <td class="text-right"> {{ credito.patrimonio }} </td>
            <td> </td>
            <td></td>
            <td></td>
            <td>PLAZO MÁXIMO A APROBAR</td>
            <td class="text-right"> {{ credito.plazo_max_apro }} </td>
        </tr>

        <tr class="body">
            <td></td>
            <td></td>
            <td>PLAZO MÁXIMO POR MODALIDAD DE CONTRATO (MESES) </td>
            <td></td>
            <td> {{ credito.plazo_max_apro }} </td>
            <td>TEM</td>
            <td class="text-right"> {{credito.tem}} </td>
        </tr>

        <tr class="body">
            <td></td>
            <td></td>
            <td>MESES QUE FALTAN DE CONTRATO (MESES)</td>
            <td></td>
            <td > {{ credito.meses_fal_cont }} </td>
            <td>PRIMER PAGO A </td>
            <td class="text-right"> {{credito.primer_pago}} </td>
        </tr>

        <tr class="body">
            <td></td>
            <td></td>
            <td>PLAZO MÁXIMO DE LA OPERACIÓN (MESES)</td>
            <td></td>
            <td> {{credito.plazo_mas_ope}} </td>
            <td></td>
            <td></td>
        </tr>

        <tr class="body">
            <td></td>
            <td></td>
            <td>PRIMER PAGO A 30, 60 DÍAS</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr class="body">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr class="body">
            <td></td>
            <td></td>
            <td>TEA/TEM SEGÚN MODALIDAD DE CONTRATO</td>
            <td class="text-right"> {{ credito.idtasa_int }} </td>
            <td class="text-right"> {{ parseFloat(credito.tem_rci) * 100 }} </td>
            <td></td>
            <td></td>
        </tr>

        <tr class="body">
            <td></td>
            <td></td>
            <td>MONTO MÁXIMO A PRESTAR POR RCI</td>
            <td></td>
            <td class="text-right"> {{credito.monto_max_rci}} </td>
            <td></td>
            <td></td>
        </tr>


        

    </table>
    
</div>
</template>

<script>
export default {

    data() {
        return {
            persona : new Form ({
                estadocivil:'',
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
                edad :'',
                tiempo_trabajo :'',
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
                deuda_sc:'',
                tem_rci:'',
                idrdi:'',
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
                tipo_contrato:'',
                tem:''
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
            // let secret = await this.getOrden();
            let footer = document.getElementsByTagName('footer')[0]
            footer.style = 'display:none';
            // var css = '.thismedio{font-size:11pt;}*{ font-size:11pt;line-height:11pt !important; letter-spacing: 4pt !important;}@media print{  @page { font:10pt "Courier New";margin: 152px 0 0 0; }}',
            var css = '@page { size: A4 landscape;} *{font-size:17px !important;}',
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

.nuestra-tabla tr td{
    padding: 10px;
    border:1px solid grey;
}

.nuestra-tabla .body td{
    font-size: 10px !important;
    padding: 5px 5px;
    border:1px solid grey;
}

/* .nuestra-tabla .body {
    font-size: 10px !important;
} */

</style>