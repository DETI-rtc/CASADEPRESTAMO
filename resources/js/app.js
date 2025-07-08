/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
import Form from 'vform'
import { HasError, AlertError } from 'vform/src/components/bootstrap5'
window.Form = Form
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/es'
//locale.use(lang)
Vue.use(ElementUI, { locale ,size: 'small' });
import moment from 'moment'
import VueRouter from 'vue-router'
import VueDatePicker from '@mathieustan/vue-datepicker'
import '@mathieustan/vue-datepicker/dist/vue-datepicker.min.css'
Vue.use(VueDatePicker)
import vSelect from 'vue-select'
Vue.component('v-select', vSelect)


import VueHtmlToPaper from 'vue-html-to-paper';

const options = {
  name: '_blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
  styles: [
    'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
    'https://unpkg.com/kidlat-css/css/kidlat.css',
    '/css/print.css' 
  ],
  timeout: 1000, // default timeout before the print window appears
  autoClose: true, // if false, the window will not close after printing
  windowTitle: window.document.title, // override the window title
}

Vue.use(VueHtmlToPaper, options);

// or, using the defaults with no stylesheet
Vue.use(VueHtmlToPaper);








import VueCurrencyFilter from 'vue-currency-filter'
Vue.use(VueCurrencyFilter,{
  symbol : '',
  thousandsSeparator: ',',
  fractionCount: 2,
  fractionSeparator: '.',
  symbolPosition: 'front',
  symbolSpacing: true
})
var pdfMake = require('pdfmake/build/pdfmake.js');
var pdfFonts = require('pdfmake/build/vfs_fonts.js');
pdfMake.vfs = pdfFonts.pdfMake.vfs;
import VueAutonumeric from 'vue-autonumeric'
Vue.component('vue-autonumeric',VueAutonumeric)
// TIR
var Finance = require('financejs');
const { irr } = require('node-irr')
import Swal from 'sweetalert2'
window.Swal = Swal;
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 5000
});
Vue.filter('localmoney', function(number) {
  if (number == 0) {
      return parseFloat(number).toFixed(2);
  } else {
      if (number == null || number == '') {
          number = 0;
          number = parseFloat(number).toFixed(2);
          return number;
      }
      return parseFloat(number).toFixed(2);
  }
});
Vue.filter('localperu', function(number) {
  if (number == 0) {
      return parseFloat(number).toFixed(2);
  } else {
      if (number == null || number == '') {
          number = 0;
          number = parseFloat(number).toFixed(2);
          return number;
      }
      return new Intl.NumberFormat('es-MX',{   
        minimumFractionDigits:2,    
        maximumFractionDigits: 2,        
      }).format(number);
      //parseFloat(number).toFixed(2);
  }
})

window.Toast = Toast;

Vue.use(VueRouter) 
const routes = [
    { path: '/home', component: require('./panel/Inicio.vue').default },
    { path: '/listcomprobantes', component: require('./comprobante/listComprobantes.vue').default },
    { path: '/generarcompro', component: require('./comprobante/Listacuotasvencidas.vue').default },
    { path: '/regcomprobante', component: require('./comprobante/invoice.vue').default },
    { path: '/productos', component: require('./producto/index.vue').default },
    { path: '/clientes', component: require('./components/Persona.vue').default },
    // { path: '/clientes', component: require('./persona/index.vue').default },
    
    { path: '/almacen', component: require('./almacen/index.vue').default },
    { path: '/categoria', component: require('./categoria/index.vue').default },
   
    { path: '/tipodocumento', component: require('./tipodocumento/index.vue').default },
    { path: '/tiponotacredito', component: require('./tiponotacredito/index.vue').default },
    { path: '/tiponotadebito', component: require('./tiponotadebito/index.vue').default },
    { path: '/tipocomprobante', component: require('./tipocomprobante/index.vue').default },
    { path: '/tipoafectacion', component: require('./tipoafectacion/index.vue').default },
    
    { path: '/serie', component: require('./series/index.vue').default },
    { path: '/unidad', component: require('./unidades/index.vue').default },
    { path: '/perfil', component: require('./components/usuarios/Perfil.vue').default },
    { path: '/usuarios', component: require('./components/usuarios/Usuarios.vue').default }, 
    { path: '/permisos', component: require('./components/usuarios/Permisos.vue').default},        
    { path: '/roles', component: require('./components/usuarios/Roles.vue').default },
    { path: '/reportediario', component: require('./comprobante/reportes/ComprobantesDiario.vue').default },
    { path: '/reportemensual', component: require('./comprobante/reportes/ComprobantesMensual.vue').default },
    { path: '/contratos', component: require('./contrato/listaContratos.vue').default },
    { path: '/listcreditos', component: require('./contrato/listaCreditos.vue').default },
    { path: '/print/cronograma/:id', component: require('./contrato/reportes/printCronograma.vue').default },

    { path: '/print/contrato/:id', component: require('./contrato/reportes/printContrato.vue').default },
    
    // { path: '*', component: require('./components/NotFound.vue').default }
    { path: '/listas', component: require('./components/Lista.vue').default },    
   { path: '/tasaint', component: require('./components/TasaInteres.vue').default },
   { path: '/relaci', component: require('./components/RelacionCI.vue').default },
   { path: '/reladi', component: require('./components/RelacionDI.vue').default },
   { path: '/plasos', component: require('./components/PlazosMax.vue').default },
   { path: '/clientes', component: require('./components/Persona.vue').default },
   { path: '/empresas', component: require('./components/Empresas.vue').default },
   { path: '/registropago', component: require('./pago/RPago.vue').default },
   { path: '/pagos', component: require('./pago/LPago.vue').default },
   { path: '/cancelacion-pagos', component: require('./pago/CPago.vue').default },
   { path: '/simulador', component: require('./components/simulador.vue').default },
   { path: '/pagosbh', component: require('./pago/Seg_pago.vue').default },
   { path: '/asignameta', component: require('./asigna/Asignameta.vue').default },
   { path: '/asignasuper', component: require('./asigna/Asignasupervisor.vue').default },
   { path: '/hruta', component: require('./components/Hojaruta.vue').default },
   { path: '/cuotasvencidas', component: require('./components/CuotasVencida.vue').default },
   { path: '/desgravamen', component: require('./components/Desgravamen.vue').default },
   { path: '/criesgo', component: require('./components/Centralriesgo.vue').default },
   { path: '/ptmensual', component: require('./components/Plandetrabajo.vue').default },
   { path: '/convenios', component: require('./components/Convenios.vue').default },
   { path: '/hojaruta/:id', component: require('./components/PrintHojaruta.vue').default },
   { path: '/creditos', component: require('./components/creditos.vue').default },
   //FORMATOS

   {path: '/formatos/solicitudcredito/:id', component: require('./formatos/SolicitudCredito.vue').default},
   {path: '/formatos/contrato-mutuo-dinerario/:id', component: require('./formatos/ContratoMutuoDinerario.vue').default},
   {path: '/formatos/hoja-resumen/:id', component: require('./formatos/HojaResumen.vue').default},
   {path: '/formatos/pagare/:id', component: require('./formatos/Pagare.vue').default},
   {path: '/formatos/carta-autorizacion/:id', component: require('./formatos/CartaAutorizacion.vue').default},
   {path: '/formatos/hoja-instrucciones/:id', component: require('./formatos/HojaInstrucciones.vue').default},
   {path: '/formatos/hoja-instrucciones-sin-compra/:id', component: require('./formatos/HojaInstruccionesSinCompra.vue').default},
   
   {path: '/formatos/declaracion-jurada/:id', component: require('./formatos/DeclaracionJurada.vue').default},
   {path: '/formatos/calculoprestamo/:id', component: require('./formatos/CalculoPrestamo.vue').default},
   {path: '/formatos/checklist/:id', component: require('./formatos/CheckList.vue').default},
   {path: '/formatos/informe/:id', component: require('./formatos/Informe.vue').default},

    //TESORERIRA
    { path: '/tesoreria/desembolsos', component: require('./tesoreria/Desembolsos.vue').default },
    { path: '/tesoreria/creditoscancelados', component: require('./tesoreria/CreditosCancelados.vue').default },
    { path: '/tesoreria/creditos-desembolso', component: require('./tesoreria/CreditosDesembolso.vue').default },
    { path: '/tesoreria/pagos', component: require('./tesoreria/RPago.vue').default },
    { path: '/listcomprobantes', component: require('./facturacion/listComprobantes.vue').default },
    { path: '/regcomprobante', component: require('./facturacion/invoice.vue').default },

    //PRESUPUESTO
   { path: '/presupuesto/creditos', component: require('./presupuesto/Creditos.vue').default },

   //COmisiones
   { path: '/comisiones/cumplimiento-meta', component: require('./comisiones/ComisionCumplimientoMeta.vue').default },
   { path: '/comisiones/incumplimiento-cuotas', component: require('./comisiones/CuotasAtrasadas.vue').default },
   { path: '/comisiones/satisfaccion', component: require('./comisiones/ComisionSatisfaccion.vue').default },
   { path: '/comisiones/calcular', component: require('./comisiones/CalculoComisiones.vue').default },


   //REPORTES
   { path: '/reportes/controlventascredito', component: require('./reportes/ControlVentasCredito.vue').default },
   { path: '/reportes/mensual', component: require('./reportes/Mensual.vue').default },
   { path: '/reportes/detallado', component: require('./reportes/Detallado.vue').default },
   { path: '/reportes/comprobantes', component: require('./reportes/ComprobantesMensual.vue').default },
   { path: '/reportes/deudarestante', component: require('./reportes/DeudaRestante.vue').default },
   { path: '/reportes/deudaporcobrar', component: require('./reportes/DeudaPorCobrar.vue').default },
   { path: '/reportes/totalreenganche', component: require('./reportes/TotalReenganches.vue').default },
   { path: '/reportes/deudacapital', component: require('./reportes/Mensualamor.vue').default },
   { path: '/reportes/cuotanopagada', component: require('./reportes/CuotanoPagada.vue').default },

   

   
     ]
     window.Fire =  new Vue();
     export const EventBus = new Vue();
     window.EventBus = EventBus;
     const router = new VueRouter({
         mode:'history',
         routes
     })

     Vue.prototype.$eventHub = new Vue()
