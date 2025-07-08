<style scoped>
    tbody > tr > td {
        text-align: center;
        border: 1px solid #000;
    }
    .titulo {
        font-weight: bold;
        font-size: 12px;
    }
    .cabecera {
        font-weight: bold;
        font-size: 12px;
        background-color:#c0cbd0
    }
    .relleno {
        font-size: 11px;
    }
</style>
<template>
    <div class="card" style="border:none;">
        <div class="card-header no-print">
            <div class="row">
                <div class="col-md-3">
                    <!-- <button class="btn bg-gradient-danger" @click="back">Volver</button> -->
                    <button class="btn btn-warning" @click="print">Imprimir</button>
                </div>
            </div>
            
        </div>
        <div  class="card-body">
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
                        <p style="font-size:19px" class="text-center">Creando Orden de Pago</p>
                    </div>
                </div>

            </div>
            <div class="row" v-else>
                <span class="text-right titulo">N° Contrato: 00{{contrato.nro}}</span>
                <span class="text-center titulo">{{contrato.tipo_contrato.descripcion}}</span>
                <span class="text-justify">Conste por el presente contrato que celebran la Sociedad de Beneficencia Huancayo, a quien en adelante se le denominará,
                    LA BENEFICENCIA, con RUC No.20133670191, con domicilio legal en el Jr. Cusco No.1576 Huancayo, debidamente
                    representado por su Gerente General RIVAS SANTA MARIA JOSE ANGEL, identificado con Documento Nacional de Identidad Nro.
                    40824929 y de otra parte, a quien se denominará EL TITULAR.</span>
                <table style="border:1px solid #000" class="ml-2 mr-2">
                    <tbody>
                        <tr>
                            <td class="cabecera" colspan="10">DATOS DEL TITULAR</td>
                        </tr>
                        <tr>
                            <td class="titulo" colspan="2">Nombre/s del Titular</td>
                            <td class="titulo" colspan="5">Apellido Paterno del Titular</td>
                            <td class="titulo" colspan="3">Apellido Materno del Titular</td>
                        </tr>
                        <tr>
                            <td class="relleno" colspan="2">{{contrato.nombre}}</td>
                            <td class="relleno" colspan="5">{{contrato.apellido_pat}}</td>
                            <td class="relleno" colspan="3">{{contrato.apellido_mat}}</td>
                        </tr>
                        <tr>
                            <td class="titulo">DNI/Documento</td>
                            <td class="titulo">RUC</td>
                            <td class="titulo" colspan="5">Fecha de Nacimiento</td>
                            <td class="titulo">Estado Civil</td>
                            <td class="titulo">Nacionalidad</td>
                            <td class="titulo">Teléfono Particular</td>
                        </tr>
                        <tr>
                            <td class="relleno">{{contrato.dni}}</td>
                            <td class="relleno">{{contrato.ruc}}</td>
                            <td class="relleno" colspan="5">{{contrato.fecha_nac | myDate}}</td>
                            <td class="relleno">{{contrato.estado_civil}}</td>
                            <td class="relleno">{{contrato.nacionalidad}}</td>
                            <td class="relleno">{{contrato.telefono}}</td>
                        </tr>
                        <tr>
                            <td class="titulo" colspan="8">Dirección Particular (Av./Jr./Calle/Mz.)</td>
                            <td class="titulo">Dpto/Int</td>
                            <td class="titulo">Teléfono Celular</td>
                        </tr>
                        <tr>
                            <td class="relleno" colspan="8">{{contrato.direccion}}</td>
                            <td class="relleno">{{contrato.dpto}}</td>
                            <td class="relleno">{{contrato.celular}}</td>
                        </tr>
                        <tr>
                            <td class="titulo" colspan="7">Urbanización/Etapa/Sector</td>
                            <td class="titulo">Departamento</td>
                            <td class="titulo">Credo</td>
                            <td class="titulo">Sexo</td>
                        </tr>
                        <tr>
                            <td class="relleno" colspan="7">{{contrato.urb_etapa_sector}}</td>
                            <td class="relleno">{{contrato.departamento}}</td>
                            <td class="relleno">{{contrato.credo}}</td>
                            <td class="relleno">{{contrato.sexo}}</td>
                        </tr>
                        <tr>
                            <td class="titulo" colspan="7">Ocupación/Profesión/Cargo Titular/Nombre Referencia</td>
                            <td class="titulo" colspan="3">Centro de Trabajo Titular/Relación con Referencia</td>
                        </tr>
                        <tr>
                            <td class="relleno" colspan="7">{{contrato.ocupacion}}</td>
                            <td class="relleno" colspan="3">{{contrato.centro_trabajo}}</td>
                        </tr>
                        <tr>
                            <td class="titulo" colspan="7">Dirección de Centro de Trabajo Titular o Referencia</td>
                            <td class="titulo">Provincia</td>
                            <td class="titulo">Distrito</td>
                            <td class="titulo">Teléfono de Trabajo</td>
                        </tr>
                        <tr>
                            <td class="relleno" colspan="7">{{contrato.direccion_trabajo}}</td>
                            <td class="relleno" v-if="contrato.provincias">{{contrato.provincias.description}}</td>
                            <td class="relleno" v-else></td>
                            <td class="relleno" v-if="contrato.distritos">{{contrato.distritos.description}}</td>
                            <td class="relleno" v-else></td>
                            <td class="relleno">{{contrato.telefono_trabajo}}</td>
                        </tr>
                        <tr>
                            <td class="titulo">Dirección de Recaudación</td>
                            <td class="titulo">Particular</td>
                            <td v-if="contrato.tipo_direccion_recaudacion == 'Particular'" class="relleno">X</td>
                            <td v-else="contrato.tipo_direccion_recaudacion == 'Particular'" class="relleno"></td>
                            <td class="titulo">Trabajo</td>
                            <td v-if="contrato.tipo_direccion_recaudacion == 'Trabajo'" class="relleno">X</td>
                            <td v-else="contrato.tipo_direccion_recaudacion == 'Trabajo'" class="relleno"></td>
                            <td class="titulo">Otro</td>
                            <td v-if="contrato.tipo_direccion_recaudacion == 'Otro'" class="relleno">X</td>
                            <td v-else="contrato.tipo_direccion_recaudacion == 'Otro'" class="relleno"></td>
                            <td class="relleno" colspan="3">{{contrato.direccion_recaudacion}}</td>
                        </tr>
                        <tr>
                            <td class="titulo">Dirección de Correspondencia</td>
                            <td class="titulo">Particular</td>
                            <td width="2.5%" v-if="contrato.tipo_direccion_correspondencia == 'Particular'" class="relleno">X</td>
                            <td width="2.5%" v-else="contrato.tipo_direccion_correspondencia == 'Particular'" class="relleno"></td>
                            <td class="titulo">Trabajo</td>
                            <td width="2.5%" v-if="contrato.tipo_direccion_correspondencia == 'Trabajo'" class="relleno">X</td>
                            <td width="2.5%" v-else="contrato.tipo_direccion_correspondencia == 'Trabajo'" class="relleno"></td>
                            <td class="titulo">Otro</td>
                            <td width="2.5%" v-if="contrato.tipo_direccion_correspondencia == 'Otro'" class="relleno">X</td>
                            <td width="2.5%" v-else="contrato.tipo_direccion_correspondencia == 'Otro'" class="relleno"></td>
                            <td class="relleno" colspan="3">{{contrato.direccion_correspondencia}}</td>
                        </tr>
                        <tr>
                            <td class="titulo">Email Particular</td>
                            <td class="relleno" colspan="4">{{contrato.email_particular}}</td>
                            <td class="titulo" colspan="2">Email Trabajo</td>
                            <td class="relleno" colspan="3">{{contrato.email_trabajo}}</td>
                        </tr>
                        <tr>
                            <td class="cabecera" colspan="10">DATOS DEL CÓNYUGE</td>
                        </tr>
                        <tr>
                            <td class="titulo" colspan="2">Nombre/s del/a Cónyuge</td>
                            <td class="titulo" colspan="5">Apellido Paterno del/a Cónyuge</td>
                            <td class="titulo" colspan="3">Apellido Materno del/a Cónyuge</td>
                        </tr>
                        <tr>
                            <td class="relleno" colspan="2">{{contrato.nombre_conyuge}}</td>
                            <td class="relleno" colspan="5">{{contrato.apellido_pat_conyuge}}</td>
                            <td class="relleno" colspan="3">{{contrato.apellido_pat_conyuge}}</td>
                        </tr>
                        <tr>
                            <td class="titulo">DNI/Documento</td>
                            <td class="titulo">Fecha de Nacimiento</td>
                            <td class="titulo" colspan="5">Nacionalidad</td>
                            <td class="titulo">Teléfono Particular</td>
                            <td class="titulo">Teléfono Celular</td>
                            <td class="titulo">Teléfono Sexo</td>
                        </tr>
                        <tr>
                            <td class="relleno">{{contrato.numerodoc_conyuge}}</td>
                            <td class="relleno">{{contrato.fecha_nac_conyuge | myDate}}</td>
                            <td class="relleno" colspan="5">{{contrato.nacionalidad_conyuge}}</td>
                            <td class="relleno">{{contrato.telefono_conyuge}}</td>
                            <td class="relleno">{{contrato.celular_conyuge}}</td>
                            <td class="relleno">{{contrato.sexo_conyuge}}</td>
                        </tr>
                        <tr>
                            <td class="cabecera" colspan="10">DATOS DE LA PERSONA AUTORIZADA PARA DECIDIR LA OCUPACIÓN DE LA {{contrato.tipo_contrato.actividad}}</td>
                        </tr>
                        <tr>
                            <td class="titulo" colspan="2">Nombre/s</td>
                            <td class="titulo" colspan="5">Apellido Paterno</td>
                            <td class="titulo" colspan="3">Apellido Materno</td>
                        </tr>
                        <tr>
                            <td class="relleno" colspan="2">{{contrato.nombre_autorizado}}</td>
                            <td class="relleno" colspan="5">{{contrato.apellido_pat_autorizado}}</td>
                            <td class="relleno" colspan="3">{{contrato.apellido_mat_autorizado}}</td>
                        </tr>
                        <tr>
                            <td class="titulo">DNI/Documento</td>
                            <td class="titulo">Fecha de Nacimiento</td>
                            <td class="titulo" colspan="5">Nacionalidad</td>
                            <td class="titulo">Teléfono Particular</td>
                            <td class="titulo">Teléfono Celular</td>
                            <td class="titulo">Teléfono Sexo</td>
                        </tr>
                        <tr>
                            <td class="relleno">{{contrato.numerodoc_autorizado}}</td>
                            <td class="relleno">{{contrato.fecha_nac_autorizado | myDate}}</td>
                            <td class="relleno" colspan="5">{{contrato.nacionalidad_autorizado}}</td>
                            <td class="relleno">{{contrato.telefono_autorizado}}</td>
                            <td class="relleno">{{contrato.celular_autorizado}}</td>
                            <td class="relleno">{{contrato.sexo_autorizado}}</td>
                        </tr>
                    </tbody>
                </table>
                <span class="text-justify mt-2 mb-2">EL/LA CONYUGÉ se obliga y suscribe los términos del presente contrato de manera solidaria. En los términos y condiciones siguientes:</span>
                <table style="border:1px solid #000" class="ml-2 mr-2">
                    <tbody>
                        <tr>
                            <td class="cabecera" colspan="9">Sección I: CONDICIONES PARTICULARES DEL CONTRATO DE {{contrato.tipo_contrato.condiciones}}</td>
                        </tr>
                        <tr>
                            <td class="titulo">Sección I.A. {{contrato.tipo_contrato.cabecera}}</td>
                            <td class="titulo">{{contrato.tipo_contrato.tipo}}</td>
                            <td class="titulo">Capacidad Unidad</td>
                            <td class="titulo">Cap. Contratada</td>
                            <td class="titulo" colspan="2">Escogida</td>
                            <td class="titulo" colspan="2">Plataforma</td>
                            <td class="titulo">{{contrato.tipo_contrato.codigo}}</td>
                        </tr>
                        <tr style="height:1.4rem">
                            <td class="titulo"></td>
                            <td class="titulo"></td>
                            <td class="titulo"></td>
                            <td class="titulo"></td>
                            <td class="titulo" colspan="2"></td>
                            <td class="titulo" colspan="2"></td>
                            <td class="titulo"></td>
                        </tr>
                        <tr>
                            <td class="titulo">Sección I.B. Tipo de Contrato</td>
                            <td class="titulo">Tipo de Necesidad</td>
                            <td class="titulo">Periodo de Carencia</td>
                            <td class="titulo">Fecha termino Periodo de Carencia</td>
                            <td class="titulo" colspan="3">Costo de la Carencia (S/)</td>
                            <td class="titulo" colspan="2">Mínimo para Inhumar (S/)</td>
                        </tr>
                        <tr style="height:1.4rem">
                            <td class="titulo"></td>
                            <td class="titulo"></td>
                            <td class="titulo"></td>
                            <td class="titulo"></td>
                            <td class="titulo" colspan="3"></td>
                            <td class="titulo" colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="titulo">Sección I.C. De la Retribución</td>
                            <td class="titulo">Precio Lista (S/)</td>
                            <td class="titulo">Descuento (S/)</td>
                            <td class="titulo">Precio Total (S/)</td>
                            <td class="titulo" colspan="3">Fondo de mantenimiento (S/)</td>
                            <td class="titulo" colspan="2">Vencimiento de Letra por Fondo de Mantenimiento (S/)</td>
                        </tr>
                        <tr style="height:1.4rem">
                            <td class="titulo"></td>
                            <td class="titulo"></td>
                            <td class="titulo"></td>
                            <td class="titulo"></td>
                            <td class="titulo" colspan="3"></td>
                            <td class="titulo" colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="titulo">Sección I.D. Financiamiento</td>
                            <td class="titulo" colspan="8">Modalidad de Pago</td>
                        </tr>
                        <tr>
                            <td class="titulo"></td>
                            <td class="titulo">Contado</td>
                            <td class="titulo">Pago Inicial (S/)</td>
                            <td class="titulo">Pagaré</td>
                            <td class="titulo" colspan="3">Letra</td>
                            <td class="titulo" colspan="2">Seguro</td>
                        </tr>
                        <tr style="height:1.4rem">
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo" width="2.5%"></td>
                        </tr>
                        <tr>
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo">Monto Cuota</td>
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo">Monto Cuota</td>
                            <td class="titulo" width="2.5%"></td>
                            <td class="titulo">Monto Cuota</td>
                            <td class="titulo" width="2.5%"></td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-justify mt-2">De fallecer EL TITULAR o declararse su muerte presunta y suceder lo mismo con la persona por él autorizada, solo los herederos de EL TITULAR, debidamente acreditados como tales, podrán decidir sobre la realización del servicio funerario de cremación.</p>
                <p class="text-justify">Las partes declaran conocer y aceptar tanto las Condiciones Particulares como las Condiciones Generales, las cuales se adjuntan y forman parte del presente Contrato, y que en el presente Contrato no ha mediado ninguna causal de dolo, nulidad o anulabilidad que lo invalide, por lo tanto, en señal de conformidad lo firman en un original y una copia.</p>
                <p class="text-justify">Este contrato será válido cuando LA BENEFICENCIA lo suscriba. Nuestros Representantes solo están autorizados para recibir el Pago Inicial.</p>
                <p class="text-justify">Aceptado el {{contrato.dia}} de {{contrato.mes | upText}} del {{contrato.anio}}</p>
                <table class="ml-2 mr-2">
                    <thead>
                        <tr style="display:flex">
                            <td style="display:flex;align-items: end;justify-content: center; height: 10rem; border-left:1px solid #000; border-bottom:1px solid #000" width="38%">EL TITULAR</td>
                            <td style="display:flex;align-items: end;justify-content: center; height: 10rem; border-left:1px solid #000" width="24%">CODIGO VENDEDOR/A</td>
                            <td style="display:flex;align-items: end;justify-content: center; height: 10rem; border-left:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000" width="38%">LA BENEFICENCIA</td>
                        </tr>
                    </thead>
                </table>
                <!-- CESION DE SEPULTURA -->
                <div v-if="contrato.tipo == 1" style="columns: 2;" class="mt-4 pt-4">
                    <!-- <div style="width:49%" class="mt-3 pt-3"> -->
                        <p class="text-justify">
                            Sección II: CONDICIONES GENERALES
                        </p>
                        <p class="text-justify">
                            1. LA BENEFICENCIA es una Institución con personería jurídica de Derecho Público Interno, que realiza funciones de bienestar y promoción social complementarias a los fines sociales y tutelares del Estado, cuyo objetivo entre otros es colaborar en la solución de problemas de salud, apoyo social y cultural de los niños, adolescentes y jóvenes abandonados, como también de los adultos mayores y mujeres desvalidas, en extrema precariedad económica, de conformidad con el Decreto Legislativo No.1411. Para el cumplimiento de estos nobles fines, cuenta con áreas de negocios, con la finalidad de recaudar fondos, siendo una de estas áreas, el Cementerio General de Huancayo y el Cementerio Ecológico de Hualahoyo.
                        </p>
                        <p class="text-justify">
                            De la Cesión en Uso de Sepultura
                        </p>
                        <p class="text-justify">
                            2. La presente sección establece los términos y condiciones generales por los que se regirán las partes del presente contrato de SECIÓN EN USO DE NICHO, identificada en la Sección I - Condiciones Particulares Cláusula I.A., que LA BENEFICENCIA otorga a EL TITULAR de acuerdo a la LEY DE CEMENTERIOS Y SU REGLAMENTO.
                        <br> • El número de sepulturas que LA BENEFICENCIA cede en uso a EL TITULAR en virtud de este contrato se indica en el recuadro Capacidad Contratada, asimismo el número máximo de sepulturas que soporta la unidad de sepultación se indica en el recuadro Capacidad Unidad. </p>
                        <p class="text-justify">
                            3. La cesión en uso otorga a EL TITULAR o sus herederos el derecho a una reserva para la inhumación de los beneficiarios en la Unidad de Sepultación determinada en la Cláusula I.A., siempre que EL TITULAR o sus herederos cumplan con las estipulaciones de este contrato, en este sentido, EL TITULAR declara expresamente que tiene conocimiento y acepta todas y cada una de las disposiciones previstas en el referido Reglamento Interno. EL TITULAR o sus herederos designaran, a el(los) beneficiario(s) del derecho de sepultura objeto de este contrato, comunicándolo por escrito a LA BENEFICENCIA.
                        </p>
                        <p class="text-justify">4. La cesión de sepultura otorgada por LA BENEFICENCIA a EL TITULAR constituye una prestación de servicios, que se inicia con la inhumación en la sepultura del beneficiario y que se brinda de acuerdo con la LEY DE CEMENTERIOS Y SERVICIOS FUNERARIOS LEY No.26298, su Reglamento y modificatorias.</p>
                        <p class="text-justify">Condiciones para la Inhumación y Costo Uso Anticipado</p>
                        <p class="text-justify">5. El tipo de necesidad e inicio del servicio de este contrato tanto en Necesidad Futura (NF) o Necesidad Inmediata (NI) se indica en la cláusula I.B. Los contratos de tipo NF tienen el beneficio de brindárseles un plazo de hasta (…........) meses para cancelar el espacio elegido. Sin embargo, si EL TITULAR desea ocupar el espacio antes de los (…........) meses o antes de haber culminado de cancelar dicho espacio, será imprescindible que cancele antes de efectuar la inhumación.</p>
                        <p class="text-justify">6. Las condiciones que debe cumplir EL TITULAR para poder inhumar. <br> • Completar el pago del espacio elegido para Inhumar indicado en la sección I.B. <br> • Completar el pago del Servicio de Sepultura. <br> • Cumplir con los requisitos establecidos por Ley y el Reglamento Interno.</p>
                        <p class="text-justify">Del Financiamiento, la Mora y la Resolución (Anulación) del Contrato</p>
                        <p class="text-justify"> 7. La retribución que deberá pagar EL TITULAR a LA BENEFICENCIA por la cesión de sepultura podrá ser al contado o financiada al crédito, según se exprese en "Modalidad de Pago" de la cláusula I.D.
                        <p class="text-justify">En caso de financiamiento, las cuotas a pagar comprenderán el capital de la retribución que se devenguen durante el plazo (NQ de Cuotas) de financiamiento.  El monto a pagar y cronograma de pago se establece en Tabla de Financiamiento empleada por LA BENEFICENCIA,</p>
                        <p class="text-justify">que EL TITULAR declara conocer y aceptar a su entera satisfacción. Sin perjuicio de lo anterior, Queda establecido que en caso la sepultura contratada en Necesidad Futura sufra de incumplimiento por parte deEL TITULAR por un plazo de seis (6) meses consecutivos, EL TITULAR pierde los derechos sobre la sepultura contratada y no existe opción a realizar la devolución de la inicial y/o cuotas pagadas. LA BENEFICENCIA queda facultada para ofrecer dicho espacio a terceros y el TITULAR puede contratar nuevamente con LA BENEFICENCIA un nuevo producto sobre un nuevo espacio o un nuevo servicio a precios vigentes (precio actualizado) y usar sus adelantos como nueva inicial.</p>
                        <p class="text-justify">8. EL TITULAR quedará automáticamente constituido en mora en caso de falta de pago oportuno de cualquiera de las cuotas, sin que para ello se requiera de conminación o requerimiento alguno por parte de LA BENEFICENCIA.</p>
                        <p class="text-justify">9. Sin perjuicio de lo establecido en la cláusula anterior, en el supuesto en que EL TITULAR no cumpliese con cualquiera de sus obligaciones, en especial con las relativas al pago oportuno de todas y cada una de las cuotas pactadas, LA BENEFICENCIA queda plenamente facultada para, alternativamente y a su libre elección, (i) resolver unilateral y extrajudicialmente el presente contrato mediante una comunicación escrita a EL TITULAR en tal sentido o (ii) si correspondiera dar por vencida(s) toda(s) la(s) cuota(s) pendiente(s) de pago y proceder a la ejecución forzosa del contrato.</p>
                        <p class="text-justify">Reglamento Interno, Servicios de Sepultación y Título de Cesión en Uso</p>
                        <p class="text-justify">10. Las disposiciones del Reglamento Interno forman parte integrante del presente contrato. Cualquier modificación al Reglamento Interno aprobada por la Entidad surtirá efecto en forma Inmediata, incluso para los TITULARES que hayan celebrado contrato con LA BENEFICENCIA con anterioridad a ello. Los cambios en el Reglamento Interno no podrán afectar los derechos esenciales que este contrato reconoce a EL TITULAR ni podrán importar incremento de la retribución pactada.</p>
                        <p class="text-justify">11. Una vez pagada íntegramente la retribución indicada en la Cláusula I.C. y no existiendo obligaciones pendientes de EL TITULAR con LA BENEFICENCIA, esta emitirá un CERTIFICADO DE CESION FUTURA que acredita el derecho pleno de EL TITULAR sobre la cesión otorgada sobre la sepultura individualizada en la Cláusula I.A. Este certificado quedará debidamente Inscrito en el Registro</p>
                        <div class="text-center">
                            <div style="margin-left:auto; margin-right:auto; text-align:center;border-top:1px solid #000; width:80%; height:6rem; margin-top:6rem">EL TITULAR</div>
                            <div style="margin-left:auto; margin-right:auto; text-align:center;border-top:1px solid #000; width:80%;">LA BENEFICENCIA</div>
                        </div>
                    <!-- </div> -->
                </div>

                <!-- SERVICIO DE CREMACIÓN -->
                <div v-else style="columns: 2; line-height: 15pt;" class="mt-4 pt-4">
                    <!-- <div style="width:49%" class="mt-3 pt-3"> -->
                        <p class="text-justify" style="line-height: 15pt;">Sección II: CONDICIONES GENERALES</p>
                        <p class="text-justify" style="line-height: 15pt;">1. LA BENEFICENCIA es una Institución con personería jurídica de Derecho Público Interno, que realiza funciones de bienestar y promoción social complementarias a los fines sociales y tutelares del Estado, cuyo objetivo entre otros es colaborar en la solución de problemas de salud, apoyo social y cultural de los niños, adolescentes y jóvenes abandonados, como también de los adultos mayores y mujeres desvalidas, en extrema precariedad económica, de conformidad con el Decreto Legislativo No.1411. Para el cumplimiento de estos nobles fines, cuenta con áreas de negocios, con la finalidad de recaudar fondos, siendo una de estas áreas, el Cementerio General de Huancayo y el Cementerio Ecológico de Hualahoyo.</p>
                        <p class="text-justify" style="line-height: 15pt;">Del servicio de cremación</p>
                        <p class="text-justify" style="line-height: 15pt;">2. La presente sección establece los términos y condiciones generales por los que se regirán las partes del presente contrato del SERVICIO FUNERARIO DE CREMACIÓN, identificada en la Sección I - Condiciones Particulares Cláusula I.A., que LA BENEFICENCIA otorga a EL TITULAR de acuerdo a la LEY DE CEMENTERIOS Y SU REGLAMENTO. <br> • El número de cremación que LA BENEFICENCIA se compromete a prestar a EL TITULAR en virtud de este contrato se indica en el recuadro Capacidad Contratada, asimismo el número máximo de cremaciones que soporta la unidad de cremación se indica en el recuadro Capacidad Unidad.</p>
                        <p class="text-justify" style="line-height: 15pt;">3. El presente contrato otorga a EL TITULAR o sus herederos el derecho a una reserva para la cremación de los beneficiarios en la Unidad de Cremación determinada en la Cláusula I.A., siempre que EL TITULAR o sus herederos cumplan con las estipulaciones de este contrato, en este sentido, EL TITULAR declara expresamente que tiene conocimiento y acepta todas y cada una de las disposiciones previstas en el referido Reglamento Interno. EL TITULAR o sus herederos designaran, a el(los) beneficiario(s) del derecho de cremación objeto de este contrato, comunicándolo por escrito a LA BENEFICENCIA.</p>
                        <p class="text-justify" style="line-height: 15pt;">4. El servicio funerario otorgado por LA BENEFICENCIA a EL TITULAR constituye una prestación de servicios, que se realiza previa verificación de los requisitos señalados en la LEY DE CEMENTERIOS Y SERVICIOS FUNERARIOS LEY No.26298, su Reglamento y modificatorias.</p>
                        <p class="text-justify" style="line-height: 15pt;">Condiciones para la Cremación y Costo Uso Anticipado</p>
                        <p class="text-justify" style="line-height: 15pt;">5. El tipo de necesidad e inicio del servicio de este contrato tanto en Necesidad Futura (NF) o Necesidad Inmediata (NI) se indica en la cláusula I.B. Los contratos de tipo NF tienen el beneficio de brindárseles un plazo de hasta doce (12) meses para cancelar el servicio contratado. Sin embargo, si EL TITULAR desea efectivizar la prestación antes de los doce (12) meses o antes de haber culminado de cancelar, será imprescindible que cancele antes de efectuar la cremación.</p>
                        <p class="text-justify" style="line-height: 15pt;">6. Las condiciones que debe cumplir EL TITULAR para poder cremar <br> • Completar el pago del servicio contratado indicado en la sección I.B. <br> • Completar el pago del Servicio de Sepultura, de corresponder. <br> • Cumplir con los requisitos establecidos por Ley y el Reglamento Interno.</p>
                        <p class="text-justify" style="line-height: 15pt;">Del Financiamiento, la Mora y la Resolución (Anulación) del Contrato</p>
                        <p class="text-justify" style="line-height: 15pt;">7. La retribución que deberá pagar EL TITULAR a LA BENEFICENCIA por la prestación del servicio podrá ser al contado o financiada al crédito, según se exprese en "Modalidad de Pago" de la cláusula I.D. En caso de financiamiento, las cuotas a pagar comprenderán el capital de la retribución que se devenguen durante el plazo (NQ de Cuotas) de financiamiento. El monto a pagar y cronograma de pago se establece en Tabla de Financiamiento empleada por LA BENEFICENCIA, que EL TITULAR declara conocer y aceptar a su entera satisfacción. Sin perjuicio de lo anterior. Queda establecido que en caso el servicio funerario contratado en Necesidad Futura sufra de incumplimiento por parte de EL TITULAR por un plazo de seis (6) meses consecutivos, EL TITULAR pierde los derechos sobre el servicio funerario contratado y no existe opción para realizar devolución de la inicial y/o cuotas pagadas. El TITULAR puede contratar nuevamente con LA BENEFICENCIA un nuevo producto o servicio a precios vigentes (precio actualizado) y usar sus adelantos como nueva inicial.</p>
                        <p class="text-justify" style="line-height: 15pt;">8. EL TITULAR quedará automáticamente constituido en mora en caso de falta de pago oportuno de cualquiera de las cuotas, sin que para ello se requiera de conminación o requerimiento alguno por parte de LA BENEFICENCIA.</p>
                        <p class="text-justify" style="line-height: 15pt;">9. Sin perjuicio de lo establecido en la cláusula anterior, en el supuesto en que EL TITULAR no cumpliese con cualquiera de sus obligaciones, en especial con las relativas al pago oportuno de todas y cada una de las cuotas pactadas, LA BENEFICENCIA queda plenamente facultada para, alternativamente y a su libre elección, (i) resolver unilateral y extrajudicialmente el presente contrato mediante una comunicación escrita a EL TITULAR en tal sentido o (ii) si correspondiera dar por vencida(s) toda(s) la(s) cuota(s) pendiente(s) de pago y proceder a la ejecución forzosa del contrato.</p>
                        <p class="text-justify" style="line-height: 15pt;">Reglamento Interno, Servicio Funerario de Cremación</p>
                        <p class="text-justify" style="line-height: 15pt;">10. Las disposiciones del Reglamento Interno forman parte integrante del presente contrato. Cualquier modificación al Reglamento Interno aprobada por la Entidad surtirá efecto en forma Inmediata, incluso para los TITULARES que hayan celebrado contrato con LA BENEFICENCIA con anterioridad a ello. Los cambios en el Reglamento Interno no podrán afectar los derechos esenciales que este contrato reconoce a EL TITULAR ni podrán importar incremento de la retribución pactada.</p>
                        <p class="text-justify" style="line-height: 15pt;">11. Una vez pagada íntegramente la retribución indicada en la Cláusula I.C. y no existiendo obligaciones pendientes de EL TITULAR con LA BENEFICENCIA, esta emitirá un CERTIFICADO DE CREMACIÓN FUTURA que acredita el derecho pleno de EL TITULAR sobre el servicio funerario a prestar señalada en la Cláusula I.A. Este certificado quedará debidamente Inscrito en el Registro General de Certificados que lleva LA BENEFICENCIA para este efecto.</p>
                        <p class="text-justify" style="line-height: 15pt;">Generales</p>
                        <p class="text-justify" style="line-height: 15pt;">12. Los encabezamientos de las cláusulas son meramente enunciativos y no forman parte de la misma.</p>
                        <p class="text-justify" style="line-height: 15pt;">13. La nulidad, invalidez o inaplicabilidad de cualquier cláusula decretada por autoridad competente no afecta la validez de las demás cláusulas.</p>
                        <p class="text-justify" style="line-height: 15pt;">14. En la interpretación y ejecución del presente Contrato se aplicará supletoriamente la Ley de la Materia, el Código Civil y demás normas pertinentes.</p>
                        <p class="text-justify" style="line-height: 15pt;">15. Las partes renuncian al fuero de sus domicilios y se someten a la jurisdicción de los jueces de Huancayo para el caso de cualquier controversia derivada de la ejecución o interpretación de! presente contrato que no puedan solucionarse directamente, de buena fe.</p>
                        <p class="text-justify" style="line-height: 15pt;">16. Todos los avisos, comunicaciones y notificaciones relacionadas a este contrato y a su ejecución, incluyendo las requeridas en cualquier controversia judicial, se efectuarán en los domicilios señalados en el Contrato. A fin de surtir efectos frente a la contraparte, el cambio de domicilio deberá notificarse mediante carta notarial o por comunicación escrita con cargo de recepción o mediante publicación en dos diarios de mayor circulación en la Ciudad de Huancayo.</p>
                        <div style="margin-left:auto; margin-right:auto;text-align:center;border-top:1px solid #000; width:80%; height:6rem; margin-top:6rem">EL TITULAR</div>
                        <div style="margin-left:auto; margin-right:auto;text-align:center;border-top:1px solid #000; width:80%;">LA BENEFICENCIA</div>
                    <!-- </div> -->
                </div>
            </div>
        </div>

    </div>


