@extends('backend.layouts.master')

@section('title', 'Manage Order')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Manage Custom Order</a> </h6>
                <div class="inner">
                    <button class="btn btn-sm btn-danger"><a class="text-white" href="{{ route('admin.customserviceorder.custom_service_order_pdf')}}"><i class="fa fa-file-pdf"> Export Pdf</i></a></button>
                    <button class="btn btn-sm btn-success"><a class="text-white" href="{{ route('custom_service_order_export')}}"><i class="fa fa-download"> Export excel</i></a></button>
                    <button class="btn btn-sm btn-info"><a class="text-white" id="add"><i class="fa fa-plus"> Custom Order</i></a></button>
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
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Product</th>
                                <th>Attachment</th>
                                <th>Status</th>
                                <th width="70" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @isset($customserviceorders)
                                @foreach ($customserviceorders as $customserviceorder)
                                    <tr customorder-data="{{ json_encode($customserviceorder) }}" data-category="{{ $customserviceorder->product ? $customserviceorder->product->category_id : ''}}" data-phone="{{ $customserviceorder->customer->customer_phone ?? '' }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('admin.customserviceorder.show', $customserviceorder->id) }}">
                                                {{ $customserviceorder->order_no }}
                                            </a>
                                        </td>
                                        <td>{{ $customserviceorder->created_at ? date('Y-m-d', strtotime($customserviceorder->created_at)) : 'N/A' }}</td>
                                        <td>{{ $customserviceorder->customer_name ?? 'N/A' }}</td>
                                        <td>{{ $customserviceorder->customer->customer_phone ?? 'N/A' }}</td>
                                        <td>{{ $customserviceorder->custom_service_product_name ?? 'N/A' }}</td>
                                        <td>
                                            @if( $customserviceorder->order_attachment )
                                                <a style="text-decoration: none;" href="{{ asset($customserviceorder->order_attachment) }}" download>
                                                    <img src="{{ asset($customserviceorder->order_attachment) }}" style="width: 80px;" alt="Attachment File">
                                                </a>
                                            @else 
                                                <img src="{{ asset('assets/img/blank-img.png') }}" alt="Attachment File">
                                            @endif
                                        </td>
                                    
                                        <td class="text-center">

                                            @if($customserviceorder->status == "pending")
                                            <span class="badge badge-warning status_modal" type="button" data-orderid="{{ $customserviceorder->id }}"
                                                data-status="{{ $customserviceorder->status }}">
                                                {{ ucfirst($customserviceorder->status) }}
                                            </span>
                                            @elseif($customserviceorder->status == "confirm")
                                            <span class="badge badge-info status_modal" type="button" data-orderid="{{ $customserviceorder->id }}"
                                                data-status="{{ $customserviceorder->status }}">
                                                {{ ucfirst($customserviceorder->status) }}
                                            </span>
                                            @elseif($customserviceorder->status == "processing")
                                            <span class="badge badge-dark status_modal" type="button" data-orderid="{{ $customserviceorder->id }}"
                                                data-status="{{ $customserviceorder->status }}">
                                                {{ ucfirst($customserviceorder->status) }}
                                            </span>
                                            @elseif($customserviceorder->status == "rejected" || $customserviceorder->status == "cancelled" ||
                                            $customserviceorder->status == "returned")
                                            <span class="badge badge-danger">
                                                {{ ucfirst($customserviceorder->status) }}
                                            </span>
                                            @elseif($customserviceorder->status == "completed")
                                            <span class="badge badge-success">
                                                Delivered
                                            </span>
                                            @endif

                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="fa fa-eye text-info text-decoration-none detail"></a>
                                            @if($customserviceorder->status !== "cancelled" && $customserviceorder->status !== "returned" && $customserviceorder->status !== "completed")
                                            <a href="javascript:void(0)" class="fa fa-edit mx-2 text-warning text-decoration-none update"></a>
                                            <a href="{{ route('admin.customserviceorder.destroy',$customserviceorder->id )}}" class="fa fa-trash text-danger text-decoration-none delete"></a>
                                            @endif 
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset
                       
                            
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    
    </div>
</div>


