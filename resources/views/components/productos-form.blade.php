@if (isset($table) && isset($data))
    <div id="appProductos" class="container">
        <div class="row">
            <div class="col-md-6">
                <h5>Formulario de Productos</h5>
                <form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post" id="appProductos">
                    @csrf

                    @if (isset($p) && $p->id > 0)
                        <input type="hidden" name="id" value="{{ $p->id }}">
                    @endif

                    <div class="mb-3">
                        <x-input-text name="nombre" label="Nombre:" val="{{ $p->nombre ?? '' }}" required maxlength="50"/>
                    </div>

                    <div class="mb-3">
                        <x-input-select name="categorias_id" label="Categoría:" :data="$data['categorias']" table="categorias"
                            showName="categoria" val="{{ $p->categorias_id ?? '' }}" required/>
                    </div>

                    <div class="mb-3">
                        <x-input-number name="minimos" label="Mínimos: 1-99999" placeholder="00000" min="1"
                            max="99999" val="{{ $p->minimos ?? '' }}" required maxDigitos="5"/>
                    </div>

                    <div class="mb-3">
                        <x-input-number name="maximos" label="Máximos: 1-99999" placeholder="00000" min="1"
                            max="99999" val="{{ $p->maximos ?? '' }}" required maxDigitos="5"/>
                    </div>

                    <div class="mb-3">
                        <label for="vencimiento" class="form-label">Vencimiento:</label>
                        <select class="form-select" aria-label="Default select example" id="vencimiento" name="vencimiento">
                            <option selected disabled>--Seleccione--</option>
                            <option value="1" :selected="vencimiento == 1">Si</option>
                            <option value="0" :selected="vencimiento == 0">No</option>
                        </select>
                        {{-- <small class="text-danger"><span class="mdi mdi-alert"></span> {{ $message }}</small> --}}
                    </div>

                    <div class="input-group">
                        <button class="btn btn-primary mb-3" type="submit">Guardar</button>
                    </div>
                </form>
            </div>

            <div class="col-md-6" id="appProductos">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Últimos 6 registros de Productos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <ul class="list-group col-6">
                                @foreach ($data['productos'] as $producto)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $producto->nombre }}
                                        <a class="btn btn-sm btn-danger"
                                            href="{{ route('productos.confirm', [
                                                'id' => \Crypt::encryptString($producto->id),
                                            ]) }}">Eliminar</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                @if (empty($data['productos']))
                    <p>No hay registros de productos.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        var app = new Vue({
            el: '#appProductos',
            data: {
                productos: @json($data['productos']),
                vencimiento: {{ isset($p) ? ($p->vencimiento == 1 ? '1' : '0') : '0' }},

            },
            methods: {
                // Add your methods here
            },
        });
    </script>
@else
    Este formulario requiere lo atributos :table y :productos
@endif
