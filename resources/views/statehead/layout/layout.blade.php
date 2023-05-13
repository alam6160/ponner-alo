<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="icon" href="{{ Helper::favIcon() }}" type="../favicon.png" sizes="16x16" />
    
    <title>{{ env('APP_NAME') }} | @yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('assests/theme/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assests/theme/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assests/theme/css/icon-font.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assests/theme/css/owl.carousel.min.css') }}"/>
    <link href="{{ asset('assests/theme/css/animate.css') }}" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" type="text/css" href="{{ asset('assests/theme/css/style.css') }}" />

    <link rel="stylesheet" href="{{ asset('assests/parsley/parsley.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/bootstrap-sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/datatables/responsive/css/responsive.bootstrap4.min.css') }}">

    @stack('style')
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid"> 
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <img src="{{ Helper::adminLogo() }}" alt="SlingShot">
                </a>
                <button class="navbar-toggler" id="show-sidebar"> 
                    <span class="navbar-toggler-icon"></span> 
                </button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown user"> 
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-caret-down" aria-hidden="true"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink"> 
                            <a class="dropdown-item" href="#">
                              <span class="lnr lnr-user"></span> My Account</a> 
                            <a class="dropdown-item" href="#">
                                <span class="lnr lnr-cog"></span> Change Password</a> 
                            <a class="dropdown-item" href="{{ route('statehead.logout') }}">
                                <span class="lnr lnr-power-switch"></span> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Header End -->
    <section class="left-nav">
        <div class="page-wrapper chiller-theme toggled">
            <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
                <i class="fas fa-bars"></i>
            </a>
            <nav id="sidebar" class="sidebar-wrapper">
                <div class="sidebar-content">
                    <div class="sidebar-header">
                        <div class="user-pic">
                            <img class="img-responsive img-rounded" src="{{ Helper::adminUser() }}" alt="User picture">
                        </div>
                        <div class="user-info">
                            <span class="user-name">
                                {{ auth()->user()->fname.' '.auth()->user()->lname }}
                            </span>
                            <span class="user-status">
                                <i class="fa fa-circle"></i>
                                <span>Online</span>
                            </span>
                        </div>
                    </div>
                    
                    @include('statehead.layout.sidebar')
                    <!-- sidebar-menu  -->
                </div>
            </nav>
            <!-- sidebar-wrapper  -->
        </div>
        <!-- page-wrapper -->
    </section>
    <!-- Left Nav End -->
    <section class="main-content">
        <div class="container-fluid">
            <div class="content-inner">
                <div class="content-body">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="footer-inner">
            <div class="left-info"></div>
            <div class="right-info">
                <span>&copy; {{ env('APP_NAME') }}. All Right Resercved | {{ date('Y') }}</span>
            </div>
        </div>
    </footer>
    
    <div class="overlay"></div>
    <!-- Scripts -->
    <!-- Scripts -->
    <script src="{{ asset('assests/theme/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assests/theme/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assests/theme/js/ionicons.js') }}"></script>
    <script src="{{ asset('assests/theme/js/owl.carousel.js') }}"></script>

    <script src="{{ asset('assests/slugify/jquery.slugify.js') }}"></script>
    <script src="{{ asset('assests/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('assests/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assests/bootstrap-sweetalert/sweetalert.min.js') }}"></script>

    <script src="{{ asset('assests/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assests/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script src="{{ asset('assests/datatables/datatables.js') }}"></script>
    <script src="{{ asset('assests/datatables/responsive/js/responsive.bootstrap4.min.js') }}"></script>


    <script src="{{ asset('assests/theme/js/customize.js') }}"></script>
    <script>
        $(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('[data-toggle="tooltip"]').tooltip();

            /*bs-custom-file-input */
            bsCustomFileInput.init(); 

            $('.select2').select2();
        });
    </script>
    <script>
        /* USE :  onkeypress="return isNumber(event)" */
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
    
    
        /* USE : onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" */
        function firstDeci(){
            $('input').keypress(function(evt) {
                if (evt.which == ".".charCodeAt(0) && $(this).val().trim() == "") {
                    return false;
                }
            }); 
        }
    
        function isNumberKey(txt, evt) {
    
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode == 46) {
                /*Check if the text already contains the . character */
                if (txt.value.indexOf('.') === -1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (charCode > 31 && (charCode < 48 || charCode > 57)){
                    return false;
                }  
            }
            return true;
        }
    
        /* USE :  onkeypress="return onlyNumberKey(event)" type="tel" */
        function onlyNumberKey(evt) {
            /* Only ASCII character in that range allowed */
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode;
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)){
                return false;
            }
            return true;
        }
        function setSubmitButton(formId, status = '', message = '') {
            if (status === '') {
                $('#'+formId+' [name=submit]').prop('disabled', true);
                $('#'+formId+' [name=submit]').html('<span class="spinner-border spinner-border-sm"></span>&nbsp;&nbsp;Processing');
            } else if(status === 'reset'){
                $('#'+formId+' [name=submit]').prop('disabled', false);
                $('#'+formId+' [name=submit]').text('Submit');
    
                $('#'+formId).parsley().reset();
                $('#'+formId)[0].reset();
    
                toastr.success(message);
            }
        }
        function setUpdateButton(formId, status = '', message = '') {
            if (status === '') {
                $('#'+formId+' [name=submit]').prop('disabled', true);
                $('#'+formId+' [name=submit]').html('<span class="spinner-border spinner-border-sm"></span>&nbsp;&nbsp;Processing');
            } else if(status === 'reset'){
                $('#'+formId+' [name=submit]').prop('disabled', false);
                $('#'+formId+' [name=submit]').text('Update');
    
                $('#'+formId).parsley().reset();
                //$('#'+formId)[0].reset();
                toastr.success(message);
            }
        }
        function setRestButton(formId, status) {
            $('#'+formId+' [name=submit]').prop('disabled', false);
            $('#'+formId).parsley().reset();
            if (status == 'submit') {
                $('#'+formId+' [name=submit]').text('Submit');
            } else {
                $('#'+formId+' [name=submit]').text('Update');
            }
        }
        function setError(params) {
            if (Array.isArray(params)) {
                params.forEach(function (val) {  
                    toastr.error(val);
                });
            } else {
                toastr.error(params);
            }
        }
        function setRedirect(url, message) {
            alert(message);
            window.location.replace(url);
        }
    </script>
    <script>
        $(function(){
            $(".sidebar-dropdown > a").click(function() {
                $(".sidebar-submenu").slideUp(200);
                if (
                    $(this)
                    .parent()
                    .hasClass("active")
                ) {
                    $(".sidebar-dropdown").removeClass("active");
                    $(this)
                        .parent()
                        .removeClass("active");
                } else {
                    $(".sidebar-dropdown").removeClass("active");
                    $(this)
                        .next(".sidebar-submenu")
                        .slideDown(200);
                    $(this)
                        .parent()
                        .addClass("active");
                }
            });
            
            $('#show-sidebar').click(function() {
                $(".left-nav").toggleClass("toggle-sidebar");
                $(".overlay").toggleClass("overlay-active");
            });
        });
        
    </script>

    @stack('script')
</body>

</html>