@extends('layouts.list')

@section('list')
    <div id="indexGalerias">
        <div class="text-uppercase mb-3">
            <label class="form-label">Filtra por categoría de fotos:</label>
            <div>
                @foreach ($data['categoria_fotos'] as $categoria)
                    <label class="btn btn-outline-primary">
                        <input type="checkbox" name="categoria_foto" value="{{ $categoria->id }}" class="d-none">
                        {{ $categoria->categoria }}
                    </label>
                @endforeach
                <label class="btn btn-outline-primary">
                    <input type="checkbox" name="categoria_foto" value="" class="d-none">
                    Todas las categorías
                </label>
            </div>
        </div>
        <div class="row" id="contenedor_galerias" >
            @forelse($p as $d)
                <div class="col-md-4 mb-4 text-uppercase" data-categoria="{{ $d->categoria_fotos->id ?? '' }}">
                    <div class="card h-100"> <!-- Establecer altura máxima para las tarjetas -->
                        <img src="{{ asset('img/' . $d->foto) }}" class="card-img-top" style=" width: 100%; height:30vh;">
                        <div class="card-body d-flex flex-column align-items-center">
                            <!-- Alineación vertical del contenido -->
                            <h5 class="card-title text-center mb-3">Categoría: {{ $d->categoria_fotos->categoria ?? ''}}</h5>
                            <p class="card-text text-center mb-3">Descripción: {{ Str::limit($d->descripcion, 100) }}</p>
                            <!-- Truncar el texto -->
                            <div class="card-text mt-auto text-muted">
                                <!-- Alineación del pie de tarjeta en la parte inferior -->
                                <small>Fecha de creación: {{ $d->created_at }}</small><br>
                                <small>Fecha de modificación: {{ $d->updated_at }}</small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <x-acciones :table="$th['table']" :d="$d" />
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="card-text">Aun no se han agregado datos.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
@section('script')
    <script>
        new Vue({
            el: '#indexGalerias',
            data: {

            },
            mounted() {
                document.addEventListener('DOMContentLoaded', function() {
                    const Ccheckbox = document.querySelectorAll('input[name="categoria_foto"]');
                    const fotos = document.querySelectorAll('.col-md-4');
                    Ccheckbox.forEach(function(checkbox) {
                        checkbox.addEventListener('change', function() {
                            const catSelected = this.value;
                            fotos.forEach(function(foto) {
                                const categoriaFoto = foto.getAttribute(
                                    'data-categoria');
                                if (catSelected === '') {
                                    foto.style.display = 'block';
                                } else {
                                    if (categoriaFoto === catSelected) {
                                        foto.style.display = 'block';
                                    } else {
                                        foto.style.display = 'none';
                                    }
                                }
                            });
                        });
                    });
                });
            },

        });
    </script>
@endsection
