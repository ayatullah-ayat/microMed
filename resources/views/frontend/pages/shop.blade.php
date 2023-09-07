@extends('frontend.layouts.master')
@section('title','Shop')
@section('content')
<!-- Product Shop Area-->
<div class="container bg-dark text-white pt-2 pb-2 shopFilter position-sticky">
    <button class="btn btn-danger" id="shopFilterBtn">Filter</button>
</div>
<section class="container-fluid product-shop-area my-5">
    <div class="container">
        <div class="row">

            <div class="col-md-3" id="shopFilterContent">
                <div class="shop-sidebar">

                    @includeIf('frontend.layouts.partials.shop_sidebar', [
                    'categories' =>$categories ?? null,
                    'productColor' =>$productColors ?? null,
                    'productSize' =>$productSize ?? null,
                    'maxSalesPrice' => $maxSalesPrice ? $maxSalesPrice->max_sale_price : 0
                    ])

                </div>
            </div>
            <div class="col-md-9">
                @isset($products)
                <div class="row gy-3 gx-3" data-insert="append" data-filter-insert="html">

                    @php
                    $maxId = 0;
                    // $limit = 20;
                    @endphp

                    @foreach ($products as $item)
                    @php
                    $maxId = $item->id;
                    @endphp

                    <div class="col-md-3 col-sm-4 col-6">
                        <div class="card __product-card">
                            <div class="card-wishlist {{ in_array($item->id,$wishLists) ? 'removeFromWish' : 'addToWish' }}" data-auth="{{ auth()->user()->id ?? null }}" data-productid="{{ $item->id }}" style="z-index: 100;" type="button"> <i class="fa-solid fa-heart"></i></div>
                            <a href="{{ route('product_detail',$item->id ) }}">
                                <img draggable="false" src="{{asset( $item->product_thumbnail_image )}}" class="img-fluid" alt="...">
                            </a>
                            <div class="card-body p-0">
                                <div class="card-product-title card-title text-center fw-bold mt-3">
                                    <a href="{{ route('product_detail',$item->id ) }}" class="text-decoration-none text-dark">
                                        <h5 data-toggle="tooltip" data-placement="bottom" title="{{ $item->product_name }}">{{ \Str::limit($item->product_name, 15) }}</h5>
                                    </a>
                                </div>

                                <div class="card-product-price card-text text-center fw-bold">
                                    <h5>Sale Price {{ salesPrice($item) ?? '0'}} /=
                                        @if($item->product_discount)
                                        <span class="text-decoration-line-through text-danger"> {{ round($item->unit_price,0) ?? '0'}} /=</span>
                                        @endif
                                    </h5>
                                </div>
                                <div class="card-product-button d-flex flex-column mt-3">
                                    @if($item->total_stock_qty > 0)
                                    <button type="button" 
                                        data-productid="{{ $item->id }}" 
                                        class="btn btn-sm btn-secondary btn-card {{ !in_array($item->id,$productIds) ? 'openCartModal' : 'alreadyInCart' }}"
                                        style="width: 80%; margin: auto; background-color: #515a5a !important">
                                        {!! !in_array($item->id,$productIds) ? '+ Cart' :'<span>+ Cart</span>' !!}
                                    </button>
                                    <button data-productid="{{ $item->id }}" 
                                            data-isordernow="1" 
                                            type="button" 
                                            class="btn btn-sm btn-danger openCartModal mt-2 mb-2"
                                            style="width: 80%; margin: auto; background-color: #cf273d !important;"> Order Now</button>
                                    @else
                                    <span class="text-danger">Out of Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>

                <div data-totalcount="{{ $countProducts }}" data-ajax-filter="" class="text-center loadMoreContainer {{ $countProducts <= $limit ? 'd-none' : '' }} pt-4">
                    {!! loadMoreButton( route('shop_index'),$maxId, $limit) !!}
                </div>

                @endisset
                <!-- Our Contact Area-->
                <section class="container-fluid call-center-area">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 d-flex align-items-center justify-content-center">
                                <div class="call-center text-center">
                                    <h2 class="text-uppercase">For Any Help You May Call Us At:<span> <a href="tel:01971819813" class="text-decoration-none" type="button">0197-1819-813</a></span></h2>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>
            </div>


        </div>
    </div>
</section>

