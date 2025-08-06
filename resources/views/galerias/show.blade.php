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
            <div class="row mb-4">
                <div class="col-12 text-uppercase">
                    <h5>montajes para galerias</h5>
                </div>
                <div class="col-3 mb-3">
                    <button type="button" class="card p-2 w-100 border border-1 border-success" data-bs-toggle="modal"
                        data-bs-target="#bodegas">
                        <div class="card-body body_cajas">
                            <p class="card-text">
                                <span class="mdi mdi-plus"></span> Agregar montajes
                            </p>
                        </div>
                    </button>
                </div>
                <div class="col-3 mb-3" v-for="ba in montajesAdd" :key="ba.id">
                    <div class="card p-2 w-100 border border-1 border-success">
                        <div class="card-body body_cajas">
                            <a class="nav-link float-end text-muted" href="#" @click="setMontaje(ba.montajes_id)" title="Quitar asignacion">
                                <span class="mdi mdi-delete"></span>
                            </a>
                            <p class="card-text">
                                <span class="mdi mdi-account-cog-outline"></span> @{{ ba.montajes ? ba.montajes.montaje : 'Montaje no definido' }}

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
                        <h5 class="modal-title text-uppercase" id="exampleModalLabel">Agregar montajes a galerias</h5>
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
                                                v-model="searchMontaje">
                                            <a href="#" class="input-group-text" id="basic-addon2"
                                                v-show="txtBusquedap.length > 0 && FilterPrecio.length > 0"
                                                @change="clearPrecio()">
                                                <span class="mdi mdi-backspace-outline"></span>
                                            </a>
                                        </div>
                                        <ul class="list-group shadow" v-for="b in getMontajesList " :key="b.id">
                                            <li class="list-group-item rounded-0 border-0 usuario" @change="setMontaje(b.id)">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input"
                                                             :checked="getCheckMontaje(b.id)"
                                                            :value="b.id">
                                                        @{{ b.montaje}}
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-uppercase" style="max-height: 100%; overflow:auto;">
                                <h5 clas="text-uppercase">montajes agregados a esta galeria</h5>
                                <small v-if="montajesAdd.length === 0">Aún no se han agregado montajes a esta galeria</small>
                                <ul class="list-group" v-for="ba in montajesAdd" :key="ba.id">
                                    <li class="list-group-item">
                                        @{{ ba.montajes ? ba.montajes.montaje : 'Montaje no definido' }}
                                        <a class="nav-link float-end text-muted" href="#"
                                            @click="setMontaje(ba.montajes_id)">
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
                galeria: "{{ Crypt::encryptString($p->id) }}",
                ListFilter: [],
                message: {},
                montajes_galerias: [],
                montajeSelected: [],
                searchMontaje: "",
                txtBusquedap: "",
                FilterPrecio: [],
                montajesList: @json($montajes),
                montajesAdd:@json($montajes_galerias)
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
                setMontaje(id) {
                    axios.post("{{ route('galerias.montajes') }}", {
                        montaje: id,
                        galeria: this.galeria,
                    }).then((r) => {
                        if (r.data) {
                            if (r.data.list)
                                this.montajesAdd = r.data.list;

                            if (r.data.message)
                                this.setMessage(r.data.message, r.data.type);
                        }
                    });
                },
                getCheckMontaje(id) {
                    return this.montajesAdd.find(r =>r.montajes_id === id) !== undefined;
                }
            },
            computed: {
                getMontajesList() {
                    var reg = new RegExp(this.searchMontaje, "i");
                    return this.montajesList.filter(r => reg.test(r.montaje));
                }
            }
        });
    </script>
@endsection
