@extends('layouts.bodegas')

@section('panel_bodega')
    <div id="appExistencias">
        <div class="row mb-4">
            <div class="col-12 text-uppercase h3">
                Reporte de existencias por bodega
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <form class="form-inline" method="post" action="{{ route('existencias.reporte_bodega_existencia_search') }}">

                    @csrf
                    <div class="row">
                        <div class="col-3">
                            <label for="">Filtrar por bodegas</label>
                            <!-- Agrega un campo para seleccionar la bodega -->
                            <select class="form-select" name="bodega_users_id" v-model="bodegalogueado"
                                @change="actualizarBodegaLogueada">
                                <option selected disabled>Seleccionar Bodega</option>
                                @foreach ($bodega as $b)
                                    <option value="{{ $b->relacionBodegas->id }}">{{ $b->relacionBodegas->bodega }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 align-self-end">
                            <button class="btn btn-outline-primary" value="{{ Crypt::encryptString(1) }}" name="accion"
                                type="submit">Buscar</button>

                            <button class="btn btn-outline-success" value="{{ Crypt::encryptString(2) }}" name="accion"
                                :disabled="existenciaBodega <= 0" type="submit">Generar reporte</button>

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

                        @forelse ($existencias as $e)
                            <tr>
                                <td scope="row">#{{ $e->lote }}</td>
                                <td scope="row">{{ $e->vencimiento ?? 'No vence' }}</td>
                                <td scope="row">{{ $e->productosExistencias->nombre }}</td>
                                <td scope="row">
                                    {{ $e->ingreso }}
                                </td>
                                <td scope="row">
                                    {{ $e->bodega_entrada }}
                                </td>
                                <td scope="row">
                                    {{ $e->salio }}
                                </td>
                                <td scope="row">
                                    {{ $e->bodega_salida }}
                                </td>
                                <td scope="row">{{ $e->existencia }}</td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-uppercase">No hay existencias disponibles en la bodega
                                    seleccionada.</td>
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
                bodegaId: "",
                bodega: "{{ $bodega }}",
                bodegalogueado: "{{ $bodegalogueado }}",

            },
            mounted() {
                const bodegaL = localStorage.getItem('bodegaLogueada');
                if (bodegaL) {
                    this.bodegalogueado = bodegaL;
                }
            },
            methods: {
                actualizarBodegaLogueada() {
                    localStorage.setItem('bodegaLogueada', this.bodegalogueado);
                },
            },
            computed: {

            },
        });
    </script>
@endsection