<!-- Add to Cart Modal -->
<div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="row">
                    <div class="col-5">
                        <p class="product-cart-description text-capitalize">product description</p>
                        <img class="img-fluid product-cart-image" src="https://images.pexels.com/photos/341523/pexels-photo-341523.jpeg" alt="">
                    </div>
                    <div class="col-1"></div>
                    <div class="col-6">
                        <div class="single-prodect-color">
                            <!-- <div class="spacer"></div> -->
                            <h3 class="mt-0">Select Color</h3>
                            <div class="ms-2 row cart_color_container" style="margin-left: -0.5rem!important;">

                            </div>
                        </div>

                        <div class="single-prodect-size">
                            <h3 class="mt-0">Select Size</h3>
                            <div class="row" style="margin-left: -0.5rem!important;">
                                <div class="ms-2 row cart_size_container" style="margin-left: -0.5rem!important;">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer" style="justify-content: space-between; padding: 0;">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning btn-sm addToCart">Add to Cart</button>
            </div>
        </div>
    </div>
</div>


@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/common_assets/libs/jquery/css/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('assets/frontend/pages/css/shop.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/common_assets/libs/jquery/jquery-ui.min.js') }}"> </script>
<script>
    let timeId = null;
    $(function() {
        $(document).on("change", '.category_container input[name="category"]', filterBy)

        $(document).on("click", '.color_container .color', selectColor)
        $(document).on("click", '.size_container .size', selectSize)

        $('#addToCartModal').on('hide.bs.modal', function(e) {
            if (e.target === this) {
                $(this).addClass('fadeOutUp');
                setTimeout(() => {
                    $(this).modal('hide').removeClass('fadeOutUp');
                    $('.addToCart').removeAttr('data-ordernow');
                    $('.addToCart').removeAttr('data-cartproduct-id');
                }, 500);
            }
        });


        $(document).on("click", '.cart_color_container .color', selectCartColor)
        $(document).on("click", '.cart_size_container .size', selectCartSize)

        $(document).on('click', '.addToCart', addToCart);

        $(document).on("click", '.filterTagName', selectTag)

        $(document).on("click", '.parentCategory', toggleChildrenCategories)

        $(document).on("click", '.stateChange', incrementDecrementCount)

        $(document).ready(function() {
            // Get all the accordion items
            var accordionItems = $('.accordion .collapse');

            accordionItems.slice(1).collapse('hide');
            accordionItems.first().collapse('show');

            $(document).on('click', '#shopFilterBtn', showFilterContent);
        });

        // ============Range Slider ================== 

        $("#slider-range").slider({
            range: true,
            min: 0,
            max: $("#max-price").data('max'),
            step: 5,
            slide: function(event, ui) {
                $("#min-price").html(ui.values[0]);
                suffix = '';
                if (ui.values[1] == $("#max-price").data('max')) {
                    suffix = ' +';
                }
                // console.log(ui.values[1], suffix)
                $("#max-price").html(ui.values[1] + suffix);

                filterBy();
            }
        })

        // ============slider ================== 

    });

    function showFilterContent() {
        console.log('shopFilterBtn clicked');
        $('#shopFilterContent').toggle();
    }

    function selectColor() {
        let
            currentElem = $(this);
        currentElem.toggleClass('selected')

        filterBy();

        // updateSelectedStatus();
    }

    function selectCartColor() {
        let currentElem = $(this);

        $(document).find('.cart_color_container .color').removeClass('selected');
        currentElem.toggleClass('selected')
    }

    function selectCartSize() {
        let currentElem = $(this);

        $(document).find('.cart_size_container .size').removeClass('selected');

        currentElem.toggleClass('selected');
    }

    function updateSelectedStatus(product_id) {

        const cartItemsQty = @json($cartQtys ?? []);
        const color = $(document).find('.cart_color_container .color.selected').attr('data-color');
        const size = $(document).find('.cart_size_container .size.selected').attr('data-size');

        console.log('color', color, 'size', size);
        let obj = {
            product_id,
            qty: 1,
            color,
            size,
        };

        let cartQtys = cartItemsQty.filter(singleOne => (singleOne?.product_id != product_id));
        cartQtys.push(obj);

        updateCartQty(cartQtys);
        console.log('cart Updated', cartQtys);
    }

    function selectSize() {
        let
            currentElem = $(this);
        currentElem.toggleClass('selected');
        filterBy();
    }

    function selectTag() {
        let
            currentElem = $(this);
        currentElem.toggleClass('selected');
        filterBy();
    }


    function toggleChildrenCategories() {
        let
            elem = $(this),
            current = elem.attr('data-category'),
            target = $(`.childer-category[data-parent=${current}]`),
            icon = elem.find('.triggerIcon');
        target.toggle();

        if (icon.hasClass('fa-angle-down')) {
            icon.removeClass('fa-angle-down')
            icon.addClass('fa-angle-up')
        } else {
            icon.removeClass('fa-angle-up')
            icon.addClass('fa-angle-down')
        }
    }

    function addToCart(e) {

        // validation
        let selectedColorElem = $(document).find('.single-prodect-color .color.selected');
        let selectedSizeElem = $(document).find('.single-prodect-size .size.selected');

        if (selectedColorElem.length === 0) {
            alert('Please Select Color');
            return;
        } else if (selectedSizeElem.length === 0) {
            alert('Please Select Size');
            return;
        }

        let
            elem = $('.addToCart'),
            id = elem.attr('data-cartproduct-id'),
            cartBadge = $('.cartvalue'),
            selectedColor = selectedColorElem.attr('data-color'),
            selectedSize = selectedSizeElem.attr('data-size');

        console.log('productId', id);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            url: APP_URL + '/add-to-cart',
            data: {
                productId: id
            },
            dataType: 'html',
            cache: false,
            success: function(items) {
                console.log('cart added successfully!');
                let products = JSON.parse(items);
                if (!Array.isArray(products)) {
                    products = Object.entries(products);
                }
                cartBadge.html(products.length || 1);
                updateSelectedStatus(id);

                setTimeout(() => {
                    if ($('.addToCart').attr('data-ordernow')) {
                        window.location.href = "/cart"
                    } else {
                        window.location.href = "/shop";
                    }
                }, 500);

                $('#addToCartModal').modal('hide');

            },
            error: function(xhr, status, error) {
                console.log("An AJAX error occured: " + status + "\nError: " + error);
            }
        });


    }

    function incrementDecrementCount(e) {
        let
            countElem = $('#count'),
            count = Number(countElem.text() ?? 0),
            elem = $(this),
            ref = elem.attr('id'),
            pattern1 = /(plus|increment|increament)/im,
            pattern2 = /(minus|decrement|decreament)/im,
            minCount = Number(countElem?.attr('data-min') ?? 1),
            maxCount = Number(countElem?.attr('data-max') ?? 10);

        if (pattern1.test(ref)) {

            count++;
            if (count > maxCount) count = maxCount;

        } else if (pattern2.test(ref)) {

            count--;
            if (count < minCount) count = minCount;
        }

        countElem.text(count);
    }


    function filterBy() {

        let
            category_ids = [],
            colors = [],
            sizes = [],
            prices = null,
            tags = [],
            filterObj = {};

        clearTimeout(timeId)

        timeId = setTimeout(() => {
            let categories = $(document).find('input[name="category"]:checked');
            let colorElems = $(document).find('.color_container .color.selected');
            let sizeElems = $(document).find('.size_container .size.selected');
            let tagElems = $(document).find('.filterTagName.selected');
            let minPrice = $('#min-price').text();
            let maxPrice = $('#max-price').text();

            categories.map((i, cat) => {
                category_ids.push($(cat).val());
            })

            colorElems.map((i, color) => {
                colors.push($(color).attr('data-color'));
            })

            sizeElems.map((i, size) => {
                sizes.push($(size).attr('data-size'));
            })

            prices = {
                minPrice,
                maxPrice
            };

            tagElems.map((i, tag) => {
                tags.push($(tag).attr('data-tag'));
            })

            filterObj = {
                category_ids: category_ids,
                colors: colors,
                sizes: sizes,
                prices: prices,
                tags: tags
            };

            console.log(filterObj);
            shop_ajax_filter(filterObj);
        }, 500);
    }



    function shop_ajax_filter(filterObj) {

        let
            elemContainer = $('.loadMoreContainer'),
            elem = $('.loadMoreBtn'),
            max_id = elem.attr('data-maxid'),
            limit = elem.attr('data-limit'),
            method = 'POST',
            dataInsertElem = $(document).find('[data-filter-insert]');
        dataInsert = dataInsertElem.data('filter-insert');

        elem.attr('data-ajax-filter', '');

        ajaxFormToken();

        $.ajax({
            url: APP_URL + '/shop',
            type: method,
            data: filterObj,
            cache: false,
            success: function(res) {
                // console.log(res, res?.max_id);
                elem.attr('data-maxid', res?.max_id);

                elemContainer.attr('data-totalcount', Number(res?.totalCount));

                if (res?.html != null) {

                    elem.attr('data-ajax-filter', true);

                    if (res?.isLast || Number(res?.totalCount) <= Number(limit)) {
                        elemContainer.addClass('d-none');
                    } else {
                        elemContainer.removeClass('d-none');
                    }

                    // console.log(dataInsertElem, dataInsert);
                    if (dataInsertElem.length) {
                        dataInsertElem[dataInsert](res.html);
                    }

                    // data-ajax=true

                }
            },
            error: function(error) {
                console.log(error);
                elem.attr('data-ajax-filter', '');
            }
        });

    }
</script>
@endpush