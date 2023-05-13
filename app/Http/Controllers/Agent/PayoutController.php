<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\AgentPayout::whereAgentId(Auth::id())->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '';
                    if ($data->status == '3') {
                        $btn = '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-delete" data-cancelremark="'.$data->cancel_remark.'" title="View" onclick="cancelView(this)"><i class="fa fa-eye fa-lg"></i></a>';
                    }
                    
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
                ->editColumn('verification_at', function($data) { 
                    return (empty($data->verification_at)) ? '' : date('d-m-Y', strtotime($data->verification_at));
                })
                ->editColumn('created_at', function($data) { 
                    return date('d-m-Y', strtotime($data->created_at));
                })
                ->rawColumns(['actions','receive_by'])
                ->make(true);
        }
    }
    public function request(Request $request)
    {
        if ($request->ajax()) {
            $user = \App\Models\User::select(['id', 'wallet'])->find( Auth::id() );
            $maxamount = floatval($user->wallet);
            $validator = validator($request->all(), [
                "amount"        =>"required|numeric|max:$maxamount",
                "receive_by"    =>"required|in:1,2",
            ]);
            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $account_desc = [];
                $agentBank = \App\Models\AgentBank::whereAgentId(Auth::id())->first();
                if ($request->receive_by == '1') {
                    $account_desc['swift_code'] = $agentBank->swift_code;
                    $account_desc['bank_name'] = $agentBank->bank_name;
                    $account_desc['ifsc_code'] = $agentBank->ifsc_code;
                    $account_desc['account_no'] = $agentBank->account_no;
                    $account_desc['account_holder'] = $agentBank->account_holder;
                    $account_desc['bank_remark'] = $agentBank->bank_remark;
                }else{
                    $account_desc['other_info'] = $agentBank->other_info;
                    $account_desc['other_remark'] = $agentBank->other_remark;
                }

                $req_no = \App\Helper\Clib::unique_code( new \App\Models\AgentPayout(), 'REQ' );
                $agentPayout = new \App\Models\AgentPayout();
                $agentPayout->agent_id = Auth::id();
                $agentPayout->req_no = $req_no;
                $agentPayout->amount = floatval($request->amount);
                $agentPayout->receive_by = $request->receive_by;
                $agentPayout->account_desc = json_encode($account_desc);
                $agentPayout->save();
                $user->decrement('wallet', floatval($request->amount));

                $output = ['type'=>'success', 'message'=>'Successfully submit your request', 'maxamount'=> floatval($user->wallet)];

            }
            return response()->json($output);
        }
        return view('agent.payout.index');
    }
    public function creaditLog(Request $request)
    {

        if ($request->ajax()) {
        
            $data = \App\Models\AgentWalletlog::where('agent_id', Auth::id())->where('type', '1')->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    
                    $btn .='</div>';

                    return '';
                    
                })
                ->editColumn('grand_total', function($data) use($ccurrency) {
                    return  '<span>'.$ccurrency.''.$data->grand_total.'</span>';
                })
                ->editColumn('wallet', function($data) use($ccurrency) {
                    return  '<span>'.$ccurrency.''.$data->wallet.'</span>';
                })
                ->editColumn('commission', function($data) use($ccurrency) {
                    return  (empty($data->commission)) ? '' :  $data->commission.'%';
                })
                ->editColumn('admin_charge', function($data) use($ccurrency) {
                    if (empty($data->admin_charge)) {
                        return '';
                    } else {
                        return  '<span>'.$ccurrency.''.$data->admin_charge.'</span>';
                    }
                })
                ->editColumn('product_name', function($data) {
                    return '<span title="'.$data->product_name.'">'.\Illuminate\Support\Str::words($data->product_name, '3', '...') .'</span>';
                })
                ->addColumn('qty_sub_total', function($data) use($ccurrency) {
                    return '<span>'.$data->qty.' x '.$ccurrency.' '.$data->sub_total.'</span>';
                })
                ->rawColumns(['actions','product_name','grand_total','qty_sub_total','wallet','admin_charge'])
                ->make(true);
        }
        if ( Auth::user()->vendor_type == '1') {
            $data['table'] = collect([
                'order_no'      =>'Order No',
                'product_name'  =>'Product Name',
                'qty_sub_total' =>'Qty X Price',
                'grand_total'   =>'Total',
                'wallet'        =>'Wallet',
                'commission'    =>'Commission',
                'admin_charge'  =>'Admin Charge',
            ]);
        } else {
            $data['table'] = collect([
                'order_no'      =>'Order No',
                'product_name'  =>'Product Name',
                'qty_sub_total' =>'Qty X Price',
                'grand_total'   =>'Total',
                'wallet'        =>'Wallet',
                //'commission'    =>'Commission',
                //'admin_charge'  =>'Admin Charge',
            ]);
        }

        $data['title'] = 'Wallet Creadit Log';
        $data['module_title'] = 'Wallet';
        $data['indexURL'] = route('agent.payout.creaditlog');

        return view('agent.payout.creaditlog', $data);
    }
}
