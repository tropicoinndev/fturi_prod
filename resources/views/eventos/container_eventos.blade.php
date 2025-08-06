@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card" style="min-height: 90vh;">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12 text-uppercase h3">
                            Panel de impresion
                        </div>
                        <div class="col-12">
                            <a class="btn btn-outline-primary" href="{{ $url }}" target="_blank" role="button">Vista PDF</a>
                            <a class="btn btn-outline-secondary" href="{{ route('eventos.eventos_reporte') }}" role="button">Volver a eventos</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <iframe src="{{ $url }}" frameborder="0" style="height: 55em; width: 100%;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
