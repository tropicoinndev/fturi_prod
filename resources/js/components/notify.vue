<template>
    <div class="alertas">
        <div class="alert alert-primary alert-dismissible fade show" role="alert" v-for="(m, index) in getMessage"
            :key="index">

            <strong>{{ m.message }} </strong>
            <div class="row">
                <div class="col-12">
                    <button type="button" class="close btn btn-sm btn-light" data-dismiss="alert" aria-label="Close"
                        @click="m.seen = true">
                        Ok
                    </button>
                    <a v-if="m.src.length > 0" class="btn btn-sm btn-secondary mx-2" :href="m.src" role="button"
                        @click="m.seen = true">
                        {{ m.name ?? 'Ir' }}
                    </a>
                </div>
            </div>

        </div>
    </div>
</template>
<style>
.alertas {
    position: fixed;
    bottom: 1%;
    right: 0.5%;
    width: 260px;
    z-index: 1201;
}
</style>
<script>

export default {
    props: {
        chanel: String,
        listen: String,
    },
    data() {
        return {
            message: [],
        };
    }, computed: {
        getMessage() {
            return this.message.filter((m) => m.seen == false);
        }
    }, mounted() {
        console.log('Component chanel:' + this.chanel)
    }, created() {
        window.Echo.private(this.chanel)
            .listen(this.listen, (d) => {
                switch (d.tipo) {
                    case 1:
                        this.message.push({ message: d.message, seen: false, src: d.link });
                        break;
                    case 2:
                        this.message.push({ message: d.message, seen: false, src: d.link });
                        setTimeout(() => {
                            document.location = d.link;
                        }, 3000)
                        break;
                    default:
                        console.log('Opcion no encontrada');
                        break;
                }
            });
    }
};
</script>
