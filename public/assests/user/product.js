function switch_variant(product_id)
{
	let a1_value = document.getElementById("attribute_1").value;
	let a2_value = document.getElementById("attribute_2").value;
	let a3_value = document.getElementById("attribute_3").value;

	if((a1_value) && (a2_value) && (a3_value)){ var specification = a1_value + '-' + a2_value + '-' + a3_value; }
	else if((a1_value) && (a2_value)){ var specification = a1_value + '-' + a2_value; }
	else{ var specification = a1_value; }

	let data = {'product_id': product_id, 'specification': specification};

	let xhr = new XMLHttpRequest();
	let ajax_url = base_url + 'ajax/switch-product-variant';

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
   				if(response['data']['discounted_price'])
   				{
   					var template = `<del>&#x9F3;` + response['data']['mrp'] + `</del>
                                    <span>&#x9F3;`+ response['data']['discounted_price'] +`<small>/Unit</small></span>`;
   				}else{
   					var template = `<span>&#x9F3;` + response['data']['mrp'] + `<small>/Unit</small></span>`;
   				}

   				let cart_function = 'add_to_cart(' + response['data']['product_id'] + ', ' + response['data']['variant_index'] + ');';
   				let wishlist_function = 'add_remove_wishlist_item(' + response['data']['product_id'] + ', ' + response['data']['variant_index'] + ', 1);';

   				document.getElementById("specifications").innerHTML = response['data']['specifications'];
   				document.getElementById("sku").innerHTML = response['data']['sku'];
   				document.getElementById("prices").innerHTML = template;
   				document.getElementById("cart_btn").setAttribute("onclick","javascript:" + cart_function);
   				document.getElementById("wishlist_btn").setAttribute("onclick","javascript:" + wishlist_function);

   				if(response['wishlist'] == 0)
   				{
   					document.getElementById("wishlist_btn").title = 'Add To Wishlist';
	   				document.getElementById("wishlist_tag").innerHTML = 'Add To Wishlist';
   				}else{
   					document.getElementById("wishlist_btn").title = 'Remove From Wishlist';
	   				document.getElementById("wishlist_tag").innerHTML = 'Remove From Wishlist';
   				}
   			}else{
   				toastr.error(response['message']);
   			}
   		}
	};
}

function add_to_cart(product_id, variant_index)
{
	let data = {'product_id': product_id, 'variant_index': variant_index};

	let xhr = new XMLHttpRequest();
	let ajax_url = base_url + 'ajax/user/add-to-cart';

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
   				document.getElementById("cart_total_items_hd").innerHTML = response['cart_data']['cart_total_items'];
   				document.getElementById("cart_total_items_cl").innerHTML = response['cart_data']['cart_total_items'];
   				document.getElementById("cart_total_items_tm").innerHTML = response['cart_data']['cart_total_items'];
   				document.getElementById("cart_total_value_hd").innerHTML = response['cart_data']['cart_total_value'];
   				document.getElementById("cart_total_value_cl").innerHTML = response['cart_data']['cart_total_value'];

   				document.getElementById("cart_items").innerHTML = null;
   				let value = response['cart_data']['cart_items'];

   				Object.keys(value).forEach(index =>
   				{
   					let template = `<li class="cart-item" id="cart_item_` + value[index]['id'] + `">
				                        <div class="cart-media">
				                            <a href="` + base_url + `product/` + value[index]['attributes']['product_slug'] + `">
				                                <img src="` + base_url + value[index]['attributes']['product_image'] + `"></a>
				                            <button class="cart-delete" onclick="remove_cart_item(` + value[index]['id'] + `);"><i class="far fa-trash-alt"></i></button>
				                        </div>
				                        <div class="cart-info-group">
				                            <div class="cart-info">
				                                <h6><a href="` + base_url + `product/` + value[index]['attributes']['product_slug'] + `">` + value[index]['name'] + `</a></h6>
				                                <p>Unit Price - ` + response['currency'] + `<span id="unit_price_` + value[index]['id'] + `">` + value[index]['price'] + `</span></p>
				                            </div>
				                            <div class="cart-action-group">
				                                <div class="product-action">
				                                    <button class="action-minus" title="Decrease" id="decrease_btn_` + value[index]['id'] + `" onclick="update_cart_item(` + value[index]['id'] + `);" disabled><i class="icofont-minus"></i></button>
				                                    <input class="action-input" title="Quantity" type="text" id="quantity_box_` + value[index]['id'] + `" value="` + value[index]['quantity'] + `" oninput="update_cart_item(` + value[index]['id'] + `);">
				                                    <button class="action-plus" title="Increase" id="increase_btn_` + value[index]['id'] + `" onclick="update_cart_item(` + value[index]['id'] + `);" disabled><i class="icofont-plus"></i></button>
				                                </div>
				                                <h6>` + response['currency'] + `<span id="total_price_` + value[index]['id'] + `">` + (Number(value[index]['price']) * parseInt(value[index]['quantity'])) + `</span></h6>
				                            </div>
				                        </div>
				                    </li>`;

	               	document.getElementById("cart_items").innerHTML += template;
	            });

   				toastr.success(response['message']);
   			}
   			else if(response['status'] == 2)
   			{
   				toastr.success(response['message']);
   			}else{
   				toastr.error(response['message']);
   			}
   		}
	};
}

