<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RetailerController extends Controller
{
    public function signup(Request $request)
    {
        $validator = validator($request->all(), [
            "title"                 =>"required|string",
            "fname"                 =>"required|string",
            "lname"                 =>"required|string",
            "email"                 =>"required|email|unique:users,email",
            "contact"               =>"required|regex:/^[0-9]{10}$/",
            "password"              =>"required|min:6",
            "cpassword"             =>"required|min:6|same:password",
            "licence"               =>"nullable|string",
            "address"               =>"nullable|string",
            "state_id"              =>"nullable|integer",
            "pin_code"              =>"nullable|integer",
            "user_code"             =>"required|string",
        ]);

        if ($validator->fails()) {
            $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
        } else {

            $agent = \App\Models\User::whereCode(trim($request->user_code))->whereUserType('4')->first();

            if (blank($agent)) {
                $output = ['type'=>'error', 'message'=> 'Invalid Agent ID'];
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

                if ($user->save()) {

                    $retailerProfile = new \App\Models\RetailerProfile();
                
                    $retailerProfile->agent_id = $agent->id;
                    $retailerProfile->statehead_id = NULL;
                    if (!empty($agent->agent_prfile->statehead_id)) {
                        $retailerProfile->statehead_id = $agent->agent_prfile->statehead_id;
                    }
                    
                    $retailerProfile->licence = (empty($request->licence)) ? NULL : strip_tags(trim($request->licence));
                    $retailerProfile->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));

                    $retailerProfile->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                    $retailerProfile->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));
                    
                    $user->retailer_prfile()->save($retailerProfile);

                    $output = ['type'=>'success', 'message'=>'Successfully create retailer'];

                    } else {
                        $output = ['type'=>'error', 'message'=> 'try again..'];
                    }
                } 
            
        }
        return response()->json($output);
    }
}
