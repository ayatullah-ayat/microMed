@extends('backend.layouts.master')

@section('title', 'Stock Report')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="javascript:void(0)" class="text-decoration-none">Stock Report</a> </h6>
                <div class="inner">
                    <button class="btn btn-sm btn-danger"><a class="text-white" href="{{ route('admin.stock_pdf')}}"><i class="fa fa-file-pdf"> Export Pdf</i></a></button>
                    <button class="btn btn-sm btn-success"><a class="text-white" download="" href="{{ route('stock_report_export')}}"><i class="fa fa-download"> Export excel</i></a></button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr style="background-color: #eee !important;">
                                <th>SL</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Unit</th>
                                <th>Sell Price</th>
                                <th>Supplier Price</th>
                                <th>In Qty</th>
                                <th>Out Qty</th>
                                <th>Stock Qty</th>
                            </tr>
                        </thead>
                        <tbody>

                            @php
                                $totalProductQty= 0;
                                $totalOutQty    = 0;
                                $totalStockQty  = 0;
                            @endphp

                            @foreach ($stocks as $item)
                                @php
                                    $totalProductQty += $item->total_product_qty;
                                    $totalOutQty += $item->total_stock_out_qty;
                                    $totalStockQty += $item->total_stock_qty;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->product_name ?? 'N/A' }}</td>
                                    <td>{{ $item->category_name ?? 'N/A' }}</td>
                                    <td>{{ $item->product_unit ?? 'N/A' }}</td>
                                    <td>{{ $item->sales_price ?? 'N/A' }}</td>
                                    <td>{{ $item->purchase_price ?? 'N/A' }}</td>
                                    <td class="inqty">{{ $item->total_product_qty ?? 'N/A' }}</td>
                                    <td class="outqty">{{ $item->total_stock_out_qty ?? 'N/A' }}</td>
                                    <td class="stockqty">{{ $item->total_stock_qty ?? 'N/A' }}</td>
                                </tr>
                            @endforeach

                            
                        </tbody>
                        <tfoot>
                            <tr class="" style="background-color: #eee !important;">
                                <th colspan="6"></th>
                                <th id="totalInqty">{{ $totalProductQty }}</th>
                                <th id="totalOutqty">{{ $totalOutQty }}</th>
                                <th id="totalStockqty">{{ $totalStockQty }}</th>
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
@endpush

@push('js')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/backend/libs/demo/datatables-demo.js') }}"></script>
    <script>
        $(document).ready(function(){
            $(document).on('keyup change', '.dataTables_filter input[type="search"]', calcSummary)
        })


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
    </script>
@endpush