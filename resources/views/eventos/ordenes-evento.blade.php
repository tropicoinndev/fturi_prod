@extends('layouts.app')
@section('style')
    <style>
        .btn-light {
            background: #DAE0E5;
        }

        .message {
            position: fixed;
            top: 20%;
            right: 1%;
            z-index: 100;
        }
    </style>
@endsection

@section('content')
    <div id="ordenesEvento">
        <div class="container">
            <div class="card panel-body shadow p-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-message></x-message>
                            <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                                v-show="message.message && message.type">
                                <strong>@{{ message.message }}</strong>
                            </div>

                            <h3 class="card-title text-uppercase">Detalle de ordenes de eventos</h3>
                            <p class="text-uppercase text-muted">Listado de detalles ordenes de eventos</p>
                        </div>

                    </div>
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <div class="card shadow bg-body rounded">
                                <h5 class="card-header text-bg-primary text-center">Agregar desde concepto</h5>
                                <div class="card-body">
                                    <div class="row  mb-2">
                                        <div class="col-4">
                                            <div class="row">

                                                <div class="col-md-12"> <!-- Nombre del titular o cliente -->
                                                    <div class="d-flex justify-content-start">
                                                        <div class="titular">
                                                            @if (isset($p->clientes_id) && $p->clientes_id != null)
                                                                <span class="fw-bold">Cliente:</span>
                                                                {{ $p->clientes->nombre }}
                                                            @else
                                                                <span class="fw-bold">Titular:</span>
                                                                {{ $p->titular ?? '' }}
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-4">

                                            <span> No. </span>
                                            <small class="ms-1 rounded-pill bg-light text-muted p-1">
                                                @{{ orden }}
                                            </small>

                                        </div>

                                        <div class="col-4 ">
                                            <div class="float-end">
                                                @if(!$p->comprobante)
                                                    <a type="button" class="btn btn-light btn-sm ms-3" href="{{ route('ordenes.bloquear', [
                                                'id' => $p->id,
                                            ]) }} "title="Regresar"
                                                    rol="button">
                                                    <span class="mdi mdi-lock-open-outline"></span>
                                                    <span class="d-none d-md-inline">Bloquear </span>
                                                </a>
                                            @endif
                                                <a type="button" class="btn btn-light btn-sm ms-3" href="{{ route('eventos.detalle', [
                                                'id' => $eventoId->cid,
                                            ]) }} "title="Regresar"
                                                    rol="button">
                                                    <span class="mdi mdi-arrow-left"></span>
                                                    <span class="d-none d-md-inline">Regresar</span>
                                                </a>
                                            </div>


                                        </div>
                                    </div>


                                    <table class="table" v-if="!ordenValid.comprobante">
                                        <thead>
                                            <tr>
                                                <th scope="row">Concepto</th>
                                                <th scope="col">Cantidad</th>
                                                <th scope="col">Precio</th>
                                                <th scope="col">Total</th>
                                                <th scope="col" style="text-align: center;">Info</th>
                                                <th scope="col" style="text-align: center;">Descuento</th>
                                                <th scope="col" style="text-align: center;">Venta Total</th>
                                                <th scope="col" style="text-align: center;">Agregar | Limpiar
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div v-show="!serviciosSelected.id">
                                                    <input type="text" class="form-control"
                                                        placeholder="Buscar concepto..." autocomplete="off" id="concepto"
                                                        name="concepto" v-model="txtBusqueda" @keyup="getServicios"
                                                        :disabled="serviciosSelected.length == 1">
                                                </div>

                                                <div class="text-center"
                                                    :class="{ 'producto-seleccionado': serviciosSelected }"
                                                    v-show="serviciosSelected.id">
                                                     @{{ serviciosSelected.servicio}}
                                                    <span class=" btn  mdi mdi-close" @click="limpiar"
                                                        title="Quitar seleccion"></span>
                                                </div>
                                                </td>
                                                <td style="width: 8%;">
                                                    <input type="number" class="form-control" placeholder="0"
                                                        min="1" id="cantidad" name="cantidad" v-model="cantidad"
                                                        @keyup="calcularTotal()" @change="calcularTotal()" @input="Decimal" @blur="ToInteger"
                                                        :disabled="serviciosSelected.length == 0" step="1">
                                                </td>
                                                <td style="width: 9%;">
                                                    <input type="number" class="form-control" placeholder="0.00"
                                                        step="any" id="precio" name="precio" v-model="precio"
                                                        @change="calcularTotal()" :disabled="serviciosSelected.length == 0">
                                                </td>
                                                <td style="width: 9%;">
                                                    <input type="number" class="form-control" placeholder="0.00"
                                                        min="0" step="any" disabled id="total" name="total"
                                                        v-model="total">
                                                </td>
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <span class="mdi mdi-cog"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li style="margin-left: 15px;">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="checkIva" :checked="estadoIva" disabled>
                                                                    <label class="form-check-label"
                                                                        for="checkIva">IVA</label>
                                                                </div>
                                                            </li>
                                                            <li style="margin-left: 15px;">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="checkCesc" :checked="estadoCesc" disabled>
                                                                    <label class="form-check-label"
                                                                        for="checkCesc">CESC</label>
                                                                </div>
                                                            </li>
                                                            <li style="margin-left: 15px;">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="checkAdvalorem" :checked="estadoAdvalorem"
                                                                        disabled>
                                                                    <label class="form-check-label"
                                                                        for="checkAdvalorem">Advalorem</label>
                                                                </div>
                                                            </li>
                                                            <li style="margin-left: 15px;">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="checkPropina" :checked="estadoPropina"
                                                                        disabled>
                                                                    <label class="form-check-label"
                                                                        for="checkPropina">Propina</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td style="width: 19%;">
                                                    <select :disabled="!serviciosSelected.descuento" class="form-select"
                                                        name="descuentos_id" id="descuentos_id" v-model="descuentos_id"
                                                        @change="changeSelectDescuentos">
                                                        <option selected disabled>--Seleccione--</option>
                                                        <option
                                                            v-for="({id, descuento, porcentaje, decimales}, index) in arrayDescuentos"
                                                            :value="id">
                                                            @{{ descuento + ' - ' + porcentaje + '%' }}
                                                        </option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <span class="form-control text-center" style="font-size: 17px;"><b>$
                                                            @{{ ventaTotal }}</b></span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-outline-success"
                                                        style="margin-right: 5px;" @click="agregarDetalleOrden"
                                                        :disabled="serviciosSelected.length == 0">
                                                        Agregar
                                                    </button>

                                                    <button type="button" class="btn btn-outline-secondary"
                                                        :disabled="serviciosSelected.length == 0" @click="limpiar">
                                                        Limpiar
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    {{-- @{{ servicios }} --}}
                                    <!--Desplegable / Listado de servicios.-->
                                    <div class="result shadow" v-if="!ordenValid.comprobante"
                                        style="position: absolute; z-index: 10; margin-top: -20px; margin-left: 9px; width: 29.0em; background: white; padding: 2px;">

                                        <!-- Mostrar lista de servicios si hay servicios disponibles -->
                                        <ul v-if="servicios.length > 0" class="list-group">
                                            <li v-for="servicio in servicios"
                                                class="list-group-item d-flex justify-content-between align-items-center"
                                                style="border-radius: 0px; border: none; cursor:pointer;"
                                                @click="selected(servicio)">
                                                @{{ servicio.servicio }}
                                                <span class="badge bg-primary rounded-pill">$
                                                    @{{ servicio.precio_unitario | decimales }}</span>
                                            </li>
                                        </ul>

                                        <!-- Mostrar mensaje si no hay servicios -->
                                        <div v-if="servicios.length === 0" class="result shadow text-muted"
                                            style="width: 20.0em;background: white; opacity: 0.8;">
                                            @{{ mensaje }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-lg bg-body rounded">
                                <h5 class="card-header text-bg-success text-center">Detalle de orden</h5>
                                <div class="card-body">

                                    @if (!$p->clientes_id)
                                        <div class="alert alert-warning" role="alert">
                                            *No se mostraran los impuestos hasta que agregue un cliente.
                                        </div>
                                    @endif

                                    <div class="table-responsive">
                                        <table class="table table-sm table align-middle user-select-none">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Cantidad</th>
                                                    <th scope="col" style="width:30%;">Concepto</th>
                                                    <th scope="col" style="text-align: right;">P. Unitario
                                                    </th>
                                                    <th scope="col" style="text-align: right;">P. Total
                                                    </th>
                                                    <th v-if="!ordenValid.comprobante "scope="col" style="text-align: center;">Eliminar
                                                    </th>
                                                    <th  v-if="e.autoriza && e.modificacion" scope="col" style="text-align: center;">Editar
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="listaDetalle in detalleOrdenes">
                                                    <td class="font-monospace">@{{ listaDetalle.cantidad }}</td>
                                                    <td style="width:30%;">@{{ listaDetalle.servicios.servicio }}</td>
                                                    <td><span class="float-end font-monospace">$
                                                            @{{ getVenta(listaDetalle) | decimales }}</span>
                                                    </td>
                                                    <td><span class="float-end font-monospace">$
                                                            @{{ (getVenta(listaDetalle) * listaDetalle.cantidad) | decimales }}</span>
                                                    </td>
                                                    <td style="text-align: center;"v-if="!ordenValid.comprobante ">
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                                            @click="eliminarDetalleOrden(listaDetalle.id, listaDetalle.servicios.servicio)">
                                                            <span class="mdi mdi-trash-can"></span>
                                                        </button>
                                                    </td>
                                                    @can('ordenes.aumentar')
                                                    <td style="text-align: center;" v-if="e.autoriza && e.modificacion ">
                                                        <button type="button" class="btn btn-light btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#modalEditar"
                                                            @click="editarDetalleOrden(listaDetalle.id, listaDetalle.servicios.servicio,listaDetalle.cantidad,)">
                                                            <span class="mdi  mdi-pencil-box-outline"></span>
                                                        </button>
                                                    </td>
                                                    @endcan
                                                </tr>
                                                <tr class="border-1 border-bottom border-secondary"></tr>
                                                <tr class="font-monospace">
                                                    <td colspan="2"></td>
                                                    <td><b class="float-end">Sub-total</b></td>
                                                    <td><span class="float-end text-secondary">$
                                                            @{{ getSubTotal() | decimales }}</span>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr class="font-monospace">
                                                    <td colspan="2"></td>
                                                    <td><b class="float-end">IVA</b></td>
                                                    <td><span class="float-end text-secondary">$
                                                            @{{ getIVA() | decimales }}</span>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr class="font-monospace">
                                                    <td colspan="2"></td>
                                                    <td><b class="float-end">CESC</b></td>
                                                    <td><span
                                                            class="float-end text-secondary">$@{{ getCESC() | decimales }}</span>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr class="font-monospace">
                                                    <td colspan="2"></td>
                                                    <td><b class="float-end">Ad-valorem (+)</b></td>
                                                    <td><span class="float-end text-secondary">$
                                                            @{{ getAdvalorem() | decimales }}</span>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr class="font-monospace">
                                                    <td colspan="2"></td>
                                                    <td><b class="float-end">Propina (+)</b></td>
                                                    <td><span class="float-end text-secondary">$
                                                            @{{ getPropina() | decimales }}</span>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr class="table-light font-monospace">
                                                    <td colspan="2"></td>
                                                    <td><b class="float-end">TOTAL</b></td>
                                                    <td><b><span class="float-end" style="font-size: 17px;">$
                                                                @{{ (getTOTAL()) | decimales }}</span></b></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End container-->

                <!-- Modal eliminar-->
                <div class="modal fade" id="modalEliminar" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('detalle_ordenes.delete') }}" method="POST">
                                @csrf

                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Esta seguro de
                                        eliminar este
                                        servicio?
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="idEliminarDetalleOrden" :value="idEliminarDetalleOrden">
                                    <div class="mt-3 mb-3">
                                        <ul>
                                            <li>@{{ registroAEliminar }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger">Si, Eliminar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal editar cantidad si el evento permite modificacion-->
                <div class="modal fade" id="modalEditar" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('detalle_ordenes.add_cantidad') }}" method="POST">
                                @csrf

                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar cantidad del detalle de  ordden
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="detalle" :value="idEliminarDetalleOrden">
                                    <div class="row">


                                        <div class="col-5">
                                            <input type="number" class="form-control" name="cantidad"
                                                 min="1" placeholder="Cantidad" :value="cantidadEditar"
                                                required />

                                        </div>
                                        <div class="col-7 p-2 fw-bold">
                                            @{{ registroAEliminar  }}
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
@endsection
@section('script')
    <script>
        var app = new Vue({
            el: '#ordenesEvento',
            data: {
                txtBusqueda: '',
                e: @json($eventoId),
                servicios: [],
                serviciosSelected: [],
                detalleOrdenes: @json($detalleOrdenes),
                orden: {{ Crypt::decryptString($p->id) }}, //Id de la orden.
                ordenValid: @json($p),
                cantidad: 1,
                precio: 0,
                precioSugerido: 0,
                total: 0,
                ventaTotal: 0,
                iva: 0,
                cesc: 0,
                advalorem: 0,

                estadoIva: true,
                estadoCesc: false,
                estadoAdvalorem: false,
                estadoPropina: false,

                titular: '',

                txtTitular: @json($p->titular),



                idEliminarDetalleOrden: null,
                registroAEliminar: '',

                //Descuentos.
                arrayDescuentos: @json($descuentos),
                descuentos_id: 0,
                //message
                message: {},
                mensaje: '',

            },
            mounted() {

            },
            computed: {

            },
            methods: {
                Decimal(event) {
                     event.target.value = event.target.value.replace(/[^0-9.]/g, '');
                    this.cantidad = event.target.value;
                    },
                 ToInteger(event) {

                        if (event.target.value.includes('.')) {
                            this.cantidad = parseInt(event.target.value, 10);
                            event.target.value = this.cantidad;
                        }
                        },
                getIVA() {
                    return this.detalleOrdenes.reduce((acumulador, v) => acumulador + (v.iva * v.cantidad), 0)
                },
                getCESC() {
                    return this.detalleOrdenes.reduce((acumulador, v) => acumulador + (v.cesc * v.cantidad), 0);
                },
                getAdvalorem() {
                    return this.detalleOrdenes.reduce((acumulador, v) => acumulador + (v.advalorem * v.cantidad),
                        0);
                },
                getPropina() {
                    return this.detalleOrdenes.reduce((acumulador, v) => acumulador + (v.propina * v.cantidad), 0);
                },
                getServicios() {
                    if (this.txtBusqueda.length > 4) {
                        axios.post("{{ route('servicios.apiSearch') }}", {
                                txtBusqueda: (this.txtBusqueda).toUpperCase(),
                                idOrden: this
                                    .orden, //Enviar el id de la orden para verificar si esta inactiva, para asi evitar agregar un nuevo producto/servicio.
                            })
                            .then((resp) => {

                                this.servicios = resp.data.servicios || [];
                                this.mensaje = resp.data.mensaje || '';
                            })
                            .catch(error => {
                                console.log(error);
                            });
                    }
                },
                selected(s) {
                    this.serviciosSelected = s;
                    this.servicios = [];
                    this.precio = this.serviciosSelected.precio_unitario;
                    this.precioSugerido = this.serviciosSelected.sugerido;
                    this.txtBusqueda = this.serviciosSelected.servicio;

                    this.calcularTotal();

                    this.estadoIva = this.serviciosSelected.iva || false;
                    this.estadoCesc = this.serviciosSelected.cesc || false;
                    this.estadoAdvalorem = this.serviciosSelected.advalorem || false;
                    this.estadoPropina = this.serviciosSelected.propina || false;
                },
                calcularTotal() {
                    if (this.precio < this.serviciosSelected.precio_unitario) {
                        this.precio = this.serviciosSelected.precio_unitario;

                    } else {
                        this.total = ((this.precio * this.cantidad)).toFixed(2);
                        this.ventaTotal = this.total;
                    }
                },
                agregarDetalleOrden() {
                    axios.post("{{ route('detalle_ordenes.store') }}", {
                            cantidad: this.cantidad,
                            precio_unitario: this.precio,
                            precio_sugerido: this.precioSugerido,
                            neto: this.total,
                            iva: this.estadoIva,
                            cesc: this.estadoCesc,
                            advalorem: this.estadoAdvalorem,
                            propina: this.estadoPropina,
                            descuentos_id: this.descuentos_id,
                            servicios_id: this.serviciosSelected.id,
                            ordenes_id: this.orden,
                        })
                        .then((resp) => {
                            //console.log(resp);
                            if (resp.data.type === 'success') {
                                this.detalleOrdenes = resp.data.detalleOrdenes;
                                this.limpiar();
                            } else {
                                this.setMessage(resp.data.msj, resp.data.type);

                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                setMessage(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };
                    setTimeout(() => {
                        this.message = {};
                    }, 3 * 1000)
                },
                limpiar() {
                    this.txtBusqueda = '';
                    this.cantidad = 1;
                    this.precio = '';
                    this.total = '';
                    this.ventaTotal = 0;
                    this.serviciosSelected = [];

                    this.estadoCesc = this.estadoAdvalorem = this.estadoPropina = false;
                },
                eliminarDetalleOrden(id, selectedDelete) {
                    this.idEliminarDetalleOrden = id;
                    this.registroAEliminar = selectedDelete;
                },
                editarDetalleOrden(id, selectedDelete, cantidad) {
                    this.idEliminarDetalleOrden = id;
                    this.registroAEliminar = selectedDelete;
                    this.cantidadEditar = cantidad;
                },
                getVenta(d) {
                    //Impuestos en variables de entorno
                    let iva = {{ env('iva', 0.13) }};
                    let cesc = {{ env('cesc', 0.05) }};
                    let propina = {{ env('propina', 0.1) }};
                    let advalorem = {{ env('advalorem', 0.05) }};

                    //AdValorem
                    let advaloremTotal = 0;
                    let porcentaje = 1;
                    let precio_neto = 0;
                    let s = d.servicios;
                    let precio = d.precio_unitario;
                    //console.log(d)

                    //Calculo de descuentos
                    if (d.descuentos_id > 0)
                        precio = this.round(d.precio_unitario - (d.precio_unitario * d.descuentos.decimales), 4);

                    //Precio incluye IVA
                    if (s.iva)
                        porcentaje += iva;

                    //Precio incluye CESC
                    if (s.cesc)
                        porcentaje += cesc;

                    //Precio incluye Propina
                    if (s.propina)
                        porcentaje += propina;

                    //calcular advalorem
                    if (s.advalorem) {
                        precio_neto = this.round(precio / porcentaje, 4);
                        let sugerido = this.round(s.sugerido / (1 + iva), 4);

                        if (precio_neto > sugerido)
                            advaloremTotal = this.round((precio_neto - sugerido) * advalorem, 4);
                    }
                    //console.log(d);
                    precio_neto = this.round((precio - advaloremTotal) / porcentaje, 4);

                    //console.log(precio_neto)
                    return parseFloat(precio_neto);
                },
                round(val, decimal) {
                    return parseFloat(val.toFixed(decimal));
                },
                getSubTotal() {
                    const suma = this.detalleOrdenes.reduce((acumulador, v) => {
                        const cantidad = v.cantidad || 1;
                        const subtotalItem = cantidad * this.getVenta(v);
                        return acumulador + subtotalItem;
                    }, 0);

                    return suma;
                },
                getTOTAL() {
                    const total = this.detalleOrdenes.reduce((acumulador, v) => {
                        const cantidad = v.cantidad ||
                            1; // Asumo una cantidad predeterminada de 1 si no está especificada
                        //const precioUnitario = v.precio_unitario || 0;
                        const subtotalItem = this.getSubTotal() + this.getIVA() + this.getCESC() + this.getAdvalorem() + this.getPropina();
                        return acumulador + subtotalItem;

                    }, 0);

                    return total;

                },
                changeSelectDescuentos() {
                    this.descuentos_id = this.descuentos_id;
                    console.log('Change: ', this.descuentos_id);
                },
            },
            filters: {
                decimales: function(value) {
                    var deci = 2;
                    if (!value) return 0.00;
                    return parseFloat(value).toFixed(deci);
                }
            }
        })
    </script>
@endsection
