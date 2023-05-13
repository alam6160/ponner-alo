<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function dashboard(Request $request)
    {
        $data['countDeliveredOrders'] = \App\Models\OrderDetails::where('status', '4')->count();
        $data['countReturnOrders'] = \App\Models\OrderDetails::where('status', '6')->where('return_status', '2')->count();
        $data['countOrders'] = \App\Models\OrderDetails::count();

        $data['countCustomers'] = \App\Models\User::where('user_type', '9')->count();

        $data['countVendors'] = \App\Models\User::where('user_type', '4')->count();
        $data['countRegVendors'] = \App\Models\User::where('user_type', '4')->where('vendor_type', '1')->count();
        $data['countSubVendors'] = \App\Models\User::where('user_type', '4')->where('vendor_type', '2')->count();
        $data['countProducts'] = \App\Models\Product::count();

        return view('admin.site.dashboard', $data);
    }
    public function edit_profile(Request $request)
    {
        if ($request->ajax()) {

            $uuid = Auth::id();
            $validator = validator($request->all(), [
                "fname"=>"required|string",
                "lname"=>"required|string",
                "email"=>"required|email|unique:users,email,{$uuid}",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                $user = \App\Models\User::find(auth()->user()->id);
                $user->fname  = trim($request->fname);
                $user->lname  = trim($request->lname);
                $user->email  = trim($request->email);
                if ($user->save()) {
                    $output = ['type'=>'success', 'message'=>'Successfully update prpfile'];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
                
            }
            return response()->json($output);
        }
        return view('admin.site.profile');
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
        return view('admin.site.chagepassword');
    }
    public function siteSetting(Request $request)
    {
        $sitesettings = \App\Models\SiteSetting::all();
        if ($request->ajax()) {
            $postData = $request->except(['_token']);
            $i = 0;
            foreach ($postData as $postkey => $value) {
                \App\Models\SiteSetting::whereKeyName($postkey)->update([
                    'key_value'=> (empty($value)) ? NULL : strip_tags(trim($value))   
                ]);
                $i++;
            }
            if ($i > 0) {
                $output = ['type'=>'success', 'message'=> 'Successfully Update'];
            } else {
                $output = ['type'=>'error', 'message'=> 'Not Update'];
            }
            return response()->json($output);
        }
        $data['sitesettings'] =  $sitesettings;
        return view('admin.site.site_setting', $data);
    }
    public function subscriptionLog(Request $request)
    {
        if ($request->ajax()) {
        
            
            $data = \App\Models\SubscriptionLog::with(['agent:id,code,fname,lname,email,contact'])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->addColumn('vendor_code', function($data) {
                    return  $data->agent->code;
                })
                ->addColumn('vendor_name', function($data) {
                    return $data->agent->fname.' '.$data->agent->lname;
                })
                ->editColumn('date', function($data) {
                    return date('d-m-Y', strtotime($data->date));
                })
                ->editColumn('expaire_date', function($data) {
                    return date('d-m-Y', strtotime($data->expaire_date));
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        $data['table'] = collect([
            'vendor_code'       =>'Vendor Code',
            'vendor_name'       =>'Vendor Name',
            'date'              =>'Date',
            'expaire_date'      =>'Expaired Date',
            'membership_price'  =>'Amount',
        ]);

        $data['title'] = 'Subscription History';
        $data['module_title'] = 'Subscription';
        $data['indexURL'] = route('admin.subscriptionlog');

        return view('admin.site.subscriptionlog', $data);
    }
  	public function uploadLogo(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "logo_file" =>"required|image|mimes:png|max:1048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                if (isset($_FILES['logo_file'])) {
                    if ($_FILES['logo_file']['error'] == 0) {

                        $file = $request->file('logo_file') ;
                        $fileName = $file->getClientOriginalName() ;
                        $destinationPath = public_path('assests/icon/');
                        $file->move($destinationPath,$fileName);
                        rename(public_path('assests/icon/'.$fileName), public_path('assests/icon/logo_1.png'));
                        $output = ['type'=>'success', 'message'=>'Successfully update logo'];
                    }
                }
            }
            return response()->json($output);
        }
    }
}
