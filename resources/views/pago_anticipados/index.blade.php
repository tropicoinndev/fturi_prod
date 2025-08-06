@extends('layouts.cajas')

@section('panel_caja')
    <div class="col-12">
        <form action="{{ route($th['table'] . '.search') }}" method="post">
            @csrf
            <input type="text" class="form-control" placeholder="Buscar por clientes ..." id="txtBusqueda" name="txtBusqueda"
                value="{{ $txtBusqueda ?? '' }}" autocomplete="off">
        </form>

    </div>
    <div class="table-responsive col-12">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">Acciones</th>
                    <th scope="col">#</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Forma de pago</th>
                    <th scope="col">Monto actual</th>
                    <th scope="col">Monto creacion</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Usuario</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($p as $d)
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
                                    @if ($d->cobros->count() > 0)
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('pago_anticipados.confirm', ['id' => \Crypt::encryptString($d->id)]) }}">
                                                Eliminar
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <span class="text-mutted p-3">
                                                No es posible realizar acciones en pagos anticipados
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                        <td>
                            {{ $d->id }}
                        </td>
                        <td>
                            {{ $d->fecha }}
                        </td>
                        <td>
                            {{ $d->clientes->nombre }}
                        </td>
                        <td>
                            {{ $d->forma_pagos->forma }}
                        </td>
                        <td class="text-end">
                            ${{ number_format($d->monto, 2) }}
                        </td>
                        <td class="text-end">
                            ${{ number_format($d->monto_historico, 2) }}
                        </td>
                        <td>
                            {{ optional($d)->cobros->count() > 0 ? 'Con comprobante' : 'Aun sin comprobante' }}
                        </td>
                        <td class="text-uppercase">
                            {{ $d->usuarios->name }}
                        </td>
                    </tr>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Aun no se han agregado datos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $p->links() }}
    </div>
@endsection
