@extends('backend.layouts.master')

@section('title', 'Return Purchases')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Return Purchase</a> </h6>
                <div class="inner">
                    <button class="btn btn-sm btn-danger"><a class="text-white" href="{{ route('admin.return_purchase.return_purchase_pdf')}}"><i class="fa fa-file-pdf"> Export Pdf</i></a></button>
                    <button class="btn btn-sm btn-success"><a class="text-white" href="{{ route('purchase_return_export') }}"><i class="fa fa-download"> Export excel</i></a></button>
                    <a class="btn btn-sm btn-outline-info float-right" href="{{ route('admin.purchase.index') }}">Purchase List</i></a>
                    <a href="{{ route('admin.purchase.manage_stock') }}" class="btn btn-sm btn-outline-success float-right mx-2" id="manga-stock"> <i class="fas fa-shopping-bag"></i> Manage Stock</a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Invoice No</th>
                                <th>Supplier Name</th>
                                <th>Date</th>
                                <th>Total Qty</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($purchases as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('admin.return_purchase.showInvoice', $item->invoice_no) }}">{{ $item->invoice_no ?? 'N/A' }}</a>
                                    </td>
                                    <td>{{ $item->purchase->supplier->supplier_name ?? 'N/A' }}</td>
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