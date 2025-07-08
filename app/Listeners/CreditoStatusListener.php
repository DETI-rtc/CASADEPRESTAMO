<?php

namespace App\Listeners;

use App\Notifications\CreditoNotificacion;
use App\Events\CreditoStatusChangedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use DB;

class CreditoStatusListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CreditoStatusChangedEvent  $event
     * @return void
     */
    public function handle(CreditoStatusChangedEvent $event)
    {

        //if ($event->credito->nivel_aproba = 1) {
        //    $super = DB::table('nivel1_has_nivel2')->where('idnivel1',$event->credito->idanalista)->where('estado',1)->first();
        //    $usuario = User::findOrFail($super->idnivel2);
        //    Notification::send($usuario, new CreditoNotificacion($event->credito));
        //}

        if ($event->credito->nivel_aproba = 1) {
            //$super = DB::table('nivel1_has_nivel2')->where('idnivel1',$event->credito->idanalista)->where('estado',1)->first();
            $usuario = User::find(4);
        }

        if ($event->credito->nivel_aproba = 2) {
            //$super = DB::table('nivel1_has_nivel2')->where('idnivel1',$event->credito->idanalista)->where('estado',1)->first();
            $usuario = User::find(3);
        }
        if ($event->credito->nivel_aproba = 3 ) {
            $usuario = User::find(2);
        }
        if ($event->credito->nivel_aproba = 4 ) {
            $usuario = User::find(2);
        }
        Notification::send($usuario, new CreditoNotificacion($event->credito));
        //if ($event->credito->nivel_aproba = 4) {
        //    $super = DB::table('nivel1_has_nivel2')->where('idnivel1',$event->credito->idanalista)->where('estado',1)->first();
        //    $usuario = User::findOrFail($super->idnivel2);
        //    Notification::send($usuario, new CreditoNotificacion($event->credito));
        //}
        
        //$super = DB::table('nivel1_has_nivel2')->where('idnivel1',$event->credito->idanalista)->where('estado',1)->first();
        //$usuario = User::findOrFail($super->idnivel2);
         //   Notification::send($usuario, new CreditoNotificacion($event->credito));
            
    }
}
