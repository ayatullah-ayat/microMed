@extends('backend.layouts.master')

@section('title', 'Edit Order')

@section('content')
    <div>
        <div class="container-fluid card p-3 shadow">
            <div class="py-3 d-flex justify-content-between align-items-center">
                <h4 class="text-dark font-weight-bold text-dark">Edit Order Information</h4>
                <a class="text-white btn btn-sm btn-info float-right" href="{{ route('admin.ecom_orders.order_manage') }}"><i class="fa fa-arrow-left"> Back</i></a>
            </div>
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="product"> Order NO</label>
                        <input readonly type="text" class="form-control" id="order_no" value="{{ $order->order_no }}">
                    </div>
                    <span class="v-msg"></span>
                </div>

                <div class="col-md-4" data-col="col">
                    <div class="form-group">
                        <label for="order_date">Order Date </label>
                        <input type="text" data-required autocomplete="off" class="form-control" id="order_date" name="order_date" placeholder="Order Date" value="{{ $order->order_date }}">
                    </div>
                    <span class="v-msg"></span>
                </div>

                <div class="col-md-4" data-col="col">
                    <div class="form-group">
                        <label for="customer"> Customer </label>
                        <select name="customer" class="customer" data-required id="customer" data-placeholder="Select Customer">
                            @if ($customers)
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $order->customer_id == $customer->id ? 'selected':'' }}>{{ $customer->customer_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <span class="v-msg"></span>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="note">Note</label>
                        <textarea name="note" id="note" cols="0" rows="3" class="form-control note" placeholder="Note" value="" >{{ $order->order_note }}</textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="shipping_address">Shipment</label>
                        <textarea name="shipping_address" id="shipping_address" cols="0" rows="3" class="form-control shipment" placeholder="Shipping Address" value="" >{{ $order->shipping_address }}</textarea>
                    </div>
                </div>



                <div class="col-md-12">
                    <div class="table-responsive w-100">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-danger text-white">
                                <tr>
                                    <th>Item Information</th>
                                    <th>Color</th>
                                    <th width="200">Size</th>
                                    <th width="100" class="text-center">Qty</th>
                                    <th width="150" class="text-center">Sales Price</th>
                                    <th width="150" class="text-center">Discount Price</th>
                                    <th width="150" class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody id="edittbody">

                                @foreach ($order->orderDetails as $item)
                                <tr>
                                    <td>
                                        <input type="hidden" name="ids[]" class="product_ids" value="{{ $item->product_id }}">
                                        <div class="form-group">
                                            <select name="product" class="product" data-required data-placeholder="Select Customer">
                                                @if ($products)
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' :'' }}>{{ $product->product_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <span class="v-msg"></span>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="color" class="color" data-required data-placeholder="Select Color">
                                                @if($colors)
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->variant_name }}" {{ $item->product_color == $color->variant_name ? 'selected' : '' }}>{{ $color->variant_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <span class="v-msg"></span>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="size" class="size" data-required data-placeholder="Select Sizes">
                                                @if($sizes)
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->variant_name }}" {{ $item->product_size == $color->variant_name ? 'selected' : '' }}>{{ $size->variant_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <span class="v-msg"></span>
                                    </td>
                                    <td >
                                        <input type="text" class="form-control calculatePrice order_qty" value="{{ $item->product_qty }}">
                                    </td>
                                    <td>
                                        <input type="text"  class="form-control calculatePrice product_price" readonly value="{{ $item->product_price }}">
                                        <input type="hidden" class="form-control purchase_price" value="{{ $item->purchase_price }}">
                                    </td>
                                    <td>
                                        <input type="text"  class="form-control discount_price calculatePrice" value="{{ $item->discount_price }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control total_price" value="{{ $item->subtotal }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-12 d-flex justify-content-end">
                    <button id="order_update_btn" class="btn btn-success text-right outline-none" data-id="{{ $order->id }}"> <i class="fa fa-save"></i> Update</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/purchase/addpurchase.css')}}">
@endpush

