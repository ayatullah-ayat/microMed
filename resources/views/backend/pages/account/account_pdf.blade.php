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
                <td width="10%">#SL</td>
                <td width="15%" align="left">Account Type</td>
                <td width="10%">Date</td>
                <td width="20%" align="left">Description</td>
                <td width="15%" align="left">Note</td>
                <td width="10%">Cash In</td>
                <td width="10%">Cash Out</td>
                <td width="10%">Current Balance</td>
            </tr>
        </thead>
        <tbody>
            @isset($accounts)
            {{-- @dd($accounts) --}}
            @php
                $total_cash_in          = 0;
                $total_cash_out         = 0;
                $total_current_balance  = 0;
            @endphp

                @foreach ($accounts as $item)
                @php
                    $total_cash_in          += $item->cash_in;
                    $total_cash_out         += $item->cash_out;
                    $total_current_balance  += $item->current_balance;
                @endphp
                    <tr style="border-bottom: 1px solid #000;">
                        <td align="center">{{  $loop->iteration }}</td>
                        <td align="left">{{  $item->account_type ?? 'N/A' }}</td>
                        <td align="center">{{  $item->date ?? 'N/A' }}</td>
                        <td align="left">{{ $item->description ?? 'N/A' }}</td>
                        <td align="left">{{ $item->note ?? 'N/A' }}</td>
                        <td align="right">{{ $item->cash_in ?? '0.0' }}</td>
                        <td align="right">{{ $item->cash_out ?? '0.0' }}</td>
                        <td align="right">{{ $item->current_balance ?? '0.0' }}</td>
                    </tr>
                @endforeach
            @endisset
            <!-- END ITEMS HERE -->
            <tr>
                <td class="totals" colspan="5">Total:</td>
                <td class="totals cost">{{ $total_cash_in ?? '0.0'}}</td>
                <td class="totals cost">{{ $total_cash_out ?? '0.0' }}</td>
                <td class="totals cost">{{ $total_current_balance ?? '0.0' }}</td>
            </tr>
        </tbody>
    </table>
 

</body>

</html>