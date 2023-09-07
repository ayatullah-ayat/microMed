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
    </style>
</head>

<body>

    <htmlpageheader name="myheader">
        <table width="100%">
            <tr>
                @php
                $company = getCompanyProfile();
                @endphp

                <td width="50%" style="color:#0000BB; "><span style="font-weight: bold; font-size: 14pt;">
                        @if($company->dark_logo)
                        <img width="180" height="auto" style="padding:0px !important; margin-bottom: 5px;"
                            src="{{ asset($company->dark_logo) }}" alt="">
                        @else
                        <img width="180" height="80" style="padding:0px !important;" src="https://themeshaper.net/img/logo.png" alt="">
                        @endif
                    </span><br />
                    {{ $company->company_address ?? 'House-07,Kobi faruk soroni<br />Nikunja 2 ,Dhaka 1229' }}
                    <br /><span style="font-family:dejavusanscondensed;">&#9742;</span>
                    {{ $company->company_phone ?? '' }}
                </td>
                <td style="text-align: right; width: 35%; min-width: 100px;vertical-align: bottom">
                    <p>Invocie No :</p>
                    <p>Sale Date :</p>
                </td>

                <td style="width: calc(50% - 35%);text-align: right;vertical-align: bottom">
                    <p>{{ $sale->invoice_no ?? '' }}</p>
                    <p>{{ $sale->sales_date ?? '' }}</p>
                </td>
            </tr>
        </table>
    </htmlpageheader>

    <sethtmlpageheader name="myheader" value="on" show-this-page="1" />

    {{-- @dd($sale) --}}

    <table width="100%" style="font-family: serif;" cellpadding="10">
        <tr>
            <td width="45%" style="padding-left: 0px !important;">
            <span style="font-size: 9pt; color: #555555; font-family: sans;font-weight:bold;">Sale To:</span><br /><br />
            Customer Name: {{ $sale->customer_name ?? 'N/A' }}<br />Customer Phone: {{
                $sale->customer->customer_phone ?? 'N/A' }}<br />Customer Email: {{ $sale->customer->customer_email ?? 'N/A' }}</td>
            <td width="10%">&nbsp;</td>
            <td width="45%" style="text-align: right; padding-right: 0px !important;">
                <div style="text-align: right;visibility:hidden;">x</div>
                <div style="text-align: right;visibility:hidden;">y</div>
                <div style="text-align: right;visibility:hidden;">z</div>
                <div style="text-align: right;visibility:hidden;">z</div>
                <div style="text-align: right">Date: {{ date('Y-m-d') }}</div>
            </td>
        </tr>
    </table>
    <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
        <thead>
            <tr>
                <td width="5%">#SL</td>
                <td width="20%" align="left">Item Name</td>
                <td width="25%" align="left">Variant</td>
                <td width="15%">Unit</td>
                <td width="15%">Price</td>
                <td width="10%">Qty</td>
                <td width="15%" align="right">Amount</td>
            </tr>
        </thead>
        <tbody>

            @php
            $subSubtotal = 0;
            $totalDiscount = $sale->total_discount_price ?? 0;
            @endphp

            @if(isset($sale->saleProducts))
                @foreach ($sale->saleProducts as $item)
                @php
                $subSubtotal   += $item->subtotal;
                @endphp
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td align="left">
                        {{ $item->product_name ?? '' }}
                    </td>
                    <td align="left">
                        @if($item->product_color)
                        Color: {{ $item->product_color ?? '' }} <br>
                        @endif
                        @if($item->product_size)
                        Size: {{ $item->product_size ?? '' }}
                        @endif
                    </td>
                    <td align="center">{{ $item->product->product_unit ?? 'N/A' }}</td>
                    <td align="center">{{ $item->sales_price ?? '0.0' }}</td>
                    <td align="center">{{ $item->product_qty ?? '0' }}</td>
                    <td align="right">{{ $item->subtotal }}</td>
                </tr>
                @endforeach
            @endif 

            <!-- END ITEMS HERE -->
            <tr>
                <td class="blanktotal" colspan="5" rowspan="5"></td>
                <td class="totals" colspan="1"><b>Subtotal:</b></td>
                <td class="totals cost">{{ $subSubtotal ?? 0 }}</td>
            </tr>
            <tr>
                <td class="totals" colspan="1"><b>Discount:</b></td>
                <td class="totals cost"><b>{{ $totalDiscount }}</b></td>
            </tr>
            <tr>
                <td class="totals" colspan="1"><b>Total:</b></td>
                <td class="totals cost"><b>{{ $grandTotal = $subSubtotal - $totalDiscount }}</b></td>
            </tr>
            <tr>
                <td class="totals" colspan="1"><b>Paid:</b></td>
                <td class="totals cost">{{ $sale->total_payment ?? 0 }}</td>
            </tr>
            <tr>
                <td class="totals" colspan="1"><b>Due:</b></td>
                <td class="totals cost"><b>{{ $grandTotal - $sale->total_payment ?? 0 }}</b></td>
            </tr>
        </tbody>
    </table>
    <br />

    {!! pdfFooter($organizationlogo) !!}

</body>

</html>