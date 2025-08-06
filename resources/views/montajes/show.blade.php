@extends('layouts.form')

@section('form')
    <style>
        .ch-60 {
            height: 90vh;
            overflow-x: auto;
            overflow-y: auto;
        }

        .usuario:hover {
            background: #4DB6AC;
            color: #fff;
        }

        .btn-del {
            display: none;
        }

        .usuario:hover .btn-del {
            display: block;
        }

        .message {
            position: fixed;
            top: 20%;
            right: 1%;
            width: 15%;
            z-index: 100;
        }
    </style>

    <div id="montajesShow">
        @can('montajes.show')
            <div class="row mb-4 h-100 w-100">
                <div class="col-12">
                    <h5>Montajes Galerias</h5>
                </div>
                <div class="col-3 mb-3">
                    <button type="button" class="card p-2 w-100  h-100 border border-1 border-success" data-bs-toggle="modal"
                        data-bs-target="#bodegas">
                        <div class="card-body body_cajas mb-3">
                            <p class="card-text">
                                <span class="mdi mdi-plus "></span> Agregar galerías
                            </p>
                        </div>
                    </button>
                </div>
                <div class="col-3 mb-2" v-for="ba in galeriasAdd" :key="ba.id">
                    <div class="card p-2 w-100 h-100 border border-1 border-success">
                        <div class="card-body body_cajas">
                            <a class="nav-link float-end text-muted" href="#" @change="setGaleria(ba.galerias_id)" title="Quitar asignacion">
                                <span class="mdi mdi-delete"></span>
                            </a>
                            <p class="card-text">
                                <img v-if="ba.galerias" :src="'/img/' + ba.galerias.foto" alt="Galería"
                                    class="img-thumbnail mr-2" style="max-width: 250px; max-height: 250px;">

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        <div class="modal fade" id="bodegas" tabindex="-1" aria-labelledby="bodegas" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar galería a montaje</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                            v-show="message.message && message.type">
                            <strong>@{{ message.message }}</strong>
                        </div>
                        <div class="row">
                            <div class="col-8 ch-60">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Buscar..."
                                                v-model="searchGaleria">
                                            <a href="#" class="input-group-text" id="basic-addon2"
                                                v-show="txtBusquedap.length > 0 && FilterPrecio.length > 0"
                                                @change="clearPrecio()">
                                                <span class="mdi mdi-backspace-outline"></span>
                                            </a>
                                        </div>
                                        <ul class="list-group shadow" v-for="b in getGaleriasList " :key="b.id">
                                            <li class="list-group-item rounded-0 border-0 usuario" @change="setGaleria(b.id)">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input"
                                                             :checked="getCheckGaleria(b.id)"
                                                            :value="b.id">
                                                        <img v-if="b.foto" :src="'/img/' + b.foto" alt="Galería"
                                                            class="img-thumbnail mr-2"
                                                            style="max-width: 150px; max-height: 150px;">
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4" style="max-height: 100%; overflow:auto;">
                                <h5>Galerías agregadas a este montaje</h5>
                                <small v-if="galeriasAdd.length === 0">Aún no se han agregado galerías a este
                                    montaje</small>
                                <ul class="list-group" v-for="ba in galeriasAdd" :key="ba.id">
                                    <li class="list-group-item">
                                        <img v-if="ba.galerias" :src="'/img/' + ba.galerias.foto" alt="Galería"
                                            class="img-thumbnail mr-2" style="max-width: 150px; max-height: 150px;">
                                        <a class="nav-link float-end text-muted" href="#"
                                            @change="setGaleria(ba.galerias_id)">
                                            <span class="mdi mdi-delete"></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var app = new Vue({
            el: "#montajesShow",
            data: {
                txtBusqueda: "",
                list: [],
                selected: [],
                montaje: "{{ Crypt::encryptString($p->id) }}",
                ListFilter: [],
                message: {},
                montajes_galerias: [],
                galeriaSelected: [],
                searchGaleria: "",
                txtBusquedap: "",
                FilterPrecio: [],
                galeriasList: @json($galerias),
                galeriasAdd: @json($montajes_galerias)
            },
            methods: {
                setMessage(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };
                    setTimeout(() => {
                        this.message = {};
                    }, 2000);
                },
                clearPrecio() {
                    this.txtBusquedap = "";
                    this.getFilterPrecio();
                },
                setGaleria(id) {
                    axios.post("{{ route('montajes.galeria') }}", {
                        galeria: id,
                        montaje: this.montaje,
                    }).then((r) => {
                        if (r.data) {
                            if (r.data.list)
                                this.galeriasAdd = r.data.list;

                            if (r.data.message)
                                this.setMessage(r.data.message, r.data.type);
                        }
                    });
                },
                getCheckGaleria(id) {
                    return this.galeriasAdd.find(r => r.galerias_id === id) !== undefined;
                }
            },
            computed: {
                getGaleriasList() {
                    var reg = new RegExp(this.searchGaleria, "i");
                    return this.galeriasList.filter(r => reg.test(r.descripcion));
                }
            }
        });
    </script>
@endsection
