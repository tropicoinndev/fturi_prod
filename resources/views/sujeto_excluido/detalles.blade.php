@extends('layouts.cajas')
@section('css-caja')
    <style>
        .busquedaList {
            position: absolute;
            width: 60%;
            background: #fff;
        }
    </style>
@endsection
@section('panel_caja')
    <div id="appSujetoExcluido">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="text-uppercase">Sujetos excluidos</h3>
                    <p class="text-uppercase">
                        Detalle de Factura Electronica Sujeto Excluido
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    Correlativo
                </div>
                <div class="col-8">
                    {{ $p->correlativo }}
                </div>
                <div class="col-4">
                    <a class="btn btn-light" href="{{ route('clientes.show', ['id' => $p->clientes->cid]) }}"
                        target="_blank" role="button">
                        <div class="mdi mdi-eye"></div>
                    </a>

                    Clientes
                </div>
                <div class="col-8">
                    {{ $p->clientes->nombre }}
                </div>

                <div class="col-4">
                    Identificaciones
                </div>
                <div class="col-8">
                    @forelse ($p->clientes->identificaciones as $i)
                        <p>
                            <b>
                                {{ $i->identificaciones->identificacion }}
                            </b>
                            {{ $i->numero }}
                        </p>
                    @empty
                        <div class="alert alert-danger" role="alert">
                            Antes debe agregarse una identificación
                        </div>
                    @endforelse

                </div>
            </div>
            <div class="row my-1">
                <div class="col-12">
                    @if (!$p->completo && $p->detalles->sum('compra') > 0)
                        <a class="btn btn-outline-primary"
                            href="{{ route('sujeto_excluido.completado', ['id' => $p->cid]) }}" role="button"> <span
                                class="mdi mdi-check"></span> Completado</a>
                    @else
                        @if (!$p->enviado && $p->completo && $p->detalles->sum('compra') > 0)
                            @can('sujeto_excluido.autorizar')
                                <!-- Modal trigger button -->
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                    data-bs-target="#modalId">
                                    <span class="mdi mdi-send-check"></span> Autorizar y enviar FESE
                                </button>



                                <div class="modal fade" id="modalId" tabindex="-1" data-bs-backdrop="static"
                                    data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalTitleId">
                                                    Autorizar y enviar a MH
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('sujeto_excluido.autorizar') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $p->cid }}">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="tipo_operacion">Tipo de operación:</label>
                                                        <select class="form-select" id="tipo_operacion" name="tipo_operacion"
                                                            required>
                                                            <option value="">Seleccione el tipo de operación
                                                            </option>
                                                            <option value="1"
                                                                {{ $p->tipo_operacion > 0 && $p->tipo_operacion == 1 ? 'selected' : '' }}>
                                                                Gravada</option>
                                                            <option value="2"
                                                                {{ $p->tipo_operacion > 0 && $p->tipo_operacion == 2 ? 'selected' : '' }}>
                                                                No Gravada</option>
                                                            <option value="3"
                                                                {{ $p->tipo_operacion > 0 && $p->tipo_operacion == 3 ? 'selected' : '' }}>
                                                                Excluido o no Constituye Renta</option>
                                                            <option value="4"
                                                                {{ $p->tipo_operacion > 0 && $p->tipo_operacion == 4 ? 'selected' : '' }}>
                                                                Mixta</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="clasificacion">Clasificación:</label>
                                                        <select class="form-select" id="clasificacion" name="clasificacion"
                                                            required>
                                                            <option selected value="">Seleccione una clasificación
                                                            </option>
                                                            <option value="1"
                                                                {{ $p->clasificacion > 0 && $p->clasificacion == 1 ? 'selected' : '' }}>
                                                                Costo</option>
                                                            <option value="2"
                                                                {{ $p->clasificacion > 0 && $p->clasificacion == 2 ? 'selected' : '' }}>
                                                                Gasto</option>

                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="sector">Sector:</label>
                                                        <select class="form-select" id="sector" name="sector" required>
                                                            <option value="">Seleccione un sector
                                                            </option>
                                                            <option value="1"
                                                                {{ $p->sector > 0 && $p->sector == 1 ? 'selected' : '' }}>
                                                                Industria</option>
                                                            <option value="2"
                                                                {{ $p->sector > 0 && $p->sector == 2 ? 'selected' : '' }}>
                                                                Comercio</option>
                                                            <option value="3"
                                                                {{ $p->sector > 0 && $p->sector == 3 ? 'selected' : '' }}>
                                                                Agropecuaria</option>
                                                            <option value="4"
                                                                {{ $p->sector > 0 && $p->sector == 4 ? 'selected' : '' }}>
                                                                Servicios, profesionales, artes y oficios
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tipo_clasificacion">Tipo de costo / gasto:</label>
                                                        <select class="form-select" id="tipo_clasificacion"
                                                            name="tipo_clasificacion" required>
                                                            <option selected value="">Seleccione un tipo de costo / gasto
                                                            </option>
                                                            <option value="1"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 1 ? 'selected' : '' }}>
                                                                Gastos de venta sin donación</option>
                                                            <option value="2"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 2 ? 'selected' : '' }}>
                                                                Gastos de administración sin donación
                                                            </option>
                                                            <option value="3"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 3 ? 'selected' : '' }}>
                                                                Gastos financieros sin donación</option>
                                                            <option value="4"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 4 ? 'selected' : '' }}>
                                                                Costos artículos producidos/comprados
                                                                importaciones/internacionales</option>
                                                            <option value="5"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 5 ? 'selected' : '' }}>
                                                                Costos artículos producidos/comprados interno
                                                            </option>
                                                            <option value="6"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 6 ? 'selected' : '' }}>
                                                                Costos indirectos de fabricación</option>
                                                            <option value="7"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 7 ? 'selected' : '' }}>
                                                                Mano de obra</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Contraseña</label>
                                                        <input type="password" class="form-control" name="password"
                                                            aria-describedby="helpId" placeholder="Escriba su contraseña"
                                                            required />
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            name="confirm" id="confirm">
                                                        <label class="form-check-label" for="confirm">
                                                            Confirmo que he revisado detenidamente los detalles del FESE y
                                                            corresponden a lo requerido por {{ env('empresa') }} por ello
                                                            procedo a
                                                            enviarlo a MH.
                                                        </label>
                                                    </div>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cerrar
                                                    </button>
                                                    <button type="submit" class="btn btn-primary"><span
                                                            class="mdi mdi-send-check"></span> Enviar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        @else
                            @if ($p->dte != null)
                                <a href="{{ route('comprobantes.api_pdfDte', ['id' => Crypt::encryptString($p->dte->id)]) }}"
                                    target="_blank" class="btn btn-light">
                                    <span class="mdi mdi-printer"></span>
                                    Imprimir
                                </a>
                            @endif
                            @can('sujeto_excluido.autorizar')
                                <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                    data-bs-target="#modalClasificacion">
                                    <span class="mdi mdi-pencil"></span> Editar clasificación
                                </button>
                                <div class="modal fade" id="modalClasificacion" tabindex="-1" data-bs-backdrop="static"
                                    data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalTitleId">
                                                    Autorizar y enviar a MH
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('sujeto_excluido.editar_clasificacion') }}"
                                                method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $p->cid }}">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="tipo_operacion">Tipo de operación:</label>
                                                        <select class="form-select" id="tipo_operacion" name="tipo_operacion"
                                                            required>
                                                            <option value="">Seleccione el tipo de operación
                                                            </option>
                                                            <option value="1"
                                                                {{ $p->tipo_operacion > 0 && $p->tipo_operacion == 1 ? 'selected' : '' }}>
                                                                Gravada</option>
                                                            <option value="2"
                                                                {{ $p->tipo_operacion > 0 && $p->tipo_operacion == 2 ? 'selected' : '' }}>
                                                                No Gravada</option>
                                                            <option value="3"
                                                                {{ $p->tipo_operacion > 0 && $p->tipo_operacion == 3 ? 'selected' : '' }}>
                                                                Excluido o no Constituye Renta</option>
                                                            <option value="4"
                                                                {{ $p->tipo_operacion > 0 && $p->tipo_operacion == 4 ? 'selected' : '' }}>
                                                                Mixta</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="clasificacion">Clasificación:</label>
                                                        <select class="form-select" id="clasificacion" name="clasificacion"
                                                            required>
                                                            <option selected value="">Seleccione una clasificación
                                                            </option>
                                                            <option value="1"
                                                                {{ $p->clasificacion > 0 && $p->clasificacion == 1 ? 'selected' : '' }}>
                                                                Costo</option>
                                                            <option value="2"
                                                                {{ $p->clasificacion > 0 && $p->clasificacion == 2 ? 'selected' : '' }}>
                                                                Gasto</option>

                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="sector">Sector:</label>
                                                        <select class="form-select" id="sector" name="sector" required>
                                                            <option value="">Seleccione un sector
                                                            </option>
                                                            <option value="1"
                                                                {{ $p->sector > 0 && $p->sector == 1 ? 'selected' : '' }}>
                                                                Industria</option>
                                                            <option value="2"
                                                                {{ $p->sector > 0 && $p->sector == 2 ? 'selected' : '' }}>
                                                                Comercio</option>
                                                            <option value="3"
                                                                {{ $p->sector > 0 && $p->sector == 3 ? 'selected' : '' }}>
                                                                Agropecuaria</option>
                                                            <option value="4"
                                                                {{ $p->sector > 0 && $p->sector == 4 ? 'selected' : '' }}>
                                                                Servicios, profesionales, artes y oficios
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tipo_clasificacion">Tipo de costo / gasto:</label>
                                                        <select class="form-select" id="tipo_clasificacion"
                                                            name="tipo_clasificacion" required>
                                                            <option selected value="">Seleccione un tipo de costo /
                                                                gasto
                                                            </option>
                                                            <option value="1"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 1 ? 'selected' : '' }}>
                                                                Gastos de venta sin donación</option>
                                                            <option value="2"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 2 ? 'selected' : '' }}>
                                                                Gastos de administración sin donación
                                                            </option>
                                                            <option value="3"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 3 ? 'selected' : '' }}>
                                                                Gastos financieros sin donación</option>
                                                            <option value="4"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 4 ? 'selected' : '' }}>
                                                                Costos artículos producidos/comprados
                                                                importaciones/internacionales</option>
                                                            <option value="5"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 5 ? 'selected' : '' }}>
                                                                Costos artículos producidos/comprados interno
                                                            </option>
                                                            <option value="6"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 6 ? 'selected' : '' }}>
                                                                Costos indirectos de fabricación</option>
                                                            <option value="7"
                                                                {{ $p->tipo_clasificacion > 0 && $p->tipo_clasificacion == 7 ? 'selected' : '' }}>
                                                                Mano de obra</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cerrar
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <span class="mdi mdi-pencil"></span> Editar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        @endif
                    @endif
                </div>
            </div>
            @if (!$p->clientes->tipo_cliente)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            Este cliente es contribuyente, debe elegir un cliente natural que no este inscrito.
                        </div>
                    </div>
                </div>
            @else
                @if ($p->clientes->identificaciones->count() == 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                El cliente debe tener una o mas identificaciones. (DUI, NIT, Carnet de residente o
                                Pasaporte)
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row mt-4">
                        <div class="col-12 table-responsive">
                            @if (!$p->completo)
                                <form action="{{ route('sujeto_excluido.detalles_store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $p->cid }}">
                                    <input type="hidden" name="concepto" :value="concepto">
                                    <input type="hidden" name="opcion" :value="opcion">
                            @endif
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Concepto</th>
                                        <th scope="col">Tipo de item</th>
                                        <th scope="col">Unidad de medida</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Precio unitario</th>
                                        <th scope="col">Renta</th>
                                        <th scope="col">Total</th>
                                        @if (!$p->completo)
                                            <th scope="col">Opciones</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$p->completo)
                                        <tr>
                                            <td v-if="!addConcepto">
                                                <div class="btn-group" role="group">
                                                    <input type="radio" class="btn-check" autocomplete="off"
                                                        id="existente" name="opcions" v-model="opcion" value="1" />
                                                    <label class="btn btn-outline-secondary" for="existente"
                                                        title="Buscar un concepto existente">
                                                        <span class="mdi mdi-magnify"></span>
                                                    </label>

                                                    <input type="radio" class="btn-check" id="nuevo"
                                                        autocomplete="off" name="opcions" v-model="opcion"
                                                        value="2" />
                                                    <label class="btn btn-outline-secondary" for="nuevo"
                                                        title="Agregar un nuevo concepto y guardarlo para usar otra vez">
                                                        <span class="mdi mdi-plus"></span>
                                                    </label>

                                                    <input type="radio" class="btn-check" id="descriptivo"
                                                        autocomplete="off" name="opcions" v-model="opcion"
                                                        value="3" />
                                                    <label class="btn btn-outline-secondary" for="descriptivo"
                                                        title="Agregar concepto para uso único">
                                                        <span class="mdi mdi-text"></span>
                                                    </label>
                                                </div>

                                            </td>
                                            <td :colspan="!addConcepto ? 7 : 2">
                                                <textarea class="form-control" rows="3" required placeholder="Describa el concepto del pago en 250 caracteres"
                                                    maxlength="250" minlength="5" v-model="concepto" v-if="opcion != 1 && !addConcepto"></textarea>
                                                <input class="form-control"
                                                    placeholder="Escriba para buscar un concepto existente"
                                                    v-if="opcion == 1 && !addConcepto" v-model="busqueda"
                                                    @keyup="getConcepto()">
                                                <div class="busquedaList shadow"
                                                    v-if="opcion == 1 && !addConcepto && list.length > 0">
                                                    <ul class="list-group list-group-flush">
                                                        <a class="list-group-item list-group-item-action" href="#"
                                                            v-for="l in list" @index="l.cid" @click="setConcepto(l)">
                                                            @{{ l.conceptos }} Monto: $@{{ l.monto }} Renta:
                                                            @{{ l.renta }}
                                                        </a>


                                                    </ul>
                                                </div>
                                                <div class="text-truncate" style="max-width: 4cm;" :title="concepto">
                                                    <small v-if="addConcepto">
                                                        <span class="mdi mdi-close btn btn-light text-danger btn-sm"
                                                            @click="addConcepto = false"></span>
                                                        @{{ concepto }}
                                                    </small>
                                                </div>

                                            </td>
                                            <td v-if="!addConcepto">
                                                <button type="button" class="btn btn-light" @click="addConcepto = true"
                                                    :disabled="concepto.length < 4">
                                                    <span class="mdi mdi-check"></span>
                                                </button>

                                            </td>
                                            <td v-if="addConcepto">
                                                <select class="form-select" name="tipo_item" required v-model="item">
                                                    <option selected value="">Seleccione un tipo de item</option>
                                                    @foreach ($items as $i => $v)
                                                        <option value="{{ $i }}">{{ $v }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td v-if="addConcepto">
                                                <select class="form-select" name="unidad_medida" required
                                                    v-model="unidad">
                                                    <option selected value="">Seleccione un tipo de item</option>
                                                    @foreach ($unidades as $u => $c)
                                                        <option value="{{ $u }}">{{ $c }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td v-if="addConcepto">
                                                <input type="number" class="form-control" name="cantidad"
                                                    step="1" min="1" placeholder="Cantidad" required
                                                    v-model="cantidad" @keyup="getTotal()" @change="getRenta()" />
                                            </td>

                                            <td v-if="addConcepto">
                                                <input type="number" class="form-control" name="precio_unitario"
                                                    step="0.01" min="0.01" placeholder="Precio unitario" required
                                                    v-model="unitario" @keyup="getTotal()" @change="getRenta()" />
                                            </td>
                                            <td v-if="addConcepto">
                                                <input type="number" class="form-control" name="renta" step="0.01"
                                                    min="0.01" placeholder="Renta total" v-model="renta"
                                                    :disabled="!(item == 2 || item == 3)"
                                                    :required="item == 2 || item == 3" />
                                            </td>
                                            <td v-if="addConcepto">
                                                <input type="number" class="form-control" name="total" step="0.01"
                                                    min="0.01" placeholder="Total" v-model="total" />
                                            </td>
                                            <td v-if="addConcepto">
                                                <button type="submit" class="btn btn-primary pb-0"
                                                    :disabled="!isValid">
                                                    <div class="mdi mdi-plus h5"></div>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                    @php
                                        $total = 0;
                                        $renta = 0;
                                    @endphp
                                    @forelse ($p->detalles as $v)
                                        <tr>
                                            <td colspan="2">
                                                {{ $v->descripcion }}
                                            </td>
                                            <td>
                                                {{ $items[$v->tipo_item] }}
                                            </td>
                                            <td>
                                                {{ $unidades[$v->unidad_medida] }}
                                            </td>
                                            <td>
                                                {{ $v->cantidad }}
                                            </td>
                                            <td>
                                                ${{ number_format($v->precio_unitario, 2) }}
                                            </td>
                                            <td>
                                                ${{ number_format($v->renta, 2) }}
                                            </td>
                                            <td>
                                                ${{ number_format($v->compra, 2) }}
                                            </td>
                                            @if (!$p->completo)
                                                <td>

                                                    <a name="" id="" class="btn btn-light text-danger"
                                                        href="{{ route('sujeto_excluido.detalles_delete', ['id' => $v->cid]) }}"
                                                        role="button">
                                                        <span class="mdi mdi-delete"></span></a>
                                                </td>
                                            @endif
                                        </tr>
                                        @php
                                            $total += $v->compra;
                                            $renta += $v->renta;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="9">Aun sin agregar ningún detalle</td>
                                        </tr>
                                    @endforelse
                                    @if (count($p->detalles) > 0)
                                        <tr>
                                            <td colspan="6">
                                                TOTAL
                                            </td>
                                            <td>
                                                ${{ number_format($renta, 2) }}
                                            </td>
                                            <td>
                                                ${{ number_format($total, 2) }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if (!$p->completo)
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            @endif



        </div>
    </div>
@endsection
@section('script-caja')
    <script type="module">
        var app = window.appVue({
            emits: ['cliente'],
            data() {
                return {
                    item: "",
                    unidad: "",
                    cantidad: null,
                    concepto: "",
                    unitario: null,
                    total: null,
                    renta: null,
                    opcion: 1,
                    addConcepto: false,
                    p_renta: {{ env('renta', 0.1) }},
                    list: [],
                    busqueda: "",

                }
            },
            computed: {
                isValid() {

                    return this.item &&
                        parseInt(this.item) >= 1 &&
                        parseInt(this.item) <= 3 &&
                        this.unidad &&
                        this.unidad > 0 &&
                        this.cantidad &&
                        this.cantidad > 0 &&
                        this.unitario &&
                        this.unitario > 0 &&
                        this.total &&
                        this.total > 0 &&
                        (
                            this.item == 1 ||
                            (
                                this.renta &&
                                this.renta > 0
                            )
                        ) &&
                        this.concepto &&
                        this.concepto.trim().length > 4;
                }
            },
            methods: {
                setcliente: function(c) {
                    this.cliente = c.id;

                    console.log(this.cliente, this.confirm)
                },
                getTotal: function() {
                    this.getRenta()
                    var i = 0;
                    if (this.cantidad &&
                        parseInt(this.cantidad) > 0 &&
                        this.unitario &&
                        parseFloat(this.unitario) > 0)
                        i = parseInt(this.cantidad) * parseFloat(this.unitario);
                    if (this.renta && this.renta > 0)
                        i = i - this.renta;
                    this.total = parseFloat(i.toFixed(2));

                },
                getRenta: function() {
                    var i = 0;
                    if (this.item == 2 || this.item == 3)
                        if (this.cantidad &&
                            parseInt(this.cantidad) > 0 &&
                            this.unitario &&
                            parseFloat(this.unitario) > 0) {
                            var monto = parseInt(this.cantidad) * parseFloat(this.unitario);
                            i = parseFloat((monto * (this.p_renta)).toFixed(2));
                        }
                    this.renta = i;

                },
                getConcepto: function() {
                    if (this.busqueda && this.busqueda.length > 4) {
                        axios.post("{{ route('sujeto_excluido.detalles_search_concepto') }}", {
                            busqueda: this.busqueda.trim()
                        }).then(rs => {
                            if (rs.data.list)
                                this.list = rs.data.list;

                        })

                    } else this.list = [];
                },
                setConcepto: function(i) {
                    this.item = i.tipo_item;
                    this.unidad = i.unidad;
                    this.concepto = i.conceptos;
                    this.unitario = i.monto;
                    this.addConcepto = true;
                    this.list = [];
                    this.busqueda = "";
                }
            },
        });
        app.component('clientes', component.clientes);
        app.mount("#appSujetoExcluido");
    </script>
@endsection
