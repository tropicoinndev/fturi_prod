@extends('layouts.dtes')

@section('dte_content')
    <style>
        .ch-60 {
            height: 90vh;
            overflow-x: auto;
            overflow-y: auto;
        }

        .usuario:hover {
            background: #4DB6AC;
            color: #fff;
        }

        .btn-del {
            display: none;
        }

        .usuario:hover .btn-del {
            display: block;
        }

        .message {
            position: fixed;
            top: 20%;
            right: 1%;
            width: 15%;
            z-index: 100;
        }
    </style>

    <div id="cajasShow">

        <div class="row">
            <div class="col-12 h1 {{ $p->estado ? 'text-success' : 'text-danger' }}">
                {{ $p->caja }}
                {{ $p->id }}
            </div>
            <div class="col-12">
                <small>
                    Detalles de caja
                </small>
            </div>
            <x-message></x-message>
            <div class="col-12 my-4">
                <button class="btn btn-light" type="button" data-bs-toggle="modal" data-bs-target="#precios">
                    <span class="mdi mdi-currency-usd h4"></span>
                    Agregar precios
                </button>
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#usuarios">
                    <span class="mdi mdi-account-plus h4"></span>
                    Agregar usuarios
                </button>
                <a href="{{ route('cajas.bloquear', ['id' => $p->cid]) }}" class="btn btn-light"
                    title="No permitirá el acceso a ningún usuario, y cerrara la sesión">
                    <span class="mdi mdi-account-cancel h4"></span>
                    @if ($p->estado)
                        Bloquear acceso
                    @else
                        Desbloquear acceso
                    @endif
                </a>
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#alertas">
                    <span class="mdi mdi-message-badge h4"></span>
                    Alerta
                </button>
            </div>
        </div>

        <!-- Turnos -->
        <div class="row mb-4">
            <div class="col-12">
                <h5>Turnos</h5>
            </div>

            <div class="col-3">
                <button type="button" class="card w-100 p-3 border border-1 border-success" data-bs-toggle="modal"
                    data-bs-target="#turnos">
                    <div class="card-body">
                        <p class="card-text">
                            <span class="mdi mdi-plus"></span> Agregar turnos
                        </p>
                    </div>
                </button>
            </div>

            <!-- mostrar todas los usuarios agregados a esta caja -->
            @foreach ($cajaTurnos as $t)
                <div class="col-3 mb-4">
                    <div class="card w-100 h-100">
                        <div class="card-body">
                            <a href="{{ route('caja_turnos.confirm', ['id' => \Crypt::encryptString($t->id)]) }}"
                                class="float-end text-danger h4" title="Eliminar Caja">
                                <span class="mdi mdi-close"></span>
                            </a>
                            <b class="card-title">{{ $t->opcion_turnos->turno }}</b>
                            <div class="card-text">
                                {{ $t->opcion_turnos?->apertura }} a {{ $t->opcion_turnos?->cierre }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <h5>
                    Bodegas de ventas
                </h5>
            </div>
            <div class="col-3 mb-3 ">
                <button type="button" class="card p-2 w-100 border border-1 border-success" data-bs-toggle="modal"
                    data-bs-target="#bodegas">
                    <div class="card-body body_cajas">
                        <p class="card-text">
                            <span class="mdi mdi-plus"></span> Agregar bodegas
                        </p>
                    </div>
                </button>


            </div>
            <div class="col-3 mb-3 " v-for="ba in bodegasAdd" :key="ba.id">
                <div class="card p-2 w-100 border border-1 border-success">
                    <div class="card-body body_cajas">
                        <a class="nav-link float-end text-muted" href="#" @click="setBodega(ba.bodegas_id)">
                            <span class="mdi mdi-delete"></span>
                        </a>
                        <p class="card-text">
                            <span class="mdi mdi-fireplace-off"></span> @{{ ba.bodegas.bodega }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <h5>
                    Cajas comprobantes
                </h5>
            </div>
            <div class="col-3  mb-3">
                <button type="button" class="card p-2 w-100 h-100 border border-1 border-success" data-bs-toggle="modal"
                    data-bs-target="#comprobanteCaja">
                    <div class="card-body body_cajas">
                        <p class="card-text">
                            <span class="mdi mdi-plus"></span> Agregar cajas comprobantes
                        </p>
                    </div>
                </button>


            </div>
            <div class="col-3 mb-3" v-for="cc in cajasAdd" :key="cc.id">
                <div class="card p-2 w-100 border border-1 border-success">
                    <div class="card-body body_cajas">
                        <a class="nav-link float-end text-muted " @click="setCaja(cc.origen_cajas_id)"
                            title="Eliminar caja">
                            <span class="mdi mdi-delete"></span>
                        </a>
                        <a class="nav-link float-end text-muted me-4" :href="'/cajas/comprobantes/status/' + cc.cid"
                            title="Cambiar estado de la caja">
                            <span class="mdi mdi-swap-horizontal"></span>
                        </a>
                        <p class="card-text text-uppercase">
                            <span class="mdi mdi-store-cog-outline"></span> @{{ cc.cajas_origen && cc.cajas_origen.caja }}
                            <span class="text-end">Estado: @{{ cc.cajas_origen && cc.cajas_origen.estado == 1 ? 'Activo' : 'Inactivo' }}</span>
                        </p>
                    </div>
                </div>
            </div>


            <!-- Modal turnos Cajas -->
            <div class="modal fade" id="turnos" tabindex="-1" aria-labelledby="turnos" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar turnos a la caja: <span
                                    class="text-lowercase">{{ $p->caja }}</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                                v-show="message.message && message.type">
                                <strong>@{{ message.message }}</strong>
                            </div>
                            <br>
                            <!-- mostrar todas los turnos agregados a caja -->
                            <div class="row">

                                <div class="col-8 ch-60">
                                    <div class="form-inline">
                                        <div class="form-group">
                                            <label for="">Turnos</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Buscar..."
                                                    v-model="txtBusqueda_Turnos" @keyup="getFilterTurnos()">

                                                <a href="#" class="input-group-text" id="basic-addon2"
                                                    v-show="txtBusqueda_Turnos.length > 0 && Filter_Turnos.length > 0"
                                                    @click="clearTurnos()">
                                                    <span class="mdi mdi-backspace-outline"></span>
                                                </a>
                                            </div>
                                            <ul class="list-group shadow" v-if="Filter_Turnos.length > 0"
                                                v-for="k in Filter_Turnos" v-bind:key="k.id">
                                                <li class="list-group-item rounded-0 border-0 usuario"
                                                    @change="setTurnoCaja(k.id)">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input"
                                                                :checked="isCheckTurnos(k.id)">
                                                            @{{ k.turno }}


                                                        </label>

                                                    </div>

                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4" style="max-height: 100%; overflow:auto;">
                                    <h5>Turnos agregados a la caja: <span class="text-lowercase">{{ $p->caja }}</span>
                                    </h5>
                                    <small v-if="caja_turnos.length == 0">Aun no se han agregado Turnos a esta caja</small>
                                    <ul class="list-group" v-for="t in caja_turnos" :key="t.id">
                                        <li class="list-group-item">
                                            @{{ t.opcion_turnos.turno }}
                                            <a class="nav-link float-end text-muted" href="#"
                                                @click="setTurnoCaja(t.opcion_turnos_id)"><span
                                                    class="mdi mdi-delete"></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Cajas precios -->
            <div class="modal fade" id="precios" tabindex="-1" aria-labelledby="precios" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar precios a caja: <span
                                    class="text-lowercase">{{ $p->caja }}</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                                v-show="message.message && message.type">
                                <strong>@{{ message.message }}</strong>

                            </div>
                            <div class="row">

                                <div class="col-8 ch-60">

                                    <div class="form-inline">
                                        <div class="form-group">
                                            <label for="list_precios">Precios</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Buscar..."
                                                    v-model="txtBusquedap" @keyup="getFilterPrecio()">

                                                <a href="#" class="input-group-text" id="basic-addon2"
                                                    v-show="txtBusquedap.length > 0 && FilterPrecio.length > 0"
                                                    @click="clearPrecio()">
                                                    <span class="mdi mdi-backspace-outline"></span>
                                                </a>
                                            </div>
                                            <ul class="list-group shadow" v-if="FilterPrecio.length > 0"
                                                v-for="pr in FilterPrecio " v-bind:key="pr.id">
                                                <li class="list-group-item rounded-0 border-0 usuario"
                                                    @change="setPrecioCajas(pr.id)">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input"
                                                                :checked="isCheckPrecio(pr.id)">
                                                            @{{ pr.detalle }}
                                                        </label>

                                                    </div>

                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4" style="max-height: 100%; overflow:auto;">
                                    <h5>Precios agregados a la caja: <span
                                            class="text-lowercase">{{ $p->caja }}</span></h5>
                                    <small v-if="caja_precios.length == 0">Aun no se han agregado precios a esta
                                        caja</small>
                                    <ul class="list-group" v-for="pr in caja_precios" :key="pr.id">
                                        <li class="list-group-item">
                                            @{{ pr.precios.detalle }}
                                            <a class="nav-link float-end text-muted" href="#"
                                                @click="setPrecioCajas(pr.precios_id)"><span
                                                    class="mdi mdi-delete"></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Modal Cajas bodegas -->
            <div class="modal fade" id="bodegas" tabindex="-1" aria-labelledby="bodegas" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar bodegas de ventas </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                                v-show="message.message && message.type">
                                <strong>@{{ message.message }}</strong>

                            </div>
                            <div class="row">

                                <div class="col-8 ch-60">

                                    <div class="form-inline">
                                        <label for="searchBodega">Bodegas</label>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Buscar..."
                                                    v-model="searchBodega">
                                            </div>
                                            <ul class="list-group shadow" v-for="b in getBodegasList " v-bind:key="b.id">
                                                <li class="list-group-item rounded-0 border-0 usuario"
                                                    @change="setBodega(b.id)">

                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input"
                                                                :checked="getCheckBodega(b.id)" :value="b.id">
                                                            @{{ b.bodega }}
                                                        </label>

                                                    </div>

                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4" style="max-height: 100%; overflow:auto;">
                                    <h5>Bodegas agregadas a esta caja: <span
                                            class="text-lowercase">{{ $p->caja }}</span></h5>
                                    <small v-if="bodegasAdd.length == 0">Aun no se han agregado bodegas a esta
                                        caja</small>
                                    <ul class="list-group" v-for="ba in bodegasAdd" :key="ba.id">
                                        <li class="list-group-item">
                                            @{{ ba.bodegas.bodega }}
                                            <a class="nav-link float-end text-muted" href="#"
                                                @click="setBodega(ba.bodegas_id)">
                                                <span class="mdi mdi-delete"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Modal Usuarios Cajas -->
            <div class="modal fade" id="usuarios" tabindex="-1" aria-labelledby="usuarios" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar usuarios a la caja: <span
                                    class="text-lowercase">{{ $p->caja }}</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                                v-show="message.message && message.type">
                                <strong>@{{ message.message }}</strong>
                            </div>
                            <!-- mostrar todos usuario cajas -->
                            <div class="row">

                                <div class="col-8 ch-60">
                                    <div class="form-inline">
                                        <div class="form-group">
                                            <label for="">Usuarios</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Buscar..."
                                                    v-model="txtBusqueda" @keyup="getFilter()">

                                                <a href="#" class="input-group-text" id="basic-addon2"
                                                    v-show="txtBusqueda.length > 0 && ListFilter.length > 0"
                                                    @click="clear()">
                                                    <span class="mdi mdi-backspace-outline"></span>
                                                </a>
                                            </div>

                                            <ul class="list-group shadow" v-if="ListFilter.length > 0"
                                                v-for="p in ListFilter" v-bind:key="p.id">
                                                <li class="list-group-item rounded-0 border-0 usuario"
                                                    @change="setCajasUsuario(p.id)">

                                                    <div class="form-check" style="cursor:pointer;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input"
                                                                :checked="isCheck(p.id)">
                                                            @{{ p.name }}
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4" style="max-height: 100%; overflow:auto;">
                                    <h5>Usuarios agregados a la caja: <span
                                            class="text-lowercase">{{ $p->caja }}</span>
                                    </h5>
                                    <small v-if="cajas_users.length == 0">Aun no se han agregado usuarios en caja</small>
                                    <ul class="list-group" v-for="c in cajas_users" :key="c.id">
                                        <li class="list-group-item">
                                            @{{ c.users.name }}
                                            <a class="nav-link float-end text-muted" href="#"
                                                @click="setCajasUsuario(c.users_id)"><span
                                                    class="mdi mdi-delete"></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Cajas comprobantes -->
            <div class="modal fade" id="comprobanteCaja" tabindex="-1" aria-labelledby="comprobanteCaja"
                aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar cajas de ventas comprobantes</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                                v-show="message.message && message.type">
                                <strong>@{{ message.message }}</strong>

                            </div>
                            <div class="row">

                                <div class="col-8 ch-60">

                                    <div class="form-inline">
                                        <div class="form-group">

                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" placeholder="Buscar..."
                                                    v-model="searchCaja">
                                            </div>
                                            <ul class="list-group shadow" v-for="c in getCajasList " v-bind:key="c.id">
                                                <li class="list-group-item rounded-0 border-0 usuario"
                                                    @change="setCaja(c.id)" style="cursor:pointer;">

                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input"
                                                                :checked="getCheckCaja(c.id)" :value="c.id">
                                                            @{{ c.caja }}
                                                        </label>

                                                    </div>

                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4" style="max-height: 100%; overflow:auto;">
                                    <h5>Cajas agregadas a esta caja de donde puede realizar comprobantes</h5>
                                    <small v-if="cajasAdd.length == 0">Aun no se han agregado bodegas a esta
                                        caja</small>
                                    <ul class="list-group" v-for="ca in cajasAdd" :key="ca.id">
                                        <li class="list-group-item">
                                            @{{ ca.cajas_origen && ca.cajas_origen.caja }}
                                            <a class="nav-link float-end text-muted" href="#"
                                                @click="setCaja(ca.origen_cajas_id)">
                                                <span class="mdi mdi-delete"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Modal Alertas -->
            <div class="modal fade" id="alertas" tabindex="-1" aria-labelledby="turnos" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Alerta a cajas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('cajas.send_alertas') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $p->cid }}">
                                <div class="mb-3">
                                    <select class="form-select" aria-label="Default select example" name="tipo">
                                        <option selected>Tipo de alerta</option>
                                        <option value="1">Notificación</option>
                                        <option value="2">Redirection</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label">Alerta</label>
                                    <input type="text" class="form-control" name="message" id=""
                                        aria-describedby="helpId" placeholder="Escriba aquí el mensaje"
                                        maxlength="100" />

                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label">Path redirección</label>
                                    <input type="text" class="form-control" name="url" id=""
                                        aria-describedby="helpId" placeholder="Eje. /home" />
                                </div>

                                <button class="btn btn-primary " type="submit">
                                    <span class="mdi mdi-send"></span>
                                    Enviar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        var app = new Vue({
            el: "#cajasShow",
            data: {
                /*para agregar usuario a caja*/
                txtBusqueda: "",
                list: [],
                selected: [],
                caja: "{{ Crypt::encryptString($p->id) }}",
                ListFilter: [],
                message: {},
                cajas_users: [],

                /*para agregar opcion turno  a caja*/
                txtBusqueda_Turnos: "",
                listf: [],
                selected_Turnos: [],
                Filter_Turnos: [],
                caja_turnos: [],
                /*para agregar precio  a caja*/
                precio: '',
                caja_precios: [],

                txtBusquedap: "",
                FilterPrecio: [],
                listp: [],
                selectedPrecio: [],

                bodegasList: @json($bodegas),
                bodegasAdd: @json($bodega_cajas),
                bodegaSelected: [],
                searchBodega: "",
                /**para cajas comprobantes */
                cajasList: @json($cajas),
                cajasAdd: @json($cajas_comprobantes),
                cajaSelected: [],
                searchCaja: "",

            },
            methods: {
                /** funciones para usuarios permitidos en caja  */
                getData() {
                    axios.get('{{ route('cajas_users.index_api') }}')
                        .then((rs) => {
                            if (rs.data.list)
                                this.setList(rs.data.list);


                        })
                },
                setCajasUsuario(id) {

                    axios.post('{{ route('cajas.store_apiUsuario') }}', {
                        "caja": this.caja,
                        "user": id,
                    }).then((rs) => {

                        if (rs.data) {
                            this.cajas_users = rs.data.users;
                            this.setMessage(rs.data.message, rs.data.type);
                        }

                    })
                },
                getUsuarioCaja() {

                    axios.post('{{ route('cajas.list_usuarios') }}', {
                        "caja": this.caja,
                    }).then((rs) => {
                        if (rs.data.users)
                            this.cajas_users = rs.data.users;

                    })
                },
                destroyCajasUsuario(id) {
                    if (confirm("Este usuario se borrara definitivamente. ¿Realmente quieres eliminarlo?"))
                        axios.post('{{ route('cajas_users.destroy_api') }}', {
                            "id": id
                        })
                        .then((rs) => {
                            if (rs.data.list)
                                this.setList(rs.data.list);
                            if (rs.data.message)
                                this.setMessage(rs.data.message, rs.data.type);
                        }).catch(e => console.log(e));
                },
                setSelected(p) {
                    this.selected = p;
                    this.list = [];
                    this.txtBusqueda = "";
                },
                setMessage(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };
                    setTimeout(() => {
                        this.message = {};
                    }, 2 * 1000)
                },
                getFilter() {
                    let reg = new RegExp(this.txtBusqueda, "i");
                    this.ListFilter = this.list.filter(r => reg.test(r.name))
                },
                setList(l) {
                    this.list = l;
                    this.getFilter();
                },
                clear() {
                    this.txtBusqueda = "";
                    this.getFilter();

                },
                isCheck(id) {
                    if (this.cajas_users != null && this.cajas_users.length > 0)
                        return this.cajas_users.find(p => p.users_id == id) != null ? true : false;
                },

                /**funciones para opcion de turnos habilitados a  esta caja */

                getDataTurnos() {
                    axios.get('{{ route('caja_turnos.index_api') }}')
                        .then((rs) => {
                            if (rs.data.listf)
                                this.setListTurnos(rs.data.listf);

                        })
                },
                setTurnoCaja(id) {

                    axios.post('{{ route('cajas.store_apiTurnos') }}', {
                        "caja": this.caja,
                        "opcion_turno": id,
                    }).then((rs) => {

                        if (rs.data) {
                            this.caja_turnos = rs.data.opcion_turnos;
                            this.setMessage(rs.data.message, rs.data.type);

                        }

                    })
                },
                getTurnosCaja() {

                    axios.post('{{ route('cajas.list_opcionTurnos') }}', {
                        "caja": this.caja,
                    }).then((rs) => {
                        if (rs.data.opcion_turnos)
                            this.caja_turnos = rs.data.opcion_turnos;

                    })
                },

                destroyCajasTurnos(id) {
                    if (confirm("Este Turno se borrara definitivamente. ¿Realmente quieres eliminarlo?"))
                        axios.post('{{ route('caja_turnos.destroy_api') }}', {
                            "id": id
                        })
                        .then((rs) => {
                            if (rs.data.listf)
                                this.setListTurnos(rs.data.listf);
                            if (rs.data.message)
                                this.setMessage(rs.data.message, rs.data.type);
                        }).catch(e => console.log(e));
                },
                setSelectedTurnos(k) {
                    this.selected_Turnos = k;
                    this.listf = [];
                    this.txtBusqueda_Turnos = "";
                },

                getFilterTurnos() {
                    let reg = new RegExp(this.txtBusqueda_Turnos, "i");
                    this.Filter_Turnos = this.listf.filter(r => reg.test(r.turno))
                },
                setListTurnos(l) {
                    this.listf = l;
                    this.getFilterTurnos();
                },
                clearTurnos() {
                    this.txtBusqueda_Turnos = "";
                    this.getFilterTurnos();
                },
                isCheckTurnos(id) {
                    if (this.caja_turnos != null && this.caja_turnos.length > 0)
                        return this.caja_turnos.find(p => p.opcion_turnos_id == id) != null ? true : false;
                },
                /**funciones para asignar precios a  esta caja */

                /***aqui obtengo los datos de las cajas disponibles */
                getDataPrecio() {
                    axios.get('{{ route('caja_precios.index_precio') }}')
                        .then((rs) => {
                            if (rs.data.listp)
                                this.setListPrecio(rs.data.listp);

                        })
                },
                /**aqui creo la caja al precio */
                setPrecioCajas(id) {

                    axios.post('{{ route('cajas.store_apiPrecio') }}', {
                        "caja": this.caja,
                        "precio": id,
                    }).then((rs) => {

                        if (rs.data) {
                            this.caja_precios = rs.data.precios;
                            this.setMessage(rs.data.message, rs.data.type);
                        }

                    })
                },
                /** aqui obtengo las precios disponibles*/
                getPrecioCaja() {

                    axios.post('{{ route('cajas.list_precios') }}', {
                        "caja": this.caja,
                    }).then((rs) => {
                        if (rs.data.precios)
                            this.caja_precios = rs.data.precios;

                    })
                },
                destroyCajasPrecio(id) {
                    if (confirm("Este precio se borrara definitivamente. ¿Realmente quieres eliminarlo?"))
                        axios.post('{{ route('caja_precios.destroy_apiPrecio') }}', {
                            "id": id
                        })
                        .then((rs) => {
                            if (rs.data.listp)
                                this.setListPrecio(rs.data.listp);
                            if (rs.data.message)
                                this.setMessage(rs.data.message, rs.data.type);
                        }).catch(e => console.log(e));
                },
                setSelectedPrecio(pr) {
                    this.selectedPrecio = pr;
                    this.listp = [];
                    this.txtBusquedap = "";
                },

                getFilterPrecio() {
                    let reg = new RegExp(this.txtBusquedap, "i");
                    this.FilterPrecio = this.listp.filter(r => reg.test(r.detalle))
                },
                setListPrecio(l) {
                    this.listp = l;
                    this.getFilterPrecio();
                },
                clearPrecio() {
                    this.txtBusquedap = "";
                    this.getFilterPrecio();
                },
                isCheckPrecio(id) {
                    if (this.caja_precios != null && this.caja_precios.length > 0)
                        return this.caja_precios.find(p => p.precios_id == id) != null ? true : false;
                },
                //**bodegas cajas */
                setBodega(id) {
                    axios.post("{{ route('cajas.bodega') }}", {
                        bodega: id,
                        caja: this.caja,
                    }).then((r) => {
                        if (r.data) {
                            if (r.data.list)
                                this.bodegasAdd = r.data.list;

                            if (r.data.message)
                                this.setMessage(r.data.message, r.data.type);
                        }
                    })
                },
                getCheckBodega(id) {
                    return this.bodegasAdd.find(r => r.bodegas_id == id) != null;
                },
                //*cajas comprobantes cajas de ventas*/
                setCaja(id) {
                    axios.post("{{ route('cajas.comprobantes_cajas') }}", {
                        cajaC: id,
                        caja: this.caja,
                    }).then((r) => {
                        if (r.data) {
                            if (r.data.list)
                                this.cajasAdd = r.data.list;

                            if (r.data.message)
                                this.setMessage(r.data.message, r.data.type);
                        }
                    }).catch((error) => {
                        console.error('Error en la petición:', error);
                    });
                },
                getCheckCaja(id) {
                    return this.cajasAdd.find(r => r.origen_cajas_id == id) != null;
                },
            },
            mounted: function() {
                document.onreadystatechange = () => {
                    if (document.readyState == "complete") {
                        this.getData()
                        this.getDataTurnos()
                        this.getDataPrecio()
                        this.getUsuarioCaja();
                        this.getTurnosCaja();
                        this.getPrecioCaja();
                    }
                };
                const modalCajas = document.getElementById('turnos');
                modalCajas.addEventListener('hidden.bs.modal', () => {
                    location.reload();
                });
            },
            computed: {
                getBodegasList() {
                    var reg = new RegExp(this.searchBodega, "i");
                    return this.bodegasList.filter(r => reg.test(r.bodega));
                },
                getCajasList() {
                    var reg = new RegExp(this.searchCaja, "i");
                    return this.cajasList.filter(r => reg.test(r.caja));
                }
            }
        });
    </script>
@endsection
