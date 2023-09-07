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
                <td width="20%">Product Name</td>
                <td width="5%">Category</td>
                <td width="10%">Unit</td>
                <td width="15%">Sale Price</td>
                <td width="5%">Supplier Price</td>
                <td width="10%">In Qty</td>
                <td width="10%">Stock Qty</td>
                <td width="10%">Returned Qty</td>
            </tr>
        </thead>
        <tbody>
            @isset($stocks)
            @php
                $total_sale_price           = 0;
                $total_supplier_price       = 0;
                $total_in_qty               = 0;
                $total_stock_qty            = 0;
                $total_return_qty           = 0;
            @endphp

                @foreach ($stocks as $item)
                @php
                    $total_sale_price           += $item->sales_price;
                    $total_supplier_price       += $item->product_price;
                    $total_in_qty               += $item->product_qty;
                    $total_stock_qty            += $item->stocked_qty;
                    $total_return_qty           += $item->returned_qty;
                @endphp
                    <tr style="border-bottom: 1px solid #000;">
                        <td align="left">{{  $loop->iteration }}</td>
                        <td align="left">{{  $item->product_name ?? 'N/A' }}</td>
                        <td align="left">{{  $item->category_name ?? 'N/A' }}</td>
                        <td align="left">{{  $item->product_unit ?? 'N/A' }}</td>
                        <td align="right">{{ $item->sales_price ?? '0.0' }}</td>
                        <td align="right">{{ $item->product_price ?? '0.0' }}</td>
                        <td align="right">{{ $item->product_qty ?? '0.0' }}</td>
                        <td align="right">{{ $item->stocked_qty ?? '0.0' }}</td>
                        <td align="right">{{ $item->returned_qty ?? '0.0' }}</td>
                       
                    </tr>
                @endforeach
            @endisset
            <!-- END ITEMS HERE -->
            <tr>
                <td class="totals" colspan="4">Total:</td>
                <td class="totals cost">{{ $total_sale_price ?? '0.0'}}</td>
                <td class="totals cost">{{ $total_supplier_price ?? '0.0' }}</td>
                <td class="totals cost">{{ $total_in_qty ?? '0.0' }}</td>
                <td class="totals cost">{{ $total_stock_qty ?? '0.0' }}</td>
                <td class="totals cost">{{ $total_return_qty ?? '0.0' }}</td>
            </tr>
        </tbody>
    </table>
 

</body>

</html>