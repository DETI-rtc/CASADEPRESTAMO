<template>
    <div>
        <!-- Navbar -->
        <Navbar :ruta="ruta" :usuario="authUser" :avatar="newavatar" :listRoles ="roles" ></Navbar>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <Sidebar  :listPermisos ="permisos" :listRoles ="roles"></Sidebar>

        <!-- Contenido Principal del Sitio Web -->
         <div class="content-wrapper px-2 py-4">
            <!-- <section> -->
                <!-- <div class="container-fluid"> -->
                    <router-view> </router-view>
                <!-- </div> -->
            <!-- </section> -->
        </div>
        <!-- /.content-wrapper -->

        <Footer></Footer>

        <!-- Control Sidebar -->
        
    </div>
</template>

<script>
    import Navbar from './plantilla/Navbar'
    import Sidebar from './plantilla/Sidebar'
    import Footer from './plantilla/Footer'
    export default {
        props: ['ruta', 'usuario','avatar'],
        components: {Navbar, Sidebar, Footer},
        data() {
            return {
                authUser: this.usuario,
                newavatar:this.avatar,
                permisos: [],
                roles: [],
            }
        },
        mounted() {
            EventBus.$on('actuausuario', data => {
                this.authUser = data.nombres+' '+data.apellidos;
                this.newavatar = this.ruta+'/storage/'+data.avatar;
            })
            axios.get("/api/permisouser").then(({ data }) => (this.permisos=data)).catch();
            axios.get("/api/roleuser").then(({ data }) => (this.roles = data)).catch();
        },
    }
</script>

<style>

</style>