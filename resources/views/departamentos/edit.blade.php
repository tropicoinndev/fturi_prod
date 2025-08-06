@extends('layouts.form')

@section('form')
    <x-departamentos-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection