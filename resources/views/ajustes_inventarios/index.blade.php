@extends('layouts.ajustes_inventarios')

@section('style-ajustes-inventarios')
    <style>
        .estilo-card {
            height: 290px;
            border-radius: 15px;
        }
    </style>
@endsection

@section('ajustes_inventarios_content')
    <div id="appAjustesInventariosIndex">
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="card-title text-uppercase">
                    <span class="mdi mdi-view-dashboard text-success h2"></span>
                    Dashboard
                </h3>
                <small>
                    Listado de todos los ajustes autorizados o negados
                </small>
                <x-message></x-message>
            </div>
        </div>

        <div class="row ">
            @forelse($p as $d)
                @if($d->solicitante_users_id !== null)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-3 ">
                        <div class="estilo-card card border-{{ $d->autorizado ? 'success' : 'danger' }} h-100">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-truncate"><b>{{ $d->userSolicitante->name }}</b></h5>
                                <small>{{ $d->observacion ?? '---' }}</small>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <div class="row">
                                    <div class="col-7"><b>Ajuste Nº:</b></div>
                                    <div class="col-5 text-end text-danger"><b>#{{ $d->id }}</b></div>

                                    <div class="col-7"><b>Realizado por:</b></div>
                                    <div class="col-5 text-end">{{ $d->userRealiza->name }}</div>

                                    <div class="col-7"><b>Estado:</b></div>
                                    <div class="col-5 text-end">
                                        @if($d->estado)
                                            <span class="text-success">Activo</span>
                                        @else
                                            <span class="text-danger">Inactivo</span>
                                        @endif
                                    </div>

                                    <div class="col-7"><b>Autorizado:</b></div>
                                    <div class="col-5 text-end">
                                        @if($d->autorizado)
                                            <span class="text-success">Si</span>
                                        @else
                                            <span class="text-danger">No</span>
                                        @endif
                                    </div>

                                    <div class="col-7"><b>Fecha de proceso:</b></div>
                                    <div class="col-5 text-end text-muted mb-3">{{ $d->fecha_proceso }}</div>

                                    <div class="col-12 text-end mb-1">
                                        <a href="{{ route('solicitud.detalle',['id'=>$d->cid]) }}" class="btn btn-outline-{{ $d->autorizado ? 'success' : 'danger' }} btn-sm rounded-5">Detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                No hay datos para mostrar.
            @endforelse
        </div>
    </div>
@endsection

@section('script-ajustes-inventarios')
    <script type="module">
        var app = window.appVue({
            data() {
                return {

                }
            },
            mounted() {
                console.log('Ajustes index mounted.');
            },
            methods: {

            },
        }).
        mount("#appAjustesInventariosIndex");
    </script>
@endsection
