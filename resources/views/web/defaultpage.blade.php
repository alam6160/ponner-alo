@extends('web.common')
@section('content')

@push('style')
    <link rel="stylesheet" href="{{asset('assests/web/css/about.css')}}">
@endpush

<section class="inner-section single-banner" style="background: url({{asset('assests/web/images/single-banner.jpg')}}) no-repeat center;">
    <div class="container">
        <h2>{{$title}}</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
        </ol>
    </div>
</section>

<section class="about-choose">
    <div class="container">
        <div class="row">
            <div class="col-11 col-md-9 col-lg-7 col-xl-6 mx-auto">
                <div class="section-heading"><h2>{{ $title }} </h2></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="choose-card">
                    <div class="choose-text">
                        {!! (blank($page)) ? '' : htmlspecialchars_decode($page->page_desc) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection