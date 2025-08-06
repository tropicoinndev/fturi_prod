@extends('layouts.form')

@section('form')
    <x-tipo_comprobantes-form table="{{ $th['table'] }}" :data="$data" />
@endsection
