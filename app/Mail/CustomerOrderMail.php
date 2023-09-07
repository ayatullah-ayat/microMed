<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $data;

    public function __construct(Order $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->orderStatus( $this->data->status ?? null );
        
        return $this->markdown('mail.customer-order-mail', ['data' => $this->data, 'subject' => $subject])
        ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
        ->subject($subject);
    }


    private function orderStatus($status){
        $subject = '';
        switch ($status) {
            case 'pending':
                $subject = "Order Place";
                break;
            case 'confirm':
                $subject = "Order Confirmation";
                break;
            case 'processing':
                $subject = "Order Confirmation";
                break;
            case 'cancelled':
                $subject = "Order Cancellation";
                break;
        }

        return $subject;
    }
}
