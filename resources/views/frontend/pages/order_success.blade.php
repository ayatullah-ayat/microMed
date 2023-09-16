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
                                <span class="mt-3" style="color: #61af4b; font-size: 20px">Thank you, We've received your order.</span> 
                                <div class="mt-2">Your order is paid <strong>Cash On Delivery.</strong> You will receive an SMS notification regarding the order.</div> 
                                <div class="mt-2">Your order reference is <strong>{{ $order_no }}</strong> You will receive an SMS notification regarding the order.</div> 
                                <a target="_blank" href="{{ route('invoice_download', $order_no) }}" class="text-decoration-none invoice-link border-bottom">Download Invoice</a> 
                            </div> 
                            <button class="btn btn-danger btn-block order-button">Go to your Order</button>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection