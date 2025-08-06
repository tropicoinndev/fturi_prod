@extends('layouts.hab')

@section('content-hab')
    <style>
        .column,
        .dia {
            position: relative;
            width: 14.28%;
        }

        .dia {

            max-height: 100px;
            height: 100px;
            cursor: pointer;
        }

        .dia-content {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .ocupado {
            background: #455A64;
            color: #fafafa;
        }

        .dia .tooltip {
            visibility: hidden;
            width: 180px;
            background-color: #2979FF;
            color: #fff;
            text-align: center;
            padding: 8px;
            border-radius: 5px;
            position: absolute;
            z-index: 1;
            bottom: 110%;
            left: 50%;
            margin-left: -75px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .ocupado:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }

        .ocupado:hover {
            background: #009688;
            color: #fafafa;
        }

        .flecha {
            position: absolute;
            width: 0px;
            height: 0px;
            margin-left: 20px;
            border-top: 15px solid #2979FF;
            border-right: 15px solid transparent;
            border-bottom: 15px solid transparent;
            border-left: 15px solid transparent;
        }

        .head {
            background: #CFD8DC;
            padding: 10px;
        }

        .panelCalendar {
            min-height: 85vh;
        }

        body {
            background: #E1F5FE;
        }
    </style>
    <div id="appReporteHab" class="container">
        <div class="card panelCalendar">
            <div class="card-body p-5">
                <div class="row">
                    <div class="card-title col-12 h5 text-uppercase">Calendario de reservaciones por habitación</div>

                    <div class="col-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Habitación</label>
                            <select class="form-select" aria-label="Default select example" v-model='habitacion'
                                @change="getReservas()">
                                <option selected value="">Seleccione una habitación</option>
                                @foreach ($hab as $h)
                                    <option value="{{ $h->id }}">Habitación {{ $h->numero_habitacion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Mes</label>
                            <input type="month" name="" id="" class="form-control" placeholder=""
                                aria-describedby="helpId" v-model='mes' @change="getReservas()" />
                        </div>
                    </div>

                </div>
                <div class="row text-center" v-if="calendar.length > 0">
                    <div class="column head">
                        <div class="dia-content">Domingo</div>
                    </div>
                    <div class="column head">
                        <div class="dia-content">Lunes</div>
                    </div>
                    <div class="column head">
                        <div class="dia-content">Martes</div>
                    </div>
                    <div class="column head">
                        <div class="dia-content">Miércoles</div>
                    </div>
                    <div class="column head">
                        <div class="dia-content">Jueves</div>
                    </div>
                    <div class="column head">
                        <div class="dia-content">Viernes</div>
                    </div>
                    <div class="column head">
                        <div class="dia-content">Sábado</div>
                    </div>
                </div>
                <div class="row text-center placeholder-glow" v-for='s in  calendar' v-if="calendar.length > 0">
                    <div class="dia" :class="{ 'placeholder': load, 'ocupado': d.reserva != null }"
                        v-for='d in s.semana'>

                        <div class="tooltip" v-if="d.reserva != null">
                            <b>
                                @{{ d.reserva.relacion_reservaciones.clientes_id > 0 ? d.reserva.relacion_reservaciones.relacion_clientes.nombre : d.reserva.relacion_reservaciones.titular }}
                            </b>
                            <br>
                            @{{ d.reserva.fecha_ingreso }} al @{{ d.reserva.fecha_salida }} <br>
                            @{{ d.reserva.relacion_tarifas.tarifa }} $@{{ parseFloat(d.reserva.relacion_tarifas.precio).toFixed(2) }}

                            <div class="flecha"></div>
                        </div>
                        <div class="dia-content">
                            @{{ d.dia }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        var app = new Vue({
            el: '#appReporteHab',
            data: {
                habitacion: '',
                mes: '',
                calendar: [],
                load: true,
                list: []
            },
            methods: {
                getReservas: function() {
                    this.load = true;
                    const [year, month] = this.mes.split('-').map(Number);
                    if (this.mes.length > 5 && this.habitacion > 0) {
                        axios.post("{{ route('reservaciones.api_habitaciones') }}", {
                            month: month,
                            year: year,
                            habitacion: this.habitacion
                        }).then((r) => {
                            if (r.data) {
                                this.list = r.data.list;
                                this.getDays()
                            } else
                                this.list = [];

                        }).catch(function(error) {
                            console.log(error);

                        });
                    }

                },
                getDays: function() {
                    const [year, month] = this.mes.split('-').map(Number);
                    const primerDia = new Date(year, month - 1, 1);
                    const ultimoDia = new Date(year, month, 0);
                    const inicio = primerDia.getDate();
                    const fin = ultimoDia.getDate();
                    const dia = primerDia.getDay();
                    let calendar = [];


                    for (var h = 0; h < dia; h++) {
                        var d = {
                            dia: '',
                            cliente: '',
                        };
                        calendar.push(d);
                    }

                    for (var i = 1; i <= fin; i++) {
                        let dia = new Date(year + "-" + month + "-" + i + " 00:00:00").getTime();
                        let l = this.list.filter(l => {
                            let fecha_ingreso = new Date(l.fecha_ingreso + " 00:00:00").getTime();
                            let fecha_salida = new Date(l.fecha_salida + " 00:00:00").getTime();
                            return dia >= fecha_ingreso && dia <= fecha_salida;
                        });
                        var reserva = l.length > 0 ? l[0] : null;
                        var d = {
                            dia: i,
                            reserva: reserva

                        };
                        calendar.push(d);

                    }

                    this.calendar = this.semanas(calendar);
                    this.load = false;
                },
                semanas: function(objeto) {
                    const grupos = [];
                    for (let i = 0; i < objeto.length; i += 7) {
                        const grupo = objeto.slice(i, i + 7);
                        grupos.push({
                            'semana': grupo
                        });
                    }
                    return grupos;
                }
            },
            mounted() {
                /*this.mes = '2024-01';
                this.getDays();
                this.load = false;*/
            },

        });
    </script>
@endsection
