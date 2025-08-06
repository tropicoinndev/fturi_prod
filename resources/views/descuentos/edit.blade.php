@extends('layouts.form')

@section('form')
    <x-descuentos-form table="{{ $th['table'] }}" :p="$p"/>
@endsection
