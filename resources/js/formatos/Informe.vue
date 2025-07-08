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
    <h4 class="text-center"><strong>INFORME N°______-2022-SBH/GNE-DECDP</strong></h4>
    <table style="font-weight:bold !important;" class="table table-borderless">
        <tr>
            <td style="width: 100px;">Atención :</td>
            <td>
                <span>DANY DANIEL REYES RAMOS</span>
                <br>
                <span>SUB GERENTE DE LA CASA DE PRESTAMOS HUANCAYO</span>
            </td>
        </tr>
        <tr>
            <td>Asunto :</td>
            <td>COMITÉ DE CRÉDITO</td>
        </tr>
        <tr>
            <td>Fecha :</td>
            <td>HUANCAYO, {{credito.dia}} DE {{credito.mes}} DEL {{credito.anio}}</td>
        </tr>
    </table>
    <p>Por medio de la presente me dirijo a usted para saludarlo cordialmente, así mismo
        solicito desembolso de Crédito por la cliente:</p>
    <ul>
        <li>{{ persona.paterno + ' '+ persona.materno + ' ' + persona.nombres }} con DNI: {{ persona.dni }}, de {{persona.edad}} años de edad,
        personal {{ credito.tipo_contrato }}, viene laborando {{ persona.tiempo_trabajo}} años como {{ persona.cargo }}.</li>

        <li>Cliente con buen calificativo en {{empresa.razonsocial}} (EQUIFAX) NORMAL 100%.</li>

        <!-- <li>Cliente solicita reenganche de S/ 28,000.00 (Veintiocho mil con 00/100 soles) para la
        compra de deuda de la COOPERATIVA CREDIPLATA y el resto para la construcción de su
        casa.</li> -->
    </ul>

    <span>CLTE: {{ persona.paterno + ' '+ persona.materno + ' ' + persona.nombres }}</span>
    <br>
    <span>DNI: {{ persona.dni }}</span>
    <br>
    <span>REGIMEN LABORAL: {{ credito.tipo_contrato }} </span>
    <br>
    <span>MONTO: S/.{{ credito.monto_credito }}</span>
    <br>
    <span>PLAZO: {{cronograma.num_cuotas}} meses</span>
    <br>
    <span>TEA: {{cronograma.tasa_efe_anu}}% </span>
    <br>
    <span>Es cuanto informo para su conocimiento. </span>
    <!-- <p>Cantidad: (Moneda y Monto) <strong>S/. {{ credito.monto_credito }}</strong>
        <br>
        Fecha de Vencimiento:  <strong>{{ cronograma.f_ultima_cuota }}</strong>   N° {{cronograma.num_cuotas}}
        <br>
        Valor recibido a mi (nuestra) entera satisfacción. Por este Pagaré, yo (nosotros) que abajo firmo (firmamos), me comprometo (nos comprometemos) incondicionalmente a pagar a 
        LA CASA DE PRÉSTAMO HUANCAYO - SBH, la suma de S/ <strong>{{ credito.monto_multiplicado }}</strong> en la misma moneda y en la dirección de LA CASA DE PRÉSTAMO HUANCAYO - SBH sito en Jr. Cuzco 1576 Huancayo, Junín.
    </p>
    <p>
        Queda estipulado que si no efectuó (efectuamos) el pago a su presentación, abonaré (abonaremos) además del monto indicado, 
        intereses compensatorios a una tasa efectiva mensual del {{ cronograma.tasa_efe_anu }}%, hasta la total cancelación, más los 
        tributos, los gastos notariales y judiciales si los hubiera. El modo adeudado incluye comisiones, gastos notariales, seguros, tasaciones, penalidad por pago fuera de fecha según sea el caso y cualquier otro que sea necesario para el otorgamiento del crédito, oportunamente informado en el tarifario de la Casa de Préstamos.
    </p>
    <p>
        Los gastos mencionados serán dados al momento de liquidarse. Así mismo, acepto (aceptamos) todas las renovaciones, totales o parciales, que se anoten en este documento, sin que sea necesario que estén, suscritas. El presente Pagaré no se encuentra sujeto a la formalidad del protesto. En tal sentido, la acción cambiaria, se ejecutará por el solo mérito de haber vencido el plazo señalado en el presente Pagaré. Asimismo, será (serán) de mi (nuestro) cargo el integro de los tributos y gastos que afecten a este Pagaré o la obligación en él, contenida. Renuncio (renunciamos) expresamente al domicilio que pudiera corresponderme y acepto (aceptamos) los tribunales que LA CASA DE PRÉSTAMO HUANCAYO - SBH, o el tenedor del presente Pagaré designe para efectos de ejecución.
    </p>
    <p>
        Huancayo {{ credito.dia }} de {{ credito.mes }} {{ credito.anio }}
    </p> -->
    <table class="w-100">
        <tr>
            <td class="text-center" style="padding-top:180px;"> 
                <span>_____________________________________</span>
                <br>
                <span><strong>SOLICITADO POR :</strong></span>
                <!-- <br>
                <span>Nombre: {{ persona.paterno + ' '+ persona.materno + ' ' + persona.nombres }}</span>
                <br>
                <span>DNI: {{ persona.dni }}</span> -->
            </td>

            <td class="text-center" style="padding-top:180px;"> 
                <span>_____________________________________</span>
                <br>
                <span><strong>APROBADO POR :</strong></span>
                <!-- <br>
                <span>Nombre: _____________________________</span>
                <br>
                <span>DNI: _________________________________</span> -->
            </td>
        </tr>

        
    </table>
    
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
                tipo_contrato:''
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
            var css = '@page { size: A4 portrait;} *{font-size:17px !important;}',
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