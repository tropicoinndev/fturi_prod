@extends('layouts.form')

@section('form')
    <x-control_cortesias-form table="{{ $th['table'] }}" :data="data"/>
@endsection
