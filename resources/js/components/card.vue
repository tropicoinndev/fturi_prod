<template>
    <div class="card card-comanda">
        <div class="card-body">
            <div class="row">
                <div class="col-1 text-center m-auto" :class="{ h3: l.token != 1101 }">
                    {{ l.token == 1101 ? l.existencia_group : '∞' }}
                </div>
                <div class="col-7">
                    <div class="row">
                        <div class="col-12 fs-5 fw-light producto text-uppercase">
                            {{ l.detalle }}
                        </div>
                        <div class="col-12 caja text-uppercase" v-if="l.token != 1101">No requiere existencias.</div>
                        <div class="col-12 caja text-uppercase" v-if="l.token == 1101">Salida de {{ l.bodega }}</div>
                        <div class="col-12 creacion text-uppercase" v-if="l.token == 1101">
                            VEN. {{ l.ven }} · ({{ l.vencimiento }})
                        </div>
                        <div class="col-12 tiempo" v-if="l.token == 1101">
                            Lote:
                            <span class="creacion">
                                {{ l.lote }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="row text-end" v-show="!inputObservacion">
                        <div class="col-12 caja fw-bolder mb-3">$ {{ parseFloat(l.precio).toFixed(2) }}</div>
                        <div class="col-12">
                            <div class="input-group mb-2">
                                <input class="form-control" type="number" min="1" step="1" placeholder="Cantidad"
                                    :max="l.token == 1101 ? l.existencia_group : ''"
                                    :class="{ 'is-invalid': cantidad != null && !isValid, 'is-valid': isValid }"
                                    v-model="cantidad" />
                                <div class="input-group-append">
                                    <button class="input-group-text btn-light fs-4 rounded-0"
                                        @click="agregarDestalleComanda()" :disabled="!isValid" :class="{
                                            'text-danger': cantidad != null && !isValid,
                                            'text-bg-success': isValid,
                                        }"
                                        :title="!isValid ? 'Debe agregar una cantidad valida' : 'Presione para agregar'">
                                        <span class="mdi mdi-plus"></span>
                                    </button>
                                </div>
                            </div>
                            <span class="mdi mdi-format-list-checks fs-5 text-observacion"
                                v-show="observacion.length > 0" :title="observacion"></span>
                            <a href="#" class="text-decoration-none text-observacion" @click="inputObservacion = true">
                                Agregar observación
                            </a>
                        </div>
                    </div>
                    <div class="row text-end" v-show="inputObservacion">
                        <div class="col-12">
                            <div class="mb-3">
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                                    placeholder="Escriba las observaciones necesarias para producción."
                                    v-model="observacion" maxlength="200"></textarea>
                            </div>
                            <a href="#" class="text-decoration-none text-observacion" @click="inputObservacion = false">
                                Ocultar </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    //cSpell:ignore aceptacion, duracion, dias, observacion
    emits: ['detalle'],
    props: {
        l: Object,
        comanda: String,
        url: String,
    },
    data() {
        return {
            inputObservacion: false,
            cantidad: null,
            observacion: '',
        };
    },
    methods: {
        agregarDestalleComanda: function () {
            axios.post(this.url, {
                cantidad: this.cantidad,
                comanda: this.comanda,
                observacion: this.observacion,
                precio: this.l.cid,
                lote: this.l.lote,
            }).then((r) => {
                if (r.data) {
                    console.log(r.data);
                    this.$emit('detalle', r.data);
                }
            }).catch((err) => console.log(err));
        },
    },
    computed: {
        isValid() {
            if (this.l.token == 1101)
                return this.cantidad != null && this.cantidad > 0 && Number.isInteger(this.cantidad) && this.cantidad <= this.l.existencia_group;
            else return this.cantidad != null && this.cantidad > 0 && Number.isInteger(this.cantidad);
        },
    },
};
</script>
