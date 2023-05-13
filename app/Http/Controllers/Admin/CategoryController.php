<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Category::with('parent:id,cat_title')->withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    if ($data->trashed()) {

                        $btn = '<a href="javascript:void(0)" class="btn-ancher link-disable"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-delete" data-row="'.$data->id.'" data-status="1" title="Restore"><i class="fa fa-trash-o fa-lg"></i></a>';

                    } else {
                        $btn = '<a href="'.route('admin.product.category.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->addColumn('parent_cat', function($data){
                    return $data->parent->cat_title;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "cat_title" =>"required|string",
                //"slug"      =>"required|string|unique:categories,slug",
                "parent_id" =>"nullable|integer",
                "product_filter_id.*" =>"nullable|integer",
                "logo"      =>"nullable|image|mimes:jpg,jpeg,png,webp|max:2048",
                "thumbnail" =>"nullable|image|mimes:jpg,jpeg,png,webp|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $logo = NULL;
                if ($_FILES['logo']['error'] == 0) {
                    $logo = $request->file('logo')->store('uploads');
                }

                $thumbnail = NULL;
                if ($_FILES['thumbnail']['error'] == 0) {
                    $thumbnail = $request->file('thumbnail')->store('uploads');
                }

                $slug = \App\Helper\Clib::generateSlug(new \App\Models\Category(), $request->cat_title, 'cat_title');

                $category = new \App\Models\Category();
                $category->cat_title = strip_tags(trim($request->cat_title));
                $category->slug = $slug;
                $category->parent_id = (empty($request->parent_id)) ? NULL : strip_tags(trim($request->parent_id));

                $category->product_filter_id = (empty($request->product_filter_id)) ? NULL : json_encode( array_filter($request->product_filter_id) );
                $category->logo = (empty($logo)) ? NULL : strip_tags(trim($logo));
                $category->thumbnail = (empty($thumbnail)) ? NULL : strip_tags(trim($thumbnail));

                if ($category->save()) {
                    $parent_categories = \App\Models\Category::select(['id','cat_title'])->whereNull('parent_id')->get();

                    $files['logo'] = (empty($category->logo)) ? NULL : asset($category->logo) ;
                    $files['thumbnail'] = (empty($category->thumbnail)) ? NULL : asset($category->thumbnail) ;

                    $output = ['type'=>'success', 'message'=>'Successfully create category', 'parent_categories'=> $parent_categories, 'files'=> $files];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Category';
        $data['parent_categories'] = \App\Models\Category::select(['id','cat_title'])->whereNull('parent_id')->get();
        $data['parentFilters'] = \App\Models\ProductFilter::whereNull('parent_id')->get();
        return view('admin.product.category.category', $data);
    }
    public function edit(Request $request, $id)
    {
        $category = \App\Models\Category::find($id);
        if ($request->ajax()) {

            if (blank($category)) {
                return ['type'=>'redirect', 'url'=> route('admin.product.category.create'),'message'=>'This category is already deleted' ];
            }

            $validator = validator($request->all(), [
                "cat_title" =>"required|string",
                //"slug"      =>"required|string|unique:categories,slug,{$id}",
                "parent_id" =>"nullable|integer",
                "product_filter_id.*" =>"nullable|integer",
                "logo"      =>"nullable|image|mimes:jpg,jpeg,png,webp|max:2048",
                "thumbnail" =>"nullable|image|mimes:jpg,jpeg,png,webp|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $logo = $category->logo;
                if ($_FILES['logo']['error'] == 0) {
                    $logo = $request->file('logo')->store('uploads');
                    if (!empty($category->logo)) {
                        $path = public_path($category->logo);
                        if (is_file($path)) {
                            unlink($path);
                        }
                    }
                }

                $thumbnail = $category->thumbnail;
                if ($_FILES['thumbnail']['error'] == 0) {
                    $thumbnail = $request->file('thumbnail')->store('uploads');
                    if (!empty($category->thumbnail)) {
                        $path = public_path($category->thumbnail);
                        if (is_file($path)) {
                            unlink($path);
                        }
                    }
                }

                $slug = \App\Helper\Clib::generateSlug(new \App\Models\Category(), $request->cat_title, 'cat_title', $id);
                
                $category->cat_title = strip_tags(trim($request->cat_title));
                $category->slug = $slug;
                $category->parent_id = (empty($request->parent_id)) ? NULL : strip_tags(trim($request->parent_id));
                $category->product_filter_id = (empty($request->product_filter_id)) ? NULL : json_encode( array_filter($request->product_filter_id) );
                $category->logo = (empty($logo)) ? NULL : strip_tags(trim($logo));
                $category->thumbnail = (empty($thumbnail)) ? NULL : strip_tags(trim($thumbnail));

                if ($category->save()) {

                    $parent_categories = \App\Models\Category::select(['id','cat_title'])->whereNull('parent_id')->whereNot('id', $id)->get();

                    $output = ['type'=>'success', 'message'=>'Successfully create category', 'parent_categories'=> $parent_categories];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        if (blank($category)) {
            return redirect()->route('admin.product.category.create');
        }
        $data['title'] = 'Edit Category';
        $data['parent_categories'] = \App\Models\Category::select(['id','cat_title'])->whereNull('parent_id')->whereNot('id', $id)->get();
        $data['parentFilters'] = \App\Models\ProductFilter::whereNull('parent_id')->get();
        $data['category'] = $category;
        return view('admin.product.category.category', $data);
    }
}

