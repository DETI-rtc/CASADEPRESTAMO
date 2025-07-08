<template>
    <div>
        <!-- Navbar -->
        <panelgerente v-if="roles.includes('Gerente')" ></panelgerente>
        <panelsuper v-if="roles.includes('Supervisor')"></panelsuper>
        <panelotros v-if="roles.includes('Agente')"></panelotros>
        <panelteso v-if="roles.includes('Tesorero')"></panelteso>

    </div>
</template>

<script>
    export default {
        
        
        data() {
            return {
                roles:[],
            }
        },
        mounted() {
            axios.get("/api/roleuser").then(({ data }) => {
                this.roles=data;
                if (this.roles.includes('PRESUPUESTO')) {
                    this.$router.push({ path: '/presupuesto/creditos'});
                }
            }).catch();
        },
    }
</script>
