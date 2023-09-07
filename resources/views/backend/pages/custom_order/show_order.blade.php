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
    {{-- @dd($booking->invoice_no) --}}

    <htmlpageheader name="myheader">
        <table width="100%">
            <tr>
                {{-- <td width="50%" style="color:#0000BB; "><span style="font-weight: bold; font-size: 14pt;"> --}}
                    {{-- <img width="180" height="80" style="padding:0px !important;" src="{{ asset('assets/img/nadeem-hair01.png') }}" alt=""> --}}
                {{-- </span><br /><br /><span style="font-family:dejavusanscondensed;">&#9742;</span> 07595 183489</td> --}}
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
                <td style="text-align: right; width: 25%; min-width: 100px;vertical-align: bottom">
                    <p>Order No :</p>
                    <p>Order Date :</p>
                </td>
                <td style="width: calc(50%% - 25%);text-align: right;vertical-align: bottom">
                    <p>{{ $customServiceOrder->order_no ?? '' }}</p>
                    <p>{{ $customServiceOrder->created_at ? date('Y-m-d', strtotime($customServiceOrder->created_at)) : '' }}</p>
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
                    TO:</span><br /><br />Name: {{ ($customServiceOrder->customer_name ?? $customServiceOrder->customer->customer_name ) ?? 'N/A' }}<br />Phone: {{
                ($customServiceOrder->customer_phone ?? $customServiceOrder->customer->customer_phone) ?? 'N/A' }}<br />
                @if($customServiceOrder->customer_email)
                Email: {{ ($customServiceOrder->customer_email ?? $customServiceOrder->customer->customer_email) ?? 'N/A' }}
                @endif 
                @if($customServiceOrder->customer_address)
                Address: {{ ($customServiceOrder->customer_address ?? $customServiceOrder->customer->customer_address) ?? 'N/A' }}
                @endif 
            </td>
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
                <td width="60%" align="left">Item</td>
                <td width="40%" align="center">Attachment</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="left">
                    <img src="{{ asset($customServiceOrder->product->product_thumbnail) }}" style="width: 100px" alt="N/A">
                    <span>{{ $customServiceOrder->custom_service_product_name ?? '' }}</span>
                </td>
                <td align="center">
                    <img src="{{ asset($customServiceOrder->order_attachment) }}" alt="N/A" style="width: 100px">
                </td>
            </tr>
        </tbody>
    </table>
    <br />

    {!! pdfFooter($organizationlogo) !!}

</body>

</html>