@extends('layouts.form')

@section('form')
    <x-cortesias-form table="{{ $th['table'] }}" :data="data"/>
@endsection
