@extends('layouts.app')

@section('content')

<div id="appOrdenes">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header text-bg-primary text-center">Agregar desde concepto</h5>
                    <div class="card-body">

                        <!--Mensajes de alerta alerta-->
                        <div class="col-6">
                            <x-message></x-message>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-md-2">
                                        Titular:
                                    </div>

                                    <div class="col-10 d-flex justify-content-start">
                                        <div class="titular">
                                            @if(isset($p->clientes_id) && $p->clientes_id != null)
                                                {{ $p->clientes->nombre }}
                                            @else
                                                {{ $p->titular }}
                                            @endif
                                        </div>

                                        <button type="button" class="btn btn-light btn-sm ms-3" title="Editar cliente" data-bs-toggle="modal" data-bs-target="#modalEditarTitular">
                                            <span class="mdi mdi-pencil"></span>
                                        </button>

                                        <small class="ms-1 rounded-pill bg-light text-muted p-1">
                                            @if(($p->clientes_id != null) && ($p->clientes->tipo_cliente))
                                                Consumidor Final
                                            @else
                                                Comprobante de Credito Fiscal
                                            @endif
                                        </small>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <span>Permite credito: </span>
                                <small class="ms-1 rounded-pill bg-light text-muted p-1">
                                    @if($p->clientes_id != null && $p->clientes->credito)
                                        SI
                                    @else
                                        NO
                                    @endif
                                </small>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente">
                                    Agregar cliente
                                </button>

                                <button type="button" class="btn btn-outline-primary btn-sm">
                                    Generar comprobante
                                </button>
                            </div>
                        </div>


                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="row" style="width:35%;">Concepto</th>
                                    <th scope="col" style="width:10%;">Cantidad</th>
                                    <th scope="col" style="width:10%;">Precio</th>
                                    <th scope="col" style="width:10%;">Total</th>
                                    <th scope="col" style="width:5%;">Opciones</th>
                                    <th scope="col" style="width:20%;" class="text-center">Venta Total</th>
                                    <th scope="col" style="width:10%;">Agregar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width:25%;">
                                        <input type="text" class="form-control" placeholder="Buscar concepto..."
                                            id="concepto" name="concepto" v-model="txtBusqueda" @keyup="getServicios"
                                            :disabled="serviciosSelected.length == 1">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="0" min="1" id="cantidad"
                                            name="cantidad" v-model="cantidad" @keyup="calcularTotal()"
                                            @change="calcularTotal()" :disabled="serviciosSelected.length == 0">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="0.00" min="1" step="any"
                                            id="precio" name="precio" v-model="precio" @keyup="calcularTotal()"
                                            @change="calcularTotal()" :disabled="serviciosSelected.length == 0">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="0.00" min="0" step="any"
                                            disabled id="total" name="total" v-model="total">
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="mdi mdi-cog"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li style="margin-left: 15px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkIva"
                                                            @click="funcCheckIva" :checked="estadoIva">
                                                        <label class="form-check-label" for="checkIva">IVA</label>
                                                    </div>
                                                </li>
                                                <li style="margin-left: 15px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkCesc"
                                                            @click="funcCheckCesc" :checked="estadoCesc">
                                                        <label class="form-check-label" for="checkCesc">CESC</label>
                                                    </div>
                                                </li>
                                                <li style="margin-left: 15px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="checkAdvalorem" @click="funcCheckAdvalorem"
                                                            :checked="estadoAdvalorem">
                                                        <label class="form-check-label"
                                                            for="checkAdvalorem">Advalorem</label>
                                                    </div>
                                                </li>
                                                <li style="margin-left: 15px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="checkPropina" @click="funcCheckPropina"
                                                            :checked="estadoPropina">
                                                        <label class="form-check-label"
                                                            for="checkPropina">Propina</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        {{-- <input type="number" class="form-control" placeholder="0.00"
                                            id="venta_total" name="venta_total" v-model="ventaTotal"> --}}

                                        <span class="form-control text-center" style="font-size: 17px;"><b>$ @{{
                                                ventaTotal }}</b></span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-success" @click="agregarOrden">
                                            Agregar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="result shadow"
                            style="position: absolute;z-index: 10;margin-top: -20px;margin-left: 9px;width: 29.0em; background:white; padding:2px;"
                            v-if="servicios.length > 0">
                            <ul class="list-group" v-for="servicio in servicios">
                                <li class="list-group-item" style="border-radius: 0px; border: none; cursor:pointer;"
                                    @click="selected(servicio)">@{{ servicio.servicio }} @{{ servicio.precio_unitario }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End row-->



        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header text-bg-success text-center">Detalle de orden</h5>
                    <div class="card-body">

                        @if(!$p->clientes_id)
                            <div class="alert alert-warning" role="alert">
                                *No se mostraran los impuestos hasta que agregue un cliente.
                            </div>
                        @endif

                        {{-- @{{ detalleOrdenes }} --}}
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:30%;">Concepto</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Precio</th>
                                    <th scope="col">Neto</th>
                                    <th scope="col">IVA</th>
                                    <th scope="col">CESC</th>
                                    <th scope="col">Advaloren</th>
                                    <th scope="col">Propina</th>
                                    <th scope="col">Venta total</th>
                                    <th scope="col">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($detalleOrdenes as $key => $valueItems)
                                    Key: {{ $key }} -> Cantidad: {{ $valueItems->cantidad }} -> Venta total ->
                                    {{ ($valueItems->neto + $valueItems->iva + $valueItems->cesc + $valueItems->advalorem) }}
                                    <br>
                                @endforeach --}}

                                <tr v-for="listaDetalle in detalleOrdenes">
                                    {{-- {{ detalleOrdenes_ventaTotal = detalleOrdenes_ventaTotal + listaDetalle.neto }} --}}

                                    <td style="width:30%;">@{{ listaDetalle.servicios.servicio }}</td>
                                    <td>@{{ listaDetalle.cantidad }}</td>
                                    <td>$ @{{ listaDetalle.precio_unitario }}</td>
                                    <td>$ @{{ calcNeto(listaDetalle) }}</td>
                                    
                                    <td>
                                        {{-- @if($p->clientes_id) --}}
                                            $ @{{ listaDetalle.iva }}
                                        {{-- @else
                                            $ 0.00
                                        @endif --}}
                                    </td>
                                    <td>
                                        {{-- @if($p->clientes_id) --}}
                                            $ @{{ listaDetalle.cesc }}
                                        {{-- @else
                                            $ 0.00
                                        @endif --}}
                                    </td>
                                    <td>
                                        {{-- @if($p->clientes_id) --}}
                                            $ @{{ listaDetalle.advalorem }}
                                        {{-- @else
                                            $ 0.00
                                        @endif --}}
                                    </td>
                                    <td>
                                        {{-- @if($p->clientes_id) --}}
                                            $ @{{ listaDetalle.propina }}
                                        {{-- @else
                                            $ 0.00
                                        @endif --}}
                                    </td>
                                    <td>$ @{{ parseFloat(calcNeto(listaDetalle) + calcTotal(listaDetalle)) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            @click="eliminarDetalleOrden(listaDetalle.id)">
                                            <span class="mdi mdi-trash-can"></span>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8">Sub-total</td>
                                    <td colspan="2">$ @{{ subTotal.toFixed(4) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="8"><b>Total</b></td>
                                    <td colspan="1"><b>$ 0</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End container-->

    <!-- Modal agregar cliente-->
    <div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar cliente</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('ordenes.update') }}" method="POST">
                        @csrf
    
                        <input type="hidden" name="id_orden" :value="orden">
                        <input type="hidden" name="clientes_id" :value="clientesSelected.id">
                        {{-- <input type="hidden" name="titular" :value="clientesSelected.titular"> --}}
    
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="titular" class="form-label">Cliente:</label>
    
                                <div v-if="clientesSelected.id >= 0">
                                    @{{ clientesSelected.titular }}
                                </div>
    
                                <div class="input-group mb-3" v-if="!(clientesSelected.id >= 0)">
                                    <input type="text" class="form-control" id="titular" name="clientes_id" autocomplete="off" placeholder="Buscar cliente..."
                                        v-model="txtCliente" @keyup="getClientes">
                                    <span class="input-group-text" id="basic-addon2"
                                        v-if="clientes.length == 0 && txtCliente.length > 3" @click="AddTitular">Agregar
                                        titular</span>
                                </div>
    
                                <div class="result shadow"
                                    style="position: absolute;z-index: 10;margin-top: 0px;margin-left: 0px; width: 32.5em; background:white; padding:2px;"
                                    v-if="clientes.length > 0">
                                    <ul class="list-group" v-for="cliente in clientes">
                                        <li class="list-group-item"
                                            style="border-radius: 0px; border: none; cursor:pointer;"
                                            @click="selectedClientes(cliente)">@{{ cliente.nombre }} - <small>@{{ cliente.direccion
                                                }} - @{{ (cliente.tipo_cliente) ? 'Natural' : 'Juridico' }}</small></li>
                                    </ul>
                                </div>
    
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary"
                                :disabled="clientesSelected.length == 0">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal editar titular-->
    <div class="modal fade" id="modalEditarTitular" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar titular</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('ordenes.updateTitular') }}" method="POST">
                        @csrf
    
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="titular" class="form-label">Cliente:</label>
                                <input type="hidden" name="id_orden" :value="orden">
                                <input type="text" class="form-control" placeholder="Ingrese titular" name="titular" :value="txtTitular">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    var app = new Vue({
        el: '#appOrdenes',
        data: {
            txtBusqueda: '',
            servicios: [],
            serviciosSelected: [],
            detalleOrdenes: @json($detalleOrdenes),
            orden: {{ Crypt::decryptString($p->id) }},
            cantidad: 0,
            precio: 0,
            total: 0,
            ventaTotal: 0,
            iva: 0,
            cesc: 0,
            advalorem: 0,

            estadoIva: true,
            estadoCesc: false,
            estadoAdvalorem: false,
            estadoPropina: false,

            titular: '',

            txtTitular: @json($p->titular),

            //Cliente
            txtCliente: '',
            clientes: [],
            clientesSelected: [],

            detalleOrdenes_ventaTotal: 0,

            subTotal: 0,
        },
        methods: {
            getServicios(){
                axios.post("{{ route('servicios.apiSearch') }}",{
                    txtBusqueda: this.txtBusqueda,
                })
                .then((resp) => {

                    if(resp.data.servicios){
                        this.servicios = resp.data.servicios;
                    }
                    else{
                        this.servicios = [];
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            },
            selected(s){
                this.serviciosSelected = s;
                this.servicios = [];
                this.precio = this.serviciosSelected.precio_unitario;
                this.txtBusqueda = this.serviciosSelected.servicio;
            },
            calcularTotal(){
                //this.total = (this.precio * this.cantidad).toLocaleString('es-SV');
                this.total = ((this.precio * this.cantidad)).toFixed(4);

                this.ventaTotal = this.total;
            },
            agregarOrden(){
                /*this.detalleOrdenes = [this.serviciosSelected];
                console.log(this.detalleOrdenes);*/

                axios.post("{{ route('detalle_ordenes.store') }}",{
                    cantidad: this.cantidad,
                    precio_unitario: this.precio,
                    neto: this.total,
                    iva: this.estadoIva,
                    cesc: this.estadoCesc,
                    advalorem: this.estadoAdvalorem,
                    propina: this.estadoPropina,
                    descuentos_id: null,
                    servicios_id: this.serviciosSelected.id,
                    ordenes_id: this.orden,
                })
                .then((resp) => {
                    console.log('Resp agregarOrden: ',resp);

                    if(resp.data.detalleOrdenes)
                        this.detalleOrdenes = resp.data.detalleOrdenes;

                    if(resp.data.type === 'success'){

                    }
                    else{
                        console.log('Error: ',resp.data.msj);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            },
            eliminarDetalleOrden(id){
                axios.post("{{ route('detalle_ordenes.delete') }}",{
                    id: id
                })
                    .then((resp) => {
                        //console.log('Resp eliminar: ',resp);
                        this.mostrarDetalleOrdenes();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            funcCheckIva(){
                this.estadoIva = !this.estadoIva; console.log('Estado iva: ',this.estadoIva);

                /*if(this.estadoIva){
                    this.iva = (this.total * 0.13);
                    this.ventaTotal = (this.total + this.iva);
                    console.log('iva: ',this.iva);
                }
                else{
                    this.iva = 0;
                    this.ventaTotal = (this.precio * this.cantidad);
                }*/
            },
            funcCheckCesc(){
                this.estadoCesc = !this.estadoCesc; console.log('Estado cesc: ',this.estadoCesc);

                /*this.cesc = (this.estadoCesc) ? (this.total * 0.05) : 0;
                console.log('cesc: ',this.cesc);*/
            },
            funcCheckAdvalorem(){
                this.estadoAdvalorem = !this.estadoAdvalorem; console.log('Estado advalorem: ',this.estadoAdvalorem);
            },
            funcCheckPropina(){
                this.estadoPropina = !this.estadoPropina; console.log('Estado propina: ',this.estadoPropina);
            },
            getClientes(){
                axios.post("{{ route('clientes.apiSearch') }}",{
                    txtCliente: this.txtCliente,
                })
                .then((resp) => {
                    if(resp.data.clientes){
                        this.clientes = resp.data.clientes;
                        //console.log('clientes: ',this.clientes[0].nombre);
                    }
                    else{
                        this.clientes = [];
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            },
            selectedClientes(s){
                this.clientesSelected = {id: s.id, titular: s.nombre};
                this.clientes = [];
            },
            calcNeto(listaDeDetalles){
                let cantidad = parseFloat(listaDeDetalles.cantidad);
                let precioUnitario = parseFloat(listaDeDetalles.precio_unitario);
                return parseFloat(cantidad * precioUnitario).toFixed(4);
            },
            calcTotal(listaDeDetalles){
                //return (listaDeDetalles.neto * listaDeDetalles.cantidad).toFixed(4);
                let neto = parseFloat(listaDeDetalles.neto);
                let iva = parseFloat(listaDeDetalles.iva);
                let cesc = parseFloat(listaDeDetalles.cesc);
                let advalorem = parseFloat(listaDeDetalles.advalorem);
                let resultado = parseFloat(neto + iva + cesc + advalorem);
                this.subTotal = parseFloat(this.subTotal + resultado);
                return parseFloat(resultado);
                
            },
        },
        mounted() {

        },
    })
</script>

@endsection
