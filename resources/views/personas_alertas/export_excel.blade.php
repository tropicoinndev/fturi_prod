<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <div>
            <table>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">nombres</th>
                        <th scope="col">apellidos</th>
                        <th scope="col">alias</th>
                        <th scope="col">numero_identificacion</th>
                        <th scope="col">persona_buscada</th>
                        <th scope="col">peps</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($p as $d)
                        <tr>
                            <td scope="row">{{ $d->id }}</td>
                            <td>{{ $d->nombres }}</td>
                            <td>{{ $d->apellidos }}</td>
                            <td>{{ $d->alias }}</td>
                            <td>{{ $d->numero_identificacion }}</td>
                            <td>{{ $d->ilicita ? 'Persona relacionada a ilicitos' : 'Persona NO relacionada a ilicitos' }}</td>
                            <td>{{ $d->peps ? 'Persona expuesta politicamente' : 'Persona NO expuesta politicamente' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
