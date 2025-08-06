<template>
    <div class="input-group">
        <div class="input-group-append mx-2">
            <span class="mdi h5"
                :class="{ 'mdi-check': status && change, 'mdi-alert-circle': !status && change, 'mdi-equal': !change }"
                :title="message"></span>
        </div>
        <div class="seachControls col-10" v-if="dataSelected == null">
            <input class="form-control w-100" type="text" v-model="buscar" placeholder="Escriba aquí...">
            <ul class="list-group list-group-flush listControl shadow" v-if="buscar.length > 3 && dataSelected == null">
                <li class="list-group-item pointer" @click="setChange(d)" v-for="d in getData" v-bind:key="d.cid">
                    {{ d[name] }}
                </li>
            </ul>
        </div>
        <div class="selectedObject col-12" v-if="dataSelected != null">
            <span class="badge badge-pill text-bg-primary">
                {{ dataSelected[name] }}
                <span class="mdi mdi-close pointer ms-2" @click="dataSelected = null"></span>
            </span>
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
            dataSelected: null,
            buscar: '',
        };
    },
    computed: {
        getData: function () {
            if (this.buscar.length > 3) {
                let reg = new RegExp(this.buscar.toUpperCase());
                return this.data.filter(i => {
                    return reg.test(i[this.name].toUpperCase());
                });
            }
        }
    },
    methods: {
        setChange: function (d) {
            this.status = null;
            this.change = false;
            this.dataSelected = d;
            console.log(d);

            axios.post(this.url, {
                id: this.id,
                value: this.dataSelected.id,
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
