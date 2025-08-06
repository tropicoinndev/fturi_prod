@extends('layouts.dtes')

@section('css-dte')
    <link rel="stylesheet" href="{{ asset('css/json-viewer.css') }}">
@endsection

@section('dte_content')
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-format-list-bulleted h3">Editar Contingencia</span>
            </h3>
            <small>Editar contingencia</small>
            <x-message></x-message>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('dte.contingenciasIndex') }}" class="btn btn-outline-secondary">
                <span class="mdi mdi-arrow-left"></span> Volver
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('dte.contingenciasUpdate') }}" method="POST">
                        @csrf
        
                        <input type="hidden" name="id" value="{{ $p->cid }}"><!--Id encriptado-->

                        <div class="mb-2">
                            <x-input-text name="codigo" label="Codigo:" val="{{ $p->codigo ?? '' }}"/>
                        </div>
                
                        <div class="mb-3">
                            <x-input-text name="valor" label="Valor:" val="{{ $p->valor ?? '' }}"/>
                        </div>
        
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-content-save"></span> Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
