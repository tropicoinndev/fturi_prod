@extends('layouts.cajas')
@section('panel_caja')
    <div id="appCobros">
        <div class="container" style="min-height: 80vh;">
            <div class="row mb-4">
                <div class="col-12">
                    <h3>
                        Cobros
                    </h3>
                    <div class="mt-2">
                        Listado de cobros configurados
                    </div>
                </div>
                <div class="col-12 my-3">
                    <form action="{{ route('cobros.search') }}" method="post" class="d-flex">
                        @csrf

                        <div class="col">
                            <input type="text" class="form-control" name="buscar" id="buscar"
                                placeholder="Buscar por nombre/titular" autocomplete="off" value="{{ $buscar ?? '' }}">
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary ms-3">Buscar</button>
                        </div>
                    </form>

                </div>
            </div>
            <div class="row">
                @forelse ($p as $c)
                    <div class="col-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <small class="float-end">Nº {{ $c->id }}</small>
                                <h5 class="card-title text-truncate">
                                    {{ $c->titular ?? ($c->clientes->nombre ?? 'NO SE AGREGO CLIENTE / TITULAR') }}
                                </h5>
                                <p class="card-text">
                                    {{ $c->detalleCobros->count() }} concepto(s)
                                </p>
                                <p class="card-text">
                                    <a href="{{ route('cobros.create_config', ['id' => Crypt::encryptString($c->id)]) }}"
                                        class="btn btn-primary">Configurar pago</a>
                                    <a href="{{ route('comprobantes.cobro', ['id' => Crypt::encryptString($c->id)]) }}"
                                        class="btn btn-light text-success">Comprobante</a>
                                    <a href="{{ route('cobros.confirm', ['id' => Crypt::encryptString($c->id)]) }}"
                                        class="btn btn-light text-danger">Eliminar</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 mb-3">
                        Aun no se han agregado cobros. Revise las solicitudes de comprobante, para crear un nuevo cobro.
                    </div>
                @endforelse


            </div>
        </div>
    </div>
@endsection
