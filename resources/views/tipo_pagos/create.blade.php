@extends('layouts.form')

@section('form')
    <x-tipo_pagos-form table="{{ $th['table'] }}" :data="$data"/>
@endsection
