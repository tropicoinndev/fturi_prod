<div id="{{ $id }}">
    <input type="hidden" name="{{ $id }}" v-model="selected.id ?? 0">
    <div class="form-group" v-if="selected.length == 0">
        <label for="">{{ $label ?? 'Usuarios' }}</label>
        <input type="text" class="form-control" placeholder="Usuarios..." v-model="cajas_users" @keyup="getData()">
        <ul class="list-group" v-if="list.length > 0" v-for="p in list" v-bind:key="p.id">
            <li class="list-group-item" @click="setSelected(p)">@{{ p[show] }}</li>
        </ul>
    </div>
    <div v-if="selected.id">
        <h5>
            <span class="badge bg-secondary">
                @{{ selected[show] }}
                <span class="mdi mdi-close" @click="selected = []"></span>
            </span>
        </h5>
    </div>
</div>
<script>
    var app = new Vue({
        el:"#{{ $id }}",
        data:{
            Usuarios : "",
            show:'{{ $showname }}',
            list:[],
            selected: []
        },
        methods:{
            getData(){
               axios.post('{{ $route }}', {
                "usuarios":this.cajas_users,
               }).then((us) =>{
                    if(us.data.list)
                        this.list = us.data.list;

               })
            },
            setSelected(p){
                this.selected = p;
                this.list = [];
                this.usuarios = "";
            }
        }
    });
</script>
