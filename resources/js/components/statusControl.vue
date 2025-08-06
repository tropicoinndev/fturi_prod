<template>
    <div class="input-group">
        <div class="input-group-append mx-4">
            <span class="mdi h5"
                :class="{ 'mdi-check': status && change, 'mdi-alert-circle': !status && change, 'mdi-equal': !change }"
                :title="message"></span>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" v-model="val" :id="id" @change="setChange()">
            <label class="form-check-label" :for="id">{{ !val ? 'Activar' : 'Desactivar' }}</label>
        </div>

    </div>
</template>
<script>

export default {
    props: {
        id: String,
        url: String,
        actual: String,
    },
    data() {
        return {
            message: '',
            status: null,
            change: false,
            val: null
        };
    }, created() {
        this.val = parseInt(this.actual) == 1;
    },

    methods: {
        setChange: function () {
            this.status = null;
            this.change = false;
            axios.post(this.url, {
                id: this.id
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
