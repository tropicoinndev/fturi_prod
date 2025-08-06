@extends('layouts.form')

@section('form')
    <x-proveedores-form table="{{ $th['table'] }}"  :data="$data"/>
@endsection
