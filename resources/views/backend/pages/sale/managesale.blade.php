@extends('backend.layouts.master')

@section('title', 'Manage Sale')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Manage Sale</a> </h6>
                <div class="inner">
                    <button class="btn btn-sm btn-danger"><a class="text-white" href="{{ route('admin.ecom_sales.manage_sale_pdf')}}"><i class="fa fa-file-pdf"> Export Pdf</i></a></button>
                    <button class="btn btn-sm btn-success"><a class="text-white" href="{{ route('sale_data_export') }}"><i class="fa fa-download"> Export excel</i></a></button>
                    <button class="btn btn-sm btn-info"><a class="text-white" href="{{ route('admin.ecom_sales.add_sale') }}"><i class="fa fa-plus"> Sale</i></a></button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Invoice NO</th>
                                <th>Customer Name</th>
                                <th>Date</th>
                                <th>Total Qty</th>
                                <th>Total Amount</th>
                                <th>Total Payment</th>
                                <th>Total Due</th>
                                <th>Paymet status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($sales as $sale)
                                {{-- @dd($sale->saleProducts) --}}
                                <tr data-item="{{ $sale->id }}" data-sale="{{ json_encode($sale->saleProducts) }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="{{ route('admin.ecom_sales.showInvoice', $sale->invoice_no) }}">{{ $sale->invoice_no ?? 'N/A' }}</a></td>
                                    <td>{{ $sale->customer_name ?? 'N/A' }}</td>
                                    <td>{{ $sale->sales_date ?? 'N/A' }}</td>
                                    <td>{{ $sale->sold_total_qty ?? 0 }}</td>
                                    <td>{{ $sale->order_grand_total ?? 0 }}</td>
                                    <td class="payment-amount">{{ $sale->total_payment ?? '0.0' }}</td>
                                    <td class="payment-due">{{ $due = $sale->order_grand_total - $sale->total_payment }}</td>
                                    <td class="text-center payment-amount-status">
                                        @if($due > 0)
                                        <span class="btn btn-outline-success btn-sm payNow" data-total-bill="{{ $sale->order_grand_total - $sale->total_payment }}" data-saleid="{{ $sale->id }}">Pay Now</span>
                                        @else
                                        <span class="badge badge-success">Paid</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-warning warnig-hover return-sale mx-2 text-decoration-none">
                                            <i class="fa fa-angle-double-right"></i> Return
                                        </a>
                                        <a href="{{ route('admin.ecom_sales.edit', $sale->id) }}" class="fa fa-edit mx-2 text-warning text-decoration-none"></a>
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


