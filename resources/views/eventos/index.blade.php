@extends('layouts.app')
@section('style')
    <style>
        body {
            background-color: #E0F2F1;
        }

        .panel-body {
            min-height: 91vh;
            top: 0;
        }

        .btn-light {
            background: #DAE0E5;
        }

        .card-eventos {
            background: #E3F2FD;
            border: none;
            box-shadow: 1px 5px 2px #BBDEFB;

        }

        .text-eventos {
            color: #37474F;
        }

        .text-cliente {
            color: #37474F;
        }

        .titulo {
            color: #263238;
        }

        .contactos {
            color: #37474F;
            font-size: 9pt;
        }

        .tipo-evento {
            color: #546E7A;
            font-size: 9pt;
        }

        .text-observacion {
            color: #455A64;
        }

        .total {
            color: #37474F;
        }

        .dpl {
            background: #E3F2FD;
        }

        .fecha-label {
            margin-left: 30px;
        }

        .fecha-info {
            font-weight: bold;
        }

        [v-cloak] {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div id="appEventos" v-cloak>
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-12">
                    <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                        v-show="message.message && message.type">
                        <strong>@{{ message.message }}</strong>
                    </div>
                    <div class="card panel-body shadow p-3">
                        <x-message></x-message>
                        <div class="row p-4">

                            <div class="col- 12 mb-3">
                                <h3>EVENTOS</h3>
                                <div class="col-12 mb-3">

                                    LISTADO DE EVENTOS

                                </div>
                                <div class="input-group ">
                                    @can('eventos.create')
                                        <a type="button" class="btn btn-light m-1  p-2" href="{{ route('eventos.evento') }}"
                                            style="border-radius: 6px; width: 14%;"><span class="mdi mdi-check"></span>
                                            AGREGAR EVENTO</a>
                                    @endcan
                                    <input type="text" class="form-control" aria-describedby="helpId"
                                        placeholder="BUSCAR POR NUMERO DE EVENTO O CLIENTE" v-model="txtBusqueda"
                                        style="border-radius: 9px; margin-left:10px;" />

                                </div>

                            </div>
                            <div class="col-12 mb-3">

                                <div class="col-12">
                                    @can('eventos.pendiente')
                                    <a class="btn btn-light m-1" role="button" @click="tipoFiltrado = 1"
                                        style="position: relative;">
                                        PENDIENTES
                                        <span v-if="tipoFiltrado == 1"
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                            @{{ funcBuscarEventos.length }}
                                        </span>
                                    </a>
                                    @endcan
                                    @can('eventos.completados')
                                        <a class="btn btn-light m-1 " role="button" @click="tipoFiltrado =2"
                                            style="position: relative;">

                                            COMPLETADOS
                                            <span v-if="tipoFiltrado == 2"
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                                @{{ funcBuscarEventos.length }}
                                            </span>
                                        </a>
                                    @endcan
                                    @can('eventos.autorizado')
                                    <a class="btn btn-light  m-1" role="button" @click="tipoFiltrado =3"
                                        style="position: relative;">
                                        AUTORIZADOS
                                        <span v-if="tipoFiltrado == 3"
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                            @{{ funcBuscarEventos.length }}
                                        </span>
                                    </a>
                                    @endcan
                                    @can('eventos.cerrado')
                                    <a class="btn btn-light  m-1" role="button" @click="tipoFiltrado = 4"
                                        style="position: relative;">
                                        CERRADOS
                                        <span v-if="tipoFiltrado == 4"
                                            class=" position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                            @{{ funcBuscarEventos.length }}
                                        </span>
                                    </a>
                                    @endcan
                                    @can('eventos.todo')
                                    <a class="btn btn-light  m-1" role="button" @click="tipoFiltrado = 0"
                                        style="position: relative;">
                                        TODOS
                                        <span v-if="tipoFiltrado == 0"
                                            class=" position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                            @{{ funcBuscarEventos.length }}
                                        </span>
                                    </a>
                                    @endcan
                                </div>
                            </div>
                            <div class="col-12 mb-4" v-if="funcBuscarEventos.length === 0">
                                <div class="alert alert-info" role="alert">
                                    No se encontraron eventos.
                                </div>
                            </div>

                            <div class="col-12 mb-4"
                                v-for="({id, cid, clientes, total_evento, formateada, forma_pagos, estado, autoriza, facturado, comprobante, titular, solicita, minimo_personas, maximo_personas, tipo_eventos}, e) in funcBuscarEventos"
                                :key="e">
                                <div class="card card-eventos">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-9 titulo text-uppercase">
                                                        @{{ clientes && clientes.nombre ? clientes.nombre : titular }}
                                                    </div>
                                                    <div
                                                        class="col-3 d-flex align-items-end justify-content-end total text-uppercase">
                                                        Nº @{{ id }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div
                                                        class="col-9 contactos titulo text-uppercase d-flex align-items-start justify-content-start">
                                                        CONTACTOS: TEL. <template
                                                            v-if="clientes && clientes.contactos && clientes.contactos.length > 0">
                                                            <div>
                                                                <span>@{{ clientes.contactos[0].valor }}</span>
                                                            </div>
                                                        </template>, EMAIL: @{{ clientes && clientes.email ? clientes.email : 'no se asignado cliente aun' }}
                                                    </div>
                                                    <div
                                                        class="col-3 d-flex align-items-end justify-content-end total text-uppercase">
                                                        MIN:@{{ minimo_personas }} - MAX: @{{ maximo_personas }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-9 tipo-evento text-uppercase">
                                                        TIPO DE EVENTO: @{{ tipo_eventos && tipo_eventos.evento }} <span
                                                            class="fecha-label">FECHA:</span>
                                                        <span class="fecha-info">@{{ formateada }}</span>
                                                    </div>
                                                    <div
                                                        class="col-3 d-flex align-items-end justify-content-end total text-uppercase">
                                                        @{{ forma_pagos && forma_pagos.forma }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-9 tipo-evento">
                                                        ESTADO:
                                                        <span class="tipo-evento">
                                                            <span
                                                                v-if="estado && !comprobante && !solicita">PENDIENTE
                                                                DE COMPLETAR</span>
                                                            <span v-if="estado == true && solicita">COMPLETADO</span>
                                                            <span v-if="autoriza">AUTORIZADO</span>
                                                            <span v-if="estado == true && facturado == true">CERRADO</span>
                                                        </span>
                                                    </div>
                                                    <div
                                                        class="col-3 d-flex align-items-end justify-content-end total text-uppercase">
                                                        TOTAL: $ @{{ total_evento.toFixed(2) }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-10">
                                                        <div class="btn-group m-1" role="group">
                                                            <button class="btn btn-light text-uppercase dropdown-toggle"
                                                                type="button" id="dropdownMenuButton1"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <span class="mdi mdi-plus"></span> Agregar
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                <li class="nav-item" v-show="clientes">
                                                                    @can('anticipos.create')
                                                                        <a class="dropdown-item"
                                                                            :href="'/eventos/anticipos/' + cid" role="button">
                                                                            <span class="mdi mdi-plus-thick"></span> ANTICIPO
                                                                        </a>
                                                                    @endcan
                                                                </li>
                                                                <li class="nav-item">
                                                                    @can('comandas.create')
                                                                        <a class="dropdown-item" href="#AddComandas"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#AddComandas"
                                                                            :data-id="cid" :data-mesa="numeroDeMesa"
                                                                            role="button">
                                                                            <span class="mdi mdi-cart-plus"></span> COMANDA
                                                                        </a>
                                                                    @endcan
                                                                </li>
                                                                <li class="nav-item">
                                                                    @can('servicios.create')
                                                                        <a class="dropdown-item" href="#AddOrdenes"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#AddOrdenes" :data-id="cid"
                                                                            role="button">
                                                                            <span
                                                                                class="mdi mdi-treasure-chest-outline"></span>
                                                                            SERVICIO
                                                                        </a>
                                                                    @endcan
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        @can('eventos.autorizar')
                                                            <a class="btn btn-light m-1"
                                                                :href="'/eventos/solicita/autorizar/' + cid" role="button" v-show="!autoriza">
                                                                <span class="mdi mdi-signature-freehand"></span> SOLICITAR
                                                                AUTORIZACION
                                                            </a>
                                                        @endcan
                                                        @can('eventos.duplicar')
                                                            <a class="btn btn-light m-1" :href="'/eventos/duplicar/' + cid" v-show="autoriza"
                                                                role="button">
                                                                <span class="mdi mdi-content-duplicate"></span> DUPLICAR
                                                            </a>
                                                        @endcan
                                                        @can('eventos.desbloquear')
                                                            <a class="btn btn-light m-1" href="#DesbloquearModal"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#DesbloquearModal"
                                                                            :data-id="cid" v-show="autoriza"
                                                                role="button">
                                                                <span class="mdi mdi-calendar-lock-open"></span> DESBLOQUEAR
                                                            </a>
                                                        @endcan
                                                    </div>
                                                    <div class="col-2 d-flex align-items-end justify-content-end ms-auto">
                                                        @can('eventos.detalle')
                                                            <a class="btn btn-light m-1" :href="'/eventos/detalle/' + cid"
                                                                role="button" @click.stop>
                                                                <span class="mdi mdi-file-document-alert-outline"></span>
                                                                DETALLES
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="row justify-content-center">
                            <div class="col-md-12 text-center">
                                {{ $eventos->links() }}
                            </div>
                        </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>

    </div>
    </div>
    <!--MODAL AGREGAR COMANDA A EVENTO -->
    <div id="AddComandas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Agregar comandas a evento</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="{{ route('comandas.comandaEvento') }}" method="post">
                            @csrf
                            <input type="hidden" id="eventos_id" name="eventos_id">
                            <div class="col-12 mt-1">
                                <input type="number" class="form-control" id="mesa_comanda" name="mesa_comanda"
                                    placeholder="ingrese el numero de la comanda " required>
                            </div>
                            <div class="col-12 mt-1">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="comfirmacion_comanda" required>
                                    <label class="form-check-label" for="comfirmacion_comanda">
                                        Confirmo, que agregare comandas a este evento
                                        <br>
                                        <small>Por favor, vuelva a revisar antes de agregar </small>
                                    </label>
                                </div>

                            </div>
                            <div class="col">
                                <button id="btnAgregarComanda" type="submit" class="btn btn-primary">Agregar</button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">Cerrar</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--MODAL AGREGAR ordenes A EVENTO -->
    <div id="AddOrdenes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Agregar ordenes a evento</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="{{ route('ordenes.ordenEvento') }}" method="post">
                            @csrf
                            <input type="hidden" id="eventos_id" name="eventos_id">
                            <div class="col-12 mt-1">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="comfirmar_orden" required>
                                    <label class="form-check-label" for="comfirmar_orden">
                                        Confirmo, que agregare una orden de servicio a este evento.
                                        <br>
                                        <small>Por favor, vuelva a revisar antes de agregar </small>
                                    </label>
                                </div>

                            </div>
                            <div class="col ">
                                <button type="submit" class="btn btn-primary  ">Agregar</button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">Cerrar</button>

                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- modal para desbloquear evento -->
        <div class="modal fade" id="DesbloquearModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel">
                            Desbloquear evento
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('eventos.desbloquear_evento') }}" method="post">
                        @csrf
                            <input type="hidden" id="eventos_id" name="eventos_id">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="password" class="form-label">Ingrese su contraseña</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Escriba aquí su contraseña" required />
                            </div>
                            <div class="mb-3">
                                <label for="observacion_negacion" class="form-label">Observaciones o motivos por que se desbloqueara este evento :
                                </label>
                                <textarea class="form-control h-100" id="observacion_negacion" name="observacion_negacion"
                                    placeholder="Escriba las observaciones o motivos del por que se debra desbloquear  (max. 200 caracteres) aqui ..."
                                    rows="6" style="resize: vertical;"></textarea>

                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="confirm" value="1"
                                    id="confirmar" required>
                                <label class="form-check-label" for="confirmar">
                                    Confirmo desbloquear este evento.
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">
                                Desbloquear
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var app = new Vue({
            el: '#appEventos',
            data: {
                eventos: @json($eventos).data,
                type: '',
                tipoFiltrado: 0,
                txtBusqueda: '',
                numeroDeMesa: '',
                message: {},


            },
            methods: {

            },
            mounted() {
                let myModal = document.getElementById('AddComandas');
                myModal.addEventListener('show.bs.modal', function(event) {
                    let button = event.relatedTarget;
                    let eventoId = button.getAttribute('data-id');
                    let mesa = button.getAttribute('data-mesa');
                    let eventoIdInput = myModal.querySelector('#eventos_id');
                    let mesaInput = myModal.querySelector('#mesa_comanda');
                    eventoIdInput.value = eventoId;
                    mesaInput.value = mesa;
                });
                let myModalOrdenes = document.getElementById('AddOrdenes');
                myModalOrdenes.addEventListener('show.bs.modal', function(event) {
                    let button = event.relatedTarget;
                    let eventoId = button.getAttribute('data-id');
                    let eventoIdInput = myModalOrdenes.querySelector('#eventos_id');
                    eventoIdInput.value = eventoId;
                });
                let myModalDesbloquear = document.getElementById('DesbloquearModal');
                myModalDesbloquear.addEventListener('show.bs.modal', function(event) {
                    let button = event.relatedTarget;
                    let eventoId = button.getAttribute('data-id');
                    let eventoIdInput = myModalDesbloquear.querySelector('#eventos_id');
                    eventoIdInput.value = eventoId;
                });


            },

            computed: {
                funcBuscarEventos() {
                    switch (this.tipoFiltrado) {
                        case 1: //Pendientes
                            return this.eventos.filter((evento) => ((evento.estado == 1) && (evento.comprobante ==
                                false) && !evento.solicita));
                            break;
                        case 2: //Completados
                            return this.eventos.filter((evento) => evento.estado == 1 && evento.solicita);
                            break;
                        case 3: //Autorizados
                            return this.eventos.filter((evento) => evento.autoriza && evento
                                .autoriza_users_id);

                            break;
                        case 4: //Cerrados
                            return this.eventos.filter((evento) => evento.facturado == true && evento.estado ==
                                false);
                            break;
                        default: //Caja de busqueda / Todas debe buscar por id y cliente
                            const buscar = this.txtBusqueda.trim();
                            return this.eventos.filter((evento) => {
                                const clienteNombre = evento.clientes ? evento.clientes.nombre : '';
                                const titular = evento.titular ? evento.titular : '';
                                return (
                                    (evento.id === parseInt(buscar)) ||
                                    (clienteNombre.toLowerCase().includes(buscar.toLowerCase())) ||
                                    (titular.toLowerCase().includes(buscar.toLowerCase()))
                                );
                            }); //parsee el txtBusqueda
                            break;
                    }

                }
            }
        });
    </script>
@endsection
