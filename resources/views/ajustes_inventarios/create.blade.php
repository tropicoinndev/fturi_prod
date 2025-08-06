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
    <div id="appAjustesInventariosCreate">
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="card-title text-uppercase">
                    <span class="mdi mdi-file-document-plus text-success h2"></span>
                    Crear solicitud
                </h3>
                <x-message></x-message>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <form action="{{ route('solicitud.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="solicitanteUsersId" class="form-label">Usuario solicitante:</label>
                        <select class="form-select rounded-5" id="solicitanteUsersId" name="solicitanteUsersId" aria-label="Default select example" required>
                            <option value="" selected disabled>--Seleccione---</option>
                            @foreach($usuarios as $u)
                                <option class="text-uppercase" value="{{ $u->cid }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="observacion" class="form-label">Observación de ajuste:</label>
                        <textarea class="form-control rounded-4" id="observacion" name="observacion" rows="3" placeholder="Escriba aqui..."></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary rounded-5"><span class="mdi mdi-content-save"></span> Crear solicitud</button>
                    </div>
                </form>
            </div>
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
                console.log('Ajustes create mounted.');
            },
            methods: {
                
            },
        }).
        mount("#appAjustesInventariosCreate");
    </script>
@endsection
