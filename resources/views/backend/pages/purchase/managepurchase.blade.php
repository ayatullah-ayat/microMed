@extends('backend.layouts.master')

@section('title', 'Manage Purchase')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Manage Purchase</a> </h6>
                <div class="inner">
                    <button class="btn btn-sm btn-danger"><a class="text-white" href="{{ route('admin.purchase.get_purchase_pdf')}}"><i class="fa fa-file-pdf"> Export Pdf</i></a></button>
                    <button class="btn btn-sm btn-success"><a class="text-white" href="{{ route('purchase_data_export') }}"><i class="fa fa-download"> Export excel</i></a></button>
                    <a class="btn btn-sm btn-outline-info float-right" href="{{ route('admin.purchase.create') }}"><i class="fa fa-plus"> Purchase</i></a>
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
                                <th>Purchase Date</th>
                                <th>Total Qty</th>
                                <th>Total Amount</th>
                                <th>Total Payment</th>
                                <th>Total Due</th>
                                <th>Paymet status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($purchases as $item)

                                <tr data-item="{{ $item->id }}" data-purchase="{{ json_encode($item->purchaseProducts) }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('admin.purchase.showInvoice', $item->invoice_no) }}">{{ $item->invoice_no ?? 'N/A' }}</a>
                                    </td>
                                    <td>{{ $item->supplier_name ?? 'N/A' }}</td>
                                    <td>{{ $item->purchase_date ?? 'N/A' }}</td>
                                    <td>{{ $item->total_qty ?? '0' }}</td>
                                    <td>{{ $item->total_price ?? '0.0' }}</td>
                                    <td class="payment-amount">{{ $item->total_payment ?? '0.0' }}</td>
                                    <td class="payment-due">{{ $due = $item->total_price - $item->total_payment }}</td>
                                    <td class="text-center payment-amount-status">
                                        @if($due > 0)
                                        <span class="btn btn-outline-success btn-sm payNow" data-total-bill="{{ $item->total_price - $item->total_payment }}" data-purchaseid="{{ $item->id }}">Pay Now</span>   
                                        @else 
                                        <span class="badge badge-success">Paid</span>
                                        @endif  
                                    </td>
                                    <td class="text-center">
                                        @if(!$item->is_manage_stock)
                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-warning warnig-hover return-purchase mx-2 text-decoration-none">
                                            <i class="fa fa-angle-double-right"></i> Return
                                        </a>
                                        <a href="{{ route('admin.purchase.edit', $item->id) }}" class="fa fa-edit mx-2 text-warning text-decoration-none"></a>
                                        <a href="{{ route('admin.purchase.destroy', $item->id) }}" class="fa fa-trash text-danger text-decoration-none delete"></a>
                                        @else 
                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-warning warnig-hover return-purchase mx-2 text-decoration-none">
                                            <i class="fa fa-angle-double-right"></i> Return
                                        </a>
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


<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel1"
    aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
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
                    <button type="button" id="pay" class="btn btn-sm btn-success float-right mx-1"><i class="fa fa-save"></i> Pay</button>
                    <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Close</button>
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
                            <th class="text-center text-white">Unit Price</th>
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
    <style>
        .warnig-hover:hover{
            color: #fff !important;
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
            $(document).on("click",".payNow", openPaymentModal)
            $(document).on("input change","#total_payment", isPaymentAmountValid)
            $(document).on("click","#pay", makePayment)
            $(document).on('click', '.delete', deleteToDatabase)
            $(document).on('click', '.return-purchase', returnModalVisible)
            $(document).on('click', '#return', returnToDb)
        })


        function returnToDb(){

            let rows = $(document).find('#return_tbody').find(`tr[data-purchase-product-id]`),
            products = [];

            [...rows].forEach(row => {
                let purchase_product_id = Number($(row).attr('data-purchase-product-id'));
                let returned_qty = Number($(row).find('.return_qty').val());

                products.push({
                    purchase_product_id,
                    returned_qty
                });
            });

            // console.log(products);

            clearTimeout(timeId);

            ajaxFormToken();


            timeId = setTimeout(function(){
                //
                $.ajax({
                    url     : `{{ route('admin.return_purchase.store') }}`,
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
            data= row?.attr('data-purchase') ? JSON.parse(row.attr('data-purchase')) : null,
            html= "";

            if(data){
                //

                if(Array.isArray(data)){
                    data.forEach(product => {
                        html += `<tr data-purchase-product-id="${product?.id}">
                            <th>${product.product_name ?? 'N/A'}</th>
                            <th>${product.product_qty ?? 0}</th>
                            <th>${product?.returned_qty ?? 0 }</th>
                            <th style="width: 100px !important;">
                                <input type="number" class="text-center return_qty" name="return_qty" id="return_qty_${product.id}" style="max-width: 100px" value="0"><br>
                                <span class="v-error text-danger"></span>
                            </th>
                            <th class="text-center">${product.product_price ?? 0.0}</th>
                            <th class="text-center">${product.subtotal ?? 0.0}</th>
                        </tr>`;
                    })
                }   

                // console.log(data.purchase_products);

            }

            $('#return_tbody').html(html)

            $('#returnModal').modal('show')
        }


        function makePayment(){
            let 
            total_bill  = $('#billAmount').text(),
            purchase_id = $('#billAmount').attr('data-purchaseid'),
            total_payment= $('#total_payment').val().trim();

            // console.log(total_payment, purchase_id);

            ajaxFormToken();

            $.ajax({
                url     : `{{ route('admin.purchase.payment') }}`,
                method  : 'POST',
                data    : { purchase_id, total_payment},
                success(res){
                    console.log(res);

                    if(res.success){
                        setTimeout(() => {
                            location.reload()
                        }, 2000);
                    }
                },
                error(err){
                    console.log(err);
                }
            })

            // $('#paymentModal').modal('hide')
        }


        function openPaymentModal(){

            let elem = $(this),
            id          = elem.attr('data-item'),
            bill        = elem.attr('data-total-bill'),
            purchase_id = elem.attr('data-purchaseid');
            //
            $('#billAmount').text(bill).attr('data-purchaseid', purchase_id);
            $('#paymentModal').modal('show')

            //payment-amount-status
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