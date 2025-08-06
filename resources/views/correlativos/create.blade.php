@extends('layouts.cajas')

@section('panel_caja')
<x-correlativos-form table="{{ $th['table'] }}" :data="$data" />
@endsection
