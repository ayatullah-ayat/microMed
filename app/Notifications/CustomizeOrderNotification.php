<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\Custom\CustomServiceOrder;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomizeOrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $customServiceOrder;
    public $customer;
    public $body;

    public function __construct(CustomServiceOrder $customServiceOrder, $customer, $body)
    {
        $this->customServiceOrder    = $customServiceOrder;
        $this->customer = $customer;
        $this->body     = $body;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'mail',
            'database'
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');

        return (new MailMessage)
            ->markdown('templates.customize_order_confirmation', ['data' => $this->customServiceOrder, 'customer'  => $this->customer, 'admin' => $notifiable])
            ->subject('Customize Order Request!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order_id'      => $this->customServiceOrder->id ?? null,
            'order_no'      => $this->customServiceOrder->order_no ?? null,
            'customer_id'   => $this->customer->id ?? null,
            'customer_name' => $this->customer->name ?? null,
            'body'          => json_encode($this->customServiceOrder) ?? null,
        ];
    }
}
