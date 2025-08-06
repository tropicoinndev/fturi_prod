@extends('layouts.form')

@section('form')

<x-precio :precio="\Crypt::encryptString($p->id)"
:cajas="$cajas"
:cajasP="$cajasP"
:preciosP="$preciosP"
:precios="$precios"

></x-precio>

@endsection
