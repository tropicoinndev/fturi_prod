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
                                    Editar precios de cortesias
                                </h3>
                                <small>Seleccione una o mas cuentas para editar el precio</small>
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
                        </div>

                        <div class="row" v-if='cuentas.length'>
                            <h5>Listado de cortesias</h5>
                            <div class="col-6 mb-3">
                                <label for="buscar">Buscar</label>
                                <input type="text" id="buscar" class="form-control"
                                    placeholder="Buscar por nombre de titular o correlativo de cuenta..."
                                    v-model='buscar' />


                            </div>
                            <div class="col-6 mb-3">
                                <label for="tipo_cortesias">Tipo de cortesía (@{{ tipo }})</label>
                                <select class="form-select" id="tipo_cortesias" v-model="tipo">
                                    <option selected value="">TODOS</option>
                                    <option v-for='tc in tipo_cortesias' :value="tc.id" class="text-uppercase">
                                        @{{ tc.tipo }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 text-uppercase mb-3">
                                Seleccione las cuentas para generar el cobro.
                            </div>
                            <div class="col-4 mb-3" v-for="p in listCuentas">
                                <input type="checkbox" class="btn-check" :id="p.id" autocomplete="off"
                                    :value="p" v-model='selected' multiple>
                                <label class="btn btn-outline-primary card" :for="p.id" v-if="p.origen == 1">
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
                                <label class="btn btn-outline-primary card" :for="p.id" v-if="p.origen == 2">
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
                                            $@{{ parseFloat(p.estadia.tarifas.precio * p.estadia.dias).toFixed(2) }}
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
                                <label class="btn btn-outline-primary card" :for="p.id" v-if="p.origen == 3">
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

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary" data-bs-target="#modalEditarPrecios"
                                    data-bs-toggle="modal">Editar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="modalEditarPrecios" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Editar precios
                        </h5>
                        
                    </div>
                    <div class="modal-body row">
                        <div class="col-12 table-responsive">
                            <table class="table table-light table-borderless">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Cant.</th>
                                        <th>Concepto</th>
                                        <th class="text-right">Unitario</th>

                                    </tr>
                                </thead>
                                <tbody v-if="selectedData">
                                    <tr v-for="(detalle, index) in selectedData" :key="index">
                                        <td>@{{ detalle.cantidad }}</td>
                                        <td>@{{ detalle.concepto }}</td>
                                        <td class="text-right">
                                            <precios :url="detalle.ruta"
                                                :data="{
                                                    id: detalle.id,
                                                    precio: detalle.unitario,
                                                    propina: detalle.propina,
                                                    req: detalle.req
                                                }">
                                            </precios>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="reload()">
                            Cerrar y actualizar
                        </button>
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

    <script type="module">
        var app = window.appVue({
            data() {
                return {
                    detalleShow: true,
                    cuentas: @json($cuentas),
                    selected: [],
                    titular: '',
                    buscar: '',
                    selectedIndex: 0,
                    tipo_cortesias: @json($tipos_cortesias),
                    tipo: '',
                    route: {
                        comanda: "{{ route('comandas.precios') }}",
                        orden: "{{ route('ordenes.precios') }}",
                        recepcion: "{{ route('recepciones.precios') }}",
                    },
                };
            },
            methods: {
                getTotal: function(obj) {
                    let total = 0;
                    if (obj && obj.length > 0)
                        total = obj.reduce((previous, current) => {
                            return previous + parseFloat(current.total);
                        }, total);

                    return total;
                },
                reload() {
                    window.location.reload()
                }
            },
            mounted() {},
            computed: {
                listCuentas: function() {

                    let reg = new RegExp(this.buscar, 'i');
                    return this.cuentas.filter(c => (reg.test(c.titular.titular) ||
                            reg.test(c.cuenta) ||
                            reg.test(c.origen_id)) &&
                        (this.tipo == "" || this.tipo == c.titular.tipo_cortesias_id));

                },
                selectedData: function() {
                    const detalle = [];
                    this.selected.map((d) => {
                        switch (d.origen) {
                            case 1:
                                d.detalle.detalle_orden.map(p => {
                                    detalle.push({
                                        id: p.cid,
                                        cantidad: p.cantidad,
                                        concepto: p.servicios.servicio ??
                                            'No se encontró el servicio',
                                        unitario: parseFloat(p.precio_unitario).toFixed(
                                            2),
                                        total: parseFloat(p.cantidad *
                                            p.precio_unitario).toFixed(2),
                                        ruta: this.route.orden,
                                        propina: p.propina > 0,
                                        req: true,
                                    })
                                });

                                break;
                            case 2:
                                console.log(d.detalle.tarifa);
                                detalle.push({
                                    id: d.detalle.cid,
                                    cantidad: d.detalle.dias,
                                    concepto: d.detalle.tarifas.tarifa ??
                                        'No se encontró la tarifa',
                                    unitario: parseFloat(d.detalle.tarifa ?? d.detalle.tarifas
                                            .precio)
                                        .toFixed(2),
                                    total: parseFloat(d.detalle.dias *
                                            (d.detalle.tarifa ?? d.detalle.tarifas.precio))
                                        .toFixed(2),
                                    ruta: this.route.recepcion,
                                    req: false,
                                });

                                break;
                            case 3:
                                d.detalle.detalles_comanda.map(p => {

                                    detalle.push({
                                        id: p.cid,
                                        cantidad: p.cantidad,
                                        concepto: p.precios.detalle ??
                                            'No se encontró el precio',
                                        unitario: parseFloat(p.precio).toFixed(
                                            2),
                                        total: parseFloat(p.cantidad *
                                            p.precio).toFixed(2),
                                        ruta: this.route.comanda,
                                        propina: p.propina,
                                        req: true,
                                    })
                                });
                                break;
                        }
                    });
                    return detalle;
                }
            }
        });
        app.component('precios', component.precios);
        app.mount("#appCortesia");
    </script>
@endsection
