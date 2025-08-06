@extends('layouts.mantenimientos')
@section('css-mantenimiento')
    <style>
            .supervisiones-panel {
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



        /* Estilo para la tabla */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            /* Borde de la tabla */
        }

        .table th,
        .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .table tbody tr:hover {
            background-color: #f5f5f5;
        }
        .contenedor-mantenimientos {
            min-height: 80vh;
            display: flex;
            flex-direction: column;
        }
        .custom-input-container {
                display: flex;
                align-items: center;
            }

            .custom-input-container input {
                flex: 1;
                margin-right: 10px; /* Espacio entre el input y el botón */
            }
        [v-cloak] {
            display: none;
        }
    </style>
@endsection
@section('panel_mantenimiento')
    <div id="appSupervision" class="contenedor-mantenimientos" v-cloak>
        <!-- Mensajes de alerta. -->
        <div class="row">
            <x-message></x-message>
        </div>
        <div>
            <a class="regresar text-uppercase" href="{{ route('mantenimientos.index') }}">
                <span class="mdi mdi-arrow-left-box icono"></span> Volver a mantenimientos
            </a>
        </div>


        <div class=" p-3  shadow supervisiones-panel  ">
            <h3>SUPERVISIONES</h3>
            <h6>Todos los tipos de mantenimientos que puede supervisar</h6>

            <div class="col-12 mt-3">
                <small v-if="mantenimientosSupervisados.length === 0"> Aun no se han supervisado mantenimientos</small>

                <ul class="list-group">
                    <li class="list-group-item" v-for="p in mantenimientosSupervisados" :key="p.id">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-uppercase ">@{{ p.tipo_mantenimientos.mantenimiento }}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container">

            <!-- mostrar los mantenimientos por la fecha de hoy -->
                <div class="row">
                    <div class="col-sm-6 col-md-4 p-4 text-uppercase">
                        <h6>mantenimientos de Hoy</h6>
                        <input type="date" v-model="inicio" name="inicio" class="form-control"  :max="final">
                        <button  class="btn btn-primary mt-2" @click="filtrarToDay()">Hoy</button>
                    </div>
                    <div class="col-sm-6 col-md-6 p-4 text-uppercase">
                        <h6>Seleccione las fechas para buscar los mantenimientos</h6>
                        <div class="custom-input-container">
                            <input type="date" v-model="final" name="final"class="form-control" :min="inicio">
                            <button  class="btn btn-primary" @click="filtrarByDates()" >Buscar por fechas</button>
                        </div>
                    </div>
                </div>
            <div class="row">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mantenimiento</th>
                            <th>Habitacion</th>
                             <th>
                                Asignado
                                <select v-model="userSelected" class="form-select text-uppercase" @change="filtrarByUser">

                                    <option v-for="u in usuarios" :value="u.id">@{{ u.name }}</option>
                                </select>
                            </th>
                            <th>Estado</th>
                            <th>Creación</th>
                            <th>Tiempo de confirmacion</th>
                            <th>Confirmacion</th>
                            <th>Finalizacion</th>
                            <th>Tiempo tardado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="mt in mantenimientosFiltrados" :key="mt.id" >
                            <td>@{{ mt.tipo_mantenimientos.mantenimiento }}</td>
                            <td>@{{ mt.habitaciones.numero_habitacion }}</td>
                            <td>@{{ mt.asignado ? mt.asignado.name : 'No asignado' }}</td>
                            <td>@{{ mt.estado }}</td>
                            <td>@{{ mt.creacion }}</td>
                            <td>@{{ mt.confirmaciontime }}</td>
                            <td>@{{ mt.confirmacion_asignacion }}</td>
                            <td>@{{ mt.finalizacion }}</td>
                            <td>@{{ mt.transcurrido }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    </div>
@endsection
@section('script')
    <script>
        const supervisionMantenimientos = new Vue({

            el: '#appSupervision',
            data: {
            inicio: ""
            , final: ""
            , mantenimientosSupervisados: @json($mantenimientosSupervisados)
            , usuarios: @json($usuarios)
            , mantenimientos: @json($mantenimientos)
            ,userSelected:''
            ,mantenimientosFiltrados:@json($mantenimientos),

            },
            methods: {
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
                 filtrarByUser() {
                    if (this.userSelected) {
                        this.mantenimientosFiltrados = this.mantenimientos.filter(mt => mt.asignado && mt.asignado.id === this.userSelected);
                    } else {
                        this.mantenimientosFiltrados = this.mantenimientos;
                    }
                },
                 filtrarToDay: function() {
                const fechaActualUTC = new Date();


                const offsetHorarioElSalvador = -6 * 60;
                const offsetActual = fechaActualUTC.getTimezoneOffset();
                const ajusteOffset = (offsetHorarioElSalvador - offsetActual) * 60 * 1000;
                const fechaActualElSalvador = new Date(fechaActualUTC.getTime() + ajusteOffset);

                // Formatear la fecha actual en el formato 'YYYY-MM-DD'
                const hoy = fechaActualElSalvador.toISOString().split('T')[0];
                this.mantenimientosFiltrados = this.mantenimientos.filter(mt => {
                    const fecha = mt.fecha.slice(0, 10); // Obtener solo la fecha de creación del mantenimiento
                    return fecha == hoy;
                });
            },
            filtrarByDates: function() {
                // Filtrar mantenimientos según las fechas establecidas
                this.mantenimientosFiltrados = this.mantenimientos.filter(mt => {
                    const fecha = mt.fecha.slice(0, 10); // Obtener solo la fecha de creación del mantenimiento
                    return fecha >= this.inicio && fecha <= this.final;
                });
            },



            }

        });
    </script>
@endsection
