<?php

namespace App\Http\Controllers\Statehead;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ManageAgentController extends Controller
{
    public function index(Request $request)
    {
        $user_type = '4';
        if ($request->ajax()) {

            $select = ['users.*','agent_profiles.address','agent_profiles.servicable_pincodes'];

            
            $state_id = $request->input('state_id', '');
            $query = $request->input('query', '');
            $pincodes = $request->input('pincodes', '');
            $start_date = $request->input('start_date', '');
            $close_date = $request->input('close_date', '');

            if (empty($state_id)  && empty($query) && empty($pincodes) && empty($start_date) && empty($close_date) ) {
                $data = \App\Models\User::select($select)->join('agent_profiles','agent_profiles.agent_id', '=','users.id')->where('users.user_type', $user_type)->where('agent_profiles.statehead_id', auth()->user()->id)->get();
            } else {
                
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

                    } else {
                        $btn = '<a href="'.route('statehead.manage-user.agent.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';
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

                ->rawColumns(['actions','contact_det'])
                ->make(true);
        }

        $data['allstates'] = \App\Models\State::all();
        return view('statehead.manage-user.agent.index', $data);
    }
    public function create(Request $request)
    {
        $agentReservePincodes = \App\Models\AgentProfile::selectRaw('GROUP_CONCAT(servicable_pincodes) as reservepincodes')->first();

        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "title"                 =>"required|string",
                "fname"                 =>"required|string",
                "lname"                 =>"required|string",
                "email"                 =>"required|email|unique:users,email",
                "contact"               =>"required|regex:/^[0-9]{10}$/",
                "password"              =>"required|min:6",
                "organization_name"     =>"nullable|string",
                "licence"               =>"nullable|string",
                "servicable_pincodes.*" =>"required|integer",
                "address"               =>"nullable|string",
                "state_id"              =>"nullable|integer",
                "pin_code"              =>"nullable|integer",
                "brand_logo_file"       =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "gst_file"              =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "drug_licence_file"     =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "aadhaar_file"          =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $reservepincodesArr = explode(',', $agentReservePincodes->reservepincodes);
                $servicable_pincodes = $request->servicable_pincodes;
                $commonPincodes = array_intersect($reservepincodesArr, $servicable_pincodes);

                if (!empty($commonPincodes)) {
                    $message = "This ".implode(",", $commonPincodes)." pincodes are already Reserved";
                    return ['type'=>'error', 'message'=> $message];
                }

                $user = new \App\Models\User();
                $user->code         = \App\Helper\Clib::unique_code(new \App\Models\User(), 'A');
                $user->title        = strip_tags(trim($request->title));
                $user->fname        = strip_tags(trim($request->fname));
                $user->lname        = strip_tags(trim($request->lname));
                $user->email        = strip_tags(trim($request->email));
                $user->contact      = strip_tags(trim($request->contact));
                $user->password     = Hash::make(trim($request->password));
                $user->user_type    = '4';
                $user->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
                

                if ($user->save()) {

                    $brand_logo_file = NULL;
                    if ($_FILES['brand_logo_file']['error'] == 0) {
                        $brand_logo_file = $request->file('brand_logo_file')->store('uploads');
                    }
                    $gst_file = NULL;
                    if ($_FILES['gst_file']['error'] == 0) {
                        $gst_file = $request->file('gst_file')->store('uploads');
                    }
                    $drug_licence_file = NULL;
                    if ($_FILES['drug_licence_file']['error'] == 0) {
                        $drug_licence_file = $request->file('drug_licence_file')->store('uploads');
                    }
                    $aadhaar_file = NULL;
                    if ($_FILES['aadhaar_file']['error'] == 0) {
                        $aadhaar_file = $request->file('aadhaar_file')->store('uploads');
                    }

                    $agentProfile = new \App\Models\AgentProfile();
                    
                    $agentProfile->statehead_id = auth()->user()->id;
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

                    $output = ['type'=>'success', 'message'=>'Successfully create HSP'];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }
        $data['title'] = 'Create HSP';
        $data['allstates'] = \App\Models\State::all();
        $data['agentReservePincodes'] = $agentReservePincodes;
        return view('statehead.manage-user.agent.agent', $data);
    }
    public function edit(Request $request, $id)
    {
        $user_type = '4';
        $user = \App\Models\User::whereHas('agent_prfile', function(\Illuminate\Database\Eloquent\Builder $query){
            $query->where('statehead_id', auth()->user()->id);
        })->whereUserType($user_type)->find($id);

        $agentReservePincodes = \App\Models\AgentProfile::selectRaw('GROUP_CONCAT(servicable_pincodes) as reservepincodes')->where('agent_id', '!=', $id)->first();
        
        if ($request->ajax()) {

            if (blank($user)) {
                return ['type'=>'redirect', 'url'=> route('statehead.manage-user.agent.index'),'message'=>'This HSP is already delected' ];
            }

            $validator = validator($request->all(), [
                "title"                 =>"required|string",
                "fname"                 =>"required|string",
                "lname"                 =>"required|string",
                "email"                 =>"required|email|unique:users,email,{$id}",
                "contact"               =>"required|regex:/^[0-9]{10}$/",
                "password"              =>"nullable|min:6",
                "organization_name"     =>"nullable|string",
                "licence"               =>"nullable|string",
                "servicable_pincodes.*" =>"required|integer",
                "address"               =>"nullable|string",
                "state_id"              =>"nullable|integer",
                "pin_code"              =>"nullable|integer",
                "brand_logo_file"       =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "gst_file"              =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "drug_licence_file"     =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "aadhaar_file"          =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $reservepincodesArr = explode(',', $agentReservePincodes->reservepincodes);
                $servicable_pincodes = $request->servicable_pincodes;
                $commonPincodes = array_intersect($reservepincodesArr, $servicable_pincodes);

                if (!empty($commonPincodes)) {
                    $message = "This ".implode(",", $commonPincodes)." pincodes are already Reserved";
                    return ['type'=>'error', 'message'=> $message];
                }

                $user->title        = strip_tags(trim($request->title));
                $user->fname        = strip_tags(trim($request->fname));
                $user->lname        = strip_tags(trim($request->lname));
                $user->email        = strip_tags(trim($request->email));
                $user->contact      = strip_tags(trim($request->contact));
                if (!empty($request->password)) {
                    $user->password     = Hash::make(trim($request->password));
                }
                
                if ($user->save()) {

                    $brand_logo_file = $user->agent_prfile->brand_logo_file;
                    if ($_FILES['brand_logo_file']['error'] == 0) {
                        $brand_logo_file = $request->file('brand_logo_file')->store('uploads');
                        if (!empty($user->agent_prfile->brand_logo_file)) {
                            $path = public_path($user->agent_prfile->brand_logo_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }
                    $gst_file = $user->agent_prfile->gst_file;
                    if ($_FILES['gst_file']['error'] == 0) {
                        $gst_file = $request->file('gst_file')->store('uploads');
                        if (!empty($user->agent_prfile->gst_file)) {
                            $path = public_path($user->agent_prfile->gst_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }
                    $drug_licence_file = $user->agent_prfile->drug_licence_file;
                    if ($_FILES['drug_licence_file']['error'] == 0) {
                        $drug_licence_file = $request->file('drug_licence_file')->store('uploads');
                        if (!empty($user->agent_prfile->drug_licence_file)) {
                            $path = public_path($user->agent_prfile->drug_licence_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }
                    $aadhaar_file = $user->agent_prfile->aadhaar_file;
                    if ($_FILES['aadhaar_file']['error'] == 0) {
                        $aadhaar_file = $request->file('aadhaar_file')->store('uploads');
                        if (!empty($user->agent_prfile->aadhaar_file)) {
                            $path = public_path($user->agent_prfile->aadhaar_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }

                    \App\Models\DeliveryboyProfile::whereAgentId($id)->update(['statehead_id'=> auth()->user()->id ]);
                    \App\Models\RetailerProfile::whereAgentId($id)->update(['statehead_id'=> auth()->user()->id ]);
                    
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

                    $output = ['type'=>'success', 'message'=>'Successfully update HSP'];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }

        if (blank($user)) {
            return redirect()->route('statehead.manage-user.agent.index');
        }
        $data['title'] = 'Edit HSP';
        $data['allstates'] = \App\Models\State::all();
        $data['agentReservePincodes'] = $agentReservePincodes;
        $data['user'] = $user;
        return view('statehead.manage-user.agent.agent', $data);
    }
}
