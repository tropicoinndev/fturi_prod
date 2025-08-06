<template>
    <div class="input-group has-validation mb-3">
        <select class="form-select" aria-label="Default select example" v-model="empleadoSelected">
            <option value="">Seleccione un empleado</option>
            <option :value="e.cuser" v-for="e in empleados">
                {{ e.users.name }}
            </option>
        </select>


        <label class="btn btn-warning input-group-text" data-toggle="buttons">
            <input type="checkbox" v-model="prioridad"> Prioridad
        </label>


    </div>
    <div class="input-group has-validation mb-3" v-if="!atajos">
        <input type="number" class="form-control mb-1" :class="{ 'is-invalid': message.length > 0 }" name="minutos"
            min="1" :id="'minutos_' + producto.id" step="1" aria-describedby="helpId" placeholder="Cantidad en minutos"
            required v-model="timer" />
        <span class="input-group-text btn ct2" @click="setTimer()"
            :class="{ 'disabled': !empleadoSelected || empleadoSelected.length == 0 }">
            <span class="mdi mdi-alarm-plus"></span>
            Iniciar
        </span>
        <div class="invalid-feedback" v-if="message.length > 0">
            {{ message }}
        </div>
    </div>
    <div class="row" v-if="atajos">
        <div class="col-12">
            <button type="button" class="btn btn-sm ct2 float-end me-2" @click="setTimer(20)"
                :disabled="!empleadoSelected || empleadoSelected.length == 0">
                <span class="mdi mdi-alarm-plus"></span>
                20m
            </button>
            <button type="button" class="btn btn-sm ct2 float-end me-2" @click="setTimer(30)"
                :disabled="!empleadoSelected || empleadoSelected.length == 0">
                <span class="mdi mdi-alarm-plus"></span>
                30m
            </button>
            <button type="button" class="btn btn-sm ct2 float-end me-2" @click="setTimer(45)"
                :disabled="!empleadoSelected || empleadoSelected.length == 0">
                <span class="mdi mdi-alarm-plus"></span>
                45m
            </button>
            <button type="button" class="btn btn-sm ct2 float-end me-2" @click="setTimer(60)"
                :disabled="!empleadoSelected || empleadoSelected.length == 0">
                <span class="mdi mdi-alarm-plus"></span>
                60m
            </button>
        </div>
    </div>
    <div class="col-12 m-2 mt-4">
        <btn-terminado :producto="producto" :url="url.completo" @filtro="setFiltros"></btn-terminado>
        <button type="button" class="btn btn-sm ct1 me-2 float-end"
            :data-bs-target="'#mdlCancelarPedido' + producto.cid" data-bs-toggle="modal">
            <span class="mdi mdi-timer-cancel"></span>
            Negar
        </button>
    </div>

    <div :id="'mdlCancelarPedido' + producto.cid" class="modal fade" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="false" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-bg-danger">
                    <h5 class="modal-title" id="my-modal-title">Negar pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        @click="cancelarNegar()"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12 mb-2">
                            Comanda # {{ producto.comandas_id }}
                        </div>
                        <div class="col-12 mb-2">
                            {{ producto.cantidad }} {{ producto.dprecio.detalle }}
                        </div>
                        <div class="col-12 mb-4">
                            Solicitado por
                            <span class="text-capitalize">
                                {{ producto.user_solicita.name }}
                            </span>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" name="" id="observaciones" rows="3" v-model="observacion"
                                placeholder="Escriba las razones por las que no se podrá entregar este pedido"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-light">
                                    <input type="checkbox" v-model="this.confirmacionNegacion">
                                    Confirmo que el pedido se negara por las razones descritas en el campo
                                    observaciones.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" role="button"
                        :disabled="!this.confirmacionNegacion || this.observacion.length < 4" @click="setNegacion()"
                        data-bs-dismiss="modal">
                        Negar pedido
                    </button>
                    <button class="btn btn-light mx-3" data-bs-dismiss="modal" role="button"
                        @click="cancelarNegar()">Cerrar</button>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
//cSpell:ignore confirmacion, observacion
import btnTerminado from './btn-terminado.vue';
export default {
    emits: ['timer', 'filtrop'],
    components: {
        btnTerminado,
    },
    props: {
        empleados: Object,
        producto: Object,
        url: Object,
        atajos: Boolean,
    },
    data() {
        return {
            message: '',
            timer: null,
            empleadoSelected: '',
            prioridad: false,
            confirmacion: false,
            confirmacionNegacion: false,
            observacion: '',
        };
    }, methods: {
        setTimer: function (minutos) {

            if (this.empleadoSelected.length > 0) {
                if (minutos)
                    this.timer = minutos;
                axios.post(this.url.timer, {
                    id: this.producto.cid,
                    espera: this.timer,
                    empleado: this.empleadoSelected,
                    prioridad: this.prioridad,
                }).then(rs => {
                    if (rs.data.producto) {
                        this.$emit('timer', rs.data.producto);
                        this.timer = null;
                        this.empleadoSelected = '';
                        this.prioridad = false;
                    }
                    else
                        this.message = rs.data.message;
                }).catch(e => console.log(e));
            } else this.message = "Debe seleccionar un empleado";
        },
        setFiltros: function (producto, message = null) {

            if (producto.id != null) {
                this.$emit('filtrop', producto, message);

            }

        },
        setNegacion: function () {
            if (this.confirmacionNegacion) {
                axios.post(this.url.negado, {
                    id: this.producto.cid,
                    observacion: this.observacion,
                }).then(rs => {
                    if (rs.data.producto)
                        this.$emit('filtrop', rs.data.producto, rs.data.message);
                    else
                        this.message = rs.data.message;
                    this.cancelarNegar();
                }).catch(e => {

                    alert('Ocurrió un error al conectarse con el servidor, por favor recargue la pagina, en caso que el problema continue informe a informatica@tropicoinn.com.sv');
                });
            } else alert('Antes debe confirmar que se requiere guardar.');
        },
        cancelar: function () {
            this.confirmacion = false;
        },
        cancelarNegar: function () {
            this.confirmacionNegacion = false;
            this.observacion = '';
        }
    }, mounted() {
        //console.log('Component timer')
    },
};
</script>