<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AjaxDeleteController extends Controller
{
    private function change_status($model, $module)
    {
        $request = Request();
        if ($request->isMethod('post')) {
            
            $validator = Validator::make($request->all(), [
                'id'    => 'required|integer',
                'status'=> "required|in:1,2",
            ]);
            if ($validator->fails() ) {
                $output = array('type' => 'error', 'message'=> $validator->errors()->all() );
            }else{
                if ($request->status == '2') {
                    if ($model::withTrashed()->find($request->id)->delete() ) {
                        $output = array('type' => 'success', 'message'=> 'Successfully deleted '.$module);
                    } else {
                        $output = array('type' => 'error', 'message'=> 'Whoops! try again...');
                    }
                    
                } elseif($request->status == '1') {
                    if ($model::withTrashed()->find($request->id)->restore()) {
                        $output = array('type' => 'success', 'message'=> 'Successfully restore '.$module);
                    } else {
                        $output = array('type' => 'error', 'message'=> 'Whoops! try again...');
                    }
                }
                
            }
            return response()->json($output);
        }
    }
    public function user_status()
    {
        $model = new \App\Models\User;
        return $this->change_status($model, 'user');
    }
    public function category_status()
    {
        $model = new \App\Models\Category;
        return $this->change_status($model, 'category');
    }
    public function product_status()
    {
        $model = new \App\Models\Product;
        return $this->change_status($model, 'product');
    }
    public function blog_status()
    {
        $model = new \App\Models\Blog;
        return $this->change_status($model, 'blog');
    }
    public function user_active(Request $request)
    {
        if ($request->isMethod('post')) {
            
            $validator = Validator::make($request->all(), [
                'id'    => 'required|integer',
                'status'=> "required|in:1,2",
            ]);
            if ($validator->fails() ) {
                $output = array('type' => 'error', 'message'=> $validator->errors()->all() );
            }else{
                $user = \App\Models\User::find($request->id);
                if (blank($user)) {
                    $output = array('type' => 'error', 'message'=> 'User Data not found');
                } else {
                    $user->status = trim($request->status);
                    if ($user->save()) {
                        $output = array('type' => 'success', 'message'=> 'Successfully Change status');
                    } else {
                        $output = array('type' => 'error', 'message'=> 'Whoops! try again...');
                    }
                    
                }
            }
            return response()->json($output);
        }
    }
    public function productAddon_status()
    {
        $model = new \App\Models\ProductAddon;
        return $this->change_status($model, 'product addon');
    }
    public function productFilter_status()
    {
        $model = new \App\Models\ProductFilter;
        return $this->change_status($model, 'product filter');
    }
    public function coupon_status()
    {
        $model = new \App\Models\Coupon;
        return $this->change_status($model, 'coupon');
    }
    public function blogcategory_status()
    {
        $model = new \App\Models\BlogCategory;
        return $this->change_status($model, 'blog category');
    }
    public function slider_status()
    {
        $model = new \App\Models\Slider();
        return $this->change_status($model, 'slider');
    }
}
