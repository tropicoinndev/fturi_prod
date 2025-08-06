@extends('layouts.form')

@section('form')
    <x-productos-form table="{{ $th['table'] }}" :data="$data" />
@endsection