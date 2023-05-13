<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserApplicationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\UserApplication::withTrashed()->orderBy('status', 'asc')->orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {
                    return '<a href="'.route('admin.application.view', ['id'=> $data->id]).'" class="btn-ancher"><i class="fa fa-eye fa-lg"></i></a>';
                })
                ->addColumn('name', function($data){
                    return   $data->fname.' '.$data->lname;
                })
                ->addColumn('contact_det', function($data){
                    return   $data->email.'<br>'.$data->contact;
                })
                ->editColumn('status', function($data){
                    return \App\Helper\Helper::applicationStatus($data->status);
                })
                ->editColumn('department', function($data){
                    return strtoupper($data->department);
                })
                ->editColumn('created_at', function($data){
                    return date('d-m-Y', strtotime($data->created_at));
                })
                ->rawColumns(['actions','contact_det'])
                ->make(true);
        }
        $data['allstates'] = \App\Models\State::all();
        return view('admin.application.index', $data);
    }
    public function view(Request $request, $id)
    {
        $userApplication = \App\Models\UserApplication::find($id);
        if (blank($userApplication)) {
            return redirect()->route('admin.application.index');
        }


        //AGENT PINCODES
        $agentReservePincodes = \App\Models\AgentProfile::selectRaw('GROUP_CONCAT(servicable_pincodes) as reservepincodes')->first();
        $collectionARPincodes = collect( explode(',', $agentReservePincodes->reservepincodes));
        $data['collectionARPincodes'] = $collectionARPincodes;
        $data['agentReservePincodes'] = $agentReservePincodes;

        //SATEHEAD STATE
        $servicable_state_ids = \App\Models\StateheadProfile::select('servicable_state_id')->get()->pluck('servicable_state_id')->unique()->toArray();
        $data['servicable_state_ids'] = $servicable_state_ids;
        $data['stateReserveStates'] = \App\Models\State::whereNotIn('id', array_filter($servicable_state_ids))->get();

        $data['allstateheads'] = \App\Models\User::whereUserType('3')->get();
        

        $data['userApplication'] = $userApplication;
        return view('admin.application.view', $data);
    }
    public function approve_satehead(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "email"                 =>"required|email|unique:users,email",
                "user_application_id"   =>"required|integer",
                "amount"                =>"required|numeric",
                "servicable_state_id"   =>"required|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $userApplication = \App\Models\UserApplication::find($request->user_application_id);
                if (blank($userApplication)) {
                    $output = ['type'=>'error', 'message'=> 'Application data not found...'];
                } else {
                    $searchState = \App\Models\StateheadProfile::where('servicable_state_id', $request->servicable_state_id)->first();
                    if (!blank($searchState)) {
                        $output = ['type'=>'error', 'message'=> 'state is already reserve'];
                    } else {
                        $user = new \App\Models\User();
                        $user->code         = \App\Helper\Clib::unique_code(new \App\Models\User(), 'SH');
                        $user->title        = $userApplication->title;
                        $user->fname        = $userApplication->fname;
                        $user->lname        = $userApplication->lname;
                        $user->email        = strip_tags(trim($request->email));
                        $user->contact      = $userApplication->contact;
                        $user->password     = Hash::make(trim('123456'));
                        $user->user_type    = '3';
                        $user->status       = '1';
                        $user->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();

                        if ($user->save()) {

                            $content = (object) json_decode($userApplication->content, true);

                            $stateheadProfile = new \App\Models\StateheadProfile();
                    
                            
                            $stateheadProfile->organization_name = (empty($content->organization_name)) ? NULL : $content->organization_name;
                            $stateheadProfile->licence = (empty($content->licence)) ? NULL : $content->licence;
                            $stateheadProfile->address = (empty($userApplication->address)) ? NULL : $userApplication->address;

                            $stateheadProfile->pin_code = (empty($userApplication->pin_code)) ? NULL : $userApplication->pin_code;
                            $stateheadProfile->state_id = (empty($userApplication->state_id)) ? NULL : $userApplication->state_id;
                            $stateheadProfile->amount = (empty($request->amount)) ? NULL : strip_tags(trim($request->amount));
                            $stateheadProfile->servicable_state_id = (empty($request->servicable_state_id)) ? NULL : strip_tags(trim($request->servicable_state_id));

                            $stateheadProfile->brand_logo_file  = (empty($content->brand_logo_file)) ? NULL : $content->brand_logo_file;
                            $stateheadProfile->gst_file         = (empty($content->gst_file)) ? NULL : $content->gst_file;

                            $stateheadProfile->drug_licence_file = (empty($content->drug_licence_file)) ? NULL : $content->drug_licence_file;
                            $stateheadProfile->aadhaar_file     = (empty($content->aadhaar_file)) ? NULL : $content->aadhaar_file;
                            $user->statehead_prfile()->save($stateheadProfile);

                            $userApplication->status = '2';
                            $userApplication->save();

                            $output = ['type'=>'success', 'message'=>'Successfully approve application & create state-head account'];

                        } else {
                            $output = ['type'=>'error', 'message'=> 'try again..'];
                        }
                    }
                }
            }
            return response()->json($output);
        }
    }
    public function approve_agent(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "email"                 =>"required|email|unique:users,email",
                "user_application_id"   =>"required|integer",
                "amount"                =>"required|numeric",
                "satehead_id"           =>"nullable|integer",
                "servicable_pincodes.*" =>"required|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $userApplication = \App\Models\UserApplication::find($request->user_application_id);
                if (blank($userApplication)) {
                    $output = ['type'=>'error', 'message'=> 'Application data not found...'];
                } else {
                    $agentReservePincodes = \App\Models\AgentProfile::selectRaw('GROUP_CONCAT(servicable_pincodes) as reservepincodes')->first();

                    $reservepincodesArr = explode(',', $agentReservePincodes->reservepincodes);
                    $servicable_pincodes = $request->servicable_pincodes;
                    $commonPincodes = array_intersect($reservepincodesArr, $servicable_pincodes);

                    if (!empty($commonPincodes)) {
                        $message = "This ".implode(",", $commonPincodes)." pincodes are already Reserved";
                        return ['type'=>'error', 'message'=> $message];
                    }

                    // AGENT CREATE.....
                    $user = new \App\Models\User();
                    $user->code         = \App\Helper\Clib::unique_code(new \App\Models\User(), 'A');
                    $user->title        = $userApplication->title;
                    $user->fname        = $userApplication->fname;
                    $user->lname        = $userApplication->lname;
                    $user->email        = strip_tags(trim($request->email));
                    $user->contact      = $userApplication->contact;
                    $user->password     = Hash::make(trim('123456'));
                    $user->user_type    = '4';
                    $user->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();

                    if ($user->save()) {
                        
                        $content = (object) json_decode($userApplication->content, true);

                        $agentProfile = new \App\Models\AgentProfile();

                        $agentProfile->statehead_id = (empty($request->satehead_id)) ? NULL : strip_tags(trim($request->satehead_id));

                        $agentProfile->organization_name = (empty($content->organization_name)) ? NULL : $content->organization_name;
                        $agentProfile->licence = (empty($content->licence)) ? NULL : $content->licence;
                        $agentProfile->address = (empty($userApplication->address)) ? NULL : $userApplication->address;
    
                        $agentProfile->pin_code = (empty($userApplication->pin_code)) ? NULL : $userApplication->pin_code;
                        $agentProfile->state_id = (empty($userApplication->state_id)) ? NULL : $userApplication->state_id;
    
                        $agentProfile->servicable_pincodes = (empty($request->servicable_pincodes)) ? NULL : implode(',', $request->servicable_pincodes);
                        $agentProfile->amount = (empty($request->amount)) ? NULL : strip_tags(trim($request->amount));
    
                        $agentProfile->brand_logo_file      = (empty($content->brand_logo_file)) ? NULL : $content->brand_logo_file;
                        $agentProfile->gst_file             = (empty($content->gst_file)) ? NULL : $content->gst_file;
                        $agentProfile->drug_licence_file    = (empty($content->drug_licence_file)) ? NULL : $content->drug_licence_file;
                        $agentProfile->aadhaar_file         = (empty($content->aadhaar_file)) ? NULL : $content->aadhaar_file;
                        
                        $user->agent_prfile()->save($agentProfile);

                        $userApplication->status = '2';
                        $userApplication->save();
    
                        $output = ['type'=>'success', 'message'=>'Successfully approve application & create HSP'];

                    } else {
                        $output = ['type'=>'error', 'message'=> 'try again..'];
                    }
                }
            }
            return response()->json($output);
        }
    }
    public function cancel(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "user_application_id"   =>"required|integer",
                "cancel_msg"            =>"nullable|string",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $userApplication = \App\Models\UserApplication::find($request->user_application_id);
                if (blank($userApplication)) {
                    $output = ['type'=>'error', 'message'=> 'Application data not found...'];
                } else {

                    $userApplication->status = '3';
                    $userApplication->cancel_msg = (empty($request->cancel_msg)) ? NULL : strip_tags(trim($request->cancel_msg));

                    if ($userApplication->save()) {
                        $output = ['type'=>'success', 'message'=>'Successfully cancel application', 'cancel_msg'=> $userApplication->cancel_msg];
                    } else {
                        $output = ['type'=>'error', 'message'=> 'try again..'];
                    }     
                }
            }
            return response()->json($output);
        }
    }
}
