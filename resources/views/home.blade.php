@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
                <div id="app2" class="card-body row">
                    <h3>Render de variable locales</h3>
                    <div class="col-12 mb-2" v-for="l in list">
                        <example :name="l.name" />
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="module">
    let privado = 'notify.{{ auth()->id() }}'



const userId = '{{ auth()->id() }}';
    let evento = 'NotifyPublic';

    console.log(privado);
    window.Echo.channel('events')
        .listen(evento, (e) => console.log(e));

    window.Echo.private(privado)
        .listen('NotifyPrivate', (e) => console.log(e));

    window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        console.log(notification);
    });
    //console.log(window.Echo)
</script>
@endsection


