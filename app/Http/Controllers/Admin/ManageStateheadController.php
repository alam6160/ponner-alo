<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ManageStateheadController extends Controller
{
    public function index(Request $request)
    {
        $user_type = '3';
        if ($request->ajax()) {

            $select = ['users.*','statehead_profiles.address','statehead_profiles.pin_code'];

            $data = \App\Models\User::select($select)->join('statehead_profiles','statehead_profiles.statehead_id', '=','users.id')->where('users.user_type', $user_type)->withTrashed()->get();

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
                        $btn = '<a href="'.route('admin.manage-user.statehead.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        if ($data->status == 1) {

                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="2" title="Active"><i class="fa fa-user-plus fa-lg"></i></a>';

                        } else {
                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger btn-status" data-row="'.$data->id.'" data-status="1" title="Inactive"><i class="fa fa-user-times fa-lg"></i></a>';
                        }

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->addColumn('statehead_det', function($data){
                    return $data->fname.' '.$data->lname;
                })
                ->addColumn('contact_det', function($data){
                    return $data->contact.'<br>'.$data->email;
                })
                ->addColumn('address', function($data){
                    return $data->address;
                })
                ->addColumn('active_status', function($data){
                    return ($data->status == '1') ? 'Inactive' : 'Active' ;
                })

                ->rawColumns(['actions','contact_det'])
                ->make(true);
        }
        $data['allstates'] = \App\Models\State::all();
        return view('admin.manage-user.statehead.index', $data);
    }
    public function create(Request $request)
    {
        $servicable_state_ids = \App\Models\StateheadProfile::select('servicable_state_id')->get()->pluck('servicable_state_id')->unique()->toArray();

        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "title"                 =>"required|string",
                "fname"                 =>"required|string",
                "lname"                 =>"required|string",
                "email"                 =>"required|email|unique:users,email",
                "contact"               =>"required|regex:/^[0-9]{10}$/",
                "password"              =>"required|min:6",
                "servicable_state_id"   =>"required|integer",
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

                $searchState = \App\Models\StateheadProfile::where('servicable_state_id', $request->servicable_state_id)->first();

                if (!blank($searchState)) {
                    return response()->json(['type'=>'error', 'message'=> 'Servicable State is already reserve']);
                }

                $user = new \App\Models\User();
                $user->code         = \App\Helper\Clib::unique_code(new \App\Models\User(), 'SH');
                $user->title        = strip_tags(trim($request->title));
                $user->fname        = strip_tags(trim($request->fname));
                $user->lname        = strip_tags(trim($request->lname));
                $user->email        = strip_tags(trim($request->email));
                $user->contact      = strip_tags(trim($request->contact));
                $user->password     = Hash::make(trim($request->password));
                $user->user_type    = '3';
                $user->status       = '2';
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

                    $stateheadProfile = new \App\Models\StateheadProfile();
                    
                    
                    $stateheadProfile->organization_name = (empty($request->organization_name)) ? NULL : strip_tags(trim($request->organization_name));
                    $stateheadProfile->licence = (empty($request->licence)) ? NULL : strip_tags(trim($request->licence));
                    $stateheadProfile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));

                    $stateheadProfile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $stateheadProfile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));

                    $stateheadProfile->servicable_state_id = (empty($request->servicable_state_id)) ? NULL : strip_tags(trim($request->servicable_state_id));

                    $stateheadProfile->brand_logo_file  = (empty($brand_logo_file)) ? NULL : strip_tags(trim($brand_logo_file));
                    $stateheadProfile->gst_file         = (empty($gst_file)) ? NULL : strip_tags(trim($gst_file));
                    $stateheadProfile->drug_licence_file  = (empty($drug_licence_file)) ? NULL : strip_tags(trim($drug_licence_file));
                    $stateheadProfile->aadhaar_file     = (empty($aadhaar_file)) ? NULL : strip_tags(trim($aadhaar_file));
                    
                    $user->statehead_prfile()->save($stateheadProfile);

                    $servicable_state_ids = \App\Models\StateheadProfile::select('servicable_state_id')->get()->pluck('servicable_state_id')->unique()->toArray();
                    $unReserveStates = \App\Models\State::whereNotIn('id', array_filter($servicable_state_ids))->get();

                    $output = ['type'=>'success', 'message'=>'Successfully create state-head', 'unReserveStates'=> $unReserveStates];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }

        
        $data['unReserveStates'] = \App\Models\State::whereNotIn('id', array_filter($servicable_state_ids))->get();

        $data['title'] = 'Create State Head';
        $data['allstates'] = \App\Models\State::all();
        return view('admin.manage-user.statehead.statehead', $data);
    }
    public function edit(Request $request, $id)
    {
        $user_type = '3';
        $user = \App\Models\User::whereUserType($user_type)->find($id);
        if ($request->ajax()) {

            if (blank($user)) {
                return ['type'=>'redirect', 'url'=> route('admin.manage-user.statehead.index'),'message'=>'This State-head is already delected' ];
            }

            $validator = validator($request->all(), [
                "title"                 =>"required|string",
                "fname"                 =>"required|string",
                "lname"                 =>"required|string",
                "email"                 =>"required|email|unique:users,email,{$id}",
                "contact"               =>"required|regex:/^[0-9]{10}$/",
                "password"              =>"nullable|min:6",
                "servicable_state_id"   =>"required|integer",
                "organization_name"     =>"nullable|string",
                "licence"               =>"nullable|string",
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

                $user->title        = strip_tags(trim($request->title));
                $user->fname        = strip_tags(trim($request->fname));
                $user->lname        = strip_tags(trim($request->lname));
                $user->email        = strip_tags(trim($request->email));
                $user->contact      = strip_tags(trim($request->contact));
                if (!empty($request->password)) {
                    $user->password     = Hash::make(trim($request->password));
                }
                
                if ($user->save()) {

                    $brand_logo_file = $user->statehead_prfile->brand_logo_file;
                    if ($_FILES['brand_logo_file']['error'] == 0) {
                        $brand_logo_file = $request->file('brand_logo_file')->store('uploads');
                        if (!empty($user->statehead_prfile->brand_logo_file)) {
                            $path = public_path($user->statehead_prfile->brand_logo_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }
                    $gst_file = $user->statehead_prfile->gst_file;
                    if ($_FILES['gst_file']['error'] == 0) {
                        $gst_file = $request->file('gst_file')->store('uploads');
                        if (!empty($user->statehead_prfile->gst_file)) {
                            $path = public_path($user->statehead_prfile->gst_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }
                    $drug_licence_file = $user->statehead_prfile->drug_licence_file;
                    if ($_FILES['drug_licence_file']['error'] == 0) {
                        $drug_licence_file = $request->file('drug_licence_file')->store('uploads');
                        if (!empty($user->statehead_prfile->drug_licence_file)) {
                            $path = public_path($user->statehead_prfile->drug_licence_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }
                    $aadhaar_file = $user->statehead_prfile->aadhaar_file;
                    if ($_FILES['aadhaar_file']['error'] == 0) {
                        $aadhaar_file = $request->file('aadhaar_file')->store('uploads');
                        if (!empty($user->statehead_prfile->aadhaar_file)) {
                            $path = public_path($user->statehead_prfile->aadhaar_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }

                    
                    $user->statehead_prfile->organization_name = (empty($request->organization_name)) ? NULL : strip_tags(trim($request->organization_name));
                    $user->statehead_prfile->licence = (empty($request->licence)) ? NULL : strip_tags(trim($request->licence));
                    $user->statehead_prfile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));

                    $user->statehead_prfile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $user->statehead_prfile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));

                    $user->statehead_prfile->servicable_state_id = (empty($request->servicable_state_id)) ? NULL : strip_tags(trim($request->servicable_state_id));

                    $user->statehead_prfile->brand_logo_file  = (empty($brand_logo_file)) ? NULL : strip_tags(trim($brand_logo_file));
                    $user->statehead_prfile->gst_file         = (empty($gst_file)) ? NULL : strip_tags(trim($gst_file));
                    $user->statehead_prfile->drug_licence_file  = (empty($drug_licence_file)) ? NULL : strip_tags(trim($drug_licence_file));
                    $user->statehead_prfile->aadhaar_file     = (empty($aadhaar_file)) ? NULL : strip_tags(trim($aadhaar_file));
                    
                    $user->statehead_prfile->save();

                    $output = ['type'=>'success', 'message'=>'Successfully update state-head'];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }

        if (blank($user)) {
            return redirect()->route('admin.manage-user.statehead.index');
        }

        $servicable_state_ids = \App\Models\StateheadProfile::select('servicable_state_id')->get()->pluck('servicable_state_id')->unique()->toArray();
        if (empty($user->statehead_prfile->servicable_state_id)) {
            $data['unReserveStates'] = \App\Models\State::whereNotIn('id', array_filter($servicable_state_ids))->get();
        } else {
            $data['unReserveStates'] = \App\Models\State::whereNotIn('id', array_filter($servicable_state_ids))->orWhere('id', $user->statehead_prfile->servicable_state_id)->get();
        }

        $data['title'] = 'Edit State Head';
        $data['allstates'] = \App\Models\State::all();
        $data['user'] = $user;
        return view('admin.manage-user.statehead.statehead', $data);
    }
}
