@extends('frontend.layouts.master')
@section('title','Custom Order')

@section('content')
<!-- Single Product Area-->
<section class="container-fluid custom-product-area my-5 pb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                @isset($customServiceProduct)
                <div class="custom-prodect-image-wrapper">
                    <img src="{{asset($customServiceProduct->product_thumbnail)}}" alt="custom product images">
                    {{-- <img src="{{asset('assets/frontend/img/product/Rectangle 98.png')}}"
                        alt="custom product images"> --}}
                </div>
                @endisset
            </div>
            <div class="col-md-6">
                <div class="custom-prodect-info">

                    <div class="single-prodect-title">
                        @if(isset( $customServiceProduct->product_name))
                        <h2>{{ $customServiceProduct->product_name }}</h2>
                        @endif
                    </div>

                    <div class="single-prodect-description">
                        @if(isset( $customServiceProduct->product_description))
                        <p>{{ $customServiceProduct->product_description }}</p>
                        @endif
                    </div>

                    <div class="custom-prodect-form">
                        {{-- @dd($customServiceProduct) --}}
                        <input type="hidden" name="product_id" id="product_id" value="{{$customServiceProduct->id}}">
                        <input type="hidden" name="product_name" id="product_name"
                            value="{{ $customServiceProduct->product_name}}">
                        <div class="form-group">
                            <label for="customerName"> Your Name </label>
                            <input type="text" name="customer_name" id="customer_name"
                                class="form-control-new form-control2 border" id="customerName" placeholder=" Your Name ">
                        </div>
                        <div class="form-group">
                            <label for="customerPhone"> Phone Number </label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                class="form-control-new form-control2 border" id="customerPhone"
                                placeholder=" Phone Number  ">
                        </div>

                        <div class="form-group">
                            <label for="customerAddress"> Your Address </label>
                            <textarea style="resize: none;" name="customer_address" id="customer_address"
                                class="form-control-new border" id="customerAddress" rows="20" cols="10"
                                placeholder=" Enter Your Address.... "></textarea>
                        </div>

                        <div class="button-area form-group row justify-content-between align-items-center my-4">
                            <div class="col-md-7 mx-0 px-0">
                                <input type="file" name="logo" id="customLogo" class="d-none">
                                <button class="btn btn-light text-danger w-100"
                                    onclick="javascript: document.getElementById('customLogo').click()">
                                    <span class="fa-solid fa-images"></span><span id="fileCount" class="mx-1">Click Here to add Your Logo</span>
                                </button>
                            </div>
                            <div class="col-md-5 mx-0 px-0 d-flex justify-content-end">
                                <button id="submit_to_order" class="btn btn-danger text-white w-custom-95">Confirm</button>
                            </div>
                        </div>
                    </div>

                    @if($socialicon && ($socialicon->fb_messenger || $socialicon->whatsapp))
                    <div class="contact-info row">
                        <h6 class="mt-4 mb-2">Connect with Us</h6>
                        <div class="contact-inner-info col-md-12">
                            {{-- @dd($socialicon) --}}
                            <div class="d-flex gap-2 contact-info-customerorder">
                                @if ($socialicon)
                                @if($socialicon->whatsapp)
                                <span><a style="font-size: 35px; color: #48C857 !important; margin-right: 10px"
                                        target="_blank" href="{{$socialicon->whatsapp}}"><i class="fab fa-whatsapp"
                                            style="color: #48C857 !important;"></i></a></span>
                                @endif
                                @if($socialicon->fb_messenger)
                                <span><span><a style="font-size: 35px; color: #00B2FF !important;" target="_blank"
                                            href="{{ $socialicon->fb_messenger }}"><i class="fab fa-facebook-messenger"
                                                style="color: #00B2FF !important;"></i></a></span></span>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif



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
<link rel="stylesheet" href="{{ asset('assets/frontend/pages/css/customorder.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/frontend/libs/slick-carousel/slick.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/config.js') }}"></script>
<script>
    let timeId = null;
        $(function(){    
    
        // ============slider ================== 
            $('.product-slider .slider').slick({
                dots: true,
                infinite: false,
                speed: 500,
                slidesToShow: 5,
                slidesToScroll: 5,
                // centerMode: true,
                responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
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

        $(document).ready(function(){
            $(document).on('click' , '#submit_to_order', submitToDatabase)
            $(document).on('change' , '#customLogo', countUploadedLogo)
        });

        function submitToDatabase(){

            let elem = $(this);

            ajaxFormToken()

            clearTimeout(timeId)
            let uplodedFile = fileReader($('#customLogo'));
            timeId = setTimeout(() => {
                let obj = {
                    url     : `{{ route('customize.store') }}`,
                    method  : "POST",
                    data    : { ...formatData(), order_attachment: JSON.stringify(uplodedFile) },
                };

                $.ajax({
                    ...obj,
                    beforeSend(){
                        elem.html(`<i class="fa fa-spinner fa-spin text-white" style="color:#fff !important;"></i> রিকুয়েস্ট পাঠানো হচ্ছে ...`);
                    },
                    success(res){
                        if(res?.success){
                            _toastMsg(res?.msg ?? 'Success!', 'success');
                            elem.html(`রিকুয়েস্ট পাঠানো হয়েছে`);
                            resetForm();

                            setTimeout(() => {
                                location.reload()
                                elem.html(`কনফার্ম করুন`);
                            }, 2000);
                        }else{
                            _toastMsg(res?.msg ?? 'Something wents wrong!');
                            elem.html(`কনফার্ম করুন`);
                        }
                    },
                    error(err){
                        console.log(err);
                        _toastMsg((err.responseJSON?.msg) ?? 'Something wents wrong!')
                        elem.html(`কনফার্ম করুন`);
                    },
                })

                // 
            }, 500);
            
        }

        function formatData(){
            return {
                customer_name       : $('#customer_name').val(),
                customer_phone      : $('#customer_phone').val(),
                customer_address    : $('#customer_address').val().trim(),
                _token              : `{{ csrf_token()}}`,
                custom_service_product_id: $('#product_id').val(),
                custom_service_product_name: $('#product_name').val(),
                order_attachment: null
            }
            
        }


        function resetForm(){
            $('#customer_name').val('');
            $('#customer_phone').val('');
            $('#customer_address').val('');
        }


        function fileReader(elem){

            let file = [];
            if(elem[0].files && elem[0].files[0]){
                //customLogo
                let FR = new FileReader();
                FR.addEventListener("load", function (e) {
                    file.push(e.target.result);
                });

                FR.readAsDataURL(elem[0].files[0]);

                return file;
            }
            
        }


        function countUploadedLogo(e){

            if(!e.target.files?.length){
                $('#fileCount').text('লোগো এ্যাড করতে এখানে ক্লিক করুন')
                return false;
            }

            $('#fileCount').text('লোগোটি সফলভাবে এ্যাড হয়েছে')

        }
            
</script>
@endpush