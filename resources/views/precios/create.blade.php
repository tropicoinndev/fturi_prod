@extends('layouts.form')

@section('form')
    <x-precios-form table="{{ $th['table'] }}" :data="$data" />
@endsection