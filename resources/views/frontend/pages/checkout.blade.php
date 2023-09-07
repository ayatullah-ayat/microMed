@extends('frontend.layouts.master')
@section('title','Checkout')

@section('content')
<!-- Checkout Area-->
<div class="container-fluid checkout-area my-5">
    <div class="container">
        <div class="row my-4">

            @php
                $pQty = 1;
                $totalPrice = 0;
                $couponPrice = session('coupon') ? session('coupon')['total_discount_amount'] : 0;
                $grandtotalPrice=0;
            @endphp

            @isset($product)
                @if($product->total_product_unit_price && $product->total_product_qty)
                    @php
                        $totalprice = $product->total_product_unit_price;
                        $totalqty = $product->total_product_qty;
                        $unitprice = $totalprice / $totalqty;
                    @endphp
                @endif

                @if ( $product->total_product_unit_price)
                    @php
                        $pQty = isset($_GET['q']) ? $_GET['q'] : 1;
                        $saleprice = (salesPrice($product) ?? 0.0) * (int)$pQty;
                        $totalPrice += $saleprice;
                        $grandtotalPrice = $totalPrice;
                    @endphp
                @endif

            @endisset

            {{-- /Product TO session or cart/ --}}
            @if( isset($cartProducts) && !isset($product->product_name))


                @foreach ($cartProducts as $key => $item)
                    @php $productQty = 1; @endphp

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
                    @php
                        $totalPrice += (salesPrice($item) ?? 0.0) * $productQty;
                        $grandtotalPrice = $totalPrice; 
                    @endphp
                @endforeach
            @endif

            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Overview: </span>
                    <span class="badge badge-secondary badge-pill text-dark"></span>
                </h4>
                <ul class="list-group mb-3">

                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0"> Product Price (Tk): </h6>
                        </div>
                        <span class="text-muted" id="producterdum">{{ $totalPrice ?? 0.0 }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <div class="text-success">
                            <h6 class="my-0"> Discount (Tk): <span type="button" class="remove-coupon" data-coupon="{{ session('coupon') ? session('coupon')['coupon_code'] : ''}}">
                                    {!! session('coupon') ? '<i class="fa fa-trash"></i>' : ''!!}
                                </span> </h6>

                        </div>
                        <span class="text-success" id="discount">{{ $couponPrice }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <span> Grand Total (Tk):</span>
                        <strong id="surbomot"> {{ $grandtotalPrice - $couponPrice }} </strong>
                    </li>

                </ul>

                <form id="couponForm" class="card" action="{{ route('checkCoupon')}}" method="POST">
                    <div class="input-group">
                        <input type="text" id="coupon" class="form-control" placeholder="Coupon Code">
                        <button type="submit" class="btn btn-danger">Redeem</button>
                    </div>
                </form>
                <p class="alert my-2 py-1" id="coupon_msg"></p>
            </div>

            <div class="col-md-8 order-md-1">
                <!-- Checkout cart start-->
                <section class="product-cart-area">
                    <div class="containerx">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="cart-items-details">
                                    <table class="table table-borderless table-sm">
                                        <!-- --------------------- heading -------------------  -->
                                        <tr class="bg-danger">
                                            <th>Product</th>
                                            <th>Color</th>
                                            <th>Size</th>
                                            <th class="text-center">Price (Tk)</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-right">Total (Tk)</th>
                                        </tr>
                                        <!-- --------------------- heading -------------------  -->

                                        {{-- @dd($product) --}}
                                        <tbody class="checkout-cart-tbody">
                                            @if( isset($product) && $product->product_name)

                                            @php
                                            $color = request('c');
                                            $size = request('s');
                                            $qty = request('q');
                                            $orderableData = [
                                            'product_id' => $product->id,
                                            'color' => $color,
                                            'size' => $size,
                                            'qty' => (int)$qty,
                                            'sales_price' => salesPrice($product) ?? 0.0,
                                            'subtotal' => (salesPrice($product) ?? 0.0) * (int)$qty,
                                            ];
                                            @endphp

                                            <tr data-productid="{{ $product->id }}" data-orderable-product="{{ json_encode($orderableData) }}">
                                                <td class="align-middle">
                                                    <div class="cart-info">
                                                        {{-- <a href="javascript:void(0)"><i class="fa-solid fa-xmark fa-lg"></i></a> --}}
                                                        <a href="{{ route('product_detail',$product->id ) }}">
                                                            <img src="{{asset($product->product_thumbnail_image ?? '')}}" alt="">
                                                        </a>
                                                        <a href="{{ route('product_detail',$product->id ) }}" class="text-decoration-none">
                                                            <p class="text-dark">{{ $product->product_name ?? 'N/A'}}</p>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="align-middle product-color" data-color="{{ $productColor }}">{{ $productColor }}</td>
                                                <td class="align-middle product-size" data-size="{{ $productSize }}">{{ $productSize }}</td>
                                                <td class="text-center align-middle Sale_Price" data-salesprice="{{ salesPrice($product) ?? 0.0}}"> {{ salesPrice($product) ?? 0.0  }}
                                                </td>
                                                <td class="text-center align-middle btn-qty-cell">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" data-increment-type="minus" class="stateChange btn btn-light"><span class="fa fa-minus"></span></button>
                                                        <button type="button" class="btn btn-light count" data-min="1" data-max="{{ $product->total_stock_qty > 10 ? 10 : $product->total_stock_qty }}" value="">{{ $pQty }}</button>
                                                        <button type="button" data-increment-type="plus" class="stateChange btn btn-light"><span class="fa fa-plus"></span></button>
                                                    </div>
                                                </td>
                                                <td class="text-right px-2 align-middle subtotal"> {{ $saleprice }}</td>
                                            </tr>
                                            @else

                                            @if( isset($cartProducts) && count($cartProducts))

                                            @foreach ($cartProducts as $k => $product)

                                            @php
                                            $productQty = 1;
                                            $color = null;
                                            $size = null;
                                            $qty = $productQty;
                                            @endphp

                                            @foreach ($cartQtys as $cartQty)
                                            @php
                                            if(isset($cartQty['product_id']) && $cartQty['product_id'] == $product->id){
                                            $productQty = (int)$cartQty['qty'] ?? 1;
                                            $color = $cartQty['color'] ?? null;
                                            $size = $cartQty['size'] ?? null;
                                            $qty = $productQty;
                                            break;
                                            }
                                            @endphp
                                            @endforeach

                                            @php
                                            $orderableData = [
                                            'product_id' => $product->id,
                                            'color' => $color,
                                            'size' => $size,
                                            'qty' => (int)$qty,
                                            'sales_price' => salesPrice($product) ?? 0.0,
                                            'subtotal' => (salesPrice($product) ?? 0.0) * (int)$qty,
                                            ];
                                            @endphp

                                            <tr data-productid="{{ $product->id }}" data-orderable-product="{{ json_encode($orderableData) }}">
                                                <td class="align-middle">
                                                    <div class="cart-info">
                                                        <a href="javascript:void(0)" data-productid="{{ $product->id }}" class="removeFromCheckout"><i class="fa-solid fa-xmark fa-lg"></i></a>
                                                        <a href="{{ route('product_detail',$product->id ) }}">
                                                            <img src="{{asset($product->product_thumbnail_image ?? '')}}" alt="">
                                                        </a>
                                                        <a href="{{ route('product_detail',$product->id ) }}" class="text-decoration-none">
                                                            <p class="text-dark text-capitalize" data-toggle="tooltip" data-placement="bottom" title="{{ $product->product_name }}">{{ \Str::limit($product->product_name, 15) ?? 'N/A'}}</p>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="align-middle product-color" data-color="{{ $color }}">{{ $color }}</td>
                                                <td class="align-middle product-size" data-size="{{ $size }}">{{ $size }}</td>
                                                <td class="text-center align-middle Sale_Price" data-salesprice="{{ salesPrice($product) ?? 0.0 }}"> {{ salesPrice($product) ?? 0.0 }}
                                                </td>
                                                <td class="text-center align-middle btn-qty-cell">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" data-increment-type="minus" class="stateChange btn btn-light"><span class="fa fa-minus"></span></button>
                                                        <button type="button" class="btn btn-light count" data-min="1" data-max="{{ $product->total_stock_qty > 10 ? 10 : $product->total_stock_qty }}" data-productid="{{ $product->id }}">{{ $productQty }}</button>
                                                        <button type="button" data-increment-type="plus" class="stateChange btn btn-light"><span class="fa fa-plus"></span></button>
                                                    </div>
                                                </td>
                                                <td class="text-right px-2 align-middle subtotal"> {{ (salesPrice($product) ?? 0.0) * $productQty }}</td>
                                            </tr>
                                            @endforeach

                                            @else
                                            <tr>
                                                <td colspan="4">
                                                    <div class="w-100 alert alert-danger text-center">
                                                        <h5>No Data Found!</h5>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                            @endif
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Checkout cart end -->

                <!-- Checkout Shipment start-->
                <h4 class="mb-3">Shipment Address:</h4>
                <div class="row" id="shipment-form">

                    <div class="col-md-6 px-2 mb-3">
                        <div class="form-group">
                            <input type="text" class="form-control border" placeholder="Write Your Name" required name="full_name" value="@auth{{auth()->guard('web')->user()->name ?? ''}}@endauth">
                            <span class="v-msg text-danger"></span>
                        </div>
                    </div>

                    <div class="col-md-6 px-2 mb-3">
                        <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text form-control" id="basic-addon1">+88</span>
                        </div>
                            <input type="text" class="form-control border" placeholder="Write Your Phone No" required name="mobile_no" value="@auth{{auth()->guard('web')->user()->profile ? auth()->guard('web')->user()->profile->mobile_no : ''}}@endauth">
                            <span class="v-msg text-danger"></span>
                        </div>
                    </div>

                    <div class="col-md-6 px-2 mb-3">
                        <div class="form-group">
                            <input type="email" class="form-control border" placeholder="Write Your Email" name="email" value="@auth{{auth()->guard('web')->user()->email ?? ''}}@endauth">
                            <span class="v-msg text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6 px-2 mb-3">
                        <div class="form-group">
                            <select class="border" name="city" id="city" style="width: 100%; height: 28px;">
                                <option value="0" disabled selected>Select City</option>
                                @foreach($cities as $city)
                                <option value="{{ $city->name }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 px-2 mb-3">
                        <div class="form-group">
                            <textarea name="address" id="" style="resize:vertical" cols="30" rows="10" required class="form-control" placeholder="Write Your Full Address"></textarea>
                            <span class="v-msg text-danger"></span>
                        </div>
                    </div>

                    <div class="col-md-12 px-2 mb-3">
                        <strong>Select a Payment</strong>
                        <div class="form-group">
                            <input type="radio" name="payment_type" id="cashondelivery" checked value="Cash On Delivery">
                            <label for="cashondelivery">Cash On Delivery</label>
                            {{-- <input type="radio" name="payment_type" id="bkash" value="Bkash">
                                <label for="bkash">Bkash</label>
                                <input type="radio" name="payment_type" id="sslcommerz" value="SSL Commerz">
                                <label for="sslcommerz">SSL Commerz</label> --}}
                        </div>
                        <span class="v-msg text-danger payment-v-msg"></span>

                        <div class="payment-type-box">
                            <div class="row">
                                {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control border" name="card_no">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control border" name="card_no">
                                        </div>
                                    </div> --}}
                                <div class="col-md-4 my-3">
                                    <button class="btn btn-sm btn-danger text-white text-center" id="makePayment"> <span class="fa fa-paper-plane mx-1" style="color: #fff !important;"></span>Place Order</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Checkout Shipment end-->

            </div>

        </div>
    </div>
</div>


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
<link rel="stylesheet" href="{{ asset('assets/frontend/pages/css/checkout.css') }}">
<link rel="stylesheet" href="{{ asset('assets/frontend/pages/css/cart.css') }}">
@endpush

@push('js')
<script>
    let timeId = null;
    $(function() {
        $(document).on("click", '.stateChange', incrementDecrementCount2)
        $(document).on("submit", '#couponForm', applyCoupon)
        $(document).on("change input", '[required]', checkSingleValidation)
        $(document).on("click", '#makePayment', submitToMakeOrder)
        $(document).on("click", '.remove-coupon', removeCoupon)
    });

    function incrementDecrementCount2(e) {
        let
            elem = $(this),
            countElem = elem.closest('tr').find('.count'),
            ref = elem.attr('data-increment-type'),
            count = Number(countElem.text() ?? 0),
            pattern1 = /(plus|increment|increament)/im,
            pattern2 = /(minus|decrement|decreament)/im,
            minCount = Number(countElem?.attr('data-min') ?? 1),
            maxCount = Number(countElem?.attr('data-max') ?? 10),
            price = Number(elem.closest('tr').find('.Sale_Price').attr('data-salesprice') ?? 0);

        if (pattern1.test(ref)) {
            count++;
            if (count > maxCount) count = maxCount;

        } else if (pattern2.test(ref)) {

            count--;
            if (count < minCount) count = minCount;
        }

        countElem.text(count);

        priceCalculation2((price * count), elem.closest('tr').find('.subtotal'))
    }

    function priceCalculation2(price, target) {

        let pattern = /^[+-]?\d+(\.\d+)$/im;
        if (pattern.test(price)) {
            price = price.toFixed(3);
        }

        target.text(price);
        overview2();

    }

    function overview2() {
        let
            pattern = /^[+-]?\d+(\.\d+)$/im,
            totalProduct = 0,
            grandTotal = 0,
            disCountPrice = Number($('#discount').text() ?? 0),
            rows = $(document).find('.cart-items-details table').find(`tr[data-productid]`);

        [...rows].forEach(row => {
            let itemCount = Number($(row).find('.count').text() ?? 0);
            let itemPrice = Number($(row).find('.Sale_Price').attr('data-salesprice') ?? 0);
            totalProduct += itemPrice * itemCount;
        })


        grandTotal = Number(totalProduct) - Number(disCountPrice)

        if (pattern.test(totalProduct)) {
            totalProduct = totalProduct.toFixed(3);
            grandTotal = grandTotal.toFixed(3)
        }

        $('#producterdum').text(totalProduct);
        $('#surbomot').text(grandTotal);
    }

    function getOrupdateProductInfo() {

        let
            orderObject = {};
        total_product_price = Number($('#producterdum').text());
        total_discount_price = Number($('#discount').text());
        grand_total = Number($('#surbomot').text());
        rows = [...$(document).find(`tr[data-productid]`)],
            orderableDataArr = [];

        rows.forEach(row => {
            let orderableData = $(row)?.attr('data-orderable-product') ? JSON.parse($(row)?.attr('data-orderable-product')) : null;
            if (orderableData) {
                orderableData.qty = parseInt($(row).find('.count').text());
                orderableData.subtotal = Number($(row).find('.subtotal').text());
            }

            orderableDataArr.push(orderableData);

        });

        orderObject = {
            total_product_price,
            total_discount_price,
            grand_total,
            products: orderableDataArr,
        };

        return orderObject;

    }

    function shipmentInfo() {
        // form data
        let name = $('input[name="full_name"]').val();
        let mobile_no = $('input[name="mobile_no"]').val();
        let email = $('input[name="email"]').val();
        let address = $('textarea[name="address"]').val();
        let city = $('#city').val();
        let payment_type = $('input[name="payment_type"]:checked').val();

        return {
            name,
            mobile_no,
            email,
            address,
            city,
            payment_type
        };
    }

    function resetShipmentInfo() {
        // form data
        $('input[name="full_name"]').val('');
        $('input[name="mobile_no"]').val('');
        $('input[name="email"]').val('');
        $('textarea[name="address"]').val('');
        $('input#cashondelivery').prop('checked', true);

    }

    function validationErrorCheck() {
        // form data
        let
            isValid = true;
        parentWrapper = $('#shipment-form');

        [...parentWrapper.find('[required]')].forEach(elem => {
            const value = $(elem).val().trim();

            $(elem).parent().find('.v-msg').text(``);

            if (!value) {
                $(elem).parent().find('.v-msg').text(`${capitalize($(elem).attr('name')) ?? 'This Field '} is Required!`);
                isValid = false;
            }

        });

        if (!$('input[name="payment_type"]:checked').val()) {
            $('.payment-v-msg').text('Please Select a Payment Type!')
            isValid = false;
        } else {
            $('.payment-v-msg').text('')
        }

        //payment_type

        return isValid;

    }

    function checkSingleValidation() {
        let elem = $(this);

        if (!elem.val().trim()) {
            elem.parent().find('.v-msg').text(`${capitalize(elem.attr('name')) ?? 'This Field '} is Required!`);
            elem.addClass('is-invalid')
            return false;
        }

        elem.parent().find('.v-msg').text(``);
        elem.removeClass('is-invalid')
    }


    function applyCoupon(e) {
        e.preventDefault();

        let
            form = $(this),
            method = form.attr('method'),
            url = form.attr('action'),
            couponCode = form.find('#coupon').val(),
            producterdumElem = $('#producterdum');
        productPrice = Number(producterdumElem.text());

        let orderData = getOrupdateProductInfo();

        if (!orderData?.products?.length) {
            alert("Please select atleast 1 Product!")
            return false;
        }

        clearTimeout(timeId)

        ajaxFormToken();

        timeId = setTimeout(() => {
            $.ajax({
                url: url,
                type: method ?? "POST",
                data: {
                    order: orderData,
                    coupon: couponCode
                },
                cache: false,
                success: function(res) {

                    if (res.success) {
                        $('#discount').text(res.data.total_discount_amount);
                        form.find('#coupon').val('');
                        $('#surbomot').text(Number(productPrice - Number(res.data.total_discount_amount)).toFixed(2));
                        $('.remove-coupon').html('<i class="fa fa-trash"></i>')
                        $('.remove-coupon').attr('data-coupon', couponCode);
                        $('#coupon_msg').text(res?.msg).addClass('alert-success').removeClass('alert-danger')

                    } else {
                        $('#coupon_msg').text(res?.msg).addClass('alert-danger').removeClass('alert-success')
                        form.find('#coupon').val('');
                    }

                    setTimeout(() => {
                        $('#coupon_msg').text('').removeClass('alert-danger').removeClass('alert-success')
                    }, 3000)

                    console.log(res);
                },
                error: function(error) {
                    console.log(error);
                    $('#coupon_msg').text(error.responseJSON?.msg).addClass('alert-danger').removeClass('alert-success')
                    setTimeout(() => {
                        $('#coupon_msg').text('').removeClass('alert-danger').removeClass('alert-success')
                    }, 3000)

                    form.find('#coupon').val('');

                }
            });
        }, 500);

        // if success then add discount price 

    }

    function removeCoupon() {
        let elem = $(this),
            coupon = elem.attr('data-coupon'),
            discountElem = $('#discount'),
            surbomotElem = $('#surbomot'),
            producterdum = Number($('#producterdum').text() ?? 0),
            discountAmount = Number(discountElem.text() ?? 0);
        grandAmount = Number(surbomotElem.text() ?? 0);

        clearTimeout(timeId)

        ajaxFormToken();

        timeId = setTimeout(() => {
            $.ajax({
                url: `{{ route('removeCoupon') }}`,
                type: "POST",
                data: {
                    coupon: coupon
                },
                cache: false,
                success: function(res) {

                    if (res.success) {

                        let currentGTotal = grandAmount + Number(res?.total_discount_amount ?? 0);
                        $('#discount').text(0);
                        $('#surbomot').text(Number(currentGTotal).toFixed(3));
                        elem.html('');
                        elem.attr('data-coupon', '');
                        $('#coupon_msg').text(res?.msg).addClass('alert-success').removeClass('alert-danger')
                    } else {
                        $('#coupon_msg').text(res?.msg).removeClass('alert-success').addClass('alert-danger')
                    }

                    setTimeout(() => {
                        $('#coupon_msg').text('').removeClass('alert-danger').removeClass('alert-success')
                    }, 3000)

                    console.log(res);
                },
                error: function(error) {
                    console.log(error);
                    $('#coupon_msg').text(error.responseJSON?.msg).removeClass('alert-success').addClass('alert-danger')

                    setTimeout(() => {
                        $('#coupon_msg').text('').removeClass('alert-danger').removeClass('alert-success')
                    }, 3000)
                }
            });
        }, 500);
        //
    }


    function submitToMakeOrder() {
        //

        let orderBtn = $(this);
        let orderData = getOrupdateProductInfo();
        console.log('orderData', orderData);

        let shipmentData = shipmentInfo();

        if (!orderData?.products?.length) {
            alert("Please select atleast 1 Product!")
            return false;
        }

        // validation 

        if (!validationErrorCheck()) return false;

        // make payment & send ajax request to insert order data
        ajaxFormToken();

        clearTimeout(timeId)

        timeId = setTimeout(() => {
            $.ajax({
                url: `{{ route('store_order') }}`,
                type: "POST",
                data: {
                    order: orderData,
                    shipment: shipmentData
                },
                cache: false,
                beforeSend() {
                    orderBtn.html(`<span class="fa fa-spinner fa-spin mx-1" style="color: #fff !important;"></span>Processing ...`);
                },
                success: function(res) {
                    // if success then reset form

                    if (res?.success) {

                        _toastMsg(res?.msg ?? 'Success!', 'success');

                        resetShipmentInfo();

                        setTimeout(() => {
                            window.location.href = "{{ route('order_success') }}" + '/' + res.data.order_no;
                        }, 3000)
                    } else {
                        _toastMsg(res?.msg ?? 'Something wents wrong!');
                    }

                    orderBtn.html(`<span class="fa fa-paper-plane mx-1" style="color: #fff !important;"></span>Place Order`);
                },
                error: function(error) {
                    console.log(error);
                    _toastMsg((error.responseJSON?.msg) ?? 'Something wents wrong!')
                    orderBtn.html(`<span class="fa fa-paper-plane mx-1" style="color: #fff !important;"></span>Place Order`);
                }
            });
        }, 500);

    }
</script>
@endpush