<?php

namespace App\Http\Controllers\User;

use Hash;
use Session;

use App\Models\User;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Web;
use App\Models\CustomerProfile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerBillingaddress;
use App\Models\CustomerShippingaddress;
use Illuminate\Support\Facades\Validator;

class Account extends Controller
{
	public function __construct()
	{
		$this->web_controller = new Web;
		$this->user_model = new User;
		$this->state_model = new State;
		$this->customer_profile_model = new CustomerProfile;
		$this->customer_billing_address_model = new CustomerBillingaddress;
		$this->customer_shipping_address_model = new CustomerShippingaddress;
	}

	public function fetch_profile()
    {
    	$data = $this->web_controller->common();

    	$data['user_data'] = DB::table('users')
    					->join('customer_profiles', 'customer_profiles.customer_id','=','users.id')
    					->join('customer_billingaddresses', 'customer_billingaddresses.customer_id','=','users.id')
    					->join('customer_shippingaddresses', 'customer_shippingaddresses.customer_id','=','users.id')
    					->select('users.*','customer_profiles.address AS r_address','customer_profiles.pin_code AS r_pincode','customer_profiles.state_id AS r_stateid','customer_billingaddresses.title AS b_title','customer_billingaddresses.fname AS b_fname','customer_billingaddresses.lname AS b_lname','customer_billingaddresses.email AS b_email','customer_billingaddresses.contact AS b_contact','customer_billingaddresses.address AS b_address','customer_billingaddresses.pin_code AS b_pincode','customer_billingaddresses.state_id AS b_stateid','customer_shippingaddresses.title AS s_title','customer_shippingaddresses.fname AS s_fname','customer_shippingaddresses.lname AS s_lname','customer_shippingaddresses.email AS s_email','customer_shippingaddresses.contact AS s_contact','customer_shippingaddresses.address AS s_address','customer_shippingaddresses.pin_code AS s_pincode','customer_shippingaddresses.state_id AS s_stateid')
    					->where('users.id', Auth::id())
    					->first();

    	$data['title'] = 'My Account';
    	$data['states_list'] = $this->state_model->get();

		return view('user/profile', $data);
	}

	public function update_name(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'title' => 'required',
			'fname' => 'required',
			'lname' => 'required'
		],[],[
			'title' => 'Title',
			'fname' => 'First Name',
			'lname' => 'Last Name'
		]);

		if(!$validator->fails())
		{
			$form_data = Request()->post();

			if($this->user_model->where('id', Auth::id())->update($form_data))
			{
				$response = [
					'status'	=> 1,
					'message'	=> 'Your Profile Information Has Updated Successfully.'
				];
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Something Went Wrong. Please Try Again!'
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

    public function update_email(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'email' => 'required|email|unique:users,email'
		],[],[
			'email' => 'E-Mail ID'
		]);

		if(!$validator->fails())
		{
			$form_data = Request()->post();

			if($this->user_model->where('id', Auth::id())->update($form_data))
			{
				$response = [
					'status'	=> 1,
					'message'	=> 'Your Profile Information Has Updated Successfully.'
				];
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Something Went Wrong. Please Try Again!'
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

    public function update_phone(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'contact' => 'required'
		],[],[
			'contact' => 'Phone No.'
		]);

		if(!$validator->fails())
		{
			$form_data = Request()->post();

			if($this->user_model->where('id', Auth::id())->update($form_data))
			{
				$response = [
					'status'	=> 1,
					'message'	=> 'Your Profile Information Has Updated Successfully.'
				];
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Something Went Wrong. Please Try Again!'
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

    public function update_raddress(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'address' => 'required',
			'pin_code' => 'required|integer',
			'state_id' => 'required|integer'
		],[],[
			'address' => 'Address',
			'pin_code' => 'PIN Code',
			'state_id' => 'State'
		]);

		if(!$validator->fails())
		{
			$form_data = Request()->post();

			if($this->customer_profile_model->where('customer_id', Auth::id())->update($form_data))
			{
				$response = [
					'status'	=> 1,
					'message'	=> 'Your Profile Information Has Updated Successfully.'
				];
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Something Went Wrong. Please Try Again!'
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

    public function update_baddress(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'title' => 'required',
			'fname' => 'required',
			'lname' => 'required',
			'email' => 'required|email',
			'contact' => 'required',
			'address' => 'required',
			'pin_code' => 'required|integer',
			'state_id' => 'required|integer'
		],[],[
			'title' => 'Title',
			'fname' => 'First Name',
			'lname' => 'Last Name',
			'email' => 'E-Mail ID',
			'contact' => 'Contact No.',
			'address' => 'Address',
			'pin_code' => 'PIN Code',
			'state_id' => 'State'
		]);

		if(!$validator->fails())
		{
			$form_data = Request()->post();

			if($this->customer_billing_address_model->where('customer_id', Auth::id())->update($form_data))
			{
				$response = [
					'status'	=> 1,
					'message'	=> 'Your Profile Information Has Updated Successfully.'
				];
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Something Went Wrong. Please Try Again!'
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

    public function update_saddress(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'title' => 'required',
			'fname' => 'required',
			'lname' => 'required',
			'email' => 'required|email',
			'contact' => 'required',
			'address' => 'required',
			'pin_code' => 'required|integer',
			'state_id' => 'required|integer'
		],[],[
			'title' => 'Title',
			'fname' => 'First Name',
			'lname' => 'Last Name',
			'email' => 'E-Mail ID',
			'contact' => 'Contact No.',
			'address' => 'Address',
			'pin_code' => 'PIN Code',
			'state_id' => 'State'
		]);

		if(!$validator->fails())
		{
			$form_data = Request()->post();

			if($this->customer_shipping_address_model->where('customer_id', Auth::id())->update($form_data))
			{
				$response = [
					'status'	=> 1,
					'message'	=> 'Your Profile Information Has Updated Successfully.'
				];
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Something Went Wrong. Please Try Again!'
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

    public function change_password(Request $form)
    {
    	$validator = Validator::make($form->all(), [
			'cpass' => 'required|min:6',
			'npass' => 'required|min:6',
			'rpass' => 'required|min:6'
		],[],[
			'cpass' => 'Current Password',
			'npass' => 'New Password',
			'rpass' => 'Repeat Password'
		]);

		if(!$validator->fails())
		{
			$form_data = Request()->post();
			$user_data = $this->user_model->where('id', Auth::id())->first();

			$cpass = trim($form_data['cpass']);
			$npass = trim($form_data['npass']);
			$rpass = trim($form_data['rpass']);
			$new_password = Hash::make($npass);

			if($npass == $rpass)
			{
				if(Hash::check($cpass, $user_data->password))
				{
					if($this->user_model->where('id', Auth::id())->update(['password' => $new_password]))
					{
						$response = [
							'status'	=> 1,
							'message'	=> 'Your Account Password Has Changed Successfully.'
						];
					}else{
						$response = [
							'status'	=> 0,
							'message'	=> 'Something Went Wrong. Please Try Again!'
						];
					}
				}else{
					$response = [
						'status'	=> 0,
						'message'	=> 'Invalid Current Password. Kindly Verify & Try Again!'
					];
				}
			}else{
				$response = [
					'status'	=> 0,
					'message'	=> 'Your New Password Does Not Match With The Repeat Password.'
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
}

?>