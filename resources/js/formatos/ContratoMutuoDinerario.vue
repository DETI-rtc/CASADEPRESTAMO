<template>
<div class="card">
    <div class="card-header no-print">
        <div class="col-md-6 pt-5">
        <button class="btn btn-success btn-sm no-print" @click="imprimir">Imprimir</button>
        <el-select v-model="tipo" placeholder="Seleccione">
                    <el-option
                    v-for="item in tipos"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                    </el-option>
                </el-select>
        </div>
    </div>

    <div class="card-body p-5" id="printMe">
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
            <!-- <button class="btn btn-primary btn-sm no-print" @click="imprimir">imprimir</button> -->
            <div class="btn-group no-print" role="group" aria-label="Basic example">
                <!-- <button class="btn btn-primary btn-sm " @click="imprimir">imprimir</button> -->
                <!-- <el-select v-model="tipo" placeholder="Seleccione">
                    <el-option
                    v-for="item in tipos"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                    </el-option>
                </el-select> -->
            </div>
            
        
        <h3 class="text-center"><strong>CONTRATO DE MUTUO DINERARIO</strong></h3>
        <p>
            Conste por el presente documento UN CONTRATO DE MUTUO DINERARIO que celebran de una parte la SOCIEDAD DE BENEFICENCIA DE HUANCAYO, 
            persona jurídica con R.U.C. Nº 20133670191, con domicilio real e institucional en Jr. Cusco Nº 1576 del distrito y provincia de Huancayo de la región Junín; 
            representada por su Gerente General {{ credito.repre_legal }}, identificado con DNI N° {{ credito.dni_repre }}, con domicilio en Jr. Ica Nº 684 - 
            Huancayo, quien suscribe el presente debidamente facultado a través del poder que corre inscrito en Registros Públicos; a quien en adelante se le 
            denominará EL MUTUANTE; y de la otra parte el Sr. <strong>{{ persona.paterno + ' ' + persona.materno + ' '+ persona.nombres }}</strong>, con DNI Nº <strong>{{ persona.dni }}</strong>, 
            de estado civil <strong>{{ persona.estadocivil }}</strong>, domiciliado en <strong>{{ persona. direccion + ' ' + persona.n_domicilio + ' - ' + persona.distrito}}</strong>, a quien 
            en adelante se le denominará EL MUTUATARIO; en los términos contenidos en las cláusulas siguientes:
        </p>
        <p>
            <strong>Cláusula Primera: Otorgantes</strong>
        </p>

        <table>
            <tr>
                <td class="enumerador">1.1</td>
                <td>
                    <p>
                        EL MUTUANTE es una es una persona jurídica de derecho público interno, cuenta con autonomía económica, financiera y 
                        administrativa que brinda servicios de protección social de interés público en su ámbito local provincial, a las niñas, niños, 
                        adolescentes, mujeres, personas con discapacidad y personas adultas mayores que se encuentren en situación de riesgo o vulnerabilidad, 
                        de manera complementaria a los servicios que presta el Estado, bajo los enfoques de derechos, género, intercultural e intergeneracional.
                    </p>
                
                    <p>
                        Las actividades comerciales de EL MUTUANTE, entre ellas la colocación de créditos a través de su unidad de negocios 
                        “Casa de Préstamo”, se rigen exclusivamente por el Código Civil y demás normas del sector privado.
                    </p>

                    
                </td>
            </tr>
            <tr>
                <td class="enumerador">1.2</td>
                <td>
                    <p>
                        EL MUTUATARIO es una persona natural que manifiesta su voluntad en forma expresa, directa y/o debidamente
                        representada conforme al Código Civil y demás normas vigentes. 
                    </p>
                
                    <p>
                        EL MUTUATARIO declara expresamente que con anterioridad al otorgamiento del crédito materia del presente contrato,
                        EL MUTUANTE le ha proporcionado toda la información detallada sobre las condiciones del préstamo contratado, 
                        lo cual incluye, pero sin limitarse, información respecto a tasa de interés compensatorio, tasa de interés moratorio o penalidad 
                        por pago de obligaciones en fecha posterior al vencimiento, así como sobre el monto de las comisiones, otras penalidades, gastos y 
                        tribu tos que se aplican a los mismos, inclusive los gastos judiciales y demás en los que pudiera incurrir EL MUTUANTE en caso de 
                        incumplimiento
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">1.3</td>
                <td>
                    <p>
                        EL (LOS) FIADOR(ES) Y/O AVAL(ES) y/o EL (LA) CÓNYUGE, constituyen fianza solidaria, personal, e irrevocable por plazo indeterminado por lo 
                        que aquella garantía personal permanecerá vigente, hasta que las obligaciones asumidas por EL MUTUATARIO sean totalmente canceladas.
                    </p>
            
                    <p>
                        ÉL(LOS) FIADOR(ES) Y/O AVAL(ES) Y/O LA CÓNYUGE además declaran que han sido instruidos acerca de los alcances y significados de los términos y 
                        condiciones establecidos en los formularios contractuales entregados, habiendo sido absueltas y aclaradas a satisfacción sus consultas y/o dudas, 
                        por lo que declaran tener pleno y exacto conocimiento de las condiciones establecidas en dichos documentos.
                    </p>
                    <p>
                        EL (LOS) FIADOR(ES) Y/O AVAL(ES) Y/O LA CÓNYUGE renuncia(n) al beneficio de excusión y se obligan a reembolsar a 
                        EL MUTUANTE cualquier suma que sea de cargo de EL MUTUATARIO en virtud de este contrato, incluyendo intereses compensatorios, 
                        moratorios o penalidad por pago de obligaciones en fecha posterior al vencimiento, otras penalidades, comisiones y gastos. El (LOS) 
                        FIADOR (ES) Y/O AVAL(ES) Y/O LA CÓNYUGE acepta(n) desde ya las renovaciones o prórrogas del préstamo.
                    </p>
                </td>
            </tr>
        </table>
                

        <p>
            <strong>Cláusula Segunda: Conformación del contrato</strong>
        </p>

        <table>
            <tr>
                <td class="enumerador">2.1</td>
                <td>
                    <p>
                        Son parte integrante del presente contrato: (i) la solicitud de crédito, (ii) la hoja resumen y (iii) el cronograma de pagos. 
                        Estos documentos deberán contener los datos de EL MUTUATARIO y de EL(LOS) FIADOR(ES) Y/O AVAL(ES) Y/O LA CÓNYUGE; tienen el carácter 
                        de Declaración Jurada y deberán encontrarse firmados por las personas antes indicadas. Los citados documentos deberán ser actualizarlos cuando sufran algún cambio.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">2.2</td>
                <td>
                    <p>
                        La hoja resumen es el documento mediante el cual se informa el monto del capital del crédito, la tasa de interés compensatorio efectivo anual, 
                        la tasa de interés moratoria o penalidad por pago de obligaciones en fecha posterior a su vencimiento, las comisiones, los gastos, otras penalidades y 
                        la primas del (los) seguro(s) que serán de obligación de EL MUTUATARIO. Asimismo, en dicho documento se resume alguna de las obligaciones contraídas por EL MUTUATARIO, 
                        EL (LOS) FIADOR(ES) Y/O AVAL(ES) Y/O LA CÓNYUGE, y/o por LA MUTUATARIA que sean relevantes para las partes.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">2.3</td>
                <td>
                    <p>
                        EL MUTUATARIO a la suscripción del presente contrato declaran haber recibido una hoja resumen y el cronograma de pagos del crédito otorgado, 
                        que detalla el número de cuotas de pago, el monto mensual de las mismas y otros.
                    </p>
                </td>
            </tr>
        </table>

        <p><strong>Cláusula Tercera: Objeto del contrato</strong></p>

        <table>
            <tr>
                <td class="enumerador">3.1</td>
                <td>
                    <p>
                        Por el presente se celebra el contrato de mutuo entre EL MUTUANTE y EL MUTUATARIO. Donde EL MUTUANTE entrega la cantidad de  S/. {{ credito.monto_credito  }} a 
                        EL MUTUATARIO y este se compromete a devolverlo en el plazo de <strong>{{ credito.plazo_credito }} cuotas</strong>.
                    </p>
                </td>
            </tr>
        </table>
        
        <p>
        <strong> Cláusula Cuarta: Modo de entrega del mutuo</strong>
        </p>

        <table>
            <tr>
                <td class="enumerador">4.1</td>
                <td>
                    <p>
                        EL MUTUANTE entrega el monto acordado en la Cláusula Tercera al momento de suscripción del presente contrato, el cual se entrega en moneda nacional a 
                        través de <span v-if="tipo == 0">CCI N° {{persona.cci}} banco {{persona.cci_banco}}</span> <span v-if="tipo == 1">CHEQUES NO NEGOCIABLES DE LA ENTIDAD</span>  <span v-if="tipo == 3">CUENTA <strong>SCOTIABANK NRO {{persona.cuenta_scotiabank}}</strong></span> a nombre de EL MUTUATARIO, dejándose como constancia de conformidad del acto 
                        realizado la suscripción del presente documento.
                    </p>
                </td>
            </tr>
        </table>
        
        <p>
            <strong>Cláusula Quinta: Obligaciones y pago del mutuo</strong>
        </p>

        <table>
            <tr>
                <td class="enumerador">5.1</td>
                <td>
                    <p>
                        El crédito otorgado será cancelado mediante cuotas mensuales que incluyen el capital, 
                        interés compensatorio, comisiones, seguros, tributos y gastos que EL MUTUANTE aplique 
                        según lo establecido en la hoja resumen. 
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">5.2</td>
                <td>
                    <p>
                        El crédito será pagado por EL MUTUATARIO en efectivo, en la misma moneda del crédito y en el plazo indicado en el cronograma de pagos respectivo. 
                        En caso de resultar feriado o no laborable el inmediato día útil posterior a la fecha de vencimiento.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">5.3</td>
                <td>
                    <p>
                        En caso de incumplimiento de una o más cuotas de amortización, EL MUTUATARIO pagará el interés moratorio o penalidad por pago de obligaciones 
                        con fecha posterior al vencimiento, en ningún caso ambos en el mismo momento.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">5.4</td>
                <td>
                    <p>
                        En caso de que no se tenga por cumplida cualquiera de las obligaciones del EL MUTUATARIO, EL MUTUANTE quedará habilitado para exigir el pago íntegro de 
                        lo adeudado en dicho momento y desarrollar las acciones pertinentes a fin de satisfacer su crédito.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">5.5</td>
                <td>
                    <p>
                        En caso de una cancelación anticipada se realizará después del sexto mes.
                    </p>
                </td>
            </tr>
        </table>
        
        <p>
            <strong>Cláusula Sexta: Características del mutuo</strong>
        </p>

        <table>
        <tr>
            <td class="enumerador">6.1</td>
            <td>
                <p>
                    El presente contrato se suscribe a título oneroso por lo cual existirá un 
                    pago de intereses compensatorios por el desembolso realizado por EL MUTUANTE.
                </p>
            </td>
        </tr>
        <tr>
            <td class="enumerador">6.2</td>
            <td>
                <p>
                    La tasa de interés pactada es del <strong>{{ cronograma.tasa_efe_anu }}%</strong> del total del monto entregado, dicha suma equivale a <strong>S/. {{ credito.monto_multiplicado }}</strong>, 
                    os cuales para efecto de pago serán fraccionados en forma proporcional a la cantidad de armadas pactadas.
                </p>
            </td>
        </tr>
        <tr>
            <td class="enumerador">6.3</td>
            <td>
                <p>
                    En caso de incumplimiento por parte de EL MUTUATARIO del cronograma de pagos establecidos por las partes, se 
                    procederá al cobro de intereses moratorios de forma automática desde el primer día del incumplimiento a una tasa de <strong>2%</strong>  diaria sobre el monto que se tenga por deuda en ese momento.
                </p>
            </td>
        </tr>
        
        </table>
        
        
        
        <p>
            <strong>Cláusula Séptima: Garantías</strong>
        </p>

        <table>
            <tr>
                <td class="enumerador">7.1</td>
                <td>
                    <p>
                        EL FIADOR (ES) SOLIDARIO (S) Y/O AVAL (ES) tiene participación en el presente contrato, solo cuando EL 
                        MUTUATARIO incumpla con la obligación contraída frente a EL MUTUANTE, en dicho caso responderá con 
                        su patrimonio personal, es decir sin beneficio de excusión, para que EL MUTUANTE pueda realizar el cobro 
                        de su crédito.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">7.2</td>
                <td>
                    <p>
                        La vigencia de la presente garantía personal se extenderá por todo el periodo que se tenga por vigente el presente contrato.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">7.3</td>
                <td>
                    <p>
                        EL MUTUATARIO (o el GARANTE, según corresponda) declaran que el cumplimiento de la deuda 
                        objeto del presente contrato se encuentra garantizada con un Pagaré a la orden de EL MUTUANTE, 
                        para garantizar todas las obligaciones asumidas por EL MUTUATARIO, el mismo que autoriza a EL 
                        MUTUANTE a completar el título valor según los términos y condiciones establecidas en el presente contrato

                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">7.4</td>
                <td>
                    <p>
                        En ese sentido, EL MUTUATARIO y FIADOR (ES) SOLIDARIO (S) Y/O AVAL (ES) Y/O EL(LA)CÓNYUGE autorizan a EL MUTUANTE a completar el Pagaré en los términos siguientes: 
                        (i) La fecha de emisión será, a criterio de EL MUTUANTE, la fecha en la que se efectuó el desembolso, o aquella en la cual EL MUTUANTE proceda a hacer uso de las 
                        facultades otorgadas por el presente documento; (ii) La fecha de vencimiento será, a criterio de EL MUTUANTE, la fecha de vencimiento del crédito representado en el 
                        Pagaré, a la vista o la fecha que EL MUTUANTE dé por vencido todos los plazos de acuerdo a lo señalado en el presente contrato; (iii) El importe total que EL MUTUANTE 
                        deberá consignar será, a decisión de EL MUTUANTE, el equivalente al: (a) saldo del capital en la fecha de emisión del Pagaré; o (b) el importe total adeudado por EL MUTUATARIO 
                        en virtud al presente contrato, en la fecha de llenado del Pagaré; y (iv) Las tasa de interés compensatorio y moratorio serán las fijadas por EL MUTUANTE en la hoja resumen 
                        que forma parte del presente contrato.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">7.5</td>
                <td>
                    <p>
                        EL MUTUATARIO y EL (LOS) FIADO R (ES) SOLIDARIO(S) Y/O AVAL(ES) Y/O EL(LA)CÓNYUGE autorizan expresamente a EL MUTUANTE para que sin límite alguno puedan efectuar el 
                        endoso del Pagare emitido en sus diferentes modalidades, así como efectuar la cesión de derechos o negociarlo libremente de acuerdo a sus necesidades.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">7.6</td>
                <td>
                    <p>
                        EL MUTUATARIO y FIADOR (ES) SOLIDARIO (S) Y/O AVAL (ES) Y/O EL(LA)CÓNYUGE aceptan y dan por válidas todas las 
                        renovaciones y prórrogas totales o 
                        parciales que se anoten en el Pagaré, aun cuando no estén suscritas por EL MUTUATARIO y FIADOR (ES) 
                        SOLIDARIO (S) Y/O AVAL (ES) Y/O EL(LA)CÓNYUGE. Del mismo modo, EL MUTUATARIO deja expresa constancia que a la firma 
                        del presente contrato ha recibido de EL MUTUANTE una copia del Pagaré incompleto.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">7.7</td>
                <td>
                    <p>
                        En la emisión o transferencia del Pagaré, EL MUTUANTE podrá sustituir la firma autógrafa de sus representantes por su firma impresa, digitalizada o por cualquier otro medio de seguridad gráfico, mecánico o electrónico.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">7.8</td>
                <td>
                    <p>
                        La emisión y/o entrega de títulos valores a favor de EL MUTUANTE, su renovación o prórroga, (i) no producirá novación de las obligaciones asumidas 
                        por EL MUTUATARIO, salvo que expresamente se acuerde lo contrario; y/o, (ii) en ningún caso determina la extinción de las obligaciones primitivas, aun cuando dichos títulos se hubiesen perjudicado por causa imputable a EL MUTUANTE.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">7.9</td>
                <td>
                    <p>
                        Queda establecido también que el PAGARÉ forma parte integrante del presente contrato y que, no obstante, ocurra su deterioro, pérdida o destrucción, el presente contrato mantiene su íntegra validez. 
                        Es voluntad de las partes que dicho Pagaré sirva de instrumento para las gestiones de cobranzas a que hubiera lugar, todo ello sin perjuicio del derecho de EL MUTUANTE de reclamar el incumplimiento de la obligación causal.
                    </p>
                </td>
            </tr>
            
        </table>
        
        <p>
            <strong>Cláusula Octava: Las afectaciones</strong>
        </p>
        <table>
            <tr>
                <td class="enumerador">8.1</td>
                <td>
                    <p>
                    Las partes acuerdan que el pago de los gastos que se generen a raíz de la realización del presente contrato es de cargo de EL MUTUATARIO (se puede acordar lo contrario).
                    </p>
                </td>
            </tr>
            <tr>
                <td class="enumerador">8.2</td>
                <td>
                    <p>
                        En el caso que, con motivo del pago de los intereses compensatorios, EL MUTUANTE se encuentre 
                        obligado y exigido al pago de los tributos que correspondieran, estos serán asumidos o reembolsados 
                        por EL MUTUATARIO.
                    </p>
                </td>
            </tr>
        </table>
        
        
        <p>
            <strong>Cláusula Novena: Fin del contrato</strong>
        </p>
        <table>
            <tr>
                <td class="enumerador">9.1</td>
                <td>
                    <p>
                        Los efectos del presente contrato concluirán únicamente con la devolución del monto entregado por EL MUTUANTE a EL MUTUATARIO, más los intereses pactados. Se aceptará el pago adelantado de las armadas pactadas sin que ello afecte el monto de los intereses acordados en la presente.
                    </p>
                </td>
            </tr>
            
        </table>
        
        <p>
            <strong>Cláusula Décima: Comunicación de las partes</strong>
        </p>
        <table>
            <tr>
                <td class="enumerador">10.1</td>
                <td>
                    <p>
                        Los domicilios de las partes suscribientes del presente contrato se encuentran en 
                        su parte introductoria, los cuales son señalados para efectos de la validez de las 
                        comunicaciones o notificaciones entre los suscribientes. Para los efectos del cambio de 
                        domicilio de alguno de los suscribientes de la presente, este solo será oponible en tanto 
                        las demás partes fueran notificadas mediante carta notarial acusando recibo.
                    </p>
                </td>
            </tr>
            
        </table>
    
        <p>
            Las partes, conformes con lo establecido en el presente contrato lo suscriben en la ciudad de Huancayo, a los {{ credito.dia }} días del mes de {{ credito.mes }} de {{ credito.anio }}.
        </p>

        <table class="w-100 text-left">
            <tr >
                <td style="width:50%">
                    <div class="mb-5" style="margin-bottom:90px !important;">
                        <span><strong>EL CLIENTE</strong></span>
                    </div>
                    <br>
                    <span style="margin-top:50px; padding-top:50px">_________________________</span>
                    <br>
                    <span>Nombres: {{ persona.paterno + ' ' + persona.materno + ' ' +persona.nombres }}</span>
                    <br>
                    <span>DNI: {{ persona.dni }}</span>
                </td>
                <td style="width:50%">
                    <div class="mb-5" style="margin-bottom:90px !important;">
                    <span><strong>CÓNYUGE DEL CLIENTE</strong></span>
                    </div>
                    <br>
                    <span>_________________________</span>
                    <br>
                    <span>Nombres: _________________</span>
                    <br>
                    <span>DNI: _________________</span>
                </td>
            </tr>

            <tr>
                <td class="pt-5">
                    <div class="mb-5" style="margin-bottom:50px !important;">
                    <span><strong>EL AVAL</strong></span>
                    </div>
                    <br>
                    <span>_________________________</span>
                    <br>
                    <span>Nombres: _________________</span>
                    <br>
                    <span>DNI: _________________</span>
                </td>
                <td class="pt-5">
                    <div class="mb-5" style="margin-bottom:50px !important;">
                    <span><strong>CÓNYUGE DEL AVAL</strong></span>
                    </div>
                    <br>
                    <span>_________________________</span>
                    <br>
                    <span>Nombres: _________________</span>
                    <br>
                    <span>DNI: _________________</span>
                </td>
            </tr>
        </table>
       </div>
    </div>
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
                cci:'',
                cci_banco:'',
                dni :'',
                nombres :'',
                paterno :'',
                materno :'',
                sexo :'',
                fec_nac :'',
                idestadocivil :'',
                estadocivil:'',
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
                distrito:'',
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
                repre_legal:'',
                dni_repre:'',
            }),

            cronograma : new Form({
                id :'',
                monto_multiplicado:'',
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
            // let secret = await this.getOrden();
            let footer = document.getElementsByTagName('footer')[0]
            footer.style = 'display:none';
            // var css = '.thismedio{font-size:11pt;}*{ font-size:11pt;line-height:11pt !important; letter-spacing: 4pt !important;}@media print{  @page { font:10pt "Courier New";margin: 152px 0 0 0; }}',
            var css = '@page { size: A4 portrait;}   .blanco td{padding:0.55rem !important;} .espacio{padding:2rem !important;}',
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
    .enumerador {
        vertical-align: top;
        width:10%;
        text-align: center;
    }
    

</style>