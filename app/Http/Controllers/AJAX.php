<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use Cart;
use Session;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CustomerWishlist;
use App\Http\Controllers\Controller;
use App\Models\CustomerBillingaddress;
use App\Models\CustomerShippingaddress;
use Illuminate\Support\Facades\Validator;

class AJAX extends Controller
{
	public function __construct()
	{
		$this->user_model = new User;
		$this->coupon_model = new Coupon;
		$this->product_model = new Product;
		$this->wishlist_model = new CustomerWishlist;
		$this->customer_billing_address_model = new CustomerBillingaddress;
		$this->customer_shipping_address_model = new CustomerShippingaddress;
	}

	public function product_variant_data(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'product_id' => 'required|integer',
			'specification' => 'required'
		],[],[
			'product_id' => 'Product ID',
			'specification' => 'Product Variant'
		]);

		if(!$validator->fails())
		{
			$form_data = Request()->post();
			$product_id = trim($form_data['product_id']);
			$specification = trim($form_data['specification']);

			$product_data = $this->product_model
				->where('id', $product_id)
				->where('product_type','2')
				->whereJsonContains('specifications', $specification)
				->first();

			if(!empty($product_data))
			{
				$mrp = json_decode($product_data->mrp);
                $discounted_price = json_decode($product_data->discounted_price);
                $specifications_1 = explode(',', $product_data->specifications_1);
                $specifications_2 = explode(',', $product_data->specifications_2);
                $specifications_3 = explode(',', $product_data->specifications_3);
                $specifications = json_decode($product_data->specifications);
                $sku = json_decode($product_data->sku);

				foreach($specifications as $index => $value)
				{
					if($specification == $value)
					{
						$variant_index = $index; break;
					}
				}

				if(isset($variant_index))
				{
					if(Auth::check())
					{
						$wishlist_data = $this->wishlist_model->where(['user_id' => Auth::id(), 'product_id' => $product_data->id, 'variant_index' => $variant_index])->first();
					}

					$response = [
						'status'	=> 1,
						'wishlist'	=> (!empty($wishlist_data)) ? 1 : 0,
						'data'		=> [
							'product_id'		=> $product_data->id,
							'variant_index'		=> $variant_index,
							'mrp'				=> $mrp[$variant_index],
							'discounted_price'	=> $discounted_price[$variant_index],
							'specifications'	=> $specifications[$variant_index],
							'sku'				=> $sku[$variant_index]
						]
					];
				}else{
					$response = [
						'status'	=> 0,
						'message'	=> 'Product Variant Not Found.'
					];
				}
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Product Data Not Found.'
				];
			}
		}else{
			$response = [
				'status'	=> 0,
				'message'	=> $validator->errors()->first()
			];
		}

		return response()->json($response);
    }

    public function add_to_cart(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'product_id' => 'required|integer',
			'variant_index' => 'required|integer'
		],[],[
			'product_id' => 'Product ID',
			'variant_index' => 'Product Variant'
		]);

		if(!$validator->fails())
		{
			$form_data = Request()->post();
			$product_id = trim($form_data['product_id']);
			$variant_index = trim($form_data['variant_index']);

			$product_data = $this->product_model->where('id', $product_id)->first();

			if(!empty($product_data))
			{
				$remain_stock = ($product_data->number_of_items - 1);

				if($remain_stock >= 0)
				{
					$cart_product_id = (int)($product_id.$variant_index);
					$cart_product_data = Cart::get($cart_product_id);

					if(empty($cart_product_data))
					{
						if($product_data->product_type == '1')
						{
							Cart::add([
								'id'			=> $cart_product_id,
								'name'			=> $product_data->name,
								'price'			=> (!empty($product_data->discounted_price)) ? $product_data->discounted_price : $product_data->mrp,
								'quantity'		=> 1,
								'attributes'	=> [
									'product_id'	=> $product_data->id,
									'product_slug'	=> $product_data->slug,
									'product_image'	=> $product_data->featuredimage,
									'product_type'	=> $product_data->product_type,
									'vendor_id'		=> $product_data->user_id,
									'sku'			=> NULL,
									'discounted_price' 	=> NULL,
									'mrp'				=> NULL,
									'variant_index'	=> NULL
								]
							]);

							if(!Cart::isEmpty())
					        {
					            $cart_data['cart_items'] = Cart::getContent()->toArray();
					            $cart_data['cart_total_items'] = Cart::getContent()->count();
					            $cart_data['cart_total_value'] = Cart::getTotal();
					        }else{
					        	$cart_data['cart_items'] = [];
					            $cart_data['cart_total_items'] = 0;
					            $cart_data['cart_total_value'] = 0;
					        }

							$response = [
								'status'	=> 1,
								'message'	=> 'Product Has Been Added To Cart.',
								'currency'	=> \Helper::ccurrency(),
								'cart_data'	=> $cart_data
							];
						}elseif($product_data->product_type == '2')
						{
							$mrp = json_decode($product_data->mrp);
			                $discounted_price = json_decode($product_data->discounted_price);
			                $specifications = json_decode($product_data->specifications);

			                if(!empty($specifications[$variant_index]))
			                {
			                	Cart::add([
									'id'			=> $cart_product_id,
									'name'			=> $product_data->name.' ('.$specifications[$variant_index].')',
									'price'			=> (!empty($discounted_price[$variant_index])) ? $discounted_price[$variant_index] : $mrp[$variant_index],
									'quantity'		=> 1,
									'attributes'	=> [
										'product_id'	=> $product_data->id,
										'product_slug'	=> $product_data->slug,
										'product_image'	=> $product_data->featuredimage,
										'product_type'	=> $product_data->product_type,
										'vendor_id'		=> $product_data->user_id,
										'sku'			=> NULL,
										'discounted_price' 	=> NULL,
										'mrp'				=> NULL,
										'variant_index'	=> NULL
									]
								]);

								if(!Cart::isEmpty())
						        {
						            $cart_data['cart_items'] = Cart::getContent()->toArray();
						            $cart_data['cart_total_items'] = Cart::getContent()->count();
						            $cart_data['cart_total_value'] = Cart::getTotal();
						        }else{
						        	$cart_data['cart_items'] = [];
						            $cart_data['cart_total_items'] = 0;
						            $cart_data['cart_total_value'] = 0;
						        }

								$response = [
									'status'	=> 1,
									'message'	=> 'Product Has Been Added To Cart.',
									'currency'	=> \Helper::ccurrency(),
									'cart_data'	=> $cart_data
								];
			                }else{
			                	$response = [
									'status'	=> 0,
									'message'	=> 'Product Data Is Not Valid.'
								];
			                }
						}else{
							$response = [
								'status'	=> 0,
								'message'	=> 'Product Type Is Not Valid.'
							];
						}
					}else{
						$response = [
							'status'	=> 2,
							'message'	=> 'Product Is Already In Cart.'
						];
					}
				}else{
					$response = [
						'status'	=> 0,
						'message'	=> 'Product Is Out Of Stock.'
					];
				}
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Product Data Not Found.'
				];
			}
		}else{
			$response = [
				'status'	=> 0,
				'message'	=> $validator->errors()->first()
			];
		}

		return response()->json($response);
    }

    public function remove_cart_item(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'cart_item_id' => 'required|integer'
		],[],[
			'cart_item_id' => 'Cart Item ID'
		]);

		if(!$validator->fails())
		{
			$form_data = Request()->post();
			$cart_item_id = trim($form_data['cart_item_id']);
			$cart_item_data = Cart::get($cart_item_id);

			if(!empty($cart_item_data))
			{
				Cart::remove($cart_item_id);

				if(!Cart::isEmpty())
		        {
		            $cart_data['cart_items'] = Cart::getContent()->toArray();
		            $cart_data['cart_total_items'] = Cart::getContent()->count();
		            $cart_data['cart_total_value'] = Cart::getTotal();
		        }else{
		        	$cart_data['cart_items'] = [];
		            $cart_data['cart_total_items'] = 0;
		            $cart_data['cart_total_value'] = 0;
		        }

		        $response = [
					'status'	=> 1,
					'message'	=> 'Cart Item Has Been Removed.',
					'cart_data'	=> $cart_data
				];
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Cart Item Is Not Valid.'
				];
			}
		}else{
			$response = [
				'status'	=> 0,
				'message'	=> $validator->errors()->first()
			];
		}

		return response()->json($response);
    }

    public function update_cart_item(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'cart_item_id' => 'required|integer',
			'cart_item_qty' => 'required|integer|gt:0'
		],[],[
			'cart_item_id' => 'Cart Item ID',
			'cart_item_qty' => 'Cart Item Quantity'
		]);

		if(!$validator->fails())
		{
			$form_data = Request()->post();
			$cart_item_id = trim($form_data['cart_item_id']);
			$cart_item_qty = trim($form_data['cart_item_qty']);
			$cart_item_data = Cart::get($cart_item_id);

			if(!empty($cart_item_data))
			{
				Cart::update($cart_item_id, [
					'quantity' => [
						'relative'	=> FALSE,
						'value'		=> $cart_item_qty
					]
				]);

				$cart_data['cart_item_data'] = Cart::get($cart_item_id)->toArray();
	            $cart_data['cart_total_items'] = Cart::getContent()->count();
	            $cart_data['cart_total_value'] = Cart::getTotal();

		        $response = [
					'status'	=> 1,
					'message'	=> 'Cart Item Has Been Updated.',
					'cart_data'	=> $cart_data
				];
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Cart Item Is Not Valid.'
				];
			}
		}else{
			$response = [
				'status'	=> 0,
				'message'	=> $validator->errors()->first()
			];
		}

		return response()->json($response);
    }

    public function add_remove_wishlist_item(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'product_id' => 'required|integer',
			'variant_index' => 'required|integer'
		],[],[
			'product_id' => 'Product ID',
			'variant_index' => 'Product Variant'
		]);

		if(!$validator->fails())
		{
			$form_data = Request()->post();
			$product_id = trim($form_data['product_id']);
			$variant_index = trim($form_data['variant_index']);

			$product_data = $this->product_model->where('id', $product_id)->first();
			$wishlist_data = $this->wishlist_model->where(['user_id' => Auth::id(), 'product_id' => $product_id, 'variant_index' => $variant_index])->first();

			if(!empty($product_data))
			{
				if(empty($wishlist_data))
				{
					$this->wishlist_model->create(['user_id' => Auth::id(), 'product_id' => $product_id, 'variant_index' => $variant_index]);
					$total_wishlist_items = $this->wishlist_model->where('user_id', Auth::id())->count();

					$response = [
						'status'	=> 2,
						'message'	=> 'Product Has Been Added In Your Wishlist.',
						'wishlist'	=> $total_wishlist_items
					];
				}else{
					$this->wishlist_model->where('id', $wishlist_data->id)->forceDelete();
					$total_wishlist_items = $this->wishlist_model->where('user_id', Auth::id())->count();

					$response = [
						'status'	=> 1,
						'message'	=> 'Product Has Been Removed From Your Wishlist.',
						'wishlist'	=> $total_wishlist_items
					];
				}
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Product Data Not Found.'
				];
			}
		}else{
			$response = [
				'status'	=> 0,
				'message'	=> $validator->errors()->first()
			];
		}

		return response()->json($response);
    }

    public function apply_coupon(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'coupon_code' => 'required'
		],[],[
			'coupon_code' => 'Coupon Code'
		]);

		if(!$validator->fails())
		{
			$today_date = date('Y-m-d');
			$form_data = Request()->post();
			$coupon_code = trim($form_data['coupon_code']);
			$coupon_data = $this->coupon_model->where('code', $coupon_code)->where('expire_date', '>=', $today_date)->first();

			if(!empty($coupon_data))
			{
				if(!Cart::isEmpty())
		        {
		            $cart_total_value = Cart::getTotal();

		            if($coupon_data->discount_type == '1')
		            {
		            	$discount_value = $coupon_data->discount;
		            	$checkout_value = round(($cart_total_value - $coupon_data->discount), 2);
		            }else{
		            	$discount_value = (($coupon_data->discount / 100) * $cart_total_value);
		            	$checkout_value = round(($cart_total_value - (($coupon_data->discount / 100) * $cart_total_value)), 2);
		            }

		            if($checkout_value > 0)
		            {
		            	$cart_condition = new \Darryldecode\Cart\CartCondition([
		            		'name'		=> 'coupon',
		            		'type'		=> 'discount',
		            		'target'	=> 'total',
		            		'value'		=> '-'.$discount_value,
							'attributes'=> [
								'coupon_code'	=> $coupon_data->code,
								'id'			=> $coupon_data->id,
								'discount_type'	=> $coupon_data->discount_type,
								'discount'		=> $coupon_data->discount
							]
		            	]);

		            	Cart::condition($cart_condition);
			            $cart_total_value = Cart::getTotal();

		            	$response = [
							'status'			=> 1,
							'message'			=> 'Your Coupon Applied Successfully.',
							'cart_total_value'	=> $cart_total_value
						];
		            }else{
		            	$response = [
							'status'	=> 0,
							'message'	=> 'You Are Not Eligible For This Coupon Yet.'
						];
		            }
		        }else{
		        	$response = [
						'status'	=> 0,
						'message'	=> 'Your Cart Is Empty.'
					];
		        }
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Invalid Coupon Code.'
				];
			}
		}else{
			$response = [
				'status'	=> 0,
				'message'	=> $validator->errors()->first()
			];
		}

		return response()->json($response);
    }

    public function remove_coupon()
    {
    	Cart::clearCartConditions();
    	$cart_total_value = Cart::getTotal();

    	$response = [
			'status'			=> 1,
			'message'			=> 'Your Coupon Removed Successfully.',
			'cart_total_value'	=> $cart_total_value
		];

		return response()->json($response);
    }

    public function checkout_verify()
    {
    	if(Auth::check())
    	{
    		$cart_total_value = Cart::getTotal();

    		if((!Cart::isEmpty()) AND ($cart_total_value > 0))
    		{
    			$billing_address = $this->customer_billing_address_model->where('customer_id', Auth::id())->first();
    			$shipping_address = $this->customer_shipping_address_model->where('customer_id', Auth::id())->first();

    			if((!empty($billing_address->title)) AND (!empty($billing_address->fname)) AND (!empty($billing_address->lname)) AND (!empty($billing_address->email)) AND (!empty($billing_address->contact)) AND (!empty($billing_address->address)) AND (!empty($billing_address->pin_code)) AND (!empty($billing_address->state_id)) AND (!empty($shipping_address->title)) AND (!empty($shipping_address->fname)) AND (!empty($shipping_address->lname)) AND (!empty($shipping_address->email)) AND (!empty($shipping_address->contact)) AND (!empty($shipping_address->address)) AND (!empty($shipping_address->pin_code)) AND (!empty($shipping_address->state_id)))
    			{
    				$response = ['status' => 1];
    			}else{
    				$response = [
						'status'	=> 0,
						'message'	=> 'Billing Or Shipping Address Not Found.'
					];
    			}
    		}else{
    			$response = [
					'status'	=> 0,
					'message'	=> 'Your Cart Is Empty.'
				];
    		}
    	}else{
    		$response = [
				'status'	=> 0,
				'message'	=> 'You Are Not Logged-In Right Now!'
			];
    	}

		return response()->json($response);
    }
}

?>