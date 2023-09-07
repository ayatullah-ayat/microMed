<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Micro Media Ecommerce & Customization">
    <meta name="keywords" content="Micro Media, Ecommerce, Customize">

    <title>@yield('title','Micro Media')</title>
    <link rel="icon" href="https://micromediabd.com/public/assets/frontend/img/fav_icon.png">
    {{-- libs css goes here --}}
    <link rel="stylesheet" href="{{ asset('assets/frontend/libs/bootstrap5/bootstrap5.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/frontend/libs/fontawesome6/all.min.css') }}"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- main css goes here --}}
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/master.css') }}">

    @stack('css')

    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '254113150287162');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=254113150287162&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->

    <!-- Facebook domain verification code-->
    <meta name="facebook-domain-verification" content="is9gd74qdkdsayhzc5bs62qiksspm6" />
    <!-- End Facebook domain verification code-->


    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-1YHEE3KLPE"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-1YHEE3KLPE');
    </script>

</head>

<body>

    {{-- -------- header -------------------------------  --}}
    @includeIf('frontend.layouts.partials.header')
    {{-- -------- header -------------------------------  --}}

    {{-- ------------- content area ---------------------------  --}}
    @hasSection('content')
    @yield('content')
    @endIf

    @sectionMissing('content')
    <div class="py-5 mx-5">
        <h2 class="text-center text-uppercase font-weight-bold display-5 alert alert-danger alert-heading">No content Found</h2>
    </div>
    @endIf
    {{-- ------------- content area ---------------------------  --}}

    {{-- -------- footer ------------------------------- --}}
    @includeIf('frontend.layouts.partials.footer')
    {{-- -------- footer ---------------------------------}}


    <div class="modal fade" style="z-index: 22001;" id="orderTrackModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content modal-dialog-scrollable">

                <div class="modal-header">
                    <h5 class="modal-title text-center mx-auto" id="exampleModalLabel "> Track Order Your! </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row track-order-group mb-5">
                        <div class="form-group d-flex">
                            <input type="text" placeholder="Enter Order No" class="form-control order-track-input border">
                            <button class="btn btn-success btn-sm media-768 ordertrackBtn"><i class="fa-solid fa-paper-plane"></i> Check</button>
                        </div>

                    </div>

                    <div class="row order-track-row" style="display: none;" id="trackOrderRow"></div>

                    <div class="row px-3 order-not-found" style="display: none;">
                        <div class="col-md-12 alert alert-danger">
                            <h2>404</h2>
                            <h6>Oops ! No Order Found!</h6>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    



    <!-- ------------------ modal -------------------  -->


    <!-- Messenger Chat plugin Code -->
    <div id="fb-root"></div>

    <!-- Your Chat plugin code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>



</body>


<script src="{{ asset('assets/common_assets/libs/jquery/jquery.min.js') }}"> </script>