<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true"
    role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel1">Pay Now</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <table class="table table-sm">
                    <tr>
                        <th>Bill Amount</th>
                        <th>:</th>
                        <td id="billAmount">0</td>
                    </tr>
                    <tr>
                        <th>Payment Amount</th>
                        <th>:</th>
                        <th>
                            <input type="text" name="total_payment" id="total_payment" value="0"><br>
                            <span class="v-error text-danger"></span>
                        </th>
                    </tr>
                </table>

            </div>

            <div class="modal-footer">
                <div class="w-100">
                    <button type="button" id="pay" class="btn btn-sm btn-success float-right mx-1"><i
                            class="fa fa-save"></i> Pay</button>
                    <button type="button" class="btn btn-sm btn-danger float-right mx-1"
                        data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true"
    role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel1">Purchase Return</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <table class="table table-sm">
                    <thead>
                        <tr class="bg-danger">
                            <th class="text-white">Product</th>
                            <th class="text-white">Available Qty</th>
                            <th class="text-white">Prev Returned Qty</th>
                            <th class="text-white">Return Qty</th>
                            <th class="text-center text-white">Sales Price</th>
                            <th class="text-center text-white">Total</th>
                        </tr>
                    </thead>
                    <tbody id="return_tbody"></tbody>
                </table>

            </div>

            <div class="modal-footer">
                <div class="w-100">
                    <button type="button" id="return" class="btn btn-sm btn-success float-right mx-1"><i
                            class="fa fa-save"></i> Return</button>
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
                $(document).on("click",".payNow", openPaymentModal)
                $(document).on("input change","#total_payment", isPaymentAmountValid)
                $(document).on("click","#pay", makePayment)
                $(document).on('click', '.delete', deleteToDatabase)
                $(document).on('click', '.return-sale', returnModalVisible)
                $(document).on('click', '#return', returnToDb)
            })
    
    
            function returnToDb(){
    
                let rows = $(document).find('#return_tbody').find(`tr[data-sale-product-id]`),
                products = [];
    
                [...rows].forEach(row => {
                    let sale_product_id = Number($(row).attr('data-sale-product-id'));
                    let returned_qty = Number($(row).find('.return_qty').val());
    
                    products.push({
                        sale_product_id,
                        returned_qty
                    });
                });
    
                // console.log(products);
    
                clearTimeout(timeId);
    
                ajaxFormToken();
    
    
                timeId = setTimeout(function(){
                    //
                    $.ajax({
                        url     : `{{ route('admin.return_sale.store') }}`,
                        method  : 'POST',
                        data    : { products },
                        success(res){
                            console.log(res);
                            if(res.success){
                                $('#return_tbody').html('');
                                $('#returnModal').modal('hide')
    
                                _toastMsg(res?.msg ?? 'Success!', 'success');
    
                                setTimeout(() => {
                                    location.reload()
                                }, 2000);
                            }else{
                                _toastMsg((res?.msg) ?? 'Something wents wrong!')
                            }
                        },
                        error(err){
                            console.log(err);
                            _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!')
                        },
                    })
    
                },500)
    
    
            }
    
            function returnModalVisible(){
                //
                let elem = $(this),
                row = elem.closest('tr'),
                data= row?.attr('data-sale') ? JSON.parse(row.attr('data-sale')) : null,
                html= "";
    
                if(data){
                    //
    
                    if(Array.isArray(data)){
                        data.forEach(product => {
                            html += `<tr data-sale-product-id="${product?.id}">
                                <th>${product.product_name ?? 'N/A'}</th>
                                <th>${product.product_qty ?? 0}</th>
                                <th>${product?.returned_qty ?? 0 }</th>
                                <th style="width: 100px !important;">
                                    <input type="number" class="text-center return_qty" name="return_qty" id="return_qty_${product.id}" style="max-width: 100px" value="0"><br>
                                    <span class="v-error text-danger"></span>
                                </th>
                                <th class="text-center">${product.sales_price ?? 0.0}</th>
                                <th class="text-center">${product.subtotal ?? 0.0}</th>
                            </tr>`;
                        })
                    }   
    
                }
    
                $('#return_tbody').html(html)
    
                $('#returnModal').modal('show')
            }
    
    
            function makePayment(){
                let 
                total_bill  = $('#billAmount').text(),
                sale_id = $('#billAmount').attr('data-saleid'),
                total_payment= $('#total_payment').val().trim();
    
    
                ajaxFormToken();
    
                $.ajax({
                    url     : `{{ route('admin.ecom_sales.payment') }}`,
                    method  : 'POST',
                    data    : { sale_id, total_payment},
                    success(res){
                        console.log(res);
    
                        if(res.success){
                            _toastMsg(res?.msg ?? 'Success!', 'success');
                            setTimeout(() => {
                                location.reload()
                            }, 2000);
                        }else{
                            _toastMsg(res?.msg ?? '');
                        }
                    },
                    error(err){
                        console.log(err);
                        _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!')
                    }
                })

            }
    
    
            function openPaymentModal(){
    
                let elem = $(this),
                id          = elem.attr('data-item'),
                bill        = elem.attr('data-total-bill'),
                sale_id     = elem.attr('data-saleid');
                //
                $('#billAmount').text(bill).attr('data-saleid', sale_id);
                $('#paymentModal').modal('show')
    
            }
    
    
            function isPaymentAmountValid(){
                let elem = $(this),
                payment = Number(elem.val().trim() ?? 0),
                bill    = Number($('#billAmount').text() ?? 0 );
    
                $('.v-error').text('')
                if(payment > bill){
                    elem.addClass("is-invalid");
                    $('.v-error').text('Invalid Payment!');
                }
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
    
    
    </script>
@endpush