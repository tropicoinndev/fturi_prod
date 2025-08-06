@extends('layouts.dtes')
@section('dte_content')
    <section id="appComprobantesTurnos">
        <div class="row  mb-4">
            <div class="col-12">
                <h3 class="card-title text-uppercase">CAMBIO DE TURNOS COMPROBANTES</h3>
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
                            <label for="" class="form-label">Tipo comprobante</label>
                            <select class="form-select" aria-label="Tipo de comprobante" v-model="comprobanteSelected">
                                <option value="">Seleccione un tipo de comprobante</option>
                                <option value="{{ Crypt::encryptString(0) }}">Todos</option>
                                <option :value="c.cid" v-for="c in comprobantes">@{{ c.tipo }}</option>
                            </select>
                        </div>
                    </div>
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
                            <label for="" class="form-label">Fecha cambio</label>
                            <input type="date" class="form-control" placeholder="Fecha cambio" v-model="fechaCambio" />
                        </div>
                    </div>
                    <div class="col me-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Fecha busqueda</label>
                            <input type="date" class="form-control" placeholder="Fecha búsqueda"
                                v-model="fechaBusqueda" />
                        </div>
                    </div>
                    <div class="col me-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Correlativo</label>
                            <input type="number" class="form-control" placeholder="Correlativo" v-model="correlativo"
                                min="0" step="1" />
                        </div>
                    </div>
                    <div class="col-1 me-3 d-flex align-items-end">
                        <div class="mb-3">
                            <button class="btn btn-light m-auto" type="button" :disabled="!isValid"
                                @click="getComprobantes()">
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
                            <th scope="col">Fecha</th>
                            <th scope="col">Documento</th>
                            <th scope="col">Correlativo</th>
                            <th scope="col">Titular</th>
                            <th scope="col">Total</th>
                            <th scope="col">Turno actual</th>
                            <th scope="col">Cambiar Turno</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for='l in list'>
                            <th scope="row">@{{ l.fecha }}</th>
                            <td>@{{ l.tipo_comprobantes.tipo }}</td>
                            <td>@{{ l.correlativo }}</td>
                            <td>@{{ l.titular }}</td>
                            <td>$@{{ l.total }}</td>
                            <td>@{{ l.turnos_opcion.fecha }} @{{ l.turnos_opcion.opcion.turno }}</td>
                            <td>
                                <turnos :comprobantes_id="l.cid" :turnos="turno"
                                    url="{{ route('comprobantes.api_cambioTurnoStore') }}" :actual="l.turnos_cid">
                                </turnos>
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
    </section>
@endsection
@section('script-dte')
    <script type="module">
        var app = window.appVue({
            data() {
                return {
                    cajas: @json($cajas),
                    comprobantes: @json($comprobantes),
                    cajaSelected: '',
                    comprobanteSelected: '',
                    fechaCambio: null,
                    fechaBusqueda: null,
                    correlativo: null,
                    list: [],
                    turno: [],
                    error: null,
                }
            },
            computed: {
                isValid() {
                    return this.comprobanteSelected &&
                        this.comprobanteSelected.length >= 100 &&
                        this.cajaSelected &&
                        this.cajaSelected.length >= 100 &&
                        this.fechaCambio &&
                        this.fechaCambio.length == 10 &&
                        this.fechaBusqueda &&
                        this.fechaBusqueda.length == 10;
                }
            },
            methods: {
                getComprobantes: function() {
                    if (this.isValid)
                        axios.post("{{ route('comprobantes.cambioTurnosSearch') }}", {
                            'caja': this.cajaSelected,
                            'comprobante': this.comprobanteSelected,
                            'fechaTurno': this.fechaCambio,
                            'fechaBusqueda': this.fechaBusqueda,
                            'correlativo': this.correlativo,
                        }).then((r) => {

                            if (r.data.error && r.data.error.length > 0) {
                                this.error = r.data.error;
                            } else if (
                                r.data.list &&
                                r.data.turnos
                            ) {
                                this.list = r.data.list;
                                this.turno = r.data.turnos;
                            } else this.error =
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
        app.component('turnos', component.turnos);
        app.mount("#appComprobantesTurnos");
    </script>
@endsection
