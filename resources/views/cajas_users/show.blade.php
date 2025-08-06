@extends('layouts.form')

@section('form')
    <x-cajas_users-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
