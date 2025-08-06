@extends('layouts.form')

@section('form')
    <x-cajas-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
