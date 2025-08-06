@extends('layouts.cajas')

@section('panel_caja')
    <div id="appAbonos">
        <form action="{{ route('abonos_detalles.store') }}" method="post">
            <input type="hidden" name="id" value="{{ Crypt::encryptString($p->id) }}">
            <input type="hidden" name="comprobantes[]" v-for="c in getComprobantes" :value="c" @key="c">
            @csrf
            <div class="row">
                <div class="col-12">
                    <h4 class="text-uppercase">Nuevo ingreso a caja</h4>
                </div>
                <div class="col-12 text-uppercase ">
                    Cliente:
                    <span class="fw-bold">
                        {{ $p->clientes->nombre }} {{ $p->email }}
                    </span>
                </div>

                <div class="col-12 h5 mb-4">
                    <button class="btn btn-primary float-end" type="submit"
                        :disabled="comprobantes.length == 0 || comprobantes == null">
                        Guardar
                        <span v-if="comprobantes.length > 0 && comprobantes != null">
                            $@{{ parseFloat(getSumMonto).toFixed(2) }}
                        </span>
                    </button>
                    <a class="btn btn-light float-end me-2" href="{{ route('abonos.index') }}">Volver</a>

                    <p>
                        Seleccione uno mas comprobantes para agregar al ingreso
                    </p>
                </div>
            </div>


            <div class="row">
                @forelse ($comprobantes as $c)
                    @php
                        $e = ['comprobante' => Crypt::encryptString($c->comprobantes_id), 'monto' => $c->monto];
                    @endphp
                    <div class="col-4 mb-4">
                        <input type="checkbox" class="btn-check" id="{{ $c->id }}" autocomplete="off"
                            :value='@json($e)' v-model="comprobantes">
                        <label class="card btn btn-outline-primary" for="{{ $c->id }}">
                            <div class="card-body">
                                <h5 class="card-title text-start">{{ $c->tipo }}<span
                                        class="float-end">#{{ $c->correlativo }}</span></h5>
                                <h6 class="card-subtitle mb-2 text-start">
                                    {{ $c->fecha }}
                                    <span class="float-end">${{ number_format($c->monto, 2) }} /
                                        ${{ number_format($c->total, 2) }}</span>
                                </h6>
                            </div>
                        </label>
                    </div>
                @empty
                    <div class="alert alert-danger" role="alert">
                        No se puede completar este ingreso a caja, no hay ningun comprobante en la forma de pago creditos.
                    </div>
                @endforelse
            </div>

        </form>
    </div>
@endsection
@section('script-caja')
    <script type="module">
        var abonos = appVue({
            data() {
                return {
                    comprobantes: []
                }
            },
            computed: {
                getSumMonto() {
                    return this.comprobantes.reduce((p, c) => {
                        const pv = parseFloat(p);
                        const ac = parseFloat(c.monto);
                        console.log(pv, ac);
                        return pv + ac;
                    }, 0);
                },
                getComprobantes() {
                    return this.comprobantes.map((i) => {
                        return i.comprobante;
                    });
                }
            },
            methods: {
                setCliente: function(c) {
                    this.cliente = c;
                }
            },
        });
        abonos.component('clientes', component.clientes);
        abonos.mount("#appAbonos");
    </script>
@endsection
