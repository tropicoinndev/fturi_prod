<style>
    .ch-60 {
        height: 60vh;
        overflow-x: auto;
    }

    .precio:hover {
        background: #4DB6AC;
        color: #fff;
    }

    .precioinf {
        background: #4DB6AC;
        color: #fff;
    }

    .precio .card,
    .precio1 .card {
        height: 85px;
        overflow-x: auto;
        margin-bottom: 5.5%;
    }

    .pointer {
        cursor: pointer;
    }

    .btn-del {
        display: none;
    }

    .precio:hover .btn-del {
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
        height: 70px;
    }

    .dev {
        left: 39%;
        transform: translateX(-1%);
        bottom: 6%;
    }
</style>
<div id="precioShow">
    <!-- inicia seccion de detalles de impuesto de el precio -->

    <div class="col-12 mb-4">
        @foreach ($precios as $pr)
        @endforeach
        <div class="col-12 mb-3">
            <h5>Detalle del precio</h5>
        </div>
        @if ($pr->estado)
            @can('precios.index')
                <button type="button" class="btn bg-light border border-1 border-dark dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Impuestos
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li>
                        <a class="dropdown-item" href="{{ route('precios.impuestos',['id'=>\Crypt::encryptString($pr->id),'tipo'=>\Crypt::encryptString(1),]) }}">
                            @if($pr->constante == 0)
                                <span class="mdi mdi-account-badge text-success h5"></span> Precio constante
                            @endif
                        </a>
                        <a class="dropdown-item" href="{{ route('precios.impuestos',['id'=>\Crypt::encryptString($pr->id),'tipo'=>\Crypt::encryptString(4),]) }}" data-bs-toggle="modal" data-bs-target="#fechas">
                            @if($pr->constante)
                                <span class="mdi mdi-account-badge text-danger h5"></span> Precio por temporada
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('precios.impuestos',['id'=>\Crypt::encryptString($pr->id),'tipo'=>\Crypt::encryptString(2),]) }}">
                            @if($pr->iva)
                                <span class="mdi mdi-account-badge text-success h5"></span> Eliminar IVA
                            @else
                                <span class="mdi mdi-account-badge text-danger h5"></span> Agregar IVA
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('precios.impuestos',['id'=>\Crypt::encryptString($pr->id),'tipo'=>\Crypt::encryptString(3),]) }}">
                            @if ($pr->propina)
                                <span class="mdi mdi-account-badge text-success h5"></span> Eliminar propina
                            @else
                                <span class="mdi mdi-account-badge text-danger h5"></span> Agregar propina
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('precios.impuestos',['id'=>\Crypt::encryptString($pr->id),'tipo'=>\Crypt::encryptString(4),]) }}">
                            @if($pr->advalorem)
                                <span class="mdi mdi-account-badge text-success h5"></span> Eliminar Ad-Valorem
                            @endif
                        </a>
                        <a class="dropdown-item" href="{{ route('precios.impuestos',['id'=>\Crypt::encryptString($pr->id),'tipo'=>\Crypt::encryptString(4),]) }}"data-bs-toggle="modal" data-bs-target="#precioSugerido">
                            @if($pr->advalorem == 0)
                                <span class="mdi mdi-account-badge text-danger h5"></span> Agregar Ad-Valorem
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('precios.impuestos',['id'=>\Crypt::encryptString($pr->id),'tipo'=>\Crypt::encryptString(5),]) }}">
                            @if ($pr->descuento)
                                <span class="mdi mdi-account-badge text-success h5"></span> Eliminar descuento
                            @else
                                <span class="mdi mdi-account-badge text-danger h5"></span> Agregar descuento
                            @endif
                        </a>
                    </li>
                @endif
            </ul>
        @endcan

        @if (isset($pr) && isset($pr->precio) && !$pr->estado)
            @can('precios.index')
                <a class="btn btn-outline-success"
                    href="{{ route('precios.statusPrecios', ['id' => \Crypt::encryptString($pr->id)]) }}" role="button">
                    <span class="mdi mdi-account-badge"></span>
                    Activar precio
                </a>
            @endcan
        @endif
        @if ($pr->estado)
            @can('precios.index')
                <a class="btn btn-outline-danger"
                    href="{{ route('precios.statusPrecios', ['id' => \Crypt::encryptString($pr->id)]) }}" role="button">
                    <span class="mdi mdi-account-badge-outline"></span>
                    Desactivar precio
                </a>
            @endcan
        @endif

    </div>
    <div class="col-3 position-relative   top-30 end-0  text-center dev">
        <div class="precioinf  text-white position-absolute mt-30">
            <b><span class="icon icon-info"></span> Informacion</b>
            <hr>
            <div class="row">
                <div class="col-6 text-right">
                    Precio Neto:
                </div>
                <div class="col-6" id="txtprecio">
                    @{{ txtprecio }}
                </div>
                <div class="col-6 text-right">
                    IVA:
                </div>
                <div class="col-6" id="txtiva">
                    @{{ txtiva }}
                </div>
                <div class="col-6 text-right">
                    Ad-Valorem:
                </div>
                <div class="col-6" id="txtvalorem">
                    @{{ txtvalorem }}
                </div>
                @if ($pr->advalorem)
                        <div class="col-6 text-right">
                            Precio sugerido:
                        </div>
                        <div class="col-6">
                            @{{ precio_sugerido }}
                        </div>

                @endif
                <div class="col-6 text-right">
                    Propina:
                </div>
                <div class="col-6" id="txtpropina">
                    @{{ txtpropina }}
                </div>
                <div class="col-6 text-right ">
                    Precio total:
                </div>
                <div class="col-6 text-right " id="txttotal">
                    @{{ txttotal }}
                </div>
            </div>
        </div>
    </div>

    @if (isset($pr->id))
        <div class="row mb-5 ">
            <div class="col-12">
                <h4>Informacion</h4>
            </div>
            <div class="col-2 text-muted">
                Precio:
            </div>
            <div class="col-10">
                {{ $pr->constante ? 'Precio constante ' : 'Precio por temporada ' }}
            </div>

            <div class="col-2 text-muted">
                Incluye IVA:
            </div>
            <div class="col-10">
                {{ $pr->iva ? 'Incluye iva' : 'No incluye iva' }}
            </div>
            <div class="col-2 text-muted">
                Propina:
            </div>
            <div class="col-10">
                {{ $pr->propina ? 'Incluye propina' : 'No incluye propina' }}
            </div>
            <div class="col-2 text-muted">
                 Ad-Valorem:
            </div>
            <div class="col-10">
                {{ $pr->advalorem ? 'Incluye advalorem' : 'No incluye advalorem' }}
            </div>
            <div class="col-2 text-muted">
                Descuento:
            </div>
            <div class="col-10">
                {{ $pr->descuento ? 'Permite descuento' : 'No permite descuento' }}
            </div>

        </div>
    @endif
    <!--finaliza la seccion para mostrar los calculos de los impuestos en el precio -->

    <div class="row mb-4">
        <div class="col-12">
            @can('cajas.create')
            <h5>Cajas
                <a href="{{ route('cajas.create') }}" class="card-link" target="_blank">
                    <span class="mdi mdi-plus"></span>
                    Agregar</a>
            </h5>
            @endcan
        </div>
        @can('caja_precios.create')
            <div class="col-3">
                <button type="button" class="card p-2 w-100 border border-1 border-success" data-bs-toggle="modal"
                    data-bs-target="#cajas">
                    <div class="card-body body_cajas">
                        <p class="card-text">
                            <span class="mdi mdi-plus"></span> Agregar cajas a precio
                        </p>
                    </div>
                </button>
            </div>
        @endcan

        <!-- mostrar todas las cajas agregadas a precio -->
        @foreach ($cajasP as $caja)
            <div class="col-3 precio1">
                <div class="card ">
                    @can('caja_precios.index')
                    <div class="card-body ">
                        <a href="{{ route('caja_precios.confirm', ['id' => \Crypt::encryptString($caja->id)]) }}"
                            class="float-end
                            text-danger h4" title="Eliminar Caja">
                            <span class="mdi mdi-close"></span>
                        </a>
                        <b class="card-title text-uppercase">{{ $caja->cajas->caja }}</b>
                        <p class="card-text">{{ $caja->codigo }}</p>

                    </div>
                    @endcan
                </div>
            </div>
        @endforeach

    </div>
    <!-- se muestran los productos agregados a este precio  -->
    <div class="row mb-4">
        <div class="col-12">

            <h5>Productos agregados a este precio</h5>
        </div>


        <!-- mostrar todas las cajas agregadas a usuarios -->
        @if ($preciosP->isEmpty())
            <p>Aún no se han agregado productos a este precio.</p>
        @else
            @foreach ($preciosP as $p)
                <div class="col-3 precio1">
                    <div class="card">
                        <div class="card-body">
                            <b class="card-title text-uppercase">{{ $p->productos->nombre }}</b>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </div>


    <!-- Modal cajas a precio -->
    <div class="modal fade" id="cajas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar cajas a precio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
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
                                        <li class="list-group-item rounded-0 border-0 precio">
                                            <div class="form-check" style="cursor:pointer;">
                                                <label class="form-check-label">
                                                    <input @click="setCajasPrecio(p.id)" type="checkbox" class="form-check-input" :checked="isCheck(p.id)">
                                                    @{{ p.caja }}
                                                </label>

                                            </div>

                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-4" style="max-height: 100%; overflow:auto;">
                            <h5>Cajas agregadas a este precio</h5>
                            <small v-if="caja_precios.length == 0">Aun no se han agregado cajas este precio</small>
                            <ul class="list-group" v-for="c in caja_precios" :key="c.id">
                                <li class="list-group-item">
                                    @{{ c.cajas.caja }}
                                    <a class="nav-link float-end text-muted" href="#"
                                        @click="setCajasPrecio(c.cajas_id)"><span class="mdi mdi-delete"></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Modal para ingresar o editar el precio sugerido -->
        <div class="modal fade" id="precioSugerido" tabindex="-1" aria-labelledby="precioSugeridoModalLabel"
            aria-hidden="true" v-show="showModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="precioSugeridoModalLabel">Agregar Precio Sugerido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('precios.storePrecioSugerido') }}" method="post">
                            @csrf
                            <input type="hidden" name="precio" value="{{ $precio }}">

                            <div class="mb-3">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="sugerido" name="sugerido" required v-model="precio_sugerido">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar precio sugerido</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal para ingresar o editar las fechas de inicio y finalizacion -->
        <div class="modal fade" id="fechas" tabindex="-1" aria-labelledby="fechasModalLabel"
            aria-hidden="true" v-show="showModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fechasModalLabel">Agregar fechas de inicio y finalizacion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('precios.storeFechas') }}" method="post">
                            @csrf
                            <input type="hidden" name="precio" value="{{ $precio }}">

                            <div class="mb-3">
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required v-model="fecha_inicio">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control" id="fecha_final" name="fecha_final" required v-model="fecha_final">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar fechas</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>
<script>
    var app = new Vue({
        el: "#precioShow",
        data: {
            /** show precios*/
            cajasP: "{{ $cajasP }}",
            precios: "{{ $precios }}",
            precio_productos: "{{ $preciosP }}",
            precio: "{{ $precio }}",
            caja_precios: [],
            message: {},
            txtBusqueda: "",
            ListFilter: [],
            list: [],
            selected: [],
            iva: parseFloat("{{ env('iva', 0.13) }}"),
            propina: parseFloat("{{ env('propina', 0.10) }}"),
            advalorem: parseFloat("{{ env('advalorem', 0.05) }}"),
            precioc: parseFloat("{{ $pr->precio }}"),
            precio_sugerido: parseFloat("{{ $pr->sugerido }}"),
            fecha_inicio: parseFloat("{{ $pr->fecha_inicio }}"),
            fecha_final: parseFloat("{{ $pr->fecha_final }}"),
            txtprecio: null,
            txtiva: null,
            txtvalorem: null,
            txtpropina: null,
            txttotal: null,
            txtprecioneto: null,
            validar_iva: parseFloat("{{ $pr->iva }}"),
            validar_advalorem: parseFloat("{{ $pr->advalorem }}"),
            validar_propina: parseFloat("{{ $pr->propina }}"),
            agregados: 1,
            showModal: false,
        },
        methods: {
            /***aqui obtengo los datos de las cajas disponibles */
            getDataCaja() {
                axios.get('{{ route('caja_precios.index_api') }}')
                    .then((rs) => {
                        if (rs.data.list)
                            this.setList(rs.data.list);

                    })
            },
            /**aqui creo la caja al precio */
            setCajasPrecio(id) {

                axios.post("{{ route('precios.store_apiPrecio') }}", {
                    "precio": this.precio,
                    "caja": id,
                }).then((rs) => {

                    if (rs.data) {
                        this.caja_precios = rs.data.cajas;
                        this.setMessage(rs.data.message, rs.data.type);
                    }
                })
            },
                    /** aqui obtengo las cajas disponibles*/
            getCajasPrecio() {

                axios.post('{{ route('precios.list_cajas') }}', {
                    "precio": this.precio,
                }).then((rs) => {
                    if (rs.data.cajas)
                        this.caja_precios = rs.data.cajas;
                })
            },
            /**con este metodo se elimina */
            destroyPreciosCajas(id) {
                if (confirm("Esta caja se borrara definitivamente. ¿Realmente quieres eliminarla?"))
                    axios.post('{{ route("caja_precios.destroy_api") }}', {
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
                let reg = new RegExp(this.txtBusqueda, "i");
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
            isCheck(id) {
                if (this.caja_precios != null && this.caja_precios.length > 0)
                    return this.caja_precios.find(p => p.cajas_id == id) != null ? true : false;
            },

            // Method to save the suggested price
            savePrecioSugerido() {
                axios.post('{{ route("precios.storePrecioSugerido") }}', {
                    "precio": this.precio,
                    "sugerido": this.precio_sugerido,
                }).then((rs) => {
                    if (rs.data.success) {
                        // Refresh the data after saving the suggested price
                        this.precio_sugerido =  parseFloat(rs.data.sugerido);
                        this.validar_advalorem = false;
                        this.setMessage(rs.data.message, rs.data.type);


                    }
                });
            },
                saveFechas() {
                axios.post('{{ route("precios.storeFechas") }}', {
                    "precio": this.precio,
                    "fecha_inicio": this.fecha_inicio,
                    "fecha_final": this.fecha_final,
                }).then((rs) => {
                    if (rs.data.success) {

                        this.fecha_inicio =  rs.data.fecha_inicio;
                        this.fecha_final =  rs.data.fecha_final;
                        this.validar_constante = false;
                        this.setMessage(rs.data.message, rs.data.type);


                    }
                });
            },
        },
        mounted: function() {
                  document.onreadystatechange = () => {
                if (document.readyState == "complete") {
                    this.getDataCaja();
                    this.getCajasPrecio();
                    this.txtprecio = '$' + this.precioCalculado;
                    this.txtpropina = '$' + this.propinaCalculada;
                    this.txtvalorem = '$' + this.valoremCalculado;
                    this.txtiva = '$' + this.ivaCalculado;
                    this.txttotal = '$' + this.totalCalculado;
                }
            };
               // actualiza las cajas
                const modalCajas = document.getElementById('cajas');
                modalCajas.addEventListener('hidden.bs.modal', () => {
                    location.reload();
                });
        },
        computed: {
            valoremCalculado() { //trabaje las validaciones por separado y refactorize la funcion es mas legible para entender lo que hace
                let ivatotal = this.validar_iva ? this.iva : 0;
                let propinatotal = this.validar_propina ? this.propina : 0;
                if (!this.validar_advalorem) {
                    return 0;
                }
                const preciototal = this.precioc / (this.agregados + (ivatotal + propinatotal + this.advalorem));

                const impuesto = this.agregados + ivatotal; //aqui en esta variable guardo la suma de agregados mas iva
                const precioSugeridoTotal = this.precio_sugerido / impuesto;

                const montoAdvalorem = preciototal > this.precio_sugerido ?
                    (preciototal - precioSugeridoTotal) * this.advalorem : 0;

                return montoAdvalorem.toFixed(2);
            },

            precioCalculado() {
                let ivatotal = this.validar_iva ? this.iva : 0;
                let propinatotal = this.validar_propina ? this.propina : 0;
                let advaloremtotal = this.validar_advalorem ? this.advalorem : 0;
                const impuestos = this.agregados + ivatotal + propinatotal ; //aqui se almacenan la suma de todo los porcentajes mas agregados

                const precioNeto = (this.precioc - parseFloat(this.valoremCalculado))/impuestos; //pendiente revisar si es asi que se debe hacer el calculo del precio neto
                return precioNeto.toFixed(2);
            },

            propinaCalculada() {
                if (!this.validar_propina) {
                    return 0;
                }

                const montoPropina = this.propina ? parseFloat(this.precioCalculado) * this.propina : 0;
                return montoPropina.toFixed(2);
            },

            ivaCalculado() {
                if (!this.validar_iva) {
                    return 0;
                }

                const montoIva = parseFloat(this.precioCalculado) * this.iva;
                return montoIva.toFixed(2);
            },

            totalCalculado() {
                if (!this.validar_iva && !this.validar_advalorem && !this.validar_propina) {
                    return parseFloat(this.precioCalculado).toFixed(2);
                }

                const subtotal = parseFloat(this.precioCalculado);
                const montoValorem = parseFloat(this.valoremCalculado);
                const montoPropina = parseFloat(this.propinaCalculada);
                const montoIva = parseFloat(this.ivaCalculado);

                const total = subtotal + montoValorem + montoPropina + montoIva;
                return total.toFixed(2);
            }

        }
    });
</script>
