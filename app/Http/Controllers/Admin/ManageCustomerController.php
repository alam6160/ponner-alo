<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ManageCustomerController extends Controller
{
    public function index(Request $request)
    {
        $user_type = '9';
        if ($request->ajax()) {

            $select = ['users.*','customer_profiles.address','customer_profiles.pin_code'];

            
            $data = \App\Models\User::select($select)->join('customer_profiles','customer_profiles.customer_id', '=','users.id')->where('users.user_type', $user_type)->withTrashed()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    if ($data->trashed()) {
                        $btn = '<a href="javascript:void(0)" class="btn-ancher link-disable"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        if ($data->status == 1) {

                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher link-disable text-success" title="Active"><i class="fa fa-user-plus fa-lg"></i></a>';

                        } else {
                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher link-disable text-danger" title="Active"><i class="fa fa-user-times fa-lg"></i></a>';
                        }
                        

                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-delete" data-row="'.$data->id.'" data-status="1" title="Restore"><i class="fa fa-trash-o fa-lg"></i></a>';

                    } else {
                        $btn = '<a href="'.route('admin.manage-user.customer.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        if ($data->status == 1) {

                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="2" title="Active"><i class="fa fa-user-plus fa-lg"></i></a>';

                        } else {
                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger btn-status" data-row="'.$data->id.'" data-status="1" title="Inactive"><i class="fa fa-user-times fa-lg"></i></a>';
                        }

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->addColumn('customer_dets', function($data){
                    return $data->fname.' '.$data->lname;
                })
                ->addColumn('contact_det', function($data){
                    return $data->contact.'<br>'.$data->email;
                })
                ->addColumn('active_status', function($data){
                    return ($data->status == '1') ? 'Inactive' : 'Active' ;
                })

                ->rawColumns(['actions','contact_det'])
                ->make(true);
        }

        $data['allstates'] = \App\Models\State::all();
        return view('admin.manage-user.customer.index', $data);
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "title"                 =>"required|string",
                "fname"                 =>"required|string",
                "lname"                 =>"required|string",
                "email"                 =>"required|email|unique:users,email",
                "contact"               =>"required|regex:/^[0-9]{10}$/",
                "password"              =>"required|min:6",
                "address"               =>"nullable|string",
                "state_id"              =>"nullable|integer",
                "pin_code"              =>"nullable|integer",

                "shipping_title"                 =>"nullable|string",
                "shipping_fname"                 =>"nullable|string",
                "shipping_lname"                 =>"nullable|string",
                "shipping_email"                 =>"nullable|email",
                "shipping_contact"               =>"nullable|regex:/^[0-9]{10}$/",
                "shipping_alt_contact"           =>"nullable|regex:/^[0-9]{10}$/",
                "shipping_address"               =>"nullable|string",
                "shipping_state_id"              =>"nullable|integer",
                "shipping_pin_code"              =>"nullable|integer",

                "billing_title"                 =>"nullable|string",
                "billing_fname"                 =>"nullable|string",
                "billing_lname"                 =>"nullable|string",
                "billing_email"                 =>"nullable|email",
                "billing_contact"               =>"nullable|regex:/^[0-9]{10}$/",
                "billing_alt_contact"           =>"nullable|regex:/^[0-9]{10}$/",
                "billing_address"               =>"nullable|string",
                "billing_state_id"              =>"nullable|integer",
                "billing_pin_code"              =>"nullable|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                $user = new \App\Models\User();
                $user->code         = \App\Helper\Clib::unique_code(new \App\Models\User(), 'C');
                $user->title        = strip_tags(trim($request->title));
                $user->fname        = strip_tags(trim($request->fname));
                $user->lname        = strip_tags(trim($request->lname));
                $user->email        = strip_tags(trim($request->email));
                $user->contact      = strip_tags(trim($request->contact));
                $user->password     = Hash::make(trim($request->password));
                $user->user_type    = '9';
                $user->status       = '2';
                $user->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
                

                if ($user->save()) {

                    $customerProfile = new \App\Models\CustomerProfile();
                    $customerProfile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));
                    $customerProfile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $customerProfile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));

                    $customerShippingaddress = new \App\Models\CustomerShippingaddress();
                    $customerShippingaddress->title = (empty($request->shipping_title)) ? NULL : strip_tags(trim($request->shipping_title));
                    $customerShippingaddress->fname = (empty($request->shipping_fname)) ? NULL : strip_tags(trim($request->shipping_fname));
                    $customerShippingaddress->lname = (empty($request->shipping_lname)) ? NULL : strip_tags(trim($request->shipping_lname));
                    $customerShippingaddress->email = (empty($request->shipping_email)) ? NULL : strip_tags(trim($request->shipping_email));
                    $customerShippingaddress->contact = (empty($request->shipping_contact)) ? NULL : strip_tags(trim($request->shipping_contact));
                    $customerShippingaddress->alt_contact = (empty($request->shipping_alt_contact)) ? NULL : strip_tags(trim($request->shipping_alt_contact));
                    $customerShippingaddress->address = (empty($request->shipping_address)) ? NULL : strip_tags(trim($request->shipping_address));
                    $customerShippingaddress->state_id = (empty($request->shipping_state_id)) ? NULL : strip_tags(trim($request->shipping_state_id));
                    $customerShippingaddress->pin_code = (empty($request->shipping_pin_code)) ? NULL : strip_tags(trim($request->shipping_pin_code));

                    $customerBillingaddress = new \App\Models\CustomerBillingaddress();
                    $customerBillingaddress->title = (empty($request->billing_title)) ? NULL : strip_tags(trim($request->billing_title));
                    $customerBillingaddress->fname = (empty($request->billing_fname)) ? NULL : strip_tags(trim($request->billing_fname));
                    $customerBillingaddress->lname = (empty($request->billing_lname)) ? NULL : strip_tags(trim($request->billing_lname));
                    $customerBillingaddress->email = (empty($request->billing_email)) ? NULL : strip_tags(trim($request->billing_email));
                    $customerBillingaddress->contact = (empty($request->billing_contact)) ? NULL : strip_tags(trim($request->billing_contact));
                    $customerBillingaddress->alt_contact = (empty($request->billing_alt_contact)) ? NULL : strip_tags(trim($request->billing_alt_contact));
                    $customerBillingaddress->address = (empty($request->billing_address)) ? NULL : strip_tags(trim($request->billing_address));
                    $customerBillingaddress->state_id = (empty($request->billing_state_id)) ? NULL : strip_tags(trim($request->billing_state_id));
                    $customerBillingaddress->pin_code = (empty($request->billing_pin_code)) ? NULL : strip_tags(trim($request->billing_pin_code));
                    
                    $user->customer_profile()->save($customerProfile);
                    $user->customer_shipping()->save($customerShippingaddress);
                    $user->customer_billing()->save($customerBillingaddress);
                    
                    $output = ['type'=>'success', 'message'=>'Successfully create customer'];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Customer';
        $data['allstates'] = \App\Models\State::all();
        return view('admin.manage-user.customer.customer', $data);
    }
    public function edit(Request $request, $id)
    {
        $user_type = '9';
        $user = \App\Models\User::whereUserType($user_type)->find($id);

        if ($request->ajax()) {

            if (blank($user)) {
                return ['type'=>'redirect', 'url'=> route('admin.manage-user.customer.index'),'message'=>'This customer is already delected' ];
            }

            $validator = validator($request->all(), [
                "title"                 =>"required|string",
                "fname"                 =>"required|string",
                "lname"                 =>"required|string",
                "email"                 =>"required|email|unique:users,email,{$id}",
                "contact"               =>"required|regex:/^[0-9]{10}$/",
                "password"              =>"nullable|min:6",
                "address"               =>"nullable|string",
                "state_id"              =>"nullable|integer",
                "pin_code"              =>"nullable|integer",

                "shipping_title"       =>"nullable|string",
                "shipping_fname"       =>"nullable|string",
                "shipping_lname"       =>"nullable|string",
                "shipping_email"       =>"nullable|email",
                "shipping_contact"     =>"nullable|regex:/^[0-9]{10}$/",
                "shipping_alt_contact" =>"nullable|regex:/^[0-9]{10}$/",
                "shipping_address"     =>"nullable|string",
                "shipping_state_id"    =>"nullable|integer",
                "shipping_pin_code"    =>"nullable|integer",

                "billing_title"       =>"nullable|string",
                "billing_fname"       =>"nullable|string",
                "billing_lname"       =>"nullable|string",
                "billing_email"       =>"nullable|email",
                "billing_contact"     =>"nullable|regex:/^[0-9]{10}$/",
                "billing_alt_contact" =>"nullable|regex:/^[0-9]{10}$/",
                "billing_address"     =>"nullable|string",
                "billing_state_id"    =>"nullable|integer",
                "billing_pin_code"    =>"nullable|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $user->title        = strip_tags(trim($request->title));
                $user->fname        = strip_tags(trim($request->fname));
                $user->lname        = strip_tags(trim($request->lname));
                $user->email        = strip_tags(trim($request->email));
                $user->contact      = strip_tags(trim($request->contact));
                if (!empty($request->password)) {
                    $user->password     = Hash::make(trim($request->password));
                }
                
                if ($user->save()) {

                    $user->customer_profile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));
                    $user->customer_profile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $user->customer_profile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));

                    
                    $user->customer_shipping->title = (empty($request->shipping_title)) ? NULL : strip_tags(trim($request->shipping_title));
                    $user->customer_shipping->fname = (empty($request->shipping_fname)) ? NULL : strip_tags(trim($request->shipping_fname));
                    $user->customer_shipping->lname = (empty($request->shipping_lname)) ? NULL : strip_tags(trim($request->shipping_lname));
                    $user->customer_shipping->email = (empty($request->shipping_email)) ? NULL : strip_tags(trim($request->shipping_email));
                    $user->customer_shipping->contact = (empty($request->shipping_contact)) ? NULL : strip_tags(trim($request->shipping_contact));
                    $user->customer_shipping->alt_contact = (empty($request->shipping_alt_contact)) ? NULL : strip_tags(trim($request->shipping_alt_contact));
                    $user->customer_shipping->address = (empty($request->shipping_address)) ? NULL : strip_tags(trim($request->shipping_address));
                    $user->customer_shipping->state_id = (empty($request->shipping_state_id)) ? NULL : strip_tags(trim($request->shipping_state_id));
                    $user->customer_shipping->pin_code = (empty($request->shipping_pin_code)) ? NULL : strip_tags(trim($request->shipping_pin_code));

                    
                    $user->customer_billing->title = (empty($request->billing_title)) ? NULL : strip_tags(trim($request->billing_title));
                    $user->customer_billing->fname = (empty($request->billing_fname)) ? NULL : strip_tags(trim($request->billing_fname));
                    $user->customer_billing->lname = (empty($request->billing_lname)) ? NULL : strip_tags(trim($request->billing_lname));
                    $user->customer_billing->email = (empty($request->billing_email)) ? NULL : strip_tags(trim($request->billing_email));
                    $user->customer_billing->contact = (empty($request->billing_contact)) ? NULL : strip_tags(trim($request->billing_contact));
                    $user->customer_billing->alt_contact = (empty($request->billing_alt_contact)) ? NULL : strip_tags(trim($request->billing_alt_contact));
                    $user->customer_billing->address = (empty($request->billing_address)) ? NULL : strip_tags(trim($request->billing_address));
                    $user->customer_billing->state_id = (empty($request->billing_state_id)) ? NULL : strip_tags(trim($request->billing_state_id));
                    $user->customer_billing->pin_code = (empty($request->billing_pin_code)) ? NULL : strip_tags(trim($request->billing_pin_code));
                    
                    $user->customer_profile->save();
                    $user->customer_shipping->save();
                    $user->customer_billing->save();
                    

                    $output = ['type'=>'success', 'message'=>'Successfully update customer'];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }

        if (blank($user)) {
            return redirect()->route('admin.manage-user.customer.index');
        }
        $data['title'] = 'Edit Customer';
        $data['allstates'] = \App\Models\State::all();
        $data['user'] = $user;
        return view('admin.manage-user.customer.customer', $data);
    }
}
