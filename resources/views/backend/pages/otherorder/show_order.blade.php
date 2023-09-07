<html>

<head>
    <style>
    
        @page {
          line-height: 2.9;
        }
        
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

    <htmlpageheader style="width: 50%; color: red;" name="myheader">
        <br /><br /><br /><br /><br />
            <table width="100%" style="font-family: serif;" cellpadding="10">
        <tr>
            <td width="70%" style="padding-left: 0px !important;">
                <div style="font-size: 7pt; color: #555555; font-family: sans;font-weight:bold;">ORDER
                    TO:</div><br />
                <div>Order No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $otherOrder->order_no ?? '' }}</div>
                <p style="font-size: 5pt;">&nbsp;&nbsp;&nbsp; </p>
                @if($otherOrder->customer_name)
                <p>Customer Name&nbsp;&nbsp;&nbsp;: {{ $otherOrder->customer_name ?? '' }}</p>
                <p style="font-size: 5pt;">&nbsp;&nbsp;&nbsp; </p>
                @endif
                <div style="margin-top: 15px !important;">Company Name&nbsp;&nbsp;&nbsp;&nbsp;: {{ $otherOrder->institute_description ?? 'N/A' }}</div>
                <p style="font-size: 5pt;">&nbsp;&nbsp;&nbsp; </p>
                @if($otherOrder->address)
                <div style="padding-bottom: 15px !important;">Company Address&nbsp;: {{ $otherOrder->address ?? '' }}</div>
                <p style="font-size: 5pt;">&nbsp;&nbsp;&nbsp; </p>
                @endif 
                <div style="line-height: 1.6 !important">Phone &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $otherOrder->moible_no ?? 'N/A' }} </div><br />
            </td>
            <td width="10%">&nbsp;</td>
            <td width="20%" style="text-align: right; padding-right: 0px !important;">
                <div style="text-align: right;visibility:hidden;">x</div>
                <div style="text-align: right;visibility:hidden;">y</div>
                <div style="text-align: right">Date: {{ date('Y-m-d', strtotime($otherOrder->order_date)) }}</div>
            </td>
        </tr>
    </table>
    </htmlpageheader>


    <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
    
    <!--<sethtmlpagefooter name="myfooter" value="on" />-->
    
    <!--<htmlpagefooter name="myfooter">-->
    <!--    <table width="100%" style="margin-bottom: 65px;">-->
    <!--        <tr style="padding-bottom: 15px !important;">-->
    <!--            <td width="20%" style="padding-left: 0px !important;">-->
    <!--                <div style="border-top: 1.5px solid black;">Authorized Signature</div>-->
    <!--            </td>-->
    <!--            <td width="55%">&nbsp;</td>-->
    <!--            <td width="18%" style="text-align: right; padding-right: 0px !important;">-->
    <!--                <div style="border-top: 1.5px solid black;">Receiver Signature</div>-->
    <!--            </td>-->
    <!--        </tr>-->
    <!--    </table>-->
    <!--</htmlpagefooter>-->
    

<br /><br /><br /><br /><br /><br /><br /><br />
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
                $notes          = [];
            @endphp
            @if($otherOrders && count($otherOrders))

                @php
                $subtotal               =  0;
                $serviceCharge          = 0;
                $discountTotal          = 0;
                $advanceBalanceTotal    = 0;
                @endphp

            @foreach($otherOrders as $other)
                @php
                $subtotal           += $other->total_order_price;
                $discountTotal      = $other->order_discount_price;
                $serviceCharge      = $other->service_charge;
                $advanceBalanceTotal= $other->advance_balance;
                $notes[]            = $other->note;
                @endphp

            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td align="left">
                    {{ $other->category_name ?? '' }}
                </td>
                <td align="center">{{ $other->price ?? '0.0' }}</td>
                <td align="center">{{ $other->order_qty ?? '0' }}</td>
                <td align="right">{{ $other->total_order_price }}</td>
            </tr>
            @endforeach 
            
            <!-- END ITEMS HERE -->
            <tr>
                <td class="blanktotal" colspan="2" rowspan="6"></td>
                <td class="totals" colspan="2">Subtotal:</td>
                <td class="totals cost">{{ $subtotal ?? 0 }}</td>
            </tr>
            <tr>
                <td class="totals" colspan="2">Discount:</td>
                <td class="totals cost">{{ $discountTotal ?? 0 }}</td>
            </tr>
            <tr>
                <td class="totals" colspan="2">Service Charge:</td>
                <td class="totals cost">{{ $serviceCharge ?? 0 }}</td>
            </tr>
            <tr>
                <td class="totals" colspan="2"><b>Total:</b></td>
                <td class="totals cost"><b>{{ $gTotal = ($subtotal + $serviceCharge) - $discountTotal  }}</b></td>
            </tr>
            <tr>
                <td class="totals" colspan="2">Paid:</td>
                <td class="totals cost">{{ $advanceBalanceTotal }}</td>
            </tr>
            <tr>
                <td class="totals" colspan="2"><b>Due:</b></td>
                <td class="totals cost"><b>{{ $gTotal - $advanceBalanceTotal }}</b>
                </td>
            </tr>
            @else 

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
                <td class="totals cost"><b>{{ ($subSubtotal + $otherOrder->service_charge) - $otherOrder->order_discount_price }}</b></td>
            </tr>
            <tr>
                <td class="totals" colspan="2">Paid:</td>
                <td class="totals cost">{{ $otherOrder->advance_balance }}</td>
            </tr>
            <tr>
                <td class="totals" colspan="2"><b>Due:</b></td>
                <td class="totals cost"><b>{{ (($subSubtotal + $otherOrder->service_charge) - $otherOrder->order_discount_price ?? 0 ) - $otherOrder->advance_balance }}</b></td>
            </tr>

            @endif 
        </tbody>
    </table>
    @if(isset($otherOrder->note))
    <p style="margin-top: 15px;"><b>Note:</b> {{ $otherOrder->note ?? '' }}</p>
    @endif
    

</body>

</html>