@push('style')
<link href="https://cdn.jsdelivr.net/npm/shareon@2/dist/shareon.min.css" rel="stylesheet">

@if (!empty($product_data))

    @php $product_images = explode(',', $product_data->images); @endphp
    @if (!empty($product_images))
        <meta property="og:image" content="{{ asset($product_data->featuredimage) }}" />
    @endif

    <meta property="og:title" content="{{ $product_data->name }}" />
    <meta property="og:description" content="{{ $product_data->short_desc }}" />   
@endif

@endpush

@extends('web/common')

@section('content')

    <section class="inner-section" style="margin-top:80px;">

        <div class="container">

            <div class="row">

                <div class="col-lg-6">

                    <div class="details-gallery">

                        <div class="details-label-group">

                            @if (strtotime($product_data->created_at) > strtotime('-15 day'))
                                <label class="details-label new">New</label>
                            @endif

                            @if ($product_data->saletype == '2')
                                <label class="details-label sale">Best Selling</label>
                            @endif

                            {{-- <label class="details-label off">-10%</label> --}}

                        </div>

                        @php
                            
                            $product_images = explode(',', $product_data->images);
                            
                        @endphp

                        @if (!empty($product_images))
                            <ul class="details-preview">

                                <li><img src="{{ asset($product_data->featuredimage) }}"></li>

                                @foreach ($product_images as $product_image)
                                    <li><img src="{{ asset($product_image) }}"></li>
                                @endforeach

                            </ul>

                            <ul class="details-thumb">

                                <li><img src="{{ asset($product_data->featuredimage) }}"></li>

                                @foreach ($product_images as $product_image)
                                    <li><img src="{{ asset($product_image) }}"></li>
                                @endforeach

                            </ul>
                        @endif

                    </div>

                </div>

                <div class="col-lg-6">

                    @if ($product_data->product_type == '1')

                        <div class="details-content">

                            <h3 class="details-name" style="font-weight:bold;">{{ $product_data->name }}</h3>

                            <div class="details-meta">

                                <p style="font-weight:bold;">SKU: <span>{{ $product_data->sku }}</span></p>

                                {{-- <p style="font-weight:bold;">Brand: <a href="javascript:void(0);">Motorola</a></p> --}}

                            </div>

                            
                                <div class="details-rating">

                                    <i class="{{ $avg['avg'] >= 1 ? 'active icofont-star' : 'icofont-star' }}"></i>
                                    <i class="{{ $avg['avg'] >= 2 ? 'active icofont-star' : 'icofont-star' }}"></i>
                                    <i class="{{ $avg['avg'] >= 3 ? 'active icofont-star' : 'icofont-star' }}"></i>
                                    <i class="{{ $avg['avg'] >= 4 ? 'active icofont-star' : 'icofont-star' }}"></i>
                                    <i class="{{ $avg['avg'] >= 5 ? 'active icofont-star' : 'icofont-star' }}"></i>
                                    <a>({{ $avg['total_review'] }})</a>
                                </div>

                                {{-- share --}}
                                <h6>Share with</h6>
                                <div class="shareon">
                                    <a class="facebook"></a>
                                    <a class="linkedin"></a>
                                    {{-- <a class="mastodon"></a> --}}
                                    <!-- FB App ID is required for the Messenger button to function -->
                                    {{-- <a class="messenger" data-fb-app-id="0123456789012345"></a> --}}
                                    {{-- <a class="odnoklassniki"></a> --}}
                                    {{-- <a class="pinterest"></a> --}}
                                    {{-- <a class="pocket"></a> --}}
                                    {{-- <a class="reddit"></a> --}}
                                    <a class="telegram"></a>
                                    <a class="twitter"></a>
                                    {{-- <a class="viber"></a> --}}
                                    {{-- <a class="vkontakte"></a> --}}
                                    <a class="whatsapp"></a>
                                </div>

                                
                            <h3 class="details-price">

                                @if (!empty($product_data->discounted_price))
                                    <del>{!! Helper::ccurrency() !!}{{ $product_data->mrp }}</del>

                                    <span>{!! Helper::ccurrency() !!}{{ $product_data->discounted_price }}<small>/Unit</small></span>
                                @else
                                    <span>{!! Helper::ccurrency() !!}{{ $product_data->mrp }}<small>/Unit</small></span>
                                @endif

                            </h3>

                            <p class="details-desc">{!! nl2br($product_data->short_desc) !!}</p>

                            {{-- <div class="details-list-group">

                                <label class="details-list-title">Tags:</label>

                                <ul class="details-tag-list">

                                    <li><a href="javascript:void(0);">Fruits</a></li>

                                    <li><a href="javascript:void(0);">Chilis</a></li>

                                </ul>

                            </div> --}}

                            {{-- <div class="details-list-group">

                                <label class="details-list-title">Share:</label>

                                <ul class="details-share-list">

                                    <li><a href="javascript:void(0);" class="icofont-facebook" title="Facebook"></a></li>

                                    <li><a href="javascript:void(0);" class="icofont-twitter" title="Twitter"></a></li>

                                    <li><a href="javascript:void(0);" class="icofont-linkedin" title="Linkedin"></a></li>

                                    <li><a href="javascript:void(0);" class="icofont-instagram" title="Instagram"></a></li>

                                </ul>

                            </div> --}}

                            <div class="details-action-group">

                                <button class="product-add-cart" title="Add To Cart" id="cart_btn"
                                    onclick="add_to_cart({{ $product_data->id }}, 0);"><i
                                        class="fas fa-shopping-basket"></i><span>Add To Cart</span></button>

                                @php
                                    
                                    if (Auth::check()) {
                                        $wishlist_data = $wishlist_model->where(['user_id' => Auth::id(), 'product_id' => $product_data->id, 'variant_index' => '0'])->first();
                                    }
                                    
                                @endphp

                                @if (!empty($wishlist_data))
                                    <button class="product-add-cart" title="Remove From Wishlist" id="wishlist_btn"
                                        onclick="add_remove_wishlist_item({{ $product_data->id }}, 0, 1);"><i
                                            class="icofont-heart"></i><span id="wishlist_tag">Remove From
                                            Wishlist</span></button>
                                @else
                                    <button class="product-add-cart" title="Add To Wishlist" id="wishlist_btn"
                                        onclick="add_remove_wishlist_item({{ $product_data->id }}, 0, 1);"><i
                                            class="icofont-heart"></i><span id="wishlist_tag">Add To
                                            Wishlist</span></button>
                                @endif

                            </div>

                        </div>
                    @else
                        @php
                            
                            $mrp = json_decode($product_data->mrp);
                            
                            $discounted_price = json_decode($product_data->discounted_price);
                            
                            $specifications_1 = explode(',', $product_data->specifications_1);
                            
                            $specifications_2 = explode(',', $product_data->specifications_2);
                            
                            $specifications_3 = explode(',', $product_data->specifications_3);
                            
                            $specifications = json_decode($product_data->specifications);
                            
                            $sku = json_decode($product_data->sku);
                            
                        @endphp



                        <div class="details-content">

                            <h3 class="details-name" style="font-weight:bold;">{{ $product_data->name }} (<span
                                    id="specifications">{{ $specifications[0] }}</span>)</h3>

                            <div class="details-meta">

                                <p style="font-weight:bold;">SKU: <span id="sku">{{ $sku[0] }}</span></p>



                            </div>

                            <div class="details-rating">
                                <i class="active icofont-star"></i>
                                <i class="active icofont-star"></i>
                                <i class="active icofont-star"></i>
                                <i class="active icofont-star"></i>
                                <i class="icofont-star"></i>
                                <a href="javascript:void(0);">(4)</a>
                            </div>

                            <h3 class="details-price" id="prices">

                                @if (!empty($discounted_price[0]))
                                    <del>{!! Helper::ccurrency() !!}{{ $mrp[0] }}</del>

                                    <span>{!! Helper::ccurrency() !!}{{ $discounted_price[0] }}<small>/Unit</small></span>
                                @else
                                    <span>{!! Helper::ccurrency() !!}{{ $mrp[0] }}<small>/Unit</small></span>
                                @endif

                            </h3>

                            <div class="row">

                                @if (!empty($product_data->attribute_1))
                                    <div class="form-group col-md-4">

                                        <label class="form-label"
                                            style="font-weight:bold;">{{ $product_data->attribute_1 }}</label>

                                        <select class="form-select" id="attribute_1"
                                            onchange="switch_variant({{ $product_data->id }});">

                                            @foreach ($specifications_1 as $s1_value)
                                                <option value="{{ $s1_value }}">{{ $s1_value }}</option>
                                            @endforeach

                                        </select>

                                    </div>
                                @else
                                    <input type="hidden" id="attribute_1" value="" readonly>
                                @endif

                                @if (!empty($product_data->attribute_2))
                                    <div class="form-group col-md-4">

                                        <label class="form-label"
                                            style="font-weight:bold;">{{ $product_data->attribute_2 }}</label>

                                        <select class="form-select" id="attribute_2"
                                            onchange="switch_variant({{ $product_data->id }});">

                                            @foreach ($specifications_2 as $s2_value)
                                                <option value="{{ $s2_value }}">{{ $s2_value }}</option>
                                            @endforeach

                                        </select>

                                    </div>
                                @else
                                    <input type="hidden" id="attribute_2" value="" readonly>
                                @endif

                                @if (!empty($product_data->attribute_3))
                                    <div class="form-group col-md-4">

                                        <label class="form-label"
                                            style="font-weight:bold;">{{ $product_data->attribute_3 }}</label>

                                        <select class="form-select" id="attribute_3"
                                            onchange="switch_variant({{ $product_data->id }});">

                                            @foreach ($specifications_3 as $s3_value)
                                                <option value="{{ $s3_value }}">{{ $s3_value }}</option>
                                            @endforeach

                                        </select>

                                    </div>
                                @else
                                    <input type="hidden" id="attribute_3" value="" readonly>
                                @endif

                            </div>

                            <p class="details-desc">{!! nl2br($product_data->short_desc) !!}</p>

                            {{-- <div class="details-list-group">

                                <label class="details-list-title">Tags:</label>

                                <ul class="details-tag-list">

                                    <li><a href="javascript:void(0);">Fruits</a></li>

                                    <li><a href="javascript:void(0);">Chilis</a></li>

                                </ul>

                            </div> --}}

                            {{-- <div class="details-list-group">

                                <label class="details-list-title">Share:</label>

                                <ul class="details-share-list">

                                    <li><a href="javascript:void(0);" class="icofont-facebook" title="Facebook"></a></li>

                                    <li><a href="javascript:void(0);" class="icofont-twitter" title="Twitter"></a></li>

                                    <li><a href="javascript:void(0);" class="icofont-linkedin" title="Linkedin"></a></li>

                                    <li><a href="javascript:void(0);" class="icofont-instagram" title="Instagram"></a></li>

                                </ul>

                            </div> --}}

                            <div class="details-action-group">

                                <button class="product-add-cart" title="Add To Cart" id="cart_btn"
                                    onclick="add_to_cart({{ $product_data->id }}, 0);"><i
                                        class="fas fa-shopping-basket"></i><span>Add To Cart</span></button>

                                @php
                                    
                                    if (Auth::check()) {
                                        $wishlist_data = $wishlist_model->where(['user_id' => Auth::id(), 'product_id' => $product_data->id, 'variant_index' => '0'])->first();
                                    }
                                    
                                @endphp

                                @if (!empty($wishlist_data))
                                    <button class="product-add-cart" title="Remove From Wishlist" id="wishlist_btn"
                                        onclick="add_remove_wishlist_item({{ $product_data->id }}, 0, 1);"><i
                                            class="icofont-heart"></i><span id="wishlist_tag">Remove From
                                            Wishlist</span></button>
                                @else
                                    <button class="product-add-cart" title="Add To Wishlist" id="wishlist_btn"
                                        onclick="add_remove_wishlist_item({{ $product_data->id }}, 0, 1);"><i
                                            class="icofont-heart"></i><span id="wishlist_tag">Add To
                                            Wishlist</span></button>
                                @endif

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </section>

    <section class="inner-section">

        <div class="container">

            <div class="row">

                <div class="col-lg-12">

                    <ul class="nav nav-tabs">

                        <li><a href="#description" class="tab-link active" data-bs-toggle="tab">Descriptions</a></li>

                        {{-- <li><a href="#specification" class="tab-link" data-bs-toggle="tab">Specifications</a></li> --}}

                        <li id="review_list"><a href="#reviews" class="tab-link" data-bs-toggle="tab">Reviews</a></li>

                    </ul>

                </div>

            </div>

            <div class="tab-pane fade show active" id="description">

                <div class="row">

                    <div class="col-lg-12">

                        <div class="product-details-frame">

                            <div class="tab-descrip">

                                <p>{!! nl2br(html_entity_decode($product_data->description)) !!}</p> <br>



                                @if ($vendor_details_show == true)
                                    <strong>Vendor Name</strong> : {{ $vendor->fname . ' ' . $vendor->lname }} <br>

                                    <strong>Vendor Contact</strong> : {{ $vendor->contact }} <br>

                                    <strong>Vendor Email</strong> : {{ $vendor->email }} <br>
                                @endif
                                
                                {{-- <strong>Vendor Name</strong> : Vendor Name <br>

                                <strong>Vendor Contact</strong> : 9735170720 <br>

                                <strong>Vendor Email</strong> : krishnabasak268@gmail.com <br> --}}


                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- <div class="tab-pane fade" id="specification">

                <div class="row">

                    <div class="col-lg-12">

                        <div class="product-details-frame">

                            <table class="table table-bordered">

                                <tbody>

                                    <tr>

                                        <th scope="row">Product code</th>

                                        <td>SKU: 101783</td>

                                    </tr>

                                    <tr>

                                        <th scope="row">Weight</th>

                                        <td>1kg, 2kg</td>

                                    </tr>

                                    <tr>

                                        <th scope="row">Styles</th>

                                        <td>@Girly</td>

                                    </tr>

                                    <tr>

                                        <th scope="row">Properties</th>

                                        <td>Short Dress</td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div> --}}

            <div class="tab-pane fade" id="reviews">

                <div class="row">
                    <div class="col-lg-12">
                        @if ($review_done == null and Auth::check())
                            <div class="product-details-frame" id="r_data">
                                <div id="review_body">
                                    <h3 class="frame-title">add your review</h3>
                                    <form class="review-form" onsubmit="return false;">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="star-rating">
                                                    <input type="radio" name="rating" id="star-1"
                                                        value="5"><label for="star-1"></label>
                                                    <input type="radio" name="rating" id="star-2"
                                                        value="4"><label for="star-2"></label>
                                                    <input type="radio" name="rating" id="star-3"
                                                        value="3"><label for="star-3"></label>
                                                    <input type="radio" name="rating" id="star-4"
                                                        value="2"><label for="star-4"></label>
                                                    <input type="radio" name="rating" id="star-5"
                                                        value="1"><label for="star-5"></label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <textarea class="form-control" placeholder="Describe" id="review"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12" id="submit" onclick="save()"><button
                                                    class="btn btn-inline"><i class="icofont-water-drop"></i><span>Submit Review</span></button></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            @if (!empty($review_done))
                                <div class="product-details-frame">
                                    <ul class="review-list" id="list_body">
                                        <li class="review-item" id="review_{{ $review_done['id'] }}">
                                            <div class="review-media">
                                                <h5 class="review-meta"><a
                                                        href="#">{{ $review_done['customer'] }}</a><span>{{ $review_done['created_at'] }}</span>
                                                </h5>
                                            </div>
                                            <ul class="review-rating">

                                                {{-- <li class="icofont-ui-rating"></li>
                                                    <li class="icofont-ui-rating"></li>
                                                    <li class="icofont-ui-rating"></li>
                                                    <li class="icofont-ui-rating"></li>
                                                    <li class="icofont-ui-rate-blank"></li> --}}

                                                <i class="{{ $review_done['rating'] >= 1 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank' }}"></i>
                                                <i class="{{ $review_done['rating'] >= 2 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank' }}"></i>
                                                <i class="{{ $review_done['rating'] >= 3 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank' }}"></i>
                                                <i class="{{ $review_done['rating'] >= 4 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank' }}"></i>
                                                <i class="{{ $review_done['rating'] >= 5 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank' }}"></i>

                                            </ul>
                                            <p class="review-desc">{{ $review_done['review'] }}</p>
                                        </li>
                                        <button class="btn btn-success" onclick="edit({{ $review_done['id'] }})"><i
                                                class="icofont-reply"></i>Edit</button>
                                        <button class="btn btn-danger"
                                            onclick="delete_review({{ $review_done['id'] }})"><i
                                                class="icofont-reply"></i>Delete</button>
                                    </ul>
                                </div>
                            @endif
                        @endif
                        <div class="product-details-frame">
                            <ul class="review-list" id="list_body">
                                {{-- <li class="review-item">

                                    <div class="review-media">

                                        <h5 class="review-meta"><a href="#">miron mahmud</a><span>June 02,
                                                2020</span></h5>

                                    </div>

                                    <ul class="review-rating">

                                        <li class="icofont-ui-rating"></li>

                                        <li class="icofont-ui-rating"></li>

                                        <li class="icofont-ui-rating"></li>

                                        <li class="icofont-ui-rating"></li>

                                        <li class="icofont-ui-rate-blank"></li>

                                    </ul>

                                    <p class="review-desc">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus
                                        hic amet qui velit, molestiae suscipit perferendis, autem doloremque blanditiis
                                        dolores nulla excepturi ea nobis!</p>

                                    <form class="review-reply"><input type="text" placeholder="reply your thoughts"><button><i class="icofont-reply"></i>reply</button></form>

                                    <ul class="review-reply-list">

                                        <li class="review-reply-item">

                                            <div class="review-media"><a class="review-avatar" href="#"><img src="images/avatar/02.jpg" alt="review"></a>

                                                <h5 class="review-meta"><a href="#">labonno khan</a><span><b>author -</b>June 02, 2020</span></h5>

                                            </div>

                                            <p class="review-desc">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus hic amet qui velit, molestiae suscipit perferendis, autem doloremque blanditiis dolores nulla excepturi ea nobis!</p>

                                            <form class="review-reply"><input type="text" placeholder="reply your thoughts"><button><i class="icofont-reply"></i>reply</button></form>

                                        </li>

                                        <li class="review-reply-item">

                                            <div class="review-media"><a class="review-avatar" href="#"><img src="images/avatar/03.jpg" alt="review"></a>

                                                <h5 class="review-meta"><a href="#">tahmina bonny</a><span>June 02, 2020</span></h5>

                                            </div>

                                            <p class="review-desc">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus hic amet qui velit, molestiae suscipit perferendis, autem doloremque blanditiis dolores nulla excepturi ea nobis!</p>

                                            <form class="review-reply"><input type="text" placeholder="reply your thoughts"><button><i class="icofont-reply"></i>reply</button></form>

                                        </li>
                                    </ul>

                                </li> --}}

                                @if (!empty($review_list))
                                    @foreach ($review_list as $item)
                                    @php
                                        $user = $user_model->where('id', $item['customer_id'])->first();
                                    @endphp
                                        <li class="review-item" id="review_{{ $item['id'] }}">
                                            <div class="review-media">
                                                <h5 class="review-meta"><a
                                                        href="#">{{$user->fname. ' '. $user->lname}}</a><span>{{ $item['created_at'] }}</span>
                                                </h5>
                                            </div>
                                            <ul class="review-rating">

                                                {{-- <li class="icofont-ui-rating"></li>
                                                <li class="icofont-ui-rating"></li>
                                                <li class="icofont-ui-rating"></li>
                                                <li class="icofont-ui-rating"></li>
                                                <li class="icofont-ui-rate-blank"></li> --}}

                                                <i
                                                    class="{{ $item['rating'] >= 1 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank' }}"></i>
                                                <i
                                                    class="{{ $item['rating'] >= 2 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank' }}"></i>
                                                <i
                                                    class="{{ $item['rating'] >= 3 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank' }}"></i>
                                                <i
                                                    class="{{ $item['rating'] >= 4 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank' }}"></i>
                                                <i
                                                    class="{{ $item['rating'] >= 5 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank' }}"></i>

                                            </ul>
                                            <p class="review-desc">{{ $item['review'] }}</p>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        {{$review_list->links()}}
                    </div>
                </div>
            </div>
        </div>




    </section>



    @if ($similar_products->isNotEmpty())
        <section class="inner-section">

            <div class="container">

                <div class="row">

                    <div class="col">

                        <div class="section-heading">

                            <h2>Similar Products</h2>

                        </div>

                    </div>

                </div>

                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                    @foreach ($similar_products as $product)
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

                {{-- <div class="row">

                    <div class="col-lg-12">

                        <div class="section-btn-25">

                            <a href="{{url('/')}}/products-list/similar-products" class="btn btn-outline"><i class="fas fa-eye"></i><span>Show More</span></a>

                        </div>

                    </div>

                </div> --}}

            </div>

        </section>
    @endif

@endsection
@section('extrajs')
    <script type="text/javascript">
        const product_id = {{ $product_data->id }};
    </script>
    <script src="{{ asset('assests/user/product_review.js') }}"></script>

    <script
    src="https://cdn.jsdelivr.net/npm/shareon@2/dist/shareon.iife.js"
    defer
    init
  ></script>
@endsection
