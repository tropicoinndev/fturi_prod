@extends('layouts.form')

@section('form')
    <x-sucursales-form table="{{ $th['table'] }}" :data="$data"/>
@endsection
