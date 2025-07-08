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
    <el-select v-model="tipo" placeholder="Seleccione" class="no-print">
            <el-option
            v-for="item in tipo_cuenta"
            :key="item.value"
            :label="item.label"
            :value="item.value">
            </el-option>
        </el-select>
    <h4 class="text-center"><strong> HOJAS DE INSTRUCCIONES PARA EL DESEMBOLSO SIN COMPRA DE DEUDA </strong></h4>
    <div class="text-right pt-5">
        <p>
            Huancayo, {{credito.dia}} de {{credito.mes}} del {{ credito.anio }}
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
        Yo, <strong>{{persona.paterno + ' ' + persona.materno + ' ' + persona.nombres}}</strong> con DNI
        <strong> {{ persona.dni }}</strong>
    </p>
    <p>
        Mediante el presente documento, AUTORIZO a la Casa de Préstamos Huancayo-SBH, que los fondos producto del préstamo obtenidos, 
        me sean entregados en cheque bancario o abonado a mi cuenta con 
        <template v-if="tipo == 2">
            CCI N° {{persona.cci}}
        </template>
        <template v-if="tipo == 3">
            cuenta Scotiabank N° <strong>{{persona.cuenta_scotiabank}}</strong>
        </template>

        <template v-if="tipo == 1">
            CHEQUE
        </template>
        
        
        , asumiendo todos los gastos en que incurran.
    </p>

    <p class="mt-3 pt-5" style="padding-top:250px !important;">
        <strong>CLIENTE</strong>
        <br> <strong>Nombre</strong>:{{persona.paterno + ' ' + persona.materno + ' ' + persona.nombres}}.
        <br> <strong>DNI</strong>: {{ persona.dni }}

    </p>
</div>
</template>

<script>
export default {

    data() {
        return {
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
                cci:'',
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
                dia:'',
                mes:'',
                anio:'',
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
                
            }).catch((err) => {
                console.log(err);
            });
        },

        async imprimir() {
            // let secret = await this.getOrden();
            let footer = document.getElementsByTagName('footer')[0]
            footer.style = 'display:none';
            // var css = '.thismedio{font-size:11pt;}*{ font-size:11pt;line-height:11pt !important; letter-spacing: 4pt !important;}@media print{  @page { font:10pt "Courier New";margin: 152px 0 0 0; }}',
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