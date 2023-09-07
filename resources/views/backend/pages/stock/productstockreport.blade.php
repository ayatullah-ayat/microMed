@extends('backend.layouts.master')

@section('title', 'Product Stock Report')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Purchase Product Stock Report</a> </h6>
                <div class="inner">
                    <a class="btn btn-sm btn-danger downloadPDF"><i class="fa fa-file-pdf"></i> Export PDF</a>
                    <button class="btn btn-sm btn-success"><a class="text-white" id="excelExport" href="javascript:void(0) {{ route('purchase_product_stock_report_export')}}?supplier_id=&product_id=&from_date=&to_date="><i class="fa fa-download"> Export excel</i></a></button>
                </div>
            </div>

            <div class="card-body">
                <div class="row filterRow">
                    <div class="col-md-3" data-col="col">
                        <div class="form-group">
                            <label for="supplier"> Supplier<span style="color: red;" class="req">*</span></label>
                            <select name="supplier" class="supplier" data-required id="supplier"
                                data-placeholder="Select Supplier">
                                @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="v-msg"></span>
                    </div>

                    <div class="col-md-4" data-col="col">
                        <div class="form-group">
                            <label for="product"> Product<span style="color: red;" class="req">*</span></label>
                            <select name="product" class="product" data-required id="product" data-placeholder="Select Product"></select>
                        </div>
                        <span class="v-msg"></span>
                    </div>

                    <div class="col-md-2" data-col="col">
                        <div class="form-group">
                            <label for="purchase_from_date">From Date</label>
                            <input type="text" data-required autocomplete="off" class="form-control" id="purchase_from_date"
                                name="purchase_from_date">
                        </div>
                        <span class="v-msg"></span>
                    </div>

                    <div class="col-md-2" data-col="col">
                        <div class="form-group">
                            <label for="purchase_to_date">To Date</label>
                            <input type="text" data-required autocomplete="off" class="form-control" id="purchase_to_date" name="purchase_to_date">
                        </div>
                        <span class="v-msg"></span>
                    </div>

                    <div class="col-md-1">
                        <p></p>
                        <button class="btn btn-sm btn-success" id="search_result"><i class="fa fa-search"></i>
                            Search</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="mydataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr style="background-color: #eee !important;">
                                <th>SL</th>
                                <th>Product Name</th>
                                <th>Unit</th>
                                <th>Supplier Price</th>
                                <th>In Qty</th>
                                <th>Stock Qty</th>
                                <th>Returned Qty</th>
                                <th>In Amount</th>
                                <th>Stock Amount</th>
                                <th>Returned Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd text-center dataTables_empty_row">
                                <td colspan="10" class="dataTables_empty" valign="top">No data available in table</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="" style="background-color: #eee !important;">
                                <th colspan="4"></th>
                                <th id="totalInqty">{{ 0 }}</th>
                                <th id="totalStockqty">{{ 0 }}</th>
                                <th id="totalReturnqty">{{ 0 }}</th>
                                <th id="totalInAmount">0</th>
                                <th id="totalInStock">0</th>
                                <th id="totalInReturn">0</th>
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
    .dataTables_length {
        display: none !important;
    }

    @media screen and (min-width: 768px) {

        .filterRow {
            position: relative;
            /* margin-bottom: -50px !important; */
        }

        .filterRow #search_result {
            margin-bottom: -40px !important
        }

        .filterRow input,
        .filterRow #supplier,
        .filterRow .form-group {
            z-index: 20 !important;
            position: relative;
        }

        #dataTable_wrapper {
            display: inline !important;
        }

        .filterRow #search_result {
            z-index: 21 !important;
            position: relative;
        }

        #mydataTable_wrapper {
            z-index: 22;
            position: relative;
        }
    }


    #dataTable {
        z-index: 1 !important;
        position: relative !important;
    }

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
            init()
            $(document).on('click','#search_result', searchSupplierProduct)
            $(document).on('change','#supplier', getProductBySupplier)

            $(document).on('click','.downloadPDF', openPDF)
        })


        function getProductBySupplier(){
            let supplier_id = $(this).val();

             $.ajax({
                url     : `{{ route('admin.getProductBySupplier') }}`,
                method  : 'GET',
                data    : { supplier_id },
                success(data){
                    // console.log(data);
                    let options = ``;
                    data.forEach(d => {
                        options += `<option value="${d.id}">${d.product_name}</option>`;
                    });

                    $('#product').html(options);
                },
                error(err){
                    console.log(err);
                },
            })

        }



        function searchSupplierProduct(){
            let 
            supplier_id = $('#supplier').val(),
            product_id  = $('#product').val(),
            from_date   = $('#purchase_from_date').val(),
            to_date     = $('#purchase_to_date').val();


            clearTimeout(timeId);

            timeId = setTimeout(() => {
                $.ajax({
                    url     : `{{ route('admin.product_stock_report') }}`,
                    method  : 'GET',
                    data    : { supplier_id, product_id, from_date, to_date },
                    beforeSend(){
                        $('#mydataTable').find('tbody').html(`
                            <tr class="odd text-center dataTables_empty_row">
                                <td colspan="10" class="h6" valign="top">
                                    <i class="fa fa-spinner fa-spin fa-2x"></i> Loading ...
                                </td>
                            </tr>
                        `);
                    },
                    success(data){
                        loadAjaxData(data);
                        $('#excelExport').attr('href',`{{ route('purchase_product_stock_report_export')}}?supplier_id=${supplier_id}&product_id=${product_id}&from_date=${from_date}&to_date=${to_date}`)
                    },
                    error(err){
                        console.log(err);
                    },
                })
            }, 500);
        }



        function loadAjaxData(resData){

            let 
            count          = 0,
            totalProductQty= 0,
            totalStockQty  = 0,
            totalReturnQty = 0,
            totalInAmount = 0,
            totalInStock = 0,
            totalInReturn = 0,
            html           = ``;

            if(resData.length){

                resData.forEach(item => {
                    // console.log(item);
    
                    totalProductQty += item.product_qty ?? 0;
                    totalStockQty   += item.stocked_qty ?? 0;
                    totalReturnQty  += item.returned_qty ?? 0;

                    totalInAmount   += item.subtotal ?? 0;
                    totalInStock    += item.product_price * item.stocked_qty ?? 0;
                    totalInReturn   += item.product_price * item.returned_qty ?? 0;
    
                    html += `<tr>
                        <td>${++count}</td>
                        <td>${ item.product_name ?? 'N/A' }</td>
                        <td>${ item.product_unit ?? 'N/A' }</td>
                        <td>${ item.product_price ?? '0' }</td>
                        <td class="inqty">${ item.product_qty ?? '0' }</td>
                        <td class="stockqty">${ item.stocked_qty ?? '0' }</td>
                        <td class="returnqty">${ item.returned_qty ?? '0' }</td>
                        <td class="inPrice">${ item.subtotal ?? '0' }</td>
                        <td class="stockPrice">${ item.product_price * item.stocked_qty ?? '0' }</td>
                        <td class="returnPrice">${ item.product_price * item.returned_qty ?? '0' }</td>
                    </tr>`;
                })
            }else{
                html += `<tr class="odd text-center dataTables_empty_row">
                    <td colspan="10" class="dataTables_empty" valign="top">No data available in table</td>
                </tr>`;
            }

            setTimeout(function(){
                $('#mydataTable').find('tbody').html(html);
                $('#totalInqty').text(totalProductQty);
                $('#totalStockqty').text(totalStockQty);
                $('#totalReturnqty').text(totalReturnQty);
                $('#totalInAmount').text(totalInAmount);
                $('#totalInStock').text(totalInStock);
                $('#totalInReturn').text(totalInReturn);
            },1000)

        }

    function init(){
    
        let arr=[
            {
                selector : `#supplier`,
                type : 'select',
            },
            {
                selector : `#product`,
                type : 'select',
            },
            {
                selector : `#purchase_from_date`,
                type : 'date',
                format : 'yyyy-mm-dd',
            }
        ];
    
        globeInit(arr);


        $('#purchase_to_date').datepicker({
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
        supplier_id = $('#supplier').val(),
        product_id  = $('#product').val(),
        from_date   = $('#purchase_from_date').val(),
        to_date     = $('#purchase_to_date').val();

        open(`{{ route('admin.product_stock_pdf') }}?supplier_id=${supplier_id}&product_id=${product_id}&from_date=${from_date}&to_date=${to_date}`,'_self')
    }


</script>









@endpush