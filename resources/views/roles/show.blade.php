@extends('layouts.form')

@section('form')

<x-permissions :role="\Crypt::encryptString($p->id)"></x-permissions>

@endsection
