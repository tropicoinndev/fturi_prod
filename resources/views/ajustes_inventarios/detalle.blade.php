@extends('layouts.ajustes_inventarios')

@section('style-ajustes-inventarios')
    <style>
        /*Alerta asincrona*/
        .alert-dismissible {
            border-radius: 12px;
        }
        [v-cloak]{
            display: none;
        }
    </style>
@endsection

@section('ajustes_inventarios_content')
    <div id="appDetalles" v-cloak>
        <div class="row">
            <div class="col-12">
                <h3 class="card-title text-uppercase mb-3">
                    <span class="mdi mdi-file-eye text-success h2"></span>
                    Solicitud de Ajuste #{{ $ajusteInv->id }}
                </h3>
                <x-message></x-message>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-6">
                <a href="{{ route('ajustes_inventarios.solicitados') }}" class="btn btn-outline-secondary rounded-5 mb-3"><span class="mdi mdi-arrow-left"></span> Volver</a>
            </div>
            {{-- <div v-if="ajusteExi.length > 0" class="col-6 text-end">
                <button @click="opcion = 2" type="button" class="btn btn-outline-danger rounded-5 me-3" data-bs-toggle="modal" data-bs-target="#modalAutorizarNegar"><span class="mdi mdi-cancel"></span> Negar ajuste</button>
                <button @click="opcion = 1" type="button" class="btn btn-primary rounded-5" data-bs-toggle="modal" data-bs-target="#modalAutorizarNegar"><span class="mdi mdi-lock-open-check"></span> Autorizar ajuste</button>
            </div> --}}
        </div>

        <div class="row text-uppercase mb-4">
            <div class="col-2"><b>Usuario solicitante: </b></div>
            <div class="col-10">
                {{ $ajusteInv->userSolicitante->name }}
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle ms-3" data-bs-toggle="modal" data-bs-target="#modalEditSolicitante" title="Editar Solicitante">
                    <span class="mdi mdi-pencil"></span>
                </button>
            </div>

            <div class="col-2"><b>Realizado por: </b></div>
            <div class="col-10">{{ $ajusteInv->userRealiza->name }}</div>

            <div class="col-2"><b>Fecha de proceso: </b></div>
            <div class="col-10 text-muted">{{ $ajusteInv->fecha_proceso }}</div>

            <div class="col-2"><b>Observación: </b></div>
            <div class="col-10">{{ $ajusteInv->observacion ?? '---' }}</div>

            <div class="col-2"><b>Estado: </b></div>
            <div class="col-10">
                @if($ajusteInv->estado)
                    <span class="text-success">Activo</span>
                @else
                    <span class="text-danger">Inactivo</span>
                @endif
            </div>

            <div class="col-2"><b>Solicitado: </b></div>
            <div class="col-10">
                @if($ajusteInv->solicitado)
                    <span class="text-success">Si</span>
                @else
                    <span class="text-danger">No</span>
                @endif
            </div>
        </div>

        <div class="row mb-4">
            <!--Columna 1-->
            <div class="col-6">
                <!--Alerta asincrona-->
                <div v-if="alerta.m && alerta.t" :class="'alert alert-dismissible fade show alert-'+alerta.t" :style="'border-left: solid 5px #'+borderColorAlert" role="alert">
                    <strong><small>@{{ alerta.m }}</small></strong>
                    <!--Vaciar el objeto de la alerta al hacer click, para evitar error de que la clase no existe-->
                    <button @click="alerta = {}" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                @if(!$ajusteInv->solicitado)
                    <div style="border-radius: 15px;" class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="row">
                                    <label for="loteId" class="form-label">Número de lote:</label>

                                    <div class="col-9">
                                        <input v-model="numLote" type="number" class="form-control rounded-5" id="loteId" aria-describedby="loteIdHelp" min="1" max="99999" placeholder="Ingrese el número de lote a buscar">
                                        <div v-show="lblMessage" id="loteIdHelp" class="form-text text-danger">@{{ lblMessage }}</div>
                                    </div>
                                    <div class="col-3 text-end">
                                        <button @click="findLote" type="button" class="btn btn-primary rounded-5"><span class="mdi mdi-magnify"></span> Buscar</button>
                                    </div>
                                </div>
                            </div>

                            <div v-show="Object.keys(loteEncontrado).length > 0" class="mb-5">
                                <div style="height: 100px; border-radius: 15px;" class="card">
                                    <div class="card-body">
                                        <div class="row mb-1">
                                            <div class="col-9">
                                                <b>#@{{ loteEncontrado.id }} · @{{ loteEncontrado.bodega }}</b>
                                            </div>
                                            <div class="col-3 text-end">
                                                <small class="text-muted">@{{ loteEncontrado.creacion }}</small>
                                            </div>
                                        </div>

                                        <p style="margin-bottom: -2px;" class="text-uppercase">@{{ loteEncontrado.producto }} · <small class="text-secondary"><b>$ @{{ parseFloat(loteEncontrado.precioCosto).toFixed(2) }}</b></small></p>
                                        <p class="text-uppercase">
                                            Cant. Historial: <span class="text-success me-3"><b>@{{ loteEncontrado.cantHistorial }}</b></span>
                                            Existencia: <span class="text-primary"><b>@{{ loteEncontrado.existencia }}</b></span>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div v-show="Object.keys(loteEncontrado).length > 0" class="mb-3">
                                <p style="margin-bottom: 8px;">Acción a realizar:</p>
                                <input @click="accion = '{{ Crypt::encryptString(2) }}'" :disabled="statusValidateCantidad" type="radio" class="btn-check" name="options-base" id="descartar" autocomplete="off">
                                <label class="btn btn-outline-danger rounded-5 me-3" for="descartar"><span class="mdi mdi-trash-can-outline"></span> Descartar</label>

                                <input @click="accion = '{{ Crypt::encryptString(1) }}'" :disabled="statusValidateCantidad" type="radio" class="btn-check" name="options-base" id="aumentar" autocomplete="off">
                                <label class="btn btn-outline-success rounded-5" for="aumentar"><span class="mdi mdi-plus"></span> Aumentar</label>
                            </div>

                            <div v-show="Object.keys(loteEncontrado).length > 0" class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad de existencia:</label>
                                <input v-model="cantExistencias" @keyup="validateCantidad" @change="validateCantidad"  type="number" :class="'form-control rounded-5 '+(statusValidateCantidad ? 'is-invalid' : '')" id="cantidad" aria-describedby="cantidadHelp" min="1" max="99999" placeholder="Escriba aqui...">
                                <div id="cantidadHelp" :class="'form-text '+(statusValidateCantidad ? 'text-danger' : '')">@{{ lblValidateCantidad }}</div>
                            </div>
                        </div>
                        <div v-if="Object.keys(loteEncontrado).length > 0" style="border-top: none;" class="card-footer bg-transparent text-end">
                            <button @click="saveSolicitud" :disabled="Object.keys(loteEncontrado).length <= 0 || accion === null || cantExistencias === ''" type="button" class="btn btn-primary rounded-5"><span class="mdi mdi-content-save"></span> Guardar solicitud</button>
                        </div>
                    </div>
                @else
                    <div style="border-radius: 15px;" class="alert alert-success text-uppercase" role="alert">
                        <b>Este ajuste ya fué solicitado.</b>
                    </div>
                @endif
            </div>

            <!--Columna 2-->
            <div class="col-6">
                <div style="border-radius: 15px;" class="card">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <div class="col-6">
                                <h5 class="card-title text-uppercase">Lotes a modificar (@{{ ajusteExi.length }})</h5>
                            </div>
                            <div v-if="ajusteExi.length > 0" class="col-6 text-end">
                                <form action="{{ route('ajustes_inventarios.completarSolicitud') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="ajusteInvId" value="{{ $ajusteInv->cid }}">
                                    <button @if($ajusteInv->solicitado) disabled @endif type="submit" class="btn btn-{{ $ajusteInv->solicitado ? 'secondary' : 'success' }} rounded-5">
                                        <span class="mdi mdi-check-bold"></span> {{ $ajusteInv->solicitado ? 'Solicitud completada' : 'Completar solicitud' }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div v-if="ajusteExi.length > 0" class="row">
                            <div v-for="ae in ajusteExi" class="col-12 mb-2">
                                <div style="border-radius: 15px;" class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <p class="mt-0 mb-0">
                                                    <b>Lote:</b> #@{{ ae.existencias_id }} ·
                                                    <span :class="ae.accion === 1 ? 'text-success' : 'text-danger'"><b>@{{ ae.accion === 1 ? 'Aumentar' : 'Descartar' }}</b></span>
                                                    @{{ parseFloat(ae.cantidad).toFixed(2) }} unidades
                                                    <br>
                                                    <b>Bodega:</b> @{{ ae.existencias.bodegas.bodega }}
                                                    <br>
                                                    <b>Producto:</b> @{{ ae.existencias.productos_existencias.nombre }}
                                                </p>
                                            </div>
                                            <div class="col-4 text-end">
                                                @if(!$ajusteInv->solicitado)
                                                    <button @click="setLoteSelected(ae)" type="button" class="btn btn-outline-warning rounded-5 me-2" title="Editar cantidad" data-bs-toggle="modal" data-bs-target="#modalEditarCantidad">
                                                        <span class="mdi mdi-pencil"></span>
                                                    </button>

                                                    <a type="button" :href="'/ajustes_inventarios/confirm/'+ae.id" class="btn btn-outline-danger rounded-5" title="Eliminar modificacion">
                                                        <span class="mdi mdi-delete"></span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-muted">Aún no se han a agrego lotes a modificar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Modal editar cantidad-->
        <div class="modal fade" id="modalEditarCantidad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('ajustes_inventarios.update') }}" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h1 class="modal-title fs-5 text-uppercase" id="exampleModalLabel">Lote #@{{ editLoteSelected.loteId }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="ajusteExiId" :value="editLoteSelected.ajusteExiId">

                            <div class="mb-3">
                                <label class="form-label text-uppercase"><b>Total existencias: </b></label> <span class="badge text-bg-primary">@{{ editLoteSelected.existenciaActual }}</span>
                            </div>
                            <div class="mb-3">
                                <p style="margin-bottom: 8px;">Acción:</p>
                                <input type="radio" class="btn-check" name="accion" id="editDescartar" value="{{ Crypt::encryptString(2) }}" :checked="editLoteSelected.accion === 2" autocomplete="off">
                                <label class="btn btn-outline-danger rounded-5 me-3" for="editDescartar"><span class="mdi mdi-trash-can-outline"></span> Descartar</label>

                                <input type="radio" class="btn-check" name="accion" id="editAumentar" value="{{ Crypt::encryptString(1) }}" :checked="editLoteSelected.accion === 1" autocomplete="off">
                                <label class="btn btn-outline-success rounded-5" for="editAumentar"><span class="mdi mdi-plus"></span> Aumentar</label>
                            </div>
                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad a editar:</label>
                                <input v-model="cantExistencias" @keyup="validateEditCantidad" @change="validateEditCantidad" type="number" name="cantidad" :value="cantExistencias" :class="'form-control rounded-5 '+(statusValidateCantidad ? 'is-invalid' : '')" id="cantidad" aria-describedby="cantidadHelp" min="1" max="99999" placeholder="Escriba aqui...">
                                <div id="cantidadHelp" :class="'form-text '+(statusValidateCantidad ? 'text-danger' : '')">@{{ lblValidateCantidad }}</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary rounded-5" data-bs-dismiss="modal">
                                <span class="mdi mdi-window-close"></span> Cancelar</button>
                            <button :disabled="cantExistencias === '' || statusValidateCantidad" type="submit" class="btn btn-primary rounded-5">
                                <span class="mdi mdi-pencil"> Editar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Modal Autorizar|Negar-->
        <div class="modal fade" id="modalAutorizarNegar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('ajustes_inventarios.authAction') }}" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h1 class="modal-title fs-5 text-uppercase" id="exampleModalLabel">
                                <span>@{{ opcion === 1 ? 'Autorizar ajuste' : 'Negar ajuste' }}</span>
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" class="form-control" :value="opcion" id="opcion" name="opcion">
                            <input type="hidden" class="form-control" value="{{ $ajusteInv->cid }}" id="ajusteInventarioId" name="ajusteInventarioId">

                            <div class="mb-3">
                                <label for="password" class="form-label">Ingrese su contraseña:</label>
                                <input type="password" id="password" name="password" class="form-control rounded-5" placeholder="Escriba aquí su contraseña" autocomplete="off">
                            </div>

                            <div class="mb-3">
                                <label for="observacion" class="form-label">Observaciones:</label>
                                <textarea class="form-control rounded-4" id="observacion" name="observacion" rows="3" placeholder="Escriba aqui las observaciones sobre la autorización de este ajuste de inventario."></textarea>
                            </div>

                            <div class="form-check">
                                <input  class="form-check-input" type="checkbox" id="flexCheckDefault" name="confirm" value="1">
                                <label class="form-check-label" for="flexCheckDefault">Confirmo la @{{ opcion === 1 ? 'autorización' : 'negación' }} de este ajuste de inventario</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button v-if="opcion === 2" type="submit" class="btn btn-danger rounded-5 me-3"><span class="mdi mdi-cancel"></span> Negar ajuste</button>
                            <button v-else type="submit" class="btn btn-primary rounded-5"><span class="mdi mdi-lock-open-check"></span> Autorizar ajuste</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Modal editar solicitante-->
        <div class="modal fade" id="modalEditSolicitante" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('ajustes_inventarios.editSolicitante') }}" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"><span class="mdi mdi-pencil"></span> Editar Solicitante</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{--Id del ajuste de inventario actual--}}
                            <input type="hidden" name="ajusteInventarioId" value="{{ $ajusteInv->cid }}">
                            {{--Id del usuario solicitante actual--}}
                            <input type="hidden" name="solicitanteUsersId" value="{{ $ajusteInv->userSolicitante->cid }}">

                            <div class="mb-3">
                                <label class="form-label"><b>· {{ $ajusteInv->userSolicitante->name }} ·</b></label>
                            </div>
                            <div class="mb-3">
                                <label for="newSolicitanteUsersId" class="form-label">Nuevo usuario solicitante:</label>
                                <select class="form-select rounded-5" id="newSolicitanteUsersId" name="newSolicitanteUsersId" aria-label="Default select example" required>
                                    <option value="" selected disabled>--Seleccione---</option>
                                    @foreach($usuarios as $u)
                                        <option class="text-uppercase" value="{{ $u->cid }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary rounded-5" data-bs-dismiss="modal"><span class="mdi mdi-window-close"></span> Cerrar</button>
                            <button type="submit" class="btn btn-primary rounded-5"><span class="mdi mdi-content-save"></span> Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div><!--End div App-->
