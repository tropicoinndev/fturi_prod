@extends('layouts.clientes_panel')
@section('style-content')
    <style>
        body {
            background: #80DEEA !important;
        }
    </style>
@endsection
@section('content_cliente')
    <div class="container" id="appContent">
        <div class="card-body p-2">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    Alertas de créditos
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <input type="text" class="form-control" name="" id="" aria-describedby="helpId"
                        placeholder="Buscar por nombre o fecha" v-model="titular" />
                </div>
                <div class="col-6">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="ven" value="option1" v-model="vencido" />
                        <label class="form-check-label" for="ven">Vencidos</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="venhoy" value="option2" v-model="hoy" />
                        <label class="form-check-label" for="venhoy">Vencen hoy</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="tiempo" value="option3" v-model="aTiempo" />
                        <label class="form-check-label" for="tiempo">A tiempo</label>
                    </div>

                </div>
            </div>
            <div class="row mb-2">

                <table class="table table-striped table-inverse">
                    <thead class="thead-default">
                        <tr>
                            <th>FECHA</th>
                            <th>TITULAR</th>
                            <th>CRÉDITO</th>
                            <th>DIAS TRANSCURRIDOS</th>
                            <th>ESTADO</th>
                            <th>OPCIONES</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for="c in getData">
                            <td scope="row">@{{ c.fecha }}</td>
                            <td>@{{ c.titular }}</td>
                            <td>@{{ money(c.monto) }}</td>
                            <td>hace @{{ c.dias_transcurridos }} dias / @{{ c.dias }} dias</td>

                            <td>
                                <h5>
                                    <span class="badge" :class="getBg(c)">@{{ getStatus(c) }}</span>
                                </h5>


                            </td>
                            <td>
                                <a class="btn btn-light me-2" :href="`/comprobantes/pdf/${c.cid_comprobante}`"
                                    role="button" target="_blank" title="Representación gráfica del DTE">
                                    Comprobante
                                </a>
                                @can('clientes.comprobantes')
                                    <a class="btn btn-light" :href="`/clientes/comprobantes/?id=${c.cid_cliente}`"
                                        role="button" target="_blank" title="Ver los comprobantes del cliente">
                                        Cliente
                                    </a>
                                @endcan
                            </td>
                        </tr>



                        <tr v-if="data.length == 0">
                            <td colspan="6">
                                <div class="alert alert-success" role="alert">
                                    No hay comprobantes pendiente. Actualmente si muestran los comprobantes con
                                    {{ env('dias_credito', 5) }} dias de anticipación.
                                </div>
                            </td>

                        </tr>



                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
@section('script-content')
    <script type="module">
        const app = appVue({

            data() {
                return {
                    data: @json($comprobantes),
                    vencido: true,
                    hoy: true,
                    aTiempo: true,
                    titular: ''
                }
            },
            methods: {
                getBg: function(c) {
                    if (c.dias > c.dias_transcurridos)
                        return 'bg-success';
                    else if (c.dias == c.dias_transcurridos)
                        return 'bg-warning';
                    else return 'bg-danger';
                },
                getStatus: function(c) {
                    switch (this.getStatusNumber(c)) {
                        case 2:
                            return 'A tiempo';
                            break;
                        case 1:
                            return 'Vence hoy';
                            break;
                        case 0:
                            return 'Vencido';
                            break;
                        default:
                            break;
                    }


                },
                getStatusNumber: function(c) {
                    if (c.dias > c.dias_transcurridos)
                        return 2;
                    else if (c.dias == c.dias_transcurridos)
                        return 1;
                    else return 0;
                },
                money: function(value) {

                    return new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(value);

                },
            },
            computed: {
                getData() {
                    let d = this.data.filter(v => {
                        let reg = new RegExp((this.titular).toUpperCase());
                        let status = this.getStatusNumber(v);
                        return (((this.vencido && status == 0) || (this.hoy && status == 1) || (this
                            .aTiempo &&
                            status == 2)) && (reg.test(v.titular) || reg.test(v.fecha)));

                    })
                    return d;
                }
            }
        });
        app.mount("#appContent");
    </script>
@endsection