<div class="modal fade" id="customServiceOrderModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel"> <span class="heading">Create</span> Custom Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="service-container">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="font-weight-bold bg-custom-booking">Order Information</h5>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">Select Category</label>
                                <select name="category_id" id="category_id" class="form-control" data-placeholder="Select a Category">
                                    @isset($customservicecategories)
                                        @foreach ($customservicecategories as $customservicecategory)
                                            <option value="{{ $customservicecategory->id }}">{{ $customservicecategory->category_name  }}</option>
                                        @endforeach        
                                    @endisset
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_id">Select Product</label>
                                <select name="product_id" id="product_id" class="form-control" data-placeholder="Select a Product"></select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_name">Customer Name <span style="color: red;" class="req">*</span></label>
                                <input type="text" class="form-control" id="customer_name" placeholder="Customer Name">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_phone">Customer Phone</label>
                                <input name="customer_phone" id="customer_phone" class="form-control" placeholder="Customer Phone"/>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="customer_address">Customer Address</label>
                                <textarea name="customer_address" id="customer_address" cols="0" rows="5" class="form-control" placeholder="Customer Address"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label for="">Attachment File</label>
                            {!! renderFileInput(['id' => 'attachment_file', 'imageSrc' => '']) !!}
                            <span class="v-msg"></span>
                        </div>

                        {{-- <div class="col-md-12">
                            <div class="form-group">
                                <label>Is Active</label><br>
                                <input type="radio" name="is_active" id="isActive" checked>
                                <label for="isActive">Active</label>
                                <input type="radio" name="is_active" id="isInActive">
                                <label for="isInActive">Inactive</label>
                            </div>
                        </div> --}}

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="w-100">
                    <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                    <button id="customOrder_save_btn" type="button" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Save</span></button>
                    <button id="customOrder_update_btn" type="button" class="save_btn btn btn-sm btn-success float-right d-none"><i class="fa fa-save"></i> <span>Update</span></button>
                    <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="categoryDetailModal"  tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel1">Custom Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="modalDetail"></div>

            <div class="modal-footer">
                <div class="w-100">
                    <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Close</button>
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
                    <button type="button" id="change_order_status" class="btn btn-sm btn-success float-right mx-1"><i
                            class="fa fa-save"></i> Save</button>
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
        
        $(document).ready(function(){
            init();
            $(document).on('click','#add', createModal)
            $(document).on('change','#category_id', loadProductByCategory)
            $(document).on('click','#customOrder_save_btn', submitToDatabase)

            $(document).on('click', '.delete', deleteToDatabase)
            $(document).on('click' , '#reset', resetForm)
            $(document).on('change' ,'#attachment_file', checkImage)

            $(document).on('click', '.update', showUpdateModal)
            $(document).on('click','#customOrder_update_btn', updateToDatabase)
            $(document).on('click','.detail', showDataToModal)

            $(document).on('click', '.status_modal', openStatusModal)
            $(document).on('click', '#change_order_status', changeStatus)
        });



        function changeStatus(){
            let 
            order_id    = $('#order_status').attr('data-orderid'),
            status      = $('#order_status').val();

            ajaxFormToken();

            $.ajax({
                url     : `{{ route('admin.customserviceorder.approval','') }}/${order_id}`,
                method  : 'POST',
                data    : { status},
                success(res){
                    if(res.success){
                        setTimeout(()=> location.reload(), 2000)
                    }
                    console.log(res);
                },
                error(err){
                    console.log(err);
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



        function loadProductByCategory(){
            let product_id = $(this).val();

            $.ajax({
                url     : `{{ route('admin.customserviceorder.getProduct','')}}/${product_id}`,
                method  : 'GET',
                beforeSend(){
                    console.log('sending ...');
                },
                success(data){

                    let options = ``;

                    if(data.length){
                        data.forEach(item => {
                            options += `<option value="${item.id}">${item.text}</option>`;
                        });
                    }

                    $(document).find('#product_id').html(options);
                },
                error(err){
                    console.log(err);
                },
            })
        }

        function showDataToModal(){
            let 
            elem            = $(this),
            tr              = elem.closest('tr'),
            customOrder     = tr?.attr('customorder-data') ? JSON.parse(tr.attr('customorder-data')) : null,
            modalDetailElem = $('#modalDetail'),
            defaultPhone    = tr.attr('data-phone');

            if(customOrder){
                let html = `
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th>Customer</th>
                        <td>${customOrder.customer_name ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th>Order NO</th>
                        <td>${customOrder.order_no ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th>Order Date</th>
                        <td>${customOrder.created_at ? customOrder.created_at.split('T')[0] : 'N/A'}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>${(customOrder.customer_phone ?? defaultPhone) ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th>Product</th>
                        <td>${customOrder.custom_service_product_name ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>${customOrder.customer_address ?? 'N/A'}</td>
                    </tr>
                    <tr>
                        <th>Attachment</th>
                        <td>
                            ${ customOrder.order_attachment ? `
                                <img src="{{ asset('') }}${customOrder.order_attachment}" style="width:80px" alt="Category Image">
                            `: ` <img src="" style="width:80px" alt="Category Image">`}
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>${renderStatus(customOrder)}</td>
                    </tr>
                </table>
                `;


                // <tr>
                //     <th>Payment</th>
                //     <td>${customOrder.advance_balance ?? 'N/A'}</td>
                // </tr>
                // <tr>
                //     <th>Delivered Qty</th>
                //     <td>${customOrder.delivered_qty ?? 'N/A'}</td>
                // </tr>
        

                modalDetailElem.html(html);
            }

            $('#categoryDetailModal').modal('show')
        }


        function renderStatus(customOrder){
            let content = "";
            if(customOrder.status == "pending")
            {
                content = `<span class="badge badge-warning status_modal" type="button">
                    ${ capitalize(customOrder.status) }
                </span>`;
            }else if(customOrder.status == "confirm")
            {
                content = `<span class="badge badge-info status_modal" type="button">
                    ${ capitalize(customOrder.status) }
                </span>`;
            }
            else if(customOrder.status == "processing")
            {
                content =`<span class="badge badge-dark status_modal" type="button">
                    ${ capitalize(customOrder.status) }
                </span>`;
            }
            else if(customOrder.status == "rejected" || customOrder.status == "cancelled" || customOrder.status == "returned")
            {
                content =`<span class="badge badge-danger">
                    ${ capitalize(customOrder.status) }
                </span>`;
            }
            else if(customOrder.status == "completed"){
                content =`<span class="badge badge-success">
                    Delivered
                </span>`;
            }

            return content;
        }

        // call the func on change file input 
        function checkImage() {
            fileRead(this, '#img-preview');
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

                        console.log(res);
                        if(res?.success){
                            _toastMsg(res?.msg ?? 'Success!', 'success');
                            resetData();

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
                    dropdownParent  : '#customServiceOrderModal',
                    selector        : `#category_id`,
                    type            : 'select',
                },
                {
                    dropdownParent  : '#customServiceOrderModal',
                    selector        : `#product_id`,
                    type            : 'select',
                },
                {
                    selector        : `#booking_date`,
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

        function createModal(){
            showModal('#customServiceOrderModal');
            $('#customOrder_save_btn').removeClass('d-none');
            $('#customOrder_update_btn').addClass('d-none');
            $('#customServiceOrderModal .heading').text('Create')
            resetData();
        }

        function resetForm(){
            resetData()
        }

        function showUpdateModal(){
            resetData();
            let customOrder = $(this).closest('tr').attr('customorder-data');
            let category_id = $(this).closest('tr').attr('data-category');
            if(customOrder){

                console.log(category_id, $(this).closest('tr'));

                $('#customOrder_save_btn').addClass('d-none');
                $('#customOrder_update_btn').removeClass('d-none');

                customOrder = JSON.parse(customOrder);

                $('#customServiceOrderModal .heading').text('Edit').attr('data-id', customOrder?.id)

                $('#category_id').val(category_id).trigger('change')

                setTimeout(() => {
                 $('#product_id').val(customOrder?.custom_service_product_id).trigger('change')
                }, 1000);

                $('#customer_name').val(customOrder?.customer_name)
                $('#customer_phone').val(customOrder?.customer_phone)
                $('#customer_address').val(customOrder?.customer_address)

                // show previos image on modal
                $(document).find('#img-preview').attr('src', `{{ asset('') }}${customOrder.order_attachment}`);

                showModal('#customServiceOrderModal');
            }
        }

        function updateToDatabase(){
            ajaxFormToken();

            let id  = $('#customServiceOrderModal .heading').attr('data-id');
            let obj = {
                url     : `{{ route('admin.customserviceorder.update', '' ) }}/${id}`, 
                method  : "PUT",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function submitToDatabase(){
            //
            
            ajaxFormToken();

            let obj = {
                url     : `{{ route('admin.customserviceorder.store') }}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })

            resetData()
        }

        function formatData(){
            return {
                category_id                 : $('#category_id').val(),
                customer_name               : $('#customer_name').val(),
                customer_phone              : $('#customer_phone').val(),
                customer_address            : $('#customer_address').val().trim(),
                _token                      : `{{ csrf_token()}}`,
                custom_service_product_id   : $('#product_id').val(),
                order_attachment            : fileToUpload('#img-preview'),
            }
        }

        function resetData(){

            $('#category_id').val(null),
            $('#product_id').val(null),
            $('#customer_name').val(null),
            $('#customer_phone').val(null),
            $('#customer_address').val(null),
            $('#product_name').val(null),
            $('#order_attachment').val(null),
            fileToUpload('#img-preview')
        }

    </script>
@endpush