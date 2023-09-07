@extends('backend.layouts.master')

@section('title', 'Supplier Stock Report')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="javascript:void(0)" class="text-decoration-none">Supplier Stock Report</a> </h6>
                <div class="inner">
                    <a class="btn btn-sm btn-danger downloadPDF"><i class="fa fa-file-pdf"></i> Export PDF</a>
                    <button class="btn btn-sm btn-success"><a class="text-white" id="excelExport" href="javascript:void(0) {{route('supplier_stock_report_export')}}?supplier_id=&date="><i class="fa fa-download"> Export excel</i></a></button>
                </div>
            </div>

            <div class="card-body">
                <div class="row filterRow">
                    <div class="col-md-3" data-col="col">
                        <div class="form-group">
                            <label for="supplier"> Supplier<span style="color: red;" class="req">*</span></label>
                            <select name="supplier" class="supplier" data-required id="supplier" data-placeholder="Select Supplier">
                                @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="v-msg"></span>
                    </div>
                    
                    <div class="col-md-3" data-col="col">
                        <div class="form-group">
                            <label for="purchase_date">Date <span style="color: red;" class="req">*</span></label>
                            <input type="text" data-required autocomplete="off" class="form-control" id="purchase_date"
                                name="purchase_date">
                        </div>
                        <span class="v-msg"></span>
                    </div>

                    <div class="col-md-2">
                        <p></p>
                        <button class="btn btn-sm btn-success" id="search_result"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="mydataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr style="background-color: #eee !important;">
                                <th>SL</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Unit</th>
                                <th>Sell Price</th>
                                <th>Supplier Price</th>
                                <th>In Qty</th>
                                <th>Stock Qty</th>
                                <th>Returned Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd text-center dataTables_empty_row">
                                <td colspan="9" class="dataTables_empty" valign="top">No data available in table</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="" style="background-color: #eee !important;">
                                <th colspan="6"></th>
                                <th id="totalInqty">{{ 0 }}</th>
                                <th id="totalStockqty">{{ 0 }}</th>
                                <th id="totalReturnqty">{{ 0 }}</th>
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
        .dataTables_length{
         display: none !important;
         }
       @media screen and (min-width: 768px){
            
            .filterRow {
                position: relative;
                /* margin-bottom: -50px !important; */
            }
            
            .filterRow #search_result{
                margin-bottom: -40px !important
            }
            
            .filterRow input,
            .filterRow #supplier,
            .filterRow .form-group
            {
                z-index: 20 !important;
                position: relative;
            }
            #dataTable_wrapper{
                display: inline !important;
            }

            .filterRow #search_result{
                z-index: 21 !important;
                position: relative;
            }

            #mydataTable_wrapper{
                z-index: 22;
                position: relative;
            }
       }


        #dataTable{
            z-index: 1 !important;
            position: relative !important;
        }

        .dataTables_empty{
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
            $(document).on('keyup change', '.dataTables_filter input[type="search"]', calcSummary)
            $(document).on('click','#search_result', searchSupplierProduct)

            $(document).on('click','.downloadPDF', openPDF)
        })

        function searchSupplierProduct(){
            let 
            supplier_id = $('#supplier').val(),
            date        = $('#purchase_date').val();


            clearTimeout(timeId);

            timeId = setTimeout(() => {
                $.ajax({
                    url     : `{{ route('admin.supplier_stock_report') }}`,
                    method  : 'GET',
                    data    : { supplier_id, date },
                    beforeSend(){
                        $('#mydataTable').find('tbody').html(`
                            <tr class="odd text-center dataTables_empty_row">
                                <td colspan="9" class="h6" valign="top">
                                    <i class="fa fa-spinner fa-spin fa-2x"></i> Loading ...
                                </td>
                            </tr>
                        `);
                    },
                    success(data){
                        loadAjaxData(data);
                        $('#excelExport').attr('href',`{{route('supplier_stock_report_export')}}?supplier_id=${supplier_id}&date=${date}`)
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
            html           = ``;

            if(resData.length){

                resData.forEach(item => {
    
                    totalProductQty += item.product_qty ?? 0;
                    totalStockQty += item.stocked_qty ?? 0;
                    totalReturnQty += item.returned_qty ?? 0;
    
                    html += `<tr>
                        <td>${++count}</td>
                        <td>${ item.product_name ?? 'N/A' }</td>
                        <td>${ item.category_name ?? 'N/A' }</td>
                        <td>${ item.product_unit ?? 'N/A' }</td>
                        <td>${ item.sales_price ?? '0' }</td>
                        <td>${ item.product_price ?? '0' }</td>
                        <td class="inqty">${ item.product_qty ?? '0' }</td>
                        <td class="outqty">${ item.stocked_qty ?? '0' }</td>
                        <td class="stockqty">${ item.returned_qty ?? '0' }</td>
                    </tr>`;
                })
            }else{
                html += `<tr class="odd text-center dataTables_empty_row">
                    <td colspan="9" class="dataTables_empty" valign="top">No data available in table</td>
                </tr>`;
            }

            setTimeout(function(){
                $('#mydataTable').find('tbody').html(html);
                $('#totalInqty').text(totalProductQty);
                $('#totalStockqty').text(totalStockQty);
                $('#totalReturnqty').text(totalReturnQty);
            },1000)

        }

        function calcSummary(){
            let rows = $('#dataTable').find('tbody').find('tr');
            let 
            totalProductQty = 0,
            totalOutQty     = 0,
            totalStockQty   = 0;

            if(rows.length){
                
                [...rows].forEach( row => {
                    const tr = $(row);
                    if(tr.find('.inqty').length){

                        totalProductQty += Number(tr.find('.inqty').text() ?? 0);
                        totalOutQty += Number(tr.find('.outqty').text() ?? 0);
                        totalStockQty += Number(tr.find('.stockqty').text() ?? 0);
                    }
                })

            }

            $('#totalInqty').text(totalProductQty);
            $('#totalOutqty').text(totalOutQty);
            $('#totalStockqty').text(totalStockQty);
        }


    function init(){
    
        let arr=[
            {
                selector : `#supplier`,
                type : 'select',
            },
            {
                selector : `#purchase_date`,
                type : 'date',
                format : 'yyyy-mm-dd',
            },
        ];
    
        globeInit(arr);
    }


    function openPDF(e){
        e.preventDefault();

        let
        supplier_id = $('#supplier').val(),
        date        = $('#purchase_date').val();

        open(`{{ route('admin.supplier_stock_pdf') }}?supplier_id=${supplier_id}&purchase_date=${date}`,'_self')
    }

    </script>
@endpush