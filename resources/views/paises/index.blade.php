@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Países</th>
                <th scope="col">Nacionalidad</th>
                <th scope="col">Código Hacienda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
                <tr>
                    <td><x-acciones :table="$th['table']" :d="$d" /></td>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $d->pais }}</td>
                    <td>{{ $d->nacionalidad }}</td>
                    <td>{{ $d->codigo_mh }}</td>
                </tr>
            @empty
                <tr><td colspan="8">Aun no se han agregado datos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
