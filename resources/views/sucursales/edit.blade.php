@extends('layouts.form')

@section('form')
    <x-sucursales-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
