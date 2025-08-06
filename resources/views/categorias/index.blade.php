@section('style')
    <style>

    </style>
@endsection

@extends('layouts.list')

@section('list')
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">Acciones</th>
                    <th scope="col">#</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Token</th>
                    <th scope="col">Fecha creación</th>
                    <th scope="col">Fecha edición</th>
                </tr>
            </thead>
            <tbody>
                @forelse($p as $d)
                    <tr>
                        <td><x-acciones :table="$th['table']" :d="$d"/></td>
                        <th>{{ $loop->index + 1 }}</th>
                        <td>{{ $d->categoria }}</td>
                        <td>
                            @if($d->estado)
                                <span class="text-success">Activo</span>
                            @else
                                <span class="text-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            @if($d->token =="1201")
                                <span class="text-success">Venta</span>
                            @elseif($d->token =="1202")
                                <span class="text-success">Producción</span>
                            @elseif($d->token =="1203")
                                <span class="text-success">Ventas-producción</span>
                            @endif
                        </td>
                        <td><span class="text-muted">{{ $d->created_at }}</span></td>
                        <td><span class="text-muted">{{ $d->updated_at }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="3">Aun no se han agregado datos.</td></tr>
                @endforelse
            </tbody>
        </table>
        
    </div>
@endsection


@section('script')
    <script>

    </script>
@endsection
