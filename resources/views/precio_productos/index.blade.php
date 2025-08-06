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
    .categoria {
  cursor: pointer;
  display: inline-block;
  padding: 4px;
  border: none;
  background-color: transparent;
}

.categoria input {
  border: none;
  background-color: transparent;
  width: 100%;
  padding: 6px 4512px;
  font-size: 14px;
  line-height: 1.42857143;
  color: #555;
  background-image: none;
  border: 1px solid #ccc;
  border-radius: 4px;
  transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
}

.categoria:focus {
  border-color: #66afe9;
  outline: 0;
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
}
  .categoria:hover {
    background: #4DB6AC;
    color: #ffffff;
    }
 

</style>
@yield('styles')
@endsection

@section('content')
<div id="appProductosActivos">
    
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-6" >
                <h3>Productos Precios Activos</h3>
                
            </div>
            <small >Busque cada producto y agregue una cantidad.</small> 
        </div>
        
        <hr>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="txtBusqueda" class="col-sm-2 col-form-label">Buscar:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-lg"
                            placeholder="Escriba el nombre del producto..." autocomplete="off" id="productos_id"
                            v-model="txtProducto" @keyup="getProductos">
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes de alerta. -->
        <div class="col-12">
            <x-message></x-message>
        </div>
        
        
            <div class="row">
            <!-- Bucle FOR de productos activos. -->
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3  "
                v-for="precio in precio_productos" :key="precio.id">
                <div class="card border-success p-3 shadow" >
                    <div class="card-body ">
                        <h4 class="card-title "><b><span>Producto:  </span>@{{ precio.productos.nombre }} <hr></b></h4>
                            <h4 for="descargo"><span>Precios:  </span>@{{ precio.precios.detalle }}</h4>
                            <h4 for="descargo"><span>Descargos: </span>@{{ precio.descargo }}</h4>
                        
                    
								
                    </div>
                </div>
            </div>
        </div>
        <!-- End row. -->
    </div>
    
    <!-- End container. -->
</div>

<script>
    const precio_productos = new Vue({
        el: '#appProductosActivos',
        data: {
            // Producto
            producto: '',
            txtProducto: '',

            // Categorias.
            
            precio_productos: @json($p),
            listFilter: '',
            descargo: '',
            dataProd: @json($data),
        },
        methods: {
            getProductos() {
                if (this.txtProducto.length > 0) {
                    axios.get("{{ route('precio_productos.api_search') }}", {
                            params: {
                                txtProducto: this.txtProducto,
                            }
                        })
                        .then((resp) => {
                            this.productos = resp.data.productos || [];
                        })
                        .catch(error => {
                            console.log(error);
                        });
                } else {
                    this.productos = [];
                }
            },
            agregarProducto(producto) {
                // aun no tengo precios_id no manda data aun
                axios.post("{{ route('precio_productos.store') }}", {
                productos_id: producto.id,
                precios_id: producto.id,
                descargo: this.descargo,
                })
                .then((response) => {
                
                console.log(response);
                })
                .catch((error) => {
                
                console.error(error);
                });
            },
        
            
                
          
        },
        
        computed: {
                
            
        }
    });
</script>

@endsection
