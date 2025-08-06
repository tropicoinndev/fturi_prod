<style>
    .ch-60 {
        height: 60vh;
        overflow-x: auto;
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
@can('admin')
    <div id="bodegasShow">
        <div class="alert show message" :class="'alert-'+ message.type" usuario="alert"
            v-show="message.message && message.type">
            <strong>@{{ message.message }}</strong>
        </div>

        <div class="row">
            <div class="col-8 ch-60">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="">Usuarios</label>

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Buscar..." v-model="txtBusqueda">

                            <a href="#" class="input-group-text" id="basic-addon2"
                                v-show="txtBusqueda.length > 0 && listFilter.length > 0" @click="clear">
                                <span class="mdi mdi-backspace-outline"></span>
                            </a>
                        </div>

                        <ul class="list-group shadow" v-if="listFilter.length > 0" v-for="p in listFilter"
                            v-bind:key="p.id">
                            <li class="list-group-item rounded-0 border-0 usuario" @change="apiStoreBodegaUsuario(p.id)">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input"
                                             :checked="isCheck(p.id)">@{{ p.name }}
                                    </label>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

            <div class="col-4" style="max-height: 100%; overflow:auto;">
                <h5>Usuarios agregados a esta bodega</h5>
                <small v-if="arrayBodegasUsuarios.length == 0">Aun no se han agregado usuarios en bodega</small>
                <ul class="list-group" v-for="b in arrayBodegasUsuarios">
                    <li class="list-group-item">
                        @{{ b.relacion_usuarios.name }}
                        <a class="nav-link float-end text-muted" href="#" @click="apiDeleteBodegaUsuario(b.id)">
                            <span class="mdi mdi-delete"></span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
@endcan

<script>
    var app = new Vue({
        el: '#bodegasShow',
        data: {
            txtBusqueda         : '',
            list                : [],

            message             : {},

            arrayUsuarios       : [],
            bodegaId            : "{{ $bodegaid }}",//Viene como atributo en el formulario.
            arrayBodegasUsuarios: []
        },
        methods: {
            apiGetUsuarios(){
                axios.get('{{ route("bodegas.api_get_usuarios") }}')
                    .then((rs) => {
                        if(rs){
                            this.arrayUsuarios = rs.data.usuarios;
                        }
                    })
            },
            apiStoreBodegaUsuario(usuarioId){
                axios.post('{{ route("bodegas.api_store_bodega_usuario") }}',{
                    bodegas_id: this.bodegaId,//id encriptado.
                    users_id  : usuarioId     //id no encriptado.
                })
                    .then((rs) => {
                        const { message, type, userList } = rs.data;

                        this.setMessage(message, type);
                        if(userList.length > 0)
                            this.arrayBodegasUsuarios = userList
                    })
            },
            apiGetBodegasUsuarios(){
                axios.get('{{ route("bodegas.api_get_bodegas_usuarios", ["id" => $bodegaid]) }}')
                    .then((rs) => {
                        this.arrayBodegasUsuarios = rs.data.bodegaUsuarios;
                    })
            },
            apiDeleteBodegaUsuario(id){
                axios.post('{{ route("bodegas.api_delete_bodega_usuario") }}',{
                    id: id,
                })
                    .then((rs) => {
                        const { message, type } = rs.data;

                        this.setMessage(message, type);
                        this.apiGetBodegasUsuarios();
                    })
            },
            apiDeleteUsuario(usuarioId){
                if(confirm('Eliminar usuario definitivamente?')){
                    axios.post('{{ route("bodegas.api_delete_usuario") }}',{
                        id: usuarioId,
                    })
                        .then((rs) => {
                            console.log('Response apiDeleteUsuario: ',rs);
                        })
                }
            },
            isCheck(id){
                if(this.arrayBodegasUsuarios != null && this.arrayBodegasUsuarios.length > 0)
                    return this.arrayBodegasUsuarios.find(p => p.users_id == id) != null ? true : false;
            },
            clear(){
                this.txtBusqueda = '';
                this.listFilter;
            },

            setMessage(m, t){
                this.message = {
                    'message': m,
                    'type'   : t,
                };
                setTimeout(() => {
                    this.message = {};
                }, 2 * 1000)
            },
        },
        mounted: function(){
            document.onreadystatechange = () => {
                if(document.readyState == "complete"){
                    this.apiGetUsuarios();
                    this.apiGetBodegasUsuarios();
                }
            }
        },
        computed: {
            listFilter(){
                let reg = new RegExp(this.txtBusqueda.toLowerCase());
                if(this.arrayUsuarios.length > 0)
                    return this.arrayUsuarios.filter(r => reg.test(r.name.toLowerCase()));
            },
        }
    });
</script>
