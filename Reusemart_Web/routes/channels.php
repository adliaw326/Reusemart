<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('user.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('Pembeli.{pembeliID}Penitip.{penitipID}', function($user, $pembeliID, $penitipID){
    return ((int) $user->id === (int) $pembeliID || (int) $user->id === (int) $penitipID);
});

