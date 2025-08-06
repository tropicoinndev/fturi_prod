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
                    <h5 class="card-title">Agregar un cliente o un titular</h5>

                    <form action="{{ route('reservaciones.store') }}" method="POST" id="reservacionesCreateForm">
                        @csrf
                        <input type="hidden" name="clientes_id" :value="clientesSelected.id">
                        <input type="hidden" name="titular" :value="titular">
                        <input type="hidden" name="contacto" :value="contacto">

                        <label for="txtBusqueda" class="form-label">Cliente:</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" autocomplete="off"
                                placeholder="Escriba para buscar un cliente..." id="txtBusqueda"
                                aria-label="Recipient's username" aria-describedby="basic-addon2" v-model="txtBusqueda"
                                @keyup="apiSearchClientes">
                            <span v-if="arrayClientes.length == 0" class="input-group-text" id="basic-addon2"
                                style="cursor: pointer;" @click="setTitular">Agregar un titular</span>
                        </div>

                        <!--Desplegable / Listado de clientes.-->
                        <div class="result shadow"
                            style="position: absolute;z-index: 100; margin-top: -13px; width: 93%; background:white;">
                            <ul class="list-group">
                                <li v-if="arrayClientes.length > 0" v-for="cliente in arrayClientes"
                                    class="list-group-item d-flex justify-content-between align-items-center"
                                    style="border-radius: 0px; border: none; cursor: pointer;" @click="selected(cliente)">
                                    @{{ cliente.cliente }}
                                </li>
                                <li v-show="arrayClientes.length == 0 && txtBusqueda.length > 4 && txtBusqueda.length < 8 && clientesSelected == 0 && this.titular.length == 0"
                                    class="list-group-item" style="border-radius: 0px; border: none; cursor: pointer;">
                                    No se encontro un cliente con esta especificacion <b>@{{ txtBusqueda }}</b>, puede
                                    continuar escribiendo y agregarlo como un titular presionando en <code>Agregar un
                                        titular</code>.
                                </li>
                            </ul>
                        </div>

                        <div v-if="clientesSelected.length == 0 && this.titular.length > 0" class="mb-3">
                            <label for="contacto" class="form-label">Contacto:</label>
                            <textarea id="contacto" class="form-control" name="contacto" rows="3" v-model="contacto" maxlength="100"
                                placeholder="Agregue aquí las formas de contacto del titular Ej. 0000-0000 - correo@servidor.com"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="">Medio de reserva</label><br>
                            <div class="btn-group mt-2" role="group" aria-label="Basic radio toggle button group">
                                @foreach ($tipo_reservacion as $tr)
                                    <input type="radio" class="btn-check" name="tipo_reservaciones_id"
                                        value="{{ \Crypt::encryptString($tr->id) }}" id="btnradio-{{ $tr->id }}"
                                        autocomplete="off" required>
                                    <label class="btn btn-outline-success"
                                        for="btnradio-{{ $tr->id }}">{{ $tr->tipo_reservacion }}</label>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="forma_pagos" class="form-label">Forma pagos:</label>
                            <select class="form-select" id="forma_pagos" name="forma_pagos" required>
                                <option selected value="">Seleccione una forma de pago</option>
                                @foreach ($forma_pagos as $fp)
                                    <option value="{{ $fp->cid }}">{{ $fp->forma }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" @click="enviarForm()"
                            :disabled="(clientesSelected.length == 0 && titular == '') || send" id="btnSubmit">
                            Siguiente
                            <span class="mdi mdi-arrow-right-thick"></span>
                        </button>
                        <div id="labelProcess" v-if="send">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Procesando...</span>
                            </div>
                            <span>Procesando...</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var app = new Vue({
            el: '#appReservacionesCreate',
            data: {
                txtBusqueda: '',
                arrayClientes: [],
                clientesSelected: [],
                titular: '',
                clientes_id: '',
                contacto: '',
                send: false,
                message: {},
            },
            mounted() {

            },
            methods: {
                enviarForm: function() {
                    this.send = true;
                    document.getElementById('reservacionesCreateForm').submit();
                },
                apiSearchClientes() {
                    if (this.txtBusqueda.length > 3) {
                        axios.post("{{ route('clientes.apiGetClientes') }}", {
                                busqueda: (this.txtBusqueda).toUpperCase(),
                            })
                            .then((rs) => {
                                this.arrayClientes = [];
                                this.arrayClientes = rs.data.clientes;
                            })
                            .catch(error => {
                                console.log('Error JS: ', error);
                            })
                    } else this.arrayClientes = [];
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
            },
            computed: {

            },
        });
    </script>
@endsection
