@extends('layouts.hab')

@section('content-hab')
    <style>
        .message {
            position: fixed;
            top: 10%;
            right: 1%;
            width: 20%;
            z-index: 100;
        }
    </style>

    <div id="appReservacionesCreate" class="container">
        <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
            v-show="message.message && message.type">
            <strong>@{{ message.message }}</strong>
        </div>

        <div class="row justify-content-center">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ingreso a Habitacion {{ $hab->numero_habitacion }}</h5>
                    <x-message></x-message>
                    <form action="{{ route('recepciones.store') }}" method="POST" id="form-recepciones">
                        @csrf
                        <input type="hidden" name="clientes_id" :value="clientesSelected.id">
                        <input type="hidden" name="habitaciones_id" v-model="habitaciones_id">

                        <label for="txtBusqueda" class="form-label">Cliente:</label>
                        <div class="cliente-selected col-12" v-show="clientesSelected.id > 0">
                            <h1 class="badge badge-pill rounded text-bg-primary">
                                @{{ clientesSelected.cliente }}
                                <span class="mdi mdi-close" @click="clientesSelected = []"></span>
                            </h1>

                        </div>
                        <div class="cliente-search" v-show="clientesSelected.length == 0">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" autocomplete="off"
                                    placeholder="Escriba para buscar un cliente..." id="txtBusqueda"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2" v-model="txtBusqueda"
                                    @keyup="apiSearchClientes">
                            </div>

                            <!--Desplegable / Listado de clientes.-->
                            <div v-if="arrayClientes.length > 0" class="result shadow"
                                style="position: absolute;z-index: 1; margin-top: -13px; width: 93%; background:white; padding: 2px;">
                                <ul class="list-group">
                                    <li v-for="cliente in arrayClientes"
                                        class="list-group-item d-flex justify-content-between align-items-center"
                                        style="border-radius: 0px; border: none; cursor: pointer;"
                                        @click="selected(cliente)">
                                        @{{ cliente.cliente }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="habitacion-data col-12 mt-2" v-show="clientesSelected.id > 0">
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="">Fecha de salida</label>
                                    <input type="date" class="form-control" name="fecha_salida" :min="minSalida()"
                                        v-model="fechaSalida" required @change="getDisponible()">
                                    <span v-if="this.fechaSalida != null && this.fechaSalida.length > 6"
                                        :class="{ 'text-info': valid, 'text-danger': !valid }">
                                        <span v-if="valid" class="mdi mdi-check"> Fecha valida</span>
                                        <span v-if="!valid" class="mdi mdi-block-helper"> Fecha invalida, es posible que
                                            tenga alguna reservación activa. Intente con otra habitación o corrija la
                                            fecha.</span>
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="col-12 my-3">
                                        Seleccione una tarifa
                                    </div>
                                    <div class="col-3 mb-2" role="group" aria-label="Basic radio toggle button group"
                                        v-for="t in tarifas" :key="t.tarifas_id">
                                        <input type="radio" class="btn-check" name="tarifas_id" :value="t.tarifas_cid"
                                            :id="'tarifa-' + t.tarifas_id" autocomplete="off" v-model="tarifas_id">
                                        <label class="card btn btn-outline-primary" :for="'tarifa-' + t.tarifas_id">
                                            <div class="card-body">
                                                <div class="card-title">
                                                    $@{{ parseFloat(t.precio).toFixed(2) }} /
                                                    @{{ t.numero_dias }} dias
                                                </div>
                                                <div class="card-text">
                                                    @{{ t.tarifa }}
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="btn-guardar"
                            :disabled="((clientesSelected.length == 0 && titular == '') ||
                                !this.valid ||
                                tarifas_id == null ||
                                tarifas_id.length == 0)">
                            Siguiente
                            <span class="mdi mdi-arrow-right-thick"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        //---Evitar duplicar registros al hacer click rápidamente y muchas veces en el botón de guardar---
        //Siempre esperar que cargue todo el DOM
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#form-recepciones');
            const btnGuardar = document.querySelector('#btn-guardar');

            form.addEventListener('submit', function(e) {
                btnGuardar.disabled = true;
                btnGuardar.innerText = 'Guardando...';
            });
        });
        //-----

        var app = new Vue({
            el: '#appReservacionesCreate',
            data: {
                txtBusqueda: '',
                arrayClientes: [],
                clientesSelected: [],
                titular: '',
                contacto: '',
                message: {},
                fechaSalida: null,
                tarifas_id: null,
                habitaciones_id: "{{ Crypt::encryptString($hab->id) }}",
                valid: false,
                tarifas: [],
            },
            methods: {
                apiSearchClientes() {
                    if (this.txtBusqueda.length > 4) {
                        axios.post("{{ route('clientes.apiGetClientes') }}", {
                                busqueda: (this.txtBusqueda).toUpperCase(),
                            })
                            .then((rs) => {
                                this.arrayClientes = rs.data.clientes;
                            })
                            .catch(error => {
                                console.log('Error JS: ', error);
                            })
                    }
                },
                getDisponible() {
                    if (this.fechaSalida != null &&
                        this.fechaSalida.length > 6 &&
                        (new Date(this.fechaSalida)) >= (new Date(this.minSalida()))
                    ) {
                        axios.post("{{ route('recepciones.api_validate') }}", {
                            habitaciones_id: this.habitaciones_id,
                            fecha_salida: this.fechaSalida,
                        }).then(r => {
                            if (r.data) {
                                this.valid = r.data.valid;
                                if (this.valid)
                                    this.tarifas = r.data.tarifas.get_tarifas;
                                console.log(this.tarifas);

                            } else this.valid = false;
                        }).catch(err => console.log(err));
                    } else this.valid = false;

                },
                selected(s) {
                    this.clientesSelected = s;
                    this.arrayClientes = [];
                    this.txtBusqueda = this.clientesSelected.nombre;
                },
                setTitular() {
                    this.titular = this.txtBusqueda;
                    this.contacto = this.contacto;
                    this.setMessage('Titular agregado', 'success');
                },
                setMessage(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };
                    setTimeout(() => {
                        this.message = {};
                    }, 4000)
                },
                minSalida: function() {
                    const fecha = new Date();
                    fecha.setDate(fecha.getDate() + 1);
                    const format =
                        `${fecha.getFullYear()}-${String(fecha.getMonth() + 1).padStart(2, '0')}-${String(fecha.getDate()).padStart(2, '0')}`;
                    return format;
                }
            },
            computed: {

            },
        });
    </script>
@endsection
