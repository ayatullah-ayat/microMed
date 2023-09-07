@extends('backend.layouts.master')

@section('title', 'Add Purchase')

@section('content')
    <div>
        <div class="container-fluid card p-5 shadow">
            <div class="row justify-content-between">
                <h4 class="text-dark font-weight-bold">Add Purchase List</h4>
                <a href="{{ route('admin.purchase.index') }}" class="btn btn-info float-right">List</a>
            </div>
            <div class="row">
                {{-- @dd($purchase) --}}
                <div class="col-md-3" data-col="col">
                    <div class="form-group">
                        <label for="stuff"> Supplier<span style="color: red;" class="req">*</span></label>
                        <select name="supplier" class="supplier" data-required id="supplier" data-placeholder="Select Supplier">
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"  {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->supplier_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="v-msg"></span>
                </div>

                <div class="col-md-3" data-col="col">
                    <div class="form-group">
                        <label for="purchase_date">Purchase Date <span style="color: red;" class="req">*</span></label>
                        <input type="text" data-required autocomplete="off" class="form-control" id="purchase_date" name="purchase_date" value="{{ $purchase->purchase_date }}">
                    </div>
                    <span class="v-msg"></span>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Invoice No.<span style="color: red;" class="req">*</span></label>
                        <input type="text" readonly class="form-control check_invoice" placeholder="" id="invoice_no" value="{{ $purchase->invoice_no }}">
                        <span class="v-msg-invoice text-danger"></span>
                    </div>
                </div>

                <div class="col-md-3" data-col="col">
                    <div class="form-group">
                        <label for="stuff"> Currency<span style="color: red;" class="req">*</span></label>
                        <select name="currency" class="currency" data-required id="currency" data-placeholder="Select currency">
                            @foreach ($currencies as $currency)
                            <option value="{{ $currency->currency_name }}" {{ $purchase->currency == $currency->currency_name ? 'selected' : '' }}>{{ $currency->currency_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="v-msg"></span>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Details Or Note</label>
                        {{-- <input type="text" class="form-control" placeholder=""> --}}
                        <textarea class="form-control" name="" id="purchase_note" cols="0" rows="1"  placeholder="">{{ $purchase->purchase_note ?? '' }}</textarea>
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
                                    <th width="200">Unit</th>
                                    <th width="130" class="text-center">Qty</th>
                                    <th width="150" class="text-center">Purchase Price</th>
                                    <th width="150" class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody id="purchase-body">

                                @foreach ($purchase->purchaseProducts as $product)

                                    @php
                                        $product_colors = $product->product_colors ? explode(",",$product->product_colors) : [];
                                        $product_sizes = $product->product_sizes ? explode(",",$product->product_sizes) : [];
                                    @endphp

                                    <tr>
                                        <td>
                                            <select name="product_name" class="product_name" data-required data-placeholder="Search Or Type Product">
                                                <option value="{{ $product->product_name }}">{{ $product->product_name }}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select name="color" multiple class="color" data-required data-placeholder="Select Color">
                                                    @foreach ($colors as $color)
                                                    <option value="{{ $color->variant_name }}" {{ in_array($color->variant_name, $product_colors) ? 'selected' : '' }}>{{ $color->variant_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="v-msg"></span>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select name="size" class="size" data-required multiple data-placeholder="Select Sizes">
                                                    @foreach ($sizes as $size)
                                                    <option value="{{ $size->variant_name }}" {{ in_array($size->variant_name, $product_sizes) ? 'selected' : '' }}>{{ $size->variant_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="v-msg"></span>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select name="unit" class="unit" data-required data-placeholder="Select Unit">
                                                    @foreach ($units as $unit)
                                                    <option value="{{ $unit->unit_name }}" {{ $product->product_unit == $unit->unit_name ? 'selected' : '' }}>{{ $unit->unit_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="v-msg"></span>
                                        </td>
                                        <td >
                                            <input type="number" class="form-control qty state_change" value="{{ $product->product_qty ?? 0 }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control purchase-price state_change" value="{{ $product->product_price ?? 0 }}">
                                        </td>
                                        <td>
                                            <input type="number" readonly class="form-control subtotal" value="{{ $product->subtotal ?? 0 }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="">
                                    <th colspan="4"></th>
                                    <th colspan="2" id="total_qty" class="px-4">{{ $purchase->total_qty }}</th>
                                    <th colspan="2" id="grandtotal" class="px-4">{{ $purchase->total_price }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="col-md-12 d-flex justify-content-end">
                    <button class="btn btn-success text-right outline-none" id="purchase-btn"> <i class="fa fa-save"></i> Update</button>
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
        $(document).on('click','#add', createModal)
        $(document).on('click','#purchase-btn', submitToDatabase)
        $(document).on('input keyup change','.state_change', calcSubTotal)
        $(document).on('input change','.check_invoice', checkInvoice)
    });



    function checkInvoice(){
        let invoice = $(this).val().trim();

        ajaxFormToken();

        $.ajax({
            url: `{{ route('admin.purchase.checkInvoice') }}`,
            method: 'POST',
            data : { invoice },
            success(res){
                // console.log(res,'ss');
                if(!res.success) {
                    $('.v-msg-invoice').text(res?.msg);
                }else{
                    $('.v-msg-invoice').text('');
                }
            },
            error(err){
                console.log(err);
            },
        })
    }



    function calcSubTotal(){
        //purchase-price

        let 
        elem        = $(this),
        qtyElem     = elem.closest('tr').find('.qty'),
        qty         = qtyElem.val().trim(),
        priceElem   = elem.closest('tr').find('.purchase-price'),
        price       = priceElem.val().trim(),
        subtotalElem= elem.closest('tr').find('.subtotal');


        if(Number(qty) < 0){
            qtyElem.val(0);
            qty = 0;
        }

        if(Number(price) < 0){
            qtyElem.val(0);
            price = 0;
        }


        let subtotal = Number(qty) * Number(price);

        subtotalElem.val(subtotal)


        summary()


    }

    function summary(){
        let rows        = $('#purchase-body').find('tr');
        let total_qty   = 0;
        let grandtotal  = 0;

        [...rows].forEach( row => {
            total_qty += Number($(row).find('.qty').val() ?? 0);
            grandtotal += Number($(row).find('.subtotal').val() ?? 0);
        });


        $('#total_qty').text(total_qty);
        $('#grandtotal').text(grandtotal);
    }

    function init(){

        let arr=[
            {
                selector        : `#supplier`,
                type            : 'select',
                selectedVal     : @json($purchase->supplier_id ?? null)
            },
            {
                selector        : `.currency`,
                type            : 'select',
                selectedVal     : @json($purchase->currency ?? null)
            },
            {
                selector        : `#purchase_date`,
                type            : 'date',
                format          : 'yyyy-mm-dd',
            }];

            $('.product_name').select2({
                width : '100%' ,
                theme : 'bootstrap4',
            }).trigger('change')

            $('.unit').select2({
                width : '100%' ,
                theme : 'bootstrap4',
            }).trigger('change')

            $(`.color`).select2({
                width : '100%' ,
                theme : 'bootstrap4',
            }).trigger('change');

            $(`.size`).select2({
                width : '100%' ,
                theme : 'bootstrap4',
            }).trigger('change');


        globeInit(arr);


        // InitProduct();


        
    }


    function InitProduct(selector='#product_name'){
        $(selector).select2({
            width               : '100%',
            theme               : 'bootstrap4',
            minimumInputLength  : 2,
            tags                : true,
            // tags: [],
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
                processResults     : function (data) {
                    console.log(data);
                    return {
                        results: $.map(data, function (item) {

                            return {
                                text: item.product_name ?? 'N/A',
                                id: item.product_name
                            }
                        })
                    };
                }
            }
        });
    }


    function createModal(){
        showModal('#categoryModal');
    }

    function submitToDatabase(){
        //

        ajaxFormToken();

        let id = @json($purchase->id ?? null);

        let obj = {
            url     : `{{ route('admin.purchase.update','') }}/${id}`, 
            method  : "PUT",
            data    : formatData(),
        };

        $.ajax({
            ...obj,
             success(res){
                if(res?.success){
                    _toastMsg(res?.msg ?? 'Success!', 'success');
                    open(`{{ route('admin.purchase.index') }}`,'_self')
                }else{
                    _toastMsg(res?.msg ?? 'Something wents wrong!');
                }
            },
            error(err){
                _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!')
            },

        })

    }


    function formatData(){
        //
        return {
            supplier_id     : $('#supplier').val(),
            purchase_date   : $('#purchase_date').val(),
            invoice_no      : $('#invoice_no').val(),
            currency        : $('#currency').val(),
            purchase_note   : $('#purchase_note').val(),
            total_qty       : $('#total_qty').text(),
            total_price     : $('#grandtotal').text(),
            products        : products()
        };

    }


    function products(){
        let rows = $('#purchase-body').find('tr');
        let productArr = [];
        
        [...rows].forEach( row => {

            productArr.push({
                product_name    : $(row).find('.product_name').val(),
                invoice_no      : $('#invoice_no').val(),
                product_colors  : $(row).find('.color').val(),
                product_sizes   : $(row).find('.size').val(),
                product_qty     : Number($(row).find('.qty').val() ?? 0),
                product_price   : $(row).find('.purchase-price').val(),
                subtotal        : Number($(row).find('.subtotal').val() ?? 0),
                product_unit    : $(row).find('.unit').val(),
                product_id      : null,
                purchase_id     : null,
            });
        });


        return productArr;
    }

</script>
@endpush

