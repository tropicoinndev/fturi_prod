@extends('layouts.app')
@section('style')
    <style>
        body {
            background-color: #E0F2F1;
        }

        .panel-body {
            min-height: 91vh;
            color: #FAFAFA;
            top: 0;
        }

        .btn-light {
            background: #DAE0E5;
        }

        .titular {
            background: #D9D9D9;
            color: #000;
        }

        .c1 {
            background: #B2DFDB;
            color: #37474F;
        }

        .c2 {
            background: #263238;
            color: #FAFAFA;
        }

        .c3 {
            background: #1565C0;
            color: #FAFAFA;
        }

        .cuentas {
            border: 1px solid #BBDEFB;
        }

        .hidden-checkbox {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .selected {
            background: #007bff;
            color: #fff;
        }

        .salon1 {
            /**se usara cuando este disponible para seleccionar*/
            background: #B2DFDB;
            color: #000;
        }

        .salon2 {
            /**se usapara cuando se seleccione el checboks patra mandar aguardar */
            background: #4DB6AC;
            color: #fff;
        }

        .salon3 {
            /**se usara cuando ya no se pueda seleccionar por que ya esta asociada a un evento existente  */
            background: #FFCCBC;
            color: #000;
        }

        .active {
            border: 2px solid #4DB6AC;
        }

        .ocupado-message {
            color: #000;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div id="duplicarEvento">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-12">
                    <x-message></x-message>
                    <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                        v-show="message.message && message.type">
                        <strong>@{{ message.message }}</strong>
                    </div>
                    <div class="float-end  col-12">
                            @can('eventos.detalle')
                                <a class="btn btn-light  m-1 " :href="'/eventos/detalle/' + eventoId.cid" role="button"
                                    @click.stop>
                                    <span class="mdi mdi-arrow-left"></span>
                                    Regresar al detalle
                                </a>
                            @endcan
                        </div>
                    <div class="card panel shadow p-3">

                        <div class="row p-4 ">
                            <h3>DUPLICAR EVENTO</h3>

                            <div class="row mb-3 text-uppercase">
                                <div class="col-12 mb-1 text-muted">
                                    DUPLICACION DE EVENTOS

                                </div>
                                <div class="row  text-uppercase fw-bold">
                                    @if (!$evento->clientes && $evento->titular)
                                        <h5>{{ $evento->titular }}</h5>
                                    @elseif ($evento->clientes)
                                        <h6>{{ $evento->clientes->nombre }}</h6>
                                    @else
                                        No se ha asignado cliente aún
                                    @endif

                                </div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2 fw-bold">
                                    Fecha de inicio y de finalizacion del evento:
                                </div>
                                <div class="col-md-10">
                                    @if ($evento->fecha)
                                        {{ \Carbon\Carbon::parse($evento->fecha)->isoFormat('dddd, D [de] MMMM [de] YYYY') }},
                                        hora en que iniciara el evento
                                        {{ \Carbon\Carbon::parse($evento->inicio)->format('h:i A') }} y {{ \Carbon\Carbon::parse($evento->fecha_fin)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}, hora en la que
                                        finalizara {{ \Carbon\Carbon::parse($evento->finalizacion)->format('h:i A') }}.
                                    @else
                                        Aun no se hazy fecha y hora de inicio y finalizacion de evento.
                                    @endif
                                </div>

                            </div>
                            <!-- section configuraciones de evento -->
                            <div class="container-fluid  mb-4 h-100 w-100">

                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="fw-bold">Generalidades</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2"> Tipo de evento:
                                    </div>
                                    <div class="col-md-10">{{ $evento->tipo_eventos->evento }},
                                        {{ $evento->tipo_eventos->descripcion }}</div>

                                </div>
                                <div class="row">
                                    <div class="col-md-2"> Salon(es):
                                    </div>
                                    <div class="col-md-10">
                                        {{ implode(', ', $evento->salones->pluck('salones.salon')->toArray()) }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 "> Sonido:
                                    </div>
                                    <div class="col-md-10">
                                        @if ($evento->sonidos)
                                            {{ $evento->sonidos->sonido }}, {{ $evento->sonidos->descripcion }}
                                        @else
                                            Aun no se ha agregado el tipo de sonido, seleccione en el boton agregar.
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2"> Observaciones de sonido:
                                    </div>
                                    <div class="col-md-10">
                                        @if ($evento->observaciones_sonidos)
                                            {{ $evento->observaciones_sonidos }}
                                        @else
                                            Aun no se han agregado observaciones del sonido, para agregar las observaciones
                                            del sonido presione en el boton agregar, antes debe agregar el tipo de sonido
                                            para poder agregar observaciones.
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        Tipo de montaje:
                                    </div>
                                    <div class="col-md-10">
                                        @if ($evento->montajes)
                                            {{ $evento->montajes->montaje }}, {{ $evento->montajes->descripcion }}
                                        @else
                                            Aun no se agregado un montaje a este evento
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2"> Observaciones del montaje: </div>
                                    <div class="col-md-10">
                                        @if ($evento->montaje)
                                            {{ $evento->montaje }}
                                        @else
                                            Aun no se han agregado observaciones del montaje, para agregar las observaciones
                                            presione en el boton agregar.
                                        @endif
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-2">
                                        @if ($evento->observaciones)
                                            Observaciones generales:
                                        @else
                                            Observaciones generales:
                                        @endif
                                    </div>

                                    <div class="col-md-10">
                                        @if ($evento->observaciones)
                                            {{ $evento->observaciones }}
                                        @else
                                            Aun no se han agregado observaciones, para agregar las observaciones presione en
                                            el boton agregar.
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-md-2"> Encargado del evento:
                                    </div>
                                    <div class="col-md-10">
                                        @if ($evento->encargado)
                                            {{ $evento->encargado }}
                                        @else
                                            Nombre del enecargado del evento / +503 7777 2222 / persona@correo.com
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2"> Imagenes de referencia:
                                    </div>
                                    <div class="col-md-10">
                                        @if ($evento->galerias_evento)
                                            Se seleccionaron {{ count($evento->galerias_evento) }} imagenes de referencia
                                            para el
                                            evento.
                                        @else
                                            Aun no se han seleccionado imagenes de referencia para el evento
                                        @endif
                                    </div>

                                </div>

                            </div>
                            <form action="{{ route('eventos.duplicar') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                    <label for="fecha" class="form-label ">Seleccione la fecha de el evento:</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" v-model="fecha" value="{{ old('fecha') }}"
                                        required min="{{ now()->toDateString() }}" @input="checkUpdate">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                    <label for="fecha_fin" class="form-label ">Seleccione la fecha de el evento:</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" v-model="fecha_fin" value="{{ old('fecha_fin') }}"
                                        required min="{{ now()->toDateString() }}" @input="checkUpdate">
                                    </div>
                                </div>

                                <div class=" col-12 mb-2">

                                    <label for="inicio" class="form-label">Hora de inicio:</label>
                                    <input type="time" class="form-control" id="inicio" name="inicio"
                                         v-model="inicio" @input="checkUpdate" value="{{ old('inicio') }}">

                                </div>
                                <div class=" col-12 mb-4">
                                    <label for="finalizacion" class="form-label">Hora de finalización:</label>
                                    <input type="time" class="form-control" id="finalizacion" name="finalizacion"
                                         v-model="finalizacion" @input="checkUpdate" value="{{ old('finalizacion') }}">

                                </div>
                                <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">

                                <!-- section para las cuentas para copiar su detalle y duplicar -->
                                <div class=" mb-4">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5>Seleccione las comandas si desea duplicar </h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <template v-if="comandas.length > 0">
                                                <div class="col-4" v-for="(comanda, index) in comandas"
                                                    :key="index">
                                                    <div class="card c1 mb-3"
                                                        :class="{
                                                            'c1': !comanda.comprobante,
                                                            'c2': comanda.comprobante && !comanda.facturada,
                                                            'c3': comanda.facturada && !comanda.estado && comanda
                                                                .comprobante,
                                                            'selected': selectedComanda.includes(comanda.cid)
                                                        }">
                                                        <input class="btn-check" type="checkbox" :name="'comandas[]'"
                                                            :id="'comanda_' + comanda.cid" v-model="selectedComanda"
                                                            :value="comanda.cid">
                                                        <label :for="'comanda_' + comanda.cid">
                                                            <div class="card-body d-flex flex-column">
                                                                <div class="d-flex justify-content-between">
                                                                    <h5 class="card-title text-center">Comanda</h5>
                                                                    <p class="card-subtitle text-end">Nº
                                                                        @{{ comanda.id }}</p>
                                                                </div>
                                                                <p class="card-subtitle mb-0">$ @{{ comanda.sum_comanda.toFixed(2) }}</p>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <div class="col-12">
                                                    <p>No se han agregado comandas a este evento.</p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <!-- section de ordenes de servicio de evento -->
                                <div class="  mb-3">
                                    <div class="row">
                                        <div class="row ">
                                            <div class="col-8">
                                                <h5>Seleccione las ordenes si desea duplicar </h5>
                                            </div>

                                        </div>

                                        <div class="row " v-if="ordenes.length > 0">
                                            <div class="col-4" v-for="(orden, index) in ordenes" :key="index">
                                                <div class="card mb-3"
                                                    :class="{
                                                        'c1': !orden.comprobante,
                                                        'c2': orden.comprobante && !orden.facturada,
                                                        'c3': !orden.estado && orden.comprobante,
                                                        'selected': selectedOrden.includes(orden.id)
                                                    }">
                                                    <input class="btn-check" type="checkbox" :name="'ordenes[]'"
                                                        :value="orden.id" :id="'ordenes_' + orden.id"
                                                        v-model="selectedOrden" multiple>
                                                    <label :for="'ordenes_' + orden.id"
                                                        class="card-body d-flex flex-column">
                                                        <div class="d-flex justify-content-between">
                                                            <h5 class="card-title text-start">Ordenes</h5>
                                                            <p class="card-subtitle text-end">Nº @{{ orden.orden }}</p>
                                                        </div>
                                                        <p class="card-subtitle mb-0">$ @{{ orden.sum_orden.toFixed(2) }}</p>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>


                                        <div v-else class="row">
                                            <div class="col-12">
                                                <p>No hay órdenes en este evento.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="  row">
                                    <div class="col-4">
                                        <h5>Seleccione los salones a duplicar</h5>
                                        <div class="btn-group mb-2" role="group"
                                            aria-label="Basic radio toggle button group">
                                            <input type="radio" class="btn-check" name="mismos" id="mismos"
                                                v-model="opcionSalon" autocomplete="off":value="1"
                                                @change="mismosSalones">
                                            <label class="btn btn-outline-primary" for="mismos">Los mismos
                                                salones</label>

                                            <input type="radio" class="btn-check" id="editar" autocomplete="off"
                                                v-model="opcionSalon" :value="0" @change="getDisponibilidad">
                                            <label class="btn btn-outline-secondary" for="editar">Editar salones</label>

                                        </div>
                                    </div>
                                    <div class="contenedor-salones">
                                        <div class="row" v-if="opcionSalon == 1">

                                            <div class="col-3 mb-1 p-2" v-for="(s, index) in salonesEventoAnterior"
                                                :key="index">
                                                <div
                                                    :class="{
                                                        'salon1': true,
                                                        'card': true,
                                                        'salon2': selectedSalon.includes(s
                                                            .id),
                                                        'active': selectedSalon.includes(s
                                                            .id),
                                                        'salon3': salonesOcupadosEventoAnteriorSet.has(s.id)
                                                    }">
                                                    <label :for="'checkbox_' + s.id">
                                                        <div class="card-body text-center">
                                                            <input type="checkbox" class="hidden-checkbox"
                                                                :name="'salones[]'" :id="'checkbox_' + s.id"
                                                                v-model="selectedSalon" :value="s.id"
                                                                :disabled="salonesOcupadosEventoAnteriorSet.has(s.id)"@change="getDisponibilidad">

                                                            <p>@{{ s.salon }}
                                                            <div v-if="salonesOcupadosEventoAnteriorSet.has(s.id)">
                                                                <small class="ocupado-message">Ocupado</small>
                                                            </div>
                                                            <div v-else>
                                                                <small>Disponible</small>
                                                            </div>

                                                            </p>

                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row" v-else-if="opcionSalon == 0">

                                            <div class="col-3 mb-1 p-2" v-for="(s, index) in salones"
                                                :key="index">
                                                <div
                                                    :class="{
                                                        'salon1': true,
                                                        'card': true,
                                                        'salon2': selectedSalon.includes(s
                                                            .id),
                                                        'active': selectedSalon.includes(s
                                                            .id),
                                                        'salon3': salonesOcupadosSet.has(s.id)
                                                    }">
                                                    <label :for="'checkbox_' + s.id">
                                                        <div class="card-body text-center">
                                                            <input type="checkbox" class="hidden-checkbox"
                                                                :name="'salones[]'" :id="'checkbox_' + s.id"
                                                                v-model="selectedSalon" :value="s.id"
                                                                :disabled="salonesOcupadosSet.has(s.id)"@change="getDisponibilidad">

                                                            <p>@{{ s.salon }}
                                                            <div v-if="salonesOcupadosSet.has(s.id)">
                                                                <small class="ocupado-message">Ocupado</small>
                                                            </div>
                                                            <div v-else>
                                                                <small>Disponible</small>
                                                            </div>

                                                            </p>

                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div>
                                        <button class="btn btn-primary btn-sm" type="submit"><span
                                                class="mdi mdi-check"></span>
                                            DUPLICAR EVENTO</button>
                                        <button class="btn btn-second btn-sm" role="button"
                                            onclick="window.location='{{ route('eventos.eventos') }}'"
                                            style="background:#D9D9D9;">
                                            <span class="mdi mdi-arrow-left-thick"></span> VOLVER</button>

                                    </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('script')
    <script>
        new Vue({
            el: '#duplicarEvento',
            data: {
                eventoId: @json($evento),
                ordenes: @json($ordenes),
                comandas: @json($comandas),
                salones: @json($salones),
                salonesEventoAnterior: @json($salonesEventoAnterior),
                txtComanda: '',
                txtOrden: '',
                selectedSalon: [],
                selectedComanda: [],
                selectedOrden: [],
                opcionSalon: 1,
                titular: '',
                salonesOcupadosSet: new Set(),
                salonesOcupadosEventoAnteriorSet: new Set(),
                fecha: '{{  old('fecha', (new \DateTime())->format('Y-m-d'))}}',
                fecha_fin: '{{  old('fecha_fin', (new \DateTime())->format('Y-m-d'))}}',
                inicio: '{{old('inicio')}}',
                finalizacion: '{{old('finalizacion')}}',
                message: {},
            },
            methods: {
                checkUpdate() {
                    if (this.fecha && this.inicio && this.finalizacion) {
                        this.getDisponibilidad();
                        this.mismosSalones();
                    }
                },
                async getDisponibilidad() {
                    try {
                        const response = await axios.post(
                            "{{ route('eventos_salones.checkDisponibilidad') }}", { //pendiente solucionar
                                fecha: this.fecha,
                                inicio: this.inicio,
                                finalizacion: this.finalizacion,
                            });
                        this.salonesOcupadosSet = new Set(response.data.salones.map(salon => salon.id));

                    } catch (error) {
                        console.error(error);
                    }
                },
                async mismosSalones() {
                    try {
                        const response = await axios.post(
                            "{{ route('eventos_salones.checkDisponibilidad') }}", {

                                fecha: this.fecha,
                                inicio: this.inicio,
                                finalizacion: this.finalizacion,
                            });
                        this.selectedSalon = [];
                        if (response.data.salones.length > 0) {
                            const mensajeAlerta =
                                `Los mismos salones no están disponibles  del evento #: ${this.eventoId.id} que comienza el ${this.fecha} a las ${this.inicio} y finaliza a las ${this.finalizacion}. Por favor, seleccione otros salones.`;
                            alert(mensajeAlerta);
                        }
                        this.salonesOcupadosEventoAnteriorSet = new Set(response.data.salones.map(salon => salon
                            .id));

                    } catch (error) {
                        console.error(error);
                    }
                },
                selectSalon(salon) {
                    if (this.opcionSalon === 1) {
                        this.selectedSalon = salon;
                        this.selectedSalones.push(salon);
                    }
                },
                selectComanda(comanda) {
                    const index = this.selectedComanda.indexOf(comanda.id);
                    if (index === -1) {
                        this.selectedComanda.push(comanda.id);
                    } else {
                        this.selectedComanda.splice(index, 1);
                    }
                },
                selectOrden(orden) {
                    const index = this.selectedOrden.indexOf(orden.id);
                    if (index === -1) {
                        this.selectedOrden.push(orden.id);
                    } else {
                        this.selectedOrden.splice(index, 1);
                    }
                },

            },
            computed: {


            },
                watch: {


                inicio: function (newValue, oldValue) {
                    const currentDate = new Date();
                        currentDate.setUTCHours(currentDate.getUTCHours() - 6);
                        currentDate.setHours(currentDate.getHours() + 3);
                        currentDate.setMinutes(currentDate.getMinutes() + 5);
                        const fechaActual = currentDate.toISOString().split('T')[0];
                        let horaActual = currentDate.toISOString().split('T')[1].substring(0, 5);
                        console.log('Hora actual:', horaActual);

                        if (this.fecha === fechaActual || !this.fecha) {
                            if (newValue < horaActual) {
                                this.inicio = horaActual;
                            }
                        } else if (this.fecha > fechaActual) {

                            this.inicio;
                        }
                }
            },

        });
    </script>
@endsection
