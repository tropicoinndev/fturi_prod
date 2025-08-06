@if($json)
    <div style="height: 4.29cm;" class="col-5 estilo-emisor-receptor">
        <p><b>NOMBRE:</b> {{ $json->emisor->nombre }}</p>
        @if(isset($json->emisor->nombreComercial) && $json->emisor->nombreComercial != null)
            <p class="mt-n015"><b>NOMBRE COMERCIAL:</b> {{ $json->emisor->nombreComercial }}</p>
        @endif
        <p class="mt-n015"><b>NIT:</b> {{ $json->emisor->nit }}</p>
        <p class="mt-n015"><b>NRC:</b> {{ $json->emisor->nrc }}</p>
        <p class="mt-n015"><b>ACTIVIDAD:</b> {{ $json->emisor->descActividad }}</p>
        <p class="mt-n015"><b>DIRECCIÓN:</b> {{ $json->emisor->direccion->complemento }}</p>
        <p class="mt-n015"><b>TELÉFONO:</b> {{ $json->emisor->telefono }}</p>
        <p class="mt-n015 lowerCaseEmail"><b>CORREO:</b> {{ $json->emisor->correo }}</p>
        @if(isset($json->emisor->tipoEstablecimiento) && $json->emisor->tipoEstablecimiento != null)
            <p class="mt-n015"><b>TIPO DE ESTABLECIMIENTO:</b> {{ $json->emisor->tipoEstablecimiento == '01' ? 'SUCURSAL / AGENCIA' : 'CASA MATRIZ' }}</p>
        @endif
    </div>
    <div class="col-1"></div>{{--Columna vacia--}}
@endif