@push('js')
<script>
    $(document).ready(function(){
        init();
        $(document).on('click','#order_update_btn', updateToDatabase)
        $(document).on('input keyup change', ".calculatePrice", OrderPriceCalculation)
        $(document).on('change', ".product", getProductInfo)
    });



      function getProductInfo(){
        let 
        elem        = $(this),
        product_id  = elem.val(),
        row         = elem.closest('tr'),
        colorSelect = row.find('.color'),
        sizeSelect  = row.find('.size'),
        salesElem   = row.find('.product_price');

        $.ajax({
            url     : `{{ route('admin.ecom_sales.getVariantsByProduct','') }}/${product_id}`,
            method  : 'GET',
            success(res){

                let option1 = ``;
                let option2 = ``;

                if(res.colors){
                    res.colors.forEach(colr => {
                        option1 += `<option value="${colr.color_name}">${colr.color_name}</option>`;
                    })
                }

                
                if(res.sizes){
                    res.sizes.forEach(size => {
                        option2 += `<option value="${size.size_name}">${size.size_name}</option>`;
                    })
                }

                colorSelect.html(option1);
                sizeSelect.html(option2);

                if(res?.product){
                    salesElem.val(res.product.sales_price).prop("readonly",true);
                }

                OrderPriceCalculation();
            },
            error(err){
                console.log(err);
            }

        })
    }



    function OrderPriceCalculation(){
            let rows = $('#edittbody').find('tr');
            let total_qty = 0;
            let grandtotal = 0;
            
            [...rows].forEach( row => {
                let qty         = Number($(row).find('.order_qty').val() ?? 0);
                let price       = Number($(row).find('.product_price').val() ?? 0);
                let discount    = Number($(row).find('.discount_price').val() ?? 0);
            
                let total = (price * qty) - discount;
                $(row).find('.total_price').val(total);

                total_qty += qty;
                grandtotal += price;
            
            });
    }

    function init(){

        let arr=[
            {
                selector        : `#customer`,
                type            : 'select',
                selectedVal     : @json($order->customer_id)
            },
            {
                selector        : `#order_date`,
                type            : 'date',
                format          : 'yyyy-mm-dd',
            },
        ];

        globeInit(arr);

        $(`.product`).select2({
            width           : '100%',
            theme           : 'bootstrap4',
        }).trigger('change');

        $(`.color`).select2({
            width           : '100%',
            theme           : 'bootstrap4',
        }).trigger('change');

        $(`.size`).select2({
            width           : '100%',
            theme           : 'bootstrap4',
        }).trigger('change');

    }

    function updateToDatabase(){
        ajaxFormToken();

        let order_id = $(this).attr('data-id');

        let obj = {
            url     : `{{ route('admin.ecom_orders.update','')}}/${order_id}`, 
            method  : "PUT",
            data    : formatData(),
        };

        $.ajax({
            ...obj,
            success(res){
                console.log(res);
                if(res?.success){
                    _toastMsg(res?.msg ?? 'Success!', 'success');
                    setTimeout(() => {
                        open(`{{ route('admin.ecom_orders.order_manage') }}`,'_self')
                    }, 2000);
                }else{
                    _toastMsg(res?.msg ?? 'Something wents wrong!');
                }

            },
            error(err){
                console.log(err);
                _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!')
            },
        });


    }

    function formatData(){
        return {
        order_no            : $('#order_no').val(),
        order_date          : $('#order_date').val(),
        customer_id         : $('#customer').val(),
        order_note          : $('#note').val(),
        shipping_address    : $('#shipping_address').val(),
        products            : productsInfo()
        }
    }

    function productsInfo(){

        let rows        = $('#edittbody').find('tr');
        let total_qty   = 0;
        let grandtotal  = 0;
        let productsArr =[];

        [...rows].forEach( row => {
            let  order_no       = $('#order_no').val().trim();
            let product_id      = $(row).find('.product').val();
            let product_name    = $(row).find('.product').find('option:selected').text();
            let product_color   = $(row).find('.color').val();
            let product_size    = $(row).find('.size').val();
            let product_qty     = Number($(row).find('.order_qty').val() ?? 0);
            let product_price   = Number($(row).find('.product_price').val() ?? 0);
            let discount_price  = Number($(row).find('.discount_price').val() ?? 0);
            let subtotal        = Number($(row).find('.total_price').val() ?? 0);
            let purchase_price  = Number($(row).find('.purchase_price').val() ?? 0);

            productsArr.push({
                order_no,
                product_id,
                product_name,
                product_color,
                product_size,
                product_qty,
                product_price,
                discount_price,
                purchase_price,
                subtotal
            });
        });

        return productsArr;
    }

</script>
@endpush
