<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
//Canal privado de notificaciones
Broadcast::channel('channel-notify.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
//Canal publico de notificaciones
Broadcast::channel('notify.{id}', function ($user) {
    return true;
});
Broadcast::channel('cajas.event.{caja_id}', function ($user) {
    return true;
});
Broadcast::channel('bodegas.event.{bodega_id}', function ($user) {
    return true;
});
Broadcast::channel('pedidos.cocina', function ($user) {
    return true;
});
Broadcast::channel('pedidos.bar', function ($user) {
    return true;
});
Broadcast::channel('pedidos.response.{caja_id}', function ($user) {
    return true;
});

Broadcast::channel('anticipos.event.{caja_id}', function ($user) {
    return true;
});

