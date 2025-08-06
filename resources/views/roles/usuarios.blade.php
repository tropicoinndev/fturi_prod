@extends('layouts.form')

@section('form')
    <style>
        .ch-60 {
            height: 60vh;
            overflow-x: auto;
        }

        .permission:hover {
            background: #4DB6AC;
            color: #fff;
        }

        .btn-del {
            display: none;
        }

        .permission:hover .btn-del {
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
    <div id="roleShow">
        <div class="alert show message" :class="'alert-' + message.type" role="alert"
            v-show="message.message && message.type">
            <strong>@{{ message.message }}</strong>
        </div>
        <div class="row">

            <div class="col-8 ch-60">

                <div class="form-inline">
                    <div class="form-group">
                        <label for="">Permisos</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Buscar..." v-model="txtBusqueda"
                                @keyup="getFilter()" @keyup.enter="setPermision()">

                            <a href="#" class="input-group-text" id="basic-addon2"
                                v-show="txtBusqueda.length > 0 && ListFilter.length > 0" @click="clear()">
                                <span class="mdi mdi-backspace-outline"></span>
                            </a>
                        </div>
                        <ul class="list-group shadow" v-if="ListFilter.length > 0" v-for="p in ListFilter"
                            v-bind:key="p.id">
                            <li class="list-group-item rounded-0 border-0 permission">

                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" @change="setUserRole(p.cid)"
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
                <h5>Usuarios agregados</h5>
                <small v-if="userRole.length == 0">Aun no se han agregado usuarios a este rol</small>
                <ul class="list-group" v-for="p in this.userRole">
                    <li class="list-group-item">
                        @{{ p.users.name }}
                        <a class="nav-link float-end text-muted" href="#" @click="setUserRole(p.users.cid)"><span
                                class="mdi mdi-delete"></span></a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <script>
        var app = new Vue({
            el: "#roleShow",
            data: {
                txtBusqueda: "",
                list: [],
                selected: [],
                role: "{{ Crypt::encryptString($p->id) }}",
                ListFilter: [],
                message: {},
                userRole: [],
            },
            methods: {

                getData() {
                    axios.get('{{ route('users.api_usuario') }}')
                        .then((rs) => {
                            if (rs.data.list)
                                this.setList(rs.data.list);
                        })
                },
                setUserRole(id) {
                    axios.post('{{ route('roles.set_usuarios_api') }}', {
                        "role_id": this.role,
                        "user_id": id,
                    }).then((rs) => {
                        if (rs.data) {

                            this.userRole = rs.data.list;
                            this.setMessage(rs.data.message, rs.data.type);

                        }


                    })
                },
                getUsersRole(p) {

                    axios.post('{{ route('roles.usuarios_api') }}', {
                        "role": this.role,
                    }).then((rs) => {
                        if (rs.data.list)
                            this.userRole = rs.data.list;

                    })
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
                    return this.userRole.find(p => p.model_id == id) != null ? true : false;
                }
            },
            mounted: function() {
                document.onreadystatechange = () => {
                    if (document.readyState == "complete") {
                        this.getData()
                        this.getUsersRole();
                    }
                }
            },
            computed: {

            }
        });
    </script>
@endsection
