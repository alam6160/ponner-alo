@extends('fontend.layout.layout')
@section('title', 'Blog Details')

@section('content')

@push('style')
<style>
    .card {
        background-color: white;
        padding: 25px;
        margin-top: 20px;
    }
    .post-blog{margin-bottom: 20px;margin-top: 10px;}
    .mb-10{margin-bottom: 10px;}
    .mb-15{margin-bottom: 15px;}
    .mt-10{margin-top: 10px;}
    .mt-15{margin-top: 15px;}
    .mt-20{margin-top: 20px;}
    .fs-15{font-size: 15px;}
    .place-center{place-self: center;}
    ul {
        list-style-type: none;
    }
    .category ul li {
        display: inline-block;
        margin-bottom: 5px;
        margin-right: 1px;}
        .category ul li a {
        display: inline-block;
        padding: 6px 10px;
        background: #f5f5f5;
        color: #222;
        transition: all 0.2s ease-out;
        font-weight: 500;
        font-size: 13px;
        border-radius: 4px;
    }
</style>
@endpush

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Blog</h2>
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li>Blog</li>
                </ol>
            </div>
        </div>
    </section><!-- End Breadcrumbs Section -->
    <section class="counts">
        <div class="container">
            <div class="row">
                <div class="leftcolumn">
                    <div class="card">
                        <h2 class="mb-10" >{{ $blog->blog_title }}</h2>
                        <h4 class="mb-15">{{ date('F j, Y', strtotime($blog->schedule_date)) }}</h4>
                        @if (!empty($blog->thumbnail))
                        <img src="{{ asset($blog->thumbnail) }}" class="img-fluid" alt="">
                        @endif
                        <div style="mt-4">
                            {!! htmlspecialchars_decode($blog->blog_desc) !!}
                        </div>
                    </div>
                </div>
                <div class="rightcolumn">
                    <div class="search-box mt-20">
                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-info" type="submit">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <h4 class="text-center"><b>New Post</b></h4>
                        @foreach ($allblogs as $var)
                        <div class="post-blog">
                            <div class="row">
                                <div class="col-md-12 place-center">
                                    <a href="{{ route('blog.details', ['slug'=> $var->slug]) }}" class="fs-15">{{ $var->blog_title }}</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        
                        <a href="{{ route('blog.index') }}" class="btn btn-outline-info">Show More</a>
                    </div>
                    <div class="card" id="blog-post">
                        <h4 class="text-center"><b>Follow Us</b></h4>
                        <div class="social-links text-center text-md-right pt-3 pt-md-0">
                            <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                            <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                            <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                            <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                        </div>
                    </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

@endsection
<!-- ======= Hero Section ======= -->