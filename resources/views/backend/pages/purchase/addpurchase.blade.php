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
                <div class="col-md-3" data-col="col">
                    <div class="form-group">
                        <label for="stuff"> Supplier<span style="color: red;" class="req">*</span></label>
                        <select name="supplier" class="supplier" data-required id="supplier" data-placeholder="Select Supplier">
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="v-msg"></span>
                </div>

                <div class="col-md-3" data-col="col">
                    <div class="form-group">
                        <label for="purchase_date">Purchase Date <span style="color: red;" class="req">*</span></label>
                        <input type="text" data-required autocomplete="off" class="form-control" id="purchase_date" name="purchase_date">
                    </div>
                    <span class="v-msg"></span>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Invoice No.<span style="color: red;" class="req">*</span></label>
                        <input type="text" class="form-control check_invoice" placeholder="" id="invoice_no">
                        <span class="v-msg-invoice text-danger"></span>
                    </div>
                </div>

                <div class="col-md-3" data-col="col">
                    <div class="form-group">
                        <label for="stuff"> Currency<span style="color: red;" class="req">*</span></label>
                        <select name="currency" class="currency" data-required id="currency" data-placeholder="Select currency">
                            @foreach ($currencies as $currency)
                            <option value="{{ $currency->currency_name }}">{{ $currency->currency_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="v-msg"></span>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Details Or Note</label>
                        {{-- <input type="text" class="form-control" placeholder=""> --}}
                        <textarea class="form-control" name="" id="purchase_note" cols="0" rows="1"  placeholder=""></textarea>
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
                                    <th width="100" class="text-center align-middle">
                                        <button class="btn btn-sm btn-info" id="addNewRow"><i class="fa fa-plus"></i></button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="purchase-body">
                                <tr>
                                    <td>
                                        <select name="product_name" class="product_name" data-required id="product_name" data-placeholder="Search Or Type Product"></select>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="color" multiple class="color" data-required id="color" data-placeholder="Select Color">
                                                @foreach ($colors as $color)
                                                <option value="{{ $color->variant_name }}">{{ $color->variant_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="v-msg"></span>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="size" class="size" data-required id="size" multiple data-placeholder="Select Sizes">
                                                @foreach ($sizes as $size)
                                                <option value="{{ $size->variant_name }}">{{ $size->variant_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="v-msg"></span>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="unit" class="unit" data-required id="unit" data-placeholder="Select Unit">
                                                @foreach ($units as $unit)
                                                <option value="{{ $unit->unit_name }}">{{ $unit->unit_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="v-msg"></span>
                                    </td>
                                    <td >
                                        <input type="number" class="form-control qty state_change">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control purchase-price state_change">
                                    </td>
                                    <td>
                                        <input type="number" readonly class="form-control subtotal">
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-times text-danger fa-lg" type="button"></i>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="">
                                    <th colspan="4"></th>
                                    <th colspan="2" id="total_qty" class="px-4">0</th>
                                    <th colspan="2" id="grandtotal" class="px-4">0</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="col-md-12 d-flex justify-content-end">
                    <button class="btn btn-success text-right outline-none" id="purchase-btn"> <i class="fa fa-save"></i> Submit</button>
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

        $(document).on('click','#addNewRow', createNewRow)
        $(document).on('click','.fa-times', removeRow)
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

    function removeRow(){
        let 
        row = $(this).closest('tr'),
        rows= $('#purchase-body').find('tr');

        if(rows.length <= 1)
        {
            alert("You can't delete This Row ?")
            return false;
        }

        row.remove();

        summary()
    }


    function createNewRow(){

        let ref = new Date().getTime();

        let html = `<tr>
            <td>
                <select name="product_name" class="product_name" data-required id="product_name_${ref}" data-placeholder="Search Or Type Product"></select>
            </td>
            <td>
                <div class="form-group">
                    <select name="color" multiple class="color" data-required id="color_${ref}" data-placeholder="Select Color">
                        @foreach ($colors as $color)
                        <option value="{{ $color->variant_name }}">{{ $color->variant_name }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="v-msg"></span>
            </td>
            <td>
                <div class="form-group">
                    <select name="size" class="size" data-required id="size_${ref}" multiple data-placeholder="Select Sizes">
                        @foreach ($sizes as $size)
                        <option value="{{ $size->variant_name }}">{{ $size->variant_name }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="v-msg"></span>
            </td>
            <td>
                <div class="form-group">
                    <select name="unit" class="unit" data-required id="unit_${ref}" data-placeholder="Select Unit">
                        @foreach ($units as $unit)
                        <option value="{{ $unit->unit_name }}">{{ $unit->unit_name }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="v-msg"></span>
            </td>
            <td>
                <input type="number" class="form-control qty state_change">
            </td>
            <td>
                <input type="number" class="form-control purchase-price state_change">
            </td>
            <td>
                <input type="number" readonly class="form-control subtotal">
            </td>
            <td class="text-center">
                <i class="fa fa-times text-danger fa-lg" type="button"></i>
            </td>
        </tr>`;


        $('#purchase-body').append(html)

        InitProduct(`#product_name_${ref}`);

        $(`#color_${ref}`).select2({
            width : '100%',
            theme : 'bootstrap4',
        }).val(null).trigger('change')

        $(`#size_${ref}`).select2({
            width : '100%',
            theme : 'bootstrap4',
        }).val(null).trigger('change')

        $(`#unit_${ref}`).select2({
            width : '100%',
            theme : 'bootstrap4',
        }).val(null).trigger('change')

    }


    function init(){

        let arr=[
            {
                selector        : `#supplier`,
                type            : 'select',
            },
            {
                selector        : `.color`,
                type            : 'select',
            },
            {
                selector        : `.size`,
                type            : 'select',
            },
            {
                selector        : `.currency`,
                type            : 'select',
            },
            {
                selector        : `.unit`,
                type            : 'select',
            },
            {
                selector        : `#purchase_date`,
                type            : 'date',
                format          : 'yyyy-mm-dd',
            },
        ];

        globeInit(arr);


        InitProduct();


        
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

        let obj = {
            url     : `{{ route('admin.purchase.store') }}`, 
            method  : "POST",
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

