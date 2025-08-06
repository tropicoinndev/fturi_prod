<template>
    <div class="input-group">
        <div class="input-group-append mx-2">
            <span class="mdi h5"
                :class="{ 'mdi-check': status && change, 'mdi-alert-circle': !status && change, 'mdi-equal': !change }"
                :title="message"></span>
        </div>
        <select class="form-select" v-model="idSelected" @change="setChange()">
            <option selected value="">Seleccione una opción</option>
            <option :value="d.id" v-for="d in data" v-bind:key="d.cid">{{ d[name] }}</option>
        </select>
    </div>
</template>
<script>

export default {
    props: {
        data: Object,
        id: String,
        url: String,
        name: String,
        actual: String,
    },
    data() {
        return {
            message: '',
            status: null,
            change: false,
            idSelected: null,
        };
    }, created() {
        this.idSelected = this.actual;

    },

    methods: {
        setChange: function () {
            this.status = null;
            this.change = false;
            axios.post(this.url, {
                id: this.id,
                value: this.idSelected,
            }).then(rs => {
                console.log(rs);

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
