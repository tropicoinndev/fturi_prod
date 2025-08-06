@extends('layouts.form')

@section('form')
    <x-forma_pagos-form table="{{ $th['table'] }}" :p="$p" :data="$data"/>
@endsection
