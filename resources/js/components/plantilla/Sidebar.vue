<template>
    <aside class="main-sidebar elevation-4 sidebar-light-info">
    <!-- Brand Logo -->
    <router-link to="/home" class="brand-link elevation-4" >
    
      <img src="/img/casa.png" alt="AdminLTE Docs Logo Small" class="brand-image-xl logo-xs">
      <img src="/img/logocasa2.png" alt="AdminLTE Docs Logo Large" class="brand-image-xs logo-xl brand-text font-weight-light" style="left: 12px">     
      
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar os-theme-dark">
      <!-- Sidebar user (optional) -->
      

            
      <!-- Sidebar Menu -->
      
      <nav class="mt-2">
      
        <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact" data-widget="treeview" role="menu" data-accordion="true">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
           <li class="nav-header">Acciones</li>
           <li class="nav-item treeview">

             <template v-if="listRoles.includes('Administrador') || listRoles.includes('Supervisor') || listRoles.includes('Gerente') || listRoles.includes('Agente') || listRoles.includes('Asesor')">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-registered"></i>
                  <p>Registros  <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <router-link to="/creditos" class="nav-link">
                      <i class="fa fa-credit-card nav-icon"></i>
                      <p>Creditos</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link to="/registropago" class="nav-link">
                      <i class="fa fa-cash-register nav-icon"></i>
                      <p>Pagos</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link to="/cancelacion-pagos" class="nav-link">
                      <i class="fas fa-piggy-bank nav-icon"></i>
                      <p>Creditos Cancelados</p>
                    </router-link>
                  </li>
                  <template v-if="listRoles.includes('Administrador') || listRoles.includes('Supervisor') ">
                    <li class="nav-item">
                      <router-link to="/asignasuper" class="nav-link">
                        <i class="fa fa-file-invoice-dollar nav-icon"></i>
                        <p>Asignar Supervisor</p>
                      </router-link>
                    </li>
                  </template>
                  <li class="nav-item">
                    <router-link to="/pagosbh" class="nav-link">
                      <i class="fa fa-search-dollar nav-icon"></i>
                      <p>Seguimiento Pagos SBH</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link to="/clientes" class="nav-link">
                      <i class="fa fa-users nav-icon"></i>
                      <p>Clientes</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link to="/criesgo" class="nav-link">
                      <i class="fas fa-running nav-icon"></i>
                      <p>Central de Riego</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link to="/ptmensual" class="nav-link">
                      <i class="fas fa-file-signature nav-icon"></i>
                      <p>Plan de Trabajo Mensual</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link to="/cuotasvencidas" class="nav-link">
                      <i class="fas fa-list nav-icon"></i>
                      <p>Cuotas Vencidas</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link to="/hruta" class="nav-link">
                      <i class="fas fa-route nav-icon"></i>
                      <p>Hoja de ruta</p>
                    </router-link>
                  </li>
                 
                </ul>
             </template>
          </li>
          <li class="nav-item has-treeview">

            <template v-if="listRoles.includes('Administrador') || listRoles.includes('Supervisor') || listRoles.includes('Gerente') || listRoles.includes('Agente') || listRoles.includes('Asesor')">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-mail-bulk"></i>
                <p>
                  Mantenimiento
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/empresas" class="nav-link">
                    <i class="fa fa-city nav-icon"></i>
                    <p>Empresas</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/listas" class="nav-link">
                    <i class="fa fa-clipboard-list nav-icon"></i>
                    <p>Listas</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link  to="/plasos" class="nav-link">
                    <i class="fa fa-clipboard-check nav-icon"></i>
                    <p>Plasos Maximoss</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/tasaint" class="nav-link">
                    <i class="fa fa-percent nav-icon"></i>
                    <p>Tasas de Interes</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/relaci" class="nav-link">
                    <i class="fa fa-circle nav-icon"></i>
                    <p>RCI</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/desgravamen" class="nav-link">
                    <i class="fa fa-circle nav-icon"></i>
                    <p>Desgravamen</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/reladi" class="nav-link">
                    <i class="fa fa-text-height nav-icon"></i>
                    <p>RDI</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/clientes" class="nav-link">
                    <i class="fa fa-users nav-icon"></i>
                    <p>Clientes</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/asigseguro" class="nav-link">
                    <i class="fa fa-users nav-icon"></i>
                    <p>Seguro Desgravamen</p>
                  </router-link>
                </li>
              </ul>
            </template>
          </li>

           


          <li class="nav-item treeview" v-if="listRoles.includes('Tesorero') || listRoles.includes('Administrador') ">
                <a href="#" class="nav-link">
                  <!-- <i class="nav-icon fas fa-registered"></i> -->
                  <i class="nav-icon fas fa-university"></i>
                  <p> Tesoreria <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                
                <ul class="nav nav-treeview">
                  <!-- <li class="nav-item">
                    <router-link to="/tesoreria/desembolsos" class="nav-link">
                      <i class="fas fa-university nav-icon"></i>
                      <p>Desembolsos</p>
                    </router-link>
                  </li> -->

                  <li class="nav-item">
                    <router-link to="/cancelacion-pagos" class="nav-link">
                      <i class="fas fa-piggy-bank nav-icon"></i>
                      <p>Cancelar Creditos</p>
                    </router-link>
                  </li>

                  <li class="nav-item">
                    <router-link to="/registropago" class="nav-link">
                      <i class="fa fa-cash-register nav-icon"></i>
                      <p>Registrar Pagos</p>
                    </router-link>
                  </li>

                  <li class="nav-item">
                    <router-link to="/tesoreria/pagos" class="nav-link">
                      <i class="fa fa-cash-register nav-icon"></i>
                      <p>Validar Pagos</p>
                    </router-link>
                  </li>
                  <!-- <li class="nav-item">
                    <router-link to="/tesoreria/creditoscancelados" class="nav-link">
                      <i class="fas fa-file-invoice-dollar nav-icon"></i>
                      <p>Cancelacion de Creditos</p>
                    </router-link>
                  </li> -->

                  <li class="nav-item">
                    <router-link to="/tesoreria/creditos-desembolso" class="nav-link">
                      <!-- <i class="fas fa-university nav-icon"></i> -->
                      <i class="fas fa-file-invoice-dollar nav-icon"></i>
                      <p>Creditos para desembolso</p>
                    </router-link>
                  </li>
                </ul>
                
          </li>
          

          <li class="nav-item treeview" v-if="listRoles.includes('PRESUPUESTO') || listRoles.includes('Administrador') ">
                <a href="#" class="nav-link">
                  <!-- <i class="nav-icon fas fa-registered"></i> -->
                  <i class="nav-icon fas fa-university"></i>
                  <p> Presupuesto <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <router-link to="/presupuesto/creditos" class="nav-link">
                      <!-- <i class="fas fa-university nav-icon"></i> -->
                      <i class="fas fa-users nav-icon"></i>
                      <p>CLIENTES</p>
                    </router-link>
                  </li>
                </ul>
          </li>

          <li class="nav-item treeview" v-if="listRoles.includes('Administrador') || listRoles.includes('Gerente')">
                <a href="#" class="nav-link">
                  <!-- <i class="nav-icon fas fa-registered"></i> -->
                  <i class="nav-icon fas fa-comment-dollar"></i>
                  <p> COMISIONES <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <router-link to="/comisiones/calcular" class="nav-link">
                      <!-- <i class="fas fa-university nav-icon"></i> -->
                      <i class="nav-icon fas fa-money-check-alt"></i>
                      <p>Calcular Comisiones</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link to="/comisiones/cumplimiento-meta" class="nav-link">
                      <!-- <i class="fas fa-university nav-icon"></i> -->
                      <i class=" nav-icon fas fa-tachometer-alt"></i>
                      <p>Meta Comercial</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link to="/comisiones/incumplimiento-cuotas" class="nav-link">
                      <!-- <i class="fas fa-university nav-icon"></i> -->
                      <i class="nav-icon fas fa-funnel-dollar"></i>
                      <p>Meta de Riesgo</p>
                    </router-link>
                  </li>

                  <li class="nav-item">
                    <router-link to="/comisiones/satisfaccion" class="nav-link">
                      <!-- <i class="fas fa-university nav-icon"></i> -->
                      <i class="nav-icon fas fa-headset"></i>
                      <p>Calidad de Proceso</p>
                    </router-link>
                  </li>

                  
                </ul>
          </li>
          <li class="nav-item has-treeview" v-if="listRoles.includes('Administrador')">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-user-shield purple"></i>
              <p>
                Administrador
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/usuarios" class="nav-link">
                  <i class="fa fa-users-cog nav-icon purple"></i>
                  <p>Usuarios</p>
                </router-link>
              </li>
             
              <li class="nav-item">
                <router-link to="/roles" class="nav-link">
                  <i class="fa fa-id-card-alt nav-icon purple"></i>
                  <p>Roles</p>
                </router-link>
              </li>
             
              <li class="nav-item">
                <router-link to="/permisos" class="nav-link">
                  <i class="fa fa-id-card-alt nav-icon purple"></i>
                  <p>Permisos</p>
                </router-link>
              </li>
             
              <li class="nav-item">
                <router-link to="/desarrollador" class="nav-link">
                  <i class="fa fa-users nav-icon purple"></i>
                  <p>Desarrollador</p>
                </router-link>
              </li>
              
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>

<script>
    export default {
        props: ['ruta', 'usuario', 'listPermisos','listRoles'],
        data() {
            return {
                fullscreenLoading: false
            }
        },
        mounted() {
            
        },
        methods: {
            
        },
        
    }
</script>

<style>

</style>
