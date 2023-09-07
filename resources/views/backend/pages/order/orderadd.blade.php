@extends('backend.layouts.master')

@section('title', 'Add Order')

@section('content')
    <div>
        <div class="container-fluid card p-3 shadow">

            <div class=" py-3 d-flex justify-content-between align-items-center">
                <h4 class="text-dark font-weight-bold text-dark">Add Order Information</h4>
                <a class="text-white btn btn-sm btn-info float-right" href="{{ route('admin.ecom_orders.order_manage') }}"><i class="fa fa-arrow-left"> Back</i></a>
            </div>
        
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="order_no">Order NO</label>
                        <input type="text" id="order_no" class="form-control" placeholder="Order NO">
                    </div>
                </div>

                <div class="col-md-4" data-col="col">
                    <div class="form-group">
                        <label for="order_date">Order Date </label>
                        <input type="text" data-required autocomplete="off" class="form-control" id="order_date" name="order_date" placeholder="Order Date">
                    </div>
                    <span class="v-msg"></span>
                </div>

                <div class="col-md-4" data-col="col">
                    {{-- @dd($customers) --}}
                    <div class="form-group">
                        <label for="customer"> Customer </label>
                        <select name="customer" class="customer" data-required id="customer" data-placeholder="Select Customer">
                            @if ($customers)
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <span class="v-msg"></span>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="note">Note</label>
                        <textarea name="note" id="note" cols="0" rows="3" class="form-control note" placeholder="Note"></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="shipping_address">Shipment</label>
                        <textarea name="shipping_address" id="shipping_address" cols="0" rows="3" class="form-control shipment" placeholder="Shipment Address"></textarea>
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
                                    {{-- <th width="150" class="text-center">Discount Price</th> --}}
                                    <th width="100" class="text-center">Total</th>
                                    <th width="100" class="text-center">
                                        <button id="addNewRow" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="addorder-body">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <select name="product" class="product" data-required id="product" data-placeholder="Select Product">
                                                @if ($products)
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <span class="v-msg"></span>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="color" class="color" data-required id="color" data-placeholder="Select Color"></select>
                                        </div>
                                        <span class="v-msg"></span>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="size" class="size" data-required id="size" data-placeholder="Select Sizes"></select>
                                        </div>
                                        <span class="v-msg"></span>
                                    </td>

                                    <td >
                                        <input type="text" id="order_qty" class="form-control calculatePrice order_qty">
                                    </td>
                                    <td>
                                        <input type="text" id="product_price" class="form-control calculatePrice product_price">
                                        <input type="hidden" class="form-control purchase_price">
                                    </td>
                                    {{-- <td>
                                        <input readonly value="00.00" type="text" id="discount_price" class="form-control discount_price">
                                    </td> --}}
                                    <td>
                                        <input type="text" id="total_price" class="form-control total_price">
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-times text-danger fa-lg" type="button"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-12 d-flex justify-content-end">
                    <button id="save_order_btn" class="btn btn-success text-right outline-none"> <i class="fa fa-save"></i> Submit</button>
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
        $(document).on('click','#save_order_btn', submitToDatabase)
        $(document).on('input keyup change', ".calculatePrice", OrderPriceCalculation)
        $(document).on('change', ".product", getProductInfo)

        $(document).on('click','#addNewRow', createNewRow)
        $(document).on('click','.fa-times', removeRow)

    });


    function getProductInfo(){
        let 
        elem        = $(this),
        product_id  = elem.val(),
        row         = elem.closest('tr'),
        colorSelect = row.find('.color'),
        sizeSelect  = row.find('.size'),
        salesElem   = row.find('.product_price'),
        purchaseElem= row.find('.purchase_price');

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
                    purchaseElem.val(res.product.purchase_price).prop("readonly",true);
                }

                OrderPriceCalculation();
            },
            error(err){
                console.log(err);
            }

        })
    }



    
    function removeRow(){
        let 
        row = $(this).closest('tr'),
        rows= $('#addorder-body').find('tr');

        if(rows.length <= 1)
        {
            alert("You can't delete This Row ?")
            return false;
        }

        row.remove();
    }

    function createNewRow(){
        let ref = new Date().getTime();
        let html = `<tr> 
                        <td>
                            <div class="form-group">
                                <select name="product" class="product" data-required id="product_name_${ref}" data-placeholder="Select Product">
                                    @if ($products)
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <span class="v-msg"></span>
                        </td>

                        <td>
                            <div class="form-group">
                                <select name="color" class="color" data-required id="color_${ref}" data-placeholder="Select Color"></select>
                            </div>
                            <span class="v-msg"></span>
                        </td>

                        <td>
                            <div class="form-group">
                                <select name="size" class="size" data-required id="size_${ref}" data-placeholder="Select Sizes"></select>
                            </div>
                            <span class="v-msg"></span>
                        </td>

                        <td >
                            <input type="text" id="order_qty" class="form-control calculatePrice order_qty">
                        </td>
                        <td>
                            <input type="text" id="product_price" class="form-control calculatePrice product_price">
                            <input type="hidden" class="form-control purchase_price">
                        </td>
                        <td>
                            <input type="text" id="total_price" class="form-control total_price">
                        </td>
                        
                        <td class="text-center">
                            <i class="fa fa-times text-danger fa-lg" type="button"></i>
                        </td>
                        </tr>`;


        $('#addorder-body').append(html)

        $(`#product_name_${ref}`).select2({
            width : '100%',
            theme : 'bootstrap4',
            tags  : true,
        }).val(null).trigger('change')
        $(`#color_${ref}`).select2({
            width : '100%',
            theme : 'bootstrap4',
        }).val(null).trigger('change')
        $(`#size_${ref}`).select2({
            width : '100%',
            theme : 'bootstrap4',
        }).val(null).trigger('change')
    }

    function OrderPriceCalculation(){

            let rows        = $('#addorder-body').find('tr');
            let total_qty   = 0;
            let grandtotal  = 0;

            [...rows].forEach( row => {
                let qty = Number($(row).find('.order_qty').val() ?? 0);
                let price = Number($(row).find('.product_price').val() ?? 0);

                let total   = price * qty;
                $(row).find('.total_price').val(total);

            });
    }

    function init(){

        let arr=[
            {
                selector        : `#customer`,
                type            : 'select',
            },
            {
                selector        : `#product`,
                type            : 'select',
            },
            {
                selector        : `#color`,
                type            : 'select'
            },
            {
                selector        : `#size`,
                type            : 'select'
            },
            {
                selector        : `#order_date`,
                type            : 'date',
                format          : 'yyyy-mm-dd',
            },
        ];

        globeInit(arr);

    }

    function submitToDatabase(){
        ajaxFormToken();
        let obj = {
            url     : `{{ route('admin.ecom_orders.store')}}`, 
            method  : "POST",
            data    : formatData(),
        };
        ajaxRequest(obj, { reload: true, timer: 2000 })
    }

    function formatData(){
        return {
            order_no            : $('#order_no').val().trim(),
            order_date          : $('#order_date').val(),
            customer_id         : $('#customer').val(),
            order_note          : $('#note').val().trim(),
            shipping_address    : $('#shipping_address').val().trim(),
            products            : productsInfo()
        }
    }

    function productsInfo(){

        let rows        = $('#addorder-body').find('tr');
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
            let purchase_price  = Number($(row).find('.purchase_price').val() ?? 0);
            let subtotal        = Number($(row).find('.total_price').val() ?? 0);

            productsArr.push({
                order_no,
                product_id,
                product_name,
                product_color,
                product_size,
                product_qty,
                product_price,
                purchase_price,
                subtotal
            });
        });

        return productsArr;
    }

</script>
@endpush

