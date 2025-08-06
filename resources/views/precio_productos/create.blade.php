@extends('layouts.form')

@section('form')
    <x-precio_productos-form table="{{ $th['table'] }}" :data="$data" />
@endsection