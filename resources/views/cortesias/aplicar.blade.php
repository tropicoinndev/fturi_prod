@extends('layouts.cajas')

@section('panel_caja')
    <div id="appCortesia">
        <form action="{{ route('cortesias.store') }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ Crypt::encryptString($origen == 1 ? $p->orden : $p->id) }}">
            <input type="hidden" name="origen" value="{{ Crypt::encryptString($origen) }}">

            <div class="row mb-3 text-uppercase">
                <div class="col-12 h2 text-muted">
                    Crear cortesía
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-uppercase fw-bolder mb-2">
                    Se agregara la siguiente cuenta a cortesía:
                </div>

                @switch($origen)
                    @case(1)
                        <div class="col-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        Orden No. {{ $p->orden }}
                                    </h5>
                                    <p class="card-text">
                                    <div>
                                        {{ $p->detalle_orden->count() }}
                                        {{ $p->detalle_orden->count() > 1 ? 'conceptos agregados' : 'concepto agregado' }}
                                        · ${{ number_format($p->getSumDetalleOrden(), 2) }}
                                    </div>
                                    <div>
                                        <span>
                                            {{ $p->cajas->caja }}
                                        </span>
                                        <span class="float-end">
                                            {{ \Carbon::parse($p->created_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @break

                    @case(2)
                        <div class="col-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        Estadía Reg. {{ $p->id }}
                                    </h5>
                                    <p class="card-text">
                                    <div>
                                        Estadía de {{ $p->dias }}
                                        {{ $p->dias > 1 ? 'dias' : 'dia' }}
                                        · ${{ number_format($p->tarifas->precio * $p->dias, 2) }}
                                    </div>
                                    <div>
                                        <span class="float-end">
                                            {{ \Carbon::parse($p->created_at)->diffForHumans() }}
                                        </span>
                                        <span>
                                            Habitación {{ $p->habitaciones->numero_habitacion }}
                                        </span>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @break

                    @case(3)
                        <div class="col-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        Comanda No. {{ $p->id }} - Mesa #{{ $p->mesa }}
                                    </h5>
                                    <p class="card-text">
                                    <div>
                                        {{ $p->detalles_comanda->count() }}
                                        {{ $p->detalles_comanda->count() > 1 ? 'conceptos agregados' : 'concepto agregado' }}
                                        · ${{ number_format($p->detalles_comanda->sum('total'), 2) }}
                                    </div>
                                    <div>
                                        <span>
                                            {{ $p->cajas->caja }}
                                        </span>
                                        <span class="float-end">
                                            {{ \Carbon::parse($p->created_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @break
                @endswitch

            </div>
            <div class="row">
                <div class="col-12">
                    <h5>
                        Seleccione el titular de la cortesía:
                    </h5>
                </div>
                <div class="col-12 mb-3">

                    <div class="btn-group me-3" role="group" aria-label="Basic radio toggle button group"
                        v-for="t in tipo_cortesia" :title="t.descripcion">
                        <input type="radio" class="btn-check" name="tipo_cortesia" :id="'btnradio_' + t.id"
                            autocomplete="off" v-model="titulares" :value="t.titulares" checked>
                        <label class="btn btn-outline-primary" :for="'btnradio_' + t.id" @click="titular = ''">
                            @{{ t.tipo }}
                            <span class="badge text-bg-light">@{{ t.titulares.length ?? 0 }}</span>
                        </label>
                    </div>
                </div>
                <div class="col-4 mb-3" v-for="p in titulares" v-if="titulares.length > 0">
                    <input type="radio" class="btn-check" name="titular" :id="'titular' + p.cid" autocomplete="off"
                        :value="p.cid" v-model="titular">
                    <label class="card btn btn-outline-primary" :for="'titular' + p.cid">
                        <div class="card-body d-flex content-end" style="height: 60px;">
                            <p class="card-title">@{{ p.titular }}</p>
                        </div>
                    </label>
                </div>

            </div>
            <div class="form-group mt-5">
                <button class="btn btn-primary" type="submit"
                    :disabled="titular == null || titular.length < 90">Guardar</button>
                <a class="btn btn-light" href="{{ url()->previous() }}">Volver</a>
            </div>
        </form>
    </div>
    <script>
        var app = new Vue({
            el: '#appCortesia',
            data: {
                tipo_cortesia: @json($tipoCortesia),
                titulares: [],
                titular: '',
            },
            mounted() {
                if (this.tipo_cortesia.length > 0)
                    this.titulares = this.tipo_cortesia[0].titulares ?? [];
            },
        });
    </script>
@endsection
