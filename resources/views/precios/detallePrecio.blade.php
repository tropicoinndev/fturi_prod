@extends('layouts.form')

@section('form')

<x-detalle_precio :precio="\Crypt::encryptString($p->id)"
:productos="$productos"
:categorias="$categorias"
:productosPrecios="$productosPrecios"
:precios="$precios"
:precioDetalle="$p->detalle"
></x-detalle_precio>

@endsection
