@extends('layouts.show')

@section('style-show')
<style>
    .producto .card {
        height: 85px;
        overflow-x: auto;
    }

    .pointer {
        cursor: pointer;
    }
</style>
@endsection

@section('show')
@if (!$p->producto && !isset($detalle->id) && ($p->categoria->token =="1202" ||$p->categoria->token =="1203" ))
      
    <div class="row">
        <div class="col-12">
            <h3>Agregue la información del detalle del producto</h3>
            <x-detalle_productos-form :table="$th['table']" data="" :producto="$p->id"></x-detalle_productos-form>
        </div>
    </div>
@else
    <section id="app">
        @if (isset($detalle->id))
            <div class="row mb-4"> 
                <div class="col-12">
                    <h4>Información</h4>
                </div>
                <div class="col-2 text-muted">
                    Medida en ml:
                </div>
                <div class="col-10">
                    {{ $detalle->medida_ml }}
                </div>
                 <div class="col-2 text-muted">
                    Onzas:
                </div>
                <div class="col-10">
                    {{ $detalle->onzas }}
                </div>
                 <div class="col-2 text-muted">
                    Perdidas en onza:
                </div>
                <div class="col-10">
                    {{ $detalle->perdida_onzas }}
                </div>
            </div>
              @can('productos.index')
            <a class="btn btn-outline-dark"
                href="{{ route('detalle_productos.edit', ['id'=>\Crypt::encryptString($detalle->id)]) }}"
                role="button">
                <span class="mdi mdi-account-edit h5"></span>
                Editar detalle de producto
            </a>
            @endcan
           
        @endif
    </section>
@endif
@endsection

