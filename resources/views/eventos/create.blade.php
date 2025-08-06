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

        .hidden-checkbox {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .titular {
            background: #B2DFDB;
            color: #000;
        }

        .c1 {
            /**se usara cuando este disponible para seleccionar*/
            background: #B2DFDB;
            color: #000;
        }

        .c2 {
            /**se usapara cuando se seleccione el checboks patra mandar aguardar */
            background: #4DB6AC;
            color: #fff;
        }

        .c3 {
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

        [v-cloak] {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div id="creacionEvento" v-cloak>
        <div class="container">
            <div class="row justify-content-center">
                <x-message></x-message>
                <div class="col-md-12">
                    <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                        v-show="message.message && message.type">
                        <strong>@{{ message.message }}</strong>
                    </div>
                    <div class="card panel shadow p-3">

                        <div class="row p-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h3>NUEVO EVENTO</h3>
                                </div>
                                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                    <a href="{{ route('eventos.eventos') }}" class="btn btn-light">
                                        <span class="mdi mdi-arrow-left fs-5 me-2"></span>
                                        Volver
                                    </a>
                                </div>
                            </div>


                            <div class="row mb-3 text-uppercase">
                                <div class="col-12 h7 text-muted">
                                    CREACION DE EVENTOS

                                </div>
                            </div>


                            <form action="{{ route('eventos.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="clientes_id" v-model="selectedCliente"
                                    value="{{ old('clientes_id') }}">
                                <div class="btn-group mb-2" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="option" id="cliente" autocomplete="off"
                                        v-model="selectedOption" value="cliente">
                                    <label class="btn btn-outline-primary" for="cliente"
                                        :class="{ 'active': selectedOption === 'cliente' }">
                                        Cliente
                                    </label>

                                    <input type="radio" class="btn-check" name="option" id="titular" autocomplete="off"
                                        v-model="selectedOption" value="titular">
                                    <label class="btn btn-outline-secondary" for="titular"
                                        :class="{ 'active': selectedOption === 'titular' }">
                                        Titular
                                    </label>
                                </div>
                                <div class="col-12">
                                    <label for="" class="form-label">Buscar cliente</label>
                                    <input type="text" name="cliente" class="form-control" v-model="txtBuscarApi"
                                        placeholder="Buscar el cliente a quien se le creará el evento" @input="getClientes"
                                        autocomplete="off" @keydown.up.prevent="selectItem(-1)" @keyup="getClientes"
                                        @keydown.down.prevent="selectItem(1)" @keydown.enter="setCliente()"
                                        v-show="selectedOption === 'cliente'" value="{{ old('cliente') }}" />

                                    <input type="text" name="titular" class="form-control" v-model="titular"
                                        placeholder="Nombre del titular" v-show="selectedOption === 'titular'" />

                                    <div class="d-fixed shadow " v-show="showClientList && selectedOption === 'cliente'">
                                        <div class="list-group list-group-flush">
                                            <button type="button" class="list-group-item list-group-item-action"
                                                v-for="(cl, index) in clientesList" @click="setCliente(index)"
                                                :key="index" @keydown.enter="setCliente(index)"
                                                :key="index" :class="{ 'active': index === selectedIndex }"
                                                tabindex="0">
                                                @{{ cl.cliente }}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-12" v-if="noResults && selectedOption === 'cliente'">
                                        <div class="list-group list-group-flush mt-2">
                                            No se encontró ningún registro con los parámetros de búsqueda.
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <label for="fecha" class="form-label ">Seleccione la fecha de inicio del
                                            evento:</label>
                                        <input type="date" class="form-control" id="fecha" name="fecha"
                                            v-model="fecha" required min="{{ now()->toDateString() }}"
                                            @change="getDisponibilidad">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label for="fecha_fin" class="form-label ">Seleccione la fecha de finalizacion del
                                            evento:</label>
                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                                            v-model="fecha_fin" required min="fecha" @change="getDisponibilidad">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class=" col-6 mb-2">

                                        <label for="inicio" class="form-label">Hora de inicio:</label>
                                        <input type="time" class="form-control" id="inicio" name="inicio"
                                            v-model="inicio" @change="getDisponibilidad">

                                    </div>
                                    <div class=" col-6 mb-2">
                                        <label for="finalizacion" class="form-label">Hora de finalización:</label>
                                        <input type="time" class="form-control" id="finalizacion" name="finalizacion"
                                            v-model="finalizacion" @change="getDisponibilidad">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class=" col-6 mb-2">
                                        <label for="minimo_personas" class="form-label ">Minimo de personas:</label>

                                        <input type="number" class="form-control" id="minimo_personas"
                                            :max="maximo_personas" name="minimo_personas" required
                                            value="{{ old('minimo_personas', $val ?? '') }}">


                                    </div>
                                    <div class=" col-6 mb-2">
                                        <label for="maximo_personas" class="form-label ">Maximo de personas:</label>

                                        <input type="number" class="form-control" id="maximo_personas"
                                            :min="minimo_personas" name="maximo_personas" required
                                            value="{{ old('maximo_personas', $val ?? '') }}">


                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6 mb-2">
                                        <label for="fpago" class="form-label">Seleccione la forma de pago:</label>
                                        <select class="form-select" id="fpago" name="fpago" required>
                                            <option value="" disabled selected>Seleccione una forma de pago</option>
                                            @foreach ($Fpagos as $fpago)
                                                <option value="{{ $fpago->id }}"
                                                    {{ old('fpago', $val['fpago'] ?? '') == $fpago->id ? 'selected' : '' }}>
                                                    {{ $fpago->forma }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label for="Tipoevento" class="form-label">Seleccione el tipo de evento:</label>
                                        <select class="form-select" id="Tipoevento" name="Tipoevento"
                                            v-model="selectedTipoEvento" @change="checkSalonTipoEvento" required>
                                            <option value="" disabled selected>Seleccione un tipo de evento</option>
                                            @foreach ($tipoEventos as $e)
                                                <option value="{{ $e->id }}"
                                                    {{ old('Tipoevento', $val['Tipoevento'] ?? '') == $e->id ? 'selected' : '' }}>
                                                    {{ $e->evento }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label for="encargado" class="form-label ">Encargado:</label>
                                        <input type="text" class="form-control" id="encargado" name="encargado"
                                            placeholder="Escriba el nombre del encargado del evento y numero de telefono si es necesario"
                                            value="{{ old('encargado', $val ?? '') }}">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <h5>Seleccione los salones</h5>
                                        <div class="btn-group mb-2" role="group"
                                            aria-label="Basic radio toggle button group">
                                            <input type="radio" class="btn-check" name="opcionSalon" id="unidos"
                                                autocomplete="off" v-model="opcionSalon" :value="0"
                                                {{ old('opcionSalon', $val['opcionSalon'] ?? '') == 0 ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="unidos">Unidos</label>

                                            <input type="radio" class="btn-check" name="opcionSalon" id="separados"
                                                autocomplete="off" v-model="opcionSalon" :value="1"
                                                {{ old('opcionSalon', $val['opcionSalon'] ?? '') == 1 ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="separados">Separados</label>
                                            <input type="hidden" name="opcion_salon" v-model="opcionSalon" />
                                        </div>

                                    </div>
                                    <input type="hidden" name="salones_seleccionados" v-model="selectedSalon" />
                                    <div class="row">
                                        <div class="col-3 mb-1 p-2" v-for="(s, index) in salones" :key="index">
                                            <div
                                                :class="{
                                                    'c1': true,
                                                    'card': true,
                                                    'c2': selectedSalon.includes(s
                                                        .id),
                                                    'active': selectedSalon.includes(s
                                                        .id),
                                                    'c3': salonesOcupadosSet.has(s.id)
                                                }">
                                                <label :for="'checkbox_' + s.id"
                                                    :class="{ 'disabled': !salonesHabilitados }">

                                                    <div class="card-body text-center">
                                                        <input type="checkbox" class="hidden-checkbox"
                                                            :id="'checkbox_' + s.id" v-model="selectedSalon"
                                                            :value="s.id">
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

                                    <div>
                                        <button class="btn btn-primary btn-sm" type="submit"><span
                                                class="mdi mdi-check"></span> AGREGAR EVENTO</button>
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
    <script>
        var app = new Vue({
            el: '#creacionEvento',
            data: {
                Tipoevento: @json($tipoEventos),
                Fpagos: @json($Fpagos),
                salones: @json($salones),
                retraso: @json($retraso),
                clientesList: [],
                selectedIndex: 0,
                txtBuscarApi: '',
                titular: '{{ old('titular') }}',
                cliente: '',
                selectedCliente: '',
                selectedClientId: {},
                showClientList: false,
                noResults: false,
                selectedSalon: [],
                salonesOcupados: [],
                selectedOption: 'cliente',
                opcionSalon: 1,
                fecha: '{{ old('fecha', (new \DateTime())->format('Y-m-d')) }}',
                fecha_fin: '{{ old('fecha_fin', (new \DateTime())->format('Y-m-d')) }}',
                inicio: '{{ old('inicio') }}',
                minimo_personas: '',
                maximo_personas: '',
                salonesOcupadosSet: new Set(),
                finalizacion: '{{ old('finalizacion') }}',
                selectedTipoEvento: '',
                salonesHabilitados: false,
                message: {},

            },
            methods: {
                async checkSalonTipoEvento() {
                    try {
                        const r = await axios.post("{{ route('tipo_eventos.check_salon') }}", {
                            tipo_eventos_id: this.selectedTipoEvento,
                        });
                        this.salonesHabilitados = r.data.habilitar_salones;
                        this.selectedSalon = [];
                    } catch (error) {
                        console.error(error);
                    }
                },
                async getDisponibilidad() {
                    try {
                        if (this.disponibilidad) {
                            const response = await axios.post(
                                "{{ route('eventos_salones.checkDisponibilidad') }}", {
                                    fecha: this.fecha,
                                    fecha_fin: this.fecha_fin,
                                    inicio: this.inicio,
                                    finalizacion: this.finalizacion,
                                });
                            this.salonesOcupadosSet = new Set(response.data.salones.map(salon => salon.id));
                            this.selectedSalon = [];
                        }

                    } catch (error) {
                        console.error(error);
                    }
                },
                getClientes: function(e) {
                    const codTecla = e.keyCode || e.which;
                    const tecla = e.key;
                    if (this.txtBuscarApi.length > 4 && tecla !==
                        'ArrowUp' && tecla !== 'ArrowDown' && tecla !== 'Enter') {
                        axios.post("{{ route('clientes.api_search_list') }}", {
                                busqueda: this.txtBuscarApi,
                            })
                            .then((response) => {
                                this.clientesList = response.data.list || [];
                                this.showClientList = this.clientesList.length > 0;
                                this.noResults = this.clientesList.length === 0;
                            })
                            .catch((error) => {
                                console.error(error);
                                this.clientesList = [];
                                this.showClientList = false;
                                this.noResults = false;
                            });
                    } else {
                        this.clientesList = [];
                        this.showClientList = false;
                        this.noResults = false;
                    }

                },
                selectItem(index) {
                    let v = this.selectedIndex + (index);
                    if (v >= this.clientesList.length)
                        this.selectedIndex = 0;
                    else if (v < 0)
                        this.selectedIndex = this.clientesList.length;
                    else
                        this.selectedIndex = v;
                },
                setCliente(index = null) {
                    if (index !== null) {
                        this.selectedIndex = index;
                        this.selectedClientId = this.clientesList[index];
                        this.clientesList = [];
                        this.txtBuscarApi = this.selectedClientId.cliente;
                        this.selectedCliente = this.selectedClientId.id;
                        this.showClientList = false;
                    } else if (this.selectedIndex !== null) {
                        this.selectedClientId = this.clientesList[this.selectedIndex];
                        this.txtBuscarApi = this.selectedClientId.cliente;
                        this.selectedCliente = this.selectedClientId.id;
                        this.showClientList = false;
                    }
                },
                selectSalon(salon) {
                    if (this.opcionSalon === 1) {
                        this.selectedSalon = salon;
                        this.selectedSalones.push(salon);
                    }
                },



            },
            mounted() {
                //validacion maximos y minimos de personas
                document.addEventListener("DOMContentLoaded", function() {
                    const minimoPersonasInput = document.getElementById("minimo_personas");
                    const maximoPersonasInput = document.getElementById("maximo_personas");

                    minimoPersonasInput.addEventListener("change", function() {
                        if (parseInt(minimoPersonasInput.value) > parseInt(maximoPersonasInput
                                .value)) {
                            alert(
                                "El número mínimo de personas no puede ser mayor que el número máximo de personas."
                            );
                            minimoPersonasInput.value = maximoPersonasInput.value;
                        }
                    });

                    maximoPersonasInput.addEventListener("change", function() {
                        if (parseInt(maximoPersonasInput.value) < parseInt(minimoPersonasInput
                                .value)) {
                            alert(
                                "El número máximo de personas no puede ser menor que el número mínimo de personas."
                            );
                            maximoPersonasInput.value = minimoPersonasInput.value;
                        }
                    });
                    // Validación de horas
                    const inputInicio = document.getElementById('inicio');
                    const inputFinalizacion = document.getElementById('finalizacion');

                    // Solo aplicar la validación si la fecha de inicio es igual a la fecha de fin
                    if (this.fecha === this.fecha_fin) {
                        inputFinalizacion.addEventListener('change', () => {
                            const horaInicio = inputInicio.value;
                            const horaFin = inputFinalizacion.value;

                            if (horaInicio && horaFin && this.fecha === this.fecha_fin) {
                                // Verificar si la hora de inicio es mayor que la hora de finalización
                                if (horaInicio > horaFin) {
                                    alert(
                                        'La hora de finalización debe ser mayor a la hora de inicio. Por favor, seleccione una hora mayor para la finalización.'
                                    );

                                    const ahora = new Date();
                                    let nuevaHora = ahora.getHours();
                                    let nuevoMinuto = ahora.getMinutes() + 60;

                                    // Ajustar minutos y horas si se pasa de 60 minutos
                                    if (nuevoMinuto >= 60) {
                                        nuevaHora += Math.floor(nuevoMinuto / 60);
                                        nuevoMinuto = nuevoMinuto % 60;
                                    }

                                    // Formatear las horas y minutos para asegurarse de que tengan dos dígitos
                                    const horaFormateada = nuevaHora.toString().padStart(2, '0');
                                    const minutoFormateado = nuevoMinuto.toString().padStart(2,
                                        '0');

                                    // Asignar la nueva hora al campo de finalización
                                    inputFinalizacion.value =
                                        `${horaFormateada}:${minutoFormateado}`;
                                }
                            }
                        });
                    }

                    //validacion fechas
                    const fecha = document.getElementById("fecha");
                    const final = document.getElementById("fecha_fin");

                    fecha.addEventListener("change", function() {
                        const fechaInicio = new Date(fecha.value);
                        const fechaFin = new Date(final.value);

                        if (fechaInicio > fechaFin) {
                            alert(
                                "La fecha de inicio no puede ser mayor a la fecha de finalización."
                            );
                            fecha.value = final
                                .value; // Resetea la fecha de inicio al valor de fecha_fin
                        }
                    });

                    final.addEventListener("change", function() {
                        const fechaInicio = new Date(fecha.value);
                        const fechaFin = new Date(final.value);

                        if (fechaFin < fechaInicio) {
                            alert(
                                "La fecha de finalización no puede ser menor que la fecha de inicio del evento."
                            );
                            final.value = fecha
                                .value; // Resetea la fecha de fin al valor de fecha de inicio
                        }
                    });

                });

            },
            computed: {
                disponibilidad: function() {
                    return this.fecha && this.inicio && this.finalizacion;
                }
            },
            watch: {
                disponibilidad: function(newDisponibilidad, oldDisponibilidad) {
                    if (newDisponibilidad) {
                        this.getDisponibilidad();
                    }
                },

                inicio: function(newValue, oldValue) {
                    if (this.fecha === this.fecha_fin) {
                        const currentDate = new Date();
                        currentDate.setUTCHours(currentDate.getUTCHours() - 6);
                        currentDate.setHours(currentDate.getHours() + this.retraso);
                        currentDate.setMinutes(currentDate.getMinutes() + 5);
                        const fechaActual = currentDate.toISOString().split('T')[0];
                        let horaActual = currentDate.toISOString().split('T')[1].substring(0, 5);
                        if (this.fecha === fechaActual || !this.fecha) {
                            if (newValue < horaActual) {
                                this.inicio = horaActual;
                            }
                        } else if (this.fecha > fechaActual) {

                            this.inicio = newValue;
                        }
                    }
                }
            },
        });
    </script>
@endsection
