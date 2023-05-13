<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ManageAgentController extends Controller
{
    public function index(Request $request)
    {
        $user_type = '4';

        /*
        \Illuminate\Support\Facades\DB::enableQueryLog();
        $select = ['users.*','agent_profiles.address','agent_profiles.servicable_pincodes'];

        $data = \App\Models\User::select($select)->join('agent_profiles','agent_profiles.agent_id', '=','users.id')
        ->where('users.fname', 'like', '%o%')
        //->orWhere('users.lname', 'like', "%ch%")
        ->where('users.email', 'like', '%%')
        ->where('agent_profiles.address', 'like', "%%")
        ->where('agent_profiles.state_id', 'like', '%%')
        //->orWhere('agent_profiles.servicable_pincodes', 'like', "%%")
        ->where('users.user_type', $user_type)
        ->withTrashed()->get();

        dump($data);
        $query = \Illuminate\Support\Facades\DB::getQueryLog();
        dd( end($query));
        */

        

        
        // $data = \App\Models\User::where('user_type', $user_type)
        //         ->Where('fname', 'like', '%'.$query.'%')
        //         ->orWhere('lname', 'like', '%'.$query.'%')
        //         ->orWhere('email', 'like', '%'.$query.'%')
        //         ->orWhere('contact', 'like', '%'.$query.'%')
        //         ->withTrashed()->get();
        // $whereSql = "(`fname` like '%?%' or `lname` like '%?%' or `email` like '%?%')";


        // $query = (string) 'A';
        // \Illuminate\Support\Facades\DB::enableQueryLog();
        // $whereSql = "user_type = ? and (`fname` like '%?%' or `lname` like '%?%' or `email` like '%?%' or `contact` like '%?%')";
        
        // $data = \App\Models\User::withTrashed()->whereRaw($whereSql, ['4', $query, $query, $query, $query])->get();

        // dump($data);
        // $query = \Illuminate\Support\Facades\DB::getQueryLog();
        // dd( end($query));
        /*
        $data = \Illuminate\Support\Facades\DB::table('users')->select("  where user_type = 4 and (`fname` like '%A%' or `lname` like '%A%' or `email` like '%A%' or `contact` like '%A%')")->get();

        dump($data);
        $query = \Illuminate\Support\Facades\DB::getQueryLog();
        dd( end($query)); */

        if ($request->ajax()) {

            $query = $request->input('query', '');
            if (empty($query)) {

                $data = \App\Models\User::whereUserType($user_type)->withTrashed()->get();

            } else {

                $data = \App\Models\User::where('user_type', $user_type)
                    ->Where('fname', 'like', '%'.$query.'%')
                    ->orWhere('lname', 'like', '%'.$query.'%')
                    ->orWhere('email', 'like', '%'.$query.'%')
                    ->orWhere('contact', 'like', '%'.$query.'%')
                    ->withTrashed()->get();
            }

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
                        $btn = '<a href="'.route('admin.manage-user.agent.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        if ($data->status == 1) {

                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="2" title="Active"><i class="fa fa-user-plus fa-lg"></i></a>';

                        } else {
                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger btn-status" data-row="'.$data->id.'" data-status="1" title="Inactive"><i class="fa fa-user-times fa-lg"></i></a>';
                        }

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->addColumn('agent_det', function($data){
                    return $data->fname.' '.$data->lname;
                })
                ->addColumn('contact_det', function($data){
                    return $data->contact.'<br>'.$data->email;
                })
                ->addColumn('active_status', function($data){
                    return ($data->status == '1') ? 'Inactive' : 'Active' ;
                })
                ->editColumn('vendor_type', function($data){
                    return ($data->vendor_type == '1') ? 'Regular' : 'Subscription' ;
                })

                ->rawColumns(['actions','contact_det'])
                ->make(true);
        }

        $data['title'] = 'All Vendors';
        $data['indexURL'] = route('admin.manage-user.agent.index');
        $data['allstates'] = \App\Models\State::all();
        return view('admin.manage-user.agent.index', $data);
    }
    public function regular(Request $request)
    {
        $user_type = '4';

        if ($request->ajax()) {

            $query = $request->input('query', '');
            if (empty($query)) {

                $data = \App\Models\User::whereUserType($user_type)->where('vendor_type', '1')->withTrashed()->get();

            } else {

                $data = \App\Models\User::where('user_type', $user_type)
                    ->Where('fname', 'like', '%'.$query.'%')
                    ->orWhere('lname', 'like', '%'.$query.'%')
                    ->orWhere('email', 'like', '%'.$query.'%')
                    ->orWhere('contact', 'like', '%'.$query.'%')
                    ->withTrashed()->get();
            }

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
                        $btn = '<a href="'.route('admin.manage-user.agent.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        if ($data->status == 1) {

                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="2" title="Active"><i class="fa fa-user-plus fa-lg"></i></a>';

                        } else {
                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger btn-status" data-row="'.$data->id.'" data-status="1" title="Inactive"><i class="fa fa-user-times fa-lg"></i></a>';
                        }

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->addColumn('agent_det', function($data){
                    return $data->fname.' '.$data->lname;
                })
                ->addColumn('contact_det', function($data){
                    return $data->contact.'<br>'.$data->email;
                })
                ->addColumn('active_status', function($data){
                    return ($data->status == '1') ? 'Inactive' : 'Active' ;
                })
                ->editColumn('vendor_type', function($data){
                    return ($data->vendor_type == '1') ? 'Regular' : 'Subscription' ;
                })

                ->rawColumns(['actions','contact_det'])
                ->make(true);
        }
        $data['title'] = 'Regular Vendors';
        $data['indexURL'] = route('admin.manage-user.agent.regular');
        $data['allstates'] = \App\Models\State::all();
        return view('admin.manage-user.agent.index', $data);
    }
    public function subscription(Request $request)
    {
        $user_type = '4';

        if ($request->ajax()) {

            $query = $request->input('query', '');
            if (empty($query)) {

                $data = \App\Models\User::whereUserType($user_type)->where('vendor_type', '2')->withTrashed()->get();

            } else {

                $data = \App\Models\User::where('user_type', $user_type)
                    ->Where('fname', 'like', '%'.$query.'%')
                    ->orWhere('lname', 'like', '%'.$query.'%')
                    ->orWhere('email', 'like', '%'.$query.'%')
                    ->orWhere('contact', 'like', '%'.$query.'%')
                    ->withTrashed()->get();
            }

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
                        $btn = '<a href="'.route('admin.manage-user.agent.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        if ($data->status == 1) {

                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="2" title="Active"><i class="fa fa-user-plus fa-lg"></i></a>';

                        } else {
                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger btn-status" data-row="'.$data->id.'" data-status="1" title="Inactive"><i class="fa fa-user-times fa-lg"></i></a>';
                        }

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->addColumn('agent_det', function($data){
                    return $data->fname.' '.$data->lname;
                })
                ->addColumn('contact_det', function($data){
                    return $data->contact.'<br>'.$data->email;
                })
                ->addColumn('active_status', function($data){
                    return ($data->status == '1') ? 'Inactive' : 'Active' ;
                })
                ->editColumn('vendor_type', function($data){
                    return ($data->vendor_type == '1') ? 'Regular' : 'Subscription' ;
                })

                ->rawColumns(['actions','contact_det'])
                ->make(true);
        }
        $data['title'] = 'Membership Vendors';
        $data['indexURL'] = route('admin.manage-user.agent.subscription');
        $data['allstates'] = \App\Models\State::all();
        return view('admin.manage-user.agent.index', $data);
    }

    public function create(Request $request)
    {
        //$agentReservePincodes = \App\Models\AgentProfile::selectRaw('GROUP_CONCAT(servicable_pincodes) as reservepincodes')->first();

        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "title"                 =>"required|string",
                "fname"                 =>"required|string",
                "lname"                 =>"required|string",
                "email"                 =>"required|email|unique:users,email",
                "contact"               =>"required|regex:/^[0-9]{10}$/",
                "password"              =>"required|min:6",
                "vendor_type"           =>"required|in:1,2",
                "organization_name"     =>"nullable|string",
                "licence"               =>"nullable|string",
                "servicable_pincodes.*" =>"nullable|integer",
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

                $user = new \App\Models\User();
                $user->code         = \App\Helper\Clib::unique_code(new \App\Models\User(), 'V');
                $user->title        = strip_tags(trim($request->title));
                $user->fname        = strip_tags(trim($request->fname));
                $user->lname        = strip_tags(trim($request->lname));
                $user->email        = strip_tags(trim($request->email));
                $user->contact      = strip_tags(trim($request->contact));
                $user->password     = Hash::make(trim($request->password));
                $user->user_type    = '4';
                $user->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
                $user->vendor_type = (empty($request->vendor_type)) ? NULL : strip_tags(trim($request->vendor_type));
                $user->wallet = 0;
                $user->status = '2';
                

                if ($user->save()) {

                
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

                    $agentProfile = new \App\Models\AgentProfile();
                    
                    $agentProfile->statehead_id = (empty($request->statehead_id)) ? NULL : strip_tags(trim($request->statehead_id));
                    $agentProfile->organization_name = (empty($request->organization_name)) ? NULL : strip_tags(trim($request->organization_name));
                    
                    $agentProfile->licence = (empty($request->licence)) ? NULL : strip_tags(trim($request->licence));
                    $agentProfile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));

                    $agentProfile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $agentProfile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));

                    $agentProfile->servicable_pincodes = (empty($request->servicable_pincodes)) ? NULL : implode(',', $request->servicable_pincodes);

                    $agentProfile->brand_logo_file  = (empty($brand_logo_file)) ? NULL : strip_tags(trim($brand_logo_file));
                    $agentProfile->gst_file         = (empty($gst_file)) ? NULL : strip_tags(trim($gst_file));
                    $agentProfile->drug_licence_file  = (empty($drug_licence_file)) ? NULL : strip_tags(trim($drug_licence_file));
                    $agentProfile->aadhaar_file     = (empty($aadhaar_file)) ? NULL : strip_tags(trim($aadhaar_file));
                    
                    $user->agent_prfile()->save($agentProfile);

                    $agentBank = new \App\Models\AgentBank();
                    $user->agent_bank()->save($agentBank);

                    $output = ['type'=>'success', 'message'=>'Successfully create Vendor'];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Vendor';
        $data['allstates'] = \App\Models\State::all();
        $data['allstateheads'] = \App\Models\User::whereUserType('3')->get();
        //$data['agentReservePincodes'] = $agentReservePincodes;
        return view('admin.manage-user.agent.agent', $data);
    }
    public function edit(Request $request, $id)
    {
        $user_type = '4';
        $agentReservePincodes = \App\Models\AgentProfile::selectRaw('GROUP_CONCAT(servicable_pincodes) as reservepincodes')->where('agent_id', '!=', $id)->first();

        $user = \App\Models\User::whereUserType($user_type)->find($id);
        if ($request->ajax()) {

            if (blank($user)) {
                return ['type'=>'redirect', 'url'=> route('admin.manage-user.agent.index'),'message'=>'This Vendor is already delected' ];
            }

            $validator = validator($request->all(), [
                "title"                 =>"required|string",
                "fname"                 =>"required|string",
                "lname"                 =>"required|string",
                "email"                 =>"required|email|unique:users,email,{$id}",
                "contact"               =>"required|regex:/^[0-9]{10}$/",
                "password"              =>"nullable|min:6",
                "vendor_type"           =>"required|in:1,2",
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
                if (!empty($request->password)) {
                    $user->password     = Hash::make(trim($request->password));
                }
                $user->vendor_type = (empty($request->vendor_type)) ? NULL : strip_tags(trim($request->vendor_type));
                
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
                    
                    $user->agent_prfile->statehead_id = (empty($request->statehead_id)) ? NULL : strip_tags(trim($request->statehead_id));

                    if (empty($request->statehead_id)) {
                        \App\Models\DeliveryboyProfile::whereAgentId($id)->update(['statehead_id'=> NULL]);
                        \App\Models\RetailerProfile::whereAgentId($id)->update(['statehead_id'=> NULL]);
                    } else {
                        \App\Models\DeliveryboyProfile::whereAgentId($id)->update(['statehead_id'=> $request->statehead_id]);
                        \App\Models\RetailerProfile::whereAgentId($id)->update(['statehead_id'=> $request->statehead_id]);
                    }
                    
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

                    $output = ['type'=>'success', 'message'=>'Successfully update Vendor', 'files'=> $files];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }

        if (blank($user)) {
            return redirect()->route('admin.manage-user.agent.index');
        }
        $data['title'] = 'Edit Vendor';
        $data['allstates'] = \App\Models\State::all();
        $data['allstateheads'] = \App\Models\User::whereUserType('3')->get();
        $data['agentReservePincodes'] = $agentReservePincodes;
        $data['user'] = $user;
        return view('admin.manage-user.agent.agent', $data);
    }
}
