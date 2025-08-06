@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <!-- ** Encabezado de index **-->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12 col-lg-6">
                                <h3 class="card-title text-capitalize">
                                    {{ $th['title'] ?? 'Listado' }}
                                </h3>
                                <p class="text-uppercase text-muted">
                                    {{ $th['sub'] ?? '' }}
                                </p>
                            </div>

                            <!--Mensajes de alerta alerta-->
                            <div class="col-12 col-lg-6">
                                <x-message></x-message>
                            </div>
                            <!--Boton agregar y caja de busqueda-->
                            <div class="row mb-3">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-2">
                                    @if (isset($th['btnAdd']) && $th['btnAdd'])
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop">
                                            <span class="mdi mdi-plus"></span> Agregar
                                        </button>

                                    @elseif (Route::has($th['table'] . '.create'))
                                        <a href="{{ route($th['table'] . '.create') }}" class="btn btn-outline-primary">
                                            <span class="mdi mdi-plus"></span> Agregar
                                        </a>

                                        <!--Este botón unicamente se mostrará en la vista de 'precios'-->
                                        <!--Desde el controlador de precios se envía la bandera 'showBtnPrecioCajas' en true-->
                                        @can('precios.cajas')
                                            @isset($showBtnPrecioCajas)
                                                <a href="{{ route('precios.precioCajasIndex') }}" class="btn btn-outline-secondary ms-3">
                                                    <span class="mdi mdi-plus"></span> Asignar precios a cajas
                                                </a>
                                            @endisset
                                        @endcan
                                    @endif
                                </div>

                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <form action="{{ route($th['table'] . '.search') }}" method="post">
                                        @csrf
                                        <input type="text" class="form-control" placeholder="Buscar..." id="txtBusqueda"
                                            name="txtBusqueda" value="{{ $txtBusqueda ?? '' }}" autocomplete="off">
                                    </form>
                                </div>
                            </div>

                            <!--Tabla-->
                            @yield('list')

                            @if (isset($p) && $p instanceof \Illuminate\Pagination\LengthAwarePaginator && $p->links() != null)
                                <div class="col-12">
                                    {{ $p->links() }}
                                </div>
                            @endif
                            @if (isset($data) && $data instanceof \Illuminate\Pagination\LengthAwarePaginator && $data->links() != null)
                                <div class="col-12">
                                    {{ $data->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (isset($th['btnAdd']) && $th['btnAdd'])
            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 800px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel"><span class="mdi mdi-plus"></span> Agregar
                                {{ $th['title'] }}</h1>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <x-dynamic-component :component="$th['table'] . '-form'" :table="$th['table']" :data="$data ?? ''" />
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endsection
