@extends('layouts.mantenimientos')
@section('panel_mantenimiento')
    <style>
        .asignaciones-panel {
            position: fixed;
            width: 260px;
            height: 92vh;
            right: 1%;
            z-index: 1050;
            transition: width 0.3s ease-in-out;
            background: #E0F2F1;
            border-radius: 8px;
            top: 69px;
            overflow: auto;
        }

        .asignaciones-panel,
        #btnAsignacio {
            color: #37474F;
            font-size: 12pt;
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

        .custom-label {
            display: block;
            border: 1px solid #ccc;
            padding: 10px 10px 10px 10px;
            transition: background-color 0.3s ease-in-out;
            cursor: pointer;
        }

        .selected {
            background-color: #007bff;
        }

        .active {
            background-color: #007bff;
            background-color: #007bff;
            color: white;
        }



        .ct {
            color: black;
        }

        .selected .ct {
            color: #ffffff;
        }



        .show-assignments-panel:hover {
            background-color: #0056b3;

        }


        .contador-titulo {
            display: flex;
            align-items: center;
        }

        .contador-no-asignados {
            margin-left: 10px;
            color: red;
        }

        #btnAsignacion {
            position: fixed;
            bottom: 12%;
            right: 5%;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            z-index: 1200;

        }

        .custom-message {
            position: fixed;
            z-index: 1500;
        }
    </style>

    <div id="appAsignacion">
        <div class="col-8"v-if="message.type" :class="['alert', 'alert-' + message.type, 'custom-message']">
            @{{ message.message }}
        </div>
        <x-message></x-message>
        <button class="btn btn-light" id="btnAsignacion" @click="setFilter(1)" v-show="!filterShow"><span
                class="mdi mdi-filter-variant"></span> Asignaciones</button>
        <div class="p-3 shadow asignaciones-panel" v-show="filterShow">
            <div class="row col-12" style="margin-right:8px;">
                <p class="col-12 fs-4 d-flex align-items-center">
                    <a href="#" class="h3" @click="setFilter(0)">
                        <span class="mdi mdi-minus"></span>
                    </a>
                    <span class="h5 ml-2">ASIGNACIONES</span>
                </p>
            </div>
            <div class="col-12" v-if="selectedUser">
                <small v-if="actividadesAsignadas === 0 && mantenimientosNoAsignados.length === 0">
                    Aún no se ha asignado habitación a este mantenimiento y empleado
                </small>
                <div class="row">
                    <div class="col-12">
                        <strong>Mantenimientos asignados</strong>
                    </div>
                    <div class="col-12">
                        <ul class="list-group">
                            <li class="list-group-item" v-for="p in actividadesAsignadas" :key="p.id">
                                <a class="float-end
                    text-danger h4" title="Eliminar Asignacion"
                                    @click="eliminarAsignacion(p) ">
                                    <span class="mdi mdi-close"></span>
                                </a>
                                @{{ p.tipo_mantenimientos.mantenimiento }}

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container" style="z-index: 100;">

            <!-- Mensajes de alerta. -->

            <div class="">
                <a class="regresar text-uppercase" href="{{ route('mantenimientos.index') }}">
                    <span class="mdi mdi-arrow-left-box icono"></span> Volver a mantenimientos
                </a>

            </div>

            <div class="contador-titulo">
                <h1 class="m-2">Asignación de tareas</h1>
                <div class="contador-no-asignados">
                    <span>@{{ contarMantenimientosNoAsignados() }} Mantenimientos sin asignar </span>
                </div>
            </div>

            <!-- mostrar todo los empleados  -->
            <div class="row">
                <div class="col-md-4 p-4 text-uppercase">
                    <h6>Selecciona el empleado</h6>
                    <select v-model="selectedUser" @change="filterMantenimientos" class="form-select"
                        class="text-uppercase">
                        <option value="" disabled>Seleccione el empleado</option>
                        <option v-for="user in users" :key="user.id" :value="user.id" class="text-uppercase">
                            <span class="text-uppercase">@{{ user.name }}</span>
                        </option>
                    </select>
                </div>
            </div>
            <div class="row" v-if="selectedUser">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4" v-for="mt in mantenimientosMostrados"
                    :key="mt.id">
                    <div class="card custom-card"
                        :class="{ selected: selectedM.includes(mt.id), active: isActive(selectedUser, mt.id) }">
                        <div class="card-body">
                            <label :for="'m_' + mt.id" class="d-block custom-checkbox-label">
                                <input class="d-none" type="checkbox" :id="'m_' + mt.id" :value="mt.id"
                                    v-model="selectedM" @click="asignarMantenimientoUsuario(mt)">
                                <div class="custom-checkbox"></div>
                                <h5 class="card-title text-uppercase ct">
                                    @{{ mt.tipo_mantenimientos.mantenimiento }}
                                </h5>
                                <div class=" ct">
                                    Habitacion: @{{ mt.habitaciones.numero_habitacion }}

                                </div>
                                <div class="mt-3">
                                    <span class="ct">Solicitado por:</span>
                                    <span class="text-uppercase ct">@{{ mt.creador.name }}</span>
                                </div>
                                <div class="mt-2 ct">
                                    <span class="ct">Fecha de creación:</span>
                                    <span>@{{ mt.creacion }}</span>
                                </div>
                                <div class="mt-2 ct">
                                    <span class=" ct">Tiempo transcurrido:</span>
                                    <span>@{{ tiempoTranscurrido(mt) }} horas</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    </div>


    <script>
        const asignacionesMantenimientos = new Vue({

            el: '#appAsignacion',
            data: {

                message: {},
                users: @json($users),
                mantenimientos: @json($mantenimientos),

                selectedUser: null,
                txtHabitacion: '',
                selectedM: [],
                mantenimientosAsignados: [], // Initialize here
                mantenimientosNoAsignados: [],
                mantenimientosMostrados: [],
                actividadesAsignadas: [],
                filterShow: 1,
            },
            methods: {
                setFilter: function(status) {
                    this.filterShow = !!status;
                    // Guarda el valor de filterShow en el almacenamiento local
                    localStorage.setItem('filterShow', this.filterShow ? 1 : 0);
                },
                contarMantenimientosNoAsignados() {
                    const mantenimientosSinAsignarse = this.mantenimientos.filter(mt => mt.asignado_users_id ===
                        null);
                    return mantenimientosSinAsignarse.length;
                },
                async actualizarMantenimientosNoAsignados() {
                    try {
                        const response = await axios.post(
                            '{{ route('mantenimientos.obtenerMantenimientosNoAsignados') }}');
                        this.mantenimientosNoAsignados = response.data.mantenimientosNoAsignados;
                    } catch (error) {
                        console.error('Error al obtener los mantenimientos no asignados:', error);
                    }
                },
                async obtenerActividades(userId) {
                    try {
                        const response = await axios.post(
                            '{{ route('mantenimientos.obtenerMantenimientosByUser') }}', {
                                userId: userId
                            });
                        this.actividadesAsignadas = response.data.mantenimientosAsignados;
                    } catch (error) {
                        console.error('Error al obtener los mantenimientos asignados:', error);
                    }
                },
                isActive(mantenimientos_id, userId, ) {
                    const maintenance = this.mantenimientos.find(mt => mt.id === mantenimientos_id);
                    if (maintenance) {
                        return maintenance.asignado_users_id === userId;
                    }
                    return false;
                },
                filterMantenimientos() {
                    if (this.selectedUser) {
                        // Filtra los mantenimientos asignados al usuario seleccionado
                        this.mantenimientosAsignados = this.mantenimientos.filter(mt => {
                            return mt.asignado_users_id === this.selectedUser;
                        });

                        // Llena selectedM con los IDs de los mantenimientos asignados al usuario seleccionado
                        this.selectedM = this.mantenimientosAsignados.map(mt => mt.id);

                        // Filtra los mantenimientos no asignados a ningún otro usuario
                        this.mantenimientosNoAsignados = this.mantenimientos.filter(mt => {
                            return mt.asignado_users_id === null;
                        });

                        // Creo un nuevo arreglo que contiene tanto los mantenimientos asignados como los no asignados
                        this.mantenimientosMostrados = [...this.mantenimientosAsignados, ...this
                            .mantenimientosNoAsignados
                        ];
                        this.obtenerActividades(this.selectedUser);
                    } else {
                        // Si no se ha seleccionado un usuario, muestra todos los mantenimientos
                        this.mantenimientosAsignados = [];
                        this.selectedM = [];

                        // Llena la lista de mantenimientos no asignados con los que no tienen asignado_users_id
                        this.mantenimientosNoAsignados = this.mantenimientos.filter(mt => mt.asignado_users_id ===
                            null);

                        // Mostrar todos los mantenimientos si no se ha seleccionado un usuario
                        this.mantenimientosMostrados = [...this.mantenimientos];
                    }
                    this.actualizarMantenimientosNoAsignados();

                },
                // Esta función asigna y desasigna un mantenimiento a un usuario
                asignarMantenimientoUsuario(mantenimiento) {
                    if (!this.users.find((u) => u.id === this.selectedUser)) {
                        this.setMessage('El usuario seleccionado no existe', 'error');
                        return;
                    }

                    // Verifica si el mantenimiento ya está asignado al usuario
                    if (mantenimiento.asignado_users_id === this.selectedUser) {

                        axios.post(
                                '{{ route('mantenimientos.desasignarMantenimiento') }}', {
                                    mantenimientoId: mantenimiento.id,
                                    userId: this.selectedUser,
                                })
                            .then((response) => {
                                if (response.data.success) {
                                    mantenimiento.asignado_users_id = null;
                                    const index = this.selectedM.indexOf(mantenimiento.id);
                                    if (index !== -1) {
                                        this.selectedM.splice(index, 1);
                                    }
                                    this.setMessage('Mantenimiento desasignado correctamente', 'success');
                                    this.actualizarMantenimientosNoAsignados();
                                    return this.obtenerActividades(this.selectedUser);

                                }
                            })
                            .catch((error) => {
                                console.error('Error al desasignar el mantenimiento:', error);
                                this.setMessage('Error al desasignar el mantenimiento', 'error');
                            });




                    } else {
                        // El mantenimiento no está asignado al usuario, así que procede a asignarlo

                        axios.post(
                                '{{ route('mantenimientos.asignarMantenimientos') }}', {
                                    userId: this.selectedUser,
                                    mantenimientoId: mantenimiento.id,
                                })
                            .then((rs) => {
                                if (rs.data.success) {
                                    mantenimiento.asignado_users_id = this.selectedUser;
                                    this.setMessage('Mantenimiento asignado correctamente', 'success');
                                    this.actualizarMantenimientosNoAsignados();
                                    this.obtenerActividades(this.selectedUser);



                                } else {
                                    this.setMessage('Error al asignar el mantenimiento', 'error');
                                }
                            })


                            .catch((error) => {
                                console.error('Error al asignar el mantenimiento:', error);
                                this.setMessage('Error al asignar el mantenimiento', 'error')
                            });

                    }

                },
                eliminarAsignacion(mantenimientoId) {
                    axios.post(
                            '{{ route('mantenimientos.desasignarMantenimiento') }}', {
                                mantenimientoId: mantenimientoId.id,
                                userId: this.selectedUser,
                            })
                        .then((response) => {
                            if (response.data.success) {
                                mantenimientoId.asignado_users_id = null;
                                const index = this.selectedM.indexOf(mantenimientoId.id);
                                if (index !== -1) {
                                    this.selectedM.splice(index, 1);
                                }
                                this.setMessage('Mantenimiento desasignado correctamente', 'success');
                                this.actualizarMantenimientosNoAsignados();
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                                return this.obtenerActividades(this.selectedUser);


                            }
                        })
                        .catch((error) => {
                            console.error('Error al desasignar el mantenimiento:', error);
                            this.setMessage('Error al desasignar el mantenimiento', 'error');
                        });
                },

                tiempoTranscurrido(mt) {
                    const ahora = new Date();
                    const fechaCreacion = new Date(mt.created_at);
                    const diferenciaMilisegundos = ahora - fechaCreacion;
                    /**cree este arreglo unidades para acceder a los divisores para hacer los calculos */
                    const UNIDADES = [{
                            divisor: 31536000,
                            singular: "año",
                            plural: "años"
                        },
                        {
                            divisor: 2592000,
                            singular: "mes",
                            plural: "meses"
                        },
                        {
                            divisor: 604800,
                            singular: "semana",
                            plural: "semanas"
                        },
                        {
                            divisor: 86400,
                            singular: "día",
                            plural: "días"
                        },
                        {
                            divisor: 3600,
                            singular: "hora",
                            plural: "horas"
                        },
                        {
                            divisor: 60,
                            singular: "minuto",
                            plural: "minutos"
                        },
                        {
                            divisor: 1,
                            singular: "segundo",
                            plural: "segundos"
                        }
                    ];

                    const unidad = UNIDADES.find(u => Math.floor(diferenciaMilisegundos / (u.divisor * 1000)) >= 1);

                    if (unidad) {
                        const valor = Math.floor(diferenciaMilisegundos / (unidad.divisor * 1000));
                        return `${valor} ${valor === 1 ? unidad.singular : unidad.plural}`;
                    }

                    return "Recién creado";
                },

                setMessage(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };

                    setTimeout(() => {
                        this.message = {};
                    }, 1 * 1000)
                },


            },
            created() {
                this.filterShow = parseInt(localStorage.getItem('filterShow')) === 1;
            }
        });
    </script>
@endsection
