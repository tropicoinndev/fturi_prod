<template>
    <div class="tiempo" v-if="porcentaje < 25">

        <b>Aceptado:</b> {{ aceptacion }} · {{ transcurrido ?? 0 }}/{{ fEspera }} <span v-show="incremento">+ {{
            getFormato(incremento) }}</span>· {{ porcentaje }}% · <b>Faltante: </b>{{
                faltante }}
    </div>
    <div class="progress" role="progressbar" aria-label="Example with label" aria-valuemin="0" aria-valuemax="100" v-if="porcentaje != 0 ||
        entrega != 0 ||
        faltante != 0 ||
        transcurrido != 0 ||
        fEspera != 0">
        <div class="progress-bar overflow-visible"
            :class="{ 'bg-prioridad': prioridad && porcentaje <= 90, 'bg-tiempo': !prioridad && porcentaje <= 90, 'bg-danger': porcentaje > 90 }"
            :style="{ 'width': porcentaje + '%' }">
            <div class="texto" v-if="porcentaje > 25 && porcentaje < 100">
                {{ transcurrido ?? 0 }}/{{ fEspera }} <span v-show="incremento">+ {{ getFormato(incremento) }}</span>·
                {{ porcentaje }}% · {{ faltante }}
            </div>
            <div class="texto" v-if="porcentaje > 100">
                Tiempo completado (Hora de entrega: {{ entrega }}) Transcurrido: {{ extra }}
            </div>

        </div>
        <div class="entrega" v-if="porcentaje < 90">
            Entrega {{ entrega }}
        </div>
    </div>
</template>
<script>
import moment from 'moment';

export default {
    //cSpell:ignore aceptacion, duracion, dias
    emits: ['timer'],
    props: {
        aceptacion: String,
        espera: String,
        prioridad: Boolean,
        actualizar: Number,
        incremento: String,
        api: String,

    },
    data() {
        return {
            faltante: 0,
            entrega: 0,
            porcentaje: 0,
            transcurrido: 0,
            fEspera: '',
            extra: 0,
        };
    }, created() {
    }, methods: {
        getTime: function () {

            if (this.api)
                axios.get(this.api).then((r) => {
                    //console.log(r.data.datetime);
                    this.calculoTiempo(r.data.datetime)
                }).catch(function (error) {
                    console.log(error);
                });
            else
                this.calculoTiempo();
            setTimeout(() => {
                this.getTime();
            }, (this.actualizar ?? 30) * 1000);

        },
        calculoTiempo: function (datetime) {
            moment.locale('es');
            const [horasEspera, minutosEspera] = this.espera.split(':').map(Number);
            const horaAceptacion = moment(this.aceptacion, 'YYYY-MM-DD HH:mm:ss');
            let horaLimite = horaAceptacion.clone().add(horasEspera, 'hours').add(minutosEspera, 'minutes');

            if (this.incremento)
                horaLimite.add(this.incremento, 'minutes');
            let tiempoActual = null;
            if (datetime != null)
                tiempoActual = moment(datetime, 'YYYY-MM-DD HH:mm:ss')
            else
                tiempoActual = moment();

            const diffLimite = horaLimite.diff(tiempoActual);
            const diffTrancurrido = tiempoActual.diff(horaAceptacion);

            this.porcentaje = this.getPorcentaje(horaAceptacion, horaLimite);
            this.entrega = horaLimite.format("HH:mm");
            this.faltante = this.getFormato(diffLimite).trim();
            this.transcurrido = this.getFormato(diffTrancurrido).trim();
            this.fEspera = horasEspera > 0 ? this.espera + ' horas' : minutosEspera + ':00 minutos'
            if (this.porcentaje > 100) {
                const diffExtra = moment().diff(horaLimite);
                this.extra = this.getFormato(diffExtra).trim();
            }
        },
        //@param inicio: moment, final: moment
        getPorcentaje: function (inicio, final) {
            const actual = moment();
            const diffActual = final.diff(actual);
            const diffTotal = final.diff(inicio);
            const porcentaje = 100 - ((diffActual / diffTotal) * 100);
            return parseFloat(porcentaje.toFixed(2));
        },
        getFormato: function (diff) {
            const duracion = moment.duration(diff);
            const dias = duracion.days();
            const horas = duracion.hours();
            const minutos = duracion.minutes();
            const segundos = duracion.seconds();
            let diferencia = '';
            if (dias > 0) {
                diferencia += `${dias} días `;
            }
            if (horas > 0) {
                diferencia += `${horas} horas `;
            }
            if (minutos > 0) {
                diferencia += `${minutos} min `;
            }
            if (dias == 0 && horas == 0 && minutos == 0 && segundos > 0) {
                diferencia += `${segundos} seg `;
            }
            return diferencia;
        }
    }, mounted() {
        this.getTime();
        // console.log('Component timer')
    },
};
</script>
