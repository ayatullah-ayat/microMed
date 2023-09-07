<?php

namespace App\Listeners;

use App\Models\Admin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CustomizeOrderNotification;

class CustomizeOrderListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        
        if ($event->customServiceOrder) {
            // client instance 
            $customer     = $event->customServiceOrder->customer;

            // admin instance
            $userAdmins  = Admin::all();

            $body       = null;

            foreach ($userAdmins as $userAdmin) {
                Notification::send($userAdmin, new CustomizeOrderNotification($event->customServiceOrder, $customer, $body));
            }
        }
    }
}
