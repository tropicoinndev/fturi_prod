@extends('layouts.form')

@section('form')
<x-contribuyente-form table="{{ $th['table'] }}" :p="$p" :cliente="$p->clientes_id" />
@endsection
