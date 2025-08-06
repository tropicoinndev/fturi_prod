<template>
    <div class="row">
        <div :class="{ 'col-9': data.req, 'col-11': !data.req }">
            <input type="number" step="any" min="0.01" class="form-control" v-model='precio'
                :placeholder="`Precio: ` + data.precio" @keydown.enter="setChange()" @change="setChange()" />
        </div>
        <div class="col-2" v-if="data.req">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="propinaCheckBox" value="1" v-model="propina"
                    @change="setChange()" />
                <label class="form-check-label" for="propinaCheckBox">Propina</label>
            </div>
        </div>
        <div class="col-1">
            <div class="spinner-border spinner-border-sm" role="status" v-if="change && status == null">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span class="mdi mdi-check align-middle" role="status" v-if="change && status">
            </span>
            <span class="mdi mdi-alert-circle-outline align-middle" role="status"
                v-if="change && status != null && !status">
            </span>
            <span class="mdi mdi-pencil-outline" role="status" v-if="!change">
            </span>
        </div>
    </div>
</template>
<style></style>
<script type="module">
import axios from "axios";
export default {
    props: {
        url: String,
        data: {
            id: String,
            precio: Number,
            propina: Boolean,
            req: Boolean,

        }
    },
    data() {
        return {
            precio: 0,
            propina: null,
            status: null,
            change: false,
        };
    }, methods: {
        setChange: function (e) {
            if (this.precio > 0) {
                this.change = true;
                this.status = null;
                setTimeout(() => {
                    this.change = false;
                }, 1000 * 6);
                axios.post(this.url,
                    {
                        id: this.data.id,
                        precio: this.precio,
                        propina: this.propina
                    }
                ).then((rs) => {
                    if (rs.data)
                        this.status = rs.data.status;
                    if (!rs.data.status) alert(rs.data.message)
                }).catch((error) => {
                    console.log('Error: ' + error);
                    this.status = false;
                });
            }

        },
    }, mounted() {
        this.precio = this.data.precio;
        this.data.req = this.data.req ?? false;
        if (this.data.req)
            this.propina = this.data.propina;
    }
};
</script>