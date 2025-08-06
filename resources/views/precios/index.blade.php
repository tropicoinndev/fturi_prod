@extends('layouts.list')

@section('list')

<div class="table-responsive">
  <table class="table table-hover">
    <thead class="table-light">
      <tr>
        <th scope="col">Acciones</th>
        <th scope="col">#</th>
        <th scope="col">Detalle</th>
        <th scope="col">Precio</th>
        <th scope="col">Iva</th>
        <th scope="col">Advalorem</th>
        <th scope="col">Sugerido</th>
        <th scope="col">Propina</th>
        <th scope="col">Categorías Precios</th>
        <th scope="col">Cajas</th>
        <th scope="col">Descuentos</th>
        <th scope="col">Constante</th>
        <th scope="col">Fecha Inicio</th>
        <th scope="col">Fecha Final</th>
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
        <td>{{ $d->detalle }}</td>
        <td>$ {{ number_format($d->precio, 2) }}</td>
        <td>
          @if($d->iva)
          <span class="badge bg-success">Si</span>
          @else
          <span class="badge bg-danger">No</span>
          @endif
        </td>
        <td>
          @if($d->advalorem)
          <span class="badge bg-success">Si</span>
          @else
          <span class="badge bg-danger">No</span>
          @endif
        </td>
        <td>$ {{ number_format($d->sugerido, 2) }}</td>
        <td>
          @if($d->propina)
          <span class="badge bg-success">Si</span>
          @else
          <span class="badge bg-danger">No</span>
          @endif
        </td>
        <td>{{ $d->categorias_precios->categoria}}</td>
        <td class="text-uppercase">
             @if($d->precio_cajas->isEmpty())
                No está asignado a cajas.
            @else
                {{ $d->precio_cajas->pluck('cajas.caja')->implode(', ') }}

            @endif
        </td>
        <td>
          @if($d->descuento)
          <span class="badge bg-success">Si</span>
          @else
          <span class="badge bg-danger">No</span>
          @endif
        </td>
        <td>
          @if($d->constante)
          <span class="badge bg-success">Si</span>
          @else
          <span class="badge bg-danger">No</span>
          @endif
        </td>
        <td>{{ $d->fecha_inicio}}</td>
        <td>{{ $d->fecha_final}}</td>

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
