@extends('frontend.layouts.master')
@section('title','Contact')

@section('content')

<!-- Contact Area-->
<section class="container-fluid contact-area">
    <div class="container">
        <div class="row">

            <div class="col-md-6">
                @if(isset($contactInfo))
                    <div class="contact-details">

                        @if ($contactInfo->title)
                            <h2>{{ $contactInfo->title ?? 'Office Address'}} </h2>
                        @endif
                        <!-- <img src="images/logo-mic.png" alt="contact logo"> -->

                        @if ($contactInfo->description)
                            <p>
                                {{ $contactInfo->description ?? 'Create any type of customized product content at lowest cost, fastest time, highest quality assurance only at Micromedia. Order any type of customized print job as per your requirement from above list.' }}
                            </p>
                        @endif

                        @if ($contactInfo->address)
                            <div class="contact-icons d-flex">
                                <div class="icon">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>

                                <div class="icon-address">
                                    <p>
                                        {{ $contactInfo->address ?? null}}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($contactInfo->email)
                            <div class="contact-icons d-flex">
                                <div class="icon">
                                    <i class="fa-regular fa-envelope"></i>
                                </div>

                                <div class="icon-address">
                                    <p> {{ $contactInfo->email ?? null}} </p>
                                </div>
                            </div>
                        @endif

                        @if ( $contactInfo->phone )
                            <div class="contact-icons d-flex">
                                <div class="icon">
                                    <i class="fa-solid fa-phone"></i>
                                </div>

                                <div class="icon-address">
                                    <p>{{ $contactInfo->phone ?? null}}</p>
                                </div>
                            </div>
                        @endif

                        @if ($sociallink)
                            <div class="contact-social-media d-flex">
                                @if($sociallink->facebook)
                                    <a href="{{$sociallink->facebook}}"><i class="fa-brands fa-facebook"></i></a>
                                @endif

                                @if($sociallink->twitter)
                                    <a href="{{$sociallink->twitter}}"><i class="fa-brands fa-twitter-square"></i></a>
                                @endif

                                @if($sociallink->linkedin)
                                    <a href="{{$sociallink->linkedin}}"><i class="fa-brands fa-linkedin"></i></a>
                                @endif
                            </div>
                        @endif


                    </div>
                @endif
            </div>


            <div class="col-md-6 mt-3">
                <div class="contact-form">
                    <h3 class="text-center">Contact Us </h3>

                        <div class="form-group form-group2">
                            <input type="text" class="form-control-new form-control2 border mt-3"
                                id="name" placeholder=" Your Name ">
                        </div>

                        <div class="form-group">
                            <input type="email" class="form-control-new form-control2 border mt-3"
                                id="email" placeholder=" Email Address ">
                        </div>

                        <div class="form-group">
                            <input type="number" class="form-control-new form-control2 border mt-3"
                                id="phone" placeholder=" Phone Number ">
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control-new form-control2 border mt-3"
                                id="subject" placeholder="Subject">
                        </div>

                        <div class="form-group">
                            <textarea style="resize: none;" class="form-control-new border mt-3"
                                id="message" rows="20" cols="10"
                                placeholder=" Specify what you want.... "></textarea>
                        </div>

                        <div class="form-group text-center mt-3">
                            <!-- <button class="contact-button btn btn-danger btn-sm btn-outline-none border-0"> পাঠিয়ে দিন </button> -->
                            <button class="btn btn-sm mt-3 btn-danger w-100" id="contact_sent_btn"> Send </button>
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
                    <h2 class="text-uppercase">For Any Help You May Call Us At:<span> <a href="tel:01971819813" class="text-decoration-none" type="button">0197-1819-813</a></span></h2>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/frontend/pages/css/contact.css') }}">
@endpush

@push('js')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/backend/libs/demo/datatables-demo.js') }}"></script>
    <script>
        $(document).ready(function(){
            $(document).on('click','#contact_sent_btn', submitToDatabase)
        });

        function submitToDatabase(){

            let obj = {
                url     : `{{ route('contact_store')}}`, 
                method  : "POST",
                data    : formatData(),
            };

            ajaxRequest(obj, { reload: true, timer: 2000 })
        }

        function formatData(){
            return {
                name    : $('#name').val().trim(),
                email   : $('#email').val(),
                phone   : $('#phone').val(),
                subject : $('#subject').val().trim(),
                message : $('#message').val().trim(),
                _token  : `{{csrf_token()}}`
            }
        }

    </script>
@endpush