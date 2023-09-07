@extends('backend.layouts.master')

@section('title', 'Manage Order')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="javascript:void(0)" class="text-decoration-none">Manage Order</a> </h6>
           
                <div class="inner">
                    <button class="btn btn-sm btn-danger"><a class="text-white" href="{{ route('admin.ecom_orders.export_pdf')}}"><i class="fa fa-file-pdf"> Export Pdf</i></a></button>
                    <button class="btn btn-sm btn-success"><a class="text-white" href="{{ route('order_export')}}"><i class="fa fa-download"> Export excel</i></a></button>
                    <button class="btn btn-sm btn-info"><a class="text-white"    href="{{ route('admin.ecom_orders.order_add') }}"><i class="fa fa-plus"> Order</i></a></button>
                </div>
              
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Order NO</th>
                                <th>Order Date</th>
                                <th>Customer Name</th>
                                <th>Customer Phone</th>
                                <th>Shipping Address</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th width="70" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order->order_no ?? 'N/A' }}</td>
                                    <td>{{ $order->order_date ?? 'N/A' }}</td>
                                    <td>{{ ($order->customer_name ?? $order->customer->customer_name ) ?? 'N/A' }}</td>
                                    <td>{{ ($order->customer_phone  ?? $order->customer->customer_phone) ?? 'N/A' }}</td>
                                    <td>{{ $order->shipping_address  ?? 'N/A' }}</td>
                                    <td>{{ $order->order_total_price  ?? 'N/A' }}</td>
                                    <td class="text-center">

                                        @if($order->status == "pending")
                                        <span class="badge badge-warning status_modal" type="button" data-orderid="{{ $order->id }}" data-status="{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        @elseif($order->status == "confirm")
                                        <span class="badge badge-info status_modal" type="button" data-orderid="{{ $order->id }}" data-status="{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        @elseif($order->status == "processing")
                                        <span class="badge badge-dark status_modal" type="button" data-orderid="{{ $order->id }}" data-status="{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        @elseif($order->status == "rejected" || $order->status == "cancelled" || $order->status == "returned")
                                        <span class="badge badge-danger">
                                            {{ ucfirst($order->status)  }}
                                        </span>
                                        @elseif($order->status == "completed")
                                        <span class="badge badge-success">
                                            Delivered
                                        </span>
                                        @endif 
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.ecom_orders.show', $order->id) }}" class="fa fa-eye text-info text-decoration-none"></a>
                                        @if($order->status !== "completed" && $order->status !== "cancelled")
                                        <a href="{{ route('admin.ecom_orders.edit', $order->id)}}" class="fa fa-edit mx-2 text-warning text-decoration-none"></a>
                                        <a href="{{ route('admin.ecom_orders.destroy', $order->id )}}" class="fa fa-trash text-danger text-decoration-none delete"></a>
                                        @endif 
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    
    </div>
</div>

<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true"
    role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel1">Order Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <table class="table table-sm">
                    <tr>
                        <th style="max-width: max-content;">Select Status</th>
                        <th>:</th>
                        <th>
                            <select name="order_status" id="order_status" data-placeholder="Select a Status"></select>
                        </th>
                    </tr>
                </table>

            </div>

            <div class="modal-footer">
                <div class="w-100">
                    <button type="button" id="change_order_status" class="btn btn-sm btn-success float-right mx-1"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-sm btn-danger float-right mx-1"
                        data-dismiss="modal">Close</button>
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
        /* .custom-btn-import {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 5px;
        } */
    </style>
@endpush

@push('js')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/backend/libs/demo/datatables-demo.js') }}"></script>

    <script>
        $(document).ready(function(){
            init();
            $(document).on('click', '.status_modal', openStatusModal)
            $(document).on('click', '#change_order_status', changeStatus)
            $(document).on('click', '.delete', deleteToDatabase)
        });


        function changeStatus(){
            let 
            elem        = $(this),
            order_id    = $('#order_status').attr('data-orderid'),
            status      = $('#order_status').val();

            ajaxFormToken();

            $.ajax({
                url     : `{{ route('admin.ecom_orders.approval','') }}/${order_id}`,
                method  : 'POST',
                data    : { status},
                beforeSend(){
                    elem.html(`<i class="fa fa-spinner fa-spin"></i> Processing ...`)
                },
                success(res){
                    if(res.success){
                        setTimeout(()=> location.reload(), 2000)
                    }
                    console.log(res);
                    elem.html(`<i class="fa fa-save"></i> Save`)
                },
                error(err){
                    console.log(err);
                    elem.html(`<i class="fa fa-save"></i> Save`)
                },
            })
        }


        function openStatusModal(){
            //

            let elem    = $(this),
            status      = elem.attr('data-status'),
            order_id    = elem.attr('data-orderid'),
            options     = ``;

            $('#order_status').html(`
                <option value="pending" ${ status == "pending" ? "selected" : ''}>Pending</option>
                <option value="confirm" ${ status == "confirm" ? "selected" : ''}>Confirm</option>
                <option value="processing" ${ status == "processing" ? "selected" : ''}>Processing</option>
                <option value="completed" ${ status == "completed" ? "selected" : ''}>Completed</option>
                <option value="cancelled" ${ status == "cancelled" ? "selected" : ''}>Cancelled</option>
            `).attr('data-orderid', order_id).select2({
                width: '100%',
                theme : 'bootstrap4',
            });
        

            $('#statusModal').modal('show')
        }

        function deleteToDatabase(e){
            e.preventDefault();
            let elem = $(this),
            href = elem.attr('href');
            if(confirm("Are you sure to delete the record?")){
                ajaxFormToken();

                $.ajax({
                    url     : href, 
                    method  : "DELETE",
                    data    : {},
                    success(res){

                        // console.log(res?.data);
                        if(res?.success){
                            _toastMsg(res?.msg ?? 'Success!', 'success');

                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error(err){
                        console.log(err);
                        _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!')
                    },
                });
            }
        }

        function init(){

            let arr=[
                {
                    dropdownParent  : '#categoryModal',
                    selector        : `#email_template`,
                    type            : 'select',
                },
                {
                    selector        : `#order_date`,
                    type            : 'date',
                    format          : 'yyyy-mm-dd',
                },
            ];

            globeInit(arr);

            // $(`#stuff`).select2({
            //     width           : '100%',
            //     dropdownParent  : $('#categoryModal'),
            //     theme           : 'bootstrap4',
            // }).val(null).trigger('change')


            // $('#booking_date').datepicker({
            //     autoclose : true,
            //     clearBtn : false,
            //     todayBtn : true,
            //     todayHighlight : true,
            //     orientation : 'bottom',
            //     format : 'yyyy-mm-dd',
            // })
        }

    </script>
@endpush