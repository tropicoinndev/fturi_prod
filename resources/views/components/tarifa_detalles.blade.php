@extends('layouts.app')

@section('style')
    <style>
        .sidebarInformacion {
            position: fixed;
            width: 21%;
            min-height: 97vh;
            right: 0;

        }

        .sidebarInformacionR {
            position: fixed;
            width: 275px;
            left: 0;
            bottom: 1%;
            top: 15%;
            overflow-y: auto;

        }

        .panel-body {
            min-height: 550px;
        }

        .regresar {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 10px 10px 10px 10px;
            background-color: light;
            color: #007bff;
            text-decoration: none;
            border: none;
            margin-left: 13px;
            font-size: 16px;
        }

        .regresar .icono {
            margin-right: 0;
            font-size: 30px;
        }

        .custom-form {
            display: flex;
            justify-content: space-between;
        }

        .column {
            flex: 1;
            margin-right: 20px;
        }

        .selected {
            background-color: #007bff;
            color: #fff;
        }

        select option:checked {
            background-color: #007bff;
            color: #fff;
        }

        .select-multiple option:selected {
            border: 2px solid #000;
            /* Puedes personalizar el color y el estilo del borde aquí */
            background-color: #fff;
            /* Personaliza el fondo de las opciones seleccionadas si es necesario */
            color: #007bff;
            /* Personaliza el color del texto de las opciones seleccionadas */
        }

        .custom-height {
            height: 300px;
            /* Ajusta la altura según tus preferencias */
        }
    </style>
    @yield('styles')
@endsection

