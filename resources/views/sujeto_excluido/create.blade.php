@extends('layouts.cajas')

@section('panel_caja')
    <div id="appSujetoExcluido">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="text-uppercase">Sujetos excluidos</h3>
                    <p class="text-uppercase">
                        Nuevo Factura Electronica Sujeto Excluido
                        #{{ $correlativo->actual == 0 ? $correlativo->inicio : $correlativo->actual + 1 }}
                    </p>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 table-responsive">
                    <form action="{{ route('sujeto_excluido.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="clientes_id" v-model="cliente">
                        <div class="row">

                            <clientes url="{{ route('clientes.api_search_list_naturales') }}" @cliente="setcliente">
                            </clientes>

                            <div class="col-12 mt-3">
                                <div class="mb-3">
                                    <label for="" class="form-label">Observaciones</label>
                                    <textarea class="form-control" name="observacion" maxlength="200"
                                        placeholder="Agregue una observación a este pago si considera necesario." rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="confirm"
                                        name="confirm" required v-model="confirm" />
                                    <label class="form-check-label" for="confirm"> Confirmo que requiero realizar este
                                        sujeto excluido al cliente seleccionado, y he agregado las observaciones necesarias
                                        para hacer entendible porque se realiza este pago.</label>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button class="btn btn-primary" type="submit" :disabled="!isValid">Guardar y seguir
                                </button>
                                <a class="btn btn-light ms-2" href="{{ route('sujeto_excluido.index') }}">Volver</a>
                            </div>
                        </div>


                    </form>
                </div>
            </div>


        </div>
    </div>
@endsection
@section('script-caja')
    <script type="module">
        var app = window.appVue({
            emits: ['cliente'],
            data() {
                return {
                    cliente: null,
                    confirm: false,
                }
            },
            computed: {
                isValid() {
                    console.log(this.cliente, this.confirm)
                    return this.cliente && parseInt(this.cliente) > 0 && this.confirm;
                }
            },
            methods: {
                setcliente: function(c) {
                    this.cliente = c.id;

                    console.log(this.cliente, this.confirm)
                },
            },
        });
        app.component('clientes', component.clientes);
        app.mount("#appSujetoExcluido");
    </script>
@endsection
