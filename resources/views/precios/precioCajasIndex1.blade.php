@extends('layouts.form')

@section('form')
    <style>
        [v-cloak] {
            display: none;
        }

        /*#appPrecioCajas {
            text-transform: uppercase;
        }*/
        .altura-card {
            height: 625px;
        }
        .cursor-pointer {
            cursor: pointer;
        }

        .scrollable-list {
            max-height: 453px;
            overflow-y: auto;
            
            scrollbar-width: thin;/*Ancho de la barra de desplazamiento*/
        }
    </style>

    <div style="height: 620px;" id="appPrecioCajas" v-cloak>
        <div class="row">
            <div class="col-6">
                <div class="card altura-card rounded-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Precios</h5>

                        <div class="mb-3">
                            <label for="cajas" class="form-label">Cajas:</label>
                            <select v-model="cajaSelected" @change="setCaja" class="form-select rounded-5" aria-label="Default select example" id="cajas">
                                <option value="" disabled selected>---Seleccione---</option>
                                <option v-for="caja in cajas" :value="caja">@{{ caja.caja }}</option>
                            </select>
                        </div>

                        {{-- @{{ cajaPrecios }} --}}
                        <div class="mb-3">
                            <ul v-show="cajaPrecios.length > 0" class="list-group rounded-4 scrollable-list text-uppercase"><!--Scrollable-->
                                {{-- <li v-for="pre in precios" :key="pre.id" class="list-group-item cursor-pointer" @click="toggleCheckboxPrecios(pre.id)">
                                    <input class="form-check-input cursor-pointer" type="checkbox" :id="'checkbox-' + pre.id" v-model="pre.chequeado"> @{{ pre.precio }}
                                </li> --}}
                                <li v-for="cp in cajaPrecios" :key="cp.id" style="width: 99%;" @click="setCajaPrecio(cp)" @click="toggleCheckboxPrecios(cp.id)" class="list-group-item cursor-pointer d-flex justify-content-between">
                                    <span>
                                        <input class="form-check-input cursor-pointer" type="checkbox" :id="'checkbox-' + cp.id" checked>
                                         <span class="text-muted">@{{ cp.precios.categorias_precios.categoria }}</span> · 
                                        <b>@{{ cp.precios.detalle }}</b>
                                    </span>
                                    <span class="badge text-bg-success">$ @{{ cp.precios.precio }}</span>
                                </li>
                            </ul>
                            <p v-show="lblMensaje">
                                @{{ lblMensaje }}
                            </p>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card altura-card rounded-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Precios agregados a esta caja · @{{ cajaSelected.caja }}</h5>
                        
                        <ul style="max-height: 540px; overflow-y: auto; scrollbar-width: thin;" class="list-group rounded-4 text-uppercase"><!--Scrollable-->
                            <li v-for="cp in cajaPrecios" :key="cp.id" style="width: 99%;" class="list-group-item d-flex justify-content-between">
                                <span>
                                     <span class="text-muted">@{{ cp.precios.categorias_precios.categoria }}</span> · 
                                    <b>@{{ cp.precios.detalle }}</b>
                                </span>
                                <span>
                                    <span class="badge text-bg-success me-3">$ @{{ cp.precios.precio }}</span>
                                    <span @click="setCajaPrecio(cp)" class="mdi mdi-delete cursor-pointer"></span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- End div-app -->

    <script type="module">
        const app = window.appVue({
            data(){
                return {
                    cajas: @json($cajas),
                    cajaSelected: '',

                    cajaPrecios: [],
                    lblMensaje: null,
                    precioSelected: null,

                    precios: [
                        {id: 5, precio: 'Orden de Pupusas Gourmet + (Soda)', chequeado: true},
                        {id: 6, precio: 'Tequila José Cuervo Blanco', chequeado: false},
                        {id: 7, precio: 'Soda Uva', chequeado: true},
                    ],
                }
            },
            mounted(){
                //console.log('appPrecioCajas Mounted.');

                if(this.cajaPrecios.length <= 0)
                    this.lblMensaje = 'Por favor, seleccione una caja.';
            },
            methods: {
                toggleCheckboxPrecios(precioId){
                    const item = this.precios.find(p => p.id === precioId);

                    if(item){
                        item.chequeado = !item.chequeado;
                    }
                    console.log('Item: ',item);
                },
                setCaja(){
                    //console.log('Caja selected: ',this.cajaSelected);

                    axios.post("{{ route('precios.apiGetCajaPrecios') }}",{
                        cajaId: this.cajaSelected.cid,
                    }).then((r) => {
                        //console.log('r: ',r.data);

                        this.cajaPrecios = []; //Limpiar el array por cada cambio de caja que haya
                        this.lblMensaje = null;//Limpiar mensaje de error si hay nuevos datos

                        if(r.data.status){
                            this.cajaPrecios = r.data.cajaPrecios;
                        }
                        else{
                            this.lblMensaje = r.data.message;
                        }
                    }).catch((e) => {
                        console.log('e: ',e);
                    });
                },
                setCajaPrecio(pc){
                    this.precioSelected = pc;
                    //console.log('Precio selected: ',this.precioSelected);

                    this.updateCajaPrecios();
                },
                updateCajaPrecios(){
                    axios.post("{{ route('precios.updateCajaPrecios') }}",{
                        cajaId: this.cajaSelected.cid,
                        precioId: parseInt(this.precioSelected.precios_id),
                    }).then((r) => {
                        //console.log('r: ',r);

                        this.cajaPrecios = []; //Limpiar el array por cada cambio de caja que haya
                        this.lblMensaje = null;//Limpiar mensaje de error si hay nuevos datos

                        if(r.data.status){
                            this.cajaPrecios = r.data.cajaPrecios;
                        }
                        else{
                            this.lblMensaje = r.data.message;
                        }
                    }).catch((e) => {
                        console.log('e: ',e);
                    });
                },
            },
            computed: {
                //Code...
            },
            watch: {
                //Code...
            },
        });
        app.mount('#appPrecioCajas');
    </script>
@endsection
