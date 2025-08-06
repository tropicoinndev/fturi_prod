@extends('layouts.dtes')


@section('dte_content')
    <div class="row">
        <div class="col-12 col-lg-6">
            <h3 class="card-title text-capitalize">
                {{ $th['title'] ?? 'Listado' }}
            </h3>
            <p class="text-uppercase text-muted">
                {{ $th['sub'] ?? '' }}
            </p>
        </div>
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
                @endif
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <form action="{{ route($th['table'] . '.search') }}" method="post">
                    @csrf
                    <input type="text" class="form-control" placeholder="Buscar..." id="txtBusqueda" name="txtBusqueda"
                        value="{{ $txtBusqueda ?? '' }}" autocomplete="off">
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
