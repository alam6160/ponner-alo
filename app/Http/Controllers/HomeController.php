<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('fontend.home');
    }

    public function joinus(Request $request)
    {
        $agentReservePincodes = \App\Models\AgentProfile::selectRaw('GROUP_CONCAT(servicable_pincodes) as reservepincodes')->first();
        $retailerReservePincodes = \App\Models\RetailerProfile::selectRaw('GROUP_CONCAT(servicable_pincodes) as reservepincodes')->first();
        $servicable_state_ids = \App\Models\StateheadProfile::select('servicable_state_id')->get()->pluck('servicable_state_id')->unique()->toArray();


        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "title"                 =>"required|string",
                "fname"                 =>"required|string",
                "lname"                 =>"required|string",
                "email"                 =>"required|email",
                "contact"               =>"required|regex:/^[0-9]{10}$/",
                "department"            =>"required|in:hsp,retailer,statehead",
                "organization_name"     =>"nullable|string",
                "licence"               =>"nullable|string",
                "servicable_pincodes.*" =>"nullable|string|required_if:department,hsp,retailer",
                "servicable_state_id"   =>"nullable|string|required_if:department,statehead",
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

                if ($request->department == 'hsp') {

                    $reservepincodesArr = explode(',', $agentReservePincodes->reservepincodes);
                    $servicable_pincodes = $request->servicable_pincodes;
                    $commonPincodes = array_intersect($reservepincodesArr, $servicable_pincodes);

                    if (!empty($commonPincodes)) {
                        $message = "This ".implode(",", $commonPincodes)." pincodes are already Reserved";
                        return ['type'=>'error', 'message'=> $message];
                    }

                }elseif ($request->department == 'retailer') {
                    $reservepincodesArr = explode(',', $retailerReservePincodes->reservepincodes);
                    $servicable_pincodes = $request->servicable_pincodes;
                    $commonPincodes = array_intersect($reservepincodesArr, $servicable_pincodes);

                    if (!empty($commonPincodes)) {
                        $message = "This ".implode(",", $commonPincodes)." pincodes are already Reserved";
                        return ['type'=>'error', 'message'=> $message];
                    }
                }elseif ($request->department == 'statehead') {
                    if (in_array($request->servicable_state_id, $servicable_state_ids)) {
                        $message = "State are already Reserved";
                        return ['type'=>'error', 'message'=> $message];
                    }
                }

                $userApplication = new \App\Models\UserApplication();
                $userApplication->appl_no = \App\Helper\Clib::unique_code(new \App\Models\UserApplication(), 'APPL');
                
                $userApplication->department = (empty($request->department)) ? NULL : strip_tags(trim($request->department));
                $userApplication->title = (empty($request->title)) ? NULL : strip_tags(trim($request->title));
                $userApplication->fname = (empty($request->fname)) ? NULL : strip_tags(trim($request->fname)) ;
                $userApplication->lname = (empty($request->lname)) ? NULL : strip_tags(trim($request->lname)) ;
                $userApplication->email = (empty($request->email)) ? NULL : strip_tags(trim($request->email)) ;
                $userApplication->contact = (empty($request->contact)) ? NULL : strip_tags(trim($request->contact));

                $userApplication->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));
                $userApplication->state_id = (empty($request->state_id)) ? NULL : strip_tags(trim($request->state_id));
                $userApplication->pin_code = (empty($request->pin_code)) ? NULL : strip_tags(trim($request->pin_code));
                
                $userApplication->servicable_state_id = (empty($request->servicable_state_id)) ? NULL : strip_tags(trim($request->servicable_state_id));

                $userApplication->servicable_pincodes = NULL;
                if (!empty($request->servicable_pincodes)) {
                    $userApplication->servicable_pincodes = implode(",",$request->servicable_pincodes);
                }

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

                $jsoncontent = json_encode([
                    'organization_name'=> (empty($request->organization_name)) ? NULL : strip_tags(trim($request->organization_name)),
                    'licence'=> (empty($request->licence)) ? NULL : strip_tags(trim($request->licence)),
                    'brand_logo_file'   => $brand_logo_file,
                    'gst_file'          => $gst_file,
                    'drug_licence_file' => $drug_licence_file,
                    'aadhaar_file'      => $aadhaar_file,
                ]);

                $userApplication->content = $jsoncontent;
                if ($userApplication->save()) {
                    $output = ['type'=>'success', 'message'=>'Successfully submit your application'];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }   
            }
            return response()->json($output);
        }
        
        $data['agentReservePincodes'] = $agentReservePincodes;
        $data['retailerReservePincodes'] = $retailerReservePincodes;
        $data['stateReserveStates'] = \App\Models\State::whereNotIn('id', array_filter($servicable_state_ids))->get();
        $data['allstates'] = \App\Models\State::all();
        return view('fontend.joinus.joinus', $data);
    }
}
