<template>
    <button type="button" class="btn btn-sm ct2 me-2 float-end" :data-bs-target="'#mdlTermindado' + producto.cid"
        data-bs-toggle="modal">
        <span class="mdi mdi-check"></span>
        Terminado
    </button>
    <div :id="'mdlTermindado' + producto.cid" class="modal fade" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="false" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Confirmación de entrega</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        @click="cancelar()"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12 mb-2 text-left">
                            Comanda # {{ producto.comandas_id }}
                        </div>
                        <div class="col-12 mb-2 text-left">
                            {{ producto.cantidad }} {{ producto.dprecio.detalle }}
                        </div>
                        <div class="col-12 mb-4 text-left">
                            Solicitado por
                            <span class="text-capitalize">
                                {{ producto.user_solicita.name }}
                            </span>
                        </div>
                        <div class="col-12 mb-4 text-left">
                            <div class="btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-light">
                                    <input type="checkbox" v-model="this.confirmacion"> Confirmo que sera entregado el
                                    producto
                                    solicitado de forma inmediata
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" role="button" :disabled="!this.confirmacion"
                                @click="setEntrega()" data-bs-dismiss="modal">
                                Terminado
                            </button>
                            <button class="btn btn-light mx-3" data-bs-dismiss="modal" role="button"
                                @click="cancelar()">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<style scoped>
.text-left {
    text-align: left;
}
</style>
<script>
//cSpell:ignore confirmacion, observacion

export default {
    emits: ['filtro'],
    props: {
        producto: Object,
        url: String,
    },
    data() {
        return {
            confirmacion: false,
        };
    }, methods: {

        setEntrega: function () {

            if (this.confirmacion) {
                axios.post(this.url, {
                    id: this.producto.cid,
                }).then(rs => {
                    //console.log(rs)
                    if (rs.data.producto && rs.data.producto.id != null) {
                        this.$emit('filtro', rs.data.producto, rs.data.message);

                    } else
                        alert('No se pudo terminar el pedido, recargue la pagina e intente de nuevo');
                    this.cancelar();
                }).catch(e => {
                    console.log(e)
                    alert('Ocurrió un error al conectarse con el servidor, por favor recargue la pagina, en caso que el problema continue informe a informatica@tropicoinn.com.sv');
                });
            } else alert('Antes debe confirmar que se requiere guardar.');
        },

        cancelar: function () {
            this.confirmacion = false;
        },
    }, mounted() {
        //console.log('Component btnTerminado')
    },
};
</script>