@endsection

@section('script-ajustes-inventarios')
    <script type="module">
        let app = window.appVue({
            data(){
                return {
                    usuarios: @json($usuarios),
                    ajusteExi: @json($ajusteExi),

                    //Encontrar el lote solicitado
                    numLote: '',
                    lblMessage: null,
                    loteEncontrado: {},//Object

                    //Aumentar o disminuir cantidad
                    cantExistencias: '',
                    lblValidateCantidad: null,
                    statusValidateCantidad: null,
                    accion: null,//Accion de botones: 1 = Aumentar, 2 = Descartar

                    opcion: null,//Opcion de modal: 1 = Autorizar, 2 = Negar

                    //Modal editar cantidad lote
                    editLoteSelected: {},//Object

                    //Alerta flotante asincrona
                    alerta: {},//Object
                    timerAlert: null,
                    borderColorAlert: null,
                }
            },
            mounted(){
                //console.log('Detalles mounted.');
                this.validateCantidad();
            },
            methods: {
                findLote(){
                    if(this.numLote != ''){
                        axios.post("{{ route('ajustes_inventarios.findLote') }}",{
                            ajusteInv: '{{ $ajusteInv->cid }}',
                            numLote: parseInt(this.numLote),
                        }).then((r) => {
                            if(r.data.status){
                                this.setAlert(r.data.status, r.data.message);

                                this.loteEncontrado.id = r.data.p.id;
                                this.loteEncontrado.bodega = r.data.p.bodegas.bodega;
                                this.loteEncontrado.producto = r.data.p.productos_existencias.nombre;
                                this.loteEncontrado.precioCosto = r.data.p.precio_costo;
                                this.loteEncontrado.cantHistorial = r.data.p.cantidad_historial;
                                this.loteEncontrado.existencia = r.data.p.existencia;
                                this.loteEncontrado.creacion = r.data.p.creacion;

                                this.lblMessage = null;
                                //console.log('Lote encontrado: ',this.loteEncontrado);
                            }
                            else{
                                this.lblMessage = r.data.message;
                                this.loteEncontrado = [];//Vaciar el objeto si no se encontro un lote por segunda vez
                            }
                        }).catch((e) => {
                            this.setAlert(false, e.response.data.message);
                        });
                    }
                    this.loteEncontrado = [];//Vaciar el objeto si el input del lote esta vacia
                },
                validateCantidad(){
                    if(this.cantExistencias > this.loteEncontrado.existencia){
                        this.statusValidateCantidad = true;
                        this.lblValidateCantidad = 'La cantidad ingresada excede a la existencia actual.';
                    }
                    else{
                        this.statusValidateCantidad = false;
                        this.lblValidateCantidad = 'Ingrese la cantidad de existencia a descartar o aumentar para este lote.';
                    }
                },
                validateEditCantidad(){
                    if(this.cantExistencias > this.editLoteSelected.existenciaActual){
                        this.statusValidateCantidad = true;
                        this.lblValidateCantidad = 'La cantidad ingresada excede a la existencia actual.';
                    }
                    else{
                        this.statusValidateCantidad = false;
                        this.lblValidateCantidad = 'Ingrese la cantidad a modificar.';
                    }
                },
                saveSolicitud(){
                    if(this.numLote != '' && this.cantExistencias != ''){
                        axios.post("{{ route('ajustes_inventarios.saveSolicitud') }}",{
                            loteId: parseInt(this.loteEncontrado.id),//existencias_id
                            cantExistencias: parseInt(this.cantExistencias),
                            accion: this.accion,
                            ajusteInventarioId: '{{ $ajusteInv->cid }}',
                        }).then((r) => {
                            if(r.data.status){
                                this.setAlert(r.data.status, r.data.message);

                                this.numLote = '';
                                this.loteEncontrado = [];
                                this.cantExistencias = '';
                                this.accion = null;

                                this.ajusteExi = r.data.ajusteExi;
                            }
                            else{
                                this.setAlert(false, r.data.message);
                            }
                        }).catch((e) => {
                            this.setAlert(false, e.response.data.message);
                        });
                    }
                },
                setAlert(s, m){//Recibe como parámetro el estado y el mensaje que viene del Backend
                    //Cancela el timer anterior si existe
                    if(this.timerAlert)
                        clearTimeout(this.timerAlert);

                    if(s){
                        this.alerta.t = 'success';
                        this.borderColorAlert = '2eb96a97';
                    }
                    else{
                        this.alerta.t = 'danger';
                        this.borderColorAlert = 'fbbbbb';
                    }

                    this.alerta.m = m;

                    //Se establece un nuevo timer
                    this.timerAlert = setTimeout(() => {
                        this.alerta = {};//Vaciar el objeto cuando el timer se haya agotado
                    }, 10 * 1000);
                },
                setLoteSelected(ae){
                    console.log('ae: ',ae);
                    this.editLoteSelected.ajusteExiId = ae.id;
                    this.editLoteSelected.loteId = ae.existencias.id;
                    this.cantExistencias = parseInt(ae.cantidad);
                    this.editLoteSelected.accion = parseInt(ae.accion);
                    this.editLoteSelected.existenciaActual = parseInt(ae.existencias.existencia);
                },
            },
            computed: {

            },
            watch: {

            },
        });
        app.mount('#appDetalles');
    </script>
@endsection
