@extends('layouts.form')

@section('form')
<x-contactos-form table="{{ $th['table'] }}" :p="$p" />
@endsection
