<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;
use Session;
use Validator;
use App\Http\Controllers\Web;
use App\Models\User;
use App\Models\Product;
use App\Models\CustomerWishlist;

class Wishlist extends Controller
{
	public function __construct()
	{
		$this->web_controller = new Web;
		$this->user_model = new User;
		$this->product_model = new Product;
		$this->wishlist_model = new CustomerWishlist;
	}

	public function wishlist_items()
    {
    	$data = $this->web_controller->common();

    	$data['title'] = 'My Wishlist';
    	$data['wishlist_items'] = $this->wishlist_model->where('user_id', Auth::id())->orderBy('id','DESC')->paginate(50);
    	$data['product_model'] = $this->product_model;

		return view('user/wishlist', $data);
	}
}

?>