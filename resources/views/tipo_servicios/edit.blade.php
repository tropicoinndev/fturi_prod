@extends('layouts.form')

@section('form')
    <x-tipo_servicios-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
