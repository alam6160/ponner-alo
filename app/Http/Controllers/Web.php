<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Cart;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;
use App\Models\CustomerWishlist;
use App\Models\ProductReview;
use App\Models\User;
use App\Http\Controllers\Review;

class Web extends Controller
{
    public function __construct()
    {
        $this->slider_model = new Slider;
        $this->product_model = new Product;
        $this->category_model = new Category;
        $this->wishlist_model = new CustomerWishlist;
        $this->productReview_model = new ProductReview;
        $this->user_model = new User;
        $this->review_controller = new Review;
    }

    public function common()
    {
        if (!Cart::isEmpty()) {
            $data['cart_items'] = Cart::getContent()->toArray();
            $data['cart_total_items'] = Cart::getContent()->count();
            $data['cart_total_value'] = Cart::getTotal();
        }

        if (Auth::check()) {
            $data['total_wishlist_items'] = $this->wishlist_model->where('user_id', Auth::id())->count();
            $data['cart_conditions'] = Cart::getConditions()->toArray();
        }

        $data['parent_cats'] = $this->category_model->whereNull('parent_id')->whereNotIn('slug', ['brands'])->get();
        $data['category_model'] = $this->category_model;
        $data['wishlist_model'] = $this->wishlist_model;

        return $data;
    }

    public function home()
    {
        $data = $this->common();
        $data['title'] = 'Home';
        $past_date = date('Y-m-d H:i:s', strtotime('-15 day'));

        $data['sliders_list'] = $this->slider_model->orderBy('id', 'DESC')->limit(10)->get();
        $data['featured_products'] = $this->product_model->where('saletype', '1')->where('admin_verify', '1')->orderBy('id', 'DESC')->limit(10)->get();
        $data['best_selling_products'] = $this->product_model->where('saletype', '2')->where('admin_verify', '1')->orderBy('id', 'DESC')->limit(15)->get();
        $data['daily_deals_products'] = $this->product_model->where('saletype', '3')->where('admin_verify', '1')->orderBy('id', 'DESC')->limit(15)->get();
        $data['weekly_deals_products'] = $this->product_model->where('saletype', '4')->where('admin_verify', '1')->orderBy('id', 'DESC')->limit(15)->get();
        $data['monthly_deals_products'] = $this->product_model->where('saletype', '5')->where('admin_verify', '1')->orderBy('id', 'DESC')->limit(15)->get();
        $data['new_arrivals'] = $this->product_model->where('created_at', '>', $past_date)->where('admin_verify', '1')->orderBy('id', 'DESC')->limit(15)->get();

        $data['brands_list'] = $this->category_model->where('parent_id', function ($query) {
            $query->select('id')->from('categories')->where('slug', 'brands')->first();
        })->orderBy('id', 'DESC')->get();
        $data['product_model'] = $this->product_model;
        $data['review_controller'] = $this->review_controller;

        return view('web/home', $data);
    }

    public function products_list($slug)
    {
        $data = $this->common();
        $past_date = date('Y-m-d H:i:s', strtotime('-15 day'));

        if ($slug == 'best-deals') {
            $data['title'] = 'Our Best Deal Products';
            $data['products_list'] = $this->product_model->where('saletype', '3')->orWhere('saletype', '4')->orWhere('saletype', '5')->where('admin_verify', '1')->orderBy('id', 'DESC')->paginate(40);
        } elseif ($slug == 'new-arrivals') {
            $data['title'] = 'Our New Arrival Products';
            $data['products_list'] = $this->product_model->where('created_at', '>', $past_date)->where('admin_verify', '1')->orderBy('id', 'DESC')->paginate(40);
        } elseif ($slug == 'featured-items') {
            $data['title'] = 'Our Featured Products';
            $data['products_list'] = $this->product_model->where('saletype', '1')->where('admin_verify', '1')->orderBy('id', 'DESC')->paginate(40);
        } elseif ($slug == 'deals-of-the-day') {
            $data['title'] = 'Our Deals Of The Day';
            $data['products_list'] = $this->product_model->where('saletype', '3')->where('admin_verify', '1')->orderBy('id', 'DESC')->paginate(40);
        } else {
            $category_data = $this->category_model->where('slug', $slug)->first();

            if (!empty($category_data)) {
                $data['title'] = $category_data->cat_title . ' Products';
                $data['products_list'] = $this->product_model->where('admin_verify', '1')->whereRaw("FIND_IN_SET($category_data->id, categories)")->orderBy('id', 'DESC')->paginate(40);
            } else {
                return redirect('/');
            }
        }
        $data['review_controller'] = $this->review_controller;
        return view('web/products_list', $data);
    }

