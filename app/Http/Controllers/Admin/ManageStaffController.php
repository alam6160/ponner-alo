<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ManageStaffController extends Controller
{
    public function index(Request $request)
    {
        $user_type = ['2','6','7','8'];
        if ($request->ajax()) {

            $select = ['users.*','staff_profiles.address','staff_profiles.pin_code'];

            $data = \App\Models\User::select($select)->join('staff_profiles','staff_profiles.user_id', '=','users.id')->whereIn('users.user_type', $user_type)->withTrashed()->get();

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
                        $btn = '<a href="'.route('admin.manage-user.staff.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        if ($data->status == 1) {

                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="2" title="Active"><i class="fa fa-user-plus fa-lg"></i></a>';

                        } else {
                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger btn-status" data-row="'.$data->id.'" data-status="1" title="Inactive"><i class="fa fa-user-times fa-lg"></i></a>';
                        }

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->addColumn('staff_det', function($data){
                    return $data->fname.' '.$data->lname;
                })
                ->addColumn('contact_det', function($data){
                    return $data->contact.'<br>'.$data->email;
                })
                ->addColumn('user_type', function($data){
                    return Helper::getUserType($data->user_type);
                })
                ->addColumn('active_status', function($data){
                    return ($data->status == '1') ? 'Inactive' : 'Active' ;
                })

                ->rawColumns(['actions','contact_det'])
                ->make(true);
        }

        $data['allstates'] = \App\Models\State::all();
        return view('admin.manage-user.staff.index', $data);
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
                "user_type"             =>"required|in:2,6,7,8",
                "address"               =>"nullable|string",
                "state_id"              =>"nullable|integer",
                "pin_code"              =>"nullable|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                $user = new \App\Models\User();
                $user->code         = \App\Helper\Clib::unique_code(new \App\Models\User(), 'S');
                $user->title        = strip_tags(trim($request->title));
                $user->fname        = strip_tags(trim($request->fname));
                $user->lname        = strip_tags(trim($request->lname));
                $user->email        = strip_tags(trim($request->email));
                $user->contact      = strip_tags(trim($request->contact));
                $user->password     = Hash::make(trim($request->password));
                $user->user_type    = strip_tags(trim($request->user_type));
                $user->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
                $user->approve_at = \Carbon\Carbon::now()->toDateTimeString();
                $user->status = '2';
                

                if ($user->save()) {

                    $staffProfile = new \App\Models\StaffProfile();

                    $staffProfile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));

                    $staffProfile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $staffProfile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));
                    
                    $user->staff_profile()->save($staffProfile);

                    $output = ['type'=>'success', 'message'=>'Successfully create staff'];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Staff';
        $data['allstates'] = \App\Models\State::all();
        return view('admin.manage-user.staff.staff', $data);
    }
    public function edit(Request $request, $id)
    {
        $user_type = ['2','6','7','8'];
        $user = \App\Models\User::whereIn('user_type', $user_type)->find($id);

        if ($request->ajax()) {

            if (blank($user)) {
                return ['type'=>'redirect', 'url'=> route('admin.manage-user.staff.index'),'message'=>'This staff is already delected' ];
            }

            $validator = validator($request->all(), [
                "title"     =>"required|string",
                "fname"     =>"required|string",
                "lname"     =>"required|string",
                "email"     =>"required|email|unique:users,email,{$id}",
                "contact"   =>"required|regex:/^[0-9]{10}$/",
                "password"  =>"nullable|min:6",
                "user_type" =>"required|in:2,6,7,8",
                "address"   =>"nullable|string",
                "state_id"  =>"nullable|integer",
                "pin_code"  =>"nullable|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                
                $user->title        = strip_tags(trim($request->title));
                $user->fname        = strip_tags(trim($request->fname));
                $user->lname        = strip_tags(trim($request->lname));
                $user->email        = strip_tags(trim($request->email));
                $user->contact      = strip_tags(trim($request->contact));
                $user->user_type    = strip_tags(trim($request->user_type));
                if (!empty($request->password)) {
                    $user->password     = Hash::make(trim($request->password));
                }

                if ($user->save()) {

                    
                    $user->staff_profile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));

                    $user->staff_profile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $user->staff_profile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));
                    
                    $user->staff_profile->save();

                    $output = ['type'=>'success', 'message'=>'Successfully updated staff'];

                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }
        if (blank($user)) {
            return redirect()->route('admin.manage-user.staff.index');
        }
        $data['title'] = 'Edit Staff';
        $data['allstates'] = \App\Models\State::all();
        $data['user'] = $user;
        return view('admin.manage-user.staff.staff', $data);
    }
}
