@extends('backend.layouts.master')

@section('title', 'Return Sales')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Return Sale</a> </h6>
                <div class="inner">
                    <button class="btn btn-sm btn-danger"><a class="text-white" href="{{ route('admin.return_sale.sale_return_pdf')}}"><i class="fa fa-file-pdf"> Export Pdf</i></a></button>
                    <button class="btn btn-sm btn-success"><a class="text-white" href="{{ route('sale_return_export') }}"><i class="fa fa-download"> Export excel</i></a></button>
                    <a class="btn btn-sm btn-outline-info" href="{{ route('admin.ecom_sales.manage_sale') }}">Sales List</i></a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Invoice No</th>
                                <th>Customer Name</th>
                                <th>Date</th>
                                <th>Total Qty</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($sales as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('admin.return_sale.showInvoice', $item->invoice_no) }}">{{ $item->invoice_no ?? 'N/A' }}</a>
                                    </td>
                                    <td>{{ $item->sale->customer->customer_name ?? 'N/A' }}</td>
                                    <td>{{ $item->created_at ? date('Y-m-d', strtotime($item->created_at)) : 'N/A' }}</td>
                                    <td>{{ $item->returned_qty ?? '0' }}</td>
                                    <td>{{ $item->subtotal ?? '0.0' }}</td>
                                </tr>
                            @endforeach
                        </tbody>

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
@endpush

@push('js')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/backend/libs/demo/datatables-demo.js') }}"></script>
@endpush