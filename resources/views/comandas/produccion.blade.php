@extends('layouts.Panelproduccion')
@section('css-produccion')
    <style>
        body {
            background-color: #E0F2F1;
        }



        .btn-light {
            background: #DAE0E5;
        }

        .progress {
            height: 1.25rem;
        }

        .progress-bar.bg-tiempo {
            background-color: #26A69A !important;
        }


        .bg-tiempo {
            background: #26A69A !important;
            color: #ECEFF1;

        }

        .bg-prioridad {
            background: #F5FA55 !important;
            color: #263238 !important;
            font-weight: bold;
        }

        .bg-tiempo2 {
            background: #F4511E;
            color: #ECEFF1;
            width: 100%;
        }

        .card-productos {
            background: #E3F2FD;
            border: none;
            box-shadow: 1px 5px 2px #BBDEFB;
            E0F7FA
        }

        .btn-check:checked+.card-comanda,
        .card-comanda-activa {
            background: #BBDEFB !important;
            border: none;
            box-shadow: 1px 7px 2px #90CAF9;
        }

        .card-comanda:hover {

            box-shadow: 1px 5px 2px #90CAF9;
        }

        .card-productos.producto {
            color: #263238;
        }

        .card-productos.caja {
            color: #37474F;
        }

        .card-productos.creacion {
            color: #546E7A;
            font-size: 10pt;
            font-weight: lighter;
        }

        .card-productos.tiempo {
            color: #37474F;
        }

        .card-productos.bg-tiempo {
            background: #26A69A;
            color: #ECEFF1;
        }

        .panel-solicitudes {
            position: fixed;
            height: 92vh;
            width: 260px;
            left: 6px;
            top: 75px;
            bottom: 12px;
            background: #FFF;
            border-radius: 6px;
            overflow-y: scroll;
            z-index: 1000;
        }


        .text-mesa {
            color: #263238;
        }

        .text-cliente {
            color: #37474F;
        }

        .text-creacion {
            color: #37474F;
        }

        .text-observacion {
            color: #455A64;
        }

        .btn-mesas {
            position: fixed;
            bottom: 16px;
            left: 6px;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #26A69A;
            color: #FAFAFA;
            font-size: 22pt;
        }

        .btn-mesas:hover {
            background: #239589;
            color: #FFF;
        }

        body::-webkit-scrollbar {
            width: 8px;

        }

        body::-webkit-scrollbar-thumb {
            background-color: #ababab;
            border-radius: 6px;
        }

        body::-webkit-scrollbar-thumb:hover {
            background-color: #9f9f9f;

        }

        * {
            scrollbar-width: thin;
            scrollbar-color: #ababab #f5f5f5;
        }

        *:hover {
            scrollbar-color: #9f9f9f rgb(239, 239, 239);
        }

        .panel-solicitudes::-webkit-scrollbar {
            width: 8px;

        }

        .panel-solicitudes::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 4px;
        }

        .panel-solicitudes::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        .ct1 {
            background-color: #37474F;
            color: #ffffff;
        }

        .ct1:hover {
            background-color: #263238;
            color: #ffffff;
        }

        .ct2 {
            background-color: #26A69A;
            color: #ffffff;
        }

        .ct2:hover {
            background-color: #009688;
            color: #ffffff;
        }

        .card-comanda-no-seleccionada {
            background: #37474F;
            color: #ECEFF1;
        }

        .notify {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 2%;
            z-index: 2001;
            background: #263238;
        }

        .notify .card {
            width: 30%;
            margin: auto;
        }

        .fade-enter-active,
        .fade-leave-active {
            transition: opacity 0.45s;
        }

        .fade-enter,
        .fade-leave-to {
            opacity: 0;
        }

        .content-alert {
            position: fixed;
            right: 1%;
            bottom: 2%;
            width: 30%;
            z-index: 1100;
        }

        .gold-card {
            background: #FABF55 !important;
            color: #261201;
        }

        .progress-bar {
            background-color: #26A69A !important;
        }

        .card-pedido {
            background: #E3F2FD !important;
            box-shadow: 0px 5px 2px #BBDEFB;
            border: none !important;
        }

        .btn-check:checked+.card-pedido,
        .card-pedido-activa {
            background: #BBDEFB !important;
            border: none;
            box-shadow: 0px 7px 2px #90CAF9;
        }

        .card-pedido:hover {

            box-shadow: 0px 5px 2px #90CAF9;
        }
    </style>
@endsection

