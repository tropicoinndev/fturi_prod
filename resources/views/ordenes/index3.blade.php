@extends('layouts.app')

@section('content')

<div id="appOrdenes">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header text-bg-primary text-center">Agregar desde concepto</h5>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-md-2">
                                        Titular:
                                    </div>

                                    <div class="col-10 d-flex justify-content-start">
                                        <div class="titular">
                                            AAAAA
                                        </div>
                                        <button type="button" class="btn btn-light btn-sm ms-3" title="Editar cliente">
                                            <span class="mdi mdi-pencil"></span>
                                        </button>
                                        <small class="ms-1 rounded-pill bg-light text-muted p-1">
                                            Consumidor Final
                                        </small>

                                        <small class="ms-1 rounded-pill bg-light text-muted p-1">
                                            Comprobante de Credito Fiscal
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-success btn-sm me-2">
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
                                        <input type="text" class="form-control" placeholder="Escriba aqui..."
                                            id="concepto" name="concepto" v-model="txtBusqueda" @keyup="getServicios" :disabled="serviciosSelected.length == 1">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="0" min="1" id="cantidad"
                                            name="cantidad" v-model="cantidad" @keyup="calcularTotal()" @change="calcularTotal()" :disabled="serviciosSelected.length == 0">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="0.00" min="1" step="any" id="precio"
                                            name="precio" v-model="precio" @keyup="calcularTotal()" @change="calcularTotal()" :disabled="serviciosSelected.length == 0">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="0.00" min="0" step="any" disabled id="total"
                                            name="total" v-model="total">
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
                                                        <input class="form-check-input" type="checkbox" id="checkIva" @click="funcCheckIva" :checked="estadoIva">
                                                        <label class="form-check-label" for="checkIva">IVA</label>
                                                    </div>
                                                </li>
                                                <li style="margin-left: 15px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkCesc" @click="funcCheckCesc" :checked="estadoCesc">
                                                        <label class="form-check-label" for="checkCesc">CESC</label>
                                                    </div>
                                                </li>
                                                <li style="margin-left: 15px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkAdvalorem" @click="funcCheckAdvalorem" :checked="estadoAdvalorem">
                                                        <label class="form-check-label" for="checkAdvalorem">Advalorem</label>
                                                    </div>
                                                </li>
                                                <li style="margin-left: 15px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkPropina" @click="funcCheckPropina" :checked="estadoPropina">
                                                        <label class="form-check-label" for="checkPropina">Propina</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        {{-- <input type="number" class="form-control" placeholder="0.00" id="venta_total"
                                            name="venta_total" v-model="ventaTotal"> --}}
                                            
                                        <span class="form-control text-center" style="font-size: 17px;"><b>$ @{{ ventaTotal }}</b></span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-success" @click="agregarOrden">
                                            Agregar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="result shadow" style="position: absolute;z-index: 10;margin-top: -20px;margin-left: 9px;width: 29.0em; background:white; padding:2px;" v-if="servicios.length > 0">
                            <ul class="list-group" v-for="servicio in servicios">
                                <li class="list-group-item" style="border-radius: 0px; border: none; cursor:pointer;" @click="selected(servicio)">@{{ servicio.servicio }} @{{ servicio.precio_unitario }}</li>
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
                        <div class="alert alert-warning" role="alert">
                            *No se mostraran los impuestos hasta que agregue un cliente.
                        </div>

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
                                    <td style="width:30%;">@{{ listaDetalle.servicios_id }} - @{{ listaDetalle.id }}</td>
                                    <td>@{{ listaDetalle.cantidad }}</td>
                                    <td>$ @{{ listaDetalle.precio_unitario }}</td>
                                    <td>$ @{{ listaDetalle.neto }}</td>
                                    <td>$ @{{ listaDetalle.iva }}</td>
                                    <td>$ @{{ listaDetalle.cesc }}</td>
                                    <td>$ @{{ listaDetalle.advalorem }}</td>
                                    <td>$ @{{ listaDetalle.propina }}</td>
                                    <td>$ @{{ listaDetalle.venta_total }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarDetalleOrden(listaDetalle.id)">
                                            <span class="mdi mdi-trash-can"></span>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8">Sub-total</td>
                                    <td colspan="2">$ 26.55</td>
                                </tr>
                                <tr>
                                    <td colspan="8"><b>Total</b></td>
                                    <td colspan="1"><b>$ 26.55</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End container-->

    {{-- @{{ message }}
    <span class="text-danger">@{{ titulo }}</span> --}}
</div>

<script>
    var app = new Vue({
        el: '#appOrdenes',
        data: {
            message: 'Hola Vue!',
            titulo: 'TROPICO',

            servicios: [],
            txtBusqueda: '',
            serviciosSelected: [],
            detalleOrdenes: [],

            cantidad: 0,
            precio: 0,
            total: 0,
            ventaTotal: 0,
            iva: 0,
            cesc: 0,
            advalorem: 0,

            estadoIva: false,
            estadoCesc: false,
            estadoAdvalorem: false,
            estadoPropina: false,
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
            mostrarDetalleOrdenes(){
                axios.get("{{ route('detalle_ordenes.index') }}")
                    .then((respuesta) => {
                        //console.log('Resp Listar Orden: ',respuesta);
                        this.detalleOrdenes = respuesta.data.p;
                    })
                    .catch(error => {
                        console.log(error);
                    });
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
                    descuentos_id: 5,
                    servicios_id: this.serviciosSelected.id,
                    ordenes_id: 1
                })
                .then((resp) => {
                    //console.log('Resp agregarOrden: ',resp);

                    if(resp.data.type === 'success'){
                        this.mostrarDetalleOrdenes();
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
            }
        },
        computed: {

        }
    })
</script>

@endsection
