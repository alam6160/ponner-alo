<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;

class MainController extends Controller
{
    public function dashboard(Request $request)
    {
        $data['agent'] = \App\Models\User::find(Auth::id());

        if (Auth::user()->vendor_type == '2') {

            $membership_price = 0;
            $subscriptionLog = \App\Models\SubscriptionLog::whereAgentId( Auth::id() )->where('expaire_date', '>=', date('Y-m-d'))->orderBy('id', 'desc')->first();
            if (blank($subscriptionLog)) {
                $membership_price = \App\Helper\Clib::getVendorMembershipPrice( Auth::id() );
            }
            $data['subscriptionLog'] = $subscriptionLog;
            $data['membership_price'] = $membership_price;
        }

        $ordersIds = \App\Models\OrderItem::select(['order_detail_id'])->where('agent_id', Auth::id() )->get()->pluck('order_detail_id')->unique()->toArray();

        $data['countDeliveredOrders'] = \App\Models\OrderDetails::where('status', '4')->whereIn('id', $ordersIds)->count();
        $data['countReturnOrders'] = \App\Models\OrderDetails::where('status', '6')->where('return_status', '2')->whereIn('id', $ordersIds)->count();
        $data['countOrders'] = \App\Models\OrderDetails::whereIn('id', $ordersIds)->count();
        $data['countProducts'] = \App\Models\Product::where('user_id', Auth::id())->count();

        return view('agent.site.dashboard', $data);
    }
    public function upgradeAccount(Request $request)
    {
        if ($request->ajax()) {

            $amount = \App\Helper\Clib::getVendorMembershipPrice( Auth::id() );
            $info = array(
                'currency'          => "BDT", 
                'amount'            =>  $amount, 
                'order_id'          => time().''.Auth::id().''.intval($amount), 
                'discsount_amount'  => 0, 
                'disc_percent'      => 0, 
                'client_ip'         => "", 
                'customer_name'     => Auth::user()->fname.' '.Auth::user()->lname, 
                'customer_phone'    => Auth::user()->contact, 
                'email'             => Auth::user()->email, 
                'customer_address'  => "address", 
                'customer_city'     => "city", 
                'customer_state'    => "", 
                'customer_postcode' => "", 
                'customer_country'  => "", 
                'value1'            => "upgrate_account",
                'value2'            => Auth::id(),
                'value3'            => $amount,
            );

            $shurjopay_service = new ShurjopayController();
            $shurjopayResponse = $shurjopay_service->checkout($info);

            $redirect_url = $shurjopayResponse->getTargetUrl();
            $output = ['type'=>'success', 'message'=> 'Successfully create your payment request & redirect payment page', 'url'=> $redirect_url];

            /*

            $membership_price = \App\Helper\Clib::getVendorMembershipPrice( Auth::id() );

            $exp_dt = \Carbon\Carbon::now()->addMonth()->toDateString();
            $subscriptionLog = new \App\Models\SubscriptionLog();
            $subscriptionLog->agent_id = Auth::id();
            $subscriptionLog->membership_price = $membership_price;
            $subscriptionLog->date = \Carbon\Carbon::now()->toDateString();
            $subscriptionLog->expaire_date = $exp_dt;
            $subscriptionLog->save();

            $user = \App\Models\User::find( Auth::id() );
            $user->vendor_type = '2';
            $user->save();

            $text = 'Your subscription pack will be expired at '.date('d-m-Y', strtotime($subscriptionLog->expaire_date)); 

            $output = ['type'=> 'success', 'message'=> 'Successfully Upgrade Account', 'text'=>$text ];
            */
            return response()->json($output);

        }
    }
    public function upgradePage(Request $request)
    {
        if (Auth::user()->vendor_type == '1') {
            return redirect()->route('agent.dashboard');
        }
        $subscriptionLog = \App\Models\SubscriptionLog::whereAgentId( Auth::id() )->where('expaire_date', '>=', date('Y-m-d'))->orderBy('id', 'desc')->first();
        if (!blank($subscriptionLog)) {
            return redirect()->route('agent.dashboard');
        }

        $data['membership_price'] = \App\Helper\Clib::getVendorMembershipPrice( Auth::id() );
        return view('agent.site.upgrade-page', $data);
    }
    public function apply_payment(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "payment_mode"   =>"required|in:1,2",
                "remark"         =>"required|string",
                "payment_proof"  =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                //dd($request->all());
                $applicationPayment = \App\Models\ApplicationPayment::where('user_id', Auth::id())->first();
                if (!blank($applicationPayment)) {
                    if ($applicationPayment->status == 1) {
                        return response()->json(['type'=>'error', 'message'=> 'Already applied & waiting approvation']);
                    }elseif ($applicationPayment->status == 2) {
                        return response()->json(['type'=>'error', 'message'=> 'Already Verified your payment request']);
                    }
                }

                $payment_proof = NULL;
                if ($_FILES['payment_proof']['error'] == 0) {
                    $payment_proof = $request->file('payment_proof')->store('uploads');
                }

                $agentProfile = \App\Models\AgentProfile::select('amount')->where('agent_id', Auth::id())->first();

                $applicationPayment = new \App\Models\ApplicationPayment();
                $applicationPayment->req_no = \App\Helper\Clib::unique_code(new \App\Models\ApplicationPayment(), 'REQ');
                $applicationPayment->user_id = Auth::id();
                $applicationPayment->department = 'hsp';
                $applicationPayment->payable_amount = $agentProfile->amount;
                $applicationPayment->payment_mode = strip_tags($request->payment_mode);
                $applicationPayment->remark = (empty($request->remark)) ? NULL : strip_tags(trim($request->remark));
                $applicationPayment->payment_proof = (empty($payment_proof)) ? NULL : $payment_proof;

                if ($applicationPayment->save()) {
                    $output = ['type'=>'success', 'message'=>'Successfully submit your request', 'applicationPayment'=> $applicationPayment];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                } 

            }
            return response()->json($output);
        }
    }
    public function profile(Request $request)
    {
        $user = \App\Models\User::find( Auth::id() );

        if ($request->ajax()) {

            if (blank($user)) {
                return ['type'=>'redirect', 'url'=> route('admin.manage-user.agent.index'),'message'=>'This Vendor is already delected' ];
            }

            $validator = validator($request->all(), [
                "title"                 =>"required|string",
                "fname"                 =>"required|string",
                "lname"                 =>"required|string",
                "email"                 =>"required|email|unique:users,email,{$user->id}",
                "contact"               =>"required|regex:/^[0-9]{10}$/",
                "servicable_pincodes.*" =>"nullable|integer",
                "organization_name"     =>"nullable|string",
                "licence"               =>"nullable|string",
                "address"               =>"nullable|string",
                "state_id"              =>"nullable|integer",
                "pin_code"              =>"nullable|integer",
                "statehead_id"          =>"nullable|integer",
                "brand_logo_file"       =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "gst_file"              =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "drug_licence_file"     =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "aadhaar_file"          =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                /*
                $reservepincodesArr = explode(',', $agentReservePincodes->reservepincodes);
                $servicable_pincodes = $request->servicable_pincodes;
                $commonPincodes = array_intersect($reservepincodesArr, $servicable_pincodes);

                if (!empty($commonPincodes)) {
                    $message = "This ".implode(",", $commonPincodes)." pincodes are already Reserved";
                    return ['type'=>'error', 'message'=> $message];
                } */

                $user->title        = strip_tags(trim($request->title));
                $user->fname        = strip_tags(trim($request->fname));
                $user->lname        = strip_tags(trim($request->lname));
                $user->email        = strip_tags(trim($request->email));
                $user->contact      = strip_tags(trim($request->contact));
                
                if ($user->save()) {

                    $brand_logo_file = $user->agent_prfile->brand_logo_file;
                    if (isset($_FILES['brand_logo_file'])) {
                        if ($_FILES['brand_logo_file']['error'] == 0) {
                            $brand_logo_file = $request->file('brand_logo_file')->store('uploads');
                            if (!empty($user->agent_prfile->brand_logo_file)) {
                                $path = public_path($user->agent_prfile->brand_logo_file);
                                if (is_file($path)) {
                                    unlink($path);
                                }
                            }
                        }
                    }
                    
                    $gst_file = $user->agent_prfile->gst_file;
                    if (isset($_FILES['gst_file'])) {
                        if ($_FILES['gst_file']['error'] == 0) {
                            $gst_file = $request->file('gst_file')->store('uploads');
                            if (!empty($user->agent_prfile->gst_file)) {
                                $path = public_path($user->agent_prfile->gst_file);
                                if (is_file($path)) {
                                    unlink($path);
                                }
                            }
                        }
                    }
                    
                    $drug_licence_file = $user->agent_prfile->drug_licence_file;
                    if (isset($_FILES['drug_licence_file'])) {
                        if ($_FILES['drug_licence_file']['error'] == 0) {
                            $drug_licence_file = $request->file('drug_licence_file')->store('uploads');
                            if (!empty($user->agent_prfile->drug_licence_file)) {
                                $path = public_path($user->agent_prfile->drug_licence_file);
                                if (is_file($path)) {
                                    unlink($path);
                                }
                            }
                        }
                    }
                    
                    $aadhaar_file = $user->agent_prfile->aadhaar_file;
                    if (isset($_FILES['aadhaar_file'])) {
                        if ($_FILES['aadhaar_file']['error'] == 0) {
                            $aadhaar_file = $request->file('aadhaar_file')->store('uploads');
                            if (!empty($user->agent_prfile->aadhaar_file)) {
                                $path = public_path($user->agent_prfile->aadhaar_file);
                                if (is_file($path)) {
                                    unlink($path);
                                }
                            }
                        }
                    }
                    
                    /*
                    $user->agent_prfile->statehead_id = (empty($request->statehead_id)) ? NULL : strip_tags(trim($request->statehead_id));

                    if (empty($request->statehead_id)) {
                        \App\Models\DeliveryboyProfile::whereAgentId($id)->update(['statehead_id'=> NULL]);
                        \App\Models\RetailerProfile::whereAgentId($id)->update(['statehead_id'=> NULL]);
                    } else {
                        \App\Models\DeliveryboyProfile::whereAgentId($id)->update(['statehead_id'=> $request->statehead_id]);
                        \App\Models\RetailerProfile::whereAgentId($id)->update(['statehead_id'=> $request->statehead_id]);
                    }*/
                    
                    $user->agent_prfile->organization_name = (empty($request->organization_name)) ? NULL : strip_tags(trim($request->organization_name));
                    $user->agent_prfile->licence = (empty($request->licence)) ? NULL : strip_tags(trim($request->licence));
                    $user->agent_prfile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));

                    $user->agent_prfile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $user->agent_prfile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));

                    $user->agent_prfile->servicable_pincodes = (empty($request->servicable_pincodes)) ? NULL : implode(',', $request->servicable_pincodes);

                    $user->agent_prfile->brand_logo_file  = (empty($brand_logo_file)) ? NULL : strip_tags(trim($brand_logo_file));
                    $user->agent_prfile->gst_file         = (empty($gst_file)) ? NULL : strip_tags(trim($gst_file));
                    $user->agent_prfile->drug_licence_file  = (empty($drug_licence_file)) ? NULL : strip_tags(trim($drug_licence_file));
                    $user->agent_prfile->aadhaar_file     = (empty($aadhaar_file)) ? NULL : strip_tags(trim($aadhaar_file));
                    
                    $user->agent_prfile->save();

                    $files['brand_logo_file'] = (empty($user->agent_prfile->brand_logo_file)) ? NULL : asset($user->agent_prfile->brand_logo_file) ;
                    $files['drug_licence_file'] = (empty($user->agent_prfile->drug_licence_file)) ? NULL : asset($user->agent_prfile->drug_licence_file) ;

                    $output = ['type'=>'success', 'message'=>'Successfully update profile', 'files'=> $files];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }

        $data['user'] = $user;
        return view('agent.site.profile', $data);
    }
    public function chagepassword(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "password"              =>"required|min:6",
                "cpassword"             =>"required|min:6|same:password",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                $user = \App\Models\User::find(auth()->user()->id);
                $user->password     = \Illuminate\Support\Facades\Hash::make(trim($request->password));
                
                if ($user->save()) {
                    $output = ['type'=>'success', 'message'=>'Successfully change password'];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }
        return view('agent.site.chagepassword');
    }
    public function updateBank(Request $request)
    {
        $agentBank = \App\Models\AgentBank::whereAgentId( Auth::id() )->first();

        if ($request->ajax()) {
            $validationArr = [
                "form_type"=>"required|in:1,2"
            ];
            if ($request->form_type == '1') {
                $validationArr['bank_name']         = "required|string";
                $validationArr['account_no']        = "required|integer";
                $validationArr['account_holder']    = "required|string";
                $validationArr['ifsc_code']         = "required|string";
                $validationArr['swift_code']        = "nullable|string";
                $validationArr['bank_remark']       = "nullable|string";
            }elseif ($request->form_type == '2') {
                $validationArr['other_info']    = "required|string";
                $validationArr['other_remark']  = "nullable|string";
            }

            $validator = validator($request->all(), $validationArr);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                if ($request->form_type == '1') {

                    $agentBank->bank_name = (empty($request->bank_name)) ? NULL : strip_tags(trim($request->bank_name));
                    $agentBank->account_no = (empty($request->account_no)) ? NULL : strip_tags(trim($request->account_no));
                    $agentBank->account_holder = (empty($request->account_holder)) ? NULL : strip_tags(trim($request->account_holder));
                    $agentBank->ifsc_code = (empty($request->ifsc_code)) ? NULL : strip_tags(trim($request->ifsc_code));
                    $agentBank->swift_code = (empty($request->swift_code)) ? NULL : strip_tags(trim($request->swift_code));
                    $agentBank->bank_remark = (empty($request->bank_remark)) ? NULL : strip_tags(trim($request->bank_remark));

                }elseif ($request->form_type == '2') {
                    $agentBank->other_info = (empty($request->other_info)) ? NULL : strip_tags(trim($request->other_info));
                    $agentBank->other_remark = (empty($request->other_remark)) ? NULL : strip_tags(trim($request->other_remark));
                }
                $agentBank->save();
                $output = ['type'=>'success', 'message'=>'Successfully update details'];
            }
            return response()->json($output);
        }
        $data['agentBank'] = $agentBank;
        return view('agent.site.bank', $data);
    }
    public function subscriptionLog(Request $request)
    {
        if ($request->ajax()) {
        
            $data = \App\Models\SubscriptionLog::where([
                ['agent_id','=', Auth::id()]
            ])->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->editColumn('date', function($data) {
                    return date('d-m-Y', strtotime($data->date));
                })
                ->editColumn('expaire_date', function($data) {
                    return date('d-m-Y', strtotime($data->expaire_date));
                })
                ->editColumn('membership_price', function($data) use($ccurrency) {
                    return  '<span>'.$ccurrency.''.$data->membership_price.'</span>';
                })
                ->rawColumns(['actions','membership_price'])
                ->make(true);
        }
        $data['table'] = collect([
            'date'      =>'Subscription Date',
            'expaire_date'      =>'Expaired Date',
            'membership_price'  =>'Amount',
            'shurjopayment_order_id'=>'Order ID',
        ]);

        if (Auth::user()->vendor_type == '1') {
            return redirect()->route('agent.dashboard');
        }

        $data['title'] = 'Subscription History';
        $data['module_title'] = 'Subscription';
        $data['indexURL'] = route('agent.subscriptionlog');

        return view('agent.site.subscriptionlog', $data);
    }
}
