@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        .listComprobantes {
            cursor: pointer;
        }

        .listComprobantes:hover {
            background: #00838F;
            color: #ffffff;
        }
    </style>
@endsection
@section('content_cliente')
    <div class="row p-4" id="appROS">
        <div class="col-12 h2 text-uppercase mb-4">
            NUEVO REPORTE DE OPERACIÓN SOSPECHOSA
        </div>
        <div class="col-12">
            <x-message />
            <form action="{{ route('ros.store') }}" method="post">
                @csrf
                <input type="hidden" name="comprobantes_id" :value="comprobanteSelected?.id">
                <input type="hidden" name="sucursales_id" :value="sucursalSelected?.id">
                <div class="mb-2">
                    ¿Tiene el correlativo del comprobante?
                </div>
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="correlativo" value="1"
                            v-model="correlativo" id="si" @click="comprobanteSelected = sucursalSelected = null" />
                        <label class="form-check-label" for="si"
                            @click="comprobanteSelected = sucursalSelected = null">Si, tengo el correlativo</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="correlativo" value="0"
                            v-model="correlativo" id="no" @click="comprobanteSelected = sucursalSelected = null" />
                        <label class="form-check-label" for="no"
                            @click="comprobanteSelected = sucursalSelected = null">No</label>
                    </div>
                </div>
                <div class="row" v-if="correlativo == 1 && comprobanteSelected != null">
                    <div class="alert alert-success" role="alert">
                        <strong>Comprobante seleccionado</strong>
                        <div class="row mb-3">
                            <div class="col-12">
                                <b>Fecha:</b> @{{ comprobanteSelected.fecha }}
                            </div>
                            <div class="col-12">
                                <b>Titular:</b> @{{ comprobanteSelected.titular }}
                            </div>
                            <div class="col-12">
                                <b>Correlativo interno:</b> @{{ comprobanteSelected.correlativo }}
                            </div>
                        </div>
                        <button class="btn btn-light" type="button" @click="comprobanteSelected = null">Cambiar
                            selección</button>
                    </div>

                </div>
                <div class="row" v-if="sucursalSelected != null">
                    <div class="alert alert-light" role="alert">
                        <strong>Detalles de sucursal</strong>
                        <div class="row mb-3">
                            <div class="col-12">
                                <b>Punto de servicio:</b> @{{ sucursalSelected.sucursal }}
                            </div>
                            <div class="col-12">
                                <b>Dirección:</b> @{{ sucursalSelected.direccion }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="correlativo == 1 && comprobanteSelected == null">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Buscar comprobante</label>
                            <div class="input-group">
                                <input type="number" step="1" min="1" class="form-control"
                                    placeholder="Escriba el numero de correlativo interno" v-model="busqueda"
                                    @keydown.enter.prevent @keydown.enter="getComprobante" />
                                <div class="input-group-append">
                                    <button class="btn btn-primary ms-3" type="button"
                                        @click="getComprobante">Buscar</button>
                                </div>
                            </div>

                        </div>
                        <ul class="list-group list-group-flush"
                            v-if="comprobantes && comprobantes.length > 0 && busqueda != null">
                            <span>-- Seleccione el comprobante correspondiente a la transacción --</span>
                            <li class="list-group-item listComprobantes" v-for="c in comprobantes" @key="c.cid"
                                @click="setComprobante(c)">
                                <b>Fecha:</b> @{{ c.fecha }} <b>Titular:</b> @{{ c.titular }} <b>Correlativo
                                    interno:</b> @{{ c.correlativo }}
                            </li>
                        </ul>

                    </div>
                </div>
                <div class="row mb-4" v-if="correlativo == 0">
                    <div class="col-12 mb-2">
                        Seleccione la sucursal donde sucedio la operacion
                    </div>
                    <div class="col-4" v-for="s in sucursales">

                        <input type="radio" class="btn-check" :id="'sucursal_' + s.id" autocomplete="off"
                            name="sucursales" v-model="sucursalSelected" :value="s" />

                        <label class="card btn btn-outline-primary h-100" :for="'sucursal_' + s.id">
                            <div class="card-body">
                                <h5 class="card-title">@{{ s.sucursal }}</h5>
                                <small class="card-text">
                                    @{{ s.direccion }}
                                </small>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="row" v-if="sucursalSelected != null || comprobanteSelected !=null">
                    <div class="col-12 mb-3">
                        <label for="fecha" class="form-label">Fecha de la operación</label>
                        <input type="date" class="form-control" name="fecha" id="fecha" aria-describedby="helpId"
                            required :value="comprobanteSelected?.fecha ?? null" />
                    </div>
                    <div class="col-12 mb-3">
                        <label for="forma_pagos_id" class="form-label">Tipo de operacion</label>
                        <select class="form-select" name="forma_pagos_id" id="forma_pagos_id" required>
                            <option selected value="">Seleccione una opción</option>
                            @foreach ($formas as $f)
                                <option value="{{ $f->id }}">{{ $f->forma }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="monto" class="form-label">Monto</label>
                        <input type="number" class="form-control" name="monto" id="monto"
                            aria-describedby="helpId" placeholder="Ingrese el monto de la operacion" required
                            :value="comprobanteSelected?.total ?? 0" />
                    </div>
                    <div class="col-12 mb-3">
                        <label for="clase" class="form-label">Clase de producto</label>
                        <textarea class="form-control" name="clase" id="clase" aria-describedby="helpId" placeholder="Escriba aquí"
                            required></textarea>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="nombre" class="form-label">Nombre de quien realiza la transacción</label>
                        <input type="text" class="form-control" name="nombre" id="nombre"
                            aria-describedby="helpId" placeholder="Escriba aquí" required
                            :value="comprobanteSelected?.clientes?.nombre ?? null" />
                    </div>
                    <div class="col-12 mb-3">
                        <label for="identificaciones" class="form-label">Tipo de identificación</label>
                        <select class="form-select" name="identificaciones" id="identificaciones">
                            <option selected value="">Seleccione una opción</option>
                            @foreach ($identificaciones as $i)
                                <option value="{{ $i->id }}">{{ $i->identificacion }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="numero" class="form-label">Identificación</label>
                        <input type="text" class="form-control" name="numero" id="numero"
                            aria-describedby="helpId" placeholder="Escriba aquí" />
                    </div>
                    <div class="col-12 mb-3">
                        <label for="observaciones" class="form-label">Breve descripción del evento:</label>
                        <textarea class="form-control" name="observaciones" id="observaciones" aria-describedby="helpId"
                            placeholder="Razón por la que considera la operación sospechosa" required></textarea>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="cargo" class="form-label">Cargo</label>
                        <input type="text" class="form-control" name="cargo" id="cargo"
                            aria-describedby="helpId" placeholder="Escriba aquí su cargo en la empresa" />
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Guardar ROS</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <script type="module">
        var app = window.appVue({
            data() {
                return {
                    busqueda: '',
                    correlativo: 1,
                    comprobantes: [],
                    sucursales: @json($sucursales),
                    comprobanteSelected: null,
                    sucursalSelected: null,
                }
            },
            methods: {
                getComprobante: function() {
                    if (this.busqueda != null && this.busqueda > 0)
                        axios.post("{{ route('ros.comprobantes') }}", {
                            'bq': this.busqueda,
                        }).then((r) => {
                            console.log(r);

                            if (r.data.list) {
                                this.comprobantes = r.data.list;
                            }
                        }).catch((err) => {
                            console.log(err);

                        });

                },
                setComprobante: function(c) {
                    this.comprobanteSelected = c;
                    this.sucursalSelected = c.sucursal;
                }
            },
        });

        app.mount("#appROS");
    </script>
@endsection