    public function product_details($slug)
    {
        $data = $this->common();

        $data['product_data'] = $this->product_model->where('slug', $slug)->where('admin_verify', '1')->first();


        if (!empty($data['product_data'])) {

            $data['avg'] = $this->review_controller->avg_star($data['product_data']->id);

            $data['title'] = $data['product_data']->name;
            $product_cats = explode(',', $data['product_data']->categories);
            $last_cat_index = array_key_last($product_cats);
            $data['similar_products'] = $this->product_model
                ->whereNot('id', $data['product_data']->id)
                ->whereRaw("FIND_IN_SET($product_cats[$last_cat_index], categories)")
                ->orderBy('id', 'DESC')
                ->limit(40)->get();

            $vendor = $data['product_data']->vendor;
            $vendor_details_show = FALSE;
            if ($vendor->vendor_type == '2') {
                $subscriptionLog = \App\Models\SubscriptionLog::where([
                    ['expaire_date', '>=', date('Y-m-d')],
                    ['agent_id', '=', $vendor->id]
                ])->orderBy('id', 'desc')->first();

                $vendor_details_show = (blank($subscriptionLog)) ? FALSE : TRUE;
            }
            $data['vendor'] = $vendor;
            $data['vendor_details_show'] = $vendor_details_show;

            // review start
            $review_list = $this->productReview_model->where('product_id', $data['product_data']->id)->where('customer_id', '!=', Auth::id())->orderBy('id', 'DESC')->paginate(15);

            $data['user_model'] = $this->user_model;
            $data['review_list'] = $review_list;

            $review_done = $this->productReview_model->where(['product_id' => $data['product_data']->id, 'customer_id' => Auth::id()])->first();

            if (!empty($review_done)) {

                $customer_detail = $this->user_model->where('id', $review_done['customer_id'])->first();
                $review_done_data = [
                    'id' => $review_done['id'],
                    'product_id' => $review_done['product_id'],
                    'rating' => $review_done['rating'],
                    'review' => $review_done['review'],
                    'created_at' => date("d-m-Y", strtotime($review_done['created_at'])),
                    'customer' => $customer_detail['fname'] . " " . $customer_detail['lname'],
                ];

                $data['review_done'] = $review_done_data;
            } else {
                $data['review_done'] = null;
            }
            // Review end

            $data['review_controller'] = $this->review_controller;
            return view('web/product_details', $data);
        } else {
            return redirect('/');
        }
    }

