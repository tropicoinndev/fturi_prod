@extends('layouts.dtes')
@section('dte_content')
    <section id="appComprobantesTurnos">
        <div class="row  mb-4">
            <div class="col-12">
                <h3 class="card-title text-uppercase">ABRIR TURNO</h3>
                <small>
                    EDICIÓN DE TURNOS
                </small>
                <x-message></x-message>
            </div>
        </div>
        <div class="row  mb-4">
            <div class="col-12">
                <form class="d-flex">

                    <div class="col me-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Caja</label>
                            <select class="form-select" aria-label="Cajas" v-model="cajaSelected">
                                <option value="">Seleccione una caja</option>
                                <option :value="j.cid" v-for="j in cajas">
                                    @{{ j.caja }}
                                </option>

                            </select>
                        </div>
                    </div>
                    <div class="col me-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Fecha</label>
                            <input type="date" class="form-control" placeholder="Fecha cambio" v-model="fecha" />
                        </div>
                    </div>
                    <div class="col-1 me-3 d-flex align-items-end">
                        <div class="mb-3">
                            <button class="btn btn-light m-auto" type="button" :disabled="!isValid" @click="getTurnos()">
                                <span class="mdi mdi-magnify"></span>
                                Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">

            <div class="col-12 table-responsive" v-if="list && list.length > 0">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Turno</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Apertura</th>
                            <th scope="col">Cierre</th>
                            <th scope="col">Usuario apertura</th>
                            <th scope="col">Usuario cierra</th>
                            <th scope="col">Abrir de nuevo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for='l in list'>
                            <th scope="row">@{{ l.opcion.turno }}</th>
                            <th scope="row">@{{ l.fecha }}</th>
                            <td>@{{ l.apertura }}</td>
                            <td>@{{ l.cierre }}</td>
                            <td>@{{ l.uapertura.user }}</td>
                            <td>@{{ l.ucierre.user }}</td>
                            <td>
                                <button class="btn btn-light" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalApertura" @click="turno = l">Abrir</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12" v-if="error">
                <div class="alert alert-danger" role="alert">
                    @{{ error }}
                </div>
            </div>
        </div>



        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        <div class="modal fade" id="modalApertura" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            role="dialog" aria-labelledby="Aperturar turno" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered " role="document">
                <div class="modal-content" v-if="turno != null">
                    <div class="modal-header">
                        <h5 class="modal-title" id="Aperturar">
                            Aperturar @{{ turno.opcion.turno }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('comprobantes.api_aperturaTurnoStore') }}" method="post">
                        @csrf
                        <input type="hidden" name="turno" :value="turno.cid">
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-4">Fecha</div>
                                <div class="col-8">@{{ turno.fecha }}</div>
                                <div class="col-4">Apertura</div>
                                <div class="col-8">@{{ turno.apertura }}</div>
                                <div class="col-4">Cierre</div>
                                <div class="col-8">@{{ turno.cierre }}</div>
                                <div class="col-4">Usuario aperturo</div>
                                <div class="col-8">@{{ turno.uapertura.user }}</div>
                                <div class="col-4">Usuario cerro</div>
                                <div class="col-8">@{{ turno.ucierre.user }}</div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="confirm"
                                            id="confirm">
                                        <label class="form-check-label" for="confirm">
                                            Confirmo que recibí una solicitud, y estoy dando el seguimiento según lo
                                            solicitado.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cerrar
                            </button>
                            <button type="submit" class="btn btn-primary">Abrir turno</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
@endsection
@section('script-dte')
    <script type="module">
        var app = window.appVue({
            data() {
                return {
                    cajas: @json($cajas),
                    cajaSelected: '',
                    fecha: null,
                    list: [],
                    turno: null,
                    error: null,
                }
            },
            computed: {
                isValid() {
                    return this.cajaSelected &&
                        this.cajaSelected.length >= 100 &&
                        this.fecha &&
                        this.fecha.length == 10;
                }
            },
            methods: {
                getTurnos: function() {
                    if (this.isValid)
                        axios.post("{{ route('comprobantes.AperturaTurnosSearch') }}", {
                            'caja': this.cajaSelected,
                            'fecha': this.fecha,
                        }).then((r) => {

                            if (r.data.error && r.data.error.length > 0)
                                this.error = r.data.error;
                            else if (r.data.list)
                                this.list = r.data.list;
                            else this.error =
                                'No se encontraron datos, no se encontraron turnos o comprobantes';


                        }).catch((err) => {
                            console.log(err);

                        });

                },
                setErrores: function(error) {
                    this.error = error;
                    setTimeout(() => {
                        this.error = null;
                    }, 5000);
                }
            },
        });

        app.mount("#appComprobantesTurnos");
    </script>
@endsection
