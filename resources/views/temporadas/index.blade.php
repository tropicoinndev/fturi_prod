@extends('layouts.list')

@section('list')

<div class="table-responsive">
  <table class="table table-hover">
    <thead class="table-light">
      <tr>
        <th scope="col">Acciones</th>
        <th scope="col">#</th>
        <th scope="col">Temporada</th>
        <th scope="col">Fecha Inicio</th>
        <th scope="col">Fecha Finalizacion</th>
        <th scope="col">Estado</th>
        
      </tr>
    </thead>
    <tbody>
      @forelse($p as $d)
      <tr>
        <td>
          <x-acciones :table="$th['table']" :d="$d" />
        </td>

        <th>{{ $loop->index + 1 }}</th>
        <td>{{ $d->temporada }}</td>
        <td>{{$d->fecha_inicio}}</td>
        <td>{{$d->fecha_finalizacion}}</td>
        <td>
          @if($d->estado)
          <span class="text-success">Activo</span>
          @else
          <span class="text-danger">Inactivo</span>
          @endif
        </td>

      </tr>
      @empty
      <tr>
        <td colspan="3">Aun no se han agregado datos.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
