<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Web;
use App\Models\CustomerWishlist;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct() {
        $this->web_controller = new Web;
		$this->user_model = new User;
		$this->product_model = new Product;
		$this->wishlist_model = new CustomerWishlist;
    }
    public function index(Request $request)
    {
        $data = $this->web_controller->common();
        $data['title'] = 'My Orders';
        $data['orderDetails'] = \App\Models\OrderDetails::with('order_items')->where('user_id', Auth::id() )->latest()->paginate(10);
        $data['totalorders'] = \App\Models\OrderDetails::where('user_id', Auth::id() )->count();
        return view('user.orders', $data);
    }
    public function invoice(Request $request, $order_key)
    {
        $orderDetails = \App\Models\OrderDetails::where([
            ['user_id','=', Auth::id()],
            ['order_key','=', $order_key]
        ])->with('order_items')->first();
        if (blank($orderDetails)) {
            return redirect()->route('home');
        }
        $data = $this->web_controller->common();

        $data['title'] = 'Order Invoice';
        $data['orderDetails'] = $orderDetails;
        
        return view('user.order_invoice', $data);
    }
    public function returnOrder(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "order_id"      =>"required|integer",
                "return_remark" =>"required|string",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                
                $orderDetails = \App\Models\OrderDetails::where([
                    ['status', '=', '4'],
                    ['user_id','=', Auth::id() ],
                    ['return_last_date', '>=', date('Y-m-d')],
                ])->find($request->order_id);

                if (blank($orderDetails)) {
                    $output = ['type'=>'error', 'message'=> 'Invalid Order Id']; 
                } else {
                    $orderDetails->status = '6';
                    $orderDetails->return_status = '1';
                    $orderDetails->return_remark = (empty($request->return_remark)) ? NULL : strip_tags($request->return_remark);
                    $orderDetails->save();

                    $output = ['type'=>'success', 'message'=> 'Successfully submit return request']; 
                }
                
            }
            return response()->json($output);
        }
    }
}
