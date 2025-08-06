@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #80DEEA !important;
        }

        .cliente .card {
            height: 85px;
            overflow-x: auto;
        }

        .pointer {
            cursor: pointer;
        }

        button.disabled {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('content_cliente')
    <div id="showApp">
        <div class="container">
            <div class="row justify-content-center">

                {{-- Encabezado de index --}}
                <div class="col-md-12">
                    <div class="card-body p-2">
                        <div class="row mb-2">
                            <div class="col-12">
                                <h3 class="card-title text-uppercase">
                                    {{ $th['title'] ?? '' }}
                                </h3>
                                <p class="text-uppercase text-muted">
                                    {{ $th['sub'] ?? '' }}
                                </p>
                            </div>

                            {{-- Alerta --}}
                            <div class="col-12">
                                <x-message></x-message>
                            </div>

                            {{-- Información de contribuyente --}}
                            @if (!$p->tipo_cliente && !isset($detalle->id))
                                <div class="row">
                                    <div class="col-12">
                                        <h3>Agregue la información del contribuyente</h3>
                                        <x-contribuyente-form :table="$th['table']" data=""
                                            :cliente="$p->id"></x-contribuyente-form>
                                    </div>
                                </div>
                            @else
                                <section id="app-component">
                                    <div class="row mb-4">
                                        <div class="col-12 mb-2">

                                            {{-- Alerta de activar|desactivar cliente --}}
                                            @if (!$p->tipo_cliente)
                                                @if ($clIdenti->count() > 0 && $clContacto->count() > 0 && !$p->estado)
                                                    <div class="alert alert-info" role="alert">
                                                        Ya puede activar este cliente. Presione sobre <b>Activar cliente<b>.
                                                    </div>
                                                @elseif ($clIdenti->count() == 0 && $clContacto->count() == 0 && !$p->estado)
                                                    <div class="alert alert-warning" role="alert">
                                                        Aun no se puede activar este cliente. Debe tener registrado al menos
                                                        una identificación y un
                                                        contacto.
                                                    </div>
                                                @endif
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            @if ($p->estado && $clIdenti->count() > 0 && $clContacto->count() > 0 && isset($detalle->id))

                                                {{-- Dropdown: Activar|Desactivar Exento --}}
                                                @can('clientes.create')
                                                    <button type="button"
                                                        class="btn bg-light border border-1 border-dark dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Exento
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('detalle_contribuyentes.exento', [
                                                                    'id' => \Crypt::encryptString($detalle->id),
                                                                    'tipo' => \Crypt::encryptString(1),
                                                                ]) }}">
                                                                @if ($detalle->exento)
                                                                    <span class="mdi mdi-account-cancel text-danger h5"></span>
                                                                    Desactivar exento de todo
                                                                @else
                                                                    <span class="mdi mdi-account-badge text-success h5"></span>
                                                                    Exento de todo
                                                                @endif
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @endcan

                                                {{-- Dropdown: Opciones Periodos de créditos --}}
                                                @can('clientes.create')
                                                    <button class="btn bg-light border border-1 border-dark dropdown-toggle"
                                                        type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <span class="mdi mdi-cog"></span> Opciones del cliente
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        @can('clientes.credito')
                                                            <li>
                                                                <a class="dropdown-item text-{{ !$p->credito ? 'success' : 'danger' }}"
                                                                    href="{{ route('clientes.credito', ['id' => \Crypt::encryptString($p->id)]) }}">
                                                                    @if (!$p->credito)
                                                                        <span class="mdi mdi-account-credit-card h5"></span>
                                                                        Permitir crédito
                                                                    @else
                                                                        <span class="mdi mdi-account-credit-card-outline h5"></span>
                                                                        No permitir crédito
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('clientes.credito')
                                                            <li>
                                                                <a class="dropdown-item text-{{ !$p->ccf ? 'success' : 'danger' }}"
                                                                    href="{{ route('clientes.ccf', ['id' => \Crypt::encryptString($p->id)]) }}">
                                                                    @if (!$p->ccf)
                                                                        <span class="mdi mdi-file-document-check-outline h5"></span>
                                                                        Permitir crédito fiscal
                                                                    @else
                                                                        <span class="mdi mdi-account-credit-card-outline h5"></span>
                                                                        No permitir crédito fiscal
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('clientes.create')
                                                            <li>
                                                                <a class="dropdown-item text-{{ !$p->descuento ? 'success' : 'danger' }}"
                                                                    href="{{ route('clientes.descuento', ['id' => \Crypt::encryptString($p->id)]) }}">
                                                                    @if (!$p->descuento)
                                                                        <span class="mdi mdi-cart-percent h5"></span>
                                                                        Permitir descuento
                                                                    @else
                                                                        <span class="mdi mdi-cart-percent h5"></span>
                                                                        No permite descuento
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('clientes.create')
                                                            <li>
                                                                <a class="dropdown-item text-{{ !$detalle->percepcion ? 'success' : 'danger' }}"
                                                                    href="{{ route('clientes.retencion', ['id' => \Crypt::encryptString($detalle->id)]) }}">
                                                                    @if (!$detalle->percepcion)
                                                                        <span class="mdi mdi-account-credit-card h5"></span>
                                                                        Activar retención
                                                                    @else
                                                                        <span class="mdi mdi-account-credit-card-outline h5"></span>
                                                                        Desactivar retención
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('clientes.accionista')
                                                            <li>
                                                                <a class="dropdown-item text-{{ !$p->accionista ? 'success' : 'danger' }}"
                                                                    href="{{ route('clientes.accionista', ['id' => \Crypt::encryptString($p->id)]) }}">
                                                                    @if (!$p->accionista)
                                                                        <span class="mdi mdi-account-credit-card h5"></span>
                                                                        Activar como accionista
                                                                    @else
                                                                        <span class="mdi mdi-account-credit-card-outline h5"></span>
                                                                        Desactivar como accionista
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                @endcan

                                                {{-- Botón: Editar información --}}
                                                @can('clientes.update')
                                                    <a class="btn btn-outline-dark"
                                                        href="{{ route('detalle_contribuyentes.edit', ['id' => \Crypt::encryptString($detalle->id)]) }}"
                                                        role="button">
                                                        <span class="mdi mdi-account-edit h5"></span>
                                                        Editar información
                                                    </a>
                                                @endcan

                                                {{-- Botón: Agregar sucursales --}}
                                                @can('clientes.create')
                                                    <a class="btn btn-outline-dark"
                                                        href="{{ route('clientes.juridico_sucursal', ['id' => \Crypt::encryptString($p->id)]) }}"
                                                        role="button">
                                                        <span class="mdi mdi-account-plus h5"></span>
                                                        Agregar sucursales cliente
                                                    </a>
                                                @endcan
                                            @endif

                                            {{-- Botones: Activar cliente según el tipo de cliente --}}
                                            @if (!$p->tipo_cliente && $clIdenti->count() > 0 && $clContacto->count() > 0 && !$p->estado)
                                                @can('clientes.create')
                                                    <a class="btn btn-outline-success"
                                                        href="{{ route('clientes.state', ['id' => \Crypt::encryptString($p->id)]) }}"
                                                        role="button">
                                                        <span class="mdi mdi-account-badge"></span>
                                                        Activar cliente
                                                    </a>
                                                @endcan
                                            @endif
                                            @if ($p->tipo_cliente && !$p->estado)
                                                @can('clientes.create')
                                                    <a class="btn btn-outline-success"
                                                        href="{{ route('clientes.state', ['id' => \Crypt::encryptString($p->id)]) }}"
                                                        role="button">
                                                        <span class="mdi mdi-account-badge"></span>
                                                        Activar cliente
                                                    </a>
                                                @endcan
                                            @endif

                                            @if ($p->estado)
                                                @if ($p->tipo_cliente)
                                                    <button
                                                        class="btn bg-light border border-1 border-dark  dropdown-toggle"
                                                        type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <span class="mdi mdi-cog"></span> Opciones del cliente
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        @can('clientes.credito')
                                                            <li>
                                                                <a class="dropdown-item text-{{ !$p->credito ? 'success' : 'danger' }}"
                                                                    href="{{ route('clientes.credito', ['id' => \Crypt::encryptString($p->id)]) }}">
                                                                    @if (!$p->credito)
                                                                        <span class="mdi mdi-account-credit-card h5"></span>
                                                                        Permitir crédito
                                                                    @else
                                                                        <span
                                                                            class="mdi mdi-account-credit-card-outline h5"></span>
                                                                        No permitir crédito
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('clientes.create')
                                                            <li>
                                                                <a class="dropdown-item text-{{ !$p->descuento ? 'success' : 'danger' }}"
                                                                    href="{{ route('clientes.descuento', ['id' => \Crypt::encryptString($p->id)]) }}">
                                                                    @if (!$p->descuento)
                                                                        <span class="mdi mdi-cart-percent h5"></span>
                                                                        Permitir descuento
                                                                    @else
                                                                        <span class="mdi mdi-cart-percent h5"></span>
                                                                        No permite descuento
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                @endif

                                                @can('clientes.create')
                                                    <a class="btn btn-outline-danger"
                                                        href="{{ route('clientes.state', ['id' => \Crypt::encryptString($p->id)]) }}"
                                                        role="button">
                                                        <span class="mdi mdi-account-badge-outline"></span>
                                                        Desactivar cliente
                                                    </a>
                                                @endcan
                                            @endif

                                            {{-- Botón modal para Actividad económica --}}
                                            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal"
                                                data-bs-target="#actividadEconomica">
                                                <span class="mdi mdi-cash-edit h5"></span>
                                                {{ $p->actividades_economicas_id != null ? 'Editar' : 'Agregar' }}
                                                actividad económica
                                            </button>
                                        </div>
                                    </div>

                                    @php
                                        $categoriaCliente = 'Sin categorizar';
                                    @endphp

                                    @foreach ($categorias as $cat)
                                        @if ($cat['id'] == $p->categoria)
                                            @php
                                                $categoriaCliente = $cat['categoria'];
                                            @endphp
                                        @endif
                                    @endforeach

                                    {{-- Panel de información --}}
                                    @if ($p->tipo_cliente)
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h4>Información</h4>
                                            </div>
                                            <div class="col-2 text-muted">
                                                Dirección:
                                            </div>
                                            <div class="col-10">
                                                {{ $p->direccion }}
                                                @if ($p->municipios)
                                                    | {{ $p->municipios->municipio ?? 'Sin municipio asignado' }},
                                                    {{ $p->municipios->departamentos->departamento ?? 'Sin departamento asignado' }},
                                                    {{ $p->municipios->departamentos->paises->pais ?? 'Sin país asignado' }}
                                                @elseif($p->extranjero)
                                                    | {{ $p->extranjero->pais }}
                                                @else
                                                    | Sin ubicación asignada
                                                @endif
                                            </div>
                                            <div class="col-2 text-muted">
                                                Categoría del cliente:
                                            </div>
                                            <div class="col-10">
                                                {{ $categoriaCliente }}
                                            </div>
                                            <div class="col-2 text-muted">
                                                Actividad del cliente:
                                            </div>
                                            <div class="col-10">
                                                {{ $p->actividades->actividad ?? 'Sin actividad asignada' }}
                                            </div>
                                            <div class="col-2 text-muted">
                                                Email:
                                            </div>
                                            <div class="col-10">
                                                {{ $p->email ?? 'No se ha agregado email' }}
                                            </div>
                                            <div class="col-2 text-muted">
                                                Crédito:
                                            </div>
                                            @if ($p->credito)
                                                <div class="col-10">
                                                    Este cliente si permite crédito.
                                                </div>
                                            @else
                                                <div class="col-10">
                                                    No es permitido crédito a este cliente.
                                                </div>
                                            @endif
                                            @if ($p->credito && $p->periodosCreditos)
                                                <div class="col-2 text-muted">
                                                    Periodo del crédito:
                                                </div>
                                                <div class="col-10">
                                                    {{ $p->periodosCreditos->periodo ?? '---' }} ·
                                                    ({{ $p->periodosCreditos->dias ?? '---' }}
                                                    días)
                                                </div>
                                            @endif
                                            <div class="col-2 text-muted">
                                                Descuento:
                                            </div>
                                            @if ($p->descuento)
                                                <div class="col-10">
                                                    Este cliente tiene permitido descuentos.
                                                </div>
                                            @else
                                                <div class="col-10">
                                                    No es permitido descuentos a este cliente.
                                                </div>
                                            @endif
                                            <div class="col-2 text-muted">
                                                Observaciones:
                                            </div>
                                            <div class="col-10">
                                                {{ $p->observaciones ?? '---' }}
                                            </div>
                                            <div class="col-2 text-muted">
                                                Ultima modificación:
                                            </div>
                                            <div class="col-10">
                                                {{ $p->updated_at }}
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Panel de detalles --}}
                                    @if (isset($detalle->id))
                                        <div class="row mb-5 ">
                                            <div class="col-12">
                                                <h4>Información</h4>
                                            </div>
                                            <div class="col-2 text-muted">
                                                Nombre jurídico:
                                            </div>
                                            <div class="col-10">
                                                {{ $detalle->juridico }}
                                            </div>
                                            <div class="col-2 text-muted">
                                                Dirección:
                                            </div>
                                            <div class="col-10">
                                                {{ $p->direccion }} |
                                                {{ $p->municipios->municipio ?? $p->extranjero?->pais }},
                                                {{ $p->municipios->departamentos->departamento ?? 'Sin departamento asignado' }},
                                                {{ $p->municipios->departamentos->paises->pais ?? 'Sin país asignado' }}
                                            </div>
                                            <div class="col-2 text-muted">
                                                Categoría del cliente:
                                            </div>
                                            <div class="col-10">
                                                {{ $categoriaCliente }}
                                            </div>
                                            <div class="col-2 text-muted">
                                                Actividad del cliente:
                                            </div>
                                            <div class="col-10">
                                                {{ $p->actividades->actividad ?? '' }}
                                            </div>
                                            <div class="col-2 text-muted">
                                                Email:
                                            </div>
                                            <div class="col-10">
                                                {{ $p->email ?? 'No se ha agregado email' }}
                                            </div>
                                            <div class="col-2 text-muted">
                                                Nrc:
                                            </div>
                                            @if ($detalle && $detalle->nrc)
                                                <div class="col-10">
                                                    {{ $detalle->nrc }}
                                                </div>
                                            @else
                                                <div class="col-10">
                                                    Es exento no es necesario nrc .
                                                </div>
                                            @endif
                                            <div class="col-2 text-muted">
                                                Exento:
                                            </div>
                                            @if ($detalle->exento)
                                                <div class="col-10">
                                                    Es exento de todos los impuestos.
                                                </div>
                                            @else
                                                <div class="col-10">
                                                    No es exento de impuestos.
                                                </div>
                                            @endif
                                            <div class="col-2 text-muted">
                                                Crédito:
                                            </div>
                                            @if ($p->credito)
                                                <div class="col-10">
                                                    Este cliente si permite crédito.
                                                </div>
                                            @else
                                                <div class="col-10">
                                                    No es permitido crédito a este cliente.
                                                </div>
                                            @endif
                                            <div class="col-2 text-muted">
                                                Descuento:
                                            </div>
                                            @if ($p->descuento)
                                                <div class="col-10">
                                                    Este cliente tiene permitido descuentos.
                                                </div>
                                            @else
                                                <div class="col-10">
                                                    No es permitido descuentos a este cliente.
                                                </div>
                                            @endif
                                            <div class="col-2 text-muted">
                                                Crédito fiscal:
                                            </div>
                                            @if ($p->ccf)
                                                <div class="col-10">
                                                    Este cliente tiene permitido comprobantes de crédito fiscal.
                                                </div>
                                            @else
                                                <div class="col-10">
                                                    No es permitido comprobantes de crédito fiscal a este cliente.
                                                </div>
                                            @endif
                                            <div class="col-2 text-muted">
                                                Retención:
                                            </div>
                                            @if ($detalle->percepcion)
                                                <div class="col-10">
                                                    Este cliente aplica retención.
                                                </div>
                                            @else
                                                <div class="col-10">
                                                    No aplica retención a este cliente.
                                                </div>
                                            @endif
                                            <div class="col-2 text-muted">
                                                Accionista:
                                            </div>
                                            @if ($p->accionista)
                                                <div class="col-10">
                                                    Este cliente es accionista.
                                                </div>
                                            @else
                                                <div class="col-10">
                                                    Este cliente no es accionista.
                                                </div>
                                            @endif
                                            <div class="col-2 text-muted">
                                                Ultima modificación:
                                            </div>
                                            <div class="col-10">
                                                {{ $detalle->updated_at }}
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Cards dinámicas --}}
                                    @if (isset($detalle->id) || ($p->estado && $p->tipo_cliente))
                                        @can('clientes.credito')
                                            @if ($p->credito)
                                                <div class="row mb-4">
                                                    <div class="col-12">
                                                        <h5>Periodos de créditos
                                                            @can('periodos_creditos.create')
                                                                <a href="{{ route('periodos_creditos.create') }}"
                                                                    class="card-link" target="_blank">Agregar</a>
                                                            @endcan
                                                        </h5>
                                                    </div>
                                                    <div class="col-3 cliente">
                                                        @php
                                                            $isDisabled = !$p->tipo_cliente && !isset($detalle->id);
                                                        @endphp
                                                        <button type="button"
                                                            class="card p-2 w-100 border border-1 border-success {{ $isDisabled ? 'disabled' : '' }}"
                                                            data-bs-toggle="{{ $isDisabled ? '' : 'modal' }}"
                                                            data-bs-target="{{ $isDisabled ? '' : '#modalPeriodosCreditos' }}">
                                                            <div class="card-body">
                                                                <p class="card-text">
                                                                    <span
                                                                        class="mdi mdi-{{ $p->periodosCreditos ? 'pencil' : 'plus' }}"></span>
                                                                    {{ $p->periodosCreditos ? 'Editar' : 'Agregar' }}
                                                                </p>
                                                            </div>
                                                        </button>
                                                    </div>
                                                    @if ($p->periodosCreditos)
                                                        <div class="col-3 cliente mb-2">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <a href="{{ route('clientes.periodos_creditos_confirm', ['id' => \Crypt::encryptString($p->id)]) }}"
                                                                        class="float-end text-danger h4"
                                                                        title="Eliminar periodo de crédito">
                                                                        <span class="mdi mdi-close"></span>
                                                                    </a>
                                                                    <b
                                                                        class="card-title">{{ $p->periodosCreditos->periodo ?? '---' }}</b>
                                                                    <p class="card-text">
                                                                        {{ $p->periodosCreditos->dias ?? '' }} días</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endcan

                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h5>Identificaciones
                                                    @can('identificaciones.create')
                                                        <a href="{{ route('identificaciones.create') }}" class="card-link"
                                                            target="_blank">Agregar</a>
                                                    @endcan
                                                </h5>
                                            </div>
                                            <div class="col-3 cliente">
                                                @php
                                                    $isDisabled = !$p->tipo_cliente && !isset($detalle->id);
                                                @endphp
                                                <button type="button"
                                                    class="card p-2 w-100 border border-1 border-success {{ $isDisabled ? 'disabled' : '' }}"
                                                    data-bs-toggle="{{ $isDisabled ? '' : 'modal' }}"
                                                    data-bs-target="{{ $isDisabled ? '' : '#identificaciones' }}">
                                                    <div class="card-body">
                                                        <p class="card-text">
                                                            <span class="mdi mdi-plus"></span> Agregar
                                                        </p>
                                                    </div>
                                                </button>
                                            </div>
                                            @foreach ($clIdenti as $identi)
                                                <div class="col-3 cliente mb-2">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <a href="{{ route('clientes_identificaciones.confirm', ['id' => \Crypt::encryptString($identi->id)]) }}"
                                                                class="float-end text-danger h4"
                                                                title="Eliminar identificacion">
                                                                <span class="mdi mdi-close"></span>
                                                            </a>
                                                            <b
                                                                class="card-title">{{ $identi->identificaciones->identificacion }}</b>
                                                            <p class="card-text">{{ $identi->numero }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h5>
                                                    Contactos
                                                    @can('contactos.create')
                                                        <a href="{{ route('contactos.create') }}" class="card-link"
                                                            target="_blank">Agregar</a>
                                                    @endcan
                                                </h5>
                                            </div>
                                            <div class="col-3 cliente mb-2">
                                                <button type="button"
                                                    class="card p-2 w-100 border border-1 border-success {{ $isDisabled ? 'disabled' : '' }}"
                                                    data-bs-toggle="{{ $isDisabled ? '' : 'modal' }}"
                                                    data-bs-target="{{ $isDisabled ? '' : '#contactos' }}">
                                                    <div class="card-body">
                                                        <p class="card-text">
                                                            <span class="mdi mdi-plus"></span> Agregar
                                                        </p>
                                                    </div>
                                                </button>
                                            </div>
                                            @foreach ($clContacto as $contacto)
                                                <div class="col-3 cliente mb-2">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <a href="{{ route('clientes_contactos.confirm', ['id' => \Crypt::encryptString($contacto->id)]) }}"
                                                                class="float-end text-danger h4"
                                                                title="Eliminar identificacion">
                                                                <span class="mdi mdi-close"></span>
                                                            </a>
                                                            <b class="card-title">{{ $contacto->contactos->contacto }}</b>
                                                            <p class="card-text">
                                                                {{ $contacto->valor }}
                                                                <span data-bs-toggle="popover" title="Observaciones"
                                                                    data-bs-content="{{ $contacto->observaciones }}"
                                                                    class="mdi mdi-details h5 text-muted pointer"></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if (!$p->tipo_cliente)
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <h5>
                                                        Giros
                                                        @can('giros.create')
                                                            <a href="{{ route('giros.create') }}" class="card-link"
                                                                target="_blank">Agregar</a>
                                                        @endcan
                                                    </h5>
                                                </div>
                                                <div class="col-3 cliente">
                                                    <button type="button"
                                                        class="card p-2 w-100 border border-1 border-success {{ $isDisabled ? 'disabled' : '' }}"
                                                        data-bs-toggle="{{ $isDisabled ? '' : 'modal' }}"
                                                        data-bs-target="{{ $isDisabled ? '' : '#giros' }}">
                                                        <div class="card-body">
                                                            <p class="card-text">
                                                                <span class="mdi mdi-plus"></span> Agregar
                                                            </p>
                                                        </div>
                                                    </button>
                                                </div>
                                                @foreach ($clGiro as $giro)
                                                    <div class="col-3 cliente mb-2">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <a href="{{ route('clientes_giros.confirm', ['id' => \Crypt::encryptString($giro->id)]) }}"
                                                                    class="float-end text-danger h4"
                                                                    title="Eliminar identificacion">
                                                                    <span class="mdi mdi-close"></span>
                                                                </a>
                                                                <b class="card-title">{{ $giro->giros->giro }}</b>
                                                                <p class="card-text">
                                                                    <span data-bs-toggle="popover" title="Informacion"
                                                                        data-bs-content="{{ $giro->giros->descripcion }}"
                                                                        class="mdi mdi-details h5 text-muted pointer"></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h5>Solicitantes de anulación</h5>
                                            </div>
                                            <div class="col-3 cliente">
                                                <button type="button"
                                                    class="card p-2 w-100 border border-1 border-success  {{ $isDisabled ? 'disabled' : '' }}"
                                                    data-bs-toggle="{{ $isDisabled ? '' : 'modal' }}"
                                                    data-bs-target="{{ $isDisabled ? '' : '#modalSolicitantes' }}">
                                                    <div class="card-body">
                                                        <p class="card-text"><span class="mdi mdi-plus"></span> Agregar
                                                        </p>
                                                    </div>
                                                </button>
                                            </div>
                                            @foreach ($solicitantes as $soli)
                                                <div class="col-3 cliente mb-2">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <a href="{{ route('solicitantes.eliminar', ['id' => \Crypt::encryptString($soli->solicitantes_id), 'clientes_id' => \Crypt::encryptString($soli->id)]) }}"
                                                                class="float-end text-danger h4"
                                                                title="Eliminar solicitante"
                                                                onclick="return confirm('¿Estás seguro de que deseas eliminar este solicitante?');">
                                                                <span class="mdi mdi-close"></span>
                                                            </a>
                                                            <b
                                                                class="card-title">{{ $soli->solicitantes->nombre_completo }}</b>
                                                            <p class="card-text">Teléfono:
                                                                {{ $soli->solicitantes->telefono }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- Modales --}}
                                    <!-- Modal Solicitantes -->
                                    <div class="modal fade" id="modalSolicitantes" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar solicitante
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('solicitantes.store') }}" method="post">
                                                    @csrf

                                                    <div class="modal-body">
                                                        <input type="hidden" name="clientes_id"
                                                            value="{{ $p->id }}">

                                                        <div class="mb-3">
                                                            <x-input-text name="nombre_completo" label="Nombre completo:"
                                                                val="{{ $p->nombre_completo ?? '' }}" required
                                                                maxlength="100" />
                                                        </div>

                                                        {{-- <div class="mb-3">
                                                    <x-input-select name="identificaciones_id" label="Identificación:"
                                                        :data="$data['identificaciones']" table="identificaciones" showName="identificacion" val="{{ $p->identificaciones_id ?? '' }}" required/>
                                                    </div> --}}
                                                        <div class="input-group mb-3">
                                                            <label class="input-group-text"
                                                                for="identificacion">Identificación:</label>
                                                            <select class="form-select" id="identificacion"
                                                                name="identificaciones_id" required v-model="sIdenti">
                                                                <option value="">Seleccione un tipo de identificación
                                                                </option>
                                                                <option v-for="i in identificaciones"
                                                                    :value="i.id">@{{ i.identificacion }}
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            {{-- <x-input-text name="numero_documento" label="Nº documento:" val="{{ $p->numero_documento ?? '' }}"
                                                        required maxlength="20"/> --}}
                                                            <label for="identi" class="form-label">Número de
                                                                identificación</label>
                                                            <input type="text" class="form-control"
                                                                :class="{
                                                                    'is-invalid': !isValidIdenti() && identi.length >
                                                                        0,
                                                                    'is-valid': isValidIdenti()
                                                                }"
                                                                id="identi" aria-describedby="textHelp" required
                                                                v-model="identi" name="numero_documento"
                                                                placeholder="Ingrese el número de identificación">
                                                            <div id="textHelp" class="form-text" v-if="oIdenti">
                                                                @{{ oIdenti.info }}</div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <x-input-text name="telefono"
                                                                label="Teléfono: 8 dígitos sin guiones ni espacios"
                                                                val="{{ $p->telefono ?? '' }}" placeholder="00000000"
                                                                required maxlength="8" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <x-input-text name="correo"
                                                                label="Correo: ejemplo@ejemplo.com"
                                                                val="{{ $p->correo ?? '' }}"
                                                                placeholder="ejemplo@ejemplo.com" required maxlength="100"
                                                                pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary"
                                                            :disabled="!isValidIdenti()">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Modal periodos de créditos -->
                                    <div class="modal fade" id="modalPeriodosCreditos" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar Periodo de
                                                        Crédito</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('clientes.periodoCreditoStore') }}"
                                                    method="post">
                                                    @csrf

                                                    <input type="hidden" name="clientes_id"
                                                        value="{{ $p->cid }}">

                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="periodos_creditos_id">Periodo
                                                                de crédito:</label>
                                                            <select class="form-select" id="periodos_creditos_id"
                                                                name="periodos_creditos_id" required>
                                                                <option value="0" selected>--Seleccione un periodo--
                                                                </option>
                                                                @foreach ($periodosCreditos as $pc)
                                                                    <option value="{{ $pc->cid }}">
                                                                        {{ $pc->periodo }} ·
                                                                        {{ $pc->dias }} días</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Modal Clientes identificaciones -->
                                    <div class="modal fade" id="identificaciones" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar identificacón
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('clientes_identificaciones.store') }}"
                                                    method="post">
                                                    @csrf
                                                    <input type="hidden" name="clientes_id"
                                                        value="{{ $p->id }}">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <div class="input-group mb-3">
                                                                <label class="input-group-text"
                                                                    for="identificacion">Identificación</label>
                                                                <select class="form-select" id="identificacion"
                                                                    name="identificaciones_id" required v-model="sIdenti">
                                                                    <option value="">
                                                                        Seleccione un tipo de identificación
                                                                    </option>
                                                                    <option v-for="i in identificaciones"
                                                                        :value="i.id">
                                                                        @{{ i.identificacion }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="identi" class="form-label">Número de
                                                                identificación</label>
                                                            <input type="text" class="form-control"
                                                                :class="{
                                                                    'is-invalid': !isValidIdenti() && identi.length >
                                                                        0,
                                                                    'is-valid': isValidIdenti()
                                                                }"
                                                                id="identi" aria-describedby="textHelp" required
                                                                v-model="identi" name="numero"
                                                                placeholder="Ingrese el número de identificación">
                                                            <div id="textHelp" class="form-text" v-if="oIdenti">
                                                                @{{ oIdenti.info }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary"
                                                            :disabled="!isValidIdenti()">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Modal Clientes Contactos -->
                                    <div class="modal fade" id="contactos" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar identificación
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('clientes_contactos.store') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="clientes_id"
                                                        value="{{ $p->id }}">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <div class="input-group mb-3">
                                                                <label class="input-group-text"
                                                                    for="contacto">Contacto</label>
                                                                <select class="form-select" id="contacto"
                                                                    name="contactos_id" required v-model="sContacto">
                                                                    <option value="">Seleccione un tipo de contacto
                                                                    </option>
                                                                    <option v-for="c in contactos" :value="c.id">
                                                                        @{{ c.contacto }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="identi" class="form-label">Ingrese el
                                                                contacto</label>
                                                            <input type="text" class="form-control"
                                                                :class="{
                                                                    'is-invalid': !isValidContacto() && contacto
                                                                        .length >
                                                                        0,
                                                                    'is-valid': isValidContacto()
                                                                }"
                                                                id="identi" aria-describedby="textHelp" required
                                                                v-model="contacto" name="valor"
                                                                placeholder="Ingrese el numero de contacto">
                                                            <div id="textHelp" class="form-text" v-if="oContacto">
                                                                @{{ oContacto.info }}
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="observacion"
                                                                class="form-label">Observación</label>
                                                            <textarea class="form-control" id="observacion" name="observacion" rows="3"
                                                                placeholder="Agregue información necesaria para este contacto, horas de contacto, si es un número de teléfono (Ej.: Acepta WhastApp)"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary"
                                                            :disabled="!isValidContacto()">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    @if (!$p->tipo_cliente)
                                        <!-- Modal Giros -->
                                        <div class="modal fade" id="giros" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar giro</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('clientes_giros.store') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="clientes_id"
                                                            value="{{ $p->id }}">

                                                        <div class="modal-body">
                                                            <div class="mb-3" v-if="oGiros.length > 0">
                                                                <span class="badge badge-pill bg-primary mr-2"
                                                                    v-for="og in oGiros">
                                                                    <input type="checkbox" :name="'giro-' + og.id"
                                                                        :id="'giro-' + og.id" :value="og.id"
                                                                        class="d-none" checked>
                                                                    <label>@{{ og.giro }}</label>
                                                                    <span class="mdi mdi-close"
                                                                        @click="setGiro(og)"></span>
                                                                </span>
                                                            </div>
                                                            <div class="mb-3">
                                                                <div class="input-group mb-3">
                                                                    <label class="input-group-text" for="contacto">Buscar
                                                                        giro</label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Escriba aqui para buscar..."
                                                                        v-model='txtBqGiros'>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3"
                                                                style="max-height: 250px; overflow-x: auto;">
                                                                <div class="list-group">
                                                                    <div v-for="g in filterGiros"
                                                                        class="list-group-item list-group-item-action flex-column align-items-start"
                                                                        :class="{ 'active': isActive(g.id) }"
                                                                        @click="setGiro(g)">
                                                                        <div class="d-flex w-100 justify-content-between">
                                                                            <h5 class="mb-1">@{{ g.giro }}</h5>
                                                                            <small>@{{ g.codigo }}</small>
                                                                        </div>
                                                                        <p class="mb-1">
                                                                            @{{ g.descripcion }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary"
                                                                :disabled="oGiros.length == 0">
                                                                Guardar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </section>


                                <!-- Modal Actividades económicas -->
                                <div class="modal fade" id="actividadEconomica" tabindex="-1"
                                    aria-labelledby="actividadEconomicaLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="actividadEconomicaLabel">Agregar actividad
                                                    económica</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Cerrar"></button>
                                            </div>
                                            <form action="{{ route('clientes.actividadEconomicaUpdate') }}"
                                                method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $p->cid }}">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <x-search label="Buscar actividad económica:"
                                                            showname="actividad_economica" val=""
                                                            :route="route('clientes.get_actividades')" id="actividades_economicas_id" />
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        :disabled="!isValidContacto()">Guardar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan
@endsection

