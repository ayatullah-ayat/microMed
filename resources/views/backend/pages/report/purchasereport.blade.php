@extends('backend.layouts.master')

@section('title', 'Date Wise Purchase Report')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="javascript:void(0)" class="text-decoration-none">Date Wise Purchase Report</a></h6>
                <div class="inner">
                    <a class="btn btn-sm btn-danger downloadPDF"><i class="fa fa-file-pdf"></i> Export PDF</a>
                    <button class="btn btn-sm btn-success"><a id="excelExport" class="text-white" href="javascript:void(0){{ route('date_purchase_report_export')}}?from_date=&to_date="><i class="fa fa-download"> Export excel</i></a></button>
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
                        <button class="btn btn-sm btn-success" id="search_result"><i
                                class="fa fa-search"></i>Search</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="mydataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr style="background-color: #eee !important;">
                                <th>SL</th>
                                <th>Invoice NO</th>
                                <th>Supplier Name</th>
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
                                <th id="totalPurchaseQty">0</th>
                                <th id="totalPurchaseAmount">0</th>
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
<link href="{{ asset('assets/backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{ asset('assets/backend/css/currency/currency.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/backend/css/product/product.css') }}">
<style>
    .dataTables_empty {
        font-size: 20px;
        color: #f77c7c;
        font-weight: bold;
    }
</style>
@endpush

@push('js')
<!-- Page level plugins -->
<script src="{{ asset('assets/backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Page level custom scripts -->
<script src="{{ asset('assets/backend/libs/demo/datatables-demo.js') }}"></script>
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
                    url     : `{{ route('admin.purchase_report') }}`,
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
                        $('#excelExport').attr('href',`{{ route('date_purchase_report_export')}}?from_date=${from_date}&to_date=${to_date}`)
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
            totalPurchaseQty    = 0,
            totalPurchaseAmount = 0,
            html            = ``;

            if(resData.length){

                resData.forEach(purchase => {
    
                    totalPurchaseQty += purchase.total_qty ?? 0;
                    totalPurchaseAmount += purchase.total_price ?? 0;
    
                    html += `<tr>
                        <td>${++count}</td>
                        <td><a target="_blank" href="{{ route('admin.purchase.showInvoice', '') }}/${purchase.invoice_no}"> ${purchase.invoice_no ?? 'N/A' }</a></td>
                        <td>${purchase.supplier_name ?? 'N/A' }</td>
                        <td>${purchase.purchase_date ?? 'N/A' }</td>
                        <td>${purchase.total_qty ?? 0 }</td>
                        <td>${purchase.total_price ?? 0 }</td>
                    </tr>`;
                })
            }else{
                html += `<tr class="odd text-center dataTables_empty_row">
                    <td colspan="6" class="dataTables_empty" valign="top">No data available in table</td>
                </tr>`;
            }

            setTimeout(function(){
                $('#mydataTable').find('tbody').html(html);
                $('#totalPurchaseQty').text(totalPurchaseQty);
                $('#totalPurchaseAmount').text(totalPurchaseAmount);
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

        open(`{{ route('admin.purchase_report_pdf') }}?from_date=${from_date}&to_date=${to_date}`,'_self')
    }
</script>

@endpush