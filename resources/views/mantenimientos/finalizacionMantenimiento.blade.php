@extends('layouts.mantenimientos')
@section('style')
    <style>
        .sidebarPanel {
            position: fixed;
            width: 280px;
            min-height: 90vh;

        }

        .asignaciones-panel {
            position: fixed;
            width: 260px;
            min-height: 90vh;
            right: 0;
            transition: width 0.3s ease-in-out, visibility 0s linear 0.3s, opacity 0.3s ease-in-out;
            z-index: 1000;
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

        .hidden-checkbox {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .ct {
            color: black;
        }

        .selected .ct {
            color: #ffffff;
        }

        @media (max-width: 767px) {
            .sidebarPanel {
                width: 0;
                visibility: hidden;
                opacity: 0;
                transition: width 0.3s ease-in-out, visibility 0s linear 0.3s, opacity 0.3s ease-in-out;
                z-index: 1000;
            }

            .sidebar-visible {
                width: 280px !important;
                visibility: visible !important;
                opacity: 1 !important;
                transition: width 0.3s ease-in-out, visibility 0s linear, opacity 0.3s ease-in-out;
            }

            .asignaciones-panel {
                width: 0;
                visibility: hidden;
                opacity: 0;
                transition: width 0.3s ease-in-out, visibility 0s linear 0.3s, opacity 0.3s ease-in-out;

            }
        }

        .show-assignments-panel {
            position: fixed;
            top: 10%;
            /* Ajusta la distancia desde la parte superior */
            right: 0px;
            /* Ajusta la distancia desde la derecha */
            z-index: 1001;
            /* Asegura que el botón esté por encima del panel */
        }

        .show-assignments-panel {
            /* ...otros estilos... */
            background-color: #007bff;
            color: white;
            border: none;
            padding: 7px 9px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .show-assignments-panel:hover {
            background-color: #0056b3;
        }

        .input-button-container {
            display: flex;
            align-items: center;
        }

        .input-button-container input {
            margin-right: 5px;
            font-size: 16px;
            border-radius: 10px;
            border: 2px solid #007bff;
        }

        .input-button-container button {
            margin-left: 20px;
            font-size: 11px;
            border-radius: 10px;
            border: 2px solid #007bff;
        }
    </style>

    @yield('styles')
@endsection

@section('panel_mantenimiento')
    <button id="showAssignmentsPanel" class="btn btn-primary d-md-none show-assignments-panel">
        <span>Finalizados</span>
    </button>
    <div id="appFinalizacion">
        <!-- Mensajes de alerta. -->
        <div v-if="message.type" :class="['alert', 'alert-' + message.type]">
            @{{ message.message }}
        </div>
        <div>
            <a class="regresar text-uppercase" href="{{ route('mantenimientos.index') }}">
                <span class="mdi mdi-arrow-left-box icono"></span> Volver a mantenimientos
            </a>
        </div>


        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light shadow asignaciones-panel  ">
            <h3>FINALIZADOS</h3>
            <small>Todos los mantenimiens FIinalizados</small>

            <div class="col-12 mt-3">
                <small v-if=" mantenimientosFinalizados.length === 0"> Aun no se ha asignado
                    habitacion a este mantenimiento y
                    empleado</small>

                <ul class="list-group">
                    <li class="list-group-item" v-for="p in mantenimientosFinalizados" :key="p.id">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-uppercase ">@{{ p.observacion }}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-6 p-4 text-uppercase">
                    <h6>Detalles acciones surgidas en el mantenimiento realizado</h6>
                    <div class="input-button-container">
                         <textarea name="bitacora_asignado" class="form-control" v-model="bitacora_asignado"></textarea>

                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4" v-for="mt in mantenimientos"
                    :key="mt.id">
                    <div class="card custom-card" :class="{ selected: selectedM.includes(mt.id), active: isActive(mt.id) }">
                        <div class="card-body">
                            <label :for="'m_' + mt.id" class="d-block custom-checkbox-label">
                                <input class="ocultar hidden-checkbox" type="checkbox" :id="'m_' + mt.id"
                                    :value="mt.id" v-model="selectedM" @click="finalizarMantenimientoUsuario(mt)">
                                <div class="custom-checkbox"></div>
                                <h5 class="card-title text-uppercase ct">
                                    @{{ mt.tipo_mantenimientos.mantenimiento }}

                                </h5>
                                <div class="mt-3">
                                    <span class="ct">Asignado a:</span>
                                    <span class="text-uppercase ct">@{{ mt.asignado.name }}</span>
                                </div>
                                <div class="mt-2 ct">
                                    <span class="ct">Fecha de confirmacion:</span>
                                    <span>@{{ mt.confirmacion_asignacion }}</span>
                                </div>
                                <div class="mt-2 ct">
                                    <span class=" ct">Desde que se confirmo:</span>
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
        const finalizacionesMantenimientos = new Vue({

            el: '#appFinalizacion',
            data: {

                message: {},
                users: @json($users),
                mantenimientos: @json($mantenimientos),
                bitacora_asignado: null,
                selectedM: [],
                mantenimientosFinalizados: @json($mantenimientosFinalizados), // Initialize here
                mantenimientosNoAsignados: [],
            },
            methods: {
                isActive(mantenimientoId) {
                    const maintenance = this.mantenimientos.find(mt => mt.id === mantenimientoId);
                    return maintenance && maintenance.finalizacion;
                },

                // Esta función finaliza un mantenimiento y tambien lo elimina 
                async finalizarMantenimientoUsuario(mantenimiento) {
                      try {
                        // Verifica si el mantenimiento ya está finalizado
                        const esFinalizado = this.mantenimientosFinalizados.some(item => item.id === mantenimiento.id);

                        if (esFinalizado) {
                            // Si ya está finalizado, elimina la finalización
                            const response = await axios.post('{{ route('mantenimientos.eliminarFinalizarMantenimientos') }}', {
                                mantenimiento_id: mantenimiento.id,
                            });
                            const index = this.mantenimientosFinalizados.findIndex(item => item.id === mantenimiento.id);
                            if (index !== -1) {
                                this.mantenimientosFinalizados.splice(index, 1);
                                this.setMessage('Finalización del mantenimiento eliminada con éxito', 'success');
                            }
                        } else {
                            // Si no está finalizado, finaliza el mantenimiento
                            if (!this.bitacora_asignado) {
                                this.setMessage('El campo de bitácora es obligatorio', 'danger');
                                return;
                            }
                            // Envío la data por post para finalizar el mantenimiento
                            const response = await axios.post('{{ route('mantenimientos.finalizarMantenimientos') }}', {
                                bitacora_asignado: this.bitacora_asignado,
                                mantenimiento_id: mantenimiento.id,
                            });
                            if (response.status === 200) {
                                this.bitacora_asignado = '';
                                this.mantenimientosFinalizados.push(mantenimiento);
                                this.setMessage('Mantenimiento finalizado con éxito', 'success');
                            } else {
                                this.setMessage('Error al finalizar el mantenimiento', 'danger');
                            }
                        }
                    } catch (error) {
                        console.error('Error en la gestión del mantenimiento:', error);
                        this.setMessage('Error en la gestión del mantenimiento', 'danger');
                    }

                },

                tiempoTranscurrido(mt) {
                    const ahora = new Date();
                    const fechaCreacion = new Date(mt.confirmacion_asignacion);
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
                        this.txtHabitacion = '';
                    }, 1 * 1000)
                },


            },
            mounted: function() {
                /**cree esta funcion para cuando se aplique el responsive aparescan los botones de ocultar los paneles */
                document.addEventListener("DOMContentLoaded", () => {
                    const assignmentsPanel = document.querySelector(".asignaciones-panel");
                    const showAssignmentsPanelBtn = document.getElementById("showAssignmentsPanel");
                    let isPanelVisible = false;

                    showAssignmentsPanelBtn.addEventListener("click", () => {
                        if (isPanelVisible) {
                            assignmentsPanel.style.width = "0";
                            assignmentsPanel.style.visibility = "hidden";
                            assignmentsPanel.style.opacity = "0";
                        } else {
                            assignmentsPanel.style.width =
                                "260px"; // Ajusta el ancho según tus necesidades
                            assignmentsPanel.style.visibility = "visible";
                            assignmentsPanel.style.opacity = "1";
                        }

                        isPanelVisible = !isPanelVisible; // Cambiar el estado
                    });
                });
            },

            computed: {

            }
        });
    </script>
@endsection
