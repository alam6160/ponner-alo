<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{$title}} - MV ECommerce</title>
    <link rel="icon" href="{{asset('assests/web/images/favicon.png')}}">
    <link rel="stylesheet" href="{{asset('assests/web/fonts/flaticon/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/fonts/icofont/icofont.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/fonts/fontawesome/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/vendor/venobox/venobox.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/vendor/slickslider/slick.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/vendor/niceselect/nice-select.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/vendor/bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/css/user-auth.css')}}">
    <link rel="stylesheet" href="{{asset('assests/web/css/style.css')}}">

    <link rel="stylesheet" href="{{asset('assests/toastr/toastr.min.css')}}">

    @stack('style')
</head>

<body>
    <section class="user-form-part">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-12 col-lg-12 col-xl-10">
                    <div class="user-form-logo">
                        <a href="{{url('/')}}"><img src="{{ Helper::frontendLogo(); }}"></a>
                    </div>

                    @yield('content')

                    <div class="user-form-footer">
                        <p>{{ Helper::csname() }} | &COPY; Copyright</p>
                        {{-- <p>Greeny | &COPY; Copyright by <a href="#">Mironcoder</a></p> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{asset('assests/web/vendor/bootstrap/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('assests/web/vendor/bootstrap/popper.min.js')}}"></script>
    <script src="{{asset('assests/web/vendor/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{asset('assests/web/vendor/countdown/countdown.min.js')}}"></script>
    <script src="{{asset('assests/web/vendor/niceselect/nice-select.min.js')}}"></script>
    <script src="{{asset('assests/web/vendor/slickslider/slick.min.js')}}"></script>
    <script src="{{asset('assests/web/vendor/venobox/venobox.min.js')}}"></script>
    <script src="{{asset('assests/web/js/nice-select.js')}}"></script>
    <script src="{{asset('assests/web/js/countdown.js')}}"></script>
    <script src="{{asset('assests/web/js/accordion.js')}}"></script>
    <script src="{{asset('assests/web/js/venobox.js')}}"></script>
    <script src="{{asset('assests/web/js/slick.js')}}"></script>
    <script src="{{asset('assests/web/js/main.js')}}"></script>

    <script src="{{asset('assests/toastr/toastr.min.js')}}"></script>

    @stack('script')
    
</body>

</html>