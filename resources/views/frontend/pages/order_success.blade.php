@extends('frontend.layouts.master')
@section('title','Checkout Successful | Micromediabd')

@section('content')
<div class="container mt-4 mb-4">
    <div class="row d-flex cart align-items-center justify-content-center">
        <div class="col-md-10">
                <div class="row g-0">
                    <div class="col-md-12 border-right p-5">
                        <div class="text-center order-details">
                            <div class="d-flex justify-content-center mb-5 flex-column align-items-center"> 
                                <span class="check1"><i class="fas fa-check-circle fa-lg" style="color: #39ed2c !important;"></i></span> 
                                <span class="font-weight-bold">Order Confirmed</span> 
                                <small class="mt-2">Your illustration will go to you soon</small> 
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