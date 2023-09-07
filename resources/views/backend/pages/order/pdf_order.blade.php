<html>

<head>
    <style>
        body {
            font-family: nikosh, sans-serif;
            font-size: 10pt;
        }
        p {
            margin: 0pt;
        }
        table.items {
            border: 0.1mm solid #000000;
        }
        td {
            vertical-align: top;
        }
        .items td {
            border-left: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        table thead td {
            background-color: #EEEEEE;
            text-align: center;
            border: 0.1mm solid #000000;
            font-variant: small-caps;
        }
        .items td.blanktotal {
            background-color: #EEEEEE !important;
            border: 0.1mm solid #000000;
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        .items td.totals {
            text-align: right;
            border: 0.1mm solid #000000;
        }
        .items td.cost {
            text-align: "."center;
        }
        .spacer{
            height: 110px !important;
        }
    </style>
</head>

<body>

    @php
        $company = getCompanyProfile();
    @endphp

        
<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
    <htmlpageheader name="myheader">
        <table width="100%">
            <tr>
                <td width="100%" style="color:#0000BB; text-align:center;">
                    <span style="font-weight: bold; font-size: 14pt;">
                        @if(isset($company->dark_logo))
                            <img width="180" height="auto" style="padding:0px !important; margin-bottom: 5px;" src="{{ asset($company->dark_logo) }}" alt="">
                        @else 
                            <img width="180" height="80" style="padding:0px !important;" src="https://themeshaper.net/img/logo.png" alt="">
                        @endif 
                    </span>
                    <br />
                        {!! $company->company_address ?? 'House-07,Kobi faruk soroni<br />Nikunja 2 ,Dhaka 1229' !!}
                    <br />
                    <span style="font-family:dejavusanscondensed;">&#9742;</span>{{ $company->company_phone ?? '০১৯৭-১৮১৯-৮১৩' }}
                </td>
            </tr>
        </table>
        </htmlpageheader>
 
    <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
        <thead>
            <tr>
                <td width="5%">#SL</td>
                <td width="10%">Order NO</td>
                <td width="8%">Order Date</td>
                <td width="8%">Sizes</td>
                <td width="8%">Colors</td>
                <td width="6%">Total Qty</td>
                <td width="6%">Total Price</td>
                <td width="6%">Discount Price</td>
                <td width="6%">Total Payment</td>
                <td width="8%">Payment Type</td>
                <td width="8%">Customer Name</td>
                <td width="8%">Mobile NO</td>
                <td width="12%">Shipping Address</td>
                <td width="8%">Order Note</td>
                <td width="6%">Status</td>
            </tr>
        </thead>
        <tbody>
            @isset($ordersdata)
            {{-- @dd($ordersdata) --}}
            @php
                $all_total_qty    = 0;
                $all_total_price  = 0;
                $total_discount_price  = 0;
                $total_payments  = 0;
            @endphp

                @foreach ($ordersdata as $item)
                @php
                    $all_total_qty    += $item->order_total_qty;
                    $all_total_price  += $item->order_total_price;
                    $total_discount_price  += $item->discount_price;
                    $total_payments  += $item->payment_total_price;
                @endphp
                    <tr style="border-bottom: 1px solid #000;">
                        <td align="center">{{  $loop->iteration }}</td>
                        <td align="center">{{  $item->order_no ?? 'N/A' }}</td>
                        <td align="center">{{  $item->order_date ?? 'N/A' }}</td>
                        <td align="center">{{  $item->order_sizes ?? 'N/A' }}</td>
                        <td align="center">{{  $item->order_colors ?? 'N/A' }}</td>
                        <td align="right ">{{  $item->order_total_qty ?? 'N/A' }}</td>
                        <td align="right ">{{  $item->order_total_price ?? 'N/A' }}</td>
                        <td align="right ">{{  $item->discount_price ?? 'N/A' }}</td>
                        <td align="right ">{{  $item->payment_total_price ?? 'N/A' }}</td>
                        <td align="center">{{  $item->payment_type ?? 'N/A' }}</td>
                        <td align="center">{{  $item->customer_name ?? 'N/A' }}</td>
                        <td align="center">{{  $item->customer_phone ?? 'N/A' }}</td>
                        <td align="center">{{  $item->shipping_address ?? 'N/A' }}</td>
                        <td align="center">{{  $item->order_note ?? 'N/A' }}</td>
                        <td align="center">{{  $item->status ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @endisset
            <!-- END ITEMS HERE -->
            <tr>
                <td class="totals" colspan="5">Total:</td>
                <td class="totals cost">{{ $all_total_qty ?? '0.0'}}</td>
                <td class="totals cost">{{ $all_total_price ?? '0.0' }}</td>
                <td class="totals cost">{{ $total_discount_price ?? '0.0' }}</td>
                <td class="totals cost">{{ $total_payments ?? '0.0' }}</td>
                <td class="totals" colspan="6"></td>
            </tr>
        </tbody>
    </table>
 

</body>

</html>