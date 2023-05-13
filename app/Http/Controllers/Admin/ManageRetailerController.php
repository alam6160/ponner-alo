<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ManageRetailerController extends Controller
{
    public function index(Request $request)
    {
        $user_type = '5';
        if ($request->ajax()) {

            $select = ['users.*', 'retailer_profiles.agent_id','retailer_profiles.address','retailer_profiles.servicable_pincodes'];

            $data = \App\Models\User::select($select)->join('retailer_profiles','users.id','=','retailer_profiles.retailer_id')->where('users.user_type', $user_type)->withTrashed()->get();

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
                        $btn = '<a href="'.route('admin.manage-user.retailer.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        if ($data->status == 1) {

                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="2" title="Active"><i class="fa fa-user-plus fa-lg"></i></a>';

                        } else {
                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger btn-status" data-row="'.$data->id.'" data-status="1" title="Inactive"><i class="fa fa-user-times fa-lg"></i></a>';
                        }

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Deleted"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->addColumn('retailer_det', function($data){
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
        return view('admin.manage-user.retailer.index', $data);
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
                "organization_name"     =>"nullable|string",
                "licence"               =>"nullable|string",
                "servicable_pincodes.*" =>"required|integer",
                "address"               =>"nullable|string",
                "state_id"              =>"nullable|integer",
                "pin_code"              =>"nullable|integer",
                "agent_id"              =>"required|integer",
                "brand_logo_file"       =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "gst_file"              =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "drug_licence_file"     =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "aadhaar_file"          =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                $user = new \App\Models\User();
                $user->code         = \App\Helper\Clib::unique_code(new \App\Models\User(), 'R');
                $user->title        = strip_tags(trim($request->title));
                $user->fname        = strip_tags(trim($request->fname));
                $user->lname        = strip_tags(trim($request->lname));
                $user->email        = strip_tags(trim($request->email));
                $user->contact      = strip_tags(trim($request->contact));
                $user->password     = Hash::make(trim($request->password));
                $user->user_type    = '5';
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

                    $retailerProfile = new \App\Models\RetailerProfile();
                    
                    $retailerProfile->agent_id = (empty($request->agent_id)) ? NULL : strip_tags(trim($request->agent_id));
                    $retailerProfile->statehead_id = NULL;
                    if (!empty($request->agent_id)) {
                        $agentProfile = \App\Models\AgentProfile::select(['statehead_id'])->whereAgentId($request->agent_id)->first();
                        $retailerProfile->statehead_id = (empty($agentProfile->statehead_id)) ? NULL : $agentProfile->statehead_id ;
                    }

                    $retailerProfile->organization_name = (empty($request->organization_name)) ? NULL : strip_tags(trim($request->organization_name));
                    $retailerProfile->licence = (empty($request->licence)) ? NULL : strip_tags(trim($request->licence));
                    $retailerProfile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));

                    $retailerProfile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $retailerProfile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));

                    $retailerProfile->servicable_pincodes = (empty($request->servicable_pincodes)) ? NULL : implode(',', $request->servicable_pincodes);

                    $retailerProfile->brand_logo_file  = (empty($brand_logo_file)) ? NULL : strip_tags(trim($brand_logo_file));
                    $retailerProfile->gst_file         = (empty($gst_file)) ? NULL : strip_tags(trim($gst_file));
                    $retailerProfile->drug_licence_file  = (empty($drug_licence_file)) ? NULL : strip_tags(trim($drug_licence_file));
                    $retailerProfile->aadhaar_file     = (empty($aadhaar_file)) ? NULL : strip_tags(trim($aadhaar_file));
                    
                    $user->retailer_prfile()->save($retailerProfile);

                    $output = ['type'=>'success', 'message'=>'Successfully create retailer'];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Retailer';
        $data['allagents'] = \App\Models\User::whereUserType('4')->get();
        $data['allstates'] = \App\Models\State::all();
        return view('admin.manage-user.retailer.retailer', $data);
    }
    public function edit(Request $request, $id)
    {
        $user_type = '5';
        $user = \App\Models\User::whereUserType($user_type)->find($id);
        if ($request->ajax()) {

            if (blank($user)) {
                return ['type'=>'redirect', 'url'=> route('admin.manage-user.retailer.index'),'message'=>'This Retailer is already deleted' ];
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
                "agent_id"              =>"required|integer",
                "brand_logo_file"       =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "gst_file"              =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "drug_licence_file"     =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "aadhaar_file"          =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
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

                    $brand_logo_file = $user->retailer_prfile->brand_logo_file;
                    if ($_FILES['brand_logo_file']['error'] == 0) {
                        $brand_logo_file = $request->file('brand_logo_file')->store('uploads');
                        if (!empty($user->retailer_prfile->brand_logo_file)) {
                            $path = public_path($user->retailer_prfile->brand_logo_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }
                    $gst_file = $user->retailer_prfile->gst_file;
                    if ($_FILES['gst_file']['error'] == 0) {
                        $gst_file = $request->file('gst_file')->store('uploads');
                        if (!empty($user->retailer_prfile->gst_file)) {
                            $path = public_path($user->retailer_prfile->gst_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }
                    $drug_licence_file = $user->retailer_prfile->drug_licence_file;
                    if ($_FILES['drug_licence_file']['error'] == 0) {
                        $drug_licence_file = $request->file('drug_licence_file')->store('uploads');
                        if (!empty($user->retailer_prfile->drug_licence_file)) {
                            $path = public_path($user->retailer_prfile->drug_licence_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }
                    $aadhaar_file = $user->retailer_prfile->aadhaar_file;
                    if ($_FILES['aadhaar_file']['error'] == 0) {
                        $aadhaar_file = $request->file('aadhaar_file')->store('uploads');
                        if (!empty($user->retailer_prfile->aadhaar_file)) {
                            $path = public_path($user->retailer_prfile->aadhaar_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }

                    
                    
                    $user->retailer_prfile->agent_id = (empty($request->agent_id)) ? NULL : strip_tags(trim($request->agent_id));

                    $user->retailer_prfile->statehead_id = NULL;
                    if (!empty($request->agent_id)) {
                        $agentProfile = \App\Models\AgentProfile::select(['statehead_id'])->whereAgentId($request->agent_id)->first();
                        $user->retailer_prfile->statehead_id = (empty($agentProfile->statehead_id)) ? NULL : $agentProfile->statehead_id ;
                    }
                    $user->retailer_prfile->organization_name = (empty($request->organization_name)) ? NULL : strip_tags(trim($request->organization_name));
                    $user->retailer_prfile->licence = (empty($request->licence)) ? NULL : strip_tags(trim($request->licence));
                    $user->retailer_prfile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));

                    $user->retailer_prfile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $user->retailer_prfile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));

                    $user->retailer_prfile->servicable_pincodes = (empty($request->servicable_pincodes)) ? NULL : implode(',', $request->servicable_pincodes);

                    $user->retailer_prfile->brand_logo_file  = (empty($brand_logo_file)) ? NULL : strip_tags(trim($brand_logo_file));
                    $user->retailer_prfile->gst_file         = (empty($gst_file)) ? NULL : strip_tags(trim($gst_file));
                    $user->retailer_prfile->drug_licence_file  = (empty($drug_licence_file)) ? NULL : strip_tags(trim($drug_licence_file));
                    $user->retailer_prfile->aadhaar_file     = (empty($aadhaar_file)) ? NULL : strip_tags(trim($aadhaar_file));
                    
                    $user->retailer_prfile->save();

                    $output = ['type'=>'success', 'message'=>'Successfully update retailer'];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }

        if (blank($user)) {
            return redirect()->route('admin.manage-user.retailer.index');
        }
        $data['title'] = 'Edit Retailer';
        $data['allagents'] = \App\Models\User::whereUserType('4')->get();
        $data['allstates'] = \App\Models\State::all();
        $data['user'] = $user;
        return view('admin.manage-user.retailer.retailer', $data);
    }
}
