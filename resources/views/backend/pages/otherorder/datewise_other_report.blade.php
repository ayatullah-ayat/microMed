@extends('backend.layouts.master')

@section('title','Other orders Report')

@section('content')
    <div>
        <div>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
    
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><a href="javascript:void(0)" class="text-decoration-none">Date Wise Other orders Report</a></h6>
                    <a class="btn btn-sm btn-danger downloadPDF"><i class="fa fa-file-pdf"></i> PDF</a>
                </div>
    
                <div class="card-body">
    
                    <div class="row align-items-end">
                        <div class="col-md-2" data-col="col">
                            <div class="form-group">
                                <label for="from_date">From Date</label>
                                <input type="text" data-required autocomplete="off" value="{{ date('Y-m-d')}}" class="form-control" id="from_date"
                                    name="from_date">
                            </div>
                            <span class="v-msg"></span>
                        </div>
    
                        <div class="col-md-2" data-col="col">
                            <div class="form-group">
                                <label for="to_date">To Date</label>
                                <input type="text" data-required autocomplete="off" class="form-control" id="to_date"
                                    name="to_date">
                            </div>
                            <span class="v-msg"></span>
                        </div>
    
                        <div class="col-md-2 pb-3">
                            <button class="btn btn-sm btn-success" id="search_result"><i
                                    class="fa fa-search"></i>Search</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="mydataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr style="background-color: #eee !important;">
                                    <th>SL</th>
                                    <th>Order Date</th>
                                    <th>Order NO</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total Price</th>
                                    <th>Discount</th>
                                    <th>Service Charge</th>
                                    <th>Advance Amount</th>
                                    <th>Due Amount</th>
                                    <th>Mobile</th>
                                    <th>Company</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
    
                                <tr class="odd text-center dataTables_empty_row">
                                    <td colspan="15" class="dataTables_empty" valign="top">No data available in table</td>
                                </tr>
    
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #eee !important;">
                                    <th colspan="5"></th>
                                    <th id="totalQty">0</th>
                                    <th id="totalPrice">0</th>
                                    <th id="totalDiscount">0</th>
                                    <th id="totalServiceCharge">0</th>
                                    <th id="totalAdvanced">0</th>
                                    <th id="totalDue">0</th>
                                    <th colspan="4"></th>
                                </tr>
                            </tfoot>
    
                        </table>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
    
@endsection


@push('css')
<!-- Custom styles for this page -->
<link href="{{ asset('assets/backend/css/currency/currency.css')}}" rel="stylesheet">
<style>
    .dataTables_empty {
        font-size: 20px;
        color: #f77c7c;
        font-weight: bold;
    }
</style>
@endpush


@push('js')
<script>
    let timeId = null;
    $(document).ready(function(){
        init();

        $(document).on('click','#search_result', getInvoices)
        $(document).on('click','.downloadPDF', openPDF)
    })



    function getInvoices(){
            let 
            from_date   = $('#from_date').val(),
            to_date     = $('#to_date').val();


            clearTimeout(timeId);

            timeId = setTimeout(() => {
                $.ajax({
                    url     : `{{ route('admin.otherOrder.datewise_report') }}`,
                    method  : 'GET',
                    data    : { from_date, to_date },
                    beforeSend(){
                        $('#mydataTable').find('tbody').html(`
                            <tr class="odd text-center dataTables_empty_row">
                                <td colspan="13" class="h6" valign="top">
                                    <i class="fa fa-spinner fa-spin fa-2x"></i> Loading ...
                                </td>
                            </tr>
                        `);
                    },
                    success(data){
                        loadAjaxData(data);

                        console.log('d====', data);
                    },
                    error(err){
                        console.log(err);
                    },
                })
            }, 500);
    }

    function loadAjaxData(resData){


            let 
            count               = 0,
            totalQty            = 0,
            totalPrice          = 0,
            totalDiscount       = 0,
            totalServiceCharge  = 0,
            totalAdvanced       = 0,
            totalDue            = 0,
            html                = ``;

            if(resData.length){

                resData.forEach(order => {
    
                    let category_names = '',
                        prices = '',
                        order_qty = '',
                        total_order_price = 0,
                        itemLength = order.categories.length,
                        index = 0;



                    let sub_total_order_qty = 0,
                        sub_total_order_price = 0;

                    if(order.categories.length) {

                        order.categories.forEach(item => {
                            total_order_price += item.total_order_price;

                            sub_total_order_price += item.total_order_price;
                            sub_total_order_qty += item.order_qty;
                            if(++index == itemLength){
                                category_names += item.category_name;
                                prices += item.price;
                                order_qty += item.order_qty;
                            }else {
                                category_names += `${item.category_name}, `;
                                prices += `${item.price}, `;
                                order_qty += `${item.order_qty}, `;
                            }
                        })
                    }

                    totalQty            += sub_total_order_qty ?? 0,
                    totalPrice          += sub_total_order_price ?? 0,
                    totalDiscount       += order.order_discount_price ?? 0,
                    totalServiceCharge  += order.service_charge ?? 0,
                    totalAdvanced       += order.advance_balance ?? 0,
                    totalDue            += order.due_price ?? 0,
    
                    html += `<tr>
                        <td>${++count}</td>
                        <td>${order.order_date ?? 'N/A' }</td>
                        <td>${order.order_no }</td>
                        <td>${category_names ?? 'N/A' }</td>
                        <td>${prices ?? '0.0' }</td>
                        <td>${order_qty ?? '0' }</td>
                        <td>${total_order_price ?? '0.0' }</td>
                        <td>${order.order_discount_price ?? '0.0' }</td>
                        <td>${order.service_charge ?? '0.0' }</td>
                        <td>${order.advance_balance ?? '0.0' }</td>
                        <td>${order.due_price ?? '0.0' }</td>
                        <td>${order.moible_no ?? 'N/A' }</td>
                        <td>${order.institute_description ?? 'N/A' }</td>
                        <td>${order.address ?? 'N/A' }</td>
                        <td>${order.status ?? 'N/A' }</td>
                        <td>${order.note ?? 'N/A' }</td>
                    </tr>`;
                })
            }else{
                html += `<tr class="odd text-center dataTables_empty_row">
                    <td colspan="15" class="dataTables_empty" valign="top">No data available in table</td>
                </tr>`;
            }


            setTimeout(function(){
                $('#mydataTable').find('tbody').html(html);
                $('#totalQty').text(totalQty);
                $('#totalPrice').text(totalPrice);
                $('#totalDiscount').text(totalDiscount);
                $('#totalServiceCharge').text(totalServiceCharge);
                $('#totalAdvanced').text(totalAdvanced);
                $('#totalDue').text(totalDue);
            },1000)

    }

    function init(){

        $('#from_date').datepicker({
            autoclose       : true,
            clearBtn        : false,
            todayBtn        : true,
            todayHighlight  : true,
            orientation     : 'bottom',
            format          : 'yyyy-mm-dd',
        })

         $('#to_date').datepicker({
            autoclose       : true,
            clearBtn        : false,
            todayBtn        : true,
            todayHighlight  : true,
            orientation     : 'bottom',
            format          : 'yyyy-mm-dd',
        })
    }

    function openPDF(e){
        // console.log(e);
        e.preventDefault();

        let
        from_date = $('#from_date').val(),
        to_date = $('#to_date').val();

        open(`{{ route('admin.otherOrder.datewise_pdf') }}?from_date=${from_date}&to_date=${to_date}`,'_self')
    }
    
</script>

@endpush

