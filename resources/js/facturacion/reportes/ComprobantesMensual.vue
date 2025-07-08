<template>
<div>
    <div class="card" style="border:none;">
        <div class="card-header no-print">
            <div class="row">
                <div class="col-md-3">
                    <button class="btn bg-gradient-danger" @click="back">Volver</button>
                    <button class="btn btn-warning" @click="print">Imprimir</button>
                </div>
                <div class="col-md-3">
                    <el-select v-model="mes" @input="getComprobantes" class="border-left rounded-left">
                        <el-option v-for="option in meses" :key="option.nombre" :value="option.mes" :label="option.nombre"></el-option>
                    </el-select>
                </div>
            </div>
            
        </div>
        <div class="card-body">
            <div v-if="loading">
                <div class="d-flex justify-content-center align-content-center" style="height: 50vh; margin-top:20em">
                    <div style="top:100px">
                        <div class="spinner-grow text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-secondary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-success" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-danger" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-warning" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-dark" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <br>
                        <p style="font-size:20px" class="text-center">Creando Reporte</p>
                    </div>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="10" class="text-center pt-0 mt-0 pb-0" style="border:none"><span style="font-size:13px; font-weigth:bolder; text-transform: uppercase;">RECAUDACIONES CONSOLIDADAS - CEMENTERIO GENERAL DE HUANCAYO</span></th>
                    </tr>
                    <tr>
                        <th colspan="10" class="text-center pt-0 mt-0" style="border:none"><span style="font-size:13px; font-weigth:bolder; text-transform: uppercase;">DEL {{fechainicio |myDate}} AL {{fechafin | myDate}}</span></th>
                    </tr>
                </thead>
            </table>
            <table class="table mt-0 pt-0">
                <thead>
                    <tr>
                        <th class="text-center head" style="border-bottom:1px solid #000"><span>Fecha</span></th>
                        <th class="text-center head" style="border-bottom:1px solid #000"><span>Gravada</span></th>
                        <th class="text-center head" style="border-bottom:1px solid #000"><span>Inafecta</span></th>
                        <th class="text-center head" style="border-bottom:1px solid #000"><span>Exonerada</span></th>
                        <th class="text-center head" style="border-bottom:1px solid #000"><span>IGV</span></th>
                        <th class="text-center head" style="border-bottom:1px solid #000"><span>Facturado</span></th>
                        <th class="text-center head" style="border-bottom:1px solid #000"><span>Contado</span></th>
                        <th class="text-center head" style="border-bottom:1px solid #000"><span>Crédito</span></th>
                        <th class="text-center head" style="border-bottom:1px solid #000"><span>N. Crédito</span></th>
                        <th class="text-center head" style="border-bottom:1px solid #000"><span>T. General</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(com, i) in comprobantes">
                        <template v-if="i+1 < comprobantes.length">
                            <td class="tds">{{com.fecha_emision | myDate}}</td>
                            <td class="tds text-right">{{com.gravada| moneda}}</td>
                            <td class="tds text-right">{{com.inafecta| moneda}}</td>
                            <td class="tds text-right">{{com.exonerada| moneda}}</td>
                            <td class="tds text-right">{{com.igv| moneda}}</td>
                            <td class="tds text-right">{{com.facturado| moneda}}</td>
                            <td class="tds text-right">{{com.contado| moneda}}</td>
                            <td class="tds text-right">{{com.credito| moneda}}</td>
                            <td class="tds text-right">{{com.nota_credito| moneda}}</td>
                            <td class="tds text-right">{{com.total| moneda}}</td>
                        </template>
                        <template v-else>
                            <td style="border-top:1px solid #000; border-bottom: none;" class="subtotal font-weight-bold">TOTALES</td>
                            <td style="border-top:1px solid #000; border-bottom: none;" class="subtotal font-weight-bold text-right">{{com.gravada| moneda}}</td>
                            <td style="border-top:1px solid #000; border-bottom: none;" class="subtotal font-weight-bold text-right">{{com.inafecta| moneda}}</td>
                            <td style="border-top:1px solid #000; border-bottom: none;" class="subtotal font-weight-bold text-right">{{com.exonerada| moneda}}</td>
                            <td style="border-top:1px solid #000; border-bottom: none;" class="subtotal font-weight-bold text-right">{{com.igv| moneda}}</td>
                            <td style="border-top:1px solid #000; border-bottom: none;" class="subtotal font-weight-bold text-right">{{com.facturado| moneda}}</td>
                            <td style="border-top:1px solid #000; border-bottom: none;" class="subtotal font-weight-bold text-right">{{com.contado| moneda}}</td>
                            <td style="border-top:1px solid #000; border-bottom: none;" class="subtotal font-weight-bold text-right">{{com.credito| moneda}}</td>
                            <td style="border-top:1px solid #000; border-bottom: none;" class="subtotal font-weight-bold text-right">{{com.nota_credito| moneda}}</td>
                            <td style="border-top:1px solid #000; border-bottom: none;" class="subtotal font-weight-bold text-right">{{com.total| moneda}}</td>
                        </template>
                    </tr>
                </tbody>
            </table>
            <table v-if="comprobantes.length > 0">
                <thead>
                    <tr>
                        <th colspan="2">Resumen de Recaudaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="resumen text-right">Ventas al Contado S/.</td>
                        <td class="resumen text-right">{{comprobantes[comprobantes.length-1].contado | moneda}}</td>
                    </tr>
                    <tr>
                        <td class="resumen text-right">Ventas al Crédito S/.</td>
                        <td class="resumen text-right">{{comprobantes[comprobantes.length-1].credito | moneda}}</td>
                    </tr>
                    <tr>
                        <td class="resumen text-right">Ventas Nota Crédito S/.</td>
                        <td class="resumen text-right">{{comprobantes[comprobantes.length-1].nota_credito | moneda}}</td>
                    </tr>
                    <tr>
                        <td class="resumen text-right">Total de Ventas S/.</td>
                        <td class="resumen text-right" style="border-top:1px solid #000">{{comprobantes[comprobantes.length-1].total | moneda}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>

<script>
    // import { saveAs } from 'file-saver'
    // import * as Excel from 'exceljs/dist/exceljs.min.js'
    export default {
        data() {
            return {
                dataCuadro:'',
                loading:false,
                comprobantes:[],
                mes:momento().format('M'),
                fechainicio:'',
                fechafin:'',
                anio:'',
                meses: [{
                        nombre: 'ENERO',
                        mes: "1",
                        fechainicio: '-01-01',
                        fechafin: '-01-31'
                    },
                    {
                        nombre: 'FEBRERO',
                        mes: "2",
                        fechainicio: '-02-01',
                        fechafin: '-02-28'
                    },
                    {
                        nombre: 'MARZO',
                        mes: "3",
                        fechainicio: '-03-01',
                        fechafin: '-03-31'
                    },
                    {
                        nombre: 'ABRIL',
                        mes: "4",
                        fechainicio: '-04-01',
                        fechafin: '-04-30'
                    },
                    {
                        nombre: 'MAYO',
                        mes: "5",
                        fechainicio: '-05-01',
                        fechafin: '-05-31'
                    },
                    {
                        nombre: 'JUNIO',
                        mes: "6",
                        fechainicio: '-06-01',
                        fechafin: '-06-30'
                    },
                    {
                        nombre: 'JULIO',
                        mes: "7",
                        fechainicio: '-07-01',
                        fechafin: '-07-31'
                    },
                    {
                        nombre: 'AGOSTO',
                        mes: "8",
                        fechainicio: '-08-01',
                        fechafin: '-08-31'
                    },
                    {
                        nombre: 'SEPTIEMBRE',
                        mes: "9",
                        fechainicio: '-09-01',
                        fechafin: '-09-30'
                    },
                    {
                        nombre: 'OCTUBRE',
                        mes: "10",
                        fechainicio: '-10-01',
                        fechafin: '-10-31'
                    },
                    {
                        nombre: 'NOVIEMBRE',
                        mes: "11",
                        fechainicio: '-11-01',
                        fechafin: '-11-30'
                    },
                    {
                        nombre: 'DICIEMBRE',
                        mes: "12",
                        fechainicio: '-12-01',
                        fechafin: '-12-31'
                    },
                ]
            }
        },
        methods: {
            back(){
                history.back();
            },
            print(){
                let footer = document.getElementsByTagName('footer')[0]
                footer.style = 'display:none';
                var css = "@page { size: A4 landscape;  margin: -3rem 1rem 1rem 1rem !important;} .tds{padding:0.04rem 0.3rem 0.04rem 0.3rem !important; border:none !important; font-size:11px !important} .subtotal{font-size:11px;padding:0.1rem 0.3rem 0.1rem 0.3rem !important;} .head{padding:0.1rem 0.3rem 0.1rem 0.3rem !important;font-size:12px} .resumen{padding:0.04rem 0.3rem 0.04rem 0.3rem !important;font-size:11px}",
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

                window.print();
                footer.style = 'display:block';
            },
            getComprobantes(){
                this.loading = true;
                let fecha = this.meses.find(i  => i.mes == this.mes);
                this.fechainicio = new Date().getFullYear() + fecha.fechainicio
                this.fechafin = new Date().getFullYear() + fecha.fechafin
                this.anio = new Date().getFullYear()
                let dato = {anio:this.anio, fechainicio: this.fechainicio, fechafin: this.fechafin}
                axios.post('/api/comprobantemensual', dato).then( ({data}) => {
                    this.comprobantes = data;
                    this.loading = false;
                })
            },
        },
        created() {
            this.getComprobantes();
            document.title = 'Caja Mensual - SBH';
        }
    }
</script>
<style scoped>

</style>