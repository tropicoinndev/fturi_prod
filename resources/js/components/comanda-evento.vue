<template>
    <div class="card card-comanda">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Observación -->
                <div class="col-4" v-show="inputObservacion">
                    <textarea class="form-control mb-3" rows="3"
                        placeholder="Escriba aquí las observaciones para quien preparará el producto."
                        v-model="observacion"></textarea>
                    <a href="#" class="text-decoration-none text-observacion"
                        @click="inputObservacion = false">Ocultar</a>
                </div>

                <!-- Nombre del producto y salida de bodega -->
                <div class="col-4" v-show="!inputObservacion">
                    <div class="fs-5 fw-light producto text-uppercase">{{ l.detalle }}</div>
                    <div class="caja text-uppercase" v-if="l.token !== 1101">No requiere existencias.</div>
                    <div class="caja text-uppercase" v-if="l.token === 1101">Salida de {{ l.bodega }}</div>
                    <span class="mdi mdi-format-list-checks fs-5 text-observacion" v-show="observacion.length > 0"
                        :title="observacion"></span>
                    <a href="#" class="text-decoration-none text-observacion" @click="inputObservacion = true">Agregar
                        observación</a>
                </div>

                <!-- Cantidad -->
                <div class="col-3">
                    <div class="d-flex align-items-center">
                        <label for="cantidad" class="mx-1">Cantidad:</label>
                        <input type="number" class="form-control mb-2" id="cantidad" min="1" step="1"
                            placeholder="Cantidad" v-model="cantidad" @input="validateInteger">
                    </div>
                </div>

                <!-- Precio unitario -->
                <div class="col-3" v-if="canEditPrices">
                    <div class="d-flex align-items-center">
                        <label for="precio_unitario" class="mx-1">P.Unit:</label>
                        <input type="number" class="form-control mb-2" id="precio" min="1" name="precioModificado"
                            placeholder="Precio unitario"
                            :class="{ 'is-invalid': precioModificado != null && !isValidPrecio, 'is-valid': isValidPrecio }"
                            v-model="precioModificado">
                    </div>
                </div>

                <!-- Botón de agregar y total -->
                <div class="col-2 d-flex align-items-center justify-content-end">
                    <div class="me-2">$
                        {{ (parseFloat(this.cantidad * this.precioModificado) || parseFloat(this.cantidad * l.
                        precio)) . toFixed(2) }}
                    </div>
                    <div v-if="!comprobante" class="h-10 w-20"
                        :class="{ 'text-danger': cantidad != null && !isValid, 'at': isValid }"
                        :title="!isValid ? 'Debe agregar una cantidad válida' : 'Presione para agregar'">
                        <button class="btn btn-light m-1" @click="agregarDetalleComanda()">+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    emits: ['detalle'],
    props: {
        l: {
            type: Object,
            required: true
        },
        comanda: {
            type: String,
            required: true
        },
        comprobante: {
            type: Boolean,
            required: true
        },
        canEditPrices: {
            type: Boolean,
            required: true
        },
        url: {
            type: String,
            required: true
        },
    },

    data() {
        return {
            inputObservacion: false,
            cantidad: 1,
            precioModificado: this.l.precio,
            observacion: '',
        };
    },
    methods: {
        validateInteger(event) {
            const value = event.target.value;
            // Remover cualquier punto decimal
            if (value.includes('.')) {
                event.target.value = parseInt(value);
                this.cantidad = parseInt(value);
            }
        },
        agregarDetalleComanda: function () {
            if (this.cantidad <= 0) {
                alert('La cantidad debe ser mayor que cero.');
            } else {
                axios.post(this.url, {
                    cantidad: this.cantidad,
                    comanda: this.comanda,
                    observacion: this.observacion,
                    precio: this.l.cid,
                    precioModificado: this.precioModificado,
                    lote: this.l.lote,
                }).then((r) => {
                    if (r.data) {
                        console.log(r.data);
                        this.$emit('detalle', r.data);
                    }
                }).catch((err) => console.log(err));
            }
        },
    },
    computed: {
        isValid() {
            return Number.isInteger(this.cantidad) && this.cantidad > 0;
        },

        isValidPrecio() {
            return this.precioModificado != null && !isNaN(this.precioModificado) && this.precioModificado > 0;
        }
    }
};
</script>
