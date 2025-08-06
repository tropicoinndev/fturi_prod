@extends('layouts.form')

@section('form')
    <x-tipo_camas-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
