@extends('layouts.form')

@section('form')
    <x-proveedores-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
