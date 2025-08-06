<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte de nuevo huésped creado - TURISTICAS DE ORIENTE S.A. DE C.V.</title>

        <style>
            table {
                border-collapse: collapse;
                /* border: solid 1px #ccc; */
                border-radius: 15px;
                overflow: hidden;
            }
            td, th {
                padding: 8px;
                /* text-align: left; */
            }
            td { width: 400px; }
            .bg-red { background: rgb(148, 18, 18); }
            .bg-white { background: rgb(248, 248, 248); }
            .bg-yellow { background: rgb(148, 118, 18); }
            .text-white { color: rgb(248, 248, 248); }
            .text-center { text-align: center; }
            .text-uppercase { text-transform: uppercase; }
        </style>
    </head>
    <body>
        <main>
            <table>
                <tr class="bg-red">
                    <th colspan="2">
                        <h3 class="text-white text-center text-uppercase">
                            {{ $data['ilicita'] ? 'Persona relacionada a ilicitos' : ($data['peps'] ? 'Persona expuesta políticamente' : '---') }}
                        </h3>
                    </th>
                </tr>
                <tr>
                    <th class="bg-yellow text-white" style="border-bottom: solid 1px #ccc;">Información del huésped</th>
                    <th class="bg-yellow text-white" style="border-bottom: solid 1px #ccc;">Información de la persona con alerta</th>
                </tr>
                <tr>
                    <td class="bg-white" style="border-right: solid 1px #ccc;">
                        <p><b>Nombre:</b> {{ $data['nombre'] }}</p>
                        <p><b>Nacimiento:</b> {{ $data['nacimiento'] }}</p>
                        <p><b>Teléfono:</b> {{ $data['telefono'] }}</p>
                        <p><b>Tipo de Identificación:</b> {{ $data['tipoIdentificacion'] }}</p>
                        <p><b>Nº de Identificación:</b> {{ $data['identificacion'] }}</p>
                        <p><b>Municipio:</b> {{ $data['municipio'] }}</p>
                        <p><b>País:</b> {{ $data['pais'] }}</p>
                    </td>
                    <td class="bg-white">
                        <p><b>Nombres:</b> {{ $data['nombres'] }}</p>
                        <p><b>Apellidos:</b> {{ $data['apellidos'] }}</p>
                        <p><b>Alias:</b> {{ $data['alias'] }}</p>
                        <p><b>Nº de Identificación:</b> {{ $data['n_identificacion'] }}</p>
                    </td>
                </tr>
            </table>
        </main>
    </body>
</html>
