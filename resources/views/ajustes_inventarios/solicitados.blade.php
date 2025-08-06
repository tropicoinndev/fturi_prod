@extends('layouts.ajustes_inventarios')

@section('style-ajustes-inventarios')
    <style>
        .estilo-card {
            height: 265px;
            border-radius: 15px;
        }
        [v-cloak]{
            display: none;
        }
    </style>
@endsection

@section('ajustes_inventarios_content')
    <div id="appAjustesSinCompletar"  v-cloak>
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="card-title text-uppercase mb-4">
                    <span class="mdi mdi-file-document-check text-success h2"></span>
                    Ajustes sin completar
                </h3>
                <x-message></x-message>
            </div>
        </div>

        <div class="row mb-4">
            @if($p->isEmpty())
                <div class="alert alert-warning" role="alert">
                    No hay solicitudes de ajustes de inventarios disponibles.
                </div>
            @else
            @foreach($p as $s)
                @if($s->solicitante_users_id !== null)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3 ">
                        <div class="estilo-card card border-success h-100 w-100">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-truncate"><b>{{ $s->userSolicitante->name }}</b></h5>
                                <small>{{ $s->observacion ?? '---' }}</small>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <div class="row">
                                    <div class="col-7"><b>Ajuste Nº:</b></div>
                                    <div class="col-5 text-end text-danger"><b>#{{ $s->id }}</b></div>

                                    <div class="col-7"><b>Realizado por:</b></div>
                                    <div class="col-5 text-end">{{ $s->userRealiza->name }}</div>

                                    <div class="col-7"><b>Estado:</b></div>
                                    <div class="col-5 text-end">
                                        @if($s->estado)
                                            <span class="text-success">Activo</span>
                                        @else
                                            <span class="text-danger">Inactivo</span>
                                        @endif
                                    </div>

                                    <div class="col-7"><b>Fecha de proceso:</b></div>
                                    <div class="col-5 text-end text-muted mb-3">{{ $s->fecha_proceso }}</div>

                                    <div class="col-12 text-end mb-1">
                                        <a href="{{ route('solicitud.detalle',['id'=>$s->cid]) }}" class="btn btn-outline-success btn-sm rounded-5">Detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
        </div>
        <div class="d-flex justify-content-start mt-4">
            {{ $p->links() }}
        </div>
    </div>
@endsection

@section('script-ajustes-inventarios')
    <script type="module">
        let app = window.appVue({
            data(){
                return {

                }
            },
            mounted(){


            },
            methods: {

            },
            computed: {

            },
            watch: {

            },
        });
        app.mount('#appAjustesSinCompletar');
    </script>
@endsection
