@extends('layouts.cajas')
@section('panel_caja')
    @if (!$p->estado)
        <script>
            window.location = "/ordenes";
        </script>
    @else
        <style>
            .message {
                position: fixed;
                top: 20%;
                right: 1%;
                z-index: 100;
            }
        </style>
        <div id="appOrdenes">
            <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                v-show="message.message && message.type">
                <strong>@{{ message.message }}</strong>
            </div>
            <div class="container">
                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="card shadow bg-body rounded">
                            <h5 class="card-header text-bg-primary text-center">Agregar desde concepto</h5>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-4">
                                        <div class="row">
                                            <div class="col-3">Titular</div>

                                            <div class="col-10 d-flex justify-content-start">
                                                @if (isset($p->clientes_id) && $p->clientes_id != null)
                                                    <!-- Cliente seleccionado -->
                                                    <div class="titular ">
                                                        {{ $p->clientes->nombre }}
                                                    </div>
                                                @else
                                                    <!-- Cliente no seleccionado -->
                                                    <div class="col-10 d-flex justify-content-start">
                                                        {{ $p->titular }}
                                                    </div>
                                                @endif

                                                <!-- Botón de edición (se mostrará siempre que haya un titular) -->
                                                <button type="button" class="btn btn-light btn-sm ms-3"
                                                    title="Editar cliente" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarTitular">
                                                    <span class="mdi mdi-pencil"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-3">

                                        <span> No. </span>
                                        <small class="ms-1 rounded-pill bg-light text-muted p-1">
                                            @{{ orden }}
                                        </small>

                                    </div>

                                    <div class="col-5" v-if="!ordenValid.facturada">

                                        @can('ordenes.comprobante')
                                            <form action="{{ route('ordenes.comprobante') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $p->id }}">
                                                <button type="submit" class="btn btn-outline-primary float-end">
                                                    Orden completa
                                                </button>
                                            </form>
                                        @endcan

                                        <a class="btn btn-outline-secondary" href="javascript:void(0);"
                                            data-bs-toggle="modal" data-bs-target="#modalEditarObservacion">Editar
                                            observación</a>

                                        <button type="button" class="btn btn-outline-success me-2 float-end"
                                            data-bs-toggle="modal" data-bs-target="#modalAgregarCliente">
                                            {{ $p->clientes_id != null ? 'Editar cliente' : 'Agregar cliente' }}
                                        </button>
                                    </div>
                                </div>


                                <table class="table" v-if="!ordenValid.facturada">
                                    <thead>
                                        <tr>
                                            <th scope="row">Concepto</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Precio</th>
                                            <th scope="col">Total</th>
                                            <th scope="col" style="text-align: center;">Info</th>
                                            <th scope="col" style="text-align: center;">Descuento</th>
                                            <th scope="col" style="text-align: center;">Venta Total</th>
                                            <th scope="col" style="text-align: center;">Agregar | Limpiar</th>
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
                                                    @{{ serviciosSelected.servicio }}
                                                    <span class=" btn  mdi mdi-close" @click="limpiar"
                                                        title="Quitar seleccion"></span>
                                                </div>

                                            </td>
                                            <td style="width: 8%;">
                                                <input type="number" class="form-control" placeholder="0" min="1"
                                                    id="cantidad" name="cantidad" v-model="cantidad"
                                                    @keyup="calcularTotal()" @change="calcularTotal()" @input="Decimal"
                                                    :disabled="serviciosSelected.length == 0">
                                            </td>
                                            <td style="width: 9%;">
                                                <input type="number" class="form-control" placeholder="0.00" step="any"
                                                    id="precio" name="precio"
                                                    :min="this.serviciosSelected?.precio_unitario" v-model="precio"
                                                    @change="calcularTotal()" :disabled="serviciosSelected.length == 0">
                                            </td>
                                            <td style="width: 9%;">
                                                <input type="number" class="form-control" placeholder="0.00" min="0"
                                                    step="any" disabled id="total" name="total" v-model="total">
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
                                                                <label class="form-check-label" for="checkIva">IVA</label>
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
                                                                    id="checkPropina" :checked="estadoPropina" disabled>
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
                                <div class="result shadow" v-if="!ordenValid.facturada"
                                    style="position: absolute; z-index: 10; margin-top: -20px; margin-left: 9px; width: 29.0em; background: white; padding: 2px;">

                                    <!-- Mostrar lista de servicios si hay servicios disponibles -->
                                    <ul v-if="servicios.length > 0" class="list-group">
                                        <li v-for="servicio in servicios"
                                            class="list-group-item d-flex justify-content-between align-items-center"
                                            style="border-radius: 0px; border: none; cursor:pointer;"
                                            @click="selected(servicio)">
                                            @{{ servicio.servicio }}
                                            <span class="badge bg-primary rounded-pill">$ @{{ servicio.precio_unitario | decimales }}</span>
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
                                                <th scope="col" style="text-align: right;">P. Unitario</th>
                                                <th scope="col" style="text-align: right;">P. Total</th>
                                                <th scope="col" style="text-align: center;">Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="listaDetalle in detalleOrdenes">
                                                <td class="font-monospace">@{{ listaDetalle.cantidad }}</td>
                                                <td style="width:30%;">@{{ listaDetalle.servicios.servicio }}</td>
                                                <td><span class="float-end font-monospace">$ @{{ getVenta(listaDetalle) | decimales }}</span>
                                                </td>
                                                <td><span class="float-end font-monospace">$ @{{ (getVenta(listaDetalle) * listaDetalle.cantidad) | decimales }}</span>
                                                </td>
                                                <td style="text-align: center;"v-if="!ordenValid.facturada ">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                                        @click="eliminarDetalleOrden(listaDetalle.id, listaDetalle.servicios.servicio)">
                                                        <span class="mdi mdi-trash-can"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr class="border-1 border-bottom border-secondary"></tr>
                                            <tr class="font-monospace">
                                                <td colspan="2"></td>
                                                <td><b class="float-end">Sub-total</b></td>
                                                <td><span class="float-end text-secondary">$ @{{ getSubTotal() | decimales }}</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr class="font-monospace">
                                                <td colspan="2"></td>
                                                <td><b class="float-end">IVA</b></td>
                                                <td><span class="float-end text-secondary">$ @{{ getIVA() | decimales }}</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr class="font-monospace">
                                                <td colspan="2"></td>
                                                <td><b class="float-end">CESC</b></td>
                                                <td><span class="float-end text-secondary">$@{{ getCESC() | decimales }}</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr class="font-monospace">
                                                <td colspan="2"></td>
                                                <td><b class="float-end">Ad-valorem (+)</b></td>
                                                <td><span class="float-end text-secondary">$ @{{ getAdvalorem() | decimales }}</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr class="font-monospace">
                                                <td colspan="2"></td>
                                                <td><b class="float-end">Propina (+)</b></td>
                                                <td><span class="float-end text-secondary">$ @{{ getPropina() | decimales }}</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr class="table-light font-monospace">
                                                <td colspan="2"></td>
                                                <td><b class="float-end">TOTAL</b></td>
                                                <td><b><span class="float-end" style="font-size: 17px;">$
                                                            @{{ (getSubTotal() + getIVA() + getCESC() + getAdvalorem() + getPropina()) | decimales }}</span></b></td>{{-- getTOTAL() --}}
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

            <!-- Modal editar observación-->
            <div class="modal fade" id="modalEditarObservacion" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('ordenes.editarDescripcion') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar observación</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <input type="hidden" name="ordenId" value="{{ $p->id }}">

                                    <label class="form-label" for="descripcion">Observaciones: (opcional)</label>
                                    <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="3"
                                        placeholder="Escriba una observación para ésta orden">{{ $p->descripcion }}</textarea>
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

            <!-- Modal eliminar-->
            <div class="modal fade" id="modalEliminar" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('detalle_ordenes.delete') }}" method="POST">
                            @csrf

                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Esta seguro de eliminar este
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

            <!-- Modal agregar cliente-->
            <div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Editar cliente</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('ordenes.update') }}" method="POST">
                                @csrf

                                <input type="hidden" name="id_orden" :value="orden">
                                <input type="hidden" name="clientes_id" :value="clientesSelected.id">

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="titular" class="form-label">Cliente:</label>

                                        <div v-if="clientesSelected.id >= 0"> @{{ clientesSelected.titular }}</div>

                                        <div class="input-group mb-3" v-if="!(clientesSelected.id >= 0)">
                                            <input type="text" class="form-control" id="titular" name="clientes_id"
                                                autocomplete="off" placeholder="Buscar cliente..." v-model="txtCliente"
                                                @keyup="getClientes">

                                            <span class="input-group-text" id="basic-addon2">
                                                <template v-if="clientes.length > 0">Hay clientes</template>
                                                <template v-else>
                                                    <a href="{{ route('clientes.create') }}" target="_blank"
                                                        style="text-decoration: none;">Crear cliente</a>
                                                </template>
                                            </span>
                                        </div>

                                        <div class="result shadow"
                                            style="position: absolute;z-index: 10;margin-top: 0px;margin-left: 0px; width: 32.3em; background:white; padding:2px;"
                                            v-if="clientes.length > 0">
                                            <ul class="list-group" v-for="cliente in clientes">
                                                <li class="list-group-item d-flex justify-content-between align-items-center"
                                                    style="border-radius: 0px; border: none; cursor:pointer;"
                                                    @click="selectedClientes(cliente)">@{{ cliente.nombre }} -
                                                    @{{ cliente.identificacion }} - @{{ cliente.numero }}
                                                    <small>
                                                        <span v-if="cliente.tipo_cliente"
                                                            class="badge bg-primary rounded-pill">Natural</span>
                                                        <span v-else
                                                            class="badge bg-secondary rounded-pill">Juridico</span>
                                                    </small>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary"
                                        :disabled="clientesSelected.length == 0">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal editar titular-->
            <div class="modal fade" id="modalEditarTitular" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Editar titular</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('ordenes.updateTitular') }}" method="POST">
                                @csrf

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="titular" class="form-label">Titular:</label>
                                        <input type="hidden" name="id_orden" :value="orden">
                                        <input type="text" class="form-control" placeholder="Ingrese titular"
                                            name="titular" :value="txtTitular">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--End div appOrdenes-->
    @endif

    <script>
        var app = new Vue({
            el: '#appOrdenes',
            data: {
                txtBusqueda: '',
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

                //Cliente.
                txtCliente: '',
                clientes: [],
                clientesSelected: [],

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
                    // Actualizar el valor de `cantidad`
                    this.cantidad = event.target.value;
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
                    if (parseFloat(this.precio) < parseFloat(this.serviciosSelected.precio_unitario)) {
                        alert("El precio debe ser mayor a: $" + this.serviciosSelected.precio_unitario)
                    }

                    this.total = ((this.precio * this.cantidad)).toFixed(2);
                    this.ventaTotal = this.total;
                    //                    }
                },
                agregarDetalleOrden() {
                    if (parseFloat(this.precio) < parseFloat(this.serviciosSelected.precio_unitario)) {
                        alert("El precio debe ser mayor a: $" + this.serviciosSelected.precio_unitario)
                    } else
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
                AddTitular() {
                    this.clientesSelected = {
                        id: 0,
                        titular: this.txtCliente
                    }
                },
                getClientes() {
                    if (this.txtCliente.length > 2) {
                        //this.txtCliente = this.txtTitular;

                        axios.post("{{ route('clientes.api_search') }}", {
                                busqueda: (this.txtCliente).toUpperCase(),
                            })
                            .then((resp) => {
                                console.log(resp);
                                this.clientes = resp.data.clientes || [];
                            })
                            .catch(error => {
                                console.log(error);
                            });
                    }
                },
                selectedClientes(s) {
                    this.clientesSelected = {
                        id: s.id,
                        titular: s.nombre
                    };
                    this.clientes = [];
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
                        const precioUnitario = v.precio_unitario || 0;
                        //const subtotalItem = cantidad * precioUnitario;
                        const subtotalItem = this.getSubTotal() + this.getIVA() + this.getCESC() + this
                            .getAdvalorem() + this.getPropina();
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
