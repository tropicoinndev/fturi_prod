@extends('layouts.ajustes_inventarios')

@section('style-ajustes-inventarios')
    <style>

    </style>
@endsection

@section('ajustes_inventarios_content')
    <div id="appAutorizarDetalle">
        <div class="row">
            <div class="col-12">
                <h3 class="card-title text-uppercase mb-3">
                    <span class="mdi mdi-file-eye text-success h2"></span>
                    Solicitud de Ajuste #{{ $ajusteInv->id }}
                </h3>
                <x-message></x-message>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-6">
                <a href="{{ route('ajustes_inventarios.autorizar') }}" class="btn btn-outline-secondary rounded-5 mb-3"><span class="mdi mdi-arrow-left"></span> Volver</a>
            </div>
            <div class="col-6 text-end">
                <button @click="opcion = 2" type="button" class="btn btn-outline-danger rounded-5 me-3" data-bs-toggle="modal" data-bs-target="#modalAutorizarNegar"><span class="mdi mdi-cancel"></span> Negar ajuste</button>
                <button @click="opcion = 1" type="button" class="btn btn-primary rounded-5" data-bs-toggle="modal" data-bs-target="#modalAutorizarNegar"><span class="mdi mdi-lock-open-check"></span> Autorizar ajuste</button>
            </div>
        </div>

        <div class="row text-uppercase mb-4">
            <div class="col-2"><b>Usuario solicitante: </b></div>
            <div class="col-10">{{ $ajusteInv->userSolicitante->name }}</div>

            <div class="col-2"><b>Realizado por: </b></div>
            <div class="col-10">{{ $ajusteInv->userRealiza->name }}</div>
            
            <div class="col-2"><b>Fecha de proceso: </b></div>
            <div class="col-10 text-muted">{{ $ajusteInv->fecha_proceso }}</div>

            <div class="col-2"><b>Observación: </b></div>
            <div class="col-10">{{ $ajusteInv->observacion ?? '---' }}</div>

            <div class="col-2"><b>Estado: </b></div>
            <div class="col-10">
                @if($ajusteInv->estado)
                    <span class="text-success">Activo</span>
                @else
                    <span class="text-danger">Inactivo</span>
                @endif
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
                            <input type="hidden" class="form-control" value="{{ $ajusteInv->cid }}" id="ajusteInventarioId" name="ajusteInventarioId">

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
                }
            },
            mounted(){
                //console.log('Detalles mounted.');
            },
            methods: {
                
            },
            computed: {

            },
            watch: {

            },
        });
        app.mount('#appAutorizarDetalle');
    </script>
@endsection
