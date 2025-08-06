<template>
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal-content-bodega">

            <div style="border-bottom: none;" class="modal-header">
                <h1 class="modal-title fs-5 text-white text-uppercase ms-3" id="staticBackdropLabel">Seleccione Una
                    Bodega</h1>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>

            <div class="modal-body">
                <div style="background: #fff; margin-top: -25px; border-bottom: solid 5px rgba(84, 110, 122, 51%);"
                    class="panel-card-modal p-3 mb-3">

                    <div class="row">
                        <div v-for="b in bodegas" class="col-12" v-bind:key="b.id">
                            <!--No se mostrara en el listado la bodega que haya sido seleccionada-->
                            <div v-if="b !== bs" @click="setBodega(b)" data-bs-dismiss="modal">
                                <!--Se a grego el atributo para cerrar la modal-->
                                <div style="cursor: pointer;" class="panel-card mb-2">
                                    <div class="row">

                                        <div class="col-10">
                                            <b style="font-size: 12px; color: #37474F;" class="ms-3">{{ b.bodegas.bodega
                                                }}</b>
                                        </div>

                                        <div class="col-2 text-end">
                                            <i style="font-size: 15px; color: #37474F;"
                                                class="bi bi-chevron-right me-3"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>
/*Configuraciones de la modal de bodegas*/
.modal-content-bodega {
    background: #37474F;
    position: fixed;
    /*Indica la posicion inferior a la que se colocara la modal*/
    bottom: 0;
    left: 0;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
}

/*Resolucion tablet*/
@media(min-width: 768px) {
    .modal-content-bodega {
        right: 0;
        /*Nos aseguramos que la modal no tenga margen a la derecha*/
        margin: 0 auto;
        /*Centramos la modal en dispositivos tablet*/
        width: 500px;
        /*En resolucion movil tendra un ancho especifico*/
    }
}
</style>

<script type="module">
import axios from "axios";

export default {
    props: {
        bodegas: Object,//Recibimos como propiedad un objeto de bodegas
    },
    data() {
        return {
            bodegaSelected: {
                id: null,
                name: null,
            },

            //Se usa para evitar repetir la misma bodega que esta en el listado de la modal...
            //Si ya esta seleccionada la bodega, no se mostrara en el listado
            bs: null,
        }
    },
    created() {
        //Almacenar bodega en el localStorage
        if (this.bodegaSelected.id == null) {
            if (localStorage.getItem('bodegaSelectedId') != null) {//Si ya hay algo en el localStorage, tomamos los datos segun el id
                let bodegaEncontrada = this.bodegas.find(i => i.bodegas_id == localStorage.getItem('bodegaSelectedId'));

                if (bodegaEncontrada) {
                    this.bs = bodegaEncontrada;

                    this.bodegaSelected.id = localStorage.getItem('bodegaSelectedId');
                    this.bodegaSelected.name = localStorage.getItem('bodegaSelectedName');
                }
            }
            else {//De lo contrario, sino hay datos... seleccionamos la primer bodega por defecto y la guardamos en el localStorage
                this.bs = this.bodegas[0];
                this.saveBodegaToLocalStorage();
            }
        }
    },
    mounted() {
        //console.log('Cambiar bodega mounted.');
    },
    methods: {
        setBodega(b) {
            this.bs = b;

            //Emitir un evento con la bodega seleccionada
            this.$emit('bodega-seleccionada', {
                id: b.bodegas.id,
                name: b.bodegas.bodega,
            });
        },
        saveBodegaToLocalStorage() {
            //Siempre se almacenaran datos en el localStorage,
            //Ya sea si es una bodega por defecto o una pre-seleccionada
            this.bodegaSelected.id = this.bs.bodegas.id;
            this.bodegaSelected.name = this.bs.bodegas.bodega;

            localStorage.setItem('bodegaSelectedId', this.bodegaSelected.id);
            localStorage.setItem('bodegaSelectedName', this.bodegaSelected.name);
        },
    },
    computed: {

    },
}
</script>
