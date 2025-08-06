<template>
    <form :action="url" method="post">
        <input type="hidden" name="id" :value="id">
        <input type="hidden" name="_token" :value="token">
        <div class="mb-3">
            <label for="codigo" class="form-label">Codigo de confirmación
                <a :href="reenvio">
                    Reenviar codigo
                </a>
            </label>
            <input type="number" class="form-control" id="codigo" name="codigo" maxlength="6" placeholder="Escriba aquí"
                required v-model="codigo" />
            <small id="helpCodigo" class="form-text text-muted">Para completar la
                solicitud, debe ingresar el codigo enviado al grupo de telegram de
                mantenimiento de habitaciones de esta sucursal</small>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary" type="submit" :disabled="inValid">
                <span class="mdi mdi-send"></span>
                Enviar
            </button>
        </div>

    </form>
</template>
<script>

export default {

    props: {
        id: String,
        url: String,
        reenvio: String,
        token: String
    },
    data() {
        return {
            codigo: null
        };
    },
    computed: {
        inValid: function () {
            return !this.codigo || this.codigo.toString().length != 6;
        }
    }
};
</script>
