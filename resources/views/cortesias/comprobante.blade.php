@extends('layouts.app')
@section('content')
    <style>
        body {
            background-color: #B2DFDB;
        }

        .panel {
            min-height: 90vh;
        }

        .cortesia {
            background: #0277BD;
            color: #FAFAFA;
        }

        .btn {
            text-align: left;
        }

        .panelDetalles {
            position: fixed;
            right: 2px;
            bottom: 10px;
            top: 73px;
            background: #fafafa;
            width: 400px;
            border-radius: 6px;
            overflow-y: auto;
        }

        .btnDetalle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            position: fixed;
            right: 2%;
            bottom: 2%;
        }

        .d-fixed {
            position: absolute;
            top: 10;
            left: 1;
            right: 1;
            max-height: 250px;
            width: 50%;
            overflow-y: auto;
            background: #f2f2f2;
            z-index: 200;
        }
    </style>
    <div class="container" id="appCortesia">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card panel shadow p-3">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12 mb-4">

                                <h3 class="card-title text-uppercase">
                                    Crear cobro de cortesias
                                </h3>
                                <small>Seleccione las opciones y cliente para poder generar un cobro</small>
                            </div>
                            <div class="col-12" v-if="cuentas.length == 0">
                                <div class="alert alert-primary" role="alert">
                                    <h4 class="alert-heading">Información</h4>
                                    Aun no hay cortesias para generar comprobantes, esto puede ocurrir porque ya fueron
                                    facturadas todas, o aun no han sido autorizadas. Si es necesario que aparezca una o mas
                                    cuentas aquí, solicite a quien autoriza las cortesias; que realice las autorizaciones
                                    necesarias para poder generar el cobro.
                                </div>
                            </div>
                            <!--Mensajes de alerta alerta-->
                            <div class="col-12 col-lg-12">
                                <x-message></x-message>
                            </div>
                            <div class="row"
                                v-if="(cliente == null || cliente.id == null || comprobante == '' || caja == '' || !head) && cuentas.length > 0">
                                <div class="col-12 mb-3">
                                    <label for="tipo_comprobante">Tipo de comprobante</label>
                                    <select class="form-select" id="tipo_comprobante" v-model="comprobante">
                                        <option selected value="">Seleccione el tipo de comprobante a generarse
                                        </option>
                                        <option v-for='tc in tipo_comprobantes' :value="tc">
                                            @{{ tc.tipo }}
                                        </option>


                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="caja">Caja</label>
                                    <select class="form-select" aria-label="Default select example" id="caja" required
                                        v-model="caja">
                                        <option selected value="">Seleccione una caja</option>
                                        <option v-for="cj in cajas" :value="cj">@{{ (cj.caja).toUpperCase() }}</option>
                                    </select>
                                </div>
                                <div class="col-12" v-if="cliente != null">
                                    <span class="mdi mdi-close btn text-danger" @click="cliente = null"></span>
                                    @{{ cliente.cliente }}
                                </div>
                                <div class="col-12" v-if="cliente == null">
                                    <label for="" class="form-label">Buscar cliente</label>
                                    <input type="text" class="form-control" v-model='txtBuscarApi'
                                        placeholder="Buscar el cliente a quien se aplicara el comprobante"
                                        @keyup="getClientes" @keydown.up.prevent="selectItem(-1)"
                                        @keydown.down.prevent="selectItem(1)" @keydown.enter="setCliente()" />

                                    <div class="d-fixed shadow"
                                        v-if="txtBuscarApi.length > 4 && clientesList && clientesList.length > 0 && cliente == null">
                                        <div class="list-group list-group-flush">
                                            <button type="button" class="list-group-item list-group-item-action "
                                                aria-current="true" v-for="(cl, index) in clientesList"
                                                @click="setCliente(index)" :key="index"
                                                :class="{ 'active': index === selectedIndex }" tabindex="0">
                                                @{{ cl.cliente }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12"
                                    v-if="txtBuscarApi.length > 4 && clientesList && clientesList.length == 0 && cliente == null">
                                    <div class="list-group list-group-flush mt-2">
                                        No se encontró ningún registro con los parámetros de búsqueda, intente buscar con
                                        otra palabra o revise la ortografía, e intente de nuevo.
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <button class="btn btn-primary" type="button" role="button"
                                        :disabled="cliente == null || cliente.id == null || comprobante == '' || caja == ''"
                                        @click="head = true">
                                        <span class="mdi mdi-arrow-right"></span>
                                        Siguiente
                                    </button>
                                    <a class="btn btn-light" href="{{ url()->previous() }}">Volver</a>
                                </div>
                            </div>
                            <div class="col-12">
                                <form action="{{ route('cortesias.cobro') }}" method="post">
                                    @csrf

                                    <div class="row"
                                        v-if='cliente && cliente != null && cliente.id > 0 && cuentas.length > 0 && comprobante && caja && head'>
                                        <input type="hidden" name="clientes_id" v-model="cliente.id">
                                        <input type="hidden" name="cajas_id" v-model="caja.cid">
                                        <input type="hidden" name="comprobante" v-model="comprobante.ctoken">
                                        <input type="hidden" name="cuentas[]" v-for="s in selected" :value='s.id'
                                            multiple>


                                        <div class="col-12 mb-3">
                                            <div class="card text-bg-light">

                                                <div class="card-body">
                                                    <a class="btn btn-light float-end" href="#" role="button"
                                                        @click="cliente = null">
                                                        <span class="mdi mdi-close "
                                                            title="Eliminar seleccion de cliente y buscar otro."></span>
                                                    </a>
                                                    <h5 class="card-title text-uppercase">
                                                        Cliente: @{{ cliente.nombre }}
                                                    </h5>
                                                    <div class="card-text text-uppercase" v-if="cliente.identificacion">
                                                        @{{ cliente.identificacion }}: @{{ cliente.numero }}
                                                    </div>
                                                    <div class="card-text text-uppercase">
                                                        Caja: @{{ caja.caja }}
                                                    </div>
                                                    <div class="card-text text-uppercase">
                                                        Tipo de comprobante: @{{ comprobante.tipo }}
                                                    </div>
                                                    <div class="card-text" v-if="cliente.identificacion == null">
                                                        Sin identificación registrada.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <h5>Listado de cortesias</h5>
                                            <div class="mb-3">
                                                <input type="text" name="" id="" class="form-control"
                                                    placeholder="Buscar por nombre de titular o correlativo de cuenta..."
                                                    v-model='buscar' />

                                            </div>
                                        </div>

                                        <div class="col-12 text-uppercase mb-3">
                                            Seleccione las cuentas para generar el cobro.
                                        </div>
                                        <div class="col-4 mb-3" v-for="p in listCuentas">
                                            <input type="checkbox" class="btn-check" :id="p.id"
                                                autocomplete="off" :value="p" v-model='selected' multiple>
                                            <label class="btn btn-outline-primary card" :for="p.id"
                                                v-if="p.origen == 1">
                                                <div class="card-body ">
                                                    <h5 class="card-title text-truncate" :title="p.titular.titular">
                                                        @{{ p.titular.titular }}
                                                    </h5>
                                                    <p class="card-text">
                                                    <div class="col-12">
                                                        Orden No. @{{ p.detalle.orden }}
                                                    </div>
                                                    <div>
                                                        @{{ p.detalle.count_orden }}
                                                        @{{ p.detalle.count_orden > 1 ? 'conceptos agregados' : 'concepto agregado' }}
                                                        · $@{{ parseFloat(p.detalle.sum_orden).toFixed(2) }}
                                                    </div>
                                                    <div>
                                                        <span>
                                                            @{{ p.detalle.cajas.caja }}
                                                        </span>
                                                        <span class="float-end">
                                                            @{{ p.detalle.creacion }}
                                                        </span>
                                                    </div>
                                                    </p>
                                                </div>
                                            </label>
                                            <label class="btn btn-outline-primary card" :for="p.id"
                                                v-if="p.origen == 2">
                                                <div class="card-body">
                                                    <h5 class="card-title text-truncate" :title="p.titular.titular">
                                                        @{{ p.titular.titular }}
                                                    </h5>
                                                    <p class="card-text">
                                                    <div class="col-12">
                                                        Estadía Reg. @{{ p.estadia.id }}
                                                    </div>
                                                    <div>
                                                        Estadía de @{{ p.estadia.dias }}
                                                        @{{ p.estadia.dias > 1 ? 'dias' : 'dia' }}
                                                        ·
                                                        $@{{ parseFloat((p.estadia.tarifa ?? p.estadia.tarifas.precio) * p.estadia.dias).toFixed(2) }}
                                                    </div>
                                                    <div>
                                                        <span class="float-end">
                                                            @{{ p.estadia.creacion }}
                                                        </span>
                                                        <span>
                                                            Habitación @{{ p.estadia.habitaciones.numero_habitacion }}
                                                        </span>
                                                    </div>
                                                    </p>
                                                </div>
                                            </label>
                                            <label class="btn btn-outline-primary card" :for="p.id"
                                                v-if="p.origen == 3">
                                                <div class="card-body">
                                                    <h5 class="card-title text-truncate" :title="p.titular.titular">
                                                        @{{ p.titular.titular }}

                                                    </h5>
                                                    <p class="card-text">
                                                    <div class="col-12">
                                                        Comanda No. @{{ p.comanda.id }} - Mesa
                                                        #@{{ p.comanda.mesa }}
                                                    </div>
                                                    <div>
                                                        @{{ p.comanda.count_comanda }}
                                                        @{{ p.comanda.count_comanda > 1 ? 'conceptos agregados' : 'concepto agregado' }}
                                                        ·
                                                        $@{{ parseFloat(p.comanda.sum_comanda).toFixed(2) }}
                                                    </div>
                                                    <div>
                                                        <span>
                                                            @{{ p.comanda.cajas.caja }}
                                                        </span>
                                                        <span class="float-end">
                                                            @{{ p.comanda.creacion }}
                                                        </span>
                                                    </div>
                                                    </p>
                                                </div>
                                            </label>
                                        </div>


                                        <div class="form-group mt-5">
                                            <button class="btn btn-light" type="button" role="button"
                                                @click="head = false">
                                                <span class="mdi mdi-arrow-left"></span>
                                                Atrás
                                            </button>
                                            <button class="btn btn-primary" type="submit"
                                                :disabled="selected.length == 0 || selected == null">
                                                Generar cobro
                                            </button>
                                            <a class="btn btn-light" href="{{ url()->previous() }}">Volver</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <button type="button" class="btn btn-primary btnDetalle text-center" @click="detalleShow = true"
            v-show="!detalleShow">
            <span class="mdi mdi-file-document-multiple h3"></span>
        </button>
        <div class="panelDetalles shadow p-3" v-if="detalleShow && selected.length > 0">
            <div class="row">
                <div class="col-12 h4">
                    <span class="mdi mdi-minus float-end btn" @click="detalleShow = false"></span>
                    DETALLES
                </div>
                <div class="col-12 table-responsive">
                    <table class="table table-light">
                        <thead class="thead-light">
                            <tr>
                                <th>Cant.</th>
                                <th>Concepto</th>
                                <th class="text-right">Unit.</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody v-if="selectedData">
                            <tr v-for="(detalle, index) in selectedData" :key="index">
                                <td>@{{ detalle.cantidad }}</td>
                                <td>@{{ detalle.concepto }}</td>
                                <td class="text-right">$@{{ detalle.unitario }}</td>
                                <td class="text-right">$@{{ detalle.total }}</td>
                            </tr>
                        </tbody>
                        <tfoot v-if="selectedData">
                            <tr>
                                <th colspan="3">
                                    Total
                                    <span class="float-end">$</span>
                                </th>
                                <th class="text-right"> @{{ getTotal(selectedData) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        var app = new Vue({
            el: '#appCortesia',
            data: {
                detalleShow: true,
                cuentas: @json($cuentas),
                selected: [],
                titular: '',
                buscar: '',
                cliente: null,
                txtBuscarApi: '',
                clientesList: [],
                selectedIndex: 0,
                tipo_comprobantes: @json($tipo_comprobantes),
                cajas: @json($cajas),
                comprobante: '',
                caja: '',
                head: false,
            },
            methods: {
                getTotal: function(obj) {
                    let total = 0;
                    console.log(obj);
                    if (obj && obj.length > 0)
                        total = obj.reduce((previous, current) => {
                            console.log(current);
                            return previous + parseFloat(current.total);
                        }, total);

                    return total;
                },
                getClientes: function(e) {
                    const codTecla = e.keyCode || e.which;
                    const tecla = e.key;
                    console.log(tecla)

                    if (this.txtBuscarApi.length > 4 && (![38, 40, 13].includes(codTecla) && tecla !==
                            'ArrowUp' && tecla !== 'ArrowDown' && tecla !== 'Enter'))
                        axios.post("{{ route('clientes.api_search_list') }}", {
                            busqueda: this.txtBuscarApi,
                        }).then((rs) => {
                            console.log(rs);
                            if (rs.data.list)
                                this.clientesList = rs.data.list;
                            else
                                this.clientesList = [];



                        }).catch((error) => {
                            console.log(error);

                        });

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
                    if (index != null)
                        this.selectedIndex = index;

                    this.cliente = this.clientesList[this.selectedIndex];
                    this.clientesList = [];
                    this.txtBuscarApi = '';
                }
            },
            mounted() {
                console.log(this.cuentas);
            },
            computed: {
                listCuentas: function() {
                    if (this.buscar.length > 0) {
                        let reg = new RegExp(this.buscar, 'i');
                        return this.cuentas.filter(c => reg.test(c.titular.titular) ||
                            reg.test(c.cuenta) ||
                            reg.test(c.origen_id)
                        );
                    } else return this.cuentas;
                },
                selectedData: function() {
                    let detalle = [];
                    this.selected.map(function(d) {
                        switch (d.origen) {
                            case 1:
                                d.detalle.detalle_orden.map(p => {
                                    detalle.push({
                                        cantidad: p.cantidad,
                                        concepto: p.servicios.servicio ??
                                            'No se encontró el servicio',
                                        unitario: parseFloat(p.precio_unitario).toFixed(
                                            2),
                                        total: parseFloat(p.cantidad *
                                            p.precio_unitario).toFixed(2),
                                    })
                                });

                                break;
                            case 2:
                                detalle.push({
                                    cantidad: d.detalle.dias,
                                    concepto: d.detalle.tarifas.tarifa ??
                                        'No se encontró la tarifa',
                                    unitario: parseFloat(d.detalle.tarifa ?? d.detalle.tarifas
                                            .precio)
                                        .toFixed(2),
                                    total: parseFloat(d.detalle.dias *
                                            (d.detalle.tarifa ?? d.detalle.tarifas.precio))
                                        .toFixed(2),
                                });

                                break;
                            case 3:
                                d.detalle.detalles_comanda.map(p => {
                                    console.log(p);
                                    detalle.push({
                                        cantidad: p.cantidad,
                                        concepto: p.precios.detalle ??
                                            'No se encontró el precio',
                                        unitario: parseFloat(p.precio).toFixed(
                                            2),
                                        total: parseFloat(p.cantidad *
                                            p.precio).toFixed(2),
                                    })
                                });
                                break;
                        }
                    });
                    return detalle;
                }
            }
        });
    </script>
@endsection
