@extends('frontend.layouts.master')
@section('title','Checkout Successful | Micromediabd')

@section('content')
<div class="container mt-4 mb-4">
    <div class="row d-flex justify-content-center">
        <div class="col-md-10">
                <div class="row g-0">
                    <div class="col-md-12 border-right p-3">
                        <div class="text-center">
                            <div class="d-flex justify-content-center mb-5 flex-column align-items-center"> 
                                <span class="check1"><i class="fa-solid fa-circle-check fa-2xl" style="color: #61af4b !important;"></i></span> 
                                <span class="mt-3" style="color: #61af4b; font-size: 28px; font-weight: 400">Thank you, We've received your order.</span> 
                                <div class="mt-2">Your order is paid <strong>Cash On Delivery.</strong> You will receive an SMS notification regarding the order.</div> 
                                <div class="mt-2">Your order reference is <strong>{{ $order_no }}</strong> and total order value is <strong>{{ $order->order_total_price }}</strong></div> 
                                <div class="mt-2">Your shipping address is <strong>{{ $order->shipping_address }}</strong></div> 
                                <div class="mt-2">Please remember these information for any kind of future inconvenience regarding your order</div> 
                                <a target="_blank" href="{{ route('invoice_download', $order_no) }}" class="btn btn-secondary mt-4 text-white text-decoration-none invoice-link border-bottom">Download Invoice</a> 
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection