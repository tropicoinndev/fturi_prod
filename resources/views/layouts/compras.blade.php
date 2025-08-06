@extends('layouts.app')

@section('content')
    <div class="container shadow panel-body p-4">
        <div class="row">
            <x-message></x-message>
        </div>
        @yield('panel_compras')
    </div>
@endsection
