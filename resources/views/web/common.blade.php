<!DOCTYPE html>
<html lang="en">

<head>
    @stack('style')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title}} - {{ Helper::csname() }}</title>
    <link rel="icon" href="{{asset('assests/web/images/favicon.png')}}">
    <link rel="stylesheet" href="{{asset('assests/web/fonts/flaticon/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/fonts/icofont/icofont.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/fonts/fontawesome/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/vendor/venobox/venobox.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/vendor/slickslider/slick.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/vendor/niceselect/nice-select.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/vendor/bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/css/index.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/css/profile.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/css/product-details.css')}}">

    {{-- @stack('style') --}}
</head>

<body>
    <div class="backdrop"></div>
    <a class="backtop fas fa-arrow-up" href="#"></a>
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-5">
                    <div class="header-top-welcome">
                        <p>Welcome to {{ Helper::csname() }} in Your Dream Online Store!</p>
                    </div>
                </div>
                <div class="col-md-5 col-lg-3">
                    {{-- <div class="header-top-select">
                        <div class="header-select"><i class="icofont-world"></i>
                            <select class="select">
                                <option value="english" selected>english</option>
                                <option value="bangali">bangali</option>
                                <option value="arabic">arabic</option>
                            </select></div>
                        <div class="header-select"><i class="icofont-money"></i>
                            <select class="select">
                                <option value="english" selected>doller</option>
                                <option value="bangali">pound</option>
                                <option value="arabic">taka</option>
                            </select>
                        </div>
                    </div> --}}
                </div>
                <div class="col-md-7 col-lg-4">
                    <ul class="header-top-list">
                        <li><a href="{{ route('agent') }}">Vendor Login</a></li>
                        <li><a href="{{ route('vendorsignup') }}">Vendor Register</a></li>
                        <li>
                           
  <select >
    <option value="apple">Changed Background</option>
    <option value="banana">Custom</option>
    <option value="orange">Orange</option>
    <option value="strawberry">Strawberry</option>
  </select>
