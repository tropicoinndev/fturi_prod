@extends('layouts.form')

@section('form')
    <x-bodegas-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection