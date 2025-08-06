@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card" style="min-height: 90vh;">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12 text-uppercase h3">
                                Panel de impresión
                            </div>
                            <div class="col-12">
                                <a class="btn btn-outline-primary" href="{{ $url }}" target="_blank"
                                    role="button">Vista PDF</a>
                                @if ($ruta)
                                    <a class="btn btn-outline-secondary" href="{{ $ruta }}"
                                        role="button">Volver</a>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <embed src="{{ $url }}" style="height: 55em; width: 100%;"></embed>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
