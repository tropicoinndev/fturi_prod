@extends('layouts.list')

@section('list')
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">Acciones</th>
                    <th scope="col">#</th>
                    <th scope="col">Tarifa</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Numero de dias</th>
                    <th scope="col">Paquete</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Temporada</th>

                </tr>
            </thead>
            <tbody>
                @forelse($p as $d)
                    <tr>
                        <td>
                            <x-acciones :table="$th['table']" :d="$d" />
                        </td>

                        <th>{{ $d->id }}</th>
                        <td>{{ $d->tarifa }}

                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop" onclick="copiarTexto('{{ $d->tarifa }}')">
                                <span class="mdi mdi-content-copy"></span>
                            </button>
                        </td>
                        <td>$ {{ number_format($d->precio, 2) }}</td>
                        <td> {{ $d->numero_dias }}</td>
                        <td>
                            @if ($d->paquete)
                                <span class="text-success">Incluye comida y bebida</span>
                            @else
                                <span class="text-danger">No incluye comida ni bebida</span>
                            @endif
                        </td>
                        <td>
                            @if ($d->estado)
                                <span class="text-success">Activo</span>
                            @else
                                <span class="text-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>{{ $d->temporadas->temporada }}</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Aun no se han agregado datos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
        function copiarTexto(texto) {
            let tarifa = document.getElementById('tarifa').value = texto;
        }
    </script>
@endsection
