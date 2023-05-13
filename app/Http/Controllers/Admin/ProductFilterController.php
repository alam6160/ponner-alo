<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class ProductFilterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\ProductFilter::with('parent')->withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    if ($data->trashed()) {

                        $btn = '<a href="javascript:void(0)" class="btn-ancher link-disable"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-delete" data-row="'.$data->id.'" data-status="1" title="Restore"><i class="fa fa-trash-o fa-lg"></i></a>';

                    } else {
                        $btn = '<a href="'.route('admin.product.filter.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->addColumn('filter_values', function($data) {
                    return $data->parent->filter_title;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "filter_title"  =>"required|string",
                "parent_id"     =>"nullable|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $productFilter = new \App\Models\ProductFilter();
                $productFilter->filter_title  = strip_tags(trim($request->filter_title));
                $productFilter->parent_id = (empty($request->parent_id)) ? NULL : strip_tags(trim($request->parent_id)) ;
                if ($productFilter->save()) {
                    $parent_filters = \App\Models\ProductFilter::select(['id','filter_title'])->whereNull('parent_id')->get();
                    $output = ['type'=>'success', 'message'=>'Successfully create filter', 'parent_filters'=> $parent_filters];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Filter';
        $data['parentFilters'] = \App\Models\ProductFilter::whereNull('parent_id')->get();
        return view('admin.product.filter.index', $data);
    }
    public function edit(Request $request, $id)
    {
        $productFilter = \App\Models\ProductFilter::find($id);
        if ($request->ajax()) {

            if (blank($productFilter)) {
                return ['type'=>'redirect', 'url'=> route('admin.product.filter.create'),'message'=>'This product is already delected' ];
            }
            $validator = validator($request->all(), [
                "filter_title"  =>"required|string",
                "parent_id"     =>"nullable|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                
                $productFilter->filter_title  = strip_tags(trim($request->filter_title));
                $productFilter->parent_id = (empty($request->parent_id)) ? NULL : strip_tags(trim($request->parent_id)) ;

                if ($productFilter->save()) {
                    
                    $parent_filters = \App\Models\ProductFilter::select(['id','filter_title'])->whereNull('parent_id')->whereNot('id', $id)->get();

                    $output = ['type'=>'success', 'message'=>'Successfully updated filter', 'parent_filters'=> $parent_filters];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        if (blank($productFilter)) {
            return redirect()->route('admin.product.filter.create');
        }
        $data['title'] = 'Edit Filter';
        $data['productFilter'] = $productFilter;
        $data['parentFilters'] = \App\Models\ProductFilter::whereNull('parent_id')->whereNot('id', $id)->get();
        return view('admin.product.filter.index', $data);
    }
    public function values(Request $request, $id)
    {
        # code...
    }
}