</template>
<script>
    export default {
        data() {
            return {
                error: {},
                loading: true,
                contrato:'',
            }
        },
        methods: {
            back(){
                history.back();
            },
            print() {
                let footer = document.getElementsByTagName('footer')[0]
                footer.style = 'display:none';
                var css = "@media print {.cabecera {background:#c0cbd0 !important; -webkit-print-color-adjust: exact;} @page { size: A4; margin: 2rem 2rem 1rem 2rem !important;} .content-wrapper{margin-top:0 !important; padding-top:0 !important;} .card-body{margin-top:0 !important; padding-bottom:0; padding-top:0 !important} .card{margin-bottom:0 !important} .tds{padding:0.1rem 0.3rem 0.1rem 0.3rem !important; font-size:11px !important} ",
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
            loadContrato(id){
                this.loading = true;
                axios.get('/api/contratos/'+id).then( ({data}) => {
                    this.contrato = data;
                    this.contrato.mes = momento(this.contrato.fecha).format('MMMM');
                    this.contrato.dia = momento(this.contrato.fecha).format('DD');
                    this.contrato.anio = momento(this.contrato.fecha).format('YYYY');
                    this.loading = false;
                })
            },
        },
        created() {
            let idContrato;
            idContrato = this.$route.params.id;
            this.loadContrato(idContrato);
            document.title = `Imprimir/Contrato - SBH`;
        },
    }

</script>
