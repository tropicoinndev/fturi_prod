@extends('layouts.form')

@section('form')
    <style>
        .color-rojo {
            color: #b92e2e;
        }

        .fs-12pt{
            font-size: 12pt;
        }
        .fs-11pt{
            font-size: 11pt;
        }

        .card-correlativos:hover {
            background: #4DB6AC;
            color: #fff;
            cursor: pointer;
        }

        /*Alerta flotante*/
        .alert-dismissible {
            position: fixed;
            bottom: 80px;
            width: 40%;
            right: 6%;
            border-radius: 12px;
            z-index: 1000;
        }
    </style>

    <div id="appCorrelativoSucursals" class="user-select-none">

        <!--Alerta flotante asincrona-->
        <div v-if="alerta.m && alerta.t" :class="'alert alert-dismissible fade show alert-'+alerta.t" :style="'border-left: solid 5px #'+borderColorAlert" role="alert">
            <strong><small>@{{ alerta.m }}</small></strong>
            <!--Vaciar el objeto de la alerta al hacer click, para evitar error de que la clase no existe-->
            <button @click="alerta = {}" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!--Informacion de la sucursal-->
        <div v-if="correlativoSucursals.length > 0" class="row mt-3 mb-5">
            <div class="col-12"><h4>Informacion</h4></div>

            <div class="col-2 text-muted">Sucursal:</div>
            <div class="col-10">{{ $p->sucursal }}</div>

            <div class="col-2 text-muted">Giro:</div>
            <div class="col-10">{{ $p->giro }}</div>

            <div class="col-2 text-muted">Dirección:</div>
            <div class="col-10">{{ $p->direccion }}</div>

            <div class="col-2 text-muted">Teléfono:</div>
            <div class="col-10">{{ $p->telefono }}</div>

            <div class="col-2 text-muted">Correo:</div>
            <div class="col-10">{{ $p->correo }}</div>

            <div class="col-2 text-muted">NRC:</div>
            <div class="col-10">{{ $p->nrc }}</div>

            <div class="col-2 text-muted">NIT:</div>
            <div class="col-10">{{ $p->nit }}</div>

            <div class="col-2 text-muted">Cod. Establ.:</div>
            <div class="col-10">{{ $p->codigo_establecimiento }}</div>

            <div class="col-2 text-muted">IVA:</div>
            <div class="col-10">{{ $p->iva ? 'SI' : 'NO' }}</div>

            <div class="col-2 text-muted">Matriz:</div>
            <div class="col-10">{{ $p->matriz ? 'SI' : 'NO' }}</div>
        </div>
        
        <!--listado de correlativos-->
        <div class="row">
            <div class="col-3">
                <button type="button" class="card border border-1 border-success" data-bs-toggle="modal" data-bs-target="#modalAgregarDte">
                    <div class="card-body">
                        <p class="card-text fs-4"><span class="mdi mdi-plus"></span> Agregar correlativos</p>
                    </div>
                </button>
            </div>

            <!--v-for-->
            <div v-for="cs in correlativoSucursals" :key="cs.id" class="col-3 mb-3"><!--Margen entre cada card-->
                <div class="card card-correlativos border border-1 border-secondary">
                    <div style="height: 210px;" class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <span style="font-size: 9pt; color: blue;" v-if="cs.tipo_dte === 1"><b>CONSUMIDOR FINAL</b></span>
                                <span style="font-size: 9pt; color: blue;" v-if="cs.tipo_dte === 3"><b>CRÉDITO FISCAL</b></span>
                                <span style="font-size: 9pt; color: blue;" v-if="cs.tipo_dte === 4"><b>NOTA DE REMISIÓN</b></span>
                                <span style="font-size: 9pt; color: blue;" v-if="cs.tipo_dte === 5"><b>NOTA DE CRÉDITO</b></span>
                                <span style="font-size: 9pt; color: blue;" v-if="cs.tipo_dte === 6"><b>NOTA DE DÉBITO</b></span>
                                <span style="font-size: 9pt; color: blue;" v-if="cs.tipo_dte === 7"><b>COMPROBANTE DE RETENCIÓN</b></span>
                                <span style="font-size: 9pt; color: blue;" v-if="cs.tipo_dte === 8"><b>COMPROBANTE DE LIQUIDACIÓN</b></span>
                                <span style="font-size: 9pt; color: blue;" v-if="cs.tipo_dte === 9"><b>DOCUMENTO CONTABLE DE LIQUIDACIÓN</b></span>
                                <span style="font-size: 9pt; color: blue;" v-if="cs.tipo_dte === 11"><b>FACTURA DE EXPORTACIÓN</b></span>
                                <span style="font-size: 9pt; color: blue;" v-if="cs.tipo_dte === 14"><b>FACTURA DE SUJETO EXCLUIDO</b></span>
                                <span style="font-size: 9pt; color: blue;" v-if="cs.tipo_dte === 15"><b>COMPROBANTE DE DONACIÓN</b></span>
                                <span style="font-size: 9pt; color: blue;" v-if="cs.tipo_dte === null"><b>---</b></span>
                            </div>

                            <div class="col-6 text-end">
                                <div style="margin-top: -8px; margin-left: -10px;" class="btn-group" role="group" aria-label="Basic example">
                                    <button @click="edit(cs)" type="button" class="btn btn-sm" title="Editar Correlativo Actual" data-bs-toggle="modal" data-bs-target="#modalEditarCorrelativo">
                                        <span class="mdi mdi-pencil fs-5"></span>
                                    </button>
                                    <button @click="restart(cs.cid)" v-if="cs.actual !== 0" type="button" class="btn btn-sm" title="Reiniciar Correlativo Actual">
                                        <span class="mdi mdi-reload fs-5"></span>
                                    </button>
                                    <button @click="status(cs.cid)" type="button" class="btn btn-sm" :title="cs.estado ? 'Desactivar Correlativo Actual' : 'Activar Correlativo Actual'">
                                        <span :class="cs.estado ? 'mdi mdi-toggle-switch text-success' : 'mdi mdi-toggle-switch-off text-danger'" class="fs-5"></span>
                                    </button>

                                    <button @click="destroy(cs.cid)" type="button" class="btn btn-sm" title="Eliminar Correlativo">
                                        <span class="mdi mdi-close text-danger fs-5"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!--Info de correlativos-->
                        <hr style="margin-top: 1px;">
                        <div class="row">
                            <div class="col-5 fs-12pt"><b>Inicio:</b></div>
                            <div class="col-7">@{{ cs.inicio }}</div>

                            <div class="col-5 fs-12pt"><b>Actual:</b></div>
                            <div class="col-7">@{{ cs.actual }}</div>

                            <div class="col-5 fs-12pt"><b>Final:</b></div>
                            <div class="col-7">@{{ cs.final }}</div>
                        </div>

                        <hr style="margin-top: 1px;">
                        <p :class="'text-center fs-4 '+ (new Date().getFullYear() > cs.year ? 'text-danger' : '')"><span class="mdi mdi-calendar-month"></span> @{{ cs.year }}</p>
                    </div>
                </div>
            </div>
        </div>



        <!--Modal agregar DTE actual-->
        <div class="modal fade" id="modalAgregarDte" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><span class="mdi mdi-plus"></span> Agregar DTE · <small>{{ $p->sucursal }}</small></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tipoDte" class="form-label fs-6">Tipo de DTE:</label>
                            <select v-model="tipoDte" class="form-select" id="tipoDte" aria-label="Default select example">
                                <option selected disabled value="null">---Seleccione---</option>
                                <option :value="'{{ \Crypt::encryptString(1) }}'">CONSUMIDOR FINAL</option>
                                <option :value="'{{ \Crypt::encryptString(3) }}'">CRÉDITO FISCAL</option>
                                <option :value="'{{ \Crypt::encryptString(4) }}'">NOTA DE REMISIÓN</option>
                                <option :value="'{{ \Crypt::encryptString(5) }}'">NOTA DE CRÉDITO</option>
                                <option :value="'{{ \Crypt::encryptString(6) }}'">NOTA DE DÉBITO</option>
                                <option :value="'{{ \Crypt::encryptString(7) }}'">COMPROBANTE DE RETENCIÓN</option>
                                <option :value="'{{ \Crypt::encryptString(8) }}'">COMPROBANTE DE LIQUIDACIÓN</option>
                                <option :value="'{{ \Crypt::encryptString(9) }}'">DOCUMENTO CONTABLE DE LIQUIDACIÓN</option>
                                <option :value="'{{ \Crypt::encryptString(11) }}'">FACTURAS DE EXPORTACIÓN</option>
                                <option :value="'{{ \Crypt::encryptString(14) }}'">FACTURA DE SUJETO EXCLUIDO</option>
                                <option :value="'{{ \Crypt::encryptString(15) }}'">COMPROBANTE DE DONACIÓN</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cerrar</button>
                        <button @click="store" :disabled="!tipoDte" type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-floppy"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </div>



        <!--Modal editar correlativo actual-->
        <div class="modal fade" id="modalEditarCorrelativo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><span class="mdi mdi-pencil"></span> Editar Correlativo · <small>{{ $p->sucursal }}</small></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="color-rojo">- Solo se permiten números.</p>

                        <div class="mb-3">
                            <label for="actual" class="form-label fs-6">Correlativo Actual:</label>
                            <input v-model="txtActual" @input="validateInput('txtActual', $event)" type="text" class="form-control" id="actual" aria-describedby="actualHelp" placeholder="0" required maxlength="15" pattern="[0-9]{1,15}" inputmode="numeric" autocomplete="off">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cerrar</button>
                        <button @click="update" type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-floppy"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- End div-app -->

    <script type="module">
        let app = window.appVue({
            data(){
                return {
                    //data
                    sucursal: @json($p),
                    correlativoSucursals: @json($correlativoSucursals),

                    csSelected: {},

                    //Modal crear
                    tipoDte: null,

                    //Modal editar
                    txtActual: 0,

                    //Alerta flotante
                    alerta: {},//Object
                    timerAlert: null,
                    borderColorAlert: null,
                }
            },
            beforeUnMounted(){
                //Code...
            },
            mounted(){
                //console.log('Correlativo Sucursals Mounted.');

                //Colocar en el input de la modal el correlativo actual
                if(this.correlativoSucursals.length > 0)
                    this.txtActual = parseInt(this.correlativoSucursals[0].actual);
            },
            methods: {
                getData(){
                    axios.post("{{ route('correlativo_sucursal.getData') }}",{
                        sucursalId: this.sucursal.cid,
                    }).then((r) => {
                        if(r.data.status)
                            this.correlativoSucursals = r.data.correlativoSucursals;
                    }).catch((e) => {
                        this.setAlert(false, e.response.data.message);
                    });
                },
                store(){
                    axios.post("{{ route('correlativo_sucursal.store') }}",{
                        sucursalId: this.sucursal.cid,
                        tipoDte: this.tipoDte,
                    }).then((r) => {
                        this.setAlert(r.data.status, r.data.message);
                        this.getData();
                    }).catch((e) => {
                        this.setAlert(false, e.response.data.message);
                    });
                },
                edit(cs){
                    this.csSelected = cs;
                    this.txtActual = parseInt(this.csSelected.actual);
                },
                update(){
                    //if(this.txtActual.length > 0){
                        axios.post("{{ route('correlativo_sucursal.update') }}",{
                            id: this.csSelected.cid,
                            actual: parseInt(this.txtActual),
                            sucursalId: this.csSelected.sucursales.cid,
                            tipoDte: parseInt(this.csSelected.tipo_dte),
                        }).then((r) => {
                            this.setAlert(r.data.status, r.data.message);
                            this.getData();
                        }).catch((e) => {
                            console.log('e: ',e);
                            this.setAlert(false, e.response.data.message);
                        });
                    //}
                },
                restart(id){
                    axios.post("{{ route('correlativo_sucursal.restart') }}",{
                        id: id,
                    }).then((r) => {
                        this.setAlert(r.data.status, r.data.message);
                        this.getData();
                    }).catch((e) => {
                        this.setAlert(false, e.response.data.message);
                    });
                },
                status(id){
                    axios.post("{{ route('correlativo_sucursal.status') }}",{
                        id: id,
                    }).then((r) => {
                        this.setAlert(r.data.status, r.data.message);
                        this.getData();
                    }).catch((e) => {
                        this.setAlert(false, e.response.data.message);
                    });
                },
                destroy(id){
                    if(confirm('Esta seguro de eliminar este correlativo?')){
                        axios.post("{{ route('correlativo_sucursal.delete') }}",{
                            id: id,
                        }).then((r) => {
                            this.setAlert(r.data.status, r.data.message);
                            this.getData();

                            //if(r.data.status)
                                //this.correlativoSucursals = r.data.correlativoSucursals;
                        }).catch((e) => {
                            this.setAlert(false, e.response.data.message);
                        });
                    }
                },
                validateInput(inputName, event){//Recibe como parámetro el v-model de cada input y el event
                    let input = event.target.value;

                    input = input.replace(/\s/g, '');//Eliminar espacios en blanco
                    input = input.replace(/\D/g, '');//Eliminar carácteres que no sean números

                    this[inputName] = input;//Actualizar el valor del input correspondiente
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
                /*closeModal(nameModal){//Recibe como parámetro el nombre de la modal
                    let modal = document.getElementById(nameModal);

                    if(modal){
                        modal.classList.remove('show');
                        modal.style.display = 'none';
                        document.body.classList.remove('modal-open');
                        let modalBackdrop = document.getElementsByClassName('modal-backdrop');

                        if(modalBackdrop[0])
                            document.body.removeChild(modalBackdrop[0]);
                    }
                },*/
            },
            computed: {
                //Code...
            },
            watch: {
                //Code...
            },
        });
        app.mount('#appCorrelativoSucursals');
    </script>
@endsection
