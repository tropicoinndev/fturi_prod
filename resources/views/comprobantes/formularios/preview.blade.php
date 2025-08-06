@extends('layouts.app')
@section('content')
    <style>
        .bg-gray {
            background: #ECEFF1 !important;
        }

        .colf {
            width: 6cm;
        }

        body {
            background: #26C6DA;
            text-transform: uppercase;
        }

        .vacio::after {
            content: '---'
        }
    </style>
    <div class="container">
        <div class="card p-4">
            <div class="card-body">
                <div class="card-text">
                    <div class="row">
                        <div class="col-12">
                            <x-message></x-message>
                            <table class="table table-light table-borderless">
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="text-center bg-gray fw-bold">
                                            FORMULARIO DE OPERACIONES REGULADAS
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2" class="text-center bg-gray fw-bold">
                                            PARTE I - PERSONAS INVOLUCRADAS EN LA TRANSACCIÓN
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center bg-gray">
                                            SECCIÓN A: Persona que realiza físicamente la transacción
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Distinta al cliente:
                                        </td>
                                        <td>
                                            {{ $p->distinto ? 'SI' : 'NO' }}
                                        </td>
                                    </tr>
                                    @php
                                        $ap = $p->seccion_a_persona_id === null;
                                    @endphp
                                    <tr>
                                        <td class="colf">
                                            Apellidos:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->apersona?->apellidos }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Nombres:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->apersona?->nombre }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Lugar de nacimiento:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->apersona?->nacimiento }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Departamento:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->apersona?->departamentos?->departamento }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Fecha de nacimiento:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->apersona?->fecha_nacimiento }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Nacionalidad:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->apersona?->departamentos?->paises?->nacionalidad ?? $p?->apersona?->paises->nacionalidad }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Estado civil:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->apersona?->estado_civil }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Tipo de documento:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->apersona?->identificaciones?->identificacion }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Nº documento:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->apersona?->identificacion }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Profesión u Oficio:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->apersona?->profesion }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Domicilio:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->apersona?->domicilio }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center bg-gray">
                                            SECCIÓN B: Persona o Personas a cuyo nombre se realiza la transacción
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Tipo de persona:
                                        </td>
                                        <td>
                                            {{ $p->tipo_persona ? 'Persona natural' : 'Jurídica' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center bg-gray">
                                            B-1 Persona Natural
                                        </td>
                                    </tr>
                                    @php
                                        $ap = $p->seccion_b_persona_id === null;
                                    @endphp
                                    <tr>
                                        <td class="colf">
                                            Apellidos:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bpersona?->apellidos }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Nombres:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bpersona?->nombre }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Lugar de nacimiento:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bpersona?->nacimiento }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Departamento:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bpersona?->departamentos?->departamento }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Fecha de nacimiento:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bpersona?->fecha_nacimiento }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Nacionalidad:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bpersona?->departamentos?->paises?->nacionalidad ?? $p?->bpersona?->paises->nacionalidad }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Estado civil:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bpersona?->estado_civil }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Tipo de documento:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bpersona?->identificaciones?->identificacion }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Nº documento:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bpersona?->identificacion }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Profesión u Oficio:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bpersona?->profesion }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Domicilio:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bpersona?->domicilio }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center bg-gray">
                                            B-2 Persona Jurídica
                                        </td>
                                    </tr>
                                    @php
                                        $ap = $p->seccion_b_juridico_id === null;
                                    @endphp
                                    <tr>
                                        <td class="colf">
                                            Nombre o Razón Social:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bjuridico?->nombre }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Dirección Comercial:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            @if ($p?->bjuridico != null)
                                                {{ $p?->bjuridico?->direccion }}
                                                <b>
                                                    Municipio:
                                                </b>
                                                {{ $p?->bjuridico?->municipios?->municipio ?? 'Sin información' }}
                                                <b>
                                                    Departamento:
                                                </b>
                                                {{ $p?->bjuridico?->municipios?->departamentos?->departamento ?? 'Sin información' }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Actividad Económica:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            {{ $p?->bjuridico?->actividades->actividad }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Identificación Tributario:
                                        </td>
                                        <td class="{{ $ap ? 'vacio' : '' }}">
                                            @if ($p?->bjuridico != null)
                                                <ul>
                                                    <li>
                                                        <b>NRC: </b>
                                                        {{ $p?->bjuridico?->detalle?->nrc ?? 'Sin información' }}
                                                    </li>


                                                    @forelse ($p?->bjuridico?->identificaciones as $i)
                                                        <li>
                                                            <b>
                                                                {{ $i?->identificaciones?->identificacion }}
                                                            </b>
                                                            {{ $i?->numero }}
                                                        </li>
                                                    @empty
                                                        <li>No se encontraron mas identificaciones.</li>
                                                    @endforelse
                                                </ul>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center bg-gray fw-bold">
                                            PARTE II - DETALLE DE LA TRANSACCIÓN EN EFECTIVO U OTRO MEDIO.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Punto de Servicio:
                                        </td>
                                        <td>
                                            Punto de venta: {{ $p->caja->caja }} ·
                                            Sucursal: {{ $p->caja->sucursales->sucursal }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Municipio:
                                        </td>
                                        <td>
                                            {{ $p->caja->sucursales->municipios->municipio }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Departamento:
                                        </td>
                                        <td>
                                            {{ $p->caja?->sucursales?->municipios?->departamentos?->departamento }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            N° de Comprobante:
                                        </td>
                                        <td>
                                            Correlativo Interno: {{ $p->comprobante->correlativo }} ·
                                            Codigo de generacion:
                                            {{ $p->comprobante?->dteOne?->codigo_generacion ?? 'SIN DTE' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Clase de Servicio:
                                        </td>
                                        <td>
                                            {{ $p->clase_servicio }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Observación de Transacción:
                                        </td>
                                        <td>
                                            {{ $p->observaciones }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Monto de la Transacción:
                                        </td>
                                        <td>
                                            ${{ number_format($p?->comprobante?->total, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Valor en Efectivo:

                                        </td>
                                        <td>
                                            ${{ number_format($p->efectivo, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Valor en Cheque:

                                        </td>
                                        <td>
                                            ${{ number_format($p->cheque, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Valor en Tarjeta de Débito o Crédito

                                        </td>
                                        <td>
                                            ${{ number_format($p->tarjeta, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Procedencia del Efectivo:
                                        </td>
                                        <td>
                                            {{ $p->comprobante?->procedencia }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Fecha de Transacción:
                                        </td>
                                        <td>
                                            {{ $p->comprobante?->fecha }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Cargo del empleado:
                                        </td>
                                        <td>
                                            {{ $p->cargo }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Nombre del empleado:
                                        </td>
                                        <td>
                                            {{ $p->comprobante?->users?->empleadoOne?->nombre_completo ?? 'Usuario sin registro de empleado' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Firma del empleado:
                                        </td>
                                        <td>
                                            ______________________________________
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Nombre del jefe de area:
                                        </td>
                                        <td>
                                            {{ $p?->supervisa?->name ?? 'Al completar la revision del formulario aparecerá aquí el nombre de quien superviso el formulario' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Firma del jefe de area:
                                        </td>
                                        <td>
                                            ______________________________________
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center bg-gray fw-bold">
                                            PARTE III - IDENTIFICACIÓN DEL SUJETO OBLIGADO
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center bg-gray">
                                            PERSONA NATURAL
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Apellidos:
                                        </td>
                                        <td class="vacio"></td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Nombres:
                                        </td>
                                        <td class="vacio"></td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Identificación:
                                        </td>
                                        <td class="vacio"></td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Actividad Económica:
                                        </td>
                                        <td class="vacio"></td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Dirección Comercial:
                                        </td>
                                        <td class="vacio"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center bg-gray">
                                            PERSONA JURÍDICA
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Nombre o Razón Social:
                                        </td>
                                        <td>
                                            {{ env('empresa', 'TURISTICAS DE ORIENTE, S.A. DE C.V.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Dirección Comercial:
                                        </td>
                                        <td>
                                            {{ $p?->caja?->sucursales?->direccion }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Actividad Económica:
                                        </td>
                                        <td>
                                            {{ $p?->caja?->sucursales?->giro }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            Identificación Tributario:
                                        </td>
                                        <td>
                                            <ul>
                                                <li>
                                                    NRC: {{ $p?->caja?->sucursales?->nrc }}
                                                </li>
                                                <li>
                                                    NIT: {{ $p?->caja?->sucursales?->nit }}
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    @if ($p->completado && (!$p->revisado || $p->users_revisa_id === null))
                        @canany(['operaciones_reguladas.supervisar', 'operaciones_reguladas.autorizacion'])
                            <div class="row border border-success p-4 rounded">
                                <div class="col-12">
                                    <h4 class="text-success">
                                        <span class="mdi mdi-check"></span>
                                        Confirmación de revision
                                    </h4>
                                </div>
                                <div class="col-12">
                                    <form action="{{ route('operaciones_reguladas.revision') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $p->cid }}">

                                        <div class="mb-3">
                                            <label for="" class="form-label">Contraseña</label>
                                            <input type="password" class="form-control" name="pass" id=""
                                                placeholder="Escriba su contraseña para confirmar las acciones" />
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    name="confirm" id="confirmRevision">
                                                <label class="form-check-label" for="confirmRevision">
                                                    Confirmo que he revisado detenidamente cada una de las secciones, toda la
                                                    información es correcta.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-primary" type="submit">
                                                <span class="mdi mdi-check"></span>
                                                Completar revision
                                            </button>
                                            <a class="btn btn-light"
                                                href="{{ route('operaciones_reguladas.descompletar', ['id' => $p->cid]) }}"
                                                role="button">
                                                <span class="mdi mdi-file-edit"></span>
                                                Editar formulario
                                            </a>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endcanany
                    @elseif ($p->completado && $p->revisado && $p->users_revisa_id !== null)
                        @can('operaciones_reguladas.autorizacion')
                            <div class="row border border-success p-4 rounded">
                                <div class="col-12">
                                    <h4 class="text-success">
                                        <span class="mdi mdi-check"></span>
                                        Autorización del formulario
                                    </h4>
                                </div>
                                <div class="col-12">
                                    <form action="{{ route('operaciones_reguladas.autorizacion') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $p->cid }}">

                                        <div class="mb-3">
                                            <label for="fecha" class="form-label">Fecha de envió</label>
                                            <input type="date" class="form-control" name="fecha" id="fecha"
                                                placeholder="Escriba su contraseña para confirmar las acciones" />
                                            <small>Requerido solamente para completar</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Observaciones</label>
                                            <textarea class="form-control" name="observaciones"
                                                placeholder="Escriba los errores encontrados y posibles soluciones max: 200 caracteres" rows="4"
                                                maxlength="200"></textarea>
                                            <small>Requerido solamente para negar, agregar las correcciones</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Contraseña</label>
                                            <input type="password" class="form-control" name="pass"
                                                placeholder="Escriba su contraseña para confirmar las acciones" />
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    name="confirm" id="confirmRevision">
                                                <label class="form-check-label" for="confirmRevision">
                                                    Confirmo que he revisado detenidamente cada una de las secciones, toda la
                                                    información es correcta.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-primary" type="submit" name="opcion" value="1">
                                                <span class="mdi mdi-check"></span>
                                                Autorizar
                                            </button>
                                            <button class="btn btn-danger" type="submit" name="opcion" value="2">
                                                <span class="mdi mdi-file-document-alert"></span>
                                                Negar y enviar correcciones
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