@section('panel_produccion')
    <div id="pedidos">
        <div class="notify" v-if="!notify">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Alertas</h5>
                    <p class="card-text">
                        Para escuchar las alertas de un nuevo pedido debe aceptar las notificaciones, de lo contrario no
                        llegaran los nuevos pedidos.<br><br>
                        <button type="button" class="btn btn-primary" @click="notify = true">
                            <span class="mdi mdi-bell-alert"></span>
                            Aceptar notificaciones
                        </button>
                    </p>
                </div>
            </div>
        </div>
        <button class="btn btn-mesas" type="button" v-show="!panelProduccion" @click="setPanel()">
            <span class="mdi mdi-table-chair"></span>
        </button>
        <div class="panel-solicitudes p-4 shadow" v-show="panelProduccion">
            <div class="row">
                <div class="col-12">
                    <span class="h4">
                        Solicitudes
                    </span>
                    <span class="mdi mdi-minus btn float-end" @click="setPanel()"></span>
                </div>
                <div class="col-12 mt-3">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="btnAtajos" v-model="atajos"
                            @change="setAtajos()">
                        <label class="form-check-label" for="btnAtajos">
                            Mostrar atajos (min)
                        </label>
                    </div>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="actualizarSeg">Actualizar (seg)</span>
                        <input type="number" name="name" class="form-control" placeholder="Segundos"
                            aria-describedby="actualizarSeg" v-model="actualizar" @change="setActualizar()" />
                    </div>



                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="" id="" aria-describedby="helpId"
                            placeholder="Buscar por numero de mesa" v-model="searchMesa" @keyup="buscarMesa()" />
                    </div>
                </div>
                <div class="col-12">
                    <div class="row " id="mesas">
                        <div class="col-12 mb-3 btn-group" v-for="c in getNumeroComandas">
                            <input class="btn-check d-none" type="checkbox" :value="c.id" :id="c.cid"
                                v-model="filterPedidos" autocomplete="off" multiple>
                            <label class="card card-pedido btn" :for="c.cid">
                                <div class="card-body ">
                                    <div class="row text-center">
                                        <div class="col-12 text-uppercase">@{{ c.id }}</div>
                                        <div class="col-12 text-uppercase">
                                            @{{ c.cajas.caja }}
                                        </div>
                                        <div class="col-12 text-uppercase text-truncate">
                                            <small>Mesa #@{{ c.mesa }} ·
                                                @{{ c.clientes_id > 0 ? c.clientes.nombre : (c.titular ?? 'Sin cliente') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row p-4 panel-content">

            <h3 class="text-uppercase">{{ $title }}</h3>
            <div class="col-12 mb-3">
                <div class="mb-3">
                    <label for="" class="form-label">
                        Buscar productos
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-describedby="helpId"
                            placeholder="Escriba el nombre del producto" v-model="searchProducto" />

                    </div>
                </div>
            </div>
            <div class="col-12 row">
                <div class="col-12" v-if="getSolicitudes.length == 0 && !load">
                    <div class="alert alert-primary" role="alert">
                        <h4 class="alert-heading">¡Aun no hay pedidos!</h4>
                        <p>En esta sección aparecerán todos los pedidos, no es requerido recargar esta pagina para poder ver
                            los nuevos pedidos mientras estas conectado a internet.</p>
                        <hr>
                        <p class="mb-0">Si este dispositivo esta o estuvo desconectado de internet, por favor revisa la
                            conexión y recarga la pagina para conectarse al servicio de pedidos.</p>
                    </div>
                </div>
                <div class="col-12" v-show="load">
                    <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="100"
                        aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%">
                            Cargando...
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-4 fade-out" v-for="p in getSolicitudes" :key="p.id">
                    <input type="checkbox" class="btn-check" :id="'pedido_' + p.id" autocomplete="off" multiple
                        v-model="selectedDetalle" :value="p">
                    <label class="card" :for="'pedido_' + p.id"
                        :class="{ 'gold-card': p.prioridad, 'card-productos': !p.prioridad }">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-1 text-lg-center m-auto">
                                    <div class="d-block d-sm-none">Cantidad @{{ p.cantidad }}
                                    </div>
                                    <div class="d-none d-sm-block">@{{ p.cantidad }}</div>
                                </div>
                                <div class="col-12 col-lg-7">
                                    <div class="row">
                                        <div class="col-12 fs-5 fw-light producto text-uppercase">
                                            @{{ p.dprecio.detalle }} Pedido: @{{ p.comandas_id }}
                                        </div>
                                        <div class="col-12 caja text-uppercase">
                                            Solicitado de @{{ p.comandawtcaja.cajas.caja }} · @{{ p.user_solicita.user }}
                                        </div>
                                        <div class="col-12 creacion text-uppercase">
                                            <b>
                                                Solicitado @{{ p.solicitado }}
                                            </b>
                                        </div>


                                    </div>
                                </div>
                                <div class="col-4" v-if="p.aceptacion && p.espera && p.entregado == null">
                                    <div class="row">
                                        <div class="col-12 creacion fw-bolder mb-1 ">
                                            <button type="button" class="btn  btn-sm ct1 float-end me-2"
                                                @click="setMinutos(p.cid)">
                                                <span class="mdi mdi-alarm-plus"></span>
                                                @{{ addMinutos }} minutos
                                            </button>
                                        </div>
                                        <div class="col-12 creacion fw-bolder mb-2 float-end">
                                            <btn-terminado :producto="p"
                                                url="{{ route('comandas.produccion.completo') }}"
                                                @filtro="setTerminado"></btn-terminado>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-2"
                                    v-if="p.aceptacion && p.espera && p.entregado == null && p.aceptacion.length > 2">

                                    <progreso :aceptacion="p.aceptacion" :espera="p.espera"
                                        :incremento="p.incremento_tiempo" :prioridad="p.prioridad"
                                        :actualizar="actualizar" />
                                </div>

                                <div class="col-12 col-lg-4" v-if="p.aceptacion == null || p.espera == null">
                                    <timer :producto="p" :empleados="empleados"
                                        :url="{
                                            'timer': '{{ route('comandas.produccion.timer') }}',
                                            'completo': '{{ route('comandas.produccion.completo') }}',
                                            'negado': '{{ route('comandas.produccion.negado') }}',
                                        }"
                                        @timer="setTimer" @filtrop="setTerminado" :atajos="atajos">
                                    </timer>
                                </div>
                                <div class="col-12 mt-2  text-uppercase " v-if="p.observaciones">
                                    <p class="alert alert-light text-justify" role="alert">
                                        <b>
                                            Observacion @{{ p.observaciones }}

                                        </b>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

        </div>

        <div class="content-alert fade-out" v-show='message.length > 0'>
            <div class="alert alert-success" :class="{ 'alert-success': typeMessage, 'alert-danger': !typeMessage }"
                role="alert">
                <h4 class="alert-heading">Información</h4>
                <p>
                    @{{ message }}
                </p>
                <p class="mb-0">
                    <button class="btn btn-light btn-sm " type="buttom" role="button"
                        @click="message=''">Ocultar</button>
                </p>
            </div>
        </div>

        <div class="panelAlertas" v-if="pmessage && pmessage.length > 0">
            <div class="alert  alert-dismissible fade show" :class="{ 'alert-info': status, 'alert-danger': !status }"
                role="alert">
                <strong>@{{ pmessage }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    @click="pmessage = ptype = ''"></button>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="module">
        //TODO migrar a componente el tiempo
        var app = appVue({

            data() {
                return {
                    comanda_detalles: @json($comanda_detalles),
                    selectedDetalle: [],
                    pedido: [],
                    searchProducto: '',
                    searchMesa: '',
                    panelProduccion: false,
                    notify: null,
                    timer: null,
                    empleados: @json($empleados),
                    empleadoSelected: '',
                    atajos: false,
                    message: '',
                    typeMessage: true,
                    load: false,
                    addMinutos: 5,
                    pmessage: '',
                    filterPedidos: [],
                    actualizar: 30,
                    broadcast: @json($broadcast),
                    routeData: '{{ $routeData }}'
                };
            },
            created() {
                //route: 'pedidos.cocina'
                //listen: 'PedidosCocina'
                if (this.broadcast && this.broadcast.route && this.broadcast.listen) {
                    console.log('Socket Inicializacion pusher', this.broadcast.route, this.broadcast.listen);
                    window.Echo.private(this.broadcast.route)
                        .listen(this.broadcast.listen, (d) => {
                            console.log(d);
                            this.pedido = d;
                            this.getData();
                            this.sound();
                        });
                }

                if (localStorage.getItem('atajos') != null)
                    this.atajos = parseInt(localStorage.getItem('atajos')) == 1;
                else {
                    window.localStorage.setItem('atajos', this.atajos ? 1 : 0);
                }
                if (localStorage.getItem('actualizarSeg') != null)
                    this.actualizar = parseInt(localStorage.getItem('actualizarSeg'));
                else {
                    window.localStorage.setItem('actualizarSeg', this.actualizar);
                }

                if (localStorage.getItem('panelProduccion') != null)
                    this.panelProduccion = parseInt(localStorage.getItem('panelProduccion')) == 1;
                else localStorage.setItem('panelProduccion', this.panelProduccion ? 1 : 0);

                this.getNotifyPermission()
            },
            computed: {
                getSolicitudes: function() {
                    const bq = new RegExp(this.searchProducto, 'i');
                    return this.comanda_detalles.filter(
                        c => (bq.length == 0 || bq.test(c.dprecio.detalle)) &&
                        (this.filterPedidos.length == 0 || this.filterPedidos.includes(c
                            .comandas_id))
                    ).sort((a, b) => {
                        if (a.prioridad && !b.prioridad)
                            return -1;
                        else if (!a.prioridad && b.prioridad)
                            return 1;
                        else {
                            if (a.espera !== null && b.espera !== null)
                                return a.espera.localeCompare(b.espera);
                            else if (a.espera === null && b.espera !== null)
                                return 1;
                            else if (a.espera !== null && b.espera === null)
                                return -1;
                            else {
                                if (a.aceptacion === null && b.aceptacion !== null)
                                    return -1;
                                else if (a.aceptacion !== null && b.aceptacion === null)
                                    return -1;
                                else
                                    return 0;
                            }
                        }
                    });
                },
                getNumeroComandas: function() {
                    let comandas = [];
                    this.comanda_detalles.map((p) => {
                        if (!comandas.some(i => i.id == p.comandas_id))
                            comandas.push(p.comandawtcaja);

                    })

                    return comandas.sort((a, b) => a - b);
                }
            },
            methods: {
                getData: function() {
                    //routeData: {{ route('comandas.produccion_cocina') }}
                    if (this.routeData && this.routeData != null)
                        axios.post(this.routeData, {

                        }).then((rs) => {
                            this.comanda_detalles = rs.data.list;
                        }).catch((err) => {

                        });
                },
                setMinutos: function(id) {
                    axios.post("{{ route('comandas.produccion.set_minutos') }}", {
                        minutos: this.addMinutos,
                        id: id,
                    }).then((rs) => {
                        this.setTimer([rs.data.producto]);
                    }).catch((err) => {

                    });
                },
                sound: function() {
                    if (this.notify) {
                        if (Notification.permission !== "granted") {
                            Notification.requestPermission();
                        } else {
                            let notification = new Notification("¡Nuevo pedido!", {
                                body: "Hay una nueva solicitud de pedidos"
                            });
                        }
                        const sound = "{{ asset('sonidos/notify.wav') }}";
                        new Audio(sound).play();
                    }
                },
                setTimer: function(producto) {

                    if (producto.length == 1) {
                        let comandas = this.comanda_detalles.filter(p => p.id != producto[0].id);
                        this.comanda_detalles = [];
                        this.load = true;
                        this.comanda_detalles = [...comandas, ...producto];
                        this.load = false;
                        this.setMessage('Se agrego correctamente.');
                    } else
                        this.setMessage('Ocurrió un error al agregar, actualice e intente de nuevo.', false);

                },
                setTerminado: function(producto, message = null) {
                    try {


                        if (producto.id > 0) {
                            let comandas = this.comanda_detalles.filter(p => p.id != producto.id);
                            this.comanda_detalles = comandas;
                            console.log(message);
                            if (message)
                                this.setMessage(message);
                        }
                    } catch (error) {
                        console.log(error);
                    }
                },
                setPanel: function() {
                    this.panelProduccion = !this.panelProduccion;
                    window.localStorage.setItem('panelProduccion', this.panelProduccion ? 1 : 0);
                },
                setAtajos: function() {
                    window.localStorage.setItem('atajos', this.atajos ? 1 : 0);
                },
                setActualizar: function() {
                    window.localStorage.setItem('actualizarSeg', this.actualizar);
                },
                setMessage: function(message, typeMessage = true) {
                    this.message = message;
                    this.typeMessage = typeMessage;
                    setInterval(() => {
                        this.message = '';
                        this.typeMessage = true;
                    }, 10 * 1000);
                },

                getNotifyPermission: function() {

                    if (Notification.permission === "granted") {
                        this.notify = true;
                    } else if (Notification.permission !== "denied") {
                        Notification.requestPermission().then(permission => {
                            if (permission === "granted") {
                                this.notify = true;
                            }
                        });
                    }

                },
            },
        });
        app.component('timer', component.timer);
        app.component('progreso', component.progreso);
        app.component('btntimer', component.btnTimer);
        app.component('btnTerminado', component.btnTerminado);
        app.mount("#pedidos");
    </script>
@endsection
