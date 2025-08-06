@extends('layouts.form')

@section('form')

<x-bodegas :bodegaid="\Crypt::encryptString($p->id)"></x-bodegas>

@endsection