<script src="{{ asset('assets/frontend/libs/bootstrap5/boostrap5.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="{{ asset('assets/frontend/libs/fontawesome6/all.min.js') }}"></script>
<script src="{{ asset('assets/backend/libs/notifications/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/config.js') }}"></script>
<script src="{{ asset('assets/common_assets/js/cart.js') }}"></script>
<script src="{{ asset('assets/common_assets/js/wish.js') }}"></script>
<script src="{{ asset('assets/common_assets/js/search.js') }}"></script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.ordertraking > a', openTrackModal)
        $(document).on('click', '.ordertraking-footer > a', openTrackModal)
        $(document).on('change', '.order-track-input', callToTrack)
        $(document).on('click', '.ordertrackBtn', callToTrack)
        $(document).on('click', '.loadMoreBtn', loadMoreItems)
        $(document).on('click', '.customize-product-box', loadCustomizeProductPage)
        $(document).on('click', '#searchBtn', toggleSearchBar);

        activeNavMenu()
    })

    function toggleSearchBar()
    {
        $('#mobileSearch').toggle();
        $('#navbarSupportedContent').removeClass('show');
    }
    function callToTrack() {
        let orderNo = $('.order-track-input').val();

        ajaxFormToken()

        $.ajax({
            url: `{{ route('trackOrder') }}`,
            method: 'POST',
            data: {
                order_no: orderNo
            },
            beforeSend() {
                $('.ordertrackBtn').html(`<i class="fa-solid fa-spinner fa-spin"></i> <span class="spinnerOrdertrack">Checking ...</span>`);
            },
            success(res) {

                setTimeout(() => {
                    if (res.success && res.data) {
                        $('.order-track-row').show();
                        $('.order-not-found').hide();
                        renderOrderStatus(res)

                        $('.order-track-input').val('')
                    } else {
                        $('.order-not-found').show();
                        $('.order-track-row').hide();
                    }

                    $('.ordertrackBtn').html(`<i class="fa-solid fa-paper-plane"></i> Check`);

                }, 2000);
            },
            error(err) {
                console.log(err);
                $('.order-not-found').show();
                $('.order-track-row').hide();
                $('.ordertrackBtn').html(`<i class="fa-solid fa-paper-plane"></i> Check`);
            },
        })

    }


    function renderOrderStatus(res) {

        let from = res.order_from;
        let data = res.data;
        let pattern = /ecomerce/im;
        let html = "";

        if (pattern.test(from)) {
            html = `<div class="container-fluid">
                <article class="card">
                    <header class="card-header bg-danger text-white"> <b> My Orders / Tracking </b> </header>
                    <div class="card-body">
            
                        <div class="row">
                            <div class="col-md-2">
                                <b> Order No. </b><br>
                                ${data.order_no ?? 'N/A'}
                            </div>
            
                            <div class="col-md-2">
                                <b> Order Date </b><br>
                                ${data.order_date ?? 'N/A'}
                            </div>
            
                            <div class="col-md-2">
                                <b> Shipping By - ${data.customer_name ?? 'N/A'} </b><br>
                                ${data.shipping_address ?? 'N/A'}
                            </div>
            
                            <div class="col-md-2">
                                <b> Mobile No. </b><br>
                                ${data?.customer_phone ?? 'N/A'}
                            </div>
            
                            <div class="col-md-2">
                                <b> Payment Method </b><br>
                                ${data?.payment_type ?? 'N/A'}
                            </div>
            
                            <div class="col-md-2">
                                <b> Total Amount </b><br>
                                ${data.order_total_price ?? 0}
                            </div>
            
                        </div>
            
            
                        <div class="track">
                            ${ trackStatusRender(data) }
                        </div>
            
                        <hr><br><br>
                    </div>
                </article>
            </div>`;

        } else {
            html = `<div class="container-fluid">
                <article class="card">
                    <header class="card-header bg-danger text-white"> <b> My Orders / Tracking </b> </header>
                    <div class="card-body">
            
                        <div class="row">
                            <div class="col-md-2">
                                <b> Order No. </b><br>
                                ${data.order_no ?? 'N/A'}
                            </div>
            
                            <div class="col-md-2">
                                <b> Order Date </b><br>
                                ${data.order_date ?? 'N/A'}
                            </div>
            
                            <div class="col-md-2">
                                <b> Mobile No. </b><br>
                                ${data?.customer?.mobile_no ?? 'N/A'}
                            </div>
            
                            <div class="col-md-2">
                                <b> Total Amount </b><br>
                                ${data.order_total_price ?? 0}
                            </div>
            
                        </div>
            
            
                        <div class="track">
                            ${ trackStatusRender(data) }
                        </div>
            
                        <hr><br><br>
                    </div>
                </article>
            </div>`;
        }


        $('#trackOrderRow').html(html);
    }

    function openTrackModal() {
        $('#orderTrackModal').modal('show')
    }

    function loadCustomizeProductPage() {
        let elem = $(this);
        $(document).find('.customize-product-box').find('.modal-card').removeClass('active')
        elem.find('.modal-card').addClass('active')

        setTimeout(() => {
            window.open(elem.attr('data-href'), "_self");
        }, 1000);
    }
</script>

<script>
    var chatbox = document.getElementById('fb-customer-chat');
    chatbox.setAttribute("page_id", "172842346796529");
    chatbox.setAttribute("attribution", "biz_inbox");
</script>

<!-- Your SDK code -->
<script>
    window.fbAsyncInit = function() {
        FB.init({
            xfbml: true,
            version: 'v13.0'
        });
    };

    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
@stack('js')

</html>