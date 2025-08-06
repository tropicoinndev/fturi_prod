@extends('layouts.mantenimientos')
@section('panel_mantenimiento')
    <section id="appHabitaciones">
        <div class="row p-4">
            <div class="col-12 h4 text-uppercase fw-bold">
                Asignación de tareas en habitación
            </div>
            <form action="{{ route('mantenimientos.habitaciones_store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12" v-if="list.length == 0">
                        No hay habitaciones para agregar.
                    </div>
                    <div class="col-10" v-if="list.length > 0">
                        <div class="row">
                            <div class="col-12 fw-bold">
                                Seleccione las habitaciones para asignar un mantenimiento:
                            </div>
                            <div class="col-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Buscar habitacion</span>
                                    </div>
                                    <input class="form-control" type="text" name=""
                                        placeholder="Escriba el numero de habitacion Ej.: 101" v-model="txtBuscar">

                                </div>
                            </div>
                            <div class="col-3 mt-3" v-for="i in getList">
                                <input type="checkbox" class="btn-check" :id="'hab-' + i.id" :value="i"
                                    name="habitacion[]" multiple autocomplete="off" v-model="habitaciones">
                                <label class="card btn btn-outline-primary text-left" :for="'hab-' + i.id">
                                    <div class="card-body ">
                                        <h5 class="card-title">Habitación @{{ i.numero_habitacion }}</h5>
                                        <p class="card-text">@{{ i.relacion_estado_habitaciones.estado_habitacion }}</p>
                                    </div>
                                </label>
                            </div>

                            <div class="col-12 mt-3 fw-bold">
                                Configuración de asignación:
                            </div>
                            <input type="hidden" name="habitaciones[]" :value="hab.cid" v-for="hab in habitaciones"
                                multiple>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="my-input">Seleccione el tipo de mantenimiento a realizar en las habitaciones
                                        seleccionadas</label>
                                    <select class="form-select" aria-label="Tipos de mantenimientos"
                                        name="tipo_mantenimientos_id" required>
                                        <option selected value="">Seleccione un tipo de mantenimiento</option>
                                        @foreach ($tipo_mantenimientos as $tm)
                                            <option value="{{ Crypt::encryptString($tm->id) }}">{{ $tm->mantenimiento }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="my-input">Seleccione el empleado</label>
                                    <select class="form-select" aria-label="Seleccione un usuario" name="users_id" required>
                                        <option selected value="">Seleccione un empleado</option>
                                        @foreach ($usuarios as $u)
                                            <option value="{{ Crypt::encryptString($u->id) }}">{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="comfirmacion"
                                        required>
                                    <label class="form-check-label" for="comfirmacion">
                                        Confirmacion de asignacion, confirmo que se agregaran las habitaciones al usuario
                                        seleccionado.
                                        <br>
                                        <small>Por favor, vuelva a revisar antes de guardar </small>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary"
                                    :disabled="habitaciones.length == 0">Guardar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-2" v-if="list.length > 0">
                        <div class="row">
                            <div class="col-12 fw-bold">
                                Habitaciones seleccionadas
                            </div>
                            <div class="col-12">
                                <ul class="list-group">
                                    <li class="list-group-item" v-for="h in habitaciones">Hab. @{{ h.numero_habitacion }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </section>
    <script>
        var app = new Vue({
            el: "#appHabitaciones",
            data: {
                habitaciones: [],
                empleado: null,
                mantenimiento: null,
                list: @json($p),
                txtBuscar: ''
            },
            computed: {
                getList() {
                    var reg = new RegExp(this.txtBuscar);
                    return this.list.filter(i => {
                        return reg.test(i.numero_habitacion);
                    });
                }
            },

        });
    </script>
@endsection
