@extends('layouts.app')
@section('style')
    <style>
        .active:hover {
            color: #134e8d;
            font-weight: bold;
        }

        body {
            background-color: #E0F2F1;
        }

        .panel-body {
            min-height: 91vh;
            color: #FAFAFA;
            top: 0;
        }

        .btn-light {
            background: #DAE0E5;
        }

        .titular {
            background: #D9D9D9;
            color: #000;
        }

        .c1 {
            background: #B2DFDB;
            color: #37474F;
        }

        .c2 {
            background: #263238;
            color: #FAFAFA;
        }

        .c3 {
            background: #1565C0;
            color: #FAFAFA;
        }

        .cortesia {
            background: #1B5699;
            color: #FAFAFA;
        }

        .cuentas {
            border: 1px solid #BBDEFB;
        }

        .at {

            color: #37474F;
            font-weight: bold;
            text-decoration: none;

        }


        .Salon1 {
            /**se usara cuando este disponible para seleccionar*/
            background: #B2DFDB;
            color: #000;
        }

        .Salon2 {
            /**se usapara cuando se seleccione el checboks patra mandar aguardar */
            background: #4DB6AC;
            color: #fff;
        }

        .Salon3 {
            /**se usara cuando ya no se pueda seleccionar por que ya esta asociada a un evento existente  */
            background: #FFCCBC;
            color: #000;
        }

        .Salon {
            border: 2px solid #4DB6AC;
        }

        .ocupado-message {
            color: #000;
            font-weight: bold;
        }

        .selected-image {
            border: 5px solid #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
            animation: selectAnimation 0.5s forwards;
        }

        @keyframes selectAnimation {
            0% {
                transform: scale(1);
                opacity: 0.5;
            }

            100% {
                transform: scale(1.1);
                opacity: 1;
            }
        }

        .unselected-image {
            animation: deselectAnimation 0.5s forwards;
        }

        @keyframes deselectAnimation {
            0% {
                transform: scale(1.1);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 0.5;
            }
        }

        .image-info {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background-color: rgba(0, 123, 255, 0.7);
            color: white;
            padding: 5px;
            display: none;
        }

        .selected .image-info {
            display: block;
        }

        [v-cloak] {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div id="appEventoDetalle" v-cloak>
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-12">

                    <div class="card panel shadow p-3">
                        <x-message></x-message>
                        <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                            v-show="message.message && message.type">
                            <strong>@{{ message.message }}</strong>
                        </div>
                        <div class="row p-4">
                            <h3>CREACION DE EVENTO Nº {{ $evento->id }}</h3>

                            <div class="row mb-3 text-uppercase">
                                @if (!$evento->clientes && $evento->titular)
                                    <!-- Mostrar el botón para agregar cliente cuando haya un titular pero no haya un cliente -->
                                    <a class="at" v-if="modificar" href="#addCliente" data-bs-toggle="modal"
                                        data-bs-target="#addCliente" role="button">
                                        <span class="mdi mdi-plus"></span> {{ $evento->titular }}

                                    </a>
                                    <a v-else class="at">{{ $evento->titular }}</a>
                                @elseif ($evento->clientes)
                                    <!-- Mostrar el enlace para editar el cliente y el icono de lápiz cuando haya un cliente asignado -->
                                    <a v-if="modificar" class="at" href="#editarCliente" data-bs-toggle="modal"
                                        data-bs-target="#editarCliente" role="button">
                                        <span class="mdi mdi-pencil-outline"></span> {{ $evento->clientes->nombre }}
                                    </a>
                                    <a v-else class="at">{{ $evento->clientes->nombre }}</a>
                                @else
                                    No se ha asignado cliente aún
                                @endif

                            </div>

                            <div class="col-12 mb-3">
                                <a class="btn btn-light  m-1" href="{{ route('eventos.eventos') }}" role="button">
                                    <span class="mdi mdi-arrow-left"></span> Volver
                                </a>
                                @if ($evento->clientes)
                                    @can('anticipos.create')
                                        <a class="btn btn-light  m-1" :href="'/eventos/anticipos/' + eventoId.cid"
                                            role="button">
                                            <span class="mdi mdi-plus-thick"></span> Anticipo
                                        </a>
                                    @endcan
                                @endif
                                @can('comandas.create')
                                    <a v-show="modificar" class="btn btn-light m-1 " href="#AddComandas" data-bs-toggle="modal"
                                        data-bs-target="#AddComandas" :data-id="eventoId.cid" :data-mesa="numeroDeMesa"
                                        role="button">
                                        <span class="mdi mdi-cart-plus"></span>
                                        Comanda
                                    </a>
                                @endcan
                                @can('servicios.index')
                                    <a v-show="modificar" class="btn btn-light  m-1" href="#AddOrdenes" data-bs-toggle="modal"
                                        data-bs-target="#AddOrdenes" :data-id="eventoId.cid" role="button">
                                        <span class="mdi mdi-treasure-chest-outline"></span>
                                        Servicio
                                    </a>
                                @endcan
                                @can('eventos.autorizar')
                                    <a class="btn btn-light  m-1" :href="'/eventos/solicita/autorizar/' + eventoId.cid"
                                        role="button" v-if="!eventoId.solicita">
                                        <span class="mdi mdi-signature-freehand"></span> Solicitar
                                        Autorizacion
                                    </a>
                                @endcan
                                @can('eventos.duplicar')
                                    <a class="btn btn-light  m-1" :href="'/eventos/duplicar/' + eventoId.cid" role="button"
                                        v-show="eventoId.autoriza">
                                        <span class="mdi mdi-content-duplicate"
                                            v-if="eventoId.autoriza && eventoId.autoriza_users_id"></span> Duplicar
                                    </a>
                                @endcan
                                @can('comprobante.index')
                                    <button class="btn btn-light dropdown-toggle m-1 " type="button" id="dropdownMenuButton1"
                                        v-if="eventoId.autoriza" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="mdi mdi-file-document-outline"></span>
                                        Comprobantes
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                        @php
                                            $comandasExist = $evento->comandasEvento($evento->id)->exists();
                                            $ordenesExist = $evento->ordenesEvento($evento->id)->exists();
                                            $comanda = $evento
                                                ->comandasEvento($evento->id)
                                                ->pluck('id')
                                                ->first();
                                            $orden = $evento
                                                ->ordenesEvento($evento->id)
                                                ->pluck('id')
                                                ->first();

                                            $origen = $comandasExist ? 3 : ($ordenesExist ? 1 : null);
                                        @endphp

                                        @if ($origen != null)
                                            @foreach ([['origen' => $origen]] as $item)
                                                @can('eventos.cobros')
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('cobros.create', [
                                                                'origen' => Crypt::encryptString($item['origen']),
                                                                'origen_id' => $comanda ? Crypt::encryptString($comanda) : $orden,
                                                                'tipo_comprobante' => Crypt::encryptString('7002'),
                                                            ]) }}">Factura</a>
                                                    </li>
                                                    @if ($evento->clientes_id > 0 && !$evento->clientes->tipo_cliente)
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('cobros.create', [
                                                                    'origen' => Crypt::encryptString($item['origen']),
                                                                    'origen_id' => $comanda ? Crypt::encryptString($comanda) : $orden,
                                                                    'tipo_comprobante' => Crypt::encryptString('7001'),
                                                                ]) }}">Creditofiscal</a>
                                                        </li>
                                                    @endcan
                                                @endif
                                            @endforeach
                                        @endif
                                        @can('eventos.cobros_anticipados')
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('eventos.pago_anticipado', [
                                                        'id' => $evento->cid,
                                                    ]) }}">Pago
                                                    anticipado</a>
                                            </li>
                                        @endcan


                                    </ul>
                                @endcan
                                @can('eventos.autorizar')
                                    <a class="btn btn-light m-1" href="{{ route('eventos.panelAutorizacion') }}" role="button"
                                        v-if=" eventoId.solicita && !eventoId.autoriza ">
                                        <span class="mdi mdi-food-turkey"></span> Autorizar eventos
                                    </a>
                                @endcan
                                @can('eventos.preview')
                                    <a class="btn btn-light m-1" :href="'/eventos/imprimir/' + eventoId.cid" role="button">
                                        <span class="mdi mdi-printer-eye"></span> Vista previa
                                    </a>
                                @endcan

                            </div>

                            <!-- section configuraciones de evento -->
                            <div class="container-fluid  mb-4 h-100 w-100">

                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="fw-bold">Configuraciones</h4>
                                        <span class="text-danger"
                                            v-if="eventoId.observacion_negacion && eventoId.negacion_users_id && !eventoId.autoriza">
                                            Observación de negación: @{{ eventoId.observacion_negacion ?? 'Sin observación' }}</span>

                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2 fw-bold">
                                        Fecha de evento:
                                    </div>
                                    <div class="col-md-10 fw-bold">
                                        @if ($evento->fecha)
                                            Inicia
                                            {{ \Carbon\Carbon::parse($evento->fecha)->isoFormat('dddd, D [de] MMMM [de] YYYY') }},
                                            hora en que iniciara el evento
                                            {{ \Carbon\Carbon::parse($evento->inicio)->format('h:i A') }} y finaliza
                                            {{ \Carbon\Carbon::parse($evento->fecha_fin)->isoFormat('dddd, D [de] MMMM [de] YYYY') }},
                                            hora en la que
                                            finalizara {{ \Carbon\Carbon::parse($evento->finalizacion)->format('h:i A') }}.
                                        @else
                                            Aun no se hazy fecha y hora de inicio y finalizacion de evento.
                                        @endif
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-2 at"> <a v-if="modificar" class="at" href="#editarTipo"
                                            data-bs-toggle="modal" data-bs-target="#editarTipo" role="button">
                                            <span class="mdi mdi-pencil-outline"></span>Tipo de evento:
                                        </a> <a v-else class="at"> Tipo de evento:</a></div>
                                    <div class="col-md-10">{{ $evento->tipo_eventos->evento }},
                                        {{ $evento->tipo_eventos->descripcion }}
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-2 at"><a v-if="modificar" class="at"
                                            href="#modalEditarSalones" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarSalones" role="button"
                                            @click="getDisponibilidad">
                                            <span class="mdi mdi-pencil-outline"></span>Salon(es):
                                        </a><a v-else class="at">Salon(es):</a></div>
                                    <div class="col-md-10">
                                        {{ implode(', ', $evento->salones->pluck('salones.salon')->toArray()) }}
                                    </div>
                                </div>
                                <div class="row">
                                    @if (!isset($evento->observaciones_factura))
                                        <div class="col-md-2 at "><a v-if="modificar" class="at"
                                                href="#ObservacionFactura" data-bs-toggle="modal"
                                                data-bs-target="#observacionFactura" role="button">
                                                <span class="mdi mdi-plus"></span>Observacion de facturacion del evento:
                                            </a><a v-else class="at"> Observacion de facturacion del evento:</a></div>
                                    @else
                                        <div class="col-md-2 at"><a v-if="modificar" class="at"
                                                href="#observacionFactura" data-bs-toggle="modal"
                                                data-bs-target="#observacionFactura" role="button">
                                                <span class="mdi mdi-pencil-outline"></span>Observacion de facturacion del
                                                evento:
                                            </a> <a v-else class="at">Observacion de facturacion del evento:</a> </div>
                                    @endif
                                    <div class="col-md-10">
                                        @if ($evento->observaciones_factura)
                                            {{ $evento->observaciones_factura }}
                                        @else
                                            Aun no se ha agregado la observacion de como se va facturar el evento,
                                            seleccione en el boton agregar.
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    @if (!isset($evento->sonidos))
                                        <div class="col-md-2 at "><a v-if="modificar" class="at"
                                                href="#asignarSonidos" data-bs-toggle="modal"
                                                data-bs-target="#asignarSonidos" role="button">
                                                <span class="mdi mdi-plus"></span>Sonido:
                                            </a><a v-else class="at"> Sonido:</a></div>
                                    @else
                                        <div class="col-md-2 at"><a v-if="modificar" class="at"
                                                href="#asignarSonidos" data-bs-toggle="modal"
                                                data-bs-target="#asignarSonidos" role="button">
                                                <span class="mdi mdi-pencil-outline"></span>Sonido:
                                            </a> <a v-else class="at">Sonido:</a> </div>
                                    @endif
                                    <div class="col-md-10">
                                        @if ($evento->sonidos)
                                            {{ $evento->sonidos->sonido }}, {{ $evento->sonidos->descripcion }}
                                        @else
                                            Aun no se ha agregado el tipo de sonido, seleccione en el boton agregar.
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    @if (!isset($evento->observaciones_sonidos))
                                        <div class="col-md-2 at "><a v-if="modificar" class="at"
                                                href="#observacionSonidos" data-bs-toggle="modal"
                                                data-bs-target="#observacionSonidos" role="button">
                                                <span class="mdi mdi-plus"></span> Observaciones de sonido:
                                            </a> <a v-else class="at">Observaciones de sonido:</a></div>
                                    @else
                                        <div class="col-md-2 at"><a v-if="modificar" class="at"
                                                href="#observacionSonidos" data-bs-toggle="modal"
                                                data-bs-target="#observacionSonidos" role="button">
                                                <span class="mdi mdi-pencil-outline"></span> Observaciones de sonido:
                                            </a><a v-else class="at">Observaciones de sonido:</a></div>
                                    @endif
                                    <div class="col-md-10">
                                        @if ($evento->observaciones_sonidos)
                                            {{ $evento->observaciones_sonidos }}
                                        @else
                                            Aun no se han agregado observaciones del sonido, para agregar las observaciones
                                            del sonido presione en el boton agregar, antes debe agregar el tipo de sonido
                                            para poder agregar observaciones.
                                        @endif
                                    </div>
                                </div>
                                <div class="row">

                                    @if ($evento->montajes)
                                        <div class="col-md-2 at ">
                                            <a v-if="modificar" class="at" href="#asignarMontajes"
                                                data-bs-toggle="modal" data-bs-target="#asignarMontajes" role="button">
                                                <span class="mdi mdi-pencil-outline"></span> Tipo de montaje:
                                            </a><a v-else class="at">Tipo de montaje:</a>
                                        </div>
                                    @else
                                        <div class="col-md-2 at">
                                            <a v-if="modificar" class="at" href="#asignarMontajes"
                                                data-bs-toggle="modal" data-bs-target="#asignarMontajes" role="button">
                                                <span class="mdi mdi-plus"></span> Tipo de montaje:
                                            </a>
                                            <a v-else class="at">Tipo de montaje:</a>

                                        </div>
                                    @endif

                                    <div class="col-md-10">
                                        @if ($evento->montajes)
                                            {{ $evento->montajes->montaje }}, {{ $evento->montajes->descripcion }}
                                        @else
                                            Aun no se agregado un montaje a este evento
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    @if (!isset($evento->montaje))
                                        <div class="col-md-2 at"><a v-if="modificar" class="at"
                                                href="#asignarMontajes" data-bs-toggle="modal"
                                                data-bs-target="#asignarMontajes"><span class="mdi mdi-plus"></span>
                                                Observaciones del montaje:
                                                <a v-else class="at">Observaciones del montaje:</a>
                                            </a>
                                        </div>
                                    @else
                                        <div class="col-md-2 at"><a v-if="modificar" class="at"
                                                href="#observacionMontaje" data-bs-toggle="modal"
                                                data-bs-target="#observacionMontaje"><span
                                                    class="mdi mdi-pencil-outline"></span>Observaciones del montaje: </a>
                                            <a v-else class="at">Observaciones del montaje:</a>

                                        </div>
                                    @endif
                                    <div class="col-md-10">
                                        @if ($evento->montaje)
                                            {{ $evento->montaje }}
                                        @else
                                            Aun no se han agregado observaciones del montaje, para agregar las observaciones
                                            presione en el boton agregar.
                                        @endif
                                    </div>
                                </div>
                                <div class="row ">

                                    @if ($evento->observaciones)
                                        <div class="col-md-2 at"><a v-if="modificar" class="at"
                                                href="#modalObservacionesGenerales" data-bs-toggle="modal"
                                                data-bs-target="#modalObservacionesGenerales">
                                                <span class="mdi mdi-pencil"></span> Observaciones generales:
                                            </a> <a v-else class="at">Observaciones generales:</a></div>
                                    @else
                                        <div class="col-md-2 at"><a v-if="modificar" class="at"
                                                href="#modalObservacionesGenerales" data-bs-toggle="modal"
                                                data-bs-target="#modalObservacionesGenerales">
                                                <span class="mdi mdi-plus"></span> Observaciones generales:
                                            </a> <a v-else class="at">Observaciones generales:</a></div>
                                    @endif


                                    <div class="col-md-10">
                                        @if ($evento->observaciones)
                                            {{ $evento->observaciones }}
                                        @else
                                            Aun no se han agregado observaciones, para agregar las observaciones presione en
                                            el boton agregar.
                                        @endif
                                    </div>
                                </div>
                                <div class="row ">

                                    @if ($evento->minimo_personas && $evento->maximo_personas)
                                        <div class="col-md-2 at"><a v-if="modificar" class="at"
                                                href="#minimoPersonas" data-bs-toggle="modal"
                                                data-bs-target="#minimoPersonas">
                                                <span class="mdi mdi-pencil"></span> Minimo y maximo de personas:
                                            </a> <a v-else class="at">Actualizar minimo y el maximo de personas:</a>
                                        </div>
                                    @endif


                                    <div class="col-md-10">
                                        @if ($evento->minimo_personas && $evento->maximo_personas)
                                            Minimo personas: {{ $evento->minimo_personas }}, y el maximo de personas es:
                                            {{ $evento->maximo_personas }}
                                        @else
                                            Aun no se han agregado observaciones, para agregar las observaciones presione en
                                            el boton agregar.
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    @if ($evento->encargado)
                                        <div class="col-md-2 at"> <a v-if="modificar" class="at"
                                                href="#actualizarEncargado" data-bs-toggle="modal"
                                                data-bs-target="#actualizarEncargado" role="button">
                                                <span class="mdi mdi-pencil-outline"></span> Encargado del evento:
                                            </a> <a v-else class="at">Encargado del evento:</a></div>
                                    @else
                                        <div class="col-md-2 at"> <a v-if="modificar" class="at"
                                                href="#actualizarEncargado" data-bs-toggle="modal"
                                                data-bs-target="#actualizarEncargado" role="button">
                                                <span class="mdi mdi-plus"></span> Encargado del evento:
                                            </a> <a v-else class="at">Encargado del evento:</a></div>
                                    @endif

                                    <div class="col-md-10">
                                        @if ($evento->encargado)
                                            {{ $evento->encargado }}
                                        @else
                                            Nombre del enecargado del evento / +503 7777 2222 / persona@correo.com
                                        @endif
                                    </div>
                                </div>
                                <div class="row">

                                    @if ($evento->galerias_evento->isEmpty())
                                        <div class="col-md-2 at"><a v-if="modificar" class="at" href="#modalAddFoto"
                                                data-bs-toggle="modal" data-bs-target="#modalAddFoto" role="button">
                                                <span class="mdi mdi-plus"></span> Agregar Imágenes de Referencia:
                                            </a><a v-else class="at">Agregar Imágenes de Referencia:</a></div>
                                    @else
                                        <div class="col-md-2 at "><a v-if="modificar" class="at"
                                                href="#modalAddFoto" data-bs-toggle="modal"
                                                data-bs-target="#modalAddFoto" role="button">
                                                <span class="mdi mdi-pencil-outline"></span>Imágenes de Referencia:
                                            </a><a v-else class="at">Imágenes de Referencia:</a></div>
                                    @endif


                                    <div class="col-md-10">
                                        @if ($evento->galerias_evento)
                                            Se seleccionaron {{ count($evento->galerias_evento) }} imagenes de referencia
                                            para el
                                            evento.
                                        @else
                                            Aun no se han seleccionado imagenes de referencia para el evento
                                        @endif
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-2 at"> <a v-if="modificar" class="at"
                                            href="#modalEditarFecha" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarFecha" role="button">
                                            <span class="mdi mdi-pencil-outline"></span> Fecha de evento:
                                        </a><a v-else class="at">Fecha de evento: </a></div>
                                    <div class="col-md-10">
                                        @if ($evento->fecha)
                                            {{ \Carbon\Carbon::parse($evento->fecha)->isoFormat('dddd, D [de] MMMM [de] YYYY') }},
                                            hora en que iniciara el evento
                                            {{ \Carbon\Carbon::parse($evento->inicio)->format('h:i A') }} y
                                            finalizara el dia
                                            {{ \Carbon\Carbon::parse($evento->fecha_fin)->isoFormat('dddd, D [de] MMMM [de] YYYY') }},
                                            {{ \Carbon\Carbon::parse($evento->finalizacion)->format('h:i A') }}.
                                        @else
                                            Aun no se hay fecha y hora de inicio y finalizacion de evento.
                                        @endif
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-2 at">

                                        <a v-if="modificar" class="at" href="#detalleMontajeEvento"
                                            data-bs-toggle="modal" data-bs-target="#detalleMontajeEvento" role="button">
                                            <span class="mdi mdi-plus"></span> Agregar detalles del montaje:
                                        </a> <a v-else class="at">Agregar detalles del montaje: </a>

                                    </div>

                                    <div class="col-md-10">
                                        @if ($evento->detalle_montaje)
                                            Se agregaron {{ count($evento->detalle_montaje) }} detalles de montaje
                                            para el
                                            evento.
                                        @else
                                            Aun no se han agregado detalles para el evento
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <h4 class="fw-bold">Cuentas agregadas</h4>
                            </div>
                            <!-- section de comandas de evento -->
                            <div class="card cuentas h-100 w-100 p-4 mb-4">
                                <div class="row">
                                    <div class="row mb-3">
                                        <div class="col-8 active">
                                            <h5 v-if="modificar || addCuenta">
                                                @can('eventos.modificar')
                                                    <a class="at" href="#AddComandas" data-bs-toggle="modal"
                                                        data-bs-target="#AddComandas" :data-id="eventoId.cid"
                                                        :data-mesa="numeroDeMesa" role="button">
                                                        <span class="mdi mdi-plus"></span>
                                                        COMANDAS
                                                    </a>
                                                @endcan
                                            </h5>
                                            <h5 v-else>
                                                COMANDAS
                                            </h5>

                                        </div>
                                        <div class="col-4 mb-2">
                                            <input type="text" class="form-control" id="searchComanda"
                                                v-model="txtComanda" placeholder="Buscar por numero de comanda...">

                                        </div>
                                    </div>

                                    <div class="row">
                                        <template v-if="buscarComandas.length > 0">
                                            <div class="col-4"
                                                v-for="({id,cid,tipo_comanda,mesa, clientes, sum_comanda,creacion, facturada, comprobante, estado}, c) in buscarComandas"
                                                :key="c">
                                                <div class="card c1 mb-3"
                                                    :class="{
                                                        'c1': !comprobante,
                                                        'c2': comprobante && !
                                                            facturada,
                                                        'c3': facturada && !estado && comprobante,
                                                        'cortesia': tipo_comanda && !estado,
                                                    
                                                    
                                                    }">
                                                    <div class="card-body d-flex flex-column">

                                                        <div class="d-flex justify-content-between">
                                                            <h5 class="card-title text-start">Comanda </h5>
                                                            <p class="card-subtitle text-end">Nº @{{ id }}</p>
                                                        </div>
                                                        <p class="card-subtitle mb-0"><b>#Mesa: @{{ mesa }}</b>,
                                                            <b> monto:$ @{{ sum_comanda.toFixed(2) }}</b>

                                                        </p>
                                                        <small v-if="comprobante == false">Cuenta abierta</small>
                                                        <small
                                                            v-if="comprobante == true && !facturada && estado == true">Cuenta
                                                            cerrada</small>
                                                        <small
                                                            v-if="facturada == true  && comprobante == true && estado == false && tipo_comanda == 5">Factura
                                                            anticipada</small>
                                                        <small
                                                            v-if="facturada  && comprobante && !estado && tipo_comanda == 3">Cortesia</small>

                                                    </div>
                                                    <div class="mt-auto p-2 text-start mb-2" v-if="comprobante == false">
                                                        @can('comandas.create')
                                                            <a :href="'/eventos/comanda/' + cid + '/' + eventoId.cid"
                                                                class="btn btn-light bt-sm mx-1"><span
                                                                    class="mdi mdi-plus"></span> Agregar</a>
                                                        @endcan
                                                        @can('comandas.bloquear')
                                                            <a :href="'/comandas/bloquear/' + cid"
                                                                class="btn btn-light mx-1"><span
                                                                    class="mdi mdi-lock-open-outline"></span>
                                                                Bloquear</a>
                                                        @endcan

                                                    </div>
                                                    <div class="mt-auto d-flex justify-content-between p-2 mb-2"
                                                        v-if="comprobante == true">
                                                        @can('eventos.comanda')
                                                            <a :href="'/eventos/comanda/' + cid + '/' + eventoId.cid"
                                                                class="btn btn-light bt-sm text-start"><span
                                                                    class="mdi mdi-format-list-bulleted"></span>
                                                                Detalle</a>
                                                        @endcan
                                                        @can('comandas.desbloquear')
                                                            <a class="btn btn-light bt-sm text-start"
                                                                title="Desbloquear cuenta"
                                                                v-if="comprobante == true && facturada == false"
                                                                :href="'/comandas/desbloquear/' + cid"><span
                                                                    class="mdi mdi-lock-open-variant"></span>Desbloquear</a>
                                                        @endcan



                                                        <p class="text-end">@{{ creacion }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <div class="col-12">
                                                <p>No se han agregado comandas a este evento.</p>
                                            </div>
                                        </template>
                                    </div>



                                </div>
                            </div>
                            <!-- section de ordenes de servicio de evento -->
                            <div class="card cuentas h-100 w-100 p-4 mb-3">
                                <div class="row">
                                    <div class="row mb-3">
                                        <div class="col-8 active">
                                            <h5 v-if="modificar || addCuenta">
                                                @can('eventos.modificar')
                                                    <a class="at" href="#AddOrdenes" data-bs-toggle="modal"
                                                        data-bs-target="#AddOrdenes" :data-id="eventoId.cid" role="button">
                                                        <span class="mdi mdi-plus"></span>ORDENES DE SERVICIO AGREGADAS

                                                    </a>
                                                @endcan
                                            </h5>
                                            <h5 v-else>
                                                ORDENES DE SERVICIO AGREGADAS</h5>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <input type="text" class="form-control" id="searchOrder"
                                                v-model="txtOrden" placeholder="Buscar por numero de orden...">

                                        </div>
                                    </div>

                                    <div class="row" v-if="buscarOrdenes.length > 0">
                                        <div class="col-4"
                                            v-for="({id,orden,comprobante,sum_orden,estado,facturada, creacion}, o) in buscarOrdenes"
                                            :key="o">
                                            <div class="card  mb-3"
                                                :class="{
                                                    'c1': !comprobante,
                                                    'c2': comprobante && !
                                                        facturada,
                                                    'c3': !estado && comprobante
                                                }">
                                                <div class="card-body d-flex flex-column ">
                                                    <div class="d-flex justify-content-between">
                                                        <h5 class="card-title text-start">Ordenes</h5>
                                                        <p class="card-subtitle text-end ">Nº @{{ orden }}</p>

                                                    </div>
                                                    <p class="card-subtitle mb-0 ">$ @{{ sum_orden.toFixed(2) }}</p>
                                                    <small v-if="comprobante == false">Cuenta abierta</small>
                                                    <small
                                                        v-if="comprobante == true && !facturada && estado == true">Cuenta
                                                        cerrada</small>
                                                    <small v-if="comprobante == true && estado == false">Factura
                                                        anticipada</small>
                                                </div>
                                                <div class="mt-auto p-2 text-start mb-2" v-if="comprobante == false">
                                                    @can('eventos.ordenes')
                                                        <a :href="'/eventos/eventos/' + id + '/' + eventoId.cid"
                                                            class="btn btn-light bt-sm mx-1"><span
                                                                class="mdi mdi-plus"></span>
                                                            Agregar</a>
                                                    @endcan
                                                    @can('ordenes.bloquear')
                                                        <a :href="'/ordenes/bloquear/' + id" class="btn btn-light mx-1"><span
                                                                class="mdi mdi-lock-open-outline"></span> Bloquear</a>
                                                    @endcan
                                                    @can('ordenes.anular')
                                                        <a v-if="sum_orden == 0" :href="'/ordenes/anular/orden/' + id"
                                                            type="button" class="btn btn-danger mb-1">Eliminar</a>
                                                    @endcan
                                                </div>
                                                <div class="mt-auto d-flex justify-content-between p-2 mb-2"
                                                    v-if="comprobante == true">
                                                    <a :href="'/eventos/eventos/' + id + '/' + eventoId.cid"
                                                        class="btn btn-light bt-sm text-start"><span
                                                            class="mdi mdi-format-list-bulleted"></span> Detalle</a>
                                                    @can('ordenes.desbloquear')
                                                        <a class="btn btn-light bt-sm text-start" title="Desbloquear cuenta"
                                                            v-if="comprobante == true && facturada == false"
                                                            :href="'/ordenes/desbloquear/' + id"><span
                                                                class="mdi mdi-lock-open-variant"></span> Desbloquear</a>
                                                    @endcan
                                                    <p class="text-end">@{{ creacion }}</p>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div v-else class="row">
                                        <div class="col-12">
                                            <p>No hay órdenes en este evento.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- section para eventos que posse el anticipo -->
                            @can('eventos.anticipos')
                                <div class="card cuentas h-100 w-100 p-4 mb-4">
                                    <div class="row">
                                        <div class="row mb-3">
                                            <div class="col-8 active" style="display: flex; align-items: center;">
                                                @can('eventos.modificar')
                                                    <h5><a v-if="eventoId.clientes" class="at "
                                                            :href="'/eventos/anticipos/' + eventoId.cid" role="button">
                                                            <span class="mdi mdi-plus"></span> ANTICIPOS AGREGADOS A ESTE EVENTO

                                                        </a></h5>
                                                @endcan

                                            </div>
                                        </div>
                                        @if ($evento->getAnticipos->count() > 0)
                                            <div class="row ">
                                                @foreach ($evento->getAnticipos as $a)
                                                    <div class="col-4 ">
                                                        <div class="card c3  mb-3">
                                                            <div class="card-body d-flex flex-column text-uppercase">
                                                                <div class="d-flex justify-content-between">
                                                                    <h5 class="card-title text-start">Nº
                                                                        {{ $a->anticipos->id }}
                                                                    </h5>
                                                                    <p class="card-subtitle text-end ">
                                                                        ${{ number_format($a->anticipos->monto, 2) }}</p>
                                                                </div>
                                                                <p class="card-subtitle mb-0 text-uppercase ">
                                                                    {{ $a->anticipos->clientes->nombre }}
                                                                </p>
                                                                <small>{{ $a->anticipos->concepto }}</small>
                                                            </div>
                                                            <div class="mt-auto p-2 text-start mb-2">
                                                                @can('anticipos.print')
                                                                    <a class="btn btn-light m-2" target="_blank"
                                                                        href="{{ route('anticipos.container', ['id' => Crypt::encryptString($a->anticipos->id)]) }}">
                                                                        <span class="mdi mdi-printer"></span>
                                                                        Imprimir
                                                                    </a>
                                                                @endcan
                                                                @can('anticipos.delete')
                                                                    <a class="btn btn-light text-danger" role="button"
                                                                        href="{{ route('anticipos.destroy_asignacion', ['id' => Crypt::encryptString($a->id)]) }}')"
                                                                        onclick="return confirm('¿Estás seguro de eliminar este anticipo del evento?');">
                                                                        <span class="mdi mdi-delete"></span>
                                                                        Eliminar asignacion
                                                                    </a>
                                                                @endcan
                                                            </div>

                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <p>No hay anticipos en este evento.</p>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            @endcan
                            <!-- section para eventos que posee galerias -->
                            <div class="card cuentas h-100 w-100 p-4 mb-4">
                                <div class="row">
                                    <div class="col-8 active">
                                        <h5 v-if="modificar">
                                            <a class="at text-uppercase" href="#modalAddFoto" data-bs-toggle="modal"
                                                data-bs-target="#modalAddFoto" role="button">
                                                <span class="mdi mdi-plus"></span> Galería del evento
                                            </a>
                                        </h5>
                                        <h5 v-else class="at text-uppercase">
                                            Galería del evento
                                        </h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if ($evento->galerias_evento->isEmpty())
                                        <div id="alerta_fotos" class="h5 text-center text-muted">No se han agregado
                                            galerías al evento.</div>
                                    @else
                                        <section class="row" id="contenedorFotos">
                                            @foreach ($evento->galerias_evento as $eg)
                                                @php
                                                    $i = asset('img/' . $eg->galerias->foto);
                                                @endphp
                                                <article class="col-12 col-sm-6 col-md-4 mb-2"
                                                    data-categoria="{{ $eg->galerias->categoria_fotos_id }}">
                                                    <div class="card h-100">
                                                        <div class="card-img-top"
                                                            style="background-image: url('{{ $i }}'); background-size: cover; background-position: center;background-repeat: no-repeat; height: 20vh;"
                                                            aria-label="Foto de la galería del evento"></div>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </section>
                                    @endif
                                </div>
                            </div>
                            <!-- section para eventos que posee resrvacion de habitaciones -->
                            @can('eventos.reservaciones')
                                <div class="card cuentas h-100 w-100 p-4 mb-4">
                                    <div class="row">
                                        <div class="col-8 active">
                                            <h5><a v-if="eventoId.clientes" class="at "
                                                    :href="'/eventos/reservacion/' + eventoId.cid" role="button">
                                                    <span class="mdi mdi-plus"></span> RESERVACIONES AGREGADAS A ESTE EVENTO

                                                </a></h5>

                                        </div>
                                    </div>
                                    <div class="row">
                                        @forelse ($reservas as $r)
                                            @php
                                                $tarifas = $r->detalleReservaciones
                                                    ->map(function ($detalle) {
                                                        return $detalle->relacionTarifas
                                                            ? $detalle->relacionTarifas->tarifa .
                                                                    ' (' .
                                                                    $detalle->relacionTarifas->numero_dias .
                                                                    ' días)'
                                                            : '';
                                                    })
                                                    ->filter()
                                                    ->implode(', ');
                                            @endphp
                                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                                <div class="card c1 h-100">
                                                    <div class="card-body d-flex flex-column justify-content-between">
                                                        <div>
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <h5 class="card-title">Reservación</h5>
                                                                <p class="card-subtitle">Nº {{ $r->id }}</p>
                                                            </div>
                                                            <p class="card-subtitle mb-0 text-uppercase">
                                                                {{ $r->relacionClientes->nombre ?? '' }}</p>
                                                            <p class="card-subtitle mb-0">
                                                                {{ $r->relacionTipoReservaciones->tipo_reservacion ?? '' }} |
                                                                {{ $tarifas }}</p>


                                                        </div>
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                                            <div class="dropdown">
                                                                <button class="btn btn-light dropdown-toggle" type="button"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <span class="mdi  mdi-account-details">Detalles</span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    @if (!$r->eliminado)
                                                                        @if (!$r->completa)
                                                                            @can('reservaciones.habitaciones')
                                                                                <li>
                                                                                    <a class="dropdown-item"
                                                                                        href="{{ route('detalle_reservas.index', ['id' => \Crypt::encryptString($r->id)]) }}"
                                                                                        {{ $r->completa ? 'disabled' : '' }}>
                                                                                        <span class="mdi mdi-plus"></span>
                                                                                        Agregar habitaciones
                                                                                    </a>
                                                                                </li>
                                                                            @endcan
                                                                        @endif
                                                                        @if ($r->completa)
                                                                            @can('reservaciones.imprimir')
                                                                                <li>
                                                                                    <a class="dropdown-item"
                                                                                        href="{{ route('reservaciones.imprimir_container', ['id' => Crypt::encryptString($r->id)]) }}">
                                                                                        <span class="mdi mdi-printer"></span>
                                                                                        Imprimir
                                                                                    </a>
                                                                                </li>
                                                                            @endcan
                                                                        @endif
                                                                    @else
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                                href="{{ route('reservaciones.anulacion_detalle', ['id' => \Crypt::encryptString($r->id)]) }}">
                                                                                <span class="mdi mdi-details"></span>
                                                                                Detalle de anulacion
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                            @can('reservaciones.anular')
                                                                <a class="btn btn-light ms-2"
                                                                    href="{{ route('reservaciones.anular', ['id' => $r->cid]) }}"
                                                                    role="button">
                                                                    <span class="mdi mdi-delete"></span> Eliminar
                                                                </a>
                                                            @endcan
                                                            <p class="mb-0 ms-2">
                                                                {{ \Carbon\Carbon::parse($r->created_at)->diffForHumans() }}
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p>No hay reservaciones en este evento.</p>
                                            </div>
                                        @endforelse
                                    </div>




                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--MODAL EDITAR CLIENTE -->
        <div id="editarCliente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Editar cliente</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            @click="clearClientes()"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-show="clienteSelected == null">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="buscarcliente" class="form-label">Buscar clientes
                                        registrados</label>
                                    <input type="text" class="form-control" id="buscarcliente"
                                        placeholder="Escriba el nombre, numero de identificacion, o telefono..."
                                        v-model="buscarClientes" @keyup="apiSearchClientes()">

                                    <div class="lista shadow-lg w-25">
                                        <ul class="list-group list-group"
                                            v-show="listClientes.length > 0 && buscarClientes.length > 4">
                                            <li v-for="v in listClientes" :key="'cliente_cod_' + v.id"
                                                class="list-group-item list-group-item-action text-uppercase"
                                                @click="setClienteSelected(v)">
                                                @{{ v.cliente }}
                                            </li>
                                        </ul>
                                        <ul class="list-group list-group"
                                            v-show="listClientes.length == 0 && buscarClientes.length > 4">
                                            <li class="list-group-item list-group-item-action text-uppercase">No se
                                                han encontrado registros con los parametros de busqueda, intente
                                                cambiar los parametros de busqueda.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="clienteSelected != null && clienteSelected.id > 0">
                            <form action="{{ route('eventos.edit_cliente') }}" method="post">
                                @csrf
                                <input type="hidden" name="eventos_id"
                                    value="{{ \Crypt::encryptString($evento->id) }}">
                                <div class="col-12">
                                    <h5>Editar cliente</h5>
                                    <div class="card border border-info mb-3">
                                        <input type="hidden" name="clientes_id" :value="clienteSelected.id">
                                        <div class="card-body text-uppercase">
                                            <h5 class="card-title">
                                                <span class="mdi mdi-close float-end pointer text-danger"
                                                    @click="clearClientes()">
                                                </span>
                                                @{{ clienteSelected.cliente }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <h5>Se moveran los siguientes anticipos al nuevo cliente:</h5>
                                    <small>Quite la seccion para los anticipos que no deben cambiar de cliente</small>
                                </div>
                                <div class="row">
                                    @foreach ($evento->getAnticipos as $at)
                                        <div class="col-4 mb-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <input type="checkbox" name="anticipos_id[]" class="form-check-input"
                                                        multiple value="{{ Crypt::encryptString($at->anticipos->id) }}"
                                                        id="anticipo_{{ $at->id }}" checked />
                                                    <span class="float-end">No.{{ $at->anticipos->id }}</span>
                                                </div>
                                                <label class="card-body" for="anticipo_{{ $at->id }}">
                                                    <p class="card-text">
                                                        <b>Monto:</b> ${{ number_format($at->anticipos->monto, 2) }}
                                                        <br>
                                                        <b>Concepto:</b> {{ $at->anticipos->concepto }}
                                                    </p>
                                                </label>
                                                <div class="card-footer">
                                                    Creado: {{ $at->anticipos->created_at }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-12 my-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" v-model="clienteConfirm"
                                            id="confirmEditCliente" value="1">
                                        <label class="form-check-label" for="confirmEditCliente">
                                            Confirmo, estoy seguro de realizar los cambios.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" :disabled="getValidEditCliente">Editar
                                        cliente </button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- MODAL ADD CLIENTE AL EVENTO SI NO POSEE CLIENTE -->
        <div id="addCliente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title"><span class="mdi mdi-plus"></span>Agregar cliente
                        </h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            @click="clearClientes()"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-show="clienteSelected == null">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="buscarcliente" class="form-label">Buscar clientes
                                        registrados</label>
                                    <input type="text" class="form-control" id="buscarcliente"
                                        placeholder="Escriba el nombre, numero de identificacion, o telefono..."
                                        v-model="buscarClientes" @keyup="apiSearchClientes()">

                                    <div class="lista shadow-lg w-25">
                                        <ul class="list-group list-group"
                                            v-show="listClientes.length > 0 && buscarClientes.length > 4">
                                            <li v-for="v in listClientes" :key="'cliente_cod_' + v.id"
                                                class="list-group-item list-group-item-action text-uppercase"
                                                @click="setClienteSelected(v)">
                                                @{{ v.cliente }}
                                            </li>
                                        </ul>
                                        <ul class="list-group list-group"
                                            v-show="listClientes.length == 0 && buscarClientes.length > 4">
                                            <li class="list-group-item list-group-item-action text-uppercase">No se
                                                han encontrado registros con los parametros de busqueda, intente
                                                cambiar los parametros de busqueda.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="clienteSelected != null && clienteSelected.id > 0">
                            <form action="{{ route('eventos.add_cliente') }}" method="post">
                                @csrf
                                <input type="hidden" name="eventos_id"
                                    value="{{ \Crypt::encryptString($evento->id) }}">
                                <div class="col-12">
                                    <h5>Agregar cliente</h5>
                                    <div class="card border border-info mb-3">
                                        <input type="hidden" name="clientes_id" :value="clienteSelected.id">
                                        <div class="card-body text-uppercase">
                                            <h6 class="card-title">
                                                <span class="mdi mdi-close float-end pointer text-danger"
                                                    @click="clearClientes()">
                                                </span>
                                                @{{ clienteSelected.cliente }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 my-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" v-model="clienteConfirm"
                                            id="confirmEditCliente" value="1">
                                        <label class="form-check-label" for="confirmEditCliente">
                                            Confirmo, que estoy seguro de agregar el cliente.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary"
                                        :disabled="getValidEditCliente">Agregar
                                        cliente</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- MODAL PARA EDITAR SALONES ASOCIADO AUN EVENTO Y QUE PERMITA EDITAR O QUITAR -->
        <div class="modal fade" id="modalEditarSalones" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <form action="{{ route('eventos.edit_salones') }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel"><span class="mdi mdi-pencil"></span>
                                Editar salones </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 75vh">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="title text-uppercase">Salones asociados al evento Nº @{{ eventoId.id }}
                                    </h4>
                                    <input type="hidden" name="eventos_id" :value="eventoId.cid">
                                    <div class="mb-3">
                                        <label for="salones" class="form-label">Seleccione los salones a editar en este
                                            evento:</label>
                                        <div class="row">
                                            <div class="col-4 mb-2" v-for="salon in salones" :key="salon.id">

                                                <input class="btn-check" type="checkbox" :name="'salones[]'"
                                                    :value="salon.id" :id="'salones_' + salon.id"
                                                    v-model="selectedSalon">
                                                <label class="card text-center" :for="'salones_' + salon.id">
                                                    <div class="card-body"
                                                        :class="{
                                                            'Salon1': true,
                                                            'card': true,
                                                            'Salon2': selectedSalon.includes(salon.id),
                                                            'Salon': selectedSalon.includes(salon.id),
                                                            'Salon3': salonesOcupadosSet.has(salon.id)
                                                        }">
                                                        <p class="card-text">@{{ salon.salon }}</p>
                                                        <div v-if="salonesOcupadosSet.has(salon.id)">
                                                            <small class="ocupado-message">Ocupado <a
                                                                    :href="'/eventos_salones/eliminar/salon/' + salon.cid"
                                                                    title="Eliminar salon"><span
                                                                        class="float-end mdi mdi-delete"></span></a></small>
                                                        </div>
                                                        <div v-else>
                                                            <small>Disponible</small>
                                                        </div>
                                                    </div>
                                                </label>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><span
                                    class="mdi mdi-close"></span> Cerrar</button>
                            <button type="submit" class="btn btn-primary"
                                :disabled="!salones.length || !selectedSalon.length"><span class="mdi mdi-check"></span>
                                Actualizar salones</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- modal de actualizar fecha -->
        <!-- modal para editar fecha y hora inicio y finalizacion de ele evento -->
        <div id="modalEditarFecha" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <form id="editarFechaForm" action="{{ route('eventos.edit_fecha') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="my-modal-title"><span class="mdi mdi-update"></span> Actualizar
                                fecha y hora de inicio y finalización del evento #{{ $evento->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    @if ($evento->fecha)
                                        <p>
                                            <strong>Fecha de inicio:</strong>
                                            {{ \Carbon\Carbon::parse($evento->fecha)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                        </p>
                                        <p>
                                            <strong>Fecha de finalizacion:</strong>
                                            {{ \Carbon\Carbon::parse($evento->fecha_fin)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                        </p>
                                        <p>
                                            <strong>Hora de inicio:</strong>
                                            {{ \Carbon\Carbon::parse($evento->inicio)->format('h:i A') }}
                                        </p>
                                        <p>
                                            <strong>Hora de finalización:</strong>
                                            {{ \Carbon\Carbon::parse($evento->finalizacion)->format('H:i A') }}
                                        </p>
                                    @else
                                        <p>Aún no se ha definido la fecha y hora de inicio y finalización del evento.</p>
                                    @endif
                                </div>
                            </div>
                            <h5 class="fw-bold">Selecciona las nuevas fechas en que inicia y finaliza y hora de inicio y
                                finalización:</h5>
                            <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="fecha" class="form-label">Fecha de inicio:</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha"
                                        v-model="fecha" required min="{{ now()->toDateString() }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="fecha_fin" class="form-label">Fecha de finalizacion:</label>
                                    <input type="date" class="form-control" id="fecha_fim" name="fecha_fin"
                                        v-model="fecha_fin" required min="{{ now()->toDateString() }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label for="inicio" class="form-label">Hora de inicio:</label>
                                    <input type="time" class="form-control" id="inicio" name="inicio"
                                        v-model="inicio" required>
                                </div>
                                <div class="col-6 mb-2">
                                    <label for="finalizacion" class="form-label">Hora de finalización:</label>
                                    <input type="time" class="form-control" id="finalizacion" name="finalizacion"
                                        v-model="finalizacion" required>
                                </div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="confirm_edit_fecha" value="1"
                                    id="confirm_edit_fecha" required>
                                <label class="form-check-label text-uppercase" for="confirm_edit_fecha">
                                    <span class="badge bg-danger">AL CONFIRMAR SE BORRARAN LAS CONFIGURACIONES DE LOS
                                        SALONES
                                        AGREGADOS, DEBE VOLVER A SELECCIONAR LOS SALONES PARA ESTE CAMBIO.</span>
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-start">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button id="editarFechaBtn" type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!--MODAL AGREGAR COMANDA A EVENTO -->
    <div id="AddComandas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Agregar comandas a evento</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="{{ route('comandas.comandaEvento') }}" method="post">
                            @csrf
                            <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">
                            <div class="col-12 mt-1">
                                <input type="number" class="form-control" id="mesa_comanda" name="mesa_comanda"
                                    placeholder="ingrese el numero de la comanda " required>
                            </div>
                            <div class="col-12 mt-1">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="comfirmacion_comanda" required>
                                    <label class="form-check-label" for="comfirmacion_comanda">
                                        Confirmo, que agregare comandas a este evento
                                        <br>
                                        <small>Por favor, vuelva a revisar antes de agregar </small>
                                    </label>
                                </div>

                            </div>
                            <div class="col">
                                <button id="btnAgregarComanda" type="submit" class="btn btn-primary">Agregar</button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">Cerrar</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--MODAL AGREGAR ordenes A EVENTO -->
    <div id="AddOrdenes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Agregar ordenes a evento</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="{{ route('ordenes.ordenEvento') }}" method="post">
                            @csrf
                            <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">
                            <div class="col-12 mt-1">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="comfirmar_orden" required>
                                    <label class="form-check-label" for="comfirmar_orden">
                                        Confirmo, que agregare una orden de servicio a este evento.
                                        <br>
                                        <small>Por favor, vuelva a revisar antes de agregar </small>
                                    </label>
                                </div>

                            </div>
                            <div class="col ">
                                <button type="submit" class="btn btn-primary  ">Agregar</button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">Cerrar</button>

                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal asignar sonidos a evento -->
    <div id="asignarSonidos" class="modal fade" tabindex="-1" aria-labelledby="asignarSonidosLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="asignarSonidosLabel">Agregar sonido al evento #{{ $evento->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="sonidoForm" action="{{ route('eventos.asignarSonido') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="sonidos_id" class="form-label">Seleccionar el sonido a utilizar en el
                                evento</label>
                            <select class="form-select" id="sonidos_id" name="sonidos_id">
                                <option value="" disabled selected>Seleccione los sonidos</option>
                                @foreach ($sonidos as $s)
                                    <option value="{{ Crypt::encryptString($s->id) }}">{{ $s->sonido }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (isset($evento->sonidos))
                            <div class="mb-3">
                                <label class="form-label">Sonido asignado</label>
                                <input type="text" class="form-control" value="{{ $evento->sonidos->sonido }}"
                                    readonly />
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="observaciones_sonidos" class="form-label">Observaciones sonido</label>
                            <textarea class="form-control" id="observaciones_sonidos" name="observaciones_sonidos" rows="3"
                                placeholder="Escriba las observaciones de sonido aquí...">{{ $evento->observaciones_sonidos ?? '' }}</textarea>
                        </div>

                        <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">

                        <div class="d-flex justify-content-start">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal asignar montajes a evento -->
    <div id="asignarMontajes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Asignar montaje a evento #
                        {{ $evento->id }}
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="montajeForm" action="{{ route('eventos.asignar_montaje') }}" method="POST">
                        @csrf

                        <div class="modal-body">
                            <div class="mb-3">
                                <div class=" mb-3">
                                    <div>
                                        @if ($evento->montajes)
                                            <h5 class="card-title">Tipo de montaje actual:</h5>
                                            <input class="form-control " value="{{ $evento->montajes->montaje }}"
                                                readonly />
                                        @else
                                            <h5 class="card-title">Asignar el tipo de montaje al evento</h5>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 mt-2 mb-2">
                                    <div class="form-group">
                                        <label for="momtajes_id">Seleccionar los montajes a utilizar en el evento</label>
                                        <select class="form-select" id="montajes_id" name="montajes_id" required>
                                            <option selected disabled>Seleccione los montajes
                                            </option>
                                            @foreach ($montajes as $m)
                                                <option value="{{ Crypt::encryptString($m->id) }}">
                                                    {{ $m->montaje }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="montaje" class="form-label">Montaje:
                                    </label>
                                    <textarea class="form-control h-100" id="montaje" name="montaje" placeholder="Escriba aqui..." rows="4"
                                        style="resize: vertical;"></textarea>

                                </div>
                                <div class="mb-3">

                                    <!-- Campo oculto para el ID de la evento encriptado -->
                                    <input type="hidden" name="eventos_id"
                                        value="{{ Crypt::encryptString($evento->id) }}">
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="montajeNBtn" type="submit" class="btn btn-primary">Guardar</button>

                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal para actualizar el tipo de evento  a este  evento -->
    <div id="editarTipo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Actualizar el tipo de evento a este evento #
                        {{ $evento->id }}
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tipoEventoForm" action="{{ route('eventos.actualizarTipoEvento') }}" method="POST">
                        @csrf

                        <div class="modal-body">
                            <div class="mb-3">
                                <div class=" mb-3">
                                    <div>
                                        @if ($evento->tipo_eventos)
                                            <h5 class="card-title">Tipo de evento asignado actual:</h5>
                                            <input class="form-control " value="{{ $evento->tipo_eventos->evento }}"
                                                readonly />
                                        @else
                                            <h5 class="card-title">Asignar el tipo de evento al evento</h5>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="form-group">
                                        <label for="my-input">Seleccionar el tipo de evento si desea actualizarlo</label>
                                        <select class="form-select" aria-label="TipoEventos" name="tipo_eventos_id"
                                            required>
                                            <option selected disabled>Seleccione tipos de eventos
                                            </option>
                                            @foreach ($tipo_eventos as $te)
                                                <option value="{{ Crypt::encryptString($te->id) }}" selected>
                                                    {{ $te->evento }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">

                                    <!-- Campo oculto para el ID de la evento encriptado -->
                                    <input type="hidden" name="eventos_id"
                                        value="{{ Crypt::encryptString($evento->id) }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button id="tEventoBtn" type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- modal agregar detalle de montajes al evento -->
    <div class="modal fade" id="detalleMontajeEvento" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">
                        Agregar detalles del montaje al evento {{ $evento->id }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('eventos.detalle_montaje_evento') }}" method="post">
                    @csrf
                    <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">

                    <div class="modal-body">
                        <div class="form-group mb-1">
                            <label for="rotafolio_plumon" class="form-label">Rotafolio y plumon:</label>
                            <input type="number" class="form-control" name="rotafolio_plumon" id="rotafolio_plumon"
                                placeholder="Escriba aqui la cantidad solicitada " />
                        </div>
                        <div class="form-group mb-1">
                            <label for="bandera" class="form-label">Banderas:</label>
                            <input type="text" class="form-control" name="bandera" id="bandera"
                                placeholder="Escriba aqui las banderas requeridas " />
                        </div>
                        <div class=" form-group mb-1">
                            <label for="podium" class="form-label">Podium:</label>
                            <input type="text" class="form-control" name="podium" id="podium"
                                placeholder="Escriba aqui los detalles del podium aqui " />
                        </div>
                        <div class="form-group mb-1">
                            <label for="pista_baile" class="form-label">Pista de baile:</label>
                            <input type="text" class="form-control" name="pista_baile" id="pista_baile"
                                placeholder="Escriba aquí las dimensiones de la pista de baile" />
                        </div>
                        <div class="form-group mb-1">
                            <label for="tarima" class="form-label">Tarima :</label>
                            <input type="text" class="form-control" name="tarima" id="tarima"
                                placeholder="Escriba aqui los detalles de la tarima a montar" />
                        </div>

                        <div class="form-group mb-1">
                            <label for="otros" class="form-label">Otros :</label>
                            <input type="text" class="form-control" name="otros" id="otros"
                                placeholder="Escriba aqui si se asignaron otros detalles extras " />
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="confirm" value="1"
                                id="confirm" required>
                            <label class="form-check-label" for="confirm">
                                Confirmar la asignacion de detalles de montaje al evento.
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal actualizar encargado a este evento -->
    <div id="actualizarEncargado" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Actualizar el encargado del evento #
                        {{ $evento->id }}
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="encargadoForm" action="{{ route('eventos.actualizarEncargado') }}" method="POST">
                        @csrf

                        <div class="mb-2">
                            <div class="mb-0">
                                <!-- Campo oculto para el ID de la evento encriptado -->
                                <input type="hidden" name="eventos_id"
                                    value="{{ Crypt::encryptString($evento->id) }}">
                            </div>


                            <div class="mb-3">
                                <label for="encargado" class="form-label">Encargado:
                                </label>
                                <textarea class="form-control h-100" id="encargado" name="encargado"
                                    placeholder="Escriba aqui el nombre del enecargado del evento / +503 7777 2222 / persona@correo.com"
                                    rows="3" style="resize: vertical;">{{ $evento->encargado }}</textarea>

                            </div>
                        </div>


                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="encargadoBtn" type="submit" class="btn btn-primary">Actualizar</button>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!--Modal observaciones generales del evento-->
    <div class="modal fade" id="modalObservacionesGenerales" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form action="{{ route('eventos.observacion_general') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"><span
                                class="mdi mdi-eye-arrow-right-outline"></span>
                            Observaciones generales del evento Nº {{ $evento->id }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            @click="limpiar"></button>
                    </div>
                    <div class="modal-body" style="max-height: 75vh">
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <div class="mb-2">

                                        <div class="mb-3">
                                            <input type="hidden" name="eventos_id"
                                                value="{{ Crypt::encryptString($evento->id) }}">

                                            <div class=" mb-3">
                                                <label for="observaciones" class="form-label">Agregue observacion
                                                    general
                                                    o
                                                    si desea actualizar:
                                                </label>
                                                <textarea class="form-control h-100" id="observaciones" name="observaciones" rows="8"
                                                    placeholder="Escriba las observaciones generales del evento No. {{ $evento->id }} (max. 200 caracteres)"
                                                    style="resize: vertical;">{{ $evento->observaciones }}</textarea>

                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal" @click="limpiar">
                                            <span class="mdi mdi-close"></span> Cerrar
                                        </button>
                                        <button type="submit" class="btn btn-primary"><span
                                                class="mdi mdi-check"></span>Guardar</button>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!--Modal para agregar fotos y conectarla con eventos_galerias-->
    <div class="modal fade" id="modalAddFoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <form action="{{ route('galerias.add_foto') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"><span class="mdi mdi-plus"></span>
                            Agregar imagenes de referencia </h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            @click="limpiar"></button>
                    </div>

                    <div class="modal-body" style="max-height: 75vh">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="">
                                    <div class="card-body">


                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <a class=" btn btn-primary" href="#modalAddFotoGaleria"
                                                    data-bs-toggle="modal" data-bs-target="#modalAddFotoGaleria"
                                                    role="button">
                                                    <span class="mdi mdi-plus"></span> Cargar foto
                                                </a>
                                            </div>
                                        </div>


                                        <h4 class="card-title text-uppercase">
                                            Imágenes de referencia del evento Nº {{ $evento->id }}
                                        </h4>
                                        <input type="hidden" name="eventos_id"
                                            value="{{ Crypt::encryptString($evento->id) }}">

                                        <div class="text-uppercase mb-3">
                                            <label class="form-label">filtrar galerias por categorias:</label>
                                            <div>
                                                @foreach ($categoria_fotos as $categoria)
                                                    <label class="btn btn-outline-primary">
                                                        <input type="checkbox" name="categoria_foto"
                                                            value="{{ $categoria->id }}" class="d-none">
                                                        {{ $categoria->categoria }}
                                                    </label>
                                                @endforeach
                                                <label class="btn btn-outline-primary">
                                                    <input type="checkbox" name="categoria_foto" value=""
                                                        class="d-none">
                                                    Todas las categorías
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row " id="contenedorFotos">
                                            @foreach ($galerias as $g)
                                                @php
                                                    $image = asset('img/' . $g->foto);
                                                @endphp
                                                <div class="col-4 mb-3" data-categoria="{{ $g->categoria_fotos_id }}"
                                                    style=" width: 30%; height: 45vh;">
                                                    <input class="btn-check" type="checkbox" name="galerias[]"
                                                        value="{{ $g->id }}"
                                                        id="galerias_{{ $g->id }}" multiple>

                                                    <label class="card btn btn-primary text-start"
                                                        for="galerias_{{ $g->id }}"
                                                        style="width: 100%; height: 100%; display: block;
                                                                    background-image: url('{{ $image }}');
                                                                    background-size: cover;
                                                                    background-position: center;
                                                                    background-repeat: no-repeat;
                                                                    ">
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div id="alerta" class="h5" style="display: none;">No se han agregado
                                            fotos a esta
                                            categoría.</div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            @click="limpiar">
                            <span class="mdi mdi-close"></span> Cerrar
                        </button>
                        @if (count($evento->galerias_evento) > 0)
                            <a class=" btn btn-primary" href="#updateGaleria" data-bs-toggle="modal"
                                data-bs-target="#updateGaleria" role="button">
                                <span class="mdi mdi-pencil"></span> Actualizar
                            </a>
                        @endif
                        <button id="addGaleriaBtn" type="submit" class="btn btn-primary"><span
                                class="mdi mdi-check"></span>Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Modal para agregar fotos a galerias por si se requiere -->
    <div class="modal fade" id="modalAddFotoGaleria" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <form action="{{ route('galerias.cargar_galeria') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="staticBackdropLabel"><span class="mdi mdi-plus"></span>
                            Agregar
                            nueva galería </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">
                        <div class="mb-3">
                            <label for="foto" class="form-label">Fotos:</label>
                            <input type="file" class="form-control" id="foto" name="foto"
                                accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción:</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="categoria_fotos_id" class="form-label">Seleccionar la categoría de la
                                foto:</label>
                            <select class="form-select" id="categoria_fotos_id" name="categoria_fotos_id" required>
                                <option selected disabled>Seleccione la categoría</option>
                                @foreach ($categoria_fotos as $c)
                                    <option value="{{ Crypt::encryptString($c->id) }}">{{ $c->categoria }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="addGaleriaFotoBtn" type="submit" class="btn btn-primary"><span
                                class="mdi mdi-check"></span> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal para aactualizar las galerias de evento ya se reafactorizo la imagen con background   -->
    <div class="modal fade" id="updateGaleria" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <form action="{{ route('galerias.edit_galeria') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"><span class="mdi mdi-pencil"></span>
                            Actualizar imagenes de referencia que ya posee el evento #{{ $evento->id }} </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            @click="limpiar"></button>
                    </div>
                    <div class="modal-body" style="max-height: 75vh">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="">
                                    <div class="card-body">
                                        <h4 class="card-title text-uppercase">
                                            actualizacion de imágenes de referencia del evento Nº {{ $evento->id }}
                                        </h4>
                                        <input type="hidden" name="eventos_id"
                                            value="{{ Crypt::encryptString($evento->id) }}">

                                        <div class="text-uppercase mb-3">
                                            <label class="form-label">filtrar galeria:</label>
                                            <div>
                                                @foreach ($categoria_fotos as $categoria)
                                                    <label class="btn btn-outline-primary">
                                                        <input type="checkbox" name="categoria_foto"
                                                            value="{{ $categoria->id }}" class="d-none">
                                                        {{ $categoria->categoria }}
                                                    </label>
                                                @endforeach
                                                <label class="btn btn-outline-primary">
                                                    <input type="checkbox" name="categoria_foto" value=""
                                                        class="d-none">
                                                    Todas las categorías
                                                </label>
                                            </div>
                                        </div>

                                        <div class="row" id="contenedorFotos">
                                            @foreach ($evento->galerias_evento as $eg)
                                                @php
                                                    $i = asset('img/' . $eg->galerias->foto);
                                                @endphp
                                                <div class="col-4 mb-2"
                                                    data-categoria="{{ $eg->galerias->categoria_fotos_id }}"
                                                    style="width: 30%; height: 45vh;">
                                                    <input class="btn-check" type="checkbox" name="galeriasEvento[]"
                                                        value="{{ $eg->galerias->id }}"
                                                        id="galeriasEvento_{{ $eg->galerias->id }}" multiple
                                                        style="position: absolute; opacity: 0;">

                                                    <label class="card btn btn-primary text-start"
                                                        for="galeriasEvento_{{ $eg->galerias->id }}"
                                                        style="width: 100%; height: 100%; display: block;
                                                        background-image: url('{{ $i }}');
                                                        background-size: cover;
                                                        background-position: center;
                                                        background-repeat: no-repeat;">
                                                    </label>
                                                </div>
                                            @endforeach

                                        </div>
                                        <div id="alerta_fotos" class="h5" style="display: none;">No se han
                                            agregado fotos a esta
                                            categoría.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button id="addGaleriaBtn" type="submit" class="btn btn-primary"><span
                                class="mdi mdi-check"></span>Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal actualizar observaciones sonidos a evento -->
    <div id="observacionSonidos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Actualizar observaciones a sonidos del evento #
                        {{ $evento->id }}
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ObservacionsonidoForm" action="{{ route('eventos.sonido_observacion') }}"
                        method="POST">
                        @csrf



                        <div class="mb-1">

                            <!-- Campo oculto para el ID de la evento encriptado -->
                            <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">
                        </div>

                        <div class="mb-3">
                            <label for="observacion_sonido" class="form-label">Observaciones sonido:
                            </label>
                            <textarea class="form-control h-100" id="observaciones_sonidos" name="observaciones_sonidos"
                                placeholder="Escriba las observaciones de sonido aqui ..." rows="6" style="resize: vertical;">{{ $evento->observaciones_sonidos }}</textarea>

                        </div>


                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="observacionSonidoBtn" type="submit" class="btn btn-primary">Guardar</button>

                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal actualizar montaje a evento -->
    <div id="observacionMontaje" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Actualizar observaciones del montaje del evento #
                        {{ $evento->id }}
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ObservacionesMontajeForm" action="{{ route('eventos.update_montaje') }}"
                        method="POST">
                        @csrf
                        <div class="mb-1">

                            <!-- Campo oculto para el ID de la evento encriptado -->
                            <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">
                        </div>

                        <div class="mb-3">
                            <label for="montaje" class="form-label">Observacione del montaje:
                            </label>
                            <textarea class="form-control h-100" id="montaje" name="montaje"
                                placeholder="Escriba las observaciones de montaje aqui ..." rows="6" style="resize: vertical;">{{ $evento->montaje }}</textarea>

                        </div>



                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="observacionMontajeBtn" type="submit" class="btn btn-primary">Actualizar</button>

                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- modal para editar minimo y maximo de personas del  evento -->
    <div id="minimoPersonas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="minimoPersonasForm" action="{{ route('eventos.cantidad_personas') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title"><span class="mdi mdi-update"></span> Actualizar el
                            minimo y maximo de personas del evento #{{ $evento->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <h5 class="fw-bold">Actualiza el minimo y maximo de personas:</h5>
                        <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">

                        <div class="row">
                            <div class="col-6 mb-2">
                                <label for="minimo_personas" class="form-label">Minimo de personas:</label>
                                <input type="text" class="form-control" id="minimo_personas"
                                    name="minimo_personas" value="{{ $evento->minimo_personas }}"
                                    :max="maximo_personas" required>
                            </div>
                            <div class="col-6 mb-2">
                                <label for="maximo_personas" class="form-label">Maximo de personas:</label>
                                <input type="text" class="form-control" id="maximo_personas"
                                    name="maximo_personas" value="{{ $evento->maximo_personas }}"
                                    :min="minimo_personas" required>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="confirm_edit_personas"
                                value="1" id="confirm_edit_personas" required>
                            <label class="form-check-label text-uppercase" for="confirm_edit_personas">
                                <span class="">Confirma actualizar el minimo de personas y el maximo de personas del
                                    evento</span>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-start">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="editarFechaBtn" type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal para agregar observaciones de como facturar el evento y actualizarlas a evento -->
    <div id="observacionFactura" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Agregar observaciones de como se va facturar el evento #
                        {{ $evento->id }}
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ObservacionesFacturaForm" action="{{ route('eventos.observacion_factura') }}"
                        method="POST">
                        @csrf
                        <div class="mb-1">

                            <!-- Campo oculto para el ID de la evento encriptado -->
                            <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">
                        </div>

                        <div class="mb-3">
                            <label for="montaje" class="form-label">Observacione para facturar:
                            </label>
                            <textarea class="form-control h-100" id="observaciones_facturacion" name="observaciones_facturacion"
                                placeholder="Escriba las observaciones de facturacion aqui ..." rows="6" style="resize: vertical;">{{ $evento->observaciones_factura }}</textarea>

                        </div>



                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="observacionFacturaBtn" type="submit" class="btn btn-primary">Guardar</button>

                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
@section('script')
    <script>
        new Vue({
            el: '#appEventoDetalle',
            data: {
                eventoId: @json($evento),
                ordenes: @json($ordenes),
                comandas: @json($comandas),
                salones: @json($salones),
                txtComanda: '',
                numeroDeMesa: '',
                txtOrden: '',
                txtAnticipo: '',
                titular: '',
                listClientes: [],
                buscarClientes: '',
                clienteSelected: null,
                clientes_cod: parseInt('{{ $evento->clientes_id }}'),
                clienteConfirm: false,
                confirmEdit: false,
                selectedSalon: [],
                selectSalones: [],
                salonesOcupadosSet: new Set(),
                message: {},
                inicio: '',
                fecha: '',
                fecha_fin: '',
                finalizacion: '',
                minimo_personas: '',
                maximo_personas: '',
                retraso: @json($retraso),
            },
            methods: {
                async getDisponibilidad() {
                    try {
                        const response = await axios.post(
                            "{{ route('eventos_salones.salon_evento') }}", {
                                eventos_id: this.eventoId.cid,

                            });
                        this.salonesOcupadosSet = new Set(response.data.salonesEvento.map(salon => salon.id));

                    } catch (error) {
                        console.error(error);
                    }
                },
                apiSearchClientes: function() {
                    if (this.buscarClientes.length > 4) {
                        axios.post("{{ route('clientes.apiGetClientes') }}", {
                                busqueda: (this.buscarClientes).toUpperCase(),
                            })
                            .then((rs) => {
                                this.listClientes = rs.data.clientes;
                            })
                            .catch(error => {
                                console.log('Error JS: ', error);
                            })
                    }
                },
                setClienteSelected: function(v) {
                    if (this.clientes_cod != v.id)
                        this.clienteSelected = v;
                    else
                        alert('Esta eligiendo el mismo cliente asignado');
                },
                clearClientes: function() {
                    this.buscarClientes = '';
                    this.listClientes = [];
                    this.clienteSelected = null;
                },
                selectSalon(salon) {
                    if (this.selectedSalon.has(salon.id)) {
                        // El salón ya está seleccionado, así que lo deseleccionamos
                        this.selectedSalon.delete(salon.id);
                    } else {
                        // El salón no está seleccionado, así que lo seleccionamos
                        this.selectedSalon.add(salon.id);
                    }
                },
                validaInicio() {
                    const currentDateTime = new Date();
                    currentDateTime.setHours(currentDateTime.getHours() + 3);

                    const selectedStartTime = new Date(this.fecha + 'T' + this.inicio);
                    this.isFormValid = selectedStartTime >= currentDateTime && this.finalizacion !== '';

                    if (this.finalizacion) {
                        this.validateEndTime();
                    }
                },

            },
            mounted() {

                document.addEventListener('DOMContentLoaded', function() {
                    const checkboxes = document.querySelectorAll('input[name="categoria_foto"]');
                    const fotos = document.querySelectorAll('.col-4');
                    const alerta_fotos = document.getElementById('alerta_fotos');
                    const alerta = document.getElementById('alerta');

                    checkboxes.forEach(function(checkbox) {
                        checkbox.addEventListener('change', function() {
                            const catSelected = this.value;
                            let fotosEncontradas = false;

                            fotos.forEach(function(foto) {
                                const categoriaFoto = foto.getAttribute(
                                    'data-categoria');
                                if (catSelected === '' || categoriaFoto ===
                                    catSelected) {
                                    foto.style.display = 'block';
                                    fotosEncontradas = true;
                                } else {
                                    foto.style.display = 'none';
                                }
                            });

                            if (!fotosEncontradas) {
                                alerta_fotos.style.display = 'block';
                                alerta.style.display = 'block';
                            } else {
                                alerta_fotos.style.display = 'none';
                                alerta.style.display = 'none';
                            }
                        });
                    });
                });
                document.addEventListener('DOMContentLoaded', function() {
                    const imageCheckboxes = document.querySelectorAll(
                        'input[type="checkbox"][name="galerias[]"]');

                    imageCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', function() {
                            const label = this.nextElementSibling;

                            if (this.checked) {
                                label.classList.add('selected-image');
                                label.classList.remove('unselected-image');
                                label.parentNode.classList.add('selected');
                            } else {
                                label.classList.remove('selected-image');
                                label.classList.add('unselected-image');
                                label.parentNode.classList.remove('selected');
                            }
                        });
                    });
                });

                const updateGaleriaModal = document.getElementById('updateGaleria');

                updateGaleriaModal.addEventListener('shown.bs.modal', function() {
                    const imageCheckboxes = updateGaleriaModal.querySelectorAll(
                        'input[type="checkbox"][name="galeriasEvento[]"]');
                    imageCheckboxes.forEach(checkbox => {

                        checkbox.addEventListener('change', function() {
                            const label = this.nextElementSibling;
                            if (this.checked) {
                                label.classList.add('selected-image');
                                label.classList.remove('unselected-image');
                                label.parentNode.classList.add('selected');
                            } else {
                                label.classList.remove('selected-image');
                                label.classList.add('unselected-image');
                                label.parentNode.classList.remove('selected');
                            }
                        });
                    });
                });

                const inputInicio = document.getElementById('inicio');
                const inputFinalizacion = document.getElementById('finalizacion');
                inputFinalizacion.addEventListener('change', function() {
                    if (inputInicio.value && inputFinalizacion.value) {
                        if (inputInicio.value >= inputFinalizacion.value) {
                            alert(
                                'La hora de inicio debe ser menor que la hora de finalización.');


                        }
                    }
                });


            },
            computed: {
                buscarComandas() {

                    const regex = new RegExp(this.txtComanda, 'i');
                    return this.comandas.filter((c) => c.clientes && regex.test(c.clientes
                        .nombre) || (c.titular && regex.test(c.titular)) || c.id === parseInt(this
                        .txtComanda));
                },
                buscarOrdenes() {
                    const rgx = new RegExp(this.txtOrden, 'i');
                    return this.ordenes.filter((o) => o.clientes && rgx.test(o.clientes.nombre) || (o.titular && rgx
                            .test(o.titular)) || o.orden === this
                        .txtOrden);
                },
                getValidEditCliente: function() {
                    console.log(this.clienteConfirm, this.clienteSelected != null, this.clienteSelected.id > 0)
                    return !(this.clienteConfirm &&
                        this.clienteSelected != null &&
                        this.clienteSelected.id > 0);
                },
                modificar() {
                    return ((!this.eventoId.autorizacion && !this.eventoId.autoriza && !this.eventoId
                        .modificacion &&
                        !this.eventoId.solicita) || (this.eventoId.observacion_negacion && this.eventoId
                        .negacion_users_id && !this.eventoId.autorizacion && !this.eventoId.autoriza));
                },
                addCuenta() {
                    return ((this.eventoId.autorizacion && this.eventoId.autoriza && this.eventoId.modificacion));
                }

            },
            watch: {
                inicio: function(newValue, oldValue) {
                    const currentDate = new Date();
                    currentDate.setUTCHours(currentDate.getUTCHours() - 6);
                    currentDate.setHours(currentDate.getHours() + this.retraso);
                    currentDate.setMinutes(currentDate.getMinutes() + 5);
                    const fechaActual = currentDate.toISOString().split('T')[0];
                    let horaActual = currentDate.toISOString().split('T')[1].substring(0, 5);

                    if (this.fecha === fechaActual || !this.fecha) {
                        if (newValue < horaActual) {
                            this.inicio = horaActual;
                        }
                    } else if (this.fecha > fechaActual) {
                        console.log('Asignando nuevo valor de inicio:', newValue);
                        this.inicio;
                    }
                }
            },

        });
    </script>
@endsection
