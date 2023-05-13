@extends('web/common')

@section('content')




    @if ($sliders_list->isNotEmpty())
        {{-- <section class="home-index-slider slider-arrow slider-dots">

            @foreach ($sliders_list as $slider)

                <div class="banner-part banner-1">

                    <div class="container">

                        <div class="row align-items-center">

                            <div class="col-md-12 col-lg-12">

                                <div class="banner-content">

                                    <h1>{{$slider->title}}</h1>

                                    <p>{{$slider->sub_title}}</p>

                                    @if (!empty($slider->link) and !empty($slider->caption))

                                        <div class="banner-btn">

                                            <a class="btn btn-inline" href="{{$slider->link}}"><i class="fas fa-shopping-basket"></i><span>{{$slider->caption}}</span></a>

                                        </div>

                                    @endif

                                </div>

                            </div>

                            <div class="col-md-6 col-lg-6">

                                <div class="banner-img">

                                    <img src="{{asset($slider->thumnanail)}}">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                

            @endforeach

        </section>  --}}
        <section>
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <!-- <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div> -->
                <div class="carousel-inner">
                    @foreach ($sliders_list as $slider)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            @if (empty($slider->link))
                                <img src="{{ asset($slider->thumnanail) }}" class="d-block w-100" alt="...">
                            @else
                                <a href="{{ $slider->link }}"><img src="{{ asset($slider->thumnanail) }}"
                                        class="d-block w-100" alt="..."></a>
                            @endif

                        </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>
    @endif


    @if ($parent_cats->isNotEmpty())
        <section class="section suggest-part">

            <div class="container">

                <ul class="suggest-slider slider-arrow">

                    @foreach ($parent_cats as $category)
                        <li>

                            <a class="suggest-card" href="{{ url('/') }}/products-list/{{ $category->slug }}">

                                @if (!empty($category->logo))
                                    <img src="{{ asset($category->logo) }}">
                                @endif

                                @php $total_items = $product_model->whereRaw("FIND_IN_SET($category->id, categories)")->count(); @endphp

                                <h5>{{ $category->cat_title }}<span>{{ $total_items }} Items</span></h5>

                            </a>

                        </li>
                    @endforeach

                </ul>

            </div>

        </section>
    @endif



    @if ($daily_deals_products->isNotEmpty())
        <section class="section recent-part">

            <div class="container">

                <div class="row">

                    <div class="col-lg-12">

                        <div class="section-heading">

                            <h2>Deals Of The Day</h2>

                        </div>

                    </div>

                </div>

                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                    @foreach ($daily_deals_products as $product)
                        <div class="col">

                            <div class="product-card">

                                <div class="product-media">

                                    <div class="product-label">

                                        @if (strtotime($product->created_at) > strtotime('-15 day'))
                                            <label class="label-text new">New</label>
                                        @endif

                                        @if ($product->saletype == '2')
                                            <label class="label-text sale">Best Selling</label>
                                        @endif

                                    </div>



                                    @php
                                        
                                        if (Auth::check()) {
                                            $wishlist_data = $wishlist_model->where(['user_id' => Auth::id(), 'product_id' => $product->id, 'variant_index' => '0'])->first();
                                        }
                                        
                                    @endphp

                                    @if (!empty($wishlist_data))
                                        <button class="product-wish wish active" title="Remove From Wishlist"
                                            id="wishlist_btn_{{ $product->id }}"
                                            onclick="add_remove_wishlist_item({{ $product->id }}, 0, 0);"><i
                                                class="fas fa-heart"></i></button>
                                    @else
                                        <button class="product-wish wish" title="Add To Wishlist"
                                            id="wishlist_btn_{{ $product->id }}"
                                            onclick="add_remove_wishlist_item({{ $product->id }}, 0, 0);"><i
                                                class="fas fa-heart"></i></button>
                                    @endif



                                    <a class="product-image"  href="{{ url('/') }}/product/{{ $product->slug }}">

                                        <img src="{{ asset($product->featuredimage) }}">

                                    </a>

                                    <div class="product-widget">

                                        @if (!empty($product->video_url))
                                            <a title="Product Video" href="{{ $product->video_url }}"
                                                class="venobox fas fa-play" data-autoplay="true" data-vbtype="video"></a>
                                        @endif

                                        {{-- <a title="Product View" href="javascript:void(0);" class="fas fa-eye" data-bs-toggle="modal" data-bs-target="#product-view"></a> --}}

                                    </div>

                                </div>

                                @if ($product->product_type == '1')
                                    <div class="product-content">

                                        @php
                                            $avg_review = $review_controller->avg_star($product->id);
                                        @endphp
                                            <div class="product-rating">

                                                <i class="{{($avg_review['avg'] >= 1) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 2) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 3) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 4) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 5) ? 'active icofont-star' : 'icofont-star'}}"></i>

                                                <a href="javascript:void(0);">({{$avg_review['total_review']}})</a>

                                            </div>

                                        <h6 class="product-name">

                                            <a 
                                                href="{{ url('/') }}/product/{{ $product->slug }}">{{ substr($product->name, 0, 50) }}</a>

                                        </h6>

                                        <h6 class="product-price">

                                            @if (!empty($product->discounted_price))
                                                <del>{!! Helper::ccurrency() !!}{{ $product->mrp }}</del>

                                                <span>{!! Helper::ccurrency() !!}{{ $product->discounted_price }}<small>/Item</small></span>
                                            @else
                                                <span>{!! Helper::ccurrency() !!}{{ $product->mrp }}<small>/Item</small></span>
                                            @endif

                                        </h6>

                                        <button class="product-add-cart" title="Add To Cart"
                                            onclick="add_to_cart({{ $product->id }}, 0);"><i
                                                class="fas fa-shopping-basket"></i><span>Add</span></button>

                                    </div>
                                @else
                                    @php
                                        
                                        $mrp = json_decode($product->mrp);
                                        
                                        $discounted_price = json_decode($product->discounted_price);
                                        
                                        $specifications = json_decode($product->specifications);
                                        
                                    @endphp



                                    <div class="product-content">

                                        {{-- <div class="product-rating">

                                            <i class="active icofont-star"></i>

                                            <i class="active icofont-star"></i>

                                            <i class="active icofont-star"></i>

                                            <i class="active icofont-star"></i>

                                            <i class="icofont-star"></i>

                                            <a href="javascript:void(0);">(4)</a>

                                        </div> --}}

                                        <h6 class="product-name">

                                            <a  href="{{ url('/') }}/product/{{ $product->slug }}">{{ substr($product->name, 0, 50) }}
                                                ({{ $specifications[0] }})
                                            </a>

                                        </h6>

                                        <h6 class="product-price">

                                            @if (!empty($discounted_price[0]))
                                                <del>{!! Helper::ccurrency() !!}{{ $mrp[0] }}</del>

                                                <span>{!! Helper::ccurrency() !!}{{ $discounted_price[0] }}<small>/Item</small></span>
                                            @else
                                                <span>{!! Helper::ccurrency() !!}{{ $mrp[0] }}<small>/Item</small></span>
                                            @endif

                                        </h6>

                                        <button class="product-add-cart" title="Add To Cart"
                                            onclick="add_to_cart({{ $product->id }}, 0);"><i
                                                class="fas fa-shopping-basket"></i><span>Add</span></button>

                                    </div>
                                @endif

                            </div>

                        </div>
                    @endforeach

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="section-btn-25">

                            <a href="{{ url('/') }}/products-list/deals-of-the-day" class="btn btn-outline"><i
                                    class="fas fa-eye"></i><span>Show More</span></a>

                        </div>

                    </div>

                </div>

            </div>

        </section>
    @endif



    <div class="section promo-part">

        <div class="container">

            <div class="row">

                <div class="col-lg-12">

                    <div class="promo-img">

                        <a href="javascript:void(0);"><img src="{{ asset('assests/web/images/promo/home/03.jpg') }}"></a>

                    </div>

                </div>

            </div>

        </div>

    </div>



    @if ($featured_products->isNotEmpty())
        <section class="section feature-part">

            <div class="container">

                <div class="row">

                    <div class="col-lg-12">

                        <div class="section-heading">

                            <h2>Our Featured Items</h2>

                        </div>

                    </div>

                </div>

                <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-2">

                    @foreach ($featured_products as $product)
                        <div class="col">

                            <div class="feature-card">

                                <div class="feature-media">

                                    <div class="feature-label">

                                        <label class="label-text feat">Featured</label>

                                    </div>



                                    @php
                                        
                                        if (Auth::check()) {
                                            $wishlist_data = $wishlist_model->where(['user_id' => Auth::id(), 'product_id' => $product->id, 'variant_index' => '0'])->first();
                                        }
                                        
                                    @endphp

                                    @if (!empty($wishlist_data))
                                        <button class="feature-wish wish active" title="Remove From Wishlist"
                                            id="wishlist_btn_{{ $product->id }}"
                                            onclick="add_remove_wishlist_item({{ $product->id }}, 0, 0);"><i
                                                class="fas fa-heart"></i></button>
                                    @else
                                        <button class="feature-wish wish" title="Add To Wishlist"
                                            id="wishlist_btn_{{ $product->id }}"
                                            onclick="add_remove_wishlist_item({{ $product->id }}, 0, 0);"><i
                                                class="fas fa-heart"></i></button>
                                    @endif



                                    <a class="feature-image"  href="{{ url('/') }}/product/{{ $product->slug }}">

                                        <img src="{{ asset($product->featuredimage) }}">

                                    </a>

                                    <div class="feature-widget">

                                        @if (!empty($product->video_url))
                                            <a title="Product Video" href="{{ $product->video_url }}"
                                                class="venobox fas fa-play" data-autoplay="true" data-vbtype="video"></a>
                                        @endif

                                        {{-- <a title="Product View" href="javascript:void(0);" class="fas fa-eye" data-bs-toggle="modal" data-bs-target="#product-view"></a> --}}

                                    </div>

                                </div>

                                @if ($product->product_type == '1')
                                    <div class="feature-content">

                                        <h6 class="feature-name">

                                            <a
                                             href="{{ url('/') }}/product/{{ $product->slug }}">{{ substr($product->name, 0, 50) }}</a>

                                        </h6>


                                        @php
                                            $avg_review = $review_controller->avg_star($product->id);
                                        @endphp
                                            <div class="product-rating">

                                                <i class="{{($avg_review['avg'] >= 1) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 2) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 3) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 4) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 5) ? 'active icofont-star' : 'icofont-star'}}"></i>

                                                <a href="javascript:void(0);">({{$avg_review['total_review']}})</a>

                                            </div>

                                        <h6 class="feature-price">

                                            @if (!empty($product->discounted_price))
                                                <del>{!! Helper::ccurrency() !!}{{ $product->mrp }}</del>

                                                <span>{!! Helper::ccurrency() !!}{{ $product->discounted_price }}<small>/Item</small></span>
                                            @else
                                                <span>{!! Helper::ccurrency() !!}{{ $product->mrp }}<small>/Item</small></span>
                                            @endif

                                        </h6>

                                        {{-- <p class="feature-desc">{{ substr($product->short_desc, 0, 100) }}</p> --}}

                                        <button class="product-add-cart" title="Add To Cart"
                                            onclick="add_to_cart({{ $product->id }}, 0);"><i
                                                class="fas fa-shopping-basket"></i><span>Add</span></button>

                                    </div>
                                @else
                                    @php
                                        
                                        $mrp = json_decode($product->mrp);
                                        
                                        $discounted_price = json_decode($product->discounted_price);
                                        
                                        $specifications = json_decode($product->specifications);
                                        
                                    @endphp



                                    <div class="feature-content">

                                        <h6 class="feature-name">

                                            <a  href="{{ url('/') }}/product/{{ $product->slug }}">{{ substr($product->name, 0, 50) }}
                                                ({{ $specifications[0] }})
                                            </a>

                                        </h6>

                                        <div class="feature-rating">

                                            <i class="{{($avg_review['avg'] >= 1) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 2) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 3) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 4) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 5) ? 'active icofont-star' : 'icofont-star'}}"></i>

                                                <a href="javascript:void(0);">({{$avg_review['total_review']}})</a>

                                        </div>

                                        <h6 class="feature-price">

                                            @if (!empty($discounted_price[0]))
                                                <del>{!! Helper::ccurrency() !!}{{ $mrp[0] }}</del>

                                                <span>{!! Helper::ccurrency() !!}{{ $discounted_price[0] }}<small>/Item</small></span>
                                            @else
                                                <span>{!! Helper::ccurrency() !!}{{ $mrp[0] }}<small>/Item</small></span>
                                            @endif

                                        </h6>

                                        <p class="feature-desc">{{ substr($product->short_desc, 0, 100) }}</p>

                                        <button class="product-add-cart" title="Add To Cart"
                                            onclick="add_to_cart({{ $product->id }}, 0);"><i
                                                class="fas fa-shopping-basket"></i><span>Add</span></button>

                                    </div>
                                @endif

                            </div>

                        </div>
                    @endforeach

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="section-btn-25">

                            <a href="{{ url('/') }}/products-list/featured-items" class="btn btn-outline"><i
                                    class="fas fa-eye"></i><span>Show More</span></a>

                        </div>

                    </div>

                </div>

            </div>

        </section>
    @endif

    {{-- <section class="section countdown-part">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-6 mx-auto">

                    <div class="countdown-content">

                        <h3>special discount offer for vegetable items</h3>

                        <p>Reprehenderit sed quod autem molestiae aut modi minus veritatis iste dolorum suscipit quis voluptatum fugiat mollitia quia minima</p>

                        <div class="countdown countdown-clock" data-countdown="2022/12/22">

                            <span class="countdown-time"><span>00</span><small>days</small></span>

                            <span class="countdown-time"><span>00</span><small>hours</small></span>

                            <span class="countdown-time"><span>00</span><small>minutes</small></span>

                            <span class="countdown-time"><span>00</span><small>seconds</small></span>

                        </div>

                        <a href="javascript:void(0);" class="btn btn-inline">

                            <i class="fas fa-shopping-basket"></i><span>Shop Now</span>

                        </a>

                    </div>

                </div>

                <div class="col-lg-1"></div>

                <div class="col-lg-5">

                    <div class="countdown-img">

                        <img src="{{asset('assests/web/images/countdown.png')}}">

                        <div class="countdown-off"><span>20%</span><span>off</span></div>

                    </div>

                </div>

            </div>

        </div>

    </section> --}}



    @if ($new_arrivals->isNotEmpty())
        <section class="section newitem-part">

            <div class="container">

                <div class="row">

                    <div class="col">

                        <div class="section-heading">

                            <h2>Our New Arrivals</h2>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col">

                        <ul class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                            @foreach ($new_arrivals as $product)
                                <li class="col-md-4 col-lg-2 col-sm-4">

                                    <div class="product-card">

                                        <div class="product-media">

                                            <div class="product-label">

                                                <label class="label-text new">New</label>

                                            </div>



                                            @php
                                                
                                                if (Auth::check()) {
                                                    $wishlist_data = $wishlist_model->where(['user_id' => Auth::id(), 'product_id' => $product->id, 'variant_index' => '0'])->first();
                                                }
                                                
                                            @endphp

                                            @if (!empty($wishlist_data))
                                                <button class="product-wish wish active" title="Remove From Wishlist"
                                                    id="wishlist_btn_{{ $product->id }}"
                                                    onclick="add_remove_wishlist_item({{ $product->id }}, 0, 0);"><i
                                                        class="fas fa-heart"></i></button>
                                            @else
                                                <button class="product-wish wish" title="Add To Wishlist"
                                                    id="wishlist_btn_{{ $product->id }}"
                                                    onclick="add_remove_wishlist_item({{ $product->id }}, 0, 0);"><i
                                                        class="fas fa-heart"></i></button>
                                            @endif



                                            <a class="product-image" 
                                                href="{{ url('/') }}/product/{{ $product->slug }}"><img
                                                    src="{{ asset($product->featuredimage) }}"></a>

                                            <div class="product-widget">

                                                @if (!empty($product->video_url))
                                                    <a title="Product Video" href="{{ $product->video_url }}"
                                                        class="venobox fas fa-play" data-autoplay="true"
                                                        data-vbtype="video"></a>
                                                @endif

                                                {{-- <a title="Product View" href="javascript:void(0);" class="fas fa-eye" data-bs-toggle="modal" data-bs-target="#product-view"></a> --}}

                                            </div>

                                        </div>

                                        @if ($product->product_type == '1')
                                            <div class="product-content">

                                                @php
                                            $avg_review = $review_controller->avg_star($product->id);
                                        @endphp
                                            <div class="product-rating">

                                                <i class="{{($avg_review['avg'] >= 1) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 2) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 3) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 4) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 5) ? 'active icofont-star' : 'icofont-star'}}"></i>

                                                <a href="javascript:void(0);">({{$avg_review['total_review']}})</a>

                                            </div>
                                            
                                                {{-- <div class="product-rating">

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="icofont-star"></i>

                                                    <a href="javascript:void(0);">(4)</a>

                                                </div> --}}

                                                <h6 class="product-name">

                                                    <a
                                                     href="{{ url('/') }}/product/{{ $product->slug }}">{{ substr($product->name, 0, 50) }}</a>

                                                </h6>

                                                <h6 class="product-price">

                                                    @if (!empty($product->discounted_price))
                                                        <del>{!! Helper::ccurrency() !!}{{ $product->mrp }}</del>

                                                        <span>{!! Helper::ccurrency() !!}{{ $product->discounted_price }}<small>/Item</small></span>
                                                    @else
                                                        <span>{!! Helper::ccurrency() !!}{{ $product->mrp }}<small>/Item</small></span>
                                                    @endif

                                                </h6>

                                                <button class="product-add-cart" title="Add To Cart"
                                                    onclick="add_to_cart({{ $product->id }}, 0);"><i
                                                        class="fas fa-shopping-basket"></i><span>Add</span></button>

                                            </div>
                                        @else
                                            @php
                                                
                                                $mrp = json_decode($product->mrp);
                                                
                                                $discounted_price = json_decode($product->discounted_price);
                                                
                                                $specifications = json_decode($product->specifications);
                                                
                                            @endphp



                                            <div class="product-content">

                                                {{-- <div class="product-rating">

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="icofont-star"></i>

                                                    <a href="javascript:void(0);">(4)</a>

                                                </div> --}}

                                                <h6 class="product-name">

                                                    <a  href="{{ url('/') }}/product/{{ $product->slug }}">{{ substr($product->name, 0, 50) }}
                                                        ({{ $specifications[0] }})
                                                    </a>

                                                </h6>

                                                <h6 class="product-price">

                                                    @if (!empty($discounted_price[0]))
                                                        <del>{!! Helper::ccurrency() !!}{{ $mrp[0] }}</del>

                                                        <span>{!! Helper::ccurrency() !!}{{ $discounted_price[0] }}<small>/Item</small></span>
                                                    @else
                                                        <span>{!! Helper::ccurrency() !!}{{ $mrp[0] }}<small>/Item</small></span>
                                                    @endif

                                                </h6>

                                                <button class="product-add-cart" title="Add To Cart"
                                                    onclick="add_to_cart({{ $product->id }}, 0);"><i
                                                        class="fas fa-shopping-basket"></i><span>Add</span></button>

                                            </div>
                                        @endif

                                    </div>

                                </li>
                            @endforeach

                        </ul>

                    </div>

                </div>

                <div class="row">

                    <div class="col">

                        <div class="section-btn-25">

                            <a href="{{ url('/') }}/products-list/new-arrivals" class="btn btn-outline"><i
                                    class="fas fa-eye"></i><span>Show More</span></a>

                        </div>

                    </div>

                </div>

            </div>

        </section>
    @endif



    <div class="section promo-part">

        <div class="container">

            <div class="row">

                <div class="col-sm-12 col-md-6 col-lg-6 px-xl-3">

                    <div class="promo-img">

                        <a href="javascript:void(0);">

                            <img src="{{ asset('assests/web/images/promo/home/01.jpg') }}">

                        </a>

                    </div>

                </div>

                <div class="col-sm-12 col-md-6 col-lg-6 px-xl-3">

                    <div class="promo-img">

                        <a href="javascript:void(0);">

                            <img src="{{ asset('assests/web/images/promo/home/02.jpg') }}">

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>



    @if ($daily_deals_products->isNotEmpty() or
        $weekly_deals_products->isNotEmpty() or
        $monthly_deals_products->isNotEmpty())

        <section class="section niche-part">

            <div class="container">

                <div class="row">

                    <div class="col-lg-12">

                        <div class="section-heading">

                            <h2>Browse By Top Deals</h2>

                        </div>

                    </div>

                </div>

                @php
                    
                    if ($daily_deals_products->isNotEmpty()) {
                        $tab_link = ['active', null, null];
                    
                        $tab_pane = ['show active', null, null];
                    } elseif ($weekly_deals_products->isNotEmpty()) {
                        $tab_link = [null, 'active', null];
                    
                        $tab_pane = [null, 'show active', null];
                    } elseif ($monthly_deals_products->isNotEmpty()) {
                        $tab_link = [null, null, 'active'];
                    
                        $tab_pane = [null, null, 'show active'];
                    } else {
                        $tab_link = [null, null, null];
                    
                        $tab_pane = [null, null, null];
                    }
                    
                @endphp

                <div class="row">

                    <div class="col-lg-12">

                        <ul class="nav nav-tabs">

                            @if ($daily_deals_products->isNotEmpty())
                                <li><a href="#daily-deals" class="tab-link {{ $tab_link[0] }}" data-bs-toggle="tab"><i
                                            class="icofont-price"></i><span>Daily Deals</span></a></li>
                            @endif

                            @if ($weekly_deals_products->isNotEmpty())
                                <li><a href="#weekly-deals" class="tab-link {{ $tab_link[1] }}"
                                        data-bs-toggle="tab"><i class="icofont-star"></i><span>Weekly Deals</span></a>
                                </li>
                            @endif

                            @if ($monthly_deals_products->isNotEmpty())
                                <li><a href="#monthly-deals" class="tab-link {{ $tab_link[2] }}"
                                        data-bs-toggle="tab"><i class="icofont-sale-discount"></i><span>Monthly
                                            Deals</span></a></li>
                            @endif

                        </ul>

                    </div>

                </div>

                @if ($daily_deals_products->isNotEmpty())
                    <div class="tab-pane fade {{ $tab_pane[0] }}" id="daily-deals">

                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                            @foreach ($daily_deals_products as $product)
                                <div class="col">

                                    <div class="product-card">

                                        <div class="product-media">

                                            <div class="product-label">

                                                @if (strtotime($product->created_at) > strtotime('-15 day'))
                                                    <label class="label-text new">New</label>
                                                @endif

                                                @if ($product->saletype == '1')
                                                    <label class="label-text feat">Featured</label>
                                                @elseif($product->saletype == '2')
                                                    <label class="label-text sale">Best Selling</label>
                                                @endif

                                            </div>



                                            @php
                                                
                                                if (Auth::check()) {
                                                    $wishlist_data = $wishlist_model->where(['user_id' => Auth::id(), 'product_id' => $product->id, 'variant_index' => '0'])->first();
                                                }
                                                
                                            @endphp

                                            @if (!empty($wishlist_data))
                                                <button class="product-wish wish active" title="Remove From Wishlist"
                                                    id="wishlist_btn_{{ $product->id }}"
                                                    onclick="add_remove_wishlist_item({{ $product->id }}, 0, 0);"><i
                                                        class="fas fa-heart"></i></button>
                                            @else
                                                <button class="product-wish wish" title="Add To Wishlist"
                                                    id="wishlist_btn_{{ $product->id }}"
                                                    onclick="add_remove_wishlist_item({{ $product->id }}, 0, 0);"><i
                                                        class="fas fa-heart"></i></button>
                                            @endif



                                            <a class="product-image" 
                                                href="{{ url('/') }}/product/{{ $product->slug }}"><img
                                                    src="{{ asset($product->featuredimage) }}"></a>

                                            <div class="product-widget">

                                                @if (!empty($product->video_url))
                                                    <a title="Product Video" href="{{ $product->video_url }}"
                                                        class="venobox fas fa-play" data-autoplay="true"
                                                        data-vbtype="video"></a>
                                                @endif

                                                {{-- <a title="Product View" href="javascript:void(0);" class="fas fa-eye" data-bs-toggle="modal" data-bs-target="#product-view"></a> --}}

                                            </div>

                                        </div>

                                        @if ($product->product_type == '1')
                                            <div class="product-content">

                                            @php
                                            $avg_review = $review_controller->avg_star($product->id);
                                            @endphp
                                            <div class="product-rating">

                                                <i class="{{($avg_review['avg'] >= 1) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 2) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 3) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 4) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 5) ? 'active icofont-star' : 'icofont-star'}}"></i>

                                                <a href="javascript:void(0);">({{$avg_review['total_review']}})</a>

                                            </div>
                                                {{-- <div class="product-rating">

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="icofont-star"></i>

                                                    <a href="javascript:void(0);">(4)</a>

                                                </div> --}}

                                                <h6 class="product-name">

                                                    <a
                                                     href="{{ url('/') }}/product/{{ $product->slug }}">{{ substr($product->name, 0, 50) }}</a>

                                                </h6>

                                                <h6 class="product-price">

                                                    @if (!empty($product->discounted_price))
                                                        <del>{!! Helper::ccurrency() !!}{{ $product->mrp }}</del>

                                                        <span>{!! Helper::ccurrency() !!}{{ $product->discounted_price }}<small>/Item</small></span>
                                                    @else
                                                        <span>{!! Helper::ccurrency() !!}{{ $product->mrp }}<small>/Item</small></span>
                                                    @endif

                                                </h6>

                                                <button class="product-add-cart" title="Add To Cart"
                                                    onclick="add_to_cart({{ $product->id }}, 0);"><i
                                                        class="fas fa-shopping-basket"></i><span>Add</span></button>

                                            </div>
                                        @else
                                            @php
                                                
                                                $mrp = json_decode($product->mrp);
                                                
                                                $discounted_price = json_decode($product->discounted_price);
                                                
                                                $specifications = json_decode($product->specifications);
                                                
                                            @endphp



                                            <div class="product-content">

                                                {{-- <div class="product-rating">

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="icofont-star"></i>

                                                    <a href="javascript:void(0);">(4)</a>

                                                </div> --}}

                                                <h6 class="product-name">

                                                    <a  href="{{ url('/') }}/product/{{ $product->slug }}">{{ substr($product->name, 0, 50) }}
                                                        ({{ $specifications[0] }})
                                                    </a>

                                                </h6>

                                                <h6 class="product-price">

                                                    @if (!empty($discounted_price[0]))
                                                        <del>{!! Helper::ccurrency() !!}{{ $mrp[0] }}</del>

                                                        <span>{!! Helper::ccurrency() !!}{{ $discounted_price[0] }}<small>/Item</small></span>
                                                    @else
                                                        <span>{!! Helper::ccurrency() !!}{{ $mrp[0] }}<small>/Item</small></span>
                                                    @endif

                                                </h6>

                                                <button class="product-add-cart" title="Add To Cart"
                                                    onclick="add_to_cart({{ $product->id }}, 0);"><i
                                                        class="fas fa-shopping-basket"></i><span>Add</span></button>

                                            </div>
                                        @endif

                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>
                @endif

                @if ($weekly_deals_products->isNotEmpty())
                    <div class="tab-pane fade {{ $tab_pane[1] }}" id="weekly-deals">

                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                            @foreach ($weekly_deals_products as $product)
                                <div class="col">

                                    <div class="product-card">

                                        <div class="product-media">

                                            <div class="product-label">

                                                @if (strtotime($product->created_at) > strtotime('-15 day'))
                                                    <label class="label-text new">New</label>
                                                @endif

                                                @if ($product->saletype == '1')
                                                    <label class="label-text feat">Featured</label>
                                                @elseif($product->saletype == '2')
                                                    <label class="label-text sale">Best Selling</label>
                                                @endif

                                            </div>



                                            @php
                                                
                                                if (Auth::check()) {
                                                    $wishlist_data = $wishlist_model->where(['user_id' => Auth::id(), 'product_id' => $product->id, 'variant_index' => '0'])->first();
                                                }
                                                
                                            @endphp

                                            @if (!empty($wishlist_data))
                                                <button class="product-wish wish active" title="Remove From Wishlist"
                                                    id="wishlist_btn_{{ $product->id }}"
                                                    onclick="add_remove_wishlist_item({{ $product->id }}, 0, 0);"><i
                                                        class="fas fa-heart"></i></button>
                                            @else
                                                <button class="product-wish wish" title="Add To Wishlist"
                                                    id="wishlist_btn_{{ $product->id }}"
                                                    onclick="add_remove_wishlist_item({{ $product->id }}, 0, 0);"><i
                                                        class="fas fa-heart"></i></button>
                                            @endif



                                            <a class="product-image" 
                                                href="{{ url('/') }}/product/{{ $product->slug }}"><img
                                                    src="{{ asset($product->featuredimage) }}"></a>

                                            <div class="product-widget">

                                                @if (!empty($product->video_url))
                                                    <a title="Product Video" href="{{ $product->video_url }}"
                                                        class="venobox fas fa-play" data-autoplay="true"
                                                        data-vbtype="video"></a>
                                                @endif

                                                {{-- <a title="Product View" href="javascript:void(0);" class="fas fa-eye" data-bs-toggle="modal" data-bs-target="#product-view"></a> --}}

                                            </div>

                                        </div>

                                        @if ($product->product_type == '1')
                                            <div class="product-content">

                                            @php
                                            $avg_review = $review_controller->avg_star($product->id);
                                            @endphp
                                            <div class="product-rating">

                                                <i class="{{($avg_review['avg'] >= 1) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 2) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 3) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 4) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 5) ? 'active icofont-star' : 'icofont-star'}}"></i>

                                                <a href="javascript:void(0);">({{$avg_review['total_review']}})</a>

                                            </div>

                                                {{-- <div class="product-rating">

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="icofont-star"></i>

                                                    <a href="javascript:void(0);">(4)</a>

                                                </div> --}}

                                                <h6 class="product-name">

                                                    <a
                                                     href="{{ url('/') }}/product/{{ $product->slug }}">{{ substr($product->name, 0, 50) }}</a>

                                                </h6>

                                                <h6 class="product-price">

                                                    @if (!empty($product->discounted_price))
                                                        <del>{!! Helper::ccurrency() !!}{{ $product->mrp }}</del>

                                                        <span>{!! Helper::ccurrency() !!}{{ $product->discounted_price }}<small>/Item</small></span>
                                                    @else
                                                        <span>{!! Helper::ccurrency() !!}{{ $product->mrp }}<small>/Item</small></span>
                                                    @endif

                                                </h6>

                                                <button class="product-add-cart" title="Add To Cart"
                                                    onclick="add_to_cart({{ $product->id }}, 0);"><i
                                                        class="fas fa-shopping-basket"></i><span>Add</span></button>

                                            </div>
                                        @else
                                            @php
                                                
                                                $mrp = json_decode($product->mrp);
                                                
                                                $discounted_price = json_decode($product->discounted_price);
                                                
                                                $specifications = json_decode($product->specifications);
                                                
                                            @endphp



                                            <div class="product-content">

                                                {{-- <div class="product-rating">

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="icofont-star"></i>

                                                    <a href="javascript:void(0);">(4)</a>

                                                </div> --}}

                                                <h6 class="product-name">

                                                    <a  href="{{ url('/') }}/product/{{ $product->slug }}">{{ substr($product->name, 0, 50) }}
                                                        ({{ $specifications[0] }})
                                                    </a>

                                                </h6>

                                                <h6 class="product-price">

                                                    @if (!empty($discounted_price[0]))
                                                        <del>{!! Helper::ccurrency() !!}{{ $mrp[0] }}</del>

                                                        <span>{!! Helper::ccurrency() !!}{{ $discounted_price[0] }}<small>/Item</small></span>
                                                    @else
                                                        <span>{!! Helper::ccurrency() !!}{{ $mrp[0] }}<small>/Item</small></span>
                                                    @endif

                                                </h6>

                                                <button class="product-add-cart" title="Add To Cart"
                                                    onclick="add_to_cart({{ $product->id }}, 0);"><i
                                                        class="fas fa-shopping-basket"></i><span>Add</span></button>

                                            </div>
                                        @endif

                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>
                @endif

                @if ($monthly_deals_products->isNotEmpty())
                    <div class="tab-pane fade {{ $tab_pane[2] }}" id="monthly-deals">

                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                            @foreach ($monthly_deals_products as $product)
                                <div class="col">

                                    <div class="product-card">

                                        <div class="product-media">

                                            <div class="product-label">

                                                @if (strtotime($product->created_at) > strtotime('-15 day'))
                                                    <label class="label-text new">New</label>
                                                @endif

                                                @if ($product->saletype == '1')
                                                    <label class="label-text feat">Featured</label>
                                                @elseif($product->saletype == '2')
                                                    <label class="label-text sale">Best Selling</label>
                                                @endif

                                            </div>



                                            @php
                                                
                                                if (Auth::check()) {
                                                    $wishlist_data = $wishlist_model->where(['user_id' => Auth::id(), 'product_id' => $product->id, 'variant_index' => '0'])->first();
                                                }
                                                
                                            @endphp

                                            @if (!empty($wishlist_data))
                                                <button class="product-wish wish active" title="Remove From Wishlist"
                                                    id="wishlist_btn_{{ $product->id }}"
                                                    onclick="add_remove_wishlist_item({{ $product->id }}, 0, 0);"><i
                                                        class="fas fa-heart"></i></button>
                                            @else
                                                <button class="product-wish wish" title="Add To Wishlist"
                                                    id="wishlist_btn_{{ $product->id }}"
                                                    onclick="add_remove_wishlist_item({{ $product->id }}, 0, 0);"><i
                                                        class="fas fa-heart"></i></button>
                                            @endif



                                            <a class="product-image" 
                                                href="{{ url('/') }}/product/{{ $product->slug }}"><img
                                                    src="{{ asset($product->featuredimage) }}"></a>

                                            <div class="product-widget">

                                                @if (!empty($product->video_url))
                                                    <a title="Product Video" href="{{ $product->video_url }}"
                                                        class="venobox fas fa-play" data-autoplay="true"
                                                        data-vbtype="video"></a>
                                                @endif

                                                {{-- <a title="Product View" href="javascript:void(0);" class="fas fa-eye" data-bs-toggle="modal" data-bs-target="#product-view"></a> --}}

                                            </div>

                                        </div>

                                        @if ($product->product_type == '1')
                                            <div class="product-content">

                                            @php
                                            $avg_review = $review_controller->avg_star($product->id);
                                            @endphp
                                            <div class="product-rating">

                                                <i class="{{($avg_review['avg'] >= 1) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 2) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 3) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 4) ? 'active icofont-star' : 'icofont-star'}}"></i>
                                                <i class="{{($avg_review['avg'] >= 5) ? 'active icofont-star' : 'icofont-star'}}"></i>

                                                <a href="javascript:void(0);">({{$avg_review['total_review']}})</a>

                                            </div>
                                                {{-- <div class="product-rating">

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="icofont-star"></i>

                                                    <a href="javascript:void(0);">(4)</a>

                                                </div> --}}

                                                <h6 class="product-name">

                                                    <a
                                                     href="{{ url('/') }}/product/{{ $product->slug }}">{{ substr($product->name, 0, 50) }}</a>

                                                </h6>

                                                <h6 class="product-price">

                                                    @if (!empty($product->discounted_price))
                                                        <del>{!! Helper::ccurrency() !!}{{ $product->mrp }}</del>

                                                        <span>{!! Helper::ccurrency() !!}{{ $product->discounted_price }}<small>/Item</small></span>
                                                    @else
                                                        <span>{!! Helper::ccurrency() !!}{{ $product->mrp }}<small>/Item</small></span>
                                                    @endif

                                                </h6>

                                                <button class="product-add-cart" title="Add To Cart"
                                                    onclick="add_to_cart({{ $product->id }}, 0);"><i
                                                        class="fas fa-shopping-basket"></i><span>Add</span></button>

                                            </div>
                                        @else
                                            @php
                                                
                                                $mrp = json_decode($product->mrp);
                                                
                                                $discounted_price = json_decode($product->discounted_price);
                                                
                                                $specifications = json_decode($product->specifications);
                                                
                                            @endphp



                                            <div class="product-content">

                                                {{-- <div class="product-rating">

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="active icofont-star"></i>

                                                    <i class="icofont-star"></i>

                                                    <a href="javascript:void(0);">(4)</a>

                                                </div> --}}

                                                <h6 class="product-name">

                                                    <a  href="{{ url('/') }}/product/{{ $product->slug }}">{{ substr($product->name, 0, 50) }}
                                                        ({{ $specifications[0] }})
                                                    </a>

                                                </h6>

                                                <h6 class="product-price">

                                                    @if (!empty($discounted_price[0]))
                                                        <del>{!! Helper::ccurrency() !!}{{ $mrp[0] }}</del>

                                                        <span>{!! Helper::ccurrency() !!}{{ $discounted_price[0] }}<small>/Item</small></span>
                                                    @else
                                                        <span>{!! Helper::ccurrency() !!}{{ $mrp[0] }}<small>/Item</small></span>
                                                    @endif

                                                </h6>

                                                <button class="product-add-cart" title="Add To Cart"
                                                    onclick="add_to_cart({{ $product->id }}, 0);"><i
                                                        class="fas fa-shopping-basket"></i><span>Add</span></button>

                                            </div>
                                        @endif

                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>
                @endif

                <div class="row">

                    <div class="col-lg-12">

                        <div class="section-btn-25">

                            <a href="{{ url('/') }}/products-list/best-deals" class="btn btn-outline"><i
                                    class="fas fa-eye"></i><span>Show More</span></a>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    @endif



    @if ($brands_list->isNotEmpty())
        <section class="section brand-part">

            <div class="container">

                <div class="row">

                    <div class="col-12">

                        <div class="section-heading">

                            <h2>Shop By Brands</h2>

                        </div>

                    </div>

                </div>

                <div class="brand-slider slider-arrow">

                    @foreach ($brands_list as $brand)
                        <div class="brand-wrap">

                            <div class="brand-media">

                                <img src="{{ asset($brand->logo) }}">

                                <div class="brand-overlay">

                                    <a href="{{ url('/') }}/products-list/{{ $brand->slug }}"><i
                                            class="fas fa-link"></i></a>

                                </div>

                            </div>

                            @php $total_items = $product_model->whereRaw("FIND_IN_SET($brand->id, categories)")->count(); @endphp

                            <div class="brand-meta">

                                <h4>{{ $brand->slug }}</h4>
                                <p>({{ $total_items }} Items)</p>

                            </div>

                        </div>
                    @endforeach

                </div>

                {{-- <div class="row">

                    <div class="col-lg-12">

                        <div class="section-btn-50">

                            <a href="{{url('/')}}/products-list/brands" class="btn btn-outline"><i class="fas fa-eye"></i><span>Show More</span></a>

                        </div>

                    </div>

                </div> --}}

            </div>

        </section>
    @endif



    <section class="section testimonial-part">

        <div class="container">

            <div class="row">

                <div class="col-12">

                    <div class="section-heading">

                        <h2>client's feedback</h2>

                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-lg-12">

                    <div class="testimonial-slider slider-arrow">

                        <div class="testimonial-card"><i class="fas fa-quote-left"></i>

                            <p>পন্যেরআলো  ওয়েব সাইটে নিজের প্রোডাক্ট বিক্রি করতে পারছি ও লাভবান হচ্ছি। পাশাপাশি এখান থেকে যে কোন ধরণের পন্য কিন্তেও পারছি, চাইলে আপনিও এখানে কেনাবেচা করতে পারেন।</p>

                            <h5>খায়রুল মামুন মিন্টু</h5>

                            <ul>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                            </ul><img src="{{ asset('assests/web/images/avatar/01.jpg') }}" alt="testimonial">

                        </div>

                        <div class="testimonial-card"><i class="fas fa-quote-left"></i>

                            <p>আমি অনেক জায়গায় আমার নিজের পন্য বিক্রি করেছি, কিন্তু পন্যেরআলোতে অধীক মুনাফা পাচ্ছি।</p>

                            <h5>আইমুন আক্তার</h5>

                            <ul>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                            </ul><img src="{{ asset('assests/web/images/avatar/02.jpg') }}" alt="testimonial">

                        </div>

                        <div class="testimonial-card"><i class="fas fa-quote-left"></i>

                            <p>আমি পন্যেরআলো ওয়েব সাইটে নতুন, কিন্ত এখানে নিজের তৈরি করা পন্য বিক্রি করতে পারছি ও পছন্দমত পন্য কিনতেও পারছি সুলভ মূল্যে।</p>

                            <h5>সাথী আক্তার</h5>

                            <ul>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                            </ul><img src="{{ asset('assests/web/images/avatar/03.jpg') }}" alt="testimonial">

                        </div>

                        <div class="testimonial-card"><i class="fas fa-quote-left"></i>

                            <p>আমি এখানে আমার নিজের পন্য বিক্রি করতে পারছি ও লাভবান হচ্ছি। আমার কাছে এটি আনন্দের।</p>

                            <h5>সখিনা আক্তার</h5>

                            <ul>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                                <li class="fas fa-star"></li>

                            </ul><img src="{{ asset('assests/web/images/avatar/04.jpg') }}" alt="testimonial">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- <section class="section blog-part">

        <div class="container">

            <div class="row">

                <div class="col-12">

                    <div class="section-heading">

                        <h2>Read our articles</h2>

                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-lg-12">

                    <div class="blog-slider slider-arrow">

                        <div class="blog-card">

                            <div class="blog-media">

                                <a class="blog-img" href="#"><img src="{{asset('assests/web/images/blog/01.jpg')}}" alt="blog"></a>

                            </div>

                            <div class="blog-content">

                                <ul class="blog-meta">

                                    <li><i class="fas fa-user"></i><span>admin</span></li>

                                    <li><i class="fas fa-calendar-alt"></i><span>february 02, 2021</span></li>

                                </ul>

                                <h4 class="blog-title">

                                    <a href="blog-details.html">Voluptate blanditiis provident Lorem ipsum dolor sit amet</a>

                                </h4>

                                <p class="blog-desc">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Alias autem recusandae deleniti nam dignissimos sequi ... </p>

                                <a class="blog-btn" href="#"><span>read more</span><i class="icofont-arrow-right"></i></a>

                            </div>

                        </div>

                        <div class="blog-card">

                            <div class="blog-media">

                                <a class="blog-img" href="#"><img src="{{asset('assests/web/images/blog/02.jpg')}}" alt="blog"></a>

                            </div>

                            <div class="blog-content">

                                <ul class="blog-meta">

                                    <li><i class="fas fa-user"></i><span>admin</span></li>

                                    <li><i class="fas fa-calendar-alt"></i><span>february 02, 2021</span></li>

                                </ul>

                                <h4 class="blog-title">

                                    <a href="blog-details.html">Voluptate blanditiis provident Lorem ipsum dolor sit amet</a>

                                </h4>

                                <p class="blog-desc">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Alias autem recusandae deleniti nam dignissimos sequi ... </p>

                                <a class="blog-btn" href="#"><span>read more</span><i class="icofont-arrow-right"></i></a>

                            </div>

                        </div>

                        <div class="blog-card">

                            <div class="blog-media">

                                <a class="blog-img" href="#"><img src="{{asset('assests/web/images/blog/03.jpg')}}" alt="blog"></a>

                            </div>

                            <div class="blog-content">

                                <ul class="blog-meta">

                                    <li><i class="fas fa-user"></i><span>admin</span></li>

                                    <li><i class="fas fa-calendar-alt"></i><span>february 02, 2021</span></li>

                                </ul>

                                <h4 class="blog-title">

                                    <a href="blog-details.html">Voluptate blanditiis provident Lorem ipsum dolor sit amet</a>

                                </h4>

                                <p class="blog-desc">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Alias autem recusandae deleniti nam dignissimos sequi ... </p>

                                <a class="blog-btn" href="#"><span>read more</span><i class="icofont-arrow-right"></i></a>

                            </div>

                        </div>

                        <div class="blog-card">

                            <div class="blog-media">

                                <a class="blog-img" href="#"><img src="{{asset('assests/web/images/blog/04.jpg')}}" alt="blog"></a>

                            </div>

                            <div class="blog-content">

                                <ul class="blog-meta">

                                    <li><i class="fas fa-user"></i><span>admin</span></li>

                                    <li><i class="fas fa-calendar-alt"></i><span>february 02, 2021</span></li>

                                </ul>

                                <h4 class="blog-title">

                                    <a href="blog-details.html">Voluptate blanditiis provident Lorem ipsum dolor sit amet</a>

                                </h4>

                                <p class="blog-desc">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Alias autem recusandae deleniti nam dignissimos sequi ... </p>

                                <a class="blog-btn" href="#"><span>read more</span><i class="icofont-arrow-right"></i></a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-lg-12">

                    <div class="section-btn-25"><a href="blog-grid.html" class="btn btn-outline"><i class="fas fa-eye"></i><span>view all blog</span></a></div>

                </div>

            </div>

        </div>

    </section> --}}



@endsection