@section('script-content')
<script type="application/javascript">
        var showApp = new Vue({
            el:'#app-component',    
            data:{
                identificaciones    : @json($identificaciones),
                contactos           : @json($contactos),
                giros               : @json($giros),
                clGiros             : @json($clGiro),
                sIdenti             : "",
                sContacto           : "",
                sDescuento          : "",
                oIdenti             : [],
                oContacto           : [],
                oGiros              : [],
                identi              : '',
                contacto            : '',
                txtBqGiros          : '',
            },
            methods:{
                isValidIdenti: function(){
                    this.oIdenti = this.identificaciones.find(r => r.id == this.sIdenti);
                    if(this.oIdenti){
                        return this.valid(this.oIdenti.regex, this.identi)
                    }
                    return false;
                },
                isValidContacto: function(){
                    this.oContacto = this.contactos.find(r => r.id == this.sContacto);
                    if(this.oContacto){
                        return this.valid(this.oContacto.regex, this.contacto)
                    }
                    return false;
                },
                setGiro:function(g){
                    if(this.isActive(g.id))
                        this.oGiros = this.oGiros.filter(r => r.id != g.id);
                    else
                        this.oGiros.push(g);
                },
                isActive:function(id){
                    return this.oGiros.find(r => r.id == id) != null? true : false;
                },
                valid:function(reg, val){
                    var reg = new RegExp(reg, 'gi')
                    return reg.test(val);
                },
                getGirosClientes: function(){
                    this.clGiros.forEach(g => this.setGiro(this.giros.find(p => p.id == g.giros_id)))
                }
            },
            mounted: function () {
                document.onreadystatechange = () => {
                    if (document.readyState == "complete") {
                        this.getGirosClientes();
                    }
                }
            },
            computed:{
                filterGiros:function(){
                    if(this.txtBqGiros.length > 0){
                        let regx = new RegExp(this.txtBqGiros);
                        return this.giros.filter(g => regx.test(g.nombre) || regx.test(g.descripcion) || regx.test(g.codigo));
                    }
                    return this.giros;
                }
            }
        });
    </script>
@endsection
