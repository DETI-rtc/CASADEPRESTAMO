<template>
<div class="card-body">
    <table style="width:100%;">
        <tr>
            <td style="text-align: left;"><img :src="'/storage/logo.png'" alt="SBH" style="width:100px;"></td>
            <td></td>
            <td style="text-align: right;"><img :src="'/storage/sbhlogo.png'" alt="SBH" style="width:100px;"></td>
        </tr>
    </table>
    <div class="btn-group no-print" role="group" aria-label="Basic example">
        <button class="btn btn-primary btn-sm " @click="imprimir">imprimir</button>
        <el-select v-model="tipo" placeholder="Seleccione">
            <el-option
            v-for="item in tipos"
            :key="item.value"
            :label="item.label"
            :value="item.value">
            </el-option>
        </el-select>
    </div>
    <h4 class="text-center"><strong> HOJAS DE INSTRUCCIONES PARA EL DESEMBOLSO CON COMPRA DE DEUDA </strong></h4>
    <div class="text-right pt-5">
        <p>
            Huancayo, {{ credito.dia }} de {{ credito.mes }} del {{ credito.anio }}
        </p>
    </div>
    <p>
        Señores:
        <br>Casa de Préstamo de Huancayo - SBH.
        <br>Presente. -
    </p>
    <p>
        Estimados Señores:
    </p>
    <p>
        Yo,  <strong>{{ persona.paterno + ' ' + persona.materno + ' ' + persona.nombres }}</strong> con DNI
        <strong> {{  persona.dni }}</strong>
    </p>
    <p>
        Mediante el presente documento, declaro las deudas que mantengo con las siguientes instituciones financieras, 
        por las cuales SOLICITO Y AUTORIZO a la Casa de Préstamo Huancayo-SBH, realizar la cancelación de las mismas, 
        con los fondos producto del préstamo obtenido, asumiendo todos los gastos que correspondan.
    </p>
    <table class="w-100 inst" border="1">
        <tr>
            <td style="width:20%; border: 1px solid grey;">Institución</td>
            <td style="width:20%; border: 1px solid grey;">Tipo de Deuda: Tarjeta o Préstamo</td>
            <td style="width:20%; border: 1px solid grey;">Monto</td>
            <td style="width:30%; border: 1px solid grey;">Número de TC o Préstamo</td>
        </tr>
        <tr>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
        </tr>
        <tr>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
        </tr>
        <tr>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
        </tr>
        <tr>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
        </tr>
        <tr>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
        </tr>
        <tr>
            <td style="height:25px; border: 1px solid grey;" colspan="2" class="text-center"><strong>Total</strong></td>
            <td style="height:25px; border: 1px solid grey;"></td>
            <td style="height:25px; border: 1px solid grey;"></td>
        </tr>
    </table>
    <p>Así mismo, si hubiese saldo a mi favor, este me deberá ser entregado en cheque bancario o abonado a mi cuenta con <span v-if="tipo == 0">CCI N° ………………………………………………………</span> <span v-if="tipo == 1">CHEQUES NO NEGOCIABLES</span>  <span v-if="tipo == 3">CUENTA <strong>SCOTIABANK NRO {{persona.cuenta_scotiabank}}</strong></span>, asumiendo todos los gastos en que se incurra.</p>
    <p class="mt-3 pt-5" style="margin-top:180px !important;">
        <strong>CLIENTE</strong>
        <br> <strong>Nombre</strong>:{{ persona.paterno + ' ' + persona.materno + ' ' + persona.nombres }}
        <br> <strong>DNI</strong>: {{ persona.dni }}
    </p>
</div>
</template>

<script>
export default {

    data() {
        return {
            tipos:[
                {value:1, label:'Cheque'},
                {value:0, label:'CCI'},
                {value:3, label:'Cuenta Scotiabank'},
            ],
            tipo:0,
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
                cuenta_scotiabank:'',

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
            // let secret = await this.getOrden();
            let footer = document.getElementsByTagName('footer')[0]
            footer.style = 'display:none';
            // var css = '.thismedio{font-size:11pt;}*{ font-size:11pt;line-height:11pt !important; letter-spacing: 4pt !important;}@media print{  @page { font:10pt "Courier New";margin: 152px 0 0 0; }}',
            var css = '@page { size: A4 portrait;} *{font-size:18px !important;}',
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

.inst tr td{
    padding-left: 5px;
    padding-right: 5px;
}
</style>