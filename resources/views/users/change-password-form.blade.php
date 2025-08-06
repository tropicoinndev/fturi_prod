<!-- resources/views/auth/change-password.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-uppercase">Cambiar Contraseña</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success d-flex justify-content-between align-items-center" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close text-end" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger d-flex justify-content-between align-items-center" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close text-end" data-bs-dismiss="alert" aria-label="Close"></button>

                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.changePassword') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Contraseña Actual</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nueva Contraseña</label>
                                <input type="password" name="new_password" class="form-control" required>
                                
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Guardar contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
