<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Place </title>
    <style>

        body,
        body *:not(html):not(style):not(br):not(tr):not(code) {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif,
                'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            position: relative;
        }

        body {
            -webkit-text-size-adjust: none;
            background-color: #ffffff;
            color: #718096;
            height: 100%;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            width: 100% !important;
        }

        p,
        ul,
        ol,
        blockquote {
            line-height: 1.4;
            text-align: left;
        }
        .order-header .logo img {
            margin: 0 auto;
            display: block;
        }

        .order-body {
            width: 50%;
            margin: 0 auto;
        }


        @media screen and (max-width: 768px){
            .order-body{
                width: 100% !important;
            }
        }

        .header-content {
            text-align: center;
        }

        .order-no {
            color: #000 !important;
            font-weight: bold;
        }

        .btn {
            border: none;
            outline: none;
            padding: 5px 10px;
            font-weight: 600;
        }

        .btn-danger {
            color: #fff;
            background-color: red;
        }

        .table {
            width: 100%;
            position: relative;
            text-align: left;
            border-collapse: collapse;
        }

        .table.fixed {

            table-layout: fixed;
        }

        .table thead tr {
            background-color: #fdfd;
            color: #000;
        }

        .table-borderless,
        .table-borderless tr , 
        .table-borderless tr th, 
        .table-borderless tr td {
            border: none;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .mb-30 {
            margin-bottom: 30px;
        }

        .mb-40 {
            margin-bottom: 40px;
        }

        .table tbody th,
        .table tbody td {
            padding: 0px 10px;
        }

        .inner-product img {
            float: left;
            max-width: 100px;
            margin-right: 10px;
        }

        .call-us {
            color: #fff !important;
            background-color: red !important;
        }

        .table a {
            text-decoration: none !important;
            color: #fff;
        }

        .header {
        padding: 25px 0;
        text-align: center;
        }
        
        .header a {
        color: #3d4852;
        font-size: 19px;
        font-weight: bold;
        text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="order-body">
        <table class="table order-header mb-30">
            <tbody>
                <tr>
                    <th class="header">
                        <div class="logo header" style="text-align: center !important;">
                            <a href="{{ '' }}" style="display: block; text-align:center;">
                            <img src="{{ asset('assets/frontend/img/logogroup.jpg') }}" alt="Logo" style="text-align: center !important;margin: 0 auto;display: block;">
                            </a>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th>
                        <div class="header-content" style="text-align: center !important;">
                            @if(preg_match("/pending/im",$data->status))
                            <h2 style="text-align: center !important;">Thanks For Your Order!</h2>
                            <p style="padding: 0; line-height: 1em; font-weight: normal;text-align: center !important;">Hi {{ ($data->customer_name ?? $data->customer->customer_name)?? '' }}, We have received
                                order #<a href="{{ url('/') }}" class="order-no">{{ $data->order_no ?? '' }}</a> and We're working on it now.</p>
                            <p style="padding: 0; line-height: 1em; font-weight: normal;text-align: center !important;">We'll Email you an update when we have confirmed/shipped it.</p>
                            @elseif(preg_match("/confirm/im",$data->status))
                            <h2 style="text-align: center !important;">Hi {{ ($data->customer_name ?? $data->customer->customer_name)?? ''  }}!</h2>
                            <p style="padding: 0; line-height: 1em; font-weight: normal;text-align: center !important;">Your Order is Confirmed. Let us Know if have any issues.</p>
                            @elseif(preg_match("/processing/im",$data->status))
                            <h2 style="text-align: center !important;">Hi {{ ($data->customer_name ?? $data->customer->customer_name)?? ''  }}!</h2>
                            <p style="padding: 0; line-height: 1em; font-weight: normal;text-align: center !important;">Your Order is Confirmed and Now We are working on it.</p>
                            @elseif(preg_match("/cancelled/im",$data->status))
                            <h2 style="text-align: center !important;">Hi {{ ($data->customer_name ?? $data->customer->customer_name)?? ''  }}!</h2>
                            <p style="padding: 0; line-height: 1em; font-weight: normal;text-align: center !important;">Your Order has been <span style="color: red">Cancelled</span>. Let us Know if have any issues.</p>
                            @endif
                            @if(!preg_match("/cancelled/im",$data->status)) 
                            <button class="btn btn-danger"><a href="{{ url('/') }}">Visit Our site & Track Your order</a></button>
                            @else 
                            <button class="btn btn-danger"><a href="{{ url('/') }}">Visit Our site</a></button>
                            @endif 
                        </div>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="order-body">
        <table class="table fixed table-borderless mb-20">
            <thead>
                <tr>
                    <th style="padding: 5px 10px;">ORDER & SHIPPING INFO</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <td rowspan="4" style="vertical-align:top">
                    <h4 style="margin-bottom: 0px !important">Order Details</h4><hr>
                    Order No: #{{ $data->order_no ?? '' }}<br />
                    Order Date: {{ $data->order_date ?? '' }}<br />
                    Order Total: {{ $data->order_total_price ?? '0' }}
                </td>
                <td rowspan="4" style="vertical-align:top">                
                    <h4 style="margin-bottom: 0px !important">Shipping Address: </h4><hr>
                    {{ $data->shipping_address ?? '' }}
                </td>

            </tbody>
        </table>

        <table class="table table-borderless">
            <thead>
                <tr>
                    <th style="padding: 5px 10px;">ITEMS</th>
                    <th style="width: 100px;text-align: center;">Qty</th>
                    <th style="width: 130px;text-align: right;">Price</th>
                </tr>
            </thead>
            <tbody>

                @php
                    $totalItems = count($data->orderDetails) ?? 0;
                @endphp
                @foreach ($data->orderDetails as $item)

                    @php
                        $image = $item->product->product_thumbnail_image ?? 'assets/frontend/img/logogroup.jpg';
                    @endphp
                    <tr>
                        <td style="vertical-align:top">
                            <div class="inner-product">
                                <img src="{{ asset($image) }}" alt="product image">
                                <p>{{ $item->product_name ?? '' }}</p>
                            </div>
                        </td>
                        <td style="text-align: center;vertical-align:top">{{ $item->product_qty ?? '0' }}</td>
                        <td style="text-align: right; padding-right: 5px;vertical-align:top">{{ $item->subtotal ?? '0' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" style="text-align: right;">Subtotal ({{ $totalItems }}{{ $totalItems  <= 1 ? 'Item' : "Items" }}):</th>
                    <td style="text-align: right;padding-right: 5px;">{{ $data->order_total_price ?? '0' }}</td>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right;">Shipping:</th>
                    <td style="text-align: right;padding-right: 5px;">{{ '0' }}</td>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right;">Tax:</th>
                    <td style="text-align: right;padding-right: 5px;">{{ '0' }}</td>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right;">Total:</th>
                    <td style="text-align: right;padding-right: 5px;">{{ $data->order_total_price ?? '0' }}</td>
                </tr>
            </tfoot>
        </table>
        <table class="table table-borderless fixed" cellpadding="0">
            <thead>
                <tr class="call-us">
                    <th style="vertical-align: middle;padding-top: 10px; padding-bottom: 0px !important;">
                        <h3 style="text-align: center !important;">Call us At <span><a href="tel:01971819813">০১৯৭-১৮১৯-৮১৩</a></span> or Reply to this Email.</h3>
                    </th>
                </tr>
            </thead>
        </table>

        Best Regards,<br>
        {{ config('app.name') }}.
    </div>


</body>

</html>