function remove_cart_item(cart_item_id)
{
	let data = {'cart_item_id': cart_item_id};

	let xhr = new XMLHttpRequest();
	let ajax_url = base_url + 'ajax/user/remove-cart-item';

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
   				document.getElementById("cart_item_" + cart_item_id).remove();
   				document.getElementById("cart_total_items_hd").innerHTML = response['cart_data']['cart_total_items'];
   				document.getElementById("cart_total_items_cl").innerHTML = response['cart_data']['cart_total_items'];
   				document.getElementById("cart_total_items_tm").innerHTML = response['cart_data']['cart_total_items'];
   				document.getElementById("cart_total_value_hd").innerHTML = response['cart_data']['cart_total_value'];
   				document.getElementById("cart_total_value_cl").innerHTML = response['cart_data']['cart_total_value'];

   				toastr.success(response['message']);
   			}else{
   				toastr.error(response['message']);
   			}
   		}
	};
}

function update_cart_item(cart_item_id)
{
	document.getElementById("decrease_btn_" + cart_item_id).disabled = true;
	document.getElementById("increase_btn_" + cart_item_id).disabled = true;
	document.getElementById("quantity_box_" + cart_item_id).disabled = true;

	setTimeout(function()
	{
		let cart_item_qty = parseInt(document.getElementById("quantity_box_" + cart_item_id).value);

		if(cart_item_qty > 0)
		{
			let data = {'cart_item_id': cart_item_id, 'cart_item_qty': cart_item_qty};

			let xhr = new XMLHttpRequest();
			let ajax_url = base_url + 'ajax/user/update-cart-item';

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
		   				document.getElementById("unit_price_" + cart_item_id).innerHTML = response['cart_data']['cart_item_data']['price'];
		   				document.getElementById("quantity_box_" + cart_item_id).innerHTML = response['cart_data']['cart_item_data']['quantity'];
		   				document.getElementById("total_price_" + cart_item_id).innerHTML = (Number(response['cart_data']['cart_item_data']['price']) * parseInt(response['cart_data']['cart_item_data']['quantity']));

		   				document.getElementById("cart_total_items_hd").innerHTML = response['cart_data']['cart_total_items'];
		   				document.getElementById("cart_total_items_cl").innerHTML = response['cart_data']['cart_total_items'];
		   				document.getElementById("cart_total_items_tm").innerHTML = response['cart_data']['cart_total_items'];
		   				document.getElementById("cart_total_value_hd").innerHTML = response['cart_data']['cart_total_value'];
		   				document.getElementById("cart_total_value_cl").innerHTML = response['cart_data']['cart_total_value'];

		   				toastr.success(response['message']);
		   			}else{
		   				toastr.error(response['message']);
		   			}
		   		}
			};
		}else{
			toastr.error('Quantity Should Not Be Less Than One.');
		}

		document.getElementById("decrease_btn_" + cart_item_id).disabled = false;
		document.getElementById("increase_btn_" + cart_item_id).disabled = false;
		document.getElementById("quantity_box_" + cart_item_id).disabled = false;
	}, 1000);
}

function add_remove_wishlist_item(product_id, variant_index, button_type)
{
	let user_id = localStorage.getItem('user_id');

	if(user_id)
	{
		let data = {'product_id': product_id, 'variant_index': variant_index};

		let xhr = new XMLHttpRequest();
		let ajax_url = base_url + 'ajax/user/add-remove-wishlist-item';

		xhr.open("POST", ajax_url);
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.send(JSON.stringify(data));

		xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200)
	   		{
	   			let response = JSON.parse(xhr.responseText);

	   			if(response['status'] == 2)
	   			{
	   				if(button_type == 0)
	   				{
	   					document.getElementById("wishlist_btn_" + product_id).classList.add('active');
	   				}
	   				else if(button_type == 1)
	   				{
	   					document.getElementById("wishlist_btn").title = 'Remove From Wishlist';
	   					document.getElementById("wishlist_tag").innerHTML = 'Remove From Wishlist';
	   				}else{
	   					window.location.reload();
	   				}

	   				document.getElementById("wishlist_total_items_hd").innerHTML = response['wishlist'];
	   				document.getElementById("wishlist_total_items_tm").innerHTML = response['wishlist'];

	   				toastr.success(response['message']);
	   			}
	   			else if(response['status'] == 1)
	   			{
	   				if(button_type == 0)
	   				{
	   					document.getElementById("wishlist_btn_" + product_id).classList.remove('active');
	   				}
	   				else if(button_type == 1)
	   				{
	   					document.getElementById("wishlist_btn").title = 'Add To Wishlist';
	   					document.getElementById("wishlist_tag").innerHTML = 'Add To Wishlist';
	   				}else{
	   					document.getElementById(product_id + "-" + variant_index).remove();
	   				}

	   				document.getElementById("wishlist_total_items_hd").innerHTML = response['wishlist'];
	   				document.getElementById("wishlist_total_items_tm").innerHTML = response['wishlist'];

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
