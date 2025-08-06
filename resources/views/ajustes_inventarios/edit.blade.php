@extends('layouts.ajustes_inventarios')

@section('style-ajustes-inventarios')
    <style>
        /*Alerta asincrona*/
        .alert-dismissible {
            /*position: fixed;
            bottom: 200px;
            width: 20%;
            right: 50%;*/
            border-radius: 12px;
            /*z-index: 1000;*/
        }
    </style>
@endsection

@section('ajustes_inventarios_content')
    <div id="appCreateAjustesInventarios">
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="card-title text-uppercase">
                    <span class="mdi mdi-file-document-edit text-success h2"></span>
                    Editar solicitud
                </h3>
                <x-message></x-message>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <!--Alerta asincrona-->
                <div v-if="alerta.m && alerta.t" :class="'alert alert-dismissible fade show alert-'+alerta.t" :style="'border-left: solid 5px #'+borderColorAlert" role="alert">
                    <strong><small>@{{ alerta.m }}</small></strong>
                    <!--Vaciar el objeto de la alerta al hacer click, para evitar error de que la clase no existe-->
                    <button @click="alerta = {}" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                @{{ ajusInventario }}
                <div style="border-radius: 12px;" class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="loteId" class="form-label">Número de lote:</label>
                            <input @keyup="findLote" v-model="numLote" disabled type="number" class="form-control rounded-5" id="loteId" aria-describedby="loteIdHelp" min="1" max="99999" placeholder="Ingrese el número de lote a buscar">
                            <div v-show="lblMessage" id="loteIdHelp" class="form-text text-danger">@{{ lblMessage }}</div>
                        </div>

                        <div v-show="Object.keys(loteEncontrado).length > 0" class="mb-5">
                            <div style="height: 100px; border-radius: 12px;" class="card">
                                <div class="card-body">
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <b>#@{{ loteEncontrado.id }} · @{{ loteEncontrado.bodega }}</b>
                                        </div>
                                        <div class="col-3 text-end">
                                            <small class="text-muted">@{{ loteEncontrado.creacion }}</small>
                                        </div>
                                    </div>

                                    <p style="margin-bottom: -2px;" class="text-uppercase">@{{ loteEncontrado.producto }} · <small class="text-secondary"><b>$ @{{ parseFloat(loteEncontrado.precioCosto).toFixed(2) }}</b></small></p>
                                    <p class="text-uppercase">
                                        Cant. Historial: <span class="text-success me-3"><b>@{{ loteEncontrado.cantHistorial }}</b></span>
                                        Existencia: <span class="text-primary"><b>@{{ loteEncontrado.existencia }}</b></span>
                                    </p>
                                </div>
                            </div>
                            <hr>
                        </div>

                        <div v-show="Object.keys(loteEncontrado).length > 0" class="mb-3">
                            <p style="margin-bottom: 8px;">Acción a realizar:</p>
                            <input @click="accion = '{{ Crypt::encryptString(2) }}'" type="radio" class="btn-check" name="options-base" id="descartar" autocomplete="off" :checked="accion === 2">
                            <label class="btn btn-outline-danger rounded-5 me-3" for="descartar"><span class="mdi mdi-trash-can-outline"></span> Descartar</label>

                            <input @click="accion = '{{ Crypt::encryptString(1) }}'" type="radio" class="btn-check" name="options-base" id="aumentar" autocomplete="off" :checked="accion === 1">
                            <label class="btn btn-outline-success rounded-5" for="aumentar"><span class="mdi mdi-plus"></span> Aumentar</label>
                        </div>
                        
                        <div v-show="Object.keys(loteEncontrado).length > 0" class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad de existencia:</label>
                            <input v-model="cantExistencias" type="number" class="form-control rounded-5" id="cantidad" aria-describedby="cantidadHelp" min="1" max="99999" placeholder="Escriba aqui...">
                            <div id="cantidadHelp" class="form-text">Ingrese la cantidad de existencia a descartar o aumentar para este lote.</div>
                        </div>

                        <div v-show="Object.keys(loteEncontrado).length > 0" class="mb-3">
                            <label for="observacion" class="form-label">Observación de ajuste:</label>
                            <textarea v-model="observacion" class="form-control rounded-4" id="observacion" rows="3" placeholder="Escriba aqui..."></textarea>
                            <div class="form-text">Escriba de manera concisa cual es el motivo de ajuste para este lote, y las cantidades a descartar o aumentar.</div>
                        </div>

                        <div v-show="Object.keys(loteEncontrado).length > 0" class="mb-3">
                            <label for="solicitanteUsersId" class="form-label">Usuario solicitante:</label>
                            <select v-model="solicitanteUsersId" class="form-select rounded-5" id="solicitanteUsersId" aria-label="Default select example">
                                <option :value="ajusteInv.user_solicitante.cid" selected>@{{ ajusteInv.user_solicitante.name }}</option>
                                <option v-for="user in usuarios" :value="user.cid">@{{ user.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div style="border-top: none;" class="card-footer bg-transparent text-end">
                        <button @click="editSolicitud" :disabled="Object.keys(loteEncontrado).length <= 0 || observacion.length < 5 || solicitanteUsersId <= 0 || accion === null" type="button" class="btn btn-primary rounded-5"><span class="mdi mdi-content-save"></span> Guardar solicitud</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-ajustes-inventarios')
    <script type="module">
        let app = window.appVue({
            data(){
                return {
                    ajusteInv: @json($ajusteInv),
                    ajusteExi: @json($ajusteExi),
                    usuarios: @json($usuarios),

                    //Encontrar el lote solicitado
                    numLote: '',
                    lblMessage: null,
                    loteEncontrado: {},//Object

                    //Aumentar o disminuir cantidad
                    cantExistencias: '',
                    accion: null,//Accion de botones: 1 = Aumentar, 2 = Descartar
                    observacion: '',
                    solicitanteUsersId: 0,

                    //Alerta flotante asincrona
                    alerta: {},//Object
                    timerAlert: null,
                    borderColorAlert: null,
                }
            },
            mounted(){
                //console.log('create solicitud ajuste mounted.');
                this.numLote = this.ajusteExi.existencias_id;
                this.cantExistencias = parseInt(this.ajusteExi.cantidad);
                this.accion = parseInt(this.ajusteExi.accion);
                this.observacion = this.ajusteInv.observacion;
                this.solicitanteUsersId = this.ajusteInv.user_solicitante.cid;

                this.findLote();
            },
            methods: {
                findLote(){
                    if(this.numLote != ''){
                        axios.post("{{ route('ajustes_inventarios.findLote') }}",{
                            numLote: parseInt(this.numLote),
                        }).then((r) => {
                            if(r.data.status){
                                this.setAlert(r.data.status, r.data.message);
                                
                                this.loteEncontrado.id = r.data.p.id;
                                this.loteEncontrado.bodega = r.data.p.bodegas.bodega;
                                this.loteEncontrado.producto = r.data.p.productos_existencias.nombre;
                                this.loteEncontrado.precioCosto = r.data.p.precio_costo;
                                this.loteEncontrado.cantHistorial = r.data.p.cantidad_historial;
                                this.loteEncontrado.existencia = r.data.p.existencia;
                                this.loteEncontrado.creacion = r.data.p.creacion;

                                this.lblMessage = null;
                                //console.log('Lote encontrado: ',this.loteEncontrado);
                            }
                            else{
                                this.lblMessage = r.data.message;
                                this.loteEncontrado = [];//Vaciar el objeto si no se encontro un lote por segunda vez
                            }
                        }).catch((e) => {
                            this.setAlert(false, e.response.data.message);
                        });
                    }
                    this.loteEncontrado = [];//Vaciar el objeto si el input del lote esta vacia
                },
                editSolicitud(){
                    if(this.numLote != '' && this.cantExistencias != '' && this.observacion.length >= 5){
                        axios.post("{{ route('ajustes_inventarios.editSolicitud') }}",{
                            loteId: parseInt(this.loteEncontrado.id),
                            cantExistencias: parseInt(this.cantExistencias),
                            accion: this.accion,
                            observacion: this.observacion,
                            solicitanteUsersId: this.solicitanteUsersId,
                        }).then((r) => {
                            if(r.data.status){
                                this.setAlert(r.data.status, r.data.message);

                                this.numLote = '';
                                this.loteEncontrado = [];
                                this.cantExistencias = '';
                                this.accion = null,
                                this.observacion = '';
                                this.solicitanteUsersId = 0;
                            }
                            else{
                                this.setAlert(false, r.data.message);
                            }
                        }).catch((e) => {
                            this.setAlert(false, e.response.data.message);
                        });
                    }
                },
                setAlert(s, m){//Recibe como parámetro el estado y el mensaje que viene del Backend
                        //Cancela el timer anterior si existe
                        if(this.timerAlert)
                            clearTimeout(this.timerAlert);

                        if(s){
                            this.alerta.t = 'success';
                            this.borderColorAlert = '2eb96a97';
                        }
                        else{
                            this.alerta.t = 'danger';
                            this.borderColorAlert = 'fbbbbb';
                        }

                        this.alerta.m = m;

                        //Se establece un nuevo timer
                        this.timerAlert = setTimeout(() => {
                            this.alerta = {};//Vaciar el objeto cuando el timer se haya agotado
                        }, 10 * 1000);
                    },
            },
            computed: {
                //Code...
            },
            watch: {
                //Code...
            },
        });
        app.mount('#appCreateAjustesInventarios');
    </script>
@endsection
