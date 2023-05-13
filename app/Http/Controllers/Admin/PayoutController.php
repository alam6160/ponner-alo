<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\AgentPayout::with([
                'agent:id,fname,lname,email,contact'
            ])->orderBy('status', 'asc')->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<a href="'.route('admin.payout.view', ['id'=>$data->id]).'" class="btn-ancher text-success">View</a>';
                    return $btn;
                    
                })
                ->editColumn('receive_by', function($data) { 
                    $receive_by = ($data->receive_by == '1') ? 'Bank' : 'Other';
                    //return $receive_by;
                    return  "<a href='javascript:void(0)' data-accountdesc='$data->account_desc' onclick='viewAccountDesc(this)'>$receive_by</a>";
                })
                ->editColumn('status', function($data) { 
                    $status = '';
                    switch ($data->status) {
                        case '1':
                            $status = 'Pending';
                            break;
                        case '2':
                            $status = 'Complete';
                            break;
                        case '3':
                            $status = 'Cancel';
                            break;
                        
                        default:
                            $status = 'Pending';
                            break;
                    }
                    return $status;
                })
                ->editColumn('agent_name', function($data) { 
                    return $data->agent->fname.' '.$data->agent->lname;
                })
                ->editColumn('created_at', function($data) { 
                    return date('d-m-Y', strtotime($data->created_at));
                })
                ->rawColumns(['actions','receive_by'])
                ->make(true);
        }
        return view('admin.payout.index');
    }
    public function view(Request $request, $id)
    {
        $agentPayout = \App\Models\AgentPayout::with(['agent'])->find($id);
        if (blank($agentPayout)) {
            return redirect()->route('admin.payout.index');
        }
        $data['agentPayout'] = $agentPayout;
        return view('admin.payout.view', $data);
    }
    public function cancel(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "agent_payout_id"   =>"required|integer",
                "cancel_remark"        =>"nullable|string",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $agentPayout = \App\Models\AgentPayout::find($request->agent_payout_id);
                if (blank($agentPayout)) {
                    $output = ['type'=>'error', 'message'=> 'Application data not found...'];
                } else {

                    $agentPayout->status = '3';
                    $agentPayout->verification_at = \Carbon\Carbon::now()->toDateTimeString();;
                    $agentPayout->cancel_remark = (empty($request->cancel_remark)) ? NULL : strip_tags(trim($request->cancel_remark));

                    if ($agentPayout->save()) {

                        $agent = \App\Models\User::select(['id', 'wallet'])->withTrashed()->find( $agentPayout->agent_id );
                        $agent->wallet = floatval($agent->wallet) + floatval($agentPayout->amount);
                        $agent->save();

                        $output = ['type'=>'success', 'message'=>'Successfully cancel application', 'cancel_remark'=> $agentPayout->cancel_remark];
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
                "agent_payout_id"   =>"required|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $agentPayout = \App\Models\AgentPayout::find($request->agent_payout_id);
                if (blank($agentPayout)) {
                    $output = ['type'=>'error', 'message'=> 'Application data not found...'];
                } else {

                    $agentPayout->status = '2';
                    $agentPayout->verification_at = \Carbon\Carbon::now()->toDateTimeString();

                    if ($agentPayout->save()) {
                        $output = ['type'=>'success', 'message'=>'Successfully approve application'];
                    } else {
                        $output = ['type'=>'error', 'message'=> 'try again..'];
                    }     
                }
            }
            return response()->json($output);
        }
    }
}
