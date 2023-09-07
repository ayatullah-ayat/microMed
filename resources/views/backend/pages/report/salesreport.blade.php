@extends('backend.layouts.master')

@section('title', 'Date Wise Sales Report')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="javascript:void(0)" class="text-decoration-none">Date Wise Sales Report</a></h6>
                <div class="inner">
                    <a class="btn btn-sm btn-danger downloadPDF"><i class="fa fa-file-pdf"></i> Export PDF</a>
                    <button class="btn btn-sm btn-success"><a id="excelExport" class="text-white" href="javascript:void(0) {{ route('date_sales_report_export')}}?from_date=&to_date="><i class="fa fa-download"> Export excel</i></a></button>
                </div>
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
                        <button class="btn btn-sm btn-success" id="search_result"><i class="fa fa-search"></i>Search</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="mydataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr style="background-color: #eee !important;">
                                <th>SL</th>
                                <th>Invoice NO</th>
                                <th>Customer Name</th>
                                <th>Date</th>
                                <th>Total Qty</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr class="odd text-center dataTables_empty_row">
                                <td colspan="6" class="dataTables_empty" valign="top">No data available in table</td>
                            </tr>

                        </tbody>
                        <tfoot>
                            <tr style="background-color: #eee !important;">
                                <th colspan="4"></th>
                                <th id="totalSoldQty">0</th>
                                <th id="totalSoldAmount">0</th>
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
                    url     : `{{ route('admin.sales_report') }}`,
                    method  : 'GET',
                    data    : { from_date, to_date },
                    beforeSend(){
                        $('#mydataTable').find('tbody').html(`
                            <tr class="odd text-center dataTables_empty_row">
                                <td colspan="6" class="h6" valign="top">
                                    <i class="fa fa-spinner fa-spin fa-2x"></i> Loading ...
                                </td>
                            </tr>
                        `);
                    },
                    success(data){
                        loadAjaxData(data);
                        $('#excelExport').attr('href',`{{ route('date_sales_report_export')}}?from_date=${from_date}&to_date=${to_date}`)
                    },
                    error(err){
                        console.log(err);
                    },
                })
            }, 500);
        }




        function loadAjaxData(resData){

            let 
            count           = 0,
            totalSoldQty    = 0,
            totalSoldAmount = 0,
            html            = ``;

            if(resData.length){

                resData.forEach(sale => {
    
                    totalSoldQty += sale.sold_total_qty ?? 0;
                    totalSoldAmount += sale.order_grand_total ?? 0;
    
                    html += `<tr>
                        <td>${++count}</td>
                        <td><a target="_blank" href="{{ route('admin.ecom_sales.showInvoice', '') }}/${sale.invoice_no}"> ${sale.invoice_no ?? 'N/A' }</a></td>
                        <td>${sale.customer_name ?? 'N/A' }</td>
                        <td>${sale.sales_date ?? 'N/A' }</td>
                        <td>${sale.sold_total_qty ?? 0 }</td>
                        <td>${sale.order_grand_total ?? 0 }</td>
                    </tr>`;
                })
            }else{
                html += `<tr class="odd text-center dataTables_empty_row">
                    <td colspan="6" class="dataTables_empty" valign="top">No data available in table</td>
                </tr>`;
            }

            setTimeout(function(){
                $('#mydataTable').find('tbody').html(html);
                $('#totalSoldQty').text(totalSoldQty);
                $('#totalSoldAmount').text(totalSoldAmount);
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
        e.preventDefault();

        let
        from_date = $('#from_date').val(),
        to_date = $('#to_date').val();

        open(`{{ route('admin.sales_report_pdf') }}?from_date=${from_date}&to_date=${to_date}`,'_self')
    }
</script>
    
@endpush