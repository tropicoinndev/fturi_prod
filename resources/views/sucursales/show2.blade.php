@extends('layouts.form')

@section('form')
    {{-- <x-sucursales-form table="{{ $th['table'] }}" :p="$p" :data="$data"/> --}}

    <style>
        .color-rojo {
            color: #b92e2e;
        }
    </style>

    <div id="appCorrelativoSucursals" class="container-fluid user-select-none">

        {{-- {{ $correlativoSucursals }} --}}
        <!--Boton agregar y caja de busqueda-->
        <div class="row justify-content-between mb-3">
            <div class="col-6">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarCorrelativo">
                    <i class="bi bi-plus-lg"></i> Agregar
                </button>
            </div>

            <div class="col-6">
                <input type="text" class="form-control" placeholder="Buscar...">
            </div>
        </div>

        <!--Listado-->
        <div class="row justify-content-center">
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Acciones</th>
                            <th scope="col">#</th>
                            <th scope="col" class="text-end">Inicio</th>
                            <th scope="col" class="text-end">Actual</th>
                            <th scope="col" class="text-end">Final</th>
                            <th scope="col" class="text-center">Año</th>
                            <th scope="col" class="text-center">Sucursal</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($correlativoSucursals as $cs)
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="mdi mdi-cog"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><span class="mdi mdi-pencil"></span> Editar</a></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('correlativo_sucursal.status',['id'=>$cs->cid]) }}">
                                                    <span class="{{ $cs->estado ? 'mdi mdi-toggle-switch text-success' : 'mdi mdi-toggle-switch-off text-danger' }}"></span> 
                                                    {{ $cs->estado ? 'Desactivar' : 'Activar' }}
                                                </a>
                                            </li>
                                            @auth
                                                @can('admin')
                                                    <li><a class="dropdown-item" href="{{ route('correlativo_sucursal.restart',['id'=>$cs->cid]) }}"><span class="mdi mdi-reload"></span> Reiniciar Actual</a></li>
                                                @endcan
                                            @endauth
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="{{ route('correlativo_sucursal.confirm',['id'=>$cs->cid]) }}"><span class="mdi mdi-delete text-danger"></span> Eliminar</a></li>
                                        </ul>
                                    </div>

                                    <!--<x-acciones :table="$th['table']" :d="$cs"/>-->
                                </td>
                                <th scope="row">{{ $cs->id }}</th>
                                <td class="text-end">{{ $cs->inicio }}</td>
                                <td class="text-end">{{ $cs->actual }}</td>
                                <td class="text-end">{{ $cs->final }}</td>
                                <td class="text-center">{{ $cs->year }}</td>
                                <td class="text-center">{{ $cs->sucursales->sucursal }}</td>
                                <td class="{{ $cs->estado ? 'text-success' : 'text-danger' }}">
                                    {{ $cs->estado ? 'Activo' : 'Inactivo' }}
                                </td>
                            </tr>
                        @empty
                            <p>No datos para mostrar.</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>



        <!--Modal crear correlativo-->
        <div class="modal fade" id="modalAgregarCorrelativo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('correlativo_sucursal.store') }}" method="POST">
                    @csrf

                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="bi bi-plus-lg"></i> Agregar Correlativo · <small>{{ $p->sucursal }}</small></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!--Indicaciones-->
                            <small class="color-rojo">
                                {{-- <p style="margin-bottom: -1px;">- Todos los campos son requeridos *</p> --}}
                                <p>- Solo se permiten números.</p>
                            </small>

                            <!--Sucursal ID encriptado-->
                            <input type="hidden" value="{{ $p->cid }}" name="sucursalId">

                            <div class="mb-3">
                                <label for="inicio" class="form-label">Inicio:</label>
                                <input v-model="txtInicio" @input="validateInput('txtInicio', $event)" type="text" class="form-control" id="inicio" name="inicio" aria-describedby="inicioHelp" placeholder="0" required maxlength="15" pattern="[0-9]{1,15}" inputmode="numeric" autocomplete="off">
                                <!--<div id="inicioHelp" class="form-text">We'll never share your email with anyone else.</div>-->
                            </div>
    
                            <div class="mb-3">
                                <label for="actual" class="form-label">Actual:</label>
                                <input v-model="txtActual" @input="validateInput('txtActual', $event)" type="text" class="form-control" id="actual" name="actual" aria-describedby="actualHelp" placeholder="0" required maxlength="15" pattern="[0-9]{1,15}" inputmode="numeric" autocomplete="off">
                                <!--<div id="actualHelp" class="form-text">We'll never share your email with anyone else.</div>-->
                            </div>
    
                            <div class="mb-3">
                                <label for="final" class="form-label">Final:</label>
                                <input v-model="txtFinal" @input="validateInput('txtFinal', $event)" type="text" class="form-control" id="final" name="final" aria-describedby="finalHelp" placeholder="0" required maxlength="15" pattern="[0-9]{1,15}" inputmode="numeric" autocomplete="off">
                                <!--<div id="finalHelp" class="form-text">We'll never share your email with anyone else.</div>-->
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cerrar</button>
                            <button :disabled="txtInicio.length <= 0" type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div><!-- End div-app -->

    <script type="module">
        let app = window.appVue({
            data(){
                return {
                    txtInicio: 1,
                    txtActual: 0,
                    txtFinal: 999999999999999,
                }
            },
            beforeUnMounted(){
                //Code...
            },
            mounted(){
                console.log('Correlativo Sucursals Mounted.');
            },
            methods: {
                validateInput(inputName, event){//Recibe como parametro el v-model de cada input y el event
                    let input = event.target.value;

                    input = input.replace(/\s/g, '');//Eliminar espacios en blanco
                    input = input.replace(/\D/g, '');//Eliminar caracteres que no sean números

                    this[inputName] = input;//Actualizar el valor del input correspondiente
                },
            },
            computed: {
                //Code...
            },
            watch: {
                //Code...
            },
        });
        app.mount('#appCorrelativoSucursals');
    </script>
@endsection
