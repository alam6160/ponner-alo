<?php

namespace App\Http\Controllers\Statehead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function dashboard(Request $request)
    {
        $data['statehead'] = \App\Models\User::find(Auth::id());
        $data['applicationPayment'] = \App\Models\ApplicationPayment::whereUserId(Auth::id())->latest('id')->first();
        return view('statehead.site.dashboard', $data);
    }
    public function apply_payment(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "payment_mode"   =>"required|in:1,2",
                "remark"         =>"required|string",
                "payment_proof"  =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                //dd($request->all());
                $applicationPayment = \App\Models\ApplicationPayment::where('user_id', Auth::id())->first();
                if (!blank($applicationPayment)) {
                    if ($applicationPayment->status == 1) {
                        return response()->json(['type'=>'error', 'message'=> 'Already applied & waiting approvation']);
                    }elseif ($applicationPayment->status == 2) {
                        return response()->json(['type'=>'error', 'message'=> 'Already Verified your payment request']);
                    }
                }

                $payment_proof = NULL;
                if ($_FILES['payment_proof']['error'] == 0) {
                    $payment_proof = $request->file('payment_proof')->store('uploads');
                }

                $stateheadProfile = \App\Models\StateheadProfile::select('amount')->where('statehead_id', Auth::id())->first();

                $applicationPayment = new \App\Models\ApplicationPayment();
                $applicationPayment->req_no = \App\Helper\Clib::unique_code(new \App\Models\ApplicationPayment(), 'REQ');
                $applicationPayment->user_id = Auth::id();
                $applicationPayment->department = 'statehead';
                $applicationPayment->payable_amount = $stateheadProfile->amount;
                $applicationPayment->payment_mode = strip_tags($request->payment_mode);
                $applicationPayment->remark = (empty($request->remark)) ? NULL : strip_tags(trim($request->remark));
                $applicationPayment->payment_proof = (empty($payment_proof)) ? NULL : $payment_proof;

                if ($applicationPayment->save()) {
                    $output = ['type'=>'success', 'message'=>'Successfully submit your request', 'applicationPayment'=> $applicationPayment];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                } 

            }
            return response()->json($output);
        }
    }
}
