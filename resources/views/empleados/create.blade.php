@extends('layouts.form')

@section('form')
    <x-empleados-form table="{{ $th['table'] }}" :data="data"/>
@endsection
