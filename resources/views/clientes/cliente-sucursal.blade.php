@extends('layouts.app')

@section('style')
    <style>
        :root {
            --color-primary: #263238;
            --color-secondary: #37474F;
            --color-light: #E3F2FD;
            --color-background: #E0F2F1;
            --color-highlight: #BBDEFB;
            --color-dark: #455A64;
            --shadow-light: 1px 5px 2px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--color-background);
        }

        .panel-body {
            min-height: 91vh;
            color: #FAFAFA;
        }

        .btn-light {
            background: #DAE0E5;
        }

        .card-eventos,
        .dpl {
            background: var(--color-light);
            border: none;
            box-shadow: var(--shadow-light);
        }

        .card-anticipo {
            background-color: #FFFFFF;
            border: 1px solid var(--color-highlight);
            box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .titulo,
        .titulo-anticipo {
            color: var(--color-primary);
            font-weight: bold;
        }

        .text-eventos,
        .text-cliente,
        .total,
        .monto {
            color: var(--color-secondary);
        }

        .contactos,
        .tipo-evento,
        .text-observacion,
        .concepto {
            color: var(--color-dark);
        }

        [v-cloak] {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="container" style="min-height: 80vh;">
        <div class="row">
            <div class="col-12 m-auto">
                <div class="card p-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-10">
                                <h4>Creacion  por medio del cliente {{ $p->nombre }} #{{ $p->id }}</h4>
                            </div>
                            <div class="col-md-2 text-md-start mt-2 mt-md-0">
                                <a href="{{ route('clientes.show', ['id' => \Crypt::encryptString($p->id)]) }}"
                                    class="btn btn-light">
                                    <span class="mdi mdi-arrow-left fs-5 me-2"></span>
                                    Volver
                                </a>
                            </div>
                        </div>
                        <x-message></x-message>
                        <div class="col-12">
                            <div class="card-anticipo mt-4">

                                @if (isset($p->detalle->id))
                                    <div class="row mb-5">
                                        <div class="col-12">
                                            <h4>Información</h4>
                                        </div>
                                        <div class="col-2 text-muted">
                                            Nombre jurídico:
                                        </div>
                                        <div class="col-10">
                                            {{ $p->detalle->juridico }}
                                        </div>
                                        <div class="col-2 text-muted">
                                            Dirección:
                                        </div>
                                        <div class="col-10">
                                            {{ $p->direccion }} |
                                            {{ $p->municipios->municipio ?? $p->extranjero->pais }},
                                            {{ $p->municipios->departamentos->departamento ?? 'Sin departamento asignado' }},
                                            {{ $p->municipios->departamentos->paises->pais ?? 'Sin país asignado' }}
                                        </div>
                                        <div class="col-2 text-muted">
                                            Categoría del cliente:
                                        </div>
                                        <div class="col-10">
                                            @if ($p->categoria == '1')
                                                Pequeño
                                            @elseif ($p->categoria == '2')
                                                Mediano
                                            @elseif ($p->categoria == '3')
                                                Grande
                                            @else
                                                No se asignado categoria a este cliente
                                            @endif
                                        </div>
                                        <div class="col-2 text-muted">
                                            Actividad del cliente:
                                        </div>
                                        <div class="col-10">
                                            {{ $p->actividades->actividad ?? 'No se asigno actividad economica a este cliente ' }}
                                        </div>
                                        <div class="col-2 text-muted">
                                            Email:
                                        </div>
                                        <div class="col-10">
                                            {{ $p->email ?? 'No se ha agregado email' }}
                                        </div>
                                        <div class="col-2 text-muted">
                                            NRC:
                                        </div>
                                        <div class="col-10">
                                            @if ($p->detalle->nrc)
                                                {{ $p->detalle->nrc }}
                                            @else
                                                Es exento, no es necesario NRC.
                                            @endif
                                        </div>
                                        <div class="col-2 text-muted">
                                            Exento:
                                        </div>
                                        <div class="col-10">
                                            @if ($p->detalle->exento)
                                                Es exento de todos los impuestos.
                                            @else
                                                No es exento de impuestos.
                                            @endif
                                        </div>
                                        <div class="col-2 text-muted">
                                            Crédito:
                                        </div>
                                        <div class="col-10">
                                            @if ($p->credito)
                                                Este cliente sí permite crédito.
                                            @else
                                                No se permite crédito a este cliente.
                                            @endif
                                        </div>
                                        <div class="col-2 text-muted">
                                            Descuento:
                                        </div>
                                        <div class="col-10">
                                            @if ($p->descuento)
                                                Este cliente tiene permitido descuentos.
                                            @else
                                                No se permiten descuentos a este cliente.
                                            @endif
                                        </div>
                                        <div class="col-2 text-muted">
                                            Crédito fiscal:
                                        </div>
                                        <div class="col-10">
                                            @if ($p->ccf)
                                                Este cliente tiene permitido comprobantes de crédito fiscal.
                                            @else
                                                No se permiten comprobantes de crédito fiscal a este cliente.
                                            @endif
                                        </div>
                                        <div class="col-2 text-muted">
                                            Retención:
                                        </div>
                                        <div class="col-10">
                                            @if ($p->detalle->percepcion)
                                                Este cliente aplica retención.
                                            @else
                                                No aplica retención a este cliente.
                                            @endif
                                        </div>
                                        <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                            <a href="#createClientModal"data-bs-toggle="modal"
                                                data-bs-target="#createClientModal" class="btn btn-light">
                                                <span class="mdi mdi-fireplace-off fs-5 me-2"></span>
                                                Crear cliente
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- Modal crear cliente -->
<div class="modal fade" id="createClientModal" tabindex="-1" aria-labelledby="createClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createClientModalLabel">Crear nuevo cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para crear cliente -->
                <form action="{{ route('clientes.cliente_sucursal') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="clientes_id" value="{{ Crypt::encryptString($p->id) }}">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                         v-model="nombre"  value="{{ old('nombre') }}" placeholder="Ingrese el nombre del cliente" required aria-required="true">

                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" v-model="direccion"
                         value="{{ old('direccion') }}" placeholder="Ingrese la dirección" required aria-required="true">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" v-model="email" placeholder="ejemplo@dominio.com">

                    </div>

                    <div class="mb-3">

                        <x-search label="Buscar ciudad o municipio:" showname="ciudad" :val="isset($t) ? $t->municipios : ''" :route="route('municipios.apiByCiudad')" id="municipios_id" required aria-required="true" />

                    </div>

                    <div class="d-flex justify-content-start">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>






@endsection
