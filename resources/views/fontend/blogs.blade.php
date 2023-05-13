@extends('fontend.layout.layout')
@section('title', 'All Blogs')

@section('content')

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
    <section id="doctors" class="doctors">
        <div class="container">
            <div class="section-title">
                <h2>Blogs</h2>
                <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
            </div>
            <div class="row">
                @foreach ($allblogs as $var)
                <div class="col-lg-6 mt-4">
                    <div class="member d-flex align-items-start">
                        <div class="pic2">
                            @if (empty($var->thumbnail))
                            <img src="{{ asset('assests/frontend/img/doctors/doctors-1.jpg') }}" class="img-fluid" alt="">
                            @else
                            <img src="{{ asset($var->thumbnail) }}" class="img-fluid" alt="">
                            @endif
                        </div>
                        <div class="member-info">
                            <h4>{{ $var->blog_title }}</h4>
                            <span>{{ date('d.m.Y', strtotime($var->schedule_date) ) }}</span>
                            @php
                                $blog_desc = strip_tags(htmlspecialchars_decode($var->blog_desc));
                                
                            @endphp
                            <p>{{ \Illuminate\Support\Str::of($blog_desc)->words(10, '...') }}</p>
                            <br><span></span><br>
                            <div class="{{ route('blog.details', ['slug'=> $var->slug]) }}">
                                <a href="{{ route('blog.details', ['slug'=> $var->slug]) }}">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div><br>
            
        </div>
    </section>
</main><!-- End #main -->

@endsection
<!-- ======= Hero Section ======= -->