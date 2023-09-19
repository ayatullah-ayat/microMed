@extends('frontend.layouts.master')
@section('title','About')

@section('content')
 
<!-- about section starts  -->

<section class="about my-5" id="about">

    <div class="heading-title text-center text-danger">
        <h2 class="fw-bold text-uppercase"> About Us </h2>
    </div>

    <div class="container">

        <div class="row pt-5">

            <div class="col-md-6 left">

                <div class="content">

                    <h2 class="mb-4"> {{ $aboutData ? $aboutData->about_title :  'ধরনের কাস্টমাইজড প্রোডাক্ট সামগ্রী'}}</h2>

                    <p> {!! $aboutData ? $aboutData->about_description : 'আমরা যে কোনো ধরনের কাস্টমাইজড প্রোডাক্ট সামগ্রী তৈরি করতে সর্বনিম্ন খরচে,
                        দ্রুততম সময়ে, সর্বোচ্চ গুণগত মানের নিশ্চয়তা পাবেন কেবল মাইক্রোমিডিয়ায়। আপনার চাহিদা অনুযায়ী যে
                        কোনো ধরনে কাস্টমাইজড প্রিন্টের কাজের অর্ডার করুন উপরের লিস্ট থেকে।' !!} 
                    </p>

                </div>

            </div>

            <div class="col-md-6 right">
                <div class="image">
                    @if (isset($aboutData->about_thumbnail))
                        <img draggable="false" src="{{ asset( $aboutData->about_thumbnail) }}" alt="About Img">
                    @else
                        <img draggable="false" src="{{ asset('assets/frontend/img/about/about-us.jpg') }}" alt="About Img">
                    @endif
                </div>

            </div>

    </div>

</section>

<!-- about section ends -->



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
    <link rel="stylesheet" href="{{ asset('assets/frontend/pages/css/about.css') }}">
@endpush