@extends('layouts.cajas')

@section('panel_caja')
    <div class="col-12">
        <h3>INGRESOS A CAJA</h3>
        <x-message />
    </div>
    <div class="col-12">
        <div class="d-flex">
            <div class="col">
                <div class="mb-3">
                    <a href="{{ route('abonos.create') }}" class="btn btn-primary">Nuevo</a>
                </div>
            </div>
            <div class="col">
                <form action="{{ route('abonos.search') }}" method="POST">
                    @csrf
                    <input type="number" name="buscar" id="" class="form-control"
                        placeholder="Buscar por correlativo" aria-describedby="helpId" />
                </form>
            </div>
        </div>

    </div>
    <div class="table-responsive col-12">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">Acciones</th>
                    <th scope="col">#</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Monto</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Usuario</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $d)
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <span class="mdi mdi-cog"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <span class="text-mutted p-3">
                                            Opciones
                                        </span>
                                    </li>
                                    @if (!$d->estado)
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('abonos_detalles.create', ['id' => \Crypt::encryptString($d->id)]) }}">
                                                Editar detalle
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger"
                                                href="{{ route('abonos.confirm', ['id' => \Crypt::encryptString($d->id)]) }}">
                                                Eliminar
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('abonos.container', ['id' => \Crypt::encryptString($d->id)]) }}">
                                                Imprimir
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                        <td>
                            {{ $d->id }}
                        </td>
                        <td>
                            {{ $d->clientes->nombre }}
                        </td>
                        <td class="text-end">
                            ${{ number_format($d->monto, 2) }}
                        </td>
                        <td>
                            {{ $d->estado ? 'Completado' : 'Aun sin completar' }}
                        </td>
                        <td class="text-uppercase">
                            {{ $d->users->user }}
                        </td>
                    </tr>
                @empty
                    <div class="alert alert-info" role="alert">
                        Este turno no tiene ningun ingreso a caja, para agregar presione en el botón <a
                            href="{{ route('abonos.create') }}" class="btn btn-sm btn-primary mx-2">Nuevo</a>
                    </div>
                @endforelse
            </tbody>
        </table>
        {{ $data->links() }}
    </div>
@endsection
