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
    <h4 class="text-center"><strong>CARTA AUTORIZACION</strong></h4>

    <div class="text-right pt-5">
        <p>
            Huancayo, {{ credito.dia }} de {{ credito.mes }} del {{ credito.anio }}
        </p>
    </div>
    <p>
        Señores:
        <br>Empresa/Institución: <strong>{{ empresa.razonsocial }}</strong>
        <br>RUC	: <strong>{{ empresa.ruc }}</strong>
    </p>
    <p>
        Presente.-
    </p>
    <p>
        Estimados Señores:
    </p>
    <p>
        Yo, <strong>{{ persona.paterno + ' ' + persona.materno + ' ' +persona.nombres }} </strong>  con DNI <strong>{{ persona.dni }}</strong>
        quien ocupa el puesto de {{persona.cargo}}
        en el área de {{ persona.area}} , ubicado en la 
        dirección <strong>{{ empresa.direccion }} </strong>, 
        mediante la presente, AUTORIZO a UDS, descontar mensualmente el monto de <strong>S/. {{ cuota.cuota }} </strong>
        de mi remuneración y transferir dicho importe a la Casa de Préstamo Huancayo -SBH, 
        para el pago de la cuota mensual del préstamo convenio, otorgado por dicha entidad financiera. 
        Así mismo, en el caso que dejase de laborar en esta empresa, AUTORIZO a UDS realizar la retención 
        del monto correspondiente del saldo actualizado más los intereses vencidos del préstamo en mención.
    </p>
    <p>
        Datos de Préstamo:
    </p>
    <p>
        Monto			: <strong>S/. {{ credito.monto_credito }}</strong>
        <br> Plazo			:     <strong>{{ credito.plazo_credito }}</strong>   cuotas
        <br> Importe de la Cuota	:  <strong>S/.{{ cuota.cuota }}</strong>   
    </p>
    <div class="mt-3 pt-5" style="margin-top:150px !important;">
        <div style="width:40%; border-top:2px solid black;"></div>
        <strong>CLIENTE</strong>
        <br> <strong>Nombre</strong>: {{ persona.paterno + ' ' + persona.materno + ' ' +persona.nombres }} 
        <br> <strong>DNI</strong>: {{ persona.dni }}

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
                area:'',
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
            var css = '@page { size: A4 portrait;} *{font-size:19px !important;}',
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