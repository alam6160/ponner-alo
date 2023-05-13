<?php

namespace App\Http\Controllers\User;

use Session;
use Validator;

use App\Models\User;
use App\Models\State;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Web;
use App\Models\CustomerProfile;
use App\Models\CustomerWishlist;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerBillingaddress;
use App\Models\CustomerShippingaddress;
use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;

class CheckoutController extends Controller
{
    public function __construct()
	{
		$this->web_controller = new Web;
		$this->user_model = new User;
		$this->product_model = new Product;
		$this->wishlist_model = new CustomerWishlist;
        $this->state_model = new State;
		$this->customer_profile_model = new CustomerProfile;
		$this->customer_billing_address_model = new CustomerBillingaddress;
		$this->customer_shipping_address_model = new CustomerShippingaddress;
	}
    public function index(Request $request)
    {
		if (\Darryldecode\Cart\Facades\CartFacade::isEmpty()) {
			return redirect()->route('home');
		}
        $data = $this->web_controller->common();
        $data['title'] = 'Checkout';
        $data['states_list'] = $this->state_model->get();
        $data['user_data'] = DB::table('users')
    					->join('customer_profiles', 'customer_profiles.customer_id','=','users.id')
    					->join('customer_billingaddresses', 'customer_billingaddresses.customer_id','=','users.id')
    					->join('customer_shippingaddresses', 'customer_shippingaddresses.customer_id','=','users.id')
    					->select('users.*','customer_profiles.address AS r_address','customer_profiles.pin_code AS r_pincode','customer_profiles.state_id AS r_stateid','customer_billingaddresses.title AS b_title','customer_billingaddresses.fname AS b_fname','customer_billingaddresses.lname AS b_lname','customer_billingaddresses.email AS b_email','customer_billingaddresses.contact AS b_contact','customer_billingaddresses.address AS b_address','customer_billingaddresses.pin_code AS b_pincode','customer_billingaddresses.state_id AS b_stateid','customer_shippingaddresses.title AS s_title','customer_shippingaddresses.fname AS s_fname','customer_shippingaddresses.lname AS s_lname','customer_shippingaddresses.email AS s_email','customer_shippingaddresses.contact AS s_contact','customer_shippingaddresses.address AS s_address','customer_shippingaddresses.pin_code AS s_pincode','customer_shippingaddresses.state_id AS s_stateid')
    					->where('users.id', Auth::id())
    					->first();
        
        $data['items'] = \Darryldecode\Cart\Facades\CartFacade::getContent();
        $data['subTotal'] = \Darryldecode\Cart\Facades\CartFacade::getSubTotal();
        $data['total'] = \Darryldecode\Cart\Facades\CartFacade::getTotal();

        $data['cartCondition'] = \Darryldecode\Cart\Facades\CartFacade::getCondition('coupon');
                        
        return view('user.checkout', $data);
    }
	public function submit_checkout(Request $request)
	{

		if ($request->ajax()) {
			$validator = validator($request->all(), [
				"payment_mode"=>"required|in:1,2"
			]);

			if ($validator->fails()) {
				$output = ['type'=>'error', 'message'=> $validator->errors()->all()];
			} else {
	
				$items = \Darryldecode\Cart\Facades\CartFacade::getContent();
				$subTotal = \Darryldecode\Cart\Facades\CartFacade::getSubTotal();
				$total = \Darryldecode\Cart\Facades\CartFacade::getTotal();
				$cartCondition = \Darryldecode\Cart\Facades\CartFacade::getCondition('coupon');

				$user = \App\Models\User::find( Auth::id() );
				$shipping_address = json_encode($user->customer_shipping->only(['title','fname','lname','email','contact','alt_contact','address','pin_code','state_id']));
				$billing_address = json_encode($user->customer_billing->only(['title','fname','lname','email','contact','alt_contact','address','pin_code','state_id']));

				$discount =  0; $coupon_id = NULL; $coupon_code = NULL; $coupon_desc = NULL;
				if (!blank($cartCondition)) {
					$discount = str_replace('-','', $cartCondition->getValue());

					$getAttributes = $cartCondition->getAttributes();
					$coupon_code = $getAttributes['coupon_code'];
					$coupon_id = $getAttributes['id'];

					$coupon_desc = json_encode([
						'discount_type'	=> $getAttributes['discount_type'],
						'discount'		=> $getAttributes['discount'],
					]);
				}

				if ($request->payment_mode == '1') {
					
					$orderDetails = new \App\Models\OrderDetails();
					$orderDetails->order_no 		= \App\Helper\Clib::generateOrderNo();
					$orderDetails->order_key 		= md5($orderDetails->order_no);
					$orderDetails->user_id 			= Auth::id();
					$orderDetails->order_date 		= date('Y-m-d');
					$orderDetails->sub_total 		= floatval($subTotal);
					$orderDetails->discount 		= floatval($discount);
					$orderDetails->delivery_charge 	= 0;
					$orderDetails->grand_total 		= floatval($total);
					$orderDetails->payment_mode 	= '1';
					$orderDetails->payment_status 	= '1';

					$orderDetails->shipping_address	= $shipping_address;
					$orderDetails->billing_address 	= $billing_address;
					
					$orderDetails->coupon_id = (empty($coupon_id)) ? NULL : $coupon_id;
					$orderDetails->coupon_code = (empty($coupon_code)) ? NULL : $coupon_code;
					$orderDetails->coupon_desc = (empty($coupon_desc)) ? NULL : $coupon_desc;

					$orderDetails->save();

					foreach ($items as $key => $item) {

						$wallet = NULL;  $commission = NULL; $vendor_type = NULL; $admin_charge = NULL;
						if (!blank($item->attributes->vendor_id)) {
			
							$vendor  = \App\Models\User::select('vendor_type')->withTrashed()->find($item->attributes->vendor_id);
			
							if (!blank($vendor)) {
								$vendor_type = $vendor->vendor_type;
								if ($vendor->vendor_type == '1') {
				
									$siteSetting = \App\Models\SiteSetting::select('key_value')->where('key_name', 'commission')->first();
				
									$commission = (blank($siteSetting)) ? 0 : floatval($siteSetting->key_value);
									$admin_charge = ($commission * floatval($item->getPriceSum()) ) / 100;
									$wallet = floatval($item->getPriceSum()) - floatval($admin_charge);
				
								} else {
									$commission = NULL;
									$admin_charge = NULL;
									$wallet = $item->price;
								}
							}
						}
			
						$orderItem = new \App\Models\OrderItem();
						$orderItem->order_no = $orderDetails->order_no;
						$orderItem->product_id = $item->attributes->product_id;
						$orderItem->qty = intval($item->quantity);
						$orderItem->sub_total = floatval($item->price);
						$orderItem->grand_total = floatval($item->getPriceSum());
						$orderItem->product_name = $item->name;
						$orderItem->product_sku = $item->attributes->sku;
						$orderItem->product_type = $item->attributes->product_type;
						$orderItem->product_desc = json_encode([
							'discounted_price'	=> $item->attributes->discounted_price,
							'mrp'				=> $item->attributes->mrp,
							'product_image'		=> $item->attributes->product_image
						]);
						$orderItem->agent_id 		= $item->attributes->vendor_id;
						$orderItem->vendor_type 	= $vendor_type;
						$orderItem->wallet 			= (empty($wallet)) ? NULL : floatval($wallet);
						$orderItem->commission 		= (empty($commission)) ? NULL : floatval($commission);
						$orderItem->admin_charge 	= (empty($admin_charge)) ? NULL : floatval($admin_charge);

						$orderDetails->order_items()->save($orderItem);
						
					}
					
					\Darryldecode\Cart\Facades\CartFacade::clear();
					\Darryldecode\Cart\Facades\CartFacade::clearCartConditions();
					$redirect_url = route('user.order.invoice', ['order_key'=> $orderDetails->order_key]);
					$output = ['type'=>'success', 'message'=> 'Thank you! We have recieved your order.', 'url'=> $redirect_url];
					
				} elseif($request->payment_mode == '2') {

					$order_item = [];
					foreach ($items as $key => $item) {

						$wallet = NULL;  $commission = NULL; $vendor_type = NULL; $admin_charge = NULL;
						if (!blank($item->attributes->vendor_id)) {
			
							$vendor  = \App\Models\User::select('vendor_type')->withTrashed()->find($item->attributes->vendor_id);
			
							if (!blank($vendor)) {
								$vendor_type = $vendor->vendor_type;
								if ($vendor->vendor_type == '1') {
				
									$siteSetting = \App\Models\SiteSetting::select('key_value')->where('key_name', 'commission')->first();
				
									$commission = (blank($siteSetting)) ? 0 : floatval($siteSetting->key_value);
									$admin_charge = ($commission * floatval($item->getPriceSum()) ) / 100;
									$wallet = floatval($item->getPriceSum()) - floatval($admin_charge);
				
								} else {
									$commission = NULL;
									$admin_charge = NULL;
									$wallet = $item->price;
								}
							}
						}

						$order_item[$key] = [
							'product_id'		=>$item->attributes->product_id,
							'qty'				=>intval($item->quantity),
							'sub_total'			=>floatval($item->price),
							'grand_total'		=>floatval($item->getPriceSum()),
							'product_name'		=>$item->name,
							'product_sku'		=>$item->attributes->sku,
							'product_type'		=>$item->attributes->product_type,
							'discounted_price'	=>$item->attributes->discounted_price,
							'mrp'				=>$item->attributes->mrp,
							'product_image'		=>$item->attributes->product_image,
							'agent_id'			=>$item->attributes->vendor_id,
							'vendor_type'		=>$vendor_type,
							'wallet'			=>(empty($wallet)) ? NULL : floatval($wallet),
							'commission'		=>(empty($commission)) ? NULL : floatval($commission),
							'admin_charge'		=>(empty($admin_charge)) ? NULL : floatval($admin_charge),
						];
					}

					
					$prePaymentlog = new \App\Models\PrePaymentlog();
					$prePaymentlog->order_no = time().''.Auth::id().''.intval($total);
					$prePaymentlog->order_key = md5($prePaymentlog->order_no);
					$prePaymentlog->user_id = Auth::id();
					$prePaymentlog->sub_total = floatval($subTotal);
					$prePaymentlog->delivery_charge = 0;
					$prePaymentlog->discount = floatval($discount);
					$prePaymentlog->grand_total = floatval($total);
					$prePaymentlog->order_date = date('Y-m-d');
					$prePaymentlog->shipping_address = $shipping_address;
					$prePaymentlog->billing_address = $billing_address;
					$prePaymentlog->coupon_id = (empty($coupon_id)) ? NULL : $coupon_id;
					$prePaymentlog->coupon_code = (empty($coupon_code)) ? NULL : $coupon_code;
					$prePaymentlog->coupon_desc = (empty($coupon_desc)) ? NULL : $coupon_desc;
					$prePaymentlog->order_note = NULL;
					$prePaymentlog->customer_name = $user->fname.' '.$user->lname;
					$prePaymentlog->customer_phone = $user->contact;
					$prePaymentlog->email = $user->email;
					$prePaymentlog->customer_address = $user->customer_profile->address;
					$prePaymentlog->customer_city = NULL;
					$prePaymentlog->customer_state = NULL;
					$prePaymentlog->customer_postcode = $user->customer_profile->pin_code;
					$prePaymentlog->customer_country = NULL;
					$prePaymentlog->order_item = json_encode($order_item);
					
					$prePaymentlog->save();

					\Darryldecode\Cart\Facades\CartFacade::clear();
					\Darryldecode\Cart\Facades\CartFacade::clearCartConditions();

					$info = array(
						'currency' 			=> "BDT", 
						'amount' 			=> $prePaymentlog->grand_total, 
						'order_id' 			=>$prePaymentlog->order_no, 
						'discsount_amount' 	=> 0, 
						'disc_percent' 		=> 0, 
						'client_ip' 		=> "", 
						'customer_name' 	=> $prePaymentlog->customer_name, 
						'customer_phone' 	=> $prePaymentlog->customer_phone, 
						'email' 			=> $prePaymentlog->email, 
						'customer_address' 	=> "address", 
						'customer_city' 	=> "city", 
						'customer_state' 	=> "", 
						'customer_postcode' => "", 
						'customer_country' 	=> "", 
						'value1' 			=> "ordered", 
					);

					$shurjopay_service = new ShurjopayController();
					$shurjopayResponse = $shurjopay_service->checkout($info);

					$redirect_url = $shurjopayResponse->getTargetUrl();
					$output = ['type'=>'success', 'message'=> 'Successfully create your payment request', 'url'=> $redirect_url];



				}
			}
			return response()->json($output);
		} 
	}
	public function shurjoPaymentResponse(Request $request)
	{
		/*
		$shurjopay_service = new ShurjopayController();
		$shurjoPaymentResponse = json_decode($shurjopay_service->verify($request->query('order_id')));
		$response = $shurjoPaymentResponse[0];
		dd($response); */

		if (empty($request->query('order_id'))) {
			echo "Invalid Order ID";
		} else {

			$order_id = $request->query('order_id');
			if ($request->ajax()) {
				

				$shurjopay_service = new ShurjopayController();
				$shurjoPaymentResponse = json_decode($shurjopay_service->verify($order_id));
				$response = $shurjoPaymentResponse[0];

				if (empty($response)) {
					$output = ['type'=>'refresh', 'message'=> 'Refresh Page'];
				}else{

					if ($response->sp_massage == 'Success') {

						if ($response->value1 === 'upgrate_account') {
							
							$exp_dt = \Carbon\Carbon::now()->addMonth()->toDateString();
							$subscriptionLog = new \App\Models\SubscriptionLog();
							$subscriptionLog->agent_id = $response->value2; //VENDOR ID
							$subscriptionLog->membership_price = $response->value3;
							$subscriptionLog->date = \Carbon\Carbon::now()->toDateString();
							$subscriptionLog->expaire_date = $exp_dt;
							$subscriptionLog->shurjopayment_order_id = $response->order_id;
							$subscriptionLog->shurjopayment_desc = json_encode($response);
							$subscriptionLog->save();

							$redirect_url = route('agent.dashboard');
							$output = ['type'=>'success', 'message'=> 'Thank you!', 'url'=> $redirect_url];


						}elseif ($response->value1 === 'ordered') {
							$prePaymentlog = \App\Models\PrePaymentlog::where('order_no', $response->customer_order_id)->whereNull('payment_date')->first();

							if (!blank($prePaymentlog)) {

								$orderDetails = new \App\Models\OrderDetails();
								$orderDetails->order_no 		= \App\Helper\Clib::generateOrderNo();
								$orderDetails->order_key 		= md5($orderDetails->order_no);
								$orderDetails->user_id 			= $prePaymentlog->user_id;
								$orderDetails->order_date 		= $prePaymentlog->order_date;
								$orderDetails->sub_total 		= $prePaymentlog->sub_total;
								$orderDetails->discount 		= $prePaymentlog->discount;
								$orderDetails->delivery_charge 	= $prePaymentlog->delivery_charge;
								$orderDetails->grand_total 		= $prePaymentlog->grand_total;
								$orderDetails->payment_mode 	= '2';
								$orderDetails->payment_status 	= '2';

								$orderDetails->shipping_address	= $prePaymentlog->shipping_address;
								$orderDetails->billing_address 	= $prePaymentlog->billing_address;
								
								$orderDetails->coupon_id = (empty($prePaymentlog->coupon_id)) ? NULL : $prePaymentlog->coupon_id;
								$orderDetails->coupon_code = (empty($prePaymentlog->coupon_code)) ? NULL : $prePaymentlog->coupon_code;
								$orderDetails->coupon_desc = (empty($prePaymentlog->coupon_desc)) ? NULL : $prePaymentlog->coupon_desc;

								$orderDetails->shurjopayment_order_id = $response->order_id;
								$orderDetails->shurjopayment_desc = json_encode($response);

								$orderDetails->save();
								$order_item = (empty($prePaymentlog->order_item)) ? [] : json_decode($prePaymentlog->order_item);

								foreach ($order_item as $key => $item) {

									$orderItem = new \App\Models\OrderItem();
									$orderItem->order_no = $orderDetails->order_no;
									$orderItem->product_id = $item->product_id;
									$orderItem->qty = intval($item->qty);
									$orderItem->sub_total = floatval($item->sub_total);
									$orderItem->grand_total = floatval($item->grand_total);
									$orderItem->product_name = $item->product_name;
									$orderItem->product_sku = $item->product_sku;
									$orderItem->product_type = $item->product_type;
									$orderItem->product_desc = json_encode([
										'discounted_price'	=> $item->discounted_price,
										'mrp'				=> $item->mrp,
										'product_image'		=> $item->product_image
									]);
									$orderItem->agent_id 		= $item->agent_id;
									$orderItem->vendor_type 	= $item->vendor_type;
									$orderItem->wallet 			= (empty($item->wallet)) ? NULL : floatval($item->wallet);
									$orderItem->commission 		= (empty($item->commission)) ? NULL : floatval($item->commission);
									$orderItem->admin_charge 	= (empty($item->admin_charge)) ? NULL : floatval($item->admin_charge);

									$orderDetails->order_items()->save($orderItem);
								}

								$prePaymentlog->payment_date = \Carbon\Carbon::now()->toDateTimeString();
								$prePaymentlog->save();
								
								$redirect_url = route('user.order.invoice', ['order_key'=> $orderDetails->order_key]);
								$output = ['type'=>'success', 'message'=> 'Thank you! We have recieved your order.', 'url'=> $redirect_url];

							}else{
								$output = ['type'=>'error', 'message'=> 'Payment Invoice not found'];
							}
						}
					}else{
						$redirect_url = route('home');
						$output = ['type'=>'success', 'message'=> $response->sp_massage, 'url'=> $redirect_url];
					}
				}
				return response()->json($output);
			}
			$data['ajaxURL'] = route('payment.response', ['order_id'=> $order_id]);
			return view('user.paymentprocess', $data);
		}
		
	}
	public function shurjoPaymentCancel(Request $request)
	{
		dd($request);
	}
}
