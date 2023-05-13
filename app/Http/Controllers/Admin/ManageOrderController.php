<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class ManageOrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
        
            $data = \App\Models\OrderDetails::orderBy('status', 'asc')->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    $btn .= '<a href="'.route('admin.manageorder.details', ['order_key'=> $data->order_key]).'" role="button" class="btn-ancher text-primary" target="blank" title="View">View</a>';

                    if ($data->status == '1') {
                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="2" title="Processed">Processed</a>';

                        $btn .= '<a href="javascript:void(0)" id="orderstatus_'.$data->id.'" role="button" class="btn-ancher text-danger" data-row="'.$data->id.'" data-status="2" onclick="cancelOrder(this)" title="cancel">Cancel</a>';
                    }elseif ($data->status == '2') {

                        $btn .= '<a href="javascript:void(0)" role="button" id="orderstatus_'.$data->id.'" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="3" title="Shipped">Shipped</a>';

                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger" data-row="'.$data->id.'" data-status="2" onclick="cancelOrder(this)" title="cancel">Cancel</a>';
                    }elseif ($data->status == '3') {

                        $btn .= '<a href="javascript:void(0)" role="button" id="orderstatus_'.$data->id.'" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="4" title="Delivered">Delivered</a>';

                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger" data-row="'.$data->id.'" data-status="2" onclick="cancelOrder(this)" title="cancel">Cancel</a>';

                    }elseif ($data->status == '6') {

                        if ($data->return_status == '1') {
                            $approveStatus = json_encode(['id'=>$data->id, 'status'=> '2', 'type'=> '1']);
                            $rejectStatus = json_encode(['id'=>$data->id, 'status'=> '3', 'type'=> '2']);

                            $btn .= '<a href="javascript:void(0)" role="button" id="appreturnstatus_'.$data->id.'" class="btn-ancher text-success" data-info='.$approveStatus.' title="Approved" onclick="chageReturnStatus(this)">Approved</a>';

                            $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger" data-info='.$rejectStatus.' id="rejreturnstatus_'.$data->id.'" onclick="chageReturnStatus(this)" title="Reject">Reject</a>';
                        }
                    }
                    
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->editColumn('order_date', function($data) {
                    return  date('d-m-Y', strtotime($data->order_date));
                })
                ->editColumn('grand_total', function($data) use($ccurrency) {
                    return  '<span>'.$ccurrency.''.$data->grand_total.'</span>';
                })
                ->addColumn('status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->status);
                })
                ->addColumn('payment_mode', function($data) {
                    return \App\Helper\Helper::getOrderPaymentModel($data->payment_mode);
                })
                ->rawColumns(['actions','grand_total'])
                ->make(true);
        }
        $data['table'] = collect([
            'order_no'      =>'Order No',
            'order_date'    =>'Order Date',
            'grand_total'   =>'Grand Total',
            'status'        =>'Status',
            'payment_mode'  =>'Payment Mode',
        ]);
        $data['title'] = 'All Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('admin.manageorder.index');
        $data['statusURL'] = route('admin.manageorder.changestatus');
        $data['orderstatus'] = [
            [
                'label'=> 'Processed',
                'url'=> route('admin.manageorder.processed'),
            ],
            [
                'label'=> 'Shipped',
                'url'=> route('admin.manageorder.shipped'),
            ],
            [
                'label'=> 'Delivered',
                'url'=> route('admin.manageorder.delivered'),
            ],
            [
                'label'=> 'Cancel',
                'url'=> route('admin.manageorder.cancel'),
            ],
            [
                'label'=> 'Return',
                'url'=> route('admin.manageorder.return'),
            ],
        ];

        return view('admin.manage-order.index', $data);
    }
    public function recieved(Request $request)
    {
        if ($request->ajax()) {
        
            $data = \App\Models\OrderDetails::where([
                ['status','=', '1']
            ])->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    $btn .= '<a href="'.route('admin.manageorder.details', ['order_key'=> $data->order_key]).'" role="button" class="btn-ancher text-primary" target="blank" title="View">View</a>';

                    $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="2" title="Processed">Processed</a>';

                    $btn .= '<a href="javascript:void(0)" id="orderstatus_'.$data->id.'" role="button" class="btn-ancher text-danger" data-row="'.$data->id.'" data-status="2" onclick="cancelOrder(this)" title="cancel">Cancel</a>';
                    
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->editColumn('order_date', function($data) {
                    return  date('d-m-Y', strtotime($data->order_date));
                })
                ->editColumn('grand_total', function($data) use($ccurrency) {
                    return  '<span>'.$ccurrency.''.$data->grand_total.'</span>';
                })
                ->addColumn('status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->status);
                })
                ->addColumn('payment_mode', function($data) {
                    return \App\Helper\Helper::getOrderPaymentModel($data->payment_mode);
                })
                ->rawColumns(['actions','grand_total'])
                ->make(true);
        }
        $data['table'] = collect([
            'order_no'      =>'Order No',
            'order_date'    =>'Order Date',
            'grand_total'   =>'Grand Total',
            'status'        =>'Status',
            'payment_mode'  =>'Payment Mode',
        ]);
        $data['title'] = 'All Recieved Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('admin.manageorder.recieved');
        $data['statusURL'] = route('admin.manageorder.changestatus');
        $data['orderstatus'] = [
            [
                'label'=> 'All Orders',
                'url'=> route('admin.manageorder.index'),
            ],
            [
                'label'=> 'Processed',
                'url'=> route('admin.manageorder.processed'),
            ],
            [
                'label'=> 'Shipped',
                'url'=> route('admin.manageorder.shipped'),
            ],
            [
                'label'=> 'Delivered',
                'url'=> route('admin.manageorder.delivered'),
            ],
            [
                'label'=> 'Cancel',
                'url'=> route('admin.manageorder.cancel'),
            ],
            [
                'label'=> 'Return',
                'url'=> route('admin.manageorder.return'),
            ],
        ];

        return view('admin.manage-order.index', $data);
    }
    public function processed(Request $request)
    {
        if ($request->ajax()) {
        
            $data = \App\Models\OrderDetails::where([
                ['status','=', '2']
            ])->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    $btn .= '<a href="'.route('admin.manageorder.details', ['order_key'=> $data->order_key]).'" role="button" class="btn-ancher text-primary" target="blank" title="View">View</a>';

                    $btn .= '<a href="javascript:void(0)" role="button" id="orderstatus_'.$data->id.'" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="3" title="Shipped">Shipped</a>';

                    $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger" data-row="'.$data->id.'" data-status="2" onclick="cancelOrder(this)" title="cancel">Cancel</a>';
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->editColumn('order_date', function($data) {
                    return  date('d-m-Y', strtotime($data->order_date));
                })
                ->editColumn('grand_total', function($data) use($ccurrency) {
                    return  '<span>'.$ccurrency.''.$data->grand_total.'</span>';
                })
                ->addColumn('status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->status);
                })
                ->addColumn('payment_mode', function($data) {
                    return \App\Helper\Helper::getOrderPaymentModel($data->payment_mode);
                })
                ->rawColumns(['actions','grand_total'])
                ->make(true);
        }
        $data['table'] = collect([
            'order_no'      =>'Order No',
            'order_date'    =>'Order Date',
            'grand_total'   =>'Grand Total',
            'status'        =>'Status',
            'payment_mode'  =>'Payment Mode',
        ]);
        $data['title'] = 'All Processed Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('admin.manageorder.processed');
        $data['statusURL'] = route('admin.manageorder.changestatus');
        $data['orderstatus'] = [
            [
                'label'=> 'All Orders',
                'url'=> route('admin.manageorder.index'),
            ],
            [
                'label'=> 'Recieved',
                'url'=> route('admin.manageorder.recieved'),
            ],
            [
                'label'=> 'Shipped',
                'url'=> route('admin.manageorder.shipped'),
            ],
            [
                'label'=> 'Delivered',
                'url'=> route('admin.manageorder.delivered'),
            ],
            [
                'label'=> 'Cancel',
                'url'=> route('admin.manageorder.cancel'),
            ],
            [
                'label'=> 'Return',
                'url'=> route('admin.manageorder.return'),
            ],
        ];

        return view('admin.manage-order.index', $data);
    }
    public function shipped(Request $request)
    {
        if ($request->ajax()) {
        
            $data = \App\Models\OrderDetails::where([
                ['status','=', '3']
            ])->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    $btn .= '<a href="'.route('admin.manageorder.details', ['order_key'=> $data->order_key]).'" role="button" class="btn-ancher text-primary" target="blank" title="View">View</a>';

                    $btn .= '<a href="javascript:void(0)" role="button" id="orderstatus_'.$data->id.'" class="btn-ancher text-success btn-status" data-row="'.$data->id.'" data-status="4" title="Delivered">Delivered</a>';

                    $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger" data-row="'.$data->id.'" data-status="2" onclick="cancelOrder(this)" title="cancel">Cancel</a>';
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->editColumn('order_date', function($data) {
                    return  date('d-m-Y', strtotime($data->order_date));
                })
                ->editColumn('grand_total', function($data) use($ccurrency) {
                    return  '<span>'.$ccurrency.''.$data->grand_total.'</span>';
                })
                ->addColumn('status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->status);
                })
                ->addColumn('payment_mode', function($data) {
                    return \App\Helper\Helper::getOrderPaymentModel($data->payment_mode);
                })
                ->rawColumns(['actions','grand_total'])
                ->make(true);
        }
        $data['table'] = collect([
            'order_no'      =>'Order No',
            'order_date'    =>'Order Date',
            'grand_total'   =>'Grand Total',
            'status'        =>'Status',
            'payment_mode'  =>'Payment Mode',
        ]);
        $data['title'] = 'All Shipped Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('admin.manageorder.shipped');
        $data['statusURL'] = route('admin.manageorder.changestatus');
        $data['orderstatus'] = [
            [
                'label'=> 'All Orders',
                'url'=> route('admin.manageorder.index'),
            ],
            [
                'label'=> 'Recieved',
                'url'=> route('admin.manageorder.recieved'),
            ],
            [
                'label'=> 'Processed',
                'url'=> route('admin.manageorder.processed'),
            ],
            [
                'label'=> 'Delivered',
                'url'=> route('admin.manageorder.delivered'),
            ],
            [
                'label'=> 'Cancel',
                'url'=> route('admin.manageorder.cancel'),
            ],
            [
                'label'=> 'Return',
                'url'=> route('admin.manageorder.return'),
            ],
        ];

        return view('admin.manage-order.index', $data);
    }
    public function delivered(Request $request)
    {
        if ($request->ajax()) {
        
            $data = \App\Models\OrderDetails::where([
                ['status','=', '4']
            ])->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    $btn .= '<a href="'.route('admin.manageorder.details', ['order_key'=> $data->order_key]).'" role="button" class="btn-ancher text-primary" target="blank" title="View">View</a>';
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->editColumn('order_date', function($data) {
                    return  date('d-m-Y', strtotime($data->order_date));
                })
                ->editColumn('grand_total', function($data) use($ccurrency) {
                    return  '<span>'.$ccurrency.''.$data->grand_total.'</span>';
                })
                ->addColumn('status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->status);
                })
                ->addColumn('payment_mode', function($data) {
                    return \App\Helper\Helper::getOrderPaymentModel($data->payment_mode);
                })
                ->rawColumns(['actions','grand_total'])
                ->make(true);
        }
        $data['table'] = collect([
            'order_no'      =>'Order No',
            'order_date'    =>'Order Date',
            'grand_total'   =>'Grand Total',
            'status'        =>'Status',
            'payment_mode'  =>'Payment Mode',
        ]);
        $data['title'] = 'All Delivered Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('admin.manageorder.delivered');
        $data['statusURL'] = route('admin.manageorder.changestatus');
        $data['orderstatus'] = [
            [
                'label'=> 'All Orders',
                'url'=> route('admin.manageorder.index'),
            ],
            [
                'label'=> 'Recieved',
                'url'=> route('admin.manageorder.recieved'),
            ],
            [
                'label'=> 'Processed',
                'url'=> route('admin.manageorder.processed'),
            ],
            [
                'label'=> 'Shipped',
                'url'=> route('admin.manageorder.shipped'),
            ],
            [
                'label'=> 'Cancel',
                'url'=> route('admin.manageorder.cancel'),
            ],
            [
                'label'=> 'Return',
                'url'=> route('admin.manageorder.return'),
            ],
        ];

        return view('admin.manage-order.index', $data);
    }
    public function cancel(Request $request)
    {
        if ($request->ajax()) {
        
            $data = \App\Models\OrderDetails::where([
                ['status','=', '5']
            ])->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    $btn .= '<a href="'.route('admin.manageorder.details', ['order_key'=> $data->order_key]).'" role="button" class="btn-ancher text-primary" target="blank" title="View">View</a>';
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->editColumn('order_date', function($data) {
                    return  date('d-m-Y', strtotime($data->order_date));
                })
                ->editColumn('grand_total', function($data) use($ccurrency) {
                    return  '<span>'.$ccurrency.''.$data->grand_total.'</span>';
                })
                ->addColumn('status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->status);
                })
                ->addColumn('payment_mode', function($data) {
                    return \App\Helper\Helper::getOrderPaymentModel($data->payment_mode);
                })
                ->rawColumns(['actions','grand_total'])
                ->make(true);
        }
        $data['table'] = collect([
            'order_no'      =>'Order No',
            'order_date'    =>'Order Date',
            'grand_total'   =>'Grand Total',
            'status'        =>'Status',
            'payment_mode'  =>'Payment Mode',
        ]);
        $data['title'] = 'All Cancel Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('admin.manageorder.cancel');
        $data['statusURL'] = route('admin.manageorder.changestatus');
        $data['orderstatus'] = [
            [
                'label'=> 'All Orders',
                'url'=> route('admin.manageorder.index'),
            ],
            [
                'label'=> 'Recieved',
                'url'=> route('admin.manageorder.recieved'),
            ],
            [
                'label'=> 'Processed',
                'url'=> route('admin.manageorder.processed'),
            ],
            [
                'label'=> 'Shipped',
                'url'=> route('admin.manageorder.shipped'),
            ],
            [
                'label'=> 'Delivered',
                'url'=> route('admin.manageorder.delivered'),
            ],
            [
                'label'=> 'Return',
                'url'=> route('admin.manageorder.return'),
            ],
        ];

        return view('admin.manage-order.index', $data);
    }
    public function return(Request $request)
    {
        if ($request->ajax()) {
        
            $data = \App\Models\OrderDetails::where([
                ['status','=', '6']
            ])->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    $btn .= '<a href="'.route('admin.manageorder.details', ['order_key'=> $data->order_key]).'" role="button" class="btn-ancher text-primary" target="blank" title="View">View</a>';

                    if ($data->return_status == '1') {
                        $approveStatus = json_encode(['id'=>$data->id, 'status'=> '2', 'type'=> '1']);
                        $rejectStatus = json_encode(['id'=>$data->id, 'status'=> '3', 'type'=> '2']);

                        $btn .= '<a href="javascript:void(0)" role="button" id="appreturnstatus_'.$data->id.'" class="btn-ancher text-success" data-info='.$approveStatus.' title="Approved" onclick="chageReturnStatus(this)">Approved</a>';

                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-danger" data-info='.$rejectStatus.' id="rejreturnstatus_'.$data->id.'" onclick="chageReturnStatus(this)" title="Reject">Reject</a>';
                    }

                    $btn .='</div>';

                    return $btn;
                    
                })
                ->editColumn('order_date', function($data) {
                    return  date('d-m-Y', strtotime($data->order_date));
                })
                ->editColumn('grand_total', function($data) use($ccurrency) {
                    return  '<span>'.$ccurrency.''.$data->grand_total.'</span>';
                })
                ->addColumn('status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->status);
                })
                ->addColumn('rstatus', function($data) {
                    return \App\Helper\Helper::getOrderReturnStatus($data->return_status);
                })
                ->addColumn('payment_mode', function($data) {
                    return \App\Helper\Helper::getOrderPaymentModel($data->payment_mode);
                })
                ->rawColumns(['actions','grand_total'])
                ->make(true);
        }
        $data['table'] = collect([
            'order_no'      =>'Order No',
            'order_date'    =>'Order Date',
            'grand_total'   =>'Grand Total',
            'status'        =>'Status',
            'rstatus'       =>'Return Status',
            'payment_mode'  =>'Payment Mode',
        ]);
        $data['title'] = 'All Return Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('admin.manageorder.return');
        $data['statusURL'] = route('admin.manageorder.changestatus');
        $data['orderstatus'] = [
            [
                'label'=> 'All Orders',
                'url'=> route('admin.manageorder.index'),
            ],
            [
                'label'=> 'Recieved',
                'url'=> route('admin.manageorder.recieved'),
            ],
            [
                'label'=> 'Processed',
                'url'=> route('admin.manageorder.processed'),
            ],
            [
                'label'=> 'Shipped',
                'url'=> route('admin.manageorder.shipped'),
            ],
            [
                'label'=> 'Delivered',
                'url'=> route('admin.manageorder.delivered'),
            ],
            [
                'label'=> 'Cancel',
                'url'=> route('admin.manageorder.cancel'),
            ],
        ];

        return view('admin.manage-order.index', $data);
    }
    public function changestatus(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "id"=>"required|integer",
                "status"=>"required|in:1,2,3,4",
            ]);
            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                $orderDetails = \App\Models\OrderDetails::select(['id','status','delivery_date','return_last_date'])->find($request->id);
                if (blank($orderDetails)) {
                    $output = ['type'=>'error', 'message'=> 'Invalid Order Id'];
                } else {
                    $orderDetails->status = $request->status;
                    $orderDetails->save();

                    if ($orderDetails->status == '2') {
                        # code...
                    }elseif ($orderDetails->status == '3') {
                        # code...
                    }elseif ($orderDetails->status == '4') {

                        $dt = \Carbon\Carbon::now();
                        $return_last_date = $dt->addDays(7)->toDateTimeString();
                        $orderDetails->delivery_date = date('Y-m-d');
                        $orderDetails->return_last_date = $return_last_date;
                        $orderDetails->save();

                        $orderItem = \App\Models\OrderItem::where('order_detail_id', $orderDetails->id)->whereNotNull('agent_id')->get();
                        if (!blank($orderItem)) {
                            foreach ($orderItem as $key => $value) {

                                $product_desc = json_decode($value->product_desc);
                                $agent = \App\Models\User::select(['id', 'wallet'])->withTrashed()->find( $value->agent_id );
                                $agent->wallet = floatval($agent->wallet) + floatval($value->wallet);
                                $agent->save();

                                $agentWalletlog = new \App\Models\AgentWalletlog();
                                $agentWalletlog->agent_id = $value->agent_id;
                                $agentWalletlog->wallet = $value->wallet;
                                $agentWalletlog->commission = $value->commission;
                                $agentWalletlog->admin_charge = $value->admin_charge;
                                $agentWalletlog->order_detail_id = $value->order_detail_id;
                                $agentWalletlog->order_no = $value->order_no;
                                $agentWalletlog->product_id = $value->product_id;
                                $agentWalletlog->qty = $value->qty;
                                $agentWalletlog->sub_total = $value->sub_total;
                                $agentWalletlog->grand_total = $value->grand_total;
                                $agentWalletlog->product_name = $value->product_name;
                                $agentWalletlog->product_sku = $value->product_sku;
                                $agentWalletlog->product_image = (isset($product_desc->product_image)) ? $product_desc->product_image : NULL;
                                $agentWalletlog->type = '1';

                                $agentWalletlog->save();
                            }
                        }
                    }
                    $output = ['type'=>'success', 'message'=> 'Successfully chage status'];
                }
            }
            return response()->json($output);
        }
    }
    public function details(Request $request, $order_key)
    {
        $orderDetails = \App\Models\OrderDetails::with([
            'order_items', 
            'order_items.vendor:id,title,fname,lname,email,contact,code', 
            'customer:id,title,fname,lname,email,contact',
        ])->where('order_key',$order_key)->first();
        if (blank($orderDetails)) {
            return redirect()->route('admin.manageorder.recieved');
        }

        return view('admin.manage-order.details', ['orderDetails'=> $orderDetails]);

        /*
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "id"    =>"required|integer",
            ]);
            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                $orderDetails = \App\Models\OrderDetails::with('order_items')->find($request->id);
                if (blank($orderDetails)) {
                    $output = ['type'=>'error', 'message'=> 'Order data not found'];
                } else {
                    $html = view('admin.manage-order.ajaxview', ['orderDetails'=> $orderDetails])->render();
                    $output = ['type'=>'success', 'message'=> 'Successfully fetch data', 'html'=> $html];
                }
            }
            return response()->json($output);
        } */
    }
    public function cancelOrder(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "order_id"      =>"required|integer",
                "cancel_remark" =>"required|string",
            ]);
            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                $orderDetails = \App\Models\OrderDetails::select(['id','status','cancel_remark'])->find($request->order_id);
                if (blank($orderDetails)) {
                    $output = ['type'=>'error', 'message'=> 'Invalid Order Id'];
                } else {
                    $orderDetails->status = '5';
                    $orderDetails->cancel_remark = (empty($request->cancel_remark)) ? NULL : strip_tags($request->cancel_remark);
                    $orderDetails->save();
                    $output = ['type'=>'success', 'message'=> 'Successfully cancel order'];
                }
            }
            return response()->json($output);
        }
    }
    public function chageReturnStatus(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "id"        =>"required|integer",
                "status"    =>"required|in:2,3",
                "type"      =>"required|in:1,2",
            ]);
            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {
                $orderDetails = \App\Models\OrderDetails::where('status', '6')->find($request->id);
                if (blank($orderDetails)) {
                    $output = ['type'=>'error', 'message'=> 'Invalid Order Id'];
                } else {
                    
                    if ($request->status == '2') {
                        $orderDetails->return_status = $request->status;
                        $orderDetails->save();

                        $orderItem = \App\Models\OrderItem::where('order_detail_id', $orderDetails->id)->whereNotNull('agent_id')->get();
                        if (!blank($orderItem)) {
                            foreach ($orderItem as $key => $value) {

                                $product_desc = json_decode($value->product_desc);
                                $agent = \App\Models\User::select(['id', 'wallet'])->withTrashed()->find( $value->agent_id );

                                $agent->wallet = floatval($agent->wallet) - floatval($value->wallet);
                                $agent->save();

                                $agentWalletlog = new \App\Models\AgentWalletlog();
                                $agentWalletlog->agent_id = $value->agent_id;
                                $agentWalletlog->wallet = $value->wallet;
                                $agentWalletlog->commission = $value->commission;
                                $agentWalletlog->admin_charge = $value->admin_charge;
                                $agentWalletlog->order_detail_id = $value->order_detail_id;
                                $agentWalletlog->order_no = $value->order_no;
                                $agentWalletlog->product_id = $value->product_id;
                                $agentWalletlog->qty = $value->qty;
                                $agentWalletlog->sub_total = $value->sub_total;
                                $agentWalletlog->grand_total = $value->grand_total;
                                $agentWalletlog->product_name = $value->product_name;
                                $agentWalletlog->product_sku = $value->product_sku;
                                $agentWalletlog->product_image = (isset($product_desc->product_image)) ? $product_desc->product_image : NULL;
                                $agentWalletlog->type = '2';

                                $agentWalletlog->save();
                            }
                        }

                        $output = ['type'=>'success', 'message'=> 'Successfully approve return request'];
                    }elseif ($request->status == '3') {
                        $orderDetails->return_status = $request->status;
                        $orderDetails->save();
                        $output = ['type'=>'success', 'message'=> 'Successfully reject return request'];
                    }
                }
            }
            return response()->json($output);
        }
    }
}
