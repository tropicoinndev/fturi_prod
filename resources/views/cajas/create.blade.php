@extends('layouts.form')

@section('form')
    <x-cajas-form table="{{ $th['table'] }}" :data="$data" :p="$p ?? null" />
@endsection
