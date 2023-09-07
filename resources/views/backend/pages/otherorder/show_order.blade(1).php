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

    @php
        $company = getCompanyProfile();
    @endphp

    <htmlpageheader name="myheader">
        <table width="100%">
            <tr>
                {{-- <td width="50%" style="color:#0000BB; "><span style="font-weight: bold; font-size: 14pt;"> --}}
                    {{-- <img width="180" height="80" style="padding:0px !important;" src="{{ asset('assets/img/nadeem-hair01.png') }}" alt=""> --}}
                {{-- </span><br /><br /><span style="font-family:dejavusanscondensed;">&#9742;</span> 07595 183489</td> --}}
                <td width="50%" style="color:#0000BB; "><span style="font-weight: bold; font-size: 14pt;">
                    @if(isset($company->dark_logo))
                    <img width="180" height="auto" style="padding:0px !important; margin-bottom: 5px;" src="{{ asset($company->dark_logo) }}" alt="">
                    @else 
                    <img width="180" height="80" style="padding:0px !important;" src="https://themeshaper.net/img/logo.png" alt="">
                    @endif 
                </span><br />
                {{ $company->company_address ?? 'House-07,Kobi faruk soroni<br />Nikunja 2 ,Dhaka 1229' }}
                <br /><span style="font-family:dejavusanscondensed;">&#9742;</span>
                    {{ $company->company_phone ?? '' }}</td>
                <td style="text-align: right; width: 25%; min-width: 100px;vertical-align: bottom">
                    <p>Order No :</p>
                    <p>Order Date :</p>
                </td>
                <td style="width: calc(50%% - 25%);text-align: right;vertical-align: bottom">
                    <p>{{ $otherOrder->order_no ?? '' }}</p>
                    <p>{{ $otherOrder->order_date ?? '' }}</p>
                </td>
            </tr>
        </table>
    </htmlpageheader>

    {{-- @dd($order->order_no) --}}

    {{-- <htmlpagefooter name="myfooter">
        <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
            Page {PAGENO} of {nb}
        </div>
    </htmlpagefooter> --}}

    <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
    {{--
    <sethtmlpagefooter name="myfooter" value="on" /> --}}

    <table width="100%" style="font-family: serif;" cellpadding="10">
        <tr>
            <td width="45%" style="padding-left: 0px !important;"><span
                    style="font-size: 7pt; color: #555555; font-family: sans;font-weight:bold;">ORDER
                    TO:</span><br /><br />Company Name&nbsp;&nbsp;&nbsp;&nbsp;: {{ $otherOrder->institute_description ?? 'N/A' }}<br />Company Address: {{ $otherOrder->address ?? 'N/A' }}<br />
                    Phone &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $otherOrder->moible_no ?? 'N/A' }}</td>
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
                <td width="50%" align="left">Category Name</td>
                <td width="15%">Price</td>
                <td width="10%">Qty</td>
                <td width="20%" align="right">Amount</td>
            </tr>
        </thead>
        <tbody> 

            @php
                $subSubtotal    = $otherOrder->total_order_price ?? 0;
                $totalDiscount  = 0;
            @endphp
            <tr>
                <td align="center">1</td>
                <td align="left">
                    {{ $otherOrder->category_name ?? '' }}
                </td>
                <td align="center">{{ $otherOrder->price ?? '0.0' }}</td>
                <td align="center">{{ $otherOrder->order_qty ?? '0' }}</td>
                <td align="right">{{ $otherOrder->total_order_price }}</td>
            </tr>

            <!-- END ITEMS HERE -->
            <tr>
                <td class="blanktotal" colspan="2" rowspan="6"></td>
                <td class="totals" colspan="2">Subtotal:</td>
                <td class="totals cost">{{ $subSubtotal ?? 0 }}</td>
            </tr>
            <tr>
                <td class="totals" colspan="2">Discount:</td>
                <td class="totals cost">{{ $otherOrder->order_discount_price ?? 0 }}</td>
            </tr>         
            <tr>
                <td class="totals" colspan="2">Service Charge:</td>
                <td class="totals cost">{{ $otherOrder->service_charge ?? 0 }}</td>
            </tr>
            <tr>
                <td class="totals" colspan="2"><b>Total:</b></td>
                <td class="totals cost"><b>{{ ($subSubtotal + $otherOrder->service_charge) - $otherOrder->order_discount_price  }}</b></td>
            </tr>
            <tr>
                <td class="totals" colspan="2">Paid:</td>
                <td class="totals cost">{{ $otherOrder->advance_balance }}</td>
            </tr>
            <tr>
                <td class="totals" colspan="2"><b>Due:</b></td>
                <td class="totals cost"><b>{{ (($subSubtotal + $otherOrder->service_charge) - $otherOrder->order_discount_price ?? 0 ) - $otherOrder->advance_balance }}</b></td>
            </tr>
        </tbody>
    </table>
    @if(isset($otherOrder->note))
    <p style="margin-top: 10px"><b>Note:</b> {{ $otherOrder->note ?? '' }}</p>
    @endif 
    <br />

    {!! pdfFooter($organizationlogo) !!}

</body>

</html>