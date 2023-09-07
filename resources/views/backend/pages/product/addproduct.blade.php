@extends('backend.layouts.master')

@section('title', 'Add Product')

@section('content')
<div class="card p-4 shadow">
    <div class="w-100">
        <h4 class="text-dark f-2x font-weight-bold d-inline-block">Add Product Information</h4>
        <a class="text-white btn btn-sm btn-info float-right" href="{{ route('admin.products.index') }}"><i class="fa fa-arrow-left"> Back</i></a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" id="productForm" enctype="multipart/form-data">
        {{-- @csrf  --}}
        <div class="row">
        
            <div class="col-md-6" data-col="col">
                <div class="form-group">
                    <label for="category"> Category<span style="color: red;" class="req">*</span></label>
                    <select required name="category_id" class="category" data-required id="category" data-placeholder="Select Category">
                        @if($categories)
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <span class="v-msg"></span>
            </div>
        
            <div class="col-md-6" data-col="col">
                <div class="form-group">
                    <label for="subcategory">Sub Category</label>
                    <select name="subcategory_id" class="subcategory" data-required id="subcategory"
                        data-placeholder="Select Sub Category"></select>
                </div>
                <span class="v-msg"></span>
            </div>
        
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Product Name<span style="color: red;" class="req">*</span></label>
                    <input required name="product_name" id="product_name" type="text" class="form-control" placeholder="Product Name">
                </div>
            </div>
        
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Product SKU<span style="color: red;" class="req">*</span></label> 
                    <input name="product_sku" id="product_sku" type="text" class="form-control" placeholder="Product SKU">
                </div>
            </div>

            <div class="col-md-6" data-col="col">
                <div class="form-group">
                    <label for="brand"> Brand </label>
                    <select name="brand" class="brand" data-required id="brand" data-placeholder="Select brand">
                        @if($brands)
                        @foreach ($brands as $item)
                        <option value="{{ $item->id }}">{{ $item->brand_name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <span class="v-msg"></span>
            </div>
            
            <div class="col-md-6" data-col="col">
                <div class="form-group">
                    <label for="currency">Currency <span style="color: red;" class="req">*</span></label>
                    {{-- <span class="float-right">
                        <label for="manageVariant" type="button">Manage Variant wise Price & Qty</label>
                        <input type="checkbox" name="manageVariant" id="manageVariant">
                    </span> --}}
                    <select name="currency" required class="currency" data-required id="currency" data-placeholder="Select currency">
                        @if($currencies)
                        @foreach ($currencies as $item)
                        <option value="{{ $item->currency_name }}">{{ $item->currency_name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <span class="v-msg"></span>
            </div>

        {{-- ----------------------------------------------------------------------------------------- --}}
            <div class="row p-0 mx-0 w-100" id="defaultPrice" data-product-variant="">
                <div class="col-md-6" data-col="col">
                    <div class="form-group">
                        <label for="color"> Color <span style="color: red;" class="req">*</span></label>
                        <select name="color_ids" multiple class="color" data-required id="color" data-placeholder="Select Color">
                            @if($colors)
                                @foreach ($colors as $item)
                                    <option value="{{ $item->variant_name }}">{{ $item->variant_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <span class="v-msg"></span>
                </div>
                
                <div class="col-md-6" data-col="col">
                    <div class="form-group">
                        <label for="size">Size <span style="color: red;" class="req">*</span></label>
                        <select name="size_ids" class="size" multiple data-required id="size" data-placeholder="Select Size">
                            @if($sizes)
                                @foreach ($sizes as $item)
                                    <option value="{{ $item->variant_name }}">{{ $item->variant_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <span class="v-msg"></span>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="purchase_price">Purchase Price <span style="color: red;" class="req">*</span></label>
                        <input name="purchase_price" id="purchase_price" required type="number" class="form-control calcPriceQty" placeholder="Purchase Price">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="unit_price">Unit Price <span style="color: red;" class="req">*</span></label>
                        <input name="unit_price" id="unit_price" type="number" class="form-control calcPriceQty" placeholder="Product Price">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="wholesale_price">Wholesale Price (Offline Sale)</label>
                        <input name="wholesale_price" id="wholesale_price" type="number" class="form-control calcPriceQty" placeholder="Product Wholesale Price">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="sale_price">Sales Price <span style="color: red;" class="req">*</span></label>
                        <input name="sale_price" readonly id="sale_price" type="number" class="form-control calcPriceQty"
                            placeholder="Sales Price">
                    </div>
                </div>
                
            </div>

        {{-- -----------------------------------------------------------------------------------------------  --}}
        
            <div class="col-md-3">
                <div class="form-group">
                    <label for="product_qty">Product Qty <span style="color: red;" class="req">*</span></label>
                    <input required name="product_qty" id="product_qty" min="1" type="number" class="calcPriceQty form-control" placeholder="Product Qty">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="discount">Discount(%)</label>
                    <input name="discount" id="discount" type="number" class="form-control calcPriceQty" placeholder="Discount">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="total_product_price">Total Unit Price</label>
                    <input readonly name="total_product_price" id="total_product_price" type="number" class="form-control" placeholder="Total Unit Price">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="total_sales_price">Total Sales Price</label>
                    <input readonly name="total_sales_price" id="total_sales_price" type="number" class="form-control" placeholder="Total Sales Price">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="total_wholesale_price">Total Wholesale Price</label>
                    <input readonly name="total_wholesale_price" id="total_wholesale_price" type="number" class="form-control" placeholder="Total Wholesale Price">
                </div>
            </div>

            <div class="col-md-3" data-col="col">
                <div class="form-group">
                    <label for="unit">Uom (Unit) <span style="color: red;" class="req">*</span></label>
                    <select name="product_unit" class="unit" data-required id="unit" required data-placeholder="Select Unit">
                        @if($units)
                        @foreach ($units as $item)
                        <option value="{{ $item->unit_name }}">{{ $item->unit_name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <span class="v-msg"></span>
            </div>
        
            <div class="col-md-12">
                <div class="form-group">
                    <label for="short_description">Short Description</label>
                    <textarea name="short_description" id="short_description" cols="" rows="5" class="form-control" placeholder="Product Short Description"></textarea>
                </div>
            </div>
        
            <div class="col-md-12">
                <div class="form-group">
                    <label for="description">Product Description <span style="color: red;" class="req">*</span></label>
                    <textarea name="description" id="description" required cols="" rows="5" class="form-control" placeholder="Product Description"></textarea>
                </div>
            </div>
        
            <div class="col-md-12">
                <div class="form-group">
                    <label for="specification">Product Specification</label>
                    <textarea name="specification" id="specification" cols="" rows="5" class="form-control" placeholder="Product Specification"></textarea>
                </div>
            </div>
        
            <div class="col-md-12 d-flex">
                <div class="form-group mr-4">
                    <input type="checkbox" name="is_best_sale" id="is_best_sale">
                    <label for="is_best_sale"> Best Sale </label>
                </div>
        
                <div class="form-group">
                    <input type="checkbox" checked name="allow_review" id="allow_review">
                    <label for="allow_review"> Allow Review </label>
                </div>
        
                <div class="form-group mx-4">
                    <input type="checkbox" checked name="is_active" id="is_active">
                    <label for="is_active"> Is Active </label>
                </div>
        
                <div class="form-group">
                    <input type="checkbox" checked name="is_publish" id="is_publish">
                    <label for="is_publish"> Is Publish </label>
                </div>
        
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="default_image">Thumbnail Image <span style="color: red;" class="req">*</span></label><br>
                    <input name="product_thumbnail_image" required type="file" id="default_image">
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    <label for="product_gallery">Gallery Image</label><br>
                    <input name="product_gallery" type="file" multiple id="product_gallery">
                </div>
            </div>
        
            <div class="col-md-12" data-col="col">
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <select name="tags" class="tags" multiple data-required id="tags" data-placeholder="Select Tags"></select>
                </div>
                <span class="v-msg"></span>
            </div>
            
            <div class="col-md-12">
                <div class="w-100">
                    <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                    <button type="submit" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i> <span>Submit</span></button>
                    <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        
        </div>
    </form>
</div>



{{-- -------------------- modal area ----------------------------------------------  --}}
<div class="modal fade" id="manageVariantSizePriceModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true"
    role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel1">Manage Product Variant & Stock Qty </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
            {{-- ---------------------------------------- variant wise qty price --------------------------------------- --}}
                <div class="row">
                    <div class="col-md-12" id="variantWisePrice">
                        <table class="table table-sm table-bordered w-100 text-center mx-auto">
                            <thead class="bg-danger">
                                <tr>
                                    <th class="text-white">Color</th>
                                    <th class="text-white">Size</th>
                                    <th class="text-white">Unit Price</th>
                                    <th class="text-white">Wholesale <br> Price</th>
                                    <th class="text-white">Qty</th>
                                    <th class="text-white">Total Unit <br> Price</th>
                                    <th class="text-white">Total Wholesale <br> Price</th>
                                    <th class="text-white">Total Sales <br> Price</th>
                                    <th class="align-middle">
                                        <button class="btn btn-sm btn-light text-dark d-inline-block" id="addVariantRow"><i class="fa fa-plus"></i></button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="single">
                                    <td>
                                        <select name="color_id" class="color" data-required data-placeholder="Select Color">
                                            @if($colors)
                                                @foreach ($colors as $item)
                                                <option value="{{ $item->variant_name }}">{{ $item->variant_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                        <select name="size_id" class="size" data-required data-placeholder="Select Size">
                                            @if($sizes)
                                                @foreach ($sizes as $item)
                                                    <option value="{{ $item->variant_name }}">{{ $item->variant_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="unit_price" class="form-control calcPriceQtyModal text-center">
                                    </td>
                                    <td>
                                        <input type="number" name="wholesale_price" class="form-control calcPriceQtyModal text-center">
                                    </td>
                                    <td>
                                        <input type="number" name="product_qty" class="form-control calcPriceQtyModal text-center">
                                    </td>
                                    <td>
                                        <input type="number" readonly name="total_unit_price" value="0" class="form-control text-center">
                                    </td>
                                    <td>
                                        <input type="number" readonly name="total_wholesale_price" value="0" class="form-control text-center">
                                    </td>
                                    <td>
                                        <input type="number" readonly name="total_sales_price" value="0" class="form-control text-center">
                                    </td>
                                    <td>
                                        <span class="fa fa-times text-danger fa-lg deleteVariantRow" type="button"></span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7" class="text-right"> Discount(%)</th>
                                    <th>
                                        <input type="number" name="discount_percentage" class="form-control text-center calcPriceQtyModal" max="100">
                                    </th>
                                    <th></th>
                                </tr>
                                <tr class="bg-dark text-white">
                                    <th colspan="4"></th>
                                    <th colspan="1" id="totalQtyModal">0</th>
                                    <th colspan="1" id="totalUnitPriceModal">0</th>
                                    <th colspan="1" id="totalWholesalePriceModal">0</th>
                                    <th colspan="1" id="totalSalesPriceModal">0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            {{-- ---------------------------------------- variant wise qty price --------------------------------------- --}}
            </div>

            <div class="modal-footer">
                <div class="w-100">
                    <button type="button" id="save-variant-price-qty" class="btn btn-sm btn-success float-right mx-1"><i class="fa fa-save"></i> Submit</button>
                    <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('css')
    <link href="{{ asset('assets/backend/css/currency/currency.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/libs/summernote/summernote.css')}}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('assets/backend/libs/summernote/summernote.js') }}"></script>
    <script>

    let uploadedFiles = [];
    let timeId = null;
    $(document).ready(function(){
        init();

        $(document).on('change','.category', getAllSubcategories)

        $(document).on('keyup change','.calcPriceQty', calcTotalPrice)

        $(document).on('change','#manageVariant', manageVariantPriceStock)


        $(document).on('click','#addVariantRow', createRow)
        $(document).on('click','.deleteVariantRow', deleteRow)


        // ----------------------------- modal --------------------- 
        $(document).on('click','#save-variant-price-qty', ()=>{

            let 
            data                = [],
            rows                = [...$('#variantWisePrice').find('tbody').find('tr')],
            totalUnitPrice      = 0,
            totalWholesalePrice = 0,
            totalSalesPrice     = 0,
            totalQty            = 0;

            rows.forEach(row => {

                let 
                color_name      = $(row).find('[name="color_id"]').val() ?? null,
                size_name       = $(row).find('[name="size_id"]').val() ?? null,
                unit_price      = $(row).find('[name="unit_price"]').val() ?? 0,
                wholesale_price = $(row).find('[name="wholesale_price"]').val() ?? 0,
                sales_price     = $(row).find('[name="total_sales_price"]').val() ?? 0,
                product_qty     = $(row).find('[name="product_qty"]').val() ?? 0,
                stock_qty       = $(row).find('[name="product_qty"]').val() ?? 0;

                data.push(
                    {
                        color_name,
                        size_name,
                        unit_price      : Number(unit_price),
                        sales_price     : (Number(sales_price) / Number(product_qty)).toFixed(3),
                        wholesale_price : Number(wholesale_price),
                        product_qty     : Number(product_qty),
                        stock_qty       : Number(stock_qty),
                    }
                )
                                    
                totalUnitPrice      += Number(unit_price ?? 0 ) * Number(product_qty ?? 0);
                totalWholesalePrice += Number(wholesale_price ?? 0 ) * Number(product_qty ?? 0);
                totalQty            += Number(product_qty) ?? 0;
            });
                    
            
            $('#total_product_price').val(totalUnitPrice.toFixed(3));
            $('#total_wholesale_price').val(totalWholesalePrice.toFixed(3));
            $('#product_qty').val( totalQty );
            $('#discount').val($('[name="discount_percentage"]').val() ?? 0);

            totalSalesPrice = Number($('#totalSalesPriceModal').text() ?? 0);
            $('#total_sales_price').val(totalSalesPrice.toFixed(3));

            collectionOfVariantPriceQty(data);

            hideModal('#manageVariantSizePriceModal');
        })

        $(document).on('keyup change','.calcPriceQtyModal', calcTotalPriceModal)

        $(document).on('submit','#productForm', submitToDatabase)
    });


    function calcTotalPrice(){

        let 
        initVal         = 0,
        elem            = $(this),
        currentVal      = elem.val(),
        manageVariant   = $('#manageVariant').is(":checked"),
        unitPrice       = $('#unit_price').val() ?? 0,
        wholesalePrice  = $('#wholesale_price').val() ?? 0,
        qty             = $('#product_qty').val() ?? 0,
        discount        = $('#discount').val() ?? 0;

        if(Number(currentVal) < 0){
            elem.val(initVal);
        }

        if(Number(discount) > 100){
            $('#discount').val(100)
            discount = 100;
        }

        let 
        totalUnitPriceModal         = $('#totalUnitPriceModal').text(),
        totalWholesalePriceModal    = $('#totalWholesalePriceModal').text(),
        totalQtyModal               = $('#totalQtyModal').text();

        let totalUnitPrice      = manageVariant ? Number(totalUnitPriceModal ?? 0) : Number(unitPrice) * Number(qty); 
        let totalSalesPrice     = manageVariant ? Number(totalUnitPriceModal ?? 0 ) : Number(unitPrice) * Number(qty); 
        let totalWholesalePrice = manageVariant ? Number(totalWholesalePriceModal ?? 0) : Number(wholesalePrice) * Number(qty); 

        if(discount){
            discount            = discount / 100;
            totalSalesPrice     = totalUnitPrice - (totalUnitPrice * discount);
            totalWholesalePrice = totalWholesalePrice - (totalWholesalePrice * discount);
        }

        $('#total_product_price').val(Math.round(totalUnitPrice))
        $('#total_wholesale_price').val(Math.round(totalWholesalePrice));
        $('#total_sales_price').val(Math.round(totalSalesPrice));
        $('#sale_price').val( Math.round(totalSalesPrice / Number(qty)) );

    }

    function calcTotalPriceModal(){

        let 
        initVal         = 0,
        elem            = $(this),
        currentVal      = elem.val(),
        data            = [],
        rows            = [...$('#variantWisePrice').find('tbody').find('tr')],
        discount        = $('[name="discount_percentage"]').val() ?? 0,
        totalUnitPrice  = 0,
        totalWholesalePrice = 0,
        totalSalesPrice = 0,
        totalQty = 0;

        if(Number(currentVal) < 0){
            elem.val(initVal);
        }

        if(Number(discount) > 100){
            $('[name="discount_percentage"]').val(100)
            discount = 100;
        }

        rows.forEach(row => {

            let 
            color_name      = $(row).find('[name="color_id"]').val() ?? null,
            size_name       = $(row).find('[name="size_id"]').val() ?? null,
            unit_price      = $(row).find('[name="unit_price"]').val() ?? 0,
            wholesale_price = $(row).find('[name="wholesale_price"]').val() ?? 0,
            product_qty     = $(row).find('[name="product_qty"]').val() ?? 0,
            stock_qty       = $(row).find('[name="product_qty"]').val() ?? 0;

            let 
            unitPriceSingle     = Number(unit_price ?? 0 ) * Number(product_qty ?? 0),
            salesPriceSingle    = Number(unit_price ?? 0 ) * Number(product_qty ?? 0),
            wholeSalePriceSingle= Number(wholesale_price ?? 0 ) * Number(product_qty ?? 0),
            productQtySingle    = Number(product_qty ?? 0);

            totalUnitPrice      += unitPriceSingle;
            totalWholesalePrice += wholeSalePriceSingle;
            totalQty            += productQtySingle;

            if(discount){
                discount            = discount / 100;
                salesPriceSingle    = unitPriceSingle - (unitPriceSingle * discount);
                wholeSalePriceSingle= wholeSalePriceSingle - (wholeSalePriceSingle * discount);
            }

            totalSalesPrice += salesPriceSingle;

            $(row).find('[name="total_unit_price"]').val(unitPriceSingle);
            $(row).find('[name="total_wholesale_price"]').val( wholeSalePriceSingle);
            $(row).find('[name="total_sales_price"]').val( salesPriceSingle );
        });


        $('#totalUnitPriceModal').text(totalUnitPrice.toFixed(3));
        $('#totalWholesalePriceModal').text(totalWholesalePrice.toFixed(3));
        $('#totalSalesPriceModal').text(totalSalesPrice.toFixed(3));
        $('#totalQtyModal').text(totalQty);

    }

    function getAllSubcategories(){
        let category_id = $(this).val();

        $.ajax({
            url     : `{{ route('admin.subcategory.subcategoriesByCategory','')}}/${category_id}`,
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

                $(document).find('#subcategory').html(options);
            },
            error(err){
                console.log(err);
            },
        })
    }
    
    function createRow(){

        let 
        elem    = $(this),
        tbody   = $('#variantWisePrice').find('tbody'),
        id      = new Date().getTime(),
        colors  = @json($colors),
        sizes   = @json($sizes),
        html    = `
        <tr class="single">
           <td>
                <select name="color_id" id="color_${id}" class="color" data-required data-placeholder="Select Color">
                    ${
                        colors.map((color,i) => (
                            `<option value="${color.variant_name}">${color.variant_name}</option>`
                        )).join('')
                    }
                </select>
            </td>
            <td>
                <select name="size_id" id="size_${id}" class="size" data-required data-placeholder="Select Size">
                    ${
                        sizes.map((size,i) => (
                        `<option value="${size.variant_name}">${size.variant_name}</option>`
                        )).join('')
                    }
                </select>
            </td>
            <td>
                <input type="number" name="unit_price" class="form-control calcPriceQtyModal text-center">
            </td>
            <td>
                <input type="number" name="wholesale_price" class="form-control calcPriceQtyModal text-center">
            </td>
            <td>
                <input type="number" min="1" name="product_qty" class="form-control calcPriceQtyModal text-center">
            </td>
            <td>
                <input type="number" readonly name="total_unit_price" value="0" class="form-control text-center">
            </td>
            <td>
                <input type="number" readonly name="total_wholesale_price" value="0" class="form-control text-center">
            </td>
            <td>
                <input type="number" readonly name="total_sales_price" value="0" class="form-control text-center">
            </td>
            <td>
                <span class="fa fa-times text-danger fa-lg deleteVariantRow" type="button"></span>
            </td>
        </tr>`;
        
        tbody.append(html);

        $(`#size_${id}`).select2({
            width : '100%' ,
            theme : 'bootstrap4',
            tags  : false,
            dropdownParent: $('#manageVariantSizePriceModal'),
        }).val(null).trigger('change')

        $(`#color_${id}`).select2({
            width : '100%' ,
            theme : 'bootstrap4',
            tags  : false,
            dropdownParent: $('#manageVariantSizePriceModal'),
        }).val(null).trigger('change')
    }

    function deleteRow(){
        $(this).closest('tr').remove();
        $('[name="discount_percentage"]').trigger('keyup')
    }

    function collectionOfVariantPriceQty(data=null){

        let targetElem = $('#defaultPrice');

        if(data){
            targetElem.attr('data-product-variant', JSON.stringify(data));
        }else{
            return targetElem?.attr('data-product-variant') ? JSON.parse(targetElem.attr('data-product-variant')) : null;
        }

    }

    function manageVariantPriceStock(){
        let elem = $(this);

        if(elem.is(":checked")){
            $('#defaultPrice').hide();
            showModal('#manageVariantSizePriceModal');
        }else{
            $('#defaultPrice').show();
            hideModal('#manageVariantSizePriceModal');
        }
    }

    function init(){

        let arr=[
            {
                selector        : `#subcategory`,
                type            : 'select',
            },
            {
                selector        : `.color`,
                type            : 'select'
            },
            {
                selector        : `.size`,
                type            : 'select',
            },
            {
                selector        : `#tags`,
                type            : 'select',
                tags            : true,
            },
            {
                selector        : `.category`,
                type            : 'select',
            },
            {
                selector        : `.brand`,
                type            : 'select'
            },
            {
                selector        : `.currency`,
                type            : 'select'
            },
            {
                selector        : `.unit`,
                type            : 'select'
            },
        ];

        globeInit(arr);

        $('#short_description').summernote()
        $('#description').summernote()

        $('#specification').summernote({
            callbacks: {
                onChange: function(contents, $editable) {
                    console.log('onChange:', contents, $editable);
                },
                onImageUpload: function(files) {
                // upload image to server and create imgNode...
                    // console.log(files);
                    uploadedFiles = [];
                    uploadedFiles.push(files);
                    // $('#specification').summernote('insertNode', imgNode);
                }
            }
        });

    }

    function createModal(){
        showModal('#categoryModal');
    }

    function submitToDatabase(e){

        //
        e.preventDefault();

        ajaxFormToken();

        clearTimeout(timeId)

        let
        thumbnail       = fileRead($('#default_image')),
        productGallery  = fileRead($('#product_gallery'));

        timeId = setTimeout(() => {

            $.ajax({
                url         : `{{ route('admin.products.store') }}`,
                method      : 'POST',
                dataType    : "json",
                cache       : false,
                async       : false,
                data        : { ...formatProductData(), product_thumbnail_image: JSON.stringify(thumbnail), product_gallery: JSON.stringify(productGallery) },
                beforeSend(){
                    console.log('Sending ...');
                },
                success(res){
                    console.log(res);
                    if(res?.success){
                        _toastMsg(res?.msg ?? 'Success!', 'success');

                        setTimeout(() => {
                            open(`{{ route('admin.products.index') }}`,'_self');
                        }, 2000);
                    }else{
                        _toastMsg(res?.msg ?? 'Error!');
                    }
                },
                error(err){
                    console.log(err);
                    _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!')
                },
            });

        }, 500);

        


        // hideModal('#categoryModal');
    }

    function formatProductData(){

        let
        variantPrices       = collectionOfVariantPriceQty(),
        is_product_variant  = Number($('#manageVariant').prop('checked')) && variantPrices ? 1 : 0;

        if(!is_product_variant){
            variantPrices = [];            
        }

        return {
            category_id             : $('.category').val(),
            subcategory_id          : $('#subcategory').val(),
            product_name            : $('#product_name').val(),
            product_sku             : $('#product_sku').val(),
            total_product_unit_price: $('#total_product_price').val() ?? 0,
            total_stock_price       : $('#total_sales_price').val() ?? 0,
            total_product_wholesale_price: $('#total_wholesale_price').val() ?? 0,
            purchase_price          : $('#purchase_price').val(),
            unit_price              : $('#unit_price').val(),
            sales_price             : $('#sale_price').val(),
            colors                  : $('#color').val(),
            sizes                   : $('#size').val(),
            product_qty             : $('#product_qty').val(), // total Product qty
            brand                   : $('.brand').val(),
            currency                : $('.currency').val(),
            product_unit            : $('.unit').val(),
            discount                : $('#discount').val(),
            description             : $('#description').val(),
            specification           : $('#specification').val(),
            short_description       : $('#short_description').val(),
            is_best_sale            : Number($('#is_best_sale').prop('checked')),
            allow_review            : Number($('#allow_review').prop('checked')),
            is_active               : Number($('#is_active').prop('checked')),
            is_publish              : Number($('#is_publish').prop('checked')),
            product_thumbnail_image : null,
            product_gallery         : [],
            tags                    : $('#tags').val(),
            variant_prices          : variantPrices,
            is_product_variant,
        };
    }

    function fileRead(elem, src = '#img-preview') {
        let files = [];

        if (elem[0]?.files && elem[0]?.files[0]) 
        {
            
            Array.from(elem[0].files)
            .forEach(file => { 
                let FR = new FileReader();
                FR.addEventListener("load", function (e) {
                    files.push(e.target.result);
                });
                
                // console.log(file);
                FR.readAsDataURL(file)
            });

            return files;
        }
    }

</script>
@endpush