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
    <div class="alert show message" :class="'alert-'+ message.type" role="alert"
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
                        <a href="#" class="input-group-text" id="basic-addon2" v-show="ListFilter.length == 0"
                            @click="setPermision()">
                            <span class="mdi mdi-plus"></span>
                        </a>
                        <a href="#" class="input-group-text" id="basic-addon2"
                            v-show="txtBusqueda.length > 0 && ListFilter.length > 0" @click="clear()">
                            <span class="mdi mdi-backspace-outline"></span>
                        </a>
                    </div>
                    <ul class="list-group shadow" v-if="ListFilter.length > 0" v-for="p in ListFilter"
                        v-bind:key="p.id">
                        <li class="list-group-item rounded-0 border-0 permission">
                            <a class="nav-link float-end text-white btn-del" href="#" @click="destroyPermission(p.id)"
                                v-show="!isCheck(p.id)">
                                <span class="mdi mdi-delete"></span>
                                Eliminar
                            </a>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" @change="setPermisionRole(p.id)"
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
            <h5>Permisos agregados</h5>
            <small v-if="permissionRole.length == 0">Aun no se han agregado permisos a este rol</small>
            <ul class="list-group" v-for="permission in this.permissionRole">
                <li class="list-group-item">
                    @{{ permission.permissions.name }}
                    <a class="nav-link float-end text-muted" href="#"
                        @click="setPermisionRole(permission.permission_id)"><span class="mdi mdi-delete"></span></a>
                </li>
            </ul>
        </div>
    </div>

</div>
<script>
    var app = new Vue({
        el:"#roleShow",
        data:{
            txtBusqueda     : "",
            list            : [],
            selected        : [],
            role            : "{{ $role }}",
            ListFilter      : [],
            message         : {},
            permissionRole  : [],
        },
        methods:{

            getData(){
               axios.get('{{ route("permissions.index_api") }}')
               .then((rs) =>{
                    if(rs.data.list)
                        this.setList(rs.data.list);

               })
            },
            setPermisionRole(id){
                axios.post('{{ route("roles.permission_create") }}', {
                    "role":this.role,
                    "permission":id,
                }).then((rs) =>{
                if(rs.data){
                    this.permissionRole = rs.data.permission;
                    this.setMessage(rs.data.message, rs.data.type);
                     console.log(rs.data.permission);
                }


                })
            },
            getPermisionRole(p){
                //Api para permisos_role
                axios.post('{{ route("roles.permission_list") }}', {
                "role":this.role, //ID del rol
                }).then((rs) =>{
                if(rs.data.permission)
                    this.permissionRole = rs.data.permission; //Response de permisos role.

                })
            },
            setPermision(){
                if(this.txtBusqueda.length > 0 && this.ListFilter.length == 0){
                    axios.post('{{ route("permissions.store_api") }}',{"permission":this.txtBusqueda,})
                    .then((rs) =>{
                        if(rs.data.list)
                            this.setList(rs.data.list);

                        if(rs.data.message)
                            this.setMessage(rs.data.message, rs.data.type);
                    }).catch(e => console.log(e));
                }
            },
            destroyPermission(id){
                if(confirm("Este permiso se borrara definitivamente. ¿Realmente quieres eliminarlo?"))
                    axios.post('{{ route("permissions.destroy_api") }}',{ "id":id })
                    .then((rs) =>{
                        if(rs.data.list)
                            this.setList(rs.data.list);
                        if(rs.data.message)
                            this.setMessage(rs.data.message, rs.data.type);
                    }).catch(e => console.log(e));
            },
            setSelected(p){
                this.selected = p;
                this.list = [];
                this.txtBusqueda = "";
            },
            setMessage(m, t){
                this.message = {
                    'message': m,
                    'type': t,
                };
                setTimeout(()=> {
                    this.message = {};
                }, 2 * 1000)
            },
            getFilter(){
                let reg = new RegExp(this.txtBusqueda);
                this.ListFilter = this.list.filter(r => reg.test(r.name))
            },
            setList(l){
                this.list = l;
                this.getFilter();
            },
            clear(){
                this.txtBusqueda = "";
                this.getFilter();
            },
            isCheck(id){
                return this.permissionRole.find(p => p.permission_id == id) != null ? true : false ;
            }
        },
        mounted: function () {
            document.onreadystatechange = () => {
                if (document.readyState == "complete") {
                    this.getData()
                    this.getPermisionRole();
                }
            }
        },
        computed:{

        }
    });
</script>
