@extends('layouts.app')
@section('content')
    <style>
        body {
            background-color: #B2DFDB;
        }

        .panel {
            min-height: 90vh;
        }

        .cortesia {
            background: #0277BD;
            color: #FAFAFA;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">

            <!-- ** Encabezado de index **-->
            <div class="col-md-12">
                <div class="card panel shadow p-3">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12 col-lg-6 mb-4">
                                {{-- {{ $p }} --}}
                                <h3 class="card-title text-uppercase ">
                                    Autorización de cortesias
                                </h3>
                                <small>
                                    Listado de cortesias que requieren autorización.
                                </small>
                            </div>

                            <!--Mensajes de alerta alerta-->
                            <div class="col-12 col-lg-6">
                                <x-message></x-message>
                            </div>
                            <div class="col-12 text-uppercase mb-2">
                                <b>TITULAR DE LA CORTESÍA:</b> {{ $p->titular->titular }}
                            </div>
                            @if (isset($p->origen) && $p->origen == 2)
                                <div class="col-12 text-uppercase mb-2">
                                    <b>HABITACIÓN:</b> #{{ $p->detalle?->habitaciones?->numero_habitacion }}
                                </div>
                            @endif
                            @if (isset($p->origen) && $p->origen == 2)
                                <div class="col-12 text-uppercase mb-2">
                                    <b>USUARIO:</b>
                                    {{ $p->detalle?->usuarios?->name }}
                                </div>
                            @endif
                            <div class="col-12 text-uppercase mb-2">
                                <b>MONTO UTILIZADO:</b> ${{ number_format($p->titular->consumo, 2) }} en {{ date('m/Y') }}
                            </div>
                            <div class="col-12 text-uppercase mb-2">
                                <b>TIPO DE CUENTA:</b> {{ $p->cuenta }}
                            </div>
                            <div class="col-12 text-uppercase mb-2">
                                <b>{{ $p->cuenta }}:</b> Nº {{ $p->origen_id }}

                                @switch($p->origen)
                                    @case(2)
                                        <a class="btn btn-light"
                                            href="{{ route('recepciones.print', ['id' => Crypt::encryptString($p->origen_id)]) }}"
                                            role="button" target="_blank">
                                            <span class="mdi mdi-printer"></span>
                                            Tarjeta de registro
                                        </a>
                                    @break

                                    @case(3)
                                        <a class="btn btn-light"
                                            href="{{ route('comandas.print', ['id' => Crypt::encryptString($p->origen_id)]) }}"
                                            role="button" target="_blank">
                                            <span class="mdi mdi-printer"></span>
                                            Detalle de comanda
                                        </a>
                                    @break

                                    @default
                                @endswitch
                            </div>
                            @if ($p->observacion)
                                <div class="col-12 text-uppercase mb-2">
                                    <b>Observación:</b>
                                    <span class="text-danger">
                                        {{ $p->observacion }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <a class="btn btn-primary float-end" data-bs-toggle="modal" href="#AutorizaModal"
                                    role="button">
                                    Autorizar cortesía
                                </a>
                                <a class="btn btn-danger float-end me-2" data-bs-toggle="modal" href="#NegarModal"
                                    role="button">
                                    Negar cortesía
                                </a>
                                <h5>DETALLE DE LA CORTESÍA</h5>
                            </div>
                            <div class="col-12 table-responsive my-3">
                                <table class="table table-light">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Cantidad</th>
                                            <th>Conceptos</th>
                                            <th>Unitario</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @switch($p->origen)
                                            @case(1)
                                                @foreach ($p->orden->detalle_orden as $c)
                                                    <tr>
                                                        <th>{{ $c->cantidad }}</th>
                                                        <th>{{ $c->servicios->servicio }}</th>
                                                        <th>${{ number_format($c->precio_unitario, 2) }}</th>
                                                        <th>${{ number_format($c->cantidad * $c->precio_unitario, 2) }}</th>
                                                    </tr>
                                                @endforeach
                                            @break

                                            @case(2)
                                                @if ($p->estadia)
                                                    <tr>
                                                        <th>{{ $p->estadia->dias }} {{ $p->estadia->dias > 1 ? 'días' : 'dia' }}
                                                        </th>
                                                        <th>{{ $p->estadia->tarifas->tarifa }}</th>
                                                        <th>${{ number_format($p->estadia->tarifas->precio, 2) }}</th>
                                                        <th>${{ number_format($p->estadia->dias * $p->estadia->tarifas->precio, 2) }}
                                                        </th>
                                                    </tr>
                                                    @php
                                                        $total += $p->estadia->dias * $p->estadia->tarifas->precio;
                                                    @endphp
                                                @endif
                                            @break

                                            @case(3)
                                                <tr>
                                                    <td colspan="4" class="text-uppercase fw-bolder text-center bg-gray-100">
                                                        Caja {{ $p->comanda->cajas->caja }}
                                                    </td>
                                                </tr>
                                                @foreach ($p->comanda->detalles_comanda as $c)
                                                    <tr>
                                                        <th>{{ $c->cantidad }}</th>
                                                        <th>{{ $c->precios->detalle }}</th>
                                                        <th>${{ number_format($c->precio, 2) }}</th>
                                                        <th>${{ number_format($c->cantidad * $c->precio, 2) }}</th>
                                                    </tr>
                                                    @php
                                                        $total += $c->cantidad * $c->precio;
                                                    @endphp
                                                @endforeach
                                            @break
                                        @endswitch

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Totales</th>
                                            <th>${{ number_format($total, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="AutorizaModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">
                        Autorizar cortesía
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('cortesias.auth_accion') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $p->cid }}">
                    <input type="hidden" name="opcion" value="{{ Crypt::encryptString(1) }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="password" class="form-label">Ingrese su contraseña</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Escriba aquí su contraseña" required />
                        </div>
                        <div class="mb-3">
                            <label for="observacion" class="form-label">Observaciones</label>
                            <textarea name="observacion" class="form-control" id="observacion" rows="3"
                                placeholder="Escriba aquí las observaciones sobre la autorización de esta cortesía."></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="confirm" value="1" id="confirm"
                                required>
                            <label class="form-check-label" for="confirm">
                                Confirmo la autorización de esta cortesía.
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">
                            Autorizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="NegarModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">
                        Negación de cortesía
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('cortesias.auth_accion') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $p->cid }}">
                    <input type="hidden" name="opcion" value="{{ Crypt::encryptString(2) }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="password" class="form-label">Ingrese su contraseña</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Escriba aquí su contraseña" required />
                        </div>
                        <div class="mb-3">
                            <label for="observacion" class="form-label">Observaciones</label>
                            <textarea name="observacion" class="form-control" id="observacion" rows="3" maxlength="200"
                                placeholder="Escriba aquí las observaciones sobre la negación de esta cortesía."></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="confirm" value="1"
                                id="confirmNegar" required>
                            <label class="form-check-label" for="confirmNegar">
                                Confirmo la negación de esta cortesía.
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="submit">
                            Negar cortesía
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
