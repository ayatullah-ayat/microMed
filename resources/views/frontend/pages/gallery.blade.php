@extends('frontend.layouts.master')
@section('title','Gallery')

@section('content')
  <!-- gallery section starts  -->

  <section class="gallery my-5"  id="gallery" style="min-height: 60vh">

    {{-- <div class="heading-title text-center">
        <h2> Gallery  </h2>
    </div> --}}

    <div class="text-center text-danger">
            <h2 class="fw-bold"> SOME PICTURES  OF CUSTOMIZED PRODUCTS </h2>
        </div>
 
    <div class="container">

        <div class="row pt-5">

            <div class="col-md-12">
                
                <div class="parent-container">

                    @if(isset($gallery) && isset($gallery->images))
                        @php
                            $images = json_decode($gallery->images);
                        @endphp

                        {{-- @dd($gallery->is_allow_caption) --}}
                        @foreach($images as $item)
                         @if ($item->is_active)
                            <a href="{{ asset($item->image)}}" class="lightbox-cats">
                                <img src="{{ asset($item->image)}}" alt="">
                            </a>  
                         @endif
                        @endforeach 
                    @else 
                    <div class="alert alert-danger w-100 fw-bold">
                        No Content Found
                    </div>                       
                    @endif


                </div>

            </div>

        </div>

    </div>        

</section>

<!-- gallery section Ends  -->



<!-- Our Contact Area Starts-->

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

<!-- Our Contact Area Ends-->

@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/frontend/libs/magnific-popup/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/pages/css/gallery.css') }}">
@endpush

@push('js')
    <script src="{{asset('assets/frontend/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script>

        $(document).ready(function() {
            

            $('.parent-container').magnificPopup({
                delegate: 'a',
                gallery: {
                    enabled: true
                },
                type: 'image',
                // mediumImage: {
                //     src: 'path/to/medium-image-1.jpg',
                //     w:800,
                //     h:600
                // },
                // originalImage: {
                //     src: 'path/to/large-image-1.jpg',
                //     w: 1400,
                //     h: 1050
                // }

            });

        });

    </script>
@endpush