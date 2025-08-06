@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #80DEEA !important;
        }

        .clientes-naturales td {
            background: #FAFAFA !important;
        }

        .clientes-juridicos td {
            background: #FAFAFA !important;
        }

        .clientes-disabled td {
            background: #FBE9E7 !important;
        }

        .clientes td {
            padding: 15px !important;
        }
    </style>
@endsection

@section('content_cliente')
    <div class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 col-lg-6">
                    <h3 class="card-title text-capitalize">{{ $th['title'] ?? 'Listado' }}</h3>
                    <p class="text-uppercase text-muted">{{ $th['sub'] ?? '' }}</p>
                </div>

                {{--Alerta--}}
                <div class="col-12 col-lg-6"><x-message></x-message></div>

                {{--Botón agregar y caja de búsqueda--}}
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
                        
                        @if(isset($p) && count($p) > 0)
                            <a href="{{ route($th['table'].'.exportData', ['extention'=>Crypt::encryptString(1)]) }}" class="btn btn-outline-success">
                                Exportar a Excel
                            </a>

                            <a href="{{ route($th['table'].'.exportData', ['extention'=>Crypt::encryptString(2)]) }}" class="btn btn-outline-success">
                                Exportar a CSV
                            </a>
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

                {{--Tabla--}}
                <div class="table-responsive mb-3" style="min-height: 50vh;">
                    <table class="table table-hover table-borderless">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Acciones</th>
                                <th scope="col">#</th>
                                <th scope="col">Nombres</th>
                                <th scope="col">Apellidos</th>
                                <th scope="col">Alias</th>
                                <th scope="col">Nº de identificación</th>
                                <th scope="col">P. Buscada</th>
                                <th scope="col">PEPS</th>
                                <th scope="col">Estado alerta</th>
                                <th scope="col">Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($p as $d)
                                <tr>
                                    <td>
                                        <x-acciones :table="$th['table']" :d="$d"/>
                                    </td>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ $d->nombres }}</td>
                                    <td>{{ $d->apellidos }}</td>
                                    <td>{{ $d->alias ?? '---' }}</td>
                                    <td>{{ $d->numero_identificacion }}</td>
                                    <td>{{ $d->ilicita ? 'Persona relacionada a ilicitos' : 'Persona No relacionada a ilicitos' }}</td>
                                    <td>{{ $d->peps ? 'Persona expuesta politicamente' : 'Persona No expuesta politicamente' }}</td>
                                    <td>{{ $d->estado ? 'Activada' : 'Desactivada' }}</td>
                                    <td>{{ $d->usuarios->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Aun no se han agregado datos para mostrar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{--Paginación--}}
                @if(isset($p) && $p instanceof \Illuminate\Pagination\LengthAwarePaginator && $p->links() != null)
                    <div class="col-12">
                        {{ $p->links() }}
                    </div>
                @endif
                @if(isset($data) && $data instanceof \Illuminate\Pagination\LengthAwarePaginator && $data->links() != null)
                    <div class="col-12">
                        {{ $data->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
