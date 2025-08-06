@extends('layouts.form')

@section('form')
<x-mantenimientos-form table="{{ $th['table'] }}" :p="$p" :habitaciones="$p->habitaciones_id" />
@endsection