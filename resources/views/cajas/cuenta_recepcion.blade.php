<div class="row">
    <div class="col-12 mb-2 h4">
        ORDEN Nº
        <strong>
            {{ $p->id }}
        </strong>
    </div>
    <div class="col-12 mb-2">
        <a class="btn btn-light" href="{{ route('cajas.buscar_cuentas') }}" role="button">
            <span class="mdi mdi-arrow-left-bottom-bold h5"></span>
            Volver
        </a>
    </div>
    <div class="col-4 mb-2">
        Estado:
    </div>
    <div class="col-8 mb-2">
        {{ $p->estado ? 'Activa' : 'Desactivada' }}
    </div>
    <div class="col-4 mb-2">
        Facturada:
    </div>
    <div class="col-8 mb-2">
        {{ $p->facturada ? 'Cuenta facturada' : ($p->eliminado ? 'Cuenta anulada' : 'Sin facturar') }}
    </div>
    <div class="col-4 mb-2">
        Cliente:
    </div>
    <div class="col-8 mb-2">
        {{ $p->clientes?->nombre ?? ($p->titular ?? 'Sin cliente o titular') }}
    </div>
    <div class="col-4 mb-2">
        Sucursal:
    </div>
    <div class="col-8 mb-2">
        {{ $p->habitaciones->sucursales->sucursal }}
    </div>

    <div class="col-4 mb-2">
        Fecha de creación:
    </div>
    <div class="col-8 mb-2">
        {{ $p->created_at }}
    </div>
    <div class="col-4 mb-4">
        Turno:
    </div>
    <div class="col-8 mb-4">
        {{ $p->turnos?->id ?? 'Sin turno agregado' }} · {{ $p->turnos?->fecha ?? '---' }}
    </div>

    @if ($r && $r->id != null)
        <div class="col-12 mb-3 text-uppercase fw-bold">
            Registro de facturación
        </div>
        <div class="col-12 mb-2">

            @if ($r->comprobantes?->dteOne?->cid && $r->comprobantes?->dteOne?->cid != null)
                <a class="btn btn-light" href="{{ route('dte.documento', ['id' => $r->comprobantes?->dteOne?->cid]) }}"
                    role="button" target="_blank">
                    <span class="mdi mdi-file-document-check h5"></span>
                    DTE
                </a>
            @else
                No se encontró DTE
            @endif
            @can('cajas.estado_cuentas')
                @if (!$p->facturada || $p->estado)
                    <a class="btn btn-light"
                        href="{{ route('cajas.estado_cuentas', ['tipo' => Crypt::encryptString($tipo), 'id' => $p->cid]) }}"
                        role="button"
                        title="Esta acción desactivara la cuenta, por que se encontró con estado activo o sin facturar">
                        <span class="mdi mdi-file-document-check h5"></span>
                        Actualizar estado de cuenta
                    </a>
                @endif
            @endcan

        </div>
        <div class="col-4 mb-2">
            Comprobante:
        </div>
        <div class="col-8 mb-2">
            {{ $r?->comprobantes?->correlativo ?? 'Sin comprobante' }}
        </div>
        <div class="col-4 mb-2">
            Fecha del comprobante:
        </div>
        <div class="col-8 mb-2">
            {{ $r?->comprobantes?->fecha ?? 'Sin comprobante' }}
        </div>
        <div class="col-4 mb-2">
            Titular:
        </div>
        <div class="col-8 mb-2">
            {{ $r?->comprobantes?->titular ?? 'Sin comprobante' }}
        </div>
    @else
        <div class="col-12 mb-2">
            Sin registro de facturación
        </div>
    @endif

</div>
<div class="row mt-4">
    <div class="col-12 fw-bold mb-3 text-uppercase">
        Detalles de la cuenta
    </div>
    <div class="col-12">
        <table class="table table-light">
            <thead class="thead-dark">
                <tr>
                    <th>Cantidad</th>
                    <th>Detalle</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $p->dias }}</td>
                    <td class="text-uppercase">
                        Habitación {{ $p->habitaciones->numero_habitacion }} ·
                        {{ $p->tarifas->tarifa }}</td>
                    <td>${{ number_format($p->tarifas->precio, 2) }} /{{ $p->tarifas->numero_dias }}
                        {{ $p->tarifas->numero_dias > 1 ? 'dias' : 'dia' }} </td>
                    <td>${{ number_format(($p->tarifas->precio / $p->tarifas->numero_dias) * $p->dias, 2) }}</td>
                    <td>{{ $p?->usuarios?->name }}</td>
                </tr>
            </tbody>

        </table>
    </div>
</div>
