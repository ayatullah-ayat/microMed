<!-- Main Header-->
<header class="main-header box-shadow" style="background-color:#FFFF;box-shadow: 0 0 8px 0 rgba(0,0,0,.4);">

    <nav class="navbar navbar-expand-lg navbar-light" style="width: 100% !important; padding-top: 10px; padding-bottom: 0px; background-color: #FFF;">

        <div class="container">
            @if (isset($companylogo))
            <a class="navbar-brand" href="{{ url('/') }}">
                <img class="logo" src="{{asset( $companylogo->dark_logo )}}" alt="">
            </a>
            @else
            <a class="navbar-brand" href="{{ url('/') }}">
                <img class="logo" src="{{asset('assets/frontend/img/logo-mic.png')}}" alt="">
            </a>
            @endif

            
            <div class="d-flex flex-row align-items-center">
                <div class="cart-icon cart-icon-mobile me-1">
                    <a href="#" id="searchBtn"> <i class="fas fa-search"></i></a>
                </div>

                <div class="cart-icon cart-icon-mobile me-1 ordertraking">
                    <a class="nav-link text-uppercase" href="#"> <i class="fas fa-truck-fast" style="margin-bottom: 1px;"></i> </a>
                </div>

                <div class="cart-icon cart-icon-mobile">
                    <a href="{{ route('cart_index') }}"> <i class="fas fa-cart-shopping"></i><span class="cartvalue"> {{ isset($productIds) && is_array($productIds) ? count($productIds) : 0 }} </span></a>
                </div>
                <button class="navbar-toggler" style="border: none !important;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse menu" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0" id="navbarNav">
                    <li class="nav-item active">
                        <a class="nav-link text-uppercase" aria-current="page" href="{{ route('home_index') }}">Customized</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-uppercase" href="{{ route('shop_index') }}">Shop</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-uppercase" href="{{ route('gallery_index') }}">Gallery</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-uppercase" href="{{ route('about_index') }}">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-uppercase" href="{{ route('contact_index') }}">Contact</a>
                    </li>

                    <!-- <li class="nav-item ordertraking">
                        <a class="nav-link text-uppercase" href="#"> Order Track </a>
                    </li> -->
                </ul>

                <div class="cart-icon-desktop">
                    <form action="{{ route('searchResult') }}" autocomplete="off" class="d-flex justify-content-lg-end justify-content-start" style="position: relative !important;">
                        <!-- <div class="input-group ">
                            <input class="form-control search" name="key" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn d-flex justify-content-center" type="submit"><i class="fas fa-search"></i></button>
                        </div> -->
                        <div class="input-group input-group-sm">
                            <input type="text" 
                                class="form-control" 
                                placeholder="Search" 
                                style="background-color: #e4e7eb; 
                                        color: #a7a7a7; 
                                        font-size: 13px !important; 
                                        border-top-left-radius: 20px;
                                        border-bottom-left-radius: 20px;" 
                                aria-label="Recipient's username" 
                                aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button style="border: 1px solid #dadadb; margin-right: 5px;" class="btn d-flex justify-content-center" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <div id="my-list"></div>
                    </form>
                </div>

                <div class="cart-icon cart-icon-desktop">
                    <a href="{{ route('cart_index') }}"> <i class="fas fa-cart-shopping"></i><span class="cartvalue"> {{ isset($productIds) && is_array($productIds) ? count($productIds) : 0 }} </span></a>
                </div>
                <div class="cart-icon cart-icon-desktop ordertraking">
                    <a class="nav-link text-uppercase" href="#"> <i class="fas fa-truck-fast" style="margin-bottom: 1px;"></i> </a>
                </div>
                

            </div>
        </div>

    </nav>

</header>

<div class="mt-2 mb-2" id="mobileSearch" style="position: fixed; top: 60px; padding: 8px; width: 100%; display: none;">
    <div class="container">
        <form action="{{ route('searchResult') }}" autocomplete="off" class="d-flex justify-content-lg-end justify-content-start" style="position: relative !important;">
            <!-- <div class="input-group ">
                <input class="form-control search" name="key" type="search" placeholder="Search" aria-label="Search">
                <button class="btn d-flex justify-content-center" type="submit"><i class="fas fa-search"></i></button>
            </div> -->
            <div class="input-group input-group-sm">
                <input type="text" 
                    class="form-control" 
                    placeholder="Search" 
                    name="key"
                    style="background-color: #e4e7eb; 
                            color: #a7a7a7; 
                            font-size: 13px !important;  
                    aria-label="Recipient's username" 
                    aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button style="border: 1px solid #dadadb; margin-right: 5px;" class="btn d-flex justify-content-center" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div id="my-list"></div>
        </form>
    </div>
</div>