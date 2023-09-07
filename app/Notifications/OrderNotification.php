<?php

namespace App\Notifications;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification
{
    use Queueable;


    public $order;
    public $customer;
    public $body;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    // public function __construct(Order $order, Customer $customer, $body)
    public function __construct(Order $order, $customer, $body)
    {
        $this->order    = $order;
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
            ->markdown('templates.order_confirmation', ['data'=> $this->order, 'customer'  => $this->customer, 'admin' => $notifiable])
            ->subject('Order Approval Request!');
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
            'order_id'      => $this->order->id ?? null,
            'order_no'      => $this->order->order_no ?? null,
            'customer_id'   => $this->customer->id ?? null,
            'customer_name' => $this->customer->name ?? null,
            'body'          => json_encode($this->order) ?? null,
        ];
    }
}
