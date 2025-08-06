@extends('layouts.bodegas')

@section('panel_bodega')
    <div id="appSolicitudesHistoria">
            <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                v-show="message.message && message.type">
                <strong>@{{ message.message }}</strong>
            </div>

        <div class="row mb-4">
            <div class="col-12 text-uppercase h3">
                Historial de solicitudes de requisiciones en la bodega entrada:   {{ session('bodega')->bodega }}
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <div class="mb-3">
                    <label for="">Buscar</label>
                    <input type="text" class="form-control"
                        placeholder="buscar por id, bodegas y usuarios ..." v-model="txtBusqueda">

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped table-inverse">
                    <thead class="thead-inverse">
                        <tr>
                                <th scope="col">N#</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Solicitud</th>
                                <th scope="col">Bodega entrada</th>
                                <th scope="col">Bodega salida</th>
                                <th scope="col">Usuario crea</th>
                                <th scope="col">Usuario autoriza</th>
                                <th scope="col">Usuario elimina</th>
                                <th scope="col">Estado</th>

                        </tr>
                    </thead>
                     <tbody>
                         <tr v-for="s in getSolicitudes" :key="s.id">
                                <td scope="row">@{{ s.id }}</td>
                                <td scope="row">@{{ s.fecha }}</td>
                                <td scope="row">@{{ s.solicitud }}</td>
                                <td scope="row">@{{s.relacion_bodegas_entrada.bodega }}</td>
                                <td scope="row">@{{s.relacion_bodegas_salida.bodega }}</td>
                                <td scope="row">@{{s.relacion_user_creacion.name }}</td>
                                <td scope="row">@{{s.relacion_user_autorizacion ? s.relacion_user_autorizacion.name : '' }}</td>
                                <td scope="row">@{{s.elimina ? s.elimina.name : '' }}</td>
                                <td scope="row">@{{ getEstadoTexto(s.estado) }}</td>
                            </tr>

                    </tbody>
                </table>
            </div>
        </div>


        </div>
        <div class="row justify-content-center">
                            <div class="col-md-12 text-center">
                                {{ $requisiciones->links() }}
                            </div>
                        </div>

    </div>
    <script>
        var app = new Vue({
            el: '#appSolicitudesHistoria',
            data: {
                txtBusqueda: '',
                requisiciones: @json($requisiciones).data,
                message: {},
                type:'',

            },
            methods: {

            },
            computed: {
                getSolicitudes: function() {
                       let regx = new RegExp((this.txtBusqueda).toLowerCase());
                return this.requisiciones.filter(o =>
                    regx.test(o.id.toString()) ||
                    regx.test(o.relacion_user_creacion.name.toLowerCase()) ||
                    regx.test(o.relacion_user_autorizacion?.name.toLowerCase()) ||
                    regx.test(o.relacion_bodegas_entrada.bodega.toLowerCase()) ||
                    regx.test(o.relacion_bodegas_salida.bodega.toLowerCase())
                );
                },
                getEstadoTexto: function () {
                return function (estado) {
                    switch (estado) {
                        case 1:
                            return 'Activa';
                        case 2:
                            return 'Completada';
                        case 3:
                            return 'Autorizada';
                        case 4:
                            return 'Negada';
                        default:
                            return 'Anulada';
                    }
                };
            },

            }
        });

    </script>
@endsection
