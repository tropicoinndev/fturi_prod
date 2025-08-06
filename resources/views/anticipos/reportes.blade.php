@extends('layouts.anticipos')

@section('css-anticipos')
    <style>
        .table {
            font-size: 0.875rem;
            background-color: #ffffff;
        }

        .table thead {
            background-color: #f2f2f2;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #e0e0e0;
        }

        .panelCalendar {
            min-height: 85vh;
        }

        [v-cloak] {
            display: none;
        }
    </style>
@endsection

@section('panel_anticipo')
    <div id="AnticipoDisponible" class="container" v-cloak>
        <div class="card panelCalendar">
            <div class="card-body p-5">
                <form action="{{ route('anticipos.reporte_anticipo_buscar') }}" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="card-title col-12 h5 text-uppercase mb-4">REPORTE DE ANTICIPOS DISPONIBLES</div>
                        <div class="col-6">
                            <input type="hidden" name="clientes_id" v-model="selectedCliente"
                                value="{{ $clienteId ?? old('clientes_id') }}">
                            <label for="buscarCliente" class="form-label">Buscar cliente</label>
                            <div class="col-12 input-group">
                                <input type="text" id="buscarCliente" name="cliente" class="form-control"
                                    v-model="txtBuscarApi" placeholder="Buscar el cliente con anticipos"
                                    @input="getClientes" autocomplete="off" @keydown.up.prevent="selectItem(-1)"
                                    @keydown.down.prevent="selectItem(1)" @keydown.enter.prevent="setCliente()"
                                    value="{{ $clienteId ?? old('cliente') }}" :readonly="isReadonly" />
                                <button class="btn btn-outline-secondary" type="button" @click="clearCliente"
                                    v-show="selectedCliente">
                                    <i class="mdi mdi-close"></i>
                                </button>
                            </div>
                            <div class="d-fixed shadow mt-2" v-show="showClientList"
                                :style="{ top: 'calc(100% + 0.5rem)' }">
                                <div class="list-group list-group-flush">
                                    <button type="button" class="list-group-item list-group-item-action"
                                        v-for="(cl, index) in clientesList" @click="setCliente(index)"
                                        :key="index" @keydown.enter="setCliente(index)"
                                        :class="{ 'active': index === selectedIndex }" tabindex="0">
                                        @{{ cl.cliente }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-12" v-if="noResults" :style="{ marginTop: '0.5rem' }">
                                <div class="list-group list-group-flush">
                                    No se encontró ningún registro con los parámetros de búsqueda.
                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="mb-3">
                                <label for="" class="form-label">Fecha</label>
                                <input type="date" name="fecha" class="form-control" value="{{ $fecha ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-3 row align-items-center">
                            <div class="col">
                                <button class="btn btn-light" type="submit" role="button" value="1" name="opcion">
                                    <span class="mdi mdi-magnify h5"></span>
                                    Buscar
                                </button>
                                <button class="btn btn-light" type="submit" role="button" value="2" name="opcion">
                                    <span class="mdi mdi-file-pdf-box h5"></span>
                                    PDF
                                </button>
                                <button class="btn btn-light" type="submit" role="button" value="3" name="opcion">
                                <span class="mdi mdi-file-excel h5"></span>
                                XLS
                            </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if (isset($anticipos) && count($anticipos) > 0)
                    <div class="row">
                        @foreach ($anticipos as $anticipo)
                            <div class="col-12 mb-4">
                                <table
                                    class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Caja/Turno</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Aplicación</th>
                                            <th scope="col">Monto</th>
                                            <th scope="col">Cliente</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Separacion</th>
                                            <th scope="col">Usuario</th>
                                            <th scope="col">Creado</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="{{ $anticipo->anulado ? 'row-anulado' : '' }}">
                                            <td>{{ $anticipo->id }}</td>
                                            <td>{{ $anticipo->turnos->cajasSucursales->caja }}/{{ $anticipo->turnos->opcion->turno }}
                                            </td>
                                            <td>{{ $anticipo->fecha }}</td>
                                            <td>{{ $anticipo->fecha_aplicacion }}</td>
                                            <td>${{ number_format($anticipo->monto_historico, 2, ',', '.') }}</td>
                                            <td>{{ $anticipo->clientes->nombre ?? 'N/A' }}</td>
                                            <td>
                                                @if ($anticipo->estado)
                                                    <span class="text-success">Anticipo sin aplicar</span>
                                                @else
                                                    <span class="text-info">Anticipo aplicado</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($anticipo->separado)
                                                    <span class="text-danger">Este anticipo fue separado</span>
                                                @else
                                                    <span class="text-success">Anticipo normal</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $anticipo->users->name }}</span>
                                            </td>
                                            <td>{{ $anticipo->creacion }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="11">
                                                <strong>Concepto:</strong> {{ $anticipo->concepto }}
                                            </td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="row">
                        <div class="col-12">
                            <p class="text-center text-uppercase">No hay anticipos para el cliente seleccionado en la fecha
                                seleccionada</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const reporteDisponible = new Vue({
            el: '#AnticipoDisponible',
            data() {
                return {
                    clientesList: [],
                    selectedIndex: 0,
                    txtBuscarApi: '',
                    selectedCliente: '',
                    showClientList: false,
                    noResults: false,
                    isReadonly: false,
                };
            },
            watch: {
                selectedCliente(newValue) {
                    const selectedCliente = {
                        nombre: this.txtBuscarApi,
                        id: newValue,
                    };
                    localStorage.setItem('selectedCliente', JSON.stringify(selectedCliente));
                    this.isReadonly = !!newValue;
                },
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
                        const cliente = this.clientesList[index];
                        this.selectedCliente = cliente.id;
                        this.txtBuscarApi = cliente.cliente;
                        this.showClientList = false;
                    }
                },

                clearCliente() {
                    this.selectedCliente = '';
                    this.txtBuscarApi = '';
                    this.clientesList = [];
                    this.showClientList = false;
                    this.isReadonly = false;
                },
            },
            mounted() {
                const storedCliente = localStorage.getItem('selectedCliente');
                if (storedCliente) {
                    const selectedCliente = JSON.parse(storedCliente);
                    this.selectedCliente = selectedCliente.id;
                    this.txtBuscarApi = selectedCliente.nombre;
                }
            },
        });
    </script>
@endsection
