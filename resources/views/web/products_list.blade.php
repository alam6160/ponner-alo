@extends('web/common')
@section('content')

    <div class="modal fade" id="product-view">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close icofont-close" data-bs-dismiss="modal"></button>
                <div class="product-view">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="view-gallery">
                                <div class="view-label-group">
                                    <label class="view-label new">new</label>
                                    <label class="view-label off">-10%</label>
                                </div>
                                <ul class="preview-slider slider-arrow">
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                </ul>
                                <ul class="thumb-slider">
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                    <li><img src="{{asset('assests/web/images/product/01.jpg')}}"></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="view-details">
                                <h3 class="view-name">
                                    <a href="product-video.html">existing product name</a>
                                </h3>
                                <div class="view-meta">
                                    <p>SKU:<span>1234567</span></p>
                                    <p>BRAND:<a href="#">radhuni</a></p>
                                </div>
                                <div class="view-rating">
                                    <i class="active icofont-star"></i>
                                    <i class="active icofont-star"></i>
                                    <i class="active icofont-star"></i>
                                    <i class="active icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <a href="product-video.html">(3 reviews)</a>
                                </div>
                                <h3 class="view-price">
                                    <del>$38.00</del><span>$24.00<small>/per kilo</small></span>
                                </h3>
                                <p class="view-desc">Lorem ipsum dolor sit amet consectetur adipisicing elit non tempora magni repudiandae sint suscipit tempore quis maxime explicabo veniam eos reprehenderit fuga</p>
                                <div class="view-list-group">
                                    <label class="view-list-title">tags:</label>
                                    <ul class="view-tag-list">
                                        <li><a href="#">organic</a></li>
                                        <li><a href="#">vegetable</a></li>
                                        <li><a href="#">chilis</a></li>
                                    </ul>
                                </div>
                                <div class="view-list-group">
                                    <label class="view-list-title">Share:</label>
                                    <ul class="view-share-list">
                                        <li><a href="#" class="icofont-facebook" title="Facebook"></a></li>
                                        <li><a href="#" class="icofont-twitter" title="Twitter"></a></li>
                                        <li><a href="#" class="icofont-linkedin" title="Linkedin"></a></li>
                                        <li><a href="#" class="icofont-instagram" title="Instagram"></a></li>
                                    </ul>
                                </div>
                                <div class="view-add-group">
                                    <button class="product-add" title="Add to Cart">
                                        <i class="fas fa-shopping-basket"></i>
                                        <span>add to cart</span>
                                    </button>
                                    <div class="product-action">
                                        <button class="action-minus" title="Quantity Minus"><i class="icofont-minus"></i></button>
                                        <input class="action-input" title="Quantity Number" type="text" name="quantity" value="1">
                                        <button class="action-plus" title="Quantity Plus"><i class="icofont-plus"></i></button>
                                    </div>
                                </div>
                                <div class="view-action-group">
                                    <a class="view-wish wish" href="#" title="Add Your Wishlist"><i class="icofont-heart"></i><span>add to wish</span></a>
                                    <a class="view-compare" href="compare.html" title="Compare This Item"><i class="fas fa-random"></i><span>Compare This</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="inner-section single-banner" style="background: url({{asset('assests/web/images/single-banner.jpg')}}) no-repeat center;">
        <div class="container">
            <h2>{{$title}}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
            </ol>
        </div>
    </section>

    <section class="inner-section shop-part">
        <div class="container">
            <div class="row content-reverse">
                {{-- <div class="col-lg-3">
                    <div class="shop-widget-promo"><a href="#"><img src="images/promo/shop/01.jpg" alt="promo"></a></div>
                    <div class="shop-widget">
                        <h6 class="shop-widget-title">Filter by Price</h6>
                        <form>
                            <div class="shop-widget-group"><input type="text" placeholder="Min - 00"><input type="text" placeholder="Max - 5k"></div><button class="shop-widget-btn"><i class="fas fa-search"></i><span>search</span></button>
                        </form>
                    </div>
                    <div class="shop-widget">
                        <h6 class="shop-widget-title">Filter by Rating</h6>
                        <form>
                            <ul class="shop-widget-list">
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="feat1"><label for="feat1"><i class="fas fa-star active"></i><i class="fas fa-star active"></i><i class="fas fa-star active"></i><i class="fas fa-star active"></i><i class="fas fa-star active"></i></label></div><span class="shop-widget-number">(13)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="feat2"><label for="feat2"><i class="fas fa-star active"></i><i class="fas fa-star active"></i><i class="fas fa-star active"></i><i class="fas fa-star active"></i><i class="fas fa-star"></i></label></div><span class="shop-widget-number">(28)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="feat3"><label for="feat3"><i class="fas fa-star active"></i><i class="fas fa-star active"></i><i class="fas fa-star active"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></label></div><span class="shop-widget-number">(35)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="feat4"><label for="feat4"><i class="fas fa-star active"></i><i class="fas fa-star active"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></label></div><span class="shop-widget-number">(47)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="feat5"><label for="feat5"><i class="fas fa-star active"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></label></div><span class="shop-widget-number">(59)</span>
                                </li>
                            </ul><button class="shop-widget-btn"><i class="far fa-trash-alt"></i><span>clear filter</span></button>
                        </form>
                    </div>
                    <div class="shop-widget">
                        <h6 class="shop-widget-title">Filter by Tag</h6>
                        <form>
                            <ul class="shop-widget-list">
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="tag1"><label for="tag1">new items</label></div><span class="shop-widget-number">(13)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="tag2"><label for="tag2">sale items</label></div><span class="shop-widget-number">(28)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="tag3"><label for="tag3">rating items</label></div><span class="shop-widget-number">(35)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="tag4"><label for="tag4">feature items</label></div><span class="shop-widget-number">(47)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="tag5"><label for="tag5">discount items</label></div><span class="shop-widget-number">(59)</span>
                                </li>
                            </ul><button class="shop-widget-btn"><i class="far fa-trash-alt"></i><span>clear filter</span></button>
                        </form>
                    </div>
                    <div class="shop-widget">
                        <h6 class="shop-widget-title">Filter by Brand</h6>
                        <form><input class="shop-widget-search" type="text" placeholder="Search...">
                            <ul class="shop-widget-list shop-widget-scroll">
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="brand1"><label for="brand1">mari gold</label></div><span class="shop-widget-number">(13)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="brand2"><label for="brand2">tredar</label></div><span class="shop-widget-number">(28)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="brand3"><label for="brand3">keya</label></div><span class="shop-widget-number">(35)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="brand4"><label for="brand4">diamond</label></div><span class="shop-widget-number">(47)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="brand5"><label for="brand5">lilly's</label></div><span class="shop-widget-number">(59)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="brand6"><label for="brand6">fremant</label></div><span class="shop-widget-number">(64)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="brand7"><label for="brand7">avocads</label></div><span class="shop-widget-number">(77)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="brand8"><label for="brand8">boroclas</label></div><span class="shop-widget-number">(85)</span>
                                </li>
                            </ul><button class="shop-widget-btn"><i class="far fa-trash-alt"></i><span>clear filter</span></button>
                        </form>
                    </div>
                    <div class="shop-widget">
                        <h6 class="shop-widget-title">Filter by Category</h6>
                        <form><input class="shop-widget-search" type="text" placeholder="Search...">
                            <ul class="shop-widget-list shop-widget-scroll">
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="cate1"><label for="cate1">vegetables</label></div><span class="shop-widget-number">(13)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="cate2"><label for="cate2">groceries</label></div><span class="shop-widget-number">(28)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="cate3"><label for="cate3">fruits</label></div><span class="shop-widget-number">(35)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="cate4"><label for="cate4">dairy farm</label></div><span class="shop-widget-number">(47)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="cate5"><label for="cate5">sea foods</label></div><span class="shop-widget-number">(59)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="cate6"><label for="cate6">diet foods</label></div><span class="shop-widget-number">(64)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="cate7"><label for="cate7">dry foods</label></div><span class="shop-widget-number">(77)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="cate8"><label for="cate8">fast foods</label></div><span class="shop-widget-number">(85)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="cate9"><label for="cate9">drinks</label></div><span class="shop-widget-number">(92)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="cate10"><label for="cate10">coffee</label></div><span class="shop-widget-number">(21)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="cate11"><label for="cate11">meats</label></div><span class="shop-widget-number">(14)</span>
                                </li>
                                <li>
                                    <div class="shop-widget-content"><input type="checkbox" id="cate12"><label for="cate12">fishes</label></div><span class="shop-widget-number">(56)</span>
                                </li>
                            </ul><button class="shop-widget-btn"><i class="far fa-trash-alt"></i><span>clear filter</span></button>
                        </form>
                    </div>
                </div> --}}

                @if($products_list->isNotEmpty())
                    <div class="col-lg-12">
                        {{-- <div class="row">
                            <div class="col-lg-12">
                                <div class="top-filter">
                                    <div class="filter-show">
                                        <label class="filter-label">Show :</label>
                                        <select class="form-select filter-select">
                                            <option value="1">12</option>
                                            <option value="2">24</option>
                                            <option value="3">36</option>
                                        </select>
                                    </div>
                                    <div class="filter-short">
                                        <label class="filter-label">Short by :</label>
                                        <select class="form-select filter-select">
                                            <option selected>default</option>
                                            <option value="3">trending</option>
                                            <option value="1">featured</option>
                                            <option value="2">recommend</option>
                                        </select>
                                    </div>
                                    <div class="filter-action">
                                        <a href="shop-3column.html" title="Three Column"><i class="fas fa-th"></i></a>
                                        <a href="shop-2column.html" title="Two Column"><i class="fas fa-th-large"></i></a>
                                        <a href="shop-1column.html" title="One Column"><i class="fas fa-th-list"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4">
                            @foreach($products_list as $product)
                                <div class="col">
                                    <div class="product-card">
                                        <div class="product-media">
                                            <div class="product-label">
                                                @if(strtotime($product->created_at) > strtotime('-15 day'))
                                                    <label class="label-text new">New</label>
                                                @endif
                                                @if($product->saletype == '2')
                                                    <label class="label-text sale">Best Selling</label>
                                                @endif
                                            </div>

                                            @php
                                                if(Auth::check())
                                                {
                                                    $wishlist_data = $wishlist_model->where(['user_id' => Auth::id(), 'product_id' => $product->id, 'variant_index' => '0'])->first();
                                                }
                                            @endphp
                                            @if(!empty($wishlist_data))
                                                <button class="product-wish wish active" title="Remove From Wishlist" id="wishlist_btn_{{$product->id}}" onclick="add_remove_wishlist_item({{$product->id}}, 0, 0);"><i class="fas fa-heart"></i></button>
                                            @else
                                                <button class="product-wish wish" title="Add To Wishlist" id="wishlist_btn_{{$product->id}}" onclick="add_remove_wishlist_item({{$product->id}}, 0, 0);"><i class="fas fa-heart"></i></button>
                                            @endif

                                            <a  class="product-image" href="{{url('/')}}/product/{{$product->slug}}">
                                                <img src="{{asset($product->featuredimage)}}">
                                            </a>
                                            <div class="product-widget">
                                                @if(!empty($product->video_url))
                                                    <a  title="Product Video" href="{{$product->video_url}}" class="venobox fas fa-play" data-autoplay="true" data-vbtype="video"></a>
                                                @endif
                                                {{-- <a title="Product View" href="javascript:void(0);" class="fas fa-eye" data-bs-toggle="modal" data-bs-target="#product-view"></a> --}}
                                            </div>
                                        </div>
                                        @if($product->product_type == '1')
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
                                                    <a  href="{{url('/')}}/product/{{$product->slug}}">{{substr($product->name, 0, 50)}}</a>
                                                </h6>
                                                <h6 class="product-price">
                                                    @if(!empty($product->discounted_price))
                                                        <del>{!! Helper::ccurrency() !!}{{$product->mrp}}</del>
                                                        <span>{!! Helper::ccurrency() !!}{{$product->discounted_price}}<small>/Item</small></span>
                                                    @else
                                                        <span>{!! Helper::ccurrency() !!}{{$product->mrp}}<small>/Item</small></span>
                                                    @endif
                                                </h6>
                                                <button class="product-add-cart" title="Add To Cart" onclick="add_to_cart({{$product->id}}, 0);"><i class="fas fa-shopping-basket"></i><span>Add</span></button>
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
                                                    <a href="{{url('/')}}/product/{{$product->slug}}">{{substr($product->name, 0, 50)}} ({{$specifications[0]}})</a>
                                                </h6>
                                                <h6 class="product-price">
                                                    @if(!empty($discounted_price[0]))
                                                        <del>{!! Helper::ccurrency() !!}{{$mrp[0]}}</del>
                                                        <span>{!! Helper::ccurrency() !!}{{$discounted_price[0]}}<small>/Item</small></span>
                                                    @else
                                                        <span>{!! Helper::ccurrency() !!}{{$mrp[0]}}<small>/Item</small></span>
                                                    @endif
                                                </h6>
                                                <button class="product-add-cart" title="Add To Cart" onclick="add_to_cart({{$product->id}}, 0);"><i class="fas fa-shopping-basket"></i><span>Add</span></button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{$products_list->links()}}
                    </div>
                @endif

            </div>
        </div>
    </section>

@endsection