@section('content')
    <div id="appTarifaDetalle">

        <div>
            <a class="regresar text-uppercase" href="{{ route('tarifas.index') }}">
                <span class="mdi mdi-arrow-left-box icono"></span> Volver a tarifas
            </a>
        </div>
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light shadow sidebarInformacionR mt-4">
            <div class="row">
                <div class="col-12 text-uppercase h3">
                    Tarifas
                </div>
                <div class="col-12">
                    <div class="list-group">
                        @foreach ($tarifasAll as $n)
                            <a href="{{ route('tarifas.detalle', ['id' => $n->cid]) }}"
                                class="list-group-item list-group-item-action {{ $tarifas->id == $n->id ? 'active' : '' }}"
                                id="{{ $tarifas->id == $n->id ? 'tarifaSelected' : 't' . $n->id }}" aria-current="true">
                                <small>
                                    {{ $n->tarifa }}
                                </small>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light shadow sidebarInformacion mt-4">
            <h3>Informacion de la tarifa</h3>
            <small></small>

            <div class="col-12 col-md-12">
                <div class="row mb-3 p-4">
                    <div class="col-md-12">
                        <div class="card border-secondary p-6">
                            <div class="card-body">
                                <p>
                                    <span class="card-title text-uppercase h4 w-100">
                                        @{{ tarifas.tarifa }}
                                    </span><br>
                                    <small class="text-uppercase mb-3">
                                        <span>Precio de tarifa: </span> $ @{{ tarifas.precio }}
                                    </small><br>
                                    <small class="text-uppercase mb-3">
                                        <span>Numero de dias: </span>@{{ tarifas.numero_dias }}
                                    </small>

                                </p>
                            </div>
                        </div>
                    </div>

                    @can('admin')
                        <div class="col-12 mt-3">
                            <h4>Tipos y formas agregados a la tarifa</h4>

                            <small v-if="tarifaDetalles.length == 0"> Aun no se ha agregado un tipo y forma de habitacion a esta
                                tarifa</small>

                            <ul class="list-group">
                                <li class="list-group-item" v-for="p in tarifaDetalles" :key="p.id">
                                    <div>
                                        <span v-if="p.tipo_habitaciones && p.forma_habitaciones">
                                            @{{ p.tipo_habitaciones.tipo_habitacion }} - @{{ p.forma_habitaciones.forma_habitacion }}
                                            <span
                                                class="badge rounded-pill text-bg-secondary mdi mdi-account">@{{ p.forma_habitaciones.max_personas }}
                                            </span>


                                            <a class="nav-link float-end text-muted" href="#"
                                                @click="destroyTarifaDetalle(p.id)">
                                                <span class="mdi mdi-delete"></span>
                                            </a>
                                    </div>
                                </li>
                            </ul>
                        </div>


                    </div>
                </div>
            </div>
            <div class="container">
                <div class="col-11 " v-if="message.type" :class="['alert', 'alert-' + message.type]">
                    @{{ message.message }}
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h2>Detalle de tarifas en habitaciones .</h2>
                        <h6>seleccione el tipo y la forma de las habitaciones</h6>
                    </div>
                </div>
                <div class="container mt-4">
                    <div class="row justify-content-start">
                        <div class="col-12 col-md-11">
                            <div class="card">
                                <div class="card-body">
                                    <div class="custom-form">
                                        <div class="column mb-2">
                                            <div class="form-group">
                                                <h4>Tipos</h4>
                                                <select multiple class="form-control select-multiple custom-height"
                                                    v-model="selectedTipos">
                                                    <option v-for="t in tipo_habitaciones" :key="t.id"
                                                        :value="t.id" :class="{ 'selected': isSelectedTipo(t.id) }"
                                                        @dblclick="deactivateTipoDetalle(t.id)">
                                                        @{{ t.tipo_habitacion }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="column">
                                            <div class="form-group">
                                                <h4>Formas</h4>
                                                <select multiple class="form-control select-multiple custom-height"
                                                    v-model="selectedFormas">
                                                    <option v-for="f in forma_habitaciones" :key="f.id"
                                                        :value="f.id"
                                                        :class="{ 'bg-primary text-white': isSelectedForma(f.id) }"
                                                        @dblclick="deactivateFormaDetalle(f.id)">
                                                        @{{ f.forma_habitacion }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" text-start-center ">
                                        <button class="btn btn-primary" type="button"
                                            @click="saveTarifaDetalle">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4 mt-3">
                        <div class="col-11">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase">Habitaciones que aplican:</h5>

                                    <!-- mostrar todas las habitaciones agregadas a esta tarifa -->
                                    @if ($habitaciones->isEmpty())
                                        <p>Aún no se ha aplicado esta tarifa en habitaciones.</p>
                                    @else
                                        <div class="row">
                                            @foreach ($habitaciones as $p)
                                                <div class="col-3 tarifa mt-2">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h6 class="card-title ">
                                                                <strong>Habitación {{ $p->numero_habitacion }}</strong>
                                                            </h6>
                                                            <p class="card-title">
                                                                <span class="text-uppercase">
                                                                    {{ $p->relacionFormaHabitaciones->forma_habitacion }} |
                                                                    {{ $p->relacionTipoHabitaciones->tipo_habitacion }}</span>
                                                            </p>

                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- End container. -->
            </div>
        @endcan

    </div>
    <script>
        window.onload = () => {
            const tarifaSelectedIndex = document.getElementById("tarifaSelected");
            if (tarifaSelectedIndex) {
                tarifaSelectedIndex.scrollIntoView({
                    block: 'center',
                    inline: 'center',
                    behavior: 'smooth'
                });
            }
        };

        const tarifa_detalles = new Vue({

            el: '#appTarifaDetalle',
            data: {
                tarifa: "{{ $tarifa }}",
                tarifaDetalles: @json($tarifaDetalles),
                forma_habitaciones: @json($formaHabitaciones),
                tipo_habitaciones: @json($tipoHabitaciones),
                tarifas: @json($tarifas),
                message: {},
                selectedTipos: [],
                selectedFormas: [],
            },

            methods: {
                isSelectedTipo(tipoId) {
                    return this.selectedTipos.find(id => id === tipoId) !== undefined || this.tarifaDetalles.find(
                        detalle => detalle.tipo_habitaciones_id === tipoId);
                },
                deactivateTipoDetalle(tipoId) {
                    const index = this.tarifaDetalles.findIndex(detalle => detalle.tipo_habitaciones_id === tipoId);
                    if (index !== -1) {
                        const detalle = this.tarifaDetalles[index];
                        this.destroyTarifaDetalle(detalle
                            .id); // Llama a la función destroyTarifaDetalle con el ID del detalle
                    }
                },
                deactivateFormaDetalle(formaId) {
                    const index = this.tarifaDetalles.findIndex(detalle => detalle.forma_habitaciones_id ===
                        formaId);
                    if (index !== -1) {
                        const detalle = this.tarifaDetalles[index];
                        this.destroyTarifaDetalle(detalle.id); // Elimina el detalle de tarifa
                    }
                },

                Tipos(tipoId) {
                    const index = this.selectedTipos.indexOf(tipoId);
                    this.selectedTipos = index === -1 ? [...this.selectedTipos, tipoId] :
                        this.selectedTipos.filter(id => id !== tipoId);
                },
                isSelectedForma(formaId) {
                    return this.selectedFormas.find(id => id === formaId) !== undefined || this.tarifaDetalles.find(
                        detalle => detalle.forma_habitaciones_id === formaId);
                },
                Formas(formaId) {
                    const index = this.selectedFormas.indexOf(formaId);
                    this.selectedFormas = index === -1 ? [...this.selectedFormas, formaId] :
                        this.selectedFormas.filter(id => id !== formaId);
                },
                async saveTarifaDetalle() {
                    try {
                        // Comprobar si se han seleccionado tipos y formas
                        const tiposSeleccionados = this.selectedTipos.length > 0;
                        const formasSeleccionadas = this.selectedFormas.length > 0;

                        if (!tiposSeleccionados && !formasSeleccionadas) {
                            this.setMessage('Seleccione al menos un tipo o forma o ambas de habitación.',
                                'warning');
                            return; // si no hay ninguna seleccion de tipo o forma se mostrara el mensaje
                        }

                        const tiposFormas = [];

                        // Obtengo  las combinaciones de tipo y forma ya guardadas para la tarifa actual
                        const combinacionesGuardadas = this.tarifaDetalles.map(detalle => ({
                            tipo_habitaciones_id: detalle.tipo_habitaciones_id,
                            forma_habitaciones_id: detalle.forma_habitaciones_id,
                        }));

                        // Combino tipos y formas seleccionados con los ya guardados y si no hay los creo
                        for (const tipoId of tiposSeleccionados ? this.selectedTipos : combinacionesGuardadas
                                .map(detalle => detalle.tipo_habitaciones_id)) {
                            for (const formaId of formasSeleccionadas ? this.selectedFormas :
                                    combinacionesGuardadas.map(detalle => detalle.forma_habitaciones_id)) {
                                tiposFormas.push({
                                    "tarifa": this.tarifa,
                                    "tipo_habitaciones_id": tipoId,
                                    "forma_habitaciones_id": formaId,
                                });
                            }
                        }

                        const response = await axios.post('{{ route('tarifa_detalles.store') }}', tiposFormas);

                        if (response.data) {
                            this.tarifaDetalles = response.data.tarifaDetalles;
                            this.selectedTipos = [];
                            this.selectedFormas = [];
                            this.setMessage(response.data.message, response.data.type);


                        }
                    } catch (error) {
                        console.error(error);
                    }
                },

                destroyTarifaDetalle(id) {
                    if (confirm("Este tipo y forma se borrará definitivamente. ¿Realmente quieres eliminarlo?")) {
                        axios.post('{{ route('tarifa_detalles.destroy_api') }}', {
                                "id": id
                            })
                            .then((rs) => {
                                if (rs.data.tarifaDetalles)
                                    this.tarifaDetalles = rs.data.tarifaDetalles;
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2 * 1000);

                                this.setMessage(rs.data.message, rs.data.type);
                            }).catch(e => console.log(e));
                    }
                },
                setMessage(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };
                    setTimeout(() => {
                        this.message = {};
                    }, 2 * 1000)
                },
            },

            mounted() {

            },
            computed: {

            }
        });
    </script>
@endsection
