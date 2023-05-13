<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class ApplicationPaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $selectField = ['id','req_no','user_id','department','payable_amount','status','created_at'];
            
            $data = \App\Models\ApplicationPayment::select($selectField)->with('user:id,fname,lname,email,contact')->orderBy('status', 'asc')->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {
                    return '<a href="'.route('admin.application.payment.view', ['id'=> $data->id]).'" class="btn-ancher"><i class="fa fa-eye fa-lg"></i></a>';
                })
                ->addColumn('name', function($data){
                    return   $data->user->fname.' '.$data->user->lname;
                })
                ->addColumn('contact_det', function($data){
                    return   $data->user->email.'<br>'.$data->user->contact;
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
        return view('admin.application.payment.index', $data);
    }
    public function view(Request $request, $id)
    {
        $applicationPayment = \App\Models\ApplicationPayment::withTrashed()->find($id);
        


        if (blank($applicationPayment)) {
            return redirect()->route('admin.application.payment.index');
        }
        //dd($applicationPayment);
        $data['applicationPayment'] = $applicationPayment;
        return view('admin.application.payment.view', $data);
    }
    public function cancel(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "application_payment_id"    =>"required|integer",
                "cancel_remark"             =>"nullable|string",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $applicationPayment = \App\Models\ApplicationPayment::find($request->application_payment_id);
                if (blank($applicationPayment)) {
                    $output = ['type'=>'error', 'message'=> 'Request data not found...'];
                } else {

                    $applicationPayment->cancel_remark = (empty($request->cancel_remark)) ? NULL : strip_tags(trim($request->cancel_remark));
                    $applicationPayment->status = '3';
                    if ($applicationPayment->save()) {
                        
                        $output = ['type'=>'success', 'message'=>'Successfully cancel request', 'cancel_remark'=> $applicationPayment->cancel_remark];
                    } else {
                        $output = ['type'=>'error', 'message'=> 'try again..'];
                    }     
                }
            }
            return response()->json($output);
        }
    }
    public function approve(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "application_payment_id"    =>"required|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $applicationPayment = \App\Models\ApplicationPayment::find($request->application_payment_id);
                if (blank($applicationPayment)) {
                    $output = ['type'=>'error', 'message'=> 'Request data not found...'];
                } else {
                    $applicationPayment->status = '2';
                    $applicationPayment->approved_at = \Carbon\Carbon::now()->toDateTimeString();
                    if ($applicationPayment->save()) {
                        
                        \App\Models\User::whereId($applicationPayment->user_id)->update(
                            [
                                'status'=> '2',
                                'approve_at'=> \Carbon\Carbon::now()->toDateTimeString()
                            ]
                        );
                        $output = ['type'=>'success', 'message'=>'Successfully approve request'];
                    } else {
                        $output = ['type'=>'error', 'message'=> 'try again..'];
                    }     
                }
            }
            return response()->json($output);
        }
    }
}