</li>
                           
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <header class="header-part">
        <div class="container">
            <div class="header-content">
                <div class="header-media-group">
                    <button class="header-user"><img src="{{asset('assests/web/images/user.png')}}"></button>
                    <a href="{{url('/')}}"><img src="{{ Helper::frontendLogo(); }}"></a>
                    <button class="header-src"><i class="fas fa-search"></i></button>
                </div>
                <a href="{{url('/')}}" class="header-logo"><img src="{{ Helper::frontendLogo(); }}"></a>

                @if(Auth::check())
                    @if (Auth::user()->user_type == '9')
                        <a href="{{url('user/account')}}" class="header-widget" title="My Account">
                            <img src="{{asset('assests/web/images/user.png')}}"><span>My Account</span>
                        </a>
                    @else
                        <a href="{{url('sign-in')}}" class="header-widget" title="Login">
                            <img src="{{asset('assests/web/images/user.png')}}"><span>Login</span>
                        </a>
                    @endif
                    
                @else
                    <a href="{{url('sign-in')}}" class="header-widget" title="Login">
                        <img src="{{asset('assests/web/images/user.png')}}"><span>Login</span>
                    </a>
                @endif

                <form class="header-form" action="{{ route('search.submit') }}" method="POST">
                    @csrf
                    <input type="text" placeholder="Search" name="search" required  value="{{ (isset($searchkey)) ? $searchkey : ''; }}">
                    <button type="submit" name="submit"><i class="fas fa-search"></i></button>
                </form>
                <div class="header-widget-group">
                    {{-- <a href="compare.html" class="header-widget" title="Compare List">
                        <i class="fas fa-random"></i><sup>0</sup>
                    </a> --}}
                    <a href="{{url('user/wishlist')}}" class="header-widget" title="My Wishlist">
                        <i class="fas fa-heart"></i><sup id="wishlist_total_items_hd">@if(Auth::check()){{$total_wishlist_items}}@else{{0}}@endif</sup>
                    </a>

                    @if (! request()->routeIs('user.checkout.index'))
                    <button class="header-widget header-cart" title="My Cart"><i class="fas fa-shopping-basket"></i>
                        <sup id="cart_total_items_hd">@if(!empty($cart_total_items)){{$cart_total_items}}@else{{0}}@endif</sup>
                        <span>Total Price<small>{!! Helper::ccurrency() !!} <span style="font-size:16px; font-weight:bold; margin-left:0;" id="cart_total_value_hd">@if(!empty($cart_total_value)){{$cart_total_value}}@else{{0}}@endif</span></small></span>
                    </button>
                    @endif
                    

                </div>
            </div>
        </div>
    </header>
    <nav class="navbar-part">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="navbar-content">
                        <ul class="navbar-list">
                            <li class="navbar-item">
                                <a class="navbar-link" href="{{url('/')}}">Home</a>
                            </li>

                            @if($parent_cats->isNotEmpty())
                                <li class="navbar-item dropdown-megamenu">
                                    <a class="navbar-link dropdown-arrow" href="javascript:void(0);">Categories</a>
                                    <div class="megamenu">
                                        <div class="container megamenu-scroll">
                                            <div class="row row-cols-5">
                                                @foreach($parent_cats as $parent_cat)
                                                    <div class="col">
                                                        <div class="megamenu-wrap">
                                                            <h5 class="megamenu-title">{{$parent_cat->cat_title}}</h5>
                                                            @php $child_cats = $category_model->where('parent_id', $parent_cat->id)->get(); @endphp
                                                            @if($child_cats->isNotEmpty())
                                                                <ul class="megamenu-list">
                                                                    @foreach($child_cats as $child_cat)
                                                                        <li><a href="{{url('/')}}/products-list/{{$child_cat->slug}}">{{$child_cat->cat_title}}</a></li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            {{-- <li class="navbar-item dropdown">
                                <a class="navbar-link dropdown-arrow" href="javascript:void(0);">Blogs</a>
                                <ul class="dropdown-position-list">
                                    <li><a href="javascript:void(0);">blog grid</a></li>
                                    <li><a href="javascript:void(0);">blog standard</a></li>
                                    <li><a href="javascript:void(0);">blog details</a></li>
                                    <li><a href="javascript:void(0);">blog author</a></li>
                                </ul>
                            </li> --}}
                            <li class="navbar-item">
                                <a class="navbar-link" href="{{ route('aboutus') }}">About Us</a>
                            </li>
                            <li class="navbar-item">
                                <a class="navbar-link" href="{{ route('contactus') }}">Contact Us</a>
                            </li>
                            <li class="navbar-item dropdown">
                                <a class="navbar-link dropdown-arrow" href="javascript:void(0);">My Account</a>
                                <ul class="dropdown-position-list">
                                    @if(Auth::check())
                                        @if (Auth::user()->user_type == '9')
                                            <li><a href="{{url('/')}}/user/account">My Account</a></li>
                                            <li><a href="{{ route('user.order.index') }}">My Orders</a></li>
                                            <li><a href="{{url('/')}}/sign-out">Logout</a></li>
                                        @else
                                            <li><a href="{{url('/')}}/sign-in">Login</a></li>
                                            <li><a href="{{url('/')}}/sign-up">Register</a></li>
                                            <li><a href="{{url('/')}}/reset-password">Reset Password</a></li>
                                        @endif
                                        
                                    @else
                                        <li><a href="{{url('/')}}/sign-in">Login</a></li>
                                        <li><a href="{{url('/')}}/sign-up">Register</a></li>
                                        <li><a href="{{url('/')}}/reset-password">Reset Password</a></li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                        <div class="navbar-info-group">
                            <div class="navbar-info"><i class="icofont-ui-touch-phone"></i>
                                <p><small>Call Us</small><span>{!! Helper::site_data('contact_no') !!}</span></p>
                            </div>
                            <div class="navbar-info"><i class="icofont-ui-email"></i>
                                <p><small>Email Us</small><span>{!! Helper::site_data('email_id') !!}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    @if($parent_cats->isNotEmpty())
        <aside class="category-sidebar">
            <div class="category-header">
                <h4 class="category-title"><i class="fas fa-align-left"></i><span>Categories</span></h4>
                <button class="category-close"><i class="icofont-close"></i></button>
            </div>
            <ul class="category-list">
                @foreach($parent_cats as $parent_cat)
                    <li class="category-item">
                        <a class="category-link dropdown-link" href="javascript:void(0);"><i class="flaticon-vegetable"></i><span>{{$parent_cat->cat_title}}</span></a>
                        @php $child_cats = $category_model->where('parent_id', $parent_cat->id)->get(); @endphp
                        @if($child_cats->isNotEmpty())
                            <ul class="dropdown-list">
                                @foreach($child_cats as $child_cat)
                                    <li><a href="{{url('/')}}/products-list/{{$child_cat->slug}}">{{$child_cat->cat_title}}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
            <div class="category-footer">
                <p>Copyright &copy; {{date('Y')}} - <a href="{{url('/')}}">{!! Helper::site_data('company_name') !!}</a></p>
            </div>
        </aside>
    @endif

    <aside class="cart-sidebar">
        <div class="cart-header">
            <div class="cart-total">
                <i class="fas fa-shopping-basket"></i>
                <span>Total Items (<span id="cart_total_items_cl">@if(!empty($cart_total_items)){{$cart_total_items}}@else{{0}}@endif</span>)</span>
            </div>
            <button class="cart-close"><i class="icofont-close"></i></button>
        </div>
        <ul class="cart-list" id="cart_items">
            @if(!empty($cart_items))
                @foreach($cart_items as $item)
                    <li class="cart-item" id="cart_item_{{$item['id']}}">
                        <div class="cart-media">
                            <a href="{{url('/')}}/product/{{$item['attributes']['product_slug']}}">
                                <img src="{{asset($item['attributes']['product_image'])}}"></a>
                            <button class="cart-delete" onclick="remove_cart_item({{$item['id']}});"><i class="far fa-trash-alt"></i></button>
                        </div>
                        <div class="cart-info-group">
                            <div class="cart-info">
                                <h6><a href="{{url('/')}}/product/{{$item['attributes']['product_slug']}}">{{$item['name']}}</a></h6>
                                <p>Unit Price - {!! Helper::ccurrency() !!}<span id="unit_price_{{$item['id']}}">{{$item['price']}}</span></p>
                            </div>
                            <div class="cart-action-group">
                                <div class="product-action">
                                    <button class="action-minus" title="Decrease" id="decrease_btn_{{$item['id']}}" onclick="update_cart_item({{$item['id']}});"><i class="icofont-minus"></i></button>
                                    <input class="action-input" title="Quantity" type="text" id="quantity_box_{{$item['id']}}" value="{{$item['quantity']}}" oninput="update_cart_item({{$item['id']}});">
                                    <button class="action-plus" title="Increase" id="increase_btn_{{$item['id']}}" onclick="update_cart_item({{$item['id']}});"><i class="icofont-plus"></i></button>
                                </div>
                                <h6>{!! Helper::ccurrency() !!}<span id="total_price_{{$item['id']}}">{{$item['price'] * $item['quantity']}}</span></h6>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
        <div class="cart-footer">
            {{-- <button class="coupon-btn" id="coupon_button" style="@if(!empty($cart_conditions)){{'display:none;'}}@endif">Do You Have A Coupon Code?</button> --}}
            <form class="coupon-form" id="coupon_form" style="@if(!empty($cart_conditions)){{'display:none;'}}@endif">
                <input type="text" id="coupon_code" placeholder="Enter Your Coupon Code">
                <button type="button" onclick="apply_coupon();"><span>Apply</span></button>
            </form>
            <button class="remove-coupon" id="remove_coupon" onclick="remove_coupon();" style="@if(empty($cart_conditions)){{'display:none;'}}@endif">Coupon Applied! Remove Coupon?</button>
            <a class="cart-checkout-btn" href="javascript:void(0);" onclick="checkout_verify();">
                <span class="checkout-label">Proceed To Checkout</span>
                <span class="checkout-price">{!! Helper::ccurrency() !!}<span id="cart_total_value_cl">@if(!empty($cart_total_value)){{$cart_total_value}}@else{{0}}@endif</span></span>
            </a>
        </div>
    </aside>
    <aside class="nav-sidebar">
        <div class="nav-header">
            <a href="{{url('/')}}">
                <img src="{{ Helper::frontendLogo(); }}">
            </a>
            <button class="nav-close"><i class="icofont-close"></i></button>
        </div>
        <div class="nav-content">
            <div class="nav-btn">
                

                @if(Auth::check())
                    @if (Auth::user()->user_type == '9')
                    <a href="{{url('user/account')}}" class="btn btn-inline">
                        <i class="fa fa-user"></i><span>My Account</span>
                    </a>
                    @else
                    <a href="{{url('sign-in')}}" class="btn btn-inline">
                        <i class="fa fa-user"></i><span>Login</span>
                    </a>
                    @endif
                @else
                    <a href="{{url('sign-in')}}" class="btn btn-inline">
                        <i class="fa fa-user"></i><span>Login</span>
                    </a>
                @endif

            </div>
            {{-- <div class="nav-select-group">
                <div class="nav-select">
                    <i class="icofont-world"></i>
                    <select class="select">
                        <option value="english" selected>English</option>
                        <option value="bangali">Bangali</option>
                        <option value="arabic">Arabic</option>
                    </select>
                </div>
                <div class="nav-select">
                    <i class="icofont-money"></i>
                    <select class="select">
                        <option value="english" selected>Doller</option>
                        <option value="bangali">Pound</option>
                        <option value="arabic">Taka</option>
                    </select>
                </div>
            </div> --}}
            <ul class="nav-list">
                <li>
                    <a class="nav-link" href="{{url('/')}}"><i class="icofont-home"></i>Home</a>
                </li>
                {{-- <li>
                    <a class="nav-link dropdown-link" href="javascript:void(0);"><i class="icofont-book-alt"></i>Blogs</a>
                    <ul class="dropdown-list">
                        <li><a href="javascript:void(0);">blog grid</a></li>
                        <li><a href="javascript:void(0);">blog standard</a></li>
                        <li><a href="javascript:void(0);">blog details</a></li>
                        <li><a href="javascript:void(0);">blog author</a></li>
                    </ul>
                </li> --}}

                @if(Auth::check())
                    @if (Auth::user()->user_type == '9')
                    <li>
                        <a class="nav-link" href="{{url('user/account')}}"><i class="icofont-ui-user"></i>My Account</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('user.order.index') }}"><i class="icofont-ui-user"></i>My Orders</a>
                    </li>
                    @else
                    <li>
                        <a class="nav-link dropdown-link" href="javascript:void(0);"><i class="icofont-ui-user"></i>My Account</a>
                        <ul class="dropdown-list">
                            <li><a href="{{url('/')}}/sign-in">Login</a></li>
                            <li><a href="{{url('/')}}/sign-up">Register</a></li>
                            <li><a href="{{url('/')}}/reset-password">Reset Password</a></li>
                        </ul>
                    </li>
                    @endif
                    
                @else
                    <li>
                        <a class="nav-link dropdown-link" href="javascript:void(0);"><i class="icofont-ui-user"></i>My Account</a>
                        <ul class="dropdown-list">
                            <li><a href="{{url('/')}}/sign-in">Login</a></li>
                            <li><a href="{{url('/')}}/sign-up">Register</a></li>
                            <li><a href="{{url('/')}}/reset-password">Reset Password</a></li>
                        </ul>
                    </li>
                @endif

                {{-- <li>
                    <a class="nav-link" href="javascript:void(0);"><i class="icofont-sale-discount"></i>Offers</a>
                </li> --}}
                <li>
                    <a class="nav-link" href="{{ route('aboutus') }}"><i class="icofont-info-circle"></i>About Us</a>
                </li>
                {{-- <li>
                    <a class="nav-link" href="javascript:void(0);"><i class="icofont-support-faq"></i>Need Help</a>
                </li> --}}
                <li>
                    <a class="nav-link" href="{{ route('contactus') }}"><i class="icofont-contacts"></i>Contact Us</a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('termsconditions') }}"><i class="icofont-book-mark"></i>Terms & Conditions</a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('privacypolicy') }}"><i class="icofont-warning"></i>Privacy Policy</a>
                </li>

                @if(Auth::check())
                    @if (Auth::user()->user_type == '9')
                    <li>
                        <a class="nav-link" href="{{url('sign-out')}}"><i class="icofont-logout"></i>Logout</a>
                    </li>
                    @endif
                    
                @endif

            </ul>
            <div class="nav-info-group">
                <div class="nav-info">
                    <i class="icofont-ui-touch-phone"></i>
                    <p><small>Call Us</small><span>{!! Helper::site_data('contact_no') !!}</span></p>
                </div>
                <div class="nav-info">
                    <i class="icofont-ui-email"></i>
                    <p><small>Email Us</small><span>{!! Helper::site_data('email_id') !!}</span></p>
                </div>
            </div>
            <div class="nav-footer">
                <p>Copyright &copy; {{date('Y')}} - <a href="{{url('/')}}">{!! Helper::site_data('company_name') !!}</a></p>
            </div>
        </div>
    </aside>
    <div class="mobile-menu">
        <a href="{{url('/')}}" title="Home"><i class="fas fa-home"></i><span>Home</span></a>
        <button class="cate-btn" title="Categories"><i class="fas fa-list"></i><span>Categories</span></button>
        <button class="cart-btn" title="My Cart"><i class="fas fa-shopping-basket"></i>
            <span>My Cart</span><sup id="cart_total_items_tm">@if(!empty($cart_total_items)){{$cart_total_items}}@else{{0}}@endif</sup>
        </button>
        <a href="{{url('user/wishlist')}}" title="My Wishlist"><i class="fas fa-heart"></i>
            <span>My Wishlist</span><sup id="wishlist_total_items_tm">@if(Auth::check()){{$total_wishlist_items}}@else{{0}}@endif</sup>
        </a>
    </div>

    @yield('content')

    {{-- <section class="news-part" style="background: url({{asset('assests/web/images/newsletter.jpg')}}) no-repeat center;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-5 col-lg-6 col-xl-7">
                    <div class="news-text">
                        <h2>Get 20% Discount for Subscriber</h2>
                        <p>Lorem ipsum dolor consectetur adipisicing accusantium</p>
                    </div>
                </div>
                <div class="col-md-7 col-lg-6 col-xl-5">
                    <form class="news-form">
                        <input type="text" placeholder="Enter Your Email Address">
                        <button><span><i class="icofont-ui-email"></i>Subscribe</span></button>
                    </form>
                </div>
            </div>
        </div>
    </section> --}}
    <section class="intro-part">
        <div class="container">
            <div class="row intro-content">
                <div class="col-sm-6 col-lg-3">
                    <div class="intro-wrap">
                        <div class="intro-icon"><i class="fas fa-truck"></i></div>
                        <div class="intro-content">
                            <h5>free home delivery</h5>
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="intro-wrap">
                        <div class="intro-icon"><i class="fas fa-sync-alt"></i></div>
                        <div class="intro-content">
                            <h5>instant return policy</h5>
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="intro-wrap">
                        <div class="intro-icon"><i class="fas fa-headset"></i></div>
                        <div class="intro-content">
                            <h5>quick support system</h5>
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="intro-wrap">
                        <div class="intro-icon"><i class="fas fa-lock"></i></div>
                        <div class="intro-content">
                            <h5>secure payment way</h5>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer-part">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-xl-4">
                    <div class="footer-widget">
                        <a class="footer-logo" href="{{url('/')}}"><img src="{{ Helper::frontendLogo(); }}"></a>
                        <p class="footer-desc">{!! nl2br(Helper::site_data('short_about')) !!}</p>
                        <ul class="footer-social">
                            <li><a class="icofont-facebook" href="javascript:void(0);"></a></li>
                            <li><a class="icofont-twitter" href="javascript:void(0);"></a></li>
                            <li><a class="icofont-linkedin" href="javascript:void(0);"></a></li>
                            <li><a class="icofont-instagram" href="javascript:void(0);"></a></li>
                            <li><a class="icofont-pinterest" href="javascript:void(0);"></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="footer-widget contact">
                        <h3 class="footer-title">Contact Us</h3>
                        <ul class="footer-contact">
                            <li><i class="icofont-ui-email"></i>
                                <p><span>{!! Helper::site_data('email_id') !!}</span><span>{!! Helper::site_data('email_id_2') !!}</span></p>
                            </li>
                            <li><i class="icofont-ui-touch-phone"></i>
                                <p><span>{!! Helper::site_data('contact_no') !!}</span><span>{!! Helper::site_data('contact_no_2') !!}</span></p>
                            </li>
                            <li><i class="icofont-location-pin"></i>
                                <p>{!! Helper::site_data('address') !!}</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="footer-widget">
                        <h3 class="footer-title">Quick Links</h3>
                        <div class="footer-links">
                            <ul>
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('aboutus') }}">About Us</a></li>
                                <li><a href="{{ route('contactus') }}">Contact Us</a></li>
                                <li><a href="{{ route('termsconditions') }}">Terms & Conditions</a></li>
                                <li><a href="{{ route('privacypolicy') }}">Privacy Policy</a></li>
                            </ul>
                            {{-- <ul>
                                <li><a href="javascript:void(0);">Location</a></li>
                                <li><a href="javascript:void(0);">Affiliates</a></li>
                                <li><a href="javascript:void(0);">Contact</a></li>
                                <li><a href="javascript:void(0);">Carrer</a></li>
                                <li><a href="javascript:void(0);">Faq</a></li>
                            </ul> --}}
                        </div>
                    </div>
                </div>
                {{-- <div class="col-sm-6 col-xl-3">
                    <div class="footer-widget">
                        <h3 class="footer-title">Download App</h3>
                        <p class="footer-desc">Lorem ipsum dolor sit amet tenetur dignissimos ipsum eligendi autem obcaecati minus ducimus totam reprehenderit exercitationem!</p>
                        <div class="footer-app">
                            <a href="javascript:void(0);"><img src="{{asset('assests/web/images/google-store.png')}}"></a>
                            <a href="javascript:void(0);"><img src="{{asset('assests/web/images/app-store.png')}}"></a>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="footer-bottom">
                        <p class="footer-copytext">Copyright &copy; {{date('Y')}} - <a href="{{url('/')}}">{!! Helper::site_data('company_name') !!}</a></p>
                        <div class="footer-card">
                            <a href="javascript:void(0);"><img src="{{asset('assests/web/images/payment/jpg/01.jpg')}}"></a>
                            <a href="javascript:void(0);"><img src="{{asset('assests/web/images/payment/jpg/02.jpg')}}"></a>
                            <a href="javascript:void(0);"><img src="{{asset('assests/web/images/payment/jpg/03.jpg')}}"></a>
                            <a href="javascript:void(0);"><img src="{{asset('assests/web/images/payment/jpg/04.jpg')}}"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{asset('assests/web/vendor/bootstrap/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('assests/web/vendor/bootstrap/popper.min.js')}}"></script>
    <script src="{{asset('assests/web/vendor/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{asset('assests/web/vendor/countdown/countdown.min.js')}}"></script>
    <script src="{{asset('assests/web/vendor/niceselect/nice-select.min.js')}}"></script>
    <script src="{{asset('assests/web/vendor/slickslider/slick.min.js')}}"></script>
    <script src="{{asset('assests/web/vendor/venobox/venobox.min.js')}}"></script>
    <script src="{{asset('assests/sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{asset('assests/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('assests/web/js/nice-select.js')}}"></script>
    <script src="{{asset('assests/web/js/countdown.js')}}"></script>
    <script src="{{asset('assests/web/js/accordion.js')}}"></script>
    <script src="{{asset('assests/web/js/venobox.js')}}"></script>
    <script src="{{asset('assests/web/js/slick.js')}}"></script>
    <script src="{{asset('assests/web/js/main.js')}}"></script>

    @yield('extrajs')
    
    <script>
        const base_url = '{{url('/')}}/';

        function setProcessingButton(formID) {
            $("#"+formID+" [name='submit']").prop('disabled', true);
            $("#"+formID+" [name='submit']").html('<span class="spinner-border spinner-border-sm"></span>&nbsp;&nbsp;Processing');
        }
        function setErrorMessage(response) {
            if (Array.isArray(response.message)) {
                response.message.forEach(function (val) { toastr.error(val); });
            } else { toastr.error(response.message); }
        }
        function resetButton(formID, label = 'Submit') {
            $("#"+formID+" [name='submit']").prop('disabled', false);
            $("#"+formID+" [name='submit']").text(label);
        }
        function setSuccessButton(response, formID, label = 'Submit') {
            resetButton(formID, label);
            $("#"+formID)[0].reset();
            $('#'+formID).parsley().reset();
            toastr.success(response.message);
        }
        function setUpdateSuccessButton(response, formID, label = 'Submit') {
            resetButton(formID, label);
            $('#'+formID).parsley().reset();
            toastr.success(response.message);
        }
        function setRedirect(url, message) {
            alert(message);
            window.location.replace(url);
        }
    </script>

    <script src="{{asset('assests/user/product.js')}}"></script>
    <script src="{{asset('assests/user/checkout.js')}}"></script>

    @if(Auth::check())
        <script>
            localStorage.setItem('user_id', {{Auth::id()}});
        </script>
    @else
        <script>
            localStorage.removeItem('user_id');
        </script>
    @endif

    @yield('account-js')

    @stack('script')

</body>

</html>