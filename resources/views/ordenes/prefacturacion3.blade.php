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
                        <div class="col-6"><x-message></x-message></div>

                        <div class="row mb-2">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-md-2">Titular:</div>

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
                                    {{ ($p->clientes_id != null) ? 'Editar cliente' : 'Agregar cliente'; }}
                                </button>

                                <button type="button" class="btn btn-outline-primary btn-sm">
                                    Generar comprobante
                                </button>
                            </div>
                        </div>


                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="row" style="width:12%;">Concepto</th>
                                    <th scope="col" style="width:5%;">Cantidad</th>
                                    <th scope="col" style="width:5%;">Precio</th>
                                    <th scope="col" style="width:5%;">Total</th>
                                    <th scope="col" style="width:3%; text-align: center;">Opciones</th>
                                    <th scope="col" style="width:7%; text-align: center;">Venta Total</th>
                                    <th scope="col" style="width:8%; text-align: center;">Agregar | Limpiar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width:12%;">
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
                                        <input type="number" class="form-control" placeholder="0.00" step="any"
                                            id="precio" name="precio" v-model="precio" @keyup="calcularTotal()"
                                            @change="calcularTotal()" :disabled="serviciosSelected.length == 0">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="0.00" min="0" step="any"
                                            disabled id="total" name="total" v-model="total">
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="mdi mdi-cog"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li style="margin-left: 15px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkIva"
                                                            @click="funcCheckIva" :checked="estadoIva" disabled>
                                                        <label class="form-check-label" for="checkIva">IVA</label>
                                                    </div>
                                                </li>
                                                <li style="margin-left: 15px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkCesc"
                                                            @click="funcCheckCesc" :checked="estadoCesc" disabled>
                                                        <label class="form-check-label" for="checkCesc">CESC</label>
                                                    </div>
                                                </li>
                                                <li style="margin-left: 15px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="checkAdvalorem" @click="funcCheckAdvalorem" :checked="estadoAdvalorem" disabled>
                                                        <label class="form-check-label" for="checkAdvalorem">Advalorem</label>
                                                    </div>
                                                </li>
                                                <li style="margin-left: 15px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="checkPropina" @click="funcCheckPropina" :checked="estadoPropina" disabled>
                                                        <label class="form-check-label" for="checkPropina">Propina</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        {{-- <input type="number" class="form-control" placeholder="0.00"
                                            id="venta_total" name="venta_total" v-model="ventaTotal"> --}}

                                        <span class="form-control text-center" style="font-size: 17px;"><b>$ @{{ ventaTotal }}</b></span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-success" style="margin-right: 5px;" @click="agregarOrden" :disabled="serviciosSelected.length == 0">
                                            Agregar
                                        </button>

                                        <button type="button" class="btn btn-outline-secondary" :disabled="serviciosSelected.length == 0" @click="funcLimpiarCajas">
                                            limpiar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!--List.-->
                        <div class="result shadow"
                            style="position: absolute;z-index: 10;margin-top: -20px;margin-left: 9px;width: 29.0em; background:white; padding:2px;"
                            v-if="servicios.length > 0">

                            <ul class="list-group">
                                <li v-for="servicio in servicios" class="list-group-item d-flex justify-content-between align-items-center" 
                                    style="border-radius: 0px; border: none; cursor:pointer;" @click="selected(servicio)">
                                        @{{ servicio.servicio }}
                                    <span class="badge bg-primary rounded-pill">$ @{{ servicio.precio_unitario | decimales }}</span>
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
                                <tr v-for="listaDetalle in detalleOrdenes">
                                    <td style="width:30%;">@{{ listaDetalle.servicios.servicio }}</td>
                                    <td>@{{ listaDetalle.cantidad }}</td>
                                    <td>
                                        $ <span class="float-end">@{{ listaDetalle.precio_unitario | decimales }}</span>
                                    </td>
                                    <td>
                                        $ <span class="float-end">@{{ listaDetalle.neto | decimales }}</span>
                                    </td>
                                    <td>
                                        $ <span class="float-end">@{{ listaDetalle.iva | decimales}}</span>
                                    </td>
                                    <td>
                                        $ <span class="float-end">@{{ listaDetalle.cesc | decimales}}</span>
                                    </td>
                                    <td>
                                        $ <span class="float-end">@{{ listaDetalle.advalorem | decimales}}</span>
                                    </td>
                                    <td>
                                        $ <span class="float-end">@{{ listaDetalle.propina | decimales}}</span>
                                    </td>
                                    <td>
                                        $ <span class="float-end">@{{ getVenta(listaDetalle) | decimales}}</span></td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminar"
                                            @click="eliminarDetalleOrden(listaDetalle.id, listaDetalle.servicios.servicio)">
                                            <span class="mdi mdi-trash-can"></span>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8">Sub-total</td>
                                    <td colspan="1">
                                        $ <span class="float-end">@{{ getSubTotal() | decimales }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="8"><b>Total</b></td>
                                    <td colspan="1"><b>$ 0</b></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End container-->

    <!-- Modal eliminar-->
    <div class="modal fade" id="modalEliminar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('detalle_ordenes.delete') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Esta seguro de eliminar este servicio?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="idEliminarDetalleOrden" :value="idEliminarDetalleOrden">
                        <div class="mt-3 mb-3">
                            <ul>
                                <li>@{{ registroAEliminar }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Si, Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal agregar cliente-->
    <div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="titular" class="form-label">Cliente:</label>

                                <div v-if="clientesSelected.id >= 0"> @{{ clientesSelected.titular }}</div>

                                <div class="input-group mb-3" v-if="!(clientesSelected.id >= 0)">
                                    <input type="text" class="form-control" id="titular" name="clientes_id"
                                        autocomplete="off" placeholder="Buscar cliente..." v-model="txtCliente" @keyup="getClientes">

                                    <span class="input-group-text" id="basic-addon2"
                                        v-if="clientes.length == 0 && txtCliente.length > 3" @click="AddTitular">Agregar titular
                                    </span>
                                </div>

                                <div class="result shadow"
                                    style="position: absolute;z-index: 10;margin-top: 0px;margin-left: 0px; width: 32.5em; background:white; padding:2px;"
                                    v-if="clientes.length > 0">
                                    <ul class="list-group" v-for="cliente in clientes">
                                        <li class="list-group-item"
                                            style="border-radius: 0px; border: none; cursor:pointer;"
                                            @click="selectedClientes(cliente)">@{{ cliente.nombre }} - <small>@{{ cliente.direccion }} - @{{ (cliente.tipo_cliente) ? 'Natural' : 'Juridico' }}</small>
                                        </li>
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
    <div class="modal fade" id="modalEditarTitular" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                                <input type="text" class="form-control" placeholder="Ingrese titular" name="titular"
                                    :value="txtTitular">
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
            txtBusqueda:       '',
            servicios:         [],
            serviciosSelected: [],
            detalleOrdenes:    @json($detalleOrdenes),
            orden:             {{ Crypt::decryptString($p->id) }},
            cantidad:          1,
            precio:            0,
            total:             0,
            ventaTotal:        0,
            iva:               0,
            cesc:              0,
            advalorem:         0,

            estadoIva:       true,
            estadoCesc:      false,
            estadoAdvalorem: false,
            estadoPropina:   false,

            titular: '',

            txtTitular: @json($p->titular),

            //Cliente.
            txtCliente:       '',
            clientes:         [],
            clientesSelected: [],

            //Detalle de ordenes.
            netoDO:       0,
            ivaDO:        0,
            cescDO:       0,
            ventaTotalDO: 0,
            subTotalDO:   0,

            idEliminarDetalleOrden: null,
            registroAEliminar:      '',
        },
        mounted(){

        },
        computed: {

        },
        methods: {
            getServicios(){
                if(this.txtBusqueda.length > 2){
                    axios.post("{{ route('servicios.apiSearch') }}",{
                        txtBusqueda: this.txtBusqueda,
                    })
                    .then((resp) => {
                        this.servicios = (resp.data.servicios) ? resp.data.servicios : [];
                    })
                    .catch(error => {
                        console.log(error);
                    });
                }
            },
            selected(s){
                this.serviciosSelected = s;
                this.servicios         = [];
                this.precio            = this.serviciosSelected.precio_unitario;
                this.txtBusqueda       = this.serviciosSelected.servicio;

                this.calcularTotal();

                this.estadoIva = (this.serviciosSelected.iva) ? true : false;
                this.estadoCesc = (this.serviciosSelected.cesc) ? true : false;
                this.estadoAdvalorem = (this.serviciosSelected.advalorem) ? true : false;
                this.estadoPropina = (this.serviciosSelected.propina) ? true : false;
            },
            calcularTotal(){
                if(this.precio < this.serviciosSelected.precio_unitario){
                    this.precio = this.serviciosSelected.precio_unitario;
                }
                else{
                    this.total      = ((this.precio * this.cantidad)).toFixed(2);
                    this.ventaTotal = this.total;
                }
            },
            agregarOrden(){
                axios.post("{{ route('detalle_ordenes.store') }}",{
                    cantidad:        this.cantidad,
                    precio_unitario: this.precio,
                    neto:            this.total,
                    iva:             this.estadoIva,
                    cesc:            this.estadoCesc,
                    advalorem:       this.estadoAdvalorem,
                    propina:         this.estadoPropina,
                    descuentos_id:   null,
                    servicios_id:    this.serviciosSelected.id,
                    ordenes_id:      this.orden,
                })
                .then((resp) => {
                    if(resp.data.type === 'success'){
                        this.detalleOrdenes = resp.data.detalleOrdenes;
                        this.funcLimpiarCajas();
                    }
                    else{
                        console.log('Error: ',resp.data.msj);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            },
            funcLimpiarCajas(){
                this.txtBusqueda       = '';
                this.cantidad          = 1;
                this.precio            = '';
                this.total             = '';
                this.ventaTotal        = 0;
                this.serviciosSelected = [];

                this.estadoCesc = this.estadoAdvalorem = this.estadoPropina = false;
            },
            eliminarDetalleOrden(id, selectedDelete){
                this.idEliminarDetalleOrden = id;
                this.registroAEliminar      = selectedDelete;
            },
            funcCheckIva(){       this.estadoIva       = !this.estadoIva;       },
            funcCheckCesc(){      this.estadoCesc      = !this.estadoCesc;      },
            funcCheckAdvalorem(){ this.estadoAdvalorem = !this.estadoAdvalorem; },
            funcCheckPropina(){   this.estadoPropina   = !this.estadoPropina;   },
            getClientes(){
                this.txtCliente = this.txtTitular;
                
                axios.post("{{ route('clientes.apiSearch') }}",{
                    txtCliente: this.txtCliente,
                })
                .then((resp) => {
                    this.clientes = (resp.data.clientes) ? resp.data.clientes : [];
                })
                .catch(error => {
                    console.log(error);
                });
            },
            selectedClientes(s){
                this.clientesSelected = {id: s.id, titular: s.nombre};
                this.clientes         = [];
            },

            //Detalle de ordenes.
            calcularNetoDO(listaDeDetalles){
                let cantidad       = parseFloat(listaDeDetalles.cantidad);
                let precioUnitario = parseFloat(listaDeDetalles.precio_unitario);
                this.netoDO        = parseFloat((cantidad * precioUnitario) / 1.18);
                return parseFloat(this.netoDO).toFixed(4);
            },
            calcularIvaDO(listaDeDetalles){
                this.ivaDO = (this.netoDO * 0.13);
                return parseFloat(this.ivaDO).toFixed(4);
            },
            calcularCescDO(listaDeDetalles){
                this.cescDO = (this.netoDO * 0.05);
                return parseFloat(this.cescDO).toFixed(4);
            },
            calcularVentaTotalDO(listaDeDetalles){
                this.ventaTotalDO = (this.netoDO + this.ivaDO + this.cescDO + 0);
                //this.subTotalDO += this.ventaTotalDO;
                return this.ventaTotalDO.toFixed(4);
            },
            getVenta(d){
                return parseFloat(d.precio_unitario * d.cantidad)
            },
            getSubTotal(){
                var i   = 0;
                var sum = this.detalleOrdenes.reduce((acumulador, v) => acumulador + this.getVenta(v) , i);
                return sum
            }
        },
        filters:{
            decimales: function(value){
                var deci = 2;
                if(!value) return 0.00;
                return parseFloat(value).toFixed(deci);
            }
        }
    })
</script>

@endsection
