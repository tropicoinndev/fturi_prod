@extends('layouts.anticipos')

@section('css-anticipos')
    <style>
        :root {
            --color-primary: #263238;
            --color-secondary: #37474F;
            --color-light: #E3F2FD;

            --color-highlight: #BBDEFB;
            --color-dark: #455A64;
            --shadow-light: 1px 5px 2px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--color-background);
        }

        .panel-body {
            min-height: 91vh;

        }

        .btn-light {
            background: #DAE0E5;
        }
        .card-anticipo {
            background-color: #FFFFFF;
            border: 1px solid var(--color-highlight);
            box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .titulo,
        .titulo-anticipo {
            color: var(--color-primary);
            font-weight: bold;
        }

        .text-eventos,
        .text-cliente,
        .total,
        .monto {
            color: var(--color-secondary);
        }
        .concepto {
            color: var(--color-dark);
        }

        .update {
            min-height: 80vh;
        }

        [v-cloak] {
            display: none;
        }
    </style>
@endsection

@section('panel_anticipo')
    <div id="updateClienteAnticipo" v-cloak>
        <div class="update container">
            <div class="row">
                <div class="col-12 m-auto">
                    <div class="card p-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h3>Actualización de cliente del anticipo #{{ $p->id }}</h3>
                                </div>
                                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                    <a href="{{ route('anticipos.index') }}" class="btn btn-light">
                                        <span class="mdi mdi-arrow-left fs-5 me-2"></span>
                                        Volver
                                    </a>
                                </div>
                            </div>
                            <div class="card-text">
                                Cliente: {{ $p->clientes->nombre }}
                            </div>
                            <div class="card-text">
                                <p>Identificación:
                                    {{ $p->clientes?->identificaciones[0]?->identificaciones?->identificacion ?? 'Sin identificación' }}:{{ $p->clientes?->identificaciones[0]?->numero ?? 'Sin número' }}
                                </p>
                            </div>
                            <div class="col-12">
                                <div class="card-anticipo mt-4">
                                    <div class="titulo-anticipo">Detalles del Anticipo</div>
                                    <div class="monto">Fecha de aplicacion: {{ $p->aplicacion }}</div>
                                    <div class="monto">Monto: ${{ number_format($p->monto, 2) }}</div>
                                    <div class="monto">Turno: {{ $p->turnos->opcion->turno }}</div>
                                    <div class="monto">Caja: {{ $p->turnos->cajasSucursales->caja }}</div>
                                    <div class="concepto">Concepto: {{ $p->concepto }}</div>
                                    <div class="concepto">Creado por: {{ $p->users->name }}</div>
                                    <div class="concepto">Fecha y hora de creacion: {{ $p->creacion }}</div>
                                </div>
                            </div>
                            <div class="card-text pt-2 pb-2">
                                @if (!$p->anulado)
                                    <form action="{{ route('anticipos.updateClienteAnticipo') }}" method="post">
                                        @csrf
                                        <input type="hidden" value="{{ Crypt::encryptString($p->id) }}" name="id">
                                        <input type="hidden" name="clientes_id" v-model="selectedCliente"
                                            value="{{ old('clientes_id') }}">

                                        <div class="col-12">
                                            <label for="buscarCliente" class="form-label">Buscar cliente</label>
                                            <input type="text" id="buscarCliente" name="cliente" class="form-control"
                                                v-model="txtBuscarApi"
                                                placeholder="Buscar el cliente a quien se asignará el anticipo"
                                                @input="getClientes" autocomplete="off" @keydown.up.prevent="selectItem(-1)"
                                                @keydown.down.prevent="selectItem(1)" @keydown.enter.prevent="setCliente()"
                                                 value="{{ old('cliente') }}" />

                                            <div class="d-fixed shadow"
                                                v-show="showClientList ">
                                                <div class="list-group list-group-flush">
                                                    <button type="button" class="list-group-item list-group-item-action"
                                                        v-for="(cl, index) in clientesList" @click="setCliente(index)"
                                                        :key="index" @keydown.enter="setCliente(index)"
                                                        :class="{ 'active': index === selectedIndex }" tabindex="0">
                                                        @{{ cl.cliente }}
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-12" v-if="noResults ">
                                                <div class="list-group list-group-flush mt-2">
                                                    No se encontró ningún registro con los parámetros de búsqueda.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    name="confirmacion" id="confirmacion" required>
                                                <label class="form-check-label" for="confirmacion">
                                                    Sí, estoy seguro de actualizar el cliente al anticipo
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                Actualizar cliente al anticipo
                                            </button>
                                            <a href="{{ route('anticipos.index') }}" class="btn btn-light">Cancelar</a>
                                        </div>
                                    </form>
                                @else
                                    <div class="alert alert-primary" role="alert">
                                        Este anticipo ya fue anulado.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const clienteUpdate = new Vue({
            el: '#updateClienteAnticipo',
            data: {
                clientesList: [],
                selectedIndex: 0,
                txtBuscarApi: '',
                selectedCliente: '',
                showClientList: false,

                noResults: false
            },
            methods: {
                getClientes() {
                    if (this.txtBuscarApi.length > 4) {
                        axios.post("{{ route('clientes.api_search_list') }}", {
                                busqueda: this.txtBuscarApi
                            })
                            .then(response => {
                                this.clientesList = response.data.list || [];
                                this.showClientList = this.clientesList.length > 0;
                                this.noResults = this.clientesList.length === 0;
                            })
                            .catch(error => {
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
                    this.selectedIndex = (this.selectedIndex + index + this.clientesList.length) % this.clientesList
                        .length;
                },
                setCliente(index = this.selectedIndex) {
                    if (this.clientesList[index]) {
                        this.selectedClientId = this.clientesList[index];
                        this.txtBuscarApi = this.selectedClientId.cliente;
                        this.selectedCliente = this.selectedClientId.id;
                        this.showClientList = false;
                    }
                }
            }
        });
    </script>
@endsection
