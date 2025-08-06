<template>
    <div class="input-group">
        <div class="input-group-append mx-2">
            <span class="mdi h5"
                :class="{ 'mdi-check': status && change, 'mdi-alert-circle': !status && change, 'mdi-equal': !change }"
                :title="message"></span>
        </div>
        <div class="btn-group">
            <button type="button" class="btn dropdown-toggle" :class="'btn-' + getSelected.color"
                data-bs-toggle="dropdown" aria-expanded="false">
                {{ getSelected.value }}
            </button>
            <ul class="dropdown-menu p-0">
                <li v-for="t in data" v-bind:key="t.id" @click="setChange(t.id)">
                    <button class="dropdown-item text-uppercase" :class="'text-bg-' + t.color" type="button">
                        {{ t.value }}
                    </button>
                </li>
            </ul>
        </div>

    </div>
</template>
<script>

export default {
    props: {
        data: Object,
        id: String,
        url: String,
        actual: String,
    },
    data() {
        return {
            message: '',
            status: null,
            change: false,
            idSelected: null
        };
    }, created() {
        this.idSelected = this.actual;

    },
    computed: {
        getSelected() {
            return this.data.find(v => v.id == this.idSelected) ?? { value: 'Sin seleccionar', color: 'light' };
        }
    },
    methods: {
        setChange: function (val) {

            this.status = null;
            this.change = false;
            if (val != null && val != this.idSelected) {
                axios.post(this.url, {
                    id: this.id,
                    val: val,
                }).then(rs => {
                    if (rs.data.status) {
                        this.setStatus(rs.data.status);
                        this.idSelected = val;
                    }
                    else
                        this.message = rs.data.message;
                }).catch(e => console.log(e));
            } else this.message = "Debe seleccionar una opción valida";
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
