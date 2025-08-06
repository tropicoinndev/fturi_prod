@extends('layouts.form')

@section('form')
    <style>
        .list-camas:hover {
            background: #4DB6AC;
            color: #fff;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>

    <div id="appAdminHab">
        <div class="row">
            <div class="col-12">
                <h5>Camas</h5>
            </div>
            <div class="col-3 mb-3">
                <button type="button" class="card p-2 w-100 border-success" data-bs-toggle="modal" data-bs-target="#modalAddCamas">
                    <div class="card-body">
                        <p class="card-text"><span class="mdi mdi-plus"></span> Agregar Camas a habitación</p>
                    </div>
                </button>
            </div>

            <!--Mostrar todas las camas de cada habitación-->
            @foreach($habitacionCamas as $hc)
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('administrar_habitaciones.deleteCama',['id'=>$hc->cid]) }}" class="float-end text-danger h4" title="Eliminar cama">
                                <span class="mdi mdi-close"></span>
                            </a>
                            <b class="card-title">{{ $hc->tipo_camas->tipo_cama }} {{-- · {{ $hc->cantidad }}--}}</b>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <!--Modal agregar camas a habitación-->
        <div class="modal fade" id="modalAddCamas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><span class="mdi mdi-plus"></span> Agregar camas</h1>
                        <button @click="reloadPage" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!--Alerta-->

                        <div class="row">
                            <div class="col-8">
                                <input v-model="txtTipoCamas" type="text" class="form-control mb-3" placeholder="Buscar...">

                                <ul class="list-group shadow">
                                    <li v-for="tc in filterTipoCamas" :key="tc.id" @click="setCama(tc.id)" class="list-group-item border-0 list-camas cursor-pointer">
                                        <input class="form-check-input cursor-pointer" type="checkbox" :checked="isCheckTipoCama(tc.id)"> @{{ tc.tipo_cama }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-4" style="max-height: 100%; overflow:auto;">
                                <h5 class="mb-3">Camas agregadas a esta habitación</h5>

                                <ul v-if="habitacionCamas.length > 0" class="list-group">
                                    <li v-for="hc in habitacionCamas" :key="hc.id" class="list-group-item">
                                        <span>@{{ hc.tipo_camas.tipo_cama }}</span>
                                        <span @click="setCama(hc.tipo_camas_id)" class="mdi mdi-delete cursor-pointer float-end"></span>
                                    </li>
                                </ul>
                                <p v-else>Aún no se han agregado camas a esta habitación.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- End div-app -->

    <script type="module">
        const app = window.appVue({
            data(){
                return {
                    habitacion: @json($habitacion),
                    tipoCamas: @json($tipoCamas),
                    habitacionCamas: @json($habitacionCamas),

                    txtTipoCamas: '',
                }
            },
            mounted(){
                console.log('appAdminHab Mounted.');
            },
            methods: {
                setCama(tipoCamaId){
                    //console.log('Tipo cama id: ',tipoCamaId);
                    //console.log('Habitacion id: ',this.habitacion.id);

                    axios.post("{{ route('administrar_habitaciones.apiStoreCamaHabitacion') }}",{
                        habitacionId: parseInt(this.habitacion.id),
                        tipoCamaId: parseInt(tipoCamaId),
                    }).then((r) => {
                        //console.log('r: ',r);

                        if(r.data.status){
                            this.habitacionCamas = r.data.habitacionCamas;
                        }
                    }).catch((e) => {
                        console.log('e: ',e);
                    });
                },
                isCheckTipoCama(id){
                    //1: Tabla dependiente, 2: Tabla Independiente
                    return this.habitacionCamas.find(p => p.tipo_camas_id == id) != null ? true : false;
                },
                reloadPage(){
                    window.location.reload();
                },
            },
            computed: {
                filterTipoCamas(){
                    let regx = new RegExp((this.txtTipoCamas).toLowerCase());

                    return this.tipoCamas.filter(tc => regx.test(tc.tipo_cama.toLowerCase()));
                },
            },
            watch: {
                //Code...
            },
        });
        app.mount('#appAdminHab');
    </script>
@endsection
