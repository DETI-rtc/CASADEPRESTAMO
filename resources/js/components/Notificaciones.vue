<template>
  <li class="nav-item dropdown m-auto">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
          <i class="far fa-bell text-lg"></i>
          <span class="badge badge-danger navbar-badge"  v-if="notifications.length > 0">{{notifications.length}} </span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right " style="left: inherit; right: 0px;">
          <template  v-for="notification in notifications" >
            
                <a href="/creditos" class="dropdown-item">
                <!-- Message Start -->
                <div class="media">
                  
                  <div class="media-body">
                    <h5 class="dropdown-item-title">
                      Credito Nº {{ notification.credito }}
                      <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                    </h5>
                    <p class="text-sm">Pora su Aprobacion  x {{ notification.monto | currency }}</p>
                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i><timeago :datetime="notification.time" :auto-update="60"></timeago></p>
                  </div>
                </div>
                <!-- Message End -->
              </a>
          </template>
                    
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer" v-if="notifications.length > 0" >See All Messages</a>
        </div>
      </li>

</template>

<script>
import VueTimeago from 'vue-timeago'
Vue.use(VueTimeago, {
  name: 'Timeago', // Component name, `Timeago` by default
  locale: 'es', // Default locale
  // We use `date-fns` under the hood
  // So you can use all locales from it
  locales: {
    'es': require('date-fns/locale/es'),
    ja: require('date-fns/locale/ja')
  }
})
export default {
  
  props: ['notificacion'],
  data(){
    return{
      notifications: []
    }
  },
methods: {

},


  mounted() {
     EventBus.$on('actunoti', data1 => {
               axios.get("/api/notificaciones").then(({ data }) => (this.notifications=data)).catch(); 
            })
     axios.get("/api/notificaciones").then(({ data }) => (this.notifications=data)).catch();    
    // window.Echo.channel('pizza-tracker.' + this.order_id)
    window.Echo.channel('credito-tracker')
    .listen('CreditoStatusChangedEvent', (credito) =>
    {
     //console.log(credito);
     //Swal.fire(
     // 'Tienes Creditos por Aprobar',
      //'Aprobar',
      //'warning'
      //);
     //axios.get("/api/notificaciones").then(({ data }) => (this.notifications=data)).catch(); 
    });
  },
};
</script>