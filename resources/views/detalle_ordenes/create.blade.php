@extends('layouts.form')

@section('form')
    <x-detalle_ordenes-form table="{{ $th['table'] }}" :data="$data"/>
@endsection
