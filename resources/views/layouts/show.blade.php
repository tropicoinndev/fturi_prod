@extends('layouts.app')
@section('style')
    @yield('style-show')
@endsection
@section('content')
    <div id="showApp">
        <div class="container">
            <div class="row justify-content-center">
                <!-- ** Encabezado de index **-->
                <div class="col-md-12">
                    <div class="card p-2">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-12">
                                    <h3 class="card-title text-uppercase">
                                        {{ $th['title'] ?? '' }}
                                    </h3>
                                    <p class="text-uppercase text-muted">
                                        {{ $th['sub'] ?? '' }}
                                    </p>
                                </div>

                                <!--Mensajes de alerta alerta-->
                                <div class="col-12">
                                    <x-message></x-message>
                                </div>


                                <!--Tabla-->
                                @yield('show')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @yield('out')

    </div>
@endsection
@section('script')
    @yield('script-show')
@endsection
