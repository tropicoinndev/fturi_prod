<template>
    <div class="row">
        <div class="col-12">
            Enviar mensaje
            <textarea v-model="enviar" class="form-control" placeholder="Notificacion"></textarea>
            <input type="text" class="form-control" v-model="src" placeholder="src">
            <button type="button" class="btn btn-primary" @click="setMessage()">Enviar</button>
            <button type="button" class="btn btn-primary" @click="setRedirect()">Redireccion</button>
        </div>
    </div>
</template>

<script>
import io from "socket.io-client"
const socket = io.connect("http://192.168.3.216:6001");
export default {

    data() {
        return {
            message: [],
        };
    },
    setup() {

        let enviar = '';
        let src = '';
        return {
            enviar, src
        }
    }, mounted() {
        console.log('Component mounted. Socket')
    }, created() {
        socket.on('message', (d) => {
            if (this.message == null) this.message = [];
            this.message.push(d);
            console.log(this.message)
        });
    }, methods: {
        setMessage: function () {
            console.log(this.enviar);
            socket.emit("message", {
                type: 'notify',
                message: this.enviar,
                src: this.src,
            });
        },
        setRedirect: function () {
            socket.emit("message", {
                type: 'redirect',
                src: this.enviar,
            });
        }
    }
};
</script>