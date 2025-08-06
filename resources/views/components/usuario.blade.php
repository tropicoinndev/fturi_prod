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
    height: 85px;
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
    height: 95px;
}
</style>
<div id="usuarioShow">
    <div class="row mb-4">
        @can('admin')
        <div class="col-12">
            <h5>Roles <a href="{{ route('roles.create') }}" class="card-link  text" target="_blank"><span
                        class="mdi mdi-plus"></span> Agregar</a>
            </h5>
        </div>
        @endcan
        <div class="col-3 usuarios mb-3">
            <button type="button" class="card p-2 w-100 border border-1 border-success" data-bs-toggle="modal"
                data-bs-target="#role">
                <div class="card-body">
                    <p class="card-text">
                        <span class="mdi mdi-plus"></span> Agregar Rol
                    </p>
                </div>
            </button>
        </div>

        <!-- mostrar todo los roles que posee el usuario  -->
        @foreach ($rolesU as $role)
        <div class="col-3 ">
            <div class="card">

                <form action="{{ route('users.desasignar_Rol') }}" method="post">
                    @csrf
                    <div class="card-body  mb-3">
                        <b class="card-title">{{ $role->name }}</b>
                        <input type="hidden" name="rolName" value="{{ $role->name }}">
                        <input type="hidden" name="usuario" value="{{ $user }}">
                        <button type="submit" class="float-end
                    text-danger h1 position-absolute top-0 end-0 btn btn-out-danger
                    " title="Deshabilitar Rol"
                            href="{{route('users.desasignar_Rol', ['id'=> \Crypt::encryptString($role->id)]) }}">
                            <span class="mdi mdi-close"></span></button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>


    <div class="row mb-4">
        <div class="col-12">
            @can('admin')
            <h5>Cajas
                <a href="{{ route('cajas.create') }}" class="card-link" target="_blank">
                    <span class="mdi mdi-plus"></span>
                    Agregar</a>
            </h5>
        </div>
        @endcan
        <div class="col-3 mb-2">
            <button type="button" class="card p-2 w-100 border border-1 border-success" data-bs-toggle="modal"
                data-bs-target="#cajas">
                <div class="card-body body_cajas">
                    <p class="card-text">
                        <span class="mdi mdi-plus"></span> Agregar cajas a usuario
                    </p>
                </div>
            </button>
        </div>
        <!-- mostrar todas las cajas agregadas a usuarios -->
        @foreach ($cajasU as $caja)
        <div class="col-3 ">
            <div class="card usuario">
                <div class="card-body">
                    <a href="{{ route('cajas_users.delete_caja', ['id'=> \Crypt::encryptString($caja->id)]) }}" class="float-end
                    text-danger h4" title="Eliminar Caja">
                        <span class="mdi mdi-close"></span>
                    </a>
                    <b class="card-title">{{ $caja->cajas->caja }}</b>
                    <p class="card-text">{{ $caja->codigo }}</p>
                    <a href="#"></span>
                        <a type="button" class="btn btn-outline-secondary"
                            href="{{route('cajas_users.resetearPin', ['id'=> \Crypt::encryptString($caja->id)]) }}"><span
                                class="mdi mdi-lock-reset">Resetear Pin

                        </a>

                    </a>
                </div>
            </div>
        </div>
        @endforeach

    </div>
    <!-- mostrar tipo de mantenimientos  a usuarios -->

    <div class="row mb-4">
        <div class="col-12">
            @can('admin')
            <h5>Tipo de mantenimiento
                <a href="{{ route('tipo_mantenimientos.create') }}" class="card-link" target="_blank">
                    <span class="mdi mdi-plus"></span>
                    Agregar</a>
            </h5>
        </div>
        @endcan
        <div class="col-3 mb-4 ">
            <button type="button" class="card p-2 w-100 border border-1 border-success" data-bs-toggle="modal"
                data-bs-target="#mantenimientos">
                <div class="card-body body_cajas">
                    <p class="card-text">
                        <span class="mdi mdi-plus"></span> Agregar tipo de mantenimientos a usuario
                    </p>
                </div>
            </button>
        </div>
        <!-- mostrar todas las cajas agregadas a usuarios -->
        @foreach ($mantenimientosUsuarios as $tm)
        <div class="col-3 mb-4 ">
            <div class="card usuario">
                <div class="card-body body_cajas">
                    <a href="{{ route('tipo_mantenimiento_users.confirm', ['id'=> \Crypt::encryptString($tm->id)]) }}"
                        class="float-end
                    text-danger h4" title="Eliminar tipo mantenimiento">
                        <span class="mdi mdi-close"></span>
                    </a>
                    <b class="card-title">{{ $tm->tipo_mantenimientos->mantenimiento }}</b>
                    </a>
                </div>
            </div>
        </div>
        @endforeach

    </div>
    <!-- este foreach recorre los usuarios y asi accedo asus propiedades -->
    @foreach ($usuarios as $u)
    @endforeach
    <!-- Modal agregar Roles -->
    <div class="modal fade" id="role" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">Agregar Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.asignarRol') }}" method="post">
                    @csrf
                    <input type="hidden" name="usuario" value="{{ $user }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="rol">Roles </label>
                                <select class="form-select" id="rol" name="roles_id" required v-model="sRol">
                                    @foreach ($roles as $rol)
                                    <option value="{{ $rol->name}}">

                                        {{$rol->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary"
                            href="{{route('users.asignarRol', ['id'=> \Crypt::encryptString($rol->id)]) }}">Asignar
                            Rol</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cajas Usuarios -->
    <div class="modal fade" id="cajas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar cajas a usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert show message" :class="'alert-'+ message.type" usuario="alert"
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
                                    <ul class="list-group shadow" v-if="ListFilter.length > 0" v-for="p in ListFilter" v-bind:key="p.id">
                                        <li class="list-group-item rounded-0 border-0 usuario" @change="setCajasUsuario(p.id)">
                                            <div class="form-check" style="cursor:pointer;">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" :checked="isCheckCajasUser(p.id)">@{{ p.caja }}
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-4" style="max-height: 100%; overflow:auto;">
                            <h5>Cajas agregadas a este usuario</h5>
                            <small v-if="cajas_users.length == 0">Aun no se han agregado cajas este usuario</small>
                            <ul class="list-group" v-for="c in cajas_users" :key="c.id">
                                <li class="list-group-item">
                                    @{{ c.cajas.caja }}
                                    <a class="nav-link float-end text-muted" href="#"
                                        @click="setCajasUsuario(c.cajas_id)"><span class="mdi mdi-delete"></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Modal tipo mantenimientos  Usuarios -->
    <div class="modal fade" id="mantenimientos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar tipo de mantenimientos a <span class="text-uppercase">{{$u->name}}</span> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert show message" :class="'alert-'+ message.type" usuario="alert"
                        v-show="message.message && message.type">
                        <strong>@{{ message.message }}</strong>

                    </div>
                    <div class="row">

                        <div class="col-8 ch-60">

                            <div class="form-inline">
                                <div class="form-group">

                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Buscar..."
                                            v-model="txtBusquedaM" @keyup="getFilterMantenimientos()">

                                        <a href="#" class="input-group-text" id="basic-addon2"
                                            v-show="txtBusquedaM.length > 0 && ListFilterM.length > 0"
                                            @click="clearMantenimientos()">
                                            <span class="mdi mdi-backspace-outline"></span>
                                        </a>
                                    </div>
                                    <ul class="list-group shadow" v-if="ListFilterM.length > 0" v-for="tm in ListFilterM" v-bind:key="tm.id">
                                        <li @change="setTipoMantenimientosUsuario(tm.id)" class="list-group-item rounded-0 border-0 usuario">
                                            <div class="form-check" style="cursor:pointer;">
                                                <label class="form-check-label" >
                                                    <input type="checkbox" class="form-check-input" :checked="isCheckMantenimientos(tm.id)">@{{ tm.mantenimiento }}
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-4" style="max-height: 100%; overflow:auto;">
                            <h5>Tipo de mantenimientos agregados a <span class="text-uppercase">{{$u->name}}</span></h5>
                            <small v-if="tipo_mantenimiento_users.length == 0">Aun no se han agregado mantenimientos a este usuario </small>
                            <ul class="list-group" v-for="m in tipo_mantenimiento_users" :key="m.id">
                                <li @click="setTipoMantenimientosUsuario(m.tipo_mantenimientos_id)" class="list-group-item">@{{ m.tipo_mantenimientos.mantenimiento }}
                                    <a class="nav-link float-end text-muted" href="#"><span class="mdi mdi-delete"></span></a>
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
    el: "#usuarioShow",
    data: {
        /** show usuario*/
        caja: '',
        tipo_mantenimiento: '',
        rol: '',
        role: '',
        sRol: '',
        roles: "{{$roles}}",
        rolesU: "{{$rolesU}}",
        cajasU: "{{$cajasU}}",
        tipo_mantenimientosU: "{{$mantenimientosUsuarios}}",
        user: "{{$user}}",
        usuarios: "{{$usuarios}}",
        cajas_users: [],
        tipo_mantenimiento_users: [],
        message: {},
        txtBusqueda: "",
        ListFilter: [],
        list: [],
        selected: [],
        /**usado para tipo de mantenientos usuarios */
        txtBusquedaM: "",
        ListFilterM: [],
        listm: [],
        selectedM: [],
    },
    methods: {
        /** estan son las funciones para agregar cajas a usuarios */
        /***aqui obtengo los datos de las cajas disponibles */
        getDataCaja() {
            axios.get('{{ route("cajas.index_api") }}')
                .then((rs) => {
                    if (rs.data.list)
                        this.setList(rs.data.list);

                })
        },
        /**aqui creo la caja al usuario */
        setCajasUsuario(id){
            axios.post('{{ route("users.store_apiCaja") }}', {
                "user": this.user,
                "caja": id,
            }).then((rs) => {
                console.log('data cajas users: ',rs);

                if (rs.data) {
                    this.cajas_users = rs.data.cajas;
                    this.setMessage(rs.data.message, rs.data.type);
                }
            })
        },
        isCheckCajasUser(id) {
            /*if (this.cajas_users != null && this.cajas_users.length > 0)
                return this.cajas_users.find(p => p.cajas_id == id) != null ? true : false;*/
            return this.cajas_users.find(p => p.cajas_id == id) != null ? true : false;
        },
        /** aqui obtengo las cajas disponibles*/
        getCajaUsuario() {

            axios.post('{{ route("users.list_cajas") }}', {
                "user": this.user,
            }).then((rs) => {
                if (rs.data.cajas)
                    this.cajas_users = rs.data.cajas;

            })
        },
        /**con este metodo se elimina */
        destroyUsuarioCajas(id) {
            if (confirm("Esta caja se borrara definitivamente. ¿Realmente quieres eliminarla?"))
                axios.post('{{ route("cajas.destroy_api") }}', {
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
            let reg = new RegExp(this.txtBusqueda);
            this.ListFilter = this.list.filter(r => reg.test(r.caja))
        },
        setList(l) {
            this.list = l;
            this.getFilter();
        },
        clear() {
            this.txtBusqueda = "";
            this.getFilter();
        },
        /** aqui finalizan las funciones para agregar cajas a usuarios*/
        /**aqui son las funciones tipo mantenimientos a usuarios*/
        /***aqui obtengo los tipos de mantenimienos disponibles */
        getDataMantenimiento() {
            axios.get('{{ route("tipo_mantenimientos.index_api") }}')
                .then((rs) => {
                    if (rs.data.listm)
                        this.setListMantenimientos(rs.data.listm);
                })
        },
        /**aqui creo la caja al usuario */
        setTipoMantenimientosUsuario(id) {
            axios.post('{{ route("users.store_apiTipoMantenimientos") }}', {
                "user": this.user,
                "tipo_mantenimiento": id,
            }).then((rs) => {
                //console.log('data users mant: ',rs.data.tipo_mantenimientos[0]['tipo_mantenimientos'].id);

                if (rs.data) {
                    this.tipo_mantenimiento_users = rs.data.tipo_mantenimientos;
                    console.log('data users mant: ',this.tipo_mantenimiento_users);

                    this.setMessage(rs.data.message, rs.data.type);
                }
            })
        },
        isCheckMantenimientos(id) {
            /*if (this.tipo_mantenimiento_users != null && this.tipo_mantenimiento_users.length > 0)
                return this.tipo_mantenimiento_users.find(p => p.tipo_mantenimientos_id == id) != null ? true : false;*/
            return this.tipo_mantenimiento_users.find(p => p.tipo_mantenimientos_id == id) != null ? true : false;
        },
        /** aqui obtengo las cajas disponibles*/
        getMantenimientoUsuario() {

            axios.post('{{ route("users.list_tipoMantenimientos") }}', {
                "user": this.user,
            }).then((rs) => {
                if (rs.data.tipo_mantenimientos)
                    this.tipo_mantenimiento_users = rs.data.tipo_mantenimientos;

            })
        },
        /**con este metodo se elimina */
        destroyUsuarioMantenimientos(id) {
            if (confirm(
                    "Este tipo de mantenimiento se borrara definitivamente. ¿Realmente quieres eliminarla?"
                ))
                axios.post('{{ route("tipo_mantenimientos.destroy_api") }}', {
                    "id": id
                })
                .then((rs) => {
                    if (rs.data.listm)
                        this.setListMantenimientos(rs.data.listm);
                    if (rs.data.message)
                        this.setMessage(rs.data.message, rs.data.type);
                }).catch(e => console.log(e));
        },
        setSelectedMantenimientos(tm) {
            this.selectedM = tm;
            this.listm = [];
            this.txtBusquedaM = "";
        },

        getFilterMantenimientos() {
            let reg = new RegExp(this.txtBusquedaM, "i");
            this.ListFilterM = this.listm.filter(r => reg.test(r.mantenimiento))
        },
        setListMantenimientos(tm) {
            this.listm = tm;
            this.getFilterMantenimientos();
        },
        clearMantenimientos() {
            this.txtBusquedaM = "";
            this.getFilterMantenimientos();
        },
    },
    mounted: function() {
        document.onreadystatechange = () => {
            if (document.readyState == "complete") {
                this.getDataCaja();
                this.getDataMantenimiento();
                this.getMantenimientoUsuario();
                this.getCajaUsuario();
            }
        };
        const modalCajas = document.getElementById('cajas');
        modalCajas.addEventListener('hidden.bs.modal', () => {
            location.reload();
        });
        const modalm = document.getElementById('mantenimientos');
        modalm.addEventListener('hidden.bs.modal', () => {
            location.reload();
        });
    },
    computed: {

    }
});
</script>
