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

{{-- @dd($company) --}}
        
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
                <td width="10%">Order Date</td>
                <td width="10%">Order NO</td>
                <td width="15%">Category</td>
                <td width="5%">Price</td>
                <td width="5%">Qty</td>
                <td width="10%">Total Price</td>
                <td width="10%">Discount</td>
                <td width="10%">Service Charge</td>
                <td width="10%">Advance Amount</td>
                <td width="10%">Due Amount</td>
                <td width="8%">Mobile NO</td>
                <td width="12%">Company</td>
                <td width="12%">Address</td>
                <td width="5%">Status</td>
                <td width="8%">Note</td>
            </tr>
        </thead>
        <tbody>
            @isset($orders)
            @php
                $all_total_qty          = 0;
                $all_total_price        = 0;
                $total_service_price    = 0;
                $total_discount         = 0;
                $total_payments         = 0;
                $total_due              = 0;
            @endphp

                @foreach ($orders as $item)

                @php
                    $all_total_qty          += $item->order_qty;
                    $all_total_price        += $item->total_order_price;
                    $total_service_price    += $item->service_charge;
                    $total_discount         += $item->order_discount_price;
                    $total_payments         += $item->advance_balance;
                    $total_due              += $item->due_price;







                    $total_order_price = 0;
                    $qty = 0;
                    $total_prices = 0;
                    $prices = null;
                    $category_names = null;

                    $itemLength = count($item->categories);
                    $index = 0;

                    foreach ($item->categories as $it) {
                        $total_order_price += $it->total_order_price;
                        $qty += $it->order_qty;
                        $total_prices += $it->price;
                        if (++$index === $itemLength) {
                            $category_names .= "$it->category_name";
                            $prices .= "$it->price";
                        }else{
                            $category_names .= "$it->category_name, ";
                            $prices .= "$it->price, ";
                        }
                    }  
                @endphp
                    <tr style="border-bottom: 1px solid #000;">
                        <td align="center">{{  $loop->iteration }}</td>
                        <td align="center">{{  $item->order_date ?? 'N/A' }}</td>
                        <td align="center">{{  $item->order_no ?? 'N/A' }}</td>
                        <td>{{ $category_names ?? 'N/A' }}</td>
                        <td align="right">{{ $prices ?? '0.0' }}</td>
                        <td align="right">{{ $qty ?? '0.0' }}</td>
                        <td align="right">{{ $total_prices ?? '0.0' }}</td>
                        <td align="right">{{ $item->order_discount_price ?? '0.0' }}</td>
                        <td align="right">{{ $item->service_charge ?? '0.0' }}</td>
                        <td align="right">{{ $item->advance_balance ?? '0.0' }}</td>
                        <td align="right">{{ $item->due_price ?? '0.0' }}</td>
                        <td>{{ $item->moible_no ?? 'N/A' }}</td>
                        <td>{{ $item->institute_description ?? 'N/A' }}</td>
                        <td>{{ $item->address ?? 'N/A' }}</td>
                        <td>{{ $item->note ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @endisset
            <!-- END ITEMS HERE -->
            <tr>
                <td class="totals" colspan="5">Total:</td>
                <td class="totals cost">{{ $all_total_qty ?? '0.0'}}</td>
                <td class="totals cost">{{ $all_total_price ?? '0.0' }}</td>
                <td class="totals cost">{{ $total_discount ?? '0.0' }}</td>
                <td class="totals cost">{{ $total_service_price ?? '0.0' }}</td>
                <td class="totals cost">{{ $total_payments ?? '0.0' }}</td>
                <td class="totals cost">{{ $total_due ?? '0.0' }}</td>
                <td class="totals" colspan="4"></td>
            </tr>
        </tbody>
    </table>
 

</body>

</html>