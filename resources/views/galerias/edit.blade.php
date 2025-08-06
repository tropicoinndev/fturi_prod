@extends('layouts.form')

@section('form')
    <x-galerias-form table="{{ $th['table'] }}"  :p="$p" :data="$data" />
@endsection
