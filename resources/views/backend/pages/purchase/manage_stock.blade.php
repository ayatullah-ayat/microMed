@extends('backend.layouts.master')

@section('title', 'Manage Purchase')

@section('content')
<div>
    <div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><a href="/" class="text-decoration-none">Manage Stock</a> </h6>
            </div>

            <div class="card-body">
                <div class="row">
                    {{-- <div class="form-group col-md-3">
                        <label for="barcode">Product Code/Scan</label>
                        <input id="barcode" type="text" name="barcode" class="form-control" placeholder="Enter barcode">
                    </div> --}}

                    <div class="form-group col-md-4">
                        <label for="productName">Product Name</label>
                        <select name="productName" id="productName" data-placeholder="Select or Search Product"></select>
                    </div>

                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button class="btn btn-sm btn-success py-2" id="add_product"><i class="fa fa-plus"></i> Add</button>
                    </div>
                </div>
            </div>

        </div>

        <div class="card shadow">
            <div class="card-header">
                <h5 class="text-dark">Products</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Purchase Price</th>
                            <th>Sizes</th>
                            <th>Colors</th>
                            <th>Total</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="table_body"></tbody>
                </table>
            </div>
        </div>
    
    </div>
</div>


<div class="modal fade" id="manageStockModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true"
    role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel1">Stock Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
            <form action="{{ route('admin.products.store') }}" method="POST" id="productForm" enctype="multipart/form-data">

                <div class="row">
                    <input type="hidden" name="purchase_product_id" id="purchase_product_id">
                
                    <div class="col-md-6" data-col="col">
                        <div class="form-group">
                            <label for="category"> Category<span style="color: red;" class="req">*</span></label>
                            <select required name="category_id" class="category" data-required id="category" data-placeholder="Select Category">
                                @if(isset($categories) && $categories)
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
                            <label for="product_name">Product Name<span style="color: red;" class="req">*</span></label>
                            <input required readonly name="product_name" id="product_name" type="text" class="form-control" placeholder="Product Name">
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
                                @if(isset($brands) && $brands)
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
                            <select name="currency" required class="currency" data-required id="currency" data-placeholder="Select currency">
                                @if(isset($currencies) && $currencies)
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
                                    @if(isset($colors) && $colors)
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
                                    @if( isset($sizes) && $sizes)
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
                                <label for="purchase_price">Purchase Price <span style="color: red;" class="req">*</span><span class="wrapper">(<span id="old_purchase_price">0</span>)</span></label>
                                <input name="purchase_price" readonly id="purchase_price" type="number" class="form-control calcPriceQty" placeholder="Product Price">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="unit_price">Unit Price <span style="color: red;" class="req">*</span> <span class="wrapper">(<span id="old_unit_price">0</span>)</span></label>
                                <input name="unit_price" id="unit_price" type="number" class="form-control calcPriceQty" placeholder="Product Price">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="wholesale_price">Wholesale Price <span class="wrapper">(<span id="old_wholesale_price">0</span>)</span></label>
                                <input name="wholesale_price" id="wholesale_price" type="number" class="form-control calcPriceQty" placeholder="Product Wholesale Price">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sale_price">Sales Price <span class="wrapper">(<span id="old_sales_price">0</span>)</span> <span style="color: red;" class="req">*</span></label>
                                <input name="sale_price" readonly id="sale_price" type="number" class="form-control calcPriceQty"
                                    placeholder="Sales Price">
                            </div>
                        </div>
                        
                    </div>

                {{-- -----------------------------------------------------------------------------------------------  --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product_qty">Product Qty <span style="color: red;" class="req">*</span> <span class="wrapper">(<span id="old_qty">0</span>)</span> </label>
                            <input required readonly name="product_qty" id="product_qty" min="1" type="number" class="calcPriceQty form-control" placeholder="Product Qty">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="discount">Discount(%) <span class="wrapper">(<span id="old_discount">0</span>)</span></label>
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
                                @if(isset($units) && $units)
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
                            <textarea name="short_description" id="short_description" cols="" rows="5" class="form-control"
                                placeholder="Product Short Description"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Product Description <span style="color: red;" class="req">*</span></label>
                            <textarea name="description" id="description" cols="" rows="5" class="form-control" placeholder="Product Description"></textarea>
                        </div>
                    </div>
                
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="specification">Product Specification</label>
                            <textarea name="specification" id="specification" cols="" rows="5" class="form-control"
                                placeholder="Product Specification"></textarea>
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
                
                </div>
            {{-- </form> --}}
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <button type="button" id="reset" class="btn btn-sm btn-secondary"><i class="fa fa-sync"></i> Reset</button>
                    <button type="submit" class="save_btn btn btn-sm btn-success float-right"><i class="fa fa-save"></i><span>Submit</span></button>
                    <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-dismiss="modal">Cancel</button>
                </div>

            </div>
            </form>

        </div>
    </div>
</div>

@endsection

@push('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/backend/libs/summernote/summernote.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/css/currency/currency.css')}}" rel="stylesheet">
@endpush

