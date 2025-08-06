@extends('layouts.app')

@section('content')
    <style>
        body {
            background: #4DB6AC;
            height: 93.2vh;
            margin: 0;
            padding: 0;
        }

        .login {
            background: #fafafae4;

        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-5 mt-5">
                <div class="card pb-4 pt-4 login shadow">
                    <div class="card-body">
                        <img class="img-fluid w-25 mx-auto d-block" src="{{ asset(env('logo_lg', 'images/logo-lg.png')) }}"
                            alt="Logo de {{ env('empresa') }}">
                        <div class="col-12 text-center fs-3 mb-4">
                            Iniciar sesión
                        </div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Correo electrónico</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus
                                        placeholder="Correo electrónico">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Contraseña</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password" placeholder="Contraseña">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Iniciar sesión
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
