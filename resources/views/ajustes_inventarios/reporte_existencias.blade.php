@extends('layouts.ajustes_inventarios')
@section('style-ajustes-inventarios')
<style>
    [v-cloak] {
            display: none;
        }
</style>
@endsection
@section('ajustes_inventarios_content')
<div id="reporteBodegaProducto" v-cloak>
        <div class="row mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-file-chart text-success h2"></span>
                Reporte de existencias por producto
            </h3>
            <x-message></x-message>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-12">
            <form class="row align-items-center" action="{{ route('ajustes_inventarios.reportExisByBodegaSearch') }}" method="POST">
                @csrf
                <div class="col-4">
                        <div>
                            <label for="" class="form-label">Filtrar por productos</label>
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="searchProduct" placeholder="Ingrese al menos 4 caracteres para buscar productos" @keyup="filtrarProductos" :disabled="selectedProductId">
                                <button class="btn btn-outline-secondary" type="button" @click="limpiarSeleccion" v-show="selectedProductId">
                                    <i class="mdi mdi-close"></i>
                                </button>

                            </div>
                            <input type="hidden" name="productoId" v-model="selectedProductId">

                            <ul v-if="searchProduct.length >= 4" class="list-group" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);border-radius: 0.25rem;position: absolute;width: 22%;max-height: 200px;overflow-y: auto; z-index:4; background-color: rgba(255, 255, 255, 0.9)">
                                <li v-for="producto in productos" :class="{ 'list-group-item': true, 'list-group-item-action': true, 'd-flex': true, 'justify-content-between': true, 'align-items-center': true, 'active': selectedProductId == producto.id }" style="border-radius: 0px; border: none; cursor:pointer; transition: background-color 0.3s, transform 0.3s;" @click="selectProduct(producto)">
                                    @{{ producto.nombre }}
                                </li>
                            </ul>
                        </div>
                        <div v-if="mensaje" class="list-group-item d-flex justify-content-between align-items-center" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);border-radius: 0.25rem;position: absolute;width: 22%;max-height: 200px;overflow-y: auto; z-index:4;background-color: rgba(255, 255, 255, 0.9) ">
                            @{{ mensaje }}
                        </div>
                    </div>


                <div class="col-4">
                    <label for="bodegaId" class="form-label">Filtrar por bodega:</label>
                    <select class="form-select rounded-5" id="bodegaId" name="bodegaId" aria-label="Default select example" required>
                        <option value="" selected disabled>--Seleccione---</option>
                        @foreach($bodegas as $b)
                            <option value="{{ $b->id }}" {{ isset($bodegaId) && $bodegaId == $b->id ? 'selected' : '' }} class="text-uppercase">{{ $b->bodega }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-4 text-end">
                    <button style="margin-top: 30px;" type="submit" class="btn btn-primary rounded-5 me-3" value="{{ Crypt::encryptString(1) }}" name="opcion"><span class="mdi mdi-magnify"></span> Buscar</button>
                    <button @if(!isset($reporteExistencias)) disabled @endif style="margin-top: 30px;" type="submit" class="btn btn-outline-success rounded-5 me-2" value="{{ Crypt::encryptString(2) }}" name="opcion"><span class="mdi mdi-file-pdf-box"></span> Generar reporte</button>
                </div>
            </form>
        </div>
    </div>

    @isset($reporteExistencias)
        <div class="row mb-4">
            <div class="col-12">
                <div class="col-12">
                    <table class="table table-hover table-sm table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Lote</th>
                                <th>Vencimiento</th>
                                <th>Producto</th>
                                <th>Ingreso</th>
                                <th>Bodega entra producto</th>
                                <th>Salio</th>
                                <th>Bodega sale producto</th>
                                <th>Existencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reporteExistencias as $e)
                                <tr>
                                    <td scope="row">#{{ $e['existencia']->id }}</td>
                                    <td scope="row">{{ $e['existencia']->vencimiento }}</td>
                                    <td scope="row">{{ $e['existencia']->productosExistencias->nombre }}</td>
                                    <td scope="row">{{ $e['requisicion_detalles_id']->relacionRequisiciones->created_at }}</td>
                                    <td scope="row">
                                        {{ $e['requisicion_detalles_id']->relacionRequisiciones->relacionBodegasEntrada->bodega }}
                                    </td>
                                    <td scope="row">{{ $e['requisicion_detalles_id']->relacionRequisiciones->updated_at }}</td>
                                    <td scope="row">
                                        {{ $e['requisicion_detalles_id']->relacionRequisiciones->relacionBodegasSalida->bodega }}
                                    </td>
                                    <td scope="row">{{ $e['existencia']->existencia }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-uppercase">No hay existencias disponibles del producto que se ha seleccionado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endisset
</div>
@endsection
@section('script-ajustes-inventarios')
    <script type="module">
        const app = window.appVue({
            data(){
                return {
            bodegaId: "{{ $bodegaId ?? '' }}",


            searchProduct: '',
            selectedProductId: null,
            productos: [],
            mensaje: '',
                }
            },
            mounted(){
            // Recupero el producto seleccionado de localStorage al inicio
            const storedProduct = localStorage.getItem('selectedProduct');
            if (storedProduct) {
                const selectedProduct = JSON.parse(storedProduct);
                this.searchProduct = selectedProduct.nombre;
                this.selectedProductId = selectedProduct.id;
            }
            const bodegaL = localStorage.getItem('bodegaLogueada');
            if (bodegaL) {
                this.bodegalogueado = bodegaL;
            }
            },
            methods: {
                    actualizarBodegaLogueada() {
                localStorage.setItem('bodegaLogueada', this.bodegalogueado);
            },
            async filtrarProductos() { //filtra los productos de forma asincrona
                if (this.debouncedSearch) {
                    clearTimeout(this.debouncedSearch);
                }
                this.debouncedSearch = setTimeout(async () => {
                    if (this.searchProduct.trim().length > 4) {
                        try {
                            const response = await axios.post(
                                "{{ route('existencias.productos') }}", {
                                    productos_id: this.searchProduct,
                                });

                            this.productos = response.data.productos || [];
                            this.mensaje = response.data.mensaje || '';

                        } catch (error) {
                            console.log(error);
                        }
                    }
                }, 100);
            },
            selectProduct: function(selectedProduct) {
                this.searchProduct = selectedProduct.nombre;
                this.selectedProductId = selectedProduct.id;
                this.productos = [];
            },
            limpiarBusqueda: function() {
                this.searchProduct = '';
                this.selectedProductId = null;
                this.productos = [];
                this.mensaje = '';
            },
            limpiarSeleccion() {
                this.searchProduct = ''; // Limpiar el campo de búsqueda
                this.selectedProductId = null; // Desseleccionar el producto
                this.productos = []; // Limpiar la lista de productos filtrados
            }
            },
            computed: {

            },
            watch: {
                searchProduct: function(newValue) {
                const selectedProduct = {
                    nombre: this.searchProduct,
                    id: this.selectedProductId,
                };
                localStorage.setItem('selectedProduct', JSON.stringify(selectedProduct));
            },
            },
        });
        app.mount('#reporteBodegaProducto');
    </script>
@endsection
