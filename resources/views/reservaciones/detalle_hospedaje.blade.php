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
            width: 0;
            height: 0;
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

        .align-items-end {
            align-items: flex-end !important;
        }
    </style>

    <div class="container" id="detalleHuesped">
        <div class="card panelCalendar">
            <div class="card-body p-5">
                <form action="{{ route('reservaciones.detalleHospedajeHuespedSearch') }}" method="post">
                    @csrf

                    <div class="row mb-2">
                        <div class="card-title col-12 h5 text-uppercase mb-4">
                            DETALLE HOSPEDAJE HUÉSPED
                        </div>
                    </div>

                    <div class="row mb-2 align-items-center">
                        <div class="col-5">
                            <div class="mb-3">
                                <label for="buscarhuesped" class="form-label">Buscar huésped registrado</label>
                                <div class="input-group">
                                <input type="text" class="form-control" id="buscarhuesped"
                                    placeholder="Escriba el nombre, número de identificación o teléfono..."
                                    v-model="buscarHuesped" @keyup="getHuespedSearch" autocomplete="off">
                                <button class="btn btn-outline-secondary" type="button" @click="limpiarSeleccion" v-show="huespedSelect.id">
                                    <i class="mdi mdi-close"></i>
                                </button>
                                </div>
                                <!-- Lista de resultados -->
                                <div class="lista shadow-lg w-25">
                                    <ul class="list-group" v-show="listHuesped.length > 0">
                                        <li v-for="v in listHuesped" :key="v.id"
                                            class="list-group-item list-group-item-action text-uppercase"
                                            @click="setHuesped(v)">
                                            @{{ v.nombre }} · @{{ v.identificaciones_id > 0 ? v.identificaciones.identificacion : 'Sin identificación' }}
                                            @{{ v.identificacion }} · @{{ v.telefono ?? 'Sin teléfono' }}
                                        </li>
                                    </ul>
                                    <ul class="list-group" v-show="!listHuesped">
                                        <li class="list-group-item list-group-item-action text-uppercase">
                                            No se han encontrado registros con los parámetros de búsqueda.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="huesped_id" :value="huespedSelect ? huespedSelect.id : ''">

                        <div class="col-2">
                            <div class="mb-3">
                                <label for="f_ingreso" class="form-label">Fecha de ingreso:</label>
                                <input type="date" class="form-control" id="f_ingreso" name="f_ingreso" v-model="f_ingreso" value="{{ $fecha_ingreso }}">
                            </div>
                        </div>

                        <div class="col-5">
                            <div class="mb-3">
                                <label class="w-100 mb-1">Seleccione el ingreso:</label>
                                <div class="d-flex align-items-center">
                                    <div class="btn-group me-3" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check" name="tipo_entrada" id="recepcion"
                                            value="0" autocomplete="off" v-model="tipo_entrada"  value="{{ $tipo_ingreso_huesped }}" required>
                                        <label class="btn btn-outline-primary" for="recepcion">Recepciones</label>

                                        <input type="radio" class="btn-check" name="tipo_entrada" id="reservaciones"
                                            value="1" autocomplete="off" v-model="tipo_entrada"  value="{{ $tipo_ingreso_huesped }}">
                                        <label class="btn btn-outline-primary" for="reservaciones">Reservaciones</label>
                                    </div>

                                    <button type="submit" class="btn btn-light">
                                        <span class="mdi mdi-magnify h5"></span> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                @if(isset($data))
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Huésped</th>
                                    <th scope="col">Ingreso por</th>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Nacionalidad</th>
                                    <th scope="col">Fecha entrada</th>
                                    <th scope="col">Fecha salida</th>
                                    <th scope="col">Nº habitación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $huesped)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $huesped->nombre_huesped }}</td>
                                        <td>{{ $huesped->tipo_registro }}</td>

                                        {{-- Mostrar habitación según el tipo de registro --}}
                                        <td>
                                            {{ $huesped->tipo_identificacion }} : {{$huesped->documento_huesped}}
                                        </td>
                                        <td>
                                            {{ $huesped->nacionalidad_huesped ?? 'Salvadoreña'}}
                                        </td>
                                        <td>
                                            {{ $huesped->fecha_ingreso }}
                                        </td>
                                        <td>
                                            {{ $huesped->fecha_salida }}
                                        </td>
                                        <td>
                                            {{ $huesped->numero_habitacion }}
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-uppercase"> No hay registros disponibles (si no encuentra registros al seleccionar recepciones puede seleccionar reservas o viceversa)</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="mt-4">Aún no hay datos para mostrar.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        var app = new Vue({
            el: '#detalleHuesped',
            data: {
                buscarHuesped: '',
                huesped_id: '',
                listHuesped: [],
                huespedSelect: [],
                tipo_entrada: "{{$tipo_ingreso_huesped}}",
                f_ingreso:"{{$fecha_ingreso}}",
            },
            methods: {
                getHuespedSearch: function(){
                    if(this.buscarHuesped.length > 3){
                        axios.post('{{ route('huespedes.api_buscar') }}', {
                            'buscar': this.buscarHuesped.toUpperCase(),
                        }).then(r => {
                            this.listHuesped = r.data.list.length ? r.data.list : [];
                        });
                    }
                    else{
                        this.listHuesped = [];
                    }
                },
                setHuesped: function(huesped){
                    this.huespedSelect = huesped;
                    this.buscarHuesped = huesped.nombre;
                    this.listHuesped = [];
                },
                limpiarSeleccion: function(){
                    this.buscarHuesped = '';
                    this.huespedSelect = [];
                    this.listHuesped = [];
                }
            },
            watch: {
                huespedSelect(newValue){
                    const selectedData = {
                        nombre: this.buscarHuesped,
                        id: newValue.id || '',
                    };
                    localStorage.setItem('selectedHuesped', JSON.stringify(selectedData));
                }
            },
            mounted(){
                const storedData = localStorage.getItem('selectedHuesped');

                if(storedData){
                    const parsedData = JSON.parse(storedData);
                    this.buscarHuesped = parsedData.nombre;
                    this.huespedSelect = { id: parsedData.id };
                }
            },
        });
    </script>
@endsection
