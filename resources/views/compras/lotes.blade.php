@extends('layouts.app')

@section('content')
    {{-- @if ($p->estado)
    <script>
        window.location = '/compras';
    </script>
@else --}}

    <style>
        .message {
            position: fixed;
            top: 20%;
            right: 1%;
            width: 15%;
            z-index: 100;
        }
    </style>

    <div id="appCompras">

        <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
            v-show="message.message && message.type">
            <strong>@{{ message.message }}</strong>
        </div>

        <div class="container">
            <div class="row mb-5">
                <div class="col-md-2 mb-2">
                    <a type="button" class="btn btn-light text-end" href="{{route('compras.index')}}"
                                                >Regresar a compras</a>
                </div>
                <div class="col-md-12">

                    <div class="card shadow-lg bg-body rounded">
                        <h5 class="card-header text-bg-primary text-center">Agregar desde producto</h5>
                        <div class="card-body">

                            <!--Mensajes de alerta-->
                            <div class="col-6">
                                <x-message></x-message>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <span>Proveedor: </span>
                                    <span class="ms-1 rounded-pill bg-light text-muted p-1">
                                        @{{ arrayCompras.relacion_proveedores.proveedor }}
                                    </span>
                                </div>

                                <div class="col-md-2">
                                    <span>Tipo pago: </span>
                                    <small class="ms-1 rounded-pill bg-light text-muted p-1">
                                        @{{ arrayCompras.relacion_tipo_pagos.tipo_pago }}
                                    </small>
                                </div>

                                <div class="col-md-2">
                                    <span>Fecha: </span>
                                    <small class="ms-1 rounded-pill bg-light text-muted p-1">
                                        @{{arrayCompras.formateada}}
                                    </small>
                                </div>

                                <div class="col-md-2">
                                    <span>Tipo de documento: </span>
                                    <small class="ms-1 rounded-pill bg-light text-muted p-1">
                                        CCF
                                    </small>
                                </div>

                                <div class="col-md-2">
                                    <span>Clase: </span>
                                    <small class="ms-1 rounded-pill bg-light text-muted p-1">
                                        AAA
                                    </small>
                                </div>


                            </div>
                            <table v-if="!arrayCompras.completado" class="table">
                                <tbody>
                                    <tr>
                                        <!--  (Concepto) -->
                                        <td class="col-md-3">
                                            <div v-show="!productosSelected.id">
                                                <label for="producto">Concepto</label>
                                                <input type="text" class="form-control mb-4"
                                                    placeholder="Buscar producto..." autocomplete="off" id="producto"
                                                    name="producto" v-model="txtBusqueda" @keyup="apiSearchProductos"
                                                    :disabled="productosSelected.length > 0 || arrayCompras.completado">
                                            </div>
                                            <div class="active" v-show="productosSelected.id > 0">
                                                Concepto: @{{ productosSelected.nombre }}
                                                <span class="btn mdi mdi-close" @click="limpiar"
                                                    title="Quitar seleccion"></span>
                                            </div>
                                            <div v-if="arrayProductos.length > 0" class="result shadow"
                                                style="position: absolute;z-index: 10;margin-top: -20px;margin-left: 9px;width: 20.0em; background:white; padding:2px;">

                                                <ul class="list-group">
                                                    <li v-for="producto in arrayProductos"
                                                        class="list-group-item d-flex justify-content-between align-items-center"
                                                        style="border-radius: 0px; border: none; cursor:pointer;"
                                                        @click="selected(producto)">@{{ producto.nombre }}
                                                        <!-- <span class="badge bg-primary rounded-pill">$ 0.00</span> -->
                                                    </li>

                                                </ul>
                                            </div>
                                            <div v-if="arrayProductos.length ===0" class="result shadow">
                                                @{{ mensaje }}
                                            </div>
                                        </td>

                                        <!-- dalles y impuestos -->
                                        <td class="col-md-7">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="cantidad">Cantidad</label>
                                                    <input type="number" class="form-control" placeholder="0"
                                                        min="1" id="cantidad" name="cantidad" v-model="cantidad"
                                                        @keyup="calcularTotal" @change="calcularTotal"
                                                        :disabled="productosSelected.length == 0">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="precio">Precio Neto</label>
                                                    <input type="number" class="form-control" placeholder="0.00"
                                                        step="any" id="precio" name="precio" v-model="precio"
                                                        @keyup="calcularTotal" @change="calcularTotal"
                                                        :disabled="productosSelected.length == 0">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="total">subTotal</label>
                                                    <input type="number" class="form-control" placeholder="0.00"
                                                        step="any" id="total" name="total" v-model="compraTotal"
                                                        :disabled="productosSelected.length == 0">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="fecha_vencimiento">Fecha Vencimiento</label>
                                                    <input type="date"
                                                        class="form-control" id="fecha_vencimiento" name="fecha_vencimiento"
                                                        v-model="fecha_vencimiento"
                                                        :disabled="!productosSelected.id || !productosSelected.vencimiento "
                                                        :min="fechaActual":disabled-dates="d => d < new Date()">
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="iva">Iva</label>
                                                    <div class="form-control" style="display: flex; align-items: center;">
                                                        <input type="checkbox" id="iva" v-model="iva"
                                                            @change="calcularTotal" style="width: 20px; height: 20px;">
                                                        <input type="number" class="form-control" step="any"
                                                            id="iva" name="iva" v-model="calcularIva" readonly
                                                            style="border: none; background-color: transparent; font-size: 16px;">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="percepcion">Percepción</label>
                                                    <div class="form-control" style="display: flex; align-items: center;">
                                                        <input type="checkbox" id="percepcion" v-model="percepcion"
                                                            @change="calcularTotal" style="width: 20px; height: 20px;">
                                                        <input type="number" class="form-control" placeholder="0.32"
                                                            step="any" id="percepcion" name="percepcion"
                                                            v-model="calcularPercepcion" readonly
                                                            style="border: none; background-color: transparent; font-size: 16px;">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="cantidad">Compra total</label>
                                                    <span class="form-control" style="font-size: 17px;"><b>$
                                                            @{{ totalConImpuestos }}</b></span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- agrgar y limpiar -->
                                        <td class="col-md-2 text-start ">
                                            <label for="agregar">Agregar | Limpiar</label>
                                            <button type="button" class="btn btn-outline-success"
                                                style="margin-right: 5px;" @click="apiStoreLote()"
                                                :disabled="productosSelected.length == 0">Agregar</button>
                                            <button type="button" class="btn btn-outline-secondary" @click="limpiar"
                                                :disabled="productosSelected.length == 0">Limpiar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!--End row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg bg-body rounded">
                        <h5 class="card-header text-bg-success text-center">Detalle de lote</h5>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-10">
                                    <div v-if="!arrayCompras.relacion_proveedores.proveedor" class="alert alert-warning"
                                        role="alert">
                                        *No se mostraran los impuestos hasta que agregue un proveedor.
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="!arrayCompras.completado">
                                    <button type="button" class="btn btn-outline-primary mt-2 mb-2"
                                        @click="apiEstadoCompletado">Factura completada</button>
                                </div>
                            </div>


                            <div class="table-responsive">
                                <table class="table table-sm table align-middle user-select-none">
                                    <thead>
                                        <tr>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col" style="width:30%;">Concepto</th>
                                            <th scope="col" style="text-align: right;">F. Vencimiento</th>
                                            <th scope="col" style="text-align: right;">P. Unitario</th>
                                            <th scope="col" style="text-align: right;">P. Total</th>
                                            <th scope="col" style="text-align: center;">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="listaLote in arrayLotes">
                                            <td class="font-monospace">@{{ listaLote.cantidad }}</td>
                                            <td style="width: 30%;">@{{ listaLote.relacion_productos.nombre }}</td>
                                            <td>
                                                <span class="float-end font-monospace ">@{{ listaLote.fecha_vencimiento ?? 'No vence' }}</span>
                                            </td>
                                            <td>
                                                <span class="float-end font-monospace">$ @{{ listaLote.precio | decimales }}</span>
                                            </td>

                                            <td>
                                                <span class="float-end font-monospace">$ @{{ (listaLote.cantidad * listaLote.precio) | decimales }}</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    :disabled="arrayLotes.length == 0" data-bs-toggle="modal"
                                                    data-bs-target="#modalEliminar"
                                                    @click="eliminarLote(listaLote.id, listaLote.relacion_productos.nombre)"v-if="!arrayCompras.completado">
                                                    <span class="mdi mdi-trash-can"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="border-1 border-bottom border-secondary"></tr>
                                        <tr class="font-monospace">
                                            <td colspan="3"></td>
                                            <td><b class="float-end">Sub-total</b></td>
                                            <td>
                                                <span class="float-end text-secondary">$ @{{ getSubTotal() | decimales }}</span>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="font-monospace">
                                            <td colspan="3"></td>
                                            <td><b class="float-end">IVA</b></td>
                                            <td colspan="1">
                                                <span class="float-end text-secondary">$ @{{ getIvaTotal | decimales }}</span>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="font-monospace">
                                            <td colspan="3"></td>
                                            <td><b class="float-end">Fovial</b></td>
                                            <td colspan="1">
                                                <span class="float-end text-secondary">$ @{{ arrayCompras.fovial | decimales }}</span>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="font-monospace">
                                            <td colspan="3"></td>
                                            <td><b class="float-end">Retencion</b></td>
                                            <td colspan="1">
                                                <span class="float-end text-secondary">$ @{{ getRetencionTotal | decimales }}</span>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="table-light font-monospace">
                                            <td colspan="3"></td>
                                            <td><b class="float-end">TOTAL</b></td>
                                            <td colspan="1">
                                                <b><span class="float-end" style="font-size: 17px;">$
                                                        @{{ getTotal | decimales }}</span></b>
                                            </td>
                                            <td></td>
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

        <!-- Modal eliminar-->
        <div class="modal fade" id="modalEliminar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('lotes.delete') }}" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Esta seguro de eliminar este producto?
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="idEliminarLote" :value="idEliminarLote">
                            <div class="mt-3 mb-3">
                                <ul>
                                    <li>@{{ registroAEliminar }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Si, Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div><!--End div appCompras-->
    {{-- @endif --}}

    <script>
        var app = new Vue({
            el: '#appCompras',
            data: {
                txtBusqueda: '',
                arrayProductos: [],
                compra: "{{ $p->id }}", //Id de la compra.
                arrayCompras: @json($p),
                cantidad: 1,
                precio: 0.00,
                compraTotal: 0.00,
                productosSelected: [],
                arrayLotes: @json($lotes),
                idEliminarLote: null,
                registroAEliminar: '',
                fecha_vencimiento: null,
                iva: true,
                percepcion: 0.00,
                message: {},
                mensaje: '',
                totalConImpuestos: 0.00,
                fechaActual: null
            },
            mounted() {
                /**tomo la fecha actual */
                const fechaA = new Date();

                /**le sumo 30 dias para que sea un minimo para poder asignar vencimiento a productos */
                const fechaVencimiento = new Date(fechaA.setDate(fechaA.getDate() + 30));

                // Formatear la fecha para asignarla a fechaActual
                this.fechaActual = fechaVencimiento.toISOString().split('T')[0];

            },
            computed: {
                getTotal: function() {
                    return this.arrayLotes.reduce((acumulador, lote) => acumulador + parseFloat(lote.total ||
                        0), 0);
                },
                getRetencionTotal: function() {
                    return this.arrayLotes.reduce((acumulador, lote) => acumulador + parseFloat(lote
                        .retencion || 0), 0);
                },
                getIvaTotal: function() {
                    return this.arrayLotes.reduce((acumulador, lote) => acumulador + parseFloat(lote.iva || 0),
                        0);
                },
                calcularIva: function() {
                    const subTotal = ((this.cantidad * this.precio).toFixed(2));
                    return this.iva ? (subTotal * 0.13).toFixed(2) : "0.00";
                },
                calcularPercepcion: function() {
                    const subTotal = (this.cantidad * this.precio).toFixed(2);
                    return this.percepcion ? (subTotal * 0.01).toFixed(2) : "0.00";
                },

            },
            methods: {
                async apiSearchProductos() {
                    if (this.debouncedSearch) {
                        clearTimeout(this.debouncedSearch);
                    }
                    this.debouncedSearch = setTimeout(async () => {
                        if (this.txtBusqueda.trim().length > 4) {
                            try {
                                const response = await axios.post(
                                    "{{ route('productos.apiSearchProductos') }}", {
                                        txtBusqueda: this.txtBusqueda,
                                        idCompra: this.compra,
                                    });

                                this.arrayProductos = response.data.productos || [];
                                this.mensaje = response.data.mensaje || '';
                            } catch (error) {
                                console.log(error);
                            }
                        }
                    }, 100);
                },

                calcularTotal() {
                    if (parseFloat(this.cantidad) <= 0) {

                        this.setMessage('La cantidad debe ser mayor a 0.', 'danger');
                        return;
                    }
                    this.compraTotal = (this.cantidad * this.precio).toFixed(2);
                    const totalSinImpuestos = parseFloat(this.compraTotal);
                    const iva = this.iva ? (totalSinImpuestos * 0.13) : 0.00;
                    const percepcion = this.percepcion ? (totalSinImpuestos * 0.01) : 0.00;
                    this.totalConImpuestos = (totalSinImpuestos + iva + percepcion).toFixed(2);
                },
                selected(s) {
                    this.productosSelected = s;
                    this.arrayProductos = [];
                    this.txtBusqueda = this.productosSelected.nombre;

                },

                apiStoreLote() {
                    if (parseFloat(this.precio) <= 0 || parseFloat(this.cantidad) <= 0) {
                        this.setMessage('El precio debe ser mayor a 0.', 'danger');
                        return;
                    }
                    let retencion = 0.00;
                    let ivaT = 0.00;
                    if (this.percepcion) {
                        // Solo calcula la retención si la percepción está seleccionada
                        retencion = ((this.cantidad * this.precio).toFixed(2) * 0.01).toFixed(2);
                    }
                    if (this.iva) {
                        // Solo calcula iva está seleccionada
                        ivaT = ((this.cantidad * this.precio).toFixed(2) * 0.13).toFixed(2);
                    }
                    axios.post("{{ route('lotes.store') }}", {
                            compras_id: this.compra,
                            cantidad: this.cantidad,
                            precio: this.precio,
                            iva: ivaT,
                            total: this.totalConImpuestos,
                            retencion: retencion,
                            productos_id: this.productosSelected.id,
                            fecha_vencimiento: this.fecha_vencimiento,
                        })
                        .then((rs) => {
                            if (rs.data.type === 'success') {
                                this.arrayLotes = rs.data.lotes;
                                this.setMessage('Producto agregado al detalle de lote.', 'success');
                                location.reload();
                                this.limpiar();
                            } else {
                                console.log('Error: ', rs.data.msj);
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        })
                },
                apiEstadoCompletado() {
                    axios.post("{{ route('compras.api_estado_completado') }}", {
                            id: this.compra,
                        })
                        .then((rs) => {
                            if (rs.data.type === 'success') {
                                this.setMessage('Compra completada exitosamente', 'success');
                                const redirectUrl = rs.data.redirect_url;
                                if (redirectUrl) {
                                    window.location.href = redirectUrl;
                                } else {
                                    this.setMessage('Compra no completada exitosamente', 'danger');
                                }

                            } else {
                                this.setMessage(rs.data.msj, rs.data.type);
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        })
                },
                limpiar() {
                    this.txtBusqueda = '';
                    this.cantidad = 1;
                    this.precio = 0.00;
                    this.fovial = 0.00;
                    this.percepcion = 0.00;
                    this.iva = 0.00;
                    this.compraTotal = 0.00;
                    this.fecha_vencimiento = '';
                    this.productosSelected = [];
                },

                getCompra(d) {
                    return parseFloat(d.precio * d.cantidad);

                },
                getSubTotal() {
                    var i = 0;
                    var suma = this.arrayLotes.reduce((acumulador, v) => acumulador + this.getCompra(v), i);
                    return suma;
                },
                eliminarLote(id, selectedDelete) {
                    this.idEliminarLote = id;
                    this.registroAEliminar = selectedDelete;
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

            },
            filters: {
                decimales: function(value) {
                    var deci = 2;
                    if (!value) return 0.00;
                    return parseFloat(value).toFixed(deci);
                }
            }

        });
    </script>
@endsection
