@extends('layouts.mantenimientos')

@section('style')
    <style>
        .sidebarPanel2 {
            position: fixed;
            width: 260px;
            min-height: 97vh;
            right: 0;
        }

        .panel-body {
            min-height: 550px;
        }

        .regresar {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 10px 10px 10px 10px;
            background-color: light;
            color: #007bff;
            text-decoration: none;
            border: none;
            margin-left: 13px;
            font-size: 16px;
        }

        .regresar .icono {
            margin-right: 0;
            font-size: 30px;
        }

        .input-group {
            position: relative;
        }

        .error-message {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            margin-top: 4px;
        }
    </style>
    @yield('styles')
@endsection

@section('panel_mantenimiento')
    <div id="appAsignacion">
        <div>
            <a class="regresar text-uppercase" href="{{ route('administracion_mantenimientos.index') }}">
                <span class="mdi mdi-arrow-left-box icono"></span> Volver a mantenimientos
            </a>
        </div>

        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light shadow sidebarPanel2">
            <h3>ASIGNACIONES</h3>
            <small>Todas las asignaciones</small>

            <div class="col-12 mt-3">
                <small v-if="mantenimientos.length === 0"> Aun no se ha asignado habitacion a este mantenimiento y
                    empleado</small>

                <ul class="list-group">
                    <li class="list-group-item" v-for="p in mantenimientos" :key="p.id">
                        <div v-if="asignacion">
                            <span>@{{ p.observacion }}</span>

                            <a class="nav-link float-end text-muted" href="#" @click="destroyDetallePrecio(p.id)">
                                <span class="mdi mdi-delete"></span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container">

            <!-- mostrar todo los empleados  -->
            <div class="row">
                <div class="col-md-4">
                    <h6>Selecciona el empleado</h6>
                    <select v-model="selectedUser" class="form-control">
                        <option value="" disabled>Seleccione el empleado</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">
                            @{{ user.name }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Mensajes de alerta. -->

            <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                v-show="message.message && message.type">
                <strong>@{{ message.message }}</strong>
            </div>
            <div class="row">
                <!-- Bucle FOR de productos activos. -->
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4"
                    v-for="h in habitaciones" :key="h.id">
                    <div class="col-md-4">
                        <div class="card border-secondary p-3">
                            <div class="card-body">
                                <p>
                                    <span class="card-title text-uppercase h4 w-100">
                                        @{{ h.numero_habitacion }}
                                    </span><br>
                                    <small class="text-uppercase mb-3">
                                        @{{ tipo_mantenimientos }}
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End row. -->
            </div>
            <!-- End row. -->
        </div>
        </div>
        
    </div>
    

    <script>
        const mantenimientos = new Vue({

            el: '#appAsignacion',
            data: {
                asignacion: "{{ $asignacion }}",
                message: {},
                habitaciones: @json($habitaciones),
                users: @json($users),
                mantenimientos: @json($mantenimientos),
                selectedUser: null,
                txtHabitacion:'',

            },
            methods: {

                setMessage(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };

                    setTimeout(() => {
                        this.message = {};
                        this.txtProducto = '';
                    }, 1 * 1000)
                },


            },

            computed: {
                funcBuscarMantenimientos() {
                    return this.mantenimientos.filter((m) =>
                        m.observacion.includes(this.txtHabitacion.toLowerCase())
                    );
                },
            }
        });
    </script>
@endsection
