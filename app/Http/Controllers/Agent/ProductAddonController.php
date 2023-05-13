<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductAddonController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\ProductAddon::where('user_id', Auth::id())->withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    if ($data->trashed()) {

                        $btn = '<a href="javascript:void(0)" class="btn-ancher link-disable"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-delete" data-row="'.$data->id.'" data-status="1" title="Restore"><i class="fa fa-trash-o fa-lg"></i></a>';

                    } else {
                        $btn = '<a href="'.route('agent.product.addon.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "addon_title"   =>"required|string",
                "addon_price"   =>"required|numeric",
                "addon_desc"    =>"nullable|string",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $productAddon = new \App\Models\ProductAddon();
                $productAddon->addon_title  = strip_tags(trim($request->addon_title));
                $productAddon->addon_price  = strip_tags(trim($request->addon_price));
                $productAddon->addon_desc   = (empty($request->addon_desc)) ? NULL : strip_tags(trim($request->addon_desc));
                $productAddon->user_id = Auth::id();
                
                if ($productAddon->save()) {
                    
                    $output = ['type'=>'success', 'message'=>'Successfully create product addon'];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Addon';
        return view('agent.product.addon.index', $data);
    }
    public function edit(Request $request, $id)
    {
        $productAddon = \App\Models\ProductAddon::where('user_id', Auth::id())->find($id);

        if ($request->ajax()) {

            if (blank($productAddon)) {
                return ['type'=>'redirect', 'url'=> route('agent.product.addon.create'),'message'=>'This product addon is already deleted' ];
            }

            $validator = validator($request->all(), [
                "addon_title"   =>"required|string",
                "addon_price"   =>"required|numeric",
                "addon_desc"    =>"nullable|string",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $productAddon->addon_title  = strip_tags(trim($request->addon_title));
                $productAddon->addon_price  = strip_tags(trim($request->addon_price));
                $productAddon->addon_desc   = (empty($request->addon_desc)) ? NULL : strip_tags(trim($request->addon_desc));
                
                if ($productAddon->save()) {
                    
                    $output = ['type'=>'success', 'message'=>'Successfully updated product addon'];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        if (blank($productAddon)) {
            return redirect()->route('agent.product.addon.create');
        }
        $data['title'] = 'Edit Addon';
        $data['productAddon'] = $productAddon;
        return view('agent.product.addon.index', $data);
    }
}
