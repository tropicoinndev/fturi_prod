@extends('layouts.bodegas')

@section('panel_bodega')
<div id="appExistencias">
    <div class="row mb-4">
        <div class="col-12 text-uppercase h3">
            Reporte de existencias por producto
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <form class="form-inline" method="post" action="{{ route('existencias.existencias_reporte_search') }}">

                @csrf
                <div class="row">
                    <div class="col-4">
                        <div>
                            <label for="">Filtrar por productos</label>
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="searchProduct" placeholder="Ingrese al menos 4 caracteres para buscar productos" @keyup="filtrarProductos" :disabled="selectedProductId">
                                <button class="btn btn-outline-secondary" type="button" @click="limpiarSeleccion" v-show="selectedProductId">
                                    <i class="mdi mdi-close"></i>
                                </button>

                            </div>
                            <input type="hidden" name="productos_id" v-model="selectedProductId">

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
                    <div class="col-3">
                        <label for="">Filtrar por bodegas</label>
                        <!-- Agrega un campo para seleccionar la bodega -->
                        <select class="form-select" name="bodega_users_id" v-model="bodegalogueado" @change="actualizarBodegaLogueada">
                            <option selected disabled>Seleccionar Bodega</option>
                            @foreach ($bodega as $b)
                            <option value="{{ $b->relacionBodegas->id }}">{{ $b->relacionBodegas->bodega }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 align-self-end">
                        <button class="btn btn-outline-primary" value="{{ Crypt::encryptString(1) }}" name="accion" type="submit">Buscar</button>
                        <button class="btn btn-outline-danger" @click="limpiarBusqueda()">Limpiar</button>
                        <button class="btn btn-outline-success" value="{{ Crypt::encryptString(2) }}" name="accion" type="submit" {{ collect($reporteExistencias)->isEmpty() ? 'disabled' : '' }}>Generar reporte</button>

                    </div>

                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table table-striped table-inverse">
                <thead class="thead-inverse">
                    <tr>
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
                        <td scope="row">{{ $e->vencimiento ?? 'No vence'}}</td>
                        <td scope="row">{{ $e->productosExistencias->nombre }}</td>
                        <td scope="row">{{ $e->ingreso }}
                        </td>
                        <td scope="row">
                            {{ $e->bodega_entrada }}
                        </td>
                        <td scope="row">{{ $e->salio }}
                        </td>
                        <td scope="row">
                            {{ $e->bodega_salida }}
                        </td>
                        <td scope="row">{{ $e->existencia }}</td>

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
<script>
    var app = new Vue({
        el: '#appExistencias',
        data: {
            bodegaId: "{{ $bodegaId ?? '' }}",
            bodega: "{{ $bodega }}",
            bodegalogueado: "{{ $bodegalogueado }}",
            productosId: "{{ $productosId }}",
            searchProduct: '',
            selectedProductId: null,
            productos: [],
            mensaje: '',

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
        watch: {
            // Observo cambios en  searchProduct y almacena el producto seleccionado en localStorage
            searchProduct: function(newValue) {
                const selectedProduct = {
                    nombre: this.searchProduct,
                    id: this.selectedProductId,
                };
                localStorage.setItem('selectedProduct', JSON.stringify(selectedProduct));
            },
        },
        mounted() {
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
        computed: {

        },
    });
</script>
@endsection
