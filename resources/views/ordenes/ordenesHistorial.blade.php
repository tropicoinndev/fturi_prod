@extends('layouts.cajas')

@section('panel_caja')
    <div id="appPanelCaja">
            <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                v-show="message.message && message.type">
                <strong>@{{ message.message }}</strong>
            </div>

        <div class="row mb-4">
            <div class="col-12 text-uppercase h3">
                ordenes historial
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <div class="mb-3">
                    <label for="">Buscar</label>
                    <input type="text" class="form-control"
                        placeholder="Escriba el numero de orden/ o el titular/cliente ..." v-model="txtBusqueda">

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped table-inverse">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Fecha</th>
                            <th>Titular</th>
                            <th>Clientes</th>
                            <th>Cajas</th>
                            <th>Estado</th>
                            <th>Comprobante</th>

                        </tr>
                    </thead>
                     <tbody>
                         <tr v-for="orden in getOrdenes" :key="orden.id">
                                <td scope="row">@{{ orden.fecha }}</td>
                                <td scope="row">@{{ orden.titular }}</td>
                                <td scope="row">
                                    <span v-if="orden.clientes">@{{ orden.clientes.nombre }}</span>
                                    <span v-else>Sin cliente asignado</span>
                                </td>
                                <td scope="row">@{{ orden.cajas.caja }}</td>
                                <td scope="row">
                                    <span v-if="orden.estado == 1">Activo</span>
                                    <span v-else>Desactivado</span>
                                </td>
                                <td scope="row">
                                    <span v-if="orden.comprobante == 1">Con comprobante</span>
                                    <span v-else>Sin comprobante</span>
                                    
                                </td>
                                <td> <button class="btn btn-sm btn-primary" @click="cambiarComprobante(orden)">
                                        @{{ orden.comprobante == 1 ? 'Bloquear' : 'Desbloquear' }}
                                    </button></td>
                            </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <script>
        var app = new Vue({
            el: '#appPanelCaja',
            data: {
                txtBusqueda: '',
                ordenes: @json($ordenes),
                message: {},
                type:'',
                
            },
            methods: {
                cambiarComprobante(orden){
                     const ordenId = orden.id;
                    axios.post("{{ route('ordenes.cambiarComprobante') }}", {
                            ordenId: ordenId
                        })
                        .then((resp) => {
                            //console.log(resp);
                            if (resp.data.type === 'success') {
                                this.setMessage('comprobante cambiado exitosamente.', 'success');
                                setTimeout(() => {
                                    location.reload();    
                                }, 2 *1000);
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
            },
            computed: {
                getOrdenes: function() {
                    let regx = new RegExp((this.txtBusqueda).toLowerCase());
                    return this.ordenes.filter(o =>
                                 regx.test(o.titular.toLowerCase()) ||
                                (o.clientes?.nombre && regx.test(o.clientes.nombre.toLowerCase())) ||
                                regx.test(o.fecha.toLowerCase()) ||
                                regx.test(o.id.toString())
                            );
                },
            }
        });
                
    </script>
@endsection
