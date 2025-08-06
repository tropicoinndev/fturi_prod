<template>
    <div class="control">
        <div class="row" v-if="!change">
            <div class="col-5">
                <select class="form-select" v-model="identificaciones">
                    <option value="" selected disabled>
                        ---Seleccione una opcion---
                    </option>
                    <option v-for="i in getList" :key="i.id" :value="i">
                        {{ i.identificacion }}
                    </option>
                </select>
            </div>
            <div class="col-5">
                <input type="text" class="form-control" :class="{ 'is-invalid': !isValid, 'is-valid': isValid }"
                    name="numero" :disabled="identificaciones == null"
                    :placeholder="identificaciones?.info ?? 'Seleccione una identificacion'"
                    :pattern="identificaciones?.regex ?? ''" v-model="numero" />
            </div>
            <div class="col-2">
                <button class="btn btn-primary" type="submit" :disabled="!isValid" @click="setChange">
                    <span class="mdi mdi-content-save-check h5"></span>
                </button>
            </div>
        </div>
        <div class="row" v-if="change">
            <div class="alert alert-success" role="alert" v-if="status">
                <strong>Se guardaron los cambios</strong>
            </div>
            <div class="alert alert-success" role="alert" v-if="!status">
                <strong>Error:</strong> {{ message }}
            </div>

        </div>
    </div>
</template>
<style scoped>
.pointer {
    cursor: pointer;
}

.listControl {
    position: absolute;
    z-index: 120;
}
</style>
<script>

export default {
    props: {
        id: String,
        tipo: String,
        url: String,
        list: Object,
    },
    data() {
        return {
            message: '',
            status: null,
            change: false,
            identificaciones: null,
            numero: null
        };
    },
    computed: {
        isValid: function () {
            if (this.identificaciones != null && this.identificaciones.id > 0) {
                let reg = new RegExp(this.identificaciones.regex);
                return reg.test(this.numero);
            }
            return false;
        },
        getList: function () {
            return this.list.filter(i => i.tipo_cliente == parseInt(this.tipo));
        }
    },
    methods: {
        setChange: function () {
            this.status = null;
            this.change = false;
            axios.post(this.url, {
                id: this.id,
                identificaciones: this.identificaciones.id,
                numero: this.numero,
            }).then(rs => {
                if (rs.data.status)
                    this.setStatus(rs.data.status);
                else
                    this.message = rs.data.message;
            }).catch(e => console.log(e));

        },
        setStatus: function (status) {
            this.change = true;
            this.status = status;

            setTimeout(() => {
                this.status = null;
                this.change = false;
            }, 5000);
        }
    }

};
</script>
