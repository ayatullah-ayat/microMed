@extends('frontend.layouts.master')
@section('title','Dashboard')

@section('content')
<!-- User Dashboard Area-->
<section class="user-dashboard-area mt-3 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="sidebar">

                    <div class="sidebar__info">
                        <p class="p-1"> Customer Information </p>

                        <div class="d-flex align-items-center py-2">

                            <div class="sidebar__info--thumbnail mx-3">
                                {!! profilePhoto(auth()->user()->profile ? auth()->user()->profile->photo : null,['class' => 'img-fluid', 'width' => '50px', 'draggable' => 'false'] ) !!}
                            </div>

                            <div class="sidebar__info--content">
                                <h3>{{ auth()->user()->name ?? 'N/A' }}</h3>
                            </div>
                        </div>

                    </div>

                    <div class="user-dashboard-menu">
                        <div class="">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true"> Dashboard </button>
                                <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false"> Profile </button>
                                {{-- <button class="nav-link" id="v-pills-purchaseditems-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-purchaseditems" type="button" role="tab"
                                        aria-controls="v-pills-purchaseditems" aria-selected="false"> Buy Item </button> --}}
                                <button class="nav-link" id="v-pills-orderlist-tab" data-bs-toggle="pill" data-bs-target="#v-pills-orderlist" type="button" role="tab" aria-controls="v-pills-orderlist" aria-selected="false">Order List </button>
                                <button class="nav-link" id="v-pills-wishlist-tab" data-bs-toggle="pill" data-bs-target="#v-pills-wishlist" type="button" role="tab" aria-controls="v-pills-wishlist" aria-selected="false">Wish List </button>
                                <button class="nav-link" id="v-pills-editprofile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-editprofile" type="button" role="tab" aria-controls="v-pills-editprofile" aria-selected="false"> Update Profile </button>
                                <button class="nav-link" id="v-pills-resetpass-tab" data-bs-toggle="pill" data-bs-target="#v-pills-resetpass" type="button" role="tab" aria-controls="v-pills-resetpass" aria-selected="false"> Reset Password </button>
                                <button class="nav-link" onclick="javascript: document.getElementById('logoutForm').submit()" data-bs-toggle="pill" type="button">Logout </button>
                                <form action="{{ route('logout') }}" method="POST" id="logoutForm"> @csrf </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="mainbar">

                    <div class="user-profile-details">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <div class="account-info">
                                    <h2 class="mb-3"> Dashboard </h2>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="box">
                                                <h2 class="title">Total Orders </h2>
                                                <h4 class="title" style="border: none !important; font-size: 24px">{{ count($orders) ?? 0 }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="box">
                                                <h2 class="title"> Pending Orders </h2>
                                                <h4 class="title" style="border: none !important; font-size: 24px">{{ $pendingOrders ?? 0 }}</h4>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="box">
                                                <h2 class="title"> Completed Orders</h2>
                                                <h4 class="title" style="border: none !important; font-size: 24px">{{ $completedOrders ?? 0 }}</h4>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <div class="account-info">
                                    <h2 class="mb-3"> Profile </h2>
                                    <div class="row justify-content-between">

                                        <div class="col-md-4 order-md-2">
                                            <div class="user-profile-picture">
                                                {!! profilePhoto(auth()->user()->profile ? auth()->user()->profile->photo : null ) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-8 order-md-1">
                                            <div class="user-profile-info">
                                                @php
                                                $authUser = auth()->user();
                                                $gender = null;
                                                $hasProfile = false;
                                                if($authUser->profile){
                                                $hasProfile = true;
                                                $gender = $authUser->profile->gender == 1 ? 'পুরুষ' : ($authUser->profile->gender == 2 ? 'মহিলা' : 'অন্যান্য');
                                                }

                                                @endphp
                                                {{-- @dd($authUser) --}}
                                                <ul>
                                                    <li><span class="text-danger fw-bold"> Name: </span> {{ $authUser->name ?? 'N/A' }} </li>
                                                    <li><span class="text-danger fw-bold"> User Name: </span> {{ $authUser->username ?? 'N/A' }}</li>
                                                    <li><span class="text-danger fw-bold"> Email: </span> {{ $authUser->email ?? 'N/A' }} </li>
                                                    <li><span class="text-danger fw-bold"> Mobile: </span> {{ $hasProfile ? $authUser->profile->mobile_no : 'N/A' }}</li>
                                                    <li><span class="text-danger fw-bold"> Gender </span>{{ $gender ?? 'N/A' }} </li>
                                                    <li><span class="text-danger fw-bold"> Address </span> {{ $hasProfile ? $authUser->profile->address : 'N/A' }} </li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="v-pills-purchaseditems" role="tabpanel" aria-labelledby="v-pills-purchaseditems-tab">
                                <div class="account-info">
                                    <h2 class="mb-3"> Buy Item </h2>
                                    <div class="data-table">
                                        <table id="user-purchaseditems" class="table table-striped w-100">
                                            <thead class="bg-danger">
                                                <tr class="text-white">
                                                    <th> Product Name </th>
                                                    <th> Order </th>
                                                    <th>Total Order</th>
                                                    <th> Amount </th>
                                                    <th> Time </th>
                                                    <th> Price </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="order-product align-middle">
                                                        <div class="inner-wrap">
                                                            <img src="{{asset('assets/frontend/img/product/product-1.png')}}" alt="">
                                                            <p>Garrett consecte vitae suscipit optio. </p>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">System Architect</td>
                                                    <td class="align-middle">Edinburgh</td>
                                                    <td class="align-middle">61</td>
                                                    <td class="align-middle">2011/04/25</td>
                                                    <td class="align-middle">$320,800</td>
                                                </tr>
                                                <tr>
                                                    <td class="order-product">
                                                        <div class="inner-wrap">
                                                            <img src="{{asset('assets/frontend/img/product/product-2.png')}}" alt="">
                                                            <p>Garrett Winters System Architect weqrqwrwq </p>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">Accountant</td>
                                                    <td class="align-middle">Edinburgh</td>
                                                    <td class="align-middle">61</td>
                                                    <td class="align-middle">2011/04/25</td>
                                                    <td class="align-middle">$320,800</td>
                                                </tr>
                                                <tr>
                                                    <td class="order-product">
                                                        <div class="inner-wrap">
                                                            <img src="{{asset('assets/frontend/img/product/product-3.png')}}" alt="">
                                                            <p>Garrett Winters System Architect weqrqwrwq </p>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">Accountant</td>
                                                    <td class="align-middle">Edinburgh</td>
                                                    <td class="align-middle">61</td>
                                                    <td class="align-middle">2011/04/25</td>
                                                    <td class="align-middle">$320,800</td>
                                                </tr>
                                                <tr>
                                                    <td class="order-product">
                                                        <div class="inner-wrap">
                                                            <img src="{{asset('assets/frontend/img/product/product-4.png')}}" alt="">
                                                            <p>Garrett Winters System Architect weqrqwrwq </p>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">Accountant</td>
                                                    <td class="align-middle">Edinburgh</td>
                                                    <td class="align-middle">61</td>
                                                    <td class="align-middle">2011/04/25</td>
                                                    <td class="align-middle">$320,800</td>
                                                </tr>
                                                <tr>
                                                    <td class="order-product">
                                                        <div class="inner-wrap">
                                                            <img src="{{asset('assets/frontend/img/product/product-5.png')}}" alt="">
                                                            <p>Garrett Winters System Architect weqrqwrwq </p>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">Accountant</td>
                                                    <td class="align-middle">Edinburgh</td>
                                                    <td class="align-middle">61</td>
                                                    <td class="align-middle">2011/04/25</td>
                                                    <td class="align-middle">$320,800</td>
                                                </tr>

                                            </tbody>
                                            <tfoot>
                                                <tr class="text-center">
                                                    <th> Product Name </th>
                                                    <th> Order </th>
                                                    <th>Total Order</th>
                                                    <th> Amount </th>
                                                    <th> Time </th>
                                                    <th> Price </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="v-pills-orderlist" role="tabpanel" aria-labelledby="v-pills-orderlist-tab">
                                <div class="account-info">
                                    <h2 class="mb-3"> Order List </h2>
                                    <div class="data-table">
                                        <table id="user-product-oderlist" class="table table-striped w-100">
                                            <thead class="bg-danger fixed-header-top">
                                                <tr class="text-white">
                                                    <th> Order Date</th>
                                                    <th> Order </th>
                                                    <th>Total Order</th>
                                                    <th>Total Amount </th>
                                                    <th>Total Price </th>
                                                    <th>Status </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @if( isset($orders) && count($orders))

                                                @foreach ($orders as $item)
                                                {{-- @dd($item)) --}}
                                                @foreach ($item->orderDetails as $detail)
                                                @php $detail->product; @endphp
                                                @endforeach

                                                <tr data-product="{{ $item->orderDetails ? json_encode($item->orderDetails) : null }}" class="product-order" title="double click to show details">
                                                    <td class="align-middle">{{ $item->order_date ?? 'N/A' }}</td>
                                                    <td class="align-middle">{{ $item->order_no ?? 'N/A' }}</td>
                                                    <td class="align-middle">{{ $item->orderDetails ? count($item->orderDetails) : 0 }}</td>
                                                    <td class="align-middle">{{ $item->order_total_qty ?? 0 }}</td>
                                                    <td class="align-middle">{{ $item->order_total_price ?? 0.0 }}</td>
                                                    <td class="align-middle">{{ $item->status ?? 'N/A' }}</td>
                                                </tr>
                                                @endforeach
                                                @endif

                                            </tbody>
                                            <tfoot class="fixed-footer-top bg-danger text-white">
                                                <tr>
                                                    <th> Order Date</th>
                                                    <th> Order </th>
                                                    <th>Total Order</th>
                                                    <th>Total Amount </th>
                                                    <th>Total Price </th>
                                                    <th>Status </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="v-pills-wishlist" role="tabpanel" aria-labelledby="v-pills-wishlist-tab">
                                <div class="account-info ">
                                    <h2 class="mb-3"> Wish List </h2>
                                    <div class="row parentOfWishLish {{ isset($wishListProducts) && count($wishListProducts) ? 'shopping-card-row' : '' }}">

                                        @isset($wishListProducts)
                                        @forelse ($wishListProducts as $item)
                                        <div class="mb-3 __product-card-parent-wish">
                                            <div class="card __product-card">
                                                <div class="card-wishlist {{ in_array($item->id,$wishLists) ? 'removeFromWish' : '' }}" data-productid="{{ $item->id }}" data-auth="{{ auth()->user()->id ?? null }}" style="z-index: 100;" type="button"> <i class="fa-solid fa-heart"></i></div>
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
                                                    $unitprice = $totalprice / $totalqty;
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
                                                        <button type="button" data-productid="{{ $item->id }}" class="btn btn-sm btn-secondary btn-card {{ !in_array($item->id,$productIds) ? 'addToCart' : 'alreadyInCart' }}"> {!! !in_array($item->id,$productIds) ? 'কার্ডে যুক্ত করুন' :'<span> <i class=\'fa fa-circle-check\'></i> অলরেডি যুক্ত আছে</span>' !!}</button>
                                                        <a href="{{ route('checkout_index',$item->id ) }}" type="button" class="btn btn-sm btn-danger"> Order </a>
                                                        @else
                                                        <span class="text-danger">Out of Stock</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="alert alert-danger w-100 py-4">
                                            <h5>Your Wish List is Empty!</h5>
                                        </div>
                                        @endforelse

                                        @else
                                        <div class="alert alert-danger w-100 py-4">
                                            <h5>Your Wish List is Empty!</h5>
                                        </div>

                                        @endisset

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="v-pills-editprofile" role="tabpanel" aria-labelledby="v-pills-editprofile-tab">

                                <div class="account-info">
                                    <h2 class="mb-3"> Update Profile </h2>
                                    <div class="row">

                                        <div class="col-md-12 px-2 mb-3 d-flex align-items-center">

                                            <div class="profile-img">
                                                {!! profilePhoto(auth()->user()->profile ? auth()->user()->profile->photo : null ) !!}
                                                <div title="Click To Upload Image" type="button" class="overlay d-flex align-items-center justify-content-center" onclick="javascript: document.getElementById('profileImageUploader').click()">
                                                    <span class="fa-solid fa-images fa-2x text-danger"></span>
                                                    <input id="profileImageUploader" type="file" class="form-control border file-loder bg-danger d-none" name="profile_img">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6 px-2 mb-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control border" placeholder="Write Your Name" data-required value="{{ $authUser->name ?? 'N/A' }}" name="name">
                                            </div>

                                            <div class="v-msg text-danger"></div>
                                        </div>

                                        <div class="col-md-6 px-2 mb-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control border" placeholder="Write Your User Name" name="username" value="{{ $authUser->username ?? ''}}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 px-2 mb-3">
                                            <div class="form-group">
                                                <input type="email" class="form-control border" data-required value="{{ $authUser->email ?? 'N/A' }}" placeholder="Write Your Email" name="email" readonly>
                                            </div>
                                            <div class="v-msg text-danger"></div>
                                        </div>


                                        <div class="col-md-6 px-2 mb-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control border" value="{{ $hasProfile ? $authUser->profile->mobile_no : 'N/A' }}" placeholder="Write Your Phone No" name="mobile_no">
                                            </div>
                                            <div class="v-msg text-danger"></div>
                                        </div>


                                        <div class="col-md-6 px-2 mb-3">
                                            <div class="form-group">
                                                <select class="form-select form-control border" id="gender">
                                                    @php
                                                    $selectedDefault = "selected";
                                                    $address = null;
                                                    if($hasProfile){
                                                    $selectedDefault = "";
                                                    $address = $authUser->profile->address ?? '';
                                                    }

                                                    @endphp
                                                    <option value="" {{ $selectedDefault }}> Select Gender</option>
                                                    <option value="1" {{ $hasProfile && $authUser->profile->gender == 1 ? 'selected':'' }}> Man </option>
                                                    <option value="2" {{ $hasProfile && $authUser->profile->gender == 2 ? 'selected':'' }}> Woman </option>
                                                    <option value="3" {{ $hasProfile && $authUser->profile->gender == 3 ? 'selected':'' }}> Other </option>
                                                </select>
                                            </div>

                                            <div class="v-msg text-danger"></div>
                                        </div>

                                        <div class="col-md-6 px-2 mb-3">
                                            <div class="form-group">
                                                <select class="form-select form-control border" id="district">
                                                    <option selected value=""> Select District </option>
                                                    @foreach ($districts as $district)
                                                    <option value="{{ $district }}" {{ $hasProfile && $authUser->profile->district == $district ? 'selected' : '' }}> {{ $district }} </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="v-msg text-danger"></div>
                                        </div>

                                        <div class="col-md-12 px-2 mb-3">
                                            <div class="form-group">
                                                <textarea name="address" id="address" style="resize:vertical" cols="5" rows="5" class=" border form-control" placeholder="Write Your Address !">{{ $address ?? '' }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <button class="updatebtn btn btn-sm btn-danger text-white text-center px-5"> Update </button>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="v-pills-resetpass" role="tabpanel" aria-labelledby="v-pills-resetpass-tab">
                                <div class="account-info">
                                    <h2> Reset Password </h2>

                                    <form id="passwordResetForm" method="POST" action="{{ route('dashboard.reset_password', auth()->user()->id ?? null )}}" autocomplete="off" autofill="off" autosave="off">
                                        <div class="col-md-6 px-2 mb-3">
                                            <div class="form-group">
                                                <input type="password" class="form-control border" placeholder="Old Password" name="old_password" id="old_password">
                                            </div>
                                        </div>

                                        <div class="col-md-6 px-2 mb-3">
                                            <div class="form-group">
                                                <input type="password" class="form-control border" placeholder="New Password" name="new_password" id="new_password">
                                            </div>

                                            <div class="my-3">
                                                <button class="resetBtn btn btn-sm btn-danger text-white text-center px-5"> Reset Password </button>
                                                {{-- <a href="{{ route('password.request')}}" class="resetBtn btn btn-sm btn-dark text-white text-center px-5"> Forgot Your Password </a> --}}
                                            </div>
                                        </div>
                                    </form>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>


<!-- Our Contact Area-->
<section class="container-fluid call-center-area">

    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex align-items-center justify-content-center">
                <div class="call-center text-center">
                    <h2 class="text-capitalize">You didn't find what you were looking for? Please Call:<span> <a href="tel:01971819813" class="text-decoration-none" type="button">0197-1819-813</a></span></h2>
                </div>

            </div>
        </div>
    </div>

</section>



<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false" aria-modal="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-bold modal-heading" id="exampleModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </button>
            </div>

            <div class="modal-body" id="orderDetailsInfo">
                <table id="user-order-products" class="table table-sm table-striped w-100">
                    <thead class="bg-danger">
                        <tr class="text-white">
                            <th> Product Name </th>
                            <th> Order </th>
                            <th>Price </th>
                            <th>Total Amount </th>
                            <th>Total Price </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="modal-footer">
                <div class="w-100">
                    <button type="button" class="btn btn-sm btn-danger float-right mx-1" data-bs-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/frontend/pages/css/user_dashboard.css') }}">
@endpush

@push('js')
<script>
    $(document).ready(function() {
        $(document).on('dblclick', '.product-order', getProducts)
        $(document).on('change', '#profileImageUploader', readFile)
        $(document).on('click', '.updatebtn', updateProfile)
        $(document).on('submit', '#passwordResetForm', resetPassword)
    })


    function getProducts() {
        let elem = $(this),
            products = elem?.attr('data-product') ? JSON.parse(elem.attr('data-product')) : null;

        if (products) {
            $('#orderDetailsModal').modal('show');

            let html = "";
            if (products.length) {
                products.forEach(product => {

                    let APP_URL = location.origin;
                    let src = `${product.product.product_thumbnail_image ? `${APP_URL}/${product.product.product_thumbnail_image}`  : `{{ asset('assets/frontend/img/product/product-1.png') }}`}`;
                    html += `<tr>
                        <td class="order-product align-middle">
                            <div class="inner-wrap">
                                <img src="${src}" alt="" class="mx-1">
                                <p>${product.product_name ?? 'N/A'}</p>
                            </div>
                        </td>
                        <td class="align-middle">${product.order_no ?? 'N/A'}</td>
                        <td class="align-middle">${(product.product_price - product.discount_price) ?? 0}</td>
                        <td class="align-middle">${product.product_qty ?? '0'}</td>
                        <td class="align-middle">${ product.subtotal ?? 0.0 }</td>
                    </tr>`;
                })
            }


            $('#orderDetailsInfo tbody').html(html)


        }

    }

    function readFile() {
        if (this.files && this.files[0]) {
            let FR = new FileReader();
            FR.addEventListener("load", function(e) {
                $(document).find('#v-pills-editprofile .profile-img img').attr('src', e.target.result);
            });

            FR.readAsDataURL(this.files[0]);
        }
    }

    function updateAllProfileImages() {

        let currentSrc = $(document).find('#v-pills-editprofile .profile-img img').attr('src');
        $('.sidebar__info--thumbnail img').attr('src', currentSrc);
        $('.top-header img').attr('src', currentSrc);
    }


    function updateProfile() {
        // validation check
        if (!checkRequired()) return false;

        // profile update ajax request
        ajaxFormToken();

        let id = `{{ auth()->user()->id }}`;
        let obj = {
            url: `{{ route('dashboard.update_profile', '') }}/${id}`,
            method: "PUT",
            data: userObject(),
        };

        ajaxRequest(obj, {
            reload: true,
            timer: 2000
        })

        // send Request to DB
        // let data = userObject();

        // update profile 

        updateAllProfileImages()

    }

    function checkRequired() {
        let
            isvalid = true,
            fields = $(document).find(`[data-required]`);

        [...fields].forEach(elem => {
            const element = $(elem);
            const valueOfField = element.val().trim();
            const nameAttr = element?.attr('name');
            const type = element?.attr('type');

            element.parent().parent().find('.v-msg').text(``);

            if (!valueOfField) {
                element.parent().parent().find('.v-msg').text(`${capitalize(nameAttr)} is Required`)
                isvalid = false;
            }

            if (valueOfField && type == "email" && !validateEmail(valueOfField)) {
                element.parent().parent().find('.v-msg').text(`Please Write a valid Email!`)
            }
        });

        return isvalid;
    }

    function capitalize(str = "") {
        return str.replace('_', ' ').split(' ').map(x => {
            return (x.charAt(0).toUpperCase() + x.substr(1, x.length))
        }).join(' ')
    }

    const validateEmail = (email) => {
        return email.match(
            /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
    };

    function userObject() {
        return {
            name: $('input[name="name"]').val().trim(),
            username: $('input[name="username"]').val().trim(),
            email: $('input[name="email"]').val().trim(),
            mobile_no: $('input[name="mobile_no"]').val().trim(),
            gender: $('#gender').val().trim(),
            district: $('#district').val().trim(),
            address: $('#address').val().trim(),
            photo: $(document).find('#v-pills-editprofile .profile-img img')?.attr("src") ?? null
        };
    }


    function resetPassword(e) {
        e.preventDefault();
        let
            form = $(this),
            data = form.serialize();
        ajaxFormToken();

        let obj = {
            url: form.attr('action'),
            method: "PUT",
            data,
        };

        ajaxRequest(obj, {
            reload: false,
            timer: 2000
        })

        console.log(data);

    }
</script>
@endpush