@extends('layouts.form')

@section('form')
    <x-categoria_fotos-form table="{{ $th['table'] }}" :p="$p" />
@endsection