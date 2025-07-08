<template>
<div>
    <div class="card" style="border:none;">
        <div class="card-header no-print">
            <div class="row">
                <div class="col-md-3">
                    <button class="btn bg-gradient-danger text-white" @click="back">Volver</button>
                    <button class="btn btn-warning text-white" @click="print"><i class="fa fa-print"></i>  Imprimir</button>
                </div>
                <div class="col-md-2">
                    <el-select v-model="idcliente" @change="changeClientes" filterable placeholder="Seleccione un cliente">
                        <el-option v-for="option in clientes" :key="option.id" :value="option.id" :label="option.razon_social"></el-option>
                    </el-select>
                </div>
                <div class="col-md-2">
                    <el-select v-model="idcredito" @change="getCronograma" filterable placeholder="Seleccione un crédito">
                        <el-option v-for="option in creditos" :key="option.id" :value="option.id" :label="option.nrocredito"></el-option>
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
            <div v-else>
                <!-- <span style="font-size:16px; font-weigth:bolder; text-transform: uppercase;">CEMENTERIO GENERAL DE HUANCAYO</span>
                <span style="font-size:16px; font-weigth:bolder; text-transform: uppercase;">CRONOGRAMA DE PAGOS DEL CRÉDITO N° 00{{credito.nrocredito}}</span> -->
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="10" class="text-center pt-0" style="border:none"><span style="font-size:16px; font-weigth:bolder; text-transform: uppercase;">CEMENTERIO GENERAL DE HUANCAYO</span></th>
                        </tr>
                        <tr>
                            <th colspan="10" class="text-center pt-1 pb-2" style="border:none"><span style="font-size:16px; font-weigth:bolder; text-transform: uppercase;">CRONOGRAMA DE PAGOS DEL CRÉDITO N° 00{{credito.nrocredito}}</span></th>
                        </tr>
                        <tr>
                            <th width="25%" class="tds"  colspan="2">Cliente</th>
                            <th width="35%" class="tds" >{{credito.contrato.nombre +' '+ credito.contrato.apellido_pat +' '+credito.contrato.apellido_mat}}</th>
                            <th width="25%" class="tds" >Precio</th>
                            <th width="15%" class="tds" >{{credito.monto_contratado | moneda}}</th>
                        </tr>
                        <tr>
                            <th class="tds"  colspan="2">Dirección</th>
                            <th class="tds" >{{credito.contrato.direccion}}</th>
                            <th class="tds" >Descuento</th>
                            <th class="tds" >{{credito.descuento | moneda}}</th>
                        </tr>
                        <tr>
                            <th class="tds"  colspan="2">Tipo de Sepultura o Servicio</th>
                            <th class="tds" >{{credito.contrato.tipo_contrato.condiciones}}</th>
                            <th class="tds" >Inicial (%)</th>
                            <th class="tds" >{{credito.inicialpercent | moneda}}</th>
                        </tr>
                        <tr>
                            <th class="tds"  colspan="2">Descripción</th>
                            <th class="tds" >{{credito.contrato.tipo_contrato.descripcion}}</th>
                            <th class="tds" >Monto Cuota Inicial</th>
                            <th class="tds" >{{credito.cuota_inicial | moneda}}</th>
                        </tr>
                        <tr>
                            <th class="tds"  colspan="2">Contrato</th>
                            <th class="tds" >00{{credito.contrato.nro}}-{{anio}}</th>
                            <th class="tds" >Monto a Financiar</th>
                            <th class="tds" >{{credito.monto_pagare | moneda}}</th>
                        </tr>
                        <tr>
                            <th class="tds"  colspan="2">Fecha Contrato</th>
                            <th class="tds" >{{credito.contrato.fecha | myDate}}</th>
                            <th class="tds" >N° de Cuotas</th>
                            <th class="tds" >{{credito.nrocuotas}}</th>
                        </tr>
                        <tr>
                            <th class="tds"  colspan="2">Fecha 1ra Cuota</th>
                            <th class="tds" >{{credito.fecha_primeracuota | myDate}}</th>
                            <th class="tds" >F. Venc. Última Cuota</th>
                            <th class="tds" >{{credito.fecha_fin | myDate}}</th>
                        </tr>
                    </thead>
                </table>
                <table class="table">
                    <thead>
                        <tr>
                            <th width="10%" class="text-center tds">N° CUOTA</th>
                            <th width="15%" class="text-center tds">FECHA DE PAGO</th>
                            <th width="35%" class="text-center tds">SALDO CAPITAL</th>
                            <th width="25%" class="text-center tds">% CUOTA</th>
                            <th width="15%" class="text-center tds">CUOTA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tds text-center">0</td>
                            <td class="tds text-center">{{credito.fecha_inicio | myDate}}</td>
                            <td class="tds text-center">S/ {{credito.monto_pagare |moneda}}</td>
                            <td class="tds"></td>
                            <td class="tds"></td>
                        </tr>
                        <tr v-for="com in credito.arraydetalle">
                            <td class="tds text-center">{{com.nro_cuota}}</td>
                            <td class="tds text-center">{{com.fecha_vencimiento | myDate}}</td>
                            <td class="tds text-center">S/ {{com.saldocapital | moneda}}</td>
                            <td class="tds text-center">{{com.cuotapercent}}</td>
                            <td class="tds text-right">S/ {{com.monto_deuda | moneda}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
                idcredito:null,
                credito:{},
                cliente:{},
                clientes:[],
                creditos:[],
                idcliente:null,
                total:0,
                fecha: momento().format('yyyy-MM-DD'),
                anio:momento().format('YYYY'),
            }
        },
        methods: {
            back(){
                history.back();
            },
            print(){
                let footer = document.getElementsByTagName('footer')[0]
                footer.style = 'display:none';
                var css = "@page { size: A4; font-family: '8pin-matrix', arial;  margin: -1rem 1rem 1rem 1rem !important;} .tds{padding:0.1rem 0.3rem 0.1rem 0.3rem !important; font-size:11px !important;}",
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

            getCronograma(){
                this.loading = true;
                this.total = 0;
                axios.get('/api/creditos/'+ this.idcredito).then( ({data}) => {
                    this.credito = data;
                    this.credito.fecha_primeracuota = momento(this.credito.fecha_inicio).add(1,'M').format('yyyy-MM-DD');
                    let saldo = this.credito.monto_pagare;
                    this.credito.arraydetalle.map(i => {
                        this.total+= parseFloat(i.monto_deuda)
                        i.saldocapital = saldo - i.monto_deuda;
                        saldo -= i.monto_deuda;
                        this.credito.inicialpercent = (this.credito.cuota_inicial * 100) / this.credito.monto_contratado;
                        i.cuotapercent = ((100 - this.credito.inicialpercent)/this.credito.nrocuotas).toFixed(3);
                    });
                    this.idcliente = data.idcliente;
                    this.loading = false;
                })
            },
            getClientes(){
                axios.get('/api/clientes').then( ({data}) => {
                    this.clientes = data;
                    this.clientes = this.clientes.filter( i  => {
                        i.creditos = i.creditos.filter(k => {
                            if (k.estado == 1) {
                                return true;
                            }
                        })
                        if (i.creditos.length > 0) {
                            return true;
                        }
                    })
                    let cliente = this.clientes.find(i => i.id == this.idcliente);
                    this.creditos = cliente.creditos;
                    this.creditos = this.creditos.filter(i => {
                        if (i.estado == 1) {
                            return true;
                        }
                    })
                })
            },
            changeClientes(){
                this.loading = true;
                this.idcredito = null;
                this.credito = {};
                let cliente = this.clientes.find(i => i.id == this.idcliente);
                this.cliente = cliente;
                this.creditos = cliente.creditos;
            },
        },
        created() {
            this.idcredito = parseInt(this.$route.params.id);
            this.getCronograma();
            this.getClientes();
            document.title = 'Cronograma - SBH';
        }
    }
</script>
<style scoped>
    .tds{
        border:1px solid #000 !important
    }
</style>