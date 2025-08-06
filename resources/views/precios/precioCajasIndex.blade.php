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
            max-height: 545px;
            overflow-y: auto;
            
            scrollbar-width: thin;/*Ancho de la barra de desplazamiento*/
        }
    </style>

    <div style="height: 620px;" id="appPrecioCajas" v-cloak>
        <div class="row">
            <div class="col-6">
                <div class="card altura-card rounded-4">
                    <div class="card-body">
                        {{-- <h5 class="card-title mb-4">Listado de precios <small>(@{{ filterPrecios.length }})</small></h5> --}}
                        <div class="mb-3">
                            {{-- @{{ precios }} --}}
                            <label for="cajas" class="form-label">Agregar precios a caja de:</label>
                            <select v-model="cajaSelected" @change="setCaja" class="form-select rounded-5" aria-label="Default select example" id="cajas">
                                <option value="" disabled selected>---Seleccione---</option>
                                <option v-for="caja in cajas" :value="caja">@{{ caja.caja }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="precios" class="form-label">Precios:</label>
                            <input v-model="txtSearch" type="text" class="form-control rounded-5" placeholder="Escriba aqui...">
                        </div>
                        
                        <ul style="max-height: 420px; overflow-y: auto; scrollbar-width: thin;" class="list-group rounded-4 text-uppercase"><!--Scrollable-->
                            <li v-for="pre in filterPrecios" :key="pre.id" style="width: 99%;" @click="setCajaPrecio(pre)" @click="toggleCheckboxPrecios(pre.id)" class="list-group-item cursor-pointer">
                                <div class="row align-items-center">
                                    <div class="col-10 text-truncate">
                                        <input class="form-check-input cursor-pointer" type="checkbox" :id="'checkbox-' + pre.id" :checked="isChecked(pre.id)">
                                         <span class="text-muted">@{{ pre.categorias_precios.categoria }}</span> · 
                                        <b :class="!pre.estado ? 'text-danger' : ''">@{{ pre.detalle }}</b>
                                    </div>
                                    <div class="col-2 text-end">
                                        <span class="badge text-bg-success">$ @{{ pre.precio }}</span>
                                    </div>

                                    <div class="col-12">
                                        <small class="ms-4">@{{ pre.constante ? 'Constante: Si' : 'Constante: No · '+pre.fecha_inicio+' - '+pre.fecha_final }}</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card altura-card rounded-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Precios agregados a esta caja: @{{ cajaSelected.caja }}</h5>

                        {{-- @{{ cajaPrecios }} --}}
                        <div class="mb-3">
                            <ul v-show="cajaPrecios.length > 0" class="list-group rounded-4 scrollable-list text-uppercase"><!--Scrollable-->
                                <li v-for="cp in cajaPrecios" :key="cp.id" style="width: 99%;" class="list-group-item d-flex justify-content-between">
                                    <span>
                                         <span class="text-muted">@{{ cp.precios.categorias_precios.categoria }}</span> · 
                                        <b>@{{ cp.precios.detalle }}</b>
                                    </span>
                                    <span>
                                        <span class="badge text-bg-success me-3">$ @{{ cp.precios.precio }}</span>
                                        <span @click="setCajaPrecio(cp.precios)" class="mdi mdi-delete cursor-pointer"></span>
                                    </span>
                                </li>
                            </ul>
                            <p v-show="lblMensaje">
                                @{{ lblMensaje }}
                            </p>
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
                    precios: @json($precios),
                    txtSearch: '',

                    cajas: @json($cajas),
                    cajaSelected: '',

                    cajaPrecios: [],
                    lblMensaje: null,
                    precioSelected: null,
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
                    // Lógica para añadir o quitar el item de cajaPrecios
                    if (item) {
                        const index = this.cajaPrecios.findIndex(cp => cp.precios_id === precioId);
                        if (index > -1) {
                            this.cajaPrecios.splice(index, 1);
                        } else {
                            this.cajaPrecios.push(item);
                        }
                    }
                },
                isChecked(precioId) {
                    return this.cajaPrecios.some(cp => cp.precios_id === precioId);
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
                        //console.log('e: ',e);
                    });
                },
                setCajaPrecio(pc){
                    if(this.cajaSelected === ''){
                        this.lblMensaje = 'Por favor, seleccione una caja.';
                        alert('Antes de agregar un precio, debe seleccionar una caja.');
                    }
                    else{
                        this.lblMensaje = '';

                        this.precioSelected = pc;
                        //console.log('Precio selected: ',this.precioSelected);

                        this.updateCajaPrecios();
                    }
                },
                updateCajaPrecios(){
                    axios.post("{{ route('precios.updateCajaPrecios') }}",{
                        cajaId: this.cajaSelected.cid,
                        precioId: parseInt(this.precioSelected.id),
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
                        //console.log('e: ',e);
                    });
                },
            },
            computed: {
                filterPrecios(){
                    let regx = new RegExp((this.txtSearch).toLowerCase());

                    return this.precios.filter(c => regx.test(c.detalle.toLowerCase()));
                },
            },
            watch: {
                //Code...
            },
        });
        app.mount('#appPrecioCajas');
    </script>
@endsection
