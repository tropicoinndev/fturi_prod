@extends('layouts.app')

@section('content')
 @if ($p->estado ==2)
    <script>
        window.location = '/bodegas/my';
    </script>
@else
<style>
    .message {
        position: fixed;
        top: 20%;
        right: 1%;
        width: 15%;
        z-index: 100;
    }
    .producto-seleccionado {
    background-color:#ffffff;
    border: 1px solid #ccc;
    padding: 2px;
    border-radius: 9px;
}
</style>

<div id="appExistencias">
    <div class="alert show message" :class="'alert-'+ message.type" usuario="alert"
        v-show="message.message && message.type">
        <strong>@{{ message.message }}</strong>
    </div>

    <div class="container">
        <div class="row mb-5">
            <div class="col-md-2 mb-2 ">
                    <a type="button" class="btn btn-light text-end" href="{{route('bodegas.my')}}"
                        >Regresar a requisiciones</a>
                </div>
            <div class="col-md-12">
                <div class="card shadow-lg bg-body rounded">
                    <h5 class="card-header text-bg-primary text-center">Agregar desde requisicion</h5>
                    <div class="card-body">

                        <!--Mensajes de alerta-->
                        <div class="col-6">
                            <x-message></x-message>
                        </div>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="row" style="width:10%;">Producto</th>
                                    <th scope="col" style="width:2%;">Cantidad</th>
                                    <th scope="col" style="width:2%;">Existencias</th>
                                    <th scope="col" style="width:8%; text-align: center;">Agregar | Limpiar</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayRequisiciones.estado === 1 || arrayRequisiciones.estado === 4">
                                <tr>
                                    <td style="width: 10%;">
                                        <div v-show="!productoSelect">
                                            <input type="text" class="form-control" placeholder="Buscar producto..."
                                            autocomplete="off" id="producto" name="producto" v-model="txtBusqueda"
                                            @keyup="apiSearchProductos" :disabled="productosSelected.id > 0"></div>
                                        <div class="text-center" :class="{ 'producto-seleccionado': productoSelect }" v-show="productoSelect">
                                                Concepto: @{{ productosSelected.producto_nombre }}
                                                <span class=" btn  mdi mdi-close" @click="limpiar"
                                                    title="Quitar seleccion"></span>
                                            </div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control"
                                            placeholder="0" min="1" id="cantidad"  :max="productosSelected.existencia_sum" name="cantidad" v-model="cantidad" :disabled="productosSelected.length == 0 || productosSelected.maximos <= 0">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="0" min="1" disabled id="existencia" name="existencia" v-model="existencia">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-success" style="margin-right: 5px;" @click="apiStoreRequisicionDetalle(productosSelected.id, lote)" :disabled="productosSelected.length == 0 || productosSelected.maximos <= 0"
                                        >
                                            Agregar
                                        </button>

                                        <button type="button" class=" btn btn-outline-secondary" @click="limpiar" :disabled="productosSelected.length == 0 || productosSelected.maximos <= 0">
                                            Limpiar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!--Desplegable / Listado de productos.-->

                        <div v-if="arrayProductos.length > 0" class="result shadow" style="position: absolute;z-index: 10;margin-top: -20px;margin-left: 9px;width: 35.5em; background:white; padding:2px;">

                            <ul class="list-group" v-for="producto in arrayProductos">
                                <li  class="list-group-item d-flex justify-content-between align-items-center"
                                    style="border-radius: 0px; border: none; cursor:pointer;" @click="selected(producto)" >
                                    <span class="badge bg-primary red rounded-pill">Lote:# @{{ producto.lote_id }}</span>
                                    @{{producto.producto_nombre }}
                                    <span class="badge bg-primary rounded-pill">Existencias: @{{ producto.existencia_sum }}</span>
                                </li>
                            </ul>

                        </div>
                        <div v-if="arrayProductos.length ===0 || arrayProductos.length>0" class="result shadow" style="position: absolute;z-index: 110;margin-top: -20px;margin-left: 9px;width: 35.5em; background:white; padding:2px;">
                            @{{mensaje }}
                        </div>

                    </div>
                </div>
            </div>
        </div><!--End row-->

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg bg-body rounded">
                    <h5 class="card-header text-bg-success text-center">Detalle de existencia</h5>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-10">

                            </div>
                            @can('requisicion_detalles.index')
                            <div class="col-md-2" v-if="arrayRequisiciones.estado === 1 || arrayRequisiciones.estado === 4">
                                <button type="button" class="btn btn-outline-primary mt-2 mb-2" @click="RequisicionCompletada">Requisicion completa</button>
                            </div>
                            @endcan
                        </div>

                        <div class="table-responsive">
                                <table class="table table-sm table align-middle user-select-none">
                                    <thead>
                                        <tr>
                                            <th scope="col">#Lote BE</th>
                                            <th scope="col">#Lote BS</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col" style="width:20%;">Producto</th>
                                            <th scope="col" style="text-align: right;">Precio de Costo</th>
                                            <th scope="col" style="text-align: right;">vencimiento</th>

                                            <th scope="col" style="text-align: center;">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="listaLote in arrayExistencias" :key="listaLote.id">
                                            <td class="font-monospace">@{{ listaLote.lotes_id && listaLote.lotes_id  ? listaLote.lotes_id : ''}}</td>
                                            <td class="font-monospace">@{{ listaLote.lote_origen && listaLote.lote_origen  ? listaLote.lote_origen : ''}}</td>
                                            <td class="font-monospace">@{{ listaLote.cantidad }}</td>
                                            <td style="width: 20%;">@{{ listaLote.relacion_productos.nombre }}</td>
                                            <td>
                                                <span class="float-end font-monospace">$ @{{ listaLote.relacion_existencias && listaLote.relacion_existencias.precio_costo ? listaLote.relacion_existencias.precio_costo  : 'no posee precio'}}</span>
                                            </td>
                                            <td>
                                                <span class="float-end font-monospace">@{{listaLote.relacion_existencias && listaLote.relacion_existencias.vencimiento ? listaLote.relacion_existencias.vencimiento : 'no hay vencimiento'  }}</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    :disabled="arrayExistencias.length == 0" data-bs-toggle="modal"
                                                    data-bs-target="#modalEliminar"
                                                    @click="eliminarDetalle(listaLote.id, listaLote.relacion_productos.nombre)"v-if="arrayRequisiciones.estado === 1 || arrayRequisiciones.estado === 4">
                                                    <span class="mdi mdi-trash-can"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="border-1 border-bottom border-secondary"></tr>

                                    </tbody>
                                </table>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- Modal eliminar-->
        <div class="modal fade" id="modalEliminar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('requisicion_detalles.devolverProducto') }}" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Esta seguro de eliminar este producto?
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="idEliminardetalle" :value="idEliminardetalle">
                            <div class="mt-3 mb-3">

                                <ul>
                                    <li>@{{ registroAEliminar }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Si, Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div><!--End div appExistencias-->
 @endif
<script>
    var app = new Vue({
        el: '#appExistencias',
        data: {
            txtBusqueda   : '',
            arrayProductos: [],
            requisicion:@json($p->id),
            arrayRequisiciones: @json($p),
            arrayExistencias: @json($existencias),
            cantidad         : 1,
            existencia       : 0,
            productosSelected: [],
            lote:null,
            vencimiento:'',
            producto:null,
            mensaje:'',
            registroAEliminar: '',
            idEliminardetalle: null,
            message: {},
            productoSelect: false,
        },
        mounted(){

        },
        computed: {

        },
        methods: {
            async apiSearchProductos(){
                    if (this.debouncedSearch) {
                            clearTimeout(this.debouncedSearch);
                        }

                        this.debouncedSearch = setTimeout(async () => {
                            if (this.txtBusqueda.length > 3) {
                                try {
                                    const response = await axios.post("{{ route('existencias.productosExistencias') }}", {
                                        txtBusqueda: this.txtBusqueda,
                                        id:this.requisicion,
                                    });

                                    if (response.data && response.data.nombresProductosEnExistencia) {
                                        this.arrayProductos = response.data.nombresProductosEnExistencia;
                                        this.mensaje = '';
                                    } else {
                                        this.arrayProductos = [];
                                        this.mensaje = response.data.mensaje;
                                    }
                                } catch (error) {
                                    console.log(error);
                                }
                            } else {
                                this.arrayProductos = [];
                                this.mensaje = '';
                            }
                        }, 300);
            },
            selected(producto){
                this.productosSelected = producto;
                   if (this.productosSelected.existencia_sum > 0) {
                        this.arrayProductos = [];
                        this.txtBusqueda = this.productosSelected.producto_nombre;
                        this.existencia = this.productosSelected.existencia_sum;
                        this.vencimiento = this.productosSelected.vencimiento;
                         this.productoSelect = true;
                    } else {
                        this.setMessage('No hay existencias disponibles!!!', 'danger');
                    }

                    console.log(this.productosSelected.nombre);

            },
            apiStoreRequisicionDetalle(){
                  if (this.cantidad <= this.existencia && this.cantidad > 0) {
                        axios.post("{{ route('requisicion_detalles.store') }}",{
                            productos_id: this.productosSelected.producto_id,
                            cantidad: this.cantidad,
                            requisiciones_id: this.requisicion,
                            lotes_id:this.productosSelected.lote_id,
                            vencimiento:this.vencimiento,
                        })
                        .then((rs) => {
                            if (rs.data.type === 'success') {
                                this.setMessage('Producto agregado al detalle de requisicion.', 'success');
                                this.limpiar();
                                setTimeout(() => {
                                    location.reload();
                                }, 1 *1000);
                            } else {
                                this.setMessage(rs.data.msj, rs.data.type);

                            }
                        })
                    } else {
                        // La cantidad ingresada es mayor que la existencia disponible
                        this.setMessage('La cantidad ingresada es mayor que la existencia disponible || la cantidad debe ser 1 o mayor', 'danger');
                    }
            },
            RequisicionCompletada() {
                    axios.post("{{ route('requisicion_detalles.RequisicionDetalleCompleta') }}", {
                            requisicion: this.requisicion,
                        })
                        .then((rs) => {

                            if (rs.data.type === 'success') {
                                this.setMessage(rs.data.msj, rs.data.type);
                                setTimeout(() => {
                                    location.reload();
                                }, 1 *1000);


                            } else {
                                this.setMessage(rs.data.msj, rs.data.type);
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        })
                },
            limpiar(){
                this.txtBusqueda       = '';
                this.cantidad          = 1;
                this.existencia        = 0;
                this.productosSelected = [];
                 this.productoSelect = false;
            },
            eliminarDetalle(id, selectedDelete) {
                    this.idEliminardetalle = id;
                    this.registroAEliminar = selectedDelete;
                    console.log(id, selectedDelete);
                },
            setMessage(m, t){
                this.message = {
                    'message': m,
                    'type'   : t,
                };
                setTimeout(() => {
                    this.message = {};
                }, 4 * 1000)
            },
        },
    });
</script>

@endsection
