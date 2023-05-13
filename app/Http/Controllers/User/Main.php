<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;
use Hash;
use Session;
use Carbon\Carbon;
use App\Helper\Clib;
use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\CustomerBillingaddress;
use App\Models\CustomerShippingaddress;

class Main extends Controller
{
	public function __construct()
	{
		$this->clib_helper = new Clib;
		$this->user_model = new User;
		$this->customer_profile_model = new CustomerProfile;
		$this->customer_billing_address_model = new CustomerBillingaddress;
		$this->customer_shipping_address_model = new CustomerShippingaddress;
	}

	public function signin(Request $form)
    {
    	$data['title'] = 'Account Login';

    	if(Request()->isMethod('post'))
    	{
    		$form->validate([
				'email' => 'required|email|exists:users',
				'password' => 'required|min:6'
			],[],[
				'email' => 'E-Mail ID',
				'password' => 'Password'
			]);

			$form_data = Request()->except(['_token']);

			$email = trim($form_data['email']);
			$user_data = $this->user_model->where(['email' => $email, 'status' => '2', 'user_type' => '9'])->first();

			if((!empty($user_data)) AND (Auth::attempt($form_data)))
            {
            	return redirect()->intended('/');
            }else{
            	$form->flash();
				return redirect('sign-in')->with('message','Oops! Invalid E-Mail ID Or Password.')->withInput();
            }
    	}

    	return view('user/signin', $data);
    }

    public function signup(Request $form)
    {
    	$data['title'] = 'Account Register';

    	if(Request()->isMethod('post'))
    	{
    		$form->validate([
    			'title' => 'required',
    			'fname' => 'required',
    			'lname' => 'required',
				'email' => 'required|email|unique:users,email',
				'contact' => 'required',
				'password' => 'required|min:6'
			],[],[
				'title' => 'Title',
    			'fname' => 'First Name',
    			'lname' => 'Last Name',
				'email' => 'E-Mail ID',
				'contact' => 'Phone No.',
				'password' => 'Password'
			]);

			$form_data = Request()->except(['_token']);

			$form_data['code'] = $this->clib_helper->unique_code($this->user_model, 'C');
			$form_data['password'] = Hash::make(trim($form_data['password']));
			$form_data['status'] = '2';
			$form_data['user_type'] = '9';
			$form_data['email_verified_at'] = Carbon::now()->toDateTimeString();
			$form_data['approve_at'] = Carbon::now()->toDateTimeString();

			$user = $this->user_model->create($form_data);

			if(!empty($user->id))
            {
            	$this->customer_profile_model->create(['customer_id' => $user->id]);
            	$this->customer_billing_address_model->create(['customer_id' => $user->id]);
            	$this->customer_shipping_address_model->create(['customer_id' => $user->id]);

            	return redirect('sign-in')->with('message','Hi! Your Account Registration Successful. Login Now!');
            }else{
            	$form->flash();
				return redirect('sign-up')->with('message','Oops! Something Went Wrong. Please Try Again!')->withInput();
            }
    	}

    	return view('user/signup', $data);
    }

    public function signout()
	{
		Auth::logout();
		return redirect('/')->with('message','You Have Successfully Logged-Out.');
	}
}

?>