    public function aboutUs(Request $request)
    {
        $page = \App\Models\Page::find(2);
        $data = $this->common();
        $data['title'] = (blank($page)) ? 'About Us' : $page->page_title;
        $data['page'] = $page;
        return view('web.defaultpage', $data);
    }
    public function contactUs(Request $request)
    {

        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "name"      => "required|string",
                "email"     => "required|email",
                "subject"   => "required|string",
                "message"   => "required|string",
            ]);

            if ($validator->fails()) {
                $output = ['type' => 'error', 'message' => $validator->errors()->all()];
            } else {
                $email_id = \App\Helper\Helper::cemail();
                \Illuminate\Support\Facades\Mail::to($email_id)->send(new \App\Mail\ContactUs($request));
                $output = ['type' => 'success', 'message' => 'Successfully Send Message'];
            }
            return response()->json($output);
        }

        $data = $this->common();
        $data['title'] = 'Contact Us';
        return view('web.contactus', $data);
    }
    public function termsConditions(Request $request)
    {
        $page = \App\Models\Page::find(1);
        $data = $this->common();
        $data['title'] = (blank($page)) ? 'About Us' : $page->page_title;
        $data['page'] = $page;
        return view('web.defaultpage', $data);
    }
    public function privacyPolicy(Request $request)
    {
        $page = \App\Models\Page::find(3);
        $data = $this->common();
        $data['title'] = (blank($page)) ? 'About Us' : $page->page_title;
        $data['page'] = $page;
        return view('web.defaultpage', $data);
    }
    public function vendorSignup(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "title"                 => "required|string",
                "fname"                 => "required|string",
                "lname"                 => "required|string",
                "email"                 => "required|email|unique:users,email",
                "contact"               => "required|min:8",
                "password"              => "required|min:6",
                "vendor_type"           => "required|in:1,2",
                "organization_name"     => "nullable|string",
            ]);

            if ($validator->fails()) {
                $output = ['type' => 'error', 'message' => $validator->errors()->all()];
            } else {

                $user = new \App\Models\User();
                $user->code         = \App\Helper\Clib::unique_code(new \App\Models\User(), 'V');
                $user->title        = strip_tags(trim($request->title));
                $user->fname        = strip_tags(trim($request->fname));
                $user->lname        = strip_tags(trim($request->lname));
                $user->email        = strip_tags(trim($request->email));
                $user->contact      = strip_tags(trim($request->contact));
                $user->password     = \Illuminate\Support\Facades\Hash::make(trim($request->password));
                $user->user_type    = '4';
                $user->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
                $user->vendor_type = (empty($request->vendor_type)) ? NULL : strip_tags(trim($request->vendor_type));
                $user->wallet = 0;
                $user->status = '2';

                if ($user->save()) {

                    /*
                    $brand_logo_file = NULL;
                    if (isset($_FILES['brand_logo_file'])) {
                        if ($_FILES['brand_logo_file']['error'] == 0) {
                            $brand_logo_file = $request->file('brand_logo_file')->store('uploads');
                        }
                    }
                    
                    $gst_file = NULL;
                    if (isset($_FILES['gst_file'])) {
                        if ($_FILES['gst_file']['error'] == 0) {
                            $gst_file = $request->file('gst_file')->store('uploads');
                        }
                    }

                    $drug_licence_file = NULL;
                    if (isset($_FILES['drug_licence_file'])) {
                        if ($_FILES['drug_licence_file']['error'] == 0) {
                            $drug_licence_file = $request->file('drug_licence_file')->store('uploads');
                        }
                    }
                    
                    $aadhaar_file = NULL;
                    if (isset($_FILES['aadhaar_file'])) {
                        if ($_FILES['aadhaar_file']['error'] == 0) {
                            $aadhaar_file = $request->file('aadhaar_file')->store('uploads');
                        }
                    }
                    */

                    $agentProfile = new \App\Models\AgentProfile();
                    $agentProfile->organization_name = (empty($request->organization_name)) ? NULL : strip_tags(trim($request->organization_name));

                    /*
                    $agentProfile->licence = (empty($request->licence)) ? NULL : strip_tags(trim($request->licence));
                    $agentProfile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));

                    $agentProfile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $agentProfile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));

                    $agentProfile->servicable_pincodes = (empty($request->servicable_pincodes)) ? NULL : implode(',', $request->servicable_pincodes);

                    $agentProfile->brand_logo_file  = (empty($brand_logo_file)) ? NULL : strip_tags(trim($brand_logo_file));
                    $agentProfile->gst_file         = (empty($gst_file)) ? NULL : strip_tags(trim($gst_file));
                    $agentProfile->drug_licence_file  = (empty($drug_licence_file)) ? NULL : strip_tags(trim($drug_licence_file));
                    $agentProfile->aadhaar_file     = (empty($aadhaar_file)) ? NULL : strip_tags(trim($aadhaar_file)); */

                    $user->agent_prfile()->save($agentProfile);

                    $agentBank = new \App\Models\AgentBank();
                    $user->agent_bank()->save($agentBank);

                    $output = ['type' => 'success', 'message' => 'Successfully create account'];
                } else {
                    $output = ['type' => 'error', 'message' => 'try again..'];
                }
            }
            return response()->json($output);
        }
        $data['title'] = 'Vendor Signup';
        return view('web.vendor-signup', $data);
    }
    public function searchIndex(Request $request, $key)
    {

        if (empty($key)) {
            $key = '';
            $products_list = \App\Models\Product::paginate(40);
        } else {
            $key = trim(str_replace('-', ' ', $key));
            $products_list = \App\Models\Product::where('name', 'like', "%$key%")->where('admin_verify', '1')->paginate(40);
        }
        $data = $this->common();
        $data['title'] = 'Search Products';
        $data['products_list'] = $products_list;
        $data['searchkey'] = $key;
        $data['review_controller'] = $this->review_controller;
        return view('web.products_list', $data);
    }
    public function submitSearch(Request $request)
    {
        if (isset($_POST['submit'])) {
            $key = $request->search;
            if (empty($key)) {
                return redirect()->route('home');
            } else {
                $key = trim(str_replace(' ', '-', $key));
                return redirect()->route('search.index', ['key' => $key]);
            }
        }
    }
    public function resetPassword(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "email" => "required|email",
            ]);

            if ($validator->fails()) {
                $output = ['type' => 'error', 'message' => $validator->errors()->all()];
            } else {
                $email = trim($request->email);
                $selectField = ['id', 'password', 'fname', 'lname', 'email'];
                $user = \App\Models\User::select($selectField)->where('email', $email)->first();
                if (blank($user)) {
                    $output = ['type' => 'error', 'message' => 'Invalid email id'];
                } else {

                    $newpass = substr(str_shuffle('123456789AQWTYUKLBVDMNPASXZ'), 0, 8);
                    $user->password = \Illuminate\Support\Facades\Hash::make(trim($newpass));
                    $user->save();

                    \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\ResetPassword($user, $newpass));
                    $output = ['type' => 'success', 'message' => 'Successfully send password on email'];
                }
            }
            return response()->json($output);
        }
        $data['title'] = 'Rest Password';
        return view('web.resetpassword', $data);
    }
}
