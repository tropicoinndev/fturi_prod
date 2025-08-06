@extends('layouts.list')

@section('list')
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="w-20">Acciones</th>
                    <th scope="col">#</th>
                    <th scope="col">Sucursal</th>
                    <th scope="col">Logo</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Correo</th>
                    <th scope="col">NIT</th>
                    <th scope="col">NRC</th>
                    <th scope="col">Giro</th>
                    <th scope="col">Código de establecimiento</th>
                    <th scope="col">Municipio</th>
                    <th scope="col">Matríz</th>
                </tr>
            </thead>
            <tbody>
                @forelse($p as $d)
                    <tr>
                        <td>
                            <x-acciones :table="$th['table']" :d="$d" />
                        </td>
                        <th>{{ $loop->index + 1 }}</th>
                        <td>{{ $d->sucursal }}</td>
                        <td>
                            <img src="{{ asset('lgo/' . $d->logo) }}" alt="{{ $d->sucursal }}" style="max-width: 50px; max-height: 50px;">
                        </td>
                        <td>{{ $d->direccion }}</td>
                        <td>{{ $d->telefono }}</td>
                        <td>{{ $d->correo }}</td>
                        <td>{{ $d->nit }}</td>
                        <td>{{ $d->nrc }}</td>
                        <td>{{ $d->giro }}</td>
                        <td>{{ $d->codigo_establecimiento ?? '' }}</td>
                        <td>{{ isset($d->municipios) ? $d->municipios->municipio : '' }}</td>
                        <td>{{ $d->matriz ? 'Si' : 'No' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center">Aun no se han agregado datos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
