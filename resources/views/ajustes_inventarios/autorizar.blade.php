@extends('layouts.ajustes_inventarios')

@section('style-ajustes-inventarios')
    <style>
        .estilo-card {
            height: 265px;
            border-radius: 15px;
        }
    </style>
@endsection

@section('ajustes_inventarios_content')
    <div id="appAjustesPendientesAutorizar">
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="card-title text-uppercase mb-4">
                    <span class="mdi mdi-file-clock text-success h2"></span>
                    Solicitudes pendientes de autorizar
                </h3>
                <x-message></x-message>
            </div>
        </div>

        <div class="row mb-4">
            {{-- {{ json_encode($p) }} --}}
            @foreach($p as $s)
                @if($s->solicitante_users_id !== null)
                    <div class="col-4 mb-3">
                        <div class="estilo-card card border-success h-100 w-100">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-truncate"><b>{{ $s->userSolicitante->name }}</b></h5>
                                <small>{{ $s->observacion ?? '---' }}</small>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <div class="row">
                                    <div class="col-6"><b>Ajuste Nº:</b></div>
                                    <div class="col-6 text-end text-danger"><b>#{{ $s->id }}</b></div>

                                    <div class="col-6"><b>Bodega:</b></div>
                                    <div class="col-6 text-end">{{ $s->ajustesExistencias[0]->existencias->bodegas->bodega }}</div>

                                    <div class="col-6"><b>Realizado por:</b></div>
                                    <div class="col-6 text-end">{{ $s->userRealiza->name }}</div>

                                    <div class="col-6"><b>Estado:</b></div>
                                    <div class="col-6 text-end">
                                        @if($s->estado)
                                            <span class="text-success">Activo</span>
                                        @else
                                            <span class="text-danger">Inactivo</span>
                                        @endif
                                    </div>

                                    <div class="col-6"><b>Fecha de proceso:</b></div>
                                    <div class="col-6 text-end text-muted mb-3">{{ $s->fecha_proceso }}</div>

                                    <div class="col-12 text-end mb-1">
                                        <a href="{{ route('autorizar.detalle',['id'=>$s->cid]) }}" class="btn btn-outline-success btn-sm rounded-5">Detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
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
                console.log('Ajustes sin completar mounted.');
            },
            methods: {

            },
            computed: {

            },
            watch: {

            },
        });
        app.mount('#appAjustesPendientesAutorizar');
    </script>
@endsection
