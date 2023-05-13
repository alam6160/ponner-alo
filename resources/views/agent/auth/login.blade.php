<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<link rel="icon" href="{{ Helper::favIcon() }}" type="../favicon.png" sizes="16x16" />
<title>{{ Helper::csname() }} | Login</title>
<!--<link rel="stylesheet" href="css/bootstrap.min.css" />-->
<link rel="stylesheet" href="{{ asset('assests/theme/css/bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assests/theme/css/font-awesome.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assests/theme/js/ionicons.js') }}" />
<link rel="stylesheet" href="{{ asset('assests/theme/css/owl.carousel.min.css') }}" />
<link href="{{ asset('assests/theme/css/animate.css') }}" rel='stylesheet' type='text/css' />
<link rel="stylesheet" type="text/css" href="{{ asset('assests/theme/css/style.css') }}" />

<link rel="stylesheet" href="{{ asset('assests/parsley/parsley.css') }}">
</head>

<body class="login-body">
<section class="main-container">
	<div class="container">
    	<div class="entry-container">      
        	<div class="form-box form-wrap">
				<div class="row">
					<!-- <div class="col-lg-6">
						<div class="log-graphics">
							<div class="logo"><img src="{{ Helper::loginLogo() }}" alt=""></div>
							<div class="title-div">
							<p>Sign in to your account</p>
							</div>
						</div>
					</div> -->
					<div class="col-lg-12">
						<div class="log-graphics">
							<div class="logo"><img src="{{ Helper::loginLogo() }}" alt=""></div>
							<div class="title-div">
							<p>Sign in to your account</p>
							</div>
						</div>
						<div class="mt-2 mr-1">
						@if ($errors->any())
            				<x-alert-message :message="$errors->all()" />
       					@endif

						@if ($message = session('agent_signin'))
            				<x-alert-message :type="$message[1]" :message="$message[0]" />
       	 				@endif
						</div>
						<form class="login-form" action="" method="POST" id="login-form">
                            @csrf
							<fieldset>
							    <label for="email">Email ID</label>
                                <input id="email" class="form-control" type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
							</fieldset>

							<fieldset>
                                <label for="password">Password</label>
                                <input id="password" class="form-control" type="password" placeholder="Password" name="password" required minlength="6">
							</fieldset>
							<a href="{{ route('resetpassword') }}" class="forgot-btn">Forget your password?</a>
							<button type="submit" name="submit" class="btn-primary btn-block">Log In</button>
						</form>
					</div>
				</div>
        	</div>
			<!-- Login End -->
        </div>
    </div>
</section>
<!-- Scripts -->
<script src="{{ asset('assests/theme/js/jquery.min.js') }}"></script>
<script src="{{ asset('assests/theme/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assests/theme/js/ionicons.js') }}"></script>
<script src="{{ asset('assests/theme/js/owl.carousel.js') }}"></script>
<script src="{{ asset('assests/parsley/parsley.min.js') }}"></script>
<script src="{{ asset('assests/theme/js/customize.js') }}"></script>

<script>
    $(function(){
        $('#login-form').parsley();
    });
</script>
</body>
</html>
