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

    <section class="inner-section wishlist-part">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-scroll table-responsive">
                        <table class="table-list">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product Image</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Price / Item</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Shopping</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            @if($wishlist_items->isNotEmpty())
                                <tbody>
                                    @foreach($wishlist_items as $index => $item)
                                        @php
                                            $product_data = $product_model->where('id', $item->product_id)->first();

                                            if($product_data->product_type == '1')
                                            {
                                                $product_name = $product_data->name;
                                                $product_price = (!empty($product_data->discounted_price)) ? $product_data->discounted_price : $product_data->mrp;
                                            }else{
                                                $mrp = json_decode($product_data->mrp);
                                                $discounted_price = json_decode($product_data->discounted_price);
                                                $specifications = json_decode($product_data->specifications);

                                                $product_name = $product_data->name.' ('.$specifications[$item->variant_index].')';
                                                $product_price = (!empty($discounted_price[$item->variant_index])) ? $discounted_price[$item->variant_index] : $mrp[$item->variant_index];
                                            }

                                            $product_stock_status = ($product_data->number_of_items > 0) ? 'In Stock' : 'Out Of Stock';
                                            $stock_status_class = ($product_data->number_of_items > 0) ? 'stock-in' : 'stock-out';
                                        @endphp
                                        <tr id="{{$product_data->id}}-{{$item->variant_index}}">
                                            <td class="table-serial"><h6>{{($index + 1)}}</h6></td>
                                            <td class="table-image"><img src="{{asset($product_data->featuredimage)}}"></td>
                                            <td class="table-name"><h6>{{substr($product_name, 0, 50)}}</h6></td>
                                            <td class="table-price"><h6>{!! Helper::ccurrency() !!}{{$product_price}}</h6></td>
                                            <td class="table-status"><h6 class="{{$stock_status_class}}">{{$product_stock_status}}</h6></td>
                                            <td class="table-shop">
                                                <button class="product-add-cart" title="Add To Cart" onclick="add_to_cart({{$product_data->id}}, {{$item->variant_index}});"><span>Add To Cart</span></button>
                                            </td>
                                            <td class="table-action">
                                                <a class="view" href="{{url('/')}}/product/{{$product_data->slug}}" title="View Item"><i class="fas fa-eye"></i></a>
                                                <a class="trash" href="javascript:void(0);" title="Remove Item" onclick="add_remove_wishlist_item({{$product_data->id}}, {{$item->variant_index}}, 2);"><i class="icofont-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                    </div>
                    <br>
                    {{$wishlist_items->links()}}
                </div>
            </div>
        </div>
    </section>

@endsection