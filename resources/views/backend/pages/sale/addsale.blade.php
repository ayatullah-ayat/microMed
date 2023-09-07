@extends('backend.layouts.master')

@section('title', 'Add Sale')

@section('content')
    <div>
        <div class="container-fluid card p-3 shadow">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between">
                    <h4 style="color: #000;" class="text-dark font-weight-bold text-dark">Add Sale Information</h4>
                    <a class="btn btn-sm btn-outline-success" href="{{ route('admin.ecom_sales.manage_sale') }}">Sales List</a>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6" data-col="col">
                    <div class="form-group">
                        <label for="customer"> Customer<span style="color: red;" class="req">*</span></label>
                        <select name="customer" class="customer" data-required id="customer" data-placeholder="Select Customer">
                            @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->customer_name ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="v-msg"></span>
                </div>

                <div class="col-md-6" data-col="col">
                    <div class="form-group">
                        <label for="sale_date">Sale Date </label>
                        <input type="text" data-required autocomplete="off" class="form-control" id="sale_date" name="sale_date">
                    </div>
                    <span class="v-msg"></span>
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
                                    <th width="150" class="text-center">Total</th>
                                    <th width="100" class="text-center">
                                        <button class="btn btn-sm btn-info add_row"><i class="fa fa-plus"></i> Add</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="sale_tbody">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <select name="product" class="product" data-required id="product_name" data-placeholder="Select Product"></select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="color" class="color" data-required id="color" data-placeholder="Select Color"></select>
                                        </div>
                                        <span class="v-msg"></span>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="size" class="size" data-required id="size" data-placeholder="Select Size"></select>
                                        </div>
                                        <span class="v-msg"></span>
                                    </td>
                                    <td >
                                        <input type="number" class="form-control qty calculatePrice">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control sales_price calculatePrice">
                                        <input type="hidden" class="form-control purchase_price">
                                    </td>
                                    <td>
                                        <input type="number" readonly class="form-control subtotal text-right px-2">
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-times text-danger fa-lg deleteRow" type="button"></i>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3"></th>
                                    <th colspan="2">Subtotal</th>
                                    <th>
                                        <input type="number" readonly id="grand_sub_total" value="0" class="text-right">
                                    </th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="3"></th>
                                    <th colspan="2">Discount (Tk)</th>
                                    <th>
                                        <input type="number" id="discount" value="0" class="text-right">
                                    </th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="3"></th>
                                    <th colspan="2">Grandtotal</th>
                                    <th>
                                        <input type="number" readonly id="grand_total" value="0" class="text-right">
                                    </th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="3"></th>
                                    <th colspan="2">Payment Amount</th>
                                    <th>
                                        <input type="number" readonly id="total_payment" value="0" class="text-right">
                                    </th>
                                    <th></th>
                                </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="col-md-12 d-flex justify-content-end">
                    <button class="btn btn-success text-right outline-none" id="submitToSale"> <i class="fa fa-save"></i> Submit</button>
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

    let timeId = null;
    $(document).ready(function(){
        init();

        $(document).on('click','.add_row', createRow)
        $(document).on('click','.deleteRow', deleteRow)
        $(document).on('change','.product', getColorSizes)
        $(document).on('input keyup change', ".calculatePrice", priceCalculation)
        $(document).on('input keyup change', "#discount", priceCalculation)

        $(document).on('click','#submitToSale', submitToDatabase)
    });



    function priceCalculation(){

        let rows        = $('#sale_tbody').find('tr');
        let total_qty   = 0;
        let grandtotal  = 0;
        let discount    = Number($('#discount').val() ?? 0);

        [...rows].forEach( row => {
            let qty     = Number($(row).find('.qty').val() ?? 0);
            let price   = Number($(row).find('.sales_price').val() ?? 0);

            let total   = price * qty;
            $(row).find('.subtotal').val(total);

            total_qty  += qty;
            grandtotal += total;

        });

        // $('#grand_qty').text(total_qty);
        $('#grand_sub_total').val(grandtotal);
        $('#grand_total').val(grandtotal - discount);
    }



    function getColorSizes(){
        let 
        elem        = $(this),
        product_id  = elem.val(),
        row         = elem.closest('tr'),
        colorSelect = row.find('.color'),
        sizeSelect  = row.find('.size'),
        salesElem   = row.find('.sales_price'),
        purchaseElem= row.find('.purchase_price');

        $.ajax({
            url : `{{ route('admin.ecom_sales.getVariantsByProduct','') }}/${product_id}`,
            method: 'GET',
            success(res){
                console.log(res);

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

                priceCalculation();

                //product

            },
            error(err){
                console.log(err);
            }

        })
        console.log(product_id);
    }

    function deleteRow(){
        let 
        currentRow  = $(this).closest('tr'),
        rows        = $('#sale_tbody').find('tr');

        if(rows.length <= 1){
            alert("You can't delete Last Item?")
            return false;
        }

        currentRow.remove();
    }


    function createRow(){
        let id = new Date().getTime();
        let row = `
        <tr>
            <td>
                <div class="form-group">
                    <select name="product" class="product" data-required id="product_name_${id}" data-placeholder="Search Product"></select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <select name="color" class="color" data-required id="color_${id}" data-placeholder="Select Color"></select>
                </div>
                <span class="v-msg"></span>
            </td>
            <td>
                <div class="form-group">
                    <select name="size" class="size" data-required id="size_${id}" data-placeholder="Select Size"></select>
                </div>
                <span class="v-msg"></span>
            </td>
           <td>
                <input type="number" class="form-control qty calculatePrice">
            </td>
            <td>
                <input type="number" class="form-control sales_price calculatePrice">
                <input type="hidden" class="form-control purchase_price">
            </td>
            <td>
                <input type="number" readonly class="form-control subtotal text-right px-2">
            </td>
            <td class="text-center">
                <i class="fa fa-times text-danger fa-lg deleteRow" type="button"></i>
            </td>
        </tr>
        `;

        $('#sale_tbody').append(row);

        InitProduct(`#product_name_${id}`);

        $(`#color_${id}`).select2({
            width : '100%',
            theme : 'bootstrap4',
        }).val(null).trigger('change')
        $(`#size_${id}`).select2({
            width : '100%',
            theme : 'bootstrap4',
        }).val(null).trigger('change')
    }


    function init(){

        let arr=[
            {
                selector        : `#customer`,
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
                selector        : `#sale_date`,
                type            : 'date',
                format          : 'yyyy-mm-dd',
            },
        ];

        globeInit(arr);

        InitProduct();
    }


    function submitToDatabase(){
        //

        ajaxFormToken();

        clearTimeout(timeId)

        timeId = setTimeout(() => {
            let obj = {
                url     : `{{ route('admin.ecom_sales.store') }}`, 
                method  : "POST",
                data    : formatData(),
            };

            $.ajax({
                ...obj,
                success(res){
                    console.log(res);
                     if(res?.success){
                        _toastMsg(res?.msg ?? 'Success!', 'success');
                        resetData()
                    }else{
                        _toastMsg(res?.msg ?? 'Something wents wrong!');
                    }
                },
                error(err){
                    console.log(err);
                    _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!')
                },
            })
        }, 500);

    }


    function InitProduct(selector='#product_name'){
        $(selector).select2({
            width               : '100%',
            theme               : 'bootstrap4',
            minimumInputLength  : 2,
            ajax: {
                url         : `{{ route('admin.purchase.searchProduct')}}`,
                dataType    : 'json',
                type        : "GET",
                quietMillis : 50,
                data        : function (term) {
                    return {
                        term: term
                    };
                },
                processResults : function (data) {
                    console.log(data);
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.product_name ?? 'N/A',
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
    }


    function formatData(){
        return {
            sale_date           : $('#sale_date').val(),
            customer_id         : $('#customer').val(),
            customer_name       : $('#customer').find('option:selected').text(),
            order_subtotal      : $('#grand_sub_total').val().trim() ?? 0,
            discount_price      : $('#discount').val().trim() ?? 0,
            order_grand_total   : $('#grand_total').val().trim() ?? 0,
            total_payment       : $('#total_payment').val().trim() ?? 0,
            products            : productsInfo()
        }
    }

    function productsInfo(){

        let rows        = $('#sale_tbody').find('tr');
        let total_qty   = 0;
        let grandtotal  = 0;
        let productsArr =[];

        [...rows].forEach( row => {
            let product_id      = $(row).find('.product').val();
            let product_name    = $(row).find('.product').find('option:selected').text();
            let product_color   = $(row).find('.color').val();
            let product_size    = $(row).find('.size').val();
            let product_qty     = Number($(row).find('.qty').val() ?? 0);
            let sales_price     = Number($(row).find('.sales_price').val() ?? 0);
            let purchase_price  = Number($(row).find('.purchase_price').val() ?? 0);
            let subtotal        = Number($(row).find('.subtotal').val() ?? 0);

            if(product_id){
                productsArr.push({
                    product_id,
                    product_name,
                    product_color,
                    product_size,
                    product_qty,
                    sales_price,
                    purchase_price,
                    subtotal
                });
            }

        });

        return productsArr;
    }



    function resetData(){
        $('#sale_date').val('')
        $('#customer').val(null).trigger('change')
        $('#grand_sub_total').val(0)
        $('#discount').val(0)
        $('#grand_total').val(0)

        let rowDefault = `<tr>
            <td>
                <div class="form-group">
                    <select name="product" class="product" data-required id="product_name"
                        data-placeholder="Select Product"></select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <select name="color" class="color" data-required id="color" data-placeholder="Select Color"></select>
                </div>
                <span class="v-msg"></span>
            </td>
            <td>
                <div class="form-group">
                    <select name="size" class="size" data-required id="size" data-placeholder="Select Size"></select>
                </div>
                <span class="v-msg"></span>
            </td>
            <td>
                <input type="number" class="form-control qty calculatePrice">
            </td>
            <td>
                <input type="number" class="form-control sales_price calculatePrice">
                <input type="hidden" class="form-control purchase_price">
            </td>
            <td>
                <input type="number" readonly class="form-control subtotal text-right px-2">
            </td>
            <td class="text-center">
                <i class="fa fa-times text-danger fa-lg deleteRow" type="button"></i>
            </td>
        </tr>`;

        $('#sale_tbody').html(rowDefault);

        init();
    }

</script>
@endpush

