<?php 
namespace App\Http\Services;

use Exception;
use App\Mail\CustomerOrderMail;
use Illuminate\Support\Facades\Mail;


trait MailService
{
    private function sendEmail($order)
    {
        try {

            // $order->customer_email = "mdalauddinjahin365@gmail.com";

            Mail::to($order->customer_email)->send(new CustomerOrderMail($order));

            return [
                'success'   => true,
                'msg'       => 'OK',
            ];
        } catch (\Exception $e) {
            return [
                'success'   => false,
                'msg'       => $e->getMessage(),
            ];
        }
    }

}