/**
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
 import {ServerTable, ClientTable, Event} from 'vue-tables-2'
window.momento = moment;
momento.locale('es');
 Vue.use(ClientTable);
 Vue.filter('myDate', function(created) {
    if (created) {
      return moment(created).format('DD/MM/YYYY');
    } else {
      return '';
    }
  });
  Vue.filter('upText', function(text){
      return text.toUpperCase();
  });
  Vue.filter('lowText', function(text){
      return text.toLowerCase()
  });
  Vue.filter('Money', function (value) {
      return 'S/. ' + parseFloat(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      
  });
  Vue.filter('decimal', function (value) {
      return 'S/. ' + new Intl.NumberFormat('es-MX').format(value);
  });
  Vue.filter('decimal1', function (value) {
    return  new Intl.NumberFormat('es-MX').format(value);
});
  
  Vue.filter('moneda', function (value) {
    if (value) {
      value = parseFloat(value).toFixed(2);
      const formatoMexico = (data) => {
        const exp = /(\d)(?=(\d{3})+(?!\d))/g;
        const rep = '$1,';
        return data.toString().replace(exp,rep);
      }
      return formatoMexico(value)
    } else {
      return '0';
    }
  });
  Vue.filter('reverse', function(value) {
    // slice to make a copy of array, then reverse the copy
    return value.slice().reverse();
  });
  Vue.filter('redondear', function (value) {
    return  parseFloat(value).toFixed(2);
  });
  Vue.filter('toCurrency', function (value) {
    
    const nru = parseFloat(value);

var formatter = new Intl.NumberFormat('es-PE', {
    style: 'currency',
    currency: 'PEN',
    minimumFractionDigits: 2
});
return formatter.format(nru);
});
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
//Vue.component('comprobante', require('./comprobante/invoice.vue'));
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('App', require('./components/App.vue').default);
Vue.component('x-graph', require('./components/graph/Graph.vue').default);
Vue.component('x-graph-line', require('./components/graph/GraphLine.vue').default);
Vue.component('notificaciones', require('./components/Notificaciones.vue').default);
Vue.component('panelgerente', require('./panel/Homegerente.vue').default);
Vue.component('panelsuper', require('./panel/Homesupervisor.vue').default);
Vue.component('panelotros', require('./panel/Home.vue').default);
Vue.component('panelteso', require('./panel/Hometeso.vue').default);
Vue.component('calendario', require('./components/Calendario.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
