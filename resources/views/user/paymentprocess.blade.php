<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="{{ asset('assests/toastr/toastr.min.css') }}" />
	<title>{{ env('APP_NAME') }} | Validate Payment</title>
	<style>
		.loader {
		    background: #ffffff;
		    width: 100%;
		    height: 100%;
		    line-height: 50px;
		    text-align: center;
		    position: fixed;
		    top: 50%;
		    left: 50%;
		    transform: translate(-50%, -50%);
		    font-family: helvetica, arial, sans-serif;
		    font-weight: 900;
		    letter-spacing: 0.2em;
		    z-index: 9999999;
		}
		.loader span {
		    position: absolute;
		    width: 250px;
		    top: 50%;
		    left: 50%;
		    transform: translate(-50%, -50%);
		    color: #000;
		    text-transform: uppercase;
		}
		.loader span::before,
		.loader span::after {
		    content: "";
		    display: block;
		    width: 15px;
		    height: 15px;
		    background: #ed5e29;
		    position: absolute;
		    animation: load 0.7s infinite alternate ease-in-out;
		}
		.loader span::before {
		    top: 0;
		}
		.loader span::after {
		    bottom: 0;
		}
		@keyframes load {
		    0% {
		        left: 0;
		        height: 30px;
		        width: 15px;
		    }
		    50% {
		        height: 8px;
		        width: 40px;
		    }
		    100% {
		        left: 235px;
		        height: 30px;
		        width: 15px;
		    }
		}

	</style>
</head>
<body>
	<div class="loader" ><span>Processing...</span>
  		Please wait
	</div>
	
	<script src="{{ asset('assests/frontend/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('assests/toastr/toastr.min.js') }}"></script>

	<script>
        $(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            callAjax();
        });
        function callAjax() {
            formdata = {};
			
            $.ajax({
                type: "GET",
                url: "{{ $ajaxURL }}",
                dataType: "JSON",
                success: function (response) {
                    if (response.type == 'success') {
						alert(response.message);
						window.location.replace(response.url);
					} else if(response.type == 'error') {
						if (Array.isArray(response.message)) {
							response.message.forEach(function (val) { toastr.error(val); });
						} else { toastr.error(response.message); }
					}else if(response.type == 'refresh'){
						window.location.reload();
					}
                }
            }); 
        }
    </script>
</body>
</html>