@extends('layouts.ajustes_inventarios')

@section('style-ajustes-inventarios')
    <style>
        /*Alerta asincrona*/
        .alert-dismissible {
            /*position: fixed;
            bottom: 200px;
            width: 20%;
            right: 50%;*/
            border-radius: 12px;
            /*z-index: 1000;*/
        }
    </style>
@endsection

@section('ajustes_inventarios_content')
    <div id="appShowAjustesInventarios">
        <div class="row mb-3">
            <div class="col-12">
                <h3 class="card-title text-uppercase">
                    <span class="mdi mdi-file-document-plus text-success h2"></span>
                    Detalle de solicitud
                </h3>
                <x-message></x-message>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <a href="{{ route('ajustes_inventarios.solicitados') }}" class="btn btn-outline-secondary rounded-5 mb-3"><span class="mdi mdi-arrow-left"></span> Volver</a>
            </div>
            <div class="col-6 text-end">
                <button @click="opcion = 2" type="button" class="btn btn-outline-danger rounded-5 me-3" data-bs-toggle="modal" data-bs-target="#modalAutorizarNegar"><span class="mdi mdi-cancel"></span> Negar ajuste</button>
                <button @click="opcion = 1" type="button" class="btn btn-primary rounded-5" data-bs-toggle="modal" data-bs-target="#modalAutorizarNegar"><span class="mdi mdi-lock-open-check"></span> Autorizar ajuste</button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <!--Alerta asincrona-->
                <div v-if="alerta.m && alerta.t" :class="'alert alert-dismissible fade show alert-'+alerta.t" :style="'border-left: solid 5px #'+borderColorAlert" role="alert">
                    <strong><small>@{{ alerta.m }}</small></strong>
                    <!--Vaciar el objeto de la alerta al hacer click, para evitar error de que la clase no existe-->
                    <button @click="alerta = {}" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div style="border-radius: 12px;" class="card">
                    <div class="card-body">
                        <p style="margin-bottom: 12px;" class="text-uppercase fs-5"><b>Observación:</b> {{ $p->ajusInventario->observacion }}</p>
                        <p style="margin-bottom: 3px;" class="text-uppercase"><b>Lote a modificar:</b> #{{ $p->existencias->id }}</p>
                        <p style="margin-bottom: 3px;" class="text-uppercase"><b>Acción:</b> {{ $p->accion === 1 ? 'Aumentar existencias' : 'Descartar existencias' }}</p>
                        <p style="margin-bottom: 12px;" class="text-uppercase"><b>Cantidad:</b> {{ number_format($p->cantidad, 2) }}</p>
                        <p style="margin-bottom: 3px;" class="text-uppercase"><b>Solicitante:</b> {{ $p->ajusInventario->userSolicitante->name }}</p>
                        <p style="margin-bottom: 3px;" class="text-uppercase"><b>Realizado por:</b> {{ $p->userRealiza->name }}</p>
                        <p style="margin-bottom: 3px;" class="text-uppercase"><b>Fecha de proceso:</b> {{ $p->ajusInventario->fecha_proceso }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!--Modal Autorizar|Negar-->
        <div class="modal fade" id="modalAutorizarNegar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('ajustes_inventarios.authAction') }}" method="POST">
                        @csrf
                        
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 text-uppercase" id="exampleModalLabel">
                                <span>@{{ opcion === 1 ? 'Autorizar ajuste' : 'Negar ajuste' }}</span>
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" class="form-control" :value="opcion" id="opcion" name="opcion">
                            <input type="hidden" class="form-control" value="{{ $p->cid }}" id="ajusteExistenciaId" name="ajusteExistenciaId">

                            <div class="mb-3">
                                <label for="password" class="form-label">Ingrese su contraseña:</label>
                                <input type="password" id="password" name="password" class="form-control rounded-5" placeholder="Escriba aquí su contraseña" autocomplete="off">
                            </div>
    
                            <div class="mb-3">
                                <label for="observacion" class="form-label">Observaciones:</label>
                                <textarea class="form-control rounded-4" id="observacion" name="observacion" rows="3" placeholder="Escriba aqui las observaciones sobre la autorización de este ajuste de inventario."></textarea>
                            </div>
    
                            <div class="form-check">
                                <input  class="form-check-input" type="checkbox" id="flexCheckDefault" name="confirm" value="1">
                                <label class="form-check-label" for="flexCheckDefault">Confirmo la @{{ opcion === 1 ? 'autorización' : 'negación' }} de este ajuste de inventario</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button v-if="opcion === 2" type="submit" class="btn btn-danger rounded-5 me-3"><span class="mdi mdi-cancel"></span> Negar ajuste</button>
                            <button v-else type="submit" class="btn btn-primary rounded-5"><span class="mdi mdi-lock-open-check"></span> Autorizar ajuste</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!--End div App-->
@endsection

@section('script-ajustes-inventarios')
    <script type="module">
        let app = window.appVue({
            data(){
                return {
                    opcion: null,//Opcion de modal: 1 = Autorizar, 2 = Negar

                    //Alerta flotante asincrona
                    alerta: {},//Object
                    timerAlert: null,
                    borderColorAlert: null,
                }
            },
            mounted(){
                //console.log('show ajuste mounted.');
            },
            methods: {
                //Code...
            },
            computed: {
                //Code...
            },
            watch: {
                //Code...
            },
        });
        app.mount('#appShowAjustesInventarios');
    </script>
@endsection
