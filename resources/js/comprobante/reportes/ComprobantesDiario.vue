<template>
<div>
    <div class="card" style="border:none;">
        <div class="card-header no-print">
            <div class="row">
                <div class="col-md-3">
                    <button class="btn bg-gradient-danger text-white" @click="back">Volver</button>
                    <button class="btn btn-warning text-white" @click="print"><i class="fa fa-print"></i>  Imprimir</button>
                </div>
                <div class="col-md-6">
                    <el-select v-model="anio" filterable placeholder="Seleccione" @input="getComprobantes">
                            <el-option
                              v-for="item in periodos"
                              :key="item.año"
                              :label="item.año"
                              :value="item.año">
                            </el-option>
                          </el-select>
                          <el-select v-model="mes" filterable placeholder="Seleccione" @input="getComprobantes">
                            <el-option
                              v-for="item in meses"
                              :key="item.value"
                              :label="item.label"
                              :value="item"
                            >
                            </el-option>
                          </el-select>
                          <el-select v-model="tipo" filterable placeholder="Seleccione" @input="getComprobantes">
                            <el-option
                              v-for="item in tipos"
                              :key="item.value"
                              :label="item.label"
                              :value="item"
                            >
                            </el-option>
                          </el-select>
                    <!-- <input type="date" class="form-control" v-model="fecha" @change="getComprobantes"> -->
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
                        <th colspan="10" class="text-center pt-0" style="border:none"><span style="font-size:16px; font-weigth:bolder; text-transform: uppercase;">CASA DE PRESTAMOS HUANCAYO</span></th>
                    </tr>
                    <tr>
                        <th colspan="10" class="text-center pt-2" style="border:none"><span style="font-size:16px; font-weigth:bolder; text-transform: uppercase;">BOLETAS DE VENTA POR INTERESES DE {{ mes.label }} {{anio}}</span></th>
                    </tr>
                </thead>
            </table>
            <table class="table mt-2" style="width:60%">
                <thead>
                    <tr>
                        <th width="5%" class="text-center p-0">Doc</th>
                        <th width="10%" class="text-center p-0">Serie</th>
                        <th width="10%" class="text-center p-0">N° Inc</th>
                        <th width="10%" class="text-center p-0">N° Fin</th>
                        <th width="15%" class="text-center p-0">Can. Doc.</th>
                        <th width="15%" class="text-center p-0">Efectivo</th>
                        <th width="15%" class="text-center p-0">Crédito</th>
                        <th width="20%" class="text-center p-0">Total</th>
                    </tr>
                </thead>
                <tbody style="border:none">
                    <tr v-for="resu in documentos_resumen">
                        <td style="font-size:11px; border:none; padding:0.1rem;text-align:center" >{{resu.doc}}</td>
                        <td style="font-size:11px; border:none; padding:0.1rem;text-align:center" >{{resu.serie}}</td>
                        <td style="font-size:11px; border:none; padding:0.1rem; text-align:center" >{{inicio}}</td>
                        <td style="font-size:11px; border:none; padding:0.1rem; text-align:center" >{{fin}}</td>
                        <td style="font-size:11px; border:none; padding:0.1rem; text-align:center" >{{resu.cantidad}}</td>
                        <td style="font-size:11px; border:none; padding:0.1rem" class="text-right" >{{resu.efectivo | moneda}}</td>
                        <td style="font-size:11px; border:none; padding:0.1rem" class="text-right" >{{resu.credito | moneda}}</td>
                        <td style="font-size:11px; border:none; padding:0.1rem" class="text-right" >{{resu.total | moneda}}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th class="text-center p-0"><span>N°</span></th>
                        <th class="text-center p-0"><span>Documento</span></th>
                        <th class="text-center p-0"><span>Ruc</span></th>
                        <th class="text-center p-0"><span>Cliente</span></th>
                        <th class="text-center p-0"><span>Sub total</span></th>
                        <th class="text-center p-0"><span>IGV</span></th>
                        <th class="text-center p-0"><span>Total</span></th>
                        <th class="text-center p-0"><span>Nota. Crédito</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(com, i) in comprobantes">
                        <td class="tds" >{{i+1}}</td>
                        <td class="tds" >{{com.serie}}-{{com.correlativo}}</td>
                        <td class="tds">{{com.numerodoc}}</td>
                        <td class="tds" >{{com.cliente}}</td>
                        <td class="tds text-right" >{{parseFloat(com.op_gravadas) | moneda}}</td>
                        <td class="tds text-right" >{{parseFloat(com.igv) | moneda}}</td>
                        <template v-if="com.tipo_comprobante_id == '07'">
                        <td class="tds text-right" ></td>
                        <td class="tds text-right" >{{parseFloat(com.total) | moneda}}</td>
                        </template>
                        <template v-else>
                            <td class="tds text-right" >{{parseFloat(com.total) | moneda}}</td>
                        <td class="tds text-right" ></td>
                            
                        </template>
                        
                        <!-- <template v-if="com.tipo_comprobante_id != '07'">
                            <td v-if="com.forma_pago == 'Credito'" class="tds text-right"></td>
                            <td v-if="com.forma_pago == 'Credito'" class="tds text-right">{{com.total | moneda}}</td>
                            <td v-if="com.forma_pago == 'Contado'" class="tds text-right">{{com.total | moneda}}</td>
                            <td v-if="com.forma_pago == 'Contado'" class="tds text-right"></td>
                            <td v-if="com.op_exoneradas + com.op_inafectas > 0" class="tds text-right">{{parseFloat(com.op_inafectas) + parseFloat(com.op_exoneradas) | moneda}}</td>
                            <td v-else class="tds text-right"></td>
                            <td class="tds text-right"></td>
                        </template>
                        <template v-else>
                            <td v-if="com.forma_pago == 'Credito'" class="tds text-right"></td>
                            <td v-if="com.forma_pago == 'Credito'" class="tds text-right"></td>
                            <td v-if="com.forma_pago == 'Contado'" class="tds text-right"></td>
                            <td v-if="com.forma_pago == 'Contado'" class="tds text-right"></td>
                            <td v-if="com.op_exoneradas + com.op_inafectas > 0" class="tds text-right">{{parseFloat(com.op_inafectas) + parseFloat(com.op_exoneradas) | moneda}}</td>
                            <td v-else class="tds text-right"></td>
                            <td class="tds text-right" v-if="com.tipo_comprobante_id == '07'">{{com.total | moneda}}</td>
                        </template> -->
                    </tr>
                    <tr>
                        <td class="tds text-right font-weight-bold" colspan="4">SUB TOTAL</td>
                        <td class="tds text-right font-weight-bold">{{total_sub | moneda}}</td>
                        <td class="tds text-right font-weight-bold">{{total_igv | moneda}}</td>
                        <td class="tds text-right font-weight-bold">{{total_contado | moneda}}</td>
                        <td class="tds text-right font-weight-bold">{{total_nota_credito | moneda}}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th class="text-center" colspan="4" style="border:none">RESUMEN</th>
                    </tr>
                </thead>
                <tbody style="border:none">
                    <tr>
                        <td style="border:none; padding:0.1rem" class="text-right tds">Total de Comprobantes</td>
                        <td style="border:none; padding:0.1rem" class="text-right tds">{{totalcomprobantes | moneda}}</td>
                        <td style="border:none; padding:0.1rem" class="text-right tds">Total de Creditos</td>
                        <td style="border:none; padding:0.1rem" class="text-right tds">{{total_credito | moneda}}</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:0.1rem" class="text-right tds">Total de Notas de Crédito</td>
                        <td style="border:none; padding:0.1rem" class="text-right tds">{{total_nota_credito | moneda}}</td>
                        <td style="border:none; padding:0.1rem" class="text-right tds">Total de Exon/Inaf</td>
                        <td style="border:none; padding:0.1rem" class="text-right tds">{{total_inaf_exo | moneda}}</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:0.1rem" class="text-right tds">Total en Efectivo</td>
                        <td style=" border-bottom: none;border-top:1px solid #000; padding:0.1rem" class="text-right font-weight-bold tds">{{total_efectivo | moneda}}</td>
                        <td style="border:none; padding:0.1rem" class="text-right tds">Total General</td>
                        <td style=" border-bottom: none;border-top:1px solid #000; padding:0.1rem" class="text-right font-weight-bold tds">{{total_general | moneda}}</td>
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
    import moment from 'moment'
    export default {
        data() {
            return {
                dataCuadro:'',
                inicio:'',
                fin:'',
                loading:false,
                total_contado : 0,
                total_credito : 0,
                total_inaf_exo : 0,
                totalcomprobantes: 0,
                total_factura : 0,
                total_boleta : 0,
                total_credito : 0,
                total_igv : 0,
                total_sub : 0,
                total_debito : 0,
                total_efectivo : 0,
                total_general: 0,
                total_nota_credito:0,
                documentos:[],
                comprobantes:[],
                documentos_resumen:[],
                fecha: momento().format('yyyy-MM-DD'),
                periodos:[],
                meses:[
                {
                  label:'Enero',
                  value:1
                },
                {
                  label:'Febrero',
                  value:2
                },
                {
                  label:'Marzo',
                  value:3
                },
                {
                  label:'Abril',
                  value:4
                },
                {
                  label:'Mayo',
                  value:5
                },
                {
                  label:'Junio',
                  value:6
                },
                {
                  label:'Julio',
                  value:7
                },
                {
                  label:'Agosto',
                  value:8
                },
                {
                  label:'Setiembre',
                  value:9
                },
                {
                  label:'Octubre',
                  value:10
                },
                {
                  label:'Noviembre',
                  value:11
                },
                {
                  label:'Diciembre',
                  value:12
                },
              ],
              tipo:'',
              tipos:[
                {
                  label:'Nro Correlativo',
                  value:1
                },
                {
                  label:'Alfabetico',
                  value:2
                },
                
              ],
              mes:'',
              anio:moment().format('YYYY'),
            }
        },
        methods: {
            back(){
                history.back();
            },
            print(){
                let footer = document.getElementsByTagName('footer')[0]
                footer.style = 'display:none';
                var css = "@page { size: A4; font-family: '8pin-matrix', arial;  margin: 0.944rem 0.944rem 0.944rem 0.944rem !important;} .tds{padding:0.1rem 0.3rem 0.1rem 0.3rem !important; font-size:11px !important}",
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

            // exportExcel() {
            //     const workbook = new Excel.Workbook()
            //     const worksheet = workbook.addWorksheet("COMPROMISOS")
            //     worksheet.mergeCells('A1', 'AD1');
            //     worksheet.getCell('A1').value = 'COMPROMISOS DE GASTOS DEL MES DE '+ this.mes;
            //     worksheet.getCell('A1').alignment = {
            //         vertical: 'middle',
            //         horizontal: 'center'
            //     };
            //     let data = [];
            //     for (const i in this.dataCuadro[0]) {
            //         data.push(i);
            //     }
            //     worksheet.getRow(2).values = data;
            //     let columnas  = []
            //     data.map (i => {
            //         if (i == 'PARTIDA') {
            //             let columna = {
            //                 key:i,
            //                 width:70,
            //             }
            //             columnas.push(columna)
            //         } else if (i == 'TOTAL GENERAL') {
            //             let columna = {
            //                 key:i,
            //                 width:20,
            //             }
            //             columnas.push(columna)
            //         } else {
            //             let columna = {
            //                 key:i,
            //                 width:14,
            //             }
            //             columnas.push(columna)
            //         }
            //     })
            //     worksheet.columns = columnas;
            //     var borderStyles = {
            //         top: {
            //             style: "thin"
            //         },
            //         left: {
            //             style: "thin"
            //         },
            //         bottom: {
            //             style: "thin"
            //         },
            //         right: {
            //             style: "thin"
            //         }
            //     };
            //     this.dataCuadro.map( i => {
            //         worksheet.addRow(i,);
            //     })
            //     worksheet.eachRow({
            //         includeEmpty: true
            //     }, function (row, rowNumber) {
            //         let rowexcel = '';
            //         row.eachCell({
            //             includeEmpty: true
            //         }, function (cell, colNumber) {
            //             // cell.border = borderStyles;
            //             if (cell.value == 'PARTIDA') {
            //                 cell.value = 'PARTIDA PRESUPUESTAL DEL GASTO'
            //             }
            //             if (cell.value.substr(0,5) == 'TOTAL' || rowexcel == rowNumber || rowNumber == 2) {
            //                 rowexcel = rowNumber;
            //                 cell.border = borderStyles
            //             } else if(rowNumber > 2) {
            //                 cell.border = {left:{style:'thin'},right:{style:'thin'}};
            //             }
            //             if (rowNumber >= 3 && colNumber >= 2) {
            //                 let value = parseFloat(cell.value).toFixed(2);
            //                 const formatoMexico = (data) => {
            //                     const exp = /(\d)(?=(\d{3})+(?!\d))/g;
            //                     const rep = '$1,';
            //                     return data.toString().replace(exp,rep);
            //                 }
            //                 if (cell.value == 0) {
            //                     cell.value = '-'
            //                 } else {
            //                     cell.value = formatoMexico(value)
            //                 }
            //                 cell.alignment = { vertical: 'middle', horizontal: 'right' }
            //             }
            //         });
            //     });
            //     workbook.xlsx.writeBuffer().then((buf) => {
            //         saveAs(new Blob([buf]), `GASTOS_DEL_MES_DE_${this.mes}.xlsx`);
            //     });
            // },
            getComprobantes(){
                this.loading = true;
                let dato = {anio:this.anio ,mes: this.mes.value,tipo: this.tipo.value}
                this.total_contado = 0;
                this.total_credito = 0;
                this.total_inaf_exo = 0;
                this.total_factura = 0;
                this.total_boleta = 0;
                this.total_nota_credito = 0;
                this.total_debito = 0;
                this.total_sub = 0;
                this.total_igv = 0;
                this.total_efectivo = 0;
                this.total_general = 0;
                this.totalcomprobantes = 0;
                this.documentos = [];
                this.documentos_resumen= [];
                axios.post('/api/comprobantediario', dato).then( ({data}) => {
                    this.comprobantes = data.comprobante;
                    this.inicio=data.minimo;
                    this.fin=data.maximo;
                    let serie = '';
                    this.comprobantes.length > 0 ? serie = this.comprobantes[0].serie : serie = '';
                    this.documentos = this.comprobantes.map(item => item.serie)
                        .filter((value, index, self) => self.indexOf(value) === index)
                    this.comprobantes.map( i  => {
                        this.documentos.map( j => {
                            let cantidad = 0;
                            if (j == i.serie) {
                                cantidad += 1;
                                let encontro = this.documentos_resumen.find(d => d.serie == j);
                                if (!encontro) {
                                    let credito = 0;
                                    let contado = 0;
                                    i.forma_pago == 'Credito' ? credito += parseFloat(i.total) : contado += parseFloat(i.total);
                                    this.documentos_resumen.push({cantidad: cantidad,doc: i.tipo_comprobante_id, serie: i.serie, inicio: i.correlativo, fin: i.correlativo, efectivo:contado, credito: credito, total:parseFloat(i.total)})
                                } else {
                                    this.documentos_resumen.find(d => {
                                        if (d.serie == encontro.serie) {
                                            d.fin = i.correlativo;
                                            i.forma_pago == 'Credito' ? d.credito += parseFloat(i.total) : d.efectivo += parseFloat(i.total)
                                            d.total += parseFloat(i.total);
                                            d.cantidad += cantidad;
                                        }
                                    })
                                }
                                // console.log(i,j);
                            } else {
                                cantidad = 0;
                            }
                        });
                        i.tipo_comprobante_id == '03' ? this.total_igv += parseFloat(i.igv):this.total_igv += 0;
                        i.tipo_comprobante_id == '03' ? this.total_sub += parseFloat(i.op_gravadas):this.total_sub += 0;
                        i.forma_pago == 'Contado' && i.tipo_comprobante_id != '07' ? this.total_contado += parseFloat(i.total) : this.total_contado += 0;
                        i.forma_pago == 'Credito' && i.tipo_comprobante_id != '07' ? this.total_credito += parseFloat(i.total) : this.total_credito += 0;
                        this.totalcomprobantes += parseFloat(i.total);
                        this.total_inaf_exo += parseFloat(i.op_inafectas) + parseFloat(i.op_exoneradas);
                        i.tipo_comprobante_id == '01' ? this.total_factura += parseFloat(i.total) : this.total_factura += 0;
                        i.tipo_comprobante_id == '03' ? this.total_boleta += parseFloat(i.total) : this.total_boleta += 0;
                        i.tipo_comprobante_id == '07' ? this.total_nota_credito += parseFloat(i.total) : this.total_nota_credito += 0;
                        i.tipo_comprobante_id == '08' ? this.total_debito += parseFloat(i.total) : this.total_debito += 0;
                    })
                    this.totalcomprobantes=this.total_contado;
                    this.total_efectivo = this.totalcomprobantes - this.total_nota_credito;
                    this.total_general = this.total_efectivo + this.total_credito;
                    // this.comprobantes.arraydetallerecaudacion.map( i=> {
                    //     i.total = i.importe;
                        
                    //     if (i.idmeta == idmeta) {
                    //         i.total+=i.importa
                    //     }
                    // })
                    this.loading = false;
                })
            },
            loaddata(){
                axios.get("/api/periodos")
              .then(({ data }) => (this.periodos = data)).catch(error => { this.error = error.response });
              this.mes.value=moment().format('M');
            },

            // irReporte(id) {
            //     this.$router.push('/tesoreria/ingresosdiarios/' + id);
            // },
            
        },
        created() {
            // let idingreso = this.$route.params.id;
            this.loaddata();
            document.title = 'Caja Diario - SBH';
        }
    }
</script>
<style scoped>

</style>