@push('js')
    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/backend/libs/summernote/summernote.js') }}"></script>

    <script>

        let timeId = null;
        $(document).ready(function(){
            $(document).on("click","#add_product", getProduct)
            $(document).on("click",".manageStock", manageStockModal)
            $(document).on("click",".fa-trash", function(){
                $(this).closest('tr').remove();
            })

            InitProduct();

            init();



        $(document).on('change','.category', getAllSubcategories)

        $(document).on('keyup change','.calcPriceQty', calcTotalPrice)

        $(document).on('submit','#productForm', submitToDatabase)

        })




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

            // $('#total_product_price').val(totalUnitPrice.toFixed(3));
            // $('#total_wholesale_price').val(totalWholesalePrice.toFixed(3));
            // $('#total_sales_price').val(totalSalesPrice.toFixed(3));
            // $('#sale_price').val( (totalSalesPrice / Number(qty)).toFixed(3) );


            $('#total_product_price').val(Math.round(totalUnitPrice))
            $('#total_wholesale_price').val(Math.round(totalWholesalePrice));
            $('#total_sales_price').val(Math.round(totalSalesPrice));
            $('#sale_price').val( Math.round(totalSalesPrice / Number(qty)) );

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
    


        function getProduct(){
            let 
            barcode     = null,
            // barcode     = $('#barcode').val().trim() ?? null,
            product_name= $('#productName').val(),
            tbody       = $('#table_body');

            $.ajax({
                url     : `{{ route('admin.purchase.getProduct') }}`,
                method  : 'GET',
                data    : { barcode, product_name},
                success(data){
                    console.log(data);
                    if(data && data?.product_name){

                        let checkRow = $(document).find(`tr[data-id="${data.id}"]`).length;
                        if(!checkRow){
                            tbody.append(`
                            <tr data-id="${data.id}" data-purchase-product='${JSON.stringify(data)}'>
                                <td>${data.product_name ?? 'N/A'}</td>
                                <td class="text-center">${data.product_qty ?? 0}</td>
                                <td class="text-center">${data.product_price ?? 0}</td>
                                <td>${data.product_sizes ?? 'N/A'}</td>
                                <td>${data.product_colors ?? 'N/A'}</td>
                                <td>${data.subtotal ?? 0}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-success manageStock" type="button">Manage Stock</a>
                                    <button class="btn btn-sm btn-danger del"><span class="fa fa-trash text-white"></span></button>
                                </td>
                            </tr>
                            `);
                        }
                    }
                },
                error(err){
                    console.log(err);
                }
            })

        }




        function InitProduct(selector='#productName'){
            $(selector).select2({
                width               : '100%',
                theme               : 'bootstrap4',
                minimumInputLength  : 2,
                ajax: {
                    url         : `{{ route('admin.purchase.searchPurchaseProduct')}}`,
                    dataType    : 'json',
                    type        : "GET",
                    quietMillis : 50,
                    data        : function (term) {
                        return {
                            term: term
                        };
                    },
                    processResults     : function (data) {
                        // console.log(data);
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


        function manageStockModal(){

            let 
            purchaseProduct = $(this).closest('tr')?.attr('data-purchase-product');
            data            = purchaseProduct ? JSON.parse(purchaseProduct) : null;

            $('.form-group label .wrapper').hide();

            resetForm();

            if(data){

                console.log(data?.product,'product');

                $('#purchase_id').val(data.purchase_id ?? '');
                $('#purchase_product_id').val(data.id ?? '');

                $('#product_name').val(data.product_name ?? '');
                $('#purchase_price').val(data.product_price ?? 0);
                $('#product_qty').val(data.product_qty ?? 0);
                $('#unit').val(data.product_unit).trigger('change');

                let colors= null;
                let sizes = null;

                if(data.product_colors){
                    colors = data.product_colors.split(",");
                    $('#color').val(colors).trigger('change')
                }

                if(data.product_sizes){
                    sizes = data.product_sizes.split(",");
                    $('#size').val(sizes).trigger('change')
                }

                if(data.purchase?.currency){
                    $('#currency').val(data.purchase?.currency).trigger('change')
                }


                let form = $('#productForm');
                if(data?.product){

                    let product = data?.product;

                    form.attr('data-productid', product?.id);

                    $('#product_sku').val(product.product_sku).prop('readonly',true);
                    $('#short_description').val(product.short_description);
                    $('#description').val(product.product_description);
                    $('#specification').val(product.product_specification);

                    $('.category').val(product.category_id).trigger('change')
                    $('#is_active').prop("checked", Boolean(product.is_active));
                    $('#is_best_sale').prop("checked", Boolean(product.is_best_sale));
                    $('#is_publish').prop("checked", Boolean(product.is_publish));
                    $('#allow_review').prop("checked", Boolean(product.allowed_review));
                    
                    setTimeout(function(){
                        $('.subcategory').val(product.subcategory_id).trigger('change')
                    },2000)

                    let colrs = Array.isArray(colors) ? colors : [];
                    if(product.product_colors && product.product_colors?.length){
                        product.product_colors.forEach(colorObj => {
                            if(!colrs.includes(colorObj?.color_name)){
                                colrs.push(colorObj?.color_name);
                            }
                        });
                    }

                    $('#color').val(colrs).trigger('change')


                    let sizeArr = Array.isArray(sizes) ? sizes : [];
                    if(product.product_sizes && product.product_sizes?.length){
                        product.product_sizes.forEach(sizeObj => {
                            if(!sizeArr.includes(sizeObj?.size_name)){
                                sizeArr.push(sizeObj?.size_name);
                            }
                        });
                    }

                    $('#size').val(sizeArr).trigger('change')

                    let brands = Array.isArray(product?.brands) ? product?.brands : [];
                    if(brands.length){
                        let options = $('#brand').find('option');
                        [...options].forEach(opt => {

                            if($(opt).text() == brands[0]['brand_name']){
                               $(opt).prop('selected', true).trigger('change')
                            }
                        });
                    }

                    let tags = Array.isArray(product?.single_product_tags) ? product.single_product_tags : [];
                    let tagArr = [];
                    if(tags.length){
                        tags.forEach(tag => {
                            if(!tagArr.includes(tag.tag_name)){
                                tagArr.push(tag.tag_name);
                            }
                        })
                    }

                    $('#tags').select2({
                        width : '100%' ,
                        theme : 'bootstrap4',
                        tags : tagArr,
                    }).val(tagArr).trigger('change')

                    $('#old_qty').text(product?.total_stock_qty);
                    $('#product_qty').val(Number(data.product_qty)+Number(product?.total_stock_qty));

                    $('#old_unit_price').text(product?.unit_price);
                    $('#old_sales_price').text(product?.sales_price);
                    $('#old_wholesale_price').text(Number(product?.total_product_wholesale_price / product?.total_product_qty).toFixed(2));
                    $('#old_purchase_price').text(product.product_price ?? 0);
                    $('#old_discount').text(`${product.product_discount}`);

                    $('.form-group label .wrapper').show();

                    $('#default_image').removeAttr('required');

                }else{
                    form.removeAttr('data-productid');
                    $('#product_sku').val('').prop('readonly',false);
                    $('#default_image').prop('required',true);
                }
            }

            $('#manageStockModal').modal('show')
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

            $('#description').summernote()
            $('#short_description').summernote()
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





    function submitToDatabase(e){

        //
        e.preventDefault();

        ajaxFormToken();

        clearTimeout(timeId)

        let
        action              = $(this).attr('action'),
        productId           = $(this)?.attr('data-productid'),
        action2             = productId ? `{{ route('admin.products.update','') }}/${productId}` : action,
        thumbnail           = fileRead($('#default_image')),
        productGallery      = fileRead($('#product_gallery')),
        purchase_product_id = $('#purchase_product_id').val();

        timeId = setTimeout(() => {

            $.ajax({
                url         : action2,
                method      : productId ? 'PUT' :'POST',
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
                            $(document).find(`tr[data-id=${purchase_product_id}]`).find('.del').remove();

                            $(document).find(`tr[data-id=${purchase_product_id}]`)
                            .find('.manageStock')
                            .html(`<i class="fa fa-circle-check"></i> Done`)
                            .removeClass('manageStock');

                            $('#manageStockModal').modal('hide')
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
            short_description       : $('#short_description').val(),
            specification           : $('#specification').val(),
            is_best_sale            : Number($('#is_best_sale').prop('checked')),
            allow_review            : Number($('#allow_review').prop('checked')),
            is_active               : Number($('#is_active').prop('checked')),
            is_publish              : Number($('#is_publish').prop('checked')),
            product_thumbnail_image : null,
            product_gallery         : [],
            tags                    : $('#tags').val(),
            variant_prices          : [],
            is_product_variant      : 0,
            purchase_product_id     : $('#purchase_product_id').val(),
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



    function resetForm(){

        $('.category').val(null).trigger('change')
        $('#subcategory').val(null).trigger('change')
        $('#product_name').val('')
        $('#product_sku').val('').prop('readonly',false)
        $('#total_product_price').val('')
        $('#total_sales_price').val('') 
        $('#total_wholesale_price').val('') 
        $('#unit_price').val('')
        $('#sale_price').val('')
        $('#color').val(null).trigger('change')
        $('#size').val(null).trigger('change')
        $('#product_qty').val(0)
        $('.brand').val(null).trigger('change')
        $('.currency').val(null).trigger('change')
        $('.unit').val(null).trigger('change')
        $('#discount').val('')
        $('#description').val('')
        $('#specification').val('')
        $('#short_description').val('')
        $('#is_best_sale').prop('checked')
        $('#allow_review').prop('checked')
        $('#is_active').prop('checked')
        $('#is_publish').prop('checked')
        $('#tags').val(null).trigger('change')
        $('#default_image').val(null)
    }

    </script>
@endpush