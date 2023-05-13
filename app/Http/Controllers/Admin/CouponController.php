<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            
            $data = \App\Models\Coupon::withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    if ($data->trashed()) {

                        $btn = '<a href="javascript:void(0)" class="btn-ancher link-disable"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-delete" data-row="'.$data->id.'" data-status="1" title="Restore"><i class="fa fa-trash-o fa-lg"></i></a>';

                    } else {
                        $btn = '<a href="'.route('admin.product.coupon.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->editColumn('discount_type', function($data){
                    return \App\Helper\Helper::getDiscountType($data->discount_type);
                })
                ->editColumn('expire_date', function($data){
                    return (empty($data->expire_date)) ? '' : date('d-m-Y', strtotime($data->expire_date));
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "code"          =>"required|string|unique:coupons,code",
                "discount_type" =>"required|in:1,2",
                "discount"      =>"required|numeric",
                "expire_date"   =>"nullable|date",
                "product_ids.*" =>"nullable|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $coupon = new \App\Models\Coupon();
                $coupon->code = strip_tags(trim($request->code));
                $coupon->discount_type = strip_tags(trim($request->discount_type));
                $coupon->discount = floatval($request->discount);
                $coupon->expire_date = (empty($request->expire_date)) ? NULL : date('Y-m-d', strtotime($request->expire_date));

                $coupon->product_ids = (empty($request->product_ids)) ? NULL : implode(',', $request->product_ids);
               
                if ($coupon->save()) {
                    $output = ['type'=>'success', 'message'=>'Successfully create coupon'];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Coupon';
        $data['allproducts'] = \App\Models\Product::all();
        return view('admin.product.coupon.coupon', $data);
    }
    public function edit(Request $request, $id)
    {
        $coupon = \App\Models\Coupon::find($id);
        if ($request->ajax()) {

            if (blank($coupon)) {
                return ['type'=>'redirect', 'url'=> route('admin.product.coupon.create'),'message'=>'This coupon is already deleted' ];
            }
            $validator = validator($request->all(), [
                "code"          =>"required|string|unique:coupons,code,{$id}",
                "discount_type" =>"required|in:1,2",
                "discount"      =>"required|numeric",
                "expire_date"   =>"nullable|date",
                "product_ids.*" =>"nullable|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $coupon->code = strip_tags(trim($request->code));
                $coupon->discount_type = strip_tags(trim($request->discount_type));
                $coupon->discount = floatval($request->discount);
                $coupon->expire_date = (empty($request->expire_date)) ? NULL : date('Y-m-d', strtotime($request->expire_date));

                $coupon->product_ids = (empty($request->product_ids)) ? NULL : implode(',', $request->product_ids);
               
                if ($coupon->save()) {
                    $output = ['type'=>'success', 'message'=>'Successfully updated coupon'];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        if (blank($coupon)) {
            return redirect()->route('admin.product.coupon.create');
        }
        $data['title'] = 'Edit Coupon';
        $data['allproducts'] = \App\Models\Product::all();
        $data['coupon'] = $coupon;
        return view('admin.product.coupon.coupon', $data);
    }
}
