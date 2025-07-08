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
    <!-- <el-select v-model="tipo" placeholder="Seleccione" class="no-print">
            <el-option
            v-for="item in tipo_cuenta"
            :key="item.value"
            :label="item.label"
            :value="item.value">
            </el-option>
    </el-select> -->
    <h4 class="text-center"><strong> HOJA RESUMEN DE CRÉDITO CONVENIO</strong></h4>

    <p>
        En cumplimiento de lo señalado en la Resolución SBS N° 3274 - 2017 
        (Reglamento de Gestión de Conducta de Mercado del Sistema Financiero) 
        la Casa de Préstamo de Huancayo - SBH (“LA CASA DE PRÉSTAMO HUANCAYO”), 
        informa al cliente (“EL CLIENTE”) lo siguiente, respecto al “Crédito” 
        que solicita y se desembolsa en la fecha.
    </p>
    <p>
        <strong> <u> 1. Datos del Cliente </u> </strong>
    </p>

    <table class="w-100">
        <tr>
            <!-- <td class="bg-secondary" style="height:20px !important;"></td> -->
            <td><input type="text" class="w-100" v-model="datos_cliente" disabled></td>
        </tr>
    </table>

    <p>
        <strong> <u> 2. Características del Crédito </u> </strong>
    </p>
    <table class="w-100">
        <tr>
            <td colspan="2">Tasa Interés Compensatoria Efectiva Anual (TEA): </td>
            <td  style="width:20%;"> <input type="text"  disabled class="w-100" v-model="cronograma.tasa_efe_anu"></td>
            <td colspan="2">(Tasa fija a 360  días)</td>
        </tr>
        <tr>
            <td colspan="2">Tasa de Costo Efectivo Anual (TCEA) :</td>
            <td><input type="text"  disabled class="w-100"></td>
            <td colspan="2">(Tasa fija a 360  días)</td>
        </tr>
        <tr>
            <td style="width:20%">Monto del crédito (S/):  </td>
            <td style="width:20%"><input type="text"  disabled class="w-100" v-model="cronograma.mon_financiado"></td>
            <td colspan="2">Plazo del crédito (cuotas mensuales)</td>
            <td><input type="text"  disabled class="w-100" v-model="cronograma.num_cuotas"></td>
        </tr>
        <tr>
            <td>Plazo de gracia:  </td>
            <td><input type="text"  disabled class="w-100"></td>
            <td>Día de pago :</td>
            <td style="width:20%"><input type="text"  disabled class="w-100" v-model="credito.fecha_primera_couta"></td>
            <td style="width:20%"></td>
        </tr>
    </table>
    <p>
        <strong> <u> 3. Comisiones y Gastos asociados al crédito </u> </strong>
    </p>
    <table>
        <tr>
            <td colspan="2">Descuento automático por Planilla: </td>
            <td  style="width:20%;"> <input type="text"  disabled class="w-100" v-model="cuota.cuota"></td>
        </tr>
    </table>
    <p>
        (*) El importe de la comisión varía de acuerdo a la institución en la que labora EL CLIENTE. Se cobra durante la vigencia del crédito, siempre y 
        cuando el cliente tenga algún saldo deudor. Si EL CLIENTE paga la cuota mensual en los puntos de pago de LA CASA DE PRÉSTAMO HUANCAYO - SBH, antes
         de la fecha de vencimiento, dicha comisión será devuelta a través de una orden de pago. El canal libre de costo es los puntos de pago de la Casa. (**) Del monto del crédito.
    </p>
    <p>
        (*) El importe de la comisión varía de acuerdo a la institución en la que labora EL CLIENTE. Se cobra durante la vigencia del crédito, siempre y 
        cuando el cliente tenga algún saldo deudor. Si EL CLIENTE paga la cuota mensual en los puntos de pago de LA CASA DE PRÉSTAMO HUANCAYO - SBH, antes 
        de la fecha de vencimiento, dicha comisión será devuelta a través de una orden de pago. El canal libre de costo es los puntos de pago de la Casa. (**) Del monto del crédito.
    </p>
    <p>
        <strong> <u> 4. Incumplimiento de Obligaciones Contraídas </u> </strong>
    </p>
    <p>
        Ante el incumplimiento de las condiciones pactadas, se procederá a realizar el reporte correspondiente a las Centrales de Riesgo con la calificación que corresponda.
    </p>
    <p>
        <strong> <u> 5. Periodo de Gracia </u> </strong>
    </p>
    <p>
        Es potestad de LA CASA DE PRÉSTAMO HUANCAYO- SBH conceder a EL CLIENTE un periodo de gracia, en cuyo caso, EL CLIENTE deberá cancelar los intereses 
        equivalentes a la tasa de interés compensatoria que se encuentra vigente.
    </p>

    <p>
        <strong> <u> 6. Alcances y obligaciones puntuales de fiadores solidarios </u> </strong>
    </p>

    <p>
        El cliente ha otorgado a LA CASA DE PRÉSTAMO HUANCAYO- SBH la siguiente garantía (marcar con una “X” si corresponde):
    </p>
    <p>
        <!-- Fiador Solidario {{ '"asdasd"' }} -->
    </p>
    <p>
        El detalle de los alcances y obligaciones de la garantía antes señalada se encontrará en el Contrato que el cliente se obliga a suscribir con LA CASA DE PRÉSTAMO HUANCAYO- SBH, en documento adicional.
    </p>
    <p>
        <strong> <u> 7. Declaración final de EL CLIENTE Y  AVAL O FIADOR </u> </strong>
    </p>

    <p>
        El (Los) suscrito(s) declaro (amos) haber recibido por parte de la CASA DE PRÉSTAMO HUANCAYO - SBH, el contrato de “Crédito por Convenio” y 
        la Hoja Resumen correspondiente junto con el Cronograma de Pagos. Así mismo, declaro (amos) que LA CASA ha absuelto todas y cada una de mis (nuestras) dudas acerca del contenido y 
        la forma de ejecución de las disposiciones señaladas en los mencionados documentos. En vista de ello, suscribo (suscribimos), en total conocimiento de las condiciones, el presente 
        instrumento en señal de conformidad de los términos y condiciones de Crédito recibido en dos ejemplares de igual tenor en la ciudad 
        de Huancayo a los {{ credito.dia }} días del mes de {{ credito.mes }} del {{ credito.anio }}.
    </p>

    <table class="w-100">
        <tr>
            <td style="padding-top:20%;"> 
                <span>_____________________________________</span>
                <br>
                <span>EL CLIENTE</span>
                <br>
                <span>Nombre: {{ persona.paterno + ' ' + persona.materno + ' ' +persona.nombres }}</span>
                <br>
                <span>DNI: {{persona.dni}}</span>
            </td>

            <td style="padding-top:20%;"> 
                <span>_____________________________________</span>
                <br>
                <span>CÓNYUGE DEL CLIENTE</span>
                <br>
                <span>Nombre: _____________________________</span>
                <br>
                <span>DNI: _________________________________</span>
            </td>
        </tr>

        <tr>
            <td style="padding-top:20%;"> 
                <span>_____________________________________</span>
                <br>
                <span>EL AVAL O FIADOR</span>
                <br>
                <span>Nombre: _____________________________</span>
                <br>
                <span>DNI: _________________________________</span>
            </td>

            <td style="padding-top:20%;"> 
                <span>_____________________________________</span>
                <br>
                <span>CÓNYUGE DEL AVAL O FIADOR</span>
                <br>
                <span>Nombre: _____________________________</span>
                <br>
                <span>DNI: _________________________________</span>
            </td>
        </tr>

        <tr>
            <td style="padding-top:20%;"> 
                <span>_____________________________________</span>
                <br>
                <span>CASA DE PRÉSTAMO DE HUANCAYO</span>
                <br>
                <span>Nombre: _____________________________</span>
                <br>
                <span>DNI: _________________________________</span>
            </td>

            <td style="padding-top:20%;"> 
                
            </td>
        </tr>
    </table>

</div>
</template>

<script>
export default {

    data() {
        return {
            datos_cliente:'',
            tipo_cuenta:[
                {label:'CHEQUE', value:1},
                {label:'CCI', value:2},
                {label:'CUENTA SCOTIABANK', value:3}
            ],
            tipo:'',
            
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
                fecha_primera_couta:'',
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
                dia_pago:'',
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
                this.datos_cliente = data.persona.nombres + ' ' + data.persona.paterno + ' '+ data.persona.materno;
                
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
    input {
        text-align: center !important;
    }
}
</style>