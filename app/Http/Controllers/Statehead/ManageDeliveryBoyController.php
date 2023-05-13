<?php

namespace App\Http\Controllers\Statehead;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ManageDeliveryBoyController extends Controller
{
    public function index(Request $request)
    {
        $user_type = '9';
        if ($request->ajax()) {

            $select = ['users.*','deliveryboy_profiles.address','deliveryboy_profiles.servicable_pincodes'];
           
            $data = \App\Models\User::select($select)->join('deliveryboy_profiles','deliveryboy_profiles.deliveryboy_id', '=','users.id')->where('users.user_type', $user_type)->where('deliveryboy_profiles.statehead_id', auth()->user()->id)->get();
            

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    if ($data->trashed()) {
                        $btn = '<a href="javascript:void(0)" class="btn-ancher link-disable"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                    } else {
                        $btn = '<a href="'.route('statehead.manage-user.deliveryboy.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';
                       
                    }
                    return $btn;
                    
                })
                ->addColumn('delboy_det', function($data){
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
        return view('statehead.manage-user.deliveryboy.index', $data);
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
                "driving_licence"       =>"nullable|string",
                "servicable_pincodes.*" =>"required|integer",
                "address"               =>"nullable|string",
                "state_id"              =>"nullable|integer",
                "pin_code"              =>"nullable|integer",
                "agent_id"              =>"required|integer",
                "driving_licence_file"  =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "aadhaar_file"          =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                $user = new \App\Models\User();
                $user->code         = \App\Helper\Clib::unique_code(new \App\Models\User(), 'DB');
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

                    $driving_licence_file = NULL;
                    if ($_FILES['driving_licence_file']['error'] == 0) {
                        $driving_licence_file = $request->file('driving_licence_file')->store('uploads');
                    }
                    $aadhaar_file = NULL;
                    if ($_FILES['aadhaar_file']['error'] == 0) {
                        $aadhaar_file = $request->file('aadhaar_file')->store('uploads');
                    }

                    $deliveryboyProfile = new \App\Models\DeliveryboyProfile();
                    
                    $deliveryboyProfile->agent_id = (empty($request->agent_id)) ? NULL : strip_tags(trim($request->agent_id));

                    $deliveryboyProfile->statehead_id = NULL;
                    if (!empty($request->agent_id)) {
                        $agentProfile = \App\Models\AgentProfile::select(['statehead_id'])->whereAgentId($request->agent_id)->first();
                        $deliveryboyProfile->statehead_id = (empty($agentProfile->statehead_id)) ? NULL : $agentProfile->statehead_id ;
                    }

                    $deliveryboyProfile->driving_licence = (empty($request->driving_licence)) ? NULL : strip_tags(trim($request->driving_licence));

                    $deliveryboyProfile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));
                    $deliveryboyProfile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $deliveryboyProfile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));

                    $deliveryboyProfile->servicable_pincodes = (empty($request->servicable_pincodes)) ? NULL : implode(',', $request->servicable_pincodes);

                    $deliveryboyProfile->driving_licence_file  = (empty($driving_licence_file)) ? NULL : strip_tags(trim($driving_licence_file));
                    $deliveryboyProfile->aadhaar_file     = (empty($aadhaar_file)) ? NULL : strip_tags(trim($aadhaar_file));
                    
                    $user->deliveryboy_profile()->save($deliveryboyProfile);

                    $output = ['type'=>'success', 'message'=>'Successfully create delivery boy'];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Delivery Boy';
        $data['allstates'] = \App\Models\State::all();
        $data['allagents'] = \App\Models\User::whereHas('agent_prfile', function(\Illuminate\Database\Eloquent\Builder $query){
            $query->where('statehead_id', auth()->user()->id);
        })->whereUserType('4')->get();
        return view('statehead.manage-user.deliveryboy.deliveryboy', $data);
    }
    public function edit(Request $request, $id)
    {
        $user_type = '9';

        $user = \App\Models\User::whereHas('deliveryboy_profile', function(\Illuminate\Database\Eloquent\Builder $query){
            $query->where('statehead_id', auth()->user()->id);
        })->whereUserType($user_type)->find($id);

        if ($request->ajax()) {

            if (blank($user)) {
                return ['type'=>'redirect', 'url'=> route('statehead.manage-user.deliveryboy.index'),'message'=>'This delivery boy is already delected' ];
            }

            $validator = validator($request->all(), [
                "title"                 =>"required|string",
                "fname"                 =>"required|string",
                "lname"                 =>"required|string",
                "email"                 =>"required|email|unique:users,email,{$id}",
                "contact"               =>"required|regex:/^[0-9]{10}$/",
                "password"              =>"nullable|min:6",
                "driving_licence"       =>"nullable|string",
                "servicable_pincodes.*" =>"nullable|integer",
                "address"               =>"nullable|string",
                "state_id"              =>"nullable|integer",
                "pin_code"              =>"nullable|integer",
                "agent_id"              =>"required|integer",
                "driving_licence_file"  =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
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

                    $driving_licence_file = $user->deliveryboy_profile->driving_licence_file;
                    if ($_FILES['driving_licence_file']['error'] == 0) {
                        $driving_licence_file = $request->file('driving_licence_file')->store('uploads');
                        if (!empty($user->deliveryboy_profile->driving_licence_file)) {
                            $path = public_path($user->deliveryboy_profile->driving_licence_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }

                    $aadhaar_file = $user->deliveryboy_profile->aadhaar_file;
                    if ($_FILES['aadhaar_file']['error'] == 0) {
                        $aadhaar_file = $request->file('aadhaar_file')->store('uploads');
                        if (!empty($user->deliveryboy_profile->aadhaar_file)) {
                            $path = public_path($user->deliveryboy_profile->aadhaar_file);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }

                    
                    $user->deliveryboy_profile->agent_id = (empty($request->agent_id)) ? NULL : strip_tags(trim($request->agent_id));
                    

                    $user->deliveryboy_profile->driving_licence = (empty($request->driving_licence)) ? NULL : strip_tags(trim($request->driving_licence));
                    $user->deliveryboy_profile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));

                    $user->deliveryboy_profile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $user->deliveryboy_profile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));

                    $user->deliveryboy_profile->servicable_pincodes = (empty($request->servicable_pincodes)) ? NULL : implode(',', $request->servicable_pincodes);

                    $user->deliveryboy_profile->driving_licence_file  = (empty($driving_licence_file)) ? NULL : strip_tags(trim($driving_licence_file));
                    $user->deliveryboy_profile->aadhaar_file     = (empty($aadhaar_file)) ? NULL : strip_tags(trim($aadhaar_file));
                    
                    $user->deliveryboy_profile->save();

                    $output = ['type'=>'success', 'message'=>'Successfully update delivery boy'];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }

        if (blank($user)) {
            return redirect()->route('statehead.manage-user.deliveryboy.index');
        }
        $data['title'] = 'Edit Delivery Boy';
        $data['allstates'] = \App\Models\State::all();
        $data['user'] = $user;
        $data['allagents'] = \App\Models\User::whereHas('agent_prfile', function(\Illuminate\Database\Eloquent\Builder $query){
            $query->where('statehead_id', auth()->user()->id);
        })->whereUserType('4')->get();
        return view('statehead.manage-user.deliveryboy.deliveryboy', $data);
    }
}
