@extends('layouts.print_bootstrap')
@section('content')
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>ROS</title>
        <style>
            .colf {
                width: 8cm;
            }

            body {
                background: #ffffff;
                text-transform: uppercase;
            }

            .vacio::after {
                content: '---'
            }

            .card {
                background: #ffffff;

            }

            .table-light tr td {
                background: #ffffff !important;

            }

            .table-light .bg-gray {
                background: #ECEFF1 !important;
            }

            .bb {
                border-bottom: 1px solid #000 !important;
            }
        </style>
    </head>

    <body>
        <div class="card border-0">
            <div class="card-body">
                <div class="card-text">
                    <div class="row">
                        <div class="col-12 text-center h1 fw-bold">
                            <h3>
                                {{ env('empresa') }}
                            </h3>
                        </div>
                        <div class="col-12 h3 mb-3 text-center">
                            REPORTE DE OPERACIÓN SOSPECHOSA
                        </div>
                        <div class="col-12 mb-3">
                            <b>Art. 9-A LEY CONTRA LAVADO DE DINERO Y ACTIVOS DE EL SALVADOR</b>
                            LOS REPORTES DE OPERACIONES SOSPECHOSAS DEBERÁN SER REMITIDOS A LA UNIDAD DE
                            INVESTIGACIÓN FINANCIERA EN EL PLAZO MÁXIMO DE CINCO DÍAS HÁBILES, CONTADOS A PARTIR
                            DEL MOMENTO EN QUE, DE ACUERDO AL ANÁLISIS QUE SE REALICE, EXISTAN SUFICIENTES
                            ELEMENTOS DE JUICIO PARA CONSIDERARLAS IRREGULARES, INCONSISTENTES O QUE NO
                            GUARDAN RELACIÓN CON EL TIPO DE ACTIVIDAD ECONÓMICA DEL CLIENTE.
                        </div>
                        <div class="col-12 mb-3">
                            <b>
                                USAR ESTE FORMATO PARA REPORTAR (DE FORMA RESERVADA) TRANSACCIONES A LA UNIDAD DE
                                CUMPLIMIENTO, QUE PUEDAN SER CONSIDERADAS COMO OPERACIÓN SOSPECHOSA, SIN IMPORTAR EL MONTO Y
                                LA FORMA EN LA CUAL SE INTENTÓ REALIZARLA O SE REALIZÓ.
                            </b>

                        </div>
                        <div class="col-12">
                            <table class="table table-light table-borderless">
                                <tbody>

                                    <tr>
                                        <td class="colf">
                                            FECHA DE OPERACIÓN:
                                        </td>
                                        <td class="bb">
                                            {{ $p->fecha }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            VALOR DE LA OPERACIÓN (USD):
                                        </td>
                                        <td class="bb">
                                            ${{ number_format($p->monto, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            TIPO DE OPERACIÓN:
                                        </td>
                                        <td class="bb">
                                            {{ $p->forma_pagos->forma }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            PUNTO DE SERVICIO:
                                        </td>
                                        <td class="bb">
                                            {{ $p->sucursales->matriz ? 'CASA MATRIZ' : 'SUCURSAL' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            DIRECCIÓN PUNTO DE SERVICIO:
                                        </td>
                                        <td class="bb">
                                            {{ $p->sucursales->direccion }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            DEPARTAMENTO PUNTO DE SERVICIO:
                                        </td>
                                        <td class="bb">
                                            {{ $p->sucursales->municipios->departamentos->departamento }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            MUNICIPIO PUNTO DE SERVICIO:
                                        </td>
                                        <td class="bb">
                                            {{ $p->sucursales->municipios->municipio }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            CLASE DE PRODUCTO:
                                        </td>
                                        <td class="bb">
                                            {{ $p->clase_producto }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            NOMBRE DE QUIEN REALIZA LA TRANSACCIÓN:
                                        </td>
                                        <td class="bb">
                                            {{ $p->nombre }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            TIPO DE DOCUMENTO:
                                        </td>
                                        <td class="bb">
                                            {{ $p?->identificaciones?->identificacion ?? '---' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            NUMERO DE DOCUMENTO:
                                        </td>
                                        <td class="bb">
                                            {{ $p->numero_identificacion ?? '---' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            BREVE DESCRIPCIÓN DEL EVENTO
                                        </td>
                                        <td class="bb">
                                            {{ $p->observaciones }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="colf">
                                            NOMBRE DEL EMPLEADO
                                        </td>
                                        <td class="bb">
                                            {{ $p->users->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colf">
                                            CARGO DEL EMPLEADO
                                        </td>
                                        <td class="bb">
                                            {{ $p->cargo }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </body>

    </html>
@endsection
