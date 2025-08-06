@extends('layouts.list')

@section('list')
    <div class="table-responsive" id="appUsers">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col-1">Acciones</th>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Tipo de usuario</th>

                </tr>
            </thead>
            <tbody>
                @forelse($p as $d)
                    <tr>
                        <td><x-acciones :table="$th['table']" :d="$d" /></td>
                        <th>{{ $d->id }}</th>
                        <td>{{ $d->name }}</td>
                        <td>{{ $d->email }}</td>
                        <td>
                            <select_control :data="tokens" name="label" actual="{{ $d->token }}"
                                id="{{ $d->cid }}" :url="url" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No se encontraron registros para mostrar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <script type="module">
        var app = window.appVue({
            data() {
                return {

                    tokens: @json($token),
                    url: "{{ route('users.api_usuario_token') }}"
                }
            },
        });
        app.component('select_control', component.select_control);
        app.mount("#appUsers");
    </script>
@endsection
