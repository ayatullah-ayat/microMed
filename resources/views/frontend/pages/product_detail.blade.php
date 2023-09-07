@extends('frontend.layouts.master')
@section('title','Product Details')

@section('content')

<!-- Single Product Area-->
<section class="container-fluid single-product-area my-5">
    <div class="container m-container">
        <div class="row">
            <div class="col-md-6">
                <div class="single-prodect-info right">

                    <div class="exzoom" id="exzoom">
                        <!-- Images -->
                        <div class="exzoom_img_box">
                            <ul class='exzoom_img_ul'>
                                @if(isset($product->product_thumbnail_image))
                                <li><img src="{{ asset($product->product_thumbnail_image )}}" /></li>
                                @endif

                                @isset($product->productImages)
                                    @foreach ($product->productImages as $item)
                                        <li><img src="{{ asset($item->product_image )}}" /></li>
                                    @endforeach
                                @endisset

                            </ul>
                        </div>

                        <div class="exzoom_nav"></div>
                        <!-- Nav Buttons -->
                        <p class="exzoom_btn">
                            <a href="javascript:void(0);" class="exzoom_prev_btn"> <i class="fa fa-angles-left"></i>
                            </a>
                            <a href="javascript:void(0);" class="exzoom_next_btn"> <i class="fa fa-angles-right"></i>
                            </a>
                        </p>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="single-prodect-info">

                    <div class="single-prodect-title">
                        <h2>{{ $product->product_name }}</h2>
                    </div>

                    <div class="single-prodect-offer-price d-flex">
                        <h3> Sale Price- {{ salesPrice($product)  ?? '0' }} Tk </h3>
                        @if($product->product_discount)
                        <h5> {{ round($product->unit_price, 0) ?? '0' }} Tk </h5>
                        @endif
                    </div>

                    <div class="single-prodect-description">
                        <p>
                            @if(isset($product->short_description))
                            {!! Str::words($product->short_description, 200,'.......') !!}
                            @else
                        </p>
                        <p>
                            {!! Str::words($product->product_description, 150,'.......') !!}
                        </p>
                        @endif
                    </div>

                    @if (count($product->brands))
                        @php $brands = []; @endphp
                            @foreach ($product->brands as $brand)
                                @php
                                    $brands[]= $brand->pivot->brand_name;
                                @endphp
                        @endforeach

                        <div class="single-prodect-model">
                            <h3> Brand: {{ implode(',', $brands) ?? 'T Shirt Model No- AT-505'}} </h3>
                        </div>
                    @endif


                    @php
                    $stockQty = $product->total_stock_qty ?? 0;
                    $cartQty = 1;
                    $selectedColor = null;
                    $selectedSize = null;
                    if(isset($cartQtys) && count($cartQtys)){
                        foreach ($cartQtys as $key => $item) {
                            if(isset($item['product_id']) && $item['product_id'] == $product->id){
                                $cartQty = ($item['qty']);
                                $selectedColor = $item['color'] ?? null;
                                $selectedSize = $item['size'] ?? null;
                            }
                        }
                    }
                    @endphp


                    <div class="single-prodect-color">
                        <!-- <div class="spacer"></div> -->
                        <h3> Color </h3>

                        @isset( $product->productColors )
                        {{-- @dd($product->productColors) --}}
                        <div class="ms-2 row color_container" style="margin-left: -0.5rem!important;">
                            @foreach ($product->productColors as $indx => $item)
                            <div type="button" data-color="{{ $item->color_name  ?? ''}}" class="col-md-2 col-1 color {{ matchColor($item->color_name) ? ' black' : '' }} " style="background-color: {{ $item->color_name }}; {{matchColor($item->color_name) ? 'box-shadow: 0px 0px 2px #000;': '' }}"> <i class="fa-solid fa-check"></i></div>
                            @endforeach
                            <span class="text-danger d-none" id="selectColor"><small>Please Select Color</small></span>
                        </div>
                        @endisset
                    </div>

                    <div class="single-prodect-size">
                        <h3> Size </h3>
                        <div class="row" style="margin-left: -0.5rem!important;">
                            @isset( $product->productSizes )
                            <div class=" ms-2 row size_container" style="margin-left: -0.5rem!important;">
                                @foreach ($product->productSizes as $indx => $item)
                                <div type="button" data-size="{{ $item->size_name  ?? ''}}" class=" col-md-2 col-1 size"> <span>{{ $item->size_name  ?? ''}}</span></div>
                                @endforeach
                                <span class="text-danger d-none" id="selectSizeMessage"><small>Please Select Size</small></span>
                            </div>
                            @endisset

                        </div>

                    </div>

                    <div class="actions">

                        @if($stockQty > 1)
                        <div class="btn-group" role="group">
                            <button type="button" id="minus" class="stateChange btn btn-light"><span class="fa fa-minus"></span></button>
                            <button type="button" style="cursor:default;" data-productid="{{ $product->id }}" class="btn btn-light" id="count" data-min="1" data-max="{{ $stockQty >= 10 ? 10 : $stockQty}}">{{ $cartQty }}</button>
                            <button type="button" id="plus" class="stateChange btn btn-light"><span class="fa fa-plus"></span></button>
                        </div>
                        @else
                        <span class="text-danger fw-bold">Out of Stock</span>
                        @endif

                        @if($stockQty > 0)
                        <div><button type="button" data-detail="true" class="btn btn-dark {{ $stockQty > 0 ? 'addToCart' : '' }} {{ !in_array($product->id,$productIds) ? 'addToCart' : 'alreadyInCart' }}" data-productid="{{ $product->id }}" data-stockqty="{{$stockQty}}">{!! !in_array( $product->id, $productIds) ? 'Add To Cart' :'<span>Already In Cart</span>' !!}</button></div>
                        <div><button type="button" class="btn btn-danger checkoutToGo" data-stockqty="{{$stockQty}}" data-ordernow="1">Order Now</button></div>
                        <div data-auth="{{ auth()->user()->id ?? null }}" class="d-flex align-items-center text-danger {{ in_array($product->id,$wishLists) ? 'removeFromWish' : 'addToWish' }}" data-productid="{{ $product->id }}" data-stockqty="{{$stockQty}}" type="button"><span class="fa fa-heart fa-2x"></span></div>
                        @endif

                    </div>

                    <div class="single-prodect-category">
                        <h3 class="mb-2"> Categories</h3>
                        <div class="d-flex flex-wrap gap-1">
                            @isset($product->category)
                            <div class="ms-2 row category_container" style="margin-left: -0.5rem!important;">
                                <div><button data-size="{{ $product->category->category_name  ?? ''}}" type="button" class="btn rounded btn-light me-2"> {{ $product->category->category_name  ?? ''}} </button></div>
                            </div>
                            @endisset
                        </div>
                    </div>

                    <div class="single-prodect-category">
                        <h3 class="mb-2"> Tags </h3>
                        <div class="d-flex flex-wrap  gap-1">

                            @isset( $product->singleProductTags )
                            @foreach ($product->singleProductTags as $indx => $item)
                            <div type="button" data-tag="{{ $item->tag_name  ?? ''}}" class="btn rounded btn-light me-2">{{ $item->tag_name  ?? ''}}</div>
                            @endforeach
                            @endisset
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product-Tabs Area-->
<section class="container-fluid product-tabs">
    <div class="container m-container">
        <div class="row">
            <div class="col-md-12">

                <div class="product-tab-area">

                    <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                        <li class=" nav-item bg-light"> <a class="nav-link active" data-bs-toggle="tab" href="#des"> Description </a></li>
                        <li class="nav-item bg-light"> <a class="nav-link " data-bs-toggle="tab" href="#specification"> Specification </a></li>
                        <li class="nav-item bg-light"> <a class="nav-link " data-bs-toggle="tab" href="#reviews"> Review </a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="des">
                            <div class="row border g-0 shadow-sm " style="overflow-x: auto !important">
                                <div class="col-md-12 p-5 tabs-product-comments">

                                    <p>
                                        {!! $product->product_description !!}
                                    </p>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="specification">
                            <div class="row border g-0 shadow-sm" style="overflow-x: auto !important">
                                <div class="col p-5 tabs-product-comments">
                                    <p>
                                        {!! $product->product_specification !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="reviews">
                            <div class="border g-0 shadow-sm">

                                {{-- @dd($product->comments()->paginate(5)) --}}

                                <div class="review-container">

                                    @if($product && $product->comments)

                                    @php
                                    $is_approved = 0;
                                    $maxId = 0;
                                    $limit = 10;
                                    $commentData = $product->comments()->latest()->where('is_approved', 1)->take($limit)->get();
                                    $countReviews= $product->comments()->where('is_approved', 1)->count();
                                    @endphp

                                    @foreach ($commentData as $comment)
                                    @php
                                    $maxId = $comment->id;
                                    @endphp

                                    @if($comment->commentedBy && boolval($comment->is_approved))

                                    @php
                                    $is_approved = boolval($comment->is_approved);
                                    @endphp

                                    <div class="col tabs-product-comments d-flex">

                                        <div class="reviw-person">
                                            @isset($comment->commentedBy->profile)
                                            <img src="{{asset($comment->commentedBy->profile->photo ?? 'assets/frontend/img/comment/comment.png')}}" alt="reviw person">
                                            @else
                                            <img src="{{asset('assets/frontend/img/comment/comment.png')}}" alt="reviw person">
                                            @endisset
                                        </div>

                                        <div class="comment-text">
                                            <h3> {{ $comment->commentedBy->username ?? $comment->commentedBy->name}}</h3>
                                            <ul class="tabs-product-review mb-2 list-unstyled d-flex">
                                                @for ($start=0; $start < 5; $start++) <li><i class="fa-{{$start<$comment->ratting ? 'solid':'regular'}} fa-star"></i></li>
                                                    @endfor
                                            </ul>

                                            <p>{{ $comment->body ?? 'N/A'}}</p>
                                        </div>

                                    </div>
                                    @endif
                                    @endforeach

                                    <div data-totalcount="{{ $countReviews }}" class="col-md-12 tabs-product-comments loadMoreContainer {{ $countReviews <= $limit || !$is_approved ? 'd-none' : '' }} pt-0">
                                        {{-- {{ $commentData->links() }} --}}
                                        {!! loadMoreButton( route('show_review', $product->id),$maxId, $limit) !!}

                                    </div>
                                    @endif
                                </div>

                                @guest
                                <div class="col-md-12 tabs-product-comments my-0 mb-5" style="margin-left: 3.6%">
                                    <a href="{{ route('login') }}" class="btn btn-danger btn-sm">Review & comment</a>
                                </div>
                                @endguest

                                @auth
                                <div class="col tabs-product-comments d-flex">

                                    <div class="reviw-person">
                                        @isset(auth()->user()->profile)
                                        <img src="{{asset(auth()->user()->profile->photo ?? 'assets/frontend/img/comment/comment.png')}}" style="border: 1px solid rgb(138, 136, 136)" alt="Person">
                                        @else
                                        <img src="{{asset('assets/frontend/img/comment/comment.png')}}" alt="Person">
                                        @endisset
                                    </div>

                                    <div class="comment-text w-100">
                                        <h3> {{ auth()->user()->name ?? 'N/A' }} </h3>
                                        <ul class="tabs-product-review make mb-2 list-unstyled d-flex">
                                            <li><i class="fa-regular fa-star star" data-index="0"></i></li>
                                            <li><i class="fa-regular fa-star star" data-index="1"></i></li>
                                            <li><i class="fa-regular fa-star star" data-index="2"></i></li>
                                            <li><i class="fa-regular fa-star star" data-index="3"></i></li>
                                            <li><i class="fa-regular fa-star star" data-index="4"></i></li>
                                        </ul>

                                        <div class="comment-area w-100 mb-4">
                                            <textarea style="resize: none;" class="w-75"></textarea>
                                            <button type="button" id="review-btn" class="d-block btn btn-sm btn-danger my-2"> Submit </button>
                                        </div>

                                    </div>
                                </div>
                                @endauth

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Related Product Area-->
@includeIf('frontend.layouts.partials.other_product', ['products' => $otherProducts ?? null ])

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

@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/frontend/libs/slick-carousel/slick-theme.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/frontend/libs/slick-carousel/slick.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/frontend/libs/exzoom/jquery.exzoom.css') }}">
<link rel="stylesheet" href="{{ asset('assets/frontend/pages/css/shop.css') }}">
<link rel="stylesheet" href="{{ asset('assets/frontend/pages/css/product_detail.css') }}">
<style>
    .tabs-product-comments table {
        width: 100% !important;
    }
</style>
@endpush

@push('js')
<script src="{{ asset('assets/frontend/libs/slick-carousel/slick.min.js') }}"></script>
<script src="{{ asset('assets/frontend/libs/exzoom/jquery.exzoom.js') }}"></script>
<script>
    var ratedIndex = -1;

    $(function() {

        updateSelectedStatus();

        resetStarsColor();

        if (localStorage.getItem('ratedIndex') != null) {
            setStars(parseInt(localStorage.getItem('ratedIndex')));
        }



        $('#review-btn').on('click', function() {
            ratedIndex = parseInt(localStorage.getItem('ratedIndex'));
            let comment = $('.comment-area textarea').val();
            let product_id = @json($product -> id ?? null)

            saveToDataBase({
                ratting: ratedIndex,
                comment,
                product_id
            });

        });


        $('.star').on('click', function() {
            ratedIndex = parseInt($(this).data('index'));
            localStorage.setItem('ratedIndex', ++ratedIndex);
            setIndexItem(ratedIndex);
        });


        $('.star').mouseover(function() {
            resetStarsColor();
            var currentIndex = parseInt($(this).data('index'));
            setStars(++currentIndex);

        });

        $('.star').mouseleave(function() {
            resetStarsColor();
            if (ratedIndex != -1) {
                setStars(ratedIndex);
            }
        });

        $(document).on("click", '.color_container .color', selectColor)
        $(document).on("click", '.size_container .size', selectSize)
        $(document).on("click", '.stateChange', incrementDecrementCount)
        $(document).on("click", '.checkoutToGo', addToCart)
        $(document).on('click', '.addToCart', addToCart);

        $("#exzoom").exzoom({
            // thumbnail nav options
            "navWidth": 60,
            "navHeight": 60,
            "navItemNum": 5,
            "navItemMargin": 7,
            "navBorder": 1,
            // autoplay
            "autoPlay": false,
            // autoplay interval in milliseconds
            // "autoPlayTimeout": 2000
        });


        // ============slider ================== 
        $('.product-slider .slider').slick({
            speed: 500,
            slidesToShow: 5,
            slidesToScroll: 5,
            // centerMode: true,
            responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    arrows: false,
                }
            }, {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }, {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }]
        });
        // ============slider ================== 


    });

    function addToCart(e) {

        let selectedColorElem = $(document).find('.single-prodect-color .color.selected');
        let selectedSizeElem = $(document).find('.single-prodect-size .size.selected');

        if(selectedSizeElem.length === 0 && selectedColorElem.length === 0)
        {
            $('#selectColor').removeClass('d-none');
            $('#selectSizeMessage').removeClass('d-none');
            return;
        }
        else if (selectedColorElem.length === 0) {
            $('#selectColor').removeClass('d-none');
            return;
        } else if (selectedSizeElem.length === 0) {
            $('#selectSizeMessage').removeClass('d-none');
            return;
        }

        let elem = $(this);

        let id = @json($product -> id ?? null),
            cartBadge = $('.cartvalue');

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

                setTimeout(() => {
                    if (elem.attr('data-ordernow')) {
                        window.location.href = "/checkout"
                    }
                }, 500);
                cartBadge.html(products.length || 1);
                updateSelectedStatus(id);

            },
            error: function(xhr, status, error) {
                console.log("An AJAX error occured: " + status + "\nError: " + error);
            }
        });


        }


    function setIndexItem(index) {
        console.log(index);
    }

    function setStars(max) {
        for (var i = 0; i < max; i++) {
            $('.star:eq(' + i + ')').addClass('fa-solid');
        }
    }


    function resetStarsColor() {
        $('.star').removeClass('fa-solid')
        $('.star').addClass('fa-regular')
    }


    function saveToDataBase(obj = {}) {

        ajaxFormToken();

        $.ajax({
            url: `{{ route('create_review', '')}}/${obj?.product_id}`,
            type: "POST",
            data: obj,
            cache: false,
            success: function(res) {
                //    console.log(res.data );

                if (res?.success) {
                    localStorage.setItem('ratedIndex', null);
                    $('.comment-area textarea').val('');
                    resetStarsColor()
                    renderReview(res.data)
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function renderReview(data) {


        // console.log(data);
        // return false;

        let boxContainer = $('.review-container');
        let html = ``;
        if (data) {
            let profileSrc = `${data?.commented_by?.profile?.photo ?? 'assets/frontend/img/comment/comment.png'}`;
            html = ` <div class="col tabs-product-comments d-flex">
                                                
                        <div class="reviw-person">
                            ${
                                data?.commented_by?.profile ?  `<img src="${APP_URL}/${profileSrc}" alt="reviw person">` : (
                                    `<img src="${APP_URL}/assets/frontend/img/comment/comment.png" alt="reviw person">`
                                )
                            
                            }
                        </div>

                        <div class="comment-text">
                            <h3> ${data?.commented_by?.username ?? data?.commented_by?.name} </h3>
                            <ul class="tabs-product-review mb-2 list-unstyled d-flex">
                                ${
                                    renderRattings(data?.ratting)
                                }
                            </ul>

                            <p>${ data?.body ?? 'N/A'}</p>
                        </div>

                    </div>`;
        }

        boxContainer.prepend(html);
    }


    function renderRattings(ratting) {
        let rattingContent = ``;
        for (let index = 0; index < 5; index++) {
            rattingContent += `<li><i class="fa-${ index < ratting ? 'solid':'regular'} fa-star"></i></li>`;
        }

        return rattingContent;
    }


    // function makeReview(){
    //     open(`${window.location.origin}/login`,'_self');
    // }


    function selectColor() {
        let
            currentElem = $(this);

        $(document).find('.color_container .color').removeClass('selected')
        currentElem.addClass('selected')

        updateSelectedStatus();
    }

    function selectSize() {
        let
            currentElem = $(this);

        $(document).find('.size_container .size').removeClass('selected')
        currentElem.addClass('selected');

        updateSelectedStatus();
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

        updateSelectedStatus();

    }


    function updateSelectedStatus() {

        const cartItemsQty = @json($cartQtys ?? []);
        const color = $(document).find('.color_container .color.selected').attr('data-color');
        const size = $(document).find('.size_container .size.selected').attr('data-size');
        const product_id = @json($product -> id ?? null);

        let obj = {
            product_id,
            qty: Number($('#count').text() ?? 0),
            color,
            size,
        };

        let cartQtys = cartItemsQty.filter(singleOne => (singleOne?.product_id != product_id));
        cartQtys.push(obj);

        updateCartQty(cartQtys);
    }


    function checkoutPage(e) {
        e.preventDefault();

        const color = $(document).find('.color_container .color.selected').attr('data-color');
        const size = $(document).find('.size_container .size.selected').attr('data-size');

        let href = $(this).attr('href');
        open(`${href}?ref={{ uniqid() }}&q=${$('#count').text()??''}&c=${color ?? ''}&s=${size??''}`, '_self')
    }
</script>
@endpush