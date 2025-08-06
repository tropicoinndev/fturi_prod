@extends('layouts.form')

@section('form')
    <x-detalle_productos-form table="{{ $th['table'] }}" :p="$p" :producto="$p->productos_id" />
@endsection