<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ManageOrderController extends Controller
{
    public function recieved(Request $request)
    {

        if ($request->ajax()) {
        
            $data = \App\Models\OrderItem::whereHas('order_details', function(Builder $query){
                $query->where('status', '1');
            })->where('agent_id', Auth::id())->with('order_details:id,status,order_date')->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->addColumn('order_status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->order_details->status);
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
                ->addColumn('order_date', function($data) {
                    return date('d-m-Y', strtotime($data->order_details->order_date));
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
                'order_status'  =>'Order Status',
                'order_date'    =>'Order Date',
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
                'order_status'  =>'Order Status',
                'order_date'    =>'Order Date',
                'product_name'  =>'Product Name',
                'qty_sub_total' =>'Qty X Price',
                'grand_total'   =>'Total',
                'wallet'        =>'Wallet',
                //'commission'    =>'Commission',
                //'admin_charge'  =>'Admin Charge',
            ]);
        }
        

        

        $data['title'] = 'All Recieved Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('agent.manageorder.recieved');

        return view('agent.manage-order.index', $data);
    }
    public function processed(Request $request)
    {

        if ($request->ajax()) {
        
            $data = \App\Models\OrderItem::whereHas('order_details', function(Builder $query){
                $query->where('status', '2');
            })->where('agent_id', Auth::id())->with('order_details:id,status,order_date')->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->addColumn('order_status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->order_details->status);
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
                ->addColumn('order_date', function($data) {
                    return date('d-m-Y', strtotime($data->order_details->order_date));
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
                'order_status'  =>'Order Status',
                'order_date'    =>'Order Date',
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
                'order_status'  =>'Order Status',
                'order_date'    =>'Order Date',
                'product_name'  =>'Product Name',
                'qty_sub_total' =>'Qty X Price',
                'grand_total'   =>'Total',
                'wallet'        =>'Wallet',
                //'commission'    =>'Commission',
                //'admin_charge'  =>'Admin Charge',
            ]);
        }
        

        

        $data['title'] = 'All Processed Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('agent.manageorder.processed');

        return view('agent.manage-order.index', $data);
    }
    public function shipped(Request $request)
    {

        if ($request->ajax()) {
        
            $data = \App\Models\OrderItem::whereHas('order_details', function(Builder $query){
                $query->where('status', '3');
            })->where('agent_id', Auth::id())->with('order_details:id,status,order_date')->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->addColumn('order_status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->order_details->status);
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
                ->addColumn('order_date', function($data) {
                    return date('d-m-Y', strtotime($data->order_details->order_date));
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
                'order_status'  =>'Order Status',
                'order_date'    =>'Order Date',
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
                'order_status'  =>'Order Status',
                'order_date'    =>'Order Date',
                'product_name'  =>'Product Name',
                'qty_sub_total' =>'Qty X Price',
                'grand_total'   =>'Total',
                'wallet'        =>'Wallet',
                //'commission'    =>'Commission',
                //'admin_charge'  =>'Admin Charge',
            ]);
        }
        

        

        $data['title'] = 'All Shipped Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('agent.manageorder.shipped');

        return view('agent.manage-order.index', $data);
    }
    public function delivered(Request $request)
    {

        if ($request->ajax()) {
        
            $data = \App\Models\OrderItem::whereHas('order_details', function(Builder $query){
                $query->where('status', '4');
            })->where('agent_id', Auth::id())->with('order_details:id,status,order_date')->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->addColumn('order_status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->order_details->status);
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
                ->addColumn('order_date', function($data) {
                    return date('d-m-Y', strtotime($data->order_details->order_date));
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
                'order_status'  =>'Order Status',
                'order_date'    =>'Order Date',
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
                'order_status'  =>'Order Status',
                'order_date'    =>'Order Date',
                'product_name'  =>'Product Name',
                'qty_sub_total' =>'Qty X Price',
                'grand_total'   =>'Total',
                'wallet'        =>'Wallet',
                //'commission'    =>'Commission',
                //'admin_charge'  =>'Admin Charge',
            ]);
        }
        

        

        $data['title'] = 'All Delivered Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('agent.manageorder.delivered');

        return view('agent.manage-order.index', $data);
    }
    public function cancel(Request $request)
    {

        if ($request->ajax()) {
        
            $data = \App\Models\OrderItem::whereHas('order_details', function(Builder $query){
                $query->where('status', '5');
            })->where('agent_id', Auth::id())->with('order_details:id,status,order_date')->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->addColumn('order_status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->order_details->status);
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
                ->addColumn('order_date', function($data) {
                    return date('d-m-Y', strtotime($data->order_details->order_date));
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
                'order_status'  =>'Order Status',
                'order_date'    =>'Order Date',
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
                'order_status'  =>'Order Status',
                'order_date'    =>'Order Date',
                'product_name'  =>'Product Name',
                'qty_sub_total' =>'Qty X Price',
                'grand_total'   =>'Total',
                'wallet'        =>'Wallet',
                //'commission'    =>'Commission',
                //'admin_charge'  =>'Admin Charge',
            ]);
        }
        

        

        $data['title'] = 'All Cancel Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('agent.manageorder.cancel');

        return view('agent.manage-order.index', $data);
    }
    public function return(Request $request)
    {

        if ($request->ajax()) {
        
            $data = \App\Models\OrderItem::whereHas('order_details', function(Builder $query){
                $query->where('status', '6');
            })->where('agent_id', Auth::id())->with('order_details:id,status,order_date')->get();

            $ccurrency = \App\Helper\Helper::ccurrency();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<div class="btn-group mb-2">';
                    
                    $btn .='</div>';

                    return $btn;
                    
                })
                ->addColumn('order_status', function($data) {
                    return \App\Helper\Helper::getOrderStatus($data->order_details->status);
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
                ->addColumn('order_date', function($data) {
                    return date('d-m-Y', strtotime($data->order_details->order_date));
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
                'order_status'  =>'Order Status',
                'order_date'    =>'Order Date',
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
                'order_status'  =>'Order Status',
                'order_date'    =>'Order Date',
                'product_name'  =>'Product Name',
                'qty_sub_total' =>'Qty X Price',
                'grand_total'   =>'Total',
                'wallet'        =>'Wallet',
                //'commission'    =>'Commission',
                //'admin_charge'  =>'Admin Charge',
            ]);
        }
        

        

        $data['title'] = 'All Return Orders';
        $data['module_title'] = 'Orders';
        $data['indexURL'] = route('agent.manageorder.return');

        return view('agent.manage-order.index', $data);
    }
}
