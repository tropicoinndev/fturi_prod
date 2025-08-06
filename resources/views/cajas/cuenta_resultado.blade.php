@extends('layouts.dtes')
@section('dte_content')
    <style>
        #appCuentas {
            text-transform: uppercase;
        }
    </style>
    <div id="appCuentas">
        @switch($tipo)
            @case(1)
                @include('cajas.cuenta_orden')
            @break

            @case(2)
                @include('cajas.cuenta_recepcion')
            @break

            @case(3)
                @include('cajas.cuenta_comanda')
            @break

            @default
        @endswitch
    </div>
@endsection
