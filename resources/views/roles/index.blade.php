@extends('layouts.list')

@section('list')

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="w-25">Acciones</th>
                    <th scope="col">#</th>
                    <th scope="col">Rol</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $d)
                    <tr>
                        <td>
                            <div class="row">
                                @can($th['table'] . '.create')
                                    @if (Route::has($th['table'] . '.usuarios'))
                                        <div class="col-2">
                                            <a class="btn btn-light"
                                                href="{{ route($th['table'] . '.usuarios', ['id' => Crypt::encryptString($d->id)]) }}"
                                                role="button">
                                                <span class="mdi  mdi-account-key"></span>
                                            </a>
                                        </div>
                                    @endif
                                @endcan
                                <div class="col">
                                    <x-acciones :table="$th['table']" :d="$d" />
                                </div>
                            </div>

                        </td>
                        <th>{{ $loop->index + 1 }}</th>
                        <td>{{ $d->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Aun no se han agregado datos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
