<template>
    <div class="col-12" v-if="cliente != null">
        <span class="mdi mdi-close btn text-danger" @click="clearCliente()"></span>
        {{ cliente.cliente }}
    </div>
    <div class="col-12" v-if="cliente == null">
        <label for="" class="form-label">Buscar cliente</label>
        <input type="text" class="form-control" v-model='txtBuscarApi' placeholder="Buscar un cliente"
            @keyup="getClientes" @keydown.up.prevent="selectItem(-1)" @keydown.down.prevent="selectItem(1)"
            @keydown.enter="setCliente()" />

        <div class="d-fixed shadow"
            v-if="txtBuscarApi.length > 4 && clientesList && clientesList.length > 0 && cliente == null">
            <div class="list-group list-group-flush">
                <button type="button" class="list-group-item list-group-item-action " aria-current="true"
                    v-for="(cl, index) in clientesList" @click="setCliente(index)" :key="index"
                    :class="{ 'active': index === selectedIndex }" tabindex="0">
                    {{ cl.cliente }}
                </button>
            </div>
        </div>
    </div>
    <div class="col-12" v-if="txtBuscarApi.length > 5 && clientesList && clientesList.length == 0 && cliente == null">
        <div class="list-group list-group-flush mt-2">
            No se encontró ningún registro con los parámetros de búsqueda, intente buscar con
            otra palabra o revise la ortografía, e intente de nuevo.
        </div>
    </div>
</template>
<style>
.alertas {
    position: fixed;
    bottom: 1%;
    right: 0.5%;
    width: 260px;
}
</style>
<script type="module">
import axios from "axios";
export default {
    props: {
        url: String,
    },
    emits: ['cliente'],
    data() {
        return {
            txtBuscarApi: '',
            clientesList: [],
            cliente: null,
            selectedIndex: 0,
        };
    }, methods: {
        getClientes: function (e) {
            const codTecla = e.keyCode || e.which;
            const tecla = e.key;

            if (
                this.txtBuscarApi.length > 4 &&
                (
                    ![38, 40, 13].includes(codTecla) &&
                    tecla !== 'ArrowUp' &&
                    tecla !== 'ArrowDown' &&
                    tecla !== 'Enter'
                )
            )
                axios.post(this.url, {
                    busqueda: this.txtBuscarApi.toUpperCase(),
                }).then((rs) => {

                    if (rs.data.list)
                        this.clientesList = rs.data.list;
                    else
                        this.clientesList = [];
                }).catch((error) => {
                    console.log('Error: ' + error);
                });

        },
        selectItem: function (index) {
            let v = this.selectedIndex + (index);
            if (v >= this.clientesList.length)
                this.selectedIndex = 0;
            else if (v < 0)
                this.selectedIndex = this.clientesList.length;
            else
                this.selectedIndex = v;
        },
        setCliente: function (index = null) {
            if (index != null)
                this.selectedIndex = index;
            this.cliente = this.clientesList[this.selectedIndex];
            this.clientesList = [];
            this.txtBuscarApi = '';
            if (this.cliente)
                this.$emit('cliente', this.cliente);
        }
        , clearCliente: function () {
            this.cliente = null;
            this.$emit('cliente', this.cliente);
        }
    },
    computed: {

    }, mounted() {
        //console.log('Component clientes api: ' + this.url)
    }, created() {

    }
};
</script>