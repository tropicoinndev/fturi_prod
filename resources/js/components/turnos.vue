<template>
    <div class="input-group">
        <select class="form-select" @change="setTurno()" v-model="turnoSelected">
            <option value="">Cambiar turno</option>
            <option :value="actual">Turno actual</option>
            <option :value="t.cid" v-for="t in turnos" v-bind:key="t.cid">
                {{ t.fecha }}
                {{ t.opcion.turno }}
            </option>
        </select>
        <div class="input-group-append">
            <span class="mdi h5"
                :class="{ 'mdi-check': status && change, 'mdi-alert-circle': !status && change, 'mdi-equal': !change }"
                :title="message"></span>
        </div>
    </div>
</template>
<script>

export default {

    props: {
        turnos: Object,
        comprobantes_id: String,
        url: String,
        actual: String,
    },
    data() {
        return {
            message: '',
            turnoSelected: '',
            status: null,
            change: false,
        };
    }, methods: {
        setTurno: function () {
            console.log(this.turnoSelected);

            if (this.turnoSelected && this.turnoSelected.length > 100) {
                axios.post(this.url, {
                    comprobantes_id: this.comprobantes_id,
                    turno: this.turnoSelected,
                }).then(rs => {
                    if (rs.data.status)
                        this.setStatus(rs.data.status);
                    else
                        this.message = rs.data.message;
                }).catch(e => console.log(e));
            } else this.message = "Debe seleccionar un turno valido";
        },
        setStatus: function (status) {
            this.change = true;
            this.status = status;
            setTimeout(() => {
                this.status = null;
                this.change = false;
            }, 3000);
        }
    }

};
</script>
