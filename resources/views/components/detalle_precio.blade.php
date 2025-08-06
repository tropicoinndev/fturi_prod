@extends('layouts.app')

@section('style')
    <style>
        .sidebarPanel {
            position: fixed;
            width: 260px;
            min-height: 97vh;
        }

        .sidebarPanel2 {
            position: fixed;
            width: 260px;
            min-height: 97vh;
            right: 0;
        }

        .panel-body {
            min-height: 550px;
        }

        .regresar {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 10px 10px 10px 10px;
            background-color: light;
            color: #007bff;
            text-decoration: none;
            border: none;
            margin-left: 13px;
            font-size: 16px;
        }

        .regresar .icono {
            margin-right: 0;
            font-size: 30px;
        }

        .input-group {
            position: relative;
        }

        .error-message {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            margin-top: 4px;
        }
    </style>
    @yield('styles')
@endsection

@section('content')
    <div id="appDetallePrecio">
        <div>
            <a class="regresar text-uppercase" href="{{ route('precios.index') }}">
                <span class="mdi mdi-arrow-left-box icono"></span> Volver a precios
            </a>
        </div>
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light shadow sidebarPanel">
            <h3>Categorias</h3>
            <small>ordenar productos por categorias.</small>
            <ul class="list-group list-group-flush mt-3">
                <label class="list-group-item text-uppercase" :class="{ 'active': catSelected.length == 0 }"
                    @click="catSelected = []">
                    Todas las categorias
                </label>
                <label class="list-group-item text-uppercase" role="group" v-for="cat in categorias" :key="cat.id"
                    :for="'cat-' + cat.id" :class="{ 'active': isCatSelect(cat.id) }">
                    <input type="checkbox" class="btn-check" :id="'cat-' + cat.id" :value="cat"
                        autocomplete="off" v-model="catSelected">
                    @{{ cat.categoria }}
                </label>

            </ul>
        </div>
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light shadow sidebarPanel2">
            <h3>Detalle del precio</h3>
            <small>Todas las medidas son unitarias (1 Oz = 1 sencillo)</small>

            <div class="col-12 mt-3">
                <small v-if="productosPrecios.length === 0"> Aun no se ha agregado un producto a este precio</small>

                <ul class="list-group">
                    <li class="list-group-item" v-for="p in productosPrecios" :key="p.id">
                        <div v-if="p.productos">
                            <span>@{{ p.descargo }}</span>
                            @{{ p.productos.nombre }}
                            <a class="nav-link float-end text-muted" href="#" @click="destroyDetallePrecio(p.id)">
                                <span class="mdi mdi-delete"></span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container">
            <div class="row mb-3">
                <div class="col-12 h4 text-uppercase fw-bold">
                    <span>
                        {{ $precioDetalle }}
                    </span>
                </div>
                <div class="col-md-6">
                    <h6>Busque cada producto y agregue una cantidad.</h6>
                </div>

            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="mb-3 row">
                        <label for="txtBusqueda" class="col-sm-1 col-form-label">Buscar:</label>
                        <div class="col-sm-11">
                            <input type="text" class="form-control form-control-lg"
                                placeholder="Escriba el nombre del producto..." autocomplete="off" id="productos_id"
                                v-model="txtProducto">
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-3" v-show="catSelected.length > 0">
                    Filtro: <span class="badge text-white bg-success ms-1 text-uppercase"
                        v-for="c in catSelected">@{{ c.categoria }}</span>
                </div>
            </div>

            <!-- Mensajes de alerta. -->

            <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                v-show="message.message && message.type">
                <strong>@{{ message.message }}</strong>
            </div>


            <div class="row">
                <!-- Bucle FOR de productos activos. -->
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3"
                    v-for="({ id, nombre, categoria }, index) in funcBuscarProductos"
                    v-if="(
                (categoria.token == '1201' && findPrecioToken() == '1101') ||
                (categoria.token == '1201' || categoria.token == '1202' || categoria.token == '1203') && findPrecioToken() == '1102' ||
                (categoria.token == '1202' || categoria.token == '1203') && findPrecioToken() == '1103' ||
                (categoria.token == '1201' || categoria.token == '1202') && findPrecioToken() == '1104' ||
                 (categoria.token == '1201' && findPrecioToken() == '1105')
                )">

                    <div class="card border-secondary p-3">
                        <div class="card-body">
                            <p>
                                <span class="card-title text-uppercase h4 w-100">
                                    @{{ nombre }}
                                </span><br>
                                <small class="text-uppercase mb-3">
                                    @{{ categoria.categoria }}
                                </small>
                            </p>
                            <descargo-input v-show="showDescargoInput(id)" :productos_id="id" :precio="precio"
                                :descargo="getDescargo(id)" @list="(list) => productosPrecios = list" />
                        </div>
                    </div>
                </div>
                <!-- End row. -->
            </div>
            <!-- End container. -->
        </div>

    </div>


    <script>
        Vue.component('descargoInput', {
            //Propiedades requeridas para funcionar el componente
            props: ['productos_id', 'precio', 'descargo'],
            data() {
                return {
                    localMessage: '',
                    iDescargo: this.descargo,
                };
            },
            methods: {
                setProducto() {
                    if (this.iDescargo > 0) {
                        axios.post("{{ route('precio_productos.store') }}", {
                                precio: this.precio, //Prop
                                productos_id: this.productos_id, //Prop
                                descargo: this.iDescargo //variable del componente
                            })
                            .then(response => {
                                if (response.data) {
                                    //Retornar una lista de productos agregados al precio, por medio de emision entre componentes
                                    this.$emit('list', response.data.list);

                                    //Retornar mensajes del controlador
                                    this.setM(response.data.message, response.data.type);
                                }
                            })
                            .catch(error => {
                                console.log(error);
                            });
                    } else {
                        alert("El valor de descargo debe ser mayor a Cero (0)");
                    }
                },
                setM(m, t) {
                    this.localMessage = {
                        message: m,
                        type: t
                    };
                    setTimeout(() => {
                        this.localMessage = {};
                    }, 6 * 1000);
                }
            },
            template: `
            <div class="input-group">
                <input
                type="number"
                class="form-control w-100"
                min="1"
                max="99"
                maxlength="99"
                placeholder="Descargo"
                aria-describedby="basic-addon1"
                @change="setProducto()"
                v-model="iDescargo"
                >
                <div v-if="localMessage.message" class="alert show message error-message" :class="'alert-' + localMessage.type" usuario="alert" v-show="localMessage.message && localMessage.type">
                <strong>@{{ localMessage.message }}</strong>
                </div>
            </div>
        `,
        });
        const precio_productos = new Vue({

            el: '#appDetallePrecio',
            data: {
                precio: "{{ $precio }}",
                preciot: {{ Crypt::decryptString($precio) }},
                descargo: '',
                txtProducto: '',
                message: {},
                productosPrecios: @json($productosPrecios),
                categorias: @json($categorias),
                productos: @json($productos),
                precios: @json($precios),
                catSelected: []

            },
            methods: {
                showDescargoInput(id) {
                    const {
                        categoria
                    } = this.funcBuscarProductos.find(producto => producto.id === id);
                    const precioToken = this.findPrecioToken();

                    if (categoria.token == '1201' && precioToken == '1101') {
                        return this.productosPrecios >= 0;
                    }
                    if (precioToken == '1105') {
                        return false;
                    }
                    if (categoria.token !== '1201' && precioToken != '1101') {
                        return true;
                    }

                    return false;
                },
                findPrecioToken() {
                    const precioToken = this.precios.find(p => p.id === this.preciot);
                    return precioToken ? precioToken.categorias_precios.token : null;
                },
                getDescargo(id) {
                    let pp = this.productosPrecios.find(p => p.productos_id == id);
                    if (pp)
                        return pp.descargo;
                    else
                        return '';
                },
                destroyDetallePrecio(id) {
                    if (confirm("Este producto se borrara definitivamente. ¿Realmente quieres eliminarlo?")) {
                        axios.post('{{ route('precio_productos.destroy_api') }}', {
                            "id": id
                        }).then((rs) => {
                            if (rs.data) {
                                //Retornar listado de precios_productos aun existentes
                                if (rs.data.list)
                                    this.productosPrecios = rs.data.list;
                                this.txtProducto = 'Actualizando productos...';
                                //Alerta de eliminacion
                                this.setMessage(rs.data.message, rs.data.type);

                            }


                        }).catch(e => console.log(e));
                    }
                },

                setMessage(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };

                    setTimeout(() => {
                        this.message = {};
                        this.txtProducto = '';
                    }, 1 * 1000)
                },

                isCatSelect(cat) {
                    return this.catSelected.find(c => c.id == cat) != null;
                }
            },

            computed: {
                funcBuscarProductos() {
                    return this.productos.filter((producto) =>
                        producto.nombre.toLowerCase().includes(this.txtProducto.toLowerCase()) &&
                        (this.catSelected.length == 0 || this.catSelected.find(c => c.id == producto
                            .categorias_id) != null)
                    );
                },
            }
        });
    </script>
@endsection
