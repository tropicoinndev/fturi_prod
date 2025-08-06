@extends('layouts.form')

@section('form')
    <x-empleados-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
