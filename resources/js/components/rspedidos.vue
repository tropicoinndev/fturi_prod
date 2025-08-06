<template>

</template>
<style>
.alertas {
    position: fixed;
    bottom: 1%;
    right: 0.5%;
    width: 260px;
}
</style>
<script>

export default {
    emits: ['rspedidos'],
    props: {
        chanel: String,
        listen: String,
    },
    data() {
        return {
            comanda_detalle: [],
            message: '',
            type: true
        };
    }, computed: {

    }, mounted() {
        //console.log('Component rspedidos:' + this.chanel)
    }, created() {
        window.Echo.private(this.chanel)
            .listen(this.listen, (d) => {
                //console.log(d);
                this.comanda_detalle = d.comanda_detalles.comandas.detalles_comanda;
                this.message = d.mensaje;
                this.$emit('rspedidos', {
                    comanda_detalle: this.comanda_detalle,
                    message: this.message,
                });
            });
    }
};
</script>
