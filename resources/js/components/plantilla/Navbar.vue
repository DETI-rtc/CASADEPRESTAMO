<template>
    <nav class="main-header navbar navbar-expand-md navbar-white navbar-light  ">
        <div class="container-fluid">
            <a href="" class="navbar-brand">
                <img src="" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="display:none">

            </a>
            <button class="navbar-toggler order-1" type="button" data-bs-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item mx-1">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                                class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item mx-1">
                        <router-link to="/home" class=" btn btn-outline-success col-md-12 " v-if="listRoles.includes('Administrador') || listRoles.includes('Supervisor') || listRoles.includes('Gerente') || listRoles.includes('Agente') "><i class="fas fa-chart-line"></i> Panel </router-link>
                    </li>
                    <li class="nav-item mx-1" v-if="listRoles.includes('Administrador') || listRoles.includes('Supervisor') || listRoles.includes('Gerente') || listRoles.includes('Agente') || listRoles.includes('Asesor')">
                        <router-link to="/simulador" class="btn btn-outline-warning col-md-12 ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            Simulador
                        </router-link>
                    </li>
                    <li class="nav-item mx-1" v-if="listRoles.includes('Administrador') || listRoles.includes('Tesorero') ">
                        <router-link to="/listcomprobantes" class="btn btn-outline-warning col-md-12 ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            Comprobantes
                        </router-link>
                    </li>
                    <li class="nav-item mx-1" v-if="listRoles.includes('Administrador') || listRoles.includes('Tesorero') ">
                        <router-link to="/generarcompro" class="btn btn-outline-warning col-md-12 ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            Genrerar Comprobantes
                        </router-link>
                    </li>
                    <li class="nav-item mx-1" v-if="listRoles.includes('Administrador') || listRoles.includes('Supervisor') || listRoles.includes('Gerente') || listRoles.includes('Agente') ">
                        <router-link to="/creditos" class="btn btn-outline-info col-md-12  ">
                            <i class="nav-icon fa fa-credit-card green"></i> Creditos </router-link>
                    </li>
                    <li class="nav-item mx-1" v-if="listRoles.includes('Administrador') || listRoles.includes('Supervisor') || listRoles.includes('Gerente') || listRoles.includes('Agente') ">
                        <router-link to="/cancelacion-pagos" class="btn btn-outline-danger col-md-12  ">
                            <i class="nav-icon fa fa-credit-card green"></i> Cancela Creditos </router-link>
                    </li>
                    <li class="nav-item mx-1" v-if="listRoles.includes('Administrador') || listRoles.includes('Tesorero') || listRoles.includes('Gerente') || listRoles.includes('temporal') || listRoles.includes('Supervisor') ">
                        <router-link to="/registropago" class="btn btn-outline-dark col-md-12  ">
                            <i class="nav-icon fas fa-cash-register green"></i> Pagos </router-link>
                    </li>
                    <template
                        v-if="listRoles.includes('Administrador') || listRoles.includes('Supervisor') || listRoles.includes('Gerente')">
                        <li class="nav-item mx-1">
                            <router-link to="/asignameta" class="btn btn-outline-success col-md-12  ">
                                <i class="nav-icon fas fa-file-signature green"></i> Asignar Metas </router-link>
                        </li>
                    </template>
                    <li class="nav-item mx-1" v-if="listRoles.includes('Administrador') || listRoles.includes('Supervisor') || listRoles.includes('Gerente') || listRoles.includes('Agente') ">  
                        <router-link to="/clientes" class="btn btn-outline-warning col-md-12  ">
                            <i class="nav-icon fa fa-users green"></i> Clientes </router-link>
                    </li>
                    <li class="nav-item mx-1">
                        <router-link to="/convenios" class="btn btn-outline-primary col-md-12  " v-if="listRoles.includes('Administrador') || listRoles.includes('Supervisor') || listRoles.includes('Gerente') || listRoles.includes('Agente') || listRoles.includes('Asesor')">
                            <i class="nav-icon fas fa-people-arrows green"></i> Convenios </router-link>
                    </li>

                    <li class="nav-item mx-1 dropdown hidden-caret" v-if="listRoles.includes('Administrador') || listRoles.includes('Supervisor') || listRoles.includes('Gerente') || listRoles.includes('Agente') || listRoles.includes('Asesor')  ">
                        <a class="btn btn-outline-secondary col-md-12" data-bs-toggle="dropdown" href="#"
                            aria-expanded="false">
                            <i class="nav-icon fas fa-chart-area green"></i>
                            Reportes
                        </a>
                        <div class="dropdown-menu quick-actions quick-actions-info animated fadeIn">
                            <div class="quick-actions-header">
                                <span class="title mb-1">Reportes Gerenciales</span>

                            </div>
                            <div class="quick-actions-scroll scrollbar-outer">
                                <div class="quick-actions-items">
                                    <div class="row m-0">
                                        <!-- <a class="col-6 col-md-4 p-0" href="#">
                                            <div class="quick-actions-item">
                                                <i class="fa fa-users"></i>
                                                <span class="text">Reporte Cuota Saldo</span>
                                            </div>
                                        </a>
                                        <a class="col-6 col-md-4 p-0" href="#">
                                            <div class="quick-actions-item">
                                                <i class="fa fa-bars"></i>
                                                <span class="text">Reporte Creditos</span>
                                            </div>
                                        </a>
                                        <a class="col-6 col-md-4 p-0" href="#">
                                            <div class="quick-actions-item">
                                                <i class="fa fa-credit-card"></i>
                                                <span class="text"> Cuotas Pagadas</span>
                                            </div>
                                        </a>
                                        <a class="col-6 col-md-4 p-0" href="#">
                                            <div class="quick-actions-item">
                                                <i class="fa fa-users"></i>
                                                <span class="text">Total de Creditos</span>
                                            </div>
                                        </a>
                                        <a class="col-6 col-md-4 p-0" href="#">
                                            <div class="quick-actions-item">
                                                <i class="fa fa-users"></i>
                                                <span class="text">Cuotas por Cobrar</span>
                                            </div>
                                        </a>
                                        <a class="col-6 col-md-4 p-0" href="#">
                                            <div class="quick-actions-item">
                                                <i class="fa fa-users"></i>
                                                <span class="text">Reporte Interes</span>
                                            </div>
                                        </a>
                                        <a class="col-6 col-md-4 p-0" href="#">
                                            <div class="quick-actions-item">
                                                <i class="fa fa-users"></i>
                                                <span class="text">Cumplimiento de Metas</span>
                                            </div>
                                        </a> -->
                                        <!-- <a class="col-6 col-md-4 p-0" href="#">
                                            <div class="quick-actions-item">
                                                <i class="fa fa-users"></i>
                                                <span class="text">Control de Ventas de Crédito</span>
                                            </div>
                                        </a> -->

                                        <router-link class="col-6 col-md-4 p-0" to="/reportes/controlventascredito" >
                                            <div class="quick-actions-item">
                                                <i class="fab fa-cc-mastercard"></i>
                                                <span class="text">Control de Ventas de Crédito</span>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item mx-1 dropdown hidden-caret" v-if="listRoles.includes('Administrador') || listRoles.includes('Supervisor') || listRoles.includes('Gerente') || listRoles.includes('Agente') || listRoles.includes('Asesor') || listRoles.includes('Tesorero') || listRoles.includes('temporal')">
                        <a class="btn btn-outline-secondary col-md-12" data-bs-toggle="dropdown" href="#"
                            aria-expanded="false">
                            <i class="nav-icon fas fa-chart-area green"></i>
                            Reportes
                        </a>
                        <div class="dropdown-menu quick-actions quick-actions-info animated fadeIn">
                            <div class="quick-actions-header">
                                <span class="title mb-1">Reportes </span>
                            </div>
                            <div class="quick-actions-scroll scrollbar-outer">
                                <div class="quick-actions-items">
                                    <div class="row m-0">
                                        <router-link class="col-6 col-md-4 p-0" to="/reportes/mensual" >
                                            <div class="quick-actions-item">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                                <span class="text">Pagos Mensual</span>
                                            </div>
                                        </router-link>
                                        <router-link class="col-6 col-md-4 p-0" to="/reportes/detallado" >
                                            <div class="quick-actions-item">
                                                <i class="fas fa-file-invoice"></i>
                                                <span class="text">Pago Detallado</span>
                                            </div>
                                        </router-link>
                                        <router-link class="col-6 col-md-4 p-0" to="/reportes/comprobantes" >
                                            <div class="quick-actions-item">
                                                <i class="fas fa-ticket-alt"></i>
                                                <span class="text">Pago Comprobantes</span>
                                            </div>
                                        </router-link>
                                        <router-link class="col-6 col-md-4 p-0" to="/reportediario" >
                                            <div class="quick-actions-item">
                                            <i class="nav-icon fa fa-print green"></i>
                                            <span class="text"> Reportes Detallado Comprobantes</span>
                                            </div>
                                        </router-link>

                                        <router-link class="col-6 col-md-4 p-0" to="/reportes/deudarestante" >
                                            <div class="quick-actions-item">
                                                <i class="fas fa-ticket-alt"></i>
                                                <span class="text">Deuda Restante</span>
                                            </div>
                                        </router-link>
                                        <router-link class="col-6 col-md-4 p-0" to="/reportes/cuotanopagada" >
                                            <div class="quick-actions-item">
                                                <i class="fas fa-ticket-alt"></i>
                                                <span class="text">Cuotas No Pagadas</span>
                                            </div>
                                        </router-link>
                                        <router-link class="col-6 col-md-4 p-0" to="/reportes/deudacapital" >
                                            <div class="quick-actions-item">
                                                <i class="fas fa-ticket-alt"></i>
                                                <span class="text">Retorno de Capital</span>
                                            </div>
                                        </router-link>
                                        <router-link class="col-6 col-md-4 p-0" to="/reportes/totalreenganche" >
                                            <div class="quick-actions-item">
                                                <i class="fas fa-ticket-alt"></i>
                                                <span class="text">Total Reenganches</span>
                                            </div>
                                        </router-link>
                                        <router-link class="col-6 col-md-4 p-0" to="/reportes/deudaporcobrar" >
                                            <div class="quick-actions-item">
                                                <i class="fas fa-ticket-alt"></i>
                                                <span class="text">Deuda X Cobrar</span>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </li>
                </ul>
            </div>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto order-1 order-md-3" style="display: -webkit-box;">
                <notificaciones :notificacion="newnotificacion"></notificaciones>
                <li>
                    <div class="dropdown show">
                        <a href="#"
                            class="dropdown-toggle navbar-list iq-waves-effect d-flex align-items-center bg-teal redondear"
                            role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <img :src="avatar" class="img-nuev redondear mr-3" alt="user">
                            <div class="caption">
                                <h6 class="mb-0 line-height text-white">{{usuario}}</h6>
                                <span class="font-size-12 text-white">{{listRoles[0]}}</span>
                            </div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <router-link to="/perfil" class=" dropdown-item iq-sub-card bg-primary-hover">
                                <div class="media align-items-center">
                                    <div class="rounded iq-card-icon iq-bg-primary">
                                        <i class="ri-lock-line"></i>
                                    </div>
                                    <div class="media-body ml-3">
                                        <h6 class="mb-0 ">Perfil</h6>
                                        <p class="mb-0 font-size-12">Configurar Perfil</p>
                                    </div>
                                </div>
                            </router-link>
                            <div class="d-inline-block w-100 text-center p-3">
                                <a class="btn btn-outline-secondary" href="/logout"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar
                                    Session
                                    <i class="nav-icon fa fa-sign-out-alt"></i></a>
                                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" :value="csrf">
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        props: ['ruta', 'avatar', 'usuario', 'listRoles', 'notificacion'],
        data() {
            return {

                newnotificacion: this.notificacion,

                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

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
