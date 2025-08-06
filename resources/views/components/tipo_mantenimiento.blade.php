<style>
    .ch-60 {
        height: 60vh;
        overflow-x: auto;
    }

    .usuario:hover {
        background: #4DB6AC;
        color: #fff;
    }

    .usuario .card {
        height: 65px;
        overflow-x: auto;
    }

    .pointer {
        cursor: pointer;
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

    .body_cajas {
        height: 85px;
    }
</style>
<div id="TipomantenimientosShow">
    <!-- este foreach recorre los tipos de mantenimientos y asi accedo asus propiedades -->
    @foreach ($tipomantenimientos as $m)
    @endforeach
        <div class="row">
                <div class="col-6 mb-4">
                    <h6 class="card-subtitle mb-2">Detalles</h6>
                    <div class="row ">
                        <div class="col-6 text-right">Id del grupo telegram:</div>
                        <div class="col-6" >{{isset($m->id_grupo_telegram)? $m->id_grupo_telegram : 'No tiene grupo de telegram'}}</div>
                        <div class="col-6 text-right">Tiempo promedio:</div>
                        <div class="col-6" >
                                {{$m->duracion_promedio}}
                        </div>
                        <div class="col-6 text-right">Estado en que inicia:</div>
                        <div class="col-6" >
                                {{isset($m->inicio_habitaciones)? $m->inicio_habitaciones->estado_habitacion : 'No inicio con estado'}}
                        </div>
                        <div class="col-6 text-right">Estado en que se completo:</div>
                        <div class="col-6" >
                                {{isset($m->completado_habitaciones)? $m->completado_habitaciones->estado_habitacion : 'No se completo con estado' }}
                        </div>
                        <div class="col-6 text-right">Notificacion:</div>
                        <div class="col-6">
                            @if ($m->notificacion == 1)
                                Sí
                            @else
                                No
                            @endif
                        </div>

                    </div>
                </div>

        </div>
    @can('tipo_mantenimientos.create')
    <div class="row mb-3">

        <div class="col-3 mb-4">
            <button type="button" class="card p-2 border border-1 border-success" data-bs-toggle="modal"
                data-bs-target="#tipo_mantenimientos">
                <div class="card-body body_cajas p-2">
                    <p class="card-text">
                        <span class="mdi mdi-plus"></span> Agregar usuarios a tipo de mantenimientos <span>
                    </p>
                </div>
            </button>
        </div>
        <!-- mostrar todas las usuarios agregadas a mantenimientos -->
        @foreach ($tipoMantenimientoU as $t)
            <div class="col-3 mb-4 ">
                <div class="card usuario">
                    <div class="card-body body_cajas">
                        <a href="{{ route('tipo_mantenimiento_users.confirm', ['id' => \Crypt::encryptString($t->id)]) }}"
                            class="float-end
                    text-danger h4" title="Eliminar Caja">
                            <span class="mdi mdi-close"></span>
                        </a>
                        <b class="card-title">{{ $t->users->name }}</b>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
    @endcan
    <!-- este foreach recorre los tipos de mantenimientos y asi accedo asus propiedades -->
    @foreach ($tipomantenimientos as $m)
    @endforeach

    <!-- Modal Cajas Usuarios -->
    <div class="modal fade" id="tipo_mantenimientos" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar usuarios a <span
                            class="text-uppercase">{{ $m->mantenimiento }}</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Buscar..."
                                            v-model="txtBusqueda" @keyup="getFilter()">

                                        <a href="#" class="input-group-text" id="basic-addon2"
                                            v-show="txtBusqueda.length > 0 && ListFilter.length > 0" @click="clear()">
                                            <span class="mdi mdi-backspace-outline"></span>
                                        </a>
                                    </div>
                                    <ul class="list-group shadow" v-if="ListFilter.length > 0">
                                        <li class="list-group-item rounded-0 border-0 usuario" v-for="p in ListFilter" :key="p.id">
                                            <div class="form-check" @change="setUsuarioTipoMantenimientos(p.id)" style="cursor:pointer;">
                                                <input type="checkbox" class="form-check-input" :id="'checkbox-' + p.id" :value="p"
                                                     :checked="isCheck(p.id)">
                                                <label class="form-check-label" :for="'checkbox-' + p.id">
                                                    @{{ p.name }}
                                                </label>
                                            </div>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                        <div class="col-4" style="max-height: 100%; overflow:auto;">
                            <h5>Usuarios agregados a <span class="text-uppercase">{{ $m->mantenimiento }}</span></h5>
                            <small v-if="tipo_mantenimiento_users.length == 0">Aun no se han agregado usuarios este
                                mantenimiento</small>
                            <ul class="list-group" v-for="c in tipo_mantenimiento_users" :key="c.id">
                                <li class="list-group-item">
                                    @{{ c.users.name }}
                                    <a class="nav-link float-end text-muted" href="#"
                                        @click="setUsuarioTipoMantenimientos(c.users_id)"><span
                                            class="mdi mdi-delete"></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



</div>
<script>
    var app = new Vue({
        el: "#TipomantenimientosShow",
        data: {
            /** show usuario*/
            mantenimiento: "{{ $mantenimiento }}",
            tmantenimiento: "{{ $tipomantenimientos }}",
            tipo_mantenimiento_users: [],
            message: {},
            txtBusqueda: "",
            ListFilter: [],
            list: [],
            selected: [],
            user: '',

        },
        methods: {
            /** estan son las funciones para agregar cajas a usuarios */
            /***aqui obtengo los datos de las cajas disponibles */
            getDataUsuario() {
                axios.get('{{ route('users.api_usuario') }}')
                    .then((rs) => {
                        if (rs.data.list)
                            this.setList(rs.data.list);

                    })
            },
            /**aqui creo el tipo de mantenimiento al usuario */
            setUsuarioTipoMantenimientos(id) {

                axios.post('{{ route('tipo_mantenimientos.store_apiUsuariosMantenimientos') }}', {
                    "mantenimiento": this.mantenimiento,
                    "user": id,
                }).then((rs) => {

                    if (rs.data) {
                        this.tipo_mantenimiento_users = rs.data.users;
                        this.setMessage(rs.data.message, rs.data.type);
                    }

                })
            },
            /** aqui obtengo los usuarios disponibles*/
            getUsuarioMantenimiento() {

                axios.post('{{ route('tipo_mantenimientos.list_usuarios') }}', {
                    "mantenimiento": this.mantenimiento,
                }).then((rs) => {
                    if (rs.data.users)
                        this.tipo_mantenimiento_users = rs.data.users;

                })
            },
            /**con este metodo se elimina */
            destroyUsuariosMantenimientos(id) {
                if (confirm("Esta usuario se borrara definitivamente. ¿Realmente quieres eliminarlo?"))
                    axios.post('{{ route('users.destroy_api') }}', {
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
                if (this.tipo_mantenimiento_users != null && this.tipo_mantenimiento_users.length > 0)
                    return this.tipo_mantenimiento_users.find(p => p.users_id == id) != null ? true : false;
            },
            /** aqui finalizan las funciones para agregar cajas a usuarios*/


        },
        mounted: function() {
            document.onreadystatechange = () => {
                if (document.readyState == "complete") {
                    this.getDataUsuario();
                    this.getUsuarioMantenimiento();
                }
            };
            const modal = document.getElementById('tipo_mantenimientos');
            modal.addEventListener('hidden.bs.modal', () => {
                location.reload();
            });
        },
        computed: {

        }
    });
</script>
