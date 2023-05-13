function apply_coupon()
{
	let user_id = localStorage.getItem('user_id');

	if(user_id)
	{
		let data = {'coupon_code': document.getElementById("coupon_code").value};

		let xhr = new XMLHttpRequest();
		let ajax_url = base_url + 'ajax/user/apply-coupon';

		xhr.open("POST", ajax_url);
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.send(JSON.stringify(data));

		xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200)
	   		{
	   			let response = JSON.parse(xhr.responseText);

	   			if(response['status'] == 1)
	   			{
	   				document.getElementById("coupon_form").style.display = 'none';
	   				document.getElementById("coupon_button").style.display = 'none';
	   				document.getElementById("remove_coupon").removeAttribute("style");

	   				document.getElementById("cart_total_value_cl").innerHTML = response['cart_total_value'];
	   				document.getElementById("cart_total_value_hd").innerHTML = response['cart_total_value'];

	   				toastr.success(response['message']);
	   			}else{
	   				toastr.error(response['message']);
	   			}
	   		}
		};
	}else{
		toastr.info('You Are Not Logged-In Right Now!');
	}
}

function remove_coupon()
{
	let user_id = localStorage.getItem('user_id');

	if(user_id)
	{
		let xhr = new XMLHttpRequest();
		let ajax_url = base_url + 'ajax/user/remove-coupon';

		xhr.open("GET", ajax_url);
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.send();

		xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200)
	   		{
	   			let response = JSON.parse(xhr.responseText);

	   			if(response['status'] == 1)
	   			{
	   				document.getElementById("remove_coupon").style.display = 'none';
	   				document.getElementById("coupon_form").removeAttribute("style");
	   				document.getElementById("coupon_button").removeAttribute("style");

	   				document.getElementById("cart_total_value_cl").innerHTML = response['cart_total_value'];
	   				document.getElementById("cart_total_value_hd").innerHTML = response['cart_total_value'];

	   				toastr.success(response['message']);
	   			}else{
	   				toastr.error(response['message']);
	   			}
	   		}
		};
	}else{
		toastr.info('You Are Not Logged-In Right Now!');
	}
}

function checkout_verify()
{
	let user_id = localStorage.getItem('user_id');

	if(user_id)
	{
		let xhr = new XMLHttpRequest();
		let ajax_url = base_url + 'ajax/user/checkout-verify';

		xhr.open("GET", ajax_url);
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.send();

		xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200)
	   		{
	   			let response = JSON.parse(xhr.responseText);

	   			if(response['status'] == 1)
	   			{
	   				let checkout_url = base_url + 'user/checkout';
	   				window.location.replace(checkout_url);
	   			}else{
	   				toastr.error(response['message']);
	   			}
	   		}
		};
	}else{
		login_url = base_url + 'sign-in';
		window.location.replace(login_url);
		//toastr.info('You Are Not Logged-In Right Now!');
	}
}
