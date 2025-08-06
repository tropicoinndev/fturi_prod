@extends('layouts.app')

@section('style')
    <style>
        .bodega{
            min-height: 200px;
            transition: background 1s ease-out;
        }
        .pin{
            transition: opacity 1s ease-out;
            opacity: 0;
            height: 0;
            overflow: hidden;
        }
        .bodega:hover .pin{
            opacity: 1;
            height: auto;
        }
        .bodega:hover{
            background: #009688;
            color: #fff;
        }

    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row" style="margin-top: 55px !important;">
            <div class="col-12 ">
                <div class="card ">

                    <div class="card-body ">
                        <h3 class="card-title">BODEGAS</h3>
                        <p class="card-text">
                            <div class="row mt-5 mb-2 ">
                                @forelse ($bodegas as $b)
                                    @isset($b->relacionBodegas->bodega)
                                    <div class="col-12 col-sm-6 col-md-4  mb-3">
                                        <div class="card bodega border-1 border-dark">
                                            <div class="card-body d-flex">
                                                <div class="col align-self-center text-center">
                                                    <div class="col-12 h2 card-title text-uppercase">
                                                        {{ $b->relacionBodegas->bodega }}
                                                    </div>
                                                    <div class="card-text mb-2">
                                                        BODEGA
                                                    </div>
                                                    <div class="card-text pin">
                                                        <form action="{{ route('bodegas.auth') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="bodega"
                                                                value="{{ \Crypt::encryptString($b->id) }}">
                                                            <button class="btn btn-outline-light " type="submit">Acceder</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endisset
                                    @empty
                                    <div class="col-12">
                                        <div class="alert alert-warning" role="alert">
                                            <strong>
                                                No tienes acceso a ninguna bodega.
                                            </strong>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
