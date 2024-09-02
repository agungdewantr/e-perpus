<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Pusher\Pusher;

class NotifkasiController extends Controller
{
    public function getnotif($param){
        $list_notif = Notifikasi::where('user_id_to', $param)
                        // ->where('is_active', 1)
                        ->orderBy('updated_at', 'desc')
                        ->get();
        return $list_notif;
    }
    public function notif(){
        $options = [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ];

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        $pusher->trigger(auth()->user()->id. '-notification', 'notify', [
            'message' => 'Coba',
            'isi' => 'CobaCobaCobaCobaCobaCobaCobaCoba',
            'route' => route('detailanggota.index', null)
        ]);
        $notifikasi = new Notifikasi();
        $notifikasi->user_id_from = auth()->user()->id;
        $notifikasi->user_id_to = auth()->user()->id;
        $notifikasi->tentang = 'Coba';
        $notifikasi->route = route('detailanggota.index', null);
        $notifikasi->isi = 'CobaCobaCobaCobaCobaCobaCobaCoba.';
        $notifikasi->save();
    }

    public function readnotif($param){
        Notifikasi::where('user_id_to', $param)
                    ->where('is_active', 1)
                    ->update(['is_active' => 0]);
        return 'sucess';
    }
}
