@extends('frontend.layouts.master')
@section('title','Your Cart')

@section('content')
<!-- Cart Area-->
<section class="product-cart-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="cart-items-details cart-page">
                    <table class="table table-borderless table-sm">
                        <!-- --------------------- heading -------------------  -->
                        <tr class="bg-danger cart-header">
                            <th>Product</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th class="text-center"> Price(Tk) </th>
                            <th class="text-center"> Amount </th>
                            <th class="text-right"> Total(Tk) </th>
                        </tr>
                        <!-- --------------------- heading -------------------  -->

                       <tbody class="cart-tbody">
                           @php
                            $grandTotal = 0;
                            @endphp
                            
                            @if(isset($cartProducts) && count($cartProducts))
                            
                            @foreach ($cartProducts as $key => $item)

                                @php 
                                    $productQty = 1;
                                    $productColor = null;
                                    $productSize = null;
                                @endphp

                                @foreach ($cartQtys as $cartQty)
                                    @php
                                        if(isset($cartQty['product_id']) && $cartQty['product_id'] == $item->id){
                                            $productQty = (int)$cartQty['qty'] ?? 1;
                                            $productColor = $cartQty['color'] ?? null;
                                            $productSize = $cartQty['size'] ?? null;
                                            break;
                                        }
                                    @endphp
                                @endforeach
                                @php $grandTotal += (salesPrice($item) ?? 0.0) * $productQty; @endphp
                            
                            <tr data-productid="{{ $item->id }}">
                                <td class="align-middle">
                                    <div class="cart-info">
                                       <a href="javascript:void(0)" data-productid="{{ $item->id }}" class="removeFromCart"><i class="fa-solid fa-xmark fa-lg"></i></a>
                                       <a href="{{ route('product_detail',$item->id ) }}"> <img src="{{asset($item->product_thumbnail_image ?? null)}}" alt=""></a>
                                       <a href="{{ route('product_detail',$item->id ) }}" class="text-decoration-none"> <p class="text-dark">{{ $item->product_name ?? 'N/A' }}</p></a>
                                    </div>
                                </td>
                                <td class="align-middle product-color" data-color="{{ $productColor }}">{{ $productColor }}</td>
                                <td class="align-middle product-size" data-size="{{ $productSize }}">{{ $productSize }}</td>
                                <td class="text-center align-middle Sale_Price" data-salesprice={{ salesPrice($item) ?? 0.0 }}> {{ salesPrice($item) ?? 0.0 }} </td>
                                <td class="text-center align-middle btn-qty-cell">
                                    <div class="btn-group" role="group">
                                        <button type="button" id="minus_{{ $item->id }}" data-increment-type="minus"
                                            class="cartstateChange btn btn-light"><span class="fa fa-minus"></span></button>
                                        <button type="button" class="btn btn-light count" data-productid="{{ $item->id }}" id="count_{{ $item->id }}" data-min="1" data-max="{{ $item->total_stock_qty > 10 ? 10 : $item->total_stock_qty }}">{{ $productQty }}</button>
                                        <button type="button" id="plus_{{ $item->id }}" data-increment-type="plus"
                                            class="cartstateChange btn btn-light"><span class="fa fa-plus"></span></button>
                                    </div>
                                </td>
                                <td class="text-right px-2 align-middle subtotal"> {{ (salesPrice($item) ?? 0.0) * $productQty }}</td>
                            </tr>
                            @endforeach
                            
                            @else
                            
                            <tr>
                                <td colspan="4">
                                    <div class="w-100 alert alert-danger text-center">
                                        <h5>Your cart is Empty</h5>
                                    </div>
                                </td>
                            </tr>
                            
                            @endif
                       </tbody>


                        <!-- -------------------- footer ---------------------------  -->
                        @if(isset($cartProducts) && count($cartProducts))
                        <tr class="fw-bold grandTotalSection">
                            <td colspan="3" class="text-dark text-end"> <span>Grand Total</span></td>
                            <td class="px-2" id="grandTotal"> {{ $grandTotal }} </td>
                        </tr>
                        <tr class="cart-checkout-footer" data-shopuri="{{ route('shop_index') }}">
                            <td colspan="2"></td>
                            <td colspan="2">
                                <a href="{{ route('checkout_index') }}" class="btn btn-danger btn-sm text-decoration-none text-white w-100">CheckOut</a>
                            </td>
                        </tr>
                        @else 
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">
                                <a href="{{ route('shop_index') }}" class="btn btn-danger btn-sm text-decoration-none text-white w-100">Buy</a>
                            </td>
                        </tr>
                        @endif 
                        <!-- -------------------- footer ---------------------------  -->

                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Contact Area-->
<section class="container-fluid call-center-area">

    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex align-items-center justify-content-center">
                <div class="call-center text-center">
                    <h2 class="text-capitalize">You didn't find what you were looking for? Please Call:<span> <a href="tel:01971819813" class="text-decoration-none" type="button">0197-1819-813</a></span></h2>
                </div>

            </div>
        </div>
    </div>

</section>

@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/frontend/pages/css/cart.css') }}">
@endpush