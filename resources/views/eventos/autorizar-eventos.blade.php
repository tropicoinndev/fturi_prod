@extends('layouts.app')
@section('style')
    <style>
        body {
            background-color: #B2DFDB;
        }

        .panel {
            min-height: 90vh;
        }

        .evento {
            background: #BBDEFB;
            color: #263238;
        }

        .test {
            width: 90%;
            /* Ancho del 70% */
            float: left;
            border-bottom: 1px solid #000;

        }

        .test1 {
            width: 30%;
            /* Ancho del 30% */
            float: left;
            margin: left 0px;


        }

        .mb {
            margin-bottom: 5px;

        }

        .underline {
            text-decoration: underline;
        }

        .float-end {
            float: right;
        }

        .float-start {
            float: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
@endsection

@section('content')
    <div id="autorizarEvento">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card panel shadow p-3">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-12 col-lg-6 mb-4">
                                    <h3 class="card-title text-uppercase">
                                        Eventos pendientes por autorizar
                                    </h3>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="mb-3">
                                                <input type="text" name="" id="" class="form-control"
                                                    placeholder="Buscar por nombre de cliente..." v-model="buscar" />
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <a class="btn btn-primary float-end" style="margin-right: 10px;"
                                                href="{{ route('eventos.eventos') }}" role="button">
                                                Volver
                                            </a>
                                        </div>
                                    </div>
                                </div>


                                <!--Mensajes de alerta alerta-->
                                <div class="col-12 col-lg-12">
                                    <x-message></x-message>
                                </div>
                                <div class="col-12 my-3">
                                    <h5>Listado de eventos pendientes de autorización</h5>
                                </div>
                                <div class="row col-md-12 ">
                                    <div v-for="evento in listEventos" :key="evento.id" class="col-12 col-lg-4 mb-3">
                                        <div class="card evento">
                                            <div class="card-body">
                                                <span class="float-end"> No. @{{ evento.id }}</span>
                                                <h5 class="card-title text-truncate">
                                                    @{{  evento.clientes ? evento.clientes.nombre : evento.titular}}
                                                </h5>
                                                <p class="aling-item-center">
                                                <div>
                                                    Tipo de evento: <span class="text-uppercase">@{{ evento.tipo_eventos.evento }}</span>
                                                </div>
                                                <div>
                                                    Creado por: <span class="text-uppercase">@{{ evento.usuarios.name }}</span>
                                                </div>
                                                <div class="monto">
                                                    <span class="float-end">Monto: $@{{ evento.total_evento.toFixed(2) }}</span>
                                                </div>
                                                </p>
                                                <div class="actions">

                                                    <a class="btn btn-primary" :href="'/eventos/autorizar/pendientes/' + evento.cid" type="button"


                                                        >
                                                        Detalle
                                                    </a>
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="eventos.length === 0" class="col-12">
                                        <div class="alert alert-primary" role="alert">
                                            <h4 class="alert-heading">Información</h4>
                                            Aun no se han solicitado eventos para autorizar, cuando se soliciten la autorizacion aparecerán
                                            aquí.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- Modal para actualizar el tipo de evento  a este  evento -->
    <div id="detalle" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Actualizar el tipo de evento a este evento #
                        </h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const autorizarEvento = new Vue({
            el: '#autorizarEvento',
            data() {
                return {

                    eventos: @json($p),
                    buscar: '',

                };
            },

            methods: {

            },
            mounted() {},
            computed: {
                listEventos: function() {

                    const searchText = this.buscar.trim();
                    return this.eventos.filter((evento) => {
                        const clienteNombre = evento.clientes ? evento.clientes.nombre : '';
                        const titular = evento.titular ? evento.titular : '';
                        return (
                            (evento.id === parseInt(searchText)) ||
                            (clienteNombre.toLowerCase().includes(searchText.toLowerCase())) ||
                            (titular.toLowerCase().includes(searchText.toLowerCase()))
                        );
                    });

                },

            }
        });
    </script>
@endsection
