@extends('frontend.layouts.master')
@section('title','Search Result')

@section('content')
<!-- Product Search Area-->
<section class="container-fluid product-shop-area my-4">

    <div class="container">

        <div class="search-area">

            <div class="row">

                <div class="col-md-6 d-flex align-items-center">

                    <div class="search-title">
                        <p> {{ $totalItems ?? 0 }} টি রেজাল্ট পাওয়া গেছে আপনার “{{ $query ?? '' }}” শব্দ থেকে </p>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="solt-by">

                        <p class="me-3"> সোর্ট বাই </p>

                        <select class="form-select sortby" data-key="{{ request('key') }}">
                            <option selected value="newest"> নতুন আইটেপ </option>
                            {{-- <option value="popular"> মোস্ট পপুলার </option> --}}
                            <option value="max_price"> সর্বোচ্চ রেট </option>
                        </select>

                    </div>

                </div>

            </div>

        </div>

        <h1 class="search-heading"> ই-কমার্স শপ </h1>

        <div class="parent-container" >
            @if(isset($products) && count($products))
                <div class="row shopping-card-row ecommerce-container">
            
                    @foreach ($products as $item)
                    <div class="mb-3">
                        <div class="card __product-card">
                            <div class="card-wishlist {{ in_array($item->id,$wishLists) ? 'removeFromWish' : 'addToWish' }}"
                                data-auth="{{ auth()->user()->id ?? null }}" data-productid="{{ $item->id }}" style="z-index: 100;"
                                type="button"> <i class="fa-solid fa-heart"></i></div>
                            <a href="{{ route('product_detail',$item->id ) }}">
                                <img draggable="false" src="{{asset( $item->product_thumbnail_image )}}" class="card-img-top" alt="...">
                            </a>
                            <div class="card-body p-0">
                                <div class="card-product-title card-title text-center fw-bold">
                                    <a href="{{ route('product_detail',$item->id ) }}" class="text-decoration-none text-dark">
                                        <h5>{{ $item->product_name }}</h5>
                                    </a>
                                </div>
                        
                                @if ( $item->total_product_unit_price && $item->total_product_qty )
                                @php
                                $totalprice = $item->total_product_unit_price;
                                $totalqty = $item->total_product_qty;
                                $unitprice = $totalprice / $totalqty ?? 0.0;
                                @endphp
                                @endif
                        
                        
                                <div class="card-product-price card-text text-center fw-bold">
                                    <h5>বর্তমান মূুল্য {{ salesPrice($item) ?? '0.0'}} /=
                                        @if($item->product_discount)
                                        <span class="text-decoration-line-through text-danger"> {{ $unitprice ?? '0.0'}} /=</span>
                                        @endif
                                    </h5>
                                </div>
                                <div class="card-product-button d-flex justify-content-evenly">
                                    @if($item->total_stock_qty > 0)
                                    <button type="button" data-productid="{{ $item->id }}"
                                        class="btn btn-sm btn-secondary btn-card {{ !in_array($item->id,$productIds) ? 'addToCart' : 'alreadyInCart' }}">
                                        {!! !in_array($item->id,$productIds) ? 'কার্ডে যুক্ত করুন' :'<span>অলরেডি যুক্ত আছে</span>'
                                        !!}</button>
                                    <a href="{{ route('checkout_index',$item->id ) }}" type="button" class="btn btn-sm btn-danger"> অর্ডার
                                        করুন
                                    </a>
                                    @else
                                    <span class="text-danger">Out of Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                
                </div>
            @else 
                @if(count($customProducts) < 1)
                    <div class="alert alert-danger px-5">
                        <div class="inner">
                            <h5>দুঃখিত!</h5>
                            <h6>কোন রেজাল্ট পাওয়া যায়নি</h6>
                        </div>
                    </div>
                @endif 
            @endif
        </div>


        @if(isset($customProducts) && count($customProducts))
        <h1 class="search-heading"> কাস্টমাইজ সপ </h1>

        <div class="row shopping-card-row customize-container">

            @foreach ($customProducts as $item)
                @if($item->product_thumbnail)
                <div class="card card-box customize-product-box">
                    <a href="{{ route('customize.customorder_show', $item->id) }}">
                        <div class="modal-card text-center">
                            <img src="{{ asset($item->product_thumbnail) }}" class="pt-3" alt="Product Image">
                            <p> {{ $item->product_name ?? 'N/A' }} </p>
                        </div>
                    </a>
                </div>

                @endif 
            @endforeach

        </div>
        
        @endif 

    </div>

</section>

<!-- Product Shop Area Ends-->


<!-- Our Contact Area-->
<section class="container-fluid call-center-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex align-items-center justify-content-center">
                <div class="call-center text-center">
                    <h2> আপনি যা খুঁজছিলেন তা খুঁজে পাননি? কল করুন:<span> <a href="tel:01971819813" class="text-decoration-none" type="button">০১৯৭-১৮১৯-৮১৩</a></span></h2>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/frontend/pages/css/search_result.css') }}">
@endpush

@push('js')

<script>
    $(document).ready(function(){
        $(document).on("change",'.sortby', sortBy)
    })


    function sortBy(){
        let elem = $(this),
        order_by = elem.val(),
        key      = elem.data('key'),
        ecommerce= $('.ecommerce-container');

        $.ajax({
            url     : APP_URL + '/search',
            type    : "GET",
            data    : { order_by, key },
            cache   : false,
            success : function (res) {
                ecommerce.html(res?.productHTML)
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
</script>

@endpush