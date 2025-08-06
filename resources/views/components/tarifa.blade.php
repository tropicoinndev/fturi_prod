<style>
    .ch-60 {
        height: 60vh;
        overflow-x: auto;
    }
    .tarifa .card {
        height: 85px;
    }
</style>
<div id="tarifa">
    <!-- mostrar los calculos de los impuestos que posse la tarifa-->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Detalles de la tarifa</h5>
            <!-- mostrar tarifas y sus propiedades -->
            @foreach ($tarifas as $tr)
            @endforeach

            <div class="row">
                <div class="col-3 mb-4">
                    <h6 class="card-subtitle mb-2"> Informacion del precio de
                        la tarifa  </h6>
                    <div class="row">
                        <div class="col-6 text-right">Precio Neto:</div>
                        <div class="col-6" id="txtprecio">@{{ txtprecioNeto }}</div>
                        <div class="col-6 text-right">IVA:</div>
                        <div class="col-6" id="txtiva">@{{ txtiva }}</div>
                        <div class="col-6 text-right">CET:</div>
                        <div class="col-6" id="txtvalorem">@{{ txtcet }}</div>
                        <div class="col-6 text-right">Precio total:</div>
                        <div class="col-6 text-right" id="txttotal">@{{ txttotal }}</div>
                    </div>
                </div>
                <div class="col-6 mb-4">
                    <h6 class="card-subtitle mb-2">Fechas en las que
                        aplica la tarifa</h6>
                    <div class="row">
                        <div class="col-12 text-right">Temporada en que aplica: {{ $tr->temporadas->temporada }}</div>
                        <div class="col-12 text-right">Fecha en que inicia: {{ $tr->temporadas->fecha_inicio }}</div>
                        <div class="col-12 text-right">Fecha en que finaliza: {{ $tr->temporadas->fecha_finalizacion }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">Habitaciones Aplicables:</h5>

                    <!-- mostrar todas las habitaciones agregadas a esta tarifa -->
                    @if ($habitaciones->isEmpty())
                        <p>Aún no se ha aplicado esta tarifa en habitaciones.</p>
                    @else
                        <div class="row">
                            @foreach ($habitaciones as $p)
                                <div class="col-3 tarifa mt-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title ">
                                                <strong>Habitación {{$p->numero_habitacion}}</strong> </h6>
                                            <p class="card-title">
                                            <span class="text-uppercase"> {{ $p->relacionFormaHabitaciones->forma_habitacion }} | {{ $p->relacionTipoHabitaciones->tipo_habitacion }}</span> </p>

                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var app = new Vue({
        el: "#tarifa",
        data: {
            /** show tarifas*/
            tarifa: "{{ $tarifa }}",
            iva: parseFloat("{{ env('iva', 0.13) }}"),
            cet: parseFloat("{{ env('cet', 0.05) }}"),
            preciot: parseFloat("{{ $tr->precio }}"),
            txtiva: null,
            txtcet: null,
            txttotal: null,
            txtprecioNeto: null,
            agregados: 1,

        },
        methods: {
            //
        },
        mounted: function() {
            document.onreadystatechange = () => {
                if (document.readyState == "complete") {
                    this.txtprecioNeto = '$' + this.precioNeto;
                    this.txtiva = '$' + this.ivaCalculado;
                    this.txtcet = '$' + this.cetCalculado;
                    this.txttotal = '$' + this.totalCalculado;
                }
            };

        },
        computed: {
            precioNeto() {
                const impuestos = this.agregados + this.iva + this.cet; //impuestos
                const precioNeto = this.preciot / impuestos;
                return precioNeto.toFixed(4);
            },
            ivaCalculado() {
                const montoIva = parseFloat(this.precioNeto) * this.iva;
                return montoIva.toFixed(4);
            },
            cetCalculado() {
                const montoCet = parseFloat(this.precioNeto) * this.cet;
                return montoCet.toFixed(4);
            },
            totalCalculado() {
                const subtotal = parseFloat(this.precioNeto);
                const montoCet = parseFloat(this.cetCalculado);
                const montoIva = parseFloat(this.ivaCalculado);
                const total = subtotal + montoCet + montoIva;
                return total.toFixed(2);
            }
        }
    });
</script>
