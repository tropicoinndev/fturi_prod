@if (isset($table) && isset($d))
    <div class="dropdown">
        <button class="btn btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="mdi mdi-cog"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <span class="text-mutted p-3">
                    Opciones
                </span>
            </li>
            @if (Route::has($table . '.show'))
                @can($table . '.index')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.show', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="mdi mdi-eye"></span>
                            Ver mas
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.detalleProducto'))
                @can($table . '.index')
                    @php
                        $mostrarDetalleProducto = $table === 'productos' && $d->categoria->token !== 1201;
                    @endphp

                    @if ($mostrarDetalleProducto)
                        <li>
                            <a class="dropdown-item"
                                href="{{ route($table . '.detalleProducto', ['id' => \Crypt::encryptString($d->id)]) }}">
                                <span class="mdi mdi-details""></span>
                                Detalle producto
                            </a>
                        </li>
                    @endif
                @endcan
            @endif
            @if (Route::has($table . '.detallePrecio'))
                @can($table . '.index')
                    @php
                        $mostrarDetallePrecio = $table === 'precios' && $d->categorias_precios->token !== 1105;
                    @endphp

                    @if ($mostrarDetallePrecio)
                        <li>
                            <a class="dropdown-item"
                                href="{{ route($table . '.detallePrecio', ['id' => \Crypt::encryptString($d->id)]) }}">
                                <span class="mdi mdi-cart-plus"></span>
                                Detalle precio
                            </a>
                        </li>
                    @endif
                @endcan
            @endif
            @if (Route::has($table . '.detalle'))
                @can($table . '.index')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.detalle', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="mdi mdi-cart-plus"></span>
                            Tarifa detalles
                        </a>
                    </li>
                @endcan
            @endif

            @if (Route::has($table . '.anularAnticipo'))
                @can($table . '.anular_anticipo')
                    @php
                        // Verificar si el anticipo está aplicado
                        $Anulable =
                            $table === 'anticipos' &&
                            $d->monto === $d->monto_historico &&
                            !$d->anulado &&
                            !$d->separado;
                    @endphp
                    @if ($Anulable)
                        <li>
                            <a class="dropdown-item"
                                href="{{ route($table . '.anularAnticipo', ['id' => \Crypt::encryptString($d->id)]) }}">
                                <span class="mdi mdi-cash-off"></span>
                                Anular anticipo
                            </a>
                        </li>
                    @endif
                @endcan
            @endif



            @if (Route::has($table . '.status'))
                @can($table . '.status')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.status', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="font-bold mdi mdi-{{ $d->estado ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->estado ? 'Desactivar ' : 'Activar' }}
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.hospedaje'))
                @can($table . '.hospedaje')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.hospedaje', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="font-bold mdi mdi-{{ $d->estado ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->hospedaje ? 'Desactivar cobros hospedaje' : 'Activar cobros hospedaje' }}
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.salon'))
                @can($table . '.salon')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.salon', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="font-bold mdi mdi-{{ $d->salon ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->salon ? 'Desactivar salon ' : 'Activar salon' }}
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.print'))
                @can($table . '.create')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.print', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->estado ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            Imprimir
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.requisicion'))
                @can($table . '.create')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.printRequisicion', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->estado ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            Imprimir requisicion
                        </a>
                    </li>
                @endcan
            @endif

            @if (Route::has($table . '.statusPrecios'))
                @can($table . '.statusPrecios')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusPrecios', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->estado ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->estado ? 'Desactivar Precio ' : 'Activar Precio' }}
                        </a>
                    </li>
                @endcan
            @endif

            @if (Route::has($table . '.statusServiciosIva'))
                @can($table . '.statusServiciosIva')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusServiciosIva', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="font-bold mdi mdi-{{ $d->iva ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->iva ? 'Quitar IVA' : 'Agregar IVA' }}
                        </a>
                    </li>
                @endcan
            @endif

            @if (Route::has($table . '.statusServiciosCesc'))
                @can($table . '.statusServiciosCesc')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusServiciosCesc', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="font-bold mdi mdi-{{ $d->cesc ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->cesc ? 'Quitar CESC' : 'Agregar CESC' }}
                        </a>
                    </li>
                @endcan
            @endif

            @if (Route::has($table . '.statusServiciosAdvalorem'))
                @can($table . '.statusServiciosAdvalorem')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusServiciosAdvalorem', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->advalorem ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->advalorem ? 'Quitar Ad-valorem' : 'Agregar Ad-valorem' }}
                        </a>
                    </li>
                @endcan
            @endif

            @if (Route::has($table . '.statusServiciosPropina'))
                @can($table . '.statusServiciosPropina')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusServiciosPropina', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->propina ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->propina ? 'Quitar Propina' : 'Agregar Propina' }}
                        </a>
                    </li>
                @endcan
            @endif

            @if (Route::has($table . '.statusServiciosPrecios'))
                @can($table . '.statusServiciosPrecios')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusServiciosPrecios', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->precios ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->precios ? 'Bloquear Precios' : 'Desbloquear Precios' }}
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.serviciosDescuento'))
                @can($table . '.serviciosDescuento')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.serviciosDescuento', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->descuento ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->descuento ? 'Desactivar descuento' : 'Activar descuento' }}
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.statusPreciosIva'))
                @can($table . '.statusPreciosIva')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusPreciosIva', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="font-bold mdi mdi-{{ $d->iva ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->iva ? 'Desactivar IVA' : 'Activar IVA' }}
                        </a>
                    </li>
                @endcan
            @endif

            @if (Route::has($table . '.statusPreciosPropina'))
                @can($table . '.statusPreciosPropina')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusPreciosPropina', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->propina ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->propina ? 'Desactivar Propina' : 'Activar Propina' }}
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.statusPreciosDescuentos'))
                @can($table . '.statusPreciosDescuentos')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusPreciosDescuentos', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->descuento ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->descuento ? 'Bloquear Descuento' : 'Permitir Descuento' }}
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.statusPreciosConstante'))
                @can($table . '.statusPreciosConstante')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusPreciosDescuentos', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->constante ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->constante ? 'Desactivar Constante' : 'Activar Constante' }}
                        </a>
                    </li>
                @endcan
            @endif

            @if (Route::has($table . '.statusPermiteCredito'))
                @can($table . '.statusPermiteCredito')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusPermiteCredito', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->permite_credito ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->permite_credito ? 'Bloquear Credito' : 'Desbloquear Credito' }}
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.statusPaquete'))
                @can($table . '.statusPaquete')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusPaquete', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->paquete ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->paquete ? 'No incluye comida y bebida ' : 'Incluye comida y bebida' }}
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.statusTarifas'))
                @can($table . '.statusTarifas')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.statusTarifas', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->paquete ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->estado ? 'Desactivar tarifa ' : 'Activar tarifa' }}
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.bloquear'))
                @can($table . '.bloquear')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.bloquear', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span
                                class="font-bold mdi mdi-{{ $d->paquete ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->bloqueado ? 'Desbloquear' : 'Bloquear' }}
                        </a>
                    </li>
                @endcan
            @endif

            {{--Personas Alertas--}}
            @if(Route::has($table.'.ilicita'))
                @can($table.'.ilicita')
                    <li>
                        <a class="dropdown-item" href="{{ route($table.'.ilicita', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="font-bold mdi mdi-{{ $d->ilicita ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->ilicita ? 'No' : 'Si' }} es buscada
                        </a>
                    </li>
                @endcan
            @endif
            @if(Route::has($table.'.peps'))
                @can($table.'.peps')
                    <li>
                        <a class="dropdown-item" href="{{ route($table.'.peps', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="font-bold mdi mdi-{{ $d->peps ? 'toggle-switch-off' : 'toggle-switch' }}"></span>
                            {{ $d->peps ? 'No' : 'Si' }} es perseguida
                        </a>
                    </li>
                @endcan
            @endif
            {{--------------------}}


            @if (Route::has($table . '.edit'))
                @can($table . '.edit')
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.edit', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="mdi mdi-pencil"></span>
                            Editar
                        </a>
                    </li>
                @endcan
            @endif


            @if (Route::has($table . '.confirm'))
                @can($table . '.delete')
                    <h1 class="dropdown-divider"></h1>
                    <li>
                        <a class="dropdown-item text-danger"
                            href="{{ route($table . '.confirm', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="mdi mdi-delete"></span>
                            Eliminar
                        </a>
                    </li>
                @endcan
            @endif
            @if (Route::has($table . '.restablecer'))
                @can($table . '.restablecer')
                    <h1 class="dropdown-divider"></h1>
                    <li>
                        <a class="dropdown-item"
                            href="{{ route($table . '.restablecer', ['id' => \Crypt::encryptString($d->id)]) }}">
                            <span class="mdi mdi-account-key"></span>
                            Restablecer
                        </a>
                    </li>
                @endcan
            @endif
        </ul>
    </div>
@else
    Son requeridos los atributos :table y :d
@endif
